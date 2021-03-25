<?php

    if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
    
    class WPH_module_component
        {
            var $wph;
            var $module_settings;
            
            var $title;
                     
            function __construct()
                {
                    $this->id       =   $this->get_component_id();
                    $this->title    =   $this->get_component_title();
                    
                    global $wph;
                    
                    $this->wph  =   $wph;
                    
                    $this->module_settings  =   $this->get_module_settings();
                }
            
            function get_component_id()
                {
                    if($this->get_component_title() === FALSE)   
                        return FALSE;
                        
                    return sanitize_title($this->get_component_title());
                    
                }
                
            function get_component_title()
                {
                    
                    return FALSE;
                }
                
            
            function get_module_settings()
                {
                    
                    return array();   
                }
                
        }
    
 
?>