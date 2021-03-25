<?php

    /**
    * Compatibility for Plugin Name: WP-Optimize - Clean, Compress, Cache
    * Compatibility checked on Version: 3.0.11
    */

    if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
    
    class WPH_conflict_handle_wp_optimize
        {
                        
            var $wph;
                           
            function __construct()
                {
                    if( !   $this->is_plugin_active())
                        return FALSE;
                    
                    global $wph;
                    
                    $this->wph  =   $wph;
                        
                    add_filter( 'wpo_pre_cache_buffer', array( $this , 'wpo_pre_cache_buffer' ), 99, 2 );   
                }                        
            
            static function is_plugin_active()
                {
                    
                    include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
                    
                    if(is_plugin_active( 'wp-optimize/wp-optimize.php' ))
                        return TRUE;
                        else
                        return FALSE;
                }
            
            function wpo_pre_cache_buffer( $buffer, $flags )
                {
                    
                    $buffer =   $this->wph->ob_start_callback( $buffer );
                    
                    return $buffer;
                        
                }
   
        }
        
    new WPH_conflict_handle_wp_optimize();


?>