<?php   


    if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
    
    class WPH_setup_interface
        {
            
            var $wph;
            var $functions;
                   
            function __construct()
                {
                    global $wph;
                    $this->wph          =   &$wph;
                    
                    $this->functions    =   new WPH_functions();
                    
                    add_action( 'admin_init',       array ( $this, 'run_sample_setup') );
                    add_action( 'admin_init',       array ( $this, 'pasive_actions') );
                    add_action( 'admin_notice',     array ( $this, 'admin_notices' ) );  
                    
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
                                            'confirm_message' => __('Any existing options values will be overwritten, are you sure you want to continue?',    'wp-hide-security-enhancer')
                                        );
                    wp_localize_script( 'wph', 'wph_vars', $translation_array );
                    
                    wp_enqueue_script( 'wph'); 
                
                }
            
            
            
            function admin_notices()
                {
                    if( isset( $_GET['sample-setup-completed'] )    &&  $_GET['sample-setup-completed'] ==  'true' )
                        {
                                     
                            if( $found_errors   === FALSE )
                                echo "<div class='notice notice-success'><p>". __('Settings saved', 'wp-hide-security-enhancer')  ."<br />" .  __('Remember, site cache clear is required.', 'wp-hide-security-enhancer')  ."</p></div>";
                        }   
                    
                    
                }
            
            
            function pasive_actions()
                {
                    
                    if ( isset ( $_GET['wph_environment'] ) && $_GET['wph_environment'] == 'ignore-rewrite-test' )
                        update_option( 'wph-environment-ignore-rewrite-test', 'false' );
                            
                }
            
            function run_sample_setup()
                {   
                    if ( ! isset ( $_POST['wph-run-sample-setup'] ) )
                        return FALSE;
                    
                    $nonce  =   $_POST['wph-run-sample-setup-nonce'];
                    if ( ! wp_verify_nonce( $nonce, 'wph-run-sample-setup' ) )
                        return FALSE;
                    
                    //only for admins
                    If ( !  current_user_can ( 'manage_options' ) )
                        return FALSE;
                        
                    $_settings  =   array( 
                                            'new_theme_path'                =>  $this->functions->random_word(),
                                            'new_theme_child_path'          =>  $this->functions->random_word(),
                                            
                                            'new_include_path'              =>  $this->functions->random_word(),
                                            'new_content_path'              =>  $this->functions->random_word(),
                                            'new_plugin_path'               =>  $this->functions->random_word(),
                                            'new_upload_path'               =>  $this->functions->random_word(),
                                            'new_wp_comments_post'          =>  $this->functions->random_word() . ".php",
                                            'remove_generator_meta'         =>  'yes',
                                            'remove_other_generator_meta'   =>  'yes',
                                            'remove_wlwmanifest'            =>  'yes',
                                            'remove_header_link'            =>  'yes',
                                            'remove_html_comments'          =>  'yes'
                                            );
                    $this->wph->settings['module_settings']   =   $_settings;
                                                            
                    //generate a new write_check_string
                    $write_check_string  =   time() . '_' . mt_rand(100, 99999);
                    $this->wph->settings['write_check_string']   =   $write_check_string;
                                                                                   
                    //update the settings
                    $this->functions->update_settings($this->wph->settings);
                    
                    //trigger the settings changed action
                    do_action('wph/settings_changed', 'wp-hide', FALSE );
              
                    //redirect
                    $new_admin_url     =   $this->functions->get_module_item_setting('admin_url'  ,   'admin');
                    
                    //check if the rewrite applied
                    if ( ! empty ( $new_admin_url ) &&  ! $this->rewrite_rules_applied() )
                        $new_admin_url  =   '';
                    
                    if(!empty($new_admin_url)   &&  $this->is_permalink_enabled())
                        $new_location       =   trailingslashit(    home_url()  )   . $new_admin_url .  "/admin.php?page=wp-hide";
                        else
                        $new_location       =   trailingslashit(    site_url()  )   .  "wp-admin/admin.php?page=wp-hide";
                    
                    
                    $new_location   .=  '&sample-setup-completed=true';
                        
                    wp_redirect($new_location);
                    die();
                    
                }
            
            
            function _render()
                {
                    
                    //mark the section as being viwed
                    update_option( 'wph-first-view', 'false' );
                    
      
                          
                    ?>
                    <div id="wph" class="wrap">
                        <h1>WP Hide & Security Enhancer - <?php _e( "Setup", 'wp-hide-security-enhancer' ) ?></h1>
                                          
                        <?php echo $this->functions->get_ad_banner(); ?>
                        
                        <?php
                        
                            if( isset( $_GET['sample-setup-completed'] )    &&  $_GET['sample-setup-completed'] ==  'true' )
                                {
                                    ?>
                                        <div class="start-container title success">
                                            <h2><?php _e( "Sample Setup deployed !", 'wp-hide-security-enhancer' ) ?></h2>
                                        </div>
                                        <div class="container-description">
                                            <p><?php _e( "A basic plugin set-up has been deployed, to get you started. A site cache clear is required to ensure the updates are reflected on the front side", 'wp-hide-security-enhancer' ) ?>. </p>
                                            <p><?php _e( "Check with the front side to ensure everything is working. Further adjustments to other options are recommended", 'wp-hide-security-enhancer' ) ?>. </p>
                                        </div>
                                    
                                        <p><br /><br /><br /></p>
                                    <?php
                                }
                                          
                            $results    =   $this->functions->check_server_environment();
                            
                            ?>
                            <div class="start-container title test <?php if ( $found_issues ===  TRUE ) { echo ' warning';} ?>">
                                <h2><?php _e( "Checking your environment ..", 'wp-hide-security-enhancer' ) ?></h2>
                            </div>
                            <div class="container-description environment-notices">
                            <?php
                            
                            if ( $results['found_issues'] !==  FALSE )
                                {    
                                    echo $results['errors'];
                                }
                            
                            if ( $results['critical_issues'] ===  TRUE )
                                {    
                                    ?>
                                    <p class="framed"><span class="dashicons dashicons-warning error"></span> <?php _e('Critical issues were identified on your site, please fix them before proceeding with customizations.', 'wp-hide-security-enhancer') ?></p>
                                    <?php
                                }
                            
                            if ( $results['found_issues'] ===  FALSE )
                                {    
                                    ?>
                                    <p><span class="dashicons dashicons-plugins-checked"></span> <?php _e('No problems have been found on your server environment.', 'wp-hide-security-enhancer') ?></p>
                                    <?php
                                }
                        ?>
                            </div>               
                        <div class="start-container title">
                            <h2><?php _e( "Getting Started", 'wp-hide-security-enhancer' ) ?></h2>
                        </div>
                        <div class="container-description">
                            <p><b>WP Hide & Security Enhancer</b> <?php _e( "plugin helps to hide your WordPress, theme, and plugins", 'wp-hide-security-enhancer' ) ?>. <?php _e( "This improves the site security as hackers' boots can't exploit the vulnerabilities of your site, as not being aware of the user code", 'wp-hide-security-enhancer' ) ?>. <?php _e( "Daily, more vulnerabilities are found", 'wp-hide-security-enhancer' ) ?> <a href="https://wpvulndb.com/" target="_blank">WPVulndb.com/</a>, <?php _e( "but using WP Hide & Security Enhancer you will be perfectly safe", 'wp-hide-security-enhancer' ) ?> !</p>

                        </div> 
                        
                        <div class="start-container title help">
                            <h2><?php _e( "Recovery", 'wp-hide-security-enhancer' ) ?></h2>
                        </div> 
                        <div class="container-description">
                        <?php $this->functions->show_recovery() ?>
                        </div>
                                                   
                        <div class="start-container title info">
                            <h2><?php _e( "Basic functionality", 'wp-hide-security-enhancer' ) ?></h2>
                        </div>
                        <div class="container-description">
                            <p><?php _e( "The basic principle of the plugin is to change default assets URLs, remove or change specific HTML elements, and disable unused services. This makes WordPress unrecognizable. The process isn't automated, so it needs to be done manually while getting feedback on the front side to ensure everything is still functional. No file and directory are being changed anywhere, everything is processed on the fly using output buffering and filters", 'wp-hide-security-enhancer' ) ?>..</p>

                            <p><?php _e( "A default directory structure for WordPress appears like this on outputted HTML", 'wp-hide-security-enhancer' ) ?>:<br />
                            https://--domain--<span class="highlight">/wp-includes/</span>css/dashicons.min.css  &nbsp;&nbsp;&nbsp;&nbsp;or &nbsp;&nbsp;&nbsp;&nbsp;  https://--domain--<span class="highlight">/wp-content/</span>themes/pub/wporg-plugins/css/style.css
                            <br /><?php _e( "So the most important first step, is to change the default /wp-content/ and /wp-includes/ to something else. This can be done at", 'wp-hide-security-enhancer' ) ?> <a href="admin.php?page=wp-hide-rewrite&component=wp-includes">WP Includes</a> <?php _e( "and", 'wp-hide-security-enhancer' ) ?> <a href="admin.php?page=wp-hide-rewrite&component=wp-content">WP Content</a>
                            <br /><?php _e( "Further adjustments to", 'wp-hide-security-enhancer' ) ?> <a href="admin.php?page=wp-hide-rewrite&component=theme">Theme</a>, <a href="admin.php?page=wp-hide-rewrite&component=plugins">Plugins</a>, <a href="admin.php?page=wp-hide-rewrite&component=uploads">Uploads</a> and <a href="admin.php?page=wp-hide-admin">Admin</a> <?php _e( "can be applied", 'wp-hide-security-enhancer' ) ?>.</p>
                            <p></p>
                            <p><?php _e( "At this point, for all simple sites, the WordPress CMS is already hidden. If using complex themes and plugins, further plugin options adjustments are necessarily or PRO might be required ( for example", 'wp-hide-security-enhancer' ) ?> <a href="https://www.wp-hide.com/how-to-easily-hide-elementor-page-builder/" target="_blank"><?php _e( "hiding Elementor", 'wp-hide-security-enhancer' ) ?></a>, Divi, visual builder etc).

                            <br /><?php _e( "Take the time to understand each option, there are in-line additional help also external description to each of the features. If need additional assistance, use the support forum or contact us. Full description of each option can be found at", 'wp-hide-security-enhancer' ) ?> <a href="https://www.wp-hide.com/documentation_category/plugin-options-explained/" target="_blank"><?php _e( "Documentation", 'wp-hide-security-enhancer' ) ?></a>. </p>

                        
                        </div>
                        
                        <div class="start-container title setup">
                            <h2><?php _e( "Sample setup", 'wp-hide-security-enhancer' ) ?></h2>
                        </div>
                        <div class="container-description">
                            <p><?php _e( "This creates a simple setup to get you started. The procedure activates some of the basic plugin options.  All options will be reset and any existing values will be overwritten, so use with caution.  After the procedure, for all basic sites, the WordPress CMS will be already hidden, otherwise further options adjustments will be necessary", 'wp-hide-security-enhancer' ) ?>.</p>
                            <form id="wph-run-sample-setup" method="post" action="admin.php?page=wp-hide">
                                <p><a href="javascript: void(0)" onclick="return WPH.confirm_sample_setup();" class="button-primary"><?php _e( "Run Sample Setup", 'wp-hide-security-enhancer' ) ?></a></p>
                                
                                <input type="hidden" name="wph-run-sample-setup" value="true" />
                                <input type="hidden" name="wph-run-sample-setup-nonce" value="<?php echo wp_create_nonce( 'wph-run-sample-setup' ) ?>" />
                            </form>
                        </div>
                        
                                                
                        <div class="start-container">
                            <div class="text">
                         
                                <h2><?php _e('Demo Video', 'wp-hide-security-enhancer') ?></h2>
                                <p>Sample video on how to fill options and check the changes on outputted HTML.</p>
                                
                                <div id="wph-video" style="max-width: 800px">
                                    <div style="padding:56.25% 0 0 0;position:relative;">
                                        <iframe src="https://player.vimeo.com/video/192011678?title=0&byline=0&portrait=0" style="position:absolute;top:0;left:0;width:100%;height:100%;" frameborder="0" allow="autoplay; fullscreen" allowfullscreen></iframe>
                                    </div>
                                </div>
                                <script src="https://player.vimeo.com/api/player.js"></script>
                                
                            
                            
                            </div>                     
                        </div>

                    <?php
                }
                
        }


?>