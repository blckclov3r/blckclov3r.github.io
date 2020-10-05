<?php
namespace PowerpackElementsLite\Modules\IconList\Widgets;

use PowerpackElementsLite\Base\Powerpack_Widget;

// Elementor Classes
use Elementor\Controls_Manager;
use Elementor\Control_Media;
use Elementor\Utils;
use Elementor\Repeater;
use Elementor\Icons_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Image_Size;
use Elementor\Core\Schemes;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Icon List Widget
 */
class Icon_List extends Powerpack_Widget {

	/**
	 * Retrieve icon list widget name.
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return parent::get_widget_name( 'Icon_List' );
	}

	/**
	 * Retrieve icon list widget title.
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return parent::get_widget_title( 'Icon_List' );
	}

	/**
	 * Retrieve icon list widget icon.
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return parent::get_widget_icon( 'Icon_List' );
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
		return parent::get_widget_keywords( 'Icon_List' );
	}

	/**
	 * Register icon list widget controls.
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
		 * Content Tab: List
		 */
		$this->start_controls_section(
			'section_list',
			array(
				'label' => __( 'List', 'powerpack' ),
			)
		);

		$this->add_control(
			'view',
			array(
				'label'        => __( 'Layout', 'powerpack' ),
				'type'         => Controls_Manager::CHOOSE,
				'default'      => 'traditional',
				'options'      => array(
					'traditional' => array(
						'title' => __( 'Default', 'powerpack' ),
						'icon'  => 'eicon-editor-list-ul',
					),
					'inline'      => array(
						'title' => __( 'Inline', 'powerpack' ),
						'icon'  => 'eicon-ellipsis-h',
					),
				),
				'render_type'  => 'template',
				'prefix_class' => 'pp-icon-list-',
				'label_block'  => false,
			)
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'text',
			array(
				'label'       => __( 'Text', 'powerpack' ),
				'label_block' => true,
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => array(
					'active' => true,
				),
				'default'     => __( 'List Item #1', 'powerpack' ),
			)
		);

		$repeater->add_control(
			'pp_icon_type',
			array(
				'label'       => esc_html__( 'Icon Type', 'powerpack' ),
				'type'        => Controls_Manager::CHOOSE,
				'label_block' => false,
				'options'     => array(
					'none'   => array(
						'title' => esc_html__( 'None', 'powerpack' ),
						'icon'  => 'fa fa-ban',
					),
					'icon'   => array(
						'title' => esc_html__( 'Icon', 'powerpack' ),
						'icon'  => 'fa fa-star',
					),
					'image'  => array(
						'title' => esc_html__( 'Image', 'powerpack' ),
						'icon'  => 'fa fa-picture-o',
					),
					'number' => array(
						'title' => esc_html__( 'Number', 'powerpack' ),
						'icon'  => 'fa fa-hashtag',
					),
				),
				'default'     => 'icon',
			)
		);

		$repeater->add_control(
			'icon',
			array(
				'label'            => __( 'Icon', 'powerpack' ),
				'type'             => Controls_Manager::ICONS,
				'label_block'      => true,
				'default'          => array(
					'value'   => 'fas fa-check',
					'library' => 'fa-solid',
				),
				'fa4compatibility' => 'list_icon',
				'condition'        => array(
					'pp_icon_type' => 'icon',
				),
			)
		);

		$repeater->add_control(
			'list_image',
			array(
				'label'       => __( 'Image', 'powerpack' ),
				'label_block' => true,
				'type'        => Controls_Manager::MEDIA,
				'dynamic'     => array(
					'active' => true,
				),
				'default'     => array(
					'url' => Utils::get_placeholder_image_src(),
				),
				'condition'   => array(
					'pp_icon_type' => 'image',
				),
			)
		);

		$repeater->add_control(
			'icon_text',
			array(
				'label'       => __( 'Number/Text', 'powerpack' ),
				'label_block' => false,
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'condition'   => array(
					'pp_icon_type' => 'number',
				),
			)
		);

