<?php
if (! isset($data)) {
	exit;
}
?>

<?php
if ($data['google_fonts_remove']) {
	?>
	<div class="wpacu-warning" style="font-size: inherit;"><span class="dashicons dashicons-warning"></span> As the option to remove Google Fonts is turned <strong>on</strong>, the options below are irrelevant. If you turn <strong>off</strong> "Remove Google Fonts" and save settings, this notice will disappear and the options below will take effect as the fonts will be loading.</div>
	<?php
}
?>

<table class="wpacu-form-table">
	<tr valign="top">
		<th scope="row" class="setting_title">
			<label for="wpacu_google_fonts_combine"><?php _e('Combine Multiple Requests Into Fewer Ones', 'wp-asset-clean-up'); ?></label>
			<p class="wpacu_subtitle"><small><em>And choose the loading option</em></small></p>
		</th>
		<td>
			<label class="wpacu_switch">
				<input id="wpacu_google_fonts_combine"
				       type="checkbox"
				       data-target-opacity="google_fonts_combine_wrap"
					<?php echo (($data['google_fonts_combine'] == 1) ? 'checked="checked"' : ''); ?>
					   name="<?php echo WPACU_PLUGIN_ID . '_settings'; ?>[google_fonts_combine]"
					   value="1" /> <span class="wpacu_slider wpacu_round"></span> </label>

			&nbsp;<?php _e('This option combines multiple font requests into fewer requests', 'wp-asset-clean-up'); ?>. Once it's active, you can choose how the load takes place, depending on your website, by choosing one of the radio options below. <strong>You can enable this option even if you know you have one LINK request. If no combination will take place, the loading type below will be applied.</strong> Note that the asynchronous loading could cause a <strong>FOUT</strong> (flash of unstyled text) until the Google fonts get loaded, so it's recommended to test it out after the change.

			<div id="google_fonts_combine_wrap" <?php if (! $data['google_fonts_combine']) { ?>style="opacity: 0.4;"<?php } ?>>
				<div class="google_fonts_load_types">
					<div style="flex-basis: 70%; padding-right: 20px;" class="wpacu-fancy-radio"><label for="google_fonts_combine_type_rb"><input id="google_fonts_combine_type_rb" class="google_fonts_combine_type" type="radio" name="<?php echo WPACU_PLUGIN_ID . '_settings'; ?>[google_fonts_combine_type]" <?php if (! $data['google_fonts_combine_type']) { ?>checked="checked"<?php } ?> value="" />Render-blocking (default)</label></div>
					<div style="flex-basis: 90%; padding-right: 20px;" class="wpacu-fancy-radio"><label for="google_fonts_combine_type_async"><input id="google_fonts_combine_type_async" class="google_fonts_combine_type" type="radio" name="<?php echo WPACU_PLUGIN_ID . '_settings'; ?>[google_fonts_combine_type]" <?php if ($data['google_fonts_combine_type'] === 'async') { ?>checked="checked"<?php } ?> value="async" />Asynchronous via Web Font Loader (webfont.js)</label></div>
					<div style="flex-basis: 90%;" class="wpacu-fancy-radio"><label for="google_fonts_combine_type_async_preload"><input id="google_fonts_combine_type_async_preload" class="google_fonts_combine_type" type="radio" name="<?php echo WPACU_PLUGIN_ID . '_settings'; ?>[google_fonts_combine_type]" <?php if ($data['google_fonts_combine_type'] === 'async_preload') { ?>checked="checked"<?php } ?> value="async_preload" />Asynchronous by preloading the CSS stylesheet</label></div>
				</div>

				<!-- Render-blocking (default) info -->
				<div id="wpacu_google_fonts_combine_type_rb_info_area" class="wpacu_google_fonts_combine_type_area" <?php if ($data['google_fonts_combine_type']) { echo 'style="display: none;"'; } ?>>
					<p><strong>Example</strong> The following LINK tags will be merged into one tag:</p>

					<ul>
						<li><code>&lt;link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Droid+Sans"&gt;</code></li>
						<li><code>&lt;link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inconsolata:bold"&gt;</code></li>
					</ul>
					<hr />
					<ul>
						<li><code>&lt;link rel="stylesheet" id="wpacu-combined-google-fonts-css" href="https://fonts.googleapis.com/css?family=Droid+Sans|Inconsolata:bold"&gt;</code></li>
					</ul>

					<p><strong>Result:</strong> This simple feature saves one round trip to the server for each additional font requested (reducing the number of HTTP requests), and also protects against blocking on older browsers which only have 2 connections open per domain at a time.</p>
				</div>
				<!-- /Render-blocking (default) info -->

				<!-- Async info -->
				<div id="wpacu_google_fonts_combine_type_async_info_area" class="wpacu_google_fonts_combine_type_area" <?php if ($data['google_fonts_combine_type'] !== 'async') { echo 'style="display: none;"'; } ?>>
					<p><strong>Example</strong> The following LINK tags will be converted into an inline JavaScript tag place:</p>

					<ul>
						<li><code>&lt;link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Droid+Sans"&gt;</code></li>
						<li><code>&lt;link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inconsolata:bold"&gt;</code></li>
					</ul>
					<hr />
					<ul>
						<li>
							<code>
								<?php
								$asyncWebFontLoaderSnippet = <<<HTML
