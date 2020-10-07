<?php
namespace ElementorBlockBuilder;

use Elementor\User;
use ElementorBlockBuilder\Blocks;

/**
 * Class Plugin
 *
 * Main Plugin class
 */
class Plugin {
	/**
	 * Instance
	 *
	 * @access private
	 * @static
	 *
	 * @var Plugin The single instance of the class.
	 */
	public static $instance = null;

	/**
	 * Blocks
	 * @access protected
	 * @var array of blocks
	 */
	protected $blocks = [];

	/**
	 * class aliases
	 * @access private
	 * @var array
	 */
	private $classes_aliases = [];

	/**
	 * Instance
	 *
	 * Ensures only one instance of the class is loaded or can be loaded.
	 *
	 * @access public
	 *
	 * @return Plugin An instance of the class.
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	private function add_block( $block ) {
		$this->blocks[ $block->get_name() ] = $block;
	}

	public function get_blocks( $block = null ) {
		if ( isset( $this->blocks[ $block ] ) ) {
			return $this->blocks[ $block ];
		}

		return $this->blocks;
	}

	/**
	 * @param $template
	 *
	 * @access public
	 * @return string
	 */
	public function template_include( $template ) {
		if ( isset( $_REQUEST['elementor-block'] ) && User::is_current_user_can_edit() ) {
			add_filter( 'show_admin_bar', '__return_false' );
			//Compatibility for some Themes
			remove_action( 'wp_head', '_admin_bar_bump_cb' );
			return ELEMENTOR_PATH . 'modules/page-templates/templates/canvas.php';
		}

		return $template;
	}

	public function register_blocks() {
		include_once BLOCK_BUILDER_PATH . '/blocks/template-block.php';

		$this->add_block( new Blocks\Template_Block() );
	}

	public function autoload( $class ) {
		if ( 0 !== strpos( $class, __NAMESPACE__ ) ) {
			return;
		}

		$has_class_alias = isset( $this->classes_aliases[ $class ] );

		// Backward Compatibility: Save old class name for set an alias after the new class is loaded
		if ( $has_class_alias ) {
			$class_alias_name = $this->classes_aliases[ $class ];
			$class_to_load = $class_alias_name;
		} else {
			$class_to_load = $class;
		}

		if ( ! class_exists( $class_to_load ) ) {
			$filename = strtolower(
				preg_replace(
					[ '/^' . __NAMESPACE__ . '\\\/', '/([a-z])([A-Z])/', '/_/', '/\\\/' ],
					[ '', '$1-$2', '-', DIRECTORY_SEPARATOR ],
					$class_to_load
				)
			);
			$filename = BLOCK_BUILDER_PATH . $filename . '.php';

			if ( is_readable( $filename ) ) {
				include $filename;
			}
		}

		if ( $has_class_alias ) {
			class_alias( $class_alias_name, $class );
		}
	}

	public static function elementor() {
		return \Elementor\Plugin::$instance;
	}

	/**
	 *  Plugin class constructor
	 *
	 * Register plugin action hooks and filters
	 *
	 * @access public
	 */
	public function __construct() {
		spl_autoload_register( [ $this, 'autoload' ] );

		add_filter( 'template_include', [ $this, 'template_include' ], 12 /* After WooCommerce & Elementor Pro - Locations Manager */ );
		add_action( 'init', [ $this, 'register_blocks' ] );
	}
}
// Instantiate Plugin Class
Plugin::instance();
