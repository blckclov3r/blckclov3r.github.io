<?php
    
    if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
    
    /**
    * 
    *   WP Hide & Security Enhancer - MU plugin loader
    * 
    * 
    */

    //check if the plugin still exists, or this file should be removed
    if(! file_exists(WP_PLUGIN_DIR . '/wp-hide-security-enhancer/wp-hide.php' ))
        return FALSE;
    
    //check if the plugin is active
    $active_plugins =   (array)get_option('active_plugins');
    if( !in_array( 'wp-hide-security-enhancer/wp-hide.php' , $active_plugins) )
        return FALSE;
    
    define('WPH_PATH',              trailingslashit( dirname( WP_PLUGIN_DIR . '/wp-hide-security-enhancer/wp-hide.php' ) )  );
    define('WPH_MULOADER',          TRUE);
    define('WPH_MULOADER_VERSION',  '1.3.5');
    
    define('WPH_URL',               str_replace(array('https:', 'http:'), "", plugins_url() . '/wp-hide-security-enhancer' ) );
    
    include_once(WPH_PATH . '/include/wph.class.php');
    include_once(WPH_PATH . '/include/functions.class.php');
    
    include_once(WPH_PATH . '/include/module.class.php');
    include_once(WPH_PATH . '/include/module.component.class.php');
    
    
    global $wph;
    $wph    =   new WPH();
    $wph->init();
    
    /**
    * Early Turn ON buffering to allow a callback
    * 
    */
    ob_start(array($wph, 'ob_start_callback'));
    

?>