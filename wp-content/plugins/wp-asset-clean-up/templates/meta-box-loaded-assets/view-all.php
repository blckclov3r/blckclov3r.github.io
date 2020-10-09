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
* ------------------------------
* [START] STYLES & SCRIPTS LIST
* ------------------------------
*/

// [wpacu_pro]
echo $data['plugins_unloaded_notice'];
// [wpacu_pro]
?>
<div class="wpacu-assets-collapsible-wrap wpacu-wrap-all">
    <a style="padding: 15px;" class="wpacu-assets-collapsible <?php if ($listAreaStatus !== 'contracted') { ?>wpacu-assets-collapsible-active<?php } ?>" href="#wpacu-assets-collapsible-content">
        <?php _e('Styles (.css files) &amp; Scripts (.js files)', 'wp-asset-clean-up'); ?> &#10141; Total enqueued (+ core files): <?php echo (int)$data['total_styles'] + (int)$data['total_scripts']; ?> (Styles: <?php echo $data['total_styles']; ?>, Scripts: <?php echo $data['total_scripts']; ?>)
    </a>

    <div id="wpacu-assets-collapsible-content"
         class="wpacu-assets-collapsible-content <?php if ($listAreaStatus !== 'contracted') { ?>wpacu-open<?php } ?>">
        <div>
            <?php
            if (! empty($data['all']['styles']) || ! empty($data['all']['scripts'])) {
                ?>
                <p><?php echo sprintf(__('The following styles &amp; scripts are loading on this page. Please select the ones that are %sNOT NEEDED%s. If you are not sure which ones to unload, it is better to leave them enabled and consult with a developer about unloading the assets.', 'wp-asset-clean-up'), '<span style="color: #CC0000;"><strong>', '</strong></span>'); ?></p>
                <p><?php echo __('"Load in on this page (make an exception)" will take effect when a bulk unload rule is used. Otherwise, the asset will load anyway unless you select it for unload.', 'wp-asset-clean-up'); ?></p>
                <?php
	            if ($data['plugin_settings']['hide_core_files']) {
		            ?>
                    <div class="wpacu_note"><span class="dashicons dashicons-info"></span> WordPress CSS &amp; JavaScript core files are hidden as requested in the plugin's settings. They are meant to be managed by experienced developers in special situations.</div>
                    <div class="wpacu-clearfix" style="margin-top: 10px;"></div>
		            <?php
	            }

	            if ( ( (isset($data['core_styles_loaded']) && $data['core_styles_loaded']) || (isset($data['core_scripts_loaded']) && $data['core_scripts_loaded']) ) && ! $data['plugin_settings']['hide_core_files']) {
                    ?>
                    <div class="wpacu_note wpacu_warning"><em><?php
                            echo sprintf(
                                __('Assets that are marked with %s are part of WordPress core files. Be careful if you decide to unload them! If you are not sure what to do, just leave them loaded by default and consult with a developer.', 'wp-asset-clean-up'),
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
                    $data['view_all'] = true;
                    $data['rows_build_array'] = true;
                    $data['rows_assets'] = array();

                    require_once __DIR__.'/_asset-style-rows.php';
                    require_once __DIR__.'/_asset-script-rows.php';

                    if (! empty($data['rows_assets'])) {
                        ksort($data['rows_assets']);

                        foreach ($data['rows_assets'] as $assetRow) {
                            echo $assetRow."\n";
                        }
                    }
                    ?>
                    </tbody>
                </table>
                <?php
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
 * -----------------------------
 * [END] STYLES & SCRIPTS LIST
 * -----------------------------
 */
