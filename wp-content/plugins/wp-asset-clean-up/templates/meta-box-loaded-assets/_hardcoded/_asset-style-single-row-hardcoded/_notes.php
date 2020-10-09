<?php
/*
 * The file is included from /templates/meta-box-loaded-assets/_asset-style-single-row.php
*/
if (! isset($data, $tagType)) {
	exit; // no direct access
}
?>
<div class="wpacu-handle-notes">
	<p><small>No notes have been added about this hardcoded <?php echo $tagType; ?> tag (e.g. why you unloaded it or decided to keep it loaded) &#10230; <a data-handle="<?php echo $data['row']['obj']->handle; ?>" href="#" class="wpacu-manage-hardcoded-assets-requires-pro-popup wpacu-add-handle-note wpacu-for-style"><span class="dashicons dashicons-welcome-write-blog"></span> <label for="wpacu_handle_note_<?php echo $data['row']['obj']->handle; ?>">Add Note</label></a></small></p>
</div>