<?php

/**
 * Premium Banner.
 */
namespace PremiumAddons\Widgets;

// Elementor Classes.
use Elementor\Widget_Base;
use Elementor\Utils;
use Elementor\Control_Media;
use Elementor\Controls_Manager;
use Elementor\Scheme_Color;
use Elementor\Scheme_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Css_Filter;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Text_Shadow;

// PremiumAddons Classes.
use PremiumAddons\Helper_Functions;
use PremiumAddons\Includes;

if ( ! defined( 'ABSPATH' ) ) exit; // If this file is called directly, abort.

/**
 * Class Premium_Banner
 */
class Premium_Banner extends Widget_Base {

	protected $templateInstance;

	public function getTemplateInstance() {
		return $this->templateInstance = Includes\premium_Template_Tags::getInstance();
	}

	public function get_name() {
		return 'premium-addon-banner';
	}

	public function get_title() {
		return sprintf( '%1$s %2$s', Helper_Functions::get_prefix(), __('Banner', 'premium-addons-for-elementor') );
	}

	public function get_icon() {
		return 'pa-banner';
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
            'tilt-js',
            'premium-addons-js'
        ];
    }
    
    public function get_custom_help_url() {
		return 'https://premiumaddons.com/support/';
	}

	/**
	 * Register Banner controls.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function _register_controls() {

		$this->start_controls_section('premium_banner_global_settings',
			[
				'label'         => __( 'Image', 'premium-addons-for-elementor' )
			]
		);
        
        $this->add_control('premium_banner_image',
			[
				'label'			=> __( 'Upload Image', 'premium-addons-for-elementor' ),
				'description'	=> __( 'Select an image for the Banner', 'premium-addons-for-elementor' ),
				'type'			=> Controls_Manager::MEDIA,
                'dynamic'       => [ 'active' => true ],
				'default'		=> [
					'url'	=> Utils::get_placeholder_image_src()
				],
				'show_external'	=> true
			]
		);
        
        $this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name'          => 'thumbnail',
				'default'       => 'full',
				'separator'     => 'none',
			]
		);
        
        $this->add_control('premium_banner_link_url_switch',
            [
                'label'         => __('Link', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SWITCHER
            ]
        );

		$this->add_control('premium_banner_image_link_switcher',
			[
				'label'			=> __( 'Custom Link', 'premium-addons-for-elementor' ),
				'type'			=> Controls_Manager::SWITCHER,
				'description'	=> __( 'Add a custom link to the banner', 'premium-addons-for-elementor' ),
                'condition'     => [
                    'premium_banner_link_url_switch'    => 'yes',
                ],
			]
		);
        
        $this->add_control('premium_banner_image_custom_link',
			[
				'label'			=> __( 'Set custom Link', 'premium-addons-for-elementor' ),
				'type'			=> Controls_Manager::URL,
                'dynamic'       => [ 'active' => true ],
				'description'	=> __( 'What custom link you want to set to banner?', 'premium-addons-for-elementor' ),
				'condition'		=> [
					'premium_banner_image_link_switcher' => 'yes',
                    'premium_banner_link_url_switch'    => 'yes'
				],
				'show_external' => false
			]
		);

		$this->add_control('premium_banner_image_existing_page_link',
			[
				'label'			=> __( 'Existing Page', 'premium-addons-for-elementor' ),
				'type'			=> Controls_Manager::SELECT2,
				'condition'		=> [
					'premium_banner_image_link_switcher!' => 'yes',
                    'premium_banner_link_url_switch'    => 'yes'
				],
                'label_block'   => true,
                'multiple'      => false,
				'options'		=> $this->getTemplateInstance()->get_all_post()
			]
		);
        
        $this->add_control('premium_banner_link_title',
			[
				'label'			=> __( 'Link Title', 'premium-addons-for-elementor' ),
				'type'			=> Controls_Manager::TEXT,
                'dynamic'       => [ 'active' => true ],
                'condition'     => [
                    'premium_banner_link_url_switch'    => 'yes'
                ]
			]
		);
        

		$this->add_control('premium_banner_image_link_open_new_tab',
			[
				'label'			=> __( 'New Tab', 'premium-addons-for-elementor' ),
				'type'			=> Controls_Manager::SWITCHER,
				'description'	=> __( 'Choose if you want the link be opened in a new tab or not', 'premium-addons-for-elementor' ),
                'condition'     => [
                    'premium_banner_link_url_switch'    => 'yes'
                ]
			]
		);

		$this->add_control('premium_banner_image_link_add_nofollow',
			[
				'label'			=> __( 'Nofollow Option', 'premium-addons-for-elementor' ),
				'type'			=> Controls_Manager::SWITCHER,
				'description'	=> __('if you choose yes, the link will not be counted in search engines', 'premium-addons-for-elementor' ),
                'condition'     => [
                    'premium_banner_link_url_switch'    => 'yes'
                ]
			]
		);
        
        $this->add_control('premium_banner_image_animation',
			[
				'label'			=> __( 'Effect', 'premium-addons-for-elementor' ),
				'type'			=> Controls_Manager::SELECT,
				'default'		=> 'animation1',
				'description'	=> __( 'Choose a hover effect for the banner', 'premium-addons-for-elementor' ),
				'options'		=> [
					'animation1'		=> __('Effect 1', 'premium-addons-for-elementor'),
					'animation5'		=> __('Effect 2', 'premium-addons-for-elementor'),
					'animation13'       => __('Effect 3', 'premium-addons-for-elementor'),
					'animation2'		=> __('Effect 4', 'premium-addons-for-elementor'),
					'animation4'		=> __('Effect 5', 'premium-addons-for-elementor'),
                    'animation6'		=> __('Effect 6', 'premium-addons-for-elementor'),
                    // 'animation7'		=> __('Effect 7', 'premium-addons-for-elementor'),
                    // 'animation8'		=> __('Effect 8', 'premium-addons-for-elementor'),
                    // 'animation9'		=> __('Effect 9', 'premium-addons-for-elementor'),
                    // 'animation10'		=> __('Effect 10', 'premium-addons-for-elementor'),
				]
			]
		);
        
        $this->add_control('premium_banner_active',
			[
				'label'			=> __( 'Always Hovered', 'premium-addons-for-elementor' ),
				'type'			=> Controls_Manager::SWITCHER,
				'description'	=> __( 'Choose if you want the effect to be always triggered', 'premium-addons-for-elementor' )
			]
		);
        
        $this->add_control('premium_banner_hover_effect',
            [
                'label'         => __('Hover Effect', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SELECT,
                'options'       => [
                    'none'          => __('None', 'premium-addons-for-elementor'),
                    'zoomin'        => __('Zoom In', 'premium-addons-for-elementor'),
                    'zoomout'       => __('Zoom Out', 'premium-addons-for-elementor'),
                    'scale'         => __('Scale', 'premium-addons-for-elementor'),
                    'grayscale'     => __('Grayscale', 'premium-addons-for-elementor'),
                    'blur'          => __('Blur', 'premium-addons-for-elementor'),
                    'bright'        => __('Bright', 'premium-addons-for-elementor'),
                    'sepia'         => __('Sepia', 'premium-addons-for-elementor'),
                ],
                'default'       => 'none',
            ]
        );
        
        $this->add_control('premium_banner_height',
			[
				'label'			=> __( 'Height', 'premium-addons-for-elementor' ),
				'type'			=> Controls_Manager::SELECT,
                'options'		=> [
					'default'		=> __('Default', 'premium-addons-for-elementor'),
					'custom'		=> __('Custom', 'premium-addons-for-elementor')
				],
				'default'		=> 'default',
				'description'	=> __( 'Choose if you want to set a custom height for the banner or keep it as it is', 'premium-addons-for-elementor' )
			]
		);
        
		$this->add_responsive_control('premium_banner_custom_height',
			[
				'label'			=> __( 'Min Height', 'premium-addons-for-elementor' ),
				'type'			=> Controls_Manager::NUMBER,
				'description'	=> __( 'Set a minimum height value in pixels', 'premium-addons-for-elementor' ),
				'condition'		=> [
					'premium_banner_height' => 'custom'
				],
				'selectors'		=> [
					'{{WRAPPER}} .premium-banner-ib' => 'height: {{VALUE}}px;'
				]
			]
		);
        
        $this->add_responsive_control('premium_banner_img_vertical_align',
			[
				'label'			=> __( 'Vertical Align', 'premium-addons-for-elementor' ),
				'type'			=> Controls_Manager::SELECT,
				'condition'		=> [
					'premium_banner_height' => 'custom'
				],
                'options'		=> [
					'flex-start'	=> __('Top', 'premium-addons-for-elementor'),
                    'center'		=> __('Middle', 'premium-addons-for-elementor'),
					'flex-end'		=> __('Bottom', 'premium-addons-for-elementor'),
                    'inherit'		=> __('Full', 'premium-addons-for-elementor')
				],
                'default'       => 'flex-start',
				'selectors'		=> [
					'{{WRAPPER}} .premium-banner-img-wrap' => 'align-items: {{VALUE}}; -webkit-align-items: {{VALUE}};'
				]
			]
		);
        
        $this->add_control('mouse_tilt',
            [
                'label'         => __('Enable Mouse Tilt', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SWITCHER,
                'return_value'  => 'true'
            ]
        );
        
        $this->add_control('mouse_tilt_rev',
            [
                'label'         => __('Reverse', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SWITCHER,
                'return_value'  => 'true',
                'condition'     => [
                    'mouse_tilt'    => 'true'
                ]
            ]
        );
     
		$this->add_control('premium_banner_extra_class',
			[
				'label'			=> __( 'Extra Class', 'premium-addons-for-elementor' ),
				'type'			=> Controls_Manager::TEXT,
                'dynamic'       => [ 'active' => true ],
                'description'	=> __( 'Add extra class name that will be applied to the banner, and you can use this class for your customizations.', 'premium-addons-for-elementor' )
			]
		);

		
		$this->end_controls_section();

		$this->start_controls_section('premium_banner_image_section',
  			[
  				'label' => __( 'Content', 'premium-addons-for-elementor' )
  			]
  		);
        
        $this->add_control('premium_banner_title',
			[
				'label'			=> __( 'Title', 'premium-addons-for-elementor' ),
				'placeholder'	=> __( 'Give a title to this banner', 'premium-addons-for-elementor' ),
				'type'			=> Controls_Manager::TEXT,
                'dynamic'       => [ 'active' => true ],
				'default'		=> __( 'Premium Banner', 'premium-addons-for-elementor' ),
				'label_block'	=> false
			]
		);
        
        $this->add_control('premium_banner_title_tag',
			[
				'label'			=> __( 'HTML Tag', 'premium-addons-for-elementor' ),
				'description'	=> __( 'Select a heading tag for the title. Headings are defined with H1 to H6 tags', 'premium-addons-for-elementor' ),
				'type'			=> Controls_Manager::SELECT,
				'default'		=> 'h3',
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
				'label_block'	=> true,
			]
		);
        
        
        $this->add_control('premium_banner_description_hint',
			[
				'label'			=> __( 'Description', 'premium-addons-for-elementor' ),
				'type'			=> Controls_Manager::HEADING,
			]
		);
        
        $this->add_control('premium_banner_description',
			[
				'label'			=> __( 'Description', 'premium-addons-for-elementor' ),
				'description'	=> __( 'Give the description to this banner', 'premium-addons-for-elementor' ),
				'type'			=> Controls_Manager::WYSIWYG,
                'dynamic'       => [ 'active' => true ],
				'default'		=> __( 'Premium Banner gives you a wide range of styles and options that you will definitely fall in love with', 'premium-addons-for-elementor' ),
				'label_block'	=> true
			]
		);
        
        $this->add_control('premium_banner_link_switcher',
            [
                'label'         => __('Button', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SWITCHER,
                'condition'     => [
                    'premium_banner_link_url_switch!'   => 'yes'
                ]
            ]
        );

        
        $this->add_control('premium_banner_more_text', 
            [
                'label'         => __('Text','premium-addons-for-elementor'),
                'type'          => Controls_Manager::TEXT,
                'dynamic'       => [ 'active' => true ],
                'default'       => 'Click Here',
                'condition'     => [
                    'premium_banner_link_switcher'    => 'yes',
                    'premium_banner_link_url_switch!'   => 'yes'
                ]
            ]
        );
        
        $this->add_control('premium_banner_link_selection',
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
                    'premium_banner_link_switcher'    => 'yes',
                    'premium_banner_link_url_switch!'   => 'yes'
                ]
            ]
        );

        $this->add_control('premium_banner_link',
            [
                'label'         => __('Link', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::URL,
                'dynamic'       => [ 'active' => true ],
                'default'       => [
                    'url'   => '#',
                ],
                'placeholder'   => 'https://premiumaddons.com/',
                'label_block'   => true,
                'condition'     => [
                    'premium_banner_link_selection' => 'url',
                    'premium_banner_link_switcher'    => 'yes',
                    'premium_banner_link_url_switch!'   => 'yes'
                ]
            ]
        );
        
        $this->add_control('premium_banner_existing_link',
            [
                'label'         => __('Existing Page', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SELECT2,
                'options'       => $this->getTemplateInstance()->get_all_post(),
                'multiple'      => false,
                'condition'     => [
                    'premium_banner_link_selection'     => 'link',
                    'premium_banner_link_switcher'    => 'yes',
                    'premium_banner_link_url_switch!'   => 'yes'
                ],
                'label_block'   => true
            ]
        );
        
        
        $this->add_control('premium_banner_title_text_align', 
            [
                'label'         => __('Alignment', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::CHOOSE,
                'options'       => [
                    'left'  => [
                        'title'     => __('Left', 'premium-addons-for-elementor'),
                        'icon'      => 'fa fa-align-left'
                    ],
                    'center'  => [
                        'title'     => __('Center', 'premium-addons-for-elementor'),
                        'icon'      => 'fa fa-align-center'
                    ],
                    'right'  => [
                        'title'     => __('Right', 'premium-addons-for-elementor'),
                        'icon'      => 'fa fa-align-right'
                    ],
                ],
                'default'       => 'left',
                'toggle'        => false,
                'selectors'     => [
                    '{{WRAPPER}} .premium-banner-ib-title, {{WRAPPER}} .premium-banner-ib-content, {{WRAPPER}} .premium-banner-read-more'   => 'text-align: {{VALUE}} ;',
                ]
            ]
        );
        
        $this->end_controls_section();
        
        $this->start_controls_section('premium_banner_responsive_section',
            [
                'label'         => __('Responsive', 'premium-addons-for-elementor'),
            ]
        );
        
        $this->add_control('premium_banner_responsive_switcher',
            [
                'label'         => __('Responsive Controls', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SWITCHER,
                'description'   => __('If the description text is not suiting well on specific screen sizes, you may enable this option which will hide the description text.', 'premium-addons-for-elementor')
            ]
        );
        
        $this->add_control('premium_banner_min_range', 
            [
                'label'     => __('Minimum Size', 'premium-addons-for-elementor'),
                'type'      => Controls_Manager::NUMBER,
                'description'=> __('Note: minimum size for extra small screens is 1px.','premium-addons-for-elementor'),
                'default'   => 1,
                'condition' => [
                    'premium_banner_responsive_switcher'    => 'yes'
                ],
            ]
        );

        $this->add_control('premium_banner_max_range', 
            [
                'label'     => __('Maximum Size', 'premium-addons-for-elementor'),
                'type'      => Controls_Manager::NUMBER,
                'description'=> __('Note: maximum size for extra small screens is 767px.','premium-addons-for-elementor'),
                'default'   => 767,
                'condition' => [
                    'premium_banner_responsive_switcher'    => 'yes'
                ],
            ]
        );

		$this->end_controls_section();
        
        $this->start_controls_section('premium_banner_opacity_style',
			[
				'label' 		=> __( 'Image', 'premium-addons-for-elementor' ),
				'tab' 			=> Controls_Manager::TAB_STYLE
			]
		);
        
        $this->add_control('premium_banner_image_bg_color',
			[
				'label' 		=> __( 'Background Color', 'premium-addons-for-elementor' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .premium-banner-ib' => 'background: {{VALUE}};'
				]
			]
		);
        
		$this->add_control('premium_banner_image_opacity',
			[
				'label' => __( 'Image Opacity', 'premium-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 1
				],
				'range' => [
					'px' => [
		                'min' => 0,
		                'max' => 1,
		                'step' => .1
		            ]
				],
				'selectors' => [
		            '{{WRAPPER}} .premium-banner-ib img' => 'opacity: {{SIZE}};'
		        ]
			]
		);

		$this->add_control('premium_banner_image_hover_opacity',
			[
				'label' => __( 'Hover Opacity', 'premium-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 1
				],
				'range' => [
					'px' => [
		                'min' => 0,
		                'max' => 1,
		                'step' => .1
		            ]
				],
				'selectors' => [
		            '{{WRAPPER}} .premium-banner-ib img.active' => 'opacity: {{SIZE}};'
		        ]
			]
		);
        
        $this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			[
				'name' => 'css_filters',
				'selector' => '{{WRAPPER}} .premium-banner-ib img',
			]
		);
        
        $this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			[
				'name'      => 'hover_css_filters',
                'label'     => __('Hover CSS Filters', 'premium-addons-for-elementor'),
				'selector'  => '{{WRAPPER}} .premium-banner-ib img.active'
			]
		);

		$this->add_group_control(
            Group_Control_Border::get_type(), 
            [
                'name'          => 'premium_banner_image_border',
                'selector'      => '{{WRAPPER}} .premium-banner-ib'
            ]
        );

		$this->add_responsive_control(
			'premium_banner_image_border_radius',
			[
				'label' => __( 'Border Radius', 'premium-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units'    => ['px', '%' ,'em'],
				'selectors' => [
		            '{{WRAPPER}} .premium-banner-ib' => 'border-radius: {{SIZE}}{{UNIT}};'
		        ]
			]
		);
        
        $this->add_control('blend_mode',
			[
				'label'     => __( 'Blend Mode', 'elementor' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
					''              => __( 'Normal', 'elementor' ),
					'multiply'      => 'Multiply',
					'screen'        => 'Screen',
					'overlay'       => 'Overlay',
					'darken'        => 'Darken',
					'lighten'       => 'Lighten',
					'color-dodge'   => 'Color Dodge',
					'saturation'    => 'Saturation',
					'color'         => 'Color',
					'luminosity'    => 'Luminosity',
				],
                'separator' => 'before',
				'selectors' => [
					'{{WRAPPER}} .premium-banner-ib' => 'mix-blend-mode: {{VALUE}}',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section('premium_banner_title_style',
			[
				'label' 		=> __( 'Title', 'premium-addons-for-elementor' ),
				'tab' 			=> Controls_Manager::TAB_STYLE
			]
		);

		$this->add_control('premium_banner_color_of_title',
			[
				'label' => __( 'Color', 'premium-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'scheme' => [
					'type' => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_1
				],
				'selectors' => [
					'{{WRAPPER}} .premium-banner-ib-desc .premium_banner_title' => 'color: {{VALUE}};'
				]
			]
		);
        
        $this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'premium_banner_title_typography',
				'selector' => '{{WRAPPER}} .premium-banner-ib-desc .premium_banner_title',
				'scheme' => Scheme_Typography::TYPOGRAPHY_1
			]
		);
        
        $this->add_control('premium_banner_style2_title_bg',
			[
				'label'			=> __( 'Background', 'premium-addons-for-elementor' ),
				'type'			=> Controls_Manager::COLOR,
				'default'       => '#f2f2f2',
				'description'	=> __( 'Choose a background color for the title', 'premium-addons-for-elementor' ),
				'condition'		=> [
					'premium_banner_image_animation' => 'animation5'
				],
				'selectors'     => [
				    '{{WRAPPER}} .premium-banner-animation5 .premium-banner-ib-desc'    => 'background: {{VALUE}};',
			    ]
			]
		);

		$this->add_control('premium_banner_style3_title_border',
			[
				'label'			=> __( 'Title Border Color', 'premium-addons-for-elementor' ),
				'type'			=> Controls_Manager::COLOR,
				'condition'		=> [
					'premium_banner_image_animation' => ['animation13','animation9','animation10']
				],
				'selectors'     => [
                    '{{WRAPPER}} .premium-banner-animation13 .premium-banner-ib-title::after'    => 'background: {{VALUE}};',
                    '{{WRAPPER}} .premium-banner-animation9 .premium-banner-ib-desc::before , {{WRAPPER}} .premium-banner-animation9 .premium-banner-ib-desc::after'    => 'background: {{VALUE}};',
                    '{{WRAPPER}} .premium-banner-animation10 .premium-banner-ib-title::after'    => 'background: {{VALUE}};',
			    ]
			]
		);

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'label'             => __('Shadow','premium-addons-for-elementor'),
                'name'              => 'premium_banner_title_shadow',
                'selector'          => '{{WRAPPER}} .premium-banner-ib-desc .premium_banner_title'
            ]
        );
        
        $this->add_responsive_control('premium_banner_title_margin',
            [
                'label'         => __('Margin', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::DIMENSIONS,
                'size_units'    => ['px', 'em', '%'],
                'selectors'     => [
                    '{{WRAPPER}} .premium-banner-ib-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ]
            ]
        );

		$this->end_controls_section();

		$this->start_controls_section('premium_banner_styles_of_content',
			[
				'label' 		=> __( 'Description', 'premium-addons-for-elementor' ),
				'tab' 			=> Controls_Manager::TAB_STYLE
			]
		);

		$this->add_control('premium_banner_color_of_content',
			[
				'label' => __( 'Color', 'premium-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'scheme' => [
					'type' => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_3
				],
				'selectors' => [
					'{{WRAPPER}} .premium-banner .premium_banner_content' => 'color: {{VALUE}};'
				],
			]
		);
        
        $this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'          => 'premium_banner_content_typhography',
				'selector'      => '{{WRAPPER}} .premium-banner .premium_banner_content',
				'scheme'        => Scheme_Typography::TYPOGRAPHY_3,
			]
		);

		$this->add_control('premium_banner_scaled_border_color',
			[
				'label' => __( 'Inner Border Color', 'premium-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'condition'		=> [
					'premium_banner_image_animation' => ['animation4', 'animation6', 'animation7' , 'animation8']
				],
				'selectors' => [
                    '{{WRAPPER}} .premium-banner-animation4 .premium-banner-ib-desc::after, {{WRAPPER}} .premium-banner-animation4 .premium-banner-ib-desc::before, {{WRAPPER}} .premium-banner-animation6 .premium-banner-ib-desc::before' => 'border-color: {{VALUE}};',
                    '{{WRAPPER}} .premium-banner-animation7 .premium-banner-br , {{WRAPPER}} .premium-banner-animation8 .premium-banner-br' => 'background-color: {{VALUE}};'
				],
			]
		);

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'label'             => __('Shadow','premium-addons-for-elementor'),
                'name'              => 'premium_banner_description_shadow',
                'selector'          => '{{WRAPPER}} .premium-banner .premium_banner_content',
            ]
        );
        
        $this->add_responsive_control('premium_banner_desc_margin',
            [
                'label'         => __('Margin', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::DIMENSIONS,
                'size_units'    => ['px', 'em', '%'],
                'selectors'     => [
                    '{{WRAPPER}} .premium-banner-ib-content' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ]
            ]
        );
        
		$this->end_controls_section();
        
        $this->start_controls_section('premium_banner_styles_of_button',
			[
				'label' 		=> __( 'Button', 'premium-addons-for-elementor' ),
				'tab' 			=> Controls_Manager::TAB_STYLE,
                'condition'     => [
                    'premium_banner_link_switcher'   => 'yes',
                    'premium_banner_link_url_switch!'   => 'yes'
                ]
			]
		);

		$this->add_control('premium_banner_color_of_button',
			[
				'label' => __( 'Color', 'premium-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'scheme' => [
					'type' => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_3
				],
				'selectors' => [
					'{{WRAPPER}} .premium-banner .premium-banner-link' => 'color: {{VALUE}};'
				]
			]
		);
        
        $this->add_control('premium_banner_hover_color_of_button',
			[
				'label' => __( 'Hover Color', 'premium-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'scheme' => [
					'type' => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_3,
				],
				'selectors' => [
					'{{WRAPPER}} .premium-banner .premium-banner-link:hover' => 'color: {{VALUE}};'
				]
			]
		);
        
        $this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'          => 'premium_banner_button_typhography',
                'scheme'        => Scheme_Typography::TYPOGRAPHY_3,
				'selector'      => '{{WRAPPER}} .premium-banner-link',
			]
		);
        
        $this->add_control('premium_banner_backcolor_of_button',
			[
				'label' => __( 'Background Color', 'premium-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .premium-banner .premium-banner-link' => 'background-color: {{VALUE}};'
				],
			]
		);
        
        $this->add_control('premium_banner_hover_backcolor_of_button',
			[
				'label' => __( 'Hover Background Color', 'premium-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .premium-banner .premium-banner-link:hover' => 'background-color: {{VALUE}};'
				]
			]
		);

        $this->add_group_control(
            Group_Control_Border::get_type(), 
            [
                'name'          => 'premium_banner_button_border',
                'selector'      => '{{WRAPPER}} .premium-banner .premium-banner-link'
            ]
        );
        
        $this->add_control('premium_banner_button_border_radius',
            [
                'label'         => __('Border Radius', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SLIDER,
                'size_units'    => ['px', '%' ,'em'],
                'selectors'     => [
                    '{{WRAPPER}} .premium-banner-link' => 'border-radius: {{SIZE}}{{UNIT}};'
                ]
            ]
        );
        
        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'label'             => __('Shadow','premium-addons-for-elementor'),
                'name'              => 'premium_banner_button_shadow',
                'selector'          => '{{WRAPPER}} .premium-banner .premium-banner-link',
            ]
        );
        
        $this->add_responsive_control('premium_banner_button_margin',
            [
                'label'         => __('Margin', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::DIMENSIONS,
                'size_units'    => ['px', 'em', '%'],
                'selectors'     => [
                    '{{WRAPPER}} .premium-banner-read-more' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ]
            ]
        );
        
        $this->add_responsive_control('premium_banner_button_padding',
            [
                'label'         => __('Padding', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::DIMENSIONS,
                'size_units'    => ['px', 'em', '%'],
                'selectors'     => [
                    '{{WRAPPER}} .premium-banner-link' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ]
            ]
        );

		$this->end_controls_section();
        
        $this->start_controls_section('premium_banner_container_style',
			[
				'label' 		=> __( 'Container', 'premium-addons-for-elementor' ),
				'tab' 			=> Controls_Manager::TAB_STYLE
			]
		);
        
        $this->add_group_control(
            Group_Control_Border::get_type(), 
            [
                'name'          => 'premium_banner_border',
                'selector'      => '{{WRAPPER}} .premium-banner'
            ]
        );
        
        $this->add_control('premium_banner_border_radius',
            [
                'label'         => __('Border Radius', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::DIMENSIONS,
                'size_units'    => ['px', '%' ,'em'],
                'selectors'     => [
                    '{{WRAPPER}} .premium-banner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ]
            ]
        );
        
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'          => 'premium_banner_shadow',
                'selector'      => '{{WRAPPER}} .premium-banner',
            ]
        );
        
        $this->end_controls_section();

	}

    /**
	 * Render Banner widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render() {
        
			$settings 	= $this->get_settings_for_display();
            
            $this->add_render_attribute( 'banner', 'id', 'premium-banner-' . $this->get_id() );
            $this->add_render_attribute( 'banner', 'class', 'premium-banner' );
            
            if( 'true' === $settings['mouse_tilt'] ) {
                $this->add_render_attribute( 'banner', 'data-box-tilt', 'true' );
                if( 'true' === $settings['mouse_tilt_rev'] ) {
                    $this->add_render_attribute( 'banner', 'data-box-tilt-reverse', 'true' );
                }
            }
            
           
            $this->add_inline_editing_attributes('premium_banner_title');
            $this->add_render_attribute('premium_banner_title', 'class', array(
                'premium-banner-ib-title',
                'premium_banner_title'
            ));
            
            $this->add_inline_editing_attributes('premium_banner_description', 'advanced');

			$title_tag 	= $settings[ 'premium_banner_title_tag' ];
			$title 		= $settings[ 'premium_banner_title' ];
			$full_title = '<div class="premium-banner-title-wrap"><'. $title_tag . ' ' . $this->get_render_attribute_string('premium_banner_title') .'>' .$title. '</'.$title_tag.'></div>';

			$link = 'yes' == $settings['premium_banner_image_link_switcher'] ? $settings['premium_banner_image_custom_link']['url'] : get_permalink( $settings['premium_banner_image_existing_page_link'] );

			$link_title = $settings['premium_banner_link_url_switch'] === 'yes' ? $settings['premium_banner_link_title'] : '';
            
			$open_new_tab = $settings['premium_banner_image_link_open_new_tab'] == 'yes' ? ' target="_blank"' : '';
            $nofollow_link = $settings['premium_banner_image_link_add_nofollow'] == 'yes' ? ' rel="nofollow"' : '';
			$full_link = '<a class="premium-banner-ib-link" href="'. $link .'" title="'. $link_title .'"'. $open_new_tab . $nofollow_link . '></a>';
			$animation_class = 'premium-banner-' . $settings['premium_banner_image_animation'];
            $hover_class = ' ' . $settings['premium_banner_hover_effect'];
			$extra_class = ! empty( $settings['premium_banner_extra_class'] ) ? ' '. $settings['premium_banner_extra_class'] : '';
			$active = $settings['premium_banner_active'] == 'yes' ? ' active' : '';
			$full_class = $animation_class.$hover_class.$extra_class.$active;
            $min_size = $settings['premium_banner_min_range'] .'px';
            $max_size = $settings['premium_banner_max_range'] .'px';
            
            
            $banner_url = 'url' == $settings['premium_banner_link_selection'] ? $settings['premium_banner_link']['url'] : get_permalink( $settings['premium_banner_existing_link'] );
            
            $image_html = '';
            if ( ! empty( $settings['premium_banner_image']['url'] ) ) {
                
                $this->add_render_attribute( 'image', 'src', $settings['premium_banner_image']['url'] );
                $this->add_render_attribute( 'image', 'alt', Control_Media::get_image_alt( $settings['premium_banner_image'] ) );
                
                $this->add_render_attribute( 'image', 'title', Control_Media::get_image_title( $settings['premium_banner_image'] ) );

                $image_html = Group_Control_Image_Size::get_attachment_image_html( $settings, 'thumbnail', 'premium_banner_image' );
                
            }
            
        ?>
            <div <?php echo $this->get_render_attribute_string('banner'); ?>>
				<div class="premium-banner-ib <?php echo $full_class; ?> premium-banner-min-height">
					<?php if( ! empty(  $settings['premium_banner_image']['url'] ) ) : ?>
                        <?php if( $settings['premium_banner_height'] == 'custom' ) : ?>
                            <div class="premium-banner-img-wrap">
                        <?php endif;
                            echo $image_html;
                        if( $settings['premium_banner_height'] == 'custom' ): ?>
                            </div>
                        <?php endif;
					endif; ?>
					<div class="premium-banner-ib-desc">
                        <?php if ( $settings['premium_banner_image_animation'] === 'animation7' || $settings['premium_banner_image_animation'] === 'animation8' ) : ?>
                            <div class="premium-banner-border">
                                <div class="premium-banner-br premium-banner-bleft premium-banner-brlr "></div>
                                <div class="premium-banner-br premium-banner-bright premium-banner-brlr "></div>
                                <div class="premium-banner-br premium-banner-btop premium-banner-brtb "></div>
                                <div class="premium-banner-br premium-banner-bottom premium-banner-brtb "></div>
                            </div>
                        
                        <?php endif; ?>
                        <div class="premium-banner-desc-centered">
						    <?php echo $full_title;
                            if( ! empty( $settings['premium_banner_description'] ) ) : ?>
                                <div class="premium-banner-ib-content premium_banner_content">
                                    <div <?php echo $this->get_render_attribute_string('premium_banner_description'); ?>><?php echo $settings[ 'premium_banner_description' ]; ?></div>
                                </div>
                            <?php endif; ?>
                    <?php
                    if( 'yes' == $settings['premium_banner_link_switcher'] && !empty( $settings['premium_banner_more_text'] ) ) : ?>
                        
                            <div class ="premium-banner-read-more">
                                <a class = "premium-banner-link" <?php if( !empty( $banner_url ) ) : ?> href="<?php echo $banner_url; ?>"<?php endif;?><?php if( !empty( $settings['premium_banner_link']['is_external'] ) ) : ?> target="_blank" <?php endif; ?><?php if( !empty($settings['premium_banner_link']['nofollow'] ) ) : ?> rel="nofollow" <?php endif; ?>><?php echo esc_html( $settings['premium_banner_more_text'] ); ?></a>
                            </div>
                        
                    <?php endif; ?>
                        </div>
					</div>
					<?php 
						if( $settings['premium_banner_link_url_switch'] === 'yes' && ( ! empty( $settings['premium_banner_image_custom_link']['url'] ) || !empty( $settings['premium_banner_image_existing_page_link'] ) ) ) {
							echo $full_link;
						}
					 ?>
				</div>
                <?php if($settings['premium_banner_responsive_switcher'] == 'yes' ) : ?>
                <style>
                    @media( min-width: <?php echo $min_size; ?> ) and (max-width:<?php echo $max_size; ?> ) {
                    #premium-banner-<?php echo esc_attr( $this->get_id() ); ?> .premium-banner-ib-content {
                        display: none;
                        }  
                    }
                </style>
                <?php endif; ?>
			</div>
		<?php
	}

    /**
	 * Render Banner widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function content_template() {
        ?>
        <#

            view.addRenderAttribute( 'banner', 'id', 'premium-banner-' + view.getID() );
            view.addRenderAttribute( 'banner', 'class', 'premium-banner' );
            
            if( 'true' === settings.mouse_tilt ) {
                view.addRenderAttribute( 'banner', 'data-box-tilt', 'true' );
                if( 'true' === settings.mouse_tilt_rev ) {
                    view.addRenderAttribute( 'banner', 'data-box-tilt-reverse', 'true' );
                }
            }
            
            var active = 'yes' === settings.premium_banner_active ? 'active' : '';
            
            view.addRenderAttribute( 'banner_inner', 'class', [
                'premium-banner-ib',
                'premium-banner-min-height',
                'premium-banner-' + settings.premium_banner_image_animation,
                settings.premium_banner_hover_effect,
                settings.premium_banner_extra_class,
                active
            ] );
            
            var titleTag = settings.premium_banner_title_tag,
                title    = settings.premium_banner_title;
                
            view.addRenderAttribute( 'premium_banner_title', 'class', [
                'premium-banner-ib-title',
                'premium_banner_title'
            ] );
            
            view.addInlineEditingAttributes( 'premium_banner_title' );
            
            var description = settings.premium_banner_description;
            
            view.addInlineEditingAttributes( 'description', 'advanced' );
            
            var linkSwitcher = settings.premium_banner_link_switcher,
                readMore     = settings.premium_banner_more_text,
                bannerUrl    = 'url' === settings.premium_banner_link_selection ? settings.premium_banner_link.url : settings.premium_banner_existing_link;
                
            var bannerLink = 'yes' === settings.premium_banner_image_link_switcher ? settings.premium_banner_image_custom_link.url : settings.premium_banner_image_existing_page_link,
                linkTitle = 'yes' === settings.premium_banner_link_url_switch ? settings.premium_banner_link_title : '';
                
            var minSize = settings.premium_banner_min_range + 'px',
                maxSize = settings.premium_banner_max_range + 'px';
                
            var imageHtml = '';
            if ( settings.premium_banner_image.url ) {
                var image = {
                    id: settings.premium_banner_image.id,
                    url: settings.premium_banner_image.url,
                    size: settings.thumbnail_size,
                    dimension: settings.thumbnail_custom_dimension,
                    model: view.getEditModel()
                };

                var image_url = elementor.imagesManager.getImageUrl( image );

                imageHtml = '<img src="' + image_url + '"/>';

            }
            
        #>
        
            <div {{{ view.getRenderAttributeString( 'banner' ) }}}>
				<div {{{ view.getRenderAttributeString( 'banner_inner' ) }}}>
					<# if( '' !== settings.premium_banner_image.url ) { #>
                        <# if( 'custom' === settings.premium_banner_height ) { #>
                            <div class="premium-banner-img-wrap">
                        <# } #>
                            {{{imageHtml}}}
                        <# if( 'custom' === settings.premium_banner_height ) { #>
                            </div>
                        <# } #>
                    <# } #>
					<div class="premium-banner-ib-desc">
                        <# if (settings.premium_banner_image_animation ==='animation7' || settings.premium_banner_image_animation ==='animation8'){ #>
                            <div class="premium-banner-border">
                                <div class="premium-banner-br premium-banner-bleft premium-banner-brlr"></div>
                                <div class="premium-banner-br premium-banner-bright premium-banner-brlr"></div>
                                <div class="premium-banner-br premium-banner-btop premium-banner-brtb"></div>
                                <div class="premium-banner-br premium-banner-bottom premium-banner-brtb"></div>
                            </div>
                        <# } #>
                        <div class="premium-banner-desc-centered">
                            <# if( '' !== title ) { #>
                                <div class="premium-banner-title-wrap">
                                    <{{{titleTag}}} {{{ view.getRenderAttributeString('premium_banner_title') }}}>{{{ title }}}</{{{titleTag}}}>
                                </div>
                            <# } #>
                            <# if( '' !== description ) { #>
                                <div class="premium-banner-ib-content premium_banner_content">
                                    <div {{{ view.getRenderAttributeString( 'description' ) }}}>{{{ description }}}</div>
                                </div>
                            <# } #>
                        <# if( 'yes' === linkSwitcher && '' !== readMore ) { #>
                            <div class ="premium-banner-read-more">
                                <a class = "premium-banner-link" href="{{ bannerUrl }}">{{{ readMore }}}</a>
                            </div>
                        <# } #>
                        </div>
					</div>
					<# if( 'yes' === settings.premium_banner_link_url_switch  && ( '' !== settings.premium_banner_image_custom_link.url || '' !== settings.premium_banner_image_existing_page_link ) ) { #>
							<a class="premium-banner-ib-link" href="{{ bannerLink }}" title="{{ linkTitle }}"></a>
                    <# } #>
				</div>
                <# if( 'yes' === settings.premium_banner_responsive_switcher ) { #>
                <style>
                    @media( min-width: {{minSize}} ) and ( max-width: {{maxSize}} ) {
                        #premium-banner-{{ view.getID() }} .premium-banner-ib-content {
                            display: none;
                        }  
                    }
                </style>
                <# } #>
            </div>
        
        
        <?php
	}
}