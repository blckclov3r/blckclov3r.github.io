<?php
namespace WpAssetCleanUp\OptimiseAssets;

use WpAssetCleanUp\Main;
use WpAssetCleanUp\Menu;
use WpAssetCleanUp\FileSystem;
use WpAssetCleanUp\Misc;
use WpAssetCleanUp\ObjectCache;
use WpAssetCleanUp\Preloads;

/**
 * Class CombineCss
 * @package WpAssetCleanUp\OptimiseAssets
 */
class CombineCss
{
	/**
	 * @var string
	 */
	public static $jsonStorageFile = 'css-combined{maybe-extra-info}.json';

	/**
	 * @param $htmlSource
	 *
	 * @return mixed
	 */
	public static function doCombine($htmlSource)
	{
		if (! (function_exists('libxml_use_internal_errors') && function_exists('libxml_clear_errors') && class_exists('\DOMDocument')) && class_exists('\DOMXpath')) {
			return $htmlSource;
		}

		if ( ! self::proceedWithCssCombine() ) {
			return $htmlSource;
		}

		global $wp_styles;
		$wpacuRegisteredStyles = $wp_styles->registered;

		// Speed up processing by getting the already existing final CSS file URI
		// This will avoid parsing the HTML DOM and determine the combined URI paths for all the CSS files
		$storageJsonContents = OptimizeCommon::getAssetCachedData(self::$jsonStorageFile, OptimizeCss::getRelPathCssCacheDir(), 'css');

		// $uriToFinalCssFile will always be relative ONLY within WP_CONTENT_DIR . self::getRelPathCssCacheDir()
		// which is usually "wp-content/cache/asset-cleanup/css/"

		$skipCache = false; // default

		if (isset($_GET['wpacu_no_cache'])) {
			$skipCache = true;
		}

		if ( $skipCache || empty($storageJsonContents) ) {
			$storageJsonContentsToSave = array();

			/*
			 * NO CACHING? Parse the DOM
			*/
			// Nothing in the database records or the retrieved cached file does not exist?
			OptimizeCommon::clearAssetCachedData(self::$jsonStorageFile);

			$storageJsonContents = array();

			$domTag = OptimizeCommon::getDomLoadedTag($htmlSource, 'combineCss');

			foreach (array('head', 'body') as $docLocationTag) {
				$combinedUriPathsGroup = $localAssetsPathsGroup = $linkHrefsGroup = array();
				$localAssetsExtraGroup = array();

				$docLocationElements = $domTag->getElementsByTagName($docLocationTag)->item(0);

				if ($docLocationElements === null) { continue; }

				$xpath = new \DOMXpath($domTag);
				$linkTags = $xpath->query('/html/'.$docLocationTag.'/link[@rel="stylesheet"] | /html/'.$docLocationTag.'/link[@rel="preload"]');
				if ($linkTags === null) { continue; }

				foreach ($linkTags as $tagObject) {
					$linkAttributes = array();
					foreach ($tagObject->attributes as $attrObj) { $linkAttributes[$attrObj->nodeName] = trim($attrObj->nodeValue); }

					// Only rel="stylesheet" (with no rel="preload" associated with it) gets prepared for combining as links with rel="preload" (if any) are never combined into a standard render-blocking CSS file
					// rel="preload" is there for a reason to make sure the CSS code is made available earlier prior to the one from rel="stylesheet" which is render-blocking
					if (isset($linkAttributes['rel'], $linkAttributes['href']) && $linkAttributes['href']) {
						$href = (string) $linkAttributes['href'];

						// Separate each combined group by the "media" attribute; e.g. we don't want "all" and "print" mixed
						$mediaValue = (array_key_exists('media', $linkAttributes) && $linkAttributes['media']) ? $linkAttributes['media'] : 'all';

						// Check if there is any rel="preload" (Basic) connected to the rel="stylesheet"
						// making sure the file is not added to the final CSS combined file
						if (isset($linkAttributes['data-wpacu-style-handle']) &&
						    $linkAttributes['data-wpacu-style-handle'] &&
						    ObjectCache::wpacu_cache_get($linkAttributes['data-wpacu-style-handle'], 'wpacu_basic_preload_handles')) {
							$mediaValue = 'wpacu_preload_basic_' . $mediaValue;
						}

						// Check if the CSS file has any 'data-wpacu-skip' attribute; if it does, do not alter it
						if (isset($linkAttributes['data-wpacu-skip'])) {
							continue;
						}

						if (self::skipCombine($linkAttributes['href'])) {
							continue;
						}

						// Make the right reference for later use
						if ($linkAttributes['rel'] === 'preload') {
							if (isset($linkAttributes['data-wpacu-preload-css-basic'])) {
								$mediaValue = 'wpacu_preload_basic_' . $mediaValue;
							} else {
								continue;
							}
						}

						// Was it optimized and has the URL updated? Check the Source URL to determine if it should be skipped from combining
						if (isset($linkAttributes['data-wpacu-link-rel-href-before']) && $linkAttributes['data-wpacu-link-rel-href-before'] && self::skipCombine($linkAttributes['data-wpacu-link-rel-href-before'])) {
							continue;
						}

						// Avoid combining own plugin's CSS (irrelevant) as it takes extra useless space in the caching directory
						if (isset($linkAttributes['id']) && $linkAttributes['id'] === WPACU_PLUGIN_ID.'-style-css') {
							continue;
						}

						$localAssetPath = OptimizeCommon::getLocalAssetPath($href, 'css');

						// It will skip external stylesheets (from a different domain)
						if ( $localAssetPath ) {
							$styleExtra = array();

							if (isset($linkAttributes['data-wpacu-style-handle'], $wpacuRegisteredStyles[$linkAttributes['data-wpacu-style-handle']]->extra) && Main::instance()->settings['_combine_loaded_css_append_handle_extra']) {
								$styleExtra = $wpacuRegisteredStyles[$linkAttributes['data-wpacu-style-handle']]->extra;
							}

							$combinedUriPathsGroup[$mediaValue][]      = OptimizeCommon::getSourceRelPath($href);
							$localAssetsPathsGroup[$mediaValue][$href] = $localAssetPath;
							$linkHrefsGroup[$mediaValue][]             = $href;
							$localAssetsExtraGroup[$mediaValue][$href] = $styleExtra;
						}
					}
				}

				// No Link Tags or only one tag in the combined group? Do not proceed with any combining
				if ( empty( $combinedUriPathsGroup ) ) {
					continue;
				}

				foreach ($combinedUriPathsGroup as $mediaValue => $combinedUriPaths) {
					// There have to be at least two CSS files to create a combined CSS file
					if (count($combinedUriPaths) < 2) {
						continue;
					}

					$localAssetsPaths = $localAssetsPathsGroup[$mediaValue];
					$linkHrefs = $linkHrefsGroup[$mediaValue];
					$localAssetsExtra = array_filter($localAssetsExtraGroup[$mediaValue]);

					$shaOneForCombinedCss = self::generateShaOneForCombinedCss($combinedUriPaths, $localAssetsExtra);

					$maybeDoCssCombine = self::maybeDoCssCombine(
						$shaOneForCombinedCss,
						$localAssetsPaths, $linkHrefs,
						$localAssetsExtra,
						$docLocationTag
					);

					// Local path to combined CSS file
					$localFinalCssFile = $maybeDoCssCombine['local_final_css_file'];

					// URI (e.g. /wp-content/cache/asset-cleanup/[file-name-here.css]) to the combined CSS file
					$uriToFinalCssFile = $maybeDoCssCombine['uri_final_css_file'];

					// Any link hrefs removed perhaps if the file wasn't combined?
					$linkHrefs = $maybeDoCssCombine['link_hrefs'];

					if (is_file($localFinalCssFile)) {
						$storageJsonContents[$docLocationTag][$mediaValue] = array(
							'uri_to_final_css_file' => $uriToFinalCssFile,
							'link_hrefs'            => array_map(static function($href) {
								return str_replace('{site_url}', '', OptimizeCommon::getSourceRelPath($href));
							}, $linkHrefs)
						);

						$storageJsonContentsToSave[$docLocationTag][$mediaValue] = array(
							'uri_to_final_css_file' => $uriToFinalCssFile,
							'link_hrefs'            => array_map(static function($href) {
								return OptimizeCommon::getSourceRelPath($href);
							}, $linkHrefs)
						);
					}
				}
			}

			libxml_clear_errors();

			OptimizeCommon::setAssetCachedData(
				self::$jsonStorageFile,
				OptimizeCss::getRelPathCssCacheDir(),
				json_encode($storageJsonContentsToSave)
			);
		}

		$cdnUrls = OptimizeCommon::getAnyCdnUrls();
		$cdnUrlForCss = isset($cdnUrls['css']) ? $cdnUrls['css'] : false;

		if ( ! empty($storageJsonContents) ) {
			foreach ($storageJsonContents as $docLocationTag => $mediaValues) {
				$groupLocation = 1;

				foreach ($mediaValues as $mediaValue => $storageJsonContentLocation) {
					if (! isset($storageJsonContentLocation['link_hrefs'][0])) {
						continue;
					}

					// Irrelevant to have only one CSS file in a combine CSS group
					if (count($storageJsonContentLocation['link_hrefs']) < 2) {
						continue;
					}

					$storageJsonContentLocation['link_hrefs'] = array_map(static function($href) {
						return str_replace('{site_url}', '', $href);
					}, $storageJsonContentLocation['link_hrefs']);

					$finalTagUrl = OptimizeCommon::filterWpContentUrl($cdnUrlForCss) . OptimizeCss::getRelPathCssCacheDir() . $storageJsonContentLocation['uri_to_final_css_file'];

					$finalCssTagAttrs = array();

					if (strpos($mediaValue, 'wpacu_preload_basic_') === 0) {
						// Put the right "media" value after cleaning the reference
						$mediaValueClean = str_replace('wpacu_preload_basic_', '', $mediaValue);

						// Basic Preload
						$finalCssTag = <<<HTML
<link rel='stylesheet' data-wpacu-to-be-preloaded-basic='1' id='wpacu-combined-css-{$docLocationTag}-{$groupLocation}-preload-it-basic' href='{$finalTagUrl}' type='text/css' media='{$mediaValueClean}' />
HTML;
						$finalCssTagRelPreload = <<<HTML
<link rel='preload' as='style' data-wpacu-preload-it-basic='1' id='wpacu-combined-css-{$docLocationTag}-{$groupLocation}-preload-it-basic' href='{$finalTagUrl}' type='text/css' media='{$mediaValueClean}' />
HTML;

						$finalCssTagAttrs['rel']   = 'preload';
						$finalCssTagAttrs['media'] = $mediaValueClean;

						$htmlSource = str_replace(Preloads::DEL_STYLES_PRELOADS, $finalCssTagRelPreload."\n" . Preloads::DEL_STYLES_PRELOADS, $htmlSource);
					} else {
						// Render-blocking CSS
						$finalCssTag = <<<HTML
<link rel='stylesheet' id='wpacu-combined-css-{$docLocationTag}-{$groupLocation}' href='{$finalTagUrl}' type='text/css' media='{$mediaValue}' />
HTML;
						$finalCssTagAttrs['rel']   = 'stylesheet';
						$finalCssTagAttrs['media'] = $mediaValue;
					}

					// In case one (e.g. usually a developer) needs to alter it
					$finalCssTag = apply_filters(
						'wpacu_combined_css_tag',
						$finalCssTag,
						array(
							'attrs'        => $finalCssTagAttrs,
							'doc_location' => $docLocationTag,
							'group_no'     => $groupLocation,
							'href'         => $finalTagUrl
						)
					);

					// Reference: https://stackoverflow.com/questions/2368539/php-replacing-multiple-spaces-with-a-single-space
					$finalCssTag = preg_replace('!\s+!', ' ', $finalCssTag);

					$htmlSourceBeforeAnyLinkTagReplacement = $htmlSource;

					// Detect first LINK tag from the <$locationTag> and replace it with the final combined LINK tag
					$firstLinkTag = OptimizeCss::getFirstLinkTag($storageJsonContentLocation['link_hrefs'][0], $htmlSource);

					if ($firstLinkTag) {
						// 1) Strip inline code before/after it (if any)
						// 2) Finally, strip the actual tag
						$htmlSource = self::stripTagAndAnyInlineAssocCode( $firstLinkTag, $wpacuRegisteredStyles, $finalCssTag, $htmlSource );
					}

					if ($htmlSource !== $htmlSourceBeforeAnyLinkTagReplacement) {
						$htmlSource = self::stripJustCombinedLinkTags(
							$storageJsonContentLocation['link_hrefs'],
							$wpacuRegisteredStyles,
							$htmlSource
						); // Strip the combined files to avoid duplicate code

						// There should be at least two replacements made AND all the tags should have been replaced
						// Leave no room for errors, otherwise the page could end up with extra files loaded, leading to a slower website
						if ($htmlSource === 'do_not_combine') {
							$htmlSource = $htmlSourceBeforeAnyLinkTagReplacement;
						} else {
							$groupLocation++;
						}
					}
				}
			}
		}

		return $htmlSource;
	}

