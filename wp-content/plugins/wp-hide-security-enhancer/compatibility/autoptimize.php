<?php

    /**
    * Plugin Compatibility      :   Autoptimize
    * Introduced at version     :   2.5.0
    */


    class WPH_conflict_handle_autoptimize
        {
                        
            static function init()
                {
                    if( !   self::is_plugin_active())
                        return false;
                    
                    add_filter( 'autoptimize_css_after_minify',     array( 'WPH_conflict_handle_autoptimize', 'autoptimize_css_after_minify' ), 999);
                    add_filter( 'autoptimize_js_after_minify',      array( 'WPH_conflict_handle_autoptimize', 'autoptimize_js_after_minify' ),  999);
                    
                }                        
            
            static function is_plugin_active()
                {
                    
                    include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
                    
                    if(is_plugin_active( 'autoptimize/autoptimize.php' ))
                        return true;
                        else
                        return false;
                }
            
            static public function autoptimize_css_after_minify( $code )
                {   
                    global $wph; 
                    
                    //applay the replacements
                    $code  =   $wph->ob_start_callback( $code );
                    
                    return $code;
                                 
                }
                      
            static public function autoptimize_js_after_minify( $code )
                {   
                    global $wph; 
                    
                    //applay the replacements
                    $code  =   $wph->ob_start_callback( $code );
                    
                    return $code;
                                 
                }
                            
        }
        
    WPH_conflict_handle_autoptimize::init();


?>