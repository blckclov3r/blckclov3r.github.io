<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
$hide_label = $this->get_field_data( 'hide_label' );
?>
<div class="mzldr-control-group<?php echo esc_attr( $this->get_field_data( 'control_group_class' ) ); ?>">
	<div class="row">
		<?php if ( ! $hide_label ) { ?>
		<div class="mzldr-control-label col-md-12">
			<label for="<?php echo esc_attr( $this->get_field_data( 'id' ) ); ?>"><?php echo esc_html( $this->get_field_data( 'label' ) ); ?></label>
		</div>
		<?php } ?>
		<div class="mzldr-control-input col-md-12">
