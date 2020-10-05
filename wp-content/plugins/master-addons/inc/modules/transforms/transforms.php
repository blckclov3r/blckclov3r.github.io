<?php
namespace MasterAddons\Inc\Classes;

use \Elementor\Controls_Manager;
use \Elementor\Element_Base;

use \MasterAddons\Inc\Classes\JLTMA_Extension_Prototype;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) { exit; };

class JLTMA_Extension_Transforms extends JLTMA_Extension_Prototype{

    private static $instance = null;
    public $name = 'Transforms';
    public $has_controls = true;

    private function add_controls($element, $args) {

        $element_type = $element->get_type();

        $element->add_control(
            'enabled_transform', [
                'label' => __('Enabled Transforms', MELA_TD ),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
            ]
        );

        $element->start_controls_tabs(
            'jltma_transform_fx_tabs',
            [
                'condition' => [
                    'enabled_transform' => 'yes',
                ],
            ]
        );


        $element->start_controls_tab(
            'jltma_transform_fx_tab_normal',
            [
                'label' => __( 'Normal', MELA_TD ),
                'condition' => [
                    'enabled_transform' => 'yes',
                ],
            ]
        );
        

        $element->add_control(
            'jltma_transform_fx_translate_toggle',
            [
                'label'        => __( 'Translate', MELA_TD ),
                'type'         => Controls_Manager::POPOVER_TOGGLE,
                'return_value' => 'yes',
                'condition'    => [
                    'enabled_transform' => 'yes',
                ],
            ]
        );

        $element->start_popover();

        $element->add_responsive_control(
            'jltma_transform_fx_translate_x',
            [
                'label'      => __( 'Translate X', MELA_TD ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [
                    'px' => [
                        'min' => - 1000,
                        'max' => 1000,
                    ],
                ],
                'condition'  => [
                    'jltma_transform_fx_translate_toggle' => 'yes',
                    'enabled_transform'                  => 'yes',
                ],
            ]
        );

        $element->add_responsive_control(
            'jltma_transform_fx_translate_y',
            [
                'label'      => __( 'Translate Y', MELA_TD ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [
                    'px' => [
                        'min' => - 1000,
                        'max' => 1000,
                    ],
                ],
                'condition'  => [
                    'jltma_transform_fx_translate_toggle' => 'yes',
                    'enabled_transform'                  => 'yes',
                ],
                'selectors'  => [
                    '(desktop){{WRAPPER}}' =>
                        '-ms-transform:'
                        . 'translate({{jltma_transform_fx_translate_x.SIZE || 0}}px, {{jltma_transform_fx_translate_y.SIZE || 0}}px);'
                        . '-webkit-transform:'
                        . 'translate({{jltma_transform_fx_translate_x.SIZE || 0}}px, {{jltma_transform_fx_translate_y.SIZE || 0}}px);'
                        . 'transform:'
                        . 'translate({{jltma_transform_fx_translate_x.SIZE || 0}}px, {{jltma_transform_fx_translate_y.SIZE || 0}}px);',
                    '(tablet){{WRAPPER}}'  =>
                        '-ms-transform:'
                        . 'translate({{jltma_transform_fx_translate_x_tablet.SIZE || 0}}px, {{jltma_transform_fx_translate_y_tablet.SIZE || 0}}px);'
                        . '-webkit-transform:'
                        . 'translate({{jltma_transform_fx_translate_x_tablet.SIZE || 0}}px, {{jltma_transform_fx_translate_y_tablet.SIZE || 0}}px);'
                        . 'transform:'
                        . 'translate({{jltma_transform_fx_translate_x_tablet.SIZE || 0}}px, {{jltma_transform_fx_translate_y_tablet.SIZE || 0}}px);',
                    '(mobile){{WRAPPER}}'  =>
                        '-ms-transform:'
                        . 'translate({{jltma_transform_fx_translate_x_mobile.SIZE || 0}}px, {{jltma_transform_fx_translate_y_mobile.SIZE || 0}}px);'
                        . '-webkit-transform:'
                        . 'translate({{jltma_transform_fx_translate_x_mobile.SIZE || 0}}px, {{jltma_transform_fx_translate_y_mobile.SIZE || 0}}px);'
                        . 'transform:'
                        . 'translate({{jltma_transform_fx_translate_x_mobile.SIZE || 0}}px, {{jltma_transform_fx_translate_y_mobile.SIZE || 0}}px);',
                ],
            ]
        );

        $element->end_popover();



        // $element->add_responsive_control(
        //     'jltma_transform_fx_perspective',
        //     [
        //         'label' => esc_html__( 'Perspective Size', MELA_TD ),
        //         'type' => Controls_Manager::SLIDER,
        //         'range' => [
        //             'px' => [
        //                 'step' => 1,
        //                 'min' => 0,
        //                 'max' => 10000,
        //             ],
        //         ],
        //         'default' => [
        //             'size' => '1000',
        //         ],
        //         'condition' => [
        //             'enabled_transform' => 'yes',
        //             // 'jltma_morphing_effects' => ['rotateX', 'rotateY'],
        //         ],
        //         'selectors' => [
        //             '{{WRAPPER}}' => 'perspective: {{SIZE}}px;',
        //         ],
        //     ]
        // );



        $element->add_control(
            'jltma_transform_fx_rotate_toggle',
            [
                'label'     => __( 'Rotate', MELA_TD ),
                'type'      => Controls_Manager::POPOVER_TOGGLE,
                'condition' => [
                    'enabled_transform' => 'yes',
                ],
            ]
        );

        $element->start_popover();

        $element->add_responsive_control(
            'jltma_transform_fx_rotate_x',
            [
                'label'      => __( 'Rotate X', MELA_TD ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [
                    'px' => [
                        'min' => - 180,
                        'max' => 180,
                    ],
                ],
                'condition'  => [
                    'jltma_transform_fx_rotate_toggle' => 'yes',
                    'enabled_transform'               => 'yes',
                ],
            ]
        );

        $element->add_responsive_control(
            'jltma_transform_fx_rotate_y',
            [
                'label'      => __( 'Rotate Y', MELA_TD ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [
                    'px' => [
                        'min' => - 180,
                        'max' => 180,
                    ],
                ],
                'condition'  => [
                    'jltma_transform_fx_rotate_toggle' => 'yes',
                    'enabled_transform'               => 'yes',
                ],
            ]
        );

        $element->add_responsive_control(
            'jltma_transform_fx_rotate_z',
            [
                'label'      => __( 'Rotate Z', MELA_TD ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [
                    'px' => [
                        'min' => - 180,
                        'max' => 180,
                    ],
                ],
                'condition'  => [
                    'jltma_transform_fx_rotate_toggle' => 'yes',
                    'enabled_transform'               => 'yes',
                ],
                'selectors'  => [
                    '(desktop){{WRAPPER}}' =>
                        '-ms-transform:'
                        . 'translate({{jltma_transform_fx_translate_x.SIZE || 0}}px, {{jltma_transform_fx_translate_y.SIZE || 0}}px) '
                        . 'rotateX({{jltma_transform_fx_rotate_x.SIZE || 0}}deg) rotateY({{jltma_transform_fx_rotate_y.SIZE || 0}}deg) rotateZ({{jltma_transform_fx_rotate_z.SIZE || 0}}deg);'
                        . '-webkit-transform:'
                        . 'translate({{jltma_transform_fx_translate_x.SIZE || 0}}px, {{jltma_transform_fx_translate_y.SIZE || 0}}px) '
                        . 'rotateX({{jltma_transform_fx_rotate_x.SIZE || 0}}deg) rotateY({{jltma_transform_fx_rotate_y.SIZE || 0}}deg) rotateZ({{jltma_transform_fx_rotate_z.SIZE || 0}}deg);'
                        . 'transform:'
                        . 'translate({{jltma_transform_fx_translate_x.SIZE || 0}}px, {{jltma_transform_fx_translate_y.SIZE || 0}}px) '
                        . 'rotateX({{jltma_transform_fx_rotate_x.SIZE || 0}}deg) rotateY({{jltma_transform_fx_rotate_y.SIZE || 0}}deg) rotateZ({{jltma_transform_fx_rotate_z.SIZE || 0}}deg);',
                    '(tablet){{WRAPPER}}'  =>
                        '-ms-transform:'
                        . 'translate({{jltma_transform_fx_translate_x_tablet.SIZE || 0}}px, {{jltma_transform_fx_translate_y_tablet.SIZE || 0}}px) '
                        . 'rotateX({{jltma_transform_fx_rotate_x_tablet.SIZE || 0}}deg) rotateY({{jltma_transform_fx_rotate_y_tablet.SIZE || 0}}deg) rotateZ({{jltma_transform_fx_rotate_z_tablet.SIZE || 0}}deg);'
                        . '-webkit-transform:'
                        . 'translate({{jltma_transform_fx_translate_x_tablet.SIZE || 0}}px, {{jltma_transform_fx_translate_y_tablet.SIZE || 0}}px) '
                        . 'rotateX({{jltma_transform_fx_rotate_x_tablet.SIZE || 0}}deg) rotateY({{jltma_transform_fx_rotate_y_tablet.SIZE || 0}}deg) rotateZ({{jltma_transform_fx_rotate_z_tablet.SIZE || 0}}deg);'
                        . 'transform:'
                        . 'translate({{jltma_transform_fx_translate_x_tablet.SIZE || 0}}px, {{jltma_transform_fx_translate_y_tablet.SIZE || 0}}px) '
                        . 'rotateX({{jltma_transform_fx_rotate_x_tablet.SIZE || 0}}deg) rotateY({{jltma_transform_fx_rotate_y_tablet.SIZE || 0}}deg) rotateZ({{jltma_transform_fx_rotate_z_tablet.SIZE || 0}}deg);',
                    '(mobile){{WRAPPER}}'  =>
                        '-ms-transform:'
                        . 'translate({{jltma_transform_fx_translate_x_mobile.SIZE || 0}}px, {{jltma_transform_fx_translate_y_mobile.SIZE || 0}}px) '
                        . 'rotateX({{jltma_transform_fx_rotate_x_mobile.SIZE || 0}}deg) rotateY({{jltma_transform_fx_rotate_y_mobile.SIZE || 0}}deg) rotateZ({{jltma_transform_fx_rotate_z_mobile.SIZE || 0}}deg);'
                        . '-webkit-transform:'
                        . 'translate({{jltma_transform_fx_translate_x_mobile.SIZE || 0}}px, {{jltma_transform_fx_translate_y_mobile.SIZE || 0}}px) '
                        . 'rotateX({{jltma_transform_fx_rotate_x_mobile.SIZE || 0}}deg) rotateY({{jltma_transform_fx_rotate_y_mobile.SIZE || 0}}deg) rotateZ({{jltma_transform_fx_rotate_z_mobile.SIZE || 0}}deg);'
                        . 'transform:'
                        . 'translate({{jltma_transform_fx_translate_x_mobile.SIZE || 0}}px, {{jltma_transform_fx_translate_y_mobile.SIZE || 0}}px) '
                        . 'rotateX({{jltma_transform_fx_rotate_x_mobile.SIZE || 0}}deg) rotateY({{jltma_transform_fx_rotate_y_mobile.SIZE || 0}}deg) rotateZ({{jltma_transform_fx_rotate_z_mobile.SIZE || 0}}deg);',
                ],
            ]
        );

        $element->end_popover();

        $element->add_control(
            'jltma_transform_fx_scale_toggle',
            [
                'label'        => __( 'Scale', MELA_TD ),
                'type'         => Controls_Manager::POPOVER_TOGGLE,
                'return_value' => 'yes',
                'condition'    => [
                    'enabled_transform' => 'yes',
                ],
            ]
        );

        $element->start_popover();

        $element->add_responsive_control(
            'jltma_transform_fx_scale_x',
            [
                'label'      => __( 'Scale X', MELA_TD ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'default'    => [
                    'size' => 1,
                ],
                'range'      => [
                    'px' => [
                        'min'  => 0,
                        'max'  => 5,
                        'step' => .1,
                    ],
                ],
                'condition'  => [
                    'jltma_transform_fx_scale_toggle' => 'yes',
                    'enabled_transform'              => 'yes',
                ],
            ]
        );

        $element->add_responsive_control(
            'jltma_transform_fx_scale_y',
            [
                'label'      => __( 'Scale Y', MELA_TD ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'default'    => [
                    'size' => 1,
                ],
                'range'      => [
                    'px' => [
                        'min'  => 0,
                        'max'  => 5,
                        'step' => .1,
                    ],
                ],
                'condition'  => [
                    'jltma_transform_fx_scale_toggle' => 'yes',
                    'enabled_transform'              => 'yes',
                ],
                'selectors'  => [
                    '(desktop){{WRAPPER}}' =>
                        '-ms-transform:'
                        . 'translate({{jltma_transform_fx_translate_x.SIZE || 0}}px, {{jltma_transform_fx_translate_y.SIZE || 0}}px) '
                        . 'rotateX({{jltma_transform_fx_rotate_x.SIZE || 0}}deg) rotateY({{jltma_transform_fx_rotate_y.SIZE || 0}}deg) rotateZ({{jltma_transform_fx_rotate_z.SIZE || 0}}deg) '
                        . 'scaleX({{jltma_transform_fx_scale_x.SIZE || 1}}) scaleY({{jltma_transform_fx_scale_y.SIZE || 1}});'
                        . '-webkit-transform:'
                        . 'translate({{jltma_transform_fx_translate_x.SIZE || 0}}px, {{jltma_transform_fx_translate_y.SIZE || 0}}px) '
                        . 'rotateX({{jltma_transform_fx_rotate_x.SIZE || 0}}deg) rotateY({{jltma_transform_fx_rotate_y.SIZE || 0}}deg) rotateZ({{jltma_transform_fx_rotate_z.SIZE || 0}}deg) '
                        . 'scaleX({{jltma_transform_fx_scale_x.SIZE || 1}}) scaleY({{jltma_transform_fx_scale_y.SIZE || 1}});'
                        . 'transform:'
                        . 'translate({{jltma_transform_fx_translate_x.SIZE || 0}}px, {{jltma_transform_fx_translate_y.SIZE || 0}}px) '
                        . 'rotateX({{jltma_transform_fx_rotate_x.SIZE || 0}}deg) rotateY({{jltma_transform_fx_rotate_y.SIZE || 0}}deg) rotateZ({{jltma_transform_fx_rotate_z.SIZE || 0}}deg) '
                        . 'scaleX({{jltma_transform_fx_scale_x.SIZE || 1}}) scaleY({{jltma_transform_fx_scale_y.SIZE || 1}});',
                    '(tablet){{WRAPPER}}'  =>
                        '-ms-transform:'
                        . 'translate({{jltma_transform_fx_translate_x_tablet.SIZE || 0}}px, {{jltma_transform_fx_translate_y_tablet.SIZE || 0}}px) '
                        . 'rotateX({{jltma_transform_fx_rotate_x_tablet.SIZE || 0}}deg) rotateY({{jltma_transform_fx_rotate_y_tablet.SIZE || 0}}deg) rotateZ({{jltma_transform_fx_rotate_z_tablet.SIZE || 0}}deg) '
                        . 'scaleX({{jltma_transform_fx_scale_x_tablet.SIZE || 1}}) scaleY({{jltma_transform_fx_scale_y_tablet.SIZE || 1}});'
                        . '-webkit-transform:'
                        . 'translate({{jltma_transform_fx_translate_x_tablet.SIZE || 0}}px, {{jltma_transform_fx_translate_y_tablet.SIZE || 0}}px) '
                        . 'rotateX({{jltma_transform_fx_rotate_x_tablet.SIZE || 0}}deg) rotateY({{jltma_transform_fx_rotate_y_tablet.SIZE || 0}}deg) rotateZ({{jltma_transform_fx_rotate_z_tablet.SIZE || 0}}deg) '
                        . 'scaleX({{jltma_transform_fx_scale_x_tablet.SIZE || 1}}) scaleY({{jltma_transform_fx_scale_y_tablet.SIZE || 1}});'
                        . 'transform:'
                        . 'translate({{jltma_transform_fx_translate_x_tablet.SIZE || 0}}px, {{jltma_transform_fx_translate_y_tablet.SIZE || 0}}px) '
                        . 'rotateX({{jltma_transform_fx_rotate_x_tablet.SIZE || 0}}deg) rotateY({{jltma_transform_fx_rotate_y_tablet.SIZE || 0}}deg) rotateZ({{jltma_transform_fx_rotate_z_tablet.SIZE || 0}}deg) '
                        . 'scaleX({{jltma_transform_fx_scale_x_tablet.SIZE || 1}}) scaleY({{jltma_transform_fx_scale_y_tablet.SIZE || 1}});',
                    '(mobile){{WRAPPER}}'  =>
                        '-ms-transform:'
                        . 'translate({{jltma_transform_fx_translate_x_mobile.SIZE || 0}}px, {{jltma_transform_fx_translate_y_mobile.SIZE || 0}}px) '
                        . 'rotateX({{jltma_transform_fx_rotate_x_mobile.SIZE || 0}}deg) rotateY({{jltma_transform_fx_rotate_y_mobile.SIZE || 0}}deg) rotateZ({{jltma_transform_fx_rotate_z_mobile.SIZE || 0}}deg) '
                        . 'scaleX({{jltma_transform_fx_scale_x_mobile.SIZE || 1}}) scaleY({{jltma_transform_fx_scale_y_mobile.SIZE || 1}});'
                        . '-webkit-transform:'
                        . 'translate({{jltma_transform_fx_translate_x_mobile.SIZE || 0}}px, {{jltma_transform_fx_translate_y_mobile.SIZE || 0}}px) '
                        . 'rotateX({{jltma_transform_fx_rotate_x_mobile.SIZE || 0}}deg) rotateY({{jltma_transform_fx_rotate_y_mobile.SIZE || 0}}deg) rotateZ({{jltma_transform_fx_rotate_z_mobile.SIZE || 0}}deg) '
                        . 'scaleX({{jltma_transform_fx_scale_x_mobile.SIZE || 1}}) scaleY({{jltma_transform_fx_scale_y_mobile.SIZE || 1}});'
                        . 'transform:'
                        . 'translate({{jltma_transform_fx_translate_x_mobile.SIZE || 0}}px, {{jltma_transform_fx_translate_y_mobile.SIZE || 0}}px) '
                        . 'rotateX({{jltma_transform_fx_rotate_x_mobile.SIZE || 0}}deg) rotateY({{jltma_transform_fx_rotate_y_mobile.SIZE || 0}}deg) rotateZ({{jltma_transform_fx_rotate_z_mobile.SIZE || 0}}deg) '
                        . 'scaleX({{jltma_transform_fx_scale_x_mobile.SIZE || 1}}) scaleY({{jltma_transform_fx_scale_y_mobile.SIZE || 1}});',
                ],
            ]
        );

        $element->end_popover();

        $element->add_control(
            'jltma_transform_fx_skew_toggle',
            [
                'label'        => __( 'Skew', MELA_TD ),
                'type'         => Controls_Manager::POPOVER_TOGGLE,
                'return_value' => 'yes',
                'condition'    => [
                    'enabled_transform' => 'yes',
                ],
            ]
        );

        $element->start_popover();

        $element->add_responsive_control(
            'jltma_transform_fx_skew_x',
            [
                'label'      => __( 'Skew X', MELA_TD ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'deg' ],
                'range'      => [
                    'px' => [
                        'min' => - 180,
                        'max' => 180,
                    ],
                ],
                'condition'  => [
                    'jltma_transform_fx_skew_toggle' => 'yes',
                    'enabled_transform'             => 'yes',
                ],
            ]
        );

        $element->add_responsive_control(
            'jltma_transform_fx_skew_y',
            [
                'label'      => __( 'Skew Y', MELA_TD ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'deg' ],
                'range'      => [
                    'px' => [
                        'min' => - 180,
                        'max' => 180,
                    ],
                ],
                'condition'  => [
                    'jltma_transform_fx_skew_toggle' => 'yes',
                    'enabled_transform'             => 'yes',
                ],
                'selectors'  => [
                    '(desktop){{WRAPPER}}' =>
                        '-ms-transform:'
                        . 'translate({{jltma_transform_fx_translate_x.SIZE || 0}}px, {{jltma_transform_fx_translate_y.SIZE || 0}}px) '
                        . 'rotateX({{jltma_transform_fx_rotate_x.SIZE || 0}}deg) rotateY({{jltma_transform_fx_rotate_y.SIZE || 0}}deg) rotateZ({{jltma_transform_fx_rotate_z.SIZE || 0}}deg) '
                        . 'scaleX({{jltma_transform_fx_scale_x.SIZE || 1}}) scaleY({{jltma_transform_fx_scale_y.SIZE || 1}}) '
                        . 'skew({{jltma_transform_fx_skew_x.SIZE || 0}}deg, {{jltma_transform_fx_skew_y.SIZE || 0}}deg);'
                        . '-webkit-transform:'
                        . 'translate({{jltma_transform_fx_translate_x.SIZE || 0}}px, {{jltma_transform_fx_translate_y.SIZE || 0}}px) '
                        . 'rotateX({{jltma_transform_fx_rotate_x.SIZE || 0}}deg) rotateY({{jltma_transform_fx_rotate_y.SIZE || 0}}deg) rotateZ({{jltma_transform_fx_rotate_z.SIZE || 0}}deg) '
                        . 'scaleX({{jltma_transform_fx_scale_x.SIZE || 1}}) scaleY({{jltma_transform_fx_scale_y.SIZE || 1}}) '
                        . 'skew({{jltma_transform_fx_skew_x.SIZE || 0}}deg, {{jltma_transform_fx_skew_y.SIZE || 0}}deg);'
                        . 'transform:'
                        . 'translate({{jltma_transform_fx_translate_x.SIZE || 0}}px, {{jltma_transform_fx_translate_y.SIZE || 0}}px) '
                        . 'rotateX({{jltma_transform_fx_rotate_x.SIZE || 0}}deg) rotateY({{jltma_transform_fx_rotate_y.SIZE || 0}}deg) rotateZ({{jltma_transform_fx_rotate_z.SIZE || 0}}deg) '
                        . 'scaleX({{jltma_transform_fx_scale_x.SIZE || 1}}) scaleY({{jltma_transform_fx_scale_y.SIZE || 1}}) '
                        . 'skew({{jltma_transform_fx_skew_x.SIZE || 0}}deg, {{jltma_transform_fx_skew_y.SIZE || 0}}deg);',
                    '(tablet){{WRAPPER}}'  =>
                        '-ms-transform:'
                        . 'translate({{jltma_transform_fx_translate_x_tablet.SIZE || 0}}px, {{jltma_transform_fx_translate_y_tablet.SIZE || 0}}px) '
                        . 'rotateX({{jltma_transform_fx_rotate_x_tablet.SIZE || 0}}deg) rotateY({{jltma_transform_fx_rotate_y_tablet.SIZE || 0}}deg) rotateZ({{jltma_transform_fx_rotate_z_tablet.SIZE || 0}}deg) '
                        . 'scaleX({{jltma_transform_fx_scale_x_tablet.SIZE || 1}}) scaleY({{jltma_transform_fx_scale_y_tablet.SIZE || 1}}) '
                        . 'skew({{jltma_transform_fx_skew_x_tablet.SIZE || 0}}deg, {{jltma_transform_fx_skew_y_tablet.SIZE || 0}}deg);'
                        . '-webkit-transform:'
                        . 'translate({{jltma_transform_fx_translate_x_tablet.SIZE || 0}}px, {{jltma_transform_fx_translate_y_tablet.SIZE || 0}}px) '
                        . 'rotateX({{jltma_transform_fx_rotate_x_tablet.SIZE || 0}}deg) rotateY({{jltma_transform_fx_rotate_y_tablet.SIZE || 0}}deg) rotateZ({{jltma_transform_fx_rotate_z_tablet.SIZE || 0}}deg) '
                        . 'scaleX({{jltma_transform_fx_scale_x_tablet.SIZE || 1}}) scaleY({{jltma_transform_fx_scale_y_tablet.SIZE || 1}}) '
                        . 'skew({{jltma_transform_fx_skew_x_tablet.SIZE || 0}}deg, {{jltma_transform_fx_skew_y_tablet.SIZE || 0}}deg);'
                        . 'transform:'
                        . 'translate({{jltma_transform_fx_translate_x_tablet.SIZE || 0}}px, {{jltma_transform_fx_translate_y_tablet.SIZE || 0}}px) '
                        . 'rotateX({{jltma_transform_fx_rotate_x_tablet.SIZE || 0}}deg) rotateY({{jltma_transform_fx_rotate_y_tablet.SIZE || 0}}deg) rotateZ({{jltma_transform_fx_rotate_z_tablet.SIZE || 0}}deg) '
                        . 'scaleX({{jltma_transform_fx_scale_x_tablet.SIZE || 1}}) scaleY({{jltma_transform_fx_scale_y_tablet.SIZE || 1}}) '
                        . 'skew({{jltma_transform_fx_skew_x_tablet.SIZE || 0}}deg, {{jltma_transform_fx_skew_y_tablet.SIZE || 0}}deg);',
                    '(mobile){{WRAPPER}}'  =>
                        '-ms-transform:'
                        . 'translate({{jltma_transform_fx_translate_x_mobile.SIZE || 0}}px, {{jltma_transform_fx_translate_y_mobile.SIZE || 0}}px) '
                        . 'rotateX({{jltma_transform_fx_rotate_x_mobile.SIZE || 0}}deg) rotateY({{jltma_transform_fx_rotate_y_mobile.SIZE || 0}}deg) rotateZ({{jltma_transform_fx_rotate_z_mobile.SIZE || 0}}deg) '
                        . 'scaleX({{jltma_transform_fx_scale_x_mobile.SIZE || 1}}) scaleY({{jltma_transform_fx_scale_y_mobile.SIZE || 1}}) '
                        . 'skew({{jltma_transform_fx_skew_x_mobile.SIZE || 0}}deg, {{jltma_transform_fx_skew_y_mobile.SIZE || 0}}deg);'
                        . '-webkit-transform:'
                        . 'translate({{jltma_transform_fx_translate_x_mobile.SIZE || 0}}px, {{jltma_transform_fx_translate_y_mobile.SIZE || 0}}px) '
                        . 'rotateX({{jltma_transform_fx_rotate_x_mobile.SIZE || 0}}deg) rotateY({{jltma_transform_fx_rotate_y_mobile.SIZE || 0}}deg) rotateZ({{jltma_transform_fx_rotate_z_mobile.SIZE || 0}}deg) '
                        . 'scaleX({{jltma_transform_fx_scale_x_mobile.SIZE || 1}}) scaleY({{jltma_transform_fx_scale_y_mobile.SIZE || 1}}) '
                        . 'skew({{jltma_transform_fx_skew_x_mobile.SIZE || 0}}deg, {{jltma_transform_fx_skew_y_mobile.SIZE || 0}}deg);'
                        . 'transform:'
                        . 'translate({{jltma_transform_fx_translate_x_mobile.SIZE || 0}}px, {{jltma_transform_fx_translate_y_mobile.SIZE || 0}}px) '
                        . 'rotateX({{jltma_transform_fx_rotate_x_mobile.SIZE || 0}}deg) rotateY({{jltma_transform_fx_rotate_y_mobile.SIZE || 0}}deg) rotateZ({{jltma_transform_fx_rotate_z_mobile.SIZE || 0}}deg) '
                        . 'scaleX({{jltma_transform_fx_scale_x_mobile.SIZE || 1}}) scaleY({{jltma_transform_fx_scale_y_mobile.SIZE || 1}}) '
                        . 'skew({{jltma_transform_fx_skew_x_mobile.SIZE || 0}}deg, {{jltma_transform_fx_skew_y_mobile.SIZE || 0}}deg);',
                ],
            ]
        );

        $element->end_popover();

        $element->end_controls_tab();


        // Hover Transforms
        $element->start_controls_tab(
            'jltma_transform_fx_tab_hover',
            [
                'label' => __( 'Hover', MELA_TD ),
                'condition' => [
                    'enabled_transform' => 'yes',
                ],
            ]
        );

        $element->add_control(
            'jltma_transform_fx_translate_toggle_hover',
            [
                'label'        => __( 'Translate', MELA_TD ),
                'type'         => Controls_Manager::POPOVER_TOGGLE,
                'return_value' => 'yes',
                'condition'    => [
                    'enabled_transform' => 'yes',
                ],
            ]
        );

        $element->start_popover();

        $element->add_responsive_control(
            'jltma_transform_fx_translate_x_hover',
            [
                'label'      => __( 'Translate X', MELA_TD ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [
                    'px' => [
                        'min' => - 1000,
                        'max' => 1000,
                    ],
                ],
                'condition'  => [
                    'jltma_transform_fx_translate_toggle_hover' => 'yes',
                    'enabled_transform'                         => 'yes',
                ],
            ]
        );

        $element->add_responsive_control(
            'jltma_transform_fx_translate_y_hover',
            [
                'label'      => __( 'Translate Y', MELA_TD ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [
                    'px' => [
                        'min' => - 1000,
                        'max' => 1000,
                    ],
                ],
                'condition'  => [
                    'jltma_transform_fx_translate_toggle_hover' => 'yes',
                    'enabled_transform'                         => 'yes',
                ],
                'selectors'  => [
                    '(desktop){{WRAPPER}}:hover' =>
                        '-ms-transform:'
                        . 'translate({{jltma_transform_fx_translate_x_hover.SIZE || 0}}px, {{jltma_transform_fx_translate_y_hover.SIZE || 0}}px);'
                        . '-webkit-transform:'
                        . 'translate({{jltma_transform_fx_translate_x_hover.SIZE || 0}}px, {{jltma_transform_fx_translate_y_hover.SIZE || 0}}px);'
                        . 'transform:'
                        . 'translate({{jltma_transform_fx_translate_x_hover.SIZE || 0}}px, {{jltma_transform_fx_translate_y_hover.SIZE || 0}}px);',
                    '(tablet){{WRAPPER}}:hover'  =>
                        '-ms-transform:'
                        . 'translate({{jltma_transform_fx_translate_x_hover_tablet.SIZE || 0}}px, {{jltma_transform_fx_translate_y_hover_tablet.SIZE || 0}}px);'
                        . '-webkit-transform:'
                        . 'translate({{jltma_transform_fx_translate_x_hover_tablet.SIZE || 0}}px, {{jltma_transform_fx_translate_y_hover_tablet.SIZE || 0}}px);'
                        . 'transform:'
                        . 'translate({{jltma_transform_fx_translate_x_hover_tablet.SIZE || 0}}px, {{jltma_transform_fx_translate_y_hover_tablet.SIZE || 0}}px);',
                    '(mobile){{WRAPPER}}:hover'  =>
                        '-ms-transform:'
                        . 'translate({{jltma_transform_fx_translate_x_hover_mobile.SIZE || 0}}px, {{jltma_transform_fx_translate_y_hover_mobile.SIZE || 0}}px);'
                        . '-webkit-transform:'
                        . 'translate({{jltma_transform_fx_translate_x_hover_mobile.SIZE || 0}}px, {{jltma_transform_fx_translate_y_hover_mobile.SIZE || 0}}px);'
                        . 'transform:'
                        . 'translate({{jltma_transform_fx_translate_x_hover_mobile.SIZE || 0}}px, {{jltma_transform_fx_translate_y_hover_mobile.SIZE || 0}}px);',
                ],
            ]
        );

        $element->end_popover();


        $element->add_control(
            'jltma_transform_fx_rotate_toggle_hover',
            [
                'label'     => __( 'Rotate', MELA_TD ),
                'type'      => Controls_Manager::POPOVER_TOGGLE,
                'condition' => [
                    'enabled_transform' => 'yes',
                ],
            ]
        );

        $element->start_popover();

        $element->add_responsive_control(
            'jltma_transform_fx_rotate_x_hover',
            [
                'label'      => __( 'Rotate X', MELA_TD ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [
                    'px' => [
                        'min' => - 180,
                        'max' => 180,
                    ],
                ],
                'condition'  => [
                    'jltma_transform_fx_rotate_toggle_hover' => 'yes',
                    'enabled_transform'               => 'yes',
                ],
            ]
        );

        $element->add_responsive_control(
            'jltma_transform_fx_rotate_y_hover',
            [
                'label'      => __( 'Rotate Y', MELA_TD ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [
                    'px' => [
                        'min' => - 180,
                        'max' => 180,
                    ],
                ],
                'condition'  => [
                    'jltma_transform_fx_rotate_toggle_hover' => 'yes',
                    'enabled_transform'               => 'yes',
                ],
            ]
        );

        $element->add_responsive_control(
            'jltma_transform_fx_rotate_z_hover',
            [
                'label'      => __( 'Rotate Z', MELA_TD ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [
                    'px' => [
                        'min' => - 180,
                        'max' => 180,
                    ],
                ],
                'condition'  => [
                    'jltma_transform_fx_rotate_toggle_hover' => 'yes',
                    'enabled_transform'               => 'yes',
                ],
                'selectors'  => [
                    '(desktop){{WRAPPER}}:hover' =>
                        '-ms-transform:'
                        . 'translate({{jltma_transform_fx_translate_x_hover.SIZE || 0}}px, {{jltma_transform_fx_translate_y_hover.SIZE || 0}}px) '
                        . 'rotateX({{jltma_transform_fx_rotate_x_hover.SIZE || 0}}deg) rotateY({{jltma_transform_fx_rotate_y_hover.SIZE || 0}}deg) rotateZ({{jltma_transform_fx_rotate_z_hover.SIZE || 0}}deg);'
                        . '-webkit-transform:'
                        . 'translate({{jltma_transform_fx_translate_x_hover.SIZE || 0}}px, {{jltma_transform_fx_translate_y_hover.SIZE || 0}}px) '
                        . 'rotateX({{jltma_transform_fx_rotate_x_hover.SIZE || 0}}deg) rotateY({{jltma_transform_fx_rotate_y_hover.SIZE || 0}}deg) rotateZ({{jltma_transform_fx_rotate_z_hover.SIZE || 0}}deg);'
                        . 'transform:'
                        . 'translate({{jltma_transform_fx_translate_x_hover.SIZE || 0}}px, {{jltma_transform_fx_translate_y_hover.SIZE || 0}}px) '
                        . 'rotateX({{jltma_transform_fx_rotate_x_hover.SIZE || 0}}deg) rotateY({{jltma_transform_fx_rotate_y_hover.SIZE || 0}}deg) rotateZ({{jltma_transform_fx_rotate_z_hover.SIZE || 0}}deg);',
                    '(tablet){{WRAPPER}}:hover'  =>
                        '-ms-transform:'
                        . 'translate({{jltma_transform_fx_translate_x_hover_tablet.SIZE || 0}}px, {{jltma_transform_fx_translate_y_hover_tablet.SIZE || 0}}px) '
                        . 'rotateX({{jltma_transform_fx_rotate_x_hover_tablet.SIZE || 0}}deg) rotateY({{jltma_transform_fx_rotate_y_hover_tablet.SIZE || 0}}deg) rotateZ({{jltma_transform_fx_rotate_z_hover.tablet.SIZE || 0}}deg);'
                        . '-webkit-transform:'
                        . 'translate({{jltma_transform_fx_translate_x_hover_tablet.SIZE || 0}}px, {{jltma_transform_fx_translate_y_hover_tablet.SIZE || 0}}px) '
                        . 'rotateX({{jltma_transform_fx_rotate_x_hover_tablet.SIZE || 0}}deg) rotateY({{jltma_transform_fx_rotate_y_hover_tablet.SIZE || 0}}deg) rotateZ({{jltma_transform_fx_rotate_z_hover.tablet.SIZE || 0}}deg);'
                        . 'transform:'
                        . 'translate({{jltma_transform_fx_translate_x_hover_tablet.SIZE || 0}}px, {{jltma_transform_fx_translate_y_hover_tablet.SIZE || 0}}px) '
                        . 'rotateX({{jltma_transform_fx_rotate_x_hover_tablet.SIZE || 0}}deg) rotateY({{jltma_transform_fx_rotate_y_hover_tablet.SIZE || 0}}deg) rotateZ({{jltma_transform_fx_rotate_z_hover.tablet.SIZE || 0}}deg);',
                    '(mobile){{WRAPPER}}:hover'  =>
                        '-ms-transform:'
                        . 'translate({{jltma_transform_fx_translate_x_hover_mobile.SIZE || 0}}px, {{jltma_transform_fx_translate_y_hover_mobile.SIZE || 0}}px) '
                        . 'rotateX({{jltma_transform_fx_rotate_x_hover_mobile.SIZE || 0}}deg) rotateY({{jltma_transform_fx_rotate_y_hover_mobile.SIZE || 0}}deg) rotateZ({{jltma_transform_fx_rotate_z_hover.mobile.SIZE || 0}}deg);'
                        . '-webkit-transform:'
                        . 'translate({{jltma_transform_fx_translate_x_hover_mobile.SIZE || 0}}px, {{jltma_transform_fx_translate_y_hover_mobile.SIZE || 0}}px) '
                        . 'rotateX({{jltma_transform_fx_rotate_x_hover_mobile.SIZE || 0}}deg) rotateY({{jltma_transform_fx_rotate_y_hover_mobile.SIZE || 0}}deg) rotateZ({{jltma_transform_fx_rotate_z_hover.mobile.SIZE || 0}}deg);'
                        . 'transform:'
                        . 'translate({{jltma_transform_fx_translate_x_hover_mobile.SIZE || 0}}px, {{jltma_transform_fx_translate_y_hover_mobile.SIZE || 0}}px) '
                        . 'rotateX({{jltma_transform_fx_rotate_x_hover_mobile.SIZE || 0}}deg) rotateY({{jltma_transform_fx_rotate_y_hover_mobile.SIZE || 0}}deg) rotateZ({{jltma_transform_fx_rotate_z_hover.mobile.SIZE || 0}}deg);',
                ],
            ]
        );

        $element->end_popover();

        $element->add_control(
            'jltma_transform_fx_scale_toggle_hover',
            [
                'label'        => __( 'Scale', MELA_TD ),
                'type'         => Controls_Manager::POPOVER_TOGGLE,
                'return_value' => 'yes',
                'condition'    => [
                    'enabled_transform' => 'yes',
                ],
            ]
        );

        $element->start_popover();

        $element->add_responsive_control(
            'jltma_transform_fx_scale_x_hover',
            [
                'label'      => __( 'Scale X', MELA_TD ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'default'    => [
                    'size' => 1,
                ],
                'range'      => [
                    'px' => [
                        'min'  => 0,
                        'max'  => 5,
                        'step' => .1,
                    ],
                ],
                'condition'  => [
                    'jltma_transform_fx_scale_toggle_hover' => 'yes',
                    'enabled_transform'              => 'yes',
                ],
            ]
        );

        $element->add_responsive_control(
            'jltma_transform_fx_scale_y_hover',
            [
                'label'      => __( 'Scale Y', MELA_TD ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'default'    => [
                    'size' => 1,
                ],
                'range'      => [
                    'px' => [
                        'min'  => 0,
                        'max'  => 5,
                        'step' => .1,
                    ],
                ],
                'condition'  => [
                    'jltma_transform_fx_scale_toggle_hover' => 'yes',
                    'enabled_transform'                     => 'yes',
                ],
                'selectors'  => [
                    '(desktop){{WRAPPER}}:hover' =>
                        '-ms-transform:'
                        . 'translate({{jltma_transform_fx_translate_x_hover.SIZE || 0}}px, {{jltma_transform_fx_translate_y_hover.SIZE || 0}}px) '
                        . 'rotateX({{jltma_transform_fx_rotate_x_hover.SIZE || 0}}deg) rotateY({{jltma_transform_fx_rotate_y_hover.SIZE || 0}}deg) rotateZ({{jltma_transform_fx_rotate_z_hover.SIZE || 0}}deg) '
                        . 'scaleX({{jltma_transform_fx_scale_x_hover.SIZE || 1}}) scaleY({{jltma_transform_fx_scale_y_hover.SIZE || 1}});'
                        . '-webkit-transform:'
                        . 'translate({{jltma_transform_fx_translate_x_hover.SIZE || 0}}px, {{jltma_transform_fx_translate_y_hover.SIZE || 0}}px) '
                        . 'rotateX({{jltma_transform_fx_rotate_x_hover.SIZE || 0}}deg) rotateY({{jltma_transform_fx_rotate_y_hover.SIZE || 0}}deg) rotateZ({{jltma_transform_fx_rotate_z_hover.SIZE || 0}}deg) '
                        . 'scaleX({{jltma_transform_fx_scale_x_hover.SIZE || 1}}) scaleY({{jltma_transform_fx_scale_y_hover.SIZE || 1}});'
                        . 'transform:'
                        . 'translate({{jltma_transform_fx_translate_x_hover.SIZE || 0}}px, {{jltma_transform_fx_translate_y_hover.SIZE || 0}}px) '
                        . 'rotateX({{jltma_transform_fx_rotate_x_hover.SIZE || 0}}deg) rotateY({{jltma_transform_fx_rotate_y_hover.SIZE || 0}}deg) rotateZ({{jltma_transform_fx_rotate_z_hover.SIZE || 0}}deg) '
                        . 'scaleX({{jltma_transform_fx_scale_x_hover.SIZE || 1}}) scaleY({{jltma_transform_fx_scale_y_hover.SIZE || 1}});',
                    '(tablet){{WRAPPER}}:hover'  =>
                        '-ms-transform:'
                        . 'translate({{jltma_transform_fx_translate_x_hover_tablet.SIZE || 0}}px, {{jltma_transform_fx_translate_y_hover_tablet.SIZE || 0}}px) '
                        . 'rotateX({{jltma_transform_fx_rotate_x_hover_tablet.SIZE || 0}}deg) rotateY({{jltma_transform_fx_rotate_y_hover_tablet.SIZE || 0}}deg) rotateZ({{jltma_transform_fx_rotate_z_hover.tablet.SIZE || 0}}deg) '
                        . 'scaleX({{jltma_transform_fx_scale_x_hover_tablet.SIZE || 1}}) scaleY({{jltma_transform_fx_scale_y_hover_tablet.SIZE || 1}});'
                        . '-webkit-transform:'
                        . 'translate({{jltma_transform_fx_translate_x_hover_tablet.SIZE || 0}}px, {{jltma_transform_fx_translate_y_hover_tablet.SIZE || 0}}px) '
                        . 'rotateX({{jltma_transform_fx_rotate_x_hover_tablet.SIZE || 0}}deg) rotateY({{jltma_transform_fx_rotate_y_hover_tablet.SIZE || 0}}deg) rotateZ({{jltma_transform_fx_rotate_z_hover.tablet.SIZE || 0}}deg) '
                        . 'scaleX({{jltma_transform_fx_scale_x_hover_tablet.SIZE || 1}}) scaleY({{jltma_transform_fx_scale_y_hover_tablet.SIZE || 1}});'
                        . 'transform:'
                        . 'translate({{jltma_transform_fx_translate_x_hover_tablet.SIZE || 0}}px, {{jltma_transform_fx_translate_y_hover_tablet.SIZE || 0}}px) '
                        . 'rotateX({{jltma_transform_fx_rotate_x_hover_tablet.SIZE || 0}}deg) rotateY({{jltma_transform_fx_rotate_y_hover_tablet.SIZE || 0}}deg) rotateZ({{jltma_transform_fx_rotate_z_hover.tablet.SIZE || 0}}deg) '
                        . 'scaleX({{jltma_transform_fx_scale_x_hover_tablet.SIZE || 1}}) scaleY({{jltma_transform_fx_scale_y_hover_tablet.SIZE || 1}});',
                    '(mobile){{WRAPPER}}:hover'  =>
                        '-ms-transform:'
                        . 'translate({{jltma_transform_fx_translate_x_hover_mobile.SIZE || 0}}px, {{jltma_transform_fx_translate_y_hover_mobile.SIZE || 0}}px) '
                        . 'rotateX({{jltma_transform_fx_rotate_x_hover_mobile.SIZE || 0}}deg) rotateY({{jltma_transform_fx_rotate_y_hover_mobile.SIZE || 0}}deg) rotateZ({{jltma_transform_fx_rotate_z_hover.mobile.SIZE || 0}}deg) '
                        . 'scaleX({{jltma_transform_fx_scale_x_hover_mobile.SIZE || 1}}) scaleY({{jltma_transform_fx_scale_y_hover_mobile.SIZE || 1}});'
                        . '-webkit-transform:'
                        . 'translate({{jltma_transform_fx_translate_x_hover_mobile.SIZE || 0}}px, {{jltma_transform_fx_translate_y_hover_mobile.SIZE || 0}}px) '
                        . 'rotateX({{jltma_transform_fx_rotate_x_hover_mobile.SIZE || 0}}deg) rotateY({{jltma_transform_fx_rotate_y_hover_mobile.SIZE || 0}}deg) rotateZ({{jltma_transform_fx_rotate_z_hover.mobile.SIZE || 0}}deg) '
                        . 'scaleX({{jltma_transform_fx_scale_x_hover_mobile.SIZE || 1}}) scaleY({{jltma_transform_fx_scale_y_hover_mobile.SIZE || 1}});'
                        . 'transform:'
                        . 'translate({{jltma_transform_fx_translate_x_hover_mobile.SIZE || 0}}px, {{jltma_transform_fx_translate_y_hover_mobile.SIZE || 0}}px) '
                        . 'rotateX({{jltma_transform_fx_rotate_x_hover_mobile.SIZE || 0}}deg) rotateY({{jltma_transform_fx_rotate_y_hover_mobile.SIZE || 0}}deg) rotateZ({{jltma_transform_fx_rotate_z_hover.mobile.SIZE || 0}}deg) '
                        . 'scaleX({{jltma_transform_fx_scale_x_hover_mobile.SIZE || 1}}) scaleY({{jltma_transform_fx_scale_y_hover_mobile.SIZE || 1}});',
                ],
            ]
        );

        $element->end_popover();

        $element->add_control(
            'jltma_transform_fx_skew_toggle_hover',
            [
                'label'        => __( 'Skew', MELA_TD ),
                'type'         => Controls_Manager::POPOVER_TOGGLE,
                'return_value' => 'yes',
                'condition'    => [
                    'enabled_transform' => 'yes',
                ],
            ]
        );

        $element->start_popover();

        $element->add_responsive_control(
            'jltma_transform_fx_skew_x_hover',
            [
                'label'      => __( 'Skew X', MELA_TD ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'deg' ],
                'range'      => [
                    'px' => [
                        'min' => - 180,
                        'max' => 180,
                    ],
                ],
                'condition'  => [
                    'jltma_transform_fx_skew_toggle_hover'  => 'yes',
                    'enabled_transform'                     => 'yes',
                ],
            ]
        );

        $element->add_responsive_control(
            'jltma_transform_fx_skew_y_hover',
            [
                'label'      => __( 'Skew Y', MELA_TD ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'deg' ],
                'range'      => [
                    'px' => [
                        'min' => - 180,
                        'max' => 180,
                    ],
                ],
                'condition'  => [
                    'jltma_transform_fx_skew_toggle_hover' => 'yes',
                    'enabled_transform'             => 'yes',
                ],
                'selectors'  => [
                    '(desktop){{WRAPPER}}:hover' =>
                        '-ms-transform:'
                        . 'translate({{jltma_transform_fx_translate_x_hover.SIZE || 0}}px, {{jltma_transform_fx_translate_y_hover.SIZE || 0}}px) '
                        . 'rotateX({{jltma_transform_fx_rotate_x_hover.SIZE || 0}}deg) rotateY({{jltma_transform_fx_rotate_y_hover.SIZE || 0}}deg) rotateZ({{jltma_transform_fx_rotate_z_hover.SIZE || 0}}deg) '
                        . 'scaleX({{jltma_transform_fx_scale_x_hover.SIZE || 1}}) scaleY({{jltma_transform_fx_scale_y_hover.SIZE || 1}}) '
                        . 'skew({{jltma_transform_fx_skew_x_hover.SIZE || 0}}deg, {{jltma_transform_fx_skew_y_hover.SIZE || 0}}deg);'
                        . '-webkit-transform:'
                        . 'translate({{jltma_transform_fx_translate_x_hover.SIZE || 0}}px, {{jltma_transform_fx_translate_y_hover.SIZE || 0}}px) '
                        . 'rotateX({{jltma_transform_fx_rotate_x_hover.SIZE || 0}}deg) rotateY({{jltma_transform_fx_rotate_y_hover.SIZE || 0}}deg) rotateZ({{jltma_transform_fx_rotate_z_hover.SIZE || 0}}deg) '
                        . 'scaleX({{jltma_transform_fx_scale_x_hover.SIZE || 1}}) scaleY({{jltma_transform_fx_scale_y_hover.SIZE || 1}}) '
                        . 'skew({{jltma_transform_fx_skew_x_hover.SIZE || 0}}deg, {{jltma_transform_fx_skew_y_hover.SIZE || 0}}deg);'
                        . 'transform:'
                        . 'translate({{jltma_transform_fx_translate_x_hover.SIZE || 0}}px, {{jltma_transform_fx_translate_y_hover.SIZE || 0}}px) '
                        . 'rotateX({{jltma_transform_fx_rotate_x_hover.SIZE || 0}}deg) rotateY({{jltma_transform_fx_rotate_y_hover.SIZE || 0}}deg) rotateZ({{jltma_transform_fx_rotate_z_hover.SIZE || 0}}deg) '
                        . 'scaleX({{jltma_transform_fx_scale_x_hover.SIZE || 1}}) scaleY({{jltma_transform_fx_scale_y_hover.SIZE || 1}}) '
                        . 'skew({{jltma_transform_fx_skew_x_hover.SIZE || 0}}deg, {{jltma_transform_fx_skew_y_hover.SIZE || 0}}deg);',
                    '(tablet){{WRAPPER}}:hover'  =>
                        '-ms-transform:'
                        . 'translate({{jltma_transform_fx_translate_x_hover_tablet.SIZE || 0}}px, {{jltma_transform_fx_translate_y_hover_tablet.SIZE || 0}}px) '
                        . 'rotateX({{jltma_transform_fx_rotate_x_hover_tablet.SIZE || 0}}deg) rotateY({{jltma_transform_fx_rotate_y_hover_tablet.SIZE || 0}}deg) rotateZ({{jltma_transform_fx_rotate_z_hover.tablet.SIZE || 0}}deg) '
                        . 'scaleX({{jltma_transform_fx_scale_x_hover_tablet.SIZE || 1}}) scaleY({{jltma_transform_fx_scale_y_hover_tablet.SIZE || 1}}) '
                        . 'skew({{jltma_transform_fx_skew_x_hover_tablet.SIZE || 0}}deg, {{jltma_transform_fx_skew_y_hover_tablet.SIZE || 0}}deg);'
                        . '-webkit-transform:'
                        . 'translate({{jltma_transform_fx_translate_x_hover_tablet.SIZE || 0}}px, {{jltma_transform_fx_translate_y_hover_tablet.SIZE || 0}}px) '
                        . 'rotateX({{jltma_transform_fx_rotate_x_hover_tablet.SIZE || 0}}deg) rotateY({{jltma_transform_fx_rotate_y_hover_tablet.SIZE || 0}}deg) rotateZ({{jltma_transform_fx_rotate_z_hover.tablet.SIZE || 0}}deg) '
                        . 'scaleX({{jltma_transform_fx_scale_x_hover_tablet.SIZE || 1}}) scaleY({{jltma_transform_fx_scale_y_hover_tablet.SIZE || 1}}) '
                        . 'skew({{jltma_transform_fx_skew_x_hover_tablet.SIZE || 0}}deg, {{jltma_transform_fx_skew_y_hover_tablet.SIZE || 0}}deg);'
                        . 'transform:'
                        . 'translate({{jltma_transform_fx_translate_x_hover_tablet.SIZE || 0}}px, {{jltma_transform_fx_translate_y_hover_tablet.SIZE || 0}}px) '
                        . 'rotateX({{jltma_transform_fx_rotate_x_hover_tablet.SIZE || 0}}deg) rotateY({{jltma_transform_fx_rotate_y_hover_tablet.SIZE || 0}}deg) rotateZ({{jltma_transform_fx_rotate_z_hover.tablet.SIZE || 0}}deg) '
                        . 'scaleX({{jltma_transform_fx_scale_x_hover_tablet.SIZE || 1}}) scaleY({{jltma_transform_fx_scale_y_hover_tablet.SIZE || 1}}) '
                        . 'skew({{jltma_transform_fx_skew_x_hover_tablet.SIZE || 0}}deg, {{jltma_transform_fx_skew_y_hover_tablet.SIZE || 0}}deg);',
                    '(mobile){{WRAPPER}}:hover'  =>
                        '-ms-transform:'
                        . 'translate({{jltma_transform_fx_translate_x_hover_mobile.SIZE || 0}}px, {{jltma_transform_fx_translate_y_hover_mobile.SIZE || 0}}px) '
                        . 'rotateX({{jltma_transform_fx_rotate_x_hover_mobile.SIZE || 0}}deg) rotateY({{jltma_transform_fx_rotate_y_hover_mobile.SIZE || 0}}deg) rotateZ({{jltma_transform_fx_rotate_z_hover.mobile.SIZE || 0}}deg) '
                        . 'scaleX({{jltma_transform_fx_scale_x_hover_mobile.SIZE || 1}}) scaleY({{jltma_transform_fx_scale_y_hover_mobile.SIZE || 1}}) '
                        . 'skew({{jltma_transform_fx_skew_x_hover_mobile.SIZE || 0}}deg, {{jltma_transform_fx_skew_y_hover_mobile.SIZE || 0}}deg);'
                        . '-webkit-transform:'
                        . 'translate({{jltma_transform_fx_translate_x_hover_mobile.SIZE || 0}}px, {{jltma_transform_fx_translate_y_hover_mobile.SIZE || 0}}px) '
                        . 'rotateX({{jltma_transform_fx_rotate_x_hover_mobile.SIZE || 0}}deg) rotateY({{jltma_transform_fx_rotate_y_hover_mobile.SIZE || 0}}deg) rotateZ({{jltma_transform_fx_rotate_z_hover.mobile.SIZE || 0}}deg) '
                        . 'scaleX({{jltma_transform_fx_scale_x_hover_mobile.SIZE || 1}}) scaleY({{jltma_transform_fx_scale_y_hover_mobile.SIZE || 1}}) '
                        . 'skew({{jltma_transform_fx_skew_x_hover_mobile.SIZE || 0}}deg, {{jltma_transform_fx_skew_y_hover_mobile.SIZE || 0}}deg);'
                        . 'transform:'
                        . 'translate({{jltma_transform_fx_translate_x_hover_mobile.SIZE || 0}}px, {{jltma_transform_fx_translate_y_hover_mobile.SIZE || 0}}px) '
                        . 'rotateX({{jltma_transform_fx_rotate_x_hover_mobile.SIZE || 0}}deg) rotateY({{jltma_transform_fx_rotate_y_hover_mobile.SIZE || 0}}deg) rotateZ({{jltma_transform_fx_rotate_z_hover.mobile.SIZE || 0}}deg) '
                        . 'scaleX({{jltma_transform_fx_scale_x_hover_mobile.SIZE || 1}}) scaleY({{jltma_transform_fx_scale_y_hover_mobile.SIZE || 1}}) '
                        . 'skew({{jltma_transform_fx_skew_x_hover_mobile.SIZE || 0}}deg, {{jltma_transform_fx_skew_y_hover_mobile.SIZE || 0}}deg);',
                ],
            ]
        );

        $element->end_popover();



        // $element->add_control(
        //     'jltma_transform_fx_transition_duration',
        //     [
        //         'label' => __( 'Transition Duration', MELA_TD ),
        //         'type' => Controls_Manager::SLIDER,
        //         'separator' => 'before',
        //         'size_units' => ['px'],
        //         'range' => [
        //             'px' => [
        //                 'min' => 0,
        //                 'max' => 3,
        //                 'step' => .1,
        //             ]
        //         ],
        //         'condition' => [
        //             'enabled_transform' => 'yes',
        //         ],
        //         'selectors' => [
        //             '{{WRAPPER}}'       => 
        //             '-webkit-transition-duration: {{jltma_transform_fx_transition_duration.SIZE}}s, .2s;
        //                     transition-duration: {{jltma_transform_fx_transition_duration.SIZE}}s, .2s; 
        //             -webkit-transition-property: -webkit-transform;
        //                     transition-property: -webkit-transform;
        //                     transition-property:         transform;
        //                     transition-property:         transform, -webkit-transform;
        //             -webkit-transform: 
        //                 translate( {{jltma_transform_fx_translate_x.SIZE || 0 }}, {{jltma_transform_fx_translate_y.SIZE || 0}}) 
        //                 scale( {{jltma_transform_fx_scale_x.SIZE || 1 }}, {{jltma_transform_fx_scale_y.SIZE || 1 }} ) 
        //                 skew( {{jltma_transform_fx_skew_x.SIZE || 0 }}, {{jltma_transform_fx_skew_y.SIZE || 0 }}) 
        //                 rotateX( {{jltma_transform_fx_rotate_x.SIZE || 0 }} ) 
        //                 rotateY( {{jltma_transform_fx_rotate_y.SIZE || 0 }} ) 
        //                 rotateZ( {{jltma_transform_fx_rotate_z.SIZE || 0 }} );

        //                 transform: 
        //                     translate( {{jltma_transform_fx_translate_x.SIZE || 0 }}, {{jltma_transform_fx_translate_y.SIZE || 0 }} ) 
        //                     scale( {{jltma_transform_fx_scale_x.SIZE || 1 }}, {{jltma_transform_fx_scale_y.SIZE || 1 }} ) 
        //                     skew( {{jltma_transform_fx_skew_x.SIZE || 1 }}, {{jltma_transform_fx_skew_y.SIZE || 1 }} ) 
        //                     rotateX( {{jltma_transform_fx_rotate_x.SIZE || 1 }} ) 
        //                     rotateY( {{jltma_transform_fx_rotate_y.SIZE || 0 }} ) 
        //                     rotateZ( {{jltma_transform_fx_rotate_z.SIZE || 0 }} );',

        //             // '{{WRAPPER}}:hover' => ''
        //         ],
        //     ]
        // );


        $element->end_controls_tab();
        $element->end_controls_tabs();

    }


    protected function add_actions() {
        add_action('elementor/element/common/jltma_section_transforms_advanced/before_section_end', function( $element, $args ) {
            $this->add_controls($element, $args);
        }, 10, 2);
    }


    public static function get_instance() {
        if ( ! self::$instance ) {
            self::$instance = new self;
        }
        return self::$instance;
    }

}

JLTMA_Extension_Transforms::get_instance();