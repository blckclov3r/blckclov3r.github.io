<?php
namespace WpAssetCleanUp\OptimiseAssets;

/**
 * Class FontsGoogle
 * @package WpAssetCleanUp\OptimiseAssets
 */
class FontsGoogleRemove
{
	/**
	 * @var array
	 */
	public static $stringsToCheck = array(
		'//fonts.googleapis.com',
		'//fonts.gstatic.com'
	);

	/**
	 * @var array
	 */
	public static $possibleWebFontConfigCdnPatterns = array(
		'//ajax.googleapis.com/ajax/libs/webfont/(.*?)', // Google Apis
		'//cdnjs.cloudflare.com/ajax/libs/webfont/(.*?)', // Cloudflare
		'//cdn.jsdelivr.net/npm/webfontloader@(.*?)' // jsDELIVR
	);

	/**
	 * Called late from OptimizeCss after all other optimizations are done (e.g. minify, combine)
	 *
	 * @param $htmlSource
	 *
	 * @return mixed
	 */
	public static function cleanHtmlSource($htmlSource)
	{
		$htmlSource = self::cleanLinkTags($htmlSource);
		$htmlSource = self::cleanFromInlineStyleTags($htmlSource);
		$htmlSource = str_replace(FontsGoogle::NOSCRIPT_WEB_FONT_LOADER, '', $htmlSource);

		return $htmlSource;
	}

	/**
	 * @param $htmlSource
	 *
	 * @return mixed
	 */
	public static function cleanLinkTags($htmlSource)
	{
		// Do not continue if there is no single reference to the string we look for in the clean HTML source
		if (stripos($htmlSource, FontsGoogle::$containsStr) === false) {
			return $htmlSource;
		}

		// Get all valid LINKs that have the self::$stringsToCheck within them
		$strContainsArray = array_map(static function($containsStr) {
			return preg_quote($containsStr, '/');
		}, self::$stringsToCheck);

		$strContainsFormat = implode('|', $strContainsArray);

		preg_match_all('#<link[^>]*(' . $strContainsFormat . ').*(>)#Usmi', $htmlSource, $matchesFromLinkTags, PREG_SET_ORDER);

		$stripLinksList = array();

		// Needs to match at least one to carry on with the replacements
		if (isset($matchesFromLinkTags[0]) && ! empty($matchesFromLinkTags[0])) {
			foreach ($matchesFromLinkTags as $linkIndex => $linkTagArray) {
				$linkTag = trim(trim($linkTagArray[0], '"\''));

				if (strip_tags($linkTag) !== '') {
					continue; // Something might be funny there, make sure the tag is valid
				}

				// Check if the Google Fonts CSS has any 'data-wpacu-skip' attribute; if it does, do not remove it
				if (preg_match('#data-wpacu-skip([=>/ ])#i', $linkTag)) {
					continue;
				}

				$stripLinksList[$linkTag] = '';
			}

			$htmlSource = strtr($htmlSource, $stripLinksList);
		}

		return $htmlSource;
	}

