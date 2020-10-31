<?php
namespace WpAssetCleanUp;

use WpAssetCleanUp\OptimiseAssets\OptimizeCommon;
use WpAssetCleanUp\OptimiseAssets\OptimizeJs;

/**
 * Class HardcodedAssets
 * @package WpAssetCleanUp
 */
class HardcodedAssets
{
	/**
	 *
	 */
	public static function init()
	{
		add_action( 'init', static function() {
			if (Main::instance()->isGetAssetsCall) {
				// Case 1: An AJAX call is made from the Dashboard
				self::initBufferingForAjaxCallFromTheDashboard();
			} elseif (self::useBufferingForEditFrontEndView()) {
				// Case 2: The logged-in admin manages the assets from the front-end view
				self::initBufferingForFrontendManagement();
			}
		});
	}

	/**
	 *
	 */
	public static function initBufferingForAjaxCallFromTheDashboard()
	{
		ob_start();

		add_action('shutdown', static function() {
			$htmlSource = '';

			// We'll need to get the number of ob levels we're in, so that we can iterate over each, collecting
			// that buffer's output into the final output.
			$htmlSourceLevel = ob_get_level();

			for ($wpacuI = 0; $wpacuI < $htmlSourceLevel; $wpacuI++) {
				$htmlSource .= ob_get_clean();
			}

			$anyHardCodedAssets = HardcodedAssets::getAll($htmlSource); // Fetch all for this type of request

			$htmlSource = str_replace('{wpacu_hardcoded_assets}', $anyHardCodedAssets, $htmlSource);

			echo $htmlSource;
		}, 0);
	}

	/**
	 *
	 */
	public static function initBufferingForFrontendManagement()
	{
		// Used to print the hardcoded CSS/JS
		ob_start();

		add_action('shutdown', static function() {
			$htmlSource = '';

			// We'll need to get the number of ob levels we're in, so that we can iterate over each, collecting
			// that buffer's output into the final output.
			$htmlSourceLevel = ob_get_level();

			for ($wpacuI = 0; $wpacuI < $htmlSourceLevel; $wpacuI++) {
				$htmlSource .= ob_get_clean();
			}

			echo self::addHardcodedAssetsForEditFrontEndView(OptimizeCommon::alterHtmlSource($htmlSource));
		}, 0);
	}

	/**
	 * @param $htmlSource
	 *
	 * @return string|string[]
	 */
	public static function addHardcodedAssetsForEditFrontEndView($htmlSource)
	{
		if ( ! ($anyHardCodedAssets = ObjectCache::wpacu_cache_get('wpacu_hardcoded_assets_encoded')) ) {
			$htmlSource = str_replace( '{wpacu_assets_collapsible_wrap_hardcoded_list}', '', $htmlSource);
			return $htmlSource;
		}

		$jsonH = base64_decode($anyHardCodedAssets);

		$wpacuPrintHardcodedManagementList = static function($jsonH) {
			$data = ObjectCache::wpacu_cache_get('wpacu_settings_frontend_data') ?: array();
			$data['do_not_print_list'] = true;
			$data['all']['hardcoded'] = (array)json_decode($jsonH, ARRAY_A);
			ob_start();
			include_once WPACU_PLUGIN_DIR.'/templates/meta-box-loaded-assets/_assets-hardcoded-list.php'; // generate $hardcodedTagsOutput
			return ob_get_clean();
		};

		$htmlSource = str_replace(
			'{wpacu_assets_collapsible_wrap_hardcoded_list}',
			$wpacuPrintHardcodedManagementList($jsonH), // call the function to return the HTML output
			$htmlSource
		);

		return $htmlSource;
	}

	/**
	 * @return bool
	 */
	public static function useBufferingForEditFrontEndView()
	{
		// The logged-in admin needs to be outside the Dashboard (in the front-end view)
		// "Manage in the Front-end" is enabled in "Settings" -> "Plugin Usage Preferences"
		return (Main::instance()->frontendShow() && ! is_admin() && Menu::userCanManageAssets() && ! Main::instance()->isGetAssetsCall);
	}

