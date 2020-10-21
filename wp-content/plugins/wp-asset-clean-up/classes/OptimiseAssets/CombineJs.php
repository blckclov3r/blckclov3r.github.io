<?php
namespace WpAssetCleanUp\OptimiseAssets;

use WpAssetCleanUp\Main;
use WpAssetCleanUp\Menu;
use WpAssetCleanUp\FileSystem;
use WpAssetCleanUp\Misc;
use WpAssetCleanUp\ObjectCache;

/**
 * Class CombineJs
 * @package WpAssetCleanUp\OptimiseAssets
 */
class CombineJs
{
	/**
	 * @var string
	 */
	public static $jsonStorageFile = 'js-combined{maybe-extra-info}.json';

	/**
	 * @param $htmlSource
	 *
	 * @return mixed
	 */
	public static function doCombine($htmlSource)
	{
		if (! (function_exists('libxml_use_internal_errors') && function_exists('libxml_clear_errors') && class_exists('\DOMDocument'))) {
			return $htmlSource;
		}

		if ( ! self::proceedWithJsCombine() ) {
			return $htmlSource;
		}

		global $wp_scripts;
		$wpacuRegisteredScripts = $wp_scripts->registered;

		$combineLevel = 2;

		$isDeferAppliedOnBodyCombineGroupNo = false;

		// Speed up processing by getting the already existing final CSS file URI
		// This will avoid parsing the HTML DOM and determine the combined URI paths for all the CSS files
		$finalCacheList = OptimizeCommon::getAssetCachedData(self::$jsonStorageFile, OptimizeJs::getRelPathJsCacheDir(), 'js');

		// $uriToFinalJsFile will always be relative ONLY within WP_CONTENT_DIR . self::getRelPathJsCacheDir()
		// which is usually "wp-content/cache/asset-cleanup/js/"

		// "true" would make it avoid checking the cache and always use the DOM Parser / RegExp
		// for DEV purposes ONLY as it uses more resources
		$skipCache = false;

		if (isset($_GET['wpacu_no_cache'])) {
			$skipCache = true;
		}

		if ( $skipCache || empty($finalCacheList) ) {
			/*
			 * NO CACHING TRANSIENT; Parse the DOM
			*/
			// Nothing in the database records or the retrieved cached file does not exist?
			OptimizeCommon::clearAssetCachedData(self::$jsonStorageFile);

			$combinableList = array();

			$jQueryMigrateInBody = false;
			$jQueryLibInBodyCount = 0;

			$minifyJsInlineTagsIsNotEnabled = ! (MinifyJs::isMinifyJsEnabled() && Main::instance()->settings['minify_loaded_js_inline']);

			if ($minifyJsInlineTagsIsNotEnabled) {
				$domTag = new \DOMDocument();
				libxml_use_internal_errors(true);

				// Strip irrelevant tags to boost the speed of the parser (e.g. NOSCRIPT / SCRIPT(inline) / STYLE)
				// Sometimes, inline CODE can be too large and it takes extra time for loadHTML() to parse
				$htmlSourceAlt = preg_replace( '@<script(| (type=(\'|"|)text/(javascript|template|html)(\'|"|)))>.*?</script>@si', '', $htmlSource );
				$htmlSourceAlt = preg_replace( '@<(style|noscript)[^>]*?>.*?</\\1>@si', '', $htmlSourceAlt );
				$htmlSourceAlt = preg_replace( '#<link([^<>]+)/?>#iU', '', $htmlSourceAlt );

				if (Main::instance()->isFrontendEditView) {
					$htmlSourceAlt = preg_replace( '@<form action="#wpacu_wrap_assets" method="post">.*?</form>@si', '', $htmlSourceAlt );
				}

				$domTag->loadHTML( $htmlSourceAlt );
			} else {
				$domTag = OptimizeCommon::getDomLoadedTag($htmlSource, 'combineJs');
			}

			// Only keep combinable JS files
			foreach ( array( 'head', 'body' ) as $docLocationScript ) {
				$groupIndex = 1;

				$docLocationElements = $domTag->getElementsByTagName($docLocationScript)->item(0);
				if ($docLocationElements === null) { continue; }

				// High accuracy (e.g. it ignores tags inside HTML comments, conditional or not)
				$scriptTags = $docLocationElements->getElementsByTagName('script');
				if ($scriptTags === null) { continue; }

				if ($docLocationScript && Main::instance()->settings['combine_loaded_js_defer_body']) {
					ObjectCache::wpacu_cache_set('wpacu_html_dom_body_tag_for_js', $docLocationElements);
				}

				foreach ($scriptTags as $tagObject) {
					$scriptAttributes = array();

					if ( isset($tagObject->attributes) && ! empty($tagObject->attributes) ) {
						foreach ( $tagObject->attributes as $attrObj ) {
							$scriptAttributes[ $attrObj->nodeName ] = trim( $attrObj->nodeValue );
						}
					}

					$scriptNotCombinable = false; // default (usually, most of the SCRIPT tags can be optimized)

					// Check if the CSS file has any 'data-wpacu-skip' attribute; if it does, do not alter it
					if (isset($scriptAttributes['data-wpacu-skip'])) {
						$scriptNotCombinable = true;
					}

					$hasSrc = isset($scriptAttributes['src']) && trim($scriptAttributes['src']); // No valid SRC attribute? It's not combinable (e.g. an inline tag)
					$isPluginScript = isset($scriptAttributes['data-wpacu-plugin-script']); // Only of the user is logged-in (skip it as it belongs to the Asset CleanUp (Pro) plugin)

					if (! $scriptNotCombinable && (! $hasSrc || $isPluginScript)) {
						// Inline tag? Skip it in the BODY
						if ($docLocationScript === 'body') {
							continue;
						}

						// Because of jQuery, we will not have the list of all inline scripts and then the combined files as it is in BODY
						if ($docLocationScript === 'head') {
							$handleToCheck = isset($scriptAttributes['data-wpacu-script-handle']) ? $scriptAttributes['data-wpacu-script-handle'] : ''; // Maybe: JS Inline (Before, After)
							if ($handleToCheck === '' && isset($scriptAttributes['id'])) {
								$replaceToGetHandle = '';
								if (strpos($scriptAttributes['id'], '-js-extra') !== false)  { $replaceToGetHandle = '-js-extra';  }
								if (strpos($scriptAttributes['id'], '-js-before') !== false) { $replaceToGetHandle = '-js-before'; }
								if (strpos($scriptAttributes['id'], '-js-after') !== false)  { $replaceToGetHandle = '-js-after';  }

								if ($replaceToGetHandle) {
									$handleToCheck = str_replace( $replaceToGetHandle, '', $scriptAttributes['id'] ); // Maybe: JS Inline (Data)
								}
								}

							// Once an inline SCRIPT (with few exceptions below), except the ones associated with an enqueued script tag (with "src") is stumbled upon, a new combined group in the HEAD tag will be formed
							if ($handleToCheck && Main::instance()->settings['_combine_loaded_js_append_handle_extra']) {
								$getInlineAssociatedWithHandle = OptimizeJs::getInlineAssociatedWithScriptHandle($handleToCheck, $wpacuRegisteredScripts, 'handle');

								if ( ($getInlineAssociatedWithHandle['data'] || $getInlineAssociatedWithHandle['before'] || $getInlineAssociatedWithHandle['after'])
									|| in_array(trim($tagObject->nodeValue), array($getInlineAssociatedWithHandle['data'], $getInlineAssociatedWithHandle['before'], $getInlineAssociatedWithHandle['after']))
									|| (strpos(trim($tagObject->nodeValue), '/* <![CDATA[ */') === 0 && Misc::endsWith(trim($tagObject->nodeValue), '/* ]]> */')) ) {

									// It's associated with the enqueued scripts or it's a (standalone) CDATA inline tag added via wp_localize_script()
									// Skip it instead and if the CDATA is not standalone (e.g. not associated with any script tag), the loop will "stay" in the same combined group
									continue;
								}
							}


							$scriptNotCombinable = true;
						}
					}

					$isInGroupType = 'standard';
					$isJQueryLib = $isJQueryMigrate = false;

					// Has SRC and $isPluginScript is set to false OR it does not have "data-wpacu-skip" attribute
					if (! $scriptNotCombinable) {
						$src = (string)$scriptAttributes['src'];

						if (self::skipCombine($src)) {
							$scriptNotCombinable = true;
						}

						if (isset($scriptAttributes['data-wpacu-to-be-preloaded-basic']) && $scriptAttributes['data-wpacu-to-be-preloaded-basic']) {
							$scriptNotCombinable = true;
						}

						// Was it optimized and has the URL updated? Check the Source URL
						if (! $scriptNotCombinable && isset($scriptAttributes['data-wpacu-script-rel-src-before']) && $scriptAttributes['data-wpacu-script-rel-src-before'] && self::skipCombine($scriptAttributes['data-wpacu-script-rel-src-before'])) {
							$scriptNotCombinable = true;
						}

						$isJQueryLib     = isset($scriptAttributes['data-wpacu-jquery-core-handle']);
						$isJQueryMigrate = isset($scriptAttributes['data-wpacu-jquery-migrate-handle']);

						if (isset($scriptAttributes['async'], $scriptAttributes['defer'])) { // Has both "async" and "defer"
							$isInGroupType = 'async_defer';
						} elseif (isset($scriptAttributes['async'])) { // Has only "async"
							$isInGroupType = 'async';
						} elseif (isset($scriptAttributes['defer'])) { // Has only "defer"
							// Does it have "defer" attribute, it's combinable (all checks were already done), loads in the BODY tag and "combine_loaded_js_defer_body" is ON? Keep it to the combination list
							$isCombinableWithBodyDefer = (! $scriptNotCombinable && $docLocationScript === 'body' && Main::instance()->settings['combine_loaded_js_defer_body']);

							if (! $isCombinableWithBodyDefer) {
								$isInGroupType = 'defer'; // Otherwise, add it to the "defer" group type
							}
						}
					}

					if ( ! $scriptNotCombinable ) {
						// It also checks the domain name to make sure no external scripts would be added to the list
						if ( $localAssetPath = OptimizeCommon::getLocalAssetPath( $src, 'js' ) ) {
							$scriptExtra = array();

							if (isset($scriptAttributes['data-wpacu-script-handle'], $wpacuRegisteredScripts[$scriptAttributes['data-wpacu-script-handle']]->extra) && Main::instance()->settings['_combine_loaded_js_append_handle_extra']) {
								$scriptExtra = $wpacuRegisteredScripts[$scriptAttributes['data-wpacu-script-handle']]->extra;
							}

							// Standard (could be multiple groups per $docLocationScript), Async & Defer, Async, Defer
							$groupByType = ($isInGroupType === 'standard') ? $groupIndex : $isInGroupType;

							if ($docLocationScript === 'body') {
								if ($isJQueryLib || strpos($localAssetPath, '/wp-includes/js/jquery/jquery.js') !== false) {
									$jQueryLibInBodyCount++;
								}

								if ($isJQueryMigrate || strpos($localAssetPath, '/wp-includes/js/jquery/jquery-migrate') !== false) {
									$jQueryLibInBodyCount++;
									$jQueryMigrateInBody = true;
								}
							}

							$combinableList[$docLocationScript][$groupByType][] = array(
								'src'   => $src,
								'local' => $localAssetPath,
								'info'  => array(
									'is_jquery'         => $isJQueryLib,
									'is_jquery_migrate' => $isJQueryMigrate
								),
								'extra' => $scriptExtra
							);

							if ($docLocationScript === 'body' && $jQueryLibInBodyCount === 2) {
								$jQueryLibInBodyCount = 0; // reset it
								$groupIndex ++; // a new JS group will be created if jQuery & jQuery Migrate are combined in the BODY
								continue;
							}
						}
					} else {
						$groupIndex ++; // a new JS group will be created (applies to "standard" ones only)
					}
				}
			}

			// Could be pages such as maintenance mode with no external JavaScript files
			if (empty($combinableList)) {
				return $htmlSource;
			}

			$finalCacheList = array();

			foreach ($combinableList as $docLocationScript => $combinableListGroups) {
				$groupNo = 1;

				foreach ($combinableListGroups as $groupType => $groupFiles) {
					// Any groups having one file? Then it's not really a group and the file should load on its own
					// Could be one extra file besides the jQuery & jQuery Migrate group or the only JS file called within the HEAD
					if (count($groupFiles) < 2) {
						continue;
					}

					$combinedUriPaths = $localAssetsPaths = $groupScriptSrcs = array();
					$localAssetsExtra = array();
					$jQueryIsIncludedInGroup = false;

					foreach ($groupFiles as $groupFileData) {
						if ($groupFileData['info']['is_jquery'] || strpos($groupFileData['local'], '/wp-includes/js/jquery/jquery.js') !== false) {
							$jQueryIsIncludedInGroup = true;

							// Is jQuery in the BODY without jQuery Migrate loaded?
							// Isolate it as it needs to be the first to load in case there are inline scripts calling it before the combined group(s)
							if ($docLocationScript === 'body' && ! $jQueryMigrateInBody) {
								continue;
							}
						}

						$src                    = $groupFileData['src'];
						$groupScriptSrcs[]      = $src;
						$combinedUriPaths[]     = OptimizeCommon::getSourceRelPath($src);
						$localAssetsPaths[$src] = $groupFileData['local'];
						$localAssetsExtra[$src] = $groupFileData['extra'];
					}

					$shaOneForCombinedJs = self::generateShaOneForCombinedJs($combinedUriPaths, $localAssetsExtra);

					$maybeDoJsCombine = self::maybeDoJsCombine(
						$shaOneForCombinedJs,
						$localAssetsPaths,
						$localAssetsExtra,
						$docLocationScript
					);

					// Local path to combined CSS file
					$localFinalJsFile = $maybeDoJsCombine['local_final_js_file'];

					// URI (e.g. /wp-content/cache/asset-cleanup/[file-name-here.js]) to the combined JS file
					$uriToFinalJsFile = $maybeDoJsCombine['uri_final_js_file'];

					if (! is_file($localFinalJsFile)) {
						return $htmlSource; // something is not right as the file wasn't created, we will return the original HTML source
					}

					$groupScriptSrcsFilter = array_map(static function($src) {
						$src = str_replace(site_url(), '', $src);
						// Starts with // (protocol is missing) - the replacement above wasn't made
						if (strpos($src, '//') === 0) {
							$siteUrlNoProtocol = str_replace(array('http:', 'https:'), '', site_url());
							return str_replace($siteUrlNoProtocol, '', $src);
						}
						return $src;
					}, $groupScriptSrcs);

					$finalCacheList[$docLocationScript][$groupNo] = array(
						'uri_to_final_js_file' => $uriToFinalJsFile,
						'script_srcs'          => $groupScriptSrcsFilter
					);

					if (in_array($groupType, array('async_defer', 'async', 'defer'))) {
						if ($groupType === 'async_defer') {
							$finalCacheList[$docLocationScript][$groupNo]['extra_attributes'][] = 'async';
							$finalCacheList[$docLocationScript][$groupNo]['extra_attributes'][] = 'defer';
						} else {
							$finalCacheList[$docLocationScript][$groupNo]['extra_attributes'][] = $groupType;
						}
					}

					// Apply defer="defer" to combined JS files from the BODY tag (if enabled), except the combined jQuery & jQuery Migrate Group
					if ($docLocationScript === 'body' && ! $jQueryIsIncludedInGroup && Main::instance()->settings['combine_loaded_js_defer_body']) {
						if ($isDeferAppliedOnBodyCombineGroupNo === false) {
							// Only record the first one
							$isDeferAppliedOnBodyCombineGroupNo = $groupNo;
						}

						$finalCacheList[$docLocationScript][$groupNo]['extra_attributes'][] = 'defer';
					}

					$groupNo ++;
				}
			}

			OptimizeCommon::setAssetCachedData(self::$jsonStorageFile, OptimizeJs::getRelPathJsCacheDir(), json_encode($finalCacheList));
		}

		if (! empty($finalCacheList)) {
			$cdnUrls = OptimizeCommon::getAnyCdnUrls();
			$cdnUrlForJs = isset($cdnUrls['js']) ? $cdnUrls['js'] : false;

			foreach ( $finalCacheList as $docLocationScript => $cachedGroupsList ) {
				foreach ($cachedGroupsList as $groupNo => $cachedValues) {
					$htmlSourceBeforeGroupReplacement = $htmlSource;

					$uriToFinalJsFile = $cachedValues['uri_to_final_js_file'];
					$filesSources = $cachedValues['script_srcs'];

					// Basic Combining (1) -> replace "first" tag with the final combination tag (there would be most likely multiple groups)
					// Enhanced Combining (2) -> replace "last" tag with the final combination tag (most likely one group)
					$indexReplacement = ($combineLevel === 2) ? (count($filesSources) - 1) : 0;

					$finalTagUrl = OptimizeCommon::filterWpContentUrl($cdnUrlForJs) . OptimizeJs::getRelPathJsCacheDir() . $uriToFinalJsFile;

					$finalJsTagAttrsOutput = '';
					$extraAttrs = array();

					if (isset($cachedValues['extra_attributes']) && ! empty($cachedValues['extra_attributes'])) {
						$extraAttrs = $cachedValues['extra_attributes'];
						foreach ($extraAttrs as $finalJsTagAttr) {
							$finalJsTagAttrsOutput .= ' '.$finalJsTagAttr.'=\''.$finalJsTagAttr.'\' ';
						}
						$finalJsTagAttrsOutput = trim($finalJsTagAttrsOutput);
					}

					$finalJsTag = <<<HTML
<script {$finalJsTagAttrsOutput} id='wpacu-combined-js-{$docLocationScript}-group-{$groupNo}' type='text/javascript' src='{$finalTagUrl}'></script>
HTML;
					// In case one needs to alter it (e.g. developers that might want to add custom attributes such as data-cfasync="false")
					$finalJsTag = apply_filters(
						'wpacu_combined_js_tag',
						$finalJsTag,
						array(
							'attrs'        => $extraAttrs,
					        'doc_location' => $docLocationScript,
					        'group_no'     => $groupNo,
					        'src'          => $finalTagUrl
						)
					);

					// Reference: https://stackoverflow.com/questions/2368539/php-replacing-multiple-spaces-with-a-single-space
					$finalJsTag = preg_replace('!\s+!', ' ', $finalJsTag);

					$scriptTagsStrippedNo = 0;

					$scriptTags = OptimizeJs::getScriptTagsFromSrcs($filesSources, $htmlSource);

					foreach ($scriptTags as $groupScriptTagIndex => $scriptTag) {
						$replaceWith = ($groupScriptTagIndex === $indexReplacement) ? $finalJsTag : '';
						$htmlSourceBeforeTagReplacement = $htmlSource;

						// 1) Strip any inline code associated with the tag
						// 2) Finally, strip the actual tag
						$htmlSource = self::stripTagAndAnyInlineAssocCode( $scriptTag, $wpacuRegisteredScripts, $replaceWith, $htmlSource );

						if ($htmlSource !== $htmlSourceBeforeTagReplacement) {
							$scriptTagsStrippedNo ++;
						}
						}

					// At least two tags have to be stripped from the group to consider doing the group replacement
					// If the tags weren't replaced it's likely there were changes to their structure after they were cached for the group merging
					if (count($filesSources) !== $scriptTagsStrippedNo) {
						$htmlSource = $htmlSourceBeforeGroupReplacement;
					}
				}
			}
		}

		// Only relevant if "Defer loading JavaScript combined files from <body>"" in "Settings" - "Combine CSS & JS Files" - "Combine loaded JS (JavaScript) into fewer files"
		// and there is at least one combined deferred tag

		if (isset($finalCacheList['body']) && (! empty($finalCacheList['body'])) && Main::instance()->settings['combine_loaded_js_defer_body']) {
			// CACHE RE-BUILT
			if ($isDeferAppliedOnBodyCombineGroupNo > 0 && $domTag = ObjectCache::wpacu_cache_get('wpacu_html_dom_body_tag_for_js')) {
				$strPart = "id='wpacu-combined-js-body-group-".$isDeferAppliedOnBodyCombineGroupNo."' type='text/javascript' ";
				list(,$htmlAfterFirstCombinedDeferScript) = explode($strPart, $htmlSource);
				$htmlAfterFirstCombinedDeferScriptMaybeChanged = $htmlAfterFirstCombinedDeferScript;
				$scriptTags = $domTag->getElementsByTagName('script');
			} else {
				// FROM THE CACHE
				foreach ($finalCacheList['body'] as $bodyCombineGroupNo => $values) {
					if (isset($values['extra_attributes']) && in_array('defer', $values['extra_attributes'])) {
						$isDeferAppliedOnBodyCombineGroupNo = $bodyCombineGroupNo;
						break;
					}
				}

				if (! $isDeferAppliedOnBodyCombineGroupNo) {
					// Not applicable to any combined group
					return $htmlSource;
				}

				$strPart = "id='wpacu-combined-js-body-group-".$isDeferAppliedOnBodyCombineGroupNo."' type='text/javascript' ";

				$htmlAfterFirstCombinedDeferScriptMaybeChanged = false;

				if (strpos($htmlSource, $strPart) !== false) {
					list( , $htmlAfterFirstCombinedDeferScript ) = explode( $strPart, $htmlSource );
					$htmlAfterFirstCombinedDeferScriptMaybeChanged = $htmlAfterFirstCombinedDeferScript;
				}

				$domTag = new \DOMDocument();
				libxml_use_internal_errors(true);

				// Strip irrelevant tags to boost the speed of the parser (e.g. NOSCRIPT / SCRIPT(inline) / STYLE)
				// Sometimes, inline CODE can be too large and it takes extra time for loadHTML() to parse
				$htmlSourceAlt = preg_replace( '@<script(| type=\'text/javascript\'| type="text/javascript")>.*?</script>@si', '', $htmlAfterFirstCombinedDeferScript );
				$htmlSourceAlt = preg_replace( '@<(style|noscript)[^>]*?>.*?</\\1>@si', '', $htmlSourceAlt );
				$htmlSourceAlt = preg_replace( '#<link([^<>]+)/?>#iU', '', $htmlSourceAlt );

				if (Main::instance()->isFrontendEditView) {
					$htmlSourceAlt = preg_replace( '@<form action="#wpacu_wrap_assets" method="post">.*?</form>@si', '', $htmlSourceAlt );
				}

				// It could no other SCRIPT left, stop here in this case
				if (strpos($htmlSource, '<script') !== false) {
					return $htmlSource;
				}

				$domTag->loadHTML( $htmlSourceAlt );
				$scriptTags = $domTag->getElementsByTagName('script');
			}

			if ( $scriptTags === null ) {
				return $htmlSource;
			}

			foreach ($scriptTags as $tagObject) {
				if (empty($tagObject->attributes)) { continue; }

				$scriptAttributes = array();

				foreach ( $tagObject->attributes as $attrObj ) {
					$scriptAttributes[ $attrObj->nodeName ] = trim( $attrObj->nodeValue );
				}

				// No "src" attribute? Skip it (most likely an inline script tag)
				if (! (isset($scriptAttributes['src']) && $scriptAttributes['src'])) {
					continue;
				}

				// Skip it as "defer" is already set
				if (isset($scriptAttributes['defer'])) {
					continue;
				}

				// Has "src" attribute and "defer" is not applied? Add it
				if ($htmlAfterFirstCombinedDeferScriptMaybeChanged !== false) {
					$htmlAfterFirstCombinedDeferScriptMaybeChanged = trim( preg_replace(
						'#src(\s+|)=(\s+|)(|"|\'|\s+)(' . preg_quote( $scriptAttributes['src'], '/' ) . ')(\3)#si',
						'src=\3\4\3 defer=\'defer\'',
						$htmlAfterFirstCombinedDeferScriptMaybeChanged
					) );
				}
			}

			if ($htmlAfterFirstCombinedDeferScriptMaybeChanged && $htmlAfterFirstCombinedDeferScriptMaybeChanged !== $htmlAfterFirstCombinedDeferScript) {
				$htmlSource = str_replace($htmlAfterFirstCombinedDeferScript, $htmlAfterFirstCombinedDeferScriptMaybeChanged, $htmlSource);
			}
		}

		libxml_clear_errors();

		// Finally, return the HTML source
		return $htmlSource;
	}

