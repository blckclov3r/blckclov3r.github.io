<?php

# Exit if accessed directly				
if (!defined('ABSPATH')){ exit(); }	

# update routines for new fields and replacements
function fvm_update_changes() {
	
	# current version
	global $fvm_var_plugin_version;	
	
	# Version 3.0 routines start
	
	# delete old FVM files
	global $fvm_var_dir_path, $fvm_var_inc_lib, $fvm_var_inc_dir;
	
	# prevent deleting if the paths are empty
	# can happen when a user runs wp-cli on a child directory
	if(isset($fvm_var_dir_path) && isset($fvm_var_inc_lib) && isset($fvm_var_inc_dir) && !empty($fvm_var_dir_path) && !empty($fvm_var_inc_lib) && !empty($fvm_var_inc_dir)) {
		
		# must be inside a fast-velocity-minify directory
		if (stripos($fvm_var_dir_path, 'fast-velocity-minify') !== false) {
		
			# delete		
			if(file_exists($fvm_var_inc_dir.'functions-cache.php')) { @unlink($fvm_var_inc_dir.'functions-cache.php'); }
			if(file_exists($fvm_var_inc_dir.'functions-cli.php')) { @unlink($fvm_var_inc_dir.'functions-cli.php'); }
			if(file_exists($fvm_var_inc_dir.'functions-serverinfo.php')) { @unlink($fvm_var_inc_dir.'functions-serverinfo.php'); }
			if(file_exists($fvm_var_inc_dir.'functions-upgrade.php')) { @unlink($fvm_var_inc_dir.'functions-upgrade.php'); }
			if(file_exists($fvm_var_inc_dir.'functions.php')) { @unlink($fvm_var_inc_dir.'functions.php'); }
			if(file_exists($fvm_var_dir_path.'fvm.css')) { @unlink($fvm_var_dir_path.'fvm.css'); }
			if(file_exists($fvm_var_dir_path.'fvm.js')) { @unlink($fvm_var_dir_path.'fvm.js'); }
			if(file_exists($fvm_var_inc_lib.'mrclay' . DIRECTORY_SEPARATOR . 'HTML.php')) { 
				@unlink($fvm_var_inc_lib.'mrclay' . DIRECTORY_SEPARATOR . 'HTML.php');
				@unlink($fvm_var_inc_lib.'mrclay' . DIRECTORY_SEPARATOR . 'index.html');
				@rmdir($fvm_var_inc_lib.'mrclay');
			}
		
		}
	}
	
	# Version 3.2 routines start
	if (get_option("fastvelocity_plugin_version") !== false) {
		if (version_compare($fvm_var_plugin_version, '3.2.0', '>=') && get_option("fastvelocity_min_ignore") !== false) {
			
			# cleanup
			delete_option('fastvelocity_min_change_cache_path');
			delete_option('fastvelocity_min_change_cache_base_url');
			delete_option('fastvelocity_min_fvm_cdn_url');
			delete_option('fastvelocity_plugin_version');
			delete_option('fvm-last-cache-update');
			delete_option('fastvelocity_min_ignore');
			delete_option('fastvelocity_min_blacklist');
			delete_option('fastvelocity_min_ignorelist');
			delete_option('fastvelocity_min_excludecsslist');
			delete_option('fastvelocity_min_excludejslist');
			delete_option('fastvelocity_min_enable_purgemenu');
			delete_option('fastvelocity_min_default_protocol');
			delete_option('fastvelocity_min_disable_js_merge');
			delete_option('fastvelocity_min_disable_css_merge');
			delete_option('fastvelocity_min_disable_js_minification');
			delete_option('fastvelocity_min_disable_css_minification');
			delete_option('fastvelocity_min_remove_print_mediatypes');
			delete_option('fastvelocity_min_skip_html_minification');
			delete_option('fastvelocity_min_strip_htmlcomments');
			delete_option('fastvelocity_min_skip_cssorder');
			delete_option('fastvelocity_min_skip_google_fonts');
			delete_option('fastvelocity_min_skip_emoji_removal');
			delete_option('fastvelocity_fvm_clean_header_one');
			delete_option('fastvelocity_min_enable_defer_js');
			delete_option('fastvelocity_min_exclude_defer_jquery');
			delete_option('fastvelocity_min_force_inline_css');
			delete_option('fastvelocity_min_force_inline_css_footer');
			delete_option('fastvelocity_min_remove_googlefonts');
			delete_option('fastvelocity_min_defer_for_pagespeed');
			delete_option('fastvelocity_min_defer_for_pagespeed_optimize');
			delete_option('fastvelocity_min_exclude_defer_login');
			delete_option('fastvelocity_min_skip_defer_lists');
			delete_option('fastvelocity_min_fvm_fix_editor');
			delete_option('fastvelocity_min_loadcss');
			delete_option('fastvelocity_min_fvm_removecss');
			delete_option('fastvelocity_enabled_css_preload');
			delete_option('fastvelocity_enabled_js_preload');
			delete_option('fastvelocity_fontawesome_method');
			delete_option('fastvelocity_gfonts_method');
			
		}
	}
	# Version 3.2 routines end
	
}
