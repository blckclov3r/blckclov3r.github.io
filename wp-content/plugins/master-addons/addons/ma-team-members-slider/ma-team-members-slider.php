<?php
	namespace Elementor;
	use Elementor\Widget_Base;
	use MasterAddons\Inc\Helper\Master_Addons_Helper;

	if ( ! defined( 'ABSPATH' ) ) exit; // If this file is called directly, abort.

	class Master_Addons_Team_Members_Carousel extends Widget_Base {

		public function get_name() {
			return 'ma-team-members-slider';
		}

		public function get_title() {
			return esc_html__( 'MA Team Members Carousel', MELA_TD);
		}

		public function get_icon() {
			return 'ma-el-icon eicon-person';
		}

		public function get_categories() {
			return [ 'master-addons' ];
		}

		public function get_script_depends() {
			return [ 'jquery-slick', 'gridder' ];
		}

		public function get_style_depends() {
			return [
				'gridder',
				'font-awesome-5-all',
                'font-awesome-4-shim'
			];
		}

		public function get_help_url() {
			return 'https://master-addons.com/demos/team-carousel/';
		}

		protected function _register_controls() {

			$this->start_controls_section(
				'section_team_carousel',
				[
					'label' => esc_html__( 'Contents', MELA_TD ),
				]
			);


			// Premium Version Codes
			if ( ma_el_fs()->can_use_premium_code() ) {
				$this->add_control(
					'ma_el_team_carousel_preset',
					[
						'label' => esc_html__( 'Style Preset', MELA_TD ),
						'type' => Controls_Manager::SELECT,
						'default' => '-default',
						'options' => [
							'-default'              => __( 'Team Carousel', MELA_TD ),
							'-circle'               => __( 'Circle Gradient', MELA_TD ),
							'-circle-animation'     => __( 'Circle Animation', MELA_TD ),
							'-social-left'          => __( 'Social Left on Hover', MELA_TD ),
							'-content-hover'        => __( 'Content on Hover', MELA_TD ),
							'-content-drawer'       => __( 'Content Drawer', MELA_TD ),
						],
					]
				);

			} else{
				$this->add_control(
					'ma_el_team_carousel_preset',
					[
						'label' => __( 'Style Preset', MELA_TD ),
						'type' => Controls_Manager::SELECT,
						'default' => '-default',
						'options' => [
							'-default'                    => __( 'Team Carousel', MELA_TD ),
							'-circle'                     => __( 'Circle Gradient', MELA_TD ),
							'-content-hover'              => __( 'Content on Hover', MELA_TD ),
							'-pro-team-slider-1'          => __( 'Social Left on Hover (Pro)', MELA_TD ),
							'-pro-team-slider-2'          => __( 'Content Drawer (Pro)', MELA_TD ),
							'-pro-team-slider-3'          => __( 'Circle Animation (Pro)', MELA_TD )
						],
						'description' => sprintf( '5+ more Variations on <a href="%s" target="_blank">%s</a>',
							esc_url_raw( admin_url('admin.php?page=master-addons-settings-pricing') ),
							__( 'Upgrade Now', MELA_TD ) )
					]
				);

			}




			if ( ma_el_fs()->can_use_premium_code() ) {
				$this->add_control(
					'ma_el_team_circle_image',
					[
						'label' => esc_html__( 'Circle Gradient Image', MELA_TD ),
						'type' => Controls_Manager::SELECT,
						'default' => 'circle_01',
						'options' => [
							'circle_01'                   => esc_html__( 'Circle 01', MELA_TD ),
							'circle_02'                   => esc_html__( 'Circle 02', MELA_TD ),
							'circle_03'                   => esc_html__( 'Circle 03', MELA_TD ),
							'circle_04'                   => esc_html__( 'Circle 04', MELA_TD ),
							'circle_05'                   => esc_html__( 'Circle 05', MELA_TD ),
							'circle_06'                   => esc_html__( 'Circle 06', MELA_TD ),
							'circle_07'                   => esc_html__( 'Circle 07', MELA_TD ),
						],
						'condition' => [
							'ma_el_team_carousel_preset' => '-circle'
						]
					]
				);
			}else{
				$this->add_control(
					'ma_el_team_circle_image',
					[
						'label' => esc_html__( 'Circle Gradient Image', MELA_TD ),
						'type' => Controls_Manager::SELECT,
						'default' => 'circle_01',
						'options' => [
							'circle_01'                   	 => esc_html__( 'Circle 01', MELA_TD ),
							'circle_02'                   	 => esc_html__( 'Circle 02', MELA_TD ),
							'circle_03'                   	 => esc_html__( 'Circle 03', MELA_TD ),
							'circle-pro-1'                   => esc_html__( 'Circle 04 (Pro)', MELA_TD ),
							'circle-pro-2'                   => esc_html__( 'Circle 05 (Pro)', MELA_TD ),
							'circle-pro-3'                   => esc_html__( 'Circle 06 (Pro)', MELA_TD ),
							'circle-pro-4'                   => esc_html__( 'Circle 07 (Pro)', MELA_TD ),
						],
						'condition' => [
							'ma_el_team_carousel_preset' => '-circle'
						],
						'description' => '<span class="pro-feature">Animated Variations are Pro Features. Upgrade to  <a href="' . ma_el_fs()->get_upgrade_url() . '" target="_blank">Pro Version</a> unlock this Option.</span>'
					]
				);
			}


			// Premium Version Codes
			if ( !ma_el_fs()->can_use_premium_code() ) {
				$this->add_control(
					'ma_el_team_circle_image_animation',
					[
						'label'       => esc_html__( 'Animation Style', MELA_TD ),
						'type'        => Controls_Manager::SELECT,
						'default'     => 'animation_svg_01',
						'options'     => [
							'animation_svg_01' 		=> esc_html__( 'Animation 1', MELA_TD ),
							'animation_svg_02' 		=> esc_html__( 'Animation 2', MELA_TD ),
							'animation_svg_03' 		=> esc_html__( 'Animation 3', MELA_TD ),
							'svg_animated_pro_1' 	=> esc_html__( 'Animation 4 (Pro)', MELA_TD ),
							'svg_animated_pro_2' 	=> esc_html__( 'Animation 5 (Pro)', MELA_TD ),
							'svg_animated_pro_3' 	=> esc_html__( 'Animation 6 (Pro)', MELA_TD ),
							'svg_animated_pro_4' 	=> esc_html__( 'Animation 7 (Pro)', MELA_TD ),
						],
						'condition'   => [
							'ma_el_team_carousel_preset' => '-circle-animation'
						],
						'description' => sprintf( '5+ More Animated Variations Available on Pro Version <a href="%s" target="_blank">%s</a>', esc_url_raw( admin_url( 'admin.php?page=master-addons-settings-pricing' ) ),
							__( 'Upgrade Now', MELA_TD ) )						
					]
				);
			}else{
				$this->add_control(
					'ma_el_team_circle_image_animation',
					[
						'label'       => esc_html__( 'Animation Style', MELA_TD ),
						'type'        => Controls_Manager::SELECT,
						'default'     => 'animation_svg_01',
						'options'     => [
							'animation_svg_01' => esc_html__( 'Animation 1', MELA_TD ),
							'animation_svg_02' => esc_html__( 'Animation 2', MELA_TD ),
							'animation_svg_03' => esc_html__( 'Animation 3', MELA_TD ),
							'animation_svg_04' => esc_html__( 'Animation 4', MELA_TD ),
							'animation_svg_05' => esc_html__( 'Animation 5', MELA_TD ),
							'animation_svg_06' => esc_html__( 'Animation 6', MELA_TD ),
							'animation_svg_07' => esc_html__( 'Animation 7', MELA_TD ),
						],
						'condition'   => [
							'ma_el_team_carousel_preset' => '-circle-animation'
						]
					]
				);

			}



			$team_repeater = new Repeater();

			/*
			* Team Member Image
			*/
			$team_repeater->add_control(
				'ma_el_team_carousel_image',
				[
					'label' => __( 'Image', MELA_TD ),
					'type' => Controls_Manager::MEDIA,
					'default' => [
						'url' => Utils::get_placeholder_image_src(),
					],
					'selectors' => [
//						'{{WRAPPER}} .ma-el-team-member-circle-animation .ma-el-team-member-thumb .animation_svg_02:after' => 'background-image: url("{{URL}}");'
					]

				]
			);
			$team_repeater->add_group_control(
				Group_Control_Image_Size::get_type(),
				[
					'name' => 'thumbnail',
					'default' => 'full',
					'condition' => [
						'ma_el_team_carousel_image[url]!' => '',
					]
				]
			);

			$team_repeater->add_control(
				'ma_el_team_carousel_name',
				[
					'label' => esc_html__( 'Name', MELA_TD ),
					'type' => Controls_Manager::TEXT,
					'label_block' => true,
					'default' => esc_html__( 'John Doe', MELA_TD ),
				]
			);

			$team_repeater->add_control(
				'ma_el_team_carousel_designation',
				[
					'label' => esc_html__( 'Designation', MELA_TD ),
					'type' => Controls_Manager::TEXT,
					'label_block' => true,
					'default' => esc_html__( 'My Designation', MELA_TD ),
				]
			);

			$team_repeater->add_control(
				'ma_el_team_carousel_description',
				[
					'label' => esc_html__( 'Description', MELA_TD ),
					'type' => Controls_Manager::TEXTAREA,
					'default' => esc_html__( 'Add team member details here', MELA_TD ),
				]
			);

			$team_repeater->add_control(
				'ma_el_team_carousel_enable_social_profiles',
				[
					'label' => esc_html__( 'Display Social Profiles?', MELA_TD ),
					'type' => Controls_Manager::SWITCHER,
					'default' => 'yes',
				]
			);

			$team_repeater->add_control(
				'ma_el_team_carousel_facebook_link',
				[
					'label' => __( 'Facebook URL', MELA_TD ),
					'type' => Controls_Manager::URL,
					'condition' => [
						'ma_el_team_carousel_enable_social_profiles!' => '',
					],
					'placeholder' => __( 'https://master-addons.com', MELA_TD ),
					'label_block' => true,
					'default' => [
						'url' => '',
						'is_external' => true,
					],
				]
			);

			$team_repeater->add_control(
				'ma_el_team_carousel_twitter_link',
				[
					'label' => __( 'Twitter URL', MELA_TD ),
					'type' => Controls_Manager::URL,
					'condition' => [
						'ma_el_team_carousel_enable_social_profiles!' => '',
					],
					'placeholder' => __( 'https://master-addons.com', MELA_TD ),
					'label_block' => true,
					'default' => [
						'url' => '',
						'is_external' => true,
					],
				]
			);

			$team_repeater->add_control(
				'ma_el_team_carousel_instagram_link',
				[
					'label' => __( 'Instagram URL', MELA_TD ),
					'type' => Controls_Manager::URL,
					'condition' => [
						'ma_el_team_carousel_enable_social_profiles!' => '',
					],
					'placeholder' => __( 'https://master-addons.com', MELA_TD ),
					'label_block' => true,
					'default' => [
						'url' => '',
						'is_external' => true,
					],
				]
			);

			$team_repeater->add_control(
				'ma_el_team_carousel_linkedin_link',
				[
					'label' => __( 'Linkedin URL', MELA_TD ),
					'type' => Controls_Manager::URL,
					'condition' => [
						'ma_el_team_carousel_enable_social_profiles!' => '',
					],
					'placeholder' => __( 'https://master-addons.com', MELA_TD ),
					'label_block' => true,
					'default' => [
						'url' => '',
						'is_external' => true,
					],
				]
			);

			$team_repeater->add_control(
				'ma_el_team_carousel_dribbble_link',
				[
					'label' => __( 'Dribbble URL', MELA_TD ),
					'type' => Controls_Manager::URL,
					'condition' => [
						'ma_el_team_carousel_enable_social_profiles!' => '',
					],
					'placeholder' => __( 'https://master-addons.com', MELA_TD ),
					'label_block' => true,
					'default' => [
						'url' => '',
						'is_external' => true,
					],
				]
			);


			$this->add_control(
				'team_carousel_repeater',
				[
					'label' => esc_html__( 'Team Carousel', MELA_TD ),
					'type' => Controls_Manager::REPEATER,
					'fields' => $team_repeater->get_controls(),
					'title_field' => '{{{ ma_el_team_carousel_name }}}',
					'default' => [
						[
							'ma_el_team_carousel_name' => __( 'Member #1', MELA_TD ),
							'ma_el_team_carousel_description' => __( 'Add team member details here', MELA_TD ),
						],
						[
							'ma_el_team_carousel_name' => __( 'Member #2', MELA_TD ),
							'ma_el_team_carousel_description' => __( 'Add team member details here', MELA_TD ),
						],
						[
							'ma_el_team_carousel_name' => __( 'Member #3', MELA_TD ),
							'ma_el_team_carousel_description' => __( 'Add team member details here', MELA_TD ),
						],
						[
							'ma_el_team_carousel_name' => __( 'Member #4', MELA_TD ),
							'ma_el_team_carousel_description' => __( 'Add team member details here', MELA_TD ),
						],
					]
				]
			);


			$this->add_control(
				'title_html_tag',
				[
					'label'   => __( 'Title HTML Tag', MELA_TD ),
					'type'    => Controls_Manager::SELECT,
					'options' => Master_Addons_Helper::ma_el_title_tags(),
					'default' => 'h3',
				]
			);


			$this->end_controls_section();

			/*
			* Team Members Styling Section
			*/
			$this->start_controls_section(
				'ma_el_section_team_carousel_styles_preset',
				[
					'label' => esc_html__( 'General Styles', MELA_TD ),
					'tab' => Controls_Manager::TAB_STYLE
				]
			);

			$this->add_responsive_control(
				'ma_el_team_image_bg_size',
				[
					'label' => __( 'Background Image Size', MELA_TD ),
					'description' => __('Height Width will be same ratio', MELA_TD),
					'type' => Controls_Manager::SLIDER,
					'size_units'    => ['px'],
					'range' => [
						'px' => [
							'min' => 100,
							'max' => 1000,
							'step' => 5,
						]
					],
					'default' => [
						'unit' => 'px',
						'size' => 125,
					],
					'condition' => [
						'ma_el_team_carousel_preset' => ['-circle','-circle-animation']
					],
					'selectors' => [
						'{{WRAPPER}} .ma-el-team-member-circle .ma-el-team-member-thumb svg,
						{{WRAPPER}} .ma-el-team-member-circle .ma-el-team-member-thumb,
						{{WRAPPER}} .ma-el-team-member-circle-animation .ma-el-team-member-thumb svg,
						{{WRAPPER}} .ma-el-team-member-circle-animation .ma-el-team-member-thumb .animation_svg_02,
						{{WRAPPER}} .ma-el-team-member-circle-animation .ma-el-team-member-thumb .animation_svg_03,
						{{WRAPPER}} .ma-el-team-member-circle-animation .ma-el-team-member-thumb' =>
                            'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};'

					]
				]
			);

			$this->add_responsive_control(
				'ma_el_team_image_bg_position_left',
				[
					'label' => __( 'Background Position(Left)', MELA_TD ),
					'type' => Controls_Manager::SLIDER,
					'size_units'    => ['px', '%' ,'em'],
					'default' => [
						'unit' => 'px',
						'size' => -5,
					],
					'range' => [
						'px' => [
							'min' => -100,
							'max' => 150,
							'step' => 1,
						]
					],
					'condition' => [
						'ma_el_team_carousel_preset' => ['-circle','-circle-animation']
					],
					'selectors' => [
						'{{WRAPPER}} .ma-el-team-member-circle .ma-el-team-member-thumb svg,
						{{WRAPPER}} .ma-el-team-member-circle-animation .ma-el-team-member-thumb svg' => 'left: {{SIZE}}{{UNIT}};'
                    ]
				]
			);

			$this->add_responsive_control(
				'ma_el_team_image_bg_position_top',
				[
					'label' => __( 'Background Position(Top)', MELA_TD ),
					'type' => Controls_Manager::SLIDER,
					'size_units'    => ['px', '%' ,'em'],
					'range' => [
						'px' => [
							'min' => 0,
							'max' => 150,
							'step' => 1,
						]
					],
					'condition' => [
						'ma_el_team_carousel_preset' => ['-circle','-circle-animation']
					],
					'selectors' => [
						'{{WRAPPER}} .ma-el-team-member-circle .ma-el-team-member-thumb svg,
						{{WRAPPER}} .ma-el-team-member-circle-animation .ma-el-team-member-thumb svg' => 'top: {{SIZE}}{{UNIT}};'
                    ]
				]
			);


			$this->add_responsive_control(
				'ma_el_team_image_size',
				[
					'label' => __( 'Member Image Size', MELA_TD ),
					'description' => __('Height Width will be same ratio', MELA_TD),
					'type' => Controls_Manager::SLIDER,
					'separator' => 'before',
					'size_units'    => ['px'],
					'range' => [
						'px' => [
							'min' => 100,
							'max' => 1000,
							'step' => 5,
						],
						'%' => [
							'min' => 0,
							'max' => 100,
						],
					],
					'default' => [
						'unit' => 'px',
						'size' => 100,
					],
					'condition' => [
						'ma_el_team_carousel_preset' => ['-circle','-circle-animation']
					],
					'selectors' => [
//						'{{WRAPPER}} .ma-el-team-member-circle .ma-el-team-member-thumb' =>
//                            'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};',

						'{{WRAPPER}} .ma-el-team-member-circle .ma-el-team-member-thumb img,
						{{WRAPPER}} .ma-el-team-member-circle-animation .ma-el-team-member-thumb .animation_svg_03_center,
						{{WRAPPER}} .ma-el-team-member-circle-animation .ma-el-team-member-thumb img' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};',

//						'{{WRAPPER}} .ma-el-team-member-circle .ma-el-team-member-thumb svg' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};',

					]
				]
			);


			$this->add_responsive_control(
				'ma_el_team_image_position_left',
				[
					'label' => __( 'Member Image Position(Left)', MELA_TD ),
					'type' => Controls_Manager::SLIDER,
					'size_units'    => ['px', '%' ,'em'],
					'default' => [
						'unit' => '%',
						'size' => 45,
					],
					'range' => [
						'px' => [
							'min' => -100,
							'max' => 150,
							'step' => 1,
						]
					],
					'condition' => [
						'ma_el_team_carousel_preset' => ['-circle','-circle-animation']
					],
					'selectors' => [
						'{{WRAPPER}} .ma-el-team-member-circle .ma-el-team-member-thumb img,
						{{WRAPPER}} .ma-el-team-member-circle-animation .ma-el-team-member-thumb img' => 'left: {{SIZE}}{{UNIT}};'
					]
				]
			);

			$this->add_responsive_control(
				'ma_el_team_image_position_top',
				[
					'label' => __( 'Member Image Position(Top)', MELA_TD ),
					'type' => Controls_Manager::SLIDER,
					'size_units'    => ['px', '%' ,'em'],
					'default' => [
						'unit' => '%',
						'size' => 45,
					],
					'range' => [
						'px' => [
							'min' => -100,
							'max' => 150,
							'step' => 1,
						]
					],
					'condition' => [
						'ma_el_team_carousel_preset' => ['-circle','-circle-animation']
					],
					'selectors' => [
						'{{WRAPPER}} .ma-el-team-member-circle .ma-el-team-member-thumb img,
						{{WRAPPER}} .ma-el-team-member-circle-animation .ma-el-team-member-thumb img' => 'top: {{SIZE}}{{UNIT}};'
					]
				]
			);



			$this->add_responsive_control(
				'ma_el_team_carousel_item_gap',
				[
					'label' => __( 'Item Padding', MELA_TD ),
					'type' => Controls_Manager::SLIDER,
					'size_units'    => ['px', '%' ,'em'],
					'condition' => [
						'ma_el_team_carousel_preset' => ['-default','-circle','-content-drawer','-circle-animation']
					],
					'devices' => [ 'desktop', 'tablet', 'mobile' ],
                    'desktop_default' => [
                        'size' => 15,
                        'unit' => 'px',
                    ],
                    'tablet_default' => [
                        'size' => 10,
                        'unit' => 'px',
					],

					'selectors' => [
						'{{WRAPPER}} .ma-el-team-carousel-wrapper .slick-track .ma-el-team-carousel-default-inner,
						{{WRAPPER}} .ma-el-team-carousel-wrapper .slick-track .ma-el-team-carousel-circle-inner,
						{{WRAPPER}} .gridder .gridder-list' => 'padding: {{SIZE}}{{UNIT}};'
					]
				]
			);


			$this->add_control(
				'ma_el_team_carousel_avatar_bg',
				[
					'label' => esc_html__( 'Avatar Background Color', MELA_TD ),
					'type' => Controls_Manager::COLOR,
					'default' => '#826EFF',
					'selectors' => [
						'{{WRAPPER}} .ma-el-team-member-circle .ma-el-team-member-thumb svg.team-avatar-bg' => 'fill: {{VALUE}};',
					],
					'condition' => [
						'ma_el_team_carousel_preset' => '-circle',
					],
				]
			);

			$this->add_control(
				'ma_el_team_carousel_bg',
				[
					'label' => esc_html__( 'Background Color', MELA_TD ),
					'type' => Controls_Manager::COLOR,
					'default' => '#f9f9f9',
					'selectors' => [
						'{{WRAPPER}} .ma-el-team-member-basic,
						{{WRAPPER}} .ma-el-team-member-circle,
						{{WRAPPER}} .ma-el-team-member-social-left,
						{{WRAPPER}} .ma-el-team-member-rounded' => 'background: {{VALUE}};',
						'{{WRAPPER}} .gridder .gridder-show' => 'background-color: {{VALUE}};',
                        '{{WRAPPER}} #animation_svg_04 circle' => 'fill: {{VALUE}}'
					],
				]
			);


			$this->add_control('ma_el_team_carousel_content_align',
				[
					'label'         => __( 'Content Alignment', MELA_TD ),
					'type'          => Controls_Manager::CHOOSE,
					'options'       => [
						'left'      => [
							'title'=> __( 'Left', MELA_TD ),
							'icon' => 'fa fa-align-left',
						],
						'center'    => [
							'title'=> __( 'Center', MELA_TD ),
							'icon' => 'fa fa-align-center',
						],
						'right'     => [
							'title'=> __( 'Right', MELA_TD ),
							'icon' => 'fa fa-align-right',
						],
					],
					'default'       => 'left',
					'selectors'     => [
						'{{WRAPPER}} .ma-el-team-member-content' => 'text-align: {{VALUE}};',
					],
				]
			);

			$this->end_controls_section();


			$this->start_controls_section(
				'section_team_carousel_name',
				[
					'label' => __('Name', MELA_TD),
					'tab' => Controls_Manager::TAB_STYLE,
				]
			);

			$this->add_control(
				'ma_el_title_color',
				[
					'label' => __('Color', MELA_TD),
					'type' => Controls_Manager::COLOR,
					'default' => '#000',
					'selectors' => [
						'{{WRAPPER}} .ma-el-team-member-name' => 'color: {{VALUE}};',
					],
				]
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' => 'title_typography',
					'selector' => '{{WRAPPER}} .ma-el-team-member-name',
				]
			);

			$this->end_controls_section();

			$this->start_controls_section(
				'section_team_member_designation',
				[
					'label' => __('Designation', MELA_TD),
					'tab' => Controls_Manager::TAB_STYLE,
				]
			);

			$this->add_control(
				'ma_el_designation_color',
				[
					'label' => __('Color', MELA_TD),
					'type' => Controls_Manager::COLOR,
					'default' => '#8a8d91',
					'selectors' => [
						'{{WRAPPER}} .ma-el-team-member-designation' => 'color: {{VALUE}};',
					],
				]
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' => 'designation_typography',
					'selector' => '{{WRAPPER}} .ma-el-team-member-designation',
				]
			);

			$this->end_controls_section();

			$this->start_controls_section(
				'section_team_carousel_description',
				[
					'label' => __('Description', MELA_TD),
					'tab' => Controls_Manager::TAB_STYLE,
				]
			);

			$this->add_control(
				'ma_el_description_color',
				[
					'label' => __('Color', MELA_TD),
					'type' => Controls_Manager::COLOR,
					'default' => '#8a8d91',
					'selectors' => [
						'{{WRAPPER}} .ma-el-team-member-about,
						{{WRAPPER}} .gridder-expanded-content p.ma-el-team-member-desc' => 'color: {{VALUE}};',
					],
				]
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' => 'ma_el_description_typography',
					'selector' => '{{WRAPPER}} .ma-el-team-member-about',
				]
			);

			$this->end_controls_section();


			/* Carousel Settings */
			$this->start_controls_section(
				'section_carousel_settings',
				[
					'label' => esc_html__( 'Carousel Settings', MELA_TD ),
				]
			);

			$slides_per_view = range( 1, 6 );
			$slides_per_view = array_combine( $slides_per_view, $slides_per_view );

			$this->add_responsive_control(
				'ma_el_team_per_view',
				[
					'type'           		=> Controls_Manager::SELECT,
					'label'          		=> esc_html__( 'Columns', MELA_TD ),
					'options'        		=> $slides_per_view,
					'default'        		=> '4',
					'tablet_default' 		=> '3',
					'mobile_default' 		=> '2',
					'frontend_available' 	=> true,
				]
			);

			$this->add_responsive_control(
				'ma_el_team_slides_to_scroll',
				[
					'type'      => Controls_Manager::SELECT,
					'label'     => esc_html__( 'Items to Scroll', MELA_TD ),
					'options'   => $slides_per_view,
					'default'   => '1',
				]
			);

			$this->add_control(
				'ma_el_team_carousel_nav',
				[
					'label' => esc_html__( 'Navigation Style', MELA_TD ),
					'type' => Controls_Manager::SELECT,
					'default' => 'arrows',
					'separator' => 'before',
					'options' => [
						'arrows' => esc_html__( 'Arrows', MELA_TD ),
						'dots' => esc_html__( 'Dots', MELA_TD ),

					],
				]
			);


			$this->start_controls_tabs( 'ma_el_team_carousel_navigation_tabs' );

			$this->start_controls_tab( 'ma_el_team_carousel_navigation_control', [ 'label' => esc_html__( 'Normal', MELA_TD
			) ] );

			$this->add_control(
				'ma_el_team_carousel_arrow_color',
				[
					'label' => esc_html__( 'Arrow Background', MELA_TD ),
					'type' => Controls_Manager::COLOR,
					'default' => '#b8bfc7',
					'selectors' => [
						'{{WRAPPER}} .ma-el-team-carousel-prev, {{WRAPPER}} .ma-el-team-carousel-next' => 'background: {{VALUE}};',
					],
					'condition' => [
						'ma_el_team_carousel_nav' => 'arrows',
					],
				]
			);

			$this->add_control(
				'ma_el_team_carousel_dot_color',
				[
					'label' => esc_html__( 'Dot Color', MELA_TD ),
					'type' => Controls_Manager::COLOR,
					'default' => '#8a8d91',
					'selectors' => [
						'{{WRAPPER}} .ma-el-team-carousel-wrapper .slick-dots li button' => 'background-color: {{VALUE}};',
					],
					'condition' => [
						'ma_el_team_carousel_nav' => 'dots',
					],
				]
			);


			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name'        => 'ma_el_team_carousel_border',
					'placeholder' => '1px',
					'default'     => '0px',
					'selector'    => '{{WRAPPER}} .ma-el-team-carousel-prev, {{WRAPPER}} .ma-el-team-carousel-next'
				]
			);


			$this->end_controls_tab();

			$this->start_controls_tab( 'ma_el_team_carousel_social_icon_hover', [ 'label' => esc_html__( 'Hover', MELA_TD )
			] );

			$this->add_control(
				'ma_el_team_carousel_arrow_hover_color',
				[
					'label' => esc_html__( 'Arrow Hover', MELA_TD ),
					'type' => Controls_Manager::COLOR,
					'default' => '#917cff',
					'selectors' => [
						'{{WRAPPER}} .ma-el-team-carousel-prev:hover, {{WRAPPER}} .ma-el-team-carousel-next:hover' =>
							'background: {{VALUE}};',
					],
					'condition' => [
						'ma_el_team_carousel_nav' => 'arrows',
					],
				]
			);

			$this->add_control(
				'ma_el_team_carousel_dot_hover_color',
				[
					'label' => esc_html__( 'Dot Hover', MELA_TD ),
					'type' => Controls_Manager::COLOR,
					'default' => '#8a8d91',
					'selectors' => [
						'{{WRAPPER}} .ma-el-team-carousel-wrapper .slick-dots li.slick-active button, {{WRAPPER}} .ma-el-team-carousel-wrapper .slick-dots li button:hover' => 'background: {{VALUE}};',
					],
					'condition' => [
						'ma_el_team_carousel_nav' => 'dots',
					],
				]
			);


			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name'        => 'ma_el_team_carousel_hover_border',
					'placeholder' => '1px',
					'default'     => '0px',
					'selector'    => '{{WRAPPER}} .ma-el-team-carousel-prev:hover, {{WRAPPER}} .ma-el-team-carousel-next:hover'
				]
			);

			$this->end_controls_tab();

			$this->end_controls_tabs();

			$this->add_control(
				'ma_el_team_transition_duration',
				[
					'label'   => esc_html__( 'Transition Duration', MELA_TD ),
					'type'    => Controls_Manager::NUMBER,
					'default' => 1000,
					'separator' => 'before',
				]
			);

			$this->add_control(
				'ma_el_team_autoplay',
				[
					'label'     => esc_html__( 'Autoplay', MELA_TD ),
					'type'      => Controls_Manager::SWITCHER,
					'default'   => 'no',
				]
			);

			$this->add_control(
				'ma_el_team_autoplay_speed',
				[
					'label'     => esc_html__( 'Autoplay Speed', MELA_TD ),
					'type'      => Controls_Manager::NUMBER,
					'default'   => 5000,
					'condition' => [
						'ma_el_team_autoplay' => 'yes',
					],
				]
			);

			$this->add_control(
				'ma_el_team_loop',
				[
					'label'   => esc_html__( 'Infinite Loop', MELA_TD ),
					'type'    => Controls_Manager::SWITCHER,
					'default' => 'yes',
				]
			);

			$this->add_control(
				'ma_el_team_pause',
				[
					'label'     => esc_html__( 'Pause on Hover', MELA_TD ),
					'type'      => Controls_Manager::SWITCHER,
					'default'   => 'yes',
					'condition' => [
						'ma_el_team_autoplay' => 'yes',
					],
				]
			);

			$this->end_controls_section();



            /**
             * Content Tab: Docs Links
             */
            $this->start_controls_section(
                'jltma_section_help_docs',
                [
                    'label' => esc_html__( 'Help Docs', MELA_TD ),
                ]
            );


            $this->add_control(
                'help_doc_1',
                [
                    'type'            => Controls_Manager::RAW_HTML,
                    'raw'             => sprintf( esc_html__( '%1$s Live Demo %2$s', MELA_TD ), '<a href="https://master-addons.com/demos/team-carousel/" target="_blank" rel="noopener">', '</a>' ),
                    'content_classes' => 'jltma-editor-doc-links',
                ]
            );

            $this->add_control(
                'help_doc_2',
                [
                    'type'            => Controls_Manager::RAW_HTML,
                    'raw'             => sprintf( esc_html__( '%1$s Documentation %2$s', MELA_TD ), '<a href="https://master-addons.com/docs/addons/team-members-carousel/?utm_source=widget&utm_medium=panel&utm_campaign=dashboard" target="_blank" rel="noopener">', '</a>' ),
                    'content_classes' => 'jltma-editor-doc-links',
                ]
            );

            $this->add_control(
                'help_doc_3',
                [
                    'type'            => Controls_Manager::RAW_HTML,
                    'raw'             => sprintf( esc_html__( '%1$s Watch Video Tutorial %2$s', MELA_TD ), '<a href="https://www.youtube.com/watch?v=ubP_h86bP-c" target="_blank" rel="noopener">', '</a>' ),
                    'content_classes' => 'jltma-editor-doc-links',
                ]
            );
            $this->end_controls_section();




			if ( ma_el_fs()->is_not_paying() ) {

				$this->start_controls_section(
					'maad_el_section_pro',
					[
						'label' => esc_html__( 'Upgrade to Pro Version for More Features', MELA_TD )
					]
				);

				$this->add_control(
					'maad_el_control_get_pro',
					[
						'label' => esc_html__( 'Unlock more possibilities', MELA_TD ),
						'type' => Controls_Manager::CHOOSE,
						'options' => [
							'1' => [
								'title' => esc_html__( '', MELA_TD ),
								'icon' => 'fa fa-unlock-alt',
							],
						],
						'default' => '1',
						'description' => '<span class="pro-feature"> Upgrade to  <a href="' . ma_el_fs()->get_upgrade_url() . '" target="_blank">Pro Version</a> for more Elements with Customization Options.</span>'
					]
				);

				$this->end_controls_section();
			}




			$this->start_controls_section(
				'ma_el_team_carousel_social_section',
				[
					'label' => __('Social', MELA_TD),
					'tab' => Controls_Manager::TAB_STYLE,
					'condition' => [
						'ma_el_team_carousel_preset' => ['-social-left', '-default'],
					],
				]
			);

			$this->start_controls_tabs( 'ma_el_team_carousel_social_icons_style_tabs' );

			$this->start_controls_tab( 'ma_el_team_carousel_social_icon_control',
				[ 'label' => esc_html__( 'Normal', MELA_TD ) ]
			);

			$this->add_control(
				'ma_el_team_carousel_social_icon_color',
				[
					'label' => esc_html__( 'Icon Color', MELA_TD ),
					'type' => Controls_Manager::COLOR,
					'default' => '#FFF',
					'selectors' => [
						'{{WRAPPER}} .ma-el-team-member-social li a' => 'color: {{VALUE}};'
					],
				]
			);

			$this->add_control(
				'ma_el_team_carousel_social_color_1',
				[
					'label' => esc_html__( 'Background Color', MELA_TD ),
					'type' => Controls_Manager::COLOR,
					'default' => '#FFF',
					'selectors' => [
						'{{WRAPPER}} .ma-el-team-member-social-left .ma-el-team-member-social li a' => 'background: {{VALUE}};'
					],
				]
			);

			$this->end_controls_tab();

			$this->start_controls_tab( 'ma_el_team_carousel_social_icon_hover_control',
				[ 'label' => esc_html__( 'Hover', MELA_TD ) ]
			);

			$this->add_control(
				'ma_el_team_carousel_social_icon_hover_color',
				[
					'label' => esc_html__( 'Icon Hover Color', MELA_TD ),
					'type' => Controls_Manager::COLOR,
					'default' => '#FFF',
					'selectors' => [
						'{{WRAPPER}} .ma-el-team-member-social li a:hover' => 'color: {{VALUE}};'
					],
				]
			);


			$this->add_control(
				'ma_el_team_carousel_social_hover_bg_color_1',
				[
					'label' => esc_html__( 'Hover Color', MELA_TD ),
					'type' => Controls_Manager::COLOR,
					'default' => '#ff6d55',
					'selectors' => [
						'{{WRAPPER}} .ma-el-team-member-social-left .ma-el-team-member-social li a:hover' => 'background: {{VALUE}};',
					],
				]
			);

			$this->end_controls_tab();

			$this->end_controls_tabs();

			$this->end_controls_section();


			if ( ma_el_fs()->is_not_paying() ) {

				$this->start_controls_section(
					'ma_el_section_pro_style_section',
					[
						'label' => esc_html__( 'Upgrade to Pro Version for More Features', MELA_TD ),
						'tab' => Controls_Manager::TAB_STYLE
					]
				);

				$this->add_control(
					'ma_el_control_get_pro_style_tab',
					[
						'label' => esc_html__( 'Unlock more possibilities', MELA_TD ),
						'type' => Controls_Manager::CHOOSE,
						'options' => [
							'1' => [
								'title' => esc_html__( '', MELA_TD ),
								'icon' => 'fa fa-unlock-alt',
							],
						],
						'default' => '1',
						'description' => '<span class="pro-feature"> Upgrade to  <a href="' . ma_el_fs()->get_upgrade_url() . '" target="_blank">Pro Version</a> for more Elements with
Customization Options.</span>'
					]
				);

				$this->end_controls_section();
			}


		}

		protected function render() {
			$settings = $this->get_settings_for_display();

			$team_carousel_classes = $this->get_settings_for_display('ma_el_team_carousel_image_rounded');

			$team_preset = $settings['ma_el_team_carousel_preset'];

			$this->add_render_attribute(
				'ma_el_team_carousel',
				[
					'class' => [
								'ma-el-team-members-slider-section',
								'ma-el-team-carousel-wrapper',
								'ma-el-team-carousel' . $team_preset
							],
					'data-team-preset' 		=> $team_preset,
					'data-carousel-nav' 	=> $settings['ma_el_team_carousel_nav'],
					'data-slidestoshow' 	=> $settings['ma_el_team_per_view'],
					'data-slidestoscroll' 	=> $settings['ma_el_team_slides_to_scroll'],
					'data-speed' 			=> $settings['ma_el_team_transition_duration'],
				]
			);


			$this->add_render_attribute(
				'ma_el_team_slider_section',
				[
					'class' => 'ma-el-team-members-slider-section',
					'data-team-preset' => $team_preset,
				]
			);

			if ( $settings['ma_el_team_autoplay'] == 'yes' ) {
				$this->add_render_attribute( 'ma_el_team_carousel', 'data-autoplay', "true");
				$this->add_render_attribute( 'ma_el_team_carousel', 'data-autoplayspeed', $settings['ma_el_team_autoplay_speed'] );
			}

			if ( $settings['ma_el_team_pause'] == 'yes' ) {
				$this->add_render_attribute( 'ma_el_team_carousel', 'data-pauseonhover', "true" );
			}

			if ( $settings['ma_el_team_loop'] == 'yes' ) {
				$this->add_render_attribute( 'ma_el_team_carousel', 'data-loop', "true");
			}
			?>



			<?php if( $team_preset == '-content-drawer' ) { ?>

                <div <?php echo $this->get_render_attribute_string( 'ma_el_team_slider_section' ); ?>>
                <!-- Gridder navigation -->
                <ul class="gridder">

					<?php foreach ( $settings['team_carousel_repeater'] as $key => $member ) {

						$team_carousel_image = $member['ma_el_team_carousel_image'];
						$team_carousel_image_url = Group_Control_Image_Size::get_attachment_image_src( $team_carousel_image['id'], 'thumbnail', $member );
						if( empty( $team_carousel_image_url ) ) :
							$team_carousel_image_url = $team_carousel_image['url'];
						else:
							$team_carousel_image_url = $team_carousel_image_url;
						endif;
						?>

                        <li class="gridder-list" data-griddercontent="#ma-el-team<?php echo $key+1;?>">
                            <img src="<?php echo esc_url($team_carousel_image_url); ?>" class="circled"
                                 alt="<?php echo $member['ma_el_team_carousel_name']; ?>">
                            <div class="ma-team-drawer-hover-content">

							<?php echo $settings['title_html_tag']; ?>

                                <<?php echo $settings['title_html_tag']; ?> class="ma-el-team-member-name">
                                    <?php echo $member['ma_el_team_carousel_name'];?>
								</<?php echo $settings['title_html_tag']; ?>>

                                <span class="ma-el-team-member-designation">
                                    <?php echo $member['ma_el_team_carousel_designation']; ?>
                                </span>
                            </div>
                        </li>

					<?php } ?>
                </ul>

                <!-- Gridder content -->
				<?php foreach ( $settings['team_carousel_repeater'] as $key => $member ) { ?>

                    <div id="ma-el-team<?php echo $key+1;?>" class="gridder-content">
                        <div class="content-left">
                            <span class="ma-el-team-member-designation"><?php echo $member['ma_el_team_carousel_designation']; ?></span>
                            <<?php echo $settings['title_html_tag']; ?> class="ma-el-team-member-name">
								<?php echo $member['ma_el_team_carousel_name'];?>
							</<?php echo $settings['title_html_tag']; ?>>
							<p class="ma-el-team-member-desc">
                                <?php echo $this->parse_text_editor( $member['ma_el_team_carousel_description'] ); ?>
                            </p>
                        </div>

                        <div class="content-right">
							<?php if ( $member['ma_el_team_carousel_enable_social_profiles'] == 'yes' ): ?>
                                <ul class="list-inline ma-el-team-member-social">

									<?php if ( ! empty( $member['ma_el_team_carousel_facebook_link']['url'] ) ) : ?>
										<?php $target = $member['ma_el_team_carousel_facebook_link']['is_external'] ? ' target="_blank"' : ''; ?>
                                        <li>
                                            <a href="<?php echo esc_url( $member['ma_el_team_carousel_facebook_link']['url'] ); ?>"<?php echo $target; ?>><i class="fa fa-facebook"></i></a>
                                        </li>
									<?php endif; ?>

									<?php if ( ! empty( $member['ma_el_team_carousel_twitter_link']['url'] ) ) : ?>
										<?php $target = $member['ma_el_team_carousel_twitter_link']['is_external'] ? ' target="_blank"' : ''; ?>
                                        <li>
                                            <a href="<?php echo esc_url( $member['ma_el_team_carousel_twitter_link']['url'] ); ?>"<?php echo $target; ?>><i class="fa fa-twitter"></i></a>
                                        </li>
									<?php endif; ?>

									<?php if ( ! empty( $member['ma_el_team_carousel_instagram_link']['url'] ) ) : ?>
										<?php $target = $member['ma_el_team_carousel_instagram_link']['is_external'] ?
											' target="_blank"' : ''; ?>
                                        <li>
                                            <a href="<?php echo esc_url(
												$member['ma_el_team_carousel_instagram_link']['url'] ); ?>"<?php echo $target; ?>><i class="fa fa-instagram"></i></a>
                                        </li>
									<?php endif; ?>

									<?php if ( ! empty( $member['ma_el_team_carousel_linkedin_link']['url'] ) ) : ?>
										<?php $target = $member['ma_el_team_carousel_linkedin_link']['is_external'] ? ' target="_blank"' : ''; ?>
                                        <li>
                                            <a href="<?php echo esc_url( $member['ma_el_team_carousel_linkedin_link']['url'] ); ?>"<?php echo $target; ?>><i class="fa fa-linkedin"></i></a>
                                        </li>
									<?php endif; ?>

									<?php if ( ! empty( $member['ma_el_team_carousel_dribbble_link']['url'] ) ) : ?>
										<?php $target = $member['ma_el_team_carousel_dribbble_link']['is_external'] ? ' target="_blank"' : ''; ?>
                                        <li>
                                            <a href="<?php echo esc_url( $member['ma_el_team_carousel_dribbble_link']['url'] ); ?>"<?php echo $target; ?>><i class="fa fa-dribbble"></i></a>
                                        </li>
									<?php endif; ?>

                                </ul>
							<?php endif; ?>
                        </div>
                    </div>
				<?php } ?>

                </div>

			<?php } else { ?>


                <div <?php echo $this->get_render_attribute_string( 'ma_el_team_carousel' ); ?>>

                        <?php foreach ( $settings['team_carousel_repeater'] as $key => $member ) :

                            $team_carousel_image = $member['ma_el_team_carousel_image'];
                            $team_carousel_image_url = Group_Control_Image_Size::get_attachment_image_src( $team_carousel_image['id'], 'thumbnail', $member );
                            if( empty( $team_carousel_image_url ) ) : $team_carousel_image_url = $team_carousel_image['url']; else: $team_carousel_image_url = $team_carousel_image_url; endif;
                            ?>

                            <div class="ma-el-team-carousel<?php echo $team_preset; ?>-inner">
                                <div class="ma-el-team-member<?php echo $team_preset; ?> text-center">
                                    <div class="ma-el-team-member-thumb">
                                        <?php
//                                            if( $team_preset == '-circle' && isset( $settings['ma_el_team_circle_image'] ) && !isset( $settings['ma_el_team_circle_image_animation'] )) {
                                            if( $team_preset == '-circle' && isset( $settings['ma_el_team_circle_image'] )) {
                                                $file_path =  MELA_PLUGIN_PATH . '/assets/images/circlesvg/' . $settings['ma_el_team_circle_image'] . '.svg';
                                                echo file_get_contents($file_path);
	                                        	echo '<img src="' . esc_url($team_carousel_image_url) .'" class="circled" alt="' . $member['ma_el_team_carousel_name'] .'">';

                                            } elseif ( $team_preset == '-circle-animation' && isset( $settings['ma_el_team_circle_image_animation'] )) {

	                                            if($settings['ma_el_team_circle_image_animation'] == "animation_svg_02"){

	                                                echo '<div class="animation_svg_02"><img src="' . esc_url($team_carousel_image_url) .'" class="circled" alt="' . $member['ma_el_team_carousel_name'] .'"></div>';

                                                } elseif ($settings['ma_el_team_circle_image_animation'] == "animation_svg_03"){

	                                                echo '<div class="animation_svg_03"></div><div class="animation_svg_03"></div><div class="animation_svg_03"></div><div class="animation_svg_03_center"><img src="' . esc_url($team_carousel_image_url) .'" class="circled" alt="' . $member['ma_el_team_carousel_name'] .'"></div>';

                                                }  else{

		                                            $file_path =  MELA_PLUGIN_PATH . '/assets/images/animation/' .
		                                                          $settings['ma_el_team_circle_image_animation'] . '.svg';
		                                            echo file_get_contents($file_path);
		                                            echo '<img src="' . esc_url($team_carousel_image_url) .'" class="circled" alt="' . $member['ma_el_team_carousel_name'] .'">';
                                                }


                                            }else{

                                                echo '<img src="' . esc_url($team_carousel_image_url) .'" class="circled" alt="' . $member['ma_el_team_carousel_name'] .'">';

                                            } ?>

                                    </div>
                                    <div class="ma-el-team-member-content">
										<<?php echo $settings['title_html_tag']; ?> class="ma-el-team-member-name">
											<?php echo $member['ma_el_team_carousel_name'];
											?>
										</<?php echo $settings['title_html_tag']; ?>>
                                        <span class="ma-el-team-member-designation"><?php echo $member['ma_el_team_carousel_designation']; ?></span>
                                        <p class="ma-el-team-member-about">
                                            <?php echo $member['ma_el_team_carousel_description']; ?>
                                        </p>
                                        <?php if ( $member['ma_el_team_carousel_enable_social_profiles'] == 'yes' ): ?>
                                            <ul class="list-inline ma-el-team-member-social">

                                                <?php if ( ! empty( $member['ma_el_team_carousel_facebook_link']['url'] ) ) : ?>
                                                    <?php $target = $member['ma_el_team_carousel_facebook_link']['is_external'] ? ' target="_blank"' : ''; ?>
                                                    <li>
                                                        <a href="<?php echo esc_url( $member['ma_el_team_carousel_facebook_link']['url'] ); ?>"<?php echo $target; ?>><i class="fa fa-facebook"></i></a>
                                                    </li>
                                                <?php endif; ?>

                                                <?php if ( ! empty( $member['ma_el_team_carousel_twitter_link']['url'] ) ) : ?>
                                                    <?php $target = $member['ma_el_team_carousel_twitter_link']['is_external'] ? ' target="_blank"' : ''; ?>
                                                    <li>
                                                        <a href="<?php echo esc_url( $member['ma_el_team_carousel_twitter_link']['url'] ); ?>"<?php echo $target; ?>><i class="fa fa-twitter"></i></a>
                                                    </li>
                                                <?php endif; ?>

                                                <?php if ( ! empty( $member['ma_el_team_carousel_instagram_link']['url'] ) ) : ?>
                                                    <?php $target = $member['ma_el_team_carousel_instagram_link']['is_external'] ?
                                                        ' target="_blank"' : ''; ?>
                                                    <li>
                                                        <a href="<?php echo esc_url(
                                                            $member['ma_el_team_carousel_instagram_link']['url'] ); ?>"<?php echo $target; ?>><i class="fa fa-instagram"></i></a>
                                                    </li>
                                                <?php endif; ?>

                                                <?php if ( ! empty( $member['ma_el_team_carousel_linkedin_link']['url'] ) ) : ?>
                                                    <?php $target = $member['ma_el_team_carousel_linkedin_link']['is_external'] ? ' target="_blank"' : ''; ?>
                                                    <li>
                                                        <a href="<?php echo esc_url( $member['ma_el_team_carousel_linkedin_link']['url'] ); ?>"<?php echo $target; ?>><i class="fa fa-linkedin"></i></a>
                                                    </li>
                                                <?php endif; ?>

                                                <?php if ( ! empty( $member['ma_el_team_carousel_dribbble_link']['url'] ) ) : ?>
                                                    <?php $target = $member['ma_el_team_carousel_dribbble_link']['is_external'] ? ' target="_blank"' : ''; ?>
                                                    <li>
                                                        <a href="<?php echo esc_url( $member['ma_el_team_carousel_dribbble_link']['url'] ); ?>"<?php echo $target; ?>><i class="fa fa-dribbble"></i></a>
                                                    </li>
                                                <?php endif; ?>

                                            </ul>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                </div>

			<?php } ?>


			<?php
		}

	}

	Plugin::instance()->widgets_manager->register_widget_type( new Master_Addons_Team_Members_Carousel() );