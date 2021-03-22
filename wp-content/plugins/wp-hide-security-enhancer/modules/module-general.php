<?php

    if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
    
    class WPH_module_general extends WPH_module
        {
      
            function load_components()
                {
                    
                    //add components
                    include(WPH_PATH . "/modules/components/general-meta.php");
                    $this->components[]  =   new WPH_module_general_meta();                    
                    
                    include(WPH_PATH . "/modules/components/general-admin-bar.php");
                    $this->components[]  =   new WPH_module_general_admin_bar();
                    
                    include(WPH_PATH . "/modules/components/general-feed.php");
                    $this->components[]  =   new WPH_module_general_feed();
                    
                    include(WPH_PATH . "/modules/components/general-robots-txt.php");
                    $this->components[]  =   new WPH_module_general_robots_txt();
                    
                    include(WPH_PATH . "/modules/components/general-wpemoji.php");
                    $this->components[]  =   new WPH_module_general_wpemoji();
                    
                    include(WPH_PATH . "/modules/components/general-styles.php");
                    $this->components[]  =   new WPH_module_general_styles();
                    
                    include(WPH_PATH . "/modules/components/general-scripts.php");
                    $this->components[]  =   new WPH_module_general_scripts();
                    
                    include(WPH_PATH . "/modules/components/general-oembed.php");
                    $this->components[]  =   new WPH_module_general_oembed();
                    
                    include(WPH_PATH . "/modules/components/general-headers.php");
                    $this->components[]  =   new WPH_module_general_headers();
                    
                    include(WPH_PATH . "/modules/components/general-html.php");
                    $this->components[]  =   new WPH_module_general_html();
                    
                    //action available for mu-plugins
                    do_action('wp-hide/module_load_components', $this);
                    
                }
            
            function use_tabs()
                {
                    
                    return TRUE;
                }
            
            function get_module_id()
                {
                    
                    return 'general';
                }
                
            function get_module_slug()
                {
                    
                    return 'wp-hide-general';   
                }
    
            function get_interface_menu_data()
                {
                    $interface_data                     =   array();
                    
                    $interface_data['menu_title']       =   __('General / Html',    'wp-hide-security-enhancer');
                    $interface_data['menu_slug']        =   self::get_module_slug();
                    $interface_data['menu_position']    =   20;
                    
                    return $interface_data;
                }
    
            function get_interface_data()
                {
      
                    $interface_data                     =   array();
                    
                    $interface_data['title']              =   __('WP Hide & Security Enhancer - General / Html',    'wp-hide-security-enhancer');
                    $interface_data['description']        =   '';
                    $interface_data['handle_title']       =   '';
                    
                    return $interface_data;
                    
                }
            
            
            
                
                
        }
    
 
?>