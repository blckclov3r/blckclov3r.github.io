<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
$bind_atts  = MZLDR_Helper::getHTMLAttributes( $this->get_field_data( 'bind' ) );
$name       = $this->get_field_data( 'name' );
$custom_url = $this->get_field_data( 'custom_url' );
$image      = ! empty( $custom_url ) ? $custom_url : $this->get_field_data( 'image' );
$image_alt  = MZLDR_WP_Helper::get_image_alt( $image );
$image_alt  = ( $image_alt ) ? $image_alt : '';
?>
<div class="mzldr-image-input-field">
	<input type="button" class="mzldr-button mzldr-image-upload-button" value="<?php _e( 'Update Image', 'maz-loader' ); ?>"<?php echo $bind_atts; ?> />
	<input type="button" class="mzldr-button mzldr-image-field-reset-button" value="<?php _e( 'Reset', 'maz-loader' ); ?>" />
	<div class="mlzdr-background-image-preview<?php echo ( ! empty( $image ) ) ? esc_attr( ' is-visible' ) : ''; ?>">
		<img src="<?php echo esc_attr( $image ); ?>" alt="<?php echo esc_attr( $image_alt ); ?>" data-field-id="<?php echo esc_attr( $this->get_field_data( 'field_id' ) ); ?>" />
	</div>
	<input type="hidden" value="<?php echo esc_attr( $image ); ?>" name="<?php echo esc_attr( $name ); ?>" />
</div>
