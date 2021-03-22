<?php

    if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
    
    class WPH_module_rewrite_new_upload_path extends WPH_module_component
        {
            
            function get_component_title()
                {
                    return "Uploads";
                }
                                    
            function get_module_settings()
                {
                    $this->module_settings[]                  =   array(
                                                                    'id'            =>  'new_upload_path',
                                                                    'label'         =>  __('New Uploads Path',    'wp-hide-security-enhancer'),
                                                                    'description'   =>  __('The default uploads path is set to',    'wp-hide-security-enhancer') . ' <strong>'. str_replace(get_bloginfo('wpurl'), '' ,$this->wph->default_variables['upload_url'])  .'/</strong>',
                                                                    
                                                                    'help'          =>  array(
                                                                                                        'title'                     =>  __('Help',    'wp-hide-security-enhancer') . ' - ' . __('New Uploads Path',    'wp-hide-security-enhancer'),
                                                                                                        'description'               =>  __("Use any alphanumeric symbols for this field which will be used as the new slug for the uploads folder. Using this option the default media folder can be mapped to another path. Filling with a slug like 'media' the links become like this:",    'wp-hide-security-enhancer') . "<br />  <br />
                                                                                                                                            <code>&lt;img class=&quot;alignnone size-full&quot; src=&quot;http://domain.com/media/106658.jpg&quot; alt=&quot;&quot; width=&quot;640&quot; height=&quot;390&quot; alt=&quot;&quot; /&gt;</code>",
                                                                                                        'option_documentation_url'  =>  'https://www.wp-hide.com/documentation/rewrite-uploads/'
                                                                                                        ),
                                                                    
                                                                    'value_description' =>  __('e.g. media',    'wp-hide-security-enhancer'),
                                                                    'input_type'    =>  'text',
                                                                    
                                                                    'sanitize_type' =>  array(array($this->wph->functions, 'sanitize_file_path_name')),
                                                                    'processing_order'  =>  40
                                                                    );
                                                                    
                    $this->module_settings[]                  =   array(
                                                                    'id'            =>  'block_upload_url',
                                                                    'label'         =>  __('Block default uploads URL',    'wp-hide-security-enhancer'),
                                                                    'description'   =>  __('Block default /wp-content/uploads/ media folder from being accesible through default urls.',    'wp-hide-security-enhancer'),
                                                                    
                                                                    'help'          =>  array(
                                                                                                        'title'                     =>  __('Help',    'wp-hide-security-enhancer') . ' - ' . __('Block default uploads URL',    'wp-hide-security-enhancer'),
                                                                                                        'description'               =>  __("This blocks the default wp-content/uploads/ url.<br />The functionality apply only if <b>New Plugins Path</b> option is filled in.",    'wp-hide-security-enhancer'),
                                                                                                        'option_documentation_url'  =>  'https://www.wp-hide.com/documentation/rewrite-uploads/'
                                                                                                        ),
                                                                        
                                                                    'advanced_option'   =>  array(
                                                                        
                                                                                                        'description'               =>  '<b>' . __('This is an advanced option !',    'wp-hide-security-enhancer') . '</b><br />' . __('This can break the layout if server not supporting the feature. Ensure `New Uploads Path` option works fine before activate this. Once active test it thoroughly.<br />If not working, set to <b>No</b> to revert.',    'wp-hide-security-enhancer')
                                                                                                
                                                                                                ),
                                                                    
                                                                    'input_type'    =>  'radio',
                                                                    'options'       =>  array(
                                                                                                'no'        =>  __('No',     'wp-hide-security-enhancer'),
                                                                                                'yes'       =>  __('Yes',    'wp-hide-security-enhancer'),
                                                                                                ),
                                                                    'default_value' =>  'no',
                                                                    
                                                                    'sanitize_type' =>  array('sanitize_title', 'strtolower'),
                                                                    'processing_order'  =>  45
                                                                    
                                                                    );
                                                                    
                    return $this->module_settings;   
                }
                
                
                
            function _init_new_upload_path($saved_field_data)
                {
                    if(empty($saved_field_data))
                        return FALSE;
          
                    //add default plugin path replacement
                    $new_upload_path        =   $this->wph->functions->untrailingslashit_all(    $this->wph->functions->get_module_item_setting('new_upload_path')  );
                    $new_url                =   trailingslashit(    home_url()  )   . $new_upload_path;
                    $this->wph->functions->add_replacement( $this->wph->default_variables['upload_url'], $new_url);
                    
                }
                
            function _callback_saved_new_upload_path($saved_field_data)
                {
                    $processing_response    =   array();
                    
                    //check if the field is noe empty
                    if(empty($saved_field_data))
                        return  $processing_response; 
                                                            
                    $uploads_path =   $this->wph->functions->get_url_path(   $this->wph->default_variables['upload_url']   );
                    
                    $rewrite_base   =   trailingslashit( $saved_field_data );
                    $rewrite_to     =   $this->wph->functions->get_rewrite_to_base( $uploads_path );                    
                               
                    if($this->wph->server_htaccess_config   === TRUE)
                        $processing_response['rewrite'] = "\nRewriteRule ^"    .   $rewrite_base   .   '(.+) '. $rewrite_to .'$1 [L,QSA]';
                        
                    if($this->wph->server_web_config   === TRUE)
                        $processing_response['rewrite'] = '
                            <rule name="wph-new_upload_path" stopProcessing="true">
                                <match url="^'.  $rewrite_base   .'(.*)"  />
                                <action type="Rewrite" url="'.  $rewrite_to .'{R:1}"  appendQueryString="true" />
                            </rule>
                                                            ';
                                
                    return  $processing_response;   
                }
            
                            
            function _callback_saved_block_upload_url($saved_field_data)
                {
                    $processing_response    =   array();
                    
                    if(empty($saved_field_data) ||  $saved_field_data   ==  'no')
                        return FALSE;
                    
                    //prevent from blocking if the wp-include is not modified
                    $new_upload_path     =   $this->wph->functions->get_module_item_setting('new_upload_path');
                    if (empty(  $new_upload_path ))
                        return FALSE;
                    
                    $default_upload_url    =   untrailingslashit   (  $this->wph->default_variables['upload_url']  );
                    $default_upload_url    =   str_replace(    site_url(), "", $default_upload_url);
                    $default_upload_url    =   ltrim(rtrim($default_upload_url, "/"),  "/");
                                
                    $rewrite_base   =   $this->wph->functions->get_rewrite_base( $default_upload_url, FALSE );
                    $rewrite_to     =   $this->wph->functions->get_rewrite_to_base( 'index.php', TRUE, FALSE, 'site_path' );
                    
                    $text   =   '';
                    
                    if($this->wph->server_htaccess_config   === TRUE)
                        {                                        
                            $text   =   "RewriteCond %{ENV:REDIRECT_STATUS} ^$\n";
                            $text   .=   "RewriteRule ^".   $rewrite_base   ."(.+) ".  $rewrite_to ."?wph-throw-404 [L]";
                        }
                        
                    if($this->wph->server_web_config   === TRUE)
                            $text   = '
                                        <rule name="wph-block_upload_url" stopProcessing="true">
                                            <match url="^'.  $rewrite_base   .'(.*)"  />
                                            <action type="Rewrite" url="'.  $rewrite_to .'?wph-throw-404" />  
                                        </rule>
                                                            ';
                               
                    $processing_response['rewrite'] = $text;            
                                
                    return  $processing_response;     
                    
                    
                }


        }
?>