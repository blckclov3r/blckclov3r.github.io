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
				'changelog' => $this->get_changelog(),
				'proChangelog' => ( class_exists( 'Kadence_Theme_Pro' ) ? $this->get_pro_changelog() : '' ),
			)
		);
	}
	/**
	 * Get Changelog ( Largely Borrowed From Neve Theme )
	 */
	public function get_changelog() {
		$changelog      = array();
		$changelog_path = get_template_directory() . '/changelog.txt';
		if ( ! is_file( $changelog_path ) ) {
			return $changelog;
		}
		global $wp_filesystem;
		if ( ! is_object( $wp_filesystem ) ) {
			require_once ABSPATH . '/wp-admin/includes/file.php';
			WP_Filesystem();
		}

		$changelog_string = $wp_filesystem->get_contents( $changelog_path );
		if ( is_wp_error( $changelog_string ) ) {
			return $changelog;
		}
		$changelog = explode( PHP_EOL, $changelog_string );
		$releases  = [];
		foreach ( $changelog as $changelog_line ) {
			if ( empty( $changelog_line ) ) {
				continue;
			}
			if ( substr( ltrim( $changelog_line ), 0, 2 ) === '==' ) {
				if ( isset( $release ) ) {
					$releases[] = $release;
				}
				$changelog_line = trim( str_replace( '=', '', $changelog_line ) );
				$release = array(
					'head'    => $changelog_line,
				);
			} else {
				if ( preg_match( '/[*|-]?\s?(\[fix]|\[Fix]|fix|Fix)[:]?\s?\b/', $changelog_line ) ) {
					//$changelog_line     = preg_replace( '/[*|-]?\s?(\[fix]|\[Fix]|fix|Fix)[:]?\s?\b/', '', $changelog_line );
					$changelog_line = trim( str_replace( [ '*', '-' ], '', $changelog_line ) );
					$release['fix'][] = $changelog_line;
					continue;
				}

				if ( preg_match( '/[*|-]?\s?(\[add]|\[Add]|add|Add)[:]?\s?\b/', $changelog_line ) ) {
					//$changelog_line        = preg_replace( '/[*|-]?\s?(\[add]|\[Add]|add|Add)[:]?\s?\b/', '', $changelog_line );
					$changelog_line = trim( str_replace( [ '*', '-' ], '', $changelog_line ) );
					$release['add'][] = $changelog_line;
					continue;
				}
				$changelog_line = trim( str_replace( [ '*', '-' ], '', $changelog_line ) );
				$release['update'][] = $changelog_line;
			}
		}
		return $releases;
	}
	/**
	 * Get Changelog ( Largely Borrowed From Neve Theme )
	 */
	public function get_pro_changelog() {
		$changelog      = array();
		if ( ! defined( 'KTP_PATH' ) ) {
			return $changelog;
		}
		$changelog_path = KTP_PATH . '/changelog.txt';
		if ( ! is_file( $changelog_path ) ) {
			return $changelog;
		}
		global $wp_filesystem;
		if ( ! is_object( $wp_filesystem ) ) {
			require_once ABSPATH . '/wp-admin/includes/file.php';
			WP_Filesystem();
		}

		$changelog_string = $wp_filesystem->get_contents( $changelog_path );
		if ( is_wp_error( $changelog_string ) ) {
			return $changelog;
		}
		$changelog = explode( PHP_EOL, $changelog_string );
		$releases  = [];
		foreach ( $changelog as $changelog_line ) {
			if ( empty( $changelog_line ) ) {
				continue;
			}
			if ( substr( ltrim( $changelog_line ), 0, 2 ) === '==' ) {
				if ( isset( $release ) ) {
					$releases[] = $release;
				}
				$changelog_line = trim( str_replace( '=', '', $changelog_line ) );
				$release = array(
					'head'    => $changelog_line,
				);
			} else {
				if ( preg_match( '/[*|-]?\s?(\[fix]|\[Fix]|fix|Fix)[:]?\s?\b/', $changelog_line ) ) {
					//$changelog_line     = preg_replace( '/[*|-]?\s?(\[fix]|\[Fix]|fix|Fix)[:]?\s?\b/', '', $changelog_line );
					$changelog_line = trim( str_replace( [ '*', '-' ], '', $changelog_line ) );
					$release['fix'][] = $changelog_line;
					continue;
				}

				if ( preg_match( '/[*|-]?\s?(\[add]|\[Add]|add|Add)[:]?\s?\b/', $changelog_line ) ) {
					//$changelog_line        = preg_replace( '/[*|-]?\s?(\[add]|\[Add]|add|Add)[:]?\s?\b/', '', $changelog_line );
					$changelog_line = trim( str_replace( [ '*', '-' ], '', $changelog_line ) );
					$release['add'][] = $changelog_line;
					continue;
				}
				$changelog_line = trim( str_replace( [ '*', '-' ], '', $changelog_line ) );
				$release['update'][] = $changelog_line;
			}
		}
		return $releases;
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
								<h2><?php esc_html_e( 'Video Tutorials', 'kadence' ); ?></h2>
								<p><?php esc_html_e( 'Want a guide? We have video tutorials to walk you through getting started.', 'kadence' ); ?></p>
								<a href="https://kadence-theme.com/learn-kadence/" target="_blank" class="sidebar-link"><?php esc_html_e( 'Watch Videos', 'kadence' ); ?></a>
							</div>
						</div>
						<div class="support-section sidebar-section components-panel">
							<div class="components-panel__body is-opened">
								<h2><?php esc_html_e( 'Documentation', 'kadence' ); ?></h2>
								<p><?php esc_html_e( 'Need help? We have a knowledge base full of articles to get you started.', 'kadence' ); ?></p>
								<a href="https://kadence-theme.com/knowledge-base/" target="_blank" class="sidebar-link"><?php esc_html_e( 'Browse Docs', 'kadence' ); ?></a>
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
