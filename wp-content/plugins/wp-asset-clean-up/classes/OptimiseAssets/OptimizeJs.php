<?php
namespace WpAssetCleanUp\OptimiseAssets;

use WpAssetCleanUp\FileSystem;
use WpAssetCleanUp\CleanUp;
use WpAssetCleanUp\Main;
use WpAssetCleanUp\MetaBoxes;
use WpAssetCleanUp\Misc;
use WpAssetCleanUp\ObjectCache;
use WpAssetCleanUp\Plugin;
use WpAssetCleanUp\Preloads;

/**
 * Class OptimizeJs
 * @package WpAssetCleanUp
 */
class OptimizeJs
{
	/**
	 *
	 */
	public function init()
	{
		add_action( 'wp_print_footer_scripts', static function() {
			/* [wpacu_timing] */ Misc::scriptExecTimer( 'prepare_optimize_files_js' ); /* [/wpacu_timing] */
			self::prepareOptimizeList();
			/* [wpacu_timing] */ Misc::scriptExecTimer( 'prepare_optimize_files_js', 'end' ); /* [/wpacu_timing] */
		}, PHP_INT_MAX );
	}

	/**
	 *
	 */
	public static function prepareOptimizeList()
	{
		// Are both Minify and Cache Dynamic JS disabled? No point in continuing and using extra resources as there is nothing to change
		if ( ! self::isWorthCheckingForOptimization() || Plugin::preventAnyChanges() ) {
			return;
		}

		global $wp_scripts;

		$jsOptimizeList = array();

		$wpScriptsList = array_unique(array_merge($wp_scripts->done, $wp_scripts->queue));

		// Collect all enqueued clean (no query strings) HREFs to later compare them against any hardcoded JS
		$allEnqueuedCleanScriptSrcs = array();

		foreach ($wpScriptsList as $index => $scriptHandle) {
			if (isset(Main::instance()->wpAllScripts['registered'][$scriptHandle]->src) && ($src = Main::instance()->wpAllScripts['registered'][$scriptHandle]->src)) {
				$localAssetPath = OptimizeCommon::getLocalAssetPath($src, 'js');

				if (! $localAssetPath || ! is_file($localAssetPath)) {
					continue; // not a local file
				}

				ob_start();
				$wp_scripts->do_item($scriptHandle);
				$scriptSourceTag = trim(ob_get_clean());

				// Check if the JS has any 'data-wpacu-skip' attribute; if it does, do not alter it
				if (preg_match('#data-wpacu-skip([=>/ ])#i', $scriptSourceTag)) {
					unset($wpScriptsList[$index]);
					continue;
				}

				$cleanScriptSrcFromTagArray = OptimizeCommon::getLocalCleanSourceFromTag($scriptSourceTag, 'src');

				if (isset($cleanScriptSrcFromTagArray['source']) && $cleanScriptSrcFromTagArray['source']) {
					$allEnqueuedCleanScriptSrcs[] = $cleanScriptSrcFromTagArray['source'];
				}
			}
		}

		// [Start] Collect for caching
		foreach ($wpScriptsList as $handle) {
			if (! isset($wp_scripts->registered[$handle]->src)) {
				continue;
			}

			$value = $wp_scripts->registered[$handle];

			$localAssetPath = OptimizeCommon::getLocalAssetPath($value->src, 'js');
			if (! $localAssetPath || ! is_file($localAssetPath)) {
				continue; // not a local file
			}

			$optimizeValues = self::maybeOptimizeIt($value);
			ObjectCache::wpacu_cache_set('wpacu_maybe_optimize_it_js_'.$handle, $optimizeValues);

			if ( ! empty( $optimizeValues ) ) {
				$jsOptimizeList[] = $optimizeValues;
			}
		}

		ObjectCache::wpacu_cache_add('wpacu_js_enqueued_srcs', $allEnqueuedCleanScriptSrcs);
		ObjectCache::wpacu_cache_add('wpacu_js_optimize_list', $jsOptimizeList);
		// [End] Collect for caching
	}

