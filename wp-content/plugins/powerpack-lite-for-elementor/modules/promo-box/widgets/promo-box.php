<?php
namespace PowerpackElementsLite\Modules\PromoBox\Widgets;

use PowerpackElementsLite\Base\Powerpack_Widget;

// Elementor Classes
use Elementor\Controls_Manager;
use Elementor\Utils;
use Elementor\Icons_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Image_Size;
use Elementor\Scheme_Typography;
use Elementor\Modules\DynamicTags\Module as TagsModule;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Promo Box Widget
 */
class Promo_Box extends Powerpack_Widget {

	/**
	 * Retrieve promo box widget name.
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return parent::get_widget_name( 'Promo_Box' );
	}

	/**
	 * Retrieve promo box widget title.
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return parent::get_widget_title( 'Promo_Box' );
	}

	/**
	 * Retrieve promo box widget icon.
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return parent::get_widget_icon( 'Promo_Box' );
	}

	/**
	 * Get widget keywords.
	 *
	 * Retrieve the list of keywords the widget belongs to.
	 *
	 * @since 1.4.13.1
	 * @access public
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return parent::get_widget_keywords( 'Promo_Box' );
	}

	/**
	 * Register promo box widget controls.
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
		 * Content Tab: Content
		 */
		$this->start_controls_section(
			'section_promo_box',
			array(
				'label' => __( 'Content', 'powerpack' ),
			)
		);

		$this->add_control(
			'heading',
			array(
				'label'   => __( 'Heading', 'powerpack' ),
				'type'    => Controls_Manager::TEXT,
				'dynamic' => array(
					'active' => true,
				),
				'default' => __( 'Heading', 'powerpack' ),
			)
		);

