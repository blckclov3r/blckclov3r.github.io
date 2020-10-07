<?php
if (!defined('ABSPATH')) die('No direct access allowed');

define('WP_OPTIMIZE_MINIFY_VERSION', '2.6.5');
define('WP_OPTIMIZE_MINIFY_DIR', dirname(__FILE__));
if (!defined('WP_OPTIMIZE_SHOW_MINIFY_ADVANCED')) define('WP_OPTIMIZE_SHOW_MINIFY_ADVANCED', false);

class WP_Optimize_Minify {
	/**
	 * Constructor - Initialize actions and filters
	 *
	 * @return void
	 */
	public function __construct() {
		if (!class_exists('WP_Optimize_Minify_Incompatible_Plugins')) include WP_OPTIMIZE_MINIFY_DIR.'/class-wp-optimize-minify-incompatible-plugins.php';
		$found_incompatible_plugins = WP_Optimize_Minify_Incompatible_Plugins::instance()->found_incompatible_plugins();

		/**
		 * Filters the list of plugins incompatible with minify which are currently active. Returning false will enable the feature anyways.
		 *
		 * @param array|boolean $found_incompatible_plugins - The active incompatible plugins
		 * @return array|boolean
		 */
		if (apply_filters('wpo_minify_found_incompatible_plugins', $found_incompatible_plugins)) {
			$this->incompatible_plugins = $found_incompatible_plugins;
			add_action('wp_optimize_admin_page_wpo_minify_status', array($this, 'output_incompatible_status'), 20);
			return;
		}

		if (!class_exists('WP_Optimize_Minify_Config')) {
			include WP_OPTIMIZE_MINIFY_DIR.'/class-wp-optimize-minify-config.php';
		}

		$this->enabled = wp_optimize_minify_config()->is_enabled();

		$this->load_admin();

		// Don't run the rest if PHP requirement isn't met
		if (!WPO_MINIFY_PHP_VERSION_MET) return;

		/**
		 * Directory that stores the cache, including gzipped files and mobile specifc cache
		 */
		if (!defined('WPO_CACHE_MIN_FILES_DIR')) define('WPO_CACHE_MIN_FILES_DIR', untrailingslashit(WP_CONTENT_DIR).'/cache/wpo-minify');
		if (!defined('WPO_CACHE_MIN_FILES_URL')) define('WPO_CACHE_MIN_FILES_URL', untrailingslashit(WP_CONTENT_URL).'/cache/wpo-minify');

		if (!class_exists('WP_Optimize_Minify_Cache_Functions')) {
			include WP_OPTIMIZE_MINIFY_DIR.'/class-wp-optimize-minify-cache-functions.php';
		}

		$this->load_frontend();

		// cron job to delete old wpo_min cache
		add_action('wpo_minify_purge_old_cache', array('WP_Optimize_Minify_Cache_Functions', 'purge_old'));
		// front-end actions; skip on certain post_types or if there are specific keys on the url or if editor or admin
	}

	/**
	 * Load the admin class
	 *
	 * @return void
	 */
	private function load_admin() {
		if (!is_admin()) return;

		if (!class_exists('WP_Optimize_Minify_Admin')) {
			include WP_OPTIMIZE_MINIFY_DIR.'/class-wp-optimize-minify-admin.php';
		}
		new WP_Optimize_Minify_Admin();
	}

	/**
	 * Load the frontend class
	 *
	 * @return void
	 */
	private function load_frontend() {
		if ($this->enabled) {
			if (!class_exists('WP_Optimize_Minify_Front_End')) {
				include WP_OPTIMIZE_MINIFY_DIR.'/class-wp-optimize-minify-front-end.php';
			}
			new WP_Optimize_Minify_Front_End();
		}
	}

	/**
	 * Run during activation
	 * Increment cache first as it will save files to that dir
	 *
	 * @return void
	 */
	public function plugin_activate() {
		// increment cache time
		if (class_exists('WP_Optimize_Minify_Cache_Functions')) {
			WP_Optimize_Minify_Cache_Functions::cache_increment();
		}
		
		// old cache purge event cron
		wp_clear_scheduled_hook('wpo_minify_purge_old_cache');
		if (!wp_next_scheduled('wpo_minify_purge_old_cache')) {
			wp_schedule_event(time() + 86400, 'daily', 'wpo_minify_purge_old_cache');
		}
	}

	/**
	 * Run during plugin deactivation
	 *
	 * @return void
	 */
	public function plugin_deactivate() {
		if (class_exists('WP_Optimize_Minify_Cache_Functions')) {
			WP_Optimize_Minify_Cache_Functions::purge_temp_files();
			WP_Optimize_Minify_Cache_Functions::purge_old();
			WP_Optimize_Minify_Cache_Functions::purge_others();
		}

		// old cache purge event cron
		wp_clear_scheduled_hook('wpo_minify_purge_old_cache');
	}

	/**
	 * Run during plugin uninstall
	 *
	 * @return void
	 */
	public function plugin_uninstall() {
		// remove options from DB
		if (!function_exists('wp_optimize_minify_config')) {
			include WP_OPTIMIZE_MINIFY_DIR.'/class-wp-optimize-minify-config.php';
		}
		wp_optimize_minify_config()->purge();
		// remove minified files
		if (class_exists('WP_Optimize_Minify_Cache_Functions')) {
			WP_Optimize_Minify_Cache_Functions::purge();
			WP_Optimize_Minify_Cache_Functions::purge_others();
		}
	}

	/**
	 * Outputs a message in the status tab, when the feature can't be loaded
	 *
	 * @return void
	 */
	public function output_incompatible_status() {
		?>
		<div class="notice notice-error below-h2">
			<p>
				<?php printf(__('Minify cannot be loaded, because an incompatible plugin was found: %s', 'wp-optimize'), implode(', ', $this->incompatible_plugins)); ?>
			</p>
		</div>
		<?php
	}
}
