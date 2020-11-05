<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://www.feataholic.com
 * @since      1.1.8 Free
 *
 * @package    MZLDR
 * @subpackage MZLDR/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.1.8 Free
 * @package    MZLDR
 * @subpackage MZLDR/includes
 * @author     Your Name <email@example.com>
 */
class MZLDR {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      MZLDR    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'MZLDR_VERSION' ) ) {
			$this->version = MZLDR_VERSION;
		} else {
			$this->version = MZLDR_Helper::getVersion();
		}
		$this->plugin_name = 'maz-loader';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - MZLDR. Orchestrates the hooks of the plugin.
	 * - MZLDR_i18n. Defines internationalization functionality.
	 * - MZLDR_Admin. Defines all hooks for the admin area.
	 * - MZLDR_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {
		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-maz-loader-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-maz-loader-i18n.php';

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-maz-loader-admin-notice.php';

		// Settings page
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-maz-loader-admin-settings.php';

		// Maz Loader - Helpers
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/helpers/class-maz-loader-animations-helper.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/helpers/class-maz-loader-fields-helper.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/helpers/class-maz-loader-registry.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/helpers/class-maz-loader-publishing-rules.php';
		
		
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/helpers/publishing_rules/class-maz-loader-homepage-rule.php';
		
		// Maz Loader - Models
		require_once MZLDR_ADMIN_PATH . 'models/loader_model.php';
		require_once MZLDR_ADMIN_PATH . 'models/impression_model.php';
		require_once MZLDR_ADMIN_PATH . 'models/statistics_model.php';
		
		// Maz Loader - Tables
		require_once MZLDR_ADMIN_PATH . 'tables/class-mzldr-list-table.php';

		// block
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/blocks/class-maz-loader-block.php';
		
		// API
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/api/class-maz-loader-api.php';
		
		// Maz Loader - Extra libraries that need to be included
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-maz-loader-wp-helper.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-maz-loader-ajax-response.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-maz-loader-constants.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/forms/class-maz-loader-forms.php';
		
		// shortcode
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-maz-loader-shortcode.php';

		// Maz Loader - Fields
		require_once MZLDR_ADMIN_PATH . 'models/fields/field.php';
		require_once MZLDR_ADMIN_PATH . 'models/fields/text.php';
		require_once MZLDR_ADMIN_PATH . 'models/fields/image.php';
		require_once MZLDR_ADMIN_PATH . 'models/fields/icon.php';
		

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-maz-loader-admin.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/controllers/class-maz-loader-form-controller.php';
		

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-maz-loader-public.php';

		$this->loader = new MZLDR_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the MZLDR_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {
		$plugin_i18n = new MZLDR_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );
	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {
		$plugin_admin  			= new MZLDR_Admin( $this->get_plugin_name(), $this->get_version() );
		$plugin_admin_settings  = new MZLDR_Admin_Settings();
		$loader_block			= new MZLDR_Loader_Block();
		$admin_notices 			= new MZLDR_Admin_Notice();

		
		$this->loader->add_action( 'plugin_action_links_' . MZLDR_PLG_BASENAME, $plugin_admin, 'plugin_action_links' );
		

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

		// success admin notices
		$this->loader->add_action( 'mzldr_notices_hook', $admin_notices, 'maz_loader_admin_notice_handle' );

		// Run first on admin init
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'admin_page_init' );

		// Add the Main Menu Item
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'add_menu_pages' );

		// Add Settings Page
		$this->loader->add_action( 'admin_init', $plugin_admin_settings, 'add_settings_page' );

		

		if (MZLDR_Loader_Block::canRender()) {
			// loader block
			$this->loader->add_action( 'enqueue_block_editor_assets', $loader_block, 'register_block_script' );
			$this->loader->add_filter( 'block_categories', $loader_block, 'register_block_category', 10, 2 );
			$this->loader->add_action( 'init', $loader_block, 'register_block' );
		}

		// Submit New / Edit Loader
		$plugin_admin_form_controller = new MZLDR_Form_Controller();
		$this->loader->add_action( 'admin_post_maz_loader_submission', $plugin_admin_form_controller, 'maz_loader_submission_response' );
		// Preview Ajax call
		$this->loader->add_action( 'wp_ajax_preview_maz_loader', $plugin_admin_form_controller, 'maz_loader_preview_response' );
		$this->loader->add_action( 'wp_ajax_nopriv_preview_maz_loader', $plugin_admin_form_controller, 'maz_loader_preview_response' );

		

		// api
		$api = new MZLDR_API();
		$api->run();

		// Rate reminder actions.
		$this->loader->add_action( 'activated_plugin', $plugin_admin, 'set_rate_reminder' );
		$this->loader->add_action( 'admin_notices', $plugin_admin, 'show_rate_reminder' );
		$this->loader->add_action( 'upgrader_process_complete', $plugin_admin, 'set_update_rate_reminder', 1, 2 );
		$this->loader->add_action( 'wp_ajax_mzldr_update_rate_reminder', $plugin_admin, 'mzldr_update_rate_reminder' );
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {
		// Do not run the front end scripts on backend
		if ( is_admin() )
		{
			return;
		}
		
		$public_loader = new MZLDR_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $public_loader, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $public_loader, 'enqueue_scripts' );
		
		// Render the loaders
		// try: wp_head
		$this->loader->add_action( 'wp_footer', $public_loader, 'render' );

		// shortcode
		$shortcode = new MZLDR_Shortcode();
		$this->loader->add_shortcode( 'mzldr', $shortcode, 'mzldr_handle' );
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		 $this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		 return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    MZLDR    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		 return $this->version;
	}
}
