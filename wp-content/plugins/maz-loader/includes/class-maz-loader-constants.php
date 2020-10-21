<?php

/**
 * MAZ Loader Constants
 *
 * @link       https://www.feataholic.com
 * @since      1.1.8 Free
 * @author     Feataholic <support@feataholic.com>
 *
 * @package    MZLDR
 * @subpackage MZLDR/includes
 */
class MZLDR_Constants {

	/**
	 * Feataholic URL
	 *
	 * @var string
	 */
	private static $FA_URL = 'https://www.feataholic.com/';

	/**
	 * Base URL
	 *
	 * @var string
	 */
	private static $BASE_URL = 'https://www.feataholic.com/downloads/maz-loader/';

	/**
	 * Returns the documentation URL
	 *
	 * @return string
	 */
	public static function getDocumentationURL() {
		return self::$FA_URL . 'wordpress-plugins/maz-loader/documentation/';
	}

	/**
	 * Returns the Support URL
	 *
	 * @return string
	 */
	public static function getSupportURL() {
		return self::$FA_URL . 'contact/';
	}

	/**
	 * Returns the Plugin Page URL
	 *
	 * @return string
	 */
	public static function getPluginPageURL() {
		return self::$BASE_URL;
	}

	/**
	 * Returns the Upgrade URL
	 *
	 * @return string
	 */
	public static function getUpgradeURL() {
		return self::$FA_URL . '?edd_action=add_to_cart&download_id=1048&discount=PROUPGRADE30OFF';
	}

	/**
	 * Returns the Review URL
	 *
	 * @return string
	 */
	public static function getReviewURL() {
		return 'https://wordpress.org/support/plugin/maz-loader/reviews/#new-post';
	}

	/**
	 * Returns the Settings URL
	 *
	 * @return tring
	 */
	public static function getSettingsURL() {
		return admin_url( 'admin.php?page=maz-loader-settings' );
	}

	/**
	 * Returns the Create New Loader URL
	 *
	 * @return tring
	 */
	public static function getCreateNewLoaderURL() {
		return admin_url( 'admin.php?page=maz-loader' );
	}


}
