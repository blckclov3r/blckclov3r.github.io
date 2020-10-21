<?php
namespace WpAssetCleanUp\OptimiseAssets;

use WpAssetCleanUp\Main;
use WpAssetCleanUp\Misc;
use WpAssetCleanUp\Plugin;

/**
 * Class FontsGoogle
 * @package WpAssetCleanUp\OptimiseAssets
 */
class FontsGoogle
{
	/**
	 * @var string
	 */
	public static $containsStr = '//fonts.googleapis.com/';

	/**
	 * @var string
	 */
	public static $matchesStr = '//fonts.googleapis.com/(css|icon)\?';

	/**
	 *
	 */
	const NOSCRIPT_WEB_FONT_LOADER = '<span style="display: none;" data-name=wpacu-delimiter content="ASSET CLEANUP NOSCRIPT WEB FONT LOADER"></span>';

	/**
	 *
	 */
	const COMBINED_LINK_DEL = '<span style="display: none;" data-name=wpacu-delimiter content="ASSET CLEANUP COMBINED LINK LOCATION"></span>';

	/**
	 *
	 */
	public function init()
	{
		add_filter('wp_resource_hints', array($this, 'resourceHints'), PHP_INT_MAX, 2);

		add_action('wp_head',   array($this, 'preloadFontFiles'), 1);
		add_action('wp_footer', static function() {
			if ( Plugin::preventAnyChanges() || Main::isTestModeActive() || Main::instance()->settings['google_fonts_remove'] ) {
				return;
			}

			echo self::NOSCRIPT_WEB_FONT_LOADER;
		}, PHP_INT_MAX);

		add_filter('wpacu_html_source_after_optimization', static function($htmlSource) {
			// Is the mark still there and wasn't replaced? Strip it
			return str_replace(FontsGoogle::NOSCRIPT_WEB_FONT_LOADER, '', $htmlSource);
		});

		add_action('init', function() {
			// don't apply any changes if not in the front-end view (e.g. Dashboard view)
			// or test mode is enabled and a guest user is accessing the page
			if ( Plugin::preventAnyChanges() || Main::isTestModeActive() || Main::instance()->settings['google_fonts_remove'] ) {
				return;
			}

			add_filter('style_loader_src', array($this, 'alterGoogleFontLink'));
		}, 20);
	}

	/**
	 * @param $urls
	 * @param $relationType
	 *
	 * @return array
	 */
	public function resourceHints($urls, $relationType)
	{
		// don't apply any changes if not in the front-end view (e.g. Dashboard view)
		// or test mode is enabled and a guest user is accessing the page
		if (is_admin() || Main::isTestModeActive() || Plugin::preventAnyChanges()) {
			return $urls;
		}

		// Are the Google Fonts removed? Do not add it and strip any existing ones
		if (! empty($urls) && Main::instance()->settings['google_fonts_remove']) {
			foreach ($urls as $urlKey => $urlValue) {
				if (is_string($urlValue) && ((stripos($urlValue, 'fonts.googleapis.com') !== false) || (stripos($urlValue, 'fonts.gstatic.com') !== false))) {
					unset($urls[$urlKey]);
				}
			}

			return $urls; // Finally, return the list after any removals
		}

		// Google Fonts "preconnect"
		if ('preconnect' === $relationType
		    && ! Main::instance()->settings['google_fonts_remove']  // "Remove Google Fonts" has to be turned off
		    && Main::instance()->settings['google_fonts_preconnect']) { // Needs to be enabled within "Plugin Usage Preferences" in "Settings"
			$urls[] = array(
				'href' => 'https://fonts.gstatic.com/',
				'crossorigin'
			);
		}

		return $urls;
	}

