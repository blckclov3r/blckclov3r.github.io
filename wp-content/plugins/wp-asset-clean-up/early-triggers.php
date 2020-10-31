<?php
// Exit if accessed directly
if (! defined('ABSPATH')) {
	exit;
}

if (array_key_exists('wpacu_clean_load', $_GET)) {
	// Autoptimize
	$_GET['ao_noptimize'] = $_REQUEST['ao_noptimize'] = '1';

	// LiteSpeed Cache
	if ( ! defined( 'LITESPEED_DISABLE_ALL' ) ) {
		define('LITESPEED_DISABLE_ALL', true);
	}

	add_action( 'litespeed_disable_all', static function($reason) {
		do_action( 'litespeed_debug', '[API] Disabled_all due to: A clean load of the page was requested via '. WPACU_PLUGIN_TITLE );
	} );

	// No "WP-Optimize – Clean, Compress, Cache." minify
	add_filter('pre_option_wpo_minify_config', function() { return array(); });
}

if (! function_exists('assetCleanUpHasNoLoadMatches')) {
	/**
	 * Any matches from "Settings" -> "Plugin Usage Preferences" -> "Do not load the plugin on certain pages"?
	 * @param $targetUri
	 *
	 * @return bool
	 */
	function assetCleanUpHasNoLoadMatches($targetUri = '')
	{
		if ($targetUri === '') {
			// When called from the Dashboard, it should never be empty
			if (is_admin()) {
				return false;
			}

			$targetUri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : ''; // Invalid request
		} else {
			// Passed from the Dashboard as an URL; Strip the prefix and hostname to keep only the URI
			$parseUrl = parse_url($targetUri);
			$targetUri = isset($parseUrl['path']) ? $parseUrl['path'] : '';
		}

		if ($targetUri === '') {
			return false; // Invalid request
		}

		$doNotLoadRegExps = array();

		$wpacuPluginSettingsJson = get_option( WPACU_PLUGIN_ID . '_settings' );
		$wpacuPluginSettings     = @json_decode( $wpacuPluginSettingsJson, ARRAY_A );
		$doNotLoadPatterns       = isset( $wpacuPluginSettings['do_not_load_plugin_patterns'] ) ? $wpacuPluginSettings['do_not_load_plugin_patterns'] : '';

		if ( $doNotLoadPatterns !== '' ) {
			$doNotLoadPatterns = trim( $doNotLoadPatterns );

			if ( strpos( $doNotLoadPatterns, "\n" ) ) {
				// Multiple values (one per line)
				foreach ( explode( "\n", $doNotLoadPatterns ) as $doNotLoadPattern ) {
					$doNotLoadPattern = trim( $doNotLoadPattern );
					if ( $doNotLoadPattern ) {
						$doNotLoadRegExps[] = '#' . $doNotLoadPattern . '#';
					}
				}
			} elseif ( $doNotLoadPatterns ) {
				// Only one value?
				$doNotLoadRegExps[] = '#' . $doNotLoadPatterns . '#';
			}
		}

		if ( ! empty( $doNotLoadRegExps ) ) {
			foreach ( $doNotLoadRegExps as $doNotLoadRegExp ) {
				if ( preg_match( $doNotLoadRegExp, $targetUri ) ) {
					// There's a match
					return $targetUri;
				}
			}
		}

		return false;
	}
}

