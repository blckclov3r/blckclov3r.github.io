<?php

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );


// PERMISSION UPDATES
function cstm_cds_update_permissions() {
	global $wp_roles;


	// Call the settings
	$cstm_cds_permission_roles = cstm_cds_pull_option(
		'cstm_cds_permission_roles',
		array('cstm_cds_admin', 'administrator') // Default settings
	);


	// Check all the roles
	foreach ( $wp_roles->roles as $role_name => $role_details) {

			$role = get_role( $role_name );
			if ( in_array($role_name, $cstm_cds_permission_roles) || $role_name == 'cstm_cds_admin' )
				$role->add_cap( 'cstm_cds_full_access' );

			else
				$role->remove_cap( 'cstm_cds_full_access' );

	}


}
add_action('admin_init', 'cstm_cds_update_permissions');