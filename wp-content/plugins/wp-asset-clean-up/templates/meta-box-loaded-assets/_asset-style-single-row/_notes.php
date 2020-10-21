<?php
/*
 * The file is included from /templates/meta-box-loaded-assets/_asset-style-single-row.php
*/

if (! isset($data)) {
	exit; // no direct access
}

$handleNote = (isset($data['handle_notes']['styles'][$data['row']['obj']->handle]) && $data['handle_notes']['styles'][$data['row']['obj']->handle])
	? $data['handle_notes']['styles'][$data['row']['obj']->handle]
	: false;
?>
<div class="wpacu-handle-notes">
	<?php if (! $handleNote) { ?>
		<p><small>No notes have been added about this stylesheet file (e.g. why you unloaded it or decided to keep it loaded) &#10230; <a data-handle="<?php echo $data['row']['obj']->handle; ?>" href="#" class="wpacu-add-handle-note wpacu-for-style"><span class="dashicons dashicons-welcome-write-blog"></span> <label for="wpacu_handle_note_<?php echo $data['row']['obj']->handle; ?>">Add Note</label></a></small></p>
	<?php } else { ?>
		<p><small>The following note has been added for this stylesheet file (<em>to have it removed on update, just leave the text area empty</em>):</small></p>
	<?php } ?>
	<div <?php if ($handleNote) { echo 'style="display: block;"'; } ?>
		data-style-handle="<?php echo $data['row']['obj']->handle; ?>"
		class="wpacu-handle-notes-field">
                <textarea id="wpacu_handle_note_style_<?php echo $data['row']['obj']->handle; ?>"
                          style="min-height: 45px;"
                          data-wpacu-adapt-height="1"
                          data-wpacu-is-empty-on-page-load="<?php echo (! $handleNote) ? 'true' : 'false'; ?>"
                          <?php if (! $handleNote) { echo 'disabled="disabled"'; } ?>
                          placeholder="<?php echo esc_attr('Add your note here about this stylesheet file', 'wp-asset-clean-up'); ?>"
                          name="wpacu_handle_notes[styles][<?php echo $data['row']['obj']->handle; ?>]"><?php echo esc_textarea($handleNote); ?></textarea>
	</div>
</div>