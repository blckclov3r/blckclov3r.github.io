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

$style_atts = array(
	'font-size'  => !empty( $fieldData->get( 'size' ) ) ? esc_attr( $fieldData->get( 'size' ) ) . 'px' : '',
	'color'      => esc_attr( $fieldData->get( 'color' ) ),
);
$style_atts = MZLDR_Helper::getCSSAttributes( $style_atts );
$inner_style_atts = [];
if ( ! empty( $padding_value ) ) {
	$inner_style_atts['padding'] = esc_attr( $padding_value );
}
if ( ! empty( $margin_value ) ) {
	$inner_style_atts['margin'] = esc_attr( $margin_value );
}
$inner_style_atts = MZLDR_Helper::getCSSAttributes( $inner_style_atts );

$percentage_counter_classes = ($fieldData->get( 'show_percentage' ) == 'no') ? ' is-hidden' : '';

$outer_bar_atts = [
	'width' => esc_attr( $fieldData->get( 'bar_width' ) ) . '%',
	'background' => esc_attr( $fieldData->get( 'background' ) )
];
$outer_bar_atts = MZLDR_Helper::getCSSAttributes( $outer_bar_atts );

$bar_atts = [
	'background' => esc_attr( $fieldData->get( 'bar_color' ) ),
	'height' => esc_attr( $fieldData->get( 'bar_height' ) ) . 'px'
];
$bar_atts = MZLDR_Helper::getCSSAttributes( $bar_atts );

$data_duration = $fieldData->get( 'loader_settings.minimum_loading_time' ) + $fieldData->get( 'loader_settings.duration' );
$data_duration = ($data_duration / 1000) > 0 ? $data_duration / 1000 : 1;

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
	class="mazloader-item-percentage-counter mazloader-item-progress-bar mazloader-item-text pos_<?php echo esc_attr( $fieldData->get( 'position' ) ); ?><?php echo implode(' ', $field_classes); ?>"
	<?php echo $field_animation; ?>
	<?php echo $style_atts; ?>
>
	<div <?php echo $inner_style_atts; ?>>
		<div class="progress-bar-item-outer"<?php echo $outer_bar_atts; ?>><div class="progress-bar-item"<?php echo $bar_atts; ?>></div></div>
		<div class="mzldr-percentage-counter mzldr-progress-bar<?php echo esc_attr($percentage_counter_classes); ?>" data-duration="<?php echo $data_duration; ?>">1</div>
	</div>
</div>