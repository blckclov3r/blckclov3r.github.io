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
$margin       = array(
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

$style_atts = array();
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

$icon_value = $fieldData->get( 'icon' );
$icon_tab_value = $fieldData->get( 'icon_tab' );

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
	class="mazloader-item-icon<?php echo implode(' ', $field_classes); ?>"
	<?php echo $field_animation; ?>
	<?php echo $style_atts; ?>
>
	<div <?php echo $inner_style_atts; ?>>
	<?php
	if (in_array($icon_tab_value, ['svg', 'gif'])) {
		echo '<img src="' . MZLDR_PUBLIC_MEDIA_URL . 'img/' . esc_attr( $icon_tab_value ) . '/' . esc_attr( $icon_value ) . '.' . esc_attr( $icon_tab_value ) . '" alt="' . __( 'preloader image', 'maz-loader' ). '" />';
	} elseif ($icon_tab_value == 'css-other') {
		$data = MZLDR_Icon_Field::getCSSOtherIconData($icon_value);

		echo '<div class="mzldr-css-icon ' . esc_attr( $icon_tab_value ) . ' style' . esc_attr( $icon_value ) . '">';

		if (isset($data['external_file'])) {
			if (isset($data['type']) && $data['type'] == 'svg') {
				echo $data['html'];
			} else {
			echo '<img src="' . MZLDR_PUBLIC_MEDIA_URL . 'img/css_other/' . esc_attr( $data['filename'] ) . '" alt="' . __( 'preloader image', 'maz-loader' ) . '" />';
			}
		} else {
			echo $data['css'];
		}

		echo '</div>';
	}
	?>
	</div>
</div>
