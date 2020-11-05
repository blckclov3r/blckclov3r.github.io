<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
?>
<a href="<?php echo MZLDR_Constants::getUpgradeURL(); ?>" class="mzldr-button upgrade mzldr-pro-upgrade-button" data-title="<?php echo esc_attr($this->get_field_data('label')); ?>"><?php _e('Upgrade to PRO', 'maz-loader'); ?></a>