<?php

/**
 * Fired during plugin deactivation
 *
 * @link       https://www.feataholic.com
 * @since      1.1.8 Free
 *
 * @package    MZLDR
 * @subpackage MZLDR/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.1.8 Free
 * @package    MZLDR
 * @subpackage MZLDR/includes
 * @author     Your Name <email@example.com>
 */
class MZLDR_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {
		$mzldr_options = get_option( 'mzldr_settings_options' );
		$mzldr_settings_field_keep_data = isset($mzldr_options['mzldr_settings_field_keep_data']) ? $mzldr_options['mzldr_settings_field_keep_data'] : '';
		$mzldr_settings_field_keep_data = ($mzldr_settings_field_keep_data == '1') ? true : false;

		if ($mzldr_settings_field_keep_data) {
			return false;
		}

		delete_option('mzldr_settings_options');
		delete_option('mzldr_version');

		global $wpdb;
		$table_name = $wpdb->prefix . 'mzldr_loaders';
		$sql        = "DROP TABLE IF EXISTS $table_name";
		$wpdb->query( $sql );
		$table_name = $wpdb->prefix . 'mzldr_impressions';
		$sql        = "DROP TABLE IF EXISTS $table_name";
		$wpdb->query( $sql );
	}

}
