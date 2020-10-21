<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * MAZ Loader Impression Model.
 *
 * @link       https://www.feataholic.com
 * @since      1.1.8 Free
 *
 * @package    MZLDR
 * @subpackage MZLDR/admin
 */

/**
 * MAZ Loader Impression Model.
 *
 * @package    MZLDR
 * @subpackage MZLDR/admin
 * @author     Your Name <email@example.com>
 */
class MZLDR_Impression_Model {

	private $wpdb;

	public function __construct() {
		global $wpdb;

		$this->wpdb = $wpdb;
	}

	/**
	 * Add an impression for the loader
	 *
	 * @param   object $loader
	 *
	 * @return  boolean
	 */
	public function track( $loader ) {
		// $loader_id = isset( $loader[0]->id ) ? $loader[0]->id : '';
		$loader_id = isset( $loader->id ) ? $loader->id : '';

		if ( empty( $loader_id ) ) {
			return false;
		}

		$user_id = get_current_user_id();
		$user_ip = $_SERVER['REMOTE_ADDR'];
		$table   = $this->wpdb->prefix . 'mzldr_impressions';

		$data = array(
			'loader_id'  => $loader_id,
			'user_id'    => $user_id,
			'visitor_ip' => $user_ip,
		);

		$format = array( '%s', '%s', '%s' );

		$this->wpdb->insert( $table, $data, $format );
	}

	/**
	 * Checks if we can track the loader
	 *
	 * @param   object $loader
	 *
	 * @return  void
	 */
	public function checkBeforeTrack( $loader ) {

		$mzldr_options = get_option( 'mzldr_settings_options' );

		$mzldr_settings_field_impressions = isset( $mzldr_options['mzldr_settings_field_impressions'] ) ? $mzldr_options['mzldr_settings_field_impressions'] : '';
		$mzldr_settings_field_impressions = ( $mzldr_settings_field_impressions == 'enable' ) ? true : false;

		if ( $mzldr_settings_field_impressions ) {
			$this->track( $loader );
		}

	}

	/**
	 * Delete all impression of a loader
	 *
	 * @param   integer $loader_id
	 *
	 * @return  integer
	 */
	public function deleteLoaderImpressions( $loader_id ) {
		$this->wpdb->delete(
			"{$this->wpdb->prefix}mzldr_impressions",
			array(
				'loader_id' => $loader_id,
			),
			array(
				'%d',
			)
		);

		if ( $this->wpdb->last_error ) {
			return false;
		}

		return true;
	}

}
