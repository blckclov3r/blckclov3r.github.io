<?php

    if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
    
    class WPH_module_cdn_setup extends WPH_module_component
        {
            function get_component_title()
                {
                    return "CDN";
                }
                                    
            function get_module_settings()
                {
                    $this->module_settings[]                  =   array(
                                                                    'id'            =>  'cdn_url',
                                                                    'label'         =>  __('CDN Url',    'wp-hide-security-enhancer'),
                                                                    'description'   =>  __('Some CDN providers (like stackpath.com ) replace site assets with custom url, enter here such url. Otherwise this option should stay empty.', 'wp-hide-security-enhancer'),
                                                                    
                                                                    'help'          =>  array(
                                                                                                        'title'                     =>  __('Help',    'wp-hide-security-enhancer') . ' - ' . __('CDN Url',    'wp-hide-security-enhancer'),
                                                                                                        'description'               =>  __("A Content Delivery Network - CDN - is a network of servers located around the globe in fundamental spots with fast access. It takes a while for a web page to load especially if the server is located far away from the user. So they are designed to host and deliver copies of your site's static and dynamic content such as images, CSS, JavaScript, audio and video streams.",    'wp-hide-security-enhancer') .
                                                                                                                                            "<br /><br />" . __('Sample CDN url:',    'wp-hide-security-enhancer') .
                                                                                                                                            "<br /><code>cdnjs.cloudflare.com</code><br /><br />" .
                                                                                                                                            __('Enter a CDN Url to allow the plugin to process assets provided through CDN service.',    'wp-hide-security-enhancer'),
                                                                                                        'option_documentation_url'  =>  'https://www.wp-hide.com/documentation/admin-change-wp-admin/'
                                                                                                        ),
                                                                    
                                                                    'input_type'    =>  'text',
                                                         
                                                                    
                                                                    'sanitize_type' =>  array()
                                                                    
                                                                    );
                                                                    
                    return $this->module_settings;   
                }
                
                
                
            function _init_scripts_remove_version($saved_field_data)
                {
   
                    
                }


        }
?>