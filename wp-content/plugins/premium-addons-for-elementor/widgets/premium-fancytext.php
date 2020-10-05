<?php

/**
 * Premium Fancy Text.
 */
namespace PremiumAddons\Widgets;

// Elementor Classes.
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Scheme_Color;
use Elementor\Scheme_Typography;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Text_Shadow;

// PremiumAddons Classes.
use PremiumAddons\Helper_Functions;

if ( ! defined( 'ABSPATH' ) ) exit; // If this file is called directly, abort.

/**
 * Class Premium_Fancytext
 */
class Premium_Fancytext extends Widget_Base {
    
    public function get_name() {
        return 'premium-addon-fancy-text';
    }

    public function get_title() {
        return sprintf( '%1$s %2$s', Helper_Functions::get_prefix(), __('Fancy Text', 'premium-addons-for-elementor') );
	}

    public function get_icon() {
        return 'pa-fancy-text';
    }
    
    public function get_style_depends() {
        return [
            'premium-addons'
        ];
    }
    
    public function get_script_depends() {
        return [
            'typed-js',
            'vticker-js',
            'premium-addons-js'
        ];
    }

    public function get_categories() {
        return [ 'premium-elements' ];
    }
    
    public function get_custom_help_url() {
		return 'https://premiumaddons.com/support/';
	}

    /**
	 * Register Testimonials controls.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
    protected function _register_controls() {

        $this->start_controls_section('premium_fancy_text_content',
            [
                'label'         => __('Fancy Text', 'premium-addons-for-elementor'),
            ]
        );
        
        /*Prefix Text*/ 
        $this->add_control('premium_fancy_prefix_text',
                [
                    'label'         => __('Prefix', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::TEXT,
                    'dynamic'       => [ 'active' => true ],
                    'default'       => __('This is', 'premium-addons-for-elementor'),
                    'description'   => __( 'Text before Fancy text', 'premium-addons-for-elementor' ),
                    'label_block'   => true,
                ]
                );
        
        $repeater = new REPEATER();
        
        $repeater->add_control('premium_text_strings_text_field',
            [
                'label'       => __( 'Fancy String', 'premium-addons-for-elementor' ),
                'dynamic'     => [ 'active' => true ],
                'type'        => Controls_Manager::TEXT,
                'label_block' => true,
            ]
        );
        
        /*Fancy Text Strings*/
        $this->add_control('premium_fancy_text_strings',
            [
                'label'         => __( 'Fancy Text', 'premium-addons-for-elementor' ),
                'type'          => Controls_Manager::REPEATER,
                'default'       => [
                    [
                        'premium_text_strings_text_field' => __( 'Designer', 'premium-addons-for-elementor' ),
                        ],
                    [
                        'premium_text_strings_text_field' => __( 'Developer', 'premium-addons-for-elementor' ),
                        ],
                    [
                        'premium_text_strings_text_field' => __( 'Awesome', 'premium-addons-for-elementor' ),
                        ],
                    ],
                'fields'        => $repeater->get_controls(),
                'title_field'   => '{{{ premium_text_strings_text_field }}}',
            ]
        );

		/*Prefix Text*/ 
        $this->add_control('premium_fancy_suffix_text',
                [
                    'label'         => __('Suffix', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::TEXT,
                    'dynamic'       => [ 'active' => true ],
                    'default'       => __('Text', 'premium-addons-for-elementor'),
                    'description'   => __( 'Text after Fancy text', 'premium-addons-for-elementor' ),
                    'label_block'   => true,
                ]
                );
        
        /*Front Text Align*/
        $this->add_responsive_control('premium_fancy_text_align',
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
                        '{{WRAPPER}} .premium-fancy-text-wrapper' => 'text-align: {{VALUE}};',
                        ],
                    ]
                );
        
        $this->end_controls_section();
        
        $this->start_controls_section('premium_fancy_additional_settings',
            [
                'label'         => __('Additional Settings', 'premium-addons-for-elementor'),
            ]
        );
        
