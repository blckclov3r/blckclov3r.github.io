<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
?>
<input type="hidden" class="<?php echo esc_attr( $this->get_field_data( 'class' ) ); ?>" name="<?php echo esc_attr( $this->get_field_data( 'name' ) ); ?>" value="<?php echo esc_attr( $this->get_field_data( 'value' ) ); ?>" />
