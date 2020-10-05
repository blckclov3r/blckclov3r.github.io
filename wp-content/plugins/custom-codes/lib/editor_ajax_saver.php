<?php

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );


// Composer
require_once( WP_PLUGIN_DIR.'/custom-codes/vendor/autoload.php' );


// SASS
$cstm_cds_scss = new scssc();
$cstm_cds_scss->setFormatter("scss_formatter");
//$cstm_cds_scss->setLineNumberStyle(Compiler::LINE_COMMENTS);
$cstm_cds_scss->addImportPath(function($path) {
    if (!file_exists(CSTM_CDS_DIR.$path)) return null;
    return CSTM_CDS_DIR.$path;
});


// AutoPrefixer
$cstm_cds_autoprefixer = new Autoprefixer(['ff > 2', '> 2%', 'ie 8']);



// Sample Content
require_once( dirname(__file__).'/editor_defaults.php' );



// SAVE THE FILES WITH AJAX
function cstm_cds_saver_ajax() {
	global $cstm_cds_sass, $cstm_cds_scss, $cstm_cds_autoprefixer, $cstm_cds_sass_responsivity_codes, $cstm_cds_result_level;

	// THE DATA
	$data = $_POST;





	// Security Check
	if (
		!current_user_can('cstm_cds_full_access') ||
		!isset($data['cstm_cds_editor_contents']) ||
		!wp_verify_nonce( $data['cstm_cds_nonce'], 'cc-nonce')
	) {
		$cstm_cds_json_result['error'][] = "Permission check failed";
		echo json_encode($cstm_cds_json_result);
		exit();
	}


	// Response Images
	$cstm_cds_ok_image = '<img class="ok responser result" src="'.admin_url("/images/yes.png").'">';
	$cstm_cds_error_image = '<img class="error responser result" src="'.admin_url("/images/no.png").'">';
	$css_done = $js_done = false;
	$cstm_cds_json_result = array();



	// Custom Folder Exists?
	if (!file_exists(CSTM_CDS_DIR))
		mkdir(CSTM_CDS_DIR, 0755, true);
	@chmod(CSTM_CDS_DIR, 0755);





	// START CHECKING EACH FILE
	foreach ($data['cstm_cds_editor_contents'] as $key => $file) {

		// Obtained data
		$custom_codes_file_lang = $file[0];
		$custom_codes_file_name = $file[1];
		$custom_codes_file_content = stripslashes_deep( $file[2] );
		$cstm_cds_admin = substr($custom_codes_file_name, 0, 6) == "admin_" ? true : false;


		// Permission Check
		if ( substr($custom_codes_file_name, 0, 6) == "admin_" && !current_user_can('cstm_cds_full_access') ) {
			$cstm_cds_json_result['error'][] = "Permission check failed";
			echo json_encode($cstm_cds_json_result);
			exit();
		}



		// IF SASS FILE
		if ( $custom_codes_file_lang == 'sass' ) {

			$css_output_done = "";
			$file_scss = CSTM_CDS_DIR."$custom_codes_file_name.scss";
			$file_css_output = CSTM_CDS_DIR."$custom_codes_file_name.css";

			if ( substr($custom_codes_file_name, 0, 6) == "admin_" ) {
				$file_main_css_output = CSTM_CDS_DIR."admin_panel.css";
			} else {
				$file_main_css_output = CSTM_CDS_DIR."custom_public.css";
			}


			// Data to compile
			if ( $custom_codes_file_name == "custom_public" ) {

				$data_to_compile = cstm_cds_css_main_file(false);

			} elseif ( $custom_codes_file_name == "admin_panel" ) {

				$data_to_compile = cstm_cds_css_main_file(true);

			} else {

				if ( $custom_codes_file_name == "mixins" ) {

					$data_to_compile = cstm_cds_css_main_file(false, true);
					$mixin_output = "custom_public";

				} elseif ( $custom_codes_file_name == "admin_mixins" ) {

					$data_to_compile = cstm_cds_css_main_file(true, true);
					$mixin_output = "admin_panel";

				} else {

					// ADMIN NORMAL SASS
					if ( substr($custom_codes_file_name, 0, 6) == "admin_" ) {

						if ( file_exists(CSTM_CDS_DIR."admin_mixins.scss") ) {
							$data_to_compile = "@import 'admin_mixins.scss'; \n".$custom_codes_file_content;
						} else {
							$data_to_compile = $custom_codes_file_content;
						}

					// PUBLIC NORMAL SASS
					} else {

						if ( file_exists(CSTM_CDS_DIR."mixins.scss") ) {
							$data_to_compile = "@import 'mixins.scss'; \n".$custom_codes_file_content;
						} else {
							$data_to_compile = $custom_codes_file_content;
						}

					}

				}

			}


			// SASS FILE SAVE
			$scss_content = stripslashes_deep( $custom_codes_file_content ) == "" ? cstm_cds_empty_codes('sass', $custom_codes_file_name) : $custom_codes_file_content;

			cstm_cds_process_timer_start();
				$scss_done = file_put_contents($file_scss, $scss_content, FILE_TEXT );
			$scss_end = cstm_cds_process_timer_finish();

			// SASS FILE ERROR CATCHING
			if ( stripslashes_deep( $custom_codes_file_content ) != "" && isset($scss_done) && $scss_done == 0 ) {
				$cstm_cds_json_result['error'][] = $cstm_cds_error_image." '$custom_codes_file_name.scss' File Not Saved for $cstm_cds_result_level<br>";
			} else {
				$cstm_cds_json_result['success']["$custom_codes_file_name.scss"] = $cstm_cds_ok_image." '$custom_codes_file_name.scss' File Saved for $cstm_cds_result_level: $scss_done ($scss_end)<br>";
			}



			// OUTPUT FILE SAVE
			try {


				// DO NOT OUTPUT SELF SCSS IF MIXINS FILE
				if ( $custom_codes_file_name != "mixins" && $custom_codes_file_name != "admin_mixins" ) {

					cstm_cds_process_timer_start();
						$css_output_content = $cstm_cds_scss->compile( $data_to_compile );
						//$css_output_content = $cstm_cds_autoprefixer->compile($css_output_content);
						$css_output_done = file_put_contents($file_css_output, $css_output_content, FILE_TEXT );
					$css_output_end = cstm_cds_process_timer_finish();

				} else {

					cstm_cds_process_timer_start();
						$css_output_content = $cstm_cds_scss->compile( $data_to_compile );
						//$css_output_content = $cstm_cds_autoprefixer->compile($css_output_content);
						$css_output_done = file_put_contents($file_main_css_output, $css_output_content, FILE_TEXT );

						// Increase the version number
						$css_version = cstm_cds_pull_option('cstm_cds_'.($cstm_cds_admin ? 'admin_' : '').'css_save_count', 0);
						update_option( 'cstm_cds_'.($cstm_cds_admin ? 'admin_' : '').'css_save_count', $css_version+1 );

					$css_output_end = cstm_cds_process_timer_finish();

				}


				if ( substr($custom_codes_file_name, 0, 6) == "admin_" ) {
					$cstm_cds_json_result['css_output'] = cstm_cds_css_main_file(true);
				} else {
					$cstm_cds_json_result['css_output'] = cstm_cds_css_main_file(false);
				}

			} catch (Exception $e) {
			    $cstm_cds_json_result['error'][] = "<b>Error:</b> ".$e->getMessage()."<br>";
				$cstm_cds_json_result['css_output'] = "*** Fix this error *** \n".$e->getMessage();
			}

			// OUTPUT ERROR CATCHING
			if ( $custom_codes_file_name == "mixins" || $custom_codes_file_name == "admin_mixins" ) $custom_codes_file_name = $mixin_output;

			if ( stripslashes_deep( $custom_codes_file_content ) != "" && isset($css_output_done) && $css_output_done == 0 ) {
				$cstm_cds_json_result['error'][] = $cstm_cds_error_image." Output '$custom_codes_file_name.css' Not Saved for $cstm_cds_result_level<br>";
			} else {
				$cstm_cds_json_result['success']["$custom_codes_file_name.css"] = $cstm_cds_ok_image." Output '$custom_codes_file_name.css' Saved for $cstm_cds_result_level: $css_output_done ($css_output_end)<br>";
			}



		// IF CSS FILE
		} elseif ( $custom_codes_file_lang == 'css' ) {

			$file_css = CSTM_CDS_DIR."$custom_codes_file_name.css";

			// REMOVE THE OLD SASS FILE
			if(file_exists(CSTM_CDS_DIR."$custom_codes_file_name.scss"))
				@unlink(CSTM_CDS_DIR."$custom_codes_file_name.scss");



			// MAIN CSS FILE SAVE
			if ( $custom_codes_file_name == "custom_public" ) {

				$css_content = $cstm_cds_json_result['css_output'] = cstm_cds_css_main_file(false);

			} elseif ($custom_codes_file_name == "admin_panel" ) {

				$css_content = $cstm_cds_json_result['css_output'] = cstm_cds_css_main_file(true);

			} else {
				$css_content = $custom_codes_file_content == "" ? cstm_cds_empty_codes('css', $custom_codes_file_name) :  $custom_codes_file_content;
			}

			cstm_cds_process_timer_start();
				$css_done = file_put_contents($file_css, $css_content, FILE_TEXT );

				// Increase the version number
				$css_version = cstm_cds_pull_option('cstm_cds_'.($cstm_cds_admin ? 'admin_' : '').'css_save_count', 0);
				update_option( 'cstm_cds_'.($cstm_cds_admin ? 'admin_' : '').'css_save_count', $css_version+1 );

			$css_end = cstm_cds_process_timer_finish();


			// CSS FILE ERROR CATCHING
			if ( $custom_codes_file_content != "" && isset($css_done) && $css_done == 0 ) {
				$cstm_cds_json_result['error'][] = $cstm_cds_error_image." '$custom_codes_file_name.css' Not Saved for $cstm_cds_result_level<br>";
			} else {
				$cstm_cds_json_result['success']["$custom_codes_file_name.css"] = $cstm_cds_ok_image." '$custom_codes_file_name.css' Saved for $cstm_cds_result_level: $css_done ($css_end)<br>";
			}



		// IF OTHER FILE
		} else {

			// SAVE
			$file_name = "$custom_codes_file_name.$custom_codes_file_lang";
			$file_other = CSTM_CDS_DIR.$file_name;

			cstm_cds_process_timer_start();
				$other_done = file_put_contents($file_other, $custom_codes_file_content, FILE_TEXT );

				if ( $file_name == "custom_public.js" || $file_name == "admin_panel.js" ) {

					// Increase the version number
					$js_bottom_version = cstm_cds_pull_option('cstm_cds_'.($cstm_cds_admin ? 'admin_' : '').'js_bottom_save_count', 0);
					update_option( 'cstm_cds_'.($cstm_cds_admin ? 'admin_' : '').'js_bottom_save_count', $js_bottom_version+1 );

				} elseif ( $file_name == "custom_public_head.js" || $file_name == "admin_panel_head.js" ) {

					// Increase the version number
					$js_head_version = cstm_cds_pull_option('cstm_cds_'.($cstm_cds_admin ? 'admin_' : '').'js_head_save_count', 0);
					update_option( 'cstm_cds_'.($cstm_cds_admin ? 'admin_' : '').'js_head_save_count', $js_head_version+1 );

				}


			$other_end = cstm_cds_process_timer_finish();

			// ERROR CATCHING
			if ( $custom_codes_file_content != "" && isset($other_done) && $other_done == 0 ) {
				$cstm_cds_json_result['error'][] = $cstm_cds_error_image." '$custom_codes_file_name.$custom_codes_file_lang' Not Saved for $cstm_cds_result_level<br>";
			} else {
				$cstm_cds_json_result['success']["$custom_codes_file_name.$custom_codes_file_lang"] = $cstm_cds_ok_image." '$custom_codes_file_name.$custom_codes_file_lang' Saved for $cstm_cds_result_level: $other_done ($other_end)<br>";
			}

		}


	}



	echo json_encode($cstm_cds_json_result);
	die();
}
add_action('wp_ajax_cstm_cds_ajax_action', 'cstm_cds_saver_ajax');