	/**
	 * @param $src
	 *
	 * @return bool
	 */
	public static function skipCombine($src)
	{
		$regExps = array(
			'#/wp-content/bs-booster-cache/#'
		);

		if (Main::instance()->settings['combine_loaded_js_exceptions'] !== '') {
			$loadedCssExceptionsPatterns = trim(Main::instance()->settings['combine_loaded_js_exceptions']);

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
			if (preg_match($regExp, $src)) {
				// Skip combination
				return true;
			}
		}

		return false;
	}

	/**
	 * @param $shaOneForCombinedJs
	 * @param $localAssetsPaths
	 * @param $localAssetsExtra
	 * @param $docLocationScript
	 *
	 * @return array
	 */
	public static function maybeDoJsCombine($shaOneForCombinedJs, $localAssetsPaths, $localAssetsExtra, $docLocationScript)
	{
		$uriToFinalJsFile = $docLocationScript . '-' . $shaOneForCombinedJs . '.js';
		$localFinalJsFile  = WP_CONTENT_DIR . OptimizeJs::getRelPathJsCacheDir() . $uriToFinalJsFile;

		// Only combine if $shaOneCombinedUriPaths.js does not exist
		// If "?ver" value changes on any of the assets or the asset list changes in any way
		// then $shaOneCombinedUriPaths will change too and a new JS file will be generated and loaded
		$skipIfFileExists = true;

		if ($skipIfFileExists || ! is_file($localFinalJsFile)) {
			// Change $assetsContents as paths to fonts and images that are relative (e.g. ../, ../../) have to be updated
			$finalJsContents = '';

			foreach ($localAssetsPaths as $assetHref => $localAssetsPath) {
				if ($jsContent = trim(FileSystem::file_get_contents($localAssetsPath))) {
					if ($jsContent === '') {
						continue;
					}

					// Does it have a source map? Strip it
					if (strpos($jsContent, 'sourceMappingURL') !== false) {
						$jsContent = OptimizeCommon::stripSourceMap($jsContent);
					}

					$pathToAssetDir = OptimizeCommon::getPathToAssetDir($assetHref);

					$contentToAddToCombinedFile = '/*!'.str_replace(ABSPATH, '/', $localAssetsPath)."*/\n";

					// This includes the extra from 'data' (CDATA added via wp_localize_script()) & 'before' as they are both printed BEFORE the SCRIPT tag
					$contentToAddToCombinedFile .= self::appendToCombineJs('before', $localAssetsExtra, $assetHref, $pathToAssetDir);
					$contentToAddToCombinedFile .= OptimizeJs::maybeDoJsFixes($jsContent, $pathToAssetDir . '/') . "\n";
					// This includes the inline 'after' the SCRIPT tag
					$contentToAddToCombinedFile .= self::appendToCombineJs('after', $localAssetsExtra, $assetHref, $pathToAssetDir);

					$finalJsContents .= $contentToAddToCombinedFile;
				}
			}

			if ($finalJsContents !== '') {
				FileSystem::file_put_contents($localFinalJsFile, $finalJsContents);
			}
		}

		return array(
			'uri_final_js_file'   => $uriToFinalJsFile,
			'local_final_js_file' => $localFinalJsFile
		);
	}

