<?php

/**
* Compatibility for Plugin Name: Fusion Builder
* Compatibility checked on Version: 1.4.2
*/


    class WPH_conflict_fusion_builder
        {
                        
            static function init()
                {
                    if( !   self::is_plugin_active())
                        return FALSE;
                                        
                    add_action('wph/settings_changed',  array( 'WPH_conflict_fusion_builder',    'settings_changed'));
                    
                }                        
            
            static function is_plugin_active()
                {
                    
                    include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
                    
                    if(is_plugin_active( 'fusion-builder/fusion-builder.php' ))
                        return TRUE;
                        else
                        return FALSE;
                }
                
                
            static function settings_changed()
                {
                    $fusion_cache = new Fusion_Cache();
                    $fusion_cache->reset_all_caches();
                }
      
                            
        }
        
    
    WPH_conflict_fusion_builder::init();


?>