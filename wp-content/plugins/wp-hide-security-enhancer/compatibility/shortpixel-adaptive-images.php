<?php

    /**
    * Compatibility for Plugin Name: ShortPixel Adaptive Images
    * Compatibility checked on Version: 0.9.2
    */

    if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
    
    class WPH_conflict_shortpixel_ai
        {
                           
            static function init()
                {
                    if( !   self::is_plugin_active())
                        return FALSE;
                    
                    add_action('wp_calculate_image_srcset',        array( 'WPH_conflict_shortpixel_ai', 'wp_calculate_image_srcset') , -1, 5);   
                    
                    add_action( 'init',                            array( 'WPH_conflict_shortpixel_ai', 'init_ob'), 2 ); 
                }                        
            
            static function is_plugin_active()
                {
                    
                    include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
                    
                    if(is_plugin_active( 'shortpixel-adaptive-images/short-pixel-ai.php' ))
                        return TRUE;
                        else
                        return FALSE;
                }
            
            static public function wp_calculate_image_srcset( $sources, $size_array, $image_src, $image_meta, $attachment_id )
                {   
                    //replace the urls
                    global $wph;
                    
                    //retrieve the replacements list
                    $replacement_list   =   $wph->functions->get_replacement_list();
                                            
                    //replace the urls
                    foreach ( $sources as $size =>  $data ) 
                        {
                            $sources[$size]['url'] =   $wph->functions->content_urls_replacement( $sources[$size]['url'],  $replacement_list );
                        }
                    
                    return $sources;    
                               
                }
                
                
            static public function init_ob()
                {
                    
                    if (is_feed()
                        || (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
                        || (defined('DOING_CRON') && DOING_CRON)
                        || (defined('WP_CLI') && WP_CLI)
                        || (is_admin() && function_exists("is_user_logged_in") && is_user_logged_in()
                            && !(function_exists("wp_doing_ajax") && wp_doing_ajax())
                            && !(defined( 'DOING_AJAX' ) && DOING_AJAX))
                    ) {
                        return;
                    }
                    
                    ob_start( array ( 'WPH_conflict_shortpixel_ai', 'maybe_replace_images_src' ) );   
                    
                }
            
            
            static function maybe_replace_images_src( $content )
                {
                    $content = preg_replace_callback(  '/=("|\')([^"|\']+.(jpg|jpeg|png))/im', array( 'WPH_conflict_shortpixel_ai', '_replace_image_slug') , $content);
                       
                    return $content;    
                }
                
            static function _replace_image_slug( $match )
                {
                    global $wph;
                    
                    $found  =   $match[0];
                    
                    $replacements   =   $wph->functions->get_replacement_list();
                    
                    //do simple replacements
                    foreach($replacements   as  $replace    =>  $replace_to)
                        {
                            $found  =   str_replace($replace, $replace_to, $found);
                        }
                    
                    return $found;
                }
                                
        }


?>