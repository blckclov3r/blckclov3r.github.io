<?php

/**
 * Premium Google Maps.
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
use PremiumAddons\Admin\Settings\Maps;
use PremiumAddons\Helper_Functions;

if ( ! defined( 'ABSPATH' ) ) exit; // If this file is called directly, abort.

/**
 * Class Premium_Maps
 */
class Premium_Maps extends Widget_Base {
    
    public function get_name() {
        return 'premium-addon-maps';
    }
    
    public function is_reload_preview_required() {
        return true;
    }

    public function get_title() {
		return sprintf( '%1$s %2$s', Helper_Functions::get_prefix(), __('Google Maps', 'premium-addons-for-elementor') );
	}
    
    public function get_icon() {
        return 'pa-maps';
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
            'google-maps-cluster',
            'premium-maps-api-js',
            'premium-maps-js'
        ];
    }
    
    public function get_keywords() {
		return [ 'google', 'marker', 'pin' ];
	}
    
    public function get_custom_help_url() {
		return 'https://premiumaddons.com/support/';
	}

    /**
	 * Register Google Maps controls.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
    protected function _register_controls() {

        $this->start_controls_section('premium_maps_map_settings',
            [
                'label'         => __('Center Location', 'premium-addons-for-elementor'),
            ]
        );
        
        $settings = Maps::get_enabled_keys();
        
        if( empty( $settings['premium-map-api'] ) ) {
            $this->add_control('premium_maps_api_url',
                [
                    'label'         => '<span style="line-height: 1.4em;">Premium Maps requires an API key. Get your API key from <a target="_blank" href="https://developers.google.com/maps/documentation/javascript/get-api-key">here</a> and add it to Premium Addons admin page. Go to Dashboard -> Premium Addons for Elementor -> Google Maps API</span>',
                    'type'          => Controls_Manager::RAW_HTML,
                ]
            );
        }
        
        $this->add_control('premium_map_ip_location',
            [
                'label'         => __( 'Get User Location', 'premium-addons-for-elementor' ),
                'description'   => __('Get center location from visitor\'s location','premium-addons-for-elementor'),
                'type'          => Controls_Manager::SWITCHER,
                'return_value'  => 'true'
            ]
        );
        
        $this->add_control('premium_map_location_finder',
            [
                'label'         => __( 'Latitude & Longitude Finder', 'premium-addons-for-elementor' ),
                'type'          => Controls_Manager::SWITCHER,
                'condition'     => [
                    'premium_map_ip_location!'  => 'true'
                ]
            ]
        );
        
        $this->add_control('premium_map_notice',
		    [
			    'label' => __( 'Find Latitude & Longitude', 'elementor' ),
			    'type'  => Controls_Manager::RAW_HTML,
			    'raw'   => '<form onsubmit="getAddress(this);" action="javascript:void(0);"><input type="text" id="premium-map-get-address" class="premium-map-get-address" style="margin-top:10px; margin-bottom:10px;"><input type="submit" value="Search" class="elementor-button elementor-button-default" onclick="getAddress(this)"></form><div class="premium-address-result" style="margin-top:10px; line-height: 1.3; font-size: 12px;"></div>',
			    'label_block' => true,
                'condition'     => [
                    'premium_map_location_finder'   => 'yes',
                    'premium_map_ip_location!'  => 'true'
                ]
		    ]
	    );
        
        
        $this->add_control('premium_maps_center_lat',
                [
                    'label'         => __('Center Latitude', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::TEXT,
                    'dynamic'       => [ 'active' => true ],
                    'description'   => __('Center latitude and longitude are required to identify your location', 'premium-addons-for-elementor'),
                    'default'       => '18.591212',
                    'label_block'   => true,
                    'condition'     => [
                        'premium_map_ip_location!'  => 'true'
                        ]
                    ]
                );

        $this->add_control('premium_maps_center_long',
                [
                    'label'         => __('Center Longitude', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::TEXT,
                    'dynamic'       => [ 'active' => true ],
                    'description'   => __('Center latitude and longitude are required to identify your location', 'premium-addons-for-elementor'),
                    'default'       => '73.741261',
                    'label_block'   => true,
                    'condition'     => [
                        'premium_map_ip_location!'  => 'true'
                        ]
                    ]
                );
        
        $this->end_controls_section();
        
        $this->start_controls_section('premium_maps_map_pins_settings',
            [
                'label'         => __('Markers', 'premium-addons-for-elementor'),
            ]
        );
        
        $this->add_control('premium_maps_markers_width',
                [
                    'label'         => __('Max Width', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::NUMBER,
                    'title'         => __('Set the Maximum width for markers description box','premium-addons-for-elementor'),
                    ]
                );
         
        $repeater = new REPEATER();

        $repeater->add_control('pin_icon',
            [
                'label'         => __('Custom Icon', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::MEDIA,
                'dynamic'       => [ 'active' => true ],
            ]
        );

        $repeater->add_control('pin_icon_size',
			[
				'label' => __( 'Size', 'premium-addons-for-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'size_units'    => ['px', 'em'],
				'range' => [
					'px' => [
		                'min' => 1,
		                'max' => 200,
                    ],
                    'em' => [
		                'min' => 1,
		                'max' => 20,
		            ]
				]
			]
		);
        
        $repeater->add_control('premium_map_pin_location_finder',
            [
                'label'         => __( 'Latitude & Longitude Finder', 'premium-addons-for-elementor' ),
                'type'          => Controls_Manager::SWITCHER,
            ]
        );
        
        $repeater->add_control('premium_map_pin_notice',
		    [
			    'label' => __( 'Find Latitude & Longitude', 'elementor' ),
			    'type'  => Controls_Manager::RAW_HTML,
			    'raw'   => '<form onsubmit="getPinAddress(this);" action="javascript:void(0);"><input type="text" id="premium-map-get-address" class="premium-map-get-address" style="margin-top:10px; margin-bottom:10px;"><input type="submit" value="Search" class="elementor-button elementor-button-default" onclick="getPinAddress(this)"></form><div class="premium-address-result" style="margin-top:10px; line-height: 1.3; font-size: 12px;"></div>',
			    'label_block' => true,
                'condition' => [
                    'premium_map_pin_location_finder'   => 'yes'
                ]
		    ]
	    );
        
        $repeater->add_control('map_latitude',
            [
                'label'         => __('Latitude', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::TEXT,
                'dynamic'       => [ 'active' => true ],
                'description'   => 'Click <a href="https://www.latlong.net/" target="_blank">here</a> to get your location coordinates',
                'label_block'   => true,
            ]
        );
        
        $repeater->add_control('map_longitude',
            [
                'name'          => 'map_longitude',
                'label'         => __('Longitude', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::TEXT,
                'dynamic'       => [ 'active' => true ],
                'description'   => 'Click <a href="https://www.latlong.net/" target="_blank">here</a> to get your location coordinates',
                'label_block'   => true,
            ]
        );
        
        $repeater->add_control('pin_title',
            [
                'label'         => __('Title', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::TEXT,
                'dynamic'       => [ 'active' => true ],
                'label_block'   => true,
            ]
        );
        
        $repeater->add_control('pin_desc',
            [
                'label'         => __('Description', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::WYSIWYG,
                'dynamic'       => [ 'active' => true ],
                'label_block'   => true,
            ]
        );
        
        $this->add_control('premium_maps_map_pins',
            [
                'label'         => __('Map Pins', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::REPEATER,
                'default'       => [
                    'map_latitude'      => '18.591212',
                    'map_longitude'     => '73.741261',
                    'pin_title'         => __('Premium Google Maps', 'premium-addons-for-elementor'),
                    'pin_desc'          => __('Add an optional description to your map pin', 'premium-addons-for-elementor'),
                ],
                'fields'       => $repeater->get_controls(),
                'title_field'   => '{{{ pin_title }}}'
            ]
        );
        
        $this->end_controls_section();
        
        $this->start_controls_section('premium_maps_controls_section',
                [
                    'label'         => __('Controls', 'premium-addons-for-elementor'),
                    ]
                );
        
        $this->add_control('premium_maps_map_type',
                [
                    'label'         => __( 'Map Type', 'premium-addons-for-elementor' ),
                    'type'          => Controls_Manager::SELECT,
                    'options'       => [
                        'roadmap'       => __( 'Road Map', 'premium-addons-for-elementor' ),
                        'satellite'     => __( 'Satellite', 'premium-addons-for-elementor' ),
                        'terrain'       => __( 'Terrain', 'premium-addons-for-elementor' ),
                        'hybrid'        => __( 'Hybrid', 'premium-addons-for-elementor' ),
                        ],
                    'default'       => 'roadmap',
                    ]
                );
        
        $this->add_responsive_control('premium_maps_map_height',
                [
                    'label'         => __( 'Height', 'premium-addons-for-elementor' ),
                    'type'          => Controls_Manager::SLIDER,
                    'default'       => [
                            'size' => 500,
                    ],
                    'range'         => [
                            'px' => [
                                'min' => 80,
                                'max' => 1400,
                            ],
                    ],
                    'selectors'     => [
                            '{{WRAPPER}} .premium_maps_map_height' => 'height: {{SIZE}}px;',
                    ],
                ]
  		);
        
        $this->add_control('premium_maps_map_zoom',
                [
                    'label'         => __( 'Zoom', 'premium-addons-for-elementor' ),
                    'type'          => Controls_Manager::SLIDER,
                    'default'       => [
                        'size' => 12,
                    ],
                    'range'         => [
                        'px' => [
                                'min' => 0,
                                'max' => 22,
                        ],
                    ],
                ]
                );

        $this->add_control('disable_drag',
            [
                'label'         => __( 'Disable Map Drag', 'premium-addons-for-elementor' ),
                'type'          => Controls_Manager::SWITCHER,
            ]
        );
        
        $this->add_control('premium_maps_map_option_map_type_control',
                [
                    'label'         => __( 'Map Type Controls', 'premium-addons-for-elementor' ),
                    'type'          => Controls_Manager::SWITCHER,
                ]
                );

        $this->add_control('premium_maps_map_option_zoom_controls',
                [
                    'label'         => __( 'Zoom Controls', 'premium-addons-for-elementor' ),
                    'type'          => Controls_Manager::SWITCHER,
                ]
                );
		
        $this->add_control('premium_maps_map_option_streeview',
                [
                    'label'         => __( 'Street View Control', 'premium-addons-for-elementor' ),
                    'type'          => Controls_Manager::SWITCHER,
                ]
                );
		
        $this->add_control('premium_maps_map_option_fullscreen_control',
                [
                    'label'         => __( 'Fullscreen Control', 'premium-addons-for-elementor' ),
                    'type'          => Controls_Manager::SWITCHER,
                ]
                );
		
        $this->add_control('premium_maps_map_option_mapscroll',
                [
                    'label'         => __( 'Scroll Wheel Zoom', 'premium-addons-for-elementor' ),
                    'type'          => Controls_Manager::SWITCHER,
                ]
                );
        
        $this->add_control('premium_maps_marker_open',
                [
                    'label'         => __( 'Info Container Always Opened', 'premium-addons-for-elementor' ),
                    'type'          => Controls_Manager::SWITCHER,
                ]
                );
        
        $this->add_control('premium_maps_marker_hover_open',
                [
                    'label'         => __( 'Info Container Opened when Hovered', 'premium-addons-for-elementor' ),
                    'type'          => Controls_Manager::SWITCHER,
                ]
                );
        
        $this->add_control('premium_maps_marker_mouse_out',
                [
                    'label'         => __( 'Info Container Closed when Mouse Out', 'premium-addons-for-elementor' ),
                    'type'          => Controls_Manager::SWITCHER,
                    'condition'     => [
                        'premium_maps_marker_hover_open'   => 'yes'
                        ]
                    ]
                );

        if( $settings['premium-map-cluster'] ) {
            $this->add_control('premium_maps_map_option_cluster',
                [
                    'label'         => __( 'Marker Clustering', 'premium-addons-for-elementor' ),
                    'type'          => Controls_Manager::SWITCHER,
                ]
            );
        }
        
        
        $this->end_controls_section();
        
        $this->start_controls_section('premium_maps_custom_styling_section',
            [
                'label'         => __('Map Style', 'premium-addons-for-elementor'),
            ]
        );
        
        $this->add_control('premium_maps_custom_styling',
            [
                'label'         => __('JSON Code', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::TEXTAREA,
                'description'   => 'Get your custom styling from <a href="https://snazzymaps.com/" target="_blank">here</a>',
                'label_block'   => true,
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
                'raw'             => sprintf( __( '%1$s Getting started » %2$s', 'premium-addons-for-elementor' ), '<a href="https://premiumaddons.com/docs/google-maps-widget-tutorial/?utm_source=pa-dashboard&utm_medium=pa-editor&utm_campaign=pa-plugin" target="_blank" rel="noopener">', '</a>' ),
                'type'            => Controls_Manager::RAW_HTML,
                'content_classes' => 'editor-pa-doc',
            ]
        );
        
        $this->add_control('doc_2',
            [
                'raw'             => sprintf( __( '%1$s Getting your API key » %2$s', 'premium-addons-for-elementor' ), '<a href="https://premiumaddons.com/docs/getting-your-api-key-for-google-reviews/?utm_source=pa-dashboard&utm_medium=pa-editor&utm_campaign=pa-plugin" target="_blank" rel="noopener">', '</a>' ),
                'type'            => Controls_Manager::RAW_HTML,
                'content_classes' => 'editor-pa-doc',
            ]
        );
        
        $this->end_controls_section();
        
        $this->start_controls_section('premium_maps_pin_title_style',
            [
                'label'         => __('Title', 'premium-addons-for-elementor'),
                'tab'           => Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_control('premium_maps_pin_title_color',
            [
                'label'         => __('Color', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::COLOR,
                'scheme'        => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_1,
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-maps-info-title'   => 'color: {{VALUE}};',
                ]
            ]
        );
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
                [
                    'name'          => 'pin_title_typography',
                    'scheme'        => Scheme_Typography::TYPOGRAPHY_1,
                    'selector'      => '{{WRAPPER}} .premium-maps-info-title',
                ]
                );
        
        $this->add_responsive_control('premium_maps_pin_title_margin',
                [
                    'label'         => __('Margin', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::DIMENSIONS,
                    'size_units'    => ['px', 'em', '%'],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-maps-info-title'   => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        ]
                    ]
                );
        
        /*Pin Title Padding*/
        $this->add_responsive_control('premium_maps_pin_title_padding',
                [
                    'label'         => __('Padding', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::DIMENSIONS,
                    'size_units'    => ['px', 'em', '%'],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-maps-info-title'   => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        ]
                    ]
                );
        
        /*Pin Title ALign*/
        $this->add_responsive_control('premium_maps_pin_title_align',
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
                    'default'       => 'center',
                    'selectors'     => [
                        '{{WRAPPER}} .premium-maps-info-title' => 'text-align: {{VALUE}};',
                        ],
                    ]
                );
                
        /*End Title Style Section*/
        $this->end_controls_section();
        
        /*Start Pin Style Section*/
        $this->start_controls_section('premium_maps_pin_text_style',
                [
                    'label'         => __('Description', 'premium-addons-for-elementor'),
                    'tab'           => Controls_Manager::TAB_STYLE,
                ]
                );
        
        $this->add_control('premium_maps_pin_text_color',
                [
                    'label'         => __('Color', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::COLOR,
                    'scheme'        => [
                        'type'  => Scheme_Color::get_type(),
                        'value' => Scheme_Color::COLOR_2,
                    ],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-maps-info-desc'   => 'color: {{VALUE}};',
                        ]
                    ]
                );
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'          => 'pin_text_typo',
                'scheme'        => Scheme_Typography::TYPOGRAPHY_1,
                'selector'      => '{{WRAPPER}} .premium-maps-info-desc',
            ]
        );
        
        $this->add_responsive_control('premium_maps_pin_text_margin',
            [
                'label'         => __('Margin', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::DIMENSIONS,
                'size_units'    => ['px', 'em', '%'],
                'selectors'     => [
                    '{{WRAPPER}} .premium-maps-info-desc'   => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );
        
        $this->add_responsive_control('premium_maps_pin_text_padding',
            [
                'label'         => __('Padding', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::DIMENSIONS,
                'size_units'    => ['px', 'em', '%'],
                'selectors'     => [
                    '{{WRAPPER}} .premium-maps-info-desc'   => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );
        
        $this->add_responsive_control('premium_maps_pin_description_align',
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
                'default'       => 'center',
                'selectors'     => [
                    '{{WRAPPER}} .premium-maps-info-desc' => 'text-align: {{VALUE}};',
                ],
            ]
        );
        
        $this->end_controls_section();
        
        $this->start_controls_section('premium_maps_box_style',
            [
                'label'         => __('Map', 'premium-addons-for-elementor'),
                'tab'           => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'              => 'map_border',
                'selector'          => '{{WRAPPER}} .premium-maps-container',
            ]
        );

        $this->add_control('premium_maps_box_radius',
                [
                    'label'         => __('Border Radius', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::SLIDER,
                    'size_units'    => ['px', '%', 'em'],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-maps-container,{{WRAPPER}} .premium_maps_map_height' => 'border-radius: {{SIZE}}{{UNIT}};'
                    ]
                ]
            );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'label'         => __('Shadow','premium-addons-for-elementor'),
                'name'          => 'premium_maps_box_shadow',
                'selector'      => '{{WRAPPER}} .premium-maps-container',
            ]
        );

        $this->add_responsive_control('premium_maps_box_margin',
            [
                'label'         => __('Margin', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::DIMENSIONS,
                'size_units'    => [ 'px', 'em', '%' ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-maps-container' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ]
            ]
        );
        
        $this->add_responsive_control('premium_maps_box_padding',
            [
                'label'         => __('Padding', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::DIMENSIONS,
                'size_units'    => [ 'px', 'em', '%' ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-maps-container' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ]
            ]
        );
        
        $this->end_controls_section();
        
    }

    /**
	 * Render Google Maps widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
    protected function render() {
        
        $settings = $this->get_settings_for_display();
        
        $map_pins = $settings['premium_maps_map_pins'];

        $street_view = 'yes' == $settings['premium_maps_map_option_streeview'] ? 'true' : 'false';
        
        $scroll_wheel = 'yes' == $settings['premium_maps_map_option_mapscroll'] ? 'true' : 'false';
        
        $enable_full_screen = 'yes' == $settings['premium_maps_map_option_fullscreen_control'] ? 'true' : 'false';
        
        $enable_zoom_control = 'yes' == $settings['premium_maps_map_option_zoom_controls'] ? 'true' : 'false';
        
        $map_type_control = 'yes' == $settings['premium_maps_map_option_map_type_control'] ? 'true' : 'false';
        
        $automatic_open = 'yes' == $settings['premium_maps_marker_open'] ? 'true' : 'false';
        
        $hover_open = 'yes' == $settings['premium_maps_marker_hover_open'] ? 'true' : 'false';
        
        $hover_close = 'yes' == $settings['premium_maps_marker_mouse_out'] ? 'true' : 'false';
        
        $marker_cluster = false; 
        
        $is_cluster_enabled = Maps::get_enabled_keys()['premium-map-cluster'];
        
        if( $is_cluster_enabled ) {
            $marker_cluster = 'yes' == $settings['premium_maps_map_option_cluster'] ? 'true' : 'false';
        }
        
        $centerlat = !empty($settings['premium_maps_center_lat']) ? $settings['premium_maps_center_lat'] : 18.591212;
        
        $centerlong = !empty($settings['premium_maps_center_long']) ? $settings['premium_maps_center_long'] : 73.741261;
        
        $marker_width = !empty($settings['premium_maps_markers_width']) ? $settings['premium_maps_markers_width'] : 1000;
        
        $get_ip_location = $settings['premium_map_ip_location'];
        
        if( 'true' == $get_ip_location ) {
            
            if ( isset( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
                $http_x_headers = explode( ',', $_SERVER['HTTP_X_FORWARDED_FOR'] );

                $_SERVER['REMOTE_ADDR'] = $http_x_headers[0];
            }
            $ipAddress = $_SERVER['REMOTE_ADDR'];

            $env = unserialize(file_get_contents("http://www.geoplugin.net/php.gp?ip=$ipAddress"));

            $centerlat = isset( $env['geoplugin_latitude'] ) ? $env['geoplugin_latitude'] : $centerlat;
            
            $centerlong = isset( $env['geoplugin_longitude'] ) ? $env['geoplugin_longitude'] : $centerlong;
            
        }
        
        $map_settings = [
            'zoom'                  => $settings['premium_maps_map_zoom']['size'],
            'maptype'               => $settings['premium_maps_map_type'],
            'streetViewControl'     => $street_view,
            'centerlat'             => $centerlat,
            'centerlong'            => $centerlong, 
            'scrollwheel'           => $scroll_wheel,
            'fullScreen'            => $enable_full_screen,
            'zoomControl'           => $enable_zoom_control,
            'typeControl'           => $map_type_control,
            'automaticOpen'         => $automatic_open,
            'hoverOpen'             => $hover_open,
            'hoverClose'            => $hover_close,
            'cluster'               => $marker_cluster,
            'drag'                  => $settings['disable_drag']
        ];
        
        $this->add_render_attribute('style_wrapper', 'data-style', $settings['premium_maps_custom_styling']);
    ?>

    <div class="premium-maps-container" id="premium-maps-container">
        <?php if( count( $map_pins ) ) { ?>
	        <div class="premium_maps_map_height" data-settings='<?php echo wp_json_encode( $map_settings ); ?>' <?php echo $this->get_render_attribute_string('style_wrapper'); ?>>
			<?php
        	foreach( $map_pins as $index => $pin ) {
                
                $key = 'map_marker_' . $index;

                $this->add_render_attribute( $key, [
                    'class' => 'premium-pin',
                    'data-lng' => $pin['map_longitude'],
                    'data-lat'  => $pin['map_latitude'],
                    'data-icon' => $pin['pin_icon']['url'],
                    'data-icon-size' => $pin['pin_icon_size']['size'],
                    'data-max-width' => $marker_width
                ]);

				?>
		        <div <?php echo $this->get_render_attribute_string( $key ); ?>>
                    <?php if( ! empty( $pin['pin_title'] )|| !empty( $pin['pin_desc'] ) ) : ?>
                        <div class='premium-maps-info-container'><p class='premium-maps-info-title'><?php echo $pin['pin_title']; ?></p><div class='premium-maps-info-desc'><?php echo $pin['pin_desc']; ?></div></div>
                    <?php endif; ?>
		        </div>
		        <?php
	        }
	        ?>
	        </div>
			<?php
        } ?>
    </div>
    <?php
    }
}