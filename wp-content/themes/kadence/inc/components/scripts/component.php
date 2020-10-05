<?php
/**
 * Kadence\Scripts\Component class
 *
 * @package kadence
 */

namespace Kadence\Scripts;

use Kadence\Component_Interface;
use function Kadence\kadence;
use WP_Post;
use function add_action;
use function add_filter;
use function wp_enqueue_script;
use function get_theme_file_uri;
use function get_theme_file_path;
use function wp_script_add_data;
use function wp_localize_script;

/**
 * Class for adding scripts to the front end.
 */
class Component implements Component_Interface {

	/**
	 * Gets the unique identifier for the theme component.
	 *
	 * @return string Component slug.
	 */
	public function get_slug() : string {
		return 'scripts';
	}

	/**
	 * Adds the action and filter hooks to integrate with WordPress.
	 */
	public function initialize() {
		add_action( 'wp_enqueue_scripts', array( $this, 'action_enqueue_scripts' ) );
		add_action( 'wp_print_footer_scripts', array( $this, 'action_print_skip_link_focus_fix' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'ie_11_support_scripts' ), 60 );
	}
	/**
	 * Add some very basic support for IE11
	 */
	public function ie_11_support_scripts() {
		if ( apply_filters( 'kadence_add_ie11_support', false ) || kadence()->option( 'ie11_basic_support' ) ) {
			wp_enqueue_style( 'kadence-ie11', get_theme_file_uri( '/assets/css/ie.min.css' ), array(), KADENCE_VERSION );
			wp_enqueue_script(
				'kadence-css-vars-poly',
				get_theme_file_uri( '/assets/js/css-vars-ponyfill.min.js' ),
				array(),
				KADENCE_VERSION,
				true
			);
			wp_script_add_data( 'kadence-css-vars-poly', 'async', true );
			wp_script_add_data( 'kadence-css-vars-poly', 'precache', true );
			wp_enqueue_script(
				'kadence-ie11',
				get_theme_file_uri( '/assets/js/ie.min.js' ),
				array(),
				KADENCE_VERSION,
				true
			);
			wp_script_add_data( 'kadence-ie11', 'async', true );
			wp_script_add_data( 'kadence-ie11', 'precache', true );
		}
	}
	/**
	 * Enqueues a script that improves navigation menu accessibility as well as sticky header etc.
	 */
	public function action_enqueue_scripts() {

		// If the AMP plugin is active, return early.
		if ( kadence()->is_amp() ) {
			return;
		}

		// Enqueue the slide script.
		wp_register_script(
			'kadence-slide',
			get_theme_file_uri( '/assets/js/src/tiny-slider.js' ),
			array(),
			KADENCE_VERSION,
			true
		);
		wp_script_add_data( 'kadence-slide', 'async', true );
		wp_script_add_data( 'kadence-slide', 'precache', true );
		// Enqueue the slide script.
		wp_register_script(
			'kadence-slide-init',
			get_theme_file_uri( '/assets/js/slide-init.min.js' ),
			array( 'kadence-slide', 'kadence-navigation' ),
			KADENCE_VERSION,
			true
		);
		wp_script_add_data( 'kadence-slide-init', 'async', true );
		wp_script_add_data( 'kadence-slide-init', 'precache', true );
		// Main js file.
		wp_enqueue_script(
			'kadence-navigation',
			get_theme_file_uri( '/assets/js/navigation.min.js' ),
			array(),
			KADENCE_VERSION,
			true
		);
		wp_script_add_data( 'kadence-navigation', 'async', true );
		wp_script_add_data( 'kadence-navigation', 'precache', true );
		wp_localize_script(
			'kadence-navigation',
			'kadenceConfig',
			array(
				'screenReader' => array(
					'expand'   => __( 'Expand child menu', 'kadence' ),
					'collapse' => __( 'Collapse child menu', 'kadence' ),
				),
				'breakPoints' => array(
					'desktop' => 1024,
					'tablet' => 768,
				),
			)
		);
	}

	/**
	 * Prints an inline script to fix skip link focus in IE11.
	 *
	 * The script is not enqueued because it is tiny and because it is only for IE11,
	 * thus it does not warrant having an entire dedicated blocking script being loaded.
	 *
	 * Since it will never need to be changed, it is simply printed in its minified version.
	 *
	 * @link https://git.io/vWdr2
	 */
	public function action_print_skip_link_focus_fix() {

		// If the AMP plugin is active, return early.
		if ( kadence()->is_amp() ) {
			return;
		}

		// Print the minified script.
		?>
		<script>
		/(trident|msie)/i.test(navigator.userAgent)&&document.getElementById&&window.addEventListener&&window.addEventListener("hashchange",function(){var t,e=location.hash.substring(1);/^[A-z0-9_-]+$/.test(e)&&(t=document.getElementById(e))&&(/^(?:a|select|input|button|textarea)$/i.test(t.tagName)||(t.tabIndex=-1),t.focus())},!1);
		</script>
		<?php
	}
}
