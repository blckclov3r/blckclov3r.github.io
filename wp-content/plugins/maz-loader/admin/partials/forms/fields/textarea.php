<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
$noescape = (!empty($this->get_field_data('no_escape'))) ? $this->get_field_data('no_escape') : false;
$value = $this->get_field_data( 'value' );
$extra_classes = ! empty( $this->get_field_data( 'class' ) ) ? ' ' . implode( ' ', $this->get_field_data( 'class' ) ) : '';
$bind_atts = MZLDR_Helper::getHTMLAttributes( $this->get_field_data( 'bind' ) );
?>
<textarea <?php echo $bind_atts; ?>data-field-id="<?php echo esc_attr( $this->get_field_data( 'field_id' ) ); ?>" id="<?php echo esc_attr( $this->get_field_data( 'id' ) ); ?>" name="<?php echo esc_attr( $this->get_field_data( 'name' ) ); ?>" placeholder="<?php echo esc_attr( $this->get_field_data( 'placeholder' ) ); ?>" class="mzldr-control-input-item<?php echo esc_attr( $extra_classes ); ?>" rows="<?php echo esc_attr( $this->get_field_data( 'rows' ) ); ?>"><?php echo $value; ?></textarea>
