<?php

namespace PremiumAddons\Admin\Includes;

use PremiumAddons\Helper_Functions;

if ( ! defined( 'ABSPATH' ) ) exit;

class Plugin_Info {

    public function create_about_menu() {
        
        if ( ! Helper_Functions::is_hide_about() ) {
                add_submenu_page(
                'premium-addons',
                '',
                __('About','premium-addons-for-elementor'),
                'manage_options',
                'premium-addons-about',
                [ $this, 'pa_about_page' ]
            );
        }
        
    }

	public function pa_about_page() {
        
        $theme_name = Helper_Functions::get_installed_theme();
        
        $url = sprintf('https://premiumaddons.com/pro/?utm_source=about-page&utm_medium=wp-dash&utm_campaign=get-pro&utm_term=%s', $theme_name );
        
        $support_url = sprintf('https://premiumaddons.com/support/?utm_source=about-page&utm_medium=wp-dash&utm_campaign=get-support&utm_term=%s', $theme_name );
        
        ?>
        <div class="wrap">
           <div class="response-wrap"></div>
           <div class="pa-header-wrapper">
              <div class="pa-title-left">
                 <h1 class="pa-title-main"><?php echo Helper_Functions::name(); ?></h1>
                 <h3 class="pa-title-sub"><?php echo sprintf(__('Thank you for using %s. This plugin has been developed by %s and we hope you enjoy using it.','premium-addons-for-elementor'), Helper_Functions::name(),Helper_Functions::author()); ?></h3>
              </div>
              <?php if( ! Helper_Functions::is_hide_logo() ) : ?>
                <div class="pa-title-right">
                    <img class="pa-logo" src="<?php echo PREMIUM_ADDONS_URL . 'admin/images/premium-addons-logo.png';?>">
                </div>
                <?php endif; ?>
           </div>
           <div class="pa-settings-tabs">
              <div id="pa-about" class="pa-settings-tab">
                 <div class="pa-row">
                    <div class="pa-col-half">
                       <div class="pa-about-panel">
                          <div class="pa-icon-container">
                             <i class="dashicons dashicons-info abt-icon-style"></i>
                          </div>
                          <div class="pa-text-container">
                             <h4><?php echo __('What is Premium Addons?', 'premium-addons-for-elementor'); ?></h4>
                             <p><?php echo __('Premium Addons for Elementor extends Elementor Page Builder capabilities with many fully customizable widgets and addons that help you to build impressive websites with no coding required.', 'premium-addons-for-elementor'); ?></p>
                             <?php if( ! defined('PREMIUM_PRO_ADDONS_VERSION') ) : ?>
                                <p><?php echo __('Get more widgets and addons with ', 'premium-addons-for-elementor'); ?><strong><?php echo __('Premium Addons Pro', 'premium-addons-for-elementor'); ?></strong> <a href="<?php echo esc_url( $url ); ?>" target="_blank" ><?php echo __('Click Here', 'premium-addons-for-elementor'); ?></a><?php echo __(' to know more.', 'premium-addons-for-elementor'); ?></p>
                             <?php endif; ?>
                          </div>
                       </div>
                    </div>
                    <div class="pa-col-half">
                       <div class="pa-about-panel">
                          <div class="pa-icon-container">
                             <i class="dashicons dashicons-universal-access-alt abt-icon-style"></i>
                          </div>
                          <div class="pa-text-container">
                             <h4><?php echo __('Docs and Support', 'premium-addons-for-elementor'); ?></h4>
                             <p><?php echo __('It’s highly recommended to check out documentation and FAQ before using this plugin. ', 'premium-addons-for-elementor'); ?><a target="_blank" href="<?php echo esc_url( $support_url ); ?>"><?php echo __('Click Here', 'premium-addons-for-elementor'); ?></a><?php echo __(' for more details. You can also join our ', 'premium-addons-for-elementor'); ?><a href="https://www.facebook.com/groups/PremiumAddons" target="_blank"><?php echo __('Facebook Group', 'premium-addons-for-elementor'); ?></a><?php echo __(' and Our ', 'premium-addons-for-elementor'); ?><a href="https://my.leap13.com/forums/" target="_blank"><?php echo __('Community Forums', 'premium-addons-for-elementor'); ?></a></p>
                          </div>
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
        </div>
    <?php }
    
	public function __construct() {
        add_action( 'admin_menu', array ($this,'create_about_menu' ), 100 );
	}    
}