	/**
	 * @param $combinedUriPaths
	 * @param $localAssetsExtra
	 *
	 * @return string
	 */
	public static function generateShaOneForCombinedJs($combinedUriPaths, $localAssetsExtra)
	{
		$finalShaOneContent = implode('', $combinedUriPaths);

		// Is '_combine_loaded_js_append_handle_extra' turned ON?
		if ( Main::instance()->settings['_combine_loaded_js_append_handle_extra'] && ! empty($localAssetsExtra) ) {
			$afterContentForAll = '';

			foreach ( $localAssetsExtra as $values ) {
				if (empty($values)) {
					continue;
				}

				foreach ( array( 'data', 'before', 'after' ) as $keyToCheck ) {
					if ( isset( $values[ $keyToCheck ] ) && $values[ $keyToCheck ] ) {
						if ( is_array( $values[ $keyToCheck ] ) ) {
							$afterContentForAll .= implode( '', $values[ $keyToCheck ] );
						} else {
							$afterContentForAll .= $values[ $keyToCheck ];
						}
					}
				}
			}

			$finalShaOneContent .= $afterContentForAll;
		}

		return sha1($finalShaOneContent);
	}

	/**
	 * @param $addItLocation
	 * @param $localAssetsExtra
	 * @param $assetHref
	 * @param $pathToAssetDir
	 *
	 * @return string
	 */
	public static function appendToCombineJs($addItLocation, $localAssetsExtra, $assetHref, $pathToAssetDir)
	{
		$extraContentToAppend = '';
		$doJsMinifyInline = Main::instance()->settings['minify_loaded_js'] && Main::instance()->settings['minify_loaded_js_inline'];

		if ($addItLocation === 'before') {
			// [Before JS Content]
			if (isset($localAssetsExtra[$assetHref]['data']) && ($dataValue = $localAssetsExtra[$assetHref]['data'])) {
				$extraContentToAppend = '';
				if (self::isInlineJsCombineable($dataValue) && trim($dataValue) !== '') {
					$cData = $doJsMinifyInline ? MinifyJs::applyMinification( $dataValue ) : $dataValue;
					$cData = OptimizeJs::maybeDoJsFixes( $cData, $pathToAssetDir . '/' );
					$extraContentToAppend .= '/* [inline: cdata] */' . $cData . '/* [/inline: cdata] */' . "\n";
				}
			}

			if (isset($localAssetsExtra[$assetHref]['before']) && ! empty($localAssetsExtra[$assetHref]['before'])) {
				$inlineBeforeJsData = '';

				foreach ($localAssetsExtra[$assetHref]['before'] as $beforeData) {
					if (! is_bool($beforeData)) {
						$inlineBeforeJsData .= $beforeData . "\n";
					}
				}

				if (trim($inlineBeforeJsData)) {
					$inlineBeforeJsData = OptimizeJs::maybeAlterContentForInlineScriptTag( $inlineBeforeJsData, $doJsMinifyInline );
					$inlineBeforeJsData = OptimizeJs::maybeDoJsFixes( $inlineBeforeJsData, $pathToAssetDir . '/' );
					$extraContentToAppend .= '/* [inline: before] */' . $inlineBeforeJsData . '/* [/inline: before] */' . "\n";
				}
			}
			// [/Before JS Content]
		} elseif ($addItLocation === 'after') {
			// [After JS Content]
			if (isset($localAssetsExtra[$assetHref]['after']) && ! empty($localAssetsExtra[$assetHref]['after'])) {
				$inlineAfterJsData = '';

				foreach ($localAssetsExtra[$assetHref]['after'] as $afterData) {
					if (! is_bool($afterData)) {
						$inlineAfterJsData .= $afterData."\n";
					}
				}

				if ( trim($inlineAfterJsData) ) {
					$inlineAfterJsData = OptimizeJs::maybeAlterContentForInlineScriptTag( $inlineAfterJsData, $doJsMinifyInline );
					$inlineAfterJsData = OptimizeJs::maybeDoJsFixes( $inlineAfterJsData, $pathToAssetDir . '/' );
					$extraContentToAppend .= '/* [inline: after] */' . $inlineAfterJsData . '/* [/inline: after] */' . "\n";
				}
			}
			// [/After JS Content]
		}

		return $extraContentToAppend;
	}

