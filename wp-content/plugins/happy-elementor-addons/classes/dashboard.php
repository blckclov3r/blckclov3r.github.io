<?php
/**
 * Dashboard manager
 *
 * Package: Happy_Addons
 * @since 2.0.0
 */
namespace Happy_Addons\Elementor;

defined( 'ABSPATH' ) || die();

class Dashboard {

    const PAGE_SLUG = 'happy-addons';

    const LICENSE_PAGE_SLUG = 'happy-addons-license';

    const WIDGETS_NONCE = 'ha_save_dashboard';

    static $menu_slug = '';

    public static function init() {
        add_action( 'admin_menu', [ __CLASS__, 'add_menu' ], 21 );
        add_action( 'admin_menu', [ __CLASS__, 'update_menu_items' ], 99 );
        add_action( 'admin_enqueue_scripts', [ __CLASS__, 'enqueue_scripts' ] );
        add_action( 'wp_ajax_' . self::WIDGETS_NONCE, [ __CLASS__, 'save_data' ] );

        add_action( 'admin_init', [ __CLASS__, 'activation_redirect' ] );
        add_filter( 'plugin_action_links_' . plugin_basename( HAPPY_ADDONS__FILE__ ), [ __CLASS__, 'add_action_links' ] );

        add_action( 'happyaddons_save_dashboard_data', [ __CLASS__, 'save_widgets_data' ] );

        add_action( 'in_admin_header', [ __CLASS__, 'remove_all_notices' ], PHP_INT_MAX );
    }

    public static function is_page() {
        return ( isset( $_GET['page'] ) && ( $_GET['page'] === self::PAGE_SLUG || $_GET['page'] === self::LICENSE_PAGE_SLUG ) );
    }

    public static function remove_all_notices() {
        if ( self::is_page() ) {
            remove_all_actions( 'admin_notices' );
            remove_all_actions( 'all_admin_notices' );
        }
    }

    public static function activation_redirect() {
        if ( get_option( HAPPY_ADDONS_REDIRECTION_FLAG, false ) && ! isset( $_GET['activate-multi'] ) ) {
            delete_option( HAPPY_ADDONS_REDIRECTION_FLAG );
            die( wp_redirect( ha_get_dashboard_link() ) );
        }
    }

    public static function add_action_links( $links ) {
        if ( ! current_user_can( 'manage_options' ) ) {
            return $links;
        }

        $links = array_merge( [
            sprintf( '<a href="%s">%s</a>',
                ha_get_dashboard_link(),
                esc_html__( 'Settings', 'happy-elementor-addons' )
            )
        ], $links );
        if ( ! ha_has_pro() ) {
            $links = array_merge( $links, [
                sprintf( '<a target="_blank" style="color:#e2498a; font-weight: bold;" href="%s">%s</a>',
                    'https://happyaddons.com/go/get-pro',
                    esc_html__( 'Get Pro', 'happy-elementor-addons' )
                )
            ] );
        }
        return $links;
    }

    public static function save_data() {
        if ( ! current_user_can( 'manage_options' ) ) {
            return;
        }

        if ( ! check_ajax_referer( self::WIDGETS_NONCE, 'nonce' ) ) {
            wp_send_json_error();
        }

        $posted_data = ! empty( $_POST['data'] ) ? $_POST['data'] : '';
        $data = [];
        parse_str( $posted_data, $data );

        do_action( 'happyaddons_save_dashboard_data', $data );

        wp_send_json_success();
    }

    public static function save_widgets_data( $data ) {
        $widgets = ! empty( $data['widgets'] ) ? $data['widgets'] : [];
        $inactive_widgets = array_values( array_diff( array_keys( self::get_real_widgets_map() ), $widgets ) );
        Widgets_Manager::save_inactive_widgets( $inactive_widgets );
    }

