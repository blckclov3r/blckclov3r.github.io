<?php

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );


if ( !is_admin() ) { // Front-End

	// Option caller
	function cstm_cds_pull_option($setting_name, $default_value) {
		$option = get_option( $setting_name, $default_value );
		return !isset($option) || $option == "" ? $default_value : $option;
	}


	// RELEASE CUSTOM CSS AND JS FILES
	if ( !function_exists('cstm_cds_public_codes') ) {

		function cstm_cds_public_codes() {

			// CSS File
			if( file_exists(WP_CONTENT_DIR .'/custom_codes/custom_public.css') )
				wp_enqueue_style( 'cstm-cds-css', WP_CONTENT_URL .'/custom_codes/custom_public.css', array(), cstm_cds_pull_option('cstm_cds_css_save_count', 0) );

			// Call jQuery
			wp_enqueue_script('jquery');

			// JS File Head
			if( file_exists(WP_CONTENT_DIR .'/custom_codes/custom_public_head.js') )
				wp_enqueue_script( 'cstm-cds-head-js', WP_CONTENT_URL .'/custom_codes/custom_public_head.js', array( 'jquery' ), cstm_cds_pull_option('cstm_cds_js_head_save_count', 0));

			// JS File Bottom
			if( file_exists(WP_CONTENT_DIR .'/custom_codes/custom_public.js') )
				wp_enqueue_script( 'cstm-cds-bottom-js', WP_CONTENT_URL .'/custom_codes/custom_public.js', array( 'jquery' ), cstm_cds_pull_option('cstm_cds_js_bottom_save_count', 0), true);

		}
		add_action( 'wp_enqueue_scripts', 'cstm_cds_public_codes', 99999 );

	}


} elseif ( is_admin() ) { // Back-End


	// RELEASE ADMIN CUSTOM CSS AND JS FILES
	if ( !function_exists('cstm_cds_admin_panel_codes') ) {

		function cstm_cds_admin_panel_codes() {

			$cstm_cds_admin_active = false;
			foreach ( cstm_cds_pull_option( 'cstm_cds_admin_roles', array() ) as $cstm_cds_role ) {

				if ( current_user_can($cstm_cds_role) ) {
					$cstm_cds_admin_active = true;
					break;
				}

			}


			if ( !current_user_can('cstm_cds_admin') && $cstm_cds_admin_active ) {

				// CSS File
				if( file_exists(WP_CONTENT_DIR .'/custom_codes/admin_panel.css') )
					wp_enqueue_style( 'cstm-cds-admin-css', WP_CONTENT_URL .'/custom_codes/admin_panel.css', array(), cstm_cds_pull_option('cstm_cds_admin_css_save_count', 0) );

				// Call jQuery
				wp_enqueue_script('jquery');

				// JS File Head
				if( file_exists(WP_CONTENT_DIR .'/custom_codes/admin_panel_head.js') )
					wp_enqueue_script( 'cstm-cds-admin-head-js', WP_CONTENT_URL .'/custom_codes/admin_panel_head.js', array( 'jquery' ), cstm_cds_pull_option('cstm_cds_admin_js_head_save_count', 0));

				// JS File Bottom
				if( file_exists(WP_CONTENT_DIR .'/custom_codes/admin_panel.js') )
					wp_enqueue_script( 'cstm-cds-admin-bottom-js', WP_CONTENT_URL .'/custom_codes/admin_panel.js', array( 'jquery' ), cstm_cds_pull_option('cstm_cds_admin_js_bottom_save_count', 0), true);

			}

		}
		add_action( 'admin_enqueue_scripts', 'cstm_cds_admin_panel_codes', 99999 );

	}


}


// RELEASE CUSTOM functions.php
if ( !function_exists('cstm_cds_include_custom_functions') ) {

	function cstm_cds_include_custom_functions() {


		if(file_exists(WP_CONTENT_DIR .'/custom_codes/admin_functions.php')) {

			error_reporting(0);
			require( WP_CONTENT_DIR .'/custom_codes/admin_functions.php' );

		}

	}
	cstm_cds_include_custom_functions();

}
?>