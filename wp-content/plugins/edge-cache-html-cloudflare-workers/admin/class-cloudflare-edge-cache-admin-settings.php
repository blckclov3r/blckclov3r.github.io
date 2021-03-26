<?php
/**
 * The settings of the plugin.
 *
 * @link       https://silviustroe.com
 * @since      1.0.0
 *
 * @package    Cloudflare_Edge_Cache
 * @subpackage Cloudflare_Edge_Cache/admin
 */

/**
 * Class WordPress_Plugin_Template_Settings
 *
 */
class Cloudflare_Edge_Cache_Admin_Settings
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

    private $email;
    private $api_key;
    private $domain;
    private $full_domain;

    private $displayDonate;

    public function __construct($plugin_name, $version)
    {
        $this->plugin_name = $plugin_name;
        $this->version = $version;

        $options = get_option('cf_edge_cache_input');
        $this->email = $options['cloudflare_email'];
        $this->api_key = $options['cloudflare_api_key'];


        $full_domain = parse_url(site_url(), PHP_URL_HOST);
        $this->full_domain = $full_domain;

        $domain = str_ireplace('www.', '', $full_domain); //little fix for www.
        $this->domain = $domain;
    }

    /**
     * This function introduces the theme options into the 'Appearance' menu and into a top-level
     * 'WPPB Demo' menu.
     */
    public function setup_plugin_options_menu()
    {
        //Add the menu to the Plugins set of menu items
        add_plugins_page(
            'Cloudflare Edge Caching',           // The title to be displayed in the browser window for this page.
            'Cloudflare Edge Caching Options',          // The text to be displayed for this menu item
            'manage_options',          // Which type of users can see this menu item
            'cf_caching_options',      // The unique ID - that is, the slug - for this menu item
            array($this, 'render_settings_page_content')        // The name of the function to call when rendering this menu's page
        );
    }

    /**
     * Provides default values for the Display Options.
     *
     * @return array
     */
    public function default_display_options()
    {
        $defaults = array(
            'show_header' => '',
            'show_content' => '',
            'show_footer' => 'Plm',
        );
        return $defaults;
    }

    /**
     * Provides default values for the Input Options.
     *
     * @return array
     */
    public function default_input_options()
    {
        $defaults = array(
            'input_example' => '',
            'cloudflare_email' => '',
            'textarea_example' => '',
            'checkbox_example' => '',
            'radio_example' => '2',
            'time_options' => 'default'
        );
        return $defaults;
    }

    function my_plugin_action_links($links)
    {
        $links = array_merge(array(
            '<a href="' . esc_url(admin_url('/options-general.php')) . '">' . __('Settings', 'textdomain') . '</a>'
        ), $links);
        return $links;
    }

    /**
     * Renders a simple page to display for the theme menu defined above.
     */
    public function render_settings_page_content($active_tab = '')
    {
        ?>
        <!-- Create a header in the default WordPress 'wrap' container -->
        <div class="wrap">
            <h2><?php _e('Cloudflare Edge Caching Settings', 'cf_edge_cache'); ?></h2>
            <?php settings_errors(); ?>
            <form method="post" action="options.php">
                <?php
                settings_fields('cf_edge_cache_input');
                do_settings_sections('cf_edge_cache_input');

                submit_button();
                ?>
            </form>
        </div><!-- /.wrap -->
        <div class="wrap">
            <button class="button button-primary" id="installWorker">(Re)Install Cloudflare Worker</button>
        </div>
        <div class="footer">
            <a class="donate-button" href="https://www.paypal.me/silviustroe" target="_blank">Buy me a coffee
                ☕️</a>
        </div>

        <?php
    }

    /**
     * This function provides a simple description for the General Options page.
     *
     * It's called from the 'wppb-demo_initialize_theme_options' function by being passed as a parameter
     * in the add_settings_section function.
     */
    public function general_options_callback()
    {
        $options = get_option('cf_edge_cache_options');
        var_dump($options);
        echo '<p>' . __('Select which areas of content you wish to display.', 'cf_edge_cache') . '</p>';
    } // end general_options_callback

    /**
     * This function provides a simple description for the Input Examples page.
     *
     * It's called from the 'wppb-demo_theme_initialize_input_examples_options' function by being passed as a parameter
     * in the add_settings_section function.
     */
    public function input_examples_callback()
    {
        $options = get_option('cf_edge_cache_input');

        //var_dump($options);
        echo '<p>' . __('Configure Cloudflare API settings to can install automatically the worker', 'cf_edge_cache') . '</p>';

        if (isset($options['cloudflare_response']))
            echo '<p style="color: blueviolet">Current Cloudflare Worker installation status: ' . $options['cloudflare_response'] . '</p>';
    } // end general_options_callback

    /**
     * Initializes the theme's display options page by registering the Sections,
     * Fields, and Settings.
     *
     * This function is registered with the 'admin_init' hook.
     */
    public function initialize_display_options()
    {
        // If the theme options don't exist, create them.
        if (false == get_option('cf_edge_cache_options')) {
            $default_array = $this->default_display_options();
            add_option('cf_edge_cache_options', $default_array);
        }
        add_settings_section(
            'general_settings_section',                  // ID used to identify this section and with which to register options
            __('Display Options', 'cf_edge_cache'),            // Title to be displayed on the administration page
            array($this, 'general_options_callback'),      // Callback used to render the description of the section
            'cf_edge_cache_options'                    // Page on which to add this section of options
        );
        // Next, we'll introduce the fields for toggling the visibility of content elements.
        add_settings_field(
            'show_header',                    // ID used to identify the field throughout the theme
            __('Header', 'cf_edge_cache'),          // The label to the left of the option interface element
            array($this, 'toggle_header_callback'),  // The name of the function responsible for rendering the option interface
            'cf_edge_cache_options',              // The page on which this option will be displayed
            'general_settings_section',              // The name of the section to which this field belongs
            array(                        // The array of arguments to pass to the callback. In this case, just a description.
                __('Activate this setting to display the header.', 'cf_edge_cache'),
            )
        );
        add_settings_field(
            'show_content',
            __('Content', 'cf_edge_cache'),
            array($this, 'toggle_content_callback'),
            'cf_edge_cache_options',
            'general_settings_section',
            array(
                __('Activate this setting to display the content.', 'cf_edge_cache'),
            )
        );
        add_settings_field(
            'show_footer',
            __('Footer', 'cf_edge_cache'),
            array($this, 'toggle_footer_callback'),
            'cf_edge_cache_options',
            'general_settings_section',
            array(
                __('Activate this setting to display the footer.', 'cf_edge_cache'),
            )
        );
        // Finally, we register the fields with WordPress
        register_setting(
            'cf_edge_cache_options',
            'cf_edge_cache_options'
        );
    } // end wppb-demo_initialize_theme_options

    /**
     * Initializes the theme's input example by registering the Sections,
     * Fields, and Settings. This particular group of options is used to demonstration
     * validation and sanitization.
     *
     * This function is registered with the 'admin_init' hook.
     */
    public function initialize_input_examples()
    {
        //delete_option('cf_edge_cache_input');
        if (false == get_option('cf_edge_cache_input')) {
            $default_array = $this->default_input_options();
            update_option('cf_edge_cache_input', $default_array);
        } // end if
        add_settings_section(
            'input_examples_section',
            __('Cloudflare API Settings', 'cf_edge_cache'),
            array($this, 'input_examples_callback'),
            'cf_edge_cache_input'
        );
        add_settings_field(
            'Cloudflare E-mail',
            __('Cloudflare E-mail', 'cf_edge_cache'),
            array($this, 'input_cloudflare_email_callback'),
            'cf_edge_cache_input',
            'input_examples_section'
        );
        add_settings_field(
            'Cloudflare API',
            __('Cloudflare API Key', 'cf_edge_cache'),
            array($this, 'input_cloudflare_api_callback'),
            'cf_edge_cache_input',
            'input_examples_section'
        );

        register_setting(
            'cf_edge_cache_input',
            'cf_edge_cache_input',
            array($this, 'validate_input_examples')
        );
    }

    /**
     * This function renders the interface elements for toggling the visibility of the header element.
     *
     * It accepts an array or arguments and expects the first element in the array to be the description
     * to be displayed next to the checkbox.
     */
    public function toggle_header_callback($args)
    {
        // First, we read the options collection
        $options = get_option('cf_edge_cache_options');
        // Next, we update the name attribute to access this element's ID in the context of the display options array
        // We also access the show_header element of the options collection in the call to the checked() helper function
        $html = '<input type="checkbox" id="show_header" name="cf_edge_cache_options[show_header]" value="1" ' . checked(1, isset($options['show_header']) ? $options['show_header'] : 0, false) . '/>';
        // Here, we'll take the first argument of the array and add it to a label next to the checkbox
        $html .= '<label for="show_header"> ' . $args[0] . '</label>';
        echo $html;
    } // end toggle_header_callback

    public function toggle_content_callback($args)
    {
        $options = get_option('cf_edge_cache_options');
        $html = '<input type="checkbox" id="show_content" name="cf_edge_cache_options[show_content]" value="1" ' . checked(1, isset($options['show_content']) ? $options['show_content'] : 0, false) . '/>';
        $html .= '<label for="show_content"> ' . $args[0] . '</label>';
        echo $html;
    } // end toggle_content_callback

    public function toggle_footer_callback($args)
    {
        $options = get_option('cf_edge_cache_options');
        $html = '<input type="checkbox" id="show_footer" name="cf_edge_cache_options[show_footer]" value="1" ' . checked(1, isset($options['show_footer']) ? $options['show_footer'] : 0, false) . '/>';
        $html .= '<label for="show_footer"> ' . $args[0] . '</label>';
        echo $html;
    } // end toggle_footer_callback

    public function input_cloudflare_email_callback()
    {
        $options = get_option('cf_edge_cache_input');
        // Render the output
        echo '<input required size="50" type="email" id="cloudflare_email" name="cf_edge_cache_input[cloudflare_email]" value="' . $options['cloudflare_email'] . '" />';
    } // end input_element_callback

    public function input_cloudflare_api_callback()
    {
        $options = get_option('cf_edge_cache_input');
        // Render the output
        echo '<input required size="50" type="text" id="cloudflare_api_key" name="cf_edge_cache_input[cloudflare_api_key]" value="' . $options['cloudflare_api_key'] . '" />';
    } //

    public function get_cloudflare_account()
    {
        $url = "https://api.cloudflare.com/client/v4/zones?name=$this->domain&status=active";
        $options = get_option('cf_edge_cache_input');

        $email = $options['cloudflare_email'];
        $api_key = $options['cloudflare_api_key'];

        $args = array(
            'method' => 'GET',
            'timeout' => 45,
            'sslverify' => false,
            'headers' => array(
                'X-Auth-Email' => $email,
                'X-Auth-Key' => $api_key,
                'Content-Type' => 'application/json',
            )
        );

        $request = wp_remote_get($url, $args);

        $res = wp_remote_retrieve_body($request);
        $return = json_decode($res, true);


        if (!isset($return['errors'][0])) {
            return array(
                'zone_id' => $return['result'][0]['id'],
                'account_id' => $return['result'][0]['account']['id']
            );
        } else {
            return $return['errors'][0]['message'] . '. Code ' . $return['errors'][0]['code'];
        }

    }


    public function create_cloudflare_route($zone_id)
    {

        $url = "https://api.cloudflare.com/client/v4/zones/$zone_id/workers/routes";

        $args = array(
            'method' => 'POST',
            'timeout' => 45,
            'sslverify' => false,
            'headers' => array(
                'X-Auth-Email' => $this->email,
                'X-Auth-Key' => $this->api_key,
                'Content-Type' => 'application/json',
            ),
            'body' => '{"pattern":"' . $this->full_domain . '/*","script":"wp-edge-caching"}'
        );

        $request = wp_remote_request($url, $args);


        $res = wp_remote_retrieve_body($request);
        $return = json_decode($res, true);

        $old_option = get_option('cf_edge_cache_input');

        if (!isset($return['errors'][0])) {
            $old_option['cloudflare_response'] = 'Cloudflare Route has been set';
            update_option('cf_edge_cache_input', $old_option);
        } else {
            //if route installed, all ok
            if ($return['errors'][0]['code'] == '10020') return;

            $old_option['cloudflare_response'] = $return['errors'][0]['message'] . '. Code ' . $return['errors'][0]['code'];
            update_option('cf_edge_cache_input', $old_option);
        }

        echo $old_option['cloudflare_response'];

        wp_die();
    }

    public function create_cloudflare_worker()
    {
        $options = get_option('cf_edge_cache_input');

        $email = $this->email;
        $api_key = $this->api_key;
        $cf_account = $this->get_cloudflare_account();
        if (is_array($cf_account) && array_key_exists("account_id", $cf_account)) {
            $zone_id = $cf_account['zone_id'];
            $account_id = $cf_account['account_id'];
        } else {
            $options['cloudflare_response'] = $cf_account;
            update_option('cf_edge_cache_input', $options);
            echo $cf_account;
            wp_die();
            return;
        }

        $worker_content = file_get_contents(plugin_dir_path(dirname(__FILE__)) . 'admin/worker-code.worker');

        $url = "https://api.cloudflare.com/client/v4/accounts/${account_id}/workers/scripts/wp-edge-caching";
        $body = "
        // API settings if KV isn't being used
const CLOUDFLARE_API = {
  email: \"${email}\", // From https://dash.cloudflare.com/profile
  key: \"${api_key}\",   // Global API Key from https://dash.cloudflare.com/profile
  zone: \"$zone_id\"   // \"Zone ID\" from the API section of the dashboard overview page https://dash.cloudflare.com/
};
" . $worker_content;

        $args = array(
            'method' => 'PUT',
            'timeout' => 45,
            'sslverify' => false,
            'headers' => array(
                'X-Auth-Email' => $email,
                'X-Auth-Key' => $api_key,
                'Content-Type' => 'application/javascript',
            ),
            'body' => $body,
        );

        $request = wp_remote_request($url, $args);


        $res = wp_remote_retrieve_body($request);
        $return = json_decode($res, true);

        $old_option = get_option('cf_edge_cache_input');

        if (!isset($return['errors'][0])) {
            $this->create_cloudflare_route($zone_id);
            $old_option['cloudflare_response'] = 'Cloudflare Worker successfully (re)installed!';
            update_option('cf_edge_cache_input', $old_option);
        } else {
            $old_option['cloudflare_response'] = $return['errors'][0]['message'] . '. Code ' . $return['errors'][0]['code'];
            update_option('cf_edge_cache_input', $old_option);
        }

        echo $old_option['cloudflare_response'];

        wp_die();
    }


    function validate_input_examples($input)
    {
        // Create our array for storing the validated options
        $output = array();
        // Loop through each of the incoming options
        foreach ($input as $key => $value) {
            // Check to see if the current option has a value. If so, process it.
            if (isset($input[$key])) {
                // Strip all HTML and PHP tags and properly handle quoted strings
                $output[$key] = strip_tags(stripslashes($input[$key]));
            } // end if
        } // end foreach
        // Return the array processing any additional functions filtered by this action
        return apply_filters('validate_input_examples', $output, $input);
    } // end validate_input_examples
}