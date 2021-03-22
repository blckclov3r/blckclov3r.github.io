<?php

    if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
    
    class WPH_module_rewrite_new_theme_path extends WPH_module_component
        {
            
            var $rewrite_global_output      =   FALSE;
            
            var $cache_compare_for_clear    =   array();
            
            function get_component_title()
                {
                    return "Theme";
                }
            
                                     
            function get_module_settings()
                {
                    $this->module_settings[]                  =   array(
                                                                                'type'              =>  'split',
                                                                                'label'             =>  ucfirst( get_option('template') )  
                                                                                );
                    
                    $this->module_settings[]                  =   array(
                                                                    'id'            =>  'new_theme_path',
                                                                    'label'         =>  __('New Theme Path',    'wp-hide-security-enhancer'),
                                                                    'description'   =>  __('Change theme url, which as default the path is set to',    'wp-hide-security-enhancer') . ' <strong>'. str_replace(get_bloginfo('wpurl'), '' ,$this->wph->default_variables['template_url'])  .'/</strong>',
                                                                    
                                                                    'help'          =>  array(
                                                                                                        'title'                     =>  __('Help',    'wp-hide-security-enhancer') . ' - ' . __('New Theme Path',    'wp-hide-security-enhancer'),
                                                                                                        'description'               =>  __("This option helps to change the theme url to a custom one. As default all theme assets ( styles, JavaScript etc ) are loaded using the theme url and appear on front side html source like this:",    'wp-hide-security-enhancer') ." <br />  <br />
                                                                                                                                            <code>&lt;link rel='stylesheet' href='http://-domain-name-/wp-content/themes/Divi/style.css' type='text/css' media='all' /&gt;</code>
                                                                                                                                            <br /><br /> " . __("When using this option, if filling with `template`, all urls on front side become as follow:",    'wp-hide-security-enhancer') ." <br />  <br /> 
                                                                                                                                            <code>&lt;link rel='stylesheet' href='http://-domain-name-/template/style.css' type='text/css' media='all' /&gt;</code>",
                                                                                                        'option_documentation_url'  =>  'https://www.wp-hide.com/documentation/rewrite-theme/'
                                                                                                        ),
                                                                    
                                                                    'value_description'     =>  __('Example',    'wp-hide-security-enhancer') . ': <b>' . __('template',    'wp-hide-security-enhancer'),
                                                                    'input_type'            =>  'text',
                                                                                                                                        
                                                                    'sanitize_type' =>  array(array($this->wph->functions, 'sanitize_file_path_name'), 'strtolower'),
                                                                    'processing_order'  =>  10
                                                                    );
                    
                                        
                    $this->module_settings[]                  =   array(
                                                                    'id'            =>  'new_style_file_path',
                                                                    'label'         =>  __('New Style File Path',    'wp-hide-security-enhancer'),
                                                                    'description'   =>  __('Change default theme style file style.css, current path is set to',    'wp-hide-security-enhancer') . ' <strong>'. str_replace(get_bloginfo('wpurl'), '' ,   $this->wph->default_variables['template_url'])  .'/style.css</strong>',
                                                                    
                                                                    'help'          =>  array(
                                                                                                        'title'                     =>  __('Help',    'wp-hide-security-enhancer') . ' - ' . __('New Style File Path',    'wp-hide-security-enhancer'),
                                                                                                        'description'               =>  __("This allow to change the default style.css filename to something else e.g. template-style.css. Per this example, on front side the main style link change from /style.css to /template-style.css",    'wp-hide-security-enhancer') ." <br />  <br /> 
                                                                                                                                            <code>&lt;link rel='stylesheet' href='http://-domain-name-/template/template-style.css' type='text/css' media='all' /&gt;</code>",
                                                                                                        'option_documentation_url'  =>  'https://www.wp-hide.com/documentation/rewrite-theme/',
                                                                                                        'input_value_extension'     =>  'css'
                                                                                                        ),
                                                                    
                                                                    'value_description' =>  __('Example',    'wp-hide-security-enhancer') . ': <b>' . __('skin.css',    'wp-hide-security-enhancer'),
                                                                    'input_type'    =>  'text',
                                                                    
                                                                    'sanitize_type' =>  array(array($this->wph->functions, 'sanitize_file_path_name')),
                                                                    
                                                                    'processing_order'  =>  5
                                                                    );
                                                                    
                    $this->module_settings[]                  =   array(
                                                                        'id'            =>  'style_file_clean',
                                                                        'label'         =>  __('Remove description header from Style file',    'wp-hide-security-enhancer'),
                                                                        'description'   =>  
                                                                                            array(
                                                                                            __('Strip out all meta data from style file.',    'wp-hide-security-enhancer') . '<br />'
                                                                                            ),
                                                                        
                                                                        'help'          =>  array(
                                                                                                        'title'                     =>  __('Help',    'wp-hide-security-enhancer') . ' - ' . __('New Style File Path',    'wp-hide-security-enhancer'),
                                                                                                        'description'               =>  __("Strip out all meta data from style file as Theme Name, Theme URI, Author etc. Those are important informations for hackers to find out possible theme security breaches. A list of headers can e found at",    'wp-hide-security-enhancer') . " <a href='https://codex.wordpress.org/Theme_Development#Theme_Stylesheet' target='_blank'>". __("Theme Headers",    'wp-hide-security-enhancer') . "</a><br /><br />" .
                                                                                                                                        __("This feature may fail if style file url not available on html ( being concatenated ).",    'wp-hide-security-enhancer'),
                                                                                                        'option_documentation_url'  =>  'https://www.wp-hide.com/documentation/rewrite-theme/'
                                                                                                        ),
                                                                        
                                                                        'advanced_option'   =>  array(
                                                                        
                                                                                                        'description'               =>  '<b>' . __('This is an advanced option !',    'wp-hide-security-enhancer') . '</b><br />' . __('This can break the layout if server not supporting the feature. Once active test it thoroughly.<br />If not working, set to No to revert.',    'wp-hide-security-enhancer')
                                                                                                
                                                                                                ),
                                                                        
                                                                        'input_type'    =>  'radio',
                                                                        'options'       =>  array(
                                                                                                    'no'        =>  __('No',     'wp-hide-security-enhancer'),
                                                                                                    'yes'       =>  __('Yes',    'wp-hide-security-enhancer'),
                                                                                                    ),
                                                                                                    
                                                                        'options_post'  =>   '<p><span class="dashicons dashicons-warning important" alt="f534">warning</span> ' . __('This functionality use caching! If active, cache clear is recommended on styles updates.',    'wp-hide-security-enhancer') .' </p> <p><a href="admin.php?page=wp-hide&wph_cache_clear=true" class="button action">' . __("Cache Clear",    'wp-hide-security-enhancer') . "</a></p>" ,
                                                                        
                                                                        'default_value' =>  'no',
                                                                        
                                                                        'sanitize_type' =>  array('sanitize_title', 'strtolower'),
                                                                        'processing_order'  =>  3
                                                                        
                                                                        );
                                                                        
                                                                        
                    if($this->wph->templates_data['use_child_theme'])                                                
                        {
                            $this->module_settings[]                  =   array(
                                                                                'type'            =>  'split'
                                                                                
                                                                                );
                            
                            $this->module_settings[]                  =   array(
                                                                                'type'              =>  'split',
                                                                                'label'             =>  ucfirst( get_option('current_theme') )  
                                                                                );
                            
                            $this->module_settings[]                  =   array(
                                                                            'id'            =>  'new_theme_child_path',
                                                                            'label'         =>  __('New Theme Path',    'wp-hide-security-enhancer'),
                                                                            'description'   =>  __('Change child theme url, which as default the path is set to',    'wp-hide-security-enhancer') . ' <strong>'. str_replace(get_bloginfo('wpurl'), '' , trailingslashit($this->wph->templates_data['themes_url']) . $this->wph->templates_data['child']['folder_name'])  .'/</strong>',

                                                                            'help'          =>  array(
                                                                                                        'title'                     =>  __('Help',    'wp-hide-security-enhancer') . ' - ' . __('New Theme Path',    'wp-hide-security-enhancer'),
                                                                                                        'description'               =>  __("This option helps to change the child theme url to a custom one. As default all theme assets ( styles, JavaScript etc ) are loaded using the theme url and appear on front side html source like this:",    'wp-hide-security-enhancer') . " <br />  <br />
                                                                                                                                            <code>&lt;link rel='stylesheet' href='http://-domain-name-/wp-content/themes/Divi-child/style.css' type='text/css' media='all' /&gt;</code>
                                                                                                                                            <br /><br /> " . __("When using this option, if filling with `template-child`, all urls on front side become as follow:",    'wp-hide-security-enhancer') . " <br />  <br /> 
                                                                                                                                            <code>&lt;link rel='stylesheet' href='http://-domain-name-/template-child/style.css' type='text/css' media='all' /&gt;</code>",
                                                                                                        'option_documentation_url'  =>  'https://www.wp-hide.com/documentation/rewrite-theme/'
                                                                                                        ),
                                                                    
                                                                            'value_description'     =>  __('Example',    'wp-hide-security-enhancer') . ': <b>' . __('template-child',    'wp-hide-security-enhancer'),
                                                                            'input_type'    =>  'text',
                                                                            
                                                                            'sanitize_type' =>  array(array($this->wph->functions, 'sanitize_file_path_name'), 'strtolower'),
                                                                            'processing_order'  =>  9
                                                                            );
                                                                            
                            $this->module_settings[]                  =   array(
                                                                            'id'            =>  'child_style_file_path',
                                                                            'label'         =>  __('New Style File Path',    'wp-hide-security-enhancer'),
                                                                            'description'   =>  __('Change default child theme style file style.css, current path is set to',    'wp-hide-security-enhancer') . ' <strong>'. str_replace(get_bloginfo('wpurl'), '' ,   $this->wph->default_variables['stylesheet_uri'])  .'/style.css</strong>',
                                                                            
                                                                            'help'          =>  array(
                                                                                                        'title'                     =>  __('Help',    'wp-hide-security-enhancer') . ' - ' . __('New Style File Path',    'wp-hide-security-enhancer'),
                                                                                                        'description'               =>  __("This allow to change the default style.css filename to something else e.g. template-style.css. Per this example, on front side the main style link change from /style.css to /child-style.css",    'wp-hide-security-enhancer') . " <br />  <br /> 
                                                                                                                                            <code>&lt;link rel='stylesheet' href='http://-domain-name-/template-child/child-style.css' type='text/css' media='all' /&gt;</code>",
                                                                                                        'option_documentation_url'  =>  'https://www.wp-hide.com/documentation/rewrite-theme/',
                                                                                                        'input_value_extension'     =>  'css'
                                                                                                        ),
                                                                    
                                                                            'value_description' =>  __('Example',    'wp-hide-security-enhancer') . ': <b>' . __('child-skin.css',    'wp-hide-security-enhancer'),
                                                                            'input_type'    =>  'text',
                                                                            
                                                                            'sanitize_type' =>  array(array($this->wph->functions, 'sanitize_file_path_name')),
                                                                            
                                                                            'processing_order'  =>  5
                                                                            );
                                                                            
                            $this->module_settings[]                  =   array(
                                                                                'id'            =>  'child_style_file_clean',
                                                                                'label'         =>  __('Remove description header from Style file',    'wp-hide-security-enhancer'),
                                                                                'description'   =>  
                                                                                                    array(
                                                                                                        __('Strip out all meta data from child theme style file.',    'wp-hide-security-enhancer') . '<br />'
                                                                                                        ),
                                                                                
                                                                                'help'          =>  array(
                                                                                                        'title'                     =>  __('Help',    'wp-hide-security-enhancer') . ' - ' . __('New Style File Path',    'wp-hide-security-enhancer'),
                                                                                                        'description'               =>  __("Strip out all meta data from style file as Theme Name, Theme URI, Author etc. Those are important informations for hackers to find out possible theme security breaches. A list of headers can e found at",    'wp-hide-security-enhancer') . " <a href='https://codex.wordpress.org/Theme_Development#Theme_Stylesheet' target='_blank'>" . __("Theme Headers",    'wp-hide-security-enhancer') . "</a><br /><br />" .
                                                                                                                                        __("This feature may fail if style file url not available on html ( being concatenated ).",    'wp-hide-security-enhancer'),
                                                                                                        'option_documentation_url'  =>  'https://www.wp-hide.com/documentation/rewrite-theme/'
                                                                                                        ),
                                                                        
                                                                                'advanced_option'   =>  array(
                                                                                
                                                                                                                'description'               =>  '<b>' . __('This is an advanced option !',    'wp-hide-security-enhancer') . '</b><br />' . __('This can break the layout if server not supporting the feature. Ensure all regular options works fine before activate this. Once active test it thoroughly.<br />If not working, set to No to revert.',    'wp-hide-security-enhancer')
                                                                                                        
                                                                                                        ),
                                                                                
                                                                                
                                                                                'input_type'    =>  'radio',
                                                                                'options'       =>  array(
                                                                                                            'no'        =>  __('No',     'wp-hide-security-enhancer'),
                                                                                                            'yes'       =>  __('Yes',    'wp-hide-security-enhancer'),
                                                                                                            ),
                                                                                
                                                                                'options_post'  =>   '<p><span class="dashicons dashicons-warning important" alt="f534">warning</span> ' . __('This functionality use caching! If active, cache clear is recommended on styles updates.',    'wp-hide-security-enhancer') .'</p><p><a href="admin.php?page=wp-hide&wph_cache_clear=true" class="button action">' . __("Cache Clear",    'wp-hide-security-enhancer') . '</a></p>' ,
                                                                        
                                                                                'default_value' =>  'no',
                                                                                
                                                                                'sanitize_type' =>  array('sanitize_title', 'strtolower'),
                                                                                'processing_order'  =>  3
                                                                                
                                                                                );
                                                                                
                            add_filter('wp-hide/interface/process', array($this, 'interface_process'), 10, 3);
             
                        }
                        
                    add_action('wph/settings_changed', array($this, 'settings_changed'), 10, 2);
                                                                    
                    return $this->module_settings;   
                }
                
                
                
                
            /**
            * New Theme Path
            *     
            * @param mixed $saved_field_data
            */
            function _init_new_theme_path($saved_field_data)
                {
                    if(empty($saved_field_data))
                        return FALSE;

                    //add replacement url
                    $this->wph->functions->add_replacement( $this->wph->default_variables['template_url'], trailingslashit(    home_url()  )   .   $saved_field_data );

                }
                
            function _callback_saved_new_theme_path($saved_field_data)
                {
                    $processing_response    =   array();
                    
                    //check if the field is noe empty
                    if(empty($saved_field_data))
                        return  $processing_response; 
          
                    $theme_path =   $this->wph->functions->get_url_path( $this->wph->templates_data['themes_url'] . $this->wph->templates_data['main']['folder_name']    );
                    
                    $path           =   '';
                    $path           .=  trailingslashit(   $saved_field_data   );
                    
                    $theme_path = str_replace(' ', '%20', $theme_path);
                    $rewrite_to     =   $this->wph->functions->get_rewrite_to_base( $theme_path );
                               
                    if($this->wph->server_htaccess_config   === TRUE)
                        $processing_response['rewrite'] = "\nRewriteRule ^"    .   $path   .   '(.+) '. $rewrite_to .'$1 [L,QSA]';
                    
                    if($this->wph->server_web_config   === TRUE)
                        $processing_response['rewrite'] = '
                            <rule name="wph-new_theme_path" stopProcessing="true">
                                <match url="^'.  $path   .'(.*)"  />
                                <action type="Rewrite" url="'.  $rewrite_to .'{R:1}"  appendQueryString="true" />
                            </rule>
                                                            ';
                                
                    return  $processing_response;   
                }
                      
                         
            
            function _init_new_theme_child_path($saved_field_data)
                {
                    if(empty($saved_field_data))
                        return FALSE;

                    
                    //add replacement url
                    $this->wph->functions->add_replacement(  $this->wph->default_variables['stylesheet_uri'] , trailingslashit(    home_url()  )   .   untrailingslashit( $saved_field_data ) );

                }
                
            function _callback_saved_new_theme_child_path($saved_field_data)
                {
                    $processing_response    =   array();
                    
                    //check if the field is noe empty
                    if(empty($saved_field_data))
                        return  $processing_response; 
          
                    $theme_path =   $this->wph->functions->get_url_path( $this->wph->templates_data['themes_url'] . $this->wph->templates_data['child']['folder_name']    );
                    
                    $path           =   '';
                    $path           .=  trailingslashit(   $saved_field_data   );
                               
                    
                    $theme_path = str_replace(' ', '%20', $theme_path);
                    $rewrite_to     =   $this->wph->functions->get_rewrite_to_base( $theme_path );
                    
                    if($this->wph->server_htaccess_config   === TRUE)
                        $processing_response['rewrite']     =   "\nRewriteRule ^"    .   $path   .   '(.+) '. $rewrite_to .'$1 [L,QSA]';
                        
                    if($this->wph->server_web_config   === TRUE)
                        $processing_response['rewrite'] = '
                            <rule name="wph-new_theme_child_path" stopProcessing="true">
                                <match url="^'.  $path   .'(.*)"  />
                                <action type="Rewrite" url="'.  $rewrite_to .'{R:1}"  appendQueryString="true" />
                            </rule>
                                                            ';
                                
                    return  $processing_response;   
                }
                
                
          
            function _init_new_style_file_path($saved_field_data)
                {
                    if(empty($saved_field_data))
                        return FALSE;
                    
                    if($this->wph->functions->is_theme_customize())
                        return;    
                    
                    $new_theme_path     =   $this->wph->functions->get_module_item_setting('new_theme_path');
                    
                    //add default replacements
                    $template_url           =   trailingslashit( $this->wph->default_variables['template_url'] );
                    $old_style_file_path    =   trailingslashit( $this->wph->default_variables['template_url'] )    .   'style.css';
                    
                    if(!empty($new_theme_path))
                        {
                            $new_style_file_path    =  trailingslashit(    home_url()  )   .   trailingslashit($new_theme_path) . $saved_field_data;
                            $this->wph->functions->add_replacement( $old_style_file_path ,  $new_style_file_path );
                        }
                        else
                        {
                            $new_style_file_path    =  $template_url    .   $saved_field_data;
                            $this->wph->functions->add_replacement( $old_style_file_path ,  $new_style_file_path );
                        }
                            
                    
           
                    //add replacement for style.css when already template name replaced
                    if(!empty($new_theme_path))
                        {
                            $old_style_file_path    =   trailingslashit(    site_url()  ) . trailingslashit( $new_theme_path ) . 'style.css';
                            $this->wph->functions->add_replacement( $old_style_file_path ,  $new_style_file_path );
                        }
                  
                }
                
            function _callback_saved_new_style_file_path($saved_field_data)
                {
                    $processing_response    =   array();
                    
                    //check if the field is noe empty
                    if(empty($saved_field_data))
                        return  $processing_response; 
          
                    $current_stylesheet_uri     =   $this->wph->default_variables['template_url'];
                    $current_stylesheet_uri     =   $this->wph->functions->get_url_path( $current_stylesheet_uri );
                    $current_stylesheet_uri     =   trailingslashit( $current_stylesheet_uri ) . 'style.css';
                    
                    $path           =   '';
                    /*
                    if(!empty($this->wph->default_variables['wordpress_directory']))
                        $path           =   trailingslashit($this->wph->default_variables['wordpress_directory']);
                    */
                    
                    $new_theme_path     =   $this->wph->functions->get_module_item_setting('new_theme_path');
                    if(!empty($new_theme_path))
                        {
                            $path    .=  trailingslashit($new_theme_path) . $saved_field_data;
                        }
                        else
                        {
                            $template_relative_url  =   $this->wph->functions->get_url_path_relative_to_domain_root($this->wph->default_variables['template_url']);
                            $path    .=  trailingslashit($template_relative_url) . $saved_field_data;
                        }
                    
                    $current_stylesheet_uri = str_replace(' ', '%20', $current_stylesheet_uri);
                    $rewrite_to     =   $this->wph->functions->get_rewrite_to_base( $current_stylesheet_uri, TRUE, FALSE );
                               
                    if($this->wph->server_htaccess_config   === TRUE)
                        $processing_response['rewrite'] = "\nRewriteRule ^"    .   $path   .   ' '. $rewrite_to .' [L,QSA]';            
                        
                    if($this->wph->server_web_config   === TRUE)
                        $processing_response['rewrite'] = '
                            <rule name="wph-new_style_file_path" stopProcessing="true">
                                <match url="^'.  $path   .'"  />
                                <action type="Rewrite" url="'.  $rewrite_to .'"  appendQueryString="true" />
                            </rule>
                                                            ';
                                
                    return  $processing_response;   
                }

            
           
            function _init_style_file_clean($saved_field_data)
                {
                    
                    if(empty($saved_field_data) ||  $saved_field_data   ==  'no')
                        return FALSE;   
                    
                    //actual style file path
                    $file_path     =   trailingslashit(get_template_directory()) . 'style.css';
                    $file_path     =   str_replace( '\\', '/', $file_path);
                    $file_path     =   trim($file_path, '\\/ ');
                    
                    //not for windows
                    if ( DIRECTORY_SEPARATOR    !=  '\\')
                        $file_path      =   DIRECTORY_SEPARATOR . $file_path;
                                            
                    $this->cache_compare_for_clear[]    =   $file_path;
                    
                    if ( ! has_filter( 'shutdown', array($this, 'cache_compare_for_clear'), 999 ) )
                        add_action('shutdown', array($this, 'cache_compare_for_clear'), 999);
                    
                }
           
            
            function _callback_saved_style_file_clean($saved_field_data)
                {
                    
                    if(empty($saved_field_data) ||  $saved_field_data   ==  'no')
                        return FALSE;
                    
                    $wp_content_path =   $this->wph->functions->get_url_path( WP_PLUGIN_URL    );
                        
                    $processing_response    =   array();
                    
                    //actual style file path
                    $current_stylesheet_uri     =   $this->wph->default_variables['template_url'];
                    $current_stylesheet_uri     =   $this->wph->functions->get_url_path( $current_stylesheet_uri );
                    $current_stylesheet_uri     =   trailingslashit( $current_stylesheet_uri ) . 'style.css'; 
                                        
                    //current style file path
                    $path           =   '';
                    $new_theme_path         =  $this->wph->functions->get_module_item_setting('new_theme_path'); 
                    $new_style_file_path    =  $this->wph->functions->get_module_item_setting('new_style_file_path');
                    if(!empty($new_style_file_path))
                        {
                            /*
                            if(!empty($this->wph->default_variables['wordpress_directory']))
                                $path           =   trailingslashit($this->wph->default_variables['wordpress_directory']);
                            */
                            
                            
                            if(!empty($new_theme_path))
                                {
                                    $path    .=  trailingslashit($new_theme_path) . $new_style_file_path;
                                }
                                else
                                {
                                    $template_relative_url  =   $this->wph->functions->get_url_path_relative_to_domain_root($this->wph->default_variables['template_url']);
                                    $path    .=  trailingslashit($template_relative_url) . $new_style_file_path;
                                }
     
                        }
                        else if(!empty($new_theme_path))
                            {
                                $path           =  trailingslashit( $new_theme_path ) . 'style.css';   
                            }
                            else
                            {
                                //use the default
                                //  cont/themes/twentyfifteen/style.css
                                
                                $default_path   =   get_template_directory_uri();
                                   
                                //check for modified wp-content folder
                                $new_content_path =   $this->wph->functions->get_module_item_setting('new_content_path');
                                if(!empty($new_content_path))
                                    {
                                        $path   =   str_replace( trailingslashit( WP_CONTENT_URL ) , "/", $default_path);
                                        $path   =   $new_content_path . $path;
                                    }
                                    else
                                    {
                                        $path   =   str_replace( trailingslashit( WP_CONTENT_URL ) , "/", $default_path);
                                        
                                        $wp_content_folder      =   str_replace( site_url() , '' , WP_CONTENT_URL);
                                        $wp_content_folder      =   trim($wp_content_folder, '/');
                                        
                                        $path   =   $wp_content_folder . $path;
                                    }
                                
                                //$path       =   $this->wph->functions->get_url_path( get_template_directory_uri() );
                                $path       =  trailingslashit( $path ) . 'style.css';
                            }
                    
                    //plugin File Processor router path
                    $file_processor =   $this->wph->functions->get_url_path( WP_PLUGIN_URL    );
                    $file_processor =   $this->wph->functions->get_rewrite_to_base( trailingslashit( $file_processor ) . 'wp-hide-security-enhancer/router/file-process.php', TRUE, FALSE );
                    
                    $current_stylesheet_uri =   str_replace(' ', '%20', $current_stylesheet_uri);
                    $current_stylesheet_uri =   $this->wph->functions->get_rewrite_to_base( $current_stylesheet_uri, TRUE, FALSE );
                    $path                   =   str_replace(' ', '%20', $path);
                    
                    $processing_response['rewrite'] =   '';
                    if($this->rewrite_global_output === FALSE)
                        {
                            $processing_response['rewrite'] =   $this->get_rewrite_global_file_process();   
                            $this->rewrite_global_output    =   TRUE;
                        }
                    
                    
                    if($this->wph->server_htaccess_config   === TRUE)                               
                        $processing_response['rewrite'] .= "\nRewriteRule ^"    .   $path   .   ' '. $file_processor . '?action=style-clean&file_path=' . $current_stylesheet_uri .'&replacement_path=/'. $path .' [L,QSA]';
                        
                    if($this->wph->server_web_config   === TRUE)
                        $processing_response['rewrite'] .= '
                            <rule name="wph-style_file_clean" stopProcessing="true">
                                <match url="^'.  $path   .'"  />
                                <action type="Rewrite" url="'.  $file_processor .'?action=style-clean&amp;file_path=' . $current_stylesheet_uri .'&amp;replacement_path=/'. $path .'"  appendQueryString="true" />
                            </rule>
                                                            ';
                                      
                    return  $processing_response; 
                    
                }
            
            
                
                
            function _init_child_style_file_path($saved_field_data)
                {

                    if(empty($saved_field_data))
                        return FALSE;
                    
                    if($this->wph->functions->is_theme_customize())
                        return;
                        
                    $new_theme_path     =   $this->wph->functions->get_module_item_setting('new_theme_child_path');
                    
                    //add default replacements
                    $template_url           =   trailingslashit( $this->wph->default_variables['stylesheet_uri'] );
                    $old_style_file_path    =   trailingslashit( $this->wph->default_variables['stylesheet_uri'] )    .   'style.css';
                    
                    if(!empty($new_theme_path))
                        {
                            $new_style_file_path    =  trailingslashit(    home_url()  )   .   trailingslashit($new_theme_path) . $saved_field_data;
                            $this->wph->functions->add_replacement( $old_style_file_path , $new_style_file_path );
                        }
                        else
                        {
                            $new_style_file_path    =  $template_url    .   $saved_field_data;
                            $this->wph->functions->add_replacement( $old_style_file_path , $new_style_file_path );
                        }
                            
                    
           
                    //add replacement for style.css when already template name replaced
                    if(!empty($new_theme_path))
                        {
                            $old_style_file_path    =   trailingslashit(    site_url()  ) . trailingslashit( $new_theme_path ) . 'style.css';
                            $this->wph->functions->add_replacement( $old_style_file_path ,  $new_style_file_path );
                        }
                        
           
                }
                
            function _callback_saved_child_style_file_path($saved_field_data)
                {
                    
                    $processing_response    =   array();
                    
                    //check if the field is noe empty
                    if(empty($saved_field_data))
                        return  $processing_response; 
          
                    $current_stylesheet_uri     =   $this->wph->default_variables['stylesheet_uri'];
                    $current_stylesheet_uri     =   $this->wph->functions->get_url_path( $current_stylesheet_uri, TRUE );
                    $current_stylesheet_uri     =   trailingslashit( $current_stylesheet_uri ) . 'style.css';
                    
                    $path           =   '';
                    /*
                    if(!empty($this->wph->default_variables['wordpress_directory']))
                        $path           =   trailingslashit($this->wph->default_variables['wordpress_directory']);
                    */
                    
                    $new_theme_path     =   $this->wph->functions->get_module_item_setting('new_theme_child_path');
                    if(!empty($new_theme_path))
                        {
                            $path    .=  trailingslashit($new_theme_path) . $saved_field_data;
                        }
                        else
                        {
                            $template_relative_url  =   $this->wph->functions->get_url_path_relative_to_domain_root($this->wph->default_variables['stylesheet_uri']);
                            $path    .=  trailingslashit($template_relative_url) . $saved_field_data;
                        }
                    
                    $current_stylesheet_uri = str_replace(' ', '%20', $current_stylesheet_uri);
                    $rewrite_to     =   $this->wph->functions->get_rewrite_to_base( $current_stylesheet_uri, TRUE, FALSE );
                    
                    if($this->wph->server_htaccess_config   === TRUE)           
                        $processing_response['rewrite'] = "\nRewriteRule ^"    .   $path   .   ' '. $rewrite_to .' [L,QSA]';            
                    
                    if($this->wph->server_web_config   === TRUE)
                        $processing_response['rewrite'] = '
                            <rule name="wph-child_style_file_path" stopProcessing="true">
                                <match url="^'.  $path   .'"  />
                                <action type="Rewrite" url="'.  $rewrite_to .'"  appendQueryString="true" />
                            </rule>
                                                            ';
                                
                    return  $processing_response;   
                }
 

            function _init_child_style_file_clean($saved_field_data)
                {
                    
                    if(empty($saved_field_data) ||  $saved_field_data   ==  'no')
                        return FALSE;   
                    
                    //actual style file path
                    $file_path     =   trailingslashit(get_stylesheet_directory()) . 'style.css';
                    $file_path     =   str_replace( '\\', '/', $file_path);
                    $file_path     =   trim($file_path, '\\/ ');
                    
                    //not for windows
                    if ( DIRECTORY_SEPARATOR    !=  '\\')
                        $file_path      =   DIRECTORY_SEPARATOR . $file_path;
                                            
                    $this->cache_compare_for_clear[]    =   $file_path;
                    
                    if ( ! has_filter( 'shutdown', array($this, 'cache_compare_for_clear'), 999 ) )
                        add_action('shutdown', array($this, 'cache_compare_for_clear'), 999);
                    
                }
 
            function _callback_saved_child_style_file_clean($saved_field_data)
                {
                    
                    if(empty($saved_field_data) ||  $saved_field_data   ==  'no')
                        return FALSE;
                        
                    $processing_response    =   array();
                    
                    //actual style file path
                    $current_stylesheet_uri     =   trailingslashit ( $this->wph->templates_data['themes_url'] ) . $this->wph->templates_data['child']['folder_name'];
                    $current_stylesheet_uri     =   $this->wph->functions->get_url_path( $current_stylesheet_uri );
                    $current_stylesheet_uri     =   trailingslashit( $current_stylesheet_uri ) . 'style.css'; 
                                        
                    //current style file path
                    $path           =   '';
                    $new_theme_path         =  $this->wph->functions->get_module_item_setting('new_theme_child_path'); 
                    $new_style_file_path    =  $this->wph->functions->get_module_item_setting('child_style_file_path');
                    if(!empty($new_style_file_path))
                        {
                            /*
                            if(!empty($this->wph->default_variables['wordpress_directory']))
                                $path           =   trailingslashit($this->wph->default_variables['wordpress_directory']);
                            */
                            
                            if(!empty($new_theme_path))
                                {
                                    $path    .=  trailingslashit($new_theme_path) . $new_style_file_path;
                                }
                                else
                                {
                                    $template_relative_url  =   $this->wph->functions->get_url_path_relative_to_domain_root($this->wph->default_variables['stylesheet_uri']);
                                    $path    .=  trailingslashit($template_relative_url) . $new_style_file_path;
                                }
                        }
                        else if(!empty($new_theme_path))
                            {
                                $path           =  trailingslashit( $new_theme_path ) . 'style.css';   
                            }
                            else
                            {
                                //use the default
                                //  cont/themes/twentyfifteen/style.css
                                
                                $default_path   =   trailingslashit ( $this->wph->templates_data['themes_url'] ) . $this->wph->templates_data['child']['folder_name'];
                                   
                                //check for modified wp-content folder
                                $new_content_path =   $this->wph->functions->get_module_item_setting('new_content_path');
                                if(!empty($new_content_path))
                                    {
                                        $path   =   str_replace( trailingslashit( WP_CONTENT_URL ) , "/", $default_path);
                                        $path   =   $new_content_path . $path;
                                    }
                                    else
                                    {
                                        $path   =   str_replace( trailingslashit( WP_CONTENT_URL ) , "/", $default_path);
                                        
                                        $wp_content_folder      =   str_replace( site_url() , '' , WP_CONTENT_URL);
                                        $wp_content_folder      =   trim($wp_content_folder, '/');
                                        
                                        $path   =   $wp_content_folder . $path;
                                    }
                                
                                //$path       =   $this->wph->functions->get_url_path( get_template_directory_uri() );
                                $path       =  trailingslashit( $path ) . 'style.css';
                            }
                    
                    //plugin File Processor router path
                    $file_processor =   $this->wph->functions->get_url_path( WP_PLUGIN_URL );
                    $file_processor =   $this->wph->functions->get_rewrite_to_base( trailingslashit( $file_processor ) . 'wp-hide-security-enhancer/router/file-process.php', TRUE, FALSE );
                    
                    $current_stylesheet_uri =   str_replace(' ', '%20', $current_stylesheet_uri);
                    $current_stylesheet_uri =   $this->wph->functions->get_rewrite_to_base( $current_stylesheet_uri, TRUE, FALSE );
                    $path                   =   str_replace(' ', '%20', $path);
                    
                    $processing_response['rewrite'] =   '';
                    if($this->rewrite_global_output === FALSE)
                        {
                            $processing_response['rewrite'] =   $this->get_rewrite_global_file_process();   
                            $this->rewrite_global_output    =   TRUE;
                        }
                    
                    
                    if($this->wph->server_htaccess_config   === TRUE)                               
                        $processing_response['rewrite'] .= "\nRewriteRule ^"    .   $path   .   ' '. $file_processor . '?action=style-clean&file_path=' . $current_stylesheet_uri .'&replacement_path=/'. $path .' [L,QSA]';
                    
                    if($this->wph->server_web_config   === TRUE)
                        $processing_response['rewrite'] .= '
                            <rule name="wph-child-style_file_clean" stopProcessing="true">
                                <match url="^'.  $path   .'"  />
                                <action type="Rewrite" url="'.  $file_processor .'?action=style-clean&amp;file_path=' . $current_stylesheet_uri .'&amp;replacement_path=/'. $path .'"  appendQueryString="true" />
                            </rule>
                                                            ';
                                
                    return  $processing_response; 
                    
                }
                
                
            function get_rewrite_global_file_process()
                {
                    
                    $rewrite    =   '';
                    
                    $cache_path =   '%{DOCUMENT_ROOT}';
                    $cache_path .=  $this->wph->functions->get_rewrite_to_base( $this->wph->default_variables['content_directory'], TRUE, FALSE, 'full_path' );

                    $actual_cache_path  =   '';
                    $actual_cache_path .=  $this->wph->functions->get_rewrite_to_base( $this->wph->default_variables['content_directory'], TRUE, FALSE, 'full_path' );
                    
                    if($this->wph->server_htaccess_config   === TRUE)
                        {
                                                        
                            $rewrite    =   "\n" . 'RewriteCond "' . $cache_path . '/cache/wph/%{HTTP_HOST}%{REQUEST_URI}" -f' ."\n" .
                                                'RewriteRule .* "' . $actual_cache_path . '/cache/wph/%{HTTP_HOST}%{REQUEST_URI}" [L]' ."\n";
                        }
                    
                    if($this->wph->server_web_config   === TRUE)
                        {
                            $rewrite    =   "\n" . '
                            <rule name="wph_rewrite_global_file_process" stopProcessing="true">
                                <match url=".*" />
                                <conditions>
                                    <add input="' . $cache_path . '/cache/wph/{HTTP_HOST}{URL}" matchType="IsFile" />
                                </conditions>
                                <action type="Rewrite" url="' . $actual_cache_path . '/cache/wph/{HTTP_HOST}{URL}" appendQueryString="false" />
                            </rule>' ."\n";   
                            
                        }
                    
                    return $rewrite;
                    
                }
                
                
            /**
            * Compare if there's any changes on the cached files
            * Trigger cache clear if something has changed
            * 
            */
            function cache_compare_for_clear()
                {
                    
                    global $wp_filesystem;

                    if (empty($wp_filesystem)) 
                        {
                            require_once (ABSPATH . '/wp-admin/includes/file.php');
                            WP_Filesystem();
                        }
                    
                       
                    $access_type = get_filesystem_method();
                    if($access_type !== 'direct')
                        return;

                    $cache_files_data   =   get_option('wph_cache_files_data');
                    $found_changes      =   FALSE;
                    
                    foreach($this->cache_compare_for_clear  as  $file)
                        {
                            
                            $file_size  =   $wp_filesystem->size( $file );
                            $file_time  =   $wp_filesystem->mtime( $file );
                            
                            if(isset($cache_files_data[md5($file)]))
                                {
                                    $file_data  =      $cache_files_data[md5($file)];
                                    
                                    if($file_data['size']   !=  $file_size  ||  $file_data['time']  !=  $file_time)
                                        $found_changes  =   TRUE;
                                    
                                }
                            
                            $file_data['size']  =   $file_size;
                            $file_data['time']  =   $file_time;
                            
                            $cache_files_data[md5($file)]  =   $file_data;
                            
                        }
              
                    update_option('wph_cache_files_data', $cache_files_data);
                    
                    if($found_changes   === TRUE)
                        $this->wph->functions->cache_clear();
                    
                }
                
            
            /**
            * Ensure specific processing execution when saving the options
            *     
            * @param mixed $errors
            * @param mixed $process_interface_save_errors
            * @param mixed $_settings_
            * @param mixed $module_settings
            */
            function interface_process( $errors, $_settings_, $module_settings )
                {
                    global $process_interface_save_errors;
                       
                    //when using the 'Child - New Theme Path' trigger a warning tha the 'New Theme Path' should be changed to, to avoid relative paths issues within child styles
                    
                    if( isset ( $_settings_['new_theme_path'] ) &&  isset ( $_settings_['new_theme_child_path'] ) &&    $_settings_['new_theme_path']    ==  ''  &&  $_settings_['new_theme_child_path'] !=  '')
                        {
                            $process_interface_save_errors[]    =   array(  'type'      =>  'warning',
                                                                            'message'   =>  __('When changing the Child Theme Path it is recommended to also change the Main Theme Path to avoid relative paths issues within style files and layout break.', 'wp-hide-security-enhancer')
                                                                                    );
                        }
                       
                    return $errors;    
                }
                
                
            /**
            * Flush the style cache when this area being changed
            *     
            * @param mixed $screen_slug
            * @param mixed $tab_slug
            */
            function settings_changed( $screen_slug, $tab_slug )
                {
                    if( strtolower( $this->get_component_title() ) != $tab_slug )
                        return;   
                    
                    $this->wph->functions->cache_clear();
                    
                }
 
          
        }
?>