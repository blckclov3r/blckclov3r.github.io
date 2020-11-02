<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * MAZ Loader Field.
 *
 * @link       https://www.feataholic.com
 * @since      1.1.8 Free
 *
 * @package    MZLDR
 * @subpackage MZLDR/admin
 */

/**
 * MAZ Loader Field.
 *
 * @package    MZLDR
 * @subpackage MZLDR/admin
 * @author     Your Name <email@example.com>
 */
class MZLDR_Field {

	/**
	 * The id of the field
	 * 
	 * @var  integer
	 */
	protected $id;

	/**
	 * The type of the field
	 * 
	 * @var  string
	 */
	protected $type;

	/**
	 * The field data
	 * 
	 * @var  array
	 */
	protected $field_data;

	/**
	 * The settings of the field
	 * 
	 * @var  array
	 */
	protected $settings;

	/**
	 * Form object to render fields
	 * 
	 * @var  Class
	 */
	protected $form;

	/**
	 * Free or Pro field
	 * 
	 * @var  boolean
	 */
	protected $pro;

	/**
	 * Constructor
	 * 
	 * @return  void
	 */
	public function __construct( $field_data = array() ) {
		if ( ! count( (array) $field_data ) ) {
			return;
		}

		$this->pro = isset( $field_data->pro ) ? $field_data->pro : false;

		$this->id = $field_data->id;

		$this->type = $field_data->type;

		$this->name = isset( $field_data->_name ) ? $field_data->_name : '';

		$this->field_data = $field_data;

		// get all settings
		$this->settings = array(
			'id'   => array(
				'type'  => 'hidden',
				'name'  => 'mzldr[data][' . $this->id . '][id]',
				'class' => 'field_item_id',
				'value' => $this->id,
			),
			'type' => array(
				'type'  => 'hidden',
				'name'  => 'mzldr[data][' . $this->id . '][type]',
				'class' => 'field_item_type',
				'value' => $this->type,
			),
		);
		$field_settings = $this->getFieldSettings();
		$this->settings = array_merge( $this->settings, $field_settings );

		// init forms
		$this->form = new MZLDR_Forms();
	}

	/**
	 * Renders a field either in preview mode or live mode
	 * 
	 * @param  boolean  $preview
	 * 
	 * @return  void
	 */
	public function render( $preview ) {
		$prefix = $preview ? MZLDR_ADMIN_PATH . 'partials/loader/fields/' : MZLDR_PUBLIC_PATH . 'partials/fields/';

		include $prefix . $this->type . '.php';
	}

	/**
	 * Renders the field's settings
	 * 
	 * @param  $boolean  $edit
	 * 
	 * @return  void
	 */
	public function renderFieldSettings( $edit = false ) {
		include MZLDR_ADMIN_PATH . 'partials/forms/fields/settings/start.php';

		foreach ( $this->settings as $key => $field ) {
			$function_name = 'generate' . MZLDR_Fields_Helper::getFieldGenerateFunctionName( $field['type'] ) . 'Field';
			$this->form->$function_name( $field );
		}

		include MZLDR_ADMIN_PATH . 'partials/forms/fields/settings/end.php';
	}

}