	/**
	 * @param $filesSources
	 * @param $wpacuRegisteredStyles
	 * @param $htmlSource
	 *
	 * @return mixed
	 */
	public static function stripJustCombinedLinkTags($filesSources, $wpacuRegisteredStyles, $htmlSource)
	{
		preg_match_all('#<link[^>]*(stylesheet|preload)[^>]*(>)#Umi', $htmlSource, $matchesSourcesFromTags, PREG_SET_ORDER);

		$linkTagsStrippedNo = 0;

		foreach ($matchesSourcesFromTags as $matchSourceFromTag) {
			$matchedSourceFromTag = (isset($matchSourceFromTag[0]) && strip_tags($matchSourceFromTag[0]) === '') ? trim($matchSourceFromTag[0]) : '';

			if (! $matchSourceFromTag) {
				continue;
			}

			// The DOMDocument is already checked if it's enabled in doCombine()
			$domTag = new \DOMDocument();

			libxml_use_internal_errors(true);
			$domTag->loadHTML($matchedSourceFromTag);

			foreach ($domTag->getElementsByTagName('link') as $tagObject) {
				if (empty($tagObject->attributes)) { continue; }

				foreach ($tagObject->attributes as $tagAttrs) {
					if ($tagAttrs->nodeName === 'href') {
						$relNodeValue = trim(OptimizeCommon::getSourceRelPath($tagAttrs->nodeValue));

						if (in_array($relNodeValue, $filesSources)) {
							$htmlSourceBeforeLinkTagReplacement = $htmlSource;

							// 1) Strip inline code before/after it (if any)
							// 2) Finally, strip the actual tag
							$htmlSource = self::stripTagAndAnyInlineAssocCode( $matchedSourceFromTag, $wpacuRegisteredStyles, '', $htmlSource );

							if ($htmlSource !== $htmlSourceBeforeLinkTagReplacement) {
								$linkTagsStrippedNo++;
							}
							continue;
						}
					}
				}
			}

			libxml_clear_errors();
		}

		// Aren't all the LINK tags stripped? They should be, otherwise, do not proceed with the HTML alteration (no combining will take place)
		// Minus the already combined tag
		if (($linkTagsStrippedNo < 2) && (count($filesSources) !== $linkTagsStrippedNo)) {
			return 'do_not_combine';
		}

		return $htmlSource;
	}

