<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              piwebsolution.com
 * @since             1.9.9
 * @package           Http2_Push_Content
 *
 * @wordpress-plugin
 * Plugin Name:       HTTP2 push content
 * Description:       Push all CSS and JS file throuhg http2 server, plugin add extra files that you want to push like images font files and other
 * Version:           1.9.9
 * Author:            Pi websolution
 * Author URI:        piwebsolution.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       http2-push-content
 * Domain Path:       /languages
 * WC tested up to: 5.0
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

include_once( ABSPATH . 'wp-admin/includes/plugin.php' );


if(is_plugin_active( 'http2-push-content-pro/http2-push-content.php')){

    function pi_http2_free_error_notice() {
        ?>
        <div class="error notice">
            <p><?php _e( 'You have the PRO version of this plugin active, Free version and PRO version cant be active same time', 'http2-push-content'); ?></p>
        </div>
        <?php
    }
    add_action( 'admin_notices', 'pi_http2_free_error_notice' );
    deactivate_plugins(plugin_basename(__FILE__));
	return;
	
}else{
/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */

define( 'HTTP2_PUSH_CONTENT', '1.9.9' );

define('HTTP2_PUSH_CONTENT_PRICE', '$11');
define('HTTP2_PUSH_CONTENT_BUY_URL', 'https://www.piwebsolution.com/cart/?add-to-cart=694&variation_id=1682');


define('PI_HTTP2_MAX_HEADER_SIZE', 1024*8);

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-http2-push-content-activator.php
 */
function activate_http2_push_content() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-http2-push-content-activator.php';
	Http2_Push_Content_Activator::activate();
}
 
/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-http2-push-content-deactivator.php
 */
function deactivate_http2_push_content() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-http2-push-content-deactivator.php';
	Http2_Push_Content_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_http2_push_content' );
register_deactivation_hook( __FILE__, 'deactivate_http2_push_content' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-http2-push-content.php';

if(!function_exists('pisol_http2_plugin_link')){
function pisol_http2_plugin_link( $links ) {
    $links = array_merge( array(
        '<a href="' . esc_url( admin_url( '/admin.php?page=http2-push-content' ) ) . '">' . __( 'Settings', 'http2-push-content' ) . '</a>',
        '<a style="color:#0a9a3e; font-weight:bold;" target="_blank" href="https://wordpress.org/support/plugin/http2-push-content/reviews/#bbp_topic_content">' . __( 'SEND SUGGESTIONS TO IMPROVE','http2-push-content' ) . '</a>'
    ), $links );
    return $links;
}
add_action( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'pisol_http2_plugin_link' );
}

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_http2_push_content() {

	$plugin = new Http2_Push_Content();
	$plugin->run();

}
run_http2_push_content();

}