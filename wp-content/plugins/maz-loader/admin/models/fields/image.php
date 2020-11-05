<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * MAZ Loader Image Field.
 *
 * @link       https://www.feataholic.com
 * @since      1.1.8 Free
 *
 * @package    MZLDR
 * @subpackage MZLDR/admin
 */

/**
 * MAZ Loader Image Field.
 *
 * @package    MZLDR
 * @subpackage MZLDR/admin
 * @author     Your Name <email@example.com>
 */
class MZLDR_Image_Field extends MZLDR_Field {

	protected $image;
	protected $width;
	protected $height;

	public function __construct( $field_data = array() ) {
		$this->image          = isset( $field_data->image ) && ! empty( $field_data->image ) ? $field_data->image : MZLDR_ADMIN_MEDIA_URL . 'img/placeholder.jpg';
		$this->custom_url     = isset( $field_data->custom_url ) ? $field_data->custom_url : '';
		$this->width          = isset( $field_data->width ) ? $field_data->width : '300px';
		$this->height         = isset( $field_data->height ) ? $field_data->height : '';
		$this->border_radius  = isset( $field_data->border_radius ) ? $field_data->border_radius : 0;
		$this->background     = isset( $field_data->background ) ? $field_data->background : '';
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
			'image'         => array(
				'field_id'      => $this->id,
				'type'          => $this->type,
				'id'            => 'mzldr_' . $this->type . '_field_' . $this->id,
				'name'          => 'mzldr[data][' . $this->id . '][' . $this->type . ']',
				'label'         => __( 'Image', 'maz-loader' ),
				'image'         => $this->image,
				'custom_url'    => $this->custom_url,
				'default_image' => MZLDR_ADMIN_MEDIA_URL . 'img/placeholder.jpg',
				'bind'          => array(
					'data-bind-type'         => 'loader_field_image_src_change',
					'data-bind-custom-event' => true,
					'data-bind-reason'       => 'default',
					'data-bind-field-type'   => 'image',
				),
			),
			'custom_url'    => array(
				'field_id'      => $this->id,
				'type'          => 'text',
				'id'            => 'mzldr_' . $this->type . '_field_custom_url_' . $this->id,
				'name'          => 'mzldr[data][' . $this->id . '][custom_url]',
				'label'         => __( 'Custom Image URL', 'maz-loader' ),
				'value'         => isset( $this->custom_url ) ? $this->custom_url : MZLDR_ADMIN_MEDIA_URL . 'img/placeholder.jpg',
				'placeholder'   => isset( $this->custom_url ) ? $this->custom_url : MZLDR_ADMIN_MEDIA_URL . 'img/placeholder.jpg',
				'default_image' => MZLDR_ADMIN_MEDIA_URL . 'img/placeholder.jpg',
				'description'   => __( 'Enter a custom URL for the image. This will override the image selected above.', 'maz-loader' ),
				'bind'          => array(
					'data-bind-type'         => 'keyup',
					'data-bind-custom-event' => true,
					'data-bind-reason'       => 'custom_url',
					'data-bind-field-type'   => 'image',
					'data-bind-context'      => 'field_img',
				),
			),
			'heading'       => array(
				'type'    => 'heading',
				'heading' => 'h4',
				'label'   => 'Image Width & Height',
				'class'   => 'mzldr-n-m-b',
			),
			'separator'     => array(
				'type' => 'separator',
			),
			'width'         => array(
				'field_id'    => $this->id,
				'type'        => 'text',
				'id'          => 'mzldr_' . $this->type . '_field_width_' . $this->id,
				'name'        => 'mzldr[data][' . $this->id . '][width]',
				'label'       => __( 'Width', 'maz-loader' ),
				'value'       => isset( $this->width ) ? $this->width : '300px',
				'placeholder' => isset( $this->width ) ? $this->width : '300px',
				'description' => __( 'Enter the width of the image', 'maz-loader' ),
				'bind'        => array(
					'data-bind-type'          => 'keyup',
					'data-bind-custom-event'  => true,
					'data-bind-reason'        => 'image_css_attribute_change',
					'data-bind-css-attribute' => 'width',
					'data-bind-field-type'    => 'image',
				),
			),
			'height'        => array(
				'field_id'    => $this->id,
				'type'        => 'text',
				'id'          => 'mzldr_' . $this->type . '_field_height_' . $this->id,
				'name'        => 'mzldr[data][' . $this->id . '][height]',
				'label'       => __( 'Height', 'maz-loader' ),
				'value'       => isset( $this->height ) ? $this->height : '',
				'placeholder' => isset( $this->height ) ? $this->height : '300px',
				'description' => __( 'Enter the height of the image', 'maz-loader' ),
				'bind'        => array(
					'data-bind-type'          => 'keyup',
					'data-bind-custom-event'  => true,
					'data-bind-reason'        => 'image_css_attribute_change',
					'data-bind-css-attribute' => 'height',
					'data-bind-field-type'    => 'image',
				),
			),
			'heading2'      => array(
				'type'    => 'heading',
				'heading' => 'h4',
				'label'   => 'Color & Radius',
				'class'   => 'mzldr-n-m-b',
			),
			'separator2'    => array(
				'type' => 'separator',
			),
			'background'    => array(
				'field_id'    => $this->id,
				'type'        => 'colorpicker',
				'id'          => 'mzldr_' . $this->type . '_field_background_' . $this->id,
				'name'        => 'mzldr[data][' . $this->id . '][background]',
				'label'       => __( 'Background Color', 'maz-loader' ),
				'value'       => isset( $this->background ) ? $this->background : '',
				'placeholder' => isset( $this->background ) ? $this->background : '#dedede',
				'description' => __( 'Set the background color', 'maz-loader' ),
				'background'  => $this->background,
				'bind'        => array(
					'data-bind-type'          => 'keyup',
					'data-bind-custom-event'  => true,
					'data-bind-reason'        => 'field_css_attribute_change',
					'data-bind-css-attribute' => 'background',
					'data-bind-field-type'    => 'color_picker',
					'data-bind-context'       => 'image',
				),
			),
			'border_radius' => array(
				'field_id'    => $this->id,
				'type'        => 'RangeSlider',
				'id'          => 'mzldr_' . $this->type . '_field_border_radius_' . $this->id,
				'name'        => 'mzldr[data][' . $this->id . '][border_radius]',
				'label'       => __( 'Border Radius', 'maz-loader' ),
				'value'       => isset( $this->border_radius ) ? $this->border_radius : 0,
				'placeholder' => isset( $this->border_radius ) ? $this->border_radius : 0,
				'description' => __( 'Set the border radius of the image.', 'maz-loader' ),
				'max_range'   => 100,
				'value'       => isset( $this->border_radius ) ? $this->border_radius : 0,
				'slider_type' => '%',
				'bind'        => array(
					'data-bind-listener-override' => true,
					'data-bind-type'              => 'change',
					'data-bind-custom-event'      => true,
					'data-bind-reason'            => 'item_css_attribute_change',
					'data-bind-css-attribute'     => 'border-radius',
					'data-bind-field-type'        => 'rangeslider',
					'data-bind-context'           => 'image',
					'data-bind-field-base-type'   => 'image'
				),
			),
			'heading3'      => array(
				'type'    => 'heading',
				'heading' => 'h4',
				'label'   => 'Margin & Padding',
				'class'   => 'mzldr-n-m-b',
			),
			'separator3'    => array(
				'type' => 'separator',
			),
			'padding'       => array(
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
					'data-bind-field-base-type' => 'image',
				),
			),
			'margin'        => array(
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
					'data-bind-field-base-type' => 'image',
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
		$obj->type           = 'image';
		$obj->image          = isset( $this->image ) ? $this->image : MZLDR_ADMIN_MEDIA_URL . 'img/placeholder.jpg';
		$obj->custom_url     = isset( $this->custom_url ) ? $this->custom_url : '';
		$obj->width          = isset( $this->width ) ? $this->width : '300px';
		$obj->height         = isset( $this->height ) ? $this->height : '';
		$obj->border_radius  = isset( $this->border_radius ) ? $this->border_radius : 0;
		$obj->background     = isset( $this->background ) ? $this->background : 0;
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
