<?php
/*
 * The file is included from /templates/meta-box-loaded-assets/_asset-script-single-row.php
*/

if ( ! isset($data, $ver) ) {
	exit; // no direct access
}

if (isset($data['row']['obj']->src, $data['row']['obj']->srcHref) && $data['row']['obj']->src !== '' && $data['row']['obj']->srcHref) {
	$isExternalSrc = true;

	if (\WpAssetCleanUp\Misc::getLocalSrc($data['row']['obj']->src)
	    || strpos($data['row']['obj']->src, '/?') !== false // Dynamic Local URL
	    || strpos(str_replace(site_url(), '', $data['row']['obj']->src), '?') === 0 // Starts with ? right after the site url (it's a local URL)
	) {
		$isExternalSrc = false;
	}

	$srcHref = $data['row']['obj']->srcHref;

	// If the source starts with ../ mark it as external to be checked via the AJAX call (special case)
	if (strpos($srcHref, '../') === 0) {
		$currentPageUrl = \WpAssetCleanUp\Misc::getCurrentPageUrl();
		$srcHref = trim($currentPageUrl, '/') . '/'. $data['row']['obj']->srcHref;
		$isExternalSrc = true; // simulation
	}

	$relSrc = str_replace(site_url(), '', $data['row']['obj']->src);

	if (isset($data['row']['obj']->baseUrl)) {
		$relSrc = str_replace($data['row']['obj']->baseUrl, '/', $relSrc);
	}

	if ($isExternalSrc) {
		$verToAppend = ''; // no need for any "ver"
	} else {
		$appendAfterSrcHref = ( strpos( $srcHref, '?' ) === false ) ? '?' : '&';

		if ( isset( $data['row']['obj']->ver ) && $data['row']['obj']->ver ) {
			$verToAppend = $appendAfterSrcHref .
			               (is_array( $data['row']['obj']->ver )
				               ? http_build_query( array( 'ver' => $data['row']['obj']->ver ) )
				               : 'ver=' . $ver);
		} else {
			global $wp_version;
			$verToAppend = $appendAfterSrcHref . 'ver=' . $wp_version;
		}
	}

	$isJsPreload = (isset($data['preloads']['scripts'][$data['row']['obj']->handle]) && $data['preloads']['scripts'][$data['row']['obj']->handle])
		? $data['preloads']['scripts'][$data['row']['obj']->handle]
		: false;

	if ($isJsPreload) {
		$data['row']['obj']->preload_status = 'preloaded';
	}
	?>
	<div class="wpacu-source-row">
		<?php
		if (isset($data['row']['obj']->src_origin, $data['row']['obj']->ver_origin)
		    && $data['row']['obj']->src_origin
		    && $data['row']['obj']->ver_origin) {
			$sourceText = __('Source (updated):', 'wp-asset-clean-up');
			?>
            <a style="text-decoration: none; display: inline-block;" href="#" id="wpacu-filter-handle-js-<?php echo $data['row']['obj']->handle; ?>"><span class="dashicons dashicons-filter"></span></a>
            <script type="text/javascript" data-wpacu-own-inline-script="true">
                document.getElementById("wpacu-filter-handle-js-<?php echo $data['row']['obj']->handle; ?>").addEventListener("click", function (event) {
                    var handleFilteredMsg = 'On this page, the `<?php echo $data['row']['obj']->handle; ?>` handle had its source updated via `wpacu_<?php echo $data['row']['obj']->handle; ?>_js_handle_data` filter tag.'+"\n\n"+
                        'Original Source: <?php echo $data['row']['obj']->src_origin; ?> (v<?php echo $data['row']['obj']->ver_origin; ?>)';
                    alert(handleFilteredMsg);
                    event.preventDefault();
                }, false);
            </script>
		<?php } else {
			$sourceText = __('Source:', 'wp-asset-clean-up'); // as it is, no replacement
		}
		echo $sourceText; ?>
        <a target="_blank"  style="color: green;" <?php if ($isExternalSrc) { ?> data-wpacu-external-source="<?php echo $srcHref . $verToAppend; ?>" <?php } ?> href="<?php echo $srcHref . $verToAppend; ?>"><?php echo $relSrc; ?></a> <?php if ($isExternalSrc) { ?><span data-wpacu-external-source-status></span><?php } ?>
		<div class="wpacu_hide_if_handle_row_contracted">
            &nbsp;&#10230;&nbsp;
             Preload (if kept loaded)?
            &nbsp;<select style="display: inline-block; width: auto; <?php if ($isJsPreload) { echo 'background: #f2faf2; padding: 5px; color: black;'; } ?>"
                          name="wpacu_preloads[scripts][<?php echo $data['row']['obj']->handle; ?>]">
                <option value="">No (default)</option>
                <option <?php if ($isJsPreload) { ?>selected="selected"<?php } ?> value="basic">Yes, basic</option>
            </select>
            <small>* applies site-wide</small> <small><a style="text-decoration: none; color: inherit;" target="_blank" href="https://assetcleanup.com/docs/?p=202"><span class="dashicons dashicons-editor-help"></span></a></small>
        </div>
	</div>
	<?php
} else {
    $hasNoSrc = true;
    ?>
    <input type="hidden" name="wpacu_preloads[scripts][<?php echo $data['row']['obj']->handle; ?>]" value="" />
    <?php
}