	/**
	 * @param $scriptTag
	 * @param $wpacuRegisteredScripts
	 * @param $replaceWith
	 * @param $htmlSource
	 *
	 * @return mixed
	 */
	public static function stripTagAndAnyInlineAssocCode($scriptTag, $wpacuRegisteredScripts, $replaceWith, $htmlSource)
	{
		if (Main::instance()->settings['_combine_loaded_js_append_handle_extra']) {
			$scriptExtrasValue      = OptimizeJs::getInlineAssociatedWithScriptHandle($scriptTag, $wpacuRegisteredScripts, 'tag', 'value');

			$scriptExtraCdataValue  = (isset($scriptExtrasValue['data'])   && $scriptExtrasValue['data'])   ? $scriptExtrasValue['data']   : '';
			$scriptExtraBeforeValue = (isset($scriptExtrasValue['before']) && $scriptExtrasValue['before']) ? $scriptExtrasValue['before'] : '';
			$scriptExtraAfterValue  = (isset($scriptExtrasValue['after'])  && $scriptExtrasValue['after'])  ? $scriptExtrasValue['after']  : '';

			$scriptExtrasHtml       = OptimizeJs::getInlineAssociatedWithScriptHandle($scriptTag, $wpacuRegisteredScripts, 'tag', 'html');
			preg_match_all('#data-wpacu-script-handle=([\'])' . '(.*)' . '(\1)#Usmi', $scriptTag, $outputMatches);
			$scriptHandle = (isset($outputMatches[2][0]) && $outputMatches[2][0]) ? trim($outputMatches[2][0], '"\'') : '';

			$scriptExtraCdataHtml  = (isset($scriptExtrasHtml['data'])   && $scriptExtrasHtml['data'])   ? $scriptExtrasHtml['data']   : '';
			$scriptExtraBeforeHtml = (isset($scriptExtrasHtml['before']) && $scriptExtrasHtml['before']) ? $scriptExtrasHtml['before'] : '';
			$scriptExtraAfterHtml  = (isset($scriptExtrasHtml['after'])  && $scriptExtrasHtml['after'])  ? $scriptExtrasHtml['after']  : '';

			if ($scriptExtraCdataValue || $scriptExtraBeforeValue || $scriptExtraAfterValue) {
				if ( $scriptExtraCdataValue && self::isInlineJsCombineable($scriptExtraCdataValue) ) {
					$htmlSource = str_replace($scriptExtraCdataHtml, '', $htmlSource );
				}

				if ($scriptExtraBeforeValue) {
					$repsBefore = array(
						$scriptExtraBeforeHtml => '',
						str_replace( '<script ', '<script data-wpacu-script-handle=\'' . $scriptHandle . '\' ', $scriptExtraBeforeHtml ) => '',
						'>'."\n".$scriptExtraBeforeValue."\n".'</script>' => '></script>',
						$scriptExtraBeforeValue."\n" => ''
					);
					$htmlSource = str_replace(array_keys($repsBefore), array_values($repsBefore), $htmlSource );
				}

				if ($scriptExtraAfterValue) {
					$repsBefore = array(
						$scriptExtraAfterHtml => '',
						str_replace( '<script ', '<script data-wpacu-script-handle=\'' . $scriptHandle . '\' ', $scriptExtraAfterHtml ) => '',
						'>'."\n".$scriptExtraAfterValue."\n".'</script>' => '></script>',
						$scriptExtraAfterValue."\n" => ''
					);
					$htmlSource = str_replace(array_keys($repsBefore), array_values($repsBefore), $htmlSource);
				}
			}
		}

		// Finally, strip/replace the tag
		return str_replace( array($scriptTag."\n", $scriptTag), $replaceWith, $htmlSource );
	}

