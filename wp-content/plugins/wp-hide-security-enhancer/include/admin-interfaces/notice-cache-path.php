<?php
        if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>
        <p><span class="dashicons dashicons-flag error"></span> <?php _e( "Unable to create cache folder at ", 'wp-hide-security-enhancer' ) ?><?php echo WPH_CACHE_PATH ?><?php _e( " Is the folder writable? No cache data will be available.", 'wp-hide-security-enhancer' ) ?></p>
