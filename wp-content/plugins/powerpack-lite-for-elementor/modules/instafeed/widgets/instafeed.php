<?php
namespace PowerpackElementsLite\Modules\Instafeed\Widgets;

use PowerpackElementsLite\Base\Powerpack_Widget;

// Elementor Classes
use Elementor\Controls_Manager;
use Elementor\Utils;
use Elementor\Icons_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Typography;
use Elementor\Scheme_Color;
use Elementor\Modules\DynamicTags\Module as TagsModule;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Instagram Feed Widget
 */
class Instafeed extends Powerpack_Widget {

	/**
	 * Retrieve instagram feed widget name.
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return parent::get_widget_name( 'Instafeed' );
	}

	/**
	 * Retrieve instagram feed widget title.
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return parent::get_widget_title( 'Instafeed' );
	}

	/**
	 * Retrieve instagram feed widget icon.
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return parent::get_widget_icon( 'Instafeed' );
	}

	/**
	 * Get widget keywords.
	 *
	 * Retrieve the list of keywords the widget belongs to.
	 *
	 * @access public
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return parent::get_widget_keywords( 'Instafeed' );
	}

	/**
	 * Retrieve the list of scripts the instagram feed widget depended on.
	 *
	 * Used to set scripts dependencies required to run the widget.
	 *
	 * @access public
	 *
	 * @return array Widget scripts dependencies.
	 */
	public function get_script_depends() {
		return array(
			'isotope',
			'instafeed',
			'imagesloaded',
			'pp-instagram',
			'magnific-popup',
			'powerpack-frontend',
		);
	}

