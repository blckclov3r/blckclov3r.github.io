<?php

    /**
    * Compatibility for Plugin Name: WP Fastest Cache
    * Compatibility checked on Version: 0.9.0.6 
    */
    
    if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
    
    class WPH_conflict_handle_w3_cache
        {
                        
            static function init()
                {
                    add_action('plugins_loaded',                            array('WPH_conflict_handle_w3_cache', 'pagecache') , -1);
                    
                    add_filter( 'w3tc_filename_to_url',                     array('WPH_conflict_handle_w3_cache', 'w3tc_filename_to_url') , -1);
                    
                    add_filter( 'w3tc_minify_file_handler_minify_options',  array('WPH_conflict_handle_w3_cache', 'w3tc_minify_file_handler_minify_options') );  
                }                        
            
            static function is_plugin_active()
                {
                    if(defined('W3TC_VERSION'))
                        return TRUE;
                        else
                        return FALSE;
                }
            
            static public function pagecache()
                {   
                    if( !   self::is_plugin_active())
                        return FALSE;
                    
                    //check if there's a pagecache callback
                    if(isset($GLOBALS['_w3tc_ob_callbacks'])    &&  isset($GLOBALS['_w3tc_ob_callbacks']['pagecache']))
                        {
                            $GLOBALS['WPH_w3tc_ob_callbacks']['pagecache'] =   $GLOBALS['_w3tc_ob_callbacks']['pagecache'];
                            
                            //hijackthe callback
                            $GLOBALS['_w3tc_ob_callbacks']['pagecache'] =   array( 'WPH_conflict_handle_w3_cache', 'pagecache_callback');   
                        }
                               
                }
                
            static public function pagecache_callback($value)
                {
                    global $wph;
                    
                    //applay the replacements
                    $value  =   $wph->ob_start_callback($value);
                    
                    //allow the W3-Cache to continur the initial callback
                    $callback = $GLOBALS['WPH_w3tc_ob_callbacks']['pagecache'];
                    if (is_callable($callback)) 
                        {
                            $value = call_user_func($callback, $value);
                        }
                    
                    return $value;   
                }
            
            
            static public function w3tc_filename_to_url( $url )
                {
                    global $wph;
                    
                    //do replacements for this url
                    $url    =   $wph->functions->content_urls_replacement($url,  $wph->functions->get_replacement_list() );
                       
                    return $url;   
                }
                
                
            static public function on_init()
                {
                    
                    if ( preg_match( '/\/cache\/minify\/\w+\.(?:css|js)/i', $_SERVER['REQUEST_URI'] ) )
                        {
                            add_filter( 'wp-hide/ignore_ob_start_callback', '__return_true' );
                        }
                    
                }
                
                
            static function w3tc_minify_file_handler_minify_options( $serve_options)
                {
                    $serve_options['postprocessor'] =   array( 'WPH_conflict_handle_w3_cache', 'w3tc_process_content' );
                    
                    return $serve_options;
                }
                
            static function w3tc_process_content( $content, $type )
                {
                    global $wph;
                    
                    //applay the replacements
                    $content  =   $wph->ob_start_callback( $content ); 

                    if ( preg_match( '/\/cache\/minify\/\w+\.(?:css|js)/i', $_SERVER['REQUEST_URI'] ) )
                        {
                            add_filter( 'wp-hide/ignore_ob_start_callback', '__return_true' );
                        }
                       
                    return $content;   
                }
                
        }


?>