<?php

     if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
     
     class WPH_Environment
        {
            
            var $wph                            =   '';
            var $functions                      =   '';
        
            var $environment_variable           =   array();
            
                                  
            function __construct()
                {
                    global $wph;

                    $this->wph          =   $wph;
                    $this->functions    =   new WPH_functions();
                    
                    $this->setup_variable();
                    
                }
            
                
            /**
            * create the environment content variable
            * 
            */
            private function setup_variable()
                {
                                        
                    $this->environment_variable['theme'] =   array(
                                                                    'folder_name'   =>  $this->wph->templates_data['main']['folder_name'],
                                                                    'mapped_name'   =>  isset($this->wph->settings['module_settings']['new_theme_path']) ?   $this->wph->settings['module_settings']['new_theme_path']    :   ''
                                                                    );
                                                                    
                    if(isset($this->wph->templates_data['child']))
                        {
                            $this->environment_variable['child_theme'] =   array(
                                                                    'folder_name'   =>  $this->wph->templates_data['child']['folder_name'],
                                                                    'mapped_name'   =>  isset($this->wph->settings['module_settings']['new_theme_child_path']) ?     $this->wph->settings['module_settings']['new_theme_child_path']  :   ''
                                                                    );   
                        }
                    
                    $themes_url     =   untrailingslashit($this->wph->templates_data['themes_url']);
                    $themes_url     =   str_replace(array("http://", "https://"), "", $themes_url);
                    
                    
                    $site_url       =   site_url();
                    $site_url       =   str_replace(array("http://", "https://"), "", $site_url);
                    
                    $themes_url     =   str_replace($site_url, "", $themes_url);
                    $themes_path    =   str_replace( '\\', '/', ABSPATH . ltrim($themes_url, '/'));
                    
                    //set the allowe paths
                    $this->environment_variable['allowed_paths']  =   apply_filters('wp-hide/environment_file/allowed_paths', array( $themes_path ));
                    
                    $this->environment_variable['cache_path']     =   str_replace( '\\', '/', WPH_CACHE_PATH);
                    
                    $this->environment_variable['wordpress_directory']            =   $this->wph->default_variables['wordpress_directory'];
                    $this->environment_variable['site_relative_path']             =   $this->wph->default_variables['site_relative_path'];   
                    
                }
                
            
            /**
            * Check if the environment file exists and include correct data
            * 
            */
            public function is_correct_environment()
                {
                    
                    $wp_upload_dir              =   wp_upload_dir();
                    $environment_variable       =   '';
                            
                    if( file_exists(  $wp_upload_dir['basedir'] . '/wph/environment.php' ) )
                        {
                            require_once( $wp_upload_dir['basedir'] . '/wph/environment.php' );
                        }
                        else
                        return FALSE;
                                            
                    //if nothing has changed exit
                    if ( $environment_variable   ==  json_encode($this->environment_variable) )
                        {
                            //clear any notices regarding this file which is not correct
                            self::delete_all_notices();
                            
                            return TRUE;
                        }
                        
                    return FALSE;
                    
                }
                 
                
            public static function delete_all_notices()
                {
                    delete_option( 'wph-process_set_static_environment_errors');    
                }
      
            
            function get_environment_content()
                {
                    
                    ob_start();
                    
                    echo "<?php ";
                    echo "if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly";
                    echo "\n";
                    echo '$environment_variable = \''. json_encode($this->environment_variable) .'\';';
                    echo " ?>";
                    
                    $file_data = ob_get_contents();
                    ob_end_clean();
                    
                    return $file_data;    
                    
                }
            
            function write_environment()
                {
                    
                    global $wp_filesystem;

                    if (empty($wp_filesystem)) 
                        {
                            require_once (ABSPATH . '/wp-admin/includes/file.php');
                            WP_Filesystem();
                        }

                    $file_data  =   $this->get_environment_content();
                    
                    $wp_upload_dir              =   wp_upload_dir();
                    $errors         =   FALSE;
                    $error_messages  =   array();
                    
                    if ( is_wp_error( $wp_filesystem->errors ) && $wp_filesystem->errors->get_error_code() )
                        {
                            
                            $error_messages[]  =     array(
                                                            'type'      =>  'error',
                                                            'message'   =>  __('<b>WP Hide</b> - Unable to create environment static file. The system returned the following error: ', 'wp-hide-security-enhancer') . implode("," , (array)$wp_filesystem->errors->get_error_messages() )
                                                            );
                                                                                   
                            update_option( 'wph-process_set_static_environment_errors', $error_messages);
                            
                            return;   
                        }
                        
                    if ( ! is_dir( $wp_upload_dir['basedir'] . '/wph/' ) ) 
                        {
                            if  ( $wp_filesystem->mkdir( $wp_upload_dir['basedir'] . '/wph/' ) )
                                {
                                    $errors             =   TRUE;
                                    $error_messages[]   =     array(
                                                                    'type'      =>  'error',
                                                                    'message'   => __('Some of plugin options will not work correctly so where turned off: <b>Remove description header from Style file</b>, <b>Child - Remove description header from Style file</b>', 'wp-hide-security-enhancer')
                                                                    );
                                }
                        }
                        
                    if( !$errors    &&  ! $wp_filesystem->put_contents( $wp_upload_dir['basedir'] . '/wph/environment.php' , $file_data , FS_CHMOD_FILE) ) 
                        {
                            $errors             =   TRUE;
                            
                            $error_messages[]   =     array(
                                                                    'type'      =>  'error',
                                                                    'message'   => '<b>WP Hide</b> - ' . __('Unable to create environment data at ', 'wp-hide-security-enhancer') . $wp_upload_dir['basedir'] . '/wph/environment.php ' . __('Is file writable', 'wp-hide-security-enhancer') . '? ' . __('Check with folder/file permission or contact server administrator.', 'wp-hide-security-enhancer')
                                                                    );
                                     
                            $error_messages[]   =     array(
                                                                    'type'      =>  'error',
                                                                    'message'   => __('Some of plugin options will not work correctly so where turned off: <b>Remove description header from Style file</b>, <b>Child - Remove description header from Style file</b>', 'wp-hide-security-enhancer')
                                                                    );
                                 
                            //disable certain options
                            $this->settings['module_settings']['style_file_clean']          =   'no';
                            $this->settings['module_settings']['child_style_file_clean']    =   'no';
                            
                            //save the new options
                            $this->functions->update_settings( $this->settings );
                            
                            //regenerate permalinks
                            $this->wph->settings_changed();
                            
                        }    
                        
                    if  (  ! $errors )
                        {
                            self::delete_all_notices();
                        }
                        else
                        {
                            update_option( 'wph-process_set_static_environment_errors', $error_messages );
                        }
                    
                    
                }
                
        }   
            



?>