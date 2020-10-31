<?php

    /**
    * Compatibility for Plugin Name: WP Rocket
    * Compatibility checked on Version: 
    */
    
    if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

    class WPH_conflict_handle_wp_rocket
        {
            var $wph;
                            
            function __construct()
                {
                    if( !   $this->is_plugin_active() )
                        return FALSE;
                        
                    global $wph;
                    
                    $this->wph  =   $wph;
                    
                    add_filter( 'rocket_js_url',                        array( $this,   'rocket_js_url'), 999 );
                    
                    add_filter( 'rocket_css_content',                   array( $this,   'rocket_css_content'), 999 );
                    /**
                    * 
                    * STILL THEY ARE MISSING A FILTER FOR JS Content !!!!!!   ....
                    */

                }                        
            
            function is_plugin_active()
                {
                    
                    include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
                    
                    if(is_plugin_active( 'wp-rocket/wp-rocket.php' ))
                        return TRUE;
                        else
                        return FALSE;
                }
                
                
            function rocket_buffer( $buffer )
                {
                    
                    $buffer =   $this->wph->ob_start_callback( $buffer );
                    
                    return $buffer;
                    
                }
                
                
            /**
            * Replace js urls
            *     
            * @param mixed $url
            */
            function rocket_js_url( $buffer )
                {
                    
                    //retrieve the replacements list
                    $buffer    =   $this->wph->functions->content_urls_replacement( $buffer,  $this->wph->functions->get_replacement_list() );  
                    
                    return $buffer ;   
                }
            
            
            
            /**
            * Process the Cache CSS content
            * 
            * @param mixed $content
            */
            function rocket_css_content( $buffer )
                {
                    $buffer    =   $this->wph->functions->content_urls_replacement( $buffer,  $this->wph->functions->get_replacement_list() );  
                    
                    return $buffer ;     
                }
                                        
        }


        new WPH_conflict_handle_wp_rocket();

?>