	/**
	 *
	 */
	public function preloadFontFiles()
	{
		// don't apply any changes if not in the front-end view (e.g. Dashboard view)
		// or test mode is enabled and a guest user is accessing the page
		if ( Plugin::preventAnyChanges() || Main::isTestModeActive() ) {
			return;
		}

		if (! $preloadFontFiles = trim(Main::instance()->settings['google_fonts_preload_files'])) {
			return;
		}

		$preloadFontFilesArray = array();

		if (strpos($preloadFontFiles, "\n") !== false) {
			foreach (explode("\n", $preloadFontFiles) as $preloadFontFile) {
				$preloadFontFile = trim($preloadFontFile);

				if (! $preloadFontFile) {
					continue;
				}

				$preloadFontFilesArray[] = $preloadFontFile;
			}
		} else {
			$preloadFontFilesArray[] = $preloadFontFiles;
		}

		$preloadFontFilesArray = array_unique($preloadFontFilesArray);

		$preloadFontFilesOutput = '';

		// Finally, go through the list
		foreach ($preloadFontFilesArray as $preloadFontFile) {
			$preloadFontFilesOutput .= '<link rel="preload" as="font" href="'.esc_attr($preloadFontFile).'" data-wpacu-preload-font="1" crossorigin>'."\n";
		}

		echo apply_filters('wpacu_preload_google_font_files_output', $preloadFontFilesOutput);
	}

	/**
	 * @param $htmlSource
	 *
	 * @return false|mixed|string|void
	 */
	public static function alterHtmlSource($htmlSource)
	{
		// don't apply any changes if not in the front-end view (e.g. Dashboard view)
		// or test mode is enabled and a guest user is accessing the page
		// or an AMP page is accessed
		if (Plugin::preventAnyChanges() || Main::isTestModeActive()) {
			return $htmlSource;
		}

		/*
		 * Remove Google Fonts and stop here as optimization is no longer relevant
		 */
		if (Main::instance()->settings['google_fonts_remove']) {
			return FontsGoogleRemove::cleanHtmlSource($htmlSource);
		}

		/*
		 * Optimize Google Fonts
		 */

		// Cleaner HTML Source
		$altHtmlSource = preg_replace('@<(script|style|noscript)[^>]*?>.*?</\\1>@si', '', $htmlSource);
		$altHtmlSource = preg_replace('/<!--(.|\s)*?-->/', '', $altHtmlSource);

		// Get all valid LINKs that have the $string within them
		preg_match_all('#<link[^>]*' . self::$matchesStr . '.*(>)#Usmi', $altHtmlSource, $matchesFromLinkTags, PREG_SET_ORDER);

		// Needs to match at least one to carry on with the replacements
		if (isset($matchesFromLinkTags[0]) && ! empty($matchesFromLinkTags[0])) {
			$finalCombinableLinks = $preloadedLinks = array();

			foreach ($matchesFromLinkTags as $linkIndex => $linkTagArray) {
				$linkTag = $finalLinkTag = trim(trim($linkTagArray[0], '"\''));

				// Extra checks to make sure it's a valid LINK tag
				if ( (strpos($linkTag, "'") !== false && (substr_count($linkTag, "'") % 2))
				|| (strpos($linkTag, '"') !== false && (substr_count($linkTag, '"') % 2))
				|| (trim(strip_tags($linkTag)) !== '')) {
					continue;
				}

				// Check if the CSS has any 'data-wpacu-skip' attribute; if it does, do not continue and leave it as it is (non-combined)
				if (preg_match('#data-wpacu-skip([=>/ ])#i', $linkTag)) {
					continue;
				}

				preg_match_all('#href=(["\'])' . '(.*)' . '(["\'])#Usmi', $linkTag, $outputMatches);
				$linkHrefOriginal = $finalLinkHref = trim($outputMatches[2][0], '"\'');

				// [START] Remove invalid requests with no font family
				$urlParse = parse_url(str_replace('&amp;', '&', $linkHrefOriginal), PHP_URL_QUERY);
				parse_str($urlParse, $qStr);

				if (isset($qStr['family']) && ! $qStr['family']) {
					$htmlSource = str_replace($linkTag, '', $htmlSource);
					continue;
				}
				// [END] Remove invalid requests with no font family

				// If anything is set apart from '[none set]', proceed
				if (Main::instance()->settings['google_fonts_display']) {
					$newLinkHref = $finalLinkHref = self::alterGoogleFontLink($linkHrefOriginal);

					if ($newLinkHref !== $linkHrefOriginal) {
						$finalLinkTag = str_replace($linkHrefOriginal, $newLinkHref, $linkTag);

						// Finally, alter the HTML source
						$htmlSource = str_replace($linkTag, $finalLinkTag, $htmlSource);
					}
				}

				if (preg_match('/rel=(["\'])preload(["\'])/i', $finalLinkTag)
				    || strpos($finalLinkTag, 'data-wpacu-to-be-preloaded-basic')) {
					$preloadedLinks[] = $finalLinkHref;
				}

				$finalCombinableLinks[] = array('href' => $finalLinkHref, 'tag' => $finalLinkTag);
			}

			$preloadedLinks = array_unique($preloadedLinks);

			// Remove data for preloaded LINKs
			if (! empty($preloadedLinks)) {
				foreach ($finalCombinableLinks as $fclIndex => $combinableLinkData) {
					if (in_array($combinableLinkData['href'], $preloadedLinks)) {
						unset($finalCombinableLinks[$fclIndex]);
					}
				}
			}

			$finalCombinableLinks = array_values($finalCombinableLinks);

			// Only proceed with the optimization/combine if there's obviously at least 2 combinable URL requests to Google Fonts
			// OR the loading type is different than render-blocking
			if (Main::instance()->settings['google_fonts_combine'] && (Main::instance()->settings['google_fonts_combine_type'] || count($finalCombinableLinks) > 1)) {
				$htmlSource = self::combineGoogleFontLinks($finalCombinableLinks, $htmlSource);
			}
		}

		$htmlSource = self::alterGoogleFontUrlFromInlineStyleTags($htmlSource);
		$htmlSource = str_replace(self::NOSCRIPT_WEB_FONT_LOADER, '', $htmlSource);

		return $htmlSource;
	}