    public static function enqueue_scripts( $hook ) {
        if ( self::$menu_slug !== $hook || ! current_user_can( 'manage_options' ) ) {
            return;
        }

        wp_enqueue_style(
            'happy-icons',
            HAPPY_ADDONS_ASSETS . 'fonts/style.min.css',
            null,
            HAPPY_ADDONS_VERSION
        );

        wp_enqueue_style(
            'google-nunito-font',
            HAPPY_ADDONS_ASSETS . 'fonts/nunito/stylesheet.css',
            null,
            HAPPY_ADDONS_VERSION
        );

        wp_enqueue_style(
            'happy-elementor-addons-dashboard',
            HAPPY_ADDONS_ASSETS . 'admin/css/dashboard.min.css',
            null,
            HAPPY_ADDONS_VERSION
        );

        /**
         * Magnific popup
         */
        wp_enqueue_style(
            'magnific-popup',
            HAPPY_ADDONS_ASSETS . 'vendor/magnific-popup/magnific-popup.css',
            null,
            HAPPY_ADDONS_VERSION
        );

        wp_enqueue_script(
            'jquery-magnific-popup',
            HAPPY_ADDONS_ASSETS . 'vendor/magnific-popup/jquery.magnific-popup.min.js',
            null,
            HAPPY_ADDONS_VERSION,
            true
        );

        wp_enqueue_script(
            'happy-elementor-addons-dashboard',
            HAPPY_ADDONS_ASSETS . 'admin/js/dashboard.min.js',
            [ 'jquery' ],
            HAPPY_ADDONS_VERSION,
            true
        );

        wp_localize_script(
            'happy-elementor-addons-dashboard',
            'HappyDashboard',
            [
                'nonce' => wp_create_nonce( self::WIDGETS_NONCE ),
                'ajaxUrl' => admin_url( 'admin-ajax.php' ),
                'action' => self::WIDGETS_NONCE,
                'saveChangesLabel' => esc_html__( 'Save Changes', 'happy-elementor-addons' ),
                'savedLabel' => esc_html__( 'Changes Saved', 'happy-elementor-addons' ),
            ]
        );
    }

    private static function get_real_widgets_map() {
        $widgets_map = Widgets_Manager::get_widgets_map();
        unset( $widgets_map[ Widgets_Manager::get_base_widget_key() ] );
        return $widgets_map;
    }

    public static function get_widgets() {
        $widgets_map = self::get_real_widgets_map();

        if ( ! ha_has_pro() ) {
            $widgets_map = array_merge( $widgets_map, Widgets_Manager::get_pro_widget_map() );
        }

        uksort( $widgets_map, [ __CLASS__, 'sort_widgets' ] );
        return $widgets_map;
    }

    public static function sort_widgets( $k1, $k2 ) {
        return strcasecmp( $k1, $k2 );
    }

    public static function add_menu() {
        self::$menu_slug = add_menu_page(
            __( 'Happy Elementor Addons Dashboard', 'happy-elementor-addons' ),
            __( 'Happy Addons', 'happy-elementor-addons' ),
            'manage_options',
            self::PAGE_SLUG,
            [ __CLASS__, 'render_main' ],
            ha_get_b64_icon(),
            58.5
        );

        $tabs = self::get_tabs();
        if ( is_array( $tabs ) ) {
            foreach ( $tabs as $key => $data ) {
                if ( empty( $data['renderer'] ) || ! is_callable( $data['renderer'] ) ) {
                    continue;
                }

                add_submenu_page(
                    self::PAGE_SLUG,
                    sprintf( __( '%s - Happy Elementor Addons', 'happy-elementor-addons' ), $data['title'] ),
                    $data['title'],
                    'manage_options',
                    self::PAGE_SLUG . '#' . $key,
                    [ __CLASS__, 'render_main' ]
                );
            }
        }
    }

    public static function update_menu_items() {
        if ( ! current_user_can( 'manage_options' ) ) {
            return;
        }

        global $submenu;
        $menu = $submenu[ self::PAGE_SLUG ];
        array_shift( $menu );
        $submenu[ self::PAGE_SLUG ] = $menu;
    }

    public static function get_tabs() {
        $tabs = [
            'home' => [
                'title' => esc_html__( 'Home', 'happy-elementor-addons' ),
                'renderer' => [ __CLASS__, 'render_home' ],
            ],
            'widgets' => [
                'title' => esc_html__( 'Widgets', 'happy-elementor-addons' ),
                'renderer' => [ __CLASS__, 'render_widgets' ],
            ],
            'pro' => [
                'title' => esc_html__( 'Get Pro', 'happy-elementor-addons' ),
                'renderer' => [ __CLASS__, 'render_pro' ],
            ],
        ];

        return apply_filters( 'happyaddons_dashboard_get_tabs', $tabs );
    }

    private static function load_template( $template ) {
        $file = HAPPY_ADDONS_DIR_PATH . 'templates/admin/dashboard-' . $template . '.php';
        if ( is_readable( $file ) ) {
            include( $file );
        }
    }

    public static function render_main() {
        self::load_template( 'main' );
    }

    public static function render_home() {
        self::load_template( 'home' );
    }

    public static function render_widgets() {
        self::load_template( 'widgets' );
    }

    public static function render_pro() {
        self::load_template( 'pro' );
    }
}

Dashboard::init();
