<?php


    class WPH_conflict_wps_hide_login
        {
                        
            static function init()
                {
                    if( !   self::is_plugin_active())
                        return FALSE;
                        
                    add_action( 'wp-hide/admin_notices',    array( 'WPH_conflict_webarx', 'login_conflicts') );
                    
                    
                }                        
            
            static function is_plugin_active()
                {
                    
                    include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
                    
                    if(is_plugin_active( 'wps-hide-login/wps-hide-login.php' ))
                        return TRUE;
                        else
                        return FALSE;
                }

            static function login_conflicts( )
                {
                    global $wph;
                    
                    $new_login  =   $wph->functions->get_module_item_setting('new_wp_login_php');
                    if ( empty ( $new_login ) )
                        return;
                    
                    if ( get_option( 'webarx_mv_wp_login' ) && get_option( 'webarx_rename_wp_login' ) ) 
                        {
                            echo "<div class='error'><p><b>". __("WP Hide Conflict Notice.", 'wp-hide-security-enhancer') . "</b> ". __("You use another plugin (WebARX) to change the default wp-login.php. To avoid conflicts, activate the option within a single code.", 'wp-hide-security-enhancer') .'</p></div>';    
                        }
                        
                }
            
                            
        }


?>