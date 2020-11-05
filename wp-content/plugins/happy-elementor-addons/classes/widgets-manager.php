<?php
namespace Happy_Addons\Elementor;

use Elementor\Element_Base;

defined( 'ABSPATH' ) || die();

class Widgets_Manager {

	const WIDGETS_DB_KEY = 'happyaddons_inactive_widgets';

	/**
	 * Initialize
	 */
	public static function init() {
		add_action( 'elementor/widgets/widgets_registered', [ __CLASS__, 'register' ] );
		add_action( 'elementor/frontend/before_render', [ __CLASS__, 'add_global_widget_render_attributes' ] );
	}

	public static function add_global_widget_render_attributes( Element_Base $widget ) {
		if ( $widget->get_data( 'widgetType' ) === 'global' && method_exists( $widget, 'get_original_element_instance' ) ) {
			$original_instance = $widget->get_original_element_instance();
			if ( method_exists( $original_instance, 'get_html_wrapper_class' ) && strpos( $original_instance->get_data( 'widgetType' ), 'ha-' ) !== false ) {
				$widget->add_render_attribute( '_wrapper', [
					'class' => $original_instance->get_html_wrapper_class(),
				] );
			}
		}
	}

	public static function get_inactive_widgets() {
		return get_option( self::WIDGETS_DB_KEY, [] );
	}

	public static function save_inactive_widgets( $widgets = [] ) {
		update_option( self::WIDGETS_DB_KEY, $widgets );
	}

	public static function get_widgets_map() {
		$widgets_map = [
			self::get_base_widget_key() => [
				'css' => ['common'],
				'js' => [],
				'vendor' => [
					'js' => ['anime'],
					'css' => ['happy-icons', 'font-awesome']
				]
			],
		];

		$local_widgets_map = self::get_local_widgets_map();
		$widgets_map = array_merge( $widgets_map, $local_widgets_map );

		return apply_filters( 'happyaddons_get_widgets_map', $widgets_map );
	}

	/**
	 * Get the pro widgets map for dashboard only
	 *
	 * @return array
	 */
	public static function get_pro_widget_map() {
		return [
			'advanced-heading' => [
				'title' => __( 'Advanced Heading', 'happy-elementor-addons' ),
				'icon' => 'hm hm-advanced-heading',
				'is_pro' => true,
			],
			'list-group' => [
				'title' => __( 'List Group', 'happy-elementor-addons' ),
				'icon' => 'hm hm-list-group',
				'is_pro' => true,
			],
			'hover-box' => [
				'title' => __( 'Hover Box', 'happy-elementor-addons' ),
				'icon' => 'hm hm-finger-point',
				'is_pro' => true,
			],
			'countdown' => [
				'title' => __( 'Countdown', 'happy-elementor-addons' ),
				'icon' => 'hm hm-refresh-time',
				'is_pro' => true,
			],
			'team-carousel' => [
				'title' => __( 'Team Carousel', 'happy-elementor-addons' ),
				'icon' => 'hm hm-team-carousel',
				'is_pro' => true,
			],
			'logo-carousel' => [
				'title' => __( 'Logo Carousel', 'happy-elementor-addons' ),
				'icon' => 'hm hm-logo-carousel',
				'is_pro' => true,
			],
			'source-code' => [
				'title' => __( 'Source Code', 'happy-elementor-addons' ),
				'icon' => 'hm hm-code-browser',
				'is_pro' => true,
			],
			'feature-list' => [
				'title' => __( 'Feature List', 'happy-elementor-addons' ),
				'icon' => 'hm hm-list-2',
				'is_pro' => true,
			],
			'testimonial-carousel' => [
				'title' => __( 'Testimonial Carousel', 'happy-elementor-addons' ),
				'icon' => 'hm hm-testimonial-carousel',
				'is_pro' => true,
			],
			'advanced-tabs' => [
				'title' => __( 'Advanced Tabs', 'happy-elementor-addons' ),
				'icon' => 'hm hm-tab',
				'is_pro' => true,
			],
			'animated-text' => [
				'title' => __( 'Animated Text', 'happy-elementor-addons' ),
				'icon' => 'hm hm-text-animation',
				'is_pro' => true,
			],
			'timeline' => [
				'title' => __( 'Timeline', 'happy-elementor-addons' ),
				'icon' => 'hm hm-timeline',
				'is_pro' => true,
			],
			'instagram-feed' => [
				'title' => __( 'Instagram Feed', 'happy-elementor-addons' ),
				'icon' => 'hm hm-instagram',
				'is_pro' => true,
			],
			'scrolling-image' => [
				'title' => __( 'Scrolling Image', 'happy-elementor-addons' ),
				'icon' => 'hm hm-scrolling-image',
				'is_pro' => true,
			],
			'toggle' => [
				'title' => __( 'Advanced Toggle', 'happy-elementor-addons' ),
				'icon' => 'hm hm-accordion-vertical',
				'is_pro' => true,
			],
			'accordion' => [
				'title' => __( 'Advanced Accordion', 'happy-elementor-addons' ),
				'icon' => 'hm hm-accordion-vertical',
				'is_pro' => true,
			],
			'advanced-pricing-table' => [
				'title' => __( 'Advanced Pricing Table', 'happy-elementor-addons'),
				'icon' => 'hm hm-file-cabinet',
				'is_pro' => true,
			],
			'advanced-flip-box' => [
				'title' => __( 'Advanced Flip Box', 'happy-elementor-addons' ),
				'icon' => 'hm hm-flip-card1',
				'is_pro' => true,
			],
			'business-hour' => [
				'title' => __( 'Business Hour', 'happy-elementor-addons' ),
				'icon' => 'hm hm-hand-watch',
				'is_pro' => true,
			],
			'promo-box' => [
				'title' => __( 'Promo Box', 'happy-elementor-addons' ),
				'icon' => 'hm hm-promo',
				'is_pro' => true,
			],
		];
	}

