<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
?>
<!-- Field Item Actions -->
<div class="field-item-actions">
	<a href="#" data-action="duplicate" class="duplicate-field-item-btn field-action-item" data-field-id="<?php echo esc_attr( $this->id ); ?>"><span class="dashicons dashicons-admin-page"></span></a>
	<a href="#" data-action="delete" class="delete-field-item-btn field-action-item" data-field-id="<?php echo esc_attr( $this->id ); ?>"><span class="dashicons dashicons-no-alt"></span></a>
</div>
<!-- /Field Item Actions -->
