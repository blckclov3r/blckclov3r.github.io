<?php

    if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
    
    class WPH_module_rewrite_author extends WPH_module_component
        {
            
            function get_component_title()
                {
                    return "Author";
                }
                                                
            function get_module_settings()
                {
                    $this->module_settings[]                  =   array(
                                                                        'id'            =>  'author',
                                                                        'label'         =>  __('New Author Path',    'wp-hide-security-enhancer'),
                                                                        'description'   =>  __('The default path is set to /author/',    'wp-hide-security-enhancer'),
                                                                        
                                                                        'help'          =>  array(
                                                                                                        'title'                     =>  __('Help',    'wp-hide-security-enhancer') . ' - ' . __('New Author Path',    'wp-hide-security-enhancer'),
                                                                                                        'description'               =>  __("An author URL display all posts associated to a particular author. The default URL format is:",    'wp-hide-security-enhancer') ."<br />  <br />
                                                                                                                                            <code>https://-domain-name-/author/author-name/</code>
                                                                                                                                            <br /><br /> " . __("By using a value of 'contributor' this become:",    'wp-hide-security-enhancer') ."<br />
                                                                                                                                            <code>https://-domain-name-/contributor/author-name/</code>",
                                                                                                        'option_documentation_url'  =>  'https://www.wp-hide.com/documentation/rewrite-author/',
                                                                                                        ),
                                                                        
                                                                        'value_description' =>  'e.g. contributor',
                                                                        'input_type'    =>  'text',
                                                                        
                                                                        'sanitize_type' =>  array(array($this->wph->functions, 'sanitize_file_path_name')),
                                                                        'processing_order'  =>  60
                                                                        );
                                                                        
                    $this->module_settings[]                  =   array(
                                                                        'id'            =>  'author_block_default',
                                                                        'label'         =>  __('Block default',    'wp-hide-security-enhancer'),
                                                                        'description'   =>  __('Block default /author/ when using custom one.',    'wp-hide-security-enhancer') . '<br />'.__('Apply only if ',    'wp-hide-security-enhancer') . '<b>New Author Path</b> ' . __('is not empty.',    'wp-hide-security-enhancer'),
                                                                        
                                                                        'help'          =>  array(
                                                                                                        'title'                     =>  __('Help',    'wp-hide-security-enhancer') . ' - ' . __('Block default',    'wp-hide-security-enhancer'),
                                                                                                        'description'               =>  __("After changing the default author, the old url is still accessible, this provide a way to block it.<br />The functionality apply only if <b>New Author Path</b> option is filled in.",    'wp-hide-security-enhancer'),
                                                                                                        'option_documentation_url'  =>  'https://www.wp-hide.com/documentation/rewrite-author/'
                                                                                                        ),
                                                                        
                                                                        'input_type'    =>  'radio',
                                                                        'options'       =>  array(
                                                                                                    'no'        =>  __('No',     'wp-hide-security-enhancer'),
                                                                                                    'yes'       =>  __('Yes',    'wp-hide-security-enhancer'),
                                                                                                    ),
                                                                        'default_value' =>  'no',
                                                                        
                                                                        'sanitize_type' =>  array('sanitize_title', 'strtolower'),
                                                                        'processing_order'  =>  61
                                                                        
                                                                        );
                                                                     
                    return $this->module_settings;   
                }
                
            
            
            function _init_author( $saved_field_data )
                {
                    add_filter('author_rewrite_rules',      array( $this, 'author_rewrite_rules'), 999);
                    
                    if(empty($saved_field_data))
                        return FALSE;
                    
                    //add default plugin path replacement
                    $url            =   trailingslashit(    site_url()  ) .  'author';
                    $replacement    =   trailingslashit(    home_url()  ) .  $saved_field_data;
                    $this->wph->functions->add_replacement( $url , $replacement );
                    
                    return TRUE;
                }
                
            
            /**
            * Rewrite the default Author url
            * 
            * @param mixed $author_rewrite
            */
            function author_rewrite_rules( $author_rewrite )
                {
                    
                    $new_author_path        =   $this->wph->functions->get_module_item_setting('author');
                    
                    if( empty( $new_author_path ) )
                        return $author_rewrite;
                        
                    $author_block_default   =   $this->wph->functions->get_module_item_setting('author_block_default');                    
                    
                    $new_rules              =   array();
                    foreach ( $author_rewrite   as  $key    =>  $value )
                        {
                            $new_rules[ str_replace( 'author/', $new_author_path .'/' , $key ) ]    =   $value;    
                        }
                        
                    if  ( $author_block_default ==  'yes')
                        $author_rewrite =   $new_rules;
                        else
                        $author_rewrite =   array_merge ( $author_rewrite, $new_rules );
                    
                    return $author_rewrite;
                      
                }
                
            
        }
?>