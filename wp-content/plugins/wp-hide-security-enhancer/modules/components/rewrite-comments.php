<?php

    if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
    
    class WPH_module_rewrite_comments extends WPH_module_component
        {
            
            function get_component_title()
                {
                    return "Comments";
                }
                                                
            function get_module_settings()
                {
                    $this->module_settings[]                  =   array(
                                                                        'id'            =>  'new_wp_comments_post',
                                                                        'label'         =>  __('New wp-comments-post.php',    'wp-hide-security-enhancer'),
                                                                        'description'   =>  __('The default path is set to wp-comments-post.php',    'wp-hide-security-enhancer'),
                                                                        
                                                                        'help'          =>  array(
                                                                                                        'title'                     =>  __('Help',    'wp-hide-security-enhancer') . ' - ' . __('New wp-comments-post.php',    'wp-hide-security-enhancer'),
                                                                                                        'description'               =>  __("As default the form data is being sent and processed at:",    'wp-hide-security-enhancer') ." <br />  <br />
                                                                                                                                            <code>https://-domain-name-/wp-comments-post.php</code>
                                                                                                                                            <br /><br /> " . __("This makes it easy to recognise as WordPress form. Boots always search for such file ( wp-comments-post.php ) and automatically submit spam messages.",    'wp-hide-security-enhancer') .
                                                                                                                                            __("Though this option a new file slug can replace the default.",    'wp-hide-security-enhancer'),
                                                                                                        'option_documentation_url'  =>  'https://www.wp-hide.com/documentation/rewrite-comments/',
                                                                                                        'input_value_extension'     =>  'php'
                                                                                                        ),
                                                                        
                                                                        'value_description' =>  'e.g. user-input.php',
                                                                        'input_type'    =>  'text',
                                                                        
                                                                        'sanitize_type' =>  array(array($this->wph->functions, 'sanitize_file_path_name'), array($this->wph->functions, 'php_extension_required')),
                                                                        'processing_order'  =>  60
                                                                        );
                                                                    
                    $this->module_settings[]                  =   array(
                                                                        'id'            =>  'block_wp_comments_post_url',
                                                                        'label'         =>  __('Block wp-comments-post.php',    'wp-hide-security-enhancer'),
                                                                        'description'   =>  __('Block default wp-comments-post.php.',    'wp-hide-security-enhancer'),
                                                                        
                                                                        'help'          =>  array(
                                                                                                        'title'                     =>  __('Help',    'wp-hide-security-enhancer') . ' - ' . __('Block wp-comments-post.php',    'wp-hide-security-enhancer'),
                                                                                                        'description'               =>  __("After changing the default wp-comments-post.php, the old url is still accessible, this provide a way to block the old.<br />The functionality apply only if <b>New wp-comments-post.php</b> option is filled in.",    'wp-hide-security-enhancer'),
                                                                                                        'option_documentation_url'  =>  'https://www.wp-hide.com/documentation/rewrite-comments/'
                                                                                                        ),
                                                                                                                           
                                                                        'input_type'    =>  'radio',
                                                                        'options'       =>  array(
                                                                                                    'no'        =>  __('No',     'wp-hide-security-enhancer'),
                                                                                                    'yes'       =>  __('Yes',    'wp-hide-security-enhancer'),
                                                                                                    ),
                                                                        'default_value' =>  'no',
                                                                        
                                                                        'sanitize_type' =>  array('sanitize_title', 'strtolower'),
                                                                        'processing_order'  =>  60
                                                                        
                                                                        );
                    
                                                                    
                    return $this->module_settings;   
                }
                
            
            
            function _init_new_wp_comments_post($saved_field_data)
                {
                   
                    if(empty($saved_field_data))
                        return FALSE;
                    
                    //add default plugin path replacement
                    $url            =   trailingslashit(    site_url()  ) .  'wp-comments-post.php';
                    $replacement    =   trailingslashit(    home_url()  ) .  $saved_field_data;
                    $this->wph->functions->add_replacement( $url , $replacement );
                    
                    return TRUE;
                }
                
            function _callback_saved_new_wp_comments_post($saved_field_data)
                {
                    $processing_response    =   array();
                    
                    if(empty($saved_field_data) ||  $saved_field_data   ==  'no')
                        return FALSE;
                                                            
                    $file_path   =   $this->wph->functions->get_url_path( trailingslashit(site_url()) . 'wp-comments-post.php'    );
                    
                    $rewrite_to     =   $this->wph->functions->get_rewrite_to_base( $file_path, TRUE, FALSE );
                               
                    if($this->wph->server_htaccess_config   === TRUE)
                        $processing_response['rewrite'] = "\nRewriteRule ^"    .   $saved_field_data   .   ' '. $rewrite_to .' [L,QSA]'; 
                    
                    if($this->wph->server_web_config   === TRUE)
                        $processing_response['rewrite'] = '
                            <rule name="wph-new_wp_comments_post" stopProcessing="true">
                                <match url="^'.  $saved_field_data   .'"  />
                                <action type="Rewrite" url="'.  $rewrite_to .'"  appendQueryString="true" />
                            </rule>
                                                            ';
                                
                    return  $processing_response;     
                    
                    
                }
            
            
            function _callback_saved_block_wp_comments_post_url($saved_field_data)
                {
                    $processing_response    =   array();
                    
                    if(empty($saved_field_data) ||  $saved_field_data   ==  'no')
                        return FALSE;
                    
                    //prevent from blocking if the wp_comments_post is not modified
                    $new_wp_comments_post     =   ltrim(rtrim($this->wph->functions->get_module_item_setting('new_wp_comments_post'), "/"),  "/");
                    if (empty(  $new_wp_comments_post ))
                        return FALSE;
                                        
                    $rewrite_base   =   $this->wph->functions->get_rewrite_base( 'wp-comments-post.php', FALSE, FALSE );
                    $rewrite_to     =   $this->wph->functions->get_rewrite_to_base( 'index.php', TRUE, FALSE, 'site_path' );
                    
                    $text   =   '';
                    
                    if($this->wph->server_htaccess_config   === TRUE)
                        {                                        
                            $text   =   "RewriteCond %{ENV:REDIRECT_STATUS} ^$\n";
                            $text   .=   "RewriteRule ^" . $rewrite_base ." ".  $rewrite_to ."?wph-throw-404 [L]";
                        }
                        
                    if($this->wph->server_web_config   === TRUE)
                            $text   = '
                                        <rule name="wph-block_wp_comments_post_url" stopProcessing="true">
                                            <match url="^' . $rewrite_base . '"  />
                                            <action type="Rewrite" url="'.  $rewrite_to .'?wph-throw-404" />  
                                        </rule>
                                                            ';
                               
                    $processing_response['rewrite'] = $text;            
                                
                    return  $processing_response;     
                    
                    
                }
                
            
        }
?>