		$repeater->add_control(
			'link',
			array(
				'label'       => __( 'Link', 'powerpack' ),
				'type'        => Controls_Manager::URL,
				'label_block' => true,
				'dynamic'     => array(
					'active' => true,
				),
				'placeholder' => __( 'http://your-link.com', 'powerpack' ),
			)
		);

		$this->add_control(
			'list_items',
			array(
				'label'       => '',
				'type'        => Controls_Manager::REPEATER,
				'default'     => array(
					array(
						'text' => __( 'List Item #1', 'powerpack' ),
						'icon' => __( 'fa fa-check', 'powerpack' ),
					),
					array(
						'text' => __( 'List Item #2', 'powerpack' ),
						'icon' => __( 'fa fa-check', 'powerpack' ),
					),
					array(
						'text' => __( 'List Item #3', 'powerpack' ),
						'icon' => __( 'fa fa-check', 'powerpack' ),
					),
				),
				'fields'      => $repeater->get_controls(),
				'title_field' => '<i class="{{ icon }}" aria-hidden="true"></i> {{{ text }}}',
			)
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			array(
				'name'      => 'image', // Usage: '{name}_size' and '{name}_custom_dimension', in this case 'thumbnail_size' and 'thumbnail_custom_dimension'.,
				'label'     => __( 'Image Size', 'powerpack' ),
				'default'   => 'full',
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
				'raw'             => sprintf( __( '%1$s Widget Overview %2$s', 'powerpack' ), '<a href="https://powerpackelements.com/docs/powerpack/widgets/icon-list/icon-list-widget-overview/?utm_source=widget&utm_medium=panel&utm_campaign=userkb" target="_blank" rel="noopener">', '</a>' ),
				'content_classes' => 'pp-editor-doc-links',
			)
		);

		$this->end_controls_section();

