<?php
	namespace Elementor;

	// Elementor Classes
	use Elementor\Widget_Base;
	use Elementor\Controls_Manager;
	use Elementor\Repeater;
	use Elementor\Group_Control_Border;
	use Elementor\Group_Control_Typography;
	use Elementor\Group_Control_Image_Size;
	use Elementor\Scheme_Typography;
	use MasterAddons\Inc\Helper\Master_Addons_Helper;

	/**
	 * Author Name: Liton Arefin
	 * Author URL: https://jeweltheme.com
	 * Date: 10/18/19
	 */



	// Exit if accessed directly.
	if ( ! defined( 'ABSPATH' ) ) { exit; }

	/**
	 * Master Addons: Timeline
	 */
	class Master_Addons_Timeline extends Widget_Base {

		public function get_name() {
			return 'ma-timeline';
		}

		public function get_title() {
			return __( 'MA Timeline', MELA_TD );
		}

		public function get_categories() {
			return [ 'master-addons' ];
		}

		public function get_icon() {
			return 'ma-el-icon eicon-time-line';
		}

		public function get_script_depends() {
			return [
		        'jquery-slick',
		        'master-addons-scripts'
            ];
		}

        public function get_style_depends(){
            return [
                'font-awesome-5-all',
                'font-awesome-4-shim'
            ];
        }

		public function get_keywords() {
			return [ 'timeline', 'post timeline', 'vertical timeline', 'horizontal timeline', 'image timeline' ];
		}


		public function get_help_url() {
			return 'https://master-addons.com/demos/timeline/';
		}


		protected function _register_controls() {

			$this->start_controls_section(
				'ma_el_timeline_section_start',
				[
					'label' => __( 'Timeline', MELA_TD ),
				]
			);


			if ( ma_el_fs()->can_use_premium_code() ) {
				$this->add_control(
					'ma_el_timeline_type',
					[
						'label'    => __( 'Timeline Type', MELA_TD ),
						'type'     => Controls_Manager::SELECT,
						'default'  => 'post',
						'options'  => [
							'post'          => __( 'Post Timeline', MELA_TD ),
							'custom'        => __( 'Custom Timeline', MELA_TD ),
						],
					]
				);
			} else{
				$this->add_control(
					'ma_el_timeline_type',
					[
						'label'    => __( 'Timeline Type', MELA_TD ),
						'type'     => Controls_Manager::SELECT,
						'default'  => 'custom',
						'options'  => [
							'timeline-post' => __( 'Post Timeline (Pro)', MELA_TD ),
							'custom'        => __( 'Custom Timeline', MELA_TD ),
						],
					]
				);
            }


			$this->add_control(
				'ma_el_timeline_design_type',
				[
					'label'    => __( 'Timeline Style', MELA_TD ),
					'type'     => Controls_Manager::SELECT,
					'options'  => [
						'vertical'              => __( 'Vertical Timeline', MELA_TD ),
						'horizontal'            => __( 'Horizontal Timeline', MELA_TD )
					],
					'default'  => 'vertical',
					'condition' => [
						'ma_el_timeline_type' => ['post','custom']
					],
				]
			);


			if ( ma_el_fs()->can_use_premium_code() ) {

				$this->add_control(
					'ma_el_post_grid_ignore_sticky',
					[
						'label'        => esc_html__( 'Ignore Sticky?', MELA_TD ),
						'type'         => Controls_Manager::SWITCHER,
						'return_value' => 'yes',
						'default'      => 'yes',
						'condition'    => [
							'ma_el_timeline_type' => 'post',
						]
					]
				);

				$this->add_control(
					'ma_el_timeline_post_title',
					[
						'label'        => __( 'Show Title', MELA_TD ),
						'type'         => Controls_Manager::SWITCHER,
						'default'      => 'yes',
						'label_on'     => __( 'Yes', MELA_TD ),
						'label_off'    => __( 'No', MELA_TD ),
						'return_value' => 'yes',
						'condition'    => [
							'ma_el_timeline_type' => 'post',
						]
					]
				);

				$this->add_control( 'ma_el_timeline_post_offset',
					[
						'label'     => __( 'Offset Post Count', MELA_TD ),
						'type'      => Controls_Manager::NUMBER,
						'default'   => '0',
						'min'       => '0',
						'condition' => [
							'ma_el_timeline_type' => 'post',
						]
					]
				);

				$this->add_control(
					'ma_el_timeline_date_format',
					[
						'label'     => __( 'Date Format', MELA_TD ),
						'type'      => Controls_Manager::SELECT,
						'options'   => [
							'default' => __( 'Default', MELA_TD ),
							''        => __( 'None', MELA_TD ),
							'F j, Y'  => date( 'F j, Y' ),
							'Y-m-d'   => date( 'Y-m-d' ),
							'm/d/Y'   => date( 'm/d/Y' ),
							'd/m/Y'   => date( 'd/m/Y' ),
							'custom'  => __( 'Custom', MELA_TD ),
						],
						'default'   => 'default',
						'condition' => [
							'ma_el_timeline_type' => 'post',
						],

					]
				);

				$this->add_control(
					'ma_el_timeline_time_format',
					[
						'label'     => __( 'Time Format', MELA_TD ),
						'type'      => Controls_Manager::SELECT,
						'options'   => [
							'default' => __( 'Default', MELA_TD ),
							''        => __( 'None', MELA_TD ),
							'g:i a'   => date( 'g:i a' ),
							'g:i A'   => date( 'g:i A' ),
							'H:i'     => date( 'H:i' ),
						],
						'default'   => 'default',
						'condition' => [
							'ma_el_timeline_type'         => 'post',
							'ma_el_timeline_date_format!' => 'custom',
						],
					]
				);

				$this->add_control(
					'ma_el_timeline_date_custom_format',
					[
						'label'       => __( 'Custom Format', MELA_TD ),
						'default'     => get_option( 'date_format' ) . ' ' . get_option( 'time_format' ),
						'description' => sprintf( '<a href="https://codex.wordpress.org/Formatting_Date_and_Time" target="_blank">%s</a>', __( 'Documentation on date and time formatting', MELA_TD ) ),
						'condition'   => [
							'ma_el_timeline_type'        => 'post',
							'ma_el_timeline_date_format' => 'custom',
						],
					]
				);


				$this->add_control(
					'ma_el_blog_posts_per_page',
					[
						'label'     => __( 'Posts Per Page', MELA_TD ),
						'type'      => Controls_Manager::NUMBER,
						'default'   => '3',
						'condition' => [
							'ma_el_timeline_type' => 'post',
						]
					]
				);

				$this->add_control( 'ma_el_blog_order',
					[
						'label'       => __( 'Post Order', MELA_TD ),
						'type'        => Controls_Manager::SELECT,
						'label_block' => true,
						'options'     => [
							'asc'  => __( 'Ascending', MELA_TD ),
							'desc' => __( 'Descending', MELA_TD )
						],
						'default'     => 'desc',
						'condition'   => [
							'ma_el_timeline_type' => 'post',
						]
					]
				);

				$this->add_control( 'ma_el_blog_order_by',
					[
						'label'       => __( 'Order By', MELA_TD ),
						'type'        => Controls_Manager::SELECT,
						'label_block' => true,
						'options'     => [
							'none'          => __( 'None', MELA_TD ),
							'ID'            => __( 'ID', MELA_TD ),
							'author'        => __( 'Author', MELA_TD ),
							'title'         => __( 'Title', MELA_TD ),
							'name'          => __( 'Name', MELA_TD ),
							'date'          => __( 'Date', MELA_TD ),
							'modified'      => __( 'Last Modified', MELA_TD ),
							'rand'          => __( 'Random', MELA_TD ),
							'comment_count' => __( 'Number of Comments', MELA_TD ),
						],
						'default'     => 'date',
						'condition'   => [
							'ma_el_timeline_type' => 'post',
						]
					]
				);


				$this->add_control( 'ma_el_blog_categories',
					[
						'label'       => __( 'Category', MELA_TD ),
						'type'        => Controls_Manager::SELECT2,
						'description' => __( 'Get posts for specific category(s)', MELA_TD ),
						'label_block' => true,
						'multiple'    => true,
						'options'     => Master_Addons_Helper::ma_el_blog_post_type_categories(),
						'condition'   => [
							'ma_el_timeline_type' => 'post',
						]
						//					'condition'     => [
						//						'ma_el_blog_cat_tabs'  => 'yes'
						//					]
					]
				);

				$this->add_control( 'ma_el_blog_tags',
					[
						'label'       => __( 'Filter By Tag', MELA_TD ),
						'type'        => Controls_Manager::SELECT2,
						'description' => __( 'Get posts for specific tag(s)', MELA_TD ),
						'label_block' => true,
						'multiple'    => true,
						'options'     => Master_Addons_Helper::ma_el_blog_post_type_tags(),
						'condition'   => [
							'ma_el_timeline_type' => 'post',
						]
					]
				);

				$this->add_control( 'ma_el_blog_users',
					[
						'label'       => __( 'Author', MELA_TD ),
						'type'        => Controls_Manager::SELECT2,
						'label_block' => true,
						'multiple'    => true,
						'options'     => Master_Addons_Helper::ma_el_blog_post_type_users(),
						'condition'   => [
							'ma_el_timeline_type' => 'post',
						]
					]
				);


				$this->add_control( 'ma_el_blog_posts_exclude',
					[
						'label'       => __( 'Posts to Exclude', MELA_TD ),
						'type'        => Controls_Manager::SELECT2,
						'description' => __( 'Add post(s) to exclude', MELA_TD ),
						'label_block' => true,
						'multiple'    => true,
						'options'     => Master_Addons_Helper::ma_el_blog_posts_list(),
						'condition'   => [
							'ma_el_timeline_type' => 'post',
						]
					]
				);

				$this->add_control( 'ma_el_timeline_excerpt_length',
					[
						'label'     => __( 'Excerpt Length', MELA_TD ),
						'type'      => Controls_Manager::NUMBER,
						'default'   => 25,
						'condition' => [
							'ma_el_timeline_type' => 'post',
						]
						//					'condition'     => [
						//						'ma_el_timeline_excerpt'  => 'yes',
						//					]
					]
				);

				$this->add_control( 'ma_el_timeline_excerpt_type',
					[
						'label'       => __( 'Excerpt Type', MELA_TD ),
						'type'        => Controls_Manager::SELECT,
						'options'     => [
							'three_dots'     => __( 'Three Dots', MELA_TD ),
							'read_more_link' => __( 'Read More Link', MELA_TD ),
						],
						'default'     => 'read_more_link',
						'label_block' => true,
						'condition'   => [
							'ma_el_timeline_type' => 'post',
						]
						//					'condition'     => [
						//						'ma_el_timeline_excerpt'  => 'yes',
						//					]
					]
				);

				$this->add_control( 'ma_el_timeline_excerpt_text',
					[
						'label'     => __( 'Read More Text', MELA_TD ),
						'type'      => Controls_Manager::TEXT,
						'default'   => __( 'Read More', MELA_TD ),
						'condition' => [
							//						'ma_el_timeline_excerpt'      => 'yes',
							'ma_el_timeline_excerpt_type' => 'read_more_link',
							'ma_el_timeline_type'         => 'post',
						]
					]
				);

			}

			$repeater = new Repeater();

			$repeater->start_controls_tabs( 'ma_el_custom_timeline_items_repeater' );

			$repeater->start_controls_tab(
			        'ma_el_custom_timeline_tab_content',
                    [
                            'label' => __( 'Content', MELA_TD )
                    ]
            );

			$default_title = '<h2>' . __( 'Nam commodo suscipit', MELA_TD ) . '</h2>';
			$default_paragraph = '<p>' . _x( 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo', MELA_TD ) . '</p>';


			$repeater->add_control(
				'ma_el_custom_timeline_content',
				[
					'label' 		=> '',
					'type' 			=> Controls_Manager::WYSIWYG,
					'dynamic'		=> [ 'active' => true ],
					'default' 		=> $default_title . $default_paragraph,
				]
			);


			$repeater->add_control(
				'ma_el_custom_timeline_date',
				[
					'label' 		=> __( 'Date', MELA_TD ),
					'type' 			=> Controls_Manager::DATE_TIME,
					'dynamic'		=> [ 'active' => true ],
					'default'       => __( '23 March 2020', MELA_TD ),
					'placeholder' 	=> __( '23 March 2020', MELA_TD ),
					'label_block'  		=> true,
					'picker_options'	=> array(
						'enableTime' => false,
					),
				]
			);


			$repeater->add_control(
				'ma_el_custom_timeline_link',
				[
					'label' 		=> __( 'Link', MELA_TD ),
					'description'   => __( 'Enable linking the whole card. If you have links inside the content of this card, make sure you have this disabled. Links within links are not allowed.', MELA_TD ),
					'type' 			=> Controls_Manager::URL,
					'dynamic'		=> [ 'active' => true ],
					'placeholder' 	=> esc_url( home_url( '/' ) ),
					'default' 		=> [
						'url' 		=> '',
					],
				]
			);

			$repeater->end_controls_tab();


			$repeater->start_controls_tab( 'ma_el_custom_timeline_tab_media', [ 'label' => __( 'Media', MELA_TD ) ] );

			$repeater->add_control(
				'ma_el_custom_timeline_image',
				[
					'label' 	=> esc_html__( 'Choose Image', MELA_TD ),
					'dynamic'	=> [ 'active' => true ],
					'type' 		=> Controls_Manager::MEDIA,
				]
			);

			$repeater->add_group_control(
				Group_Control_Image_Size::get_type(),
				[
					'name' 		=> 'ma_el_custom_timeline_image_size', // Actually its `image_size`
					'label' 	=> esc_html__( 'Image Size', MELA_TD ),
					'default' 	=> 'large',
					'exclude'    => array( 'custom' ),
				]
			);

			$repeater->end_controls_tab();


			if ( ma_el_fs()->can_use_premium_code() ) {

				$repeater->start_controls_tab( 'ma_el_custom_timeline_tab_style', [ 'label' => __( 'Style', MELA_TD ) ] );

				$repeater->add_control(
					'ma_el_custom_timeline_custom_style',
					[
						'label'        => __( 'Custom', MELA_TD ),
						'type'         => Controls_Manager::SWITCHER,
						'label_on'     => __( 'Yes', MELA_TD ),
						'label_off'    => __( 'No', MELA_TD ),
						'return_value' => 'yes',
						'description'  => __( 'Set custom styles that will only affect this specific item.', MELA_TD ),
					]
				);

				$repeater->add_control(
					'ma_el_custom_timeline_point_content_type',
					[
						'label'      => __( 'Type', MELA_TD ),
						'type'       => Controls_Manager::SELECT,
						'default'    => '',
						'options'    => [
							''        => __( 'Global', MELA_TD ),
							'icons'   => __( 'Icon', MELA_TD ),
							'image'   => __( 'Image', MELA_TD ),
							'numbers' => __( 'Number', MELA_TD ),
							'letters' => __( 'Letter', MELA_TD ),
						],
						'conditions' => [
							'terms' => [
								[
									'name'     => 'ma_el_custom_timeline_custom_style',
									'operator' => '==',
									'value'    => 'yes',
								]
							],
						],
					]
				);

				$repeater->add_control(
					'ma_el_custom_timeline_pointer_image',
					[
						'label'     => __( 'Pointer Image', MELA_TD ),
						'dynamic'   => [ 'active' => true ],
						'type'      => Controls_Manager::MEDIA,
						'condition' => [
							'ma_el_custom_timeline_point_content_type' => "image"
						],
					]
				);

				$repeater->add_group_control(
					Group_Control_Image_Size::get_type(),
					[
						'name'      => 'ma_el_custom_timeline_pointer_image_size', // Actually its `image_size`
						'label'     => __( 'Image Size', MELA_TD ),
						'default'   => 'large',
						'condition' => [
							'ma_el_custom_timeline_point_content_type' => "image"
						],
					]
				);


				$repeater->add_control(
					'ma_el_custom_timeline_selected_icon',
					[
						'label'            => __( 'Point Icon', MELA_TD ),
						'type'             => Controls_Manager::ICONS,
						'fa4compatibility' => 'selected_point_icon',
						'label_block'      => false,
						'default'          => [
							'value'   => 'fas fa-calendar-alt',
							'library' => 'fa-solid',
						],
						'conditions'       => [
							'terms' => [
								[
									'name'     => 'ma_el_custom_timeline_custom_style',
									'operator' => '==',
									'value'    => 'yes',
								],
								[
									'name'     => 'ma_el_custom_timeline_point_content_type',
									'operator' => '==',
									'value'    => 'icons',
								],
							],
						],
					]
				);

				$repeater->add_control(
					'ma_el_custom_timeline_point_content',
					[
						'label'      => __( 'Point Content', MELA_TD ),
						'type'       => Controls_Manager::TEXT,
						'conditions' => [
							'terms' => [
								[
									'name'     => 'ma_el_custom_timeline_custom_style',
									'operator' => '==',
									'value'    => 'yes',
								],
								[
									'name'     => 'ma_el_custom_timeline_point_content_type',
									'operator' => '!==',
									'value'    => 'icons',
								],
								[
									'name'     => 'ma_el_custom_timeline_point_content_type',
									'operator' => '!==',
									'value'    => '',
								],
							],
						],
					]
				);

				$repeater->add_control(
					'ma_el_custom_timeline_item_default',
					[
						'label'      => __( 'Default', MELA_TD ),
						'type'       => Controls_Manager::HEADING,
						'conditions' => [
							'terms' => [
								[
									'name'     => 'ma_el_custom_timeline_custom_style',
									'operator' => '==',
									'value'    => 'yes',
								],
							],
						],
					]
				);


				$repeater->add_control(
					'ma_el_custom_timeline_icon_color',
					[
						'label'      => __( 'Point Color', MELA_TD ),
						'type'       => Controls_Manager::COLOR,
						'default'    => '',
						'selectors'  => [
							'{{WRAPPER}} {{CURRENT_ITEM}} .ma-el-timeline-post-type-icon' => 'color: {{VALUE}};',
						],
						'conditions' => [
							'terms' => [
								[
									'name'     => 'ma_el_custom_timeline_custom_style',
									'operator' => '==',
									'value'    => 'yes',
								],
							],
						],
					]
				);

				$repeater->add_control(
					'ma_el_custom_timeline_point_background',
					[
						'label'      => __( 'Point Background', MELA_TD ),
						'type'       => Controls_Manager::COLOR,
						'default'    => '',
						'selectors'  => [
							'{{WRAPPER}} .ma-el-timeline {{CURRENT_ITEM}} .ma-el-timeline-post-type-icon' => 'background-color: {{VALUE}};',
						],
						'conditions' => [
							'terms' => [
								[
									'name'     => 'ma_el_custom_timeline_custom_style',
									'operator' => '==',
									'value'    => 'yes',
								],
							],
						],
					]
				);

				$repeater->add_control(
					'ma_el_custom_timeline_card_background',
					[
						'label'      => __( 'Card Background', MELA_TD ),
						'type'       => Controls_Manager::COLOR,
						'default'    => '',
						'selectors'  => [
							'{{WRAPPER}} {{CURRENT_ITEM}} .ma-el-timeline-post-inner article' => 'background-color: {{VALUE}};'
						],
						'conditions' => [
							'terms' => [
								[
									'name'     => 'ma_el_custom_timeline_custom_style',
									'operator' => '==',
									'value'    => 'yes',
								],
							],
						],
					]
				);

				$repeater->add_control(
					'ma_el_custom_timeline_date_color',
					[
						'label'      => __( 'Date Color', MELA_TD ),
						'type'       => Controls_Manager::COLOR,
						'default'    => '',
						'selectors'  => [
							'{{WRAPPER}} {{CURRENT_ITEM}} .ma-el-timeline-post-date' => 'color: {{VALUE}};',
						],
						'conditions' => [
							'terms' => [
								[
									'name'     => 'ma_el_custom_timeline_custom_style',
									'operator' => '==',
									'value'    => 'yes',
								],
							],
						],
					]
				);

				$repeater->add_control(
					'ma_el_custom_timeline_point_size',
					[
						'label'      => __( 'Scale', MELA_TD ),
						'type'       => Controls_Manager::SLIDER,
						'default'    => [
							'size' => '',
						],
						'range'      => [
							'px' => [
								'min'  => 0.5,
								'max'  => 2,
								'step' => 0.01
							],
						],
						'selectors'  => [
							'{{WRAPPER}} {{CURRENT_ITEM}} .ma-el-timeline-post-type-icon' => 'transform: scale({{SIZE}})',
						],
						'conditions' => [
							'terms' => [
								[
									'name'     => 'ma_el_custom_timeline_custom_style',
									'operator' => '==',
									'value'    => 'yes',
								],
							],
						],
					]
				);


				$repeater->add_control(
					'ma_el_custom_timeline_item_hover',
					[
						'label'      => __( 'Hover', MELA_TD ),
						'type'       => Controls_Manager::HEADING,
						'conditions' => [
							'terms' => [
								[
									'name'     => 'ma_el_custom_timeline_custom_style',
									'operator' => '==',
									'value'    => 'yes',
								],
							],
						],
					]
				);

				$repeater->add_control(
					'ma_el_custom_timeline_icon_color_hover',
					[
						'label'      => __( 'Hovered Point Color', MELA_TD ),
						'type'       => Controls_Manager::COLOR,
						'default'    => '',
						'selectors'  => [
							'{{WRAPPER}} {{CURRENT_ITEM}} .ma-el-timeline-post-type-icon:hover,
                            {{WRAPPER}} {{CURRENT_ITEM}} .ma-el-timeline-post-type-icon:hover i' => 'color: {{VALUE}};'
						],
						'conditions' => [
							'terms' => [
								[
									'name'     => 'ma_el_custom_timeline_custom_style',
									'operator' => '==',
									'value'    => 'yes',
								],
							],
						],
					]
				);

				$repeater->add_control(
					'ma_el_custom_timeline_point_background_hover',
					[
						'label'      => __( 'Hovered Point Background', MELA_TD ),
						'type'       => Controls_Manager::COLOR,
						'default'    => '',
						'selectors'  => [
							'{{WRAPPER}} {{CURRENT_ITEM}} .ma-el-timeline-post-type-icon:hover,
                            {{WRAPPER}} {{CURRENT_ITEM}} .ma-el-timeline-post-type-icon i:hover' => 'background-color: {{VALUE}};'
						],
						'conditions' => [
							'terms' => [
								[
									'name'     => 'ma_el_custom_timeline_custom_style',
									'operator' => '==',
									'value'    => 'yes',
								],
							],
						],
					]
				);


				$repeater->add_control(
					'ma_el_custom_timeline_card_background_hover',
					[
						'label'      => __( 'Hovered Card Background', MELA_TD ),
						'type'       => Controls_Manager::COLOR,
						'default'    => '',
						'selectors'  => [
							'{{WRAPPER}} {{CURRENT_ITEM}} .ma-el-timeline-post-inner article:hover' => 'background-color: {{VALUE}};'
						],
						'conditions' => [
							'terms' => [
								[
									'name'     => 'ma_el_custom_timeline_custom_style',
									'operator' => '==',
									'value'    => 'yes',
								],
							],
						],
					]
				);

				$repeater->add_control(
					'ma_el_custom_timeline_date_color_hover',
					[
						'label'      => __( 'Hovered Date Color', MELA_TD ),
						'type'       => Controls_Manager::COLOR,
						'default'    => '',
						'selectors'  => [
							'{{WRAPPER}} {{CURRENT_ITEM}} .ma-el-timeline-post-date:hover' => 'color: {{VALUE}};',
						],
						'conditions' => [
							'terms' => [
								[
									'name'     => 'ma_el_custom_timeline_custom_style',
									'operator' => '==',
									'value'    => 'yes',
								],
							],
						],
					]
				);
			}

			$repeater->end_controls_tab();

			$repeater->end_controls_tabs();


			$this->add_control(
				'ma_el_custom_timeline_items',
				[
					'label' 	=> __( 'Items', MELA_TD ),
					'type' 		=> Controls_Manager::REPEATER,
					'default' 	=> [
						[
							'ma_el_custom_timeline_date' => __( 'February 2, 2014', MELA_TD )
						],
						[
							'ma_el_custom_timeline_date' => __( 'May 10, 2015', MELA_TD )
						],
						[
							'ma_el_custom_timeline_date' => __( 'June 21, 2016', MELA_TD )
						],
					],
					'fields' 		=> array_values( $repeater->get_controls() ),
					'title_field' 	=> '{{{ ma_el_custom_timeline_date }}}',
					'condition'		=> [
						'ma_el_timeline_type'	=> 'custom'
					]
				]
			);


			$this->end_controls_section();



			/*
			 * MA Timeline: Image Style
			 */

			$this->start_controls_section(
				'ma_el_timeline_section_images',
				[
					'label' 		=> __( 'Images', MELA_TD ),
					'tab' 			=> Controls_Manager::TAB_STYLE,
				]
			);

			$this->add_control(
				'ma_el_timeline_images_align',
				[
					'label' 		=> __( 'Alignment', MELA_TD ),
					'type' 			=> Controls_Manager::CHOOSE,
					'default'		=> 'left',
					'options' 		=> [
						'left' 		=> [
							'title' => __( 'Left', MELA_TD ),
							'icon' 	=> 'eicon-h-align-left',
						],
						'center' 	=> [
							'title' => __( 'Center', MELA_TD ),
							'icon' 	=> 'eicon-h-align-center',
						],
						'right' 	=> [
							'title' => __( 'Right', MELA_TD ),
							'icon' 	=> 'eicon-h-align-right',
						],
					],
					'selectors' => [
						'{{WRAPPER}} .ee-timeline .timeline-item .timeline-item__img' 	=> 'text-align: {{VALUE}};',
					],
				]
			);


			$this->add_control(
				'ma_el_timeline_images_spacing',
				[
					'label' 	=> __( 'Spacing', MELA_TD ),
					'type' 		=> Controls_Manager::SLIDER,
					'default' 	=> [
						'size' 	=> '',
					],
					'range' 		=> [
						'px' 		=> [
							'min' 	=> 0,
							'max' 	=> 100,
						],
					],
					'selectors' => [
						'{{WRAPPER}} .ma-el-timeline-entry-thimbnail' 	=> 'margin-bottom: {{SIZE}}px;',
					],
				]
			);


			$this->add_control(
				'ma_el_timeline_images_border_radius',
				[
					'label' 		=> __( 'Border Radius', MELA_TD ),
					'type' 			=> Controls_Manager::DIMENSIONS,
					'size_units' 	=> [ 'px', '%' ],
					'selectors' 	=> [
						'{{WRAPPER}} .ma-el-timeline-entry-thimbnail img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

			$this->end_controls_section();



			/*
			 * MA Timeline: Posts
			 */

			$this->start_controls_section(
				'ma_el_timeline_section_posts_style',
				[
					'label' 		=> __( 'Posts', MELA_TD ),
					'tab' 			=> Controls_Manager::TAB_STYLE,
					'condition'		=> [
						'ma_el_timeline_type'	=> 'post',
					]
				]
			);

			$this->add_control(
				'ma_el_timeline_title_heading',
				[
					'label' => __( 'Title', MELA_TD ),
					'type' 	=> Controls_Manager::HEADING,
					'condition'			=> [
						'ma_el_timeline_type'	        => 'post',
						'ma_el_timeline_post_title!' 	=> '',
					]
				]
			);


			$this->add_control(
				'ma_el_timeline_title_color',
				[
					'label' 	=> __( 'Title Color', MELA_TD ),
					'type' 		=> Controls_Manager::COLOR,
					'default'	=> '',
					'selectors' => [
						'{{WRAPPER}} .ee-timeline .timeline-item__title a' => 'color: {{VALUE}};',
					],
					'condition'			=> [
						'ma_el_timeline_type'	        => 'post',
						'ma_el_timeline_post_title!' 	=> '',
					],
				]
			);


			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' 		=> 'ma_el_timeline_titles_typography',
					'selector' 	=> '{{WRAPPER}} .ee-timeline .timeline-item__title',
					'scheme' 	=> Scheme_Typography::TYPOGRAPHY_3,
					'condition'			=> [
						'ma_el_timeline_type'	        => 'post',
						'ma_el_timeline_post_title!' 	=> '',
					],
				]
			);


			$this->add_control(
				'ma_el_timeline_titles_spacing',
				[
					'label' 	=> __( 'Spacing', MELA_TD ),
					'type' 		=> Controls_Manager::SLIDER,
					'default' 	=> [
						'size' 	=> '',
					],
					'range' 		=> [
						'px' 		=> [
							'min' 	=> 0,
							'max' 	=> 100,
						],
					],
					'selectors' => [
						'{{WRAPPER}} .ee-timeline .timeline-item__title' 	=> 'margin-bottom: {{SIZE}}px;',
					],
					'condition'			=> [
						'ma_el_timeline_type'	        => 'post',
						'ma_el_timeline_post_title!' 	=> '',
					],
				]
			);

			$this->add_control(
				'ma_el_timeline_excerpt_heading',
				[
					'label' => __( 'Excerpt', MELA_TD ),
					'type' 	=> Controls_Manager::HEADING,
					'condition'			=> [
						'ma_el_timeline_type'	        => 'post',
						'ma_el_timeline_post_title!' 	=> '',
					]
				]
			);

			$this->add_control(
				'ma_el_timeline_excerpt_color',
				[
					'label' 	=> __( 'Excerpt Color', MELA_TD ),
					'type' 		=> Controls_Manager::COLOR,
					'default'	=> '',
					'selectors' => [
						'{{WRAPPER}} .ee-timeline .timeline-item__excerpt' => 'color: {{VALUE}};',
					],
					'condition'			=> [
						'ma_el_timeline_type'	        => 'post',
						'ma_el_timeline_post_title!' 	=> '',
					],
				]
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' 		=> 'ma_el_timeline_excerpt_typography',
					'selector' 	=> '{{WRAPPER}} .ee-timeline .timeline-item__excerpt',
					'scheme' 	=> Scheme_Typography::TYPOGRAPHY_3,
					'condition'			=> [
						'ma_el_timeline_type'	        => 'post',
						'ma_el_timeline_post_title!' 	=> '',
					]
				]
			);


			$this->add_responsive_control(
				'ma_el_timeline_excerpt_padding',
				[
					'label' 		=> __( 'Padding', MELA_TD ),
					'type' 			=> Controls_Manager::DIMENSIONS,
					'size_units' 	=> [ 'px' ],
					'selectors' 	=> [
						'{{WRAPPER}} .ee-timeline .timeline-item__excerpt' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
					'condition'			=> [
						'ma_el_timeline_type'	        => 'post',
						'ma_el_timeline_post_title!' 	=> '',
					],
				]
			);
			$this->end_controls_section();


			/*
             * MA Timeline: Layout
             */

			$this->start_controls_section(
				'ma_el_timeline_section_layout',
				[
					'label' 	=> __( 'Layout', MELA_TD ),
					'tab' 		=> Controls_Manager::TAB_STYLE,
				]
			);

			$this->add_control(
				'ma_el_timeline_bar_color',
				[
					'label' 	=> __( 'Bar Color', MELA_TD ),
					'type' 		=> Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .ma-el-blog-timeline-posts.solid-bg-color .ma-el-timeline-horz-pointer:before,
						{{WRAPPER}} .ma-el-blog-timeline-posts .ma-el-blog-timeline-post:before' => 'background-color: {{VALUE}};',
					],
				]
			);

			$this->end_controls_section();

			/*
             * MA Timeline: Date
             */

			$this->start_controls_section(
				'ma_el_timeline_section_date',
				[
					'label' 	=> __( 'Date', MELA_TD ),
					'tab' 		=> Controls_Manager::TAB_STYLE,
				]
			);

			$this->add_control(
				'ma_el_timeline_date_color',
				[
					'label' 	=> __( 'Color', MELA_TD ),
					'type' 		=> Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .ma-el-timeline-post-date' => 'color: {{VALUE}};',
					],
				]
			);
			$this->add_control(
				'ma_el_timeline_date_bg_color',
				[
					'label' 	=> __( 'Background Color', MELA_TD ),
					'type' 		=> Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .ma-el-timeline-post-date' => 'background-color: {{VALUE}};',
						'{{WRAPPER}} .ma-el-blog-timeline-post:nth-child(2n+2) .ma-el-timeline-post-date:before'=> 'border-right: 20px solid {{VALUE}};',
						'{{WRAPPER}} .ma-el-blog-timeline-post:nth-child(2n+1) .ma-el-timeline-post-date:before' => 'border-left: 20px solid {{VALUE}};',
					],
				]
			);

			$this->end_controls_section();

            /*
             * MA Timeline: Points
             */

			$this->start_controls_section(
				'ma_el_timeline_section_points',
				[
					'label' 	=> __( 'Points', MELA_TD ),
					'tab' 		=> Controls_Manager::TAB_STYLE,
				]
			);

			$this->add_control(
				'ma_el_timeline_points_content',
				[
					'label' 		=> __( 'Type', MELA_TD ),
					'type' 			=> Controls_Manager::SELECT,
					'default' 		=> 'icons',
					'options' 		=> [
						'default' 	=> __( 'Default', MELA_TD ),
						'icons' 	=> __( 'Icons', MELA_TD ),
						'image' 	=> __( 'Image', MELA_TD ),
						'numbers' 	=> __( 'Numbers', MELA_TD ),
						'letters' 	=> __( 'Letters', MELA_TD ),
					]
				]
			);

			$this->add_control(
				'ma_el_timeline_points_image',
				[
					'label' 	=> __( 'Pointer Image', MELA_TD ),
					'dynamic'	=> [ 'active' => true ],
					'type' 		=> Controls_Manager::MEDIA,
					'condition' => [
						'ma_el_timeline_points_content' => "image"
					],
				]
			);


			$this->add_control(
				'ma_el_timeline_selected_global_icon',
				[
					'label' 			=> __( 'Icon', MELA_TD ),
					'type' 				=> Controls_Manager::ICONS,
					'label_block' 		=> true,
					'fa4compatibility' 	=> 'global_icon',
					'default'			=> [
						'value' 		=> 'fa fa-calendar-alt',
						'library' 		=> 'fa-solid',
					],
					'condition'			=> [
						'ma_el_timeline_points_content' => 'icons',
					],
				]
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' 		=> 'ma_el_timeline_points_typography',
					'selector' 	=> '{{WRAPPER}} .ma-el-timeline .ma-el-timeline-post-type-icon',
					'scheme' 	=> Scheme_Typography::TYPOGRAPHY_3,
					'exclude'	=> [ 'font_size' ],
					'condition' 	=> [
						'ma_el_timeline_points_content!' => 'icons',
					],
				]
			);

