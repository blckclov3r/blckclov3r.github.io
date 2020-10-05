<?php

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );



// DEFAULT CONTENTS
function cstm_cds_empty_codes($lang, $file_name = "") {
	global $cstm_cds_result_level;


	// SASS/CSS SAMPLE CONTENT
	if ( $lang == "sass" || $lang == "css" )
		return '/* Write your '.$cstm_cds_result_level.' '.strtoupper($lang).' '.$file_name.' here! */';



	// JS SAMPLE CONTENT
	elseif ( $lang == "js" )
		return
'jQuery(document).ready(function($){
    /* Write your '.$cstm_cds_result_level.' '.$file_name.' jQuery here! */

}); // document ready';



	// PHP SAMPLE CONTENT
	elseif ( $lang == "php" )
		return
'<?php

/* BE CAREFUL:
   Codes written here can crash the site!
   EDITING THIS FILE MANUALLY VIA FTP IS RECOMMENDED */

// THIS MUST STAY HERE FOR WORDPRESS SECURITY REASONS
defined( "ABSPATH" ) or die( "No script kiddies please!" );

/* IMPORTANT:
   If you see a BLANK PAGE when you open the site, please fix the
   /wp-content/custom_codes/admin_functions.php file\'s errors manually! */

/* Write your '.$cstm_cds_result_level.' '.strtoupper($lang).' '.$file_name.' down here! */';



	// OTHER FILE SAMPLE CONTENT
	else
		return '/* Write your '.$cstm_cds_result_level.' '.strtoupper($lang).' '.$file_name.' here! */';

}