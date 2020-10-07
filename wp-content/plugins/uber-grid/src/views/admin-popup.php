<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
?>

<div id="pfhub_portfolio" style="display:none;" class="post-content">
    <div class="portfolio_insert_shortcode_popup">
        <h3>Select NavyPlugins Portfolio Gallery to insert into post</h3>
        <?php
        global $wpdb;
        $query        = "SELECT * FROM " . $wpdb->prefix . "pfhub_portfolio_grids";
        $firstrow     = $wpdb->get_row( $query );
        $container_id = 'pfhub_portfolio';
        if ( isset( $_POST["pfhub_portfolio_grid_id"] ) ) {
            $id = absint( $_POST["pfhub_portfolio_grid_id"] );
        } else {
            $id = $firstrow->id;
        }
        $query                        = "SELECT * FROM " . $wpdb->prefix . "pfhub_portfolio_grids order by id ASC";
        $shortcodeportfolios          = $wpdb->get_results( $query );
        $query                        = $wpdb->prepare( "SELECT * FROM " . $wpdb->prefix . "pfhub_portfolio_grids WHERE id= %d", $id );
        $row                          = $wpdb->get_row( $query );
        $shortecode_change_view_nonce = wp_create_nonce( 'shortecode_change_view_nonce' );

        if ( count( $shortcodeportfolios ) ) {
            echo "<select id='pfhub_portfolio-select' class='select_portfolio_list'   name='pfhub_portfolio_grid_id' change-view-nonce='" . $shortecode_change_view_nonce . "'>";
            foreach ( $shortcodeportfolios as $shortcodeportfolio ) {
                echo "<option value='" . $shortcodeportfolio->id . "'>" . $shortcodeportfolio->name . "</option>";
            }
            echo "</select>";
            echo "<button class='insert_shortcode_button' id='pfhub_portfolioinsert'>".__('Insert portfolio gallery','pfhub_portfolio')."</button>";
        } else {
            echo "No slideshows found";
        }
        ?>
        <!--------------------------------Option's HTML-------------------------------->
        <div class="options_wrapper">
            <h3><?php _e('Current Portfolio Options','pfhub_portfolio'); ?></h3>
            <ul id="portfolio-unique-options-list">
                <li style="display:none;">
                    <label for="sl_width"><?php echo __( 'The requested action is not valid.', 'pfhub_portfolio' ); ?></label>
                    <input type="text" name="sl_width" id="sl_width" value="1111" class="text_area"/>
                </li>
                <li style="display:none;">
                    <label for="sl_height"><?php echo __( 'Height', 'pfhub_portfolio' ); ?></label>
                    <input type="text" name="sl_height" id="sl_height" value="<?php echo esc_attr( $row->sl_height ); ?>"
                           class="text_area"/>
                </li>
                <li>
                    <label for="portfolio_effects_list"><?php echo __( 'Select The View', 'pfhub_portfolio' ); ?></label>
                    <select name="portfolio_effects_list" id="portfolio_effects_list">
                        <option <?php if ( $row->grid_view_type == '0' ) {
                            echo 'selected';
                        } ?> value="0"><?php echo __( 'Toggle Grid', 'pfhub_portfolio' ); ?></option>
                        <option <?php if ( $row->grid_view_type == '1' ) {
                            echo 'selected';
                        } ?> value="1"><?php echo __( 'Content Blocks', 'pfhub_portfolio' ); ?></option>
                        <option <?php if ( $row->grid_view_type == '2' ) {
                            echo 'selected';
                        } ?> value="2"><?php echo __( 'Content Popup', 'pfhub_portfolio' ); ?></option>
                        <option <?php if ( $row->grid_view_type == '3' ) {
                            echo 'selected';
                        } ?> value="3"><?php echo __( 'Projects List', 'pfhub_portfolio' ); ?></option>
                        <option <?php if ( $row->grid_view_type == '5' ) {
                            echo 'selected';
                        } ?> value="5"><?php echo __( 'Content Slider', 'pfhub_portfolio' ); ?></option>
                        <option <?php if ( $row->grid_view_type == '6' ) {
                            echo 'selected';
                        } ?> value="6"><?php echo __( 'Masonry Grid', 'pfhub_portfolio' ); ?></option>
                        <option <?php if ( $row->grid_view_type == '7' ) {
                            echo 'selected';
                        } ?> value="7"><?php echo __( 'Elastic Grid', 'pfhub_portfolio' ); ?></option>
                        <option <?php if ( $row->grid_view_type == '8' ) {
                            echo 'selected';
                        } ?> value="8"><?php echo __( 'Store View', 'pfhub_portfolio' ); ?></option>
                    </select>
                </li>
                <li class="allowIsotope">
                    <label for="pfhub_portfolio_show_sorting"><?php echo __( 'Show Sorting Buttons', 'pfhub_portfolio' ); ?></label>
                    <input type="checkbox" id="pfhub_portfolio_show_sorting" <?php if ( $row->pfhub_portfolio_show_sorting == 'on' ) {
                        echo 'checked="checked"';
                    } ?> name="pfhub_portfolio_show_sorting" value="<?php echo $row->pfhub_portfolio_show_sorting; ?>"/>
                </li>
                <li style="display:none;" class="for-content-slider">
                    <label for="sl_pausetime"><?php echo __( 'Pause time', 'pfhub_portfolio' ); ?></label>
                    <input type="text" name="sl_pausetime" id="sl_pausetime"
                           value="<?php echo esc_attr( $row->description ); ?>"
                           class="text_area"/>
                </li>
                <li style="display:none;" class="for-content-slider">
                    <label for="sl_changespeed"><?php echo __( 'Change speed', 'pfhub_portfolio' ); ?></label>
                    <input type="text" name="sl_changespeed" id="sl_changespeed" value="<?php echo esc_attr( $row->param ); ?>"
                           class="text_area"/>
                <li style="display:none;margin-top:10px" class="for-content-slider">
                    <label for="auto_slide_on"><?php echo __( 'Autoslide ', 'pfhub_portfolio' ); ?></label>
                    <input type="checkbox" name="pause_on_hover" value="<?php echo esc_attr( $row->pause_on_hover ); ?>"
                           id="auto_slide_on" <?php if ( $row->pause_on_hover == 'on' ) {
                        echo 'checked="checked"';
                    } ?> />
                </li>
            </ul>
        </div>
        <!--------------------------------------------------------------------------------->
    </div>
</div>
<style>
    .portfolio_insert_shortcode_popup .select_portfolio_list {
        padding: 2px;
        line-height: 28px;
        height: 28px;
        min-width: 150px;
        margin-right:10px;
        border: 0px;
        outline: none;
        box-shadow: 0px 1px 5px #ccc;
    }

    .portfolio_insert_shortcode_popup .insert_shortcode_button {
        border: 0px;
        background: #0073aa;
        color: #fff;
        border-radius: 2px;
        padding: 5px 18px;
        font-size: 15px;
        position: relative;
        top: 3px;
    }

    .options_wrapper {
        padding-top:20px;
    }
</style>
