<?php

    if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
    
    class WPH_module_admin extends WPH_module
        {
      
            function load_components()
                {
                    
                    //add components
                    include(WPH_PATH . "/modules/components/admin-new_wp_login_php.php");
                    $this->components[]  =   new WPH_module_admin_new_wp_login_php();
                    
                    include(WPH_PATH . "/modules/components/admin-admin_url.php");
                    $this->components[]  =   new WPH_module_admin_admin_url();
                    
                    //action available for mu-plugins
                    do_action('wp-hide/module_load_components', $this);
                    
                }
            
            function use_tabs()
                {
                    
                    return TRUE;
                }
            
            function get_module_id()
                {
                    
                    return 'admin';
                }
                
            function get_module_slug()
                {
                    
                    return 'wp-hide-admin';   
                }
    
            function get_interface_menu_data()
                {
                    $interface_data                     =   array();
                    
                    $interface_data['menu_title']       =   __('Admin',    'wp-hide-security-enhancer');
                    $interface_data['menu_slug']        =   self::get_module_slug();
                    $interface_data['menu_position']    =   30;
                    
                    return $interface_data;
                }
    
            function get_interface_data()
                {
      
                    $interface_data                     =   array();
                    
                    $interface_data['title']              =   __('WP Hide & Security Enhancer - Admin',    'wp-hide-security-enhancer');
                    $interface_data['description']        =   '';
                    $interface_data['handle_title']       =   '';
                    
                    return $interface_data;
                    
                }
                
                       
        }
    
 
?>