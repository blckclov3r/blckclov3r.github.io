<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
$extra_classes  = ! empty( $this->get_field_data( 'class' ) ) ? ' ' . implode( ' ', $this->get_field_data( 'class' ) ) : '';
$bind_atts      = MZLDR_Helper::getHTMLAttributes( $this->get_field_data( 'bind' ) );
$extra_atts     = MZLDR_Helper::getHTMLAttributes( $this->get_field_data( 'extra_atts' ) );
$values         = ! empty( $this->get_field_data( 'values' ) ) ? $this->get_field_data( 'values' ) : array();
$default        = ! empty( $this->get_field_data( 'default' ) ) ? $this->get_field_data( 'default' ) : 'center';
$selected_value = ! empty( $this->get_field_data( 'value' ) ) ? $this->get_field_data( 'value' ) : $default;
$id = $this->get_field_data( 'id' );
$name = $this->get_field_data( 'name' );
$field_id = $this->get_field_data( 'field_id' );
?>
<select
	<?php if ( ! empty( $field_id ) ) { ?>
	data-field-id="<?php echo esc_attr( $field_id ); ?>"
	<?php } ?>
	<?php if ( ! empty( $id ) ) { ?>
	id="<?php echo esc_attr( $id ); ?>"
	<?php } ?>
	<?php if ( ! empty( $name ) ) { ?>
	name="<?php echo esc_attr( $name ); ?>"
	<?php } ?>
	class="mzldr-control-input-item<?php echo esc_attr( $extra_classes ); ?>"
	<?php echo $bind_atts; ?>
	<?php echo $extra_atts; ?>
>
<?php
foreach ( $values as $key => $value ) {
	$selected_att = ( $key == $selected_value ) ? ' selected="selected"' : '';
	?>
	<option value="<?php echo esc_attr( $key ); ?>"<?php echo esc_attr( $selected_att ); ?>><?php echo esc_html( $value ); ?></option>
	<?php
}
?>
</select>
