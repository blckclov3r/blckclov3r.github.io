<?php

// Check WFPC is active
if (
    !in_array('wp-fastest-cache/wpFastestCache.php', get_option('active_plugins'))
    && !(is_multisite() && in_array('wp-fastest-cache/wpFastestCache.php', get_site_option( 'active_sitewide_plugins')))
) {
    return;
}

function flying_images_wpfc_bridge() {
    add_filter('flying_images_output_buffer', '__return_false', 10);

    // Handle buffer before plugin starts work
    add_filter('wpfc_buffer_callback_filter', function( $buffer, $extension ) {
        if( $extension == 'html' || $extension == 'mobile' ) {
               return flying_images_rewrite_html( $buffer );
        } else return $buffer;
    }, 10, 2);
}

add_action('plugins_loaded', 'flying_images_wpfc_bridge');