<?php
/*
 * No direct access to this file
 */
if (! isset($data)) {
	exit;
}
?>
<p>For the <em>homepage</em>, you can go to "CSS/JS Load Manager" -&gt; "<a href="<?php echo admin_url('admin.php?page='.WPACU_PLUGIN_ID.'_assets_manager') ?>">Homepage</a>" from the plugin's menu and you will notice the list of all the styles &amp;scripts files that are loading there. For each file, you will see options that you can enabled/disable.</p>
<p>For <em>posts, pages &amp; custom post types</em>, you can edit the page within the Dashboard or via the front-end view (if you enabled the option in "Settings") and scroll to "<?php echo WPACU_PLUGIN_TITLE; ?>: CSS &amp; JavaScript Manager" metabox area (if not hidden from "Settings" -&gt; "Plugin Usage Preferences") where you can manage all the CSS &amp; JS files loading on that post/page.</p>
<p>To view all the WordPress pages where <?php echo WPACU_PLUGIN_TITLE; ?> can do optimization for, go to "<a href="<?php echo admin_url('admin.php?page='.WPACU_PLUGIN_ID.'_assets_manager&wpacu_for=homepage'); ?>"><?php _e('CSS/JS Load Manager', 'wp-asset-clean-up'); ?></a>".</p>
<hr />

<p style="font-size: 16px;"><strong>Common Example: "Contact Form 7" plugin</strong></p>
<p>At the time of writing this (January 1, 2019), the plugin loads 2 files everywhere (site-wide), when most of the WordPress websites only use them in the contact page. These files are:</p>
<ul style="list-style: disc; margin-left: 25px;">
	<li><em>/wp-content/plugins/contact-form-7/includes/css/styles.css?ver=5.1.1</em> (Stylesheet File)</li>
	<li><em>/wp-content/plugins/contact-form-7/includes/js/scripts.js?ver=5.1.1</em> (JavaScript File)</li>
</ul>

<p>Moreover, the JavaScript file has an inline code associated with it, which looks something like this:</p>

<pre><code>&lt;script type=&#39;text/javascript&#39;&gt;
/* &lt;![CDATA[ */
var wpcf7 = {&quot;apiSettings&quot;:{&quot;root&quot;:&quot;https:\/\/www.yourdomain.com\/wp-json\/contact-form-7\/v1&quot;,&quot;namespace&quot;:&quot;contact-form-7\/v1&quot;},&quot;cached&quot;:&quot;1&quot;};
/* ]]&gt; */
&lt;/script&gt;</code></pre>

<p style="margin-top: 0;">These extra files loading, as well as the HTML code used to call them, not to mention the inline code associated with the JS file, add up to the total size of the page: the number of HTTP requests and the HTML source code size (this is a minor thing, but when dealing with tens of files, it adds up).</p>

<p>Just like "Contact Form 7", there are plenty of other files that are loading from plugins and the active theme which shouldn't be loaded in many pages. Think about pages that have mostly text such as "Terms and Conditions", "Privacy Policy" or the "404 (Not Found)" page. These ones can be stripped by a lot of "crap" which will boost the speed score and offer a better visitor experience.</p>

<p>Once you unload the right (the ones you know are not useful) files and test everything (via "Test Mode" to make sure your visitors will not be affected in case you break any page functionality), you can clear the cache if you're using a caching plugin and test your page speed score in GTMetrix or other similar tool that measures the page load. You will see an improvement. <span style="vertical-align: bottom; font-size: 20px; line-height: 20px;"><img draggable="false" class="wpacu-emoji" alt="😀" src="https://s.w.org/images/core/emoji/12.0.0-1/svg/1f600.svg" /></span></p>