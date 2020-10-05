<?php

/**
 * Premium Progress Bar.
 */
namespace PremiumAddons\Widgets;

// Elementor Classes.
use Elementor\Widget_Base;
use Elementor\Utils;
use Elementor\Controls_Manager;
use Elementor\Icons_Manager;
use Elementor\Repeater;
use Elementor\Scheme_Color;
use Elementor\Scheme_Typography;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;

// PremiumAddons Classes.
use PremiumAddons\Helper_Functions;

if ( ! defined( 'ABSPATH' ) ) exit; // If this file is called directly, abort.

/**
 * Class Premium_Progressbar
 */
class Premium_Progressbar extends Widget_Base {
    
    public function get_name() {
        return 'premium-addon-progressbar';
    }

    public function get_title() {
		return sprintf( '%1$s %2$s', Helper_Functions::get_prefix(), __('Progress Bar', 'premium-addons-for-elementor') );
	}
    
    public function get_icon() {
        return 'pa-progress-bar';
    }

    public function get_categories() {
        return [ 'premium-elements' ];
    }
    
    public function get_style_depends() {
        return [
            'premium-addons'
        ];
    }
    
    public function get_script_depends() {
        return [
            'elementor-waypoints',
            'lottie-js',
            'premium-addons-js'
        ];
    }

    public function get_keywords() {
        return ['circle', 'chart', 'line'];
    }
    
    public function get_custom_help_url() {
		return 'https://premiumaddons.com/support/';
	}

