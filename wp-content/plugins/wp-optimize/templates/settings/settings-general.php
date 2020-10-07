<?php if (!defined('WPO_VERSION')) die('No direct access allowed'); ?>

<h3 class="wpo-first-child"><?php _e('General settings', 'wp-optimize'); ?></h3>
<div class="wpo-fieldgroup">
	<p>
		<label>
			<input name="enable-admin-bar" id="enable-admin-bar" type="checkbox" value ="true" <?php echo ($options->get_option('enable-admin-menu', 'false') == 'true') ? 'checked="checked"' : ''; ?> />
			<?php _e('Enable admin bar link', 'wp-optimize'); ?>
		</label>
		<br>
		<small><?php _e('This option will put an WP-Optimize link on the top admin bar (default is off). Requires a second page refresh after saving the settings.', 'wp-optimize'); ?></small>
	</p>
</div>