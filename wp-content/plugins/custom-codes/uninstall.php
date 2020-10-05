<?php

if( defined( 'ABSPATH') && defined('WP_UNINSTALL_PLUGIN') ) {

	// STORE FILES?
	$cstm_cds_store_custom_files = get_option( 'cstm_cds_store_files', '' ) == "yes" ? true : false;

	// Delete all the files if requested
	function deleteDirectory($dir) {
	    if (!file_exists($dir)) {
	        return true;
	    }

	    if (!is_dir($dir)) {
	        return unlink($dir);
	    }

	    foreach (scandir($dir) as $item) {
	        if ($item == '.' || $item == '..') {
	            continue;
	        }

	        if (!deleteDirectory($dir . DIRECTORY_SEPARATOR . $item)) {
	            return false;
	        }

	    }

	    return rmdir($dir);
	}
	if (!$cstm_cds_store_custom_files) deleteDirectory(WP_CONTENT_DIR .'/custom_codes');



	//Remove the plugin's settings
	if ( get_option( 'cstm_cds_permission_roles' ) ) delete_option( 'cstm_cds_permission_roles' );

	if ( get_option( 'cstm_cds_admin_roles' ) ) delete_option( 'cstm_cds_admin_roles' );

	if ( get_option( 'cstm_cds_admin_notes' ) ) delete_option( 'cstm_cds_admin_notes' );

	if ( get_option( 'cstm_cds_style_mode' ) ) delete_option( 'cstm_cds_style_mode' );
	if ( get_option( 'cstm_cds_store_files' ) ) delete_option( 'cstm_cds_store_files' );

	if ( get_option( 'cstm_cds_tablet_l' ) ) delete_option( 'cstm_cds_tablet_l' );
	if ( get_option( 'cstm_cds_tablet_p' ) ) delete_option( 'cstm_cds_tablet_p' );
	if ( get_option( 'cstm_cds_phone_l' ) ) delete_option( 'cstm_cds_phone_l' );
	if ( get_option( 'cstm_cds_phone_p' ) ) delete_option( 'cstm_cds_phone_p' );

	if ( get_option( 'cstm_cds_editor_theme' ) ) delete_option( 'cstm_cds_editor_theme' );
	if ( get_option( 'cstm_cds_css_save_count' ) ) delete_option( 'cstm_cds_css_save_count' );
	if ( get_option( 'cstm_cds_js_head_save_count' ) ) delete_option( 'cstm_cds_js_head_save_count' );
	if ( get_option( 'cstm_cds_js_bottom_save_count' ) ) delete_option( 'cstm_cds_js_bottom_save_count' );

	if ( get_option( 'cstm_cds_admin_css_save_count' ) ) delete_option( 'cstm_cds_admin_css_save_count' );
	if ( get_option( 'cstm_cds_admin_js_head_save_count' ) ) delete_option( 'cstm_cds_admin_js_head_save_count' );
	if ( get_option( 'cstm_cds_admin_js_bottom_save_count' ) ) delete_option( 'cstm_cds_admin_js_bottom_save_count' );



	// Remove the capability
	foreach ( $wp_roles->roles as $role_name => $role_details ) {

		$role = get_role( $role_name );
		$role->remove_cap( 'cstm_cds_full_access' );

	}


	// Remove the new role
	remove_role( 'cstm_cds_admin' );

}

?>