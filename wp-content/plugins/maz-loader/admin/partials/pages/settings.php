<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
// check user capabilities
if ( ! current_user_can( 'manage_options' ) ) {
	return;
}
$options = get_option( 'mzldr_settings_options' );
?>
<div class="mzldr-page-settings">
	<div class="row">
		<div class="col-xs-10">
			<div class="mzldr-tabs-container">
				<ul class="mzldr-tabs">
					<li data-tab-id="general" class="mzldr-tab is-active"><?php _e( 'General', 'maz-loader' ); ?></li>
				</ul>
				<div class="mzldr-panels">
					<div class="mzldr-panel general is-active" data-tab-id="general">
						<?php
						// check if the user have submitted the settings
						if ( isset( $_GET['settings-updated'] ) ) {
							// add settings saved message with the class of "updated"
							add_settings_error( 'mzldr_settings_messages', 'mzldr_settings_message', __( 'Settings Saved', 'maz-loader' ), 'updated' );
						}
						// show error/update messages
						settings_errors( 'mzldr_settings_messages' );
						?>
						<form action="options.php" method="post">
						<?php
						settings_fields( 'mzldr_settings' );
						do_settings_sections( 'mzldr_settings' );
						submit_button( __('Save Settings', 'maz-loader') );
						?>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>