<?php

    if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
    
    class WPH_module_rewrite extends WPH_module
        {
                
            function load_components()
                {
                    
                    //add components
                    include(WPH_PATH . "/modules/components/rewrite-default.php");
                    $this->components[]  =   new WPH_module_rewrite_default();
                    
                    include(WPH_PATH . "/modules/components/rewrite-new_theme_path.php");
                    $this->components[]  =   new WPH_module_rewrite_new_theme_path();
                    
                    include(WPH_PATH . "/modules/components/rewrite-new_include_path.php");
                    $this->components[]  =   new WPH_module_rewrite_new_include_path();
                    
                    include(WPH_PATH . "/modules/components/rewrite-wp_content_path.php");
                    $this->components[]  =   new WPH_module_rewrite_wp_content_path();
                    
                    include(WPH_PATH . "/modules/components/rewrite-new_plugin_path.php");
                    $this->components[]  =   new WPH_module_rewrite_new_plugin_path();
                    
                    include(WPH_PATH . "/modules/components/rewrite-new_upload_path.php");
                    $this->components[]  =   new WPH_module_rewrite_new_upload_path();
                    
                    include(WPH_PATH . "/modules/components/rewrite-comments.php");
                    $this->components[]  =   new WPH_module_rewrite_comments();
                    
                    include(WPH_PATH . "/modules/components/rewrite-author.php");
                    $this->components[]  =   new WPH_module_rewrite_author();
                    
                    include(WPH_PATH . "/modules/components/rewrite-search.php");
                    $this->components[]  =   new WPH_module_rewrite_search();
                     
                    include(WPH_PATH . "/modules/components/rewrite-new_xml-rpc-path.php");
                    $this->components[]  =   new WPH_module_rewrite_new_xml_rpc_path();
                    
                    include(WPH_PATH . "/modules/components/rewrite-json-rest.php");
                    $this->components[]  =   new WPH_module_rewrite_json_rest();
                    
                    include(WPH_PATH . "/modules/components/rewrite-root-files.php");
                    $this->components[]  =   new WPH_module_rewrite_root_files();
                    
                    include(WPH_PATH . "/modules/components/rewrite-slash.php");
                    $this->components[]  =   new WPH_module_rewrite_slash();
                    
                    
                    //action available for mu-plugins
                    do_action('wp-hide/module_load_components', $this);
                    
                }
            
            function use_tabs()
                {
                    
                    return true;
                }
            
            function get_module_id()
                {
                    return 'rewrite';
                }
                
            function get_module_slug()
                {
                    return 'wp-hide-rewrite';   
                }
    
            function get_interface_menu_data()
                {
                    $interface_data                     =   array();
                    
                    $interface_data['menu_title']       =   __('Rewrite',    'wp-hide-security-enhancer');
                    $interface_data['menu_slug']        =   self::get_module_slug();
                    $interface_data['menu_position']    =   1;
                    
                    return $interface_data;
                }
    
            function get_interface_data()
                {
                    $interface_data                     =   array();
                    
                    $interface_data['title']              =   __('WP Hide & Security Enhancer',    'wp-hide-security-enhancer') . ' - ' . __('Rewrite',    'wp-hide-security-enhancer');
                    $interface_data['description']        =   '';
                    $interface_data['handle_title']       =   '';
                    
                    return $interface_data;
                }
                
            
                
        }
    
 
?>