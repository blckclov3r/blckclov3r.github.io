<?php
namespace WpAssetCleanUp;

use WpAssetCleanUp\OptimiseAssets\OptimizeCss;

/**
 * Class Preloads
 * @package WpAssetCleanUp
 */
class Preloads
{
	/**
	 * Printed in HEAD
	 */
	const DEL_STYLES_PRELOADS = '<meta name="wpacu-generator" content="ASSET CLEANUP STYLES PRELOADS">';

	/**
	 * Printed in HEAD
	 */
	const DEL_SCRIPTS_PRELOADS = '<meta name="wpacu-generator" content="ASSET CLEANUP SCRIPTS PRELOADS">';

	/**
	 * @var array
	 */
	public $preloads = array();

	/**
	 * @var Preloads|null
	 */
	private static $singleton;

	/**
	 * @return null|Preloads
	 */
	public static function instance()
	{
		if (self::$singleton === null) {
			self::$singleton = new self();
		}

		return self::$singleton;
	}

	/**
	 * Preloads constructor.
	 */
	public function __construct()
	{
	    if (is_admin()) {
	        return;
        }

		$this->preloads = $this->getPreloads();

		add_filter('wpfc_buffer_callback_filter', static function ($buffer) {
			$buffer = str_replace('rel=\'preload\' data-from-rel=\'stylesheet\'', 'rel=\'preload\'', $buffer);
			return $buffer;
		});
	}

	/**
	 *
	 */
	public function init()
	{
		if (! is_admin()) { // Trigger only in the front-end
		    add_filter('style_loader_tag', array($this, 'preloadCss'), 10, 2);
		    add_filter('script_loader_tag', array($this, 'preloadJs'), 10, 2);
		} else { // Trigger only within the Dashboard
			if (Misc::getVar('post', 'wpacu_remove_preloaded_assets_nonce')) {
				add_action('admin_init', static function() {
					Preloads::removePreloadFromChosenAssets();
				});
			}

			// Trigger only in "Bulk Changes" -> "Preloaded CSS/JS"
			if (isset($_GET['page']) && $_GET['page'] === WPACU_PLUGIN_ID.'_bulk_unloads'
                && get_transient('wpacu_preloads_just_removed')) {
				add_action('wpacu_admin_notices', array($this, 'noticePreloadsRemoved'));
				delete_transient('wpacu_preloads_just_removed');
			}
		}
	}

	/**
	 * @param $htmlSource
	 *
	 * @return mixed
	 */
	public function doChanges($htmlSource)
    {
	    $preloads = $this->getPreloads();

	    if (isset($preloads['styles']) && ! empty($preloads['styles'])) {
		    $htmlSource = self::appendPreloadsForStylesToHead($htmlSource, array_keys($preloads['styles']));
	    }

	    $htmlSource = str_replace(self::DEL_STYLES_PRELOADS, '', $htmlSource);

	    return $htmlSource;
    }

	/**
	 * @param string $for
	 * @return bool
	 */
	public function enablePreloads($for)
	{
	    if ($for === 'css' && ! (isset($this->preloads['styles']) && ! empty($this->preloads['styles']))) {
			return false;
		}

		if ($for === 'js' && ! (isset($this->preloads['scripts']) && ! empty($this->preloads['scripts']))) {
			return false;
		}

		// Do not use the preloads if "Optimize CSS Delivery" is enabled in WP Rocket
		if ($for === 'css' && Misc::isPluginActive('wp-rocket/wp-rocket.php') && function_exists('get_rocket_option') && get_rocket_option('async_css')) {
			return false;
		}

		// WP Fastest Cache: Combine CSS/JS is enabled
		if (! Menu::userCanManageAssets() && Misc::isPluginActive('wp-fastest-cache/wpFastestCache.php')) {
			$wpfcOptionsJson = get_option('WpFastestCache');
			$wpfcOptions     = @json_decode($wpfcOptionsJson, ARRAY_A);

			if ($for === 'css' && isset($wpfcOptions['wpFastestCacheCombineCss'])) {
				return false;
			}

			if ($for === 'js' && isset($wpfcOptions['wpFastestCacheCombineJs'])) {
				return false;
			}
		}

		// W3 Total Cache
		if (Misc::isPluginActive('w3-total-cache/w3-total-cache.php')) {
			$w3tcConfigMaster = Misc::getW3tcMasterConfig();

			if ($for === 'css') {
				$w3tcEnableCss = (int)trim(Misc::extractBetween($w3tcConfigMaster, '"minify.css.enable":', ','), '" ');

				if ($w3tcEnableCss === 1) {
					return false;
				}
			}

			if ($for === 'js') {
				$w3tcEnableJs = (int)trim(Misc::extractBetween($w3tcConfigMaster, '"minify.js.enable":', ','), '" ');

				if ($w3tcEnableJs === 1) {
					return false;
				}
			}
		}

		// LiteSpeed Cache
		if (Misc::isPluginActive('litespeed-cache/litespeed-cache.php') && ($liteSpeedCacheConf = apply_filters('litespeed_cache_get_options', get_option('litespeed-cache-conf')))) {
			if ($for === 'css' && isset($liteSpeedCacheConf['css_minify']) && $liteSpeedCacheConf['css_minify']) {
				return false;
			}

			if ($for === 'js' && isset($liteSpeedCacheConf['js_minify']) && $liteSpeedCacheConf['js_minify']) {
				return false;
			}
		}

		return true;
	}

