<?php
/**
 * Elementor default widgets enhancements
 *
 * @package Happy_Addons
 */
namespace Happy_Addons\Elementor\Extension;

use Elementor\Controls_Manager;
use Elementor\Widget_Base;

defined('ABSPATH') || die();

class Widgets_Extended {

    public static function init() {
        add_action( 'elementor/element/button/section_style/after_section_start', [ __CLASS__, 'add_button_controls' ] );
    }

    public static function add_button_controls( Widget_Base $widget ) {
        $widget->add_control(
            'ha_fixed_size_toggle',
            [
                'label' => __( 'Fixed Size', 'happy-elementor-addons' ),
                'type' => Controls_Manager::POPOVER_TOGGLE,
                'return_value' => 'yes',
            ]
        );

        $widget->start_popover();

        $widget->add_responsive_control(
            'ha_height',
            [
                'label' => __( 'Height', 'happy-elementor-addons' ),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'unit' => 'px',
                ],
                'range' => [
                    'px' => [
                        'max' => 500,
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-button' => 'height: {{SIZE}}{{UNIT}};'
                ],
                'condition' => [
                    'ha_fixed_size_toggle' => 'yes',
                ]
            ]
        );

        $widget->add_responsive_control(
            'ha_width',
            [
                'label' => __( 'Width', 'happy-elementor-addons' ),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'unit' => 'px',
                ],
                'range' => [
                    'px' => [
                        'max' => 500,
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-button' => 'width: {{SIZE}}{{UNIT}};'
                ],
                'condition' => [
                    'ha_fixed_size_toggle' => 'yes',
                ]
            ]
        );

        $widget->add_control(
            'ha_align_x',
            [
                'type' => Controls_Manager::CHOOSE,
                'label' => __( 'Horizontal Align', 'happy-addons-pro' ),
                'default' => 'center',
                'toggle' => false,
                'options' => [
                    'left' => [
                        'title' =>  __( 'Left', 'happy-addons-pro' ),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'center' => [
                        'title' =>  __( 'Center', 'happy-addons-pro' ),
                        'icon' => 'eicon-h-align-center',
                    ],
                    'right' => [
                        'title' =>  __( 'Right', 'happy-addons-pro' ),
                        'icon' => 'eicon-h-align-right',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-button' => '{{VALUE}}'
                ],
                'selectors_dictionary' => [
                    'left' => '-webkit-box-pack: start; -ms-flex-pack: start; justify-content: flex-start;',
                    'center' => '-webkit-box-pack: center; -ms-flex-pack: center; justify-content: center;',
                    'right' => '-webkit-box-pack: end; -ms-flex-pack: end; justify-content: flex-end;',
                ],
                'condition' => [
                    'ha_fixed_size_toggle' => 'yes',
                ],
            ]
        );

        $widget->add_control(
            'ha_align_y',
            [
                'type' => Controls_Manager::CHOOSE,
                'label' => __( 'Vertical Align', 'happy-addons-pro' ),
                'default' => 'center',
                'toggle' => false,
                'options' => [
                    'top' => [
                        'title' =>  __( 'Top', 'happy-addons-pro' ),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'center' => [
                        'title' =>  __( 'Center', 'happy-addons-pro' ),
                        'icon' => 'eicon-v-align-middle',
                    ],
                    'bottom' => [
                        'title' =>  __( 'Right', 'happy-addons-pro' ),
                        'icon' => 'eicon-v-align-bottom',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-button' => '{{VALUE}}',
                ],
                'selectors_dictionary' => [
                    'top' => '-webkit-box-align: start; -ms-flex-align: start; align-items: flex-start;',
                    'center' => '-webkit-box-align: center; -ms-flex-align: center; align-items: center;',
                    'bottom' => '-webkit-box-align: end; -ms-flex-align: end; align-items: flex-end;',
                ],
                'condition' => [
                    'ha_fixed_size_toggle' => 'yes',
                ],
            ]
        );

        $widget->add_control(
            'ha_flex_display',
            [
                'type' => Controls_Manager::HIDDEN,
                'default' => 'inline-flex',
                'selectors' => [
                    '{{WRAPPER}} .elementor-button' => 'display: -webkit-inline-box; display: -ms-inline-flexbox; display: inline-flex;',
                ],
                'condition' => [
                    'ha_fixed_size_toggle' => 'yes',
                    'ha_align_x!' => '',
                    'ha_align_y!' => '',
                ],
            ]
        );

        $widget->end_popover();
    }
}

Widgets_Extended::init();
