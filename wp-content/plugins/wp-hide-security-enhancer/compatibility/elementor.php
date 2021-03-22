<?php

    /**
    * Compatibility for Plugin Name: Elementor
    * Compatibility checked on Version: 2.5.16
    */

    if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
    
    use Elementor\Core\Files\Manager as Files_Manager;
    
    class WPH_conflict_elementor
        {
                        
            static function init()
                {
                    if( !   self::is_plugin_active())
                        return FALSE;
                    
                    global $wph;
                    
                    add_action( 'wph/settings_changed',         array( 'WPH_conflict_elementor',    'settings_changed') );

                    //filter the urls of the outputed widget content since there's no way to catch the outrputed buffer, elementor does this on it's own..
                    add_filter( 'elementor/widget/render_content',                  array( 'WPH_conflict_elementor', 'elementor_widget_render_content'), 999, 2);
                }                        
            
            static function is_plugin_active()
                {
                    
                    include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
                    
                    if(is_plugin_active( 'elementor/elementor.php' ))
                        return TRUE;
                        else
                        return FALSE;
                }
                
                
            static function settings_changed()
                {
                    
                    $files_manager = new Files_Manager();
                    $files_manager->clear_cache();
                    
                }
                
                
                
            static function elementor_widget_render_content( $widget_content, $class )
                {
                    global $wph;
                    
                    //do replacements for this url
                    $widget_content    =   $wph->functions->content_urls_replacement( $widget_content,  $wph->functions->get_replacement_list() );                    
                                       
                    return $widget_content;
                }
                
                
                
      
                            
        }


?>