<?php
/**
 * WP_Optimize_Updates class using for run updates in database from version to version.
 */

if (!defined('ABSPATH')) die('Access denied.');

if (!class_exists('WP_Optimize_Updates')) :

class WP_Optimize_Updates {

	/**
	 * Format: key=<version>, value=array of method names to call
	 * Example Usage:
	 *	private static $db_updates = array(
	 *		'1.0.1' => array(
	 *			'update_101_add_new_column',
	 *		),
	 *	);
	 *
	 * @var Mixed
	 */
	private static $updates = array(
		'3.0.12' => array('delete_old_locks'),
		'3.0.17' => array('disable_cache_directories_viewing'),
		'3.1.0' => array('reset_wpo_plugin_cron_tasks_schedule'),
		'3.1.4' => array('enable_minify_defer'),
	);

	/**
	 * See if any database schema updates are needed, and perform them if so.
	 * Example Usage:
	 * public static function update_101_add_new_column() {
	 *		$wpdb = $GLOBALS['wpdb'];
	 *		$wpdb->query('ALTER TABLE tm_tasks ADD task_expiry varchar(300) AFTER id');
	 *	}
	 */
	public static function check_updates() {
		$our_version = WPO_VERSION;
		$db_version = get_option('wpo_update_version');
		if (!$db_version || version_compare($our_version, $db_version, '>')) {
			foreach (self::$updates as $version => $updates) {
				if (version_compare($version, $db_version, '>')) {
					foreach ($updates as $update) {
						call_user_func(array(__CLASS__, $update));
					}
				}
			}
			update_option('wpo_update_version', WPO_VERSION);
		}
	}

	/**
	 * Delete old semaphore locks from options database table.
	 */
	public static function delete_old_locks() {
		global $wpdb;

		// using this query we delete all rows related to locks.
		$query = "DELETE FROM {$wpdb->options}".
				" WHERE (option_name LIKE ('updraft_semaphore_%')".
				" OR option_name LIKE ('updraft_last_lock_time_%')".
				" OR option_name LIKE ('updraft_locked_%')".
				" OR option_name LIKE ('updraft_unlocked_%'))".
				" AND ".
				"(option_name LIKE ('%smush')".
				" OR option_name LIKE ('%load-url-task'));";

		$wpdb->query($query);
	}

	/**
	 * Disable cache directories viewing.
	 */
	public static function disable_cache_directories_viewing() {
		wpo_disable_cache_directories_viewing();
	}

	public static function reset_wpo_plugin_cron_tasks_schedule() {
		wp_clear_scheduled_hook('wpo_plugin_cron_tasks');
	}

	/**
	 * Update Minify Defer option (The option was hidden until now, but we're changing the setting)
	 *
	 * @return void
	 */
	public static function enable_minify_defer() {
		if (!function_exists('wp_optimize_minify_config')) {
			include_once WPO_PLUGIN_MAIN_PATH . '/minify/class-wp-optimize-minify-config.php';
		}
		$current_setting = wp_optimize_minify_config()->get('enable_defer_js');
		if (true === $current_setting) {
			wp_optimize_minify_config()->update(array('enable_defer_js' => 'all'));
		} else {
			wp_optimize_minify_config()->update(array('enable_defer_js' => 'individual'));
		}
	}
}

endif;
