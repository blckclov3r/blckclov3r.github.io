<?php if (!defined('WPO_VERSION')) die('No direct access allowed'); ?>
<div id="wp-optimize-minify-advanced" class="wpo_section wpo_group">
	<h3><?php _e('Minify cache information', 'wp-optimize'); ?></h3>
	<div class="wpo-fieldgroup">
		<p>
			<?php _e('Current cache path:', 'wp-optimize'); ?>
			<strong class="wpo_min_cache_path">
				<?php

				$cache_path = WPO_MINIFY_PHP_VERSION_MET ? WP_Optimize_Minify_Cache_Functions::cache_path() : array("cachedir" => __('none', 'wp-optimize'));
				echo htmlspecialchars($cache_path['cachedir']);
				?>
			</strong>
		</p>

		<h3><?php _e('List of processed files', 'wp-optimize'); ?></h3>

		<h4><?php _e('JavaScript files', 'wp-optimize'); ?></h4>
		<div id="wpo_min_jsprocessed">
			<ul class="processed">
				<?php
					$processed_js = 0;
					// Some files exist
					if ($files && isset($files['js']) && is_array($files['js']) && $files['js']) {
						$processed_js = count($files['js']);
						foreach ($files['js'] as $js_file) {
							WP_Optimize()->include_template(
								'minify/cached-file.php',
								false,
								array(
									'file' => $js_file
								)
							);
						}
					}
				?>
				<li class="no-files-yet<?php echo $processed_js ? ' hidden' : ''; ?>">
					<span class="filename"><?php _e('There are no processed files yet.', 'wp-optimize'); ?></span>
				</li>
			</ul>
		</div>

		<h4><?php _e('CSS files', 'wp-optimize'); ?></h4>
		<div id="wpo_min_cssprocessed">
		<?php if ($wpo_minify_options['inline_css']) : ?>
			<p><?php _e('There are no merged CSS files listed here, because you are inlining all CSS directly', 'wp-optimize'); ?></p>
		<?php else : ?>
			<ul class="processed">
				<?php
					$processed_css = 0;
					if ($files && isset($files['css']) && is_array($files['css']) && $files['css']) {
						$processed_css = count($files['css']);
						foreach ($files['css'] as $css_file) {
							WP_Optimize()->include_template(
								'minify/cached-file.php',
								false,
								array(
									'file' => $css_file
								)
							);
						}
					}
					// No files were found
				?>
				<li class="no-files-yet<?php echo $processed_css ? ' hidden' : ''; ?>">
					<span class="filename"><?php _e('There are no processed files yet.', 'wp-optimize'); ?></span>
				</li>
			</ul>
		<?php endif; ?>
		</div>		
		
	</div>
	<h3><?php _e('Development options', 'wp-optimize'); ?></h3>
	<div class="wpo-fieldgroup">
		<div class="switch-container">
			<label class="switch">
				<input
					name="debug"
					id="wpo_min_enable_minify_debug"
					class="debug wpo-save-setting"
					type="checkbox"
					value="true"
					<?php checked($wpo_minify_options['debug']);?>
				>
				<span class="slider round"></span>
			</label>
			<label for="wpo_min_enable_minify_debug">
				<?php _e('Enable debug mode', 'wp-optimize'); ?>
			</label>
		</div>
		<p><?php _e('Enabling the debug mode will add various comments and show more information in the files list.', 'wp-optimize'); ?> <?php _e('It also adds extra actions in the status tab.', 'wp-optimize'); ?></p>
	</div>

	<form method="post" action="#">

	<h3><?php _e('Default exclusions', 'wp-optimize'); ?></h3>
	<div class="wpo-fieldgroup">
		<div class="switch-container">
			<label class="switch">
				<input
					name="edit_default_exclutions"
					id="wpo_min_edit_default_exclutions"
					class="debug wpo-save-setting"
					type="checkbox"
					value="true"
					<?php checked($wpo_minify_options['edit_default_exclutions']);?>
				>
				<span class="slider round"></span>
			</label>
			<label for="wpo_min_edit_default_exclutions">
				<?php _e('Edit default exclusions', 'wp-optimize'); ?>
			</label>
		</div>
		<p><?php _e('By default, WP-Optimize excludes a list of files that are known to cause problems when minified or combined.'); ?>
		<?php _e('Enable this option to see or edit those files.'); ?></p>
		<div class="wpo-minify-default-exclusions<?php echo $wpo_minify_options['edit_default_exclutions'] ? '' : ' hidden'; ?>">
			<h3><?php _e('Known incompatible files', 'wp-optimize'); ?></h3>
			<fieldset>
				<label for="ignore_list">
					<?php _e('List of files that can\'t or shouldn\'t be minified or merged.', 'wp-optimize'); ?>
					<?php _e('Do not edit this if you are not sure what it is.', 'wp-optimize'); ?>
					<span tabindex="0" data-tooltip="<?php esc_attr_e('Files that have been consistently reported by other users to cause trouble when merged', 'wp-optimize');?>"><span class="dashicons dashicons-editor-help"></span> </span>
				</label>
				<textarea
					name="ignore_list"
					rows="7"
					cols="50"
					id="ignore_list"
					class="large-text code"
					placeholder="<?php esc_attr_e('e.g.: /wp-includes/js/jquery/jquery.js', 'wp-optimize'); ?>"
				><?php echo $wpo_minify_options['ignore_list']; ?></textarea>
			</fieldset>

			<h3><?php _e('IE incompatible files', 'wp-optimize'); ?></h3>
			<fieldset>
				<label for="blacklist">
					<?php _e('List of excluded files used for IE compatibility.', 'wp-optimize'); ?>
					<?php _e('Do not edit this if you\'re not sure what it is.', 'wp-optimize'); ?>
					<span tabindex="0" data-tooltip="<?php esc_attr_e('Any CSS files that should always be ignored.', 'wp-optimize');?>"><span class="dashicons dashicons-editor-help"></span> </span>
				</label>
				<textarea
					name="blacklist"
					rows="7"
					cols="50"
					id="blacklist"
					class="large-text code"
					placeholder="<?php esc_attr_e('e.g.: /bootstrap.css', 'wp-optimize'); ?>"
				><?php echo $wpo_minify_options['blacklist']; ?></textarea>
			</fieldset>
		</div>
	</div>

	<?php if (WP_OPTIMIZE_SHOW_MINIFY_ADVANCED) : ?>
		<div class="wpo-fieldgroup">
			<fieldset>
					<br />
					<label for="enabled_css_preload">
						<input
							name="enabled_css_preload"
							type="checkbox"
							id="enabled_css_preload"
							value="1"
							<?php echo checked($wpo_minify_options['enabled_css_preload']); ?>
						>
						<?php _e('Enable WP-O Minify CSS files preloading', 'wp-optimize'); ?>
						<span tabindex="0" data-tooltip="<?php esc_attr_e('Automatically create HTTP headers for WP-O Minify-generated CSS files (when not inlined)', 'wp-optimize');?>"><span class="dashicons dashicons-editor-help"></span> </span>
					</label>
					<br />
					<label for="enabled_js_preload">
						<input
							name="enabled_js_preload"
							type="checkbox"
							id="enabled_js_preload"
							value="1"
							<?php echo checked($wpo_minify_options['enabled_js_preload']); ?>
						>
						<?php _e('Enable WP-O Minify JavaScript files Preload', 'wp-optimize'); ?>
						<span tabindex="0" data-tooltip="<?php esc_attr_e('Automatically create HTTP headers for WP-O Minify-generated JS files', 'wp-optimize');?>"><span class="dashicons dashicons-editor-help"></span> </span>
					</label>
				</fieldset>
			</div>
			<h3 class="title">
				<?php _e('HTTP Headers', 'wp-optimize'); ?>
			</h3>
			<p class="wpo_min-bold-green">
				<?php _e('Preconnect Headers: This will add link headers to your HTTP response to instruct the browser to preconnect to other domains (e.g.: fonts, images, videos, etc)', 'wp-optimize'); ?>
			</p>
			<p class="wpo_min-bold-green">
				<?php _e('Preload Headers: Use this for preloading specific, high priority resources that exist across all of your pages.', 'wp-optimize'); ?>
			</p>
			<p class="wpo_min-bold-green">
				<?php _e('Note: Some servers do not support http push or headers. If you get a server error: a) rename the plugin directory via (S)FTP or your hosting control panel, b) go to your plugins page (plugin will be disabled on access), c) rename it back and d) activate it back (reset to default settings).', 'wp-optimize'); ?>	
			</p>

			<h3><?php _e('Preconnect Headers', 'wp-optimize'); ?></h3>
			<div class="wpo-fieldgroup">
				<fieldset>
					<legend class="screen-reader-text">
						<?php _e('Preconnect', 'wp-optimize'); ?>
					</legend>
					<label for="hpreconnect">
						<span class="wpo_min-label-pad">
							<?php _e('Use only the strictly minimum necessary domain names, (CDN or frequent embeds):', 'wp-optimize'); ?>
						</span>
					</label>
					<textarea
						name="hpreconnect"
						rows="7"
						cols="50"
						id="hpreconnect"
						class="large-text code"
						placeholder="https://cdn.example.com"
						disabled
					><?php echo $wpo_minify_options['hpreconnect']; ?></textarea>
					<p>
						<?php _e('Use the complete scheme (http:// or https://) followed by the domain name only (no file paths).', 'wp-optimize'); ?>
					</p>
					<p>
						<?php _e('Examples: https://fonts.googleapis.com, https://fonts.gstatic.com', 'wp-optimize'); ?>
					</p>
				</fieldset>
			</div>
	
			<h3><?php _e('Preload Headers', 'wp-optimize'); ?></h3>
			<div class="wpo-fieldgroup">
				<fieldset>
					<legend class="screen-reader-text">
					<?php _e('Preload Headers', 'wp-optimize'); ?>
					</legend>
					<label for="hpreload">
						<span class="wpo_min-label-pad">
							<?php _e('Insert your "complete PHP header code" here:', 'wp-optimize'); ?>
						</span>
					</label>
					<textarea
						name="hpreload"
						rows="7"
						cols="50"
						id="hpreload"
						class="large-text code"
						disabled
						placeholder="Link: &lt;https://cdn.example.com/s/font/v15/somefile.woff&gt;; rel=preload; as=font; crossorigin"
					><?php echo $wpo_minify_options['hpreload']; ?></textarea>
					<p>
						<?php _e('Example of a "complete PHP header code" to paste above', 'wp-optmize'); ?>
					</p>
					<p>
						<?php _e('Link: &lt;https://fonts.gstatic.com/s/opensans/v15/mem8YaGs126MiZpBA-UFVZ0d.woff&gt;; rel=preload; as=font; crossorigin</p>', 'wp-optmize'); ?>
					<p>
						<?php _e('Link: &lt;https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/fonts/fontawesome-webfont.woff2&gt;; rel=preload; as=font; crossorigin</p>', 'wp-optmize'); ?>
				</fieldset>
			</div>
	
			<h3><?php _e('External URLs to merge', 'wp-optimize'); ?></h3>
			<div class="wpo-fieldgroup">
				<fieldset>
					<label for="merge_allowed_urls">
						<?php _e('List of external domains that can be fetched and merged:', 'wp-optimize'); ?>
						<span tabindex="0" data-tooltip="<?php esc_attr_e('Add any external "domain" for JavaScript or CSS files that can be fetched and merged by WP-Optimize, e.g.: cdnjs.cloudflare.com', 'wp-optimize');?>"><span class="dashicons dashicons-editor-help"></span> </span>
					</label>
					<textarea
						name="merge_allowed_urls"
						rows="7"
						cols="50"
						id="merge_allowed_urls"
						class="large-text code"
						placeholder="<?php esc_attr_e('e.g.: example.com', 'wp-optimize'); ?>"
					><?php echo $wpo_minify_options['merge_allowed_urls']; ?></textarea>
				</fieldset>
			</div>
	
			<h1><?php _e('CDN Options', 'wp-optimize'); ?></h1>
			<p class="wpo_min-bold-green">
				<?php printf(__('When the "Enable defer on processed JavaScript files" option is enabled, JavaScript and CSS files will not be loaded from the CDN due to %scompatibility%s reasons.', 'wp-optimize'), '<a target="_blank" href="https://www.chromestatus.com/feature/5718547946799104">', '</a>'); ?>
				<?php _e('However, you can define a CDN Domain below, in order to use it for all of the static assets "inside" your CSS and JS files.', 'wp-optimize'); ?>
			</p>
	
			<h3><?php _e('Your CDN domain', 'wp-optimize'); ?></h3>
			<div class="wpo-fieldgroup">
				<fieldset>
					<label for="cdn_url">
						<p>
							<input
								type="text"
								name="cdn_url"
								id="cdn_url"
								value="<?php echo isset($wpo_minify_options['cdn_url']) ? $wpo_minify_options['cdn_url'] : ''; ?>"
								size="80"
							/>
						</p>
						<p>
							<?php _e('Will rewrite the static assets urls inside WP-O Minify-merged files to your CDN domain. Usage: cdn.example.com', 'wp-optimize'); ?>
						</p>
					</label>
				</fieldset>
			</div>
	
			<h3><?php _e('Force the CDN Usage', 'wp-optimize'); ?></h3>
			<div class="wpo-fieldgroup">
				<p class="wpo_min-bold-green wpo_min-rowintro">
					<?php _e('If you force this, your JS files may not load for certain slow internet users on Google Chrome.', 'wp-optimize'); ?>
				</p>
				<fieldset>
					<label for="cdn_force">
						<input
							name="cdn_force"
							type="checkbox"
							id="cdn_force"
							value="1"
							<?php echo checked($wpo_minify_options['cdn_force']); ?>
						>
						<?php _e('I know what I\'m doing...', 'wp-optimize'); ?>
						<span tabindex="0" data-tooltip="<?php esc_attr_e('Load my JS files from the CDN, even when "defer for Pagespeed Insights" is enabled', 'wp-optimize');?>"><span class="dashicons dashicons-editor-help"></span> </span>
					</label>
				</fieldset>
			</div>
	<?php endif; ?>

		<p class="submit">
			<input
				class="wp-optimize-save-minify-settings button button-primary"
				type="submit"
				value="<?php esc_attr_e('Save settings', 'wp-optimize'); ?>"
			>
			<img class="wpo_spinner" src="<?php echo esc_attr(admin_url('images/spinner-2x.gif')); ?>" alt="...">
			<span class="save-done dashicons dashicons-yes display-none"></span>
		</p>
	</form>
</div>
