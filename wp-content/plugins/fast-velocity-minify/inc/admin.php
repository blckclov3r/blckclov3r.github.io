<?php

# Exit if accessed directly				
if (!defined('ABSPATH')){ exit(); }	

# check for minimum requirements and prevent activation or disable if not fully compatible
function fvm_check_minimum_requirements() {
	if(current_user_can('manage_options')) {
		
		# defaults
		$error = '';

		# php version requirements
		if (version_compare( PHP_VERSION, '5.6', '<' )) { 
			$error = __( 'FVM requires PHP 5.6 or higher. You’re still on', 'fast-velocity-minify' ) .' '. PHP_VERSION; 
		}

		# php extension requirements	
		if (!extension_loaded('mbstring')) { 
			$error = __( 'FVM requires the PHP mbstring module to be installed on the server.', 'fast-velocity-minify' ); 
		}
		
		# wp version requirements
		if ( version_compare( $GLOBALS['wp_version'], '4.5', '<' ) ) {
			$error = __( 'FVM requires WP 4.5 or higher. You’re still on', 'fast-velocity-minify' ) .' '. $GLOBALS['wp_version']; 
		}
		
		# cache permissions		
		global $fvm_cache_paths;
		if(is_dir($fvm_cache_paths['cache_base_dir']) && !is_writable($fvm_cache_paths['cache_base_dir'])) {
		$error = __( 'FVM needs writing permissions.', 'fast-velocity-minify' ). ' ['.$fvm_cache_paths['cache_base_dir'].']';
		}
		
		# deactivate plugin forcefully
		global $fvm_var_basename;
		if ((is_plugin_active($fvm_var_basename) && !empty($error)) || !empty($error)) { 
		if (isset($_GET['activate'])) { unset($_GET['activate']); }
			deactivate_plugins($fvm_var_basename); 
			add_settings_error( 'fvm_admin_notice', 'fvm_admin_notice', $error, 'success' );
		}
		
	}
}


# check for soft errors and misconfiguration
function fvm_check_misconfiguration() {
	if(current_user_can('manage_options')) {
		
		global $fvm_settings, $fvm_cache_paths;
		
		# check if custom cache directory exists
		if(isset($fvm_settings['cache']['path']) && !empty($fvm_settings['cache']['path']) && !is_dir($fvm_settings['cache']['path']) && !is_writeable($fvm_settings['cache']['path'])) {
			add_settings_error( 'fvm_admin_notice', 'fvm_admin_notice', __( 'FVM needs writing permissions.', 'fast-velocity-minify' ).
		' ['.$fvm_settings['cache']['path'].']' , 'success' );
		}
		
		# cache permissions		
		if(!is_dir($fvm_cache_paths['cache_base_dir']) && !is_writeable($fvm_settings['cache']['path'])) {
			$error = __( 'FVM needs writing permissions.', 'fast-velocity-minify' ) . ' ['.$fvm_cache_paths['cache_base_dir'].']';
		}

		# initialize database routine if not available
		fvm_initialize_database();
		
	}
}




