<?php
namespace PowerpackElementsLite\Modules\Divider\Widgets;

use PowerpackElementsLite\Base\Powerpack_Widget;

// Elementor Classes
use Elementor\Controls_Manager;
use Elementor\Utils;
use Elementor\Icons_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Image_Size;
use Elementor\Scheme_Typography;
use Elementor\Modules\DynamicTags\Module as TagsModule;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Divider Widget
 */
class Divider extends Powerpack_Widget {

	/**
	 * Retrieve divider widget name.
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return parent::get_widget_name( 'Divider' );
	}

	/**
	 * Retrieve divider widget title.
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return parent::get_widget_title( 'Divider' );
	}

	/**
	 * Retrieve divider widget icon.
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return parent::get_widget_icon( 'Divider' );
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
		return parent::get_widget_keywords( 'Divider' );
	}

	/**
	 * Register divider widget controls.
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
		 * Content Tab: Divider
		 */
		$this->start_controls_section(
			'section_buton',
			array(
				'label' => __( 'Divider', 'powerpack' ),
			)
		);

		$this->add_control(
			'divider_type',
			array(
				'label'       => esc_html__( 'Type', 'powerpack' ),
				'type'        => Controls_Manager::CHOOSE,
				'label_block' => false,
				'options'     => array(
					'plain' => array(
						'title' => esc_html__( 'Plain', 'powerpack' ),
						'icon'  => 'fa fa-ellipsis-h',
					),
					'text'  => array(
						'title' => esc_html__( 'Text', 'powerpack' ),
						'icon'  => 'fa fa-file-text-o',
					),
					'icon'  => array(
						'title' => esc_html__( 'Icon', 'powerpack' ),
						'icon'  => 'fa fa-certificate',
					),
					'image' => array(
						'title' => esc_html__( 'Image', 'powerpack' ),
						'icon'  => 'fa fa-picture-o',
					),
				),
				'default'     => 'plain',
			)
		);

