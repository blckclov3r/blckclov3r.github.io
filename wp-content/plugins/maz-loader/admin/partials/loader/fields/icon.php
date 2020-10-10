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
	'background' => esc_attr( $this->background ),
);
if ( ! empty( $padding_value ) ) {
	$style_atts['padding'] = esc_attr( $padding_value );
}
if ( ! empty( $margin_value ) ) {
	$style_atts['margin'] = esc_attr( $margin_value );
}
$style_atts = MZLDR_Helper::getCSSAttributes( $style_atts );

$icon_value     = $this->icon_value;
$icon_tab_value = $this->icon_tab_value;

// animation
$field_animation = '';
if (isset($this->animation) && $this->animation != 'none') {
	$field_animation = 'data-field-animation="' . $this->animation . '"';
}
?>
<?php require MZLDR_ADMIN_PATH . 'partials/loader/wrapper/start.php'; ?>
<div
	class="mazloader-item-icon mazloader-inner-item"
	<?php echo $field_animation; ?>
	<?php echo $style_atts; ?>
>
	<?php
	if ( in_array( $icon_tab_value, array( 'svg', 'gif' ) ) ) {
		echo '<img src="' . MZLDR_PUBLIC_MEDIA_URL . 'img/' . esc_attr( $icon_tab_value ) . '/' . esc_attr( $icon_value ) . '.' . esc_attr( $icon_tab_value ) . '" alt="' . __( 'preloader image', 'maz-loader' ) . '" />';
	} elseif ( $icon_tab_value == 'css-other' ) {
		$data = MZLDR_Icon_Field::getCSSOtherIconData( $icon_value );

		echo '<div class="mzldr-css-icon ' . esc_attr( $icon_tab_value ) . ' style' . esc_attr( $icon_value ) . '">';

		if ( isset( $data['external_file'] ) ) {
			if ( isset( $data['type'] ) && $data['type'] == 'svg' ) {
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
<?php require MZLDR_ADMIN_PATH . 'partials/loader/wrapper/end.php'; ?>
