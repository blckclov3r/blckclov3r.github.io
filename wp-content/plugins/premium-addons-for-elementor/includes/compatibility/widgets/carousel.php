<?php

namespace PremiumAddons\Compatibility\WPML\Widgets;

use WPML_Elementor_Module_With_Items;

if ( ! defined('ABSPATH') ) exit; // No access of directly access

/**
 * Carousel
 *
 * Registers translatable widget with items.
 *
 * @since 3.2.4
 */
class Carousel extends WPML_Elementor_Module_With_Items {

	/**
	 * Retrieve the field name.
	 *
	 * @since 3.2.4
	 * @return string
	 */
	public function get_items_field() {
		return 'premium_carousel_templates_repeater';
	}

	/**
	 * Retrieve the fields inside the repeater.
	 *
	 * @since 3.2.4
	 *
	 * @return array
	 */
	public function get_fields() {
		return array(
			'premium_carousel_repeater_item',
		);
	}

	/**
	 * Get the title for each repeater string
	 *
	 * @since 3.2.4
	 *
	 * @return string
	 */
	protected function get_title( $field ) {
		
        return __( 'Carousel: Template', 'premium-addons-for-elementor' );
		
	}

	/**
	 * Get `editor_type` for each repeater string
	 *
	 * @since 3.2.4
	 *
	 * @return string
	 */
	protected function get_editor_type( $field ) {

		return 'LINE';
	}

}
