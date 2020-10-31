<?php
/*
 * No direct access to this file
 */
if (! isset($data)) {
	exit;
}

$tabIdArea = 'wpacu-setting-test-mode';
$styleTabContent = ($selectedTabArea === $tabIdArea) ? 'style="display: table-cell;"' : '';
?>
<div id="<?php echo $tabIdArea; ?>" class="wpacu-settings-tab-content" <?php echo $styleTabContent; ?>>
	<h2 class="wpacu-settings-area-title"><?php _e('Test Mode', 'wp-asset-clean-up'); ?></h2>
	<p><?php echo sprintf(__('Have your visitors load the website without any %s settings while you\'re going through the plugin setup and unloading the useless CSS &amp; JavaScript!', 'wp-asset-clean-up'), WPACU_PLUGIN_TITLE); ?></p>
	<table class="wpacu-form-table">
		<tr valign="top">
			<th scope="row" class="setting_title">
				<label for="wpacu_enable_test_mode"><?php _e('Enable Test Mode?', 'wp-asset-clean-up'); ?></label>
				<p class="wpacu_subtitle"><small><em><?php _e('Apply plugin\'s changes for the admin only', 'wp-asset-clean-up'); ?></em></small></p>
				<p class="wpacu_read_more"><a target="_blank" href="https://assetcleanup.com/docs/?p=84"><?php _e('Read More', 'wp-asset-clean-up'); ?></a></p>
			</th>
			<td>
				<label class="wpacu_switch">
					<input id="wpacu_enable_test_mode"
					       type="checkbox"
						<?php echo (($data['test_mode'] == 1) ? 'checked="checked"' : ''); ?>
						   name="<?php echo WPACU_PLUGIN_ID . '_settings'; ?>[test_mode]"
						   value="1" /> <span class="wpacu_slider wpacu_round"></span> </label>
				&nbsp;
				<?php _e('This is great for debugging when you\'re going through trial and error while removing unneeded CSS &amp; JavaScript on your website.', 'wp-asset-clean-up'); ?>

                <div style="margin-top: 10px;" class="wpacu-warning">
                    <p style="margin-top: 0;"><?php _e('Your visitors will load the website with all the settings &amp; assets loaded (just like it was before you activated the plugin). Only YOU (the logged-in administrator) will see the plugin\'s settings &amp; unload rules applied.', 'wp-asset-clean-up'); ?></p>
                    <p><?php _e('To view the website as a guest visitor, just make sure you access it from a browser where you\'re not logged in, or you can test it in Incognito (Private) mode.', 'wp-asset-clean-up'); ?> (e.g. to access it in Chrome yo go to <em>File -&gt; New Incognito Window</em>, while on Firefox &amp; Safari, you access it via <em>File -&gt; New Private Window</em>) <img src="<?php echo WPACU_PLUGIN_URL; ?>/assets/icons/icon-incognito.png" alt="" style="height: 22px; width: 22px; vertical-align: middle; margin-right: 5px;" /></p>
                </div>

                    <p><strong><?php _e('Example', 'wp-asset-clean-up'); ?>:</strong> <?php _e('For instance, you have an eCommerce website (e.g. WooCommerce, Easy Digital Downloads), and you\'re worried that unloading one wrong asset could break the "add to cart" functionality or the layout of the product page. You can enable this option, do the unloading for the CSS &amp; JavaScript files you believe are not needed on certain pages, test to check if everything is alright, and then disable test mode to enable the unloading for your visitors too (not only the admin).', 'wp-asset-clean-up'); ?></p>

                <div class="wpacu-warning">
                    <p style="margin-top: 0;"><span class="dashicons dashicons-info"></span> <?php echo sprintf(__('<strong>Important:</strong> If you\'re using page speed test tools such as GTMetrix, Pingdom, Google PageSpeed Insights, etc., while test mode is enabled, you will not see any improvements in the reports because, technically, %s plugin is deactivated for guests users and anyone else (including bots and GTMetrix visits), the changes you made being visible only to you.', 'wp-asset-clean-up'), WPACU_PLUGIN_TITLE); ?></p>
                    <p><?php _e('Once everything is alright with the way your websites loads, after you stripped the "fat" from your pages, you can disable test mode to apply the changes to everyone and then run tests via GTMetrix or other similar tools.', 'wp-asset-clean-up'); ?></p>
                </div>
            </td>
		</tr>
	</table>
</div>