<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
?>
<div class="mazloader-draggable-field-item-outer">
	<h3 class="title mazloader-draggable-field-item" data-field-id="<?php echo esc_attr( $this->id ); ?>" draggable="true">
		<?php echo MZLDR_Fields_Helper::camelize($this->type); ?> <?php _e( 'Field', 'maz-loader' ); ?> (<?php echo esc_html( $this->id ); ?>)
		<?php require MZLDR_ADMIN_PATH . 'partials/loader/wrapper/actions.php'; ?>
	</h3>
	<div class="settings"<?php echo ( $edit == true ) ? ' style="display:none;"' : ''; ?> data-field-id="<?php echo esc_attr( $this->id ); ?>">
