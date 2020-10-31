<?php


    /**
    * Compatibility for Plugin Name: WP Speed of Light
    * Compatibility checked on Version: 2.6.4
    */

    if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
    
    class WPH_conflict_handle_wp_speed_of_light
        {
                        
            var $wph;
                           
            function __construct()
                {
                    if( !   $this->is_plugin_active())
                        return FALSE;
                    
                    global $wph;
                    
                    $this->wph  =   $wph;
  
                    add_filter('wpsol_before_cache', array( $this, 'wpsol_before_cache' ), 999);
                    
                    //need filters to change the urls in the minified content!!!
                                           
                        
                }                        
            
            function is_plugin_active()
                {
                    
                    include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
                    
                    if(is_plugin_active( 'wp-speed-of-light/wp-speed-of-light.php' ))
                        return TRUE;
                        else
                        return FALSE;
                }
   
                
            function wpsol_before_cache( $buffer )
                {
                    
                    $buffer =   $this->wph->ob_start_callback( $buffer );
                       
                    return $buffer;   
                    
                }
                             
        }


    new WPH_conflict_handle_wp_speed_of_light();
        
?>