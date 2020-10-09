<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
$extra_classes = ! empty( $this->get_field_data( 'class' ) ) ? ' ' . implode( ' ', $this->get_field_data( 'class' ) ) : '';
$bind_atts     = MZLDR_Helper::getHTMLAttributes( $this->get_field_data( 'bind' ) );
$value         = $this->get_field_data( 'value' );
$default       = $this->get_field_data( 'default' );
$value         = $value == 'on' ? true : false;
$id = $this->get_field_data( 'id' );
$name = $this->get_field_data( 'name' );
$field_id = $this->get_field_data( 'field_id' );
?>
<span class="mzldr-toggle-switch-outer<?php echo ( $value ) ? esc_attr( ' is-checked' ) : ''; ?>">
<input
	<?php if ( ! empty( $field_id ) ) { ?>
	data-field-id="<?php echo esc_attr( $field_id ); ?>"
	<?php } ?>
	<?php if ( ! empty( $id ) ) { ?>
	id="<?php echo esc_attr( $id ); ?>"
	<?php } ?>
	<?php if ( ! empty( $name ) ) { ?>
	name="<?php echo esc_attr( $name ); ?>"
	<?php } ?>
	type="checkbox"<?php echo $bind_atts; ?>
	value="<?php echo $value ? esc_attr( 'on' ) : esc_attr( 'off' ); ?>"<?php echo ( $value ) ? esc_attr( ' checked="checked"' ) : ''; ?>
	class="mzldr-control-input-item<?php echo esc_attr( $extra_classes ); ?>" />
	<label for="switch"></label>
</span>