//			$this->add_group_control(
//				Group_Control_Transition::get_type(),
//				[
//					'name' 		=> 'ma_el_timeline_points',
//					'selector' 	=> '{{WRAPPER}} .ma-el-timeline-post-type-icon',
//					'separator'	=> '',
//				]
//			);

			$this->start_controls_tabs( 'ma_el_timeline_tabs_points' );

			$this->start_controls_tab( 'ma_el_timeline_points_default', [ 'label' => __( 'Default', MELA_TD ) ] );

			$this->add_responsive_control(
				'ma_el_timeline_points_size',
				[
					'label' 	=> __( 'Size', MELA_TD ),
					'type' 		=> Controls_Manager::SLIDER,
					'default' 	=> [
						'size' 	=> 40,
					],
					'range' 		=> [
						'px' 		=> [
							'min' 	=> 10,
							'max' 	=> 80,
						],
					],
					'selectors' => [
						'{{WRAPPER}} .ma-el-timeline-post-type-icon' => 'width: {{SIZE}}px; height: {{SIZE}}px',
						'{{WRAPPER}} .ma-el-timeline-align--left .ma-el-timeline__line' => 'margin-left: calc( {{SIZE}}px / 2 );',
						'{{WRAPPER}} .ma-el-timeline-align--right .ma-el-timeline__line' => 'margin-right: calc( {{SIZE}}px / 2 );',
						'(tablet){{WRAPPER}} .ma-el-timeline-align--center .ma-el-timeline__line' => 'margin-left: calc( {{points_size_tablet.SIZE}}px / 2 );',
						'(mobile){{WRAPPER}} .ma-el-timeline-align--center .ma-el-timeline__line' => 'margin-left: calc( {{points_size_mobile.SIZE}}px / 2 );',
					],
				]
			);

			$this->add_responsive_control(
				'ma_el_timeline_icons_size',
				[
					'label' 	=> __( 'Icon Size', MELA_TD ),
					'type' 		=> Controls_Manager::SLIDER,
					'default' 	=> [
						'size' 	=> 1,
					],
					'range' 		=> [
						'px' 		=> [
							'step'	=> 0.1,
							'min' 	=> 1,
							'max' 	=> 4,
						],
					],
					'selectors' => [
						'{{WRAPPER}} .ma-el-timeline-post-type-icon' => 'font-size: {{SIZE}}em',
					],
					'condition'		=> [
						'ma_el_timeline_points_content' => 'icons'
					]
				]
			);

			$this->add_responsive_control(
				'ma_el_timeline_content_size',
				[
					'label' 	=> __( 'Content Size', MELA_TD ),
					'type' 		=> Controls_Manager::SLIDER,
					'default' 	=> [
						'size' 	=> 1,
					],
					'range' 		=> [
						'px' 		=> [
							'min' 	=> 1,
							'max' 	=> 4,
						],
					],
					'selectors' => [
						'{{WRAPPER}} .ma-el-timeline-post-type-icon .timeline-item__point__text' => 'font-size:{{SIZE}}em;',
					],
					'condition'		=> [
						'ma_el_timeline_points_content!' => 'icons',
					]
				]
			);

			$this->add_control(
				'ma_el_timeline_points_background',
				[
					'label' 	=> __( 'Background Color', MELA_TD ),
					'type' 		=> Controls_Manager::COLOR,
					'scheme' 	=> [
						'type' 		=> Scheme_Color::get_type(),
						'value' 	=> Scheme_Color::COLOR_1,
					],
					'selectors' => [
						'{{WRAPPER}} .ma-el-timeline-post-type-icon' 	=> 'background-color: {{VALUE}};',
					],
				]
			);

			$this->add_control(
				'ma_el_timeline_icons_color',
				[
					'label' 	=> __( 'Points Color', MELA_TD ),
					'type' 		=> Controls_Manager::COLOR,
					'default'	=> '',
					'selectors' => [
						'{{WRAPPER}} .ma-el-timeline-post-type-icon' 	=> 'color: {{VALUE}};',
					],
				]
			);

			$this->add_group_control(
				Group_Control_Text_Shadow::get_type(),
				[
					'name' 		=> 'ma_el_timeline_points_text_shadow',
					'selector' 	=> '{{WRAPPER}} .ma-el-timeline-post-type-icon',
				]
			);

			$this->end_controls_tab();

			$this->start_controls_tab( 'ma_el_timeline_points_hover', [ 'label' => __( 'Hover', MELA_TD ) ] );

			$this->add_control(
				'ma_el_timeline_points_size_hover',
				[
					'label' 	=> __( 'Scale', MELA_TD ),
					'type' 		=> Controls_Manager::SLIDER,
					'default' 	=> [
						'size' 	=> 1,
					],
					'range' 		=> [
						'px' 		=> [
							'min' 	=> 0.5,
							'max' 	=> 2,
							'step'	=> 0.01
						],
					],
					'selectors' => [
						'{{WRAPPER}} .ma-el-blog-timeline-post:hover .ma-el-timeline-post-type-icon' => 'transform: scale({{SIZE}})',
					],
				]
			);

			$this->add_control(
				'ma_el_timeline_points_background_hover',
				[
					'label' 	=> __( 'Background Color', MELA_TD ),
					'type' 		=> Controls_Manager::COLOR,
					'default'	=> '',
					'selectors' => [
						'{{WRAPPER}} .ma-el-blog-timeline-post:hover .ma-el-timeline-post-type-icon' 			=> 'background-color: {{VALUE}};',
					],
				]
			);

			$this->add_control(
				'ma_el_timeline_icons_color_hover',
				[
					'label' 	=> __( 'Points Color', MELA_TD ),
					'type' 		=> Controls_Manager::COLOR,
					'default'	=> '',
					'selectors' => [
						'{{WRAPPER}} .ma-el-timeline-post-type-icon:hover,
						{{WRAPPER}} .ma-el-blog-timeline-post:hover .ma-el-timeline-post-type-icon'=> 'color: {{VALUE}};'
					],
				]
			);

			$this->add_group_control(
				Group_Control_Text_Shadow::get_type(),
				[
					'name' 		=> 'ma_el_timeline_points_text_shadow_hover',
					'selector' 	=> '{{WRAPPER}} .ma-el-blog-timeline-post:hover .ma-el-timeline-post-type-icon'
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
                    'raw'             => sprintf( esc_html__( '%1$s Live Demo %2$s', MELA_TD ), '<a href="https://master-addons.com/demos/timeline/" target="_blank" rel="noopener">', '</a>' ),
                    'content_classes' => 'jltma-editor-doc-links',
                ]
            );

            $this->add_control(
                'help_doc_2',
                [
                    'type'            => Controls_Manager::RAW_HTML,
                    'raw'             => sprintf( esc_html__( '%1$s Documentation %2$s', MELA_TD ), '<a href="https://master-addons.com/docs/addons/timeline-element/?utm_source=widget&utm_medium=panel&utm_campaign=dashboard" target="_blank" rel="noopener">', '</a>' ),
                    'content_classes' => 'jltma-editor-doc-links',
                ]
            );

            $this->add_control(
                'help_doc_3',
                [
                    'type'            => Controls_Manager::RAW_HTML,
                    'raw'             => sprintf( esc_html__( '%1$s Watch Video Tutorial %2$s', MELA_TD ), '<a href="https://www.youtube.com/watch?v=0mcDMKrH1A0" target="_blank" rel="noopener">', '</a>' ),
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
						'description' => '<span class="pro-feature"> Upgrade to  <a href="' . ma_el_fs()->get_upgrade_url() . '" target="_blank">Pro Version</a> for more Elements with
Customization Options.</span>'
					]
				);

				$this->end_controls_section();
			}


		}

		protected function render() {

			if ( get_query_var('paged') ) {
				$paged = get_query_var('paged');
			} elseif ( get_query_var('page') ) {
				$paged = get_query_var('page');
			} else {
				$paged = 1;
			}

			$settings = $this->get_settings_for_display();

			$ma_el_timeline_type = $settings['ma_el_timeline_type'];
			$timeline_layout_type = $settings['ma_el_timeline_design_type'];

			if ( ma_el_fs()->can_use_premium_code() ) {

				$settings[] = $settings['ma_el_blog_posts_per_page'];

				$offset = $settings['ma_el_timeline_post_offset'];

				$post_per_page = $settings['ma_el_blog_posts_per_page'];

				$new_offset = $offset + ( ( $paged - 1 ) * $post_per_page );

				$post_args = Master_Addons_Helper::ma_el_blog_get_post_settings( $settings );

				$posts = Master_Addons_Helper::ma_el_blog_get_post_data( $post_args, $paged, $new_offset );
			}

			$solid_bg_class = ($timeline_layout_type == "horizontal")? "solid-bg-color" :"";

			$this->add_render_attribute('ma_el_timeline', 'class', [
					'ma-el-timeline',
					'ma-el-blog-timeline-posts',
					'ma-el-timeline-' . $settings['ma_el_timeline_type'],
					$solid_bg_class
				]
			);

			$this->ma_el_timeline_global_render_attributes();

			?>

            <div <?php echo $this->get_render_attribute_string( 'ma_el_timeline' ); ?>>

                <?php if( $ma_el_timeline_type == "post" ){

                        if( count( $posts ) ) {
                            global $post;
                            $i = 0;

                            foreach ( $posts as $post ) {
                                $i++;

                                $active_class =($i==1)?"active":"";

                                setup_postdata( $post );
                                echo '<div class="ma-el-blog-timeline-post ' . $active_class . '">';
                                    $this->ma_el_timeline();
                                echo '</div>';
                            }
                        }

                } elseif( $ma_el_timeline_type == "custom" ){


                    // Vertical Layout Design & Custom Layout
                    if($timeline_layout_type == "vertical"){

                        $j =0;
                        foreach ( $settings['ma_el_custom_timeline_items'] as $index => $item ) {
                            $j++;
                            $active_class = ($j==1)?"active":"";

                            $card_tag 		= 'div';
                            $item_key 		= $this->get_repeater_setting_key( 'item', 'ma_el_custom_timeline_items', $index );
                            $card_key 		= $this->get_repeater_setting_key( 'card', 'ma_el_custom_timeline_items', $index );
                            $point_content 	= '';
                            $wysiwyg_key 	= $this->get_repeater_setting_key( 'content', 'ma_el_custom_timeline_items', $index );
                            $meta_key 		= $this->get_repeater_setting_key( 'date', 'ma_el_custom_timeline_items', $index );


                            $this->add_render_attribute( [
                                $item_key => [
                                    'class' => [
                                        'elementor-repeater-item-' . $item['_id'],
                                        'ma-el-blog-timeline-post',
                                        $active_class
                                    ],
                                ],
                                $card_key => [
                                    'class' => 'timeline-item__card',
                                ],
                                $wysiwyg_key => [
                                    'class' => 'ma-el-timeline-entry-content',
                                ],
                                $meta_key => [
                                    'class' => [
                                            'timeline-item__meta',
                                            'meta',
                                        ],
                                    ],
                            ] );

                            if ( ! empty( $item['ma_el_custom_timeline_link']['url'] ) ) {
                                $card_tag = 'a';

                                $this->add_render_attribute( $card_key, 'href', $item['ma_el_custom_timeline_link']['url'] );

                                if ( $item['ma_el_custom_timeline_link']['is_external'] ) {
                                    $this->add_render_attribute( $card_key, 'target', '_blank' );
                                }

                                if ( $item['ma_el_custom_timeline_link']['nofollow'] ) {
                                    $this->add_render_attribute( $card_key, 'rel', 'nofollow' );
                                }
                            }

	                        if ( ma_el_fs()->can_use_premium_code() ) {

		                        if ( ( 'yes' === $item['ma_el_custom_timeline_custom_style'] && '' !== $item['ma_el_custom_timeline_point_content_type'] ) ) {
			                        $point_content_type = $item['ma_el_custom_timeline_point_content_type'];
		                        } else {
			                        $point_content_type = $item['ma_el_custom_timeline_point_content'];
		                        }


		                        switch ( $point_content_type ) {
			                        case 'numbers' :

			                        case 'letters' :
				                        $point_content = $this->ma_timeline_points_text( $point_content_type, $index, $item );
				                        break;

			                        case 'image' :
				                        $point_content = $this->ma_timeline_points_image( $item );
				                        break;

			                        case 'icons' :
				                        $point_content = $this->ma_timeline_points_icon( $item );
				                        break;

			                        default:
				                        $point_content = $this->ma_timeline_points_global_points();
		                        }
	                        }

                            ?>

                            <div <?php echo $this->get_render_attribute_string( $item_key ); ?>>

                                <div class="ma-el-timeline-post-inner">
                                    <<?php echo $card_tag; ?> <?php echo $this->get_render_attribute_string( $card_key ); ?>>
                                        <div class="ma-el-timeline-post-top">

                                            <?php echo $point_content;?>

                                            <div class="ma-el-timeline-post-date">
                                                <time datetime="<?php echo get_the_modified_date( 'c' );?>">
                                                    <?php echo $this->parse_text_editor( $item['ma_el_custom_timeline_date'] ); ?>
                                                </time>
                                            </div><!-- /.ma-el-timeline-post-date -->

                                        </div><!-- /.ma-el-timeline-post-top -->

                                        <article class="post post-type-custom">

                                            <?php if($item['ma_el_custom_timeline_image']['url']){ ?>
                                                <div <?php echo $this->get_render_attribute_string( 'image' ); ?>>
		                                            <?php echo Group_Control_Image_Size::get_attachment_image_html( $item, 'ma_el_custom_timeline_image');?>
                                                </div>
                                            <?php } ?>

                                            <div <?php echo $this->get_render_attribute_string( $wysiwyg_key ); ?>>
                                                <?php echo $this->parse_text_editor( $item['ma_el_custom_timeline_content'] ); ?>
                                            </div>
                                        </article><!-- /.post -->
                                    </<?php echo $card_tag; ?>>

                                </div><!-- /.ma-el-timeline-post-inner -->
                            </div>

                        <?php } ?>


                    <?php } elseif($timeline_layout_type == "horizontal"){
	                        // If Custom and Vertical Horizontal Design
                        ?>

                            <div class="ma-el-blog-timeline-slider">
                                <div class="ma-el-timeline-slider-inner">

                                    <?php
                                        $j =0;
                                        foreach ( $settings['ma_el_custom_timeline_items'] as $index => $item ) {
                                            $j++;
                                            $active_class = ($j==1)?"active":"";

                                            $card_tag 		= 'div';
	                                        $item_key 		= $this->get_repeater_setting_key( 'item', 'ma_el_custom_timeline_items', $index );
                                            $card_key 		= $this->get_repeater_setting_key( 'card', 'ma_el_custom_timeline_items', $index );
                                            $point_content 	= '';
                                            $wysiwyg_key 	= $this->get_repeater_setting_key( 'content', 'ma_el_custom_timeline_items', $index );
                                            $meta_key 		= $this->get_repeater_setting_key( 'date', 'ma_el_custom_timeline_items', $index );


                                            $this->add_render_attribute( [
	                                            $item_key => [
                                                    'class' => [
                                                        'elementor-repeater-item-' . $item['_id'],
                                                        'ma-el-blog-timeline-post',
                                                        $active_class
                                                    ],
                                                ],
                                                $card_key => [
                                                    'class' => 'timeline-item__card',
                                                ],
                                                $wysiwyg_key => [
                                                    'class' => 'ma-el-timeline-entry-content',
                                                ],
                                                $meta_key => [
                                                    'class' => [
                                                        'timeline-item__meta',
                                                        'meta',
                                                    ],
                                                ],
                                            ] );

            		                    if ( ! empty( $item['ma_el_custom_timeline_link']['url'] ) ) {
            			                    $card_tag = 'a';

            			                    $this->add_render_attribute( $card_key, 'href', $item['ma_el_custom_timeline_link']['url'] );

            			                    if ( $item['ma_el_custom_timeline_link']['is_external'] ) {
            				                    $this->add_render_attribute( $card_key, 'target', '_blank' );
            			                    }

            			                    if ( $item['ma_el_custom_timeline_link']['nofollow'] ) {
            				                    $this->add_render_attribute( $card_key, 'rel', 'nofollow' );
            			                    }
            		                    }


            		                    if ( ( 'yes' === isset($item['ma_el_custom_timeline_custom_style']) && isset($item['ma_el_timeline_points_content']) !==''  ) ) {
            			                    $point_content_type = $item['ma_el_timeline_points_content'];
            		                    } else {
            			                    $point_content_type = $settings['ma_el_timeline_points_content'];
            		                    }



                                        switch( $point_content_type ) {
                                            case 'numbers' :

                                            case 'letters' :
                                                $point_content = $this->ma_timeline_points_text( $point_content_type, $index, $item );
                                                break;

                                            case 'image' :
                                                $point_content = $this->ma_timeline_points_image( $item );
                                                break;

                                            case 'icons' :
                                                $point_content = $this->ma_timeline_points_icon( $item );
                                                break;

                                            default:
            				                    $point_content = '<span class="hexagon"></span>';
                                        }

//                                        if($settings['ma_el_timeline_selected_global_icon']['value']){
//	                                            $point_content = $this->ma_timeline_points_global_points();
//                                        } else{
//            				                    $point_content = '<span class="hexagon"></span>';
//                                        }

                                    ?>

                                        <<?php echo esc_attr( $card_tag);?> <?php echo $this->get_render_attribute_string($item_key); ?>>

                                            <div class="ma-el-timeline-post-top">

                                                <div class="ma-el-timeline-post-date">
                                                    <time datetime="<?php echo get_the_modified_date( 'c' );?>">
                                                        <?php echo $this->parse_text_editor( $item['ma_el_custom_timeline_date'] ); ?>
                                                    </time>
                                                </div><!-- /.ma-el-timeline-post-date -->

                                            </div><!-- /.ma-el-timeline-post-top -->

                                            <div class="ma-el-timeline-horz-pointer">
	                                            <?php echo $point_content;?>
                                            </div>

                                            <div class="ma-el-timeline-post-inner">

                                                <article class="post post-type">

                                                    <?php if($item['ma_el_custom_timeline_image']['url']){ ?>
                                                        <div <?php echo $this->get_render_attribute_string( 'image' ); ?>>
		                                                    <?php echo Group_Control_Image_Size::get_attachment_image_html( $item, 'ma_el_custom_timeline_image');?>
                                                        </div>
                                                    <?php } ?>

                                                    <div class="ma-el-timeline-entry-content">

                                                        <div <?php echo $this->get_render_attribute_string( $wysiwyg_key ); ?>>
		                                                    <?php echo $this->parse_text_editor( $item['ma_el_custom_timeline_content'] ); ?>
                                                        </div>

                                                    </div><!-- /.ma-el-timeline-entry-content -->
                                                </article><!-- /.post -->
                                            </div><!-- /.ma-el-timeline-post-inner -->
                                        </<?php echo esc_attr( $card_tag);?>><!-- /.ma-el-timeline-tag -->

            	                    <?php } ?>

                                </div><!-- /.ma-el-timeline-slider-inner -->

                                <!-- <div class="ma-el-blog-timeline-slider-navigation text-center">
                                    <ul>
                                        <li class="ma-el-blog-timeline-slider-prev"><i class="fa fa-angle-left"></i></li>
                                        <li class="ma-el-blog-timeline-slider-next"><i class="fa fa-angle-right"></i></li>
                                    </ul>
                                </div> -->

                    <?php } ?>

                    </div><!-- /.ma-el-blog-timeline-slider -->

                </div>

            <?php }

		}

		protected function ma_timeline_points_text( $type, $index = false, $item = false ){

			$settings 	= $this->get_settings();
			$letters 	= range('A', 'Z');
			$point_key 	= ( $item ) ? $this->get_repeater_setting_key( 'icon', 'items', $index ) : 'point-text';
			$text 		= 0;

			$text 		= ( $type === 'numbers' ) ? $index + 1 : $letters[ $index ];

			if ( $item ) {
				if ( $item['ma_el_custom_timeline_custom_style'] === 'yes' && '' !== $item['ma_el_custom_timeline_point_content'] ) {
					$text = $item['ma_el_custom_timeline_point_content'];
				}
			}

			$this->add_render_attribute( $point_key, 'class', [
			        'ma-el-timeline-post-type-icon',
                    'timeline-item__point__text'
            ] );

			$output = '<div ' . $this->get_render_attribute_string( $point_key ) . '>' . $text . '</div>';

			return $output;
        }

		protected function ma_timeline_points_image( $item = false ){

		    $settings 	= $this->get_settings();

			$output = '<div class="ma-el-timeline-post-mini-thumb">';

			if( isset($item['ma_el_custom_timeline_pointer_image']) && $item['ma_el_custom_timeline_pointer_image'] !="" ){

			    $output .= Group_Control_Image_Size::get_attachment_image_html( $item, 'ma_el_custom_timeline_pointer_image');

            } elseif( $settings['ma_el_timeline_points_image']!="" ){

			    $output .= Group_Control_Image_Size::get_attachment_image_html( $settings, 'ma_el_timeline_points_image');

            }


			$output .= '</div>';

			return $output;

		 }


		protected function ma_timeline_points_global_points(){
			$settings 	= $this->get_settings();

			$global_point_content_type = $settings['ma_el_timeline_points_content'];

            switch( $global_point_content_type ) {

                case 'numbers' :

                case 'letters' :
                    $point_content = $this->ma_timeline_points_text( $global_point_content_type, true, false);
                break;

                case 'image' :
                    $point_content = $this->ma_timeline_points_image($item = false );
                break;

                case 'icons' :
                    $point_content = $this->ma_timeline_points_icon( $item = false );
                break;

                default:
                    $point_content = $this->ma_timeline_points_icon( $item = false );
            }

            return $point_content;
		}


		protected function ma_timeline_points_icon( $item = false ){
			$settings = $this->get_settings();


			$global_icon_migrated 	= isset( $item['__fa4_migrated']['ma_el_timeline_selected_global_icon'] );
			$global_icon_is_new 	= empty( $item['global_icon'] ) && Icons_Manager::is_migration_allowed();
			$has_global_icon 		= ! empty( $settings['global_icon'] ) || ! empty( $settings['ma_el_timeline_selected_global_icon']['value'] );

			if ( $item ) {
				$has_item_icon = ! empty( $item['selected_point_icon'] ) || ! empty( $item['ma_el_custom_timeline_selected_icon']['value'] );
				$item_icon_migrated = isset( $item['__fa4_migrated']['ma_el_custom_timeline_selected_icon'] );
				$item_icon_is_new = empty( $item['selected_point_icon'] ) && Icons_Manager::is_migration_allowed();
			}

			$output = '<div ' .$this->get_render_attribute_string( 'icon-wrapper' ) . '>';

			$icon_markup = '<i class="%s "></i>';

			if ( $item && '' !== isset($item['selected_point_icon']) && $has_item_icon ) {
				if ( $item_icon_is_new || $item_icon_migrated ) {
					$output .= $this->get_library_point_icon( $item['ma_el_custom_timeline_selected_icon'] );
				} else {
					$output .= sprintf( $icon_markup, $item['selected_point_icon'] );
				}
			} else if ( $has_global_icon ) {
				if ( $global_icon_is_new || $global_icon_migrated ) {
					$output .= $this->get_library_point_icon( $settings['ma_el_timeline_selected_global_icon'] );
				} else {
					$output .= sprintf( $icon_markup, $settings['global_icon'] );
				}
			}

			$output .= '</div>';

			return $output;

		}


		protected function get_library_point_icon( $setting ) {
			ob_start();
			Icons_Manager::render_icon( $setting );
			return ob_get_clean();
		}


		protected function ma_el_timeline_global_render_attributes(){
			$settings = $this->get_settings_for_display();

			$this->add_render_attribute( [
				'wrapper' => [
					'class' => [
						'ma-el-timeline',
						'ma-el-timeline--vertical',
					],
				],
				'item' => [
					'class' => [
						'ma-el-timeline__item',
						'timeline-item',
					],
				],
				'icon-wrapper' => [
					'class' => [
						'ma-el-icon',
						'ma-el-timeline-post-type-icon',
						'ma-el-icon-support--svg',
						'ma-el-timeline__item__icon',
					],
				],
				'line' => [
					'class' => 'ma-el-timeline__line',
				],
				'line-inner' => [
					'class' => 'ma-el-timeline__line__inner',
				],
				'card-wrapper' => [
					'class' => 'timeline-item__card-wrapper',
				],
//				'point' => [
//					'class' => 'ma-el-timeline-item__point',
//				],
				'meta' => [
					'class' => 'timeline-item__meta',
				],
				'image' => [
					'class' => [
						'ma-el-timeline-entry-thimbnail',
						'ma-el-timeline-thumbnail',
					],
				],
				'content' => [
					'class' => 'timeline-item__content',
				],
				'arrow' => [
					'class' => 'timeline-item__card__arrow',
				],
				'meta-wrapper' => [
					'class' => 'timeline-item__meta-wrapper',
				],
			] );

        }

		protected function ma_el_timeline(){
			$settings = $this->get_settings();
		    ?>
                <div class="ma-el-timeline-post-inner">
                    <div class="ma-el-timeline-post-top">
                        <div class="ma-el-timeline-post-type-icon">
                            <i class="fa fa-pencil"></i>
                        </div><!-- /.ma-el-timeline-post-type-icon -->

                        <div class="ma-el-timeline-post-date">
                            <time datetime="<?php echo get_the_modified_date( 'c' );?>">
	                            <?php echo $this->render_date( false ); ?>
                            </time>
                        </div><!-- /.ma-el-timeline-post-date -->
                    </div><!-- /.ma-el-timeline-post-top -->

                    <article class="post post-type-">
                        <div class="ma-el-timeline-entry-content">

                            <?php if($settings['ma_el_timeline_post_title'] == "yes"){ ?>
                                <h3 class="ma-el-timeline-entry-title">
                                    <a href="<?php the_permalink(); ?>">
                                        <?php the_title();?>
                                    </a>
                                </h3>
                            <?php } ?>

                            <?php $this->ma_el_timeline_content();?>

                        </div><!-- /.ma-el-timeline-entry-content -->
                    </article><!-- /.post -->
                </div><!-- /.ma-el-timeline-post-inner -->
		<?php }



		protected function ma_el_timeline_content() {

			$settings = $this->get_settings();

			$excerpt_type = $settings['ma_el_timeline_excerpt_type'];
			$excerpt_text = $settings['ma_el_timeline_excerpt_text'];
//			$excerpt_src  = $settings['ma_el_post_grid_excerpt_content'];

			echo Master_Addons_Helper::ma_el_get_excerpt_by_id(
					get_the_ID(),
					$settings['ma_el_timeline_excerpt_length'],
					$excerpt_type,
					$this->parse_text_editor( $excerpt_text ),
					true,
					'',
					''
				);

		}

		protected function render_date( $echo = true ) {

			$settings = $this->get_settings_for_display();

			if ( 'custom' === $settings['ma_el_timeline_date_format'] ) {
				$format = $settings['ma_el_timeline_date_custom_format'];
			} else {
				$date_format = $settings['ma_el_timeline_date_format'];
				$time_format = $settings['ma_el_timeline_time_format'];
				$format = '';

				if ( 'default' === $date_format ) {
					$date_format = get_option( 'ma_el_timeline_date_format' );
				}

				if ( 'default' === $time_format ) {
					$time_format = get_option( 'ma_el_timeline_time_format' );
				}

				if ( $date_format ) {
					$format = $date_format;
					$has_date = true;
				} else {
					$has_date = false;
				}

				if ( $time_format ) {
					if ( $has_date ) {
						$format .= ' ';
					}
					$format .= $time_format;
				}
			}

			$value = get_the_date( $format );

			if ( $echo )
				echo wp_kses_post( $value );
			else return wp_kses_post( $value );
		}



	}

	Plugin::instance()->widgets_manager->register_widget_type( new Master_Addons_Timeline());
