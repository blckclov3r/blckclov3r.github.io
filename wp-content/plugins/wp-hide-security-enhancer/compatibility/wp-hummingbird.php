<?php


    class WPH_conflict_handle_hummingbird
        {
                        
            static function init()
                {
                    add_action('plugins_loaded',        array('WPH_conflict_handle_hummingbird', 'run') , -1);    
                }                        
            
            static function is_plugin_active()
                {
                    
                    include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
                    
                    if( is_plugin_active( 'wp-hummingbird/wp-hummingbird.php' ) ||  is_plugin_active( 'hummingbird-performance/wp-hummingbird.php' ))
                        return TRUE;
                        else
                        return FALSE;
                }
            
            static public function run()
                {   
                    if( !   self::is_plugin_active())
                        return FALSE;
                                        
                    add_filter( 'wphb_minify_file_content', array( 'WPH_conflict_handle_hummingbird', 'wphb_minify_file_content' ) );
                               
                }
                
            static public function wphb_minify_file_content( $content )
                {
                    
                    global $wph;
                    
                    $content         =  $wph->functions->content_urls_replacement( $content, $wph->functions->get_replacement_list() );
                    
                    return $content;   
                }

                            
        }


?>