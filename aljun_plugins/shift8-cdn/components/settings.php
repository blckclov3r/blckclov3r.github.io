<?php
/**
 * Shift8 CDN Settings
 *
 * Declaration of plugin settings used throughout
 *
 */

if ( !defined( 'ABSPATH' ) ) {
    die();
}

add_action('admin_head', 'shift8_cdn_custom_favicon');
function shift8_cdn_custom_favicon() {
  echo '
    <style>
    .dashicons-shift8 {
        background-image: url("'. plugin_dir_url(dirname(__FILE__)) .'/img/shift8pluginicon.png");
        background-repeat: no-repeat;
        background-position: center; 
    }
    </style>
  '; 
}

// create custom plugin settings menu
add_action('admin_menu', 'shift8_cdn_create_menu');
function shift8_cdn_create_menu() {
        //create new top-level menu
        if ( empty ( $GLOBALS['admin_page_hooks']['shift8-settings'] ) ) {
                add_menu_page('Shift8 Settings', 'Shift8', 'administrator', 'shift8-settings', 'shift8_main_page' , 'dashicons-shift8' );
        }
        add_submenu_page('shift8-settings', 'CDN Settings', 'CDN Settings', 'manage_options', __FILE__.'/custom', 'shift8_cdn_settings_page');
        //call register settings function
        add_action( 'admin_init', 'register_shift8_cdn_settings' );
}

// Register admin settings
function register_shift8_cdn_settings() {
    //Register our settings
    register_setting( 'shift8-cdn-settings-group', 'shift8_cdn_enabled' );
    register_setting( 'shift8-cdn-settings-group', 'shift8_cdn_url', 'shift8_cdn_url_validate' );
    register_setting( 'shift8-cdn-settings-group', 'shift8_cdn_api' );
    register_setting( 'shift8-cdn-settings-group', 'shift8_cdn_prefix' );
    register_setting( 'shift8-cdn-settings-group', 'shift8_cdn_css', array( 'default' => 'on' ) );
    register_setting( 'shift8-cdn-settings-group', 'shift8_cdn_js', array( 'default' => 'on' ) );
    register_setting( 'shift8-cdn-settings-group', 'shift8_cdn_media', array( 'default' => 'on' ));
    //register_setting( 'shift8-cdn-settings-group', 'shift8_cdn_reject_files', 'shift8_cdn_sanitize_reject_field' );
    register_setting( 'shift8-cdn-settings-group', 'shift8_cdn_reject_files' );

    // Cleanup of old settings no longer needed
    if (get_option('shift8_cdn_email')) {
        delete_option('shift8_cdn_email');
    }
}

// Uninstall hook
function shift8_cdn_uninstall_hook() {
  // Delete setting values
  delete_option('shift8_cdn_enabled');
  delete_option('shift8_cdn_url');
  delete_option('shift8_cdn_api');
  delete_option('shift8_cdn_prefix');
  delete_option('shift8_cdn_css');
  delete_option('shift8_cdn_js');
  delete_option('shift8_cdn_media');
  delete_option('shift8_cdn_reject_files');

  // Clear Cron tasks
  wp_clear_scheduled_hook( 'shift8_cdn_cron_hook' );
  // Delete transient data
  delete_transient(S8CDN_PAID_CHECK);
}
register_uninstall_hook( S8CDN_FILE, 'shift8_cdn_uninstall_hook' );

// Deactivation hook
function shift8_cdn_deactivation() {
  // Clear Cron tasks
  wp_clear_scheduled_hook( 'shift8_cdn_cron_hook' );
  // Delete transient
  delete_transient(S8CDN_PAID_CHECK);
}
register_deactivation_hook( S8CDN_FILE, 'shift8_cdn_deactivation' );

// Validate Input for Admin options
function shift8_cdn_url_validate($data){
	if(filter_var($data, FILTER_VALIDATE_URL, FILTER_FLAG_SCHEME_REQUIRED)) {
      $site_url = parse_url($data);
      return $site_url["scheme"] . '://' . $site_url["host"] . $site_url["path"];
   	} else {
   		add_settings_error(
            'shift8_cdn_url',
            'shift8-cdn-notice',
            'You did not enter a valid URL for your site URL',
            'error');
   	}
}

