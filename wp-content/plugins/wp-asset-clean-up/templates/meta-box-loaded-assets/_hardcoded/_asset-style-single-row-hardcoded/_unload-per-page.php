<?php
/*
 * The file is included from /templates/meta-box-loaded-assets/_asset-script-single-row.php
*/
if (! isset($data)) {
	exit; // no direct access
}
?>
<div class="wpacu_asset_options_wrap"
     style="padding: 6px 10px 5px !important;">
	<ul class="wpacu_asset_options">
		<li class="wpacu_unload_this_page">
			<label class="wpacu-manage-hardcoded-assets-requires-pro-popup wpacu_switch wpacu_disabled">
				<input data-handle="<?php echo $data['row']['obj']->handle; ?>"
                       data-handle-for="style"
				       class="input-unload-on-this-page wpacu-not-locked wpacu_unload_rule_input wpacu_unload_rule_for_style"
				       id="style_<?php echo $data['row']['obj']->handle; ?>"
                       disabled="disabled"
					   name="<?php echo WPACU_PLUGIN_ID; ?>[styles][]"
					   type="checkbox"
					   value="<?php echo $data['row']['obj']->handle; ?>" />
				<span class="wpacu_slider wpacu_round"></span>
			</label>
			<label class="wpacu-manage-hardcoded-assets-requires-pro-popup wpacu_slider_text" for="style_<?php echo $data['row']['obj']->handle; ?>">
				<?php echo $data['page_unload_text']; ?>
			</label>
		</li>
	</ul>
</div>
