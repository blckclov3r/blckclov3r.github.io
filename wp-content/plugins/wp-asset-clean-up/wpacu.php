<?php
/*
 * Plugin Name: Asset CleanUp: Page Speed Booster
 * Plugin URI: https://wordpress.org/plugins/wp-asset-clean-up/
 * Version: 1.3.7.0
 * Description: Unload Chosen Scripts & Styles from Posts/Pages to reduce HTTP Requests, Combine/Minify CSS/JS files
 * Author: Gabriel Livan
 * Author URI: http://gabelivan.com/
 * Text Domain: wp-asset-clean-up
 * Domain Path: /languages
*/

// Is the Pro version triggered before the Lite one and are both plugins active?
if (! defined('WPACU_PLUGIN_VERSION')) {
	define('WPACU_PLUGIN_VERSION', '1.3.7.0');
}

// Exit if accessed directly
if (! defined('ABSPATH')) {
    exit;
}

if (! defined('WPACU_PLUGIN_ID')) {
	define( 'WPACU_PLUGIN_ID', 'wpassetcleanup' ); // unique prefix (same plugin ID name for 'lite' and 'pro')
}

if ( ! defined('WPACU_PLUGIN_TITLE') ) {
	define( 'WPACU_PLUGIN_TITLE', 'Asset CleanUp' ); // a short version of the plugin name
}

require_once __DIR__.'/early-triggers.php';

if (assetCleanUpNoLoad()) {
	return; // do not continue
}

// Premium plugin version already exists, is it active?
// This action is valid starting from LITE version 1.2.6.8
// From 1.0.3, the PRO version works independently (does not need anymore LITE to be active and act as a parent plugin)
// However, it's good to have both versions active for compatibility with plugins such as "WP Cloudflare Super Page Cache"

// If the pro version (version above 1.0.2) was triggered first, we'll just check one of its constants
// If the lite version was triggered first, then we'll check if the pro version is active
// Lastly, check if the Pro version is activated via is_plugin_active()
if ( (defined('WPACU_PRO_NO_LITE_NEEDED') && WPACU_PRO_NO_LITE_NEEDED !== false && defined('WPACU_PRO_PLUGIN_VERSION') && WPACU_PRO_PLUGIN_VERSION !== false)
     || (function_exists('is_plugin_active') && is_plugin_active('wp-asset-clean-up-pro/wpacu.php')) ) {
	// Stop here as the Pro version handles everything the Lite does
	return;
}

define('WPACU_PLUGIN_FILE', __FILE__);
define('WPACU_PLUGIN_BASE', plugin_basename(WPACU_PLUGIN_FILE));

define('WPACU_ADMIN_PAGE_ID_START', WPACU_PLUGIN_ID . '_getting_started');

// Do not load the plugin if the PHP version is below 5.6
// If PHP_VERSION_ID is not defined, then PHP version is below 5.2.7, thus the plugin is not usable

$wpacuWrongPhp = ((! defined('PHP_VERSION_ID')) || (defined('PHP_VERSION_ID') && PHP_VERSION_ID < 50600));

if (! defined('WPACU_WRONG_PHP_VERSION')) {
	define( 'WPACU_WRONG_PHP_VERSION', ( ( $wpacuWrongPhp ) ? 'true' : 'false' ) );
}

if ($wpacuWrongPhp && is_admin()) { // Dashboard
    add_action('admin_notices', function() {
	    /**
	     * Print the message to the user after the plugin was deactivated
	     */
	    echo '<div class="error is-dismissible"><p>'.

	         sprintf(
		         __('%1$s requires %2$s PHP version installed. You have %3$s.', 'wp-asset-clean-up'),
		         '<strong>'.WPACU_PLUGIN_TITLE.'</strong>',
		         '<span style="color: green;"><strong>5.6+</strong></span>',
		         '<strong>'.PHP_VERSION.'</strong>'
	         ) . ' '.
	         __('If your website is compatible with PHP 7+ (e.g. you can check with your developers or contact the hosting company), it\'s strongly recommended to upgrade to a newer PHP version for a better performance.', 'wp-asset-clean-up').' '.
	         __('Thus, the plugin will not trigger on the front-end view to avoid any possible errors.', 'wp-asset-clean-up').

	         '</p></div>';

	    if (array_key_exists('active', $_GET)) {
		    unset($_GET['activate']);
	    }
    });
} elseif ($wpacuWrongPhp) { // Front
    return;
}

