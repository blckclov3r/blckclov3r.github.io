<?php


    class WPH_conflict_handle_woocommerce
        {
                        
            static function init()
                {
                    add_action('plugins_loaded',        array('WPH_conflict_handle_woocommerce', 'run') , -1);  
                    
                    //check for block
                    if( isset( $_GET['wph-throw-404'] )   )
                        add_filter ('woocommerce_is_rest_api_request', '__return_false' );  
                        
                        
                    add_filter('admin_url',                                     array( 'WPH_conflict_handle_woocommerce', 'admin_url'),      20, 3);
                    
                }                        
            
            static function is_plugin_active()
                {
                    
                    include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
                    
                    if(is_plugin_active( 'woocommerce/woocommerce.php' ))
                        return TRUE;
                        else
                        return FALSE;
                }
            
            static public function run()
                {   
                    if( !   self::is_plugin_active())
                        return FALSE;
                    
                    global $wph;
                                        
                    add_action('woocommerce_product_get_downloads', array('WPH_conflict_handle_woocommerce', 'woocommerce_product_get_downloads'), 99, 2);
                               
                }
                
            static function woocommerce_product_get_downloads( $data, $product)
                {
                    
                    //only when downloading a file
                    if( ! isset($_GET['download_file']) ||  ! isset($_GET['key'])   )
                        return $data;                    
                    
                    if( !is_array( $data )  ||  count( $data ) < 1)
                        return $data;
                    
                    global $wph;
                    
                    //if no change on the upload slug, return as is
                    $new_upload_path    =   $wph->functions->get_module_item_setting('new_upload_path');
                    if( empty ( $new_upload_path ) )
                        return $data;
                        
                    foreach ( $data as  $key    =>  $product_download )
                        {
                            $file  =   $product_download->get_file();
                            
                            $replace   =   trailingslashit ( site_url() ) .  $new_upload_path;
                            $replace   =   str_replace(array("http:", "https:") , "", $replace );
                            
                            $replace_with   =   $wph->default_variables['url'] . $wph->default_variables['uploads_directory'];
                            $replace_with   =   str_replace(array("http:", "https:") , "", $replace_with );
                            
                            $file           =   str_replace($replace, $replace_with , $file);
                            
                            //attempt to change back the url
                            $product_download->set_file( $file );
                            
                            $data[$key] =   $product_download;
                            
                        }
                    
                       
                    return $data;    
                }
                
                
            /**
            * Fix wrong admin url
            * 
            * @param mixed $url
            * @param mixed $path
            * @param mixed $blog_id
            */
            static function admin_url( $url, $path, $blog_id )
                {
                    
                    global $wph;
                    
                    $admin_url     =   $wph->functions->get_module_item_setting( 'admin_url' );
                    
                    if ( empty ( $admin_url ) )
                        return $url;

                    if ( strpos ( $url, '/wp-admin/' . $admin_url .'/' ) !==    FALSE )
                        $url    =   str_replace( '/' . $admin_url . '/', '/', $url);   
                        
                    return $url;   
                    
                }
  
                            
        }
        
        
        
?>