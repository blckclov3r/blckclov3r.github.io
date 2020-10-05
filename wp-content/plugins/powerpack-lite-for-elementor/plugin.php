<?php
namespace PowerpackElementsLite;

use Elementor\Utils;
use PowerpackElementsLite\Classes\PP_Config;

if ( ! defined( 'ABSPATH' ) ) {	exit; } // Exit if accessed directly

/**
 * Main class plugin
 */
class PowerpackLitePlugin {

	/**
	 * @var Plugin
	 */
	private static $_instance;

	/**
	 * @var Manager
	 */
	private $_extensions_manager;

	/**
	 * @var Manager
	 */
	private $_modules_manager;

	/**
	 * @var array
	 */
	private $_localize_settings = [];

	/**
	 * @return string
	 */
	public function get_version() {
		return POWERPACK_ELEMENTS_LITE_VER;
	}

	/**
	 * Throw error on object clone
	 *
	 * The whole idea of the singleton design pattern is that there is a single
	 * object therefore, we don't want the object to be cloned.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function __clone() {
		// Cloning instances of the class is forbidden
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'powerpack' ), '1.0.0' );
	}

	/**
	 * Disable unserializing of the class
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function __wakeup() {
		// Unserializing instances of the class is forbidden
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'powerpack' ), '1.0.0' );
	}

	/**
	 * @return Plugin
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	private function _includes() {
		require POWERPACK_ELEMENTS_LITE_PATH . 'includes/extensions-manager.php';
		require POWERPACK_ELEMENTS_LITE_PATH . 'includes/modules-manager.php';
	}

	public function autoload( $class ) {
		if ( 0 !== strpos( $class, __NAMESPACE__ ) ) {
			return;
		}

		$filename = strtolower(
			preg_replace(
				[ '/^' . __NAMESPACE__ . '\\\/', '/([a-z])([A-Z])/', '/_/', '/\\\/' ],
				[ '', '$1-$2', '-', DIRECTORY_SEPARATOR ],
				$class
			)
		);
		$filename = POWERPACK_ELEMENTS_LITE_PATH . $filename . '.php';

		if ( is_readable( $filename ) ) {
			include( $filename );
		}
	}

	public function get_localize_settings() {
		return $this->_localize_settings;
	}

	public function add_localize_settings( $setting_key, $setting_value = null ) {
		if ( is_array( $setting_key ) ) {
			$this->_localize_settings = array_replace_recursive( $this->_localize_settings, $setting_key );

			return;
		}

		if ( ! is_array( $setting_value ) || ! isset( $this->_localize_settings[ $setting_key ] ) || ! is_array( $this->_localize_settings[ $setting_key ] ) ) {
			$this->_localize_settings[ $setting_key ] = $setting_value;

			return;
		}

		$this->_localize_settings[ $setting_key ] = array_replace_recursive( $this->_localize_settings[ $setting_key ], $setting_value );
	}

    /**
	 * Enqueue frontend styles
	 *
	 * @since 1.3.3
	 *
	 * @access public
	 */
	public function enqueue_frontend_styles() {
		wp_enqueue_style(
			'powerpack-frontend',
			POWERPACK_ELEMENTS_LITE_URL . 'assets/css/frontend.css',
			[],
			POWERPACK_ELEMENTS_LITE_VER
		);
        
		wp_register_style(
			'odometer',
			POWERPACK_ELEMENTS_LITE_URL . 'assets/lib/odometer/odometer-theme-default.css',
			[],
			POWERPACK_ELEMENTS_LITE_VER
		);
        
		wp_register_style(
			'twentytwenty',
			POWERPACK_ELEMENTS_LITE_URL . 'assets/lib/twentytwenty/twentytwenty.css',
			[],
			POWERPACK_ELEMENTS_LITE_VER
		);
        
        if ( class_exists( 'GFCommon' ) ) {
			$gf_forms = \RGFormsModel::get_forms( null, 'title' );
			foreach ( $gf_forms as $form ) {
				if ( '0' !== $form->id ) {
					wp_enqueue_script( 'gform_gravityforms' );
					gravity_form_enqueue_scripts( $form->id );
				}
			}
		}

        if ( function_exists( 'wpforms' ) ) {
            wpforms()->frontend->assets_css();
        }
	}