&lt;script id='wpacu-google-fonts-async-load' type='text/javascript'&gt;
WebFontConfig = { google: { families: ['Droid+Sans', 'Inconsolata:bold'] } };
(function(wpacuD) {
&nbsp;&nbsp;var wpacuWf = wpacuD.createElement('script'), wpacuS = wpacuD.scripts[0];
&nbsp;&nbsp;wpacuWf.src = ('https:' === document.location.protocol ? 'https' : 'http') 
&nbsp;&nbsp;&nbsp;&nbsp;+ '://ajax.googleapis.com/ajax/libs/webfont/1.6.26/webfont.js';
&nbsp;&nbsp;wpacuWf.async = true;
&nbsp;&nbsp;wpacuS.parentNode.insertBefore(wpacuWf, wpacuS);
})(document);
&lt;/script&gt;&lt;noscript&gt;&lt;link rel="stylesheet" id="wpacu-combined-google-fonts-css" href="https://fonts.googleapis.com/css?family=Droid+Sans|Inconsolata:bold"&gt;&lt;/noscript&gt;
HTML;
								echo nl2br($asyncWebFontLoaderSnippet);
								?>
							</code>
							<p style="margin-top: 5px;"><small><strong>Note:</strong> The inline tag's contents will be minified in the resulting HTML code. A NOSCRIPT tag is appended to the SCRIPT tag as a fallback in case JavaScript is disabled for any reason.</small></p>
						</li>
					</ul>

					<p>Using the Web Font Loader asynchronously avoids blocking your page while loading the JavaScript. A <strong>disadvantage</strong> is that the rest of the page might render before the Web Font Loader is loaded and executed, which can cause a <strong>Flash of Unstyled Text (FOUT)</strong>.</p>
				</div>
				<!-- /Async info -->

				<!-- Async preload info -->
				<div id="wpacu_google_fonts_combine_type_async_preload_info_area" class="wpacu_google_fonts_combine_type_area" <?php if ($data['google_fonts_combine_type'] !== 'async_preload') { echo 'style="display: none;"'; } ?>>
					<p><strong>Example</strong> The following LINK tags will be converted into a non-render blocking LINK "preload" tag:</p>

					<ul>
						<li><code>&lt;link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Droid+Sans"&gt;</code></li>
						<li><code>&lt;link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inconsolata:bold"&gt;</code></li>
					</ul>
					<hr />
					<ul>
						<li>
							<code>
								<?php
								$asyncPreloadSnippet = <<<HTML
