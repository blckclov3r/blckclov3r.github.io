<?php
/*
 * No direct access to this file
 */
if (! isset($data)) {
	exit;
}

include_once '_top-area.php';

$wpacuTabCurrent = isset($_REQUEST['wpacu_bulk_menu_tab']) ? $_REQUEST['wpacu_bulk_menu_tab'] : 'bulk_unloaded';

$wpacuTabList = array(
    'bulk_unloaded'         => __('Bulk Unloaded (page types)', 'wp-asset-clean-up'),
    'regex_unloads'         => __('RegEx Unloads', 'wp-asset-clean-up'),
    'regex_load_exceptions' => __('RegEx Load Exceptions', 'wp-asset-clean-up'),
    'preloaded_assets'      => __('Preloaded CSS/JS', 'wp-asset-clean-up'),
    'script_attrs'          => __('Defer &amp; Async (site-wide)', 'wp-asset-clean-up'),
    'assets_positions'      => __('Updated CSS/JS positions', 'wp-asset-clean-up')
);
?>
<div class="wpacu-wrap <?php if ($data['plugin_settings']['input_style'] !== 'standard') { echo 'wpacu-switch-enhanced'; } ?>">
    <ul class="wpacu-bulk-changes-tabs">
		<?php
		foreach ($wpacuTabList as $wpacuTabKey => $wpacuTabValue) {
			?>
            <li <?php if ($wpacuTabKey === $wpacuTabCurrent) { ?>class="current"<?php } ?>>
                <a href="<?php echo admin_url('admin.php?page=wpassetcleanup_bulk_unloads&wpacu_bulk_menu_tab='.$wpacuTabKey); ?>"><?php echo $wpacuTabValue; ?></a>
            </li>
			<?php
		}
		?>
    </ul>
	<?php
	if ($wpacuTabCurrent === 'bulk_unloaded') {
		include_once '_admin-page-settings-bulk-changes/_bulk-unloaded.php';
	} elseif($wpacuTabCurrent === 'regex_unloads') {
		include_once '_admin-page-settings-bulk-changes/_regex-unloads.php';
	} elseif($wpacuTabCurrent === 'regex_load_exceptions') {
		include_once '_admin-page-settings-bulk-changes/_regex-load-exceptions.php';
	} elseif ($wpacuTabCurrent === 'preloaded_assets') {
		include_once '_admin-page-settings-bulk-changes/_preloaded-assets.php';
	} elseif ($wpacuTabCurrent === 'script_attrs') {
		include_once '_admin-page-settings-bulk-changes/_script-attrs.php';
	} elseif ($wpacuTabCurrent === 'assets_positions') {
		include_once '_admin-page-settings-bulk-changes/_assets-positions.php';
	}

	/**
	 * @param $handle
	 * @param $assetType
	 * @param $data
	 * @param string $for ('default': bulk unloads, regex unloads)
	 */
	function wpacuRenderHandleTd($handle, $assetType, $data, $for = 'default')
    {
	    global $wp_version;

	    $isCoreFile = false; // default

		if ( $for === 'default' ) {
		    $src = (isset( $data['assets_info'][ $assetType ][ $handle ]['src'] ) && $data['assets_info'][ $assetType ][ $handle ]['src']) ? $data['assets_info'][ $assetType ][ $handle ]['src'] : false;

			$isExternalSrc = true;

			if (\WpAssetCleanUp\Misc::getLocalSrc($src)
			    || strpos($src, '/?') !== false // Dynamic Local URL
			    || strpos(str_replace(site_url(), '', $src), '?') === 0 // Starts with ? right after the site url (it's a local URL)
			) {
				$isExternalSrc = false;
				$isCoreFile = \WpAssetCleanUp\Misc::isCoreFile($data['assets_info'][$assetType][$handle]);
			}

			if (strpos($src, '/') === 0 && strpos($src, '//') !== 0) {
				$src = site_url() . $src;
			}

			if (isset($data['assets_info'][ $assetType ][ $handle ]['ver']) && $data['assets_info'][ $assetType ][ $handle ]['ver']) {
				$verToPrint = $verToAppend = is_array($data['assets_info'][ $assetType ][ $handle ]['ver'])
					? implode(',', $data['assets_info'][ $assetType ][ $handle ]['ver'])
					: $data['assets_info'][ $assetType ][ $handle ]['ver'];
				$verToAppend = is_array($data['assets_info'][ $assetType ][ $handle ]['ver'])
                    ? http_build_query(array('ver' => $data['assets_info'][ $assetType ][ $handle ]['ver']))
                    : 'ver='.$data['assets_info'][ $assetType ][ $handle ]['ver'];
			} else {
				$verToAppend = 'ver='.$wp_version;
                $verToPrint = $wp_version;
            }
			?>
            <strong><span style="color: green;"><?php echo $handle; ?></span></strong>
            <small><em>v<?php echo $verToPrint; ?></em></small>
			<?php
			if ($isCoreFile) {
				?>
                <span title="WordPress Core File" style="font-size: 15px; vertical-align: middle;" class="dashicons dashicons-wordpress-alt wpacu-tooltip"></span>
				<?php
			}
			?>
            <?php
			// [wpacu_pro]
			$preloadedStatus = isset($data['assets_info'][ $assetType ][ $handle ]['preloaded_status']) ? $data['assets_info'][ $assetType ][ $handle ]['preloaded_status'] : false;
			if ($preloadedStatus === 'async') { echo '&nbsp;(<strong><em>'.$preloadedStatus.'</em></strong>)'; }
			// [/wpacu_pro]
            ?>

			<?php if ( $src ) {
			    $appendAfterSrc = strpos($src, '?') === false ? '?'.$verToAppend : '&'.$verToAppend;
			    ?>
                <div><a <?php if ($isExternalSrc) { ?> data-wpacu-external-source="<?php echo $src . $appendAfterSrc; ?>" <?php } ?> href="<?php echo $src . $appendAfterSrc; ?>" target="_blank"><small><?php echo str_replace( site_url(), '', $src ); ?></small></a> <?php if ($isExternalSrc) { ?><span data-wpacu-external-source-status></span><?php } ?></div>
			<?php } ?>
			<?php
		}
	}
	?>
</div>