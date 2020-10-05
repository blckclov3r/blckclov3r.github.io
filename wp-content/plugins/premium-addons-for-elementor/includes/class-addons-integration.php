<?php

namespace PremiumAddons;

use PremiumAddons\Admin\Settings\Maps;
use PremiumAddons\Admin\Settings\Modules_Settings;
use PremiumAddons\Helper_Functions;

if( ! defined( 'ABSPATH' ) ) exit();

class Addons_Integration {
    
    //Class instance
    private static $instance = null;
    
    //Modules Keys
    private static $modules = null;
    
    //`premium_Template_Tags` Instance
    protected $templateInstance;


    //Maps Keys
    private static $maps = null;
    
    	/**
	 * Cross-Site CDN URL.
	 *
	 * @since  1.24.1
	 * @var (String) URL
	 */
	public $cdn_url;
    
    /**
    * Initialize integration hooks
    *
    * @return void
    */
    public function __construct() {
        
        self::$modules = Modules_Settings::get_enabled_keys();
        
        self::$maps = Maps::get_enabled_keys();
        
        $this->templateInstance = Includes\premium_Template_Tags::getInstance();
        
        add_action( 'elementor/editor/before_enqueue_styles', array( $this, 'enqueue_editor_styles' ) );
        
        add_action( 'elementor/widgets/widgets_registered', array( $this, 'widgets_area' ) );
        
        add_action( 'elementor/editor/before_enqueue_scripts', array( $this,'enqueue_editor_scripts') );
        
        add_action( 'elementor/preview/enqueue_styles', array( $this, 'enqueue_preview_styles' ) );
        
        add_action( 'elementor/frontend/after_register_styles', array( $this, 'register_frontend_styles' ) );
        
        add_action( 'elementor/frontend/after_register_scripts', array( $this, 'register_frontend_scripts' ) );
        
        add_action( 'wp_ajax_get_elementor_template_content', array( $this, 'get_template_content' ) );

        // $cross_enabled = isset( self::$modules['premium-cross-domain'] ) ? self::$modules['premium-cross-domain'] : 1;

		// if( $cross_enabled ) {

		// 	add_action( 'elementor/editor/before_enqueue_scripts', array( $this, 'enqueue_editor_cp_scripts' ) );
        //     require_once PREMIUM_ADDONS_PATH  . 'includes/class-addons-cross-cp.php';
            
		// }
                    
    }
    
    /**
    * Loads plugin icons font
    * @since 1.0.0
    * @access public
    * @return void
    */
    public function enqueue_editor_styles() {

        $theme =  Helper_Functions::get_elementor_ui_theme();
        
        wp_enqueue_style(
            'pa-editor',
            PREMIUM_ADDONS_URL . 'assets/editor/css/style.css',
            array(),
            PREMIUM_ADDONS_VERSION
        );

        //Enqueue required style for Elementor dark UI Theme
        if( 'dark' === $theme ) {

            wp_enqueue_style(
                'pa-editor-dark',
                PREMIUM_ADDONS_URL . 'assets/editor/css/style-dark.css',
                array(),
                PREMIUM_ADDONS_VERSION
            );

        }
        
        $badge_text = Helper_Functions::get_badge();
        
        $dynamic_css = sprintf( '[class^="pa-"]::after, [class*=" pa-"]::after { content: "%s"; }', $badge_text ) ;

        wp_add_inline_style( 'premium-addons-font',  $dynamic_css );
        
    }
    
    /** 
    * Register Frontend CSS files
    * @since 2.9.0
    * @access public
    */
    public function register_frontend_styles() {
        
        $dir = Helper_Functions::get_styles_dir();
		$suffix = Helper_Functions::get_assets_suffix();
        
        $is_rtl = is_rtl() ? '-rtl' : '';
        
        wp_register_style(
            'pa-prettyphoto',
            PREMIUM_ADDONS_URL . 'assets/frontend/' . $dir . '/prettyphoto' . $is_rtl . $suffix . '.css',
            array(),
            PREMIUM_ADDONS_VERSION,
            'all'
        );
        
        wp_register_style(
            'premium-addons',
            PREMIUM_ADDONS_URL . 'assets/frontend/' . $dir . '/premium-addons' . $is_rtl . $suffix . '.css',
            array(),
            PREMIUM_ADDONS_VERSION,
            'all'
        );
        
    }
    
    /**
     * Enqueue Preview CSS files
     * 
     * @since 2.9.0
     * @access public
     * 
     */
    public function enqueue_preview_styles() {
        
        wp_enqueue_style( 'pa-prettyphoto' );
        
        wp_enqueue_style( 'premium-addons' );

    }
    
    /** 
     * Load widgets require function
     * 
     * @since 1.0.0
     * @access public
     * 
     */
    public function widgets_area() {
        $this->widgets_register();
    }
    
