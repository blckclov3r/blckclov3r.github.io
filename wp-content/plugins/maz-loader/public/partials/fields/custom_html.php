<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
$fieldData = new MZLDR_Registry($this->field_data);
$custom_css = $fieldData->get( 'custom_css' );
$custom_js = $fieldData->get( 'custom_js' );

// classes
$field_classes = [];

// animation
$field_animation = '';
if ($this->animation != 'none') {
	$field_animation = 'data-field-animation="' . $fieldData->get( 'animation' ) . '"';
	$field_classes[] = '';
	$field_classes[] = 'has-animation';
}
?>
<div
	id="mazloader-item-custom-html-<?php echo $fieldData->get( 'id' ); ?>"
	class="mazloader-item-custom-html<?php echo implode(' ', $field_classes); ?>"
	<?php echo $field_animation; ?>
>
	<div class="custom-html"><?php echo $fieldData->get( 'custom_html' ); ?></div>
	<?php if (!empty($custom_css)) { ?>
	<style type="text/css" class="custom-css"><?php echo $custom_css; ?></style>
	<?php } ?>
	<?php if (!empty($custom_js)) { ?>
	<script type="text/javascript"><?php echo $custom_js; ?></script>
	<?php } ?>
</div>