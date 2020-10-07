<?php

namespace PremiumAddons\Admin\Includes;

use PremiumAddons\Helper_Functions;

if( ! defined( 'ABSPATH' ) ) exit;

class Version_Control {
    
    public $pa_beta_keys = [ 'is-beta-tester' ];
    
    private $pa_beta_default_settings;
    
    private $pa_beta_settings;
    
    private $pa_beta_get_settings;
    
    public function __construct() {
        
        add_action( 'admin_menu', array ($this,'create_version_control_menu' ), 100 );
        
        add_action( 'wp_ajax_pa_beta_save_settings', array( $this, 'pa_beta_save_settings' ) );
        
    }
    
    
    public function create_version_control_menu() {
        
        if ( ! Helper_Functions::is_hide_version_control() ) {
            
                add_submenu_page(
                'premium-addons',
                '',
                __('Version Control','premium-addons-for-elementor'),
                'manage_options',
                'premium-addons-version',
                [$this, 'pa_version_page']
            );
        }
        
    }
    
    public function pa_version_page() {
        
        $js_info = array(
            'ajaxurl'   => admin_url( 'admin-ajax.php' ),
            'nonce' 	=> wp_create_nonce( 'pa-version-control' ),
	);
        
        wp_localize_script( 'pa-admin-js', 'settings', $js_info );
        
        $this->pa_beta_default_settings = array_fill_keys( $this->pa_beta_keys, true );
       
        $this->pa_beta_get_settings = get_option( 'pa_beta_save_settings', $this->pa_beta_default_settings );
        
        $pa_beta_new_settings = array_diff_key( $this->pa_beta_default_settings, $this->pa_beta_get_settings );
        
        if( ! empty( $pa_beta_new_settings ) ) {
            $pa_beta_updated_settings = array_merge( $this->pa_beta_get_settings, $pa_beta_new_settings );
            update_option( 'pa_beta_save_settings', $pa_beta_updated_settings );
        }
        
        $this->pa_beta_get_settings = get_option( 'pa_beta_save_settings', $this->pa_beta_default_settings );
        
    ?>
      
    <div class="wrap">
        <div class="response-wrap"></div>
        <form action="" method="POST" id="pa-beta-form" name="pa-beta-form">
       <div class="pa-header-wrapper">
          <div class="pa-title-left">
             <h1 class="pa-title-main"><?php echo Helper_Functions::name(); ?></h1>
             <h3 class="pa-title-sub"><?php echo sprintf(__('Thank you for using %s. This plugin has been developed by %s and we hope you enjoy using it.','premium-addons-for-elementor'), Helper_Functions::name(), Helper_Functions::author() ); ?></h3>
          </div>
          <?php if( ! Helper_Functions::is_hide_logo() ) : ?>
                <div class="pa-title-right">
                    <img class="pa-logo" src="<?php echo PREMIUM_ADDONS_URL . 'admin/images/premium-addons-logo.png'; ?>">
                </div>
            <?php endif; ?>
       </div> 
      <div class="pa-settings-tabs">
          <div id="pa-maintenance" class="pa-settings-tab">
             <div class="pa-row">
                <table class="pa-beta-table">
                   <tr>
                      <th>
                         <h4 class="pa-roll-back"><?php echo __('Rollback to Previous Version', 'premium-addons-for-elementor'); ?></h4>
                         <span class="pa-roll-back-span"><?php echo sprintf( __('Experiencing an issue with Premium Addons for Elementor version %s? Rollback to a previous version before the issue appeared.', 'premium-addons-for-elementor'), PREMIUM_ADDONS_VERSION ); ?></span>
                      </th>
                   </tr>
                   <tr class="pa-roll-row">
                      <th><?php echo __('Rollback Version', 'premium-addons-for-elementor'); ?></th>
                      <td>
                         <div><?php echo  sprintf( '<a target="_blank" href="%1$s" class="button pa-btn pa-rollback-button elementor-button-spinner">%2$s</a>', wp_nonce_url( admin_url( 'admin-post.php?action=premium_addons_rollback' ), 'premium_addons_rollback' ), __('Rollback to Version ' . PREMIUM_ADDONS_STABLE_VERSION, 'premium-addons-for-elementor') ); ?></div>
                         <p class="pa-roll-desc">
                             <span><?php echo __('Warning: Please backup your database before making the rollback.', 'premium-addons-for-elementor'); ?></span>
                         </p>
                      </td>
                   </tr>
                   <tr>
                      <th>
                         <h4 class="pa-beta-test"><?php echo __('Become a Beta Tester', 'premium-addons-for-elementor'); ?></h4>
                         <span class="pa-beta-test-span"><?php echo __('Turn-on Beta Tester, to get notified when a new beta version of Premium Addons for Elementor. The Beta version will not install automatically. You always have the option to ignore it.', 'premium-addons-for-elementor'); ?></span>
                      </th>
                   </tr>
                   <tr class="pa-beta-row">
                      <th><?php echo __('Beta Tester','premium-addons-for-elementor'); ?></th>
                      <td>
                         <div><input name="is-beta-tester" id="is-beta-tester" type="checkbox" <?php checked(1, $this->pa_beta_get_settings['is-beta-tester'], true) ?>><span><?php echo __('Check this box to get updates for beta versions','premium-addons-for-elementor'); ?></span></div>
                         <p class="pa-beta-desc"><span><?php echo __('Please Note: We do not recommend updating to a beta version on production sites.', 'premium-addons-for-elementor'); ?></span></p>
                      </td>
                   </tr>
                </table>
                <input type="submit" value="<?php echo __('Save Settings', 'premium-addons-for-elementor'); ?>" class="button pa-btn pa-save-button">
             </div>
          </div>
       </div>
        </form>
    </div>

    <?php }
    
    public function pa_beta_save_settings() {
        
        check_ajax_referer('pa-version-control', 'security');

        if( isset( $_POST['fields'] ) ) {
            parse_str( $_POST['fields'], $settings );
        } else {
            return;
        }
        
        $this->pa_beta_settings = array(
            'is-beta-tester'            => intval( $settings['is-beta-tester'] ? 1 : 0 ),
        );
        
        update_option( 'pa_beta_save_settings', $this->pa_beta_settings );
        
        return true;
    }
}