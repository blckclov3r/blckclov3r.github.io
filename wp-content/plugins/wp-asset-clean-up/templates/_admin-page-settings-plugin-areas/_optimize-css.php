<?php
/*
 * No direct access to this file
 */
use WpAssetCleanUp\Misc;
use WpAssetCleanUp\OptimiseAssets\OptimizeCommon;
use WpAssetCleanUp\OptimiseAssets\OptimizeCss;

if (! isset($data, $selectedTabArea)) {
    exit;
}

global $wp_version;

$tabIdArea = 'wpacu-setting-optimize-css';
$styleTabContent = ($selectedTabArea === $tabIdArea) ? 'style="display: table-cell;"' : '';

// [wpacu_lite]
$availableForPro = '<a class="go-pro-link-no-style" target="_blank" href="' . WPACU_PLUGIN_GO_PRO_URL . '?utm_source=plugin_usage_settings&utm_medium=local_fonts_optimization"><span class="wpacu-tooltip" style="width: 186px;">'.__('This is a feature available in the Pro version! Unlock it!', 'wp-asset-clean-up').'</span> <img style="opacity: 0.6;" width="20" height="20" src="'.WPACU_PLUGIN_URL.'/assets/icons/icon-lock.svg" valign="top" alt="" /></a>';
// [/wpacu_lite]
?>
<div id="<?php echo $tabIdArea; ?>" class="wpacu-settings-tab-content" <?php echo $styleTabContent; ?>>
    <h2 class="wpacu-settings-area-title"><?php _e('Minify / Combine loaded CSS files to reduce total page size and the number of HTTP requests', 'wp-asset-clean-up'); ?></h2>
	<?php
    $wpRocketIssues = array();

	if (($wpRocketIssues['minify_html'] = Misc::isWpRocketMinifyHtmlEnabled())
        || ($wpRocketIssues['optimize_css_delivery'] = OptimizeCss::isWpRocketOptimizeCssDeliveryEnabled())) {
		?>
        <div class="wpacu-warning" style="font-size: 13px; margin-bottom: 18px; border: 1px solid #cc000059;">
            <span class="dashicons dashicons-warning" style="color: #cc0000;"></span> <strong>Incompatibility Notice:</strong>
            <?php if (isset($wpRocketIssues['minify_html']) && $wpRocketIssues['minify_html']) { ?>
                <p style="margin-bottom: 0;">At this time, "<strong>Combine loaded CSS (Stylesheets) into fewer files</strong>" &amp; "<strong>Defer CSS Loaded in the &lt;BODY&gt; (Footer)</strong>" options do not take any effect as "<em>Minify HTML</em>" is active in "WP Rocket" -&gt; "File Optimization" Settings. If you wish to keep WP Rocket's Minify HTML on, consider optimizing CSS with WP Rocket while cleaning the useless CSS with <?php echo WPACU_PLUGIN_TITLE; ?>.</p>
            <?php } ?>
	        <?php if (isset($wpRocketIssues['optimize_css_delivery']) && $wpRocketIssues['optimize_css_delivery']) { ?>
                <p style="margin-bottom: 0;"><?php echo WPACU_PLUGIN_TITLE; ?>'s "<strong>Combine loaded CSS (Stylesheets) into fewer files</strong>" &amp; "<strong>Defer CSS Loaded in the &lt;BODY&gt; (Footer)</strong>" options do not take any effect as "<em>Optimize CSS Delivery</em>" is active in "WP Rocket" -&gt; "File Optimization" Settings. The feature is changing the way CSS is delivered by adding critical CSS to the HEAD section of the website as well as preloading the rest of the CSS files before applying their syntax on page loading. This doesn't affect the performance of your website as you can eliminate the bloat with <?php echo WPACU_PLUGIN_TITLE; ?> and use WP Rocket for CSS Optimization/Delivery if that's what works best for your website.</p>
	        <?php } ?>
        </div>
    <?php
	}
	?>
    <table class="wpacu-form-table">
        <tr valign="top">
            <th scope="row" class="setting_title">
                <label for="wpacu_minify_css_enable"><?php _e('CSS Files Minification', 'wp-asset-clean-up'); ?></label>
                <p class="wpacu_subtitle"><small><em><?php _e('Helps decrease the total page size even further', 'wp-asset-clean-up'); ?></em></small></p>
            </th>
            <td>
                <label class="wpacu_switch <?php if (! empty($data['is_optimize_css_enabled_by_other_party'])) { echo 'wpacu_disabled'; } ?>">
                    <input id="wpacu_minify_css_enable"
                           data-target-opacity="wpacu_minify_css_area"
                           type="checkbox"
                           <?php
                           echo (($data['minify_loaded_css'] == 1) ? 'checked="checked"' : '');
                           ?>
                           name="<?php echo WPACU_PLUGIN_ID . '_settings'; ?>[minify_loaded_css]"
                           value="1" /> <span class="wpacu_slider wpacu_round"></span> </label>

                &nbsp;<?php _e('This will take the remaining enqueued CSS files, minify them and load them from the cache.', 'wp-asset-clean-up'); ?>

                <?php
                if (! empty($data['is_optimize_css_enabled_by_other_party'])) {
                    ?>
                    <div style="border-left: 4px solid green; background: #f2faf2; padding: 10px; margin-top: 10px;">
                        <ul style="margin: 0;">
                            <li>This option is locked as optimize/minify stylesheets (CSS) is already enabled in the following plugins: <strong><?php echo implode(', ', $data['is_optimize_css_enabled_by_other_party']); ?></strong>. <?php echo WPACU_PLUGIN_TITLE; ?> works together with the mentioned plugin(s).</li>
                            <li>Eliminate the bloat first via <a href="<?php echo admin_url('admin.php?page=wpassetcleanup_assets_manager'); ?>">CSS & JAVASCRIPT LOAD MANAGER</a>, then minify the remaining CSS with any plugin you prefer.</li>
                        </ul>
                    </div>
                    <?php
                }

				$minifyCssExceptionsAreaStyle = empty($data['is_optimize_css_enabled_by_other_party']) && ($data['minify_loaded_css'] == 1) ? 'opacity: 1;' : 'opacity: 0.4;';
				?>
                <div id="wpacu_minify_css_area" style="<?php echo $minifyCssExceptionsAreaStyle; ?>">
                    <div style="padding: 10px; background: #f2faf2;" class="wpacu-fancy-checkbox">
                        <input id="minify_loaded_css_inline_checkbox"
                               name="<?php echo WPACU_PLUGIN_ID . '_settings'; ?>[minify_loaded_css_inline]"
                               <?php echo (($data['minify_loaded_css_inline'] == 1) ? 'checked="checked"' : ''); ?>
                               type="checkbox"
                               value="1" />
                        <label for="minify_loaded_css_inline_checkbox"> Minify inline CSS content within STYLE tags</label>
                    </div>

                    <div id="wpacu_minify_css_exceptions_area">
                        <div style="margin: 0 0 6px;"><?php _e('Do not minify the CSS files matching the patterns below (one per line)', 'wp-asset-clean-up'); ?>:</div>
                        <label for="wpacu_minify_css_exceptions">
                                        <textarea style="width: 100%;"
                                                  rows="4"
                                                  id="wpacu_minify_css_exceptions"
                                                  name="<?php echo WPACU_PLUGIN_ID . '_settings'; ?>[minify_loaded_css_exceptions]"><?php echo $data['minify_loaded_css_exceptions']; ?></textarea>
                        </label>
                    </div>
                    <ul style="list-style: none; margin-left: 18px; margin-bottom: 0;">
                        <li style="margin-bottom: 18px;"><span style="color: #ffc107;" class="dashicons dashicons-lightbulb"></span> The stylesheets (.css) cached files will be re-generated once the file version changes (the value from <code>?ver=</code>). In addition, the version number (value) from the source will be appended to the new cached .css file name (e.g. new-file-name-here-ver-1.2.css).</li>
                        <li><span style="color: #ffc107;" class="dashicons dashicons-lightbulb"></span> <?php _e('For maximum performance and to reduce server resources, the following stylesheet files will not be minified since they are already optimised and minified by the WordPress core contributors &amp; developers', 'wp-asset-clean-up'); ?>:
                            <div style="margin: 15px 0 0 28px;">
                                <ul style="list-style: disc;">
                                    <li>CSS WordPress core files that end up in .min.css (e.g. <code>/wp-includes/css/dashicons.min.css</code>, <code>/wp-includes/css/admin-bar.min.css</code>, etc.)</li>
                                    <li>CSS files from <code>/wp-content/uploads/elementor/</code> (if Elementor builder plugin is used) and <code>/wp-content/uploads/oxygen/</code> (if Oxygen builder plugin is used)</li>
                                    <li>Specific CSS files from WooCommerce (e.g. the ones located in <code>/wp-content/plugins/woocommerce/assets/css/</code>) if the plugin is used, etc.</li>
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
                    <strong><?php _e('NOTE', 'wp-asset-clean-up'); ?>:</strong> <?php echo __('Concatenating assets is no longer a recommended practice in HTTP/2', 'wp-asset-clean-up'); ?>. &nbsp; <a id="wpacu-http2-info-css-target" href="#wpacu-http2-info-css"><?php _e('Read more', 'wp-asset-clean-up'); ?></a> &nbsp;/&nbsp; <a class="wpacu_verify_http2_protocol" target="_blank" href="https://tools.keycdn.com/http2-test"><strong><?php _e('Verify if the website is delivered through the HTTP/2 network protocol', 'wp-asset-clean-up'); ?></strong></a>
                </div>
                <div class="wpacu-combine-notice-http-2-detected wpacu_hide" style="line-height: 22px; background: #f8f8f8; border-left: 4px solid #008f9c; padding: 10px; margin: 0 0 15px;">
                    <span class="wpacu_http2_protocol_is_supported" style="color: green; font-weight: 400;"><span class="dashicons dashicons-yes-alt"></span> Your website `<span style="font-weight: 500;"><?php echo get_site_url(); ?></span>` is delivered through the HTTP/2 network protocol, thus, the website will be as fast without using this feature which might require maintenance once in a while.</span> <a class="wpacu-http2-info-css-target" href="#wpacu-http2-info-css"><?php _e('Read more', 'wp-asset-clean-up'); ?></a>
                </div>
            </td>
        </tr>

        <tr valign="top">
            <th scope="row" class="setting_title">
                <label for="wpacu_combine_loaded_css_enable"><?php _e('Combine loaded CSS (Stylesheets) into fewer files', 'wp-asset-clean-up'); ?></label>
                <p class="wpacu_subtitle"><small><em><?php _e('Helps reducing the number of HTTP Requests even further', 'wp-asset-clean-up'); ?></em></small></p>
            </th>
            <td>
                <label class="wpacu_switch <?php if (! empty($data['is_optimize_css_enabled_by_other_party'])) { echo 'wpacu_disabled'; } ?>">
                    <input id="wpacu_combine_loaded_css_enable"
                           data-target-opacity="wpacu_combine_loaded_css_info_area"
                           type="checkbox"
					    <?php
					    echo (in_array($data['combine_loaded_css'], array('for_admin', 'for_all', 1)) ? 'checked="checked"' : '');
					    ?>
                           name="<?php echo WPACU_PLUGIN_ID . '_settings'; ?>[combine_loaded_css]"
                           value="1" /> <span class="wpacu_slider wpacu_round"></span> </label>

                &nbsp;<small>* if <code style="font-size: inherit;"><?php echo '/'.str_replace(ABSPATH, '', WP_CONTENT_DIR) . \WpAssetCleanUp\OptimiseAssets\OptimizeCommon::getRelPathPluginCacheDir(); ?></code> directory is not writable for some reason, this feature will not work; requires the DOMDocument XML DOM Parser to be enabled in PHP (which it is by default) for maximum performance</small>
                &nbsp;
			    <?php
			    if (! empty($data['is_optimize_css_enabled_by_other_party'])) {
				    ?>
                    <div style="border-left: 4px solid green; background: #f2faf2; padding: 10px; margin-top: 10px;">
                        <ul style="margin: 0;">
                            <li>This option is locked as optimize/minify stylesheets (CSS) is already enabled in the following plugins: <strong><?php echo implode(', ', $data['is_optimize_css_enabled_by_other_party']); ?></strong></li>
                            <li><?php echo WPACU_PLUGIN_TITLE; ?> works together with the mentioned plugin(s). Eliminate the bloat first via <a href="<?php echo admin_url('admin.php?page=wpassetcleanup_assets_manager'); ?>">CSS & JAVASCRIPT LOAD MANAGER</a>, then concatenate (if necessary) the remaining CSS with any plugin you prefer.</li>
                        </ul>
                    </div>
				    <?php
			    }
			    ?>

                <div id="wpacu_combine_loaded_css_info_area" <?php if (empty($data['is_optimize_css_enabled_by_other_party']) && in_array($data['combine_loaded_css'], array('for_admin', 'for_all', 1))) { ?> style="opacity: 1;" <?php } else { ?>style="opacity: 0.4;"<?php } ?>>
                    <div style="margin-top: 8px; padding: 12px; background: #f2faf2; border-radius: 10px;">
                        <ul style="margin: 0;">
                            <li style="float: left; margin-right: 30px; margin-bottom: 0; line-height: 32px;" class="wpacu-fancy-radio">
                                <label for="combine_loaded_css_for_guests_radio">
                                    <input id="combine_loaded_css_for_guests_radio"
                                           style="margin: -1px 0 0;"
                                        <?php echo (in_array($data['combine_loaded_css_for'], array('guests', '')) ? 'checked="checked"' : ''); ?>
                                           type="radio"
                                           name="<?php echo WPACU_PLUGIN_ID . '_settings'; ?>[combine_loaded_css_for]"
                                           value="guests" />
                                    &nbsp;<?php _e('Apply it only for guest visitors', 'wp-asset-clean-up'); ?> (<?php echo __('default', 'wp-asset-clean-up'); ?>)
                                </label>
                            </li>
                            <li style="float: left; margin-bottom: 0; line-height: 32px;" class="wpacu-fancy-radio">
                                <label for="combine_loaded_css_for_all_radio">
                                    <input id="combine_loaded_css_for_all_radio"
                                           style="margin: -1px 0 0;"
                                        <?php echo (($data['combine_loaded_css_for'] === 'all') ? 'checked="checked"' : ''); ?>
                                           type="radio"
                                           name="<?php echo WPACU_PLUGIN_ID . '_settings'; ?>[combine_loaded_css_for]"
                                           value="all" />
                                    &nbsp;<?php _e('Apply it for all visitors (not recommended)', 'wp-asset-clean-up'); ?> * <small>to avoid using extra disk space</small>
                                </label>
                            </li>
                        </ul>
                        <div style="clear: both;"></div>
                    </div>

                    <p style="margin-top: 10px;"><strong>Note:</strong> When a stylesheet is added to a combined group of files, any other inline content (e.g. added via <code style="font-size: inherit;">wp_add_inline_style()</code>) associated with it, will also be added to the combined files. This reduces the number of DOM elements as well makes sure the CSS code will load in the right (set) order.</p>

                    <hr />

                    <div id="wpacu_combine_loaded_css_exceptions_area">
                        <div style="margin: 8px 0 6px;"><?php _e('Do not combine the CSS files matching the patterns below', 'wp-asset-clean-up'); ?> (<?php _e('one per line', 'wp-asset-clean-up'); ?>):</div>
                        <label for="combine_loaded_css_exceptions">
                                    <textarea style="width: 100%;"
                                              rows="4"
                                              id="combine_loaded_css_exceptions"
                                              name="<?php echo WPACU_PLUGIN_ID . '_settings'; ?>[combine_loaded_css_exceptions]"><?php echo $data['combine_loaded_css_exceptions']; ?></textarea>
                        </label>

                        <p>Pattern Examples (you don't have to add the full URL, as it's recommended to use relative paths):</p>
                        <code>/wp-includes/css/dashicons.min.css<br />/wp-includes/css/admin-bar.min.css<br />/wp-content/plugins/plugin-title/css/(.*?).css</code>
                    </div>

                    <p>This scans the remaining CSS files (left after cleaning up the unnecessary ones) from the <code>&lt;head&gt;</code> and <code>&lt;body&gt;</code> locations and combines them into ~2 files (one in each location). To be 100% sure everything works fine after activation, consider enabling this feature only for logged-in administrator, so only you can see the updated page. If all looks good, you can later uncheck the option to apply the feature to everyone else.</p>

                    <hr />
                    <p style="margin: 8px 0 4px;"><span style="color: #ffc107;" class="dashicons dashicons-lightbulb"></span> This feature will not work <strong>IF</strong>:</p>
                    <ul style="margin-top: 0; margin-left: 35px; list-style: disc;">
                        <li>"Test Mode" is enabled, this feature will not take effect for the guest users and it will apply the changes only for you.</li>
                        <li>The URL has query strings (e.g. an URL such as //www.yourdomain.com/product/title-here/?param=1&amp;param_two=value_here)</li>
                    </ul>
                </div>
                <hr />
            </td>
        </tr>

        <tr valign="top">
            <th scope="row" class="setting_title">
                <label for="wpacu_inline_css_files_enable"><?php _e('Inline CSS Files', 'wp-asset-clean-up'); ?></label>
                <p class="wpacu_subtitle"><small><em><?php _e('This will work for local (same domain) files. External requests tags will not be altered (e.g. stackpath.bootstrapcdn.com, ajax.googleapis.com etc.).', 'wp-asset-clean-up'); ?></em></small></p>
            </th>
            <td>
                <label class="wpacu_switch">
                    <input id="wpacu_inline_css_files_enable"
                           data-target-opacity="wpacu_inline_css_files_info_area"
                           type="checkbox"
                           <?php
                           echo (($data['inline_css_files'] == 1) ? 'checked="checked"' : '');
                           ?>
                           name="<?php echo WPACU_PLUGIN_ID . '_settings'; ?>[inline_css_files]"
                           value="1" /> <span class="wpacu_slider wpacu_round"></span> </label>

	            &nbsp;<?php _e('This is usually good for small stylesheet files to save the overhead of fetching them and thus reduce the number of HTTP requests', 'wp-asset-clean-up'); ?>. You can choose automatic inlining for CSS files smaller than a specific size (in KB) or manually place the relative paths to the files (e.g. in case there is an exception for a larger file you wish to inline or just don't want to use the automatic inlining).

                <?php
                $inlineCssFiles = ($data['inline_css_files'] == 1) ? 'opacity: 1;' : 'opacity: 0.4;';
                ?>
                <div id="wpacu_inline_css_files_info_area" style="<?php echo $inlineCssFiles; ?>">
                    <p style="margin-top: 8px; padding: 10px; background: #f2faf2;">
                        <label for="wpacu_inline_css_files_below_size_checkbox">
                            <input id="wpacu_inline_css_files_below_size_checkbox"
				                <?php echo ($data['inline_css_files_below_size'] == 1 ? 'checked="checked"' : ''); ?>
                                   type="checkbox"
                                   name="<?php echo WPACU_PLUGIN_ID . '_settings'; ?>[inline_css_files_below_size]"
                                   value="1" />
			                <?php echo sprintf(__('Inline Stylesheet (.css) Files Smaller Than %s KB', 'wp-asset-clean-up'), '<input type="number" min="1" style="width: 60px;" name="'.WPACU_PLUGIN_ID.'_settings[inline_css_files_below_size_input]" value="'.$data['inline_css_files_below_size_input'].'" />'); ?>
                        </label>
                    </p>

                    <div id="wpacu_inline_css_files_list_area">
                        <div style="margin: 12px 0 6px;"><?php _e('Alternatively or in addition to automatic inlining, you can place the relative path(s) or part of them to the files you wish to inline below:', 'wp-asset-clean-up'); ?> (<strong><?php _e('one per line', 'wp-asset-clean-up'); ?></strong>):</div>
                        <p style="margin-top: 8px;"><span class="dashicons dashicons-warning" style="color: #ffc107;"></span> <strong>Note:</strong> Please input the sources to the original CSS files (one per line) like in the examples below, not to the cached/optimized ones (which are usually located in <em><?php echo str_replace(site_url(), '', WP_CONTENT_URL) . OptimizeCommon::getRelPathPluginCacheDir(); ?></em>).</p>
                        <label for="wpacu_inline_css_files_list">
                                    <textarea style="width: 100%;"
                                              rows="4"
                                              id="wpacu_inline_css_files_list"
                                              name="<?php echo WPACU_PLUGIN_ID . '_settings'; ?>[inline_css_files_list]"><?php echo $data['inline_css_files_list']; ?></textarea>
                        </label>
                        <p style="margin-bottom: 6px;"><strong>Examples</strong> (you don't have to add the full URL, as it's recommended to use relative paths, especially if you use dev/staging environments or change the domain name of your website):</p>
                        <code>/wp-content/plugins/plugin-title/styles/small-file.css<br />/wp-content/themes/my-wp-theme-dir/css/small.css</code>
                    </div>
                </div>
                <hr />
            </td>
        </tr>

        <!-- [wpacu_lite] -->
        <!-- Pro Feature -->
        <tr valign="top">
            <th scope="row" class="setting_title">
                <label style="cursor: auto;"><?php _e('Defer CSS Loaded in the <code>&lt;BODY&gt;</code> (Footer)', 'wp-asset-clean-up'); ?> <?php echo $availableForPro; ?></label>
            </th>
            <td>
                <div>
                    <ul style="margin: 0;">
                        <li style="margin-bottom: 13px;" class="wpacu-fancy-radio"><label for="wpacu_defer_css_loaded_body_moved"><input style="margin: 0;" disabled="disabled" id="wpacu_defer_css_loaded_body_moved" type="radio" name="<?php echo WPACU_PLUGIN_ID . '_settings'; ?>[defer_css_loaded_body]" checked="checked" /> &nbsp;Yes, for any stylesheet LINK tags moved from HEAD to BODY via Asset CleanUp Pro * <em>default</em></label></li>
                        <li style="margin-bottom: 13px;" class="wpacu-fancy-radio"><label for="wpacu_defer_css_loaded_body_all"><input style="margin: 0;" disabled="disabled" id="wpacu_defer_css_loaded_body_all" type="radio" name="<?php echo WPACU_PLUGIN_ID . '_settings'; ?>[defer_css_loaded_body]" /> &nbsp;Yes, for all the stylesheet LINK tags that are moved or already loaded in the BODY</label></li>
                        <li class="wpacu-fancy-radio"><label for="wpacu_defer_css_loaded_body_no"><input disabled="disabled" id="wpacu_defer_css_loaded_body_no" type="radio" name="<?php echo WPACU_PLUGIN_ID . '_settings'; ?>[defer_css_loaded_body]" /> &nbsp;No, leave the stylesheet LINK tags from the BODY as they are without any alteration</label></li>
                    </ul>
                </div>
                <div>
                    <p><strong>Note:</strong> By default, any CSS you move from <code>&lt;HEAD&gt;</code> to <code>&lt;BODY&gt;</code> when changing its position (when managing assets via the "CSS & JAVASCRIPT LOAD MANAGER") is deferred. In most cases, CSS loaded in the BODY is not meant to be render-blocking and should start loading later after the HTML document has been completely loaded and parsed. For instance, it could be the styling for a modal box that is showing up later after the page loads or a content slider that is at the bottom of a page and doesn't need to be loaded very soon as it's not needed above the fold.</p>
                    <p class="wpacu-warning" style="font-size: inherit;">This helps improve "Eliminate render-blocking resources" score in PageSpeed Insights and the browser is rendering the first content of the page sooner (as the CSS is not render-blocking) offering a better user experience.</p>
                </div>
            </td>
        </tr>
        <!-- [/wpacu_lite] -->

        <tr valign="top">
            <th scope="row" class="setting_title">
                <label for="wpacu_cache_dynamic_loaded_css_enable"><?php _e('Cache Dynamic Loaded CSS', 'wp-asset-clean-up'); ?></label>
                <p class="wpacu_subtitle"><small><em><?php _e('This option is enabled by default on new installs or after a settings reset', 'wp-asset-clean-up'); ?>.</em></small></p>
            </th>
            <td>
                <label class="wpacu_switch">
                    <input id="wpacu_cache_dynamic_loaded_css_enable"
                           data-target-opacity="wpacu_cache_dynamic_loaded_css_info_area"
                           type="checkbox"
					    <?php
					    echo (($data['cache_dynamic_loaded_css'] == 1) ? 'checked="checked"' : '');
					    ?>
                           name="<?php echo WPACU_PLUGIN_ID . '_settings'; ?>[cache_dynamic_loaded_css]"
                           value="1" /> <span class="wpacu_slider wpacu_round"></span> </label>

                &nbsp;<?php _e('Avoid loading the whole WP environment whenever a dynamic request is made such as <code>/?custom-css=value_here</code>, or <code>/wp-content/plugins/plugin-name-here/css/generate-style.php?ver=1</code>', 'wp-asset-clean-up'); ?>.
                <hr />
                <p>e.g. <code>&lt;link type="text/css" href="//yourwebsite.com/wp-content/plugins/plugin-name-here/css/generate-style.php?ver=<?php echo $wp_version; ?>" /&gt;</code></p>
                <?php
                $cacheDynamicLoadedCssAreaStyle = ($data['cache_dynamic_loaded_css'] == 1) ? 'opacity: 1;' : 'opacity: 0.4;';
                ?>
                <div id="wpacu_cache_dynamic_loaded_css_info_area" style="<?php echo $cacheDynamicLoadedCssAreaStyle; ?>">
                    <p>Some plugins and themes have options to create your own CSS/layout and save it within the Dashboard. Instead of creating static CSS files from the saved settings, the changes you made are retrieved from the database and the CSS content is created "on the fly", thus using more resources by loading the whole WP environment and make MySQL (or whatever database type if used) requests in order to print the CSS content. <?php echo WPACU_PLUGIN_TITLE; ?> detects such requests and caches the output for faster retrieval. This very important especially if your website has lots of visits (imagine WordPress loading several times only from one visitor) and you're on a shared environment with limited resources. This will also make the user experience better by decreasing the page rendering time.</p>
                </div>
            </td>
        </tr>
	</table>
</div>

<div id="wpacu-http2-info-css" class="wpacu-modal" style="padding-top: 100px;">
    <div class="wpacu-modal-content" style="max-width: 800px;">
        <span class="wpacu-close">&times;</span>
        <h2 style="margin-top: 5px;"><?php _e('Combining CSS files in HTTP/2 protocol', 'wp-asset-clean-up'); ?></h2>
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
