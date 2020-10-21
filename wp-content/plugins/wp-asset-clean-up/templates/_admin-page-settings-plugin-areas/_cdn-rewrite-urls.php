<?php
/*
 * No direct access to this file
 */

use WpAssetCleanUp\OptimiseAssets\OptimizeCommon;

if (! isset($data, $selectedTabArea)) {
    exit;
}

global $wp_version;

$tabIdArea = 'wpacu-setting-cdn-rewrite-urls';
$styleTabContent = ($selectedTabArea === $tabIdArea) ? 'style="display: table-cell;"' : '';

$wpacuCloudFlareIconUrl = WPACU_PLUGIN_URL . '/assets/icons/icon-cloudflare.svg';
$wpacuCloudflareImgIcon = <<<HTML
<img alt="" style="margin-left: 4px; vertical-align: middle; width: 22px; height: 22px;" src="{$wpacuCloudFlareIconUrl}" />
HTML;
?>
<div id="<?php echo $tabIdArea; ?>" class="wpacu-settings-tab-content" <?php echo $styleTabContent; ?>>
    <h2 class="wpacu-settings-area-title"><?php _e('Rewrite cached static assets URLs with the CDN ones if necessary', 'wp-asset-clean-up'); ?></h2>

    <div class="wpacu-warning" style="margin: 0 0 20px;">
        <p style="margin: 0;"><strong>Note:</strong> This option is only needed if you <strong>already use a CDN</strong> (apart from Cloudflare) and the URL to any cached CSS/JS from Asset CleanUp Pro is the local one and not the one from CDN. <span style="white-space: nowrap;"><a style="display: inline; text-decoration: none; color: #0073aa;" target="_blank" href="https://assetcleanup.com/docs/?p=957"><span style="font-size: 25px; margin-top: -2px;" class="dashicons dashicons-editor-help"></span</a> <a style="display: inline; margin-left: 6px;" target="_blank" href="https://assetcleanup.com/docs/?p=957">Read more about it</a></span></p>
        <p id="wpacu-site-uses-cloudflare" style="display: none; margin: 10px 0 0 0;"><?php echo $wpacuCloudflareImgIcon; ?> Cloudflare CDN/Proxy is used for your website, meaning that a CDN is already active. Unless the assets are already set to load from a different CDN for any reason, then you <strong>do not need</strong> to enable this feature.</p>
    </div>

    <table class="wpacu-form-table">
        <tr valign="top">
            <th scope="row" class="setting_title">
                <label for="wpacu_cdn_rewrite_enable"><?php _e('Enable CDN URL rewrite?', 'wp-asset-clean-up'); ?></label>
                <p class="wpacu_subtitle"><small><em><?php echo sprintf(__('This applies ONLY to files saved in %s', 'wp-asset-clean-up'), '<code style="font-size: inherit;">'.str_replace(ABSPATH, '', '/' . WP_CONTENT_DIR . OptimizeCommon::getRelPathPluginCacheDir().'</code>')); ?></em></small></p>
            </th>
            <td>
                <label class="wpacu_switch">
                    <input id="wpacu_cdn_rewrite_enable"
                           data-target-opacity="wpacu_cdn_rewrite_enable_area"
                           type="checkbox"
                           <?php
                           echo (($data['cdn_rewrite_enable'] == 1) ? 'checked="checked"' : '');
                           ?>
                           name="<?php echo WPACU_PLUGIN_ID . '_settings'; ?>[cdn_rewrite_enable]"
                           value="1" /> <span class="wpacu_slider wpacu_round"></span> </label>

                &nbsp;If you're using a CDN enabled through your hosting company or by another party plugin, the external URL is not always recognisable by <?php echo WPACU_PLUGIN_TITLE; ?> and it's considered an external URL unconnected to your website's CSS/JS files. To fix, this, please put the CDN's CNAME/URL in the inputs below to make sure the files are detected as local files and optimized accordingly.

                <?php
				$cdnRewriteAreaStyle = ($data['cdn_rewrite_enable'] == 1) ? 'opacity: 1;' : 'opacity: 0.4;';
				?>
                <div id="wpacu_cdn_rewrite_enable_area" style="<?php echo $cdnRewriteAreaStyle; ?>">
                    <div style="margin-top: 20px; margin-bottom: 0;"></div>
                    <table>
                        <tr>
                            <td style="vertical-align: top;" valign="top">For Stylesheet (.css) Files:&nbsp;&nbsp;</td>
                            <td style="padding-bottom: 10px;">
                                <label for="wpacu_cdn_rewrite_url_css"><input id="wpacu_cdn_rewrite_url_css"
                                        name="<?php echo WPACU_PLUGIN_ID . '_settings'; ?>[cdn_rewrite_url_css]"
                                        value="<?php echo $data['cdn_rewrite_url_css']; ?>"
                                        style="width: 300px;" /><br />
                                </label>

                                <ul style="font-style: italic; line-height: 13px; font-size: 12px; margin-top: 5px; margin-bottom: 0;">
                                    <li>e.g. //css-zone-name.kxcdn.com</li>
                                    <li>zone-name.kxcdn.com etc.</li>
                                </ul>
                            </td>
                        </tr>
                        <tr>
                            <td style="vertical-align: top;" valign="top">For JavaScript (.js) Files:&nbsp;&nbsp;</td>
                            <td style="padding-bottom: 3px;"><label for="wpacu_cdn_rewrite_url_js">
                                    <input id="wpacu_cdn_rewrite_url_js"
                                           name="<?php echo WPACU_PLUGIN_ID . '_settings'; ?>[cdn_rewrite_url_js]"
                                           value="<?php echo $data['cdn_rewrite_url_js']; ?>"
                                           style="width: 300px;" /><br />
                                </label>
                                <ul style="font-style: italic; line-height: 13px; font-size: 12px; margin-top: 5px;">
                                    <li>e.g. //js-zone-name.kxcdn.com</li>
                                    <li>zone-name.kxcdn.com etc.</li>
                                </ul>
                            </td>
                        </tr>
                    </table>
                    <hr />
                    <p style="margin-top: 10px;"><strong>Note:</strong> Most of the time the CNAME / CDN URL is the same for both CSS &amp; JS files. You can use the same value in both fields.</p>

                    <p class="wpacu-warning" style="font-size: inherit;">
                        <span class="dashicons dashicons-warning"></span> If you're unsure if the <strong>C</strong>ontent <strong>D</strong>elivery <strong>N</strong>etwork's CNAME/URL is the right one, please enable "Test Mode" to test it out, thus making sure the layout won't be broken for your website visitors.
                    </p>
                </div>
			</td>
		</tr>
	</table>
</div>
