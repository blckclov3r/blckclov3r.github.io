<?php
/**
 * News Ticker widget class
 *
 * @package Happy_Addons
 */

namespace Happy_Addons\Elementor\Widget;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Typography;
use Elementor\Group_Control_Background;

defined( 'ABSPATH' ) || die();

class News_Ticker extends Base {

	/**
	 * Get widget title.
	 *
	 * @return string Widget title.
	 * @since 1.0.0
	 * @access public
	 *
	 */
	public function get_title () {
		return __( 'News Ticker', 'happy-elementor-addons' );
	}

	public function get_custom_help_url() {
		return 'https://happyaddons.com/docs/happy-addons-for-elementor/widgets/news-ticker/';
	}

	/**
	 * Get widget icon.
	 *
	 * @return string Widget icon.
	 * @since 1.0.0
	 * @access public
	 *
	 */
	public function get_icon () {
		return 'hm hm-slider';
	}

	public function get_keywords () {
		return [ 'news', 'news-ticker', 'ticker', 'text-slider', 'slider' ];
	}

	/**
	 * Get a list of all WPForms
	 *
	 * @return array
	 */
	public function ha_get_posts () {
		$posts = [];
		$_posts = get_posts( [
			'post_type' => 'post',
			'post_status' => 'publish',
			'posts_per_page' => -1,
			'orderby' => 'title',
			'order' => 'ASC',
		] );

		if ( ! empty( $_posts ) ) {
			$posts = wp_list_pluck( $_posts, 'post_title', 'ID' );
		}
		return $posts;

	}

