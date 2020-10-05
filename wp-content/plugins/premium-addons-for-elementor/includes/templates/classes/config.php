<?php

namespace PremiumAddons\Includes\Templates\Classes;

use PremiumAddons\Helper_Functions;
use PremiumAddonsPro\License\Admin;


if( ! defined( 'ABSPATH' ) ) exit; // No access of directly access

if( ! class_exists('Premium_Templates_Core_Config') ) {
    
    /**
     * Premium Templates Core config.
     *
     * Templates core class is responsible for handling templates library.
     *
     * @since 3.6.0
     * 
     */
    class Premium_Templates_Core_Config {
        
        /*
         * Instance of the class
         * 
         * @access private
         * @since 3.6.0
         * 
         */
        private static $instance = null;
        
        /*
         * Holds config data
         * 
         * @access private
         * @since 3.6.0
         * 
         */
        private $config;
        
        /*
         * License page slug
         * 
         * @access private
         * @since 3.6.0
         * 
         */
        private $slug = 'premium-addons-pro-license';
    
        /**
        * Premium_Templates_Core_Config constructor.
        *
        * Sets config data.
        *
        * @since 3.6.0
        * @access public
        */
        public function __construct() {
            
            $this->config = array(
                'premium_temps'     => __('Premium Templates', 'premium-addons-for-elementor'),
                'key'               => $this->get_license_key(),
                'status'            => $this->get_license_status(),
                'license_page'      => $this->get_license_page(),
                'pro_message'       => $this->get_pro_message(),
                'api'               => array(
					'enabled'   => true,
					'base'      => 'https://pa.premiumtemplates.io/',
					'path'      => 'wp-json/patemp/v2',
					'id'        => 9,
					'endpoints' => array(
						'templates'  => '/templates/',
						'keywords'   => '/keywords/',
						'categories' => '/categories/',
                        'template'   => '/template/',
						'info'       => '/info/',
						'template'   => '/template/',
					),
				),
            );
            
        }
        
        /**
         * Get license key.
         *
         * Gets Premium Add-ons PRO license key.
         *
         * @since 3.6.0
         * @access public
         * 
         * @return string|boolean license key or false if no license key 
         */
        public function get_license_key() {
            
            if( ! defined ('PREMIUM_PRO_ADDONS_VERSION') ) {
                return;
            }
            
            $key = Admin::get_license_key();
            
            return $key;
            
        }

        /**
         * Get license status.
         *
         * Gets Premium Add-ons PRO license status.
         *
         * @since 3.6.0
         * @access public
         * 
         * @return string|boolean license status or false if no license key 
         */
        public function get_license_status() {
            
            if( ! defined ('PREMIUM_PRO_ADDONS_VERSION') ) {
                return;
            }
            
            $status = Admin::get_license_status();
            
            return $status;
            
        }
        
        /**
         * Get license page.
         *
         * Gets Premium Add-ons PRO license page.
         *
         * @since 3.6.0
         * @access public
         * 
         * @return string admin license page or plugin URI
         */
        public function get_license_page() {
            
            if( defined ('PREMIUM_PRO_ADDONS_VERSION') ) {
                
                return add_query_arg(
                    array(
                        'page'  => $this->slug,
                    ),
                    esc_url( admin_url('admin.php') )
                );
                
            } else {
                
                $theme_slug = Helper_Functions::get_installed_theme();
                
                $url = sprintf('https://premiumaddons.com/pro/?utm_source=premium-templates&utm_medium=wp-dash&utm_campaign=get-pro&utm_term=%s', $theme_slug);
                
                return $url;
                
            }
             
        }
        
        /**
         * 
         * Get License Message
         * 
         * @since 3.6.0
         * @access public
         * 
         * @return string Pro version message
         */
        public function get_pro_message() {
            
            if( defined ('PREMIUM_PRO_ADDONS_VERSION') ) {
                return __('Activate License', 'premium-addons-for-elementor');
            } else {
                return __('Get Pro', 'premium-addons-for-elementor');
            }
            
        }


        
        /**
         * Get
         *
         * Gets a segment of config data.
         *
         * @since 3.6.0
         * @access public
         * 
         * @return string|array|false data or false if not set
         */
        public function get( $key = '' ) {
            
			return isset( $this->config[ $key ] ) ? $this->config[ $key ] : false;
            
		}
        
        
        /**
         * Creates and returns an instance of the class
         * 
         * @since 3.6.0
         * @access public
         * 
         * @return object
         * 
         */
        public static function get_instance() {
            
            if( self::$instance == null ) {
                
                self::$instance = new self;
                
            }
            
            return self::$instance;
            
        }
            
        
    }   
    
}
