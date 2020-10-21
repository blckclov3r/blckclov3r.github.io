<?php
/*
 * The file is included from /templates/meta-box-loaded-assets/_asset-style-single-row.php
*/

if (! isset($data, $inlineCodeStatus)) {
	exit; // no direct access
}

if (! empty($data['row']['extra_data_css_list'])) { ?>
	<div class="wpacu-assets-inline-code-wrap">
		<?php _e('Inline styling associated with the handle:', 'wp-asset-clean-up'); ?>
		<a class="wpacu-assets-inline-code-collapsible"
			<?php if ($inlineCodeStatus !== 'contracted') { echo 'wpacu-assets-inline-code-collapsible-active'; } ?>
           href="#"><?php _e('Show / Hide', 'wp-asset-clean-up'); ?></a>
		<div class="wpacu-assets-inline-code-collapsible-content <?php if ($inlineCodeStatus !== 'contracted') { echo 'wpacu-open'; } ?>">
			<div>
				<p style="margin-bottom: 15px; line-height: normal !important;">
					<?php
					foreach ($data['row']['extra_data_css_list'] as $extraDataCSS) {
					    $htmlInline = trim(\WpAssetCleanUp\OptimiseAssets\OptimizeCss::generateInlineAssocHtmlForHandle(
						    $data['row']['obj']->handle,
						    $extraDataCSS
					    ));
						echo '<small><code>'.nl2br(htmlspecialchars($htmlInline)).'</code></small>';
					}
					?>
				</p>
			</div>
		</div>
	</div>
	<?php
}
