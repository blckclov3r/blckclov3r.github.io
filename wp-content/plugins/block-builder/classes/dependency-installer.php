<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Dependency_Installer {

	const ACTION_INSTALL = 'install';
	const ACTION_ACTIVATE = 'activate';

	public function is_plugin_installed( $plugin ) {
		$plugins = get_plugins();
		return ! empty( $plugins[ $plugin ] );
	}

	public function enqueue_installer_scripts() {
		$this->enqueue_wp_updates_scripts();
		add_action( 'admin_footer', function() {
			?>
            <script>
				jQuery( document ).ready( function($) {
					$( document ).on( 'click', '.install-now', function( e ) {
						e.preventDefault();
						var $button = $( this );
						$button.addClass( 'install-now updating-message' )
							.css({
								opacity: '0.5',
								'pointer-events': 'none',
								cursor: 'default',
							});
						wp.updates.installPlugin( {
							slug: $button.data( 'plugin-slug' ),
							success: function ( response ) {
								wp.updates.installPluginSuccess( response );
								location.replace( response.activateUrl );
							}
						} );
					} );
				} );
            </script>
			<?php
		} );
	}

	private function enqueue_wp_updates_scripts() {
		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'plugin-install' );
		wp_enqueue_script( 'updates' );
	}

	public function get_plugin_action_button( $slug, $button_label = '', $path = '' ) {
		return sprintf(
			'<a class="%s" data-plugin-slug="%s" href="%s">%s</a>',
			'button button-primary install-now',
			esc_attr( $slug ),
			esc_attr( $this->get_plugin_action_url( $slug, self::ACTION_INSTALL ) ),
			esc_html( $button_label )
		);
	}

	public function get_plugin_action_url( $slug, $action, $plugin_dir = '' ) {
		$url = '';
		if ( empty( $plugin_dir ) ) {
			$plugin_dir = $slug . '/';
		}

		switch ( $action ) {
			case self::ACTION_INSTALL:
				$url = wp_nonce_url(
					self_admin_url( 'update.php?action=' . self::ACTION_INSTALL . '-plugin&plugin=' . $slug ),
					$action . '-plugin_' . $slug
				);
				break;
			case self::ACTION_ACTIVATE:
				$slug = $plugin_dir . $slug . '.php';
				$url = wp_nonce_url(
					self_admin_url( 'plugins.php?action=' . self::ACTION_ACTIVATE . '&plugin=' . $slug ),
					$action . '-plugin_' . $slug
				);

				break;
		}
		return $url;
	}

	public function get_plugin_missing_notice( $plugin_name, $slug, $plugin_dir = '' ) {
		if ( empty( $plugin_dir ) ) {
			$plugin_dir = $slug . '/';
		}

		$plugin_file = $plugin_dir . $slug . '.php';
		$label = __( 'Activate', 'block-builder' );

		if ( ! $this->is_plugin_installed( $plugin_file ) ) {
			$label = __( 'Install', 'block-builder' ) . ' & ' . __( 'Activate', 'block-builder' );
			$this->enqueue_installer_scripts();
			$action_button = $this->get_plugin_action_button( $slug, $label . ' ' . $plugin_name );
		} else {
			//$this->enqueue_activator_scripts();
			$action_button = sprintf(
				'<a class="%s" href="%s">%s</a>',
				'button button-primary install-now',
				$this->get_plugin_action_url( $slug, self::ACTION_ACTIVATE ),
				$label . ' ' . $plugin_name
			);
		}

		$message = sprintf(
		/* translators: 1: Plugin name 2: missing plugin */
			esc_html__( 'In order to use %1$s, you need to install and activate %2$s first.', 'block-builder' ),
			'<strong>' . esc_html__( 'Elementor Blocks for Gutenberg', 'block-builder' ) . '</strong>',
			'<strong>' . $plugin_name . '</strong>'
		);

		printf( '<div class="notice notice-error is-dismissible"><p>%1$s</p><p>%2$s</p></div>', $message, $action_button );
	}
}