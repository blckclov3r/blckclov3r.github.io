<?php


    /**
    * Compatibility for Plugin Name: Fluent Forms - Best Form Plugin for WordPress 
    * Compatibility checked on Version: 3.5.5
    */

    class WPH_conflict_handle_fluentform
        {
                        
            static function init()
                {
                    if( !   self::is_plugin_active() )
                        return FALSE;
                    
                    add_action('wp-hide/content_urls_replacement',        array( 'WPH_conflict_handle_fluentform',    '_content_urls_replacement' ), 10, 2 );
                    
                }                        
            
            static function is_plugin_active()
                {
                    
                    include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
                    
                    if( is_plugin_active( 'fluentform/fluentform.php' ) )
                        return TRUE;
                        else
                        return FALSE;
                }
                       
            static function _content_urls_replacement( $text, $_replacements )
                {
                                            
                    global $wph;
                    
                    /**
                    * Process Double json encoded urls
                    */
                    foreach($_replacements   as $old_url =>  $new_url)
                        {
                            $old_url    =   trim(json_encode( trim(json_encode($old_url), '"') ), '"');
                            $new_url    =   trim(json_encode( trim(json_encode($new_url), '"') ), '"');
                  
                            $text =   str_ireplace(    $old_url, $new_url  ,$text   );
                        }
                       
                    return $text; 
                    
                }

           
        }
        
        
    WPH_conflict_handle_fluentform::init();



?>