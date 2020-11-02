<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
$extra_classes = ! empty( $this->get_field_data( 'class' ) ) ? ' ' . implode( ' ', $this->get_field_data( 'class' ) ) : '';
$extra_atts    = MZLDR_Helper::getHTMLAttributes( $this->get_field_data( 'extra_atts' ) );
$bind_atts     = MZLDR_Helper::getHTMLAttributes( $this->get_field_data( 'bind' ) );
$id = $this->get_field_data( 'id' );
$name = $this->get_field_data( 'name' );
$field_id = $this->get_field_data( 'field_id' );
?>
<input
	type="text"
	<?php echo $extra_atts; ?>
	<?php echo $bind_atts; ?>
	<?php if ( ! empty( $field_id ) ) { ?>
	data-field-id="<?php echo esc_attr( $field_id ); ?>"
	<?php } ?>
	<?php if ( ! empty( $id ) ) { ?>
	id="<?php echo esc_attr( $id ); ?>"
	<?php } ?>
	<?php if ( ! empty( $name ) ) { ?>
	name="<?php echo esc_attr( $name ); ?>"
	<?php } ?>
	value="<?php echo esc_attr( $this->get_field_data( 'value' ) ); ?>"
	data-alpha="true"
	placeholder="<?php echo esc_attr( $this->get_field_data( 'placeholder' ) ); ?>"
	class="color-picker mzldr-control-color-picker-item<?php echo esc_attr( $extra_classes ); ?>"
/>
