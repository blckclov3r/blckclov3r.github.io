<?php

/**
 * Admin Notices
 *
 * @link       https://www.feataholic.com
 * @since      1.1.8 Free
 *
 * @package    MZLDR
 * @subpackage MZLDR/admin
 */

/**
 * Admin Notices
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    MZLDR
 * @subpackage MZLDR/admin
 * @author     Your Name <email@example.com>
 */
class MZLDR_Admin_Notice {

	/**
	 * Add a flash notice to {prefix}options table until a full page refresh is done
	 *
	 * @param string  $notice our notice message
	 * @param string  $type This can be "info", "warning", "error" or "success", "warning" as default
	 * @param boolean $dismissible set this to TRUE to add is-dismissible functionality to your notice
	 * @return void
	 */
	public static function add_flash_notice( $notice = '', $type = 'warning', $dismissible = true ) {
		// Here we return the notices saved on our option, if there are not notices, then an empty array is returned
		$notices = get_option( 'mzldr_flash_notices', array() );

		$dismissible_text = ( $dismissible ) ? 'is-dismissible' : '';

		// We add our new notice.
		array_push(
			$notices,
			array(
				'notice'      => $notice,
				'type'        => $type,
				'dismissible' => 'is-dismissible', // $dismissible_text
			)
		);

		// Then we update the option with our notices array
		update_option( 'mzldr_flash_notices', $notices );
	}

	/**
	 * Function executed when the 'admin_notices' action is called, here we check if there are notices on
	 * our database and display them, after that, we remove the option to prevent notices being displayed forever.
	 *
	 * @return void
	 */
	public function maz_loader_admin_notice_handle() {
		$notices = get_option( 'mzldr_flash_notices', array() );

		// Iterate through our notices to be displayed and print them.
		foreach ( $notices as $notice ) {
			printf(
				'<div class="mzldr-notice-wrapper"><div class="mzldr-notice notice notice-%1$s %2$s"><p>%3$s</p></div></div>',
				$notice['type'],
				$notice['dismissible'],
				$notice['notice']
			);
		}

		// Now we reset our options to prevent notices being displayed forever.
		if ( ! empty( $notices ) ) {
			delete_option( 'mzldr_flash_notices', array() );
		}
	}

}
