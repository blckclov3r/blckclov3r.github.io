<?php

/**
 * PA Category Manager.
 */
namespace PremiumAddons\Includes;

use PremiumAddons\Helper_Functions;

if( ! defined( 'ABSPATH' ) ) exit();

/**
 * Class Premium_Addons_Category.
 */
class Addons_Category {
    
    /**
	 * Class object
	 *
	 * @var instance
	 */
    private static $instance = null;
    
    public function __construct() {
        $this->create_premium_category();
    }
    
    /*
     * Create Premium Addons Category
     * 
     * Adds category `Premium Addons` in the editor panel.
     * 
     * @access public
     * 
     */
    public function create_premium_category() {
        \Elementor\Plugin::instance()->elements_manager->add_category(
            'premium-elements',
            array(
                'title' => Helper_Functions::get_category()
            ),
        1);
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
    

if ( ! function_exists( 'premium_addons_category' ) ) {

	/**
	 * Returns an instance of the plugin class.
	 * @since  2.6.8
	 * @return object
	 */
	function premium_addons_category() {
		return Addons_Category::get_instance();
	}
}
premium_addons_category();