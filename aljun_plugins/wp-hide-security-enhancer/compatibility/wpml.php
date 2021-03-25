<?php


    class WPH_conflict_handle_wpml
        {
                        
            static function init()
                {
                    add_action('plugins_loaded',        array('WPH_conflict_handle_wpml', 'run') , -1);    
                }                        
            
            static function is_plugin_active()
                {
                    
                    include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
                    
                    if(is_plugin_active( 'sitepress-multilingual-cms/sitepress.php' ))
                        return TRUE;
                        else
                        return FALSE;
                }
            
            static public function run()
                {   
                    if( !   self::is_plugin_active())
                        return FALSE;
                    
                    global $wph;
                                        
                    add_action('wp-hide/ob_start_callback', array('WPH_conflict_handle_wpml', 'wpml_domain_per_language'), 999);
                               
                }
            
            /**
            * Fix the replacement domain when using different domains for each WPML language
            *     
            * @param mixed $buffer
            */
            static public function wpml_domain_per_language( $buffer)
                {
                    
                    global $sitepress, $wph;
    
                    if (!$sitepress) 
                        {
                            return $buffer;
                        }
                    
                    $current_lang       = apply_filters( 'wpml_current_language', NULL );
                    $default_lang       = apply_filters('wpml_default_language', NULL );
                    $domain_per_lang    = $sitepress->get_setting( 'language_negotiation_type' ) == WPML_LANGUAGE_NEGOTIATION_TYPE_DOMAIN ? true : false;
                    if ($current_lang == $default_lang || !$domain_per_lang) 
                        {
                            return $buffer;
                        }
                    
                    $replacement_list       = $wph->functions->get_replacement_list();
                    $home_url               = home_url();
                    $default_home_url       = $sitepress->convert_url( $sitepress->get_wp_api()->get_home_url(), $default_lang );
                    $new_replacement_list   = array();
                    
                    if (!empty($replacement_list) && is_array($replacement_list)) 
                        {
                            foreach ($replacement_list as $old_url => $new_url) 
                                {
                                    $old_url = str_ireplace($default_home_url, $home_url, $old_url);
                                    $new_url = str_ireplace($default_home_url, $home_url, $new_url);
                                    $new_replacement_list[$old_url] = $new_url;
                                }
                            
                            return $wph->functions->content_urls_replacement($buffer,  $new_replacement_list );    
                        } 
                    
                    return $buffer;    
                }
  
                            
        }
        
        
        
?>