<?php
	namespace Elementor;

	// Elementor Classes
	use Elementor\Widget_Base;
	use Elementor\Controls_Manager;
	use Elementor\Utils;
	use Elementor\Repeater;
	use Elementor\Group_Control_Image_Size;
	use Elementor\Group_Control_Background;
	use Elementor\Group_Control_Box_Shadow;
	use Elementor\Group_Control_Border;
	use Elementor\Group_Control_Typography;
	use Elementor\Scheme_Typography;
	use Elementor\Scheme_Color;
	use MasterAddons\Inc\Helper\Master_Addons_Helper;

	if ( ! defined( 'ABSPATH' ) ) exit; // If this file is called directly, abort.

	class Master_Addons_Advanced_Accordion extends Widget_Base {

		public function get_name() {
			return 'ma-advanced-accordion';
		}

		public function get_title() {
			return esc_html__( 'MA Advanced Accordion', MELA_TD);
		}

		public function get_icon() {
			return 'ma-el-icon eicon-accordion';
		}

		public function get_categories() {
			return [ 'master-addons' ];
		}

		public function get_style_depends() {
			return [
				'font-awesome-5-all',
                'font-awesome-4-shim'
			];
		}


		public function get_help_url() {
			return 'https://master-addons.com/demos/advanced-accordion/';
		}


		protected function _register_controls() {

			/*-----------------------------------------------------------------------------------*/
			/*	CONTENT TAB
			/*-----------------------------------------------------------------------------------*/

			$this->start_controls_section(
				'section_accordion_settings',
				[
					'label'                 => esc_html__( 'Settings', MELA_TD )
				]
			);

			$this->add_control(
				'accordion_type',
				[
					'label'                 => esc_html__( 'Accordion Type', MELA_TD ),
					'type'                  => Controls_Manager::SELECT,
					'default'               => 'accordion',
					'label_block'           => false,
					'options'               => [
						'accordion' 	=> esc_html__( 'Accordion', MELA_TD ),
						'toggle' 		=> esc_html__( 'Toggle', MELA_TD ),
					],
					'frontend_available'    => true,
				]
			);

			$this->add_control(
				'title_html_tag',
				[
					'label'   => __( 'Title HTML Tag', MELA_TD ),
					'type'    => Controls_Manager::SELECT,
					'options' => Master_Addons_Helper::ma_el_title_tags(),
					'default' => 'div',
				]
			);

			$this->add_control(
				'toggle_icon_show',
				[
					'label'                 => esc_html__( 'Toggle Icon', MELA_TD ),
					'type'                  => Controls_Manager::SWITCHER,
					'default'               => 'yes',
					'label_on'              => __( 'Show', MELA_TD ),
					'label_off'             => __( 'Hide', MELA_TD ),
					'return_value'          => 'yes',
				]
			);


			$this->add_control(
				'toggle_icon',
				[
					'label'         	=> esc_html__( 'Collapse Icon', MELA_TD ),
					'type'          	=> Controls_Manager::ICONS,
					'fa4compatibility' 	=> 'icon',
					'render_type'      	=> 'template',
					'default'       	=> [
						'value'     => 'fas fa-minus',
						'library'   => 'solid',
					],
					'include'               => [
						'fa fa-minus',
						'fa fa-minus-circle',
						'fa fa-minus-square-o',
						'fa fa-minus-square',
						'fa fa-search-minus',
						'fa fa-plus',
						'fa fa-plus-circle',
						'fa fa-plus-square-o',
						'fa fa-plus-square',
						'fa fa-search-plus',
						'fa fa-angle-right',
						'fa fa-angle-double-right',
						'fa fa-chevron-right',
						'fa fa-chevron-circle-right',
						'fa fa-arrow-right',
						'fa fa-long-arrow-right',
					],
					'condition'             => [
						'toggle_icon_show' => 'yes'
					]					
					
				]
			);


			$this->add_control(
				'active_icon',
				[
					'label'         	=> esc_html__( 'Open Icon', MELA_TD ),
					'type'          	=> Controls_Manager::ICONS,
					'fa4compatibility' 	=> 'icon',
					'render_type'      	=> 'template',
					'default'       	=> [
						'value'     => 'fas fa-plus',
						'library'   => 'solid',
					],
					'include'               => [
						'fa fa-minus',
						'fa fa-minus-circle',
						'fa fa-minus-square-o',
						'fa fa-minus-square',
						'fa fa-search-minus',
						'fa fa-plus',
						'fa fa-plus-circle',
						'fa fa-plus-square-o',
						'fa fa-plus-square',
						'fa fa-search-plus',
						'fa fa-angle-right',
						'fa fa-angle-double-right',
						'fa fa-chevron-right',
						'fa fa-chevron-circle-right',
						'fa fa-arrow-right',
						'fa fa-long-arrow-right',
					],
					'condition'             => [
						'toggle_icon_show' => 'yes'
					]					
					
				]
			);


			$this->add_control(
				'toggle_speed',
				[
					'label'                 => esc_html__( 'Toggle Speed (ms)', MELA_TD ),
					'type'                  => Controls_Manager::NUMBER,
					'label_block'           => false,
					'default'               => 300,
					'frontend_available'    => true,
				]
			);
			$this->end_controls_section();



			/**
			 * Content Tab: Tabs
			 */
			$this->start_controls_section(
				'section_accordion_tabs',
				[
					'label'                 => esc_html__( 'Accordions', MELA_TD )
				]
			);

			$repeater = new Repeater();

			$repeater->add_control(
				'accordion_tab_default_active',
				[
					'label'                 => esc_html__( 'Active as Default', MELA_TD ),
					'type'                  => Controls_Manager::SWITCHER,
					'default'               => 'no',
					'return_value'          => 'yes',
				]
			);

			$repeater->add_control(
				'accordion_tab_icon_show',
				[
					'label'                 => esc_html__( 'Enable Tab Icon', MELA_TD ),
					'type'                  => Controls_Manager::SWITCHER,
					'default'               => 'yes',
					'return_value'          => 'yes',
				]
			);


			$repeater->add_control(
				'accordion_tab_title_icon',
				[
					'label'         	=> esc_html__( 'Icon', MELA_TD ),
					'description' 		=> esc_html__('Please choose an icon from the list.', MELA_TD),
					'type'          	=> Controls_Manager::ICONS,
					'fa4compatibility' 	=> 'icon',
					'default'       	=> [
						'value'     => 'fas fa-plus',
						'library'   => 'solid',
					],
					'render_type'      => 'template',
					'condition' => [
						'accordion_tab_icon_show' => 'yes'
					],
				]
			);


			// Premium Version Codes
			if ( ma_el_fs()->can_use_premium_code() ) {

				/* Start of Single Accordion Tab Styles */
				$repeater->add_control(
					'single_tab_title_bg_color_show',
					[
						'label'                 => esc_html__( 'Enable Background Color', MELA_TD ),
						'type'                  => Controls_Manager::SWITCHER,
						'default'               => 'no',
						'return_value'          => 'yes',
					]
				);

				$repeater->add_control(
					'single_title_text_color',
					[
						'label'                 => esc_html__( 'Title Color', MELA_TD ),
						'type'                  => Controls_Manager::COLOR,
						'default'               => '#333333',
						'condition'             => [
							'single_tab_title_bg_color_show' => 'yes'
						]
					]
				);

				$repeater->add_control(
					'single_tab_title_bg_color',
					[
						'label'                 => esc_html__( 'Title Background Color', MELA_TD ),
						'type'                  => Controls_Manager::COLOR,
						'default'               => '#fff',
						'condition'             => [
							'single_tab_title_bg_color_show' => 'yes'
						]
					]
				);

				$repeater->add_control(
					'single_title_content_color',
					[
						'label'                 => esc_html__( 'Content Color', MELA_TD ),
						'type'                  => Controls_Manager::COLOR,
						'default'               => '#333333',
						'condition'             => [
							'single_tab_title_bg_color_show' => 'yes'
						],
						'selectors'             => [
							'{{WRAPPER}} .ma-advanced-accordion .ma-accordion-item.ma-multicolor-accordion .ma-accordion-tab-content p' => 'color: {{VALUE}}'
//                                '{{WRAPPER}} .ma-accordion-item' => 'margin-bottom: {{SIZE}}{{UNIT}};',
						]
					]
				);

				$repeater->add_control(
					'single_tab_content_bg_color',
					[
						'label'                 => esc_html__( 'Content Background Color', MELA_TD ),
						'type'                  => Controls_Manager::COLOR,
						'default'               => '#fff',
						'condition'             => [
							'single_tab_title_bg_color_show' => 'yes'
						],
                        'selectors'             => [
                                '{{WRAPPER}} .ma-advanced-accordion .ma-accordion-item.ma-multicolor-accordion .ma-accordion-tab-content'
                                => 'background-color: {{VALUE}};'
//                                '{{WRAPPER}} .ma-accordion-item' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                        ]
					]
				);


				/* End of Single Accordion Tab Styles */

			}




			$repeater->add_control(
				'tab_title',
				[
					'label'                 => __( 'Title', MELA_TD ),
					'type'                  => Controls_Manager::TEXT,
					'default'               => __( 'Accordion Title', MELA_TD ),
					'dynamic'               => [
						'active'   => true,
					],
				]
			);


			// Premium Version Codes
			if ( ma_el_fs()->can_use_premium_code() ) {

				$repeater->add_control(
					'content_type',
					[
						'label'                 => esc_html__( 'Content Type', MELA_TD ),
						'type'                  => Controls_Manager::SELECT,
						'label_block'           => false,
						'options'               => [
							'content'   => __( 'Content', MELA_TD ),
							//						'image'     => __( 'Image', MELA_TD ),
							//						'video' 	=> __( 'Video', MELA_TD ),
							'section'   => __( 'Saved Section', MELA_TD ),
							'widget'    => __( 'Saved Widget', MELA_TD ),
							'template'  => __( 'Saved Page Template', MELA_TD ),
						],
						'default'               => 'content',
					]
				);

				$repeater->add_control(
					'accordion_content',
					[
						'label'                 => esc_html__( 'Content', MELA_TD ),
						'type'                  => Controls_Manager::WYSIWYG,
						'default'               => esc_html__( 'Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', MELA_TD ),
						'dynamic'               => [ 'active' => true ],
						'condition'             => [
							'content_type'	=> 'content',
						],
					]
				);

				$repeater->add_control(
					'image',
					[
						'label'                 => __( 'Image', MELA_TD ),
						'type'                  => Controls_Manager::MEDIA,
						'dynamic'               => [
							'active'   => true,
						],
						'default'               => [
							'url' => Utils::get_placeholder_image_src(),
						],
						'conditions'            => [
							'terms' => [
								[
									'name'      => 'content_type',
									'operator'  => '==',
									'value'     => 'image',
								],
							],
						],
					]
				);

				$repeater->add_control(
					'saved_widget',
					[
						'label'                 => __( 'Choose Widget', MELA_TD ),
						'type'                  => Controls_Manager::SELECT,
						'options'               => $this->get_page_template_options( 'widget' ),
						'default'               => '-1',
						'condition'             => [
							'content_type'    => 'widget',
						],
						'conditions'        => [
							'terms' => [
								[
									'name'      => 'content_type',
									'operator'  => '==',
									'value'     => 'widget',
								],
							],
						],
					]
				);

				$repeater->add_control(
					'saved_section',
					[
						'label'                 => __( 'Choose Section', MELA_TD ),
						'type'                  => Controls_Manager::SELECT,
						'options'               => $this->get_page_template_options( 'section' ),
						'default'               => '-1',
						'conditions'        => [
							'terms' => [
								[
									'name'      => 'content_type',
									'operator'  => '==',
									'value'     => 'section',
								],
							],
						],
					]
				);

				$repeater->add_control(
					'templates',
					[
						'label'                 => __( 'Choose Template', MELA_TD ),
						'type'                  => Controls_Manager::SELECT,
						'options'               => $this->get_page_template_options( 'page' ),
						'default'               => '-1',
						'conditions'        => [
							'terms' => [
								[
									'name'      => 'content_type',
									'operator'  => '==',
									'value'     => 'template',
								],
							],
						],
					]
				);

			} else {
				$repeater->add_control(
					'content_type',
					[
						'label'                 => esc_html__( 'Content Type', MELA_TD ),
						'type'                  => Controls_Manager::SELECT,
						'label_block'           => false,
						'options'               => [
							'content'   => __( 'Content', MELA_TD ),
							'section'   => __( 'Saved Section (Pro)', MELA_TD ),
							'widget'    => __( 'Saved Widget (Pro)', MELA_TD ),
							'template'  => __( 'Saved Page Template (Pro)', MELA_TD ),
						],
						'default'               => 'content',
					]
				);

				$repeater->add_control(
					'accordion_content',
					[
						'label'                 => esc_html__( 'Content', MELA_TD ),
						'type'                  => Controls_Manager::WYSIWYG,
						'default'               => esc_html__( 'Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', MELA_TD ),
						'dynamic'               => [ 'active' => true ],
						'condition'             => [
							'content_type'	=> 'content',
						],
					]
				);
            }


			$this->add_control(
				'tabs',
				[
					'type'                  => Controls_Manager::REPEATER,
					'default'               => [
						[ 'tab_title' => esc_html__( 'Accordion Tab Title 1', MELA_TD ) ],
						[ 'tab_title' => esc_html__( 'Accordion Tab Title 2', MELA_TD ) ],
						[ 'tab_title' => esc_html__( 'Accordion Tab Title 3', MELA_TD ) ],
					],
					'fields'                => array_values( $repeater->get_controls() ),
					'title_field'           => '{{tab_title}}',
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
                    'raw'             => sprintf( esc_html__( '%1$s Live Demo %2$s', MELA_TD ), '<a href="https://master-addons.com/demos/advanced-accordion/" target="_blank" rel="noopener">', '</a>' ),
                    'content_classes' => 'jltma-editor-doc-links',
                ]
            );

            $this->add_control(
                'help_doc_2',
                [
                    'type'            => Controls_Manager::RAW_HTML,
                    'raw'             => sprintf( esc_html__( '%1$s Documentation %2$s', MELA_TD ), '<a href="https://master-addons.com/docs/addons/elementor-accordion-widget/?utm_source=widget&utm_medium=panel&utm_campaign=dashboard" target="_blank" rel="noopener">', '</a>' ),
                    'content_classes' => 'jltma-editor-doc-links',
                ]
            );

            $this->add_control(
                'help_doc_3',
                [
                    'type'            => Controls_Manager::RAW_HTML,
                    'raw'             => sprintf( esc_html__( '%1$s Watch Video Tutorial %2$s', MELA_TD ), '<a href="https://www.youtube.com/watch?v=rdrqWa-tf6Q" target="_blank" rel="noopener">', '</a>' ),
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


			/*
			 * Style Presets
			 */

			$this->start_controls_section(
				'ma_advanced_style_settings',
				[
					'label' => esc_html__( 'General Styles', MELA_TD ),
					'tab' => Controls_Manager::TAB_STYLE,
				]
			);

			$this->add_control(
				'ma_advanced_accordion_style',
				[
					'label'       	=> esc_html__( 'Style Preset', MELA_TD ),
					'type' 			=> Controls_Manager::SELECT,
					'default' 		=> 'three',
					'label_block' 	=> false,
					'options' 		=> [
						'one' => esc_html__( 'Style 1', MELA_TD ),
						'two' => esc_html__( 'Style 2', MELA_TD ),
						'three' => esc_html__( 'Style 3', MELA_TD ),
						'four' => esc_html__( 'Style 4', MELA_TD ),
						'five' => esc_html__( 'Style 5', MELA_TD ),
						'six' => esc_html__( 'Style 6', MELA_TD ),
						'seven' => esc_html__( 'Style 7', MELA_TD ),
						'eight' => esc_html__( 'Style 8', MELA_TD ),
						'nine' => esc_html__( 'Style 9', MELA_TD ),
						'ten' => esc_html__( 'Style 10', MELA_TD ),
					],
				]
			);

			$this->end_controls_section();



			/**
			 * Style Tab: Items
			 */
			$this->start_controls_section(
				'section_accordion_items_style',
				[
					'label'                 => esc_html__( 'Items', MELA_TD ),
					'tab'                   => Controls_Manager::TAB_STYLE,
				]
			);

			$this->add_responsive_control(
				'accordion_items_spacing',
				[
					'label'                 => __( 'Spacing', MELA_TD ),
					'type'                  => Controls_Manager::SLIDER,
					'range'                 => [
						'px' 	=> [
							'min' => 0,
							'max' => 200,
						],
					],
					'default'               => [
						'size' 	=> '',
					],
					'selectors'             => [
						'{{WRAPPER}} .ma-accordion-item' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					],
				]
			);

			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name'                  => 'accordion_items_border',
					'label'                 => esc_html__( 'Border', MELA_TD ),
					'selector'              => '{{WRAPPER}} .ma-accordion-item',
				]
			);

			$this->add_responsive_control(
				'accordion_items_border_radius',
				[
					'label'                 => esc_html__( 'Border Radius', MELA_TD ),
					'type'                  => Controls_Manager::DIMENSIONS,
					'size_units'            => [ 'px', 'em', '%' ],
					'selectors'             => [
						'{{WRAPPER}} .ma-accordion-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

			$this->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				[
					'name'                  => 'accordion_items_box_shadow',
					'selector'              => '{{WRAPPER}} .ma-accordion-item',
				]
			);

			$this->end_controls_section();

			/**
			 * Style Tab: Title
			 */
			$this->start_controls_section(
				'section_title_style',
				[
					'label'                 => esc_html__( 'Title', MELA_TD ),
					'tab'                   => Controls_Manager::TAB_STYLE,
				]
			);

			$this->start_controls_tabs( 'accordion_tabs_style' );

			$this->start_controls_tab(
				'accordion_tab_normal',
				[
					'label'                 => __( 'Normal', MELA_TD ),
				]
			);

			$this->add_control(
				'tab_title_bg_color',
				[
					'label'                 => esc_html__( 'Background Color', MELA_TD ),
					'type'                  => Controls_Manager::COLOR,
					'default'               => '#fff',
					'selectors'	=> [
						'{{WRAPPER}} .ma-advanced-accordion .ma-accordion-tab-title' => 'background-color: {{VALUE}};',
					],
				]
			);

			$this->add_control(
				'tab_title_text_color',
				[
					'label'                 => esc_html__( 'Color', MELA_TD ),
					'type'                  => Controls_Manager::COLOR,
					'default'               => '#333333',
					'selectors'             => [
						'{{WRAPPER}} .ma-advanced-accordion .ma-accordion-tab-title' => 'color: {{VALUE}};',
					],
				]
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name'                  => 'tab_title_typography',
					'selector'              => '{{WRAPPER}} .ma-advanced-accordion .ma-accordion-tab-title',
				]
			);

			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name'                  => 'tab_title_border',
					'label'                 => esc_html__( 'Border', MELA_TD ),
					'selector'              => '{{WRAPPER}} .ma-advanced-accordion .ma-accordion-tab-title',
				]
			);

			$this->end_controls_tab();

			$this->start_controls_tab(
				'accordion_tab_hover',
				[
					'label'                 => __( 'Hover', MELA_TD ),
				]
			);

			$this->add_control(
				'tab_title_bg_color_hover',
				[
					'label'                 => esc_html__( 'Background Color', MELA_TD ),
					'type'                  => Controls_Manager::COLOR,
					'default'               => '#414141',
					'selectors'	=> [
						'{{WRAPPER}} .ma-advanced-accordion .ma-accordion-tab-title:hover' => 'background-color: {{VALUE}}',
					],
				]
			);

			$this->add_control(
				'tab_title_text_color_hover',
				[
					'label'                 => esc_html__( 'Color', MELA_TD ),
					'type'                  => Controls_Manager::COLOR,
					'default'               => '#ffffff',
					'selectors'             => [
						'{{WRAPPER}} .ma-advanced-accordion .ma-accordion-tab-title:hover,
						{{WRAPPER}} .ma-accordion-item.ma-multicolor-accordion .ma-accordion-tab-title:hover' => 'color: {{VALUE}};',
					],
				]
			);

			$this->add_control(
				'tab_title_border_color_hover',
				[
					'label'                 => esc_html__( 'Border Color', MELA_TD ),
					'type'                  => Controls_Manager::COLOR,
					'default'               => '#ffffff',
					'selectors'             => [
						'{{WRAPPER}} .ma-advanced-accordion .ma-accordion-tab-title:hover' => 'border-color: {{VALUE}};',
					],
				]
			);

			$this->end_controls_tab();

			$this->start_controls_tab(
				'accordion_tab_active',
				[
					'label'                 => __( 'Active', MELA_TD ),
				]
			);

			$this->add_control(
				'tab_title_bg_color_active',
				[
					'label'                 => esc_html__( 'Background Color', MELA_TD ),
					'type'                  => Controls_Manager::COLOR,
					'default'               => '#414141',
					'selectors'	=> [


					        //Design 3 & Design 7 & Design 9
						'{{WRAPPER}} .ma-advanced-accordion .ma-accordion-item.three .ma-accordion-tab-title.active,
						{{WRAPPER}} .ma-advanced-accordion .ma-accordion-item.seven .ma-accordion-tab-title.active,
						{{WRAPPER}} .ma-advanced-accordion .ma-accordion-item.nine .ma-accordion-tab-title.active,
						{{WRAPPER}} .ma-advanced-accordion .ma-accordion-item.three .ma-accordion-tab-title:hover,
						{{WRAPPER}} .ma-advanced-accordion .ma-accordion-item.seven .ma-accordion-tab-title:hover,
						{{WRAPPER}} .ma-advanced-accordion .ma-accordion-item.nine .ma-accordion-tab-title:hover' =>
                            'background-color: #fff;',
                        '{{WRAPPER}} .ma-advanced-accordion .ma-accordion-item.three .ma-accordion-tab-title.active .ma-accordion-title-icon,
                        {{WRAPPER}} .ma-advanced-accordion .ma-accordion-item.seven .ma-accordion-tab-title.active .ma-accordion-title-icon,
                        {{WRAPPER}} .ma-advanced-accordion .ma-accordion-item.nine .ma-accordion-tab-title.active .ma-accordion-title-icon,
                        {{WRAPPER}} .ma-advanced-accordion .ma-accordion-item.three .ma-accordion-tab-title:hover .ma-accordion-title-icon,
                        {{WRAPPER}} .ma-advanced-accordion .ma-accordion-item.seven .ma-accordion-tab-title:hover .ma-accordion-title-icon,
                        {{WRAPPER}} .ma-advanced-accordion .ma-accordion-item.nine .ma-accordion-tab-title:hover .ma-accordion-title-icon,
                        {{WRAPPER}} .ma-advanced-accordion .ma-accordion-item.nine .ma-accordion-tab-title:hover .ma-accordion-toggle-icon,
                        {{WRAPPER}} .ma-advanced-accordion .ma-accordion-item.nine .ma-accordion-tab-title.active .ma-accordion-toggle-icon,
                        {{WRAPPER}} .ma-advanced-accordion .ma-accordion-item.three .ma-accordion-tab-title.active .ma-accordion-toggle-icon,
                        {{WRAPPER}} .ma-advanced-accordion .ma-accordion-item.three .ma-accordion-tab-title:hover .ma-accordion-toggle-icon,
                        {{WRAPPER}} .ma-advanced-accordion .ma-accordion-item.seven .ma-accordion-tab-title.active .ma-accordion-toggle-icon,
                        {{WRAPPER}} .ma-advanced-accordion .ma-accordion-item.seven .ma-accordion-tab-title:hover .ma-accordion-toggle-icon'=>
                            'color: #4b00e7;',

					        //Design 4
						'{{WRAPPER}} .ma-advanced-accordion .ma-accordion-item.four .ma-accordion-tab-title.active,
						{{WRAPPER}} .ma-advanced-accordion .ma-accordion-item.four .ma-accordion-tab-title:hover' =>
                            'background-color: #4b00e7;',
                        '{{WRAPPER}} .ma-advanced-accordion .ma-accordion-item.four .ma-accordion-tab-title.active .ma-accordion-title-icon,
                         {{WRAPPER}} .ma-advanced-accordion .ma-accordion-item.four .ma-accordion-tab-title.active .ma-accordion-toggle-icon,
                        {{WRAPPER}} .ma-advanced-accordion .ma-accordion-item.four .ma-accordion-tab-title:hover .ma-accordion-toggle-icon'=>
                            'color: #fff;',

					        //Design 5
						'{{WRAPPER}} .ma-advanced-accordion .ma-accordion-item.five .ma-accordion-tab-title.active,
						{{WRAPPER}} .ma-advanced-accordion .ma-accordion-item.five .ma-accordion-tab-title:hover' =>
                            'background-image: -webkit-gradient( linear, left bottom, left top, from(rgb(10,57,252)), to(rgb(213,0,137)));
                            background-image: -o-linear-gradient( bottom, rgb(10,57,252) 0%, rgb(213,0,137) 100%);
                            background-image: linear-gradient( 0deg, rgb(10,57,252) 0%, rgb(213,0,137) 100%);
                            background-image: -webkit-linear-gradient( 0deg, rgb(10,57,252) 0%, rgb(213,0,137) 100%);',
                        '{{WRAPPER}} .ma-advanced-accordion .ma-accordion-item.five .ma-accordion-tab-title.active .ma-accordion-title-icon' =>
                            'color: #fff;',

					        //Design 8
						'{{WRAPPER}} .ma-advanced-accordion .ma-accordion-item.eight .ma-accordion-tab-title.active,
						{{WRAPPER}} .ma-advanced-accordion .ma-accordion-item.ten .ma-accordion-tab-title.active,
						{{WRAPPER}} .ma-advanced-accordion .ma-accordion-item.eight .ma-accordion-tab-title:hover,
						{{WRAPPER}} .ma-advanced-accordion .ma-accordion-item.ten .ma-accordion-tab-title:hover' =>
                            'background-image: -webkit-gradient( linear, left bottom, left top, from(rgb(10,57,252)), to(rgb(213,0,137)));
                            background-image: -o-linear-gradient( bottom, rgb(10,57,252) 0%, rgb(213,0,137) 100%);
                            background-image: linear-gradient( 0deg, rgb(10,57,252) 0%, rgb(213,0,137) 100%);
                            background-image: -webkit-linear-gradient( 0deg, rgb(10,57,252) 0%, rgb(213,0,137) 100%);',
                        '{{WRAPPER}} .ma-advanced-accordion .ma-accordion-item.eight .ma-accordion-tab-title.active .ma-accordion-title-icon,
                        {{WRAPPER}} .ma-advanced-accordion .ma-accordion-item.ten .ma-accordion-tab-title.active
                                      .ma-accordion-title-icon,
                        {{WRAPPER}} .ma-advanced-accordion .ma-accordion-item.eight .ma-accordion-tab-content,
			                         {{WRAPPER}} .ma-advanced-accordion .ma-accordion-item.ten .ma-accordion-tab-content'=>
                            'color: #fff;',


						'{{WRAPPER}} .ma-advanced-accordion .ma-accordion-tab-title.active' =>
							'background-color: {{VALUE}};',

					],
				]
			);

			$this->add_control(
				'tab_title_text_color_active',
				[
					'label'                 => esc_html__( 'Color', MELA_TD ),
					'type'                  => Controls_Manager::COLOR,
					'default'               => '#ffffff',
					'selectors'             => [
						'{{WRAPPER}} .ma-advanced-accordion .ma-accordion-tab-title.active' => 'color: {{VALUE}};',
					],
				]
			);

			$this->add_control(
				'tab_title_border_color_active',
				[
					'label'                 => esc_html__( 'Border Color', MELA_TD ),
					'type'                  => Controls_Manager::COLOR,
					'default'               => '#ffffff',
					'selectors'             => [
						'{{WRAPPER}} .ma-advanced-accordion .ma-accordion-tab-title.active' => 'border-color: {{VALUE}};',
					],
				]
			);

			$this->end_controls_tab();

			$this->end_controls_tabs();

			$this->add_control(
				'tab_icon_heading',
				[
					'label'                 => __( 'Icon', MELA_TD ),
					'type'                  => Controls_Manager::HEADING,
					'separator'             => 'before',
				]
			);

			$this->add_responsive_control(
				'tab_icon_size',
				[
					'label'                 => __( 'Icon Size', MELA_TD ),
					'type'                  => Controls_Manager::SLIDER,
					'default'               => [
						'size'	=> 16,
						'unit'	=> 'px',
					],
					'size_units'            => [ 'px' ],
					'range'                 => [
						'px'		=> [
							'min'	=> 0,
							'max'	=> 100,
							'step'	=> 1,
						]
					],
					'selectors'             => [
						'{{WRAPPER}} .ma-advanced-accordion .ma-accordion-tab-title .fa' => 'font-size: {{SIZE}}{{UNIT}};',
					]
				]
			);
			$this->add_responsive_control(
				'tab_icon_spacing',
				[
					'label'                 => __( 'Icon Spacing', MELA_TD ),
					'type'                  => Controls_Manager::SLIDER,
					'default'               => [
						'size'	=> 10,
						'unit'	=> 'px',
					],
					'size_units'            => [ 'px' ],
					'range'                 => [
						'px'	=> [
							'min'	=> 0,
							'max'	=> 100,
							'step'	=> 1,
						]
					],
					'selectors'             => [
						'{{WRAPPER}} .ma-advanced-accordion .ma-accordion-tab-title .fa' => 'margin-right: {{SIZE}}{{UNIT}};',
					]
				]
			);

			$this->end_controls_section();

			/**
			 * Style Tab: Content
			 */
			$this->start_controls_section(
				'section_content_style',
				[
					'label'                 => esc_html__( 'Content', MELA_TD ),
					'tab'                   => Controls_Manager::TAB_STYLE,
				]
			);

			$this->add_control(
				'tab_content_bg_color',
				[
					'label'                 => esc_html__( 'Background Color', MELA_TD ),
					'type'                  => Controls_Manager::COLOR,
					'default'               => '',
					'selectors'	=> [
						'{{WRAPPER}} .ma-advanced-accordion .ma-accordion-item .ma-accordion-tab-content' => 'background-color: {{VALUE}};',
					],
				]
			);

			$this->add_control(
				'tab_content_text_color',
				[
					'label'                 => esc_html__( 'Text Color', MELA_TD ),
					'type'                  => Controls_Manager::COLOR,
					'default'               => '#333',
					'selectors'             => [
						'{{WRAPPER}} .ma-advanced-accordion .ma-accordion-item .ma-accordion-tab-content,
						{{WRAPPER}} .ma-advanced-accordion .ma-accordion-item .ma-accordion-tab-content p' => 'color: {{VALUE}};',
					],
				]
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name'                  => 'tab_content_typography',
					'selector'              => '{{WRAPPER}} .ma-advanced-accordion .ma-accordion-item .ma-accordion-tab-content',
				]
			);

			$this->add_responsive_control(
				'tab_content_padding',
				[
					'label'                 => esc_html__( 'Padding', MELA_TD ),
					'type'                  => Controls_Manager::DIMENSIONS,
					'size_units'            => [ 'px', 'em', '%' ],
					'selectors'             => [
						'{{WRAPPER}} .ma-advanced-accordion .ma-accordion-item .ma-accordion-tab-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

			$this->end_controls_section();

			/**
			 * Style Tab
			 */
			$this->start_controls_section(
				'section_toggle_icon_style',
				[
					'label'                 => esc_html__( 'Toggle icon', MELA_TD ),
					'tab'                   => Controls_Manager::TAB_STYLE,
				]
			);

			$this->add_control(
				'toggle_icon_color',
				[
					'label'                 => esc_html__( 'Color', MELA_TD ),
					'type'                  => Controls_Manager::COLOR,
					'default'               => '#444',
					'selectors'	=> [
						'{{WRAPPER}} .ma-advanced-accordion .ma-accordion-tab-title .ma-accordion-toggle-icon' => 'color: {{VALUE}};',
					],
					'condition'	=> [
						'toggle_icon_show' => 'yes'
					]
				]
			);

			$this->add_control(
				'toggle_icon_active_color',
				[
					'label'                 => esc_html__( 'Active Color', MELA_TD ),
					'type'                  => Controls_Manager::COLOR,
					'default'               => '#fff',
					'selectors'	=> [
						'{{WRAPPER}} .ma-advanced-accordion .ma-accordion-tab-title.active .ma-accordion-toggle-icon' =>
                            'color: {{VALUE}};',
						'{{WRAPPER}} .ma-advanced-accordion .ma-accordion-item:hover .ma-accordion-tab-title .ma-accordion-toggle-icon' => 'color: {{VALUE}};',
					],
					'condition'             => [
						'toggle_icon_show' => 'yes'
					]
				]
			);

			$this->add_responsive_control(
				'toggle_icon_size',
				[
					'label'                 => __( 'Size', MELA_TD ),
					'type'                  => Controls_Manager::SLIDER,
					'default'               => [
						'size'	=> 16,
						'unit'	=> 'px',
					],
					'size_units'            => [ 'px' ],
					'range'	=> [
						'px'	=> [
							'min'	=> 0,
							'max'	=> 100,
							'step'	=> 1,
						]
					],
					'selectors'             => [
						'{{WRAPPER}} .ma-advanced-accordion .ma-accordion-tab-title .ma-accordion-toggle-icon' => 'font-size: {{SIZE}}{{UNIT}};',
					],
					'condition'             => [
						'toggle_icon_show' => 'yes'
					]
				]
			);

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
						'description' => '<span class="pro-feature"> Upgrade to  <a href="' . ma_el_fs()->get_upgrade_url() . '" target="_blank">Pro Version</a> for more Elements with Customization Options.</span>'
					]
				);
				$this->end_controls_section();
			}



		}

		protected function render() {

			$settings	= $this->get_settings_for_display();
			$id_int		= substr( $this->get_id_int(), 0, 3 );

			$this->add_render_attribute( 'ma_advance_accordion', [
				'class'                 => 'ma-advanced-accordion',
				'id'                    => 'ma-advanced-accordion-'.esc_attr( $this->get_id() ),
				'data-accordion-id'     => esc_attr( $this->get_id() )
			] );
			?>

            <div
                <?php echo $this->get_render_attribute_string('ma_advance_accordion'); ?>
	            <?php echo 'data-accordion-id="' . esc_attr($this->get_id()) . '"'; ?>
	            <?php echo !empty($settings['accordion_type']) ? 'data-accordion-type="' . esc_attr($settings['accordion_type']) . '"' : 'accordion'; ?>
	            <?php echo !empty($settings['toggle_speed']) ? 'data-toogle-speed="' . esc_attr($settings['toggle_speed']) . '"' : '300'; ?>
            >
                <div class="ma-accordion-<?php echo esc_attr( $settings['ma_advanced_accordion_style'] );?> <?php if( $settings['ma_advanced_accordion_style'] == 'three' ) echo "blue-color";?> <?php if( $settings['ma_advanced_accordion_style'] == 'four' ) echo "title-blue-bg";?><?php if( $settings['ma_advanced_accordion_style'] == 'five' ) echo "title-border"; ?><?php if( $settings['ma_advanced_accordion_style'] == 'six' ) echo "title-gradient-bg"; ?> <?php if( $settings['ma_advanced_accordion_style'] == 'seven' ) echo "title-icon-bg"; ?> <?php if( $settings['ma_advanced_accordion_style'] == 'eight' ) echo "active-bg"; ?> <?php if( $settings['ma_advanced_accordion_style'] == 'nine' ) echo "icon-round-bg"; ?> <?php if( $settings['ma_advanced_accordion_style'] == 'ten' ) echo "image-bg"; ?>">

				<?php
					foreach( $settings['tabs'] as $index => $tab ) {

						$tab_count = $index+1;
						$tab_title_setting_key = $this->get_repeater_setting_key('tab_title', 'tabs', $index);
						$tab_content_setting_key = $this->get_repeater_setting_key('accordion_content', 'tabs', $index);

						$tab_title_class 	= ['ma-accordion-tab-title ma-advanced-accordion-header'];
						$tab_content_class 	= ['ma-accordion-tab-content'];


						if ($tab['accordion_tab_default_active'] == 'yes') {
							$tab_title_class[] 		= 'active-default';
							$tab_content_class[] 	= 'active-default';
						}

						$this->add_render_attribute($tab_title_setting_key, [
							'id' 			=> 'ma-accordion-tab-title-' . $id_int . $tab_count,
							'class' 		=> $tab_title_class,
							'tabindex' 		=> $id_int . $tab_count,
							'data-tab' 		=> $tab_count,
							'role' 			=> 'tab',
							'aria-controls' => 'ma-accordion-tab-content-' . $id_int . $tab_count,
						]);

						$this->add_render_attribute($tab_content_setting_key, [
							'id' 				=> 'ma-accordion-tab-content-' . $id_int . $tab_count,
							'class' 			=> $tab_content_class,
							'data-tab' 			=> $tab_count,
							'role' 				=> 'tabpanel',
							'aria-labelledby' 	=> 'ma-accordion-tab-title-' . $id_int . $tab_count,
						]);


					if ( ma_el_fs()->can_use_premium_code() ) {

						if ( $tab['single_tab_title_bg_color_show'] == 'yes' ) {
						    $single_item_class = 'ma-multicolor-accordion';
						}
					} ?>

                        <div class="ma-accordion-item <?php echo esc_attr( $settings['ma_advanced_accordion_style'] )
                        ; ?> <?php echo isset($single_item_class) ? $single_item_class :'' ;?>">
                            <<?php echo $settings['title_html_tag']; ?> <?php echo $this->get_render_attribute_string
                            ($tab_title_setting_key);

                            // Premium Version Codes
                            if ( ma_el_fs()->can_use_premium_code() ) {

                                if($tab['single_tab_title_bg_color_show']=='yes'){ ?>
                                    style="background-color:<?php echo $tab['single_tab_title_bg_color'];?>;
                                    color:<?php echo $tab['single_title_text_color'];?>"

                                <?php } // Premium Version Codes

                            } ?>>
                                <span class="ma-accordion-title-icon">

                                    <?php if ( $tab['accordion_tab_icon_show'] === 'yes' ) { ?>
                                        <span class="ma-accordion-tab-icon">
                                            <?php Master_Addons_Helper::jltma_fa_icon_picker( 'fas fa-plus', 'icon', $tab['accordion_tab_title_icon'], 'accordion_tab_title_icon', 'fa-accordion-icon' ); 
                                        ?>
                                        </span>
                                    <?php } ?>

                                    <span class="ma-accordion-title-text">
                                        <?php echo $tab['tab_title']; ?>
                                    </span>
                                </span>
								<?php if ( $settings['toggle_icon_show'] === 'yes' ) { ?>
                                    <span class="ma-accordion-toggle-icon">
                                        <?php Master_Addons_Helper::jltma_fa_icon_picker( 'fas fa-minus', 'icon', $settings['toggle_icon'], 'toggle_minus_icon', 'ma-el-accordion-icon-closed' ); 
                                        ?>

                                        <?php Master_Addons_Helper::jltma_fa_icon_picker( 'fas fa-plus', 'icon', $settings['active_icon'], 'toggle_active_icon', 'ma-el-accordion-icon-opened' ); 
                                        ?>
                                    </span>


								<?php } ?>
                            </<?php echo $settings['title_html_tag']; ?>>

                            <div <?php echo $this->get_render_attribute_string($tab_content_setting_key);

	                            // Premium Version Codes
	                            if ( ma_el_fs()->can_use_premium_code() ) {

		                            if($tab['single_tab_title_bg_color_show']=='yes'){ ?>
                                        style="background-color:<?php echo $tab['single_tab_content_bg_color'];?>;
                                                color:<?php echo $tab['single_title_content_color'];?>"

		                            <?php } // Premium Version Codes

	                            }
	                            ?>>
								<?php

						            if ( ma_el_fs()->can_use_premium_code() ) {
							            if ( $tab['content_type'] == 'content' ) {

								            echo do_shortcode( $tab['accordion_content'] );

							            } else if ( $tab['content_type'] == 'section' && ! empty( $tab['saved_section'] ) ) {

								            echo \Elementor\Plugin::$instance->frontend->get_builder_content_for_display( $tab['saved_section'] );

							            } else if ( $tab['content_type'] == 'template' && ! empty( $tab['templates'] ) ) {

								            echo \Elementor\Plugin::$instance->frontend->get_builder_content_for_display( $tab['templates'] );

							            } else if ( $tab['content_type'] == 'widget' && ! empty( $tab['saved_widget'] ) ) {

								            echo \Elementor\Plugin::$instance->frontend->get_builder_content_for_display( $tab['saved_widget'] );

							            }
						            } else{
							            if ( $tab['content_type'] == 'content' ) {

								            echo do_shortcode( $tab['accordion_content'] );

							            }
                                    }
								?>
                            </div>

                        </div>
					<?php } ?>
                </div>
            </div>
			<?php
		}

		public function get_page_template_options( $type = '' ) {

			$page_templates = Master_Addons_Helper::ma_get_page_templates( $type );

			$options[-1]   = __( 'Select', MELA_TD );

			if ( count( $page_templates ) ) {
				foreach ( $page_templates as $id => $name ) {
					$options[ $id ] = $name;
				}
			} else {
				$options['no_template'] = __( 'No saved templates found!', MELA_TD );
			}

			return $options;
		}

	}

	Plugin::instance()->widgets_manager->register_widget_type( new Master_Addons_Advanced_Accordion());