<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
$icons         = MZLDR_Icon_Field::getIconsList();
$selected_icon = $this->get_field_data( 'value' );
$selected_tab  = $this->get_field_data( 'tab_value' );
?>
<div class="mzldr-tabs-container mzldr-icons-field">
	<ul class="mzldr-tabs">
		<li data-tab-id="svg" class="mzldr-tab<?php echo $selected_tab == 'svg' ? ' is-active' : ''; ?>"><?php _e( 'SVG', 'maz-loader' ); ?></li>
		<li data-tab-id="gif" class="mzldr-tab<?php echo $selected_tab == 'gif' ? ' is-active' : ''; ?>"><?php _e( 'GIFs', 'maz-loader' ); ?></li>
		<li data-tab-id="css-other" class="mzldr-tab<?php echo $selected_tab == 'css-other' ? ' is-active' : ''; ?>"><?php _e( 'CSS & Other', 'maz-loader' ); ?></li>
	</ul>
	<div class="mzldr-panels">
		<div class="mzldr-panel svg<?php echo $selected_tab == 'svg' ? ' is-active' : ''; ?>" data-tab-id="svg">
			<?php
			foreach ( $icons['svg'] as $icon ) {
				?>
				<div class="icon<?php echo ( $icon == $selected_icon && $selected_tab == 'svg' ) ? ' is-active' : ''; ?>" data-icon-id="<?php echo esc_attr( $icon ); ?>">
					<img src="<?php echo MZLDR_PUBLIC_MEDIA_URL . 'img/svg/' . esc_attr( $icon ) . '.svg'; ?>" alt="<?php _e( 'loading icon', 'maz-loader' ); ?>" />
				</div>
				<?php
			}
			?>
		</div>
		<div class="mzldr-panel gif<?php echo $selected_tab == 'gif' ? ' is-active' : ''; ?>" data-tab-id="gif">
			<?php
			foreach ( $icons['gif'] as $icon ) {
				?>
				<div class="icon<?php echo ( $icon == $selected_icon && $selected_tab == 'gif' ) ? ' is-active' : ''; ?>" data-icon-id="<?php echo esc_attr( $icon ); ?>">
					<img src="<?php echo MZLDR_PUBLIC_MEDIA_URL . 'img/gif/' . esc_attr( $icon ) . '.gif'; ?>" alt="<?php _e( 'loading icon', 'maz-loader' ); ?>" />
				</div>
				<?php
			}
			?>
		</div>
		<div class="mzldr-panel css-other<?php echo $selected_tab == 'css-other' ? ' is-active' : ''; ?>" data-tab-id="css-other">
			<?php
			foreach ( $icons['css-other'] as $key => $value ) {
				?>
				<div class="icon<?php echo ( $key == $selected_icon && $selected_tab == 'css-other' ) ? ' is-active' : ''; ?><?php echo ( isset( $value['class'] ) ) ? esc_attr( $value['class'] ) : ''; ?>" data-icon-id="<?php echo esc_attr( $key ); ?>">
					<div class="mzldr-css-icon css-other style<?php echo esc_attr( $key ); ?>">
						<?php
						if ( isset( $value['external_file'] ) ) {
							if ( isset( $value['type'] ) && $value['type'] == 'svg' ) {
								echo $value['html'];
							} else {
								?>
							<img src="<?php echo MZLDR_PUBLIC_MEDIA_URL . 'img/css_other/' . esc_attr( $value['filename'] ); ?>" alt="<?php _e( 'loading icon', 'maz-loader' ); ?>" />
								<?php
							}
						} else {
							echo $value['css'];
						}
						?>
					</div>
				</div>
				<?php
			}
			?>
		</div>
	</div>
	<input type="hidden" class="icon_value" value="<?php echo esc_attr( $this->get_field_data( 'value' ) ); ?>" name="<?php echo esc_attr( $this->get_field_data( 'name' ) ); ?>" />
	<input type="hidden" class="icon_tab_value" value="<?php echo esc_attr( $this->get_field_data( 'tab_value' ) ); ?>" name="<?php echo esc_attr( $this->get_field_data( 'tab_name' ) ); ?>" />
</div>