        $this->add_control('premium_fancy_text_effect', 
            [
                'label'         => __('Effect', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SELECT,
                'options'       => [
                    'typing' => __('Typing', 'premium-addons-for-elementor'),
                    'slide'  => __('Slide Up', 'premium-addons-for-elementor'),
                    'zoomout'=> __('Zoom Out', 'premium-addons-for-elementor'),
                    'rotate' => __('Rotate', 'premium-addons-for-elementor'),
                    'custom' => __('Custom', 'premium-addons-for-elementor'),
                ],
                'default'       => 'typing',
                'render_type'   => 'template',
                'label_block'   => true,
            ]
        );

        $this->add_control('custom_animation', 
            [
                'label'         => __('Animations', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::ANIMATION,
                'render_type'   => 'template',
                'default'       => 'fadeIn',
                'condition'     => [
                    'premium_fancy_text_effect' => 'custom'
                ]
            ]
		);

        $this->add_control('premium_fancy_text_type_speed',
            [
                'label'         => __('Type Speed', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::NUMBER,
                'default'       => 30,
                'description'   => __( 'Set typing effect speed in milliseconds.', 'premium-addons-for-elementor' ),
                'condition'     => [
                    'premium_fancy_text_effect' => 'typing',
                ],
            ]
        );
        
        $this->add_control('premium_fancy_text_zoom_speed',
            [
                'label'         => __('Animation Speed', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::NUMBER,
                'description'   => __('Set animation speed in milliseconds. Default value is 1000', 'premium-addons-for-elementor'),
                'condition'     => [
                    'premium_fancy_text_effect!' => [ 'typing', 'slide' ],
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-fancy-text-wrapper:not(.typing):not(.slide) .premium-fancy-list-items'   => 'animation-duration: {{VALUE}}ms'
                ]
            ]
        );
        
        $this->add_control('premium_fancy_text_zoom_delay',
            [
                'label'         => __('Animation Delay', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::NUMBER,
                'description'   => __('Set animation delay in milliseconds.Default value is 2500', 'premium-addons-for-elementor'),
                'condition'     => [
                    'premium_fancy_text_effect!' => [ 'typing', 'slide' ],
                ]
            ]
        );

        $this->add_control('loop_count',
            [
                'label'         => __('Loop Count', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::NUMBER,
                'condition'     => [
                    'premium_fancy_text_effect!' => [ 'typing', 'slide' ],
                ],
            ]
        );
        
        $this->add_control('premium_fancy_text_back_speed',
            [
                'label'         => __('Back Speed', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::NUMBER,
                'default'       => 30,
                'description'   => __( 'Set a speed for backspace effect in milliseconds.', 'premium-addons-for-elementor' ),
                'condition'     => [
                    'premium_fancy_text_effect' => 'typing',
                ],
            ]
        );
        
        $this->add_control('premium_fancy_text_start_delay',
            [
                'label'         => __('Start Delay', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::NUMBER,
                'default'       => 30,
                'description'   => __( 'If you set it on 5000 milliseconds, the first word/string will appear after 5 seconds.', 'premium-addons-for-elementor' ),
                'condition'     => [
                    'premium_fancy_text_effect' => 'typing',
                ],
            ]
        );
        
        /*Back Delay*/
        $this->add_control('premium_fancy_text_back_delay',
                [
                    'label'         => __('Back Delay', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::NUMBER,
                    'default'       => 30,
                    'description'   => __( 'If you set it on 5000 milliseconds, the word/string will remain visible for 5 seconds before backspace effect.', 'premium-addons-for-elementor' ),
                    'condition'     => [
                        'premium_fancy_text_effect' => 'typing',
                        ],
                ]
                );
        
        /*Type Loop*/
        $this->add_control('premium_fancy_text_type_loop',
                [
                    'label'         => __('Loop','premium-addons-for-elementor'),
                    'type'          => Controls_Manager::SWITCHER,
                    'default'       => 'yes',
                    'condition'     => [
                        'premium_fancy_text_effect' => 'typing',
                        ],
                    ]
                );
        
        /*Show Cursor*/
        $this->add_control('premium_fancy_text_show_cursor',
                [
                    'label'         => __('Show Cursor','premium-addons-for-elementor'),
                    'type'          => Controls_Manager::SWITCHER,
                    'default'       => 'yes',
                    'condition'     => [
                        'premium_fancy_text_effect' => 'typing',
                        ],
                    ]
                );
        
        /*Cursor Text*/
        $this->add_control('premium_fancy_text_cursor_text',
                [
                    'label'         => __('Cursor Mark', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::TEXT,
                    'dynamic'       => [ 'active' => true ],
                    'default'       => '|',
                    'condition'     => [
                        'premium_fancy_text_effect'     => 'typing',
                        'premium_fancy_text_show_cursor'=> 'yes',
                        ],
                    ]
                );

        $this->add_control('premium_slide_up_speed',
            [
                'label'         => __('Animation Speed', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::NUMBER,
                'default'       => 200,
                'description'   => __( 'Set a duration value in milliseconds for slide up effect.', 'premium-addons-for-elementor' ),
                'condition'     => [
                    'premium_fancy_text_effect' => 'slide',
                ],
            ]
        );
        
        /*Slide Up Pause Time*/
        $this->add_control('premium_slide_up_pause_time',
                [
                    'label'         => __('Pause Time (Milliseconds)', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::NUMBER,
                    'default'       => 3000,
                    'description'   => __( 'How long should the word/string stay visible? Set a value in milliseconds.', 'premium-addons-for-elementor' ),
                    'condition'     => [
                        'premium_fancy_text_effect' => 'slide',
                        ],
                ]
                );
        
        /*Slide Up Shown Items*/
        $this->add_control('premium_slide_up_shown_items',
                [
                    'label'         => __('Show Items', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::NUMBER,
                    'default'       => 1,
                    'description'   => __( 'How many items should be visible at a time?', 'premium-addons-for-elementor' ),
                    'condition'     => [
                        'premium_fancy_text_effect' => 'slide',
                        ],
                ]
                );
        
        /*Pause on Hover*/
        $this->add_control('premium_slide_up_hover_pause',
            [
                'label'         => __('Pause on Hover','premium-addons-for-elementor'),
                'type'          => Controls_Manager::SWITCHER,
                'description'   => __( 'If you enabled this option, the slide will be paused when mouseover.', 'premium-addons-for-elementor' ),
                'condition'     => [
                    'premium_fancy_text_effect' => 'slide',
                ],
            ]
        );
        
        $this->add_responsive_control('premium_fancy_slide_align',
            [
                'label'         => __( 'Fancy Text Alignment', 'premium-addons-for-elementor' ),
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
                'toggle'        => false,
                'selectors'     => [
                    '{{WRAPPER}} .premium-fancy-list-items' => 'text-align: {{VALUE}};',
                ],
                'condition'     => [
                    'premium_fancy_text_effect' => 'slide',
                ],
            ]
        );
       
        $this->end_controls_section();
        
        /*Start Fancy Text Settings Tab*/
        $this->start_controls_section('premium_fancy_text_style_tab',
                [
                    'label'         => __('Fancy Text', 'premium-addons-for-elementor'),
                    'tab'           => Controls_Manager::TAB_STYLE,
                ]
                );
        
        /*Fancy Text Color*/
        $this->add_control('premium_fancy_text_color',
                [
                    'label'         => __('Color', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::COLOR,
                    'scheme'        => [
                        'type'  => Scheme_Color::get_type(),
                        'value' => Scheme_Color::COLOR_1,
                    ],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-fancy-text' => 'color: {{VALUE}};',
                    ]
                ]
                );
        
         /*Fancy Text Typography*/
        $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name'          => 'fancy_text_typography',
                    'scheme'        => Scheme_Typography::TYPOGRAPHY_1,
                    'selector'      => '{{WRAPPER}} .premium-fancy-text',
                    ]
                );  
        
        /*Fancy Text Background Color*/
        $this->add_control('premium_fancy_text_background_color',
                [
                    'label'         => __('Background Color', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::COLOR,
                    'selectors'     => [
                        '{{WRAPPER}} .premium-fancy-text' => 'background-color: {{VALUE}};',
                    ]
                ]
                );
        
        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name'          => 'text_shadow',
                'selector'      => '{{WRAPPER}} .premium-fancy-text',
            ]
        );
      
        /*End Fancy Text Settings Tab*/
        $this->end_controls_section();

        /*Start Cursor Settings Tab*/
        $this->start_controls_section('premium_fancy_cursor_text_style_tab',
                [
                    'label'         => __('Cursor Text', 'premium-addons-for-elementor'),
                    'tab'           => Controls_Manager::TAB_STYLE,
                    'condition'     => [
                        'premium_fancy_text_cursor_text!'   => ''
                ]
            ]
        );
        
        /*Cursor Color*/
        $this->add_control('premium_fancy_text_cursor_color',
                [
                    'label'         => __('Color', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::COLOR,
                    'scheme'        => [
                        'type'  => Scheme_Color::get_type(),
                        'value' => Scheme_Color::COLOR_1,
                    ],
                    'selectors'     => [
                        '{{WRAPPER}} .typed-cursor' => 'color: {{VALUE}};',
                    ]
                ]
                );
        
         /*Cursor Typography*/
        $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name'          => 'fancy_text_cursor_typography',
                    'scheme'        => Scheme_Typography::TYPOGRAPHY_1,
                    'selector'      => '{{WRAPPER}} .typed-cursor',
                    ]
                );  
        
        /*Cursor Background Color*/
        $this->add_control('premium_fancy_text_cursor_background',
                [
                    'label'         => __('Background Color', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::COLOR,
                    'selectors'     => [
                        '{{WRAPPER}} .typed-cursor' => 'background-color: {{VALUE}};',
                    ]
                ]
                );
      
        /*End Fancy Text Settings Tab*/
        $this->end_controls_section();
        
        /*Start Prefix Suffix Text Settings Tab*/
        $this->start_controls_section('premium_prefix_suffix_style_tab',
                [
                    'label'         => __('Prefix & Suffix', 'premium-addons-for-elementor'),
                    'tab'           => Controls_Manager::TAB_STYLE,
                ]
                );
        
        /*Prefix Suffix Text Color*/
        $this->add_control('premium_prefix_suffix_text_color',
                [
                    'label'         => __('Color', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::COLOR,
                    'scheme'        => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_2,
                    ],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-prefix-text, {{WRAPPER}} .premium-suffix-text' => 'color: {{VALUE}};',
                    ]
                ]
                );
        
        /*Prefix Suffix Typography*/
        $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name'          => 'prefix_suffix_typography',
                    'scheme'        => Scheme_Typography::TYPOGRAPHY_1,
                    'selector'      => '{{WRAPPER}} .premium-prefix-text, {{WRAPPER}} .premium-suffix-text',
                ]
                );
        
        /*Prefix Suffix Text Background Color*/
        $this->add_control('premium_prefix_suffix_text_background_color',
                [
                    'label'         => __('Background Color', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::COLOR,
                    'selectors'     => [
                        '{{WRAPPER}} .premium-prefix-text, {{WRAPPER}} .premium-suffix-text' => 'background-color: {{VALUE}};',
                    ]
                ]
                );
        
        /*End Prefix Suffix Text Settings Tab*/
        $this->end_controls_section();
    }

    /**
	 * Render Fancy Text widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
    protected function render() {
        
        $settings   = $this->get_settings_for_display();
        
        $effect     = $settings['premium_fancy_text_effect'];
        
        if( $effect === 'typing' ) {
            
            $show_cursor = ( ! empty( $settings['premium_fancy_text_show_cursor'] ) ) ? true : false;
            
            $cursor_text = addslashes( $settings['premium_fancy_text_cursor_text'] );
            
            $loop = ! empty( $settings['premium_fancy_text_type_loop'] ) ? true : false;
            
            $strings = array();
            
            foreach ( $settings['premium_fancy_text_strings'] as $item ) {
                if ( ! empty( $item['premium_text_strings_text_field'] ) ) {
                    array_push( $strings, str_replace('\'','&#39;', $item['premium_text_strings_text_field'] ) );
                }
            }
            $fancytext_settings = [
                'effect'    => $effect,
                'strings'   => $strings,
                'typeSpeed' => $settings['premium_fancy_text_type_speed'],
                'backSpeed' => $settings['premium_fancy_text_back_speed'],
                'startDelay'=> $settings['premium_fancy_text_start_delay'],
                'backDelay' => $settings['premium_fancy_text_back_delay'],
                'showCursor'=> $show_cursor,
                'cursorChar'=> $cursor_text,
                'loop'      => $loop,
            ];
        } elseif( $effect === 'slide' ) {
            
            $this->add_render_attribute( 'prefix', 'class', 'premium-fancy-text-span-align' );
            $this->add_render_attribute( 'suffix', 'class', 'premium-fancy-text-span-align' );
            
            $mause_pause = 'yes' === $settings['premium_slide_up_hover_pause'] ? true : false;
            $fancytext_settings = [
                'effect'        => $effect,
                'speed'         => $settings['premium_slide_up_speed'],
                'showItems'     => $settings['premium_slide_up_shown_items'],
                'pause'         => $settings['premium_slide_up_pause_time'],
                'mousePause'    => $mause_pause
            ];
        } else {

            $fancytext_settings = [
                'effect'        => $effect,
                'delay'         => $settings['premium_fancy_text_zoom_delay'],
                'count'         => $settings['loop_count'],
            ];

            if( $effect === 'custom' ) {
                $fancytext_settings['animation'] = $settings['custom_animation'];
            }
        }
        
        $this->add_render_attribute('wrapper', 'class', [ 'premium-fancy-text-wrapper', $effect ] );
        
        $this->add_render_attribute('wrapper', 'data-settings', wp_json_encode( $fancytext_settings ) );
        
    ?>
    
        <div <?php echo $this->get_render_attribute_string('wrapper'); ?>>
            <span class="premium-prefix-text"><span <?php echo $this->get_render_attribute_string('prefix'); ?>><?php echo wp_kses( ( $settings['premium_fancy_prefix_text'] ), true ); ?></span></span>

        <?php if ( $effect === 'typing'  ) : ?>
            <span class="premium-fancy-text"></span>
        <?php else : ?> 
            <div class="premium-fancy-text" style='display: inline-block; text-align: center'>
                <ul class="premium-fancy-text-items-wrapper">
                    <?php foreach ( $settings['premium_fancy_text_strings'] as $index => $item ) :
                        if ( ! empty( $item['premium_text_strings_text_field'] ) ) :
                            $this->add_render_attribute( 'text_' . $item['_id'], 'class', 'premium-fancy-list-items' );
                        
                            if( ( 'typing' !== $effect && 'slide' !== $effect ) && 0 !== $index ) {
                                $this->add_render_attribute( 'text_' . $item['_id'], 'class', 'premium-fancy-item-hidden' );
                            } else {
                                $this->add_render_attribute( 'text_' . $item['_id'], 'class', 'premium-fancy-item-visible' );
                            }

                        ?>
                            <li <?php echo $this->get_render_attribute_string('text_' . $item['_id'] ) ?>>
                                <?php echo esc_html( $item['premium_text_strings_text_field'] ); ?>
                            </li>
                        <?php endif; 
                    endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        <span class="premium-suffix-text"><span <?php echo $this->get_render_attribute_string('suffix'); ?>><?php echo wp_kses( ( $settings['premium_fancy_suffix_text'] ), true ); ?></span></span>
    </div>
    <?php
    }
    
    /**
	 * Render Fancy Text widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
    protected function content_template() {
        ?>
        <#
        
            view.addInlineEditingAttributes('prefix');
            view.addInlineEditingAttributes('suffix');
            
            var effect = settings.premium_fancy_text_effect;
            
            var fancyTextSettings = {};
            
            fancyTextSettings.effect = effect;
        
        if( 'typing' === effect ) {
        
            var cursorText          = settings.premium_fancy_text_cursor_text,
                cursorTextEscaped   = cursorText.replace(/'/g, "\\'"),
                showCursor  = settings.premium_fancy_text_show_cursor ? true : false,
                loop        = settings.premium_fancy_text_type_loop ? true : false,
                strings     = [];
            
            _.each( settings.premium_fancy_text_strings, function( item ) {
                if ( '' !== item.premium_text_strings_text_field ) {
                
                    var fancyString = item.premium_text_strings_text_field;
                    
                    strings.push( fancyString );
                }
            });

            fancyTextSettings.strings    = strings,
            fancyTextSettings.typeSpeed  = settings.premium_fancy_text_type_speed,
            fancyTextSettings.backSpeed  = settings.premium_fancy_text_back_speed,
            fancyTextSettings.startDelay = settings.premium_fancy_text_start_delay,
            fancyTextSettings.backDelay  = settings.premium_fancy_text_back_delay,
            fancyTextSettings.showCursor = showCursor,
            fancyTextSettings.cursorChar = cursorTextEscaped,
            fancyTextSettings.loop       = loop;
            
            
        } else if ( 'slide' === effect ) {

            view.addRenderAttribute( 'prefix', 'class', 'premium-fancy-text-span-align' );
            view.addRenderAttribute( 'suffix', 'class', 'premium-fancy-text-span-align' );
        
            var mausePause = 'yes' === settings.premium_slide_up_hover_pause ? true : false;
            
            fancyTextSettings.speed         = settings.premium_slide_up_speed,
            fancyTextSettings.showItems     = settings.premium_slide_up_shown_items,
            fancyTextSettings.pause         = settings.premium_slide_up_pause_time,
            fancyTextSettings.mousePause    = mausePause
           
        } else {
            
            fancyTextSettings.delay         = settings.premium_fancy_text_zoom_delay;
            fancyTextSettings.count         = settings.loop_count;

            if( 'custom' === effect ) {
                fancyTextSettings.animation = settings.custom_animation;
            }
        
        }
        
            view.addRenderAttribute( 'container', 'class', [ 'premium-fancy-text-wrapper', effect ] );
            view.addRenderAttribute( 'container', 'data-settings', JSON.stringify( fancyTextSettings ) );
        
        #>
        
            <div {{{ view.getRenderAttributeString('container') }}}>
                <span class="premium-prefix-text"><span {{{ view.getRenderAttributeString('prefix') }}}>{{{ settings.premium_fancy_prefix_text }}}</span></span>

            <# if ( 'typing' === effect ) { #>
                <span class="premium-fancy-text"></span>
            <# } else { #> 
                <div class="premium-fancy-text" style=' display: inline-block; text-align: center;'>
                    <ul class="premium-fancy-text-items-wrapper">
                        <# _.each ( settings.premium_fancy_text_strings, function ( item, index ) {
                            if ( '' !== item.premium_text_strings_text_field ) {
                                view.addRenderAttribute( 'text_' + item._id, 'class', 'premium-fancy-list-items' );
                        
                            if( ( 'typing' !== effect && 'slide' !== effect ) && 0 !== index ) {
                                view.addRenderAttribute( 'text_' + item._id, 'class', 'premium-fancy-item-hidden' );
                            } else {
                                view.addRenderAttribute( 'text_' + item._id, 'class', 'premium-fancy-item-visible' );
                            } #>
                            
                                <li {{{ view.getRenderAttributeString('text_' + item._id ) }}}>{{{ item.premium_text_strings_text_field }}}</li>
                        <# } }); #>
                    </ul>
                </div>
            <# } #>
                <span class="premium-suffix-text"><span {{{ view.getRenderAttributeString('suffix') }}}>{{{ settings.premium_fancy_suffix_text }}}</span></span>
            </div>
        
        <?php
    }
    
}