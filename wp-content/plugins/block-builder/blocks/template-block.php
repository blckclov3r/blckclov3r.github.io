<?php
namespace ElementorBlockBuilder\Blocks;

use ElementorBlockBuilder\Plugin;
use Elementor\Utils;
use Elementor\TemplateLibrary\Source_Local;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Template_Block {

	public function get_name() {
		return 'template';
	}

	public function allow_show_in_rest_elementor_templates() {
		global $wp_post_types;

		if ( isset( $wp_post_types[ Source_Local::CPT ] ) ) {
			$wp_post_types[ Source_Local::CPT ]->show_in_rest = is_user_logged_in();
			$wp_post_types[ Source_Local::CPT ]->rest_base = Source_Local::CPT;
			$wp_post_types[ Source_Local::CPT ]->rest_controller_class = 'WP_REST_Posts_Controller';
		}
	}

	public function register_block_category( $categories, $post ) {
		return array_merge(
			$categories,
			array(
				array(
					'slug' => 'elementor',
					'title' => __( 'Elementor Library', 'block-builder' ),
				),
			)
		);
	}

	public function register_block() {
		// Register our block script with WordPress
		wp_register_script(
			'elementor-block-builder',
			BLOCK_BUILDER_ASSETS_URL . 'js/template-block.min.js',
			[
				'wp-blocks',
				'wp-element',
			],
			ELEMENTOR_BLOCK_BUILDER_VERSION,
			true
		);

		wp_register_style(
			'elementor-block-builder',
			BLOCK_BUILDER_ASSETS_URL . 'css/template-block.min.css',
			[],
			ELEMENTOR_BLOCK_BUILDER_VERSION
		);

		register_block_type(
			'elementor/' . $this->get_name(),
			[
				'render_callback' => [ $this, 'elementor_template_block_render' ],
				'editor_script' => 'elementor-block-builder',
				'editor_style' => 'elementor-block-builder',
			]
		);

		// Prepare Jed locale data manually to avoid printing all of Elementor translations.
		$locale_data = [
			'' => [
				'domain' => 'block-builder',
				'lang' => is_admin() ? get_user_locale() : get_locale(),
			],
			'Edit Template with Elementor' => [ __( 'Edit Template with Elementor', 'block-builder' ) ],
			'Choose Template' => [ __( 'Choose Template', 'block-builder' ) ],
			'No Template Selected' => [ __( 'No Template Selected', 'block-builder' ) ],
			'Select a Template' => [ __( 'Select a Template', 'block-builder' ) ],
			'Select a template from your library or' => [ __('Select a template from your library or', 'block-builder' ) ],
			'create a new one.' => [ __('create a new one.', 'block-builder' ) ],
			'Loading...' => [ __( 'Loading...', 'block-builder' ) ],
			'Create Your First Template' => [ __( 'Create Your First Template', 'block-builder' ) ],
			'Settings' => [ __( 'Settings', 'block-builder' ) ],
			'Embed Elementor blocks and templates inside Gutenberg' => [ __( 'Embed Elementor blocks and templates inside Gutenberg', 'block-builder' ) ],
		];

		wp_add_inline_script(
			'elementor-block-builder',
			'wp.i18n.setLocaleData( ' . wp_json_encode( $locale_data ) . ', \'block-builder\' );',
			'before'
		);

		wp_localize_script(
			'elementor-block-builder',
			'elementorBlockBuilderConfig',
			[
				'edit_url_pattern' => admin_url( 'post.php?action=elementor&post=' ),
				'preview_url_pattern' => site_url( '?elementor-block=1&p=' ),
				'create_new_post_url' => add_query_arg( [ 'template_type' => 'section' ], Utils::get_create_new_post_url( Source_Local::CPT ) ),
			]
		);
	}

	public function elementor_template_block_render( $attributes ) {
		if ( isset( $attributes['selectedTemplate'] ) && ! is_admin() ) {
			return Plugin::elementor()->frontend->get_builder_content( $attributes['selectedTemplate'], true );
		}

		return '';
	}

	public function __construct() {
		add_action( 'init', [ $this, 'register_block' ], 100 );
		add_action( 'init', [ $this, 'allow_show_in_rest_elementor_templates' ], 250 );

		add_filter( 'block_categories', [ $this, 'register_block_category' ], 10, 2 );
	}
}