# save plugin settings on wp-admin
function fvm_save_settings() {

	# save settings
	if(isset($_POST['fvm_action']) && isset($_POST['fvm_settings_nonce']) && $_POST['fvm_action'] == 'save_settings') {
		
		if(!current_user_can('manage_options')) {
			wp_die( __('You do not have sufficient permissions to access this page.', 'fast-velocity-minify'), __('Error:', 'fast-velocity-minify'), array('response'=>200)); 
		}
		
		if(!wp_verify_nonce($_POST['fvm_settings_nonce'], 'fvm_settings_nonce')) {
			wp_die( __('Invalid nounce. Please refresh and try again.', 'fast-velocity-minify'), __('Error:', 'fast-velocity-minify'), array('response'=>200)); 
		}
		
		# update fvm_settings in the global scope
		if(isset($_POST['fvm_settings']) && is_array($_POST['fvm_settings'])) {
			
			# sanitize recursively
			if(is_array($_POST['fvm_settings'])) {
				foreach ($_POST['fvm_settings'] as $group=>$arr) {
					if(is_array($arr)) {
						foreach ($arr as $k=>$v) {
							
							# only numeric, string or arrays allowed at this level
							if(!is_string($v) && !is_numeric($v) && !is_array($v)) { $_POST['fvm_settings'][$group][$k] = ''; }
							
							# numeric fields, only positive integers allowed 
							if(is_numeric($v)) { $_POST['fvm_settings'][$group][$k] = abs(intval($v)); }
							
							# sanitize text area content
							if(is_string($v)) { $_POST['fvm_settings'][$group][$k] = strip_tags($v); }
							
							# clean cdn url
							if($group == 'cdn' && $k == 'url') { 
								$_POST['fvm_settings'][$group][$k] = trim(trim(str_replace(array('http://', 'https://'), '', $v), '/'));
							}
		
						}
					}
				}
			}
			
			# get mandatory default exclusions
			global $fvm_settings;
			$fvm_settings = fvm_get_default_settings($_POST['fvm_settings']);
			
			# purge caches
			fvm_purge_all();
			
			# save settings
			update_option('fvm_settings', json_encode($fvm_settings), false);
			add_settings_error( 'fvm_admin_notice', 'fvm_admin_notice', 'Settings saved successfully!', 'success' );
		
		} else {
			wp_die( __('Invalid data!', 'fast-velocity-minify'), __('Error:', 'fast-velocity-minify'), array('response'=>200)); 
		}
	}
}

# return checked, or empty for checkboxes in admin
function fvm_get_settings_checkbox($value) {
	if($value == 1) { return 'checked'; }
	return '';
}

# return checked, or empty for checkboxes in admin
function fvm_get_settings_radio($key, $value) {
	if($key == $value) { return 'checked'; }
	return '';
}


# add settings link on plugins listing page
add_filter("plugin_action_links_".$fvm_var_basename, 'fvm_min_settings_link' );
function fvm_min_settings_link($links) {
	global $fvm_var_basename;
	if (is_plugin_active($fvm_var_basename)) { 
		$settings_link = '<a href="'.admin_url('admin.php?page=fvm').'">Settings</a>'; 
		array_unshift($links, $settings_link); 
	}
return $links;
}


# Enqueue plugin UI CSS and JS files
function fvm_add_admin_jscss($hook) {
	if(current_user_can('manage_options')) {
		if ('settings_page_fvm' != $hook) { return; }
		global $fvm_var_dir_path, $fvm_var_url_path;
		
		# ui
		wp_enqueue_script( 'jquery-ui-core' );
		wp_enqueue_script( 'jquery-ui-accordion' );
		
		# js
		wp_enqueue_script('fvm', $fvm_var_url_path . 'assets/fvm.js', array('jquery'), filemtime($fvm_var_dir_path.'assets'. DIRECTORY_SEPARATOR .'fvm.js'));
		
		# css
		wp_enqueue_style('fvm', $fvm_var_url_path . 'assets/fvm.css', array(), filemtime($fvm_var_dir_path.'assets'. DIRECTORY_SEPARATOR .'fvm.css'));
		
	}
}


# create sidebar admin menu and add templates to admin
function fvm_add_admin_menu() {
	if (current_user_can('manage_options')) {
		add_options_page('FVM Settings', 'Fast Velocity Minify', 'manage_options', 'fvm', 'fvm_add_settings_admin');
	}
}


# print admin notices when needed (json)
function fvm_show_admin_notice_from_transient() {
	if(current_user_can('manage_options')) {
		$inf = get_transient('fvm_admin_notice');
		if($inf != false && !empty($inf)) {
			$jsonarr = json_decode($inf, true);
			if(!is_null($jsonarr) && is_array($jsonarr)){
				
				# add all
				$jsonarr = array_unique($jsonarr);
				foreach ($jsonarr as $notice) {
					add_settings_error( 'fvm_admin_notice', 'fvm_admin_notice', 'FVM: '.$notice, 'info' );
				}	
				
				# output on other pages
				if(!isset($_GET['page']) || (isset($_GET['page']) && $_GET['page'] != 'fvm')) {
					settings_errors( 'fvm_admin_notice' );
				}
			}
			
			# remove
			delete_transient('fvm_admin_notice');
		}
	}
}

