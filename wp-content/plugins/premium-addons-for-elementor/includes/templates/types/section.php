<?php

namespace PremiumAddons\Includes\Templates\Types;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Premium_Structure_Section' ) ) {

	/**
	 * Define Premium_Structure_Section class
	 */
	class Premium_Structure_Section extends Premium_Structure_Base {

		public function get_id() {
            return 'premium_section';
		}

		public function get_single_label() {
			return __( 'Section', 'premium-addons-for-elementor' );
		}

		public function get_plural_label() {
			return __( 'Sections', 'premium-addons-for-elementor' );
		}

		public function get_sources() {
			return array( 'premium-api' );
		}

		public function get_document_type() {
			return array(
				'class' => 'Premium_Section_Document',
				'file'  => PREMIUM_ADDONS_PATH . 'includes/templates/documents/section.php',
			);
		}

		/**
		 * Library settings for current structure
		 *
		 * @return void
		 */
		public function library_settings() {

			return array(
				'show_title'    => false,
				'show_keywords' => true,
			);

		}

	}

}
