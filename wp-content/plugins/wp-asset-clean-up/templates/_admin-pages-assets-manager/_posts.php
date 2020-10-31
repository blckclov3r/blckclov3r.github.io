<?php
/*
 * No direct access to this file
 */
if (! isset($data)) {
	exit;
}
?>
<div style="margin: 25px 0 0;">
	<?php
	$anyMetaBoxHidden = $data['wpacu_settings']['hide_assets_meta_box'] || $data['wpacu_settings']['hide_options_meta_box'];

	if ($anyMetaBoxHidden) {
		?>
        <div class="wpacu-warning" style="width: 95%; margin: -10px 0 15px; padding: 10px; font-size: inherit;">
            <strong><span class="dashicons dashicons-warning" style="color: orange;"></span> Important Reminder:</strong> The following meta boxes were marked to be hidden in plugin's "Settings" &#187; "Plugin Usage Preferences":
            <ul style="margin-bottom: 0; list-style: circle; padding-left: 25px;">
				<?php if ($data['wpacu_settings']['hide_assets_meta_box']) { ?>
                    <li><strong><?php echo WPACU_PLUGIN_TITLE; ?>: CSS &amp; JavaScript Manager</strong> * <em>to see the CSS/JS list for any <strong>Post</strong>, you need to make the meta box visible again</em></li>
				<?php } ?>

				<?php if ($data['wpacu_settings']['hide_options_meta_box']) { ?>
                    <li><strong><?php echo WPACU_PLUGIN_TITLE; ?>: Options</strong> * <em>to prevent minify/combine/unload settings per page, you need to make the meta box visible again</em></li>
				<?php } ?>
            </ul>
        </div>
		<?php
	}
	?>
    <p>Post Type: 'post' (e.g. blog entries) &#10230; <a target="_blank" href="https://wordpress.org/support/article/writing-posts/"><?php _e('read more', 'wp-asset-clean-up'); ?></a></p>

    <strong>How to retrieve the loaded styles &amp; scripts?</strong>

    <p style="margin-bottom: 0;"><span class="dashicons dashicons-yes"></span> If "Manage in the Dashboard?" (<em>from "Settings" -&gt; "Plugin Usage Preferences"</em>) is enabled:</p>
    <p style="margin-top: 0;">Go to "Posts" -&gt; "All Posts" -&gt; [Choose the page you want to manage the assets for] -&gt; Scroll to "Asset CleanUp" meta box where you will see the loaded CSS &amp; JavaScript files</p>

    <hr />

    <p style="margin-bottom: 0;"><span class="dashicons dashicons-yes"></span> If "Manage in the Front-end?" (<em>from "Settings" -&gt; "Plugin Usage Preferences"</em>) is enabled and you're logged in:</p>
    <p style="margin-top: 0;">Go to the page where you want to manage the files and scroll to the bottom of the page where you will see the list.</p>
</div>