# manage settings page
function fvm_add_settings_admin() {
	
	# admin only
	if (!current_user_can('manage_options')) { 
		wp_die( __('You do not have sufficient permissions to access this page.'), __('Error:'), array('response'=>200)); 
	}

	# include admin html template
	global $fvm_cache_paths, $fvm_settings, $fvm_var_dir_path;
	
	# admin html templates
	include($fvm_var_dir_path . 'layout' . DIRECTORY_SEPARATOR . 'admin-layout.php');

}


# function to list all cache files on the status page (js ajax code)
function fvm_get_logs_callback() {
		
	# must be able to cleanup cache
	if (!current_user_can('manage_options')) {
		wp_die( __('You do not have sufficient permissions to access this page.', 'fast-velocity-minify'), __('Error:', 'fast-velocity-minify'), array('response'=>200)); 
	}
	
	# must have
	if(!defined('WP_CONTENT_DIR')) { 
		wp_die( __('WP_CONTENT_DIR is undefined!', 'fast-velocity-minify'), __('Error:', 'fast-velocity-minify'), array('response'=>200)); 
	}
	
	# get info
	global $fvm_cache_paths;
	
	# must have valid cache paths
	if(isset($fvm_cache_paths['cache_dir_min']) && !empty($fvm_cache_paths['cache_dir_min'])) {
		
		# defaults
		$count_css = 0;
		$count_js = 0;
		$size_css = 0;
		$size_js = 0;
	
		# scan min directory recursively
		$errora = false;
		if(is_dir($fvm_cache_paths['cache_dir_min'])) {
			try {
				$i = new DirectoryIterator($fvm_cache_paths['cache_dir_min']);
				foreach($i as $f){
					if($f->isFile()){ 
					
						# javascript
						if(stripos($f->getRealPath(), '.js') !== false) {
							$count_js = $count_js + 1;
							$size_js = $size_js + intval($f->getSize());
						}
						
						# css
						if(stripos($f->getRealPath(), '.css') !== false) {
							$count_css = $count_css + 1;
							$size_css = $size_css + intval($f->getSize());
						}
						
					}
				}
			} catch (Exception $e) {
				$errora = get_class($e) . ": " . $e->getMessage();
			}
		}
			
		# return early if errors
		if($errora != false) {
			header('Content-Type: application/json');
			echo json_encode(array('error' => $errora));
			exit();
		}
		
		
		# defaults
		global $wpdb;
		$tbl_name_log = $wpdb->prefix .'fvm_logs';
		$tbl_name_cache = $wpdb->prefix .'fvm_cache';
		
		# initialize log
		$css_log = '';
		
		# build css logs from database
		
		$results = $wpdb->get_results("SELECT date, content, meta FROM `$tbl_name_log` WHERE type = 'css' ORDER BY id DESC LIMIT 20");
		
		# build second query
		foreach ($results as $log) {
			
			# get meta into an array
			$meta = json_decode($log->meta, true);
			
			# start log
			$css_log.= '+++++++++' . PHP_EOL;
			$css_log.= 'PROCESSED - ' . date('r', $log->date) . ' - VIA - '. $meta['loc'] . PHP_EOL;
			$css_log.= 'GENERATED - ' . $meta['fl'] . PHP_EOL;
			$css_log.= 'MEDIATYPE - ' . $meta['mt'] . PHP_EOL;
			$css_log.= '---' . PHP_EOL;
			
			# generate uid's from json
			$list = array(); $list = json_decode($log->content);
			
			# get rows to log file
			if(count($list) > 0) {
				$listuids = implode(', ', array_fill(0, count($list), '%s'));
				if(!empty($listuids)) {
					$rs = array(); $rs = $wpdb->get_results($wpdb->prepare("SELECT meta FROM `$tbl_name_cache` WHERE uid IN (".$listuids.") ORDER BY FIELD(uid, '".implode("', '", $list)."')", $list));
					foreach ($rs as $r) {
						$imt = json_decode($r->meta, true);
						$css_log.= '[Size: '.str_pad(fvm_format_filesize($imt['fs']), 10,' ',STR_PAD_LEFT).']'."\t". $imt['url'] . PHP_EOL;
					}
				}
				$css_log.= '+++++++++' . PHP_EOL . PHP_EOL;
			}
		}
		
		# trim
		$css_log = trim($css_log);

		# initialize log
		$js_log = '';
		
		# build css logs from database
		$results = $wpdb->get_results("SELECT date, content, meta FROM `$tbl_name_log` WHERE type = 'js' ORDER BY id DESC LIMIT 20");
		
		# build second query
		foreach ($results as $log) {
			
			# get meta into an array
			$meta = json_decode($log->meta, true);
			
			# start log
			$js_log.= '+++++++++' . PHP_EOL;
			$js_log.= __( 'PROCESSED', 'fast-velocity-minify' ) . ' - ' . date('r', $log->date) . ' - VIA - '. $meta['loc'] . PHP_EOL;
			$js_log.= __( 'GENERATED', 'fast-velocity-minify' ) . ' - ' . $meta['fl'] . PHP_EOL;
			$js_log.= '---' . PHP_EOL;
			
			# generate uid's from json
			$list = array(); $list = json_decode($log->content);
			
			# get rows to log file
			if(count($list) > 0) {
				$listuids = implode(', ', array_fill(0, count($list), '%s'));
				if(!empty($listuids)) {
					$rs = array(); $rs = $wpdb->get_results($wpdb->prepare("SELECT meta FROM `$tbl_name_cache` WHERE uid IN (".$listuids.") ORDER BY FIELD(uid, '".implode("', '", $list)."')", $list));
					foreach ($rs as $r) {
						$imt = json_decode($r->meta, true);
						$js_log.= '['.__( 'Size:', 'fast-velocity-minify' ).' '.str_pad(fvm_format_filesize($imt['fs']), 10,' ',STR_PAD_LEFT).']'."\t". $imt['url'] . PHP_EOL;
					}
				}
				$js_log.= '+++++++++' . PHP_EOL . PHP_EOL;
			}
		}
		
		# trim
		$js_log = trim($js_log);
		
		# default message
		if(empty($css_log)) { $css_log = __( 'No CSS files generated yet.', 'fast-velocity-minify' ); }
		if(empty($js_log)) { $js_log = __( 'No JS files generated yet.', 'fast-velocity-minify' ); }
		
		# build info
		$result = array(
			'stats_total' => array('count'=>($count_css+$count_js), 'size'=>fvm_format_filesize($size_css+$size_js)),
			'stats_css' => array('count'=>$count_css, 'size'=>fvm_format_filesize($size_css)),
			'stats_js' => array('count'=>$count_js, 'size'=>fvm_format_filesize($size_js)),
			'js_log' => $js_log,
			'css_log' => $css_log,
			'success' => 'OK'
		);
		
		# return result
		header('Content-Type: application/json');
		echo json_encode($result);
		exit();
		
	}
	
	# default
	wp_die( __('Unknown cache path!', 'fast-velocity-minify'), __('Error:', 'fast-velocity-minify'), array('response'=>200)); 
}


