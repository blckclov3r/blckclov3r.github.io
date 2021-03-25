<?php

// Register settings menu

function flying_images_register_settings_menu() {
    add_options_page('Flying Images', 'Flying Images', 'manage_options', 'flying-images', 'flying_images_settings_view');
}
add_action('admin_menu', 'flying_images_register_settings_menu');

// Settings page
function flying_images_settings_view() {
    // Validate nonce
    if (isset($_POST['submit']) && !wp_verify_nonce($_POST['flying-images-settings-form'], 'flying-images')) {
        echo '<div class="notice notice-error"><p>Nonce verification failed</p></div>';
        exit;
    }

    // Settings
    include 'settings.php';
}
