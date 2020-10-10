<?php
/**
 * Plugin Name: Elementor Blocks for Gutenberg
 * Description: Embed Elementor templates inside Gutenberg
 * Plugin URI: https://elementor.com/?utm_source=block-builder&utm_campaign=plugin-uri&utm_medium=wp-dash
 * Version: 1.0.1
 * Author: Elementor.com
 * Author URI: https://elementor.com/?utm_source=block-builder&utm_campaign=author-uri&utm_medium=wp-dash
 * Text Domain: block-builder
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

define( 'ELEMENTOR_BLOCK_BUILDER_VERSION', '1.0.1' );

define( 'BLOCK_BUILDER_PATH', plugin_dir_path( __FILE__ ) );
define( 'BLOCK_BUILDER_URL', plugins_url( '/', __FILE__ ) );
define( 'BLOCK_BUILDER_ASSETS_URL', BLOCK_BUILDER_URL . 'assets/' );

/**
 * Main Block Builder Class
 *
 */
final class Elementor_Block_Builder {

	/**
	 * Minimum Elementor Version
	 *
	 * @var string Minimum Elementor version required to run the plugin.
	 */
	const MINIMUM_ELEMENTOR_VERSION = '2.0.0';

	/**
	 * Minimum PHP Version
	 *
	 * @var string Minimum PHP version required to run the plugin.
	 */
	const MINIMUM_PHP_VERSION = '5.4';

	/**
	 * Constructor
	 *
	 * @access public
	 */
	public function __construct() {
		// Load translation
		add_action( 'init', array( $this, 'i18n' ) );

		// Init Plugin
		add_action( 'plugins_loaded', array( $this, 'init' ) );
	}

	/**
	 * Load Textdomain
	 *
	 * Load plugin localization files.
	 * Fired by `init` action hook.
	 *
	 * @access public
	 */
	public function i18n() {
		load_plugin_textdomain( 'block-builder' );
	}

	/**
	 * Initialize the plugin
	 *
	 * Validates that Elementor is already loaded.
	 * Checks for basic plugin requirements, if one check fail don't continue,
	 * if all check have passed include the plugin class.
	 *
	 * Fired by `plugins_loaded` action hook.
	 *
	 * @since 1.2.0
	 * @access public
	 */
	public function init() {
		// Check for required PHP version
		if ( version_compare( PHP_VERSION, self::MINIMUM_PHP_VERSION, '<' ) ) {
			add_action( 'admin_notices', array( $this, 'admin_notice_minimum_php_version' ) );
			return;
		}

		// Check if Gutenberg installed and activated
		if ( ! $this->is_gutenberg_active() ) {
			add_action( 'admin_notices', array( $this, 'admin_notice_missing_gutenberg_plugin' ) );
			return;
		}

		// Check if Elementor installed and activated
		if ( ! did_action( 'elementor/loaded' ) ) {
			add_action( 'admin_notices', array( $this, 'admin_notice_missing_elementor_plugin' ) );
			return;
		}

		// Check for required Elementor version
		if ( ! version_compare( ELEMENTOR_VERSION, self::MINIMUM_ELEMENTOR_VERSION, '>=' ) ) {
			add_action( 'admin_notices', array( $this, 'admin_notice_minimum_elementor_version' ) );
			return;
		}

		// Once we get here, We have passed all validation checks so we can safely include our plugin
		require_once 'plugin.php';
	}

	/**
	 * Admin notice
	 *
	 * Warning when the site doesn't have Minimum Required PHP version.
	 * @access public
	 */
	public function admin_notice_minimum_php_version() {
		if ( isset( $_GET['activate'] ) ) {
			unset( $_GET['activate'] );
		}

		$message = sprintf(
			/* translators: 1: Plugin name 2: PHP 3: Required PHP version */
			esc_html__( '%1$s requires %2$s version %3$s or greater.', 'block-builder' ),
			'<strong>' . esc_html__( 'Elementor Blocks for Gutenberg', 'block-builder' ) . '</strong>',
			'<strong>' . esc_html__( 'PHP', 'block-builder' ) . '</strong>',
			self::MINIMUM_PHP_VERSION
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );

	}

	/**
	 * Admin notice
	 *
	 * Warning when the site doesn't have Gutenberg installed or activated.
	 * @access public
	 */
	public function admin_notice_missing_gutenberg_plugin() {
		if ( ! current_user_can( 'update_plugins' ) ) {
			return;
		}

		$screen = get_current_screen();
		if ( isset( $screen->parent_file ) && 'plugins.php' === $screen->parent_file && 'update' === $screen->id ) {
			return;
		}

		$plugin_utils = $this->get_plugin_action_utils();
		$plugin_utils->get_plugin_missing_notice( __( 'Gutenberg', 'block-builder' ), 'gutenberg' );
	}

	/**
	 * Admin notice
	 *
	 * Warning when the site doesn't have Elementor installed or activated.
	 * @access public
	 */
	public function admin_notice_missing_elementor_plugin() {
		if ( ! current_user_can( 'update_plugins' ) ) {
			return;
		}

		$screen = get_current_screen();
		if ( isset( $screen->parent_file ) && 'plugins.php' === $screen->parent_file && 'update' === $screen->id ) {
			return;
		}

		$plugin_utils = $this->get_plugin_action_utils();
		$plugin_utils->get_plugin_missing_notice( __( 'Elementor', 'block-builder' ), 'elementor' );
	}

	/**
	 * Admin notice
	 *
	 * Warning when the site doesn't have a minimum required Elementor version.
	 * @access public
	 */
	public function admin_notice_minimum_elementor_version() {
		$message = sprintf(
			/* translators: 1: Plugin name 2: Elementor 3: Required Elementor version */
			esc_html__( '%1$s requires %2$s version %3$s or greater.', 'block-builder' ),
			'<strong>' . esc_html__( 'Elementor Blocks for Gutenberg', 'block-builder' ) . '</strong>',
			'<strong>' . esc_html__( 'Elementor', 'block-builder' ) . '</strong>',
			self::MINIMUM_ELEMENTOR_VERSION
		);
		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
	}

	public function get_plugin_action_utils() {
		static $plugin_utils = null;

		if ( null === $plugin_utils ) {
			include_once BLOCK_BUILDER_PATH . '/classes/dependency-installer.php';
			$plugin_utils = new Dependency_Installer();
		}

		return $plugin_utils;
	}

	public function is_gutenberg_active() {
		return function_exists( 'register_block_type' );
	}
}
// Instantiate Elementor_Block_Builder.
new Elementor_Block_Builder();
