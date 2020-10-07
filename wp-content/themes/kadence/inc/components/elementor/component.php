<?php
/**
 * Kadence\Elementor\Component class
 *
 * @package kadence
 */

namespace Kadence\Elementor;

use Kadence\Component_Interface;
use Elementor;
use function Kadence\kadence;
use function add_action;
use function add_theme_support;
use function have_posts;
use function the_post;
use function apply_filters;
use function get_template_part;
use function get_post_type;


/**
 * Class for adding Elementor plugin support.
 */
class Component implements Component_Interface {

	/**
	 * Gets the unique identifier for the theme component.
	 *
	 * @return string Component slug.
	 */
	public function get_slug() : string {
		return 'elementor';
	}

	/**
	 * Adds the action and filter hooks to integrate with WordPress.
	 */
	public function initialize() {
		// Add support for Header and Footer Plugin.
		add_action( 'after_setup_theme', array( $this, 'init_header_footer_support' ), 30 );
		// Set page to best pagebuilder settings when first loading.
		add_action( 'wp', array( $this, 'elementor_page_meta_setting' ), 20 );
		add_action( 'elementor/preview/init', array( $this, 'elementor_page_meta_setting' ) );
	}
	/**
	 * Make sure it's not a post, then set the meta if the content is empty and we are in elementor.
	 */
	public function elementor_page_meta_setting() {
		if ( ! apply_filters( 'kadence_theme_elementor_default', true ) || 'post' === get_post_type() ) {
			return;
		}
		if ( ! $this->is_elementor() ) {
			return;
		}
		global $post;
		$page_id = get_the_ID();

		$page_builder_layout = get_post_meta( $page_id, '_kad_pagebuilder_layout_flag', true );
		if ( isset( $post ) && empty( $page_builder_layout ) && ( is_admin() || is_singular() ) ) {
			if ( empty( $post->post_content ) && $this->is_built_with_elementor( $page_id ) ) {
				update_post_meta( $page_id, '_kad_pagebuilder_layout_flag', 'disabled' );
				update_post_meta( $page_id, '_kad_post_title', 'hide' );
				update_post_meta( $page_id, '_kad_post_content_style', 'unboxed' );
				update_post_meta( $page_id, '_kad_post_vertical_padding', 'hide' );
				update_post_meta( $page_id, '_kad_post_feature', 'hide' );
				$post_layout = get_post_meta( $page_id, '_kad_post_layout', true );
				if ( empty( $post_layout ) || 'default' === $post_layout ) {
					update_post_meta( $page_id, '_kad_post_layout', 'fullwidth' );
				}
				$post_title = get_post_meta( $page_id, '_kad_post_title', true );
				if ( empty( $post_title ) || 'default' === $post_title ) {
					update_post_meta( $page_id, '_kad_post_title', 'hide' );
				}
			}
		}
	}
	/**
	 * Check if page is built with elementor
	 *
	 * @return boolean true if elementor edit false if not.
	 */
	private function is_built_with_elementor( $page_id ) {
		return Elementor\Plugin::$instance->db->is_built_with_elementor( $page_id );
	}
	/**
	 * Check if in elementor editor.
	 *
	 * @return boolean true if elementor edit false if not.
	 */
	private function is_elementor() {
		if ( ( isset( $_REQUEST['action'] ) && 'elementor' === $_REQUEST['action'] ) || isset( $_REQUEST['elementor-preview'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			return true;
		}

		return false;
	}
	/**
	 * Check for use. Then
	 * Run all the Actions / Filters.
	 */
	public function init_header_footer_support() {
		add_theme_support( 'header-footer-elementor' );
		if ( function_exists( 'hfe_header_enabled' ) ) {
			if ( hfe_header_enabled() ) {
				add_action( 'template_redirect', array( $this, 'remove_theme_header' ) );
				add_action( 'kadence_header', 'hfe_render_header' );
			}
		}
		if ( function_exists( 'hfe_footer_enabled' ) ) {
			if ( hfe_footer_enabled() ) {
				add_action( 'template_redirect', array( $this, 'remove_theme_footer' ) );
				add_action( 'kadence_footer', 'hfe_render_footer' );
			}
		}
	}
	/**
	 * Disable header from the theme.
	 */
	public function remove_theme_header() {
		remove_action( 'kadence_header', 'Kadence\header_markup' );
	}
	/**
	 * Disable header from the theme.
	 */
	public function remove_theme_footer() {
		remove_action( 'kadence_footer', 'Kadence\footer_markup' );
	}

}