	/**
	 * @param $value
	 *
	 * @return array
	 */
	public static function maybeOptimizeIt($value)
	{
		if ($optimizeValues = ObjectCache::wpacu_cache_get('wpacu_maybe_optimize_it_js_'.$value->handle)) {
			return $optimizeValues;
		}

		global $wp_version;

		$src = isset($value->src) ? $value->src : false;

		if (! $src) {
			return array();
		}

		$doFileMinify = true;

		if (! MinifyJs::isMinifyJsEnabled()) {
			$doFileMinify = false;
		} elseif (MinifyJs::skipMinify($src, $value->handle)) {
			$doFileMinify = false;
		}

		// Default (it will be later replaced with the last time the file was modified, which is more accurate)
		$dbVer = (isset($value->ver) && $value->ver) ? $value->ver : $wp_version;

		$isJsFile = false;

		$localAssetPath = OptimizeCommon::getLocalAssetPath($src, 'js');
		if ($localAssetPath && is_file($localAssetPath)) {
			if ($fileMTime = @filemtime($localAssetPath)) {
				$dbVer = $fileMTime;
			}
			$isJsFile = true;
		}

		$handleDbStr = md5($value->handle);

		$transientName = 'wpacu_js_optimize_'.$handleDbStr;

		$skipCache = false;

		if (isset($_GET['wpacu_no_cache'])) {
			$skipCache = true;
		}

		if (! $skipCache) {
			if (Main::instance()->settings['fetch_cached_files_details_from'] === 'db_disk') {
				if ( ! isset( $GLOBALS['wpacu_from_location_inc'] ) ) {
					$GLOBALS['wpacu_from_location_inc'] = 1;
				}
				$fromLocation = ( $GLOBALS['wpacu_from_location_inc'] % 2 ) ? 'db' : 'disk';
			} else {
				$fromLocation = Main::instance()->settings['fetch_cached_files_details_from'];
			}

			$savedValues = OptimizeCommon::getTransient($transientName, $fromLocation);

			if ( $savedValues ) {
				$savedValuesArray = json_decode($savedValues, ARRAY_A);

				if ( $savedValuesArray['ver'] !== $dbVer ) {
					// New File Version? Delete transient as it will be re-added to the database with the new version
					OptimizeCommon::deleteTransient($transientName);
				} else {
					$localPathToJsOptimized = str_replace( '//', '/', ABSPATH . $savedValuesArray['optimize_uri'] );

					// Do not load any minified JS file (from the database transient cache) if it doesn't exist
					// It will fallback to the original JS file
					if ( isset( $savedValuesArray['source_uri'] ) && is_file( $localPathToJsOptimized ) ) {
						if (Main::instance()->settings['fetch_cached_files_details_from'] === 'db_disk') {
							$GLOBALS['wpacu_from_location_inc']++;
						}

						return array(
							$savedValuesArray['source_uri'],
							$savedValuesArray['optimize_uri'],
							$value->src,
							$value->handle
						);
					}
				}
			}
		}

		// Check if it starts without "/" or a protocol; e.g. "wp-content/theme/script.js"
		if (strpos($src, '/') !== 0 &&
		    strpos($src, '//') !== 0 &&
		    stripos($src, 'http://') !== 0 &&
		    stripos($src, 'https://') !== 0
		) {
			$src = '/'.$src; // append the forward slash to be processed as relative later on
		}

		// Starts with '/', but not with '//'
		if (strpos($src, '/') === 0 && strpos($src, '//') !== 0) {
			$src = site_url() . $src;
		}

		/*
		 * [START] JS Content Optimization
		*/
		$jsContentBefore = false;

		if (Main::instance()->settings['cache_dynamic_loaded_js'] &&
			((strpos($src, '/?') !== false) || strpos($src, '.php?') !== false || Misc::endsWith($src, '.php')) &&
		    (strpos($src, site_url()) !== false)
		) {
			$pathToAssetDir = '';
			$sourceBeforeOptimization = $value->src;

			if (! ($jsContent = DynamicLoadedAssets::getAssetContentFrom('dynamic', $value))) {
				return array();
			}
		} else {
			if (! $isJsFile) {
				return array();
			}

			/*
			 * This is a local .JS file
			 */
			$pathToAssetDir = OptimizeCommon::getPathToAssetDir($value->src);
			$sourceBeforeOptimization = str_replace(ABSPATH, '/', $localAssetPath);

			$jsContent = $jsContentBefore = FileSystem::file_get_contents($localAssetPath);
		}

		$hadToBeMinified = false;

		$jsContent = trim($jsContent);

		// If it stays like this, it means there is content there, even if only comments
		$jsContentBecomesEmptyAfterMin = false;

		if ( $doFileMinify && $jsContent ) { // only bother to minify it if it has any content, save resources
			// Minify this file?
			$jsContentBeforeMin = $jsContent;
			$jsContentAfterMin  = MinifyJs::applyMinification($jsContentBeforeMin);

			$jsContent = $jsContentAfterMin;

			if ( $jsContentBeforeMin && $jsContentAfterMin === '' ) {
				// It had content, but became empty after minification, most likely it had only comments (e.g. a default child theme's style)
				$jsContentBecomesEmptyAfterMin = true;
			} else {
				$jsContentCompare     = md5(trim( $jsContentBeforeMin, '; ' ));
				$jsContentCompareWith = md5(trim( $jsContentAfterMin, '; ' ));

				if ( $jsContentCompare !== $jsContentCompareWith ) {
					$hadToBeMinified = true;
				}
			}
		}

		if ( $jsContentBecomesEmptyAfterMin || $jsContent === '' ) {
			$jsContent = '/**/';
		} else {
			$jsContentArray = self::maybeAlterContentForJsFile( $jsContent, false );
			$jsContent = $jsContentArray['content']; // resulting content after alteration
			$jsContentAfterAlterToCompare = $jsContentArray['content_after_alter_to_compare'];

			if ( $isJsFile && ( ! $hadToBeMinified ) ) {
				$jsContentCompare     = md5(trim( $jsContent, '; ' ));
				$jsContentCompareWith = md5(trim( $jsContentAfterAlterToCompare, '; ' ));

				if ( $jsContentCompare === $jsContentCompareWith ) {
					// 1: The file was not minified
					// 2: It doesn't need any alteration (e.g. no Google Fonts to strip from its content)
					// No need to copy it in to the cache (save disk space)
					return array();
				}
			}

			// Change the necessary relative paths before the file is copied to the caching directory (e.g. /wp-content/cache/asset-cleanup/)
			$jsContent = self::maybeDoJsFixes( $jsContent, $pathToAssetDir . '/' );
		}
		/*
		 * [END] JS Content Optimization
		*/

		// Relative path to the new file
		// Save it to /wp-content/cache/js/{OptimizeCommon::$optimizedSingleFilesDir}/
		$fileVer = sha1($jsContent);

		$newFilePathUri  = self::getRelPathJsCacheDir() . OptimizeCommon::$optimizedSingleFilesDir . '/' . sanitize_title($value->handle) . '-v' . $fileVer;
		$newFilePathUri .= '.js';

		if ($jsContent === '') {
			$jsContent = '/**/';
		}

		if ($jsContent === '/**/') {
			// Leave a signature that the file is empty, thus it would be faster to take further actions upon it later on, saving resources)
			$newFilePathUri = str_replace('.js', '-wpacu-empty-file.js', $newFilePathUri);
		}

		$newLocalPath    = WP_CONTENT_DIR . $newFilePathUri; // Ful Local path
		$newLocalPathUrl = WP_CONTENT_URL . $newFilePathUri; // Full URL path

		if ($jsContent && $jsContent !== '/**/') {
			$jsContent = '/*!' . $sourceBeforeOptimization . '*/' . "\n" . $jsContent;
		}

		$saveFile = FileSystem::file_put_contents($newLocalPath, $jsContent);

		if (! $saveFile || ! $jsContent) {
			// Fallback to the original JS if the optimized version can't be created or updated
			return array();
		}

		$saveValues = array(
			'source_uri'   => OptimizeCommon::getSourceRelPath($value->src),
			'optimize_uri' => OptimizeCommon::getSourceRelPath($newLocalPathUrl),
			'ver'          => $dbVer
		);

		// Add / Re-add (with new version) transient
		OptimizeCommon::setTransient($transientName, json_encode($saveValues));

		return array(
			OptimizeCommon::getSourceRelPath($value->src), // Original SRC (Relative path)
			OptimizeCommon::getSourceRelPath($newLocalPathUrl), // New SRC (Relative path)
			$value->src, // SRC (as it is)
			$value->handle
		);
	}

