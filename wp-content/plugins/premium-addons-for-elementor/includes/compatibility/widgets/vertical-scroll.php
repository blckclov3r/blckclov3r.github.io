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
class Vertical_Scroll extends WPML_Elementor_Module_With_Items {

	/**
	 * Retrieve the field name.
	 *
	 * @since 3.1.9
	 * @return string
	 */
	public function get_items_field() {
		return 'id_repeater';
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
			'id_repeater' => 'section_id'
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
		
        return __( 'Vertical Scroll: Section ID', 'premium-addons-for-elementor' );
		
	}

	/**
	 * Get `editor_type` for each repeater string
	 *
	 * @since 3.1.9
	 *
	 * @return string
	 */
	protected function get_editor_type( $field ) {

		return 'LINE';
	}

}
