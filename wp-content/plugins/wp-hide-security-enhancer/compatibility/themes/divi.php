<?php
    
    /**
    * Theme Compatibility   :   DIVI
    * Introduced at version :   3.17.6* 
    */
    
    if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
    
    
    class WPH_conflict_theme_divi
        {
                        
            static function init()
                {
                    add_action('et_builder_custom_fonts',                       array('WPH_conflict_theme_divi',    'process_et_builder_custom_fonts'));
                    
                    add_action('et_core_page_resource_get_data',                array('WPH_conflict_theme_divi',    'process'), 99, 3);
                    
                    add_action( 'wph/settings_changed',                         array( 'WPH_conflict_theme_divi',   'settings_changed') );
                }                        
            
              
            static public function process( $resource_data, $context, $object )
                {   
                    
                    global $wph;
                    
                    $replacement_list               =   $wph->functions->get_replacement_list();
                    
                    foreach ( $resource_data as $priority => $data_part ) 
                        {
                            foreach ( $data_part as $key    =>  $data ) 
                                {
                                    $resource_data[ $priority ][ $key ] =   $wph->functions->content_urls_replacement( $data,  $replacement_list ); 
                                }
                        }
                        
                    return $resource_data;
                               
                }
            
            
            /**
            * Process the cutom fonts
            *     
            * @param mixed $all_custom_fonts
            */
            static public function process_et_builder_custom_fonts( $all_custom_fonts )
                {
                    
                    if  ( ! is_array($all_custom_fonts)     ||  count ( $all_custom_fonts ) < 1 )
                        return $all_custom_fonts;
                    
                    global $wph;
                    
                    $replacement_list   =   $wph->functions->get_replacement_list();
                        
                    foreach  ( $all_custom_fonts as $font   =>  $font_data )
                        {
                            $font_urls  =   $font_data['font_url'];
                            if ( !is_array( $font_urls ) || count ( $font_urls ) < 1 )
                                continue;
                                
                            foreach ( $font_urls    as  $type   =>  $url )
                                {
                                    $font_urls[$type]  =   $wph->functions->content_urls_replacement( $url,  $replacement_list );   
                                }
                            
                            $all_custom_fonts[$font]['font_url']    =   $font_urls;
                        }
                    
                    return $all_custom_fonts;
                       
                }
                
            static function settings_changed()
                {
                    
                    ET_Core_PageResource::remove_static_resources( 'all', 'all' );
                    
                }
                                
        }
        
        
    WPH_conflict_theme_divi::init();
    

?>