    /**
	 * Register Testimonials controls.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
    protected function _register_controls() {

        $this->start_controls_section('premium_progressbar_labels',
            [
                'label'         => __('Progress Bar Settings', 'premium-addons-for-elementor'),
            ]
        );

        $this->add_control('layout_type', 
            [
                'label'         => __('Type', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SELECT,
                'options'       => [
                    'line'          => __('Line', 'premium-addons-for-elementor'),
                    'circle'        => __('Circle', 'premium-addons-for-elementor'),
                    'dots'          => __('Dots', 'premium-addons-for-elementor'),
                ],
                'default'       =>'line',
                'label_block'   => true,
            ]
        );

        $this->add_responsive_control('dot_size', 
            [
                'label'     => __('Dot Size', 'premium-addons-for-elementor'),
                'type'      => Controls_Manager::SLIDER,
                'range'     => [
                    'px'    => [
                        'min'   => 1,
                        'max'   => 60,
                    ],
                ],
                'default'     => [
                    'size' => 25,
                    'unit' => 'px',
                ],
                'condition'     => [
                    'layout_type'   => 'dots'
                ],
                'render_type' => 'template',
                'selectors' => [
                    '{{WRAPPER}} .progress-segment' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_responsive_control('dot_spacing', 
            [
                'label'     => __('Spacing', 'premium-addons-for-elementor'),
                'type'      => Controls_Manager::SLIDER,
                'range'     => [
                    'px'    => [
                        'min'   => 1,
                        'max'   => 10,
                    ],
                ],
                'default'     => [
                    'size' => 8,
                    'unit' => 'px',
                ],
                'condition'     => [
                    'layout_type'   => 'dots'
                ],
                'render_type' => 'template',
                'selectors' => [
                    '{{WRAPPER}} .progress-segment:not(:first-child):not(:last-child)' => 'margin-right: calc( {{SIZE}}{{UNIT}}/2 ); margin-left: calc( {{SIZE}}{{UNIT}}/2 )',
                    '{{WRAPPER}} .progress-segment:first-child' => 'margin-right: calc( {{SIZE}}{{UNIT}}/2 )',
                    '{{WRAPPER}} .progress-segment:last-child' => 'margin-left: calc( {{SIZE}}{{UNIT}}/2 )',
                ],
            ]
        );

        $this->add_control('circle_size',
            [
                'label' => __('Size', 'premium-addons-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 50,
                        'max' => 500,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'size' => 200,
                ],
                'selectors' => [
                    '{{WRAPPER}} .premium-progressbar-circle-wrap' => 'width: {{SIZE}}px; height: {{SIZE}}px',
                ],
                'condition'     => [
                    'layout_type'   => 'circle'
                ]
            ]
        );
        
        $this->add_control('premium_progressbar_select_label', 
            [
                'label'         => __('Labels', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SELECT,
                'default'       =>'left_right_labels',
                'options'       => [
                    'left_right_labels'    => __('Left & Right Labels', 'premium-addons-for-elementor'),
                    'more_labels'          => __('Multiple Labels', 'premium-addons-for-elementor'),
                ],
                'label_block'   => true,
                'condition'     => [
                    'layout_type!'   => 'circle'
                ]
            ]
        );
        
        $this->add_control('premium_progressbar_left_label',
            [
                'label'         => __('Title', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::TEXT,
                'dynamic'       => [ 'active' => true ],
                'default'       => __('My Skill','premium-addons-for-elementor'),
                'label_block'   => true,
                'condition'     =>[
                    'premium_progressbar_select_label' => 'left_right_labels'
                ]
            ]
        );

        $this->add_control('premium_progressbar_right_label',
            [
                'label'         => __('Percentage', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::TEXT,
                'dynamic'       => [ 'active' => true ],
                'default'       => __('50%','premium-addons-for-elementor'),
                'label_block'   => true,
                'condition'     =>[
                    'premium_progressbar_select_label' => 'left_right_labels',
                    'layout_type!'   => 'circle'
                ]
            ]
        );

        $this->add_control('icon_type',
		  	[
		     	'label'			=> __( 'Icon Type', 'premium-addons-for-elementor' ),
		     	'type' 			=> Controls_Manager::SELECT,
		     	'options'		=> [
		     		'icon'  => __('Font Awesome', 'premium-addons-for-elementor'),
                    'image'=> __( 'Custom Image', 'premium-addons-for-elementor'),
                    'animation'     => __('Lottie Animation', 'premium-addons-for-elementor'),
		     	],
                 'default'		=> 'icon',
                 'condition'    =>[
                    'layout_type'   => 'circle'
                ]
		  	]
		);

		$this->add_control('icon_select',
		  	[
		     	'label'			=> __( 'Select an Icon', 'premium-addons-for-elementor' ),
		     	'type'              => Controls_Manager::ICONS,
                'condition'     =>[
                    'icon_type' => 'icon',
                    'layout_type'   => 'circle'
                ]
		  	]
		);

		$this->add_control('image_upload',
		  	[
		     	'label'			=> __( 'Upload Image', 'premium-addons-for-elementor' ),
		     	'type' 			=> Controls_Manager::MEDIA,
			  	'default'		=> [
			  		'url' => Utils::get_placeholder_image_src(),
                ],
                'condition'     => [
                    'icon_type' => 'image',
                    'layout_type'   => 'circle'
                ]
		  	]
        );

        $this->add_control('lottie_url', 
            [
                'label'             => __( 'Animation JSON URL', 'premium-addons-for-elementor' ),
                'type'              => Controls_Manager::TEXT,
                'dynamic'           => [ 'active' => true ],
                'description'       => 'Get JSON code URL from <a href="https://lottiefiles.com/" target="_blank">here</a>',
                'label_block'       => true,
                'condition'     => [
                    'layout_type'   => 'circle',
                    'icon_type'   => 'animation',
                ]
            ]
        );

        $this->add_control('lottie_loop',
            [
                'label'         => __('Loop','premium-addons-for-elementor'),
                'type'          => Controls_Manager::SWITCHER,
                'return_value'  => 'true',
                'default'       => 'true',
                'condition'     => [
                    'layout_type'   => 'circle',
                    'icon_type'   => 'animation',
                ]
            ]
        );

        $this->add_control('lottie_reverse',
            [
                'label'         => __('Reverse','premium-addons-for-elementor'),
                'type'          => Controls_Manager::SWITCHER,
                'return_value'  => 'true',
                'condition'     => [
                    'layout_type'   => 'circle',
                    'icon_type'   => 'animation',
                ]
            ]
        );
        
        $this->add_responsive_control('icon_size',
            [
                'label'         => __('Icon Size', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SLIDER,
                'condition'     => [
                    'layout_type'   => 'circle'
                ],
                'default'       => [
                    'unit'  => 'px',
                    'size'  => 30,
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-progressbar-circle-content i' => 'font-size: {{SIZE}}px',
                    '{{WRAPPER}} .premium-progressbar-circle-content svg, {{WRAPPER}} .premium-progressbar-circle-content img' => 'width: {{SIZE}}px; height: {{SIZE}}px',
                ]
            ]
        );

        $this->add_control('show_percentage_value',
            [
                'label'      => __('Show Percentage Value', 'premium-addons-for-elementor'),
                'type'       => Controls_Manager::SWITCHER,
                'default'     => 'yes',
                'condition'   => [
                    'layout_type'   => 'circle'
                ]
            ]
        );
        
        $repeater = new REPEATER();
        
        $repeater->add_control('text',
            [
                'label'             => __( 'Label','premium-addons-for-elementor' ),
                'type'              => Controls_Manager::TEXT,
                'dynamic'           => [ 'active' => true ],
                'label_block'       => true,
                'placeholder'       => __( 'label','premium-addons-for-elementor' ),
                'default'           => __( 'label', 'premium-addons-for-elementor' ),
            ]
        );
        
        $repeater->add_control('number',
            [
                'label'             => __( 'Percentage', 'premium-addons-for-elementor' ),
                'dynamic'           => [ 'active' => true ],
                'type'              => Controls_Manager::TEXT,
                'default'           => 50,
            ]
        );
        
        $this->add_control('premium_progressbar_multiple_label',
            [
                'label'     => __('Label','premium-addons-for-elementor'),
                'type'      => Controls_Manager::REPEATER,
                'default'   => [
                    [
                        'text' => __( 'Label','premium-addons-for-elementor' ),
                        'number' => 50
                    ]
                    ],
                'fields'    => $repeater->get_controls(),
                'condition' => [
                    'premium_progressbar_select_label'  => 'more_labels',
                    'layout_type!'   => 'circle'
                ]
            ]
        );
        
        $this->add_control('premium_progress_bar_space_percentage_switcher',
            [
                'label'      => __('Enable Percentage', 'premium-addons-for-elementor'),
                'type'       => Controls_Manager::SWITCHER,
                'default'     => 'yes',
                'description' => __('Enable percentage for labels','premium-addons-for-elementor'),
                'condition'   => [
                    'premium_progressbar_select_label'=>'more_labels',
                    'layout_type!'   => 'circle'
                ]
            ]
        );
        
        $this->add_control('premium_progressbar_select_label_icon', 
            [
                'label'         => __('Labels Indicator', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SELECT,
                'default'       =>'line_pin',
                'options'       => [
                    ''            => __('None','premium-addons-for-elementor'),
                    'line_pin'    => __('Pin', 'premium-addons-for-elementor'),
                    'arrow'       => __('Arrow','premium-addons-for-elementor'),
                ],
                'condition'     =>[
                    'premium_progressbar_select_label' => 'more_labels',
                    'layout_type!'   => 'circle'
                ]
            ]
        );
        
        $this->add_control('premium_progressbar_more_labels_align',
            [
                'label'         => __('Labels Alignment','premuim-addons-for-elementor'),
                'type'          => Controls_Manager::CHOOSE,
                'options'       => [
                    'left'      => [
                        'title'=> __( 'Left', 'premium-addons-for-elementor' ),
                        'icon' => 'fa fa-align-left',   
                    ],
                    'center'     => [
                        'title'=> __( 'Center', 'premium-addons-for-elementor' ),
                        'icon' => 'fa fa-align-center',
                    ],
                    'right'     => [
                        'title'=> __( 'Right', 'premium-addons-for-elementor' ),
                        'icon' => 'fa fa-align-right',
                    ],
                ],
                'default'       => 'center',
                'condition'     =>[
                    'premium_progressbar_select_label' => 'more_labels',
                    'layout_type!'   => 'circle'
                ]
            ]
        );
    
        $this->add_control('premium_progressbar_progress_percentage',
            [
                'label'             => __('Value', 'premium-addons-for-elementor'),
                'type'              => Controls_Manager::TEXT,
                'dynamic'           => [ 'active' => true ],
                'default'           => 50
            ]
        );
        
        $this->add_control('premium_progressbar_progress_style', 
            [
                'label'         => __('Style', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SELECT,
                'default'       => 'solid',
                'options'       => [
                    'solid'    => __('Solid', 'premium-addons-for-elementor'),
                    'stripped' => __('Striped', 'premium-addons-for-elementor'),
                    'gradient' => __('Animated Gradient', 'premium-addons-for-elementor'),
                ],
                'condition'     => [
                    'layout_type'   => 'line'
                ]
            ]
        );
        
        $this->add_control('premium_progressbar_speed',
            [
                'label'             => __('Speed (milliseconds)', 'premium-addons-for-elementor'),
                'type'              => Controls_Manager::NUMBER
            ]
        );
        
        $this->add_control('premium_progressbar_progress_animation', 
            [
                'label'         => __('Animated', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SWITCHER,
                'condition'     => [
                    'premium_progressbar_progress_style'    => 'stripped',
                    'layout_type'   => 'line'
                ]
            ]
        );

        $this->add_control('gradient_colors',
            [
                'label'         => __('Gradient Colors', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::TEXT,
                'description'   => __('Enter Colors separated with \' , \'.','premium-addons-for-elementor'),
                'default'       => '#6EC1E4,#54595F',
                'label_block'   => true,
                'condition'     => [
                    'layout_type'   => 'line',
                    'premium_progressbar_progress_style' => 'gradient'
                ]
            ]
        );
        
        $this->end_controls_section();

        
        $this->start_controls_section('premium_progressbar_progress_bar_settings',
            [
                'label'             => __('Progress Bar', 'premium-addons-for-elementor'),
                'tab'               => Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_control('premium_progressbar_progress_bar_height',
            [
                'label'         => __('Height', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SLIDER,
                'default'       => [
                    'size'  => 25,
                ],
                'label_block'   => true,
                'selectors'     => [
                    '{{WRAPPER}} .premium-progressbar-bar-wrap, {{WRAPPER}} .premium-progressbar-bar' => 'height: {{SIZE}}px;',   
                ],
                'condition'     => [
                    'layout_type'   => 'line'
                ]
            ]
        );

        $this->add_control('premium_progressbar_progress_bar_radius',
            [
                'label'         => __('Border Radius', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SLIDER,
                'size_units'    => ['px', '%', 'em'],
                'range'         => [
                    'px'  => [
                        'min' => 0,
                        'max' => 60,
                    ],
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-progressbar-bar-wrap, {{WRAPPER}} .premium-progressbar-bar, {{WRAPPER}} .progress-segment' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
                'condition'     => [
                    'layout_type!'   => 'circle'
                ]
            ]
        );

        $this->add_control('circle_border_width',
            [
                'label' => __('Border Width', 'premium-addons-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} .premium-progressbar-circle-base' => 'border-width: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}} .premium-progressbar-circle div' => 'border-width: {{SIZE}}{{UNIT}}',
                ],
                'condition'     => [
                    'layout_type'   => 'circle'
                ]
            ]
        );

        $this->add_control('circle_base_border_color',
            [
                'label'         => __('Border Color', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::COLOR,
                'scheme'        => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_1,
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-progressbar-circle-base' => 'border-color: {{VALUE}};',
                ],
                'condition'     => [
                    'layout_type'   => 'circle'
                ]
            ]
        );

        $this->add_control('fill_colors_title',
            [
                'label'             =>  __('Fill', 'premium-addons-for-elementor'),
                'type'              => Controls_Manager::HEADING,
            ]
        );
        
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'              => 'premium_progressbar_progress_color',
                'types'             => [ 'classic' , 'gradient' ],
                'selector'          => '{{WRAPPER}} .premium-progressbar-bar, {{WRAPPER}} .segment-inner',
                'condition'     => [
                    'layout_type!'   => 'circle'
                ]
            ]
        );

        $this->add_control('circle_fill_color',
            [
                'label'         => __('Select Color', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::COLOR,
                'scheme'        => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_2,
                ],
                'condition'     => [
                    'layout_type'   => 'circle'
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-progressbar-circle div' => 'border-color: {{VALUE}};',
                ],                
            ]
        );

        $this->add_control('base_colors_title',
            [
                'label'             =>  __('Base', 'premium-addons-for-elementor'),
                'type'              => Controls_Manager::HEADING,
            ]
        );
        
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'              => 'premium_progressbar_background',
                'types'             => [ 'classic' , 'gradient' ],
                'selector'          => '{{WRAPPER}} .premium-progressbar-bar-wrap:not(.premium-progressbar-dots), {{WRAPPER}} .premium-progressbar-circle-base, {{WRAPPER}} .progress-segment',
            ]
        );

        $this->add_responsive_control('premium_progressbar_container_margin',
            [
                'label'             => __('Margin', 'premium-addons-for-elementor'),
                'type'              => Controls_Manager::DIMENSIONS,
                'size_units'        => [ 'px', 'em', '%' ],
                'selectors'         => [
                    '{{WRAPPER}} .premium-progressbar-bar-wrap' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition'     => [
                    'layout_type!'   => 'circle'
                ]
            ]      
        );
        
        $this->end_controls_section();

        $this->start_controls_section('premium_progressbar_labels_section',
            [
                'label'         => __('Labels', 'premium-addons-for-elementor'),
                'tab'           => Controls_Manager::TAB_STYLE,
                'condition'     => [
                    'premium_progressbar_select_label'  => 'left_right_labels'
                ]
            ]
        );
        
        $this->add_control('premium_progressbar_left_label_hint',
            [
                'label'             =>  __('Title', 'premium-addons-for-elementor'),
                'type'              => Controls_Manager::HEADING,
            ]
        );
        
        $this->add_control('premium_progressbar_left_label_color',
                [
                    'label'         => __('Color', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::COLOR,
                    'scheme'        => [
                        'type'  => Scheme_Color::get_type(),
                        'value' => Scheme_Color::COLOR_1,
                    ],
                    'selectors'     => [
                    '{{WRAPPER}} .premium-progressbar-left-label' => 'color: {{VALUE}};',
                ]
            ]
        );
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'          => 'left_label_typography',
                'scheme'        => Scheme_Typography::TYPOGRAPHY_1,
                'selector'      => '{{WRAPPER}} .premium-progressbar-left-label',
            ]
        );
        
        $this->add_responsive_control('premium_progressbar_left_label_margin',
            [
                'label'             => __('Margin', 'premium-addons-for-elementor'),
                'type'              => Controls_Manager::DIMENSIONS,
                'size_units'        => [ 'px', 'em', '%' ],
                'selectors'         => [
                    '{{WRAPPER}} .premium-progressbar-left-label' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]      
        );
        
        $this->add_control('premium_progressbar_right_label_hint',
            [
                'label'             =>  __('Percentage', 'premium-addons-for-elementor'),
                'type'              => Controls_Manager::HEADING,
                'separator'         => 'before'
            ]
        );
        
        $this->add_control('premium_progressbar_right_label_color',
             [
                'label'             => __('Color', 'premium-addons-for-elementor'),
                'type'              => Controls_Manager::COLOR,
                 'scheme'        => [
                        'type'  => Scheme_Color::get_type(),
                        'value' => Scheme_Color::COLOR_1,
                    ],
                'selectors'        => [
                    '{{WRAPPER}} .premium-progressbar-right-label' => 'color: {{VALUE}};',
                ]
            ]
         );
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'          => 'right_label_typography',
                'scheme'        => Scheme_Typography::TYPOGRAPHY_1,
                'selector'      => '{{WRAPPER}} .premium-progressbar-right-label',
            ]
        );
        
        $this->add_responsive_control('premium_progressbar_right_label_margin',
            [
                'label'             => __('Margin', 'premium-addons-for-elementor'),
                'type'              => Controls_Manager::DIMENSIONS,
                'size_units'        => [ 'px', 'em', '%' ],
                'selectors'         => [
                    '{{WRAPPER}} .premium-progressbar-right-label' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]      
        );
        
        $this->end_controls_section();
        
        $this->start_controls_section('premium_progressbar_multiple_labels_section',
            [
                'label'         => __('Multiple Labels', 'premium-addons-for-elementor'),
                'tab'           => Controls_Manager::TAB_STYLE,
                'condition'     =>[
                    'premium_progressbar_select_label'  => 'more_labels'
                ]
            ]
        );

        $this->add_control('premium_progressbar_multiple_label_color',
             [
                'label'             => __('Labels\' Color', 'premium-addons-for-elementor'),
                'type'              => Controls_Manager::COLOR,
                 'scheme'        => [
                        'type'  => Scheme_Color::get_type(),
                        'value' => Scheme_Color::COLOR_1,
                    ],
                'selectors'        => [
                    '{{WRAPPER}} .premium-progressbar-center-label' => 'color: {{VALUE}};',
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [   
                'label'         => __('Labels\' Typography', 'premium-addons-for-elementor'),
                'name'          => 'more_label_typography',
                'scheme'        => Scheme_Typography::TYPOGRAPHY_1,
                'selector'      => '{{WRAPPER}} .premium-progressbar-center-label',
            ]
        );

        $this->add_control('premium_progressbar_value_label_color',
            [
                'label'             => __('Percentage Color', 'premium-addons-for-elementor'),
                'type'              => Controls_Manager::COLOR,
                 'scheme'        => [
                        'type'  => Scheme_Color::get_type(),
                        'value' => Scheme_Color::COLOR_1,
                    ],
                 'condition'       =>[
                     'premium_progress_bar_space_percentage_switcher'=>'yes'
                 ],
                'selectors'        => [
                    '{{WRAPPER}} .premium-progressbar-percentage' => 'color: {{VALUE}};',
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [   
                'label'         => __('Percentage Typography','premium-addons-for-elementor'),
                'name'          => 'percentage_typography',
                'condition'       =>[
                        'premium_progress_bar_space_percentage_switcher'=>'yes'
                ],
                'scheme'        => Scheme_Typography::TYPOGRAPHY_1,
                'selector'      => '{{WRAPPER}} .premium-progressbar-percentage',
            ]
        );

         $this->end_controls_section();

         $this->start_controls_section('premium_progressbar_multiple_labels_arrow_section',
            [
                'label'         => __('Arrow', 'premium-addons-for-elementor'),
                'tab'           => Controls_Manager::TAB_STYLE,
                'condition'     =>[
                    'premium_progressbar_select_label'  => 'more_labels',
                    'premium_progressbar_select_label_icon' => 'arrow'
                ]
            ]
        );
        
        $this->add_control('premium_progressbar_arrow_color',
            [
                'label'         => __('Color', 'premium_elementor'),
                'type'          => Controls_Manager::COLOR,
                'scheme'        => [
                    'type'          => Scheme_Color::get_type(),
                    'value'         => Scheme_Color::COLOR_1,
                ],
                'condition'     =>[
                    'premium_progressbar_select_label_icon' => 'arrow'
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-progressbar-arrow' => 'color: {{VALUE}};'
                ]
            ]
        );
        
	 $this->add_responsive_control('premium_arrow_size',
        [
            'label'	       => __('Size','premium-addons-for-elementor'),
            'type'             =>Controls_Manager::SLIDER,
            'size_units'       => ['px', "em"],
            'condition'        =>[
                'premium_progressbar_select_label_icon' => 'arrow'
            ],
            'selectors'          => [
                '{{WRAPPER}} .premium-progressbar-arrow' => 'border-width: {{SIZE}}{{UNIT}};'
            ]
        ]
    );

       $this->end_controls_section();

       $this->start_controls_section('premium_progressbar_multiple_labels_pin_section',
            [
                'label'         => __('Indicator', 'premium-addons-for-elementor'),
                'tab'           => Controls_Manager::TAB_STYLE,
                'condition'     =>[
                    'premium_progressbar_select_label'  => 'more_labels',
                    'premium_progressbar_select_label_icon' => 'line_pin'
                ]
            ]
        );
        
       $this->add_control('premium_progressbar_line_pin_color',
                [
                    'label'             => __('Color', 'premium-addons-for-elementor'),
                    'type'              => Controls_Manager::COLOR,
                    'scheme'            => [
                        'type'               => Scheme_Color::get_type(),
                        'value'              => Scheme_Color::COLOR_2,
                    ],
                    'condition'         =>[
                        'premium_progressbar_select_label_icon' =>'line_pin'
                    ],
                     'selectors'        => [
                    '{{WRAPPER}} .premium-progressbar-pin' => 'border-color: {{VALUE}};'
                ]
            ]
         );

        $this->add_responsive_control('premium_pin_size',
            [
                    'label'	       => __('Size','premium-addons-for-elementor'),
                    'type'             =>Controls_Manager::SLIDER,
                    'size_units'       => ['px', "em"],
                    'condition'        =>[
                        'premium_progressbar_select_label_icon' => 'line_pin'
                    ],
                    'selectors'         => [
                        '{{WRAPPER}} .premium-progressbar-pin' => 'border-left-width: {{SIZE}}{{UNIT}};'
                        ]
            ]
        );

        $this->add_responsive_control('premium_pin_height',
            [
                    'label'	       => __('Height','premium-addons-for-elementor'),
                    'type'             =>Controls_Manager::SLIDER,
                    'size_units'       => ['px', "em"],
                    'condition'        =>[
                        'premium_progressbar_select_label_icon' => 'line_pin'
                    ],
                    'selectors'         => [
                        '{{WRAPPER}} .premium-progressbar-pin' => 'height: {{SIZE}}{{UNIT}};'
                        ]
            ]
        );
        
        $this->end_controls_section();

        $this->start_controls_section('icon_style',
            [
                'label'             => __('Icon', 'premium-addons-for-elementor'),
                'tab'               => Controls_Manager::TAB_STYLE,
                'condition'     => [
                    'layout_type'     => 'circle'
                ]
            ]
        );

        $this->add_control('icon_color',
            [
                'label'         => __('Color', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::COLOR,
                'scheme'        => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_1,
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-progressbar-circle-icon' => 'color: {{VALUE}};',
                ],
                'condition'     => [
                    'icon_type'     => 'icon'
                ]
            ]
        );

        $this->add_control('icon_background_color',
            [
                'label'         => __('Background Color', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::COLOR,
                'selectors'     => [
                    '{{WRAPPER}} .premium-progressbar-circle-icon' => 'background-color: {{VALUE}};',
                ],
                'condition'     => [
                    'icon_type!'     => 'image'
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(), 
            [
                'name'          => 'icon_border',
                'selector'      => '{{WRAPPER}} .premium-progressbar-circle-icon',
            ]
        );
        
        $this->add_responsive_control('icon_border_radius',
            [
                'label'         => __('Border Radius', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::DIMENSIONS,
                'size_units'    => ['px', 'em', '%'],
                'selectors'     => [
                    '{{WRAPPER}} .premium-progressbar-circle-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}'
                ]
            ]
        );

        $this->add_responsive_control('icon_padding',
            [
                'label'         => __('Padding', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::DIMENSIONS,
                'size_units'    => ['px', 'em', '%'],
                'selectors'     => [
                    '{{WRAPPER}} .premium-progressbar-circle-icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ]
            ]
        );

        $this->end_controls_section();
    }

    /**
	 * Render Progress Bar widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
    protected function render() {
        
        $settings = $this->get_settings_for_display();

        $this->add_inline_editing_attributes('premium_progressbar_left_label');
        $this->add_inline_editing_attributes('premium_progressbar_right_label');
        
        $length = isset ( $settings['premium_progressbar_progress_percentage']['size'] ) ? $settings['premium_progressbar_progress_percentage']['size'] : $settings['premium_progressbar_progress_percentage'];
        
        $style = $settings['premium_progressbar_progress_style'];
        $type  = $settings['layout_type'];

        $progressbar_settings = [
            'progress_length'   => $length,
            'speed'             => !empty( $settings['premium_progressbar_speed'] ) ? $settings['premium_progressbar_speed'] : 1000,
            'type'              => $type,
        ];

        if( 'dots' === $type ) {
            $progressbar_settings['dot'] = $settings['dot_size']['size'];
            $progressbar_settings['spacing'] = $settings['dot_spacing']['size'];
        }

        $this->add_render_attribute( 'progressbar', 'class', 'premium-progressbar-container' );

        if( 'stripped' === $style ) {
            $this->add_render_attribute( 'progressbar', 'class', 'premium-progressbar-striped' );
        } elseif( 'gradient' === $style ) {
            $this->add_render_attribute( 'progressbar', 'class', 'premium-progressbar-gradient' );
            $progressbar_settings['gradient'] = $settings['gradient_colors'];
        }
        
        if( 'yes' === $settings['premium_progressbar_progress_animation'] ) {
            $this->add_render_attribute( 'progressbar', 'class', 'premium-progressbar-active' );
        }

        $this->add_render_attribute( 'progressbar', 'data-settings', wp_json_encode($progressbar_settings) );
        
        if( 'circle' !== $type ) {
            $this->add_render_attribute( 'wrap', 'class', 'premium-progressbar-bar-wrap' );

            if( 'dots' === $type ) {
                $this->add_render_attribute( 'wrap', 'class', 'premium-progressbar-dots' );
            }

        } else {
            $this->add_render_attribute( 'wrap', 'class', 'premium-progressbar-circle-wrap' );

            $icon_type = $settings['icon_type'];

            if( 'animation' === $icon_type ) {
                $this->add_render_attribute( 'progress_lottie', [
                    'class' => [
                        'premium-progressbar-circle-icon',
                        'premium-lottie-animation'
                    ],
                    'data-lottie-url' => $settings['lottie_url'],
                    'data-lottie-loop' => $settings['lottie_loop'],
                    'data-lottie-reverse' => $settings['lottie_reverse']
                ]);
            }

        }

    ?>

   <div <?php echo $this->get_render_attribute_string( 'progressbar' ); ?>>

        <?php if ($settings['premium_progressbar_select_label'] === 'left_right_labels') :?>
            <p class="premium-progressbar-left-label"><span <?php echo $this->get_render_attribute_string('premium_progressbar_left_label'); ?>><?php echo $settings['premium_progressbar_left_label'];?></span></p>
            <p class="premium-progressbar-right-label"><span <?php echo $this->get_render_attribute_string('premium_progressbar_right_label'); ?>><?php echo $settings['premium_progressbar_right_label'];?></span></p>
        <?php endif;?>

        <?php if ($settings['premium_progressbar_select_label'] === 'more_labels') : ?>
            <div class="premium-progressbar-container-label" style="position:relative;">
            <?php foreach($settings['premium_progressbar_multiple_label'] as $item){
                if( $this->get_settings('premium_progressbar_more_labels_align') === 'center' ) {
                    if($settings['premium_progress_bar_space_percentage_switcher'] === 'yes'){
                       if( $settings['premium_progressbar_select_label_icon'] === 'arrow' ) { 
                            echo '<div class ="premium-progressbar-multiple-label" style="left:'.$item['number'].'%;"><p class = "premium-progressbar-center-label" style="transform:translateX(-45%);">'.$item['text'].' <span class="premium-progressbar-percentage">'.$item['number'].'%</span></p><p class="premium-progressbar-arrow" style="left:'.$item['number'].'%; transform:translateX(50%);"></p></div>';
                        } elseif($settings['premium_progressbar_select_label_icon'] === 'line_pin') {
                           echo '<div class ="premium-progressbar-multiple-label" style="left:'.$item['number'].'%;"><p class = "premium-progressbar-center-label" style="transform:translateX(-45%);">'.$item['text'].' <span class="premium-progressbar-percentage">'.$item['number'].'%</span></p><p class="premium-progressbar-pin" style="left:'.$item['number'].'%; transform:translateX(50%);"></p></div>';
                        } else {
                            echo '<div class ="premium-progressbar-multiple-label" style="left:'.$item['number'].'%;"><p class = "premium-progressbar-center-label" style="transform:translateX(-45%);">'.$item['text'].' <span class="premium-progressbar-percentage">'.$item['number'].'%</span></p></div>';
                        }
                    } else{
                        if($settings['premium_progressbar_select_label_icon'] === 'arrow') { 
                            echo '<div class ="premium-progressbar-multiple-label" style="left:'.$item['number'].'%;"><p class = "premium-progressbar-center-label" style="transform:translateX(-45%);">'.$item['text'].'</p><p class="premium-progressbar-arrow" style="left:'.$item['number'].'%; transform:translateX(50%);"></p></div>';
                        } elseif( $settings['premium_progressbar_select_label_icon'] === 'line_pin') {
                           echo '<div class ="premium-progressbar-multiple-label" style="left:'.$item['number'].'%;"><p class = "premium-progressbar-center-label" style="transform:translateX(-45%)">'.$item['text'].'</p><p class="premium-progressbar-pin" style="left:'.$item['number'].'%; transform:translateX(50%);"></p></div>';
                        } else {
                            echo '<div class ="premium-progressbar-multiple-label" style="left:'.$item['number'].'%;"><p class = "premium-progressbar-center-label" style="transform:translateX(-45%);">'.$item['text'].'</p></div>';
                        }
                    }
                } elseif($this->get_settings('premium_progressbar_more_labels_align') === 'left' ) {
                    if($settings['premium_progress_bar_space_percentage_switcher'] === 'yes'){
                       if($settings['premium_progressbar_select_label_icon'] === 'arrow') { 
                            echo '<div class ="premium-progressbar-multiple-label" style="left:'.$item['number'].'%;"><p class = "premium-progressbar-center-label" style="transform:translateX(-10%);">'.$item['text'].' <span class="premium-progressbar-percentage">'.$item['number'].'%</span></p><p class="premium-progressbar-arrow" style="left:'.$item['number'].'%; transform:translateX(50%);"></p></div>';
                        } elseif($settings['premium_progressbar_select_label_icon'] === 'line_pin'){
                           echo '<div class ="premium-progressbar-multiple-label" style="left:'.$item['number'].'%;"><p class = "premium-progressbar-center-label" style="transform:translateX(-2%);">'.$item['text'].' <span class="premium-progressbar-percentage">'.$item['number'].'%</span></p><p class="premium-progressbar-pin" style="left:'.$item['number'].'%; transform:translateX(50%);"></p></div>';
                        } else {
                            echo '<div class ="premium-progressbar-multiple-label" style="left:'.$item['number'].'%;"><p class = "premium-progressbar-center-label" style="transform:translateX(-2%);">'.$item['text'].' <span class="premium-progressbar-percentage">'.$item['number'].'%</span></p></div>';
                        }
                    } else{
                        if($settings['premium_progressbar_select_label_icon'] === 'arrow') { 
                            echo '<div class ="premium-progressbar-multiple-label" style="left:'.$item['number'].'%;"><p class = "premium-progressbar-center-label" style="transform:translateX(-10%);">'.$item['text'].'</p><p class="premium-progressbar-arrow" style="left:'.$item['number'].'%; transform:translateX(50%);"></p></div>';
                        } elseif($settings['premium_progressbar_select_label_icon'] === 'line_pin'){
                           echo '<div class ="premium-progressbar-multiple-label" style="left:'.$item['number'].'%;"><p class = "premium-progressbar-center-label" style="transform:translateX(-2%);">'.$item['text'].'</p><p class="premium-progressbar-pin" style="left:'.$item['number'].'%; transform:translateX(50%);"></p></div>';
                        } else {
                            echo '<div class ="premium-progressbar-multiple-label" style="left:'.$item['number'].'%;"><p class = "premium-progressbar-center-label" style="transform:translateX(-2%);">'.$item['text'].'</p></div>';
                        }
                    }
                } else {
                    if($settings['premium_progress_bar_space_percentage_switcher'] === 'yes'){
                       if($settings['premium_progressbar_select_label_icon'] === 'arrow') { 
                            echo '<div class ="premium-progressbar-multiple-label" style="left:'.$item['number'].'%;"><p class = "premium-progressbar-center-label" style="transform:translateX(-82%);">'.$item['text'].' <span class="premium-progressbar-percentage">'.$item['number'].'%</span></p><p class="premium-progressbar-arrow" style="left:'.$item['number'].'%; transform:translateX(50%);"></p></div>';
                        } elseif($settings['premium_progressbar_select_label_icon'] === 'line_pin'){
                           echo '<div class ="premium-progressbar-multiple-label" style="left:'.$item['number'].'%;"><p class = "premium-progressbar-center-label" style="transform:translateX(-95%);">'.$item['text'].' <span class="premium-progressbar-percentage">'.$item['number'].'%</span></p><p class="premium-progressbar-pin" style="left:'.$item['number'].'%; transform:translateX(50%);"></p></div>';
                        } else {
                            echo '<div class ="premium-progressbar-multiple-label" style="left:'.$item['number'].'%;"><p class = "premium-progressbar-center-label" style="transform:translateX(-96%);">'.$item['text'].' <span class="premium-progressbar-percentage">'.$item['number'].'%</span></p></div>';
                        }
                    } else{
                        if($settings['premium_progressbar_select_label_icon'] === 'arrow') { 
                            echo '<div class ="premium-progressbar-multiple-label" style="left:'.$item['number'].'%;"><p class = "premium-progressbar-center-label" style="transform:translateX(-71%);">'.$item['text'].'</p><p class="premium-progressbar-arrow" style="left:'.$item['number'].'%; transform:translateX(50%);"></p></div>';
                        } elseif($settings['premium_progressbar_select_label_icon'] === 'line_pin'){
                           echo '<div class ="premium-progressbar-multiple-label" style="left:'.$item['number'].'%;"><p class = "premium-progressbar-center-label" style="transform:translateX(-97%);">'.$item['text'].'</p><p class="premium-progressbar-pin" style="left:'.$item['number'].'%; transform:translateX(50%);"></p></div>';
                        } else {
                            echo '<div class ="premium-progressbar-multiple-label" style="left:'.$item['number'].'%;"><p class = "premium-progressbar-center-label" style="transform:translateX(-96%);">'.$item['text'].'</p></div>';
                        }
                    }
                }

               } ?>
            </div>
        <?php endif; ?>

        <div class="clearfix"></div>
        <div <?php echo $this->get_render_attribute_string( 'wrap' ); ?>>
            <?php if( 'line' === $type ) : ?>
                <div class="premium-progressbar-bar" role="progressbar" aria-valuemin="0" aria-valuemax="100"></div>
            <?php elseif( 'circle' === $type ): ?>
                <div class="premium-progressbar-circle-base"></div>
                <div class="premium-progressbar-circle">
                    <div class="premium-progressbar-circle-left"></div>
                    <div class="premium-progressbar-circle-right"></div>
                </div>
                <div class="premium-progressbar-circle-content">
                    <?php if( !empty( $settings['icon_select']['value'] ) || ! empty( $settings['image_upload']['url'] ) || !empty( $settings['lottie_url'] )  ) : ?>
                        <?php if('icon' === $icon_type ):
                            Icons_Manager::render_icon( $settings['icon_select'], [ 'class'=> 'premium-progressbar-circle-icon', 'aria-hidden' => 'true' ] );
                        elseif( 'image' === $icon_type ) : ?>
                            <img class="premium-progressbar-circle-icon" src="<?php echo $settings['image_upload']['url']; ?>">
                        <?php else: ?>
                            <div <?php echo $this->get_render_attribute_string( 'progress_lottie' ); ?>></div>
                        <?php endif;?>
                    <?php endif; ?>
                <p class="premium-progressbar-left-label">
                    <span <?php echo $this->get_render_attribute_string('premium_progressbar_left_label'); ?>>
                        <?php echo $settings['premium_progressbar_left_label'];?>
                    </span>
                </p>
                <?php if( 'yes' === $settings['show_percentage_value'] ) : ?>
                    <p class="premium-progressbar-right-label">
                        <span <?php echo $this->get_render_attribute_string('premium_progressbar_right_label'); ?>>0%</span>
                    </p>
                <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <?php
    }
}