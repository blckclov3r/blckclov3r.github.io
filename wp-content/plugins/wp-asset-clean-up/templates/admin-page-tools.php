<?php
/*
 * No direct access to this file
 */
if (! isset($data)) {
	exit;
}

include_once '_top-area.php';

do_action('wpacu_admin_notices');
?>
<div class="wpacu-wrap wpacu-tools-area">
    <nav class="wpacu-tab-nav-wrapper nav-tab-wrapper">
        <a href="<?php echo admin_url('admin.php?page=wpassetcleanup_tools&wpacu_for=reset'); ?>" class="nav-tab <?php if ($data['for'] === 'reset') { ?>nav-tab-active<?php } ?>"><?php _e('Reset', 'wp-asset-clean-up'); ?></a>
        <a href="<?php echo admin_url('admin.php?page=wpassetcleanup_tools&wpacu_for=system_info'); ?>" class="nav-tab <?php if ($data['for'] === 'system_info') { ?>nav-tab-active<?php } ?>"><?php _e('System Info', 'wp-asset-clean-up'); ?></a>
        <a href="<?php echo admin_url('admin.php?page=wpassetcleanup_tools&wpacu_for=storage'); ?>" class="nav-tab <?php if ($data['for'] === 'storage') { ?>nav-tab-active<?php } ?>"><?php _e('Storage Info', 'wp-asset-clean-up'); ?></a>
        <a href="<?php echo admin_url('admin.php?page=wpassetcleanup_tools&wpacu_for=debug'); ?>" class="nav-tab <?php if ($data['for'] === 'debug') { ?>nav-tab-active<?php } ?>"><?php _e('Debugging', 'wp-asset-clean-up'); ?></a>
        <a href="<?php echo admin_url('admin.php?page=wpassetcleanup_tools&wpacu_for=import_export'); ?>" class="nav-tab <?php if ($data['for'] === 'import_export') { ?>nav-tab-active<?php } ?>"><?php _e('Import &amp; Export', 'wp-asset-clean-up'); ?></a>
    </nav>

	<div class="wpacu-tools-container">
		<form id="wpacu-tools-form" action="<?php echo admin_url('admin.php?page='.WPACU_PLUGIN_ID.'_tools'); ?>" method="post">
            <?php if ($data['for'] === 'reset') { ?>
                <div><label for="wpacu-reset-drop-down"><?php _e('Do you need to reset the plugin to its initial settings or reset all changes?', 'wp-asset-clean-up'); ?></label></div>

                <select name="wpacu-reset" id="wpacu-reset-drop-down">
                    <option value=""><?php _e('Select an option first', 'wp-asset-clean-up'); ?>...</option>
                    <option data-id="wpacu-warning-reset-settings" value="reset_settings"><?php _e('Reset "Settings"', 'wp-asset-clean-up'); ?></option>
                    <option data-id="wpacu-warning-reset-everything-except-settings" value="reset_everything_except_settings"><?php _e('Reset everything except "Settings"', 'wp-asset-clean-up'); ?></option>
                    <option data-id="wpacu-warning-reset-everything" value="reset_everything"><?php _e('Reset everything: "Settings", All Unloads (bulk &amp; per page) &amp; Load Exceptions', 'wp-asset-clean-up'); ?></option>
                </select>

				<div id="wpacu-license-data-remove-area">
					<label for="wpacu-remove-license-data">
						<input id="wpacu-remove-license-data" type="checkbox" name="wpacu-remove-license-data" value="1" /> <?php _e('Also remove any license data left from the Pro version if you ever used it (if you switched from Pro to Lite to make tests, but want to switch back to Pro, then do not choose this option)', 'wp-asset-clean-up'); ?>
					</label>
				</div>

                <div id="wpacu-cache-assets-remove-area">
                    <label for="wpacu-remove-cache-assets">
                        <input id="wpacu-remove-cache-assets" type="checkbox" name="wpacu-remove-cache-assets" value="1" /> <?php echo sprintf(__('Also remove any cached CSS/JS files from %s', 'wp-asset-clean-up'), '<code>/'.basename(WP_CONTENT_DIR) . \WpAssetCleanUp\OptimiseAssets\OptimizeCommon::getRelPathPluginCacheDir().'</code>'); ?> (please be careful as there might be cached pages - e.g. people previewing your page via Google Cache - still making reference to the CSS/JS files, you can leave it unchecked if you are not about it)
                    </label>
                </div>

                <div id="wpacu-warning-read"><span class="dashicons dashicons-warning"></span> <strong><?php _e('Please read carefully below what the chosen action does as this process is NOT reversible.', 'wp-asset-clean-up'); ?></strong></div>

                <div id="wpacu-warning-reset-settings" class="wpacu-warning">
                    <p><?php _e('This will reset every option from the "Settings" page/tab to the same state it was when you first activated the plugin.', 'wp-asset-clean-up'); ?></p>
                </div>

                <div id="wpacu-warning-reset-everything-except-settings" class="wpacu-warning">
                    <p><?php _e('This will reset everything (changes per page &amp; any load exceptions), except the values from "Settings".', 'wp-asset-clean-up'); ?></p>
                    <p><?php _e('This action is usually taken if you are happy with the "Settings" configuration, but want to clear everything else in terms of changes per page or group of pages.', 'wp-asset-clean-up'); ?></p>
                </div>

                <div id="wpacu-warning-reset-everything" class="wpacu-warning">
                    <p><?php _e('This will reset everything (settings, page loads &amp; any load exceptions) to the same point it was when you first activated the plugin. All the plugin\'s database records will be removed. It will technically have the same effect for your website as if the plugin would be deactivated.', 'wp-asset-clean-up'); ?></p>

                    <p><?php _e('This action is usually taken if:', 'wp-asset-clean-up'); ?></p>
                    <ul>
                        <li><?php echo sprintf(__('You believe you have applied some changes (such as unloading the wrong CSS / JavaScript file(s)) that broke the website and you need a quick fix to make it work the way it used to. Note that for this option, you can also enable "Test Mode" from the plugin\'s settings which will only apply the changes to you (logged-in administrator), while the regular visitors will view the website as if %s is deactivated.', 'wp-asset-clean-up'), WPACU_PLUGIN_TITLE); ?></li>
                        <li><?php echo sprintf(__('You want to uninstall Asset CleanUp and remove the traces left in the database (this is not the same thing as deactivating and activating the plugin again, as any changes applied would be preserved in this scenario)', 'wp-asset-clean-up'), WPACU_PLUGIN_TITLE); ?></li>
                    </ul>
                </div>

                <?php
                wp_nonce_field('wpacu_tools_reset', 'wpacu_tools_reset_nonce');
                ?>

                <input type="hidden" name="wpacu-tools-reset" value="1" />
                <input type="hidden" name="wpacu-action-confirmed" id="wpacu-action-confirmed" value="" />

                <div id="wpacu-reset-submit-area">
                    <button name="submit"
                            disabled="disabled"
                            id="wpacu-reset-submit-btn"
                            class="button button-secondary"><?php esc_attr_e('Submit', 'wp-asset-clean-up'); ?></button>
                </div>
            <?php } elseif ($data['for'] === 'system_info') {
	            wp_nonce_field('wpacu_get_system_info', 'wpacu_get_system_info_nonce');
	            ?>
                <input type="hidden" name="wpacu-get-system-info" value="1" />

                <textarea disabled="disabled"
                          style="color: rgba(51,51,51,1); background: #eee; white-space: pre; font-family: Menlo, Monaco, Consolas, 'Courier New', monospace; width: 99%; max-width: 100%;"
                          rows="20"><?php echo $data['system_info']; ?></textarea>

                <p><button name="submit"
                           id="wpacu-download-system-info-btn"
                           class="button button-primary"
                           style="font-size: 15px; line-height: 20px; padding: 3px 20px; height: 37px;">
                        <span style="padding-top: 1px;"
                              class="dashicons dashicons-download"></span>
                        <?php esc_attr_e('Download System Info For Support', 'wp-asset-clean-up'); ?>
                    </button>
                </p>
            <?php } ?>
		</form>

        <?php
        if ($data['for'] === 'storage') {
	        $currentStorageDirRel        = \WpAssetCleanUp\OptimiseAssets\OptimizeCommon::getRelPathPluginCacheDir();
	        $currentStorageDirFull       = WP_CONTENT_DIR . $currentStorageDirRel;
	        $currentStorageDirIsWritable = is_writable($currentStorageDirFull);

	        if (! $currentStorageDirIsWritable) {
		        ?>
                <div class="wpacu-warning" style="width: 98%;">
                    <p style="margin: 0;">
                        <span style="color: #cc0000;" class="dashicons dashicons-warning"></span>
				        <?php echo sprintf(
					        __('The system detected the storage directory as non-writable, thus the minify &amp; combine CSS/JS files feature will not work. Please %smake it writable%s or raise a ticket with your hosting company about this matter.', 'wp-asset-clean-up'),
					        '<a href="https://wordpress.org/support/article/changing-file-permissions/">',
					        '</a>'
				        ); ?>
                    </p>
                </div>
	        <?php }
	        ?>
            <p>
		        <?php _e('Current storage directory', 'wp-asset-clean-up'); ?>: <code><?php echo WP_CONTENT_DIR; ?><strong><?php echo $currentStorageDirRel; ?></strong></code>
                &nbsp; <?php if ($currentStorageDirIsWritable) {
			        echo '<span style="color: green;"><span class="dashicons dashicons-yes"></span> '.__('writable', 'wp-asset-clean-up').'</span>';
		        } ?>
            </p>

            <p><?php echo __('Depending on the current settings, a storage caching directory of the optimized files is needed', 'wp-asset-clean-up'); ?>. Reason being that specific CSS/JS files had to be altered and they are retrieved faster from the caching directory, rather than altering then "on the fly" on every page load. <span style="color: #004567;" class="dashicons dashicons-info"></span> <a target="_blank" href="https://assetcleanup.com/docs/?p=526">Read more</a></p>

            <?php
	        $storageStats = \WpAssetCleanUp\OptimiseAssets\OptimizeCommon::getStorageStats();

	        if (isset($storageStats['total_size'], $storageStats['total_files'])) {
		        ?>
                <p><?php _e('Total storage files', 'wp-asset-clean-up'); ?>: <strong><?php echo $storageStats['total_files']; ?></strong>, <?php echo $storageStats['total_size']; ?> of which <strong><?php echo $storageStats['total_files_assets']; ?></strong> are CSS/JS assets, <?php echo $storageStats['total_size_assets']; ?></p>
		        <?php
	        }

	        $cssJsDirMarker = '<span class="dashicons dashicons-yes-alt" style="font-size: 19px; vertical-align: top; color: green;"></span>';
	        ?>
            <p>The following list prints each directory (local path) and its size. Only the ones marked with <?php echo $cssJsDirMarker; ?> have CSS/JS files there. The other unmarked ones contain .json (for reference purposes), index.php or .htaccess file types.</p>
            <div class="wpacu-clearfix"></div>
            <?php
	        echo '<ul style="margin-top: 0;margin-left: 25px; list-style: disc;">';

	        foreach ($storageStats['dirs_files_sizes'] as $localDirPath => $localDirFileSizes) {
		        $localDirPath = trim($localDirPath);
		        $totalDirSize = array_sum($localDirFileSizes);

		        $cssJsDirMarkerOutput = '';
		        if (in_array($localDirPath, $storageStats['dirs_css_js'])) {
			        $cssJsDirMarkerOutput = $cssJsDirMarker;
		        }

		        $rowStyle = '';
		        if ($cssJsDirMarkerOutput) {
			        $rowStyle = 'background: rgba(0,0,0,.07); padding: 4px; display: inline;';
		        }

                echo '<li><div style="'.$rowStyle.'">'.$localDirPath.': <strong>'.\WpAssetCleanUp\Misc::formatBytes($totalDirSize).'</strong> '.$cssJsDirMarkerOutput.'</div></li>';
	        }

	        echo '</ul>';
            ?>
            <hr />
            <p><?php echo sprintf(__('On certain hosting platforms such as Pantheon, the number of writable directories is limited, in this case you have to change it to %s', 'wp-asset-clean-up'), '<code><strong>/uploads/asset-cleanup/</strong></code>'); ?></p>
            <p>
                <?php echo sprintf(
                    __('To change the relative directory, you have to add the following code to %s file within the root of your WordPress installation, where other constants are defined, above the line %s', 'wp-asset-clean-up'),
                '<em>wp-config.php</em>',
                    '<code><em>/* That\'s all, stop editing! Happy blogging. */</em></code>'
                );
                ?>
            </p>
            <p><code>define('WPACU_CACHE_DIR', '/uploads/asset-cleanup/');</code></p>
            <p><?php echo sprintf(
                    __('Note that the relative path is appended to %s', 'wp-asset-clean-up'),
                    '<em>'.WP_CONTENT_DIR.'/</em>'
                ); ?> which is the WordPress content directory.</p>
            <?php
        }

        if ($data['for'] === 'debug') {
	        $logPHPErrorsLocationFileSize = false;

            $isLogPHPErrors       = $data['error_log']['log_status'];
	        $logPHPErrorsLocation = $data['error_log']['log_file'];

	        if ($logPHPErrorsLocation !== 'none_set' && is_file($logPHPErrorsLocation)) {
		        $logPHPErrorsLocationFileSize = filesize($logPHPErrorsLocation);
		        $logPHPErrorsLocationFileSizeFormatted = \WpAssetCleanUp\Misc::formatBytes($logPHPErrorsLocationFileSize);
            }
            ?>
            <form method="post" action="">
                <p>In case you experience timeout errors, blank screens, 500 internal server errors and so on, it's a good idea to check the PHP error logs (they are usually activated in your PHP.ini configuration) for more information about the reason behind any issues you might have. The error log file (if any set), it meant to record PHP errors (either from Asset CleanUp or any other active plugin/theme, etc.).</p>
                <ul>
                    <li>Log PHP Errors Status: <strong><?php echo $isLogPHPErrors ? 'On' : 'Off'; ?></strong></li>
                    <li>Log PHP Errors Location: <code><?php echo $logPHPErrorsLocation; ?></code> <?php if($logPHPErrorsLocationFileSize) { ?> / <?php _e('File Size', 'wp-asset-clean-up'); ?>: <?php echo $logPHPErrorsLocationFileSizeFormatted; ?> &nbsp; <input style="vertical-align: middle;" type="submit" class="button button-primary" value="Download Error Log File" /><?php } ?></li>
                </ul>
                <input type="hidden" name="wpacu-get-error-log" value="1" />
            </form>
            <hr />
            <div>There are situations when you might want to access a certain page as if <?php echo WPACU_PLUGIN_TITLE; ?> is deactivated or you wish to access the page with only a part of the plugin's settings (without going through the standard deactivation and re-activation process which takes time). To do that, you can use the following query strings:</div>
            <style type="text/css">
                ul.wpacu-debug-list-params li {
                    margin-bottom: 15px;
                }
            </style>
            <ul class="wpacu-debug-list-params">
                <li><a href="<?php echo site_url().'/?wpacu_debug'; ?>"><code><?php echo site_url(); ?><strong>/?wpacu_debug</strong></code></a> - If you are logged-in, it will print a form at the bottom of the requested page with options to view the page with certain options deactivated (e.g. do not apply any JavaScript unloading), as well as print information about all the unloaded assets.</li>
                <li><a href="<?php echo site_url().'/?wpacu_no_load'; ?>"><code><?php echo site_url(); ?><strong>/?wpacu_no_load</strong></code></a> - On any page request if you apply this parameter, it will be like <?php echo WPACU_PLUGIN_TITLE; ?> is deactivated. The plugin's menu from the top admin bar (if shown), will also get hidden.</li>
                <li><a href="<?php echo site_url().'/?wpacu_clean_load'; ?>"><code><?php echo site_url(); ?><strong>/?wpacu_clean_load</strong></code></a> - This parameter is useful if you are checking a website that has lots of CSS/JS unloaded and combined by <?php echo WPACU_PLUGIN_TITLE; ?> or other performance plugin, but you want to view the page as if no optimization was applied (e.g. 20 CSS files were combined into one, but you want to see each one in the HTML source code, for debugging purposes). The difference between this option and "wpacu_no_load" is that it also attempts to prevent any optimization made by other performance plugins, not just from <?php echo WPACU_PLUGIN_TITLE; ?></li>
            </ul>
            <?php
        }

        if ($data['for'] === 'import_export') {
            ?>
            <div id="wpacu-import-area" class="wpacu-export-import-area">
                <form id="wpacu-import-form"
                      action="<?php echo admin_url('admin.php?page='.WPACU_PLUGIN_ID.'_tools&wpacu_for='.$data['for']); ?>"
                      method="post"
                      enctype="multipart/form-data">
                    <p><label for="wpacu-import-file">Please choose the exported JSON file and upload it for import:</label></p>
                    <p><input required="required" type="file" id="wpacu-import-file" name="wpacu_import_file" accept="application/json" /></p>
                    <p><button type="submit"
                               class="button button-secondary"
                               style="font-size: 15px; line-height: 20px; padding: 3px 12px; height: 37px;">
                                <span style="padding-top: 1px;"
                                      class="dashicons dashicons-upload"></span>
					        <?php esc_attr_e('Import', 'wp-asset-clean-up'); ?>
                            <img class="wpacu-spinner" src="<?php echo includes_url('images/wpspin-2x.gif'); ?>" alt="" />
                        </button> &nbsp;<small>* only .json extension allowed</small>
                    </p>
			        <?php wp_nonce_field('wpacu_do_import', 'wpacu_do_import_nonce'); ?>
                </form>

                <p><small><strong><span class="dashicons dashicons-warning"></span> Note:</strong> Make sure to properly test the pages of your website after you do the import to be sure the changes from the location you performed the export (e.g. staging) will work just as fine on the current server (e.g. live). The CSS/JS caching will be rebuilt after you're done with the import in case Minify/Combine CSS/JS is used.</small></p>
            </div>

            <hr />

            <div id="wpacu-export-area" class="wpacu-export-import-area">
                <form id="wpacu-export-form"
                      action="<?php echo admin_url('admin.php?page='.WPACU_PLUGIN_ID.'_tools&wpacu_for='.$data['for']); ?>"
                      method="post">
                    <p><label for="wpacu-export-selection">Please select what you would like to export:</label></p>
                    <p>
                        <select required="required" id="wpacu-export-selection" name="wpacu_export_for">
                            <option value="">Select an option first...</option>
                            <option value="settings">Settings</option>
                            <option value="everything">Everything</option>
                        </select>
                    </p>
                    <p><button type="submit"
                               class="button button-secondary"
                               style="font-size: 15px; line-height: 20px; padding: 3px 12px; height: 37px;">
                                <span style="padding-top: 1px;"
                                      class="dashicons dashicons-download"></span>
                            <?php esc_attr_e('Export', 'wp-asset-clean-up'); ?>
                        </button>
                    </p>
                    <?php wp_nonce_field('wpacu_do_export', 'wpacu_do_export_nonce'); ?>
                </form>
            </div>
        <?php
        }
        ?>
	</div>
</div>
