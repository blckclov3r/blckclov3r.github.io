<?php
/**
 * Plugin Name: Testimonial  Builder
 * Version: 1.5.4
 * Description:  Testimonial Builder is most flexible WordPress plugin available to Add and manage your Testimonial page with drag and drop feature. 
 * Author: wpshopmart
 * Author URI: https://www.wpshopmart.com
 * Plugin URI: https://wpshopmart.com/plugins/testimonial-pro/
 */

if ( ! defined( 'ABSPATH' ) ) exit; 
define("wpshopmart_test_b_directory_url", plugin_dir_url(__FILE__));
define("wpshopmart_test_b_text_domain", "wpsm_test_b");

add_image_size( 'wpsm_testi_small', 100,100, true); 

function wpsm_test_b_default_data() {
	
	$Settings_Array = serialize( array(
		"test_mb_name_clr" 	 => "#000000",
		"test_mb_deg_clr" => "#424242",
		"test_mb_web_link_clr" => "#1e73be",
		"test_mb_content_clr" => "#595959",
		"test_mb_name_font_size"   => "20",
		"test_mb_deg_font_size"   => "16",
		"test_mb_web_link_font_size"   => "15",
		"test_mb_content_font_size"   => "19",
		"test_font_family"   => "Open Sans",
		"test_layout"   => "6",
		"custom_css"   => "",
		"bgclr_style2" => "#e5e5e5",
		"test_mb_web_link_label" => "website",
		"test_image_layout" => "1",
		"templates" => "1",
	));
	add_option('test_B_default_Settings', $Settings_Array);
}
register_activation_hook( __FILE__, 'wpsm_test_b_default_data' );


add_action('admin_menu' , 'wpsm_testi_b_recom_menu');
function wpsm_testi_b_recom_menu() {
	$submenu2 = add_submenu_page('edit.php?post_type=test_builder', __('Free Vs Pro', wpshopmart_test_b_text_domain), __('Free Vs Pro', wpshopmart_test_b_text_domain), 'administrator', 'wpsm_testi_b_fvp_page', 'wpsm_testi_b_fvp_page_funct');
	$submenu = add_submenu_page('edit.php?post_type=test_builder', __('More_Free_Plugins', wpshopmart_test_b_text_domain), __('More Free Plugins', wpshopmart_test_b_text_domain), 'administrator', 'wpsm_testi_b_recom_page', 'wpsm_testi_b_recom_page_funct');
	add_action( 'admin_print_styles-' . $submenu, 'wpsm_testi_b_recom_js_css' );
	add_action( 'admin_print_styles-' . $submenu2, 'wpsm_testi_b_fvp_js_css' );
}
function wpsm_testi_b_recom_js_css(){
	wp_enqueue_style('wpsm_testi_b_bootstrap_css_recom', wpshopmart_test_b_directory_url.'assets/css/bootstrap.css');
	wp_enqueue_style('wpsm_testi_b_help_css', wpshopmart_test_b_directory_url.'assets/css/help.css');
}

// recommended free plugins 
function wpsm_testi_b_recom_page_funct(){
	require_once('ink/admin/free.php');
}

// implement testimonial settings
function wpsm_testi_b_fvp_js_css(){
	wp_enqueue_style('wpsm_servicebox_settings_fvp', wpshopmart_test_b_directory_url.'assets/css/settings.css');
	
}
function wpsm_testi_b_fvp_page_funct(){
	require_once('ink/admin/fvp.php');
}

/**
 * plugin setup config
 */
require_once("ink/install/installation.php");

/**
 * Custom Post Type Create
 */
 
require_once("ink/admin/menu.php");

/**
 * SHORTCODE
 */
require_once("template/shortcode.php"); 

?>