<?php

    /**
    * Compatibility for Plugin Name: BuddyPress
    * Compatibility checked on Version: 
    */

    if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
    
    class WPH_conflict_handle_BuddyPress
        {
                        
            static function init()
                {
                    if( !   self::is_plugin_active())
                        return false;
                                            
                    //adjust bp_core_avatar_url
                    add_filter('bp_core_avatar_url', array('WPH_conflict_handle_BuddyPress', 'bp_core_avatar_url'), 999);
                    
                }                        
            
            static function is_plugin_active()
                {
                    
                    include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
                    
                    if(is_plugin_active( 'buddypress/bp-loader.php' ))
                        return true;
                        else
                        return false;
                }
            
            static public function budypress()
                {   
                    
                               
                }
     
                
            static function bp_core_avatar_url( $url )
                {
                    global $wph;
                         
                    //do replacements for this url
                    $url    =   $wph->functions->content_urls_replacement( $url,  $wph->functions->get_replacement_list() );                    
                
                    return $url;
                    
                }    
                 
            
                            
        }


?>