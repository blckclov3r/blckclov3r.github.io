<?php

/**
* Compatibility for Plugin Name: WP Smush  and WP Smush PRO
* Compatibility checked on Version: 3.4.0
*/

    class WPH_conflict_handle_wp_smush
        {
                        
            static function init()
                {
                    if( !   self::is_plugin_active() )
                        return FALSE;
                    
                    add_filter( 'smush_filter_generate_cdn_url',                    array( 'WPH_conflict_handle_wp_smush', 'smush_filter_generate_cdn_url'), 1 );
                    
                }                        
            
            static function is_plugin_active()
                {
                    
                    include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
                    
                    if( is_plugin_active( 'wp-smushit/wp-smush.php' )   ||  is_plugin_active( 'wp-smush-pro/wp-smush.php' ) )
                        return TRUE;
                        else
                        return FALSE;
                }
                       
            static function smush_filter_generate_cdn_url( $src )
                {
                                            
                    global $wph;
                    
                    $src    =   $wph->functions->content_urls_replacement( $src,  $wph->functions->get_replacement_list() );
                       
                    return $src; 
                    
                }

           
        }
        
        
    WPH_conflict_handle_wp_smush::init();


?>