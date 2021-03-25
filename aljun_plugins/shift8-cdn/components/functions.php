<?php
/**
 * Shift8 CDN Main Functions
 *
 * Collection of functions used throughout the operation of the plugin
 *
 */

if ( !defined( 'ABSPATH' ) ) {
    die();
}

// Function to encrypt session data
function shift8_cdn_encrypt($key, $payload) {
    if (!empty($key) && !empty($payload)) {
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
        $encrypted = openssl_encrypt($payload, 'aes-256-cbc', $key, 0, $iv);
        return base64_encode($encrypted . '::' . $iv);
    } else {
        return false;
    }
}

// Function to decrypt session data
function shift8_cdn_decrypt($key, $garble) {
    if (!empty($key) && !empty($garble)) {
        list($encrypted_data, $iv) = explode('::', base64_decode($garble), 2);
        return openssl_decrypt($encrypted_data, 'aes-256-cbc', $key, 0, $iv);
    } else {
        return false;
    }
}

// Handle the ajax trigger
add_action( 'wp_ajax_shift8_cdn_push', 'shift8_cdn_push' );
function shift8_cdn_push() {
    // Register
    if ( wp_verify_nonce($_GET['_wpnonce'], 'process') && $_GET['type'] == 'register') {
        shift8_cdn_poll('register');
        die();
    // Check
    } else if ( wp_verify_nonce($_GET['_wpnonce'], 'process') && $_GET['type'] == 'check') {
        shift8_cdn_poll('check');
        die();
    // Delete
    } else if ( wp_verify_nonce($_GET['_wpnonce'], 'process') && $_GET['type'] == 'delete') {
        shift8_cdn_poll('delete');
        die();
    // Purge
    } else if ( wp_verify_nonce($_GET['_wpnonce'], 'process') && $_GET['type'] == 'purge') {
        shift8_cdn_poll('purge');
        die();
    } else {
        die();
    } 
}

// Handle the actual GET
function shift8_cdn_poll($shift8_action) {
    if (current_user_can('administrator')) {
        global $wpdb;
        global $shift8_cdn_table_name;
        $current_user = wp_get_current_user();

        $cdn_url = esc_attr(get_option('shift8_cdn_url'));
        $cdn_api = esc_attr(get_option('shift8_cdn_api'));

        // Set headers for WP Remote post
        $headers = array(
            'Content-type: application/json',
        );

        // Check values with dashboard
        if ($shift8_action == 'check') {
            // Use WP Remote Get to poll the cdn api 
            $response = wp_remote_get( S8CDN_API . '/api/check',
                array(
                    'method' => 'POST',
                    'headers' => $headers,
                    'httpversion' => '1.1',
                    'timeout' => '45',
                    'blocking' => true,
                    'body' => array(
                        'url' => $cdn_url,
                        'api' => $cdn_api
                    ),
                )
            );
        // Submit purge request
        } else if ($shift8_action == 'purge') {
            // Use WP Remote Get to poll the cdn api 
            $response = wp_remote_get( S8CDN_API . '/api/purge',
                array(
                    'method' => 'POST',
                    'headers' => $headers,
                    'httpversion' => '1.1',
                    'timeout' => '45',
                    'blocking' => true,
                    'body' => array(
                        'url' => $cdn_url,
                        'api' => $cdn_api
                    ),
                )
            );
        }

        // Deal with the response
        if (is_array($response) && $response['response']['code'] == '200' && !json_decode($response['body'])->error) {
            // Populate options from response if its a check
            if ($shift8_action == 'check') {
                update_option('shift8_cdn_api', esc_attr(json_decode($response['body'])->apikey));
                update_option('shift8_cdn_prefix', esc_attr(json_decode($response['body'])->cdnprefix));
                // Manually set the paid check transient
                $suffix_get = esc_attr(json_decode($response['body'])->user_plan->cdn_suffix);
                $suffix_set = (!empty($suffix_get) && $suffix_get !== "" ? $suffix_get : S8CDN_SUFFIX_SECOND );
                set_transient(S8CDN_PAID_CHECK, $suffix_set, 0);

                echo json_encode(array(
                'apikey' => esc_attr(json_decode($response['body'])->apikey),
                'cdnprefix' => esc_attr(json_decode($response['body'])->cdnprefix),
                ));
            } else if ($shift8_action == 'purge') {
                echo json_encode(array(
                    'response' => esc_attr(json_decode($response['body'])->response)
                ));
            }

        } else {
            echo 'Error Detected : ';
            if (is_wp_error($response)) {
                echo $response->get_error_message();
            } else {
                echo 'unknown';
            }
        } 
    } 
}

