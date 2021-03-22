<?php
        if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>

        <p><span class="dashicons dashicons-flag error"></span> <?php _e( "Your site runs on a server type which the current version can't create the required rewrite data, please check with", 'wp-hide-security-enhancer' ) ?> <span class="wph-pro">PRO</span> <?php _e( "version at", 'wp-hide-security-enhancer' ) ?> <a target="_blank" href="https://www.wp-hide.com/wp-hide-pro-now-available/">WP-Hide PRO</a>
        <br /><?php _e( "This basic version can work with Apache, LiteSpeed, IIS, Nginx set as reverse proxy for Apache, your site runs", 'wp-hide-security-enhancer' ) ?> <b><?php echo $_SERVER['SERVER_SOFTWARE']  ?></b></p>
