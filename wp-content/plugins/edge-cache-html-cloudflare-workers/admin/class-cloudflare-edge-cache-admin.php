<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://silviustroe.com
 * @since      1.0.0
 *
 * @package    Cloudflare_Edge_Cache
 * @subpackage Cloudflare_Edge_Cache/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Cloudflare_Edge_Cache
 * @subpackage Cloudflare_Edge_Cache/admin
 * @author     Silviu Stroe <silviu@silviustroe.com>
 */
class Cloudflare_Edge_Cache_Admin
{

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $plugin_name The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $version The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @param string $plugin_name The name of this plugin.
     * @param string $version The version of this plugin.
     * @since    1.0.0
     */
    public function __construct($plugin_name, $version)
    {

        $this->plugin_name = $plugin_name;
        $this->version = $version;

        $this->load_dependencies();
    }

    /**
     * Load the required dependencies for the Admin facing functionality.
     *
     * Include the following files that make up the plugin:
     *
     * - Wppb_Demo_Plugin_Admin_Settings. Registers the admin settings and page.
     *
     *
     * @since    1.0.0
     * @access   private
     */
    private function load_dependencies()
    {
        /**
         * The class responsible for orchestrating the actions and filters of the
         * core plugin.
         */

        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-cloudflare-edge-cache-admin-settings.php';

    }

    /**
     * Register the stylesheets for the admin area.
     *class-cloudflare-edge-cache-admin.php
     * @since    1.0.0
     */
    public function enqueue_styles()
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Cloudflare_Edge_Cache_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Cloudflare_Edge_Cache_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/cloudflare-edge-cache-admin.css', array(), $this->version, 'all');

    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts()
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Cloudflare_Edge_Cache_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Cloudflare_Edge_Cache_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/cloudflare-edge-cache-admin.js', array('jquery'), $this->version, false);

    }

    /**
     * Add a link to the settings page to the plugins list
     *
     * @param array $links array of links for the plugins, adapted when the current plugin is found.
     * @param string $file the filename for the current plugin, which the filter loops through.
     *
     * @return array $links
     */
    function cf_edge_cache_settings_link($links, $file)
    {

        if (false !== strpos($file, 'cloudflare-edge-cache')) {
            $mylinks = array(
                '<a target="_blank" href="https://silviustroe.com/contact/">' . __('Contact author', 'cloudflare-edge-cache') . '</a>',
                '<a href="plugins.php?page=cf_caching_options">' . __('Settings', 'cloudflare-edge-cache') . '</a>'
            );
            $links = array_merge($mylinks, $links);
        }
        return $links;
    }

}
