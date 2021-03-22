<?php

    if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
    
    class WPH_module_rewrite_new_include_path extends WPH_module_component
        {
                                    
            function get_component_title()
                {
                    return "WP Includes";
                }
            
            function get_module_settings()
                {
                    $this->module_settings[]                  =   array(
                                                                    'id'            =>  'new_include_path',
                                                                    'label'         =>  __('New Includes Path',    'wp-hide-security-enhancer'),
                                                                    'description'   =>  __('Change default /wp-includes/ path.',    'wp-hide-security-enhancer') ,
                                                                    
                                                                    'help'          =>  array(
                                                                                                        'title'                     =>  __('Help',    'wp-hide-security-enhancer') . ' - ' . __('New Includes Path',    'wp-hide-security-enhancer'),
                                                                                                        'description'               =>  __("As default a WordPress installation contain a wp-include folder which store files and resources used by WordPress core, themes and plugin. The wp-includes is a common fingerprint, which makes easily to anyone to identify the site as being created on WordPress.",    'wp-hide-security-enhancer') ." <br />  <br />
                                                                                                                                            <code>&lt;script type='text/javascript' src='https://-domain-name-/wp-include/js/jquery/jquery.js'&gt;&lt;/script&gt;</code>
                                                                                                                                            <br /><br /> " . __("After filling in this option e.g. resources the links will change to this:",    'wp-hide-security-enhancer') . " <br />  <br /> 
                                                                                                                                            <code>&lt;script type='text/javascript' src='https://-domain-name-/resources/js/jquery/jquery.js'&gt;&lt;/script&gt;</code>",
                                                                                                        'option_documentation_url'  =>  'https://www.wp-hide.com/documentation/rewrite-wp-includes/'
                                                                                                        ),
                                                                    
                                                                    'value_description' =>  __('e.g. my_includes',    'wp-hide-security-enhancer'),
                                                                    'input_type'    =>  'text',
                                                                    
                                                                    'sanitize_type' =>  array(array($this->wph->functions, 'sanitize_file_path_name')),
                                                                    'processing_order'  =>  20
                                                                    );
                    $this->module_settings[]                  =   array(
                                                                    'id'            =>  'block_wpinclude_url',
                                                                    'label'         =>  __('Block wp-includes URL',    'wp-hide-security-enhancer'),
                                                                    'description'   =>  __('Block /wp-includes/ files from being accesible through default urls. <br />Apply only if <b>New Includes Path</b> is not empty.',    'wp-hide-security-enhancer'),
                                                                    
                                                                    'help'          =>  array(
                                                                                                        'title'                     =>  __('Help',    'wp-hide-security-enhancer') . ' - ' . __('New Includes Path',    'wp-hide-security-enhancer'),
                                                                                                        'description'               =>  __("This blocks the default wp-includes urls only for non loged-in users.<br />The functionality apply only if <b>New Includes Path</b> option is filled in.",    'wp-hide-security-enhancer'),
                                                                                                        'option_documentation_url'  =>  'https://www.wp-hide.com/documentation/rewrite-wp-includes/'
                                                                                                        ),
                                                                    
                                                                    'advanced_option'   =>  array(
                                                                        
                                                                                                        'description'               =>  '<b>' . __('This is an advanced option !',    'wp-hide-security-enhancer') . '</b><br />' . __('This can break the layout if server not supporting the feature. Ensure `New Includes Path` option works fine before activate this. Once active test it thoroughly.<br />If not working, set to <b>No</b> to revert.',    'wp-hide-security-enhancer')
                                                                                                
                                                                                                ),
                                                                    
                                                                    'input_type'    =>  'radio',
                                                                    'options'       =>  array(
                                                                                                'no'        =>  __('No',     'wp-hide-security-enhancer'),
                                                                                                'yes'       =>  __('Yes',    'wp-hide-security-enhancer'),
                                                                                                ),
                                                                    'default_value' =>  'no',
                                                                    
                                                                    'sanitize_type' =>  array('sanitize_title', 'strtolower'),
                                                                    'processing_order'  =>  19
                                                                    );
                                                                    
                    return $this->module_settings;   
                }
                
                
                
            function _init_new_include_path($saved_field_data)
                {
                    if(empty($saved_field_data))
                        return FALSE;
                        
                    //add default plugin path replacement
                    $new_include_path   =   $this->wph->functions->untrailingslashit_all(    $this->wph->functions->get_module_item_setting('new_include_path')  );
                    $new_include_path   =   trailingslashit(    home_url()  )   . untrailingslashit(  $new_include_path    );
                    $this->wph->functions->add_replacement( trailingslashit(    site_url()  ) . 'wp-includes', $new_include_path );
                }
                
            function _callback_saved_new_include_path($saved_field_data)
                {
                    $processing_response    =   array();
                    
                    //check if the field is noe empty
                    if(empty($saved_field_data))
                        return  $processing_response; 
                    
                    
                    $include_path   =   $this->wph->functions->get_url_path( trailingslashit(site_url()) . WPINC    );
                    
                    $path           =   '';
                    if(!empty($this->wph->default_variables['wordpress_directory']))
                        $path           =   trailingslashit($this->wph->default_variables['wordpress_directory']);
                    $path           .=  trailingslashit(   $saved_field_data   );
                    
                    $rewrite_base   =   trailingslashit( $saved_field_data );
                    $rewrite_to     =   $this->wph->functions->get_rewrite_to_base( $include_path );
                    
                    if($this->wph->server_htaccess_config   === TRUE)           
                        $processing_response['rewrite'] = "\nRewriteRule ^"    .   $rewrite_base   .   '(.+) '. $rewrite_to .'$1 [L,QSA]';
                        
                    if($this->wph->server_web_config   === TRUE)
                        $processing_response['rewrite'] = '
                                    <rule name="wph-new_include_path" stopProcessing="true">
                                        <match url="^'.  $rewrite_base   .'(.*)"  />
                                        <action type="Rewrite" url="'.  $rewrite_to .'{R:1}"  appendQueryString="true" />
                                    </rule>
                                                                    ';
                                
                    return  $processing_response;   
                }

                
            function _callback_saved_block_wpinclude_url($saved_field_data)
                {
                    $processing_response    =   array();
                    
                    if(empty($saved_field_data) ||  $saved_field_data   ==  'no')
                        return FALSE;
                    
                    //prevent from blocking if the wp-include is not modified
                    $new_include_path       =   $this->wph->functions->get_module_item_setting('new_include_path');
                    if (empty(  $new_include_path ))
                        return FALSE;
                      
                    $rewrite_base   =   $this->wph->functions->get_rewrite_base( 'wp-includes', FALSE, FALSE );
                    $rewrite_to     =   $this->wph->functions->get_rewrite_to_base( 'index.php', TRUE, FALSE, 'site_path' );
                    
                    $text   =   '';
                    
                    if($this->wph->server_htaccess_config   === TRUE)
                        {                    
                            $text   =   "RewriteCond %{ENV:REDIRECT_STATUS} ^$\n";
                            $text   .=  "RewriteCond %{HTTP_COOKIE} !^.*wordpress_logged_in.*$ [NC]\n";
                            $text   .=  "RewriteRule ^" .$rewrite_base ."(.*) ".  $rewrite_to ."?wph-throw-404 [L]";
                        }
                    
                    if($this->wph->server_web_config   === TRUE)
                        {
                            $text = '
                            <rule name="wph-block_wpinclude_url" stopProcessing="true">  
                                    <match url="^' .$rewrite_base .'(.*)" />  
                                    <conditions>  
                                        <add input="{HTTP_COOKIE}" matchType="Pattern" pattern="wordpress_logged_in_[^.]+" negate="true" />  
                                    </conditions>  
                                    <action type="Rewrite" url="'.  $rewrite_to .'?wph-throw-404" />  
                                </rule>
                                                            ';     
                            
                        }
                               
                    $processing_response['rewrite'] = $text;            
                                
                    return  $processing_response;     
                    
                }    
                


        }
?>