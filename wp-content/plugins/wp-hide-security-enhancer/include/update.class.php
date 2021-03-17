<?php

    if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
    
    class WPH_update
        {
            var $wph;
                                  
            function __construct()
                {
                    global $wph;
                    $this->wph          =   &$wph;
                    
                    $this->_run();
                }
                
                
            private function _run()
                {                    
                    $version        =   isset($this->wph->settings['version']) ?   $this->wph->settings['version'] :   1;
                        
                    //make sure the WPH_VERSION constant is being defined, 
                    //this issue occoured in version 1.3.9
                    //We set this for 1.3.9 as this is the code version whcih included the issue, all other version include the correnct data
                    if(!defined('WPH_VERSION')  &&  defined('WPH_MULOADER') &&  version_compare($version, '1.3.9', '<='))
                        {
                            define('WPH_VERSION',   '1.3.9');
                            
                            //attempt to copy over the new version of wp-hide-loader.php
                            WPH_functions::copy_mu_loader( TRUE );
                            
                        }
                    
                    
                    if (version_compare($version, WPH_CORE_VERSION, '<')) 
                        {
                            
                            $_trigger_flush_rules           =   FALSE;
                            $_set_static_environment_file   =   FALSE;
                            $_trigger_site_cache_flush      =   FALSE;
                            
                            if(version_compare($version, '1.1', '<'))
                                {
                                    //structure and settings fields where changed since v1.1
                                    if( isset($this->wph->settings['module_settings']['rewrite_new_theme_path']) )
                                        {
                                            $module_settings    =   $this->wph->settings['module_settings'];
                                            $this->wph->settings['module_settings'] =   array();
                                          
                                            foreach($module_settings    as  $key    =>  $value)
                                                {
                                                    if(strpos($key, 'rewrite_') !== FALSE &&    strpos($key, 'rewrite_') ==  0)
                                                        $key    =   substr($key,    8);
                                                        
                                                    if(strpos($key, 'general_') !== FALSE &&    strpos($key, 'general_') ==  0)
                                                        $key    =   substr($key,    8);
                                                        
                                                    if(strpos($key, 'admin_') !== FALSE &&    strpos($key, 'admin_') ==  0)
                                                        $key    =   substr($key,    6);
                                                        
                                                    $key    =   trim($key);
                                                    if(empty($key))
                                                        continue;
                                                    
                                                    $this->wph->settings['module_settings'][$key]   =   $value;
                                                }
                                        }
                                    
                                    $version =   '1.1';
                                }
                            
                                                    
                            if(version_compare($version, '1.3', '<'))
                                {
                                    //flush rules
                                    $_trigger_flush_rules   =   TRUE;
                                    
                                    $version =   '1.3';
                                }
                    
                            
                            if(version_compare($version, '1.3.2', '<'))
                                {
                                    //flush rules
                                    $_trigger_flush_rules   =   TRUE;
                                    
                                    $version =   '1.3.2';
                                }
                                
                            if(version_compare($version, '1.3.2.2', '<'))
                                {
                                    if(isset($this->wph->settings['module_settings']['remove_version']) &&  $this->wph->settings['module_settings']['remove_version']   ==  "yes")
                                        {
                                            $this->wph->settings['module_settings']['styles_remove_version']        =   'yes';
                                            $this->wph->settings['module_settings']['scripts_remove_version']       =   'yes';
                                            
                                            unset($this->wph->settings['module_settings']['remove_version']);   
                                        }
                                                                        
                                    $version =   '1.3.2.2';
                                }
                            
                            /**
                            * Create the environment file    
                            */
                            if(version_compare($version, '1.4', '<'))
                                {
                                    
                                    
                                    //copy over the new mu-loader version
                                    WPH_functions::copy_mu_loader( TRUE );
                                    
                                    $_trigger_flush_rules   =   TRUE;
                                    
                                    $version =   '1.4';
                                    
                                }
                                
                            /**
                            * Update the environment file and mu loader    
                            */
                            if(version_compare($version, '1.4.1', '<'))
                                {
                                    
                                    
                                    //copy over the new mu-loader version
                                    WPH_functions::copy_mu_loader( TRUE );
                                    
                                    
                                    $version =   '1.4.1';                    
                                }
                                
                            if(version_compare($version, '1.4.2', '<'))
                                {
                                    
                                    $_trigger_flush_rules   =   TRUE;
                                    
                                    
                                    $version =   '1.4.2';                    
                                }
                                
                            if(version_compare($version, '1.4.4', '<'))
                                {
                                    
                                    //copy over the new mu-loader version
                                    WPH_functions::copy_mu_loader( TRUE );
                                    
                                    //remove previous rules from .htaccess file to use the new block type
                                    if($this->wph->server_htaccess_config   === TRUE)
                                        {
                                            $home_path      = $this->wph->functions->get_home_path();
                                            $htaccess_file  = $home_path . DIRECTORY_SEPARATOR . '.htaccess';   
                                            
                                            //check if .htaccess file exists and is writable
                                            if(  $this->wph->functions->is_writable_htaccess_config_file( $htaccess_file ))
                                                {
                                                    $markers    =   array(
                                                                            'start' =>  '#START - WP Hide & Security Enhancer',
                                                                            'end'   =>  '#END - WP Hide & Security Enhancer'  
                                                                            );
                                                    $this->wph->functions->clean_with_markers( $htaccess_file, $markers);
                                                }
                                        }
                                    
                                    
                                    
                                    $_trigger_flush_rules   =   TRUE;
                                    
                                    
                                    $version =   '1.4.4';                    
                                }
                                
                                
                            if(version_compare($version, '1.4.4.2', '<'))
                                {
                                            
                                    $_trigger_flush_rules   =   TRUE;                                    
                                    
                                    $version =   '1.4.4.2';                    
                                }
                                
                            if(version_compare($version, '1.4.4.4', '<'))
                                {
                                            
                                    //copy over the new mu-loader version
                                    WPH_functions::copy_mu_loader( TRUE );
                                    $_trigger_flush_rules   =   TRUE;
                                    
                                    $version =   '1.4.4.4';                    
                                }
                                
                                
                            if(version_compare($version, '1.4.7', '<'))
                                {
                                            
                                    $_trigger_flush_rules   =   TRUE;                                    
                                    
                                    $version =   '1.4.7';                    
                                }
                                
                                
                            if(version_compare($version, '1.4.7.8', '<'))
                                {
                                    if(isset($this->wph->settings['module_settings']['remove_html_new_lines']) &&  $this->wph->settings['module_settings']['remove_html_new_lines']   ==  "yes")
                                        {
                                            $this->wph->settings['module_settings']['remove_html_new_lines']    =   'all';   
                                        }
                                                                        
                                    $version =   '1.4.7.8';
                                }
                                
                            if(version_compare($version, '1.4.8.3', '<'))
                                {
                                    //copy over the new mu-loader version
                                    WPH_functions::copy_mu_loader( TRUE );
                                                                        
                                    $version =   '1.4.8.3';
                                }
                                
                            if(version_compare($version, '1.5.6.6', '<'))
                                {
                                    $_trigger_flush_rules   =   TRUE;
                                                                        
                                    $version =   '1.5.6.6';
                                }
                                
                            if(version_compare($version, '1.5.8.8', '<'))
                                {
                                    //copy over the new mu-loader version
                                    WPH_functions::copy_mu_loader( TRUE );
                                                                        
                                    $version =   '1.5.8.8';
                                    
                                    //Attempt to remove the router/environment.php file 
                                    if ( file_exists ( WPH_PATH . '/router/environment.php') )
                                        @unlink( WPH_PATH . '/router/environment.php' );
                                    
                                }     
                            
                            
                            //check for triggered flush rules
                            if ( $_trigger_flush_rules  === TRUE )
                                {
                                    //on plugin inline code update
                                    if(isset($_GET['action'])   &&  $_GET['action']     ==  'activate-plugin')
                                        add_action('shutdown',        array($this,    'flush_rules') , -1);
                                        else
                                        add_action('wp_loaded',        array($this,    'flush_rules') , -1);
                                        
                                }    
                            
                            
                            if ( $_set_static_environment_file === TRUE )        
                                {
                                    $this->wph->set_static_environment_file();
                                }
                            
                            
                            //clear teh site cache
                            if ( $_trigger_site_cache_flush === TRUE )
                                {
                                    $this->wph->functions->site_cache_clear();
                                }

                            //save the last code version
                            $this->wph->settings['version'] =   WPH_CORE_VERSION;
                            $this->wph->functions->update_settings($this->wph->settings);
                                    
                        }
                    
                     
                }
            
 
            /**
            * Regenerate rewrite rules
            * 
            */
            function flush_rules()
                {
                    /** WordPress Misc Administration API */
                    require_once(ABSPATH . 'wp-admin/includes/misc.php');
                    
                    /** WordPress Administration File API */
                    require_once(ABSPATH . 'wp-admin/includes/file.php');
                    
                    flush_rewrite_rules();
                       
                }
                
        }
        
        
?>