	/**
	 * @param $linkHrefOriginal
	 * @param bool $escHtml
	 * @param $alterFor
	 *
	 * @return string
	 */
	public static function alterGoogleFontLink($linkHrefOriginal, $escHtml = true, $alterFor = 'css')
	{
		// Some special filtering here as some hosting environments (at least staging) behave funny with // inside SCRIPT tags
		if ($alterFor === 'js') {
			$conditionOne = stripos($linkHrefOriginal, str_replace('//', '', self::$containsStr)) === false;
		} else { // css (default)
			$conditionOne = stripos($linkHrefOriginal, self::$containsStr) === false;
		}

		// Do not continue if it doesn't contain the right string or it contains 'display=' or it does not contain 'family=' or there is no value set for "font-display"
		if ($conditionOne ||
		    stripos($linkHrefOriginal, 'display=') !== false ||
		    stripos($linkHrefOriginal, 'family=') === false ||
		    ! Main::instance()->settings['google_fonts_display']) {
			// Return original source
			return $linkHrefOriginal;
		}

		$altLinkHref = str_replace('&#038;', '&', $linkHrefOriginal);

		$urlQuery = parse_url($altLinkHref, PHP_URL_QUERY);
		parse_str($urlQuery, $outputStr);

		// Is there no "display" or there is but it has an empty value? Append the one we have in the "Settings" - "Google Fonts"
		if ( ! isset($outputStr['display']) || (isset($outputStr['display']) && $outputStr['display'] === '') ) {
			$outputStr['display'] = Main::instance()->settings['google_fonts_display'];

			list($linkHrefFirstPart) = explode('?', $linkHrefOriginal);

			// Returned the updated source with the 'display' parameter appended to it
			$afterQuestionMark = http_build_query($outputStr);

			if ($escHtml) {
				$afterQuestionMark = esc_attr($afterQuestionMark);
			}

			return $linkHrefFirstPart . '?' . $afterQuestionMark;
		}

		// Return original source
		return $linkHrefOriginal;
	}

