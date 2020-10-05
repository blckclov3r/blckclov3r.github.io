<?php

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );


// Public/Admin Side?
$custom_codes_public = $cstm_cds_admin = false;
if ( isset($_GET['admin_panel']) && $_GET['admin_panel'] == true ) {
	$cstm_cds_admin = true;
} else {
	$custom_codes_public = true;
}
$cstm_cds_result_level = $custom_codes_public ? "Public" : "Admin";



// REGISTER SETTINGS
function cstm_cds_register_custom_codes_settings() {

	register_setting( 'cstm_cds_permission_settings' , 'cstm_cds_permission_roles' );

	register_setting( 'cstm_cds_admin_settings' , 'cstm_cds_admin_roles' );

	register_setting( 'cstm_cds_notes_settings' , 'cstm_cds_admin_notes' );

	register_setting( 'cstm_cds_general_settings' , 'cstm_cds_style_mode' );
	register_setting( 'cstm_cds_general_settings' , 'cstm_cds_store_files' );

	register_setting( 'cstm_cds_responsivity_settings' , 'cstm_cds_tablet_l' );
	register_setting( 'cstm_cds_responsivity_settings' , 'cstm_cds_tablet_p' );
	register_setting( 'cstm_cds_responsivity_settings' , 'cstm_cds_phone_l' );
	register_setting( 'cstm_cds_responsivity_settings' , 'cstm_cds_phone_p' );

	register_setting( 'cstm_cds_editor_settings' , 'cstm_cds_editor_theme' );
	register_setting( 'cstm_cds_editor_settings' , 'cstm_cds_css_save_count' );
	register_setting( 'cstm_cds_editor_settings' , 'cstm_cds_js_head_save_count' );
	register_setting( 'cstm_cds_editor_settings' , 'cstm_cds_js_bottom_save_count' );

	register_setting( 'cstm_cds_editor_settings' , 'cstm_cds_admin_css_save_count' );
	register_setting( 'cstm_cds_editor_settings' , 'cstm_cds_admin_js_head_save_count' );
	register_setting( 'cstm_cds_editor_settings' , 'cstm_cds_admin_js_bottom_save_count' );

}
add_action( 'admin_init', 'cstm_cds_register_custom_codes_settings' );




// Calls
$cstm_cds_style_mode = cstm_cds_pull_option( 'cstm_cds_style_mode', 'sass' );
$cstm_cds_sass = $cstm_cds_style_mode == "sass" ? true : false;


if (
	( defined( 'DOING_AJAX' ) && DOING_AJAX ) ||
	( isset($_GET['page']) && $_GET['page'] == "custom-codes" )
) {

	$cstm_cds_permission_roles = cstm_cds_pull_option( 'cstm_cds_permission_roles', array('cstm_cds_admin', 'administrator') );
	$cstm_cds_admin_roles = cstm_cds_pull_option( 'cstm_cds_admin_roles', array() );
	$cstm_cds_admin_notes = cstm_cds_pull_option( 'cstm_cds_admin_notes', '' );
	$cstm_cds_store_custom_files = cstm_cds_pull_option( 'cstm_cds_store_files', '' );
	$cstm_cds_tablet_l = cstm_cds_pull_option( 'cstm_cds_tablet_l', '1199' );
	$cstm_cds_tablet_p = cstm_cds_pull_option( 'cstm_cds_tablet_p', '991' );
	$cstm_cds_phone_l = cstm_cds_pull_option( 'cstm_cds_phone_l', '767' );
	$cstm_cds_phone_p = cstm_cds_pull_option( 'cstm_cds_phone_p', '479' );
	$cstm_cds_editor_theme = cstm_cds_pull_option( 'cstm_cds_editor_theme', 'dark' );

}