	/**
	 * This applies to both inline and static JS files contents
	 *
	 * @param $jsContent
	 * @param bool $doJsMinify (false by default as it could be already minified or non-minify type)
	 *
	 * @return array
	 */
	public static function maybeAlterContentForJsFile($jsContent, $doJsMinify = false)
	{
		if (! trim($jsContent)) { // No Content! Return it as it, no point in doing extra checks
			return array('content' => $jsContent);
		}

		/* [START] Change JS Content */
		if ($doJsMinify) {
			$jsContent = MinifyJs::applyMinification($jsContent);
		}

		if (Main::instance()->settings['google_fonts_remove']) {
			$jsContent = FontsGoogleRemove::stripReferencesFromJsCode($jsContent);
		} elseif (Main::instance()->settings['google_fonts_display']) {
			// Perhaps "display" parameter has to be applied to Google Font Links if they are active
			$jsContent = FontsGoogle::alterGoogleFontUrlFromJsContent($jsContent);
		}
		/* [END] Change JS Content */

		$jsContentAfterAlterToCompare = $jsContent; // new possible values

		// Does it have a source map? Strip it
		if (strpos($jsContent, 'sourceMappingURL') !== false) {
			$jsContent = OptimizeCommon::stripSourceMap($jsContent);
		}

		return array('content' => $jsContent , 'content_after_alter_to_compare' => $jsContentAfterAlterToCompare);
	}

	/**
	 * @param $jsContent
	 * @param $doJsMinify
	 *
	 * @return false|mixed|string|string[]|null
	 */
	public static function maybeAlterContentForInlineScriptTag($jsContent, $doJsMinify)
	{
		if (! trim($jsContent)) { // No Content! Return it as it, no point in doing extra checks
			return $jsContent;
		}

		if (mb_strlen($jsContent) > 500000) { // Bigger then ~500KB? Skip alteration for this inline SCRIPT
			return $jsContent;
		}

		$useCacheForInlineScript = true;

		if (mb_strlen($jsContent) < 40000) { // Smaller than ~40KB? Do not cache it
			$useCacheForInlineScript = false;
		}

		// For debugging purposes
		if (isset($_GET['wpacu_no_cache'])) { $useCacheForInlineScript = false; }

		if ($useCacheForInlineScript) {
			// Anything in the cache? Take it from there and don't spend resources with the minification
			// (which in some environments uses the CPU, depending on the complexity of the JavaScript code) and any other alteration
			$jsContentBeforeHash = sha1( $jsContent );

			$pathToInlineJsOptimizedItem = WP_CONTENT_DIR . self::getRelPathJsCacheDir() . '/item/inline/' . $jsContentBeforeHash . '.js';

			// Check if the file exists before moving forward
			if ( is_file( $pathToInlineJsOptimizedItem ) ) {
				$cachedJsFileExpiresIn = OptimizeCommon::$cachedAssetFileExpiresIn;

				if ( filemtime( $pathToInlineJsOptimizedItem ) < ( time() - 1 * $cachedJsFileExpiresIn ) ) {
					// Has the caching period expired? Remove the file as a new one has to be generated
					@unlink( $pathToInlineJsOptimizedItem );
				} else {
					// Not expired / Return its content from the cache in a faster way
					$inlineJsStorageItemJsonContent = FileSystem::file_get_contents( $pathToInlineJsOptimizedItem );

					if ( $inlineJsStorageItemJsonContent !== '' ) {
						return $inlineJsStorageItemJsonContent;
					}
				}
			}
		}

		/* [START] Change JS Content */
		if ($doJsMinify) {
			$jsContent = MinifyJs::applyMinification($jsContent);
		}

		if (Main::instance()->settings['google_fonts_remove']) {
			$jsContent = FontsGoogleRemove::stripReferencesFromJsCode($jsContent);
		} elseif (Main::instance()->settings['google_fonts_display']) {
			// Perhaps "display" parameter has to be applied to Google Font Links if they are active
			$jsContent = FontsGoogle::alterGoogleFontUrlFromJsContent($jsContent);
		}
		/* [END] Change JS Content */

		if ( $useCacheForInlineScript && isset($pathToInlineJsOptimizedItem) ) {
			// Store the optimized content to the cached JS file which would be read quicker
			FileSystem::file_put_contents( $pathToInlineJsOptimizedItem, $jsContent );
		}

		return $jsContent;
	}

