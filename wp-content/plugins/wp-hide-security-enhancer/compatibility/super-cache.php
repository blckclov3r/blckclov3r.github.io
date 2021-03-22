<?php


    class WPH_conflict_handle_super_cache
        {
                        
            static function init()
                {
                    add_action('plugins_loaded',        array('WPH_conflict_handle_super_cache', 'wpcache') , -1);    
                }                        
            
            static function is_plugin_active()
                {
                    
                    include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
                    
                    if(is_plugin_active( 'wp-super-cache/wp-cache.php' ))
                        return TRUE;
                        else
                        return FALSE;
                }
            
            static public function wpcache()
                {   
                    if( !   self::is_plugin_active())
                        return FALSE;
                    
                    global $wph;
                    
                    //add bufer filtering for sueprcache plugin
                    add_filter('wp_cache_ob_callback_filter', array($wph, 'ob_start_callback'), 999);
                               
                }
                            
        }


?>