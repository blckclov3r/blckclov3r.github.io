<?php

    if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
    
    class WPH_module_rewrite_default extends WPH_module_component
        {
            
            function get_component_id()
                {
                    return '_rewrite_default_';
                    
                }
                                    
            function get_module_settings()
                {
                    $this->module_settings[]                  =   array(
                                                                    'id'            =>  'rewrite_default',
                                                                    'visible'       =>  FALSE,
                                                                    'processing_order'  =>  1
                                                                    );
                                                                    
                    return $this->module_settings;   
                }
                

                
            function _callback_saved_rewrite_default($saved_field_data)
                {
                    $processing_response    =   array();

                                
                    return  $processing_response;   
                }
                
        

        }
?>