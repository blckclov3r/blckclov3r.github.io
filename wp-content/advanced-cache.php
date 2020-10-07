<?php

if (!defined('ABSPATH')) die('No direct access allowed');

// WP-Optimize advanced-cache.php (written by version: 3.1.4) (do not change this line, it is used for correctness checks)

if (!defined('WPO_ADVANCED_CACHE')) define('WPO_ADVANCED_CACHE', true);

if (is_admin()) { return; }

$possible_plugin_locations = array(
	defined('WP_PLUGIN_DIR') ? WP_PLUGIN_DIR.'/wp-optimize/cache' : false,
	defined('WP_CONTENT_DIR') ? WP_CONTENT_DIR.'/plugins/wp-optimize/cache' : false,
	dirname(__FILE__).'/plugins/wp-optimize/cache',
	'C:\xampp\htdocs\blckclov3r\wp-content\plugins\wp-optimize\cache',
);

$plugin_location = false;

foreach ($possible_plugin_locations as $possible_location) {
	if (false !== $possible_location && @file_exists($possible_location.'/file-based-page-cache.php')) {
		$plugin_location = $possible_location;
		break;
	}
}

if (!defined('WPO_CACHE_DIR')) define('WPO_CACHE_DIR', WP_CONTENT_DIR.'/wpo-cache');
if (!defined('WPO_CACHE_CONFIG_DIR')) define('WPO_CACHE_CONFIG_DIR', WPO_CACHE_DIR.'/config');
if (!defined('WPO_CACHE_FILES_DIR')) define('WPO_CACHE_FILES_DIR', WP_CONTENT_DIR.'/cache/wpo-cache');
if (false !== $plugin_location) {
	if (!defined('WPO_CACHE_EXT_DIR')) define('WPO_CACHE_EXT_DIR', $plugin_location.'/extensions');
} else {
	if (!defined('WPO_CACHE_EXT_DIR')) define('WPO_CACHE_EXT_DIR', 'C:\xampp\htdocs\blckclov3r\wp-content\plugins\wp-optimize\cache/extensions');
}

if (!@file_exists(WPO_CACHE_CONFIG_DIR . '/config-localhost.php')) { return; }

$GLOBALS['wpo_cache_config'] = @json_decode(file_get_contents(WPO_CACHE_CONFIG_DIR . '/config-localhost.php'), true);

if (empty($GLOBALS['wpo_cache_config'])) {
	include_once(WPO_CACHE_CONFIG_DIR . '/config-localhost.php');
}

if (empty($GLOBALS['wpo_cache_config']) || empty($GLOBALS['wpo_cache_config']['enable_page_caching'])) { return; }

if (false !== $plugin_location) { include_once($plugin_location.'/file-based-page-cache.php'); }