	/**
	 * @param $htmlSource
	 *
	 * @return mixed
	 */
	public static function alterGoogleFontUrlFromInlineStyleTags($htmlSource)
	{
		if (! preg_match('/@import(\s+)url\(/i', $htmlSource)) {
			return $htmlSource;
		}

		preg_match_all('#<\s*?style\b[^>]*>(.*?)</style\b[^>]*>#s', $htmlSource, $styleMatches, PREG_SET_ORDER);

		if (empty($styleMatches)) {
			return $htmlSource;
		}

		// Go through each STYLE tag
		foreach ($styleMatches as $styleInlineArray) {
			list($styleInlineTag, $styleInlineContent) = $styleInlineArray;

			// Check if the STYLE tag has any 'data-wpacu-skip' attribute; if it does, do not continue
			if (preg_match('#data-wpacu-skip([=>/ ])#i', $styleInlineTag)) {
				continue;
			}

			// Is the content relevant?
			if (! preg_match('/@import(\s+|)(url|\(|\'|")/i', $styleInlineContent)
			    || stripos($styleInlineContent, 'fonts.googleapis.com') === false) {
				continue;
			}

			// Do any alteration to the URL of the Google Font
			$newCssOutput = self::alterGoogleFontUrlFromCssContent($styleInlineTag);

			$htmlSource = str_replace($styleInlineTag, $newCssOutput, $htmlSource);
		}

		return $htmlSource;
	}

	/**
	 * @param $cssContent
	 *
	 * @return mixed
	 */
	public static function alterGoogleFontUrlFromCssContent($cssContent)
	{
		if (stripos($cssContent, 'fonts.googleapis.com') === false || ! Main::instance()->settings['google_fonts_display']) {
			return $cssContent;
		}

		$regExps = array('/@import(\s+)url\((.*?)\)(|\s+)\;/i', '/@import(\s+|)(\(|\'|")(.*?)(\'|"|\))\;/i');

		$newCssOutput = $cssContent;

		foreach ($regExps as $regExpIndex => $regExpPattern) {
			preg_match_all($regExpPattern, $cssContent, $matchesFromInlineCode, PREG_SET_ORDER);

			if (! empty($matchesFromInlineCode)) {
				foreach ($matchesFromInlineCode as $matchIndex => $matchesFromInlineCodeArray) {
					$cssImportRule = $matchesFromInlineCodeArray[0];

					if ($regExpIndex === 0) {
						$googleApisUrl = trim($matchesFromInlineCodeArray[2], '"\' ');
					} else {
						$googleApisUrl = trim($matchesFromInlineCodeArray[3], '"\' ');
					}

					// It has to be a Google Fonts API link
					if (stripos($googleApisUrl, 'fonts.googleapis.com') === false) {
						continue;
					}

					$newGoogleApisUrl = self::alterGoogleFontLink($googleApisUrl, false);

					if ($newGoogleApisUrl !== $googleApisUrl) {
						$newCssImportRule = str_replace($googleApisUrl, $newGoogleApisUrl, $cssImportRule);
						$newCssOutput = str_replace($cssImportRule, $newCssImportRule, $newCssOutput);
					}
				}
			}
		}

		return $newCssOutput;
	}

