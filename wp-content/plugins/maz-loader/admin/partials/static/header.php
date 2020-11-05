<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
?>
<!-- MAZ Loader Admin Page -->
<div id="mazloader-admin">
	<!-- MAZ Loader Header -->
	<div class="container-fluid mazloader-header">
		<div class="row">
			<div class="mazloader-header-inner">
				<div class="left-side">
					<?php echo ( $this->action == 'edit' ) ? '<span class="mzldr-label editing">' . __( 'EDITING', 'maz-loader' ) . '</span>' : ''; ?>
					<h1 class="mazloader-header-title action-<?php echo esc_attr( $this->page ); ?>"<?php echo ( $title_contenteditable == true ) ? ' contenteditable="true"' : ''; ?>><?php echo esc_html( $page_title ); ?></h1>
					<?php echo ( $this->action == 'edit' ) ? '<span class="mzldr-label id" title="' . __('Your Loader\'s ID', 'maz-loader') . '">' . $this->loader->id . '</span>' : ''; ?>
				</div>
				<span class="actions">
					<a href="<?php echo esc_attr( MZLDR_Constants::getDocumentationURL() ); ?>" target="_blank"><?php _e( 'Documentation', 'maz-loader' ); ?></a>
					|
					<a href="<?php echo esc_attr( MZLDR_Constants::getSettingsURL() ); ?>"><?php _e( 'Settings', 'maz-loader' ); ?></a>
					<?php
					
					?>
					<a href="<?php echo esc_attr( MZLDR_Constants::getUpgradeURL() ); ?>" class="mzldr-go-pro-link br pd bg"><?php _e( 'Go Pro', 'maz-loader' ); ?></a>
					<?php
					
					?>
				</span>
			</div>
		</div>
	</div>
	<!-- /MAZ Loader Header -->
	<?php do_action( 'mzldr_notices_hook' ); ?>
	<!-- MAZ Loader Content -->
	<div class="container-fluid mazloader-content">
		<div class="row">
