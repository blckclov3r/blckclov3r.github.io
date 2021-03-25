<?php
    
    /**
    * Theme Compatibility   :   Woodmart
    * Introduced at version :   4.2.2 
    */
    
    if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
    
    
    class WPH_conflict_theme_woodmart
        {
                        
            static function init()
                {
                    add_filter( 'woodmart_get_all_theme_settings_css', array( 'WPH_conflict_theme_woodmart', 'woodmart_get_all_theme_settings_css') );
                }                        
            
              
            static public function woodmart_get_all_theme_settings_css( $css )
                {   
                    
                    global $wph;
                    
                    $css    =   $wph->functions->content_urls_replacement( $css,  $wph->functions->get_replacement_list() );
                       
                    return $css; 
                               
                }
         
                                
        }
        
        
    WPH_conflict_theme_woodmart::init();
    

?>