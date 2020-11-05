<?php
/**
 * Build Welcome Page with settings.
 *
 * @package Kadence
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * Build Welcome Page class
 *
 * @category class
 */
class Kadence_Dashboard_Settings {

	/**
	 * Settings of this class
	 *
	 * @var array
	 */
	public static $settings = array();

	/**
	 * Instance of this class
	 *
	 * @var null
	 */
	private static $instance = null;

	/**
	 * Instance Control
	 */
	public static function get_instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}
	/**
	 * Class Constructor.
	 */
	public function __construct() {
		// only load if admin.
		if ( is_admin() ) {
			add_action( 'admin_menu', array( $this, 'add_menu' ) );
		}
		add_action( 'init', array( $this, 'load_api_settings' ) );
	}
	/**
	 * Redirect to the settings page on activation.
	 *
	 * @param string $key setting key.
	 */
	public static function get_data_options( $key ) {
		if ( ! isset( self::$settings[ $key ] ) ) {
			self::$settings[ $key ] = get_option( $key, array() );
		}
		return self::$settings[ $key ];
	}
	/**
	 * Add option page menu
	 */
	public function add_menu() {
		$page = add_theme_page( __( 'Kadence - Next Generation Theme', 'kadence' ), __( 'Kadence', 'kadence' ), apply_filters( 'kadence_admin_settings_capability', 'manage_options' ), 'kadence', array( $this, 'config_page' ) );
		add_action( 'admin_print_styles-' . $page, array( $this, 'scripts' ) );
		do_action( 'kadence_theme_admin_menu' );
	}
	/**
	 * Loads admin style sheets and scripts
	 */
	public function scripts() {
		wp_enqueue_style( 'kadence-dashboard', get_template_directory_uri() . '/inc/dashboard/react/dash-controls.min.css', array( 'wp-components' ), KADENCE_VERSION );
		wp_enqueue_script( 'kadence-dashboard', get_template_directory_uri() . '/assets/js/dashboard.js', array( 'wp-i18n', 'wp-element', 'wp-plugins', 'wp-components', 'wp-api', 'wp-hooks', 'wp-edit-post', 'lodash', 'wp-block-library', 'wp-block-editor', 'wp-editor' ), KADENCE_VERSION, true );
		wp_localize_script(
			'kadence-dashboard',
			'kadenceDashboardParams',
			array(
				'adminURL' => esc_url( admin_url() ),
				'settings' => esc_attr( get_option( 'kadence_theme_config' ) ),
			)
		);
	}
	/**
	 * Register settings
	 */
	public function load_api_settings() {

		register_setting(
			'kadence_theme_config',
			'kadence_theme_config',
			array(
				'type'              => 'string',
				'description'       => __( 'Config Kadence Modules', 'kadence' ),
				'sanitize_callback' => 'sanitize_text_field',
				'show_in_rest'      => true,
				'default'           => '',
			)
		);
	}

	/**
	 * Loads config page
	 */
	public function config_page() {
		?>
		<div class="kadence_theme_dash_head">
			<div class="kadence_theme_dash_head_container">
				<div class="kadence_theme_dash_logo">
					<img src="<?php echo esc_attr( get_template_directory_uri() . '/assets/images/kadence-logo.png' ); ?>">
				</div>
				<div class="kadence_theme_dash_logo_title">
					<h1>
						KADENCE <span class="subtext"><?php echo esc_html__( '- Next Generation Theme', 'kadence' ); ?></span>
					</h1>
				</div>
				<div class="kadence_theme_dash_version">
					<span>
						<?php echo esc_html( KADENCE_VERSION ); ?>
					</span>
				</div>
			</div>
		</div>
		<div class="wrap kadence_theme_dash">
			<div class="kadence_theme_dashboard">
				<h2 class="notices" style="display:none;"></h2>
				<?php settings_errors(); ?>
				<div class="page-grid">
					<div class="kadence_theme_dashboard_main">
					</div>
					<div class="side-panel">
						<?php do_action( 'kadence_theme_dash_side_panel' ); ?>
						<div class="community-section sidebar-section components-panel">
							<div class="components-panel__body is-opened">
								<h2><?php esc_html_e( 'Web Creators Community', 'kadence' ); ?></h2>
								<p><?php esc_html_e( 'Join our community of fellow kadence users creating effective websites! Share your site, ask a question and help others.', 'kadence' ); ?></p>
								<a href="https://www.facebook.com/groups/webcreatorcommunity" target="_blank" class="sidebar-link"><?php esc_html_e( 'Join our Facebook Group', 'kadence' ); ?></a>
							</div>
						</div>
						<div class="support-section sidebar-section components-panel">
							<div class="components-panel__body is-opened">
								<h2><?php esc_html_e( 'Support', 'kadence' ); ?></h2>
								<p><?php esc_html_e( 'Have a question, we are happy to help! Get in touch with our support team.', 'kadence' ); ?></p>
								<a href="https://www.kadencewp.com/free-support/" target="_blank" class="sidebar-link"><?php esc_html_e( 'Submit a Ticket', 'kadence' ); ?></a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php
	}

}
Kadence_Dashboard_Settings::get_instance();
