<?php
// no direct access
if (! isset($data)) {
	exit;
}

$listAreaStatus = $data['plugin_settings']['assets_list_layout_areas_status'];

/*
* --------------------------------------
* [START] BY PRELOAD STATUS (yes or no)
* --------------------------------------
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
		<?php echo $data['assets_list_layout_output']; ?>
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
    // [wpacu_pro]
    echo $data['plugins_unloaded_notice'];
    // [/wpacu_pro]

	$data['view_by_size'] =
	$data['rows_build_array'] =
	$data['rows_by_size'] = true;

	$data['rows_assets'] = $data['handles_sizes'] = array();

	require_once __DIR__.'/_asset-style-rows.php';
	require_once __DIR__.'/_asset-script-rows.php';

	$sizesText = array(
        'with_size'   => '<span class="dashicons dashicons-yes"></span>&nbsp; Local enqueued files ordered by their size (.css &amp; .js)',
        'external_na' => '<span class="dashicons dashicons-flag"></span>&nbsp; External enqueued files or non-existent (.css &amp; .js)',
    );

	if (! empty($data['rows_assets'])) {
		// Sorting: With Size and External / No Size Detected
		$rowsAssets = array('with_size' => array(), 'external_na' => array());

		if (isset($data['rows_assets']['with_size'], $data['handles_sizes']) && ! empty($data['handles_sizes'])) {
			$dataRowsAssetsWithSize = $data['rows_assets']['with_size'];
            unset($data['rows_assets']['with_size']); // re-built
			$data['rows_assets']['with_size'] = array();

			arsort($data['handles_sizes']);

			foreach ($data['handles_sizes'] as $uniqueHandle => $sizeInBytes) {
				$data['rows_assets']['with_size'][$uniqueHandle] = $dataRowsAssetsWithSize[$uniqueHandle];
			}
        }

		foreach ($data['rows_assets'] as $sizeStatus => $values) {
			$rowsAssets[$sizeStatus] = $values;
		}

		foreach ($rowsAssets as $sizeStatus => $values) {
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
            <div class="wpacu-assets-collapsible-wrap wpacu-by-preloads wpacu-wrap-area wpacu-<?php echo $sizeStatus; ?>">
                <a class="wpacu-assets-collapsible <?php if ($listAreaStatus !== 'contracted') { ?>wpacu-assets-collapsible-active<?php } ?>" href="#wpacu-assets-collapsible-content-<?php echo $sizeStatus; ?>">
					<?php echo $sizesText[$sizeStatus]; ?> &#10141; Total files: <?php echo $totalFiles; ?>
                </a>

                <div class="wpacu-assets-collapsible-content <?php if ($listAreaStatus !== 'contracted') { ?>wpacu-open<?php } ?>">
					<?php if ($sizeStatus === 'with_size') { ?>
						<p class="wpacu-assets-note">This is the list of local files (if any) that had their size calculated and shown in descendent order, <strong>from the largest to the smallest</strong>.</p>
					    <?php
                        if (count($values) < 1) {
                        ?>
                            <p style="padding: 0 15px 15px;"><strong>There are no local files that could have their size calculated.</strong></p>
                        <?php
                        }
                        ?>
                    <?php } elseif ($sizeStatus === 'external_na') { ?>
                        <p class="wpacu-assets-note">This is the list of assets that are external and you can manually check their size via "Get File Size" link. This list also includes local files (most likely that do not exist and are loaded in your page) that couldn't have their size calculated.</p>
					<?php } ?>

					<?php if (count($values) > 0) { ?>
                        <table class="wpacu_list_table wpacu_list_by_size wpacu_widefat wpacu_striped">
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
* ------------------------------------
* [END] BY PRELOAD STATUS (yes or no)
* ------------------------------------
*/

include '_inline_js.php';
