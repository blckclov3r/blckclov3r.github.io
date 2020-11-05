<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * MAZ Loader Text Field.
 *
 * @link       https://www.feataholic.com
 * @since      1.1.8 Free
 *
 * @package    MZLDR
 * @subpackage MZLDR/admin
 */

/**
 * MAZ Loader Text Field.
 *
 * @package    MZLDR
 * @subpackage MZLDR/admin
 * @author     Your Name <email@example.com>
 */
class MZLDR_Text_Field extends MZLDR_Field {

	protected $text;

	public function __construct( $field_data = array() ) {
		$this->text           = isset( $field_data->text ) ? $field_data->text : __( 'Text', 'maz-loader' );
		$this->value          = isset( $field_data->value ) ? $field_data->value : __( 'Text', 'maz-loader' );
		$this->size           = isset( $field_data->size ) ? $field_data->size : 15;
		$this->background     = isset( $field_data->background ) ? $field_data->background : '';
		$this->color          = isset( $field_data->color ) ? $field_data->color : '';
		$this->padding_top    = isset( $field_data->padding_top ) ? $field_data->padding_top : '';
		$this->padding_right  = isset( $field_data->padding_right ) ? $field_data->padding_right : '';
		$this->padding_bottom = isset( $field_data->padding_bottom ) ? $field_data->padding_bottom : '';
		$this->padding_left   = isset( $field_data->padding_left ) ? $field_data->padding_left : '';
		$this->padding_link   = isset( $field_data->padding_link ) ? $field_data->padding_link : 'all';
		$this->padding_type   = isset( $field_data->padding_type ) ? $field_data->padding_type : 'px';
		$this->margin_top     = isset( $field_data->margin_top ) ? $field_data->margin_top : '';
		$this->margin_right   = isset( $field_data->margin_right ) ? $field_data->margin_right : '';
		$this->margin_bottom  = isset( $field_data->margin_bottom ) ? $field_data->margin_bottom : '';
		$this->margin_left    = isset( $field_data->margin_left ) ? $field_data->margin_left : '';
		$this->margin_link    = isset( $field_data->margin_link ) ? $field_data->margin_link : 'all';
		$this->margin_type    = isset( $field_data->margin_type ) ? $field_data->margin_type : 'px';
		

		parent::__construct( $field_data );
	}

