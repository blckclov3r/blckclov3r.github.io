<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://silviustroe.com
 * @since      1.0.0
 *
 * @package    Cloudflare_Edge_Cache
 * @subpackage Cloudflare_Edge_Cache/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Cloudflare_Edge_Cache
 * @subpackage Cloudflare_Edge_Cache/includes
 * @author     Silviu Stroe <silviu@silviustroe.com>
 */
class Cloudflare_Edge_Cache
{

    /**
     * The loader that's responsible for maintaining and registering all hooks that power
     * the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      Cloudflare_Edge_Cache_Loader $loader Maintains and registers all hooks for the plugin.
     */
    protected $loader;

    /**
     * The unique identifier of this plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string $plugin_name The string used to uniquely identify this plugin.
     */
    protected $plugin_name;

    /**
     * The current version of the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string $version The current version of the plugin.
     */
    protected $version;

    /**
     * Define the core functionality of the plugin.
     *
     * Set the plugin name and the plugin version that can be used throughout the plugin.
     * Load the dependencies, define the locale, and set the hooks for the admin area and
     * the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function __construct()
    {
        if (defined('CLOUDFLARE_EDGE_CACHE_VERSION')) {
            $this->version = CLOUDFLARE_EDGE_CACHE_VERSION;
        } else {
            $this->version = '1.0.0';
        }
        $this->plugin_name = 'cloudflare-edge-cache';

        $this->load_dependencies();
        $this->define_admin_hooks();
        $this->define_public_hooks();

    }

    /**
     * Load the required dependencies for this plugin.
     *
     * Include the following files that make up the plugin:
     *
     * - Cloudflare_Edge_Cache_Loader. Orchestrates the hooks of the plugin.
     * - Cloudflare_Edge_Cache_i18n. Defines internationalization functionality.
     * - Cloudflare_Edge_Cache_Admin. Defines all hooks for the admin area.
     * - Cloudflare_Edge_Cache_Public. Defines all hooks for the public side of the site.
     *
     * Create an instance of the loader which will be used to register the hooks
     * with WordPress.
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
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-cloudflare-edge-cache-loader.php';

        /**
         * The class responsible for defining all actions that occur in the admin area.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-cloudflare-edge-cache-admin.php';

        /**
         * The class responsible for defining all actions that occur in the public-facing
         * side of the site.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'public/class-cloudflare-edge-cache-public.php';

        $this->loader = new Cloudflare_Edge_Cache_Loader();

    }


    /**
     * Register all of the hooks related to the admin area functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_admin_hooks()
    {

        $plugin_admin = new Cloudflare_Edge_Cache_Admin($this->get_plugin_name(), $this->get_version());

        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles');
        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts');

        //plugin settings
        $plugin_settings = new Cloudflare_Edge_Cache_Admin_Settings($this->get_plugin_name(), $this->get_version());
        $this->loader->add_action('admin_menu', $plugin_settings, 'setup_plugin_options_menu');
        $this->loader->add_action('admin_init', $plugin_settings, 'initialize_display_options');
        $this->loader->add_action('admin_init', $plugin_settings, 'initialize_input_examples');

        $this->loader->add_action('wp_ajax_create_cloudflare_worker', $plugin_settings, 'create_cloudflare_worker');

        $this->loader->add_action('plugin_action_links', $plugin_admin, 'cf_edge_cache_settings_link', 10, 2);

    }


    /**
     * Register all of the hooks related to the public-facing functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_public_hooks()
    {

        $plugin_public = new Cloudflare_Edge_Cache_Public($this->get_plugin_name(), $this->get_version());

        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_styles');
        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_scripts');


        //Initial plugin https://github.com/cloudflare/worker-examples/blob/master/examples/edge-cache-html/WordPress%20Plugin/cloudflare-page-cache/cloudflare-page-cache.php
        // Post ID is received
        $this->loader->add_action('wp_trash_post', $plugin_public, 'cloudflare_page_cache_purge1', 0);
        $this->loader->add_action('publish_post', $plugin_public, 'cloudflare_page_cache_purge1', 0);
        $this->loader->add_action('edit_post', $plugin_public, 'cloudflare_page_cache_purge1', 0);
        $this->loader->add_action('delete_post', $plugin_public, 'cloudflare_page_cache_purge1', 0);
        $this->loader->add_action('publish_phone', $plugin_public, 'cloudflare_page_cache_purge1', 0);
        // Coment ID is received
        $this->loader->add_action('trackback_post', $plugin_public, 'cloudflare_page_cache_purge2', 99);
        $this->loader->add_action('pingback_post', $plugin_public, 'cloudflare_page_cache_purge2', 99);
        $this->loader->add_action('comment_post', $plugin_public, 'cloudflare_page_cache_purge2', 99);
        $this->loader->add_action('edit_comment', $plugin_public, 'cloudflare_page_cache_purge2', 99);
        $this->loader->add_action('wp_set_comment_status', $plugin_public, 'cloudflare_page_cache_purge2', 99, 2);
        // No post_id is available
        $this->loader->add_action('switch_theme', $plugin_public, 'cloudflare_page_cache_purge1', 99);
        $this->loader->add_action('edit_user_profile_update', $plugin_public, 'cloudflare_page_cache_purge1', 99);
        $this->loader->add_action('wp_update_nav_menu', $plugin_public, 'cloudflare_page_cache_purge0');
        $this->loader->add_action('clean_post_cache', $plugin_public, 'cloudflare_page_cache_purge1');
        $this->loader->add_action('transition_post_status', $plugin_public, 'cloudflare_page_cache_post_transition', 10, 3);

        $this->loader->add_action('init', $plugin_public, 'cloudflare_page_cache_init_action');
    }


    /**
     * Run the loader to execute all of the hooks with WordPress.
     *
     * @since    1.0.0
     */
    public function run()
    {
        $this->loader->run();
    }

    /**
     * The name of the plugin used to uniquely identify it within the context of
     * WordPress and to define internationalization functionality.
     *
     * @return    string    The name of the plugin.
     * @since     1.0.0
     */
    public function get_plugin_name()
    {
        return $this->plugin_name;
    }

    /**
     * The reference to the class that orchestrates the hooks with the plugin.
     *
     * @return    Cloudflare_Edge_Cache_Loader    Orchestrates the hooks of the plugin.
     * @since     1.0.0
     */
    public function get_loader()
    {
        return $this->loader;
    }

    /**
     * Retrieve the version number of the plugin.
     *
     * @return    string    The version number of the plugin.
     * @since     1.0.0
     */
    public function get_version()
    {
        return $this->version;
    }

}
