<?php
/*
 * No direct access to this file
 */
if (! isset($data)) {
	exit;
}
?>
<div style="margin: 25px 0 0;">
    <p>Post Type: 'attachment' (e.g. files from <a target="_blank" href="https://wordpress.org/support/article/media-library-screen/">"Media" &#187; "Library"</a>, the page loaded usually prints the image or other media type) &#10230; <a target="_blank" href="https://wordpress.org/support/article/edit-media/"><?php _e('read more', 'wp-asset-clean-up'); ?></a></p>

    <p>Note: This is rarely used/needed and in some WordPress setups, the attachment's permalink redirects to the media file itself.</p>

    <strong>How to retrieve the loaded styles &amp; scripts?</strong>

    <p style="margin-bottom: 0;"><span class="dashicons dashicons-yes"></span> If "Manage in the Dashboard?" (<em>from "Settings" -&gt; "Plugin Usage Preferences"</em>) is enabled:</p>
    <p style="margin-top: 0;">Go to "Media" -&gt; "Library" -&gt; [Choose the media you want to manage the assets for] -&gt; Scroll to "Asset CleanUp" meta box where you will see the loaded CSS &amp; JavaScript files</p>
    <hr />
    <p style="margin-bottom: 0;"><span class="dashicons dashicons-yes"></span> If "Manage in the Front-end?" (<em>from "Settings" -&gt; "Plugin Usage Preferences"</em>) is enabled and you're logged in:</p>
    <p style="margin-top: 0;">Go to the media's permalink ("View" links in the media list) page where you want to manage the files and scroll to the bottom of the page where you will see the list.</p>
</div>