		$this->add_control(
			'heading_html_tag',
			array(
				'label'   => __( 'Heading HTML Tag', 'powerpack' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'h4',
				'options' => array(
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
			'divider_heading_switch',
			array(
				'label'        => __( 'Heading Divider', 'powerpack' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '',
				'label_on'     => __( 'On', 'powerpack' ),
				'label_off'    => __( 'Off', 'powerpack' ),
				'return_value' => 'yes',
				'condition'    => array(
					'heading!' => '',
				),
			)
		);

		$this->add_control(
			'sub_heading',
			array(
				'label'   => __( 'Sub Heading', 'powerpack' ),
				'type'    => Controls_Manager::TEXT,
				'dynamic' => array(
					'active' => true,
				),
				'default' => __( 'Sub heading', 'powerpack' ),
			)
		);

		$this->add_control(
			'sub_heading_html_tag',
			array(
				'label'   => __( 'Sub Heading HTML Tag', 'powerpack' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'h5',
				'options' => array(
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
			'divider_subheading_switch',
			array(
				'label'        => __( 'Sub Heading Divider', 'powerpack' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '',
				'label_on'     => __( 'On', 'powerpack' ),
				'label_off'    => __( 'Off', 'powerpack' ),
				'return_value' => 'yes',
				'condition'    => array(
					'sub_heading!' => '',
				),
			)
		);

		$this->add_control(
			'content',
			array(
				'label'   => __( 'Description', 'powerpack' ),
				'type'    => Controls_Manager::TEXTAREA,
				'dynamic' => array(
					'active' => true,
				),
				'default' => __( 'Enter promo box description', 'powerpack' ),
			)
		);

		$this->end_controls_section();

		/**
		 * Content Tab: Icon
		 */
		$this->start_controls_section(
			'section_promo_box_icon',
			array(
				'label' => __( 'Icon', 'powerpack' ),
			)
		);

		$this->add_control(
			'icon_switch',
			array(
				'label'        => __( 'Show Icon', 'powerpack' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '',
				'label_on'     => __( 'Yes', 'powerpack' ),
				'label_off'    => __( 'No', 'powerpack' ),
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'icon_type',
			array(
				'label'     => __( 'Icon Type', 'powerpack' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'icon',
				'options'   => array(
					'icon'  => __( 'Icon', 'powerpack' ),
					'image' => __( 'Image', 'powerpack' ),
				),
				'condition' => array(
					'icon_switch' => 'yes',
				),
			)
		);

		$this->add_control(
			'selected_icon',
			array(
				'label'            => __( 'Choose', 'powerpack' ) . ' ' . __( 'Icon', 'powerpack' ),
				'type'             => Controls_Manager::ICONS,
				'fa4compatibility' => 'icon',
				'default'          => array(
					'value'   => 'fas fa-gem',
					'library' => 'fa-solid',
				),
				'condition'        => array(
					'icon_switch' => 'yes',
					'icon_type'   => 'icon',
				),
			)
		);

		$this->add_control(
			'icon_image',
			array(
				'label'     => __( 'Image', 'powerpack' ),
				'type'      => Controls_Manager::MEDIA,
				'default'   => array(
					'url' => Utils::get_placeholder_image_src(),
				),
				'condition' => array(
					'icon_switch' => 'yes',
					'icon_type'   => 'image',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			array(
				'name'      => 'image', // Usage: '{name}_size' and '{name}_custom_dimension', in this case 'image_size' and 'image_custom_dimension'.
				'default'   => 'full',
				'separator' => 'none',
				'condition' => array(
					'icon_switch' => 'yes',
					'icon_type'   => 'image',
				),
			)
		);

		$this->add_control(
			'icon_position',
			array(
				'label'     => __( 'Icon Position', 'powerpack' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'above-title',
				'options'   => array(
					'above-title' => __( 'Above Title', 'powerpack' ),
					'below-title' => __( 'Below Title', 'powerpack' ),
				),
				'condition' => array(
					'icon_switch' => 'yes',
				),
			)
		);

		$this->end_controls_section();

		/**
		 * Content Tab: Button
		 */
		$this->start_controls_section(
			'section_promo_box_button',
			array(
				'label' => __( 'Button', 'powerpack' ),
			)
		);

		$this->add_control(
			'button_switch',
			array(
				'label'        => __( 'Button', 'powerpack' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '',
				'label_on'     => __( 'On', 'powerpack' ),
				'label_off'    => __( 'Off', 'powerpack' ),
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'button_text',
			array(
				'label'     => __( 'Button Text', 'powerpack' ),
				'type'      => Controls_Manager::TEXT,
				'dynamic'   => array(
					'active' => true,
				),
				'default'   => __( 'Get Started', 'powerpack' ),
				'condition' => array(
					'button_switch' => 'yes',
				),
			)
		);

		$this->add_control(
			'link',
			array(
				'label'       => __( 'Link', 'powerpack' ),
				'type'        => Controls_Manager::URL,
				'label_block' => true,
				'dynamic'     => array(
					'active' => true,
				),
				'placeholder' => 'https://www.your-link.com',
				'default'     => array(
					'url' => '#',
				),
				'condition'   => array(
					'button_switch' => 'yes',
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
				'raw'             => sprintf( __( '%1$s Widget Overview %2$s', 'powerpack' ), '<a href="https://powerpackelements.com/docs/powerpack/widgets/promo-box/promo-box-widget-overview/?utm_source=widget&utm_medium=panel&utm_campaign=userkb" target="_blank" rel="noopener">', '</a>' ),
				'content_classes' => 'pp-editor-doc-links',
			)
		);

		$this->end_controls_section();

		if ( ! is_pp_elements_active() ) {
			/**
			 * Content Tab: Upgrade PowerPack
			 *
			 * @since 1.2.9.4
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
		 * Style Tab: Promo Box
		 */
		$this->start_controls_section(
			'section_promo_box_style',
			array(
				'label' => __( 'Promo Box', 'powerpack' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'box_bg',
				'label'    => __( 'Background', 'powerpack' ),
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .pp-promo-box-bg',
			)
		);

		$this->add_responsive_control(
			'box_height',
			array(
				'label'      => __( 'Height', 'powerpack' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => array(
					'px' => array(
						'min'  => 50,
						'max'  => 1000,
						'step' => 1,
					),
				),
				'size_units' => array( 'px', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .pp-promo-box' => 'height: {{SIZE}}{{UNIT}}',
				),
				'separator'  => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'promo_box_border',
				'label'       => __( 'Border', 'powerpack' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .pp-promo-box-wrap',
			)
		);

		$this->add_control(
			'promo_box_border_radius',
			array(
				'label'      => __( 'Border Radius', 'powerpack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .pp-promo-box, {{WRAPPER}} .pp-promo-box-wrap, {{WRAPPER}} .pp-promo-box .pp-promo-box-banner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'box_padding',
			array(
				'label'      => __( 'Padding', 'powerpack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .pp-promo-box-wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		/**
		 * Style Tab: Overlay
		 */
		$this->start_controls_section(
			'section_promo_overlay_style',
			array(
				'label' => __( 'Overlay', 'powerpack' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'overlay_switch',
			array(
				'label'        => __( 'Overlay', 'powerpack' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '',
				'label_on'     => __( 'On', 'powerpack' ),
				'label_off'    => __( 'Off', 'powerpack' ),
				'return_value' => 'yes',
				'separator'    => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'      => 'overlay_color',
				'label'     => __( 'Color', 'powerpack' ),
				'types'     => array( 'classic', 'gradient' ),
				'selector'  => '{{WRAPPER}} .pp-promo-box-overlay',
				'condition' => array(
					'overlay_switch' => 'yes',
				),
			)
		);

		$this->end_controls_section();

		/**
		 * Style Tab: Content
		 */
		$this->start_controls_section(
			'section_promo_content_style',
			array(
				'label' => __( 'Content', 'powerpack' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'align',
			array(
				'label'       => __( 'Alignment', 'powerpack' ),
				'type'        => Controls_Manager::CHOOSE,
				'label_block' => false,
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
					'{{WRAPPER}} .pp-promo-box' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'vertical_align',
			array(
				'label'       => __( 'Vertical Alignment', 'powerpack' ),
				'type'        => Controls_Manager::CHOOSE,
				'label_block' => false,
				'default'     => 'middle',
				'options'     => array(
					'top'    => array(
						'title' => __( 'Top', 'powerpack' ),
						'icon'  => 'eicon-v-align-top',
					),
					'middle' => array(
						'title' => __( 'Center', 'powerpack' ),
						'icon'  => 'eicon-v-align-middle',
					),
					'bottom' => array(
						'title' => __( 'Bottom', 'powerpack' ),
						'icon'  => 'eicon-v-align-bottom',
					),
				),
				'selectors'   => array(
					'{{WRAPPER}} .pp-promo-box-inner-content'   => 'vertical-align: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'      => 'content_bg',
				'label'     => __( 'Background', 'powerpack' ),
				'types'     => array( 'classic', 'gradient' ),
				'selector'  => '{{WRAPPER}} .pp-promo-box-inner',
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'content_border',
				'label'       => __( 'Border', 'powerpack' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .pp-promo-box-inner',
				'separator'   => 'before',
			)
		);

		$this->add_control(
			'content_border_radius',
			array(
				'label'      => __( 'Border Radius', 'powerpack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .pp-promo-box-inner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'content_width',
			array(
				'label'      => __( 'Width', 'powerpack' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 1200,
						'step' => 1,
					),
					'%'  => array(
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					),
				),
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .pp-promo-box-wrap' => 'width: {{SIZE}}{{UNIT}}',
				),
				'separator'  => 'before',
			)
		);

		$this->add_responsive_control(
			'content_padding',
			array(
				'label'      => __( 'Padding', 'powerpack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .pp-promo-box-inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		/**
		 * Style Tab: Icon Section
		 */
		$this->start_controls_section(
			'section_promo_box_icon_style',
			array(
				'label'     => __( 'Icon', 'powerpack' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'icon_switch' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'icon_size',
			array(
				'label'      => __( 'Icon Size', 'powerpack' ),
				'type'       => Controls_Manager::SLIDER,
				'default'    => array(
					'unit' => 'px',
					'size' => 30,
				),
				'range'      => array(
					'px' => array(
						'min'  => 5,
						'max'  => 200,
						'step' => 1,
					),
				),
				'size_units' => array( 'px', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .pp-promo-box-icon' => 'font-size: {{SIZE}}{{UNIT}}',
				),
				'condition'  => array(
					'icon_switch' => 'yes',
					'icon_type'   => 'icon',
				),
			)
		);

		$this->add_responsive_control(
			'icon_img_width',
			array(
				'label'      => __( 'Width', 'powerpack' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => array(
					'px' => array(
						'min'  => 1,
						'max'  => 500,
						'step' => 1,
					),
					'%'  => array(
						'min'  => 1,
						'max'  => 100,
						'step' => 1,
					),
				),
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .pp-promo-box-icon img' => 'width: {{SIZE}}{{UNIT}}',
				),
				'condition'  => array(
					'icon_switch' => 'yes',
					'icon_type'   => 'image',
				),
			)
		);

		$this->start_controls_tabs( 'tabs_icon_style' );

		$this->start_controls_tab(
			'tab_icon_normal',
			array(
				'label' => __( 'Normal', 'powerpack' ),
			)
		);

		$this->add_control(
			'icon_color_normal',
			array(
				'label'     => __( 'Icon Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .pp-promo-box-icon'     => 'color: {{VALUE}}',
					'{{WRAPPER}} .pp-promo-box-icon svg' => 'fill: {{VALUE}}',
				),
				'condition' => array(
					'icon_switch' => 'yes',
					'icon_type'   => 'icon',
				),
			)
		);

		$this->add_control(
			'icon_bg_color_normal',
			array(
				'label'     => __( 'Background Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .pp-promo-box-icon' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_responsive_control(
			'icon_padding',
			array(
				'label'      => __( 'Padding', 'powerpack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .pp-promo-box-icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'icon_border',
				'label'       => __( 'Border', 'powerpack' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .pp-promo-box-icon',
			)
		);

		$this->add_control(
			'icon_border_radius',
			array(
				'label'      => __( 'Border Radius', 'powerpack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .pp-promo-box-icon, {{WRAPPER}} .pp-promo-box-icon img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'icon_margin',
			array(
				'label'       => __( 'Margin', 'powerpack' ),
				'type'        => Controls_Manager::DIMENSIONS,
				'size_units'  => array( 'px', '%' ),
				'placeholder' => array(
					'top'    => '',
					'right'  => '',
					'bottom' => '',
					'left'   => '',
				),
				'selectors'   => array(
					'{{WRAPPER}} .pp-promo-box-icon' => 'margin-top: {{TOP}}{{UNIT}}; margin-left: {{LEFT}}{{UNIT}}; margin-right: {{RIGHT}}{{UNIT}}; margin-bottom: {{BOTTOM}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_icon_hover',
			array(
				'label' => __( 'Hover', 'powerpack' ),
			)
		);

		$this->add_control(
			'icon_color_hover',
			array(
				'label'     => __( 'Icon Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .pp-promo-box-icon:hover' => 'color: {{VALUE}}',
					'{{WRAPPER}} .pp-promo-box-icon:hover svg' => 'fill: {{VALUE}}',
				),
				'condition' => array(
					'icon_switch' => 'yes',
					'icon_type'   => 'icon',
				),
			)
		);

		$this->add_control(
			'icon_bg_color_hover',
			array(
				'label'     => __( 'Background Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .pp-promo-box-icon:hover' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'hover_animation_icon',
			array(
				'label' => __( 'Icon Animation', 'powerpack' ),
				'type'  => Controls_Manager::HOVER_ANIMATION,
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		/**
		 * Style Tab: Heading Section
		 */
		$this->start_controls_section(
			'section_promo_box_heading_style',
			array(
				'label' => __( 'Heading', 'powerpack' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'title_color',
			array(
				'label'     => __( 'Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .pp-promo-box-title' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'title_typography',
				'label'    => __( 'Typography', 'powerpack' ),
				'selector' => '{{WRAPPER}} .pp-promo-box-title',
			)
		);

		$this->add_responsive_control(
			'title_margin',
			array(
				'label'      => __( 'Spacing', 'powerpack' ),
				'type'       => Controls_Manager::SLIDER,
				'default'    => array(
					'size' => 20,
				),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					),
					'%'  => array(
						'min'  => 0,
						'max'  => 30,
						'step' => 1,
					),
				),
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .pp-promo-box-title' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->end_controls_section();

		/**
		 * Style Tab: Heading Divider Section
		 */
		$this->start_controls_section(
			'section_heading_divider_style',
			array(
				'label'     => __( 'Heading Divider', 'powerpack' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'divider_heading_switch' => 'yes',
				),
			)
		);

		$this->add_control(
			'divider_heading_type',
			array(
				'label'     => __( 'Divider Type', 'powerpack' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'border',
				'options'   => array(
					'border' => __( 'Border', 'powerpack' ),
					'image'  => __( 'Image', 'powerpack' ),
				),
				'condition' => array(
					'divider_heading_switch' => 'yes',
				),
			)
		);

		$this->add_control(
			'divider_title_image',
			array(
				'label'     => __( 'Image', 'powerpack' ),
				'type'      => Controls_Manager::MEDIA,
				'condition' => array(
					'divider_heading_switch' => 'yes',
					'divider_heading_type'   => 'image',
				),
			)
		);

		$this->add_control(
			'divider_heading_border_type',
			array(
				'label'     => __( 'Border Type', 'powerpack' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'solid',
				'options'   => array(
					'solid'  => __( 'Solid', 'powerpack' ),
					'double' => __( 'Double', 'powerpack' ),
					'dotted' => __( 'Dotted', 'powerpack' ),
					'dashed' => __( 'Dashed', 'powerpack' ),
				),
				'selectors' => array(
					'{{WRAPPER}} .pp-promo-box-heading-divider' => 'border-bottom-style: {{VALUE}}',
				),
				'condition' => array(
					'divider_heading_switch' => 'yes',
					'divider_heading_type'   => 'border',
				),
			)
		);

		$this->add_responsive_control(
			'divider_title_width',
			array(
				'label'      => __( 'Width', 'powerpack' ),
				'type'       => Controls_Manager::SLIDER,
				'default'    => array(
					'size' => 30,
				),
				'range'      => array(
					'px' => array(
						'min'  => 1,
						'max'  => 1000,
						'step' => 1,
					),
					'%'  => array(
						'min'  => 1,
						'max'  => 100,
						'step' => 1,
					),
				),
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .pp-promo-box-heading-divider' => 'width: {{SIZE}}{{UNIT}}',
				),
				'condition'  => array(
					'divider_heading_switch' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'divider_heading_border_weight',
			array(
				'label'      => __( 'Border Weight', 'powerpack' ),
				'type'       => Controls_Manager::SLIDER,
				'default'    => array(
					'size' => 4,
				),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 50,
						'step' => 1,
					),
				),
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'{{WRAPPER}} .pp-promo-box-heading-divider' => 'border-bottom-width: {{SIZE}}{{UNIT}}',
				),
				'condition'  => array(
					'divider_heading_switch' => 'yes',
					'divider_heading_type'   => 'border',
				),
			)
		);

		$this->add_control(
			'divider_heading_border_color',
			array(
				'label'     => __( 'Border Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#000000',
				'selectors' => array(
					'{{WRAPPER}} .pp-promo-box-heading-divider' => 'border-bottom-color: {{VALUE}}',
				),
				'condition' => array(
					'divider_heading_switch' => 'yes',
					'divider_heading_type'   => 'border',
				),
			)
		);

		$this->add_responsive_control(
			'divider_title_margin',
			array(
				'label'      => __( 'Spacing', 'powerpack' ),
				'type'       => Controls_Manager::SLIDER,
				'default'    => array(
					'size' => 20,
				),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					),
					'%'  => array(
						'min'  => 0,
						'max'  => 30,
						'step' => 1,
					),
				),
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .pp-promo-box-heading-divider-wrap' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				),
				'condition'  => array(
					'divider_heading_switch' => 'yes',
				),
			)
		);

		$this->end_controls_section();

		/**
		 * Style Tab: Sub Heading Section
		 */
		$this->start_controls_section(
			'section_subheading_style',
			array(
				'label' => __( 'Sub Heading', 'powerpack' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'subtitle_color',
			array(
				'label'     => __( 'Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .pp-promo-box-subtitle' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'subtitle_typography',
				'label'    => __( 'Typography', 'powerpack' ),
				'selector' => '{{WRAPPER}} .pp-promo-box-subtitle',
			)
		);

		$this->add_responsive_control(
			'subtitle_margin',
			array(
				'label'      => __( 'Spacing', 'powerpack' ),
				'type'       => Controls_Manager::SLIDER,
				'default'    => array(
					'size' => 20,
				),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					),
					'%'  => array(
						'min'  => 0,
						'max'  => 30,
						'step' => 1,
					),
				),
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .pp-promo-box-subtitle' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->end_controls_section();

		/**
		 * Style Tab: Heading Divider Section
		 */
		$this->start_controls_section(
			'section_subheading_divider_style',
			array(
				'label'     => __( 'Sub Heading Divider', 'powerpack' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'divider_subheading_switch' => 'yes',
				),
			)
		);

		$this->add_control(
			'divider_subheading_type',
			array(
				'label'     => __( 'Divider Type', 'powerpack' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'border',
				'options'   => array(
					'border' => __( 'Border', 'powerpack' ),
					'image'  => __( 'Image', 'powerpack' ),
				),
				'condition' => array(
					'divider_subheading_switch' => 'yes',
				),
			)
		);

		$this->add_control(
			'divider_subheading_image',
			array(
				'label'     => __( 'Image', 'powerpack' ),
				'type'      => Controls_Manager::MEDIA,
				'condition' => array(
					'divider_subheading_switch' => 'yes',
					'divider_subheading_type'   => 'image',
				),
			)
		);

		$this->add_control(
			'divider_subheading_border_type',
			array(
				'label'     => __( 'Border Type', 'powerpack' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'solid',
				'options'   => array(
					'solid'  => __( 'Solid', 'powerpack' ),
					'double' => __( 'Double', 'powerpack' ),
					'dotted' => __( 'Dotted', 'powerpack' ),
					'dashed' => __( 'Dashed', 'powerpack' ),
				),
				'selectors' => array(
					'{{WRAPPER}} .pp-promo-box-subheading-divider' => 'border-bottom-style: {{VALUE}}',
				),
				'condition' => array(
					'divider_subheading_switch' => 'yes',
					'divider_subheading_type'   => 'border',
				),
			)
		);

		$this->add_responsive_control(
			'divider_subheading_width',
			array(
				'label'      => __( 'Width', 'powerpack' ),
				'type'       => Controls_Manager::SLIDER,
				'default'    => array(
					'size' => 30,
				),
				'range'      => array(
					'px' => array(
						'min'  => 1,
						'max'  => 1000,
						'step' => 1,
					),
					'%'  => array(
						'min'  => 1,
						'max'  => 100,
						'step' => 1,
					),
				),
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .pp-promo-box-subheading-divider' => 'width: {{SIZE}}{{UNIT}}',
				),
				'condition'  => array(
					'divider_subheading_switch' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'divider_subheading_border_weight',
			array(
				'label'      => __( 'Border Weight', 'powerpack' ),
				'type'       => Controls_Manager::SLIDER,
				'default'    => array(
					'size' => 4,
				),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 50,
						'step' => 1,
					),
				),
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'{{WRAPPER}} .pp-promo-box-subheading-divider' => 'border-bottom-width: {{SIZE}}{{UNIT}}',
				),
				'condition'  => array(
					'divider_subheading_switch' => 'yes',
					'divider_subheading_type'   => 'border',
				),
			)
		);

		$this->add_control(
			'divider_subheading_border_color',
			array(
				'label'     => __( 'Border Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#000000',
				'selectors' => array(
					'{{WRAPPER}} .pp-promo-box-subheading-divider' => 'border-bottom-color: {{VALUE}}',
				),
				'condition' => array(
					'divider_subheading_switch' => 'yes',
					'divider_subheading_type'   => 'border',
				),
			)
		);

		$this->add_responsive_control(
			'divider_subheading_margin',
			array(
				'label'      => __( 'Spacing', 'powerpack' ),
				'type'       => Controls_Manager::SLIDER,
				'default'    => array(
					'size' => 20,
				),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					),
					'%'  => array(
						'min'  => 0,
						'max'  => 30,
						'step' => 1,
					),
				),
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .pp-promo-box-subheading-divider-wrap' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->end_controls_section();

		/**
		 * Style Tab: Description Section
		 */
		$this->start_controls_section(
			'section_promo_description_style',
			array(
				'label' => __( 'Description', 'powerpack' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'content_color',
			array(
				'label'     => __( 'Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .pp-promo-box-content' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'content_typography',
				'label'    => __( 'Typography', 'powerpack' ),
				'selector' => '{{WRAPPER}} .pp-promo-box-content',
			)
		);

		$this->add_responsive_control(
			'content_margin',
			array(
				'label'      => __( 'Spacing', 'powerpack' ),
				'type'       => Controls_Manager::SLIDER,
				'default'    => array(
					'size' => 0,
				),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					),
					'%'  => array(
						'min'  => 0,
						'max'  => 30,
						'step' => 1,
					),
				),
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .pp-promo-box-content' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->end_controls_section();

		/**
		 * Style Tab: Footer Section
		 */

		$this->start_controls_section(
			'section_promo_box_button_style',
			array(
				'label'     => __( 'Button', 'powerpack' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'button_switch' => 'yes',
				),
			)
		);

		$this->add_control(
			'button_size',
			array(
				'label'     => __( 'Size', 'powerpack' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'md',
				'options'   => array(
					'xs' => __( 'Extra Small', 'powerpack' ),
					'sm' => __( 'Small', 'powerpack' ),
					'md' => __( 'Medium', 'powerpack' ),
					'lg' => __( 'Large', 'powerpack' ),
					'xl' => __( 'Extra Large', 'powerpack' ),
				),
				'condition' => array(
					'button_text!' => '',
				),
			)
		);

		$this->start_controls_tabs( 'tabs_button_style' );

		$this->start_controls_tab(
			'tab_button_normal',
			array(
				'label' => __( 'Normal', 'powerpack' ),
			)
		);

		$this->add_control(
			'button_bg_color_normal',
			array(
				'label'     => __( 'Background Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .pp-promo-box-button' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'button_text_color_normal',
			array(
				'label'     => __( 'Text Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .pp-promo-box-button' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'button_border_normal',
				'label'       => __( 'Border', 'powerpack' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .pp-promo-box-button',
			)
		);

		$this->add_control(
			'button_border_radius',
			array(
				'label'      => __( 'Border Radius', 'powerpack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .pp-promo-box-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'button_typography',
				'label'    => __( 'Typography', 'powerpack' ),
				'selector' => '{{WRAPPER}} .pp-promo-box-button',
			)
		);

		$this->add_responsive_control(
			'button_padding',
			array(
				'label'      => __( 'Padding', 'powerpack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .pp-promo-box-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'button_box_shadow',
				'selector' => '{{WRAPPER}} .pp-promo-box-button',
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_button_hover',
			array(
				'label' => __( 'Hover', 'powerpack' ),
			)
		);

		$this->add_control(
			'button_bg_color_hover',
			array(
				'label'     => __( 'Background Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .pp-promo-box-button:hover' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'button_text_color_hover',
			array(
				'label'     => __( 'Text Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .pp-promo-box-button:hover' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'button_border_color_hover',
			array(
				'label'     => __( 'Border Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .pp-promo-box-button:hover' => 'border-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'button_hover_animation',
			array(
				'label' => __( 'Animation', 'powerpack' ),
				'type'  => Controls_Manager::HOVER_ANIMATION,
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'button_box_shadow_hover',
				'selector' => '{{WRAPPER}} .pp-promo-box-button:hover',
			)
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	/**
	 * Render promo box widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @access protected
	 */
	protected function render_promobox_icon() {
		$settings = $this->get_settings_for_display();

		$this->add_render_attribute( 'icon', 'class', array( 'pp-promo-box-icon', 'pp-icon' ) );

		if ( $settings['hover_animation_icon'] ) {
			$this->add_render_attribute( 'icon', 'class', 'elementor-animation-' . $settings['hover_animation_icon'] );
		}

		if ( ! isset( $settings['icon'] ) && ! Icons_Manager::is_migration_allowed() ) {
			// add old default
			$settings['icon'] = 'fa fa-star';
		}

		$has_icon = ! empty( $settings['icon'] );

		if ( $has_icon ) {
			$this->add_render_attribute( 'i', 'class', $settings['icon'] );
			$this->add_render_attribute( 'i', 'aria-hidden', 'true' );
		}

		$icon_attributes = $this->get_render_attribute_string( 'icon' );

		if ( ! $has_icon && ! empty( $settings['selected_icon']['value'] ) ) {
			$has_icon = true;
		}
		$migrated = isset( $settings['__fa4_migrated']['selected_icon'] );
		$is_new   = ! isset( $settings['icon'] ) && Icons_Manager::is_migration_allowed();
		?>
		 <div class="pp-promo-box-icon-wrap">
			<span <?php echo $this->get_render_attribute_string( 'icon' ); ?>>
				<?php if ( $settings['icon_type'] == 'icon' ) { ?>
					<span class="pp-promo-box-icon-inner">
					<?php
					if ( $is_new || $migrated ) {
						Icons_Manager::render_icon( $settings['selected_icon'], array( 'aria-hidden' => 'true' ) );
					} elseif ( ! empty( $settings['icon'] ) ) {
						?>
						<i <?php echo $this->get_render_attribute_string( 'i' ); ?>></i>
						<?php
					}
					?>
					 </span>
				<?php } elseif ( $settings['icon_type'] == 'image' ) { ?>
					<?php if ( ! empty( $settings['icon_image']['url'] ) ) { ?>
					<span class="pp-promo-box-icon-inner">
						<?php echo Group_Control_Image_Size::get_attachment_image_html( $settings, 'image', 'icon_image' ); ?>
					</span>
					<?php } ?>
				<?php } ?>
			</span>
		</div>
		<?php
	}

	/**
	 * Render promo box widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		$this->add_render_attribute( 'promo-box', 'class', 'pp-promo-box' );

		$this->add_inline_editing_attributes( 'heading', 'none' );
		$this->add_render_attribute( 'heading', 'class', 'pp-promo-box-title' );

		$this->add_inline_editing_attributes( 'sub_heading', 'none' );
		$this->add_render_attribute( 'sub_heading', 'class', 'pp-promo-box-subtitle' );

		$this->add_inline_editing_attributes( 'content', 'none' );
		$this->add_render_attribute( 'content', 'class', 'pp-promo-box-content' );

		$this->add_inline_editing_attributes( 'button_text', 'none' );

		$this->add_render_attribute(
			'button_text',
			'class',
			array(
				'pp-promo-box-button',
				'elementor-button',
				'elementor-size-' . $settings['button_size'],
			)
		);

		if ( ! empty( $settings['link']['url'] ) ) {

			$this->add_link_attributes( 'button_text', $settings['link'] );

		}

		if ( $settings['button_hover_animation'] ) {
			$this->add_render_attribute( 'button_text', 'class', 'elementor-animation-' . $settings['button_hover_animation'] );
		}
		?>
		<div <?php echo $this->get_render_attribute_string( 'promo-box' ); ?>>
			<div class="pp-promo-box-bg"></div>
			<?php if ( $settings['overlay_switch'] == 'yes' ) { ?>
				<div class="pp-promo-box-overlay"></div>
			<?php } ?>
			<div class="pp-promo-box-wrap">
				<div class="pp-promo-box-inner">
					<div class="pp-promo-box-inner-content">
						<?php
						if ( $settings['icon_switch'] == 'yes' ) {
							if ( $settings['icon_position'] == 'above-title' ) {
								$this->render_promobox_icon();
							}
						}
						?>
						
						<?php if ( ! empty( $settings['heading'] ) ) { ?>
							<<?php echo $settings['heading_html_tag']; ?> <?php echo $this->get_render_attribute_string( 'heading' ); ?>>
								<?php echo $this->parse_text_editor( $settings['heading'] ); ?>
							</<?php echo $settings['heading_html_tag']; ?>>
						<?php } ?>

						<?php if ( $settings['divider_heading_switch'] == 'yes' ) { ?>
							<div class="pp-promo-box-heading-divider-wrap">
								<div class="pp-promo-box-heading-divider">
									<?php if ( $settings['divider_heading_type'] == 'image' && $settings['divider_title_image']['url'] != '' ) { ?>
										<img src="<?php echo esc_url( $settings['divider_title_image']['url'] ); ?>">
									<?php } ?>
								</div>
							</div>
						<?php } ?>

						<?php
						if ( $settings['icon_switch'] == 'yes' ) {
							if ( $settings['icon_position'] == 'below-title' ) {
								$this->render_promobox_icon();
							}
						}
						?>
						
						<?php if ( ! empty( $settings['sub_heading'] ) ) { ?>
							<<?php echo $settings['sub_heading_html_tag']; ?> <?php echo $this->get_render_attribute_string( 'sub_heading' ); ?>>
								<?php echo $settings['sub_heading']; ?>
							</<?php echo $settings['sub_heading_html_tag']; ?>>
						<?php } ?>

						<?php if ( $settings['divider_subheading_switch'] == 'yes' ) { ?>
							<div class="pp-promo-box-subheading-divider-wrap">
								<div class="pp-promo-box-subheading-divider">
									<?php if ( $settings['divider_subheading_type'] == 'image' && $settings['divider_subheading_image']['url'] != '' ) { ?>
										<img src="<?php echo esc_url( $settings['divider_subheading_image']['url'] ); ?>">
									<?php } ?>
								</div>
							</div>
						<?php } ?>

						<?php if ( ! empty( $settings['content'] ) ) { ?>
							<div <?php echo $this->get_render_attribute_string( 'content' ); ?>>
								<?php echo $this->parse_text_editor( $settings['content'] ); ?>
							</div>
						<?php } ?>
						<?php if ( $settings['button_switch'] == 'yes' ) { ?>
							<?php if ( ! empty( $settings['button_text'] ) ) { ?>
								<div class="pp-promo-box-footer">
									<a <?php echo $this->get_render_attribute_string( 'button_text' ); ?>>
										<?php echo esc_attr( $settings['button_text'] ); ?>
									</a>
								</div>
							<?php } ?>
						<?php } ?>
					</div><!-- .pp-promo-box-inner-content -->
				</div><!-- .pp-promo-box-inner -->
			</div><!-- .pp-promo-box-wrap -->
		</div>
		<?php
	}

	/**
	 * Render promo box widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @access protected
	 */
	protected function _content_template() {
		?>
		<#
		   function icon_template() {
				   var iconHTML = elementor.helpers.renderIcon( view, settings.selected_icon, { 'aria-hidden': true }, 'i' , 'object' ),
					migrated = elementor.helpers.isIconMigrated( settings, 'selected_icon' );
				#>
				<div class="pp-promo-box-icon-wrap">
					<span class="pp-promo-box-icon pp-icon elementor-animation-{{ settings.hover_animation_icon }}">
						<# if ( settings.icon_type == 'icon' ) { #>
							<span class="pp-promo-box-icon-inner">
								<# if ( settings.icon || settings.selected_icon ) { #>
								<# if ( iconHTML && iconHTML.rendered && ( ! settings.icon || migrated ) ) { #>
								{{{ iconHTML.value }}}
								<# } else { #>
									<i class="{{ settings.icon }}" aria-hidden="true"></i>
								<# } #>
								<# } #>
							</span>
						<# } else if ( settings.icon_type == 'image' ) { #>
							<span class="pp-promo-box-icon-inner">
								<#
								var image = {
									id: settings.icon_image.id,
									url: settings.icon_image.url,
									size: settings.image_size,
									dimension: settings.image_custom_dimension,
									model: view.getEditModel()
								};
								var image_url = elementor.imagesManager.getImageUrl( image );
								#>
								<img src="{{{ image_url }}}" />
							</span>
						<# } #>
					</span>
				</div>
				<#
		   }
		#>
		<div class="pp-promo-box">
			<div class="pp-promo-box-bg"></div>
			<# if ( settings.overlay_switch == 'yes' ) { #>
				<div class="pp-promo-box-overlay"></div>
			<# } #>
			<div class="pp-promo-box-wrap">
				<div class="pp-promo-box-inner">
					<div class="pp-promo-box-inner-content">
						<# if ( settings.icon_switch == 'yes' ) { #>
							<# if ( settings.icon_position == 'above-title' ) { #>
								<# icon_template(); #>
							<# } #>
						<# }
						   
						if ( settings.heading != '' ) {
							var heading = settings.heading;

							view.addRenderAttribute( 'heading', 'class', 'pp-promo-box-title' );

							view.addInlineEditingAttributes( 'heading' );

						   var heading_html = '<' + settings.heading_html_tag + ' ' + view.getRenderAttributeString( 'heading' ) + '>' + heading + '</' + settings.heading_html_tag + '>';

							print( heading_html );
						}
						   
						if ( settings.divider_heading_switch == 'yes' ) { #>
							<div class="pp-promo-box-heading-divider-wrap">
								<div class="pp-promo-box-heading-divider">
									<# if ( settings.divider_heading_type == 'image' ) { #>
										<# if ( settings.divider_title_image.url != '' ) { #>
											<img src="{{ settings.divider_title_image.url }}">
										<# } #>
									<# } #>
								</div>
							</div>
						<# }
						   
						if ( settings.icon_switch == 'yes' ) {
							if ( settings.icon_position == 'below-title' ) {
								icon_template();
							}
						}
						   
						if ( settings.sub_heading != '' ) {
							var sub_heading = settings.sub_heading;

							view.addRenderAttribute( 'sub_heading', 'class', 'pp-promo-box-subtitle' );

							view.addInlineEditingAttributes( 'sub_heading' );

							var sub_heading_html = '<' + settings.sub_heading_html_tag + ' ' + view.getRenderAttributeString( 'sub_heading' ) + '>' + sub_heading + '</' + settings.sub_heading_html_tag + '>';

							print( sub_heading_html );
						} #>

						<# if ( settings.divider_subheading_switch == 'yes' ) { #>
							<div class="pp-promo-box-subheading-divider-wrap">
								<div class="pp-promo-box-subheading-divider">
									<# if ( settings.divider_subheading_type == 'image' ) { #>
										<# if ( settings.divider_subheading_image.url != '' ) { #>
											<img src="{{ settings.divider_subheading_image.url }}">
										<# } #>
									<# } #>
								</div>
							</div>
						<# }
						   
						if ( settings.content != '' ) {
							var content = settings.content;

							view.addRenderAttribute( 'content', 'class', 'pp-promo-box-content' );

							view.addInlineEditingAttributes( 'content' );

							var content_html = '<div' + ' ' + view.getRenderAttributeString( 'content' ) + '>' + content + '</div>';

							print( content_html );
						}
						   
						if ( settings.button_switch == 'yes' ) { #>
							<# if ( settings.button_text != '' ) { #>
								<div class="pp-promo-box-footer">
									<#
										var button_text = settings.button_text;

										view.addRenderAttribute( 'button_text', 'class', [ 'pp-promo-box-button', 'elementor-button', 'elementor-size-' + settings.button_size, 'elementor-animation-' + settings.button_hover_animation ] );

										view.addInlineEditingAttributes( 'button_text' );

										var button_html = '<a href="' + settings.link.url + '"' + ' ' + view.getRenderAttributeString( 'button_text' ) + '>' + button_text + '</a>';

										print( button_html );
									#>
								</div>
							<# } #>
						<# } #>
					</div><!-- .pp-promo-box-inner-content -->
				</div><!-- .pp-promo-box-inner -->
			</div><!-- .pp-promo-box-wrap -->
		</div>
		<?php
	}
}