	/**
	 * Register instagram feed widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @access protected
	 */
	protected function _register_controls() {

		/**
		 * Content Tab: Instagram Account
		 */
		$this->start_controls_section(
			'section_instaaccount',
			array(
				'label' => __( 'Instagram Account', 'powerpack' ),
			)
		);

		$this->add_control(
			'api_info',
			array(
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => __( 'Starting October 15, 2019, new client registration and permission review on Instagram API platform are discontinued.', 'powerpack' ),
				'separator'       => 'after',
				'content_classes' => 'pp-editor-info',
			)
		);

		$this->add_control(
			'use_api',
			array(
				'label'              => __( 'Use Instagram API', 'powerpack' ),
				'type'               => Controls_Manager::SWITCHER,
				'default'            => 'yes',
				'label_on'           => __( 'Yes', 'powerpack' ),
				'label_off'          => __( 'No', 'powerpack' ),
				'return_value'       => 'yes',
				'frontend_available' => true,
			)
		);

		$this->add_control(
			'user_id',
			array(
				'label'     => __( 'User ID', 'powerpack' ),
				'type'      => Controls_Manager::TEXT,
				'condition' => array(
					'use_api' => 'yes',
				),
			)
		);

		$this->add_control(
			'access_token',
			array(
				'label'     => __( 'Access Token', 'powerpack' ),
				'type'      => Controls_Manager::TEXT,
				'condition' => array(
					'use_api' => 'yes',
				),
			)
		);

		$this->add_control(
			'client_id',
			array(
				'label'     => __( 'Client ID', 'powerpack' ),
				'type'      => Controls_Manager::TEXT,
				'condition' => array(
					'use_api' => 'yes',
				),
			)
		);

		$this->add_control(
			'username',
			array(
				'label'              => __( 'Instagram Username', 'powerpack' ),
				'description'        => __( 'This must be public account.', 'powerpack' ),
				'label_block'        => true,
				'type'               => Controls_Manager::TEXT,
				'frontend_available' => true,
				'condition'          => array(
					'use_api!' => 'yes',
				),
			)
		);

		$this->end_controls_section();

		/**
		 * Content Tab: Feed Settings
		 */
		$this->start_controls_section(
			'section_instafeed',
			array(
				'label' => __( 'Feed Settings', 'powerpack' ),
			)
		);

		$this->add_control(
			'images_count',
			array(
				'label'      => __( 'Images Count', 'powerpack' ),
				'type'       => Controls_Manager::SLIDER,
				'default'    => array( 'size' => 5 ),
				'range'      => array(
					'px' => array(
						'min'  => 1,
						'max'  => 100,
						'step' => 1,
					),
				),
				'size_units' => '',
			)
		);

		$this->add_control(
			'images_info',
			array(
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => __( 'Maximum 12 images can be displayed without using API.', 'powerpack' ),
				'separator'       => 'after',
				'content_classes' => 'pp-editor-info',
				'condition'       => array(
					'use_api!' => 'yes',
				),
			)
		);

		$this->add_control(
			'resolution',
			array(
				'label'   => __( 'Image Resolution', 'powerpack' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'thumbnail'           => __( 'Thumbnail', 'powerpack' ),
					'low_resolution'      => __( 'Low Resolution', 'powerpack' ),
					'standard_resolution' => __( 'Standard Resolution', 'powerpack' ),
				),
				'default' => 'low_resolution',
			)
		);

		$this->add_control(
			'sort_by',
			array(
				'label'   => __( 'Sort By', 'powerpack' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'none'            => __( 'None', 'powerpack' ),
					'most-recent'     => __( 'Most Recent', 'powerpack' ),
					'least-recent'    => __( 'Least Recent', 'powerpack' ),
					'most-liked'      => __( 'Most Liked', 'powerpack' ),
					'least-liked'     => __( 'Least Liked', 'powerpack' ),
					'most-commented'  => __( 'Most Commented', 'powerpack' ),
					'least-commented' => __( 'Least Commented', 'powerpack' ),
					'random'          => __( 'Random', 'powerpack' ),
				),
				'default' => 'none',
			)
		);

		$this->end_controls_section();

		/**
		 * Content Tab: General Settings
		 */
		$this->start_controls_section(
			'section_general_settings',
			array(
				'label' => __( 'General Settings', 'powerpack' ),
			)
		);

		$this->add_control(
			'feed_layout',
			array(
				'label'              => __( 'Layout', 'powerpack' ),
				'type'               => Controls_Manager::SELECT,
				'default'            => 'grid',
				'options'            => array(
					'grid'     => __( 'Grid', 'powerpack' ),
					'masonry'  => __( 'Masonry', 'powerpack' ),
					'carousel' => __( 'Carousel', 'powerpack' ),
				),
				'frontend_available' => true,
			)
		);

		$this->add_control(
			'square_images',
			array(
				'label'        => __( 'Square Images', 'powerpack' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '',
				'label_on'     => __( 'Yes', 'powerpack' ),
				'label_off'    => __( 'No', 'powerpack' ),
				'return_value' => 'yes',
				'condition'    => array(
					'feed_layout' => array( 'grid', 'carousel' ),
				),
			)
		);

		$this->add_responsive_control(
			'grid_cols',
			array(
				'label'          => __( 'Grid Columns', 'powerpack' ),
				'type'           => Controls_Manager::SELECT,
				'label_block'    => false,
				'default'        => '5',
				'tablet_default' => '3',
				'mobile_default' => '2',
				'options'        => array(
					'1' => __( '1', 'powerpack' ),
					'2' => __( '2', 'powerpack' ),
					'3' => __( '3', 'powerpack' ),
					'4' => __( '4', 'powerpack' ),
					'5' => __( '5', 'powerpack' ),
					'6' => __( '6', 'powerpack' ),
					'7' => __( '7', 'powerpack' ),
					'8' => __( '8', 'powerpack' ),
				),
				'selectors'      => array(
					'{{WRAPPER}} .pp-instagram-feed-grid .pp-feed-item' => 'width: calc( 100% / {{VALUE}} )',
				),
				'condition'      => array(
					'feed_layout' => array( 'grid', 'masonry' ),
				),
			)
		);

		$this->add_control(
			'insta_likes',
			array(
				'label'              => __( 'Likes', 'powerpack' ),
				'type'               => Controls_Manager::SWITCHER,
				'default'            => 'no',
				'label_on'           => __( 'Show', 'powerpack' ),
				'label_off'          => __( 'Hide', 'powerpack' ),
				'return_value'       => 'yes',
				'separator'          => 'before',
				'frontend_available' => true,
			)
		);

		$this->add_control(
			'insta_comments',
			array(
				'label'              => __( 'Comments', 'powerpack' ),
				'type'               => Controls_Manager::SWITCHER,
				'default'            => 'no',
				'label_on'           => __( 'Show', 'powerpack' ),
				'label_off'          => __( 'Hide', 'powerpack' ),
				'return_value'       => 'yes',
				'frontend_available' => true,
			)
		);

		$this->add_control(
			'content_visibility',
			array(
				'label'      => __( 'Content Visibility', 'powerpack' ),
				'type'       => Controls_Manager::SELECT,
				'default'    => 'always',
				'options'    => array(
					'always' => __( 'Always', 'powerpack' ),
					'hover'  => __( 'On Hover', 'powerpack' ),
				),
				'conditions' => array(
					'relation' => 'or',
					'terms'    => array(
						array(
							'name'     => 'insta_likes',
							'operator' => '==',
							'value'    => 'yes',
						),
						array(
							'name'     => 'insta_comments',
							'operator' => '==',
							'value'    => 'yes',
						),
					),
				),
			)
		);

		$this->add_control(
			'insta_image_popup',
			array(
				'label'        => __( 'Lightbox', 'powerpack' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'no',
				'label_on'     => __( 'Yes', 'powerpack' ),
				'label_off'    => __( 'No', 'powerpack' ),
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'insta_image_link',
			array(
				'label'        => __( 'Image Link', 'powerpack' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '',
				'label_on'     => __( 'Yes', 'powerpack' ),
				'label_off'    => __( 'No', 'powerpack' ),
				'return_value' => 'yes',
				'condition'    => array(
					'insta_image_popup!' => 'yes',
				),
			)
		);

		$this->add_control(
			'insta_profile_link',
			array(
				'label'        => __( 'Show Link to Instagram Profile?', 'powerpack' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'no',
				'label_on'     => __( 'Yes', 'powerpack' ),
				'label_off'    => __( 'No', 'powerpack' ),
				'return_value' => 'yes',
				'separator'    => 'before',
			)
		);

		$this->add_control(
			'insta_link_title',
			array(
				'label'     => __( 'Link Title', 'powerpack' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => __( 'Follow Us @ Instagram', 'powerpack' ),
				'condition' => array(
					'insta_profile_link' => 'yes',
				),
			)
		);

		$this->add_control(
			'insta_profile_url',
			array(
				'label'       => __( 'Instagram Profile URL', 'powerpack' ),
				'type'        => Controls_Manager::URL,
				'placeholder' => 'https://www.your-link.com',
				'default'     => array(
					'url' => '#',
				),
				'condition'   => array(
					'insta_profile_link' => 'yes',
				),
			)
		);

		$this->add_control(
			'title_icon',
			array(
				'label'            => __( 'Title Icon', 'powerpack' ),
				'type'             => Controls_Manager::ICONS,
				'fa4compatibility' => 'insta_title_icon',
				'recommended'      => array(
					'fa-brands' => array(
						'instagram',
					),
					'fa-solid'  => array(
						'user-check',
						'user-plus',
					),
				),
				'default'          => array(
					'value'   => 'fab fa-instagram',
					'library' => 'fa-brands',
				),
				'condition'        => array(
					'insta_profile_link' => 'yes',
				),
			)
		);

		$this->add_control(
			'insta_title_icon_position',
			array(
				'label'     => __( 'Icon Position', 'powerpack' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => array(
					'before_title' => __( 'Before Title', 'powerpack' ),
					'after_title'  => __( 'After Title', 'powerpack' ),
				),
				'default'   => 'before_title',
				'condition' => array(
					'insta_profile_link' => 'yes',
				),
			)
		);

		$this->add_control(
			'load_more_button',
			array(
				'label'        => __( 'Show Load More Button', 'powerpack' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'no',
				'label_on'     => __( 'Yes', 'powerpack' ),
				'label_off'    => __( 'No', 'powerpack' ),
				'return_value' => 'yes',
				'separator'    => 'before',
				'condition'    => array(
					'use_api'     => 'yes',
					'feed_layout' => 'grid',
				),
			)
		);

		$this->add_control(
			'load_more_button_text',
			array(
				'label'     => __( 'Button Text', 'powerpack' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => __( 'Load More', 'powerpack' ),
				'condition' => array(
					'use_api'          => 'yes',
					'load_more_button' => 'yes',
					'feed_layout'      => 'grid',
				),
			)
		);

		$this->end_controls_section();

		/**
		 * Content Tab: Carousel Settings
		 */
		$this->start_controls_section(
			'section_carousel_settings',
			array(
				'label'     => __( 'Carousel Settings', 'powerpack' ),
				'condition' => array(
					'feed_layout' => 'carousel',
				),
			)
		);

		$this->add_responsive_control(
			'items',
			array(
				'label'      => __( 'Visible Items', 'powerpack' ),
				'type'       => Controls_Manager::SLIDER,
				'default'    => array( 'size' => 3 ),
				'range'      => array(
					'px' => array(
						'min'  => 1,
						'max'  => 10,
						'step' => 1,
					),
				),
				'size_units' => '',
				'condition'  => array(
					'feed_layout' => 'carousel',
				),
			)
		);

		$this->add_responsive_control(
			'margin',
			array(
				'label'      => __( 'Items Gap', 'powerpack' ),
				'type'       => Controls_Manager::SLIDER,
				'default'    => array( 'size' => 10 ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					),
				),
				'size_units' => '',
				'condition'  => array(
					'feed_layout' => 'carousel',
				),
			)
		);

		$this->add_control(
			'autoplay',
			array(
				'label'        => __( 'Autoplay', 'powerpack' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'label_on'     => __( 'Yes', 'powerpack' ),
				'label_off'    => __( 'No', 'powerpack' ),
				'return_value' => 'yes',
				'condition'    => array(
					'feed_layout' => 'carousel',
				),
			)
		);

		$this->add_control(
			'autoplay_speed',
			array(
				'label'     => __( 'Autoplay Speed', 'powerpack' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => '2400',
				'title'     => __( 'Enter carousel speed', 'powerpack' ),
				'condition' => array(
					'autoplay'    => 'yes',
					'feed_layout' => 'carousel',
				),
			)
		);

		$this->add_control(
			'infinite_loop',
			array(
				'label'        => __( 'Infinite Loop', 'powerpack' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'label_on'     => __( 'Yes', 'powerpack' ),
				'label_off'    => __( 'No', 'powerpack' ),
				'return_value' => 'yes',
				'condition'    => array(
					'feed_layout' => 'carousel',
				),
			)
		);

		$this->add_control(
			'grab_cursor',
			array(
				'label'        => __( 'Grab Cursor', 'powerpack' ),
				'description'  => __( 'Shows grab cursor when you hover over the slider', 'powerpack' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '',
				'label_on'     => __( 'Show', 'powerpack' ),
				'label_off'    => __( 'Hide', 'powerpack' ),
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'navigation_heading',
			array(
				'label'     => __( 'Navigation', 'powerpack' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'arrows',
			array(
				'label'        => __( 'Arrows', 'powerpack' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'label_on'     => __( 'Yes', 'powerpack' ),
				'label_off'    => __( 'No', 'powerpack' ),
				'return_value' => 'yes',
				'condition'    => array(
					'feed_layout' => 'carousel',
				),
			)
		);

		$this->add_control(
			'dots',
			array(
				'label'        => __( 'Pagination', 'powerpack' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'label_on'     => __( 'Yes', 'powerpack' ),
				'label_off'    => __( 'No', 'powerpack' ),
				'return_value' => 'yes',
				'condition'    => array(
					'feed_layout' => 'carousel',
				),
			)
		);

		$this->add_control(
			'pagination_type',
			array(
				'label'     => __( 'Pagination Type', 'powerpack' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'bullets',
				'options'   => array(
					'bullets'  => __( 'Dots', 'powerpack' ),
					'fraction' => __( 'Fraction', 'powerpack' ),
				),
				'condition' => array(
					'dots' => 'yes',
				),
			)
		);

		$this->add_control(
			'direction',
			array(
				'label'     => __( 'Direction', 'powerpack' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'left',
				'options'   => array(
					'left'  => __( 'Left', 'powerpack' ),
					'right' => __( 'Right', 'powerpack' ),
				),
				'separator' => 'before',
			)
		);

		$this->end_controls_section();

		/**
		 * Content Tab: Docs Links
		 *
		 * @since 1.4.8
		 * @access protected
		 */
		$this->start_controls_section(
			'section_help_docs',
			array(
				'label' => __( 'Help Docs', 'powerpack' ),
			)
		);

		$this->add_control(
			'help_doc_1',
			array(
				'type'            => Controls_Manager::RAW_HTML,
				/* translators: %1$s doc link */
				'raw'             => sprintf( __( '%1$s Watch Video Overview %2$s', 'powerpack' ), '<a href="https://www.youtube.com/watch?v=33A9XL1twFE&list=PLpsSO_wNe8Dz4vfe2tWlySBCCFEgh1qZj" target="_blank" rel="noopener">', '</a>' ),
				'content_classes' => 'pp-editor-doc-links',
			)
		);

		$this->add_control(
			'help_doc_2',
			array(
				'type'            => Controls_Manager::RAW_HTML,
				/* translators: %1$s doc link */
				'raw'             => sprintf( __( '%1$s Getting started article %2$s', 'powerpack' ), '<a href="https://powerpackelements.com/docs/powerpack/widgets/instagram-feed/instagram-feed-widget-overview/?utm_source=widget&utm_medium=panel&utm_campaign=userkb" target="_blank" rel="noopener">', '</a>' ),
				'content_classes' => 'pp-editor-doc-links',
			)
		);

		$this->add_control(
			'help_doc_3',
			array(
				'type'            => Controls_Manager::RAW_HTML,
				/* translators: %1$s doc link */
				'raw'             => sprintf( __( '%1$s How to set up Instagram Feed widget? %2$s', 'powerpack' ), '<a href="https://powerpackelements.com/docs/powerpack/widgets/instagram-feed/elementor-instagram-widget-setup/?utm_source=widget&utm_medium=panel&utm_campaign=userkb" target="_blank" rel="noopener">', '</a>' ),
				'content_classes' => 'pp-editor-doc-links',
			)
		);

		$this->end_controls_section();

		if ( ! is_pp_elements_active() ) {
			/**
			 * Content Tab: Upgrade PowerPack
			 *
			 * @since 1.2.9.4
			 */
			$this->start_controls_section(
				'section_upgrade_powerpack',
				array(
					'label' => apply_filters( 'upgrade_powerpack_title', __( 'Get PowerPack Pro', 'powerpack' ) ),
					'tab'   => Controls_Manager::TAB_CONTENT,
				)
			);

			$this->add_control(
				'upgrade_powerpack_notice',
				array(
					'label'           => '',
					'type'            => Controls_Manager::RAW_HTML,
					'raw'             => apply_filters( 'upgrade_powerpack_message', sprintf( __( 'Upgrade to %1$s Pro Version %2$s for 70+ widgets, exciting extensions and advanced features.', 'powerpack' ), '<a href="#" target="_blank" rel="noopener">', '</a>' ) ),
					'content_classes' => 'upgrade-powerpack-notice elementor-panel-alert elementor-panel-alert-info',
				)
			);

			$this->end_controls_section();
		}

		/*
		-----------------------------------------------------------------------------------*/
		/*
		  STYLE TAB
		/*-----------------------------------------------------------------------------------*/

		/**
		 * Style Tab: Layout
		 */
		$this->start_controls_section(
			'section_layout_style',
			array(
				'label'     => __( 'Layout', 'powerpack' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'feed_layout' => 'grid',
				),
			)
		);$this->add_responsive_control(
			'columns_gap',
			array(
				'label'          => __( 'Columns Gap', 'powerpack' ),
				'type'           => Controls_Manager::SLIDER,
				'default'        => array(
					'size' => '',
					'unit' => 'px',
				),
				'size_units'     => array( 'px', '%' ),
				'range'          => array(
					'px' => array(
						'max' => 100,
					),
				),
				'tablet_default' => array(
					'unit' => 'px',
				),
				'mobile_default' => array(
					'unit' => 'px',
				),
				'selectors'      => array(
					'{{WRAPPER}} .pp-instafeed-grid .pp-feed-item' => 'padding-left: calc({{SIZE}}{{UNIT}}/2); padding-right: calc({{SIZE}}{{UNIT}}/2);',
					'{{WRAPPER}} .pp-instafeed-grid' => 'margin-left: calc(-{{SIZE}}{{UNIT}}/2); margin-right: calc(-{{SIZE}}{{UNIT}}/2);',
				),
				'condition'      => array(
					'feed_layout' => 'grid',
				),
			)
		);

		$this->add_responsive_control(
			'rows_gap',
			array(
				'label'          => __( 'Rows Gap', 'powerpack' ),
				'type'           => Controls_Manager::SLIDER,
				'default'        => array(
					'size' => '',
					'unit' => 'px',
				),
				'size_units'     => array( 'px', '%' ),
				'range'          => array(
					'px' => array(
						'max' => 100,
					),
				),
				'tablet_default' => array(
					'unit' => 'px',
				),
				'mobile_default' => array(
					'unit' => 'px',
				),
				'selectors'      => array(
					'{{WRAPPER}} .pp-instafeed-grid .pp-feed-item' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
				'condition'      => array(
					'feed_layout' => 'grid',
				),
			)
		);

		$this->end_controls_section();

		/**
		 * Style Tab: Images
		 */
		$this->start_controls_section(
			'section_image_style',
			array(
				'label' => __( 'Images', 'powerpack' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->start_controls_tabs( 'tabs_image_style' );

		$this->start_controls_tab(
			'tab_image_normal',
			array(
				'label' => __( 'Normal', 'powerpack' ),
			)
		);

		$this->add_control(
			'insta_image_grayscale',
			array(
				'label'        => __( 'Grayscale Image', 'powerpack' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'no',
				'label_on'     => __( 'Yes', 'powerpack' ),
				'label_off'    => __( 'No', 'powerpack' ),
				'return_value' => 'yes',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'images_border',
				'label'       => __( 'Border', 'powerpack' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .pp-instagram-feed .pp-if-img',
			)
		);

		$this->add_control(
			'images_border_radius',
			array(
				'label'      => __( 'Border Radius', 'powerpack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .pp-instagram-feed .pp-if-img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_image_hover',
			array(
				'label' => __( 'Hover', 'powerpack' ),
			)
		);

		$this->add_control(
			'insta_image_grayscale_hover',
			array(
				'label'        => __( 'Grayscale Image', 'powerpack' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'no',
				'label_on'     => __( 'Yes', 'powerpack' ),
				'label_off'    => __( 'No', 'powerpack' ),
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'images_border_color_hover',
			array(
				'label'     => __( 'Border Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .pp-instagram-feed .pp-feed-item:hover .pp-if-img' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		/**
		 * Style Tab: Content
		 */
		$this->start_controls_section(
			'section_content_style',
			array(
				'label'      => __( 'Content', 'powerpack' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'conditions' => array(
					'relation' => 'or',
					'terms'    => array(
						array(
							'name'     => 'insta_likes',
							'operator' => '==',
							'value'    => 'yes',
						),
						array(
							'name'     => 'insta_comments',
							'operator' => '==',
							'value'    => 'yes',
						),
					),
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'       => 'content_typography',
				'label'      => __( 'Typography', 'powerpack' ),
				'selector'   => '{{WRAPPER}} .pp-feed-item .pp-overlay-container',
				'conditions' => array(
					'relation' => 'or',
					'terms'    => array(
						array(
							'name'     => 'insta_likes',
							'operator' => '==',
							'value'    => 'yes',
						),
						array(
							'name'     => 'insta_comments',
							'operator' => '==',
							'value'    => 'yes',
						),
					),
				),
			)
		);

		$this->add_control(
			'likes_comments_color',
			array(
				'label'      => __( 'Color', 'powerpack' ),
				'type'       => Controls_Manager::COLOR,
				'default'    => '',
				'selectors'  => array(
					'{{WRAPPER}} .pp-feed-item .pp-overlay-container' => 'color: {{VALUE}};',
				),
				'conditions' => array(
					'relation' => 'or',
					'terms'    => array(
						array(
							'name'     => 'insta_likes',
							'operator' => '==',
							'value'    => 'yes',
						),
						array(
							'name'     => 'insta_comments',
							'operator' => '==',
							'value'    => 'yes',
						),
					),
				),
			)
		);

		$this->add_control(
			'content_vertical_align',
			array(
				'label'                => __( 'Vertical Align', 'powerpack' ),
				'type'                 => Controls_Manager::CHOOSE,
				'label_block'          => false,
				'toggle'               => false,
				'default'              => 'middle',
				'options'              => array(
					'top'    => array(
						'title' => __( 'Top', 'powerpack' ),
						'icon'  => 'eicon-v-align-top',
					),
					'middle' => array(
						'title' => __( 'Center', 'powerpack' ),
						'icon'  => 'eicon-v-align-middle',
					),
					'bottom' => array(
						'title' => __( 'Bottom', 'powerpack' ),
						'icon'  => 'eicon-v-align-bottom',
					),
				),
				'selectors_dictionary' => array(
					'top'    => 'flex-start',
					'middle' => 'center',
					'bottom' => 'flex-end',
				),
				'selectors'            => array(
					'{{WRAPPER}} .pp-overlay-container' => 'align-items: {{VALUE}};',
				),
				'conditions'           => array(
					'relation' => 'or',
					'terms'    => array(
						array(
							'name'     => 'insta_likes',
							'operator' => '==',
							'value'    => 'yes',
						),
						array(
							'name'     => 'insta_comments',
							'operator' => '==',
							'value'    => 'yes',
						),
					),
				),
			)
		);

		$this->add_control(
			'content_horizontal_align',
			array(
				'label'                => __( 'Horizontal Align', 'powerpack' ),
				'type'                 => Controls_Manager::CHOOSE,
				'label_block'          => false,
				'toggle'               => false,
				'default'              => 'center',
				'options'              => array(
					'left'   => array(
						'title' => __( 'Left', 'powerpack' ),
						'icon'  => 'eicon-h-align-left',
					),
					'center' => array(
						'title' => __( 'Center', 'powerpack' ),
						'icon'  => 'eicon-h-align-center',
					),
					'right'  => array(
						'title' => __( 'Right', 'powerpack' ),
						'icon'  => 'eicon-h-align-right',
					),
				),
				'selectors_dictionary' => array(
					'left'   => 'flex-start',
					'center' => 'center',
					'right'  => 'flex-end',
				),
				'selectors'            => array(
					'{{WRAPPER}} .pp-overlay-container' => 'justify-content: {{VALUE}};',
				),
				'conditions'           => array(
					'relation' => 'or',
					'terms'    => array(
						array(
							'name'     => 'insta_likes',
							'operator' => '==',
							'value'    => 'yes',
						),
						array(
							'name'     => 'insta_comments',
							'operator' => '==',
							'value'    => 'yes',
						),
					),
				),
			)
		);

		$this->add_responsive_control(
			'content_padding',
			array(
				'label'      => __( 'Padding', 'powerpack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .pp-overlay-container' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'conditions' => array(
					'relation' => 'or',
					'terms'    => array(
						array(
							'name'     => 'insta_likes',
							'operator' => '==',
							'value'    => 'yes',
						),
						array(
							'name'     => 'insta_comments',
							'operator' => '==',
							'value'    => 'yes',
						),
					),
				),
			)
		);

		$this->add_control(
			'icons_heading',
			array(
				'label'      => __( 'Icons', 'powerpack' ),
				'type'       => Controls_Manager::HEADING,
				'separator'  => 'before',
				'conditions' => array(
					'relation' => 'or',
					'terms'    => array(
						array(
							'name'     => 'insta_likes',
							'operator' => '==',
							'value'    => 'yes',
						),
						array(
							'name'     => 'insta_comments',
							'operator' => '==',
							'value'    => 'yes',
						),
					),
				),
			)
		);

		$this->add_control(
			'icons_style',
			array(
				'label'              => __( 'Style', 'powerpack' ),
				'type'               => Controls_Manager::CHOOSE,
				'label_block'        => false,
				'toggle'             => false,
				'default'            => 'solid',
				'options'            => array(
					'solid'   => array(
						'title' => __( 'Solid', 'powerpack' ),
						'icon'  => 'fa fa-comment',
					),
					'outline' => array(
						'title' => __( 'Outline', 'powerpack' ),
						'icon'  => 'fa fa-comment-o',
					),
				),
				'frontend_available' => true,
				'conditions'         => array(
					'relation' => 'or',
					'terms'    => array(
						array(
							'name'     => 'insta_likes',
							'operator' => '==',
							'value'    => 'yes',
						),
						array(
							'name'     => 'insta_comments',
							'operator' => '==',
							'value'    => 'yes',
						),
					),
				),
			)
		);

		$this->add_responsive_control(
			'icon_size',
			array(
				'label'      => __( 'Size', 'powerpack' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 2.5,
						'step' => 0.1,
					),
				),
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'{{WRAPPER}} .pp-feed-item .pp-if-icon' => 'font-size: {{SIZE}}em;',
				),
				'conditions' => array(
					'relation' => 'or',
					'terms'    => array(
						array(
							'name'     => 'insta_likes',
							'operator' => '==',
							'value'    => 'yes',
						),
						array(
							'name'     => 'insta_comments',
							'operator' => '==',
							'value'    => 'yes',
						),
					),
				),
			)
		);

		$this->end_controls_section();

		/**
		 * Style Tab: Overlay
		 */
		$this->start_controls_section(
			'section_overlay_style',
			array(
				'label' => __( 'Overlay', 'powerpack' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'overlay_blend_mode',
			array(
				'label'     => __( 'Blend Mode', 'powerpack' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'normal',
				'options'   => array(
					'normal'      => __( 'Normal', 'powerpack' ),
					'multiply'    => __( 'Multiply', 'powerpack' ),
					'screen'      => __( 'Screen', 'powerpack' ),
					'overlay'     => __( 'Overlay', 'powerpack' ),
					'darken'      => __( 'Darken', 'powerpack' ),
					'lighten'     => __( 'Lighten', 'powerpack' ),
					'color-dodge' => __( 'Color Dodge', 'powerpack' ),
					'color'       => __( 'Color', 'powerpack' ),
					'hue'         => __( 'Hue', 'powerpack' ),
					'hard-light'  => __( 'Hard Light', 'powerpack' ),
					'soft-light'  => __( 'Soft Light', 'powerpack' ),
					'difference'  => __( 'Difference', 'powerpack' ),
					'exclusion'   => __( 'Exclusion', 'powerpack' ),
					'saturation'  => __( 'Saturation', 'powerpack' ),
					'luminosity'  => __( 'Luminosity', 'powerpack' ),
				),
				'selectors' => array(
					'{{WRAPPER}} .pp-instagram-feed .pp-overlay-container' => 'mix-blend-mode: {{VALUE}};',
				),
			)
		);

		$this->start_controls_tabs( 'tabs_overlay_style' );

		$this->start_controls_tab(
			'tab_overlay_normal',
			array(
				'label' => __( 'Normal', 'powerpack' ),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'image_overlay_normal',
				'label'    => __( 'Overlay', 'powerpack' ),
				'types'    => array( 'classic', 'gradient' ),
				'exclude'  => array(
					'image',
				),
				'selector' => '{{WRAPPER}} .pp-instagram-feed .pp-overlay-container',
			)
		);

		$this->add_control(
			'overlay_margin_normal',
			array(
				'label'     => __( 'Margin', 'powerpack' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min'  => 0,
						'max'  => 50,
						'step' => 1,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .pp-instagram-feed .pp-overlay-container' => 'top: {{SIZE}}px; bottom: {{SIZE}}px; left: {{SIZE}}px; right: {{SIZE}}px;',
				),
			)
		);

		$this->add_control(
			'image_overlay_opacity_normal',
			array(
				'label'      => __( 'Overlay Opacity', 'powerpack' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 1,
						'step' => 0.1,
					),
				),
				'size_units' => '',
				'selectors'  => array(
					'{{WRAPPER}} .pp-instagram-feed .pp-overlay-container' => 'opacity: {{SIZE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_overlay_hover',
			array(
				'label' => __( 'Hover', 'powerpack' ),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'image_overlay_hover',
				'label'    => __( 'Overlay', 'powerpack' ),
				'types'    => array( 'classic', 'gradient' ),
				'exclude'  => array(
					'image',
				),
				'selector' => '{{WRAPPER}} .pp-instagram-feed .pp-feed-item:hover .pp-overlay-container',
			)
		);

		$this->add_control(
			'image_overlay_opacity_hover',
			array(
				'label'      => __( 'Overlay Opacity', 'powerpack' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 1,
						'step' => 0.1,
					),
				),
				'size_units' => '',
				'selectors'  => array(
					'{{WRAPPER}} .pp-instagram-feed .pp-feed-item:hover .pp-overlay-container' => 'opacity: {{SIZE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		/**
		 * Style Tab: Feed Title
		 */
		$this->start_controls_section(
			'section_feed_title_style',
			array(
				'label'     => __( 'Feed Title', 'powerpack' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'insta_profile_link' => 'yes',
				),
			)
		);

		$this->add_control(
			'feed_title_position',
			array(
				'label'        => __( 'Position', 'powerpack' ),
				'type'         => Controls_Manager::CHOOSE,
				'label_block'  => false,
				'default'      => 'middle',
				'options'      => array(
					'top'    => array(
						'title' => __( 'Top', 'powerpack' ),
						'icon'  => 'eicon-v-align-top',
					),
					'middle' => array(
						'title' => __( 'Middle', 'powerpack' ),
						'icon'  => 'eicon-v-align-middle',
					),
					'bottom' => array(
						'title' => __( 'Bottom', 'powerpack' ),
						'icon'  => 'eicon-v-align-bottom',
					),
				),
				'prefix_class' => 'pp-insta-title-',
				'condition'    => array(
					'insta_profile_link' => 'yes',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'feed_title_typography',
				'label'     => __( 'Typography', 'powerpack' ),
				'scheme'    => Scheme_Typography::TYPOGRAPHY_4,
				'selector'  => '{{WRAPPER}} .pp-instagram-feed-title',
				'condition' => array(
					'insta_profile_link' => 'yes',
				),
			)
		);

		$this->start_controls_tabs( 'tabs_title_style' );

		$this->start_controls_tab(
			'tab_title_normal',
			array(
				'label'     => __( 'Normal', 'powerpack' ),
				'condition' => array(
					'insta_profile_link' => 'yes',
				),
			)
		);

		$this->add_control(
			'title_color_normal',
			array(
				'label'     => __( 'Text Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .pp-instagram-feed-title-wrap a' => 'color: {{VALUE}};',
					'{{WRAPPER}} .pp-instagram-feed-title-wrap .pp-icon svg' => 'fill: {{VALUE}};',
				),
				'condition' => array(
					'insta_profile_link' => 'yes',
				),
			)
		);

		$this->add_control(
			'title_bg_color_normal',
			array(
				'label'     => __( 'Background Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .pp-instagram-feed-title-wrap' => 'background: {{VALUE}};',
				),
				'condition' => array(
					'insta_profile_link' => 'yes',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'title_border_normal',
				'label'       => __( 'Border', 'powerpack' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .pp-instagram-feed-title-wrap',
			)
		);

		$this->add_control(
			'title_border_radius_normal',
			array(
				'label'      => __( 'Border Radius', 'powerpack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .pp-instagram-feed-title-wrap' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_title_hover',
			array(
				'label'     => __( 'Hover', 'powerpack' ),
				'condition' => array(
					'insta_profile_link' => 'yes',
				),
			)
		);

		$this->add_control(
			'title_color_hover',
			array(
				'label'     => __( 'Text Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .pp-instagram-feed-title-wrap a:hover' => 'color: {{VALUE}};',
					'{{WRAPPER}} .pp-instagram-feed-title-wrap a:hover .pp-icon svg' => 'fill: {{VALUE}};',
				),
				'condition' => array(
					'insta_profile_link' => 'yes',
				),
			)
		);

		$this->add_control(
			'title_bg_color_hover',
			array(
				'label'     => __( 'Background Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .pp-instagram-feed-title-wrap:hover' => 'background: {{VALUE}};',
				),
				'condition' => array(
					'insta_profile_link' => 'yes',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'title_border_hover',
				'label'       => __( 'Border', 'powerpack' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .pp-instagram-feed-title-wrap:hover',
			)
		);

		$this->add_control(
			'title_border_radius_hover',
			array(
				'label'      => __( 'Border Radius', 'powerpack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .pp-instagram-feed-title-wrap:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'title_padding',
			array(
				'label'      => __( 'Padding', 'powerpack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .pp-instagram-feed-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					'insta_profile_link' => 'yes',
				),
				'separator'  => 'before',
			)
		);

		$this->add_control(
			'title_icon_heading',
			array(
				'label'     => __( 'Icon', 'powerpack' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'insta_profile_link' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'title_icon_spacing',
			array(
				'label'      => __( 'Spacing', 'powerpack' ),
				'type'       => Controls_Manager::SLIDER,
				'default'    => array( 'size' => 4 ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 30,
						'step' => 1,
					),
				),
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'{{WRAPPER}} .pp-instagram-feed .pp-icon-before_title' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .pp-instagram-feed .pp-icon-after_title' => 'margin-left: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					'insta_profile_link' => 'yes',
				),
			)
		);

		$this->end_controls_section();

		/**
		 * Style Tab: Arrows
		 */
		$this->start_controls_section(
			'section_arrows_style',
			array(
				'label'     => __( 'Arrows', 'powerpack' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'arrows'      => 'yes',
					'feed_layout' => 'carousel',
				),
			)
		);

		$this->add_control(
			'arrow',
			array(
				'label'       => __( 'Choose Arrow', 'powerpack' ),
				'type'        => Controls_Manager::SELECT,
				'label_block' => false,
				'default'     => 'fa fa-angle-right',
				'options'     => array(
					'fa fa-angle-right'          => __( 'Angle', 'powerpack' ),
					'fa fa-angle-double-right'   => __( 'Double Angle', 'powerpack' ),
					'fa fa-chevron-right'        => __( 'Chevron', 'powerpack' ),
					'fa fa-chevron-circle-right' => __( 'Chevron Circle', 'powerpack' ),
					'fa fa-arrow-right'          => __( 'Arrow', 'powerpack' ),
					'fa fa-long-arrow-right'     => __( 'Long Arrow', 'powerpack' ),
					'fa fa-caret-right'          => __( 'Caret', 'powerpack' ),
					'fa fa-caret-square-o-right' => __( 'Caret Square', 'powerpack' ),
					'fa fa-arrow-circle-right'   => __( 'Arrow Circle', 'powerpack' ),
					'fa fa-arrow-circle-o-right' => __( 'Arrow Circle O', 'powerpack' ),
					'fa fa-toggle-right'         => __( 'Toggle', 'powerpack' ),
					'fa fa-hand-o-right'         => __( 'Hand', 'powerpack' ),
				),
			)
		);

		$this->add_responsive_control(
			'arrows_size',
			array(
				'label'      => __( 'Arrows Size', 'powerpack' ),
				'type'       => Controls_Manager::SLIDER,
				'default'    => array( 'size' => '22' ),
				'range'      => array(
					'px' => array(
						'min'  => 15,
						'max'  => 100,
						'step' => 1,
					),
				),
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'{{WRAPPER}} .pp-instagram-feed .swiper-button-next, {{WRAPPER}} .pp-instagram-feed .swiper-button-prev' => 'font-size: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'left_arrow_position',
			array(
				'label'      => __( 'Align Left Arrow', 'powerpack' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => array(
					'px' => array(
						'min'  => -100,
						'max'  => 40,
						'step' => 1,
					),
				),
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'{{WRAPPER}} .pp-instagram-feed .swiper-button-prev' => 'left: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'right_arrow_position',
			array(
				'label'      => __( 'Align Right Arrow', 'powerpack' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => array(
					'px' => array(
						'min'  => -100,
						'max'  => 40,
						'step' => 1,
					),
				),
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'{{WRAPPER}} .pp-instagram-feed .swiper-button-next' => 'right: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->start_controls_tabs( 'tabs_arrows_style' );

		$this->start_controls_tab(
			'tab_arrows_normal',
			array(
				'label' => __( 'Normal', 'powerpack' ),
			)
		);

		$this->add_control(
			'arrows_bg_color_normal',
			array(
				'label'     => __( 'Background Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .pp-instagram-feed .swiper-button-next, {{WRAPPER}} .pp-instagram-feed .swiper-button-prev' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'arrows_color_normal',
			array(
				'label'     => __( 'Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .pp-instagram-feed .swiper-button-next, {{WRAPPER}} .pp-instagram-feed .swiper-button-prev' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'arrows_border_normal',
				'label'       => __( 'Border', 'powerpack' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .pp-instagram-feed .swiper-button-next, {{WRAPPER}} .pp-instagram-feed .swiper-button-prev',
			)
		);

		$this->add_control(
			'arrows_border_radius_normal',
			array(
				'label'      => __( 'Border Radius', 'powerpack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .pp-instagram-feed .swiper-button-next, {{WRAPPER}} .pp-instagram-feed .swiper-button-prev' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_arrows_hover',
			array(
				'label' => __( 'Hover', 'powerpack' ),
			)
		);

		$this->add_control(
			'arrows_bg_color_hover',
			array(
				'label'     => __( 'Background Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .pp-instagram-feed .swiper-button-next:hover, {{WRAPPER}} .pp-instagram-feed .swiper-button-prev:hover' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'arrows_color_hover',
			array(
				'label'     => __( 'Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .pp-instagram-feed .swiper-button-next:hover, {{WRAPPER}} .pp-instagram-feed .swiper-button-prev:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'arrows_border_color_hover',
			array(
				'label'     => __( 'Border Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .pp-instagram-feed .swiper-button-next:hover, {{WRAPPER}} .pp-instagram-feed .swiper-button-prev:hover' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'arrows_padding',
			array(
				'label'      => __( 'Padding', 'powerpack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .pp-instagram-feed .swiper-button-next, {{WRAPPER}} .pp-instagram-feed .swiper-button-prev' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'separator'  => 'before',
			)
		);

		$this->end_controls_section();

		/**
		 * Style Tab: Pagination: Dots
		 */
		$this->start_controls_section(
			'section_dots_style',
			array(
				'label'     => __( 'Pagination: Dots', 'powerpack' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'feed_layout'     => 'carousel',
					'dots'            => 'yes',
					'pagination_type' => 'bullets',
				),
			)
		);

		$this->add_control(
			'dots_position',
			array(
				'label'     => __( 'Position', 'powerpack' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => array(
					'inside'  => __( 'Inside', 'powerpack' ),
					'outside' => __( 'Outside', 'powerpack' ),
				),
				'default'   => 'outside',
				'condition' => array(
					'feed_layout'     => 'carousel',
					'dots'            => 'yes',
					'pagination_type' => 'bullets',
				),
			)
		);

		$this->add_responsive_control(
			'dots_size',
			array(
				'label'      => __( 'Size', 'powerpack' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => array(
					'px' => array(
						'min'  => 2,
						'max'  => 40,
						'step' => 1,
					),
				),
				'size_units' => '',
				'selectors'  => array(
					'{{WRAPPER}} .pp-instagram-feed .swiper-pagination-bullet' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}}',
				),
				'condition'  => array(
					'feed_layout'     => 'carousel',
					'dots'            => 'yes',
					'pagination_type' => 'bullets',
				),
			)
		);

		$this->add_responsive_control(
			'dots_spacing',
			array(
				'label'      => __( 'Spacing', 'powerpack' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => array(
					'px' => array(
						'min'  => 1,
						'max'  => 30,
						'step' => 1,
					),
				),
				'size_units' => '',
				'selectors'  => array(
					'{{WRAPPER}} .pp-instagram-feed .swiper-pagination-bullet' => 'margin-left: {{SIZE}}{{UNIT}}; margin-right: {{SIZE}}{{UNIT}}',
				),
				'condition'  => array(
					'feed_layout'     => 'carousel',
					'dots'            => 'yes',
					'pagination_type' => 'bullets',
				),
			)
		);

		$this->start_controls_tabs( 'tabs_dots_style' );

		$this->start_controls_tab(
			'tab_dots_normal',
			array(
				'label'     => __( 'Normal', 'powerpack' ),
				'condition' => array(
					'feed_layout'     => 'carousel',
					'dots'            => 'yes',
					'pagination_type' => 'bullets',
				),
			)
		);

		$this->add_control(
			'dots_color_normal',
			array(
				'label'     => __( 'Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .pp-instagram-feed .swiper-pagination-bullet' => 'background: {{VALUE}};',
				),
				'condition' => array(
					'feed_layout'     => 'carousel',
					'dots'            => 'yes',
					'pagination_type' => 'bullets',
				),
			)
		);

		$this->add_control(
			'active_dot_color_normal',
			array(
				'label'     => __( 'Active Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .pp-instagram-feed .swiper-pagination-bullet-active' => 'background: {{VALUE}};',
				),
				'condition' => array(
					'feed_layout'     => 'carousel',
					'dots'            => 'yes',
					'pagination_type' => 'bullets',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'dots_border_normal',
				'label'       => __( 'Border', 'powerpack' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .pp-instagram-feed .swiper-pagination-bullet',
				'condition'   => array(
					'feed_layout'     => 'carousel',
					'dots'            => 'yes',
					'pagination_type' => 'bullets',
				),
			)
		);

		$this->add_control(
			'dots_border_radius_normal',
			array(
				'label'      => __( 'Border Radius', 'powerpack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .pp-instagram-feed .swiper-pagination-bullet' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					'feed_layout'     => 'carousel',
					'dots'            => 'yes',
					'pagination_type' => 'bullets',
				),
			)
		);

		$this->add_responsive_control(
			'dots_margin',
			array(
				'label'              => __( 'Margin', 'powerpack' ),
				'type'               => Controls_Manager::DIMENSIONS,
				'size_units'         => array( 'px', 'em', '%' ),
				'allowed_dimensions' => 'vertical',
				'placeholder'        => array(
					'top'    => '',
					'right'  => 'auto',
					'bottom' => '',
					'left'   => 'auto',
				),
				'selectors'          => array(
					'{{WRAPPER}} .pp-instagram-feed .swiper-pagination-bullets' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'          => array(
					'feed_layout'     => 'carousel',
					'dots'            => 'yes',
					'pagination_type' => 'bullets',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_dots_hover',
			array(
				'label'     => __( 'Hover', 'powerpack' ),
				'condition' => array(
					'feed_layout'     => 'carousel',
					'dots'            => 'yes',
					'pagination_type' => 'bullets',
				),
			)
		);

		$this->add_control(
			'dots_color_hover',
			array(
				'label'     => __( 'Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .pp-instagram-feed .swiper-pagination-bullet:hover' => 'background: {{VALUE}};',
				),
				'condition' => array(
					'feed_layout'     => 'carousel',
					'dots'            => 'yes',
					'pagination_type' => 'bullets',
				),
			)
		);

		$this->add_control(
			'dots_border_color_hover',
			array(
				'label'     => __( 'Border Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .pp-instagram-feed .swiper-pagination-bullet:hover' => 'border-color: {{VALUE}};',
				),
				'condition' => array(
					'feed_layout'     => 'carousel',
					'dots'            => 'yes',
					'pagination_type' => 'bullets',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		/**
		 * Style Tab: Pagination: Fraction
		 * -------------------------------------------------
		 */
		$this->start_controls_section(
			'section_fraction_style',
			array(
				'label'     => __( 'Pagination: Fraction', 'powerpack' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'feed_layout'     => 'carousel',
					'dots'            => 'yes',
					'pagination_type' => 'fraction',
				),
			)
		);

		$this->add_control(
			'fraction_text_color',
			array(
				'label'     => __( 'Text Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .swiper-pagination-fraction' => 'color: {{VALUE}};',
				),
				'condition' => array(
					'feed_layout'     => 'carousel',
					'dots'            => 'yes',
					'pagination_type' => 'fraction',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'fraction_typography',
				'label'     => __( 'Typography', 'powerpack' ),
				'scheme'    => Scheme_Typography::TYPOGRAPHY_4,
				'selector'  => '{{WRAPPER}} .swiper-pagination-fraction',
				'condition' => array(
					'feed_layout'     => 'carousel',
					'dots'            => 'yes',
					'pagination_type' => 'fraction',
				),
			)
		);

		$this->end_controls_section();

		/**
		 * Style Tab: Button
		 * -------------------------------------------------
		 */
		$this->start_controls_section(
			'section_load_more_button_style',
			array(
				'label'     => __( 'Load More Button', 'powerpack' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'use_api'          => 'yes',
					'load_more_button' => 'yes',
					'feed_layout'      => 'grid',
				),
			)
		);

		$this->add_responsive_control(
			'button_alignment',
			array(
				'label'     => __( 'Alignment', 'powerpack' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'left'   => array(
						'title' => __( 'Left', 'powerpack' ),
						'icon'  => 'fa fa-align-left',
					),
					'center' => array(
						'title' => __( 'Center', 'powerpack' ),
						'icon'  => 'fa fa-align-center',
					),
					'right'  => array(
						'title' => __( 'Right', 'powerpack' ),
						'icon'  => 'fa fa-align-right',
					),
				),
				'default'   => 'center',
				'selectors' => array(
					'{{WRAPPER}} .pp-load-more-button-wrap' => 'text-align: {{VALUE}};',
				),
				'condition' => array(
					'use_api'          => 'yes',
					'load_more_button' => 'yes',
					'feed_layout'      => 'grid',
				),
			)
		);

		$this->add_control(
			'button_top_spacing',
			array(
				'label'      => __( 'Top Spacing', 'powerpack' ),
				'type'       => Controls_Manager::SLIDER,
				'default'    => array( 'size' => 20 ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					),
				),
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'{{WRAPPER}} .pp-load-more-button-wrap' => 'margin-top: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					'use_api'          => 'yes',
					'load_more_button' => 'yes',
					'feed_layout'      => 'grid',
				),
			)
		);

		$this->add_control(
			'button_size',
			array(
				'label'     => __( 'Size', 'powerpack' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'md',
				'options'   => array(
					'xs' => __( 'Extra Small', 'powerpack' ),
					'sm' => __( 'Small', 'powerpack' ),
					'md' => __( 'Medium', 'powerpack' ),
					'lg' => __( 'Large', 'powerpack' ),
					'xl' => __( 'Extra Large', 'powerpack' ),
				),
				'condition' => array(
					'use_api'          => 'yes',
					'load_more_button' => 'yes',
					'feed_layout'      => 'grid',
				),
			)
		);

		$this->start_controls_tabs( 'tabs_button_style' );

		$this->start_controls_tab(
			'tab_button_normal',
			array(
				'label'     => __( 'Normal', 'powerpack' ),
				'condition' => array(
					'use_api'          => 'yes',
					'load_more_button' => 'yes',
					'feed_layout'      => 'grid',
				),
			)
		);

		$this->add_control(
			'button_bg_color_normal',
			array(
				'label'     => __( 'Background Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .pp-load-more-button' => 'background-color: {{VALUE}}',
				),
				'condition' => array(
					'use_api'          => 'yes',
					'load_more_button' => 'yes',
					'feed_layout'      => 'grid',
				),
			)
		);

		$this->add_control(
			'button_text_color_normal',
			array(
				'label'     => __( 'Text Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .pp-load-more-button' => 'color: {{VALUE}}',
				),
				'condition' => array(
					'use_api'          => 'yes',
					'load_more_button' => 'yes',
					'feed_layout'      => 'grid',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'button_border_normal',
				'label'       => __( 'Border', 'powerpack' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .pp-load-more-button',
				'condition'   => array(
					'use_api'          => 'yes',
					'load_more_button' => 'yes',
					'feed_layout'      => 'grid',
				),
			)
		);

		$this->add_control(
			'button_border_radius',
			array(
				'label'      => __( 'Border Radius', 'powerpack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .pp-load-more-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					'use_api'          => 'yes',
					'load_more_button' => 'yes',
					'feed_layout'      => 'grid',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'button_typography',
				'label'     => __( 'Typography', 'powerpack' ),
				'scheme'    => Scheme_Typography::TYPOGRAPHY_4,
				'selector'  => '{{WRAPPER}} .pp-load-more-button',
				'condition' => array(
					'use_api'          => 'yes',
					'load_more_button' => 'yes',
					'feed_layout'      => 'grid',
				),
			)
		);

		$this->add_responsive_control(
			'button_padding',
			array(
				'label'      => __( 'Padding', 'powerpack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .pp-load-more-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					'use_api'          => 'yes',
					'load_more_button' => 'yes',
					'feed_layout'      => 'grid',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'      => 'button_box_shadow',
				'selector'  => '{{WRAPPER}} .pp-load-more-button',
				'condition' => array(
					'use_api'          => 'yes',
					'load_more_button' => 'yes',
					'feed_layout'      => 'grid',
				),
			)
		);

		$this->add_control(
			'load_more_button_icon_heading',
			array(
				'label'     => __( 'Button Icon', 'powerpack' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'use_api'          => 'yes',
					'load_more_button' => 'yes',
					'feed_layout'      => 'grid',
					'button_icon!'     => '',
				),
			)
		);

		$this->add_responsive_control(
			'button_icon_margin',
			array(
				'label'       => __( 'Margin', 'powerpack' ),
				'type'        => Controls_Manager::DIMENSIONS,
				'size_units'  => array( 'px', '%' ),
				'placeholder' => array(
					'top'    => '',
					'right'  => '',
					'bottom' => '',
					'left'   => '',
				),
				'condition'   => array(
					'use_api'          => 'yes',
					'load_more_button' => 'yes',
					'feed_layout'      => 'grid',
					'button_icon!'     => '',
				),
				'selectors'   => array(
					'{{WRAPPER}} .pp-info-box .pp-button-icon' => 'margin-top: {{TOP}}{{UNIT}}; margin-left: {{LEFT}}{{UNIT}}; margin-right: {{RIGHT}}{{UNIT}}; margin-bottom: {{BOTTOM}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_button_hover',
			array(
				'label'     => __( 'Hover', 'powerpack' ),
				'condition' => array(
					'use_api'          => 'yes',
					'load_more_button' => 'yes',
					'feed_layout'      => 'grid',
				),
			)
		);

		$this->add_control(
			'button_bg_color_hover',
			array(
				'label'     => __( 'Background Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .pp-load-more-button:hover' => 'background-color: {{VALUE}}',
				),
				'condition' => array(
					'use_api'          => 'yes',
					'load_more_button' => 'yes',
					'feed_layout'      => 'grid',
				),
			)
		);

		$this->add_control(
			'button_text_color_hover',
			array(
				'label'     => __( 'Text Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .pp-load-more-button:hover' => 'color: {{VALUE}}',
				),
				'condition' => array(
					'use_api'          => 'yes',
					'load_more_button' => 'yes',
					'feed_layout'      => 'grid',
				),
			)
		);

		$this->add_control(
			'button_border_color_hover',
			array(
				'label'     => __( 'Border Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .pp-load-more-button:hover' => 'border-color: {{VALUE}}',
				),
				'condition' => array(
					'use_api'          => 'yes',
					'load_more_button' => 'yes',
					'feed_layout'      => 'grid',
				),
			)
		);

		$this->add_control(
			'button_animation',
			array(
				'label'     => __( 'Animation', 'powerpack' ),
				'type'      => Controls_Manager::HOVER_ANIMATION,
				'condition' => array(
					'use_api'          => 'yes',
					'load_more_button' => 'yes',
					'feed_layout'      => 'grid',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'      => 'button_box_shadow_hover',
				'selector'  => '{{WRAPPER}} .pp-load-more-button:hover',
				'condition' => array(
					'use_api'          => 'yes',
					'load_more_button' => 'yes',
					'feed_layout'      => 'grid',
				),
			)
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->end_controls_section();

	}

	/**
	 * Slider Settings.
	 *
	 * @access public
	 */
	public function slider_settings() {
		$settings = $this->get_settings();

		$slider_options = array(
			'direction'     => 'horizontal',
			'speed'         => 400,
			'slidesPerView' => ( $settings['items']['size'] !== '' ) ? absint( $settings['items']['size'] ) : 3,
			'spaceBetween'  => ( $settings['margin']['size'] !== '' ) ? $settings['margin']['size'] : 10,
			'grabCursor'    => ( $settings['grab_cursor'] === 'yes' ),
			'autoHeight'    => true,
			'loop'          => ( $settings['infinite_loop'] === 'yes' ),
		);

		if ( $settings['autoplay'] == 'yes' && ! empty( $settings['autoplay_speed'] ) ) {
			$autoplay_speed = $settings['autoplay_speed'];
		} else {
			$autoplay_speed = 999999;
		}

		$slider_options['autoplay'] = array(
			'delay' => $autoplay_speed,
		);

		if ( $settings['dots'] == 'yes' ) {
			$slider_options['pagination'] = array(
				'el'        => '.swiper-pagination-' . esc_attr( $this->get_id() ),
				'type'      => $settings['pagination_type'],
				'clickable' => true,
			);
		}

		if ( $settings['arrows'] == 'yes' ) {
			$slider_options['navigation'] = array(
				'nextEl' => '.swiper-button-next-' . esc_attr( $this->get_id() ),
				'prevEl' => '.swiper-button-prev-' . esc_attr( $this->get_id() ),
			);
		}

		$elementor_bp_lg = get_option( 'elementor_viewport_lg' );
		$elementor_bp_md = get_option( 'elementor_viewport_md' );
		$bp_desktop      = ! empty( $elementor_bp_lg ) ? $elementor_bp_lg : 1025;
		$bp_tablet       = ! empty( $elementor_bp_md ) ? $elementor_bp_md : 768;
		$bp_mobile       = 320;

		$slider_options['breakpoints'] = array(
			$bp_desktop => array(
				'slidesPerView' => ( $settings['items']['size'] !== '' ) ? absint( $settings['items']['size'] ) : 2,
				'spaceBetween'  => ( $settings['margin']['size'] !== '' ) ? $settings['margin']['size'] : 10,
			),
			$bp_tablet  => array(
				'slidesPerView' => ( $settings['items_tablet']['size'] !== '' ) ? absint( $settings['items_tablet']['size'] ) : 2,
				'spaceBetween'  => ( $settings['margin_tablet']['size'] !== '' ) ? $settings['margin_tablet']['size'] : 10,
			),
			$bp_mobile  => array(
				'slidesPerView' => ( $settings['items_mobile']['size'] !== '' ) ? absint( $settings['items_mobile']['size'] ) : 1,
				'spaceBetween'  => ( $settings['margin_mobile']['size'] !== '' ) ? $settings['margin_mobile']['size'] : 10,
			),
		);

		$this->add_render_attribute(
			'insta-feed-container',
			array(
				'data-slider-settings' => wp_json_encode( $slider_options ),
			)
		);
	}

	/**
	 * Render promo box widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings();

		if ( $settings['feed_layout'] == 'carousel' ) {
			$layout = 'carousel';
		} else {
			$layout = 'grid';
		}

		$this->add_render_attribute(
			'insta-feed-wrap',
			'class',
			array(
				'pp-instagram-feed',
				'clearfix',
				'pp-instagram-feed-' . $layout,
				'pp-instagram-feed-' . $settings['content_visibility'],
			)
		);

		if ( ( $settings['feed_layout'] == 'grid' || $settings['feed_layout'] == 'masonry' ) && $settings['grid_cols'] ) {
			$this->add_render_attribute( 'insta-feed-wrap', 'class', 'pp-instagram-feed-grid-' . $settings['grid_cols'] );
		}

		if ( $settings['insta_image_grayscale'] == 'yes' ) {
			$this->add_render_attribute( 'insta-feed-wrap', 'class', 'pp-instagram-feed-gray' );
		}

		if ( $settings['insta_image_grayscale_hover'] == 'yes' ) {
			$this->add_render_attribute( 'insta-feed-wrap', 'class', 'pp-instagram-feed-hover-gray' );
		}

		if ( $settings['feed_layout'] != 'masonry' && $settings['square_images'] == 'yes' ) {
			$this->add_render_attribute( 'insta-feed-wrap', 'class', 'pp-if-square-images' );
		}

		$this->add_render_attribute( 'insta-feed-container', 'class', 'pp-instafeed' );

		$this->add_render_attribute(
			'insta-feed',
			array(
				'id'    => 'pp-instafeed-' . esc_attr( $this->get_id() ),
				'class' => 'pp-instafeed-grid',
			)
		);

		$this->add_render_attribute( 'insta-feed-inner', 'class', 'pp-insta-feed-inner' );

		if ( ! isset( $settings['insta_title_icon'] ) && ! Icons_Manager::is_migration_allowed() ) {
			// add old default
			$settings['insta_title_icon'] = 'fa fa-instagram';
		}

		$has_icon = ! empty( $settings['insta_title_icon'] );

		if ( $has_icon ) {
			$this->add_render_attribute( 'i', 'class', $settings['insta_title_icon'] );
			$this->add_render_attribute( 'i', 'aria-hidden', 'true' );
		}

		if ( ! $has_icon && ! empty( $settings['title_icon']['value'] ) ) {
			$has_icon = true;
		}
		$migrated = isset( $settings['__fa4_migrated']['title_icon'] );
		$is_new   = ! isset( $settings['insta_title_icon'] ) && Icons_Manager::is_migration_allowed();

		$this->add_render_attribute( 'title-icon', 'class', 'pp-icon pp-icon-' . $settings['insta_title_icon_position'] );

		if ( $settings['feed_layout'] == 'carousel' ) {

			$this->add_render_attribute(
				'insta-feed-inner',
				'class',
				array(
					'swiper-container-wrap',
					'pp-insta-feed-carousel-wrap',
				)
			);

			$this->add_render_attribute(
				'insta-feed-container',
				'class',
				array(
					'swiper-container',
					'swiper-container-' . esc_attr( $this->get_id() ),
				)
			);

			$this->slider_settings();

			if ( $settings['dots_position'] ) {
				$this->add_render_attribute( 'insta-feed-inner', 'class', 'swiper-container-dots-' . $settings['dots_position'] );
			} elseif ( $settings['pagination_type'] == 'fraction' ) {
				$this->add_render_attribute( 'insta-feed-inner', 'class', 'swiper-container-dots-outside' );
			}

			if ( $settings['direction'] == 'right' ) {
				$this->add_render_attribute( 'insta-feed-container', 'dir', 'rtl' );
			}

			$this->add_render_attribute( 'insta-feed', 'class', 'swiper-wrapper' );
		}

		if ( ! empty( $settings['insta_profile_url']['url'] ) ) {
			$this->add_link_attributes( 'instagram-profile-link', $settings['insta_profile_url'] );
		}

		$pp_widget_options = array(
			'user_id'      => ! empty( $settings['user_id'] ) ? $settings['user_id'] : '',
			'access_token' => ! empty( $settings['access_token'] ) ? $settings['access_token'] : '',
			'sort_by'      => ! empty( $settings['sort_by'] ) ? $settings['sort_by'] : '',
			'images_count' => ! empty( $settings['images_count']['size'] ) ? $settings['images_count']['size'] : '3',
			'target'       => 'pp-instafeed-' . esc_attr( $this->get_id() ),
			'resolution'   => ! empty( $settings['resolution'] ) ? $settings['resolution'] : '',
			'popup'        => ( $settings['insta_image_popup'] == 'yes' ) ? '1' : '0',
			'img_link'     => ( $settings['insta_image_popup'] != 'yes' && $settings['insta_image_link'] == 'yes' ) ? '1' : '0',
		);
		?>
		<div <?php echo $this->get_render_attribute_string( 'insta-feed-wrap' ); ?> data-settings='<?php echo wp_json_encode( $pp_widget_options ); ?>'>
			
			<div <?php echo $this->get_render_attribute_string( 'insta-feed-inner' ); ?>>
				<div <?php echo $this->get_render_attribute_string( 'insta-feed-container' ); ?>>
					<?php if ( $settings['insta_profile_link'] == 'yes' && $settings['insta_link_title'] ) { ?>
						<span class="pp-instagram-feed-title-wrap">
							<a <?php echo $this->get_render_attribute_string( 'instagram-profile-link' ); ?>>
								<span class="pp-instagram-feed-title">
									<?php if ( $settings['insta_title_icon_position'] == 'before_title' && $has_icon ) { ?>
									<span <?php echo $this->get_render_attribute_string( 'title-icon' ); ?>>
										<?php
										if ( $is_new || $migrated ) {
											Icons_Manager::render_icon( $settings['title_icon'], array( 'aria-hidden' => 'true' ) );
										} elseif ( ! empty( $settings['insta_title_icon'] ) ) {
											?>
											<i <?php echo $this->get_render_attribute_string( 'i' ); ?>></i>
											<?php
										}
										?>
									</span>
									<?php } ?>

									<?php echo esc_attr( $settings['insta_link_title'] ); ?>

									<?php if ( $settings['insta_title_icon_position'] == 'after_title' && $has_icon ) { ?>
									<span <?php echo $this->get_render_attribute_string( 'title-icon' ); ?>>
										<?php
										if ( $is_new || $migrated ) {
											Icons_Manager::render_icon( $settings['title_icon'], array( 'aria-hidden' => 'true' ) );
										} elseif ( ! empty( $settings['insta_title_icon'] ) ) {
											?>
											<i <?php echo $this->get_render_attribute_string( 'i' ); ?>></i>
											<?php
										}
										?>
									</span>
									<?php } ?>
								</span>
							</a>
						</span>
					<?php } ?>
					<div <?php echo $this->get_render_attribute_string( 'insta-feed' ); ?>></div>
				</div>
				<?php
					$this->render_load_more_button();

					$this->render_dots();

					$this->render_arrows();
				?>
			</div>
		</div>
		<?php
	}

	/**
	 * Render load more button output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @access protected
	 */
	protected function render_load_more_button() {
		$settings = $this->get_settings();

		$this->add_render_attribute(
			'load-more-button',
			'class',
			array(
				'pp-load-more-button',
				'elementor-button',
				'elementor-size-' . $settings['button_size'],
			)
		);

		if ( $settings['button_animation'] ) {
			$this->add_render_attribute( 'load-more-button', 'class', 'elementor-animation-' . $settings['button_animation'] );
		}

		if ( $settings['feed_layout'] == 'grid' && $settings['use_api'] == 'yes' && $settings['load_more_button'] == 'yes' ) {
			?>
			<div class="pp-load-more-button-wrap">
				<div <?php echo $this->get_render_attribute_string( 'load-more-button' ); ?>>
					<span class="pp-button-loader"></span>
					<span class="pp-load-more-button-text">
						<?php echo $settings['load_more_button_text']; ?>
					</span>
				</div>
			</div>
			<?php
		}
	}

	/**
	 * Render insta feed carousel dots output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @access protected
	 */
	protected function render_dots() {
		$settings = $this->get_settings();

		if ( $settings['feed_layout'] == 'carousel' && $settings['dots'] == 'yes' ) {
			?>
			<!-- Add Pagination -->
			<div class="swiper-pagination swiper-pagination-<?php echo esc_attr( $this->get_id() ); ?>"></div>
			<?php
		}
	}

	/**
	 * Render insta feed carousel arrows output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @access protected
	 */
	protected function render_arrows() {
		$settings = $this->get_settings();

		if ( $settings['feed_layout'] == 'carousel' && $settings['arrows'] == 'yes' ) {
			?>
			<?php
			if ( $settings['arrow'] ) {
				$pa_next_arrow = $settings['arrow'];
				$pa_prev_arrow = str_replace( 'right', 'left', $settings['arrow'] );
			} else {
				$pa_next_arrow = 'fa fa-angle-right';
				$pa_prev_arrow = 'fa fa-angle-left';
			}
			?>
			<!-- Add Arrows -->
			<div class="swiper-button-next swiper-button-next-<?php echo esc_attr( $this->get_id() ); ?>">
				<i class="<?php echo esc_attr( $pa_next_arrow ); ?>"></i>
			</div>
			<div class="swiper-button-prev swiper-button-prev-<?php echo esc_attr( $this->get_id() ); ?>">
				<i class="<?php echo esc_attr( $pa_prev_arrow ); ?>"></i>
			</div>
			<?php
		}
	}

	protected function content_template() {}

}
