<?php


    /**
    * Compatibility for Plugin Name: Asset CleanUp Pro: Page Speed Booster
    * Compatibility checked on Version:  1.1.7.6
    */
    
    if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

    class WPH_conflict_handle_wpacu
        {
            var $wph;
                            
            function __construct()
                {
                    if( !   $this->is_plugin_active() )
                        return FALSE;
                        
                    global $wph;
                    
                    $this->wph  =   $wph;
                    
                    add_filter( 'wpacu_html_source_after_optimization',             array( $this,   'process_buffer'), 999 );       

                }                        
            
            function is_plugin_active()
                {
                    
                    include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
                    
                    if( is_plugin_active( 'wp-asset-clean-up-pro/wpacu.php' ) || is_plugin_active( 'wp-asset-clean-up/wpacu.php' ) )
                        return TRUE;
                        else
                        return FALSE;
                }
                
                
            function process_buffer( $buffer )
                {
                         
                    
                    $buffer =   $this->wph->ob_start_callback( $buffer );
                    
                    return $buffer;
                    
                }
                            
        }


        new WPH_conflict_handle_wpacu();
        
?>