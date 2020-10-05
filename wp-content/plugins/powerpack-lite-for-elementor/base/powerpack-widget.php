<?php

namespace PowerpackElementsLite\Base;

use Elementor\Widget_Base;
use PowerpackElementsLite\Classes\PP_Helper;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Common Widget
 *
 * @since 0.0.1
 */
abstract class Powerpack_Widget extends Widget_Base {

	/**
	 * Get categories
	 *
	 * @since 0.0.1
	 */
	public function get_categories() {
		return [ 'powerpack-elements' ];
	}

	/**
	 * Get widget name
	 *
	 * @param string $slug Module class.
	 * @since 2.1.0
	 */
	public function get_widget_name( $slug = '' ) {
		return PP_Helper::get_widget_name( $slug );
	}

	/**
	 * Get widget title
	 *
	 * @param string $slug Module class.
	 * @since 2.1.0
	 */
	public function get_widget_title( $slug = '' ) {
		return PP_Helper::get_widget_title( $slug );
	}

	/**
	 * Get widget title
	 *
	 * @param string $slug Module class.
	 * @since 2.1.0
	 */
	public function get_widget_categories( $slug = '' ) {
		return PP_Helper::get_widget_categories( $slug );
	}

	/**
	 * Get widget title
	 *
	 * @param string $slug Module class.
	 * @since 2.1.0
	 */
	public function get_widget_icon( $slug = '' ) {
		return PP_Helper::get_widget_icon( $slug );
	}

	/**
	 * Get widget title
	 *
	 * @param string $slug Module class.
	 * @since 2.1.0
	 */
	public function get_widget_keywords( $slug = '' ) {
		return PP_Helper::get_widget_keywords( $slug );
	}

	/**
	 * Add a placeholder for the widget in the elementor editor
	 *
	 * @access public
	 * @since 1.3.0
	 *
	 * @return void
	 */
	public function render_editor_placeholder( $args = array() ) {

		if ( ! \Elementor\Plugin::instance()->editor->is_edit_mode() ) {
			return;
		}

		$defaults = [
			'title' => $this->get_title(),
			'body' 	=> __( 'This is a placeholder for this widget and is visible only in the editor.', 'powerpack' ),
		];

		$args = wp_parse_args( $args, $defaults );

		$this->add_render_attribute([
			'placeholder' => [
				'class' => 'pp-editor-placeholder',
			],
			'placeholder-title' => [
				'class' => 'pp-editor-placeholder-title',
			],
			'placeholder-content' => [
				'class' => 'pp-editor-placeholder-content',
			],
		]);

		?><div <?php echo $this->get_render_attribute_string( 'placeholder' ); ?>>
			<h4 <?php echo $this->get_render_attribute_string( 'placeholder-title' ); ?>>
				<?php echo $args['title']; ?>
			</h4>
			<div <?php echo $this->get_render_attribute_string( 'placeholder-content' ); ?>>
				<?php echo $args['body']; ?>
			</div>
		</div><?php
	}

	/**
	 * Render Template Content
	 *
	 * @access public
	 *
	 * @param int                                       $template_id  The template post ID
	 * @param \PowerpackElementsLite\Base\Powerpack_Widget  $widget       The widget instance
	 * @since 1.3.0
	 */
	public function render_template_content( $template_id, \PowerpackElementsLite\Base\Powerpack_Widget $widget ) {

		if ( 'publish' !== get_post_status( $template_id ) || ! method_exists( '\Elementor\Frontend', 'get_builder_content_for_display' ) ) {
			return;
		}

		if ( ! $template_id ) {
			if ( method_exists( $widget, 'render_editor_placeholder' ) ) {
				$placeholder = __( 'Choose a post template that you want to use as post skin in widget settings.', 'powerpack' );

				$widget->render_editor_placeholder([
					'title' => __( 'No template selected!', 'powerpack' ),
					'body' => $placeholder,
				]);
			} else {
				_e( 'No template selected!', 'powerpack' );
			}
		} else {

			global $wp_query;

			// Keep old global wp_query
			$old_query = $wp_query;

			// Create a new query from the current post in loop
			$new_query = new \WP_Query( [
				'post_type' => 'any',
				'p' => get_the_ID(),
			] );

			// Set the global query to the new query
			$wp_query = $new_query;

			// Fetch the template
			$template = PP_Helper::elementor()->frontend->get_builder_content_for_display( $template_id, true );

			// Revert to the initial query
			$wp_query = $old_query;

			?><div class="elementor-template"><?php echo $template; ?></div><?php
		}
	}

	/**
	 * Widget base constructor.
	 *
	 * Initializing the widget base class.
	 *
	 * @since 1.2.9.4
	 * @access public
	 *
	 * @param array       $data Widget data. Default is an empty array.
	 * @param array|null  $args Optional. Widget default arguments. Default is null.
	 */
	public function __construct( $data = [], $args = null ) {

		parent::__construct( $data, $args );

		add_filter( 'upgrade_powerpack_title', [$this, 'upgrade_powerpack_title'], 10, 3 );
		add_filter( 'upgrade_powerpack_message', [$this, 'upgrade_powerpack_message'], 10, 3 );
	}
	
	public function upgrade_powerpack_title() {
		$upgrade_title = __( 'Get PowerPack Pro', 'powerpack' );

		return $upgrade_title;
	}
	
	public function upgrade_powerpack_message() {
		$upgrade_message = sprintf( __( 'Upgrade to %1$s Pro Version %2$s for 70+ widgets, exciting extensions and advanced features.', 'powerpack' ), '<a href="https://powerpackelements.com/upgrade/?utm_medium=pp-elements-lite&utm_source=pp-widget-upgrade-section&utm_campaign=pp-pro-upgrade" target="_blank" rel="noopener">', '</a>' );

		return $upgrade_message;
	}
}
