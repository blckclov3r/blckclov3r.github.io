<?php
// no direct access
if (! isset($data)) {
	exit;
}

$listAreaStatus = $data['plugin_settings']['assets_list_layout_areas_status'];
?>
<div style="margin: 10px 0;">
    <?php
    echo $data['assets_list_layout_output'];
    ?>
</div>
<?php
/*
* --------------------
* [START] STYLES LIST
* --------------------
*/
?>
<div class="wpacu-contract-expand-area">
    <div class="col-left">
        <strong>&#10141; Total enqueued files (including core files): <?php echo (int)$data['total_styles'] + (int)$data['total_scripts']; ?></strong>
    </div>
    <div class="col-right">
        <a href="#" id="wpacu-assets-contract-all" class="wpacu-wp-button wpacu-wp-button-secondary">Contract All Groups</a>&nbsp;
        <a href="#" id="wpacu-assets-expand-all" class="wpacu-wp-button wpacu-wp-button-secondary">Expand All Groups</a>
    </div>
    <div class="wpacu-clearfix"></div>
</div>

<div class="wpacu-assets-collapsible-wrap wpacu-wrap-area">
    <a class="wpacu-assets-collapsible <?php if ($listAreaStatus !== 'contracted') { ?>wpacu-assets-collapsible-active<?php } ?>" href="#wpacu-assets-styles-collapsible-content">
        <span class="dashicons dashicons-admin-appearance"></span> &nbsp; <?php _e('Styles (.css files)', 'wp-asset-clean-up'); ?> &#10141; Total enqueued (+ core files): <?php echo $data['total_styles']; ?>
    </a>

    <div id="wpacu-assets-styles-collapsible-content"
         class="wpacu-assets-collapsible-content <?php if ($listAreaStatus !== 'contracted') { ?>wpacu-open<?php } ?>">
        <div>
            <?php
            if (! empty($data['all']['styles'])) {
                ?>
                <p><?php echo sprintf(__('Please select the styles &amp; scripts that are %sNOT NEEDED%s from the list below. Not sure which ones to unload? %s Use "Test Mode" (to make the changes apply only to you), while you are going through the trial &amp; error process.', 'wp-asset-clean-up'), '<span style="color: #CC0000;"><strong>', '</strong></span>', '<img draggable="false" class="wpacu-emoji" alt="🤔" src="https://s.w.org/images/core/emoji/12.0.0-1/svg/1f914.svg" />'); ?></p>
                <p><?php echo __('"Load in on this page (make an exception)" will take effect when a bulk unload rule is used. Otherwise, the asset will load anyway unless you select it for unload.', 'wp-asset-clean-up'); ?></p>
                <?php
	            if ($data['plugin_settings']['hide_core_files']) {
		            ?>
                    <div class="wpacu_note"><span class="dashicons dashicons-info"></span> WordPress CSS core files are hidden as requested in the plugin's settings. They are meant to be managed by experienced developers in special situations.</div>
                    <div style="clear:both; margin-top: 10px;"></div>
		            <?php
	            }

	            if ((isset($data['core_styles_loaded']) && $data['core_styles_loaded']) && ! $data['plugin_settings']['hide_core_files']) {
                    ?>
                    <div class="wpacu_note wpacu_warning"><em><?php
                            echo sprintf(
                                __('CSS files that are marked with %s are part of WordPress core files. Be careful if you decide to unload them! If you are not sure what to do, just leave them loaded by default and consult with a developer.', 'wp-asset-clean-up'),
                                '<span class="dashicons dashicons-warning"></span>'
                            );
                            ?>
                        </em></div>
                    <?php
                }
                ?>
                <table class="wpacu_list_table wpacu_widefat wpacu_striped">
                    <tbody>
                    <?php
                    require_once __DIR__.'/_asset-style-rows.php';
                    ?>
                    </tbody>
                </table>
                <?php
            } else {
                echo __('It looks like there are no public .css files loaded or the ones visible do not follow <a href="https://codex.wordpress.org/Function_Reference/wp_enqueue_style">the WordPress way of enqueuing styles</a>.', 'wp-asset-clean-up');
            }
            ?>
        </div>
    </div>