	/**
	 * @param $href
	 *
	 * @return bool
	 */
	public static function skipCombine($href)
	{
		$regExps = array(
			'#/wp-content/bs-booster-cache/#',
		);

		if (Main::instance()->settings['combine_loaded_css_exceptions'] !== '') {
			$loadedCssExceptionsPatterns = trim(Main::instance()->settings['combine_loaded_css_exceptions']);

			if (strpos($loadedCssExceptionsPatterns, "\n")) {
				// Multiple values (one per line)
				foreach (explode("\n", $loadedCssExceptionsPatterns) as $loadedCssExceptionPattern) {
					$regExps[] = '#'.trim($loadedCssExceptionPattern).'#';
				}
			} else {
				// Only one value?
				$regExps[] = '#'.trim($loadedCssExceptionsPatterns).'#';
			}
		}

		// No exceptions set? Do not skip combination
		if (empty($regExps)) {
			return false;
		}

		foreach ($regExps as $regExp) {
			if ( preg_match( $regExp, $href ) ) {
				// Skip combination
				return true;
			}
		}

		return false;
	}

	/**
	 * @param $shaOneForCombinedCss
	 * @param $localAssetsPaths
	 * @param $linkHrefs
	 * @param $localAssetsExtra
	 * @param $docLocationTag
	 *
	 * @return array
	 */
	public static function maybeDoCssCombine($shaOneForCombinedCss, $localAssetsPaths, $linkHrefs, $localAssetsExtra, $docLocationTag)
	{
		$uriToFinalCssFile = $docLocationTag . '-' .$shaOneForCombinedCss . '.css';
		$localFinalCssFile = WP_CONTENT_DIR . OptimizeCss::getRelPathCssCacheDir() . $uriToFinalCssFile;

		// Only combine if $shaOneCombinedUriPaths.css does not exist
		// If "?ver" value changes on any of the assets or the asset list changes in any way
		// then $shaOneCombinedUriPaths will change too and a new CSS file will be generated and loaded
		$skipIfFileExists = true;

		if ($skipIfFileExists || ! is_file($localFinalCssFile)) {
			// Change $finalCombinedCssContent as paths to fonts and images that are relative (e.g. ../, ../../) have to be updated + other optimization changes
			$finalCombinedCssContent = '';

			foreach ($localAssetsPaths as $assetHref => $localAssetsPath) {
				if ($cssContent = trim(FileSystem::file_get_contents($localAssetsPath, 'combine_css_imports'))) {
					$pathToAssetDir = OptimizeCommon::getPathToAssetDir($assetHref);

					// Does it have a source map? Strip it
					if (strpos($cssContent, 'sourceMappingURL') !== false) {
						$cssContent = OptimizeCommon::stripSourceMap($cssContent);
					}

					$finalCombinedCssContent .= '/*! '.str_replace(ABSPATH, '/', $localAssetsPath)." */\n";
					$finalCombinedCssContent .= OptimizeCss::maybeFixCssContent($cssContent, $pathToAssetDir . '/') . "\n";

					$finalCombinedCssContent = self::appendToCombineCss($localAssetsExtra, $assetHref, $pathToAssetDir, $finalCombinedCssContent);
				}
			}

			// Move any @imports to top; This also strips any @imports to Google Fonts if the option is chosen
			$finalCombinedCssContent = trim(OptimizeCss::importsUpdate($finalCombinedCssContent));

			if (Main::instance()->settings['google_fonts_remove']) {
				$finalCombinedCssContent = FontsGoogleRemove::cleanFontFaceReferences($finalCombinedCssContent);
			}

			$finalCombinedCssContent = apply_filters('wpacu_local_fonts_display_css_output', $finalCombinedCssContent, Main::instance()->settings['local_fonts_display']);

			if ($finalCombinedCssContent) {
				FileSystem::file_put_contents($localFinalCssFile, $finalCombinedCssContent);
			}
		}

		return array(
			'uri_final_css_file'   => $uriToFinalCssFile,
			'local_final_css_file' => $localFinalCssFile,
			'link_hrefs'           => $linkHrefs
		);
	}

