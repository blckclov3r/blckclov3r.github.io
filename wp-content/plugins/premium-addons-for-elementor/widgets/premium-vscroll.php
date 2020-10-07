<?php

/**
 * Premium Vertical Scroll.
 */
namespace PremiumAddons\Widgets;

// Elementor Classes.
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Scheme_Color;
use Elementor\Scheme_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;

// PremiumAddons Classes.
use PremiumAddons\Helper_Functions;
use PremiumAddons\Includes;

if( ! defined('ABSPATH') ) exit(); // If this file is called directly, abort.

/**
 * Class Premium_Vscroll
 */
class Premium_Vscroll extends Widget_Base {
    
    public function getTemplateInstance() {
		return $this->templateInstance = Includes\premium_Template_Tags::getInstance();
	}
    
    public function get_name() {
        return 'premium-vscroll';
    }
     
    public function get_title() {
        return sprintf( '%1$s %2$s', Helper_Functions::get_prefix(), __('Vertical Scroll', 'premium-addons-for-elementor') );
    }
    
    public function get_icon() {
        return 'pa-vscroll'; 
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
            'iscroll-js',
            'slimscroll-js',
            'vscroll-js'
        ];
    }
    
    public function is_reload_preview_required() {
        return true;
    }
    
    public function get_custom_help_url() {
		return 'https://premiumaddons.com/support/';
	}
    
    /**
	 * Register Video Box controls.
	 *
	 * @since 2.7.4
	 * @access protected
	 */
    protected function _register_controls() {
        
        $this->start_controls_section('content_templates',
            [
                'label'         => __('Content', 'premium-addons-for-elementor'),
            ]
        );
        
        $this->add_control('template_height_hint',
		  	[
                'label'         => '<span style="line-height: 1.4em;"><b>Important<br></b></span><ul style="line-height: 1.2"><li>1- Section Height needs to be set to default.</li><li>2- It\'s recommended that templates be the same height.</li><li>3- For navigation menu, you will need to add navigation menu items first</li></ul>',
		     	'type'          => Controls_Manager::RAW_HTML,
		     	
		  	]
		);
        
        $this->add_control('content_type',
            [
                'label'         => __('Content Type', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SELECT,
                'description'   => __('Choose which method you prefer to insert sections.', 'premium-addons-for-elementor'),
                'options'       => [
                    'templates'     => __('Elementor Templates', 'premium-addons-for-elementor'),
                    'ids'           => __('Section ID', 'premium-addons-for-elementor')
                ],
                'default'       => 'templates',
                'label_block'   => true,
            ]
        );
        
        $temp_repeater = new REPEATER();
        
        $temp_repeater->add_control('section_template',
		  	[
		     	'label'			=> __( 'Elementor Template', 'premium-addons-for-elementor' ),
		     	'type'          => Controls_Manager::SELECT2,
		     	'options'       => $this->getTemplateInstance()->get_elementor_page_list(),
		     	'multiple'      => false,
                'label_block'   => true,
		  	]
        );
        
        $temp_repeater->add_control('template_id',
		  	[
		     	'label'			=> __( 'Section ID', 'premium-addons-for-elementor' ),
                'type'          => Controls_Manager::TEXT,
                'description'   => __('Use this option to add unique ID to your template section', 'premium-addons-for-elementor'),
                'dynamic'       => [ 'active' => true ],
		  	]
		);
        
        $this->add_control('section_repeater',
           [
               'label'          => __( 'Sections', 'premium-addons-for-elementor' ),
               'type'           => Controls_Manager::REPEATER,
               'fields'         => $temp_repeater->get_controls(),
               'condition'      => [
                   'content_type'   => 'templates'
               ],
               'title_field'    => '{{{ section_template }}}'
           ]
        );
        
        $id_repeater = new REPEATER();
        
        $id_repeater->add_control('section_id',
		  	[
		     	'label'			=> __( 'Section ID', 'premium-addons-for-elementor' ),
		     	'type'          => Controls_Manager::TEXT,
                'dynamic'       => [ 'active' => true ],
		  	]
		);
        
        $this->add_control('id_repeater',
           [
               'label'          => __( 'Sections', 'premium-addons-for-elementor' ),
               'type'           => Controls_Manager::REPEATER,
               'fields'         => $id_repeater->get_controls(),
               'condition'      => [
                   'content_type'   => 'ids'
               ],
               'title_field'    => '{{{ section_id }}}'
           ]
        );
        
        $this->end_controls_section();
        
        $this->start_controls_section('nav_menu',
            [
                'label'     => __('Navigation', 'premium-addons-for-elementor'),
            ]
        );
        
        $this->add_control('nav_menu_switch',
            [
                'label'         => __('Navigation Menu', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SWITCHER,
                'description'   => __('This option works only on the frontend', 'premium-addons-for-elementor'),
            ]
        );
        
        $this->add_control('navigation_menu_pos',
            [
                'label'         => __('Position', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SELECT,
                'options'       => [
                    'left'  => __('Left', 'premium-addons-for-elementor'),
                    'right' => __('Right', 'premium-addons-for-elementor'),
                ],
                'default'       => 'left',
                'condition'     => [
                    'nav_menu_switch'   => 'yes'
                ]
            ]
        );
        
        $this->add_responsive_control('navigation_menu_pos_offset_top',
            [
                'label'         => __('Offset Top', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SLIDER,
                'size_units'    => ['px', '%' ,'em'],
                'selectors'     => [
                    '{{WRAPPER}} .premium-vscroll-nav-menu' => 'top: {{SIZE}}{{UNIT}};'
                ],
                'condition'     => [
                    'nav_menu_switch'   => 'yes',
                ]
            ]
        );
        
        $this->add_responsive_control('navigation_menu_pos_offset_left',
            [
                'label'         => __('Offset Left', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SLIDER,
                'size_units'    => ['px', '%' ,'em'],
                'selectors'     => [
                    '{{WRAPPER}} .premium-vscroll-nav-menu.left' => 'left: {{SIZE}}{{UNIT}};'
                ],
                'condition'     => [
                    'nav_menu_switch'   => 'yes',
                    'navigation_menu_pos'   => 'left'
                ]
            ]
        );
        
        $this->add_responsive_control('navigation_menu_pos_offset_right',
            [
                'label'         => __('Offset Right', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SLIDER,
                'size_units'    => ['px', '%' ,'em'],
                'selectors'     => [
                    '{{WRAPPER}} .premium-vscroll-nav-menu.right' => 'right: {{SIZE}}{{UNIT}};'
                ],
                'condition'     => [
                    'nav_menu_switch'   => 'yes',
                    'navigation_menu_pos'   => 'right'
                ]
            ]
        );
        
        $nav_repeater = new REPEATER();
        
        $nav_repeater->add_control('nav_menu_item',
		  	[
		     	'label'			=> __( 'List Item', 'premium-addons-for-elementor' ),
		     	'type'          => Controls_Manager::TEXT,
                'dynamic'       => [ 'active' => true ],
		  	]
		);
        
        $this->add_control('nav_menu_repeater',
           [
               'label'          => __( 'Menu Items', 'premium-addons-for-elementor' ),
               'type'           => Controls_Manager::REPEATER,
               'fields'         => $nav_repeater->get_controls(),
               'title_field'    => '{{{ nav_menu_item }}}',
               'condition'      => [
                   'nav_menu_switch'    => 'yes'
               ]
           ]
        );
        
        $this->add_control('navigation_dots',
            [
                'label'         => __('Navigation Dots', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SWITCHER,
                'default'       => 'yes',
                'separator'     => 'before',
                'prefix_class'  => 'premium-vscroll-nav-dots-'
            ]
        );
        
        $this->add_control('navigation_dots_pos',
            [
                'label'         => __('Horizontal Position', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SELECT,
                'options'       => [
                    'left'  => __('Left', 'premium-addons-for-elementor'),
                    'right' => __('Right', 'premium-addons-for-elementor'),
                ],
                'default'       => 'right',
                'condition'     => [
                    'navigation_dots'   => 'yes',
                ]
            ]
        );
        
        $this->add_control('navigation_dots_v_pos',
            [
                'label'         => __('Vertical Position', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SELECT,
                'options'       => [
                    'top'   => __('Top', 'premium-addons-for-elementor'),
                    'middle'=> __('Middle', 'premium-addons-for-elementor'),
                    'bottom'=> __('Bottom', 'premium-addons-for-elementor'),
                ],
                'default'       => 'middle',
                'condition'     => [
                    'navigation_dots'   => 'yes',
                ]
            ]
        );
        
        $this->add_control('dots_shape',
            [
                'label'         => __('Shape', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SELECT,
                'options'       => [
                    'circ'          => __('Circles', 'premium-addons-for-elementor'),
                    'lines'         => __('Lines', 'premium-addons-for-elementor')
                ],
                'default'       => 'circ',
                'condition'     => [
                    'navigation_dots'   => 'yes',
                ]
            ]
        );
        
        $this->add_control('dots_tooltips_switcher',
            [
                'label'         => __('Tooltips Text', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SWITCHER,
                'default'       => 'yes',
                'condition'     => [
                    'navigation_dots'   => 'yes',
                ]
            ]
        );
        
        $this->add_control('dots_tooltips',
            [
                'label'         => __('Dots Tooltips Text', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::TEXT,
                'dynamic'       => [ 'active' => true ],
                'description'   => __('Add text for each navigation dot separated by \',\'','premium-addons-for-elementor'),
                'condition'     => [
                    'navigation_dots'           => 'yes',
                    'dots_tooltips_switcher'    => 'yes'
                ]
            ]
        );
        
        $this->add_control( 'dots_animation',
			[
				'label'         => __( 'Entrance Animation', 'premium-addons-for-elementor' ),
				'type'          => Controls_Manager::ANIMATION,
				'frontend_available' => true,
                'render_type'   => 'template',
                'condition'     => [
                    'navigation_dots'   => 'yes',
                ]
			]
		);

		$this->add_control( 'dots_animation_duration',
			[
				'label' => __( 'Animation Duration', 'premium-addons-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => '',
				'options' => [
					'slow'  => __( 'Slow', 'premium-addons-for-elementor' ),
					''      => __( 'Normal', 'premium-addons-for-elementor' ),
					'fast'  => __( 'Fast', 'premium-addons-for-elementor' ),
				],
				'condition' => [
                    'navigation_dots'   => 'yes',
					'dots_animation!'   => '',
				],
			]
		);
        
        $this->end_controls_section();
        
        $this->start_controls_section('advanced_settings',
            [
                'label'         => __('Scroll Settings', 'premium-addons-for-elementor'),
            ]
        );
        
        $this->add_control('scroll_speed',
            [
                'label'         => __('Scroll Speed', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::NUMBER,
                'description'   => __('Set scolling speed in seconds, default: 0.7', 'premium-addons-for-elementor'),
            ]
        );
        
        $this->add_control('scroll_offset',
            [
                'label'         => __('Scroll Offset', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::NUMBER
            ]
        );
        
        $this->add_control('full_section',
            [
                'label'         => __('Full Section Scroll', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SWITCHER,
                'default'       => 'yes',
            ]
        );
        
        $this->add_control('save_state',
            [
                'label'         => __('Save to Browser History', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SWITCHER,
                'description'   => __('Enabling this option will save the current section ID to the browser history', 'premium-addons-for-elementor'),
                'default'       => 'yes',
            ]
        );
        
        $this->add_control('full_section_touch',
            [
                'label'         => __('Enable Full Section Scroll on Touch Devices', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SWITCHER,
                'condition'     => [
                    'full_section'  => 'yes'
                ]
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
                'raw'             => sprintf( __( '%1$s How to create an Elementor template to be used in Premium Vertical Scroll » %2$s', 'premium-addons-for-elementor' ), '<a href="https://premiumaddons.com/docs/how-to-create-elementor-template-to-be-used-with-premium-addons/?utm_source=pa-dashboard&utm_medium=pa-editor&utm_campaign=pa-plugin" target="_blank" rel="noopener">', '</a>' ),
                'content_classes' => 'editor-pa-doc',
            ]
        );
        
        $this->end_controls_section();
        
        $this->start_controls_section('navigation_style',
            [
                'label'         => __('Navigation Dots', 'premium-addons-for-elementor'),
                'tab'           => CONTROLS_MANAGER::TAB_STYLE,
                'condition' => [
                    'navigation_dots'    => 'yes'
                ]
            ]
        );
        
        $this->start_controls_tabs('navigation_style_tabs');
        
        $this->start_controls_tab('tooltips_style_tab',
            [
                'label'         => __('Tooltips', 'premium-addons-for-elementor'),
                'condition' => [
                    'dots_tooltips_switcher'    => 'yes'
                ]
            ]
        );
        
        $this->add_control('tooltips_color',
            [
                'label'         => __( 'Tooltips Text Color', 'premium-addons-for-elementor' ),
                'type'          => Controls_Manager::COLOR,
                'scheme'        => [
                    'type'  => Scheme_Color::get_type(), 
                    'value' => Scheme_Color::COLOR_1
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-vscroll-tooltip'  => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'dots_tooltips_switcher'    => 'yes'
                ]
            ]
        );
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'          => 'tooltips_typography',
                'scheme'        => Scheme_Typography::TYPOGRAPHY_1,
                'selector'      => '{{WRAPPER}} .premium-vscroll-tooltip span',
                'condition'     => [
                    'dots_tooltips_switcher'    => 'yes'
                ]
            ]
        );
        
        $this->add_control('tooltips_background',
            [
                'label'         => __( 'Tooltips Background', 'premium-addons-for-elementor' ),
                'type'          => Controls_Manager::COLOR,
                'scheme'        => [
                    'type'  => Scheme_Color::get_type(), 
                    'value' => Scheme_Color::COLOR_1
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-vscroll-tooltip'  => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .premium-vscroll-inner .premium-vscroll-dots.right .premium-vscroll-tooltip::after' => 'border-left-color: {{VALUE}}',
                    '{{WRAPPER}} .premium-vscroll-inner .premium-vscroll-dots.left .premium-vscroll-tooltip::after' => 'border-right-color: {{VALUE}}',
                ],
                'condition' => [
                    'dots_tooltips_switcher'    => 'yes'
                ]
            ]
        );
        
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'          =>  'tooltips_border',
                'selector'      => '{{WRAPPER}} .premium-vscroll-tooltip',
                'condition'     => [
                    'dots_tooltips_switcher'    => 'yes'
                ]
            ]
        );
        
        $this->add_control('tooltips_border_radius',
            [
                'label'         => __( 'Border Radius', 'premium-addons-for-elementor' ),
                'type'          => Controls_Manager::SLIDER,
                'size_units'    => ['px', 'em', '%'],
                'selectors'     => [
                    '{{WRAPPER}} .premium-vscroll-tooltip'  => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
                'condition'     => [
                    'dots_tooltips_switcher'    => 'yes'
                ]
            ]
        );
        
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'          => 'tooltips_shadow',
                'selector'      => '{{WRAPPER}} .premium-vscroll-tooltip',
                'condition'     => [
                    'dots_tooltips_switcher'    => 'yes'
                ]
            ]
        );
        
        $this->add_responsive_control('tooltips_margin',
            [
                'label'         => __('Margin', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::DIMENSIONS,
                'size_units'    => ['px', 'em', '%'],
                'selectors'     => [
                    '{{WRAPPER}} .premium-vscroll-tooltip' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'dots_tooltips_switcher'    => 'yes'
                ]
            ]
        );
        
        $this->add_responsive_control('tooltips_padding',
            [
                'label'         => __('Padding', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::DIMENSIONS,
                'size_units'    => ['px', 'em', '%'],
                'selectors'     => [
                    '{{WRAPPER}} .premium-vscroll-tooltip' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'dots_tooltips_switcher'    => 'yes'
                ]
            ]
        );
        
        $this->end_controls_tab();

        $this->start_controls_tab('dots_style_tab',
            [
                'label'         => __('Dots', 'premium-addons-for-elementor'),
            ]
        );
                
        $this->add_control('dots_color',
            [
                'label'         => __( 'Dots Color', 'premium-addons-for-elementor' ),
                'type'          => Controls_Manager::COLOR,
                'scheme'        => [
                    'type'  => Scheme_Color::get_type(), 
                    'value' => Scheme_Color::COLOR_1
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-vscroll-dots .premium-vscroll-nav-link span'  => 'background-color: {{VALUE}};',
                ]
            ]
        );
        
        $this->add_control('active_dot_color',
            [
                'label'         => __( 'Active Dot Color', 'premium-addons-for-elementor' ),
                'type'          => Controls_Manager::COLOR,
                'scheme'        => [
                    'type'  => Scheme_Color::get_type(), 
                    'value' => Scheme_Color::COLOR_2
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-vscroll-dots li.active .premium-vscroll-nav-link span'  => 'background-color: {{VALUE}};',
                ]
            ]
        );
        
        $this->add_control('dots_border_color',
            [
                'label'         => __( 'Dots Border Color', 'premium-addons-for-elementor' ),
                'type'          => Controls_Manager::COLOR,
                'scheme'        => [
                    'type'  => Scheme_Color::get_type(), 
                    'value'=> Scheme_Color::COLOR_2
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-vscroll-dots .premium-vscroll-nav-link span'  => 'border-color: {{VALUE}};',
                ]
            ]
        );
        
        $this->add_responsive_control('dots_border_radius',
            [
                'label'         => __('Border Radius', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::DIMENSIONS,
                'size_units'    => ['px', 'em', '%'],
                'selectors'     => [
                    '{{WRAPPER}} .premium-vscroll-dots .premium-vscroll-nav-link span' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}'
                ],
            ]
        );
        
        $this->end_controls_tab();
        
        $this->start_controls_tab('container_style_tab',
            [
                'label'         => __('Container', 'premium-addons-for-elementor'),
            ]
        );
        
        $this->add_control('navigation_background',
            [
                'label'         => __( 'Background Color', 'premium-addons-for-elementor' ),
                'type'          => Controls_Manager::COLOR,
                'scheme'        => [
                    'type'  => Scheme_Color::get_type(), 
                    'value'=> Scheme_Color::COLOR_1
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-vscroll-dots'  => 'background-color: {{VALUE}}'
                ]
            ]
        );
        
        $this->add_control('navigation_border_radius',
            [
                'label'         => __( 'Border Radius', 'premium-addons-for-elementor' ),
                'type'          => Controls_Manager::SLIDER,
                'size_units'    => ['px', 'em', '%'],
                'selectors'     => [
                    '{{WRAPPER}} .premium-vscroll-dots'  => 'border-radius: {{SIZE}}{{UNIT}};',
                ]
            ]
        );
        
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'label'         => __('Shadow','premium-addons-for-elementor'),
                'name'          => 'navigation_box_shadow',
                'selector'      => '{{WRAPPER}} .premium-vscroll-dots',
            ]
        );
        
        $this->end_controls_tab();
        
        $this->end_controls_tabs();
        
        $this->end_controls_section();
        
        $this->start_controls_section('navigation_menu_style',
            [
                'label'     => __('Navigation Menu', 'premium-addons-for-elementor'),
                'tab'       => CONTROLS_MANAGER::TAB_STYLE,
                'condition' => [
                    'nav_menu_switch'   => 'yes'
                ]
            ]
        );
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'          => 'navigation_items_typography',
                'selector'      => '{{WRAPPER}} .premium-vscroll-nav-menu .premium-vscroll-nav-item .premium-vscroll-nav-link'
            ]
        );
        
        $this->start_controls_tabs('navigation_menu_style_tabs');

        $this->start_controls_tab('normal_style_tab',
            [
                'label'         => __('Normal', 'premium-addons-for-elementor'),
            ]
        );
        
        $this->add_control('normal_color',
            [
                'label'         => __( 'Text Color', 'premium-addons-for-elementor' ),
                'type'          => Controls_Manager::COLOR,
                'scheme'        => [
                    'type'  => Scheme_Color::get_type(), 
                    'value'=> Scheme_Color::COLOR_1
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-vscroll-nav-menu .premium-vscroll-nav-item .premium-vscroll-nav-link'  => 'color: {{VALUE}}'
                ]
            ]
        );
        
        $this->add_control('normal_hover_color',
            [
                'label'         => __( 'Text Hover Color', 'premium-addons-for-elementor' ),
                'type'          => Controls_Manager::COLOR,
                'scheme'        => [
                    'type'  => Scheme_Color::get_type(), 
                    'value'=> Scheme_Color::COLOR_1
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-vscroll-nav-menu .premium-vscroll-nav-item .premium-vscroll-nav-link:hover'  => 'color: {{VALUE}}'
                ]
            ]
        );
        
        $this->add_control('normal_background',
            [
                'label'         => __( 'Background Color', 'premium-addons-for-elementor' ),
                'type'          => Controls_Manager::COLOR,
                'scheme'        => [
                    'type'  => Scheme_Color::get_type(), 
                    'value'=> Scheme_Color::COLOR_2
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-vscroll-nav-menu .premium-vscroll-nav-item'  => 'background-color: {{VALUE}}'
                ]
            ]
        );
        
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'label'         => __('Shadow','premium-addons-for-elementor'),
                'name'          => 'normal_shadow',
                'selector'      => '{{WRAPPER}} .premium-vscroll-nav-menu .premium-vscroll-nav-item'
            ]
        );
        
        $this->end_controls_tab();
        
        $this->start_controls_tab('active_style_tab',
            [
                'label'         => __('Active', 'premium-addons-for-elementor'),
            ]
        );
        
        $this->add_control('active_color',
            [
                'label'         => __( 'Text Color', 'premium-addons-for-elementor' ),
                'type'          => Controls_Manager::COLOR,
                'scheme'        => [
                    'type'  => Scheme_Color::get_type(), 
                    'value'=> Scheme_Color::COLOR_2
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-vscroll-nav-menu .premium-vscroll-nav-item.active .premium-vscroll-nav-link'  => 'color: {{VALUE}}'
                ]
            ]
        );
        
        $this->add_control('active_hover_color',
            [
                'label'         => __( 'Text Hover Color', 'premium-addons-for-elementor' ),
                'type'          => Controls_Manager::COLOR,
                'scheme'        => [
                    'type'  => Scheme_Color::get_type(), 
                    'value'=> Scheme_Color::COLOR_2
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-vscroll-nav-menu .premium-vscroll-nav-item.active .premium-vscroll-nav-link:hover'  => 'color: {{VALUE}}'
                ]
            ]
        );
        
        $this->add_control('active_background',
            [
                'label'         => __( 'Background Color', 'premium-addons-for-elementor' ),
                'type'          => Controls_Manager::COLOR,
                'scheme'        => [
                    'type'  => Scheme_Color::get_type(), 
                    'value'=> Scheme_Color::COLOR_1
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-vscroll-nav-menu .premium-vscroll-nav-item.active'  => 'background-color: {{VALUE}}'
                ]
            ]
        );
        
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'label'         => __('Shadow','premium-addons-for-elementor'),
                'name'          => 'active_shadow',
                'selector'      => '{{WRAPPER}} .premium-vscroll-nav-menu .premium-vscroll-nav-item.active'
            ]
        );
        
        $this->end_controls_tabs();
        
        $this->add_group_control(
            Group_Control_Border::get_type(), 
            [
                'name'          => 'navigation_items_border',
                'selector'      => '{{WRAPPER}} .premium-vscroll-nav-menu .premium-vscroll-nav-item',
                'separator'     => 'before'
            ]
        );

        $this->add_control('navigation_items_border_radius',
            [
                'label'         => __('Border Radius', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SLIDER,
                'size_units'    => ['px','em','%'],
                'selectors'     => [
                    '{{WRAPPER}} .premium-vscroll-nav-menu .premium-vscroll-nav-item'  => 'border-radius: {{SIZE}}{{UNIT}};',
                ]
            ]
        );
        
        $this->add_responsive_control('navigation_items_margin',
            [
                'label'         => __('Margin', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::DIMENSIONS,
                'size_units'    => ['px', 'em', '%'],
                'selectors'     => [
                    '{{WRAPPER}} .premium-vscroll-nav-menu .premium-vscroll-nav-item' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_responsive_control('navigation_items_padding',
            [
                'label'         => __('Padding', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::DIMENSIONS,
                'size_units'    => ['px', 'em', '%'],
                'selectors'     => [
                    '{{WRAPPER}} .premium-vscroll-nav-menu .premium-vscroll-nav-item .premium-vscroll-nav-link' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
        $this->end_controls_section();
        
    }
    
    /**
	 * Render vertical scroll widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 2.7.4
	 * @access protected
	 */
    protected function render() {
        
        $settings = $this->get_settings_for_display();
        
        $id = $this->get_id();
        
        $dots_text = explode(',', $settings['dots_tooltips'] );
        
        $this->add_render_attribute( 'vertical_scroll_wrapper', 'class', 'premium-vscroll-wrap' );
        
        $this->add_render_attribute( 'vertical_scroll_wrapper', 'id', 'premium-vscroll-wrap-' . $id );
        
        $this->add_render_attribute( 'vertical_scroll_inner', 'class', 'premium-vscroll-inner' );
        
        $this->add_render_attribute( 'vertical_scroll_inner', 'id', 'premium-vscroll-' . $id );
        
        $this->add_render_attribute( 'vertical_scroll_dots', 'class', array(
                'premium-vscroll-dots',
                'premium-vscroll-dots-hide',
                $settings['navigation_dots_pos'],
                $settings['navigation_dots_v_pos'],
                $settings['dots_shape']
            )
        );
        
        if( '' !== $settings['dots_animation'] ) {
            $this->add_render_attribute( 'vertical_scroll_dots', 'class', 'elementor-invisible' );
        }
        
        $this->add_render_attribute( 'vertical_scroll_dots_list', 'class', array( 'premium-vscroll-dots-list' ) );
        
        $this->add_render_attribute( 'vertical_scroll_menu', [
            'id'    => 'premium-vscroll-nav-menu-' . $id,
            'class' => [
                'premium-vscroll-nav-menu',
                $settings['navigation_menu_pos']
            ]
        ]);
        
        $this->add_render_attribute( 'vertical_scroll_sections_wrap', [
            'class' => 'premium-vscroll-sections-wrap',
            'id'    => 'premium-vscroll-sections-wrap-' . $id
        ]);
        
        $vscroll_settings = [
            'id'            => $id, 
            'speed'         => ! empty( $settings['scroll_speed'] ) ? $settings['scroll_speed'] * 1000 : 700,
            'offset'        => ! empty( $settings['scroll_offset'] ) ? $settings['scroll_offset'] : 0,
            'tooltips'      => 'yes' == $settings['dots_tooltips_switcher'] ? true : false,
            'dotsText'      => $dots_text,
            'dotsPos'       => $settings['navigation_dots_pos'],
            'dotsVPos'      => $settings['navigation_dots_v_pos'],
            'fullSection'   => 'yes' == $settings['full_section'] ? true : false,
            'fullTouch'     => 'yes' == $settings['full_section_touch'] ? true : false,
            'addToHistory'  => 'yes' == $settings['save_state'] ? true : false,
            'animation'     => $settings['dots_animation'],
            'duration'      => $settings['dots_animation_duration'],
        ];
        
        $templates = 'templates' === $settings['content_type'] ? $settings['section_repeater'] : $settings['id_repeater'];
        
        $nav_items = $settings['nav_menu_repeater'];

        ?>

        <div <?php echo $this->get_render_attribute_string('vertical_scroll_wrapper'); ?> data-settings='<?php echo wp_json_encode($vscroll_settings); ?>'>
            <?php if ('yes' == $settings['nav_menu_switch'] ) : ?>
                <ul <?php echo $this->get_render_attribute_string('vertical_scroll_menu'); ?>>
                    <?php foreach( $nav_items as $index => $item ) :
                        $section_id = $this->get_template_id( $index );
                    ?>
                        <li class="premium-vscroll-nav-item" data-menuanchor="<?php echo esc_attr( $section_id ); ?>">
                            <div class="premium-vscroll-nav-link">
                                <?php echo $item['nav_menu_item']; ?>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
            <div <?php echo $this->get_render_attribute_string('vertical_scroll_inner'); ?>>
                <div <?php echo $this->get_render_attribute_string('vertical_scroll_dots'); ?>>
                    <ul <?php echo $this->get_render_attribute_string('vertical_scroll_dots_list'); ?>>
                        <?php foreach( $templates as $index => $section ) :
                            $section_id = $this->get_template_id( $index );
                        ?>
                            <li data-index="<?php echo $index; ?>" data-menuanchor="<?php echo esc_attr( $section_id ); ?>" class="premium-vscroll-dot-item"><div class="premium-vscroll-nav-link"><span></span></div></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <?php if( 'templates' === $settings['content_type'] ) : ?>
                    <div <?php echo $this->get_render_attribute_string('vertical_scroll_sections_wrap'); ?>>

                        <?php foreach( $templates as $index => $section ) :
                                $section_id = $this->get_template_id( $index );

                                $this->add_render_attribute('section_' . $index, [
                                    'id'    => $section_id,
                                    'class' => [
                                        'premium-vscroll-temp',
                                        'premium-vscroll-temp-' . $id
                                    ]
                                ]);
                            ?>
                            <div <?php echo $this->get_render_attribute_string('section_' . $index); ?>>
                                <?php 
                                    $template_title = $section['section_template'];
                                    echo $this->getTemplateInstance()->get_template_content( $template_title );
                                ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
    <?php }

    /**
     * Get template ID
     * 
     * @since 3.21.0
     * @access protected
     * 
     * @param string $index template index
     *
     * @return string $id template ID
     */
    protected function get_template_id( $index ) {

        $settings = $this->get_settings_for_display();

        $checkType = 'templates' === $settings['content_type'] ? true : false;

        $templates = $checkType ? $settings['section_repeater'] : $settings['id_repeater'];

        if( ! $checkType ) {

            $id = $templates[ $index ][ 'section_id' ];

            return $id;
        }

        $widget_id = $this->get_id();

        $id = 'section_' . $widget_id . $index;

        if( ! empty( $templates[ $index ][ 'template_id' ] ) ) {
            $id = $templates[ $index ][ 'template_id' ];
        }

        return $id;

    }
}