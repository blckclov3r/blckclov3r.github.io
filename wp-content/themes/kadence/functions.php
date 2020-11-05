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

define( 'KADENCE_VERSION', '0.8.15' );
define( 'KADENCE_MINIMUM_WP_VERSION', '5.2' );
define( 'KADENCE_MINIMUM_PHP_VERSION', '7.0' );

// Bail if requirements are not met.
if ( version_compare( $GLOBALS['wp_version'], KADENCE_MINIMUM_WP_VERSION, '<' ) || version_compare( phpversion(), KADENCE_MINIMUM_PHP_VERSION, '<' ) ) {
	require get_template_directory() . '/inc/back-compat.php';
	return;
}
/**
 * For now make sure Gutenberg plugin is active else present a warning.
 */
function kadence_theme_is_gutenberg_active() {
	$gutenberg = false;
	$wpversion = false;
	if ( version_compare( (float) $GLOBALS['wp_version'], 5.4, '>=' ) ) {
		$wpversion = true;
	}
	if ( defined( 'GUTENBERG_VERSION' ) ) {
		if ( version_compare( GUTENBERG_VERSION, 7.6, '>=' ) ) {
			$gutenberg = true;
		}
	}

	if ( ! $gutenberg && ! $wpversion ) {
		add_action( 'admin_notices', 'kadence_theme_update_gutenberg_notice' );
	}
}
add_action( 'admin_init', 'kadence_theme_is_gutenberg_active' );
/**
 * For now add a warning about needing to install Gutenberg Plugin
 */
function kadence_theme_update_gutenberg_notice() {
	?>
	<div class="updated error">
		<h3 class="kt-notice-title"><?php echo esc_html__( 'Thanks for choosing the Kadence Theme', 'kadence' ); ?></h3>
		<p class="kt-notice-description"><?php echo esc_html__( 'This theme relies on the latest code from the WordPress Block Editor, please update WordPress to 5.4 to make sure all the features work.', 'kadence' ); ?></p>
	</div>
	<?php
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
