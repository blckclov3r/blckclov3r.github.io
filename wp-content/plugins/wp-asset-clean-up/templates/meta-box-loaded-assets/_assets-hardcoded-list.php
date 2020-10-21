<?php
if (! isset($data)) {
	exit; // no direct access
}

$totalHardcodedTags = 0;
$hardcodedTags      = $data['all']['hardcoded'];

// Use the WordPress cache mostly for retrieval of the values if the Dashboard retrieval was used
// Otherwise, default to $data if no valid value was set for the cache
$dataKeys = array(
	'page_unload_text'
);

foreach ($dataKeys as $dataKey) {
	if ( ! ( $dataValue = \WpAssetCleanUp\ObjectCache::wpacu_cache_get( 'wpacu_data_' . $dataKey ) ) ) {
		$dataValue = isset( $data[ $dataKey ] ) ? $data[ $dataKey ] : '';
	}

	$data[$dataKey] = $dataValue;
}

$contentWithinConditionalComments = \WpAssetCleanUp\ObjectCache::wpacu_cache_get('wpacu_hardcoded_content_within_conditional_comments');

$totalHardcodedTags  = isset($hardcodedTags['link_and_style_tags'])        ? count($hardcodedTags['link_and_style_tags'])        : 0;
$totalHardcodedTags += isset($hardcodedTags['script_src_and_inline_tags']) ? count($hardcodedTags['script_src_and_inline_tags']) : 0;