	/**
	 * @param $combinedUriPaths
	 * @param $localAssetsExtra
	 *
	 * @return string
	 */
	public static function generateShaOneForCombinedCss($combinedUriPaths, $localAssetsExtra)
	{
		$finalShaOneContent = implode('', $combinedUriPaths);

		// Is '_combine_loaded_css_append_handle_extra' turned ON?
		if ( Main::instance()->settings['_combine_loaded_css_append_handle_extra'] && ! empty($localAssetsExtra) ) {
			$afterContentForAll = '';

			foreach ($localAssetsExtra as $values) {
				if (isset($values['after']) && $values['after']) {
					if (is_array($values['after'])) {
						$afterContentForAll .= implode('', $values['after']);
					} else {
						$afterContentForAll .= $values['after'];
					}
				}
			}

			$finalShaOneContent .= $afterContentForAll;
		}

		return sha1($finalShaOneContent);
	}

	/**
	 * @param $localAssetsExtra
	 * @param $assetHref
	 * @param $pathToAssetDir
	 * @param $finalAssetsContents
	 *
	 * @return string
	 */
	public static function appendToCombineCss($localAssetsExtra, $assetHref, $pathToAssetDir, $finalAssetsContents)
	{
		if (isset($localAssetsExtra[$assetHref]['after']) && ! empty($localAssetsExtra[$assetHref]['after'])) {
			$afterCssContent = '';

			foreach ($localAssetsExtra[$assetHref]['after'] as $afterData) {
				if (! is_bool($afterData)) {
					$afterCssContent .= $afterData."\n";
				}
			}

			if (trim($afterCssContent)) {
				if ( Main::instance()->settings['minify_loaded_css'] && Main::instance()->settings['minify_loaded_css_inline'] ) {
					$afterCssContent = MinifyCss::applyMinification( $afterCssContent );
				}

				$afterCssContent = OptimizeCss::maybeFixCssContent( $afterCssContent, $pathToAssetDir . '/' );

				$finalAssetsContents .= '/* [inline: after] */'.$afterCssContent.'/* [/inline: after] */'."\n";
			}
		}

		return $finalAssetsContents;
	}

