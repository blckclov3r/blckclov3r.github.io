<?php
/*
 * The file is included from /templates/meta-box-loaded-assets/_asset-style-single-row.php
*/
if (! isset($data, $tagType)) {
	exit; // no direct access
}

if ($data['bulk_unloaded_type'] === 'post_type') {
	?>
	<div class="wpacu_asset_options_wrap">
		<ul class="wpacu_asset_options">
			<?php
            switch ($data['post_type']) {
                case 'product':
                    $unloadBulkText = __('Unload CSS on all WooCommerce "Product" pages', 'wp-asset-clean-up');
                    break;
                case 'download':
                    $unloadBulkText = __('Unload CSS on all Easy Digital Downloads "Download" pages', 'wp-asset-clean-up');
                    break;
                default:
                    $unloadBulkText = sprintf(__('Unload on All Pages of "<strong>%s</strong>" post type', 'wp-asset-clean-up'), $data['post_type']);
            }
            ?>
            <li>
                <label class="wpacu-manage-hardcoded-assets-requires-pro-popup">
                    <span style="color: #ccc;" class="wpacu-manage-hardcoded-assets-requires-pro-popup dashicons dashicons-lock"></span>
                    <?php echo $unloadBulkText; ?></label>
            </li>
		</ul>
	</div>
	<?php
}