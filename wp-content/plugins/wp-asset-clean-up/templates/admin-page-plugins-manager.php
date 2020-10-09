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
<div style="border-radius: 5px; line-height: 20px; background: white; padding: 8px; margin: -24px 0 0 0; width: 95%; border-left: 4px solid #004567; border-top: 1px solid #e7e7e7; border-right: 1px solid #e7e7e7; border-bottom: 1px solid #e7e7e7;">
    <strong>Remember:</strong> While "CSS &amp; JS Manager" helps you unload unused files within plugins, this feature allows you prevent plugins from loading completely on the targeted pages (e.g. the PHP code &amp; any MySQL queries, HTML output printed via <code>wp_head()</code> or <code>wp_footer()</code> action hook, cookies that are set). It would be like the plugin is deactivated for the pages where it's chosen to be unloaded. Consider enabling "Test Mode" in plugin's "Settings" if you're unsure about anything. Managing the plugin rules per page the same way it works with the CSS/JS manager is a feature that is not available yet as managing plugin rules is an option that has just been released. However, the RegEx unload rules can often achieve the same results. <span class="dashicons dashicons-info"></span> <a target="_blank" href="https://assetcleanup.com/docs/?p=372#wpacu-unload-plugins-via-regex"> Read more</a>
</div>
<p style="width: 98%; margin: 15px 0 0;"><small><strong>Remember:</strong> All the rules are applied in the front-end view only. They are not taking effect within the Dashboard (the function <code style="font-size: inherit;">is_admin()</code> is used to verify that) to make sure nothing will get broken while you're configuring any plugins' settings. If you wish to completely stop using a plugin, the most effective way would be to deactivate it from the "Plugins" -&gt; "Installed Plugins" area.</small></p>
<div class="wpacu-wrap" id="wpacu-plugins-load-manager-wrap">
    <form>
        <?php
        $pluginsRows = array();

        foreach ($data['active_plugins'] as $pluginData) {
            $pluginPath = $pluginData['path'];
            list($pluginDir) = explode('/', $pluginPath);
            ob_start();
        ?>
            <tr>
                <td class="wpacu_plugin_icon" width="40">
                    <?php if(isset($data['plugins_icons'][$pluginDir])) { ?>
                        <img width="40" height="40" alt="" src="<?php echo $data['plugins_icons'][$pluginDir]; ?>" />
                    <?php } else { ?>
                        <div><span class="dashicons dashicons-admin-plugins"></span></div>
                    <?php } ?>
                </td>
                <td class="wpacu_plugin_details">
                    <span class="wpacu_plugin_title"><?php echo $pluginData['title']; ?></span> <span class="wpacu_plugin_path">&nbsp;<small><?php echo $pluginData['path']; ?></small></span>
                    <div class="wpacu-clearfix"></div>

                    <div class="wrap_plugin_unload_rules_options">
                        <!-- [Start] Unload Rules -->
                        <div class="wpacu_plugin_rules_wrap">
                            <ul class="wpacu_plugin_rules">
                                <li>
                                    <label for="wpacu_global_load_plugin_<?php echo $pluginPath; ?>">
                                        <input data-wpacu-plugin-path="<?php echo $pluginPath; ?>"
                                               checked="checked"
                                               disabled="disabled"
                                               class="wpacu_plugin_load_it wpacu_plugin_load_rule_input"
                                               id="wpacu_global_load_plugin_<?php echo $pluginPath; ?>"
                                               type="radio"
                                               name="wpacu_plugins[<?php echo $pluginPath; ?>][status]"
                                               value="" />
                                        Always load it <small>(default)</small></label>
                                </li>
                            </ul>
                        </div>

                        <div class="wpacu_plugin_rules_wrap">
                            <ul class="wpacu_plugin_rules">
                                <li>
                                    <label for="wpacu_global_unload_plugin_<?php echo $pluginPath; ?>">
                                        <input data-wpacu-plugin-path="<?php echo $pluginPath; ?>"
                                               disabled="disabled"
                                               class="disabled wpacu_plugin_unload_site_wide wpacu_plugin_unload_rule_input"
                                               id="wpacu_global_unload_plugin_<?php echo $pluginPath; ?>"
                                               type="radio"
                                               name="wpacu_plugins[<?php echo $pluginPath; ?>][status]"
                                               value="unload_site_wide" />
                                        <a class="go-pro-link-no-style"
                                           href="<?php echo WPACU_PLUGIN_GO_PRO_URL; ?>?utm_source=manage_asset&utm_medium=unload_plugin_site_wide"><span class="wpacu-tooltip" style="width: 200px; margin-left: -146px;">This feature is locked for Pro users<br />Click here to upgrade!</span><img width="20" height="20" src="<?php echo WPACU_PLUGIN_URL; ?>/assets/icons/icon-lock.svg" valign="top" alt="" /></a>&nbsp; Unload site-wide (everywhere) <small>&amp; add an exception</small></label>
                                </li>
                            </ul>
                        </div>

                        <div class="wpacu_plugin_rules_wrap">
                            <ul class="wpacu_plugin_rules">
                                <li>
                                    <label for="wpacu_unload_it_regex_option_<?php echo $pluginPath; ?>"
                                           style="margin-right: 0;">
                                        <input data-wpacu-plugin-path="<?php echo $pluginPath; ?>"
                                               disabled="disabled"
                                               id="wpacu_unload_it_regex_option_<?php echo $pluginPath; ?>"
                                               class="disabled wpacu_plugin_unload_regex_radio wpacu_plugin_unload_rule_input"
                                               type="radio"
                                               name="wpacu_plugins[<?php echo $pluginPath; ?>][status]"
                                               value="unload_via_regex">
                                        <a class="go-pro-link-no-style"
                                           href="<?php echo WPACU_PLUGIN_GO_PRO_URL; ?>?utm_source=manage_asset&utm_medium=unload_plugin_via_regex"><span class="wpacu-tooltip" style="width: 200px; margin-left: -146px;">This feature is locked for Pro users<br />Click here to upgrade!</span><img width="20" height="20" src="<?php echo WPACU_PLUGIN_URL; ?>/assets/icons/icon-lock.svg" valign="top" alt="" /></a> &nbsp;<span>Unload it only for URLs with request URI matching this RegEx(es):</span></label>
                                    <a class="help_link unload_it_regex"
                                       target="_blank"
                                       href="https://assetcleanup.com/docs/?p=372#wpacu-unload-plugins-via-regex"><span style="color: #74777b;" class="dashicons dashicons-editor-help"></span></a>
                                </li>
                            </ul>
                        </div>
                        <div class="wpacu-clearfix"></div>
                    </div>
                    <!-- [End] Unload Rules -->
                </td>
            </tr>
            <?php
            $trOutput = ob_get_clean();
            $pluginsRows['always_loaded'][] = $trOutput;
        }

        if (isset($pluginsRows['always_loaded']) && ! empty($pluginsRows['always_loaded'])) {
            if (isset($pluginsRows['has_unload_rules']) && count($pluginsRows['has_unload_rules']) > 0) {
                ?>
                <div style="margin-top: 35px;"></div>
                <?php
            }

            $totalAlwaysLoadedPlugins = count($pluginsRows['always_loaded']);
            ?>

            <h3><span style="color: green;" class="dashicons dashicons-admin-plugins"></span> <span style="color: green;"><?php echo $totalAlwaysLoadedPlugins; ?></span> active plugin<?php echo ($totalAlwaysLoadedPlugins > 1) ? 's' : ''; ?> (loaded by default)</h3>
            <table class="wp-list-table wpacu-list-table widefat plugins striped">
                <?php
                foreach ( $pluginsRows['always_loaded'] as $pluginRowOutput ) {
                    echo $pluginRowOutput . "\n";
                }
                ?>
            </table>
            <?php
        }
        ?>
        <div id="wpacu-update-button-area" style="margin-left: 0;">
            <input class="disabled" disabled="disabled" type="hidden" name="wpacu_plugins_manager_submit" value="1" />
        </div>
    </form>
</div>