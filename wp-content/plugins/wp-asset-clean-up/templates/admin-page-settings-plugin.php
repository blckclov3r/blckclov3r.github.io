<?php
/*
 * No direct access to this file
 */
if (! isset($data)) {
    exit;
}

include_once '_top-area.php';

if (! defined('WPACU_USE_MODAL_BOX')) {
	define('WPACU_USE_MODAL_BOX', true);
}

// [wpacu_lite]
$availableForPro  = '<a href="'.WPACU_PLUGIN_GO_PRO_URL.'?utm_source=plugin_settings" class="go-pro-link-no-style"><span class="wpacu-tooltip">'.__('Available for Pro users', 'wp-asset-clean-up').'<br />'.__('Buy now to unlock all features!', 'wp-asset-clean-up').'</span> <img width="20" height="20" src="'.WPACU_PLUGIN_URL.'/assets/icons/icon-lock.svg" valign="top" alt="" /></a> &nbsp; ';
$settingsWithLock = '<em><strong>'.__('Note', 'wp-asset-clean-up').':</strong> '.__('The settings that have a lock are available to Pro users.', 'wp-asset-clean-up').' <a href="' . WPACU_PLUGIN_GO_PRO_URL . '?utm_source=plugin_settings">'.__('Click here to upgrade!', 'wp-asset-clean-up').'</a></em>';
// [/wpacu_lite]

do_action('wpacu_admin_notices');

$wikiStatus = ($data['wiki_read'] == 1) ? '<small style="font-weight: 200; color: green;">* '.__('read', 'wp-asset-clean-up').'</small>'
	: '<small style="font-weight: 200; color: #cc0000;"><span class="dashicons dashicons-warning" style="width: 15px; height: 15px; margin: 2px 0 0 0; font-size: 16px;"></span> '.__('unread', 'wp-asset-clean-up').'</small>';

$showSettingsType = array_key_exists('wpacu_show_all', $_GET) ? 'all' : 'tabs';
$selectedTabArea = $selectedSubTabArea = '';

