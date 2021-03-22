<?php
    
    /**
    * Theme Compatibility   :   BuddyBoss Theme
    * Introduced at version :   1.4.1 
    */
    
    if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
    
    
    class WPH_conflict_theme_buddyboss_theme
        {
                        
            static function init()
                {
                    add_filter( 'init', array ( 'WPH_conflict_theme_buddyboss_theme', 'setup_theme') );
                }                        
                
                
            static function setup_theme()
                {
                    //remove_filter('login_redirect', 'buddyboss_redirect_previous_page');
                }
                                
        }
        
        
    WPH_conflict_theme_buddyboss_theme::init();
    

?>