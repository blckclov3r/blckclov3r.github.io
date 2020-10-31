<?php


    class WPH_conflict_handle_wp_fastest_cache
        {
                        
            static function init()
                {
                    if( !   self::is_plugin_active())
                        return FALSE;
                        
                    add_filter( 'wpfc_buffer_callback_filter', array( 'WPH_conflict_handle_wp_fastest_cache' , 'wpfc_cache_callback_filter' ), 99, 2 );   
                }                        
            
            static function is_plugin_active()
                {
                    
                    include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
                    
                    if(is_plugin_active( 'wp-fastest-cache/wpFastestCache.php' ))
                        return TRUE;
                        else
                        return FALSE;
                }
            
            static function wpfc_cache_callback_filter( $buffer, $extension )
                {
                    
                    global $wph;
                    
                    $buffer =   $wph->ob_start_callback( $buffer );
                    
                    return $buffer;
                        
                }
   
        }


?>