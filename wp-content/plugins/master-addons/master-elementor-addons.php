<?php

/**
 * Plugin Name: Master Addons for Elementor
 * Description: Master Addons is easy and must have Elementor Addons for WordPress Page Builder. Clean, Modern, Hand crafted designed Addons blocks.
 * Plugin URI: https://master-addons.com/all-widgets/
 * Author: Jewel Theme
 * Version: 1.5.3
 * Author URI: https://master-addons.com
 * Text Domain: mela
 * Domain Path: /languages
 */
// No, Direct access Sir !!!
if ( !defined( 'ABSPATH' ) ) {
    exit;
}
$jltma_plugin_data = get_file_data( __FILE__, array(
    'Version'     => 'Version',
    'Plugin Name' => 'Plugin Name',
), false );
$jltma_plugin_name = $jltma_plugin_data['Plugin Name'];
$jltma_plugin_version = $jltma_plugin_data['Version'];
define( 'JLTMA_NAME', $jltma_plugin_name );
define( 'JLTMA_PLUGIN_VERSION', $jltma_plugin_version );

if ( function_exists( 'ma_el_fs' ) ) {
    ma_el_fs()->set_basename( false, __FILE__ );
} else {
    
    if ( !function_exists( 'ma_el_fs' ) ) {
        // Create a helper function for easy SDK access.
        function ma_el_fs()
        {
            global  $ma_el_fs ;
            
            if ( !isset( $ma_el_fs ) ) {
                // Activate multisite network integration.
                if ( !defined( 'WP_FS__PRODUCT_4015_MULTISITE' ) ) {
                    define( 'WP_FS__PRODUCT_4015_MULTISITE', true );
                }
                // Include Freemius SDK.
                require_once dirname( __FILE__ ) . '/lib/freemius/start.php';
                $ma_el_fs = fs_dynamic_init( [
                    'id'              => '4015',
                    'slug'            => 'master-addons',
                    'type'            => 'plugin',
                    'public_key'      => 'pk_3c9b5b4e47a06288e3500c7bf812e',
                    'is_premium'      => false,
                    'has_affiliation' => 'all',
                    'has_addons'      => false,
                    'has_paid_plans'  => true,
                    'trial'           => [
                    'days'               => 14,
                    'is_require_payment' => false,
                ],
                    'menu'            => [
                    'slug'       => 'master-addons-settings',
                    'first-path' => 'admin.php?page=master-addons-settings',
                    'account'    => true,
                ],
                    'is_live'         => true,
                ] );
            }
            
            return $ma_el_fs;
        }
        
        // Init Freemius.
        ma_el_fs();
        // Signal that SDK was initiated.
        do_action( 'ma_el_fs_loaded' );
    }

}

// Instantiate Master Addons Class
if ( !class_exists( '\\MasterAddons\\Master_Elementor_Addons' ) ) {
    require_once dirname( __FILE__ ) . '/class-master-elementor-addons.php';
}
if ( ma_el_fs()->is_not_paying() ) {
    require_once dirname( __FILE__ ) . '/inc/freemius-config.php';
}
// Activation and Deactivation hooks

if ( class_exists( '\\MasterAddons\\Master_Elementor_Addons' ) ) {
    register_activation_hook( __FILE__, array( '\\MasterAddons\\Master_Elementor_Addons', 'jltma_plugin_activation_hook' ) );
    register_deactivation_hook( __FILE__, array( '\\MasterAddons\\Master_Elementor_Addons', 'jltma_plugin_deactivation_hook' ) );
}
