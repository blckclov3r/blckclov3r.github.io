<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://www.feataholic.com
 * @since      1.1.8 Free
 *
 * @package    MZLDR
 * @subpackage MZLDR/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    MZLDR
 * @subpackage MZLDR/public
 * @author     Your Name <email@example.com>
 */
class MZLDR_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @var  string  $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @var  string  $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * The Loader Model
	 *
	 * @var class $loader_model
	 */
	private $loader_model;

	/**
	 * The Impression Model
	 *
	 * @var class $impression_model
	 */
	private $impression_model;

	/**
	 * Initialize the class and set its properties.
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->loader_model = new MZLDR_Loader_Model();
		$this->impression_model = new MZLDR_Impression_Model();
	}
	
	/**
	 * Renders the loader
	 *
	 * @return  string
	 */
	public function render() {
		$passed_loaders = [];
		
		
		if (MZLDR_Helper::isFree()) {
			/**
			 * Check if we have enabled to show the last enabled loader globally
			 * If not, return false
			 */
			$mzldr_options = get_option( 'mzldr_settings_options' );
			// Check if we have the loader_display enabled first	
			$mzldr_settings_field_loader_display = isset($mzldr_options['mzldr_settings_field_loader_display']) ? $mzldr_options['mzldr_settings_field_loader_display'] : '';
			$mzldr_settings_field_loader_display = ($mzldr_settings_field_loader_display == 'enable') ? true : false;
			if (!$mzldr_settings_field_loader_display) {
				return false;
			}
			$loader = $this->loader_model->getLatestLoader();
			$homepage_rule = new MZLDR_Homepage_Rule([]);
			if (!$homepage_rule->freeCanPass($loader)) {
				return false;
			}
			$loader = $loader[0];
			$passed_loaders[] = $loader;
			if (count((array) $loader)) {
				$this->impression_model->checkBeforeTrack($loader);
			}
		}
		

		
		

		$loaders = $passed_loaders;
		require MZLDR_PUBLIC_PATH . 'partials/loader/tmpl.php';
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @return  void
	 */
	public function enqueue_styles() {
		// Load Google Font
		wp_enqueue_style( $this->plugin_name . '-mazloader-google-font', 'https://fonts.googleapis.com/css?family=Poppins:400,700&display=swap', false );

		// Load MAZ Loader CSS
		wp_enqueue_style( $this->plugin_name, MZLDR_PUBLIC_MEDIA_URL . 'css/mazloader.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @return  void
	 */
	public function enqueue_scripts() {
		 // Load MAZ Loader JS
		wp_enqueue_script( $this->plugin_name, MZLDR_PUBLIC_MEDIA_URL . 'js/mazloader.js', array( 'jquery' ), $this->version, false );
	}

}
