<?php 

/**
 * Premium Carousel.
 */
namespace PremiumAddons\Widgets;

// Elementor Classes.
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Scheme_Color;
use Elementor\Group_Control_Border;

// PremiumAddons Classes.
use PremiumAddons\Helper_Functions;
use PremiumAddons\Includes;

if( ! defined( 'ABSPATH' ) ) exit; // No access of directly access

/**
 * Class Premium_Carousel
 */
class Premium_Carousel extends Widget_Base {

    protected $templateInstance;

	public function getTemplateInstance() {
		return $this->templateInstance = Includes\premium_Template_Tags::getInstance();
	}

	public function get_name() {
		return 'premium-carousel-widget';
	}

	public function get_title() {
        return sprintf( '%1$s %2$s', Helper_Functions::get_prefix(), __('Carousel', 'premium-addons-for-elementor') );
	}

	public function get_icon() {
		return 'pa-carousel';
	}
    
    public function is_reload_preview_required() {
        return true;
    }
    
    public function get_style_depends() {
        return [
            'font-awesome-5-all',
            'premium-addons'
        ];
    }

	public function get_script_depends() {
		return [
            'jquery-slick',
            'premium-addons-js',
        ];
	}

	public function get_categories() {
		return [ 'premium-elements' ];
	}

    public function get_custom_help_url() {
		return 'https://premiumaddons.com/support/';
	}
    
