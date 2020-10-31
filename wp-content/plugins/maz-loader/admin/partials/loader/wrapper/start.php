<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
$wrapper_classes[] = (isset($this->field_data->position)) ? ' pos_' . $this->field_data->position : '';
$wrapper_classes[] = $this->type;
?>
<div class="mazloader-item-wrapper<?php echo esc_attr(implode(' ', $wrapper_classes)); ?>" data-field-id="<?php echo esc_attr( $this->field_data->id ); ?>">
	<div class="mazloader-item-wrapper-inner">
