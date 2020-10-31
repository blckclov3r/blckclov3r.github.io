<?php


    class WPH_conflict_handle_edd
        {
                        
            static function init()
                {
                    if( !   self::is_plugin_active())
                        return false;
                        
                    add_filter( 'edd_start_session', array( 'WPH_conflict_handle_edd' , 'edd_start_session' ), -1 );   
                }                        
            
            static function is_plugin_active()
                {
                    
                    include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
                    
                    if(is_plugin_active( 'easy-digital-downloads/easy-digital-downloads.php' ))
                        return true;
                        else
                        return false;
                }
            
            static function edd_start_session( $start_session )
                {
                    
                    global $wph;
                    
                    $admin_url     =   $wph->functions->get_module_item_setting('admin_url');
                    if (empty(  $admin_url ))
                        return $start_session;  
                        
                    $start_session = true;

                    if( ! empty( $_SERVER[ 'REQUEST_URI' ] ) ) {

                        $blacklist = EDD()->session->get_blacklist();
                        $uri       = ltrim( $_SERVER[ 'REQUEST_URI' ], '/' );
                        $uri       = untrailingslashit( $uri );

                        if( in_array( $uri, $blacklist ) ) {
                            $start_session = false;
                        }

                        if( false !== strpos( $uri, 'feed=' ) ) {
                            $start_session = false;
                        }

                        if( is_admin() && false === strpos( $uri, $admin_url . '/admin-ajax.php' ) ) {
                            // We do not want to start sessions in the admin unless we're processing an ajax request
                            $start_session = false;
                        }

                        if( false !== strpos( $uri, 'wp_scrape_key' ) ) {
                            // Starting sessions while saving the file editor can break the save process, so don't start
                            $start_session = false;
                        }

                    }
                    
                    return $start_session;
                        
                }
   
        }
        
    WPH_conflict_handle_edd::init();


?>