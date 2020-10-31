<?php
/*
 * The file is included from /templates/meta-box-loaded-assets/_asset-script-single-row.php
*/

if ( ! isset($data) ) {
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
    if (strpos($data['row']['obj']->srcHref, '../') === 0) {
        $currentPageUrl = \WpAssetCleanUp\Misc::getCurrentPageUrl();
	    $currentPageUrl = trim($currentPageUrl, '/');

        $srcHref = $currentPageUrl . '/'. $data['row']['obj']->srcHref;

	    $isExternalSrc = true; // simulation
    }


	$relSrc = str_replace(site_url(), '', $data['row']['obj']->src);

	if (isset($data['row']['obj']->baseUrl)) {
		$relSrc = str_replace($data['row']['obj']->baseUrl, '/', $relSrc);
	}
	?>
	<div class="wpacu-source-row">
		<?php _e( 'Source:', 'wp-asset-clean-up' ); ?>
		<a target="_blank"
           style="color: green;" <?php if ( $isExternalSrc ) { ?> data-wpacu-external-source="<?php echo $srcHref; ?>" <?php } ?>
           href="<?php echo $data['row']['obj']->src; ?>"><?php echo $relSrc; ?></a>
		<?php if ( $isExternalSrc ) { ?><span data-wpacu-external-source-status></span><?php } ?>
	</div>
	<?php
}
