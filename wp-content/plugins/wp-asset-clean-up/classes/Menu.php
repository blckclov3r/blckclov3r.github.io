<?php
namespace WpAssetCleanUp;

/**
 * Class Menu
 * @package WpAssetCleanUp
 */
class Menu
{
	/**
	 * @var array
	 */
	public $allMenuPages = array();

	/**
	 * @var string
	 */
	private static $_capability = 'administrator';

	/**
	 * @var string
	 */
	private static $_slug;

    /**
     * Menu constructor.
     */
    public function __construct()
    {
    	$this->allMenuPages = array(
		    WPACU_PLUGIN_ID . '_getting_started',
		    WPACU_PLUGIN_ID . '_settings',
		    WPACU_PLUGIN_ID . '_assets_manager',
		    WPACU_PLUGIN_ID . '_plugins_manager',
		    WPACU_PLUGIN_ID . '_bulk_unloads',
		    WPACU_PLUGIN_ID . '_overview',
		    WPACU_PLUGIN_ID . '_tools',
		    WPACU_PLUGIN_ID . '_license',
		    WPACU_PLUGIN_ID . '_get_help',
		    WPACU_PLUGIN_ID . '_go_pro'
	    );

    	self::$_slug = WPACU_PLUGIN_ID . '_getting_started';

        add_action('admin_menu', array($this, 'activeMenu'));

        if (isset($_GET['page']) && $_GET['page'] === WPACU_PLUGIN_ID . '_go_pro') {
        	header('Location: '.WPACU_PLUGIN_GO_PRO_URL.'?utm_source=plugin_go_pro');
        	exit();
        }

	    add_action('admin_page_access_denied', array($this, 'pluginPagesAccessDenied'));
    }

    /**
     *
     */
    public function activeMenu()
    {
	    // User should be of 'administrator' role and allowed to activate plugins
	    if (! self::userCanManageAssets()) {
		    return;
	    }

        add_menu_page(
            WPACU_PLUGIN_TITLE,
	        WPACU_PLUGIN_TITLE,
	        self::getAccessCapability(),
            self::$_slug,
            array(new Info, 'gettingStarted'),
	        WPACU_PLUGIN_URL.'/assets/icons/icon-asset-cleanup.png'
        );

	    add_submenu_page(
		    self::$_slug,
		    __('Settings', 'wp-asset-clean-up'),
		    __('Settings', 'wp-asset-clean-up'),
		    self::getAccessCapability(),
		    WPACU_PLUGIN_ID . '_settings',
		    array(new Settings, 'settingsPage')
	    );

	    add_submenu_page(
		    self::$_slug,
		    __('CSS/JS Manager', 'wp-asset-clean-up'),
		    __('CSS/JS Manager', 'wp-asset-clean-up'),
		    self::getAccessCapability(),
		    WPACU_PLUGIN_ID . '_assets_manager',
		    array(new AssetsPagesManager, 'page')
	    );

	    add_submenu_page(
		    self::$_slug,
		    __('Plugins Manager', 'wp-asset-clean-up'),
		    __('Plugins Manager', 'wp-asset-clean-up'),
		    self::getAccessCapability(),
		    WPACU_PLUGIN_ID . '_plugins_manager',
		    array(new PluginsManager, 'page')
	    );

	    add_submenu_page(
	        self::$_slug,
            __('Bulk Changes', 'wp-asset-clean-up'),
            __('Bulk Changes', 'wp-asset-clean-up'),
		    self::getAccessCapability(),
		    WPACU_PLUGIN_ID . '_bulk_unloads',
            array(new BulkChanges, 'pageBulkUnloads')
        );

	    add_submenu_page(
		    self::$_slug,
		    __('Overview', 'wp-asset-clean-up'),
		    __('Overview', 'wp-asset-clean-up'),
		    self::getAccessCapability(),
		    WPACU_PLUGIN_ID . '_overview',
		    array(new Overview, 'pageOverview')
	    );

	    add_submenu_page(
		    self::$_slug,
		    __('Tools', 'wp-asset-clean-up'),
		    __('Tools', 'wp-asset-clean-up'),
		    self::getAccessCapability(),
		    WPACU_PLUGIN_ID . '_tools',
		    array(new Tools, 'toolsPage')
	    );

	    // License Page
	    add_submenu_page(
		    self::$_slug,
		    __('License', 'wp-asset-clean-up'),
		    __('License', 'wp-asset-clean-up'),
		    self::getAccessCapability(),
		    WPACU_PLUGIN_ID . '_license',
		    array(new Info, 'license')
	    );

        // Get Help | Support Page
        add_submenu_page(
	        self::$_slug,
            __('Help', 'wp-asset-clean-up'),
            __('Help', 'wp-asset-clean-up'),
	        self::getAccessCapability(),
	        WPACU_PLUGIN_ID . '_get_help',
            array(new Info, 'help')
        );

	    // Upgrade to "Go Pro" | Redirects to sale page
	    add_submenu_page(
		    self::$_slug,
		    __('Go Pro', 'wp-asset-clean-up'),
		    __('Go Pro', 'wp-asset-clean-up') . ' <span style="font-size: 16px; color: inherit;" class="dashicons dashicons-star-filled"></span>',
		    self::getAccessCapability(),
		    WPACU_PLUGIN_ID . '_go_pro',
		    function() {}
	    );

	    // Add "Asset CleanUp Pro" Settings Link to the main "Settings" menu within the Dashboard
	    // For easier navigation
	    $GLOBALS['submenu']['options-general.php'][] = array(
		    WPACU_PLUGIN_TITLE,
		    self::getAccessCapability(),
		    admin_url( 'admin.php?page=' . WPACU_PLUGIN_ID . '_settings'),
		    WPACU_PLUGIN_TITLE,
	    );

        // Rename first item from the menu which has the same title as the menu page
        $GLOBALS['submenu'][self::$_slug][0][0] = esc_attr__('Getting Started', 'wp-asset-clean-up');
    }