// Rewrite static URLs with CDN
function shift8_cdn_rewrites( $rewrites ) {
        if (shift8_cdn_check_enabled()) {
            // Get all options configured as array
            $shift8_options = shift8_cdn_check_options();
            $shift8_site_url = parse_url(get_site_url());

            $urls = array(
                home_url( 'wp-content' ),
                home_url( 'wp-includes' ),
            );
            
            foreach( $urls as $in => $out ) {
                $url = parse_url($urls[$in]);
                $rewrites[$out] = str_replace( $shift8_site_url['scheme'] . '://' . $shift8_site_url['host'], 'https://' . $shift8_options['cdn_prefix'] . S8CDN_SUFFIX_SECOND, $urls[$in] );
            }
            return $rewrites;
        }
}

add_filter( 'shift8_cdn_rewrites', 'shift8_cdn_rewrites' );

// Add DNS prefetch for faster resolution and loading
function shift8_cdn_prefetch() {
    if (shift8_cdn_check_enabled()) {
        // Get all options configured as array
        $shift8_options = shift8_cdn_check_options();
        echo '<meta http-equiv="x-dns-prefetch-control" content="on">
        <link rel="dns-prefetch" href="//' . $shift8_options['cdn_prefix'] . S8CDN_SUFFIX . '" />
        <link rel="dns-prefetch" href="//' . $shift8_options['cdn_prefix'] . S8CDN_SUFFIX_SECOND . '" />
        <link rel="dns-prefetch" href="//' . $shift8_options['cdn_prefix'] . S8CDN_SUFFIX_PAID . '" />';
    }
}

add_action('wp_head', 'shift8_cdn_prefetch', 0);


// Functions to produce debugging information
function shift8_cdn_debug_get_php_info() {
    //retrieve php info for current server
    if (!function_exists('ob_start') || !function_exists('phpinfo') || !function_exists('ob_get_contents') || !function_exists('ob_end_clean') || !function_exists('preg_replace')) {
        echo 'This information is not available.';
    } else {
        ob_start();
        phpinfo();
        $pinfo = ob_get_contents();
        ob_end_clean();

        $pinfo = preg_replace( '%^.*<body>(.*)</body>.*$%ms','$1',$pinfo);
        echo $pinfo;
    }
}

