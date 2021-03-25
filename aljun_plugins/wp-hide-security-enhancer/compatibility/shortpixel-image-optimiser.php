<?php

    /**
    * Compatibility for Plugin Name: ShortPixel Image Optimizer
    * Compatibility checked on Version: 4.15.3
    */

    if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
    
    class WPH_conflict_shortpixel_image_optimizer
        {
                           
            static function init()
                {
                    if( !   self::is_plugin_active())
                        return FALSE;
                    
                    add_action('shortpixel_image_urls',        array( 'WPH_conflict_shortpixel_image_optimizer', 'shortpixel_image_urls') , 99, 2 );   
                }                        
            
            static function is_plugin_active()
                {
                    
                    include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
                    
                    if(is_plugin_active( 'shortpixel-image-optimiser/wp-shortpixel.php' ))
                        return TRUE;
                        else
                        return FALSE;
                }
          
                
            static function shortpixel_image_urls( $urls, $handler_id )
                {
                    global $wph;
                    
                    if ( empty ( $urls ) )
                        return $urls; 
                                        
                    //retrieve the replacements list
                    $replacement_list   =   $wph->functions->get_replacement_list();
                    
                    foreach ( $urls as  $key    =>  $url )
                        {
                            $urls[ $key ] =   $wph->functions->content_urls_replacement( $urls[ $key ],  $replacement_list );
                        }
                       
                    return $urls;    
                }
            
                            
        }
        
        
    WPH_conflict_shortpixel_image_optimizer::init();


?>