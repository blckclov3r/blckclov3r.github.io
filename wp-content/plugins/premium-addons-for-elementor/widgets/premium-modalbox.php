<?php

/**
 * Premium Modal Box.
 */
namespace PremiumAddons\Widgets;

// Elementor Classes.
use Elementor\Icons_Manager;
use Elementor\Widget_Base;
use Elementor\Utils;
use Elementor\Control_Media;
use Elementor\Controls_Manager;
use Elementor\Scheme_Color;
use Elementor\Scheme_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Css_Filter;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;

// PremiumAddons Classes.
use PremiumAddons\Helper_Functions;
use PremiumAddons\Includes;

if (!defined('ABSPATH')) exit; // If this file is called directly, abort.

/**
 * Class Premium_Modalbox
 */
class Premium_Modalbox extends Widget_Base
{

    public function getTemplateInstance()
    {
        return $this->templateInstance = Includes\premium_Template_Tags::getInstance();
    }

    public function get_name()
    {
        return 'premium-addon-modal-box';
    }

    public function check_rtl()
    {
        return is_rtl();
    }

    public function get_title()
    {
        return sprintf('%1$s %2$s', Helper_Functions::get_prefix(), __('Modal Box', 'premium-addons-for-elementor'));
    }

    public function get_icon()
    {
        return 'pa-modal-box';
    }

    public function get_style_depends()
    {
        return [
            'premium-addons'
        ];
    }

    public function get_script_depends()
    {
        return [
            'modal-js',
            'lottie-js',
            'premium-addons-js',
        ];
    }

    public function get_categories()
    {
        return ['premium-elements'];
    }

    public function get_custom_help_url()
    {
        return 'https://premiumaddons.com/support/';
    }

