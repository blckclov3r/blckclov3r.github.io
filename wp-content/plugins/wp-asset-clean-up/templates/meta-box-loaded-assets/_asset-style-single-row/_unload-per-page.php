<?php
/*
 * The file is included from /templates/meta-box-loaded-assets/_asset-style-single-row.php
*/

if (! isset($data)) {
	exit; // no direct access
}
?>
<div class="wpacu_asset_options_wrap"
     style="<?php
     // If site-wide unloaded
     if ($data['row']['global_unloaded']) {
	     echo 'display: none;';
     }
     ?> padding: 8px 10px 6px !important;">
	<ul class="wpacu_asset_options" <?php if ($isGroupUnloaded) { echo 'style="display: none;"'; } ?>>
		<li class="wpacu_unload_this_page">
			<label class="wpacu_switch">
				<input data-handle="<?php echo $data['row']['obj']->handle; ?>"
                       data-handle-for="style"
				       class="input-unload-on-this-page <?php if (! $isGroupUnloaded) { echo 'wpacu-not-locked'; } ?> wpacu_unload_rule_input wpacu_unload_rule_for_style"
				       id="style_<?php echo $data['row']['obj']->handle; ?>"
					<?php if ($isGroupUnloaded) { echo 'disabled="disabled"'; }
					echo $data['row']['checked']; ?>
					   name="<?php echo WPACU_PLUGIN_ID; ?>[styles][]"
					   type="checkbox"
					   value="<?php echo $data['row']['obj']->handle; ?>" />
				<span class="wpacu_slider wpacu_round"></span>
			</label>
			<label class="wpacu_slider_text" for="style_<?php echo $data['row']['obj']->handle; ?>">
				<?php echo $data['page_unload_text']; ?>
			</label>
		</li>
	</ul>

	<?php
    // Bulk Unloaded (e.g. for all 'post' pages), except Site-Wide
	if ( $isGroupUnloaded && ! $data['row']['global_unloaded'] ) {
		?>
		<p style="margin: 0 !important;">
			<em>
				<?php echo sprintf(
					__('"%s" rule is locked and irrelevant as there are global rules set below that overwrite it', 'wp-asset-clean-up'),
					$data['page_unload_text']
				); ?>.
				<?php _e('Once all the rules below are removed, this option will become available again', 'wp-asset-clean-up'); ?>.
			</em>
		</p>
		<div class="wpacu-clearfix" style="margin-top: -5px; height: 0;"></div>
		<?php
	}
	?>
</div>
