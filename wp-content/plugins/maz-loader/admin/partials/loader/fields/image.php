<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
// padding
$padding = array(
	'top'    => esc_attr( $this->padding_top ),
	'right'  => esc_attr( $this->padding_right ),
	'bottom' => esc_attr( $this->padding_bottom ),
	'left'   => esc_attr( $this->padding_left ),
);
if ( ! empty( $this->padding_top ) ||
	! empty( $this->padding_right ) ||
	! empty( $this->padding_bottom ) ||
	! empty( $this->padding_left ) ) {
	$padding_value = implode(
		' ',
		array_map(
			function( $k, $v ) {
				$val = ! empty( $v ) ? $v : 0;

				return $val . esc_attr( $this->padding_type );
			},
			array_keys( $padding ),
			$padding
		)
	);
}

// margin
$margin = array(
	'top'    => esc_attr( $this->margin_top ),
	'right'  => esc_attr( $this->margin_right ),
	'bottom' => esc_attr( $this->margin_bottom ),
	'left'   => esc_attr( $this->margin_left ),
);
if ( ! empty( $this->margin_top ) ||
	! empty( $this->margin_right ) ||
	! empty( $this->margin_bottom ) ||
	! empty( $this->margin_left ) ) {
	$margin_value = implode(
		' ',
		array_map(
			function( $k, $v ) {
				$val = ! empty( $v ) ? $v : 0;

				return $val . esc_attr( $this->margin_type );
			},
			array_keys( $margin ),
			$margin
		)
	);
}

$style_atts = array(
	'width'         => esc_attr( $this->width ),
	'height'        => esc_attr( $this->height ),
	'background'    => esc_attr( $this->background ),
	'border-radius' => esc_attr( $this->border_radius ) . '%',
);
if ( ! empty( $padding_value ) ) {
	$style_atts['padding'] = $padding_value;
}
if ( ! empty( $margin_value ) ) {
	$style_atts['margin'] = $margin_value;
}
$style_atts = MZLDR_Helper::getCSSAttributes( $style_atts );
$image      = ! empty( $this->custom_url ) ? $this->custom_url : $this->image;
$image_alt  = MZLDR_WP_Helper::get_image_alt( $image );
$image_alt  = ( $image_alt ) ? $image_alt : '';

// animation
$field_animation = '';
if (isset($this->animation) && $this->animation != 'none') {
	$field_animation = 'data-field-animation="' . $this->animation . '"';
}
?>
<?php require MZLDR_ADMIN_PATH . 'partials/loader/wrapper/start.php'; ?>
<div
	class="mazloader-item-image mazloader-inner-item"
	<?php echo $field_animation; ?>
>
	<img src="<?php echo esc_attr( $image ); ?>"<?php echo $style_atts; ?> alt="<?php echo esc_attr( $image_alt ); ?>" />
</div>
<?php require MZLDR_ADMIN_PATH . 'partials/loader/wrapper/end.php'; ?>
