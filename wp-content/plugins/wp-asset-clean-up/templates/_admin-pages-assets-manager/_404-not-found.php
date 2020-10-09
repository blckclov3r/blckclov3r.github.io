<?php
/*
 * No direct access to this file
 */
if (! isset($data)) {
	exit;
}

$baseNamePageType = str_replace('.php', '', basename(__FILE__));
$baseNamePageType = trim($baseNamePageType, '_');

$lockedForPro = str_replace('[wpacu_chosen_page_type]', $baseNamePageType, $data['locked_for_pro']);
?>
<div style="margin: 25px 0 0;">
    <p><?php echo $lockedForPro; ?></p>
    <hr />

    <p>This page (404.php within the theme) is reached when a request is not valid. It could be an old link that is not used anymore or the visitor typed the wrong URL to an article etc. (e.g. https://yourwebsite.com/this-is-a-non-existent-page.html). The assets can be unloaded <strong>only in the front-end view</strong> (<em>"Manage in the Front-end?" from "Settings" tab has to be enabled</em>). &#10230; <a target="_blank" href="https://codex.wordpress.org/Creating_an_Error_404_Page"><?php _e('read more', 'wp-asset-clean-up'); ?></a></p>
    <p><strong>Example:</strong> <code>//www.yoursite.comn/blog/a-post-title-that-does-not-exist/</code></p>
    <hr />

    <strong>How to retrieve the loaded styles &amp; scripts?</strong>

    <p style="margin-bottom: 0;"><span class="dashicons dashicons-yes"></span> If "Manage in the Front-end?" (<em>from "Settings" -&gt; "Plugin Usage Preferences"</em>) is enabled and you're logged in:</p>
    <p style="margin-top: 0;">Go to any page that returns a 404 error (it does not watter which URL you will have as the unload rules will apply to all 404 pages) and scroll to the bottom of the page where you will see the list.</p>
</div>
