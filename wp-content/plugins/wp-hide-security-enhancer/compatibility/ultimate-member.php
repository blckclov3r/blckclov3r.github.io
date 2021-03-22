<?php

/**
* Compatibility for Plugin Name: Ultimate Member
* Compatibility checked on Version: 2.1.5
*/

    class WPH_conflict_handle_ultimate_member
        {
                        
            static function init()
                {
                    if( !   self::is_plugin_active() )
                        return FALSE;
                    
                    if ( isset ( $_POST['action'] )    &&  $_POST['action']   ==  'um_resize_image' )
                        self::_reverse_urls();
                    
                }                        
            
            static function is_plugin_active()
                {
                    
                    include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
                    
                    if( is_plugin_active( 'ultimate-member/ultimate-member.php' ) )
                        return TRUE;
                        else
                        return FALSE;
                }
                       
            static function _reverse_urls( )
                {
                                            
                    global $wph;
                    
                    $src    =   $_POST['src'];
                    
                    $src    =   $wph->functions->content_urls_replacement( $src,  array_flip ( $wph->functions->get_replacement_list() ) );
                    
                    $_POST['src']   =   $src;                       
                }

           
        }
        
        
    WPH_conflict_handle_ultimate_member::init();


?>