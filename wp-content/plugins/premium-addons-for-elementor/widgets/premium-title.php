<?php

/**
 * Premium Title.
 */
namespace PremiumAddons\Widgets;

// Elementor Classes.
use Elementor\Widget_Base;
use Elementor\Utils;
use Elementor\Icons_Manager;
use Elementor\Controls_Manager;
use Elementor\Control_Media;
use Elementor\Scheme_Color;
use Elementor\Scheme_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Background;

// PremiumAddons Classes.
use PremiumAddons\Helper_Functions;
use PremiumAddons\Includes;

if ( ! defined( 'ABSPATH' ) ) exit; // If this file is called directly, abort.

/**
 * Class Premium_Title
 */
class Premium_Title extends Widget_Base {

    protected $templateInstance;

    public function getTemplateInstance(){
        return $this->templateInstance = Includes\premium_Template_Tags::getInstance();
    }
    
    public function get_name() {
        return 'premium-addon-title';
    }

    public function get_title() {
		return sprintf( '%1$s %2$s', Helper_Functions::get_prefix(), __('Title', 'premium-addons-for-elementor') );
	}

    public function get_icon() {
        return 'pa-title';
    }
    
    public function get_style_depends() {
        return [
            'premium-addons'
        ];
    }

    public function get_script_depends() {
        return [
            'premium-addons-js',
            'lottie-js',
        ];
    }

    public function get_categories() {
        return [ 'premium-elements' ];
    }

    public function get_keywords() {
		return [ 'heading', 'text' ];
	}
    
    public function get_custom_help_url() {
		return 'https://premiumaddons.com/support/';
	}
    
