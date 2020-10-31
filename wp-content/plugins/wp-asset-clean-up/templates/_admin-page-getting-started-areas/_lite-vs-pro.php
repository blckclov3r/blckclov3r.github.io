<?php
/*
 * No direct access to this file
 */
if (! isset($data)) {
	exit;
}

$svgTick = <<<HTML
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 36 36"><path fill="#008000" d="M34.459 1.375c-1.391-.902-3.248-.506-4.149.884L13.5 28.17l-8.198-7.58c-1.217-1.125-3.114-1.051-4.239.166-1.125 1.216-1.051 3.115.166 4.239l10.764 9.952s.309.266.452.359c.504.328 1.07.484 1.63.484.982 0 1.945-.482 2.52-1.368L35.343 5.524c.902-1.39.506-3.248-.884-4.149z"/></svg>
HTML;

$svgNa = <<<HTML
<img draggable="false" class="wpacu-emoji" alt="❌" src="https://s.w.org/images/core/emoji/12.0.0-1/svg/274c.svg">
HTML;

?>
<div class="wpacu-lite-vs-pro-wrap">
	<table>
		<thead>
		<tr class="first">
			<th class="hide"></th>
			<th class="bg-lite">LITE</th>
			<th class="bg-pro">PRO</th>
		</tr>
		</thead>
		<tbody>
		<tr>
            <td><strong><?php _e('License Price', 'wp-asset-clean-up'); ?></strong> <small>* <?php echo sprintf(
                    __('after the first year, you will save %s off the initial purchase price, and be charged only %s', 'wp-asset-clean-up'),
                    '30%',
                    '$32<sup>.90</sup>'
                    );
            ?></small></td>
			<td><span class="txt-top">$</span><span class="txt-l">0</span></td>
			<td><span class="txt-top">$</span><span class="txt-l">47</span></td>
		</tr>
		<tr>
			<td><?php _e('Manage CSS &amp; JavaScript files on Homepage, Posts, Pages &amp; Custom Post Types (e.g. WooCommerce product pages, Easy Digital Downloads download items)', 'wp-asset-clean-up'); ?></td>
			<td><?php echo $svgTick; ?></td>
			<td><?php echo $svgTick; ?></td>
		</tr>
		<tr>
			<td><?php _e('Bulk Unloads: Everywhere (Site-Wide), On Specific Pages &amp; Post Types, Add load exceptions', 'wp-asset-clean-up'); ?></td>
			<td><?php echo $svgTick; ?></td>
			<td><?php echo $svgTick; ?></td>
		</tr>
		<tr>
			<td><?php _e('Manage the CSS &amp; JavaScript files within the Dashboard (default) and Front-end view (bottom of the page) if chosen', 'wp-asset-clean-up'); ?></td>
			<td><?php echo $svgTick; ?></td>
			<td><?php echo $svgTick; ?></td>
		</tr>
		<tr>
			<td><?php _e('Enable "Test Mode" to only apply the plugin\'s changes for the logged-in administrator for debugging purposes', 'wp-asset-clean-up'); ?></td>
			<td><?php echo $svgTick; ?></td>
			<td><?php echo $svgTick; ?></td>
		</tr>
		<tr>
			<td><?php _e('Minify remaining loaded CSS &amp; JavaScript files (with option to add exceptions)', 'wp-asset-clean-up'); ?></td>
			<td><?php echo $svgTick; ?></td>
			<td><?php echo $svgTick; ?></td>
		</tr>
		<tr>
			<td><?php _e('Combine remaining loaded CSS &amp; JavaScript files into fewer files from each <code>&lt;HEAD&gt;</code> and <code>&lt;BODY&gt;</code> location (with option to add exceptions)', 'wp-asset-clean-up'); ?></td>
			<td><?php echo $svgTick; ?></td>
			<td><?php echo $svgTick; ?></td>
		</tr>
		<tr>
			<td><?php echo sprintf(__('Remove unused elements from the <code>&lt;HEAD&gt;</code> and <code>&lt;BODY&gt;</code> including the following link tags: %s.', 'wp-asset-clean-up'), 'Really Simple Discovery (RSD), Windows Live Writer, REST API, Posts/Pages Shortlink, Post\'s Relational, WordPress Generators (also good for security), RSS Feed Links'); ?> <?php _e('Valid HTML comments are also stripped (exceptions can be added) while conditional Internet Explorer comments are preserved.', 'wp-asset-clean-up'); ?></td>
			<td><?php echo $svgTick; ?></td>
			<td><?php echo $svgTick; ?></td>
		</tr>
		<tr>
			<td><?php _e('Site-Wide Unload For Common Elements that are often unused such as: WordPress Emojis, jQuery Migrate, Comment Reply (if not using WP as a blog)', 'wp-asset-clean-up'); ?></td>
			<td><?php echo $svgTick; ?></td>
			<td><?php echo $svgTick; ?></td>
		</tr>
		<tr>
			<td><?php _e('Disable XML-RPC Protocol Support partially or completely', 'wp-asset-clean-up'); ?></td>
			<td><?php echo $svgTick; ?></td>
			<td><?php echo $svgTick; ?></td>
		</tr>
        <tr>
            <td><?php _e('Inline Chosen CSS Files', 'wp-asset-clean-up'); ?> * <a target="_blank" href="https://gtmetrix.com/inline-small-css.html"><small>Read more</small></a></td>
            <td><?php echo $svgTick; ?></td>
            <td><?php echo $svgTick; ?></td>
        </tr>
        <tr>
            <td><?php _e('Inline Chosen JavaScript Files', 'wp-asset-clean-up'); ?> * <a target="_blank" href="https://gtmetrix.com/inline-small-javascript.html"><small>Read more</small></a></td>
            <td><span class="na">❌</span></td>
            <td><?php echo $svgTick; ?></td>
        </tr>
		<tr>
			<td><?php _e('Unload CSS/JS for URLs with request URI matching certain RegEx(es) &amp; add load exceptions based on Regex(es) <small>e.g. you can unload a CSS file site-wide, but keep it loaded if the page URL matches the <code>#/product/#</code> RegEx</small>', 'wp-asset-clean-up'); ?></td>
			<td><span class="na">❌</span></td>
			<td><?php echo $svgTick; ?></td>
		</tr>
		<tr>
			<td><?php _e('Unload plugins site-wide and via RegEx(es)', 'wp-asset-clean-up'); ?> (go to the next level and unload whole plugins, not just the CSS/JS files loaded through them)</td>
			<td><span class="na">❌</span></td>
			<td><?php echo $svgTick; ?></td>
		</tr>
        <tr>
            <td><?php _e('Defer CSS loaded in the <code>&lt;BODY&gt;</code>', 'wp-asset-clean-up'); ?> to reduce render-blocking resources</td>
            <td><span class="na">❌</span></td>
            <td><?php echo $svgTick; ?></td>
        </tr>

		<tr>
			<td><?php _e('Manage CSS &amp; JavaScript files on Categories, Tags, Custom Taxonomy pages, Date &amp; Author Archive Pages, Search Results &amp; 404 Not Found pages', 'wp-asset-clean-up'); ?></td>
			<td><span class="na">❌</span></td>
			<td><?php echo $svgTick; ?></td>
		</tr>
		<tr>
			<td><?php _e('Manage hardcoded (non-enqueued) CSS &amp; JavaScript files', 'wp-asset-clean-up'); ?></td>
			<td><span class="na">❌</span></td>
			<td><?php echo $svgTick; ?></td>
		</tr>
		<tr>
			<td><?php _e('Move CSS &amp; JavaScript files from <code>&lt;HEAD&gt;</code> to <code>&lt;BODY&gt;</code> (to reduce render-blocking) or vice-versa (for very early triggering)', 'wp-asset-clean-up'); ?></td>
			<td><span class="na">❌</span></td>
			<td><?php echo $svgTick; ?></td>
		</tr>
		<tr>
			<td><?php _e('Apply "async" and "defer" attributes to loaded JavaScript files', 'wp-asset-clean-up'); ?></td>
			<td><span class="na">❌</span></td>
			<td><?php echo $svgTick; ?></td>
		</tr>
        <tr>
            <td><?php _e('Priority in releasing new features &amp; other improvements (updates that are meant for both Lite and Pro plugins are first released to the Pro users)', 'wp-asset-clean-up'); ?></td>
            <td><span class="na">❌</span></td>
            <td><?php echo $svgTick; ?></td>
        </tr>
		<tr>
			<td><?php _e('Priority Customer Support', 'wp-asset-clean-up'); ?></td>
			<td><span class="na">❌</span></td>
			<td><?php echo $svgTick; ?></td>
		</tr>
		<tr>
			<td colspan="3" style="text-align: center; padding: 10px;">
				<h3 style="margin: 0;"><?php echo $svgTick; ?>&nbsp; <em>30 Day Money Back Guarantee</em></h3>
				<p style="margin-top: 8px;">If you’re not satisfied with your purchase for any reason, you can request a refund within 30 days and you will get the payment refunded.</p>
				<hr />
				<a class="button button-primary button-hero" href="<?php echo WPACU_PLUGIN_GO_PRO_URL; ?>?utm_source=plugin_getting_started&utm_medium=lite_vs_pro">
					<span class="dashicons dashicons-star-filled" style="line-height: 50px;"></span> &nbsp;<?php _e('Upgrade to Pro to unlock all benefits', 'wp-asset-clean-up'); ?></a>
				&nbsp;
				<a class="button button-default button-hero" href="https://www.gabelivan.com/contact/">
					<span class="dashicons dashicons-admin-comments" style="line-height: 50px;"></span> &nbsp;<?php _e('I have some questions', 'wp-asset-clean-up'); ?></a>
			</td>
		</tr>
		</tbody>
	</table>
</div>