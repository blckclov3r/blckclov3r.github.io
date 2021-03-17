<?php

    /**
    * Compatibility for Plugin Name: Shield Security
    * Compatibility checked on Version: 9.2.1
    */

    if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
    
    use FernleafSystems\Wordpress\Plugin\Shield;
    use FernleafSystems\Wordpress\Plugin\Shield\Modules\LoginGuard;
    use FernleafSystems\Wordpress\Services\Services;
    
    class WPH_conflict_handle_wp_simple_firewall
        {
            
            static public function custom_login_check()
                {   
                    global $wph;
                    
                    if( !   self::is_plugin_active()    || defined('WPH_conflict_handle_wp_simple_firewall') )
                        return FALSE;
                        
                    //mark as being loaded
                    define('WPH_conflict_handle_wp_simple_firewall', TRUE );
                    
                    add_action('plugins_loaded',    array( 'WPH_conflict_handle_wp_simple_firewall', 'on_plugins_loaded' ), 5);
                    
                }
            
            static function is_plugin_active()
                {
                    
                    include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
                    
                    if(is_plugin_active( 'wp-simple-firewall/icwp-wpsf.php' ))
                        return TRUE;
                        else
                        return FALSE;
    
                }
                
            
            static public function on_plugins_loaded()
                {
                    
                    if ( ! class_exists( 'FernleafSystems\Wordpress\Plugin\Shield\Controller\Controller' ) )
                        return;
                            
                    $oICWP_Wpsf_Controller =   Shield\Controller\Controller::GetInstance( WP_PLUGIN_DIR . '/wp-simple-firewall/src/login_protect.php' );
                                                      
                    //check if custom login is active
                    if( method_exists( $oICWP_Wpsf_Controller->oFeatureHandlerLoginProtect, 'isCustomLoginPathEnabled')  &&  $oICWP_Wpsf_Controller->oFeatureHandlerLoginProtect->isCustomLoginPathEnabled())
                        return FALSE;
                        else
                    //version 10.0.3 and later 
                    if( method_exists( $oICWP_Wpsf_Controller->oFeatureHandlerLoginProtect, 'getCustomLoginPath')  &&  $oICWP_Wpsf_Controller->oFeatureHandlerLoginProtect->getCustomLoginPath() != '' )
                        return FALSE;
                    
                    global $wph;
                        
                    $new_login  =   $wph->functions->get_module_item_setting('new_wp_login_php');
                    if ( empty ( $new_login ) )
                        return FALSE;
                    
                    add_action('admin_notices',                                         array( 'WPH_conflict_handle_wp_simple_firewall', 'admin_notice' ));   
                    
                }
                
                
            static function admin_notice()
                {
                    global $current_user ;
                    
                    $user_id = $current_user->ID;
                    
                    //only for admins
                    if (    !   current_user_can( 'install_plugins' ) )
                        return;
                                            
                    ?>
                    <div id="WPH_conflict_handle_wp_simple_firewall_login" class="error notice">
                        <p>
                            <?php _e('<b>Conflict notice</b>: <b>The Security Firewall</b> - Login Protection -> Hide Login -> use similar functionality as to WP Hide plugin - Admin Login Url change.  ', 'wp-hide-security-enhancer'); ?>
                        </p>
                    </div>
                    
                    <?php
                    
                }

                
        }



?>