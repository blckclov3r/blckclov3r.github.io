<?php

/**
 * Premium Image Button.
 */
namespace PremiumAddons\Widgets;

// Elementor Classes.
use Elementor\Icons_Manager;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Scheme_Color;
use Elementor\Scheme_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;

// PremiumAddons Classes.
use PremiumAddons\Helper_Functions;
use PremiumAddons\Includes;

if ( ! defined( 'ABSPATH' ) ) exit; // If this file is called directly, abort.

/**
 * Class Premium_Image_Button
 */
class Premium_Image_Button extends Widget_Base {

    public function get_name() {
        return 'premium-addon-image-button';
    }

    public function get_title() {
		return sprintf( '%1$s %2$s', Helper_Functions::get_prefix(), __('Image Button', 'premium-addons-for-elementor') );
	}
    
    public function getTemplateInstance() {
		return $this->templateInstance = Includes\premium_Template_Tags::getInstance();
	}
    
    public function get_style_depends() {
        return [
            'premium-addons'
        ];
    }

    public function get_script_depends() {
        return [
            'lottie-js'
        ];
    }

    public function get_icon() {
        return 'pa-image-button';
    }

    public function get_categories() {
        return [ 'premium-elements' ];
    }
    
    public function get_custom_help_url() {
		return 'https://premiumaddons.com/support/';
	}

