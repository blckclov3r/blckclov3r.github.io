<?php
/**
 * Info box widget class
 *
 * @package Happy_Addons
 */
namespace Happy_Addons\Elementor\Widget;

use Elementor\Scheme_Typography;
use Elementor\Utils;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Happy_Addons\Elementor\Traits\Button_Renderer;

defined( 'ABSPATH' ) || die();

class InfoBox extends Base {

	use Button_Renderer;

	/**
	 * Get widget title.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Info Box', 'happy-elementor-addons' );
	}

	public function get_custom_help_url() {
		return 'https://happyaddons.com/docs/happy-addons-for-elementor/widgets/info-box/';
	}

	/**
	 * Get widget icon.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'hm hm-blog-content';
	}

	public function get_keywords() {
		return [ 'info', 'blurb', 'box', 'text', 'content' ];
	}

	/**
	 * Register content related controls
	 */
	protected function register_content_controls() {
		$this->start_controls_section(
			'_section_media',
			[
				'label' => __( 'Icon / Image', 'happy-elementor-addons' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'type',
			[
				'label' => __( 'Media Type', 'happy-elementor-addons' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'options' => [
					'icon' => [
						'title' => __( 'Icon', 'happy-elementor-addons' ),
						'icon' => 'fa fa-smile-o',
					],
					'image' => [
						'title' => __( 'Image', 'happy-elementor-addons' ),
						'icon' => 'fa fa-image',
					],
				],
				'default' => 'icon',
				'toggle' => false,
				'style_transfer' => true,
			]
		);

		$this->add_control(
			'image',
			[
				'label' => __( 'Image', 'happy-elementor-addons' ),
				'type' => Controls_Manager::MEDIA,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
				'condition' => [
					'type' => 'image'
				],
				'dynamic' => [
					'active' => true,
				]
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name' => 'thumbnail',
				'default' => 'medium_large',
				'separator' => 'none',
				'exclude' => [
					'full',
					'custom',
					'large',
					'shop_catalog',
					'shop_single',
					'shop_thumbnail'
				],
				'condition' => [
					'type' => 'image'
				]
			]
		);

		if ( ha_is_elementor_version( '<', '2.6.0' ) ) {
			$this->add_control(
				'icon',
				[
					'label' => __( 'Icon', 'happy-elementor-addons' ),
					'label_block' => true,
					'type' => Controls_Manager::ICON,
					'options' => ha_get_happy_icons(),
					'default' => 'fa fa-smile-o',
					'condition' => [
						'type' => 'icon'
					]
				]
			);
		} else {
			$this->add_control(
				'selected_icon',
				[
					'type' => Controls_Manager::ICONS,
					'fa4compatibility' => 'icon',
					'label_block' => true,
					'default' => [
						'value' => 'fas fa-smile-wink',
						'library' => 'fa-solid',
					],
					'condition' => [
						'type' => 'icon'
					]
				]
			);
		}

		$this->end_controls_section();

		$this->start_controls_section(
			'_section_title',
			[
				'label' => __( 'Title & Description', 'happy-elementor-addons' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'title',
			[
				'label' => __( 'Title', 'happy-elementor-addons' ),
				'label_block' => true,
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Happy Info Box Title', 'happy-elementor-addons' ),
				'placeholder' => __( 'Type Info Box Title', 'happy-elementor-addons' ),
				'dynamic' => [
					'active' => true,
				]
			]
		);

		$this->add_control(
			'description',
			[
				'label' => __( 'Description', 'happy-elementor-addons' ),
				'description' => ha_get_allowed_html_desc( 'intermediate' ),
				'type' => Controls_Manager::TEXTAREA,
				'default' => __( 'Happy info box description goes here', 'happy-elementor-addons' ),
				'placeholder' => __( 'Type info box description', 'happy-elementor-addons' ),
				'rows' => 5,
				'dynamic' => [
					'active' => true,
				]
			]
		);

		$this->add_control(
			'title_tag',
			[
				'label' => __( 'Title HTML Tag', 'happy-elementor-addons' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'h1'  => [
						'title' => __( 'H1', 'happy-elementor-addons' ),
						'icon' => 'eicon-editor-h1'
					],
					'h2'  => [
						'title' => __( 'H2', 'happy-elementor-addons' ),
						'icon' => 'eicon-editor-h2'
					],
					'h3'  => [
						'title' => __( 'H3', 'happy-elementor-addons' ),
						'icon' => 'eicon-editor-h3'
					],
					'h4'  => [
						'title' => __( 'H4', 'happy-elementor-addons' ),
						'icon' => 'eicon-editor-h4'
					],
					'h5'  => [
						'title' => __( 'H5', 'happy-elementor-addons' ),
						'icon' => 'eicon-editor-h5'
					],
					'h6'  => [
						'title' => __( 'H6', 'happy-elementor-addons' ),
						'icon' => 'eicon-editor-h6'
					]
				],
				'default' => 'h2',
				'toggle' => false,
			]
		);

		$this->add_responsive_control(
			'align',
			[
				'label' => __( 'Alignment', 'happy-elementor-addons' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __( 'Left', 'happy-elementor-addons' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'happy-elementor-addons' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'happy-elementor-addons' ),
						'icon' => 'eicon-text-align-right',
					],
					'justify' => [
						'title' => __( 'Justify', 'happy-elementor-addons' ),
						'icon' => 'eicon-text-align-justify',
					],
				],
				'toggle' => true,
				'selectors' => [
					'{{WRAPPER}}' => 'text-align: {{VALUE}};'
				]
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'_section_button',
			[
				'label' => __( 'Button', 'happy-elementor-addons' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'button_text',
			[
				'label' => __( 'Text', 'happy-elementor-addons' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Button Text', 'happy-elementor-addons' ),
				'placeholder' => __( 'Type button text here', 'happy-elementor-addons' ),
				'label_block' => true,
				'dynamic' => [
					'active' => true,
				]
			]
		);

		$this->add_control(
			'button_link',
			[
				'label' => __( 'Link', 'happy-elementor-addons' ),
				'type' => Controls_Manager::URL,
				'placeholder' => 'https://example.com',
				'dynamic' => [
					'active' => true,
				],
				'default' => [
					'url' => '#',
				]
			]
		);

		if ( ha_is_elementor_version( '<', '2.6.0' ) ) {
			$this->add_control(
				'button_icon',
				[
					'label' => __( 'Icon', 'happy-elementor-addons' ),
					'label_block' => true,
					'type' => Controls_Manager::ICON,
					'options' => ha_get_happy_icons(),
					'default' => 'fa fa-angle-right',
				]
			);

			$condition = ['button_icon!' => ''];
		} else {
			$this->add_control(
				'button_selected_icon',
				[
					'type' => Controls_Manager::ICONS,
					'fa4compatibility' => 'button_icon',
					'label_block' => true,
				]
			);
			$condition = ['button_selected_icon[value]!' => ''];
		}

		$this->add_control(
			'button_icon_position',
			[
				'label' => __( 'Icon Position', 'happy-elementor-addons' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'options' => [
					'before' => [
						'title' => __( 'Before', 'happy-elementor-addons' ),
						'icon' => 'eicon-h-align-left',
					],
					'after' => [
						'title' => __( 'After', 'happy-elementor-addons' ),
						'icon' => 'eicon-h-align-right',
					],
				],
				'default' => 'after',
				'toggle' => false,
				'condition' => $condition,
				'style_transfer' => true,
			]
		);

		$this->add_control(
			'button_icon_spacing',
			[
				'label' => __( 'Icon Spacing', 'happy-elementor-addons' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 10
				],
				'condition' => $condition,
				'selectors' => [
					'{{WRAPPER}} .ha-btn--icon-before .ha-btn-icon' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .ha-btn--icon-after .ha-btn-icon' => 'margin-left: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Register styles related controls
	 */
	protected function register_style_controls() {
		$this->start_controls_section(
			'_section_media_style',
			[
				'label' => __( 'Icon / Image', 'happy-elementor-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'icon_size',
			[
				'label' => __( 'Size', 'happy-elementor-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 6,
						'max' => 300,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .ha-infobox-figure--icon' => 'font-size: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					 'type' => 'icon'
				]
			]
		);

		$this->add_responsive_control(
			'image_width',
			[
				'label' => __( 'Width', 'happy-elementor-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 400,
					],
					'%' => [
						'min' => 1,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .ha-infobox-figure--image' => 'width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'type' => 'image'
				]
			]
		);

		$this->add_responsive_control(
			'image_height',
			[
				'label' => __( 'Height', 'happy-elementor-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 400,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .ha-infobox-figure--image' => 'height: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'type' => 'image'
				]
			]
		);

		$this->add_control(
			'offset_toggle',
			[
				'label' => __( 'Offset', 'happy-elementor-addons' ),
				'type' => Controls_Manager::POPOVER_TOGGLE,
				'label_off' => __( 'None', 'happy-elementor-addons' ),
				'label_on' => __( 'Custom', 'happy-elementor-addons' ),
				'return_value' => 'yes',
			]
		);

		$this->start_popover();

		$this->add_responsive_control(
			'media_offset_x',
			[
				'label' => __( 'Offset Left', 'happy-elementor-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'condition' => [
					'offset_toggle' => 'yes'
				],
				'range' => [
					'px' => [
						'min' => -1000,
						'max' => 1000,
					],
				],
				'selectors' => [
					'{{WRAPPER}}' => '--ha-infobox-media-offset-x: {{SIZE}}{{UNIT}};',
				]
			]
		);

		$this->add_responsive_control(
			'media_offset_y',
			[
				'label' => __( 'Offset Top', 'happy-elementor-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'condition' => [
					'offset_toggle' => 'yes'
				],
				'range' => [
					'px' => [
						'min' => -1000,
						'max' => 1000,
					],
				],
				'selectors' => [
					'{{WRAPPER}}' => '--ha-infobox-media-offset-y: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->end_popover();

		$this->add_responsive_control(
			'media_spacing',
			[
				'label' => __( 'Bottom Spacing', 'happy-elementor-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'selectors' => [
					'{{WRAPPER}} .ha-infobox-figure' => 'margin-bottom: {{SIZE}}{{UNIT}} !important;',
				],
			]
		);

		$this->add_responsive_control(
			'media_padding',
			[
				'label' => __( 'Padding', 'happy-elementor-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'selectors' => [
					'{{WRAPPER}} .ha-infobox-figure--image img, {{WRAPPER}} .ha-infobox-figure--icon' => 'padding: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'media_border',
				'selector' => '{{WRAPPER}} .ha-infobox-figure--image img, {{WRAPPER}} .ha-infobox-figure--icon',
			]
		);

		$this->add_responsive_control(
			'media_border_radius',
			[
				'label' => __( 'Border Radius', 'happy-elementor-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .ha-infobox-figure--image img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .ha-infobox-figure--icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'media_box_shadow',
				'exclude' => [
					'box_shadow_position',
				],
				'selector' => '{{WRAPPER}} .ha-infobox-figure--image img, {{WRAPPER}} .ha-infobox-figure--icon'
			]
		);

		$this->add_control(
			'icon_color',
			[
				'label' => __( 'Color', 'happy-elementor-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ha-infobox-figure--icon' => 'color: {{VALUE}}',
				],
				'condition' => [
					'type' => 'icon'
				]
			]
		);

		$this->add_control(
			'icon_bg_color',
			[
				'label' => __( 'Background Color', 'happy-elementor-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ha-infobox-figure--icon' => 'background-color: {{VALUE}};',
				],
				'condition' => [
					'type' => 'icon'
				]
			]
		);

		$this->add_control(
			'icon_bg_rotate',
			[
				'label' => __( 'Background Rotate', 'happy-elementor-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'deg' ],
				'default' => [
					'unit' => 'deg',
				],
				'range' => [
					'deg' => [
						'min' => 0,
						'max' => 360,
					],
				],
				'selectors' => [
					'{{WRAPPER}}' => '--ha-infobox-media-rotate: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'type' => 'icon'
				]
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'_section_title_style',
			[
				'label' => __( 'Title & Description', 'happy-elementor-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'content_padding',
			[
				'label' => __( 'Content Box Padding', 'happy-elementor-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .ha-infobox-body' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'title_heading',
			[
				'type' => Controls_Manager::HEADING,
				'label' => __( 'Title', 'happy-elementor-addons' ),
				'separator' => 'before'
			]
		);

		$this->add_responsive_control(
			'title_spacing',
			[
				'label' => __( 'Bottom Spacing', 'happy-elementor-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'selectors' => [
					'{{WRAPPER}} .ha-infobox-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'title_color',
			[
				'label' => __( 'Text Color', 'happy-elementor-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ha-infobox-title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography',
				'label' => __( 'Typography', 'happy-elementor-addons' ),
				'selector' => '{{WRAPPER}} .ha-infobox-title',
				'scheme' => Scheme_Typography::TYPOGRAPHY_2
			]
		);

		$this->add_control(
			'description_heading',
			[
				'type' => Controls_Manager::HEADING,
				'label' => __( 'Description', 'happy-elementor-addons' ),
				'separator' => 'before'
			]
		);

		$this->add_responsive_control(
			'description_spacing',
			[
				'label' => __( 'Bottom Spacing', 'happy-elementor-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'selectors' => [
					'{{WRAPPER}} .ha-infobox-text' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'description_color',
			[
				'label' => __( 'Text Color', 'happy-elementor-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ha-infobox-text' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'description_typography',
				'label' => __( 'Typography', 'happy-elementor-addons' ),
				'selector' => '{{WRAPPER}} .ha-infobox-text',
				'scheme' => Scheme_Typography::TYPOGRAPHY_3,
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'_section_style_button',
			[
				'label' => __( 'Button', 'happy-elementor-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'link_padding',
			[
				'label' => __( 'Padding', 'happy-elementor-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .ha-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'btn_typography',
				'selector' => '{{WRAPPER}} .ha-btn',
				'scheme' => Scheme_Typography::TYPOGRAPHY_4,
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'button_border',
				'selector' => '{{WRAPPER}} .ha-btn',
			]
		);

		$this->add_control(
			'button_border_radius',
			[
				'label' => __( 'Border Radius', 'happy-elementor-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .ha-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'button_box_shadow',
				'selector' => '{{WRAPPER}} .ha-btn',
			]
		);

		$this->add_control(
			'hr',
			[
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thick',
			]
		);

		$this->start_controls_tabs( '_tabs_button' );

		$this->start_controls_tab(
			'_tab_button_normal',
			[
				'label' => __( 'Normal', 'happy-elementor-addons' ),
			]
		);

		$this->add_control(
			'link_color',
			[
				'label' => __( 'Text Color', 'happy-elementor-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .ha-btn' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_bg_color',
			[
				'label' => __( 'Background Color', 'happy-elementor-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ha-btn' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_icon_translate',
			[
				'label' => __( 'Icon Translate X', 'happy-elementor-addons' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => -100,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .ha-btn--icon-before .ha-btn-icon' => '-webkit-transform: translateX(calc(-1 * {{SIZE}}{{UNIT}})); transform: translateX(calc(-1 * {{SIZE}}{{UNIT}}));',
					'{{WRAPPER}} .ha-btn--icon-after .ha-btn-icon' => '-webkit-transform: translateX({{SIZE}}{{UNIT}}); transform: translateX({{SIZE}}{{UNIT}});',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'_tab_button_hover',
			[
				'label' => __( 'Hover', 'happy-elementor-addons' ),
			]
		);

		$this->add_control(
			'link_hover_color',
			[
				'label' => __( 'Text Color', 'happy-elementor-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ha-btn:hover, {{WRAPPER}} .ha-btn:focus' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_hover_bg_color',
			[
				'label' => __( 'Background Color', 'happy-elementor-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ha-btn:hover, {{WRAPPER}} .ha-btn:focus' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_hover_border_color',
			[
				'label' => __( 'Border Color', 'happy-elementor-addons' ),
				'type' => Controls_Manager::COLOR,
				'condition' => [
					'button_border_border!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .ha-btn:hover, {{WRAPPER}} .ha-btn:focus' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_hover_icon_translate',
			[
				'label' => __( 'Icon Translate X', 'happy-elementor-addons' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 10
				],
				'range' => [
					'px' => [
						'min' => -100,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .ha-btn.ha-btn--icon-before:hover .ha-btn-icon' => '-webkit-transform: translateX(calc(-1 * {{SIZE}}{{UNIT}})); transform: translateX(calc(-1 * {{SIZE}}{{UNIT}}));',
					'{{WRAPPER}} .ha-btn.ha-btn--icon-after:hover .ha-btn-icon' => '-webkit-transform: translateX({{SIZE}}{{UNIT}}); transform: translateX({{SIZE}}{{UNIT}});',
				],
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		$this->add_inline_editing_attributes( 'title', 'basic' );
		$this->add_render_attribute( 'title', 'class', 'ha-infobox-title' );

		$this->add_inline_editing_attributes( 'description', 'intermediate' );
		$this->add_render_attribute( 'description', 'class', 'ha-infobox-text' );
		?>

		<?php if ( $settings['type'] === 'image' && ( $settings['image']['url'] || $settings['image']['id'] ) ) :
			$settings['hover_animation'] = 'disable-animation'; // hack to prevent image hover animation
			?>
			<figure class="ha-infobox-figure ha-infobox-figure--image">
				<?php echo Group_Control_Image_Size::get_attachment_image_html( $settings, 'thumbnail', 'image' ); ?>
			</figure>
		<?php elseif ( ! empty( $settings['icon'] ) || ! empty( $settings['selected_icon']['value'] ) ) : ?>
			<figure class="ha-infobox-figure ha-infobox-figure--icon">
				<?php ha_render_icon( $settings, 'icon', 'selected_icon' ); ?>
			</figure>
		<?php endif; ?>

		<div class="ha-infobox-body">
			<?php
			if ( $settings['title' ] ) :
				printf( '<%1$s %2$s>%3$s</%1$s>',
					tag_escape( $settings['title_tag'] ),
					$this->get_render_attribute_string( 'title' ),
					ha_kses_basic( $settings['title' ] )
				);
			endif;
			?>

			<?php if ( $settings['description'] ) : ?>
				<div <?php $this->print_render_attribute_string( 'description' ); ?>>
					<p><?php echo ha_kses_intermediate( $settings['description'] ); ?></p>
				</div>
			<?php endif; ?>

			<?php $this->render_icon_button(); ?>
		</div>
		<?php
	}

	public function _content_template() {
		?>
		<#
		var iconHTML, migrated;

		if ( ha.hasIconLibrary() ) {
			iconHTML = elementor.helpers.renderIcon( view, settings.selected_icon, { 'aria-hidden': true }, 'i' , 'object' ),
			migrated = elementor.helpers.isIconMigrated( settings, 'selected_icon' );
		}

		view.addInlineEditingAttributes( 'title', 'basic' );
		view.addRenderAttribute( 'title', 'class', 'ha-infobox-title' );

		view.addInlineEditingAttributes( 'description', 'intermediate' );
		view.addRenderAttribute( 'description', 'class', 'ha-infobox-text' );

		if ( settings.type === 'image' ) {
			if ( settings.image.url ) {
				var image = {
					id: settings.image.id,
					url: settings.image.url,
					size: settings.thumbnail_size,
					model: view.getEditModel()
				};

				var image_url = elementor.imagesManager.getImageUrl( image );
				#>
				<figure class="ha-infobox-figure ha-infobox-figure--image">
					<img src="{{ image_url }}">
				</figure>
			<# }
		} else if ( settings.icon || settings.selected_icon.value ) { #>
			<figure class="ha-infobox-figure ha-infobox-figure--icon">
				<# if ( ha.hasIconLibrary() && iconHTML && iconHTML.rendered && ( ! settings.icon || migrated ) ) { #>
					{{{ iconHTML.value }}}
				<# } else { #>
					<i class="{{ settings.icon }}" aria-hidden="true"></i>
				<# } #>
			</figure>
		<# } #>

		<div class="ha-infobox-body">
			<# if (settings.title) { #>
				<{{ settings.title_tag }} {{{ view.getRenderAttributeString( 'title' ) }}}>{{ settings.title }}</{{ settings.title_tag }}>
			<# } #>

			<# if (settings.description) { #>
				<div {{{ view.getRenderAttributeString( 'description' ) }}}>
					<p>{{{ settings.description }}}</p>
				</div>
			<# } #>

			<# print( ha.getButtonWithIcon(view) ); #>
		</div>
		<?php
	}
}
