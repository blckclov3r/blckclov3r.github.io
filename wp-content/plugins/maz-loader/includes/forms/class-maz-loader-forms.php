<?php

/**
 * Forms Helper
 *
 * @link       https://www.feataholic.com
 * @since      1.1.8 Free
 *
 * @package    MZLDR
 * @subpackage MZLDR/includes/forms
 */

/**
 * Forms Helper.
 *
 * Provides helper methods to run all form fields.
 *
 * @since      1.1.8 Free
 * @package    MZLDR
 * @subpackage MZLDR/includes/forms
 * @author     Your Name <email@example.com>
 */
class MZLDR_Forms {

	/**
	 * @var  array  $field_data
	 */
	private $field_data = array();

	/**
	 * Generates the start of the form field wrapper
	 *
	 * @return void
	 */
	public function generateFormFieldStart() {
		include plugin_dir_path( dirname( __DIR__ ) ) . 'admin/partials/forms/fields/wrapper/start.php';
	}

	/**
	 * Generates the end of the form field wrapper
	 *
	 * @return void
	 */
	public function generateFormFieldEnd() {
		include plugin_dir_path( dirname( __DIR__ ) ) . 'admin/partials/forms/fields/wrapper/end.php';

		$this->set_field_data( array() );
	}

	/**
	 * Generates the nonce hidden field
	 *
	 * @return void
	 */
	public function generateNonceField() {
		include plugin_dir_path( dirname( __DIR__ ) ) . 'admin/partials/forms/fields/nonce.php';
	}

	/**
	 * Generates a Hidden field
	 *
	 * @param  array $field_data
	 *
	 * @return void
	 */
	public function generateHiddenField( $field_data ) {
		$this->set_field_data( $field_data );

		include plugin_dir_path( dirname( __DIR__ ) ) . 'admin/partials/forms/fields/hidden.php';
	}

	/**
	 * Generates an Text field
	 *
	 * @param  array $field_data
	 *
	 * @return void
	 */
	public function generateTextField( $field_data = array() ) {
		$this->set_field_data( $field_data );

		$this->generateFormFieldStart();
		include plugin_dir_path( dirname( __DIR__ ) ) . 'admin/partials/forms/fields/text.php';
		$this->generateFormFieldEnd();
	}

	/**
	 * Generates an Textarea field
	 *
	 * @param  array $field_data
	 *
	 * @return void
	 */
	public function generateTextareaField( $field_data = array() ) {
		$this->set_field_data( $field_data );

		$this->generateFormFieldStart();
		include plugin_dir_path( dirname( __DIR__ ) ) . 'admin/partials/forms/fields/textarea.php';
		$this->generateFormFieldEnd();
	}

	/**
	 * Generates an Separator field
	 *
	 * @return void
	 */
	public function generateSeparatorField() {
		include plugin_dir_path( dirname( __DIR__ ) ) . 'admin/partials/forms/fields/separator.php';
	}

	/**
	 * Generates an Heading field
	 *
	 * @return void
	 */
	public function generateHeadingField( $field_data = array() ) {
		$this->set_field_data( $field_data );
		include plugin_dir_path( dirname( __DIR__ ) ) . 'admin/partials/forms/fields/heading.php';
	}

	/**
	 * Generates an Image field
	 *
	 * @param  array $field_data
	 *
	 * @return void
	 */
	public function generateImageField( $field_data = array() ) {
		$this->set_field_data( $field_data );

		$this->generateFormFieldStart();
		include plugin_dir_path( dirname( __DIR__ ) ) . 'admin/partials/forms/fields/image.php';
		$this->generateFormFieldEnd();
	}

	/**
	 * Generates an Animation Field that uses animate.css
	 *
	 * @param  array $field_data
	 *
	 * @return void
	 */
	public function generateAnimationField( $field_data = array() ) {
		$this->set_field_data( $field_data );

		$this->generateFormFieldStart();
		include plugin_dir_path( dirname( __DIR__ ) ) . 'admin/partials/forms/fields/animation.php';
		$this->generateFormFieldEnd();
	}
	/**
	 * Generates a `Upgrade to Pro` field for settings that are Pro-only
	 *
	 * @param  array $field_data
	 *
	 * @return void
	 */
	public function generateProField( $field_data = array() ) {
		$this->set_field_data( $field_data );

		$this->generateFormFieldStart();
		include plugin_dir_path( dirname( __DIR__ ) ) . 'admin/partials/pro/forms/fields/pro.php';
		$this->generateFormFieldEnd();
	}