	/**
	 * @param $htmlSource
	 *
	 * @return mixed
	 */
	public static function updateHtmlSourceOriginalToOptimizedJs($htmlSource)
	{
		$jsOptimizeList = ObjectCache::wpacu_cache_get('wpacu_js_optimize_list') ?: array();
		$allEnqueuedCleanScriptSrcs = ObjectCache::wpacu_cache_get('wpacu_js_enqueued_srcs') ?: array();

		$cdnUrls = OptimizeCommon::getAnyCdnUrls();
		$cdnUrlForJs = isset($cdnUrls['js']) ? $cdnUrls['js'] : false;

		preg_match_all('#(<script[^>]*src(|\s+)=(|\s+)[^>]*>)|(<link[^>]*(as(\s+|)=(\s+|)(|"|\')script(|"|\'))[^>]*>)#Umi', $htmlSource, $matchesSourcesFromTags, PREG_SET_ORDER);

		$scriptTagsToUpdate = array();

		foreach ($matchesSourcesFromTags as $matches) {
			$scriptSourceTag = $matches[0];

			if (strip_tags($scriptSourceTag) !== '') {
				// Hmm? Not a valid tag... Skip it...
				continue;
			}

			// Check if the CSS has any 'data-wpacu-skip' attribute; if it does, do not alter it
			if (preg_match('#data-wpacu-skip([=>/ ])#i', $scriptSourceTag)) {
				continue;
			}

			$forAttr = 'src';

			// Any preloads for the optimized script?
			// e.g. <link rel='preload' as='script' href='...' />
			if (strpos($scriptSourceTag, '<link') !== false) {
				$forAttr = 'href';
			}

			$cleanScriptSrcFromTagArray = OptimizeCommon::getLocalCleanSourceFromTag($scriptSourceTag, $forAttr);

			// Skip external links, no point in carrying on
			if (! $cleanScriptSrcFromTagArray || ! is_array($cleanScriptSrcFromTagArray)) {
				continue;
			}

			// Is it a local JS? Check if it's hardcoded (not enqueued the WordPress way)
			$cleanScriptSrcFromTag      = $cleanScriptSrcFromTagArray['source'];
			$afterQuestionMark          = $cleanScriptSrcFromTagArray['after_question_mark'];

			if (! in_array($cleanScriptSrcFromTag, $allEnqueuedCleanScriptSrcs)) {
				// Not in the final enqueued list? Most likely hardcoded (not added via wp_enqueue_scripts())
				// Emulate the object value (as the enqueued styles)
				$generatedHandle = md5($cleanScriptSrcFromTag);

				$value = (object)array(
					'handle' => $generatedHandle,
					'src'    => $cleanScriptSrcFromTag,
					'ver'    => md5($afterQuestionMark)
				);

				$optimizeValues = self::maybeOptimizeIt($value);
				ObjectCache::wpacu_cache_set('wpacu_maybe_optimize_it_js_'.$generatedHandle, $optimizeValues);

				if (! empty($optimizeValues)) {
					$jsOptimizeList[] = $optimizeValues;
				}
			}

			if (empty($jsOptimizeList)) {
				continue;
			}

			foreach ($jsOptimizeList as $jsItemIndex => $listValues) {
				// Index 0: Source URL (relative)
				// Index 1: New Optimized URL (relative)
				// Index 2: Source URL (as it is)

				// If the minified files are deleted (e.g. /wp-content/cache/ is cleared)
				// do not replace the JS file path to avoid breaking the website
				$localPathOptimizedFile = rtrim(ABSPATH, '/') . $listValues[1];

				if (! is_file($localPathOptimizedFile)) {
					continue;
				}

				// Make sure the source URL gets updated even if it starts with // (some plugins/theme strip the protocol when enqueuing JavaScript files)
				$siteUrlNoProtocol = str_replace(array('http://', 'https://'), '//', site_url());

				$sourceUrlList = array(
					site_url() . $listValues[0],
					$siteUrlNoProtocol . $listValues[0]
				); // array

				if ($cdnUrlForJs) {
					// Does it have a CDN?
					$sourceUrlList[] = OptimizeCommon::cdnToUrlFormat($cdnUrlForJs, 'rel') . $listValues[0];
				}

				// Any rel tag? You never know
				// e.g. <script src="/wp-content/themes/my-theme/script.js"></script>
				if ( (strpos($listValues[2], '/') === 0 && strpos($listValues[2], '//') !== 0)
				     || (strpos($listValues[2], '/') !== 0 &&
				         strpos($listValues[2], '//') !== 0 &&
				         stripos($listValues[2], 'http://') !== 0 &&
				         stripos($listValues[2], 'https://') !== 0) ) {
					$sourceUrlList[] = $listValues[2];
				}

				// If no CDN is set, it will return site_url() as a prefix
				$optimizeUrl = OptimizeCommon::cdnToUrlFormat($cdnUrlForJs, 'raw') . $listValues[1]; // string

				if ($scriptSourceTag !== str_ireplace($sourceUrlList, $optimizeUrl, $scriptSourceTag)) {
					// Extra measure: Check the file size which should be 4 bytes, but add some margin error in case some environments will report less
					$isEmptyOptimizedFile = (strpos($localPathOptimizedFile, '-wpacu-empty-file.js') !== false && filesize($localPathOptimizedFile) < 10);

					if ($isEmptyOptimizedFile) {
						// Strip it as its content (after optimization, for instance) is empty; no point in having extra HTTP requests
						$scriptTagsToUpdate[$scriptSourceTag.'</script>'] = '';

						// Note: As for September 3, 2020, the inline JS associated with the handle is no longer removed if the main JS file is empty
						// There could be cases when the main JS file is empty, but the inline JS tag associated with it has code that is needed

						} else {
						$newScriptSourceTag = self::updateOriginalToOptimizedTag( $scriptSourceTag, $sourceUrlList, $optimizeUrl );
						$scriptTagsToUpdate[$scriptSourceTag] = $newScriptSourceTag;
					}

					unset($jsOptimizeList[$jsItemIndex]); // item from the array is not needed anymore
					break;
				}
			}
		}

		$htmlSource = strtr($htmlSource, $scriptTagsToUpdate);

		return $htmlSource;
	}

	/**
	 * @param $scriptSourceTag string
	 * @param $sourceUrl array
	 * @param $optimizeUrl string
	 *
	 * @return mixed
	 */
	public static function updateOriginalToOptimizedTag($scriptSourceTag, $sourceUrl, $optimizeUrl)
	{
		$newScriptSourceTag = str_replace($sourceUrl, $optimizeUrl, $scriptSourceTag);
		$sourceUrlRel = is_array($sourceUrl) ? OptimizeCommon::getSourceRelPath($sourceUrl[0]) : OptimizeCommon::getSourceRelPath($sourceUrl);
		$newScriptSourceTag = str_ireplace('<script ', '<script data-wpacu-script-rel-src-before="'.$sourceUrlRel.'" ', $newScriptSourceTag);

		// Strip ?ver=
		$toStrip = Misc::extractBetween($newScriptSourceTag, '?ver', '>');

		if (in_array(substr($toStrip, -1), array('"', "'"))) {
			$toStrip = '?ver'. trim(trim($toStrip, '"'), "'");
			$newScriptSourceTag = str_replace($toStrip, '', $newScriptSourceTag);
		}

		global $wp_version;

		$newScriptSourceTag = str_replace('.js&#038;ver='.$wp_version, '.js', $newScriptSourceTag);
		$newScriptSourceTag = str_replace('.js&#038;ver=', '.js', $newScriptSourceTag);

		$newScriptSourceTag = preg_replace('!\s+!', ' ', $newScriptSourceTag); // replace multiple spaces with only one space

		return $newScriptSourceTag;
	}

