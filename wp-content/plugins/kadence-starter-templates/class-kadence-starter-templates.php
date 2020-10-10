<?php
/**
 * Importer class.
 *
 * @package Kadence Starter Templates
 */

namespace Kadence_Starter_Templates;

use function activate_plugin;
use function plugins_api;
use function wp_send_json_error;

/**
 * Block direct access to the main plugin file.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * Main plugin class with initialization tasks.
 */
class Starter_Templates {
	/**
	 * Instance of this class
	 *
	 * @var null
	 */
	private static $instance = null;

	/**
	 * The instance of the Importer class.
	 *
	 * @var object
	 */
	public $importer;

	/**
	 * The resulting page's hook_suffix, or false if the user does not have the capability required.
	 *
	 * @var boolean or string
	 */
	private $plugin_page;

	/**
	 * Holds the verified import files.
	 *
	 * @var array
	 */
	public $import_files;

	/**
	 * The path of the log file.
	 *
	 * @var string
	 */
	public $log_file_path;

	/**
	 * The index of the `import_files` array (which import files was selected).
	 *
	 * @var int
	 */
	private $selected_index;

	/**
	 * The palette for the import.
	 *
	 * @var string
	 */
	private $selected_palette;

	/**
	 * The paths of the actual import files to be used in the import.
	 *
	 * @var array
	 */
	private $selected_import_files;

	/**
	 * Holds any error messages, that should be printed out at the end of the import.
	 *
	 * @var string
	 */
	public $frontend_error_messages = array();

	/**
	 * Was the before content import already triggered?
	 *
	 * @var boolean
	 */
	private $before_import_executed = false;

	/**
	 * Make plugin page options available to other methods.
	 *
	 * @var array
	 */
	private $plugin_page_setup = array();

