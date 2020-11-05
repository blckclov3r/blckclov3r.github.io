<?php

/**
 * Fired during plugin activation
 *
 * @link       https://www.feataholic.com
 * @since      1.1.8 Free
 *
 * @package    MZLDR
 * @subpackage MZLDR/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.1.8 Free
 * @package    MZLDR
 * @subpackage MZLDR/includes
 * @author     Your Name <email@example.com>
 */
class MZLDR_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		global $table_prefix, $wpdb;

		

		if( !get_option( 'mzldr_settings_options' ) ){
			// init settings options
			$settings = array(
				'mzldr_settings_field_impressions' => 'enable',
				
				'mzldr_settings_field_loader_display' => 'enable',
				
				'mzldr_settings_field_keep_data' => '1'
			);
			update_option( 'mzldr_settings_options', $settings );
		}

		// set version
		update_option( 'mzldr_version', (string) MZLDR_Helper::getVersion() );
		
		// import tables
		$charset_collate = $wpdb->get_charset_collate();

		$tblname        = 'mzldr_loaders';
		$loaders_table = $table_prefix . $tblname;

		$tblname2        = 'mzldr_impressions';
		$impressions_table = $table_prefix . $tblname2;

		// Check to see if the table exists already, if not, then create it
		if ( $wpdb->get_var( "show tables like '$loaders_table'" ) != $loaders_table &&
 			 $wpdb->get_var( "show tables like '$impressions_table'" ) != $impressions_table) {

			require_once( ABSPATH . '/wp-admin/includes/upgrade.php' );

			$sql = '
			CREATE TABLE ' . $loaders_table . " (
				`id` int(11) NOT NULL AUTO_INCREMENT,
				`user_id` int(11) NOT NULL,
				`name` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
				`data` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
				`published` tinyint(1) NOT NULL DEFAULT '0',
				`created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
				`modified_at` datetime DEFAULT NULL,
				PRIMARY KEY (`id`)
			   ) " . $charset_collate;
			dbDelta( $sql );

			$sql = '
			CREATE TABLE ' . $impressions_table . " (
				`id` int(11) NOT NULL AUTO_INCREMENT,
				`loader_id` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
				`user_id` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
				`visitor_ip` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
				`date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
				PRIMARY KEY (`id`)
			) " . $charset_collate;
			dbDelta( $sql );
		}
	}

}
