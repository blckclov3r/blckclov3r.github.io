<?php

    /**
    * Compatibility for Plugin Name: Cache Enabler
    * Compatibility checked on Version: 1.3.4
    */

    if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
    
    class WPH_conflict_handle_cache_enabler
        {
                        
            static function init()
                {
                    if( !   self::is_plugin_active() )
                        return FALSE;
                    
                    add_filter( 'cache_enabler_before_store',                    array( 'WPH_conflict_handle_cache_enabler', 'cache_enabler_before_store'), 999 );
                    
                }                        
            
            static function is_plugin_active()
                {
                    
                    include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
                    
                    if(is_plugin_active( 'cache-enabler/cache-enabler.php' ))
                        return TRUE;
                        else
                        return FALSE;
                }
                       
            static function cache_enabler_before_store( $buffer )
                {
                                            
                    global $wph;
                    
                    $buffer    =   $wph->functions->content_urls_replacement( $buffer,  $wph->functions->get_replacement_list() );
                       
                    return $buffer; 
                    
                }
           
        }
        
        
    WPH_conflict_handle_cache_enabler::init();


?>