	/**
	 * @return array
	 */
	public function getPreloads()
	{
		if (array_key_exists('styles', $this->preloads) && array_key_exists('scripts', $this->preloads)) {
			return $this->preloads;
		}

		$preloads = array('styles' => array(), 'scripts' => array());

		$preloadsListJson = get_option(WPACU_PLUGIN_ID . '_global_data');

		if ($preloadsListJson) {
			$preloadsList = @json_decode($preloadsListJson, true);

			// Issues with decoding the JSON file? Return an empty list
			if (Misc::jsonLastError() !== JSON_ERROR_NONE) {
				return $preloads;
			}

			// Are new positions set for styles and scripts?
			foreach (array('styles', 'scripts') as $assetKey) {
				if ( isset( $preloadsList[$assetKey]['preloads'] ) && ! empty( $preloadsList[$assetKey]['preloads'] ) ) {
					$preloads[$assetKey] = $preloadsList[$assetKey]['preloads'];
				}
			}
		}

		$this->preloads = $preloads;

		return $this->preloads;
	}

	/**
	 * @param $htmlTag
	 * @param $handle
	 *
	 * @return string
	 */
	public function preloadCss($htmlTag, $handle)
	{
	    if (Plugin::preventAnyChanges()) {
	        return $htmlTag;
        }

		if ($wpacuAsyncPreloadHandle = Misc::getVar('get', 'wpacu_preload_css')) {
			// For testing purposes: Check how the page loads with the requested CSS preloaded
			$this->preloads['styles'][$wpacuAsyncPreloadHandle] = 'basic';
		}

		// Only valid for front-end pages with LINKs
		if (is_admin() || (! $this->enablePreloads('css')) || strpos($htmlTag,'<link ') === false || Main::instance()->preventAssetsSettings()) {
			return $htmlTag;
		}

		if (! isset($this->preloads['styles'])) {
			return $htmlTag;
		}

		if (array_key_exists($handle, $this->preloads['styles']) && $this->preloads['styles'][$handle]) {
			ObjectCache::wpacu_cache_set($handle, 1, 'wpacu_basic_preload_handles');
            return str_replace('<link ', '<link data-wpacu-to-be-preloaded-basic=\'1\' ', $htmlTag);
		}

		return $htmlTag;
	}

	/**
	 * @param $htmlTag
	 * @param $handle
	 * @return string
	 */
	public function preloadJs($htmlTag, $handle)
	{
		if (Plugin::preventAnyChanges()) {
			return $htmlTag;
		}

		// For testing purposes: Check how the page loads with the requested JS preloaded
		if ($wpacuJsPreloadHandle = Misc::getVar('get', 'wpacu_preload_js')) {
			$this->preloads['scripts'][$wpacuJsPreloadHandle] = 1;
		}

		// Only valid for front-end pages with SCRIPTs
		if (is_admin() || (! $this->enablePreloads('js')) || strpos($htmlTag,'<script ') === false || Main::instance()->preventAssetsSettings()) {
			return $htmlTag;
		}

		if (! isset($this->preloads['scripts'])) {
			return $htmlTag;
		}

		if (array_key_exists($handle, $this->preloads['scripts']) && $this->preloads['scripts'][$handle]) {
			return str_replace('<script ', '<script data-wpacu-to-be-preloaded-basic=\'1\' ', $htmlTag);
		}

		return $htmlTag;
	}