	/**
	 * @param $htmlSource
	 *
	 * @return mixed
	 */
	public static function cleanFromInlineStyleTags($htmlSource)
	{
		if (! preg_match('/(;?)(@import (?<url>url\(|\()?(?P<quotes>["\'()]?).+?(?P=quotes)(?(url)\)));?/', $htmlSource)) {
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

			$newStyleInlineTag = $styleInlineTag;
			$newStyleInlineContent = $styleInlineContent;

			// Is the content relevant?
			preg_match_all('/(;?)(@import (?<url>url\(|\()?(?P<quotes>["\'()]?).+?(?P=quotes)(?(url)\)));?/', $styleInlineContent, $matches);

			if (isset($matches[0]) && ! empty($matches[0])) {
				foreach ($matches[0] as $matchedImport) {
					$newStyleInlineContent = str_replace($matchedImport, '', $newStyleInlineContent);
				}

				$newStyleInlineContent = trim($newStyleInlineContent);

				// Is the STYLE tag empty after the @imports are removed? It happens on some websites; strip the tag, no point of having it empty
				if ($newStyleInlineContent === '') {
					$htmlSource = str_replace($styleInlineTag, '', $htmlSource);
				} else {
					$newStyleInlineTag = str_replace($styleInlineContent, $newStyleInlineContent, $styleInlineTag);
					$htmlSource = str_replace($styleInlineTag, $newStyleInlineTag, $htmlSource);
				}
			}

			$styleTagAfterImportsCleaned = $newStyleInlineTag;
			$styleTagAfterFontFaceCleaned = trim(self::cleanFontFaceReferences($newStyleInlineContent));
			$newStyleInlineTag = str_replace($newStyleInlineContent, $styleTagAfterFontFaceCleaned, $newStyleInlineTag);

			$htmlSource = str_replace($styleTagAfterImportsCleaned, $newStyleInlineTag, $htmlSource);
		}

		return $htmlSource;
	}

	/**
	 * @param $importsAddToTop
	 *
	 * @return mixed
	 */
	public static function stripGoogleApisImport($importsAddToTop)
	{
		// Remove any Google Fonts imports
		foreach ($importsAddToTop as $importKey => $importToPrepend) {
			if (stripos($importToPrepend, FontsGoogle::$containsStr) !== false) {
				unset($importsAddToTop[$importKey]);
			}
		}

		return $importsAddToTop;
	}

	/**
	 * If "Google Font Remove" is active, strip its references from JavaScript code as well
	 *
	 * @param $jsContent
	 *
	 * @return string|string[]|null
	 */
	public static function stripReferencesFromJsCode($jsContent)
	{
		$webFontConfigReferenceOne = "#src(\s+|)=(\s+|)(?<startDel>'|\")(\s+|)((http:|https:|)(".implode('|', self::$possibleWebFontConfigCdnPatterns).")(\s+|))(?<endDel>'|\")#si";

		if (stripos($jsContent, 'WebFontConfig') !== false
		    && preg_match('/(WebFontConfig\.|\'|"|)google(\s+|)([\'":=])/i', $jsContent)
		    && preg_match_all($webFontConfigReferenceOne, $jsContent, $matches) && ! empty($matches)
		) {
			foreach ($matches[0] as $matchIndex => $matchRow) {
				$jsContent = str_replace(
					$matchRow,
					'src=' . $matches['startDel'][$matchIndex] . $matches['endDel'][$matchIndex] . ';/* Stripped by ' . WPACU_PLUGIN_TITLE . ' */',
					$jsContent
				);
			}
		}

		$webFontConfigReferenceTwo = '#("|\')((http:|https:|)//fonts.googleapis.com/(.*?))("|\')#si';

		if (preg_match($webFontConfigReferenceTwo, $jsContent)) {
			$jsContent = preg_replace($webFontConfigReferenceTwo, '\\1\\5', $jsContent);
		}

		return $jsContent;
	}

	/**
	 * @param $cssContent
	 *
	 * @return mixed
	 */
	public static function cleanFontFaceReferences($cssContent)
	{
		preg_match_all('#@font-face(|\s+){(.*?)}#si', $cssContent, $matchesFromCssCode, PREG_SET_ORDER);

		if (! empty($matchesFromCssCode)) {
			foreach ($matchesFromCssCode as $matches) {
				$fontFaceSyntax = $matches[0];
				preg_match_all('/url(\s+|)\((?![\'"]?(?:data):)[\'"]?([^\'")]*)[\'"]?\)/i', $matches[0], $matchesFromUrlSyntax);

				if (! empty($matchesFromUrlSyntax) && stripos(implode('', $matchesFromUrlSyntax[0]), '//fonts.gstatic.com/') !== false) {
					$cssContent = str_replace($fontFaceSyntax, '', $cssContent);
				}
			}
		}

		return $cssContent;
	}
}
