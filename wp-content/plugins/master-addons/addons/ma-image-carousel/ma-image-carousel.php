<?php
	namespace Elementor;

	/**
	 * Author Name: Liton Arefin
	 * Author URL: https://jeweltheme.com
	 * Date: 10/26/19
	 */

	// Elementor Classes
	use Elementor\Widget_Base;
	use Elementor\Controls_Manager;
	use Elementor\Repeater;
	use Elementor\Group_Control_Border;
	use Elementor\Group_Control_Typography;
	use Elementor\Scheme_Typography;
	use MasterAddons\Inc\Helper\Master_Addons_Helper;


	// Exit if accessed directly.
	if ( ! defined( 'ABSPATH' ) ) { exit; }


	if ( ! defined( 'ABSPATH' ) ) exit; // If this file is called directly, abort.

	class Master_Addons_Image_Carousel extends Widget_Base {

		public function get_name() {
			return 'ma-image-carousel';
		}

		public function get_title() {
			return __( 'MA Image Carousel', MELA_TD);
		}

		public function get_icon() {
			return 'ma-el-icon eicon-media-carousel';
		}

		public function get_categories() {
			return [ 'master-addons' ];
		}

		public function get_script_depends() {
			return [ 'jquery-slick', 'master-addons-scripts' ];
		}

		public function get_style_depends() {
			return [ 'master-addons-main-style' ];
		}

		public function get_keywords() {
			return [ 'image', 'image carousel', 'image slider', 'carousel text', 'Image Carousel with Text' ];
		}

		protected function _register_controls() {

			$this->start_controls_section(
				'ma_el_image_carousel',
				[
					'label' => __( 'MA Advanced Image Carousel', MELA_TD ),
				]
			);

			$this->add_control(
				'ma_el_image_carousel_title',
				[
					'label'        => __( 'Show Title?', MELA_TD ),
					'type'         => Controls_Manager::SWITCHER,
					'default'      => 'yes',
					'return_value' => 'yes'
				]
			);

			$this->add_control(
				'ma_el_image_carousel_subtitle',
				[
					'label'        => __( 'Show Subtitle?', MELA_TD ),
					'type'         => Controls_Manager::SWITCHER,
					'default'      => 'yes',
					'return_value' => 'yes'
				]
			);

			$this->add_group_control(
				Group_Control_Image_Size::get_type(),
				[
					'name'          => 'ma_el_image_carousel_image',
					'default'       => 'medium_large',
					'seperator'         => 'after'
				]
			);


			$this->add_control(
				'ma_el_image_carousel_items',
				[
					'label'             => __( 'Carousel Contents', MELA_TD ),
					'type'              => Controls_Manager::REPEATER,
					'default'           => [

						[
							'title'                                  => __( 'Vestibulum purus quam', MELA_TD ),
							'subtitle'                               => __( 'workplace,technology', MELA_TD ),
							'ma_el_image_carousel_button_one_text'   => __( 'Details', MELA_TD ),
							'ma_el_image_carousel_link_one_url'      => "#",
							'ma_el_image_carousel_button_two_text'   => __( 'Demo', MELA_TD ),
							'ma_el_image_carousel_link_two_url'      => "#"
						],
						[
							'title'                                  => __( 'Vestibulum ullam mauris', MELA_TD ),
							'subtitle'                               => __( 'Adventure for live', MELA_TD ),
							'ma_el_image_carousel_button_one_text'   => __( 'Details', MELA_TD ),
							'ma_el_image_carousel_link_one_url'      => "#",
							'ma_el_image_carousel_button_two_text'   => __( 'Demo', MELA_TD ),
							'ma_el_image_carousel_link_two_url'      => "#"
						],
						[
							'title'                                  => __( 'Praesent egestas tristique', MELA_TD ),
							'subtitle'                               => __( 'The Perfect Workspace', MELA_TD ),
							'ma_el_image_carousel_button_one_text'   => __( 'Details', MELA_TD ),
							'ma_el_image_carousel_link_one_url'      => "#",
							'ma_el_image_carousel_button_two_text'   => __( 'Demo', MELA_TD ),
							'ma_el_image_carousel_link_two_url'      => "#"
						],
						[
							'title'                                  => __( 'Quisque malesuada', MELA_TD ),
							'subtitle'                               => __( 'Website Design', MELA_TD ),
							'ma_el_image_carousel_button_one_text'   => __( 'Details', MELA_TD ),
							'ma_el_image_carousel_link_one_url'      => "#",
							'ma_el_image_carousel_button_two_text'   => __( 'Demo', MELA_TD ),
							'ma_el_image_carousel_link_two_url'      => "#"
						],
						[
							'title'                                  => __( 'Charming Technology', MELA_TD ),
							'subtitle'                               => __( 'quadcopter', MELA_TD ),
							'ma_el_image_carousel_button_one_text'   => __( 'Details', MELA_TD ),
							'ma_el_image_carousel_link_one_url'      => "#",
							'ma_el_image_carousel_button_two_text'   => __( 'Demo', MELA_TD ),
							'ma_el_image_carousel_link_two_url'      => "#"
						],
						[
							'title'                                  => __( 'Donec mollis hendrerit', MELA_TD ),
							'subtitle'                               => __( 'Fastest typing speed', MELA_TD ),
							'ma_el_image_carousel_button_one_text'   => __( 'Details', MELA_TD ),
							'ma_el_image_carousel_link_one_url'      => "#",
							'ma_el_image_carousel_button_two_text'   => __( 'Demo', MELA_TD ),
							'ma_el_image_carousel_link_two_url'      => "#"
						],
						[
							'title'                                  => __( 'Nam commodo suscipit', MELA_TD ),
							'subtitle'                               => __( 'Adventurus Life', MELA_TD ),
							'ma_el_image_carousel_button_one_text'   => __( 'Details', MELA_TD ),
							'ma_el_image_carousel_link_one_url'      => "#",
							'ma_el_image_carousel_button_two_text'   => __( 'Demo', MELA_TD ),
							'ma_el_image_carousel_link_two_url'      => "#"
						],
						[
							'title'                                  => __( 'Maecenas egestas arcu', MELA_TD ),
							'subtitle'                               => __( 'Cup of Tea', MELA_TD ),
							'ma_el_image_carousel_button_one_text'   => __( 'Details', MELA_TD ),
							'ma_el_image_carousel_link_one_url'      => "#",
							'ma_el_image_carousel_button_two_text'   => __( 'Demo', MELA_TD ),
							'ma_el_image_carousel_link_two_url'      => "#"
						],
						[
							'title'                                  => __( 'Cras ultricies mi', MELA_TD ),
							'subtitle'                               => __( 'Health is wealth', MELA_TD ),
							'ma_el_image_carousel_button_one_text'   => __( 'Details', MELA_TD ),
							'ma_el_image_carousel_link_one_url'      => "#",
							'ma_el_image_carousel_button_two_text'   => __( 'Demo', MELA_TD ),
							'ma_el_image_carousel_link_two_url'      => "#"
						]
					],
					'fields'          => [
						[
							'name'    => 'ma_el_image_carousel_img',
							'label'   => __( 'Image', MELA_TD ),
							'type'    => Controls_Manager::MEDIA,
						],
						[
							'type'          => Controls_Manager::TEXT,
							'name'          => 'title',
							'label_block'   => true,
							'label'         => __( 'Title', MELA_TD ),
							'default'       => __( 'Item Title', MELA_TD )
						],
						[
							'type'          => Controls_Manager::TEXT,
							'name'          => 'subtitle',
							'label_block'   => true,
							'label'         => __( 'Subtitle', MELA_TD ),
							'default'       => __( 'item sub-title', MELA_TD )
						],

						[
							'name'          => 'ma_el_image_carousel_buttons',
							'label'        => __( 'Popup or Links ?', MELA_TD ),
							'type'         => Controls_Manager::CHOOSE,
							'options' => [
								'popup' => [
									'title' => __( 'Popup', MELA_TD ),
									'icon' => 'eicon-search',
								],
								'links' => [
									'title' => __( 'External Links', MELA_TD ),
									'icon' => 'eicon-editor-external-link',
								],
							],
							'default' => 'popup',
						],


						[
							'name'          => 'ma_el_image_carousel_button_one_text',
							'label'        => __( 'Button Text', MELA_TD ),
							'type'         => Controls_Manager::TEXT,
							'default'     => __( 'Details', MELA_TD ),
							'placeholder' => __( 'Details', MELA_TD ),
							'title'       => __( 'Enter Button text here', MELA_TD ),
							'condition'     => [
								'ma_el_image_carousel_buttons' => 'links'
							]
						],

						[
							'name'          => 'ma_el_image_carousel_link_one_url',
							'label'        => __( 'Button One URL', MELA_TD ),
							'type'         => Controls_Manager::URL,
							'default'     => [
								'url' => '#',
								'is_external' => true,
								'nofollow' => true,
							],
							'show_external' => true,
							'condition'     => [
								'ma_el_image_carousel_buttons' => 'links'
							]
						],

						[
							'name'          => 'ma_el_image_carousel_button_two_text',
							'label'        => __( 'Button Two Text', MELA_TD ),
							'type'         => Controls_Manager::TEXT,
							'default'     => __( 'Demo', MELA_TD ),
							'placeholder' => __( 'Demo', MELA_TD ),
							'title'       => __( 'Enter Button text here', MELA_TD ),
							'condition'     => [
								'ma_el_image_carousel_buttons' => 'links'
							]
						],

						[
							'name'          => 'ma_el_image_carousel_link_two_url',
							'label'        => __( 'Button Two URL', MELA_TD ),
							'type'         => Controls_Manager::URL,
							'default'     => [
								'url' => '#',
								'is_external' => true,
								'nofollow' => true,
							],
							'show_external' => true,
							'condition'     => [
								'ma_el_image_carousel_buttons' => 'links'
							]
						],





					],
					'title_field' => '{{title}}'
				]
			);

			$this->end_controls_section();



			/*
			 * Image Carousel Settings
			 */


			/* Carousel Settings */
			$this->start_controls_section(
				'section_carousel_settings',
				[
					'label' => esc_html__( 'Carousel Settings', MELA_TD ),
				]
			);

			$slides_per_view = range( 1, 6 );
			$slides_per_view = array_combine( $slides_per_view, $slides_per_view );

			$this->add_control(
				'ma_el_image_carousel_per_view',
				[
					'type'           => Controls_Manager::SELECT,
					'label'          => __( 'Columns', MELA_TD ),
					'options'        => $slides_per_view,
					'default'        => '3',
				]
			);

			$this->add_control(
				'ma_el_image_carousel_slides_to_scroll',
				[
					'type'      => Controls_Manager::SELECT,
					'label'     => __( 'Items to Scroll', MELA_TD ),
					'options'   => $slides_per_view,
					'default'   => '1',
				]
			);

			$this->add_control(
				'ma_el_image_carousel_nav',
				[
					'label' => __( 'Navigation Style', MELA_TD ),
					'type' => Controls_Manager::SELECT,
					'default' => 'arrows',
					'separator' => 'before',
					'options' => [
						'arrows' => __( 'Arrows', MELA_TD ),
						'dots' => __( 'Dots', MELA_TD ),

					],
				]
			);


			$this->start_controls_tabs( 'ma_el_image_carousel_navigation_tabs' );

			$this->start_controls_tab( 'ma_el_image_carousel_navigation_control', [ 'label' => __( 'Normal', MELA_TD
			) ] );

			$this->add_control(
				'ma_el_image_carousel_arrow_color',
				[
					'label' => __( 'Arrow Background', MELA_TD ),
					'type' => Controls_Manager::COLOR,
					'default' => '#b8bfc7',
					'selectors' => [
						'{{WRAPPER}} .ma-el-team-carousel-prev, {{WRAPPER}} .ma-el-team-carousel-next' => 'background: {{VALUE}};',
					],
					'condition' => [
						'ma_el_image_carousel_nav' => 'arrows',
					],
				]
			);

			$this->add_control(
				'ma_el_image_carousel_dot_color',
				[
					'label' => __( 'Dot Color', MELA_TD ),
					'type' => Controls_Manager::COLOR,
					'default' => '#8a8d91',
					'selectors' => [
						'{{WRAPPER}} .ma-el-image-carousel-wrapper .slick-dots li button' => 'background-color: {{VALUE}};',
					],
					'condition' => [
						'ma_el_image_carousel_nav' => 'dots',
					],
				]
			);


			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name'        => 'ma_el_image_carousel_border',
					'placeholder' => '1px',
					'default'     => '0px',
					'selector'    => '{{WRAPPER}} .ma-el-team-carousel-prev, {{WRAPPER}} .ma-el-team-carousel-next'
				]
			);


			$this->end_controls_tab();

			$this->start_controls_tab( 'ma_el_image_carousel_social_icon_hover', [ 'label' => __( 'Hover', MELA_TD )
			] );

			$this->add_control(
				'ma_el_image_carousel_arrow_hover_color',
				[
					'label' => __( 'Arrow Hover', MELA_TD ),
					'type' => Controls_Manager::COLOR,
					'default' => '#917cff',
					'selectors' => [
						'{{WRAPPER}} .ma-el-team-carousel-prev:hover, {{WRAPPER}} .ma-el-team-carousel-next:hover' =>
							'background: {{VALUE}};',
					],
					'condition' => [
						'ma_el_image_carousel_nav' => 'arrows',
					],
				]
			);

			$this->add_control(
				'ma_el_image_carousel_dot_hover_color',
				[
					'label' => __( 'Dot Hover', MELA_TD ),
					'type' => Controls_Manager::COLOR,
					'default' => '#8a8d91',
					'selectors' => [
						'{{WRAPPER}} .ma-el-image-carousel-wrapper .slick-dots li.slick-active button, {{WRAPPER}} .ma-el-image-carousel-wrapper .slick-dots li button:hover' => 'background: {{VALUE}};',
					],
					'condition' => [
						'ma_el_image_carousel_nav' => 'dots',
					],
				]
			);


			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name'        => 'ma_el_image_carousel_hover_border',
					'placeholder' => '1px',
					'default'     => '0px',
					'selector'    => '{{WRAPPER}} .ma-el-team-carousel-prev:hover, {{WRAPPER}} .ma-el-team-carousel-next:hover'
				]
			);

			$this->end_controls_tab();

			$this->end_controls_tabs();

			$this->add_control(
				'ma_el_image_carousel_transition_duration',
				[
					'label'   => __( 'Transition Duration', MELA_TD ),
					'type'    => Controls_Manager::NUMBER,
					'default' => 1000,
					'separator' => 'before',
				]
			);

			$this->add_control(
				'ma_el_image_carousel_autoplay',
				[
					'label'     => __( 'Autoplay', MELA_TD ),
					'type'      => Controls_Manager::SWITCHER,
					'default'   => 'no',
				]
			);

			$this->add_control(
				'ma_el_image_carousel_autoplay_speed',
				[
					'label'     => __( 'Autoplay Speed', MELA_TD ),
					'type'      => Controls_Manager::NUMBER,
					'default'   => 5000,
					'condition' => [
						'ma_el_image_carousel_autoplay' => 'yes',
					],
				]
			);

			$this->add_control(
				'ma_el_image_carousel_loop',
				[
					'label'   => __( 'Infinite Loop', MELA_TD ),
					'type'    => Controls_Manager::SWITCHER,
					'default' => 'yes',
				]
			);

			$this->add_control(
				'ma_el_image_carousel_pause',
				[
					'label'     => __( 'Pause on Hover', MELA_TD ),
					'type'      => Controls_Manager::SWITCHER,
					'default'   => 'yes',
					'condition' => [
						'ma_el_image_carousel_autoplay' => 'yes',
					],
				]
			);

			$this->end_controls_section();



			if ( ma_el_fs()->is_not_paying() ) {

				$this->start_controls_section(
					'maad_el_section_pro',
					[
						'label' => __( 'Upgrade to Pro Version for More Features', MELA_TD )
					]
				);

				$this->add_control(
					'maad_el_control_get_pro',
					[
						'label' => __( 'Unlock more possibilities', MELA_TD ),
						'type' => Controls_Manager::CHOOSE,
						'options' => [
							'1' => [
								'title' => __( '', MELA_TD ),
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

		protected function render(){
			$settings       = $this->get_settings_for_display();

			$this->add_render_attribute(
				'ma_el_image_carousel',
				[
					'class' => [ 'ma-el-image-carousel' ],
					'data-carousel-nav' => $settings['ma_el_image_carousel_nav'],
					'data-slidestoshow' => $settings['ma_el_image_carousel_per_view'],
					'data-slidestoscroll' => $settings['ma_el_image_carousel_slides_to_scroll'],
					'data-speed' => $settings['ma_el_image_carousel_transition_duration'],
				]
			);


			if ( $settings['ma_el_image_carousel_autoplay'] == 'yes' ) {
				$this->add_render_attribute( 'ma_el_image_carousel', 'data-autoplay', "true");
				$this->add_render_attribute( 'ma_el_image_carousel', 'data-autoplayspeed', $settings['ma_el_image_carousel_autoplay_speed'] );
			}

			if ( $settings['ma_el_image_carousel_pause'] == 'yes' ) {
				$this->add_render_attribute( 'ma_el_image_carousel', 'data-pauseonhover', "true" );
			}

			if ( $settings['ma_el_image_carousel_loop'] == 'yes' ) {
				$this->add_render_attribute( 'ma_el_image_carousel', 'data-loop', "true");
			}



			echo '<div class="ma-el-image-carousel-wrapper">';

			if( is_array( $settings['ma_el_image_carousel_items'] ) ):

				echo '<div '. $this->get_render_attribute_string( 'ma_el_image_carousel' ) .'>';

				foreach ( $settings['ma_el_image_carousel_items'] as $item ) :

					if( $item['ma_el_image_carousel_img']['id'] ):

						echo '<div class="ma-el-image-filter-item jltma-col-lg-'. esc_attr($settings['ma_el_image_carousel_per_view']). ' jltma-col-md-6">';

						echo '<div class="ma-image-hover-thumb">';

						echo $this->render_image( $item['ma_el_image_carousel_img']['id'], $settings );

						echo '<div class="ma-image-hover-content">';

						if( $item['ma_el_image_carousel_buttons'] == "popup" ){
							echo '<a class="ma-el-fancybox elementor-clickable" href="'. esc_url(
									$item['ma_el_image_carousel_img']['url'] ) .'" data-fancybox="gallery" aria-label="Fancybox Popup"><i class="eicon-preview"></i></a>';

						} elseif( $item['ma_el_image_carousel_buttons'] == "links" ){


							// Link One
							$this->add_render_attribute( 'ma_el_image_carousel_link_one', [
								'class'	=> [
									'button',
									'ma-el-creative-button',
									'ma-el-creative-button--default'
								],
								'href'	=> esc_url($item['ma_el_image_carousel_link_one_url']['url'] ),
							]);

							if( $item['ma_el_image_carousel_link_one_url']['is_external'] ) {
								$this->add_render_attribute( 'ma_el_image_carousel_link_one', 'target', '_blank' );
							}

							if( $item['ma_el_image_carousel_link_one_url']['nofollow'] ) {
								$this->add_render_attribute( 'ma_el_image_carousel_link_one', 'rel', 'nofollow' );
							}


							// Link Two
							$this->add_render_attribute( 'ma_el_image_carousel_link_two', [
								'class'	=> [
									'button',
									'ma-el-creative-button',
									'ma-el-creative-button--default'
								],
								'href'	=> esc_url($item['ma_el_image_carousel_link_two_url']['url'] ),
							]);

							if( $item['ma_el_image_carousel_link_two_url']['is_external'] ) {
								$this->add_render_attribute( 'ma_el_image_carousel_link_two', 'target', '_blank' );
							}

							if( $item['ma_el_image_carousel_link_two_url']['nofollow'] ) {
								$this->add_render_attribute( 'ma_el_image_carousel_link_two', 'rel', 'nofollow' );
							}


							if( $item['ma_el_image_carousel_link_one_url']['url'] !=""){
								echo '<a ' . $this->get_render_attribute_string( 'ma_el_image_carousel_link_one' ) . '>' .
								     esc_html( $item['ma_el_image_carousel_button_one_text']) . '</a>';
							}

							if( $item['ma_el_image_carousel_link_two_url']['url'] !=""){
								echo '<a ' . $this->get_render_attribute_string( 'ma_el_image_carousel_link_two' ) . '>' .
								     esc_html( $item['ma_el_image_carousel_button_two_text']) . '</a>';
							}


						}

						echo '</div><!--.ma-image-hover-content-->';
						echo '</div><!--.ma-image-hover-thumb-->';
						echo '<div class="ma-image-hover-content-details">';

						if( $settings['ma_el_image_carousel_title'] == "yes"){
							echo '<h3 class="ma-el-image-hover-title">'. esc_html( $item['title'] ) .'</h3>';
						}

						if( $settings['ma_el_image_carousel_subtitle'] == "yes"){
							echo '<span class="ma-el-image-hover-desc">'. esc_html( $item['subtitle'] ) .'</span>';
						}

						echo '</div><!--.ma-image-hover-content-details-->';

						echo '</div>';

					endif;
				endforeach;

				echo '</div>';
			endif;
			echo '</div>';
		}

		private function render_image( $image_id, $settings ) {
			$ma_el_image_carousel_image = $settings['ma_el_image_carousel_image_size'];
			if ( 'custom' === $ma_el_image_carousel_image ) {
				$image_src = Group_Control_Image_Size::get_attachment_image_src( $image_id, 'ma_el_image_carousel_image', $settings );
			} else {
				$image_src = wp_get_attachment_image_src( $image_id, $ma_el_image_carousel_image );
				$image_src = $image_src[0];
			}

			return sprintf( '<img src="%s" alt="%s" />', esc_url($image_src), esc_html( get_post_meta( $image_id, '_wp_attachment_image_alt', true) ) );
		}


	}

	Plugin::instance()->widgets_manager->register_widget_type( new Master_Addons_Image_Carousel());
