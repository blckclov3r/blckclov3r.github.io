<?php

/*
Plugin Name: Portfolio Gallery
Plugin URI: https://portfoliohub.io
Description: Coming up with the most searchable and creative wordpress portfolio gallery plugin.
Version: 1.1.2
Author: portfoliohub
Author URI: https://portfoliohub.io/
License: GPLv2 or later
Text Domain: pfhub_portfolio
Domain Path: /languages
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

define('PFHUB_PORTFOLIO_VERSION', '1.1.2');
define('PFHUB_PORTFOLIO_PLUGIN_PATH', dirname(__FILE__));
define('PFHUB_PORTFOLIO_PLUGIN_URL', plugin_dir_url(__FILE__));
define('PFHUB_PORTFOLIO_IMAGES_URL', untrailingslashit(PFHUB_PORTFOLIO_PLUGIN_URL . 'assets/images/' ));
define('PFHUB_PORTFOLIO_TEMPLATES_PATH', PFHUB_PORTFOLIO_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'views');
define("PFHUB_PORTFOLIO_ADM_PATH", str_replace( get_bloginfo( 'url' ) . '/', ABSPATH, get_admin_url() ));



require_once(__DIR__ . DIRECTORY_SEPARATOR . "lib" . DIRECTORY_SEPARATOR . "autoload.php");

$GLOBALS['pfhub_portfolio'] = new \PfhubPortfolio\PfhubPortfolio();