	/**
	 * Get the free widgets map
	 *
	 * @return array
	 */
	public static function get_local_widgets_map() {
		// All the widgets are listed below with respective map

		return [
			'infobox' => [
				'demo' => 'https://happyaddons.com/go/demo-info-box',
				'title' => __( 'Info Box', 'happy-elementor-addons' ),
				'icon' => 'hm hm-blog-content',
				'css' => ['btn', 'infobox', ],
				'js' => [],
				'vendor' => [
					'css' => [],
					'js' => [],
				],
			],
			'card' => [
				'demo' => 'https://happyaddons.com/go/demo-card',
				'title' => __( 'Card', 'happy-elementor-addons' ),
				'icon' => 'hm hm-card',
				'css' => ['btn', 'badge', 'card'],
				'js' => [],
				'vendor' => [
					'css' => [],
					'js' => [],
				],
			],
			'cf7' => [
				'demo' => 'https://happyaddons.com/go/demo-contact-form7',
				'title' => __( 'Contact Form 7', 'happy-elementor-addons' ),
				'icon' => 'hm hm-form',
				'css' => [],
				'js' => [],
				'vendor' => [
					'css' => [],
					'js' => [],
				],
			],
			'icon-box' => [
				'demo' => 'https://happyaddons.com/go/demo-icon-box',
				'title' => __( 'Icon Box', 'happy-elementor-addons' ),
				'icon' => 'hm hm-icon-box',
				'css' => ['badge', 'icon-box'],
				'js' => [],
				'vendor' => [
					'css' => [],
					'js' => [],
				],
			],
			'member' => [
				'demo' => 'https://happyaddons.com/go/demo-team-member',
				'title' => __( 'Team Member', 'happy-elementor-addons' ),
				'icon' => 'hm hm-team-member',
				'css' => ['btn', 'member'],
				'js' => [],
				'vendor' => [
					'css' => [],
					'js' => [],
				],
			],
			'review' => [
				'demo' => 'https://happyaddons.com/go/demo-review',
				'title' => __( 'Review', 'happy-elementor-addons' ),
				'icon' => 'hm hm-review',
				'css' => ['review'],
				'js' => [],
				'vendor' => [
					'css' => [],
					'js' => [],
				],
			],
			'image-compare' => [
				'demo' => 'https://happyaddons.com/go/demo-image-compare',
				'title' => __( 'Image Compare', 'happy-elementor-addons' ),
				'icon' => 'hm hm-image-compare',
				'css' => ['image-comparison'],
				'js' => [],
				'vendor' => [
					'css' => ['twentytwenty'],
					'js' => ['jquery-event-move','jquery-twentytwenty', 'imagesloaded'],
				],
			],
			'justified-gallery' => [
				'demo' => 'https://happyaddons.com/go/demo-justified-grid',
				'title' => __( 'Justified Grid', 'happy-elementor-addons' ),
				'icon' => 'hm hm-brick-wall',
				'css' => ['justified-gallery', 'gallery-filter'],
				'js' => [],
				'vendor' => [
					'css' => ['justifiedGallery', 'magnific-popup'],
					'js' => ['jquery-justifiedGallery', 'jquery-magnific-popup'],
				],
			],
			'image-grid' => [
				'demo' => 'https://happyaddons.com/go/demo-image-grid',
				'title' => __( 'Image Grid', 'happy-elementor-addons' ),
				'icon' => 'hm hm-grid-even',
				'css' => ['image-grid', 'gallery-filter'],
				'js' => [],
				'vendor' => [
					'css' => ['magnific-popup'],
					'js' => ['jquery-isotope', 'jquery-magnific-popup', 'imagesloaded'],
				],
			],
			'slider' => [
				'demo' => 'https://happyaddons.com/go/demo-slider',
				'title' => __( 'Slider', 'happy-elementor-addons' ),
				'icon' => 'hm hm-image-slider',
				'css' => ['slider-carousel'],
				'js' => [],
				'vendor' => [
					'css' => ['slick', 'slick-theme'],
					'js' => ['jquery-slick'],
				],
			],
			'carousel' => [
				'demo' => 'https://happyaddons.com/go/demo-image-carousel',
				'title' => __( 'Carousel', 'happy-elementor-addons' ),
				'icon' => 'hm hm-carousal',
				'css' => ['slider-carousel'],
				'js' => [],
				'vendor' => [
					'css' => ['slick', 'slick-theme'],
					'js' => ['jquery-slick'],
				],
			],
			'skills' => [
				'demo' => 'https://happyaddons.com/go/demo-skill-bar',
				'title' => __( 'Skill Bars', 'happy-elementor-addons' ),
				'icon' => 'hm hm-progress-bar',
				'css' => ['skills'],
				'js' => [],
				'vendor' => [
					'css' => [],
					'js' => ['elementor-waypoints', 'jquery-numerator'],
				],
			],
			'gradient-heading' => [
				'demo' => 'https://happyaddons.com/go/demo-gradient-heading',
				'title' => __( 'Gradient Heading', 'happy-elementor-addons' ),
				'icon' => 'hm hm-drag',
				'css' => ['gradient-heading'],
				'js' => [],
				'vendor' => [
					'css' => [],
					'js' => [],
				],
			],
			'wpform' => [
				'demo' => 'https://happyaddons.com/go/demo-wpforms',
				'title' => __( 'WPForms', 'happy-elementor-addons' ),
				'icon' => 'hm hm-form',
				'css' => [],
				'js' => [],
				'vendor' => [
					'css' => [],
					'js' => [],
				],
			],
			'ninjaform' => [
				'demo' => 'https://happyaddons.com/go/demo-ninja-forms',
				'title' => __( 'Ninja Forms', 'happy-elementor-addons' ),
				'icon' => 'hm hm-form',
				'css' => [],
				'js' => [],
				'vendor' => [
					'css' => [],
					'js' => [],
				],
			],
			'calderaform' => [
				'demo' => 'https://happyaddons.com/go/demo-caldera-forms',
				'title' => __( 'Caldera Forms', 'happy-elementor-addons' ),
				'icon' => 'hm hm-form',
				'css' => [],
				'js' => [],
				'vendor' => [
					'css' => [],
					'js' => [],
				],
			],
			'weform' => [
				'demo' => 'https://happyaddons.com/go/demo-weforms',
				'title' => __( 'weForms', 'happy-elementor-addons' ),
				'icon' => 'hm hm-form',
				'css' => [],
				'js' => [],
				'vendor' => [
					'css' => [],
					'js' => [],
				],
			],
			'logo-grid' => [
				'demo' => 'https://happyaddons.com/go/demo-logo-grid',
				'title' => __('Logo Grid', 'happy-elementor-addons'),
				'icon' => 'hm hm-logo-grid',
				'css' => ['logo-grid'],
				'js' => [],
				'vendor' => [
					'css' => [],
					'js' => [],
				],
			],
			'dual-button' => [
				'demo' => 'https://happyaddons.com/go/demo-dual-button',
				'title' => __( 'Dual Button', 'happy-elementor-addons' ),
				'icon' => 'hm hm-accordion-horizontal',
				'css' => ['dual-btn'],
				'js' => [],
				'vendor' => [
					'css' => [],
					'js' => [],
				],
			],
			'testimonial' => [
				'demo' => 'https://happyaddons.com/go/demo-testimonial',
				'title' => __( 'Testimonial', 'happy-elementor-addons' ),
				'icon' => 'hm hm-testimonial',
				'css' => ['testimonial'],
				'js' => [],
				'vendor' => [
					'css' => [],
					'js' => [],
				],
			],
			'number' => [
				'demo' => 'https://happyaddons.com/go/demo-number-widget',
				'title' => __( 'Number', 'happy-elementor-addons' ),
				'icon' => 'hm hm-madel',
				'css' => ['number'],
				'js' => [],
				'vendor' => [
					'css' => [],
					'js' => ['elementor-waypoints', 'jquery-numerator'],
				],
			],
			'flip-box' => [
				'demo' => 'https://happyaddons.com/gp/demo-flip-box',
				'title' => __( 'Flip Box', 'happy-elementor-addons' ),
				'icon' => 'hm hm-flip-card1',
				'css' => ['flip-box'],
				'js' => [],
				'vendor' => [
					'css' => [],
					'js' => [],
				],
			],
			'calendly' => [
				'demo' => 'https://happyaddons.com/go/demo-calendly',
				'title' => __( 'Calendly', 'happy-elementor-addons' ),
				'icon' => 'hm hm-calendar',
				'css' => [],
				'js' => [],
				'vendor' => [
					'css' => [],
					'js' => [],
				],
			],
			'pricing-table' => [
				'demo' => 'https://happyaddons.com/go/demo-pricing-table',
				'title' => __( 'Pricing Table', 'happy-elementor-addons' ),
				'icon' => 'hm hm-file-cabinet',
				'css' => ['pricing-table'],
				'js' => [],
				'vendor' => [
					'css' => [],
					'js' => [],
				],
			],
			'step-flow' => [
				'demo' => 'https://happyaddons.com/go/demo-step-flow',
				'title' => __( 'Step Flow', 'happy-elementor-addons' ),
				'icon' => 'hm hm-step-flow',
				'css' => ['steps-flow'],
				'js' => [],
				'vendor' => [
					'css' => [],
					'js' => [],
				],
			],
			'gravityforms' => [
				'title' => __( 'Gravity Forms', 'happy-elementor-addons' ),
				'icon' => 'hm hm-form',
				'css' => [],
				'js' => [],
				'vendor' => [
					'css' => [],
					'js' => [],
				],
			],
			'news-ticker' => [
				'title' => __( 'News Ticker', 'happy-elementor-addons' ),
				'icon' => 'hm hm-slider',
				'css' => ['news-ticker'],
				'js' => [],
				'vendor' => [
					'css' => [],
					'js' => ['jquery-keyframes'],
				],
			],
			'fun-factor' => [
				'title' => __( 'Fun Factor', 'happy-elementor-addons' ),
				'icon' => 'hm hm-slider',
				'css' => ['fun-factor'],
				'js' => [],
				'vendor' => [
					'css' => [],
					'js' => ['elementor-waypoints', 'jquery-numerator'],
				],
			],
			'bar-chart' => [
				'demo' => '',
				'title' => __( 'Bar Chart', 'happy-elementor-addons' ),
				'icon' => 'hm hm-graph-bar',
				'css' => ['chart'],
				'js' => [],
				'vendor' => [
					'css' => [],
					'js' => ['chart-js'],
				],
			],
			'social-icons' => [
				'title' => __( 'Social Icons', 'happy-elementor-addons' ),
				'icon' => 'hm hm-bond2',
				'css' => ['social-icons'],
				'js' => [],
				'vendor' => [
					'css' => ['hover-css'],
					'js' => [],
				]
			],
			'twitter-feed' => [
				'title' => __( 'Twitter Feed', 'happy-elementor-addons' ),
				'icon' => 'hm hm-twitter-feed',
				'css' => ['twitter-feed'],
				'js' => [],
				'vendor' => [
					'css' => [],
					'js' => [],
				],
			],
			'post-list' => [
				'title' => __( 'Post List', 'happy-elementor-addons' ),
				'icon' => 'hm hm-post-list',
				'css' => ['post-list'],
				'js' => [],
				'vendor' => [
					'css' => [],
					'js' => [],
				],
			],
			'post-tab' => [
				'title' => __( 'Post Tab', 'happy-elementor-addons' ),
				'icon' => 'hm hm-post-tab',
				'css' => ['post-tab'],
				'js' => [],
				'vendor' => [
					'css' => [],
					'js' => [],
				],
			],
			'taxonomy-list' => [
				'title' => __( 'Taxonomy List', 'happy-elementor-addons' ),
				'icon' => 'hm hm-clip-board',
				'css' => ['taxonomy-list'],
				'js' => [],
				'vendor' => [
					'css' => [],
					'js' => [],
				],
			],
			'threesixty-rotation' => [
				'title' => __( 'Threesixty Rotation', 'happy-elementor-addons' ),
				'icon' => 'hm hm-3d-rotate',
				'css' => ['threesixty-rotation'],
				'js' => [],
				'vendor' => [
					'css' => [],
					'js' => ['circlr','ha-simple-magnify'],
				],
			],
			'fluent-form' => [
		        'title' => __( 'Fluent Form', 'happy-elementor-addons' ),
		        'icon' => 'hm hm-form',
		        'css' => [],
		        'js' => [],
		        'vendor' => [
			        'css' => [],
			        'js' => [],
		        ],
	        ],
			'data-table' => [
				'title' => __( 'Data Table', 'happy-elementor-addons' ),
				'icon' => 'hm hm-data-table',
				'css' => ['data-table'],
				'js' => [],
				'vendor' => [
					'css' => [],
					'js' => [],
				],
			],
        ];
    }

	public static function get_base_widget_key() {
		return apply_filters( 'happyaddons_get_base_widget_key', '_happyaddons_base' );
	}

	/**
	 * Init Widgets
	 *
	 * Include widgets files and register them
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public static function register() {
		include_once( HAPPY_ADDONS_DIR_PATH . 'base/widget-base.php' );
		include_once( HAPPY_ADDONS_DIR_PATH . 'traits/button-renderer.php' );

		$inactive_widgets = self::get_inactive_widgets();

		foreach ( self::get_local_widgets_map() as $widget_key => $data ) {
			if ( ! in_array( $widget_key, $inactive_widgets ) ) {
				self::register_widget( $widget_key );
			}
		}
	}

	protected static function register_widget( $widget_key ) {
		$widget_file = HAPPY_ADDONS_DIR_PATH . 'widgets/' . $widget_key . '/widget.php';

		if ( is_readable( $widget_file ) ) {

			include_once( $widget_file );

			$widget_class = '\Happy_Addons\Elementor\Widget\\' . str_replace( '-', '_', $widget_key );
			if ( class_exists( $widget_class ) ) {
				ha_elementor()->widgets_manager->register_widget_type( new $widget_class );
			}
		}
	}
}

Widgets_Manager::init();
