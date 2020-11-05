<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
// Heading
$this->form->generateHeadingField(
	array(
		'heading' => 'h4',
		'label'   => 'Background',
		'class'   => 'mzldr-n-m-b',
	)
);
// Separator
$this->form->generateSeparatorField();
// Background Color Picker
$this->form->generateColorPickerField(
	array(
		'name'        => 'mzldr[loader_appearance][background_color]',
		'value'       => isset( $this->loader_data->appearance->background_color ) ? esc_attr( $this->loader_data->appearance->background_color ) : '#ffffff',
		'label'       => __( 'Background Color', 'maz-loader' ),
		'placeholder' => '#ffffff',
		'class'		  => ['mzldr-loader-appearance-bg-color'],
		'description' => __( 'Select a background color for your loader.<br><strong>Note:</strong> Your Loader must contain at least 1 field for the background color to appear.', 'maz-loader' ),
		'bind'        => array(
			'data-bind-type'          => 'keyup',
			'data-bind-custom-event'  => true,
			'data-bind-reason'        => 'loader_css_attribute_change',
			'data-bind-css-attribute' => 'background',
			'data-bind-field-type'    => 'color_picker',
		),
	)
);
// Background Image
$this->form->generateImageField(
	array(
		'name'        => 'mzldr[loader_appearance][background]',
		'image'       => isset( $this->loader_data->appearance->background ) ? esc_attr( $this->loader_data->appearance->background ) : '',
		'label'       => __( 'Background', 'maz-loader' ),
		'description' => __( 'Set a background image for your loader. Set it either as a cover or make it a background pattern.', 'maz-loader' ),
		'bind'        => array(
			'data-bind-type'         => 'loader_bg_image',
			'data-bind-custom-event' => true,
			'data-bind-field-type'   => 'image',
		),
	)
);
// Background Image Type
$this->form->generateDropDownField(
	array(
		'name'        => 'mzldr[loader_appearance][background_image_type]',
		'default'     => 'cover',
		'value'       => isset( $this->loader_data->appearance->background_image_type ) ? esc_attr( $this->loader_data->appearance->background_image_type ) : 'cover',
		'values'      => array(
			'cover'    => __( 'Cover', 'maz-loader' ),
			'repeat'   => __( 'Repeat', 'maz-loader' ),
			'repeat_x' => __( 'Repeat X', 'maz-loader' ),
			'repeat_y' => __( 'Repeat Y', 'maz-loader' ),
		),
		'label'       => __( 'Background Image Type', 'maz-loader' ),
		'description' => __( 'Set the background image type.<br><strong>Default:</strong> cover.', 'maz-loader' ),
		'bind'        => array(
			'data-bind-ui'           => true,
			'data-bind-type'         => 'change',
			'data-bind-custom-event' => true,
			'data-bind-reason'       => 'loader_class_change',
			'data-bind-context'      => 'loader_background_image_type',
			'data-bind-field-type'   => 'select',
		),
	)
);
// Background Image Position
$this->form->generateDropDownField(
	array(
		'name'        => 'mzldr[loader_appearance][background_image_position]',
		'default'     => 'center_center',
		'value'       => isset( $this->loader_data->appearance->background_image_position ) ? esc_attr( $this->loader_data->appearance->background_image_position ) : 'center_center',
		'values'      => array(
			'left_top'      => __( 'Left Top', 'maz-loader' ),
			'left_center'   => __( 'Left Center', 'maz-loader' ),
			'left_bottom'   => __( 'Left Bottom', 'maz-loader' ),
			'right_top'     => __( 'Right Top', 'maz-loader' ),
			'right_center'  => __( 'Right Center', 'maz-loader' ),
			'right_bottom'  => __( 'Right Bottom', 'maz-loader' ),
			'center_top'    => __( 'Center Top', 'maz-loader' ),
			'center_center' => __( 'Center Center', 'maz-loader' ),
			'center_bottom' => __( 'Center Bottom', 'maz-loader' ),
		),
		'label'       => __( 'Background Image Position', 'maz-loader' ),
		'description' => __( 'Set the background image type.<br><strong>Default:</strong> center center.', 'maz-loader' ),
		'bind'        => array(
			'data-bind-ui'           => true,
			'data-bind-type'         => 'change',
			'data-bind-custom-event' => true,
			'data-bind-reason'       => 'loader_class_change',
			'data-bind-context'      => 'loader_background_image_position',
			'data-bind-field-type'   => 'select',
		),
	)
);
// Heading
$this->form->generateHeadingField(
	array(
		'heading' => 'h4',
		'label'   => 'Overlay',
		'class'   => 'mzldr-n-m-b',
	)
);
// Separator
$this->form->generateSeparatorField();
// Background Overlay Color Picker
$this->form->generateColorPickerField(
	array(
		'name'        => 'mzldr[loader_appearance][background_color_overlay]',
		'value'       => isset( $this->loader_data->appearance->background_color_overlay ) ? esc_attr( $this->loader_data->appearance->background_color_overlay ) : '',
		'label'       => __( 'Background Color Overlay', 'maz-loader' ),
		'placeholder' => '#ffffff',
		'description' => __( 'Select a color for your loader\'s overlay.', 'maz-loader' ),
		'bind'        => array(
			'data-bind-type'          => 'keyup',
			'data-bind-custom-event'  => true,
			'data-bind-reason'        => 'loader_css_attribute_change',
			'data-bind-css-attribute' => 'background',
			'data-bind-context'		  => 'background_color_overlay',
			'data-bind-target'		  => '.mazloader-item-overlay',
			'data-bind-field-type'    => 'color_picker',
		),
	)
);