	/**
	 * This is to prevent certain inline JS to be appended to the combined JS files in order to avoid lots of disk space (sometimes a few GB) of JS combined files
	 *
	 * @param $jsInlineValue
	 *
	 * @return bool
	 */
	public static function isInlineJsCombineable($jsInlineValue)
	{
		// The common WordPress nonce
		if (strpos($jsInlineValue, '_nonce') !== false) {
			return false;
		}

		// WooCommerce Cart Fragments
		if (strpos($jsInlineValue, 'wc_cart_hash_') !== false && strpos($jsInlineValue, 'cart_hash_key') !== false) {
			return false;
		}

		return true; // default
	}

	/**
	 * @return bool
	 */
	public static function proceedWithJsCombine()
	{
		// not on query string request (debugging purposes)
		if (array_key_exists('wpacu_no_js_combine', $_GET)) {
			return false;
		}

		// No JS files are combined in the Dashboard
		// Always in the front-end view
		// Do not combine if there's a POST request as there could be assets loading conditionally
		// that might not be needed when the page is accessed without POST, making the final JS file larger
		if (! empty($_POST) || is_admin()) {
			return false; // Do not combine
		}

		// Only clean request URIs allowed (with few exceptions)
		if (strpos($_SERVER['REQUEST_URI'], '?') !== false) {
			// Exceptions
			if (! OptimizeCommon::loadOptimizedAssetsIfQueryStrings()) {
				return false;
			}
		}

		if (! OptimizeCommon::doCombineIsRegularPage()) {
			return false;
		}

		$pluginSettings = Main::instance()->settings;

		if ($pluginSettings['test_mode'] && ! Menu::userCanManageAssets()) {
			return false; // Do not combine anything if "Test Mode" is ON
		}

		if ($pluginSettings['combine_loaded_js'] === '') {
			return false; // Do not combine
		}

		if (OptimizeJs::isOptimizeJsEnabledByOtherParty('if_enabled')) {
			return false; // Do not combine (it's already enabled in other plugin)
		}

		// "Minify HTML" from WP Rocket is sometimes stripping combined SCRIPT tags
		// Better uncombined then missing essential SCRIPT files
		if (Misc::isWpRocketMinifyHtmlEnabled()) {
			return false;
		}

		/*
		if ( ($pluginSettings['combine_loaded_js'] === 'for_admin'
		      || $pluginSettings['combine_loaded_js_for_admin_only'] == 1)
		     && Menu::userCanManageAssets() ) {
			return true; // Do combine
		}
		*/

		// "Apply it only for guest visitors (default)" is set; Do not combine if the user is logged in
		if ( $pluginSettings['combine_loaded_js_for'] === 'guests' && is_user_logged_in() ) {
			return false;
		}

		if ( in_array($pluginSettings['combine_loaded_js'], array('for_all', 1)) ) {
			return true; // Do combine
		}

		// Finally, return false as none of the checks above matched
		return false;
	}
}
