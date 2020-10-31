<?php
// Exit if accessed directly
if (! defined('WPACU_PLUGIN_CLASSES_PATH')) {
    exit;
}

// Autoload Classes
function includeWpAssetCleanUpClassesAutoload($class)
{
    $namespace = 'WpAssetCleanUp';

    // continue only if the namespace is within $class
    if (strpos($class, $namespace) === false) {
        return;
    }

    $classFilter = str_replace($namespace.'\\', '', $class);

    // Can be directories such as "Helpers"
    $classFilter = str_replace('\\', '/', $classFilter);

    $pathToClass = WPACU_PLUGIN_CLASSES_PATH.$classFilter.'.php';

    if (is_file($pathToClass)) {
        include_once $pathToClass;
    }
}

spl_autoload_register('includeWpAssetCleanUpClassesAutoload');

\WpAssetCleanUp\ObjectCache::wpacu_cache_init();

if (isset($GLOBALS['wpacu_object_cache'])) {
	$wpacu_object_cache = $GLOBALS['wpacu_object_cache']; // just in case
}

// Main Class
\WpAssetCleanUp\Main::instance();

$wpacuSettingsClass = new \WpAssetCleanUp\Settings();

if (is_admin()) {
	$wpacuSettingsClass->adminInit();
}

// Plugin's Assets (used only when you're logged in)
$wpacuOwnAssets = new \WpAssetCleanUp\OwnAssets;
$wpacuOwnAssets->init();

// Add / Update / Remove Settings
$wpacuUpdate = new \WpAssetCleanUp\Update;
$wpacuUpdate->init();

// Menu
new \WpAssetCleanUp\Menu;

add_action('plugins_loaded', function() use ($wpacuSettingsClass) {
	$wpacuSettings = $wpacuSettingsClass->getAll();

// If "Manage in the front-end" is enabled & the admin is logged-in, do not trigger any Autoptimize caching at all
	if ( $wpacuSettings['frontend_show'] && \WpAssetCleanUp\Menu::userCanManageAssets() && ! defined( 'AUTOPTIMIZE_NOBUFFER_OPTIMIZE' ) ) {
		define( 'AUTOPTIMIZE_NOBUFFER_OPTIMIZE', true );
	}
}, -PHP_INT_MAX);

// Admin Bar (Top Area of the website when user is logged in)
new \WpAssetCleanUp\AdminBar();

// Initialize information
new \WpAssetCleanUp\Info();

// Any debug?
new \WpAssetCleanUp\Debug();

// Maintenance
new \WpAssetCleanUp\Maintenance();

// Common functions for both CSS & JS combinations
// Clear CSS/JS caching functionality
$wpacuOptimizeCommon = new \WpAssetCleanUp\OptimiseAssets\OptimizeCommon();
$wpacuOptimizeCommon->init();

if (is_admin()) {
	/*
	 * Trigger only within the Dashboard view (e.g. within /wp-admin/)
	 */
	$wpacuPlugin = new \WpAssetCleanUp\Plugin;
	$wpacuPlugin->init();

	new \WpAssetCleanUp\PluginReview();

	$wpacuPluginTracking = new \WpAssetCleanUp\PluginTracking();
	$wpacuPluginTracking->init();

	$wpacuTools = new \WpAssetCleanUp\Tools();
	$wpacuTools->init();
} elseif (\WpAssetCleanUp\Misc::triggerFrontendOptimization()) {
	/*
	 * Trigger the CSS & JS combination only in the front-end view in certain conditions (not within the Dashboard)
	 */
	// Combine/Minify CSS Files Setup
	$wpacuOptimizeCss = new \WpAssetCleanUp\OptimiseAssets\OptimizeCss();
	$wpacuOptimizeCss->init();

	// Combine/Minify JS Files Setup
	$wpacuOptimizeJs = new \WpAssetCleanUp\OptimiseAssets\OptimizeJs();
	$wpacuOptimizeJs->init();

	/*
	 * Trigger only in the front-end view (e.g. Homepage URL, /contact/, /about/ etc.)
	 */
	$wpacuCleanUp = new \WpAssetCleanUp\CleanUp();
	$wpacuCleanUp->init();

	$wpacuFontsLocal = new \WpAssetCleanUp\OptimiseAssets\FontsLocal();
	$wpacuFontsLocal->init();

	$wpacuFontsGoogle = new \WpAssetCleanUp\OptimiseAssets\FontsGoogle();
	$wpacuFontsGoogle->init();
}

\WpAssetCleanUp\Preloads::instance()->init();
