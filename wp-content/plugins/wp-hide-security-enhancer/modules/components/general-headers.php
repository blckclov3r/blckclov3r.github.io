<?php

    if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
    
    class WPH_module_general_headers extends WPH_module_component
        {
            function get_component_title()
                {
                    return "Headers";
                }
                                    
            function get_module_settings()
                {
                    
                    $this->module_settings[]                  =   array(
                                                                    'id'            =>  'remove_header_link',
                                                                    'label'         =>  __('Remove Link Header',    'wp-hide-security-enhancer'),
                                                                    'description'   =>  __('Remove Link Header being set as default by WordPress which outputs the site JSON url.', 'wp-hide-security-enhancer'),
                                                                    
                                                                    'help'          =>  array(
                                                                                                'title'                     =>  __('Help',    'wp-hide-security-enhancer') . ' - ' . __('Remove Version',    'wp-hide-security-enhancer'),
                                                                                                'description'               =>  __("HTTP header fields are components of the header section of a request and response messages in the Hypertext Transfer Protocol (HTTP). They define the operating parameters of an HTTP transaction.",    'wp-hide-security-enhancer') .
                                                                                                                                    "<br /><br />" . __("Sample header:",    'wp-hide-security-enhancer') .
                                                                                                                                    "<br /><code>Link: &lt;http://-domain-name-/wp-json/&gt;; rel=&quot;https://api.w.org/&quot;</code>",
                                                                                                'option_documentation_url'  =>  'https://www.wp-hide.com/documentation/request-headers/'
                                                                                                ),
                                                                    
                                                                    'input_type'    =>  'radio',
                                                                    'options'       =>  array(
                                                                                                'no'        =>  __('No',     'wp-hide-security-enhancer'),
                                                                                                'yes'       =>  __('Yes',    'wp-hide-security-enhancer'),
                                                                                                ),
                                                                    'default_value' =>  'no',
                                                                    
                                                                    'sanitize_type' =>  array('sanitize_title', 'strtolower'),
                                                                    'processing_order'  =>  70
                                                                    );
          
                    
                    $this->module_settings[]                  =   array(
                                                                    'id'            =>  'remove_x_powered_by',
                                                                    'label'         =>  __('Remove X-Powered-By Header',    'wp-hide-security-enhancer'),
                                                                    'description'   =>  __('Remove X-Powered-By Header if being set.', 'wp-hide-security-enhancer'),
                                                                    
                                                                    'help'          =>  array(
                                                                                                'title'                     =>  __('Help',    'wp-hide-security-enhancer') . ' - ' . __('Remove X-Powered-By Header',    'wp-hide-security-enhancer'),
                                                                                                'description'               =>  __("Sample header:",    'wp-hide-security-enhancer') .
                                                                                                                                    "<br /><code>x-powered-by: 'W3 Total Cache/0.9.5'</code>",
                                                                                                'option_documentation_url'  =>  'https://www.wp-hide.com/documentation/request-headers/'
                                                                                                ),
                                                                    
                                                                    'input_type'    =>  'radio',
                                                                    'options'       =>  array(
                                                                                                'no'        =>  __('No',     'wp-hide-security-enhancer'),
                                                                                                'yes'       =>  __('Yes',    'wp-hide-security-enhancer'),
                                                                                                ),
                                                                    'default_value' =>  'no',
                                                                    
                                                                    'sanitize_type' =>  array('sanitize_title', 'strtolower'),
                                                                    'processing_order'  =>  70
                                                                    );
                                                                    
                    $this->module_settings[]                  =   array(
                                                                    'id'            =>  'remove_x_pingback',
                                                                    'label'         =>  __('Remove X-Pingback Header',    'wp-hide-security-enhancer'),
                                                                    'description'   =>  __('Remove X-Pingback Header if being set.', 'wp-hide-security-enhancer'),
                                                                    
                                                                    'help'          =>  array(
                                                                                                'title'                     =>  __('Help',    'wp-hide-security-enhancer') . ' - ' . __('Remove X-Pingback Header',    'wp-hide-security-enhancer'),
                                                                                                'description'               =>  __("Pingback is one of four types of linkback methods for Web authors to request notification when somebody links to one of their documents. This enables authors to keep track of who is linking to, or referring to their articles. Pingback-enabled resources must either use an X-Pingback header or contain a element to the XML-RPC script.",    'wp-hide-security-enhancer'),
                                                                                                'option_documentation_url'  =>  'https://www.wp-hide.com/documentation/request-headers/'
                                                                                                ),
                                                                    
                                                                    'input_type'    =>  'radio',
                                                                    'options'       =>  array(
                                                                                                'no'        =>  __('No',     'wp-hide-security-enhancer'),
                                                                                                'yes'       =>  __('Yes',    'wp-hide-security-enhancer'),
                                                                                                ),
                                                                    'default_value' =>  'no',
                                                                    
                                                                    'sanitize_type' =>  array('sanitize_title', 'strtolower'),
                                                                    'processing_order'  =>  70
                                                                    );
                    
                                                                    
                    return $this->module_settings;   
                }
                
            
            function _init_remove_header_link( $saved_field_data )
                {
                    if(empty($saved_field_data) ||  $saved_field_data   ==  'no')
                        return FALSE;
                    
                    remove_action( 'template_redirect', 'rest_output_link_header', 11, 0 );    
                    
                }
                
                
            function _init_remove_x_powered_by($saved_field_data)
                {
                    if(empty($saved_field_data) ||  $saved_field_data   ==  'no')
                        return FALSE;
                        
                    
                }
                
            function _callback_saved_remove_x_powered_by($saved_field_data)
                {
                    $processing_response    =   array();
                    
                    if(empty($saved_field_data) ||  $saved_field_data   ==  'no')
                        return FALSE; 
                    
                    if($this->wph->server_htaccess_config   === TRUE)                               
                        $processing_response['rewrite'] = '
                            <FilesMatch "">
                                <IfModule mod_headers.c>
                                    Header unset X-Powered-By
                                </IfModule>
                            </FilesMatch>';
                            
                    if($this->wph->server_web_config   === TRUE)
                        {
                            //this goes after </rules> section
                            //to be implemented at a later version 
                            /*
                            $processing_response['rewrite'] = '
                                    <outboundRules>
                                      <rule name="wph-bcdscsdh">  
                                            <match serverVariable="RESPONSE_X-POWERED-BY" pattern=".*" ignoreCase="true" />
                                            <action type="Rewrite" value="" />  
                                        </rule>
                                   </outboundRules>
                                    ';
                            */
                            
                            $processing_response['rewrite'] =   '';
                        }
                                
                    return  $processing_response;   
                }
                
                
            function _init_remove_x_pingback($saved_field_data)
                {
                    if(empty($saved_field_data) ||  $saved_field_data   ==  'no')
                        return FALSE;
                        
                    
                }
                
            function _callback_saved_remove_x_pingback($saved_field_data)
                {
                    $processing_response    =   array();
                    
                    if(empty($saved_field_data) ||  $saved_field_data   ==  'no')
                        return FALSE; 
                    
                    if($this->wph->server_htaccess_config   === TRUE)                               
                        $processing_response['rewrite'] = '
                            <FilesMatch "">
                                <IfModule mod_headers.c>
                                    Header unset X-Pingback
                                </IfModule>
                            </FilesMatch>';
                            
                    if($this->wph->server_web_config   === TRUE)
                        {
                            
                            $processing_response['rewrite'] =   '';
                        }
                                
                    return  $processing_response;   
                }


        }
?>