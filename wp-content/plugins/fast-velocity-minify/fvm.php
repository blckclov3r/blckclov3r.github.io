<?php
/*
Plugin Name: Fast Velocity Minify
Plugin URI: http://fastvelocity.com
Description: Improve your speed score on GTmetrix, Pingdom Tools and Google PageSpeed Insights by merging and minifying CSS and JavaScript files into groups, compressing HTML and other speed optimizations. 
Author: Raul Peixoto
Author URI: http://fastvelocity.com
Text Domain: fast-velocity-minify
Version: 3.1.4
License: GPL2

------------------------------------------------------------------------
This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA
*/

# Exit if accessed directly				
if (!defined('ABSPATH')){ exit(); }	

# Invalidate OPCache for current file on WP 5.5+
if(function_exists('wp_opcache_invalidate') && stripos(__FILE__, '/fvm.php') !== false) {
	wp_opcache_invalidate(__FILE__, true);
}

# info, variables, paths
$fvm_var_file = __FILE__;                                           # /home/path/plugins/pluginname/wpr.php
$fvm_var_basename = plugin_basename($fvm_var_file);                 # pluginname/wpr.php
$fvm_var_dir_path = plugin_dir_path($fvm_var_file);                 # /home/path/plugins/pluginname/
$fvm_var_url_path = plugins_url(dirname($fvm_var_basename)) . '/';  # https://example.com/wp-content/plugins/pluginname/
$fvm_var_plugin_version = get_file_data($fvm_var_file, array('Version' => 'Version'), false)['Version'];
$fvm_var_inc_dir = $fvm_var_dir_path . 'inc' . DIRECTORY_SEPARATOR;  # /home/path/plugins/pluginname/inc/
$fvm_var_inc_lib = $fvm_var_dir_path . 'libs' . DIRECTORY_SEPARATOR; # /home/path/plugins/pluginname/libs/

# global functions for backend, frontend, ajax, etc
require_once($fvm_var_inc_dir . 'common.php');
require_once($fvm_var_inc_dir . 'updates.php');

# wp-cli support
if (defined('WP_CLI') && WP_CLI) {
	require_once($fvm_var_inc_dir . 'wp-cli.php');
}

# get all options from database
$fvm_settings = fvm_get_settings();

# get cache paths and info
$fvm_cache_paths = fvm_cachepath();

# site url, domain name
$fvm_urls = array('wp_site_url'=>site_url(), 'wp_domain'=>fvm_get_domain());


# only on backend
if(is_admin()) {
	
	# admin functionality
	require_once($fvm_var_inc_dir . 'admin.php');
	require_once($fvm_var_inc_dir . 'serverinfo.php');

	# both backend and frontend, as long as user can manage options
	add_action('admin_bar_menu', 'fvm_admintoolbar', 100);
	add_action('init', 'fvm_process_cache_purge_request');
		
	# do admin stuff, as long as user can manage options
	add_action('admin_init', 'fvm_save_settings');
	add_action('admin_init', 'fvm_check_minimum_requirements');
	add_action('admin_init', 'fvm_check_misconfiguration');
	add_action('admin_init', 'fvm_update_changes');
	add_action('admin_enqueue_scripts', 'fvm_add_admin_jscss');
	add_action('admin_menu', 'fvm_add_admin_menu');
	add_action('admin_notices', 'fvm_show_admin_notice_from_transient');
	add_action('wp_ajax_fvm_get_logs', 'fvm_get_logs_callback');
		
	# purge everything
	add_action('switch_theme', 'fvm_purge_all');
	add_action('customize_save', 'fvm_purge_all');
	add_action('avada_clear_dynamic_css_cache', 'fvm_purge_all');
	add_action('upgrader_process_complete', 'fvm_purge_all');
	add_action('update_option_theme_mods_' . get_option('stylesheet'), 'fvm_purge_all');
		
}



# frontend only, any user permissions
if(!is_admin()) {
	
	# frontend functionality
	require_once($fvm_var_inc_dir . 'frontend.php');
	
	# both back and front, as long as the option is enabled
	add_action('init', 'fvm_disable_emojis');
	add_action('wp_loaded', 'fvm_ajax_optimizer');
	
	# both backend and frontend, as long as user can manage options
	add_action('admin_bar_menu', 'fvm_admintoolbar', 100);
	add_action('init', 'fvm_process_cache_purge_request');
		
	# actions for frontend only
	add_action('template_redirect', 'fvm_start_buffer', -PHP_INT_MAX);
	
}