</div>
<?php
/*
* -------------------
* [END] STYLES LIST
* -------------------
*/

/*
 * ---------------------
 * [START] SCRIPTS LIST
 * ---------------------
 */
?>

<div class="wpacu-assets-collapsible-wrap wpacu-wrap-area">
    <a class="wpacu-assets-collapsible <?php if ($listAreaStatus !== 'contracted') { ?>wpacu-assets-collapsible-active<?php } ?>" href="#wpacu-assets-scripts-collapsible-content">
        <span class="dashicons dashicons-media-code"></span> &nbsp; <?php _e('Scripts (.js files)', 'wp-asset-clean-up'); ?> &#10141; Total enqueued (+ core files): <?php echo $data['total_scripts']; ?>
    </a>

    <div id="wpacu-assets-scripts-collapsible-content"
         class="wpacu-assets-collapsible-content <?php if ($listAreaStatus !== 'contracted') { ?>wpacu-open<?php } ?>">
        <div>
        <?php
        if (! empty($data['all']['scripts'])) {
            ?>
            <p><?php echo sprintf(__('Please select the styles &amp; scripts that are %sNOT NEEDED%s from the list below. Not sure which ones to unload? %s Use "Test Mode" (to make the changes apply only to you), while you are going through the trial &amp; error process.', 'wp-asset-clean-up'), '<span style="color: #CC0000;"><strong>', '</strong></span>', '<img draggable="false" class="wpacu-emoji" alt="🤔" src="https://s.w.org/images/core/emoji/12.0.0-1/svg/1f914.svg" />'); ?></p>
            <p><?php echo __('"Load in on this page (make an exception)" will take effect when a bulk unload rule is used. Otherwise, the asset will load anyway unless you select it for unload.', 'wp-asset-clean-up'); ?></p>
            <?php
            if ($data['plugin_settings']['hide_core_files']) {
                ?>
                <div class="wpacu_note"><span class="dashicons dashicons-info"></span> WordPress JavaScript core files are hidden as requested in the plugin's settings. They are meant to be managed by experienced developers in special situations.</div>
                <div style="clear:both; margin-top: 10px;"></div>
                <?php
            }

	        if ((isset($data['core_scripts_loaded']) && $data['core_scripts_loaded']) && ! $data['plugin_settings']['hide_core_files']) {
            ?>
                <div class="wpacu_note wpacu_warning"><em><?php
                        echo sprintf(
                            __('JavaScript files that are marked with %s are part of WordPress core files. Be careful if you decide to unload them! If you are not sure what to do, just leave them loaded by default and consult with a developer.', 'wp-asset-clean-up'),
                            '<span class="dashicons dashicons-warning"></span>'
                        );
                        ?>
                    </em></div>
                <?php
            }
            ?>

            <table class="wpacu_list_table wpacu_widefat wpacu_striped">
                <tbody>
                <?php
                require_once __DIR__.'/_asset-script-rows.php';
                ?>
                </tbody>
            </table>
            <?php
        } else {
            echo __('It looks like there are no public .js files loaded or the ones visible do not follow <a href="https://codex.wordpress.org/Function_Reference/wp_enqueue_script">the WordPress way of enqueuing scripts</a>.', 'wp-asset-clean-up');
        }
        ?>
        </div>
    </div>
</div>
<?php
if ( isset( $data['all']['hardcoded'] ) && ! empty( $data['all']['hardcoded'] ) ) {
	include_once __DIR__ . '/_assets-hardcoded-list.php';
} elseif ($data['is_frontend_view']) {
	// The following string will be replaced within a "wp_loaded" action hook
	echo '{wpacu_assets_collapsible_wrap_hardcoded_list}';
}

include '_inline_js.php';
/*
 * -------------------
 * [END] SCRIPTS LIST
 * -------------------
 */
