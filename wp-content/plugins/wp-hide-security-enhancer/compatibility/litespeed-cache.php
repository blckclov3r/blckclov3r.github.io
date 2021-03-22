<?php

    
    /**
    * Compatibility for Plugin Name: LiteSpeed Cache
    * Compatibility checked on Version: 3.2.3.2
    */
    
    if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
    
    class WPH_conflict_handle_litespeed_cache
        {
            
            var $wph;
            
            function __construct()
                {
                    if( !   $this->is_plugin_active() )
                        return FALSE;
                        
                    global $wph;
                    
                    $this->wph  =   $wph;
                    
                    add_action('litespeed_optm_cssjs',                              array( $this, 'litespeed_optm_cssjs') , 999, 3 );
                    add_action('litespeed_ccss',                                    array( $this, 'litespeed_optm_cssjs') , 999, 2 );
                                        
                }                        
            
            function is_plugin_active()
                {
                    include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
                    
                    if(is_plugin_active( 'litespeed-cache/litespeed-cache.php' ))
                        return TRUE;
                        else
                        return FALSE;
                }
            
                  
            function litespeed_optm_cssjs( $buffer, $file_type, $src_list = array()  )
                {
                    $buffer    =   $this->wph->functions->content_urls_replacement( $buffer,  $this->wph->functions->get_replacement_list() );      
                                                    
                    return $buffer;   
                }
   
                
        }

        
    new WPH_conflict_handle_litespeed_cache();

?>