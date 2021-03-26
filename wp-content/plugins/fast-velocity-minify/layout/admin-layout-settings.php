<?php if( $active_tab == 'settings' ) { ?>
<div class="fvm-wrapper">

<form method="post" id="fvm-save-changes">
			
<?php
	# nounce
	wp_nonce_field('fvm_settings_nonce', 'fvm_settings_nonce');
?>

<h2 class="title"><?php _e( 'Global Settings', 'fast-velocity-minify' ); ?></h2>
<h3 class="fvm-bold-green"><?php _e( 'Settings that affect the plugin and are not specific to speed optimization.', 'fast-velocity-minify' ); ?></h3>

<table class="form-table fvm-settings">
<tbody>

<tr>
<th scope="row"><?php _e( 'Global Options', 'fast-velocity-minify' ); ?></th>
<td>
<p class="fvm-bold-green fvm-rowintro"><?php _e( 'Recommended Settings', 'fast-velocity-minify' ); ?></p>

<fieldset>

<label for="fvm_settings_global_preserve_settings">
<input name="fvm_settings[global][preserve_settings]" type="checkbox" id="fvm_settings_global_preserve_settings" value="1" <?php echo fvm_get_settings_checkbox(fvm_get_settings_value($fvm_settings, 'global', 'preserve_settings')); ?>>
<?php _e( 'Preserve settings on uninstall', 'fast-velocity-minify' ); ?> <span class="note-info">[ <?php _e( 'If selected, all FVM settings will be preserved when you uninstall or delete the plugin.', 'fast-velocity-minify' ); ?> ]</span></label>
<br />

<label for="fvm_settings_global_force-ssl">
<input name="fvm_settings[global][force-ssl]" type="checkbox" id="fvm_settings_global_force-ssl" value="1" <?php echo fvm_get_settings_checkbox(fvm_get_settings_value($fvm_settings, 'global', 'force-ssl')); ?>>
<?php _e( 'Force HTTPS urls on merged files', 'fast-velocity-minify' ); ?> <span class="note-info">[ <?php _e( 'Recommended if you have SSL but still allow http access to the site.', 'fast-velocity-minify' ); ?> ]</span></label>
<br />

</fieldset></td>
</tr>

<tr>
<th scope="row"><?php _e( 'Advanced Global Options', 'fast-velocity-minify' ); ?></th>
<td>
<p class="fvm-bold-green fvm-rowintro"><?php _e( 'Handle with Care', 'fast-velocity-minify' ); ?></p>

<fieldset>
<label for="fvm_settings_cache_min_instant_purge">
<input name="fvm_settings[cache][min_instant_purge]" type="checkbox" id="fvm_settings_cache_min_instant_purge" value="1" <?php echo fvm_get_settings_checkbox(fvm_get_settings_value($fvm_settings, 'cache', 'min_instant_purge')); ?>>
<?php _e( 'Purge Minified CSS/JS files instantly', 'fast-velocity-minify' ); ?> <span class="note-info">[ <?php _e( 'Cache files can take up to 24 hours to be deleted by default, for compatibility reasons with certain hosts.', 'fast-velocity-minify' ); ?> ]</span></label>
<br />

</fieldset></td>
</tr>

</tbody>
</table>


<h2 class="title"><?php _e( 'HTML Settings', 'fast-velocity-minify' ); ?></h2>
<h3 class="fvm-bold-green"><?php _e( 'Optimize your HTML and remove some clutter from the HTML page.', 'fast-velocity-minify' ); ?></h3>

<table class="form-table fvm-settings">
<tbody>

<tr>
<th scope="row"><?php _e( 'HTML Options', 'fast-velocity-minify' ); ?></th>
<td>
<p class="fvm-bold-green fvm-rowintro"><?php _e( 'Recommended Settings', 'fast-velocity-minify' ); ?></p>

<fieldset>
<label for="fvm_settings_html_enable">
<input name="fvm_settings[html][enable]" type="checkbox" id="fvm_settings_html_enable" value="1" <?php echo fvm_get_settings_checkbox(fvm_get_settings_value($fvm_settings, 'html', 'enable')); ?>>
<?php _e( 'Enable HTML Processing', 'fast-velocity-minify' ); ?> <span class="note-info">[ <?php _e( 'Will enable processing for the settings below', 'fast-velocity-minify' ); ?> ]</span></label>
<br />

<label for="fvm_settings_html_nocomments">
<input name="fvm_settings[html][nocomments]" type="checkbox" id="fvm_settings_html_nocomments" value="1" <?php echo fvm_get_settings_checkbox(fvm_get_settings_value($fvm_settings, 'html', 'nocomments')); ?>>
<?php _e( 'Strip HTML Comments', 'fast-velocity-minify' ); ?> <span class="note-info">[ <?php _e( 'Will strip HTML comments from your HTML page', 'fast-velocity-minify' ); ?> ]</span></label>
<br />

<label for="fvm_settings_html_cleanup_header">
<input name="fvm_settings[html][cleanup_header]" type="checkbox" id="fvm_settings_html_cleanup_header" value="1" <?php echo fvm_get_settings_checkbox(fvm_get_settings_value($fvm_settings, 'html', 'cleanup_header')); ?>>
<?php _e( 'Cleanup Header', 'fast-velocity-minify' ); ?> <span class="note-info">[ <?php _e( 'Removes resource hints, generator tag, shortlinks, manifest link, etc', 'fast-velocity-minify' ); ?> ]</span></label>
<br />

<label for="fvm_settings_html_disable_emojis">
<input name="fvm_settings[html][disable_emojis]" type="checkbox" id="fvm_settings_html_disable_emojis" value="1" <?php echo fvm_get_settings_checkbox(fvm_get_settings_value($fvm_settings, 'html', 'disable_emojis')); ?>>
<?php _e( 'Remove Emoji', 'fast-velocity-minify' ); ?> <span class="note-info">[ <?php _e( 'Removes the default emoji scripts and styles that come with WordPress', 'fast-velocity-minify' ); ?> ]</span></label>
<br />

</fieldset></td>
</tr>

<tr>
<th scope="row"><?php _e( 'Advanced HTML Options', 'fast-velocity-minify' ); ?></th>
<td>
<p class="fvm-bold-green fvm-rowintro"><?php _e( 'Handle with Care', 'fast-velocity-minify' ); ?></p>

<fieldset>
<label for="fvm_settings_html_min_disable">
<input name="fvm_settings[html][min_disable]" type="checkbox" id="fvm_settings_html_min_disable" value="1" <?php echo fvm_get_settings_checkbox(fvm_get_settings_value($fvm_settings, 'html', 'min_disable')); ?>>
<?php _e( 'Disable HTML Minification', 'fast-velocity-minify' ); ?> <span class="note-info">[ <?php _e( 'Will disable HTML minification for testing purposes', 'fast-velocity-minify' ); ?> ]</span></label>
<br />

</fieldset></td>
</tr>

</tbody>
</table>

<div style="height: 60px;"></div>
<h2 class="title"><?php _e( 'CSS Settings', 'fast-velocity-minify' ); ?></h2>
<h3 class="fvm-bold-green"><?php _e( 'Optimize your CSS and Styles settings.', 'fast-velocity-minify' ); ?></h3>

<table class="form-table fvm-settings">
<tbody>

<tr>
<th scope="row"><?php _e( 'CSS Options', 'fast-velocity-minify' ); ?></th>
<td>
<p class="fvm-bold-green fvm-rowintro"><?php _e( 'Recommended Settings', 'fast-velocity-minify' ); ?></p>

<fieldset>
<label for="fvm_settings_css_enable">
<input name="fvm_settings[css][enable]" type="checkbox" id="fvm_settings_css_enable" value="1" <?php echo fvm_get_settings_checkbox(fvm_get_settings_value($fvm_settings, 'css', 'enable')); ?>>
<?php _e( 'Enable CSS Processing', 'fast-velocity-minify' ); ?> <span class="note-info">[ <?php _e( 'Will enable processing for the settings below', 'fast-velocity-minify' ); ?> ]</span></label>
<br />

<label for="fvm_settings_css_fonts">
<input name="fvm_settings[css][fonts]" type="checkbox" id="fvm_settings_css_fonts" value="1" <?php echo fvm_get_settings_checkbox(fvm_get_settings_value($fvm_settings, 'css', 'fonts')); ?>>
<?php _e( 'Merge Fonts and Icons separately', 'fast-velocity-minify' ); ?><span class="note-info">[ <?php _e( 'Will merge fonts and icons into a separate CSS file', 'fast-velocity-minify' ); ?> ]</span></label>
<br />

<label for="fvm_settings_css_noprint">
<input name="fvm_settings[css][noprint]" type="checkbox" id="fvm_settings_css_noprint" value="1" <?php echo fvm_get_settings_checkbox(fvm_get_settings_value($fvm_settings, 'css', 'noprint')); ?>>
<?php _e( 'Remove "Print" CSS files', 'fast-velocity-minify' ); ?> <span class="note-info">[ <?php _e( 'Will remove CSS files of mediatype "print" from the frontend', 'fast-velocity-minify' ); ?> ]</span></label>
<br />

</fieldset></td>
</tr>

<tr>
<th scope="row"><?php _e( 'Advanced CSS Options', 'fast-velocity-minify' ); ?></th>
<td>
<p class="fvm-bold-green fvm-rowintro"><?php _e( 'Handle with Care', 'fast-velocity-minify' ); ?></p>

<fieldset>

<label for="fvm_settings_css_inline-all">
<input name="fvm_settings[css][inline-all]" type="checkbox" id="fvm_settings_css_inline-all" value="1" <?php echo fvm_get_settings_checkbox(fvm_get_settings_value($fvm_settings, 'css', 'inline-all')); ?>>
<?php _e( 'Disable Merging and Inline all CSS', 'fast-velocity-minify' ); ?> <span class="note-info">[ <?php _e( 'It will inline all CSS files, instead of merging CSS into an external file (not recommended)', 'fast-velocity-minify' ); ?> ]</span></label>
<br />

<label for="fvm_settings_css_min_disable">
<input name="fvm_settings[css][min_disable]" type="checkbox" id="fvm_settings_css_min_disable" value="1" <?php echo fvm_get_settings_checkbox(fvm_get_settings_value($fvm_settings, 'css', 'min_disable')); ?>>
<?php _e( 'Disable CSS Minification', 'fast-velocity-minify' ); ?> <span class="note-info">[ <?php _e( 'Will allow merging but without CSS minification for testing purposes', 'fast-velocity-minify' ); ?> ]</span></label>
<br />

<label for="fvm_settings_css_inline-all">
<input name="fvm_settings[css][nopreload]" type="checkbox" id="fvm_settings_css_nopreload" value="1" <?php echo fvm_get_settings_checkbox(fvm_get_settings_value($fvm_settings, 'css', 'nopreload')); ?>>
<?php _e( 'Disable CSS link preload', 'fast-velocity-minify' ); ?> <span class="note-info">[ <?php _e( 'Will remove the CSS link preload from the header (not recommended)', 'fast-velocity-minify' ); ?> ]</span></label>
<br />

<label for="fvm_settings_css_async">
<input name="fvm_settings[css][async]" type="checkbox" id="fvm_settings_css_async" value="1" <?php echo fvm_get_settings_checkbox(fvm_get_settings_value($fvm_settings, 'css', 'async')); ?>>
<?php _e( 'Load generated CSS files Async', 'fast-velocity-minify' ); ?> <span class="note-info">[ <?php _e( 'Use your own critical path code like <code>&lt;style id=&quot;critical-path&quot;&gt; your code &lt;/style&gt;</code>', 'fast-velocity-minify' ); ?> ]</span></label>
<br />

</fieldset></td>
</tr>

<tr>
<th scope="row"><?php _e( 'Ignore CSS files', 'fast-velocity-minify' ); ?></th>
<td><fieldset>
<label for="fvm_settings_css_ignore"><span class="fvm-bold-green fvm-rowintro"><?php _e( "Ignore the following CSS URL's", 'fast-velocity-minify' ); ?></span></label>
<p><textarea name="fvm_settings[css][ignore]" rows="7" cols="50" id="fvm_settings_css_ignore" class="large-text code" placeholder="ex: /plugins/something/assets/problem.css"><?php echo fvm_get_settings_value($fvm_settings, 'css', 'ignore'); ?></textarea></p>
<p class="description">[ <?php _e( 'CSS files are merged and grouped automatically by mediatype, hence you have an option to exclude files.', 'fast-velocity-minify' ); ?> ]</p>
<p class="description">[ <?php _e( 'Will match using <code>PHP stripos</code> against the <code>href attribute</code> on the <code>link tag</code>', 'fast-velocity-minify' ); ?> ]</p>
</fieldset></td>
</tr>

<tr>
<th scope="row"><?php _e( 'Remove CSS Files', 'fast-velocity-minify' ); ?></th>
<td><fieldset>
<label for="fvm_settings_css_remove"><span class="fvm-bold-green fvm-rowintro"><?php _e( 'Remove the following CSS files', 'fast-velocity-minify' ); ?></span></label>
<p><textarea name="fvm_settings[css][remove]" rows="7" cols="50" id="fvm_settings_css_remove" class="large-text code" placeholder="ex: fonts.googleapis.com"><?php echo fvm_get_settings_value($fvm_settings, 'css', 'remove'); ?></textarea></p>
<p class="description">[ <?php _e( 'This will allow you to remove unwanted CSS files by URL path from the frontend', 'fast-velocity-minify' ); ?> ]</p>
<p class="description">[ <?php _e( 'Will match using <code>PHP stripos</code> against the <code>href attribute</code> on the <code>link tag</code>', 'fast-velocity-minify' ); ?> ]</p>
</fieldset></td>
</tr>


</tbody>
</table>



<div style="height: 60px;"></div>
<h2 class="title"><?php _e( 'JS Settings', 'fast-velocity-minify' ); ?></h2>
<h3 class="fvm-bold-green"><?php _e( 'In this section, you can optimize your JS files and inline scripts', 'fast-velocity-minify' ); ?></h3>

<table class="form-table fvm-settings">
<tbody>

<tr>
<th scope="row"><?php _e( 'JS Options', 'fast-velocity-minify' ); ?></th>
<td>
<p class="fvm-bold-green fvm-rowintro"><?php _e( 'Recommended Settings', 'fast-velocity-minify' ); ?></p>

<fieldset>
<label for="fvm_settings_js_enable">
<input name="fvm_settings[js][enable]" type="checkbox" id="fvm_settings_js_enable" value="1" <?php echo fvm_get_settings_checkbox(fvm_get_settings_value($fvm_settings, 'js', 'enable')); ?>>
<?php _e( 'Enable JS Processing', 'fast-velocity-minify' ); ?> <span class="note-info">[ <?php _e( 'Will enable processing for the settings below', 'fast-velocity-minify' ); ?> ]</span></label>
<br />

</fieldset></td>
</tr>

<tr>
<th scope="row"><?php _e( 'Advanced JS Options', 'fast-velocity-minify' ); ?></th>
<td>
<p class="fvm-bold-green fvm-rowintro"><?php _e( 'Handle with Care', 'fast-velocity-minify' ); ?></p>

<fieldset>
<label for="fvm_settings_js_min_disable">
<input name="fvm_settings[js][min_disable]" type="checkbox" id="fvm_settings_js_min_disable" value="1" <?php echo fvm_get_settings_checkbox(fvm_get_settings_value($fvm_settings, 'js', 'min_disable')); ?>>
<?php _e( 'Disable JS Minification', 'fast-velocity-minify' ); ?> <span class="note-info">[ <?php _e( 'Will disable JS minification (merge only) for testing purposes', 'fast-velocity-minify' ); ?> ]</span></label>
<br />

<label for="fvm_settings_js_inline-all">
<input name="fvm_settings[js][nopreload]" type="checkbox" id="fvm_settings_js_nopreload" value="1" <?php echo fvm_get_settings_checkbox(fvm_get_settings_value($fvm_settings, 'js', 'nopreload')); ?>>
<?php _e( 'Disable JS link preload', 'fast-velocity-minify' ); ?> <span class="note-info">[ <?php _e( 'Will remove the JS link preload from the header (not recommended)', 'fast-velocity-minify' ); ?> ]</span></label>
<br />

<label for="fvm_settings_js_jqupgrade">
<input name="fvm_settings[js][jqupgrade]" type="checkbox" id="fvm_settings_js_jqupgrade" value="1" <?php echo fvm_get_settings_checkbox(fvm_get_settings_value($fvm_settings, 'js', 'jqupgrade')); ?>>
<?php _e( 'Upgrade to jQuery 3', 'fast-velocity-minify' ); ?> <span class="note-info">[<?php _e( 'Will use jQuery 3.5.1 and jQuery Migrate 3.3.1 from Cloudflare (if enqueued)', 'fast-velocity-minify' ); ?>  ]</span></label>
<br />

</fieldset></td>
</tr>


<tr>
<th scope="row"><?php _e( 'Ignore Script Files', 'fast-velocity-minify' ); ?></th>
<td><fieldset>
<label for="fvm_settings_js_ignore"><span class="fvm-bold-green fvm-rowintro"><?php _e( 'Will prevent merging and minification for these files, regardless of any other broader rules in this page.', 'fast-velocity-minify' ); ?></span></label>
<p><textarea name="fvm_settings[js][ignore]" rows="7" cols="50" id="fvm_settings_js_ignore" class="large-text code" placeholder="<?php _e( '--- ex: /plugins/something/assets/problem.js ---', 'fast-velocity-minify' ); ?>"><?php echo fvm_get_settings_value($fvm_settings, 'js', 'ignore'); ?></textarea></p>
<p class="description">[ <?php _e( 'Will match using <code>PHP stripos</code> against the script <code>src</code> attribute', 'fast-velocity-minify' ); ?> ]</p>
<p class="description">[ <?php _e( 'It is highly recommended to try to leave this empty and later be more specific on what to merge', 'fast-velocity-minify' ); ?> ]</p>
</fieldset></td>
</tr>

<tr>
<th scope="row"><?php _e( 'Merge render blocking JS files in the header', 'fast-velocity-minify' ); ?></th>
<td><fieldset>
<label for="fvm_settings_merge_header"><span class="fvm-bold-green fvm-rowintro"><?php _e( 'This will merge and render block all JS files that match the paths below', 'fast-velocity-minify' ); ?></span></label>
<p><textarea name="fvm_settings[js][merge_header]" rows="7" cols="50" id="fvm_settings_js_merge_header" class="large-text code" placeholder="<?php _e( '--- suggested ---', 'fast-velocity-minify' ); ?> 

/jquery-migrate.js 
/jquery.js 
/jquery.min.js"><?php echo fvm_get_settings_value($fvm_settings, 'js', 'merge_header'); ?></textarea></p>
<p class="description">[ <?php _e( 'One possible match per line, after minification and processing, as seen on the frontend.', 'fast-velocity-minify' ); ?> ]</p>
<p class="description">[ <?php _e( 'Will match using <code>PHP stripos</code> against the script <code>src attribute</code>', 'fast-velocity-minify' ); ?> ]</p>
</fieldset></td>
</tr>

<tr>
<th scope="row"><?php _e( 'Merge and Defer Scripts', 'fast-velocity-minify' ); ?></th>
<td><fieldset>
<label for="fvm_settings_merge_defer"><span class="fvm-bold-green fvm-rowintro"><?php _e( 'This will merge and defer all JS files that match the paths below', 'fast-velocity-minify' ); ?></span></label>
<p><textarea name="fvm_settings[js][merge_defer]" rows="7" cols="50" id="fvm_settings_js_merge_defer" class="large-text code" placeholder="<?php _e( '--- example ---', 'fast-velocity-minify' ); ?>

/wp-admin/ 
/wp-includes/ 
/wp-content/"><?php echo fvm_get_settings_value($fvm_settings, 'js', 'merge_defer'); ?></textarea></p>
<p class="description">[ <?php _e( 'One possible match per line, after minification and processing, as seen on the frontend.', 'fast-velocity-minify' ); ?> ]</p>
<p class="description">[ <?php _e( 'Will match using <code>PHP stripos</code> against the script <code>src attribute', 'fast-velocity-minify' ); ?></code> ]</p>
</fieldset></td>
</tr>

<tr>
<th scope="row"><?php _e( 'Inline JavaScript Dependencies', 'fast-velocity-minify' ); ?></th>
<td><fieldset>
<label for="fvm_settings_defer_dependencies"><span class="fvm-bold-green fvm-rowintro"><?php _e( 'Delay Inline JavaScript until after the deferred scripts merged above finish loading', 'fast-velocity-minify' ); ?></span></label>
<p><textarea name="fvm_settings[js][defer_dependencies]" rows="7" cols="50" id="fvm_settings_js_defer_dependencies" class="large-text code" placeholder="<?php _e( '--- a small snippet that should match an inline script and make it wait for the deferred scripts above ---', 'fast-velocity-minify' ); ?>"><?php echo fvm_get_settings_value($fvm_settings, 'js', 'defer_dependencies'); ?></textarea></p>
<p class="description">[ <?php _e( 'Inline JavaScript matching these rules, will wait until after the window.load event', 'fast-velocity-minify' ); ?> ]</p>
<p class="description">[ <?php _e( 'Will match using <code>PHP stripos</code> against the script <code>innerHTML</code>', 'fast-velocity-minify' ); ?> ]</p>
</fieldset></td>
</tr>

<tr>
<th scope="row"><?php _e( 'Execute matching third party scripts after user interaction', 'fast-velocity-minify' ); ?></th>
<td><fieldset>
<label for="fvm_settings_js_thirdparty"><span class="fvm-bold-green fvm-rowintro"><?php _e( 'Delay the following inline scripts until after user interaction', 'fast-velocity-minify' ); ?></span></label>
<p><textarea name="fvm_settings[js][thirdparty]" rows="7" cols="50" id="fvm_settings_js_thirdparty" class="large-text code" placeholder="<?php _e( '--- example ---', 'fast-velocity-minify' ); ?> 

function(w,d,s,l,i) 
function(f,b,e,v,n,t,s)
function(h,o,t,j,a,r)
www.googletagmanager.com/gtm.js"><?php echo fvm_get_settings_value($fvm_settings, 'js', 'thirdparty'); ?></textarea></p>
<p class="description">[ <?php _e( 'If there is no interaction from the user, scripts will still load after 5 seconds automatically.', 'fast-velocity-minify' ); ?> ]</p>
<p class="description">[ <?php _e( 'Will match using <code>PHP stripos</code> against the script <code>innerHTML</code> or <code>src</code> attribute for async/defer scripts (only)', 'fast-velocity-minify' ); ?> ]</p>
</fieldset></td>
</tr>

<tr>
<th scope="row"><?php _e( 'Remove JavaScript Scripts', 'fast-velocity-minify' ); ?></th>
<td><fieldset>
<label for="fvm_settings_js_remove"><span class="fvm-bold-green fvm-rowintro"><?php _e( 'Remove the following JS files or Inline Scripts', 'fast-velocity-minify' ); ?></span></label>
<p><textarea name="fvm_settings[js][remove]" rows="7" cols="50" id="fvm_settings_js_remove" class="large-text code" placeholder="--- should be empty in most cases ---"><?php echo fvm_get_settings_value($fvm_settings, 'js', 'remove'); ?></textarea></p>
<p class="description">[ <?php _e( 'This will allow you to remove unwanted script tags from the frontend', 'fast-velocity-minify' ); ?> ]</p>
<p class="description">[ <?php _e( 'Will match using <code>PHP stripos</code> against the script <code>outerHTML</code>', 'fast-velocity-minify' ); ?> ]</p>
</fieldset></td>
</tr>


</tbody>
</table>



<div style="height: 60px;"></div>
<h2 class="title"><?php _e( 'CDN Settings', 'fast-velocity-minify' ); ?></h2>
<h3 class="fvm-bold-green"><?php _e( 'If your CDN provider gives you a different URL for your assets, you can use it here', 'fast-velocity-minify' ); ?></h3>
<table class="form-table fvm-settings">
<tbody>
<tr>
<th scope="row"><?php _e( 'CDN Options', 'fast-velocity-minify' ); ?></th>
<td>
<p class="fvm-bold-green fvm-rowintro"><?php _e( 'Select your options below', 'fast-velocity-minify' ); ?></p>

<fieldset>
<label for="fvm_settings_cdn_enable">
<input name="fvm_settings[cdn][enable]" type="checkbox" id="fvm_settings_cdn_enable" value="1" <?php echo fvm_get_settings_checkbox(fvm_get_settings_value($fvm_settings, 'cdn', 'enable')); ?>>
<?php _e( 'Enable CDN Processing', 'fast-velocity-minify' ); ?> <span class="note-info">[ <?php _e( 'Will enable processing for the settings below', 'fast-velocity-minify' ); ?> ]</span></label>
<br />

<label for="fvm_settings_cdn_cssok">
<input name="fvm_settings[cdn][cssok]" type="checkbox" id="fvm_settings_cdn_cssok" value="1" <?php echo fvm_get_settings_checkbox(fvm_get_settings_value($fvm_settings, 'cdn', 'cssok')); ?>>
<?php _e( 'Enable CDN for merged CSS files', 'fast-velocity-minify' ); ?> <span class="note-info">[ <?php _e( 'Will serve the FVM generated CSS files from the CDN', 'fast-velocity-minify' ); ?> ]</span></label>
<br />

<label for="fvm_settings_cdn_jsok">
<input name="fvm_settings[cdn][jsok]" type="checkbox" id="fvm_settings_cdn_jsok" value="1" <?php echo fvm_get_settings_checkbox(fvm_get_settings_value($fvm_settings, 'cdn', 'jsok')); ?>>
<?php _e( 'Enable CDN for merged JS files', 'fast-velocity-minify' ); ?> <span class="note-info">[ <?php _e( 'Will serve the FVM generated JS files from the CDN', 'fast-velocity-minify' ); ?> ]</span></label>
<br />

</fieldset></td>
</tr>
<tr>
<th scope="row"><span class="fvm-label-special"><?php _e( 'CDN URL', 'fast-velocity-minify' ); ?></span></th>
<td><fieldset>
<label for="fvm_settings_cdn_domain">
<p><input type="text" name="fvm_settings[cdn][domain]" id="fvm_settings_cdn_domain" value="<?php echo fvm_get_settings_value($fvm_settings, 'cdn', 'domain'); ?>" size="80" /></p>
<p class="description">[ <?php _e( 'Not needed for Cloudflare or same domain reverse proxy cdn services.', 'fast-velocity-minify' ); ?> ]</p>
</label>
<br />
</fieldset></td>
</tr>
<tr>
<th scope="row"><?php _e( 'CDN Integration', 'fast-velocity-minify' ); ?></th>
<td><fieldset>
<label for="fvm_settings_cdn_integration"><span class="fvm-bold-green fvm-rowintro"><?php _e( 'Replace the following elements', 'fast-velocity-minify' ); ?></span></label>
<p><textarea name="fvm_settings[cdn][integration]" rows="7" cols="50" id="fvm_settings_cdn_integration" class="large-text code" placeholder="--- check the help section for suggestions ---"><?php echo fvm_get_settings_value($fvm_settings, 'cdn', 'integration'); ?></textarea></p>
<p class="description">[ <?php _e( 'Uses syntax from <code>https://simplehtmldom.sourceforge.io/manual.htm', 'fast-velocity-minify' ); ?></code> ]</p>
<p class="description">[ <?php _e( 'You can target a child of a specific html tag, an element with a specific attribute, class or id.', 'fast-velocity-minify' ); ?> ]</p>
</fieldset></td>
</tr>
</tbody></table>



<div style="height: 60px;"></div>
<h2 class="title"><?php _e( 'Cache Location', 'fast-velocity-minify' ); ?></h2>
<h3 class="fvm-bold-green"><?php _e( 'FVM does not have page caching, so these settings are for the generated CSS and JS files only', 'fast-velocity-minify' ); ?></h3>
<table class="form-table fvm-settings">
<tbody>
<tr>
<th scope="row"><span class="fvm-label-special"><?php _e( 'Public Files Cache Path', 'fast-velocity-minify' ); ?></span></th>
<td><fieldset>
<label for="fvm_settings_cache_path">
<p><input type="text" name="fvm_settings[cache][path]" id="fvm_settings_cache_path" value="<?php echo fvm_get_settings_value($fvm_settings, 'cache', 'path'); ?>" size="80" /></p>
<p class="description">[ <?php _e( 'Current base path:', 'fast-velocity-minify' ); ?> <code><?php echo $fvm_cache_paths['cache_base_dir']; ?></code> ]</p>
</label>
<br />
</fieldset></td>
</tr>
<tr>
<th scope="row"><span class="fvm-label-special"><?php _e( 'Public Files Cache URL', 'fast-velocity-minify' ); ?></span></th>
<td><fieldset>
<label for="fvm_settings_cache_url">
<p><input type="text" name="fvm_settings[cache][url]" id="fvm_settings_cache_url" value="<?php echo fvm_get_settings_value($fvm_settings, 'cache', 'url'); ?>" size="80" /></p>
<p class="description">[ <?php _e( 'Current base url:', 'fast-velocity-minify' ); ?> <code><?php echo $fvm_cache_paths['cache_base_dirurl']; ?></code> ]</p>
</label>
<br />
</fieldset></td>
</tr>
</tbody></table>


<div style="height: 60px;"></div>
<h2 class="title"><?php _e( 'User Settings', 'fast-velocity-minify' ); ?></h2>
<h3 class="fvm-bold-green"><?php _e( 'For compatibility reasons, only anonymous users should be optimized by default.', 'fast-velocity-minify' ); ?></h3>
<table class="form-table fvm-settings">
<tbody>

<tr>
<th scope="row"><?php _e( 'User Options', 'fast-velocity-minify' ); ?></th>
<td>
<p class="fvm-bold-green fvm-rowintro"><?php _e( 'Force optimization for the following user roles', 'fast-velocity-minify' ); ?></p>

<fieldset>
<?php
# output user roles checkboxes
echo fvm_get_user_roles_checkboxes();
?>
</fieldset></td>
</tbody></table>


<input type="hidden" name="fvm_action" value="save_settings" />
<p class="submit"><input type="submit" class="button button-primary" value="<?php _e( 'Save Changes', 'fast-velocity-minify' ); ?>"></p>

</form>
</div>
<?php 
}