if ($showSettingsType === 'tabs') {
	$settingsTabs = array(
		'wpacu-setting-strip-the-fat'         => __( 'Stripping the "fat"', 'wp-asset-clean-up' ) . ' ' . $wikiStatus,
		'wpacu-setting-plugin-usage-settings' => __( 'Plugin Usage Preferences', 'wp-asset-clean-up' ),
		'wpacu-setting-test-mode'             => __( 'Test Mode', 'wp-asset-clean-up' ),
		'wpacu-setting-optimize-css'          => __( 'Optimize CSS', 'wp-asset-clean-up' ),
		'wpacu-setting-optimize-js'           => __( 'Optimize JavaScript', 'wp-asset-clean-up' ),
		'wpacu-setting-cdn-rewrite-urls'      => __( 'CDN: Rewrite assets URLs', 'wp-asset-clean-up' ),
		'wpacu-setting-common-files-unload'   => __( 'Site-Wide Common Unloads', 'wp-asset-clean-up' ),
		'wpacu-setting-html-source-cleanup'   => __( 'HTML Source CleanUp', 'wp-asset-clean-up' ),
		'wpacu-setting-local-fonts'           => __( 'Local Fonts', 'wp-asset-clean-up' ),
		'wpacu-setting-google-fonts'          => __( 'Google Fonts', 'wp-asset-clean-up' ),
		'wpacu-setting-disable-xml-rpc'       => __( 'Disable XML-RPC', 'wp-asset-clean-up' ),
	);

	$settingsSubTabs = array(
        'wpacu-google-fonts-optimize',
        'wpacu-google-fonts-remove'
    );

	$settingsTabActive = 'wpacu-setting-plugin-usage-settings';

    // Is 'Stripping the "fat"' marked as read? Mark the "General & Files Management" as the default tab
	$defaultTabArea = ($data['wiki_read'] == 1) ? 'wpacu-setting-plugin-usage-settings' : 'wpacu-setting-strip-the-fat';

	$selectedTabArea = isset($_REQUEST['wpacu_selected_tab_area']) && array_key_exists($_REQUEST['wpacu_selected_tab_area'],
		$settingsTabs) // the tab id area has to be one within the list above
		? sanitize_text_field($_REQUEST['wpacu_selected_tab_area']) // after update
		: $defaultTabArea; // default

	if ($selectedTabArea && array_key_exists($selectedTabArea, $settingsTabs)) {
		$settingsTabActive = $selectedTabArea;
	}

	$selectedSubTabArea = isset($_REQUEST['wpacu_selected_sub_tab_area']) // after update
        ? sanitize_text_field($_REQUEST['wpacu_selected_sub_tab_area'])
        : ''; // default
}
?>
<div class="wpacu-wrap wpacu-settings-area <?php if ($showSettingsType === 'all') { echo 'wpacu-settings-show-all'; } ?> <?php if ($data['input_style'] !== 'standard') { ?>wpacu-switch-enhanced<?php } else { ?>wpacu-switch-standard<?php } ?>">
    <form method="post" action="" id="wpacu-settings-form">
        <input type="hidden" name="wpacu_settings_page" value="1" />

        <div id="wpacu-settings-vertical-tab-wrap">
            <?php if ($showSettingsType === 'tabs') { ?>
                <div class="wpacu-settings-tab">
                    <?php
                    $wpacuOptionOn  = '<span class="wpacu-circle-status wpacu-on"></span>';
                    $wpacuOptionOff = '<span class="wpacu-circle-status wpacu-off"></span>';

                    foreach ($settingsTabs as $settingsTabKey => $settingsTabText) {
	                    $wpacuActiveTab  = ($settingsTabActive === $settingsTabKey) ? 'active' : '';
	                    $wpacuNavTextSub = '';

                        if ($settingsTabKey === 'wpacu-setting-test-mode') {
	                        $testModeStatus = ($data['test_mode'] == 1) ? $wpacuOptionOn : $wpacuOptionOff;
	                        $wpacuNavTextSub = '<div class="wpacu-tab-extra-text" style="display: inline-block; margin-left: 8px;"><small><span class="wpacu-status-wrap" data-linked-to="wpacu_enable_test_mode">'.$testModeStatus.'</span></small></div>';
                        }

	                    if ($settingsTabKey === 'wpacu-setting-optimize-css') {
	                        $cssMinifyStatus  = ($data['minify_loaded_css']  == 1 && empty($data['is_optimize_css_enabled_by_other_party'])) ? $wpacuOptionOn : $wpacuOptionOff;
		                    $cssCombineStatus = ($data['combine_loaded_css'] == 1 && empty($data['is_optimize_css_enabled_by_other_party'])) ? $wpacuOptionOn : $wpacuOptionOff;
		                    $wpacuNavTextSub  = '<div class="wpacu-tab-extra-text"><small><span class="wpacu-status-wrap" data-linked-to="wpacu_minify_css_enable">' . $cssMinifyStatus . ' '.__('Minify', 'wp-asset-clean-up').'</span> &nbsp;&nbsp; <span class="wpacu-status-wrap" data-linked-to="wpacu_combine_loaded_css_enable">' . $cssCombineStatus . ' '.__('Combine', 'wp-asset-clean-up').'</span>&nbsp; <span style="color: gray;">+ Defer, Inline</span></small></small></div>';

		                    if (! empty($data['is_optimize_css_enabled_by_other_party'])) {
			                    $wpacuNavTextSub .= '<div style="margin-top: 3px;"><small style="font-weight: lighter; color: gray;"><strong>Status:</strong> Partially locked, already enabled in other plugin(s)</small></div>';
                            }
	                    }

	                    if ($settingsTabKey === 'wpacu-setting-optimize-js') {
		                    $jsMinifyStatus  = ($data['minify_loaded_js']  == 1 && empty($data['is_optimize_js_enabled_by_other_party'])) ? $wpacuOptionOn : $wpacuOptionOff;
		                    $jsCombineStatus = ($data['combine_loaded_js'] == 1 && empty($data['is_optimize_js_enabled_by_other_party'])) ? $wpacuOptionOn : $wpacuOptionOff;
		                    $wpacuNavTextSub = '<div class="wpacu-tab-extra-text"><small><span class="wpacu-status-wrap" data-linked-to="wpacu_minify_js_enable">' . $jsMinifyStatus . ' '.__('Minify', 'wp-asset-clean-up').'</span> &nbsp;&nbsp; <span class="wpacu-status-wrap" data-linked-to="wpacu_combine_loaded_js_enable">' . $jsCombineStatus . ' '.__('Combine', 'wp-asset-clean-up').'</span>&nbsp; <span style="color: gray;">+ Defer, Inline</span></small></small></div>';

		                    if (! empty($data['is_optimize_js_enabled_by_other_party'])) {
			                    $wpacuNavTextSub .= '<div style="margin-top: 3px;"><small style="font-weight: lighter; color: gray;"><strong>Status:</strong> Locked, already enabled in other plugin(s)</small></div>';
		                    }
	                    }

	                    if ($settingsTabKey === 'wpacu-setting-cdn-rewrite-urls') {
		                    $cdnRewriteStatus = ($data['cdn_rewrite_enable'] == 1) ? $wpacuOptionOn : $wpacuOptionOff;
		                    $wpacuNavTextSub = '<div class="wpacu-tab-extra-text" style="display: inline-block; margin-left: 8px;"><small><span class="wpacu-status-wrap" data-linked-to="wpacu_cdn_rewrite_enable">'.$cdnRewriteStatus.'</span></small></div>';
                        }

                        if ($settingsTabKey === 'wpacu-setting-local-fonts') {
	                        $wpacuNavTextSub .= '<div style="margin-top: 3px;"><small style="font-weight: lighter;">Font-Display, Preload</small></div>';
                        }

	                    if ($settingsTabKey === 'wpacu-setting-google-fonts') {
		                    $wpacuNavTextSub .= '<div style="margin-top: 3px;"><small style="font-weight: lighter;">Combine, Async Load, Font-Display, Preconnect, Preload, Removal</small></div>';
	                    }
                    ?>
                        <a href="#<?php echo $settingsTabKey; ?>"
                           class="wpacu-settings-tab-link <?php echo $wpacuActiveTab; ?>"
                           onclick="wpacuTabOpenSettingsArea(event, '<?php echo $settingsTabKey; ?>');"><?php echo $settingsTabText . $wpacuNavTextSub; ?></a>
                    <?php
                    }
                    ?>
                </div>
            <?php } ?>

            <?php
            include_once '_admin-page-settings-plugin-areas/_strip-the-fat.php';
            include_once '_admin-page-settings-plugin-areas/_plugin-usage-settings.php';
            include_once '_admin-page-settings-plugin-areas/_test-mode.php';
            include_once '_admin-page-settings-plugin-areas/_optimize-css.php';
            include_once '_admin-page-settings-plugin-areas/_optimize-js.php';
            include_once '_admin-page-settings-plugin-areas/_cdn-rewrite-urls.php';
            include_once '_admin-page-settings-plugin-areas/_common-files-unload.php';
            include_once '_admin-page-settings-plugin-areas/_html-source-cleanup.php';
            include_once '_admin-page-settings-plugin-areas/_fonts-local.php';
            include_once '_admin-page-settings-plugin-areas/_fonts-google.php';
            include_once '_admin-page-settings-plugin-areas/_disable-xml-rpc-protocol.php';
            ?>

            <div class="clearfix"></div>
        </div>

        <div id="wpacu-update-button-area">
			<?php
			wp_nonce_field('wpacu_settings_update', 'wpacu_settings_nonce');
			submit_button(__('Update All Settings', 'wp-asset-clean-up'));
			?>
            <div id="wpacu-updating-settings">
                <img src="<?php echo admin_url('images/spinner.gif'); ?>" align="top" width="20" height="20" alt="" />
            </div>
        </div>
        <input type="hidden"
               name="wpacu_selected_tab_area"
               id="wpacu-selected-tab-area"
               value="<?php echo $selectedTabArea; ?>" />
        <input type="hidden"
               name="wpacu_selected_sub_tab_area"
               id="wpacu-selected-sub-tab-area"
               value="<?php echo $selectedSubTabArea; ?>" />
    </form>
</div>

<script type="text/javascript">
    <?php
    if (! empty($_POST)) {
    ?>
        // Situations: After settings update (post mode), do not jump to URL's anchor
        if (location.hash) {
            setTimeout(function() {
                window.scrollTo(0, 0);
            }, 1);
        }
    <?php
    }
    ?>
</script>