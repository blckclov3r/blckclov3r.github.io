<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
$extra_classes = ! empty( $this->get_field_data( 'class' ) ) ? ' ' . implode( ' ', $this->get_field_data( 'class' ) ) : '';
$bind_atts     = MZLDR_Helper::getHTMLAttributes( $this->get_field_data( 'bind' ) );
$extra_atts	   = MZLDR_Helper::getHTMLAttributes( $this->get_field_data( 'extra_atts' ) );
$id = $this->get_field_data( 'id' );
$name = $this->get_field_data( 'name' );
$field_id = $this->get_field_data( 'field_id' );
?>
<input
	type="text"
	<?php if ( ! empty( $field_id ) ) { ?>
	data-field-id="<?php echo esc_attr( $field_id ); ?>"
	<?php } ?>
	<?php if ( ! empty( $id ) ) { ?>
	id="<?php echo esc_attr( $id ); ?>"
	<?php } ?>
	<?php if ( ! empty( $name ) ) { ?>
	name="<?php echo esc_attr( $name ); ?>"
	<?php } ?>
	value="<?php echo esc_attr( stripslashes( $this->get_field_data( 'value' ) ) ); ?>"
	placeholder="<?php echo esc_attr( $this->get_field_data( 'placeholder' ) ); ?>"
	class="mzldr-control-input-item<?php echo esc_attr( $extra_classes ); ?>"
	<?php echo $bind_atts; ?>
	<?php echo $extra_atts; ?>
/>