if ($totalHardcodedTags === 0) {
	return; // Don't print anything if there are no hardcoded tags available
}
?>
<div class="wpacu-assets-collapsible-wrap wpacu-wrap-area wpacu-hardcoded">
    <a class="wpacu-assets-collapsible wpacu-assets-collapsible-active" href="#" style="padding: 15px 15px 15px 44px;">
        <span class="dashicons dashicons-code-standards"></span> Hardcoded (non-enqueued) Styles &amp;
        Scripts &#10141; Total: <?php echo $totalHardcodedTags; ?>
    </a>
    <div class="wpacu-assets-collapsible-content" style="max-height: inherit;">
        <div style="padding: 0;">
            <div style="margin: 15px 0 0;">
                <p><span style="color: #0073aa;" class="dashicons dashicons-info"></span> The following tags are NOT LOADED via the recommended <a target="_blank"
                                                                     href="https://developer.wordpress.org/reference/hooks/wp_enqueue_scripts/"><code>wp_enqueue_scripts()</code></a>
                action hook (despite the name, it is used for enqueuing both scripts and styles) which is the proper one to use when enqueuing scripts and styles that are meant to appear on
                the front end. The standard functions that are used inside the hook to do an enqueuing are: <a target="_blank"
                                                                                                               href="https://developer.wordpress.org/reference/functions/wp_enqueue_style/">wp_enqueue_style()</a>,
	            <a target="_blank" href="https://codex.wordpress.org/Function_Reference/wp_add_inline_style">wp_add_inline_style()</a>,
	            <a target="_blank" href="https://developer.wordpress.org/reference/functions/wp_enqueue_script/">wp_enqueue_script()</a>
	            &amp; <a target="_blank"
	                     href="https://developer.wordpress.org/reference/functions/wp_add_inline_script/">wp_add_inline_script()</a>. The tags could have been added via editing the PHP code (not using the right standard functions), directly inside posts content, widgets or via plugins such as "Insert Headers and Footers", "Head, Footer and Post Injections", etc. Be careful when unloading any of these tags as they might be related to Google Analytics/AdWords, StatCounter, Facebook Pixel, etc.
                </p>
                <div style="margin: 20px 0; border-left: solid 4px green; background: #f2faf2; padding: 10px;"><img width="20" height="20" src="<?php echo WPACU_PLUGIN_URL; ?>/assets/icons/icon-lock.svg" valign="top" alt="" /> &nbsp;Managing hardcoded LINK/STYLE/SCRIPT tags is an option <a target="_blank" href="<?php echo WPACU_PLUGIN_GO_PRO_URL; ?>?utm_source=manage_hardcoded_assets&utm_medium=top_notice">available in the Pro version</a>.</div>
            </div>
			<?php
			foreach (array('link_and_style_tags', 'script_src_and_inline_tags') as $targetKey) {
				if ( ! empty( $hardcodedTags[ $targetKey ] ) ) {
					$totalTagsForTarget  = count( $hardcodedTags[ $targetKey ] );
					$totalHardcodedTags += $totalTagsForTarget;
					?>
					<div>
						<div class="wpacu-content-title">
							<h3>
								<?php if ($targetKey === 'link_and_style_tags') { ?>Hardcoded LINK (stylesheet) &amp; STYLE tags<?php } ?>
								<?php if ($targetKey === 'script_src_and_inline_tags') { ?>Hardcoded SCRIPT (with "src" attribute &amp; inline) tags<?php } ?>
							</h3>
						</div>
						<table class="wpacu_list_table wpacu_striped">
							<tbody>
							<?php
							$hardcodedTagsOutput = '';

							foreach ( $hardcodedTags[ $targetKey ] as $indexNo => $tagOutput ) {
								$contentUniqueStr = sha1( $tagOutput );
								/*
								 * 1) Hardcoded LINK (stylesheet) &amp; STYLE tags
								*/
								if ($targetKey === 'link_and_style_tags') {
									// For LINK ("stylesheet")
									if ( stripos( $tagOutput, '<link ' ) === 0 ) {
										$generatedHandle  = 'wpacu_hardcoded_link_' . $contentUniqueStr;

										preg_match_all( '#href=(["\'])' . '(.*)' . '(["\'])#Usmi', $tagOutput,
											$outputMatches );
										$linkHrefOriginal = trim( $outputMatches[2][0], '"\'' );

										$dataRowObj = (object) array(
											'handle'     => $generatedHandle,
											'src'        => $linkHrefOriginal,
											'tag_output' => $tagOutput
										);

										$dataRowObj->inside_conditional_comment = \WpAssetCleanUp\HardcodedAssets::isWithinConditionalComment($tagOutput, $contentWithinConditionalComments);

										// Determine source href (starting with '/' but not starting with '//')
										if (strpos($linkHrefOriginal, '/') === 0 && strpos($linkHrefOriginal, '//') !== 0) {
											$dataRowObj->srcHref = get_site_url() . $linkHrefOriginal;
										} else {
											$dataRowObj->srcHref = $linkHrefOriginal;
										}

										$dataHH = $data;
										$dataHH['row']        = array();
										$dataHH['row']['obj'] = $dataRowObj;

										$templateRowOutput = \WpAssetCleanUp\Main::instance()->parseTemplate(
											'/meta-box-loaded-assets/_hardcoded/_asset-style-single-row-hardcoded',
											$dataHH
										);

										$hardcodedTagsOutput .= $templateRowOutput;
									}

									// For STYLE (inline)
									if ( stripos( $tagOutput, '<style' ) === 0 ) {
										$generatedHandle  = 'wpacu_hardcoded_style_' . $contentUniqueStr;

										$dataRowObj = (object) array(
											'handle'     => $generatedHandle,
											'src'        => false,
											'tag_output' => $tagOutput
										);

										$dataRowObj->inside_conditional_comment = \WpAssetCleanUp\HardcodedAssets::isWithinConditionalComment($tagOutput, $contentWithinConditionalComments);

										$dataHH = $data;
										$dataHH['row']        = array();
										$dataHH['row']['obj'] = $dataRowObj;

										$templateRowOutput = \WpAssetCleanUp\Main::instance()->parseTemplate(
											'/meta-box-loaded-assets/_hardcoded/_asset-style-single-row-hardcoded',
											$dataHH
										);

										$hardcodedTagsOutput .= $templateRowOutput;
									}
								} elseif ($targetKey === 'script_src_and_inline_tags') {
								/*
								 * 2) Hardcoded SCRIPT (with "src" attribute & inline) tags
								*/
									$generatedHandle = false;
									$srcHrefOriginal = false;
									$isScriptInline  = false;

									if (stripos($tagOutput, 'src') !== false) {
										$srcHrefOriginal = false;

										if (function_exists('libxml_use_internal_errors') && function_exists('libxml_clear_errors') && class_exists('DOMDocument')) {
											$domForTag = new \DOMDocument();
											libxml_use_internal_errors( true );

											$domForTag->loadHTML( $tagOutput );

											$scriptTagObj = $domForTag->getElementsByTagName( 'script' )->item( 0 );

											$scriptAttributes = array();

											foreach ( $scriptTagObj->attributes as $attrObj ) {
												if ( $attrObj->nodeName === 'src' ) {
													$srcHrefOriginal = trim( $attrObj->nodeValue );
													break;
												}
											}
										} else { // Fallback in case DOMDocument is not active for any reason
											// only look from <script to >
											preg_match_all( '#<script(.*?)src=(["\'])' . '(.*)' . '(["\'])(>)#Usmi',
												$tagOutput, $outputMatches );

											if ( isset( $outputMatches[3][0] ) ) {
												$srcHrefOriginal = trim( $outputMatches[3][0], '"\'' );
											}
										}
									}

									if ($srcHrefOriginal) {
										$generatedHandle  = 'wpacu_hardcoded_script_src_' . $contentUniqueStr;
									}

									// It it a SCRIPT without "src" attribute? Then it's an inline one
									if (! $generatedHandle) {
										$generatedHandle  = 'wpacu_hardcoded_script_inline_' . $contentUniqueStr;
									}

									$dataRowObj = (object)array(
										'handle'     => $generatedHandle,
										'tag_output' => $tagOutput
									);

									if ($srcHrefOriginal) {
										$dataRowObj->src = $srcHrefOriginal;
									}

									$dataRowObj->inside_conditional_comment = \WpAssetCleanUp\HardcodedAssets::isWithinConditionalComment($tagOutput, $contentWithinConditionalComments);

									// Determine source href (starting with '/' but not starting with '//')
                                    if ($srcHrefOriginal) {
	                                    if ( strpos( $srcHrefOriginal, '/' ) === 0 && strpos( $srcHrefOriginal, '//' ) !== 0 ) {
		                                    $dataRowObj->srcHref = get_site_url() . $srcHrefOriginal;
	                                    } else {
		                                    $dataRowObj->srcHref = $srcHrefOriginal;
	                                    }
                                    }

									$dataHH = $data;
									$dataHH['row']        = array();
									$dataHH['row']['obj'] = $dataRowObj;

									$templateRowOutput = \WpAssetCleanUp\Main::instance()->parseTemplate(
										'/meta-box-loaded-assets/_hardcoded/_asset-script-single-row-hardcoded',
										$dataHH
									);

									$hardcodedTagsOutput .= $templateRowOutput;
								}
							}

							echo $hardcodedTagsOutput;
							?>
							</tbody>
						</table>
					</div>
					<?php
				}
			}
			?>
        </div>
    </div>
</div>
<?php
