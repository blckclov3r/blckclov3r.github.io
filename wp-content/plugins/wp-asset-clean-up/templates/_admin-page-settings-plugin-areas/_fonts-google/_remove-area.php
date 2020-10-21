<?php
use WpAssetCleanUp\OptimiseAssets\OptimizeCommon;

if (! isset($data)) {
	exit;
}
?>
<table class="wpacu-form-table">
	<tr valign="top">
		<th scope="row" class="setting_title">
			<label for="wpacu_google_fonts_remove"><?php _e('Remove Google Fonts', 'wp-asset-clean-up'); ?></label>
		</th>
		<td style="padding-bottom: 10px;">
			<label class="wpacu_switch">
				<input id="wpacu_google_fonts_remove"
				       type="checkbox"
				       data-target-opacity="google_fonts_remove_wrap"
					<?php echo (($data['google_fonts_remove'] == 1) ? 'checked="checked"' : ''); ?>
					   name="<?php echo WPACU_PLUGIN_ID . '_settings'; ?>[google_fonts_remove]"
					   value="1" /> <span class="wpacu_slider wpacu_round"></span></label>
			&nbsp;This option removes the Google Fonts requests from your website including: <code>&lt;LINK&gt;</code> tags (included preloaded ones), @import within CSS stylesheet files / <code>&lt;STYLE&gt;</code> tags and any @font-face that loads font files from <em>fonts.gstatic.com</em>)
		</td>
	</tr>
</table>
<div id="google_fonts_remove_wrap" style="padding: 0 10px 10px; <?php if (! $data['google_fonts_remove']) { ?>opacity: 0.4;<?php } ?>">
	<hr />

	<p style="margin-bottom: 10px;"><strong style="border-bottom: 1px dotted black;">Possible reasons to remove Google Font requests</strong></p>
	<ul style="list-style: circle; margin-left: 22px; margin-top: 0;">
		<li>You have your own font files that you wish to implement and don't need to have any requests to Google Fonts</li>
		<li>You're already using your own local fonts and you just installed a plugin that makes requests to Google Fonts leading to extra external requests which are affecting the performance</li>
	</ul>

	<hr />

	<p style="margin-bottom: 10px;"><strong style="border-bottom: 1px dotted black;">What kind of content will be stripped?</strong> * some examples:</p>

	<strong>&#10230; LINK tags</strong>
	<ul style="list-style: none; margin-left: 0; margin-top: 8px; margin-bottom: 20px;">
		<li><code>&lt;link rel='stylesheet' href='https://fonts.googleapis.com/css?family=Roboto' /&gt;</code></li>
		<li><code>&lt;link rel='preload' as='style' href='https://fonts.googleapis.com/css?family=Roboto' /&gt;</code></li>
		<li><code>&lt;link rel='preload' as='font' href='https://fonts.gstatic.com/s/roboto/v20/KFOmCnqEu92Fr1Mu7mxKKTU1Kvnz.woff2' crossorigin /&gt;</code></li>
		<li><code>&lt;link rel='dns-prefetch' href='//fonts.googleapis.com' /&gt;</code></li>
		<li><code>&lt;link rel='preconnect' href='https://fonts.gstatic.com' crossorigin /&gt;</code></li>
	</ul>

	<strong>&#10230; @import &amp; @font-face in CSS files (from the same domain) or STYLE tags</strong>
	<ul style="list-style: none; margin-left: 0; margin-top: 8px;">
		<li><code>@import "https://fonts.googleapis.com/css?family=Open+Sans+Condensed:300";</code></li>
		<li><code>@import url("https://fonts.googleapis.com/css?family=Verdana:700");</code></li>
		<li><?php
			$cssFontFaceSample = <<<CSS
@font-face {
font-family: 'Roboto';
font-style: normal;
font-weight: 400;
src: local('Roboto'), local('Roboto-Regular'), url(https://fonts.gstatic.com/s/roboto/v20/KFOmCnqEu92Fr1Mu7mxKKTU1Kvnz.woff2) format('woff2');
unicode-range: U+1F00-1FFF;
}
CSS;

			echo '<code>'.$cssFontFaceSample.'</code>';
			?></li>
	</ul>

    <strong>&#10230; URLs to Google Fonts within JavaScript files &amp; inline SCRIPT tags</strong>
    <ul style="list-style: none; margin-left: 0; margin-top: 8px; margin-bottom: 0;">
        <li><code>loadCss("<span style="background-color: #f2faf2;">https://fonts.googleapis.com/css?family=Open+Sans+Condensed:300</span>");</code></li>
        <li><code>WebFontConfig={google:{/* code here */}};(function(d) { var wf=d.createElement('script'), s=d.scripts[0]; wf.src='<span style="background-color: #f2faf2;">https://ajax.googleapis.com/ajax/libs/webfont/1.6.26/webfont.js</span>'; wf.async=!0; s.parentNode.insertBefore(wf,s)})(document);</code>, etc.</li>
    </ul>
    <p style="font-size: inherit; font-style: italic; margin-top: 0;"><small>* CDN requests for Web Font Loader from Google, Cloudflare and jsDelivr are detected and stripped.</small></p>

	<p style="margin-bottom: 6px;"><strong style="border-bottom: 1px dotted black;">Is this solution working 100% for any website?</strong></p>
	If you're already using "WebFontConfig" and it's hardcoded in your theme or one of the plugins, it's not easy to strip it via an universal pattern as its code can be scattered in various places and some attempts to strip it off might broke the JavaScript file that triggers it. Thus, in rare cases, you might have some traces left of Google Font requests and you'll need to strip that manually.

	<p style="margin-top: 10px; font-size: inherit;" class="wpacu-warning"><strong>Note:</strong> After you enable this option, any options from "Optimize Font Delivery" won't trigger anymore. If @import or @font-face matches are found in CSS files, the new updated files will be cached and stored in <strong>/<?php echo str_replace(ABSPATH, '', WP_CONTENT_DIR) . OptimizeCommon::getRelPathPluginCacheDir(); ?></strong>. The original files (from either plugins or the theme) won't be altered in any way.</p>
</div>
