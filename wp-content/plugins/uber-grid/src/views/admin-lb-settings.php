<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
$lightbox_options_nonce = wp_create_nonce( 'pfhub_portfolio_nonce_save_lightbox_options' );
$portfolio_defaultoptions = \PfhubPortfolio\Helpers\GridHelper::getDefaultSettings();
?>
<div class="wrap">
    <?php require(PFHUB_PORTFOLIO_TEMPLATES_PATH.DIRECTORY_SEPARATOR.'admin-licensing-banner.php');?>
    <div style="margin-left: -20px;position: relative" id="poststuff">
        <p class="pro_info">
            <?php _e('Get these options after upgrading to pro version.', 'pfhub_portfolio' ); ?>
            <a href="https://portfoliohub.io/" target="_blank" class="pfhub_portfolio_button">Upgrade!</a>
        </p>
        <form
            action="admin.php?page=Options_portfolio_lightbox_styles&task=save&pfhub_portfolio_nonce_save_lightbox_options=<?php echo $lightbox_options_nonce; ?>"
            method="post" id="adminForm" name="adminForm">

            <ul id="lightbox_type">
                <li>
                    <label for="modern" <?php if ( get_option('pfhub_portfolio_lightbox_type') == 'modern' ) {	echo 'class="active"';	} ?>>Modern
                        <input type="checkbox" name="params[pfhub_portfolio_lightbox_type]" id="modern" <?php if ( get_option('pfhub_portfolio_lightbox_type') == 'modern' ) {	echo 'checked="checked"';	} ?> value="modern">
                    </label>
                </li>
                <li>
                    <label for="classic" <?php if ( get_option('pfhub_portfolio_lightbox_type') == 'classic' ) {	echo 'class="active"';	} ?>>Classic
                        <input type="checkbox" name="params[pfhub_portfolio_lightbox_type]" id="classic" <?php if ( get_option('pfhub_portfolio_lightbox_type') == 'classic' ) {echo 'checked="checked"';} ?>  value="classic">
                    </label>
                </li>
                <a onclick="document.getElementById('adminForm').submit()" style="margin-left: 20px;"
                   class="save-portfolio-options pfhub_portfolio_button"><?php echo __( 'Save', 'pfhub_portfolio' ); ?></a>
            </ul>

            <div id="lightbox-options-list" class="unique-type-options-wrapper <?php if ( get_option('pfhub_portfolio_lightbox_type') == 'classic' ) {echo "active";} ?>">
                <div class="lightbox-options-block portfolio_lightbox_options_grey_overlay">
                    <h3><?php echo __( 'Internationalization', 'pfhub_portfolio' ); ?> <img src="<?php echo PFHUB_PORTFOLIO_IMAGES_URL.'/admin/portfolio-pro-icon.png'; ?>" class="pfhub_portfolio_lightbox_pro_logo"></h3>
                    <?php include_once( PFHUB_PORTFOLIO_ADM_PATH . 'includes/plugin.php' );
                    ?>
                    <div class="has-background">
                        <label for="light_box_style"><?php echo __( 'Lightbox style', 'pfhub_portfolio' ); ?></label>
                        <select id="light_box_style" name="params[pfhub_portfolio_light_box_style]">
                            <option 'selected="selected">1</option>
                            <option <?php if ( $pfhub_portfolio_get_option['pfhub_portfolio_light_box_style'] == '2' ) {
                                echo 'selected="selected"';
                            } ?> value="2">2
                            </option>
                            <option <?php if ( $pfhub_portfolio_get_option['pfhub_portfolio_light_box_style'] == '3' ) {
                                echo 'selected="selected"';
                            } ?> value="3">3
                            </option>
                            <option <?php if ( $pfhub_portfolio_get_option['pfhub_portfolio_light_box_style'] == '4' ) {
                                echo 'selected="selected"';
                            } ?> value="4">4
                            </option>
                            <option <?php if ( $pfhub_portfolio_get_option['pfhub_portfolio_light_box_style'] == '5' ) {
                                echo 'selected="selected"';
                            } ?> value="5">5
                            </option>
                        </select>
                    </div>
                    <?php ?>
                    <div>
                        <label for="light_box_transition"><?php echo __( 'Transition type', 'pfhub_portfolio' ); ?></label>
                        <select id="light_box_transition" name="params[pfhub_portfolio_light_box_transition]">
                            <option <?php if ( $pfhub_portfolio_get_option['pfhub_portfolio_light_box_transition'] == 'elastic' ) {
                                echo 'selected="selected"';
                            } ?> value="elastic"><?php echo __( 'Elastic', 'pfhub_portfolio' ); ?></option>
                            <option <?php if ( $pfhub_portfolio_get_option['pfhub_portfolio_light_box_transition'] == 'fade' ) {
                                echo 'selected="selected"';
                            } ?> value="fade"><?php echo __( 'Fade', 'pfhub_portfolio' ); ?></option>
                            <option <?php if ( $pfhub_portfolio_get_option['pfhub_portfolio_light_box_transition'] == 'none' ) {
                                echo 'selected="selected"';
                            } ?> value="none"><?php echo __( 'None', 'pfhub_portfolio' ); ?></option>
                        </select>
                    </div>
                    <div class="has-background">
                        <label for="light_box_speed"><?php echo __( 'Opening speed', 'pfhub_portfolio' ); ?></label>
                        <input type="number" name="params[pfhub_portfolio_light_box_speed]" id="light_box_speed"
                               value="800"
                               class="text">
                        <span>ms</span>
                    </div>
                    <div>
                        <label for="light_box_fadeout"><?php echo __( 'Closing speed', 'pfhub_portfolio' ); ?></label>
                        <input type="number" name="params[pfhub_portfolio_light_box_fadeout]" id="light_box_fadeout"
                               value="300"
                               class="text">
                        <span>ms</span>
                    </div>
                    <div class="has-background">
                        <label for="light_box_title"><?php echo __( 'Show the title', 'pfhub_portfolio' ); ?></label>
                        <input type="hidden" value="false" name="params[pfhub_portfolio_light_box_title]"/>
                        <input type="checkbox"
                               id="light_box_title"  name="params[pfhub_portfolio_light_box_title]" value="true"/>
                    </div>
                    <div>
                        <label for="light_box_opacity"><?php echo __( 'Overlay transparency', 'pfhub_portfolio' ); ?></label>
                        <div class="slider-container">
                            <input name="params[pfhub_portfolio_light_box_opacity]" id="light_box_opacity"
                                   data-slider-highlight="true" data-slider-values="0,10,20,30,40,50,60,70,80,90,100"
                                   type="text"
                                   data-slider="true"
                                   value=""/>
                            <span>%</span>
                        </div>
                    </div>
                    <div class="has-background">
                        <label for="light_box_open"><?php echo __( 'Auto open', 'pfhub_portfolio' ); ?></label>
                        <input type="hidden" value="false" name="params[pfhub_portfolio_light_box_open]"/>
                        <input type="checkbox"
                               id="light_box_open" name="params[pfhub_portfolio_light_box_open]" value="true"/>
                    </div>
                    <div>
                        <label
                            for="light_box_overlayclose"></label>
                        <input type="hidden" value="false" name="params[pfhub_portfolio_light_box_overlayclose]"/>
                        <input type="checkbox" id="light_box_overlayclose" checked="checked" name="params[pfhub_portfolio_light_box_overlayclose]" value="true"/>
                    </div>
                    <div class="has-background">
                        <label for="light_box_esckey"><?php echo __( 'EscKey close', 'pfhub_portfolio' ); ?></label>
                        <input type="hidden" value="false" name="params[pfhub_portfolio_light_box_esckey]"/>
                        <input type="checkbox"
                               id="light_box_esckey" name="params[pfhub_portfolio_light_box_esckey]" value="true"/>
                    </div>
                    <div>
                        <label for="light_box_arrowkey"><?php echo __( 'Keyboard navigation', 'pfhub_portfolio' ); ?></label>
                        <input type="hidden" value="false" name="params[pfhub_portfolio_light_box_arrowkey]"/>
                        <input type="checkbox"
                               id="light_box_arrowkey" name="params[pfhub_portfolio_light_box_arrowkey]" value="true"/>
                    </div>
                    <div class="has-background">
                        <label for="light_box_loop"><?php echo __( 'Loop content', 'pfhub_portfolio' ); ?></label>
                        <input type="hidden" value="false" name="params[pfhub_portfolio_light_box_loop]"/>
                        <input type="checkbox"
                               id="light_box_loop"  checked="checked" name="params[pfhub_portfolio_light_box_loop]" value="true"/>
                    </div>
                    <div>
                        <label for="light_box_closebutton"><?php echo __( 'Show close button', 'pfhub_portfolio' ); ?></label>
                        <input type="hidden" value="false" name="params[pfhub_portfolio_light_box_closebutton]"/>
                        <input type="checkbox"
                               id="light_box_closebutton" name="params[pfhub_portfolio_light_box_closebutton]" value="true"/>
                    </div>
                </div>
                <div class="lightbox-options-block portfolio_lightbox_options_grey_overlay">
                    <h3><?php echo __( 'Dimensions', 'pfhub_portfolio' ); ?><img src="<?php echo PFHUB_PORTFOLIO_IMAGES_URL.'/admin/portfolio-pro-icon.png'; ?>" class="pfhub_portfolio_lightbox_pro_logo"></h3>

                    <div class="has-background">
                        <label for="light_box_size_fix"><?php echo __( 'Popup size fix', 'pfhub_portfolio' ); ?></label>
                        <input type="hidden" value="false" name="params[pfhub_portfolio_light_box_size_fix]"/>
                        <input type="checkbox"
                               id="light_box_size_fix"  name="params[pfhub_portfolio_light_box_size_fix]" value="true"/>
                    </div>

                    <div class="fixed-size">
                        <label for="light_box_width"><?php echo __( 'Popup width', 'pfhub_portfolio' ); ?></label>
                        <input type="number" name="params[pfhub_portfolio_light_box_width]" id="light_box_width"
                               value="769"
                               class="text">
                        <span>px</span>
                    </div>

                    <div class="has-background fixed-size">
                        <label for="light_box_height"><?php echo __( 'Popup height', 'pfhub_portfolio' ); ?></label>
                        <input type="number" name="params[pfhub_portfolio_light_box_height]" id="light_box_height"
                               value="500"
                               class="text">
                        <span>px</span>
                    </div>

                    <div class="not-fixed-size">
                        <label for="light_box_maxwidth"><?php echo __( 'Popup maxWidth', 'pfhub_portfolio' ); ?></label>
                        <input type="number" name="params[pfhub_portfolio_light_box_maxwidth]" id="light_box_maxwidth"
                               value="300"
                               class="text">
                        <span>px</span>
                    </div>
                    <div class="has-background not-fixed-size">
                        <label for="light_box_maxheight"><?php echo __( 'Popup maxHeight', 'pfhub_portfolio' ); ?></label>
                        <input type="number" name="params[pfhub_portfolio_light_box_maxheight]" id="light_box_maxheight"
                               value="100"
                               class="text">
                        <span>px</span>
                    </div>
                    <div>
                        <label
                            for="light_box_initialwidth"><?php echo __( 'Popup initial width', 'pfhub_portfolio' ); ?></label>
                        <input type="number" name="params[pfhub_portfolio_light_box_initialwidth]" id="light_box_initialwidth"
                               value=""
                               class="text">
                        <span>px</span>
                    </div>
                    <div class="has-background">
                        <label
                            for="light_box_initialheight"><?php echo __( 'Popup initial height', 'pfhub_portfolio' ); ?></label>
                        <input type="number" name="params[pfhub_portfolio_light_box_initialheight]"
                               id="light_box_initialheight"
                               value=""
                               class="text">
                        <span>px</span>
                    </div>
                </div>
                <div class="lightbox-options-block portfolio_lightbox_options_grey_overlay">
                    <h3>Slideshow <img src="<?php echo PFHUB_PORTFOLIO_IMAGES_URL.'/admin/portfolio-pro-icon.png'; ?>" class="pfhub_portfolio_lightbox_pro_logo"></h3>

                    <div class="has-background">
                        <label for="light_box_slideshow"><?php echo __( 'Slideshow', 'pfhub_portfolio' ); ?></label>
                        <input type="hidden" value="false" name="params[pfhub_portfolio_light_box_slideshow]"/>
                        <input type="checkbox"
                               id="light_box_slideshow" name="params[pfhub_portfolio_light_box_slideshow]" value="true"/>
                    </div>
                    <div>
                        <label
                            for="light_box_slideshowspeed"><?php echo __( 'Slideshow interval', 'pfhub_portfolio' ); ?></label>
                        <input type="number" name="params[pfhub_portfolio_light_box_slideshowspeed]"
                               id="light_box_slideshowspeed"
                               value="2500"
                               class="text">
                        <span>ms</span>
                    </div>
                    <div class="has-background">
                        <label
                            for="light_box_slideshowauto"><?php echo __( 'Slideshow auto start', 'pfhub_portfolio' ); ?></label>
                        <input type="hidden" value="false" name="params[pfhub_portfolio_light_box_slideshowauto]"/>
                        <input type="checkbox"
                               id="light_box_slideshowauto" checked="checked" name="params[pfhub_portfolio_light_box_slideshowauto]" value="true"/>
                    </div>
                    <div>
                        <label
                            for="light_box_slideshowstart"><?php echo __( 'Slideshow start button text', 'pfhub_portfolio' ); ?></label>
                        <input type="text" name="params[pfhub_portfolio_light_box_slideshowstart]"
                               id="light_box_slideshowstart"
                               value="Start slideshow"
                               class="text">
                    </div>
                    <div class="has-background">
                        <label
                            for="light_box_slideshowstop"><?php echo __( 'Slideshow stop button text', 'pfhub_portfolio' ); ?></label>
                        <input type="text" name="params[pfhub_portfolio_light_box_slideshowstop]" id="light_box_slideshowstop"
                               value="Stop slideshow"
                               class="text">
                    </div>
                </div>
                <div class="lightbox-options-block portfolio_lightbox_options_grey_overlay">
                    <h3>Positioning <img src="<?php echo PFHUB_PORTFOLIO_IMAGES_URL.'/admin/portfolio-pro-icon.png'; ?>" class="pfhub_portfolio_lightbox_pro_logo"></h3>

                    <div class="has-background">
                        <label for="light_box_fixed"><?php echo __( 'Fixed position', 'pfhub_portfolio' ); ?></label>
                        <input type="hidden" value="false" name="params[pfhub_portfolio_light_box_fixed]"/>
                        <input type="checkbox"
                               id="light_box_fixed" checked="checked" name="params[pfhub_portfolio_light_box_fixed]" value="true"/>
                    </div>
                    <div class="has-height">
                        <label for=""><?php echo __( 'Popup position', 'pfhub_portfolio' ); ?></label>
                        <div>
                            <table class="bws_position_table">
                                <tbody>
                                <tr>
                                    <td><input type="radio" value="1" id="slideshow_title_top-left"
                                               name="params[pfhub_portfolio_slider_title_position]" /></td>
                                    <td><input type="radio" value="2" id="slideshow_title_top-center"
                                               name="params[pfhub_portfolio_slider_title_position]" /></td>
                                    <td><input type="radio" value="3" id="slideshow_title_top-right"
                                               name="params[pfhub_portfolio_slider_title_position]" /></td>
                                </tr>
                                <tr>
                                    <td><input type="radio" value="4" id="slideshow_title_middle-left"
                                               name="params[pfhub_portfolio_slider_title_position]"  /></td>
                                    <td><input type="radio" value="5" id="slideshow_title_middle-center"
                                               name="params[pfhub_portfolio_slider_title_position]"checked="checked" /></td>
                                    <td><input type="radio" value="6" id="slideshow_title_middle-right"
                                               name="params[pfhub_portfolio_slider_title_position]"  /></td>
                                </tr>
                                <tr>
                                    <td><input type="radio" value="7" id="slideshow_title_bottom-left"
                                               name="params[pfhub_portfolio_slider_title_position]" </></td>
                                    <td><input type="radio" value="8" id="slideshow_title_bottom-center"
                                               name="params[pfhub_portfolio_slider_title_position]" /></td>
                                    <td><input type="radio" value="9" id="slideshow_title_bottom-right"
                                               name="params[pfhub_portfolio_slider_title_position]" /></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div id="new-lightbox-options-list" class="unique-type-options-wrapper <?php if ( get_option('pfhub_portfolio_lightbox_type') == 'modern' ) {echo "active";} ?>">
                <div class="portfolio_lightbox_options_grey_overlay"></div>
                <div class="lightbox-options-block">
                    <h3>General Options </h3>
                    <div class="has-background">
                        <label for="pfhub_portfolio_lightbox_lightboxView">Lightbox style
                            <div class="help">?
                                <div class="help-block">
                                    <span class="pnt"></span>
                                    <p>Choose the style of your popup</p>
                                </div>
                            </div>
                        </label>
                        <select id="pfhub_portfolio_lightbox_lightboxView" name="params[pfhub_portfolio_lightbox_lightboxView]">
                            <option <?php selected( 'view1', get_option('pfhub_portfolio_lightbox_lightboxView') ); ?>
                                value="view1">1
                            </option>
                            <option <?php selected( 'view2', get_option('pfhub_portfolio_lightbox_lightboxView') ); ?>
                                value="view2">2
                            </option>
                            <option <?php selected( 'view3', get_option('pfhub_portfolio_lightbox_lightboxView') ); ?>
                                value="view3">3
                            </option>
                            <option <?php selected( 'view4', get_option('pfhub_portfolio_lightbox_lightboxView') ); ?>
                                value="view4">4
                            </option>
                            <option <?php selected( 'view5', get_option('pfhub_portfolio_lightbox_lightboxView') ); ?>
                                value="view5">5
                            </option>
                            <option <?php selected( 'view6', get_option('pfhub_portfolio_lightbox_lightboxView') ); ?>
                                value="view6">6
                            </option>
                        </select>
                    </div>
                    <div>
                        <label for="pfhub_portfolio_lightbox_speed_new">Lightbox open speed
                            <div class="help">?
                                <div class="help-block">
                                    <span class="pnt"></span>
                                    <p>Set lightbox opening speed</p>
                                </div>
                            </div>
                        </label>
                        <input type="number" name="params[pfhub_portfolio_lightbox_speed_new]" id="pfhub_portfolio_lightbox_speed_new"
                               value="<?php echo get_option('pfhub_portfolio_lightbox_speed_new'); ?>"
                               class="text">
                        <span>ms</span>
                    </div>
                    <div class="has-background">
                        <label for="pfhub_portfolio_lightbox_overlayClose_new">Overlay close
                            <div class="help">?
                                <div class="help-block">
                                    <span class="pnt"></span>
                                    <p>Check to enable close by Esc key.</p>
                                </div>
                            </div>
                        </label>
                        <input type="hidden" value="false" name="params[pfhub_portfolio_lightbox_overlayClose_new]"/>
                        <input type="checkbox"
                               id="pfhub_portfolio_lightbox_overlayClose_new" <?php if ( get_option('pfhub_portfolio_lightbox_overlayClose_new') == 'true' ) {
                            echo 'checked="checked"';
                        } ?> name="params[pfhub_portfolio_lightbox_overlayClose_new]" value="true"/>
                    </div>
                    <div>
                        <label for="pfhub_portfolio_lightbox_style">Loop content
                            <div class="help">?
                                <div class="help-block">
                                    <span class="pnt"></span>
                                    <p>Check to enable repeating images after one cycle.</p>
                                </div>
                            </div>
                        </label>
                        <input type="hidden" value="false" name="params[pfhub_portfolio_lightbox_loop_new]"/>
                        <input type="checkbox"
                               id="pfhub_portfolio_lightbox_loop_new" <?php if ( get_option('pfhub_portfolio_lightbox_loop_new') == 'true' ) {
                            echo 'checked="checked"';
                        } ?> name="params[pfhub_portfolio_lightbox_loop_new]" value="true"/>
                    </div>
                </div>
        </form>
        <div class="lightbox-options-block portfolio_lightbox_options_grey_overlay">
            <h3>Dimensions<img src="<?php echo PFHUB_PORTFOLIO_IMAGES_URL.'/admin/portfolio-pro-icon.png'; ?>"
                               class="pfhub_portfolio_lightbox_pro_logo"></h3>
            <div class="has-background">
                <label for="pfhub_portfolio_lightbox_width_new">Lightbox Width
                    <div class="help">?
                        <div class="help-block">
                            <span class="pnt"></span>
                            <p>Set the width of the popup in percentages.</p>
                        </div>
                    </div>
                </label>
                <input type="number"
                       value="<?php echo $portfolio_defaultoptions['pfhub_portfolio_lightbox_width_new']; ?>"
                       class="text">
                <span>%</span>
            </div>
            <div>
                <label for="pfhub_portfolio_lightbox_height_new">Lightbox Height
                    <div class="help">?
                        <div class="help-block">
                            <span class="pnt"></span>
                            <p>Set the height of the popup in percentages.</p>
                        </div>
                    </div>
                </label>
                <input type="number"
                       value="<?php echo $portfolio_defaultoptions['pfhub_portfolio_lightbox_height_new']; ?>"
                       class="text">
                <span>%</span>
            </div>
            <div class="has-background">
                <label for="pfhub_portfolio_lightbox_videoMaxWidth">Lightbox Video maximum width
                    <div class="help">?
                        <div class="help-block">
                            <span class="pnt"></span>
                            <p>Set the maximum width of the popup in pixels, the height will be fixed automatically.</p>
                        </div>
                    </div>
                </label>
                <input type="number"
                       value="<?php echo $portfolio_defaultoptions['pfhub_portfolio_lightbox_videoMaxWidth']; ?>"
                       class="text">
                <span>px</span>
            </div>
        </div>
        <div class="lightbox-options-block portfolio_lightbox_options_grey_overlay">
            <h3>Slideshow<img src="<?php echo PFHUB_PORTFOLIO_IMAGES_URL.'/admin/portfolio-pro-icon.png'; ?>"
                              class="pfhub_portfolio_lightbox_pro_logo"></h3>
            <div class="has-background">
                <label for="pfhub_portfolio_lightbox_slideshow_new">Slideshow
                    <div class="help">?
                        <div class="help-block">
                            <span class="pnt"></span>
                            <p>Set the width of popup</p>
                        </div>
                    </div>
                </label>
                <input type="hidden" value="false" name="params[pfhub_portfolio_lightbox_slideshow_new]"/>
                <input type="checkbox"
                       id="pfhub_portfolio_lightbox_slideshow_new" <?php if ( $portfolio_defaultoptions['pfhub_portfolio_lightbox_slideshow_new'] == 'true' ) {
                    echo 'checked="checked"';
                } ?> name="params[pfhub_portfolio_lightbox_slideshow_new]" value="true"/>
            </div>
            <div>
                <label for="pfhub_portfolio_lightbox_slideshow_auto_new">Slideshow auto start
                    <div class="help">?
                        <div class="help-block">
                            <span class="pnt"></span>
                            <p>Set the width of popup</p>
                        </div>
                    </div>
                </label>
                <input type="hidden" value="false" name="params[pfhub_portfolio_lightbox_slideshow_auto_new]"/>
                <input type="checkbox"
                       id="pfhub_portfolio_lightbox_slideshow_auto_new" <?php if ( $portfolio_defaultoptions['pfhub_portfolio_lightbox_slideshow_auto_new'] == 'true' ) {
                    echo 'checked="checked"';
                } ?> name="params[pfhub_portfolio_lightbox_slideshow_auto_new]" value="true"/>
            </div>
            <div class="has-background">
                <label for="pfhub_portfolio_lightbox_slideshow_speed_new">Slideshow interval
                    <div class="help">?
                        <div class="help-block">
                            <span class="pnt"></span>
                            <p>Set the height of popup</p>
                        </div>
                    </div>
                </label>
                <input type="number" name="params[pfhub_portfolio_lightbox_slideshow_speed_new]"
                       id="pfhub_portfolio_lightbox_slideshow_speed_new"
                       value="<?php echo $portfolio_defaultoptions['pfhub_portfolio_lightbox_slideshow_speed_new']; ?>"
                       class="text">
                <span>ms</span>
            </div>
        </div>
        <div class="lightbox-options-block portfolio_lightbox_options_grey_overlay" style=" margin-top: -150px;">
            <h3>Advanced Options<img src="<?php echo PFHUB_PORTFOLIO_IMAGES_URL.'/admin/portfolio-pro-icon.png'; ?>"
                                     class="pfhub_portfolio_lightbox_pro_logo"></h3>
            <div class="has-background">
                <label for="pfhub_portfolio_lightbox_style">EscKey close
                    <div class="help">?
                        <div class="help-block">
                            <span class="pnt"></span>
                            <p>Choose the style of your popup</p>
                        </div>
                    </div>
                </label>
                <input type="checkbox"
                       id="pfhub_portfolio_lightbox_escKey_new"/>
            </div>
            <div>
                <label for="pfhub_portfolio_lightbox_keyPress_new">Keyboard navigation
                    <div class="help">?
                        <div class="help-block">
                            <span class="pnt"></span>
                            <p>Choose the style of your popup</p>
                        </div>
                    </div>
                </label>
                <input type="checkbox"
                       id="pfhub_portfolio_lightbox_keyPress_new"/>
            </div>
            <div class="has-background">
                <label for="pfhub_portfolio_lightbox_arrows">Show Arrows
                    <div class="help">?
                        <div class="help-block">
                            <span class="pnt"></span>
                            <p>Choose the style of your popup</p>
                        </div>
                    </div>
                </label>
                <input type="checkbox"
                       id="pfhub_portfolio_lightbox_arrows" checked/>
            </div>
            <div>
                <label for="pfhub_portfolio_lightbox_mouseWheel">Mouse Wheel Navigaion
                    <div class="help">?
                        <div class="help-block">
                            <span class="pnt"></span>
                            <p>Choose the style of your popup</p>
                        </div>
                    </div>
                </label>
                <input type="checkbox"
                       id="pfhub_portfolio_lightbox_mouseWheel" />
            </div>
            <div class="has-background">
                <label for="pfhub_portfolio_lightbox_download">Show Download Button
                    <div class="help">?
                        <div class="help-block">
                            <span class="pnt"></span>
                            <p>Choose the style of your popup</p>
                        </div>
                    </div>
                </label>
                <input type="checkbox"
                       id="pfhub_portfolio_lightbox_download" />
            </div>
            <div>
                <label for="pfhub_portfolio_lightbox_showCounter">Show Counter
                    <div class="help">?
                        <div class="help-block">
                            <span class="pnt"></span>
                            <p>Choose the style of your popup</p>
                        </div>
                    </div>
                </label>
                <input type="checkbox"
                       id="pfhub_portfolio_lightbox_showCounter" />
            </div>
            <div class="has-background">
                <label for="pfhub_portfolio_lightbox_sequence_info">Sequence Info text
                    <div class="help">?
                        <div class="help-block">
                            <span class="pnt"></span>
                            <p>Choose the style of your popup</p>
                        </div>
                    </div>
                </label>
                <input type="text"
                       style="width: 13%"
                       value="<?php echo $portfolio_defaultoptions['pfhub_portfolio_lightbox_sequence_info']; ?>"
                       class="text">
                X <input type="text"
                         style="width: 13%"
                         value="<?php echo $portfolio_defaultoptions['pfhub_portfolio_lightbox_sequenceInfo']; ?>"
                         class="text">
                XX
            </div>
            <div class="has-background">
                <label for="pfhub_portfolio_lightbox_slideAnimationType">Transition type
                    <div class="help">?
                        <div class="help-block">
                            <span class="pnt"></span>
                            <p>Choose the style of your popup</p>
                        </div>
                    </div>
                </label>
                <select id="pfhub_portfolio_lightbox_slideAnimationType" >
                    <option <?php selected( 'effect_1', $portfolio_defaultoptions['pfhub_portfolio_lightbox_slideAnimationType'] ); ?>
                        value="effect_1">Effect 1
                    </option>
                    <option <?php selected( 'effect_2', $portfolio_defaultoptions['pfhub_portfolio_lightbox_slideAnimationType'] ); ?>
                        value="effect_2">Effect 2
                    </option>
                    <option <?php selected( 'effect_3', $portfolio_defaultoptions['pfhub_portfolio_lightbox_slideAnimationType'] ); ?>
                        value="effect_3">Effect 3
                    </option>
                    <option <?php selected( 'effect_4', $portfolio_defaultoptions['pfhub_portfolio_lightbox_slideAnimationType'] ); ?>
                        value="effect_4">Effect 4
                    </option>
                    <option <?php selected( 'effect_5', $portfolio_defaultoptions['pfhub_portfolio_lightbox_slideAnimationType'] ); ?>
                        value="effect_5">Effect 5
                    </option>
                    <option <?php selected( 'effect_6', $portfolio_defaultoptions['pfhub_portfolio_lightbox_slideAnimationType'] ); ?>
                        value="effect_6">Effect 6
                    </option>
                    <option <?php selected( 'effect_7', $portfolio_defaultoptions['pfhub_portfolio_lightbox_slideAnimationType'] ); ?>
                        value="effect_7">Effect 7
                    </option>
                    <option <?php selected( 'effect_8', $portfolio_defaultoptions['pfhub_portfolio_lightbox_slideAnimationType'] ); ?>
                        value="effect_8">Effect 8
                    </option>
                    <option <?php selected( 'effect_9', $portfolio_defaultoptions['pfhub_portfolio_lightbox_slideAnimationType'] ); ?>
                        value="effect_9">Effect 9
                    </option>
                </select>
            </div>
        </div>
        <div class="lightbox-options-block portfolio_lightbox_options_grey_overlay">
            <h3>Lightbox Watermark styles<img src="<?php echo PFHUB_PORTFOLIO_IMAGES_URL.'/admin/portfolio-pro-icon.png'; ?>"
                                              class="pfhub_portfolio_lightbox_pro_logo"></h3>
            <div class="has-background">
                <label for="pfhub_portfolio_lightbox_watermark">Watermark
                    <div class="help">?
                        <div class="help-block">
                            <span class="pnt"></span>
                            <p>Set the width of popup</p>
                        </div>
                    </div>
                </label>
                <input type="checkbox"
                       id="pfhub_portfolio_lightbox_watermark"  />
            </div>
            <div>
                <label for="pfhub_portfolio_lightbox_watermark_text">Watermark Text
                    <div class="help">?
                        <div class="help-block">
                            <span class="pnt"></span>
                            <p>Choose the style of your popup</p>
                        </div>
                    </div>
                </label>
                <input type="text"  id="pfhub_portfolio_lightbox_watermark_text"
                       value="<?php echo $portfolio_defaultoptions['pfhub_portfolio_lightbox_watermark_text']; ?>"
                       class="text">
            </div>
            <div class="has-background">
                <label for="pfhub_portfolio_lightbox_watermark_textColor">Watermark Text Color
                    <div class="help">?
                        <div class="help-block">
                            <span class="pnt"></span>
                            <p>Choose the style of your popup</p>
                        </div>
                    </div>
                </label>
                <input type="text" class="color" id="pfhub_portfolio_lightbox_watermark_textColor"
                       value="#<?php echo $portfolio_defaultoptions['pfhub_portfolio_lightbox_watermark_textColor']; ?>"
                       size="10"/>
            </div>
            <div>
                <label for="pfhub_portfolio_lightbox_watermark_textFontSize">Watermark Text Font Size
                    <div class="help">?
                        <div class="help-block">
                            <span class="pnt"></span>
                            <p>Choose the style of your popup</p>
                        </div>
                    </div>
                </label>
                <input type="number"
                       id="pfhub_portfolio_lightbox_watermark_textFontSize"
                       value="<?php echo $portfolio_defaultoptions['pfhub_portfolio_lightbox_watermark_textFontSize']; ?>"
                       class="text">
                <span>px</span>
            </div>
            <div class="has-background">
                <label for="pfhub_portfolio_lightbox_watermark_containerBackground">Watermark Background Color
                    <div class="help">?
                        <div class="help-block">
                            <span class="pnt"></span>
                            <p>Choose the style of your popup</p>
                        </div>
                    </div>
                </label>
                <input type="text" class="color" id="pfhub_portfolio_lightbox_watermark_containerBackground"
                       value="#<?php echo $portfolio_defaultoptions['pfhub_portfolio_lightbox_watermark_containerBackground']; ?>"
                       size="10"/>
            </div>
            <div>
                <label for="pfhub_portfolio_lightbox_watermark_containerOpacity">Watermark Background Opacity
                    <div class="help">?
                        <div class="help-block">
                            <span class="pnt"></span>
                            <p>Choose the style of your popup</p>
                        </div>
                    </div>
                </label>
                <div class="slider-container">
                    <input id="pfhub_portfolio_lightbox_watermark_containerOpacity" data-slider-highlight="true"
                           data-slider-values="0,10,20,30,40,50,60,70,80,90,100" type="text" data-slider="true"
                           value="<?php echo $portfolio_defaultoptions['pfhub_portfolio_lightbox_watermark_containerOpacity']; ?>"/>
                    <span><?php echo $portfolio_defaultoptions['pfhub_portfolio_lightbox_watermark_containerOpacity']; ?>
							%</span>
                </div>
            </div>
            <div class="has-background">
                <label for="pfhub_portfolio_lightbox_watermark_containerWidth">Watermark Width
                    <div class="help">?
                        <div class="help-block">
                            <span class="pnt"></span>
                            <p>Choose the style of your popup</p>
                        </div>
                    </div>
                </label>
                <input type="number"
                       id="pfhub_portfolio_lightbox_watermark_containerWidth"
                       value="<?php echo $portfolio_defaultoptions['pfhub_portfolio_lightbox_watermark_containerWidth']; ?>"
                       class="text">
                <span>px</span>
            </div>
            <div class="has-height">
                <label for="pfhub_portfolio_lightbox_watermark_containerWidth">Watermark Position
                    <div class="help">?
                        <div class="help-block">
                            <span class="pnt"></span>
                            <p>Choose the style of your popup</p>
                        </div>
                    </div>
                </label>
                <div>
                    <table class="bws_position_table">
                        <tbody>
                        <tr>
                            <td><input type="radio" value="1" id="watermark_top-left"
                                    <?php if ( $portfolio_defaultoptions['pfhub_portfolio_lightbox_watermark_position_new'] == '1' ) {
                                        echo 'checked="checked"';
                                    } ?> /></td>
                            <td><input type="radio" value="2" id="watermark_top-center"
                                    <?php if ( $portfolio_defaultoptions['pfhub_portfolio_lightbox_watermark_position_new'] == '2' ) {
                                        echo 'checked="checked"';
                                    } ?> /></td>
                            <td><input type="radio" value="3" id="watermark_top-right"
                                    <?php if ( $portfolio_defaultoptions['pfhub_portfolio_lightbox_watermark_position_new'] == '3' ) {
                                        echo 'checked="checked"';
                                    } ?> /></td>
                        </tr>
                        <tr>
                            <td><input type="radio" value="4" id="watermark_middle-left"
                                    <?php if ( $portfolio_defaultoptions['pfhub_portfolio_lightbox_watermark_position_new'] == '4' ) {
                                        echo 'checked="checked"';
                                    } ?> /></td>
                            <td><input type="radio" value="5" id="watermark_middle-center"
                                    <?php if ( $portfolio_defaultoptions['pfhub_portfolio_lightbox_watermark_position_new'] == '5' ) {
                                        echo 'checked="checked"';
                                    } ?> /></td>
                            <td><input type="radio" value="6" id="watermark_middle-right"
                                    <?php if ( $portfolio_defaultoptions['pfhub_portfolio_lightbox_watermark_position_new'] == '6' ) {
                                        echo 'checked="checked"';
                                    } ?> /></td>
                        </tr>
                        <tr>
                            <td><input type="radio" value="7" id="watermark_bottom-left"
                                    <?php if ( $portfolio_defaultoptions['pfhub_portfolio_lightbox_watermark_position_new'] == '7' ) {
                                        echo 'checked="checked"';
                                    } ?> /></td>
                            <td><input type="radio" value="8" id="watermark_bottom-center"
                                    <?php if ( $portfolio_defaultoptions['pfhub_portfolio_lightbox_watermark_position_new'] == '8' ) {
                                        echo 'checked="checked"';
                                    } ?> /></td>
                            <td><input type="radio" value="9" id="watermark_bottom-right"
                                    <?php if ( $portfolio_defaultoptions['pfhub_portfolio_lightbox_watermark_position_new'] == '9' ) {
                                        echo 'checked="checked"';
                                    } ?> /></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="has-background">
                <label for="pfhub_portfolio_lightbox_watermark_margin">Watermark Margin
                    <div class="help">?
                        <div class="help-block">
                            <span class="pnt"></span>
                            <p>Choose the style of your popup</p>
                        </div>
                    </div>
                </label>
                <input type="number"
                       id="pfhub_portfolio_lightbox_watermark_margin"
                       value="<?php echo $portfolio_defaultoptions['pfhub_portfolio_lightbox_watermark_margin']; ?>"
                       class="text">
                <span>px</span>
            </div>
            <div class="has-background" style="display: none">
                <label for="pfhub_portfolio_lightbox_watermark_opacity">Watermark Text Opacity
                    <div class="help">?
                        <div class="help-block">
                            <span class="pnt"></span>
                            <p>Choose the style of your popup</p>
                        </div>
                    </div>
                </label>
                <div class="slider-container">
                    <input id="pfhub_portfolio_lightbox_watermark_opacity" data-slider-highlight="true"
                           data-slider-values="0,10,20,30,40,50,60,70,80,90,100" type="text" data-slider="true"
                           value="<?php echo $portfolio_defaultoptions['pfhub_portfolio_lightbox_watermark_opacity']; ?>"/>
                    <span><?php echo $portfolio_defaultoptions['pfhub_portfolio_lightbox_watermark_opacity']; ?>%</span>
                </div>
            </div>
            <div style="height:auto;">
                <label for="watermark_image_btn">Select Watermark Image
                    <div class="help">?
                        <div class="help-block">
                            <span class="pnt"></span>
                            <p>Set the image of Lightbox watermark.</p>
                        </div>
                    </div>
                </label>
                <img src="<?php echo $portfolio_defaultoptions['pfhub_portfolio_lightbox_watermark_img_src_new']; ?>"
                     id="watermark_image_new" style="width:120px;height:auto;">
                <input type="button" class="button wp-media-buttons-icon"
                       style="margin-left: 63%;width: auto;display: inline-block;" id="watermark_image_btn_new"
                       value="Change Image">
                <input type="hidden" id="img_watermark_hidden_new" value="<?php echo $portfolio_defaultoptions['pfhub_portfolio_lightbox_watermark_img_src_new']; ?>">
            </div>
        </div>
        <div class="lightbox-options-block portfolio_lightbox_options_grey_overlay" style="margin-top: -320px;">
            <h3>Social Share Buttons<img src="<?php echo PFHUB_PORTFOLIO_IMAGES_URL.'/admin/portfolio-pro-icon.png'; ?>"
                                         class="pfhub_portfolio_lightbox_pro_logo"></h3>
            <div class="has-background">
                <label for="pfhub_portfolio_lightbox_socialSharing">Social Share Buttons
                    <div class="help">?
                        <div class="help-block">
                            <span class="pnt"></span>
                            <p>Set the width of popup</p>
                        </div>
                    </div>
                </label>
                <input type="checkbox"  id="pfhub_portfolio_lightbox_socialSharing"  />
            </div>
            <div class="social-buttons-list">
                <label>Social Share Buttons List
                    <div class="help">?
                        <div class="help-block">
                            <span class="pnt"></span>
                            <p>Choose the style of your popup</p>
                        </div>
                    </div>
                </label>
                <div>
                    <table>
                        <tr>
                            <td>
                                <label for="pfhub_portfolio_lightbox_facebookButton">Facebook
                                    <input type="checkbox"
                                           id="pfhub_portfolio_lightbox_facebookButton" <?php if ( $portfolio_defaultoptions['pfhub_portfolio_lightbox_facebookButton'] == 'true' ) {
                                        echo 'checked="checked"';
                                    } ?>  value="true"/></label>
                            </td>
                            <td>
                                <label for="pfhub_portfolio_lightbox_twitterButton">Twitter
                                    <input type="checkbox"
                                           id="pfhub_portfolio_lightbox_twitterButton" <?php if ( $portfolio_defaultoptions['pfhub_portfolio_lightbox_twitterButton'] == 'true' ) {
                                        echo 'checked="checked"';
                                    } ?>  value="true"/></label>
                            </td>
                            <td>
                                <label for="pfhub_portfolio_lightbox_googleplusButton">Google Plus
                                    <input type="checkbox"
                                           id="pfhub_portfolio_lightbox_googleplusButton" <?php if ( $portfolio_defaultoptions['pfhub_portfolio_lightbox_googleplusButton'] == 'true' ) {
                                        echo 'checked="checked"';
                                    } ?>  value="true"/></label>
                            </td>
                            <td>
                                <label for="pfhub_portfolio_lightbox_pinterestButton">Pinterest
                                    <input type="checkbox"
                                           id="pfhub_portfolio_lightbox_pinterestButton" <?php if ( $portfolio_defaultoptions['pfhub_portfolio_lightbox_pinterestButton'] == 'true' ) {
                                        echo 'checked="checked"';
                                    } ?>  value="true"/></label>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="pfhub_portfolio_lightbox_linkedinButton">Linkedin
                                    <input type="checkbox"
                                           id="pfhub_portfolio_lightbox_linkedinButton" <?php if ( $portfolio_defaultoptions['pfhub_portfolio_lightbox_linkedinButton'] == 'true' ) {
                                        echo 'checked="checked"';
                                    } ?>  value="true"/></label>
                            </td>
                            <td>
                                <label for="pfhub_portfolio_lightbox_tumblrButton">Tumblr
                                    <input type="checkbox"
                                           id="pfhub_portfolio_lightbox_tumblrButton" <?php if ( $portfolio_defaultoptions['pfhub_portfolio_lightbox_tumblrButton'] == 'true' ) {
                                        echo 'checked="checked"';
                                    } ?>  value="true"/></label>
                            </td>
                            <td>
                                <label for="pfhub_portfolio_lightbox_redditButton">Reddit
                                    <input type="checkbox"
                                           id="pfhub_portfolio_lightbox_redditButton" <?php if ( $portfolio_defaultoptions['pfhub_portfolio_lightbox_redditButton'] == 'true' ) {
                                        echo 'checked="checked"';
                                    } ?>  value="true"/></label>
                            </td>
                            <td>
                                <label for="pfhub_portfolio_lightbox_bufferButton">Buffer
                                    <input type="checkbox"
                                           id="pfhub_portfolio_lightbox_bufferButton" <?php if ( $portfolio_defaultoptions['pfhub_portfolio_lightbox_bufferButton'] == 'true' ) {
                                        echo 'checked="checked"';
                                    } ?>  value="true"/></label>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="pfhub_portfolio_lightbox_vkButton">Vkontakte
                                    <input type="checkbox"
                                           id="pfhub_portfolio_lightbox_vkButton" <?php if ( $portfolio_defaultoptions['pfhub_portfolio_lightbox_vkButton'] == 'true' ) {
                                        echo 'checked="checked"';
                                    } ?>  value="true"/></label>
                            </td>
                            <td>
                                <label for="pfhub_portfolio_lightbox_yummlyButton">Yumly
                                    <input type="checkbox"
                                           id="pfhub_portfolio_lightbox_yummlyButton" <?php if ( $portfolio_defaultoptions['pfhub_portfolio_lightbox_yummlyButton'] == 'true' ) {
                                        echo 'checked="checked"';
                                    } ?>  value="true"/></label>
                            </td>
                            <td>
                                <label for="pfhub_portfolio_lightbox_diggButton">Digg
                                    <input type="checkbox"
                                           id="pfhub_portfolio_lightbox_diggButton" <?php if ( $portfolio_defaultoptions['pfhub_portfolio_lightbox_diggButton'] == 'true' ) {
                                        echo 'checked="checked"';
                                    } ?>  value="true"/></label>
                            </td>
                            <td>

                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

<input type="hidden" name="option" value=""/>
<input type="hidden" name="task" value=""/>
<input type="hidden" name="controller" value="options"/>
<input type="hidden" name="op_type" value="styles"/>
<input type="hidden" name="boxchecked" value="0"/>
