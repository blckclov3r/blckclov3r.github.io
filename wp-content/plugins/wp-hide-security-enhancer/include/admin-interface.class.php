<?php

    if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
    
    class WPH_interface
        {
            var $screen_slug;
            var $tab_slug;
            
            var $module;
            var $module_settings;
            var $interface_data;
            
            var $wph;
            var $functions;
                   
            function __construct()
                {
                    global $wph;
                    $this->wph          =   &$wph;
                    
                    $this->functions    =   new WPH_functions();
                    
                }

            
            function _setup_interface()
                {
                    
                    include ( WPH_PATH . '/include/admin-interfaces/_setup.php' );
                    
                }
            
                   
            function _render($interface_name)
                {
                    
                    $this->screen_slug  =   sanitize_text_field($_GET['page']);
                    $this->tab_slug     =   isset($_GET['component'])   ?   sanitize_text_field($_GET['component'])  :   FALSE;
     
                    //identify the module by slug
                    $this->module   =   $this->functions->get_module_by_slug($this->screen_slug);
                    
                    if(empty($this->tab_slug)   &&  $this->module->use_tabs  === true )
                        {
                            //get the first component
                            foreach($this->module->components   as  $module_component)
                                {
                                    if( ! $module_component->title)
                                        continue;
                                    
                                    $this->tab_slug =   $module_component->id;
                                    break;
                                }  
                            
                        }
                   
                    $this->_load_interface_data();
                    
                    $this->_do_pasive_actions();
   
                    $this->_generate_interface_html();
                    
                }
            
            function _load_interface_data()
                {
                    $this->module_settings  =   $this->functions->filter_settings(   $this->module->get_module_settings($this->tab_slug ));
                        
                    $this->interface_data   =   $this->module->get_interface_data();                      
                }
            
            
            function _do_pasive_actions()
                {
                    
                    if ( isset ( $_GET['wph_environment'] ) && $_GET['wph_environment'] == 'ignore-rewrite-test' )
                        update_option( 'wph-environment-ignore-rewrite-test', 'false' );
                    
                    
                }
                  
            function _generate_interface_html()
                {
                    
                    ?>
                        <div id="wph" class="wrap">
                            <h1><?php echo $this->interface_data['title'] ?></h1>
                         
                            <?php
                                
                                echo $this->functions->get_ad_banner();
                                
                                                                
                                $results    =   $this->functions->check_server_environment();
                                
                                if ( $results['found_issues'] !==  FALSE )
                                    {
                            
                                        ?>
                                        <div class="start-container title test">
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
                                        ?></div><?php
                                    }

                            ?>
                            
                            <div class="content<?php if( $results['critical_issues'] ) {echo (' something-wrong');} ?>">
                            
                                <?php
                                
                                if( $this->module->use_tabs  === true )
                                    $this->_generate_interface_tabs();
                                    
                                ?>
                            
                                <div id="poststuff">
                                    
                                    <?php if(!empty($this->interface_data['handle_title'])) { ?>
                                    <div class="postbox">
                                        <h3 class="handle"><?php echo $this->interface_data['handle_title'] ?></h3>
                                    </div>
                                    <?php } ?>
                                    
                                        <div class="inside">
                                               
                                            <form method="post" action="">
                                            <?php wp_nonce_field( 'wph/interface_fields', 'wph-interface-nonce' ); ?>
                                            
                                            <div class="options">
                                                <?php
                                                                                                
                                                    foreach($this->module_settings  as  $module_setting)
                                                        {
                                                            $this->_generate_module_html( $module_setting );    
                                                        }
                                                
                                                ?>
                                            </div>    
                                                   
                                                    <table class="wph_submit widefat">
                                                        <tbody>
                                                            <tr class="submit">
                                                                <td class="label">&nbsp;</td>
                                                                <td class="label">
                                                                    <input type="submit" value="<?php _e('Save',    'wp-hide-security-enhancer') ?>" class="button-primary alignright"> 
                                                                </td>    
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                            </form> 
                                        </div>
                                  
                                </div>
                            </div>                         
                        </div>
                  
                <?php   
                    
                }
                
                
            function _generate_module_html( $module_setting )
                {
                    
                    if(isset($module_setting['type'])   &&  $module_setting['type']    ==  'split' )
                        {
                            if (    ! empty ( $module_setting['label'] ) )
                                {
                                    ?>
                                    <div class="section_title"><?php echo $module_setting['label'] ?></div>
                                    <?php   
                                }
                                else
                                    {
                                        ?>
                                        <p>&nbsp;</p>
                                        <?php
                                    }
                            
                            return;
                        }
               
                        
                    if($module_setting['visible']   === FALSE)
                        return;
                        
                                        
                    $option_name    =   $module_setting['id'];
                    $value          =   $this->wph->get_setting_value(  $option_name, $module_setting );

                    
                    $is_advanced    =   ! empty ( $module_setting['advanced_option'] )  ?   TRUE    :   FALSE;
                    $hide_advanced  =   ( $is_advanced  &&  ( $value   ==  'no'    ||  empty ( $value ) )) ?    TRUE    :   FALSE;
                                        
                    ?>
                        <div class="postbox wph-postbox">
                            <table class="wph_input widefat">
                                <tbody>
                            
                                    <tr>
                                        <td class="label<?php if ( $is_advanced ) { echo ' advanced'; } ?>">
                                            <ul class="options">
                                                <?php if ( $module_setting['input_type'] != 'radio' ) { ?>
                                                <li><span class="dashicons dashicons-rest-api"          title='Generate random value for the field' onClick="WPH.randomWord( this, '<?php if  ( ! empty ($module_setting['help']['input_value_extension'])) { echo $module_setting['help']['input_value_extension']; }  ?>' )"></span></li>
                                                <li><span class="dashicons dashicons-admin-appearance"  title='Remove the field value'  onClick="WPH.clear( this )"></span></li>
                                                <?php } ?>
                                                <?php
                                                    
                                                    if ( $module_setting['help'] !==    FALSE   &&  ! empty( $module_setting['help']['option_documentation_url'] ))
                                                        {
                                                    
                                                ?>
                                                <li><a target="_blank" href="<?php echo $module_setting['help']['option_documentation_url'] ?>"><span class="dashicons dashicons-admin-links"       title='Open option help page'></span></a></li>
                                                <?php
                                                        }
                                                ?>
                                            </ul>
                                            <label for=""><?php echo $module_setting['label'] ?></label>
                                            <?php
                                                
                                                if(is_array($module_setting['description']))
                                                    {
                                                        foreach($module_setting['description']  as  $description)
                                                            {
                                                                ?>
                                                                    <div class="description"><?php echo nl2br($description) ?></div>
                                                                <?php
                                                            }    
                                                    }
                                                    else
                                                    {
                                                        ?>
                                                            <p class="description"><?php echo nl2br($module_setting['description']) ?></p>
                                                        <?php 
                                                    } ?>
                                                    
                                                <?php 
                                                
                                                    if  ( $is_advanced && $hide_advanced ) 
                                                        { 
                                                            ?>
                                                            <div class="advanced_notice">
                                                                <div class="icon">
                                                                    <img src="<?php echo WPH_URL ?>/assets/images/warning.png" />
                                                                </div>
                                                                <div class="text">
                                                                    <p> <?php  echo $module_setting['advanced_option']['description'] ?> </p>
                                                                </div>
                                                                <div class="actions">
                                                                    <a href="javascript: void(0)" onclick="WPH.showAdvanced( jQuery(this) )" class="button-primary">SHOW</a>    
                                                                </div>
                                                            </div>
                                                            
                                                            <?php
                                                        }
                                                    
                                                ?>
                                                
                                        </td>
                                    </tr>
                                    
                                    <tr class="entry<?php if  ( $is_advanced ) { echo ' advanced';} if  ( $hide_advanced ) { echo ' hide';  }   ?>"> 
                                        <td class="data">
                                            <?php if(!empty($module_setting['options_pre'])) { ?><div class="options_text text_pre"><?php echo $module_setting['options_pre'] ?></div><?php } ?>
                                            <?php if(!empty($module_setting['value_description'])) { ?><p class="description"><?php echo $module_setting['value_description'] ?></p><?php } ?>
                                            <?php
                                                
                                                switch($module_setting['input_type'])
                                                    {
                                                        case 'text' :
                                                                        $class          =   'text';
                                                                        
                                                                        ?><input name="<?php echo $module_setting['id'] ?>" class="<?php echo $class ?>" value="<?php echo esc_html($value) ?>" placeholder="<?php echo esc_html($module_setting['placeholder']) ?>" type="text"><?php
                                                                        
                                                                        break;
                                                                        
                                                        case 'textarea' :
                                                                            $class          =   'textarea';
                                                                            
                                                                            ?><textarea rows="7" name="<?php echo $module_setting['id'] ?>" class="<?php echo $class ?>"><?php echo stripslashes ( esc_html($value) ) ?></textarea><?php
                                                                            
                                                                            break;
                                                                        
                                                        case 'radio' :
                                                                        $class          =   'radio';
                                                                                                                                                                        
                                                                        ?>
                                                                        <fieldset>
                                                                            <?php  
                                                                            
                                                                                foreach($module_setting['options']  as  $option_value  =>  $option_title)
                                                                                    {
                                                                                        ?><label><input type="radio" class="<?php echo $class ?>" <?php checked($value, $option_value)  ?> value="<?php echo $option_value ?>" name="<?php echo $module_setting['id'] ?>"> <span><?php echo esc_html($option_title) ?></span></label><br /><?php
                                                                                    }
                                                                            
                                                                            ?>
                                                                        </fieldset>
                                                                        <?php
                                                                        
                                                                        break;    
                                                    }
                                                    
                                            ?>
                                            <?php if(!empty($module_setting['options_post'])) { ?><div class="options_text text_post"><?php echo $module_setting['options_post'] ?></div><?php } ?>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            
                            <div class="wph_help<?php  if ( $module_setting['help'] ===    FALSE ) { echo ' empty'; } ?>">
                                <div class="text">
                                <h4><?php echo $module_setting['help']['title'] ?></h3>
                                <?php  if ( $module_setting['help'] !==    FALSE ) { ?>
                                    <p><?php echo $module_setting['help']['description'] ?></p>
                                <?php } else { ?>
                                <p>There is no help available for this option.</p>
                                <?php }?>
                                </div>
                                
                            </div>
                            
                        </div>   
                    
                    <?php   
                    
                }
                        
                
            function _generate_interface_tabs()
                {
                    
                    ?> 
                    <h2 class="nav-tab-wrapper">
                        <?php
                            
                            //output all module components as tabs
                            foreach($this->module->components   as  $module_component)
                                {
                                    if( ! $module_component->title)
                                        continue;
                                    
                                    $class  =   '';
                                    if($module_component->id    ==  $this->tab_slug)
                                        $class  =   'nav-tab-active';
                                    
                                    ?>   
                                    <a href="<?php echo esc_url(admin_url( 'admin.php?page=' . $this->screen_slug . '&component=' . $module_component->id)); ?>" class="nav-tab <?php echo $class ?>"><?php echo $module_component->title ?></a>
                                    <?php                                    
                                }
                        
                        ?>
                    </h2>
                    <form id="reset_settings_form" action="<?php echo esc_url(admin_url( 'admin.php?page=wp-hide')) ?>" method="post" <?php
                                if($this->wph->server_htaccess_config    === FALSE && $this->wph->server_web_config   === FALSE) {echo (' class="disabled"');}
                            ?>>
                        <input type="hidden" name="reset-settings" value="true" />
                        <?php wp_nonce_field( 'wp-hide-reset-settings', '_wpnonce' ); ?>
                        
                        <input type="button" class="reset_settings button-secondary cancel alignright" value="Reset All Settings" onclick="wph_setting_reset_confirmation ();">
                        <script type='text/javascript'>
                            function wph_setting_reset_confirmation () 
                                {
                                    var agree   =   confirm(wph_vars.reset_confirmation);
                                    if (!agree)
                                        return false;
                                        
                                    document.getElementById("reset_settings_form").submit(); 
                                }
                            
                        </script>
                        
                    </form>
                    
                    <?php
                    
                }
        } 


?>