    /**
	 * Register Title controls.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
    protected function _register_controls() {

        $this->start_controls_section('premium_title_content',
            [
                'label'         => __('Title', 'premium-addons-for-elementor'),
            ]
        );
        
        $this->add_control('premium_title_text',
            [
                'label'         => __('Title', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::TEXT,
                'default'       => __('Premium Title','premium-addons-for-elementor'),
                'label_block'   => true,
                'dynamic'       => [ 'active' => true ]
            ]
        );
        
        $this->add_control('premium_title_style', 
            [
                'label'         => __('Style', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SELECT,
                'default'       => 'style1',
                'options'       => [
                    'style1'        => __('Style 1', 'premium-addons-for-elementor'),
                    'style2'        => __('Style 2', 'premium-addons-for-elementor'),
                    'style3'        => __('Style 3', 'premium-addons-for-elementor'),
                    'style4'        => __('Style 4', 'premium-addons-for-elementor'),
                    'style5'        => __('Style 5', 'premium-addons-for-elementor'),
                    'style6'        => __('Style 6', 'premium-addons-for-elementor'),
                    'style7'        => __('Style 7', 'premium-addons-for-elementor'),
                    // 'style8'        => __('Style 8', 'premium-addons-for-elementor'),
                    // 'style9'        => __('Style 9', 'premium-addons-for-elementor'),
                ],
                'label_block'   => true,
            ]
        );
        
        $this->add_control('premium_title_icon_switcher',
            [
                'label'         => __('Icon', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SWITCHER,
            ]
        );

        $this->add_control('icon_type',
            [
                'label'			=> __( 'Icon Type', 'premium-addons-for-elementor' ),
                'type' 			=> Controls_Manager::SELECT,
                'options'		=> [
                    'icon'          => __('Icon', 'premium-addons-for-elementor'),
                    'image'         => __('Image', 'premium-addons-for-elementor'),
                    'animation'     => __('Lottie Animation', 'premium-addons-for-elementor'),
                ],
                'default'		=> 'icon',
                'condition'     => [
                    'premium_title_icon_switcher'   => 'yes',
                ]
            ]
        );
        
        $this->add_control('premium_title_icon_updated', 
            [
                'label'         => __('Font Awesome Icon', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::ICONS,
                'fa4compatibility'  => 'premium_title_icon',
                'default'       => [
                    'value'         => 'fas fa-bars',
                    'library'       => 'fa-solid',
                ],
                'label_block'   => true,
                'condition'     => [
                    'premium_title_icon_switcher'   => 'yes',
                    'icon_type'   => 'icon',
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
				'condition'			=> [
					'premium_title_icon_switcher'   => 'yes',
                    'icon_type'   => 'image',
				],
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
                    'premium_title_icon_switcher'   => 'yes',
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
                    'premium_title_icon_switcher'   => 'yes',
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
                    'premium_title_icon_switcher'   => 'yes',
                    'icon_type'   => 'animation',
                ]
            ]
        );

        $this->add_responsive_control('icon_position',
            [
                'label'         => __( 'Icon Position', 'premium-addons-for-elementor' ),
                'type'          => Controls_Manager::SELECT,
                'options'       => [
                    'row'           => __('Before', 'premium-addons-for-elementor'),
                    'row-reverse'   => __('After', 'premium-addons-for-elementor'),
                    'column'        => __('Top', 'premium-addons-for-elementor'),
                ],
                'default'       => 'row',
                'toggle'        => false,
                'render_type'   => 'template',
                'prefix_class'  => 'premium-title-icon-',
                'selectors'     => [
                    '{{WRAPPER}} .premium-title-header:not(.premium-title-style7), {{WRAPPER}} .premium-title-style7-inner' => 'flex-direction: {{VALUE}}',
                ],
                'condition'     => [
                    'premium_title_icon_switcher'   => 'yes'
                ],
            ]
        );

        $this->add_responsive_control('top_icon_align',
            [
                'label'         => __( 'Icon Alignment', 'premium-addons-for-elementor' ),
                'type'          => Controls_Manager::CHOOSE,
                'options'       => [
                    'flex-start'      => [
                        'title'=> __( 'Left', 'premium-addons-for-elementor' ),
                        'icon' => 'fa fa-align-left',
                    ],
                    'center'    => [
                        'title'=> __( 'Center', 'premium-addons-for-elementor' ),
                        'icon' => 'fa fa-align-center',
                    ],
                    'flex-end'     => [
                        'title'=> __( 'Right', 'premium-addons-for-elementor' ),
                        'icon' => 'fa fa-align-right',
                    ],
                ],
                'default'       => 'center',
                'toggle'        => false,
                'selectors'     => [
                    '{{WRAPPER}}.premium-title-icon-column .premium-title-header:not(.premium-title-style7)' => 'align-items: {{VALUE}}',
                    '{{WRAPPER}}.premium-title-icon-column .premium-title-style7 .premium-title-icon'      => 'align-self: {{VALUE}}',
                ],
                'condition'     => [
                    'premium_title_icon_switcher'   => 'yes',
                    'icon_position'                 => 'column',
                    'premium_title_style!'           => [ 'style3', 'style4' ]
                ]
            ]
        );

        $this->add_control('premium_title_tag',
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
                    'div'   => 'div',
                    'span'  => 'span',
                ],
                'separator'     => 'before',
            ]
        );

        $inline_flex = [ 'style1', 'style2', 'style5', 'style6', 'style7' , 'style8' , 'style9'];
        
        $this->add_responsive_control('premium_title_align',
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
                        'icon' => 'fa fa-align-right',
                    ],
                ],
                'default'       => 'left',
                'toggle'        => false,
                'selectors'     => [
                    '{{WRAPPER}} .premium-title-container' => 'text-align: {{VALUE}};',
                ],
                'condition'     => [
                    'premium_title_style'  => $inline_flex
                ]
            ]
        );

        $this->add_responsive_control('premium_title_align_flex',
            [
                'label'         => __( 'Alignment', 'premium-addons-for-elementor' ),
                'type'          => Controls_Manager::CHOOSE,
                'options'       => [
                    'flex-start'      => [
                        'title'=> __( 'Left', 'premium-addons-for-elementor' ),
                        'icon' => 'fa fa-align-left',
                    ],
                    'center'    => [
                        'title'=> __( 'Center', 'premium-addons-for-elementor' ),
                        'icon' => 'fa fa-align-center',
                    ],
                    'flex-end'     => [
                        'title'=> __( 'Right', 'premium-addons-for-elementor' ),
                        'icon' => 'fa fa-align-right',
                    ],
                ],
                'default'       => 'flex-start',
                'toggle'        => false,
                'selectors'     => [
                    '{{WRAPPER}}:not(.premium-title-icon-column) .premium-title-header' => 'justify-content: {{VALUE}}',
                    '{{WRAPPER}}.premium-title-icon-column .premium-title-header' => 'align-items: {{VALUE}}',
                ],
                'condition'     => [
                    'premium_title_style'  => [ 'style3', 'style4' ]
                ]
            ]
        );

        $this->add_control('alignment_notice', 
            [
                'raw'               => __('Please note that left/right alignment is reversed when Icon Position is set to After.', 'premium-addons-for-elementor'),
                'type'              => Controls_Manager::RAW_HTML,
                'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
                'condition'     => [
                    'premium_title_icon_switcher'   => 'yes',
                    'icon_position'                 => 'row-reverse',
                    'premium_title_style'  => [ 'style3', 'style4']
                ]
            ] 
        );
        
        $this->add_control('premium_title_stripe_pos', 
            [
                'label'         => __('Stripe Position', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SELECT,
                'options'       => [
                    'top'    => __('Top', 'premium-addons-for-elementor'),
                    'bottom' => __('Bottom', 'premium-addons-for-elementor')
                ],
                'selectors_dictionary'  => [
                    'top'      => 'initial',
                    'bottom'    => '2',
                ],
                'default'       => 'top',
                'label_block'   => true,
                'separator'     => 'before',
                'condition'     => [
                    'premium_title_style'   => 'style7',
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-title-style7-stripe-wrap' => 'order: {{VALUE}}'
                ]
            ]
        );
        
        $this->add_responsive_control('premium_title_style7_strip_width',
            [
                'label'         => __('Stripe Width (PX)', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SLIDER,
                'size_units'    => ['px', '%', 'em'],
                'default'       => [
                    'unit'  => 'px',
                    'size'  => '120',
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-title-style7-stripe' => 'width: {{SIZE}}{{UNIT}};',
                ],
                'label_block'   => true,
                'condition'     => [
                    'premium_title_style'   => 'style7',
                ]
            ]
        );
        
        $this->add_responsive_control('premium_title_style7_strip_height',
            [
                'label'         => __('Stripe Height (PX)', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SLIDER,
                'size_units'    => ['px', 'em'],
                'default'       => [
                    'unit'  => 'px',
                    'size'  => '5',
                ],
                'label_block'   => true,
                'selectors'     => [
                    '{{WRAPPER}} .premium-title-style7-stripe' => 'height: {{SIZE}}{{UNIT}};',
                ],
                'condition'     => [
                    'premium_title_style'   => 'style7',
                ]
            ]
        );
        
        $this->add_responsive_control('premium_title_style7_strip_top_spacing',
            [
                'label'         => __('Stripe Top Spacing (PX)', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SLIDER,
                'size_units'    => ['px', '%', 'em'],
                'selectors'     => [
                    '{{WRAPPER}} .premium-title-style7-stripe-wrap' => 'margin-top: {{SIZE}}{{UNIT}};',
                ],
                'label_block'   => true,
                'condition'     => [
                    'premium_title_style'   => 'style7',
                ],
            ]
        );
        
        $this->add_responsive_control('premium_title_style7_strip_bottom_spacing',
            [
                'label'         => __('Stripe Bottom Spacing (PX)', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SLIDER,
                'size_units'    => ['px', '%', 'em'],
                'label_block'   => true,
                'selectors'     => [
                    '{{WRAPPER}} .premium-title-style7-stripe-wrap' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
                'condition'     => [
                    'premium_title_style'   => 'style7',
                ]
            ]
        );
        
        $this->add_responsive_control('premium_title_style7_strip_align',
            [
                'label'         => __( 'Stripe Alignment', 'premium-addons-for-elementor' ),
                'type'          => Controls_Manager::CHOOSE,
                'options'       => [
                    'flex-start'    => [
                        'title' => __( 'Left', 'premium-addons-for-elementor' ),
                        'icon'  => 'fa fa-align-left',
                        ],
                    'center'        => [
                        'title' => __( 'Center', 'premium-addons-for-elementor' ),
                        'icon'  => 'fa fa-align-center',
                        ],
                    'flex-end'     => [
                        'title'=> __( 'Right', 'premium-addons-for-elementor' ),
                        'icon' => 'fa fa-align-right',
                    ],
                ],
                'toggle'        => false,
                'default'       => 'center',
                'selectors'     => [
                    '{{WRAPPER}}:not(.premium-title-icon-column) .premium-title-style7-stripe-wrap' => 'justify-content: {{VALUE}}',
                    '{{WRAPPER}}.premium-title-icon-column .premium-title-style7-stripe-wrap' => 'align-self: {{VALUE}}',
                ],
                'condition'     => [
                    'premium_title_style'   => 'style7',
                ]
            ]
        );

        $this->add_control('link_switcher',
            [
                'label'         => __('Link', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SWITCHER,
            ]
        );
        
        $this->add_control('link_selection', 
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
                    'link_switcher'     => 'yes',
                ]
            ]
        );
        
        $this->add_control('custom_link',
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
                    'link_switcher'     => 'yes',
                    'link_selection'   => 'url'
                ]
            ]
        );
        
        $this->add_control('existing_link',
            [
                'label'         => __('Existing Page', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SELECT2,
                'options'       => $this->getTemplateInstance()->get_all_post(),
                'condition'     => [
                    'link_switcher'         => 'yes',
                    'link_selection'       => 'link',
                ],
                'multiple'      => false,
                'label_block'   => true,
            ]
        );
        
        $this->end_controls_section();

        $this->start_controls_section('premium_title_style_section',
            [
                'label'         => __('Title', 'premium-addons-for-elementor'),
                'tab'           => Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_control('premium_title_color', 
            [
                'label'         => __('Color', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::COLOR,
                'scheme'        => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_1,
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-title-header' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .premium-title-style8 .premium-title-text[data-animation="shiny"]' => '--base-color: {{VALUE}} ',
                ],
            ]
        );

        $this->add_control(
            'premium_title_blur_color',
            [
                'label' => esc_html__('Blur Color', 'booster-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#000',
                'selectors' => ['{{WRAPPER}} .premium-title-header' => '--shadow-color: {{VALUE}};'],
                'condition' => [
					'premium_title_style' => 'style9',
                ],
            ]
        );

        $this->add_control('shining_shiny_color_title',
            [
                'label'         => __('Shiny Color', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::COLOR,
                'default'       => '#fff',
                'condition' => [
					'premium_title_style' => 'style8',
                ],
                'selectors' => ['{{WRAPPER}} .premium-title-style8 .premium-title-text[data-animation="shiny"]' => '--shiny-color: {{VALUE}}'],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'          => 'title_typography',
                'scheme'        => Scheme_Typography::TYPOGRAPHY_1,
                'selector'      => '{{WRAPPER}} .premium-title-header',
            ]
        );
        
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'          => 'style_one_border',
                'selector'      => '{{WRAPPER}} .premium-title-style1',
                'condition'     => [
                    'premium_title_style'   => 'style1',
                ],
            ]
        );
        
        $this->add_control('premium_title_style2_background_color', 
            [
                'label'         => __('Background Color', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::COLOR,
                'scheme'        => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_2,
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-title-style2' => 'background-color: {{VALUE}};',
                ],
                'condition'     => [
                    'premium_title_style'   => 'style2',
                ],
            ]
        );
        
        $this->add_control('premium_title_style3_background_color', 
            [
                'label'         => __('Background Color', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::COLOR,
                'scheme'        => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_2
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-title-style3' => 'background-color: {{VALUE}};'
                ],
                'condition'     => [
                    'premium_title_style'   => 'style3'
                ]
            ]
        );
        
        $this->add_control('premium_title_style5_header_line_color', 
            [
                'label'         => __('Line Color', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::COLOR,
                'scheme' => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_1
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-title-style5' => 'border-bottom: 2px solid {{VALUE}};'
                ],
                'condition'     => [
                    'premium_title_style'   => 'style5'
                ]
            ]
        );
       
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'          => 'style_five_border',
                'selector'      => '{{WRAPPER}} .premium-title-container',
                'condition'     => [
                    'premium_title_style'   => ['style2','style4','style5','style6']
                ]
            ]
        );
        
        $this->add_control('premium_title_style6_header_line_color', 
            [
                'label'         => __('Line Color', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::COLOR,
                'scheme' => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_1
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-title-style6' => 'border-bottom: 2px solid {{VALUE}};'
                ],
                'condition'     => [
                    'premium_title_style'   => 'style6'
                ]
            ]
        );
       
        $this->add_control('premium_title_style6_triangle_color', 
            [
                'label'         => __('Triangle Color', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::COLOR,
                'scheme'        => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_1
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-title-style6:before' => 'border-bottom-color: {{VALUE}};'
                ],
                'condition'     => [
                    'premium_title_style'   => 'style6'
                ]
            ]
        );
        
        $this->add_control('premium_title_style7_strip_color', 
            [
                'label'         => __('Stripe Color', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::COLOR,
                'scheme'        => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_1
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-title-style7-stripe' => 'background-color: {{VALUE}};'
                ],
                'condition'     => [
                    'premium_title_style'   => 'style7'
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'label'         => __('Shadow','premium-addons-for-elementor'),
                'name'          => 'premium_title_text_shadow',
                'selector'      => '{{WRAPPER}} .premium-title-header'
            ]
        );

        $this->add_control('premium_title_shadow_value',
            [
                'label' => esc_html__('Blur Shadow Value (px)', 'booster-addons'),
                'type' => Controls_Manager::NUMBER,
                'min' => '10', 'max' => '500',  'step' => '10', 
                'dynamic' => ['active' => true],
                'selectors' => ['{{WRAPPER}} .premium-title-header' => '--shadow-value: {{VALUE}}px;'],
                'default' => '120',
                'condition' => [
					'premium_title_style' => 'style9',
                ],
            ]
        );

        $this->add_control('premium_title_delay',
            [
                'label'     => esc_html__('Animation Delay (s)', 'premium-addons-for-elementor'),
                'type'      => Controls_Manager::NUMBER,
                'min'       => '1',
                'max'       => '30',
                'step'      => 0.5,
                'condition' => [
					'premium_title_style' => ['style8','style9'],
                ],
                'render_type'   => 'template',
                'dynamic'   => ['active' => true],
                'default'   => '2'
            ]
        );

        $this->add_control('shining_animation_duration',
            [
                'label'         => __( 'Animation Duration (s)', 'premium-addons-for-elementor' ),
                'type'          => Controls_Manager::NUMBER,
                'default'       => '1',
                'step'          => 0.5,
                'condition'     => [
                    'premium_title_style' => 'style8'
                ],
                'frontend_available' => true,
                'render_type'   => 'template',
                'selectors'     => [
                    '{{WRAPPER}} .premium-title-style8 .premium-title-text[data-animation="shiny"]' => '--animation-speed: {{VALUE}}s ',
                ]
            ]
        );

        $this->add_responsive_control('premium_title_margin', 
            [
                'label'         => __('Margin', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::DIMENSIONS,
                'size_units'    => ['px', 'em', '%'],
                'selectors'     => [
                    '{{WRAPPER}} .premium-title-text' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->add_responsive_control('premium_title_padding', 
            [
                'label'         => __('Padding', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::DIMENSIONS,
                'size_units'    => ['px', 'em', '%'],
                'selectors'     => [
                    '{{WRAPPER}} .premium-title-text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );
        
        $this->end_controls_section();
        
        $this->start_controls_section('premium_title_icon_style_section',
            [
                'label'         => __('Icon', 'premium-addons-for-elementor'),
                'tab'           => Controls_Manager::TAB_STYLE,
                'condition'     => [
                    'premium_title_icon_switcher'   => 'yes'
                ]
            ]
        );
        
        $this->add_control('premium_title_icon_color', 
            [
                'label'         => __('Color', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::COLOR,
                'scheme' => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_1
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-title-icon' => 'color: {{VALUE}};'
                ],
                'condition'     => [
                    'premium_title_icon_switcher'   => 'yes',
                    'icon_type'                     => 'icon'
                ]
            ]
        );
        
        $this->add_responsive_control('premium_title_icon_size', 
            [
                'label'         => __('Size', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SLIDER,
                'size_units'    => ['px', 'em', '%'],
                'range' => [
					'px' => [
						'min' => 10,
						'max' => 300,
                    ],
                    'em' => [
						'min' => 1,
						'max' => 30,
					]
				],
                'selectors'     => [
                    '{{WRAPPER}} .premium-title-header i' => 'font-size: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}} .premium-title-header svg, {{WRAPPER}} .premium-title-header img' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}'
                ]
            ]
        );
        
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'          => 'premium_title_icon_background',
                'types'         => [ 'classic' , 'gradient' ],
                'selector'      => '{{WRAPPER}} .premium-title-icon'
            ]
        );
        
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'          => 'premium_title_icon_border',
                'selector'      => '{{WRAPPER}} .premium-title-icon'
            ]
        );
        
        $this->add_control('premium_title_icon_border_radius',
            [
                'label'         => __('Border Radius', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SLIDER,
                'size_units'    => ['px', '%', 'em'],
                'selectors'     => [
                    '{{WRAPPER}} .premium-title-icon' => 'border-radius: {{SIZE}}{{UNIT}};'
                ]
            ]
        );
        
        $this->add_responsive_control('premium_title_icon_margin',
            [
                'label'         => __('Margin', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::DIMENSIONS,
                'size_units'    => [ 'px', 'em', '%' ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-title-icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}'
                ]
            ]
        );
        
        $this->add_responsive_control('premium_title_icon_padding',
            [
                'label'         => __('Padding', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::DIMENSIONS,
                'size_units'    => [ 'px', 'em', '%' ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-title-icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}'
                ]
            ]
        );
        
        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'label'         => __('Icon Shadow', 'premium-addons-for-elementor'),
                'name'          => 'premium_title_icon_text_shadow',
                'selector'      => '{{WRAPPER}} .premium-title-icon',
                'condition'     => [
                    'premium_title_icon_switcher'   => 'yes',
                    'icon_type'                     => 'icon'
                ]
            ]
        );
        
        $this->end_controls_section();

    }

    /**
	 * Render title widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
    protected function render() {
        
        $settings = $this->get_settings_for_display();
        
        $this->add_inline_editing_attributes('premium_title_text', 'none');

        $this->add_render_attribute('premium_title_text', 'class', 'premium-title-text');
        
        $title_tag = $settings['premium_title_tag'];
        
        $selected_style = $settings['premium_title_style'];
        
        $this->add_render_attribute( 'container', 'class', [ 'premium-title-container', $selected_style ] );
        
        $this->add_render_attribute( 'title', 'class', [ 'premium-title-header', 'premium-title-' . $selected_style ] );

        if ( $selected_style === 'style9' ) {

            $this->add_render_attribute( 'title', 'data-blur-delay', $settings['premium_title_delay'] );

        }
        if($selected_style === 'style8'){

            $this->add_render_attribute( 'premium_title_text', 'data-shiny-delay', $settings['premium_title_delay'] );
            $this->add_render_attribute( 'premium_title_text', 'data-shiny-dur', $settings['shining_animation_duration'] );

        }

        $icon_position = '';
        
        if( 'yes' === $settings['premium_title_icon_switcher'] ) {

            $icon_type = $settings['icon_type'];

            $icon_position = $settings['icon_position'];

            if( 'icon' === $icon_type ) {

                if ( ! empty ( $settings['premium_title_icon'] ) ) {
                    $this->add_render_attribute( 'icon', 'class', $settings['premium_title_icon'] );
                    $this->add_render_attribute( 'icon', 'aria-hidden', 'true' );
                }
                
                $migrated = isset( $settings['__fa4_migrated']['premium_title_icon_updated'] );
                $is_new = empty( $settings['premium_title_icon'] ) && Icons_Manager::is_migration_allowed();

            } elseif( 'animation' === $icon_type ) {
                $this->add_render_attribute( 'title_lottie', [
                    'class' => [
                        'premium-title-icon',
                        'premium-lottie-animation'
                    ],
                    'data-lottie-url' => $settings['lottie_url'],
                    'data-lottie-loop' => $settings['lottie_loop'],
                    'data-lottie-reverse' => $settings['lottie_reverse']
                ]);
            } else {

                $src        = $settings['image_upload']['url'];

                $alt        = Control_Media::get_image_alt( $settings['image_upload'] );

                $this->add_render_attribute( 'title_img', [
                    'class' => 'premium-title-icon',
                    'src'   => $src,
                    'alt'   => $alt
                ]);
            }
        }

        if( $settings['link_switcher'] === 'yes' ) {

            $link = '';

            if( $settings['link_selection'] === 'link' ) {

                $link = get_permalink( $settings['existing_link'] );

            } else {

                $link = $settings['custom_link']['url'];

            }

            $this->add_render_attribute( 'link', 'href', $link );

            if( ! empty( $settings['custom_link']['is_external'] ) ) {
                $this->add_render_attribute( 'link', 'target', "_blank" );
            }

            if( ! empty( $settings['custom_link']['nofollow'] ) ) {
                $this->add_render_attribute( 'link', 'rel',  "nofollow" );
            }
        }

    ?>

    <div <?php echo $this->get_render_attribute_string('container'); ?>>
        <<?php echo $title_tag . ' ' . $this->get_render_attribute_string('title') ; ?>>
            <?php if ( $selected_style === 'style7' ) : ?>
                <?php if( 'column' !== $icon_position ) : ?>
                    <span class="premium-title-style7-stripe-wrap">
                        <span class="premium-title-style7-stripe"></span>
                    </span>
                <?php endif; ?>
                <div class="premium-title-style7-inner">
            <?php endif; ?>

            <?php if( 'yes' === $settings['premium_title_icon_switcher'] ) : ?>
                <?php if( 'icon' === $icon_type ) : ?>
                    <?php if ( $is_new || $migrated ) :
                        Icons_Manager::render_icon( $settings['premium_title_icon_updated'], [ 'class' => 'premium-title-icon', 'aria-hidden' => 'true' ] );
                    else: ?>
                        <i <?php echo $this->get_render_attribute_string( 'icon' ); ?>></i>
                    <?php endif; ?>
                <?php elseif( 'animation' === $icon_type ): ?>
                    <div <?php echo $this->get_render_attribute_string( 'title_lottie' ); ?>></div>
                <?php else: ?>
                    <img <?php echo $this->get_render_attribute_string( 'title_img' ); ?>>
                <?php endif; ?>
            <?php endif; ?>

            <?php if ( $selected_style === 'style7' ) : ?>
                <?php if( 'column' === $icon_position ) : ?>
                    <span class="premium-title-style7-stripe-wrap">
                        <span class="premium-title-style7-stripe"></span>
                    </span>
                <?php endif; ?>
            <?php endif; ?>
            <?php if( $selected_style !== 'style9' ) :?>
            <span <?php echo $this->get_render_attribute_string('premium_title_text'); ?> >
                <?php echo esc_html( $settings['premium_title_text'] ); ?>
            </span>
            <?php else: 
                     $letters_html = '<span class="premium-letters-container"'.$this->get_render_attribute_string('premium_title_text').'>';   
                     $title_array = str_split(esc_html($settings['premium_title_text']));
                     foreach ($title_array as $key => $letter):    
                         $key = $key+1;                
                         $letters_html .='<span class="premium-title-style9-letter" data-letter-index="'.esc_attr($key+1).'" data-letter="'.esc_attr($letter).'">'.$letter.'</span>';
                     endforeach;
                     $the_title = $letters_html.'</span>';
                     echo $the_title;
            ?>
            <?php endif; ?>

            <?php if ( $selected_style === 'style7' ) : ?>
                </div>
            <?php endif; ?>
            <?php if( $settings['link_switcher'] === 'yes' && !empty( $link ) ) : ?>
                <a <?php echo $this->get_render_attribute_string( 'link' ); ?>></a>
            <?php endif; ?>
        </<?php echo $title_tag; ?>>
    </div>

    <?php
    }
    
    /**
	 * Render Title widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
    protected function content_template() {
        ?>
        <#
            
            view.addInlineEditingAttributes('premium_title_text', 'none');
        
            view.addRenderAttribute('premium_title_text', 'class', 'premium-title-text');

            var titleTag = settings.premium_title_tag,
        
            selectedStyle = settings.premium_title_style,
            
            titleIcon = settings.premium_title_icon,
            
            titleText = settings.premium_title_text;
            
            view.addRenderAttribute( 'premium_title_container', 'class', [ 'premium-title-container', selectedStyle ] );
            
            view.addRenderAttribute( 'premium_title', 'class', [ 'premium-title-header', 'premium-title-' + selectedStyle ] );

            if ( selectedStyle === 'style9' ) {
                view.addRenderAttribute( 'premium_title', 'data-blur-delay', settings.premium_title_delay );
            }

            if( selectedStyle  === 'style8'){
                view.addRenderAttribute( 'premium_title_text', 'data-shiny-delay', settings.premium_title_delay );
                view.addRenderAttribute( 'premium_title_text', 'data-shiny-dur', settings.shining_animation_duration );
            }
                
            view.addRenderAttribute( 'icon', 'class', [ 'premium-title-icon', titleIcon ] );
            
            if( 'yes' === settings.premium_title_icon_switcher ) {

                var iconType = settings.icon_type,
                    iconPosition = settings.icon_position;

                if( 'icon' === iconType ) {
                    var iconHTML = elementor.helpers.renderIcon( view, settings.premium_title_icon_updated, { 'class': 'premium-title-icon', 'aria-hidden': true }, 'i' , 'object' ),
                        migrated = elementor.helpers.isIconMigrated( settings, 'premium_title_icon_updated' );
                } else if( 'animation' === iconType ) {

                    view.addRenderAttribute( 'title_lottie', {
                        'class': [
                            'premium-title-icon',
                            'premium-lottie-animation'
                        ],
                        'data-lottie-url': settings.lottie_url,
                        'data-lottie-loop': settings.lottie_loop,
                        'data-lottie-reverse': settings.lottie_reverse
                    });
                    
                } else {

                    view.addRenderAttribute( 'title_img', 'class', 'premium-title-icon' );
                    view.addRenderAttribute( 'title_img', 'src', settings.image_upload.url );

                }
                
            }

            if( 'yes' === settings.link_switcher ) {

                var link = '';

                if( settings.link_selection === 'link' ) {

                    link = settings.existing_link;

                } else {

                    link = settings.custom_link.url;

                }

                view.addRenderAttribute( 'link', 'href', link );

            }        
        #>
        <div {{{ view.getRenderAttributeString('premium_title_container') }}}>
            <{{{titleTag}}} {{{view.getRenderAttributeString('premium_title')}}}>
                <# if( 'style7' === selectedStyle ) { #>
                    <# if( 'column' !== iconPosition ) { #>
                        <span class="premium-title-style7-stripe-wrap">
                            <span class="premium-title-style7-stripe"></span>
                        </span>    
                    <# } #>
                    <div class="premium-title-style7-inner">
                <# } 
                    if( 'yes' === settings.premium_title_icon_switcher ) { #>
                        <# if( 'icon' === iconType ) { #>
                            <# if ( iconHTML && iconHTML.rendered && ( ! settings.premium_title_icon || migrated ) ) { #>
                                {{{ iconHTML.value }}}
                            <# } else { #>
                                <i {{{ view.getRenderAttributeString( 'icon' ) }}}></i>
                            <# } #>
                        <# } else if( 'animation' === iconType ) { #>
                            <div {{{ view.getRenderAttributeString('title_lottie') }}}></div>
                        <# } else { #>
                            <img {{{ view.getRenderAttributeString('title_img') }}}>
                        <# } #>
                    <# } #>
                <# if( 'style7' === selectedStyle ) { #>
                    <# if( 'column' === iconPosition ) { #>
                        <span class="premium-title-style7-stripe-wrap">
                            <span class="premium-title-style7-stripe"></span>
                        </span>
                    <# } #>
                <# } #>
                <# if( selectedStyle !== 'style9' ) {#>
                <span {{{ view.getRenderAttributeString('premium_title_text') }}} >{{{ titleText }}}</span>
                <# } else {
                     lettersHtml = '<span class="premium-letters-container"'+ view.getRenderAttributeString('premium_title_text') +'>';  
                     text =  titleText  ; 
                     titleArray = text.split('');
                     key=0;
                     titleArray.forEach(function (item) { 
                         key = key+1;                
                         lettersHtml +='<span class="premium-title-style9-letter" data-letter-index="'+(key+1)+'" data-letter="'+(item)+'">'+item+'</span>';
                    }); 
                    theTitle = lettersHtml+'</span>'; #>
                     {{{theTitle}}}
                     <#
                    }
                #>
                <# if( 'style7' === selectedStyle ) { #>
                    </div>
                <# } #>
                <# if( 'yes' === settings.link_switcher && '' !== link ) { #>
                    <a {{{ view.getRenderAttributeString('link') }}}></a>
                <# } #>
            </{{{titleTag}}}>
        </div>
        
        <?php
    }
}