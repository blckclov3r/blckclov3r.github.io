<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
$loader_data = isset($this->loader->data) ? $this->loader->data : array();
if (count((array) $loader_data)) {
	$loader_data = json_decode($loader_data);
	$loader_data = $loader_data->data;
}
?>
<div class="col-md-3 sidebar-outer">
	<form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post" id="maz-loader-form" >
		<!-- Sidebar -->
		<div class="sidebar">
			<!-- Top Menu -->
			<div class="top-menu">
				<div class="row">
					<!-- Fields List -->
					<a href="#" class="new-field-btn col-md-20 is-active" data-page-id="fields-list" title="<?php _e( 'Fields List', 'maz-loader' ); ?>">
						<span class="menu-icon dashicons dashicons-editor-ul"></span>
					</a>
					<!-- /Fields List -->
					<!-- Appearance Icon -->
					<a href="#" class="appearance-btn col-md-20" data-page-id="appearance" title="<?php _e( 'Appearance', 'maz-loader' ); ?>">
						<span class="menu-icon dashicons dashicons-format-image"></span>
					</a>
					<!-- /Appearance Icon -->
					<!-- Settings Icon -->
					<a href="#" class="settings-btn col-md-20" data-page-id="settings" title="<?php _e( 'Settings', 'maz-loader' ); ?>">
						<span class="menu-icon dashicons dashicons-admin-generic"></span>
					</a>
					<!-- /Settings Icon -->
					<?php if (MZLDR_Helper::isPro()) { ?>
					
					<?php } else { ?>
					<!-- FREE-START -->
					<!-- Custom Code Icon -->
					<a href="#" class="custom-code-btn col-md-20 mzldr-pro mzldr-pro-upgrade-button" data-title="<?php _e( 'Custom Code', 'maz-loader' ); ?>" data-page-id="custom-code" title="<?php _e( 'Custom Code', 'maz-loader' ); ?>">
						<span class="menu-icon dashicons dashicons-editor-code"></span>
						<span class="pro-label"><span class="icon dashicons dashicons-lock"></span><?php _e( 'Pro', 'maz-loader' ) ?></span>
					</a>
					<!-- /Custom Code Icon -->
					<!-- Publish Settings Icon -->
					<a href="#" class="publish-settings-btn col-md-20 mzldr-pro mzldr-pro-upgrade-button" data-title="<?php _e( 'Publish Settings', 'maz-loader' ); ?>" data-page-id="publish-settings" title="<?php _e( 'Publish Settings', 'maz-loader' ); ?>">
						<span class="menu-icon dashicons dashicons-upload"></span>
						<span class="pro-label"><span class="icon dashicons dashicons-lock"></span><?php _e( 'Pro', 'maz-loader' ) ?></span>
					</a>
					<!-- /Publish Settings Icon -->
					<!-- FREE-END -->
					<?php } ?>
				</div>
				<!-- Field Selector -->
				<div class="fields-items-selector">
					<a href="#" class="title"><span class="dashicons dashicons-plus-alt"></span> <?php _e( 'Field', 'maz-loader' ); ?></a>
					<div class="field-items">
						<div class="field-item text" data-item="text" title="<?php _e( 'Text', 'maz-loader' ); ?>">
							<div class="bottom"><span class="icon dashicons dashicons-editor-bold"></span></div>
							<h3 class="title"><?php _e( 'Text', 'maz-loader' ); ?></h3>
						</div>
						<div class="field-item image" data-item="image" title="<?php _e( 'Image', 'maz-loader' ); ?>">
							<div class="bottom"><span class="icon dashicons dashicons-format-image"></span></div>
							<h3 class="title"><?php _e( 'Image', 'maz-loader' ); ?></h3>
						</div>
						<div class="field-item icon" data-item="icon" title="<?php _e( 'Icon', 'maz-loader' ); ?>">
							<div class="bottom"><span class="icon dashicons loader-icon"></span></div>
							<h3 class="title"><?php _e( 'Icon', 'maz-loader' ); ?></h3>
						</div>
						
						<!-- FREE-START -->
						<div class="field-item mzldr-pro mzldr-pro-upgrade-button" data-title="<?php _e( 'Progress Bar', 'maz-loader' ); ?>" data-item="progress_bar" title="<?php _e( 'Progress Bar', 'maz-loader' ); ?>">
							<div class="bottom"><span class="icon dashicons dashicons-update"></span></div>
							<h3 class="title"><?php _e( 'Progress Bar', 'maz-loader' ); ?></h3>
							<span class="pro-label"><span class="icon dashicons dashicons-lock"></span><?php _e( 'Pro', 'maz-loader' ) ?></span>
						</div>
						<div class="field-item mzldr-pro mzldr-pro-upgrade-button" data-title="<?php _e( 'Percentage Counter', 'maz-loader' ); ?>" data-item="percentage_counter" title="<?php _e( 'Percentage Counter', 'maz-loader' ); ?>">
							<div class="bottom"><span class="icon dashicons dashicons-dashboard"></span></div>
							<h3 class="title"><?php _e( 'Percentage counter', 'maz-loader' ); ?></h3>
							<span class="pro-label"><span class="icon dashicons dashicons-lock"></span><?php _e( 'Pro', 'maz-loader' ) ?></span>
						</div>
						<div class="field-item mzldr-pro mzldr-pro-upgrade-button" data-title="<?php _e( 'Custom HTML', 'maz-loader' ); ?>" data-item="percentage_counter" title="<?php _e( 'Custom HTML', 'maz-loader' ); ?>">
							<div class="bottom"><span class="icon dashicons dashicons-editor-customchar"></span></div>
							<h3 class="title"><?php _e( 'Custom HTML', 'maz-loader' ); ?></h3>
							<span class="pro-label"><span class="icon dashicons dashicons-lock"></span><?php _e( 'Pro', 'maz-loader' ) ?></span>
						</div>
						<!-- FREE-END -->
					</div>
				</div>
				<!-- /Field Selector -->
			</div>
			<!-- /Top Menu -->
			<!-- Settings Pages -->
			<div class="settings-pages">
				<!-- Fields List -->
				<div class="setting-page is-active" data-page-id="fields-list">
					<!-- Settings Page Title -->
					<h3 class="setting-page-title"><?php _e( 'Fields List', 'maz-loader' ); ?></h3>
					<!-- /Settings Page Title -->
					<!-- Message -->
					<div class="message<?php echo count((array) $loader_data) ? esc_attr( ' is-hidden' ) : ''; ?>"><?php _e( 'Start adding fields by hovering over the ', 'maz-loader' ); ?><span class="dashicons dashicons-plus-alt"></span><?php _e( ' button above.', 'maz-loader' ); ?></div>
					<!-- /Message -->
					<!-- Fields List Items -->
					<div class="fields-list-items">
					<?php
					if ( is_object( $this->loader ) && count( (array) $this->loader ) ) {
						$loader     = json_decode( $this->loader->data );
						$field_data = isset( $loader->data ) && ! empty( $loader->data ) ? $loader->data : array();
						foreach ( $field_data as $fd ) {
							$type       = ucfirst( $fd->type );
							$class_name = 'MZLDR_' . $type . '_Field';
							$field      = new $class_name( $fd );
							ob_start();
							$field->renderFieldSettings( true );
							$preview_fields_html  = '<div class="item">';
							$preview_fields_html .= ob_get_contents();
							$preview_fields_html .= '</div>';
							ob_end_clean();
							echo $preview_fields_html;
						}
					}
					?>
					</div>
					<!-- /Fields List Items -->
				</div>
				<!-- /Fields List -->
				<!-- Appearance -->
				<div class="setting-page" data-page-id="appearance">
					<!-- Settings Page Title -->
					<h3 class="setting-page-title"><?php _e( 'Appearance', 'maz-loader' ); ?></h3>
					<!-- /Settings Page Title -->
					<?php require MZLDR_ADMIN_PATH . 'partials/pages/forms/loader_appearance.php'; ?>
				</div>
				<!-- /Appearance -->
				<!-- Settings -->
				<div class="setting-page" data-page-id="settings">
					<!-- Settings Page Title -->
					<h3 class="setting-page-title"><?php _e( 'Settings', 'maz-loader' ); ?></h3>
					<!-- /Settings Page Title -->
					<?php require MZLDR_ADMIN_PATH . 'partials/pages/forms/loader_settings.php'; ?>
				</div>
				<!-- /Settings -->
				<!-- Custom Code -->
				<div class="setting-page" data-page-id="custom-code">
					<!-- Settings Page Title -->
					<h3 class="setting-page-title"><?php _e( 'Custom Code', 'maz-loader' ); ?></h3>
					<!-- /Settings Page Title -->
					<?php require MZLDR_ADMIN_PATH . 'partials/pages/forms/custom_code_settings.php'; ?>
				</div>
				<!-- /Custom Code -->
				<!-- Publish Settings -->
				<div class="setting-page" data-page-id="publish-settings">
					<!-- Settings Page Title -->
					<h3 class="setting-page-title"><?php _e( 'Publish Settings', 'maz-loader' ); ?></h3>
					<!-- /Settings Page Title -->
					<?php require MZLDR_ADMIN_PATH . 'partials/pages/forms/publish_settings.php'; ?>
				</div>
				<!-- /Publish Settings -->
			</div>
			<!-- /Settings Pages -->
			<?php
			$this->form->generateSubmitButton();
			$this->form->generateHiddenField(
				array(
					'name'  => 'mzldr[loader_type]',
					'value' => 'loader',
				)
			);
			$this->form->generateHiddenField(
				array(
					'name'  => 'action',
					'value' => 'maz_loader_submission',
				)
			);
			$this->form->generateHiddenField(
				array(
					'name'  => 'submission_type',
					'value' => ( $this->action == 'edit' ) ? 'edit' : esc_attr( $this->page ),
				)
			);
			$this->form->generateHiddenField(
				array(
					'name'  => 'mzldr[loader_name]',
					'value' => esc_attr( $page_title ),
				)
			);
			// add loader_id if in edit mode
			if ( $this->action == 'edit' ) {
				$this->form->generateHiddenField(
					array(
						'name'  => 'mzldr[loader_id]',
						'value' => esc_attr( $this->loader->id ),
					)
				);
			}
			$this->form->generateNonceField();
			?>
		</div>
		<!-- /Sidebar -->
	</div>
	<div class="col-md-9 mazloader-preview-outer mazl-padding-0">
		<!-- Maz Loader Preview -->
		<div class="mazloader-preview">
			<!-- Preview Browser -->
			<div class="mazloader-preview-browser desktop">
				<!-- Browser Header -->
				<div class="browser-header">
					<!-- Browser Buttons -->
					<div class="browser-btns">
						<span class="browser-btn close"></span>
						<span class="browser-btn retract"></span>
						<span class="browser-btn expand"></span>
					</div>
					<!-- /Browser Buttons -->
					<!-- Browser Title -->
					<h3 class="browser-title"><?php _e( 'Live Preview', 'maz-loader' ); ?></h3>
					<!-- /Browser Title -->
					<!-- Browser Actions -->
					<div class="browser-actions">
						<!-- Responsiveness Buttons -->
						<div class="responsive-buttons">
							<span class="dashicons dashicons-desktop mzldr-responsive-button is-active" data-mode="desktop"></span>
							<span class="dashicons dashicons-tablet mzldr-responsive-button" data-mode="tablet"></span>
							<span class="dashicons dashicons-smartphone mzldr-responsive-button" data-mode="mobile"></span>
						</div>
						<!-- /Responsiveness Buttons -->
						<a href="#" class="mzldr-button mzldr-run-loader-btn small"><?php _e( 'Run Loader', 'maz-loader' ); ?></a>
					</div>
					<!-- /Browser Actions -->
				</div>
				<!-- /Browser Header -->
				<!-- Browser Page -->
				<div class="browser-page">
					<!-- Dummy Content Lines -->
					<div class="lines">
						<?php
						$sizes   = array( 20, 30, 40, 50, 60, 70, 80 );
						$heights = array( 10, 20, 30, 40, 50 );
						for ( $i = 0; $i < 15; $i++ ) {
							$margin_cls  = ( mt_rand( 0, 1 ) ) ? ' m-r-' . $sizes[ array_rand( $sizes ) ] : '';
							$margin_cls .= ( mt_rand( 0, 1 ) ) ? ' height-' . $heights[ array_rand( $heights ) ] : '';
							?>
							<div class="line<?php echo esc_attr( $margin_cls ); ?>"></div>
							<?php
						}
						?>
						<div class="line m-r-30"></div>
					</div>
					<!-- /Dummy Content Lines -->
					<!-- MAZ Loader Preview -->
					<div id="mazloader-preview">
					<?php if ( count( (array) $loader_data ) == 0 ) { ?>
						<div class="mazloader-items">
						<div id="mazloader-item-1" class="mazloader-item is-hidden">
					<?php } ?>
							<?php
							if ( count( (array) $loader_data ) ) {
								$preview = true;
								$loaders = array(
									$this->loader,
								);
								ob_start();
								include MZLDR_PUBLIC_PATH . 'partials/loader/tmpl.php';
								$preview_loader_html = ob_get_contents();
								ob_end_clean();
								echo $preview_loader_html;
							}
							?>
					<?php if ( count( (array) $loader_data ) == 0 ) { ?>
						</div>
						</div>
					<?php } ?>
					</div>
					<!-- /MAZ Loader Preview -->
					<!-- MAZ Loader Preview Loading -->
					<div class="mazloader-preview-loading"><?php _e( 'Loading your Loader...', 'maz-loader' ); ?></div>
					<!-- /MAZ Loader Preview Loading -->
				</div>
				<!-- /Browser Page -->
			</div>
			<!-- /Preview Browser -->
		</div>
		<!-- /Maz Loader Preview -->
	</form>
</div>
