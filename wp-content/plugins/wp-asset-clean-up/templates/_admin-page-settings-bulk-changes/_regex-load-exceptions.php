<?php
/*
 * No direct access to this file
 */
if (! isset($data)) {
	exit;
}
?>
<div class="wpacu-clearfix"></div>

<div class="alert">
	<div style="width: 95%; line-height: 22px; margin: 10px 0 0; background: white; padding: 10px; border: 1px solid #ccc; display: inline-block;">
		<h4 style="margin: 0;">How the list below gets filled?</h4>
		This list fills once you make an exception for a CSS/JS asset (handle) to load through the option "<em>Load it for URLs with request URI matching this RegEx</em>" <strong>IF</strong> if the targeted CSS/JS file is already bulk unloaded (e.g. site-wide, on all post pages, etc.) by specifying a RegEx. If you wish to add new load exception RegEx rules for other CSS/JS files, access the "<em>CSS &amp; JavaScript Load Manager</em>" for a page that loads the targeted file.
	</div>
</div>

<div class="wpacu-clearfix"></div>

<p>This feature is available only in the<img style="opacity: 0.6;" width="20" height="20" src="<?php echo WPACU_PLUGIN_URL; ?>/assets/icons/icon-lock.svg" valign="top" alt="" /> <a href="<?php echo WPACU_PLUGIN_GO_PRO_URL; ?>?utm_source=plugin_bulk_changes&utm_medium=regex_unloads"> Pro version</a>.</p>