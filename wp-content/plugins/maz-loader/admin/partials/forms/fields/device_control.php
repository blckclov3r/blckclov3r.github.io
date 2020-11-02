<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
$desktop_active = false;
$tablet_active = false;
$smartphone_active = false;
$value = $this->get_field_data('value');
$data = explode(',', $value);
$data = ($value == 'all') ? ['desktop', 'tablet', 'smartphone'] : $data;
if (!empty($value) && $value != 'all' && count($data)) {
	if (in_array('desktop', $data)) {
		$desktop_active = true;
	}
	if (in_array('tablet', $data)) {
		$tablet_active = true;
	}
	if (in_array('smartphone', $data)) {
		$smartphone_active = true;
	}
} else if ($value == 'all') {
	$desktop_active = true;
	$tablet_active = true;
	$smartphone_active = true;
}
$value = ($value == 'all') ? 'desktop,tablet,smartphone' : $value;
?>
<div class="mzldr-control-input-item mzldr-device-control-item-wrapper">
	<div class="items row">
		<div class="mzldr-device-control-item item<?php echo ($desktop_active) ? ' is-active' : ''; ?> col-md-4" data-device="desktop" title="<?php _e('Show/Hide on Desktop', 'maz-loader') ?>"><i class="dashicons dashicons-desktop"></i></div>
		<div class="mzldr-device-control-item item<?php echo ($tablet_active) ? ' is-active' : ''; ?> col-md-4" data-device="tablet" title="<?php _e('Show/Hide on Tablet', 'maz-loader') ?>"><i class="dashicons dashicons-tablet"></i></div>
		<div class="mzldr-device-control-item item<?php echo ($smartphone_active) ? ' is-active' : ''; ?> col-md-4" data-device="smartphone" title="<?php _e('Show/Hide on Mobile', 'maz-loader') ?>"><i class="dashicons dashicons-smartphone"></i></div>
	</div>
	<input
		type="hidden"
		name="<?php echo esc_attr( $this->get_field_data( 'name' ) ); ?>"
		data-field-id="<?php echo esc_attr( $this->get_field_data( 'field_id' ) ); ?>"
		class="mzldr-device-control-item-value"
		value="<?php echo esc_attr( $value ); ?>" />
</div>