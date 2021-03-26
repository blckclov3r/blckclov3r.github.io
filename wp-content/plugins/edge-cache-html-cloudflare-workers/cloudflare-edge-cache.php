<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://silviustroe.com
 * @since             1.0.0
 * @package           Cloudflare_Edge_Cache
 *
 * @wordpress-plugin
 * Plugin Name:       Edge Cache HTML via Cloudflare Workers
 * Plugin URI:        https://silviustroe.com/cloudflare-workers-wordpress-edge-cache-plugin/
 * Description:       Cache your website on the Cloudflare Delivery Network with the power of Workers.
 * Version:           1.0.5
 * Author:            Silviu Stroe
 * Author URI:        https://silviustroe.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       cloudflare-edge-cache
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define('CLOUDFLARE_EDGE_CACHE_VERSION', '1.0.0');

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-cloudflare-edge-cache-activator.php
 */
function activate_cloudflare_edge_cache()
{
    require_once plugin_dir_path(__FILE__) . 'includes/class-cloudflare-edge-cache-activator.php';
    Cloudflare_Edge_Cache_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-cloudflare-edge-cache-deactivator.php
 */
function deactivate_cloudflare_edge_cache()
{
    require_once plugin_dir_path(__FILE__) . 'includes/class-cloudflare-edge-cache-deactivator.php';
    Cloudflare_Edge_Cache_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'activate_cloudflare_edge_cache');
register_deactivation_hook(__FILE__, 'deactivate_cloudflare_edge_cache');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path(__FILE__) . 'includes/class-cloudflare-edge-cache.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_cloudflare_edge_cache()
{

    $plugin = new Cloudflare_Edge_Cache();
    $plugin->run();

}

run_cloudflare_edge_cache();
