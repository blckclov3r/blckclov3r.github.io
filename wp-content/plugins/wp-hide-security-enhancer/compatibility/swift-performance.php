<?php

    /**
    * Compatibility for Plugin Name: Swift Performance
    * Compatibility checked on Version: 
    */

    if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
    
    class WPH_conflict_handle_swift_performance
        {
                        
            static function init()
                {
                    if( !   self::is_plugin_active())
                        return FALSE;
                        
                    add_filter( 'swift_performance_buffer',         array( 'WPH_conflict_handle_swift_performance' , 'swift_performance_buffer' ), 99 );
                    
                    add_filter( 'swift_performance_css_content',    array( 'WPH_conflict_handle_swift_performance' , 'swift_performance_css_content' ), 1, 2 );
                    add_filter( 'swift_performance_js_content',     array( 'WPH_conflict_handle_swift_performance' , 'swift_performance_js_content' ), 1, 2 );
                    
                }                        
            
            static function is_plugin_active()
                {
                    
                    include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
                    
                    if(is_plugin_active( 'swift-performance/performance.php' ))
                        return TRUE;
                        else
                        return FALSE;
                }
            
            static function swift_performance_buffer( $buffer )
                {                    
                    global $wph;
                    
                    $buffer =   $wph->ob_start_callback( $buffer );                    
                                        
                    return $buffer;
                        
                }
                
                
            static function swift_performance_css_content( $content, $key )
                {
                    global $wph;
                    
                    //retrieve the replacements list
                    $replacement_list   =   $wph->functions->get_replacement_list();
                                            
                    //replace the urls
                    $content =   $wph->functions->content_urls_replacement( $content,  $replacement_list );   
                    
                    return $content ;    
                }
                
            static function swift_performance_js_content( $content )
                {
                    global $wph;
                    
                    //retrieve the replacements list
                    $replacement_list   =   $wph->functions->get_replacement_list();
                                            
                    //replace the urls
                    $content =   $wph->functions->content_urls_replacement( $content,  $replacement_list );   
                    
                    return $content ;   
                }
   
        }
        
    WPH_conflict_handle_swift_performance::init();


?>