    /**
	 * Register Modal Box controls.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
    protected function _register_controls()
    {

        $this->start_controls_section(
            'premium_modal_box_selector_content_section',
            [
                'label'         => __('Content', 'premium-addons-for-elementor'),
            ]
        );

        $this->add_control(
            'premium_modal_box_header_switcher',
            [
                'label'         => __('Header', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SWITCHER,
                'label_on'      => 'show',
                'label_off'     => 'hide',
                'default'       => 'yes',
                'description'   => __('Enable or disable modal header', 'premium-addons-for-elementor'),
            ]
        );

        $this->add_control(
            'premium_modal_box_icon_selection',
            [
                'label'         => __('Icon', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SELECT,
                'options'       => [
                    'noicon'  => __('None', 'premium-addons-for-elementor'),
                    'fonticon' => __('Font Awesome', 'premium-addons-for-elementor'),
                    'image'   => __('Custom Image', 'premium-addons-for-elementor'),
                    'animation'   => __('Lottie Animation', 'premium-addons-for-elementor'),
                ],
                'default'       => 'noicon',
                'condition'     => [
                    'premium_modal_box_header_switcher' => 'yes'
                ],
                'label_block'   => true
            ]
        );

        $this->add_control(
            'premium_modal_box_font_icon_updated',
            [
                'label'             => __('Font Awesome', 'premium-addons-for-elementor'),
                'type'              => Controls_Manager::ICONS,
                'fa4compatibility'  => 'premium_modal_box_font_icon',
                'condition'         => [
                    'premium_modal_box_icon_selection'    => 'fonticon',
                    'premium_modal_box_header_switcher' => 'yes'
                ],
                'label_block'       => true,
            ]
        );

        $this->add_control(
            'premium_modal_box_image_icon',
            [
                'label'         => __('Upload Image', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::MEDIA,
                'dynamic'       => ['active' => true],
                'default'       => [
                    'url'   => Utils::get_placeholder_image_src(),
                ],
                'condition'     => [
                    'premium_modal_box_icon_selection'    => 'image',
                    'premium_modal_box_header_switcher' => 'yes'
                ],
                'label_block'   => true,
            ]
        );

        $this->add_control(
            'header_lottie_url',
            [
                'label'             => __('Animation JSON URL', 'premium-addons-for-elementor'),
                'type'              => Controls_Manager::TEXT,
                'dynamic'           => ['active' => true],
                'description'       => 'Get JSON code URL from <a href="https://lottiefiles.com/" target="_blank">here</a>',
                'label_block'       => true,
                'condition'     => [
                    'premium_modal_box_icon_selection'    => 'animation',
                    'premium_modal_box_header_switcher' => 'yes'
                ]
            ]
        );

        $this->add_control(
            'header_lottie_loop',
            [
                'label'         => __('Loop', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SWITCHER,
                'return_value'  => 'true',
                'default'       => 'true',
                'condition'     => [
                    'premium_modal_box_icon_selection'    => 'animation',
                    'premium_modal_box_header_switcher' => 'yes'
                ]
            ]
        );

        $this->add_control(
            'header_lottie_reverse',
            [
                'label'         => __('Reverse', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SWITCHER,
                'return_value'  => 'true',
                'condition'     => [
                    'premium_modal_box_icon_selection'    => 'animation',
                    'premium_modal_box_header_switcher' => 'yes'
                ]
            ]
        );

        $this->add_responsive_control(
            'premium_modal_box_font_icon_size',
            [
                'label'         => __('Icon Size', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SLIDER,
                'size_units'    => ['px', 'em'],
                'selectors'     => [
                    '{{WRAPPER}} .premium-modal-box-modal-title i' => 'font-size: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}} .premium-modal-box-modal-title img' => 'width: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}} .premium-modal-box-modal-title svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}',
                ],
                'condition'     => [
                    'premium_modal_box_icon_selection!'    => 'noicon',
                    'premium_modal_box_header_switcher' => 'yes'
                ],
            ]
        );

        $this->add_control(
            'premium_modal_box_title',
            [
                'label'         => __('Title', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::TEXT,
                'dynamic'       => ['active' => true],
                'description'   => __('Add a title for the modal box', 'premium-addons-for-elementor'),
                'default'       => 'Modal Box Title',
                'condition'     => [
                    'premium_modal_box_header_switcher' => 'yes'
                ],
                'label_block'   => true,
            ]
        );

        $this->add_control(
            'premium_modal_box_content_heading',
            [
                'label'         => __('Content', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::HEADING,
            ]
        );

        $this->add_control(
            'premium_modal_box_content_type',
            [
                'label'         => __('Content to Show', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SELECT,
                'options'       => [
                    'editor'        => __('Text Editor', 'premium-addons-for-elementor'),
                    'template'      => __('Elementor Template', 'premium-addons-for-elementor'),
                ],
                'default'       => 'editor',
                'label_block'   => true
            ]
        );

        $this->add_control(
            'premium_modal_box_content_temp',
            [
                'label'            => __('Content', 'premium-addons-for-elementor'),
                'description'    => __('Modal content is a template which you can choose from Elementor library', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SELECT2,
                'label_block'   => true,
                'options' => $this->getTemplateInstance()->get_elementor_page_list(),
                'condition'     => [
                    'premium_modal_box_content_type'    => 'template',
                ],
            ]
        );

        $this->add_control(
            'premium_modal_box_content',
            [
                'type'          => Controls_Manager::WYSIWYG,
                'default'       => 'Modal Box Content',
                'selector'      => '{{WRAPPER}} .premium-modal-box-modal-body',
                'dynamic'       => ['active' => true],
                'condition'     => [
                    'premium_modal_box_content_type'    => 'editor',
                ],
                'show_label'    => false,
            ]
        );

        $this->add_control(
            'premium_modal_box_upper_close',
            [
                'label'         => __('Upper Close Button', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SWITCHER,
                'default'       => 'yes',
                'condition'     => [
                    'premium_modal_box_header_switcher' => 'yes'
                ]
            ]
        );

        $this->add_control(
            'premium_modal_box_lower_close',
            [
                'label'         => __('Lower Close Button', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SWITCHER,
                'default'       => 'yes',
            ]
        );

        $this->add_control(
            'premium_modal_close_text',
            [
                'label'         => __('Text', 'premium-addons-for-elementor'),
                'default'       => __('Close', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::TEXT,
                'dynamic'       => ['active' => true],
                'label_block'   => true,
                'condition'     => [
                    'premium_modal_box_lower_close'  => 'yes'
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'premium_modal_box_content_section',
            [
                'label'         => __('Trigger Options', 'premium-addons-for-elementor'),
            ]
        );

        $this->add_control(
            'premium_modal_box_display_on',
            [
                'label'         => __('Trigger', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SELECT,
                'description'   => __('Choose where would you like the modal box appear on', 'premium-addons-for-elementor'),
                'options'       => [
                    'button'        => __('Button', 'premium-addons-for-elementor'),
                    'image'         => __('Image', 'premium-addons-for-elementor'),
                    'text'          => __('Text', 'premium-addons-for-elementor'),
                    'animation'     => __('Lottie Animation', 'premium-addons-for-elementor'),
                    'pageload'      => __('On Page Load', 'premium-addons-for-elementor'),
                ],
                'label_block'   =>  true,
                'default'       => 'button',
            ]
        );

        $this->add_control(
            'premium_modal_box_button_text',
            [
                'label'         => __('Button Text', 'premium-addons-for-elementor'),
                'default'       => __('Premium Modal Box', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::TEXT,
                'dynamic'       => ['active' => true],
                'label_block'   => true,
                'condition'     => [
                    'premium_modal_box_display_on'  => 'button'
                ],
            ]
        );

        $this->add_control(
            'premium_modal_box_icon_switcher',
            [
                'label'         => __('Icon', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SWITCHER,
                'condition'     => [
                    'premium_modal_box_display_on'  => 'button'
                ],
                'description'   => __('Enable or disable button icon', 'premium-addons-for-elementor'),
            ]
        );

        $this->add_control(
            'premium_modal_box_button_icon_selection_updated',
            [
                'label'             => __('Icon', 'premium-addons-for-elementor'),
                'type'              => Controls_Manager::ICONS,
                'fa4compatibility'  => 'premium_modal_box_button_icon_selection',
                'default' => [
                    'value'     => 'fas fa-bars',
                    'library'   => 'fa-solid',
                ],
                'label_block'   => true,
                'condition'     => [
                    'premium_modal_box_display_on'      => 'button',
                    'premium_modal_box_icon_switcher'   => 'yes'
                ],
            ]
        );

        $this->add_control(
            'premium_modal_box_icon_position',
            [
                'label'         => __('Icon Position', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SELECT,
                'default'       => 'before',
                'options'       => [
                    'before'        => __('Before', 'premium-addons-for-elementor'),
                    'after'         => __('After', 'premium-addons-for-elementor'),
                ],
                'label_block'   => true,
                'condition'     => [
                    'premium_modal_box_display_on'  => 'button',
                    'premium_modal_box_icon_switcher'   => 'yes'
                ],
            ]
        );

        $this->add_control(
            'premium_modal_box_icon_before_size',
            [
                'label'         => __('Icon Size', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SLIDER,
                'selectors'     => [
                    '{{WRAPPER}} .premium-modal-trigger-btn i' => 'font-size: {{SIZE}}px',
                    '{{WRAPPER}} .premium-modal-trigger-btn svg' => 'width: {{SIZE}}px; height: {{SIZE}}px',
                ],
                'condition'     => [
                    'premium_modal_box_display_on'  => 'button',
                    'premium_modal_box_icon_switcher'   => 'yes'
                ],
            ]
        );

        if (!$this->check_rtl()) {
            $this->add_control(
                'premium_modal_box_icon_before_spacing',
                [
                    'label'         => __('Icon Spacing', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::SLIDER,
                    'condition'     => [
                        'premium_modal_box_display_on'      => 'button',
                        'premium_modal_box_icon_switcher'   => 'yes',
                        'premium_modal_box_icon_position'   => 'before'
                    ],
                    'default'       => [
                        'size'  => 15
                    ],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-modal-trigger-btn i' => 'margin-right: {{SIZE}}px',
                    ],
                    'separator'     => 'after',
                ]
            );

            $this->add_control(
                'premium_modal_box_icon_after_spacing',
                [
                    'label'         => __('Icon Spacing', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::SLIDER,
                    'default'       => [
                        'size'  => 15
                    ],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-modal-trigger-btn i' => 'margin-left: {{SIZE}}px',
                    ],
                    'separator'     => 'after',
                    'condition'     => [
                        'premium_modal_box_display_on'      => 'button',
                        'premium_modal_box_icon_switcher'   => 'yes',
                        'premium_modal_box_icon_position'   => 'after'
                    ],
                ]
            );
        }

        if ($this->check_rtl()) {
            $this->add_control(
                'premium_modal_box_icon_rtl_before_spacing',
                [
                    'label'         => __('Icon Spacing', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::SLIDER,
                    'condition'     => [
                        'premium_modal_box_display_on'      => 'button',
                        'premium_modal_box_icon_switcher'   => 'yes',
                        'premium_modal_box_icon_position'   => 'before'
                    ],
                    'default'       => [
                        'size'  => 15
                    ],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-modal-trigger-btn i' => 'margin-left: {{SIZE}}px',
                    ],
                    'separator'     => 'after',
                ]
            );

            $this->add_control(
                'premium_modal_box_icon_rtl_after_spacing',
                [
                    'label'         => __('Icon Spacing', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::SLIDER,
                    'default'       => [
                        'size'  => 15
                    ],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-modal-trigger-btn i' => 'margin-right: {{SIZE}}px',
                    ],
                    'separator'     => 'after',
                    'condition'     => [
                        'premium_modal_box_display_on'      => 'button',
                        'premium_modal_box_icon_switcher'   => 'yes',
                        'premium_modal_box_icon_position'   => 'after'
                    ],
                ]
            );
        }

        $this->add_control(
            'premium_modal_box_button_size',
            [
                'label'         => __('Button Size', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SELECT,
                'options'       => [
                    'sm'    => __('Small', 'premium-addons-for-elementor'),
                    'md'    => __('Medium', 'premium-addons-for-elementor'),
                    'lg'    => __('Large', 'premium-addons-for-elementor'),
                    'block' => __('Block', 'premium-addons-for-elementor'),
                ],
                'label_block'   => true,
                'default'       => 'lg',
                'condition'     => [
                    'premium_modal_box_display_on'  => 'button'
                ],
            ]
        );

        $this->add_control(
            'premium_modal_box_image_src',
            [
                'label'         => __('Image', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::MEDIA,
                'dynamic'       => ['active' => true],
                'default'       => [
                    'url'   => Utils::get_placeholder_image_src(),
                ],
                'label_block'   => true,
                'condition'     => [
                    'premium_modal_box_display_on'    => 'image',
                ],
            ]
        );

        $this->add_control(
            'premium_modal_box_selector_text',
            [
                'label'         => __('Text', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::TEXT,
                'dynamic'       => ['active' => true],
                'label_block'   => true,
                'default'       => __('Premium Modal Box', 'premium-addons-for-elementor'),
                'condition'     => [
                    'premium_modal_box_display_on'  => 'text',
                ]
            ]
        );

        $this->add_control(
            'lottie_url',
            [
                'label'             => __('Animation JSON URL', 'premium-addons-for-elementor'),
                'type'              => Controls_Manager::TEXT,
                'dynamic'           => ['active' => true],
                'description'       => 'Get JSON code URL from <a href="https://lottiefiles.com/" target="_blank">here</a>',
                'label_block'       => true,
                'condition'     => [
                    'premium_modal_box_display_on'  => 'animation',
                ]
            ]
        );

        $this->add_control(
            'lottie_loop',
            [
                'label'         => __('Loop', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SWITCHER,
                'return_value'  => 'true',
                'default'       => 'true',
                'condition'     => [
                    'premium_modal_box_display_on'  => 'animation',
                ]
            ]
        );

        $this->add_control(
            'lottie_reverse',
            [
                'label'         => __('Reverse', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SWITCHER,
                'return_value'  => 'true',
                'condition'     => [
                    'premium_modal_box_display_on'  => 'animation',
                ]
            ]
        );

        $this->add_control(
            'lottie_hover',
            [
                'label'         => __('Only Play on Hover', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SWITCHER,
                'return_value'  => 'true',
                'condition'         => [
                    'premium_modal_box_display_on'  => 'animation',
                ],
            ]
        );

        $this->add_responsive_control('trigger_image_animation_size',
            [
                'label'         => __('Size', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SLIDER,
                'size_units'    => ['px', 'em', '%'],
                'range'         => [
                    'px'    => [
                        'min'   => 1, 
                        'max'   => 800, 
                    ],
                    'em'    => [
                        'min'   => 1, 
                        'max'   => 30,
                    ],
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-modal-trigger-img, {{WRAPPER}} .premium-modal-trigger-animation'    => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}'
                ],
                'condition'         => [
                    'premium_modal_box_display_on'  => ['image', 'animation'],
                ],
            ]
        );

        $this->add_control(
            'premium_modal_box_popup_delay',
            [
                'label'         => __('Delay in Popup Display (Sec)', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::NUMBER,
                'description'   => __('When should the popup appear during page load? The value are counted in seconds', 'premium-addons-for-elementor'),
                'default'       => 1,
                'label_block'   => true,
                'condition'     => [
                    'premium_modal_box_display_on'  => 'pageload',
                ]
            ]
        );

        $this->add_responsive_control(
            'premium_modal_box_selector_align',
            [
                'label' => __('Alignment', 'premium-addons-for-elementor'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left'    => [
                        'title' => __('Left', 'premium-addons-for-elementor'),
                        'icon' => 'fa fa-align-left',
                    ],
                    'center' => [
                        'title' => __('Center', 'premium-addons-for-elementor'),
                        'icon' => 'fa fa-align-center',
                    ],
                    'right' => [
                        'title' => __('Right', 'premium-addons-for-elementor'),
                        'icon' => 'fa fa-align-right',
                    ],
                ],
                'default'       => 'center',
                'selectors'     => [
                    '{{WRAPPER}} .premium-modal-trigger-container' => 'text-align: {{VALUE}};',
                ],
                'condition'     => [
                    'premium_modal_box_display_on!' => 'pageload',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'premium_modal_box_selector_style_section',
            [
                'label'         => __('Trigger', 'premium-addons-for-elementor'),
                'tab'           => Controls_Manager::TAB_STYLE,
                'condition'     => [
                    'premium_modal_box_display_on!'  => 'pageload',
                ]
            ]
        );

        $this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			[
				'name'      => 'trigger_css_filters',
                'selector'  => '{{WRAPPER}} .premium-modal-trigger-animation',
                'condition' => [
                    'premium_modal_box_display_on'    => 'animation'
                ]
			]
        );

        $this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			[
				'name'      => 'trigger_hover_css_filters',
                'label'     => __('Hover CSS Filters', 'premium-addons-for-elementor'),
                'selector'  => '{{WRAPPER}} .premium-modal-trigger-animation:hover',
                'condition' => [
                    'premium_modal_box_display_on'    => 'animation'
                ]
			]
		);

        $this->add_control(
            'premium_modal_box_button_text_color',
            [
                'label'         => __('Color', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::COLOR,
                'scheme'        => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_2,
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-modal-trigger-btn, {{WRAPPER}} .premium-modal-trigger-text' => 'color:{{VALUE}};',
                ],
                'condition'     => [
                    'premium_modal_box_display_on'  => ['button', 'text'],
                ]
            ]
        );

        $this->add_control(
            'premium_modal_box_button_text_color_hover',
            [
                'label'         => __('Hover Color', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::COLOR,
                'scheme'        => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_2,
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-modal-trigger-btn:hover, {{WRAPPER}} .premium-modal-trigger-text:hover' => 'color:{{VALUE}};',
                ],
                'condition'     => [
                    'premium_modal_box_display_on'  => ['button', 'text'],
                ]
            ]
        );

        $this->add_control(
            'premium_modal_box_button_icon_color',
            [
                'label'         => __('Icon Color', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::COLOR,
                'scheme'        => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_2,
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-modal-trigger-btn i' => 'color:{{VALUE}};',
                ],
                'condition'     => [
                    'premium_modal_box_display_on'  => ['button'],
                ]
            ]
        );

        $this->add_control(
            'premium_modal_box_button_icon_hover_color',
            [
                'label'         => __('Icon Hover Color', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::COLOR,
                'scheme'        => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_2,
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-modal-trigger-btn:hover i' => 'color:{{VALUE}};',
                ],
                'condition'     => [
                    'premium_modal_box_display_on'  => ['button'],
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'          => 'selectortext',
                'scheme'        => Scheme_Typography::TYPOGRAPHY_1,
                'selector'      => '{{WRAPPER}} .premium-modal-trigger-btn, {{WRAPPER}} .premium-modal-trigger-text',
                'condition'     => [
                    'premium_modal_box_display_on'  => ['button', 'text'],
                ],
            ]
        );

        $this->start_controls_tabs('premium_modal_box_button_style');

        $this->start_controls_tab(
            'premium_modal_box_tab_selector_normal',
            [
                'label'         => __('Normal', 'premium-addons-for-elementor'),
                'condition'     => [
                    'premium_modal_box_display_on'  => ['button', 'text', 'image'],
                ]
            ]
        );

        $this->add_control(
            'premium_modal_box_selector_background',
            [
                'label'         => __('Background Color', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::COLOR,
                'scheme'        => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_1,
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-modal-trigger-btn'   => 'background-color: {{VALUE}};',
                ],
                'condition'     => [
                    'premium_modal_box_display_on'  => 'button',
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'          => 'selector_border',
                'selector'      => '{{WRAPPER}} .premium-modal-trigger-btn,{{WRAPPER}} .premium-modal-trigger-text, {{WRAPPER}} .premium-modal-trigger-img',
                'condition'     => [
                    'premium_modal_box_display_on'  => ['button', 'text', 'image'],
                ]
            ]
        );

        $this->add_control(
            'premium_modal_box_selector_border_radius',
            [
                'label'          => __('Border Radius', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SLIDER,
                'size_units'    => ['px', '%', 'em'],
                'default'       => [
                    'size'  => 0
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-modal-trigger-btn, {{WRAPPER}} .premium-modal-trigger-text, {{WRAPPER}} .premium-modal-trigger-img'     => 'border-radius:{{SIZE}}{{UNIT}};',
                ],
                'condition'     => [
                    'premium_modal_box_display_on'  => ['button', 'text', 'image'],
                ],
                'separator'     => 'after',
            ]
        );

        $this->add_responsive_control(
            'premium_modal_box_selector_padding',
            [
                'label'         => __('Padding', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::DIMENSIONS,
                'size_units'    => ['px', 'em', '%'],
                'default'       => [
                    'unit'  => 'px',
                    'top'   => 10,
                    'right' => 20,
                    'bottom' => 10,
                    'left'  => 20,
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-modal-trigger-btn, {{WRAPPER}} .premium-modal-trigger-text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ],
                'condition'     => [
                    'premium_modal_box_display_on'  => ['button', 'text'],
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'label'         => __('Shadow', 'premium-addons-for-elementor'),
                'name'          => 'premium_modal_box_selector_box_shadow',
                'selector'      => '{{WRAPPER}} .premium-modal-trigger-btn, {{WRAPPER}} .premium-modal-trigger-img',
                'condition'     => [
                    'premium_modal_box_display_on'  => ['button', 'image'],
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name'          => 'premium_modal_box_selector_text_shadow',
                'selector'      => '{{WRAPPER}} .premium-modal-trigger-text',
                'condition'     => [
                    'premium_modal_box_display_on'  => 'text',
                ]
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'premium_modal_box_tab_selector_hover',
            [
                'label'         => __('Hover', 'premium-addons-for-elementor'),
                'condition'     => [
                    'premium_modal_box_display_on'  => ['button', 'text', 'image'],
                ]
            ]
        );

        $this->add_control(
            'premium_modal_box_selector_hover_background',
            [
                'label'         => __('Background Color', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::COLOR,
                'selectors'     => [
                    '{{WRAPPER}} .premium-modal-trigger-btn:hover' => 'background: {{VALUE}};',
                ],
                'condition'     => [
                    'premium_modal_box_display_on'  => 'button',
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'          => 'selector_border_hover',
                'selector'      => '{{WRAPPER}} .premium-modal-trigger-btn:hover,
                    {{WRAPPER}} .premium-modal-trigger-text:hover, {{WRAPPER}} .premium-modal-trigger-img:hover',
                'condition'     => [
                    'premium_modal_box_display_on'  => ['button', 'text', 'image'],
                ]
            ]
        );

        $this->add_control(
            'premium_modal_box_selector_border_radius_hover',
            [
                'label'          => __('Border Radius', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SLIDER,
                'size_units'    => ['px', '%', 'em'],
                'selectors'     => [
                    '{{WRAPPER}} .premium-modal-trigger-btn:hover,{{WRAPPER}} .premium-modal-trigger-text:hover, {{WRAPPER}} .premium-modal-trigger-img:hover'     => 'border-radius:{{SIZE}}{{UNIT}};',
                ],
                'condition'     => [
                    'premium_modal_box_display_on'  => ['button', 'text', 'image'],
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'label'         => __('Shadow', 'premium-addons-for-elementor'),
                'name'          => 'premium_modal_box_selector_box_shadow_hover',
                'selector'      => '{{WRAPPER}} .premium-modal-trigger-btn:hover, {{WRAPPER}} .premium-modal-trigger-text:hover, {{WRAPPER}} .premium-modal-trigger-img:hover',
                'condition'     => [
                    'premium_modal_box_display_on'  => ['button', 'text', 'image'],
                ]
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

        $this->start_controls_section(
            'premium_modal_box_header_settings',
            [
                'label'         => __('Header', 'premium-addons-for-elementor'),
                'tab'           => Controls_Manager::TAB_STYLE,
                'condition'     => [
                    'premium_modal_box_header_switcher' => 'yes'
                ],
            ]
        );

        $this->add_control(
            'premium_modal_box_header_text_color',
            [
                'label'         => __('Color', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .premium-modal-box-modal-title' => 'color: {{VALUE}};',
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'          => 'headertext',
                'scheme'        => Scheme_Typography::TYPOGRAPHY_1,
                'selector'      => '{{WRAPPER}} .premium-modal-box-modal-title',
            ]
        );

        $this->add_control(
            'premium_modal_box_header_background',
            [
                'label'         => __('Background Color', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::COLOR,
                'selectors'     => [
                    '{{WRAPPER}} .premium-modal-box-modal-header'  => 'background: {{VALUE}};',
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'          => 'premium_modal_header_border',
                'selector'      => '{{WRAPPER}} .premium-modal-box-modal-header',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'premium_modal_box_upper_close_button_section',
            [
                'label'         => __('Upper Close Button', 'premium-addons-for-elementor'),
                'tab'           => Controls_Manager::TAB_STYLE,
                'condition'     => [
                    'premium_modal_box_upper_close'   => 'yes',
                    'premium_modal_box_header_switcher' => 'yes'
                ]
            ]
        );

        $this->add_responsive_control(
            'premium_modal_box_upper_close_button_size',
            [
                'label'         => __('Size', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SLIDER,
                'size_units'    => ['px', '%', 'em'],
                'selectors'     => [
                    '{{WRAPPER}} .premium-modal-box-modal-header button' => 'font-size: {{SIZE}}{{UNIT}};',
                ]
            ]
        );



        $this->start_controls_tabs('premium_modal_box_upper_close_button_style');

        $this->start_controls_tab(
            'premium_modal_box_upper_close_button_normal',
            [
                'label'         => __('Normal', 'premium-addons-for-elementor'),
            ]
        );

        $this->add_control(
            'premium_modal_box_upper_close_button_normal_color',
            [
                'label'         => __('Color', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::COLOR,
                'selectors'     => [
                    '{{WRAPPER}} .premium-modal-box-modal-close' => 'color: {{VALUE}};',
                ]
            ]
        );

        $this->add_control(
            'premium_modal_box_upper_close_button_background_color',
            [
                'label'         => __('Background Color', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::COLOR,
                'selectors'     => [
                    '{{WRAPPER}} .premium-modal-box-modal-close' => 'background:{{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'          => 'premium_modal_upper_border',
                'selector'      => '{{WRAPPER}} .premium-modal-box-modal-close',
            ]
        );

        $this->add_control(
            'premium_modal_upper_border_radius',
            [
                'label'          => __('Border Radius', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SLIDER,
                'size_units'    => ['px', '%', 'em'],
                'selectors'     => [
                    '{{WRAPPER}} .premium-modal-box-modal-close'     => 'border-radius:{{SIZE}}{{UNIT}};',
                ],
                'separator'     => 'after',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'premium_modal_box_upper_close_button_hover',
            [
                'label'         => __('Hover', 'premium-addons-for-elementor'),
            ]
        );

        $this->add_control(
            'premium_modal_box_upper_close_button_hover_color',
            [
                'label'         => __('Color', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::COLOR,
                'selectors'     => [
                    '{{WRAPPER}} .premium-modal-box-modal-close:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'premium_modal_box_upper_close_button_background_color_hover',
            [
                'label'         => __('Background Color', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::COLOR,
                'selectors'     => [
                    '{{WRAPPER}} .premium-modal-box-modal-close:hover' => 'background:{{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'          => 'premium_modal_upper_border_hover',
                'selector'      => '{{WRAPPER}} .premium-modal-box-modal-close:hover',
            ]
        );

        $this->add_control(
            'premium_modal_upper_border_radius_hover',
            [
                'label'          => __('Border Radius', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SLIDER,
                'size_units'    => ['px', '%', 'em'],
                'selectors'     => [
                    '{{WRAPPER}} .premium-modal-box-modal-close:hover'     => 'border-radius:{{SIZE}}{{UNIT}};',
                ],
                'separator'     => 'after',
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_responsive_control(
            'premium_modal_box_upper_close_button_padding',
            [
                'label'         => __('Padding', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::DIMENSIONS,
                'size_units'    => ['px', 'em', '%'],
                'selectors'     => [
                    '{{WRAPPER}} .premium-modal-box-modal-close' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ]
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'premium_modal_box_lower_close_button_section',
            [
                'label'         => __('Lower Close Button', 'premium-addons-for-elementor'),
                'tab'           => Controls_Manager::TAB_STYLE,
                'condition'     => [
                    'premium_modal_box_lower_close'   => 'yes',
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'          => 'lowerclose',
                'scheme'        => Scheme_Typography::TYPOGRAPHY_1,
                'selector'      => '{{WRAPPER}} .premium-modal-box-modal-lower-close',
            ]
        );

        $this->add_responsive_control(
            'premium_modal_box_lower_close_button_width',
            [
                'label'         => __('Width', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SLIDER,
                'size_units'    => ['px', '%', 'em'],
                'range'         => [
                    'px'    => [
                        'min'   => 1,
                        'max'   => 500,
                    ],
                    'em'    => [
                        'min'   => 1,
                        'max'   => 30,
                    ],
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-modal-box-modal-lower-close' => 'min-width: {{SIZE}}{{UNIT}};',
                ]
            ]
        );


        $this->start_controls_tabs('premium_modal_box_lower_close_button_style');

        $this->start_controls_tab(
            'premium_modal_box_lower_close_button_normal',
            [
                'label'         => __('Normal', 'premium-addons-for-elementor'),
            ]
        );

        $this->add_control(
            'premium_modal_box_lower_close_button_normal_color',
            [
                'label'         => __('Color', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::COLOR,
                'scheme'        => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_2,
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-modal-box-modal-lower-close' => 'color: {{VALUE}};',
                ]
            ]
        );

        $this->add_control(
            'premium_modal_box_lower_close_button_background_normal_color',
            [
                'label'         => __('Background Color', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::COLOR,
                'scheme'        => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_1,
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-modal-box-modal-lower-close' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'              => 'premium_modal_box_lower_close_border',
                'selector'          => '{{WRAPPER}} .premium-modal-box-modal-lower-close',
            ]
        );

        $this->add_control(
            'premium_modal_box_lower_close_border_radius',
            [
                'label'         => __('Border Radius', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SLIDER,
                'size_units'    => ['px', '%', 'em'],
                'selectors'     => [
                    '{{WRAPPER}} .premium-modal-box-modal-lower-close' => 'border-radius: {{SIZE}}{{UNIT}};'
                ],
                'separator'     => 'after',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'premium_modal_box_lower_close_button_hover',
            [
                'label'         => __('Hover', 'premium-addons-for-elementor'),
            ]
        );

        $this->add_control(
            'premium_modal_box_lower_close_button_hover_color',
            [
                'label'         => __('Color', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::COLOR,
                'scheme'        => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_1,
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-modal-box-modal-lower-close:hover' => 'color: {{VALUE}};',
                ]
            ]
        );

        $this->add_control(
            'premium_modal_box_lower_close_button_background_hover_color',
            [
                'label'         => __('Background Color', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::COLOR,
                'scheme'        => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_2,
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-modal-box-modal-lower-close:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'              => 'premium_modal_box_lower_close_border_hover',
                'selector'          => '{{WRAPPER}} .premium-modal-box-modal-lower-close:hover',
            ]
        );

        $this->add_control(
            'premium_modal_box_lower_close_border_radius_hover',
            [
                'label'         => __('Border Radius', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SLIDER,
                'size_units'    => ['px', '%', 'em'],
                'selectors'     => [
                    '{{WRAPPER}} .premium-modal-box-modal-lower-close:hover' => 'border-radius: {{SIZE}}{{UNIT}};'
                ],
                'separator'     => 'after',
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_responsive_control(
            'premium_modal_box_lower_close_button_padding',
            [
                'label'         => __('Padding', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::DIMENSIONS,
                'size_units'    => ['px', 'em', '%'],
                'selectors'     => [
                    '{{WRAPPER}} .premium-modal-box-modal-lower-close' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ]
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'premium_modal_box_style',
            [
                'label'         => __('Modal Box', 'premium-addons-for-elementor'),
                'tab'           => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'          => 'content_typography',
                'selector'      => '{{WRAPPER}} .premium-modal-box-modal-body',
                'condition'     => [
                    'premium_modal_box_content_type'  => 'editor'
                ],
            ]
        );

        $this->add_control(
            'premium_modal_box_content_background',
            [
                'label'         => __('Content Background Color', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::COLOR,
                'selectors'     => [
                    '{{WRAPPER}} .premium-modal-box-modal-body'  => 'background: {{VALUE}};',
                ]
            ]
        );

        $this->add_control(
            'premium_modal_box_modal_size',
            [
                'label'         => __('Width', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SLIDER,
                'size_units'    => ['px', '%', 'em'],
                'range'         => [
                    'px'    => [
                        'min'   => 50,
                        'max'   => 1000,
                    ]
                ],
                'separator'     => 'before',
                'label_block'   => true,
            ]
        );

        $this->add_responsive_control(
            'premium_modal_box_modal_max_height',
            [
                'label'         => __('Max Height', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SLIDER,
                'size_units'    => ['px', 'em'],
                'range'         => [
                    'px'    => [
                        'min'   => 50,
                        'max'   => 1000,
                    ]
                ],
                'label_block'   => true,
                'selectors'     => [
                    '{{WRAPPER}} .premium-modal-box-modal-dialog'  => 'max-height: {{SIZE}}{{UNIT}};',
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'              => 'premium_modal_box_modal_background',
                'types'             => ['classic', 'gradient'],
                'selector'          => '{{WRAPPER}} .premium-modal-box-modal'
            ]
        );

        $this->add_control(
            'premium_modal_box_footer_background',
            [
                'label'         => __('Footer Background Color', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::COLOR,
                'selectors'     => [
                    '{{WRAPPER}} .premium-modal-box-modal-footer'  => 'background: {{VALUE}};',
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'          => 'contentborder',
                'selector'      => '{{WRAPPER}} .premium-modal-box-modal-content',
            ]
        );

        $this->add_control(
            'premium_modal_box_border_radius',
            [
                'label'          => __('Border Radius', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SLIDER,
                'size_units'    => ['px', '%', 'em'],
                'selectors'     => [
                    '{{WRAPPER}} .premium-modal-box-modal-content'     => 'border-radius: {{SIZE}}{{UNIT}};',
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'          => 'premium_modal_box_shadow',
                'selector'      => '{{WRAPPER}} .premium-modal-box-modal-dialog',
            ]
        );

        $this->add_responsive_control(
            'premium_modal_box_margin',
            [
                'label'         => __('Margin', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::DIMENSIONS,
                'size_units'    => ['px', 'em', '%'],
                'selectors'     => [
                    '{{WRAPPER}} .premium-modal-box-modal-dialog' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ]
            ]
        );

        $this->add_responsive_control(
            'premium_modal_box_padding',
            [
                'label'         => __('Padding', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::DIMENSIONS,
                'size_units'    => ['px', 'em', '%'],
                'selectors'     => [
                    '{{WRAPPER}} .premium-modal-box-modal-body' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ]
            ]
        );

        $this->end_controls_section();
    }


    protected function render_header_icon($new, $migrate)
    {

        $settings = $this->get_settings_for_display();

        $header_icon = $settings['premium_modal_box_icon_selection'];

        if ('fonticon' === $header_icon) {
            if ($new || $migrate) :
                Icons_Manager::render_icon($settings['premium_modal_box_font_icon_updated'], ['aria-hidden' => 'true']);
            else : ?>
                <i <?php echo $this->get_render_attribute_string('title_icon'); ?>></i>
            <?php endif;
        } elseif ('image' === $header_icon) {
            ?>
            <img <?php echo $this->get_render_attribute_string('title_icon'); ?>>
        <?php
        } elseif ('animation' === $header_icon) {
            $this->add_render_attribute( 'header_lottie', [
                'class' => [
                    'premium-modal-header-lottie',
                    'premium-lottie-animation'
                ],
                'data-lottie-url' => $settings['header_lottie_url'],
                'data-lottie-loop' => $settings['header_lottie_loop'],
                'data-lottie-reverse' => $settings['header_lottie_reverse']
            ]);
            ?>
                <div <?php echo $this->get_render_attribute_string( 'header_lottie' ); ?>></div>
            <?php
        }
    }

    protected function render() {

        $settings = $this->get_settings_for_display();

        $trigger = $settings['premium_modal_box_display_on'];

        $this->add_inline_editing_attributes('premium_modal_box_selector_text');

        $this->add_render_attribute('trigger', 'data-toggle', 'premium-modal');

        $this->add_render_attribute('trigger', 'data-target', '#premium-modal-' . $this->get_id());

        if( 'button' === $trigger ) {
            if ( !empty( $settings['premium_modal_box_button_icon_selection'] ) ) {
                $this->add_render_attribute('icon', 'class', $settings['premium_modal_box_button_icon_selection'] );
                $this->add_render_attribute('icon', 'aria-hidden', 'true');
            }
    
            $migrated = isset($settings['__fa4_migrated']['premium_modal_box_button_icon_selection_updated']);
            $is_new = empty($settings['premium_modal_box_button_icon_selection']) && Icons_Manager::is_migration_allowed();

            $this->add_render_attribute('trigger', 'type', 'button');

            $this->add_render_attribute('trigger', 'class', [
                    'premium-modal-trigger-btn',
                    'premium-btn-' . $settings['premium_modal_box_button_size']
                ]
            );

        } elseif( 'image' === $trigger ) {

            $this->add_render_attribute('trigger', 'class', 'premium-modal-trigger-img');

            $this->add_render_attribute('trigger', 'src', $settings['premium_modal_box_image_src']['url']);

            $alt = Control_Media::get_image_alt($settings['premium_modal_box_image_src']);
            $this->add_render_attribute('trigger', 'alt', $alt);

        } elseif( 'text' === $trigger ) {
            $this->add_render_attribute('trigger', 'class', 'premium-modal-trigger-text');
        } elseif( 'animation' === $trigger ) {

            $this->add_render_attribute( 'trigger', [
                'class' => [
                    'premium-modal-trigger-animation',
                    'premium-lottie-animation'
                ],
                'data-lottie-url' => $settings['lottie_url'],
                'data-lottie-loop' => $settings['lottie_loop'],
                'data-lottie-reverse' => $settings['lottie_reverse'],
                'data-lottie-hover' => $settings['lottie_hover']
            ]);

        }
        
        if( 'template' === $settings['premium_modal_box_content_type'] ) {
            $template = $settings['premium_modal_box_content_temp'];    
        }
        
        if ( 'yes' === $settings['premium_modal_box_header_switcher'] ) {

            $header_icon = $settings['premium_modal_box_icon_selection'];
            
            $header_migrated = false;
            $header_new = false;

            if ('fonticon' === $header_icon) {

                if (!empty($settings['premium_modal_box_font_icon'])) {
                    $this->add_render_attribute('title_icon', 'class', $settings['premium_modal_box_font_icon']);
                    $this->add_render_attribute('title_icon', 'aria-hidden', 'true');
                }

                $header_migrated = isset($settings['__fa4_migrated']['premium_modal_box_font_icon_updated']);
                $header_new = empty($settings['premium_modal_box_font_icon']) && Icons_Manager::is_migration_allowed();
            } elseif( 'image' === $header_icon ) {

                $this->add_render_attribute('title_icon', 'src', $settings['premium_modal_box_image_icon']['url']);

                $alt = Control_Media::get_image_alt($settings['premium_modal_box_image_icon']);
                $this->add_render_attribute('title_icon', 'alt', $alt);

            }
        }

        $modal_settings = [
            'trigger'   => $trigger
        ];

        if( 'pageload' === $trigger ) {
            $modal_settings['delay'] = $settings['premium_modal_box_popup_delay'];
        }
        
        $this->add_render_attribute('modal', 'class', ['container', 'premium-modal-box-container']);

        $this->add_render_attribute('modal', 'data-settings', wp_json_encode($modal_settings));

        ?>

        <div <?php echo $this->get_render_attribute_string('modal') ?>>
            <div class="premium-modal-trigger-container">
                <?php
                if ( 'button' === $trigger ) : ?>
                    <button <?php echo $this->get_render_attribute_string('trigger'); ?>>
                        <?php if ('yes' === $settings['premium_modal_box_icon_switcher'] && $settings['premium_modal_box_icon_position'] === 'before') :
                            if ($is_new || $migrated) :
                                Icons_Manager::render_icon($settings['premium_modal_box_button_icon_selection_updated'], ['aria-hidden' => 'true']);
                            else : ?>
                                <i <?php echo $this->get_render_attribute_string('icon'); ?>></i>
                        <?php endif;
                        endif; ?>
                        <span><?php echo $settings['premium_modal_box_button_text']; ?></span>
                        <?php if ('yes' === $settings['premium_modal_box_icon_switcher'] && $settings['premium_modal_box_icon_position'] === 'after') :
                            if ($is_new || $migrated) :
                                Icons_Manager::render_icon($settings['premium_modal_box_button_icon_selection_updated'], ['aria-hidden' => 'true']);
                            else: ?>
                                <i <?php echo $this->get_render_attribute_string('icon'); ?>></i>
                        <?php endif;
                        endif; ?>
                    </button>
                <?php elseif ($trigger === 'image') : ?>
                    <img <?php echo $this->get_render_attribute_string('trigger'); ?>>
                <?php elseif ($trigger === 'text') : ?>
                    <span <?php echo $this->get_render_attribute_string('trigger'); ?>>
                        <div <?php echo $this->get_render_attribute_string('premium_modal_box_selector_text'); ?>><?php echo $settings['premium_modal_box_selector_text']; ?></div>
                    </span>
                <?php elseif ($trigger === 'animation') : ?>
                    <div <?php echo $this->get_render_attribute_string( 'trigger' ); ?>></div>
                <?php endif; ?>
            </div>

            <div id="premium-modal-<?php echo $this->get_id(); ?>" class="premium-modal-box-modal premium-modal-fade" role="dialog">
                <div class="premium-modal-box-modal-dialog">
                    <div class="premium-modal-box-modal-content">
                        <?php if ($settings['premium_modal_box_header_switcher'] === 'yes') : ?>
                            <div class="premium-modal-box-modal-header">
                                <?php if (!empty($settings['premium_modal_box_title'])) : ?>
                                    <h3 class="premium-modal-box-modal-title">
                                        <?php $this->render_header_icon($header_new, $header_migrated);
                                        echo $settings['premium_modal_box_title']; ?>
                                    </h3>
                                <?php endif; ?>
                                <?php if ($settings['premium_modal_box_upper_close'] === 'yes') : ?>
                                    <div class="premium-modal-box-close-button-container">
                                        <button type="button" class="premium-modal-box-modal-close" data-dismiss="premium-modal">&times;</button>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                        <div class="premium-modal-box-modal-body">
                            <?php if ($settings['premium_modal_box_content_type'] === 'editor') : 
                                echo $this->parse_text_editor( $settings['premium_modal_box_content'] );
                            else : 
                                echo $this->getTemplateInstance()->get_template_content( $template );
                            endif; ?>
                        </div>
                        <?php if ($settings['premium_modal_box_lower_close'] === 'yes') : ?>
                            <div class="premium-modal-box-modal-footer">
                                <button type="button" class="premium-modal-box-modal-lower-close" data-dismiss="premium-modal">
                                    <?php echo $settings['premium_modal_close_text']; ?>
                                </button>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <style>
            <?php if ( ! empty( $settings['premium_modal_box_modal_size']['size'] ) ) :
                echo '@media (min-width: 992px) {'; ?>#premium-modal-<?php echo  $this->get_id(); ?> .premium-modal-box-modal-dialog {
                width: <?php echo $settings['premium_modal_box_modal_size']['size'] . $settings['premium_modal_box_modal_size']['unit']; ?>
            }
            <?php echo '}';
            endif; ?>
        </style>

<?php
    }
}
