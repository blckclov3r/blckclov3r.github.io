<?php
/*
 * The file is included from /templates/meta-box-loaded-assets/_asset-script-single-row.php
*/

if (! isset($data)) {
	exit; // no direct access
}

// Only show it if "Unload site-wide" is NOT enabled
// Otherwise, there's no point to use an unload regex if the asset is unloaded site-wide
if (! $data['row']['global_unloaded']) {
?>
	<div data-script-handle="<?php echo $data['row']['obj']->handle; ?>" class="wpacu_asset_options_wrap wpacu_unload_regex_area_wrap">
		<ul class="wpacu_asset_options">
			<li>
				<label for="wpacu_unload_it_regex_option_script_<?php echo $data['row']['obj']->handle; ?>">
					<input data-handle="<?php echo $data['row']['obj']->handle; ?>"
					       id="wpacu_unload_it_regex_option_script_<?php echo $data['row']['obj']->handle; ?>"
					       class="wpacu_unload_it_regex_checkbox"
					       type="checkbox"
					       disabled="disabled"
					       value="1"/>&nbsp;<span>Unload it for URLs with request URI matching this RegEx(es):</span>

					<a class="go-pro-link-no-style"
					   href="<?php echo WPACU_PLUGIN_GO_PRO_URL; ?>?utm_source=manage_asset&utm_medium=unload_script_by_regex"><span
							class="wpacu-tooltip wpacu-larger"><?php _e( 'This feature is available in the premium version of the plugin.',
								'wp-asset-clean-up' ); ?><br/> <?php _e( 'Click here to upgrade to Pro',
								'wp-asset-clean-up' ); ?>!</span><img width="20" height="20"
					                                                  src="<?php echo WPACU_PLUGIN_URL; ?>/assets/icons/icon-lock.svg"
					                                                  valign="top" alt=""/></a>
				</label>

				<a style="text-decoration: none; color: inherit; vertical-align: middle;" target="_blank"
				   href="https://assetcleanup.com/docs/?p=313#wpacu-unload-by-regex"><span
						class="dashicons dashicons-editor-help"></span></a>
			</li>
		</ul>
	</div>
	<?php
}
