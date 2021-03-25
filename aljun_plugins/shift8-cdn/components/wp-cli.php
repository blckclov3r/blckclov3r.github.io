<?php
/**
 * Shift8 CDN WP-Cli Commands
 *
 * Definition of WP-CLI commands to interact with the Shift8 CDN plugin
 *
 */

if ( !defined( 'ABSPATH' ) ) {
    die();
}

class WDS_CLI {

	/**
	 * Enable the plugin
	 *
	 * @since  1.47
	 * @author Shift8 Web
	 */
	public function enable() {
		// Enable
		if ( get_option('shift8_cdn_enabled') && get_option('shift8_cdn_enabled') === 'off') {
			update_option('shift8_cdn_enabled', 'on');
			WP_CLI::line( 'Shift8 CDN has been enabled' );
		} else {
			WP_CLI::line( 'Shift8 CDN is either not installed or not disabled - doing nothing.' );
		}
	}

	/**
	 * Disable the plugin
	 *
	 * @since  1.47
	 * @author Shift8 Web
	 */
	public function disable() {
		// Enable
		if ( get_option('shift8_cdn_enabled') && get_option('shift8_cdn_enabled') === 'on') {
			update_option('shift8_cdn_enabled', 'off');
			WP_CLI::line( 'Shift8 CDN has been disabled' );
		} else {
			WP_CLI::line( 'Shift8 CDN is either not installed or not enabled - doing nothing.' );
		}
	}

	/**
	 * Disable the plugin
	 *
	 * @since  1.47
	 * @author Shift8 Web
	 */
	public function flush() {
		// Flush cache
		if ( get_option('shift8_cdn_enabled') && get_option('shift8_cdn_enabled') === 'on') {
			$cdn_url = esc_attr(get_option('shift8_cdn_url'));
		    $cdn_api = esc_attr(get_option('shift8_cdn_api'));

		    // Set headers for WP Remote post
		    $headers = array(
		        'Content-type: application/json',
		    );
			
			$response = wp_remote_get( S8CDN_API . '/api/purge',
                array(
                    'method' => 'POST',
                    'headers' => $headers,
                    'httpversion' => '1.1',
                    'timeout' => '45',
                    'blocking' => true,
                    'body' => array(
                        'url' => $cdn_url,
                        'api' => $cdn_api
                    ),
                )
            );
            if (is_array($response) && $response['response']['code'] == '200' && !json_decode($response['body'])->error) {
				$response_message = esc_attr(json_decode($response['body'])->response);
				WP_CLI::line( 'Shift8 CDN cache purge has been submitted : ' . $response_message );
			} else {
				if (is_wp_error($response)) {
                	WP_CLI::line('Error : ' . $response->get_error_message());
            	} else {
                WP_CLI::line('Unknown error');
            	}
			}
		} else {
			WP_CLI::line( 'Shift8 CDN is either not installed or not enabled - doing nothing.' );
		}
	}
}

/**
 * Registers our command when cli get's initialized.
 *
 * @since  1.47
 * @author Shift8 Web
 */
if ( defined( 'WP_CLI' ) && WP_CLI ) {
	WP_CLI::add_command( 'shift8cdn', 'WDS_CLI' );
}