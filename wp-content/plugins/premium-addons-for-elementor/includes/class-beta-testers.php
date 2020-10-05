<?php

/**
 * PA Beta Tester.
 */
namespace PremiumAddons\Includes;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Class Beta_Testers.
 */
class Beta_Testers {

	/**
	 * Class object
	 *
	 * @var instance
	 */
    private static $instance = null;
	
	/**
	 * Transient key
	 *
	 * @var transient_key
	 */
	private $transient_key;

	/**
	 * Construct
	 */
	public function __construct() {
		
		$check_component_active = isset(get_option( 'pa_beta_save_settings' )['is-beta-tester']) ? get_option( 'pa_beta_save_settings' )['is-beta-tester'] : 1;
		
		if ( 0 !== $check_component_active ) {
			return;
		}

		$this->transient_key = md5( 'premium_addons_beta_response_key' );

		add_filter( 'pre_set_site_transient_update_plugins', [ $this, 'compare_version' ] );

	}
    
    /**
     * Get beta version
     * 
     * Checks if the version in trunk is beta
     * 
     * @since 2.1.3
     * @access public
     */
	private function get_beta_version() {
        
		$beta_version = get_site_transient( $this->transient_key );
        
		if ( false === $beta_version ) {
            
			$beta_version = 'false';

			$response = wp_remote_get( 'https://plugins.svn.wordpress.org/premium-addons-for-elementor/trunk/readme.txt' );
            
			if ( ! is_wp_error( $response ) && ! empty( $response['body'] ) ) {
				preg_match( '/Beta tag: (.*)/i', $response['body'], $matches );
				if ( isset( $matches[1] ) ) {
					$beta_version = $matches[1];
				}
			}

			set_site_transient( $this->transient_key, $beta_version, 6 * HOUR_IN_SECONDS );
            
		}

		return $beta_version;
	}

    /**
     * Get version
     * 
     * Checks if the version in trunk is beta
     * 
     * @since 2.1.3
     * @access public
     */
	public function compare_version( $transient ) {
        
		if ( empty( $transient->checked ) ) {
			return $transient;
		}

		delete_site_transient( $this->transient_key );

		$plugin_slug = basename( PREMIUM_ADDONS_FILE , '.php' );

		$beta_version = $this->get_beta_version();
        
		if ( 'false' !== $beta_version && version_compare( $beta_version, PREMIUM_ADDONS_VERSION, '>' ) ) {
            
			$response = new \stdClass();
            
			$response->plugin = $plugin_slug;
            
			$response->slug = $plugin_slug;
            
			$response->new_version = $beta_version;
            
			$response->url = 'https://premiumaddons.com/';
            
			$response->package = sprintf( 'https://downloads.wordpress.org/plugin/premium-addons-for-elementor.%s.zip', $beta_version );
            
            echo $response->package;
            
			$transient->response[ PREMIUM_ADDONS_BASENAME ] = $response;
		}

		return $transient;
	}
	
	/**
     * Creates and returns an instance of the class
     * 
     * @since  2.6.8
     * @access public
     * 
     * @return object
     */
	public static function get_instance() {
		if( self::$instance == null ) {
			self::$instance = new self;
		}
		return self::$instance;
	}
}


if ( ! function_exists( 'premium_beta_tester' ) ) {

	/**
	 * Returns an instance of the plugin class.
	 * @since  2.6.8
	 * @return object
	 */
	function premium_beta_tester() {
		return Beta_Testers::get_instance();
	}
}
premium_beta_tester();