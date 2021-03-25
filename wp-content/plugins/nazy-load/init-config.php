<?php
// Set default config on plugin load if not set
function flying_images_set_default_config() {

    if (FLYING_IMAGES_VERSION !== get_option('FLYING_IMAGES_VERSION')) {
        
        // Lazy loading
        if (get_option('flying_images_enable_lazyloading') === false)
            update_option('flying_images_enable_lazyloading', true);

        if (get_option('flying_images_lazymethod') === false) 
            update_option('flying_images_lazymethod', "javascript");

        if (get_option('flying_images_margin') === false)
            update_option('flying_images_margin', 500);

        if (get_option('flying_images_exclude_keywords') === false)
            update_option('flying_images_exclude_keywords', ['your-logo.png']);

        // CDN
        if (get_option('flying_images_enable_cdn') === false)
            update_option('flying_images_enable_cdn', true);
        
        if (get_option('flying_images_cdn_exclude_keywords') === false)
            update_option('flying_images_cdn_exclude_keywords', []);

        // Compression
        if (get_option('flying_images_enable_compression') === false)
            update_option('flying_images_enable_compression', true);
        
        if (get_option('flying_images_quality') === false)
            update_option('flying_images_quality', 100);

        // Responsiveness
        if (get_option('flying_images_enable_responsive_images') === false)
            update_option('flying_images_enable_responsive_images', true);
            
        update_option('FLYING_IMAGES_VERSION', FLYING_IMAGES_VERSION);
    }
}

add_action('plugins_loaded', 'flying_images_set_default_config');