	/**
	 * @param $htmlSource
	 * @param bool $encodeIt - if set to "false", it's mostly for testing purposes
	 *
	 * @return string|array
	 */
	public static function getAll($htmlSource, $encodeIt = true)
	{
		$htmlSourceAlt = CleanUp::removeHtmlComments($htmlSource, true);

		$collectLinkStyles = true; // default
		$collectScripts    = true; // default

		$hardCodedAssets = array(
			'link_and_style_tags'        => array(), // LINK (rel="stylesheet") & STYLE (inline)
			'script_src_and_inline_tags' => array(), // SCRIPT (with "src" attribute) & SCRIPT (inline)
		);

		if ($collectLinkStyles) {
			/*
			* [START] Collect Hardcoded LINK (stylesheet) & STYLE tags
			*/
			preg_match_all( '#(?=(?P<link_tag><link[^>]*stylesheet[^>]*(>)))|(?=(?P<style_tag><style[^>]*?>.*</style>))#Umsi',
				$htmlSourceAlt, $matchesSourcesFromTags, PREG_SET_ORDER );

			if ( ! empty( $matchesSourcesFromTags ) ) {
				// Only the hashes are set
				// For instance, 'd1eae32c4e99d24573042dfbb71f5258a86e2a8e' is the hash for the following script:
				/*
				* <style media="print">#wpadminbar { display:none; }</style>
				 */
				$stripsSpecificStylesHashes = array(
					'5ead5f033961f3b8db362d2ede500051f659dd6d',
					'25bd090513716c34b48b0495c834d2070088ad24'
				);

				// Sometimes, the hash checking might failed (if there's a small change to the JS content)
				// Consider using a fallback verification by checking the actual content
				$stripsSpecificStylesContaining = array(
					'<style media="print">#wpadminbar { display:none; }</style>'
				);

				foreach ( $matchesSourcesFromTags as $matchedTagIndex => $matchedTag ) {
					// LINK "stylesheet" tags (if any)
					if ( isset( $matchedTag['link_tag'] ) && trim( $matchedTag['link_tag'] ) !== '' && ( trim( strip_tags( $matchedTag['link_tag'] ) ) === '' ) ) {
						$matchedTagOutput = trim( $matchedTag['link_tag'] );

						if ( strpos( $matchedTagOutput, 'data-wpacu-style-handle=' ) !== false ) {
							// Skip the LINK with src that was enqueued properly and keep the hardcoded ones
							continue;
						}

						$hardCodedAssets['link_and_style_tags'][] = $matchedTagOutput;
					}

					// STYLE inline tags (if any)
					if ( isset( $matchedTag['style_tag'] ) && trim( $matchedTag['style_tag'] ) !== '' ) {
						$matchedTagOutput = trim( $matchedTag['style_tag'] );

						/*
						 * Strip certain STYLE tags irrelevant for the list (e.g. related to the WordPress Admin Bar, etc.)
						*/
						if ( in_array( sha1( $matchedTagOutput ), $stripsSpecificStylesHashes ) ) {
							continue;
						}

						foreach ( $stripsSpecificStylesContaining as $cssContentTargeted ) {
							if ( strpos( $matchedTagOutput, $cssContentTargeted ) !== false ) {
								continue;
							}
						}

						// Do not add to the list elements such as Emojis (not relevant for hard-coded tags)
						if ( strpos( $matchedTagOutput, 'img.wp-smiley' )  !== false
						     && strpos( $matchedTagOutput, 'img.emoji' )   !== false
						     && strpos( $matchedTagOutput, '!important;' ) !== false ) {
							continue;
						}

						if ( (strpos( $matchedTagOutput, 'data-wpacu-own-inline-style=' ) !== false) ||
						     (strpos( $matchedTagOutput, 'data-wpacu-inline-css-file=')   !== false) ) {
							// remove plugin's own STYLE tags as they are not relevant in this context
							continue;
						}

						foreach ( wp_styles()->done as $cssHandle ) {
							if ( strpos( $matchedTagOutput,
									'<style id=\'' . trim( $cssHandle ) . '-inline-css\'' ) !== false ) {
								// Do not consider the STYLE added via WordPress with wp_add_inline_style() as it's not hardcoded
								continue 2;
							}
						}

						$hardCodedAssets['link_and_style_tags'][] = $matchedTagOutput;
					}
				}
			}
			/*
			* [END] Collect Hardcoded LINK (stylesheet) & STYLE tags
			*/
		}

		if ($collectScripts) {
			/*
			* [START] Collect Hardcoded SCRIPT (src/inline)
			*/
			preg_match_all( '@<script[^>]*?>.*?</script>@si', $htmlSourceAlt, $matchesScriptTags, PREG_SET_ORDER );

			if ( isset( wp_scripts()->done ) && ! empty( wp_scripts()->done ) ) {
				$allInlineAssocWithJsHandle = array();

				foreach ( wp_scripts()->done as $assetHandle ) {
					// Now, go through the list of inline SCRIPTs associated with an enqueued SCRIPT (with "src" attribute)
					// And make sure they do not show to the hardcoded list, since they are related to the handle and they are stripped when the handle is dequeued
					$anyInlineAssocWithJsHandle = OptimizeJs::getInlineAssociatedWithScriptHandle( $assetHandle, wp_scripts()->registered, 'handle' );
					if ( ! empty( $anyInlineAssocWithJsHandle ) ) {
						foreach ( $anyInlineAssocWithJsHandle as $jsInlineTagContent ) {
							if ( trim( $jsInlineTagContent ) === '' ) {
								continue;
							}

							$allInlineAssocWithJsHandle[] = trim($jsInlineTagContent);
						}
					}
				}

				$allInlineAssocWithJsHandle = array_unique($allInlineAssocWithJsHandle);
				}

			// Go through the hardcoded SCRIPT tags
			if ( isset( $matchesScriptTags ) && ! empty( $matchesScriptTags ) ) {
				// Only the hashes are set
				// For instance, 'd1eae32c4e99d24573042dfbb71f5258a86e2a8e' is the hash for the following script:
				/*
				 * <script>
				(function() {
					var request, b = document.body, c = 'className', cs = 'customize-support', rcs = new RegExp('(^|\\s+)(no-)?'+cs+'(\\s+|$)');
						request = true;
					b[c] = b[c].replace( rcs, ' ' );
					// The customizer requires postMessage and CORS (if the site is cross domain)
					b[c] += ( window.postMessage && request ? ' ' : ' no-' ) + cs;
				}());
				</script>
				 */
				$stripsSpecificScriptsHashes = array(
					'd1eae32c4e99d24573042dfbb71f5258a86e2a8e',
					'1a8f46f9f33e5d95919620df54781acbfa9efff7'
				);

				// Sometimes, the hash checking might failed (if there's a small change to the JS content)
				// Consider using a fallback verification by checking the actual content
				$stripsSpecificScriptsContaining = array(
					'// The customizer requires postMessage and CORS (if the site is cross domain)',
					'b[c] += ( window.postMessage && request ? \' \' : \' no-\' ) + cs;',
					"(function(){var request,b=document.body,c='className',cs='customize-support',rcs=new RegExp('(^|\\s+)(no-)?'+cs+'(\\s+|$)');request=!0;b[c]=b[c].replace(rcs,' ');b[c]+=(window.postMessage&&request?' ':' no-')+cs}())",
					'document.body.className = document.body.className.replace( /(^|\s)(no-)?customize-support(?=\s|$)/, \'\' ) + \' no-customize-support\''
				);

				foreach ( $matchesScriptTags as $matchedTag ) {
					if ( isset( $matchedTag[0] ) && $matchedTag[0] && strpos( $matchedTag[0], '<script' ) === 0 ) {
						$matchedTagOutput = trim( $matchedTag[0] );

						/*
						 * Strip certain SCRIPT tags irrelevant for the list (e.g. related to WordPress Customiser, Admin Bar, etc.)
						*/
						if ( in_array( sha1( $matchedTagOutput ), $stripsSpecificScriptsHashes ) ) {
							continue;
						}

						foreach ( $stripsSpecificScriptsContaining as $jsContentTargeted ) {
							if ( strpos( $matchedTagOutput, $jsContentTargeted ) !== false ) {
								continue;
							}
						}

						if ( strpos( $matchedTagOutput, 'window._wpemojiSettings' ) !== false
						     && strpos( $matchedTagOutput, 'twemoji' ) !== false ) {
							continue;
						}

						// Check the type and only allow SCRIPT tags with type='text/javascript' or no type at all (it default to 'text/javascript')
						$matchedTagInner    = strip_tags( $matchedTagOutput );
						$matchedTagOnlyTags = str_replace( $matchedTagInner, '', $matchedTagOutput );
						preg_match_all( '#type=(["\'])' . '(.*)' . '(["\'])#Usmi', $matchedTagOnlyTags,
							$outputMatches );
						$scriptType = isset( $outputMatches[2][0] ) ? trim( $outputMatches[2][0],
							'"\'' ) : 'text/javascript';

						if ( strpos( $scriptType, 'text/javascript' ) === false ) {
							continue;
						}

						if ( strpos( $matchedTagOutput, 'data-wpacu-script-handle=' ) !== false ) {
							// skip the SCRIPT with src that was enqueued properly and keep the hardcoded ones
							continue;
						}

						if ( (strpos( $matchedTagOutput, 'data-wpacu-own-inline-script=' ) !== false) ||
						     (strpos( $matchedTagOutput, 'data-wpacu-inline-js-file=' )    !== false) ) {
							// skip plugin's own SCRIPT tags as they are not relevant in this context
							continue;
						}

						if ( strpos( $matchedTagOutput, 'wpacu-preload-async-css-fallback' ) !== false ) {
							// skip plugin's own SCRIPT tags as they are not relevant in this context
							continue;
						}

						$hasSrc = false;

						if (strpos($matchedTagOnlyTags, ' src=') !== false) {
							$hasSrc = true;
						}

						if ( ! $hasSrc && ! empty( $allInlineAssocWithJsHandle ) ) {
							preg_match_all("'<script[^>]*?>(.*?)</script>'si", $matchedTagOutput, $matchesFromTagOutput);
							$matchedTagOutputInner = isset($matchesFromTagOutput[1][0]) && trim($matchesFromTagOutput[1][0])
								? trim($matchesFromTagOutput[1][0]) : false;

							$matchedTagOutputInnerCleaner = $matchedTagOutputInner;

							$stripStrStart = '/* <![CDATA[ */';
							$stripStrEnd   = '/* ]]> */';

							if (strpos($matchedTagOutputInnerCleaner, $stripStrStart) === 0
							    && Misc::endsWith($matchedTagOutputInnerCleaner, '/* ]]> */')) {
								$matchedTagOutputInnerCleaner = substr($matchedTagOutputInnerCleaner, strlen($stripStrStart));
								$matchedTagOutputInnerCleaner = substr($matchedTagOutputInnerCleaner, 0, -strlen($stripStrEnd));
								$matchedTagOutputInnerCleaner = trim($matchedTagOutputInnerCleaner);
							}

							if (in_array($matchedTagOutputInnerCleaner, $allInlineAssocWithJsHandle)) {
								continue;
							}

							}

						$hardCodedAssets['script_src_and_inline_tags'][] = trim( $matchedTag[0] );
					}
				}
			}
			/*
			* [END] Collect Hardcoded SCRIPT (src/inline)
			*/
		}

		$tagsWithinConditionalComments = self::extractHtmlFromConditionalComments( $htmlSourceAlt );

		if (self::useBufferingForEditFrontEndView()) {
			// Triggered within the front-end view (when admin is logged-in and manages the assets)
			ObjectCache::wpacu_cache_set( 'wpacu_hardcoded_content_within_conditional_comments', $tagsWithinConditionalComments );
		} elseif (Main::instance()->isGetAssetsCall) {
			// AJAX call within the Dashboard
			$hardCodedAssets['within_conditional_comments'] = $tagsWithinConditionalComments;
		}

		if ($encodeIt) {
			return base64_encode( json_encode( $hardCodedAssets ) );
		}

		return $hardCodedAssets;
	}

