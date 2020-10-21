<?php
/*
 * The file is included from /templates/meta-box-loaded-assets/_asset-script-single-row.php
*/

if (! isset($data, $inlineCodeStatus)) {
	exit; // no direct access
}

if ($data['row']['extra_data_js'] || $data['row']['extra_before_js'] || $data['row']['extra_after_js']) {
	?>
	<div class="wpacu-assets-inline-code-wrap">
		<?php _e('Inline JavaScript code associated with the handle:', 'wp-asset-clean-up'); ?>
		<a class="wpacu-assets-inline-code-collapsible"
			<?php if ($inlineCodeStatus !== 'contracted') { echo 'wpacu-assets-inline-code-collapsible-active'; } ?>
           href="#"><?php _e('Show', 'wp-asset-clean-up'); ?> / <?php _e('Hide', 'wp-asset-clean-up'); ?></a>
		<div class="wpacu-assets-inline-code-collapsible-content <?php if ($inlineCodeStatus !== 'contracted') { echo 'wpacu-open'; } ?>">
            <?php
            $extraInlineKeys = array(
                'data'   => 'CDATA added via wp_localize_script()',
                'before' => 'Before the tag:',
                'after'  => 'After the tag:'
            );

            foreach ($extraInlineKeys as $extraKeyValue => $extraKeyText) {
            	$keyToMatch = 'extra_'.$extraKeyValue.'_js';

            	if ( ! isset($data['row'][$keyToMatch]) ) {
            		continue;
	            }

                $inlineScriptContent = $data['row'][$keyToMatch];

                if (is_array($inlineScriptContent) && in_array($extraKeyValue, array('before', 'after'))) {
                    $inlineScriptContent = (string)ltrim(implode("\n", $inlineScriptContent));
                }

	            $inlineScriptContent = trim($inlineScriptContent);

                if ($inlineScriptContent) {
	                ?>
                    <div style="margin-bottom: 8px;">
                        <div style="margin-bottom: 10px;"><strong><?php echo $extraKeyText; ?></strong></div>
                        <div style="margin-top: -7px !important; line-height: normal !important;">
                            <?php
                            $htmlInline = trim( \WpAssetCleanUp\OptimiseAssets\OptimizeJs::generateInlineAssocHtmlForHandle(
                                $data['row']['obj']->handle,
                                $extraKeyValue,
                                $inlineScriptContent
                            ) );

                            echo '<small><code>' . nl2br( htmlspecialchars( $htmlInline ) ) . '</code></small>';
                            ?>
                        </div>
                    </div>
                <?php
                }
            }
            ?>
		</div>
	</div>
	<?php
}