	/**
	 * Generates an Icon field
	 *
	 * @param  array $field_data
	 *
	 * @return void
	 */
	public function generateIconField( $field_data = array() ) {
		$this->set_field_data( $field_data );

		$this->generateFormFieldStart();
		include plugin_dir_path( dirname( __DIR__ ) ) . 'admin/partials/forms/fields/icon.php';
		$this->generateFormFieldEnd();
	}

	public function generatePercentageCounterField ($field_data = array() ) {
		$this->set_field_data( $field_data );

		$this->generateFormFieldStart();
		include plugin_dir_path( dirname( __DIR__ ) ) . 'admin/partials/forms/fields/percentage_counter.php';
		$this->generateFormFieldEnd();
	}
	
	public function generateProgressBarField ($field_data = array() ) {
		$this->set_field_data( $field_data );

		$this->generateFormFieldStart();
		include plugin_dir_path( dirname( __DIR__ ) ) . 'admin/partials/forms/fields/progress_bar.php';
		$this->generateFormFieldEnd();
	}

	/**
	 * Generates the Submit Button
	 *
	 * @return void
	 */
	public function generateSubmitButton() {
		$field_data = array(
			'control_group_class' => ' submit-field',
			'hide_label' => true
		);
		$this->set_field_data( $field_data );

		$this->generateFormFieldStart();
		include plugin_dir_path( dirname( __DIR__ ) ) . 'admin/partials/forms/fields/submit_button.php';
		$this->generateFormFieldEnd();
	}

	public function generateDeviceControlField( $field_data = array() ) {
		$this->set_field_data( $field_data );

		$this->generateFormFieldStart();
		include plugin_dir_path( dirname( __DIR__ ) ) . 'admin/partials/forms/fields/device_control.php';
		$this->generateFormFieldEnd();
	}

	public function generatePublishingRulesField( $field_data = array() ) {
		$this->set_field_data( $field_data );

		$this->generateFormFieldStart();
		include plugin_dir_path( dirname( __DIR__ ) ) . 'admin/partials/forms/fields/publishing_rules.php';
		$this->generateFormFieldEnd();
	}

	public function generateColorPickerField( $field_data = array() ) {
		$this->set_field_data( $field_data );

		$this->generateFormFieldStart();
		include plugin_dir_path( dirname( __DIR__ ) ) . 'admin/partials/forms/fields/colorpicker.php';
		$this->generateFormFieldEnd();
	}

	public function generateDropDownField( $field_data = array() ) {
		$this->set_field_data( $field_data );

		$this->generateFormFieldStart();
		include plugin_dir_path( dirname( __DIR__ ) ) . 'admin/partials/forms/fields/dropdown.php';
		$this->generateFormFieldEnd();
	}

	public function generateToggleField( $field_data = array() ) {
		$this->set_field_data( $field_data );

		$this->generateFormFieldStart();
		include plugin_dir_path( dirname( __DIR__ ) ) . 'admin/partials/forms/fields/toggle.php';
		$this->generateFormFieldEnd();
	}

	public function generateRangeSliderField( $field_data = array() ) {
		$this->set_field_data( $field_data );

		$this->generateFormFieldStart();
		include plugin_dir_path( dirname( __DIR__ ) ) . 'admin/partials/forms/fields/range_slider.php';
		$this->generateFormFieldEnd();
	}

	public function generateMarginPaddingField( $field_data = array() ) {
		$this->set_field_data( $field_data );

		$this->generateFormFieldStart();
		include plugin_dir_path( dirname( __DIR__ ) ) . 'admin/partials/forms/fields/margin_padding.php';
		$this->generateFormFieldEnd();
	}

	/**
	 * Sets the field's data
	 *
	 * @param   array  $field_data
	 *
	 * @return  void
	 */
	private function set_field_data( $field_data ) {
		$this->field_data = $field_data;
	}

	/**
	 * Returns the field's data
	 *
	 * @return  mixed
	 */
	public function get_field_data( $key ) {
		return isset( $this->field_data[ $key ] ) ? $this->field_data[ $key ] : '';
	}

}
