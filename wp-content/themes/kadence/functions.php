<?php
/**
 * Kadence functions and definitions
 *
 * This file must be parseable by PHP 5.2.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package kadence
 */

define( 'KADENCE_VERSION', '0.9.9' );
define( 'KADENCE_MINIMUM_WP_VERSION', '5.2' );
define( 'KADENCE_MINIMUM_PHP_VERSION', '7.0' );

// Bail if requirements are not met.
if ( version_compare( $GLOBALS['wp_version'], KADENCE_MINIMUM_WP_VERSION, '<' ) || version_compare( phpversion(), KADENCE_MINIMUM_PHP_VERSION, '<' ) ) {
	require get_template_directory() . '/inc/back-compat.php';
	return;
}

// Include WordPress shims.
require get_template_directory() . '/inc/wordpress-shims.php';

// Load the `kadence()` entry point function.
require get_template_directory() . '/inc/class-theme.php';

// Load the `kadence()` entry point function.
require get_template_directory() . '/inc/functions.php';

// Initialize the theme.
call_user_func( 'Kadence\kadence' );

require 'theme_update_check.php';
$kadence_theme_updater = new ThemeUpdateChecker(
	'kadence',
	'https://kernl.us/api/v1/theme-updates/5e6bc9ee9c7bfc366d501241/'
);