	protected function getFieldSettings() {
		$settings = array(
			'text'       => array(
				'field_id'    => $this->id,
				'type'        => $this->type,
				'id'          => 'mzldr_' . $this->type . '_field_' . $this->id,
				'name'        => 'mzldr[data][' . $this->id . '][' . $this->type . ']',
				'label'       => __( 'Text', 'maz-loader' ),
				'value'       => isset( $this->text ) ? $this->text : __( 'Loading...', 'maz-loader' ),
				'placeholder' => isset( $this->text ) ? $this->text : __( 'Loading...', 'maz-loader' ),
				'bind'        => array(
					'data-bind-type'       => 'keyup',
					'data-bind-field-type' => 'text',
					'data-bind-reason'     => 'value',
				),
			),
			'size'       => array(
				'field_id'    => $this->id,
				'type'        => 'RangeSlider',
				'id'          => 'mzldr_' . $this->type . '_field_size_' . $this->id,
				'name'        => 'mzldr[data][' . $this->id . '][size]',
				'label'       => __( 'Font Size', 'maz-loader' ),
				'value'       => isset( $this->size ) ? $this->size : 15,
				'placeholder' => isset( $this->size ) ? $this->size : 15,
				'description' => __( 'Set the font size of the text.', 'maz-loader' ),
				'max_range'   => 100,
				'value'       => isset( $this->size ) ? $this->size : 15,
				'slider_type' => 'px',
				'bind'        => array(
					'data-bind-listener-override' => true,
					'data-bind-type'              => 'change',
					'data-bind-custom-event'      => true,
					'data-bind-reason'            => 'item_css_attribute_change',
					'data-bind-css-attribute'     => 'font-size',
					'data-bind-field-type'        => 'rangeslider',
					'data-bind-context'           => 'text',
					'data-bind-field-base-type'   => 'text'
				),
			),
			'heading'    => array(
				'type'    => 'heading',
				'heading' => 'h4',
				'label'   => 'Colors',
				'class'   => 'mzldr-n-m-b',
			),
			'separator'  => array(
				'type' => 'separator',
			),
			'background' => array(
				'field_id'    => $this->id,
				'type'        => 'colorpicker',
				'id'          => 'mzldr_' . $this->type . '_field_background_' . $this->id,
				'name'        => 'mzldr[data][' . $this->id . '][background]',
				'label'       => __( 'Background Color', 'maz-loader' ),
				'value'       => isset( $this->background ) ? $this->background : '',
				'placeholder' => isset( $this->background ) ? $this->background : '#dedede',
				'description' => __( 'Set the background color.', 'maz-loader' ),
				'bind'        => array(
					'data-bind-type'          => 'keyup',
					'data-bind-custom-event'  => true,
					'data-bind-reason'        => 'field_css_attribute_change',
					'data-bind-css-attribute' => 'background',
					'data-bind-field-type'    => 'color_picker',
				),
			),
			'color'      => array(
				'field_id'    => $this->id,
				'type'        => 'colorpicker',
				'id'          => 'mzldr_' . $this->type . '_field_color_' . $this->id,
				'name'        => 'mzldr[data][' . $this->id . '][color]',
				'label'       => __( 'Text Color', 'maz-loader' ),
				'value'       => isset( $this->color ) ? $this->color : '',
				'placeholder' => isset( $this->color ) ? $this->color : '#dedede',
				'description' => __( 'Set the text color.', 'maz-loader' ),
				'bind'        => array(
					'data-bind-type'          => 'keyup',
					'data-bind-custom-event'  => true,
					'data-bind-reason'        => 'field_css_attribute_change',
					'data-bind-css-attribute' => 'color',
					'data-bind-field-type'    => 'color_picker',
				),
			),
			'heading2'   => array(
				'type'    => 'heading',
				'heading' => 'h4',
				'label'   => 'Margin & Padding',
				'class'   => 'mzldr-n-m-b',
			),
			'separator2' => array(
				'type' => 'separator',
			),
			'padding'    => array(
				'field_id'       => $this->id,
				'type'           => 'MarginPadding',
				'id'             => 'mzldr_' . $this->type . '_field_padding_' . $this->id,
				'name_top'       => 'mzldr[data][' . $this->id . '][padding_top]',
				'name_right'     => 'mzldr[data][' . $this->id . '][padding_right]',
				'name_bottom'    => 'mzldr[data][' . $this->id . '][padding_bottom]',
				'name_left'      => 'mzldr[data][' . $this->id . '][padding_left]',
				'name_link'      => 'mzldr[data][' . $this->id . '][padding_link]',
				'name_type'      => 'mzldr[data][' . $this->id . '][padding_type]',
				'label'          => __( 'Padding', 'maz-loader' ),
				'item_top'       => $this->padding_top,
				'item_right'     => $this->padding_right,
				'item_bottom'    => $this->padding_bottom,
				'item_left'      => $this->padding_left,
				'item_link'      => $this->padding_link,
				'item_type'      => $this->padding_type,
				'padding_top'    => $this->padding_top,
				'padding_right'  => $this->padding_right,
				'padding_bottom' => $this->padding_bottom,
				'padding_left'   => $this->padding_left,
				'padding_link'   => $this->padding_link,
				'padding_type'   => $this->padding_type,
				'placeholder'    => '',
				'description'    => __( 'Set the padding.<br><strong>Note:</strong> Enter only numbers, the px, em or % is added automatically.<br><strong>Example:</strong> 5, 15 or 30.', 'maz-loader' ),
				'bind'           => array(
					'data-bind-type'            => 'keyup',
					'data-bind-custom-event'    => true,
					'data-bind-reason'          => 'field_css_attribute_change',
					'data-bind-css-attribute'   => 'padding',
					'data-bind-field-type'      => 'marginpadding',
					'data-bind-field-base-type' => 'text',
				),
			),
			'margin'     => array(
				'field_id'      => $this->id,
				'type'          => 'MarginPadding',
				'id'            => 'mzldr_' . $this->type . '_field_margin_' . $this->id,
				'name_top'      => 'mzldr[data][' . $this->id . '][margin_top]',
				'name_right'    => 'mzldr[data][' . $this->id . '][margin_right]',
				'name_bottom'   => 'mzldr[data][' . $this->id . '][margin_bottom]',
				'name_left'     => 'mzldr[data][' . $this->id . '][margin_left]',
				'name_link'     => 'mzldr[data][' . $this->id . '][margin_link]',
				'name_type'     => 'mzldr[data][' . $this->id . '][margin_type]',
				'label'         => __( 'Margin', 'maz-loader' ),
				'item_top'      => $this->margin_top,
				'item_right'    => $this->margin_right,
				'item_bottom'   => $this->margin_bottom,
				'item_left'     => $this->margin_left,
				'item_link'     => $this->margin_link,
				'item_type'     => $this->margin_type,
				'margin_top'    => $this->margin_top,
				'margin_right'  => $this->margin_right,
				'margin_bottom' => $this->margin_bottom,
				'margin_left'   => $this->margin_left,
				'margin_link'   => $this->margin_link,
				'margin_type'   => $this->margin_type,
				'placeholder'   => '',
				'description'   => __( 'Set the margin.<br><strong>Note:</strong> Enter only numbers, the px, em or % is added automatically.<br><strong>Example:</strong> 5, 15 or 30.', 'maz-loader' ),
				'bind'          => array(
					'data-bind-type'            => 'keyup',
					'data-bind-custom-event'    => true,
					'data-bind-reason'          => 'field_css_attribute_change',
					'data-bind-css-attribute'   => 'margin',
					'data-bind-field-type'      => 'marginpadding',
					'data-bind-field-base-type' => 'text',
				),
			),
			
			
			'animations_pro'   => array(
				'type'    => 'Pro',
				'label'   => 'Animations',
			),
			
		);

		return $settings;
	}