	/**
	 * The targeted LINK tag (which was enqueued and has a handle) is replaced with $replaceWith
	 * along with any inline content that was added after it via wp_add_inline_style()
	 *
	 * @param $targetedLinkTag
	 * @param $wpacuRegisteredStyles
	 * @param $replaceWith
	 * @param $htmlSource
	 *
	 * @return mixed
	 */
	public static function stripTagAndAnyInlineAssocCode($targetedLinkTag, $wpacuRegisteredStyles, $replaceWith, $htmlSource)
	{
		if (Main::instance()->settings['_combine_loaded_css_append_handle_extra']) {
			$scriptExtrasHtml = OptimizeCss::getInlineAssociatedWithLinkHandle($targetedLinkTag, $wpacuRegisteredStyles, 'tag', 'html');
			$scriptExtraAfterHtml = (isset($scriptExtrasHtml['after']) && $scriptExtrasHtml['after']) ? "\n".$scriptExtrasHtml['after'] : '';

			$htmlSource = str_replace(
				array(
					$targetedLinkTag . $scriptExtraAfterHtml,
					$targetedLinkTag . trim($scriptExtraAfterHtml)
				),
				$replaceWith,
				$htmlSource
			);
		}

		return str_replace(
			array(
				$targetedLinkTag."\n",
				$targetedLinkTag
			),
			$replaceWith."\n",
			$htmlSource
		);
	}

