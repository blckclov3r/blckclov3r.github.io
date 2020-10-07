<?php

/**
 * Premium Pricing Table.
 */
namespace PremiumAddons\Widgets;

 // Elementor Classes.
use Elementor\Modules\DynamicTags\Module as TagsModule;
use Elementor\Icons_Manager;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Scheme_Color;
use Elementor\Scheme_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;

// PremiumAddons Classes.
use PremiumAddons\Helper_Functions;
use PremiumAddons\Includes;

if ( ! defined( 'ABSPATH' ) ) exit; // If this file is called directly, abort.

/**
 * Class Premium_Pricing_Table
 */
class Premium_Pricing_Table extends Widget_Base {

    protected $templateInstance;

    public function getTemplateInstance() {
        return $this->templateInstance = Includes\premium_Template_Tags::getInstance();
    }

    public function get_name() {
        return 'premium-addon-pricing-table';
    }

    public function get_title() {
        return sprintf( '%1$s %2$s', Helper_Functions::get_prefix(), __('Pricing Table', 'premium-addons-for-elementor') );
    }

    public function get_icon() {
        return 'pa-pricing-table';
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

    public function get_categories() {
        return [ 'premium-elements' ];
    }

    public function get_custom_help_url() {
		return 'https://premiumaddons.com/support/';
	}

    /**
	 * Register Pricing Table controls.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
    protected function _register_controls() {
        
        $this->start_controls_section('premium_pricing_table_icon_section',
            [
                'label'         => __('Icon', 'premium-addons-for-elementor'),
                'condition'     => [
                    'premium_pricing_table_icon_switcher'  => 'yes',
                ]
            ]
        );

        $this->add_control('icon_type',
            [
                'label'			=> __( 'Icon Type', 'premium-addons-for-elementor' ),
                'type' 			=> Controls_Manager::SELECT,
                'options'		=> [
                    'icon'          => __('Icon', 'premium-addons-for-elementor'),
                    'animation'     => __('Lottie Animation', 'premium-addons-for-elementor'),
                ],
                'default'		=> 'icon',
            ]
        );
        
        $this->add_control('premium_pricing_table_icon_selection_updated', 
            [
                'label'         => __('Select an Icon', 'premium-addons-for-elementor'),
                'type'              => Controls_Manager::ICONS,
                'fa4compatibility'  => 'premium_pricing_table_icon_selection',
                'default' => [
                    'value'     => 'fas fa-bars',
                    'library'   => 'fa-solid',
                ],
                'condition'     => [
                    'icon_type'   => 'icon',
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
                    'icon_type'   => 'animation',
                ]
            ]
        );
        
        $this->end_controls_section();
        
        $this->start_controls_section('premium_pricing_table_title_section',
            [
                'label'         => __('Title', 'premium-addons-for-elementor'),
                'condition'     => [
                    'premium_pricing_table_title_switcher'  => 'yes',
                ]
            ]
        );
        
        $this->add_control('premium_pricing_table_title_text',
            [
                'label'         => __('Text', 'premium-addons-for-elementor'),
                'default'       => __('Pricing Table', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::TEXT,
                'dynamic'       => [ 'active' => true ],
                'label_block'   => true,
            ]
        );
        
        $this->add_control('premium_pricing_table_title_size',
            [
                'label'         => __('HTML Tag', 'premium-addons-for-elementor'),
                'description'   => __( 'Select HTML tag for the title', 'premium-addons-for-elementor' ),
                'type'          => Controls_Manager::SELECT,
                'default'       => 'h3',
                'options'       => [
                    'h1'    => 'H1',
                    'h2'    => 'H2',
                    'h3'    => 'H3',
                    'h4'    => 'H4',
                    'h5'    => 'H5',
                    'h6'    => 'H6',
                    'div'   => 'div',
                    'span'  => 'span',
                    'p'     => 'p',
                ],
                'label_block'   => true,
            ]
        );
        
        $this->end_controls_section();
        
        
        /*Price Content Section*/
        $this->start_controls_section('premium_pricing_table_price_section',
                [
                    'label'         => __('Price', 'premium-addons-for-elementor'),
                    'condition'     => [
                        'premium_pricing_table_price_switcher'  => 'yes',
                        ]
                    ]
                );

        /*Price Value*/ 
        $this->add_control('premium_pricing_table_slashed_price_value',
                [
                    'label'         => __('Slashed Price', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::TEXT,
                    'dynamic'       => [ 'active' => true ],
                    'label_block'   => true,
                ]
            );
        
        /*Price Currency*/ 
        $this->add_control('premium_pricing_table_price_currency',
                [
                    'label'         => __('Currency', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::TEXT,
                    'dynamic'       => [ 'active' => true ],
                    'default'       => '$',
                    'label_block'   => true,
                ]
                );
        
        /*Price Value*/ 
        $this->add_control('premium_pricing_table_price_value',
                [
                    'label'         => __('Price', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::TEXT,
                    'dynamic'       => [ 'active' => true ],
                    'default'       => '25',
                    'label_block'   => true,
                ]
                );
        
        /*Price Separator*/ 
        $this->add_control('premium_pricing_table_price_separator',
                [
                    'label'         => __('Divider', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::TEXT,
                    'dynamic'       => [ 'active' => true ],
                    'default'       => '/',
                    'label_block'   => true,
                ]
                );
       
        /*Price Duration*/ 
        $this->add_control('premium_pricing_table_price_duration',
                [
                    'label'         => __('Duration', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::TEXT,
                    'dynamic'       => [ 'active' => true ],
                    'default'       => 'm',
                    'label_block'   => true,
                ]
                );
        
        $this->end_controls_section();
        
        $this->start_controls_section('premium_pricing_table_list_section',
            [
                'label'         => __('Feature List', 'premium-addons-for-elementor'),
                'condition'     => [
                    'premium_pricing_table_list_switcher'  => 'yes',
                ]
            ]
        );
        
        $repeater = new REPEATER();
        
        $repeater->add_control('premium_pricing_list_item_text',
            [
                'label'       => __( 'Text', 'premium-addons-for-elementor' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => __('Feature Title', 'premium-addons-for-elementor'),
                'dynamic'     => [ 'active' => true ],
                'label_block' => true,
            ]
        );
        
        $repeater->add_control('icon_type',
            [
                'label'			=> __( 'Icon Type', 'premium-addons-for-elementor' ),
                'type' 			=> Controls_Manager::SELECT,
                'options'		=> [
                    'icon'          => __('Icon', 'premium-addons-for-elementor'),
                    'animation'     => __('Lottie Animation', 'premium-addons-for-elementor'),
                ],
                'default'		=> 'icon',
            ]
        );
        
        $repeater->add_control('premium_pricing_list_item_icon_updated',
            [
                'label'             => __( 'Icon', 'premium-addons-for-elementor' ),
                'type'              => Controls_Manager::ICONS,
                'fa4compatibility'  => 'premium_pricing_list_item_icon',
                'default'           => [
					'value'     => 'fas fa-check',
					'library'   => 'fa-solid',
                ],
                'condition'         => [
                    'icon_type' => 'icon'
                ]
            ]
        );

        $repeater->add_control('lottie_url', 
            [
                'label'             => __( 'Animation JSON URL', 'premium-addons-for-elementor' ),
                'type'              => Controls_Manager::TEXT,
                'dynamic'           => [ 'active' => true ],
                'description'       => 'Get JSON code URL from <a href="https://lottiefiles.com/" target="_blank">here</a>',
                'label_block'       => true,
                'condition'     => [
                    'icon_type'   => 'animation',
                ]
            ]
        );

        $repeater->add_control('lottie_loop',
            [
                'label'         => __('Loop','premium-addons-for-elementor'),
                'type'          => Controls_Manager::SWITCHER,
                'return_value'  => 'true',
                'default'       => 'true',
                'condition'     => [
                    'icon_type'   => 'animation',
                ]
            ]
        );

        $repeater->add_control('lottie_reverse',
            [
                'label'         => __('Reverse','premium-addons-for-elementor'),
                'type'          => Controls_Manager::SWITCHER,
                'return_value'  => 'true',
                'condition'     => [
                    'icon_type'   => 'animation',
                ]
            ]
        );

        $repeater->add_control('premium_pricing_table_item_tooltip',
            [
                'label'         => __('Tooltip', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SWITCHER,
            ]
        );

        $repeater->add_control('premium_pricing_table_item_tooltip_text',
            [
                'label'         => __('Tooltip Text', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::TEXTAREA,
                'dynamic'       => [ 'active' => true ],
                'default'       => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit',
                'condition'     => [
                    'premium_pricing_table_item_tooltip'    => 'yes'
                ]
            ]
        );

        $repeater->add_control('list_item_icon_color',
            [
                'label'         => __('Icon Color', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::COLOR,
                'selectors'     => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .premium-pricing-feature-icon'  => 'color: {{VALUE}};'
                ],
                'condition'     => [
                    'icon_type'     => 'icon'
                ]
            ]
        );

        $repeater->add_control('list_item_text_color',
            [
                'label'         => __('Text Color', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::COLOR,
                'selectors'     => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .premium-pricing-list-span'  => 'color: {{VALUE}};'
                ],
            ]
        );

         $this->add_control('premium_fancy_text_list_items',
            [
                'label'         => __( 'Features', 'premium-addons-for-elementor' ),
                'type'          => Controls_Manager::REPEATER,
                'default'       => [
                [
                    'premium_pricing_list_item_icon_updated'    => [
                        'value'     => 'fas fa-check',
                        'library'   => 'fa-solid',
                    ],
                    'premium_pricing_list_item_text'    => __( 'List Item #1', 'premium-addons-for-elementor' ),
                ],
                [
                    'premium_pricing_list_item_icon_updated'    => [
                        'value'     => 'fas fa-check',
                        'library'   => 'fa-solid',
                    ],
                    'premium_pricing_list_item_text'    => __( 'List Item #2', 'premium-addons-for-elementor' ),
                ],
                [
                    'premium_pricing_list_item_icon_updated'    => [
                        'value'     => 'fas fa-check',
                        'library'   => 'fa-solid',
                    ],
                    'premium_pricing_list_item_text'    => __( 'List Item #3', 'premium-addons-for-elementor' ),
                ],
                ],
                'fields'        => $repeater->get_controls(),
                'title_field'   => '{{{ elementor.helpers.renderIcon( this, premium_pricing_list_item_icon_updated, {}, "i", "panel" ) || \'<i class="{{ premium_pricing_list_item_icon }}" aria-hidden="true"></i>\' }}} {{{ premium_pricing_list_item_text }}}'
            ]
        );

         $this->add_responsive_control('premium_pricing_table_list_align',
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
                'selectors_dictionary'  => [
                    'left'      => 'flex-start',
                    'center'    => 'center',
                    'right'     => 'flex-end',
                ],
                'selectors'         => [
                    '{{WRAPPER}} .premium-pricing-list .premium-pricing-list-item' => 'justify-content: {{VALUE}}',
                ],
                'default' => 'center',
            ]
        );
        
        $this->end_controls_section();
        
        /*Description Content Section*/
        $this->start_controls_section('premium_pricing_table_description_section',
                [
                    'label'         => __('Description', 'premium-addons-for-elementor'),
                    'condition'     => [
                        'premium_pricing_table_description_switcher'  => 'yes',
                        ]
                    ]
                );
        
        $this->add_control('premium_pricing_table_description_text',
            [
                'label'         => __('Description', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::WYSIWYG,
                'dynamic'       => [ 'active' => true ],
                'default'       => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit'
            ]
        );
        
        $this->end_controls_section();
        
        $this->start_controls_section('premium_pricing_table_button_section',
            [
                'label'         => __('Button', 'premium-addons-for-elementor'),
                'condition'     => [
                    'premium_pricing_table_button_switcher'  => 'yes',
                ]
            ]
        );
        
        $this->add_control('premium_pricing_table_button_text',
            [
                'label'         => __('Text', 'premium-addons-for-elementor'),
                'default'       => __('Get Started' , 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::TEXT,
                'dynamic'       => [ 'active' => true ],
                'label_block'   => true,
            ]
        );

        $this->add_control('premium_pricing_table_button_url_type', 
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
        
        $this->add_control('premium_pricing_table_button_link',
            [
                'label'         => __('Link', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::TEXT,
                'dynamic'       => [
                    'active'        => true,
                    'categories'    => [
                        TagsModule::POST_META_CATEGORY,
                        TagsModule::URL_CATEGORY
                    ]
                ],
                'condition'     => [
                    'premium_pricing_table_button_url_type'     => 'url',
                ],
                'label_block'   => true,
            ]
        );
        
        $this->add_control('premium_pricing_table_button_link_existing_content',
            [
                'label'         => __('Existing Page', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SELECT2,
                'options'       => $this->getTemplateInstance()->get_all_post(),
                'condition'     => [
                    'premium_pricing_table_button_url_type'     => 'link',
                ],
                'multiple'      => false,
                'label_block'   => true,
            ]
        );
        
        $this->add_control('premium_pricing_table_button_link_target',
            [
                'label'         => __('Link Target', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SELECT,
                'description'   => __( ' Where would you like the link be opened?', 'premium-addons-for-elementor' ),
                'options'       => [
                    'blank'  => 'Blank',
                    'parent' => 'Parent',
                    'self'   => 'Self',
                    'top'    => 'Top',
                    ],
                'default'       => 'blank' ,
                'label_block'   => true,
                ]
            );
        
        $this->end_controls_section();
        
        $this->start_controls_section('premium_pricing_table_ribbon_section',
            [
                'label'         => __('Ribbon', 'premium-addons-for-elementor'),
                'condition'     => [
                    'premium_pricing_table_badge_switcher'  => 'yes',
                ]
            ]
        );
        
        $this->add_control('ribbon_type',
            [
                'label'         => __('Type', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SELECT,
                'options'       => [
                    'triangle'      => __('Triangle', 'premium-addons-for-elementor'),
                    'circle'        => __('Circle', 'premium-addons-for-elementor'),
                    'stripe'        => __('Stripe', 'premium-addons-for-elementor'),
                    'flag'          => __('Flag', 'premium-addons-for-elementor'),
                ],
                'default'       => 'triangle'
            ]
        );
        
        $this->add_control('premium_pricing_table_badge_text',
            [
                'label'         => __('Text', 'premium-addons-for-elementor'),
                'default'       => __('NEW', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::TEXT,
                'dynamic'       => [ 'active' => true ],
                'label_block'   => true,
            ]
        );
        
        $this->add_responsive_control('premium_pricing_table_badge_left_size', 
            [
                'label'     => __('Size', 'premium-addons-for-elementor'),
                'type'      => Controls_Manager::SLIDER,
                'range'     => [
                    'px'    => [
                        'min'   => 1,
                        'max'   => 300,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .premium-badge-triangle.premium-badge-left .corner' => 'border-top-width: {{SIZE}}px; border-bottom-width: {{SIZE}}px; border-right-width: {{SIZE}}px;'
                ],
                'condition' => [
                    'ribbon_type'                           => 'triangle',
                    'premium_pricing_table_badge_position'  => 'left'
                ]
            ]
        );
                
        $this->add_responsive_control('premium_pricing_table_badge_right_size', 
            [
                'label'     => __('Size', 'premium-addons-for-elementor'),
                'type'      => Controls_Manager::SLIDER,
                'range'     => [
                    'px'    => [
                        'min'   => 1,
                        'max'   => 300,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .premium-badge-triangle.premium-badge-right .corner' => 'border-right-width: {{SIZE}}px; border-bottom-width: {{SIZE}}px; border-left-width: {{SIZE}}px;'
                ],
                'condition' => [
                    'ribbon_type'                           => 'triangle',
                    'premium_pricing_table_badge_position'  => 'right'
                ]
            ]
        );
        
        $this->add_responsive_control('circle_ribbon_size', 
            [
                'label'     => __('Size', 'premium-addons-for-elementor'),
                'type'      => Controls_Manager::SLIDER,
                'range'     => [
                    'px'    => [
                        'min'   => 1,
                        'max'   => 10,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .premium-badge-circle' => 'min-width: {{SIZE}}em; min-height: {{SIZE}}em; line-height: {{SIZE}}'
                ],
                'condition' => [
                    'ribbon_type'   => 'circle'
                ]
            ]
        );
       
        $this->add_control('premium_pricing_table_badge_position',
			[
				'label'       => __( 'Position', 'premium-addons-for-elementor' ),
				'type'        => Controls_Manager::CHOOSE,
				'toggle'      => false,
				'options'     => [
					'left'  => [
						'title' => __( 'Left', 'premium-addons-for-elementor' ),
						'icon'  => 'eicon-h-align-left',
					],
					'right' => [
						'title' => __( 'Right', 'premium-addons-for-elementor' ),
						'icon'  => 'eicon-h-align-right',
					],
				],
				'default'     => 'right',
                'condition'     => [
                    'ribbon_type!'  => 'flag'
                ]
			]
		);
        
        $this->add_responsive_control('premium_pricing_table_badge_right_right', 
            [
                'label'     => __('Horizontal Offset', 'premium-addons-for-elementor'),
                'type'      => Controls_Manager::SLIDER,
                'size_units'=> [ 'px', 'em', '%' ],
                'range'     => [
                    'px'=> [
                        'min'   => 1,
                        'max'   => 170,
                    ],
                    'em'=> [
                        'min'   => 1,
                        'max'   => 30,
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}} .premium-badge-right .corner span' => 'right: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}} .premium-badge-circle' => 'right: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'ribbon_type!'                          => [ 'stripe', 'flag' ],
                    'premium_pricing_table_badge_position'  => 'right'
                ]
            ]
        );
        
        $this->add_responsive_control('premium_pricing_table_badge_right_left', 
            [
                'label'     => __('Horizontal Offset', 'premium-addons-for-elementor'),
                'type'      => Controls_Manager::SLIDER,
                'size_units'=> [ 'px', 'em', '%' ],
                'range'     => [
                    'px'=> [
                        'min'   => 1,
                        'max'   => 170,
                    ],
                    'em'=> [
                        'min'   => 1,
                        'max'   => 30,
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}} .premium-badge-left .corner span' => 'left: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}} .premium-badge-circle' => 'left: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'ribbon_type!'                          => [ 'stripe', 'flag' ],
                    'premium_pricing_table_badge_position'  => 'left'
                ]
            ]
        ); 
        
        $this->add_responsive_control('premium_pricing_table_badge_right_top', 
            [
                'label'     => __('Vertical Offset', 'premium-addons-for-elementor'),
                'type'      => Controls_Manager::SLIDER,
                'size_units'=> [ 'px', 'em', '%'],
                'range'     => [
                    'px'=> [
                        'min'   => 1,
                        'max'   => 200,
                    ],
                    'em'=> [
                        'min'   => 1,
                        'max'   => 20,
                    ]
                ],
                'condition' => [
                    'ribbon_type!'  => 'stripe'
                ],
                'selectors' => [
                    '{{WRAPPER}} .premium-pricing-badge-container .corner span' => 'top: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}} .premium-badge-circle , .premium-badge-flag .corner' => 'top: {{SIZE}}{{UNIT}}',
                ],
            ]
        );
        
        $this->end_controls_section();
        
        $this->start_controls_section('premium_pricing_table_title',
            [
                'label'         => __('Display Options', 'premium-addons-for-elementor'),
            ]
        );
    
        $this->add_control('premium_pricing_table_icon_switcher',
            [
                'label'         => __('Icon', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SWITCHER,
            ]
        );
        
        $this->add_control('premium_pricing_table_title_switcher',
            [
                'label'         => __('Title', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SWITCHER,
                'default'       => 'yes',
            ]
        );
        
        $this->add_control('premium_pricing_table_price_switcher',
            [
                'label'         => __('Price', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SWITCHER,
                'default'       => 'yes',
            ]
        );
        
        $this->add_control('premium_pricing_table_list_switcher',
            [
                'label'         => __('Features', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SWITCHER,
                'default'       => 'yes',
            ]
        );
        
        $this->add_control('premium_pricing_table_description_switcher',
            [
                'label'         => __('Description', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SWITCHER,
            ]
        );
        
        $this->add_control('premium_pricing_table_button_switcher',
            [
                'label'         => __('Button', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SWITCHER,
                'default'       => 'yes',
            ]
        );
        
        $this->add_control('premium_pricing_table_badge_switcher',
            [
                'label'         => __('Ribbon', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SWITCHER,
                'default'       => 'yes',
            ]
        );
        
        $this->end_controls_section();
        
        $this->start_controls_section('premium_pricing_icon_style_settings',
            [
                'label'         => __('Icon', 'premium-addons-for-elementor'),
                'tab'           => Controls_Manager::TAB_STYLE,
                'condition'     => [
                    'premium_pricing_table_icon_switcher'  => 'yes',
                ]
            ]
        );
        
        $this->add_control('premium_pricing_icon_color',
            [
                'label'         => __('Color', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::COLOR,
                'scheme'        => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_2,
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-pricing-icon-container i'  => 'color: {{VALUE}};'
                ],
                'condition'     => [
                    'icon_type'     => 'icon'
                ]
            ]
        );

        $this->add_responsive_control('premium_pricing_icon_size',
            [
                'label'         => __('Size', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SLIDER,
                'size_units'    => ['px', '%', 'em'],
                'default'       => [
                    'size'  => 25,
                    'unit'  => 'px'
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-pricing-icon-container i' => 'font-size: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}} .premium-pricing-icon-container svg' => 'width: {{SIZE}}px; height: {{SIZE}}{{UNIT}}'
                ]
            ]
        );
        
        $this->add_control('premium_pricing_icon_back_color',
            [
                'label'         => __('Background Color', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::COLOR,
                'scheme'        => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_1,
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-pricing-icon-container i, {{WRAPPER}} .premium-pricing-icon'  => 'background-color: {{VALUE}};'
                ]
            ]
        );
        
        $this->add_responsive_control('premium_pricing_icon_inner_padding',
            [
                'label'         => __('Padding', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SLIDER,
                'size_units'    => ['px','em'],
                'default'       => [
                    'size'  => 10,
                    'unit'  => 'px'
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-pricing-icon-container i, {{WRAPPER}} .premium-pricing-icon' => 'padding: {{SIZE}}{{UNIT}};',
                ]
            ]
        );
        
        $this->add_group_control(
            Group_Control_Border::get_type(), 
            [
                'name'          => 'premium_pricing_icon_inner_border',
                'selector'      => '{{WRAPPER}} .premium-pricing-icon-container i, {{WRAPPER}} .premium-pricing-icon',
            ]
        );
        
        $this->add_control('premium_pricing_icon_inner_radius',
            [
                'label'         => __('Border Radius', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SLIDER,
                'size_units'    => ['px', '%' , 'em'],
                'default'       => [
                    'size'  => 100,
                    'unit'  => 'px'
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-pricing-icon-container i, {{WRAPPER}} .premium-pricing-icon' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
                'separator'     => 'after'
            ]
        );
        
        $this->add_control('premium_pricing_icon_container_heading',
            [
                'label'         => __('Container', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::HEADING,
            ]
        );
        
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'              => 'premium_pricing_table_icon_background',
                'types'             => [ 'classic' , 'gradient' ],
                'selector'          => '{{WRAPPER}} .premium-pricing-icon-container',
            ]
        );
        
        $this->add_group_control(
            Group_Control_Border::get_type(), 
            [
                'name'          => 'premium_pricing_icon_border',
                'selector'      => '{{WRAPPER}} .premium-pricing-icon-container',
            ]
        );
        
        $this->add_control('premium_pricing_icon_border_radius',
            [
                'label'         => __('Border Radius', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SLIDER,
                'size_units'    => ['px', '%' ,'em'],
                'selectors'     => [
                    '{{WRAPPER}} .premium-pricing-icon-container' => 'border-radius: {{SIZE}}{{UNIT}};'
                ]
            ]
        );
        
        $this->add_responsive_control('premium_pricing_icon_margin',
            [
                'label'         => __('Margin', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::DIMENSIONS,
                'size_units'    => ['px', 'em', '%'],
                'default'       => [
                    'top'   => 50,
                    'right' => 0,
                    'bottom'=> 20,
                    'left'  => 0,
                    'unit'  => 'px',
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-pricing-icon-container' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ]
            ]      
        );
        
        /*Icon Padding*/
        $this->add_responsive_control('premium_pricing_icon_padding',
                [
                    'label'         => __('Padding', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::DIMENSIONS,
                    'size_units'    => ['px', 'em', '%'],
                    'default'       => [
                        'top'   => 0,
                        'right' => 0,
                        'bottom'=> 0,
                        'left'  => 0,
                        'unit'  => 'px',
                    ],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-pricing-icon-container' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ]
            ]      
        );
        
        $this->end_controls_section();
        
        $this->start_controls_section('premium_pricing_title_style_settings',
            [
                'label'         => __('Title', 'premium-addons-for-elementor'),
                'tab'           => Controls_Manager::TAB_STYLE,
                'condition'     => [
                    'premium_pricing_table_title_switcher'  => 'yes',
                ]
            ]
        );
        
        $this->add_control('premium_pricing_title_color',
            [
                'label'         => __('Color', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::COLOR,
                'scheme'        => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_1,
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-pricing-table-title'  => 'color: {{VALUE}};'
                ]
            ]
        );
    
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'          => 'title_typo',
                'scheme'        => Scheme_Typography::TYPOGRAPHY_1,
                'selector'      => '{{WRAPPER}} .premium-pricing-table-title',
            ]
        );
        
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'              => 'premium_pricing_table_title_background',
                'types'             => [ 'classic' , 'gradient' ],
                'selector'          => '{{WRAPPER}} .premium-pricing-table-title',
            ]
        );
        
        $this->add_responsive_control('premium_pricing_title_margin',
            [
                'label'         => __('Margin', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::DIMENSIONS,
                'size_units'    => ['px', 'em', '%'],
                'default'       => [
                    'top'   => 0,
                    'right' => 0,
                    'bottom'=> 0,
                    'left'  => 0,
                    'unit'  => 'px',
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-pricing-table-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ]
            ]      
        );
        
        $this->add_responsive_control('premium_pricing_title_padding',
            [
                'label'         => __('Padding', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::DIMENSIONS,
                'size_units'    => ['px', 'em', '%'],
                'default'       => [
                    'top'   => 0,
                    'right' => 0,
                    'bottom'=> 20,
                    'left'  => 0,
                    'unit'  => 'px',
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-pricing-table-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ]
            ]      
        );

        $this->end_controls_section();
        
        $this->start_controls_section('premium_pricing_price_style_settings',
            [
                'label'         => __('Price', 'premium-addons-for-elementor'),
                'tab'           => Controls_Manager::TAB_STYLE,
                'condition'     => [
                    'premium_pricing_table_price_switcher'  => 'yes',
                ]
            ]
        );

        $this->add_control('premium_pricing_slashed_price_heading',
            [
                'label'         => __('Slashed Price', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::HEADING,
            ]
        );
    
        $this->add_control('premium_pricing_slashed_price_color',
            [
                'label'         => __('Color', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::COLOR,
                'scheme'        => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_1,
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-pricing-slashed-price-value'  => 'color: {{VALUE}};'
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'          => 'slashed_price_typo',
                'scheme'        => Scheme_Typography::TYPOGRAPHY_1,
                'selector'      => '{{WRAPPER}} .premium-pricing-slashed-price-value',
            ]
        );
        
        $this->add_responsive_control('premium_pricing_slashed_price_margin',
                [
                    'label'             => __('Margin', 'premium-addons-for-elementor'),
                    'type'              => Controls_Manager::DIMENSIONS,
                    'size_units'        => ['px', 'em', '%'],
                    'selectors'         => [
                    '{{WRAPPER}} .premium-pricing--slashed-price-value' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]      
        );
        
        $this->add_control('premium_pricing_currency_heading',
            [
                'label'         => __('Currency', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::HEADING,
            ]
        );
       
        $this->add_control('premium_pricing_currency_color',
            [
                'label'         => __('Color', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::COLOR,
                'scheme'        => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_1,
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-pricing-price-currency'  => 'color: {{VALUE}};'
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [   
                'label'         => __('Typography', 'premium-addons-for-elementor'),
                'name'          => 'currency_typo',
                'scheme'        => Scheme_Typography::TYPOGRAPHY_1,
                'selector'      => '{{WRAPPER}} .premium-pricing-price-currency',
            ]
        );
        
        $this->add_responsive_control('premium_pricing_currency_align',
            [
                'label'         => __( 'Vertical Align', 'premium-addons-for-elementor' ),
                'type'          => Controls_Manager::CHOOSE,
                'options'       => [
                    'top'      => [
                        'title'=> __( 'Top', 'premium-addons-for-elementor' ),
                        'icon' => 'fa fa-long-arrow-up',
                    ],
                    'unset'    => [
                        'title'=> __( 'Unset', 'premium-addons-for-elementor' ),
                        'icon' => 'fa fa-align-justify',
                    ],
                    'bottom'     => [
                        'title'=> __( 'Bottom', 'premium-addons-for-elementor' ),
                        'icon' => 'fa fa-long-arrow-down',
                    ],
                ],
                'default'       => 'unset',
                'selectors'     => [
                    '{{WRAPPER}} .premium-pricing-price-currency' => 'vertical-align: {{VALUE}};',
                ],
                'label_block'   => false
            ]
        );
        
        $this->add_responsive_control('premium_pricing_currency_margin',
                [
                    'label'             => __('Margin', 'premium-addons-for-elementor'),
                    'type'              => Controls_Manager::DIMENSIONS,
                    'size_units'        => ['px', 'em', '%'],
                    'selectors'         => [
                    '{{WRAPPER}} .premium-pricing-price-currency' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    'separator'     => 'after'
                ]
            ]      
        );
        
        
        $this->add_control('premium_pricing_price_heading',
                [
                    'label'         => __('Price', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::HEADING,
                ]
                );
        
        /*Price Color*/
        $this->add_control('premium_pricing_price_color',
                [
                    'label'         => __('Color', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::COLOR,
                    'scheme'        => [
                        'type'  => Scheme_Color::get_type(),
                        'value' => Scheme_Color::COLOR_1,
                    ],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-pricing-price-value'  => 'color: {{VALUE}};'
                        ],
                    'separator'     => 'before'
                    ]
                );
        
        /*Price Typo*/
        $this->add_group_control(
            Group_Control_Typography::get_type(),
                [
                    'label'         => __('Typography', 'premium-addons-for-elementor'),
                    'name'          => 'price_typo',
                    'scheme'        => Scheme_Typography::TYPOGRAPHY_1,
                    'selector'      => '{{WRAPPER}} .premium-pricing-price-value',
                    ]
                );
        
        $this->add_responsive_control('premium_pricing_price_margin',
                [
                    'label'             => __('Margin', 'premium-addons-for-elementor'),
                    'type'              => Controls_Manager::DIMENSIONS,
                    'size_units'        => ['px', 'em', '%'],
                    'selectors'         => [
                    '{{WRAPPER}} .premium-pricing-price-value' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]      
        );
        
        $this->add_control('premium_pricing_sep_heading',
                [
                    'label'         => __('Divider', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::HEADING,
                ]
                );
        
        /*Separator Color*/
        $this->add_control('premium_pricing_sep_color',
                [
                    'label'         => __('Color', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::COLOR,
                    'scheme'        => [
                        'type'  => Scheme_Color::get_type(),
                        'value' => Scheme_Color::COLOR_1,
                    ],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-pricing-price-separator'  => 'color: {{VALUE}};'
                        ],
                    'separator'     => 'before'
                    ]
                );
        
        /*Separator Typo*/
        $this->add_group_control(
            Group_Control_Typography::get_type(),
                [
                    'label'         => __('Typography', 'premium-addons-for-elementor'),
                    'name'          => 'separator_typo',
                    'scheme'        => Scheme_Typography::TYPOGRAPHY_1,
                    'selector'      => '{{WRAPPER}} .premium-pricing-price-separator',
                ]
            );
        
        $this->add_responsive_control('premium_pricing_sep_margin',
                [
                    'label'             => __('Margin', 'premium-addons-for-elementor'),
                    'type'              => Controls_Manager::DIMENSIONS,
                    'size_units'        => ['px', 'em', '%'],
                    'default'       => [
                        'top'   => 0,
                        'right' => 0,
                        'bottom'=> 20,
                        'left'  => -15,
                        'unit'  => 'px',
                    ],
                    'selectors'         => [
                    '{{WRAPPER}} .premium-pricing-price-separator' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]      
        );
        
        $this->add_control('premium_pricing_dur_heading',
                [
                    'label'         => __('Duration', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::HEADING,
                ]
                );
        
        /*Duration Color*/
        $this->add_control('premium_pricing_dur_color',
                [
                    'label'         => __('Color', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::COLOR,
                    'scheme'        => [
                        'type'  => Scheme_Color::get_type(),
                        'value' => Scheme_Color::COLOR_1,
                    ],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-pricing-price-duration'  => 'color: {{VALUE}};'
                        ],
                    'separator'     => 'before'
                    ]
                );
        
        /*Duration Typography*/
        $this->add_group_control(
            Group_Control_Typography::get_type(),
                [
                    'label'         => __('Typography', 'premium-addons-for-elementor'),
                    'name'          => 'duration_typo',
                    'scheme'        => Scheme_Typography::TYPOGRAPHY_1,
                    'selector'      => '{{WRAPPER}} .premium-pricing-price-duration',
                ]
            );
        
        $this->add_responsive_control('premium_pricing_dur_margin',
                [
                    'label'             => __('Margin', 'premium-addons-for-elementor'),
                    'type'              => Controls_Manager::DIMENSIONS,
                    'size_units'        => ['px', 'em', '%'],
                    'selectors'         => [
                    '{{WRAPPER}} .premium-pricing-price-duration' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    'separator'     => 'after'
                ]
            ]      
        );
        
        $this->add_control('premium_pricing_price_container_heading',
                [
                    'label'         => __('Container', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::HEADING,
                ]
                );
        
        /*Price Background*/
        $this->add_group_control(
            Group_Control_Background::get_type(),
                [
                    'name'              => 'premium_pricing_table_price_background',
                    'types'             => [ 'classic' , 'gradient' ],
                    'selector'          => '{{WRAPPER}} .premium-pricing-price-container',
                    ]
                );
        
        /*Price Margin*/
        $this->add_responsive_control('premium_pricing_price_container_margin',
                [
                    'label'             => __('Margin', 'premium-addons-for-elementor'),
                    'type'              => Controls_Manager::DIMENSIONS,
                    'size_units'        => ['px', 'em', '%'],
                    'default'           => [
                        'top'       => 16,
                        'right'     => 0,
                        'bottom'    => 16,
                        'left'      => 0,
                        'unit'      => 'px',
                    ],
                    'selectors'         => [
                    '{{WRAPPER}} .premium-pricing-price-container' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]      
        );
        
        /*Price Padding*/
        $this->add_responsive_control('premium_pricing_price_padding',
                [
                    'label'             => __('Padding', 'premium-addons-for-elementor'),
                    'type'              => Controls_Manager::DIMENSIONS,
                    'size_units'        => ['px', 'em', '%'],
                    'selectors'         => [
                    '{{WRAPPER}} .premium-pricing-price-container' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]      
        );
        
        $this->end_controls_section();
        
        $this->start_controls_section('premium_pricing_list_style_settings',
            [
                'label'         => __('Features', 'premium-addons-for-elementor'),
                'tab'           => Controls_Manager::TAB_STYLE,
                'condition'     => [
                    'premium_pricing_table_list_switcher'  => 'yes',
                ]
            ]
        );
        
        $this->add_control('premium_pricing_features_text_heading',
            [
                'label'         => __('Text', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::HEADING,
            ]
        );
        
        $this->add_control('premium_pricing_list_text_color',
            [
                'label'         => __('Color', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::COLOR,
                'scheme'        => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_2,
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-pricing-list-span'  => 'color: {{VALUE}};'
                ]
            ]
        );
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'          => 'list_typo',
                'scheme'        => Scheme_Typography::TYPOGRAPHY_1,
                'selector'      => '{{WRAPPER}} .premium-pricing-list .premium-pricing-list-span',
            ]
        );
        
        $this->add_control('premium_pricing_features_icon_heading',
            [
                'label'         => __('Icon', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::HEADING,
                'separator'     => 'before'
            ]
        );
        
        $this->add_control('premium_pricing_list_icon_color',
            [
                'label'         => __('Color', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::COLOR,
                'scheme'        => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_1,
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-pricing-feature-icon'  => 'color: {{VALUE}};'
                ]
            ]
        );
        
        $this->add_responsive_control('premium_pricing_list_icon_size',
            [
                'label'         => __('Size', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SLIDER,
                'selectors'     => [
                    '{{WRAPPER}} .premium-pricing-list i' => 'font-size: {{SIZE}}px',
                    '{{WRAPPER}} .premium-pricing-list svg' => 'width: {{SIZE}}px; height: {{SIZE}}px',
                ]
            ]
        );
        
        $this->add_responsive_control('premium_pricing_list_icon_spacing',
            [
                'label'         => __('Spacing', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SLIDER,
                'default'       => [
                    'size'  => 5
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-pricing-feature-icon' => 'margin-right: {{SIZE}}px',
                ],
            ]
        );
        
        $this->add_responsive_control('premium_pricing_list_item_margin',
            [
                'label'         => __('Vertical Spacing', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SLIDER,
                'selectors'     => [
                    '{{WRAPPER}} .premium-pricing-list .premium-pricing-list-item' => 'margin-bottom: {{SIZE}}px;'
                ],
                'separator'     => 'after'
            ]
        );
        
        $this->add_control('premium_pricing_features_container_heading',
            [
                'label'         => __('Container', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::HEADING,
            ]
        );
        
        $this->add_group_control(
            Group_Control_Background::get_type(),
                [
                    'name'              => 'premium_pricing_list_background',
                    'types'             => [ 'classic' , 'gradient' ],
                    'selector'          => '{{WRAPPER}} .premium-pricing-list-container',
                    ]
                );
        
        /*List Border*/
        $this->add_group_control(
            Group_Control_Border::get_type(), 
                [
                    'name'          => 'premium_pricing_list_border',
                    'selector'      => '{{WRAPPER}} .premium-pricing-list-container',
                ]
                );
        
        /*List Border Radius*/
        $this->add_control('premium_pricing_list_border_radius',
                [
                    'label'         => __('Border Radius', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::SLIDER,
                    'size_units'    => ['px', 'em' , '%'],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-pricing-list-container' => 'border-radius: {{SIZE}}{{UNIT}};'
                    ]
                ]
                );
        
        /*List Margin*/
        $this->add_responsive_control('premium_pricing_list_margin',
                [
                    'label'         => __('Margin', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::DIMENSIONS,
                    'size_units'    => ['px', 'em', '%'],
                    'default'           => [
                        'top'       => 30,
                        'right'     => 0,
                        'bottom'    => 30,
                        'left'      => 0,
                        'unit'      => 'px',
                    ],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-pricing-list-container' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                    ]
                ]);
        
        /*List Padding*/
        $this->add_responsive_control('premium_pricing_list_padding',
                [
                    'label'         => __('Padding', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::DIMENSIONS,
                    'size_units'    => ['px', 'em', '%'],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-pricing-list-container' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                    ]
                ]);
        
        $this->end_controls_section();

        $this->start_controls_section('tooltips_style',
            [
                'label'         => __('Tooltips', 'premium-addons-for-elementor'),
                'tab'           => Controls_Manager::TAB_STYLE,
                'condition'     => [
                    'premium_pricing_table_list_switcher'  => 'yes',
                ]
            ]
        );

        $this->add_responsive_control('tooltips_align',
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
                    '{{WRAPPER}} .premium-pricing-list-tooltip' => 'text-align: {{VALUE}}',
                ],
            ]
        );

        $this->add_responsive_control('tooltips_width',
            [
                'label'         => __('Width', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SLIDER,
                'range'         => [
                    'px'    => [
                        'min'   => 1,
                        'min'   => 400,
                    ]
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-pricing-list-tooltip' => 'min-width: {{SIZE}}px;'
                ]
            ]
        );

        $this->add_control('tooltips_color',
            [
                'label'         => __('Color', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::COLOR,
                'selectors'     => [
                    '{{WRAPPER}} .premium-pricing-list-tooltip'  => 'color: {{VALUE}};'
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'          => 'tooltips_typo',
                'scheme'        => Scheme_Typography::TYPOGRAPHY_1,
                'selector'      => '{{WRAPPER}} .premium-pricing-list-tooltip',
            ]
        );

        $this->add_control('tooltips_background_color',
            [
                'label'         => __('Background Color', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::COLOR,
                'selectors'     => [
                    '{{WRAPPER}} .premium-pricing-list-tooltip'  => 'background-color: {{VALUE}};'
                ]
            ]
        );

        $this->add_control('tooltips_border_color',
            [
                'label'         => __('Border Color', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::COLOR,
                'selectors'     => [
                    '{{WRAPPER}} .list-item-tooltip'  => 'border-color: {{VALUE}};'
                ]
            ]
        );

        $this->end_controls_section();
        
        $this->start_controls_section('premium_pricing_description_style_settings',
            [
                'label'         => __('Description', 'premium-addons-for-elementor'),
                'tab'           => Controls_Manager::TAB_STYLE,
                'condition'     => [
                    'premium_pricing_table_description_switcher'  => 'yes',
                ]
            ]
        );
        
        $this->add_control('premium_pricing_desc_text_heading',
            [
                'label'         => __('Text', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::HEADING,
            ]
        );
        
        $this->add_control('premium_pricing_desc_color',
            [
                'label'         => __('Color', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::COLOR,
                'scheme'        => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_2,
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-pricing-description-container'  => 'color: {{VALUE}};'
                ]
            ]
        );
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'          => 'description_typo',
                'scheme'        => Scheme_Typography::TYPOGRAPHY_1,
                'selector'      => '{{WRAPPER}} .premium-pricing-description-container',
            ]
        );
        
        $this->add_control('premium_pricing_desc_container_heading',
            [
                'label'         => __('Container', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::HEADING,
            ]
        );
        
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'              => 'premium_pricing_table_desc_background',
                'types'             => [ 'classic' , 'gradient' ],
                'selector'          => '{{WRAPPER}} .premium-pricing-description-container',
            ]
        );
        
        $this->add_responsive_control('premium_pricing_desc_margin',
                [
                    'label'             => __('Margin', 'premium-addons-for-elementor'),
                    'type'              => Controls_Manager::DIMENSIONS,
                    'size_units'        => ['px', 'em', '%'],
                    'default'           => [
                        'top'       => 16,
                        'right'     => 0,
                        'bottom'    => 16,
                        'left'      => 0,
                        'unit'      => 'px',
                    ],
                    'selectors'         => [
                    '{{WRAPPER}} .premium-pricing-description-container' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]      
        );
        
        $this->add_responsive_control('premium_pricing_desc_padding',
                [
                    'label'             => __('Padding', 'premium-addons-for-elementor'),
                    'type'              => Controls_Manager::DIMENSIONS,
                    'size_units'        => ['px', 'em', '%'],
                    'selectors'         => [
                    '{{WRAPPER}} .premium-pricing-description-container' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]      
        );
        
        $this->end_controls_section();
        
        $this->start_controls_section('premium_pricing_button_style_settings',
            [
                'label'         => __('Button', 'premium-addons-for-elementor'),
                'tab'           => Controls_Manager::TAB_STYLE,
                'condition'     => [
                    'premium_pricing_table_button_switcher'  => 'yes',
                ]
            ]
        );
        
        $this->add_control('premium_pricing_button_color',
            [
                'label'         => __('Color', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::COLOR,
                'scheme'        => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_2,
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-pricing-price-button'  => 'color: {{VALUE}};'
                ]
            ]
        );
        
        $this->add_control('premium_pricing_button_hover_color',
            [
                'label'         => __('Hover Text Color', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::COLOR,
                'scheme'        => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_2,
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-pricing-price-button:hover'  => 'color: {{VALUE}};'
                ]
            ]
        );
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'          => 'button_typo',
                'scheme'        => Scheme_Typography::TYPOGRAPHY_1,
                'selector'      => '{{WRAPPER}} .premium-pricing-price-button',
            ]
        );
        
        $this->start_controls_tabs('premium_pricing_table_button_style_tabs');
        
        $this->start_controls_tab('premium_pricing_table_button_style_normal',
            [
                'label'         => __('Normal', 'premium-addons-for-elementor'),
            ]
        );
        
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'              => 'premium_pricing_table_button_background',
                'types'             => [ 'classic' , 'gradient' ],
                'selector'          => '{{WRAPPER}} .premium-pricing-price-button',
            ]
        );
        
        $this->add_group_control(
            Group_Control_Border::get_type(), 
            [
                'name'          => 'premium_pricing_table_button_border',
                'selector'      => '{{WRAPPER}} .premium-pricing-price-button',
            ]
        );
        
        $this->add_control('premium_pricing_table_box_button_radius',
            [
                'label'         => __('Border Radius', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SLIDER,
                'size_units'    => ['px', 'em' , '%'],
                'selectors'     => [
                    '{{WRAPPER}} .premium-pricing-price-button' => 'border-radius: {{SIZE}}{{UNIT}};'
                ]
            ]
        );
        
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'label'         => __('Shadow','premium-addons-for-elementor'),
                'name'          => 'premium_pricing_table_button_box_shadow',
                'selector'      => '{{WRAPPER}} .premium-pricing-price-button',
            ]
        );
        
        $this->add_responsive_control('premium_pricing_button_margin',
            [
                'label'         => __('Margin', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::DIMENSIONS,
                'size_units'    => ['px', 'em', '%'],
                'selectors'     => [
                    '{{WRAPPER}} .premium-pricing-price-button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ]
            ]
        );
        
        $this->add_responsive_control('premium_pricing_button_padding',
            [
                'label'         => __('Padding', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::DIMENSIONS,
                'size_units'    => ['px', 'em', '%'],
                'default'           => [
                    'top'       => 20,
                    'right'     => 0,
                    'bottom'    => 20,
                    'left'      => 0,
                    'unit'      => 'px',
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-pricing-price-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ]
            ]
        );
        
        $this->end_controls_tab();

        $this->start_controls_tab('premium_pricing_table_button_style_hover',
            [
                'label'         => __('Hover', 'premium-addons-for-elementor'),
            ]
        );
        
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'              => 'premium_pricing_table_button_background_hover',
                'types'             => [ 'classic' , 'gradient' ],
                'selector'          => '{{WRAPPER}} .premium-pricing-price-button:hover',
            ]
        );
        
        $this->add_group_control(
            Group_Control_Border::get_type(), 
            [
                'name'          => 'premium_pricing_table_button_border_hover',
                'selector'      => '{{WRAPPER}} .premium-pricing-price-button:hover',
            ]
        );
        
        $this->add_control('premium_pricing_table_button_border_radius_hover',
            [
                'label'         => __('Border Radius', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SLIDER,
                'size_units'    => ['px', 'em' , '%' ],                    
                'selectors'     => [
                    '{{WRAPPER}} .premium-pricing-price-button:hover' => 'border-radius: {{SIZE}}{{UNIT}};'
                ]
            ]
        );
        
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'label'         => __('Shadow','premium-addons-for-elementor'),
                'name'          => 'premium_pricing_table_button_shadow_hover',
                'selector'      => '{{WRAPPER}} .premium-pricing-price-button:hover',
            ]
        );
        
        $this->add_responsive_control('premium_pricing_button_margin_hover',
            [
                'label'         => __('Margin', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::DIMENSIONS,
                'size_units'    => ['px', 'em', '%'],
                'selectors'     => [
                    '{{WRAPPER}} .premium-pricing-price-button:hover' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ]
            ]
        );
        
        $this->add_responsive_control('premium_pricing_button_padding_hover',
                [
                    'label'         => __('Padding', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::DIMENSIONS,
                    'default'           => [
                        'top'       => 20,
                        'right'     => 0,
                        'bottom'    => 20,
                        'left'      => 0,
                        'unit'      => 'px',
                    ],
                    'size_units'    => ['px', 'em', '%'],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-pricing-price-button:hover' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                    ]
                ]);
        
        
        $this->end_controls_tab();
        
        $this->end_controls_tabs();

        $this->end_controls_section();
        
        $this->start_controls_section('premium_pricing_table_badge_style',
            [
                'label'         => __('Ribbon', 'premium-addons-for-elementor'),
                'tab'           => Controls_Manager::TAB_STYLE,
                'condition'     => [
                    'premium_pricing_table_badge_switcher'  => 'yes'
                ]
            ]
        );
        
        $this->add_control('premium_pricing_badge_text_color',
            [
                'label'         => __('Text Color', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::COLOR,
                'scheme'        => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_2,
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-pricing-badge-container .corner span'  => 'color: {{VALUE}};'
                ]
            ]
        );
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'          => 'badge_text_typo',
                'scheme'        => Scheme_Typography::TYPOGRAPHY_1,
                'selector'      => '{{WRAPPER}} .premium-pricing-badge-container .corner span',
            ]
        );
        
        $this->add_control('premium_pricing_badge_left_color',
            [
                'label'         => __('Background Color', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::COLOR,
                'scheme'        => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_1,
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-badge-triangle.premium-badge-left .corner'  => 'border-top-color: {{VALUE}}',
                    '{{WRAPPER}} .premium-badge-triangle.premium-badge-right .corner'  => 'border-right-color: {{VALUE}}'
                ],
                'condition'     => [
                    'ribbon_type'   => 'triangle'
                ]
            ]
        );
        
        $this->add_control('ribbon_background',
            [
                'label'         => __('Background Color', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::COLOR,
                'scheme'        => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_1,
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-badge-circle, {{WRAPPER}} .premium-badge-stripe .corner, {{WRAPPER}} .premium-badge-flag .corner'  => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .premium-badge-flag .corner::before'  => 'border-left: 8px solid {{VALUE}}'
                ],
                'condition'     => [
                    'ribbon_type!'   => 'triangle'
                ]
            ]
        );
        
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'          => 'ribbon_shadow',
                'selector'      => '{{WRAPPER}} .premium-badge-circle, {{WRAPPER}} .premium-badge-stripe .corner, {{WRAPPER}} .premium-badge-flag .corner',
                'condition'     => [
                    'ribbon_type!'   => 'triangle'
                ]
            ]
        );
        
        $this->end_controls_section();
        
        $this->start_controls_section('premium_pricing_box_style_settings',
            [
                'label'         => __('Box Settings', 'premium-addons-for-elementor'),
                'tab'           => Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->start_controls_tabs('premium_pricing_table_box_style_tabs');
        
        $this->start_controls_tab('premium_pricing_table_box_style_normal',
            [
                'label'         => __('Normal', 'premium-addons-for-elementor'),
            ]
        );
        
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'              => 'premium_pricing_table_box_background',
                'types'             => [ 'classic' , 'gradient' ],
                'selector'          => '{{WRAPPER}} .premium-pricing-table-container',
            ]
        );
        
        $this->add_group_control(
            Group_Control_Border::get_type(), 
            [
                'name'          => 'premium_pricing_table_box_border',
                'selector'      => '{{WRAPPER}} .premium-pricing-table-container',
            ]
        );
        
        $this->add_control('premium_pricing_table_box_border_radius',
            [
                'label'         => __('Border Radius', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SLIDER,
                'size_units'    => ['px', '%' ,'em'],
                'selectors'     => [
                    '{{WRAPPER}} .premium-pricing-table-container' => 'border-radius: {{SIZE}}{{UNIT}};'
                ]
            ]
        );
        
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'label'         => __('Shadow','premium-addons-for-elementor'),
                'name'          => 'premium_pricing_table_box_shadow',
                'selector'      => '{{WRAPPER}} .premium-pricing-table-container',
            ]
        );
        
        $this->add_responsive_control('premium_pricing_box_margin',
            [
                'label'         => __('Margin', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::DIMENSIONS,
                'size_units'    => ['px', 'em', '%'],
                'selectors'     => [
                    '{{WRAPPER}} .premium-pricing-table-container' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ]
            ]
        );
        
        $this->add_responsive_control('premium_pricing_box_padding',
            [
                'label'         => __('Padding', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::DIMENSIONS,
                'size_units'    => ['px', 'em', '%'],
                'default'       => [
                    'top'   => 40,
                    'right' => 0,
                    'bottom'=> 0,
                    'left'  => 0,
                    'unit'  => 'px',
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-pricing-table-container' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ]
            ]
        );
        
        $this->end_controls_tab();

        $this->start_controls_tab('premium_pricing_table_box_style_hover',
            [
                'label'         => __('Hover', 'premium-addons-for-elementor'),
            ]
        );
        
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'              => 'premium_pricing_table_box_background_hover',
                'types'             => [ 'classic' , 'gradient' ],
                'selector'          => '{{WRAPPER}} .premium-pricing-table-container:hover',
            ]
        );
        
        $this->add_group_control(
            Group_Control_Border::get_type(), 
            [
                'name'          => 'premium_pricing_table_box_border_hover',
                'selector'      => '{{WRAPPER}} .premium-pricing-table-container:hover',
            ]
        );
        
        $this->add_control('premium_pricing_table_box_border_radius_hover',
            [
                'label'         => __('Border Radius', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SLIDER,
                'size_units'    => ['px', 'em' , '%' ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-pricing-table-container:hover' => 'border-radius: {{SIZE}}{{UNIT}};'
                ]
            ]
        );
        
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'label'         => __('Shadow','premium-addons-for-elementor'),
                'name'          => 'premium_pricing_table_box_shadow_hover',
                'selector'      => '{{WRAPPER}} .premium-pricing-table-container:hover',
            ]
        );
        
        $this->add_responsive_control('premium_pricing_box_margin_hover',
            [
                'label'         => __('Margin', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::DIMENSIONS,
                'size_units'    => ['px', 'em', '%'],
                'selectors'     => [
                    '{{WRAPPER}} .premium-pricing-table-container:hover' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ]
            ]
        );
        
        $this->add_responsive_control('premium_pricing_box_padding_hover',
            [
                'label'         => __('Padding', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::DIMENSIONS,
                'size_units'    => ['px', 'em', '%'],
                'default'       => [
                    'top'   => 40,
                    'right' => 0,
                    'bottom'=> 0,
                    'left'  => 0,
                    'unit'  => 'px',
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-pricing-table-container:hover' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ]
            ]
        );
        
        $this->end_controls_tab();
        
        $this->end_controls_tabs();
        
        $this->end_controls_section();
        
        
    }

    /**
	 * Render Pricing Table widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
    protected function render() {
        
        $settings = $this->get_settings_for_display();
        
        $this->add_inline_editing_attributes('title_text');
        
        $this->add_inline_editing_attributes('description_text', 'advanced');
        
        $this->add_inline_editing_attributes('button_text');
        
        $title_tag = $settings['premium_pricing_table_title_size'];
        
        $link_type = $settings['premium_pricing_table_button_url_type'];
        
        if( 'yes' === $settings['premium_pricing_table_badge_switcher'] ) {
            $badge_position = 'premium-badge-' .  $settings['premium_pricing_table_badge_position'];
        
            $badge_style = 'premium-badge-' .  $settings['ribbon_type'];
            
            $this->add_inline_editing_attributes('premium_pricing_table_badge_text');
            
            if( 'premium-badge-flag' === $badge_style )
                $badge_position   = '';
        }
        
        if( $link_type == 'link' ) {
            $link_url = get_permalink($settings['premium_pricing_table_button_link_existing_content']);
        } elseif ( $link_type == 'url' ) {
            $link_url = $settings['premium_pricing_table_button_link'];
        }
        
        if( 'yes' === $settings['premium_pricing_table_icon_switcher'] ) {
            $icon_type = $settings['icon_type'];

            if( 'icon' === $icon_type ) {
                if ( ! empty ( $settings['premium_pricing_table_icon_selection'] ) ) {
                    $this->add_render_attribute( 'icon', 'class', $settings['premium_pricing_table_icon_selection'] );
                    $this->add_render_attribute( 'icon', 'aria-hidden', 'true' );
                }
            
                $migrated = isset( $settings['__fa4_migrated']['premium_pricing_table_icon_selection_updated'] );
                $is_new = empty( $settings['premium_pricing_table_icon_selection'] ) && Icons_Manager::is_migration_allowed();
            } else {
                $this->add_render_attribute( 'pricing_lottie', [
                    'class' => [
                        'premium-pricing-icon',
                        'premium-lottie-animation'
                    ],
                    'data-lottie-url' => $settings['lottie_url'],
                    'data-lottie-loop' => $settings['lottie_loop'],
                    'data-lottie-reverse' => $settings['lottie_reverse']
                ]);
            }
            
        }
        
    ?>
    
    <div class="premium-pricing-table-container">
        <?php if( 'yes' === $settings['premium_pricing_table_badge_switcher'] ) : ?>
            <div class="premium-pricing-badge-container <?php echo esc_attr( $badge_position . ' ' . $badge_style ); ?>">
                <div class="corner"><span <?php echo $this->get_render_attribute_string('premium_pricing_table_badge_text'); ?>><?php echo $settings['premium_pricing_table_badge_text']; ?></span></div>
            </div>
        <?php endif;
        if( $settings['premium_pricing_table_icon_switcher'] === 'yes' ) : ?>
            <div class="premium-pricing-icon-container">
                <?php if( 'icon' === $icon_type ) : ?>
                    <?php if ( $is_new || $migrated ) :
                        Icons_Manager::render_icon( $settings['premium_pricing_table_icon_selection_updated'], [ 'aria-hidden' => 'true' ] );
                    else: ?>
                        <i <?php echo $this->get_render_attribute_string( 'icon' ); ?>></i>
                    <?php endif; ?>
                <?php else: ?>
                    <div <?php echo $this->get_render_attribute_string( 'pricing_lottie' ); ?>></div>
                <?php endif; ?>
            </div>
        <?php endif;
            if( $settings['premium_pricing_table_title_switcher'] === 'yes') : ?>
        <<?php echo $title_tag; ?> class="premium-pricing-table-title">
            <span <?php echo $this->get_render_attribute_string('title_text'); ?>><?php echo $settings['premium_pricing_table_title_text'];?></span>
            </<?php echo $title_tag;?>>
        <?php endif; ?>
        <?php if($settings['premium_pricing_table_price_switcher'] === 'yes') : ?>
        <div class="premium-pricing-price-container">
            <strike class="premium-pricing-slashed-price-value">
                <?php echo $settings['premium_pricing_table_slashed_price_value']; ?>
            </strike>
            <span class="premium-pricing-price-currency">
                <?php echo $settings['premium_pricing_table_price_currency']; ?>
            </span>
            <span class="premium-pricing-price-value">
                <?php echo $settings['premium_pricing_table_price_value']; ?>
            </span>    
            <span class="premium-pricing-price-separator">
                <?php echo $settings['premium_pricing_table_price_separator']; ?>    
            </span>
            <span class="premium-pricing-price-duration">
                <?php echo $settings['premium_pricing_table_price_duration']; ?>
            </span>
        </div>
        <?php endif;
        if( 'yes' === $settings['premium_pricing_table_list_switcher'] ) : ?>
            <div class="premium-pricing-list-container">
                <ul class="premium-pricing-list">
                    <?php foreach( $settings['premium_fancy_text_list_items'] as $index => $item ) :

                        $key = 'pricing_list_item_' . $index;
                        if( 'icon' === $item['icon_type'] ) :
                            $icon_migrated = isset( $item['__fa4_migrated']['premium_pricing_list_item_icon_updated'] );
                            $icon_new = empty( $item['premium_pricing_list_item_icon'] ) && Icons_Manager::is_migration_allowed();
                        endif; 

                        $this->add_render_attribute( $key, [
                            'class' => [
                                'elementor-repeater-item-' . $item['_id'],
                                'premium-pricing-list-item',
                            ]
                        ] );
                    ?>
                        <li <?php echo $this->get_render_attribute_string( $key ); ?>>
                            <?php if( 'icon' === $item['icon_type'] ) : ?>
                                <?php if ( $icon_new || $icon_migrated ) :
                                    Icons_Manager::render_icon( $item['premium_pricing_list_item_icon_updated'], [ 'class' => 'premium-pricing-feature-icon', 'aria-hidden' => 'true' ] );
                                else: ?>
                                    <i class="premium-pricing-feature-icon <?php echo $item['premium_pricing_list_item_icon']; ?>"></i>
                                <?php endif; ?>
                            <?php else:
                                $lottie_key = 'pricing_item_lottie_' . $index;
                                $this->add_render_attribute( $lottie_key, [
                                    'class' => [
                                        'premium-pricing-feature-icon',
                                        'premium-lottie-animation'
                                    ],
                                    'data-lottie-url' => $item['lottie_url'],
                                    'data-lottie-loop' => $item['lottie_loop'],
                                    'data-lottie-reverse' => $item['lottie_reverse']
                                ]);
                            ?>
                                <div <?php echo $this->get_render_attribute_string( $lottie_key ); ?>></div>
                            <?php endif; ?>
                            
                            <?php if ( ! empty( $item['premium_pricing_list_item_text'] ) ) :
                                $item_class = 'yes' === $item['premium_pricing_table_item_tooltip'] ? 'list-item-tooltip' : '';
                            ?>
                                <span class="premium-pricing-list-span <?php echo $item_class; ?>">
                                    <?php echo $item['premium_pricing_list_item_text'];
                                    if ( 'yes' === $item['premium_pricing_table_item_tooltip'] && ! empty( $item['premium_pricing_table_item_tooltip_text'] ) ) : ?>
                                        <span class="premium-pricing-list-tooltip"><?php echo esc_html( $item['premium_pricing_table_item_tooltip_text'] ); ?></span>
                                    <?php endif; ?>    
                                </span>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        <?php if($settings['premium_pricing_table_description_switcher'] == 'yes') : ?>
        <div class="premium-pricing-description-container">
            <div <?php echo $this->get_render_attribute_string('description_text'); ?>>
            <?php echo $settings['premium_pricing_table_description_text']; ?>
            </div>
        </div>
        <?php endif; ?>
        <?php if($settings['premium_pricing_table_button_switcher'] == 'yes') : ?>
        <div class="premium-pricing-button-container">
            <a class="premium-pricing-price-button" target="_<?php echo esc_attr( $settings['premium_pricing_table_button_link_target'] ); ?>" href="<?php echo $link_url; ?>">
                <span <?php echo $this->get_render_attribute_string('button_text'); ?>><?php echo $settings['premium_pricing_table_button_text']; ?></span>
            </a>
        </div>
        <?php endif; ?>
    </div>

    <?php
    }
    
    /**
	 * Render Pricing Table widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
    protected function content_template() {
        ?>
        <#
            
        view.addInlineEditingAttributes('title_text');
        
        view.addInlineEditingAttributes('description_text', 'advanced');
        
        view.addInlineEditingAttributes('button_text');
        
        var titleTag = settings.premium_pricing_table_title_size,
            linkType = settings.premium_pricing_table_button_url_type,
            linkURL = 'link' === linkType ? settings.premium_pricing_table_button_link_existing_content : settings.premium_pricing_table_button_link;

        if( 'yes' === settings.premium_pricing_table_icon_switcher ) {

            var iconType = settings.icon_type;

            if( 'icon' === iconType ) {
                var iconHTML = elementor.helpers.renderIcon( view, settings.premium_pricing_table_icon_selection_updated, { 'aria-hidden': true }, 'i' , 'object' ),
                    migrated = elementor.helpers.isIconMigrated( settings, 'premium_pricing_table_icon_selection_updated' );
            } else {

                view.addRenderAttribute( 'pricing_lottie', {
                    'class': [
                        'premium-pricing-icon',
                        'premium-lottie-animation'
                    ],
                    'data-lottie-url': settings.lottie_url,
                    'data-lottie-loop': settings.lottie_loop,
                    'data-lottie-reverse': settings.lottie_reverse,
                });
                
            }
            
        }
        
        if( 'yes' === settings.premium_pricing_table_badge_switcher ) {
            var badgePosition   = 'premium-badge-'  + settings.premium_pricing_table_badge_position,
                badgeStyle      = 'premium-badge-'  + settings.ribbon_type;
                
            view.addInlineEditingAttributes('premium_pricing_table_badge_text');
            
            if( 'premium-badge-flag' === badgeStyle )
                badgePosition   = '';
            
        }
        
        #>
        
        <div class="premium-pricing-table-container">
            <# if('yes' === settings.premium_pricing_table_badge_switcher ) { #>
                <div class="premium-pricing-badge-container {{ badgePosition }} {{ badgeStyle }}">
                    <div class="corner"><span {{{ view.getRenderAttributeString('premium_pricing_table_badge_text') }}}>{{{ settings.premium_pricing_table_badge_text }}}</span></div>
                </div>
            <# } #>
            <# if( 'yes' === settings.premium_pricing_table_icon_switcher ) { #>
                <div class="premium-pricing-icon-container">
                <# if( 'icon' === iconType ) { #>
                    <# if ( iconHTML && iconHTML.rendered && ( ! settings.premium_pricing_table_icon_selection || migrated ) ) { #>
                        {{{ iconHTML.value }}}
                    <# } else { #>
                        <i class="{{ settings.premium_pricing_table_icon_selection }}" aria-hidden="true"></i>
                    <# } #>
                <# } else { #>
                    <div {{{ view.getRenderAttributeString('pricing_lottie') }}}></div>
                <# } #>
                </div>
            <# } #>
            <# if('yes' === settings.premium_pricing_table_title_switcher ) { #>
                <{{{titleTag}}} class="premium-pricing-table-title"><span {{{ view.getRenderAttributeString('title_text') }}}>{{{ settings.premium_pricing_table_title_text }}}</span></{{{titleTag}}}>
            <# } #>
            
            <# if('yes' === settings.premium_pricing_table_price_switcher ) { #>
                <div class="premium-pricing-price-container">
                    <strike class="premium-pricing-slashed-price-value">{{{ settings.premium_pricing_table_slashed_price_value }}}</strike>
                    <span class="premium-pricing-price-currency">{{{ settings.premium_pricing_table_price_currency }}}</span>
                    <span class="premium-pricing-price-value">{{{ settings.premium_pricing_table_price_value }}}</span>
                    <span class="premium-pricing-price-separator">{{{ settings.premium_pricing_table_price_separator }}}</span>
                    <span class="premium-pricing-price-duration">{{{ settings.premium_pricing_table_price_duration }}}</span>
                </div>
            <# } #>
            <# if('yes' === settings.premium_pricing_table_list_switcher ) { #>
                <div class="premium-pricing-list-container">
                    <ul class="premium-pricing-list">
                    <# _.each( settings.premium_fancy_text_list_items, function( item, index ) {
                        
                        var key = 'pricing_list_item_' + index;

                        view.addRenderAttribute( key, 'class', [ 'elementor-repeater-item-' + item._id, 'premium-pricing-list-item' ] );

                        if( 'icon' === item.icon_type ) {
                            var listIconHTML = elementor.helpers.renderIcon( view, item.premium_pricing_list_item_icon_updated, { 'class': 'premium-pricing-feature-icon' , 'aria-hidden': true }, 'i' , 'object' ),
                                listIconMigrated = elementor.helpers.isIconMigrated( item, 'premium_pricing_list_item_icon_updated' );
                        }
                    #>
                        <li {{{ view.getRenderAttributeString( key ) }}}>
                            <# if( 'icon' === item.icon_type ) { #>
                                <# if ( listIconHTML && listIconHTML.rendered && ( ! item.premium_pricing_list_item_icon || listIconMigrated ) ) { #>
                                    {{{ listIconHTML.value }}}
                                <# } else { #>
                                    <i class="premium-pricing-feature-icon {{ item.premium_pricing_list_item_icon }}" aria-hidden="true"></i>
                                <# } #>
                            <# } else {
                                var lottieKey = 'pricing_item_lottie_' + index;

                                view.addRenderAttribute( lottieKey, {
                                    'class': [
                                        'premium-pricing-feature-icon',
                                        'premium-lottie-animation'
                                    ],
                                    'data-lottie-url': item.lottie_url,
                                    'data-lottie-loop': item.lottie_loop,
                                    'data-lottie-reverse': item.lottie_reverse,
                                });

                            #>
                                <div {{{ view.getRenderAttributeString( lottieKey ) }}}></div>
                            <# } #>
                            
                            <# if ( '' !== item.premium_pricing_list_item_text ) { 
                                var itemClass = 'yes' === item.premium_pricing_table_item_tooltip ? 'list-item-tooltip' : '';
                            #>
                                <span class="premium-pricing-list-span {{itemClass}}">{{{ item.premium_pricing_list_item_text }}}
                                <# if ( 'yes' === item.premium_pricing_table_item_tooltip && '' !== item.premium_pricing_table_item_tooltip_text ) { #>
                                    <span class="premium-pricing-list-tooltip">{{{ item.premium_pricing_table_item_tooltip_text }}}</span>
                                <# } #>
                                </span>
                            <# } #>
                        </li>
                    <# } ); #>
                    </ul>
                </div>
            <# } #>
            <# if('yes' === settings.premium_pricing_table_description_switcher ) { #>
                <div class="premium-pricing-description-container">
                    <div {{{ view.getRenderAttributeString('description_text') }}}>
                        {{{ settings.premium_pricing_table_description_text }}}
                    </div>
                </div>
            <# } #>
            <# if('yes' === settings.premium_pricing_table_button_switcher ) { #>
                <div class="premium-pricing-button-container">
                    <a class="premium-pricing-price-button" target="_{{ settings.premium_pricing_table_button_link_target }}" href="{{ linkURL }}">
                        <span {{{ view.getRenderAttributeString('button_text') }}}>{{{ settings.premium_pricing_table_button_text }}}</span>
                    </a>
                </div>
            <# } #>
        </div>
        <?php
    }
}