<?php
        if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>
<?php

    if ( $result === FALSE )
        {
        ?>
        <p><span class="dashicons dashicons-flag error"></span><b><?php _e("Rewrite test failed! ", 'wp-hide-security-enhancer') ?></b> <?php _e("Ensure the rewrites are active for your server.", 'wp-hide-security-enhancer') ?>.</p>
        <?php
        }
        else
        {
        ?>
        <p><span class="dashicons dashicons-flag error"></span><b><?php _e("Rewrite test failed! ", 'wp-hide-security-enhancer') ?></b> <?php echo $result ?></p>
        <?php   
        }
        
        
?>