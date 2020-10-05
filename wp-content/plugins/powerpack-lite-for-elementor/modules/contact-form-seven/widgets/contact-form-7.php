<?php
namespace PowerpackElementsLite\Modules\ContactFormSeven\Widgets;

use PowerpackElementsLite\Base\Powerpack_Widget;

// Elementor Classes
use Elementor\Controls_Manager;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Typography;
use Elementor\Scheme_Color;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
/**
 * Contact Form 7 Widget
 */
class Contact_Form_7 extends Powerpack_Widget {

	/**
	 * Retrieve contact form 7 widget name.
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return parent::get_widget_name( 'Contact_Form_7' );
	}

	/**
	 * Retrieve contact form 7 widget title.
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return parent::get_widget_title( 'Contact_Form_7' );
	}

	/**
	 * Retrieve contact form 7 widget icon.
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return parent::get_widget_icon( 'Contact_Form_7' );
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
		return parent::get_widget_keywords( 'Contact_Form_7' );
	}

	/**
	 * Register contact form 7 widget controls.
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
		 * Content Tab: Contact Form
		 * -------------------------------------------------
		 */
		$this->start_controls_section(
			'section_info_box',
			array(
				'label' => __( 'Contact Form', 'powerpack' ),
			)
		);

		$this->add_control(
			'contact_form_list',
			array(
				'label'       => esc_html__( 'Contact Form', 'powerpack' ),
				'type'        => Controls_Manager::SELECT,
				'label_block' => true,
				'options'     => pp_elements_lite_get_contact_form_7_forms(),
				'default'     => '0',
			)
		);

