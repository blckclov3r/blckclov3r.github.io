<?php
/*
 * No direct access to this file
 */
if (! isset($data)) {
	exit;
}
?>
<p>This is the list of all the CSS/JS that had its original position changed (e.g. from <code>&lt;HEAD&gt;</code> to <code>&lt;BODY&gt;</code> (also known as: footer) to reduce render blocking resources, or from <code>&lt;BODY&gt;</code> to <code>&lt;HEAD&gt;</code> for early triggering). Changing the location of an asset is available in the<img style="opacity: 0.6;" width="20" height="20" src="<?php echo WPACU_PLUGIN_URL; ?>/assets/icons/icon-lock.svg" valign="top" alt="" /> <a href="<?php echo WPACU_PLUGIN_GO_PRO_URL; ?>?utm_source=plugin_bulk_changes&utm_medium=assets_positions">Pro version</a> only.</p>