	public function getDefaultData( $id ) {
		$obj                 = new stdClass();
		$obj->id             = $id;
		$obj->type           = 'text';
		$obj->text           = isset( $this->text ) ? $this->text : __( 'Loading...', 'maz-loader' );
		$obj->value          = isset( $this->text ) ? $this->text : __( 'Loading...', 'maz-loader' );
		$obj->size           = isset( $this->size ) ? $this->size : __( '15', 'maz-loader' );
		$obj->background     = isset( $this->background ) ? $this->background : '';
		$obj->color          = isset( $this->color ) ? $this->color : '';
		$obj->padding_top    = isset( $this->padding_top ) ? $this->padding_top : '';
		$obj->padding_right  = isset( $this->padding_right ) ? $this->padding_right : '';
		$obj->padding_bottom = isset( $this->padding_bottom ) ? $this->padding_bottom : '';
		$obj->padding_left   = isset( $this->padding_left ) ? $this->padding_left : '';
		$obj->padding_link   = isset( $this->padding_link ) ? $this->padding_link : 'all';
		$obj->padding_type   = isset( $this->padding_type ) ? $this->padding_type : 'px';
		$obj->margin_top     = isset( $this->margin_top ) ? $this->margin_top : '';
		$obj->margin_right   = isset( $this->margin_right ) ? $this->margin_right : '';
		$obj->margin_bottom  = isset( $this->margin_bottom ) ? $this->margin_bottom : '';
		$obj->margin_left    = isset( $this->margin_left ) ? $this->margin_left : '';
		$obj->margin_link    = isset( $this->margin_link ) ? $this->margin_link : 'all';
		$obj->margin_type    = isset( $this->margin_type ) ? $this->margin_type : 'px';
		

		return $obj;
	}
}