    /**
     * Requires widgets files
     * 
     * @since 1.0.0
     * @access private
     */
    private function widgets_register() {

        $check_component_active = self::$modules;
        
        foreach ( glob( PREMIUM_ADDONS_PATH . 'widgets/' . '*.php' ) as $file ) {
            
            $slug = basename( $file, '.php' );
            
            $enabled = isset( $check_component_active[ $slug ] ) ? $check_component_active[ $slug ] : '';
            
            if ( filter_var( $enabled, FILTER_VALIDATE_BOOLEAN ) || ! $check_component_active ) {
                $this->register_addon( $file );
            }
        }

    }
    
    /**
     * Registers required JS files
     * 
     * @since 1.0.0
     * @access public
    */
    public function register_frontend_scripts() {
        
        $maps_settings = self::$maps;
        
        $dir = Helper_Functions::get_scripts_dir();
		$suffix = Helper_Functions::get_assets_suffix();
        
        $locale = isset ( $maps_settings['premium-map-locale'] ) ? $maps_settings['premium-map-locale'] : "en";
        
        wp_register_script(
            'premium-addons-js',
            PREMIUM_ADDONS_URL . 'assets/frontend/' . $dir . '/premium-addons' . $suffix . '.js',
            array('jquery'),
            PREMIUM_ADDONS_VERSION,
            true
        );
        
        wp_register_script(
            'prettyPhoto-js',
            PREMIUM_ADDONS_URL . 'assets/frontend/' . $dir . '/prettyPhoto' . $suffix . '.js',
            array('jquery'),
            PREMIUM_ADDONS_VERSION,
            true
        );
        
        wp_register_script(
            'vticker-js',
            PREMIUM_ADDONS_URL . 'assets/frontend/' . $dir . '/vticker' . $suffix . '.js',
            array('jquery'),
            PREMIUM_ADDONS_VERSION,
            true
        );
        wp_register_script(
            'typed-js',
            PREMIUM_ADDONS_URL . 'assets/frontend/' . $dir . '/typed' . $suffix . '.js',
            array('jquery'),
            PREMIUM_ADDONS_VERSION,
            true
        );
        
        wp_register_script(
            'count-down-timer-js',
            PREMIUM_ADDONS_URL . 'assets/frontend/' . $dir . '/jquery-countdown' . $suffix . '.js',
            array('jquery'),
            PREMIUM_ADDONS_VERSION,
            true
        );
       
        wp_register_script(
            'isotope-js',
            PREMIUM_ADDONS_URL . 'assets/frontend/' . $dir . '/isotope' . $suffix . '.js',
            array('jquery'),
            PREMIUM_ADDONS_VERSION,
            true
        );

        wp_register_script(
            'modal-js',
            PREMIUM_ADDONS_URL . 'assets/frontend/' . $dir . '/modal' . $suffix . '.js',
            array('jquery'),
            PREMIUM_ADDONS_VERSION,
            true
        );
        
        wp_register_script(
            'premium-maps-js',
            PREMIUM_ADDONS_URL . 'assets/frontend/' . $dir . '/premium-maps' . $suffix . '.js',
            array( 'jquery', 'premium-maps-api-js' ),
            PREMIUM_ADDONS_VERSION,
            true
        );

        wp_register_script( 
            'vscroll-js',
            PREMIUM_ADDONS_URL . 'assets/frontend/' . $dir . '/premium-vscroll' . $suffix . '.js',
            array('jquery'),
            PREMIUM_ADDONS_VERSION,
            true
        );
        
       wp_register_script( 
           'slimscroll-js',
           PREMIUM_ADDONS_URL . 'assets/frontend/' . $dir . '/jquery-slimscroll' . $suffix . '.js',
           array('jquery'),
           PREMIUM_ADDONS_VERSION,
           true
       );
       
       wp_register_script( 
           'iscroll-js',
           PREMIUM_ADDONS_URL . 'assets/frontend/' . $dir . '/iscroll' . $suffix . '.js',
           array('jquery'),
           PREMIUM_ADDONS_VERSION,
           true
       );
       
       wp_register_script(
            'tilt-js',
            PREMIUM_ADDONS_URL . 'assets/frontend/' . $dir . '/universal-tilt' . $suffix . '.js',
            array( 'jquery' ), 
            PREMIUM_ADDONS_VERSION, 
            true
        );

        wp_register_script(
            'lottie-js',
            PREMIUM_ADDONS_URL . 'assets/frontend/' . $dir . '/lottie' . $suffix . '.js',
            array( 'jquery' ), 
            PREMIUM_ADDONS_VERSION, 
            true
        );

       if( $maps_settings['premium-map-cluster'] ) {
            wp_register_script(
                'google-maps-cluster',
                'https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/markerclusterer.js',
                array(),
                PREMIUM_ADDONS_VERSION,
                false
            );
        }
        
        if( $maps_settings['premium-map-disable-api'] && '1' != $maps_settings['premium-map-api'] ) {
			$api = sprintf ( 'https://maps.googleapis.com/maps/api/js?key=%1$s&language=%2$s', $maps_settings['premium-map-api'], $locale );
            wp_register_script(
                'premium-maps-api-js',
                $api,
                array(),
                PREMIUM_ADDONS_VERSION,
                false
            );
        }
        
        $data = array(
            'ajaxurl'       => esc_url( admin_url( 'admin-ajax.php' ) )
        );
        
		wp_localize_script( 'premium-addons-js', 'PremiumSettings', $data );
        
    }
    
