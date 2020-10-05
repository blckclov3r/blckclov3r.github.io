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
use Elementor\Scheme_Typography;
use Elementor\Modules\DynamicTags\Module as TagsModule;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Dual Heading Widget
 */
class Dual_Heading extends Powerpack_Widget {

	/**
	 * Retrieve dual heading widget name.
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return parent::get_widget_name( 'Dual_Heading' );
	}

	/**
	 * Retrieve dual heading widget title.
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return parent::get_widget_title( 'Dual_Heading' );
	}

	/**
	 * Retrieve dual heading widget icon.
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return parent::get_widget_icon( 'Dual_Heading' );
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
		return parent::get_widget_keywords( 'Dual_Heading' );
	}

	/**
	 * Register dual heading widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @access protected
	 */
	protected function _register_controls() {

		/*
		-----------------------------------------------------------------------------------*/
		/*
		  CONTENT TAB
		/*-----------------------------------------------------------------------------------*/

		/**
		 * Content Tab: Dual Heading
		 */
		$this->start_controls_section(
			'section_dual_heading',
			array(
				'label' => __( 'Dual Heading', 'powerpack' ),
			)
		);

		$this->add_control(
			'first_text',
			array(
				'label'       => __( 'First Part', 'powerpack' ),
				'type'        => Controls_Manager::TEXTAREA,
				'dynamic'     => array(
					'active' => true,
				),
				'label_block' => true,
				'rows'        => 3,
				'default'     => __( 'Our', 'powerpack' ),
			)
		);

		$this->add_control(
			'second_text',
			array(
				'label'       => __( 'Second Part', 'powerpack' ),
				'type'        => Controls_Manager::TEXTAREA,
				'dynamic'     => array(
					'active' => true,
				),
				'label_block' => true,
				'rows'        => 3,
				'default'     => __( 'Services', 'powerpack' ),
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

		$this->add_control(
			'second_part_display',
			array(
				'label'        => __( 'Second Part Display', 'powerpack' ),
				'type'         => Controls_Manager::SELECT,
				'label_block'  => false,
				'default'      => 'inline-block',
				'options'      => array(
					'inline-block' => __( 'Inline', 'powerpack' ),
					'block'        => __( 'Block', 'powerpack' ),
				),
				'prefix_class' => 'pp-dual-heading-',
				'selectors'    => array(
					'{{WRAPPER}} .pp-second-text' => 'display: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'align',
			array(
				'label'       => __( 'Alignment', 'powerpack' ),
				'type'        => Controls_Manager::CHOOSE,
				'label_block' => true,
				'options'     => array(
					'left'   => array(
						'title' => __( 'Left', 'powerpack' ),
						'icon'  => 'fa fa-align-left',
					),
					'center' => array(
						'title' => __( 'Center', 'powerpack' ),
						'icon'  => 'fa fa-align-center',
					),
					'right'  => array(
						'title' => __( 'Right', 'powerpack' ),
						'icon'  => 'fa fa-align-right',
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
		 * Content Tab: Docs Links
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
				'raw'             => sprintf( __( '%1$s Widget Overview %2$s', 'powerpack' ), '<a href="https://powerpackelements.com/docs/powerpack/widgets/dual-heading/dual-heading-widget-overview/?utm_source=widget&utm_medium=panel&utm_campaign=userkb" target="_blank" rel="noopener">', '</a>' ),
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
				'label' => __( 'First Part', 'powerpack' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'first_text_color',
			array(
				'label'     => __( 'Text Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .pp-first-text' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'first_part_bg',
				'label'    => __( 'Background', 'powerpack' ),
				'types'    => array( 'none', 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .pp-first-text',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'first_typography',
				'label'     => __( 'Typography', 'powerpack' ),
				'scheme'    => Scheme_Typography::TYPOGRAPHY_4,
				'selector'  => '{{WRAPPER}} .pp-first-text',
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'      => 'first_border',
				'label'     => __( 'Border', 'powerpack' ),
				'default'   => '1px',
				'selector'  => '{{WRAPPER}} .pp-first-text',
				'separator' => 'before',
			)
		);

		$this->add_control(
			'first_border_radius',
			array(
				'label'      => __( 'Border Radius', 'powerpack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .pp-first-text' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'first_text_padding',
			array(
				'label'      => __( 'Padding', 'powerpack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .pp-first-text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			array(
				'name'      => 'first_text_shadow',
				'selector'  => '{{WRAPPER}} .pp-first-text',
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'      => 'first_box_shadow',
				'selector'  => '{{WRAPPER}} .pp-first-text',
				'separator' => 'before',
			)
		);

		$this->end_controls_section();

		/**
		 * Style Tab: Second Part
		 */
		$this->start_controls_section(
			'second_section_style',
			array(
				'label' => __( 'Second Part', 'powerpack' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'second_text_color',
			array(
				'label'     => __( 'Text Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .pp-second-text' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'second_part_bg',
				'label'    => __( 'Background', 'powerpack' ),
				'types'    => array( 'none', 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .pp-second-text',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'second_typography',
				'label'     => __( 'Typography', 'powerpack' ),
				'scheme'    => Scheme_Typography::TYPOGRAPHY_4,
				'selector'  => '{{WRAPPER}} .pp-second-text',
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'      => 'second_border',
				'label'     => __( 'Border', 'powerpack' ),
				'default'   => '1px',
				'selector'  => '{{WRAPPER}} .pp-second-text',
				'separator' => 'before',
			)
		);

		$this->add_control(
			'second_border_radius',
			array(
				'label'      => __( 'Border Radius', 'powerpack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .pp-second-text' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'second_text_margin',
			array(
				'label'          => __( 'Spacing', 'powerpack' ),
				'type'           => Controls_Manager::SLIDER,
				'size_units'     => array( '%', 'px' ),
				'default'        => array(
					'size' => 0,
					'unit' => 'px',
				),
				'range'          => array(
					'px' => array(
						'max' => 100,
					),
				),
				'tablet_default' => array(
					'unit' => 'px',
				),
				'mobile_default' => array(
					'unit' => 'px',
				),
				'selectors'      => array(
					'{{WRAPPER}}.pp-dual-heading-inline-block .pp-second-text' => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.pp-dual-heading-block .pp-second-text' => 'margin-top: {{SIZE}}{{UNIT}};',
				),
				'separator'      => 'before',
			)
		);

		$this->add_control(
			'second_text_padding',
			array(
				'label'      => __( 'Padding', 'powerpack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .pp-second-text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			array(
				'name'      => 'second_text_shadow',
				'selector'  => '{{WRAPPER}} .pp-second-text',
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'      => 'second_box_shadow',
				'selector'  => '{{WRAPPER}} .pp-second-text',
				'separator' => 'before',
			)
		);

		$this->end_controls_section();

	}

	/**
	 * Render dual heading widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		$this->add_render_attribute( 'dual-heading', 'class', 'pp-dual-heading' );
		$this->add_inline_editing_attributes( 'first_text', 'basic' );
		$this->add_render_attribute( 'first_text', 'class', 'pp-first-text' );
		$this->add_inline_editing_attributes( 'second_text', 'basic' );
		$this->add_render_attribute( 'second_text', 'class', 'pp-second-text' );

		if ( ! empty( $settings['link']['url'] ) ) {
			$this->add_link_attributes( 'dual-heading-link', $settings['link'] );
		}

		if ( $settings['first_text'] || $settings['second_text'] ) {
			printf( '<%1$s %2$s>', $settings['heading_html_tag'], $this->get_render_attribute_string( 'dual-heading' ) );
			if ( ! empty( $settings['link']['url'] ) ) {
				printf( '<a %1$s>', $this->get_render_attribute_string( 'dual-heading-link' ) ); }

			if ( $settings['first_text'] ) {
				printf( '<span %1$s>%2$s</span>', $this->get_render_attribute_string( 'first_text' ), $this->parse_text_editor( $settings['first_text'] ) );
			}
			if ( $settings['second_text'] ) {
				printf( ' <span %1$s>%2$s</span>', $this->get_render_attribute_string( 'second_text' ), $this->parse_text_editor( $settings['second_text'] ) );
			}

			if ( ! empty( $settings['link']['url'] ) ) {
				printf( '</a>' ); }
			printf( '</%1$s>', $settings['heading_html_tag'] );
		}
	}

	/**
	 * Render dual heading widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @access protected
	 */
	protected function _content_template() {
		?>
		<{{{settings.heading_html_tag}}} class="pp-dual-heading">
			<# if ( settings.link.url ) { #><a href="{{settings.link.url}}"><# } #>
				<#
				if ( settings.first_text != '' ) {
					var first_text = settings.first_text;

					view.addRenderAttribute( 'first_text', 'class', 'pp-first-text' );

					view.addInlineEditingAttributes( 'first_text' );

					var first_text_html = '<span' + ' ' + view.getRenderAttributeString( 'first_text' ) + '>' + first_text + '</span>';

					print( first_text_html );
				}

				if ( settings.second_text != '' ) {
					var second_text = settings.second_text;

					view.addRenderAttribute( 'second_text', 'class', 'pp-second-text' );

					view.addInlineEditingAttributes( 'second_text' );

					var second_text_html = '<span' + ' ' + view.getRenderAttributeString( 'second_text' ) + '>' + second_text + '</span>';

					print( second_text_html );
				}
				#>
			<# if ( settings.link.url ) { #></a><# } #>
		</{{{settings.heading_html_tag}}}>
		<?php
	}
}