if ( isset($_GET['page']) && $_GET['page'] == "custom-codes" ) {


	// SETTINGS TAB
	function cstm_cds_settings() {
		global $cstm_cds_admin;

	    $screen = get_current_screen();

		if ($cstm_cds_admin) {
		    $screen -> add_help_tab( array(
		        'id'      => 'custom-codes-admin-permissions', // This should be unique for the screen.
		        'title'   => 'Admin Side Code Permissions',
		        'callback' => 'cstm_cds_admin_permissions_tab'
		    ) );
	    }

	    $screen -> add_help_tab( array(
	        'id'      => 'custom-codes-admin-notes', // This should be unique for the screen.
	        'title'   => 'Admin Notes',
	        'callback' => 'cstm_cds_admin_notes_tab'
	    ) );

	    $screen->add_help_tab( array(
	        'id'      => 'custom-codes-editor-settings', // This should be unique for the screen.
	        'title'   => 'Editor Settings',
	        'callback' => 'cstm_cds_editor_settings_tab'
	        // Use 'callback' instead of 'content' for a function callback that renders the tab content.
	    ) );

	    $screen->add_help_tab( array(
	        'id'      => 'custom-codes-general-settings', // This should be unique for the screen.
	        'title'   => 'General Settings',
	        'callback' => 'cstm_cds_general_settings_tab'
	    ) );

	    $screen -> add_help_tab( array(
	        'id'      => 'custom-codes-responsivity-settings', // This should be unique for the screen.
	        'title'   => 'Responsivity Settings',
	        'callback' => 'cstm_cds_responsivity_settings_tab'
	    ) );

	    $screen -> add_help_tab( array(
	        'id'      => 'custom-codes-permission-settings', // This should be unique for the screen.
	        'title'   => 'Permission Settings',
	        'callback' => 'cstm_cds_permissions_tab'
	    ) );

	}


	// EDITOR SETTINGS
	function cstm_cds_editor_settings_tab() {
		global $cstm_cds_editor_theme;
		?>

		<form method="post" action="options.php" id="custom-codes-editor-settings-form" enctype="multipart/form-data">
			<?php settings_fields( 'cstm_cds_editor_settings' ); ?>
			<?php do_settings_sections( 'cstm_cds_editor_settings' ); ?>

			<p>
				<b>Editor Theme:</b><br>
				<label><input class="es-inputs" type="radio" name="cstm_cds_editor_theme" value="dark" <?=$cstm_cds_editor_theme == "dark" ? "checked" : ""?>> Dark</label>
				<label><input class="es-inputs" type="radio" name="cstm_cds_editor_theme" value="light" <?=$cstm_cds_editor_theme == "light" ? "checked" : ""?>> Light</label>
			</p>


			<input id="custom-codes-editor-settings-saver" value="Save" type="submit" class="button-primary">
		</form>

		<script>
			jQuery(document).ready(function($){

				// Settings Link
				$('#contextual-help-link').text('Notes / Settings');

			});
		</script>

		<?php
	}


	// GENERAL SETTINGS
	function cstm_cds_general_settings_tab() {
		global $cstm_cds_style_mode, $cstm_cds_store_custom_files;
		?>

		<form method="post" action="options.php" id="custom-codes-general-settings-form" enctype="multipart/form-data">
			<?php settings_fields( 'cstm_cds_general_settings' ); ?>
			<?php do_settings_sections( 'cstm_cds_general_settings' ); ?>

			<p>
				<b>Style Mode:</b><br>
				<label><input class="gs-inputs" type="radio" name="cstm_cds_style_mode" value="sass" <?=$cstm_cds_style_mode == "sass" ? "checked" : ""?>> SASS (Recommended, but slower)</label>
				<label><input class="gs-inputs" type="radio" name="cstm_cds_style_mode" value="css" <?=$cstm_cds_style_mode == "css" ? "checked" : ""?>> CSS (Faster, but less feature)</label>
			</p>

			<p>
				<b>Store custom CSS/JS after uninstall:</b><br>
				<label><input class="gs-inputs" type="checkbox" name="cstm_cds_store_files" value="yes" <?=$cstm_cds_store_custom_files == 'yes' ? "checked" : ""?>> Yes, please</label>
			</p><br>




			<input id="custom-codes-general-settings-saver" value="Save" type="submit" class="button-primary">
		</form>

		<script>
			jQuery(document).ready(function($){

				var button_gs = $('#custom-codes-general-settings-saver');

				$('.gs-inputs').on('change', function() {

					if ( !button_gs.prop('disabled') )
						button_gs.val('Save');

				});

				$('#custom-codes-general-settings-form').submit(function() {
					var form = $(this);
					var data =  form.serialize();

					button_gs.prop("disabled", true).val('Saving...');

		            $.post( 'options.php', data ).error(function() {
		                alert('An error occured. Please try again.');
		            }).success( function() {

		                if ( $('input[name="cstm_cds_style_mode"]:checked').val() != "<?=$cstm_cds_style_mode?>" ) {
		                	button_gs.val('Refresh the page to apply settings');
		                } else {
							button_gs.prop("disabled", false).val('Saved!');
		                }

		            });

		            return false;

				});

			});
		</script>

		<?php
	}


	// RESPONSIVITY SETTINGS
	function cstm_cds_responsivity_settings_tab() {
		global
			$cstm_cds_tablet_l,
			$cstm_cds_tablet_p,
			$cstm_cds_phone_l,
			$cstm_cds_phone_p;
		?>

		<form method="post" action="options.php" id="custom-codes-responsivity-settings-form" enctype="multipart/form-data">
			<?php settings_fields( 'cstm_cds_responsivity_settings' ); ?>
			<?php do_settings_sections( 'cstm_cds_responsivity_settings' ); ?>

			<p>Making change is not recommended here. These are the best breakpoints.</p>

			<p>
				<b>Tablet Landscape Max Width:</b><br>
				<input class="rs-inputs" type="number" name="cstm_cds_tablet_l" value="<?=$cstm_cds_tablet_l?>" style="width: 60px;">px (<b>Default</b> @media (max-width: 1199px) {} )
			</p>

			<p>
				<b>Tablet Portrait Max Width:</b><br>
				<input class="rs-inputs" type="number" name="cstm_cds_tablet_p" value="<?=$cstm_cds_tablet_p?>" style="width: 60px;">px (<b>Default</b> @media (max-width: 991px) {} )
			</p>

			<p>
				<b>Phone Landscape Max Width:</b><br>
				<input class="rs-inputs" type="number" name="cstm_cds_phone_l" value="<?=$cstm_cds_phone_l?>" style="width: 60px;">px (<b>Default</b> @media (max-width: 767px) {} )
			</p>

			<p>
				<b>Phone Portrait Max Width:</b><br>
				<input class="rs-inputs" type="number" name="cstm_cds_phone_p" value="<?=$cstm_cds_phone_p?>" style="width: 60px;">px (<b>Default</b> @media (max-width: 479px) {} )
			</p>




			<input id="custom-codes-responsivity-settings-saver" value="Save" type="submit" class="button-primary">
		</form>

		<script>
			jQuery(document).ready(function($){

				var button_rs = $('#custom-codes-responsivity-settings-saver');

				$('.rs-inputs').on('change', function() {

					if ( !button_rs.prop('disabled') )
						button_rs.val('Save');

				});

				$('#custom-codes-responsivity-settings-form').submit(function() {
					var form = $(this);
					var data =  form.serialize();

					button_rs.prop("disabled", true).val('Saving...');

		            $.post( 'options.php', data ).error(function() {
		                alert('An error occured. Please try again.');
		            }).success( function() {

						button_rs.prop("disabled", false).val('Saved!');

		            });

		            return false;

				});

			});
		</script>

		<?php
	}



	// ADMIN NOTES
	function cstm_cds_admin_notes_tab() {
		global $cstm_cds_admin_notes;
		?>

		<p>Take your notes here:</p>

		<form method="post" action="options.php" id="custom-codes-admin-notes-form" enctype="multipart/form-data">
			<?php settings_fields( 'cstm_cds_notes_settings' ); ?>
			<?php do_settings_sections( 'cstm_cds_notes_settings' ); ?>

			<textarea id="custom-codes-admin-notes" name="cstm_cds_admin_notes[<?=get_current_user_id()?>]" rows="10" placeholder="Write something you shouldn't forget..."><?=is_array($cstm_cds_admin_notes) && isset($cstm_cds_admin_notes[get_current_user_id()]) ? $cstm_cds_admin_notes[get_current_user_id()] : ""?></textarea>
			<input id="custom-codes-admin-notes-saver" value="Save" type="submit" class="button-primary">
		</form>

		<script>
			jQuery(document).ready(function($){

				var button_nt = $('#custom-codes-admin-notes-saver');

				$('#custom-codes-admin-notes').on('input', function() {

					if ( !button_nt.prop('disabled') )
						button_nt.val('Save');

				});

				$('#custom-codes-admin-notes-form').submit(function() {
					var form = $(this);
					var notes = $('#custom-codes-admin-notes');
					var data =  form.serialize();

					notes.prop("disabled", true);
					button_nt.prop("disabled", true).val('Saving...');

		            $.post( 'options.php', data ).error(function() {
		                alert('An error occured. Please try again.');
		            }).success( function() {
		                notes.prop("disabled", false);
		                button_nt.prop("disabled", false).val('Saved!');
		            });

		            return false;

				});

			});
		</script>

		<?php
	}



	// ADMIN PERMISSIONS
	function cstm_cds_permissions_tab() {
		global $wp_roles, $cstm_cds_permission_roles;
		?>

		<p>Select roles to allow users editing Custom Codes:<br/>
			<small>("Custom Codes Admin" role always has access.)</small>
		</p>


		<form method="post" action="options.php" id="custom-codes-permissions-form" enctype="multipart/form-data">
			<?php settings_fields( 'cstm_cds_permission_settings' ); ?>
			<?php do_settings_sections( 'cstm_cds_permission_roles' ); ?>

			<?php


	$cstm_cds_roles_list = array();
	$cstm_cds_current_user_roles = array();
	$cstm_cds_selected_roles = array();
	foreach ( $wp_roles->roles as $role => $role_details ) {

		// Extract the Custom Codes Admin
		if ( $role == "cstm_cds_admin" ) continue;

		// Already recorded?
		$selected = in_array($role, $cstm_cds_permission_roles) ? "selected" : "";
		if ( $selected == "selected" ) {
			$cstm_cds_selected_roles[$role] = array(
				'name' => $role_details['name']
			);
		}

		// Extract the current and selected roles
		if ( !current_user_can('cstm_cds_admin') && current_user_can($role) ) {
			$cstm_cds_current_user_roles[$role] = array(
				'name' => $role_details['name'],
				'selected' => $selected
			);
			continue;
		}

		$cstm_cds_roles_list[$role] = array(
			'name' => $role_details['name'],
			'selected' => $selected
		);

	}


	// INCLUDE THE CURRENT USER SELECTED ROLES AS HIDDEN
	foreach ( $cstm_cds_current_user_roles as $role => $role_details ) {

		// Include the current and selected roles
		if ( !current_user_can('cstm_cds_admin') && $role_details['selected'] == "selected" ) echo '<input type="hidden" name="cstm_cds_permission_roles[]" value="'.$role.'">';


	}


	// SHOW THE ROLE SELECTOR
	echo '<p><select id="cstm_cds_permission_roles" name="cstm_cds_permission_roles[]" size="'.(count($cstm_cds_roles_list)+1).'" multiple>';
		echo '<option class="mandatory-option" disabled="" selected="">Custom Codes Admin</option>';
		foreach ( $cstm_cds_roles_list as $role => $role_details ) {

			$role_name = $role_details['name'];

			echo '<option value="'.$role.'" '.$role_details['selected'].'>'.$role_name.'</option>';

		}
	echo '</select></p>';



			?>

			<input id="custom-codes-permissions-saver" value="Save" type="submit" class="button-primary">
		</form>

		<p><small>These chosen roles will be able to see the Custom Codes admin menu. If none of them is chosen, <br/>all the users in Administrator and Custom Codes Admin roles will be able to use/delete the plugin.</small></p>

		<script>
			jQuery(document).ready(function($){

				var button_nt = $('#custom-codes-permissions-saver');

				$('#cstm_cds_permission_roles').on('change', function() {

					$(this).children('option.mandatory-option').prop('selected', true);


					if ( $(this).children('option:selected').length == 1 )
						$(this).children('option[value="administrator"]').prop('selected', true);


					if ( !button_nt.prop('disabled') )
						button_nt.val('Save');

				});

				$('#custom-codes-permissions-form').submit(function() {
					var form = $(this);
					var roles = $('#cstm_cds_permission_roles');
					var data =  form.serialize();

					roles.prop("disabled", true);
					button_nt.prop("disabled", true).val('Saving...');

		            $.post( 'options.php', data ).error(function() {
		                alert('An error occured. Please try again.');
		            }).success( function() {
		                roles.prop("disabled", false);
		                button_nt.prop("disabled", false).val('Saved!');
		            });

		            return false;

				});

			});
		</script>

		<?php
	}



	// ADMIN CODES ROLES
	function cstm_cds_admin_permissions_tab() {
		global $wp_roles, $cstm_cds_admin_roles;
		?>

		<p>Select roles to activate the admin side CSS and JS codes:</p>

		<form method="post" action="options.php" id="custom-codes-admin-permissions-form" enctype="multipart/form-data">
			<?php settings_fields( 'cstm_cds_admin_settings' ); ?>
			<?php do_settings_sections( 'cstm_cds_admin_roles' ); ?>

			<?php


	$cstm_cds_roles_list = array();
	$cstm_cds_current_user_roles = array();
	$cstm_cds_selected_roles = array();
	foreach ( $wp_roles->roles as $role => $role_details ) {

		// Extract the current roles
		if ( current_user_can($role) ) {
			$cstm_cds_current_user_roles[] = $role; // Record current roles
			continue;
		}

		// Already recorded?
		$selected = in_array($role, $cstm_cds_admin_roles) ? "selected" : "";
		if ( $selected == "selected" ) {
			$cstm_cds_selected_roles[$role] = array(
				'name' => $role_details['name']
			);
		}

		$cstm_cds_roles_list[$role] = array(
			'name' => $role_details['name'],
			'selected' => $selected
		);

	}



	// SHOW THE ROLE SELECTOR
	echo '<p><select id="cstm_cds_admin_roles" name="cstm_cds_admin_roles[]" size="'.count($cstm_cds_roles_list).'" multiple>';
		foreach ( $cstm_cds_roles_list as $role => $role_details ) {

			$role_name = $role_details['name'];

			echo '<option value="'.$role.'" '.$role_details['selected'].'>'.$role_name.'</option>';

		}
	echo '</select></p>';

			?>

			<input id="custom-codes-admin-permissions-saver" value="Save" type="submit" class="button-primary">
		</form>

		<script>
			jQuery(document).ready(function($){

				var button_nt = $('#custom-codes-admin-permissions-saver');

				$('#cstm_cds_admin_roles').on('change', function() {

					if ( !button_nt.prop('disabled') )
						button_nt.val('Save');

				});

				$('#custom-codes-admin-permissions-form').submit(function() {
					var form = $(this);
					var roles = $('#cstm_cds_admin_roles');
					var data =  form.serialize();

					roles.prop("disabled", true);
					button_nt.prop("disabled", true).val('Saving...');

		            $.post( 'options.php', data ).error(function() {
		                alert('An error occured. Please try again.');
		            }).success( function() {
		                roles.prop("disabled", false);
		                button_nt.prop("disabled", false).val('Saved!');
		            });

		            return false;

				});

			});
		</script>

		<?php
	}





}


?>