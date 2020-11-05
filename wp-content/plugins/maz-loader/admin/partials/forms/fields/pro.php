<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
$extra_classes = ! empty( $this->get_field_data( 'class' ) ) ? ' ' . implode( ' ', $this->get_field_data( 'class' ) ) : '';
$pro_label     = $this->get_field_data( 'label' );
?>
<a href="#" class="mzldr-button upgrade mzldr-pro-upgrade-button" data-title="<?php echo esc_attr($pro_label); ?>"><?php echo esc_html($pro_label); ?></a>