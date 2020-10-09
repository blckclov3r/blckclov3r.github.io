<?php
/*
 * The file is included from /templates/meta-box-loaded-assets/_asset-style-single-row.php
*/

if ( ! isset($data, $isCoreFile, $hideCoreFiles, $childHandles) ) {
	exit; // no direct access
}
?>
<div class="wpacu_handle" style="margin: 0 0 -8px;">
	<label for="style_<?php echo $data['row']['obj']->handle; ?>"><?php _e('Handle:', 'wp-asset-clean-up'); ?> <strong><span style="color: green;"><?php echo $data['row']['obj']->handle; ?></span></strong></label>
	&nbsp;<em>* Stylesheet (.css)</em>
	<?php if ($isCoreFile && ! $hideCoreFiles) { ?>
		<span class="dashicons dashicons-wordpress-alt wordpress-core-file"><span class="wpacu-tooltip">WordPress Core File<br /><?php _e('Not sure if needed or not? In this case, it\'s better to leave it loaded to avoid breaking the website.', 'wp-asset-clean-up'); ?></span></span>
		<?php
	}

	if (isset($data['load_exceptions_debug']['styles']) && in_array($data['row']['obj']->handle, $data['load_exceptions_debug']['styles'])) {
		// '/?wpacu_load_css=' was used and has the handle within its value
	    echo '&nbsp; <span style="color: green; font-style: italic;"><strong>Load Exception:</strong> This handle is loading for you on this page as requested via the "wpacu_load_css" value from the current page URL (for debugging purposes).</span>';
	} elseif (isset($data['current_debug']['styles']) && in_array($data['row']['obj']->handle, $data['current_debug']['styles'])) {
		// '/?wpacu_unload_css=' was used and has the handle within its value
		echo '&nbsp; <span style="color: #cc0000; font-style: italic;"><strong>Unload Exception:</strong> This handle is unloaded for you on this page as requested via the "wpacu_unload_css" value from the current page URL (for debugging purposes).</span>';
	}

	// Any conditions set such as "IE" or "lt IE 8"?
	$dataRowExtra = (array)$data['row']['obj']->extra;
	// Notify the user the assets load only on Internet Explorer
	if ( isset( $dataRowExtra['conditional'] ) && $dataRowExtra['conditional'] && strpos( $dataRowExtra['conditional'], 'IE' ) !== false ) {
        echo '&nbsp;&nbsp;<span><img style="vertical-align: middle;" width="25" height="25" src="'.WPACU_PLUGIN_URL.'/assets/icons/icon-ie.svg" alt="" title="Microsoft / Public domain" />&nbsp;<span style="font-weight: 400; color: #1C87CF;">Loads only in Internet Explorer based on the following condition:</span> <em> if '.$dataRowExtra['conditional'].'</em></span>';
	}
	?>
</div>
	<!-- Clear on form submit it if the dependency is not there anymore -->
	<input type="hidden" name="wpacu_ignore_child[styles][<?php echo $data['row']['obj']->handle; ?>]" value="" />
<?php
if (! empty($childHandles)) {
	$ignoreChild = (isset($data['ignore_child']['styles'][$data['row']['obj']->handle]) && $data['ignore_child']['styles'][$data['row']['obj']->handle]);
	?>
    <div class="wpacu_dependency_notice_area">
		<em style="font-size: 85%;">
			<span style="color: #0073aa; width: 19px; height: 19px; vertical-align: middle;" class="dashicons dashicons-info"></span>
			There are CSS "children" files depending on this file. By unloading it, the following will also be unloaded:
            <?php
            $childHandlesOutput = '';
            foreach ($childHandles as $childHandle) {
                $childHandleText = $childHandle;
	            $title = '';
	            $color = 'green';
	            if (in_array($childHandle, $data['unloaded_css_handles'])) {
		            $color = '#cc0000';
		            $title = __('This CSS handle is already unloaded.', 'wp-asset-clean-up');
	            }
                $childHandlesOutput .= '<a title="'.$title.'" style="color:'.$color.';font-weight:300;" href="#wpacu_style_row_'.$childHandle.'"><span>'.$childHandleText.'</span></a>, ';
            }
            echo trim($childHandlesOutput, ', ');
            ?>
		</em>
		<label for="style_<?php echo $data['row']['obj']->handle; ?>_ignore_children">
			&#10230; <input id="style_<?php echo $data['row']['obj']->handle; ?>_ignore_children"
			                type="checkbox"
			                <?php if ($ignoreChild) { ?>checked="checked"<?php } ?>
			                name="wpacu_ignore_child[styles][<?php echo $data['row']['obj']->handle; ?>]"
			                value="1" /> <small><?php _e('Ignore dependency rule and keep the "children" loaded', 'wp-asset-clean-up'); ?>
				<?php if (in_array($data['row']['obj']->handle, \WpAssetCleanUp\Main::instance()->keepChildrenLoadedForHandles['css'])) { echo '(recommended)'; } ?>
            </small>
		</label>
	</div>
	<?php
}