// Validate admin options
function shift8_cdn_check_enabled() {
  // If enabled is not set
  if(esc_attr( get_option('shift8_cdn_enabled') ) != 'on') return false;
  if(empty(esc_attr(get_option('shift8_cdn_url') ))) return false;
  if(empty(esc_attr(get_option('shift8_cdn_api') ))) return false;
  if(empty(esc_attr(get_option('shift8_cdn_prefix') ))) return false;

  return true;
}

// Process all options and return array
function shift8_cdn_check_options() {
  $shift8_options = array();
  $shift8_options['cdn_url'] = esc_attr( get_option('shift8_cdn_url') );
  $shift8_options['cdn_api'] = esc_attr( get_option('shift8_cdn_api') );
  $shift8_options['cdn_prefix'] = esc_attr( get_option('shift8_cdn_prefix') );
  $shift8_options['static_css'] = esc_attr( get_option('shift8_cdn_css', 'on') );
  $shift8_options['static_js'] = esc_attr( get_option('shift8_cdn_js', 'on') );
  $shift8_options['static_media'] = esc_attr( get_option('shift8_cdn_media','on') );
  
  return $shift8_options;
}

// Check if transient is set
function shift8_cdn_check_paid_transient() {
  // If transient is not set
  if (!get_transient(S8CDN_PAID_CHECK)) return false;
  if(get_transient(S8CDN_PAID_CHECK) === S8CDN_SUFFIX) return S8CDN_SUFFIX;
  if(get_transient(S8CDN_PAID_CHECK) === S8CDN_SUFFIX_SECOND) return S8CDN_SUFFIX_SECOND;
  if(get_transient(S8CDN_PAID_CHECK) === S8CDN_SUFFIX_PAID) return S8CDN_SUFFIX_PAID;

  return false;
}

// Sanitize settings meant for text areas
function shift8_cdn_sanitize_reject_field( $data ) {

  $sanitized_values = null;

  if ( ! is_array( $data ) ) {
    $values = explode( "\n", $data );
  }

  $values = array_map( 'trim', $values );
  $values = array_filter( $values );

  if ( ! $values ) {
    return [];
  }

  // Sanitize.
  foreach ( $values as $value ) {
    // Get relative URL
    $value = wp_parse_url( $value, PHP_URL_PATH );
    // Clean wildcards
    $path_components = explode( '/', $value );
    $wildcard_arr = [
      '.*'   => '(.*)',
      '*'    => '(.*)',
      '(*)'  => '(.*)',
      '(.*)' => '(.*)',
    ];

    foreach ( $path_components as &$path_component ) {
      $path_component = strtr( $path_component, $wildcard_arr );
    }
    $value = implode( '/', $path_components );

    // Finally add each sanitized value to new array
    $sanitized_values[] = $value;
  }
  //return $sanitized_values;
  return $data;
}

// Sanitize settings meant for text areas
function shift8_cdn_sanitize_reject_field_display( $data ) {

  $sanitized_values = null;

  if (! $data ) {
    return null;
  }

  if ( !is_array( $data ) ) {
    return null;
  }

  $values = array_map( 'trim', $data );
  $values = array_filter( $values );

  if ( ! $values ) {
    return null;
  }

  // Sanitize.
  foreach ( $values as $value ) {
    // Get relative URL
    //$value = wp_parse_url( $value, PHP_URL_PATH );
    // Clean wildcards
    $path_components = explode( '/', $value );
    $wildcard_arr = [
      '.*'   => '(.*)',
      '*'    => '(.*)',
      '(*)'  => '(.*)',
      '(.*)' => '(.*)',
    ];

    foreach ( $path_components as &$path_component ) {
      $path_component = strtr( $path_component, $wildcard_arr );
    }
    $value = implode( '/', $path_components );

    // Finally add each sanitized value to new array
    $sanitized_values[] = $value;
  }
  //return implode("\n", $sanitized_values);
  return $data;
}
