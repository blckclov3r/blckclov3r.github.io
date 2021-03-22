<?php

    if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
    
    class WPH_module_general_feed extends WPH_module_component
        {
            function get_component_title()
                {
                    return "Feed";
                }
                                        
            function get_module_settings()
                {
                    $this->module_settings[]                  =   array(
                                                                    'id'            =>  'remove_feed_links',
                                                                    'label'         =>  __('Remove feed|rdf|rss|rss2|atom links',    'wp-hide-security-enhancer'),
                                                                    'description'   =>  __('Remove feed|rdf|rss|rss2|atom links within head. Also block such content functionality.',  'wp-hide-security-enhancer'),
                                                                    
                                                                    'help'          =>  array(
                                                                                                'title'                     =>  __('Help',    'wp-hide-security-enhancer') . ' - ' . __('Remove feed|rdf|rss|rss2|atom links',    'wp-hide-security-enhancer'),
                                                                                                'description'               =>  __("A feed is a function of special software that allows feedreaders to access a site, automatically looking for new content and then posting the information about new content and updates to another site. This provides a way for users to keep up with the latest and hottest information posted on different blogging sites.",    'wp-hide-security-enhancer') . 
                                                                                                                                "<br />" . __("There are several different kinds of feeds, read by different feedreaders. Some feeds include RSS (alternately defined as 'Rich Site Summary' or 'Really Simple Syndication'), Atom or RDF files.",    'wp-hide-security-enhancer') .
                                                                                                                                "<br /><br/>" . __("Sample tag:",    'wp-hide-security-enhancer') .  
                                                                                                                                "<br /><code>&lt;link rel=&quot;alternate&quot; type=&quot;application/rss+xml&quot; title=&quot;WP Hide Demo Feed&quot; href=&quot;http://-domain-name-/feed/&quot; /&gt;</code>
                                                                                                                                <br /><br/>" . __("Set this option to Yes also disable the feed service.",   'wp-hide-security-enhancer'),
                                                                                                'option_documentation_url'  =>  'https://www.wp-hide.com/documentation/general-html-feed/'
                                                                                                ),
                                                                    
                                                                    'input_type'    =>  'radio',
                                                                    'options'       =>  array(
                                                                                                'no'        =>  __('No',     'wp-hide-security-enhancer'),
                                                                                                'yes'       =>  __('Yes',    'wp-hide-security-enhancer'),
                                                                                                ),
                                                                    'default_value' =>  'no',
                                                                    
                                                                    'sanitize_type' =>  array('sanitize_title', 'strtolower')
                                                                    
                                                                    ); 
                  
                                                                    
                    return $this->module_settings;   
                }
                
                
                
            function _init_remove_feed_links($saved_field_data)
                {
                    if(empty($saved_field_data) ||  $saved_field_data   ==  'no')
                        return FALSE;
                        
                    remove_action('wp_head',    'feed_links',          2);
                    remove_action('wp_head',    'feed_links_extra',    3); 
 
                    
                }
                
                
            function _callback_saved_remove_feed_links($saved_field_data)
                {
                    
                    if(empty($saved_field_data) ||  $saved_field_data   ==  'no')
                        return FALSE;
                        
                    $processing_response    =   array();
                                                         
                    $rewrite                            =  '';
                    
                    $rewrite_to     =   $this->wph->functions->get_rewrite_to_base( 'index.php', TRUE, FALSE, 'site_path' );
                    
                    if($this->wph->server_htaccess_config   === TRUE)                               
                        {
                            $rewrite    .=      "\nRewriteCond %{REQUEST_URI} ([^/]+)/(feed|rdf|rss|rss2|atom)/?$  [OR]"
                                            .   "\nRewriteCond %{REQUEST_URI} ^/(feed|rdf|rss|rss2|atom)/?$"
                                            .   "\nRewriteRule . ". $rewrite_to ."?wph-throw-404 [L]";
                            
                        }
                        
                    if($this->wph->server_web_config   === TRUE)
                        {
                            //+++ To be implemented   
                            
                        }
                    
                    $processing_response['rewrite'] =   $rewrite;                                       
                                      
                    return  $processing_response;
                    
                }    
            
        }

?>