    /**
	 * Register Carousel controls.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function _register_controls() {
		$this->start_controls_section('premium_carousel_global_settings',
			[
				'label'         => __( 'Carousel' , 'premium-addons-for-elementor' )
			]
		);
        
        $this->add_control('premium_carousel_content_type',
			[
				'label'			=> __( 'Content Type', 'premium-addons-for-elementor' ),
				'description'	=> __( 'How templates are selected', 'premium-addons-for-elementor' ),
				'type'			=> Controls_Manager::SELECT,
				'options'		=> [
					'select'        => __( 'Select Field', 'premium-addons-for-elementor' ),
					'repeater'		=> __( 'Repeater', 'premium-addons-for-elementor' )
				],
                'default'		=> 'select'
			]
		);

		$this->add_control('premium_carousel_slider_content',
		  	[
		     	'label'         => __( 'Templates', 'premium-addons-for-elementor' ),
		     	'description'	=> __( 'Slider content is a template which you can choose from Elementor library. Each template will be a slider content', 'premium-addons-for-elementor' ),
		     	'type'          => Controls_Manager::SELECT2,
		     	'options'       => $this->getTemplateInstance()->get_elementor_page_list(),
		     	'multiple'      => true,
                'label_block'   => true,
                'condition'     => [
                    'premium_carousel_content_type' => 'select'
                ]
		  	]
		);
        
        $repeater = new REPEATER();
        
        $repeater->add_control('premium_carousel_repeater_item',
            [
                'label'         => __( 'Content', 'premium-addons-for-elementor' ),
                'type'          => Controls_Manager::SELECT2,
                'label_block'   => true,
                'options'       => $this->getTemplateInstance()->get_elementor_page_list()
            ]
        );
        
        $this->add_control('premium_carousel_templates_repeater',
            [
                'label'         => __('Templates', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::REPEATER,
                'fields'        => $repeater->get_controls(),
                'condition'     => [
                    'premium_carousel_content_type' => 'repeater'
                ],
                'title_field'   => 'Template: {{{ premium_carousel_repeater_item }}}'
            ]
        );


		$this->add_control('premium_carousel_slider_type',
			[
				'label'			=> __( 'Type', 'premium-addons-for-elementor' ),
				'description'	=> __( 'Set a navigation type', 'premium-addons-for-elementor' ),
				'type'			=> Controls_Manager::SELECT,
				'options'		=> [
					'horizontal'	=> __( 'Horizontal', 'premium-addons-for-elementor' ),
					'vertical'		=> __( 'Vertical', 'premium-addons-for-elementor' )
				],
                'default'		=> 'horizontal'
			]
		);
        
        $this->add_control('premium_carousel_dot_navigation_show',
			[
				'label'			=> __( 'Dots', 'premium-addons-for-elementor' ),
				'description'	=> __( 'Enable or disable navigation dots', 'premium-addons-for-elementor' ),
				'type'			=> Controls_Manager::SWITCHER,
                'separator'     => 'before',
				'default'		=> 'yes'
			]
		);
        
        $this->add_control('premium_carousel_dot_position',
			[
				'label'			=> __( 'Position', 'premium-addons-for-elementor' ),
				'type'			=> Controls_Manager::SELECT,
				'default'		=> 'below',
				'options'		=> [
					'below'         => __( 'Below Slides', 'premium-addons-for-elementor' ),
					'above'         => __( 'On Slides', 'premium-addons-for-elementor' )
				],
                'condition'     => [
                    'premium_carousel_dot_navigation_show'  => 'yes'
                ]
			]
		);
        
        $this->add_responsive_control('premium_carousel_dot_offset',
			[
				'label'             => __( 'Horizontal Offset', 'premium-addons-for-elementor' ),
				'type'              => Controls_Manager::SLIDER,
                'size_units'        => ['px', 'em', '%'],
                'selectors'         => [
                    '{{WRAPPER}} .premium-carousel-dots-above ul.slick-dots' => 'left: {{SIZE}}{{UNIT}}',
                ],
                'condition'     => [
                    'premium_carousel_dot_navigation_show'  => 'yes',
                    'premium_carousel_dot_position'         => 'above'
                ]
			]
		);
        
        $this->add_responsive_control('premium_carousel_dot_voffset',
			[
				'label'             => __( 'Vertical Offset', 'premium-addons-for-elementor' ),
				'type'              => Controls_Manager::SLIDER,
                'size_units'        => ['px', 'em', '%'],
                'selectors'         => [
                    '{{WRAPPER}} .premium-carousel-dots-above ul.slick-dots' => 'top: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}} .premium-carousel-dots-below ul.slick-dots' => 'bottom: {{SIZE}}{{UNIT}}',
                ],
                'condition'     => [
                    'premium_carousel_dot_navigation_show'  => 'yes',
                ]
			]
		);
        
        $this->add_control('premium_carousel_navigation_effect',
			[
				'label' 		=> __( 'Ripple Effect', 'premium-addons-for-elementor' ),
				'description'	=> __( 'Enable a ripple effect when the active dot is hovered/clicked', 'premium-addons-for-elementor' ),
				'type'			=> Controls_Manager::SWITCHER,
                'prefix_class'  => 'premium-carousel-ripple-',
                'condition'		=> [
					'premium_carousel_dot_navigation_show' => 'yes'
				],
			]
		);
        
        $this->add_control('premium_carousel_navigation_show',
			[
				'label'			=> __( 'Arrows', 'premium-addons-for-elementor' ),
				'description'	=> __( 'Enable or disable navigation arrows', 'premium-addons-for-elementor' ),
				'type'			=> Controls_Manager::SWITCHER,
                'separator'     => 'before',
				'default'		=> 'yes'
			]
		);

		$this->add_control('premium_carousel_slides_to_show',
			[
				'label'			=> __( 'Appearance', 'premium-addons-for-elementor' ),
				'type'			=> Controls_Manager::SELECT,
				'default'		=> 'all',
                'separator'     => 'before',
				'options'		=> [
					'all'			=> __( 'All visible', 'premium-addons-for-elementor' ),
					'single'		=> __( 'One at a time', 'premium-addons-for-elementor' )
				]
			]
		);

		$this->add_control('premium_carousel_responsive_desktop',
			[
				'label'			=> __( 'Desktop Slides', 'premium-addons-for-elementor' ),
				'type'			=> Controls_Manager::NUMBER,
				'default'		=> 1
			]
		);

		$this->add_control('premium_carousel_responsive_tabs',
			[
				'label'			=> __( 'Tabs Slides', 'premium-addons-for-elementor' ),
				'type'			=> Controls_Manager::NUMBER,
				'default'		=> 1
			]
		);

		$this->add_control('premium_carousel_responsive_mobile',
			[
				'label'			=> __( 'Mobile Slides', 'premium-addons-for-elementor' ),
				'type'			=> Controls_Manager::NUMBER,
				'default'		=> 1
			]
		);

        $this->end_controls_section();
        
        $this->start_controls_section('premium_carousel_slides_settings',
			[
				'label' => __( 'Slides Settings' , 'premium-addons-for-elementor' )
			]
		);
        
		$this->add_control('premium_carousel_loop',
			[
				'label'			=> __( 'Infinite Loop', 'premium-addons-for-elementor' ),
				'type'			=> Controls_Manager::SWITCHER,
                'description'   => __( 'Restart the slider automatically as it passes the last slide', 'premium-addons-for-elementor' ),
				'default'		=> 'yes'
			]
		);

		$this->add_control('premium_carousel_fade',
			[
				'label'			=> __( 'Fade', 'premium-addons-for-elementor' ),
				'type'			=> Controls_Manager::SWITCHER,
                'description'   => __( 'Enable fade transition between slides', 'premium-addons-for-elementor' ),
                'condition'     => [
                    'premium_carousel_slider_type'      => 'horizontal',
                ]
			]
		);

		$this->add_control('premium_carousel_zoom',
			[
				'label'			=> __( 'Zoom Effect', 'premium-addons-for-elementor' ),
				'type'			=> Controls_Manager::SWITCHER,
				'condition'		=> [
					'premium_carousel_fade'	=> 'yes',
                    'premium_carousel_slider_type'      => 'horizontal',
				]
			]
		);

		$this->add_control('premium_carousel_speed',
			[
				'label'			=> __( 'Transition Speed', 'premium-addons-for-elementor' ),
				'description'	=> __( 'Set a navigation speed value. The value will be counted in milliseconds (ms)', 'premium-addons-for-elementor' ),
				'type'			=> Controls_Manager::NUMBER,
				'default'		=> 300
			]
		);

		$this->add_control('premium_carousel_autoplay',
			[
				'label'			=> __( 'Autoplay Slides‏', 'premium-addons-for-elementor' ),
				'description'	=> __( 'Slide will start automatically', 'premium-addons-for-elementor' ),
				'type'			=> Controls_Manager::SWITCHER,
				'default'		=> 'yes'
			]
		);

		$this->add_control('premium_carousel_autoplay_speed',
			[
				'label'			=> __( 'Autoplay Speed', 'premium-addons-for-elementor' ),
				'description'	=> __( 'Autoplay Speed means at which time the next slide should come. Set a value in milliseconds (ms)', 'premium-addons-for-elementor' ),
				'type'			=> Controls_Manager::NUMBER,
				'default'		=> 5000,
				'condition'		=> [
					'premium_carousel_autoplay' => 'yes'
				],
			]
		);

        $this->add_control('premium_carousel_animation_list', 
            [
                'label'         => __('Animations', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::HIDDEN,
                'render_type'   => 'template'
            ]
            );

		$this->add_control('premium_carousel_extra_class',
			[
				'label'			=> __( 'Extra Class', 'premium-addons-for-elementor' ),
				'type'			=> Controls_Manager::TEXT,
                'description'	=> __( 'Add extra class name that will be applied to the carousel, and you can use this class for your customizations.', 'premium-addons-for-elementor' ),
			]
		);

		$this->end_controls_section();
        
        $this->start_controls_section('premium-carousel-advance-settings',
			[
				'label'         => __( 'Additional Settings' , 'premium-addons-for-elementor' ),
			]
		);

		$this->add_control('premium_carousel_draggable_effect',
			[
				'label' 		=> __( 'Draggable Effect', 'premium-addons-for-elementor' ),
				'description'	=> __( 'Allow the slides to be dragged by mouse click', 'premium-addons-for-elementor' ),
				'type'			=> Controls_Manager::SWITCHER,
				'default'		=> 'yes'
			]
		);

		$this->add_control('premium_carousel_touch_move',
			[
				'label' 		=> __( 'Touch Move', 'premium-addons-for-elementor' ),
				'description'	=> __( 'Enable slide moving with touch', 'premium-addons-for-elementor' ),
				'type'			=> Controls_Manager::SWITCHER,
				'default'		=> 'yes'
			]
		);

		$this->add_control('premium_carousel_RTL_Mode',
			[
				'label' 		=> __( 'RTL Mode', 'premium-addons-for-elementor' ),
				'description'	=> __( 'Turn on RTL mode if your language starts from right to left', 'premium-addons-for-elementor' ),
				'type'			=> Controls_Manager::SWITCHER,
				'condition'		=> [
					'premium_carousel_slider_type!' => 'vertical'
				]
			]
		);

		$this->add_control('premium_carousel_adaptive_height',
			[
				'label' 		=> __( 'Adaptive Height', 'premium-addons-for-elementor' ),
				'description'	=> __( 'Adaptive height setting gives each slide a fixed height to avoid huge white space gaps', 'premium-addons-for-elementor' ),
				'type'			=> Controls_Manager::SWITCHER,
			]
		);

		$this->add_control('premium_carousel_pausehover',
			[
				'label' 		=> __( 'Pause on Hover', 'premium-addons-for-elementor' ),
				'description'	=> __( 'Pause the slider when mouse hover', 'premium-addons-for-elementor' ),
				'type'			=> Controls_Manager::SWITCHER,
			]
		);

		$this->add_control('premium_carousel_center_mode',
			[
				'label' 		=> __( 'Center Mode', 'premium-addons-for-elementor' ),
				'description'	=> __( 'Center mode enables a centered view with partial next/previous slides. Animations and all visible scroll type doesn\'t work with this mode', 'premium-addons-for-elementor' ),
				'type'			=> Controls_Manager::SWITCHER,
			]
		);

		$this->add_control('premium_carousel_space_btw_items',
			[
				'label' 		=> __( 'Slides\' Spacing', 'premium-addons-for-elementor' ),
                'description'   => __('Set a spacing value in pixels (px)', 'premium-addons-for-elementor'),
				'type'			=> Controls_Manager::NUMBER,
				'default'		=> '15'
			]
		);
        
        $this->add_control('premium_carousel_tablet_breakpoint',
			[
				'label' 		=> __( 'Tablet Breakpoint', 'premium-addons-for-elementor' ),
                'description'   => __('Sets the breakpoint between desktop and tablet devices. Below this breakpoint tablet layout will appear (Default: 1025px).', 'premium-addons-for-elementor'),
				'type'			=> Controls_Manager::NUMBER,
				'default'		=> 1025
			]
		);
        
        $this->add_control('premium_carousel_mobile_breakpoint',
			[
				'label' 		=> __( 'Mobile Breakpoint', 'premium-addons-for-elementor' ),
                'description'   => __('Sets the breakpoint between tablet and mobile devices. Below this breakpoint mobile layout will appear (Default: 768px).', 'premium-addons-for-elementor'),
				'type'			=> Controls_Manager::NUMBER,
				'default'		=> 768
			]
		);
        
        $this->end_controls_section();
        
        $this->start_controls_section('section_pa_docs',
            [
                'label'         => __('Helpful Documentations', 'premium-addons-for-elementor'),
            ]
        );
        
        $this->add_control('doc_1',
            [
                'type'            => Controls_Manager::RAW_HTML,
                'raw'             => sprintf( __( '%1$s Issue: I can see the first slide only » %2$s', 'premium-addons-for-elementor' ), '<a href="https://premiumaddons.com/docs/i-can-see-the-first-slide-only-in-carousel-widget/?utm_source=pa-dashboard&utm_medium=pa-editor&utm_campaign=pa-plugin" target="_blank" rel="noopener">', '</a>' ),
                'content_classes' => 'editor-pa-doc',
            ]
		);

		$this->add_control('doc_2',
            [
                'type'            => Controls_Manager::RAW_HTML,
                'raw'             => sprintf( __( '%1$s How to create an Elementor template to be used in Premium Carousel » %2$s', 'premium-addons-for-elementor' ), '<a href="https://premiumaddons.com/docs/how-to-create-elementor-template-to-be-used-with-premium-addons/?utm_source=pa-dashboard&utm_medium=pa-editor&utm_campaign=pa-plugin" target="_blank" rel="noopener">', '</a>' ),
                'content_classes' => 'editor-pa-doc',
            ]
		);
		
		$this->add_control('doc_3',
            [
                'type'            => Controls_Manager::RAW_HTML,
                'raw'             => sprintf( __( '%1$s I\'m not able to see Font Awesome icons in the widget » %2$s', 'premium-addons-for-elementor' ), '<a href="https://premiumaddons.com/docs/why-im-not-able-to-see-elementor-font-awesome-5-icons-in-premium-add-ons/?utm_source=papro-dashboard&utm_medium=papro-editor&utm_campaign=papro-plugin" target="_blank" rel="noopener">', '</a>' ),
                'content_classes' => 'editor-pa-doc',
            ]
        );

        $this->add_control('doc_4',
            [
                'type'            => Controls_Manager::RAW_HTML,
                'raw'             => sprintf( __( '%1$s How to add entrance animations to the elements inside Premium Carousel Widget » %2$s', 'premium-addons-for-elementor' ), '<a href="https://premiumaddons.com/docs/how-to-add-entrance-animations-to-elementor-elements-in-premium-carousel-widget/?utm_source=papro-dashboard&utm_medium=papro-editor&utm_campaign=papro-plugin" target="_blank" rel="noopener">', '</a>' ),
                'content_classes' => 'editor-pa-doc',
            ]
        );
        
        $this->end_controls_section();
        
		$this->start_controls_section('premium_carousel_navigation_arrows',
			[
				'label'         => __( 'Navigation Arrows', 'premium-addons-for-elementor' ),
				'tab' 			=> Controls_Manager::TAB_STYLE,
                'condition'     => [
                    'premium_carousel_navigation_show'  => 'yes'
                ]
			]
		);
        
        $this->add_control('premium_carousel_arrow_icon_next',
		    [
		        'label'         => __( 'Right Icon', 'premium-addons-for-elementor' ),
		        'type'          => Controls_Manager::CHOOSE,
		        'options'       => [
		            'right_arrow_bold'          => [
		                'icon' => 'fas fa-arrow-right',
		            ],
		            'right_arrow_long'          => [
		                'icon' => 'fas fa-long-arrow-alt-right',
		            ],
		            'right_arrow_long_circle' 	=> [
		                'icon' => 'fas fa-arrow-circle-right',
		            ],
		            'right_arrow_angle' 		=> [
		                'icon' => 'fas fa-angle-right',
		            ],
		            'right_arrow_chevron' 		=> [
		                'icon' => 'fas fa-chevron-right',
		            ]
		        ],
		        'default'       => 'right_arrow_angle',
		        'condition'		=> [
					'premium_carousel_navigation_show'  => 'yes',
					'premium_carousel_slider_type!'     => 'vertical'
				],
                'toggle'        => false
		    ]
		);

		$this->add_control('premium_carousel_arrow_icon_next_ver',
		    [
		        'label' 	=> __( 'Bottom Icon', 'premium-addons-for-elementor' ),
		        'type' 		=> Controls_Manager::CHOOSE,
		        'options' 	=> [
		            'right_arrow_bold'    		=> [
		                'icon' => 'fas fa-arrow-down',
		            ],
		            'right_arrow_long' 			=> [
		                'icon' => 'fas fa-long-arrow-alt-down',
		            ],
		            'right_arrow_long_circle' 	=> [
		                'icon' => 'fas fa-arrow-circle-down',
		            ],
		            'right_arrow_angle' 		=> [
		                'icon' => 'fas fa-angle-down',
		            ],
		            'right_arrow_chevron' 		=> [
		                'icon' => 'fas fa-chevron-down',
		            ]
		        ],
		        'default'		=> 'right_arrow_angle',
		        'condition'		=> [
					'premium_carousel_navigation_show'  => 'yes',
					'premium_carousel_slider_type'      => 'vertical',
				],
                'toggle'        => false
		    ]
		);
        
		$this->add_control('premium_carousel_arrow_icon_prev_ver',
		    [
		        'label'         => __( 'Top Icon', 'premium-addons-for-elementor' ),
		        'type'          => Controls_Manager::CHOOSE,
		        'options'       => [
		            'left_arrow_bold'    		=> [
		                'icon' => 'fas fa-arrow-up',
		            ],
		            'left_arrow_long' 			=> [
		                'icon' => 'fas fa-long-arrow-alt-up',
		            ],
		            'left_arrow_long_circle' 	=> [
		                'icon' => 'fas fa-arrow-circle-up',
		            ],
		            'left_arrow_angle' 		=> [
		                'icon' => 'fas fa-angle-up',
		            ],
		            'left_arrow_chevron' 		=> [
		                'icon' => 'fas fa-chevron-up',
		            ]
		        ],
		        'default'		=> 'left_arrow_angle',
		        'condition'		=> [
					'premium_carousel_navigation_show'  => 'yes',
					'premium_carousel_slider_type'      => 'vertical',
				],
                'toggle'        => false
		    ]
		);
        
		$this->add_control('premium_carousel_arrow_icon_prev',
		    [
		        'label'         => __( 'Left Icon', 'premium-addons-for-elementor' ),
		        'type'          => Controls_Manager::CHOOSE,
		        'options'       => [
		            'left_arrow_bold'    		=> [
		                'icon' => 'fas fa-arrow-left',
		            ],
		            'left_arrow_long' 			=> [
		                'icon' => 'fas fa-long-arrow-alt-left',
		            ],
		            'left_arrow_long_circle' 	=> [
		                'icon' => 'fas fa-arrow-circle-left',
		            ],
		            'left_arrow_angle' 		=> [
		                'icon' => 'fas fa-angle-left',
		            ],
		            'left_arrow_chevron' 		=> [
		                'icon' => 'fas fa-chevron-left',
		            ]
		        ],
		        'default'		=> 'left_arrow_angle',
		        'condition'		=> [
					'premium_carousel_navigation_show' => 'yes',
					'premium_carousel_slider_type!' => 'vertical',
				],
                'toggle'        => false
		    ]
		);
        
        $this->add_responsive_control('premium_carousel_arrow_size',
			[
				'label'         => __( 'Size', 'premium-addons-for-elementor' ),
				'type'          => Controls_Manager::SLIDER,
				'default'       => [
					'size' => 14,
				],
				'range'         => [
					'px' => [
						'min' => 0,
						'max' => 60
					],
				],
				'condition'		=> [
					'premium_carousel_navigation_show' => 'yes'
				],
				'selectors'     => [
					'{{WRAPPER}} .premium-carousel-wrapper .slick-arrow' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);
        
        $this->add_responsive_control('premium_carousel_arrow_position',
            [
                'label'         => __('Position (PX)', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SLIDER,
                'range'         => [
                    'px'    => [
                        'min'       => -100, 
                        'max'       => 100,
                    ],
                ],
                'condition'		=> [
                    'premium_carousel_navigation_show' => 'yes',
                    'premium_carousel_slider_type'     => 'horizontal'
				],
                'selectors'     => [
                    '{{WRAPPER}} a.carousel-arrow.carousel-next' => 'right: {{SIZE}}px',
                    '{{WRAPPER}} a.carousel-arrow.carousel-prev' => 'left: {{SIZE}}px',
                ]
            ]
        );
        
        $this->start_controls_tabs('premium_button_style_tabs');
        
        $this->start_controls_tab('premium_button_style_normal',
            [
                'label'             => __('Normal', 'premium-addons-for-elementor'),
            ]
            );
        
        $this->add_control('premium_carousel_arrow_color',
			[
				'label' 		=> __( 'Color', 'premium-addons-for-elementor' ),
				'type' 			=> Controls_Manager::COLOR,
				'scheme' 		=> [
				    'type' 	=> Scheme_Color::get_type(),
				    'value' => Scheme_Color::COLOR_2,
				],
				'condition'		=> [
					'premium_carousel_navigation_show' => 'yes'
				],
				'selectors' => [
					'{{WRAPPER}} .premium-carousel-wrapper .slick-arrow' => 'color: {{VALUE}};',
				],
			]
		);
        
        $this->add_control('premium_carousel_arrow_bg_color',
			[
				'label' 		=> __( 'Background Color', 'premium-addons-for-elementor' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors'     => [
					'{{WRAPPER}} a.carousel-arrow.carousel-next, {{WRAPPER}} a.carousel-arrow.carousel-prev' => 'background-color: {{VALUE}};',
				],
			]
		);
        
        $this->add_group_control(
            Group_Control_Border::get_type(), 
                [
                    'name'          => 'premium_carousel_arrows_border_normal',
                    'selector'      => '{{WRAPPER}} .slick-arrow',
                ]
                );
        
        $this->add_control('premium_carousel_arrows_radius_normal',
            [
                'label'         => __('Border Radius', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SLIDER,
                'size_units'    => ['px', '%' ,'em'],
                'selectors'     => [
                    '{{WRAPPER}} .slick-arrow' => 'border-radius: {{SIZE}}{{UNIT}};'
                ]
            ]
        );
        
        $this->end_controls_tab();
        
        $this->start_controls_tab('premium_carousel_arrows_hover',
            [
                'label'             => __('Hover', 'premium-addons-for-elementor'),
            ]
            );
        
        $this->add_control('premium_carousel_hover_arrow_color',
			[
				'label' 		=> __( 'Color', 'premium-addons-for-elementor' ),
				'type' 			=> Controls_Manager::COLOR,
				'scheme' 		=> [
				    'type' 	=> Scheme_Color::get_type(),
				    'value' => Scheme_Color::COLOR_2,
				],
				'condition'		=> [
					'premium_carousel_navigation_show' => 'yes'
				],
				'selectors' => [
					'{{WRAPPER}} .premium-carousel-wrapper .slick-arrow:hover' => 'color: {{VALUE}};',
				],
			]
		);
        
        $this->add_control('premium_carousel_arrow_hover_bg_color',
			[
				'label' 		=> __( 'Background Color', 'premium-addons-for-elementor' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors'     => [
					'{{WRAPPER}} a.carousel-arrow.carousel-next:hover, {{WRAPPER}} a.carousel-arrow.carousel-prev:hover' => 'background-color: {{VALUE}};',
				],
			]
		);
        
        $this->add_group_control(
            Group_Control_Border::get_type(), 
            [
                'name'          => 'premium_carousel_arrows_border_hover',
                'selector'      => '{{WRAPPER}} .slick-arrow:hover',
            ]
        );
        
        $this->add_control('premium_carousel_arrows_radius_hover',
            [
                'label'         => __('Border Radius', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SLIDER,
                'size_units'    => ['px', '%' ,'em'],
                'selectors'     => [
                    '{{WRAPPER}} .slick-arrow:hover' => 'border-radius: {{SIZE}}{{UNIT}};'
                ]
            ]
        );
        
        $this->end_controls_tab();
        
        $this->end_controls_tabs();

        $this->end_controls_section();

        $this->start_controls_section('premium_carousel_navigation_dots',
			[
				'label'         => __( 'Navigation Dots', 'premium-addons-for-elementor' ),
				'tab' 			=> Controls_Manager::TAB_STYLE,
                'condition'     => [
                    'premium_carousel_dot_navigation_show'  => 'yes'
                ]
			]
		);
        
        $this->add_control('premium_carousel_dot_icon',
		    [
		        'label'         => __( 'Icon', 'premium-addons-for-elementor' ),
		        'type'          => Controls_Manager::CHOOSE,
		        'options'       => [
		            'square_white'    		=> [
		                'icon' => 'far fa-square',
		            ],
		            'square_black' 			=> [
		                'icon' => 'fas fa-square',
		            ],
		            'circle_white' 	=> [
		                'icon' => 'fas fa-circle',
		            ],
		            'circle_thin' 		=> [
		                'icon' => 'far fa-circle',
		            ]
		        ],
		        'default'		=> 'circle_white',
		        'condition'		=> [
					'premium_carousel_dot_navigation_show' => 'yes'
				],
                'toggle'        => false
		    ]
		);

		$this->add_control('premium_carousel_dot_navigation_color',
			[
				'label' 		=> __( 'Color', 'premium-addons-for-elementor' ),
				'type' 			=> Controls_Manager::COLOR,
				'scheme' 		=> [
				    'type' 	=> Scheme_Color::get_type(),
				    'value' => Scheme_Color::COLOR_2,
				],
				'condition'		=> [
					'premium_carousel_dot_navigation_show' => 'yes'
				],
				'selectors'		=> [
					'{{WRAPPER}} ul.slick-dots li' => 'color: {{VALUE}}'
				]
			]
		);

		$this->add_control('premium_carousel_dot_navigation_active_color',
			[
				'label' 		=> __( 'Active Color', 'premium-addons-for-elementor' ),
				'type' 			=> Controls_Manager::COLOR,
				'scheme' 		=> [
				    'type' 	=> Scheme_Color::get_type(),
				    'value' => Scheme_Color::COLOR_1,
				],
				'condition'		=> [
					'premium_carousel_dot_navigation_show' => 'yes'
				],
				'selectors'		=> [
					'{{WRAPPER}} ul.slick-dots li.slick-active' => 'color: {{VALUE}}'
				]
			]
		);
        
        $this->add_control('premium_carousel_ripple_active_color',
			[
				'label' 		=> __( 'Active Ripple Color', 'premium-addons-for-elementor' ),
				'type' 			=> Controls_Manager::COLOR,
				'condition'		=> [
					'premium_carousel_dot_navigation_show' => 'yes',
                    'premium_carousel_navigation_effect'   => 'yes'
				],
				'selectors'		=> [
					'{{WRAPPER}}.premium-carousel-ripple-yes ul.slick-dots li.slick-active:hover:before' => 'background-color: {{VALUE}}'
				]
			]
		);
        
        $this->add_control('premium_carousel_ripple_color',
			[
				'label' 		=> __( 'Inactive Ripple Color', 'premium-addons-for-elementor' ),
				'type' 			=> Controls_Manager::COLOR,
				'condition'		=> [
					'premium_carousel_dot_navigation_show' => 'yes',
                    'premium_carousel_navigation_effect'   => 'yes'
				],
				'selectors'		=> [
					'{{WRAPPER}}.premium-carousel-ripple-yes ul.slick-dots li:hover:before' => 'background-color: {{VALUE}}'
				]
			]
		);
        
		$this->end_controls_section();

	}

	/**
	 * Render Carousel widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render() {
        
        $settings = $this->get_settings();
        
        $templates = array();
        
        if( 'select' === $settings['premium_carousel_content_type'] ){
            $templates = $settings['premium_carousel_slider_content'];
        } else {
            foreach( $settings['premium_carousel_templates_repeater'] as $template ){
                array_push($templates, $template['premium_carousel_repeater_item']);
            }
        }

        if( empty( $templates ) ) {
            return;
        }
        
        $vertical = $settings['premium_carousel_slider_type'] == 'vertical' ? true : false;
		
		$slides_on_desk = $settings['premium_carousel_responsive_desktop'];
		if( $settings['premium_carousel_slides_to_show'] == 'all' ) {
			$slidesToScroll = ! empty( $slides_on_desk ) ? $slides_on_desk : 1;
		} else {
			$slidesToScroll = 1;
		}

		$slidesToShow = !empty($slides_on_desk) ? $slides_on_desk : 1;

		$slides_on_tabs = $settings['premium_carousel_responsive_tabs'];
		$slides_on_mob = $settings['premium_carousel_responsive_mobile'];

		if( empty( $settings['premium_carousel_responsive_tabs'] ) ) {
			$slides_on_tabs = $slides_on_desk;
		}

		if( empty ( $settings['premium_carousel_responsive_mobile'] ) ) {
			$slides_on_mob = $slides_on_desk;
		}

        $infinite = $settings['premium_carousel_loop'] == 'yes' ? true : false;

        $fade = $settings['premium_carousel_fade'] == 'yes' ? true : false;
        
        $speed = !empty( $settings['premium_carousel_speed'] ) ? $settings['premium_carousel_speed'] : '';
        
        $autoplay = $settings['premium_carousel_autoplay'] == 'yes' ? true : false;
        
        $autoplaySpeed = ! empty( $settings['premium_carousel_autoplay_speed'] ) ? $settings['premium_carousel_autoplay_speed'] : '';
        
        $draggable = $settings['premium_carousel_draggable_effect'] == 'yes' ? true  : false;
		
        $touchMove = $settings['premium_carousel_touch_move'] == 'yes' ? true : false;
        
		$dir = '';
        $rtl = false;
        
		if( $settings['premium_carousel_RTL_Mode'] == 'yes' ) {
			$rtl = true;
			$dir = 'dir="rtl"';
        }
        
        $adaptiveHeight = $settings['premium_carousel_adaptive_height'] == 'yes' ? true : false;
		
        $pauseOnHover = $settings['premium_carousel_pausehover'] == 'yes' ? true : false;
		
        $centerMode = $settings['premium_carousel_center_mode'] == 'yes' ? true : false;
        
        $centerPadding = !empty( $settings['premium_carousel_space_btw_items'] ) ? $settings['premium_carousel_space_btw_items'] ."px" : '';
		
		// Navigation arrow setting setup
		if( $settings['premium_carousel_navigation_show'] == 'yes') {
			$arrows = true;

			if( $settings['premium_carousel_slider_type'] == 'vertical' ) {
				$vertical_alignment = "ver-carousel-arrow";
			} else {
				$vertical_alignment = "carousel-arrow";
			}
			
			if( $settings['premium_carousel_slider_type'] == 'vertical' ) {
				$icon_next = $settings['premium_carousel_arrow_icon_next_ver'];
				if( $icon_next == 'right_arrow_bold' ) {
					$icon_next_class = 'fas fa-arrow-down';
				}
				if( $icon_next == 'right_arrow_long' ) {
					$icon_next_class = 'fas fa-long-arrow-alt-down';
				}
				if( $icon_next == 'right_arrow_long_circle' ) {
					$icon_next_class = 'fas fa-arrow-circle-down';
				}
				if( $icon_next == 'right_arrow_angle' ) {
					$icon_next_class = 'fas fa-angle-down';
				}
				if( $icon_next == 'right_arrow_chevron' ) {
					$icon_next_class = 'fas fa-chevron-down';
				}
				$icon_prev = $settings['premium_carousel_arrow_icon_prev_ver'];

				if( $icon_prev == 'left_arrow_bold' ) {
					$icon_prev_class = 'fas fa-arrow-up';
				}
				if( $icon_prev == 'left_arrow_long' ) {
					$icon_prev_class = 'fas fa-long-arrow-alt-up';
				}
				if( $icon_prev == 'left_arrow_long_circle' ) {
					$icon_prev_class = 'fas fa-arrow-circle-up';
				}
				if( $icon_prev == 'left_arrow_angle' ) {
					$icon_prev_class = 'fas fa-angle-up';
				}
				if( $icon_prev == 'left_arrow_chevron' ) {
					$icon_prev_class = 'fas fa-chevron-up';
				}
			} else {
				$icon_next = $settings['premium_carousel_arrow_icon_next'];
				if( $icon_next == 'right_arrow_bold' ) {
					$icon_next_class = 'fas fa-arrow-right';
				}
				if( $icon_next == 'right_arrow_long' ) {
					$icon_next_class = 'fas fa-long-arrow-alt-right';
				}
				if( $icon_next == 'right_arrow_long_circle' ) {
					$icon_next_class = 'fas fa-arrow-circle-right';
				}
				if( $icon_next == 'right_arrow_angle' ) {
					$icon_next_class = 'fas fa-angle-right';
				}
				if( $icon_next == 'right_arrow_chevron' ) {
					$icon_next_class = 'fas fa-chevron-right';
				}
				$icon_prev = $settings['premium_carousel_arrow_icon_prev'];

				if( $icon_prev == 'left_arrow_bold' ) {
					$icon_prev_class = 'fas fa-arrow-left';
				}
				if( $icon_prev == 'left_arrow_long' ) {
					$icon_prev_class = 'fas fa-long-arrow-alt-left';
				}
				if( $icon_prev == 'left_arrow_long_circle' ) {
					$icon_prev_class = 'fas fa-arrow-circle-left';
				}
				if( $icon_prev == 'left_arrow_angle' ) {
					$icon_prev_class = 'fas fa-angle-left';
				}
				if( $icon_prev == 'left_arrow_chevron' ) {
					$icon_prev_class = 'fas fa-chevron-left';
				}
			}

			$next_arrow = '<a type="button" data-role="none" class="'. $vertical_alignment .' carousel-next" aria-label="Next" role="button" style=""><i class="'.$icon_next_class.'" aria-hidden="true"></i></a>';

			$left_arrow = '<a type="button" data-role="none" class="'. $vertical_alignment .' carousel-prev" aria-label="Next" role="button" style=""><i class="'.$icon_prev_class.'" aria-hidden="true"></i></a>';

			$nextArrow = $next_arrow;
			$prevArrow = $left_arrow;
		} else {
			$arrows = false;
            $nextArrow = '';
			$prevArrow = '';
		}
		if( $settings['premium_carousel_dot_navigation_show'] == 'yes' ){
			$dots =  true;
			if( $settings['premium_carousel_dot_icon'] == 'square_white' ) {
				$dot_icon = 'far fa-square';
			}
			if( $settings['premium_carousel_dot_icon'] == 'square_black' ) {
				$dot_icon = 'fas fa-square';
			}
			if( $settings['premium_carousel_dot_icon'] == 'circle_white' ) {
				$dot_icon = 'fas fa-circle';
			}
			if( $settings['premium_carousel_dot_icon'] == 'circle_thin' ) {
				$dot_icon = 'far fa-circle-thin';
			}
			$customPaging = $dot_icon;
		} else {
            $dots =  false;
            $dot_icon = '';
            $customPaging = '';
        }
		$extra_class = ! empty ( $settings['premium_carousel_extra_class'] ) ? ' ' . $settings['premium_carousel_extra_class'] : '';
		
		$animation_class = $settings['premium_carousel_animation_list'];
        
		$animation = ! empty( $animation_class ) ? 'animated ' . $animation_class : 'null';
        
        $tablet_breakpoint = ! empty ( $settings['premium_carousel_tablet_breakpoint'] ) ? $settings['premium_carousel_tablet_breakpoint'] : 1025;
        
        $mobile_breakpoint = ! empty ( $settings['premium_carousel_mobile_breakpoint'] ) ? $settings['premium_carousel_mobile_breakpoint'] : 768;
        
        $carousel_settings = [
            'vertical'      => $vertical,
            'slidesToScroll'=> $slidesToScroll,
            'slidesToShow'  => $slidesToShow,
            'infinite'      => $infinite,
            'speed'         => $speed,
            'fade'			=> $fade,
            'autoplay'      => $autoplay,
            'autoplaySpeed' => $autoplaySpeed,
            'draggable'     => $draggable,
            'touchMove'     => $touchMove,
            'rtl'           => $rtl,
            'adaptiveHeight'=> $adaptiveHeight,
            'pauseOnHover'  => $pauseOnHover,
            'centerMode'    => $centerMode,
            'centerPadding' => $centerPadding,
            'arrows'        => $arrows,
            'nextArrow'     => $nextArrow,
            'prevArrow'     => $prevArrow,
            'dots'          => $dots,
            'customPaging'  => $customPaging,
            'slidesDesk'    => $slides_on_desk,
            'slidesTab'     => $slides_on_tabs,
            'slidesMob'     => $slides_on_mob,
            'animation'     => $animation,
            'tabletBreak'   => $tablet_breakpoint,
            'mobileBreak'   => $mobile_breakpoint
        ];
        
        $this->add_render_attribute( 'carousel', 'id', 'premium-carousel-wrapper-' . esc_attr( $this->get_id() ) );
        
        $this->add_render_attribute( 'carousel', 'class', [
            'premium-carousel-wrapper',
            'carousel-wrapper-' . esc_attr( $this->get_id() ),
            $extra_class,
            $dir
        ] );
        
        if( 'yes' == $settings['premium_carousel_dot_navigation_show'] ) {
            $this->add_render_attribute( 'carousel', 'class', 'premium-carousel-dots-' . $settings['premium_carousel_dot_position'] );
            
        }

        if( $settings['premium_carousel_fade'] == 'yes' && $settings['premium_carousel_zoom'] == 'yes' ) {
			$this->add_render_attribute( 'carousel', 'class', 'premium-carousel-scale' );
        }
        
        $this->add_render_attribute( 'carousel', 'data-settings', wp_json_encode( $carousel_settings ) );
                        
		?>
            
        <div <?php echo $this->get_render_attribute_string('carousel'); ?>>
            <div id="premium-carousel-<?php echo esc_attr( $this->get_id() ); ?>" class="premium-carousel-inner">
                <?php 
                    foreach( $templates as $template_title ) :
                        if( ! empty( $template_title ) ) :
                 ?>
                <div class="premium-carousel-template item-wrapper">
                    <?php echo $this->getTemplateInstance()->get_template_content( $template_title ); ?>
                </div>
                <?php   endif; 
                endforeach; ?>
            </div>
        </div>
		<?php
	}
	
	/**
	 * Render Carousel widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
    protected function content_template() {
        
        ?>
        
        <#
        
            var vertical        = 'vertical' === settings.premium_carousel_slider_type ? true : false,
                slidesOnDesk    = settings.premium_carousel_responsive_desktop,
                slidesToScroll  = 1,
                iconNextClass   = '',
                iconPrevClass   = '',
                dotIcon         = '',
                verticalAlignment= '';
                
            if( 'all' === settings.premium_carousel_slides_to_show ) {
                slidesToScroll = '' !== slidesOnDesk ? slidesOnDesk : 1;
            } else {
                slidesToScroll = 1;
            }

            var slidesToShow    = '' !== slidesOnDesk ? slidesOnDesk : 1,
                slidesOnTabs    = settings.premium_carousel_responsive_tabs,
                slidesOnMob     = settings.premium_carousel_responsive_mobile;

            if( '' === settings.premium_carousel_responsive_tabs ) {
                slidesOnTabs = slidesOnDesk;
            }

            if( '' === settings.premium_carousel_responsive_mobile ) {
                slidesOnMob = slidesOnDesk;
            }

            var infinite    = settings.premium_carousel_loop === 'yes' ? true : false,
                fade        = settings.premium_carousel_fade === 'yes' ? true : false,
                speed       = '' !== settings.premium_carousel_speed ? settings.premium_carousel_speed : '',
                autoplay    = settings.premium_carousel_autoplay === 'yes' ? true : false,
                autoplaySpeed = '' !== settings.premium_carousel_autoplay_speed ? settings.premium_carousel_autoplay_speed : '',
                draggable   = settings.premium_carousel_draggable_effect === 'yes' ? true  : false,
                touchMove   = settings.premium_carousel_touch_move === 'yes' ? true : false,
                dir         = '',
                rtl         = false;

            if( 'yes' === settings.premium_carousel_RTL_Mode ) {
                rtl = true;
                dir = 'dir="rtl"';
            }

            var adaptiveHeight  = 'yes' === settings.premium_carousel_adaptive_height ? true : false,
                pauseOnHover    = 'yes' === settings.premium_carousel_pausehover ? true : false,
                centerMode      = 'yes' === settings.premium_carousel_center_mode ? true : false,
                centerPadding   = '' !== settings.premium_carousel_space_btw_items ? settings.premium_carousel_space_btw_items + "px" : '';
                
            // Navigation arrow setting setup
            if( 'yes' === settings.premium_carousel_navigation_show ) {
            
                var arrows = true;

                if( 'vertical' === settings.premium_carousel_slider_type ) {
                    verticalAlignment = "ver-carousel-arrow";
                } else {
                    verticalAlignment = "carousel-arrow";
                }
                
                if( 'vertical' === settings.premium_carousel_slider_type ) {
                
                    var iconNext = settings.premium_carousel_arrow_icon_next_ver;
                    
                    if( iconNext === 'right_arrow_bold' ) {
                        iconNextClass = 'fas fa-arrow-down';
                    }
                    if( iconNext === 'right_arrow_long' ) {
                        iconNextClass = 'fas fa-long-arrow-alt-down';
                    }
                    if( iconNext === 'right_arrow_long_circle' ) {
                        iconNextClass = 'fas fa-arrow-circle-down';
                    }
                    if( iconNext === 'right_arrow_angle' ) {
                        iconNextClass = 'fas fa-angle-down';
                    }
                    if( iconNext === 'right_arrow_chevron' ) {
                        iconNextClass = 'fas fa-chevron-down';
                    }
                    
                    var iconPrev = settings.premium_carousel_arrow_icon_prev_ver;

                    if( iconPrev === 'left_arrow_bold' ) {
                        iconPrevClass = 'fas fa-arrow-up';
                    }
                    if( iconPrev === 'left_arrow_long' ) {
                        iconPrevClass  = 'fas fa-long-arrow-alt-up';
                    }
                    if( iconPrev === 'left_arrow_long_circle' ) {
                        iconPrevClass  = 'fas fa-arrow-circle-up';
                    }
                    if( iconPrev === 'left_arrow_angle' ) {
                        iconPrevClass  = 'fas fa-angle-up';
                    }
                    if( iconPrev === 'left_arrow_chevron' ) {
                        iconPrevClass  = 'fas fa-chevron-up';
                    }
                    
                } else {
                    var iconNext = settings.premium_carousel_arrow_icon_next;
                    
                    if( iconNext === 'right_arrow_bold' ) {
                        iconNextClass = 'fas fa-arrow-right';
                    }
                    if( iconNext === 'right_arrow_long' ) {
                        iconNextClass = 'fas fa-long-arrow-alt-right';
                    }
                    if( iconNext === 'right_arrow_long_circle' ) {
                        iconNextClass = 'fas fa-arrow-circle-right';
                    }
                    if( iconNext === 'right_arrow_angle' ) {
                        iconNextClass = 'fas fa-angle-right';
                    }
                    if( iconNext === 'right_arrow_chevron' ) {
                        iconNextClass = 'fas fa-chevron-right';
                    }
                    
                    var iconPrev = settings.premium_carousel_arrow_icon_prev;

                    if( iconPrev === 'left_arrow_bold' ) {
                        iconPrevClass = 'fas fa-arrow-left';
                    }
                    if( iconPrev === 'left_arrow_long' ) {
                        iconPrevClass = 'fas fa-long-arrow-alt-left';
                    }
                    if( iconPrev === 'left_arrow_long_circle' ) {
                        iconPrevClass = 'fas fa-arrow-circle-left';
                    }
                    if( iconPrev === 'left_arrow_angle' ) {
                        iconPrevClass = 'fas fa-angle-left';
                    }
                    if( iconPrev === 'left_arrow_chevron' ) {
                        iconPrevClass = 'fas fa-chevron-left';
                    }
                }

                
                var next_arrow = '<a type="button" data-role="none" class="'+ verticalAlignment +' carousel-next" aria-label="Next" role="button" style=""><i class="' + iconNextClass + '" aria-hidden="true"></i></a>';

                var left_arrow = '<a type="button" data-role="none" class="'+ verticalAlignment +' carousel-prev" aria-label="Next" role="button" style=""><i class="' + iconPrevClass + '" aria-hidden="true"></i></a>';

                var nextArrow = next_arrow,
                    prevArrow = left_arrow;
                    
            } else {
                var arrows = false,
                    nextArrow = '',
                    prevArrow = '';
            }
            
            if( 'yes' === settings.premium_carousel_dot_navigation_show  ) {
            
                var dots =  true;
                
                if( 'square_white' === settings.premium_carousel_dot_icon ) {
                    dotIcon = 'far fa-square';
                }
                if( 'square_black' === settings.premium_carousel_dot_icon ) {
                    dotIcon = 'fas fa-square';
                }
                if( 'circle_white' === settings.premium_carousel_dot_icon ) {
                    dotIcon = 'fas fa-circle';
                }
                if( 'circle_thin' === settings.premium_carousel_dot_icon ) {
                    dotIcon = 'far fa-circle';
                }
                var customPaging = dotIcon;
                
            } else {
            
                var dots =  false,
                    dotIcon = '',
                    customPaging = '';
                    
            }
            var extraClass = '' !== settings.premium_carousel_extra_class  ? ' ' + settings.premium_carousel_extra_class : '';

            var animationClass  = settings.premium_carousel_animation_list;
            var animation       = '' !== animationClass ? 'animated ' + animationClass : 'null';
            
            var tabletBreakpoint = '' !== settings.premium_carousel_tablet_breakpoint ? settings.premium_carousel_tablet_breakpoint : 1025;

            var mobileBreakpoint = '' !== settings.premium_carousel_mobile_breakpoint ? settings.premium_carousel_mobile_breakpoint : 768;
            
            var carouselSettings = {};
            
            carouselSettings.vertical       = vertical;
            carouselSettings.slidesToScroll = slidesToScroll;
            carouselSettings.slidesToShow   = slidesToShow;
            carouselSettings.infinite       = infinite;
            carouselSettings.speed          = speed;
            carouselSettings.fade           = fade;
            carouselSettings.autoplay       = autoplay;
            carouselSettings.autoplaySpeed  = autoplaySpeed;
            carouselSettings.draggable      = draggable;
            carouselSettings.touchMove      = touchMove;
            carouselSettings.rtl            = rtl;
            carouselSettings.adaptiveHeight = adaptiveHeight;
            carouselSettings.pauseOnHover   = pauseOnHover;
            carouselSettings.centerMode     = centerMode;
            carouselSettings.centerPadding  = centerPadding;
            carouselSettings.arrows         = arrows;
            carouselSettings.nextArrow      = nextArrow;
            carouselSettings.prevArrow      = prevArrow;
            carouselSettings.dots           = dots;
            carouselSettings.customPaging   = customPaging;
            carouselSettings.slidesDesk     = slidesOnDesk;
            carouselSettings.slidesTab      = slidesOnTabs;
            carouselSettings.slidesMob      = slidesOnMob;
            carouselSettings.animation      = animation;
            carouselSettings.tabletBreak    = tabletBreakpoint;
            carouselSettings.mobileBreak    = mobileBreakpoint;
            
            var templates = [];
            
            if( 'select' === settings.premium_carousel_content_type ) {
            
                templates = settings.premium_carousel_slider_content;
                
            } else {
            
                _.each( settings.premium_carousel_templates_repeater, function( template ) {
                
                    templates.push( template.premium_carousel_repeater_item );
                
                } );
            
            }

            view.addRenderAttribute( 'carousel', 'id', 'premium-carousel-wrapper-' + view.getID() );

            view.addRenderAttribute( 'carousel', 'class', [
                'premium-carousel-wrapper',
                'carousel-wrapper-' + view.getID(),
                extraClass,
                dir
            ] );
            
            if( 'yes' === settings.premium_carousel_dot_navigation_show ) {
                view.addRenderAttribute( 'carousel', 'class', 'premium-carousel-dots-' + settings.premium_carousel_dot_position );
            }

            if( 'yes' === settings.premium_carousel_fade && 'yes' === settings.premium_carousel_zoom ) {
                view.addRenderAttribute( 'carousel', 'class', 'premium-carousel-scale' );
            }
        
            view.addRenderAttribute( 'carousel', 'data-settings', JSON.stringify( carouselSettings ) );
            
            view.addRenderAttribute( 'carousel-inner', 'id', 'premium-carousel-' + view.getID() );
            view.addRenderAttribute( 'carousel-inner', 'class', 'premium-carousel-inner' );
            
            
        #>
        
        <div {{{ view.getRenderAttributeString('carousel') }}}>
            <div {{{ view.getRenderAttributeString('carousel-inner') }}}>
                <# _.each( templates, function( templateID ) { 
                    if( templateID ) { 
                #>
                    <div class="item-wrapper" data-template="{{templateID}}"></div>
                <#  } 
                } ); #>
            </div>
        </div>
        
        
    <?php 
    
    }
}