    /**
	 * Enqueue frontend scripts
	 *
	 * @since 1.3.3
	 *
	 * @access public
	 */
	public function enqueue_frontend_scripts() {
        $settings = \PowerpackElementsLite\Classes\PP_Admin_Settings::get_settings();
		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		wp_register_script(
			'instafeed',
			POWERPACK_ELEMENTS_LITE_URL . 'assets/lib/instafeed/instafeed' . $suffix . '.js',
			[
				'jquery',
			],
			'1.4.1',
			true
		);

		wp_register_script(
			'pp-instagram',
			POWERPACK_ELEMENTS_LITE_URL . 'assets/js/pp-instagram.js',
			[
				'jquery',
			],
			POWERPACK_ELEMENTS_LITE_VER,
			true
		);

		wp_register_script(
			'twentytwenty',
			POWERPACK_ELEMENTS_LITE_URL . 'assets/lib/twentytwenty/jquery.twentytwenty.js',
			[
				'jquery',
			],
			'2.0.0',
			true
		);

		wp_register_script(
			'jquery-event-move',
			POWERPACK_ELEMENTS_LITE_URL . 'assets/js/jquery.event.move.js',
			[
				'jquery',
			],
			'2.0.0',
			true
		);

		wp_register_script(
			'magnific-popup',
			POWERPACK_ELEMENTS_LITE_URL . 'assets/lib/magnific-popup/jquery.magnific-popup.min.js',
			[
				'jquery',
			],
			'2.2.1',
			true
		);

		wp_register_script(
			'waypoints',
			POWERPACK_ELEMENTS_LITE_URL . 'assets/lib/waypoints/waypoints.min.js',
			[
				'jquery',
			],
			'4.0.1',
			true
		);

		wp_register_script(
			'odometer',
			POWERPACK_ELEMENTS_LITE_URL . 'assets/lib/odometer/odometer.min.js',
			[
				'jquery',
			],
			'0.4.8',
			true
		);

		wp_register_script(
			'pp-jquery-plugin',
			POWERPACK_ELEMENTS_LITE_URL . 'assets/js/jquery.plugin.js',
			[
				'jquery',
			],
			'1.0.0',
			true
		);

		wp_register_script(
			'twitter-widgets',
			POWERPACK_ELEMENTS_LITE_URL . 'assets/js/twitter-widgets.js',
			[
				'jquery',
			],
			'1.0.0',
			true
		);

		wp_register_script(
			'powerpack-pp-posts',
			POWERPACK_ELEMENTS_LITE_URL . 'assets/js/pp-posts.js',
			array(
				'jquery',
			),
			POWERPACK_ELEMENTS_LITE_VER,
			true
		);

		wp_localize_script(
			'powerpack-pp-posts',
			'pp_posts_script',
			array(
				'ajax_url'    => admin_url( 'admin-ajax.php' ),
				'posts_nonce' => wp_create_nonce( 'pp-posts-widget-nonce' ),
			)
		);

		wp_register_script(
			'pp-tooltip',
			POWERPACK_ELEMENTS_LITE_URL . 'assets/js/tooltip.js',
			[
				'jquery',
			],
			POWERPACK_ELEMENTS_LITE_VER,
			true
		);

		wp_register_script(
			'powerpack-frontend',
			POWERPACK_ELEMENTS_LITE_URL . 'assets/js/frontend.js',
			[
				'jquery',
			],
			POWERPACK_ELEMENTS_LITE_VER,
			true
		);

		$pp_localize = apply_filters(
			'pp_elements_lite_js_localize',
			array(
				'ajax_url' => admin_url( 'admin-ajax.php' ),
			)
		);
		wp_localize_script( 'jquery', 'pp', $pp_localize );
	}

    /**
	 * Enqueue editor styles
	 *
	 * @since 1.3.3
	 *
	 * @access public
	 */
	public function enqueue_editor_styles() {
		wp_enqueue_style(
			'powerpack-editor',
			POWERPACK_ELEMENTS_LITE_URL . 'assets/css/editor.css',
			[],
			POWERPACK_ELEMENTS_LITE_VER
		);
        
		wp_enqueue_style(
			'powerpack-icons',
			POWERPACK_ELEMENTS_LITE_URL . 'assets/lib/ppicons/css/powerpack-icons.css',
			[],
			POWERPACK_ELEMENTS_LITE_VER
		);
	}