function shift8_cdn_debug_version_check() {
    //outputs basic information
    $notavailable = __('This information is not available.');
    if ( !function_exists( 'get_bloginfo' ) ) {
        $wp = $notavailable;
    } else {
        $wp = get_bloginfo( 'version' );
    }

    if ( !function_exists( 'wp_get_theme' ) ) {
        $theme = $notavailable;
    } else {
        $theme = wp_get_theme();
    }

    if ( !function_exists( 'get_plugins' ) ) {
        $plugins = $notavailable;
    } else {
        $plugins_list = get_plugins();
        if( is_array( $plugins_list ) ){
            $active_plugins = '';
            $plugins = '<ul>';
            foreach ( $plugins_list as $plugin ) {
                $version = '' != $plugin['Version'] ? $plugin['Version'] : __( 'Unversioned', 'debug-info' );
                if( !empty( $plugin['PluginURI'] ) ){
                    $plugins .= '<li><a href="' . $plugin['PluginURI'] . '">' . $plugin['Name'] . '</a> (' . $version . ')</li>';
                } else {
                    $plugins .= '<li>' . $plugin['Name'] . ' (' . $version . ')</li>';
                }
            }
            $plugins .= '</ul>';
        }
    }

    if ( !function_exists( 'phpversion' ) ) {
        $php = $notavailable;
    } else {
        $php = phpversion();
    }


    $themeversion   = $theme->get( 'Name' ) . __( ' version ', 'debug-info' ) . $theme->get( 'Version' ) . $theme->get( 'Template' );
    $themeauth      = $theme->get( 'Author' ) . ' - ' . $theme->get( 'AuthorURI' );
    $uri            = $theme->get( 'ThemeURI' );

    echo '<strong>' . __( 'WordPress Version: ' ) . '</strong>' . $wp . '<br />';
    echo '<strong>' . __( 'Current WordPress Theme: ' ) . '</strong>' . $themeversion . '<br />';
    echo '<strong>' . __( 'Theme Author: ' ) . '</strong>' . $themeauth . '<br />';
    echo '<strong>' . __( 'Theme URI: ' ) . '</strong>' . $uri . '<br />';
    echo '<strong>' . __( 'PHP Version: ' ) . '</strong>' . $php . '<br />';
    echo '<strong>' . __( 'Active Plugins: ' ) . '</strong>' . $plugins . '<br />';
}

// Function to schedule cron polling interval to check if account is paid

// Check user plan options
add_action( 'shift8_cdn_cron_hook', 'shift8_cdn_check_suffix' );
function shift8_cdn_check_suffix() {
    $cdn_url = esc_attr(get_option('shift8_cdn_url'));
    $cdn_api = esc_attr(get_option('shift8_cdn_api'));

    // Use WP Remote Get to poll the cdn api 
    $response = wp_remote_get( S8CDN_API . '/api/check',
        array(
            'method' => 'POST',
            'httpversion' => '1.1',
            'timeout' => '45',
            'blocking' => true,
            'body' => array(
                'url' => $cdn_url,
                'api' => $cdn_api
            ),
        )
    );
    // Set transient based on results
    $suffix_get = esc_attr(json_decode($response['body'])->user_plan->cdn_suffix);
    $suffix_set = (!empty($suffix_get) && $suffix_get !== "" ? $suffix_get : S8CDN_SUFFIX_SECOND );
    set_transient(S8CDN_PAID_CHECK, $suffix_set, 0);
}
add_filter( 'cron_schedules', 'shift8_cdn_add_cron_interval' );
function shift8_cdn_add_cron_interval( $schedules ) { 
    $schedules['shift8_cdn_five'] = array(
        'interval' => 5,
        'display'  => esc_html__( 'Every Five Seconds' ), );
    $schedules['shift8_cdn_minute'] = array(
        'interval' => 60,
        'display'  => esc_html__( 'Every Sixty Seconds' ), );
    $schedules['shift8_cdn_halfhour'] = array(
        'interval' => 1800,
        'display'  => esc_html__( 'Every 30 minutes' ), );
    $schedules['shift8_cdn_twohour'] = array(
        'interval' => 7200,
        'display'  => esc_html__( 'Every two hours' ), );
    $schedules['shift8_cdn_fourhour'] = array(
        'interval' => 14400,
        'display'  => esc_html__( 'Every four hours' ), );
    return $schedules;
}

// Set the cron task on an every 4 hour basis to check the CDN suffix
if (shift8_cdn_check_enabled()) {
    if ( ! wp_next_scheduled( 'shift8_cdn_cron_hook' ) ) {
        wp_schedule_event( time(), 'shift8_cdn_fourhour', 'shift8_cdn_cron_hook' );
    } 
} else {
    wp_clear_scheduled_hook( 'shift8_cdn_cron_hook' );
}
