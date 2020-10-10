<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.feataholic.com
 * @since      1.1.8 Free
 *
 * @package    MZLDR
 * @subpackage MZLDR/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    MZLDR
 * @subpackage MZLDR/admin
 * @author     Your Name <email@example.com>
 */
class MZLDR_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @var string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @var string $version The current version of this plugin.
	 */
	private $version;

	/**
	 * The form elements class.
	 *
	 * @var string $form The form elements class.
	 */
	private $form;

	/**
	 * The Loader Model
	 *
	 * @var class $loader_model
	 */
	private $loader_model;

	/**
	 * The Impression Model
	 *
	 * @var class $impression
	 */
	private $impression_model;

	/**
	 * The Statistics Model
	 *
	 * @var class $statistics
	 */
	private $statistics_model;

	/**
	 * Loader Data
	 * 
	 * @var object
	 */
	private $loader_data;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param    string $plugin_name       The name of this plugin.
	 * @param    string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name      = $plugin_name;
		$this->version          = $version;
		$this->form             = new MZLDR_Forms();
		$this->loader_data      = null;
		$this->loader_model     = new MZLDR_Loader_Model();
		$this->impression_model = new MZLDR_Impression_Model();
		$this->statistics_model = new MZLDR_Statistics_Model();

		$this->loader    = array();
		$this->page      = isset( $_GET['page'] ) ? sanitize_key( $_GET['page'] ) : 'dashboard';
		$this->action    = isset( $_GET['action'] ) ? sanitize_key( $_GET['action'] ) : '';
		$this->loader_id = isset( $_GET['loader_id'] ) ? sanitize_key( $_GET['loader_id'] ) : '';
	}

	/**
	 * Runs first on admin init
	 *
	 * @return  void
	 */
	function admin_page_init() {
		if ( $this->page == 'maz-loader' ) {
			// delete loader
			if ( $this->action == 'delete' ) {
				if ( $this->loader_model->delete( $this->loader_id ) ) {
					$this->impression_model->deleteLoaderImpressions( $this->loader_id );
					$notice = __( 'Loader has been deleted.', 'maz-loader' );
					MZLDR_Admin_Notice::add_flash_notice( $notice, 'info' );
				} else {
					$notice = __( 'Loader couldn\'t be deleted.', 'maz-loader' );
					MZLDR_Admin_Notice::add_flash_notice( $notice, 'error' );
				}

				// redirect the user to the appropriate page
				MZLDR_Helper::customAdminRedirect( 'maz-loader-list' );
			} elseif ( $this->action == 'edit' ) {
				$where = array(
					'where' => 'WHERE id=' . $this->loader_id,
					'limit' => 'LIMIT 1',
				);
				$loader = $this->loader_model->getLoaders( $where );

				if ( ! count( $loader ) ) {
					$notice = __( 'Loader does not exist!', 'maz-loader' );
					MZLDR_Admin_Notice::add_flash_notice( $notice, 'error' );
					MZLDR_Helper::customAdminRedirect( 'maz-loader-list' );
				} else {
					$this->loader      = $loader[0];
					$_loader_data      = json_decode( $this->loader->data );
					$loader_appearance = isset( $_loader_data->appearance ) ? $_loader_data->appearance : array();
					$loader_settings   = isset( $_loader_data->settings ) ? $_loader_data->settings : array();
					$loader_custom_code   = isset( $_loader_data->custom_code ) ? $_loader_data->custom_code : array();
					$loader_publish_settings   = isset( $_loader_data->publish_settings ) ? $_loader_data->publish_settings : array();
					
					$loader_data_obj              = new \stdClass();
					$loader_data_obj->appearance  = $loader_appearance;
					$loader_data_obj->settings    = $loader_settings;
					$loader_data_obj->custom_code = $loader_custom_code;
					$loader_data_obj->publish_settings = (array) $loader_publish_settings;

					$this->loader_data = $loader_data_obj;
				}
			}
		} elseif ( $this->page == 'maz-loader-list' ) {
			// update loader publish status
			if ( $this->action == 'update-publish-status' ) {
				$new_status = (bool) isset( $_GET['new_status'] ) ? sanitize_key( $_GET['new_status'] ) : '';

				if ( in_array( (bool) $new_status, array( true, false ) ) ) {
					if ( $this->loader_model->updatePublishStatus( $this->loader_id, $new_status ) ) {
						$notice = __( 'Loader has been updated!', 'maz-loader' );
						MZLDR_Admin_Notice::add_flash_notice( $notice, 'info' );
					} else {
						$notice = __( 'Loader couldn\'t be updated!', 'maz-loader' );
						MZLDR_Admin_Notice::add_flash_notice( $notice, 'error' );
					}

					MZLDR_Helper::customAdminRedirect( 'maz-loader-list' );

				}
			}
		} elseif ( $this->page == 'maz-loader-dashboard' ) {
			$this->statistics = $this->statistics_model->getStatistics();
			$this->statistics = isset( $this->statistics[0] ) ? $this->statistics[0] : array();

			$where = array(
				'order' => 'ORDER BY id DESC',
				'limit' => 'LIMIT 10',
			);
			$this->loaders = $this->loader_model->getLoaders( $where );
		}
	}

	/**
	 * Adds the menu page to the backend
	 *
	 * @return  void
	 */
	function add_menu_pages() {
		
		$suffix = '';
		
		
		
		add_menu_page(
			__( 'MAZ Loader - Dashboard', 'maz-loader' ),
			__( 'MAZ Loader', 'maz-loader' ),
			'manage_options',
			'maz-loader-dashboard',
			array( $this, 'maz_loader_dashboard' ),
			plugins_url( 'maz-loader' . $suffix . '/media/admin/img/loader-icon-light.png' ),
			80
		);

		$page_edit_title = ($this->action == 'edit') ? __( 'Edit Loader', 'maz-loader' ) : __( 'Create New Loader', 'maz-loader' );
		
		add_submenu_page(
			'maz-loader-dashboard',
			$page_edit_title,
			__( 'Create New Loader', 'maz-loader' ),
			'manage_options',
			'maz-loader',
			array( $this, 'maz_loader_dashboard' )
		);

		add_submenu_page(
			'maz-loader-dashboard',
			__( 'View Loaders', 'maz-loader' ),
			__( 'View Loaders', 'maz-loader' ),
			'manage_options',
			'maz-loader-list',
			array( $this, 'maz_loader_dashboard' )
		);

		add_submenu_page(
			'maz-loader-dashboard',
			__( 'Settings', 'maz-loader' ),
			__( 'Settings', 'maz-loader' ),
			'manage_options',
			'maz-loader-settings',
			array( $this, 'maz_loader_dashboard' )
		);

		global $submenu;
		$submenu['maz-loader-dashboard'][] = array( __('Documentation', 'maz-loader'), 'manage_options', esc_url(MZLDR_Constants::getDocumentationURL()) );
		$submenu['maz-loader-dashboard'][] = array( __('Support', 'maz-loader'), 'manage_options', esc_url(MZLDR_Constants::getSupportURL()) );
		
		
		$submenu['maz-loader-dashboard'][] = array( '<span style="color:#fff176;">' . __( 'Upgrade to Pro', 'maz-loader' ) . '</span>', 'manage_options', esc_url(MZLDR_Constants::getUpgradeURL()) );
		
	}

	/**
	 * Redirect to Documentation
	 * 
	 * @return  void
	 */
	function maz_loader_documentation(){
		wp_redirect( MZLDR_Constants::getDocumentationURL() );
		exit;    
	}

	/**
	 * Loads the MAZ Loader Admin Panel
	 *
	 * @return void
	 */
	public function maz_loader_dashboard() {
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/maz-loader-admin.php';
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @return  void
	 */
	public function enqueue_styles() {
		global $pagenow;

		if (!is_admin())
		{
			return;
		}
		
		wp_enqueue_style( $this->plugin_name . '-mazloader-admin-main', MZLDR_ADMIN_MEDIA_URL . 'css/mazloader-admin-main.css', array(), $this->version, 'all' );
		
		if ( 'admin.php' != $pagenow ) {
			return;
		}

		// Load Google Font
		wp_enqueue_style( $this->plugin_name . '-mazloader-google-font', 'https://fonts.googleapis.com/css?family=Poppins:400,700&display=swap', false );

		// Load the color picker CSS library
		wp_enqueue_style( 'wp-color-picker' );

		// used by WordPress color picker  ( wpColorPicker() )
		wp_localize_script( 'wp-color-picker', 'wpColorPickerL10n',
			[
				'clear'            => __('Reset', 'maz-loader'),
				'clearAriaLabel'   => __('Reset Color', 'maz-loader'),
				'defaultString'    => __('Default', 'maz-loader'),
				'defaultAriaLabel' => __('Select default color', 'maz-loader'),
				'pick'             => __('Select color', 'maz-loader'),
				'defaultLabel'     => __('Color value', 'maz-loader'),
			]
		);

		
		
		// maz loader CSS library
		wp_enqueue_style( $this->plugin_name . '-mazloader-public', MZLDR_PUBLIC_MEDIA_URL . 'css/mazloader.css', array(), $this->version, 'all' );

		// backend files
		wp_enqueue_style( $this->plugin_name . '-flexboxgrid', MZLDR_ADMIN_MEDIA_URL . 'css/flexboxgrid.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @return  void
	 */
	public function enqueue_scripts() {
		global $pagenow;

		wp_enqueue_script( $this->plugin_name . '-review-handler', MZLDR_ADMIN_MEDIA_URL . 'js/mazloader-review-handler.js', array( 'wp-color-picker' ), $this->version, true );

		$this->passMAZLoaderJSObject();

		if ( 'admin.php' != $pagenow && 'plugins.php' != $pagenow ) {
			return;
		}

		wp_enqueue_media();

		

		wp_enqueue_script( $this->plugin_name . '-front-helper', MZLDR_PUBLIC_MEDIA_URL . 'js/src/mazloader/mazloader-front-helper.js', array( 'wp-color-picker' ), $this->version, true );

		wp_enqueue_script( $this->plugin_name . '-mazloader', MZLDR_ADMIN_MEDIA_URL . 'js/mazloader.js', array( 'wp-color-picker' ), $this->version, false );

		wp_enqueue_script( $this->plugin_name . '-wp-color-picker-alpha', MZLDR_ADMIN_MEDIA_URL . 'js/wp-color-picker-alpha.min.js', array( 'wp-color-picker' ), $this->version, true );

		$this->passLanguageStrings();
	}

	/**
	 * Send some language strings to the front end to use in JS library
	 *
	 * @return  void
	 */
	private function passLanguageStrings() {
		$data = [
			'duplicate_warning'     => __( 'You are about to duplicate this item. Proceed?', 'maz-loader' ),
			'delete_warning'        => __( 'You are about to delete this item. Proceed?', 'maz-loader' ),
			'media_uploader_title'  => __( 'Choose Image', 'maz-loader' ),
			'media_uploader_button' => __( 'Choose Image', 'maz-loader' ),
			'running'               => __( 'Running...', 'maz-loader' ),
			'shortcode_alert'       => __( 'The Loader\'s shortcode has been copied to your clipboard.', 'maz-loader' ),
		];

		wp_localize_script( $this->plugin_name . '-mazloader', 'mzldr_builder_vars', $data );
	}

	/**
	 * Send the MAZ Loader JS Object
	 *
	 * @return  void
	 */
	private function passMAZLoaderJSObject() {
		$mzldr_rate_reminder_nonce = wp_create_nonce( 'mzldr_rate_reminder_nonce' );
		
		$data = [
			'ADMIN_URL'        => MZLDR_ADMIN_URL,
			'ADMIN_MEDIA_URL'  => MZLDR_ADMIN_MEDIA_URL,
			'PUBLIC_URL'       => MZLDR_PUBLIC_URL,
			'PUBLIC_MEDIA_URL' => MZLDR_PUBLIC_MEDIA_URL,
			'logging_enabled'  => false,
			'editing'          => ( $this->action == 'edit' ) ? 'true' : 'false',
			'nonce'			   => $mzldr_rate_reminder_nonce,
			'ajax_url' 		   => admin_url( 'admin-ajax.php' )
		];

		wp_localize_script( $this->plugin_name . '-review-handler', 'mzldr_js_object', $data );
	}

	
	public function plugin_action_links($links) {
		$links = array_merge( $links, array(
			'<a href="' . MZLDR_Constants::getUpgradeURL() . '" class="mazloader-go-pro-link">' . __( 'Go Pro', 'maz-loader' ) . '</a>'
		) );
			
		return $links;
	}
	


	/**
	 * Set transients on plugin activation.
	 *
	 * @return  void
	 */
	public function set_rate_reminder() {
		if( ! get_transient( 'mzldr_rate_reminder_deleted' ) && ! get_transient( 'mzldr_rate_reminder' ) ) {
			$date = new \DateTime('2020-03-10');
			set_transient( 'mzldr_rate_reminder', $date->format( 'Y-m-d' ) );
		}
	}

	/**
	 * Set reminder transients on plugins update.
	 *
	 * @return  void
	 */
	function set_update_rate_reminder( $upgrader_object, $options ) {
		if ( $options['action'] == 'update' && $options['type'] == 'plugin' ) {
			if( ! get_transient( 'mzldr_rate_reminder_deleted' ) ) {
				$date = new \DateTime('2020-03-10');
				set_transient( 'mzldr_rate_reminder', $date->format( 'Y-m-d' ) );
			}
		}
	}

	/**
	 * Show reminders.
	 *
	 * @return  void
	 */
	function show_rate_reminder() {
		if( get_transient( 'mzldr_rate_reminder' ) ) {

			$start_date = new \DateTime( get_transient( 'mzldr_rate_reminder' ) );
			$start_date->add( new \DateInterval( 'P7D' ) );

			$current_date = new \DateTime();

			if( $current_date >= $start_date ) {
				$image = sprintf( esc_html( '%1$s' ), '<img src="https://www.feataholic.com/wp-content/uploads/2020/01/logo.svg" width="100" alt="MAZ Loader Plugin Avatar" />' );
				$message = sprintf( esc_html__( '%1$s Thank you for using our plugin, MAZ Loader! %2$s You rock! Could you please do me a BIG favor and give it a 5-star rating on WordPress? This will help me spread the word and boost my motivation. %3$s - %4$s Feataholic %5$s %6$s', 'maz-loader' ), '<b>', '&ndash;', '</br>', '<em>', '</em>', '</b>' );
				$message .= sprintf( esc_html__( '%1$s %2$s Yes, take me there! %3$s %4$s Remind me later %3$s %5$s I\'ve already done this  %3$s %6$s', 'maz-loader'  ), '<span>', '<a class="button button-primary mzldr-clear-rate-reminder" href="https://wordpress.org/support/plugin/maz-loader/reviews/?filter=5" target="_blank">', '</a>', '<a class="button mzldr-ask-later" href="#">', '<a class="button mzldr-delete-rate-reminder" href="#">', '</span>' );
				printf( '<div class="notice mzldr-review-reminder"><div class="mzldr-review-author-avatar">%1$s</div><div class="mzldr-review-message">%2$s</div></div>', wp_kses_post( $image ), wp_kses_post( $message ) );
			}
		}
	}

	/**
	 * Delete or update the rate reminder admin notice.
	 *
	 * @return  void
	 */
	function mzldr_update_rate_reminder() {
		check_ajax_referer( 'mzldr_rate_reminder_nonce' );

		if( isset( $_POST['update'] ) ) {
			if( $_POST['update'] === 'mzldr_delete_rate_reminder' ) {
				
				delete_transient( 'mzldr_rate_reminder' );

				if( ! get_transient( 'mzldr_rate_reminder' ) && set_transient( 'mzldr_rate_reminder_deleted', 'No reminder to show' ) ) {
					$response = [
						'error' => false,
					];
				} else {
					$response = [
						'error' => true,
					];
				}
			}

			if( $_POST['update'] === 'mzldr_ask_later' ) {
				$date = new \DateTime();
				$date->add( new \DateInterval( 'P7D' ) );
				$date_format = $date->format( 'Y-m-d' );

				delete_transient( 'mzldr_rate_reminder' );

				if( set_transient( 'mzldr_rate_reminder', $date_format ) ) {
					$response = [
						'error' => false,
					];
				} else {
					$response = [
						'error' => true,
						'error_type' => set_transient( 'mzldr_rate_reminder', $date_format ),
					];
				}
			}

			wp_send_json( $response );
		}
	}
}