define('WPACU_PLUGIN_DIR',          __DIR__);
define('WPACU_PLUGIN_CLASSES_PATH', WPACU_PLUGIN_DIR.'/classes/');
define('WPACU_PLUGIN_URL',          plugins_url('', WPACU_PLUGIN_FILE));

// Upgrade to Pro Sales Page
define('WPACU_PLUGIN_GO_PRO_URL',   'https://www.gabelivan.com/items/wp-asset-cleanup-pro/');

// Global Values
define('WPACU_LOAD_ASSETS_REQ_KEY', WPACU_PLUGIN_ID . '_load');

$wpacuGetLoadedAssetsAction = ((isset($_REQUEST[WPACU_LOAD_ASSETS_REQ_KEY]) && $_REQUEST[WPACU_LOAD_ASSETS_REQ_KEY])
                               || (isset($_REQUEST['action']) && $_REQUEST['action'] === WPACU_PLUGIN_ID.'_get_loaded_assets'));
define('WPACU_GET_LOADED_ASSETS_ACTION', $wpacuGetLoadedAssetsAction);

require_once WPACU_PLUGIN_DIR.'/wpacu-load.php';

if (WPACU_GET_LOADED_ASSETS_ACTION === true || ! is_admin()) {
	add_action('init', static function() {
		// "Smart Slider 3" & "WP Rocket" compatibility fix | triggered ONLY when the assets are fetched
		if ( ! function_exists('get_rocket_option') && class_exists( 'NextendSmartSliderWPRocket' ) ) {
			function get_rocket_option($option) { return ''; }
		}
	});

	add_action('parse_query', static function() { // very early triggering to set WPACU_ALL_ACTIVE_PLUGINS_LOADED
		if (defined('WPACU_ALL_ACTIVE_PLUGINS_LOADED')) { return; } // only trigger it once in this action
		define('WPACU_ALL_ACTIVE_PLUGINS_LOADED', true);
		\WpAssetCleanUp\Plugin::preventAnyChanges();
	}, 1);

	require_once WPACU_PLUGIN_DIR . '/vendor/autoload.php';
}

// No plugin changes are needed when a feed is loaded
add_action('setup_theme', static function() {
	// Only in the front-end view and when a request URI is there (e.g. not triggering the WP environment via an SSH terminal)
	if ( ! isset($_SERVER['REQUEST_URI']) || is_admin() ) {
		return;
	}

	global $wp_rewrite;

	if (isset($wp_rewrite->feed_base) &&
	    $wp_rewrite->feed_base &&
	    strpos($_SERVER['REQUEST_URI'], '/'.$wp_rewrite->feed_base) !== false) {
		$currentPageUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . parse_url(site_url(), PHP_URL_HOST) . $_SERVER['REQUEST_URI'];

		$cleanCurrentPageUrl = $currentPageUrl;
		if (strpos($currentPageUrl, '?') !== false) {
			list($cleanCurrentPageUrl) = explode('?', $currentPageUrl);
		}

		// /{feed_slug_here}/ or /{feed_slug_here}/atom/
		if ($cleanCurrentPageUrl === site_url().'/'.$wp_rewrite->feed_base.'/'
		    || $cleanCurrentPageUrl === site_url().'/'.$wp_rewrite->feed_base.'/atom/') {
			\WpAssetCleanUp\Plugin::preventAnyChanges();
		}
	}
});

