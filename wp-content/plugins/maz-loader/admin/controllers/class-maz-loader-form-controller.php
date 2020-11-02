<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * MAZ Loader Form Controller.
 *
 * @link       https://www.feataholic.com
 * @since      1.1.8 Free
 */
class MZLDR_Form_Controller {

	function __constructor() {  }

	/**
	 * Handles the MAZ Loader form submit
	 *
	 * @return  void  redirects when submitted successfully and prints response message
	 */
	public function maz_loader_submission_response() {
		$this->response = new MZLDR_Ajax_Response();

		if ( isset( $_POST['maz_loader_meta_nonce'] ) && check_admin_referer( 'maz_loader_meta_form_nonce', 'maz_loader_meta_nonce' ) ) {
			// sanitize the input
			$_POST = wp_unslash( $_POST );

			$submission_type   = isset( $_POST['submission_type'] ) ? sanitize_key( $_POST['submission_type'] ) : '';
			$action            = isset( $_POST['action'] ) ? sanitize_key( $_POST['action'] ) : '';
			$name              = isset( $_POST['mzldr']['loader_name'] ) ? sanitize_text_field( $_POST['mzldr']['loader_name'] ) : '';
			$fields            = isset( $_POST['mzldr']['data'] ) ? $_POST['mzldr']['data'] : '';
			$fields            = MZLDR_Helper::sanitize_fields_data( $fields );
			$loader_settings   = isset( $_POST['mzldr']['loader_settings'] ) ? $_POST['mzldr']['loader_settings'] : '';
			$loader_settings   = MZLDR_Helper::sanitize_loader_settings( $loader_settings );
			$loader_appearance = isset( $_POST['mzldr']['loader_appearance'] ) ? $_POST['mzldr']['loader_appearance'] : '';
			$loader_appearance = MZLDR_Helper::sanitize_loader_settings( $loader_appearance );
			

			if ( $submission_type == 'new' ) {

				$loader_model = new MZLDR_Loader_Model();

				$payload = array(
					'name'              => $name,
					'fields'            => $fields,
					'loader_settings'   => $loader_settings,
					'loader_appearance' => $loader_appearance,
					
				);

				$loader_id = $loader_model->save( $payload );

				if ( $loader_id ) {
					if ( empty( $fields ) ) {
						$notice = __( 'Loader has been saved but appears to be empty. Try adding some fields below.', 'maz-loader' );
						MZLDR_Admin_Notice::add_flash_notice( $notice, 'error' );
					} else {
						// add the admin notice
						$notice = __( 'Loader has been added successfully.', 'maz-loader' );
						MZLDR_Admin_Notice::add_flash_notice( $notice, 'info' );
					}
				} else {
					// add the admin notice
					$notice = __( 'Loader couldn\'t be saved.', 'maz-loader' );
					MZLDR_Admin_Notice::add_flash_notice( $notice, 'error' );
				}

				// redirect the user to the appropriate page
				MZLDR_Helper::customAdminRedirect( 'maz-loader&action=edit&loader_id=' . $loader_id );
				exit;
			} elseif ( $submission_type == 'edit' ) {
				$loader_id = isset( $_POST['mzldr']['loader_id'] ) ? sanitize_text_field( $_POST['mzldr']['loader_id'] ) : '';

				$loader_model = new MZLDR_Loader_Model();

				$payload = array(
					'loader_id'         => $loader_id,
					'name'              => $name,
					'fields'            => $fields,
					'loader_settings'   => $loader_settings,
					'loader_appearance' => $loader_appearance,
					
				);

				$loader_id = $loader_model->update( $payload );

				if ( $loader_id ) {

					if ( empty( $fields ) ) {
						$notice = __( 'Loader has been updated but appears to be empty. Try adding some fields below.', 'maz-loader' );
						MZLDR_Admin_Notice::add_flash_notice( $notice, 'error' );
					} else {
						// add the admin notice
						$notice = __( 'Loader has been updated successfully.', 'maz-loader' );
						MZLDR_Admin_Notice::add_flash_notice( $notice, 'info' );
					}
				} else {
					// add the admin notice
					$notice = __( 'Loader couldn\'t be updated.', 'maz-loader' );
					MZLDR_Admin_Notice::add_flash_notice( $notice, 'error' );
				}

				// redirect the user to the appropriate page
				MZLDR_Helper::customAdminRedirect( 'maz-loader&action=edit&loader_id=' . $loader_id );
				exit;
			}
		} else {
			$this->response->setMessage( __( 'Cannot submit loader. Error validating request', 'maz-loader' ) );
			$this->response->setError( true );
		}

		echo json_encode( $this->response->send() );
	}

