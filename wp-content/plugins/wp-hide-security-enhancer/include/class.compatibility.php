<?php

     if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
     
     class WPH_Compatibility
        {
            
            var $wph                            =   '';
            var $functions                      =   '';
         
            function __construct()
                {
                    global $wph;

                    $this->wph          =   $wph;
                    $this->functions    =   new WPH_functions();
                    
                    $this->init();
                    
                }
                
                
                
            function init()
                {
                    
                    /**
                    * General
                    */
                    include_once(WPH_PATH . 'compatibility/general.php');
 
                    include_once(WPH_PATH . 'compatibility/w3-cache.php');
                    WPH_conflict_handle_w3_cache::init();

                    include_once(WPH_PATH . 'compatibility/super-cache.php');
                    WPH_conflict_handle_super_cache::init(); 

                    include_once(WPH_PATH . 'compatibility/buddypress.php');
                    WPH_conflict_handle_BuddyPress::init();                    

                    include_once(WPH_PATH . 'compatibility/wp-fastest-cache.php');
                    WPH_conflict_handle_wp_fastest_cache::init();

                    include_once(WPH_PATH . 'compatibility/woocommerce.php');
                    WPH_conflict_handle_woocommerce::init();

                    include_once(WPH_PATH . 'compatibility/wpml.php');
                    WPH_conflict_handle_wpml::init();   

                    include_once(WPH_PATH . 'compatibility/woo-global-cart.php');
                    WPH_conflict_handle_wgc::init();

                    include_once(WPH_PATH . 'compatibility/shortpixel-adaptive-images.php');
                    WPH_conflict_shortpixel_ai::init();

                    include_once(WPH_PATH . 'compatibility/webarx.php');
                    WPH_conflict_webarx::init();

                    include_once(WPH_PATH . 'compatibility/wps-hide-login.php');
                    WPH_conflict_wps_hide_login::init();

                    include_once(WPH_PATH . 'compatibility/wp-hummingbird.php');
                    WPH_conflict_handle_hummingbird::init();

                    include_once(WPH_PATH . 'compatibility/wp-rocket.php');
                    include_once(WPH_PATH . 'compatibility/autoptimize.php');
                    include_once(WPH_PATH . 'compatibility/easy-digital-downloads.php');
                    include_once(WPH_PATH . 'compatibility/fusion-builder.php');
                    include_once(WPH_PATH . 'compatibility/fusion-builder.php');
                    include_once(WPH_PATH . 'compatibility/cache-enabler.php');
                    include_once(WPH_PATH . 'compatibility/wp-smush.php');
                    include_once(WPH_PATH . 'compatibility/shortpixel-image-optimiser.php');
                    include_once(WPH_PATH . 'compatibility/fluentform.php');
                    include_once(WPH_PATH . 'compatibility/ultimate-member.php');                    
                    include_once(WPH_PATH . 'compatibility/swift-performance.php'); 
                    include_once(WPH_PATH . 'compatibility/fast-velocity-minfy.php');
                    include_once(WPH_PATH . 'compatibility/litespeed-cache.php');
                    include_once(WPH_PATH . 'compatibility/wp-speed-of-light.php');
                    include_once(WPH_PATH . 'compatibility/wp-job-manager.php');
                    include_once(WPH_PATH . 'compatibility/jobboardwp.php');
                    include_once(WPH_PATH . 'compatibility/wp-optimize.php');
                    include_once(WPH_PATH . 'compatibility/hyper-cache.php');
                    include_once(WPH_PATH . 'compatibility/wp-asset-clean-up.php');
                    include_once(WPH_PATH . 'compatibility/wepos.php');
                    include_once(WPH_PATH . 'compatibility/oxygen.php');
                    include_once(WPH_PATH . 'compatibility/translatepress-multilingual.php');
                    
                    /**
                    * Themes
                    */
                    
                    $theme  =   wp_get_theme();
                    
                    if( ! $theme instanceof WP_Theme )
                        return FALSE;
                        
                    $compatibility_themes   =   array(
                                                        'avada'             =>  'avada.php',
                                                        'divi'              =>  'divi.php',
                                                        'woodmart'          =>  'woodmart.php',
                                                        'buddyboss-theme'   =>  'buddyboss-theme.php',
                                                        );
                    
                    if (isset( $theme->template ) )
                        {
                            
                            foreach ( $compatibility_themes as  $theme_slug     =>  $compatibility_file )
                                {
                                    if ( strtolower( $theme->template ) == $theme_slug  ||   strtolower( $theme->name ) == $theme_slug )
                                        {
                                            include_once(WPH_PATH . 'compatibility/themes/' .   $compatibility_file );    
                                        }
                                }
                              
                        }
      
                          
                    do_action('wph/compatibility/init');
                    
                }
            
    
                
        }   
            



?>