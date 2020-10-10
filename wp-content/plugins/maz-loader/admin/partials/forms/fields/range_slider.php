<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
$extra_classes = ! empty( $this->get_field_data( 'class' ) ) ? ' ' . implode( ' ', $this->get_field_data( 'class' ) ) : '';
$bind_atts     = MZLDR_Helper::getHTMLAttributes( $this->get_field_data( 'bind' ) );
$value         = $this->get_field_data( 'value' );
$max_range     = $this->get_field_data( 'max_range' );
$percentage    = ( ( $value - 0 ) * 100 ) / ( $max_range - 0 );
?>
<div class="mzldr-range-slider-item<?php echo esc_attr( $extra_classes ); ?>"<?php echo $bind_atts; ?>data-field-id="<?php echo esc_attr( $this->get_field_data( 'field_id' ) ); ?>" id="<?php echo esc_attr( $this->get_field_data( 'id' ) ); ?>" data-value="<?php echo esc_attr( $value ); ?>" data-range-slider-type="<?php echo esc_attr( $this->get_field_data( 'slider_type' ) ); ?>" data-max="<?php echo esc_attr( $max_range ); ?>">
	<button type="button" style="margin-left: <?php echo ( $percentage > 94 ) ? 94 : esc_attr( $percentage ); ?>%;" class="mzldr-range-slider-button"></button>
	<span class="mzldr-range-slider-label" style="margin-left: <?php echo ( $percentage > 90 ) ? 90 : esc_attr( $percentage ); ?>%;"><?php echo esc_attr( $value ); ?><?php echo esc_html( $this->get_field_data( 'slider_type' ) ); ?></span>
	<input type="hidden" value="<?php echo esc_attr( $value ); ?>" name="<?php echo esc_attr( $this->get_field_data( 'name' ) ); ?>" class="mzldr-range-slider-hidden-value mzldr-control-range-slider-item" />
</div>
