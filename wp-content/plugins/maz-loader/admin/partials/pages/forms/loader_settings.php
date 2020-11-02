<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
// Loader Minimum Loading Time
$this->form->generateTextField(
	array(
		'id'		  => 'mzldr_loader_settings_minimum_loading_time',
		'name'        => 'mzldr[loader_settings][minimum_loading_time]',
		'value'       => isset( $this->loader_data->settings->minimum_loading_time ) ? esc_attr( $this->loader_data->settings->minimum_loading_time ) : 2000,
		'label'       => __( 'Minimum Loading Time', 'maz-loader' ),
		'placeholder' => 2000,
		'description' => __( 'Set the minimum loading time in milliseconds.<br><strong>Example:</strong> 1000 for 1 second or 2500 for 2,5 seconds.', 'maz-loader' )
	)
);
// Loader Duration
$this->form->generateTextField(
	array(
		'id'		  => 'mzldr_loader_settings_duration',
		'name'        => 'mzldr[loader_settings][duration]',
		'value'       => isset( $this->loader_data->settings->duration ) ? esc_attr( $this->loader_data->settings->duration ) : 1000,
		'label'       => __( 'Duration', 'maz-loader' ),
		'placeholder' => 1000,
		'description' => __( 'Set the duration of the loader in milliseconds.<br><strong>Note:</strong> The total loading time consists of the <em>Minimum Loading Time</em> + <em>Duration</em><br><strong>Example:</strong> 1000 for 1 second or 2500 for 2,5 seconds.', 'maz-loader' ),
	)
);
// Loader Delay
$this->form->generateTextField(
	array(
		'id'		  => 'mzldr_loader_settings_delay',
		'name'        => 'mzldr[loader_settings][delay]',
		'value'       => isset( $this->loader_data->settings->delay ) ? esc_attr( $this->loader_data->settings->delay ) : 0,
		'label'       => __( 'Delay', 'maz-loader' ),
		'placeholder' => 0,
		'description' => __( 'Delay the loader before showing it in milliseconds.<br><strong>Example:</strong> 1000 for 1 second or 2500 for 2,5 seconds.', 'maz-loader' ),
	)
);
// Heading
$this->form->generateHeadingField(
	array(
		'heading' => 'h4',
		'label'   => 'Device Control',
		'class'   => 'mzldr-n-m-b',
	)
);


// Separator
$this->form->generateSeparatorField();
// Device Control Type
$this->form->generateProField(
	array(
		'label'       => __( 'Device Control', 'maz-loader' ),
		'description' => __( 'Set whether to display the Loader on all devices, on mobile or hide it on mobile devices.', 'maz-loader' )
	)
);
// Heading
$this->form->generateHeadingField(
	array(
		'heading' => 'h4',
		'label'   => 'Publishing Rules',
		'class'   => 'mzldr-n-m-b',
	)
);
 // Separator
$this->form->generateSeparatorField();
// Show Only on Home Page
$this->form->generateToggleField(
	array(
		'name'        => 'mzldr[loader_settings][show_on_homepage]',
		'default'     => true,
		'value'       => isset( $this->loader_data->settings->show_on_homepage ) ? esc_attr( $this->loader_data->settings->show_on_homepage ) : 'disabled',
		'label'       => __( 'Show on Homepage', 'maz-loader' ),
		'description' => __( 'Enable to show the loader only on the homepage. (You need to have the "<strong>Display the last published Loader on all pages?</strong>" option enabled under the Settings page.', 'maz-loader' ),
	)
);

