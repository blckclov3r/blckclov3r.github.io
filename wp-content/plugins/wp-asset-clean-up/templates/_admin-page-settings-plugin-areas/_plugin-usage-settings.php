<?php
/*
 * No direct access to this file
 */
if (! isset($data)) {
	exit;
}

$tabIdArea = 'wpacu-setting-plugin-usage-settings';
$styleTabContent = ($selectedTabArea === $tabIdArea) ? 'style="display: table-cell;"' : '';

$postTypesList = get_post_types(array('public' => true));

// Hide hardcoded irrelevant post types
foreach (\WpAssetCleanUp\MetaBoxes::$noMetaBoxesForPostTypes as $noMetaBoxesForPostType) {
    unset($postTypesList[$noMetaBoxesForPostType]);
}
?>
<div id="<?php echo $tabIdArea; ?>" class="wpacu-settings-tab-content" <?php echo $styleTabContent; ?>>
    <h2 class="wpacu-settings-area-title"><?php _e('General &amp; Files Management', 'wp-asset-clean-up'); ?></h2>
    <p><?php _e('Choose how the assets are retrieved and whether you would like to see them within the Dashboard / Front-end view', 'wp-asset-clean-up'); ?>; <?php _e('Decide how the management list of CSS &amp; JavaScript files will show up and get sorted, depending on your preferences.', 'wp-asset-clean-up'); ?></p>
    <table class="wpacu-form-table">
        <tr valign="top">
            <th scope="row">
                <label for="wpacu_dashboard"><?php _e('Manage in the Dashboard', 'wp-asset-clean-up'); ?></label>
            </th>
            <td>
                <label class="wpacu_switch">
                    <input id="wpacu_dashboard"
                           data-target-opacity="wpacu_manage_dashboard_assets_list"
                           type="checkbox"
						<?php echo (($data['dashboard_show'] == 1) ? 'checked="checked"' : ''); ?>
                           name="<?php echo WPACU_PLUGIN_ID . '_settings'; ?>[dashboard_show]"
                           value="1" /> <span class="wpacu_slider wpacu_round"></span> </label>
                &nbsp;
                <?php _e('This will show the list of assets in a meta box on edit the post (any type) / page within the Dashboard', 'wp-asset-clean-up'); ?>

                <div id="wpacu_manage_dashboard_assets_list">
                    <p><?php _e('The assets would be retrieved via AJAX call(s) that will fetch the post/page URL and extract all the styles &amp; scripts that are enqueued.', 'wp-asset-clean-up'); ?></p>
                    <p><?php _e('Note that sometimes the assets list is not loading within the Dashboard. That could be because "mod_security" Apache module is enabled or some security plugins are blocking the AJAX request. If this option doesn\'t work, consider managing the list in the front-end view.', 'wp-asset-clean-up'); ?></p>

                    <div id="wpacu-settings-assets-retrieval-mode" <?php if (! ($data['dashboard_show'] == 1)) { echo 'style="display: none;"'; } ?>>
                        <ul id="wpacu-dom-get-type-selections">
                        <li>
                            <label><?php _e('Select a retrieval way', 'wp-asset-clean-up'); ?>:</label>
                        </li>
                        <li>
                            <label>
                                <input class="wpacu-dom-get-type-selection"
                                       data-target="wpacu-dom-get-type-direct-info"
								       <?php if ($data['dom_get_type'] === 'direct') { ?>checked="checked"<?php } ?>
                                       type="radio" name="<?php echo WPACU_PLUGIN_ID . '_settings'; ?>[dom_get_type]"
                                       value="direct" /> <?php _e('Direct', 'wp-asset-clean-up'); ?>
                            </label>
                        </li>
                        <li>
                            <label>
                                <input class="wpacu-dom-get-type-selection"
                                       data-target="wpacu-dom-get-type-wp-remote-post-info"
								       <?php if ($data['dom_get_type'] === 'wp_remote_post') { ?>checked="checked"<?php } ?>
                                       type="radio" name="<?php echo WPACU_PLUGIN_ID . '_settings'; ?>[dom_get_type]"
                                       value="wp_remote_post" /> WP Remote Post
                            </label>
                        </li>
                    </ul>

                        <div class="wpacu-clearfix" style="height: 0;"></div>

                        <ul id="wpacu-dom-get-type-infos">
                            <li <?php if ($data['dom_get_type'] !== 'direct') { ?>style="display: none;"<?php } ?>
                                class="wpacu-dom-get-type-info"
                                id="wpacu-dom-get-type-direct-info">
                                <strong><?php _e('Direct', 'wp-asset-clean-up'); ?></strong> - <?php _e('This one makes an AJAX call directly on the URL for which the assets are retrieved, then an extra WordPress AJAX call to process the list. Sometimes, due to some external factors (e.g. mod_security module from Apache, security plugin or the fact that non-http is forced for the front-end view and the AJAX request will be blocked), this might not work and another choice method might work better. This used to be the only option available, prior to version 1.2.4.4 and is set as default.', 'wp-asset-clean-up'); ?>
                            </li>
                            <li <?php if ($data['dom_get_type'] !== 'wp_remote_post') { ?>style="display: none;"<?php } ?>
                                class="wpacu-dom-get-type-info"
                                id="wpacu-dom-get-type-wp-remote-post-info">
                                <strong>WP Remote Post</strong> - <?php _e('It makes a WordPress AJAX call and gets the HTML source code through wp_remote_post(). This one is less likely to be blocked as it is made on the same protocol (no HTTP request from HTTPS). However, in some cases (e.g. a different load balancer configuration), this might not work when the call to fetch a domain\'s URL (your website) is actually made from the same domain.', 'wp-asset-clean-up'); ?>
                            </li>
                        </ul>

                        <hr /><div class="wpacu-clearfix" style="height: 0;"></div>

                        <p style="margin-top: 8px;"><?php _e('When you are in the Dashboard and edit a post, page, custom post type, category or custom taxonomy and rarely manage loaded CSS/JS from the "Asset CleanUp: CSS & JavaScript Manager", you can choose to fetch the list when you click on a button. This will help declutter the edit page on load and also save resources as AJAX calls to the front-end won\'t be made to retrieve the assets\' list.', 'wp-asset-clean-up'); ?></p>
                        <ul style="margin-bottom: 0;">
                            <li>
                                <label for="assets_list_show_status_default">
                                    <input id="assets_list_show_status_default"
				                           <?php if (! $data['assets_list_show_status'] || $data['assets_list_show_status'] === 'default') { ?>checked="checked"<?php } ?>
                                           type="radio"
                                           name="<?php echo WPACU_PLUGIN_ID . '_settings'; ?>[assets_list_show_status]"
                                           value="default" /> <?php _e('Fetch the assets automatically and show the list', 'wp-asset-clean-up'); ?> (<?php _e('Default', 'wp-asset-clean-up'); ?>)
                                </label>
                            </li>
                            <li>
                                <label for="assets_list_show_status_fetch_on_click">
                                    <input id="assets_list_show_status_fetch_on_click"
				                           <?php if ($data['assets_list_show_status'] === 'fetch_on_click') { ?>checked="checked"<?php } ?>
                                           type="radio"
                                           name="<?php echo WPACU_PLUGIN_ID . '_settings'; ?>[assets_list_show_status]"
                                           value="fetch_on_click" /> <?php _e('Fetch the assets on a button click', 'wp-asset-clean-up'); ?>
                                </label>
                            </li>
                        </ul><div class="wpacu-clearfix" style="height: 0; clear: both;"></div>

                        <hr />
                    </div>
                </div>

                <div id="wpacu-settings-hide-meta-boxes">
                    <p><?php _e('If you wish to hide the meta boxes completely for any reason (e.g. you rarely manage the assets and you want to reduce cluttering in the edit post/page/taxonomy area, especially if you do lots of edits), you can do so using the options below (<em>don\'t forget to uncheck them whenever you wish to manage the CSS/JS assets again</em>)', 'wp-asset-clean-up'); ?>:</p>
                    <ul>
                        <li><label for="wpacu-hide-assets-meta-box-checkbox"><input <?php echo (($data['hide_assets_meta_box'] == 1) ? 'checked="checked"' : ''); ?> id="wpacu-hide-assets-meta-box-checkbox" type="checkbox" name="<?php echo WPACU_PLUGIN_ID . '_settings'; ?>[hide_assets_meta_box]" value="1" /> Hide "<?php echo WPACU_PLUGIN_TITLE; ?>: CSS &amp; JavaScript Manager" meta box</label></li>
                        <li><label for="wpacu-hide-options-meta-box-checkbox"><input <?php echo (($data['hide_options_meta_box'] == 1) ? 'checked="checked"' : ''); ?> id="wpacu-hide-options-meta-box-checkbox" type="checkbox" name="<?php echo WPACU_PLUGIN_ID . '_settings'; ?>[hide_options_meta_box]" value="1" /> Hide "<?php echo WPACU_PLUGIN_TITLE; ?>: Options" meta box</label></li>
                    </ul>
                    <hr />

                    <label for="wpacu-hide-meta-boxes-for-post-types">Hide all meta boxes for the following public post types (multiple selection drop-down):</label><br />
                </div>

                <select style="margin-top: 4px; min-width: 340px;" id="wpacu-hide-meta-boxes-for-post-types"
                        data-placeholder="Choose Post Type(s)..."
                        class="wpacu-chosen-select"
                        multiple="multiple"
                        name="<?php echo WPACU_PLUGIN_ID . '_settings'; ?>[hide_meta_boxes_for_post_types][]">
                    <?php foreach ($postTypesList as $postTypeKey => $postTypeValue) { ?>
                        <option <?php if (in_array($postTypeKey, $data['hide_meta_boxes_for_post_types'])) { echo 'selected="selected"'; } ?>
                                value="<?php echo $postTypeKey; ?>"><?php echo $postTypeValue; ?></option>
                    <?php } ?>
                </select>
                <p id="wpacu-hide-meta-boxes-for-post-types-info" style="margin-top: 4px;"><small>Sometimes, you might have a post type marked as 'public', but it's not queryable or doesn't have a public URL of its own, making the assets list irrelevant. Or, you have finished optimising pages for a particular post type and you wish to have the assets list hidden. You can choose to hide the meta boxes for these particular post types.</small></p>
                <hr />
            </td>
        </tr>
        <tr valign="top">
            <th scope="row">
                <label for="wpacu_frontend"><?php _e('Manage in the Front-end', 'wp-asset-clean-up'); ?></label>
            </th>
            <td>
                <label class="wpacu_switch">
                    <input id="wpacu_frontend"
                           data-target-opacity="wpacu_frontend_manage_assets_list"
                           type="checkbox"
						<?php echo (($data['frontend_show'] == 1) ? 'checked="checked"' : ''); ?>
                           name="<?php echo WPACU_PLUGIN_ID . '_settings'; ?>[frontend_show]"
                           value="1" /> <span class="wpacu_slider wpacu_round"></span> </label>
                &nbsp;
                If you are logged in, this will make the list of assets show below the page that you view (either home page, a post or a page).

                <div id="wpacu_frontend_manage_assets_list">
                    <p style="margin-top: 10px;">The area will be shown through the <code>wp_footer</code> action so in case you do not see the asset list at the bottom of the page, make sure the theme is using <a href="https://codex.wordpress.org/Function_Reference/wp_footer"><code>wp_footer()</code></a> function before the <code>&lt;/body&gt;</code> tag. Any theme that follows the standards should have it. If not, you will have to add it to make sure other plugins and code from functions.php will work fine.</p>

                    <div id="wpacu-settings-frontend-exceptions" <?php if (! ($data['frontend_show'] == 1)) { echo 'style="display: none;"'; } ?>>
                        <div style="margin: 0 0 10px;"><label for="wpacu_frontend_show_exceptions"><span class="dashicons dashicons-info"></span> In some situations, you might want to avoid showing the CSS/JS list at the bottom of the pages (e.g. you're using a page builder such as Divi, you often load specific pages as an admin and you don't need to manage assets there or you do it rarely etc.). If that's the case, you can use the following textarea to prevent the list from showing up on pages where the <strong>URI contains</strong> the specified strings (<?php _e('one per line', 'wp-asset-clean-up'); ?>):</label></div>
                        <textarea id="wpacu_frontend_show_exceptions"
                                  name="<?php echo WPACU_PLUGIN_ID . '_settings'; ?>[frontend_show_exceptions]"
                                  rows="5"
                                  style="width: 100%;"><?php echo $data['frontend_show_exceptions']; ?></textarea>
                        <p><strong>Example:</strong> If the URI contains <strong>et_fb=1</strong> which triggers the front-end Divi page builder, then you can specify it in the list above (it's added by default) to prevent the asset list from showing below the page builder area.</p>
                    </div>
                </div>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row" class="setting_title">
                <label for="wpacu_assets_list_layout"><?php _e('Assets List Layout', 'wp-asset-clean-up'); ?></label>
                <p class="wpacu_subtitle"><small><em><?php _e('You can decide how would you like to view the list of the enqueued CSS &amp; JavaScript', 'wp-asset-clean-up'); ?></em></small></p>
            </th>
            <td>
                <label>
                    <?php echo \WpAssetCleanUp\Settings::generateAssetsListLayoutDropDown($data['assets_list_layout'], WPACU_PLUGIN_ID . '_settings[assets_list_layout]' ); ?>
				</label>

                <div id="wpacu-assets-list-by-location-selected" style="margin: 10px 0; <?php if ($data['assets_list_layout'] !== 'by-location') { ?> display: none; <?php } ?>">
                    <div style="margin-bottom: 6px;"><?php _e('When list is grouped by location, keep the assets from each of the plugins in the following state', 'wp-asset-clean-up'); ?>:</div>
                    <ul class="assets_list_layout_areas_status_choices">
                        <li>
                            <label for="assets_list_layout_plugin_area_status_expanded">
                                <input id="assets_list_layout_plugin_area_status_expanded"
				                       <?php if (! $data['assets_list_layout_plugin_area_status'] || $data['assets_list_layout_plugin_area_status'] === 'expanded') { ?>checked="checked"<?php } ?>
                                       type="radio"
                                       name="<?php echo WPACU_PLUGIN_ID . '_settings'; ?>[assets_list_layout_plugin_area_status]"
                                       value="expanded"> <?php _e('Expanded', 'wp-asset-clean-up'); ?> (<?php _e('Default', 'wp-asset-clean-up'); ?>)
                            </label>
                        </li>
                        <li>
                            <label for="assets_list_layout_plugin_area_status_contracted">
                                <input id="assets_list_layout_plugin_area_status_contracted"
				                       <?php if ($data['assets_list_layout_plugin_area_status'] === 'contracted') { ?>checked="checked"<?php } ?>
                                       type="radio"
                                       name="<?php echo WPACU_PLUGIN_ID . '_settings'; ?>[assets_list_layout_plugin_area_status]"
                                       value="contracted"> <?php _e('Contracted', 'wp-asset-clean-up'); ?>
                            </label>
                        </li>
                    </ul>
                    <div class="clear"></div>
                </div>

                <div class="wpacu-clearfix"></div>

                <p style="margin-top: 8px;"><?php _e('These are various ways in which the list of assets that you will manage will show up. Depending on your preference, you might want to see the list of styles &amp; scripts first, or all together sorted in alphabetical order etc.', 'wp-asset-clean-up'); ?> <?php _e('Options that are disabled are available in the Pro version.', 'wp-asset-clean-up'); ?></p>
            </td>
        </tr>

        <tr valign="top">
            <th scope="row">
                <label><?php _e('On Assets List Layout Load, keep the groups:', 'wp-asset-clean-up'); ?></label>
            </th>
            <td>
                <ul class="assets_list_layout_areas_status_choices">
                    <li>
                        <label for="assets_list_layout_areas_status_expanded">
                            <input id="assets_list_layout_areas_status_expanded"
							       <?php if (! $data['assets_list_layout_areas_status'] || $data['assets_list_layout_areas_status'] === 'expanded') { ?>checked="checked"<?php } ?>
                                   type="radio"
                                   name="<?php echo WPACU_PLUGIN_ID . '_settings'; ?>[assets_list_layout_areas_status]"
                                   value="expanded"> <?php _e('Expanded', 'wp-asset-clean-up'); ?> (<?php _e('Default', 'wp-asset-clean-up'); ?>)
                        </label>
                    </li>
                    <li>
                        <label for="assets_list_layout_areas_status_contracted">
                            <input id="assets_list_layout_areas_status_contracted"
							       <?php if ($data['assets_list_layout_areas_status'] === 'contracted') { ?>checked="checked"<?php } ?>
                                   type="radio"
                                   name="<?php echo WPACU_PLUGIN_ID . '_settings'; ?>[assets_list_layout_areas_status]"
                                   value="contracted"> <?php _e('Contracted', 'wp-asset-clean-up'); ?>
                        </label>
                    </li>
                </ul>
                <div class="wpacu-clearfix"></div>

                <p><?php _e('Sometimes, when you have plenty of elements in the edit page, you might want to contract the list of assets when you\'re viewing the page as it will save space. This can be a good practice, especially when you finished optimising the pages and you don\'t want to keep seeing the long list of files every time you edit a page.', 'wp-asset-clean-up'); ?></p>
            </td>
        </tr>

        <tr valign="top">
            <th scope="row">
                <label><?php _e('On Assets List Layout Load, keep "Inline code associated with this handle" area', 'wp-asset-clean-up'); ?>:</label>
            </th>
            <td>
                <ul class="assets_list_inline_code_status_choices">
                    <li>
                        <label for="assets_list_inline_code_status_contracted">
                            <input id="assets_list_inline_code_status_contracted"
			                       <?php if ($data['assets_list_inline_code_status'] === 'contracted') { ?>checked="checked"<?php } ?>
                                   type="radio"
                                   name="<?php echo WPACU_PLUGIN_ID . '_settings'; ?>[assets_list_inline_code_status]"
                                   value="contracted"> <?php _e('Contracted', 'wp-asset-clean-up'); ?> (<?php _e('Default', 'wp-asset-clean-up'); ?>)
                        </label>
                    </li>
                    <li>
                        <label for="assets_list_inline_code_status_expanded">
                            <input id="assets_list_inline_code_status_expanded"
							       <?php if (! $data['assets_list_inline_code_status'] || $data['assets_list_inline_code_status'] === 'expanded') { ?>checked="checked"<?php } ?>
                                   type="radio"
                                   name="<?php echo WPACU_PLUGIN_ID . '_settings'; ?>[assets_list_inline_code_status]"
                                   value="expanded"> <?php _e('Expanded', 'wp-asset-clean-up'); ?>
                        </label>
                    </li>
                </ul>
                <div class="wpacu-clearfix"></div>

                <p><?php echo sprintf(
                        __('Some assets (CSS &amp; JavaScript) have inline code associate with them and often, they are quite large, making the asset row bigger and requiring you to scroll more until you reach a specific area. By setting it to "%s", it will hide all the inline code by default and you can view it by clicking on the toggle link inside the asset row.', 'wp-asset-clean-up'),
                        __('Contracted', 'wp-asset-clean-up')
                    ); ?></p>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row" class="setting_title">
                <label><?php _e('Input Fields Style', 'wp-asset-clean-up'); ?>:</label>
                <p class="wpacu_subtitle"><small><em><?php _e('How would you like to view the checkboxes / selectors?', 'wp-asset-clean-up'); ?></em></small></p>
                <p class="wpacu_read_more"><a href="https://assetcleanup.com/docs/?p=95" target="_blank"><?php _e('Read More', 'wp-asset-clean-up'); ?></a></p>
            </th>
            <td>
                <ul class="input_style_choices">
                    <li>
                        <label for="input_style_enhanced">
                            <input id="input_style_enhanced"
							       <?php if (! $data['input_style'] || $data['input_style'] === 'enhanced') { ?>checked="checked"<?php } ?>
                                   type="radio"
                                   name="<?php echo WPACU_PLUGIN_ID . '_settings'; ?>[input_style]"
                                   value="enhanced"> <?php _e('Enhanced iPhone Style (Default)', 'wp-asset-clean-up'); ?>
                        </label>
                    </li>
                    <li>
                        <label for="input_style_standard">
                            <input id="input_style_standard"
							       <?php if ($data['input_style'] === 'standard') { ?>checked="checked"<?php } ?>
                                   type="radio"
                                   name="<?php echo WPACU_PLUGIN_ID . '_settings'; ?>[input_style]"
                                   value="standard"> <?php _e('Standard', 'wp-asset-clean-up'); ?>
                        </label>
                    </li>
                </ul>
                <div class="wpacu-clearfix"></div>

                <p><?php _e('In case you prefer standard HTML checkboxes instead of the enhanced CSS3 iPhone style ones (on &amp; off) or you need a simple HTML layout in case you\'re using a screen reader software (e.g. for people with disabilities) which requires standard/clean HTML code, then you can choose "Standard" as an option.', 'wp-asset-clean-up'); ?> <span style="color: #004567;" class="dashicons dashicons-info"></span> <a href="https://assetcleanup.com/docs/?p=95" target="_blank">Read more</a></p>
            </td>
        </tr>

        <tr valign="top">
            <th scope="row" class="setting_title">
                <label><?php echo sprintf(__('Hide %s menus', 'wp-asset-clean-up'), '"'.WPACU_PLUGIN_TITLE.'"'); ?></label>
                <p class="wpacu_subtitle"><small><em><?php _e('Are you rarely using the plugin and want to make some space in the admin menus?', 'wp-asset-clean-up'); ?></em></small></p>
            </th>
            <td>
                <ul style="padding: 0;">
                    <li style="margin-bottom: 14px;">
                        <label for="wpacu_hide_from_admin_bar">
                            <input id="wpacu_hide_from_admin_bar"
                                   type="checkbox"
							    <?php echo (($data['hide_from_admin_bar'] == 1) ? 'checked="checked"' : ''); ?>
                                   name="<?php echo WPACU_PLUGIN_ID . '_settings'; ?>[hide_from_admin_bar]"
                                   value="1" /> <span class="wpacu_slider wpacu_round"></span>
                            <span>Hide it from the top admin bar</span> / This could be useful if your top admin bar is filled with too many items and you rarely use the plugin.</label> <span style="color: #004567;" class="dashicons dashicons-info"></span> <a href="https://assetcleanup.com/docs/?p=187" target="_blank">Read more</a>
                    </li>
                    <li>
                        <label for="wpacu_hide_from_side_bar">
                            <input id="wpacu_hide_from_side_bar"
                                   type="checkbox"
							    <?php echo (($data['hide_from_side_bar'] == 1) ? 'checked="checked"' : ''); ?>
                                   name="<?php echo WPACU_PLUGIN_ID . '_settings'; ?>[hide_from_side_bar]"
                                   value="1" /> <span class="wpacu_slider wpacu_round"></span>
                            <span>Hide it from the left sidebar within the Dashboard</span> / The only access will be from <em>"Settings" -&gt; "<?php echo WPACU_PLUGIN_TITLE; ?>"</em>.</label> <span style="color: #004567;" class="dashicons dashicons-info"></span> <a href="https://assetcleanup.com/docs/?p=584" target="_blank">Read more</a>
                    </li>
                </ul>
            </td>
        </tr>

        <tr valign="top">
            <th scope="row">
                <label for="wpacu_hide_core_files"><?php _e('Hide WordPress Core Files From The Assets List?', 'wp-asset-clean-up'); ?></label>
            </th>
            <td>
                <label class="wpacu_switch">
                    <input id="wpacu_hide_core_files"
                           type="checkbox"
						<?php echo (($data['hide_core_files'] == 1) ? 'checked="checked"' : ''); ?>
                           name="<?php echo WPACU_PLUGIN_ID . '_settings'; ?>[hide_core_files]"
                           value="1" /> <span class="wpacu_slider wpacu_round"></span> </label>
                &nbsp;
                <?php echo sprintf(__('WordPress Core Files have handles such as %s', 'wp-asset-clean-up'), "'jquery', 'wp-embed', 'comment-reply', 'dashicons'"); ?> etc.
                <p style="margin-top: 10px;"><?php _e('They should only be unloaded by experienced developers when they are convinced that are not needed in particular situations. It\'s better to leave them loaded if you have any doubts whether you need them or not. By hiding them in the assets management list, you will see a smaller assets list (easier to manage) and you will avoid updating by mistake any option (unload, async, defer) related to any core file.', 'wp-asset-clean-up'); ?></p>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row">
                <label for="wpacu_allow_usage_tracking"><?php _e('Allow Usage Tracking', 'wp-asset-clean-up'); ?></label>
            </th>
            <td>
                <label class="wpacu_switch">
                    <input id="wpacu_allow_usage_tracking"
                           type="checkbox"
					    <?php echo (($data['allow_usage_tracking'] == 1) ? 'checked="checked"' : ''); ?>
                           name="<?php echo WPACU_PLUGIN_ID . '_settings'; ?>[allow_usage_tracking]"
                           value="1" /> <span class="wpacu_slider wpacu_round"></span> </label>
                &nbsp;
                Allow <?php echo WPACU_PLUGIN_TITLE; ?> to anonymously track plugin usage in order to help us make the plugin better? No sensitive or personal data is collected. <span style="color: #004567;" class="dashicons dashicons-info"></span> <a id="wpacu-show-tracked-data-list-modal-target" href="#wpacu-show-tracked-data-list-modal">What kind of data will be sent for the tracking?</a>
            </td>
        </tr>

        <tr valign="top">
            <th scope="row">
                <label for="wpacu_fetch_cached_files_details_from"><?php _e('Fetch assets\' caching information from:', 'wp-asset-clean-up'); ?></label>
            </th>
            <td>
                <select id="wpacu_fetch_cached_files_details_from"
                        name="<?php echo WPACU_PLUGIN_ID . '_settings'; ?>[fetch_cached_files_details_from]">
                    <option <?php if ($data['fetch_cached_files_details_from'] === 'disk') { ?>selected="selected"<?php } ?> value="disk">Disk (default)</option>
                    <option <?php if ($data['fetch_cached_files_details_from'] === 'db') { ?>selected="selected"<?php } ?> value="db">Database</option>
                    <option <?php if ($data['fetch_cached_files_details_from'] === 'db_disk') { ?>selected="selected"<?php } ?> value="db_disk">Database &amp; Disk (50% / 50%)</option>
                </select> &nbsp; <span style="color: #004567; vertical-align: middle;" class="dashicons dashicons-info"></span> <a style="vertical-align: middle;" id="wpacu-fetch-assets-details-location-modal-target" href="#wpacu-fetch-assets-details-location-modal">Read more</a>
            </td>
        </tr>

        <tr valign="top">
            <th scope="row">
                <label for="wpacu_clear_cached_files_after"><?php _e('Clear previously cached CSS/JS files older than (x) days', 'wp-asset-clean-up'); ?></label>
            </th>
            <td>
                    <input id="wpacu_clear_cached_files_after"
                           type="number"
                           min="0"
                           style="width: 60px; margin-bottom: 10px;"
                           name="<?php echo WPACU_PLUGIN_ID . '_settings'; ?>[clear_cached_files_after]"
                           value="<?php echo $data['clear_cached_files_after']; ?>" /> days <small>(setting the value to 0 will result in all the previously cached CSS/JS files to be deleted).</small>
                <br />This is relevant in case there are alterations made to the content of the CSS/JS files via minification, combination or any other settings that would require an update to the content of a file (e.g. apply "font-display" to @font-face in stylesheets). When the caching is cleared, the previously cached CSS/JS files stored in <code><?php echo \WpAssetCleanUp\OptimiseAssets\OptimizeCommon::getRelPathPluginCacheDir(); ?></code> that are older than (X) days will be deleted as they are outdated and likely not referenced anymore in any source code (e.g. old cached pages, Google Search cached version etc.). <span style="color: #004567;" class="dashicons dashicons-info"></span> <a href="https://assetcleanup.com/docs/?p=237" target="_blank">Read more</a>
            </td>
        </tr>
        <?php
        ?>

        <tr valign="top">
            <th scope="row">
                <label for="wpacu_frontend"><?php _e('Do not load the plugin on certain pages', 'wp-asset-clean-up'); ?></label>
            </th>
            <td>
                &nbsp;If you wish to prevent Asset CleanUp Pro from triggering on certain pages (except the Dashboard) or group of pages for any reason (e.g. incompatibility with another plugin), you can specify some URI patterns in the following textarea (one per line), just like the examples shown below:
                <div style="margin: 8px 0 5px;">
                <textarea id="wpacu_do_not_load_plugin_patterns"
                           name="<?php echo WPACU_PLUGIN_ID . '_settings'; ?>[do_not_load_plugin_patterns]"
                           rows="4"
                           style="width: 100%;"><?php echo $data['do_not_load_plugin_patterns']; ?></textarea>
                </div>
                <div>
                    <p>You can either use specific strings or patterns (the # delimiter will be automatically applied to the <code>preg_match()</code> PHP function that would check if the requested URI is matched). Please do not include the domain name. Here are a few examples:</p>
                    <ul>
                        <li><code>/checkout/</code> - if it contains the string</li>
                        <li><code>/product/(.*?)/</code> - any product page (most likely from WooCommerce)</li>
                    </ul>
                </div>
            </td>
        </tr>
    </table>
</div>

<style type="text/css">
    #wpacu-show-tracked-data-list-modal {
        margin: 14px 0 0;
    }

    #wpacu-show-tracked-data-list-modal .table-striped {
        border: none;
        border-spacing: 0;
    }

    #wpacu-show-tracked-data-list-modal .table-striped tbody tr:nth-of-type(even) {
        background-color: rgba(0, 143, 156, 0.05);
    }

    #wpacu-show-tracked-data-list-modal .table-striped tbody tr td:first-child {
        font-weight: bold;
    }
