<?php
/*
 * The file is included from /templates/meta-box-loaded-assets/_asset-style-single-row.php
*/
if (! isset($data)) {
	exit; // no direct access
}
?>
<div class="wpacu_asset_options_wrap wpacu_unload_regex_area_wrap">
    <ul class="wpacu_asset_options">
        <li>
            <label class="wpacu-manage-hardcoded-assets-requires-pro-popup"
                   for="wpacu_unload_it_regex_option_style_<?php echo $data['row']['obj']->handle; ?>">
                <span style="color: #ccc;" class="wpacu-manage-hardcoded-assets-requires-pro-popup dashicons dashicons-lock"></span>
                Unload it for URLs with request URI matching this RegEx(es):
            </label>
            <a style="text-decoration: none; color: inherit; vertical-align: middle;" target="_blank"
               href="https://assetcleanup.com/docs/?p=313#wpacu-unload-by-regex"><span
                    class="dashicons dashicons-editor-help"></span></a>
        </li>
    </ul>
</div>