// CSS MAIN FILE CREATOR
function cstm_cds_css_main_file($cstm_cds_admin, $mixin_output = false) {
	global
		$cstm_cds_tablet_l,
		$cstm_cds_tablet_p,
		$cstm_cds_phone_l,
		$cstm_cds_phone_p;

$cstm_cds_result_level = $cstm_cds_admin ? "Admin" : "Public";


if ( $mixin_output ) {

	$desktop  = '@import "'.($cstm_cds_admin ? "admin_" : "").'desktop.scss";';
	$tablet_l = '@import "'.($cstm_cds_admin ? "admin_" : "").'tablet-l.scss";';
	$tablet_p = '@import "'.($cstm_cds_admin ? "admin_" : "").'tablet-p.scss";';
	$mobile_l = '@import "'.($cstm_cds_admin ? "admin_" : "").'mobile-l.scss";';
	$mobile_p = '@import "'.($cstm_cds_admin ? "admin_" : "").'mobile-p.scss";';
	$retina   = '@import "'.($cstm_cds_admin ? "admin_" : "").'retina.scss";';

} else {

	$desktop  = file_exists(CSTM_CDS_DIR.( $cstm_cds_admin ? "admin_" : "" )."desktop.css" ) ? @file_get_contents( CSTM_CDS_DIR.( $cstm_cds_admin ? "admin_" : "" )."desktop.css"  ) : "";
	$tablet_l = file_exists(CSTM_CDS_DIR.( $cstm_cds_admin ? "admin_" : "" )."tablet-l.css") ? @file_get_contents( CSTM_CDS_DIR.( $cstm_cds_admin ? "admin_" : "" )."tablet-l.css" ) : "";
	$tablet_p = file_exists(CSTM_CDS_DIR.( $cstm_cds_admin ? "admin_" : "" )."tablet-p.css") ? @file_get_contents( CSTM_CDS_DIR.( $cstm_cds_admin ? "admin_" : "" )."tablet-p.css" ) : "";
	$mobile_l = file_exists(CSTM_CDS_DIR.( $cstm_cds_admin ? "admin_" : "" )."mobile-l.css") ? @file_get_contents( CSTM_CDS_DIR.( $cstm_cds_admin ? "admin_" : "" )."mobile-l.css" ) : "";
	$mobile_p = file_exists(CSTM_CDS_DIR.( $cstm_cds_admin ? "admin_" : "" )."mobile-p.css") ? @file_get_contents( CSTM_CDS_DIR.( $cstm_cds_admin ? "admin_" : "" )."mobile-p.css" ) : "";
	$retina   = file_exists(CSTM_CDS_DIR.( $cstm_cds_admin ? "admin_" : "" )."retina.css"  ) ? @file_get_contents( CSTM_CDS_DIR.( $cstm_cds_admin ? "admin_" : "" )."retina.css"   ) : "";

}


return
($mixin_output ? '@import "'.($cstm_cds_admin ? "admin_" : "").'mixins.scss";' : '').
'/* =========================
	'.strtoupper($cstm_cds_result_level).' DESKTOP CSS
========================= */

'.$desktop.'

/* =========================
	'.strtoupper($cstm_cds_result_level).' DESKTOP CSS END
========================= */
/* =========================
	'.strtoupper($cstm_cds_result_level).' RESPONSIVE CSS
========================= */

/* TABLET LANDSCAPE */
@media (max-width: '.$cstm_cds_tablet_l.'px) {

'.$tablet_l.'

}

/* TABLET PORTRAIT */
@media (max-width: '.$cstm_cds_tablet_p.'px) {

'.$tablet_p.'

}

/* MOBILE LANDSCAPE */
@media (max-width: '.$cstm_cds_phone_l.'px) {

'.$mobile_l.'

}

/* MOBILE PORTRAIT */
@media (max-width: '.$cstm_cds_phone_p.'px) {

'.$mobile_p.'

}

/* RETINA FIXES */
@media only screen and (-webkit-min-device-pixel-ratio: 1.5),
 	   only screen and (-o-min-device-pixel-ratio: 3/2),
 	   only screen and (min--moz-device-pixel-ratio: 1.5),
       only screen and (min-device-pixel-ratio: 1.5) {

'.$retina.'

}
/* =========================
	'.strtoupper($cstm_cds_result_level).' RESPONSIVE CSS END
========================= */';

}


?>