	/**
	 * @param $htmlSource
	 *
	 * @return mixed
	 */
	/*
	public static function removeHtmlCommentsExceptMSIE($htmlSource)
	{
		// No comments? Do not continue
		if (strpos($htmlSource, '<!--') === false) {
			return $htmlSource;
		}

		if (! (function_exists('libxml_use_internal_errors') && function_exists('libxml_clear_errors') && class_exists('\DOMDocument'))) {
			return $htmlSource;
		}

		// First, collect all MSIE comments
		preg_match_all('#<!--\[if(.*?)]>(<!-->|-->|\s|)(.*?)(<!--<!|<!)\[endif]-->#si', $htmlSource, $matchedMSIEComments);

		$allMSIEComments = array();

		if (isset($matchedMSIEComments[0]) && ! empty($matchedMSIEComments[0])) {
			foreach ($matchedMSIEComments[0] as $matchedMSIEComment) {
				$allMSIEComments[] = $matchedMSIEComment;
			}
		}

		if (! ($domTag = ObjectCache::wpacu_cache_get('wpacu_html_dom_tag'))) {
			$domTag = new \DOMDocument();
			libxml_use_internal_errors( true );
			$domTag->loadHTML( $htmlSource );
		}

		$xpathComments = new \DOMXPath($domTag);
		$comments = $xpathComments->query('//comment()');

		libxml_clear_errors();

		if ($comments === null) {
			return $htmlSource;
		}

		preg_match_all('#<!--(.*?)-->#s', $htmlSource, $matchesRegExpComments);

		// "comments" within tag attributes or script tags?
		// e.g. <script>var type='<!-- A comment here -->';</script>
		// e.g. <div data-info="This is just a <!-- comment --> text">Content here</div>
		$commentsWithinQuotes = array();

		if (isset($matchesRegExpComments[1]) && count($matchesRegExpComments[1]) !== count($comments)) {
			preg_match_all('#=(|\s+)([\'"])(|\s+)<!--(.*?)-->(|\s+)([\'"])#s', $htmlSource, $matchesCommentsWithinQuotes);

			if (isset($matchesCommentsWithinQuotes[0]) && ! empty($matchesCommentsWithinQuotes[0])) {
				foreach ($matchesCommentsWithinQuotes[0] as $matchedDataOriginal) {
					$matchedDataUpdated = str_replace(
						array('', '<!--', '-->'),
						array('--wpacu-space-del--', '--wpacu-start-comm--', '--wpacu-end-comm--'),
						$matchedDataOriginal
					);

					$htmlSource = str_replace($matchedDataOriginal, $matchedDataUpdated, $htmlSource);

					$commentsWithinQuotes[] = array(
						'original' => $matchedDataOriginal,
						'updated'  => $matchedDataUpdated
					);
				}
			}
		}

		foreach ($comments as $comment) {
			$entireComment = CleanUp::getOuterHTML($comment);

			$htmlSource = str_replace(
				array(
					$entireComment,
					'<!--' . $comment->nodeValue . '-->'
				),
				'',
				$htmlSource
			);
		}

		if (! empty($commentsWithinQuotes)) {
			foreach ($commentsWithinQuotes as $commentQuote) {
				$htmlSource = str_replace($commentQuote['updated'], $commentQuote['original'], $htmlSource);
			}
		}

		return $htmlSource;
	}
	*/
	//endRemoveIf(development)

