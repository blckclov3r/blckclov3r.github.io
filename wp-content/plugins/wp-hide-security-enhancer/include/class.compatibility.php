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
                    
                       
                    //w3-cache compatibility handle
                    include_once(WPH_PATH . 'compatibility/w3-cache.php');
                    WPH_conflict_handle_w3_cache::init();
                    
                    //super-cache compatibility handle
                    include_once(WPH_PATH . 'compatibility/super-cache.php');
                    WPH_conflict_handle_super_cache::init(); 
                    
                    //BuddyPress handle
                    include_once(WPH_PATH . 'compatibility/buddypress.php');
                    WPH_conflict_handle_BuddyPress::init();                    
                    
                    
                    //WP Fastest Cache handle
                    include_once(WPH_PATH . 'compatibility/wp-fastest-cache.php');
                    WPH_conflict_handle_wp_fastest_cache::init();
                    
                                                            
                    //WooCommerce
                    include_once(WPH_PATH . 'compatibility/woocommerce.php');
                    WPH_conflict_handle_woocommerce::init();
                    
                    //WPML
                    include_once(WPH_PATH . 'compatibility/wpml.php');
                    WPH_conflict_handle_wpml::init();   
                    
                    //WooGlobalCart
                    include_once(WPH_PATH . 'compatibility/woo-global-cart.php');
                    WPH_conflict_handle_wgc::init();
                    
                    //ShortPixel Adaptive Images
                    include_once(WPH_PATH . 'compatibility/shortpixel-adaptive-images.php');
                    WPH_conflict_shortpixel_ai::init();
                    
                    //WebArx
                    include_once(WPH_PATH . 'compatibility/webarx.php');
                    WPH_conflict_webarx::init();
                    
                    //WPS Hide Login
                    include_once(WPH_PATH . 'compatibility/wps-hide-login.php');
                    WPH_conflict_wps_hide_login::init();
                    
                    //Hummingbird
                    include_once(WPH_PATH . 'compatibility/wp-hummingbird.php');
                    WPH_conflict_handle_hummingbird::init();
                    
                    //WP Rocket
                    include_once(WPH_PATH . 'compatibility/wp-rocket.php');
                    
                    //Autoptimize
                    include_once(WPH_PATH . 'compatibility/autoptimize.php');
                    
                    //Easy Digital Downloads
                    include_once(WPH_PATH . 'compatibility/easy-digital-downloads.php');
                    
                    //Fusion Builder
                    include_once(WPH_PATH . 'compatibility/fusion-builder.php');
                    
                    //Elementor
                    include_once(WPH_PATH . 'compatibility/fusion-builder.php');
                    
                    //Cache Enabler
                    include_once(WPH_PATH . 'compatibility/cache-enabler.php');
                    
                    //WP Smush
                    include_once(WPH_PATH . 'compatibility/wp-smush.php');
                    
                    //ShortCode Image Optimizer
                    include_once(WPH_PATH . 'compatibility/shortpixel-image-optimiser.php');

                    //Fluentform
                    include_once(WPH_PATH . 'compatibility/fluentform.php');
                    
                    //Ultimate Member
                    include_once(WPH_PATH . 'compatibility/ultimate-member.php');                    
                    
                    //Swift Performance
                    include_once(WPH_PATH . 'compatibility/swift-performance.php'); 
                    
                    //Fast Velocity Minify
                    include_once(WPH_PATH . 'compatibility/fast-velocity-minfy.php');
                    
                    //LiteSpeed Cache
                    include_once(WPH_PATH . 'compatibility/litespeed-cache.php');
                    
                    //WP Speed of Light
                    include_once(WPH_PATH . 'compatibility/wp-speed-of-light.php');
                    
                    //WP Job Manager
                    include_once(WPH_PATH . 'compatibility/wp-job-manager.php');
                    
                    //WP Job Manager
                    include_once(WPH_PATH . 'compatibility/jobboardwp.php');
                    
                    //WP-Optimize - Clean, Compress, Cache
                    include_once(WPH_PATH . 'compatibility/wp-optimize.php');
                    
                    //Hyper Cache
                    include_once(WPH_PATH . 'compatibility/hyper-cache.php');
                    
                    include_once(WPH_PATH . 'compatibility/wp-asset-clean-up.php');
                    
                    include_once(WPH_PATH . 'compatibility/wepos.php');
                    
                    include_once(WPH_PATH . 'compatibility/oxygen.php');
                    
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