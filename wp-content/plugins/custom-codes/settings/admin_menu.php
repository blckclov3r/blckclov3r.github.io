<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );


// ADMIN MENU
function cstm_cds_admin_menu() {
    global $cstm_cds_page, $cstm_cds_sass;


	    $cstm_cds_page_main = add_menu_page (
	        'Custom Codes',
	        'Custom Codes',
	        'cstm_cds_full_access',
	        'custom-codes',
	        'cstm_cds_editor_page',
	        plugin_dir_url( CSTM_CDS_FILE ).'lib/images/cstm_cds_dev_icon.png',
	        '500'
	    );

	    	$cstm_cds_page = add_submenu_page(
		    	'custom-codes',
		    	'Custom '.($cstm_cds_sass ? 'SASS' : 'CSS').' & JS',
		    	'Public Side Codes',
		    	'cstm_cds_full_access',
		    	'custom-codes',
		    	'cstm_cds_editor_page'
		    );
		    add_action( 'load-' . $cstm_cds_page, 'cstm_cds_settings' );


			$cstm_cds_admin_page = add_submenu_page(
				'custom-codes',									// admin page slug
				'Custom '.($cstm_cds_sass ? 'SASS' : 'CSS').' & JS',  // page title
				'Admin Side Codes', 							// menu title
				'cstm_cds_full_access',               					// capability required to see the page
				'custom-codes&admin_panel=true',           		// admin page slug, e.g. options-general.php?page=cstm_cds_options
				'cstm_cds_editor_page'             					// callback function to display the options page
			);


}
add_action( 'admin_menu', 'cstm_cds_admin_menu' );



// ADMIN BAR MENU
function cstm_cds_wp_toolbar( $wp_admin_bar ) {
	global $cstm_cds_sass;

	if ( current_user_can('cstm_cds_full_access') ) {

		$args = array(
			'id'    => 'cstm_cds_toolbar_custom_codes',
			'title' => '<span class="ab-icon"><img src="'.plugin_dir_url( CSTM_CDS_FILE ).'lib/images/cstm_cds_dev_icon.png"></span>
						<span class="ab-label">Custom Codes</span>',
			'href'  => admin_url('admin.php?page=custom-codes'),
			'meta'  => array( 'class' => 'cc-toolbar-custom-codes' )
		);
		$wp_admin_bar->add_node( $args );

			$args = array(
				'id'		=>	'cstm_cds_toolbar_custom_codes_public',
				'title'		=>	'Public Side Codes',
				'href'		=>	admin_url('admin.php?page=custom-codes'),
				'parent'	=>	'cstm_cds_toolbar_custom_codes',
			);
			if ( current_user_can('cstm_cds_full_access') ) $wp_admin_bar->add_node($args);

			$args = array(
				'id'		=>	'cstm_cds_toolbar_custom_codes_admin',
				'title'		=>	'Admin Side Codes',
				'href'		=>	admin_url('admin.php?page=custom-codes&admin_panel=true'),
				'parent'	=>	'cstm_cds_toolbar_custom_codes',
			);
			if ( current_user_can('cstm_cds_full_access') ) $wp_admin_bar->add_node($args);

	}

}
add_action( 'admin_bar_menu', 'cstm_cds_wp_toolbar', 9999 );

// Toolbar Style
function cstm_cds_toolbar_custom_codes_style() {

	if ( current_user_can('cstm_cds_full_access') ) {

		echo "
			<style type='text/css'>

				#wp-admin-bar-cstm_cds_toolbar_custom_codes .ab-item .ab-icon {
					-webkit-filter: grayscale(80%);
					filter: grayscale(80%);
				}
				#wp-admin-bar-cstm_cds_toolbar_custom_codes .ab-item:hover .ab-icon {
					-webkit-filter: grayscale(0%);
					filter: grayscale(0%);
				}

			</style>
		";

	}
}
add_action( 'wp_head', 'cstm_cds_toolbar_custom_codes_style' );
add_action( 'admin_head', 'cstm_cds_toolbar_custom_codes_style' );


?>