	/**
	 * @param $jsContent
	 *
	 * @return mixed
	 */
	public static function alterGoogleFontUrlFromJsContent($jsContent)
	{
		// Continue only if any of the needles (e.g. fonts.googleapis.com, WebFontConfig) are found in the haystack
		if (stripos($jsContent, 'fonts.googleapis.com') === false &&
		    strpos($jsContent, 'WebFontConfig') === false) {
			return $jsContent;
		}

		$newJsOutput = $jsContent;

		preg_match_all(
			'#fonts.googleapis.com/(.*?)(["\'])#si',
			$jsContent,
			$matchesFromJsCode
		);

		if (isset($matchesFromJsCode[0]) && ! empty($matchesFromJsCode)) {
			foreach ($matchesFromJsCode[0] as $match) {
				$matchRule = $match;
				$googleApisUrl = trim($match, '"\' ');

				$newGoogleApisUrl = self::alterGoogleFontLink($googleApisUrl, false, 'js');

				if ($newGoogleApisUrl !== $googleApisUrl) {
					$newJsMatchOutput = str_replace($googleApisUrl, $newGoogleApisUrl, $matchRule);
					$newJsOutput      = str_replace($matchRule, $newJsMatchOutput, $newJsOutput);
				}
			}
		}

		// Look for any "WebFontConfig = { google: { families: ['font-one', 'font-two'] } }" patterns
		if ( stripos( $jsContent, 'WebFontConfig' ) !== false
		     && preg_match_all( '#WebFontConfig(.*?)google(\s+|):(\s+|){(\s+|)families(\s+|):(?<families>.*?)]#s', $jsContent, $webFontConfigMatches )
		     && isset( $webFontConfigMatches['families'] ) && ! empty( $webFontConfigMatches['families'] )
		) {
			foreach ($webFontConfigMatches['families'] as $webFontConfigKey => $webFontConfigMatch) {
				$originalWholeMatch  = $webFontConfigMatches[0][$webFontConfigKey];
				$familiesMatchOutput = trim($webFontConfigMatch);

				// NO match or existing "display" parameter was found? Do not continue
				if (! $familiesMatchOutput || strpos($familiesMatchOutput, 'display=')) {
					continue;
				}

				// Alter the matched string
				$familiesNewOutput      = preg_replace('/([\'"])$/', '&display='.Main::instance()->settings['google_fonts_display'].'\\1', $familiesMatchOutput);
				$newWebFontConfigOutput = str_replace($familiesMatchOutput, $familiesNewOutput, $originalWholeMatch);

				// Finally, do the replacement
				$newJsOutput            = str_replace($originalWholeMatch, $newWebFontConfigOutput, $newJsOutput);
			}
		}

		return $newJsOutput;
	}