	/**
	 * @param $htmlSource
	 *
	 * @return mixed|void
	 */
	public static function alterHtmlSource($htmlSource)
	{
		// There has to be at least one "<script", otherwise, it could be a feed request or something similar (not page, post, homepage etc.)
		if (stripos($htmlSource, '<script') === false) {
			return $htmlSource;
		}

		/* [wpacu_timing] */ Misc::scriptExecTimer( 'alter_html_source_for_optimize_js' ); /* [/wpacu_timing] */

		/* [wpacu_pro] */$htmlSource = apply_filters('wpacu_pro_maybe_move_jquery_after_body_tag', $htmlSource);/* [/wpacu_pro] */

		if (! Main::instance()->preventAssetsSettings()) {
			/* [wpacu_timing] */ $wpacuTimingName = 'alter_html_source_unload_ignore_deps_js'; Misc::scriptExecTimer($wpacuTimingName); /* [/wpacu_timing] */
			// Are there any assets unloaded where their "children" are ignored?
			// Since they weren't dequeued the WP way (to avoid unloading the "children"), they will be stripped here
			$htmlSource = self::ignoreDependencyRuleAndKeepChildrenLoaded($htmlSource);
			/* [wpacu_timing] */ Misc::scriptExecTimer($wpacuTimingName, 'end'); /* [/wpacu_timing] */

			// Move any jQuery inline SCRIPT that is triggered before jQuery library is called through "jquery-core" handle
			if (Main::instance()->settings['move_inline_jquery_after_src_tag']) {
				/* [wpacu_timing] */ $wpacuTimingName = 'alter_html_source_move_inline_jquery_after_src_tag'; Misc::scriptExecTimer($wpacuTimingName); /* [/wpacu_timing] */
				$htmlSource = self::moveInlinejQueryAfterjQuerySrc($htmlSource);
				/* [wpacu_timing] */ Misc::scriptExecTimer($wpacuTimingName, 'end'); /* [/wpacu_timing] */
			}
		}

		/*
		 * The JavaScript files only get cached if they are minified or are loaded like /?custom-js=version - /script.php?ver=1 etc.
		 * #optimizing
		 * STEP 2: Load optimize-able caching list and replace the original source URLs with the new cached ones
		 */

		// At least minify or cache dynamic loaded JS has to be enabled to proceed
		if (self::isWorthCheckingForOptimization()) {
			/* [wpacu_timing] */ $wpacuTimingName = 'alter_html_source_original_to_optimized_js'; Misc::scriptExecTimer($wpacuTimingName); /* [/wpacu_timing] */
			// 'wpacu_js_optimize_list' caching list is also checked; if it's empty, no optimization is made
			$htmlSource = self::updateHtmlSourceOriginalToOptimizedJs($htmlSource);
			/* [wpacu_timing] */ Misc::scriptExecTimer($wpacuTimingName, 'end'); /* [/wpacu_timing] */
		}

		if (! Main::instance()->preventAssetsSettings()) {
			/* [wpacu_timing] */ $wpacuTimingName = 'alter_html_source_for_preload_js'; Misc::scriptExecTimer($wpacuTimingName); /* [/wpacu_timing] */
			$preloads = Preloads::instance()->getPreloads();

			if (isset($preloads['scripts']) && ! empty($preloads['scripts'])) {
				$htmlSource = Preloads::appendPreloadsForScriptsToHead($htmlSource);
			}

			$htmlSource = str_replace(Preloads::DEL_SCRIPTS_PRELOADS, '', $htmlSource);
			/* [wpacu_timing] */ Misc::scriptExecTimer($wpacuTimingName, 'end'); /* [/wpacu_timing] */
		}

		/* [wpacu_timing] */ $wpacuTimingName = 'alter_html_source_for_combine_js';

		Misc::scriptExecTimer($wpacuTimingName); /* [/wpacu_timing] */
		$proceedWithCombineOnThisPage = true;

		// If "Do not combine JS on this page" is checked in "Asset CleanUp Options" side meta box
		// Works for posts, pages and custom post types
		if (defined('WPACU_CURRENT_PAGE_ID') && WPACU_CURRENT_PAGE_ID > 0) {
			$pageOptions = MetaBoxes::getPageOptions( WPACU_CURRENT_PAGE_ID );

			// 'no_js_optimize' refers to avoid the combination of JS files
			if ( isset( $pageOptions['no_js_optimize'] ) && $pageOptions['no_js_optimize'] ) {
				$proceedWithCombineOnThisPage = false;
			}
		}

		if ($proceedWithCombineOnThisPage) {
			/* [wpacu_timing] */ // Note: Load timing is checked within the method /* [/wpacu_timing] */
			$htmlSource = CombineJs::doCombine($htmlSource);
		}
		/* [wpacu_timing] */ Misc::scriptExecTimer($wpacuTimingName, 'end'); /* [/wpacu_timing] */

		if (self::isWorthCheckingForOptimization() && ! Main::instance()->preventAssetsSettings() && (MinifyJs::isMinifyJsEnabled() && Main::instance()->settings['minify_loaded_js_inline'])) {
			/* [wpacu_timing] */ $wpacuTimingName = 'alter_html_source_for_minify_inline_script_tags'; Misc::scriptExecTimer($wpacuTimingName); /* [/wpacu_timing] */
			$htmlSource = MinifyJs::minifyInlineScriptTags($htmlSource);
			/* [wpacu_timing] */ Misc::scriptExecTimer($wpacuTimingName, 'end'); /* [/wpacu_timing] */
		}

		// Final cleanups
		$htmlSource = preg_replace('#(\s+|)(data-wpacu-jquery-core-handle=1|data-wpacu-jquery-migrate-handle=1)(\s+|)#Umi', ' ', $htmlSource);

		$htmlSource = preg_replace('#(\s+|)data-wpacu-script-rel-src-before=(["\'])' . '(.*)' . '(\1)(\s+|)#Usmi', ' ', $htmlSource);
		$htmlSource = preg_replace('#<script(.*)data-wpacu-script-handle=\'(.*)\'#Umi', '<script \\1', $htmlSource);
		$htmlSource = preg_replace('#<script(\s+)src=\'#Umi', '<script src=\'', $htmlSource);

		// Clear possible empty SCRIPT tags (e.g. left from associated 'before' and 'after' tags after their content was stripped)
		$htmlSource = preg_replace('#<script(\s+| )(type=\'text/javascript\'|)(\s+|)></script>#Umi', '', $htmlSource);

		/* [wpacu_timing] */ Misc::scriptExecTimer('alter_html_source_for_optimize_js', 'end'); /* [/wpacu_timing] */
		return $htmlSource;
	}

	/**
	 * @return string
	 */
	public static function getRelPathJsCacheDir()
	{
		return OptimizeCommon::getRelPathPluginCacheDir().'js/'; // keep trailing slash at the end
	}

	/**
	 * @param $scriptSrcs
	 * @param $htmlSource
	 *
	 * @return array
	 */
	public static function getScriptTagsFromSrcs($scriptSrcs, $htmlSource)
	{
		$scriptTags = array();

		foreach ($scriptSrcs as $scriptSrc) {
			$scriptSrc = str_replace('{site_url}', '', $scriptSrc);

			// Clean it up for the preg_quote() call
			if (strpos($scriptSrc, '.js?') !== false) {
				list($scriptSrc,) = explode('.js?', $scriptSrc);
				$scriptSrc .= '.js';
			}

			preg_match_all('#<script[^>]*src(|\s+)=(|\s+)[^>]*'. preg_quote($scriptSrc, '/'). '.*(>)(.*|)</script>#Usmi', $htmlSource, $matchesFromSrc, PREG_SET_ORDER);

			if (isset($matchesFromSrc[0][0]) && strip_tags($matchesFromSrc[0][0]) === '') {
				$scriptTags[] = trim($matchesFromSrc[0][0]);
			}
		}

		return $scriptTags;
	}

