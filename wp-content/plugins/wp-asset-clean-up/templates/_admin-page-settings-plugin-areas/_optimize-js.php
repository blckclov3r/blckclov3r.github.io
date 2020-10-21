<?php
/*
 * No direct access to this file
 */
use WpAssetCleanUp\Misc;
use WpAssetCleanUp\OptimiseAssets\OptimizeCommon;

if (! isset($data, $selectedTabArea)) {
	exit;
}

global $wp_version;

$tabIdArea = 'wpacu-setting-optimize-js';
$styleTabContent = ($selectedTabArea === $tabIdArea) ? 'style="display: table-cell;"' : '';

// [wpacu_lite]
$availableForProAppendInlineJsLink = WPACU_PLUGIN_GO_PRO_URL . '?utm_source=optimize_js&utm_medium=append_assoc_inline_to_combined_js';
$availableForProInlineJsLink = WPACU_PLUGIN_GO_PRO_URL . '?utm_source=optimize_js&utm_medium=inline_chosen_js_files';
$availableForProMoveScriptsToBodyLink = WPACU_PLUGIN_GO_PRO_URL . '?utm_source=optimize_js&utm_medium=move_scripts_to_body';

$availableForProAppendInlineJs = '<a class="go-pro-link-no-style" target="_blank" href="'.$availableForProAppendInlineJsLink.'"><span class="wpacu-tooltip" style="width: 186px;">'.__('This is a feature available in the Pro version! Unlock it!', 'wp-asset-clean-up').'</span> <img style="opacity: 0.6;" width="20" height="20" src="'.WPACU_PLUGIN_URL.'/assets/icons/icon-lock.svg" valign="top" alt="" /></a>';
$availableForProInlineJs       = '<a class="go-pro-link-no-style" target="_blank" href="'.$availableForProInlineJsLink.'"><span class="wpacu-tooltip" style="width: 186px;">'.__('This is a feature available in the Pro version! Unlock it!', 'wp-asset-clean-up').'</span> <img style="opacity: 0.6;" width="20" height="20" src="'.WPACU_PLUGIN_URL.'/assets/icons/icon-lock.svg" valign="top" alt="" /></a>';
$availableForProMoveScriptsToBody = '<a class="go-pro-link-no-style" target="_blank" href="'.$availableForProMoveScriptsToBodyLink.'"><span class="wpacu-tooltip" style="width: 186px;">'.__('This is a feature available in the Pro version! Unlock it!', 'wp-asset-clean-up').'</span> <img style="opacity: 0.6;" width="20" height="20" src="'.WPACU_PLUGIN_URL.'/assets/icons/icon-lock.svg" valign="top" alt="" /></a>';
// [/wpacu_lite]
?>
<div id="<?php echo $tabIdArea; ?>" class="wpacu-settings-tab-content" <?php echo $styleTabContent; ?>>
    <h2 class="wpacu-settings-area-title"><?php _e('Minify / Combine loaded JavaScript files to reduce total page size and the number of HTTP requests', 'wp-asset-clean-up'); ?></h2>
	<?php
	if (Misc::isWpRocketMinifyHtmlEnabled()) {
		?>
        <div class="wpacu-warning" style="font-size: 13px; margin-bottom: 18px; border: 1px solid #cc000059;">
            <span class="dashicons dashicons-warning" style="color: #cc0000;"></span> <strong>Incompatibility Notice:</strong> At this time, "<strong>Combine loaded JS (JavaScript) into fewer files</strong>" option is not taking any effect as "<em>Minify HTML</em>" is active in "WP Rocket" -&gt; "File Optimization" Settings. If you wish to keep WP Rocket's Minify HTML on, consider optimizing JavaScript files with WP Rocket while cleaning the useless JavaScript with <?php echo WPACU_PLUGIN_TITLE; ?>.
        </div>
		<?php
	}
	?>
    <table class="wpacu-form-table">
        <tr valign="top">
            <th scope="row" class="setting_title">
                <label for="wpacu_minify_js_enable"><?php _e('JavaScript Files Minification', 'wp-asset-clean-up'); ?></label>
                <p class="wpacu_subtitle"><small><em><?php _e('Helps decrease the total page size even further', 'wp-asset-clean-up'); ?></em></small></p>
            </th>
            <td>
                <label class="wpacu_switch <?php if (! empty($data['is_optimize_js_enabled_by_other_party'])) { echo 'wpacu_disabled'; } ?>">
                    <input id="wpacu_minify_js_enable"
                           data-target-opacity="wpacu_minify_js_area"
                           type="checkbox"
                            <?php
                            echo (($data['minify_loaded_js'] == 1) ? 'checked="checked"' : '');
                            ?>
                           name="<?php echo WPACU_PLUGIN_ID . '_settings'; ?>[minify_loaded_js]"
                           value="1" /> <span class="wpacu_slider wpacu_round"></span></label>
                &nbsp;&nbsp;<?php _e('This will take the remaining enqueued JavaScript files, minify them and load them from the cache.', 'wp-asset-clean-up'); ?>

				<?php
				if (! empty($data['is_optimize_js_enabled_by_other_party'])) {
					?>
                    <div style="border-left: 4px solid green; background: #f2faf2; padding: 10px; margin-top: 10px;">
                        <ul style="margin: 0;">
                            <li>This option is locked as optimize/minify JavaScript (JS) is already enabled in the following plugins: <strong><?php echo implode(', ', $data['is_optimize_js_enabled_by_other_party']); ?></strong>. <?php echo WPACU_PLUGIN_TITLE; ?> works together with the mentioned plugin(s).</li>
                            <li>Eliminate the bloat first via <a href="<?php echo admin_url('admin.php?page=wpassetcleanup_assets_manager'); ?>">CSS & JAVASCRIPT LOAD MANAGER</a>, then minify the remaining JS with any plugin you prefer.</li>
                        </ul>
                    </div>
					<?php
				}

				$minifyJsExceptionsAreaStyle = empty($data['is_optimize_js_enabled_by_other_party']) && ($data['minify_loaded_js'] == 1) ? 'opacity: 1;' : 'opacity: 0.4;';
				?>
                <div id="wpacu_minify_js_area" style="<?php echo $minifyJsExceptionsAreaStyle; ?>">
                    <div style="padding: 10px; background: #f2faf2;" class="wpacu-fancy-checkbox">
                        <input id="minify_loaded_js_inline_checkbox"
                               name="<?php echo WPACU_PLUGIN_ID . '_settings'; ?>[minify_loaded_js_inline]"
							<?php echo (($data['minify_loaded_js_inline'] == 1) ? 'checked="checked"' : ''); ?>
                               type="checkbox"
                               value="1" />
                        <label for="minify_loaded_js_inline_checkbox"> Minify inline JavaScript content within SCRIPT tags</label>
                    </div>
                    <div id="wpacu_minify_js_exceptions_area">
                        <div style="margin: 8px 0 6px;"><?php _e('Do not minify the JavaScript files matching the patterns below (one per line)', 'wp-asset-clean-up'); ?>:</div>
                        <label for="wpacu_minify_js_exceptions">
                                        <textarea style="width: 100%;"
                                                  rows="4"
                                                  id="wpacu_minify_js_exceptions"
                                                  name="<?php echo WPACU_PLUGIN_ID . '_settings'; ?>[minify_loaded_js_exceptions]"><?php echo $data['minify_loaded_js_exceptions']; ?></textarea>
                        </label>
                    </div>
                    <ul style="list-style: none; margin-left: 18px; margin-bottom: 0;">
                        <li style="margin-bottom: 18px;"><span style="color: #ffc107;" class="dashicons dashicons-lightbulb"></span> The CSS/JS cached files will be re-generated once the file version changes (the value from <code>?ver=</code>). In addition, the versioning value from the source will be appended to the new cached CSS/JS file name (e.g. new-file-name-here-ver-1.2).</li>
                        <li><span style="color: #ffc107;" class="dashicons dashicons-lightbulb"></span> <?php _e('For maximum performance and to reduce server resources, the following JavaScript files will not be minified since they are already optimised and minified by the WordPress core contributors &amp; developers', 'wp-asset-clean-up'); ?>:
                            <div style="margin: 8px 0 0 28px;">
                                <ul style="list-style: disc;">
                                    <li>JS WordPress core files that end up in .min.js (e.g. <code>/wp-includes/js/jquery/jquery-migrate.min.js</code>, <code>/wp-includes/js/jquery/ui/datepicker.min.js</code> etc.)</li>
                                    <li><?php echo sprintf(__('jQuery library from %s', 'wp-asset-clean-up'), '<code>/wp-includes/js/jquery/jquery.js</code>'); ?></li>
                                </ul>
                            </div>
                        </li>
                    </ul>
                </div>
            </td>
        </tr>

        <tr>
            <td colspan="2" style="padding: 0;">
                <div class="wpacu-combine-notice-default wpacu_hide" style="line-height: 22px; background: #f8f8f8; border-left: 4px solid #008f9c; padding: 10px; margin: 0 0 15px;">
                    <strong><?php _e('NOTE', 'wp-asset-clean-up'); ?>:</strong> <?php echo __('Concatenating assets is no longer a recommended practice in HTTP/2', 'wp-asset-clean-up'); ?>. &nbsp; <a data-id="wpacu-http2-info-js" class="wpacu-http2-info-js-target" href="#wpacu-http2-info-js"><?php _e('Read more', 'wp-asset-clean-up'); ?></a> &nbsp;/&nbsp; <a class="wpacu_verify_http2_protocol" target="_blank" href="https://tools.keycdn.com/http2-test"><strong><?php _e('Verify if the website is delivered through the HTTP/2 network protocol', 'wp-asset-clean-up'); ?></strong></a>
                </div>
                <div class="wpacu-combine-notice-http-2-detected wpacu_hide" style="line-height: 22px; background: #f8f8f8; border-left: 4px solid #008f9c; padding: 10px; margin: 0 0 15px;">
                    <span class="wpacu_http2_protocol_is_supported" style="color: green; font-weight: 400;"><span class="dashicons dashicons-yes-alt"></span> Your website `<span style="font-weight: 500;"><?php echo get_site_url(); ?></span>` is delivered through the HTTP/2 network protocol, thus the website will be as fast without using this feature which might require maintenance once in a while.</span> <a class="wpacu-http2-info-js-target" href="#wpacu-http2-info-js"><?php _e('Read more', 'wp-asset-clean-up'); ?></a>
                </div>
            </td>
        </tr>

		<tr valign="top">
			<th scope="row" class="setting_title">
				<label for="wpacu_combine_loaded_js_enable"><?php _e('Combine loaded JS (JavaScript) into fewer files', 'wp-asset-clean-up'); ?></label>
				<p class="wpacu_subtitle"><small><em><?php _e('Helps reducing the number of HTTP Requests even further', 'wp-asset-clean-up'); ?></em></small></p>
			</th>
			<td>
				<label class="wpacu_switch <?php if (! empty($data['is_optimize_js_enabled_by_other_party'])) { echo 'wpacu_disabled'; } ?>">
					<input id="wpacu_combine_loaded_js_enable"
                           data-target-opacity="wpacu_combine_loaded_js_info_area"
					       type="checkbox"
						<?php
						echo (in_array($data['combine_loaded_js'], array('for_admin', 'for_all', 1)) ? 'checked="checked"' : '');
						?>
						   name="<?php echo WPACU_PLUGIN_ID . '_settings'; ?>[combine_loaded_js]"
						   value="1" /> <span class="wpacu_slider wpacu_round"></span> </label>

				&nbsp;<small>* if <code style="font-size: inherit;"><?php echo '/'.str_replace(ABSPATH, '', WP_CONTENT_DIR) . \WpAssetCleanUp\OptimiseAssets\OptimizeCommon::getRelPathPluginCacheDir(); ?></code> directory is not writable for some reason, this feature will not work; requires the DOMDocument XML DOM Parser to be enabled in PHP (which it is by default) for maximum performance</small>

				<?php
				if (! empty($data['is_optimize_js_enabled_by_other_party'])) {
					?>
                    <div style="border-left: 4px solid green; background: #f2faf2; padding: 10px; margin-top: 10px;">
                        <ul style="margin: 0;">
                            <li>This option is locked as optimize/minify JavaScript (JS) is already enabled in the following plugins: <strong><?php echo implode(', ', $data['is_optimize_js_enabled_by_other_party']); ?></strong>.</li>
                            <li><?php echo WPACU_PLUGIN_TITLE; ?> works together with the mentioned plugin(s). Eliminate the bloat first via <a href="<?php echo admin_url('admin.php?page=wpassetcleanup_assets_manager'); ?>">CSS & JAVASCRIPT LOAD MANAGER</a>, then concatenate (if necessary) the JS using any plugin you prefer.</li>
                        </ul>
                    </div>
					<?php
				}
				?>

				<div id="wpacu_combine_loaded_js_info_area" <?php if (empty($data['is_optimize_js_enabled_by_other_party']) && in_array($data['combine_loaded_js'], array('for_admin', 'for_all', 1))) { ?> style="opacity: 1;" <?php } else { ?>style="opacity: 0.4;"<?php } ?>>
                    <div style="margin-top: 8px; padding: 12px; background: #f2faf2; border-radius: 10px;">
                        <ul style="margin: 0;">
                            <li style="float: left; margin-right: 30px; margin-bottom: 0; line-height: 32px;" class="wpacu-fancy-radio">
                                <label for="combine_loaded_js_for_guests_radio">
                                    <input id="combine_loaded_js_for_guests_radio"
                                           style="margin: -1px 0 0;"
										<?php echo (in_array($data['combine_loaded_js_for'], array('guests', '')) ? 'checked="checked"' : ''); ?>
                                           type="radio"
                                           name="<?php echo WPACU_PLUGIN_ID . '_settings'; ?>[combine_loaded_js_for]"
                                           value="guests" />
                                    &nbsp;<?php _e('Apply it only for guest visitors', 'wp-asset-clean-up'); ?> (<?php echo __('default', 'wp-asset-clean-up'); ?>)
                                </label>
                            </li>
                            <li style="float: left; margin-bottom: 0; line-height: 32px;" class="wpacu-fancy-radio">
                                <label for="combine_loaded_js_for_all_radio">
                                    <input id="combine_loaded_js_for_all_radio"
                                           style="margin: -1px 0 0;"
										<?php echo (($data['combine_loaded_js_for'] === 'all') ? 'checked="checked"' : ''); ?>
                                           type="radio"
                                           name="<?php echo WPACU_PLUGIN_ID . '_settings'; ?>[combine_loaded_js_for]"
                                           value="all" />
                                    &nbsp;<?php _e('Apply it for all visitors (not recommended)', 'wp-asset-clean-up'); ?> * <small>to avoid using extra disk space</small>
                                </label>
                            </li>
                        </ul>
                        <div style="clear: both;"></div>
                    </div>

					<p style="margin-top: 10px;"><strong>Note:</strong> When a file is added to a combined group of files, the CDATA as well as any other inline content (e.g. added via <code style="font-size: inherit;">wp_add_inline_script()</code>) associated with it will also be added to the combined files. This reduces the number of DOM elements as well makes sure that, in case, the combined file is deferred, the code from the inline tags is triggered at the same time as the one from the file</p>

                    <p style="padding: 10px; background: #f2faf2;">
                        <label for="wpacu_combine_loaded_js_defer_body_checkbox">
                            <input id="wpacu_combine_loaded_js_defer_body_checkbox"
								<?php echo (($data['combine_loaded_js_defer_body'] == 1) ? 'checked="checked"' : ''); ?>
                                   type="checkbox"
                                   name="<?php echo WPACU_PLUGIN_ID . '_settings'; ?>[combine_loaded_js_defer_body]"
                                   value="1" />
                            Defer loading JavaScript combined files from <code>&lt;body&gt;</code> (applies <code>defer="defer"</code> attribute to the combined script tags)
                        </label>
                    </p>

                    <hr />

                    <div id="wpacu_combine_loaded_js_exceptions_area">
                        <div style="margin: 0 0 6px;"><?php _e('Do not combine the JavaScript files matching the patterns below (one per line, see pattern examples below)', 'wp-asset-clean-up'); ?>:</div>
                        <label for="combine_loaded_js_exceptions">
                                    <textarea style="width: 100%;"
                                              rows="4"
                                              id="combine_loaded_js_exceptions"
                                              name="<?php echo WPACU_PLUGIN_ID . '_settings'; ?>[combine_loaded_js_exceptions]"><?php echo $data['combine_loaded_js_exceptions']; ?></textarea>
                        </label>

                        <p><?php _e('Pattern Examples (you don\'t have to add the full URL, as it\'s recommended to use relative paths)', 'wp-asset-clean-up'); ?>:</p>
                        <code>/wp-includes/js/admin-bar.min.js<br />/wp-includes/js/masonry.min.js<br />/wp-content/plugins/plugin-title/js/(.*?).js</code>

                        <div style="margin-top: 15px; margin-bottom: 0;"><hr /></div>
                    </div>

					<!--
                               -->
					<p style="margin: 8px 0;">
						<?php _e('This results in as less JS combination groups as possible (this combines all JS files into 2/3 files, keeping their HEAD and BODY locations and most of the inline script tags before them for maximum compatibility)', 'wp-asset-clean-up'); ?> - <span style="color: #0073aa;" class="dashicons dashicons-info"></span> <a id="wpacu-combine-js-method-info-target" href="#wpacu-combine-js-method-info"><?php _e('Read more', 'wp-asset-clean-up'); ?></a>
					</p>

					<hr />

					<div class="clearfix"></div>

					<p style="margin: 8px 0;"><span style="color: #ffc107;" class="dashicons dashicons-lightbulb"></span> To be 100% sure everything works fine after activation, consider using the checkbox option above to apply the changes only for logged-in administrator (yourself). If all looks good, you can later uncheck so the changes will apply to everyone.</p>

					<hr />

					<p style="margin: 8px 0;"><span style="color: #ffc107;" class="dashicons dashicons-lightbulb"></span> Any scripts having "defer" or "async" attributes (which are there for a reason) will be combined in their own groups and not together with other render-blocking scripts.</p>

                    <hr />

					<p style="margin: 8px 0;"><span style="color: #ffc107;" class="dashicons dashicons-lightbulb"></span> This feature will not work <strong>IF</strong>:</p>
					<ul style="list-style: disc; margin-left: 35px; margin-bottom: 0;">
						<li>"Test Mode" is enabled and a guest (not logged-in) user visits the page, as the feature's ultimate purpose is to make the plugin inactive for non logged-in administrators for ultimate debugging.</li>
						<li>The URL has query strings (e.g. an URL such as //www.yourdomain.com/product/title-here/?param=1&amp;param_two=value_here)</li>
					</ul>
				</div>

				<!--
				-->
				<div id="wpacu-combine-js-method-info" class="wpacu-modal">
					<div class="wpacu-modal-content">
						<span class="wpacu-close">&times;</span>
						<h2><?php _e('How are the JavaScript files combined?', 'wp-asset-clean-up'); ?></h2>
						<p style="margin-top: 0;"><?php _e('The plugin scans the remaining JavaScript files (left after cleaning up the unnecessary ones) from the <code>&lt;head&gt;</code> and <code>&lt;body&gt;</code> locations and combines them into one file per each location.', 'wp-asset-clean-up'); ?></p>
						<p><?php _e('Any inline JavaScript code associated with the combined scripts, will not be altered or moved in any way.', 'wp-asset-clean-up'); ?></p>
						<p><strong><?php _e('Example', 'wp-asset-clean-up'); ?>:</strong> <?php _e('If you have 5 JS files (including jQuery library) loading in the <code>&lt;head&gt;</code> location and 7 JS files loading in <code>&lt;body&gt;</code> location, you will end up with a total of 3 JS files: jQuery library &amp; jQuery Migrate (they are not combined together with other JS files for maximum performance) in 1 file and the 2 JS files for HEAD and BODY, respectively.', 'wp-asset-clean-up'); ?></p>
					</div>
				</div>
			</td>
		</tr>

        <tr valign="top">
            <th scope="row" class="setting_title">
                <label for="wpacu_inline_js_files_enable"><?php _e('Inline JavaScript Files', 'wp-asset-clean-up'); ?> <?php echo $availableForProInlineJs; ?></label>
                <p class="wpacu_subtitle"><small><em><?php _e('This will work for local (same domain) files. External requests tags will not be altered (e.g. cdnjs.cloudflare.com, ajax.googleapis.com etc.).', 'wp-asset-clean-up'); ?></em></small></p>
            </th>
            <td>
                <label class="wpacu_switch wpacu_disabled"><!-- Disabled: Available only in the Pro version -->
                    <input id="wpacu_inline_js_files_enable"
                           data-target-opacity="wpacu_inline_js_files_info_area"
                           type="checkbox"
                            <?php
                            echo (($data['inline_js_files'] == 1) ? 'checked="checked"' : '');
                            ?>
                           name="<?php echo WPACU_PLUGIN_ID . '_settings'; ?>[inline_js_files]"
                           value="1" /> <span class="wpacu_slider wpacu_round"></span> </label>

                &nbsp;<?php _e('This is usually good for small JavaScript files to save the overhead of fetching them and thus reduce the number of HTTP requests', 'wp-asset-clean-up'); ?>. You can choose automatic inlining for JS files smaller than a specific size (in KB) or manually place the relative paths to the files (e.g. in case there is an exception for a larger file you wish to inline or just don't want to use the automatic inlining).
			    <?php
			    if (! empty($data['is_optimize_js_enabled_by_other_party'])) {
                ?>
                    <div style="border-left: 4px solid green; background: #f2faf2; padding: 10px; margin-top: 10px;">
                        <ul style="margin: 0;">
                            <li>This option is not available as optimize/minify JavaScript (JS) is already enabled in the following plugins: <strong><?php echo implode(', ', $data['is_optimize_js_enabled_by_other_party']); ?></strong>.</li>
                            <li><?php echo WPACU_PLUGIN_TITLE; ?> works together with the mentioned plugin(s). Eliminate the bloat first via <a href="<?php echo admin_url('admin.php?page=wpassetcleanup_assets_manager'); ?>">CSS & JAVASCRIPT LOAD MANAGER</a>, then concatenate (if necessary) the JS using any plugin you prefer.</li>
                        </ul>
                    </div>
                <?php
			    }
			    ?>

                <div id="wpacu_inline_js_files_info_area" <?php if (empty($data['is_optimize_css_enabled_by_other_party']) && $data['inline_js_files'] == 1) { ?> style="opacity: 1;" <?php } else { ?>style="opacity: 0.4;"<?php } ?>>
                    <p class="wpacu-warning" style="margin: 10px 0; font-size: 13px; padding: 4px 9px;">
                        <small><strong style="color: orange;"><span class="dashicons dashicons-warning"></span></strong> Please be extra careful if you decide to use this feature as inlining JavaScript files can be trickier than inlining CSS ones due to the more complex syntax and various attributes that might set to the external JS file such as "async" &amp; "defer" (the content of any JS having this attribute will be wrapped between <code>document.addEventListener('DOMContentLoaded', function() {</code> and <code>});</code>.</small>
                    </p>

                    <p style="margin-top: 8px; padding: 10px; background: #f2faf2;">
                        <label for="wpacu_inline_js_files_below_size_checkbox">
                            <input id="wpacu_inline_js_files_below_size_checkbox"
				                <?php echo ($data['inline_js_files_below_size'] == 1 ? 'checked="checked"' : ''); ?>
                                   type="checkbox"
                                   name="<?php echo WPACU_PLUGIN_ID . '_settings'; ?>[inline_js_files_below_size]"
                                   value="1" />
			                <?php echo sprintf(__('Inline JavaScript (.js) Files Smaller Than %s KB', 'wp-asset-clean-up'), '<input type="number" min="1" style="width: 60px;" name="'.WPACU_PLUGIN_ID.'_settings[inline_js_files_below_size_input]" value="'.$data['inline_js_files_below_size_input'].'" />'); ?>
                        </label>
                    </p>

                    <div id="wpacu_inline_js_files_list_area">
                        <div style="margin: 12px 0 6px;"><?php _e('Alternatively or in addition to automatic inlining, you can place the relative path(s) or part of them to the files you wish to inline below:', 'wp-asset-clean-up'); ?> (<strong><?php _e('one per line', 'wp-asset-clean-up'); ?></strong>):</div>
                        <p style="margin-top: 8px;"><span class="dashicons dashicons-warning" style="color: #ffc107;"></span> <strong>Note:</strong> Please input the sources to the original JavaScript files (one per line) like in the examples below, not to the cached/optimized ones (which are usually located in <em><?php echo str_replace(site_url(), '', WP_CONTENT_URL) . OptimizeCommon::getRelPathPluginCacheDir(); ?></em>)</p>
                        <label for="wpacu_inline_js_files_list">
                                    <textarea style="width: 100%;"
                                              rows="4"
                                              id="wpacu_inline_js_files_list"
                                              name="<?php echo WPACU_PLUGIN_ID . '_settings'; ?>[inline_js_files_list]"><?php echo $data['inline_js_files_list']; ?></textarea>
                        </label>
                        <p style="margin-bottom: 6px;"><strong>Examples</strong> (you don't have to add the full URL, as it's recommended to use relative paths, especially if you use dev/staging environments or change the domain name of your website):</p>
                        <code>/wp-content/plugins/plugin-title/scripts/small-file.js<br />/wp-content/themes/my-wp-theme-dir/js/small.js</code>
                    </div>
                </div>
            </td>
        </tr>

        <tr valign="top">
            <th scope="row" class="setting_title">
                <label for="wpacu_move_inline_jquery_after_src_tag_enable"><?php _e('Move jQuery Inline Code After jQuery library is called', 'wp-asset-clean-up'); ?></label>
                <p class="wpacu_subtitle"><small><em><?php _e('Please enable it only if you have JavaScript errors showing up in the browser\'s console related to jQuery.', 'wp-asset-clean-up'); ?>.</em></small></p>
            </th>
            <td>
                <label class="wpacu_switch">
                    <input id="wpacu_move_inline_jquery_after_src_tag_enable"
                           data-target-opacity="wpacu_move_inline_jquery_after_src_tag_info_area"
                           type="checkbox"
					    <?php
					    echo (($data['move_inline_jquery_after_src_tag'] == 1) ? 'checked="checked"' : '');
					    ?>
                           name="<?php echo WPACU_PLUGIN_ID . '_settings'; ?>[move_inline_jquery_after_src_tag]"
                           value="1" /> <span class="wpacu_slider wpacu_round"></span> </label>

                &nbsp;<?php _e('Useful in case plugins/themes insert jQuery inline code in the post/page content (e.g. via shortcodes) or inside the HEAD section before jQuery library is called.', 'wp-asset-clean-up'); ?> <span style="color: #0073aa;" class="dashicons dashicons-info"></span> <a id="wpacu-move-inline-jquery-target" href="#wpacu-move-inline-jquery"><?php _e('View Examples', 'wp-asset-clean-up'); ?></a>
	            <?php
	            $moveInlineJQueryAfterSrcTagStyle = ($data['move_inline_jquery_after_src_tag'] == 1) ? 'opacity: 1;' : 'opacity: 0.4;';
	            ?>
                <div id="wpacu_move_inline_jquery_after_src_tag_info_area" style="<?php echo $moveInlineJQueryAfterSrcTagStyle; ?>">
                    <p><?php _e('This feature moves any of these inline SCRIPT tags after the HTML tag that loads jQuery (generated for the "jquery-core" handle) aiming to fix "jQuery is undefined errors".', 'wp-asset-clean-up'); ?></p>
                    <p>The option should be enabled if any of the following applies:</p>
                    <ul style="list-style: disc; margin-left: 20px;">
                        <li>You have moved "jquery-core" to BODY to reduce render-blocking resources and you get "jQuery is undefined" errors because inline jQuery scripts are loaded (usually hardcoded) before the jQuery library tag.</li>
                        <li>jQuery already loads in the HEAD (that's the default behaviour), but inline jQuery code is printed (e.g. hardcoded &amp; without using the function <code>wp_add_inline_script()</code> with 'jquery' dependency) before jQuery library's script tag (either from a plugin or the theme).</li>
                    </ul>
                </div>
            </td>
        </tr>

        <!-- [wpacu_pro] -->
        <tr valign="top">
            <th scope="row" class="setting_title">
                <label for="wpacu_move_scripts_to_body_enable"><?php _e('Move All <code>&lt;SCRIPT&gt;</code> tags From HEAD to BODY', 'wp-asset-clean-up'); ?> <?php echo $availableForProMoveScriptsToBody; ?></label>
                <p class="wpacu_subtitle"><small><em><?php _e('This triggers late after all other optimizations are applied for maximum compatibility', 'wp-asset-clean-up'); ?>.</em></small></p>
            </th>
            <td>
                <label class="wpacu_switch wpacu_disabled"><!-- Disabled: Available only in the Pro version -->
                    <input id="wpacu_move_scripts_to_body_enable"
                           data-target-opacity="wpacu_move_scripts_to_body_info_area"
                           type="checkbox"
					    <?php
					    echo (($data['move_scripts_to_body'] == 1) ? 'checked="checked"' : '');
					    ?>
                           name="<?php echo WPACU_PLUGIN_ID . '_settings'; ?>[move_scripts_to_body]"
                           value="1" /> <span class="wpacu_slider wpacu_round"></span> </label>
                &nbsp;<?php _e('This is useful if you want to reduce render-blocking resources and it will move all the <code>&lt;SCRIPT&gt;</code> tags (inline &amp; external) right after the <code>&lt;BODY&gt;</code> opening tag', 'wp-asset-clean-up'); ?>. <span style="color: #0073aa;" class="dashicons dashicons-info"></span> <a id="wpacu-move-scripts-to-body-examples-target" href="#wpacu-move-scripts-to-body-examples"><?php _e('View Examples', 'wp-asset-clean-up'); ?></a>

	            <?php
	            $moveScriptsToBodyStyle = ($data['move_scripts_to_body'] == 1) ? 'opacity: 1;' : 'opacity: 0.4;';
	            ?>
                <div id="wpacu_move_scripts_to_body_info_area" style="<?php echo $moveScriptsToBodyStyle; ?>">
                    <p>The option could be enabled if any of the following applies:</p>
                    <ul style="list-style: disc; margin-left: 20px;">
                        <li>There are a few SCRIPT tags left in the HEAD (usually hardcoded and not manageable in the CSS/JS list) and you are sure they can be moved to the BODY without any issues.</li>
                        <li>jQuery library is loaded in the HEAD (sometimes the only render-blocking resources there) and you had difficulties moving it to the BODY as there are themes/plugins which are hardcoding it in the HEAD or dequeue &amp; enqueue it incorrectly.</li>
                    </ul>
                    <p>In case there is JavaScript that you wish to keep in the HEAD, you can add a unique string from the SCRIPT tag(s) in the textarea below (one per line):</p>
                    <label for="wpacu_move_scripts_to_body_exceptions">
                        <textarea style="width: 100%;"
                                  rows="4"
                                  id="wpacu_move_scripts_to_body_exceptions"
                                  name="<?php echo WPACU_PLUGIN_ID . '_settings'; ?>[move_scripts_to_body_exceptions]"><?php echo $data['move_scripts_to_body_exceptions']; ?></textarea>
                    </label>
                    <p><small><strong>Note:</strong> A situation when SCRIPT tags should be loaded in the <code>&lt;HEAD&gt;</code> is when the page gets loaded as an AMP (Accelerated Mobile Page) via various plugins. If have SCRIPT tags loading .js from cdn.ampproject.org, you can add a unique string to the exclusion such as <code>//cdn.ampproject.org/</code> which will detects tags like <code>&lt;script type='text/javascript' src='https://cdn.ampproject.org/v0.js' async&gt;&lt;/script&gt;</code> and keep them in the &lt;HEAD&gt;</small></p>
                </div>
            </td>
        </tr>
        <!-- [/wpacu_pro] -->

        <tr valign="top">
            <th scope="row" class="setting_title">
                <label for="wpacu_cache_dynamic_loaded_js_enable"><?php _e('Cache Dynamic Loaded JavaScript', 'wp-asset-clean-up'); ?></label>
                <p class="wpacu_subtitle"><small><em><?php _e('This option is enabled by default on new installs or after a settings reset', 'wp-asset-clean-up'); ?>.</em></small></p>
            </th>
            <td>
                <label class="wpacu_switch">
                    <input id="wpacu_cache_dynamic_loaded_js_enable"
                           data-target-opacity="wpacu_cache_dynamic_loaded_js_info_area"
                           type="checkbox"
					    <?php
					    echo (($data['cache_dynamic_loaded_js'] == 1) ? 'checked="checked"' : '');
					    ?>
                           name="<?php echo WPACU_PLUGIN_ID . '_settings'; ?>[cache_dynamic_loaded_js]"
                           value="1" /> <span class="wpacu_slider wpacu_round"></span> </label>

                &nbsp;<?php _e('Avoid loading the whole WP environment whenever a dynamic request is made such as <code>/?custom-javascript=value_here</code>, or <code>/wp-content/plugins/plugin-name-here/js/generate-script-output.php?ver=1</code>', 'wp-asset-clean-up'); ?>
                <p>e.g. <code>&lt;script type="text/javascript" src="//yourwebsite.com/wp-content/plugins/plugin-name-here/js/generate-script-output.php?ver=<?php echo $wp_version; ?>"&gt;&lt;/script&gt;</code></p>
                <hr />
			    <?php
			    $cacheDynamicLoadedJsAreaStyle = ($data['cache_dynamic_loaded_js'] == 1) ? 'opacity: 1;' : 'opacity: 0.4;';
			    ?>
                <div id="wpacu_cache_dynamic_loaded_js_info_area" style="<?php echo $cacheDynamicLoadedJsAreaStyle; ?>">
                    <p>Some plugins and themes have options to create your own layout / customise specific functionality settings and save the changes from the Dashboard. Instead of creating static JS files from the saved settings, the changes you made are retrieved from the database and the JavaScript content is created "on the fly", thus using more resources by loading the whole WP environment and make MySQL (or whatever database type if used) requests in order to print the JavaScript content. <?php echo WPACU_PLUGIN_TITLE; ?> detects such requests and caches the output for faster retrieval. This very important especially if your website has lots of visits (imagine WordPress loading several times only from one visitor) and you're on a shared environment with limited resources. This will also make the user experience better by decreasing the page rendering time.</p>
                </div>
            </td>
        </tr>
	</table>
</div>

<div id="wpacu-http2-info-js" class="wpacu-modal" style="padding-top: 100px;">
    <div class="wpacu-modal-content" style="max-width: 800px;">
        <span class="wpacu-close">&times;</span>
        <h2 style="margin-top: 5px;"><?php _e('Combining JavaScript files in HTTP/2 protocol', 'wp-asset-clean-up'); ?></h2>
        <p><?php _e('While it\'s still a good idea to combine assets into fewer (or only one) files in HTTP/1 (since you are restricted to the number of open connections), doing the same in HTTP/2 is no longer a performance optimization due to the ability to transfer multiple small files simultaneously without much overhead.', 'wp-asset-clean-up'); ?></p>

        <hr />

        <p><?php _e('In HTTP/2 some of the issues that were addressed are', 'wp-asset-clean-up'); ?>:</p>
        <ul>

            <li><strong>Multiplexing</strong>: <?php _e('allows concurrent requests across a single TCP connection', 'wp-asset-clean-up'); ?></li>
            <li><strong>Server Push</strong>: <?php _e('whereby a server can push vital resources to the browser before being asked for them.', 'wp-asset-clean-up'); ?></li>
        </ul>

        <hr />

        <p><?php _e('Since HTTP requests are loaded concurrently in HTTP/2, it\'s better to only serve the files that your visitors need and don\'t worry much about concatenation.', 'wp-asset-clean-up'); ?></p>
        <p><?php _e('Note that page speed testing tools such as PageSpeed Insights, YSlow, Pingdom Tools or GTMetrix still recommend combining CSS/JS files because they haven\'t updated their recommendations based on HTTP/1 or HTTP/2 protocols so you should take into account the actual load time, not the performance grade.', 'wp-asset-clean-up'); ?></p>

        <hr />

        <p style="margin-bottom: 12px;"><?php _e('If you do decide to move on with the concatenation (which at least would improve the GTMetrix performance grade from a cosmetic point of view), please remember to <strong>test thoroughly</strong> the pages that have the assets combined (pay attention to any JavaScript errors in the browser\'s console which is accessed via right click &amp; "Inspect") as, in rare cases, due to the order in which the scripts were loaded and the way their code was written, it could break some functionality.', 'wp-asset-clean-up'); ?></p>
    </div>
</div>

<div id="wpacu-move-inline-jquery" class="wpacu-modal" style="padding-top: 80px;">
    <div class="wpacu-modal-content" style="max-width: 800px;">
        <span class="wpacu-close">&times;</span>
        <h2 style="margin-top: 5px;"><?php _e('Examples of jQuery related inline code moved after the library is called', 'wp-asset-clean-up'); ?></h2>

        <p><strong>Example #1 (without jQuery Migrate loaded)</strong></p>
        <span>FROM</span>
        <p style="margin-top: 0;">
            <code>&lt;script type="text/javascript"&gt;jQuery(document).ready(function($) { /* code here */ });&lt;/script&gt;</code><br />
            <code>&lt;script type="text/javascript" src="/wp-includes/js/jquery.js"&gt;&lt;/script&gt;</code>
        </p>
        <span>TO</span>
        <p style="margin-top: 0;">
            <code>&lt;script type="text/javascript" src="/wp-includes/js/jquery.js"&gt;&lt;/script&gt;</code><br />
            <code>&lt;script type="text/javascript"&gt;jQuery(document).ready(function($) { /* code here */ });&lt;/script&gt;</code>
        </p>

        <hr />

        <p><strong>Example #2 (with jQuery Migrate loaded)</strong></p>
        <span>FROM</span>
        <p style="margin-top: 0;">
            <code>&lt;script type="text/javascript"&gt;jQuery(document).ready(function($) { /* code here */ });&lt;/script&gt;</code><br />
            <code>&lt;script type="text/javascript"&gt;$(document).ready(function() { /* another code here */ });&lt;/script&gt;</code><br />
            <code>&lt;script type="text/javascript" src="/wp-includes/js/jquery.js"&gt;&lt;/script&gt;</code><br />
            <code>&lt;script type="text/javascript" src="/wp-includes/js/jquery-migrate.min.js"&gt;&lt;/script&gt;</code>
        </p>
        <span>TO</span>
        <p style="margin-top: 0;">
            <code>&lt;script type="text/javascript" src="/wp-includes/js/jquery.js"&gt;&lt;/script&gt;</code><br />
            <code>&lt;script type="text/javascript" src="/wp-includes/js/jquery-migrate.min.js"&gt;&lt;/script&gt;</code><br />
            <code>&lt;script type="text/javascript"&gt;jQuery(document).ready(function($) { /* code here */ });&lt;/script&gt;</code><br />
            <code>&lt;script type="text/javascript"&gt;$(document).ready(function() { /* another code here */ });&lt;/script&gt;</code>
        </p>
    </div>
</div>

<div id="wpacu-move-scripts-to-body-examples" class="wpacu-modal" style="padding-top: 70px;">
    <div class="wpacu-modal-content" style="max-width: 800px;">
        <span class="wpacu-close">&times;</span>
        <h2 style="margin-top: 5px;"><?php _e('Examples of SCRIPTS moved from HEAD to BODY', 'wp-asset-clean-up'); ?></h2>

        <span><strong>FROM</strong></span>
        <pre style="margin-top: 4px; margin-bottom: 8px; white-space: pre;">
<code>&lt;head&gt;</code>
    <code>&lt;title&gt;Your page title here&lt;/title&gt;</code>
    <code>...</code>
    <code>...</code>
    <code>&lt;script type="text/javascript" src="/wp-includes/js/jquery.js"&gt;&lt;/script&gt;</code>
    <code>&lt;script type="text/javascript"&gt;/* code here */&lt;/script&gt;</code>
<code>&lt;/head&gt;</code>
<code>&lt;body&gt;</code>
    <code>...</code>
<code>&lt;/body&gt;</code>
        </pre>

        <div style="margin-top: -6px; margin-bottom: 14px;"><hr /></div>

        <span><strong>TO</strong></span>
        <pre style="margin-top: 4px; margin-bottom: 0; white-space: pre;">
<code>&lt;head&gt;</code>
    <code>&lt;title>Your page title here&lt;/title&gt;</code>
    <code>...</code>
    <code>...</code>
<code>&lt;/head&gt;</code>
<code>&lt;body&gt;</code>
    <code>&lt;script type="text/javascript" src="/wp-includes/js/jquery.js"&gt;&lt;/script&gt;</code>
    <code>&lt;script type="text/javascript"&gt;/* code here */&lt;/script&gt;</code>
    <code>...</code>
<code>&lt;/body&gt;</code>
        </pre>
    </div>
</div>