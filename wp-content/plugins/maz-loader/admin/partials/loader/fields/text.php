<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
// padding
$padding       = array(
	'top'    => esc_attr( $this->padding_top ),
	'right'  => esc_attr( $this->padding_right ),
	'bottom' => esc_attr( $this->padding_bottom ),
	'left'   => esc_attr( $this->padding_left ),
);
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

// margin
$margin       = array(
	'top'    => esc_attr( $this->margin_top ),
	'right'  => esc_attr( $this->margin_right ),
	'bottom' => esc_attr( $this->margin_bottom ),
	'left'   => esc_attr( $this->margin_left ),
);
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

$style_atts = array(
	'font-size'  => esc_attr( $this->size ) . 'px',
	'background' => esc_attr( $this->background ),
	'color'      => esc_attr( $this->color ),
);
if ( ! empty( $padding_value ) ) {
	$style_atts['padding'] = esc_attr( $padding_value );
}
if ( ! empty( $margin_value ) ) {
	$style_atts['margin'] = esc_attr( $margin_value );
}
$style_atts = MZLDR_Helper::getCSSAttributes( $style_atts );

// animation
$field_animation = '';
if (isset($this->animation) && $this->animation != 'none') {
	$field_animation = 'data-field-animation="' . $this->animation . '"';
}
?>
<?php require MZLDR_ADMIN_PATH . 'partials/loader/wrapper/start.php'; ?>
<div
	class="mazloader-item-text mazloader-inner-item"
	<?php echo $field_animation; ?>
	<?php echo $style_atts; ?>
>
	<?php echo esc_html( stripslashes( $this->text ) ); ?>
</div>
<?php require MZLDR_ADMIN_PATH . 'partials/loader/wrapper/end.php'; ?>
