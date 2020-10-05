<?php

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );


// CREATE AND GIVE PERMISSION TO THE CUSTOM CODES DIRECTORY
function cstm_cds_plugin_activate() {

	// Create the folder
	if (!file_exists(CSTM_CDS_DIR))
		mkdir(CSTM_CDS_DIR, 0755, true);

	// Give permission
	@chmod(CSTM_CDS_DIR, 0755);

}
register_activation_hook( CSTM_CDS_FILE, 'cstm_cds_plugin_activate' );



// ADD THE CUSTOM CODES ADMIN ROLE AND CAPABILITIES WHEN INSTALL
if( !get_role( 'cstm_cds_admin' ) ) {

	$caps['cstm_cds_full_access'] = true;

	// Add the new role
	add_role('cstm_cds_admin', 'Custom Codes Admin', $caps);

}



// Add the new role to current user
function cstm_cds_add_role_to_current_user() {

	$cstm_cds_admins = get_users(['role' => 'cstm_cds_admin']);

	// If no one is cstm_cds_admin and I'm the administrator, make me the cstm_cds_admin
	if ( count($cstm_cds_admins) == 0 && !current_user_can('cstm_cds_admin') && current_user_can('administrator') ) {

		// ASSIGN CURRENT USER THE ROLE
		$u = wp_get_current_user();
		$u->add_role( 'cstm_cds_admin' );

	} elseif ( current_user_can('cstm_cds_admin') && !current_user_can('administrator') ) {

		// ASSIGN CURRENT USER THE ADMIN ROLE
		$u = wp_get_current_user();
		$u->add_role( 'administrator' );

	}

}
add_action( 'admin_init', 'cstm_cds_add_role_to_current_user' );



// Old Version Role Corrections
function cstm_cds_remove_old_roles() {
	global $wp_roles;


	// Remove old caps
	$delete_caps = array('cc_full_access', 'cc_admin');
	foreach ($delete_caps as $cap) {
		foreach (array_keys($wp_roles->roles) as $role) {
			$wp_roles->remove_cap($role, $cap);
		}
	}

	// Remove old role
	$existing_roles = $wp_roles->get_names(); //print_r($existing_roles);
	if ( isset($existing_roles['cc_admin']) && $existing_roles['cc_admin'] == "Custom Codes Admin" )
		$wp_roles->remove_role("cc_admin");


}
add_action( 'admin_init', 'cstm_cds_remove_old_roles' );



// Old Version Settings Corrections
function cstm_cds_remove_old_settings() {


	$all_options = wp_load_alloptions();
	foreach (array_keys($all_options) as $option) {

		if ( substr($option, 0, 3) == "cc_" ) {

			$option_name = substr($option, 3);
			$option_value = get_option( $option );


			if (
				$option_name != "permission_roles" &&
				$option_name != "admin_roles" &&
				$option_name != "admin_notes" &&
				$option_name != "style_mode" &&
				$option_name != "store_files" &&
				$option_name != "tablet_l" &&
				$option_name != "tablet_p" &&
				$option_name != "phone_l" &&
				$option_name != "phone_p" &&
				$option_name != "editor_theme" &&
				$option_name != "css_save_count" &&
				$option_name != "js_head_save_count" &&
				$option_name != "js_bottom_save_count" &&
				$option_name != "admin_css_save_count" &&
				$option_name != "admin_js_head_save_count" &&
				$option_name != "admin_js_bottom_save_count"
			)
				continue;


			// If new option doesn't exist, create one
			update_option("cstm_cds_".$option_name, $option_value);

			// Delete the old one
			delete_option( $option );

		}

	}

}
add_action( 'admin_init', 'cstm_cds_remove_old_settings' );