	/**
	 * Instance Control
	 */
	public static function get_instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}
	/**
	 * Construct function
	 */
	public function __construct() {
		// Set plugin constants.
		$this->set_plugin_constants();
		$this->include_plugin_files();
		add_action( 'init', array( $this, 'init_config' ) );
		add_action( 'wp_ajax_kadence_import_demo_data', array( $this, 'import_demo_data_ajax_callback' ) );
		add_action( 'wp_ajax_kadence_import_install_plugins', array( $this, 'install_plugins_ajax_callback' ) );
		add_action( 'wp_ajax_kadence_import_customizer_data', array( $this, 'import_customizer_data_ajax_callback' ) );
		add_action( 'wp_ajax_kadence_after_import_data', array( $this, 'after_all_import_data_ajax_callback' ) );
		add_action( 'after_setup_theme', array( $this, 'setup_plugin_with_filter_data' ) );
		add_action( 'plugins_loaded', array( $this, 'load_textdomain' ) );
		add_filter( 'kadence-starter-templates/import_files', array( $this, 'kadence_import_kadence_theme_files' ) );
		add_action( 'kadence-starter-templates/after_import', array( $this, 'kadence_kadence_theme_after_import' ), 10, 2 );

		add_filter( 'plugin_action_links_kadence-starter-templates/kadence-starter-templates.php', array( $this, 'add_settings_link' ) );
	}
	/**
	 * Add a little css for submenu items.
	 */
	public function basic_css_menu_support() {
		wp_register_style( 'kadence-import-admin', false );
		wp_enqueue_style( 'kadence-import-admin' );
		$css = '#menu-appearance .wp-submenu a[href^="themes.php?page=kadence-"]:before {content: "\21B3";margin-right: 0.5em;opacity: 0.5;}';
		wp_add_inline_style( 'kadence-import-admin', $css );
	}
	/**
	 * Kadence Import
	 */
	public function init_config() {
		if ( class_exists( 'Kadence\Theme' ) && defined( 'KADENCE_VERSION' ) && version_compare( KADENCE_VERSION, '0.8.0', '>=' ) ) {
			add_action( 'kadence_theme_admin_menu', array( $this, 'create_admin_page' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'basic_css_menu_support' ) );
		} else {
			add_action( 'admin_menu', array( $this, 'create_admin_page' ) );
		}
	}
	/**
	 * Kadence After Import functions.
	 *
	 * @param array $selected_import the selected import.
	 */
	public function kadence_kadence_theme_after_import( $selected_import, $selected_palette ) {
		if ( 'agency' === $selected_import['import_file_name'] ) {

			// Assign menus to their locations.
			$main_menu = get_term_by( 'name', 'Agency Menu', 'nav_menu' );

			set_theme_mod(
				'nav_menu_locations',
				array(
					'primary' => $main_menu->term_id,
					'mobile'  => $main_menu->term_id,
				)
			);

			// Assign front page.
			$homepage = get_page_by_title( 'Home' );
			if ( isset( $homepage ) && $homepage->ID ) {
				update_option( 'show_on_front', 'page' );
				update_option( 'page_on_front', $homepage->ID ); // Front Page.
			}

		} elseif ( 'agency_free' === $selected_import['import_file_name'] ) {

			// Assign menus to their locations.
			$main_menu = get_term_by( 'name', 'Agency Menu', 'nav_menu' );
			set_theme_mod(
				'nav_menu_locations',
				array(
					'primary' => $main_menu->term_id,
					'mobile'  => $main_menu->term_id,
				)
			);

			// Assign front page.
			$homepage = get_page_by_title( 'Home' );
			if ( isset( $homepage ) && $homepage->ID ) {
				update_option( 'show_on_front', 'page' );
				update_option( 'page_on_front', $homepage->ID ); // Front Page.
				$blogpage = get_page_by_title( 'Blog' );
				update_option( 'page_for_posts', $blogpage->ID ); // Blog Page.
			}

		} elseif ( 'food' === $selected_import['import_file_name'] ) {

			// Assign menus to their locations.
			$main_menu = get_term_by( 'name', 'Food Primary', 'nav_menu' );

			set_theme_mod(
				'nav_menu_locations',
				array(
					'primary' => $main_menu->term_id,
					'mobile'  => $main_menu->term_id,
					'footer'  => $main_menu->term_id,
				)
			);

			// Assign front page.
			$homepage = get_page_by_title( 'Home' );
			if ( isset( $homepage ) && $homepage->ID ) {
				update_option( 'show_on_front', 'page' );
				update_option( 'page_on_front', $homepage->ID ); // Front Page.
				$blogpage = get_page_by_title( 'Recipes' );
				update_option( 'page_for_posts', $blogpage->ID ); // Blog Page.
			}

		} elseif ( 'shopping' === $selected_import['import_file_name'] ) {
			// Assign Woo Pages.
			if ( class_exists( 'woocommerce' ) ) {
				$this->import_demo_woocommerce();
			}

			// Assign menus to their locations.
			$main_menu = get_term_by( 'name', 'Shop Menu', 'nav_menu' );

			set_theme_mod(
				'nav_menu_locations',
				array(
					'primary' => $main_menu->term_id,
					'mobile'  => $main_menu->term_id,
				)
			);

			// Assign front page.
			$homepage = get_page_by_title( 'Home' );
			if ( isset( $homepage ) && $homepage->ID ) {
				update_option( 'show_on_front', 'page' );
				update_option( 'page_on_front', $homepage->ID ); // Front Page.
				$blogpage = get_page_by_title( 'Shop News' );
				update_option( 'page_for_posts', $blogpage->ID ); // Blog Page.
			}

		} elseif ( 'sass' === $selected_import['import_file_name'] ) {
			// Assign menus to their locations.
			$main_menu = get_term_by( 'name', 'Sass Menu', 'nav_menu' );
			$footer_menu = get_term_by( 'name', 'Sass Footer', 'nav_menu' );

			set_theme_mod(
				'nav_menu_locations',
				array(
					'primary' => $main_menu->term_id,
					'mobile'  => $main_menu->term_id,
					'footer'  => $footer_menu->term_id,
				)
			);

			// Assign front page.
			$homepage = get_page_by_title( 'Home' );
			if ( isset( $homepage ) && $homepage->ID ) {
				update_option( 'show_on_front', 'page' );
				update_option( 'page_on_front', $homepage->ID ); // Front Page.
			}

		} elseif ( 'yoga' === $selected_import['import_file_name'] ) {
			// Assign menus to their locations.
			$main_menu = get_term_by( 'name', 'Yoga Menu', 'nav_menu' );

			set_theme_mod(
				'nav_menu_locations',
				array(
					'primary' => $main_menu->term_id,
					'mobile'  => $main_menu->term_id,
				)
			);

			// Assign front page.
			$homepage = get_page_by_title( 'Home' );
			if ( isset( $homepage ) && $homepage->ID ) {
				update_option( 'show_on_front', 'page' );
				update_option( 'page_on_front', $homepage->ID ); // Front Page.
			}

		} elseif ( 'ldcourse' === $selected_import['import_file_name'] ) {
			// Assign menus to their locations.
			$main_menu = get_term_by( 'name', 'Primary Menu', 'nav_menu' );

			set_theme_mod(
				'nav_menu_locations',
				array(
					'primary' => $main_menu->term_id,
					'mobile'  => $main_menu->term_id,
				)
			);

			// Assign front page.
			$homepage = get_page_by_title( 'Home' );
			if ( isset( $homepage ) && $homepage->ID ) {
				update_option( 'show_on_front', 'page' );
				update_option( 'page_on_front', $homepage->ID ); // Front Page.
			}

		}
		if ( $selected_palette && ! empty( $selected_palette ) ) {
			$palette_presets = json_decode( '{"basic":[{"color":"#2B6CB0"},{"color":"#265E9A"},{"color":"#222222"},{"color":"#3B3B3B"},{"color":"#515151"},{"color":"#626262"},{"color":"#E1E1E1"},{"color":"#F7F7F7"},{"color":"#ffffff"}],"bright":[{"color":"#255FDD"},{"color":"#00F2FF"},{"color":"#1A202C"},{"color":"#2D3748"},{"color":"#4A5568"},{"color":"#718096"},{"color":"#EDF2F7"},{"color":"#F7FAFC"},{"color":"#ffffff"}],"darkmode":[{"color":"#3296ff"},{"color":"#003174"},{"color":"#ffffff"},{"color":"#f7fafc"},{"color":"#edf2f7"},{"color":"#cbd2d9"},{"color":"#2d3748"},{"color":"#252c39"},{"color":"#1a202c"}],"orange":[{"color":"#e47b02"},{"color":"#ed8f0c"},{"color":"#1f2933"},{"color":"#3e4c59"},{"color":"#52606d"},{"color":"#7b8794"},{"color":"#f3f4f7"},{"color":"#f9f9fb"},{"color":"#ffffff"}],"pinkish":[{"color":"#E21E51"},{"color":"#4d40ff"},{"color":"#040037"},{"color":"#032075"},{"color":"#514d7c"},{"color":"#666699"},{"color":"#deddeb"},{"color":"#efeff5"},{"color":"#f8f9fa"}],"pinkishdark":[{"color":"#E21E51"},{"color":"#4d40ff"},{"color":"#f8f9fa"},{"color":"#efeff5"},{"color":"#deddeb"},{"color":"#c3c2d6"},{"color":"#514d7c"},{"color":"#221e5b"},{"color":"#040037"}],"green":[{"color":"#049f82"},{"color":"#008f72"},{"color":"#222222"},{"color":"#353535"},{"color":"#454545"},{"color":"#676767"},{"color":"#eeeeee"},{"color":"#f7f7f7"},{"color":"#ffffff"}],"fire":[{"color":"#dd6b20"},{"color":"#cf3033"},{"color":"#27241d"},{"color":"#423d33"},{"color":"#504a40"},{"color":"#625d52"},{"color":"#e8e6e1"},{"color":"#faf9f7"},{"color":"#ffffff"}]}', true );
			if ( isset( $palette_presets[ $selected_palette ] ) ) {
				$default = json_decode( '{"palette":[{"color":"#3182CE","slug":"palette1","name":"Palette Color 1"},{"color":"#2B6CB0","slug":"palette2","name":"Palette Color 2"},{"color":"#1A202C","slug":"palette3","name":"Palette Color 3"},{"color":"#2D3748","slug":"palette4","name":"Palette Color 4"},{"color":"#4A5568","slug":"palette5","name":"Palette Color 5"},{"color":"#718096","slug":"palette6","name":"Palette Color 6"},{"color":"#EDF2F7","slug":"palette7","name":"Palette Color 7"},{"color":"#F7FAFC","slug":"palette8","name":"Palette Color 8"},{"color":"#ffffff","slug":"palette9","name":"Palette Color 9"}],"second-palette":[{"color":"#3182CE","slug":"palette1","name":"Palette Color 1"},{"color":"#2B6CB0","slug":"palette2","name":"Palette Color 2"},{"color":"#1A202C","slug":"palette3","name":"Palette Color 3"},{"color":"#2D3748","slug":"palette4","name":"Palette Color 4"},{"color":"#4A5568","slug":"palette5","name":"Palette Color 5"},{"color":"#718096","slug":"palette6","name":"Palette Color 6"},{"color":"#EDF2F7","slug":"palette7","name":"Palette Color 7"},{"color":"#F7FAFC","slug":"palette8","name":"Palette Color 8"},{"color":"#ffffff","slug":"palette9","name":"Palette Color 9"}],"third-palette":[{"color":"#3182CE","slug":"palette1","name":"Palette Color 1"},{"color":"#2B6CB0","slug":"palette2","name":"Palette Color 2"},{"color":"#1A202C","slug":"palette3","name":"Palette Color 3"},{"color":"#2D3748","slug":"palette4","name":"Palette Color 4"},{"color":"#4A5568","slug":"palette5","name":"Palette Color 5"},{"color":"#718096","slug":"palette6","name":"Palette Color 6"},{"color":"#EDF2F7","slug":"palette7","name":"Palette Color 7"},{"color":"#F7FAFC","slug":"palette8","name":"Palette Color 8"},{"color":"#ffffff","slug":"palette9","name":"Palette Color 9"}],"active":"palette"}', true );
				$default['palette'][0]['color'] = $palette_presets[ $selected_palette ][0]['color'];
				$default['palette'][1]['color'] = $palette_presets[ $selected_palette ][1]['color'];
				$default['palette'][2]['color'] = $palette_presets[ $selected_palette ][2]['color'];
				$default['palette'][3]['color'] = $palette_presets[ $selected_palette ][3]['color'];
				$default['palette'][4]['color'] = $palette_presets[ $selected_palette ][4]['color'];
				$default['palette'][5]['color'] = $palette_presets[ $selected_palette ][5]['color'];
				$default['palette'][6]['color'] = $palette_presets[ $selected_palette ][6]['color'];
				$default['palette'][7]['color'] = $palette_presets[ $selected_palette ][7]['color'];
				$default['palette'][8]['color'] = $palette_presets[ $selected_palette ][8]['color'];
				update_option( 'kadence_global_palette', json_encode( $default ) );
			}
		} else if ( 'ldcourse' === $selected_import['import_file_name'] ) { 
			$default = json_decode( '{"palette":[{"color":"#3182CE","slug":"palette1","name":"Palette Color 1"},{"color":"#2B6CB0","slug":"palette2","name":"Palette Color 2"},{"color":"#1A202C","slug":"palette3","name":"Palette Color 3"},{"color":"#2D3748","slug":"palette4","name":"Palette Color 4"},{"color":"#4A5568","slug":"palette5","name":"Palette Color 5"},{"color":"#718096","slug":"palette6","name":"Palette Color 6"},{"color":"#EDF2F7","slug":"palette7","name":"Palette Color 7"},{"color":"#F7FAFC","slug":"palette8","name":"Palette Color 8"},{"color":"#ffffff","slug":"palette9","name":"Palette Color 9"}],"second-palette":[{"color":"#3182CE","slug":"palette1","name":"Palette Color 1"},{"color":"#2B6CB0","slug":"palette2","name":"Palette Color 2"},{"color":"#1A202C","slug":"palette3","name":"Palette Color 3"},{"color":"#2D3748","slug":"palette4","name":"Palette Color 4"},{"color":"#4A5568","slug":"palette5","name":"Palette Color 5"},{"color":"#718096","slug":"palette6","name":"Palette Color 6"},{"color":"#EDF2F7","slug":"palette7","name":"Palette Color 7"},{"color":"#F7FAFC","slug":"palette8","name":"Palette Color 8"},{"color":"#ffffff","slug":"palette9","name":"Palette Color 9"}],"third-palette":[{"color":"#3182CE","slug":"palette1","name":"Palette Color 1"},{"color":"#2B6CB0","slug":"palette2","name":"Palette Color 2"},{"color":"#1A202C","slug":"palette3","name":"Palette Color 3"},{"color":"#2D3748","slug":"palette4","name":"Palette Color 4"},{"color":"#4A5568","slug":"palette5","name":"Palette Color 5"},{"color":"#718096","slug":"palette6","name":"Palette Color 6"},{"color":"#EDF2F7","slug":"palette7","name":"Palette Color 7"},{"color":"#F7FAFC","slug":"palette8","name":"Palette Color 8"},{"color":"#ffffff","slug":"palette9","name":"Palette Color 9"}],"active":"palette"}', true );
			$default['palette'][0]['color'] = "#2cb1bc";
			$default['palette'][1]['color'] = "#13919b";
			$default['palette'][2]['color'] = "#0f2a43";
			$default['palette'][3]['color'] = "#133453";
			$default['palette'][4]['color'] = "#587089";
			$default['palette'][5]['color'] = "#829ab1";
			$default['palette'][6]['color'] = "#e0fcff";
			$default['palette'][7]['color'] = "#f5f7fa";
			$default['palette'][8]['color'] = "#ffffff";
			update_option( 'kadence_global_palette', json_encode( $default ) );
		}
	}
	/**
	 * Kadence Import function.
	 */
	public function import_demo_woocommerce( $shop = 'Shop', $cart = 'Cart', $checkout = 'Checkout', $myaccount = 'My Account' ) {
		$woopages = array(
			'woocommerce_shop_page_id'      => $shop,
			'woocommerce_cart_page_id'      => $cart,
			'woocommerce_checkout_page_id'  => $checkout,
			'woocommerce_myaccount_page_id' => $myaccount,
		);
		foreach ( $woopages as $woo_page_name => $woo_page_title ) {
			$woopage = get_page_by_title( $woo_page_title );
			if ( isset( $woopage ) && $woopage->ID ) {
				update_option( $woo_page_name, $woopage->ID );
			}
		}

		// We no longer need to install pages.
		delete_option( '_wc_needs_pages' );
		delete_transient( '_wc_activation_redirect' );

		// Flush rules after install.
		flush_rewrite_rules();
	}
	/**
	 * Kadence Import function.
	 */
	public function kadence_import_kadence_theme_files() {
		$woocommerce = array(
			'base'         => 'woocommerce',
			'slug'         => 'woocommerce',
			'path'         => 'woocommerce/woocommerce.php',
			'title'        => 'Woocommerce',
			'src'          => 'repo',
			'state'        => Plugin_Check::active_check( 'woocommerce/woocommerce.php' ),
		);
		$kadence_blocks = array(
			'base'         => 'kadence-blocks',
			'slug'         => 'kadence-blocks',
			'path'         => 'kadence-blocks/kadence-blocks.php',
			'title'        => 'Kadence Blocks',
			'src'          => 'repo',
			'state'        => Plugin_Check::active_check( 'kadence-blocks/kadence-blocks.php' ),
		);
		$kadence_blocks_pro = array(
			'base'         => 'kadence-blocks-pro',
			'slug'         => 'kadence-blocks-pro',
			'path'         => 'kadence-blocks-pro/kadence-blocks-pro.php',
			'title'        => 'Kadence Block Pro',
			'src'          => 'bundle',
			'state'        => Plugin_Check::active_check( 'kadence-blocks-pro/kadence-blocks-pro.php' ),
		);
		$wpzoom_recipe_card = array(
			'base'         => 'recipe-card-blocks-by-wpzoom',
			'slug'         => 'wpzoom-recipe-card',
			'path'         => 'recipe-card-blocks-by-wpzoom/wpzoom-recipe-card.php',
			'title'        => 'Recipe Card Blocks by WPZOOM',
			'src'          => 'repo',
			'state'        => Plugin_Check::active_check( 'recipe-card-blocks-by-wpzoom/wpzoom-recipe-card.php' ),
		);
		$learn_dash = array(
			'base'         => 'sfwd-lms',
			'slug'         => 'sfwd_lms',
			'path'         => 'sfwd-lms/sfwd_lms.php',
			'title'        => 'LearnDash',
			'src'          => 'thirdparty',
			'state'        => Plugin_Check::active_check( 'sfwd-lms/sfwd_lms.php' ),
		);
		if ( 'notactive' !== $kadence_blocks_pro['state'] ) {
			$agency = array(
				'import_file_name'           => 'agency',
				'categories'                 => array( 'Kadence Blocks Pro' ),
				'import_file_url'            => 'https://kadence.design/importer/kadence/agency_pro/demo_content.xml',
				'import_widget_file_url'     => '',
				'import_customizer_file_url' => 'https://kadence.design/importer/kadence/agency_pro/theme_options.json',
				'preview_url'                => 'https://demos.kadencewp.com/blocks-agency/',
				'import_preview_image_url'   => 'https://kadence.design/importer/kadence/agency_pro/preview-image.jpg',
				'import_notice'              => '',
				'plugins'                    => array(
					$kadence_blocks,
					$kadence_blocks_pro,
				),
			);
		} else {
			$agency = array(
				'import_file_name'           => 'agency_free',
				'categories'                 => array(),
				'import_file_url'            => 'https://kadence.design/importer/kadence/agency/demo_content.xml',
				'import_widget_file_url'     => '',
				'import_customizer_file_url' => 'https://kadence.design/importer/kadence/agency/theme_options.json',
				'preview_url'                => 'https://demos.kadencewp.com/agency-free/',
				'import_preview_image_url'   => 'https://kadence.design/importer/kadence/agency/preview-image.jpg',
				'import_notice'              => '',
				'plugins'                    => array(
					$kadence_blocks,
				),
			);
		}
		$demos = array(
			$agency,
			array(
				'import_file_name'           => 'food',
				'categories'                 => array(),
				'import_file_url'            => 'https://kadence.design/importer/kadence/recipe_blog/demo_content.xml',
				'import_widget_file_url'     => '',
				'import_customizer_file_url' => 'https://kadence.design/importer/kadence/recipe_blog/theme_options.json',
				'preview_url'                => 'https://demos.kadencewp.com/food/',
				'import_preview_image_url'   => 'https://kadence.design/importer/kadence/recipe_blog/preview-image.jpg',
				'import_notice'              => '',
				'plugins'                    => array(
					$kadence_blocks,
					$wpzoom_recipe_card,
				),
			),
			array(
				'import_file_name'           => 'shopping',
				'categories'                 => array( 'Woocommerce' ),
				'import_file_url'            => 'https://kadence.design/importer/kadence/shopping_site/demo_content.xml',
				'import_widget_file_url'     => 'https://kadence.design/importer/kadence/shopping_site/widget_data.json',
				'import_customizer_file_url' => 'https://kadence.design/importer/kadence/shopping_site/theme_options.json',
				'preview_url'                => 'https://demos.kadencewp.com/blocks-store/',
				'import_preview_image_url'   => 'https://kadence.design/importer/kadence/shopping_site/preview-image.jpg',
				'import_notice'              => '',
				'plugins'                    => array(
					$kadence_blocks,
					$woocommerce,
				),
			),
			array(
				'import_file_name'           => 'yoga',
				'categories'                 => array( 'Business' ),
				'import_file_url'            => 'https://kadence.design/importer/kadence/yoga_site/demo_content.xml',
				'import_widget_file_url'     => 'https://kadence.design/importer/kadence/yoga_site/widget_data.json',
				'import_customizer_file_url' => 'https://kadence.design/importer/kadence/yoga_site/theme_options.json',
				'preview_url'                => 'https://demos.kadencewp.com/blocks-active/',
				'import_preview_image_url'   => 'https://kadence.design/importer/kadence/yoga_site/preview-image.jpg',
				'import_notice'              => '',
				'plugins'                    => array(
					$kadence_blocks,
				),
			),
			array(
				'import_file_name'           => 'sass',
				'categories'                 => array( 'Business' ),
				'import_file_url'            => 'https://kadence.design/importer/kadence/sass_site/demo_content.xml',
				'import_widget_file_url'     => '',
				'import_customizer_file_url' => 'https://kadence.design/importer/kadence/sass_site/theme_options.json',
				'preview_url'                => 'https://demos.kadencewp.com/blocks-saas/',
				'import_preview_image_url'   => 'https://kadence.design/importer/kadence/sass_site/preview-image.jpg',
				'import_notice'              => '',
				'plugins'                    => array(
					$kadence_blocks,
				),
			),
			array(
				'import_file_name'           => 'ldcourse',
				'categories'                 => array( 'Business' ),
				'import_file_url'            => 'https://kadence.design/importer/kadence/ld_single_course/demo_content.xml',
				'import_widget_file_url'     => 'https://kadence.design/importer/kadence/ld_single_course/widget_data.json',
				'import_customizer_file_url' => 'https://kadence.design/importer/kadence/ld_single_course/theme_options.json',
				'preview_url'                => 'https://demos.kadencewp.com/course/',
				'import_preview_image_url'   => 'https://kadence.design/importer/kadence/ld_single_course/preview-image.jpg',
				'import_notice'              => '',
				'plugins'                    => array(
					$kadence_blocks,
					$learn_dash,
				),
			),
		);
		return $demos;
	}
	/**
	 * Private clone method to prevent cloning of the instance of the *Singleton* instance.
	 *
	 * @return void
	 */
	private function __clone() {}


	/**
	 * Private unserialize method to prevent unserializing of the *Singleton* instance.
	 *
	 * @return void
	 */
	private function __wakeup() {}

	/**
	 * Set plugin constants.
	 *
	 * Path/URL to root of this plugin, with trailing slash and plugin version.
	 */
	private function set_plugin_constants() {
		// Path/URL to root of this plugin, with trailing slash.
		if ( ! defined( 'KADENCE_STARTER_TEMPLATES_PATH' ) ) {
			define( 'KADENCE_STARTER_TEMPLATES_PATH', plugin_dir_path( __FILE__ ) );
		}
		if ( ! defined( 'KADENCE_STARTER_TEMPLATES_URL' ) ) {
			define( 'KADENCE_STARTER_TEMPLATES_URL', trailingslashit( plugin_dir_url( __FILE__ ) ) );
		}
		if ( ! defined( 'KADENCE_STARTER_TEMPLATES_VERSION' ) ) {
			define( 'KADENCE_STARTER_TEMPLATES_VERSION', '1.0.4' );
		}
	}
	/**
	 * Include all plugin files.
	 */
	private function include_plugin_files() {
		require_once KADENCE_STARTER_TEMPLATES_PATH . 'inc/class-author-meta.php';
		require_once KADENCE_STARTER_TEMPLATES_PATH . 'inc/class-import-export-option.php';
		require_once KADENCE_STARTER_TEMPLATES_PATH . 'inc/class-plugin-check.php';
		require_once KADENCE_STARTER_TEMPLATES_PATH . 'inc/class-helpers.php';
		require_once KADENCE_STARTER_TEMPLATES_PATH . 'inc/class-import-actions.php';
		require_once KADENCE_STARTER_TEMPLATES_PATH . 'inc/class-widget-importer.php';
		require_once KADENCE_STARTER_TEMPLATES_PATH . 'inc/class-logger.php';
		require_once KADENCE_STARTER_TEMPLATES_PATH . 'inc/class-logger-cli.php';
		require_once KADENCE_STARTER_TEMPLATES_PATH . 'inc/class-importer.php';
		require_once KADENCE_STARTER_TEMPLATES_PATH . 'inc/class-downloader.php';
		require_once KADENCE_STARTER_TEMPLATES_PATH . 'inc/class-customizer-importer.php';
	}

	/**
	 * Add settings link
	 *
	 * @param array $links holds plugin links.
	 */
	public function add_settings_link( $links ) {
		$settings_link = '<a href="' . admin_url( 'themes.php?page=kadence-starter-templates' ) . '">' . __( 'View Template Library', 'kadence-starter-templates' ) . '</a>';
		array_unshift( $links, $settings_link );
		return $links;
	}

	/**
	 * Creates the plugin page and a submenu item in WP Appearance menu.
	 */
	public function create_admin_page() {
		$page = add_theme_page(
			esc_html__( 'Starter Templates by Kadence WP', 'kadence-starter-templates' ),
			esc_html__( 'Starter Templates', 'kadence-starter-templates' ),
			'import',
			'kadence-starter-templates',
			array( $this, 'render_admin_page' )
		);
		add_action( 'admin_print_styles-' . $page, array( $this, 'scripts' ) );
	}

	/**
	 * Plugin page display.
	 * Output (HTML) is in another file.
	 */
	public function render_admin_page() {
		?>
		<div class="kadence_theme_dash_head">
			<div class="kadence_theme_dash_head_container">
				<div class="kadence_theme_dash_logo">
					<img src="<?php echo esc_attr( KADENCE_STARTER_TEMPLATES_URL . 'assets/images/kadence_logo.png' ); ?>">
				</div>
				<div class="kadence_theme_dash_logo_title">
					<h1>
						KADENCE STARTER TEMPLATES
					</h1>
				</div>
				<div class="kadence_theme_dash_version">
					<span>
						<?php echo esc_html( KADENCE_STARTER_TEMPLATES_VERSION ); ?>
					</span>
				</div>
			</div>
		</div>
		<div class="wrap kadence_theme_dash">
			<div class="kadence_theme_dashboard">
				<h2 class="notices" style="display:none;"></h2>
				<?php settings_errors(); ?>
				<div class="kadence_starter_templates_finished"></div>
				<div class="kadence_starter_templates_error"></div>
				<div class="page-grid">
					<div class="kadence_starter_dashboard_main">
					</div>
			</div>
		</div>
		<?php
	}

	/**
	 * Loads admin style sheets and scripts
	 */
	public function scripts() {
		$woocommerce = array(
			'title'        => 'Woocommerce',
			'state'        => Plugin_Check::active_check( 'woocommerce/woocommerce.php' ),
			'src'          => 'repo',
		);
		$kadence_blocks = array(
			'title'        => 'Kadence Blocks',
			'state'        => Plugin_Check::active_check( 'kadence-blocks/kadence-blocks.php' ),
			'src'          => 'repo',
		);
		$kadence_blocks_pro = array(
			'title'        => 'Kadence Block Pro',
			'state'        => Plugin_Check::active_check( 'kadence-blocks-pro/kadence-blocks-pro.php' ),
			'src'          => 'bundle',
		);
		$wpzoom_recipe_card = array(
			'title'        => 'Recipe Card Blocks by WPZOOM',
			'state'        => Plugin_Check::active_check( 'recipe-card-blocks-by-wpzoom/wpzoom-recipe-card.php' ),
			'src'          => 'repo',
		);
		$learn_dash = array(
			'title'        => 'LearnDash',
			'state'        => Plugin_Check::active_check( 'sfwd-lms/sfwd_lms.php' ),
			'src'          => 'thirdparty',
		);
		if ( 'notactive' !== $kadence_blocks_pro['state'] ) {
			$agency = array(
				'key'      => 0,
				'slug'     => 'agency',
				'name'     => __( 'Agency', 'kadence-starter-templates' ),
				'keywords' => array(
					__( 'portfolio', 'kadence-starter-templates' ),
					__( 'services', 'kadence-starter-templates' ),
					__( 'business', 'kadence-starter-templates' ),
					__( 'transparent', 'kadence-starter-templates' ),
				),
				'url'        => 'https://demos.kadencewp.com/blocks-agency/',
				'categories' => array( 'business' ),
				'plugins'    => array(
					$kadence_blocks,
					$kadence_blocks_pro,
				),
				'image' => 'https://kadence.design/importer/kadence/agency/preview-image.jpg',
			);
		} else {
			$agency = array(
				'key'      => 0,
				'slug'     => 'agency_free',
				'name'     => __( 'Agency', 'kadence-starter-templates' ),
				'keywords' => array(
					__( 'portfolio', 'kadence-starter-templates' ),
					__( 'services', 'kadence-starter-templates' ),
					__( 'business', 'kadence-starter-templates' ),
					__( 'transparent', 'kadence-starter-templates' ),
				),
				'url'        => 'https://demos.kadencewp.com/agency-free/',
				'categories' => array( 'business' ),
				'plugins'    => array(
					$kadence_blocks,
				),
				'image' => 'https://kadence.design/importer/kadence/agency/preview-image.jpg',
			);
		}
		$templates = array(
			$agency,
			array(
				'key'  => 1,
				'slug' => 'food',
				'name' => __( 'Recipe Blog', 'kadence-starter-templates' ),
				'keywords' => array(
					__( 'blog', 'kadence-starter-templates' ),
					__( 'food', 'kadence-starter-templates' ),
					__( 'recipe', 'kadence-starter-templates' ),
				),
				'url' => 'https://demos.kadencewp.com/food/',
				'categories' => array( 'blog' ),
				'plugins'  => array(
					$kadence_blocks,
					$wpzoom_recipe_card,
				),
				'image' => 'https://kadence.design/importer/kadence/recipe_blog/preview-image.jpg',
			),
			array(
				'key'  => 2,
				'slug' => 'shopping',
				'name' => __( 'Shopping', 'kadence-starter-templates' ),
				'keywords' => array(
					__( 'ecommerce', 'kadence-starter-templates' ),
					__( 'shopping', 'kadence-starter-templates' ),
					__( 'business', 'kadence-starter-templates' ),
				),
				'url' => 'https://demos.kadencewp.com/blocks-store/',
				'categories' => array( 'ecommerce' ),
				'plugins'  => array(
					$kadence_blocks,
					$woocommerce,
				),
				'image' => 'https://kadence.design/importer/kadence/shopping_site/preview-image.jpg',
			),
			array(
				'key'  => 3,
				'slug' => 'yogo',
				'name' => __( 'Yoga Studio', 'kadence-starter-templates' ),
				'keywords' => array(
					__( 'yoga', 'kadence-starter-templates' ),
					__( 'gym', 'kadence-starter-templates' ),
					__( 'business', 'kadence-starter-templates' ),
				),
				'url' => 'https://demos.kadencewp.com/blocks-active/',
				'categories' => array( 'business' ),
				'plugins'  => array(
					$kadence_blocks,
				),
				'image' => 'https://kadence.design/importer/kadence/yoga_site/preview-image.jpg',
			),
			array(
				'key'  => 4,
				'slug' => 'sass',
				'name' => __( 'Sass', 'kadence-starter-templates' ),
				'keywords' => array(
					__( 'sass', 'kadence-starter-templates' ),
					__( 'pricing', 'kadence-starter-templates' ),
					__( 'business', 'kadence-starter-templates' ),
				),
				'url' => 'https://demos.kadencewp.com/blocks-saas/',
				'categories' => array( 'business' ),
				'plugins'  => array(
					$kadence_blocks,
				),
				'image' => 'https://kadence.design/importer/kadence/sass_site/preview-image.jpg',
			),
			array(
				'key'  => 5,
				'slug' => 'ldcourse',
				'name' => __( 'LearnDash Course', 'kadence-starter-templates' ),
				'keywords' => array(
					__( 'course', 'kadence-starter-templates' ),
					__( 'learndash', 'kadence-starter-templates' ),
					__( 'business', 'kadence-starter-templates' ),
				),
				'url' => 'https://demos.kadencewp.com/course/',
				'categories' => array( 'business' ),
				'plugins'  => array(
					$kadence_blocks,
					$learn_dash,
				),
				'image' => 'https://kadence.design/importer/kadence/ld_single_course/preview-image.jpg',
			),
		);
		$palettes = array(
			array(
				'palette' => 'base',
				'colors' => array(
					'#2B6CB0',
					'#265E9A',
					'#222222',
					'#ffffff',
				),
			),
			array(
				'palette' => 'bright',
				'colors' => array(
					'#255FDD',
					'#00F2FF',
					'#1A202C',
					'#ffffff',
				),
			),
			array(
				'palette' => 'darkmode',
				'colors' => array(
					'#3296ff',
					'#003174',
					'#ffffff',
					'#1a202c',
				),
			),
			array(
				'palette' => 'orange',
				'colors' => array(
					'#e47b02',
					'#ed8f0c',
					'#1f2933',
					'#ffffff',
				),
			),
			array(
				'palette' => 'pinkish',
				'colors' => array(
					'#E21E51',
					'#4d40ff',
					'#040037',
					'#ffffff',
				),
			),
			array(
				'palette' => 'pinkishdark',
				'colors' => array(
					'#E21E51',
					'#4d40ff',
					'#ffffff',
					'#040037',
				),
			),
			array(
				'palette' => 'green',
				'colors' => array(
					'#049f82',
					'#008f72',
					'#222222',
					'#ffffff',
				),
			),
			array(
				'palette' => 'fire',
				'colors' => array(
					'#dd6b20',
					'#cf3033',
					'#27241d',
					'#ffffff',
				),
			),
		);
		wp_enqueue_style( 'kadence-starter-templates', KADENCE_STARTER_TEMPLATES_URL . 'assets/css/starter-templates.css', array( 'wp-components' ), KADENCE_STARTER_TEMPLATES_VERSION );
		wp_enqueue_script( 'kadence-starter-templates', KADENCE_STARTER_TEMPLATES_URL . 'assets/js/starter-templates.js', array( 'jquery', 'wp-i18n', 'wp-element', 'wp-plugins', 'wp-components', 'wp-api', 'wp-hooks', 'wp-edit-post', 'lodash', 'wp-block-library', 'wp-block-editor', 'wp-editor' ), KADENCE_STARTER_TEMPLATES_VERSION, true );
		wp_localize_script(
			'kadence-starter-templates',
			'kadenceStarterParams',
			array(
				'ajax_url'             => admin_url( 'admin-ajax.php' ),
				'ajax_nonce'           => wp_create_nonce( 'kadence-ajax-verification' ),
				'pro'                  => false,
				'isKadence'            => class_exists( 'Kadence\Theme' ),
				'templates'            => $templates,
				'palettes'             => $palettes,
				'has_content'          => ( 1 < wp_count_posts()->publish ? true : false ),
				'notice'               => esc_html__( 'Please Note: This importer is designed for new/empty sites with no content.', 'kadence-starter-templates' ),
				'plugin_progress'      => esc_html__( 'Checking/Installing/Activating Required Plugins', 'kadence-starter-templates' ),
				'content_progress'     => esc_html__( 'Importing Demo Content...', 'kadence-starter-templates' ),
				'content_new_progress' => esc_html__( 'Importing Demo Content... Still Importing.', 'kadence-starter-templates' ),
				'widget_progress'      => esc_html__( 'Importing Menus/Widgets...', 'kadence-starter-templates' ),
				'customizer_progress'  => esc_html__( 'Importing Customizer Settings...', 'kadence-starter-templates' ),
			)
		);
	}
	/**
	 * AJAX callback to install a plugin.
	 */
	public function install_plugins_ajax_callback() {
		Helpers::verify_ajax_call();

		if ( ! current_user_can( 'install_plugins' ) || ! isset( $_POST['selected'] ) ) {
			wp_send_json_error();
		}
		// Get selected file index or set it to 0.
		$selected_index = empty( $_POST['selected'] ) ? 0 : absint( $_POST['selected'] );
		$info = $this->import_files[ $selected_index ];
		$install = true;

		if ( isset( $info['plugins'] ) && ! empty( $info['plugins'] ) ) {

			if ( ! function_exists( 'plugins_api' ) ) {
				require_once( ABSPATH . 'wp-admin/includes/plugin-install.php' );
			}
			if ( ! class_exists( 'WP_Upgrader' ) ) {
				require_once( ABSPATH . 'wp-admin/includes/class-wp-upgrader.php' );
			}

			foreach( $info['plugins'] as $key => $plugin ) {
				if ( 'notactive' === $plugin['state'] && 'thirdparty' !== $plugin['src'] ) {
					$api = plugins_api(
						'plugin_information',
						array(
							'slug' => $plugin['base'],
							'fields' => array(
								'short_description' => false,
								'sections' => false,
								'requires' => false,
								'rating' => false,
								'ratings' => false,
								'downloaded' => false,
								'last_updated' => false,
								'added' => false,
								'tags' => false,
								'compatibility' => false,
								'homepage' => false,
								'donate_link' => false,
							),
						)
					);
					if ( ! is_wp_error( $api ) ) {

						// Use AJAX upgrader skin instead of plugin installer skin.
						// ref: function wp_ajax_install_plugin().
						$upgrader = new \Plugin_Upgrader( new \WP_Ajax_Upgrader_Skin() );

						$installed = $upgrader->install( $api->download_link );
						if ( $installed ) {
							$activate = activate_plugin( $plugin['path'], '', false, true );
							if ( is_wp_error( $activate ) ) {
								$install = false;
							}
						} else {
							$install = false;
						}
					} else {
						$install = false;
					}
				} elseif ( 'installed' === $plugin['state'] ) {
					$activate = activate_plugin( $plugin['path'], '', false, true );
					if ( is_wp_error( $activate ) ) {
						$install = false;
					}
				}
			}
		}

		if ( false === $install ) {
			wp_send_json_error();
		} else {
			wp_send_json( array( 'status' => 'pluginSuccess' ) );
		}
	}

	/**
	 * Main AJAX callback function for:
	 * 1). prepare import files (uploaded or predefined via filters)
	 * 2). execute 'before content import' actions (before import WP action)
	 * 3). import content
	 * 4). execute 'after content import' actions (before widget import WP action, widget import, customizer import, after import WP action)
	 */
	public function import_demo_data_ajax_callback() {
		// Try to update PHP memory limit (so that it does not run out of it).
		ini_set( 'memory_limit', apply_filters( 'kadence-starter-templates/import_memory_limit', '350M' ) );

		// Verify if the AJAX call is valid (checks nonce and current_user_can).
		Helpers::verify_ajax_call();

		// Is this a new AJAX call to continue the previous import?
		$use_existing_importer_data = $this->use_existing_importer_data();

		if ( ! $use_existing_importer_data ) {
			// Create a date and time string to use for demo and log file names.
			Helpers::set_demo_import_start_time();

			// Define log file path.
			$this->log_file_path = Helpers::get_log_path();

			// Get selected file index or set it to 0.
			$this->selected_index = empty( $_POST['selected'] ) ? 0 : absint( $_POST['selected'] );
			$this->selected_palette = empty( $_POST['palette'] ) ? '' : sanitize_text_field( $_POST['palette'] );
			/**
			 * 1). Prepare import files.
			 * Predefined import files via filter: kadence-starter-templates/import_files
			 */
			if ( ! empty( $this->import_files[ $this->selected_index ] ) ) { // Use predefined import files from wp filter: kadence-starter-templates/import_files.

				// Download the import files (content, widgets and customizer files).
				$this->selected_import_files = Helpers::download_import_files( $this->import_files[ $this->selected_index ] );

				// Check Errors.
				if ( is_wp_error( $this->selected_import_files ) ) {
					// Write error to log file and send an AJAX response with the error.
					Helpers::log_error_and_send_ajax_response(
						$this->selected_import_files->get_error_message(),
						$this->log_file_path,
						esc_html__( 'Downloaded files', 'kadence-starter-templates' )
					);
				}

				// Add this message to log file.
				$log_added = Helpers::append_to_file(
					sprintf(
						__( 'The import files for: %s were successfully downloaded!', 'kadence-starter-templates' ),
						$this->import_files[ $this->selected_index ]['import_file_name']
					) . Helpers::import_file_info( $this->selected_import_files ),
					$this->log_file_path,
					esc_html__( 'Downloaded files' , 'kadence-starter-templates' )
				);
			} else {
				// Send JSON Error response to the AJAX call.
				wp_send_json( esc_html__( 'No import files specified!', 'kadence-starter-templates' ) );
			}
		}

		// Save the initial import data as a transient, so other import parts (in new AJAX calls) can use that data.
		Helpers::set_import_data_transient( $this->get_current_importer_data() );

		if ( ! $this->before_import_executed ) {
			$this->before_import_executed = true;

			/**
			 * 2). Execute the actions hooked to the 'kadence-starter-templates/before_content_import_execution' action:
			 *
			 * Default actions:
			 * 1 - Before content import WP action (with priority 10).
			 */
			/**
			 * Clean up default contents.
			 */
			wp_delete_post( 1, true ); // Hello World.
			wp_delete_post( 2, true ); // Sample Page.
			wp_delete_comment( 1, true ); // WordPress comment.
			do_action( 'kadence-starter-templates/before_content_import_execution', $this->selected_import_files, $this->import_files, $this->selected_index, $this->selected_palette );
		}

		/**
		 * 3). Import content (if the content XML file is set for this import).
		 * Returns any errors greater then the "warning" logger level, that will be displayed on front page.
		 */
		if ( ! empty( $this->selected_import_files['content'] ) ) {
			$this->append_to_frontend_error_messages( $this->importer->import_content( $this->selected_import_files['content'] ) );
		}

		/**
		 * 4). Execute the actions hooked to the 'kadence-starter-templates/after_content_import_execution' action:
		 *
		 * Default actions:
		 * 1 - Before widgets import setup (with priority 10).
		 * 2 - Import widgets (with priority 20).
		 * 3 - Import Redux data (with priority 30).
		 */
		do_action( 'kadence-starter-templates/after_content_import_execution', $this->selected_import_files, $this->import_files, $this->selected_index, $this->selected_palette );

		// Save the import data as a transient, so other import parts (in new AJAX calls) can use that data.
		Helpers::set_import_data_transient( $this->get_current_importer_data() );

		// Request the customizer import AJAX call.
		if ( ! empty( $this->selected_import_files['customizer'] ) ) {
			wp_send_json( array( 'status' => 'customizerAJAX' ) );
		}

		// Request the after all import AJAX call.
		if ( false !== has_action( 'kadence-starter-templates/after_all_import_execution' ) ) {
			wp_send_json( array( 'status' => 'afterAllImportAJAX' ) );
		}

		// Send a JSON response with final report.
		$this->final_response();
	}


	/**
	 * AJAX callback for importing the customizer data.
	 * This request has the wp_customize set to 'on', so that the customizer hooks can be called
	 * (they can only be called with the $wp_customize instance). But if the $wp_customize is defined,
	 * then the widgets do not import correctly, that's why the customizer import has its own AJAX call.
	 */
	public function import_customizer_data_ajax_callback() {
		// Verify if the AJAX call is valid (checks nonce and current_user_can).
		Helpers::verify_ajax_call();

		// Get existing import data.
		if ( $this->use_existing_importer_data() ) {
			/**
			 * Execute the customizer import actions.
			 *
			 * Default actions:
			 * 1 - Customizer import (with priority 10).
			 */
			do_action( 'kadence-starter-templates/customizer_import_execution', $this->selected_import_files );
		}

		// Request the after all import AJAX call.
		if ( false !== has_action( 'kadence-starter-templates/after_all_import_execution' ) ) {
			wp_send_json( array( 'status' => 'afterAllImportAJAX' ) );
		}

		// Send a JSON response with final report.
		$this->final_response();
	}


	/**
	 * AJAX callback for the after all import action.
	 */
	public function after_all_import_data_ajax_callback() {
		// Verify if the AJAX call is valid (checks nonce and current_user_can).
		Helpers::verify_ajax_call();

		// Get existing import data.
		if ( $this->use_existing_importer_data() ) {
			/**
			 * Execute the after all import actions.
			 *
			 * Default actions:
			 * 1 - after_import action (with priority 10).
			 */
			do_action( 'kadence-starter-templates/after_all_import_execution', $this->selected_import_files, $this->import_files, $this->selected_index, $this->selected_palette );
		}

		// Send a JSON response with final report.
		$this->final_response();
	}


	/**
	 * Send a JSON response with final report.
	 */
	private function final_response() {
		// Delete importer data transient for current import.
		delete_transient( 'kadence_importer_data' );

		// Display final messages (success or error messages).
		if ( empty( $this->frontend_error_messages ) ) {
			$response['message'] = '';

			$response['message'] .= sprintf(
				__( '%1$sFinished! View your site%2$s', 'kadence-starter-templates' ),
				'<div class="finshed-notice-success"><p><a href="'. esc_url( home_url( '/' ) ) . '" class="button-primary button kadence-starter-templates-finish-button">',
				'</a></p></div>'
			);
		} else {
			$response['message'] = $this->frontend_error_messages_display() . '<br>';
			$response['message'] .= sprintf(
				__( '%1$sThe demo import has finished, but there were some import errors.%2$sMore details about the errors can be found in this %3$s%5$slog file%6$s%4$s%7$s', 'kadence-starter-templates' ),
				'<div class="notice  notice-warning"><p>',
				'<br>',
				'<strong>',
				'</strong>',
				'<a href="' . Helpers::get_log_url( $this->log_file_path ) .'" target="_blank">',
				'</a>',
				'</p></div>'
			);
		}

		wp_send_json( $response );
	}


	/**
	 * Get content importer data, so we can continue the import with this new AJAX request.
	 *
	 * @return boolean
	 */
	private function use_existing_importer_data() {
		if ( $data = get_transient( 'kadence_importer_data' ) ) {
			$this->frontend_error_messages = empty( $data['frontend_error_messages'] ) ? array() : $data['frontend_error_messages'];
			$this->log_file_path           = empty( $data['log_file_path'] ) ? '' : $data['log_file_path'];
			$this->selected_index          = empty( $data['selected_index'] ) ? 0 : $data['selected_index'];
			$this->selected_palette        = empty( $data['selected_palette'] ) ? '' : $data['selected_palette'];
			$this->selected_import_files   = empty( $data['selected_import_files'] ) ? array() : $data['selected_import_files'];
			$this->import_files            = empty( $data['import_files'] ) ? array() : $data['import_files'];
			$this->before_import_executed  = empty( $data['before_import_executed'] ) ? false : $data['before_import_executed'];
			$this->importer->set_importer_data( $data );

			return true;
		}
		return false;
	}


	/**
	 * Get the current state of selected data.
	 *
	 * @return array
	 */
	public function get_current_importer_data() {
		return array(
			'frontend_error_messages' => $this->frontend_error_messages,
			'log_file_path'           => $this->log_file_path,
			'selected_index'          => $this->selected_index,
			'selected_palette'        => $this->selected_palette,
			'selected_import_files'   => $this->selected_import_files,
			'import_files'            => $this->import_files,
			'before_import_executed'  => $this->before_import_executed,
		);
	}


	/**
	 * Getter function to retrieve the private log_file_path value.
	 *
	 * @return string The log_file_path value.
	 */
	public function get_log_file_path() {
		return $this->log_file_path;
	}


	/**
	 * Setter function to append additional value to the private frontend_error_messages value.
	 *
	 * @param string $additional_value The additional value that will be appended to the existing frontend_error_messages.
	 */
	public function append_to_frontend_error_messages( $text ) {
		$lines = array();

		if ( ! empty( $text ) ) {
			$text = str_replace( '<br>', PHP_EOL, $text );
			$lines = explode( PHP_EOL, $text );
		}

		foreach ( $lines as $line ) {
			if ( ! empty( $line ) && ! in_array( $line , $this->frontend_error_messages ) ) {
				$this->frontend_error_messages[] = $line;
			}
		}
	}


	/**
	 * Display the frontend error messages.
	 *
	 * @return string Text with HTML markup.
	 */
	public function frontend_error_messages_display() {
		$output = '';

		if ( ! empty( $this->frontend_error_messages ) ) {
			foreach ( $this->frontend_error_messages as $line ) {
				$output .= esc_html( $line );
				$output .= '<br>';
			}
		}

		return $output;
	}


	/**
	 * Load the plugin textdomain, so that translations can be made.
	 */
	public function load_textdomain() {
		load_plugin_textdomain( 'kadence-starter-templates', false, plugin_basename( dirname( dirname( __FILE__ ) ) ) . '/languages' );
	}


	/**
	 * Get data from filters, after the theme has loaded and instantiate the importer.
	 */
	public function setup_plugin_with_filter_data() {
		if ( ! ( is_admin() || ( defined( 'WP_CLI' ) && WP_CLI ) ) ) {
			return;
		}

		// Get info of import data files and filter it.
		$this->import_files = Helpers::validate_import_file_info( apply_filters( 'kadence-starter-templates/import_files', array() ) );

		/**
		 * Register all default actions (before content import, widget, customizer import and other actions)
		 * to the 'before_content_import_execution' and the 'kadence-starter-templates/after_content_import_execution' action hook.
		 */
		$import_actions = new ImportActions();
		$import_actions->register_hooks();

		// Importer options array.
		$importer_options = apply_filters( 'kadence-starter-templates/importer_options', array(
			'fetch_attachments' => true,
		) );

		// Logger options for the logger used in the importer.
		$logger_options = apply_filters( 'kadence-starter-templates/logger_options', array(
			'logger_min_level' => 'warning',
		) );

		// Configure logger instance and set it to the importer.
		$logger            = new Logger();
		$logger->min_level = $logger_options['logger_min_level'];

		// Create importer instance with proper parameters.
		$this->importer = new Importer( $importer_options, $logger );
	}
}
Starter_Templates::get_instance();
