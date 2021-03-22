<?php

/**
* Compatibility for Plugin Name: WooGlobalCart
* Compatibility checked on Version: 1.3.8
*/


    class WPH_conflict_handle_wgc
        {
                        
            static function init()
                {
                    add_action('plugins_loaded',        array('WPH_conflict_handle_wgc', 'run') , -1);    
                }                        
            
            static function is_plugin_active()
                {
                    
                    include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
                    
                    if(is_plugin_active( 'woo-global-cart/woo-global-cart.php' ))
                        return TRUE;
                        else
                        return FALSE;
                }
            
            static public function run()
                {   
                    if( !   self::is_plugin_active())
                        return FALSE;
                    
                    global $wph;
                                        
                    add_filter ('woogc/on_shutdown/ob_buferring_output', array('WPH_conflict_handle_wgc', 'status_ob_buferring_output'), 10, 2);
                               
                }
                 
            static function status_ob_buferring_output( $status, $ob_get_status )
                {
                    
                    if  ( is_array( $ob_get_status )    &&  $ob_get_status['name']  ==  'WPH::ob_start_callback' )
                        {
                            $status =   FALSE;
                        }    
                    
                    return $status;    
                }
                            
        }


?>