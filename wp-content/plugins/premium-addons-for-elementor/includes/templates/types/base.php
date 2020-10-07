<?php

namespace PremiumAddons\Includes\Templates\Types;

if ( ! defined('ABSPATH') ) exit; // No access of directly access

if ( ! class_exists( 'Premium_Structure_Base' ) ) {

	/**
	 * Define Premium_Structure_Base class
	 */
	abstract class Premium_Structure_Base {

		abstract public function get_id();

		abstract public function get_single_label();

		abstract public function get_plural_label();

		abstract public function get_sources();

		abstract public function get_document_type();

		/**
		 * Is current structure could be outputed as location
		 *
         * @since 3.6.0
         * @access public
         * 
		 * @return boolean
		 */
		public function is_location() {
			return false;
		}

		/**
		 * Location name
         * 
		 * @since 3.6.0
         * @access public
         * 
		 * @return boolean
		 */
		public function location_name() {
			return '';
		}

		/**
		 * Library settings for current structure
		 *
		 * @return void
		 */
		public function library_settings() {

			return array(
				'show_title'    => true,
				'show_keywords' => true,
			);

		}

	}

}