	/**
	 * @param $strFind
	 * @param $strReplaceWith
	 * @param $string
	 *
	 * @return mixed
	 */
	public static function strReplaceOnce($strFind, $strReplaceWith, $string)
	{
		if ( strpos($string, $strFind) === false ) {
			return $string;
		}

		$occurrence = strpos($string, $strFind);
		return substr_replace($string, $strReplaceWith, $occurrence, strlen($strFind));
	}

	/**
	 * @param $jsContent
	 * @param $appendBefore
	 *
	 * @return mixed
	 */
	public static function maybeDoJsFixes($jsContent, $appendBefore)
	{
		// Relative URIs for CSS Paths
		// For code such as:
		// $(this).css("background", "url('../images/image-1.jpg')");

		$jsContentPathReps = array(
			'url("../' => 'url("'.$appendBefore.'../',
			"url('../" => "url('".$appendBefore.'../',
			'url(../'  => 'url('.$appendBefore.'../',

			'url("./'  => 'url("'.$appendBefore.'./',
			"url('./"  => "url('".$appendBefore.'./',
			'url(./'   => 'url('.$appendBefore.'./'
		);

		$jsContent = str_replace(array_keys($jsContentPathReps), array_values($jsContentPathReps), $jsContent);

		$jsContent = trim($jsContent);

		if (substr($jsContent, -1) !== ';') {
			$jsContent .= "\n" . ';'; // add semicolon as the last character
		}

		return $jsContent;
	}

	/**
	 * @param $htmlSource
	 *
	 * @return false|mixed|string|void
	 */
	public static function moveInlinejQueryAfterjQuerySrc($htmlSource)
	{
		if (stripos($htmlSource, '<script') === false) {
			return $htmlSource; // no SCRIPT tags, hmm
		}

		if (! (function_exists('libxml_use_internal_errors') && function_exists('libxml_clear_errors') && class_exists('\DOMDocument')) && class_exists('\DOMXpath')) {
			return $htmlSource; // DOMDocument has to be enabled
		}

		$domTag = OptimizeCommon::getDomLoadedTag($htmlSource, 'moveInlinejQueryAfterjQuerySrc');

		$scriptTagsObj = $domTag->getElementsByTagName( 'script' );

		if ($scriptTagsObj === null) {
			return $htmlSource;
		}

		// Does it have the "src" attribute? Skip it as it's not an inline SCRIPT tag
		$jQueryPatternsToMatch = array(
			'jQuery',
			'\$(\s+|)\((\s+|)document(\s+|)\)(\s+|).(\s+|)ready(\s+|)\('
		);

		$jQueryRegExp = '#' . implode('|', $jQueryPatternsToMatch) . '#si';

		$jQueryCoreDel    = 'data-wpacu-jquery-core-handle=';
		$jQueryMigrateDel = 'data-wpacu-jquery-migrate-handle=';

		if (strpos($htmlSource, $jQueryMigrateDel) !== false) {
			$collectUntil = $jQueryMigrateDel;
		} elseif (strpos($htmlSource, $jQueryCoreDel) !== false) {
			$collectUntil = $jQueryCoreDel;
		} else {
			return $htmlSource; // No jQuery or jQuery Migrate? Just return the HTML source
		}

		$inlineBeforejQuerySrc = array();

		foreach ($scriptTagsObj as $scriptTagObj) {
			$tagContents = $scriptTagObj->nodeValue;

			if (strpos(CleanUp::getOuterHTML($scriptTagObj), $collectUntil) !== false) {
				break;
			}

			if ($tagContents !== '' && preg_match($jQueryRegExp, $tagContents)) {
				preg_match('#<script[^>]*>'.preg_quote($tagContents, '/').'</script>#si', $htmlSource, $matchesExact);
				$exactMatchTag = isset($matchesExact[0]) ? $matchesExact[0] : '';

				// Replace the first match only in rare cases there are multiple SCRIPT tags with the same code
				if ($exactMatchTag && ($pos = strpos($htmlSource, $exactMatchTag)) !== false) {
					$inlineBeforejQuerySrc[] = $exactMatchTag;
					$htmlSource = substr_replace($htmlSource, '', $pos, strlen($exactMatchTag));
				}
			}
		}

		preg_match('#<script* '.$collectUntil.'*[^>]*>(.*?)</script>#si', $htmlSource, $matches);

		if (! empty($inlineBeforejQuerySrc) && $collectUntil && isset($matches[0])) {
			$htmlSource = preg_replace('#<script* '.$collectUntil.'*[^>]*>(.*?)</script>#si', $matches[0]."\n".implode("\n", $inlineBeforejQuerySrc), $htmlSource);
		}

		return $htmlSource;
	}

