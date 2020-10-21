<?php
/*
 * The file is included from /templates/meta-box-loaded-assets/_asset-script-single-row.php
*/

if (! isset($data)) {
	exit; // no direct access
}
?>
<!-- [wpacu_lite] -->
<?php if (isset($data['row']['obj']->src) && $data['row']['obj']->src !== '') { ?>
	<div class="wpacu-script-attributes-area wpacu-lite">
		<div>If kept loaded, apply the following attributes: <em><a class="go-pro-link-no-style" href="<?php echo WPACU_PLUGIN_GO_PRO_URL; ?>">* <?php _e('Pro version', 'wp-asset-clean-up'); ?></a></em></div>
		<ul class="wpacu-script-attributes-settings wpacu-first">
			<li><a class="go-pro-link-no-style" href="<?php echo WPACU_PLUGIN_GO_PRO_URL; ?>"><span class="wpacu-tooltip wpacu-larger"><?php _e('This feature is available in the premium version of the plugin.', 'wp-asset-clean-up'); ?><br /> <?php _e('Click here to upgrade to Pro', 'wp-asset-clean-up'); ?>!</span><img width="20" height="20" src="<?php echo WPACU_PLUGIN_URL; ?>/assets/icons/icon-lock.svg" valign="top" alt="" /></a>&nbsp; <strong>async</strong> &#10230;</li>
			<li><label><input disabled="disabled" type="checkbox" value="on_this_page" /><?php _e('on this page', 'wp-asset-clean-up'); ?></label></li>
			<li><label><input disabled="disabled" type="checkbox" value="everywhere" /><?php _e('everywhere', 'wp-asset-clean-up'); ?></label></li>
		</ul>
		<ul class="wpacu-script-attributes-settings">
			<li><a class="go-pro-link-no-style" href="<?php echo WPACU_PLUGIN_GO_PRO_URL; ?>"><span class="wpacu-tooltip wpacu-larger"><?php _e('This feature is available in the premium version of the plugin.', 'wp-asset-clean-up'); ?><br /> <?php _e('Click here to upgrade to Pro', 'wp-asset-clean-up'); ?>!</span><img width="20" height="20" src="<?php echo WPACU_PLUGIN_URL; ?>/assets/icons/icon-lock.svg" valign="top" alt="" /></a>&nbsp; <strong>defer</strong> &#10230;</li>
			<li><label><input disabled="disabled" type="checkbox" value="on_this_page" /><?php _e('on this page', 'wp-asset-clean-up'); ?></label></li>
			<li><label><input disabled="disabled" type="checkbox" value="everywhere" /><?php _e('everywhere', 'wp-asset-clean-up'); ?></label></li>
		</ul>
		<div class="wpacu-clearfix"></div>
	</div>
	<div class="wpacu-clearfix"></div>
<?php } ?>
<!-- [/wpacu_lite] -->