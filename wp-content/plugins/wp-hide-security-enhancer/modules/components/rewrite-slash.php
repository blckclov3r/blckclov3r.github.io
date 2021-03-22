<?php

    if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
    
    class WPH_module_rewrite_slash extends WPH_module_component
        {
            function get_component_title()
                {
                    return "URL Slash";
                }
                                        
            function get_module_settings()
                {
                    $this->module_settings[]                  =   array(
                                                                    'id'            =>  'add_slash',
                                                                    'label'         =>  __('URL\'s add Slash',    'wp-hide-security-enhancer'),
                                                                    'description'   =>  __('Add an end slash to all links which does not include one.',    'wp-hide-security-enhancer'). '<br /> ',

                                                                    'help'          =>  array(
                                                                                                        'title'                     =>  __('Help',    'wp-hide-security-enhancer') . ' - ' . __('New XML-RPC Path',    'wp-hide-security-enhancer'),
                                                                                                        'description'               =>  __("As default the WordPress url's format include an ending slash. ",    'wp-hide-security-enhancer') .
                                                                                                                                            "<br /><br />" . __("There are situations when this slash is not being append. Turning on this option, all links will get a slash if not included as default. Disguise the existence of files and folders, since they will not be slashed as deafault, all receive an ending slashed.",    'wp-hide-security-enhancer') .
                                                                                                                                            "<br />" . __("For example the following link:" ,    'wp-hide-security-enhancer') .
                                                                                                                                            "<br /><code>https://-domain-name-/map/data</code>
                                                                                                                                            <br />" . __("will be redirected to:",    'wp-hide-security-enhancer') .
                                                                                                                                            "<br /><code>https://-domain-name-/map/data/</code>
                                                                                                                                            <br /><br />" . __('On certain servers this can produce a small lag measured in milliseconds, for each url.',    'wp-hide-security-enhancer') .
                                                                                                                                            "<br /><br />" . __('If produce endless redirects, turn this option off.',    'wp-hide-security-enhancer'),
                                                                                                        'option_documentation_url'  =>  'https://www.wp-hide.com/documentation/rewrite-url-slash/',
                                                                                                        'input_value_extension'     =>  'php'
                                                                                                        ),
                                                                                                
                                                                    'input_type'    =>  'radio',
                                                                    'options'       =>  array(
                                                                                                'no'        =>  __('No',     'wp-hide-security-enhancer'),
                                                                                                'yes'       =>  __('Yes',    'wp-hide-security-enhancer'),
                                                                                                ),
                                                                    
                                                                    'default_value' =>  'no',
                                                                    
                                                                    'sanitize_type' =>  array('sanitize_title', 'strtolower'),
                                                                    'processing_order'  =>  3
                                                                    );
                                                                    
                    return $this->module_settings;   
                }
                
            
            function _init_add_slash($saved_field_data)
                {
                    if(empty($saved_field_data) ||  $saved_field_data   ==  'no')
                        return;
                        
                    //nothing to do at the moment
                }
                
            function _callback_saved_add_slash($saved_field_data)
                {
                    $processing_response    =   array();
                    
                    if(empty($saved_field_data) ||  $saved_field_data   ==  'no')
                        return FALSE;
                    
                    if($this->wph->server_htaccess_config   === TRUE)                             
                        //\nRewriteCond %{ENV:REDIRECT_STATUS} !^$"
                        $processing_response['rewrite'] =  "\nRewriteCond %{REQUEST_URI} /+[^\.]+$"
                                                            . "\nRewriteCond %{REQUEST_METHOD} !POST"
                                                            . "\nRewriteRule ^(.+[^/])$ %{REQUEST_URI}/ [R=301,L]";
                                                            
                    if($this->wph->server_web_config   === TRUE)
                        $processing_response['rewrite'] = '
                                
                                <rule name="wph-add_slash" stopProcessing="true">  
                                    <match url="^(.+[^/])$" />  
                                    <conditions>  
                                        <add input="{REQUEST_URI}" matchType="Pattern" pattern="/+[^\.]+$"  />  
                                    </conditions>  
                                    <action type="Redirect" redirectType="Permanent" url="{R:1}/" />  
                                </rule>
                            
                                                            ';
                                    
                    return  $processing_response;   
                }
                
           
         

        }
?>