    /**
	 * Register Image  Button controls.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
    protected function _register_controls() {

        $this->start_controls_section('premium_image_button_general_section',
                [
                    'label'         => __('Button', 'premium-addons-for-elementor'),
                    ]
                );
        
        $this->add_control('premium_image_button_text',
                [
                    'label'         => __('Text', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::TEXT,
                    'dynamic'       => [ 'active' => true ],
                    'default'       => __('Click Me','premium-addons-for-elementor'),
                    'label_block'   => true,
                ]
                );
        
        $this->add_control('premium_image_button_link_selection', 
                [
                    'label'         => __('Link Type', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::SELECT,
                    'options'       => [
                        'url'   => __('URL', 'premium-addons-for-elementor'),
                        'link'  => __('Existing Page', 'premium-addons-for-elementor'),
                    ],
                    'default'       => 'url',
                    'label_block'   => true,
                ]
                );
        
        $this->add_control('premium_image_button_link',
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
                        'premium_image_button_link_selection' => 'url'
                    ]
                ]
                );
        
        $this->add_control('premium_image_button_existing_link',
                [
                    'label'         => __('Existing Page', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::SELECT2,
                    'options'       => $this->getTemplateInstance()->get_all_post(),
                    'condition'     => [
                        'premium_image_button_link_selection'     => 'link',
                    ],
                    'multiple'      => false,
                    'separator'     => 'after',
                    'label_block'   => true,
                ]
                );
        
        $this->add_control('premium_image_button_hover_effect', 
                [
                    'label'         => __('Hover Effect', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::SELECT,
                    'default'       => 'none',
                    'options'       => [
                        'none'          => __('None','premium-addons-for-elementor'),
                        'style1'        => __('Background Slide','premium-addons-for-elementor'),
                        'style3'        => __('Diagonal Slide','premium-addons-for-elementor'),
                        'style4'        => __('Icon Slide','premium-addons-for-elementor'),
                        'style5'        => __('Overlap','premium-addons-for-elementor'),
                        ],
                    'label_block'   => true,
                    ]
                );
        
        $this->add_control('premium_image_button_style1_dir', 
            [
                'label'         => __('Slide Direction', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SELECT,
                'default'       => 'bottom',
                'options'       => [
                    'bottom'       => __('Top to Bottom','premium-addons-for-elementor'),
                    'top'          => __('Bottom to Top','premium-addons-for-elementor'),
                    'left'         => __('Right to Left','premium-addons-for-elementor'),
                    'right'        => __('Left to Right','premium-addons-for-elementor'),
                ],
                'label_block'   => true,
                'condition'     => [
                    'premium_image_button_hover_effect' => 'style1',
                ],
            ]
        );
        
        $this->add_control('premium_image_button_style3_dir', 
                [
                    'label'         => __('Slide Direction', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::SELECT,
                    'default'       => 'bottom',
                    'options'       => [
                        'top'          => __('Bottom Left to Top Right','premium-addons-for-elementor'),
                        'bottom'       => __('Top Right to Bottom Left','premium-addons-for-elementor'),
                        'left'         => __('Top Left to Bottom Right','premium-addons-for-elementor'),
                        'right'        => __('Bottom Right to Top Left','premium-addons-for-elementor'),
                        ],
                    'condition'     => [
                        'premium_image_button_hover_effect' => 'style3',
                        ],
                    'label_block'   => true,
                    ]
                );

        $this->add_control('premium_image_button_style4_dir', 
            [
                'label'         => __('Slide Direction', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SELECT,
                'default'       => 'bottom',
                'options'       => [
                    'top'          => __('Bottom to Top','premium-addons-for-elementor'),
                    'bottom'       => __('Top to Bottom','premium-addons-for-elementor'),
                    'left'         => __('Left to Right','premium-addons-for-elementor'),
                    'right'        => __('Right to Left','premium-addons-for-elementor'),
                ],
                'label_block'   => true,
                'condition'     => [
                    'premium_image_button_hover_effect' => 'style4',
                ],
            ]
        );
    
        $this->add_control('premium_image_button_style5_dir', 
            [
                'label'         => __('Overlap Direction', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SELECT,
                'default'       => 'horizontal',
                'options'       => [
                    'horizontal'          => __('Horizontal','premium-addons-for-elementor'),
                    'vertical'       => __('Vertical','premium-addons-for-elementor'),
                ],
                'label_block'   => true,
                'condition'     => [
                    'premium_image_button_hover_effect' => 'style5',
                ],
            ]
        );
        
        $this->add_control('premium_image_button_icon_switcher',
            [
                'label'         => __('Icon', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SWITCHER,
                'description'   => __('Enable or disable button icon','premium-addons-for-elementor'),
                'condition'     => [
                    'premium_image_button_hover_effect!'  => 'style4'
                ],
            ]
        );

        $this->add_control('icon_type', 
            [
                'label'         => __('Icon Type', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SELECT,
                'options'       => [
                    'icon'          => __('Font Awesome', 'premium-addons-for-elementor'),
                    'animation'     => __('Lottie Animation', 'premium-addons-for-elementor'),
                ],
                'default'       => 'icon',
                'label_block'   => true,
                'condition'     => [
                    'premium_image_button_hover_effect!'  => 'style4',
                    'premium_image_button_icon_switcher' => 'yes',
                ],
            ]
        );

        $this->add_control('premium_image_button_icon_selection_updated',
            [
                'label'         => __('Icon', 'premium-addons-for-elementor'),
                'type'              => Controls_Manager::ICONS,
                'fa4compatibility'  => 'premium_image_button_icon_selection',
                'default' => [
                    'value'     => 'fas fa-bars',
                    'library'   => 'fa-solid',
                ],
                'condition'     => [
                    'premium_image_button_icon_switcher'    => 'yes',
                    'premium_image_button_hover_effect!'    =>  'style4',
                    'icon_type'                             => 'icon'
                ],
                'label_block'   => true,
            ]
        );

        $this->add_control('lottie_url', 
            [
                'label'             => __( 'Animation JSON URL', 'premium-addons-for-elementor' ),
                'type'              => Controls_Manager::TEXT,
                'dynamic'           => [ 'active' => true ],
                'description'       => 'Get JSON code URL from <a href="https://lottiefiles.com/" target="_blank">here</a>',
                'label_block'       => true,
                'condition'         => [
                    'premium_image_button_icon_switcher'  => 'yes',
                    'premium_image_button_hover_effect!'  => 'style4',
                    'icon_type'                     => 'animation'
                ],
            ]
        );

        $this->add_control('lottie_loop',
            [
                'label'         => __('Loop','premium-addons-for-elementor'),
                'type'          => Controls_Manager::SWITCHER,
                'return_value'  => 'true',
                'default'       => 'true',
                'condition'         => [
                    'premium_image_button_icon_switcher'  => 'yes',
                    'premium_image_button_hover_effect!'  => 'style4',
                    'icon_type'                     => 'animation'
                ],
            ]
        );

        $this->add_control('lottie_reverse',
            [
                'label'         => __('Reverse','premium-addons-for-elementor'),
                'type'          => Controls_Manager::SWITCHER,
                'return_value'  => 'true',
                'condition'         => [
                    'premium_image_button_icon_switcher'  => 'yes',
                    'premium_image_button_hover_effect!'  => 'style4',
                    'icon_type'                         => 'animation'
                ],
            ]
        );

        $this->add_control('slide_icon_type', 
            [
                'label'         => __('Icon Type', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SELECT,
                'options'       => [
                    'icon'          => __('Font Awesome', 'premium-addons-for-elementor'),
                    'animation'     => __('Lottie Animation', 'premium-addons-for-elementor'),
                ],
                'default'       => 'icon',
                'label_block'   => true,
                'condition'     => [
                    'premium_image_button_hover_effect'  => 'style4'
                ],
            ]
        );
        
        $this->add_control('premium_image_button_style4_icon_selection_updated',
            [
                'label'         => __('Icon', 'premium-addons-for-elementor'),
                'type'              => Controls_Manager::ICONS,
                'fa4compatibility'  => 'premium_image_button_style4_icon_selection',
                'default' => [
                    'value'     => 'fas fa-bars',
                    'library'   => 'fa-solid',
                ],
                'label_block'   => true,
                'condition'     => [
                    'slide_icon_type'   => 'icon',
                    'premium_image_button_hover_effect'  => 'style4'
                ],
            ]
        );

        $this->add_control('slide_lottie_url', 
            [
                'label'             => __( 'Animation JSON URL', 'premium-addons-for-elementor' ),
                'type'              => Controls_Manager::TEXT,
                'dynamic'           => [ 'active' => true ],
                'description'       => 'Get JSON code URL from <a href="https://lottiefiles.com/" target="_blank">here</a>',
                'label_block'       => true,
                'condition'     => [
                    'slide_icon_type'   => 'animation',
                    'premium_image_button_hover_effect'  => 'style4'
                ],
            ]
        );

        $this->add_control('slide_lottie_loop',
            [
                'label'         => __('Loop','premium-addons-for-elementor'),
                'type'          => Controls_Manager::SWITCHER,
                'return_value'  => 'true',
                'default'       => 'true',
                'condition'     => [
                    'slide_icon_type'   => 'animation',
                    'premium_image_button_hover_effect'  => 'style4'
                ]
            ]
        );

        $this->add_control('slide_lottie_reverse',
            [
                'label'         => __('Reverse','premium-addons-for-elementor'),
                'type'          => Controls_Manager::SWITCHER,
                'return_value'  => 'true',
                'condition'     => [
                    'slide_icon_type'   => 'animation',
                    'premium_image_button_hover_effect'  => 'style4'
                ]
            ]
        );
        
        $this->add_control('premium_image_button_icon_position', 
            [
                'label'         => __('Icon Position', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SELECT,
                'default'       => 'before',
                'options'       => [
                    'before'        => __('Before','premium-addons-for-elementor'),
                    'after'         => __('After','premium-addons-for-elementor'),
                ],
                'label_block'   => true,
                'condition'     => [
                    'premium_image_button_icon_switcher' => 'yes',
                    'premium_image_button_hover_effect!'  =>  'style4'
                ],
            ]
        );
        
        $this->add_responsive_control('premium_image_button_icon_before_size',
            [
                'label'         => __('Icon Size', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SLIDER,
                'condition'     => [
                    'premium_image_button_icon_switcher' => 'yes',
                    'premium_image_button_hover_effect!'  => 'style4'
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-image-button-text-icon-wrapper i' => 'font-size: {{SIZE}}px',
                    '{{WRAPPER}} .premium-image-button-text-icon-wrapper svg' => 'width: {{SIZE}}px; height: {{SIZE}}px',
                ]
            ]
        );
        
        $this->add_responsive_control('premium_image_button_icon_style4_size',
            [
                'label'         => __('Icon Size', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SLIDER,
                'condition'     => [
                    'premium_image_button_hover_effect'  => 'style4'
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-image-button-style4-icon-wrapper i' => 'font-size: {{SIZE}}px',
                    '{{WRAPPER}} .premium-image-button-style4-icon-wrapper svg' => 'width: {{SIZE}}px; height: {{SIZE}}px',
                ]
            ]
        );
        
        $this->add_responsive_control('premium_image_button_icon_before_spacing',
            [
                'label'         => __('Icon Spacing', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SLIDER,
                'default'       => [
                    'size'  => 15
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-image-button-text-icon-wrapper i, {{WRAPPER}} .premium-image-button-text-icon-wrapper svg' => 'margin-right: {{SIZE}}px',
                ],
                'separator'     => 'after',
                'condition'     => [
                    'premium_image_button_icon_switcher' => 'yes',
                    'premium_image_button_icon_position' => 'before',
                    'premium_image_button_hover_effect!' => 'style4'
                ],
            ]
        );
        
        $this->add_responsive_control('premium_image_button_icon_after_spacing',
            [
                'label'         => __('Icon Spacing', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SLIDER,
                'default'       => [
                    'size'  => 15
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-image-button-text-icon-wrapper i, {{WRAPPER}} .premium-image-button-text-icon-wrapper svg' => 'margin-left: {{SIZE}}px',
                ],
                'separator'     => 'after',
                'condition'     => [
                    'premium_image_button_icon_switcher' => 'yes',
                    'premium_image_button_icon_position' => 'after',
                    'premium_image_button_hover_effect!' => 'style4'
                ],
            ]
        );
        
        $this->add_control('premium_image_button_size', 
            [
                'label'         => __('Size', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SELECT,
                'default'       => 'lg',
                'options'       => [
                    'sm'            => __('Small','premium-addons-for-elementor'),
                    'md'            => __('Medium','premium-addons-for-elementor'),
                    'lg'            => __('Large','premium-addons-for-elementor'),
                    'block'         => __('Block','premium-addons-for-elementor'),
                ],
                'label_block'   => true,
                'separator'     => 'before',
            ]
        );
        
        $this->add_responsive_control('premium_image_button_align',
			[
				'label'             => __( 'Alignment', 'premium-addons-for-elementor' ),
				'type'              => Controls_Manager::CHOOSE,
				'options'           => [
					'left'    => [
						'title' => __( 'Left', 'premium-addons-for-elementor' ),
						'icon'  => 'fa fa-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'premium-addons-for-elementor' ),
						'icon'  => 'fa fa-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'premium-addons-for-elementor' ),
						'icon'  => 'fa fa-align-right',
					],
				],
                'selectors'         => [
                    '{{WRAPPER}} .premium-image-button-container' => 'text-align: {{VALUE}}',
                ],
				'default' => 'center',
			]
		);
        
        $this->add_control('premium_image_button_event_switcher', 
            [
                'label'         => __('onclick Event', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SWITCHER,
                'separator'     => 'before',
            ]
        );
        
        $this->add_control('premium_image_button_event_function', 
            [
                'label'         => __('Example: myFunction();', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::TEXTAREA,
                'dynamic'       => [ 'active' => true ],
                'condition'     => [
                    'premium_image_button_event_switcher' => 'yes',
                ],
            ]
        );
        
        $this->end_controls_section();

        $this->start_controls_section('premium_image_button_style_section',
            [
                'label'             => __('Button', 'premium-addons-for-elementor'),
                'tab'               => Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'              => 'premium_image_button_typo',
                'scheme'            => Scheme_Typography::TYPOGRAPHY_1,
                'selector'          => '{{WRAPPER}} .premium-image-button',
            ]
            );
        
        $this->start_controls_tabs('premium_image_button_style_tabs');
        
        $this->start_controls_tab('premium_image_button_style_normal',
            [
                'label'             => __('Normal', 'premium-addons-for-elementor'),
            ]
            );

        $this->add_control('premium_image_button_text_color_normal',
            [
                'label'             => __('Text Color', 'premium-addons-for-elementor'),
                'type'              => Controls_Manager::COLOR,
                'scheme'            => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_2,
                ],
                'selectors'         => [
                    '{{WRAPPER}} .premium-image-button .premium-image-button-text-icon-wrapper span'   => 'color: {{VALUE}};',
                ]
            ]);
        
        $this->add_control('premium_image_button_icon_color_normal',
            [
                'label'             => __('Icon Color', 'premium-addons-for-elementor'),
                'type'              => Controls_Manager::COLOR,
                'scheme'            => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_2,
                ],
                'selectors'         => [
                    '{{WRAPPER}} .premium-image-button-text-icon-wrapper i'   => 'color: {{VALUE}};',
                ],
                'condition'         => [
                    'premium_image_button_icon_switcher'    => 'yes',
                    'icon_type'                             => 'icon',
                    'premium_image_button_hover_effect!'    => 'style4'
                ]
            ]);
        
        
        $this->add_group_control(
            Group_Control_Background::get_type(),
                [
                    'name'              => 'premium_image_button_background',
                    'types'             => [ 'classic' , 'gradient' ],
                    'selector'          => '{{WRAPPER}} .premium-image-button',
                    ]
                );
        
        $this->add_group_control(
            Group_Control_Border::get_type(), 
                [
                    'name'          => 'premium_image_button_border_normal',
                    'selector'      => '{{WRAPPER}} .premium-image-button',
                ]
                );
        
        $this->add_control('premium_image_button_border_radius_normal',
                [
                    'label'         => __('Border Radius', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::SLIDER,
                    'size_units'    => ['px', '%' ,'em'],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-image-button' => 'border-radius: {{SIZE}}{{UNIT}};'
                    ]
                ]
                );
        
        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'label'         => __('Icon Shadow','premium-addons-for-elementor'),
                'name'          => 'premium_image_button_icon_shadow_normal',
                'selector'      => '{{WRAPPER}} .premium-image-button-text-icon-wrapper i',
                'condition'         => [
                    'premium_image_button_icon_switcher'    => 'yes',
                    'icon_type'                             => 'icon',
                    'premium_image_button_hover_effect!'    => 'style4'
                ]
            ]
        );
        
        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
                [
                    'label'         => __('Text Shadow','premium-addons-for-elementor'),
                    'name'          => 'premium_image_button_text_shadow_normal',
                    'selector'      => '{{WRAPPER}} .premium-image-button-text-icon-wrapper span',
                    ]
                );
        
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
                [
                    'label'         => __('Button Shadow','premium-addons-for-elementor'),
                    'name'          => 'premium_image_button_box_shadow_normal',
                    'selector'      => '{{WRAPPER}} .premium-image-button',
                ]
                );
        
        $this->add_responsive_control('premium_image_button_margin_normal',
                [
                    'label'         => __('Margin', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::DIMENSIONS,
                    'size_units'    => ['px', 'em', '%'],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-image-button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                    ]
                ]);
        
        $this->add_responsive_control('premium_image_button_padding_normal',
                [
                    'label'         => __('Padding', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::DIMENSIONS,
                    'size_units'    => ['px', 'em', '%'],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-image-button, {{WRAPPER}} .premium-image-button-effect-container' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                    ]
                ]);
        
        $this->end_controls_tab();
        
        $this->start_controls_tab('premium_image_button_style_hover',
            [
                'label'             => __('Hover', 'premium-addons-for-elementor'),
            ]
            );

        $this->add_control('premium_image_button_text_color_hover',
            [
                'label'             => __('Text Color', 'premium-addons-for-elementor'),
                'type'              => Controls_Manager::COLOR,
                'scheme'            => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_1,
                ],
                'selectors'         => [
                    '{{WRAPPER}} .premium-image-button:hover .premium-image-button-text-icon-wrapper span'   => 'color: {{VALUE}};',
                ],
                'condition'         => [
                    'premium_image_button_hover_effect!'   => 'style4'
                ]
            ]);
        
        $this->add_control('premium_image_button_icon_color_hover',
            [
                'label'             => __('Icon Color', 'premium-addons-for-elementor'),
                'type'              => Controls_Manager::COLOR,
                'scheme'            => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_1,
                ],
                'selectors'         => [
                    '{{WRAPPER}} .premium-image-button:hover .premium-image-button-text-icon-wrapper i'   => 'color: {{VALUE}};',
                ],
                'condition'         => [
                    'premium_image_button_icon_switcher'    => 'yes',
                    'icon_type'                             => 'icon',
                    'premium_image_button_hover_effect!'    => 'style4'
                ]
            ]
        );

        $this->add_control('premium_image_button_style4_icon_color',
            [
                'label'             => __('Icon Color', 'premium-addons-for-elementor'),
                'type'              => Controls_Manager::COLOR,
                'scheme'            => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_1,
                ],
                'selectors'         => [
                    '{{WRAPPER}} .premium-image-button:hover .premium-image-button-style4-icon-wrapper'   => 'color: {{VALUE}};',
                ],
                'condition'         => [
                    'premium_image_button_hover_effect'  => 'style4',
                    'slide_icon_type'   => 'icon'
                ]
            ]
        );

        $this->add_control('premium_image_button_diagonal_overlay_color',
            [
                'label'             => __('Overlay Color', 'premium-addons-for-elementor'),
                'type'              => Controls_Manager::COLOR,
                'scheme'            => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_1,
                ],
                'selectors'         => [
                    '{{WRAPPER}} .premium-image-button-diagonal-effect-top:before, {{WRAPPER}} .premium-image-button-diagonal-effect-bottom:before, {{WRAPPER}} .premium-image-button-diagonal-effect-left:before, {{WRAPPER}} .premium-image-button-diagonal-effect-right:before'   => 'background-color: {{VALUE}};',
                ],
                'condition'         => [
                    'premium_image_button_hover_effect'  => 'style3'
                ]
            ]
        );

        $this->add_control('premium_image_button_overlap_overlay_color',
            [
                'label'             => __('Overlay Color', 'premium-addons-for-elementor'),
                'type'              => Controls_Manager::COLOR,
                'scheme'            => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_1,
                ],
                'selectors'         => [
                    '{{WRAPPER}} .premium-image-button-overlap-effect-horizontal:before, {{WRAPPER}} .premium-image-button-overlap-effect-vertical:before'   => 'background-color: {{VALUE}};',
                ],
                'condition'         => [
                    'premium_image_button_hover_effect'  => 'style5'
                ]
            ]
        );
            
        $this->add_group_control(
            Group_Control_Background::get_type(),
                [
                    'name'              => 'premium_image_button_background_hover',
                    'types'             => [ 'classic' , 'gradient' ],
                    'selector'          => '{{WRAPPER}} .premium-image-button-none:hover, {{WRAPPER}} .premium-image-button-style4-icon-wrapper,{{WRAPPER}} .premium-image-button-style1-top:before,{{WRAPPER}} .premium-image-button-style1-bottom:before,{{WRAPPER}} .premium-image-button-style1-left:before,{{WRAPPER}} .premium-image-button-style1-right:before,{{WRAPPER}} .premium-image-button-diagonal-effect-right:hover, {{WRAPPER}} .premium-image-button-diagonal-effect-top:hover, {{WRAPPER}} .premium-image-button-diagonal-effect-left:hover, {{WRAPPER}} .premium-image-button-diagonal-effect-bottom:hover,{{WRAPPER}} .premium-image-button-overlap-effect-horizontal:hover, {{WRAPPER}} .premium-image-button-overlap-effect-vertical:hover',
                    ]
                );
        
        $this->add_control('premium_image_button_overlay_color',
                [
                    'label'         => __('Overlay Color', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::COLOR,
                    'scheme'        => [
                        'type'  => Scheme_Color::get_type(),
                        'value' => Scheme_Color::COLOR_1,
                    ],
                    'condition'     => [
                        'premium_image_button_overlay_switcher' => 'yes'
                    ],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-image-button-squares-effect:before, {{WRAPPER}} .premium-image-button-squares-effect:after,{{WRAPPER}} .premium-image-button-squares-square-container:before, {{WRAPPER}} .premium-image-button-squares-square-container:after' => 'background-color: {{VALUE}};',
                        ]
                    ]
                );
        
        $this->add_group_control(
            Group_Control_Border::get_type(), 
                [
                    'name'          => 'premium_image_button_border_hover',
                    'selector'      => '{{WRAPPER}} .premium-image-button:hover',
                ]
                );
        
        $this->add_control('premium_image_button_border_radius_hover',
                [
                    'label'         => __('Border Radius', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::SLIDER,
                    'size_units'    => ['px', '%' ,'em'],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-image-button:hover' => 'border-radius: {{SIZE}}{{UNIT}};'
                    ]
                ]
                );
        
        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'label'         => __('Icon Shadow','premium-addons-for-elementor'),
                'name'          => 'premium_image_button_icon_shadow_hover',
                'selector'      => '{{WRAPPER}} .premium-image-button:hover .premium-image-button-text-icon-wrapper i',
                'condition'         => [
                    'premium_image_button_icon_switcher'    => 'yes',
                    'icon_type'                             => 'icon',
                    'premium_image_button_hover_effect!'    => 'style4'
                ]
            ]
        );
        
        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'label'         => __('Icon Shadow','premium-addons-for-elementor'),
                'name'          => 'premium_image_button_style4_icon_shadow_hover',
                'selector'      => '{{WRAPPER}} .premium-image-button:hover .premium-image-button-style4-icon-wrapper i',
                'condition'         => [
                    'premium_image_button_hover_effect'     => 'style4',
                    'slide_icon_type'   => 'icon'
                ]
            ]
        );
    
        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'label'         => __('Text Shadow','premium-addons-for-elementor'),
                'name'          => 'premium_image_button_text_shadow_hover',
                'selector'      => '{{WRAPPER}}  .premium-image-button:hover .premium-image-button-text-icon-wrapper span',
                'condition'         => [
                    'premium_image_button_hover_effect!'   => 'style4'
                ]
            ]
        );
        
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
                [
                    'label'         => __('Button Shadow','premium-addons-for-elementor'),
                    'name'          => 'premium_image_button_box_shadow_hover',
                    'selector'      => '{{WRAPPER}} .premium-image-button:hover',
                ]
                );
        
        $this->add_responsive_control('premium_image_button_margin_hover',
                [
                    'label'         => __('Margin', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::DIMENSIONS,
                    'size_units'    => ['px', 'em', '%'],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-image-button:hover' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                    ]
                ]);
        
        $this->add_responsive_control('premium_image_button_padding_hover',
                [
                    'label'         => __('Padding', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::DIMENSIONS,
                    'size_units'    => ['px', 'em', '%'],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-image-button:hover' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                    ]
                ]);
        
        $this->end_controls_tab();
        
        $this->end_controls_tabs();

        $this->end_controls_section();
    }

    /**
	 * Render Image Button widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
    protected function render() {
        
        $settings = $this->get_settings_for_display();
        
        $this->add_inline_editing_attributes( 'premium_image_button_text' );
        
        if($settings['premium_image_button_link_selection'] == 'url'){
            $image_link = $settings['premium_image_button_link']['url'];
        } else {
            $image_link = get_permalink($settings['premium_image_button_existing_link']);
        }
        
        $button_text = $settings['premium_image_button_text'];
        
        $button_size = 'premium-image-button-' . $settings['premium_image_button_size'];
        
        $image_event = $settings['premium_image_button_event_function'];
        
        if ( ! empty ( $settings['premium_image_button_icon_selection'] ) ) {
            $this->add_render_attribute( 'icon', 'class', $settings['premium_image_button_icon_selection'] );
            $this->add_render_attribute( 'icon', 'aria-hidden', 'true' );
        }
        
        $icon_type = $settings['icon_type'];

        if( 'icon' === $icon_type ) {
            $migrated = isset( $settings['__fa4_migrated']['premium_image_button_icon_selection_updated'] );
            $is_new = empty( $settings['premium_image_button_icon_selection'] ) && Icons_Manager::is_migration_allowed();
        } else {
            $this->add_render_attribute( 'lottie', [
                    'class' => 'premium-lottie-animation',
                    'data-lottie-url' => $settings['lottie_url'],
                    'data-lottie-loop' => $settings['lottie_loop'],
                    'data-lottie-reverse' => $settings['lottie_reverse'],
                ]
            );
        }
        
        if ($settings['premium_image_button_hover_effect'] == 'none'){
            $style_dir = 'premium-image-button-none';
        }    elseif($settings['premium_image_button_hover_effect'] == 'style1'){
            $style_dir = 'premium-image-button-style1-' . $settings['premium_image_button_style1_dir'];
        }   elseif($settings['premium_image_button_hover_effect'] == 'style3'){
            $style_dir = 'premium-image-button-diagonal-effect-' . $settings['premium_image_button_style3_dir'];
        }   elseif($settings['premium_image_button_hover_effect'] == 'style4'){
            $style_dir = 'premium-image-button-style4-' . $settings['premium_image_button_style4_dir'];
            
            $slide_icon_type = $settings['slide_icon_type'];

            if( 'icon' === $slide_icon_type ) {
                
                if ( ! empty ( $settings['premium_image_button_style4_icon_selection'] ) ) {
                    $this->add_render_attribute( 'slide_icon', 'class', $settings['premium_image_button_style4_icon_selection'] );
                    $this->add_render_attribute( 'slide_icon', 'aria-hidden', 'true' );
                }
                
                $slide_migrated = isset( $settings['__fa4_migrated']['premium_image_button_style4_icon_selection_updated'] );
                $slide_is_new = empty( $settings['premium_image_button_style4_icon_selection'] ) && Icons_Manager::is_migration_allowed();

            } else {

                $this->add_render_attribute( 'slide_lottie', [
                        'class' => 'premium-lottie-animation',
                        'data-lottie-url' => $settings['slide_lottie_url'],
                        'data-lottie-loop' => $settings['slide_lottie_loop'],
                        'data-lottie-reverse' => $settings['slide_lottie_reverse'],
                    ]
                );

            }
            
            
        }   elseif($settings['premium_image_button_hover_effect'] == 'style5'){
            $style_dir = 'premium-image-button-overlap-effect-' . $settings['premium_image_button_style5_dir'];
        }
        
        $this->add_render_attribute( 'button', 'class', array(
            'premium-image-button',
            $button_size,
            $style_dir
        ));
        
        if( ! empty( $image_link ) ) {
        
            $this->add_render_attribute( 'button', 'href', $image_link );
            
            if( ! empty( $settings['premium_image_button_link']['is_external'] ) )
                $this->add_render_attribute( 'button', 'target', '_blank' );
            
            if( ! empty( $settings['premium_image_button_link']['nofollow'] ) )
                $this->add_render_attribute( 'button', 'rel', 'nofollow' );
        }
        
        if( 'yes' === $settings['premium_image_button_event_switcher'] && ! empty( $image_event ) ) {
            $this->add_render_attribute( 'button', 'onclick', $image_event );
        }

    ?>
    <div class="premium-image-button-container">
        <a <?php echo $this->get_render_attribute_string( 'button' ); ?>>
            <div class="premium-image-button-text-icon-wrapper">
            <?php if('yes' === $settings['premium_image_button_icon_switcher'] ) : ?>
                <?php if( $settings['premium_image_button_hover_effect'] !== 'style4' && $settings['premium_image_button_icon_position'] === 'before' ) : ?>
                    <?php if( 'icon' === $icon_type ) : ?>
                        <?php if ( $is_new || $migrated ) :
                            Icons_Manager::render_icon( $settings['premium_image_button_icon_selection_updated'], [ 'aria-hidden' => 'true' ] );
                        else: ?>
                            <i <?php echo $this->get_render_attribute_string( 'icon' ); ?>></i>
                        <?php endif; ?>
                    <?php else: ?>
                        <div <?php echo $this->get_render_attribute_string( 'lottie' ); ?>></div>
                    <?php endif; ?>
                <?php endif; ?>
            <?php endif; ?>
            
            <span <?php echo $this->get_render_attribute_string( 'premium_image_button_text' ); ?>>
                <?php echo $button_text; ?>
            </span>
            <?php if('yes' === $settings['premium_image_button_icon_switcher'] ) : ?>
                <?php if( $settings['premium_image_button_hover_effect'] !== 'style4' &&  $settings['premium_image_button_icon_position'] == 'after' ) : ?>
                    <?php if( 'icon' === $icon_type ) : ?>
                    <?php if ( $is_new || $migrated ) :
                        Icons_Manager::render_icon( $settings['premium_image_button_icon_selection_updated'], [ 'aria-hidden' => 'true' ] );
                    else: ?>
                        <i <?php echo $this->get_render_attribute_string( 'icon' ); ?>></i>
                    <?php endif; ?>
                    <?php else: ?>
                        <div <?php echo $this->get_render_attribute_string( 'lottie' ); ?>></div>
                    <?php endif; ?>
                <?php endif; ?>
            <?php endif; ?>
        </div>
        <?php if( $settings['premium_image_button_hover_effect'] == 'style4') : ?>
            <div class="premium-image-button-style4-icon-wrapper <?php echo esc_attr( $settings['premium_image_button_style4_dir'] ); ?>">
                <?php if( 'icon' === $slide_icon_type ) : ?>
                    <?php if ( $slide_is_new || $slide_migrated ) :
                        Icons_Manager::render_icon( $settings['premium_image_button_style4_icon_selection_updated'], [ 'aria-hidden' => 'true' ] );
                    else: ?>
                        <i <?php echo $this->get_render_attribute_string( 'slide_icon' ); ?>></i>
                    <?php endif; ?>
                <?php else: ?>
                    <div <?php echo $this->get_render_attribute_string( 'slide_lottie' ); ?>></div>
                <?php endif;?>
            </div>
        <?php endif; ?>
        </a>
    </div>
    
    <?php
    }
    
    /**
	 * Render Image Button widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
    protected function content_template() {
        ?>
        <#
        
        view.addInlineEditingAttributes( 'premium_image_button_text' );
        
        var buttonText = settings.premium_image_button_text,
            buttonUrl,
            styleDir,
            slideIcon,
            buttonSize = 'premium-image-button-' + settings.premium_image_button_size,
            buttonEvent = settings.premium_image_button_event_function,
            buttonIcon = settings.premium_image_button_icon_selection;
        
        if( 'url' == settings.premium_image_button_link_selection ) {
            buttonUrl = settings.premium_image_button_link.url;
        } else {
            buttonUrl = settings.premium_image_button_existing_link;
        }
        
        if ( 'none' == settings.premium_image_button_hover_effect ) {
            styleDir = 'premium-button-none';
        } else if( 'style1' == settings.premium_image_button_hover_effect ) {
            styleDir = 'premium-image-button-style1-' + settings.premium_image_button_style1_dir;
        } else if ( 'style3' == settings.premium_image_button_hover_effect ) {
            styleDir = 'premium-image-button-diagonal-effect-' + settings.premium_image_button_style3_dir;
        } else if ( 'style4' == settings.premium_image_button_hover_effect ) {
            styleDir = 'premium-image-button-style4-' + settings.premium_image_button_style4_dir;

            var slideIconType = settings.slide_icon_type;

            if( 'icon' === slideIconType ) {
                slideIcon = settings.premium_image_button_style4_icon_selection;
            
                var slideIconHTML = elementor.helpers.renderIcon( view, settings.premium_image_button_style4_icon_selection_updated, { 'aria-hidden': true }, 'i' , 'object' ),
                    slideMigrated = elementor.helpers.isIconMigrated( settings, 'premium_image_button_style4_icon_selection_updated' );

            } else {

                view.addRenderAttribute( 'slide_lottie', {
                    'class': 'premium-lottie-animation',
                    'data-lottie-url': settings.slide_lottie_url,
                    'data-lottie-loop': settings.slide_lottie_loop,
                    'data-lottie-reverse': settings.slide_lottie_reverse
                });
            
            }
            
        } else if ( 'style5' == settings.premium_image_button_hover_effect ){
            styleDir = 'premium-image-button-overlap-effect-' + settings.premium_image_button_style5_dir;
        }
        
        var iconType = settings.icon_type;

        if( 'icon' === iconType ) {
            var iconHTML = elementor.helpers.renderIcon( view, settings.premium_image_button_icon_selection_updated, { 'aria-hidden': true }, 'i' , 'object' ),
                migrated = elementor.helpers.isIconMigrated( settings, 'premium_image_button_icon_selection_updated' );
        } else {

            view.addRenderAttribute( 'slide_lottie', {
                'class': 'premium-lottie-animation',
                'data-lottie-url': settings.lottie_url,
                'data-lottie-loop': settings.lottie_loop,
                'data-lottie-reverse': settings.lottie_reverse
            });
            
        }
        
        #>
        
        <div class="premium-image-button-container">
            <a class="premium-image-button  {{ buttonSize }} {{ styleDir }}" href="{{ buttonUrl }}" onclick="{{ buttonEvent }}">
                <div class="premium-image-button-text-icon-wrapper">
                    <# if ('yes' === settings.premium_image_button_icon_switcher) { #>
                        <# if( 'before' === settings.premium_image_button_icon_position &&  'style4' !== settings.premium_image_button_hover_effect ) { #>
                            <# if( 'icon' === iconType ) {
                                if ( iconHTML && iconHTML.rendered && ( ! buttonIcon || migrated ) ) { #>
                                    {{{ iconHTML.value }}}
                                <# } else { #>
                                    <i class="{{ buttonIcon }}" aria-hidden="true"></i>
                                <# } #>
                            <# } else { #>
                                <div {{{ view.getRenderAttributeString('lottie') }}}></div>
                            <# } #>
                        <# } #>
                    <# } #>
                    
                    <span {{{ view.getRenderAttributeString('premium_image_button_text') }}}>{{{ buttonText }}}</span>
                    <# if ('yes' === settings.premium_image_button_icon_switcher) { #>
                        <# if( 'after' === settings.premium_image_button_icon_position && 'style4' !== settings.premium_image_button_hover_effect ) { #>
                            <# if( 'icon' === iconType ) {
                                if ( iconHTML && iconHTML.rendered && ( ! buttonIcon || migrated ) ) { #>
                                    {{{ iconHTML.value }}}
                                <# } else { #>
                                    <i class="{{ buttonIcon }}" aria-hidden="true"></i>
                                <# } #>
                            <# } else { #>
                                <div {{{ view.getRenderAttributeString('lottie') }}}></div>
                            <# } #>
                        <# } #>
                    <# } #>
                </div>
                <# if( 'style4' == settings.premium_image_button_hover_effect ) { #>
                    <div class="premium-image-button-style4-icon-wrapper {{ settings.premium_image_button_style4_dir }}">
                        <# if ( 'icon' === slideIconType ) { #>
                            <# if ( slideIconHTML && slideIconHTML.rendered && ( ! slideIcon || slideMigrated ) ) { #>
                                {{{ slideIconHTML.value }}}
                            <# } else { #>
                                <i class="{{ slideIcon }}" aria-hidden="true"></i>
                            <# } #>    
                        <# } else { #>
                            <div {{{ view.getRenderAttributeString('slide_lottie') }}}></div>
                        <# } #>
                    </div>
                <# } #>
            </a>
        </div>
        
        <?php
    }
}