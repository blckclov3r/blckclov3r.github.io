<?php
/*
 * No direct access to this file
 */
if (! isset($data)) {
	exit;
}

$tabIdArea = 'wpacu-setting-local-fonts';
$styleTabContent = ($selectedTabArea === $tabIdArea) ? 'style="display: table-cell;"' : '';

$ddOptions = array(
	'swap' => 'swap (most used)',
	'auto' => 'auto',
	'block' => 'block',
	'fallback' => 'fallback',
	'optional' => 'optional'
);

// [wpacu_lite]
$availableForPro = '<a class="go-pro-link-no-style" target="_blank" href="' . WPACU_PLUGIN_GO_PRO_URL . '?utm_source=plugin_usage_settings&utm_medium=local_fonts_optimization"><span class="wpacu-tooltip" style="width: 186px;">'.__('This is a feature available in the Pro version! Unlock it!', 'wp-asset-clean-up').'</span> <img style="opacity: 0.6;" width="20" height="20" src="'.WPACU_PLUGIN_URL.'/assets/icons/icon-lock.svg" valign="top" alt="" /></a>';
// [/wpacu_lite]
?>
<div id="<?php echo $tabIdArea; ?>" class="wpacu-settings-tab-content" <?php echo $styleTabContent; ?>>
    <h2 class="wpacu-settings-area-title"><?php _e('Local Fonts Optimization', 'wp-asset-clean-up'); ?></h2>
    <table class="wpacu-form-table">
        <tr valign="top">
            <th scope="row" class="setting_title">
			    <?php echo sprintf(__('Apply %s CSS property value', 'wp-asset-clean-up'), '<span style="background: #f5f5f5; padding: 4px;">font-display:</span>'); ?>
            </th>
            <td>
	            <?php echo $availableForPro; ?>&nbsp;
                &nbsp;<select
                        <?php /* [wpacu_lite] */ ?>
                        style="opacity: 0.5;"
		                <?php /* [/wpacu_lite] */ ?>
                        name="<?php echo WPACU_PLUGIN_ID . '_settings'; ?>[local_fonts_display]">
                    <option value="">Do not apply (default)</option>
				    <?php
				    foreach ($ddOptions as $ddOptionValue => $ddOptionText) {
					    $selectedOption = ($data['local_fonts_display'] === $ddOptionValue) ? 'selected="selected"' : '';
					    echo '<option '.$selectedOption.' value="'.$ddOptionValue.'">'.$ddOptionText.'</option>'."\n";
				    }
				    ?>
                </select> &nbsp; / &nbsp;

                <div style="display: inline-block; opacity: 0.5;">
                    Overwrite any existing "font-display" value? &nbsp;
                <label for="wpacu_local_fonts_display_overwrite_no"><input id="wpacu_local_fonts_display_overwrite_no"
                           disabled="disabled"
                           checked="checked"
                           type="radio"
                           name="<?php echo WPACU_PLUGIN_ID . '_settings'; ?>[local_fonts_display_overwrite]"
                               value="" />No</label>
                    &nbsp;&nbsp;&nbsp;
                    <label for="wpacu_local_fonts_display_overwrite_yes"><input id="wpacu_local_fonts_display_overwrite_yes"
                           disabled="disabled"
                           type="radio"
                           name="<?php echo WPACU_PLUGIN_ID . '_settings'; ?>[local_fonts_display_overwrite]"
                           value="1" />Yes</label>
                </div>
                &nbsp;
                <p><?php _e('This feature applies site-wide "font-display:" property (if none set already in the file) within @font-face in every loaded CSS file.', 'wp-asset-clean-up'); ?> &nbsp; <span style="color: #0073aa;" class="dashicons dashicons-info"></span>&nbsp;<a id="wpacu-local-fonts-display-info-target" href="#wpacu-local-fonts-display-info"><?php _e('Read more', 'wp-asset-clean-up'); ?></a></p>
                <p><?php echo sprintf(__('The new generated CSS files will be loaded from <code>%s</code>, as the existing files from plugins/themes will not be altered in any way.', 'wp-asset-clean-up'), \WpAssetCleanUp\OptimiseAssets\OptimizeCommon::getRelPathPluginCacheDir()); ?></p>

                <hr />

                <p><?php echo __('Deciding the behavior for a web font as it is loading can be an important performance tuning technique. If applied, this option ensures text remains visible during webfont load.', 'wp-asset-clean-up'); ?> <?php _e('The <code>font-display</code> CSS property defines how font files are loaded and display by the browser.', 'wp-asset-clean-up'); ?></p>

                <strong>Read more about this:</strong>
                    <a target="_blank" href="https://css-tricks.com/hey-hey-font-display/">Hey hey `font-display`</a> &nbsp;|&nbsp;
                    <a target="_blank" href="https://css-tricks.com/font-display-masses/">`font-display` for the Masses</a> &nbsp;|&nbsp;
                    <a target="_blank" href="https://developers.google.com/web/updates/2016/02/font-display">Controlling Font Performance with font-display</a> &nbsp;|&nbsp;
                    <a target="_blank" href="https://font-display.glitch.me/">https://font-display.glitch.me/</a> &nbsp;|&nbsp;
                    <a target="_blank" href="https://vimeo.com/241111413">Video: Fontastic Web Performance</a>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row" class="setting_title">
				<?php _e('Preload Local Font Files', 'wp-asset-clean-up'); ?>
                <p class="wpacu_subtitle"><small><em><?php _e('One per line', 'wp-asset-clean-up'); ?></em></small></p>
            </th>
            <td>
                <div style="margin: 0 0 6px;"><?php _e('If you wish to preload any of the Local Font Files (ending in .woff, .woff2, .ttf etc.), you can add their URI here like in the examples below (one per line)', 'wp-asset-clean-up'); ?>:</div>
                <textarea style="width:100%;"
                          rows="5"
                          name="<?php echo WPACU_PLUGIN_ID . '_settings'; ?>[local_fonts_preload_files]"><?php echo $data['local_fonts_preload_files']; ?></textarea>
                <hr />
                <strong>Examples:</strong>
                <div style="margin-top: 5px;">
                    <div><code>/wp-content/themes/your-theme-dir/fonts/lato.woff</code></div>
                    <div><code>/wp-content/plugins/plugin-title-here/fonts/fontawesome-webfont.ttf?v=4.5.0</code></div>
                </div>
                <hr />
                <strong>Generated Output</strong>, printed within <code>&lt;HEAD&gt;</code> and <code>&lt;/HEAD&gt;</code>
                <div style="margin-top: 5px;">
                    <div style="margin-bottom: 8px;"><code>&lt;link rel="preload" as="font" href="/wp-content/themes/your-theme-dir/fonts/lato.woff" data-wpacu-preload-font="1" crossorigin&gt;</code></div>
                    <div style="margin-bottom: 8px;"><code>&lt;link rel="preload" as="font" href="/wp-content/plugins/plugin-title-here/fonts/fontawesome-webfont.ttf?v=4.5.0" data-wpacu-preload-font="1" crossorigin&gt;</code></div>
                </div>
            </td>
        </tr>
    </table>
