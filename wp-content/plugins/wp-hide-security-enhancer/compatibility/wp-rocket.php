<?php

    /**
    * Compatibility for Plugin Name: WP Rocket
    * Compatibility checked on Version: 
    */
    
    if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

    class WPH_conflict_handle_wp_rocket
        {
            var $wph;
            
            var $internals   =   array();
                            
            function __construct()
                {
                    if( !   $this->is_plugin_active() )
                        return FALSE;
                        
                    global $wph;
                    
                    $this->wph  =   $wph;
                    
                    add_filter( 'rocket_js_url',                        array( $this,   'rocket_js_url'), 999 );
                    
                    add_filter( 'rocket_css_content',                   array( $this,   'rocket_css_content'), 999, 3 );
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
            function rocket_css_content( $buffer, $source = FALSE , $target = FALSE )
                {
                    
                    if ( $target !== FALSE )
                        {
                            $target_url =   FALSE;
                            $_target     =   str_replace ( $_SERVER['DOCUMENT_ROOT'], '', wp_normalize_path ( $target ) );
                            if ( $_target != $target )
                                $target_url     =   trailingslashit ( site_url() ) .  ltrim( $_target , '/' );  
                            
            
                            $buffer     =   $this->_convert_relative_urls ( $buffer, $target_url );
                        }
                    
                    $buffer    =   $this->wph->functions->content_urls_replacement( $buffer,  $this->wph->functions->get_replacement_list() );  
                    
                    return $buffer ;     
                }
                
                
                
                
            function _convert_relative_urls( $local_file_content, $resource_path    =   FALSE )
                {
                    if ( ! empty  ( $resource_path ) )
                        $this->internals['resource_url_path']   =   dirname( $resource_path );
                    $this->internals['site_url_parsed']     =   parse_url ( site_url() );
                       
                    $local_file_content =   preg_replace_callback( '/(?:url\s?\(\s?)(?![\'\"]?(?:data:|\/\/|http))[\'\"]?([^\'\"\)\s]+)/im' ,array($this, '_convert_relative_urls_callback') , $local_file_content );    
                    
                    $this->internals['resource_url_path']   =   '';
                    
                    return $local_file_content;
                }
            
            
            /**
            * Convert relative urls to absolute
            * e.g. ../images/image.jpg
            * or  /wp-contnet/themes/default/image.jpg
            * 
            * @param mixed $match
            */
            function _convert_relative_urls_callback( $match )
                {
                    $match_block    =   $match[0];
                    
                    //check if relative to domain
                    if ( strpos ( $match[1], '/' ) === 0 )
                        $address    =   '//' . trailingslashit( $this->internals['site_url_parsed']['host'] )  .   ltrim( $match[1], '/' );
                        else 
                        {
                            //if there is no path specified, then return as is
                            if ( empty ( $this->internals['resource_url_path'] ) )
                                return $match_block;    
                            $address    =   trailingslashit( $this->internals['resource_url_path'] )  .   ltrim( $match[1], '/' );
                        }
                    
                    $address = explode('/', $address);
                    $keys = array_keys($address, '..');

                    foreach($keys as $keypos => $key)
                        array_splice($address, $key - ($keypos * 2 + 1), 2);

                    $address = implode('/', $address);
                    $address = str_replace('./', '', $address);
                    
                    $match_block    =   str_replace( $match[1], $address, $match_block );
                    
                    return $match_block;                    
                }
                                        
        }


        new WPH_conflict_handle_wp_rocket();

?>