		if ( ! is_pp_elements_active() ) {
			/**
			 * Content Tab: Upgrade PowerPack
			 *
			 * @since 1.2.9.4
			 * @access protected
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

		/**
		 * Style Tab: List
		 */
		$this->start_controls_section(
			'section_list_style',
			array(
				'label' => __( 'List', 'powerpack' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'items_background',
				'label'    => __( 'Background', 'powerpack' ),
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .pp-list-items li',
			)
		);

		$this->add_responsive_control(
			'items_spacing',
			array(
				'label'     => __( 'List Items Gap', 'powerpack' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'max' => 50,
					),
				),
				'separator' => 'before',
				'selectors' => array(
					'{{WRAPPER}} .pp-list-items:not(.pp-inline-items) li:not(:last-child)' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .pp-list-items.pp-inline-items li:not(:last-child)' => 'margin-right: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'list_items_padding',
			array(
				'label'      => __( 'Padding', 'powerpack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .pp-list-items li' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'list_items_alignment',
			array(
				'label'                => __( 'Alignment', 'powerpack' ),
				'type'                 => Controls_Manager::CHOOSE,
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
				'default'              => '',
				'selectors'            => array(
					'{{WRAPPER}}.pp-icon-list-traditional .pp-list-items li, {{WRAPPER}}.pp-icon-list-inline .pp-list-items' => 'justify-content: {{VALUE}};',
				),
				'selectors_dictionary' => array(
					'left'  => 'flex-start',
					'right' => 'flex-end',
				),
			)
		);

		$this->add_control(
			'divider',
			array(
				'label'     => __( 'Divider', 'powerpack' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_off' => __( 'Off', 'powerpack' ),
				'label_on'  => __( 'On', 'powerpack' ),
				'separator' => 'before',
			)
		);

		$this->add_control(
			'divider_style',
			array(
				'label'     => __( 'Style', 'powerpack' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => array(
					'solid'  => __( 'Solid', 'powerpack' ),
					'double' => __( 'Double', 'powerpack' ),
					'dotted' => __( 'Dotted', 'powerpack' ),
					'dashed' => __( 'Dashed', 'powerpack' ),
					'groove' => __( 'Groove', 'powerpack' ),
					'ridge'  => __( 'Ridge', 'powerpack' ),
				),
				'default'   => 'solid',
				'condition' => array(
					'divider' => 'yes',
				),
				'selectors' => array(
					'{{WRAPPER}} .pp-list-items:not(.pp-inline-items) li:not(:last-child)' => 'border-bottom-style: {{VALUE}};',
					'{{WRAPPER}} .pp-list-items.pp-inline-items li:not(:last-child)' => 'border-right-style: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'divider_weight',
			array(
				'label'     => __( 'Weight', 'powerpack' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array(
					'size' => 1,
				),
				'range'     => array(
					'px' => array(
						'min' => 1,
						'max' => 10,
					),
				),
				'condition' => array(
					'divider' => 'yes',
				),
				'selectors' => array(
					'{{WRAPPER}} .pp-list-items:not(.pp-inline-items) li:not(:last-child)' => 'border-bottom-width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .pp-list-items.pp-inline-items li:not(:last-child)' => 'border-right-width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'divider_color',
			array(
				'label'     => __( 'Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ddd',
				'scheme'    => array(
					'type'  => Schemes\Color::get_type(),
					'value' => Schemes\Color::COLOR_3,
				),
				'condition' => array(
					'divider' => 'yes',
				),
				'selectors' => array(
					'{{WRAPPER}} .pp-list-items:not(.pp-inline-items) li:not(:last-child)' => 'border-bottom-color: {{VALUE}};',
					'{{WRAPPER}} .pp-list-items.pp-inline-items li:not(:last-child)' => 'border-right-color: {{VALUE}};',
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
				'label' => __( 'Icon', 'powerpack' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'icon_position',
			array(
				'label'        => __( 'Position', 'powerpack' ),
				'type'         => Controls_Manager::CHOOSE,
				'label_block'  => false,
				'toggle'       => false,
				'default'      => 'left',
				'options'      => array(
					'left'  => array(
						'title' => __( 'Left', 'powerpack' ),
						'icon'  => 'eicon-h-align-left',
					),
					'right' => array(
						'title' => __( 'Right', 'powerpack' ),
						'icon'  => 'eicon-h-align-right',
					),
				),
				'prefix_class' => 'pp-icon-',
			)
		);

		$this->add_control(
			'icon_vertical_align',
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
				'selectors_dictionary' => array(
					'top'    => 'flex-start',
					'middle' => 'center',
					'bottom' => 'flex-end',
				),
				'selectors'            => array(
					'{{WRAPPER}} .pp-list-container .pp-list-items li'   => 'align-items: {{VALUE}};',
				),
			)
		);

		$this->start_controls_tabs( 'tabs_icon_style' );

		$this->start_controls_tab(
			'tab_icon_normal',
			array(
				'label' => __( 'Normal', 'powerpack' ),
			)
		);

		$this->add_control(
			'icon_color',
			array(
				'label'     => __( 'Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .pp-list-items .pp-icon-list-icon' => 'color: {{VALUE}};',
					'{{WRAPPER}} .pp-list-items .pp-icon-list-icon svg' => 'fill: {{VALUE}};',
				),
				'scheme'    => array(
					'type'  => Schemes\Color::get_type(),
					'value' => Schemes\Color::COLOR_1,
				),
			)
		);

		$this->add_control(
			'icon_bg_color',
			array(
				'label'     => __( 'Background Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .pp-list-items .pp-icon-wrapper' => 'background: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'icon_size',
			array(
				'label'     => __( 'Size', 'powerpack' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array(
					'size' => 14,
				),
				'range'     => array(
					'px' => array(
						'min' => 6,
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .pp-list-items .pp-icon-list-icon' => 'font-size: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .pp-list-items .pp-icon-list-image img' => 'width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'icon_spacing',
			array(
				'label'     => __( 'Spacing', 'powerpack' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array(
					'size' => 8,
				),
				'range'     => array(
					'px' => array(
						'max' => 50,
					),
				),
				'selectors' => array(
					'{{WRAPPER}}.pp-icon-left .pp-list-items .pp-icon-wrapper' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.pp-icon-right .pp-list-items .pp-icon-wrapper' => 'margin-left: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'icon_border',
				'label'       => __( 'Border', 'powerpack' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .pp-list-items .pp-icon-wrapper',
			)
		);

		$this->add_control(
			'icon_border_radius',
			array(
				'label'      => __( 'Border Radius', 'powerpack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .pp-list-items .pp-icon-wrapper, {{WRAPPER}} .pp-list-items .pp-icon-list-image img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'icon_padding',
			array(
				'label'      => __( 'Padding', 'powerpack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .pp-list-items .pp-icon-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_icon_hover',
			array(
				'label' => __( 'Hover', 'powerpack' ),
			)
		);

		$this->add_control(
			'icon_color_hover',
			array(
				'label'     => __( 'Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .pp-icon-list-item:hover .pp-icon-wrapper .pp-icon-list-icon' => 'color: {{VALUE}};',
					'{{WRAPPER}} .pp-icon-list-item:hover .pp-icon-wrapper .pp-icon-list-icon svg' => 'fill: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'icon_bg_color_hover',
			array(
				'label'     => __( 'Background Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .pp-icon-list-item:hover .pp-icon-wrapper' => 'background: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'icon_border_color_hover',
			array(
				'label'     => __( 'Border Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .pp-icon-list-item:hover .pp-icon-wrapper' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'icon_hover_animation',
			array(
				'label' => __( 'Animation', 'powerpack' ),
				'type'  => Controls_Manager::HOVER_ANIMATION,
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
				'label' => __( 'Text', 'powerpack' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->start_controls_tabs( 'tabs_text_style' );

		$this->start_controls_tab(
			'tab_text_normal',
			array(
				'label' => __( 'Normal', 'powerpack' ),
			)
		);

		$this->add_control(
			'text_color',
			array(
				'label'     => __( 'Text Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .pp-icon-list-text' => 'color: {{VALUE}};',
				),
				'scheme'    => array(
					'type'  => Schemes\Color::get_type(),
					'value' => Schemes\Color::COLOR_2,
				),
			)
		);

		$this->add_control(
			'text_bg_color',
			array(
				'label'     => __( 'Background Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .pp-icon-list-text' => 'background: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'text_typography',
				'label'    => __( 'Typography', 'powerpack' ),
				'scheme'   => Schemes\Typography::TYPOGRAPHY_4,
				'selector' => '{{WRAPPER}} .pp-icon-list-text',
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_text_hover',
			array(
				'label' => __( 'Hover', 'powerpack' ),
			)
		);

		$this->add_control(
			'text_hover_color',
			array(
				'label'     => __( 'Text Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .pp-icon-list-item:hover .pp-icon-list-text' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'text_hover_bg_color',
			array(
				'label'     => __( 'Background Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .pp-icon-list-item:hover .pp-icon-list-text' => 'background: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->end_controls_section();

	}

	/**
	 * Render icon list widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		$this->add_render_attribute(
			array(
				'icon-list' => array(
					'class' => 'pp-list-items',
				),
				'icon'      => array(
					'class' => 'pp-icon-list-icon',
				),
				'icon-wrap' => array(
					'class' => 'pp-icon-wrapper',
				),
			)
		);

		if ( 'inline' === $settings['view'] ) {
			$this->add_render_attribute( 'icon-list', 'class', 'pp-inline-items' );
		}

		$i = 1;
		?>
		<div class="pp-list-container">
			<ul <?php echo $this->get_render_attribute_string( 'icon-list' ); ?>>
				<?php foreach ( $settings['list_items'] as $index => $item ) : ?>
					<?php if ( $item['text'] ) { ?>
						<?php
							$item_key = $this->get_repeater_setting_key( 'item', 'list_items', $index );
							$text_key = $this->get_repeater_setting_key( 'text', 'list_items', $index );

							$this->add_render_attribute(
								array(
									$item_key => array(
										'class' => 'pp-icon-list-item',
									),
									$text_key => array(
										'class' => 'pp-icon-list-text',
									),
								)
							);

							$this->add_inline_editing_attributes( $text_key, 'none' );
						?>
						<li <?php echo $this->get_render_attribute_string( $item_key ); ?>>
							<?php
							if ( ! empty( $item['link']['url'] ) ) {
								$link_key = 'link_' . $i;

								$this->add_link_attributes( $link_key, $item['link'] );

								echo '<a ' . $this->get_render_attribute_string( $link_key ) . '>';
							}

								$this->render_iconlist_icon( $item, $i );

								printf( '<span %1$s>%2$s</span>', $this->get_render_attribute_string( $text_key ), $item['text'] );

							if ( ! empty( $item['link']['url'] ) ) {
								echo '</a>';
							}
							?>
						</li>
					<?php } ?>
					<?php
					$i++;
endforeach;
				?>
			</ul>
		</div>
		<?php
	}

	/**
	 * Render info-box carousel icon output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @access protected
	 */
	protected function render_iconlist_icon( $item, $i ) {
		$settings = $this->get_settings_for_display();

		$fallback_defaults = array(
			'fa fa-check',
			'fa fa-times',
			'fa fa-dot-circle-o',
		);

		$migration_allowed = Icons_Manager::is_migration_allowed();

		// add old default
		if ( ! isset( $item['list_icon'] ) && ! $migration_allowed ) {
			$item['list_icon'] = isset( $fallback_defaults[ $index ] ) ? $fallback_defaults[ $index ] : 'fa fa-check';
		}

		$migrated = isset( $item['__fa4_migrated']['icon'] );
		$is_new   = ! isset( $item['list_icon'] ) && $migration_allowed;

		if ( $item['pp_icon_type'] != 'none' ) {
			$icon_key = 'icon_' . $i;
			$this->add_render_attribute( $icon_key, 'class', 'pp-icon-wrapper' );

			if ( $settings['icon_hover_animation'] != '' ) {
				$icon_animation = 'elementor-animation-' . $settings['icon_hover_animation'];
			} else {
				$icon_animation = '';
			}
			?>
			<span <?php echo $this->get_render_attribute_string( $icon_key ); ?>>
				<?php
				if ( $item['pp_icon_type'] == 'icon' ) {
					if ( ! empty( $item['list_icon'] ) || ( ! empty( $item['icon']['value'] ) && $is_new ) ) {
						echo '<span class="pp-icon-list-icon pp-icon ' . $icon_animation . '">';
						if ( $is_new || $migrated ) {
							Icons_Manager::render_icon( $item['icon'], array( 'aria-hidden' => 'true' ) );
						} else {
							?>
									<i class="<?php echo esc_attr( $item['list_icon'] ); ?>" aria-hidden="true"></i>
							<?php
						}
						echo '</span>';
					}
				} elseif ( $item['pp_icon_type'] == 'image' ) {
					$image_url = Group_Control_Image_Size::get_attachment_image_src( $item['list_image']['id'], 'image', $settings );

					if ( $image_url ) {
						$image_html = '<img src="' . esc_url( $image_url ) . '" alt="' . esc_attr( Control_Media::get_image_alt( $item['list_image'] ) ) . '">';
					} else {
						$image_html = '<img src="' . esc_url( $item['list_image']['url'] ) . '">';
					}

					printf( '<span class="pp-icon-list-image %2$s">%1$s</span>', $image_html, $icon_animation );
				} elseif ( $item['pp_icon_type'] == 'number' ) {
					if ( $item['icon_text'] ) {
						$number = $item['icon_text'];
					} else {
						$number = $i;
					}
					printf( '<span class="pp-icon-list-icon %2$s">%1$s</span>', $number, $icon_animation );
				}
				?>
			</span>
			<?php
		}
	}

	/**
	 * Render icon list widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @access protected
	 */
	protected function _content_template() {
		?>
		<div class="pp-list-container">
			<#
				var iconsHTML = {},
					migrated = {},
					   list_class = '';
			   
				if ( settings.view == 'inline' ) {
					list_class = 'pp-inline-items';
				}
			   
				   view.addRenderAttribute( 'list_items', 'class', [ 'pp-list-items', list_class ] );
			#>
			<ul {{{ view.getRenderAttributeString( 'list_items' ) }}}>
				<# var i = 1; #>
				<# _.each( settings.list_items, function( item, index ) {
					
					var itemKey = view.getRepeaterSettingKey( 'item', 'list_items', index ),
						textKey = view.getRepeaterSettingKey( 'text', 'list_items', index );
				   
				   view.addRenderAttribute( itemKey, {
						'class': 'pp-icon-list-item'
					});
					view.addRenderAttribute( textKey, {
						'class': 'pp-icon-list-text'
					});

				   view.addInlineEditingAttributes( textKey );
				   #>
					<# if ( item.text != '' ) { #>
						<li {{{ view.getRenderAttributeString( itemKey ) }}}>
							<# if ( item.link && item.link.url ) { #>
								<a href="{{ item.link.url }}">
							<# } #>
							<# if ( item.pp_icon_type != 'none' ) { #>
								<#
									if ( settings.icon_position == 'right' ) {
										var icon_class = 'pp-icon-right';
									} else {
										var icon_class = 'pp-icon-left';
									}
								#>
								<span class="pp-icon-wrapper {{ icon_class }}">
									<# if ( item.pp_icon_type == 'icon' ) { #>
										<# if ( item.list_icon || item.icon.value ) { #>
											<span class="pp-icon-list-icon pp-icon elementor-animation-{{ settings.icon_hover_animation }}" aria-hidden="true">
											<#
												iconsHTML[ index ] = elementor.helpers.renderIcon( view, item.icon, { 'aria-hidden': true }, 'i', 'object' );
												migrated[ index ] = elementor.helpers.isIconMigrated( item, 'icon' );
												if ( iconsHTML[ index ] && iconsHTML[ index ].rendered && ( ! item.list_icon || migrated[ index ] ) ) { #>
													{{{ iconsHTML[ index ].value }}}
												<# } else { #>
													<i class="{{ item.list_icon }}" aria-hidden="true"></i>
												<# }
											#>
											</span>
										<# } #>
									<# } else if ( item.pp_icon_type == 'image' ) { #>
										<span class="pp-icon-list-image elementor-animation-{{ settings.icon_hover_animation }}">
											<#
											var image = {
												id: item.list_image.id,
												url: item.list_image.url,
												size: settings.image_size,
												dimension: settings.image_custom_dimension,
												model: view.getEditModel()
											};
											var image_url = elementor.imagesManager.getImageUrl( image );
											#>
											<img src="{{{ image_url }}}" />
										</span>
									<# } else if ( item.pp_icon_type == 'number' ) { #>
										<#
										   if ( item.icon_text ) {
												   var number = item.icon_text;
										   } else {
												   var number = i;
										   }
										#>
										<span class="pp-icon-list-icon elementor-animation-{{ settings.icon_hover_animation }}">
											{{ number }}
										</span>
									<# } #>
								</span>
							<# } #>

							<#
								var text = item.text;

								var text_html = '<span' + ' ' + view.getRenderAttributeString( textKey ) + ' >' + text + '</span>';

								print( text_html );
							#>

							<# if ( item.link && item.link.url ) { #>
								</a>
							<# } #>
						</li>
					<# } #>
				<# i++ } ); #>
			</ul>
		</div>
		<?php
	}
}
