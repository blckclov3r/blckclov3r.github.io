<?php

namespace PremiumAddons\Admin\Includes;

use PremiumAddons\Helper_Functions;

if( ! defined( 'ABSPATH' ) ) exit;

class Config_Data {
    
    public function __construct() {
        
        add_action( 'admin_menu', array ($this,'create_sys_info_menu' ), 100 );
    }
    
    public function create_sys_info_menu() {
        add_submenu_page(
            'premium-addons',
            '',
            __( 'System Info','premium-addons-for-elementor' ),
            'manage_options',
            'premium-addons-sys',
            [$this, 'pa_sys_info_page']
        );
    }
    
    public function pa_sys_info_page() {
    ?>
    <div class="wrap">
        <div class="response-wrap"></div>
        <div class="pa-header-wrapper">
            <div class="pa-title-left">
                <h1 class="pa-title-main"><?php echo Helper_Functions::name(); ?></h1>
                <h3 class="pa-title-sub"><?php echo sprintf( __( 'Thank you for using %s. This plugin has been developed by %s and we hope you enjoy using it.','premium-addons-for-elementor' ), Helper_Functions::name(), Helper_Functions::author() ); ?></h3>
            </div>
            <?php if( ! Helper_Functions::is_hide_logo() ) : ?>
                <div class="pa-title-right">
                    <img class="pa-logo" src="<?php echo PREMIUM_ADDONS_URL . 'admin/images/premium-addons-logo.png'; ?>">
                </div>
            <?php endif; ?>
        </div>
        <div class="pa-settings-tabs pa-sys-info-tab">
            <div id="pa-system" class="pa-settings-tab">
                <div class="pa-row">                
                    <h3 class="pa-sys-info-title"><?php echo __('System setup information useful for debugging purposes.','premium-addons-for-elementor');?></h3>
                    <div class="pa-system-info-container">
                        <?php
                        require_once ( PREMIUM_ADDONS_PATH . 'admin/includes/dep/info.php');
                        echo nl2br( pa_get_sysinfo() );
                        ?>
                    </div>
                </div>
            </div>
            <?php if( ! Helper_Functions::is_hide_rate() ) : ?>
                <div>
                    <p><?php echo __('Did you like Premium Addons for Elementor Plugin? Please ', 'premium-addons-for-elementor'); ?><a href="https://wordpress.org/support/plugin/premium-addons-for-elementor/reviews/#new-post" target="_blank"><?php echo __('Click Here to Rate it ★★★★★', 'premium-addons-for-elementor'); ?></a></p>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <?php }
}

