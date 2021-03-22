<?php

    if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
    
    class WPH_module_rewrite_wp_content_path extends WPH_module_component
        {
            function get_component_title()
                {
                    return "WP Content";
                }
                                        
            function get_module_settings()
                {
                    $this->module_settings[]                  =   array(
                                                                    'id'            =>  'new_content_path',
                                                                    'label'         =>  __('New Content Path',    'wp-hide-security-enhancer'),
                                                                    'description'   =>  __('Change default /wp-content/',    'wp-hide-security-enhancer') . '<br />' .__('Your default wp-content path is set to',    'wp-hide-security-enhancer') . ' <strong>'.   $this->wph->default_variables['content_directory'] .'</strong>',
                                                                    
                                                                    'help'          =>  array(
                                                                                                        'title'                     =>  __('Help',    'wp-hide-security-enhancer') . ' - ' . __('New Content Path',    'wp-hide-security-enhancer'),
                                                                                                        'description'               =>  __("As default a WordPress installation contain a wp-content folder which store files and resources used by themes and plugin. The wp-content is a common fingerprint, which makes easily to anyone to identify the site as being created on WordPress.",    'wp-hide-security-enhancer') . " <br />  <br />
                                                                                                                                            <code>&lt;script type='text/javascript' src='https://-domain-name-/wp-content/cache/static/asset.js'&gt;&lt;/script&gt;</code>
                                                                                                                                            <br /><br /> " . __("After filling in this option e.g. data the links become:",    'wp-hide-security-enhancer') . " <br />  <br /> 
                                                                                                                                            <code>&lt;script type='text/javascript' src='https://-domain-name-/data/cache/static/asset.js'&gt;&lt;/script&gt;</code>",
                                                                                                        'option_documentation_url'  =>  'https://www.wp-hide.com/documentation/rewrite-wp-content/'
                                                                                                        ),
                                                                    
                                                                    'value_description' =>  __('e.g. my_content',    'wp-hide-security-enhancer'),
                                                                    'input_type'    =>  'text',
                                                                    
                                                                    'sanitize_type' =>  array(array($this->wph->functions, 'sanitize_file_path_name')),
                                                                    'processing_order'  =>  90
                                                                    );
                    
                    
                    $this->module_settings[]                  =   array(
                                                                    'id'            =>  'block_wp_content_path',
                                                                    'label'         =>  __('Block wp-content URL',    'wp-hide-security-enhancer'),
                                                                    'description'   =>  __('Block default /wp-content/ path. Your default wp-content path is set to',    'wp-hide-security-enhancer') . ' <strong>'.   $this->wph->default_variables['content_directory'] .'</strong>',
                                                                    
                                                                    'help'          =>  array(
                                                                                                        'title'                     =>  __('Help',    'wp-hide-security-enhancer') . ' - ' . __('New content Path',    'wp-hide-security-enhancer'),
                                                                                                        'description'               =>  __("This blocks the default wp-content urls only for non loged-in users.<br />The functionality apply only if <b>New Content Path</b> option is filled in.",    'wp-hide-security-enhancer'),
                                                                                                        'option_documentation_url'  =>  'https://www.wp-hide.com/documentation/rewrite-wp-content/'
                                                                                                        ),
                                                                    
                                                                    'advanced_option'   =>  array(
                                                                        
                                                                                                        'description'               =>  '<b>' . __('This is an advanced option !',    'wp-hide-security-enhancer') . '</b><br />' . __('This can break the layout if server not supporting the feature. Ensure New Includes Path options works fine before activate this. Once active test it thoroughly.<br />If not working, set to <b>No</b> to revert.',    'wp-hide-security-enhancer')
                                                                                                
                                                                                                ),
                                                                    
                                                                    'input_type'    =>  'radio',
                                                                    'options'       =>  array(
                                                                                                'no'        =>  __('No',     'wp-hide-security-enhancer'),
                                                                                                'yes'       =>  __('Yes',    'wp-hide-security-enhancer'),
                                                                                                ),
                                                                    'default_value' =>  'no',
                                                                    
                                                                    'sanitize_type' =>  array('sanitize_title', 'strtolower'),
                                                                    'processing_order'  =>  91
                                                                    );
                                                                    
                    return $this->module_settings;   
                }
                
            
            function _init_new_content_path($saved_field_data)
                {
                    if(empty($saved_field_data))
                        return FALSE;
 
                    $content_directory  =   $this->wph->default_variables['content_directory'];
                    
                    //add default plugin path replacement
                    $new_content_path   =   trailingslashit(    home_url()  )   . untrailingslashit(  $saved_field_data    );
                    $this->wph->functions->add_replacement( untrailingslashit(    site_url()  ) . $content_directory , $new_content_path );
                    
                    return TRUE;
                }
                
            function _callback_saved_new_content_path( $saved_field_data )
                {
                    $processing_response    =   array();
                    
                    //check if the field is noe empty
                    if(empty($saved_field_data))
                        return  $processing_response; 
                    
                    
                    $content_path   =   $this->wph->functions->get_url_path( trailingslashit(   WP_CONTENT_URL   ));
                                
                    $rewrite_base   =   trailingslashit( $saved_field_data );
                    $rewrite_to     =   $this->wph->functions->get_rewrite_to_base( $content_path );
                               
                    if($this->wph->server_htaccess_config   === TRUE)
                        $processing_response['rewrite'] = "\nRewriteRule ^"    .   $rewrite_base   .   '(.+) '. $rewrite_to .'$1 [L,QSA]';
                        
                    if($this->wph->server_web_config   === TRUE)
                        $processing_response['rewrite'] = '
                            <rule name="wph-new_content_path" stopProcessing="true">
                                <match url="^'.  $rewrite_base   .'(.*)"  />
                                <action type="Rewrite" url="'.  $rewrite_to .'{R:1}"  appendQueryString="true" />
                            </rule>
                                                            ';
                                
                    return  $processing_response;   
                }
                
                
            function _init_block_wp_content_path($saved_field_data)
                {
                    
                }
                
            function _callback_saved_block_wp_content_path($saved_field_data)
                {
                    $processing_response    =   array();
                    
                    if(empty($saved_field_data) ||  $saved_field_data   ==  'no')
                        return FALSE;
                    
                    //prevent from blocking if the wp-include is not modified
                    $new_content_path       =   $this->wph->functions->get_module_item_setting('new_content_path');
                    if (empty(  $new_content_path ))
                        return FALSE;
                    
                    $rewrite_base       =   $this->wph->functions->get_rewrite_base( $this->wph->default_variables['content_directory'], FALSE, FALSE );
                    $rewrite_to         =   $this->wph->functions->get_rewrite_to_base( 'index.php', TRUE, FALSE, 'site_path' );
                    
                    $text   =   '';
                    
                    if($this->wph->server_htaccess_config   === TRUE)
                        {                          
                            if ( stripos($_SERVER['SERVER_SOFTWARE'], 'LiteSpeed') )
                                $text   .=   "RewriteCond %{HTTP_USER_AGENT} !LiteSpeed-Image\n";    
                            
                            $text   .=   "RewriteCond %{ENV:REDIRECT_STATUS} ^$\n";
                            $text   .=   "RewriteCond %{HTTP_COOKIE} !^.*wordpress_logged_in.*$ [NC]\n";
                            $text   .=   "RewriteRule ^".   $rewrite_base   ."(.+) ".  $rewrite_to ."?wph-throw-404 [L]";
                                       
                            $processing_response['rewrite'] = $text;  
                        }
                        
                    if($this->wph->server_web_config   === TRUE)
                        $processing_response['rewrite'] = '
                            <rule name="wph-block_wp_content_path" stopProcessing="true">  
                                    <match url="^'. $rewrite_base  .'(.*)" />  
                                    <conditions>  
                                        <add input="{HTTP_COOKIE}" matchType="Pattern" pattern="wordpress_logged_in_[^.]+" negate="true" />  
                                    </conditions>  
                                    <action type="Rewrite" url="'.  $rewrite_to .'?wph-throw-404" />  
                                </rule>
                                                            '; 
                                 
                                
                    return  $processing_response;     
                }
                
         

        }
?>