<?php
namespace PowerpackElementsLite\Modules\Headings\Widgets;

use PowerpackElementsLite\Base\Powerpack_Widget;

// Elementor Classes
use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Color;
use Elementor\Scheme_Typography;
use Elementor\Modules\DynamicTags\Module as TagsModule;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Fancy Heading Widget
 */
class Fancy_Heading extends Powerpack_Widget {

	/**
	 * Retrieve fancy heading widget name.
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return parent::get_widget_name( 'Fancy_Heading' );
	}

	/**
	 * Retrieve fancy heading widget title.
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return parent::get_widget_title( 'Fancy_Heading' );
	}

	/**
	 * Retrieve fancy heading widget icon.
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return parent::get_widget_icon( 'Fancy_Heading' );
	}

	/**
	 * Get widget keywords.
	 *
	 * Retrieve the list of keywords the widget belongs to.
	 *
	 * @access public
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return parent::get_widget_keywords( 'Fancy_Heading' );
	}

	/**
	 * Register fancy heading widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @access protected
	 */
	protected function _register_controls() {

		/**
		 * Content Tab: Fancy Heading
		 */
		$this->start_controls_section(
			'section_heading',
			array(
				'label' => __( 'Fancy Heading', 'powerpack' ),
			)
		);

		$this->add_control(
			'heading_text',
			array(
				'label'       => __( 'Heading', 'powerpack' ),
				'type'        => Controls_Manager::TEXTAREA,
				'dynamic'     => array(
					'active' => true,
				),
				'label_block' => true,
				'rows'        => 2,
				'default'     => __( 'Add Your Heading Text Here', 'powerpack' ),
			)
		);

		$this->add_control(
			'link',
			array(
				'label'       => __( 'Link', 'powerpack' ),
				'type'        => Controls_Manager::URL,
				'dynamic'     => array(
					'active'     => true,
					'categories' => array(
						TagsModule::POST_META_CATEGORY,
						TagsModule::URL_CATEGORY,
					),
				),
				'label_block' => true,
				'placeholder' => 'https://www.your-link.com',
			)
		);

		$this->add_control(
			'heading_html_tag',
			array(
				'label'       => __( 'HTML Tag', 'powerpack' ),
				'type'        => Controls_Manager::SELECT,
				'label_block' => false,
				'default'     => 'h2',
				'options'     => array(
					'h1'   => __( 'H1', 'powerpack' ),
					'h2'   => __( 'H2', 'powerpack' ),
					'h3'   => __( 'H3', 'powerpack' ),
					'h4'   => __( 'H4', 'powerpack' ),
					'h5'   => __( 'H5', 'powerpack' ),
					'h6'   => __( 'H6', 'powerpack' ),
					'div'  => __( 'div', 'powerpack' ),
					'span' => __( 'span', 'powerpack' ),
					'p'    => __( 'p', 'powerpack' ),
				),
			)
		);

		$this->add_responsive_control(
			'align',
			array(
				'label'       => __( 'Alignment', 'powerpack' ),
				'type'        => Controls_Manager::CHOOSE,
				'label_block' => false,
				'options'     => array(
					'left'    => array(
						'title' => __( 'Left', 'powerpack' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center'  => array(
						'title' => __( 'Center', 'powerpack' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right'   => array(
						'title' => __( 'Right', 'powerpack' ),
						'icon'  => 'eicon-text-align-right',
					),
					'justify' => array(
						'title' => __( 'Justified', 'powerpack' ),
						'icon'  => 'eicon-text-align-justify',
					),
				),
				'default'     => '',
				'selectors'   => array(
					'{{WRAPPER}}' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		/**
		 * Content Tab: Help Docs
		 *
		 * @since 1.4.8
		 * @access protected
		 */
		$this->start_controls_section(
			'section_help_docs',
			array(
				'label' => __( 'Help Docs', 'powerpack' ),
			)
		);

		$this->add_control(
			'help_doc_1',
			array(
				'type'            => Controls_Manager::RAW_HTML,
				/* translators: %1$s doc link */
				'raw'             => sprintf( __( '%1$s Watch Video Overview %2$s', 'powerpack' ), '<a href="https://www.youtube.com/watch?v=PxWWUTeW4dc&list=PLpsSO_wNe8Dz4vfe2tWlySBCCFEgh1qZj" target="_blank" rel="noopener">', '</a>' ),
				'content_classes' => 'pp-editor-doc-links',
			)
		);

		$this->end_controls_section();

		if ( ! is_pp_elements_active() ) {
			/**
			 * Content Tab: Upgrade PowerPack
			 *
			 * @since 1.2.9.4
			 * @access protected
			 */
			$this->start_controls_section(
				'section_upgrade_powerpack',
				array(
					'label' => apply_filters( 'upgrade_powerpack_title', __( 'Get PowerPack Pro', 'powerpack' ) ),
					'tab'   => Controls_Manager::TAB_CONTENT,
				)
			);

			$this->add_control(
				'upgrade_powerpack_notice',
				array(
					'label'           => '',
					'type'            => Controls_Manager::RAW_HTML,
					'raw'             => apply_filters( 'upgrade_powerpack_message', sprintf( __( 'Upgrade to %1$s Pro Version %2$s for 70+ widgets, exciting extensions and advanced features.', 'powerpack' ), '<a href="#" target="_blank" rel="noopener">', '</a>' ) ),
					'content_classes' => 'upgrade-powerpack-notice elementor-panel-alert elementor-panel-alert-info',
				)
			);

			$this->end_controls_section();
		}

		/*
		-----------------------------------------------------------------------------------*/
		/*
		  STYLE TAB
		/*-----------------------------------------------------------------------------------*/

		/**
		 * Style Tab: First Part
		 */
		$this->start_controls_section(
			'first_section_style',
			array(
				'label' => __( 'Fancy Heading', 'powerpack' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'typography',
				'label'    => __( 'Typography', 'powerpack' ),
				'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .pp-heading-text',
			)
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			array(
				'name'     => 'heading_text_shadow',
				'selector' => '{{WRAPPER}} .pp-heading-text',
			)
		);

		$this->add_control(
			'heading_fill',
			array(
				'label'        => __( 'Fill', 'powerpack' ),
				'type'         => Controls_Manager::SELECT,
				'options'      => array(
					'solid'    => __( 'Color', 'powerpack' ),
					'gradient' => __( 'Background', 'powerpack' ),
				),
				'default'      => 'solid',
				'prefix_class' => 'pp-heading-fill-',
				'separator'    => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'      => 'gradient',
				'types'     => array( 'gradient', 'classic' ),
				'selector'  => '{{WRAPPER}} .pp-heading-text',
				'default'   => 'gradient',
				'condition' => array(
					'heading_fill' => 'gradient',
				),
			)
		);

		$this->add_control(
			'heading_text_color',
			array(
				'label'     => __( 'Text Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'scheme'    => array(
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_1,
				),
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .pp-heading-text' => 'color: {{VALUE}};',
				),
				'condition' => array(
					'heading_fill' => 'solid',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Render fancy heading widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.2.6
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		$this->add_render_attribute( 'fancy-heading', 'class', 'pp-fancy-heading' );
		$this->add_inline_editing_attributes( 'heading_text', 'basic' );
		$this->add_render_attribute( 'heading_text', 'class', 'pp-heading-text' );

		if ( ! empty( $settings['link']['url'] ) ) {
			$this->add_link_attributes( 'fancy-heading-link', $settings['link'] );
		}

		if ( $settings['heading_text'] ) {
			printf( '<%1$s %2$s>', $settings['heading_html_tag'], $this->get_render_attribute_string( 'fancy-heading' ) );
			if ( ! empty( $settings['link']['url'] ) ) {
				printf( '<a %1$s>', $this->get_render_attribute_string( 'fancy-heading-link' ) ); }

				printf( '<span %1$s>%2$s</span>', $this->get_render_attribute_string( 'heading_text' ), $this->parse_text_editor( $settings['heading_text'] ) );

			if ( ! empty( $settings['link']['url'] ) ) {
				printf( '</a>' ); }
			printf( '</%1$s>', $settings['heading_html_tag'] );
		}
	}

	/**
	 * Render fancy heading widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @access protected
	 */
	protected function _content_template() {
		?>
		<{{{settings.heading_html_tag}}} class="pp-fancy-heading">
			<# if ( settings.link.url ) { #><a href="{{settings.link.url}}"><# } #>
				<#
				if ( settings.heading_text != '' ) {
					var heading_text = settings.heading_text;

					view.addRenderAttribute( 'heading_text', 'class', 'pp-heading-text' );

					view.addInlineEditingAttributes( 'heading_text' );

					var heading_text_html = '<span' + ' ' + view.getRenderAttributeString( 'heading_text' ) + '>' + heading_text + '</span>';

					print( heading_text_html );
				}
				#>
			<# if ( settings.link.url ) { #></a><# } #>
		</{{{settings.heading_html_tag}}}>
		<?php
	}
}