</div>

<div id="wpacu-local-fonts-display-info" class="wpacu-modal" style="padding-top: 60px;">
    <div class="wpacu-modal-content" style="max-width: 800px;">
        <span class="wpacu-close">&times;</span>
        <h3 style="margin-top: 2px; margin-bottom: 4px;">font-display: <span style="background: #f2faf2;">swap</span></h3>
        <p style="margin-top: 0; margin-bottom: 22px;">The text is shown immediately (without any block period, no invisible text) in the fallback font until the custom font loads, then it's swapped with the custom font. You get a <strong>FOUT</strong> (<em>flash of unstyled text</em>).</p>

        <h3 style="margin-bottom: 4px;">font-display: <span style="background: #f2faf2;">block</span></h3>
        <p style="margin-top: 0; margin-bottom: 22px;">The text blocks (is invisible) for a short period. Then, if the custom font hasn't been downloaded yet, the browser swaps (renders the text in the fallback font), for however long it takes the custom font to be downloaded, and then re-renders the text in the custom font. You get a <strong>FOIT</strong> (<em>flash of invisible text</em>).</p>

        <h3 style="margin-bottom: 4px;">font-display: <span style="background: #f2faf2;">fallback</span></h3>
        <p style="margin-top: 0; margin-bottom: 22px;">This is somewhere in between block and swap. The text is invisible for a short period of time (100ms). Then if the custom font hasn't downloaded, the text is shown in a fallback font (for about 3s), then swapped after the custom font loads.</p>

        <h3 style="margin-bottom: 4px;">font-display: <span style="background: #f2faf2;">optional</span></h3>
        <p style="margin-top: 0; margin-bottom: 22px;">This behaves just like fallback, only the browser can decide to not use the custom font at all, based on the user's connection speed (if you're on a slow 3G or less, it will take forever to download the custom font and then swapping to it will be too late and extremely annoying)</p>

        <h3 style="margin-bottom: 4px;">font-display: <span style="background: #f2faf2;">auto</span></h3>
        <p style="margin-top: 0; margin-bottom: 0;">The default. Typical browser font loading behavior will take place. This behavior may be FOIT, or FOIT with a relatively long invisibility period. This may change as browser vendors decide on better default behaviors.</p>

        <h3 style="margin-bottom: 4px;">Example of a @font-face CSS output</h3>
        <code>@font-face{font-family:'proxima-nova-1';src:url("/wp-content/themes/my-theme-dir/fonts/proxima-nova-light.woff2") format("woff2"),url("/wp-content/themes/my-theme-dir/fonts/proxima-nova-light.woff") format("woff");font-weight:300;font-style:normal;font-stretch:normal;<span style="background: #f2faf2;">font-display:swap</span>}</code>
    </div>
</div>