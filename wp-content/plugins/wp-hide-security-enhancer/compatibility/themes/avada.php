<?php

    if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
    
    class WPH_conflict_theme_avada
        {
                        
            static function init()
                {
                    add_action('plugins_loaded',        array('WPH_conflict_theme_avada', 'run') , -1);    
                }                        
              
            static public function run()
                {   
                    
                    global $wph;
                                        
                    add_filter ('fusion_dynamic_css_final', array('WPH_conflict_theme_avada', 'url_replacement'), 999);
                    
                    //flush avada cache when settings changes
                    if ( function_exists ( 'avada_reset_all_cache' ) )
                        add_action('wph/settings_changed',  'avada_reset_all_cache');
                    if ( function_exists ( 'fusion_reset_all_caches' ) )
                        add_action('wph/settings_changed',  'fusion_reset_all_caches');
                        
                               
                }
                 
            static function url_replacement( $css )
                {
                    
                    global $wph;
                    
                    $replacement_list   =   $wph->functions->get_replacement_list();
                                            
                    //replace the urls
                    $css =   $wph->functions->content_urls_replacement( $css,  $replacement_list );    
                    
                    return $css;    
                }
                            
        }
        
        
    WPH_conflict_theme_avada::init();


?>