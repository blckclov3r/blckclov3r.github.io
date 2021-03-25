<?php

// Check W3TC is active
if (
    !in_array('w3-total-cache/w3-total-cache.php', get_option('active_plugins'))
    && !(is_multisite() && in_array('w3-total-cache/w3-total-cache.php', get_site_option( 'active_sitewide_plugins')))
) {
    return;
}

function flying_images_w3tc_bridge() {
    // Check W3TC Page Cache module is active
    if (!w3tc_config()->get_boolean('pgcache.enabled')) {
        return;
    }

    add_filter('flying_images_output_buffer', '__return_false', 10);

    // Handle buffer before plugin starts work
    add_filter('w3tc_process_content', function($buffer) {
        return flying_images_rewrite_html($buffer);
    });
}

add_action('plugins_loaded', 'flying_images_w3tc_bridge');