		$this->add_control(
			'form_title',
			array(
				'label'        => __( 'Form Title', 'powerpack' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'On', 'powerpack' ),
				'label_off'    => __( 'Off', 'powerpack' ),
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'form_title_text',
			array(
				'label'       => esc_html__( 'Title', 'powerpack' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'default'     => '',
				'condition'   => array(
					'form_title' => 'yes',
				),
			)
		);

		$this->add_control(
			'form_description',
			array(
				'label'        => __( 'Form Description', 'powerpack' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'On', 'powerpack' ),
				'label_off'    => __( 'Off', 'powerpack' ),
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'form_description_text',
			array(
				'label'     => esc_html__( 'Description', 'powerpack' ),
				'type'      => Controls_Manager::TEXTAREA,
				'default'   => '',
				'condition' => array(
					'form_description' => 'yes',
				),
			)
		);

		$this->add_control(
			'labels_switch',
			array(
				'label'        => __( 'Labels', 'powerpack' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'label_on'     => __( 'Show', 'powerpack' ),
				'label_off'    => __( 'Hide', 'powerpack' ),
				'return_value' => 'yes',
			)
		);

		$this->end_controls_section();

		/**
		 * Content Tab: Errors
		 * -------------------------------------------------
		 */
		$this->start_controls_section(
			'section_errors',
			array(
				'label' => __( 'Errors', 'powerpack' ),
			)
		);

		$this->add_control(
			'error_messages',
			array(
				'label'                => __( 'Error Messages', 'powerpack' ),
				'type'                 => Controls_Manager::SELECT,
				'default'              => 'show',
				'options'              => array(
					'show' => __( 'Show', 'powerpack' ),
					'hide' => __( 'Hide', 'powerpack' ),
				),
				'selectors_dictionary' => array(
					'show' => 'block',
					'hide' => 'none',
				),
				'selectors'            => array(
					'{{WRAPPER}} .pp-contact-form-7 .wpcf7-not-valid-tip' => 'display: {{VALUE}} !important;',
				),
			)
		);

		$this->add_control(
			'validation_errors',
			array(
				'label'                => __( 'Validation Errors', 'powerpack' ),
				'type'                 => Controls_Manager::SELECT,
				'default'              => 'show',
				'options'              => array(
					'show' => __( 'Show', 'powerpack' ),
					'hide' => __( 'Hide', 'powerpack' ),
				),
				'selectors_dictionary' => array(
					'show' => 'block',
					'hide' => 'none',
				),
				'selectors'            => array(
					'{{WRAPPER}} .pp-contact-form-7 .wpcf7-validation-errors' => 'display: {{VALUE}} !important;',
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
		 * Style Tab: Title & Description
		 * -------------------------------------------------
		 */
		$this->start_controls_section(
			'section_fields_title_description',
			array(
				'label' => __( 'Title & Description', 'powerpack' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'heading_alignment',
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
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .pp-contact-form-7 .pp-contact-form-7-heading' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'title_heading',
			array(
				'label'     => __( 'Title', 'powerpack' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'title_text_color',
			array(
				'label'     => __( 'Text Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .pp-contact-form-7 .pp-contact-form-7-title' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'title_typography',
				'label'    => __( 'Typography', 'powerpack' ),
				'scheme'   => Scheme_Typography::TYPOGRAPHY_4,
				'selector' => '{{WRAPPER}} .pp-contact-form-7 .pp-contact-form-7-title',
			)
		);

		$this->add_control(
			'description_heading',
			array(
				'label'     => __( 'Description', 'powerpack' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'description_text_color',
			array(
				'label'     => __( 'Text Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .pp-contact-form-7 .pp-contact-form-7-description' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'description_typography',
				'label'    => __( 'Typography', 'powerpack' ),
				'scheme'   => Scheme_Typography::TYPOGRAPHY_4,
				'selector' => '{{WRAPPER}} .pp-contact-form-7 .pp-contact-form-7-description',
			)
		);

		$this->end_controls_section();

		/**
		 * Style Tab: Input & Textarea
		 * -------------------------------------------------
		 */
		$this->start_controls_section(
			'section_fields_style',
			array(
				'label' => __( 'Input & Textarea', 'powerpack' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->start_controls_tabs( 'tabs_fields_style' );

		$this->start_controls_tab(
			'tab_fields_normal',
			array(
				'label' => __( 'Normal', 'powerpack' ),
			)
		);

		$this->add_control(
			'field_bg',
			array(
				'label'     => __( 'Background Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .pp-contact-form-7 .wpcf7-form-control.wpcf7-text, {{WRAPPER}} .pp-contact-form-7 .wpcf7-form-control.wpcf7-textarea, {{WRAPPER}} .pp-contact-form-7 .wpcf7-form-control.wpcf7-select' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'field_text_color',
			array(
				'label'     => __( 'Text Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .pp-contact-form-7 .wpcf7-form-control.wpcf7-text, {{WRAPPER}} .pp-contact-form-7 .wpcf7-form-control.wpcf7-textarea, {{WRAPPER}} .pp-contact-form-7 .wpcf7-form-control.wpcf7-select' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_responsive_control(
			'input_spacing',
			array(
				'label'      => __( 'Spacing', 'powerpack' ),
				'type'       => Controls_Manager::SLIDER,
				'default'    => array(
					'size' => '20',
					'unit' => 'px',
				),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					),
				),
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .pp-contact-form-7 .wpcf7-form p:not(:last-of-type) .wpcf7-form-control-wrap' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_responsive_control(
			'field_padding',
			array(
				'label'      => __( 'Padding', 'powerpack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .pp-contact-form-7 .wpcf7-form-control.wpcf7-text, {{WRAPPER}} .pp-contact-form-7 .wpcf7-form-control.wpcf7-textarea' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'text_indent',
			array(
				'label'      => __( 'Text Indent', 'powerpack' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 60,
						'step' => 1,
					),
					'%'  => array(
						'min'  => 0,
						'max'  => 30,
						'step' => 1,
					),
				),
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .pp-contact-form-7 .wpcf7-form-control.wpcf7-text, {{WRAPPER}} .pp-contact-form-7 .wpcf7-form-control.wpcf7-textarea, {{WRAPPER}} .pp-contact-form-7 .wpcf7-form-control.wpcf7-select' => 'text-indent: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_responsive_control(
			'input_width',
			array(
				'label'      => __( 'Input Width', 'powerpack' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 1200,
						'step' => 1,
					),
				),
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .pp-contact-form-7 .wpcf7-form-control.wpcf7-text, {{WRAPPER}} .pp-contact-form-7 .wpcf7-form-control.wpcf7-select' => 'width: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_responsive_control(
			'textarea_width',
			array(
				'label'      => __( 'Textarea Width', 'powerpack' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 1200,
						'step' => 1,
					),
				),
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .pp-contact-form-7 .wpcf7-form-control.wpcf7-textarea' => 'width: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'field_border',
				'label'       => __( 'Border', 'powerpack' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .pp-contact-form-7 .wpcf7-form-control.wpcf7-text, {{WRAPPER}} .pp-contact-form-7 .wpcf7-form-control.wpcf7-textarea, {{WRAPPER}} .pp-contact-form-7 .wpcf7-form-control.wpcf7-select',
				'separator'   => 'before',
			)
		);

		$this->add_control(
			'field_radius',
			array(
				'label'      => __( 'Border Radius', 'powerpack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .pp-contact-form-7 .wpcf7-form-control.wpcf7-text, {{WRAPPER}} .pp-contact-form-7 .wpcf7-form-control.wpcf7-textarea' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'field_typography',
				'label'     => __( 'Typography', 'powerpack' ),
				'scheme'    => Scheme_Typography::TYPOGRAPHY_4,
				'selector'  => '{{WRAPPER}} .pp-contact-form-7 .wpcf7-form-control.wpcf7-text, {{WRAPPER}} .pp-contact-form-7 .wpcf7-form-control.wpcf7-textarea, {{WRAPPER}} .pp-contact-form-7 .wpcf7-form-control.wpcf7-select',
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'      => 'field_box_shadow',
				'selector'  => '{{WRAPPER}} .pp-contact-form-7 .wpcf7-form-control.wpcf7-text, {{WRAPPER}} .pp-contact-form-7 .wpcf7-form-control.wpcf7-textarea, {{WRAPPER}} .pp-contact-form-7 .wpcf7-form-control.wpcf7-select',
				'separator' => 'before',
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_fields_focus',
			array(
				'label' => __( 'Focus', 'powerpack' ),
			)
		);

		$this->add_control(
			'field_bg_focus',
			array(
				'label'     => __( 'Background Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .pp-contact-form-7 .wpcf7-form input:focus, {{WRAPPER}} .pp-contact-form-7 .wpcf7-form textarea:focus' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'input_border_focus',
				'label'       => __( 'Border', 'powerpack' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .pp-contact-form-7 .wpcf7-form input:focus, {{WRAPPER}} .pp-contact-form-7 .wpcf7-form textarea:focus',
				'separator'   => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'      => 'focus_box_shadow',
				'selector'  => '{{WRAPPER}} .pp-contact-form-7 .wpcf7-form input:focus, {{WRAPPER}} .pp-contact-form-7 .wpcf7-form textarea:focus',
				'separator' => 'before',
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		/**
		 * Style Tab: Label Section
		 */
		$this->start_controls_section(
			'section_label_style',
			array(
				'label'     => __( 'Labels', 'powerpack' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'labels_switch' => 'yes',
				),
			)
		);

		$this->add_control(
			'text_color_label',
			array(
				'label'     => __( 'Text Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .pp-contact-form-7 .wpcf7-form label, {{WRAPPER}} .pp-contact-form-7 .wpcf7-form:not(input)' => 'color: {{VALUE}}',
				),
				'condition' => array(
					'labels_switch' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'label_spacing',
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
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .pp-contact-form-7 .wpcf7-form label' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				),
				'condition'  => array(
					'labels_switch' => 'yes',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'typography_label',
				'label'     => __( 'Typography', 'powerpack' ),
				'scheme'    => Scheme_Typography::TYPOGRAPHY_4,
				'selector'  => '{{WRAPPER}} .pp-contact-form-7 .wpcf7-form label',
				'condition' => array(
					'labels_switch' => 'yes',
				),
			)
		);

		$this->end_controls_section();

		/**
		 * Style Tab: Placeholder Section
		 */
		$this->start_controls_section(
			'section_placeholder_style',
			array(
				'label' => __( 'Placeholder', 'powerpack' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'placeholder_switch',
			array(
				'label'        => __( 'Show Placeholder', 'powerpack' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'label_on'     => __( 'Yes', 'powerpack' ),
				'label_off'    => __( 'No', 'powerpack' ),
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'text_color_placeholder',
			array(
				'label'     => __( 'Text Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .pp-contact-form-7 .wpcf7-form-control::-webkit-input-placeholder' => 'color: {{VALUE}}',
				),
				'condition' => array(
					'placeholder_switch' => 'yes',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'typography_placeholder',
				'label'     => __( 'Typography', 'powerpack' ),
				'scheme'    => Scheme_Typography::TYPOGRAPHY_4,
				'selector'  => '{{WRAPPER}} .pp-contact-form-7 .wpcf7-form-control::-webkit-input-placeholder',
				'condition' => array(
					'placeholder_switch' => 'yes',
				),
			)
		);

		$this->end_controls_section();

		/**
		 * Style Tab: Radio & Checkbox
		 * -------------------------------------------------
		 */
		$this->start_controls_section(
			'section_radio_checkbox_style',
			array(
				'label' => __( 'Radio & Checkbox', 'powerpack' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'custom_radio_checkbox',
			array(
				'label'        => __( 'Custom Styles', 'powerpack' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'powerpack' ),
				'label_off'    => __( 'No', 'powerpack' ),
				'return_value' => 'yes',
			)
		);

		$this->add_responsive_control(
			'radio_checkbox_size',
			array(
				'label'      => __( 'Size', 'powerpack' ),
				'type'       => Controls_Manager::SLIDER,
				'default'    => array(
					'size' => '15',
					'unit' => 'px',
				),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 80,
						'step' => 1,
					),
				),
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .pp-custom-radio-checkbox input[type="checkbox"], {{WRAPPER}} .pp-custom-radio-checkbox input[type="radio"]' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}',
				),
				'condition'  => array(
					'custom_radio_checkbox' => 'yes',
				),
			)
		);

		$this->start_controls_tabs( 'tabs_radio_checkbox_style' );

		$this->start_controls_tab(
			'radio_checkbox_normal',
			array(
				'label'     => __( 'Normal', 'powerpack' ),
				'condition' => array(
					'custom_radio_checkbox' => 'yes',
				),
			)
		);

		$this->add_control(
			'radio_checkbox_color',
			array(
				'label'     => __( 'Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .pp-custom-radio-checkbox input[type="checkbox"], {{WRAPPER}} .pp-custom-radio-checkbox input[type="radio"]' => 'background: {{VALUE}}',
				),
				'condition' => array(
					'custom_radio_checkbox' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'radio_checkbox_border_width',
			array(
				'label'      => __( 'Border Width', 'powerpack' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 15,
						'step' => 1,
					),
				),
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'{{WRAPPER}} .pp-custom-radio-checkbox input[type="checkbox"], {{WRAPPER}} .pp-custom-radio-checkbox input[type="radio"]' => 'border-width: {{SIZE}}{{UNIT}}',
				),
				'condition'  => array(
					'custom_radio_checkbox' => 'yes',
				),
			)
		);

		$this->add_control(
			'radio_checkbox_border_color',
			array(
				'label'     => __( 'Border Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .pp-custom-radio-checkbox input[type="checkbox"], {{WRAPPER}} .pp-custom-radio-checkbox input[type="radio"]' => 'border-color: {{VALUE}}',
				),
				'condition' => array(
					'custom_radio_checkbox' => 'yes',
				),
			)
		);

		$this->add_control(
			'checkbox_heading',
			array(
				'label'     => __( 'Checkbox', 'powerpack' ),
				'type'      => Controls_Manager::HEADING,
				'condition' => array(
					'custom_radio_checkbox' => 'yes',
				),
			)
		);

		$this->add_control(
			'checkbox_border_radius',
			array(
				'label'      => __( 'Border Radius', 'powerpack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .pp-custom-radio-checkbox input[type="checkbox"], {{WRAPPER}} .pp-custom-radio-checkbox input[type="checkbox"]:before' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					'custom_radio_checkbox' => 'yes',
				),
			)
		);

		$this->add_control(
			'radio_heading',
			array(
				'label'     => __( 'Radio Buttons', 'powerpack' ),
				'type'      => Controls_Manager::HEADING,
				'condition' => array(
					'custom_radio_checkbox' => 'yes',
				),
			)
		);

		$this->add_control(
			'radio_border_radius',
			array(
				'label'      => __( 'Border Radius', 'powerpack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .pp-custom-radio-checkbox input[type="radio"], {{WRAPPER}} .pp-custom-radio-checkbox input[type="radio"]:before' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					'custom_radio_checkbox' => 'yes',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'radio_checkbox_checked',
			array(
				'label'     => __( 'Checked', 'powerpack' ),
				'condition' => array(
					'custom_radio_checkbox' => 'yes',
				),
			)
		);

		$this->add_control(
			'radio_checkbox_color_checked',
			array(
				'label'     => __( 'Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .pp-custom-radio-checkbox input[type="checkbox"]:checked:before, {{WRAPPER}} .pp-custom-radio-checkbox input[type="radio"]:checked:before' => 'background: {{VALUE}}',
				),
				'condition' => array(
					'custom_radio_checkbox' => 'yes',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		/**
		 * Style Tab: Submit Button
		 */
		$this->start_controls_section(
			'section_submit_button_style',
			array(
				'label' => __( 'Submit Button', 'powerpack' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'button_align',
			array(
				'label'     => __( 'Alignment', 'powerpack' ),
				'type'      => Controls_Manager::CHOOSE,
				'default'   => 'left',
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
					'{{WRAPPER}} .pp-contact-form-7 .wpcf7-form p:nth-last-of-type(1)'   => 'text-align: {{VALUE}};',
					'{{WRAPPER}} .pp-contact-form-7 .wpcf7-form input[type="submit"]' => 'display:inline-block;',
				),
				'condition' => array(
					'button_width_type' => 'custom',
				),
			)
		);

		$this->add_control(
			'button_width_type',
			array(
				'label'        => __( 'Width', 'powerpack' ),
				'type'         => Controls_Manager::SELECT,
				'default'      => 'custom',
				'options'      => array(
					'full-width' => __( 'Full Width', 'powerpack' ),
					'custom'     => __( 'Custom', 'powerpack' ),
				),
				'prefix_class' => 'pp-contact-form-7-button-',
			)
		);

		$this->add_responsive_control(
			'button_width',
			array(
				'label'      => __( 'Width', 'powerpack' ),
				'type'       => Controls_Manager::SLIDER,
				'default'    => array(
					'size' => '100',
					'unit' => 'px',
				),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 1200,
						'step' => 1,
					),
				),
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .pp-contact-form-7 .wpcf7-form input[type="submit"]' => 'width: {{SIZE}}{{UNIT}}',
				),
				'condition'  => array(
					'button_width_type' => 'custom',
				),
			)
		);

		$this->start_controls_tabs( 'tabs_button_style' );

		$this->start_controls_tab(
			'tab_button_normal',
			array(
				'label' => __( 'Normal', 'powerpack' ),
			)
		);

		$this->add_control(
			'button_bg_color_normal',
			array(
				'label'     => __( 'Background Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .pp-contact-form-7 .wpcf7-form input[type="submit"]' => 'background-color: {{VALUE}}',
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
					'{{WRAPPER}} .pp-contact-form-7 .wpcf7-form input[type="submit"]' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'button_border_normal',
				'label'    => __( 'Border', 'powerpack' ),
				'default'  => '1px',
				'selector' => '{{WRAPPER}} .pp-contact-form-7 .wpcf7-form input[type="submit"]',
			)
		);

		$this->add_control(
			'button_border_radius',
			array(
				'label'      => __( 'Border Radius', 'powerpack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .pp-contact-form-7 .wpcf7-form input[type="submit"]' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .pp-contact-form-7 .wpcf7-form input[type="submit"]' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'button_margin',
			array(
				'label'      => __( 'Margin Top', 'powerpack' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					),
				),
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .pp-contact-form-7 .wpcf7-form input[type="submit"]' => 'margin-top: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'button_typography',
				'label'     => __( 'Typography', 'powerpack' ),
				'scheme'    => Scheme_Typography::TYPOGRAPHY_4,
				'selector'  => '{{WRAPPER}} .pp-contact-form-7 .wpcf7-form input[type="submit"]',
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'      => 'button_box_shadow',
				'selector'  => '{{WRAPPER}} .pp-contact-form-7 .wpcf7-form input[type="submit"]',
				'separator' => 'before',
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_button_hover',
			array(
				'label' => __( 'Hover', 'powerpack' ),
			)
		);

		$this->add_control(
			'button_bg_color_hover',
			array(
				'label'     => __( 'Background Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .pp-contact-form-7 .wpcf7-form input[type="submit"]:hover' => 'background-color: {{VALUE}}',
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
					'{{WRAPPER}} .pp-contact-form-7 .wpcf7-form input[type="submit"]:hover' => 'color: {{VALUE}}',
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
					'{{WRAPPER}} .pp-contact-form-7 .wpcf7-form input[type="submit"]:hover' => 'border-color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		/**
		 * Style Tab: Errors
		 */
		$this->start_controls_section(
			'section_error_style',
			array(
				'label' => __( 'Errors', 'powerpack' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'error_messages_heading',
			array(
				'label'     => __( 'Error Messages', 'powerpack' ),
				'type'      => Controls_Manager::HEADING,
				'condition' => array(
					'error_messages' => 'show',
				),
			)
		);

		$this->start_controls_tabs( 'tabs_error_messages_style' );

		$this->start_controls_tab(
			'tab_error_messages_alert',
			array(
				'label'     => __( 'Alert', 'powerpack' ),
				'condition' => array(
					'error_messages' => 'show',
				),
			)
		);

		$this->add_control(
			'error_alert_text_color',
			array(
				'label'     => __( 'Text Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .pp-contact-form-7 .wpcf7-not-valid-tip' => 'color: {{VALUE}}',
				),
				'condition' => array(
					'error_messages' => 'show',
				),
			)
		);

		$this->add_responsive_control(
			'error_alert_spacing',
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
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .pp-contact-form-7 .wpcf7-not-valid-tip' => 'margin-top: {{SIZE}}{{UNIT}}',
				),
				'condition'  => array(
					'error_messages' => 'show',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_error_messages_fields',
			array(
				'label'     => __( 'Fields', 'powerpack' ),
				'condition' => array(
					'error_messages' => 'show',
				),
			)
		);

		$this->add_control(
			'error_field_bg_color',
			array(
				'label'     => __( 'Background Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .pp-contact-form-7 .wpcf7-not-valid' => 'background: {{VALUE}}',
				),
				'condition' => array(
					'error_messages' => 'show',
				),
			)
		);

		$this->add_control(
			'error_field_color',
			array(
				'label'     => __( 'Text Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .pp-contact-form-7 .wpcf7-not-valid' => 'color: {{VALUE}}',
				),
				'condition' => array(
					'error_messages' => 'show',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'error_field_border',
				'label'       => __( 'Border', 'powerpack' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .pp-contact-form-7 .wpcf7-not-valid',
				'separator'   => 'before',
				'condition'   => array(
					'error_messages' => 'show',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'validation_errors_heading',
			array(
				'label'     => __( 'Validation Errors', 'powerpack' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'validation_errors' => 'show',
				),
			)
		);

		$this->add_control(
			'validation_errors_bg_color',
			array(
				'label'     => __( 'Background Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .pp-contact-form-7 .wpcf7-validation-errors' => 'background: {{VALUE}}',
				),
				'condition' => array(
					'validation_errors' => 'show',
				),
			)
		);

		$this->add_control(
			'validation_errors_color',
			array(
				'label'     => __( 'Text Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .pp-contact-form-7 .wpcf7-validation-errors' => 'color: {{VALUE}}',
				),
				'condition' => array(
					'validation_errors' => 'show',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'validation_errors_typography',
				'label'     => __( 'Typography', 'powerpack' ),
				'scheme'    => Scheme_Typography::TYPOGRAPHY_4,
				'selector'  => '{{WRAPPER}} .pp-contact-form-7 .wpcf7-validation-errors',
				'separator' => 'before',
				'condition' => array(
					'validation_errors' => 'show',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'validation_errors_border',
				'label'       => __( 'Border', 'powerpack' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .pp-contact-form-7 .wpcf7-validation-errors',
				'separator'   => 'before',
				'condition'   => array(
					'validation_errors' => 'show',
				),
			)
		);

		$this->add_responsive_control(
			'validation_errors_margin',
			array(
				'label'      => __( 'Margin', 'powerpack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .pp-contact-form-7 .wpcf7-validation-errors' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					'validation_errors' => 'show',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Render contact form 7 widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings();

		$this->add_render_attribute(
			'contact-form',
			'class',
			array(
				'pp-contact-form',
				'pp-contact-form-7',
			)
		);

		if ( $settings['labels_switch'] != 'yes' ) {
			$this->add_render_attribute( 'contact-form', 'class', 'labels-hide' );
		}

		if ( $settings['placeholder_switch'] == 'yes' ) {
			$this->add_render_attribute( 'contact-form', 'class', 'placeholder-show' );
		}

		if ( $settings['custom_radio_checkbox'] == 'yes' ) {
			$this->add_render_attribute( 'contact-form', 'class', 'pp-custom-radio-checkbox' );
		}

		if ( function_exists( 'wpcf7' ) ) {
			if ( ! empty( $settings['contact_form_list'] ) ) { ?>
				<div <?php echo $this->get_render_attribute_string( 'contact-form' ); ?>>
					<?php if ( $settings['form_title'] == 'yes' || $settings['form_description'] == 'yes' ) { ?>
						<div class="pp-contact-form-7-heading">
							<?php if ( $settings['form_title'] == 'yes' && $settings['form_title_text'] != '' ) { ?>
								<h3 class="pp-contact-form-title pp-contact-form-7-title">
									<?php echo esc_attr( $settings['form_title_text'] ); ?>
								</h3>
							<?php } ?>
							<?php if ( $settings['form_description'] == 'yes' && $settings['form_description_text'] != '' ) { ?>
								<div class="pp-contact-form-description pp-contact-form-7-description">
									<?php echo $this->parse_text_editor( $settings['form_description_text'] ); ?>
								</div>
							<?php } ?>
						</div>
					<?php } ?>
					<?php echo do_shortcode( '[contact-form-7 id="' . $settings['contact_form_list'] . '" ]' ); ?>
				</div>
				<?php
			}
		}
	}

	/**
	 * Render contact form 7 widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @access protected
	 */
	protected function _content_template() {}
}
