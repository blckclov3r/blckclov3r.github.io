<?php

add_action('wp_head', 'flying_images_add_resource_hints');
function flying_images_add_resource_hints(){
    $cdn_enabled = get_option('flying_images_enable_cdn');
    if($cdn_enabled) {
        ?>
<link rel="dns-prefetch" href="https://cdn.statically.io/" >
<link rel="preconnect" href="https://cdn.statically.io/" crossorigin>
        <?php
    }
};