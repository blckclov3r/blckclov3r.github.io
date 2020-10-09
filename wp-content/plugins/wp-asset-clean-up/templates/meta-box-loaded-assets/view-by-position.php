<?php
// no direct access
if (! isset($data)) {
	exit;
}

$listAreaStatus = $data['plugin_settings']['assets_list_layout_areas_status'];

/*
* -------------------------
* [START] BY EACH POSITION
* -------------------------
*/
?>
<div>
<?php
if (! empty($data['all']['styles']) || ! empty($data['all']['scripts'])) {
?>
<p><?php echo sprintf(__('The following styles &amp; scripts are loading on this page. Please select the ones that are %sNOT NEEDED%s. If you are not sure which ones to unload, it is better to enable "Test Mode" (to make the changes apply only to you), while you are going through the trial &amp; error process.', 'wp-asset-clean-up'), '<span style="color: #CC0000;"><strong>', '</strong></span>'); ?></p>
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
</div>

    <div style="margin: 10px 0;">
        <?php
        echo $data['assets_list_layout_output'];
        ?>
    </div>

    <div style="margin-bottom: 20px;" class="wpacu-contract-expand-area">
        <div class="col-left">
            <strong>&#10141; Total enqueued files (including core files): <?php echo (int)$data['total_styles'] + (int)$data['total_scripts']; ?></strong>
        </div>
        <div class="col-right">
            <a href="#" id="wpacu-assets-contract-all" class="wpacu-wp-button wpacu-wp-button-secondary">Contract All Groups</a>&nbsp;
            <a href="#" id="wpacu-assets-expand-all" class="wpacu-wp-button wpacu-wp-button-secondary">Expand All Groups</a>
        </div>
        <div class="wpacu-clearfix"></div>
    </div>

    <?php
    $data['view_by_position'] =
    $data['rows_build_array'] =
    $data['rows_by_position'] = true;

    $data['rows_assets'] = array();

    require_once __DIR__.'/_asset-style-rows.php';
    require_once __DIR__.'/_asset-script-rows.php';

    $positionsText = array(
        'head' => '<span class="dashicons dashicons-editor-code"></span>&nbsp; HEAD tag (.css &amp; .js)',
        'body' => '<span class="dashicons dashicons-editor-code"></span>&nbsp; BODY tag (.css &amp; .js)'
    );

    if (! empty($data['rows_assets'])) {
        // Sorting: head and body
        $rowsAssets = array('head' => array(), 'body' => array());

        foreach ($data['rows_assets'] as $positionMain => $values) {
            $rowsAssets[$positionMain] = $values;
        }

        foreach ($rowsAssets as $positionMain => $values) {
            ksort($values);

            $assetRowsOutput = '';

            $totalFiles    = 0;
            $assetRowIndex = 1;

            foreach ($values as $assetType => $assetRows) {
                foreach ($assetRows as $assetRow) {
                    $assetRowsOutput .= $assetRow . "\n";
                    $totalFiles++;
                }
            }
            ?>
            <div class="wpacu-assets-collapsible-wrap wpacu-by-position wpacu-wrap-area wpacu-<?php echo $positionMain; ?>">
                <a class="wpacu-assets-collapsible <?php if ($listAreaStatus !== 'contracted') { ?>wpacu-assets-collapsible-active<?php } ?>" href="#wpacu-assets-collapsible-content-<?php echo $positionMain; ?>">
                    <?php echo $positionsText[$positionMain]; ?> &#10141; Total files: <?php echo $totalFiles; ?>
                </a>

                <div class="wpacu-assets-collapsible-content <?php if ($listAreaStatus !== 'contracted') { ?>wpacu-open<?php } ?>">
                    <?php if ($positionMain === 'head') { ?>
                        <p class="wpacu-assets-note">The files below (if any) are loaded within <code>&lt;head&gt;</code> and <code>&lt;/head&gt;</code> tags. The output is done through <code>wp_head()</code> WordPress function which should be located before the closing <code>&lt;/head&gt;</code> tag of your theme.</p>
                    <?php } elseif ($positionMain === 'body') { ?>
                        <p class="wpacu-assets-note">The files below (if any)  are loaded within <code>&lt;body&gt;</code> and <code>&lt;/body&gt;</code> tags. The output is done through <code>wp_footer()</code> WordPress function which should be located before the closing <code>&lt;/body&gt;</code> tag of your theme.</p>
                    <?php } ?>

                    <?php if (count($values) > 0) { ?>
                        <table class="wpacu_list_table wpacu_list_by_position wpacu_widefat wpacu_striped">
                            <tbody>
                            <?php
                            echo $assetRowsOutput;
                            ?>
                            </tbody>
                        </table>
                    <?php } ?>
                </div>
            </div>
            <?php
        }
    }
}

if ( isset( $data['all']['hardcoded'] ) && ! empty( $data['all']['hardcoded'] ) ) {
	include_once __DIR__ . '/_assets-hardcoded-list.php';
} elseif ($data['is_frontend_view']) {
	// The following string will be replaced within a "wp_loaded" action hook
	echo '{wpacu_assets_collapsible_wrap_hardcoded_list}';
}
/*
* -----------------------
* [END] BY EACH POSITION
* -----------------------
*/

include '_inline_js.php';
