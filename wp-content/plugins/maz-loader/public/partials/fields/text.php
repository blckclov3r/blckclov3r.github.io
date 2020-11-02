<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
$fieldData = new MZLDR_Registry($this->field_data);
// padding
$padding = array(
	'top'    => esc_attr( $fieldData->get( 'padding_top' ) ),
	'right'  => esc_attr( $fieldData->get( 'padding_right' ) ),
	'bottom' => esc_attr( $fieldData->get( 'padding_bottom' ) ),
	'left'   => esc_attr( $fieldData->get( 'padding_left' ) ),
);
$padding_value = implode(
	' ',
	array_map(
		function( $k, $v ) {
			$val = ! empty( $v ) ? $v : 0;

			return esc_attr( $val ) . esc_attr( $this->field_data->padding_type );
		},
		array_keys( $padding ),
		$padding
	)
);

// margin
$margin = array(
	'top'    => esc_attr( $fieldData->get( 'margin_top' ) ),
	'right'  => esc_attr( $fieldData->get( 'margin_right' ) ),
	'bottom' => esc_attr( $fieldData->get( 'margin_bottom' ) ),
	'left'   => esc_attr( $fieldData->get( 'margin_left' ) ),
);
$margin_value = implode(
	' ',
	array_map(
		function( $k, $v ) {
			$val = ! empty( $v ) ? $v : 0;

			return esc_attr( $val ) . esc_attr( $this->field_data->margin_type );
		},
		array_keys( $margin ),
		$margin
	)
);

$style_atts = array(
	'font-size'  => !empty( $fieldData->get( 'size' ) ) ? esc_attr( $fieldData->get( 'size' ) ) . 'px' : '',
	'color'      => esc_attr( $fieldData->get( 'color' ) ),
);
if ( ! empty( $margin_value ) ) {
	$style_atts['margin'] = esc_attr( $margin_value );
}
$style_atts = MZLDR_Helper::getCSSAttributes( $style_atts );
$inner_style_atts = array(
	'background' => esc_attr( $fieldData->get( 'background' ) )
);
if ( ! empty( $padding_value ) ) {
	$inner_style_atts['padding'] = esc_attr( $padding_value );
}
$inner_style_atts = MZLDR_Helper::getCSSAttributes( $inner_style_atts );

// classes
$field_classes = [];

// animation
$field_animation = '';
if (!empty( $fieldData->get( 'animation' ) ) && $fieldData->get( 'animation' ) != 'none') {
	$field_animation = 'data-field-animation="' . $fieldData->get( 'animation' ) . '"';
	$field_classes[] = '';
	$field_classes[] = 'has-animation';
}
?>
<div
	class="mazloader-item-text<?php echo implode(' ', $field_classes); ?>"
	<?php echo $field_animation; ?>
	<?php echo $style_atts; ?>
>
	<div <?php echo $inner_style_atts; ?>><?php echo esc_html( stripslashes( $fieldData->get( 'text' ) ) ); ?></div>
</div>