	/**
	 * @return bool
	 */
	public static function proceedWithCssCombine()
	{
		// Not on query string request (debugging purposes)
		if (array_key_exists('wpacu_no_css_combine', $_GET)) {
			return false;
		}

		// No CSS files are combined in the Dashboard
		// Always in the front-end view
		// Do not combine if there's a POST request as there could be assets loading conditionally
		// that might not be needed when the page is accessed without POST, making the final CSS file larger
		if (! empty($_POST) || is_admin()) {
			return false; // Do not combine
		}

		// Only clean request URIs allowed (with Exceptions)
		// Exceptions
		if ((strpos($_SERVER['REQUEST_URI'], '?') !== false) && ! OptimizeCommon::loadOptimizedAssetsIfQueryStrings()) {
			return false;
		}

		if (! OptimizeCommon::doCombineIsRegularPage()) {
			return false;
		}

		$pluginSettings = Main::instance()->settings;

		if ($pluginSettings['test_mode'] && ! Menu::userCanManageAssets()) {
			return false; // Do not combine anything if "Test Mode" is ON and the user is in guest mode (not logged-in)
		}

		if ($pluginSettings['combine_loaded_css'] === '') {
			return false; // Do not combine
		}

		if (OptimizeCss::isOptimizeCssEnabledByOtherParty('if_enabled')) {
			return false; // Do not combine (it's already enabled in other plugin)
		}

		// "Minify HTML" from WP Rocket is sometimes stripping combined LINK tags
		// Better uncombined then missing essential CSS files
		if (Misc::isWpRocketMinifyHtmlEnabled()) {
			return false;
		}

		/*
		    // The option is no longer used since v1.1.7.3 (Pro) & v1.3.6.4 (Lite)
			if ( ($pluginSettings['combine_loaded_css'] === 'for_admin'
			      || $pluginSettings['combine_loaded_css_for_admin_only'] == 1)
			     && Menu::userCanManageAssets()) {

				return true; // Do combine
			}
		*/

		// "Apply it only for guest visitors (default)" is set; Do not combine if the user is logged in
		if ( $pluginSettings['combine_loaded_css_for'] === 'guests' && is_user_logged_in() ) {
			return false;
		}

		if (in_array($pluginSettings['combine_loaded_css'], array('for_all', 1)) ) {
			return true; // Do combine
		}

		// Finally, return false as none of the verification above matched
		return false;
	}
}
