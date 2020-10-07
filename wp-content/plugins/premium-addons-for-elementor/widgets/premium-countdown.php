<?php

/**
 * Premium Countdown.
 */
namespace PremiumAddons\Widgets;

// Elementor Classes.
use Elementor\Modules\DynamicTags\Module as TagsModule;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Scheme_Color;
use Elementor\Scheme_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;

// PremiumAddons Classes.
use PremiumAddons\Helper_Functions;

if( !defined( 'ABSPATH' ) ) exit; // No access of directly access

/**
 * Class Premium_Countdown
 */
class Premium_Countdown extends Widget_Base {
    
	public function get_name() {
		return 'premium-countdown-timer';
	}

	public function get_title() {
        return sprintf( '%1$s %2$s', Helper_Functions::get_prefix(), __('Countdown', 'premium-addons-for-elementor') );
	}

	public function get_icon() {
		return 'pa-countdown';
	}
    
    public function is_reload_preview_required() {
        return true;
    }
    
    public function get_style_depends() {
        return [
            'premium-addons'
        ];
    }
    
    public function get_script_depends() {
		return [
            'count-down-timer-js',
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
	 * Register Countdown controls.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function _register_controls() {
		$this->start_controls_section(
			'premium_countdown_global_settings',
			[
				'label'		=> __( 'Countdown', 'premium-addons-for-elementor' )
			]
		);

		$this->add_control(
			'premium_countdown_style',
		  	[
		     	'label'			=> __( 'Style', 'premium-addons-for-elementor' ),
		     	'type' 			=> Controls_Manager::SELECT,
		     	'options' 		=> [
		     		'd-u-s' => __( 'Inline', 'premium-addons-for-elementor' ),
		     		'd-u-u' => __( 'Block', 'premium-addons-for-elementor' ),
		     	],
		     	'default'		=> 'd-u-u'
		  	]
		);

		$this->add_control(
			'premium_countdown_date_time',
		  	[
		     	'label'			=> __( 'Due Date', 'premium-addons-for-elementor' ),
		     	'type' 			=> Controls_Manager::DATE_TIME,
		     	'picker_options'	=> [
		     		'format' => 'Ym/d H:m:s'
		     	],
		     	'default' => date( "Y/m/d H:m:s", strtotime("+ 1 Day") ),
				'description' => __( 'Date format is (yyyy/mm/dd). Time format is (hh:mm:ss). Example: 2020-01-01 09:30.', 'premium-addons-for-elementor' )
		  	]
		);

		$this->add_control(
			'premium_countdown_s_u_time',
			[
				'label' 		=> __( 'Time Zone', 'premium-addons-for-elementor' ),
				'type' 			=> Controls_Manager::SELECT,
				'options' 		=> [
					'wp-time'			=> __('WordPress Default', 'premium-addons-for-elementor' ),
					'user-time'			=> __('User Local Time', 'premium-addons-for-elementor' )
				],
				'default'		=> 'wp-time',
				'description'	=> __('This will set the current time of the option that you will choose.', 'premium-addons-for-elementor')
			]
		);

		$this->add_control(
			'premium_countdown_units',
		  	[
		     	'label'			=> __( 'Time Units', 'premium-addons-for-elementor' ),
		     	'type' 			=> Controls_Manager::SELECT2,
				'description' => __('Select the time units that you want to display in countdown timer.', 'premium-addons-for-elementor' ),
				'options'		=> [
					'Y'     => __( 'Years', 'premium-addons-for-elementor' ),
					'O'     => __( 'Month', 'premium-addons-for-elementor' ),
					'W'     => __( 'Week', 'premium-addons-for-elementor' ),
					'D'     => __( 'Day', 'premium-addons-for-elementor' ),
					'H'     => __( 'Hours', 'premium-addons-for-elementor' ),
					'M'     => __( 'Minutes', 'premium-addons-for-elementor' ),
					'S' 	=> __( 'Second', 'premium-addons-for-elementor' ),
				],
				'default' 		=> [ 'O', 'D', 'H', 'M', 'S' ],
				'multiple'		=> true,
				'separator'		=> 'after'
		  	]
		);
        
        $this->add_control('premium_countdown_separator',
            [
                'label'         => __('Digits Separator', 'premium-addons-for-elementor'),
                'description'   => __('Enable or disable digits separator','premium-addons-for-elementor'),
                'type'          => Controls_Manager::SWITCHER,
                'condition'     => [
                    'premium_countdown_style'   => 'd-u-u'
                ]
            ]
        );
        
        $this->add_control(
			'premium_countdown_separator_text',
			[
				'label'			=> __('Separator Text', 'premium-addons-for-elementor'),
				'type'			=> Controls_Manager::TEXT,
				'condition'		=> [
                    'premium_countdown_style'   => 'd-u-u',
					'premium_countdown_separator' => 'yes'
				],
				'default'		=> ':'
			]
		);
        
        $this->add_responsive_control(
            'premium_countdown_align',
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
                    'toggle'        => false,
                    'default'       => 'center',
                    'selectors'     => [
                        '{{WRAPPER}} .premium-countdown' => 'justify-content: {{VALUE}};',
                        ],
                    ]
                );

		$this->end_controls_section();

		$this->start_controls_section(
			'premium_countdown_on_expire_settings',
			[
				'label' => __( 'Expire' , 'premium-addons-for-elementor' )
			]
		);

		$this->add_control(
			'premium_countdown_expire_text_url',
			[
				'label'			=> __('Expire Type', 'premium-addons-for-elementor'),
				'label_block'	=> false,
				'type'			=> Controls_Manager::SELECT,
                'description'   => __('Choose whether if you want to set a message or a redirect link', 'premium-addons-for-elementor'),
				'options'		=> [
					'text'		=> __('Message', 'premium-addons-for-elementor'),
					'url'		=> __('Redirection Link', 'premium-addons-for-elementor')
				],
				'default'		=> 'text'
			]
		);

		$this->add_control(
			'premium_countdown_expiry_text_',
			[
				'label'			=> __('On expiry Text', 'premium-addons-for-elementor'),
				'type'			=> Controls_Manager::WYSIWYG,
                'dynamic'       => [ 'active' => true ],
				'default'		=> __('Countdown is finished!','prmeium_elementor'),
				'condition'		=> [
					'premium_countdown_expire_text_url' => 'text'
				]
			]
		);

		$this->add_control(
			'premium_countdown_expiry_redirection_',
			[
				'label'			=> __('Redirect To', 'premium-addons-for-elementor'),
				'type'			=> Controls_Manager::TEXT,
                'dynamic'       => [
                    'active' => true,
                    'categories' => [
                        TagsModule::POST_META_CATEGORY,
                        TagsModule::URL_CATEGORY
                    ]
                ],
				'condition'		=> [
					'premium_countdown_expire_text_url' => 'url'
				],
				'default'		=> get_permalink( 1 )
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'premium_countdown_transaltion',
			[
				'label' => __( 'Strings Translation' , 'premium-addons-for-elementor' )
			]
		);

		$this->add_control(
			'premium_countdown_day_singular',
		  	[
		     	'label'			=> __( 'Day (Singular)', 'premium-addons-for-elementor' ),
		     	'type' 			=> Controls_Manager::TEXT,
                'dynamic'       => [ 'active' => true ],
		     	'default'		=> 'Day'
		  	]
		);

		$this->add_control(
			'premium_countdown_day_plural',
		  	[
		     	'label'			=> __( 'Day (Plural)', 'premium-addons-for-elementor' ),
		     	'type' 			=> Controls_Manager::TEXT,
                'dynamic'       => [ 'active' => true ],
		     	'default'		=> 'Days'
		  	]
		);

		$this->add_control(
			'premium_countdown_week_singular',
		  	[
		     	'label'			=> __( 'Week (Singular)', 'premium-addons-for-elementor' ),
		     	'type' 			=> Controls_Manager::TEXT,
                'dynamic'       => [ 'active' => true ],
		     	'default'		=> 'Week'
		  	]
		);

		$this->add_control(
			'premium_countdown_week_plural',
		  	[
		     	'label'			=> __( 'Weeks (Plural)', 'premium-addons-for-elementor' ),
		     	'type' 			=> Controls_Manager::TEXT,
                'dynamic'       => [ 'active' => true ],
		     	'default'		=> 'Weeks'
		  	]
		);


		$this->add_control(
			'premium_countdown_month_singular',
		  	[
		     	'label'			=> __( 'Month (Singular)', 'premium-addons-for-elementor' ),
		     	'type' 			=> Controls_Manager::TEXT,
                'dynamic'       => [ 'active' => true ],
		     	'default'		=> 'Month'
		  	]
		);


		$this->add_control(
			'premium_countdown_month_plural',
		  	[
		     	'label'			=> __( 'Months (Plural)', 'premium-addons-for-elementor' ),
		     	'type' 			=> Controls_Manager::TEXT,
                'dynamic'       => [ 'active' => true ],
		     	'default'		=> 'Months'
		  	]
		);


		$this->add_control(
			'premium_countdown_year_singular',
		  	[
		     	'label'			=> __( 'Year (Singular)', 'premium-addons-for-elementor' ),
		     	'type' 			=> Controls_Manager::TEXT,
                'dynamic'       => [ 'active' => true ],
		     	'default'		=> 'Year'
		  	]
		);


		$this->add_control(
			'premium_countdown_year_plural',
		  	[
		     	'label'			=> __( 'Years (Plural)', 'premium-addons-for-elementor' ),
		     	'type' 			=> Controls_Manager::TEXT,
                'dynamic'       => [ 'active' => true ],
		     	'default'		=> 'Years'
		  	]
		);


		$this->add_control(
			'premium_countdown_hour_singular',
		  	[
		     	'label'			=> __( 'Hour (Singular)', 'premium-addons-for-elementor' ),
		     	'type' 			=> Controls_Manager::TEXT,
                'dynamic'       => [ 'active' => true ],
		     	'default'		=> 'Hour'
		  	]
		);


		$this->add_control(
			'premium_countdown_hour_plural',
		  	[
		     	'label'			=> __( 'Hours (Plural)', 'premium-addons-for-elementor' ),
		     	'type' 			=> Controls_Manager::TEXT,
                'dynamic'       => [ 'active' => true ],
		     	'default'		=> 'Hours'
		  	]
		);


		$this->add_control(
			'premium_countdown_minute_singular',
		  	[
		     	'label'			=> __( 'Minute (Singular)', 'premium-addons-for-elementor' ),
		     	'type' 			=> Controls_Manager::TEXT,
                'dynamic'       => [ 'active' => true ],
		     	'default'		=> 'Minute'
		  	]
		);

		$this->add_control(
			'premium_countdown_minute_plural',
		  	[
		     	'label'			=> __( 'Minutes (Plural)', 'premium-addons-for-elementor' ),
		     	'type' 			=> Controls_Manager::TEXT,
                'dynamic'       => [ 'active' => true ],
		     	'default'		=> 'Minutes'
		  	]
		);

        $this->add_control(
			'premium_countdown_second_singular',
		  	[
		     	'label'			=> __( 'Second (Singular)', 'premium-addons-for-elementor' ),
		     	'type' 			=> Controls_Manager::TEXT,
                'dynamic'       => [ 'active' => true ],
		     	'default'		=> 'Second',
		  	]
		);
        
		$this->add_control(
			'premium_countdown_second_plural',
		  	[
		     	'label'			=> __( 'Seconds (Plural)', 'premium-addons-for-elementor' ),
		     	'type' 			=> Controls_Manager::TEXT,
                'dynamic'       => [ 'active' => true ],
		     	'default'		=> 'Seconds'
		  	]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'premium_countdown_typhography',
			[
				'label' => __( 'Digits' , 'premium-addons-for-elementor' ),
				'tab' 			=> Controls_Manager::TAB_STYLE
			]
		);

		$this->add_control(
			'premium_countdown_digit_color',
			[
				'label' 		=> __( 'Color', 'premium-addons-for-elementor' ),
				'type' 			=> Controls_Manager::COLOR,
				'scheme' 		=> [
				    'type' 	=> Scheme_Color::get_type(),
				    'value' => Scheme_Color::COLOR_2,
				],
				'selectors'		=> [
					'{{WRAPPER}} .countdown .pre_countdown-section .pre_countdown-amount' => 'color: {{VALUE}};'
				]
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'premium_countdown_digit_typo',
				'scheme' => Scheme_Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .countdown .pre_countdown-section .pre_countdown-amount',
				'separator'		=> 'after'
			]
		);
        
        
        $this->add_control(
			'premium_countdown_timer_digit_bg_color',
			[
				'label' 		=> __( 'Background Color', 'premium-addons-for-elementor' ),
				'type' 			=> Controls_Manager::COLOR,
				'scheme' 		=> [
				    'type' 	=> Scheme_Color::get_type(),
				    'value' => Scheme_Color::COLOR_1,
				],
				'selectors'		=> [
					'{{WRAPPER}} .countdown .pre_countdown-section .pre_countdown-amount' => 'background-color: {{VALUE}};'
				]
			]
		);
        
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'          => 'premium_countdown_units_shadow',
                'selector'      => '{{WRAPPER}} .countdown .pre_countdown-section',
            ]
        );
        
        $this->add_responsive_control(
			'premium_countdown_digit_bg_size',
		  	[
		     	'label'			=> __( 'Background Size', 'premium-addons-for-elementor' ),
		     	'type' 			=> Controls_Manager::SLIDER,
                'default'       => [
                    'size'  => 30
                ],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 400,
					]
				],
				'selectors'		=> [
					'{{WRAPPER}} .countdown .pre_countdown-section .pre_countdown-amount' => 'padding: {{SIZE}}px;'
				]
		  	]
		);
        
        $this->add_group_control(
            Group_Control_Border::get_type(),
                [
                    'name'          => 'premium_countdown_digits_border',
                    'selector'      => '{{WRAPPER}} .countdown .pre_countdown-section .pre_countdown-amount',
                ]);

        $this->add_control('premium_countdown_digit_border_radius',
                [
                    'label'         => __('Border Radius', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::DIMENSIONS,
                    'size_units'    => ['px', '%', 'em'],
                    'selectors'     => [
                        '{{WRAPPER}} .countdown .pre_countdown-section .pre_countdown-amount' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                    ]
                ]
            );
        
        $this->end_controls_section();
        
        $this->start_controls_section('premium_countdown_unit_style', 
            [
                'label'         => __('Units', 'premium-addons-for-elementor'),
                'tab'           => Controls_Manager::TAB_STYLE,
            ]
            );

        $this->add_control(
			'premium_countdown_unit_color',
			[
				'label' 		=> __( 'Color', 'premium-addons-for-elementor' ),
				'type' 			=> Controls_Manager::COLOR,
				'scheme' 		=> [
				    'type' 	=> Scheme_Color::get_type(),
				    'value' => Scheme_Color::COLOR_2,
				],
				'selectors'		=> [
					'{{WRAPPER}} .countdown .pre_countdown-section .pre_countdown-period' => 'color: {{VALUE}};'
				]
			]
		);
        
        $this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'premium_countdown_unit_typo',
				'scheme' => Scheme_Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .countdown .pre_countdown-section .pre_countdown-period',
			]
		);
        
        $this->add_control(
			'premium_countdown_unit_backcolor',
			[
				'label' 		=> __( 'Background Color', 'premium-addons-for-elementor' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors'		=> [
					'{{WRAPPER}} .countdown .pre_countdown-section .pre_countdown-period' => 'background-color: {{VALUE}};'
				]
			]
		);

        $this->add_responsive_control(
			'premium_countdown_separator_width',
			[
				'label'			=> __( 'Spacing in Between', 'premium-addons-for-elementor' ),
				'type' 			=> Controls_Manager::SLIDER,
				'default' 		=> [
					'size' => 40,
				],
				'range' 		=> [
					'px' 	=> [
						'min' => 0,
						'max' => 200,
					]
				],
				'selectors'		=> [
					'{{WRAPPER}} .countdown .pre_countdown-section' => 'margin-right: calc( {{SIZE}}{{UNIT}} / 2 ); margin-left: calc( {{SIZE}}{{UNIT}} / 2 );'
				],
                'condition'		=> [
					'premium_countdown_separator!' => 'yes'
				],
			]
		);

		$this->end_controls_section();
        
        $this->start_controls_section('premium_countdown_separator_style', 
            [
                'label'         => __('Separator', 'premium-addons-for-elementor'),
                'tab'           => Controls_Manager::TAB_STYLE,
                'condition'		=> [
                    'premium_countdown_style'   => 'd-u-u',
					'premium_countdown_separator' => 'yes'
				],
            ]
            );
        
        $this->add_responsive_control(
			'premium_countdown_separator_size',
		  	[
		     	'label'			=> __( 'Size', 'premium-addons-for-elementor' ),
		     	'type' 			=> Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 100,
					]
				],
				'selectors'		=> [
					'{{WRAPPER}} .pre-countdown_separator' => 'font-size: {{SIZE}}px;'
				]
		  	]
		);

        $this->add_control(
			'premium_countdown_separator_color',
			[
				'label' 		=> __( 'Color', 'premium-addons-for-elementor' ),
				'type' 			=> Controls_Manager::COLOR,
				'scheme' 		=> [
				    'type' 	=> Scheme_Color::get_type(),
				    'value' => Scheme_Color::COLOR_2,
				],
				'selectors'		=> [
					'{{WRAPPER}} .pre-countdown_separator' => 'color: {{VALUE}};'
				]
			]
		);
        
        $this->add_responsive_control('premium_countdown_separator_margin',
                [
                    'label'         => __('Margin', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::DIMENSIONS,
                    'size_units'    => ['px', 'em'],
                    'selectors'     => [
                        '{{WRAPPER}} .pre-countdown_separator' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                    ]
                ]
            );
        
        $this->end_controls_section();
        
	}

	/**
	 * Render Countdown widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render( ) {
		
      	$settings = $this->get_settings_for_display();

      	$target_date = str_replace('-', '/', $settings['premium_countdown_date_time'] );
        
      	$formats = $settings['premium_countdown_units'];
      	$format = implode('', $formats );
      	$time = str_replace('-', '/', current_time('mysql') );
      	
      	if( $settings['premium_countdown_s_u_time'] == 'wp-time' ) : 
			$sent_time = $time;
        else:
            $sent_time = '';
        endif;

		$redirect = !empty( $settings['premium_countdown_expiry_redirection_'] ) ? esc_url($settings['premium_countdown_expiry_redirection_']) : '';
        
      	// Singular labels set up
      	$y = !empty( $settings['premium_countdown_year_singular'] ) ? $settings['premium_countdown_year_singular'] : 'Year';
      	$m = !empty( $settings['premium_countdown_month_singular'] ) ? $settings['premium_countdown_month_singular'] : 'Month';
      	$w = !empty( $settings['premium_countdown_week_singular'] ) ? $settings['premium_countdown_week_singular'] : 'Week';
      	$d = !empty( $settings['premium_countdown_day_singular'] ) ? $settings['premium_countdown_day_singular'] : 'Day';
      	$h = !empty( $settings['premium_countdown_hour_singular'] ) ? $settings['premium_countdown_hour_singular'] : 'Hour';
      	$mi = !empty( $settings['premium_countdown_minute_singular'] ) ? $settings['premium_countdown_minute_singular'] : 'Minute';
      	$s = !empty( $settings['premium_countdown_second_singular'] ) ? $settings['premium_countdown_second_singular'] : 'Second';
      	$label = $y."," . $m ."," . $w ."," . $d ."," . $h ."," . $mi ."," . $s;

      	// Plural labels set up
      	$ys = !empty( $settings['premium_countdown_year_plural'] ) ? $settings['premium_countdown_year_plural'] : 'Years';
      	$ms = !empty( $settings['premium_countdown_month_plural'] ) ? $settings['premium_countdown_month_plural'] : 'Months';
      	$ws = !empty( $settings['premium_countdown_week_plural'] ) ? $settings['premium_countdown_week_plural'] : 'Weeks';
      	$ds = !empty( $settings['premium_countdown_day_plural'] ) ? $settings['premium_countdown_day_plural'] : 'Days';
      	$hs = !empty( $settings['premium_countdown_hour_plural'] ) ? $settings['premium_countdown_hour_plural'] : 'Hours';
      	$mis = !empty( $settings['premium_countdown_minute_plural'] ) ? $settings['premium_countdown_minute_plural'] : 'Minutes';
      	$ss = !empty( $settings['premium_countdown_second_plural'] ) ? $settings['premium_countdown_second_plural'] : 'Seconds';
      	$labels1 = $ys."," . $ms ."," . $ws ."," . $ds ."," . $hs ."," . $mis ."," . $ss;
      	
        $expire_text = $settings['premium_countdown_expiry_text_'];
        
      	$pcdt_style = $settings['premium_countdown_style'] == 'd-u-s' ? ' side' : ' down';
        
        if( $settings['premium_countdown_expire_text_url'] == 'text' ){
            $event = 'onExpiry';
            $text = $expire_text;
        }
        
        if( $settings['premium_countdown_expire_text_url'] == 'url' ){
            $event = 'expiryUrl';
            $text = $redirect;
        }
        
        $separator_text = ! empty ( $settings['premium_countdown_separator_text'] ) ? $settings['premium_countdown_separator_text'] : '';
        
        $countdown_settings = [
            'label1'    => $label,
            'label2'    => $labels1,
            'until'     => $target_date,
            'format'    => $format,
            'event'     => $event,
            'text'      => $text,
            'serverSync'=> $sent_time,
            'separator' => $separator_text
        ];
        
      	?>
        <div id="countDownContiner-<?php echo esc_attr($this->get_id()); ?>" class="premium-countdown premium-countdown-separator-<?php  echo $settings['premium_countdown_separator']; ?>" data-settings='<?php echo wp_json_encode( $countdown_settings ); ?>'>
            <div id="countdown-<?php echo esc_attr( $this->get_id() ); ?>" class="premium-countdown-init countdown<?php echo $pcdt_style; ?>"></div>
        </div>
      	<?php
    }
}