	/**
	 * @return bool
	 */
	public static function userCanManageAssets()
	{
		// Has self::$_capability been changed? Just user current_user_can()
		if (self::getAccessCapability() !== self::$_capability) {
			return current_user_can(self::getAccessCapability());
		}

		// self::$_capability default value; also check if the user can activate plugins
		return current_user_can(self::getAccessCapability()) && current_user_can('activate_plugins');
	}

	/**
	 * Here self::$_capability can be overridden
	 *
	 * @return mixed|void
	 */
	public static function getAccessCapability()
	{
		return apply_filters('wpacu_access_role', self::$_capability);
	}

	/**
	 * Message to show if the user does not have self::$_capability role and tries to access a plugin's page
	 */
	public function pluginPagesAccessDenied()
	{
		if ( ! (isset($_GET['page'])
		        && strpos($_GET['page'], WPACU_PLUGIN_ID . '_') === 0
		        && in_array($_GET['page'], $this->allMenuPages)) ) {
			// Not an Asset CleanUp page
			return;
		}

		$userMeta = get_userdata(get_current_user_id());
		$userRoles = $userMeta->roles;

		wp_die(
			__('Sorry, you are not allowed to access this page.').'<br /><br />'.
			sprintf(__('Asset CleanUp requires "%s" role and the ability to activate plugins in order to access its pages.', 'wp-asset-clean-up'), '<span style="color: green; font-weight: bold;">'.self::getAccessCapability().'</span>').'<br />'.
			sprintf(__('Your current role(s): <strong>%s</strong>', 'wp-asset-clean-up'), implode(', ', $userRoles)).'<br /><br />'.
			__('The value (in green color) can be changed if you use the following snippet in functions.php (within your theme/child theme or a custom plugin):').'<br />'.
			'<p style="margin: -10px 0 0;"><code style="background: #f2f3ea; padding: 5px;">add_filter(\'wpacu_access_role\', function($role) { return \'your_role_here\'; });</code></p>'.
			'<p>If the snippet is not used, it will default to "administrator".</p>'.
			'<p>Possible values: <strong>manage_options</strong>, <strong>activate_plugins</strong>, <strong>manager</strong> etc.</p>'.
			'<p>Read more: <a target="_blank" href="https://wordpress.org/support/article/roles-and-capabilities/#summary-of-roles">https://wordpress.org/support/article/roles-and-capabilities/#summary-of-roles</a></p>',
			403
		);
	}
}
