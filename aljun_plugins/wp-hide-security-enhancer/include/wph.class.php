<?php

    if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
    
    class WPH
        {
            
            var $default_variables          =   array();
            var $templates_data             =   array();
            var $urls_replacement           =   array();
            
            var $server_htaccess_config     =   FALSE;
            var $server_web_config          =   FALSE;
            var $server_nginx_config        =   FALSE;
            
            var $modules                    =   array();
            
            var $settings;
            
            var $functions;
            
            var $disable_filters            =   FALSE;
            var $disable_ob_start_callback  =   FALSE;
            var $custom_permalinks_applied  =   FALSE;
            
            var $doing_interface_save       =   FALSE;
            var $doing_reset_settings       =   FALSE;
            
            var $enironment_checked         =   FALSE;
            
            var $uninstall                  =   FALSE;
            
            var $is_initialised             =   FALSE;
            
            var $maintenances               =   array();
               
            function __construct()
                {
                    $this->functions    =   new WPH_functions();
                      
                    $plugin_data    =   $this->functions->get_plugin_data( WPH_PATH . '/wp-hide.php', $markup = true, $translate = true );
                    
                    define('WPH_CORE_VERSION',              $plugin_data['Version']);
                    
                    if(!defined('WPH_CACHE_PATH'))
                        define('WPH_CACHE_PATH',                WP_CONTENT_DIR . '/cache/wph/' );
                        
                    if(!defined('WPH_URL'))
                        define('WPH_URL',               plugins_url() . '/wp-hide-security-enhancer' );
                        
                }
                
                
            function init()
                {
                            
                    $this->settings     =   $this->functions->get_settings();
                    
                    //set the urls_replacement priority blocks
                    $this->urls_replacement['high']     =   array();
                    $this->urls_replacement['normal']   =   array();
                    $this->urls_replacement['low']      =   array();
                    
                    $this->get_default_variables();
                    
                    //set whatever the server use htaccess or web.config configuration file
                    $this->functions->set_server_type();
                            
                    //check for interface submit
                    if(is_admin()   && isset($_POST['wph-interface-nonce']))
                        {
                            $this->doing_interface_save =   TRUE;
                        }
                        
                                            
                    //check if WPEngine
                    if (    getenv('IS_WPE')    ==  "1"   ||  getenv('IS_WPE_SNAPSHOT')    == "1" ) 
                        $this->disable_filters  =   TRUE;
                    
                    //check for permalink issues
                    $this->custom_permalinks_applied   =   $this->functions->rewrite_rules_applied();
                    
                    $this->_load_modules();
                    
                    $this->is_initialised       =   TRUE;
                    do_action('wp-hide/is_initialised');

                    
                    //check for recovery link run
                    if(isset($_GET['wph-recovery']))
                        $this->functions->do_recovery();                    
                    
                    //check for reset setings
                    if(is_admin()   && isset($_POST['reset-settings']))
                        {
                            $this->doing_reset_settings =   TRUE;
                            $this->disable_filters      =   TRUE;
                        }
                    
                    $this->_modules_components_run();
                    
                    //handle the compatibility
                    $this->plugins_themes_compatibility();
                    
                    $this->add_default_replacements();
                    
                    //check for plugin update
                    $this->update();
                    
                    
                    /**
                    * Filters
                    */
                    add_action( 'activated_plugin', array($this, 'activated_plugin'), 999, 2 );
                    
                      
                    //change any links within email message
                    add_filter('wp_mail',               array($this,    'apply_for_wp_mail') , 999);
                    
                    //process redirects
                    add_action('wp_redirect',           array($this,    'wp_redirect') , 999, 2);
                    //hijack a redirect on permalink change
                    add_action('admin_head',            array($this,    'permalink_change_redirect') , 999, 2);
                    
                    add_action('logout_redirect',       array($this,    'logout_redirect') , 999, 3);
                                                            
                    //check if force 404 error
                    add_action('init',                  array($this,    'check_for_404'));
                                        
                    add_action('admin_menu',            array($this,    'admin_menus'));
                    add_action('admin_init',            array($this,    'admin_init'), 11);
                    
                    //make sure to clear cache files on certain actions
                    add_action("after_switch_theme",    array($this->functions,    'cache_clear'));
                    
                                        
                    //rebuild and change uppon settings modified
                    add_action('wph/settings_changed',  array($this,    'settings_changed'));
                    
                    //create the static file which contain different environment variables which will be used on router
                    add_action('wph/settings_changed',  array($this,    'set_static_environment_file'), 999);
                    
                    //create the static file which contain different environment variables which will be used on router
                    add_action('admin_init',            array($this,    'environment_check'), 999);
                    
                    add_action('admin_init',                        array($this,    'mu_loader_check'), 999);
                                                                      
                    //apache
                    //add_filter('mod_rewrite_rules',         array($this,    'mod_rewrite_rules'), 999);
   
                    if($this->server_htaccess_config    === TRUE)                    
                        add_filter('flush_rewrite_rules_hard',      array($this,    'flush_rewrite_rules_hard'), 999);
                    
                    //IIS7 server
                    add_filter('iis7_url_rewrite_rules',            array($this,    'iis7_url_rewrite_rules'), 999);
                                           
                    //on switch theme
                    add_action('switch_theme',                      array($this,    'switch_theme'));
                    
                    //admin notices
                    add_action( 'admin_notices',                    array(&$this,   'admin_notices'));
                    add_action( 'network_admin_notices',            array(&$this,   'admin_notices'));
                    
                    //ensure the media urls are being saved using default WordPress urls
                    add_action( 'save_post',                        array($this,    'save_post'), 999 );
                    //ensure meta data is being saved using default WordPress urls
                    add_action( 'update_post_metadata',             array($this,    'update_post_metadata'), 999, 5 );
                    //revert any urls back to original before save
                    add_filter( 'pre_update_option',                array($this,    'pre_update_option'), 99, 3);
                    
                    //restart the buffering if already outputed. This is usefull for plugin / theme update iframe
                    add_action('admin_print_footer_scripts',        array($this, 'admin_print_footer_scripts'), -1);
                    
                    //prevent the buffer processing if not filterable available
                    add_filter( 'wp-hide/ignore_ob_start_callback', array($this, 'ignore_ob_start_callback'), 999 );
                    
                    add_filter( 'attachment_url_to_postid',         array ( $this, 'attachment_url_to_postid' ) , 999, 2 );
                                        
                }
            
            
            /**
            * Update wrapper
            * 
            */
            function update()
                {
                    
                    //check for update from older version
                    include_once(WPH_PATH . '/include/update.class.php');
                    new WPH_update();   
                    
                }
            
            
            /**
            * Load modules
            *      
            */
            function _load_modules()
                {
                    
                    $module_files   =   glob(WPH_PATH . "/modules/module-*.php");

                    foreach ($module_files as $filename)
                        {
                            $path_parts = pathinfo($filename);
                                                        
                            include_once(WPH_PATH . '/modules/' .   $path_parts['basename']);
                            
                            $module_name = str_replace('module-' , '', $path_parts['filename']);
                            $module_class_name      =   'WPH_module_'   .   $module_name;
                            $module                 =   new $module_class_name;
                            
                            //action available for mu-plugins
                            do_action('wp-hide/loaded_module', $module);
                            
                            $interface_menu_data    =   $module->get_interface_menu_data();
                            $menu_position          =   $interface_menu_data['menu_position'];
                            
                            $this->modules[$menu_position]        =   $module;

                        }
                        
                    //sort the modules array
                    ksort($this->modules);
                    
                    //filter available for mu-plugins 
                    $this->modules  =   apply_filters('wp-hide/loaded_modules', $this->modules);

           
                }
                
            
            /**
            * Runt the components of loaded modules
            * 
            */
            function _modules_components_run()
                {
                    foreach($this->modules  as  $module)
                        {
                            //process the module fields
                            $module_settings  =   $this->functions->filter_settings(   $module->get_module_settings(), TRUE    );
                            
                            usort($module_settings, array($this->functions, 'array_sort_by_processing_order'));
                           
                            
                            if($this->disable_filters   ||  $this->custom_permalinks_applied   === FALSE     ||  !is_array($module_settings)   || count($module_settings) < 1)
                                continue;
                                
                            foreach($module_settings    as  $module_setting)
                                {
                                    
                                    $field_id           =   $module_setting['id'];
                                    $saved_field_value  =   isset($this->settings['module_settings'][ $field_id ]) ?   $this->settings['module_settings'][ $field_id ]    :   '';
                                    
                                    $_class_instance    =   isset($module_setting['class_instance'])  ?   $module_setting['class_instance'] :   $module;
                                    
                                    //ignore callbacks if permalink is turned OFF
                                    if($this->functions->is_permalink_enabled())
                                        {
                                            $_callback              =   isset($module_setting['callback'])  ?   $module_setting['callback'] :   '';
                                            $_callback_arguments    =   isset($module_setting['callback_arguments'])  ?   $module_setting['callback_arguments'] :   '';
                                            if(empty($_callback))
                                                $_callback      =   '_init_'    .   $field_id;
                                            
                                            if (method_exists($_class_instance, $_callback)   && is_callable(array($_class_instance, $_callback)))
                                                $processing_data[]  =   $this->_run_component_callback( $_callback, $_callback_arguments, $_class_instance, $saved_field_value );
                                        }
                                    
                                    //action available for mu-plugins    
                                    do_action('wp-hide/module_settings_process', $field_id, $saved_field_value, $_class_instance, $module);
                                }   
                        
                        }
                        
                        
                    do_action( 'wp-hide/modules_components_run/completed' );
                    
                }
                
            
            
            /**
            * Retrieve the rewrite results from component
            * 
            */
            private function _run_component_callback( $_callback, $_callback_arguments, $_class_instance, $saved_field_value = '' )
                {
                    
                    if ( ! empty($_callback_arguments)  &&  is_array($_callback_arguments) &&   count($_callback_arguments) >   0 )
                        $module_processing_data   =   call_user_func_array( array($_class_instance, $_callback), array_values ( array_merge( array( 'field_value'    =>  $saved_field_value), $_callback_arguments) ) );
                        else
                        $module_processing_data   =   call_user_func(array($_class_instance, $_callback), $saved_field_value);
                                            
                    return $module_processing_data;
                    
                }

                
            /**
            * run on admin_init action
            *     
            */
            function admin_init()
                {
                    //check for settings reset
                    if($this->doing_reset_settings  === TRUE)
                        {
                            $this->functions->do_reset_settings();
                        }
                    
                    //check for interface submit
                    if($this->doing_interface_save  === TRUE)
                        {
                            $this->functions->process_interface_save();
                        }
                        
                    //crete required additional folders
                    $this->functions->init_cache_dir(); 
                    
                }
            
            
            function admin_print_styles()
                {
                    wp_enqueue_style( 'tipsy.css', WPH_URL . '/assets/css/tipsy.css');
                    
                    wp_register_style('WPHStyle', WPH_URL . '/assets/css/wph.css');
                    wp_enqueue_style( 'WPHStyle'); 
                
                }
                
                
            function admin_print_scripts()
                {
                    wp_enqueue_script( 'jquery');
                    wp_register_script('wph', WPH_URL . '/assets/js/wph.js');
                    
                    wp_enqueue_script('jquery.tipsy.js', WPH_URL . '/assets/js/jquery.tipsy.js' ); 
                    
                    // Localize the script with new data
                    $translation_array = array(
                                            'reset_confirmation' => __('Are you sure to reset all settings? All options will be removed. Manual remove of rewrite lines is required if no access from php',    'wp-hide-security-enhancer')
                                        );
                    wp_localize_script( 'wph', 'wph_vars', $translation_array );
                    
                    wp_enqueue_script( 'wph'); 
                
                }
                
                
            function admin_menus()
                {
                    include_once(WPH_PATH . '/include/admin-interface.class.php');
                    include_once(WPH_PATH . '/include/admin-interfaces/setup.class.php');
            
                    $this->admin_interface =    new WPH_interface(); 
                    
                    $system_warning =   FALSE;
                    if( ( $this->server_htaccess_config    === FALSE && $this->server_web_config   === FALSE)
                        ||  is_multisite()
                        )
                        $system_warning =   TRUE;
                    
                    $first_view =   get_option('wph-first-view');
                    if ( isset ( $_GET['page'] )    &&  $_GET['page']   ==  'wp-hide' )
                        $first_view =   'false'; 
                    
                    $menu_title =   'WP Hide';
                    if  ( empty ( $first_view ) ||  $system_warning )
                        $menu_title .= ' <span class="update-plugins count-1"><span class="plugin-count">!</span></span>';                      
                    $hookID   =   add_menu_page('WP Hide', $menu_title, 'manage_options', 'wp-hide');
                    
                                        
                    $menu_title =   'Setup';
                    if  ( empty ( $first_view ) ||  $system_warning )
                        $menu_title .= ' <span class="update-plugins count-1"><span class="plugin-count">!</span></span>';
                    
                    $setup_interface    =   new WPH_setup_interface();
                    $hookID   =             add_submenu_page( 'wp-hide', 'WP Hide', $menu_title, 'manage_options', 'wp-hide', array( $setup_interface,'_render' ) );
                    
                    add_action('admin_print_styles-' . $hookID ,    array($setup_interface, 'admin_print_styles'));
                    add_action('admin_print_scripts-' . $hookID ,   array($setup_interface, 'admin_print_scripts'));
         
                    foreach($this->modules   as  $module)
                        {
                            $interface_menu_data    =   $module->get_interface_menu_data();
                                                    
                            $hookID   =             add_submenu_page( 'wp-hide', 'WP Hide', $interface_menu_data['menu_title'], 'manage_options', $interface_menu_data['menu_slug'], array($this->admin_interface,'_render'));
                            
                            add_action('admin_print_styles-' . $hookID ,    array($this, 'admin_print_styles'));
                            add_action('admin_print_scripts-' . $hookID ,   array($this, 'admin_print_scripts'));
                        }   
                    
                }
                
            
            function admin_notices()
                {
                    global $wp_rewrite;

                    do_action('wp-hide/admin_notices');
                    
                    $screen =   get_current_screen();
                    
                    if ( $screen->parent_base   != 'wp-hide' )
                        {
                            
                            if( $this->server_htaccess_config    === FALSE && $this->server_web_config   === FALSE)
                                {
                                    ?><div class='error'><?php include ( WPH_PATH . 'include/admin-interfaces/notice-server-not-supported.php' );?></div><?php
                                }
                            
                            if ( is_multisite() )
                                {
                                    ?><div class='error'><?php include ( WPH_PATH . 'include/admin-interfaces/notice-is_multisite.php' );?></div><?php
                                }
                                                                                                    
                            if (  ! $this->functions->is_permalink_enabled())
                                {
                                    ?><div class='error'><?php include ( WPH_PATH . 'include/admin-interfaces/notice-no-permalinks.php' );?></div><?php
                                }
                
                            
                            if( ! $this->functions->rewrite_rules_applied() && ( $this->server_htaccess_config    === TRUE || $this->server_web_config   === TRUE ) )
                                {                            
                                    $results['found_issues']   =   TRUE;
                                    $results['critical_issues']    =   TRUE;
                                    $rewrite_file_type = '';
                                    if( $this->server_htaccess_config    === TRUE )
                                        $rewrite_file_type  =   '.htaccess';
                                    
                                    if( $this->server_web_config     === TRUE )
                                        $rewrite_file_type  =   'web.config';
                                    
                                    ?><div class='error'><?php include ( WPH_PATH . 'include/admin-interfaces/notice-write-check.php' ); ?></div><?php
                                }
                            
                            if (    getenv('IS_WPE')    ==  "1"   ||  getenv('IS_WPE_SNAPSHOT')    == "1" ) 
                                {
                                    $results['found_issues']   =   TRUE;
                                    ?><div class='error'><?php include ( WPH_PATH . 'include/admin-interfaces/notice-is-wpengine.php' );?></div><?php
                                }
                 
                            if( ! $this->functions->is_muloader())
                                {
                                    $results['found_issues']   =   TRUE;
                                    ?><div class='error'><?php include ( WPH_PATH . 'include/admin-interfaces/notice-mu-loader.php' );?></div><?php
                                }
                            if( $this->functions->is_muloader() &&  defined( 'WPH_MULOADER_VERSION' )  &&  version_compare( WPH_MULOADER_VERSION, '1.3.5', '<' ) &&    ! isset( $this->maintenances['mu_loader'] ) )
                                {
                                    $results['found_issues']   =   TRUE;
                                    ?><div class='error'><?php include ( WPH_PATH . 'include/admin-interfaces/notice-mu-loader-update.php' );?></div><?php
                                }
                                
                            if( ! is_writable( WPH_CACHE_PATH ))
                                {
                                    $results['found_issues']   =   TRUE;
                                    ?><div class='error'><?php include ( WPH_PATH . 'include/admin-interfaces/notice-cache-path.php' );?></div><?php
                                }
          
                        }
                    
                    if(isset($_GET['reset_settings']))
                        {
                            echo "<div class='updated'><p><b>WP Hide</b> ". __('All Settings where restored to default', 'wp-hide-security-enhancer')  ."</p></div>";
                            
                            $this->functions->settings_changed_check_for_cache_plugins();
                        }
                    
                        
                    if(isset($_GET['settings_updated']))
                        {
                            
                            //check for interface save processing errors
                            $process_interface_save_errors  =   get_transient( 'wph-process_interface_save_errors' );
                            
                            $found_warnings     =   FALSE;
                            $found_errors       =   FALSE;
                            
                            if( is_array($process_interface_save_errors)    &&  count($process_interface_save_errors) > 0)
                                {
                                    foreach ( $process_interface_save_errors    as  $process_interface_save_error )
                                        {
                                            if($process_interface_save_error['type']    === 'warning')
                                                $found_warnings =   TRUE;
                                                
                                            if($process_interface_save_error['type']    === 'error')
                                                $found_errors   =   TRUE;
                                        }
                                    
                                }
                            
                            if( $found_errors   === FALSE )
                                echo "<div class='notice notice-success'><p>". __('Settings saved', 'wp-hide-security-enhancer')  ."<br />" .  __('Remember, site cache clear is required.', 'wp-hide-security-enhancer')  ."</p></div>";
                            
                            if( is_array($process_interface_save_errors)    &&  count($process_interface_save_errors) > 0)
                                {
                                    //display the warnings
                                    if( $found_warnings  === TRUE )
                                        {
                                            echo "<div class='notice notice-warning'><p>";
                                            foreach ( $process_interface_save_errors    as  $process_interface_save_error )
                                                {
                                                    if($process_interface_save_error['type']    == 'warning')
                                                        {
                                                            echo $process_interface_save_error['message'] .'<br />';
                                                        }
                                                }
                                            echo "</p></div>";
                                        }
                                        
                                    //display the errors
                                    if( $found_errors  === TRUE )
                                        {
                                            echo "<div class='notice notice-error'><p>";
                                            foreach ( $process_interface_save_errors    as  $process_interface_save_error )
                                                {
                                                    if($process_interface_save_error['type']    == 'error')
                                                        {
                                                            echo $process_interface_save_error['message'] .'<br />';
                                                        }
                                                }
                                            echo "</p></div>";
                                        }
                                    
                                }
                                                  
                            $this->functions->settings_changed_check_for_cache_plugins();
                        }
                        
                    if(isset($_GET['wph_cache_clear'])  &&  $_GET['wph_cache_clear']    ==  'true')
                        {
                            
                            $this->functions->cache_clear();
                            
                            echo "<div class='updated'><p>". __('Cache cleared', 'wp-hide-security-enhancer')  ."</p></div>";
                        
                        }
                        
                        
                    //output any other errors message
                    $process_errors  =   get_option( 'wph-process_set_static_environment_errors' );
                    if  (  is_array( $process_errors )  &&  count ( $process_errors ) > 0 )
                        {
                            $found_warnings     =   FALSE;
                            $found_errors       =   FALSE;
                            
                            if( is_array($process_errors )    &&  count($process_errors ) > 0)
                                {
                                    foreach ( $process_errors     as  $process_interface_save_error )
                                        {
                                            if($process_interface_save_error['type']    === 'warning')
                                                $found_warnings =   TRUE;
                                                
                                            if($process_interface_save_error['type']    === 'error')
                                                $found_errors   =   TRUE;
                                        }
                                    
                                }
                            
                            //display the warnings
                            if( $found_warnings  === TRUE )
                                {
                                    echo "<div class='notice notice-warning'><p>";
                                    foreach ( $process_errors     as  $process_interface_save_error )
                                        {
                                            if($process_interface_save_error['type']    == 'warning')
                                                {
                                                    echo $process_interface_save_error['message'] .'<br />';
                                                }
                                        }
                                    echo "</p></div>";
                                }
                                
                            //display the errors
                            if( $found_errors  === TRUE )
                                {
                                    echo "<div class='notice notice-error'><p>";
                                    foreach ( $process_errors     as  $process_interface_save_error )
                                        {
                                            if($process_interface_save_error['type']    == 'error')
                                                {
                                                    echo $process_interface_save_error['message'] .'<br />';
                                                }
                                        }
                                    echo "</p></div>";
                                }   
                            
                            
                        }
                        
                }
                        
            /**
            * Buffer Callback. This is the place to replace all data
            *     
            * @param mixed $buffer
            */
            function ob_start_callback( $buffer )
                {
                    
                    if($this->disable_ob_start_callback === TRUE)
                        return $buffer;
                    
                    $response_headers   =   array();
                        
                    if ( empty ( $buffer ) )
                        {                            
                            //attempt to change the headers urls
                            if(function_exists('apache_response_headers'))
                                {
                                    $response_headers    =   apache_response_headers();
                                }
                                else  
                                    {
                                        if  ( ! is_null ($this->functions) )
                                            $response_headers   =   $this->functions->parseRequestHeaders();
                                    }
                            
                            if  ( ! is_null ($this->functions) )         
                                $this->functions->update_headers ( array ( 'Location' ) ,  $response_headers );
                            
                            return $buffer;
                        }
                        
                    //check for xml content tupe 
                    $headers_content_type   =   array();
                    if  ( ! is_null ( $this->functions ) )
                        $headers_content_type    =   $this->functions->get_headers_list_content_type();
                    if ( in_array( $headers_content_type , array( 'text/xml', 'application/rss+xml' ) )    &&  ! is_null ( $this->functions ) )
                        {

                            //do only url replacements
                            $replacement_list   =   $this->functions->get_replacement_list();
                    
                            //replace the urls
                            $buffer =   $this->functions->content_urls_replacement($buffer,  $replacement_list );
                            
                            //if html comments remove is on, run a regex
                            $option_remove_html_comments =   $this->functions->get_module_item_setting( 'remove_html_comments' );
                            if ( ! empty ( $option_remove_html_comments )   &&  $option_remove_html_comments    ==  'yes' )
                                $buffer =   WPH_module_general_html::remove_html_comments( $buffer );    
                            
                            return $buffer;   
                        }   
                    
                        
                    //provide a filter to disable the replacements
                    if  ( apply_filters('wp-hide/ignore_ob_start_callback', FALSE, $buffer)     === TRUE   )
                        return $buffer;
                        
                    //check headers fir content-encoding
                    if(function_exists('apache_response_headers'))
                        {
                            $response_headers    =   apache_response_headers();
                        }
                        else  
                            {
                                $response_headers = $this->functions->parseRequestHeaders();
                            }
                            
                    if(isset($response_headers['Content-Encoding']) &&  $response_headers['Content-Encoding']   ==  "gzip")
                        {
                            //Decodes the gzip compressed buffer
                            $decoded    =   @gzdecode($buffer);
                            if($decoded === FALSE   ||  $decoded    ==  '')
                                return $buffer;
                                
                            $buffer =   $decoded;
                        }
                    
                    //retrieve the replacements list
                    $replacement_list   =   $this->functions->get_replacement_list();
                                            
                    //replace the urls
                    $buffer =   $this->functions->content_urls_replacement($buffer,  $replacement_list );
                    
                    //check for redirect header and make updates
                    if(isset($response_headers['Location']))
                        {
                            $header_value   =   $response_headers['Location'];
                            $new_header_value   =   $this->functions->content_urls_replacement($header_value,  $replacement_list ); 
                            
                            if($header_value    !=  $new_header_value)
                                {
                                    header_remove("Location");
                                    header( 'Location: ' . $new_header_value );
                                }
                        }
                    
                    $buffer = apply_filters( 'wp-hide/ob_start_callback', $buffer ); 
                    
                    if(isset($response_headers['Content-Encoding']) &&  $response_headers['Content-Encoding']   ==  "gzip")
                        {
                            //compress the buffer
                            $buffer    =   gzencode($buffer);
                        }

                        
                    return $buffer;
            
                }
                
                
            /**
            * Ignore the buffer processing if the content is not filterable by header content type
            *     
            * @param mixed $ignore
            */
            function ignore_ob_start_callback( $ignore )
                {
                    $is_filterable =   $this->functions->is_filterable_content_type();
                    
                    if ( $is_filterable  === FALSE )
                        $ignore =   TRUE;
                    
                    return $ignore;    
                }
            
            /**
            * check for any query and headers change
            * 
            */
            function check_for_404()
                {
                    if(!isset($_GET['wph-throw-404']))
                        return;
                        
                    global $wp_query;

                    $wp_query->set_404();
                    status_header(404);
                    
                    add_action('request',               array($this, 'change_request'), 999);
                    add_action('parse_request',         array($this, 'change_parse_request'), 999);
                    
                    remove_action( 'template_redirect', 'redirect_canonical' );
                    remove_action( 'template_redirect', 'wp_redirect_admin_locations', 1000 );
                                        
                }
                
            
            /**
            * Modify the request data to allow a 404 error page to trigger
            * 
            * @param mixed $query_vars
            */
            function change_request($query_vars)
                {
                    
                    return array();
                       
                }
            
            function change_parse_request( $object )
                {
                    
                    $object->request            =   NULL;
                    $object->matched_rule       =   NULL;
                    $object->matched_query      =   NULL;
                    
                    $object->query_vars['error']    =   404;
                       
                }
                
            
            /**
            * The plugin always need to load first to ensure filters are loading before anything else
            * 
            */  
            function activated_plugin($plugin, $network_wide)
                {
                    return;
                    
                    if($network_wide)
                        {
                            $active_plugins = get_site_option( 'active_sitewide_plugins', array() );
                            
                            
                            
                            //$active_plugins = get_site_option( 'active_sitewide_plugins', array() );
                            
                            return;
                        }
                    
                    
                    $active_plugins = (array) get_option( 'active_plugins', array() );
                        
                    if(count($active_plugins)   <   2)
                        return;
                    
                    $plugin_path    =   'wp-hide-security-enhancer/wp-hide.php';
                    
                    $key            = array_search( $plugin_path, $active_plugins );
                    if($key === FALSE   ||  $key    <   1)
                        return;

                    array_splice    ( $active_plugins, $key, 1 );
                    array_unshift   ( $active_plugins, $plugin_path );
                    
                    update_option( 'active_plugins', $active_plugins );
                    
                }
            
            
            function wp_redirect($location, $status)
                {
                    if( $this->uninstall === TRUE   ||  $this->disable_filters   ||  $this->custom_permalinks_applied   === FALSE   )
                        return $location;
                          
                    //do not replace 404 pages
                    global $wp_the_query;
                    
                    if(!is_object($wp_the_query))
                        return $location;
                    
                    if($wp_the_query->is_404())
                        return $location;
                        
                    $location   =   $this->functions->content_urls_replacement($location,  $this->functions->get_replacement_list() );
                    
                    /**
                    * Check if register link for to apply the replacement
                    * Unfortunate the default WordPress link does not contain a beginning backslash to make a replacement match in functions->content_urls_replacement
                    */
                    if ( preg_match("/(wp-login.php?(.*)?checkemail=registered)/i", $location) || preg_match("/(wp-login.php?(.*)?checkemail=confirm)/i", $location ) )
                        {
                            $updated_slug     =   $this->functions->get_module_item_setting('new_wp_login_php'  ,   'admin');
                            if ( ! empty(  $updated_slug ))
                                $location =   str_replace('wp-login.php',  $updated_slug,  $location);     
                        }
                                        
                    $location   =   apply_filters('wp-hide/wp_redirect', $location);
                        
                    return $location; 
                }
                
            /**
            * Update arbitrary url with new data
            * 
            * @param mixed $url
            */
            function url_replace( $url )
                {
                    
                    $url =   $this->functions->content_urls_replacement($url,  $this->functions->get_replacement_list() );
                    
                    return $url;
                        
                }
                
            
            function logout_redirect($redirect_to, $requested_redirect_to, $user)
                {
                    $new_wp_login_php     =   $this->functions->get_module_item_setting('new_wp_login_php'  ,   'admin');
                    if (empty(  $new_wp_login_php ))
                        return $redirect_to;
                                        
                    $redirect_to =   str_replace('wp-login.php',  $new_wp_login_php,  $redirect_to);
                        
                    return $redirect_to; 
                }
                
            function generic_string_replacement($text)
                {
                    $text   =   $this->functions->content_urls_replacement($text,  $this->functions->get_replacement_list() );
                        
                    return $text;   
                    
                }
                     
            function get_setting_value($setting_name, $module_setting )
                {
                    $setting_value  =   isset($this->settings['module_settings'][$setting_name])    ?   $this->settings['module_settings'][$setting_name]   :   $module_setting['default_value'];
                    
                    //if radio input and value is empty, use default
                    if  ( empty ( $setting_value ) &&   $module_setting['input_type']   ==  'radio' )
                        {
                            $setting_value  =   $module_setting['default_value'];
                        }
                    
                    return $setting_value;
                }
                
                
            function settings_changed()
                {
                    //allow rewrite
                    flush_rewrite_rules(); 
                    
                    $this->functions->site_cache_clear();
                    
                }
                
            
            
                
            /**
            * Create a staitc file which contain specific variables and will be used in router 
            * 
            */
            function set_static_environment_file( $force_create = FALSE )
                {
                    $this->enironment_checked = TRUE;
                                        
                    include_once(WPH_PATH . '/include/class.environment.php');
                    $WPH_Environment    =   new WPH_Environment();
                    
                    if ( $WPH_Environment->is_correct_environment() )
                        return;

                    $WPH_Environment->write_environment();
                }
            
                
                            
                
            function get_rewrite_rules( )
                {
                    
                    $rules  =   "";
                    
                    if($this->uninstall === TRUE)
                        return $rules;
                        
                    $write_check_string =   isset( $this->settings['write_check_string'] ) ? $this->settings['write_check_string']    :   '';
                    
                    if ( empty ( $write_check_string ) )
                        {
                            //generate a new write_check_string
                            $write_check_string  =   time() . '_' . mt_rand(100, 99999);
                            $this->settings['write_check_string']   =   $write_check_string;
                                                                                           
                            //update the settings
                            $this->functions->update_settings( $this->settings );   
                        }
                    
                    $processing_data    =   $this->get_components_rules();
                                           
                    //post-process the htaccess data    
                    $_rewrite_data =   array();
                    $_page_refresh  =   FALSE;
                    foreach($processing_data    as  $response)
                        {
                            if(isset($response['rewrite']) &&  !empty($response['rewrite']))
                                {
                                    $_rewrite_data[]   =   $response['rewrite'];
                                }
                                
                            if(isset($response['page_refresh']) &&  $response['page_refresh']   === TRUE)
                                $_page_refresh  =   TRUE;
                        }
                    
                    $rules  .=  "#WriteCheckString:" . $write_check_string . "\n";
                    $rules  .=  "RewriteRule .* - [E=HTTP_MOD_REWRITE:On]" . "\n";
                    
                    $plugin_path        =   $this->functions->get_url_path( WP_PLUGIN_URL );
                    $rewrite_to         =   $this->functions->get_rewrite_to_base( trailingslashit( $plugin_path ) . 'wp-hide-security-enhancer/include/rewrite-confirm.php', TRUE, FALSE );
                    
                    $rules  .=  "RewriteRule ^rewrite_test_" .$write_check_string ."/? ". $rewrite_to ." [L,QSA]";
                    
                    if(count($_rewrite_data)   >   0)
                        {
                            foreach($_rewrite_data as  $_htaccess_data_line)   
                                {
                                    $rules .=   "\n"    .   $_htaccess_data_line;
                                }                            
                        } 
                            
                    $rules      =   apply_filters('wp-hide/mod_rewrite_rules', $rules, 'apache');
                    
                    
                    $home_root = parse_url(home_url());
                    if ( isset( $home_root['path'] ) )
                            $home_root = trailingslashit($home_root['path']);
                        else
                            $home_root = '/';
                    
                    $rules  =   "<IfModule mod_rewrite.c> \n" 
                                . "RewriteEngine On \n"
                                . "RewriteBase ". $home_root ." \n"
                                . $rules
                                . "\n"
                                . "</IfModule> \n";
                    
                    return $rules;
                              
                }
                
            /**
            * Maintain Environment file 
            * 
            */
            function environment_check( $force_check    =   FALSE )
                {
                    
                    if  ( $this->enironment_checked === TRUE )
                        return;
                    
                    include_once(WPH_PATH . '/include/class.environment.php');
                    $WPH_Environment    =   new WPH_Environment();
                    
                    if ( $WPH_Environment->is_correct_environment() )
                        return;

                    $WPH_Environment->write_environment();

                }
                
                
            /**
            * Check if the mu-loader is deployed and up to date
            * 
            * @param mixed $continue
            */
            function mu_loader_check()
                {
                    
                    if  ( $this->functions->is_muloader()   === FALSE )
                        {
                            $status =   $this->functions->copy_mu_loader();
                            if  ( $status )
                                $this->maintenances['mu_loader']    =   TRUE;
                            return;
                        }
                        
                    if  ( $this->functions->is_muloader() &&  ( !defined( 'WPH_MULOADER_VERSION' ) ||   version_compare( WPH_MULOADER_VERSION, '1.3.5', '<' ) ) )
                        {
                            $status =   $this->functions->copy_mu_loader( TRUE );
                            if  ( $status )
                                $this->maintenances['mu_loader']    =   TRUE;
                        }
                    
                    
                }
            
            
            function flush_rewrite_rules_hard( $continue )
                {
                    $home_path      = $this->functions->get_home_path();
                    $htaccess_file  = $home_path . DIRECTORY_SEPARATOR . '.htaccess';   
                    
                    //check if .htaccess file exists and is writable
                    if( !   $this->functions->is_writable_htaccess_config_file( $htaccess_file ))
                        return TRUE;
                    
                    $rules          =   $this->get_rewrite_rules();
                    
                    //check if there's a  # BEGIN WordPress    and   # END WordPress    markers or create those to ensude plugin rules are put on top of Wordpress ones   
                    $file_content = @file( $htaccess_file );
                    
                    if ( $file_content ) 
                        {    
                            if( count( preg_grep("/.*# BEGIN WordPress.*/i", $file_content) )   <   1 &&  count( preg_grep("/.*# END WordPress.*/i", $file_content) )   <   1 )
                                {
                                    $this->functions->insert_with_markers_on_top( $htaccess_file, 'WordPress', '' );
                                }
                        }
                    
                    
                    $this->functions->insert_with_markers_on_top( $htaccess_file, 'WP Hide & Security Enhancer', $rules, 'top' );
                        
                    return TRUE;
                        
                }
            
                
            function get_components_rules()
                {
                    
                    $processing_data    =   array();
                        
                    //loop all module settings and run the callback functions
                    foreach($this->modules   as  $module)
                        {
                            $module_settings  =   $this->functions->filter_settings(   $module->get_module_settings(), TRUE    );
                            
                            //sort by processing order
                            usort($module_settings, array($this->functions, 'array_sort_by_processing_order'));
                            
                            if(is_array($module_settings)   && count($module_settings) > 0)
                            foreach($module_settings    as  $module_setting)
                                {
                                    
                                    $field_id           =   $module_setting['id'];
                                    $saved_field_value  =   isset( $this->settings['module_settings'][ $field_id ]) ?   $this->settings['module_settings'][ $field_id ]    :   '';
                                                                   
                                    $_class_instance    =   isset($module_setting['class_instance'])  ?   $module_setting['class_instance'] :   $module;
                                    $_callback          =   isset($module_setting['callback_saved'])  ?   $module_setting['callback_saved'] :   '';
                                    if(empty($_callback))
                                        $_callback      =   '_callback_saved_'    .   $field_id;
                                    
                                    if (method_exists($_class_instance, $_callback)   && is_callable(array($_class_instance, $_callback)))
                                        {
                                            $module_mod_rewrite_rules   =   call_user_func(array($_class_instance, $_callback), $saved_field_value);
                                            $module_mod_rewrite_rules   =   apply_filters('wp-hide/module_mod_rewrite_rules', $module_mod_rewrite_rules, $_class_instance);
                                            
                                            $processing_data[]          =   $module_mod_rewrite_rules;
                                        }
                                        
                                }
                        }
                        
                        
                    return $processing_data;
                    
                }
                
                
            function iis7_url_rewrite_rules( $wp_rules )
                {
                    $home_path          = get_home_path();
                    $web_config_file    = $home_path . 'web.config';
                    
                    //delete all WPH rules
                    $this->iis7_delete_rewrite_rules($web_config_file);
                    
                    if($this->uninstall === TRUE)
                        return $wp_rules;                        
                    
                    $processing_data    =   $this->get_components_rules();
                                           
                    //post-process the htaccess data    
                    $_rewrite_data =   array();
                    $_page_refresh  =   FALSE;
                    foreach($processing_data    as  $response)
                        {
                            if(isset($response['rewrite']) &&  !empty($response['rewrite']))
                                {
                                    $_rewrite_data[]   =   $response['rewrite'];
                                }
                                
                            if(isset($response['page_refresh']) &&  $response['page_refresh']   === TRUE)
                                $_page_refresh  =   TRUE;
                        }
                    
                    $write_check_string =   isset( $this->settings['write_check_string'] ) ? $this->settings['write_check_string']    :   '';
                    
                    $plugin_path        =   $this->functions->get_url_path( WP_PLUGIN_URL );
                    $rewrite_to         =   $this->functions->get_rewrite_to_base( trailingslashit( $plugin_path ) . 'wp-hide-security-enhancer/include/rewrite-confirm.php' );
                                
                    //add a write stricng
                    $_writestring_rule  =   '
                        <rule name="wph-CheckString">
                            <!-- WriteCheckString:'. $write_check_string  .' -->
                        </rule>
                        <rule name="wph-RewriteTest" stopProcessing="true">
                            <match url="^rewrite_test_'.  $write_check_string   .'/?"  />
                            <action type="Rewrite" url="'.  $rewrite_to .'{R:1}" />
                        </rule>
                        
                        ';
                    array_unshift($_rewrite_data, $_writestring_rule);
                               
                    $this->iis7_add_rewrite_rule( $_rewrite_data, $web_config_file );

                    return $wp_rules;
                    
                }
                
                
           
            /**
            * Add a rewrite rule within specified file
            * 
            * @param mixed $filename
            */
            function  iis7_add_rewrite_rule( $rules, $filename )
                {
                    
                    if (!is_array($rules)    ||  count($rules)   <   1)
                        return false;
                    
                    if ( ! class_exists( 'DOMDocument', false ) ) {
                        return false;
                    }

                    // If configuration file does not exist then we create one.
                    if ( ! file_exists($filename) ) {
                        $fp = fopen( $filename, 'w');
                        fwrite($fp, '<configuration/>');
                        fclose($fp);
                    }
                    
                    $doc = new DOMDocument();
                    $doc->preserveWhiteSpace = false;

                    if ( $doc->load($filename) === false )
                        return false;

                    $xpath = new DOMXPath($doc);
        
                    // Check the XPath to the rewrite rule and create XML nodes if they do not exist
                    $xmlnodes = $xpath->query('/configuration/system.webServer/rewrite/rules');
                    if ( $xmlnodes->length > 0 ) {
                        $rules_node = $xmlnodes->item(0);
                    } else {
                        $rules_node = $doc->createElement('rules');

                        $xmlnodes = $xpath->query('/configuration/system.webServer/rewrite');
                        if ( $xmlnodes->length > 0 ) {
                            $rewrite_node = $xmlnodes->item(0);
                            $rewrite_node->appendChild($rules_node);
                        } else {
                            $rewrite_node = $doc->createElement('rewrite');
                            $rewrite_node->appendChild($rules_node);

                            $xmlnodes = $xpath->query('/configuration/system.webServer');
                            if ( $xmlnodes->length > 0 ) {
                                $system_webServer_node = $xmlnodes->item(0);
                                $system_webServer_node->appendChild($rewrite_node);
                            } else {
                                $system_webServer_node = $doc->createElement('system.webServer');
                                $system_webServer_node->appendChild($rewrite_node);

                                $xmlnodes = $xpath->query('/configuration');
                                if ( $xmlnodes->length > 0 ) {
                                    $config_node = $xmlnodes->item(0);
                                    $config_node->appendChild($system_webServer_node);
                                } else {
                                    $config_node = $doc->createElement('configuration');
                                    $doc->appendChild($config_node);
                                    $config_node->appendChild($system_webServer_node);
                                }
                            }
                        }
                    }

                    //append before other rules
                    $ref_node   =   $xpath->query('/configuration/system.webServer/rewrite/rules/rule[starts-with(@name,\'wordpress\')] | /configuration/system.webServer/rewrite/rules/rule[starts-with(@name,\'WordPress\')]');
                         
                    foreach($rules  as  $rule)
                        {
                            $rule_fragment = $doc->createDocumentFragment();
                            $rule_fragment->appendXML($rule);
                            
                            if($ref_node->length > 0)
                                $rules_node->insertBefore($rule_fragment, $ref_node->item(0));
                                else
                                $rules_node->appendChild($rule_fragment);
                        }

                    $doc->encoding = "UTF-8";
                    $doc->formatOutput = true;
                    saveDomDocument($doc, $filename);
             
                    return true;   
                    
                    
                }
           
           
           
            /**
            * Delete all wph rules within specified filename
            * 
            * @param mixed $filename
            */
            function iis7_delete_rewrite_rules( $filename )
                {
                    
                    if ( ! file_exists($filename) )
                        return true;

                    if ( ! class_exists( 'DOMDocument', false ) ) {
                        return false;
                    }

                    $doc = new DOMDocument();
                    $doc->preserveWhiteSpace = false;

                    if ( $doc -> load($filename) === false )
                        return false;
                    $xpath = new DOMXPath($doc);
                    $rules = $xpath->query('/configuration/system.webServer/rewrite/rules/rule[starts-with(@name,\'wph\')]');
                    if ( $rules->length > 0 ) 
                        {
                            
                            foreach($rules  as  $child)
                                {
                                    $parent = $child->parentNode;
                                    $parent->removeChild($child);        
                                }
                            
                            $doc->formatOutput = true;
                            saveDomDocument($doc, $filename);
                        }
                               
                    return true;   
                    
                }
                
            
            
            function get_default_variables()
                {   
                    $this->default_variables['include_url']         =   trailingslashit(    site_url()  )  . WPINC;
                    
                    $this->default_variables['template_url']        =   get_bloginfo('template_url');
                    $this->default_variables['stylesheet_uri']      =   get_stylesheet_directory_uri();
                    
                    $this->default_variables['plugins_url']         =   plugins_url();
                    
                    $wp_upload_dir  =   wp_upload_dir();
                    $this->default_variables['upload_url']          =   $wp_upload_dir['baseurl'];
                    
                    //catch the absolute siteurl in case wp folder is different than domain root
                    $this->default_variables['wordpress_directory']    =   '';
                    $this->default_variables['content_directory']      =   '';
                    
                    //content_directory
                    $content_directory   =   str_replace(ABSPATH, "", WP_CONTENT_DIR);
                    $content_directory   =   str_replace( '\\', '/', $content_directory );
                    $content_directory   =   trim($content_directory, '/ ');
                    $this->default_variables['content_directory']   =   '/' .   $content_directory;
                    
                    $home_url   =   defined('WP_HOME')  ?   WP_HOME         :   get_option('home');
                    $home_url   =   untrailingslashit($home_url);
                    //stripp the protocols to ensure there's no difference from home_ur to site_url 
                    $home_url   =   str_replace(array('http://', 'https://', 'http://www.', 'https://www.'), '', $home_url);
                    
                    $siteurl    =   defined('WP_HOME')  ?   WP_SITEURL      :   get_option('siteurl');
                    $siteurl    =   untrailingslashit($siteurl);
                    //stripp the protocols to ensure there's no difference from home_ur to site_url 
                    $siteurl   =   str_replace(array('http://', 'https://', 'http://www.', 'https://www.'), '', $siteurl);
                    
                    $wp_directory   =   str_replace($home_url, "" , $siteurl);
                    $wp_directory   =   trim(trim($wp_directory), '/');
                    
                    if($wp_directory    !=  '')
                        {
                            $this->default_variables['wordpress_directory'] =   '/' . trim($wp_directory, '/');
                        }
                    
                    //used across modules
                    $home_root = parse_url(home_url());
                    if ( isset( $home_root['path'] ) )
                            $home_root_path = trailingslashit($home_root['path']);
                        else
                            $home_root_path = '/';
                    $this->default_variables['site_relative_path']  =   $home_root_path;
                    //$this->default_variables['site_relative_path']  =   rtrim ( $this->functions->get_url_path( rtrim(    $siteurl, '/'  ), FALSE, FALSE), '/' );
                    if ( empty ( $this->default_variables['site_relative_path'] ) )
                        $this->default_variables['site_relative_path']  =   '/'; 
                    
                    //themes url
                    $this->templates_data['themes_url']                 =   trailingslashit(    get_theme_root_uri()    );
                    
                    $all_templates  =   $this->functions->get_themes();
                    $all_templates  =   $this->functions->parse_themes_headers($all_templates);
                    
                    $stylesheet     =   get_option( 'stylesheet' );
                                        
                    $this->templates_data['use_child_theme']            =   $this->functions->is_child_theme($stylesheet, $all_templates);
                    
                    $main_theme_directory                               =   $this->functions->get_main_theme_directory($stylesheet, $all_templates);
                    $this->templates_data['main']                       =   array();
                    $this->templates_data['main']['folder_name']        =   $main_theme_directory;
                    $this->templates_data['_template_' .  $main_theme_directory]    =   'main';
                    
                    if($this->templates_data['use_child_theme'])
                        {
                            $this->templates_data['child']         =   array();        
                            $this->templates_data['child']['folder_name']  =   $stylesheet;
                            $this->templates_data['_template_' .  $stylesheet]    =   'child';
                        }
                    
                      
                }
                
            
            /**
            * Apply new changes for e-mail content too
            * 
            * @param mixed $atts
            */
            function apply_for_wp_mail($atts)
                {
                    
                    if ( isset ($atts['message'] ) )
                        $atts['message'] =   $this->functions->content_urls_replacement($atts['message'],  $this->functions->get_replacement_list() );
                       
                    return $atts;
                       
                }
                
            
            /**
            * Add default Url Replacements
            * 
            */
            function add_default_replacements()
                {
                    
                    do_action('wp-hide/add_default_replacements', $this->urls_replacement);   
                }
       
                
            function switch_theme()
                {
                    $this->disable_filters  =   TRUE;
                    $this->get_default_variables();
                    
                    //allow rewrite
                    flush_rewrite_rules();
                    
                    $this->disable_filters  =   FALSE;    
                }
                
            function permalink_change_redirect()
                {
                    $screen = get_current_screen();
                    
                    if(empty($screen))
                        return;
                       
                    if($screen->base    !=  "options-permalink")
                        return;
                    
                    //recheck if the permalinks were sucesfully saved
                    $this->custom_permalinks_applied   =   $this->functions->rewrite_rules_applied();
                    
                    //ignore if permalinks are available
                    if($this->custom_permalinks_applied   === FALSE)
                        return;
                                        
                    $new_location   =   trailingslashit(    site_url()  )   . "wp-admin/options-permalink.php";   
                    
                    if($this->functions->is_permalink_enabled())
                        {
                            $new_admin_url     =   $this->functions->get_module_item_setting('admin_url'  ,   'admin');
                            if(!empty($new_admin_url))
                                $new_location      =   trailingslashit(    home_url()  )   . $new_admin_url .  "/options-permalink.php";
                        }
                        
                    $new_location   .=  '?settings-updated=true';
                    
                    //no need to redirect if it's on the same path
                    $request_uri    =   $_SERVER['REQUEST_URI'];
                    
                    $new_location_uri   =   $this->functions->get_url_path($new_location, TRUE);
                    if($request_uri ==  $new_location_uri)
                        return;
                    
                    wp_redirect( $new_location  );
                    die();
                }
            
            
            /**
            * General Plugins and Themes compatibility Handle
            *     
            */
            function plugins_themes_compatibility()
                {
                    
                    include_once( WPH_PATH . '/include/class.compatibility.php' );
                    $compatibility_handler    =   new WPH_Compatibility();
                    
                }
                
                
                
            /**
            * Revert back the files urls to default WordPress
            * 
            * @param mixed $post_id
            */
            function save_post( $post_id )
                {
                    if ( wp_is_post_revision( $post_id ) )
                        return;
                        
                    global $wpdb;
                    
                    //raw retrieve the post data
                    $mysql_query    =   $wpdb->prepare( "SELECT * FROM " .   $wpdb->posts  .  "   WHERE ID    =   %d", $post_id );
                    $post_data      =   $wpdb->get_row( $mysql_query );
                    
                    $replacement_list   =   $this->functions->get_replacement_list();
                    //reverse the list
                    $replacement_list   =   array_flip($replacement_list);
                    
                    //replace the urls
                    $post_content =   $this->functions->content_urls_replacement($post_data->post_content,  $replacement_list );
                    
                    //if there's a difference, update
                    if (  $post_content != $post_data->post_content )
                        {
                            $mysql_query    =   $wpdb->prepare( "   UPDATE " .   $wpdb->posts  .  "
                                                        SET post_content    =   %s   
                                                        WHERE ID    =   %d",  $post_content, $post_id);
                            $result         =   $wpdb->get_results( $mysql_query );
                        }
                    
                }
                
                
                
            /**
            * Revert back the files urls to default WordPress
            * 
            * @param mixed $check
            * @param mixed $object_id
            * @param mixed $meta_key
            * @param mixed $meta_value
            * @param mixed $prev_value
            */
            function update_post_metadata ( $check, $object_id, $meta_key, $meta_value, $prev_value )
                {
                    global $wpdb;

                    $meta_type      =   'post';
                    
                    $table          = _get_meta_table( $meta_type );
                    $column         = sanitize_key( $meta_type . '_id' );
                    $id_column      = 'user' == $meta_type ? 'umeta_id' : 'meta_id';
                    
                    $_meta_value     =   $meta_value;
                    
                    $replacement_list   =   $this->functions->get_replacement_list();
                    //reverse the list
                    $replacement_list   =   array_flip($replacement_list);
                    //replace the urls
                    if ( is_array ( $meta_value ) )
                        {
                            if ( count ( $meta_value ) > 0 )
                                {
                                    foreach ( $meta_value   as  $key  => $value )
                                        {
                                            if ( is_string( $meta_value) )
                                                $meta_value[$key]         =   $this->functions->content_urls_replacement( $value,  $replacement_list );
                                        }
                                }
                        }
                        else
                        {
                            if ( is_string( $meta_value) )
                                $meta_value         =   $this->functions->content_urls_replacement( $meta_value,  $replacement_list );
                        }
                    
                    $raw_meta_key   = $meta_key;
                    $passed_value   = wp_slash($meta_value);
                       
                    // Compare existing value to new value if no prev value given and the key exists only once.
                    if ( empty( $prev_value ) && function_exists( 'get_metadata_raw' ) ) 
                        {
                            $old_value = get_metadata_raw( $meta_type, $object_id, $meta_key );
                            if ( is_countable( $old_value ) && count( $old_value ) === 1 ) 
                                {
                                    if ( $old_value[0] === $meta_value ) {
                                        return false;
                                    }
                                }
                        }

                    
                    $meta_ids = $wpdb->get_col( $wpdb->prepare( "SELECT $id_column FROM $table WHERE meta_key = %s AND $column = %d", $meta_key, $object_id ) );
                    if ( empty( $meta_ids ) ) {
                        return add_metadata( $meta_type, $object_id, $raw_meta_key, $passed_value );
                    }
           

                    $_meta_value = $meta_value;
                    $meta_value  = maybe_serialize( $meta_value );

                    $data  = compact( 'meta_value' );
                    $where = array(
                        $column    => $object_id,
                        'meta_key' => $meta_key,
                    );

                    if ( ! empty( $prev_value ) ) {
                        $prev_value          = maybe_serialize( $prev_value );
                        $where['meta_value'] = $prev_value;
                    }

                    foreach ( $meta_ids as $meta_id ) {
                        /**
                         * Fires immediately before updating metadata of a specific type.
                         *
                         * The dynamic portion of the hook, `$meta_type`, refers to the meta
                         * object type (comment, post, term, or user).
                         *
                         * @since 2.9.0
                         *
                         * @param int    $meta_id     ID of the metadata entry to update.
                         * @param int    $object_id   Object ID.
                         * @param string $meta_key    Meta key.
                         * @param mixed  $_meta_value Meta value.
                         */
                        do_action( "update_{$meta_type}_meta", $meta_id, $object_id, $meta_key, $_meta_value );

                        if ( 'post' == $meta_type ) {
                            /**
                             * Fires immediately before updating a post's metadata.
                             *
                             * @since 2.9.0
                             *
                             * @param int    $meta_id    ID of metadata entry to update.
                             * @param int    $object_id  Post ID.
                             * @param string $meta_key   Meta key.
                             * @param mixed  $meta_value Meta value. This will be a PHP-serialized string representation of the value if
                             *                           the value is an array, an object, or itself a PHP-serialized string.
                             */
                            do_action( 'update_postmeta', $meta_id, $object_id, $meta_key, $meta_value );
                        }
                    }

                    $result = $wpdb->update( $table, $data, $where );
                    if ( ! $result ) {
                        return false;
                    }

                    wp_cache_delete( $object_id, $meta_type . '_meta' );

                    foreach ( $meta_ids as $meta_id ) {
                        /**
                         * Fires immediately after updating metadata of a specific type.
                         *
                         * The dynamic portion of the hook, `$meta_type`, refers to the meta
                         * object type (comment, post, term, or user).
                         *
                         * @since 2.9.0
                         *
                         * @param int    $meta_id     ID of updated metadata entry.
                         * @param int    $object_id   Object ID.
                         * @param string $meta_key    Meta key.
                         * @param mixed  $_meta_value Meta value.
                         */
                        do_action( "updated_{$meta_type}_meta", $meta_id, $object_id, $meta_key, $_meta_value );

                        if ( 'post' == $meta_type ) {
                            /**
                             * Fires immediately after updating a post's metadata.
                             *
                             * @since 2.9.0
                             *
                             * @param int    $meta_id    ID of updated metadata entry.
                             * @param int    $object_id  Post ID.
                             * @param string $meta_key   Meta key.
                             * @param mixed  $meta_value Meta value. This will be a PHP-serialized string representation of the value if
                             *                           the value is an array, an object, or itself a PHP-serialized string.
                             */
                            do_action( 'updated_postmeta', $meta_id, $object_id, $meta_key, $meta_value );
                        }
                    }

                    return true;   
                    
                }
                
                
                
            /**
            * Revert the option value before saving
            *     
            * @param mixed $query
            */
            function pre_update_option( $value, $option, $old_value )
                {
                    
                    if ( $value === $old_value || maybe_serialize( $value ) === maybe_serialize( $old_value ) ) 
                        return $value;
                    
                    //ignore specific options
                    if ( apply_filters('wph/reverse_urls/pre_update_option', $option, FALSE ) )
                        return $value;
                    
                    $replacement_list   =   $this->functions->get_replacement_list();
                    if ( count ( $replacement_list ) < 1 )
                        return $value;
                    
                    $replacement_list   =   array_flip($replacement_list);
                    
                    $value  =   $this->option_block_revert( $value, $replacement_list );
                                            
                    return $value;   
                }
                
            
            /**
            * Ignore the plugin option when saving
            * 
            * @param mixed $option_name
            * @param mixed $ignore_status
            */
            function reverse_urls_pre_update_option( $option_name, $ignore_status )
                {
                    
                    if ( !in_array( $option_name, array ( 'wph_settings', 'wph-previous-options-list' )) )
                        return $ignore_status;
                    
                    $ignore_status  =   TRUE;
                    
                    return $ignore_status;
                }
                
                
            function option_block_revert( $data, $replacement_list )
                {
                    switch (gettype($data))
                        {
                            case 'array':
                                            foreach ($data as $key => $value)
                                                {
                                                    $data[$key] = $this->option_block_revert( $value, $replacement_list );
                                                }
                                            break;
                                            
                            case 'object':
                                            foreach ($data as $key => $value)
                                                {
                                                    $data->$key = $this->option_block_revert( $value, $replacement_list );
                                                }
                                            break;
                                            
                            case 'string': 
                                            $data = $this->functions->content_urls_replacement( $data,  $replacement_list ); 
                                            
                                            break;            
                        }
                    
                    return $data;
                }           
                
            
            
            function attachment_url_to_postid ( $post_id, $url )
                {
                    
                    if ( $post_id > 0 )
                        return $post_id;
                        
                        
                    global $wpdb, $wph;

                    $url    =   $wph->functions->content_urls_replacement( $url,  array_flip ( $wph->functions->get_replacement_list() )  );
                    
                    $dir  = wp_get_upload_dir();
                    $path = $url;

                    $site_url   = parse_url( $dir['url'] );
                    $image_path = parse_url( $path );

                    // Force the protocols to match if needed.
                    if ( isset( $image_path['scheme'] ) && ( $image_path['scheme'] !== $site_url['scheme'] ) ) {
                        $path = str_replace( $image_path['scheme'], $site_url['scheme'], $path );
                    }
                    
                    if ( 0 === strpos( $path, $dir['baseurl'] . '/' ) ) {
                        $path = substr( $path, strlen( $dir['baseurl'] . '/' ) );
                    }

                    $sql = $wpdb->prepare(
                        "SELECT post_id, meta_value FROM $wpdb->postmeta WHERE meta_key = '_wp_attached_file' AND meta_value = %s",
                        $path
                    );

                    $results = $wpdb->get_results( $sql );
                    $post_id = null;

                    if ( $results ) {
                        // Use the first available result, but prefer a case-sensitive match, if exists.
                        $post_id = reset( $results )->post_id;

                        if ( count( $results ) > 1 ) {
                            foreach ( $results as $result ) {
                                if ( $path === $result->meta_value ) {
                                    $post_id = $result->post_id;
                                    break;
                                }
                            }
                        }
                    }
                    
                    return $post_id;   
                    
                }
            
                
            
            /**
            * Restart the bufering if turned off already
            *             
            */
            function admin_print_footer_scripts()
                {
                    if ( ob_get_level() < 1 )
                        ob_start( array($this, 'ob_start_callback'));    
                }
                
                
            function log_save($text)
                {
                    
                    $myfile     = fopen(WPH_PATH . "/debug.txt", "a") or die("Unable to open file!");
                    $txt        =  $text   .   "\n";
                    fwrite($myfile, $txt);
                    fclose($myfile);   
                    
                }

            
        } 


?>