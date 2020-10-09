<?php
/*
 * The file is included from /templates/meta-box-loaded-assets/_asset-style-single-row.php
*/

if ( ! isset($data, $ver, $styleHandleHasSrc, $showGoogleFontRemoveNotice) ) {
	exit; // no direct access
}

// If there is a source (in rare cases there are handles such as "woocommerce-inline" that do not have a source)
if (isset($data['row']['obj']->src, $data['row']['obj']->srcHref) && $data['row']['obj']->src && $data['row']['obj']->srcHref) {
	$styleHandleHasSrc = $isExternalSrc = true; // default

	if (\WpAssetCleanUp\Misc::getLocalSrc($data['row']['obj']->src)
	    || strpos($data['row']['obj']->src, '/?') !== false // Dynamic Local URL
	    || strpos(str_replace(site_url(), '', $data['row']['obj']->src), '?') === 0 // Starts with ? right after the site url (it's a local URL)
	) {
		$isExternalSrc = false;
	}

	$isGoogleFontLink = stripos($data['row']['obj']->srcHref, '//fonts.googleapis.com/') !== false;

	// Formatting for Google Fonts
	if ($isGoogleFontLink) {
		$data['row']['obj']->src     = urldecode(\WpAssetCleanUp\OptimiseAssets\FontsGoogle::alterGoogleFontLink($data['row']['obj']->src));
		$data['row']['obj']->srcHref = urldecode(\WpAssetCleanUp\OptimiseAssets\FontsGoogle::alterGoogleFontLink($data['row']['obj']->srcHref));
	}

	$data['row']['obj']->src = str_replace(' ', '+', $data['row']['obj']->src);
	$data['row']['obj']->srcHref = str_replace(' ', '+', $data['row']['obj']->srcHref);

	$srcHref = $data['row']['obj']->srcHref;

	// If the source starts with ../ mark it as external to be checked via the AJAX call (special case)
	if (strpos($srcHref, '../') === 0) {
		$currentPageUrl = \WpAssetCleanUp\Misc::getCurrentPageUrl();
		$srcHref = trim($currentPageUrl, '/') . '/'. $data['row']['obj']->srcHref;
		$isExternalSrc = true; // simulation
	}

	$relSrc = str_replace(site_url(), '', $data['row']['obj']->src);

	if (isset($data['row']['obj']->baseUrl)) {
		$relSrc = str_replace($data['row']['obj']->baseUrl, '/', $data['row']['obj']->src);
	}

	// "font-display" CSS Property for Google Fonts - underline the URL parameter
	$toUnderline = 'display='.$data['plugin_settings']['google_fonts_display'];
	$relSrc = str_replace($toUnderline, '<u style="background: #f2faf2;">'.$toUnderline.'</u>', $relSrc);

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

	if ( $isGoogleFontLink && $data['plugin_settings']['google_fonts_remove'] ) {
		$showGoogleFontRemoveNotice = '<span style="color:#c00;">This resource is not loaded as "Remove Google Fonts" is enabled in "Settings" -&gt; "Google Fonts".</span>';
	}

	$isCssPreload = (isset($data['preloads']['styles'][$data['row']['obj']->handle]) && $data['preloads']['styles'][$data['row']['obj']->handle])
		? $data['preloads']['styles'][$data['row']['obj']->handle]
		: false;

	if ($isCssPreload) {
		$data['row']['obj']->preload_status = 'preloaded';
	}

	if ($showGoogleFontRemoveNotice) {
		echo '<p>'.$showGoogleFontRemoveNotice.'</p>';
	}
	?>
	<div class="wpacu-source-row">
		<?php
        if (isset($data['row']['obj']->src_origin, $data['row']['obj']->ver_origin)
                  && $data['row']['obj']->src_origin
                  && $data['row']['obj']->ver_origin) {
            $sourceText = __('Source (updated):', 'wp-asset-clean-up');
            ?>
            <a style="text-decoration: none; display: inline-block;" href="#" id="wpacu-filter-handle-css-<?php echo $data['row']['obj']->handle; ?>"><span class="dashicons dashicons-filter"></span></a>
            <script type="text/javascript" data-wpacu-own-inline-script="true">
                document.getElementById("wpacu-filter-handle-css-<?php echo $data['row']['obj']->handle; ?>").addEventListener("click", function (event) {
                    var handleFilteredMsg = 'On this page, the `<?php echo $data['row']['obj']->handle; ?>` handle had its source updated via `wpacu_<?php echo $data['row']['obj']->handle; ?>_css_handle_data` filter tag.'+"\n\n"+
                        'Original Source: <?php echo $data['row']['obj']->src_origin; ?> (v<?php echo $data['row']['obj']->ver_origin; ?>)';
                    alert(handleFilteredMsg);
                    event.preventDefault();
                }, false);
            </script>
		<?php
        } else {
            $sourceText = __('Source:', 'wp-asset-clean-up'); // as it is, no replacement
        }
        echo $sourceText;
        ?>
        <a <?php if ($isExternalSrc) { ?>data-wpacu-external-source="<?php echo $srcHref . $verToAppend; ?>" <?php } ?> target="_blank" style="color: green;" href="<?php echo $srcHref . $verToAppend; ?>"><?php echo $relSrc; ?></a>

        <?php
        if (isset($data['row']['obj']->args) && $data['row']['obj']->args && $data['row']['obj']->args !== 'all') {
            $wpacuMediaSpanStyle = 'color: #004f74; font-style: italic;';
            $wpacuMediaSvgIcon = <<<SVG
<!-- Generated by IcoMoon.io -->
<svg style="vertical-align: middle; width: 22px; height: 22px; margin-left: 1px; margin-right: 1px;" version="1.1" xmlns="http://www.w3.org/2000/svg" width="18" height="28" viewBox="0 0 18 28">
<title>The media attribute specifies what media/device the target resource is optimized for.</title>
<path style="fill: #004f74;" d="M10 22c0-0.547-0.453-1-1-1s-1 0.453-1 1 0.453 1 1 1 1-0.453 1-1zM16 19.5v-15c0-0.266-0.234-0.5-0.5-0.5h-13c-0.266 0-0.5 0.234-0.5 0.5v15c0 0.266 0.234 0.5 0.5 0.5h13c0.266 0 0.5-0.234 0.5-0.5zM18 4.5v17c0 1.375-1.125 2.5-2.5 2.5h-13c-1.375 0-2.5-1.125-2.5-2.5v-17c0-1.375 1.125-2.5 2.5-2.5h13c1.375 0 2.5 1.125 2.5 2.5z"></path>
</svg>
SVG;
            $wpacuLinkToMediaDoc = 'https://www.w3schools.com/css/css_rwd_mediaqueries.asp';

            echo ' <span title="media" style="'.$wpacuMediaSpanStyle.'"><a target="_blank" href="'.$wpacuLinkToMediaDoc.'">'.$wpacuMediaSvgIcon.'</a>'.$data['row']['obj']->args.'</span> ';
        }
        ?>

        <?php if ($isExternalSrc) { ?><span data-wpacu-external-source-status></span><?php } ?>
        <div class="wpacu_hide_if_handle_row_contracted">
            &nbsp;&#10230;&nbsp;
            Preload (if kept loaded)?
            &nbsp;<select style="display: inline-block; width: auto; <?php if ($isCssPreload) { echo 'background: #f2faf2; padding: 5px; color: black;'; } ?>"
                          name="wpacu_preloads[styles][<?php echo $data['row']['obj']->handle; ?>]">
                <option value="">No (default)</option>
                <option <?php if ($isCssPreload) { ?>selected="selected"<?php } ?> value="basic">Yes, basic</option>
                <option disabled="disabled" value="async">Yes, async (Pro)</option>
            </select>
            <small>* applies site-wide</small> <small><a style="text-decoration: none; color: inherit;" target="_blank" href="https://assetcleanup.com/docs/?p=202"><span class="dashicons dashicons-editor-help"></span></a></small>
        </div>
	</div>
	<?php
} else {
	?>
    <input type="hidden" name="wpacu_preloads[styles][<?php echo $data['row']['obj']->handle; ?>]" value="" />
	<?php
}
