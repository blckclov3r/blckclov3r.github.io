<?php

/**
 * Premium Dual Heading.
 */
namespace PremiumAddons\Widgets;

// Elementor Classes.
use PremiumAddons\Includes;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Scheme_Color;
use Elementor\Scheme_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Background;

// PremiumAddons Classes.
use PremiumAddons\Helper_Functions;

if ( ! defined( 'ABSPATH' ) ) exit; // If this file is called directly, abort.

/**
 * Class Premium_Dual_Header
 */
class Premium_Dual_Header extends Widget_Base {
    
    protected $templateInstance;

    public function getTemplateInstance(){
        return $this->templateInstance = Includes\premium_Template_Tags::getInstance();
    }
    
    public function get_name() {
        return 'premium-addon-dual-header';
    }

    public function get_title() {
        return sprintf( '%1$s %2$s', Helper_Functions::get_prefix(), __('Dual Heading', 'premium-addons-for-elementor') );
	}

    public function get_style_depends() {
        return [
            'premium-addons'
        ];
    }
    
    public function get_icon() {
        return 'pa-dual-header';
    }

    public function get_categories() {
        return [ 'premium-elements' ];
    }
    
    public function get_custom_help_url() {
		return 'https://premiumaddons.com/support/';
	}

    /**
	 * Register Dual Heading controls.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
    protected function _register_controls() {

        /*Start General Section*/
        $this->start_controls_section('premium_dual_header_general_settings',
            [
                'label'         => __('Dual Heading', 'premium-addons-for-elementor')
            ]
        );
        