	protected function register_content_controls () {
		$this->start_controls_section(
			'_section_news_ticker',
			[
				'label' => __( 'News Ticker', 'happy-elementor-addons' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'sticky_title',
			[
				'label' => __( 'Sticky Title', 'happy-elementor-addons' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Breaking News', 'happy-elementor-addons' ),
                'dynamic' => [
                    'active' => true,
                ]
			]
		);

		$this->add_control(
			'sticky_title_position',
			[
				'label' => __( 'Sticky Title Position', 'happy-elementor-addons' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __( 'Left', 'happy-elementor-addons' ),
						'icon' => 'eicon-h-align-left',
					],
					'right' => [
						'title' => __( 'Right', 'happy-elementor-addons' ),
						'icon' => 'eicon-h-align-right',
					],
				],
				'default' => 'left',
				'toggle' => false,
				'style_transfer' => true,
				'selectors' => [
					'{{WRAPPER}}  .ha-news-ticker-wrapper  span.ha-news-ticker-sticky-title' => '{{VALUE}};'
				],
				'selectors_dictionary' => [
					'left' => 'left: 0',
					'right' => 'right: 0'
				],
				'condition' => [
					'sticky_title!' => '',
				]
			]
		);

		$this->add_control(
			'selected_posts',
			[
				'label' => __( 'Select Posts', 'happy-elementor-addons' ),
				'label_block' => true,
				'type' => Controls_Manager::SELECT2,
				'default' => '',
				'options' => $this->ha_get_posts(),
				'multiple' => true,
			]
		);

		$this->add_control(
			'slide_direction',
			[
				'label' => __( 'Slide direction', 'happy-elementor-addons' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __( 'Left', 'happy-elementor-addons' ),
						'icon' => 'eicon-h-align-left',
					],
					'right' => [
						'title' => __( 'Right', 'happy-elementor-addons' ),
						'icon' => 'eicon-h-align-right',
					],
				],
				'default' => 'left',
				'toggle' => false,
				'style_transfer' => true,
			]
		);

		$this->add_control(
			'item_space',
			[
				'label' => __( 'Space between items', 'happy-elementor-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => -100,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .ha-news-ticker-wrapper .ha-news-ticker-item' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .ha-news-ticker-wrapper .ha-news-ticker-item:last-child' => 'margin-right: 0;',
				],
				'style_transfer' => true,
			]
		);

		$this->add_control(
			'speed',
			[
				'label' => __( 'Slide Speed', 'happy-elementor-addons' ),
				'description' => __( 'Autoplay speed in seconds. Default 30', 'happy-elementor-addons' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 1,
				'max' => 10000,
				'default' => 30,
				'frontend_available' => true,
			]
		);

		$this->end_controls_section();
	}

	protected function register_style_controls () {

		$this->start_controls_section(
			'_style_news_ticker_wrapper',
			[
				'label' => __( 'Wrapper', 'happy-elementor-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'wrapper_background',
				'label' => __( 'Background', 'happy-elementor-addons' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .ha-news-ticker-wrapper',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'wrapper_border',
				'label' => __( 'Border', 'happy-elementor-addons' ),
				'selector' => '{{WRAPPER}} .ha-news-ticker-wrapper',
			]
		);

		$this->add_control(
			'wrapper_border_radius',
			[
				'label' => __( 'Border Radius', 'happy-elementor-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .ha-news-ticker-wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'wrapper_box_shadow',
				'label' => __( 'Box Shadow', 'happy-elementor-addons' ),
				'selector' => '{{WRAPPER}} .ha-news-ticker-wrapper',
			]
		);

		$this->add_responsive_control(
			'wrapper_padding',
			[
				'label' => __( 'Padding', 'happy-elementor-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .ha-news-ticker-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'sticky_title_position_left',
			[
				'label' => __( 'Sticky Title Position Left', 'happy-elementor-addons' ),
				'type' => Controls_Manager::HIDDEN,
				'default' => 'left',
				'selectors' => [
					'(desktop){{WRAPPER}}  .ha-news-ticker-wrapper  span.ha-news-ticker-sticky-title' => 'left: {{wrapper_padding.LEFT || 0}}{{wrapper_padding.UNIT}}; right:auto;',
					'(tablet){{WRAPPER}}  .ha-news-ticker-wrapper  span.ha-news-ticker-sticky-title' => 'left: {{wrapper_padding_tablet.LEFT}}{{wrapper_padding_tablet.UNIT}}; right:auto;',
					'(mobile){{WRAPPER}}  .ha-news-ticker-wrapper  span.ha-news-ticker-sticky-title' => 'left: {{wrapper_padding_mobile.LEFT}}{{wrapper_padding_mobile.UNIT}}; right:auto;',
				],
				'condition' => [
					'sticky_title!' => '',
					'sticky_title_position' => 'left',
				]
			]
		);

		$this->add_control(
			'sticky_title_position_right',
			[
				'label' => __( 'Sticky Title Position Right', 'happy-elementor-addons' ),
				'type' => Controls_Manager::HIDDEN,
				'default' => 'right',
				'selectors' => [
					'(desktop){{WRAPPER}}  .ha-news-ticker-wrapper  span.ha-news-ticker-sticky-title' => 'right: {{wrapper_padding.RIGHT || 0}}{{wrapper_padding.UNIT}}; left:auto;',
					'(tablet){{WRAPPER}}  .ha-news-ticker-wrapper  span.ha-news-ticker-sticky-title' => 'right: {{wrapper_padding_tablet.RIGHT}}{{wrapper_padding_tablet.UNIT}}; left:auto;',
					'(mobile){{WRAPPER}}  .ha-news-ticker-wrapper  span.ha-news-ticker-sticky-title' => 'right: {{wrapper_padding_mobile.RIGHT}}{{wrapper_padding_mobile.UNIT}}; left:auto;',
				],
				'condition' => [
					'sticky_title!' => '',
					'sticky_title_position' => 'right',
				]
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'_style_news_ticker_sticky_title',
			[
				'label' => __( 'Sticky Title', 'happy-elementor-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'sticky_title_color',
			[
				'label' => __( 'Title Color', 'happy-elementor-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ha-news-ticker-wrapper  span.ha-news-ticker-sticky-title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'sticky_title_typography',
				'label' => __( 'Typography', 'happy-elementor-addons' ),
				'scheme' => Scheme_Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .ha-news-ticker-wrapper  span.ha-news-ticker-sticky-title',
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'sticky_title_background',
				'label' => __( 'Background', 'happy-elementor-addons' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .ha-news-ticker-wrapper span.ha-news-ticker-sticky-title',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'sticky_title_border',
				'label' => __( 'Border', 'happy-elementor-addons' ),
				'selector' => '{{WRAPPER}} .ha-news-ticker-wrapper span.ha-news-ticker-sticky-title',
			]
		);

		$this->add_control(
			'sticky_title_border_radius',
			[
				'label' => __( 'Border Radius', 'happy-elementor-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .ha-news-ticker-wrapper span.ha-news-ticker-sticky-title' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'sticky_title_padding',
			[
				'label' => __( 'Padding', 'happy-elementor-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .ha-news-ticker-wrapper span.ha-news-ticker-sticky-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'_style_news_ticker_title',
			[
				'label' => __( 'Title', 'happy-elementor-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs( '_tabs_title' );

		$this->start_controls_tab(
			'_tab_title_normal',
			[
				'label' => __( 'Normal', 'happy-elementor-addons' ),
			]
		);

		$this->add_control(
			'title_color',
			[
				'label' => __( 'Title Color', 'happy-elementor-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ha-news-ticker-wrapper  li.ha-news-ticker-item a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'_tab_title_hover',
			[
				'label' => __( 'Hover', 'happy-elementor-addons' ),
			]
		);

		$this->add_control(
			'title_hover_color',
			[
				'label' => __( 'Title Color', 'happy-elementor-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ha-news-ticker-wrapper  li.ha-news-ticker-item a:hover, {{WRAPPER}} .ha-news-ticker-wrapper  li.ha-news-ticker-item a:focus' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography',
				'label' => __( 'Typography', 'happy-elementor-addons' ),
				'scheme' => Scheme_Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .ha-news-ticker-wrapper  li.ha-news-ticker-item .ha-news-ticker-title',
			]
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'title_shadow',
				'label' => __( 'Title Shadow', 'happy-elementor-addons' ),
				'selector' => '{{WRAPPER}} .ha-news-ticker-wrapper  li.ha-news-ticker-item a',
				'style_transfer' => true,
			]
		);

		$this->end_controls_section();
	}

	protected function render () {

		$settings = $this->get_settings_for_display();
		if ( empty( $settings['selected_posts'] ) ) {
			return;
		}
		$query_args = [
			'post_type' => 'post',
			'post_status' => 'publish',
			'ignore_sticky_posts' => 1,
			'post__in' => (array) $settings['selected_posts'],
		];

		$news_posts = [];
		$the_query = get_posts( $query_args );
		if ( ! empty( $the_query ) ) {
			$news_posts = wp_list_pluck( $the_query, 'post_title', 'ID' );
		}

		$this->add_render_attribute( 'wrapper', 'class', [ 'ha-news-ticker-wrapper' ] );
		$this->add_render_attribute( 'wrapper', 'data-duration', $settings['speed'] ? ( $settings['speed'] * '1000' ) : '30000' );
		$this->add_render_attribute( 'wrapper', 'data-scroll-direction', $settings['slide_direction'] );
		$this->add_render_attribute( 'container', 'class', [ 'ha-news-ticker-container' ] );
		$this->add_render_attribute( 'item', 'class', [ 'ha-news-ticker-item' ] );

		if ( count( $news_posts ) !== 0 ) :?>
			<div <?php $this->print_render_attribute_string( 'wrapper' ); ?>>
				<?php if ( $settings['sticky_title'] ): ?>
					<span class="ha-news-ticker-sticky-title">
						<?php echo esc_html( $settings['sticky_title'] ); ?>
					</span>
				<?php endif; ?>
				<ul <?php $this->print_render_attribute_string( 'container' ); ?>>
					<?php foreach ( $news_posts as $key => $value ): ?>
						<li <?php $this->print_render_attribute_string( 'item' ); ?>>
							<h2 class="ha-news-ticker-title">
								<a href="<?php echo esc_url( get_the_permalink($key) ); ?>">
									<?php echo esc_html( $value ); ?>
								</a>
							</h2>
						</li>
					<?php endforeach; ?>
				</ul>
			</div>
		<?php
		endif;
	}
}
