<?php
/*
 * The file is included from _asset-style-rows.php
*/
if (! isset($data)) {
	exit; // no direct access
}

$inlineCodeStatus = $data['plugin_settings']['assets_list_inline_code_status'];
$isCoreFile       = isset($data['row']['obj']->wp) && $data['row']['obj']->wp;
$hideCoreFiles    = $data['plugin_settings']['hide_core_files'];
$isGroupUnloaded  = $data['row']['is_group_unloaded'] || $data['row']['is_post_type_unloaded'];

// Does it have "children"? - other CSS file(s) depending on it
$childHandles     = isset($data['all_deps']['parent_to_child']['styles'][$data['row']['obj']->handle]) ? $data['all_deps']['parent_to_child']['styles'][$data['row']['obj']->handle] : array();
sort($childHandles);

// Unloaded site-wide
if ($data['row']['global_unloaded']) {
	$data['row']['class'] .= ' wpacu_is_global_unloaded';
}

// Unloaded site-wide OR on all posts, pages etc.
if ($isGroupUnloaded) {
	$data['row']['class'] .= ' wpacu_is_bulk_unloaded';
}

$rowIsContracted   = '';
$dashSign          = 'minus';
$dataRowStatusAttr = 'expanded';

if (isset($data['handle_rows_contracted']['styles'][$data['row']['obj']->handle]) && $data['handle_rows_contracted']['styles'][$data['row']['obj']->handle]) {
	$rowIsContracted   = 1;
	$dashSign          = 'plus';
	$dataRowStatusAttr = 'contracted';
}
?>
<tr data-style-handle-row="<?php echo $data['row']['obj']->handle; ?>"
    id="wpacu_style_row_<?php echo $data['row']['obj']->handle; ?>"
    class="wpacu_asset_row <?php echo $data['row']['class']; ?>"
    style="<?php if ($isCoreFile && $hideCoreFiles) { echo 'display: none;'; } ?>">
    <td valign="top" style="position: relative;" data-wpacu-row-status="<?php echo $dataRowStatusAttr; ?>">
        <div class="wpacu_handle_row_expand_contract_area">
            <a data-wpacu-handle="<?php echo $data['row']['obj']->handle; ?>"
               data-wpacu-handle-for="style"
               class="wpacu_handle_row_expand_contract"
               href="#"><span class="dashicons dashicons-<?php echo $dashSign; ?>"></span></a>
            <input type="hidden"
                   id="wpacu_style_<?php echo $data['row']['obj']->handle; ?>_row_contracted_area"
                   name="wpacu_handle_row_contracted_area[styles][<?php echo $data['row']['obj']->handle; ?>]"
                   value="<?php echo $rowIsContracted; ?>" />
        </div>
        <?php
        include '_asset-style-single-row/_handle.php';

        $ver = $data['wp_version']; // default
        if (isset($data['row']['obj']->ver) && $data['row']['obj']->ver) {
            $ver = is_array($data['row']['obj']->ver) ? implode(', ', $data['row']['obj']->ver) : $data['row']['obj']->ver;
        }

	    $data['row']['obj']->preload_status = 'not_preloaded'; // default

	    $styleHandleHasSrc = $showGoogleFontRemoveNotice = false;

        include '_asset-style-single-row/_source.php';

        // Any tips?
        if (isset($data['tips']['css'][$data['row']['obj']->handle]) && ($assetTip = $data['tips']['css'][$data['row']['obj']->handle])) {
            ?>
            <div class="tip"><strong>Tip:</strong> <?php echo $assetTip; ?></div>
            <?php
        }
        ?>
	    <div class="wpacu_handle_row_expanded_area <?php if ($rowIsContracted) { echo 'wpacu_hide'; } ?>">
		    <?php
			$extraInfo = array();

		    include '_asset-style-single-row/_handle_deps.php';

		    $extraInfo[] = __('Version:', 'wp-asset-clean-up').' '.$ver;

	        include '_asset-style-single-row/_position.php';

		    if (isset($data['row']['obj']->src) && $data['row']['obj']->src) {
			    $extraInfo[] = __('File Size:', 'wp-asset-clean-up') . ' <em>' . $data['row']['obj']->size . '</em>';
		    }

		    if (! empty($extraInfo)) {
		        $spacingAdj = (isset($noSrcLoadedIn) && $noSrcLoadedIn) ? '18px 0 10px' : '2px 0 10px';
			    echo '<div style="margin: '.$spacingAdj.';">'.implode(' &nbsp;/&nbsp; ', $extraInfo).'</div>';
		    }
	        ?>

	        <div class="wrap_bulk_unload_options">
		        <?php
		        // Unload on this page
		        include '_asset-style-single-row/_unload-per-page.php';

		        // Unload site-wide (everywhere)
		        include '_asset-style-single-row/_unload-site-wide.php';

		        // Unload on all pages of [post] post type (if applicable)
		        include '_asset-style-single-row/_unload-post-type.php';

		        // Unload via RegEx (if site-wide is not already chosen)
	            include '_asset-style-single-row/_unload-via-regex.php';

		        // If any bulk unload rule is set, show the load exceptions
		        include '_asset-style-single-row/_load-exceptions.php';
	            ?>
	            <div class="wpacu-clearfix"></div>
	        </div>

	        <?php
	        // Extra inline associated with the LINK tag
	        include '_asset-style-single-row/_extra_inline.php';

	        // Handle Note
	        include '_asset-style-single-row/_notes.php';
	        ?>
	    </div>
        <img style="display: none;" class="wpacu-ajax-loader" src="<?php echo WPACU_PLUGIN_URL; ?>/assets/icons/icon-ajax-loading-spinner.svg" alt="" />
	</td>
</tr>