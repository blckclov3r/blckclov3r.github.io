<?php

/**
 * Admin Settings
 *
 * @link       https://www.feataholic.com
 * @since      1.1.8 Free
 *
 * @package    MZLDR
 * @subpackage MZLDR/admin
 */

/**
 * Admin Settings
 *
 * @package    MZLDR
 * @subpackage MZLDR/admin
 * @author     Your Name <email@example.com>
 */
class MZLDR_Admin_Settings {

	/**
	 * Adds the settings page
	 *
	 * @return void
	 */
	function add_settings_page() {
		

		// register a new setting
		register_setting( 'mzldr_settings', 'mzldr_settings_options' );

		// register a new section
		add_settings_section(
			'mzldr_settings_section_developers',
			__( '', 'maz-loader' ),
			array( $this, 'mzldr_settings_section_developers_cb' ),
			'mzldr_settings'
		);

		add_settings_field(
			'mzldr_settings_field_impressions',
			__( 'Impressions', 'maz-loader' ),
			array( $this, 'mzldr_settings_field_impressions_cb' ),
			'mzldr_settings',
			'mzldr_settings_section_developers',
			array(
				'label_for'                  => 'mzldr_settings_field_impressions',
				'class'                      => 'mzldr_settings_row',
				'mzldr_settings_custom_data' => 'custom',
			)
		);

		
		add_settings_field(	
			'mzldr_settings_field_loader_display',	
			__( 'Display the last published Loader on all pages?', 'maz-loader' ),	
			array( $this, 'mzldr_settings_field_loader_display_cb' ),	
			'mzldr_settings',	
			'mzldr_settings_section_developers',	
			array(	
				'label_for'                  => 'mzldr_settings_field_loader_display',	
				'class'                      => 'mzldr_settings_row',	
				'mzldr_settings_custom_data' => 'custom',	
			)	
		);
		

		add_settings_field(
			'mzldr_settings_field_keep_data',
			__( 'Keep data during update?', 'maz-loader' ),
			array( $this, 'mzldr_settings_field_keep_data_cb' ),
			'mzldr_settings',
			'mzldr_settings_section_developers',
			array(
				'label_for'                  => 'mzldr_settings_field_keep_data',
				'class'                      => 'mzldr_settings_row',
				'mzldr_settings_custom_data' => 'custom',
			)
		);

		
	}

	function mzldr_settings_field_impressions_cb( $args ) {
		$options = get_option( 'mzldr_settings_options' );
		?>
		<select id="<?php echo esc_attr( $args['label_for'] ); ?>"
		data-custom="<?php echo esc_attr( $args['mzldr_settings_custom_data'] ); ?>"
		name="mzldr_settings_options[<?php echo esc_attr( $args['label_for'] ); ?>]"
		>
		<option value="enable" <?php echo isset( $options[ $args['label_for'] ] ) ? ( selected( $options[ $args['label_for'] ], 'enable', false ) ) : ( '' ); ?>>
		<?php esc_html_e( 'Enable', 'maz-loader' ); ?>
		</option>
		<option value="disable" <?php echo isset( $options[ $args['label_for'] ] ) ? ( selected( $options[ $args['label_for'] ], 'disable', false ) ) : ( '' ); ?>>
		<?php esc_html_e( 'Disable', 'maz-loader' ); ?>
		</option>
		</select>
		<p class="description">
		<?php _e( 'Set whether to track impression for each Loader. Once you are on <em>View Loaders</em> page, you will be able to view the impressions for each loader.', 'maz-loader' ); ?>
		</p>
		<?php
	}

	
	function mzldr_settings_field_loader_display_cb( $args ) {	
		$options = get_option( 'mzldr_settings_options' );	
		?>	
		<select id="<?php echo esc_attr( $args['label_for'] ); ?>"	
		data-custom="<?php echo esc_attr( $args['mzldr_settings_custom_data'] ); ?>"	
		name="mzldr_settings_options[<?php echo esc_attr( $args['label_for'] ); ?>]"	
		>	
		<option value="disable" <?php echo isset( $options[ $args['label_for'] ] ) ? ( selected( $options[ $args['label_for'] ], 'disable', false ) ) : ( '' ); ?>>	
		<?php esc_html_e( 'Disable', 'maz-loader' ); ?>	
		</option>	
		<option value="enable" <?php echo isset( $options[ $args['label_for'] ] ) ? ( selected( $options[ $args['label_for'] ], 'enable', false ) ) : ( '' ); ?>>	
		<?php esc_html_e( 'Enable', 'maz-loader' ); ?>	
		</option>	
		</select>	
		<p class="description">	
		<?php _e( 'Enable to display the last published loader on all pages. Disable to add a loader to each page using it\'s shortcode.', 'maz-loader' ); ?>	
		</p>	
		<?php	
	}
	

	function mzldr_settings_field_keep_data_cb( $args ) {
		$options = get_option( 'mzldr_settings_options' );
		if ( ! $options ) {
			$name = 1;
		} else {
			$name = isset( $options['mzldr_settings_field_keep_data'] ) ? $options['mzldr_settings_field_keep_data'] : '';
		}
		?>
		<input
			type="checkbox"
			id="<?php echo esc_attr( $args['label_for'] ); ?>"
			data-custom="<?php echo esc_attr( $args['mzldr_settings_custom_data'] ); ?>"
			name="mzldr_settings_options[<?php echo esc_attr( $args['label_for'] ); ?>]"
			value="1"
			<?php checked( 1, $name, true ); ?>
		>
		<p class="description">
		<?php _e( 'Check to keep data after uninstalling the plugin. This is helpful whenever you update so you don\'t lose any data.', 'maz-loader' ); ?>
		</p>
		<?php
	}

	

	function mzldr_settings_section_developers_cb( $args ) {
	}
}
