<?php
        if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>

    <p>
        <span class="dashicons dashicons-flag error"></span><?php _e("Unable to write/update required rewrite rules to your site", 'wp-hide-security-enhancer') ?> <?php echo $rewrite_file_type ?>. <?php _e('Is this file writable? Until fixed, no changes are applied on the front side.', 'wp-hide-security-enhancer') ?>
        <br /><?php _e("Try to go at Settings > Permalinks and save once, the core will attempt to update the required rewrites. If the problem persists, check with your host support on the correct file write permission.", 'wp-hide-security-enhancer') ?>
    </p>