&lt;link rel="preload" as="style" onload="this.rel='stylesheet'" id="wpacu-combined-google-fonts-css-preload" href="https://fonts.googleapis.com/css?family=Droid+Sans|Inconsolata:bold"&gt;
&lt;noscript&gt;&lt;link rel="stylesheet" id="wpacu-combined-google-fonts-css" href="https://fonts.googleapis.com/css?family=Droid+Sans|Inconsolata:bold"&gt;&lt;/noscript&gt;
HTML;

								echo nl2br($asyncPreloadSnippet);
								?>
							</code>
							<p style="margin-top: 5px;"><small><strong>Note:</strong> A NOSCRIPT tag is appended to the LINK "preload" tag as a fallback in case JavaScript is disabled for any reason. For some browsers like Mozilla Firefox that don't support preloading as well as Google Chrome, an extra fallback script is loaded in the HEAD section of the website. <a target="_blank" href="https://github.com/filamentgroup/loadCSS">Read more about loadCSS</a></small></p>
						</li>
					</ul>
				</div>
				<!-- /Async preload info -->
			</div>
		</td>
	</tr>
	<tr valign="top">
		<th scope="row" class="setting_title">
			<?php echo sprintf(__('Apply %s CSS property value', 'wp-asset-clean-up'), '<span style="background: #f5f5f5; padding: 4px;">font-display:</span>'); ?>
		</th>
		<td>
			<select name="<?php echo WPACU_PLUGIN_ID . '_settings'; ?>[google_fonts_display]">
				<option value="">Do not apply (default)</option>
				<?php
				foreach ($ddOptions as $ddOptionValue => $ddOptionText) {
					$selectedOption = ($data['google_fonts_display'] === $ddOptionValue) ? 'selected="selected"' : '';
					echo '<option '.$selectedOption.' value="'.$ddOptionValue.'">'.$ddOptionText.'</option>'."\n";
				}
				?>
			</select>
			&nbsp;
			<?php _e('This feature applies site-wide "&display=" with the chosen value to all the Google Font URL requests (if the parameter is not already set in the URL).', 'wp-asset-clean-up'); ?>
			<?php _e('This will result in printing of "font-display" CSS property within @font-face.', 'wp-asset-clean-up'); ?>
			<span style="color: #0073aa;" class="dashicons dashicons-info"></span> <a id="wpacu-google-fonts-display-info-target" href="#wpacu-google-fonts-display-info"><?php _e('Read more', 'wp-asset-clean-up'); ?></a>

			<hr />
			<ul>
				<li><code>&lt;link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto+Mono<strong>&amp;display=swap</strong>"&gt;</code></li>
				<li><code>&lt;link rel="stylesheet" id="wpacu-combined-google-fonts-css" href="https://fonts.googleapis.com/css?family=Droid+Sans|Inconsolata:bold<strong>&amp;display=swap</strong>"&gt;</code></li>
			</ul>
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
			<?php _e('Preconnect?', 'wp-asset-clean-up'); ?>
			<p class="wpacu_subtitle"><small><em><?php _e('Don\'t let the browser wait until it sees the CSS call font files before it begins DNS/TCP/TLS', 'wp-asset-clean-up'); ?></em></small></p>
		</th>
		<td>
			<label class="wpacu_switch">
				<input id="wpacu_google_fonts_preconnect"
				       type="checkbox"
				       data-target-opacity="google_fonts_preconnect_wrap"
					<?php echo (($data['google_fonts_preconnect'] == 1) ? 'checked="checked"' : ''); ?>
					   name="<?php echo WPACU_PLUGIN_ID . '_settings'; ?>[google_fonts_preconnect]"
					   value="1" /> <span class="wpacu_slider wpacu_round"></span> </label>
			&nbsp;If you know your website requests resources from Google Fonts (fonts.gstatic.com), then adding the preconnect resource hint will instruct the browser to preconnect to Google Fonts while it is loading the CSS, saving load time.
			<hr />
			<div id="google_fonts_preconnect_wrap">
				<p style="margin-bottom: 5px;">This will generate the following output within <code>&lt;HEAD&gt;</code> and <code>&lt;/HEAD&gt;</code>:</p>
				<code>&lt;link href='https://fonts.gstatic.com' crossorigin rel='preconnect' /&gt;</code>
			</div>
		</td>
	</tr>
	<tr valign="top">
		<th scope="row" class="setting_title">
			<?php _e('Preload Google Font Files', 'wp-asset-clean-up'); ?>
			<p class="wpacu_subtitle"><small><em><?php _e('One per line', 'wp-asset-clean-up'); ?>, only external Google Font Files are allowed</em></small></p>
		</th>
		<td>
			<div style="margin: 0 0 6px;"><?php _e('If you wish to preload any of the Google Font Files (e.g. ending in .woff2), you can add their URL here (one per line)', 'wp-asset-clean-up'); ?>:</div>
			<textarea style="width:100%;"
			          rows="5"
			          name="<?php echo WPACU_PLUGIN_ID . '_settings'; ?>[google_fonts_preload_files]"><?php echo $data['google_fonts_preload_files']; ?></textarea>
			<hr />
			<p>To get the URL to the actual font file, you have to open the Google Fonts Link (e.g. https://fonts.googleapis.com/css?family=Open+Sans:bold), locate the actual @font-face (or all of them, depends on the circumstances), and then copy the value of the <code>url</code> within the 'src:' property.</p>
			<strong>Examples:</strong>
			<div style="margin-top: 5px;">
				<div><code>https://fonts.gstatic.com/s/roboto/v20/KFOmCnqEu92Fr1Mu72xKKTU1Kvnz.woff2</code></div>
				<div><code>https://fonts.gstatic.com/s/robotomono/v7/L0x5DF4xlVMF-BfR8bXMIjhFq3-cXbKDO1w.woff2</code></div>
			</div>
			<hr />
			<strong>Generated Output</strong>, printed within <code>&lt;HEAD&gt;</code> and <code>&lt;/HEAD&gt;</code>
			<div style="margin-top: 5px;">
				<div style="margin-bottom: 8px;"><code>&lt;link rel="preload" as="font" href="https://fonts.gstatic.com/s/roboto/v20/KFOmCnqEu92Fr1Mu72xKKTU1Kvnz.woff2" data-wpacu-preload-font="1" crossorigin&gt;</code></div>
				<div style="margin-bottom: 8px;"><code>&lt;link rel="preload" as="font" href="https://fonts.gstatic.com/s/robotomono/v7/L0x5DF4xlVMF-BfR8bXMIjhFq3-cXbKDO1w.woff2" data-wpacu-preload-font="1" crossorigin&gt;</code></div>
			</div>
		</td>
	</tr>
</table>