	/**
	 * Handles the AJAX response of the Loader Live Preview
	 *
	 * @return  array
	 */
	public function maz_loader_preview_response() {
		if ( isset( $_POST['maz_loader_meta_nonce'] ) && check_admin_referer( 'maz_loader_meta_form_nonce', 'maz_loader_meta_nonce' ) ) {
			// sanitize input
			$_POST = wp_unslash( $_POST );
			$data  = isset( $_POST['mzldr'] ) ? $_POST['mzldr'] : array();

			if ( empty( $data ) ) {
				return;
			}

			$submission_type = isset( $_POST['submission_type'] ) ? sanitize_key( $_POST['submission_type'] ) : 'new_field';
			$field_type      = isset( $_POST['field_type'] ) ? sanitize_key( $_POST['field_type'] ) : '';

			$last_loader_field = array();

			$loader_fields     = isset( $data['data'] ) ? (object) $data['data'] : array();
			$loader_fields     = MZLDR_Helper::sanitize_fields_data( $loader_fields );
			$loader_settings   = isset( $data['loader_settings'] ) ? (object) $data['loader_settings'] : array();
			$loader_settings   = MZLDR_Helper::sanitize_loader_settings( $loader_settings );
			$loader_appearance = isset( $data['loader_appearance'] ) ? (object) $data['loader_appearance'] : array();
			$loader_appearance = MZLDR_Helper::sanitize_loader_settings( $loader_appearance );
			

			$loader_new_field_id = isset( $_POST['loader_new_field_id'] ) ? sanitize_key( $_POST['loader_new_field_id'] ) : '';

			// append new field
			if ( $submission_type == 'new_field' ) {
				$cls_field_type = implode('_', array_map('ucfirst', explode('_', $field_type)));
				$cls_field_type = ucfirst( $cls_field_type );
				$class_name     = 'MZLDR_' . $cls_field_type . '_Field';
				$base_field     = new $class_name();
				$id             = $loader_new_field_id;
				$new_field      = (object) $base_field->getDefaultData( $id );
				$obj            = new stdClass();
				$obj->$id       = $new_field;

				$loader_fields = (object) array_merge( (array) $loader_fields, (array) $obj );

				$last_loader_field = end( $loader_fields );
			} elseif ( $submission_type == 'duplicate_field' ) {
				$duplicate_field_id = isset( $_POST['duplicate_field_id'] ) ? sanitize_key( $_POST['duplicate_field_id'] ) : '';
				if ( empty( $duplicate_field_id ) ) {
					return;
				}

				// find the field we are trying to duplicate
				$item = '';
				foreach ( $loader_fields as $field ) {
					if ( $field['id'] == $duplicate_field_id ) {
						$item = $field;
						break;
					}
				}

				$item = new MZLDR_Registry($item);

				$cls_field_type = implode('_', array_map('ucfirst', explode('_', $field_type)));
				$cls_field_type = ucfirst( $cls_field_type );
				$class_name     = 'MZLDR_' . $cls_field_type . '_Field';
				$base_field     = new $class_name();
				$id             = $loader_new_field_id;

				$default_data = $base_field->getDefaultData( $id );

				// add the options that are same accross the fields
				if ( in_array( $field_type, array( 'text', 'image', 'icon' ) ) ) {
					$default_data->padding_link   = $item->get('padding_link');
					$default_data->padding_top    = $item->get('padding_top');
					$default_data->padding_bottom = $item->get('padding_bottom');
					$default_data->padding_left   = $item->get('padding_left');
					$default_data->padding_right  = $item->get('padding_right');
					$default_data->margin_link    = $item->get('margin_link');
					$default_data->margin_right   = $item->get('margin_right');
					$default_data->margin_left    = $item->get('margin_left');
					$default_data->margin_top     = $item->get('margin_top');
					$default_data->margin_bottom  = $item->get('margin_bottom');
					$default_data->background     = $item->get('background');
				}

				if ( $field_type == 'text' ) {
					$default_data->text  = $item->get('text');
					$default_data->value = $item->get('text');
					$default_data->size  = $item->get('size');
					$default_data->color = $item->get('color');
				} elseif ( $field_type == 'image' ) {
					$default_data->image         = $item->get('image');
					$default_data->custom_url    = $item->get('custom_url');
					$default_data->width         = $item->get('width');
					$default_data->height        = $item->get('height');
					$default_data->border_radius = $item->get('border_radius');
				} elseif ( $field_type == 'icon' ) {
					$default_data->icon     = $item->get('icon');
					$default_data->icon_tab = $item->get('icon_tab');
				} elseif ( $field_type == 'progress_bar' ) {
					$default_data->background     = $item->get('background');
					$default_data->color = $item->get('color');
					$default_data->show_percentage = $item->get('show_percentage');
					$default_data->bar_color = $item->get('bar_color');
					$default_data->bar_width = $item->get('bar_width');
					$default_data->bar_height = $item->get('bar_height');
				} elseif ($field_type == 'percentage_counter') {
					$default_data->text_before_counter = $item->get('text_before_counter');
					$default_data->text_after_counter = $item->get('text_after_counter');
				} elseif ( $field_type == 'custom_html' ) {
					$default_data->custom_html = $item->get('custom_html');
					$default_data->custom_css = $item->get('custom_css');
					$default_data->custom_js = $item->get('custom_js');
				}
				
				$new_field = (object) $default_data;
				$obj       = new stdClass();
				$obj->$id  = $new_field;

				$loader_fields = (object) array_merge( (array) $loader_fields, (array) $obj );

				$last_loader_field = $new_field;
			}

			$preview = true;

			$last_field_settings = array();

			// create a loader item to use on the template
			$field_data = new stdClass();
			foreach ( $loader_fields as $key => $value ) {
				$field_data->$key = $value;
			}

			$field_data_outer             = new stdClass();
			$field_data_outer->data       = $field_data;
			$field_data_outer->settings   = $loader_settings;
			$field_data_outer->appearance = $loader_appearance;
			

			$loader_data       = new stdClass();
			$loader_data->id   = 9999999;
			$loader_data->data = json_encode( $field_data_outer );

			// Create a loaders list so the template can read the preview loader
			$loaders = array(
				$loader_data,
			);

			ob_start();
			include MZLDR_PUBLIC_PATH . 'partials/loader/tmpl.php';
			$preview_html = ob_get_contents();
			ob_end_clean();

			if ( count( (array) $last_loader_field ) && $submission_type != 'delete_field' ) {
				$field_type 		   = implode('_', array_map('ucfirst', explode('_', $last_loader_field->type)));
				$last_field_class_name = 'MZLDR_' . ucfirst( $field_type ) . '_Field';
				$last_field            = new $last_field_class_name( $last_loader_field );

				ob_start();
				$last_field->renderFieldSettings();
				$last_field_settings = ob_get_contents();
				ob_end_clean();
			}

			echo json_encode(
				array(
					'preview'             => $preview_html,
					'last_field_settings' => $last_field_settings,
				)
			);
			wp_die();
		} else {
			wp_die( __( 'Loader cannot be loaded.', 'maz-loader' ), __( 'Error validating request.', 'maz-loader' ) );
		}
	}

}
