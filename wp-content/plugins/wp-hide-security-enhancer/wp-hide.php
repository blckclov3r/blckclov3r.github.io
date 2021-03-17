<?php
/*
Plugin Name: WP Hide & Security Enhancer
Plugin URI: https://www.wp-hide.com/
Description: Hide and increase Security for your WordPress website instance using smart techniques. No files are changed on your server.
Author: Nsp Code
Author URI: http://www.nsp-code.com 
Version: 1.6.3.1
Text Domain: wp-hide-security-enhancer
Domain Path: /languages/
*/
    
    if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
    
    //if mu-plugins component not being loaded trigger a later init
    if(!defined('WPH_PATH'))
        {
                        
            define('WPH_PATH',              plugin_dir_path(__FILE__));
            define('WPH_CACHE_PATH',        WP_CONTENT_DIR . '/cache/wph/' );
            
            define('WPH_URL',               str_replace(array('https:', 'http:'), "", plugins_url() . '/wp-hide-security-enhancer' ) );
            
            include_once(WPH_PATH . '/include/wph.class.php');
            include_once(WPH_PATH . '/include/functions.class.php');
            
            include_once(WPH_PATH . '/include/module.class.php');
            include_once(WPH_PATH . '/include/module.component.class.php');
            
            global $wph;
            $wph    =   new WPH();
            $wph->init();
            
            ob_start(array($wph, 'ob_start_callback'));
            
        }
    
    //Avoid issues if the mu loader has not updated yet
    if(!defined('WPH_URL'))
        define('WPH_URL',               plugins_url() . '/wp-hide-security-enhancer' );
          
    //load language files
    add_action( 'plugins_loaded', 'WPH_load_textdomain'); 
    function WPH_load_textdomain() 
        {
            load_plugin_textdomain('wp-hide-security-enhancer', FALSE, dirname( plugin_basename( __FILE__ ) ) . '/languages');
        }
    
    
    register_activation_hook(   __FILE__, 'WPH_activated');
    register_deactivation_hook( __FILE__, 'WPH_deactivated');

    function WPH_activated($network_wide) 
        {
            
            flush_rewrite_rules();     
            
            global $wph;
            
            //check if permalinks where saved
            $wph->custom_permalinks_applied   =   $wph->functions->rewrite_rules_applied();
            
            //reprocess components if the permalinks where applied
            if($wph->custom_permalinks_applied   === TRUE)
                {
                    $wph->_modules_components_run();
                }
            
        }

    function WPH_deactivated() 
        {
            
            global $wph;
            
            $wph->uninstall =   TRUE;
            flush_rewrite_rules();
            
            //replace the mu-loader
            WPH_functions::unlink_mu_loader();
            
        }    
        
?>