    /**
	 * Enqueue editor scripts
	 *
	 * @since 1.3.3
	 *
	 * @access public
	 */
	public function enqueue_editor_scripts() {
		wp_enqueue_script(
			'powerpack-editor',
			POWERPACK_ELEMENTS_LITE_URL . 'assets/js/editor.js',
			[
				'jquery',
			],
			POWERPACK_ELEMENTS_LITE_VER,
			true
		);
        
		wp_enqueue_script(
			'magnific-popup',
			POWERPACK_ELEMENTS_LITE_URL . 'assets/lib/magnific-popup/jquery.magnific-popup.min.js',
			[
				'jquery',
			],
			'2.2.1',
			true
		);
	}

	public function enqueue_panel_scripts() {}

	public function enqueue_editor_preview_styles() {
		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
		
		wp_enqueue_style( 'odometer' );
		wp_enqueue_style( 'twentytwenty' );
	}

	/**
	 * Register Group Controls
	 *
	 * @since 1.2.9
	 */
	public function include_group_controls() {
		// Include Control Groups
		require POWERPACK_ELEMENTS_LITE_PATH . 'includes/controls/groups/transition.php';

		// Add Control Groups
		\Elementor\Plugin::instance()->controls_manager->add_group_control( 'pp-transition', new Group_Control_Transition() );
	}

	/**
	 * Register Controls
	 *
	 * @since 1.2.9
	 *
	 * @access private
	 */
	public function register_controls() {

		// Include Controls
		require POWERPACK_ELEMENTS_LITE_PATH . 'includes/controls/query.php';

		// Register Controls
		\Elementor\Plugin::instance()->controls_manager->register_control( 'pp-query', new Control_Query() );
	}

	public function elementor_init() {
		$this->_extensions_manager = new Extensions_Manager();
		$this->_modules_manager = new Modules_Manager();

		// Add element category in panel
		\Elementor\Plugin::instance()->elements_manager->add_category(
			'powerpack-elements', // This is the name of your addon's category and will be used to group your widgets/elements in the Edit sidebar pane!
			[
				'title' => __( 'PowerPack Elements', 'powerpack' ), // The title of your modules category - keep it simple and short!
				'icon' => 'font',
			],
			1
		);
	}

	public function get_promotion_widgets($config) {

        if (is_pp_elements_active()) {
            return $config;
        }

        $promotion_widgets = [];

        if (isset($config['promotionWidgets'])) {
            $promotion_widgets = $config['promotionWidgets'];
        }

		$pro_widgets = PP_Config::get_pro_widgets();

        $combine_array = array_merge($promotion_widgets, $pro_widgets);

        $config['promotionWidgets'] = $combine_array;

        return $config;
    }

	protected function add_actions() {
		add_action( 'elementor/init', [ $this, 'elementor_init' ] );

		add_action( 'elementor/controls/controls_registered', [ $this, 'register_controls' ] );
		add_action( 'elementor/controls/controls_registered', [ $this, 'include_group_controls' ] );

		add_action( 'elementor/editor/after_enqueue_scripts', [ $this, 'enqueue_editor_scripts' ] );
        add_action( 'elementor/editor/after_enqueue_styles', [ $this, 'enqueue_editor_styles' ] );

        add_action( 'elementor/preview/enqueue_styles', [ $this, 'enqueue_editor_preview_styles' ] );

		add_action( 'elementor/frontend/after_register_scripts', [ $this, 'enqueue_frontend_scripts' ] );
		add_action( 'elementor/frontend/after_enqueue_styles', [ $this, 'enqueue_frontend_styles' ] );

		add_filter('elementor/editor/localize_settings', [$this, 'get_promotion_widgets']);
	}

	/**
	 * Plugin constructor.
	 */
	private function __construct() {
		spl_autoload_register( [ $this, 'autoload' ] );

		$this->_includes();
		$this->add_actions();
		Classes\UsageTracking::get_instance();
	}

}

if ( ! defined( 'POWERPACK_ELEMENTS_TESTS' ) ) {
	// In tests we run the instance manually.
	PowerpackLitePlugin::instance();
}