	/**
	 * @param $htmlSource
	 * @param $preloadedHandles
	 *
	 * @return mixed
	 */
	public static function appendPreloadsForStylesToHead($htmlSource, $preloadedHandles)
	{
		// Perhaps it's not applicable in the current page (no LINK tags are loaded that should be preloaded)
		if (strpos($htmlSource, 'data-wpacu-to-be-preloaded-basic') === false) {
			return $htmlSource;
		}

		// Use the RegEx as it's much faster and very accurate in this situation
		// If there are issues, fallback to DOMDocument
		$strContainsFormat = preg_quote('data-wpacu-to-be-preloaded-basic', '/');
		preg_match_all('#<link[^>]'.$strContainsFormat.'[^>]*' . 'href=(\'|"|)(.*)(\\1?\s)' . '.*(>)#Usmi', $htmlSource, $matchesSourcesFromLinkTags, PREG_SET_ORDER);

		$stickToRegEx = true; // default

		foreach ($matchesSourcesFromLinkTags as $linkTagArray) {
			$linkTag = $linkTagArray[0];

			preg_match_all('#id=([\'"])(.*?)(\\1)#', $linkTag, $matchId);
			$matchedCssId = isset($matchId[2][0]) ? $matchId[2][0] : '';
			$matchedCssHandle = substr($matchedCssId, 0, -4);

			if (! in_array($matchedCssHandle, $preloadedHandles)) {
				$stickToRegEx = false;
				break;
			}
		}

		// Something might not be right with the RegEx; Fallback to DOMDocument, more accurate, but slower
		if (! $stickToRegEx && function_exists('libxml_use_internal_errors') && function_exists('libxml_clear_errors') && class_exists('\DOMDocument')) {
			$documentForCSS = new \DOMDocument();
			libxml_use_internal_errors(true);

			$htmlSourceAlt = preg_replace( '@<(noscript|style|script)[^>]*?>.*?</\\1>@si', '', $htmlSource );
			$documentForCSS->loadHTML($htmlSourceAlt);

            $linkTags = $documentForCSS->getElementsByTagName( 'link' );

			$matchesSourcesFromLinkTags = array(); // reset its value; new fetch method was used

			foreach ( $linkTags as $tagObject ) {
				if (empty($tagObject->attributes)) { continue; }

				$linkAttributes = array();

				foreach ($tagObject->attributes as $attrObj) {
					$linkAttributes[$attrObj->nodeName] = trim($attrObj->nodeValue);
				}

				if (isset($linkAttributes['data-wpacu-to-be-preloaded-basic'], $linkAttributes['href'])) {
					$matchesSourcesFromLinkTags[][2] = $linkAttributes['href'];
                }
			}

			libxml_clear_errors();
        }

		foreach ($matchesSourcesFromLinkTags as $linkTagArray) {
			$linkHref = isset($linkTagArray[2]) ? $linkTagArray[2] : false;

			if (! $linkHref) {
				continue;
			}

			$linkPreload = self::linkPreloadCssFormat($linkHref);

			$htmlSource = str_replace(self::DEL_STYLES_PRELOADS, $linkPreload . self::DEL_STYLES_PRELOADS, $htmlSource);
		}

		return $htmlSource;
	}

	/**
	 * @param $linkHref
	 *
	 * @return string
	 */
	public static function linkPreloadCssFormat($linkHref)
	{
		if (OptimizeCss::wpfcMinifyCssEnabledOnly()) {
		    // [wpacu_lite]
			return '<link rel=\'preload\' data-from-rel=\'stylesheet\' as=\'style\' href=\''.esc_attr($linkHref).'\' data-wpacu-preload-css-basic=\'1\' />' . "\n";
		    // [/wpacu_lite]
		}

		return '<link rel=\'preload\' as=\'style\' href=\''.esc_attr($linkHref).'\' data-wpacu-preload-css-basic=\'1\' />'."\n";
	}

	/**
	 * @param $htmlSource
	 *
	 * @return mixed
	 */
	public static function appendPreloadsForScriptsToHead($htmlSource)
	{
		$strContainsFormat = preg_quote('data-wpacu-to-be-preloaded-basic=\'1\'', '/');

		preg_match_all('#<script[^>]*'.$strContainsFormat.'[^>]*' . 'src=([\'"])(.*)([\'"])' . '.*(>)#Usmi', $htmlSource, $matchesSourcesFromScriptTags, PREG_SET_ORDER);

		if (empty($matchesSourcesFromScriptTags)) {
			return $htmlSource;
		}

		foreach ($matchesSourcesFromScriptTags as $scriptTagArray) {
			$scriptSrc = isset($scriptTagArray[2]) ? $scriptTagArray[2] : false;

			if (! $scriptSrc) {
				continue;
			}

			$linkPreload = '<link rel=\'preload\' as=\'script\' href=\''.esc_attr($scriptSrc).'\' data-wpacu-preload-js=\'1\'>'."\n";

			$htmlSource = str_replace(self::DEL_SCRIPTS_PRELOADS, $linkPreload . self::DEL_SCRIPTS_PRELOADS, $htmlSource);
		}

		return $htmlSource;
	}

