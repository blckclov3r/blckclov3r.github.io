<?php
namespace PowerpackElementsLite\Modules\Pricing\Widgets;

use PowerpackElementsLite\Base\Powerpack_Widget;

// Elementor Classes
use Elementor\Controls_Manager;
use Elementor\Utils;
use Elementor\Repeater;
use Elementor\Icons_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Image_Size;
use Elementor\Scheme_Typography;
use Elementor\Scheme_Color;
use Elementor\Modules\DynamicTags\Module as TagsModule;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Pricing Table Widget
 */
class Pricing_Table extends Powerpack_Widget {

	/**
	 * Retrieve pricing table widget name.
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return parent::get_widget_name( 'Pricing_Table' );
	}

	/**
	 * Retrieve pricing table widget title.
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return parent::get_widget_title( 'Pricing_Table' );
	}

	/**
	 * Retrieve pricing table widget icon.
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return parent::get_widget_icon( 'Pricing_Table' );
	}

	/**
	 * Get widget keywords.
	 *
	 * Retrieve the list of keywords the widget belongs to.
	 *
	 * @since 1.3.7
	 * @access public
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return parent::get_widget_keywords( 'Pricing_Table' );
	}

	/**
	 * Register pricing table widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @access protected
	 */
	protected function _register_controls() {

		/*
		-----------------------------------------------------------------------------------*/
		/*
		  CONTENT TAB
		/*-----------------------------------------------------------------------------------*/

		/**
		 * Content Tab: Header
		 * -------------------------------------------------
		 */
		$this->start_controls_section(
			'section_header',
			array(
				'label' => __( 'Header', 'powerpack' ),
			)
		);

		$this->add_control(
			'icon_type',
			array(
				'label'       => esc_html__( 'Icon Type', 'powerpack' ),
				'type'        => Controls_Manager::CHOOSE,
				'label_block' => false,
				'options'     => array(
					'none'  => array(
						'title' => esc_html__( 'None', 'powerpack' ),
						'icon'  => 'fa fa-ban',
					),
					'icon'  => array(
						'title' => esc_html__( 'Icon', 'powerpack' ),
						'icon'  => 'fa fa-star',
					),
					'image' => array(
						'title' => esc_html__( 'Image', 'powerpack' ),
						'icon'  => 'fa fa-picture-o',
					),
				),
				'default'     => 'none',
			)
		);

		$this->add_control(
			'select_table_icon',
			array(
				'label'            => __( 'Icon', 'powerpack' ),
				'type'             => Controls_Manager::ICONS,
				'fa4compatibility' => 'table_icon',
				'default'          => array(
					'value'   => 'fas fa-star',
					'library' => 'fa-solid',
				),
				'condition'        => array(
					'icon_type' => 'icon',
				),
			)
		);

		$this->add_control(
			'icon_image',
			array(
				'label'     => __( 'Image', 'powerpack' ),
				'type'      => Controls_Manager::MEDIA,
				'default'   => array(
					'url' => Utils::get_placeholder_image_src(),
				),
				'condition' => array(
					'icon_type' => 'image',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			array(
				'name'      => 'image', // Usage: '{name}_size' and '{name}_custom_dimension', in this case 'image_size' and 'image_custom_dimension'.
				'default'   => 'full',
				'separator' => 'none',
				'condition' => array(
					'icon_type' => 'image',
				),
			)
		);

		$this->add_control(
			'table_title',
			array(
				'label'   => __( 'Title', 'powerpack' ),
				'type'    => Controls_Manager::TEXT,
				'dynamic' => array(
					'active' => true,
				),
				'default' => __( 'Title', 'powerpack' ),
				'title'   => __( 'Enter table title', 'powerpack' ),
			)
		);

		$this->add_control(
			'table_subtitle',
			array(
				'label'   => __( 'Subtitle', 'powerpack' ),
				'type'    => Controls_Manager::TEXT,
				'dynamic' => array(
					'active' => true,
				),
				'default' => __( 'Subtitle', 'powerpack' ),
				'title'   => __( 'Enter table subtitle', 'powerpack' ),
			)
		);

		$this->end_controls_section();

		/**
		 * Content Tab: Pricing
		 * -------------------------------------------------
		 */
		$this->start_controls_section(
			'section_pricing',
			array(
				'label' => __( 'Pricing', 'powerpack' ),
			)
		);

		$this->add_control(
			'currency_symbol',
			array(
				'label'   => __( 'Currency Symbol', 'powerpack' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					''             => __( 'None', 'powerpack' ),
					'dollar'       => '&#36; ' . __( 'Dollar', 'powerpack' ),
					'euro'         => '&#128; ' . __( 'Euro', 'powerpack' ),
					'baht'         => '&#3647; ' . __( 'Baht', 'powerpack' ),
					'franc'        => '&#8355; ' . __( 'Franc', 'powerpack' ),
					'guilder'      => '&fnof; ' . __( 'Guilder', 'powerpack' ),
					'krona'        => 'kr ' . __( 'Krona', 'powerpack' ),
					'lira'         => '&#8356; ' . __( 'Lira', 'powerpack' ),
					'peseta'       => '&#8359 ' . __( 'Peseta', 'powerpack' ),
					'peso'         => '&#8369; ' . __( 'Peso', 'powerpack' ),
					'pound'        => '&#163; ' . __( 'Pound Sterling', 'powerpack' ),
					'real'         => 'R$ ' . __( 'Real', 'powerpack' ),
					'ruble'        => '&#8381; ' . __( 'Ruble', 'powerpack' ),
					'rupee'        => '&#8360; ' . __( 'Rupee', 'powerpack' ),
					'indian_rupee' => '&#8377; ' . __( 'Rupee (Indian)', 'powerpack' ),
					'shekel'       => '&#8362; ' . __( 'Shekel', 'powerpack' ),
					'yen'          => '&#165; ' . __( 'Yen/Yuan', 'powerpack' ),
					'won'          => '&#8361; ' . __( 'Won', 'powerpack' ),
					'custom'       => __( 'Custom', 'powerpack' ),
				),
				'default' => 'dollar',
			)
		);

		$this->add_control(
			'currency_symbol_custom',
			array(
				'label'     => __( 'Custom Symbol', 'powerpack' ),
				'type'      => Controls_Manager::TEXT,
				'dynamic'   => array(
					'active' => true,
				),
				'default'   => '',
				'condition' => array(
					'currency_symbol' => 'custom',
				),
			)
		);

		$this->add_control(
			'table_price',
			array(
				'label'   => __( 'Price', 'powerpack' ),
				'type'    => Controls_Manager::TEXT,
				'dynamic' => array(
					'active' => true,
				),
				'default' => '49',
			)
		);

		$this->add_control(
			'currency_format',
			array(
				'label'   => __( 'Currency Format', 'powerpack' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '',
				'options' => array(
					'raised' => __( 'Raised', 'powerpack' ),
					''       => __( 'Normal', 'powerpack' ),
				),
			)
		);

		$this->add_control(
			'discount',
			array(
				'label'        => __( 'Discount', 'powerpack' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '',
				'label_on'     => __( 'On', 'powerpack' ),
				'label_off'    => __( 'Off', 'powerpack' ),
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'table_original_price',
			array(
				'label'     => __( 'Original Price', 'powerpack' ),
				'type'      => Controls_Manager::TEXT,
				'dynamic'   => array(
					'active' => true,
				),
				'default'   => '69',
				'condition' => array(
					'discount' => 'yes',
				),
			)
		);

		$this->add_control(
			'table_duration',
			array(
				'label'   => __( 'Duration', 'powerpack' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'per month', 'powerpack' ),
			)
		);

		$this->end_controls_section();

		/**
		 * Content Tab: Features
		 * -------------------------------------------------
		 */
		$this->start_controls_section(
			'section_features',
			array(
				'label' => __( 'Features', 'powerpack' ),
			)
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'feature_text',
			array(
				'label'       => __( 'Text', 'powerpack' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => array(
					'active' => true,
				),
				'placeholder' => __( 'Feature', 'powerpack' ),
				'default'     => __( 'Feature', 'powerpack' ),
			)
		);

		$repeater->add_control(
			'exclude',
			array(
				'label'        => __( 'Exclude', 'powerpack' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '',
				'label_on'     => __( 'Yes', 'powerpack' ),
				'label_off'    => __( 'No', 'powerpack' ),
				'return_value' => 'yes',
			)
		);

		$repeater->add_control(
			'select_feature_icon',
			array(
				'label'            => __( 'Icon', 'powerpack' ),
				'type'             => Controls_Manager::ICONS,
				'label_block'      => true,
				'default'          => array(
					'value'   => 'fas fa-check',
					'library' => 'fa-solid',
				),
				'fa4compatibility' => 'feature_icon',
			)
		);

		$repeater->add_control(
			'feature_icon_color',
			array(
				'label'     => __( 'Icon Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} {{CURRENT_ITEM}} .pp-icon i' => 'color: {{VALUE}}',
					'{{WRAPPER}} {{CURRENT_ITEM}} .pp-icon svg' => 'fill: {{VALUE}}',
				),
				'condition' => array(
					'select_feature_icon[value]!' => '',
				),
			)
		);

		$repeater->add_control(
			'feature_text_color',
			array(
				'label'     => __( 'Text Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} {{CURRENT_ITEM}}' => 'color: {{VALUE}}',
				),
			)
		);

		$repeater->add_control(
			'feature_bg_color',
			array(
				'name'      => 'feature_bg_color',
				'label'     => __( 'Background Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} {{CURRENT_ITEM}}' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'table_features',
			array(
				'label'       => '',
				'type'        => Controls_Manager::REPEATER,
				'default'     => array(
					array(
						'feature_text'        => __( 'Feature #1', 'powerpack' ),
						'select_feature_icon' => 'fa fa-check',
					),
					array(
						'feature_text'        => __( 'Feature #2', 'powerpack' ),
						'select_feature_icon' => 'fa fa-check',
					),
					array(
						'feature_text'        => __( 'Feature #3', 'powerpack' ),
						'select_feature_icon' => 'fa fa-check',
					),
				),
				'fields'      => $repeater->get_controls(),
				'title_field' => '{{{ feature_text }}}',
			)
		);

		$this->end_controls_section();

		/**
		 * Content Tab: Ribbon
		 * -------------------------------------------------
		 */
		$this->start_controls_section(
			'section_ribbon',
			array(
				'label' => __( 'Ribbon', 'powerpack' ),
			)
		);

		$this->add_control(
			'show_ribbon',
			array(
				'label'        => __( 'Show Ribbon', 'powerpack' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '',
				'label_on'     => __( 'Yes', 'powerpack' ),
				'label_off'    => __( 'No', 'powerpack' ),
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'ribbon_style',
			array(
				'label'     => __( 'Style', 'powerpack' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => '1',
				'options'   => array(
					'1' => __( 'Default', 'powerpack' ),
					'2' => __( 'Circle', 'powerpack' ),
					'3' => __( 'Flag', 'powerpack' ),
				),
				'condition' => array(
					'show_ribbon' => 'yes',
				),
			)
		);

		$this->add_control(
			'ribbon_title',
			array(
				'label'     => __( 'Title', 'powerpack' ),
				'type'      => Controls_Manager::TEXT,
				'dynamic'   => array(
					'active' => true,
				),
				'default'   => __( 'New', 'powerpack' ),
				'condition' => array(
					'show_ribbon' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'ribbon_size',
			array(
				'label'      => __( 'Size', 'powerpack' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em' ),
				'range'      => array(
					'px' => array(
						'min' => 1,
						'max' => 200,
					),
					'em' => array(
						'min' => 1,
						'max' => 15,
					),
				),
				'default'    => array(
					'size' => 4,
					'unit' => 'em',
				),
				'selectors'  => array(
					'{{WRAPPER}} .pp-pricing-table-ribbon-2' => 'min-width: {{SIZE}}{{UNIT}}; min-height: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					'show_ribbon'  => 'yes',
					'ribbon_style' => array( '2' ),
				),
			)
		);

		$this->add_responsive_control(
			'top_distance',
			array(
				'label'      => __( 'Distance from Top', 'powerpack' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array(
						'min' => 1,
						'max' => 200,
					),
				),
				'default'    => array(
					'size' => 20,
					'unit' => '%',
				),
				'selectors'  => array(
					'{{WRAPPER}} .pp-pricing-table-ribbon' => 'top: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					'show_ribbon'  => 'yes',
					'ribbon_style' => array( '2', '3' ),
				),
			)
		);

		$ribbon_distance_transform = is_rtl() ? 'translateY(-50%) translateX({{SIZE}}{{UNIT}}) rotate(-45deg)' : 'translateY(-50%) translateX(-50%) translateX({{SIZE}}{{UNIT}}) rotate(-45deg)';

		$this->add_responsive_control(
			'ribbon_distance',
			array(
				'label'     => __( 'Distance', 'powerpack' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 50,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .pp-pricing-table-ribbon-inner' => 'margin-top: {{SIZE}}{{UNIT}}; transform: ' . $ribbon_distance_transform,
				),
				'condition' => array(
					'show_ribbon'  => 'yes',
					'ribbon_style' => array( '1' ),
				),
			)
		);

		$this->add_control(
			'ribbon_position',
			array(
				'label'       => __( 'Position', 'powerpack' ),
				'type'        => Controls_Manager::CHOOSE,
				'toggle'      => false,
				'label_block' => false,
				'options'     => array(
					'left'  => array(
						'title' => __( 'Left', 'powerpack' ),
						'icon'  => 'eicon-h-align-left',
					),
					'right' => array(
						'title' => __( 'Right', 'powerpack' ),
						'icon'  => 'eicon-h-align-right',
					),
				),
				'default'     => 'right',
				'condition'   => array(
					'show_ribbon'  => 'yes',
					'ribbon_style' => array( '1', '2', '3' ),
				),
			)
		);

		$this->end_controls_section();

		/**
		 * Content Tab: Button
		 * -------------------------------------------------
		 */
		$this->start_controls_section(
			'section_button',
			array(
				'label' => __( 'Button', 'powerpack' ),
			)
		);

		$this->add_control(
			'table_button_position',
			array(
				'label'   => __( 'Button Position', 'powerpack' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'below',
				'options' => array(
					'above' => __( 'Above Features', 'powerpack' ),
					'below' => __( 'Below Features', 'powerpack' ),
					'none'  => __( 'None', 'powerpack' ),
				),
			)
		);

		$this->add_control(
			'table_button_text',
			array(
				'label'   => __( 'Button Text', 'powerpack' ),
				'type'    => Controls_Manager::TEXT,
				'dynamic' => array(
					'active' => true,
				),
				'default' => __( 'Get Started', 'powerpack' ),
			)
		);

		$this->add_control(
			'link',
			array(
				'label'       => __( 'Link', 'powerpack' ),
				'label_block' => true,
				'type'        => Controls_Manager::URL,
				'dynamic'     => array(
					'active' => true,
				),
				'placeholder' => 'https://www.your-link.com',
				'default'     => array(
					'url' => '#',
				),
			)
		);

		$this->add_control(
			'table_additional_info',
			array(
				'label'   => __( 'Additional Info', 'powerpack' ),
				'type'    => Controls_Manager::TEXTAREA,
				'dynamic' => array(
					'active' => true,
				),
				'default' => __( 'Enter additional info here', 'powerpack' ),
				'title'   => __( 'Additional Info', 'powerpack' ),
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
				'raw'             => sprintf( __( '%1$s Watch Video Overview %2$s', 'powerpack' ), '<a href="https://www.youtube.com/watch?v=cO-WFCHtwiM&list=PLpsSO_wNe8Dz4vfe2tWlySBCCFEgh1qZj" target="_blank" rel="noopener">', '</a>' ),
				'content_classes' => 'pp-editor-doc-links',
			)
		);

		$this->add_control(
			'help_doc_2',
			array(
				'type'            => Controls_Manager::RAW_HTML,
				/* translators: %1$s doc link */
				'raw'             => sprintf( __( '%1$s Widget Overview %2$s', 'powerpack' ), '<a href="https://powerpackelements.com/docs/powerpack/widgets/pricing-table/pricing-table-widget-overview/?utm_source=widget&utm_medium=panel&utm_campaign=userkb" target="_blank" rel="noopener">', '</a>' ),
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
		 * Content Tab: Table
		 * -------------------------------------------------
		 */
		$this->start_controls_section(
			'section_table_style',
			array(
				'label' => __( 'Table', 'powerpack' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'table_align',
			array(
				'label'        => __( 'Alignment', 'powerpack' ),
				'type'         => Controls_Manager::CHOOSE,
				'label_block'  => false,
				'options'      => array(
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
				'default'      => '',
				'prefix_class' => 'pp-pricing-table-align-',
			)
		);

		$this->end_controls_section();

		/**
		 * Style Tab: Header
		 * -------------------------------------------------
		 */
		$this->start_controls_section(
			'section_table_header_style',
			array(
				'label' => __( 'Header', 'powerpack' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'table_title_bg_color',
			array(
				'label'     => __( 'Background Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'scheme'    => array(
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_3,
				),
				'selectors' => array(
					'{{WRAPPER}} .pp-pricing-table-head' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'table_header_border',
				'label'       => __( 'Border', 'powerpack' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'condition'   => array(
					'table_button_text!' => '',
				),
				'selector'    => '{{WRAPPER}} .pp-pricing-table-head',
			)
		);

		$this->add_responsive_control(
			'table_title_padding',
			array(
				'label'      => __( 'Padding', 'powerpack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .pp-pricing-table-head' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'table_title_icon',
			array(
				'label'     => __( 'Icon', 'powerpack' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'icon_type!' => 'none',
				),
			)
		);

		$this->add_responsive_control(
			'table_icon_size',
			array(
				'label'      => __( 'Size', 'powerpack' ),
				'type'       => Controls_Manager::SLIDER,
				'default'    => array(
					'unit' => 'px',
					'size' => 26,
				),
				'range'      => array(
					'px' => array(
						'min'  => 5,
						'max'  => 100,
						'step' => 1,
					),
				),
				'size_units' => array( 'px', 'em' ),
				'condition'  => array(
					'icon_type'                 => 'icon',
					'select_table_icon[value]!' => '',
				),
				'selectors'  => array(
					'{{WRAPPER}} .pp-pricing-table-icon' => 'font-size: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_responsive_control(
			'table_icon_image_width',
			array(
				'label'      => __( 'Width', 'powerpack' ),
				'type'       => Controls_Manager::SLIDER,
				'default'    => array(
					'size' => 120,
					'unit' => 'px',
				),
				'range'      => array(
					'px' => array(
						'min'  => 1,
						'max'  => 1200,
						'step' => 1,
					),
				),
				'size_units' => array( 'px', '%' ),
				'condition'  => array(
					'icon_type'   => 'image',
					'icon_image!' => '',
				),
				'selectors'  => array(
					'{{WRAPPER}} .pp-pricing-table-icon' => 'width: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_control(
			'table_icon_bg_color',
			array(
				'label'     => __( 'Background Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'condition' => array(
					'icon_type!' => 'none',
				),
				'selectors' => array(
					'{{WRAPPER}} .pp-pricing-table-icon' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'table_icon_color',
			array(
				'label'     => __( 'Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'condition' => array(
					'icon_type'                 => 'icon',
					'select_table_icon[value]!' => '',
				),
				'selectors' => array(
					'{{WRAPPER}} .pp-pricing-table-icon' => 'color: {{VALUE}}',
					'{{WRAPPER}} .pp-pricing-table-icon svg' => 'fill: {{VALUE}}',
				),
			)
		);

		$this->add_responsive_control(
			'table_icon_margin',
			array(
				'label'      => __( 'Margin', 'powerpack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'condition'  => array(
					'icon_type!' => 'none',
				),
				'selectors'  => array(
					'{{WRAPPER}} .pp-pricing-table-icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'table_icon_padding',
			array(
				'label'      => __( 'Padding', 'powerpack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'condition'  => array(
					'icon_type!' => 'none',
				),
				'selectors'  => array(
					'{{WRAPPER}} .pp-pricing-table-icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'table_icon_border',
				'label'       => __( 'Border', 'powerpack' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'condition'   => array(
					'icon_type!' => 'none',
				),
				'selector'    => '{{WRAPPER}} .pp-pricing-table-icon',
			)
		);

		$this->add_control(
			'icon_border_radius',
			array(
				'label'      => __( 'Border Radius', 'powerpack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'condition'  => array(
					'icon_type!' => 'none',
				),
				'selectors'  => array(
					'{{WRAPPER}} .pp-pricing-table-icon, {{WRAPPER}} .pp-pricing-table-icon img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'table_title_heading',
			array(
				'label'     => __( 'Title', 'powerpack' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'table_title_color',
			array(
				'label'     => __( 'Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#fff',
				'selectors' => array(
					'{{WRAPPER}} .pp-pricing-table-title' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'table_title_typography',
				'label'    => __( 'Typography', 'powerpack' ),
				'scheme'   => Scheme_Typography::TYPOGRAPHY_4,
				'selector' => '{{WRAPPER}} .pp-pricing-table-title',
			)
		);

		$this->add_control(
			'table_subtitle_heading',
			array(
				'label'     => __( 'Sub Title', 'powerpack' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'table_subtitle!' => '',
				),
			)
		);

		$this->add_control(
			'table_subtitle_color',
			array(
				'label'     => __( 'Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#fff',
				'condition' => array(
					'table_subtitle!' => '',
				),
				'selectors' => array(
					'{{WRAPPER}} .pp-pricing-table-subtitle' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'table_subtitle_typography',
				'label'     => __( 'Typography', 'powerpack' ),
				'scheme'    => Scheme_Typography::TYPOGRAPHY_4,
				'condition' => array(
					'table_subtitle!' => '',
				),
				'selector'  => '{{WRAPPER}} .pp-pricing-table-subtitle',
			)
		);

		$this->add_responsive_control(
			'table_subtitle_spacing',
			array(
				'label'      => __( 'Spacing', 'powerpack' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					),
				),
				'size_units' => array( 'px', '%' ),
				'condition'  => array(
					'table_subtitle!' => '',
				),
				'selectors'  => array(
					'{{WRAPPER}} .pp-pricing-table-subtitle' => 'margin-top: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->end_controls_section();

		/**
		 * Style Tab: Pricing
		 * -------------------------------------------------
		 */
		$this->start_controls_section(
			'section_table_pricing_style',
			array(
				'label' => __( 'Pricing', 'powerpack' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'table_pricing_typography',
				'label'     => __( 'Typography', 'powerpack' ),
				'scheme'    => Scheme_Typography::TYPOGRAPHY_4,
				'selector'  => '{{WRAPPER}} .pp-pricing-table-price',
				'separator' => 'before',
			)
		);

		$this->add_control(
			'table_price_color_normal',
			array(
				'label'     => __( 'Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .pp-pricing-table-price' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'table_price_bg_color_normal',
			array(
				'label'     => __( 'Background Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#e6e6e6',
				'selectors' => array(
					'{{WRAPPER}} .pp-pricing-table-price' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'price_border_normal',
				'label'       => __( 'Border', 'powerpack' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .pp-pricing-table-price',
			)
		);

		$this->add_control(
			'pricing_border_radius',
			array(
				'label'      => __( 'Border Radius', 'powerpack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .pp-pricing-table-price' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'table_pricing_width',
			array(
				'label'      => __( 'Width', 'powerpack' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => array(
					'%'  => array(
						'min'  => 1,
						'max'  => 100,
						'step' => 1,
					),
					'px' => array(
						'min'  => 25,
						'max'  => 1200,
						'step' => 1,
					),
				),
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .pp-pricing-table-price' => 'width: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_responsive_control(
			'table_price_margin',
			array(
				'label'      => __( 'Margin', 'powerpack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .pp-pricing-table-price' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'table_price_padding',
			array(
				'label'      => __( 'Padding', 'powerpack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .pp-pricing-table-price' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'pa_logo_wrapper_shadow',
				'selector' => '{{WRAPPER}} .pp-pricing-table-price',
			)
		);

		$this->add_control(
			'table_curreny_heading',
			array(
				'label'     => __( 'Currency Symbol', 'powerpack' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'currency_symbol!' => '',
				),
			)
		);

		$this->add_control(
			'currency_size',
			array(
				'label'     => __( 'Size', 'powerpack' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .pp-pricing-table-price-prefix' => 'font-size: calc({{SIZE}}em/100)',
				),
				'condition' => array(
					'currency_symbol!' => '',
				),
			)
		);

		$this->add_control(
			'currency_position',
			array(
				'label'       => __( 'Position', 'powerpack' ),
				'type'        => Controls_Manager::CHOOSE,
				'label_block' => false,
				'default'     => 'before',
				'options'     => array(
					'before' => array(
						'title' => __( 'Before', 'powerpack' ),
						'icon'  => 'eicon-h-align-left',
					),
					'after'  => array(
						'title' => __( 'After', 'powerpack' ),
						'icon'  => 'eicon-h-align-right',
					),
				),
			)
		);

		$this->add_control(
			'currency_vertical_position',
			array(
				'label'                => __( 'Vertical Position', 'powerpack' ),
				'type'                 => Controls_Manager::CHOOSE,
				'label_block'          => false,
				'options'              => array(
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
				'default'              => 'top',
				'selectors_dictionary' => array(
					'top'    => 'flex-start',
					'middle' => 'center',
					'bottom' => 'flex-end',
				),
				'selectors'            => array(
					'{{WRAPPER}} .pp-pricing-table-price-prefix' => 'align-self: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'table_duration_heading',
			array(
				'label'     => __( 'Duration', 'powerpack' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'duration_position',
			array(
				'label'        => __( 'Duration Position', 'powerpack' ),
				'type'         => Controls_Manager::SELECT,
				'default'      => 'nowrap',
				'options'      => array(
					'nowrap' => __( 'Same Line', 'powerpack' ),
					'wrap'   => __( 'Next Line', 'powerpack' ),
				),
				'prefix_class' => 'pp-pricing-table-price-duration-',
			)
		);

		$this->add_control(
			'duration_text_color',
			array(
				'label'     => __( 'Text Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .pp-pricing-table-price-duration' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'duration_typography',
				'label'    => __( 'Typography', 'powerpack' ),
				'selector' => '{{WRAPPER}} .pp-pricing-table-price-duration',
			)
		);

		$this->add_responsive_control(
			'duration_spacing',
			array(
				'label'      => __( 'Spacing', 'powerpack' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					),
				),
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'{{WRAPPER}}.pp-pricing-table-price-duration-wrap .pp-pricing-table-price-duration' => 'margin-top: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					'duration_position' => 'wrap',
				),
			)
		);

		$this->add_control(
			'table_original_price_style_heading',
			array(
				'label'     => __( 'Original Price', 'powerpack' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'discount' => 'yes',
				),
			)
		);

		$this->add_control(
			'table_original_price_text_color',
			array(
				'label'     => __( 'Text Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'condition' => array(
					'discount' => 'yes',
				),
				'selectors' => array(
					'{{WRAPPER}} .pp-pricing-table-price-original' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_responsive_control(
			'table_original_price_text_size',
			array(
				'label'      => __( 'Font Size', 'powerpack' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => array(
					'px' => array(
						'min'  => 5,
						'max'  => 100,
						'step' => 1,
					),
				),
				'size_units' => array( 'px', 'em' ),
				'condition'  => array(
					'discount' => 'yes',
				),
				'selectors'  => array(
					'{{WRAPPER}} .pp-pricing-table-price-original' => 'font-size: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->end_controls_section();

		/**
		 * Style Tab: Features
		 * -------------------------------------------------
		 */
		$this->start_controls_section(
			'section_table_features_style',
			array(
				'label' => __( 'Features', 'powerpack' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'table_features_align',
			array(
				'label'       => __( 'Alignment', 'powerpack' ),
				'type'        => Controls_Manager::CHOOSE,
				'label_block' => false,
				'options'     => array(
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
				'default'     => '',
				'selectors'   => array(
					'{{WRAPPER}} .pp-pricing-table-features'   => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'table_features_bg_color',
			array(
				'label'     => __( 'Background Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .pp-pricing-table-features' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'table_features_text_color',
			array(
				'label'     => __( 'Text Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .pp-pricing-table-features' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_responsive_control(
			'table_features_padding',
			array(
				'label'      => __( 'Padding', 'powerpack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'default'    => array(
					'top'      => '20',
					'right'    => '',
					'bottom'   => '20',
					'left'     => '',
					'unit'     => 'px',
					'isLinked' => false,
				),
				'selectors'  => array(
					'{{WRAPPER}} .pp-pricing-table-features' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'table_features_margin',
			array(
				'label'      => __( 'Margin Bottom', 'powerpack' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 60,
						'step' => 1,
					),
				),
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'{{WRAPPER}} .pp-pricing-table-features' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'table_features_typography',
				'label'     => __( 'Typography', 'powerpack' ),
				'scheme'    => Scheme_Typography::TYPOGRAPHY_4,
				'selector'  => '{{WRAPPER}} .pp-pricing-table-features',
				'separator' => 'before',
			)
		);

		$this->add_control(
			'table_features_icon_heading',
			array(
				'label'     => __( 'Icon', 'powerpack' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'table_features_icon_color',
			array(
				'label'     => __( 'Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .pp-pricing-table-fature-icon' => 'color: {{VALUE}}',
					'{{WRAPPER}} .pp-pricing-table-fature-icon svg' => 'fill: {{VALUE}}',
				),
			)
		);

		$this->add_responsive_control(
			'table_features_icon_size',
			array(
				'label'      => __( 'Size', 'powerpack' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => array(
					'px' => array(
						'min'  => 5,
						'max'  => 100,
						'step' => 1,
					),
				),
				'size_units' => array( 'px', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .pp-pricing-table-fature-icon' => 'font-size: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_responsive_control(
			'table_features_icon_spacing',
			array(
				'label'      => __( 'Spacing', 'powerpack' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					),
				),
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'{{WRAPPER}} .pp-pricing-table-fature-icon' => 'margin-right: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'table_features_rows_heading',
			array(
				'label'     => __( 'Rows', 'powerpack' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_responsive_control(
			'table_features_spacing',
			array(
				'label'      => __( 'Spacing', 'powerpack' ),
				'type'       => Controls_Manager::SLIDER,
				'default'    => array(
					'unit' => 'px',
					'size' => 10,
				),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					),
				),
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'{{WRAPPER}} .pp-pricing-table-features li:not(:last-child)' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'table_features_alternate',
			array(
				'label'        => __( 'Striped Rows', 'powerpack' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '',
				'label_on'     => __( 'Yes', 'powerpack' ),
				'label_off'    => __( 'No', 'powerpack' ),
				'return_value' => 'yes',
			)
		);

		$this->add_responsive_control(
			'table_features_rows_padding',
			array(
				'label'      => __( 'Padding', 'powerpack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .pp-pricing-table-features li' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					'table_features_alternate' => 'yes',
				),
			)
		);

		$this->start_controls_tabs( 'tabs_features_style' );

		$this->start_controls_tab(
			'tab_features_even',
			array(
				'label'     => __( 'Even', 'powerpack' ),
				'condition' => array(
					'table_features_alternate' => 'yes',
				),
			)
		);

		$this->add_control(
			'table_features_bg_color_even',
			array(
				'label'     => __( 'Background Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .pp-pricing-table-features li:nth-child(even)' => 'background-color: {{VALUE}}',
				),
				'condition' => array(
					'table_features_alternate' => 'yes',
				),
			)
		);

		$this->add_control(
			'table_features_text_color_even',
			array(
				'label'     => __( 'Text Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .pp-pricing-table-features li:nth-child(even)' => 'color: {{VALUE}}',
				),
				'condition' => array(
					'table_features_alternate' => 'yes',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_features_odd',
			array(
				'label'     => __( 'Odd', 'powerpack' ),
				'condition' => array(
					'table_features_alternate' => 'yes',
				),
			)
		);

		$this->add_control(
			'table_features_bg_color_odd',
			array(
				'label'     => __( 'Background Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .pp-pricing-table-features li:nth-child(odd)' => 'background-color: {{VALUE}}',
				),
				'condition' => array(
					'table_features_alternate' => 'yes',
				),
			)
		);

		$this->add_control(
			'table_features_text_color_odd',
			array(
				'label'     => __( 'Text Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .pp-pricing-table-features li:nth-child(odd)' => 'color: {{VALUE}}',
				),
				'condition' => array(
					'table_features_alternate' => 'yes',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'table_divider_heading',
			array(
				'label'     => __( 'Divider', 'powerpack' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'table_feature_divider',
				'label'       => __( 'Divider', 'powerpack' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .pp-pricing-table-features li',
			)
		);

		$this->end_controls_section();

		/**
		 * Style Tab: Ribbon
		 * -------------------------------------------------
		 */
		$this->start_controls_section(
			'section_table_ribbon_style',
			array(
				'label' => __( 'Ribbon', 'powerpack' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'ribbon_bg_color',
			array(
				'label'     => __( 'Background Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .pp-pricing-table-ribbon .pp-pricing-table-ribbon-inner' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .pp-pricing-table-ribbon-3.pp-pricing-table-ribbon-right:before' => 'border-left-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'ribbon_text_color',
			array(
				'label'     => __( 'Text Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => array(
					'{{WRAPPER}} .pp-pricing-table-ribbon .pp-pricing-table-ribbon-inner' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'ribbon_typography',
				'selector' => '{{WRAPPER}} .pp-pricing-table-ribbon .pp-pricing-table-ribbon-inner',
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'box_shadow',
				'selector' => '{{WRAPPER}} .pp-pricing-table-ribbon .pp-pricing-table-ribbon-inner',
			)
		);

		$this->end_controls_section();

		/**
		 * Style Tab: Button
		 * -------------------------------------------------
		 */
		$this->start_controls_section(
			'section_table_button_style',
			array(
				'label'     => __( 'Button', 'powerpack' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'table_button_text!' => '',
				),
			)
		);

		$this->add_control(
			'table_button_size',
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
					'table_button_text!' => '',
				),
			)
		);

		$this->add_responsive_control(
			'button_spacing',
			array(
				'label'      => __( 'Spacing', 'powerpack' ),
				'type'       => Controls_Manager::SLIDER,
				'default'    => array(
					'size' => 20,
					'unit' => 'px',
				),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					),
				),
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'{{WRAPPER}} .pp-pricing-table-button-wrap' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					'table_button_text!'    => '',
					'table_button_position' => 'above',
				),
			)
		);

		$this->start_controls_tabs( 'tabs_button_style' );

		$this->start_controls_tab(
			'tab_button_normal',
			array(
				'label'     => __( 'Normal', 'powerpack' ),
				'condition' => array(
					'table_button_text!' => '',
				),
			)
		);

		$this->add_control(
			'button_bg_color_normal',
			array(
				'label'     => __( 'Background Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'condition' => array(
					'table_button_text!' => '',
				),
				'selectors' => array(
					'{{WRAPPER}} .pp-pricing-table-button' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'button_text_color_normal',
			array(
				'label'     => __( 'Text Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'condition' => array(
					'table_button_text!' => '',
				),
				'selectors' => array(
					'{{WRAPPER}} .pp-pricing-table-button' => 'color: {{VALUE}}',
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
				'condition'   => array(
					'table_button_text!' => '',
				),
				'selector'    => '{{WRAPPER}} .pp-pricing-table-button',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'button_typography',
				'label'     => __( 'Typography', 'powerpack' ),
				'scheme'    => Scheme_Typography::TYPOGRAPHY_4,
				'condition' => array(
					'table_button_text!' => '',
				),
				'selector'  => '{{WRAPPER}} .pp-pricing-table-button',
			)
		);

		$this->add_responsive_control(
			'table_button_padding',
			array(
				'label'      => __( 'Padding', 'powerpack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'condition'  => array(
					'table_button_text!' => '',
				),
				'selectors'  => array(
					'{{WRAPPER}} .pp-pricing-table-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'button_border_radius',
			array(
				'label'      => __( 'Border Radius', 'powerpack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'condition'  => array(
					'table_button_text!' => '',
				),
				'selectors'  => array(
					'{{WRAPPER}} .pp-pricing-table-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'      => 'pa_pricing_table_button_shadow',
				'condition' => array(
					'table_button_text!' => '',
				),
				'selector'  => '{{WRAPPER}} .pp-pricing-table-button',
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_button_hover',
			array(
				'label'     => __( 'Hover', 'powerpack' ),
				'condition' => array(
					'table_button_text!' => '',
				),
			)
		);

		$this->add_control(
			'button_bg_color_hover',
			array(
				'label'     => __( 'Background Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'condition' => array(
					'table_button_text!' => '',
				),
				'selectors' => array(
					'{{WRAPPER}} .pp-pricing-table-button:hover' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'button_text_color_hover',
			array(
				'label'     => __( 'Text Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'condition' => array(
					'table_button_text!' => '',
				),
				'selectors' => array(
					'{{WRAPPER}} .pp-pricing-table-button:hover' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'button_border_hover',
				'label'       => __( 'Border', 'powerpack' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'condition'   => array(
					'table_button_text!' => '',
				),
				'selector'    => '{{WRAPPER}} .pp-pricing-table-button:hover',
			)
		);

		$this->add_control(
			'button_hover_animation',
			array(
				'label'     => __( 'Animation', 'powerpack' ),
				'type'      => Controls_Manager::HOVER_ANIMATION,
				'condition' => array(
					'table_button_text!' => '',
				),
			)
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->end_controls_section();

		/**
		 * Style Tab: Footer
		 * -------------------------------------------------
		 */
		$this->start_controls_section(
			'section_table_footer_style',
			array(
				'label' => __( 'Footer', 'powerpack' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'table_footer_bg_color',
			array(
				'label'     => __( 'Background Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .pp-pricing-table-footer' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_responsive_control(
			'table_footer_padding',
			array(
				'label'      => __( 'Padding', 'powerpack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .pp-pricing-table-footer' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'table_additional_info_heading',
			array(
				'label'     => __( 'Additional Info', 'powerpack' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'table_additional_info!' => '',
				),
			)
		);

		$this->add_control(
			'additional_info_color',
			array(
				'label'     => __( 'Text Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'condition' => array(
					'table_additional_info!' => '',
				),
				'selectors' => array(
					'{{WRAPPER}} .pp-pricing-table-additional-info' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'additional_info_bg_color',
			array(
				'label'     => __( 'Background Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'condition' => array(
					'table_additional_info!' => '',
				),
				'selectors' => array(
					'{{WRAPPER}} .pp-pricing-table-additional-info' => 'background: {{VALUE}}',
				),
			)
		);

		$this->add_responsive_control(
			'additional_info_margin',
			array(
				'label'      => __( 'Margin Top', 'powerpack' ),
				'type'       => Controls_Manager::SLIDER,
				'default'    => array(
					'size' => 20,
					'unit' => 'px',
				),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					),
				),
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'{{WRAPPER}} .pp-pricing-table-additional-info' => 'margin-top: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					'table_additional_info!' => '',
				),
			)
		);

		$this->add_responsive_control(
			'additional_info_padding',
			array(
				'label'      => __( 'Padding', 'powerpack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'condition'  => array(
					'table_additional_info!' => '',
				),
				'selectors'  => array(
					'{{WRAPPER}} .pp-pricing-table-additional-info' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'additional_info_typography',
				'label'     => __( 'Typography', 'powerpack' ),
				'scheme'    => Scheme_Typography::TYPOGRAPHY_4,
				'condition' => array(
					'table_additional_info!' => '',
				),
				'selector'  => '{{WRAPPER}} .pp-pricing-table-additional-info',
			)
		);

		$this->end_controls_section();

	}

	private function get_currency_symbol( $symbol_name ) {
		$symbols = array(
			'dollar'       => '&#36;',
			'euro'         => '&#128;',
			'franc'        => '&#8355;',
			'pound'        => '&#163;',
			'ruble'        => '&#8381;',
			'shekel'       => '&#8362;',
			'baht'         => '&#3647;',
			'yen'          => '&#165;',
			'won'          => '&#8361;',
			'guilder'      => '&fnof;',
			'peso'         => '&#8369;',
			'peseta'       => '&#8359',
			'lira'         => '&#8356;',
			'rupee'        => '&#8360;',
			'indian_rupee' => '&#8377;',
			'real'         => 'R$',
			'krona'        => 'kr',
		);
		return isset( $symbols[ $symbol_name ] ) ? $symbols[ $symbol_name ] : '';
	}

	/**
	 * Render pricing table widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();
		$symbol   = '';

		if ( ! empty( $settings['currency_symbol'] ) ) {
			if ( 'custom' !== $settings['currency_symbol'] ) {
				$symbol = $this->get_currency_symbol( $settings['currency_symbol'] );
			} else {
				$symbol = $settings['currency_symbol_custom'];
			}
		}

		if ( ! isset( $settings['table_icon'] ) && ! Icons_Manager::is_migration_allowed() ) {
			// add old default
			$settings['table_icon'] = 'fa fa-star';
		}

		$has_icon = ! empty( $settings['table_icon'] );

		if ( $has_icon ) {
			$this->add_render_attribute( 'i', 'class', $settings['table_icon'] );
			$this->add_render_attribute( 'i', 'aria-hidden', 'true' );
		}

		if ( ! $has_icon && ! empty( $settings['select_table_icon']['value'] ) ) {
			$has_icon = true;
		}
		$migrated = isset( $settings['__fa4_migrated']['select_table_icon'] );
		$is_new   = ! isset( $settings['table_icon'] ) && Icons_Manager::is_migration_allowed();

		$this->add_inline_editing_attributes( 'table_title', 'none' );
		$this->add_render_attribute( 'table_title', 'class', 'pp-pricing-table-title' );

		$this->add_inline_editing_attributes( 'table_subtitle', 'none' );
		$this->add_render_attribute( 'table_subtitle', 'class', 'pp-pricing-table-subtitle' );

		$this->add_render_attribute( 'table_price', 'class', 'pp-pricing-table-price-value' );

		$this->add_inline_editing_attributes( 'table_duration', 'none' );
		$this->add_render_attribute( 'table_duration', 'class', 'pp-pricing-table-price-duration' );

		$this->add_inline_editing_attributes( 'table_additional_info', 'none' );
		$this->add_render_attribute( 'table_additional_info', 'class', 'pp-pricing-table-additional-info' );

		$this->add_render_attribute( 'pricing-table', 'class', 'pp-pricing-table' );

		$this->add_render_attribute( 'feature-list-item', 'class', '' );

		$this->add_inline_editing_attributes( 'table_button_text', 'none' );

		$this->add_render_attribute(
			'table_button_text',
			'class',
			array(
				'pp-pricing-table-button',
				'elementor-button',
				'elementor-size-' . $settings['table_button_size'],
			)
		);

		if ( ! empty( $settings['link']['url'] ) ) {
			$this->add_link_attributes( 'table_button_text', $settings['link'] );
		}

		$this->add_render_attribute( 'pricing-table-duration', 'class', 'pp-pricing-table-price-duration' );
		if ( $settings['duration_position'] == 'wrap' ) {
			$this->add_render_attribute( 'pricing-table-duration', 'class', 'next-line' );
		}

		if ( $settings['button_hover_animation'] ) {
			$this->add_render_attribute( 'table_button_text', 'class', 'elementor-animation-' . $settings['button_hover_animation'] );
		}

		if ( $settings['currency_format'] == 'raised' ) {
			$price    = explode( '.', $settings['table_price'] );
			$intvalue = $price[0];
			$fraction = '';
			if ( 2 === count( $price ) ) {
				$fraction = $price[1];
			}
		} else {
			$intvalue = $settings['table_price'];
			$fraction = '';
		}
		?>
		<div class="pp-pricing-table-container">
			<div <?php echo $this->get_render_attribute_string( 'pricing-table' ); ?>>
				<div class="pp-pricing-table-head">
					<?php if ( $settings['icon_type'] != 'none' ) { ?>
						<div class="pp-pricing-table-icon-wrap">
							<?php if ( $settings['icon_type'] == 'icon' && $has_icon ) { ?>
								<span class="pp-pricing-table-icon pp-icon">
									<?php
									if ( $is_new || $migrated ) {
										Icons_Manager::render_icon( $settings['select_table_icon'], array( 'aria-hidden' => 'true' ) );
									} elseif ( ! empty( $settings['table_icon'] ) ) {
										?>
										<i <?php echo $this->get_render_attribute_string( 'i' ); ?>></i>
										<?php
									}
									?>
								</span>
							<?php } elseif ( $settings['icon_type'] == 'image' ) { ?>
								<?php
								$image = $settings['icon_image'];
								if ( ! empty( $image['url'] ) ) {
									?>
									<span class="pp-pricing-table-icon pp-pricing-table-icon-image">
										<?php
											echo Group_Control_Image_Size::get_attachment_image_html( $settings, 'image', 'icon_image' );
										?>
									</span>
								<?php } ?>
							<?php } ?>
						</div>
					<?php } ?>
					<div class="pp-pricing-table-title-wrap">
						<?php if ( ! empty( $settings['table_title'] ) ) { ?>
							<h3 <?php echo $this->get_render_attribute_string( 'table_title' ); ?>>
								<?php echo $settings['table_title']; ?>
							</h3>
						<?php } ?>
						<?php if ( ! empty( $settings['table_subtitle'] ) ) { ?>
							<h4 <?php echo $this->get_render_attribute_string( 'table_subtitle' ); ?>>
								<?php echo $settings['table_subtitle']; ?>
							</h4>
						<?php } ?>
					</div>
				</div>
				<div class="pp-pricing-table-price-wrap">
					<div class="pp-pricing-table-price">
						<?php if ( $settings['discount'] == 'yes' && $settings['table_original_price'] ) { ?>
							<span class="pp-pricing-table-price-original">
								<?php
								if ( ! empty( $symbol ) && $settings['currency_position'] === 'after' ) {
									echo $settings['table_original_price'] . $symbol;
								} else {
									echo $symbol . $settings['table_original_price'];
								}
								?>
							</span>
						<?php } ?>
						<?php if ( ! empty( $symbol ) && ( $settings['currency_position'] === 'before' || $settings['currency_position'] === '' ) ) { ?>
							<span class="pp-pricing-table-price-prefix">
								<?php echo $symbol; ?>
							</span>
						<?php } ?>
						<span <?php echo $this->get_render_attribute_string( 'table_price' ); ?>>
							<span class="pp-pricing-table-integer-part">
								<?php echo $intvalue; ?>
							</span>
							<?php if ( $fraction ) { ?>
								<span class="pp-pricing-table-after-part">
									<?php echo $fraction; ?>
								</span>
							<?php } ?>
						</span>
						<?php if ( ! empty( $symbol ) && $settings['currency_position'] === 'after' ) { ?>
							<span class="pp-pricing-table-price-prefix">
								<?php echo $symbol; ?>
							</span>
						<?php } ?>
						<?php if ( ! empty( $settings['table_duration'] ) ) { ?>
							<span <?php echo $this->get_render_attribute_string( 'table_duration' ); ?>>
								<?php echo $settings['table_duration']; ?>
							</span>
						<?php } ?>
					</div>
				</div>
				<?php if ( $settings['table_button_position'] == 'above' ) { ?>
					<div class="pp-pricing-table-button-wrap">
						<?php if ( ! empty( $settings['table_button_text'] ) ) { ?>
							<a <?php echo $this->get_render_attribute_string( 'table_button_text' ); ?>>
								<?php echo $settings['table_button_text']; ?>
							</a>
						<?php } ?>
					</div>
				<?php } ?>
				<ul class="pp-pricing-table-features">
					<?php foreach ( $settings['table_features'] as $index => $item ) : ?>
						<?php
							$fallback_defaults = array(
								'fa fa-check',
								'fa fa-times',
								'fa fa-dot-circle-o',
							);

							$migration_allowed = Icons_Manager::is_migration_allowed();

							// add old default
							if ( ! isset( $item['feature_icon'] ) && ! $migration_allowed ) {
								$item['feature_icon'] = isset( $fallback_defaults[ $index ] ) ? $fallback_defaults[ $index ] : 'fa fa-check';
							}

							$migrated = isset( $item['__fa4_migrated']['select_feature_icon'] );
							$is_new   = ! isset( $item['feature_icon'] ) && $migration_allowed;

							$feature_key = $this->get_repeater_setting_key( 'feature_text', 'table_features', $index );
							$this->add_render_attribute( $feature_key, 'class', 'pp-pricing-table-feature-text' );
							$this->add_inline_editing_attributes( $feature_key, 'none' );

							$pa_class = '';

							if ( $item['exclude'] == 'yes' ) {
								$pa_class .= ' excluded';
							} else {
								$pa_class .= '';
							}
							?>
						<li class="elementor-repeater-item-<?php echo $item['_id'] . $pa_class; ?>">
							<?php
							if ( ! empty( $item['select_feature_icon'] ) || ( ! empty( $item['feature_icon']['value'] ) && $is_new ) ) :
								echo '<span class="pp-pricing-table-fature-icon pp-icon">';
								if ( $is_new || $migrated ) {
									Icons_Manager::render_icon( $item['select_feature_icon'], array( 'aria-hidden' => 'true' ) );
								} else {
									?>
											<i class="<?php echo $item['feature_icon']; ?>" aria-hidden="true"></i>
									<?php
								}
								echo '</span>';
								endif;
							?>
							<?php if ( $item['feature_text'] ) { ?>
								<span <?php echo $this->get_render_attribute_string( $feature_key ); ?>>
									<?php echo $item['feature_text']; ?>
								</span>
							<?php } ?>
						</li>
					<?php endforeach; ?>
				</ul>
				<div class="pp-pricing-table-footer">
					<?php if ( $settings['table_button_position'] == 'below' ) { ?>
						<?php if ( ! empty( $settings['table_button_text'] ) ) { ?>
							<a <?php echo $this->get_render_attribute_string( 'table_button_text' ); ?>>
								<?php echo $settings['table_button_text']; ?>
							</a>
						<?php } ?>
					<?php } ?>
					<?php if ( ! empty( $settings['table_additional_info'] ) ) { ?>
						<div <?php echo $this->get_render_attribute_string( 'table_additional_info' ); ?>>
							<?php echo $this->parse_text_editor( $settings['table_additional_info'] ); ?>
						</div>
					<?php } ?>
				</div>
			</div><!-- .pp-pricing-table -->
			<?php if ( $settings['show_ribbon'] == 'yes' && $settings['ribbon_title'] != '' ) { ?>
				<?php
					$classes = array(
						'pp-pricing-table-ribbon',
						'pp-pricing-table-ribbon-' . $settings['ribbon_style'],
						'pp-pricing-table-ribbon-' . $settings['ribbon_position'],
					);
					$this->add_render_attribute( 'ribbon', 'class', $classes );
					?>
				<div <?php echo $this->get_render_attribute_string( 'ribbon' ); ?>>
					<div class="pp-pricing-table-ribbon-inner">
						<div class="pp-pricing-table-ribbon-title">
							<?php echo $settings['ribbon_title']; ?>
						</div>
					</div>
				</div>
			<?php } ?>
		</div><!-- .pp-pricing-table-container -->
		<?php
	}

	/**
	 * Render pricing table widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @access protected
	 */
	protected function _content_template() {
		?>
		<#
			var buttonClasses = 'pp-pricing-table-button elementor-button elementor-size-' + settings.table_button_size + ' elementor-animation-' + settings.button_hover_animation;
		   
			var $i = 1,
				symbols = {
					dollar: '&#36;',
					euro: '&#128;',
					franc: '&#8355;',
					pound: '&#163;',
					ruble: '&#8381;',
					shekel: '&#8362;',
					baht: '&#3647;',
					yen: '&#165;',
					won: '&#8361;',
					guilder: '&fnof;',
					peso: '&#8369;',
					peseta: '&#8359;',
					lira: '&#8356;',
					rupee: '&#8360;',
					indian_rupee: '&#8377;',
					real: 'R$',
					krona: 'kr'
				},
				symbol = '',
				iconHTML = {},
				iconsHTML = {},
				migrated = {};
				iconsMigrated = {};

			if ( settings.currency_symbol ) {
				if ( 'custom' !== settings.currency_symbol ) {
					symbol = symbols[ settings.currency_symbol ] || '';
				} else {
					symbol = settings.currency_symbol_custom;
				}
			}
		   
		   if ( settings.currency_format == 'raised' ) {
				   var table_price = settings.table_price.toString(),
					price = table_price.split( '.' ),
					intvalue = price[0],
					fraction = price[1];
		   } else {
				   var intvalue = settings.table_price,
					fraction = '';
		   }
		#>
		<div class="pp-pricing-table-container">
			<div class="pp-pricing-table">
				<div class="pp-pricing-table-head">
					<# if ( settings.icon_type != 'none' ) { #>
						<div class="pp-pricing-table-icon-wrap">
							<# if ( settings.icon_type == 'icon' ) { #>
								<# if ( settings.table_icon || settings.select_table_icon ) { #>
									<span class="pp-pricing-table-icon pp-icon">
										<# if ( iconHTML && iconHTML.rendered && ( ! settings.table_icon || migrated ) ) { #>
										{{{ iconHTML.value }}}
										<# } else { #>
											<i class="{{ settings.table_icon }}" aria-hidden="true"></i>
										<# } #>
									</span>
								<# } #>
							<# } else if ( settings.icon_type == 'image' ) { #>
								<span class="pp-pricing-table-icon pp-pricing-table-icon-image">
									<# if ( settings.icon_image.url != '' ) { #>
										<#
										var image = {
											id: settings.icon_image.id,
											url: settings.icon_image.url,
											size: settings.image_size,
											dimension: settings.image_custom_dimension,
											model: view.getEditModel()
										};
										var image_url = elementor.imagesManager.getImageUrl( image );
										#>
										<img src="{{{ image_url }}}" />
									<# } #>
								</span>
							<# } #>
						</div>
					<# } #>
					<div class="pp-pricing-table-title-wrap">
						<# if ( settings.table_title ) { #>
							<h3 class="pp-pricing-table-title elementor-inline-editing" data-elementor-setting-key="table_title" data-elementor-inline-editing-toolbar="none">
								{{{ settings.table_title }}}
							</h3>
						<# } #>
						<# if ( settings.table_subtitle ) { #>
							<h4 class="pp-pricing-table-subtitle elementor-inline-editing" data-elementor-setting-key="table_subtitle" data-elementor-inline-editing-toolbar="none">
								{{{ settings.table_subtitle }}}
							</h4>
						<# } #>
					</div>
				</div>
				<div class="pp-pricing-table-price-wrap">
					<div class="pp-pricing-table-price">
						<# if ( settings.discount === 'yes' && settings.table_original_price > 0 ) { #>
							<span class="pp-pricing-table-price-original">
								<# if ( ! _.isEmpty( symbol ) && 'after' == settings.currency_position ) { #>
									{{{ settings.table_original_price + symbol }}}
								<# } else { #>
									{{{ symbol + settings.table_original_price }}}
								<# } #>
							</span>
						<# } #>
						<# if ( ! _.isEmpty( symbol ) && ( 'before' == settings.currency_position || _.isEmpty( settings.currency_position ) ) ) { #>
							<span class="pp-pricing-table-price-prefix">{{{ symbol }}}</span>
						<# } #>
						<span class="pp-pricing-table-price-value">
							<span class="pp-pricing-table-integer-part">
								{{{ intvalue }}}
							</span>
							<# if ( fraction ) { #>
								<span class="pp-pricing-table-after-part">
									{{{ fraction }}}
								</span>
							<# } #>
						</span>
						<# if ( ! _.isEmpty( symbol ) && 'after' == settings.currency_position ) { #>
							<span class="pp-pricing-table-price-prefix">{{{ symbol }}}</span>
						<# } #>
						<# if ( settings.table_duration ) { #>
							<span class="pp-pricing-table-price-duration elementor-inline-editing" data-elementor-setting-key="table_duration" data-elementor-inline-editing-toolbar="none">
								{{{ settings.table_duration }}}
							</span>
						<# } #>
					</div>
				</div>
				<# if ( settings.table_button_position == 'above' ) { #>
					<div class="pp-pricing-table-button-wrap">
						<#
						   if ( settings.table_button_text ) {
							var button_text = settings.table_button_text;

							view.addRenderAttribute( 'table_button_text', 'class', buttonClasses );

							view.addInlineEditingAttributes( 'table_button_text' );

							var button_text_html = '<a ' + 'href="' + settings.link.url + '"' + view.getRenderAttributeString( 'table_button_text' ) + '>' + button_text + '</a>';

							print( button_text_html );
						   }
						#>
					</div>
				<# } #>
				<ul class="pp-pricing-table-features">
					<# var i = 1; #>
					<# _.each( settings.table_features, function( item, index ) { #>
						<li class="elementor-repeater-item-{{ item._id }} <# if ( item.exclude == 'yes' ) { #> excluded <# } #>">
							<# if ( item.select_feature_icon || item.feature_icon.value ) { #>
								<span class="pp-pricing-table-fature-icon pp-icon">
								<#
									iconsHTML[ index ] = elementor.helpers.renderIcon( view, item.select_feature_icon, { 'aria-hidden': true }, 'i', 'object' );
									iconsMigrated[ index ] = elementor.helpers.isIconMigrated( item, 'select_feature_icon' );
									if ( iconsHTML[ index ] && iconsHTML[ index ].rendered && ( ! item.feature_icon || iconsMigrated[ index ] ) ) { #>
										{{{ iconsHTML[ index ].value }}}
									<# } else { #>
										<i class="{{ item.feature_icon }}" aria-hidden="true"></i>
									<# }
								#>
								</span>
							<# } #>

							<#
								var feature_text = item.feature_text;

								view.addRenderAttribute( 'table_features.' + (i - 1) + '.feature_text', 'class', 'pp-pricing-table-feature-text' );

								view.addInlineEditingAttributes( 'table_features.' + (i - 1) + '.feature_text' );

								var feature_text_html = '<span' + ' ' + view.getRenderAttributeString( 'table_features.' + (i - 1) + '.feature_text' ) + '>' + feature_text + '</span>';

								print( feature_text_html );
							#>
						</li>
					<# i++ } ); #>
				</ul>
				<div class="pp-pricing-table-footer">
					<#
					   if ( settings.table_button_position == 'below' ) {
						   if ( settings.table_button_text ) {
							var button_text = settings.table_button_text;

							view.addRenderAttribute( 'table_button_text', 'class', buttonClasses );

							view.addInlineEditingAttributes( 'table_button_text' );

							var button_text_html = '<a ' + 'href="' + settings.link.url + '"' + view.getRenderAttributeString( 'table_button_text' ) + '>' + button_text + '</a>';

							print( button_text_html );
						   }
					   }
					#>
					<#
					   if ( settings.table_additional_info ) {
						var additional_info_text = settings.table_additional_info;

						view.addRenderAttribute( 'table_additional_info', 'class', 'pp-pricing-table-additional-info' );

						view.addInlineEditingAttributes( 'table_additional_info' );

						var additional_info_text_html = '<div ' + view.getRenderAttributeString( 'table_additional_info' ) + '>' + additional_info_text + '</div>';

						print( additional_info_text_html );
					   }
					#>
				</div>
			</div><!-- .pp-pricing-table -->
			<# if ( settings.show_ribbon == 'yes' && settings.ribbon_title != '' ) { #>
				<div class="pp-pricing-table-ribbon pp-pricing-table-ribbon-{{ settings.ribbon_style }} pp-pricing-table-ribbon-{{ settings.ribbon_position }}">
					<div class="pp-pricing-table-ribbon-inner">
						<div class="pp-pricing-table-ribbon-title">
							<# print( settings.ribbon_title ); #>
						</div>
					</div>
				</div>
			<# } #>
		</div><!-- .pp-pricing-table-container -->
		<?php
	}

}
