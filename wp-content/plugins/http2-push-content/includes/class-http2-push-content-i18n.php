<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       piwebsolution.com
 * @since      1.0.0
 *
 * @package    Http2_Push_Content
 * @subpackage Http2_Push_Content/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Http2_Push_Content
 * @subpackage Http2_Push_Content/includes
 * @author     piwebsolution <rajeshsingh520@gmail.com>
 */
class Http2_Push_Content_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'http2-push-content',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
