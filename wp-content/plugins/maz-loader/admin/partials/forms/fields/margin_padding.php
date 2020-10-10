<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
$extra_classes = ! empty( $this->get_field_data( 'class' ) ) ? ' ' . implode( ' ', $this->get_field_data( 'class' ) ) : '';
$bind_atts     = MZLDR_Helper::getHTMLAttributes( $this->get_field_data( 'bind' ) );
$item_top      = $this->get_field_data( 'item_top' );
$item_right    = $this->get_field_data( 'item_right' );
$item_bottom   = $this->get_field_data( 'item_bottom' );
$item_left     = $this->get_field_data( 'item_left' );
$item_link     = $this->get_field_data( 'item_link' );
$item_type     = $this->get_field_data( 'item_type' );
?>
<div class="mzldr-margin-padding-item"data-field-id="<?php echo esc_attr( $this->get_field_data( 'field_id' ) ); ?>" id="<?php echo esc_attr( $this->get_field_data( 'id' ) ); ?>">
	<div class="mp_items">
		<div class="mp_item">
			<input type="text"<?php echo $bind_atts; ?> value="<?php echo esc_attr( $item_top ); ?>" name="<?php echo esc_attr( $this->get_field_data( 'name_top' ) ); ?>" data-field-id="<?php echo esc_attr( $this->get_field_data( 'field_id' ) ); ?>" class="mp-input-item top" />
			<span class="label"><?php _e( 'TOP', 'maz-loader' ); ?></span>
		</div>
		<div class="mp_item">
			<input type="text"<?php echo $bind_atts; ?> value="<?php echo esc_attr( $item_right ); ?>" name="<?php echo esc_attr( $this->get_field_data( 'name_right' ) ); ?>" data-field-id="<?php echo esc_attr( $this->get_field_data( 'field_id' ) ); ?>" class="mp-input-item right" />
			<span class="label"><?php _e( 'RIGHT', 'maz-loader' ); ?></span>
		</div>
		<div class="mp_item">
			<input type="text"<?php echo $bind_atts; ?> value="<?php echo esc_attr( $item_bottom ); ?>" name="<?php echo esc_attr( $this->get_field_data( 'name_bottom' ) ); ?>" data-field-id="<?php echo esc_attr( $this->get_field_data( 'field_id' ) ); ?>" class="mp-input-item bottom" />
			<span class="label"><?php _e( 'BOTTOM', 'maz-loader' ); ?></span>
		</div>
		<div class="mp_item">
			<input type="text"<?php echo $bind_atts; ?> value="<?php echo esc_attr( $item_left ); ?>" name="<?php echo esc_attr( $this->get_field_data( 'name_left' ) ); ?>" data-field-id="<?php echo esc_attr( $this->get_field_data( 'field_id' ) ); ?>" class="mp-input-item left" />
			<span class="label"><?php _e( 'LEFT', 'maz-loader' ); ?></span>
		</div>
		<div class="mp_item link-button<?php echo ( $item_link == 'all' ) ? esc_attr( ' is-active' ) : ''; ?>">
			<span class="dashicons dashicons-admin-links icon"></span>
			<input type="hidden" value="<?php echo esc_attr( $item_link ); ?>" name="<?php echo esc_attr( $this->get_field_data( 'name_link' ) ); ?>">
		</div>
		<div class="type">
			<a href="#" class="mzldr-mp-type-btn is-active" data-type="px"><?php _e( 'PX', 'maz-loader' ); ?></a>
			<a href="#" class="mzldr-mp-type-btn" data-type="em"><?php _e( 'EM', 'maz-loader' ); ?></a>
			<a href="#" class="mzldr-mp-type-btn" data-type="%"><?php _e( '%', 'maz-loader' ); ?></a>
			<input type="hidden" value="<?php echo esc_attr( $item_type ); ?>" name="<?php echo esc_attr( $this->get_field_data( 'name_type' ) ); ?>" />
		</div>
	</div>
</div>