	/**
	 *
	 */
	public static function updatePreloads()
	{
		if (! Misc::isValidRequest('post', 'wpacu_preloads')) {
			return;
		}

		if (! isset($_POST['wpacu_preloads']['styles']) && ! isset($_POST['wpacu_preloads']['scripts'])) {
			return;
		}

		$optionToUpdate = WPACU_PLUGIN_ID . '_global_data';
		$globalKey = 'preloads';

		$existingListEmpty = array('styles' => array($globalKey => array()), 'scripts' => array($globalKey => array()));
		$existingListJson = get_option($optionToUpdate);

		$existingListData = Main::instance()->existingList($existingListJson, $existingListEmpty);
		$existingList = $existingListData['list'];

		foreach ($_POST['wpacu_preloads']['styles'] as $styleHandle => $stylePreload) {
			$stylePreload = trim($stylePreload);

			if ($stylePreload === '' && isset($existingList['styles'][$globalKey][$styleHandle])) {
				unset($existingList['styles'][$globalKey][$styleHandle]);
			} elseif ($stylePreload !== '') {
				$existingList['styles'][$globalKey][$styleHandle] = $stylePreload;
			}
		}

		foreach ($_POST['wpacu_preloads']['scripts'] as $scriptHandle => $scriptPreload) {
			$scriptPreload = trim($scriptPreload);

			if ($scriptPreload === '' && isset($existingList['scripts'][$globalKey][$scriptHandle])) {
				unset($existingList['scripts'][$globalKey][$scriptHandle]);
			} elseif ($scriptPreload !== '') {
				$existingList['scripts'][$globalKey][$scriptHandle] = $scriptPreload;
			}
		}

		Misc::addUpdateOption($optionToUpdate, json_encode(Misc::filterList($existingList)));
	}

	/**
	 * Triggered from "Bulk Unloads" - "Preloaded CSS/JS"
	 * after the selection is made and button is clicked
	 *
	 * @return void
	 */
	public static function removePreloadFromChosenAssets()
	{
		$stylesCheckedList  = Misc::getVar('post', 'wpacu_styles_remove_preloads',  array());
		$scriptsCheckedList = Misc::getVar('post', 'wpacu_scripts_remove_preloads', array());

		if (empty($stylesCheckedList) && empty($scriptsCheckedList)) {
			return;
		}

		\check_admin_referer('wpacu_remove_preloaded_assets', 'wpacu_remove_preloaded_assets_nonce');

		$optionToUpdate = WPACU_PLUGIN_ID . '_global_data';
		$globalKey = 'preloads';

		$existingListEmpty = array('styles' => array($globalKey => array()), 'scripts' => array($globalKey => array()));
		$existingListJson = get_option($optionToUpdate);

		$existingListData = Main::instance()->existingList($existingListJson, $existingListEmpty);
		$existingList = $existingListData['list'];

		if (! empty($stylesCheckedList)) {
			foreach ($stylesCheckedList as $styleHandle => $action) {
				if ($action === 'remove') {
					unset($existingList['styles'][$globalKey][$styleHandle]);
				}
			}
		}

		if (! empty($scriptsCheckedList)) {
			foreach ($scriptsCheckedList as $scriptHandle => $action) {
				if ($action === 'remove') {
					unset($existingList['scripts'][$globalKey][$scriptHandle]);
				}
			}
		}

		Misc::addUpdateOption($optionToUpdate, json_encode(Misc::filterList($existingList)));

		set_transient('wpacu_preloads_just_removed', 1, 30);

		wp_safe_redirect(admin_url('admin.php?page=wpassetcleanup_bulk_unloads&wpacu_bulk_menu_tab=preloaded_assets&wpacu_time='.time()));
		exit();
	}

	/**
	 *
	 */
	public function noticePreloadsRemoved()
	{
		?>
		<div class="updated notice wpacu-notice is-dismissible">
			<p><span class="dashicons dashicons-yes"></span>
				<?php
				_e('The preload option was removed for the chosen CSS/JS.', 'wp-asset-clean-up');
				?>
			</p>
		</div>
		<?php
	}
}
