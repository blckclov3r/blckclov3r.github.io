<?php

    /**
    * Compatibility for Plugin Name: WP Job Manager
    * Compatibility checked on Version: 1.34.2 
    */
    
    if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

    class WPH_conflict_handle_job_manager
        {
            var $wph;
                            
            function __construct()
                {
                    if( ! $this->is_plugin_active( ))
                        return FALSE;
                        
                    global $wph;
                    
                    $this->wph  =   $wph;
                    
                    add_filter('upload_dir',            array( $this, 'upload_dir' ), 999);

                }                        
            
            function is_plugin_active( )
                {
                    
                    include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
                    
                    if( is_plugin_active( 'wp-job-manager/wp-job-manager.php' ) )
                        return TRUE;
                        else
                        return FALSE;
                }
                
            
            /**
            * Process the upload_dir data
            * 
            * @param mixed $data
            */
            function upload_dir( $data )
                {
                    
                    if (  ! $this->check_backtrace_for_caller( array ( array ( 'create_attachment', 'WP_Job_Manager_Form_Submit_Job') , array ('validate_fields', 'WP_Job_Manager_Form_Submit_Job') ) ) )
                        return $data;
                    
                    global $wph;

                    $new_upload_path        =   $wph->functions->untrailingslashit_all(    $wph->functions->get_module_item_setting('new_upload_path')  );
                    $new_content_path       =   $wph->functions->untrailingslashit_all(    $wph->functions->get_module_item_setting('new_content_path')  );
                    
                    if  ( empty ( $new_upload_path )    &&  empty ( $new_content_path ) )
                        return $data; 
                    
                    if  (  ! empty ( $new_upload_path ) )
                        {
                            $new_url                =   trailingslashit(    home_url()  )   . $new_upload_path;
                            
                            if ( is_multisite() && ! ( is_main_network() && is_main_site() && defined( 'MULTISITE' ) ) )
                                {
                                    $ms_dir = '/sites/' . get_current_blog_id();
                                    $new_url    .=  $ms_dir;
                                }   
                        }
                        else
                        {
                            $new_url                =   trailingslashit(    home_url()  )   . str_replace( '/wp-content' , $new_content_path, $wph->default_variables['uploads_directory'] );
                        }
                    
                    $data['url']            =   str_replace($data['baseurl'], $new_url, $data['url']);
                    $data['baseurl']        =   $new_url;
                    
                    return $data;   
                }
                
                
            function check_backtrace_for_caller( $groups )
                {
                    $backtrace  =   debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
                    foreach ( $groups   as $group )
                        {
                            $function_name      =   $group[0]; 
                            $class_name         =   isset ( $group[1] ) ?   $group[1]   :   FALSE;
                            
                            foreach ( $backtrace as  $block )
                                {
                                    if ( $block['function']    ==  $function_name )
                                        {
                                            if ( $class_name    ===  FALSE )
                                                return TRUE;
                                            
                                            if ( $class_name    !=  FALSE   &&  !isset( $block['class'] ) )
                                                return FALSE;
                                                
                                            if ( $block['class']    ==  $class_name )
                                                return TRUE;
                                            
                                            return FALSE;
                                            
                                        }
                                
                                }
                        }
                        
                    return FALSE;
                }
                                        
        }


        new WPH_conflict_handle_job_manager();

?>