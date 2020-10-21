<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
if ( empty( $loaders ) ) {
	return;
}
$preview = isset( $preview ) ? (bool) $preview : false;
?>
<?php include( MZLDR_PUBLIC_PATH . 'partials/wrapper/start.php' ); ?>
<?php
foreach ( $loaders as $loader ) {
	$loader_data = json_decode( $loader->data );
	$field_data = isset($loader_data->data) && !empty($loader_data->data) ? $loader_data->data : array();
	$_loader_settings = isset( $loader_data->settings ) ? $loader_data->settings : '';
	$loader_settings = NEW MZLDR_Registry($_loader_settings);
	$loader_appearance = isset( $loader_data->appearance ) ? $loader_data->appearance : '';
	$loader_appearance = NEW MZLDR_Registry($loader_appearance);
	

	$loader_style   = '';
	$loader_classes = '';
	$overlay_classes = [ ' ' ];
	$close_button_classes = [ ' ' ];
	$overlay_style = '';
	$transition_style = '';
	$wrapper_classes = [ ' ' ];

	$loader_appearance_to_parse = '';

	$has_bg_color = false;
	$has_bg_image = false;

	if ( ! empty( $_loader_settings ) && ! empty( $loader_appearance ) ) {
		// Style
		$style_params = [];
		if ( ! empty ( $loader_appearance->get( 'background_color' ) ) ) {
			$has_bg_color = true;
			$style_params['background'] = esc_attr( $loader_appearance->get( 'background_color' ) );
		}
		if ( ! empty( $loader_appearance->get('background') ) ) {
			$has_bg_image = true;
			$style_params['background'] = 'url(' . esc_url( $loader_appearance->get( 'background' ) ) . ')';
		}
		if ($has_bg_color && $has_bg_image) {
			$style_params['background'] = 'url(' . esc_url( $loader_appearance->get( 'background' ) ) . ') ' . esc_attr( $loader_appearance->get( 'background_color' ) );
		}
		$loader_style = MZLDR_Helper::getCSSAttributes( $style_params );

		if ( ! empty( $loader_appearance->get( 'background_color_overlay' ) ) ) {
			$params['background'] = esc_attr( $loader_appearance->get( 'background_color_overlay' ) );
			$overlay_style = MZLDR_Helper::getCSSAttributes($params);
			$overlay_classes[] = 'is-visible';
		}

		// Classes
		$loader_classes_items   = [];
		if ( ! empty( $loader_appearance->get( 'content_position' ) ) ) {
			$wrapper_classes[] = 'position_' .  esc_attr( $loader_appearance->get( 'content_position' ) );
		}
		if ( ! empty( $loader_appearance->get( 'items_side_by_side' ) ) && (boolean) $loader_appearance->get( 'items_side_by_side' ) ) {
			$wrapper_classes[] = 'item_pos_side_by_side';
		}
		
		if ( ! empty( $loader_appearance->get( 'background_image_type' ) ) ) {
			$loader_classes_items[] = 'bg_img_type_' .  esc_attr( $loader_appearance->get( 'background_image_type' ) );
		}
		if ( ! empty( $loader_appearance->get( 'background_image_position' ) ) ) {
			$loader_classes_items[] = 'bg_img_position_' .  esc_attr( $loader_appearance->get( 'background_image_position' ) );
		}
		if ( ! empty( $loader_appearance->get( 'display_transition' ) ) && (boolean) $loader_appearance->get( 'display_transition' ) &&
  			 ! empty( $loader_appearance->get( 'transition_style' ) )) {
			$loader_classes_items[] = 'has-transition';
			$loader_classes_items[] = $loader_appearance->get( 'transition_style' );

			$transition_style = ' style="background:' . esc_attr($loader_appearance->get( 'transition_color' )) . ';"';
		}
		// check if we have added delay, so we can add the `is-hidden` class to the loader

		if (!$preview) {
			if ($loader_settings->get('delay') > 0) {
				$loader_classes_items[] = 'is-hidden';
			}
			
			
			$loader_classes_items[] = 'dc_desktop dc_tablet dc_smartphone';
			
			
		} else {
			$loader_classes_items[] = 'is-preview';
		}

		$loader_classes = ' ' . implode( ' ', $loader_classes_items );

		// Loader Appearance [data-*] attributes
		$loader_appearance_to_parse = array(
			'disable_page_scroll' => (boolean) $loader_appearance->get( 'disable_page_scroll' ) == 'on' ? true : false,
			
		);

		// close button classes
		$close_button_classes[] = ($loader_appearance->get( 'display_close_button' ) == 'on') ? 'is-visible' : '';
		$close_button_classes[] = $loader_appearance->get( 'close_button_style' );
	}

	$loader_settings_atts   = htmlspecialchars( json_encode( $_loader_settings ) );
	$loader_appearance_atts = htmlspecialchars( json_encode( $loader_appearance_to_parse ) );
	?>
		<!-- MAZ Loader Item #<?php echo $loader->id; ?> Start -->
		<div
			id="mazloader-item-<?php echo esc_attr( $loader->id ); ?>"
			class="mazloader-item<?php echo esc_attr( $loader_classes ); ?>"
			data-settings="<?php echo $loader_settings_atts; ?>"
			data-appearance="<?php echo $loader_appearance_atts; ?>"
			data-loader-id="<?php echo esc_attr( $loader->id ); ?>"
			<?php echo $loader_style; ?>
			>
			<?php
			
			?>
			<div class="mazloader-item-overlay<?php echo implode(' ', $overlay_classes); ?>"<?php echo $overlay_style; ?>></div>
			<div class="mazloader-items-wrapper<?php echo implode(' ', $wrapper_classes); ?>">
			<?php
			foreach ( $field_data as $fd ) {
				$fd->loader_settings = $_loader_settings;
				$type		= implode('_', array_map('ucfirst', explode('_', $fd->type)));
				$type       = ucfirst( $type );
				$class_name = 'MZLDR_' . $type . '_Field';
				$field      = new $class_name( $fd );
				$field->render( $preview );
			}
			?>
			</div>
			<?php
			
			?>
		</div>
		<!-- MAZ Loader Item #<?php echo $loader->id; ?> End -->
		<?php
}
?>
<?php include( MZLDR_PUBLIC_PATH . 'partials/wrapper/end.php' ); ?>
