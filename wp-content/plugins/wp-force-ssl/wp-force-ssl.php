<?php
/*
  Plugin Name: WP Force SSL
  Plugin URI: https://wpforcessl.com/
  Description: Redirect all traffic from HTTP to HTTPS for your entire site.
  Author: WebFactory Ltd
  Author URI: https://www.webfactoryltd.com/
  Version: 1.56
  Text Domain: wp-force-ssl

  Copyright 2019 - 2020  WebFactory Ltd  (email: support@webfactoryltd.com)

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License, version 2, as
  published by the Free Software Foundation.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/


// if the file is called directly
if (!defined('ABSPATH')) {
  exit('You are not allowed to access this file directly.');
}

require_once 'wp301/wp301.php';
new wf_wp301(__FILE__, 'settings_page_wpfs-settings');


define('WPFSSL_OPTIONS_KEY', 'wpfssl_options');
define('WPFSSL_META_KEY', 'wpfssl_meta');


// start up the engine
class wpForceSSL
{
  static $instance = false;
  private $wpfs_settings = array();
  public $version = 0;
  public $plugin_url = '';
  public $meta = array();


  /**
   * Check if minimum WP and PHP versions are installed
   * Register all hooks for the plugin
   *
   * @since 1.5
   *
   * @return array plugin meta
   *
   */
  private function __construct()
  {
    $this->get_plugin_version();

    if (false === $this->check_wp_version(4.6)) {
      return false;
    }

    $this->wpfs_settings = get_option(WPFSSL_OPTIONS_KEY, array());

    if (!is_array($this->wpfs_settings) || empty($this->wpfs_settings)) {
      $this->wpfs_settings['wpfs_ssl'] = 'yes';
      $this->wpfs_settings['wpfs_hsts'] = 'no';
      update_option(WPFSSL_OPTIONS_KEY, $this->wpfs_settings);
    }

    $this->plugin_url = plugin_dir_url(__FILE__);
    $this->meta = $this->get_meta();

    if ($this->wpfs_settings['wpfs_ssl'] == 'yes') {
      add_action('template_redirect', array($this, 'wpfs_core'));
    }
    if ($this->wpfs_settings['wpfs_hsts'] == 'yes') {
      add_action('send_headers', array($this, 'to_strict_transport_security'));
    }

    add_action('admin_menu', array($this, 'add_settings_page'));
    add_filter('admin_footer_text', array($this, 'admin_footer_text'));
    add_action('admin_enqueue_scripts', array($this, 'admin_scripts'));
    add_filter('install_plugins_table_api_args_featured', array($this, 'featured_plugins_tab'));
    add_action('admin_action_wpfs_dismiss_review_notice', array($this, 'action_dismiss_review_notice'));

    // ajax hooks for the settings, and SSL certificate test
    add_action('wp_ajax_save_settting_nonce_action', array($this, 'ajax_save_setting'));
    add_action('wp_ajax_test_ssl_nonce_action', array($this, 'ajax_check_ssl'));
  } // __construct


  /**
   * Get plugin meta data, create if not existent
   *
   * @since 1.5
   *
   * @return array plugin meta
   *
   */
  public function get_meta()
  {
    $meta = get_option(WPFSSL_META_KEY, array());

    if (!is_array($meta) || empty($meta)) {
      $meta['first_version'] = $this->get_plugin_version();
      $meta['first_install'] = time();
      $meta['hide_review_notification'] = false;
      update_option(WPFSSL_META_KEY, $meta);
    }

    return $meta;
  } // get_meta


  /**
   * Get plugin version
   *
   * @since 1.5
   *
   * @return string plugin version
   *
   */
  public function get_plugin_version()
  {
    $plugin_data = get_file_data(__FILE__, array('version' => 'Version'), 'plugin');
    $this->version = $plugin_data['version'];

    return $plugin_data['version'];
  } // get_plugin_version


  /**
   * Return instance, create if not already existing
   *
   * @since 1.5
   *
   * @return object wpForceSLL instance
   *
   */
  public static function getInstance()
  {
    if (false == is_a(self::$instance, 'wpForceSSL')) {
      self::$instance = new self;
    }

    return self::$instance;
  } // getInstance


  /**
   * Enqueue admin scripts
   *
   * @since 1.5
   *
   * @return null
   *
   */
  public function admin_scripts($hook)
  {
    if (false == $this->is_plugin_page()) {
      return;
    }

    wp_enqueue_style('wpfs-style', $this->plugin_url . 'css/wpfs-style.css', null, $this->version);
    wp_enqueue_style('wpfs-sweetalert2-style', $this->plugin_url . 'css/sweetalert2.min.css', null, $this->version);

    wp_enqueue_script('wpfs-sweetalert2', $this->plugin_url . 'js/sweetalert2.min.js', array('jquery'), $this->version, true);
    wp_enqueue_script('wpfs-script', $this->plugin_url . 'js/wpfs-script.js', array('jquery'), $this->version, true);

    wp_localize_script('wpfs-script', 'wpfs', array(
      'ajaxurl' => admin_url('admin-ajax.php'),
      'loading_icon_url' => plugins_url('img/loading-icon.png', __FILE__),
      'testing' => __('Testing. Please wait ...', 'wp-force-ssl'),
      'saving' => __('Saving. Please wait ...', 'wp-force-ssl'),
      'test_success' => __('Test Completed Successfully', 'wp-force-ssl'),
      'test_failed' => __('Test Failed', 'wp-force-ssl'),
      'home_url' => get_home_url(),
      'save_success' => __('Settings saved.', 'wp-force-ssl'),
      'undocumented_error' => __('An undocumented error has occurred. Please refresh the page and try again.', 'wp-force-ssl'),
      'documented_error' => __('An error has occurred.', 'wp-force-ssl'),
      'nonce_save_settings' => wp_create_nonce('save_settting_nonce_action'),
      'nonce_test_ssl' => wp_create_nonce('test_ssl_nonce_action')
    ));
  } // admin_scripts


  /**
   * Load text domain and plugin version
   *
   * @since 1.5
   *
   * @return null
   *
   */
  static function plugins_loaded()
  {
    load_plugin_textdomain('wp-force-ssl');
  } // plugins_loaded


  /**
   * Register menu page
   *
   * @since 1.5
   *
   * @return null
   *
   */
  public function add_settings_page()
  {
    add_options_page(
      __('WP Force SSL', 'wp-force-ssl'),
      __('WP Force SSL', 'wp-force-ssl'),
      'manage_options',
      'wpfs-settings',
      array($this, 'settings_page_content')
    );
  } // add_settings_page


  /**
   * Dismiss review notification
   *
   * @since 1.55
   *
   * @return null
   *
   */
  public function action_dismiss_review_notice() {
    if (false == wp_verify_nonce(@$_GET['_wpnonce'], 'wpfs_dismiss_review_notice')) {
      wp_die('Please reload the page and try again.');
    }

    $this->meta['hide_review_notification'] = true;
    update_option(WPFSSL_META_KEY, $this->meta);

    wp_safe_redirect('options-general.php?page=wpfs-settings');
  } // action_dismiss_review_notice


  /**
   * Echo plugin settings page
   *
   * @since 1.5
   *
   * @return null
   *
   */
  public function settings_page_content()
  {
    ?>
    <div class="wrap">
      <h1>
        <?php _e('WP Force SSL', 'wp-force-ssl'); ?>
      </h1>
      <hr>
      <p>WP Force SSL is a one-click solution that helps redirect visitors from unsecure HTTP connection to a secure HTTPS one without the need to edit any code.<br>There's nothing to configure, just by activating the plugin everything is enabled and configured.</p>

      <p>If you run into any problems <a href="https://wordpress.org/support/plugin/wp-force-ssl/" target="_blank">open a new topic</a> in the official support forum. We answer all questions within a few hours.</p>

      <h2><br>Requirements for running a site on SSL</h2>
      <ul class="ul-disc">
        <li>If you already have an SSL certificate installed <a href="#" class="wpfs_test_ssl">test it</a> to make sure it works.
          If you don't have one get <a href="https://wordpress.org/hosting/" target="_blank">quality hosting</a> that offers free certificates and installation.</li>
        <li>Make sure "Force SSL" option below is enabled (green).</li>
        <li>Check that <a href="<?php echo admin_url('options-general.php'); ?>" target="_blank">WP Address &amp; Site Address</a> settings have an <code>https://</code> prefix set.</li>
      </ul>
<?php
  if (!isset($this->meta['hide_review_notification']) ||
      empty($this->meta['hide_review_notification'])) {

      $dismiss_url = add_query_arg(array('action' => 'wpfs_dismiss_review_notice', 'redirect' => urlencode($_SERVER['REQUEST_URI'])), admin_url('admin.php'));
      $dismiss_url = wp_nonce_url($dismiss_url, 'wpfs_dismiss_review_notice');
?>
      <p id="review-notification" class="box"<?php if ($this->wpfs_settings['wpfs_ssl'] == 'no') echo ' style="display: none;"'; ?>><b>Your site is now more secure! Please help others learn about this free plugin.</b><br><br>Please help other users learn about WP Force SSL by leaving a review.<br>It only takes a minute but it helps a lot and it keeps the plugin updated, maintained and free. <b>Thank you!</b><br><br>
      <a class="button button-primary" href="https://wordpress.org/support/plugin/wp-force-ssl/reviews/?filter=5" target="_blank">Help other users - leave a review</a><a href="<?php echo $dismiss_url; ?>">I already left a review</a>
      </p>
<?php
  }
?>
      <h2><br><?php _e('Settings', 'wp-force-ssl'); ?></h2>
      <form id="wpfs_form">
        <table class="form-table">
          <tbody>
            <tr>
              <th scope="row">
                <label for="enable_ssl"><?php _e('Force SSL', 'wp-force-ssl'); ?></label>
              </th>
              <td>
                <div class="toggle-wrapper">
                  <input type="checkbox" id="enable_ssl" <?php if ($this->wpfs_settings['wpfs_ssl'] == 'yes') echo 'checked'; ?> name="wpfs_ssl" value="yes">
                  <label for="enable_ssl" class="toggle"><span class="toggle_handler"></span></label>
                </div>
                <p class="description">Visitors will be automatically redirected from HTTP to HTTPS for all pages, posts and other WP content.</p>
              </td>
            </tr>
            <tr>
              <th scope="row">
                <label for="enable_hsts"><?php _e('Enable HTTP Strict Transport Security (HSTS)', 'wp-force-ssl'); ?></label>
              </th>
              <td>
                <div class="toggle-wrapper">
                  <input type="checkbox" id="enable_hsts" <?php if ($this->wpfs_settings['wpfs_hsts'] == 'yes') echo 'checked'; ?> name="wpfs_hsts" value="yes">
                  <label for="enable_hsts" class="toggle"><span class="toggle_handler"></span></label>
                </div>
                <p class="description">HSTS is a web security policy mechanism that helps to protect your site against protocol downgrade attacks and cookie hijacking.<br>It allows web servers to declare that web browsers should interact with it using only HTTPS connections.</p>
              </td>
            </tr>
          </tbody>
        </table>
        <p>
          <a id="wpfs_save_settings" name="submit" title="Save Changes" class="button button-primary" href="#">Save Changes</a>
          <a href="#" class="button button-secondary wpfs_test_ssl">Test site's SSL certificate</a>
        </p>
      </form>
    </div>
<?php
  } // settings_page_content


  /**
   * Perform the redirect to HTTPS if loaded over HTTP
   *
   * @since 1.5
   *
   * @return null
   *
   */
  public function wpfs_core()
  {
    if (!is_ssl()) {
      wp_redirect('https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'], 301);
      exit();
    }
  } // wpfs_core


  /**
   * Check if user has the minimal WP version required by WP Force SSL
   *
   * @since 1.5
   *
   * @return bool
   *
   */
  public function check_wp_version($min_version)
  {
    if (!version_compare(get_bloginfo('version'), $min_version,  '>=')) {
      add_action('admin_notices', array($this, 'notice_min_wp_version'));
      return false;
    } else {
      return true;
    }
  } // check_wp_version


  /**
   * Display error message if WP version is too low
   *
   * @since 1.5
   *
   * @return null
   *
   */
  public function notice_min_wp_version()
  {
    echo '<div class="error"><p>' . sprintf(__('WP Force SSL plugin <b>requires WordPress version 4.6</b> or higher to function properly. You are using WordPress version %s. Please <a href="%s">update it</a>.', 'wp-force-ssl'), get_bloginfo('version'), admin_url('update-core.php')) . '</p></div>';
  } // notice_min_wp_version_error


  /**
   * Add hook to filter plugin API result to add recommended addon plugins
   *
   * @since 1.5
   *
   * @return object args
   */
  public function featured_plugins_tab($args)
  {
    add_filter('plugins_api_result', array($this, 'plugins_api_result'), 10);

    return $args;
  } // featured_plugins_tab


  /**
   * Append plugin favorites list with recommended addon plugins
   *
   * @since 1.5
   *
   * @return object API response
   */
  public function plugins_api_result($res)
  {
    remove_filter('plugins_api_result', array($this, 'plugins_api_result'), 10);
    $res = $this->add_plugin_favs('wp-reset', $res);
    $res = $this->add_plugin_favs('eps-301-redirects', $res);
    $res = $this->add_plugin_favs('simple-author-box', $res);
    return $res;
  } // plugins_api_result


  /**
   * Create plugin favorites list plugin object
   *
   * @since 1.5
   *
   * @return object favorite plugins
   */
  public function add_plugin_favs($plugin_slug, $res)
  {
    if (!isset($res->plugins) || !is_array($res->plugins)) {
      return $res;
    }

    if (!empty($res->plugins) && is_array($res->plugins)) {
      foreach ($res->plugins as $plugin) {
        if (is_object($plugin) && !empty($plugin->slug) && $plugin->slug == $plugin_slug) {
          return $res;
        }
      } // foreach
    }

    $plugin_info = get_transient('wf-plugin-info-' . $plugin_slug);
    if ($plugin_info && is_object($plugin_info)) {
      array_unshift($res->plugins, $plugin_info);
    } else {
      $plugin_info = plugins_api('plugin_information', array(
        'slug'   => $plugin_slug,
        'is_ssl' => is_ssl(),
        'fields' => array(
          'banners'           => true,
          'reviews'           => true,
          'downloaded'        => true,
          'active_installs'   => true,
          'icons'             => true,
          'short_description' => true,
        )
      ));
      if (!is_wp_error($plugin_info) && is_object($plugin_info) && $plugin_info) {
        array_unshift($res->plugins, $plugin_info);
        set_transient('wf-plugin-info-' . $plugin_slug, $plugin_info, DAY_IN_SECONDS * 7);
      }
    }

    return $res;
  } // add_plugin_favs


  /**
   * Send the HTTP Strict Transport Security (HSTS) header.
   *
   * @since 1.5
   *
   * @return null
   */
  public function to_strict_transport_security()
  {
    header('Strict-Transport-Security: max-age=31536000; includeSubDomains; preload');
  } // to_strict_transport_security


  /**
   * Save plugin settings received via AJAX
   *
   * @since 1.5
   *
   * @return null
   */
  public function ajax_save_setting()
  {
    check_ajax_referer('save_settting_nonce_action');

    $wpfs_ssl = isset($_POST['wpfs_ssl']) ? 'yes' : 'no';
    $wpfs_hsts = isset($_POST['wpfs_hsts']) ? 'yes' : 'no';

    $wpfs_settings = array(
      'wpfs_ssl' => $wpfs_ssl,
      'wpfs_hsts' => $wpfs_hsts
    );
    update_option(WPFSSL_OPTIONS_KEY, $wpfs_settings);

    wp_send_json_success();
  } // ajax_save_setting


  /**
   * Check SSL Certificate by performing a request to home_url over https and send back a json response
   *
   * @since 1.5
   *
   * @return null
   */
  public function ajax_check_ssl()
  {
    check_ajax_referer('test_ssl_nonce_action');

    if (!version_compare(PHP_VERSION, '5.6',  '>=')) {
      wp_send_json_error(sprintf(__('WP Force SSL plugin <b>requires PHP version 5.6.20</b> or higher to function properly. You are using PHP version %s. Please <a href="%s" target="_blank">update it</a>.', 'wp-force-ssl'), PHP_VERSION, 'https://wordpress.org/support/update-php/'));
    }

    $url = home_url(false, 'https');
    $response = $this->make_request($url);

    if (!$response || !is_array($response)) {
      wp_send_json_error(__('Unable to test SSL certificate. Assume it\'s NOT valid.', 'wp-force-ssl'));
    } elseif (!empty($response['error'])) {
      wp_send_json_error($response['error']);
    } elseif ($response['code'] == 200 && !empty($response['body'])) {
      wp_send_json_success(__('Valid SSL certificate found!', 'wp-force-ssl'));
    } else {
      wp_send_json_error(__('Valid SSL certificate was NOT found.', 'wp-force-ssl'));
    }
  } // check_ssl


  /**
   * Perform a POST request
   *
   * @since 1.5
   *
   * @param string $url
   *
   * @return array $response
   */
  public function make_request($url = null)
  {
    if (empty($url)) {
      $url = home_url(false, 'https');
    }

    $args = array(
      'timeout' => 5,
      'httpversion' => '1.1',
      'sslverify' => true
    );

    $response = wp_remote_post($url, $args);

    $error = '';
    if (is_wp_error($response)) {
      $error = $response->get_error_message();
    }

    $out  = array(
      'code' => wp_remote_retrieve_response_code($response),
      'body' => wp_remote_retrieve_body($response),
      'error' => $error
    );

    return $out;
  } // make_request


  /**
   * Check if currently on WP Force SSL settings page
   *
   * @since 1.5
   *
   * @return bool is on WP Force SSL settings page
   */
  public function is_plugin_page()
  {
    $current_screen = get_current_screen();
    if ($current_screen->id === 'settings_page_wpfs-settings') {
      return true;
    } else {
      return false;
    }
  } // is_plugin_page


  /**
   * Change admin footer text to show plugin information
   *
   * @since 1.5
   *
   * @param string $text_org original footer text
   *
   * @return string footer text html
   */
  public function admin_footer_text($text_org)
  {
    if (false === $this->is_plugin_page()) {
      return $text_org;
    }

    $text = '<i><a target="_blank" href="' . $this->generate_web_link('admin_footer') . '">WP Force SSL</a> v' . $this->version . ' by <a href="https://www.webfactoryltd.com/" title="' . __('Visit our site to get more great plugins', 'wp-force-ssl') . '" target="_blank">WebFactory Ltd</a>.';
    $text .= ' Please <a target="_blank" href="https://wordpress.org/support/plugin/wp-force-ssl/reviews/#new-post" title="' . __('Rate the plugin', 'wp-force-ssl') . '">' . __('Rate the plugin ★★★★★', 'wp-force-ssl') . '</a>.</i> ';
    return $text;
  } // admin_footer_text


  /**
   * Generate web link
   *
   * @since 1.5
   *
   * @return string link html
   */
  public function generate_web_link($placement = '', $page = '/', $params = array(), $anchor = '')
  {
    $base_url = 'https://wpforcessl.com';
    if ('/' != $page) {
      $page = '/' . trim($page, '/') . '/';
    }
    if ($page == '//') {
      $page  =  '/';
    }

    $parts = array_merge(array('utm_source' => 'wp-force-ssl', 'utm_medium' => 'plugin', 'utm_content' => $placement, 'utm_campaign' => 'wp-force-ssl' . $this->version), $params);
    if (!empty($anchor)) {
      $anchor = '#' . trim($anchor, '#');
    }

    $out = $base_url . $page . '?' . http_build_query($parts, '', '&amp;') . $anchor;
    return $out;
  } // generate_web_link


  /**
   * Activation hook, check if trying to activate on WP Network and exit if that's the case
   *
   * @since 1.5
   *
   * @return null
   */
  public static function activate()
  {
    // Bail if activating from network, or bulk
    if (is_network_admin() || isset($_GET['activate-multi'])) {
      wp_die(__('Sorry, WP Force SSL is currently not compatible with WPMU.', 'wp-force-ssl'));
    }
  } // activate


  /**
   * Redirect to WP Force SSL settings page on activation
   *
   * @since 1.5
   *
   * @return null
   *
   */
  public function redirect_to_settings_page($plugin)
  {
    if ($plugin == plugin_basename(__FILE__)) {
      wp_safe_redirect(
        add_query_arg(
          array(
            'page' => 'wpfs-settings'
          ),
          admin_url('options-general.php')
        )
      );
      exit();
    }
  } // redirect_to_settings_page


  /**
   * Clean-up on uninstall
   *
   * @since 1.5
   *
   * @return null
   */
  public static function uninstall()
  {
    delete_option(WPFSSL_OPTIONS_KEY);
    delete_option(WPFSSL_META_KEY);
  } // uninstall


  /**
   * Disabled; we use singleton pattern so magic functions need to be disabled.
   *
   * @since 1.5
   *
   * @return null
   */
  private function __clone()
  { }


  /**
   * Disabled; we use singleton pattern so magic functions need to be disabled.
   *
   * @since 1.5
   *
   * @return null
   */
  public function __sleep()
  { }


  /**
   * Disabled; we use singleton pattern so magic functions need to be disabled.
   *
   * @since 1.5
   *
   * @return null
   */
  public function __wakeup()
  { }
  // end class
}


$wpfs = wpForceSSL::getInstance();
add_action('plugins_loaded', array($wpfs, 'plugins_loaded'));
add_action('activated_plugin', array($wpfs, 'redirect_to_settings_page'));
register_activation_hook(__FILE__, array('wpForceSSL', 'activate'));
register_uninstall_hook(__FILE__, array('wpForceSSL', 'uninstall'));
