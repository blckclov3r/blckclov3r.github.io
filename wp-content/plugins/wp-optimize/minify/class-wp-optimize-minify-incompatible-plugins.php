<?php
if (!defined('ABSPATH')) die('No direct access allowed');

if (!class_exists('WP_Optimize_Detect_Cache_Plugins')) require_once WPO_PLUGIN_MAIN_PATH . 'cache/class-wpo-detect-cache-plugins.php';

class WP_Optimize_Minify_Incompatible_Plugins extends WP_Optimize_Detect_Cache_Plugins {

	/**
	 * Get the plugins list
	 *
	 * @return array
	 */
	protected function get_plugins() {
		return array(
			'w3-total-cache' => 'W3 Total Cache',
		);
	}

	/**
	 * List of currently active plugins which are incompatible with Minify
	 *
	 * @return array|boolean
	 */
	public function found_incompatible_plugins() {
		$active_plugins = $this->get_active_cache_plugins();

		if (!empty($active_plugins)) {
			return $active_plugins;
		}
		return false;
	}


	/**
	 * Instance of WP_Optimize_Detect_Cache_Plugins.
	 *
	 * @return WP_Optimize_Minify_Incompatible_Plugins
	 */
	static public function instance() {
		static $instance;
		if (empty($instance)) {
			$instance = new self();
		}

		return $instance;
	}
}
