<?php
/*
 * The file is included from /templates/meta-box-loaded-assets/_asset-style-single-row.php
*/
if (! isset($data, $tagType)) {
	exit; // no direct access
}
?>
<div class="wpacu_asset_options_wrap">
	<ul class="wpacu_asset_options">
        <li>
            <label class="wpacu-manage-hardcoded-assets-requires-pro-popup">
                <span style="color: #ccc;" class="wpacu-manage-hardcoded-assets-requires-pro-popup dashicons dashicons-lock"></span>
                <?php _e('Unload site-wide', 'wp-asset-clean-up'); ?> <small>* <?php _e('everywhere', 'wp-asset-clean-up'); ?></small>
            </label>
        </li>
	</ul>
</div>