# run during activation
register_activation_hook($fvm_var_file, 'fvm_plugin_activate');
function fvm_plugin_activate() {
		
	# default variables
	global $wpdb;
	$charset_collate = $wpdb->get_charset_collate();
	$sqla_table_name = $wpdb->prefix . 'fvm_cache';
	$sqlb_table_name = $wpdb->prefix . 'fvm_logs';
	
	# prepare	
	$sqla = "CREATE TABLE IF NOT EXISTS `$sqla_table_name` (
        `id` bigint(20) unsigned NOT NULL auto_increment, 
        `uid` varchar(60) NOT NULL, 
		`date` bigint(20) unsigned NOT NULL, 
		`type` varchar(32) NOT NULL, 
		`content` mediumtext NOT NULL, 
		`meta` mediumtext NOT NULL, 
        PRIMARY KEY  (id), 
		UNIQUE KEY uid (uid), 
		KEY date (date), KEY type (type) 
        ) $charset_collate;";
		 
	# create logs table
	
	$sqlb = "CREATE TABLE IF NOT EXISTS `$sqlb_table_name` (
        `id` bigint(20) unsigned NOT NULL auto_increment, 
		`uid` varchar(60) NOT NULL, 
		`date` bigint(20) unsigned NOT NULL, 
		`type` varchar(32) NOT NULL, 
		`content` mediumtext NOT NULL, 
		`meta` mediumtext NOT NULL, 
		PRIMARY KEY  (id), 
		UNIQUE KEY uid (uid), 
		KEY date (date), 
		KEY type (type) 
        ) $charset_collate;";

	# run sql
	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sqla );
	dbDelta( $sqlb );
	
	# initialize cache time
	fvm_cache_increment();

}


