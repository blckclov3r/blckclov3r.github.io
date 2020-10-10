<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
$heading = $this->get_field_data( 'heading' );
$description = $this->get_field_data( 'description' );
$label   = $this->get_field_data( 'label' );
$class   = $this->get_field_data( 'class' );
?>
<?php if (!empty($label)) { ?>
<<?php echo esc_attr( $heading ); ?> class="mzldr-heading-field <?php echo esc_attr( $class ); ?>"><?php echo esc_html( $label ); ?></<?php echo esc_attr( $heading ); ?>>
<?php } ?>
<?php if (!empty($description)) { ?><p class="mzldr-heading-description"><?php echo ($description); ?></p><?php } ?>
