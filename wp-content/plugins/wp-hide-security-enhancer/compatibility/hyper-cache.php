<?php

    /**
    * Compatibility for Plugin Name: Hyper Cache
    * Compatibility checked on Version: 3.3.9
    */

    if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
    
    class WPH_conflict_handle_hyper_cache
        {
                        
            var $wph;
                           
            function __construct()
                {
                    if( !   $this->is_plugin_active())
                        return FALSE;
                    
                    global $wph;
                    
                    $this->wph  =   $wph;
                        
                    add_filter( 'cache_buffer', array( $this , 'cache_buffer' ), 99 );   
                }                        
            
            static function is_plugin_active()
                {
                    
                    include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
                    
                    if(is_plugin_active( 'hyper-cache/plugin.php' ))
                        return TRUE;
                        else
                        return FALSE;
                }
            
            function cache_buffer( $buffer )
                {
                           
                    $buffer =   $this->wph->ob_start_callback( $buffer );
                       
                    return $buffer;
                        
                }
   
        }
        
    new WPH_conflict_handle_hyper_cache();


?>