# run during deactivation
register_deactivation_hook($fvm_var_file, 'fvm_plugin_deactivate');
function fvm_plugin_deactivate() {
	global $wpdb, $fvm_settings, $fvm_cache_paths;
	
	# remove all caches on deactivation
	if(isset($fvm_cache_paths['cache_dir_min']) && stripos($fvm_cache_paths['cache_dir_min'], '/fvm') !== false) {
		fvm_rrmdir($fvm_cache_paths['cache_base_dir']);
	}
	
	# delete cache table
	$tbl_name = $wpdb->prefix .'fvm_cache';
	$sql = $wpdb->prepare( "DROP TABLE IF EXISTS `$tbl_name`", $tbl_name);
	$wpdb->query($sql);
	
	# delete logs table
	$tbl_name = $wpdb->prefix .'fvm_logs';
	$sql = $wpdb->prepare( "DROP TABLE IF EXISTS `$tbl_name`", $tbl_name);
	$wpdb->query($sql);

}

# run during uninstall
register_uninstall_hook($fvm_var_file, 'fvm_plugin_uninstall');	
function fvm_plugin_uninstall() {
	global $wpdb, $fvm_settings, $fvm_cache_paths;
	
	# fetch settings on wp-cli
	if(is_null($fvm_settings)) { $fvm_settings = fvm_get_settings(); }
	if(is_null($fvm_cache_paths)) { $fvm_cache_paths = fvm_cachepath(); }
	
	# remove settings, unless disabled
	if(!isset($fvm_settings['global']['preserve_settings']) || ( isset($fvm_settings['global']['preserve_settings']) && $fvm_settings['global']['preserve_settings'] != true)) {		
		
		# prepare and delete
		$tbl_name = $wpdb->prefix .'options';
		$sql = $wpdb->prepare( "DELETE FROM `$tbl_name` WHERE option_name = 'fvm_settings'");
		$wpdb->query($sql);
		$sql = $wpdb->prepare( "DELETE FROM `$tbl_name` WHERE option_name = 'fvm_last_cache_update'");
		$wpdb->query($sql);
		
	}
	
	# delete cache table
	$tbl_name = $wpdb->prefix .'fvm_cache';
	$sql = $wpdb->prepare( "DROP TABLE IF EXISTS `$tbl_name`");
	$wpdb->query($sql);
	
	# delete logs table
	$tbl_name = $wpdb->prefix .'fvm_logs';
	$sql = $wpdb->prepare( "DROP TABLE IF EXISTS `$tbl_name`");
	$wpdb->query($sql);
	
	# remove all cache directories
	if(isset($fvm_cache_paths['cache_dir_min']) && stripos($fvm_cache_paths['cache_dir_min'], '/fvm') !== false) {
		fvm_rrmdir($fvm_cache_paths['cache_base_dir']);
	}
}

# initialize database if it doesn't exist
function fvm_initialize_database() {
	require_once ABSPATH . 'wp-admin/includes/upgrade.php';
	global $wpdb;
	$tbl_name = $wpdb->prefix .'fvm_cache';
    $sql = $wpdb->prepare( 'SHOW TABLES LIKE %s', $wpdb->esc_like( $tbl_name ) );
    if ( $wpdb->get_var( $sql ) !== $tbl_name ) {
        fvm_plugin_activate();
    }
}


# get all known roles
function fvm_get_user_roles_checkboxes() {
	
	global $wp_roles, $fvm_settings;
	$roles_list = array();
	if(is_object($wp_roles)) {
		$roles = (array) $wp_roles->get_names();
		foreach ($roles as $role=>$rname) {
			
			$roles_list[] = '<label for="fvm_settings_minify_'.$role.'"><input name="fvm_settings[minify]['.$role.']" type="checkbox" id="fvm_settings_minify_'.$role.'" value="1" '. fvm_get_settings_checkbox(fvm_get_settings_value($fvm_settings, 'minify', $role)).'> '.$rname.' </label><br />';
		
		}
	}
	
	# return
	if(!empty($roles_list)) { return implode(PHP_EOL, $roles_list); } else { return __( 'No roles detected!', 'fast-velocity-minify' ); }

}