</style>

<div id="wpacu-show-tracked-data-list-modal" class="wpacu-modal" style="padding-top: 100px;">
    <div class="wpacu-modal-content" style="max-width: 800px;">
        <span class="wpacu-close">&times;</span>
        <p>The following information will be sent to us and it would be helpful to make the plugin better.</p>
        <p>e.g. see which themes and plugins are used the most and make the plugin as compatible as possible with them, see the most used plugin settings, determine the most used languages after English which is helpful to prioritise translations etc.</p>
        <?php
        $pluginTrackingClass = new \WpAssetCleanUp\PluginTracking();
        $pluginTrackingClass->setup_data();
        $pluginTrackingClass::showSentInfoDataTable($pluginTrackingClass->data);
        ?>
    </div>
</div>

<div id="wpacu-fetch-assets-details-location-modal" class="wpacu-modal" style="padding-top: 100px;">
    <div class="wpacu-modal-content" style="max-width: 800px;">
        <span class="wpacu-close">&times;</span>
        <p>Any optimized files (e.g. via minification, combination) have their caching information (such as original location, new optimized location, version) stored in the disk by default (in most cases, it's the most effective option) to avoid extra connections to the database for a few files' information.</p>
        <p>However, if you already have a light database and lots of Apache/NGINX resources already in use by your theme/other plugins, you can balance the usage of <?php echo WPACU_PLUGIN_TITLE; ?>'s resources and go for the "Database &amp; Disk (50% / 50%)" option (Example: If, for instance, on a page, there are 19 CSS/JS files which are optimized &amp; cached, 10 would have their caching information fetched from the database while 9 from the disk).</p>

        <p>The contents are stored like in the following example:</p>
        <p><code>{"source_uri":"\/wp-content\/plugins\/plugin-title-here\/assets\/style.css","optimize_uri":"\/wp-content\/uploads\/asset-cleanup\/css\/item\/handle-title-here-v10-8683e3d8975dab70c7f368d58203e66e70fb3e06.css","ver":10}</code></p>

        <p>Once this information is retrieved, the file's original URL will be updated to match the optimized one for the file's content stored in <code><?php echo \WpAssetCleanUp\OptimiseAssets\OptimizeCommon::getRelPathPluginCacheDir(); ?></code>.</p>

        <p><strong>Note:</strong> If you are using a plugin such as WP Rocket, WP Fastest Cache or the caching system provided by your hosting company, then this fetching process would be significantly reduced as visitors will access static HTML pages read from the caching. Technically, no SQL queries should be made as the WordPress environment would not be loaded as it happens with a non-cached page (e.g. when you are logged-in and access the front-end pages).</p>
    </div>
</div>

<!-- -->