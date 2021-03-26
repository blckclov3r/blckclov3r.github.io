<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://silviustroe.com
 * @since      1.0.0
 *
 * @package    Cloudflare_Edge_Cache
 * @subpackage Cloudflare_Edge_Cache/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Cloudflare_Edge_Cache
 * @subpackage Cloudflare_Edge_Cache/public
 * @author     Silviu Stroe <silviu@silviustroe.com>
 */
class Cloudflare_Edge_Cache_Public
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
     * @param string $plugin_name The name of the plugin.
     * @param string $version The version of this plugin.
     * @since    1.0.0
     */
    public function __construct($plugin_name, $version)
    {

        $this->plugin_name = $plugin_name;
        $this->version = $version;

    }

    /**
     * Register the stylesheets for the public-facing side of the site.
     *
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

        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/cloudflare-edge-cache-public.css', array(), $this->version, 'all');

    }

    /**
     * Register the JavaScript for the public-facing side of the site.
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

        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/cloudflare-edge-cache-public.js', array('jquery'), $this->version, false);

    }

    #https://github.com/cloudflare/worker-examples/blob/master/examples/edge-cache-html/WordPress%20Plugin/cloudflare-page-cache/cloudflare-page-cache.php

    // Callbacks that something changed
    function cloudflare_page_cache_init_action()
    {
        static $done = false;
        if ($done) {
            return;
        }
        $done = true;

        // Add the edge-cache headers
        if (!is_user_logged_in()) {
            header('x-HTML-Edge-Cache: cache,bypass-cookies=wp-|wordpress|comment_|woocommerce_');
        } else {
            header('x-HTML-Edge-Cache: nocache');
        }

    }

    // Add the response header to purge the cache. send_headers isn't always called
    // so set it immediately when something changes.
    function cloudflare_page_cache_purge()
    {
        static $purged = false;
        if (!$purged) {
            $purged = true;
            header('x-HTML-Edge-Cache: purgeall');
        }
    }

    function cloudflare_page_cache_purge0()
    {
        $this->cloudflare_page_cache_purge();
    }

    function cloudflare_page_cache_purge1($param1)
    {
        $this->cloudflare_page_cache_purge();
    }

    function cloudflare_page_cache_purge2($param1, $param2 = "")
    {
        $this->cloudflare_page_cache_purge();
    }

    function cloudflare_page_cache_post_transition($new_status, $old_status, $post)
    {
        if ($new_status != $old_status) {
            $this->cloudflare_page_cache_purge();
        }
    }

}
