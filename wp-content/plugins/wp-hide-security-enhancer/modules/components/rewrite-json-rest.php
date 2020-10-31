<?php

    if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
    
    class WPH_module_rewrite_json_rest extends WPH_module_component
        {
            
            function get_component_title()
                {
                    return "JSON REST";
                }
                                                
            function get_module_settings()
                {
                                                                    
                    $this->module_settings[]                  =   array(
                                                                    'id'            =>  'disable_json_rest_v1',
                                                                    'label'         =>  __('Disable JSON REST V1 service',    'wp-hide-security-enhancer'),
                                                                    'description'   =>  __('An API service for WordPress which is active by default.',    'wp-hide-security-enhancer'),
                                                                    
                                                                    'help'          =>  array(
                                                                                                        'title'                     =>  __('Help',    'wp-hide-security-enhancer') . ' - ' . __('Disable JSON REST V1 service',    'wp-hide-security-enhancer'),
                                                                                                        'description'               =>  __("The WordPress REST API is an easy-to-use set of HTTP endpoints which allows access a site data in simple JSON format. That including users, posts, taxonomies and more. Retrieving or updating is as simple as sending a HTTP request.",    'wp-hide-security-enhancer') .
                                                                                                                                            "<br />" . __("A REST API can be consumed everywhere. On mobile applications, on front-end (web apps) or any other devices that have access on the net, practically everything can connect from anywhere to your site and interact though JSON REST API service.",    'wp-hide-security-enhancer') .
                                                                                                                                            "<br /><br />" . __("V1 was the first development version of API, which currently is deprecated. To disable the usage of it, simply chose Yes.",    'wp-hide-security-enhancer'),
                                                                                                        'option_documentation_url'  =>  'https://www.wp-hide.com/documentation/rewrite-json-rest/'
                                                                                                        ),
                                                                    
                                                                    'advanced_option'   =>  array(
                                                                        
                                                                                                        'description'               =>  '<b>' . __('This is an advanced option !',    'wp-hide-security-enhancer') . '</b><br />' . __('This can break specific functionality. Some plugins might use this API. Once active test it thoroughly.<br />If not working, set to <b>No</b> to revert.',    'wp-hide-security-enhancer')
                                                                                                
                                                                                                ),
                                                                    
                                                                    'input_type'    =>  'radio',
                                                                    'options'       =>  array(
                                                                                                'no'        =>  __('No',     'wp-hide-security-enhancer'),
                                                                                                'yes'       =>  __('Yes',    'wp-hide-security-enhancer'),
                                                                                                ),
                                                                    'default_value' =>  'no',
                                                                    
                                                                    'sanitize_type' =>  array('sanitize_title', 'strtolower'),
                                                                    'processing_order'  =>  58
                                                                    
                                                                    );
                                                                    
                    
                    $this->module_settings[]                  =   array(
                                                                    'id'            =>  'disable_json_rest_v2',
                                                                    'label'         =>  __('Disable JSON REST V2 service',    'wp-hide-security-enhancer'),
                                                                    'description'   =>  __('An API service for WordPress which is active by default.',    'wp-hide-security-enhancer'),
                                                                    
                                                                    'help'          =>  array(
                                                                                                        'title'                     =>  __('Help',    'wp-hide-security-enhancer') . ' - ' . __('Disable JSON REST V2 service',    'wp-hide-security-enhancer'),
                                                                                                        'description'               =>  __("The WordPress REST API is an easy-to-use set of HTTP endpoints which allows access a site data in simple JSON format. That including users, posts, taxonomies and more. Retrieving or updating is as simple as sending a HTTP request.",    'wp-hide-security-enhancer') .
                                                                                                                                            "<br />" . __("A REST API can be consumed everywhere. On mobile applications, on front-end (web apps) or any other devices that have access on the net, practically everything can connect from anywhere to your site and interact though JSON REST API service.",    'wp-hide-security-enhancer') .
                                                                                                                                            "<br /><br />" . __("V2 is the current development version of API, which is included into WordPress as default. To disable the usage of it, simply chose Yes." ,    'wp-hide-security-enhancer') .
                                                                                                                                            "<br ><span class='important'>" . __("This might be required by specific plugins, including new WordPress editor Gutenberg.",    'wp-hide-security-enhancer') . "</span>",
                                                                                                        'option_documentation_url'  =>  'https://www.wp-hide.com/documentation/rewrite-json-rest/'
                                                                                                        ),
                                                                    
                                                                    'advanced_option'   =>  array(
                                                                        
                                                                                                        'description'               =>  '<b>' . __('This is an advanced option !',    'wp-hide-security-enhancer') . '</b><br />' . __('This can break specific functionality. Some plugins like Gutenberg editor use this API. Once active test it thoroughly.<br />If not working, set to <b>No</b> to revert.',    'wp-hide-security-enhancer')
                                                                                                
                                                                                                ),
                                                                    
                                                                    'input_type'    =>  'radio',
                                                                    'options'       =>  array(
                                                                                                'no'        =>  __('No',     'wp-hide-security-enhancer'),
                                                                                                'yes'       =>  __('Yes',    'wp-hide-security-enhancer'),
                                                                                                ),
                                                                    'default_value' =>  'no',
                                                                    
                                                                    'sanitize_type' =>  array('sanitize_title', 'strtolower'),
                                                                    'processing_order'  =>  58
                                                                    
                                                                    );
                                                                    
                    $this->module_settings[]                  =   array(
                                                                                'type'            =>  'split'
                                                                                
                                                                                );
                    
                    $this->module_settings[]                  =   array(
                                                                    'id'            =>  'block_json_rest',
                                                                    'label'         =>  __('Block any JSON REST calls',    'wp-hide-security-enhancer'),
                                                                    'description'   =>  __('Any call for JSON REST API service will be blocked.',    'wp-hide-security-enhancer'),

                                                                    'help'          =>  array(
                                                                                                        'title'                     =>  __('Help',    'wp-hide-security-enhancer') . ' - ' . __('Block any JSON REST calls',    'wp-hide-security-enhancer'),
                                                                                                        'description'               =>  __("This blocks the JSON REST API service.",    'wp-hide-security-enhancer') .
                                                                                                                                            "<br ><span class='important'>" . __("This might be required by specific plugins, including new WordPress editor Gutenberg.",    'wp-hide-security-enhancer') . "</span>",
                                                                                                        'option_documentation_url'  =>  'https://www.wp-hide.com/documentation/rewrite-json-rest/'
                                                                                                        ),
                                                                    
                                                                    'advanced_option'   =>  array(
                                                                        
                                                                                                        'description'               =>  '<b>' . __('This is an advanced option !',    'wp-hide-security-enhancer') . '</b><br />' . __('This can break specific functionality. Some plugins like Gutenberg editor use this API. Once active test it thoroughly.<br />If not working, set to <b>No</b> to revert.',    'wp-hide-security-enhancer')
                                                                                                
                                                                                                ),
                                                                    
                                                                    'input_type'    =>  'radio',
                                                                    'options'       =>  array(
                                                                                                'no'        =>  __('No',     'wp-hide-security-enhancer'),
                                                                                                'yes'       =>  __('Yes',    'wp-hide-security-enhancer'),
                                                                                                ),
                                                                    'default_value' =>  'no',
                                                                    
                                                                    'sanitize_type' =>  array('sanitize_title', 'strtolower'),
                                                                    'processing_order'  =>  58
                                                                    
                                                                    );
                    
                                                                                
                    $this->module_settings[]                  =   array(
                                                                                'type'            =>  'split'
                                                                                
                                                                                );
                                                                    
                    
                    $this->module_settings[]                  =   array(
                                                                    'id'            =>  'disable_json_rest_wphead_link',
                                                                    'label'         =>  __('Disable output the REST API link tag into page header',    'wp-hide-security-enhancer'),
                                                                    'description'   =>  __('By default a REST API link tag is being append to HTML.',    'wp-hide-security-enhancer'),
                                                                    
                                                                    'help'          =>  array(
                                                                                                        'title'                     =>  __('Help',    'wp-hide-security-enhancer') . ' - ' . __('disable_json_rest_wphead_link',    'wp-hide-security-enhancer'),
                                                                                                        'description'               =>  __("As default the API url is being append into the front html head tag. Using this option, it will be replaced.",    'wp-hide-security-enhancer'),
                                                                                                        'option_documentation_url'  =>  'https://www.wp-hide.com/documentation/rewrite-json-rest/'
                                                                                                        ),
                                                        
                                                                    'input_type'    =>  'radio',
                                                                    'options'       =>  array(
                                                                                                'no'        =>  __('No',     'wp-hide-security-enhancer'),
                                                                                                'yes'       =>  __('Yes',    'wp-hide-security-enhancer'),
                                                                                                ),
                                                                    'default_value' =>  'no',
                                                                    
                                                                    'sanitize_type' =>  array('sanitize_title', 'strtolower'),
                                                                    'processing_order'  =>  58
                                                                    
                                                                    );
                    
                                                                    
                    $this->module_settings[]                  =   array(
                                                                    'id'            =>  'disable_json_rest_xmlrpc_rsd',
                                                                    'label'         =>  __('Disable JSON REST WP RSD endpoint from XML-RPC responses',    'wp-hide-security-enhancer'),
                                                                    'description'   =>  __('By default a WP RSD endpoint is being append to the XML respose.',    'wp-hide-security-enhancer'),
                                                                    
                                                                    'help'          =>  array(
                                                                                                        'title'                     =>  __('Help',    'wp-hide-security-enhancer') . ' - ' . __('disable_json_rest_xmlrpc_rsd',    'wp-hide-security-enhancer'),
                                                                                                        'description'               =>  __("Disable any RSD endpoint from a XML-RPC response.",    'wp-hide-security-enhancer'),
                                                                                                        'option_documentation_url'  =>  'https://www.wp-hide.com/documentation/rewrite-json-rest/'
                                                                                                        ),
                                                                    
                                                                    'input_type'    =>  'radio',
                                                                    'options'       =>  array(
                                                                                                'no'        =>  __('No',     'wp-hide-security-enhancer'),
                                                                                                'yes'       =>  __('Yes',    'wp-hide-security-enhancer'),
                                                                                                ),
                                                                    'default_value' =>  'no',
                                                                    
                                                                    'sanitize_type' =>  array('sanitize_title', 'strtolower'),
                                                                    'processing_order'  =>  58
                                                                    
                                                                    );
                                                                    
                    $this->module_settings[]                  =   array(
                                                                    'id'            =>  'disable_json_rest_template_redirect',
                                                                    'label'         =>  __('Disable Sends a Link header for the REST API',    'wp-hide-security-enhancer'),
                                                                    'description'   =>  __('On template_redirect, disable Sends a Link header for the REST API.',    'wp-hide-security-enhancer'),
                                                                    
                                                                    'help'          =>  array(
                                                                                                        'title'                     =>  __('Help',    'wp-hide-security-enhancer') . ' - ' . __('disable_json_rest_template_redirect',    'wp-hide-security-enhancer'),
                                                                                                        'description'               =>  __("Disable Sends a Link header for the REST API, on template_redirect",    'wp-hide-security-enhancer'),
                                                                                                        'option_documentation_url'  =>  'https://www.wp-hide.com/documentation/rewrite-json-rest/'
                                                                                                        ),
                                                                    
                                                                    'input_type'    =>  'radio',
                                                                    'options'       =>  array(
                                                                                                'no'        =>  __('No',     'wp-hide-security-enhancer'),
                                                                                                'yes'       =>  __('Yes',    'wp-hide-security-enhancer'),
                                                                                                ),
                                                                    'default_value' =>  'no',
                                                                    
                                                                    'sanitize_type' =>  array('sanitize_title', 'strtolower'),
                                                                    'processing_order'  =>  58
                                                                    
                                                                    );
                    
                                                                    
                    return $this->module_settings;   
                }
                
                
                
            function _init_disable_json_rest_v1($saved_field_data)
                {
                    if(empty($saved_field_data) ||  $saved_field_data   ==  'no')
                        return FALSE;
                    
                    add_filter('json_enabled', '__return_false');
                    add_filter('json_jsonp_enabled', '__return_false');
                    
                }
                
                
            function _init_disable_json_rest_v2($saved_field_data)
                {
                    if(empty($saved_field_data) ||  $saved_field_data   ==  'no')
                        return FALSE;

                    add_filter('rest_authentication_errors', array ( $this, 'rest_authentication_errors' ) );
                    add_filter('rest_jsonp_enabled', '__return_false');
                    
                }
                
            
            function rest_authentication_errors( $result )
                {
                    
                    return new WP_Error( 'rest_disabled', 'The service is currently disabled.', array( 'status' => 400 ) );
 
                }
                
                
            function _callback_saved_block_json_rest($saved_field_data)
                {
                    $processing_response    =   array();
                    
                    if(empty($saved_field_data) ||  $saved_field_data   ==  'no')
                        return FALSE;
                    
                    $rewrite_to     =   $this->wph->functions->get_rewrite_to_base( 'index.php', TRUE, FALSE, 'site_path' );
                    
                    if($this->wph->server_htaccess_config   === TRUE)
                        {                                        
                            $text   =   "\nRewriteRule ^wp-json(.+) ".  $rewrite_to ."?wph-throw-404 [L]";
                        }
                    
                    if($this->wph->server_web_config   === TRUE)
                        $text   = '
                                    <rule name="wph-block_json_rest" stopProcessing="true">
                                        <match url="^wp-json"  />
                                        <action type="Rewrite" url="'.  $rewrite_to .'" />  
                                    </rule>
                                                        ';
                    
                               
                    $processing_response['rewrite'] = $text;            
                                
                    return  $processing_response; 

                    
                    
                }
            
            
            function _init_disable_json_rest_wphead_link($saved_field_data)
                {
                    if(empty($saved_field_data) ||  $saved_field_data   ==  'no')
                        return FALSE;

                    remove_action( 'wp_head', 'rest_output_link_wp_head', 10 );
                    
                }
            
                
            function _init_disable_json_rest_xmlrpc_rsd($saved_field_data)
                {
                    if(empty($saved_field_data) ||  $saved_field_data   ==  'no')
                        return FALSE;

                    remove_action( 'xmlrpc_rsd_apis', 'rest_output_rsd' );
                    
                }
           
           
            function _init_disable_json_rest_template_redirect($saved_field_data)
                {
                    if(empty($saved_field_data) ||  $saved_field_data   ==  'no')
                        return FALSE;

                    remove_action( 'template_redirect', 'rest_output_link_header', 11 );
                    
                }

        }
?>