if (! function_exists('assetCleanUpNoLoad')) {
	/**
	 * There are special cases when triggering "Asset CleanUp" is not relevant
	 * Thus, for maximum compatibility and backend processing speed, it's better to avoid running any of its code
	 *
	 * @return bool
	 */
	function assetCleanUpNoLoad()
	{
		// Hide top WordPress admin bar on request for debugging purposes and a cleared view of the tested page
		if (array_key_exists('wpacu_no_admin_bar', $_GET)) {
			add_filter('show_admin_bar', '__return_false', PHP_INT_MAX);
		}

		// On request: for debugging purposes - e.g. https://yourwebsite.com/?wpacu_no_load
		// Also make sure it's in the REQUEST URI and $_GET wasn't altered incorrectly before it's checked
		// Technically, it will be like the plugin is not activated: no global settings and unload rules will be applied
		if (array_key_exists('wpacu_no_load', $_GET) && strpos($_SERVER['REQUEST_URI'], 'wpacu_no_load') !== false) {
			return true;
		}

		// Needs to be called ideally from a MU plugin which always loads before Asset CleanUp
		// or from a different plugin that triggers before Asset CleanUp which is less reliable
		if (apply_filters('wpacu_plugin_no_load', false)) {
			return true;
		}

		// "Elementor" plugin Admin Area: Edit Mode
		if (isset($_GET['post'], $_GET['action']) && $_GET['post'] && $_GET['action'] === 'elementor' && is_admin()) {
			return true;
		}

		// "Elementor" plugin (Preview Mode within Page Builder)
		if (isset($_GET['elementor-preview'], $_GET['ver']) && (int)$_GET['elementor-preview'] > 0 && $_GET['ver']) {
			return true;
		}

		$wpacuIsAjaxRequest = (! empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest');

		// If an AJAX call is made to /wp-admin/admin-ajax.php and the action doesn't start with WPACU_PLUGIN_ID.'_
		// then do not trigger Asset CleanUp Pro as it's irrelevant
		$wpacuActionStartsWith = WPACU_PLUGIN_ID.'_';

		if ($wpacuIsAjaxRequest && // Is AJAX request
		    isset($_POST['action']) && // Has 'action' set as a POST parameter
		    strpos( $_POST['action'], $wpacuActionStartsWith ) !== 0 && // Doesn't start with $wpacuActionStartsWith
		    (strpos($_SERVER['REQUEST_URI'], 'admin-ajax.php') !== false) && // The request URI contains 'admin-ajax.php'
		    is_admin()) { // If /wp-admin/admin-ajax.php is called, then it will return true
			return true;
		}

		// Image Edit via Media Library
		if ($wpacuIsAjaxRequest && isset($_POST['action'], $_POST['postid']) && $_POST['action'] === 'image-editor') {
			return true;
		}

		// "Elementor" plugin: Do not trigger the plugin on AJAX calls
		if ($wpacuIsAjaxRequest && isset($_POST['action']) && (strpos($_POST['action'], 'elementor_') === 0)) {
			return true;
		}

		// "Oxygen" plugin: Edit Mode
		if (isset($_GET['ct_builder'], $_GET['ct_inner']) && $_GET['ct_builder'] === 'true' && $_GET['ct_inner'] === 'true') {
			return true;
		}

		// "Oxygen" plugin: Block Edit Mode
		if (isset($_GET['oxy_user_library'], $_GET['ct_builder']) && $_GET['oxy_user_library'] && $_GET['ct_builder']) {
			return true;
		}

		// "Oxygen" plugin (v2.4.1+): Edit Mode (Reusable Template)
		if (isset($_GET['ct_builder'], $_GET['ct_template']) && $_GET['ct_builder'] && $_GET['ct_template']) {
			return true;
		}

		// "Divi" theme builder: Front-end View Edit Mode
		if (isset($_GET['et_fb'], $_GET['PageSpeed']) && $_GET['et_fb'] == 1 && $_GET['PageSpeed']) {
			return true;
		}

		// "Divi" theme builder: Do not trigger the plugin on AJAX calls
		if ($wpacuIsAjaxRequest && isset($_POST['action']) && (strpos($_POST['action'], 'et_fb_') === 0)) {
			return true;
		}

		// Beaver Builder
		if (isset($_GET['fl_builder'])) {
			return true;
		}

		// Thrive Architect (Dashboard)
		if (isset($_GET['action'], $_GET['tve']) && $_GET['action'] === 'architect' && $_GET['tve'] === 'true' && is_admin()) {
			return true;
		}

		// Thrive Architect (iFrame)
		$tveFrameFlag = defined('TVE_FRAME_FLAG') ? TVE_FRAME_FLAG : 'tcbf';

		if (isset($_GET['tve'], $_GET[$tveFrameFlag]) && $_GET['tve'] === 'true') {
			return true;
		}

		// Page Builder by SiteOrigin
		if (isset($_GET['action'], $_GET['so_live_editor']) && $_GET['action'] === 'edit' && $_GET['so_live_editor'] && is_admin()) {
			return true;
		}

		// Brizy - Page Builder
		if (isset($_GET['brizy-edit']) || isset($_GET['brizy-edit-iframe'])) {
			return true;
		}

		// Fusion Builder Live: Avada
		if ((isset($_GET['fb-edit']) && $_GET['fb-edit']) || isset($_GET['builder'], $_GET['builder_id'])) {
			return true;
		}

		// WPBakery Page Builder
		if (isset($_GET['vc_editable'], $_GET['_vcnonce']) || (is_admin() && isset($_GET['vc_action']))) {
			return true;
		}

		// Themify Builder (iFrame)
		if (isset($_GET['tb-preview']) && $_GET['tb-preview']) {
			return true;
		}

		// "Pro" (theme.co) (iFrame)
		if (isset($_POST['_cs_nonce'], $_POST['cs_preview_state']) && $_POST['_cs_nonce'] && $_POST['cs_preview_state']) {
			return true;
		}

		// "Page Builder: Live Composer" plugin
		if (defined('DS_LIVE_COMPOSER_ACTIVE') && DS_LIVE_COMPOSER_ACTIVE) {
			return true;
		}

		// "WP Page Builder" plugin (By Themeum.com)
		if (isset($_GET['load_for']) && $_GET['load_for'] === 'wppb_editor_iframe') {
			return true;
		}

		// Perfmatters: Script Manager
		if (isset($_GET['perfmatters'])) {
			return true;
		}

		// Gravity Forms: Preview Page
		if (isset($_GET['gf_page']) && $_GET['gf_page'] === 'preview') {
			return true;
		}

		// Custom CSS Pro: Editor
		if ((isset($_GET['page']) && $_GET['page'] === 'ccp-editor')
		    || (isset($_GET['ccp-iframe']) && $_GET['ccp-iframe'] === 'true')) {
			return true;
		}

		// TranslatePress Multilingual: Edit translation mode
		if (isset($_GET['trp-edit-translation']) && $_GET['trp-edit-translation'] === 'preview') {
			return true;
		}

		// WordPress Customise Mode
		if ((isset($_GET['customize_changeset_uuid'], $_GET['customize_theme']) && $_GET['customize_changeset_uuid'] && $_GET['customize_theme'])
		    || (strpos($_SERVER['REQUEST_URI'], '/wp-admin/customize.php') !== false && isset($_GET['url']) && $_GET['url'])) {
			return true;
		}

		$cleanRequestUri = trim($_SERVER['REQUEST_URI'], '?');
		if (strpos($cleanRequestUri, '?') !== false) {
			list ($cleanRequestUri) = explode('?', $cleanRequestUri);
		}

		// REST Request
		if ((defined('REST_REQUEST') && REST_REQUEST)
		    || (strpos($_SERVER['REQUEST_URI'], '/wp-json/wp/v2/') !== false)
		    || (strpos($cleanRequestUri, '/wp-json/wc/') !== false)
		) {
			return true;
		}

		$parseUrl = parse_url(get_site_url());
		$parseUrlPath = isset($parseUrl['path']) ? $parseUrl['path'] : '';
		$targetUriAfterSiteUrl = trim(str_replace(array(get_site_url(), $parseUrlPath), '', $_SERVER['REQUEST_URI']), '/');

		if (strpos($targetUriAfterSiteUrl, 'wp-json/wc/') === 0) {
			return true;
		}

		// WordPress AJAX Heartbeat
		if (isset($_POST['action']) && $_POST['action'] === 'heartbeat') {
			return true;
		}

		// EDD Plugin (Listener)
		if (isset($_GET['edd-listener']) && $_GET['edd-listener']) {
			return true;
		}

		// AJAX Requests from various plugins/themes
		if ($wpacuIsAjaxRequest && isset($_POST['action'])
		       && (strpos($_POST['action'], 'woocommerce') === 0
		        || strpos($_POST['action'], 'wc_') === 0
		        || strpos($_POST['action'], 'jetpack') === 0
		        || strpos($_POST['action'], 'wpfc_') === 0
		        || strpos($_POST['action'], 'oxygen_') === 0
		        || strpos($_POST['action'], 'oxy_') === 0
		        || strpos($_POST['action'], 'w3tc_') === 0
		        || strpos($_POST['action'], 'wpforms_') === 0
		        || strpos($_POST['action'], 'wdi_') === 0
		    )) {
			return true;
		}

		// e.g. WooCommerce's AJAX call to /?wc-ajax=checkout | no need to trigger Asset CleanUp then, not only avoiding any errors, but also saving resources
		// "wc-ajax" could be one of the following: update_order_review, apply_coupon, checkout, etc.
		if (isset($_REQUEST['wc-ajax']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
			return true;
		}

		// Stop triggering Asset CleanUp (completely) on specific front-end pages
		// Do the trigger here and if necessary exit as early as possible to save resources via "registered_taxonomy" action hook)
		if (assetCleanUpHasNoLoadMatches()) {
			// Only use exit() when "wpassetcleanup_load" is used
			if (isset($_REQUEST['wpassetcleanup_load']) && $_REQUEST['wpassetcleanup_load']) {
				add_action('registered_taxonomy', function() {
					if ( current_user_can( 'administrator' ) ) {
						$msg = sprintf(
							__(
								'This page\'s URL is matched by one of the RegEx rules you have in <em>"Settings"</em> -&gt; <em>"Plugin Usage Preferences"</em> -&gt; <em>"Do not load the plugin on certain pages"</em>, thus %s is not loaded on that page and no CSS/JS are to be managed. If you wish to view the CSS/JS manager, please remove the matching RegEx rule and the list of CSS/JS will be fetched.',
								'wp-asset-clean-up'
							),
							WPACU_PLUGIN_TITLE
						);
						exit( $msg );
					}
				});
			}

			return true;
		}

		return false;
	}
}

// In case JSON library is not enabled (rare cases)
if (! defined('JSON_ERROR_NONE')) {
	define('JSON_ERROR_NONE', 0);
}

// Make sure the plugin doesn't load when the editor of either "X" theme or "Pro" website creator (theme.co) is ON
add_action('init', static function() {
	if (is_admin()) {
		return; // Not relevant for the Dashboard view, stop here!
	}

	if (class_exists('\WpAssetCleanUp\Menu') && \WpAssetCleanUp\Menu::userCanManageAssets() && method_exists('Cornerstone_Common', 'get_app_slug') && in_array(get_stylesheet(), array('x', 'pro'))) {
		$customAppSlug = get_stylesheet(); // default one ('x' or 'pro')

		// Is there any custom slug set in "/wp-admin/admin.php?page=cornerstone-settings"?
		// "Settings" -> "Custom Path" (check it out below)
		$cornerStoneSettings = get_option('cornerstone_settings');
		if (isset($cornerStoneSettings['custom_app_slug']) && $cornerStoneSettings['custom_app_slug'] !== '') {
			$customAppSlug = $cornerStoneSettings['custom_app_slug'];
		}

		$lengthToUse = strlen($customAppSlug) + 2; // add the slashes to the count

		if (substr($_SERVER['REQUEST_URI'], -$lengthToUse) === '/'.$customAppSlug.'/') {
			add_filter( 'wpacu_prevent_any_changes', '__return_true' );
		}
	}
});