		$this->add_control(
			'divider_direction',
			array(
				'label'     => __( 'Direction', 'powerpack' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'horizontal',
				'options'   => array(
					'horizontal' => __( 'Horizontal', 'powerpack' ),
					'vertical'   => __( 'Vertical', 'powerpack' ),
				),
				'condition' => array(
					'divider_type' => 'plain',
				),
			)
		);

		$this->add_control(
			'divider_text',
			array(
				'label'     => __( 'Text', 'powerpack' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => __( 'Divider Text', 'powerpack' ),
				'condition' => array(
					'divider_type' => 'text',
				),
			)
		);

		$this->add_control(
			'icon',
			array(
				'label'            => __( 'Icon', 'powerpack' ),
				'type'             => Controls_Manager::ICONS,
				'fa4compatibility' => 'divider_icon',
				'default'          => array(
					'value'   => 'fas fa-circle',
					'library' => 'fa-solid',
				),
				'condition'        => array(
					'divider_type' => 'icon',
				),
			)
		);

		$this->add_control(
			'text_html_tag',
			array(
				'label'     => __( 'HTML Tag', 'powerpack' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'span',
				'options'   => array(
					'h1'   => __( 'H1', 'powerpack' ),
					'h2'   => __( 'H2', 'powerpack' ),
					'h3'   => __( 'H3', 'powerpack' ),
					'h4'   => __( 'H4', 'powerpack' ),
					'h5'   => __( 'H5', 'powerpack' ),
					'h6'   => __( 'H6', 'powerpack' ),
					'div'  => __( 'div', 'powerpack' ),
					'span' => __( 'span', 'powerpack' ),
					'p'    => __( 'p', 'powerpack' ),
				),
				'condition' => array(
					'divider_type' => 'text',
				),
			)
		);

		$this->add_control(
			'divider_image',
			array(
				'label'     => __( 'Image', 'powerpack' ),
				'type'      => Controls_Manager::MEDIA,
				'default'   => array(
					'url' => Utils::get_placeholder_image_src(),
				),
				'condition' => array(
					'divider_type' => 'image',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			array(
				'name'      => 'image', // Usage: `{name}_size` and `{name}_custom_dimension`, in this case `image_size` and `image_custom_dimension`.
				'default'   => 'full',
				'separator' => 'none',
				'condition' => array(
					'divider_type' => 'image',
				),
			)
		);

		$this->add_responsive_control(
			'align',
			array(
				'label'     => __( 'Alignment', 'powerpack' ),
				'type'      => Controls_Manager::CHOOSE,
				'default'   => 'center',
				'options'   => array(
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
				'selectors' => array(
					'{{WRAPPER}}' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		if ( ! is_pp_elements_active() ) {
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
		 * Style Tab: Divider
		 */
		$this->start_controls_section(
			'section_divider_style',
			array(
				'label' => __( 'Divider', 'powerpack' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'divider_vertical_align',
			array(
				'label'                => __( 'Vertical Alignment', 'powerpack' ),
				'type'                 => Controls_Manager::CHOOSE,
				'label_block'          => false,
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
				'selectors'            => array(
					'{{WRAPPER}} .divider-text-wrap' => 'align-items: {{VALUE}};',
				),
				'selectors_dictionary' => array(
					'top'    => 'flex-start',
					'middle' => 'center',
					'bottom' => 'flex-end',
				),
				'condition'            => array(
					'divider_type!' => 'plain',
				),
			)
		);

		$this->add_control(
			'divider_style',
			array(
				'label'     => __( 'Style', 'powerpack' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'dashed',
				'options'   => array(
					'solid'  => __( 'Solid', 'powerpack' ),
					'dashed' => __( 'Dashed', 'powerpack' ),
					'dotted' => __( 'Dotted', 'powerpack' ),
					'double' => __( 'Double', 'powerpack' ),
				),
				'selectors' => array(
					'{{WRAPPER}} .pp-divider, {{WRAPPER}} .divider-border' => 'border-style: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'horizontal_height',
			array(
				'label'          => __( 'Height', 'powerpack' ),
				'type'           => Controls_Manager::SLIDER,
				'size_units'     => array( 'px' ),
				'range'          => array(
					'px' => array(
						'min' => 1,
						'max' => 60,
					),
				),
				'default'        => array(
					'size' => 3,
					'unit' => 'px',
				),
				'tablet_default' => array(
					'unit' => 'px',
				),
				'mobile_default' => array(
					'unit' => 'px',
				),
				'selectors'      => array(
					'{{WRAPPER}} .pp-divider.horizontal' => 'border-bottom-width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .pp-divider.pp-divider-horizontal' => 'border-bottom-width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .divider-border'        => 'border-top-width: {{SIZE}}{{UNIT}};',
				),
				'conditions'     => array(
					'relation' => 'or',
					'terms'    => array(
						array(
							'relation' => 'and',
							'terms'    => array(
								array(
									'name'     => 'divider_type',
									'operator' => '==',
									'value'    => 'plain',
								),
								array(
									'name'     => 'divider_direction',
									'operator' => '==',
									'value'    => 'horizontal',
								),
							),
						),
						array(
							'name'     => 'divider_type',
							'operator' => '!=',
							'value'    => 'plain',
						),
					),
				),
			)
		);

		$this->add_responsive_control(
			'vertical_height',
			array(
				'label'          => __( 'Height', 'powerpack' ),
				'type'           => Controls_Manager::SLIDER,
				'size_units'     => array( '%', 'px' ),
				'range'          => array(
					'px' => array(
						'min' => 1,
						'max' => 500,
					),
					'%'  => array(
						'min' => 1,
						'max' => 100,
					),
				),
				'default'        => array(
					'size' => 80,
					'unit' => 'px',
				),
				'tablet_default' => array(
					'unit' => 'px',
				),
				'mobile_default' => array(
					'unit' => 'px',
				),
				'selectors'      => array(
					'{{WRAPPER}} .pp-divider.vertical' => 'padding-bottom: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .pp-divider.pp-divider-vertical' => 'padding-bottom: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .divider-border'      => 'border-top-width: {{SIZE}}{{UNIT}};',
				),
				'condition'      => array(
					'divider_type'      => 'plain',
					'divider_direction' => 'vertical',
				),
			)
		);

		$this->add_responsive_control(
			'horizontal_width',
			array(
				'label'          => __( 'Width', 'powerpack' ),
				'type'           => Controls_Manager::SLIDER,
				'size_units'     => array( '%', 'px' ),
				'range'          => array(
					'px' => array(
						'min' => 1,
						'max' => 1200,
					),
				),
				'default'        => array(
					'size' => 300,
					'unit' => 'px',
				),
				'tablet_default' => array(
					'unit' => 'px',
				),
				'mobile_default' => array(
					'unit' => 'px',
				),
				'selectors'      => array(
					'{{WRAPPER}} .pp-divider.horizontal'  => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .pp-divider.pp-divider-horizontal' => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .divider-text-container' => 'width: {{SIZE}}{{UNIT}};',
				),
				'conditions'     => array(
					'relation' => 'or',
					'terms'    => array(
						array(
							'relation' => 'and',
							'terms'    => array(
								array(
									'name'     => 'divider_type',
									'operator' => '==',
									'value'    => 'plain',
								),
								array(
									'name'     => 'divider_direction',
									'operator' => '==',
									'value'    => 'horizontal',
								),
							),
						),
						array(
							'name'     => 'divider_type',
							'operator' => '!=',
							'value'    => 'plain',
						),
					),
				),
			)
		);

		$this->add_responsive_control(
			'vertical_width',
			array(
				'label'          => __( 'Width', 'powerpack' ),
				'type'           => Controls_Manager::SLIDER,
				'size_units'     => array( 'px' ),
				'range'          => array(
					'px' => array(
						'min' => 1,
						'max' => 100,
					),
				),
				'default'        => array(
					'size' => 3,
					'unit' => 'px',
				),
				'tablet_default' => array(
					'unit' => 'px',
				),
				'mobile_default' => array(
					'unit' => 'px',
				),
				'selectors'      => array(
					'{{WRAPPER}} .pp-divider.vertical'    => 'border-left-width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .pp-divider.pp-divider-vertical' => 'border-left-width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .divider-text-container' => 'width: {{SIZE}}{{UNIT}};',
				),
				'condition'      => array(
					'divider_type'      => 'plain',
					'divider_direction' => 'vertical',
				),
			)
		);

		$this->add_control(
			'divider_border_color',
			array(
				'label'     => __( 'Divider Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .pp-divider, {{WRAPPER}} .divider-border' => 'border-color: {{VALUE}};',
				),
				'condition' => array(
					'divider_type' => 'plain',
				),
			)
		);

		$this->start_controls_tabs( 'tabs_before_after_style' );

		$this->start_controls_tab(
			'tab_before_style',
			array(
				'label'     => __( 'Before', 'powerpack' ),
				'condition' => array(
					'divider_type!' => 'plain',
				),
			)
		);

		$this->add_control(
			'divider_before_color',
			array(
				'label'     => __( 'Divider Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'condition' => array(
					'divider_type!' => 'plain',
				),
				'selectors' => array(
					'{{WRAPPER}} .divider-border-left .divider-border' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_after_style',
			array(
				'label'     => __( 'After', 'powerpack' ),
				'condition' => array(
					'divider_type!' => 'plain',
				),
			)
		);

		$this->add_control(
			'divider_after_color',
			array(
				'label'     => __( 'Divider Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'condition' => array(
					'divider_type!' => 'plain',
				),
				'selectors' => array(
					'{{WRAPPER}} .divider-border-right .divider-border' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		/**
		 * Style Tab: Text
		 */
		$this->start_controls_section(
			'section_text_style',
			array(
				'label'     => __( 'Text', 'powerpack' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'divider_type' => 'text',
				),
			)
		);

		$this->add_control(
			'text_position',
			array(
				'label'        => __( 'Position', 'powerpack' ),
				'type'         => Controls_Manager::CHOOSE,
				'options'      => array(
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
				'default'      => 'center',
				'prefix_class' => 'pp-divider-',
			)
		);

		$this->add_control(
			'divider_text_color',
			array(
				'label'     => __( 'Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'condition' => array(
					'divider_type' => 'text',
				),
				'selectors' => array(
					'{{WRAPPER}} .pp-divider-text' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'typography',
				'label'     => __( 'Typography', 'powerpack' ),
				'scheme'    => Scheme_Typography::TYPOGRAPHY_4,
				'selector'  => '{{WRAPPER}} .pp-divider-text',
				'condition' => array(
					'divider_type' => 'text',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			array(
				'name'     => 'divider_text_shadow',
				'selector' => '{{WRAPPER}} .pp-divider-text',
			)
		);

		$this->add_responsive_control(
			'text_spacing',
			array(
				'label'      => __( 'Spacing', 'powerpack' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( '%', 'px' ),
				'range'      => array(
					'px' => array(
						'max' => 200,
					),
				),
				'condition'  => array(
					'divider_type' => 'text',
				),
				'selectors'  => array(
					'{{WRAPPER}}.pp-divider-center .pp-divider-content' => 'margin-left: {{SIZE}}{{UNIT}}; margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.pp-divider-left .pp-divider-content' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.pp-divider-right .pp-divider-content' => 'margin-left: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		/**
		 * Style Tab: Icon
		 */
		$this->start_controls_section(
			'section_icon_style',
			array(
				'label'     => __( 'Icon', 'powerpack' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'divider_type' => 'icon',
				),
			)
		);

		$this->add_control(
			'icon_position',
			array(
				'label'        => __( 'Position', 'powerpack' ),
				'type'         => Controls_Manager::CHOOSE,
				'options'      => array(
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
				'default'      => 'center',
				'prefix_class' => 'pp-divider-',
			)
		);

		$this->add_control(
			'divider_icon_color',
			array(
				'label'     => __( 'Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'condition' => array(
					'divider_type' => 'icon',
				),
				'selectors' => array(
					'{{WRAPPER}} .pp-divider-icon'     => 'color: {{VALUE}};',
					'{{WRAPPER}} .pp-divider-icon svg' => 'fill: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'icon_size',
			array(
				'label'      => __( 'Size', 'powerpack' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( '%', 'px' ),
				'range'      => array(
					'px' => array(
						'max' => 100,
					),
				),
				'default'    => array(
					'size' => 16,
					'unit' => 'px',
				),
				'condition'  => array(
					'divider_type' => 'icon',
				),
				'selectors'  => array(
					'{{WRAPPER}} .pp-divider-icon' => 'font-size: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'icon_rotation',
			array(
				'label'          => __( 'Icon Rotation', 'powerpack' ),
				'type'           => Controls_Manager::SLIDER,
				'size_units'     => array( '%', 'px' ),
				'range'          => array(
					'px' => array(
						'max' => 360,
					),
				),
				'default'        => array(
					'unit' => 'px',
				),
				'tablet_default' => array(
					'unit' => 'px',
				),
				'mobile_default' => array(
					'unit' => 'px',
				),
				'selectors'      => array(
					'{{WRAPPER}} .pp-divider-icon .fa' => 'transform: rotate( {{SIZE}}deg );',
				),
				'condition'      => array(
					'divider_type' => 'icon',
				),
			)
		);

		$this->add_responsive_control(
			'icon_spacing',
			array(
				'label'      => __( 'Spacing', 'powerpack' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( '%', 'px' ),
				'range'      => array(
					'px' => array(
						'max' => 200,
					),
				),
				'condition'  => array(
					'divider_type' => 'icon',
				),
				'selectors'  => array(
					'{{WRAPPER}}.pp-divider-center .pp-divider-content' => 'margin-left: {{SIZE}}{{UNIT}}; margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.pp-divider-left .pp-divider-content' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.pp-divider-right .pp-divider-content' => 'margin-left: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		/**
		 * Style Tab: Image
		 */
		$this->start_controls_section(
			'section_image_style',
			array(
				'label'     => __( 'Image', 'powerpack' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'divider_type' => 'image',
				),
			)
		);

		$this->add_control(
			'image_position',
			array(
				'label'        => __( 'Position', 'powerpack' ),
				'type'         => Controls_Manager::CHOOSE,
				'options'      => array(
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
				'default'      => 'center',
				'prefix_class' => 'pp-divider-',
			)
		);

		$this->add_responsive_control(
			'image_width',
			array(
				'label'          => __( 'Width', 'powerpack' ),
				'type'           => Controls_Manager::SLIDER,
				'size_units'     => array( '%', 'px' ),
				'range'          => array(
					'px' => array(
						'max' => 1200,
					),
				),
				'default'        => array(
					'size' => 80,
					'unit' => 'px',
				),
				'tablet_default' => array(
					'unit' => 'px',
				),
				'mobile_default' => array(
					'unit' => 'px',
				),
				'condition'      => array(
					'divider_type' => 'image',
				),
				'selectors'      => array(
					'{{WRAPPER}} .pp-divider-image' => 'width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'icon_border_radius',
			array(
				'label'      => __( 'Border Radius', 'powerpack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'condition'  => array(
					'divider_type' => 'image',
				),
				'selectors'  => array(
					'{{WRAPPER}} .pp-divider-image img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'image_spacing',
			array(
				'label'      => __( 'Spacing', 'powerpack' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( '%', 'px' ),
				'range'      => array(
					'px' => array(
						'max' => 200,
					),
				),
				'condition'  => array(
					'divider_type' => 'image',
				),
				'selectors'  => array(
					'{{WRAPPER}}.pp-divider-center .pp-divider-content' => 'margin-left: {{SIZE}}{{UNIT}}; margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.pp-divider-left .pp-divider-content' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.pp-divider-right .pp-divider-content' => 'margin-left: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

	}

	/**
	 * Render divider widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings();

		$classes = array( 'pp-divider' );

		if ( $settings['divider_direction'] ) {
			$classes[] = 'pp-divider-' . $settings['divider_direction'];
			$classes[] = $settings['divider_direction'];
		}

		if ( $settings['divider_style'] ) {
			$classes[] = 'pp-divider-' . $settings['divider_style'];
			$classes[] = $settings['divider_style'];
		}

		$this->add_render_attribute( 'divider', 'class', $classes );

		$this->add_render_attribute( 'divider-content', 'class', array( 'pp-divider-' . $settings['divider_type'], 'pp-icon' ) );

		$this->add_inline_editing_attributes( 'divider_text', 'none' );
		$this->add_render_attribute( 'divider_text', 'class', 'pp-divider-' . $settings['divider_type'] );

		if ( $settings['divider_type'] == 'icon' ) {
			if ( ! isset( $settings['divider_icon'] ) && ! Icons_Manager::is_migration_allowed() ) {
				// add old default
				$settings['divider_icon'] = 'fa fa-circle';
			}

			$has_icon = ! empty( $settings['divider_icon'] );

			if ( $has_icon ) {
				$this->add_render_attribute( 'i', 'class', $settings['divider_icon'] );
				$this->add_render_attribute( 'i', 'aria-hidden', 'true' );
			}

			$icon_attributes = $this->get_render_attribute_string( 'divider_icon' );

			if ( ! $has_icon && ! empty( $settings['icon']['value'] ) ) {
				$has_icon = true;
			}
			$migrated = isset( $settings['__fa4_migrated']['icon'] );
			$is_new   = ! isset( $settings['divider_icon'] ) && Icons_Manager::is_migration_allowed();
		}
		?>
		<div class="pp-divider-wrap">
			<?php
			if ( $settings['divider_type'] == 'plain' ) {
				?>
				<div <?php echo $this->get_render_attribute_string( 'divider' ); ?>></div>
				<?php
			} else {
				?>
				<div class="divider-text-container">
					<div class="divider-text-wrap">
						<span class="pp-divider-border-wrap divider-border-left">
							<span class="divider-border"></span>
						</span>
						<span class="pp-divider-content">
							<?php if ( $settings['divider_type'] == 'text' && $settings['divider_text'] ) { ?>
								<?php
									printf( '<%1$s %2$s>%3$s</%1$s>', $settings['text_html_tag'], $this->get_render_attribute_string( 'divider_text' ), $settings['divider_text'] );
								?>
							<?php } elseif ( $settings['divider_type'] == 'icon' ) { ?>
								<?php if ( ! empty( $settings['divider_icon'] ) || ( ! empty( $settings['icon']['value'] ) && $is_new ) ) { ?>
									<span <?php echo $this->get_render_attribute_string( 'divider-content' ); ?>>
										<?php
										if ( $is_new || $migrated ) {
											Icons_Manager::render_icon( $settings['icon'], array( 'aria-hidden' => 'true' ) );
										} elseif ( ! empty( $settings['divider_icon'] ) ) {
											?>
												<i <?php echo $this->get_render_attribute_string( 'i' ); ?>></i>
												<?php
										}
										?>
									</span>
								<?php } ?>
							<?php } elseif ( $settings['divider_type'] == 'image' ) { ?>
								<span <?php echo $this->get_render_attribute_string( 'divider-content' ); ?>>
									<?php
										$image = $settings['divider_image'];
									if ( $image['url'] ) {
										echo Group_Control_Image_Size::get_attachment_image_html( $settings, 'image', 'divider_image' );
									}
									?>
								</span>
							<?php } ?>
						</span>
						<span class="pp-divider-border-wrap divider-border-right">
							<span class="divider-border"></span>
						</span>
					</div>
				</div>
				<?php
			}
			?>
		</div>    
		<?php
	}

	/**
	 * Render divider widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @access protected
	 */
	protected function _content_template() {
		?>
		<#
		var iconHTML = elementor.helpers.renderIcon( view, settings.icon, { 'aria-hidden': true }, 'i' , 'object' ),
			migrated = elementor.helpers.isIconMigrated( settings, 'icon' );   
		#>
		<div class="pp-divider-wrap">
			<# if ( settings.divider_type == 'plain' ) { #>
				<div class="pp-divider pp-divider-{{ settings.divider_direction }} {{ settings.divider_direction }} pp-divider-{{ settings.divider_style }} {{ settings.divider_style }} "></div>
			<# } else { #>
				<div class="divider-text-container">
					<div class="divider-text-wrap">
						<span class="pp-divider-border-wrap divider-border-left">
							<span class="divider-border"></span>
						</span>
						<span class="pp-divider-content">
							<# if ( settings.divider_type == 'text' && settings.divider_text != '' ) { #>
								<{{ settings.text_html_tag }} class="pp-divider-{{ settings.divider_type }} elementor-inline-editing" data-elementor-setting-key="divider_text" data-elementor-inline-editing-toolbar="none">
									{{ settings.divider_text }}
								</{{ settings.text_html_tag }}>
							<# } else if ( settings.divider_type == 'icon' && settings.divider_icon != '' ) { #>
								<span class="pp-divider-{{ settings.divider_type }} pp-icon">
									<# if ( settings.divider_icon || settings.icon ) { #>
									<# if ( iconHTML && iconHTML.rendered && ( ! settings.divider_icon || migrated ) ) { #>
									{{{ iconHTML.value }}}
									<# } else { #>
										<i class="{{ settings.divider_icon }}" aria-hidden="true"></i>
									<# } #>
									<# } #>
								</span>
							<# } else if ( settings.divider_type == 'image' ) { #>
								<span class="pp-divider-{{ settings.divider_type }}">
									<#
									var image = {
										id: settings.divider_image.id,
										url: settings.divider_image.url,
										size: settings.image_size,
										dimension: settings.image_custom_dimension,
										model: view.getEditModel()
									};
									var image_url = elementor.imagesManager.getImageUrl( image );
									#>
									<img src="{{{ image_url }}}" />
								</span>
							<# } #>
						</span>
						<span class="pp-divider-border-wrap divider-border-right">
							<span class="divider-border"></span>
						</span>
					</div>
				</div>
			<# } #>
		</div>
		<?php
	}
}
