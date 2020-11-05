<?php
/**
 * Plugin Name:       MAZ Loader
 * Plugin URI:        https://www.feataholic.com/
 * Description:       MAZ Loader is a powerful and easy to use Preloader builder that makes your visitors a delight to wait for your website to finish loading.
 * Version:           1.1.8
 * Author:            Feataholic
 * Author URI:        https://www.feataholic.com/
 * Text Domain:       maz-loader
 * Domain Path:       /languages
 * License: 		  GNU General Public License v3.0
 * License URI: 	  http://www.gnu.org/licenses/gpl-3.0.html
 */
// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

if ( ! class_exists('MZLDR_Helper') ) {
	require plugin_dir_path( __FILE__ ) . 'includes/class-maz-loader-helper.php';
}

// MAZ Loader Store URL
if ( ! defined( 'MZLDR_SL_STORE_URL' ) ) {
	define( 'MZLDR_SL_STORE_URL', 'https://www.feataholic.com/' );
}

// MAZ Loader EDD Item
if ( ! defined( 'MZLDR_SL_ITEM_ID' ) ) {
	define( 'MZLDR_SL_ITEM_ID', 1048 );
}

/**
 * Current plugin version.
 */
if ( ! defined( 'MZLDR_VERSION' ) ) {
	define( 'MZLDR_VERSION', MZLDR_Helper::getVersion() );
}

/**
 * Plugin basename.
 */
if ( ! defined( 'MZLDR_PLG_BASENAME' ) ) {
	define( 'MZLDR_PLG_BASENAME', plugin_basename( __FILE__ ) );
}

/**
 * The admin directory path
 */
if ( ! defined( 'MZLDR_ADMIN_PATH' ) ) {
	define( 'MZLDR_ADMIN_PATH', plugin_dir_path( __FILE__ ) . 'admin/');
}

/**
 * The admin directory URL
 */
if ( ! defined( 'MZLDR_ADMIN_URL' ) ) {
	define( 'MZLDR_ADMIN_URL', plugin_dir_url( __FILE__ ) . 'admin/');
}

/**
 * The admin media URL
 */
if ( ! defined( 'MZLDR_ADMIN_MEDIA_URL' ) ) {
	define( 'MZLDR_ADMIN_MEDIA_URL', plugin_dir_url( __FILE__ ) . 'media/admin/');
}

/**
 * The public directory path
 */
if ( ! defined( 'MZLDR_PUBLIC_PATH' ) ) {
	define( 'MZLDR_PUBLIC_PATH', plugin_dir_path( __FILE__ ) . 'public/');
}

/**
 * The public directory URL
 */
if ( ! defined( 'MZLDR_PUBLIC_URL' ) ) {
	define( 'MZLDR_PUBLIC_URL', plugin_dir_url( __FILE__ ) . 'public/');
}

/**
 * The public media URL
 */
if ( ! defined( 'MZLDR_PUBLIC_MEDIA_URL' ) ) {
	define( 'MZLDR_PUBLIC_MEDIA_URL', plugin_dir_url( __FILE__ ) . 'media/public/');
}




/**
 * Free Status
 */
if (!defined( 'MZLDR_STATUS' )) {
	define ( 'MZLDR_STATUS' , 0);
}


/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-maz-loader-activator.php
 */
register_activation_hook( __FILE__, function() {
	if (!class_exists('MZLDR_Activator')) {
		require_once plugin_dir_path( __FILE__ ) . 'includes/class-maz-loader-activator.php';
	}
	MZLDR_Activator::activate();
} );

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-maz-loader-deactivator.php
 */
register_deactivation_hook( __FILE__, function() {
	if (!class_exists('MZLDR_Deactivator')) {
		require_once plugin_dir_path( __FILE__ ) . 'includes/class-maz-loader-deactivator.php';
	}
	MZLDR_Deactivator::deactivate();
} );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
if (!class_exists('MZLDR')) {
	require plugin_dir_path( __FILE__ ) . 'includes/class-maz-loader.php';
}

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
$plugin = new MZLDR();
$plugin->run();