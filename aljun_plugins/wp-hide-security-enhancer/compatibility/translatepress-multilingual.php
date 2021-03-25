<?php

    /**
    * Compatibility for Plugin Name: TranslatePress - Multilingual
    * Since: 1.9.5
    */

    if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
    
    class WPH_conflict_handle_tp_multilingual
        {
                        
            var $wph;
            
            function __construct()
                {
                    if( !   $this->is_plugin_active() )
                        return FALSE;
                        
                    global $wph;
                    $this->wph  =   $wph;
                    
                    add_filter( 'trp_is_admin_link', array ( $this, 'trp_is_admin_link' ), 99, 4 );
                    
                }                        
            
            function is_plugin_active()
                {
                    
                    include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
                    
                    if( is_plugin_active( 'translatepress-multilingual/index.php' ) )
                        return TRUE;
                        else
                        return FALSE;
                }
                
            
            function trp_is_admin_link( $is_admin_link, $url, $admin_url, $wp_login_url )
                {
                    $new_wp_login_php       =   $new_login  =   $this->wph->functions->get_module_item_setting('new_wp_login_php');
                    $login_url              =   home_url( $new_wp_login_php, 'login' );
                    
                    $default_login_url      =   site_url( 'wp-login', 'login');
                    
                    if ( strpos( $url, $default_login_url ) !== false || strpos( $url, $new_wp_login_php ) !== false 
                        ||    strpos( $url, $admin_url ) !== false ){
                        $is_admin_link = true;
                    } else {
                        $is_admin_link = false;
                    }  
                    
                    return $is_admin_link;
                       
                }
           
        }
        
        
    new WPH_conflict_handle_tp_multilingual();


?>