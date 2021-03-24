<?php if (!defined('WPO_VERSION')) die('No direct access allowed'); ?>
<h5><?php echo esc_html($log->header); ?></h5>
<ul><?php
foreach ((array) $log->files as $handle => $file) {
	$file_path = untrailingslashit(get_home_path()) . $file->url;
	$file_size = file_exists($file_path) ? ' (' . WP_Optimize()->format_size(@filesize($file_path)) . ')' : ''; // phpcs:ignore Generic.PHP.NoSilencedErrors.Discouraged

	echo '<li'.($file->success ? '' : ' class="failed"').'><span class="wpo_min_file_url"><a href="'.esc_url(get_home_url().$file->url).'" target="_blank">'.htmlspecialchars($file->url).'</a>'.$file_size.'</span>';
	if (property_exists($file, 'debug')) echo '<span class="wpo_min_file_debug">'.htmlspecialchars($file->debug).'</span>';
	echo '</li>';
}
?>
</ul>