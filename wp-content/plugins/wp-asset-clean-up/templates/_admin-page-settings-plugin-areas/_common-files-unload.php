<?php
/*
 * No direct access to this file
 */
if (! isset($data)) {
	exit;
}

$tabIdArea = 'wpacu-setting-common-files-unload';
$styleTabContent = ($selectedTabArea === $tabIdArea) ? 'style="display: table-cell;"' : '';
?>
<div id="<?php echo $tabIdArea; ?>" class="wpacu-settings-tab-content" <?php echo $styleTabContent; ?>>
    <h2 class="wpacu-settings-area-title"><?php _e('Site-Wide Unload For Common CSS &amp; JS Files', 'wp-asset-clean-up'); ?></h2>
    <p><?php _e('This area allows you to quickly add the rule "Unload Site-wide" for the scripts below, which are often used in WordPress environments.', 'wp-asset-clean-up'); ?></p>
    <table class="wpacu-form-table">
        <tr valign="top">
            <th scope="row" class="setting_title">
                <label for="wpacu_disable_emojis"><?php echo sprintf(__('Disable %s Site-Wide', 'wp-asset-clean-up'), 'Emojis'); ?></label>
                <p class="wpacu_subtitle"><small><em><?php echo sprintf(__('It will fallback to the default browser\'s Emojis and not fetch the ones from %s', 'wp-asset-clean-up'), 'https://s.w.org/'); ?></em></small></p>
            </th>
            <td>
                <label class="wpacu_switch">
                    <input id="wpacu_disable_emojis"
                           type="checkbox"
						<?php echo (($data['disable_emojis'] == 1) ? 'checked="checked"' : ''); ?>
                           name="<?php echo WPACU_PLUGIN_ID . '_settings'; ?>[disable_emojis]"
                           value="1" /> <span class="wpacu_slider wpacu_round"></span> </label>
                &nbsp;

                <?php echo sprintf(__('This will unload %s', 'wp-asset-clean-up'), 'WordPress\' Emojis'); ?> (the smiley icons)

                <p style="margin-top: 10px;">
                    <?php _e('As of WordPress 4.2, a new feature was introduced that allows you to use the new Emojis.', 'wp-asset-clean-up'); ?>
                    <?php echo __('While on some WordPress setups is useful, in many situations (especially when you are not using WordPress as a blog), you just don’t need them.', 'wp-asset-clean-up'); ?>
                    <?php echo sprintf(__('The file <em>%s</em> (12 KB) is loaded (along with extra inline JavaScript code) which adds up to the number of loaded HTTP requests.', 'wp-asset-clean-up'), '/wp-includes/js/wp-emoji-release.min.js'); ?>
                </p>
            </td>
        </tr>

        <tr valign="top">
            <th scope="row" class="setting_title">
                <label for="wpacu_disable_wp_embed"><?php echo sprintf(__('Disable %s Site-Wide', 'wp-asset-clean-up'), 'oEmbed (Embeds)'); ?></label>
                <p style="margin-top: 2px;" class="wpacu_subtitle"><small><a target="_blank" href="https://wordpress.org/support/article/embeds/">Read more about Embeds</a></small></p>
            </th>
            <td>
                <label class="wpacu_switch">
                    <input id="wpacu_disable_wp_embed"
                           type="checkbox"
					    <?php echo (($data['disable_oembed'] == 1) ? 'checked="checked"' : ''); ?>
                           name="<?php echo WPACU_PLUGIN_ID . '_settings'; ?>[disable_oembed]"
                           value="1" /> <span class="wpacu_slider wpacu_round"></span> </label>
                &nbsp;
			    <?php echo sprintf(__('This will remove %s', 'wp-asset-clean-up'), 'oEmbed feature'); ?>

                <div style="margin-top: 10px;">
				    <?php _e('As of WordPress 4.4, a new oEmbed feature was introduced allowing you to embed videos (e.g. from YouTube), Tweets and and other similar things on your website by pasting a URL, which WordPress converts, providing a live preview of the embedded element in the visual editor.', 'wp-asset-clean-up'); ?>
                    <?php _e('You can disable this feature if you do not need it.', 'wp-asset-clean-up'); ?>
                    <p><?php _e('The situations where you might want to keep it enabled include:', 'wp-asset-clean-up'); ?>
                    <ul>
                        <li>- You want other users to embed your WordPress blog articles on their site.</li>
                        <li>- You would like to embed other websites' articles, YouTube videos, Tweets etc. on you WordPress blog articles.</li>
                    </ul>
				    <?php echo sprintf(__('If this option is kept enabled, the file <em>%s</em> is also loaded which adds up to the number of loaded HTTP requests.', 'wp-asset-clean-up'), '/wp-includes/js/wp-embed.min.js'); ?>
                </div>
            </td>
        </tr>

        <tr valign="top">
            <th scope="row">
                <label for="wpacu_disable_dashicons_for_guests"><?php echo sprintf(__('Disable %s if Toolbar is hidden (Site-Wide)', 'wp-asset-clean-up'), 'Dashicons'); ?></label>
                <p style="margin-top: 2px;" class="wpacu_subtitle"><small>The top admin bar (toolbar) requires Dashicons to function properly.<br /><a target="_blank" href="https://developer.wordpress.org/resource/dashicons/">Read about Dashicons</a> | <a target="_blank" href="https://wordpress.org/support/article/toolbar/">Read about Toolbar</a></small></p>
            </th>
            <td>
                <label class="wpacu_switch">
                    <input id="wpacu_disable_dashicons_for_guests" type="checkbox"
					    <?php echo (($data['disable_dashicons_for_guests'] == 1) ? 'checked="checked"' : ''); ?>
                           name="<?php echo WPACU_PLUGIN_ID . '_global_unloads'; ?>[disable_dashicons_for_guests]"
                           value="1" /> <span class="wpacu_slider wpacu_round"></span> </label>
                &nbsp;
			    <?php echo sprintf(__('This will unload %s if the toolbar (top admin bar) is not showing up. This is be because the toolbar requires Dashicons to load properly, otherwise its layout will be broken.', 'wp-asset-clean-up'), 'Dashicons'); ?> -&gt; <em>/wp-includes/css/dashicons.min.css</em> (46 KB + the size of the actual font file loaded based on the browser)
                <p style="margin-top: 10px;"><?php _e('This is a CSS file that loads the official icon font of the WordPress admin as of 3.8. While needed for showing up the icons loaded within the top admin bar and in the styling of other plugins such as Query Monitor, it is sometimes loaded site-wide for guests (non logged-in users) when it is not needed.', 'wp-asset-clean-up'); ?></p>
            </td>
        </tr>

        <tr valign="top">
            <th scope="row">
                <label for="wpacu_disable_wp_block_library"><?php echo sprintf(__('Disable %s Site-Wide', 'wp-asset-clean-up'), 'Gutenberg CSS Block Library'); ?> <span style="color: #cc0000;" class="dashicons dashicons-wordpress-alt wordpress-core-file"><span class="wpacu-tooltip">WordPress Core File<br />Not sure if needed or not? In this case, it's better to leave it loaded to avoid breaking the website.</span></span></label>
            </th>
            <td>
                <label class="wpacu_switch">
                    <input id="wpacu_disable_wp_block_library" type="checkbox"
					    <?php echo (($data['disable_wp_block_library'] == 1) ? 'checked="checked"' : ''); ?>
                           name="<?php echo WPACU_PLUGIN_ID . '_global_unloads'; ?>[disable_wp_block_library]"
                           value="1" /> <span class="wpacu_slider wpacu_round"></span> </label>
                &nbsp;
			    <?php echo sprintf(__('This will unload %s', 'wp-asset-clean-up'), 'Gutenberg Blocks CSS file'); ?> -&gt; (<em>/wp-includes/css/dist/block-library/style.min.css</em>) (52 KB) - <span style="color: #0073aa; vertical-align: middle;" class="dashicons dashicons-info"></span> <a href="https://assetcleanup.com/docs/?p=713" target="_blank">Not sure if you need it or not? Use Coverage from Chrome DevTools!</a>
                <p style="margin-top: 10px;"><?php _e('If you\'re not using Gutenberg blocks in your posts/page (e.g. you prefer the Classic Editor), then you can unload this file site-wide to avoid an extra render-blocking external CSS file load.', 'wp-asset-clean-up'); ?></p>
                <?php
                if ($extraTip = \WpAssetCleanUp\Tips::ceGutenbergCssLibraryBlockTip()) {
	                echo '<p class="wpacu-warning" style="font-size: 100%;"><strong>Extra Tip:</strong> '.$extraTip.'</p>';
                }
                ?>
            </td>
        </tr>

	    <?php
	    global $wp_version;
	    $isjQueryMigrateUnloaded    = $wp_version >= 5.5;
	    $jqueryMigrateUnloadOpacity = $isjQueryMigrateUnloaded ? 0.65 : 1;
	    ?>
        <tr valign="top">
            <th scope="row" style="opacity: <?php echo $jqueryMigrateUnloadOpacity; ?>;">
                <label for="wpacu_disable_jquery_migrate"><?php echo sprintf(__('Disable %s Site-Wide', 'wp-asset-clean-up'), 'jQuery Migrate'); ?> <span style="color: #cc0000;" class="dashicons dashicons-wordpress-alt wordpress-core-file"><span class="wpacu-tooltip">WordPress Core File<br />Not sure if needed or not? In this case, it's better to leave it loaded to avoid breaking the website.</span></span></label>
            </th>
            <td>
                <?php if ($isjQueryMigrateUnloaded) { ?>
                    <div style="margin-bottom: 10px;" class="wpacu-warning">
                        <p style="margin-top: 0;"><span style="color: darkorange;" class="dashicons dashicons-warning"></span> Starting from WordPress 5.5, jQuery Migrate is no longer loaded, thus this option is no longer relevant for your website (which uses WordPress <?php echo $wp_version; ?>) as it acts as being always enabled. If you need to have jQuery Migrate loaded as it was before, please check the <strong><a rel="noopener noreferrer" target="_blank" href="https://wordpress.org/plugins/enable-jquery-migrate-helper/">Enable jQuery Migrate Helper</a></strong> plugin</p>
                    </div>
                <?php } ?>
                <label class="wpacu_switch" style="opacity: <?php echo $jqueryMigrateUnloadOpacity; ?>;">
                    <input id="wpacu_disable_jquery_migrate" type="checkbox"
						<?php echo (($data['disable_jquery_migrate'] == 1) ? 'checked="checked"' : ''); ?>
                           name="<?php echo WPACU_PLUGIN_ID . '_global_unloads'; ?>[disable_jquery_migrate]"
                           value="1" /> <span class="wpacu_slider wpacu_round"></span> </label>
                &nbsp;
                <span style="opacity: <?php echo $jqueryMigrateUnloadOpacity; ?>;"><?php echo sprintf(__('This will unload %s', 'wp-asset-clean-up'), 'jQuery Migrate'); ?> (<em>jquery-migrate(.min).js</em>)</span>
                <p style="margin-top: 10px; opacity: <?php echo $jqueryMigrateUnloadOpacity; ?>;"><?php _e('This is a JavaScript library that allows older jQuery code (up to version jQuery 1.9) to run on the latest version of jQuery avoiding incompatibility problems. Unless your website is using an old theme or has a jQuery plugin that was written a long time ago, this file is likely not needed to load. Consider disabling it to improve page loading time. Make sure to properly test the website.', 'wp-asset-clean-up'); ?></p>
            </td>
        </tr>

        <tr valign="top">
            <th scope="row">
                <label for="wpacu_disable_comment_reply"><?php echo sprintf(__('Disable %s Site-Wide', 'wp-asset-clean-up'), 'Comment Reply'); ?> <span style="color: #cc0000;" class="dashicons dashicons-wordpress-alt wordpress-core-file"><span class="wpacu-tooltip">WordPress Core File<br /><?php _e('Not sure if needed or not? In this case, it\'s better to leave it loaded to avoid breaking the website.', 'wp-asset-clean-up'); ?></span></span></label>
            </th>
            <td>
                <label class="wpacu_switch">
                    <input id="wpacu_disable_comment_reply" type="checkbox"
						<?php echo (($data['disable_comment_reply'] == 1) ? 'checked="checked"' : ''); ?>
                           name="<?php echo WPACU_PLUGIN_ID . '_global_unloads'; ?>[disable_comment_reply]"
                           value="1" /> <span class="wpacu_slider wpacu_round"></span> </label>
                &nbsp;
                <?php echo sprintf(__('This will unload %s', 'wp-asset-clean-up'), 'Comment Reply'); ?> (<em>/wp-includes/js/comment-reply(.min).js</em>)
                <p style="margin-top: 10px;"><?php _e('This is safe to unload if you\'re not using WordPress as a blog, do not want visitors to leave comments or you\'ve replaced the default WordPress comments with a comment platform such as Disqus or Facebook.', 'wp-asset-clean-up'); ?></p>
            </td>
        </tr>
    </table>
</div>