	/**
	 * @param string $returnType
	 * 'list' - will return the list of plugins that have JS optimization enabled
	 * 'if_enabled' - will stop when it finds the first one (any order) and return true
	 * @return array|bool
	 */
	public static function isOptimizeJsEnabledByOtherParty($returnType = 'list')
	{
		$pluginsToCheck = array(
			'autoptimize/autoptimize.php'            => 'Autoptimize',
			'wp-rocket/wp-rocket.php'                => 'WP Rocket',
			'wp-fastest-cache/wpFastestCache.php'    => 'WP Fastest Cache',
			'w3-total-cache/w3-total-cache.php'      => 'W3 Total Cache',
			'sg-cachepress/sg-cachepress.php'        => 'SG Optimizer',
			'fast-velocity-minify/fvm.php'           => 'Fast Velocity Minify',
			'litespeed-cache/litespeed-cache.php'    => 'LiteSpeed Cache',
			'swift-performance-lite/performance.php' => 'Swift Performance Lite',
			'breeze/breeze.php'                      => 'Breeze – WordPress Cache Plugin'
		);

		$jsOptimizeEnabledIn = array();

		foreach ($pluginsToCheck as $plugin => $pluginTitle) {
			// "Autoptimize" check
			if ($plugin === 'autoptimize/autoptimize.php' && Misc::isPluginActive($plugin) && get_option('autoptimize_js')) {
				$jsOptimizeEnabledIn[] = $pluginTitle;

				if ($returnType === 'if_enabled') { return true; }
			}

			// "WP Rocket" check
			if ($plugin === 'wp-rocket/wp-rocket.php' && Misc::isPluginActive($plugin)) {
				if (function_exists('get_rocket_option')) {
					$wpRocketMinifyJs = get_rocket_option('minify_js');
					$wpRocketMinifyConcatenateJs = get_rocket_option('minify_concatenate_js');
				} else {
					$wpRocketSettings  = get_option('wp_rocket_settings');
					$wpRocketMinifyJs = isset($wpRocketSettings['minify_js']) ? $wpRocketSettings['minify_js'] : false;
					$wpRocketMinifyConcatenateJs = isset($wpRocketSettings['minify_concatenate_js']) ? $wpRocketSettings['minify_concatenate_js'] : false;
				}

				if ($wpRocketMinifyJs || $wpRocketMinifyConcatenateJs) {
					$jsOptimizeEnabledIn[] = $pluginTitle;

					if ($returnType === 'if_enabled') { return true; }
				}
			}

			// "WP Fastest Cache" check
			if ($plugin === 'wp-fastest-cache/wpFastestCache.php' && Misc::isPluginActive($plugin)) {
				$wpfcOptionsJson = get_option('WpFastestCache');
				$wpfcOptions = @json_decode($wpfcOptionsJson, ARRAY_A);

				if (isset($wpfcOptions['wpFastestCacheMinifyJs']) || isset($wpfcOptions['wpFastestCacheCombineJs'])) {
					$jsOptimizeEnabledIn[] = $pluginTitle;

					if ($returnType === 'if_enabled') { return true; }
				}
			}

			// "W3 Total Cache" check
			if ($plugin === 'w3-total-cache/w3-total-cache.php' && Misc::isPluginActive($plugin)) {
				$w3tcConfigMaster = Misc::getW3tcMasterConfig();
				$w3tcEnableJs = (int)trim(Misc::extractBetween($w3tcConfigMaster, '"minify.js.enable":', ','), '" ');

				if ($w3tcEnableJs === 1) {
					$jsOptimizeEnabledIn[] = $pluginTitle;

					if ($returnType === 'if_enabled') { return true; }
				}
			}

			// "SG Optimizer" check
			if ($plugin === 'sg-cachepress/sg-cachepress.php' && Misc::isPluginActive($plugin)) {
				if (class_exists('\SiteGround_Optimizer\Options\Options') && method_exists('\SiteGround_Optimizer\Options\Options', 'is_enabled')) {
					if (@\SiteGround_Optimizer\Options\Options::is_enabled( 'siteground_optimizer_optimize_javascript')) {
						$jsOptimizeEnabledIn[] = $pluginTitle;

						if ($returnType === 'if_enabled') { return true; }
					}
				}
			}

			// "Fast Velocity Minify" check
			if ($plugin === 'fast-velocity-minify/fvm.php' && Misc::isPluginActive($plugin)) {
				// It's enough if it's active due to its configuration
				$jsOptimizeEnabledIn[] = $pluginTitle;

				if ($returnType === 'if_enabled') { return true; }
			}

			// "LiteSpeed Cache" check
			if ($plugin === 'litespeed-cache/litespeed-cache.php' && Misc::isPluginActive($plugin) && ($liteSpeedCacheConf = apply_filters('litespeed_cache_get_options', get_option('litespeed-cache-conf')))) {
				if ( (isset($liteSpeedCacheConf['js_minify']) && $liteSpeedCacheConf['js_minify'])
				     || (isset($liteSpeedCacheConf['js_combine']) && $liteSpeedCacheConf['js_combine']) ) {
					$jsOptimizeEnabledIn[] = $pluginTitle;

					if ($returnType === 'if_enabled') { return true; }
				}
			}

			// "Swift Performance Lite" check
			if ($plugin === 'swift-performance-lite/performance.php' && Misc::isPluginActive($plugin)
			    && class_exists('Swift_Performance_Lite') && method_exists('Swift_Performance_Lite', 'check_option')) {
				if ( @\Swift_Performance_Lite::check_option('merge-scripts', 1) ) {
					$jsOptimizeEnabledIn[] = $pluginTitle;
				}

				if ($returnType === 'if_enabled') { return true; }
			}

			// "Breeze – WordPress Cache Plugin"
			if ($plugin === 'breeze/breeze.php' && Misc::isPluginActive($plugin)) {
				$breezeBasicSettings    = get_option('breeze_basic_settings');
				$breezeAdvancedSettings = get_option('breeze_advanced_settings');

				if (isset($breezeBasicSettings['breeze-minify-js'], $breezeAdvancedSettings['breeze-group-js'])
				    && $breezeBasicSettings['breeze-minify-js'] && $breezeAdvancedSettings['breeze-group-js']) {
					$jsOptimizeEnabledIn[] = $pluginTitle;

					if ($returnType === 'if_enabled') { return true; }
				}
			}
		}

		if ($returnType === 'if_enabled') { return false; }

		return $jsOptimizeEnabledIn;
	}

	/**
	 * @return bool
	 */
	public static function isWorthCheckingForOptimization()
	{
		// At least one of these options have to be enabled
		// Otherwise, we will not perform specific useless actions and save resources
		return MinifyJs::isMinifyJsEnabled() ||
		       Main::instance()->settings['cache_dynamic_loaded_js'] ||
		       Main::instance()->settings['google_fonts_display'] ||
		       Main::instance()->settings['google_fonts_remove'];
	}

