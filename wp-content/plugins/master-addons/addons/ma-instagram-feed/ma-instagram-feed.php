<?php
    namespace Elementor;

    // Elementor Classes
    use \Elementor\Controls_Manager;
    use \Elementor\Group_Control_Border;
    use \Elementor\Group_Control_Box_Shadow;
    use \Elementor\Group_Control_Typography;
    use \Elementor\Scheme_Typography;
    use \Elementor\Widget_Base;

    use MasterAddons\Inc\Helper\Master_Addons_Helper;
	/**
	 * Author Name: Liton Arefin
	 * Author URL: https://jeweltheme.com
	 * Date: 29/5/20
	 */

    if ( ! defined( 'ABSPATH' ) ) exit; // If this file is called directly, abort.


    class Master_Addons_Instagram_Feed extends Widget_Base{

		public function get_name(){
			return "jltma-instagram-feed";
		}

		public function get_title(){
            return esc_html__( 'MA Instagram Feed', MELA_TD );
		}

		public function get_icon() {
			return 'ma-el-icon eicon-instagram-gallery';
		}

		public function get_categories() {
			return [ 'master-addons' ];
		}

		public function get_keywords() {
			return [
                'instagram',
                'instagram feed',
                'ma instagram feed',
                'instagram gallery',
                'ma instagram gallery',
                'social media',
                'social feed',
                'ma social feed',
                'instagram embed',
                'ma',
                'master addons',
                'instagram post',
                'instagram like',
                'instagram comments',
                'instagram video',
            ];
		}

        public function get_help_url(){
            return 'https://master-addons.com/demos/instagram-feed';
        }


        public function get_style_depends(){
            return [
                'fancybox',
                'font-awesome-5-all',
                'font-awesome-4-shim'
            ];
        }

        public function get_script_depends() {
            return [
                'jquery-slick',
                'isotope',
                'fancybox',
                'imagesloaded',
                'font-awesome-4-shim',
                'elementor-waypoints',
				'master-addons-scripts'
            ];
        }

		protected function _register_controls() {

			//Instagram Settings
			$this->start_controls_section(
				'jltma_instafeed_display',
				[
					'label' => esc_html__( 'Instagram Account Settings', MELA_TD ),
				]
			);

            $this->add_control(
                'jltma_instafeed_access_token',
                [
                    'label' => esc_html__('Access Token', MELA_TD),
                    'type' => Controls_Manager::TEXT,
                    'label_block' => true,
                    'default' => esc_html__('36409282899.8f4c5bf.2b5a056d124f4b83aa8bd90824d229d9', MELA_TD),
                    'description' => '<a href="https://www.jetseotools.com/instagram-access-token/" class="jtlma-btn" target="_blank">Get Access Token</a>', MELA_TD,
                ]
            );

			// $this->add_control(
			// 	'jltma_instafeed_show_by',
			// 	[
			// 		'label'     => esc_html__( 'Show Feed by', MELA_TD ),
			// 		'type'      => Controls_Manager::SELECT,
			// 		'default'   => 'username',
			// 		'options'   => [
			// 			'username'          => esc_html__( 'Username(Default)', MELA_TD ),
			// 			'feed_tags'         => esc_html__( 'Tags', MELA_TD )
			// 		]
			// 	]
            // );


            // $this->add_control(
            //     'jltma_instafeed_tags',
            //     [
            //         'label' => esc_html__('Tag', MELA_TD),
            //         'type'                  => Controls_Manager::TEXT,
            //         'placeholder'           => 'master-addons',
            //         'condition'             => [
            //                 'jltma_instafeed_show_by' => 'feed_tags'
            //         ],
            //     ]
            // );


            $this->end_controls_section();


            // Feed Settings
            $this->start_controls_section(
                'jltma_instafeed_settings_feed_section',
                [
                    'label' => esc_html__('Feed Settings', MELA_TD),
                ]
            );


            $this->add_responsive_control(
                'jltma_instafeed_image_count',
                [
                    'label'                 => esc_html__('Show Items', MELA_TD),
                    'type'                  => Controls_Manager::SLIDER,
                    'default'               => [ 'size' => 8 ],
                    'range'                 => [
                        'px' => [
                            'min'   => 1,
                            'max'   => 100,
                            'step'  => 1,
                        ],
                    ],
                    'size_units'            => '',
                ]
            );

            $this->add_control(
                'jltma_instafeed_sort_by',
                [
                    'label' => esc_html__('Sort By', MELA_TD),
                    'type' => Controls_Manager::SELECT,
                    'default' => 'recent-posts',
                    'options' => [
                        'recent-posts' => esc_html__('Recent Posts', MELA_TD),
                        'old-posts' => esc_html__('Old Posts', MELA_TD),
                        'most-liked' => esc_html__('Most Likes', MELA_TD),
                        'less-liked' => esc_html__('Less Likes', MELA_TD),
                        'most-commented' => esc_html__('Most Commented', MELA_TD),
                        'less-commented' => esc_html__('Less Commented', MELA_TD),
                    ],
                ]
            );

            $this->add_control(
                'jltma_instafeed_image_size',
                [
                    'label' => esc_html__('Image Size', MELA_TD),
                    'type' => Controls_Manager::SELECT,
                    'label_block' => false,
                    'default' => 'low_resolution',
                    'options' => [
                        'thumbnail' => esc_html__('Thumbnail (150x150)', MELA_TD),
                        'low_resolution' => esc_html__('Low Resolution (320x320)', MELA_TD),
                        'standard_resolution' => esc_html__('Standard Resolution (640x640)', MELA_TD),
                    ],
                    'style_transfer' => true,
                ]
            );


            $this->add_control(
                'jltma_instafeed_force_square',
                [
                    'label' => esc_html__('Force Square Image?', MELA_TD),
                    'type' => Controls_Manager::SWITCHER,
                    'return_value' => 'yes',
                    'default' => '',
                ]
            );

            $this->add_responsive_control(
                'jltma_instafeed_sq_image_size',
                [
                    'label' => esc_html__('Image Dimension (px)', MELA_TD),
                    'type' => Controls_Manager::SLIDER,
                    'default' => [
                        'size' => 300,
                    ],
                    'range' => [
                        'px' => [
                            'min' => 1,
                            'max' => 1000,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .jltma-instafeed-square-img .jltma-instafeed-item .jltma-instafeed-img,
                        {{WRAPPER}} .jltma-instafeed-square-img .jltma-instafeed-item .jltma-instafeed-card-img' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; object-fit: cover;',
                    ],
                    'condition' => [
                        'jltma_instafeed_force_square' => 'yes',
                    ],
                ]
            );

            $this->end_controls_section();



            /**
             * Content Tab: Display Settings
             */
            $this->start_controls_section(
                'jltma_instafeed_section_display_settings',
                [
                    'label'                 => esc_html__( 'Display Settings', MELA_TD ),
                ]
            );

            $this->add_control(
                'jltma_instafeed_layout',
                [
                    'label'                 => esc_html__( 'Layout', MELA_TD ),
                    'type'                  => Controls_Manager::SELECT,
                    'default'               => 'grid',
                    'options'               => [
                       'grid'           => esc_html__( 'Grid', MELA_TD ),
                       'card'           => esc_html__( 'Card Style', MELA_TD ),
                       'masonry'		=> esc_html__( 'Masonry', MELA_TD ),
                       'carousel'       => esc_html__( 'Carousel', MELA_TD ),
                    ],
                    'frontend_available'    => true,
                ]
            );

            $this->add_responsive_control(
                'jltma_instafeed_cols',
                [
                    'label'                 => esc_html__( 'Columns', MELA_TD ),
                    'type'                  => Controls_Manager::SELECT,
                    'label_block'           => false,
                    'default'               => '4',
                    'tablet_default'        => '3',
                    'mobile_default'        => '2',
                    'options'               => [
                        '1'              => esc_html__( '1 Column', MELA_TD ),
                        '2'              => esc_html__( '2 Columns', MELA_TD ),
                        '3'              => esc_html__( '3 Columns', MELA_TD ),
                        '4'              => esc_html__( '4 Columns', MELA_TD ),
                        '5'              => esc_html__( '5 Columns', MELA_TD ),
                        '6'              => esc_html__( '6 Columns', MELA_TD ),
                        '7'              => esc_html__( '7 Columns', MELA_TD ),
                        '8'              => esc_html__( '8 Columns', MELA_TD )
                    ],
                    'selectors'             => [
                        '{{WRAPPER}} .jltma-instagram-feed .jltma-instafeed-item' => 'width: calc( 100% / {{VALUE}} )',
                    ],
                    'condition'             => [
                        'jltma_instafeed_layout'       => ['grid', 'card', 'masonry']
                    ],
                ]
            );


            $this->add_control(
                'jltma_instafeed_view_style',
                [
                    'label' => esc_html__('Hover Style', MELA_TD),
                    'type' => Controls_Manager::SELECT,
                    'label_block' => false,
                    'default' => 'hover-info',
                    'options' => [
                        'hover-info'        => esc_html__('Default Hover', MELA_TD),
                        'btm-push-hover'    => esc_html__('Bottom Push Hover', MELA_TD)
                    ],
                    'style_transfer' => true,
                ]
            );

            $this->add_control(
                'jltma_instafeed_pagin_heading',
                [
                    'label' => esc_html__('Pagination', MELA_TD),
                    'type' => Controls_Manager::HEADING,
                    'separator'             => 'before',
                ]
            );

            // $this->add_control(
            //     'jltma_instafeed_load_more',
            //     [
            //         'label' => esc_html__('Show Load More?', MELA_TD),
            //         'type' => Controls_Manager::SWITCHER,
            //         'return_value' => 'yes',
            //         'default' => '',
            //         'style_transfer' => true,
            //     ]
            // );

            // $this->add_control(
            //     'jltma_instafeed_load_more_text',
            //     [
            //         'label' => esc_html__('Load More Text', MELA_TD),
            //         'type' => Controls_Manager::TEXT,
            //         'default' => esc_html__('Load More', MELA_TD),
            //         'condition' => [
            //             'jltma_instafeed_load_more' => 'yes',
            //         ],
            //     ]
            // );

            $this->add_control(
                'jltma_instafeed_caption_heading',
                [
                    'label' => __('Other Settings', MELA_TD),
                    'type' => Controls_Manager::HEADING,
                    'separator'             => 'before',
                ]
            );

            $this->add_control(
                'jltma_instafeed_show_likes',
                [
                    'label' => esc_html__('Show Like?', MELA_TD),
                    'type' => Controls_Manager::SWITCHER,
                    'return_value' => 'yes',
                    'default' => 'yes',
                    'style_transfer' => true,
                ]
            );

            $this->add_control(
                'jltma_instafeed_date',
                [
                    'label' => esc_html__('Display Date', MELA_TD),
                    'type' => Controls_Manager::SWITCHER,
                    'return_value' => 'yes',
                    'default' => 'yes',
                ]
            );

            $this->add_control(
                'jltma_instafeed_show_comments',
                [
                    'label' => esc_html__('Show Comments?', MELA_TD),
                    'type' => Controls_Manager::SWITCHER,
                    'return_value' => 'yes',
                    'default' => 'yes',
                    'style_transfer' => true,
                ]
            );

            $this->add_control(
                'jltma_instafeed_show_caption',
                [
                    'label' => esc_html__('Show Caption?', MELA_TD),
                    'type' => Controls_Manager::SWITCHER,
                    'return_value' => 'yes',
                    'default' => 'yes',
                    'condition' => [
                        'jltma_instafeed_view_style!' => 'btm-push-hover',
                    ],
                    'style_transfer' => true,
                ]
            );



            $this->add_control(
                'jltma_instafeed_user_info',
                [
                    'label' => esc_html__( 'User Info', MELA_TD ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                    'condition' => [
                        'jltma_instafeed_layout' => ['card'],
                    ],
                ]
            );
            $this->add_control(
                'jltma_instafeed_show_user_picture',
                [
                    'label' => esc_html__('Show Profile Picture?', MELA_TD),
                    'type' => Controls_Manager::SWITCHER,
                    'return_value' => 'yes',
                    'default' => 'yes',
                    'condition' => [
                        'jltma_instafeed_layout' => ['card'],
                    ],
                    'style_transfer' => true,
                ]
            );

            $this->add_control(
                'jltma_instafeed_show_username',
                [
                    'label' => esc_html__('Show Username?', MELA_TD),
                    'type' => Controls_Manager::SWITCHER,
                    'return_value' => 'yes',
                    'default' => 'yes',
                    'condition' => [
                        'jltma_instafeed_layout' => ['card'],
                    ],
                    'style_transfer' => true,
                ]
            );

            $this->add_control(
                'jltma_instafeed_show_insta_icon',
                [
                    'label' => esc_html__('Show Instagram Icon?', MELA_TD),
                    'type' => Controls_Manager::SWITCHER,
                    'return_value' => 'yes',
                    'default' => 'yes',
                    'condition' => [
                        'jltma_instafeed_layout' => ['card'],
                    ],
                    'style_transfer' => true,
                ]
            );

            $this->add_control(
                'jltma_instafeed_show_user_postdate',
                [
                    'label' => esc_html__('Show Post Date?', MELA_TD),
                    'type' => Controls_Manager::SWITCHER,
                    'return_value' => 'yes',
                    'default' => 'yes',
                    'condition' => [
                        'jltma_instafeed_layout' => ['card'],
                    ],
                    'style_transfer' => true,
                ]
            );



            $this->add_control(
                'jltma_instafeed_show_lightbox',
                [
                    'label' => esc_html__('Show Lightbox?', MELA_TD),
                    'type' => Controls_Manager::SWITCHER,
                    'return_value' => 'yes',
                    'default' => 'yes',
                    // 'condition' => [
                    //     'jltma_instafeed_layout' => ['card','grid','masonry','carousel'],
                    // ],
                    'style_transfer' => true,
                ]
            );

            $this->add_control(
                'jltma_instafeed_show_link',
                [
                    'label' => esc_html__('Image Link?', MELA_TD),
                    'type' => Controls_Manager::SWITCHER,
                    'return_value' => 'yes',
                    'default' => 'No',
                    'condition' => [
                        'jltma_instafeed_show_lightbox!' => 'yes'
                    ],
                    'style_transfer' => true,
                ]
            );

            $this->add_control(
                'jltma_instafeed_link_target',
                [
                    'label' => esc_html__('Open in new tab?', MELA_TD),
                    'type' => Controls_Manager::SWITCHER,
                    'return_value' => '_blank',
                    'default' => '_blank',
                    'condition' => [
                        'jltma_instafeed_show_link' => 'yes',
                        'jltma_instafeed_show_lightbox!' => 'yes',
                    ],
                ]
            );

            $this->add_control(
                'jltma_instafeed_profile_link',
                [
                    'label'                 => esc_html__( 'Show Link to Instagram Profile?', MELA_TD ),
                    'type'                  => Controls_Manager::SWITCHER,
                    'default'               => 'no',
                    'label_on'              => esc_html__( 'Yes', MELA_TD ),
                    'label_off'             => esc_html__( 'No', MELA_TD ),
                    'return_value'          => 'yes',
                    'separator'             => 'before',
                ]
            );

            $this->add_control(
                'jltma_instafeed_link_title',
                [
                    'label'                 => esc_html__( 'Link Title', MELA_TD ),
                    'type'                  => Controls_Manager::TEXT,
                    'default'               => esc_html__( 'Follow Us @ Instagram', MELA_TD ),
                    'condition'             => [
                        'jltma_instafeed_profile_link' => 'yes',
                    ],
                ]
            );

            $this->add_control(
                'jltma_instafeed_insta_profile_url',
                [
                    'label'                 => esc_html__( 'Instagram Profile URL', MELA_TD ),
                    'type'                  => Controls_Manager::URL,
                    'placeholder'           => 'https://instagram.com/master-addons',
                    'default'               => [
                        'url'           => '#',
                    ],
                    'condition'             => [
                        'jltma_instafeed_profile_link' => 'yes',
                    ],
                ]
            );

            $this->add_control(
                'jltma_instafeed_title_icon',
                [
                    'label'					=> esc_html__( 'Title Icon', MELA_TD ),
                    'type'					=> Controls_Manager::ICONS,
                    'fa4compatibility'		=> 'jltma_instafeed_insta_title_icon',
                    'recommended'			=> [
                        'fa-brands' => [
                            'instagram',
                        ],
                        'fa-solid' => [
                            'user-check',
                            'user-plus',
                        ],
                    ],
                    'default'				=> [
                        'value' => 'fab fa-instagram',
                        'library' => 'fa-brands',
                    ],
                    'condition'             => [
                        'jltma_instafeed_profile_link' => 'yes',
                    ],
                ]
            );

            $this->add_control(
                'jltma_instafeed_title_icon_position',
                [
                    'label'                 => esc_html__( 'Icon Position', MELA_TD ),
                    'type'                  => Controls_Manager::SELECT,
                    'options'               => [
                       'before_title'   => esc_html__( 'Before Title', MELA_TD ),
                       'after_title'    => esc_html__( 'After Title', MELA_TD ),
                    ],
                    'default'               => 'before_title',
                    'condition'             => [
                        'jltma_instafeed_profile_link' => 'yes',
                    ],
                ]
            );

            $this->end_controls_section();





            /**
            * Content Tab: Lightbox Settings
            */
            $this->start_controls_section(
                'jltma_instafeed_lightbox_section_settings',
                [
                    'label'                 => esc_html__( 'Lightbox Settings', MELA_TD ),
                    'condition'             => [
                        'jltma_instafeed_show_lightbox'   => 'yes',
                    ],
                ]
            );

            $this->add_control(
                'jltma_instafeed_lightbox_type',
                [
                    'label'                 => esc_html__( 'Lightbox Type', MELA_TD ),
                    'type'                  => Controls_Manager::SELECT,
                    'options'               => [
                       'images'        => esc_html__( 'Gallery', MELA_TD ),
                       'inline'         => esc_html__( 'Inline', MELA_TD ),
                    ],
                    'default'               => 'images',
                    // 'condition'             => [
                    //     'jltma_instafeed_profile_link' => 'yes',
                    // ],
                ]
            );


            $this->add_control(
                'jltma_instafeed_lightbox_caption',
                [
                    'label'                 => esc_html__( 'Show Caption?', MELA_TD ),
                    'type'                  => Controls_Manager::SWITCHER,
                    'default'               => 'yes',
                    'label_on'              => esc_html__( 'Show', MELA_TD ),
                    'label_off'             => esc_html__( 'Hide', MELA_TD ),
                    'return_value'          => 'yes',
                    'separator'             => 'before',
                    'condition'             => [
                        'jltma_instafeed_lightbox_type' => 'images',
                    ],
                ]
            );


            $this->add_control(
                'jltma_instafeed_lightbox_protect',
                [
                    'label'                 => esc_html__( 'Protect Download', MELA_TD ),
                    'type'                  => Controls_Manager::SWITCHER,
                    'default'               => 'yes',
                    'label_on'              => esc_html__( 'Show', MELA_TD ),
                    'label_off'             => esc_html__( 'Hide', MELA_TD ),
                    'return_value'          => 'yes',
                    'separator'             => 'before',
                    'condition'             => [
                        'jltma_instafeed_lightbox_type' => 'images',
                    ],
                ]
            );

            $this->add_control(
                'jltma_instafeed_lightbox_transition_effect',
                [
                    'label'                 => esc_html__( 'Transition Effect', MELA_TD ),
                    'description'           => esc_html__( 'Transition effect between Slides', MELA_TD ),
                    'type'                  => Controls_Manager::SELECT,
                    'options'               => [
                            'false'               => esc_html__( 'Disable', MELA_TD ),
                            'fade'                => esc_html__( 'Fade', MELA_TD ),
                            'slide'               => esc_html__( 'Slide', MELA_TD ),
                            'circular'            => esc_html__( 'Circular', MELA_TD ),
                            'tube'                => esc_html__( 'Tube', MELA_TD ),
                            'tube'                => esc_html__( 'Tube', MELA_TD ),
                            'zoom-in-out'         => esc_html__( 'Zoom in Out', MELA_TD ),
                            'rotate'              => esc_html__( 'Rotate', MELA_TD )
                    ],
                    'default'               => 'fade'
                ]
            );

            $this->add_control(
                'jltma_instafeed_lightbox_animation_effect',
                [
                    'label'                 => esc_html__( 'Animation Effect', MELA_TD ),
                    'type'                  => Controls_Manager::SELECT,
                    'description'           => esc_html__( 'Open/Close animation effect', MELA_TD ),
                    'options'               => [
                        'false'              => esc_html__( 'Disable', MELA_TD ),
                        'fade'               => esc_html__( 'Fade', MELA_TD ),
                        'zoom'               => esc_html__( 'Zoom', MELA_TD ),
                        'zoom-in-out'        => esc_html__( 'Zoom in Out', MELA_TD ),
                    ],
                    'default'               => 'fade'
                ]
            );

            $this->add_control(
                'jltma_instafeed_lightbox_buttons',
                [
                    'label'                 => esc_html__( 'Buttons', MELA_TD ),
                    'type'                  => Controls_Manager::SELECT2,
                    'multiple'              => true,
                    'options'               => [
                        'zoom'              => esc_html__( 'Zoom', MELA_TD ),
                        'share'             => esc_html__( 'Share', MELA_TD ),
                        'slideShow'         => esc_html__( 'SlideShow', MELA_TD ),
                        'fullScreen'        => esc_html__( 'Full Screen', MELA_TD ),
                        'download'          => esc_html__( 'Download', MELA_TD ),
                        'thumbs'            => esc_html__( 'Thumbs', MELA_TD ),
                        'close'             => esc_html__( 'Close', MELA_TD ),
                    ],
                    'default' => [
                            'zoom',
                            'fullScreen',
                            'close'
                        ],
                    'condition'             => [
                        'jltma_instafeed_lightbox_type' => 'images',
                    ],
                ]
            );

            $this->add_control(
                'jltma_instafeed_lightbox_width',
                [
                    'label'                 => esc_html__( 'Width', MELA_TD ),
                    'type'                  => Controls_Manager::TEXT,
                    'default'               => '800',
                    'title'                 => esc_html__( '800', MELA_TD ),
                ]
            );
            $this->add_control(
                'jltma_instafeed_lightbox_hieght',
                [
                    'label'                 => esc_html__( 'Height', MELA_TD ),
                    'type'                  => Controls_Manager::TEXT,
                    'default'               => '600',
                    'title'                 => esc_html__( '600', MELA_TD ),
                ]
            );

            $this->end_controls_section();





            /**
            * Content Tab: Carousel Settings
            */
            $this->start_controls_section(
                'jltma_instafeed_carousel_section_settings',
                [
                    'label'                 => esc_html__( 'Carousel Settings', MELA_TD ),
                    'condition'             => [
                        'jltma_instafeed_layout'   => 'carousel',
                    ],
                ]
            );

            $this->add_control(
                'jltma_instafeed_carousel_itmes_to_scroll',
                [
                    'type'                  => Controls_Manager::SLIDER,
                    'label'                 => esc_html__( 'Slides to Scroll', MELA_TD ),
                    'default'               => [ 'size' => 1 ],
                    'range'                 => [
                        'px' => [
                            'min'   => 1,
                            'max'   => 10,
                            'step'  => 1,
                        ],
                    ],
                    'size_units'            => '',
                    'condition'             => [
                        'jltma_instafeed_layout' => 'carousel',
                    ],
                ]
            );


            $this->add_control(
                'jltma_instafeed_carousel_navigation_heading',
                [
                    'label'                 => esc_html__( 'Navigation', MELA_TD ),
                    'type'                  => Controls_Manager::HEADING,
                    'separator'             => 'before',
                    'condition' => [
                        'jltma_instafeed_layout' => 'carousel',
                    ],
                ]
            );


            $this->add_control(
                'jltma_instafeed_carousel_nav',
                [
                    'label'         => esc_html__( 'Navigation Style', MELA_TD ),
                    'type'          => Controls_Manager::SELECT,
                    'default'       => 'arrows',
                    'options'       => [
                        'arrows'    => esc_html__( 'Arrows', MELA_TD ),
                        'dots'      => esc_html__( 'Dots', MELA_TD ),
                    ],
                    'condition' => [
                        'jltma_instafeed_layout' => 'carousel',
                    ],
                ]
            );

            $this->add_control(
                'jltma_instafeed_carousel_arrow_style',
                [
                    'label' => esc_html__( 'Arrow Position', MELA_TD ),
                    'type' => Controls_Manager::SELECT,
                    'default' => 'outer',
                    'options' => [
                        'inner' => esc_html__( 'Inner Content', MELA_TD ),
                        'outer' => esc_html__( 'Outer Content', MELA_TD ),
                    ],
                    'condition' => [
                        'jltma_instafeed_layout'        => 'carousel',
                        'jltma_instafeed_carousel_nav'  => 'arrows',
                    ]
                ]
            );


            $this->start_controls_tabs( 'jltma_instafeed_carousel_navigation_tabs' );

            $this->start_controls_tab( 'jltma_instafeed_carousel_navigation_control', [ 'label' => esc_html__( 'Normal', MELA_TD
            ) ] );

            $this->add_control(
                'jltma_instafeed_carousel_arrow_color',
                [
                    'label' => esc_html__( 'Arrow Background', MELA_TD ),
                    'type' => Controls_Manager::COLOR,
                    'default' => '#620acc',
                    'selectors' => [
                        '{{WRAPPER}} .ma-el-team-carousel-prev, {{WRAPPER}} .ma-el-team-carousel-next' => 'background: {{VALUE}};',
                    ],
                    'condition' => [
                        'jltma_instafeed_carousel_nav' => 'arrows',
                    ],
                ]
            );

            $this->add_control(
                'jltma_instafeed_carousel_dot_color',
                [
                    'label' => esc_html__( 'Dot Color', MELA_TD ),
                    'type' => Controls_Manager::COLOR,
                    'default' => '#41008e',
                    'selectors' => [
                        '{{WRAPPER}} .slick-dots li button' => 'background-color: {{VALUE}};',
                    ],
                    'condition' => [
                        'jltma_instafeed_carousel_nav' => 'dots',
                    ],
                ]
            );


            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name'        => 'jltma_instafeed_carousel_border',
                    'placeholder' => '1px',
                    'default'     => '0px',
                    'selector'    => '{{WRAPPER}} .ma-el-team-carousel-prev, {{WRAPPER}} .ma-el-team-carousel-next'
                ]
            );


            $this->end_controls_tab();

            $this->start_controls_tab( 'jltma_instafeed_carousel_social_icon_hover', [ 'label' => esc_html__( 'Hover', MELA_TD )
            ] );

            $this->add_control(
                'jltma_instafeed_carousel_arrow_hover_color',
                [
                    'label' => esc_html__( 'Arrow Hover', MELA_TD ),
                    'type' => Controls_Manager::COLOR,
                    'default' => '#41008e',
                    'selectors' => [
                        '{{WRAPPER}} .ma-el-team-carousel-prev:hover, {{WRAPPER}} .ma-el-team-carousel-next:hover' =>
                            'background: {{VALUE}};',
                    ],
                    'condition' => [
                        'jltma_instafeed_carousel_nav' => 'arrows',
                    ],
                ]
            );

            $this->add_control(
                'jltma_instafeed_carousel_dot_hover_color',
                [
                    'label' => esc_html__( 'Dot Hover', MELA_TD ),
                    'type' => Controls_Manager::COLOR,
                    'default' => '#41008e',
                    'selectors' => [
                        '{{WRAPPER}} .slick-dots li.slick-active button, {{WRAPPER}} .slick-dots li button:hover' => 'background: {{VALUE}};',
                    ],
                    'condition' => [
                        'jltma_instafeed_carousel_nav' => 'dots',
                    ],
                ]
            );


            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name'        => 'jltma_instafeed_carousel_hover_border',
                    'placeholder' => '1px',
                    'default'     => '0px',
                    'selector'    => '{{WRAPPER}} .ma-el-team-carousel-prev:hover, {{WRAPPER}} .ma-el-team-carousel-next:hover'
                ]
            );

            $this->end_controls_tab();

            $this->end_controls_tabs();


            $this->add_control(
                'jltma_instafeed_carousel_direction',
                [
                    'label'                 => esc_html__( 'Direction', MELA_TD ),
                    'type'                  => Controls_Manager::SELECT,
                    'default'               => 'rtl',
                    'options'               => [
                        'rtl'       => esc_html__( 'Right to Left', MELA_TD ),
                        'ltr'       => esc_html__( 'Left to Right', MELA_TD ),
                    ],
                    'separator'             => 'before',
                ]
            );

            $this->add_control(
                'jltma_instafeed_carousel_duration',
                [
                    'label'   => esc_html__( 'Transition Duration', MELA_TD ),
                    'type'    => Controls_Manager::NUMBER,
                    'default' => 1000,
                    'separator' => 'before',
                ]
            );

            $this->add_control(
                'jltma_instafeed_carousel_autoplay',
                [
                    'label'                 => esc_html__( 'Autoplay', MELA_TD ),
                    'type'                  => Controls_Manager::SWITCHER,
                    'default'               => 'yes',
                    'label_on'              => esc_html__( 'Yes', MELA_TD ),
                    'label_off'             => esc_html__( 'No', MELA_TD ),
                    'return_value'          => 'yes',
                    'condition'             => [
                        'jltma_instafeed_layout'  => 'carousel',
                    ],
                ]
            );

            $this->add_control(
                'jltma_instafeed_carousel_autoplay_speed',
                [
                    'label'                 => esc_html__( 'Autoplay Speed', MELA_TD ),
                    'type'                  => Controls_Manager::TEXT,
                    'default'               => '2400',
                    'title'                 => esc_html__( 'Enter carousel speed', MELA_TD ),
                    'condition'             => [
                        'jltma_instafeed_carousel_autoplay'     => 'yes',
                        'jltma_instafeed_layout'  => 'carousel',
                    ],
                ]
            );

            $this->add_control(
                'jltma_instafeed_carousel_infinite_loop',
                [
                    'label'                 => esc_html__( 'Infinite Loop', MELA_TD ),
                    'type'                  => Controls_Manager::SWITCHER,
                    'default'               => 'yes',
                    'label_on'              => esc_html__( 'Yes', MELA_TD ),
                    'label_off'             => esc_html__( 'No', MELA_TD ),
                    'return_value'          => 'yes',
                    'condition'             => [
                        'jltma_instafeed_layout' => 'carousel',
                    ],
                ]
            );

            $this->add_control(
                'jltma_instafeed_carousel_pause_on_hover',
                [
                    'label'                 => esc_html__( 'Pause on Hover', MELA_TD ),
                    'description'           => esc_html__( 'Pause & grab cursor when you hover over the slider', MELA_TD ),
                    'type'                  => Controls_Manager::SWITCHER,
                    'default'               => 'No',
                    'label_on'              => esc_html__( 'Yes', MELA_TD ),
                    'label_off'             => esc_html__( 'No', MELA_TD ),
                    'return_value'          => 'yes',
                ]
            );


            $this->end_controls_section();






            /**
             * Style Tab: Layout Style
             */
            $this->start_controls_section(
                'jltma_instafeed_styles_layout',
                [
                    'label' => esc_html__('Layout Style', MELA_TD),
                    'tab' => Controls_Manager::TAB_STYLE,
                    // 'condition'             => [
                    //     'jltma_instafeed_layout'       => 'grid'
                    // ],
                ]
            );

            $this->add_responsive_control(
                'jltma_instafeed_columns_gap',
                [
                    'label'                 => esc_html__( 'Columns Gap', MELA_TD ),
                    'type'                  => Controls_Manager::SLIDER,
                    'default'               => [
                        'size' => '',
                        'unit' => 'px',
                    ],
                    'size_units'            => [ 'px', '%' ],
                    'range'                 => [
                        'px' => [
                            'max' => 100,
                        ],
                    ],
                    'tablet_default'        => [
                        'unit' => 'px',
                    ],
                    'mobile_default'        => [
                        'unit' => 'px',
                    ],
                    'selectors'             => [
                        '{{WRAPPER}} .jltma-instagram-feed .jltma-instafeed-item' => 'padding-left: calc({{SIZE}}{{UNIT}}/2); padding-right: calc({{SIZE}}{{UNIT}}/2);',
                        '{{WRAPPER}} .jltma-instafeed-item .jltma-instafeed-item' => 'margin-left: calc(-{{SIZE}}{{UNIT}}/2); margin-right: calc(-{{SIZE}}{{UNIT}}/2);',
                    ],
                ]
            );

            $this->add_responsive_control(
                'jltma_instafeed_rows_gap',
                [
                    'label'                 => esc_html__( 'Rows Gap', MELA_TD ),
                    'type'                  => Controls_Manager::SLIDER,
                    'default'               => [
                        'size' => '',
                        'unit' => 'px',
                    ],
                    'size_units'            => [ 'px', '%' ],
                    'range'                 => [
                        'px' => [
                            'max' => 100,
                        ],
                    ],
                    'tablet_default'        => [
                        'unit' => 'px',
                    ],
                    'mobile_default'        => [
                        'unit' => 'px',
                    ],
                    'selectors'             => [
                        '{{WRAPPER}} .jltma-instagram-feed .jltma-instafeed-item' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                    ]
                ]
            );


            $this->add_control(
                'jltma_instafeed_container_box_bg',
                [
                    'label' => esc_html__('Container Background', MELA_TD),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .jltma-instagram-feed' => 'background: {{VALUE}}',
                    ],
                ]
            );


            $this->add_control(
                'jltma_instafeed_container_padding',
                [
                    'label'                 => esc_html__( 'Padding', MELA_TD ),
                    'type'                  => Controls_Manager::DIMENSIONS,
                    'size_units'            => ['px', 'em', '%'],
                    'selectors'         => [
                        '{{WRAPPER}} .jltma-instagram-feed' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                    ],
                ]
            );

            $this->add_control(
                'jltma_instafeed_container_margin',
                [
                    'label'                 => esc_html__( 'Margin', MELA_TD ),
                    'type'                  => Controls_Manager::DIMENSIONS,
                    'size_units'            => ['px', 'em', '%'],
                    'selectors'         => [
                        '{{WRAPPER}} .jltma-instagram-feed' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                    ],
                ]
            );

            $this->end_controls_section();



            /**
             * Style Tab: Layout Style
             */
            $this->start_controls_section(
                'jltma_instafeed_item_styles',
                [
                    'label' => esc_html__('Instagram Item Style', MELA_TD),
                    'tab' => Controls_Manager::TAB_STYLE,
                    'condition'             => [
                        'jltma_instafeed_layout'       => ['grid', 'card', 'masonry', 'carousel']
                    ],
                ]
            );

            $this->add_control(
                'jltma_instafeed_item_box_bg',
                [
                    'label' => esc_html__('Item Background', MELA_TD),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .jltma-instagram-item, {{WRAPPER}} .jltma-instafeed-item-inner' => 'background: {{VALUE}}',
                    ],
                ]
            );



            $this->add_control(
                'jltma_instafeed_item_border_radius',
                [
                    'label' => esc_html__('Border Radius', MELA_TD),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', '%'],
                    'selectors' => [
                        '{{WRAPPER}} .jltma-instafeed-item, {{WRAPPER}} .jltma-instafeed-item-inner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'jltma_instafeed_item_border',
                    'label' => esc_html__('Border', MELA_TD),
                    'selector' => '{{WRAPPER}} .jltma-instafeed-item-inner',
                ]
            );


            $this->add_control(
                'jltma_instafeed_item_padding',
                [
                    'label'                 => esc_html__( 'Padding', MELA_TD ),
                    'type'                  => Controls_Manager::DIMENSIONS,
                    'size_units'            => ['px', 'em', '%'],
                    'selectors'         => [
                        '{{WRAPPER}} .jltma-instafeed-item-inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                    ],
                ]
            );

            $this->add_control(
                'jltma_instafeed_item_margin',
                [
                    'label'                 => esc_html__( 'Margin', MELA_TD ),
                    'type'                  => Controls_Manager::DIMENSIONS,
                    'size_units'            => ['px', 'em', '%'],
                    'selectors'         => [
                        '{{WRAPPER}} .jltma-instafeed-item-inner' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                    ],
                ]
            );

            $this->add_control(
                'hr',
                [
                    'type' => Controls_Manager::DIVIDER,
                ]
            );

            $this->add_control(
                'jltma_instafeed_item_card_header_bg_color',
                [
                    'label' => esc_html__('Card Header BG', MELA_TD),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .jltma-instafeed-card header' => 'background: {{VALUE}};'
                    ],
                    'condition' => [
                        'jltma_instafeed_layout' => ['card'],
                    ],
                ]
            );
            $this->add_control(
                'jltma_instafeed_item_card_footer_bg_color',
                [
                    'label' => esc_html__('Card Footer BG', MELA_TD),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .jltma-instafeed-card footer' => 'background: {{VALUE}};'
                    ],
                    'condition' => [
                        'jltma_instafeed_layout' => ['card'],
                    ],
                ]
            );


            $this->add_control(
                'jltma_instafeed_item_username_color',
                [
                    'label' => esc_html__('Username Color', MELA_TD),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .jltma-instafeed-username' => 'color: {{VALUE}};'
                    ],
                    'condition' => [
                        'jltma_instafeed_layout' => ['card'],
                    ],
                ]
            );


            $this->add_control(
                'jltma_instafeed_item_username_hover_color',
                [
                    'label' => esc_html__('Username Color', MELA_TD),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .jltma-instafeed-username:hover' => 'color: {{VALUE}};'
                    ],
                    'condition' => [
                        'jltma_instafeed_layout' => ['card'],
                    ],
                ]
            );


            $this->add_control(
                'jltma_instafeed_item_like_color',
                [
                    'label' => esc_html__('Likes Color', MELA_TD),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .jltma-instafeed-post-likes' => 'color: {{VALUE}};'
                    ],
                    'condition' => [
                        'jltma_instafeed_show_likes' => 'yes',
                    ],
                ]
            );

            $this->add_control(
                'jltma_instafeed_item_like_hover_color',
                [
                    'label' => esc_html__('Likes Hover Color', MELA_TD),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .jltma-instafeed-post-likes:hover' => 'color: {{VALUE}};'
                    ],
                    'condition' => [
                        'jltma_instafeed_show_likes' => 'yes'
                    ],
                ]
            );
            $this->add_control(
                'jltma_instafeed_item_comment_color',
                [
                    'label' => esc_html__('Comments Color', MELA_TD),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .jltma-instafeed-post-comments' => 'color: {{VALUE}};'
                    ],
                    'condition' => [
                        'jltma_instafeed_show_comments' => 'yes',
                    ],
                ]
            );

            $this->add_control(
                'jltma_instafeed_item_comment_hover_color',
                [
                    'label' => esc_html__('Comments Hover Color', MELA_TD),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .jltma-instafeed-post-comments:hover' => 'color: {{VALUE}};'
                    ],
                    'condition' => [
                        'jltma_instafeed_show_comments' => 'yes'
                    ],
                ]
            );

            $this->add_control(
                'jltma_instafeed_item_date_color',
                [
                    'label' => esc_html__('Date Color', MELA_TD),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .jltma-instafeed-post-time' => 'color: {{VALUE}};'
                    ],
                    'condition' => [
                        'jltma_instafeed_date' => 'yes',
                    ],
                ]
            );


            $this->add_control(
                'jltma_instafeed_item_insta_icon_color',
                [
                    'label' => esc_html__('Instagram Icon Color', MELA_TD),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .jltma-instafeed-item.jltma-lightbox .jltma-instafeed-icon i' => 'color: {{VALUE}};'
                    ],
                    'condition' => [
                        'jltma_instafeed_show_insta_icon' => 'yes',
                        'jltma_instafeed_layout' => ['card']
                    ],
                ]
            );

            $this->add_control(
                'jltma_instafeed_item_date_hover_color',
                [
                    'label' => esc_html__('Date Hover Color', MELA_TD),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .jltma-instafeed-post-time:hover' => 'color: {{VALUE}};'
                    ],
                    'condition' => [
                        'jltma_instafeed_date' => 'yes'
                    ],
                ]
            );
            $this->add_control(
                'jltma_instafeed_item_caption_color',
                [
                    'label' => esc_html__('Caption Color', MELA_TD),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .jltma-insta-caption p, {{WRAPPER}} .jltma-instafeed-caption-text' => 'color: {{VALUE}};',
                    ],
                    'condition' => [
                        'jltma_instafeed_show_caption' => 'yes'
                    ],
                ]
            );

            $this->end_controls_section();



            /**
            * Style Tab: Images
            */
            $this->start_controls_section(
                'jltma_instafeed_section_image_style',
                [
                    'label'                 => esc_html__( 'Images', MELA_TD ),
                    'tab'                   => Controls_Manager::TAB_STYLE,
                ]
            );
            $this->start_controls_tabs( 'jltma_instafeed_image_tabs_style' );

            $this->start_controls_tab(
                'jltma_instafeed_image_tab_normal',
                [
                    'label'                 => esc_html__( 'Normal', MELA_TD ),
                ]
            );

            $this->add_control(
                'jltma_instafeed_image_grayscale',
                [
                    'label'                 => esc_html__( 'Grayscale Image', MELA_TD ),
                    'type'                  => Controls_Manager::SWITCHER,
                    'default'               => 'no',
                    'label_on'              => esc_html__( 'Yes', MELA_TD ),
                    'label_off'             => esc_html__( 'No', MELA_TD ),
                    'return_value'          => 'yes',
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name'                  => 'jltma_instafeed_image_border',
                    'label'                 => esc_html__( 'Border', MELA_TD ),
                    'placeholder'           => '1px',
                    'default'               => '1px',
                    'selector'              => '{{WRAPPER}} .jltma-instagram-feed .jltma-instafeed-item .jltma-instafeed-img',
                ]
            );

            $this->add_control(
                'jltma_instafeed_image_border_radius',
                [
                    'label'                 => esc_html__( 'Border Radius', MELA_TD ),
                    'type'                  => Controls_Manager::DIMENSIONS,
                    'size_units'            => [ 'px', '%' ],
                    'selectors'             => [
                        '{{WRAPPER}} .jltma-instagram-feed-gray img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );


            $this->end_controls_tab();

            $this->start_controls_tab(
                'jltma_instafeed_image_hover',
                [
                    'label'                 => esc_html__( 'Hover', MELA_TD ),
                ]
            );

            $this->add_control(
                'jltma_instafeed_image_grayscale_hover',
                [
                    'label'                 => esc_html__( 'Grayscale Image', MELA_TD ),
                    'type'                  => Controls_Manager::SWITCHER,
                    'default'               => 'no',
                    'label_on'              => esc_html__( 'Yes', MELA_TD ),
                    'label_off'             => esc_html__( 'No', MELA_TD ),
                    'return_value'          => 'yes',
                ]
            );

            $this->add_control(
                'jltma_instafeed_image_border_color_hover',
                [
                    'label'                 => esc_html__( 'Border Color', MELA_TD ),
                    'type'                  => Controls_Manager::COLOR,
                    'default'               => '',
                    'selectors'             => [
                        '{{WRAPPER}} .jltma-instagram-feed-hover-gray img:hover' => 'border-color: {{VALUE}};',
                    ],
                ]
            );

            $this->end_controls_tab();

            $this->end_controls_tabs();

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
                    'raw'             => sprintf( esc_html__( '%1$s Live Demo %2$s', MELA_TD ), '<a href="https://master-addons.com/demos/instagram-feed/" target="_blank" rel="noopener">', '</a>' ),
                    'content_classes' => 'jltma-editor-doc-links',
                ]
            );

            $this->add_control(
                'help_doc_2',
                [
                    'type'            => Controls_Manager::RAW_HTML,
                    'raw'             => sprintf( esc_html__( '%1$s Documentation %2$s', MELA_TD ), '<a href="https://master-addons.com/docs/addons/instagram-feed/?utm_source=widget&utm_medium=panel&utm_campaign=dashboard" target="_blank" rel="noopener">', '</a>' ),
                    'content_classes' => 'jltma-editor-doc-links',
                ]
            );

            $this->add_control(
                'help_doc_3',
                [
                    'type'            => Controls_Manager::RAW_HTML,
                    'raw'             => sprintf( esc_html__( '%1$s Watch Video Tutorial %2$s', MELA_TD ), '<a href="https://www.youtube.com/watch?v=4xAaKRoGV_o" target="_blank" rel="noopener">', '</a>' ),
                    'content_classes' => 'jltma-editor-doc-links',
                ]
            );
            $this->end_controls_section();


            //Upgrade to Pro
			if ( ma_el_fs()->is_not_paying() ) {

				$this->start_controls_section(
					'jltma_section_pro_style_section',
					[
						'label' => esc_html__( 'Upgrade to Pro Version for More Features', MELA_TD ),
					]
				);

				$this->add_control(
					'jltma_control_get_pro_style_tab',
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


        }


        protected function jltma_instagram_link_title(){
            $settings = $this->get_settings();

            // Profile Icon
            if ( ! isset( $settings['jltma_instafeed_title_icon'] ) && ! Icons_Manager::is_migration_allowed() ) {
                // add old default
                $settings['jltma_instafeed_title_icon'] = 'fa fa-instagram';
            }

            $has_icon = ! empty( $settings['jltma_instafeed_title_icon'] );

            if ( $has_icon ) {
                $this->add_render_attribute( 'i', 'class', $settings['jltma_instafeed_title_icon'] );
                $this->add_render_attribute( 'i', 'aria-hidden', 'true' );
            }

            if ( ! $has_icon && ! empty( $settings['title_icon']['value'] ) ) {
                $has_icon = true;
            }
            $migrated = isset( $settings['__fa4_migrated']['title_icon'] );
            $is_new = ! isset( $settings['jltma_instafeed_title_icon'] ) && Icons_Manager::is_migration_allowed();


            if ( $settings['jltma_instafeed_profile_link'] == 'yes' && $settings['jltma_instafeed_link_title'] ) { ?>
                <span class="jltma-instagram-feed-title-wrap">
                    <a <?php echo $this->get_render_attribute_string( 'jltma_instagram_profile_link' ); ?>>
                        <span class="jltma-instagram-feed-title">
                            <?php if ( $settings['jltma_instafeed_title_icon_position'] == 'before_title' && $has_icon ) { ?>
                                <span <?php echo $this->get_render_attribute_string( 'title-icon' ); ?>>
                                    <?php if ( $is_new || $migrated ) {
                                        Icons_Manager::render_icon( $settings['title_icon'], [ 'aria-hidden' => 'true' ] );
                                    } elseif ( ! empty( $settings['jltma_instafeed_title_icon'] ) ) { ?>
                                        <i <?php echo $this->get_render_attribute_string( 'i' ); ?>></i>
                                    <?php } ?>
                                </span>
                            <?php } ?>

                            <?php echo esc_attr( $settings[ 'jltma_instafeed_link_title' ] ); ?>

                            <?php if ( $settings['jltma_instafeed_title_icon_position'] == 'after_title' && $has_icon ) { ?>
                                <span <?php echo $this->get_render_attribute_string( 'title-icon' ); ?>>
                                    <?php if ( $is_new || $migrated ) {
                                        Icons_Manager::render_icon( $settings['jltma_instafeed_insta_title_icon'], [ 'aria-hidden' => 'true' ] );
                                    } elseif ( ! empty( $settings['jltma_instafeed_title_icon'] ) ) {
                                        ?><i <?php echo $this->get_render_attribute_string( 'i' ); ?>></i><?php
                                    } ?>
                                </span>
                            <?php } ?>
                        </span>
                    </a>
                </span>
            <?php }
        }


        public function jltma_instafeed_render_items( $settings ){

            if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'jltma_instafeed_load_more_action') {
                // check ajax referer
                check_ajax_referer('master-addons-elementor', 'security');

                // init vars
                $page = $_REQUEST['page'];
                parse_str($_REQUEST['settings'], $settings);
            } else {
                // init vars
                $page = 0;
                $settings = $this->get_settings();
            }

            $key = 'jltma_instafeed_' . str_replace('.', '_', $settings['jltma_instafeed_access_token']);
            $html = '';

            if (get_transient($key) === false) {

                // Show Feed by Username/Tags
                $instagram_data = wp_remote_retrieve_body(wp_remote_get('https://api.instagram.com/v1/users/self/media/recent/?access_token=' . $settings['jltma_instafeed_access_token'] ));
                //     $instagram_data = wp_remote_retrieve_body('https://api.instagram.com/v1/tags/' . $settings['jltma_instafeed_tags'] . '/media/recent?access_token=' . $settings['jltma_instafeed_access_token'] );

                set_transient($key, $instagram_data, 1800);
            } else {
                $instagram_data = get_transient($key);
            }

            $instagram_data = json_decode($instagram_data, true);


            if (empty($instagram_data['data'])) {
                return;
            }

            if (empty( $settings['jltma_instafeed_image_count']['size'] )) {
                return;
            }

            switch ($settings['jltma_instafeed_sort_by']) {
                case 'recent-posts':
                    usort($instagram_data['data'], function($a, $b) {
                        return $a['created_time'] < $b['created_time'];
                    });
                    break;

                case 'old-posts':
                    usort($instagram_data['data'], function($a, $b) {
                        return $a['created_time'] > $b['created_time'];
                    });
                    break;

                case 'most-liked':
                    usort($instagram_data['data'], function ($a, $b) {
                        return $a['likes']['count'] <= $b['likes']['count'];
                    });
                    break;

                case 'less-liked':
                    usort($instagram_data['data'], function ($a, $b) {
                        return $a['likes']['count'] >= $b['likes']['count'];
                    });
                    break;

                case 'most-commented':
                    usort($instagram_data['data'], function ($a, $b) {
                        return $a['comments']['count'] <= $b['comments']['count'];
                    });
                    break;

                case 'less-commented':
                    usort($instagram_data['data'], function ($a, $b) {
                        return $a['comments']['count'] >= $b['comments']['count'];
                    });
                    break;
            }


            // Profile URL Link
            if ( ! empty( $settings['jltma_instafeed_insta_profile_url']['url'] ) ) {
                $this->add_render_attribute( 'jltma_instagram_profile_link', [
                    'class'	=>  'jltma-insta-profile-link',
                    'href'	=> esc_url_raw($settings['jltma_instafeed_insta_profile_url']['url'] ),
                ]);
            }

            if( $settings['jltma_instafeed_insta_profile_url']['is_external'] ) {
                $this->add_render_attribute( 'jltma_instagram_profile_link', 'target', '_blank' );
            }

            if( $settings['jltma_instafeed_insta_profile_url']['nofollow'] ) {
                $this->add_render_attribute( 'jltma_instagram_profile_link', 'rel', 'nofollow' );
            }

            $this->add_render_attribute('jltma_insta_inner', 'class', [ 'jltma-instafeed-item-inner' ] );

            if ($items = $instagram_data['data']) {
                $items = array_splice($items, ($page *  $settings['jltma_instafeed_image_count']['size']),  $settings['jltma_instafeed_image_count']['size']);

                foreach ($items as $k=>$item) {

                    if ('yes' === $settings['jltma_instafeed_show_link']) {
                        $target = ( '_blank' ==$settings['jltma_instafeed_link_target']) ? 'target=_blank' : 'target=_self';
                    } else {
                        $item['link'] = '#';
                        $target = '';
                    } ?>


                <?php if($settings['jltma_instafeed_lightbox_type'] == "inline"){ ?>
                    <div class="card" style="display:none;" id="jltma-insta-lightbox-<?php echo $this->get_id() . $k;?>">

                        <div class="jltma-instafeed-item jltma-lightbox p-0 clearfix" style="background:#fff;">

                            <div class="float-left text-light jltma-col-6 p-0">
                                <img class="jltma-instafeed-card-img card-img" src="<?php echo esc_url( $item['images'][$settings['jltma_instafeed_image_size']]['url']);?>">
                            </div>

                            <div class="float-right jltma-col-6 pt-0 px-0 pb-4">
                                <header class="jltma-instafeed-item-header media">

                                    <div class="jltma-instafeed-item-user clearfix media-left m-3 float-left">
                                        <a class="align-self-center rounded-circle m-3"
                                            href="//www.instagram.com/<?php echo esc_attr( $item['user']['username']);?>">
                                                <img
                                                class="jltma-instafeed-avatar-img rounded-circle "
                                                style="width: 85px; height: 85px;"
                                                src="<?php echo esc_url( $item['user']['profile_picture']);?>" alt="<?php echo esc_attr( $item['user']['username'] );?>" >
                                        </a>
                                    </div>

                                    <div class="media-body align-self-center">
                                        <a class="text-light"
                                            href="//www.instagram.com/<?php echo esc_attr( $item['user']['username']);?>">
                                            <span class="jltma-instafeed-username jltma-insta-user-meta">
                                                <?php echo esc_html( $item['user']['full_name'] );?>
                                            </span>
                                        </a>
                                    </div>

                                    <div class="jltma-instafeed-icon float-right align-self-center mr-3">
                                        <i class="fab fa-instagram" aria-hidden="true"></i>
                                    </div>
                                </header>

                                <div class="jltma-insta-lightbox-card">
                                    <ul>
                                        <li class="active">
                                            <h5>
                                                <?php  if ($settings['jltma_instafeed_show_likes'] == 'yes') { ?>
                                                    <span class="jltma-instafeed-post-likes">
                                                        <?php echo esc_html( $item['likes']['count'] );?>
                                                    </span>
                                                <?php } ?>
                                            </h5>
                                            <?php echo esc_html__('Likes', MELA_TD );?>
                                        </li>

                                        <?php if ($settings['jltma_instafeed_show_comments'] =="yes") { ?>
                                            <li>
                                                <h5>
                                                    <span class="jltma-instafeed-post-comments">
                                                        <?php echo esc_html( $item['comments']['count'] );?>
                                                    </span>
                                                </h5>
                                                <?php echo esc_html__('Comments', MELA_TD );?>
                                            </li>
                                        <?php } ?>

                                        <?php if ( $settings['jltma_instafeed_date'] == "yes") { ?>
                                            <li>
                                                <h5>
                                                    <span class="jltma-instafeed-post-time">
                                                        <?php echo date("d M Y", $item['created_time']);?>
                                                    </span>
                                                </h5>
                                                <?php echo esc_html__('Posted on', MELA_TD );?>
                                            </li>
                                        <?php } ?>
                                    </ul>
                                </div>

                                <footer class="jltma-instafeed-item-footer p-4">
                                    <div class="clearfix">

                                        <?php if ($settings['jltma_instafeed_show_caption']=='yes' && !empty($item['caption']['text'])) { ?>
                                            <div class="jltma-instafeed-caption">
                                                <p class="jltma-instafeed-caption-text">
                                                    <?php echo substr($item['caption']['text'], 0, 60);?>...
                                                </p>
                                            </div>
                                        <?php } ?>
                                    </div>

                                    <?php if ($settings['jltma_instafeed_view_style'] == 'outer' && $settings['jltma_instafeed_show_caption'] =="yes" && !empty($item['caption']['text'])) { ?>
                                        <p class="jltma-instafeed-caption-text">
                                            <?php echo $item['caption']['text'];?>
                                        </p>
                                    <?php } ?>
                                </footer>
                            </div>
                        </div>
                    </div>
                <?php } ?>


                <?php
                // Grid View
                if( $settings['jltma_instafeed_layout'] == 'grid' ||
                     $settings['jltma_instafeed_layout'] == 'masonry' ||
                     $settings['jltma_instafeed_layout'] == 'carousel'
                    ) { ?>

                    <a
                        <?php if( $settings['jltma_instafeed_show_lightbox'] =="yes"){ ?>
                            data-fancybox="<?php echo esc_attr($settings['jltma_instafeed_lightbox_type']);?>"
                            <?php if($settings['jltma_instafeed_lightbox_type'] == "images"){ ?>
                                href="<?php echo esc_url( $item['images'][$settings['jltma_instafeed_image_size']]['url']);?>"
                                <?php if($settings['jltma_instafeed_lightbox_caption'] == "yes"){?>
                                    data-caption="<?php echo esc_attr( $item['caption']['text']);?>"
                                <?php }
                            } elseif($settings['jltma_instafeed_lightbox_type'] == "inline"){ ?>
                                data-src="#jltma-insta-lightbox-<?php echo $this->get_id() . $k;?>"
                                href="javascript:;"
                                data-width="<?php echo esc_attr( $settings['jltma_instafeed_lightbox_width'] );?>"
                                data-height="<?php echo esc_attr( $settings['jltma_instafeed_lightbox_hieght'] );?>"
                            <?php }

                        }else{ ?>

                                href="<?php echo esc_url( $item['link'] );?>"
                                <?php echo esc_attr($target); ?>

                        <?php } ?>

                        class="jltma-instafeed-item">

                        <div <?php echo $this->get_render_attribute_string( 'jltma_insta_inner' ); ?>>
                            <img class="jltma-instafeed-img"

                                src="<?php echo esc_url( $item['images'][$settings['jltma_instafeed_image_size']]['url']);?>" width="<?php echo esc_attr( $item['images'][$settings['jltma_instafeed_image_size']]['width']);?>" height="<?php echo esc_attr( $item['images'][$settings['jltma_instafeed_image_size']]['height']);?>">


                            <div class="jltma-instafeed-item-details">
                                <div class="jltma-instafeed-meta">
                                    <?php if ($settings['jltma_instafeed_show_likes'] == 'yes') { ?>
                                        <span class="jltma-instafeed-post-likes">
                                            <i class="far fa-heart" aria-hidden="true"></i>
                                            <?php echo $item['likes']['count'];?>
                                        </span>
                                    <?php } ?>

                                    <?php if ($settings['jltma_instafeed_show_comments'] =="yes") { ?>
                                        <span class="jltma-instafeed-post-comments">
                                            <i class="far fa-comments" aria-hidden="true"></i>
                                            <?php echo $item['comments']['count'];?>
                                        </span>
                                    <?php } ?>

                                    <?php if ($settings['jltma_instafeed_date'] =="yes") { ?>
                                        <span class="jltma-instafeed-post-time">
                                        <i class="far fa-clock" aria-hidden="true"></i>
                                            <?php echo esc_html( date('d M, Y', preg_replace('/[^\d]/','', $item['created_time'])) );?>
                                        </span>
                                    <?php } ?>

                                    <?php if ($settings['jltma_instafeed_view_style'] == 'hover-info' && $settings['jltma_instafeed_show_caption']=='yes' && !empty($item['caption']['text'])) { ?>
                                        <div class="jltma-insta-caption">
                                            <p class="jltma-instafeed-caption-text">
                                                <?php echo substr($item['caption']['text'], 0, 60);?>
                                            </p>
                                        </div>
                                    <?php } ?>

                                </div>
                            </div>

                        </div> <!-- .jltma-instafeed-item-inner -->

                    </a>

                <?php }

                // Card Style
                if ('card' == $settings['jltma_instafeed_layout'] ){ ?>

                    <div class="jltma-instafeed-item">
                        <div <?php echo $this->get_render_attribute_string( 'jltma_insta_inner' ); ?>>

                            <header class="jltma-instafeed-item-header media">

                                <div class="jltma-instafeed-item-user clearfix media-left mr-3 mb-4 float-left">
                                    <a href="//www.instagram.com/<?php echo esc_attr( $item['user']['username']);?>">
                                        <img src="<?php echo esc_url( $item['user']['profile_picture']);?>" alt="<?php echo esc_attr( $item['user']['username'] );?>" class="jltma-instafeed-avatar-img rounded-circle align-self-center">
                                    </a>
                                </div>

                                <div class="media-body">
                                    <a href="//www.instagram.com/<?php echo esc_attr( $item['user']['username']);?>" <?php echo esc_attr($target);?>>
                                        <span class="jltma-instafeed-username jltma-insta-user-meta">
                                            <?php echo esc_html( $item['user']['full_name'] );?>
                                        </span>
                                    </a>

                                    <?php if ($settings['jltma_instafeed_date'] =='yes') { ?>
                                        <span class="jltma-instafeed-post-time jltma-insta-user-meta">
                                            <i class="far fa-clock" aria-hidden="true"></i>
                                            <?php echo esc_html( date('d M, Y', preg_replace('/[^\d]/','', $item['created_time'])) );?>
                                        </span>
                                    <?php } ?>
                                </div>

                                <?php //if ($settings['jltma_instafeed_show_insta_icon'] == 'yes') { ?>
                                    <div class="jltma-instafeed-icon float-right align-self-center">
                                        <i class="fab fa-instagram" aria-hidden="true"></i>
                                    </div>
                                <?php //} ?>
                            </header>

                            <a href="<?php echo esc_url( $item['link'] );?>" <?php echo esc_attr($target);?> class="jltma-instafeed-item-content">
                                <img class="jltma-instafeed-card-img" src="<?php echo esc_url( $item['images'][$settings['jltma_instafeed_image_size']]['url']);?>">
                            </a>

                            <footer class="jltma-instafeed-item-footer">
                                <div class="clearfix">

                                    <?php if ($settings['jltma_instafeed_show_likes'] == 'yes') { ?>
                                        <span class="jltma-instafeed-post-likes">
                                            <i class="far fa-heart" aria-hidden="true"></i>
                                            <?php echo esc_html( $item['likes']['count'] );?>
                                        </span>

                                    <?php }
                                    if ($settings['jltma_instafeed_show_comments'] =="yes") { ?>

                                        <span class="jltma-instafeed-post-comments">
                                            <i class="far fa-comments" aria-hidden="true"></i>
                                            <?php echo esc_html( $item['comments']['count'] );?>
                                        </span>

                                    <?php } ?>
                                </div>

                                <?php if ( $settings['jltma_instafeed_show_caption'] =="yes" && !empty($item['caption']['text'])) { ?>
                                    <p class="jltma-instafeed-caption-text">
                                        <?php echo substr($item['caption']['text'], 0, 60);?>...
                                    </p>
                                <?php } ?>
                            </footer>
                        </div>
                    </div>
                <?php } // end of Card View


                }
            }

            if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'jltma_instafeed_load_more_action') {
                wp_send_json([
                    'num_pages' => ceil(count($instagram_data['data']) / $settings['jltma_instafeed_image_count']['size'] ),
                    'html' => $html
                ]);
            }

            return $html;
        }

        protected function jltma_instafeed_caption(){
            $settings = $this->get_settings();

            if ( $settings['jltma_instafeed_show_caption']=='yes' && !empty($item['caption']['text'])) { ?>
                <div class="jltma-instafeed-caption">
                    <div class="jltma-instafeed-caption-inner">
                        <div class="jltma-instafeed-meta">
                            <p class="jltma-instafeed-caption-text">
                                <?php echo substr($item['caption']['text'], 0, 60);?>
                            </p>
                        </div>
                    </div>
                </div>

            <?php }
        }

        protected function jltma_slider_settings(){
            $settings = $this->get_settings();

            //Carousel Settings
            $slider_options = [
                "carousel_nav"           => ($settings['jltma_instafeed_carousel_nav'])?$settings['jltma_instafeed_carousel_nav']:"arrows",
                'slidestoshow'           => ( $settings['jltma_instafeed_image_count']['size'] !== '' ) ? absint( $settings['jltma_instafeed_image_count']['size'] ) : 2,
                'slidestoscroll'         => ( $settings['jltma_instafeed_carousel_itmes_to_scroll']['size'] !== '' ) ? absint( $settings['jltma_instafeed_carousel_itmes_to_scroll']['size'] ) : 1,
                'autoplay'               => ( $settings['jltma_instafeed_carousel_autoplay'] === 'yes' )? true: false,
                'autoplayspeed'          => ( $settings['jltma_instafeed_carousel_autoplay_speed'] !== '' )? $settings['jltma_instafeed_carousel_autoplay_speed']: '2400',
                "loop"                   => ($settings['jltma_instafeed_carousel_infinite_loop'])? true : false,
                'speed'                  => ($settings['jltma_instafeed_carousel_duration']) ? $settings['jltma_instafeed_carousel_duration'] : 1000,
                'pauseonHover'           => ( $settings['jltma_instafeed_carousel_pause_on_hover'] === 'yes' ),
                'spaceBetween'           => ( $settings['jltma_instafeed_space_between_item']['size'] !== '' ) ? $settings['jltma_instafeed_space_between_item']['size'] : 10,
                'direction'              => ($settings['jltma_instafeed_carousel_direction'] === "rtl") ? false : true,
                'autoHeight'             => ($settings['jltma_instafeed_force_square'] == 'yes') ? false : true,
            ];


            $this->add_render_attribute([ 'jltma_instagram' =>[
                    'class' =>implode(' ', [
                        'jltma-insta-slider-container',
                        'jltma-insta-slider-' . esc_attr( $this->get_id() )
                    ]),
                    'data-slider-settings' => wp_json_encode( $slider_options )
                ]
            ]);

        }

		protected function render() {
            $settings = $this->get_settings_for_display();
            $id       = 'jltma-instagram-' . $this->get_id();
            $insta_layout = $settings['jltma_instafeed_layout'];

            // Feed Layout
            if ( $insta_layout == 'carousel' ) {
                $layout = 'carousel';
                $this->jltma_slider_settings();
            } else {
                $layout = 'grid';
            }


            $jltma_lightbox = ($settings['jltma_instafeed_show_lightbox'] == "yes") ? "lightbox-enabled" : "lightbox-disabled";

            if( $settings['jltma_instafeed_show_lightbox'] == "yes" ){

                //Lightbox Settings
                $lightbox_options = [
                    'protect'                   => ($settings['jltma_instafeed_lightbox_protect']=="yes")?true:false,
                    'preventCaptionOverlap'     => true,
                    'transitionEffect'          => ($settings['jltma_instafeed_lightbox_transition_effect']) ? $settings['jltma_instafeed_lightbox_transition_effect'] : 'fade' ,
                    'animationEffect'           => ($settings['jltma_instafeed_lightbox_animation_effect'])?$settings['jltma_instafeed_lightbox_animation_effect']:"fade",
                    'buttons'                   => $settings['jltma_instafeed_lightbox_buttons'],
                ];

                $this->add_render_attribute([ 'jltma_instagram' =>[ 'data-lightbox-settings' => wp_json_encode( $lightbox_options ) ]]);

            }


            // Grayscale Image
            if ( $settings['jltma_instafeed_image_grayscale'] == 'yes' ) {
                $this->add_render_attribute( 'jltma_instagram', 'class', 'jltma-instagram-feed-gray' );
            }

            if ( $settings['jltma_instafeed_image_grayscale_hover'] == 'yes' ) {
                $this->add_render_attribute( 'jltma_instagram', 'class', 'jltma-instagram-feed-hover-gray' );
            }


            $this->add_render_attribute(
                [
                    'jltma_instagram' => [
                        'id'    => esc_attr( $id ),
                        'class' => implode(' ', [
                            'jltma-instagram-feed',
                            'jltma-row',
                            $jltma_lightbox,
                            ($settings['jltma_instafeed_force_square'] == 'yes') ? "jltma-instafeed-square-img" : "",
                            'jltma-instafeed-' . $settings['jltma_instafeed_layout'],
                            'jltma-instafeed-' . $layout . '-' . $settings['jltma_instafeed_view_style']
                        ]),
                        'data-settings' => [
                            wp_json_encode(array_filter([
                                "container_id"         => esc_attr( $this->get_id()),
                                "layout"               => esc_attr($insta_layout),
                                'lightbox'             => ($settings['jltma_instafeed_show_lightbox'] == "yes") ? "enabled" : "disabled",
                                "show_profile"         => ($settings["jltma_instafeed_show_username"] and "grid" === $insta_layout) ? true : false
                            ]))
                        ]
                    ]
                ]
            ); ?>

                <div <?php echo ( $this->get_render_attribute_string( 'jltma_instagram' ) ); ?>>
                    <?php
                    $this->jltma_instagram_link_title();
                    $this->jltma_instafeed_render_items( $settings );?>
                </div>
                <div class="clearfix"></div>

                <?php
                $settings_var = [
                    'jltma_instafeed_access_token' => $settings['jltma_instafeed_access_token'],
                    // 'jltma_instafeed_image_count' =>  $settings['jltma_instafeed_image_count']['size'],
                    'jltma_instafeed_sort_by' => $settings['jltma_instafeed_sort_by'],
                    'jltma_instafeed_image_size' => $settings['jltma_instafeed_image_size'],
                    'jltma_instafeed_layout' => $settings['jltma_instafeed_layout'],
                    'jltma_instafeed_view_style' => $settings['jltma_instafeed_view_style'],
                    'jltma_instafeed_date' => $settings['jltma_instafeed_date'],
                    'jltma_instafeed_show_likes' => $settings['jltma_instafeed_show_likes'],
                    'jltma_instafeed_show_comments' => $settings['jltma_instafeed_show_comments'],
                    'jltma_instafeed_show_caption' => $settings['jltma_instafeed_show_caption'],
                    'jltma_instafeed_show_link' => $settings['jltma_instafeed_show_link'],
                    'jltma_instafeed_profile_link' => $settings['jltma_instafeed_profile_link'],
                    'jltma_instafeed_link_target' => $settings['jltma_instafeed_link_target']
                ];

                // if (($settings['jltma_instafeed_load_more'] == 'yes')) {
                //     echo '<div class="jltma-load-more-button-wrap">
                //         <button class="jltma-load-more-button" id="jltma-load-more-btn-' . $this->get_id() . '" data-settings="' . http_build_query($settings_var) . '" data-page="1" data-loadmore-text="'.$settings['jltma_instafeed_load_more_text'].'">
                //             <div class="jltma-btn-loader button__loader"></div>
                //             <span>'.$settings['jltma_instafeed_load_more_text'].'</span>
                //         </button>
                //     </div>';
                // }


                if ( 'masonry' === $settings['jltma_instafeed_layout'] ) {
                    if (\Elementor\Plugin::instance()->editor->is_edit_mode()) {
                        $this->render_editor_script();
                    }
                }


                if ( 'inner' == $settings['jltma_instafeed_carousel_arrow_style'] ) {
                    printf( '<style>.elementor-element-%1$s .ma-el-team-carousel-prev{ left: %2$s;  } .elementor-element-%1$s .ma-el-team-carousel-next{ right: %2$s;  }</style>', $this->get_id(), '3%' );
                }

        }

        protected function render_editor_script() {
            echo '<script type="text/javascript">
                jQuery(document).ready(function($) {

                    $(".jltma-instagram-feed").each(function() {
                        var $node_id = "' . $this->get_id() . '",
                        $gallery = $(this),
                        $scope = $(".elementor-element-"+$node_id+""),
                        $settings = {
                            itemSelector: ".jltma-instafeed-item",
                            percentPosition: true,
                            masonry: {
                                columnWidth: ".jltma-instafeed-item",
                            }
                        };

                        // init isotope
                        $instagram_gallery = $(".jltma-instagram-masonry", $scope).isotope($settings);

                        // layout gal, while images are loading
                        $instagram_gallery.imagesLoaded().progress(function() {
                            $instagram_gallery.isotope("layout");
                        });

                        $(".jltma-instafeed-item", $gallery).resize(function() {
                            $instagram_gallery.isotope("layout");
                        });
                    });
                });
            </script>';
        }

	}

Plugin::instance()->widgets_manager->register_widget_type( new Master_Addons_Instagram_Feed() );