    /*
     * Enqueue editor scripts
     * 
     * @since 3.2.5
     * @access public
     */
    public function enqueue_editor_scripts() {
		
		$map_enabled = isset( self::$modules['premium-maps'] ) ? self::$modules['premium-maps'] : 1;
        
		if( $map_enabled ) {
		
        	$premium_maps_api = self::$maps['premium-map-api'];
        
        	$locale = isset ( self::$maps['premium-map-locale'] ) ? self::$maps['premium-map-locale'] : "en";

        	$premium_maps_disable_api = self::$maps['premium-map-disable-api'];
        
        	if ( $premium_maps_disable_api && '1' != $premium_maps_api ) {
                
				$api = sprintf ( 'https://maps.googleapis.com/maps/api/js?key=%1$s&language=%2$s', $premium_maps_api, $locale );
            	wp_enqueue_script(
                	'premium-maps-api-js',
                	$api,
                	array(),
                	PREMIUM_ADDONS_VERSION,
                	false
            	);

        	}

			wp_enqueue_script(
				'pa-maps-finder',
				PREMIUM_ADDONS_URL . 'assets/editor/js/pa-maps-finder.js',
				array( 'jquery' ),
				PREMIUM_ADDONS_VERSION,
				true
			);

        }

    }

    /**
	* Load Cross Domain Copy Paste JS Files.
	*
	* @since 3.21.1
	*/

	public function enqueue_editor_cp_scripts() {

		$dir = Helper_Functions::get_scripts_dir();
        $suffix = Helper_Functions::get_assets_suffix();
        
		wp_enqueue_script(
			'premium-xdlocalstorage-js',
			PREMIUM_ADDONS_URL . 'assets/editor/js/xdlocalstorage.js',
			null,
            PREMIUM_ADDONS_VERSION,
			true
		);

		wp_enqueue_script(
			'premium-cross-cp',
			PREMIUM_ADDONS_URL . 'assets/editor/js/premium-cross-cp.js',
			array( 'jquery', 'elementor-editor', 'premium-xdlocalstorage-js' ),
			PREMIUM_ADDONS_VERSION,
			true
        );
        
		wp_localize_script(
		'jquery',
			'premium_cross_cp',
			array(
				'ajax_url' => admin_url( 'admin-ajax.php' ),
				'nonce'    => wp_create_nonce( 'premium_cross_cp_import' ),
			)
		);
	}
    
    /*
     * Get Template Content
     * 
     * Get Elementor template HTML content.
     * 
     * @since 3.2.6
     * @access public
     * 
     */
    public function get_template_content() {
        
        $template = $_GET['templateID'];
        
        if( ! isset( $template ) ) {
            return;
        }
        
        $template_content = $this->templateInstance->get_template_content( $template );
        
        if ( empty ( $template_content ) || ! isset( $template_content ) ) {
            wp_send_json_error();
        }
        
        $data = array(
            'template_content'  => $template_content
        );
        
        wp_send_json_success( $data );
        
    }
    
    /**
     * 
     * Register addon by file name.
     * 
     * @access public
     *
     * @param  string $file            File name.
     * @param  object $widgets_manager Widgets manager instance.
     * 
     * @return void
     */
    public function register_addon( $file ) {
        
        $widget_manager = \Elementor\Plugin::instance()->widgets_manager;
        
        $base  = basename( str_replace( '.php', '', $file ) );
        $class = ucwords( str_replace( '-', ' ', $base ) );
        $class = str_replace( ' ', '_', $class );
        $class = sprintf( 'PremiumAddons\Widgets\%s', $class );
        
        if( 'PremiumAddons\Widgets\Premium_Contactform' != $class ) {
            require $file;
        } else {
            if( function_exists('wpcf7') ) {
                require $file;
            }
        }
        
        if ( 'PremiumAddons\Widgets\Premium_Blog' == $class ) {
            require_once ( PREMIUM_ADDONS_PATH . 'widgets/dep/queries.php' );
        }

        if ( class_exists( $class ) ) {
            $widget_manager->register_widget_type( new $class );
        }
    }
    
    /**
     * 
     * Creates and returns an instance of the class
     * 
     * @since 1.0.0
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
    

if ( ! function_exists( 'premium_addons_integration' ) ) {

	/**
	 * Returns an instance of the plugin class.
	 * @since  1.0.0
	 * @return object
	 */
	function premium_addons_integration() {
		return Addons_Integration::get_instance();
	}
}
premium_addons_integration();
