<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
$fieldData = new MZLDR_Registry($this->field_data);
// padding
$padding       = array(
	'top'    => esc_attr( $fieldData->get( 'padding_top' ) ),
	'right'  => esc_attr( $fieldData->get( 'padding_right' ) ),
	'bottom' => esc_attr( $fieldData->get( 'padding_bottom' ) ),
	'left'   => esc_attr( $fieldData->get( 'padding_left' ) ),
);
if (!empty( $fieldData->get( 'padding_top' ) ) ||
	!empty( $fieldData->get( 'padding_right' ) ) ||
	!empty( $fieldData->get( 'padding_bottom' ) ) ||
	!empty( $fieldData->get( 'padding_left' ) )) {
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
}

// margin
$margin       = array(
	'top'    => esc_attr( $fieldData->get( 'margin_top' ) ),
	'right'  => esc_attr( $fieldData->get( 'margin_right' ) ),
	'bottom' => esc_attr( $fieldData->get( 'margin_bottom' ) ),
	'left'   => esc_attr( $fieldData->get( 'margin_left' ) ),
);
if (!empty( $fieldData->get( 'margin_top' ) ) ||
	!empty( $fieldData->get( 'margin_right' ) ) ||
	!empty( $fieldData->get( 'margin_bottom' ) ) ||
	!empty( $fieldData->get( 'margin_left' ) )) {
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
}

$style_atts = array(
	'width'         => esc_attr( $fieldData->get( 'width' ) ),
	'height'        => esc_attr( $fieldData->get( 'height' ) ),
	'background'    => esc_attr( $fieldData->get( 'background' ) ),
	'border-radius' => esc_attr( $fieldData->get( 'border_radius' ) ) . '%',
);
if ( ! empty( $padding_value ) ) {
	$style_atts['padding'] = esc_attr( $padding_value );
}
if ( ! empty( $margin_value ) ) {
	$style_atts['margin'] = esc_attr( $margin_value );
}
$style_atts = MZLDR_Helper::getCSSAttributes( $style_atts );
$src        = ! empty( $fieldData->get( 'custom_url' ) ) ? $fieldData->get( 'custom_url' ) : $fieldData->get( 'image' );
$image_alt = MZLDR_WP_Helper::get_image_alt($src);
$image_alt = ($image_alt) ? $image_alt : '';

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
	class="mazloader-item-image<?php echo implode(' ', $field_classes); ?>"
	<?php echo $field_animation; ?>
>
	<img src="<?php echo esc_attr( $src ); ?>"<?php echo $style_atts; ?> alt="<?php echo esc_attr( $image_alt ); ?>" />
</div>
