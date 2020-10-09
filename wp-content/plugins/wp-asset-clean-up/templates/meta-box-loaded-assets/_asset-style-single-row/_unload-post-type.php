<?php
/*
 * The file is included from /templates/meta-box-loaded-assets/_asset-style-single-row.php
*/

if (! isset($data)) {
	exit; // no direct access
}

if ($data['bulk_unloaded_type'] === 'post_type') {
	?>
	<div class="wpacu_asset_options_wrap" <?php if ($data['row']['global_unloaded']) { echo 'style="display: none;"'; } ?>>
		<?php
		// Unloaded On All Pages Belonging to the page's Post Type
		if ($data['row']['is_post_type_unloaded']) {
			switch ($data['post_type']) {
				case 'product':
					$alreadyUnloadedBulkText = __('This stylesheet file is unloaded on all WooCommerce "Product" pages', 'wp-asset-clean-up');
					break;
				case 'download':
					$alreadyUnloadedBulkText = __('This stylesheet file is unloaded on all Easy Digital Downloads "Download" pages', 'wp-asset-clean-up');
					break;
				default:
					$alreadyUnloadedBulkText = sprintf(__('This stylesheet file is unloaded on all <u>%s</u> post types', 'wp-asset-clean-up'), $data['post_type']);
			}
			?>
			<div style="margin: 0 0 4px!important;"><strong style="color: #d54e21;"><?php echo $alreadyUnloadedBulkText; ?>.</strong></div>
			<?php
		}
		?>

		<ul class="wpacu_asset_options">
			<?php
			if ($data['row']['is_post_type_unloaded']) {
				?>
				<li>
					<label><input data-handle="<?php echo $data['row']['obj']->handle; ?>"
					              class="wpacu_bulk_option wpacu_style wpacu_keep_bulk_rule"
					              type="radio"
					              name="wpacu_options_post_type_styles[<?php echo $data['row']['obj']->handle; ?>]"
					              checked="checked"
					              value="default"/>
						<?php _e('Keep bulk rule', 'wp-asset-clean-up'); ?></label>
				</li>

				<li>
					<label><input data-handle="<?php echo $data['row']['obj']->handle; ?>"
					              class="wpacu_bulk_option wpacu_style wpacu_remove_bulk_rule"
					              type="radio"
					              name="wpacu_options_post_type_styles[<?php echo $data['row']['obj']->handle; ?>]"
					              value="remove"/>
						<?php _e('Remove bulk rule', 'wp-asset-clean-up'); ?></label>
				</li>
				<?php
			} else {
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
					<label><input data-handle="<?php echo $data['row']['obj']->handle; ?>"
					              data-handle-for="style"
					              class="wpacu_bulk_unload wpacu_post_type_unload wpacu_post_type_style wpacu_unload_rule_input wpacu_unload_rule_for_style"
					              id="wpacu_bulk_unload_post_type_style_<?php echo $data['row']['obj']->handle; ?>"
					              type="checkbox"
					              name="wpacu_bulk_unload_styles[post_type][<?php echo $data['post_type']; ?>][]"
					              value="<?php echo $data['row']['obj']->handle; ?>"/>
						<?php echo $unloadBulkText; ?></label>
				</li>
				<?php
			}
			?>
		</ul>
	</div>
	<?php
}