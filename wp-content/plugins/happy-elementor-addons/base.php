<?php
/**
 * Plugin base class
 *
 * @package Happy_Addons
 */
namespace Happy_Addons\Elementor;

use Elementor\Controls_Manager;
use Elementor\Elements_Manager;

defined( 'ABSPATH' ) || die();

class Base {

    private static $instance = null;

    public $appsero = null;

    public static function instance() {
        if ( is_null( self::$instance ) ) {
            self::$instance = new self();
            self::$instance->init();
        }
        return self::$instance;
    }

    private function __construct() {
        add_action( 'init', [ $this, 'i18n' ] );
    }

    public function i18n() {
        load_plugin_textdomain( 'happy-elementor-addons' );
    }

    public function init() {
        $this->include_files();

        // Register custom category
        add_action( 'elementor/elements/categories_registered', [ $this, 'add_category' ] );

        // Register custom controls
		add_action( 'elementor/controls/controls_registered', [ $this, 'register_controls' ] );

		add_action( 'init', [ $this, 'include_on_init' ] );

		$this->init_appsero_tracking();

		do_action( 'happyaddons_loaded' );
	}

    /**
     * Initialize the tracker
     *
     * @return void
     */
    protected function init_appsero_tracking() {
        if ( ! class_exists( 'Happy_Addons\Appsero\Client' ) ) {
            include_once HAPPY_ADDONS_DIR_PATH . 'vendor/appsero/src/Client.php';
        }

        $this->appsero = new \Happy_Addons\Appsero\Client(
            '70b96801-94cc-4501-a005-8f9a4e20e152',
            'Happy Elementor Addons',
            HAPPY_ADDONS__FILE__
        );

        // Active insights
        $this->appsero->insights()
            ->add_extra([
                'pro_installed' => ha_has_pro() ? 'Yes' : 'No',
                'pro_version' => ha_has_pro() ? HAPPY_ADDONS_PRO_VERSION : '',
            ])
            ->init();
    }

    public function include_files() {
		include_once( HAPPY_ADDONS_DIR_PATH . 'inc/functions-forms.php' );

        include_once( HAPPY_ADDONS_DIR_PATH . 'classes/icons-manager.php' );
        include_once( HAPPY_ADDONS_DIR_PATH . 'classes/widgets-manager.php' );
        include_once( HAPPY_ADDONS_DIR_PATH . 'classes/assets-manager.php' );
        include_once( HAPPY_ADDONS_DIR_PATH . 'classes/cache-manager.php' );

        include_once( HAPPY_ADDONS_DIR_PATH . 'classes/widgets-cache.php' );
        include_once( HAPPY_ADDONS_DIR_PATH . 'classes/assets-cache.php' );

		include_once( HAPPY_ADDONS_DIR_PATH . 'classes/select2-handler.php' );
		include_once( HAPPY_ADDONS_DIR_PATH . 'classes/wpml-manager.php' );

        if ( is_admin() ) {
            include_once( HAPPY_ADDONS_DIR_PATH . 'classes/updater.php' );
            include_once( HAPPY_ADDONS_DIR_PATH . 'classes/dashboard.php' );
            include_once( HAPPY_ADDONS_DIR_PATH . 'classes/attention-seeker.php' );
        }

        if ( is_user_logged_in() ) {
			include_once( HAPPY_ADDONS_DIR_PATH . 'classes/library-manager.php' );
			include_once( HAPPY_ADDONS_DIR_PATH . 'classes/library-source.php' );
		}
	}

	public function include_on_init() {
		include_once( HAPPY_ADDONS_DIR_PATH . 'inc/functions-extensions.php' );
		include_once( HAPPY_ADDONS_DIR_PATH . 'classes/extensions-manager.php' );
	}

    /**
     * Add custom category.
     *
     * @param $elements_manager
     */
    public function add_category( Elements_Manager $elements_manager ) {
        $elements_manager->add_category(
            'happy_addons_category',
            [
                'title' => __( 'Happy Addons', 'happy-elementor-addons' ),
                'icon' => 'fa fa-smile-o',
            ]
        );
    }

    /**
     * Register controls
     *
     * @param Controls_Manager $controls_Manager
     */
    public function register_controls( Controls_Manager $controls_Manager ) {
        include_once( HAPPY_ADDONS_DIR_PATH . 'controls/foreground.php' );
	    include_once( HAPPY_ADDONS_DIR_PATH . 'controls/select2.php' );
        $foreground = __NAMESPACE__ . '\Controls\Group_Control_Foreground';
	    $select2 = __NAMESPACE__ . '\Controls\Select2';

        $controls_Manager->add_group_control( $foreground::get_type(), new $foreground() );
	    ha_elementor()->controls_manager->register_control( $select2::TYPE, new $select2() );
    }
}
