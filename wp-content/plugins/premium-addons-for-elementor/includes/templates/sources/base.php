<?php

namespace PremiumAddons\Includes\Templates\Sources;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

abstract class Premium_Templates_Source_Base {

	/**
	 * @abstract
	 * @since 3.6.0
	 * @access public
	*/
	abstract public function get_slug();

	/**
	 * @abstract
	 * @since 3.6.0
	 * @access public
	*/
	abstract public function get_version();

	/**
	 * @abstract
	 * @since 3.6.0
	 * @access public
	*/
	abstract public function get_items();

	/**
	 * @abstract
	 * @since 3.6.0
	 * @access public
	*/
	abstract public function get_categories();

	/**
	 * Return source item list
	 *
	 * @since 3.6.0
	 * @access public
	*/
	abstract public function get_keywords();

	/**
	 * @abstract
	 * @since 3.6.0
	 * @access public
	*/
	abstract public function get_item( $template_id );

	/**
	 * @abstract
	 * @since 3.6.0
	 * @access public
	*/
	abstract public function transient_lifetime();

	/**
	 * Returns templates transient key for current source
	 *
	 * @return string
	 */
	public function templates_key() {
		return 'premium_templates_' . $this->get_slug() . '_' . $this->get_version();
	}

	/**
	 * Returns categories  transient key for current source
	 *
	 * @return string
	 */
	public function categories_key() {
		return 'premium_categories_' . $this->get_slug() . '_' . $this->get_version();
	}

	/**
	 * Returns keywords transient key for current source
	 *
	 * @return string
	 */
	public function keywords_key() {
		return 'premium_keywords_' . $this->get_slug() . '_' . $this->get_version();
	}

	/**
	 * Set templates cache.
	 *
	 * @param array $value
	 */
	public function set_templates_cache( $value ) {
		set_transient( $this->templates_key(), $value, $this->transient_lifetime() );
	}

	/**
	 * Set templates cache.
	 *
	 * @param array $value
	 */
	public function get_templates_cache() {

		if ( $this->is_debug_active() ) {
			return false;
		}

		return get_transient( $this->templates_key() );
	}

	/**
	 * Delete templates cache
	 *
	 * @return [type] [description]
	 */
	public function delete_templates_cache() {
		delete_transient( $this->templates_key() );
	}

	/**
	 * Set categories cache.
	 *
	 * @param array $value
	 */
	public function set_categories_cache( $value ) {
		set_transient( $this->categories_key(), $value, $this->transient_lifetime() );
	}

	/**
	 * Set categories cache.
	 *
	 * @param array $value
	 */
	public function get_categories_cache() {

		if ( $this->is_debug_active() ) {
			return false;
		}

		return get_transient( $this->categories_key() );
	}

	/**
	 * Delete categories cache
	 *
	 * @return [type] [description]
	 */
	public function delete_categories_cache() {
		delete_transient( $this->categories_key() );
	}

	/**
	 * Set categories cache.
	 *
	 * @param array $value
	 */
	public function set_keywords_cache( $value ) {
		set_transient( $this->keywords_key(), $value, $this->transient_lifetime() );
	}

	/**
	 * Set categories cache.
	 *
	 * @param array $value
	 */
	public function get_keywords_cache() {

		if ( $this->is_debug_active() ) {
			return false;
		}

		return get_transient( $this->keywords_key() );
	}

	/**
	 * Delete categories cache
	 *
	 * @return [type] [description]
	 */
	public function delete_keywords_cache() {
		delete_transient( $this->keywords_key() );
	}

	/**
	 * Check if debug is active
	 *
	 * @return boolean
	 */
	public function is_debug_active() {

		if ( defined( 'PREMIUM_API_DEBUG' ) && true === PREMIUM_API_DEBUG ) {
			return true;
		} else {
			return false;
		}

	}

	/**
	 * Returns template ID prefix for premium templates
	 *
	 * @return string
	 */
	public function id_prefix() {
		return 'premium_';
	}

	/**
	 * @since 3.6.0
	 * @access protected
	*/
	protected function replace_elements_ids( $content ) {
		return \Elementor\Plugin::$instance->db->iterate_data( $content, function( $element ) {
			$element['id'] = \Elementor\Utils::generate_random_string();
			return $element;
		} );
	}

	/**
	 * Process content for export/import.
	 *
	 * Process the content and all the inner elements, and prepare all the
	 * elements data for export/import.
	 *
	 * @since 3.6.0
	 * @access protected
	 *
	 * @param array  $content A set of elements.
	 * @param string $method  Accepts either `on_export` to export data or
	 *                        `on_import` to import data.
	 *
	 * @return mixed Processed content data.
	 */
	protected function process_export_import_content( $content, $method ) {
		return \Elementor\Plugin::$instance->db->iterate_data(
			$content, function( $element_data ) use ( $method ) {
				$element = \Elementor\Plugin::$instance->elements_manager->create_element_instance( $element_data );

				// If the widget/element isn't exist, like a plugin that creates a widget but deactivated
				if ( ! $element ) {
					return null;
				}

				return $this->process_element_export_import_content( $element, $method );
			}
		);
	}

	/**
	 * Process single element content for export/import.
	 *
	 * Process any given element and prepare the element data for export/import.
	 *
	 * @since 3.6.0
	 * @access protected
	 *
	 * @param Controls_Stack $element
	 * @param string         $method
	 *
	 * @return array Processed element data.
	 */
	protected function process_element_export_import_content( $element, $method ) {

		$element_data = $element->get_data();

		if ( method_exists( $element, $method ) ) {
			// TODO: Use the internal element data without parameters.
			$element_data = $element->{$method}( $element_data );
		}

		foreach ( $element->get_controls() as $control ) {
			$control_class = \Elementor\Plugin::$instance->controls_manager->get_control( $control['type'] );

			// If the control isn't exist, like a plugin that creates the control but deactivated.
			if ( ! $control_class ) {
				return $element_data;
			}

			if ( method_exists( $control_class, $method ) ) {
				$element_data['settings'][ $control['name'] ] = $control_class->{$method}( $element->get_settings( $control['name'] ), $control );
			}
		}

		return $element_data;
	}
}
