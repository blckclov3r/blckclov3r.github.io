<?php
namespace PowerpackElementsLite\Classes;

/**
 * Handles logic for the admin settings page.
 *
 * @since 1.0.0
 */
final class PP_Admin_Settings {
    /**
	 * Holds any errors that may arise from
	 * saving admin settings.
	 *
	 * @since 1.0.0
	 * @var array $errors
	 */
	static public $errors = array();

	static public $settings = array();

	/**
	 * Initializes the admin settings.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	static public function init()
	{
		add_action( 'plugins_loaded', __CLASS__ . '::init_hooks' );
	}

    /**
	 * Adds the admin menu and enqueues CSS/JS if we are on
	 * the plugin's admin settings page.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	static public function init_hooks()
	{
		if ( ! is_admin() ) {
			return;
		}

        add_action( 'admin_menu',           __CLASS__ . '::menu', 601 );

		if ( isset( $_REQUEST['page'] ) && 'powerpack-settings' == $_REQUEST['page'] ) {
            //add_action( 'admin_enqueue_scripts', __CLASS__ . '::styles_scripts' );
			self::save();
			self::reset_settings();
		}
	}

    /**
	 * Enqueues the needed CSS/JS for the builder's admin settings page.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	static public function styles_scripts()
	{
		// Styles
		//wp_enqueue_style( 'pp-admin-settings', POWERPACK_ELEMENTS_LITE_URL . 'assets/css/admin-settings.css', array(), POWERPACK_ELEMENTS_LITE_VER );
	}

	/**
	 * Get settings.
	 *
	 * @since 1.0.0
	 * @return array
	 */
	static public function get_settings()
	{
		$default_settings = array(
			'plugin_name'       => '',
            'plugin_desc'       => '',
            'plugin_author'     => '',
            'plugin_uri'        => '',
            'admin_label'       => '',
            'support_link'      => '',
            'hide_support'      => 'off',
            'hide_wl_settings'  => 'off',
			'hide_plugin'       => 'off',
			'google_map_api'	=> '',
		);

		$settings = self::get_option( 'pp_elementor_settings', true );

		if ( ! is_array( $settings ) || empty( $settings ) ) {
			return $default_settings;
		}

		if ( is_array( $settings ) && ! empty( $settings ) ) {
			return array_merge( $default_settings, $settings );
		}
	}

	/**
	 * Get admin label from settings.
	 *
	 * @since 1.0.0
	 * @return string
	 */
	static public function get_admin_label()
	{
	    return 'PowerPack';
	}

	/**
	 * Renders the update message.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	static public function render_update_message()
	{
		if ( ! empty( self::$errors ) ) {
			foreach ( self::$errors as $message ) {
				echo '<div class="error"><p>' . esc_html__( $message, 'powerpack' ) . '</p></div>';
			}
		}
		else if ( ! empty( $_POST ) && ! isset( $_POST['email'] ) ) {
			echo '<div class="updated"><p>' . esc_html__( 'Settings updated!', 'powerpack' ) . '</p></div>';
		}
	}

	/**
	 * Adds an error message to be rendered.
	 *
	 * @since 1.0.0
	 * @param string $message The error message to add.
	 * @return void
	 */
	static public function add_error( $message )
	{
		self::$errors[] = $message;
	}

    /**
	 * Renders the admin settings menu.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	static public function menu()
	{
		if ( is_main_site() || ! is_multisite() ) {

			$admin_label = self::get_admin_label();

			if ( current_user_can( 'delete_users' ) ) {

				$title = $admin_label;
				$cap   = 'delete_users';
				$slug  = 'powerpack-settings';
				$func  = __CLASS__ . '::render';

				add_submenu_page( 'elementor', $title, $title, $cap, $slug, $func );
			}
		}
	}

    static public function render()
    {
		include POWERPACK_ELEMENTS_LITE_PATH . 'includes/admin/admin-settings.php';
    }

	/**
	 * Renders the action for a form.
	 *
	 * @since 1.0.0
	 * @param string $type The type of form being rendered.
	 * @return void
	 */
	static public function get_form_action( $type = '' )
	{
		return admin_url( '/admin.php?page=powerpack-settings' . $type );
	}