        /*First Header*/
        $this->add_control('premium_dual_header_first_header_text',
            [
                'label'         => __('First Heading', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::TEXT,
                'dynamic'       => [ 'active' => true ],
                'default'       => __('Premium', 'premium-addons-for-elementor'),
                'label_block'   => true,
            ]
        );

        /*Second Header*/
        $this->add_control('premium_dual_header_second_header_text',
            [
                'label'         => __('Second Heading', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::TEXT,
                'dynamic'       => [ 'active' => true ],
                'default'       => __('Addons', 'premium-addons-for-elementor'),
                'label_block'   => true,
            ]
        );
        
         /*Title Tag*/
        $this->add_control('premium_dual_header_first_header_tag',
            [
                'label'         => __('HTML Tag', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SELECT,
                'default'       => 'h2',
                'options'       => [
                    'h1'    => 'H1',
                    'h2'    => 'H2',
                    'h3'    => 'H3',
                    'h4'    => 'H4',
                    'h5'    => 'H5',
                    'h6'    => 'H6',
                    'p'     => 'p',
                    'span'  => 'span',
                ],
                'label_block'   =>  true,
            ]
        );
        
        /*Text Align*/
        $this->add_control('premium_dual_header_position',
            [
                'label'         => __( 'Display', 'premium-addons-for-elementor' ),
                'type'          => Controls_Manager::SELECT,
                'options'       => [
                    'inline'=> __('Inline', 'premium-addons-for-elementor'),
                    'block' => __('Block', 'premium-addons-for-elementor'),
                ],
                'default'       => 'inline',
                'selectors'     => [
                    '{{WRAPPER}} .premium-dual-header-first-container span, {{WRAPPER}} .premium-dual-header-second-container' => 'display: {{VALUE}};',
                ],
                'label_block'   => true
            ]
        );
        
        $this->add_control('premium_dual_header_link_switcher',
            [
                'label'         => __('Link', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SWITCHER,
                'description'   => __('Enable or disable link','premium-addons-for-elementor'),
            ]
        );
        
        $this->add_control('premium_dual_heading_link_selection', 
            [
                'label'         => __('Link Type', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SELECT,
                'options'       => [
                    'url'   => __('URL', 'premium-addons-for-elementor'),
                    'link'  => __('Existing Page', 'premium-addons-for-elementor'),
                ],
                'default'       => 'url',
                'label_block'   => true,
                'condition'     => [
                    'premium_dual_header_link_switcher'     => 'yes',
                ]
            ]
        );
        
        $this->add_control('premium_dual_heading_link',
            [
                'label'         => __('Link', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::URL,
                'dynamic'       => [ 'active' => true ],
                'default'       => [
                    'url'   => '#',
                ],
                'placeholder'   => 'https://premiumaddons.com/',
                'label_block'   => true,
                'separator'     => 'after',
                'condition'     => [
                    'premium_dual_header_link_switcher'     => 'yes',
                    'premium_dual_heading_link_selection'   => 'url'
                ]
            ]
        );
        
        $this->add_control('premium_dual_heading_existing_link',
            [
                'label'         => __('Existing Page', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SELECT2,
                'options'       => $this->getTemplateInstance()->get_all_post(),
                'condition'     => [
                    'premium_dual_header_link_switcher'         => 'yes',
                    'premium_dual_heading_link_selection'       => 'link',
                ],
                'multiple'      => false,
                'separator'     => 'after',
                'label_block'   => true,
            ]
        );
        
        /*Text Align*/
        $this->add_responsive_control('premium_dual_header_text_align',
            [
                'label'         => __( 'Alignment', 'premium-addons-for-elementor' ),
                'type'          => Controls_Manager::CHOOSE,
                'options'       => [
                    'left'      => [
                        'title'=> __( 'Left', 'premium-addons-for-elementor' ),
                        'icon' => 'fa fa-align-left',
                    ],
                    'center'    => [
                        'title'=> __( 'Center', 'premium-addons-for-elementor' ),
                        'icon' => 'fa fa-align-center',
                    ],
                    'right'     => [
                        'title'=> __( 'Right', 'premium-addons-for-elementor' ),
                        'icon' => 'fa fa-align-right'
                    ]
                ],
                'default'       => 'center',
                'selectors'     => [
                    '{{WRAPPER}} .premium-dual-header-container' => 'text-align: {{VALUE}};'
                ],
            ]
        );

        $this->add_responsive_control('rotate',
            [
                'label'         => __('Degrees', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::NUMBER,
                'min'           => -180,
                'max'           => 180,
                'selectors'     => [
                    '{{WRAPPER}} .premium-dual-header-container' => 'transform: rotate({{VALUE}}deg);'
                ],
            ]
        );

        $this->add_responsive_control('transform_origin_x',
            [
                'label' => __( 'X Anchor Point', 'premium-addons-for-elementor' ),
                'type' => Controls_Manager::CHOOSE,
                'default' => 'center',
                'options' => [
                    'left' => [
                        'title' => __( 'Left', 'premium-addons-for-elementor' ),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'center' => [
                        'title' => __( 'Center', 'premium-addons-for-elementor' ),
                        'icon' => 'eicon-h-align-center',
                    ],
                    'right' => [
                        'title' => __( 'Right', 'premium-addons-for-elementor' ),
                        'icon' => 'eicon-h-align-right',
                    ],
                ],
                'label_block' => false,
                'toggle' => false,
                'render_type' => 'ui',
                'condition' => [
                    'rotate!'    => ''
                ]
            ]
        );

        $this->add_responsive_control('transform_origin_y',
            [
                'label' => __( 'Y Anchor Point', 'premium-addons-for-elementor' ),
                'type' => Controls_Manager::CHOOSE,
                'default' => 'center',
                'options' => [
                    'top' => [
                        'title' => __( 'Top', 'premium-addons-for-elementor' ),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'center' => [
                        'title' => __( 'Center', 'premium-addons-for-elementor' ),
                        'icon' => 'eicon-v-align-middle',
                    ],
                    'bottom' => [
                        'title' => __( 'Bottom', 'premium-addons-for-elementor' ),
                        'icon' => 'eicon-v-align-bottom',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .premium-dual-header-container' => 'transform-origin: {{transform_origin_x.VALUE}} {{VALUE}}',
                ],
                'label_block' => false,
                'toggle' => false,
                'condition' => [
                    'rotate!'    => ''
                ]
            ]
		);

        /*End General Settings Section*/
        $this->end_controls_section();

        $this->start_controls_section('section_pa_docs',
            [
                'label'         => __('Helpful Documentations', 'premium-addons-for-elementor'),
            ]
        );

        $this->add_control('doc_1',
            [
                'type'            => Controls_Manager::RAW_HTML,
                'raw'             => sprintf( __( '%1$s Getting started » %2$s', 'premium-addons-for-elementor' ), '<a href="https://premiumaddons.com/docs/dual-heading-widget-tutorial/?utm_source=pa-dashboard&utm_medium=pa-editor&utm_campaign=pa-plugin" target="_blank" rel="noopener">', '</a>' ),
                'content_classes' => 'editor-pa-doc',
            ]
        );

        $this->add_control('doc_2',
            [
                'type'            => Controls_Manager::RAW_HTML,
                'raw'             => sprintf( __( '%1$s How to add an outlined heading using Dual Heading widget » %2$s', 'premium-addons-for-elementor' ), '<a href="https://premiumaddons.com/docs/how-to-add-an-outlined-heading-to-my-website/?utm_source=pa-dashboard&utm_medium=pa-editor&utm_campaign=pa-plugin" target="_blank" rel="noopener">', '</a>' ),
                'content_classes' => 'editor-pa-doc',
            ]
        );
        
        $this->end_controls_section();
        
        /*Start First Header Styling Section*/
        $this->start_controls_section('premium_dual_header_first_style',
            [
                'label'         => __('First Heading', 'premium-addons-for-elementor'),
                'tab'           => Controls_Manager::TAB_STYLE
            ]
        );
        
        /*First Typography*/
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'          => 'first_header_typography',
                'scheme'        => Scheme_Typography::TYPOGRAPHY_1,
                'selector'      => '{{WRAPPER}} .premium-dual-header-first-span'
            ]
        );
        
        $this->add_control('premium_dual_header_first_animated',
            [
                'label'         => __('Animated Background', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SWITCHER
            ]
        );

        /*First Coloring Style*/
        $this->add_control('premium_dual_header_first_back_clip',
            [
                'label'         => __('Background Style', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SELECT,
                'default'       => 'color',
                'description'   => __('Choose ‘Normal’ style to put a background behind the text. Choose ‘Clipped’ style so the background will be clipped on the text.','premium-addons-for-elementor'),
                'options'       => [
                    'color'         => __('Normal', 'premium-addons-for-elementor'),
                    'clipped'       => __('Clipped', 'premium-addons-for-elementor'),
                ],
                'label_block'   =>  true
            ]
        );

        /*First Color*/
        $this->add_control('premium_dual_header_first_color',
            [
                'label'         => __('Text Color', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::COLOR,
                'scheme'        => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_1
                ],
                'condition'     => [
                    'premium_dual_header_first_back_clip' => 'color'
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-dual-header-first-span'   => 'color: {{VALUE}};'
                ]
            ]
        );
        
        /*First Background Color*/
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'              => 'premium_dual_header_first_background',
                'types'             => [ 'classic' , 'gradient' ],
                'condition'         => [
                    'premium_dual_header_first_back_clip'  => 'color'
                ],
                'selector'          => '{{WRAPPER}} .premium-dual-header-first-span'
            ]
        );
        
        $this->add_control('premium_dual_header_first_stroke',
            [
                'label'         => __('Stroke', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SWITCHER,
                'condition'         => [
                    'premium_dual_header_first_back_clip'  => 'clipped'
                ],
            ]
        );
        
        $this->add_control('premium_dual_header_first_stroke_text_color',
            [
                'label'         => __('Stroke Text Color', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::COLOR,
                'condition'     => [
                    'premium_dual_header_first_back_clip'   => 'clipped',
                    'premium_dual_header_first_stroke'      => 'yes'
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-dual-header-first-clip.stroke .premium-dual-header-first-span'   => '-webkit-text-stroke-color: {{VALUE}};'
                ]
            ]
        );
        
        $this->add_control('premium_dual_header_first_stroke_color',
            [
                'label'         => __('Stroke Fill Color', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::COLOR,
                'condition'     => [
                    'premium_dual_header_first_back_clip'   => 'clipped',
                    'premium_dual_header_first_stroke'      => 'yes'
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-dual-header-first-clip.stroke .premium-dual-header-first-span'   => '-webkit-text-fill-color: {{VALUE}};'
                ]
            ]
        );
        
        $this->add_control('premium_dual_header_first_stroke_width',
            [
                'label'         => __('Stroke Fill Width', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SLIDER,
                'condition'     => [
                    'premium_dual_header_first_back_clip'   => 'clipped',
                    'premium_dual_header_first_stroke'      => 'yes'
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-dual-header-first-clip.stroke .premium-dual-header-first-span'   => '-webkit-text-stroke-width: {{SIZE}}px;'
                ]
            ]
        );
        
        /*First Clip*/
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'              => 'premium_dual_header_first_clipped_background',
                'types'             => [ 'classic' , 'gradient' ],
                'condition'         => [
                    'premium_dual_header_first_back_clip'  => 'clipped',
                    'premium_dual_header_first_stroke!'      => 'yes'
                ],
                'selector'          => '{{WRAPPER}} .premium-dual-header-first-span'
            ]
        );
        
        /*First Border*/
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'              => 'first_header_border',
                'selector'          => '{{WRAPPER}} .premium-dual-header-first-span',
                'separator'         => 'before'
            ]
        );
        
        /*First Border Radius*/
        $this->add_control('premium_dual_header_first_border_radius',
            [
                'label'         => __('Border Radius', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SLIDER,
                'size_units'    => ['px', '%', 'em'],
                'selectors'     => [
                    '{{WRAPPER}} .premium-dual-header-first-span' => 'border-radius: {{SIZE}}{{UNIT}};'
                ]
            ]
        );
        
        /*First Text Shadow*/
        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'label'         => __('Shadow','premium-addons-for-elementor'),
                'name'          => 'premium_dual_header_first_text_shadow',
                'selector'      => '{{WRAPPER}} .premium-dual-header-first-span'
            ]
        );
        
        /*First Margin*/
        $this->add_responsive_control('premium_dual_header_first_margin',
            [
                'label'         => __('Margin', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::DIMENSIONS,
                'size_units'    => [ 'px', 'em', '%' ],
                'separator'     => 'before',
                'selectors'     => [
                    '{{WRAPPER}} .premium-dual-header-first-span' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}'
                ]
            ]
        );
        
        /*First Padding*/
        $this->add_responsive_control('premium_dual_header_first_padding',
            [
                'label'         => __('Padding', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::DIMENSIONS,
                'size_units'    => [ 'px', 'em', '%' ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-dual-header-first-span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}'
                ]
            ]
        );
        
        /*End First Header Styling Section*/
        $this->end_controls_section();
        
        /*Start First Header Styling Section*/
        $this->start_controls_section('premium_dual_header_second_style',
            [
                'label'         => __('Second Heading', 'premium-addons-for-elementor'),
                'tab'           => Controls_Manager::TAB_STYLE
            ]
        );
        
        /*Second Typography*/
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'          => 'second_header_typography',
                'scheme'        => Scheme_Typography::TYPOGRAPHY_1,
                'selector'      => '{{WRAPPER}} .premium-dual-header-second-header'
            ]
        );
        
        $this->add_control('premium_dual_header_second_animated',
            [
                'label'         => __('Animated Background', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SWITCHER
            ]
        );
        
        /*Second Coloring Style*/
        $this->add_control('premium_dual_header_second_back_clip',
            [
                'label'         => __('Background Style', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SELECT,
                'default'       => 'color',
                'description'   => __('Choose ‘Normal’ style to put a background behind the text. Choose ‘Clipped’ style so the background will be clipped on the text.','premium-addons-for-elementor'),
                'options'       => [
                    'color'         => __('Normal', 'premium-addons-for-elementor'),
                    'clipped'       => __('Clipped', 'premium-addons-for-elementor')
                ],
                'label_block'   =>  true
            ]
        );
        
        /*Second Color*/
        $this->add_control('premium_dual_header_second_color',
            [
                'label'         => __('Text Color', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::COLOR,
                'scheme'        => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_2
                ],
                'condition'     => [
                    'premium_dual_header_second_back_clip' => 'color'
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-dual-header-second-header'   => 'color: {{VALUE}};'
                ]
            ]
        );
        
        /*Second Background Color*/
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'              => 'premium_dual_header_second_background',
                'types'             => [ 'classic' , 'gradient' ],
                'condition'         => [
                    'premium_dual_header_second_back_clip'  => 'color'
                ],
                'selector'          => '{{WRAPPER}} .premium-dual-header-second-header'
            ]
        );
        
        $this->add_control('premium_dual_header_second_stroke',
            [
                'label'         => __('Stroke', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SWITCHER,
                'condition'         => [
                    'premium_dual_header_second_back_clip'  => 'clipped'
                ],
            ]
        );
        
        $this->add_control('premium_dual_header_second_stroke_text_color',
            [
                'label'         => __('Stroke Text Color', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::COLOR,
                'condition'     => [
                    'premium_dual_header_second_back_clip'   => 'clipped',
                    'premium_dual_header_second_stroke'      => 'yes'
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-dual-header-second-clip.stroke'   => '-webkit-text-stroke-color: {{VALUE}};'
                ]
            ]
        );
        
        $this->add_control('premium_dual_header_second_stroke_color',
            [
                'label'         => __('Stroke Fill Color', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::COLOR,
                'condition'     => [
                    'premium_dual_header_second_back_clip'   => 'clipped',
                    'premium_dual_header_second_stroke'      => 'yes'
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-dual-header-second-clip.stroke'   => '-webkit-text-fill-color: {{VALUE}};'
                ]
            ]
        );
        
        $this->add_control('premium_dual_header_second_stroke_width',
            [
                'label'         => __('Stroke Fill Width', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SLIDER,
                'condition'     => [
                    'premium_dual_header_second_back_clip'   => 'clipped',
                    'premium_dual_header_second_stroke'      => 'yes'
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-dual-header-second-clip.stroke'   => '-webkit-text-stroke-width: {{SIZE}}px;'
                ]
            ]
        );
        
        /*Second Clip*/
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'              => 'premium_dual_header_second_clipped_background',
                'types'             => [ 'classic' , 'gradient' ],
                'condition'         => [
                    'premium_dual_header_second_back_clip'  => 'clipped',
                    'premium_dual_header_second_stroke!'      => 'yes'
                ],
                'selector'          => '{{WRAPPER}} .premium-dual-header-second-header'
            ]
        );
        
        /*Second Border*/
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'              => 'second_header_border',
                'selector'          => '{{WRAPPER}} .premium-dual-header-second-header',
                'separator'         => 'before'
            ]
        );
        
        /*Second Border Radius*/
        $this->add_control('premium_dual_header_second_border_radius',
            [
                'label'         => __('Border Radius', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SLIDER,
                'size_units'    => ['px', '%', 'em'],
                'selectors'     => [
                    '{{WRAPPER}} .premium-dual-header-second-header' => 'border-radius: {{SIZE}}{{UNIT}};'
                ]
            ]
        );
        
        /*Second Text Shadow*/
        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'label'         => __('Shadow','premium-addons-for-elementor'),
                'name'          => 'premium_dual_header_second_text_shadow',
                'selector'      => '{{WRAPPER}} .premium-dual-header-second-header'
            ]
        );
        
        /*Second Margin*/
        $this->add_responsive_control('premium_dual_header_second_margin',
            [
                'label'         => __('Margin', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::DIMENSIONS,
                'separator'     => 'before',
                'size_units'    => [ 'px', 'em', '%' ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-dual-header-second-header' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}'
                ]
            ]
        );
        
        /*Second Padding*/
        $this->add_responsive_control('premium_dual_header_second_padding',
            [
                'label'         => __('Padding', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::DIMENSIONS,
                'size_units'    => [ 'px', 'em', '%' ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-dual-header-second-header' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ]
            ]
        );
        
        /*End Second Header Styling Section*/
        $this->end_controls_section();
       
    }

    /**
	 * Render Dual Heading widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
    protected function render() {
        
        $settings = $this->get_settings_for_display();

        $this->add_inline_editing_attributes('premium_dual_header_first_header_text');

        $this->add_inline_editing_attributes('premium_dual_header_second_header_text');

        $first_title_tag = $settings['premium_dual_header_first_header_tag'];

        $first_title_text = $settings['premium_dual_header_first_header_text'] . ' ';

        $second_title_text = $settings['premium_dual_header_second_header_text'];

        $first_clip = '';

        $second_clip = '';
        
        $first_stroke = '';

        $second_stroke = '';

        if( 'clipped' === $settings['premium_dual_header_first_back_clip'] ) : $first_clip = "premium-dual-header-first-clip"; endif; 

        if( 'clipped' === $settings['premium_dual_header_second_back_clip'] ) : $second_clip = "premium-dual-header-second-clip"; endif; 
        
        if( ! empty( $first_clip ) && 'yes' === $settings['premium_dual_header_first_stroke'] ) : $first_stroke = " stroke"; endif; 

        if( ! empty( $second_clip ) && 'yes' === $settings['premium_dual_header_second_stroke'] ) : $second_stroke = " stroke"; endif; 
        
        $first_grad = $settings['premium_dual_header_first_animated'] === 'yes' ? ' gradient' : '';
        
        $second_grad = $settings['premium_dual_header_second_animated'] === 'yes' ? ' gradient' : '';
        
        $full_title = '<' . $first_title_tag . ' class="premium-dual-header-first-header ' . $first_clip . $first_stroke . $first_grad . '"><span class="premium-dual-header-first-span">'. $first_title_text . '</span><span class="premium-dual-header-second-header ' . $second_clip . $second_stroke . $second_grad . '">'. $second_title_text . '</span></' . $settings['premium_dual_header_first_header_tag'] . '> ';
        
        $link = '';
        if( $settings['premium_dual_header_link_switcher'] === 'yes' ) {

            if( $settings['premium_dual_heading_link_selection'] === 'link' ) {

                $link = get_permalink( $settings['premium_dual_heading_existing_link'] );

            } else {

                $link = $settings['premium_dual_heading_link']['url'];

            }
        }
        
        
    ?>
    
    <div class="premium-dual-header-container">
        <?php if( ! empty ( $link ) ) : ?>
            <a href="<?php echo esc_attr( $link ); ?>" <?php if( ! empty( $settings['premium_dual_heading_link']['is_external'] ) ) : ?> target="_blank" <?php endif; ?><?php if( ! empty( $settings['premium_dual_heading_link']['nofollow'] ) ) : ?> rel="nofollow" <?php endif; ?>>
            <?php endif; ?>
            <div class="premium-dual-header-first-container">
                <?php echo $full_title; ?>
            </div>
        <?php if( ! empty ( $link ) ) : ?>
            </a>
        <?php endif; ?>
    </div>

    <?php
    }
    
    /**
	 * Render Dual Heading widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
    protected function content_template()
    {
        ?>
        <#
        
            view.addInlineEditingAttributes('premium_dual_header_first_header_text');

            view.addInlineEditingAttributes('premium_dual_header_second_header_text');

            var firstTag = settings.premium_dual_header_first_header_tag,

            firstText = settings.premium_dual_header_first_header_text + ' ',

            secondText = settings.premium_dual_header_second_header_text,

            firstClip = '',

            secondClip = '',
            
            firstStroke = '',
            
            secondStroke = '';

            if( 'clipped' === settings.premium_dual_header_first_back_clip )
                firstClip = "premium-dual-header-first-clip"; 

            if( 'clipped' === settings.premium_dual_header_second_back_clip )
                secondClip = "premium-dual-header-second-clip";
                
            if( 'yes' === settings.premium_dual_header_first_stroke )
                firstStroke = "stroke"; 

            if( 'yes' === settings.premium_dual_header_second_stroke )
                secondStroke = "stroke";

            var firstGrad = 'yes' === settings.premium_dual_header_first_animated  ? ' gradient' : '',

                secondGrad = 'yes' === settings.premium_dual_header_second_animated ? ' gradient' : '';
            
                view.addRenderAttribute('first_title', 'class', ['premium-dual-header-first-header', firstClip, firstGrad, firstStroke ] );
                view.addRenderAttribute('second_title', 'class', ['premium-dual-header-second-header', secondClip, secondGrad, secondStroke ] );
        
            var link = '';
            if( 'yes' === settings.premium_dual_header_link_switcher ) {

                if( 'link' === settings.premium_dual_heading_link_selection ) {

                    link = settings.premium_dual_heading_existing_link;

                } else {

                    link = settings.premium_dual_heading_link.url;

                }
            }
            
        
        #>
        
        <div class="premium-dual-header-container">
            <# if( 'yes' === settings.premium_dual_header_link_switcher && '' !== link ) { #>
                <a href="{{ link }}">
            <# } #>
            <div class="premium-dual-header-first-container">
                <{{{firstTag}}} {{{ view.getRenderAttributeString('first_title') }}}>
                    <span class="premium-dual-header-first-span">{{{ firstText }}}</span><span {{{ view.getRenderAttributeString('second_title') }}}>{{{ secondText }}}</span>
                </{{{firstTag}}}>
                
            </div>
            <# if( 'yes' == settings.premium_dual_header_link_switcher && '' !== link ) { #>
                </a>
            <# } #>
        </div>
        
        <?php
    }
}