// Heading
$this->form->generateHeadingField(
	array(
		'heading' => 'h4',
		'label'   => 'Loader Appearance',
		'class'   => 'mzldr-n-m-b',
	)
);
// Separator
$this->form->generateSeparatorField();
// Content Position
$this->form->generateDropDownField(
	array(
		'name'        => 'mzldr[loader_appearance][content_position]',
		'default'     => 'center',
		'value'       => isset( $this->loader_data->appearance->content_position ) ? esc_attr( $this->loader_data->appearance->content_position ) : 'center',
		'values'      => array(
			'top_left'     => __( 'Top Left', 'maz-loader' ),
			'top'          => __( 'Top', 'maz-loader' ),
			'top_right'    => __( 'Top Right', 'maz-loader' ),
			'center_left'  => __( 'Center Left', 'maz-loader' ),
			'center'       => __( 'Center', 'maz-loader' ),
			'center_right' => __( 'Center Right', 'maz-loader' ),
			'bottom_left'  => __( 'Bottom Left', 'maz-loader' ),
			'bottom'       => __( 'Bottom', 'maz-loader' ),
			'bottom_right' => __( 'Bottom Right', 'maz-loader' ),
		),
		'label'       => __( 'Loader Content Position', 'maz-loader' ),
		'description' => __( 'Set the where your loader\'s content will appear.<br><strong>Default:</strong> Center.', 'maz-loader' ),
		'bind'        => array(
			'data-bind-ui'           => true,
			'data-bind-type'         => 'change',
			'data-bind-custom-event' => true,
			'data-bind-reason'       => 'loader_class_change',
			'data-bind-context'      => 'loader_content_position',
			'data-bind-field-type'   => 'select',
			'data-bind-target'		 => '.mazloader-items-wrapper'
		),
	)
);
// Side by Side Loader Fields
$this->form->generateToggleField(
	array(
		'name'        => 'mzldr[loader_appearance][items_side_by_side]',
		'default'     => false,
		'value'       => isset( $this->loader_data->appearance->items_side_by_side ) ? esc_attr( $this->loader_data->appearance->items_side_by_side ) : 'disabled',
		'label'       => __( 'Side by Side Loader Items', 'maz-loader' ),
		'description' => __( 'Make the loader items appear side by side instead of one below another.', 'maz-loader' ),
		'bind'        => array(
			'data-bind-ui'                => true,
			'data-bind-listener-override' => true,
			'data-bind-type'              => 'change',
			'data-bind-custom-event'      => true,
			'data-bind-reason'            => 'loader_class_change',
			'data-bind-context'           => 'loader_items_side_by_side',
			'data-bind-field-type'        => 'toggle',
			'data-bind-target'			  => '.mazloader-items-wrapper'
		),
	)
);


// Display Close Button
$this->form->generateProField(
	array(
		'label'       => __( 'Display Close Button', 'maz-loader' ),
		'description' => __( 'Enable to display a close button on your Loader.', 'maz-loader' )
	)
);
// Display Transition
$this->form->generateProField(
	array(
		'label'       => __( 'Display Transition', 'maz-loader' ),
		'description' => __( 'Enable to set a transition for your Loader. The transition runs after the Loader finishes loading.<br><a href="https://www.feataholic.com/how-to-create-a-wordpress-preloader-with-a-smooth-page-transition/" target="_blank">How can I set up transitions?</a>', 'maz-loader' )
	)
);

// Heading
$this->form->generateHeadingField(
	array(
		'heading' => 'h4',
		'label'   => 'Page Scrolling',
		'class'   => 'mzldr-n-m-b',
	)
);
// Separator
$this->form->generateSeparatorField();
// Disable Page Scroll While Loading
$this->form->generateToggleField(
	array(
		'name'        => 'mzldr[loader_appearance][disable_page_scroll]',
		'default'     => false,
		'value'       => isset( $this->loader_data->appearance->disable_page_scroll ) ? esc_attr( $this->loader_data->appearance->disable_page_scroll ) : 'disabled',
		'label'       => __( 'Disable Page Scroll', 'maz-loader' ),
		'description' => __( 'Disable page scrolling until the loader finishes.', 'maz-loader' ),
	)
);