<?php

    if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
    
    class WPH_module_rewrite_new_plugin_path extends WPH_module_component
        {
            
            function get_component_title()
                {
                    return "Plugins";
                }
                                    
            function get_module_settings()
                {
                    $this->module_settings[]                  =   array(
                                                                        'id'            =>  'new_plugin_path',
                                                                        'label'         =>  __('New Plugins Path',    'wp-hide-security-enhancer'),
                                                                        'description'   =>  __('The default plugins path is set to',    'wp-hide-security-enhancer') . ' <strong>'. str_replace(get_bloginfo('wpurl'), '' ,$this->wph->default_variables['plugins_url'])  .'</strong>',
                                                                        
                                                                        'help'          =>  array(
                                                                                                        'title'                     =>  __('Help',    'wp-hide-security-enhancer') . ' - ' . __('New Plugins Path',    'wp-hide-security-enhancer'),
                                                                                                        'description'               =>  __("Use any alphanumeric symbols for this field which will be used as the new slug for the plugins folder. Presuming the `apps` slug is being used, all plugins urls become to something like this:",    'wp-hide-security-enhancer') . "<br />  <br />
                                                                                                                                            <code>http://-domain-name-/apps/jetpack/</code>",
                                                                                                        'option_documentation_url'  =>  'https://www.wp-hide.com/documentation/rewrite-plugins/'
                                                                                                        ),
                                                                        
                                                                        'value_description' =>  'e.g. apps',
                                                                        'input_type'    =>  'text',
                                                                        
                                                                        'sanitize_type' =>  array(array($this->wph->functions, 'sanitize_file_path_name')),
                                                                        'processing_order'  =>  17
                                                                        );
                                                                    
                    $this->module_settings[]                  =   array(
                                                                        'id'            =>  'block_plugins_url',
                                                                        'label'         =>  __('Block plugins URL',    'wp-hide-security-enhancer'),
                                                                        'description'   =>  __('Block default /wp-content/plugins/ files from being accesible through default urls.',    'wp-hide-security-enhancer'),
                                                                        
                                                                        'help'          =>  array(
                                                                                                        'title'                     =>  __('Help',    'wp-hide-security-enhancer') . ' - ' . __('Block plugins URL',    'wp-hide-security-enhancer'),
                                                                                                        'description'               =>  __("This blocks the default wp-content/plugins/ url.<br />The functionality apply only if <b>New Plugins Path</b> option is filled in.",    'wp-hide-security-enhancer'),
                                                                                                        'option_documentation_url'  =>  'https://www.wp-hide.com/documentation/rewrite-plugins/'
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
                                                                        'processing_order'  =>  18
                                                                        
                                                                        );
                    
                    
                    $this->module_settings[]                  =   array(
                                                                        'type'            =>  'split'
                                                                        
                                                                        );
                    
                    $all_plugins = $this->wph->functions->get_plugins();
                    
                    //get active plugins
                    $active_plugins = (array) get_option( 'active_plugins', array() );
                    foreach($active_plugins as  $active_plugin)
                        {
                            //exclude this plugin
                            if('wp-hide-security-enhancer/wp-hide.php'  == $active_plugin)
                                continue; 
                            
                            $plugin_slug    =   sanitize_title($active_plugin);
                            
                            if(!isset($all_plugins[$active_plugin]))
                                continue; 
                                
                            $pluding_data   =   $all_plugins[$active_plugin];
                                                                            
                            $this->module_settings[]                  =   array(
                                                                                'id'            =>  'new_plugin_path_' . $plugin_slug,
                                                                                'label'         =>  __('New Path for',    'wp-hide-security-enhancer') . " <i>" . $pluding_data['Name'] ."</i> ". __('plugin',    'wp-hide-security-enhancer'),
                                                                                'description'   =>  __('This setting if set, overwrites the',    'wp-hide-security-enhancer') . ' ' . __('New Plugin Path',    'wp-hide-security-enhancer') . ' ' . __('value for this plugin.',    'wp-hide-security-enhancer'),
                                                                                
                                                                                'help'          =>  array(
                                                                                                        'title'                     =>  __('Help',    'wp-hide-security-enhancer') . ' - ' . __('New Path for',    'wp-hide-security-enhancer') . " <i>" . $pluding_data['Name'] ."</i> ",
                                                                                                        'description'               =>  "Use any alphanumeric symbols for this field which will be used as the new slug for the plugin folder. Presuming the `module_name` slug is being used, this particular plugin urls become to:<br />  <br />
                                                                                                                                            <code>http://-domain-name-/module_name/</code>",
                                                                                                        'option_documentation_url'  =>  'https://www.wp-hide.com/documentation/rewrite-plugins/'
                                                                                                        ),
                                                                                
                                                                                'value_description' =>  'e.g. modules/module_name',
                                                                                'input_type'    =>  'text',
                                                                                
                                                                                'sanitize_type' =>  array(array($this->wph->functions, 'sanitize_file_path_name')),
                                                                                
                                                                                'processing_order'  =>  16
                                                                                );
                                                                        
                        }
                                                                    
                    return $this->module_settings;   
                }
                
                
                
            function _init_new_plugin_path($saved_field_data)
                {
                    
                    //add custom plugins path replacements
                    //get active plugins
                    $active_plugins = (array) get_option( 'active_plugins', array() );
                    foreach($active_plugins as  $active_plugin)
                        {
                            $active_plugin_split        =   explode('/', $active_plugin);
                            $active_plugin_directory    =   $active_plugin_split[0];
                                     
                            $plugin_slug        =   sanitize_title($active_plugin);
                            $option_namespace   =   'new_plugin_path_' . $plugin_slug;
                                
                            //check if plugin have custom url
                            $plugin_custom_path =   $this->wph->functions->get_module_item_setting($option_namespace);
                            if(empty($plugin_custom_path))
                                continue;
                                
                            //add custom path
                            $new_url    =   trailingslashit(    site_url()  ) .  $plugin_custom_path;
                            if(!empty($path))
                                $new_url    =   trailingslashit(    $new_url  ) .  $path;
                                
                            //add replacement
                            $new_plugin_path        =   $this->wph->functions->untrailingslashit_all(    $this->wph->functions->get_module_item_setting('new_plugin_path')  );
                            $replace_url            =   trailingslashit(    trailingslashit(    WP_PLUGIN_URL  )   . $active_plugin_directory );
                            $replacement_url        =   trailingslashit(    trailingslashit(    home_url()  ) .  $plugin_custom_path );
                            $this->wph->functions->add_replacement( $replace_url, $replacement_url);
 
                        }
                    
                    
                    if(empty($saved_field_data))
                        return FALSE;
                    
                    //add default plugin path replacement
                    $new_plugin_path        =   $this->wph->functions->untrailingslashit_all(    $this->wph->functions->get_module_item_setting('new_plugin_path')  );
                    $new_plugin_path        =   trailingslashit(    home_url()  )   . untrailingslashit(  $new_plugin_path    );
                    $this->wph->functions->add_replacement( WP_PLUGIN_URL, $new_plugin_path );
                    
                    return TRUE;
                }
        
                
            function _callback_saved_new_plugin_path($saved_field_data)
                {
                    $processing_response    =   array();
                                                         
                    $rewrite                            =  '';
                    
                    $plugin_path =   $this->wph->functions->get_url_path( WP_PLUGIN_URL );
                    
                    $path           =   '';
                                   
                    if(!empty($saved_field_data))
                        $path           .=  trailingslashit(   $saved_field_data   );
                        
                    $rewrite_to     =   $this->wph->functions->get_rewrite_to_base( $plugin_path );
                    
                    
                    //add custom rewrite for plugins
                    //get active plugins
                    $active_plugins = (array) get_option( 'active_plugins', array() );
                    foreach($active_plugins as  $active_plugin)
                        {
                            $active_plugin_split        =   explode('/', $active_plugin);
                            $active_plugin_directory    =   $active_plugin_split[0];
                              
                            $plugin_slug        =   sanitize_title($active_plugin);
                            $option_namespace   =   'new_plugin_path_' . $plugin_slug;
                                
                            //check if plugin have custom url
                            $plugin_custom_path =   $this->wph->functions->get_module_item_setting($option_namespace);
                            if(empty($plugin_custom_path))
                                continue;
                                
                            //add custom path
                            $new_url    =   trailingslashit(    site_url()  ) .  $plugin_custom_path;
                            if($path    !=  '/')
                                $new_url    =   trailingslashit(    $new_url  ) .  $path;
                            
                            $plugin_rewrite_to  =   $this->wph->functions->get_rewrite_to_base( trailingslashit($plugin_path) . $active_plugin_directory );
                                                                         
                            if($this->wph->server_htaccess_config   === TRUE)
                                $rewrite    .= "\nRewriteRule ^"    .   trailingslashit(   $plugin_custom_path   )   .   '(.+) '. $plugin_rewrite_to .'$1 [L,QSA]';
                                
                            if($this->wph->server_web_config   === TRUE)
                                $rewrite    .= '
                                            <rule name="wph-new_plugin_path-'.  $plugin_slug    .'" stopProcessing="true">
                                                <match url="^'.  trailingslashit(   $plugin_custom_path   )   .'(.*)"  />
                                                <action type="Rewrite" url="'.  $plugin_rewrite_to .'{R:1}"  appendQueryString="true" />
                                            </rule>
                                                                            ';
                        }
                    
                    if( !empty($path) &&  !empty($saved_field_data))           
                        {
                            if($this->wph->server_htaccess_config   === TRUE)
                                $rewrite  .= "\nRewriteRule ^"    .   trailingslashit(   $path   )   .   '(.+) '. $rewrite_to .'$1 [L,QSA]';
                                
                            if($this->wph->server_web_config   === TRUE)
                                $rewrite    .= '
                                            <rule name="wph-new_plugin_path" stopProcessing="true">
                                                <match url="^'.  trailingslashit(   $path   )   .'(.*)"  />
                                                <action type="Rewrite" url="'.  $rewrite_to .'{R:1}"  appendQueryString="true" />
                                            </rule>
                                                                            ';
                        }
                        
                    $processing_response['rewrite']    =   $rewrite;
                                
                    return  $processing_response;   
                }
                  
                
            function _callback_saved_block_plugins_url($saved_field_data)
                {
                    $processing_response    =   array();
                    
                    if(empty($saved_field_data) ||  $saved_field_data   ==  'no')
                        return FALSE;
                    
                    //prevent from blocking if the wp-include is not modified
                    $new_theme_path     =   ltrim(rtrim($this->wph->functions->get_module_item_setting('new_plugin_path'), "/"),  "/");
                    if (empty(  $new_theme_path ))
                        return FALSE;
                    
                    $home_url   =   defined('WP_HOME')  ?   WP_HOME         :   get_option('home');
                    $home_url   =   untrailingslashit($home_url);
                    
                    $default_plugin_url    =   untrailingslashit   (   WP_PLUGIN_URL  );
                    $default_plugin_url    =   str_replace(    $home_url, "", $default_plugin_url);
                    $default_plugin_url    =   ltrim(rtrim($default_plugin_url, "/"),  "/");
                    
                    $rewrite_to     =   $this->wph->functions->get_rewrite_to_base( 'index.php', TRUE, FALSE, 'site_path' );
                    
                    $text   =   '';
                    
                    if($this->wph->server_htaccess_config   === TRUE)
                        {                    
                            $text   =   "RewriteCond %{ENV:REDIRECT_STATUS} ^$\n";
                            $text   .=   "RewriteRule ^".   $default_plugin_url   ."(.+) ".  $rewrite_to ."?wph-throw-404 [L]";
                        }
                        
                    if($this->wph->server_web_config   === TRUE)
                        $text    =  '
                                    <rule name="wph-block_plugins_url" stopProcessing="true">
                                        <match url="^'.  $default_plugin_url   .'(.*)"  />
                                        <action type="Rewrite" url="'.  $rewrite_to .'?wph-throw-404"  />
                                    </rule>
                                                                    ';
                               
                    $processing_response['rewrite'] = $text;            
                                
                    return  $processing_response;     
                    
                    
                }


        }
?>