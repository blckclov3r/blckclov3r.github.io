<?php

namespace PremiumAddons\Compatibility\WPML\Widgets;

use WPML_Elementor_Module_With_Items;

if ( ! defined('ABSPATH') ) exit; // No access of directly access

/**
 * Fancy Text
 *
 * Registers translatable widget with items.
 *
 * @since 3.1.9
 */
class Maps extends WPML_Elementor_Module_With_Items {

	/**
	 * Retrieve the field name.
	 *
	 * @since 3.1.9
	 * @return string
	 */
	public function get_items_field() {
		return 'premium_maps_map_pins';
	}

	/**
	 * Retrieve the fields inside the repeater.
	 *
	 * @since 3.1.9
	 *
	 * @return array
	 */
	public function get_fields() {
		return array(
			'map_latitude',
            'map_longitude',
            'pin_title',
            'pin_desc'
		);
	}

	/**
	 * Get the title for each repeater string
	 *
	 * @since 3.1.9
	 *
	 * @return string
	 */
	protected function get_title( $field ) {
		
        if ( 'map_latitude' === $field ) {
			return __( 'Maps: Marker Latitude', 'premium-addons-for-elementor' );
		}
        if ( 'map_longitude' === $field ) {
			return __( 'Maps: Marker Longitude', 'premium-addons-for-elementor' );
		}
        if ( 'pin_title' === $field ) {
			return __( 'Maps: Marker Title', 'premium-addons-for-elementor' );
		}
        if ( 'pin_desc' === $field ) {
			return __( 'Maps: Marker Description', 'premium-addons-for-elementor' );
		}
        
		return '';
		
	}

	/**
	 * Get `editor_type` for each repeater string
	 *
	 * @since 3.1.9
	 *
	 * @return string
	 */
	protected function get_editor_type( $field ) {

		if ( 'map_latitude' === $field || 'map_longitude' === $field || 'pin_title' === $field ) {
			return 'LINE';
		}
        if ( 'pin_desc' === $field ) {
			return 'AREA';
		}

		return '';
	}

}
