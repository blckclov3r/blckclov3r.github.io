<?php
/**
 * Shift8 CDN Rewrite Class
 *
 * Function to load styles and front end scripts
 *
 */

if ( !defined( 'ABSPATH' ) ) {
    die();
}

// Register admin scripts for custom fields
function load_shift8_cdn_wp_admin_style() {
        // admin always last
        wp_enqueue_style( 'shift8_cdn_css', plugin_dir_url(dirname(__FILE__)) . 'css/shift8_cdn_admin.css', array(), '3.0' );
        wp_enqueue_script( 'shift8_cdn_script', plugin_dir_url(dirname(__FILE__)) . 'js/shift8_cdn_admin.js', array(), '2.6' );

        wp_localize_script( 'shift8_cdn_script', 'the_ajax_script', array( 
            'ajaxurl' => admin_url( 'admin-ajax.php' ),
            'nonce' => wp_create_nonce( "shift8_cdn_response_nonce"),
        ));  
}
add_action( 'admin_enqueue_scripts', 'load_shift8_cdn_wp_admin_style' );