	/**
	 * @param $finalLinks
	 * @param $htmlSource
	 *
	 * @return false|mixed|string|void
	 */
	public static function combineGoogleFontLinks($finalLinks, $htmlSource)
	{
		$fontsArray = array();

		foreach ($finalLinks as $finalLinkIndex => $finalLinkData) {
			$finalLinkHref = $finalLinkData['href'];
			$finalLinkHref = str_replace('&#038;', '&', $finalLinkHref);

			$queries = parse_url($finalLinkHref, PHP_URL_QUERY);
			parse_str($queries, $fontQueries);

			if (! array_key_exists('family', $fontQueries) || array_key_exists('text', $fontQueries)) {
				continue;
			}

			// Strip the existing tag, leave a mark where the final combined LINK will be placed
			$stripTagWith = ($finalLinkIndex === 0) ? self::COMBINED_LINK_DEL : '';
			$finalLinkTag = $finalLinkData['tag'];

			$htmlSource = str_ireplace(array($finalLinkTag."\n", $finalLinkTag), $stripTagWith, $htmlSource);

			$family = trim($fontQueries['family']);
			$family = trim($family, '|');

			if (! $family) {
				continue;
			}

			if (strpos($family, '|') !== false) {
				// More than one family per request?
				foreach (explode('|', $family) as $familyOne) {
					if (strpos($familyOne, ':') !== false) {
						// They have types
						list ($familyRaw, $familyTypes) = explode(':', $familyOne);
						$fontsArray['families'][$familyRaw]['types'] = self::buildSortTypesList($familyTypes);
					} else {
						// They do not have types
						$familyRaw = $familyOne;
						$fontsArray['families'][$familyRaw]['types'] = false;
					}
				}
			} elseif (strpos($family, ':') !== false) {
				list ($familyRaw, $familyTypes) = explode(':', $family);
				$fontsArray['families'][$familyRaw]['types'] = self::buildSortTypesList($familyTypes);
			} else {
				$familyRaw = $family;
				$fontsArray['families'][$familyRaw]['types'] = false;
			}

			if (array_key_exists('subset', $fontQueries)) {
				// More than one subset per request?
				if (strpos($fontQueries['subset'], ',') !== false) {
					$multipleSubsets = explode(',', trim($fontQueries['subset'], ','));

					foreach ($multipleSubsets as $subset) {
						$fontsArray['subsets'][] = trim($subset);
					}
				} else {
					// Only one subset
					$fontsArray['subsets'][] = $fontQueries['subset'];
				}
			}

			if (array_key_exists('effect', $fontQueries)) {
				// More than one subset per request?
				if (strpos($fontQueries['effect'], '|') !== false) {
					$multipleSubsets = explode('|', trim($fontQueries['effect'], '|'));

					foreach ($multipleSubsets as $subset) {
						$fontsArray['effects'][] = trim($subset);
					}
				} else {
					// Only one subset
					$fontsArray['effects'][] = $fontQueries['effect'];
				}
			}
		}

		if (! empty($fontsArray)) {
			$finalCombinedParameters = '';
			ksort($fontsArray['families']);

			// Families
			foreach ($fontsArray['families'] as $familyRaw => $fontValues) {
				$finalCombinedParameters .= str_replace(' ', '+', $familyRaw);

				// Any types? e.g. 400, 400italic, bold, etc.
				if (isset($fontValues['types']) && $fontValues['types'] !== false) {
					$finalCombinedParameters .= ':' . $fontValues['types'];
				}

				$finalCombinedParameters .= '|';
			}

			$finalCombinedParameters = trim($finalCombinedParameters, '|');

			// Subsets
			if (isset($fontsArray['subsets']) && ! empty($fontsArray['subsets'])) {
				sort($fontsArray['subsets']);
				$finalCombinedParameters .= '&subset=' . implode(',', array_unique($fontsArray['subsets']));
			}

			// Effects
			if (isset($fontsArray['effects']) && ! empty($fontsArray['effects'])) {
				sort($fontsArray['effects']);
				$finalCombinedParameters .= '&effect=' . implode('|', array_unique($fontsArray['effects']));
			}

			if ($fontDisplay = Main::instance()->settings['google_fonts_display']) {
				$finalCombinedParameters .= '&display=' . $fontDisplay;
			}

			$finalCombinedParameters = esc_attr($finalCombinedParameters);

			// This is needed for both render-blocking and async (within NOSCRIPT tag as a fallback)
			$finalCombinedLink = <<<HTML
<link rel='stylesheet' id='wpacu-combined-google-fonts-css' href='https://fonts.googleapis.com/css?family={$finalCombinedParameters}' type='text/css' media='all' />
HTML;
			/*
			 * Loading Type: Render-Blocking (Default)
			 */
			if (! Main::instance()->settings['google_fonts_combine_type']) {
				$finalCombinedLink .= "\n";
				$htmlSource = str_replace(self::COMBINED_LINK_DEL, apply_filters('wpacu_combined_google_fonts_link_tag', $finalCombinedLink), $htmlSource);
			}

			/*
			 * Loading Type: Asynchronous via LINK preload with fallback
			 */
			if (Main::instance()->settings['google_fonts_combine_type'] === 'async_preload') {
				$finalPreloadCombinedLink = <<<HTML
<link rel='preload' as="style" onload="this.rel='stylesheet'" data-wpacu-preload-it-async='1' id='wpacu-combined-google-fonts-css-async-preload' href='https://fonts.googleapis.com/css?family={$finalCombinedParameters}' type='text/css' media='all' />
HTML;
				$finalPreloadCombinedLink .= "\n".Misc::preloadAsyncCssFallbackOutput();

				$htmlSource = str_replace(self::COMBINED_LINK_DEL, apply_filters('wpacu_combined_google_fonts_async_preload_link_tag', $finalPreloadCombinedLink), $htmlSource);
			}

			/*
			 * Loading Type: Asynchronous via Web Font Loader (webfont.js) with fallback
			 */
			if (Main::instance()->settings['google_fonts_combine_type'] === 'async') { // Async via Web Font Loader
				$subSetsStr = '';

				if (isset($fontsArray['subsets']) && ! empty($fontsArray['subsets'])) {
					sort($fontsArray['subsets']);
					$subSetsStr = implode(',', array_unique($fontsArray['subsets']));
				}

				$wfConfigGoogleFamilies = array();

				// Families
				$iCount = 0;

				foreach ($fontsArray['families'] as $familyRaw => $fontValues) {
					$wfConfigGoogleFamily = str_replace(' ', '+', $familyRaw);

					// Any types? e.g. 400, 400italic, bold, etc.
					$hasTypes = false;
					if (isset($fontValues['types']) && $fontValues['types']) {
						$wfConfigGoogleFamily .= ':'.$fontValues['types'];
						$hasTypes = true;
					}

					if ($subSetsStr) {
						// If there are types, continue to use the comma delimiter
						$wfConfigGoogleFamily .= ($hasTypes ? ',' : ':') . $subSetsStr;
					}

					// Append extra parameters to the last family from the list
					if ($iCount === count($fontsArray['families']) - 1) {
						// Effects
						if (isset($fontsArray['effects']) && ! empty($fontsArray['effects'])) {
							sort($fontsArray['effects']);
							$wfConfigGoogleFamily .= '&effect=' . implode('|', array_unique($fontsArray['effects']));
						}

						if ($fontDisplay = Main::instance()->settings['google_fonts_display']) {
							$wfConfigGoogleFamily .= '&display=' . $fontDisplay;
						}
					}

					$wfConfigGoogleFamilies[] = "'".$wfConfigGoogleFamily."'";

					$iCount++;
				}

				$wfConfigGoogleFamiliesStr = '['.implode(',', $wfConfigGoogleFamilies).']';

				$finalInlineTagWebFontConfig = '<script id=\'wpacu-google-fonts-async-load\' type=\'text/javascript\'>'."\n".'WebFontConfig={google:{families:'.$wfConfigGoogleFamiliesStr.'}};(function(wpacuD){var wpacuWf=wpacuD.createElement(\'script\'),wpacuS=wpacuD.scripts[0];wpacuWf.src=(\'https:\'===document.location.protocol?\'https\':\'http\')+\'://ajax.googleapis.com/ajax/libs/webfont/1.6.26/webfont.js\';wpacuWf.async=!0;wpacuS.parentNode.insertBefore(wpacuWf,wpacuS)})(document);'."\n".'</script>';

				$htmlSource = str_replace(
					array(
						self::COMBINED_LINK_DEL,
						self::NOSCRIPT_WEB_FONT_LOADER
					),
					array(
						apply_filters( 'wpacu_combined_google_fonts_inline_script_tag', $finalInlineTagWebFontConfig ),
						'<noscript>' . apply_filters( 'wpacu_combined_google_fonts_link_tag', $finalCombinedLink ) . '</noscript>' . "\n"
					),
					$htmlSource
				);
			}
		}

		return $htmlSource;
	}

	/**
	 * e.g. 300, 400, 400italic, bold, etc.
	 *
	 * @param $types
	 *
	 * @return string
	 */
	public static function buildSortTypesList($types)
	{
		$newTypes = array();

		// More than one type per family?
		if (strpos($types, ',') !== false) {
			$multipleTypes = explode(',', trim($types, ','));

			foreach ($multipleTypes as $type) {
				if (trim($type)) {
					$newTypes[] = trim($type);
				}
			}
		} else {
			// Only one type per family
			$newTypes[] = $types;
		}

		$newTypes = array_unique($newTypes);

		sort($newTypes);

		return implode(',', $newTypes);
	}
}
