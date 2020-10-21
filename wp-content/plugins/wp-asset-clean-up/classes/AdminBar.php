<?php
namespace WpAssetCleanUp;

/**
 * Class AdminBar
 * @package WpAssetCleanUp
 */
class AdminBar
{
	/**
	 *
	 */
	public function __construct()
	{
		add_action( 'init', array( $this, 'topBar' ) );

		// Hide top WordPress admin bar on request for debugging purposes and a cleared view of the tested page
		// This is done in /early-triggers.php within assetCleanUpNoLoad() function
	}

	/**
	 *
	 */
	public function topBar()
	{
		if (Menu::userCanManageAssets() && (! Main::instance()->settings['hide_from_admin_bar'])) {
			add_action( 'admin_bar_menu', array( $this, 'topBarInfo' ), 999 );
		}
	}

	/**
	 * @param $wp_admin_bar
	 */
	public function topBarInfo($wp_admin_bar)
	{
		$topTitle = WPACU_PLUGIN_TITLE;

		if (Main::instance()->settings['test_mode']) {
			$topTitle .= '&nbsp; <span class="dashicons dashicons-admin-tools"></span> <strong>TEST MODE</strong> is <strong>ON</strong>';
		}

		$goBackToCurrentUrl = '&_wp_http_referer=' . urlencode( wp_unslash( $_SERVER['REQUEST_URI'] ) );

		$wp_admin_bar->add_menu(array(
			'id'    => 'assetcleanup-parent',
			'title' => $topTitle,
			'href'  => admin_url('admin.php?page=' . WPACU_PLUGIN_ID . '_settings')
		));

		$wp_admin_bar->add_menu(array(
			'parent' => 'assetcleanup-parent',
			'id'     => 'assetcleanup-settings',
			'title'  => __('Settings', 'wp-asset-clean-up'),
			'href'   => admin_url( 'admin.php?page=' . WPACU_PLUGIN_ID . '_settings')
		));

		$wp_admin_bar->add_menu( array(
			'parent' => 'assetcleanup-parent',
			'id'     => 'assetcleanup-clear-css-js-files-cache',
			'title'  => __('Clear CSS/JS Files Cache', 'wp-asset-clean-up'),
			'href'   => wp_nonce_url( admin_url( 'admin-post.php?action=assetcleanup_clear_assets_cache' . $goBackToCurrentUrl ),
				'assetcleanup_clear_assets_cache' )
		) );

		// Only trigger in the front-end view
		if (! is_admin()) {
			if ( ! Misc::isHomePage() ) {
				// Not on the home page
				$homepageManageAssetsHref = Main::instance()->frontendShow()
					? get_site_url().'#wpacu_wrap_assets'
					: admin_url( 'admin.php?page=' . WPACU_PLUGIN_ID . '_assets_manager&wpacu_for=homepage' );

				$wp_admin_bar->add_menu(array(
					'parent' => 'assetcleanup-parent',
					'id'     => 'assetcleanup-homepage',
					'title'  => __('Manage Homepage Assets', 'wp-asset-clean-up'),
					'href'   => $homepageManageAssetsHref
				));
			} else {
				// On the home page
				// Front-end view is disabled! Go to Dashboard link
				if ( ! Main::instance()->frontendShow() ) {
					$wp_admin_bar->add_menu( array(
						'parent' => 'assetcleanup-parent',
						'id'     => 'assetcleanup-homepage',
						'title'  => __('Manage Page Assets', 'wp-asset-clean-up'),
						'href'   => admin_url('admin.php?page=' . WPACU_PLUGIN_ID . '_assets_manager&wpacu_for=homepage')
					) );
				}
			}
		}

		if (! is_admin() && Main::instance()->frontendShow()) {
			$wp_admin_bar->add_menu(array(
				'parent' => 'assetcleanup-parent',
				'id'     => 'assetcleanup-jump-to-assets-list',
				'title'  => __('Manage Page Assets', 'wp-asset-clean-up'),
				'href'   => '#wpacu_wrap_assets'
			));
		}

		$wp_admin_bar->add_menu(array(
			'parent' => 'assetcleanup-parent',
			'id'     => 'assetcleanup-bulk-unloaded',
			'title'  => __('Bulk Changes', 'wp-asset-clean-up'),
			'href'   => admin_url( 'admin.php?page=' . WPACU_PLUGIN_ID . '_bulk_unloads')
		));

		$wp_admin_bar->add_menu( array(
			'parent' => 'assetcleanup-parent',
			'id'     => 'assetcleanup-overview',
			'title'  => __('Overview', 'wp-asset-clean-up'),
			'href'   => admin_url( 'admin.php?page=' . WPACU_PLUGIN_ID . '_overview')
		) );

		$wp_admin_bar->add_menu(array(
			'parent' => 'assetcleanup-parent',
			'id'     => 'assetcleanup-support-forum',
			'title'  => __('Support Forum', 'wp-asset-clean-up'),
			'href'   => 'https://wordpress.org/support/plugin/wp-asset-clean-up',
			'meta'   => array('target' => '_blank')
		));

		}
}
