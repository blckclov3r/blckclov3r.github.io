<?php
/**
 * Shift8 CDN Define rules
 *
 * Defined rules used throughout the plugin operations
 *
 */

if ( !defined( 'ABSPATH' ) ) {
    die();
}

define( 'S8CDN_FILE', 'shift8-cdn/shift8-cdn.php' );

if ( !defined( 'S8CDN_DIR' ) )
    define( 'S8CDN_DIR', realpath( dirname( __FILE__ ) ) );

if ( !defined( 'S8CDN_TEST_README_URL' ) )
	define( 'S8CDN_TEST_README_URL', WP_PLUGIN_URL . '/' . dirname( S8CDN_FILE ) . '/test/test.png');

define( 'S8CDN_API' , 'https://shift8cdn.com');
define( 'S8CDN_SUFFIX_PAID', '.wpcdn.shift8cdn.com');
define( 'S8CDN_SUFFIX', '.cdn.shift8web.ca');
define( 'S8CDN_SUFFIX_SECOND', '.cdn.shift8web.com');
define( 'S8CDN_PAID_CHECK', 'shift8_cdn_check');