	/**
	 * @param $htmlSource
	 *
	 * @return array
	 */
	public static function extractHtmlFromConditionalComments($htmlSource)
	{
		preg_match_all('#<!--\[if(.*?)]>(<!-->|-->|\s|)(.*?)(<!--<!|<!)\[endif]-->#si', $htmlSource, $matchedContent);

		if (isset($matchedContent[1], $matchedContent[3]) && ! empty($matchedContent[1]) && ! empty($matchedContent[3])) {
			$conditions = array_map('trim', $matchedContent[1]);
			$tags       = array_map('trim', $matchedContent[3]);

			return array(
				'conditions' => $conditions,
				'tags'       => $tags,
			);
		}

		return array();
	}

	/**
	 * @param $targetedTag
	 * @param $contentWithinConditionalComments
	 *
	 * @return bool
	 */
	public static function isWithinConditionalComment($targetedTag, $contentWithinConditionalComments)
	{
		if (empty($contentWithinConditionalComments)) {
			return false;
		}

		$targetedTag = trim($targetedTag);

		foreach ($contentWithinConditionalComments['tags'] as $tagIndex => $tagFromList) {
			$tagFromList = trim($tagFromList);

			if ($targetedTag === $tagFromList || strpos($targetedTag, $tagFromList) !== false) {
				return $contentWithinConditionalComments['conditions'][$tagIndex]; // Stops here and returns the condition
				break;
			}
		}

		return false; // Not within a conditional comment (most cases)
	}

	/**
	 * @param $htmlTag
	 *
	 * @return bool|string
	 */
	public static function belongsTo($htmlTag)
	{
		$belongList = array(
			'wpcf7recaptcha.' => '"Contact Form 7" plugin',
			'c = c.replace(/woocommerce-no-js/, \'woocommerce-js\');' => '"WooCommerce" plugin',
			'.woocommerce-product-gallery{ opacity: 1 !important; }'  => '"WooCommerce" plugin',
			'-ss-slider-3' => '"Smart Slider 3" plugin',
			'N2R(["nextend-frontend","smartslider-frontend","smartslider-simple-type-frontend"]' => '"Smart Slider 3" plugin',
			'function setREVStartSize' => '"Slider Revolution" plugin',
			'jQuery(\'.rev_slider_wrapper\')' => '"Slider Revolution" plugin',
			'jQuery(\'#wp-admin-bar-revslider-default' => '"Slider Revolution" plugin'
		);

		foreach ($belongList as $ifContains => $isFromSource) {
			if ( strpos( $htmlTag, $ifContains) !== false ) {
				return $isFromSource;
			}
		}

		return false;
	}
}
