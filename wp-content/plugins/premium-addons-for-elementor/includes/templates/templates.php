<?php

namespace PremiumAddons\Includes\Templates;

if ( ! defined('ABSPATH') ) exit;

// If class `Premium_Templates` not created.
if ( ! class_exists('Premium_Templates') ) {
    
    /**
	 * Sets up and initializes the plugin.
	 */
    class Premium_Templates {
        
        /*
         * Instance of the class
         * 
         * @access private
         * @since 3.6.0
         * 
         */
        private static $instance = null;
        
        /*
         * Holds API data
         * 
         * @access public
         * @since 3.6.0
         * 
         */
        public $api;
        
        /*
         * Holds templates configuration data
         * 
         * @access public
         * @since 3.6.0
         * 
         */
        public $config;
        
        /*
         * Holds templates assets
         * 
         * @access public
         * @since 3.6.0
         * 
         */
        public $assets;
        
        /*
         * Templates Manager
         * 
         * @access public
         * @since 3.6.0
         * 
         */
        public $temp_manager;
        
        /*
         * Holds templates types
         * 
         * @access public
         * @since 3.6.0
         * 
         */
        public $types;

        
        /*
         * Construct
         * 
         * Class Constructor
         * 
         * @since 3.6.0
         * @access public
         * 
         */
        public function __construct() {
            
            add_action( 'init', array( $this, 'init' ) );
            
            
        }
        
        /**
         * Init Premium Templates
         * 
         * @since 3.6.0
         * @access public
         * 
         * @return void
        */
        public function init() {
            
            $this->load_files();
            
            $this->set_config();
            
            $this->set_assets();

            $this->set_api();
            
            $this->set_types();
                
            $this->set_templates_manager();
            
        }
        
        /**
         * Load required files for premium templates
         * 
         * @since 3.6.0
         * @access private
         * 
         * @return void
        */
        private function load_files() {
            
            require PREMIUM_ADDONS_PATH . 'includes/templates/classes/config.php';
            
            require PREMIUM_ADDONS_PATH . 'includes/templates/classes/assets.php';
            
            require PREMIUM_ADDONS_PATH . 'includes/templates/classes/manager.php';

            require PREMIUM_ADDONS_PATH . 'includes/templates/types/manager.php';

            require PREMIUM_ADDONS_PATH . 'includes/templates/classes/api.php';
            
        }
        
        /**
         * Init `Premium_Templates_Core_Config`
         * 
         * @since 3.6.0
         * @access private
         * 
         * @return void
        */
        private function set_config() {
            
            $this->config       = new Classes\Premium_Templates_Core_Config();
            
        }
        
        /**
         * Init `Premium_Templates_Assets`
         * 
         * @since 3.6.0
         * @access private
         * 
         * @return void
        */
        private function set_assets() {
            
            $this->assets       = new Classes\Premium_Templates_Assets();
            
        }
        
        /**
         * Init `Premium_Templates_API`
         * 
         * @since 3.6.0
         * @access private
         * 
         * @return void
        */
        private function set_api() {
            
            $this->api       = new Classes\Premium_Templates_API();
            
        }
        
        /**
         * Init `Premium_Templates_Types`
         * 
         * @since 3.6.0
         * @access private
         * 
         * @return void
        */
        private function set_types() {
            
            $this->types        = new Types\Premium_Templates_Types();
            
        }
        
        /**
         * Init `Premium_Templates_Manager`
         * 
         * @since 3.6.0
         * @access private
         * 
         * @return void
        */
        private function set_templates_manager() {
            
            $this->temp_manager = new Classes\Premium_Templates_Manager();
            
        }

        /**
         * Get instance
         * 
         * Creates and returns an instance of the class
         * 
         * @since 0.0.1
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

if ( ! function_exists('premium_templates') ) {
    
    /**
    * Triggers `get_instance` method
    * @since 3.6.0
    * @access public
    * return object
    */
    function premium_templates() {
       
        return Premium_Templates::get_instance();
        
    }
    
}
premium_templates();