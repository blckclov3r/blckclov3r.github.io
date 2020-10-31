<?php
/*
 * The file is included from /templates/meta-box-loaded-assets/_asset-script-single-row.php
*/

if (! isset($data)) {
	exit; // no direct access
}

$isGroupUnloaded  = $data['row']['is_group_unloaded'];
$anyUnloadRuleSet = ($isGroupUnloaded || $data['row']['checked']);
?>
<div class="wpacu_asset_options_wrap <?php if (! $anyUnloadRuleSet) { echo 'wpacu_hide'; } ?>">
	<div data-style-handle="<?php echo $data['row']['obj']->handle; ?>"
	     class="wpacu_exception_options_area_wrap">
		<div class="wpacu_area_one">
			<?php if ($isGroupUnloaded) { ?>
				<strong>Make an exception</strong> and always:
			<?php } else { ?>
				<strong>Make an exception</strong> if unloaded and always:
			<?php } ?>
        </div>
        <ul class="wpacu_area_two wpacu_asset_options wpacu_exception_options_area">
            <li id="wpacu_load_it_option_style_<?php echo $data['row']['obj']->handle; ?>">
                <label><input data-handle="<?php echo $data['row']['obj']->handle; ?>"
                              id="wpacu_style_load_it_<?php echo $data['row']['obj']->handle; ?>"
                              class="wpacu_load_it_option_one wpacu_style wpacu_load_exception"
                              type="checkbox"
						<?php if ($data['row']['is_load_exception_per_page']) { ?> checked="checked" <?php } ?>
                              name="wpacu_styles_load_it[]"
                              value="<?php echo $data['row']['obj']->handle; ?>"/>
                    Load it on this page</label>
            </li>
            <li>
                <label for="wpacu_load_it_regex_option_style_<?php echo $data['row']['obj']->handle; ?>"><input
                            data-handle="<?php echo $data['row']['obj']->handle; ?>"
                            id="wpacu_load_it_regex_option_style_<?php echo $data['row']['obj']->handle; ?>"
                            class="wpacu_load_it_option_two wpacu_style wpacu_load_exception"
                            type="checkbox"
                            disabled="disabled"
                            value="1"/>
                    Load it for URLs with request URI matching this RegEx(es): <a class="go-pro-link-no-style"
                                                                              href="<?php echo WPACU_PLUGIN_GO_PRO_URL; ?>?utm_source=manage_asset&utm_medium=load_via_regex_make_exception"><span
                                class="wpacu-tooltip wpacu-larger"><?php _e( 'This feature is available in the premium version of the plugin.',
								'wp-asset-clean-up' ); ?><br/> <?php _e( 'Click here to upgrade to Pro',
								'wp-asset-clean-up' ); ?>!</span><img width="20" height="20"
                                                                      src="<?php echo WPACU_PLUGIN_URL; ?>/assets/icons/icon-lock.svg"
                                                                      valign="top" alt=""/></a> <a
                            style="text-decoration: none; color: inherit;" target="_blank"
                            href="https://assetcleanup.com/docs/?p=21#wpacu-method-2"><span
                                class="dashicons dashicons-editor-help"></span></a></label>
            </li>
			<?php
			$isLoadItLoggedIn = in_array($data['row']['obj']->handle, $data['handle_load_logged_in']['styles']);
			?>
			<li id="wpacu_load_it_user_logged_in_option_style_<?php echo $data['row']['obj']->handle; ?>">
				<label>
                    <input data-handle="<?php echo $data['row']['obj']->handle; ?>"
				              id="wpacu_load_it_user_logged_in_option_style_<?php echo $data['row']['obj']->handle; ?>"
				              class="wpacu_load_it_option_three wpacu_style wpacu_load_exception"
				              type="checkbox"
						<?php if ($isLoadItLoggedIn) { ?> checked="checked" <?php } ?>
						      name="wpacu_load_it_logged_in[styles][<?php echo $data['row']['obj']->handle; ?>]"
						      value="1"/>
					Load it if the user is logged-in</label>
			</li>
		</ul>
		<div class="wpacu-clearfix"></div>
	</div>
</div>