	/**
	 * Returns an option from the database for
	 * the admin settings page.
	 *
	 * @since 1.0.0
	 * @param string $key The option key.
	 * @return mixed
	 */
    static public function get_option( $key, $network_override = true, $default = null )
    {
    	if ( is_network_admin() ) {
    		$value = get_site_option( $key );
    	}
        elseif ( ! $network_override && is_multisite() ) {
            $value = get_site_option( $key );
        }
        elseif ( $network_override && is_multisite() ) {
            $value = get_option( $key );
            $value = ( false === $value || (is_array($value) && in_array('disabled', $value) && get_option('pp_override_ms') != 1) ) ? get_site_option( $key ) : $value;
        }
        else {
    		$value = get_option( $key );
		}
		
		if ( empty( $value ) && ! is_null( $default ) ) {
			$value = $default;
		}

        return $value;
    }

    /**
	 * Updates an option from the admin settings page.
	 *
	 * @since 1.0.0
	 * @param string $key The option key.
	 * @param mixed $value The value to update.
	 * @return mixed
	 */
    static public function update_option( $key, $value, $network_override = true )
    {
    	if ( is_network_admin() ) {
    		update_site_option( $key, $value );
    	}
        // Delete the option if network overrides are allowed and the override checkbox isn't checked.
		else if ( $network_override && is_multisite() && ! isset( $_POST['pp_override_ms'] ) ) {
			delete_option( $key );
		}
        else {
    		update_option( $key, $value );
    	}
    }

    /**
	 * Delete an option from the admin settings page.
	 *
	 * @since 1.0.0
	 * @param string $key The option key.
	 * @param mixed $value The value to delete.
	 * @return mixed
	 */
    static public function delete_option( $key )
    {
    	if ( is_network_admin() ) {
    		delete_site_option( $key );
    	} else {
    		delete_option( $key );
    	}
	}

    static public function save()
    {
		// Only admins can save settings.
		if ( ! current_user_can('manage_options') ) {
			return;
		}

		if ( ! isset( $_POST['pp-modules-settings-nonce'] ) || ! wp_verify_nonce( $_POST['pp-modules-settings-nonce'], 'pp-modules-settings' ) ) {
            return;
		}

		self::save_modules();
		self::save_tracking();

		do_action( 'pp_admin_after_settings_saved' );
    }

	static private function save_modules()
	{
		if ( isset( $_POST['pp_enabled_modules'] ) ) {
			update_site_option( 'pp_elementor_modules', $_POST['pp_enabled_modules'] );
		} else {
			update_site_option( 'pp_elementor_modules', 'disabled' );
		}

		if ( isset( $_POST['pp_enabled_extensions'] ) ) {
			update_site_option( 'pp_elementor_extensions', $_POST['pp_enabled_extensions'] );
		} else {
			update_site_option( 'pp_elementor_extensions', 'disabled' );
		}
	}

	static private function save_tracking() {
		if ( isset( $_POST['pp_allowed_tracking'] ) ) {
			self::update_option( 'pp_allowed_tracking', sanitize_text_field( $_POST['pp_allowed_tracking'] ), true );
		} else {
			self::delete_option( 'pp_allowed_tracking' );
		}
	}

	static public function reset_settings()
	{
		if ( isset( $_GET['reset_modules'] ) ) {
			delete_site_option( 'pp_elementor_modules' );
			self::$errors[] = esc_html__('Modules settings updated!', 'powerpack');
		}
		
		if ( isset( $_GET['reset_extensions'] ) ) {
			delete_site_option( 'pp_elementor_extensions' );
			self::$errors[] = esc_html__('Extension settings updated!', 'powerpack');
		}
	}
}

PP_Admin_Settings::init();