	/**
	 * @param $htmlSource
	 *
	 * @return mixed
	 */
	public static function ignoreDependencyRuleAndKeepChildrenLoaded($htmlSource)
	{
		$ignoreChild = Main::instance()->getIgnoreChildren();

		if (isset($ignoreChild['scripts']) && ! empty($ignoreChild['scripts'])) {
			foreach (array_keys($ignoreChild['scripts']) as $scriptHandle) {
				if (isset(Main::instance()->wpAllScripts['registered'][$scriptHandle]->src, Main::instance()->ignoreChildren['scripts'][$scriptHandle.'_has_unload_rule']) && ($scriptSrc = Main::instance()->wpAllScripts['registered'][$scriptHandle]->src) && Main::instance()->ignoreChildren['scripts'][$scriptHandle.'_has_unload_rule']) {
					$toReplaceTagList = array();

					// If the handle has any inline JavaScript associated with it (before or after the tag), make sure it's stripped as well
					if ($cDataContent = self::generateInlineAssocHtmlForHandle($scriptHandle, 'data')) {
						$toReplaceTagList[] = $cDataContent;
					}

					if ($beforeContent = self::generateInlineAssocHtmlForHandle($scriptHandle, 'before')) {
						$toReplaceTagList[] = $beforeContent;
					}

					$toReplaceTagList[] = self::getScriptTagFromHandle(array('data-wpacu-script-handle=[\'"]' . $scriptHandle . '[\'"]'), $htmlSource);

					if ($afterContent = self::generateInlineAssocHtmlForHandle($scriptHandle, 'after')) {
						$toReplaceTagList[] = $afterContent;
					}

					$htmlSource = str_replace($toReplaceTagList, '', $htmlSource);

					// Extra, in case the previous replace didn't go through
					$listWithMatches   = array();
					$listWithMatches[] = 'data-wpacu-script-handle=[\'"]'.$scriptHandle.'[\'"]';
					$listWithMatches[] = preg_quote(OptimizeCommon::getSourceRelPath($scriptSrc), '/');

					$htmlSource = CleanUp::cleanScriptTagFromHtmlSource($listWithMatches, $htmlSource);
				}
			}
		}

		return $htmlSource;
	}


	/**
	 * This would fetch the content of the SCRIPT tag (data, before & after)
	 *
	 * @param $scriptTagOrHandle
	 * @param $wpacuRegisteredScripts
	 * @param string $from
	 * @param string $return ("value": JS Inline Content / "html": JS Inline Content surrounded by tags)
	 *
	 * @return array
	 */
	public static function getInlineAssociatedWithScriptHandle($scriptTagOrHandle, $wpacuRegisteredScripts, $from = 'tag', $return = 'value')
	{
		if ($from === 'tag') {
			preg_match_all('#data-wpacu-script-handle=([\'])' . '(.*)' . '(\1)#Usmi', $scriptTagOrHandle, $outputMatches);
			$scriptHandle = (isset($outputMatches[2][0]) && $outputMatches[2][0]) ? trim($outputMatches[2][0], '"\'') : '';
		} else { // 'handle'
			$scriptHandle = $scriptTagOrHandle;
		}

		if ( $return === 'value' && $scriptHandle && isset($wpacuRegisteredScripts[$scriptHandle]->extra) ) {
			$scriptExtraCdata = $scriptExtraBefore = $scriptExtraAfter = '';

			$scriptExtraArray = $wpacuRegisteredScripts[$scriptHandle]->extra;

			if (isset($scriptExtraArray['data']) && $scriptExtraArray['data']) {
				$scriptExtraCdata .= $scriptExtraArray['data']."\n";
			}

			if (isset($scriptExtraArray['before']) && ! empty($scriptExtraArray['before'])) {
				foreach ($scriptExtraArray['before'] as $beforeData) {
					if (! is_bool($beforeData)) {
						$scriptExtraBefore .= $beforeData."\n";
					}
				}
			}

			if (isset($scriptExtraArray['after']) && ! empty($scriptExtraArray['after'])) {
				foreach ($scriptExtraArray['after'] as $afterData) {
					if (! is_bool($afterData)) {
						$scriptExtraAfter .= $afterData."\n";
					}
				}
			}

			return array(
				'data'   => trim($scriptExtraCdata),
				'before' => trim($scriptExtraBefore),
				'after'  => trim($scriptExtraAfter)
			);
		}

		if ( $return === 'html' && $scriptHandle ) {
			return array(
				'data'   => self::generateInlineAssocHtmlForHandle($scriptHandle, 'data'),
				'before' => self::generateInlineAssocHtmlForHandle($scriptHandle, 'before'),
				'after'  => self::generateInlineAssocHtmlForHandle($scriptHandle, 'after')
			);
		}

		return array('data' => '', 'before' => '', 'after' => '');
	}

	/**
	 * @param $handle
	 * @param $position
	 * @param $inlineScriptContent
	 *
	 * @return string
	 */
	public static function generateInlineAssocHtmlForHandle($handle, $position, $inlineScriptContent = '')
	{
		global $wp_scripts;

		$typeAttr = '';

		if ( function_exists( 'is_admin' ) && ! is_admin() &&
		     function_exists( 'current_theme_supports' ) && ! current_theme_supports( 'html5', 'script' )
		) {
			$typeAttr = " type='text/javascript'";
		}

		$output = '';

		if ( $position === 'data' ) {
			if ( $inlineScriptContent === '' ) {
				$inlineScriptContent = $wp_scripts->get_data( $handle, 'data' );

				if (! $inlineScriptContent) {
					return '';
				}
			}

			$output .= sprintf("<script%s id='%s-js-extra'>\n", $typeAttr, esc_attr($handle));

			// CDATA is not needed for HTML 5.
			if ( $typeAttr ) {
				$output .= "/* <![CDATA[ */\n";
			}

			$output .= $inlineScriptContent."\n";

			if ( $typeAttr ) {
				$output .= "/* ]]> */\n";
			}

			$output .= '</script>';
		}

		if ( $position === 'before' || $position === 'after' ) {
			if ( $inlineScriptContent === '' ) {
				$inlineScriptContent = $wp_scripts->print_inline_script( $handle, $position, false );

				if (! $inlineScriptContent) {
					return '';
				}
			}

			if ( $inlineScriptContent ) {
				$output = sprintf( "<script%s id='%s-js-%s'>\n%s\n</script>\n", $typeAttr, $handle, $position, $inlineScriptContent );
			}
		}

		return $output;
	}

	/**
	 * @param $listWithPatterns
	 * @param $htmlSource
	 *
	 * @return string
	 */
	public static function getScriptTagFromHandle($listWithPatterns, $htmlSource)
	{
		if (empty($listWithPatterns)) {
			return '';
		}

		if (! is_array($listWithPatterns)) {
			$listWithPatterns = array($listWithPatterns);
		}

		preg_match_all(
			'#<script[^>]*('.implode('|', $listWithPatterns).')[^>].*(>)#Usmi',
			$htmlSource,
			$matchesSourcesFromTags
		);

		if (empty($matchesSourcesFromTags)) {
			return '';
		}

		if (isset($matchesSourcesFromTags[0]) && ! empty($matchesSourcesFromTags[0])) {
			foreach ($matchesSourcesFromTags[0] as $matchesFromTag) {
				if (stripos($matchesFromTag, ' src=') !== false && strip_tags($matchesFromTag) === '') {
					return $matchesFromTag.'</script>';
				}
			}
		}

		return '';
	}

	}
