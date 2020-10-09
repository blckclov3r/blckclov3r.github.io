<?php
namespace WpAssetCleanUp;

use WpAssetCleanUp\OptimiseAssets\OptimizeCommon;
use WpAssetCleanUp\OptimiseAssets\OptimizeCss;
use WpAssetCleanUp\OptimiseAssets\OptimizeJs;

/**
 * Class Plugin
 * @package WpAssetCleanUp
 */
class Plugin
{
	/**
	 *
	 */
	const RATE_URL = 'https://wordpress.org/support/plugin/wp-asset-clean-up/reviews/?filter=5#new-post';

	/**
	 * Plugin constructor.
	 */
	public function __construct()
	{
		register_activation_hook(WPACU_PLUGIN_FILE, array($this, 'whenActivated'));
		register_deactivation_hook(WPACU_PLUGIN_FILE, array($this, 'whenDeactivated'));
	}

	/**
	 *
	 */
	public function init()
	{
		// After fist time activation or in specific situations within the Dashboard
		add_action('admin_init', array($this, 'adminInit'));

		// [wpacu_lite]
		// Admin footer text: Ask the user to review the plugin
		add_filter('admin_footer_text', array($this, 'adminFooterText'), 1, 1);
		// [/wpacu_lite]

		// Show "Settings" and "Go Pro" as plugin action links
		add_filter('plugin_action_links_'.WPACU_PLUGIN_BASE, array($this, 'actionLinks'));

		// Languages
		add_action('plugins_loaded', array($this, 'loadTextDomain'));

		}

	/**
	 *
	 */
	public function loadTextDomain()
	{
		load_plugin_textdomain('wp-asset-clean-up',
			FALSE,
			basename(WPACU_PLUGIN_DIR) . '/languages/'
		);
	}

	// [wpacu_lite]
	/**
	 * @param $text
	 *
	 * @return string
	 */
	public function adminFooterText($text)
	{
		if (isset($_GET['page']) && strpos($_GET['page'], WPACU_PLUGIN_ID) !== false) {
			$text = sprintf(__('Thank you for using %s', 'wp-asset-clean-up'), WPACU_PLUGIN_TITLE.' v'.WPACU_PLUGIN_VERSION)
			        . ' <span class="dashicons dashicons-smiley"></span> &nbsp;&nbsp;';

			$text .= sprintf(
				__('If you like it, please %s<strong>rate</strong> %s%s %s on WordPress.org to help me spread the word to the community.', 'wp-asset-clean-up'),
				'<a target="_blank" href="'.self::RATE_URL.'">',
				WPACU_PLUGIN_TITLE,
				'</a>',
				'<a target="_blank" href="'.self::RATE_URL.'"><span class="dashicons dashicons-wpacu dashicons-star-filled"></span><span class="dashicons dashicons-wpacu dashicons-star-filled"></span><span class="dashicons dashicons-wpacu dashicons-star-filled"></span><span class="dashicons dashicons-wpacu dashicons-star-filled"></span><span class="dashicons dashicons-wpacu dashicons-star-filled"></span></a>'
			);
		}

		return $text;
	}
	// [/wpacu_lite]

	/**
	 * Actions taken when the plugin is activated
	 */
	public function whenActivated()
	{
	    if (WPACU_WRONG_PHP_VERSION === 'true') {
		    $recordMsg = __( '"Asset CleanUp" plugin has not been activated because the PHP version used on this server is below 5.6.',
			    'wp-asset-clean-up' );
		    deactivate_plugins( WPACU_PLUGIN_BASE );
		    error_log( $recordMsg );
		    wp_die($recordMsg);
	    }

		// Is the plugin activated for the first time?
		// Prepare for the redirection to the WPACU_ADMIN_PAGE_ID_START plugin page
		if (! get_transient(WPACU_PLUGIN_ID.'_do_activation_redirect_first_time')) {
			set_transient(WPACU_PLUGIN_ID.'_do_activation_redirect_first_time', 1);
			set_transient(WPACU_PLUGIN_ID . '_redirect_after_activation', 1, 15);
		}

		// Make a record when Asset CleanUp (Pro) is used for the first time
		self::triggerFirstUsage();

		/**
         * Note: Could be /wp-content/uploads/ if constant WPACU_CACHE_DIR was used
         *
		 * /wp-content/cache/asset-cleanup/
		 * /wp-content/cache/asset-cleanup/index.php
		 * /wp-content/cache/asset-cleanup/.htaccess
		 *
		 * /wp-content/cache/asset-cleanup/css/
         * /wp-content/cache/asset-cleanup/css/item/
		 * /wp-content/cache/asset-cleanup/css/index.php
         *
         * /wp-content/cache/asset-cleanup/js/
         * /wp-content/cache/asset-cleanup/js/item/
         * /wp-content/cache/asset-cleanup/js/index.php
         *
		 */
		self::createCacheFoldersFiles(array('css','js'));

		// Do not apply plugin's settings/rules on WooCommerce/EDD Checkout/Cart pages
		if (function_exists('wc_get_page_id')) {
			if ($wooCheckOutPageId = wc_get_page_id('checkout')) {
				Misc::doNotApplyOptimizationOnPage($wooCheckOutPageId);
			}

			if ($wooCartPageId = wc_get_page_id('cart')) {
				Misc::doNotApplyOptimizationOnPage($wooCartPageId);
			}
		}

		if (function_exists('edd_get_option') && $eddPurchasePage = edd_get_option('purchase_page', '')) {
			Misc::doNotApplyOptimizationOnPage($eddPurchasePage);
		}
	}

	/**
	 * Actions taken when the plugin is deactivated
	 */
	public function whenDeactivated()
    {
    	// Clear traces of the plugin which are re-generated once the plugin is enabled
	    // This is good when the admin wants to completely uninstall the plugin
        self::clearAllTransients();
	    self::removeCacheDirWithoutAssets();

	    // Clear other plugin's cache (if they are active)
        OptimizeCommon::clearOtherPluginsCache();
    }

	/**
	 * Removes all plugin's transients, this is usually done when the plugin is deactivated
	 */
	public static function clearAllTransients()
    {
	    global $wpdb;

	    // Remove all transients
	    $transientLikes = array(
		    '_transient_wpacu_',
		    '_transient_'.WPACU_PLUGIN_ID.'_'
	    );

	    $transientLikesSql = '';

	    foreach ($transientLikes as $transientLike) {
		    $transientLikesSql .= " option_name LIKE '".$transientLike."%' OR ";
	    }

	    $transientLikesSql = rtrim($transientLikesSql, ' OR ');

	    $sqlQuery = <<<SQL
SELECT option_name FROM `{$wpdb->prefix}options` WHERE {$transientLikesSql}
SQL;
	    $transientsToClear = $wpdb->get_col($sqlQuery);

	    foreach ($transientsToClear as $transientToClear) {
		    $transientNameToClear = str_replace('_transient_', '', $transientToClear);
		    delete_transient($transientNameToClear);
	    }
    }

	/**
	 * This is usually triggered when the plugin is deactivated
	 * If the caching directory doesn't have any CSS/JS left, it will clear itself
	 * The admin might want to clear all traces of the plugin
	 * If the plugin is re-activated, the caching directory will be re-created automatically
	 */
	public static function removeCacheDirWithoutAssets()
    {
	    $pathToCacheDir    = WP_CONTENT_DIR . OptimizeCommon::getRelPathPluginCacheDir();

	    if (! is_dir($pathToCacheDir)) {
	        return;
        }

	    $pathToCacheDirCss = WP_CONTENT_DIR . OptimizeCss::getRelPathCssCacheDir();
	    $pathToCacheDirJs  = WP_CONTENT_DIR . OptimizeJs::getRelPathJsCacheDir();

	    $allCssFiles = glob( $pathToCacheDirCss . '**/*.css' );
	    $allJsFiles  = glob( $pathToCacheDirJs . '**/*.js' );

	    // Only valid when there's no CSS or JS (not one single file) there
	    if ( count( $allCssFiles ) === 0 && count( $allJsFiles ) === 0 ) {
		    $dirItems = new \RecursiveDirectoryIterator( $pathToCacheDir );

		    $allDirs = array($pathToCacheDir);

		    // First, remove the files
		    foreach ( new \RecursiveIteratorIterator( $dirItems, \RecursiveIteratorIterator::SELF_FIRST,
				    \RecursiveIteratorIterator::CATCH_GET_CHILD ) as $item) {
		        if (is_dir($item)) {
		            $allDirs[] = $item;
                } else {
		            @unlink($item);
                }
		    }

		    usort($allDirs, static function($a, $b) {
			    return strlen($b) - strlen($a);
		    });

		    // Then, remove the empty dirs in descending order (up to the root)
            foreach ($allDirs as $dir) {
                @rmdir($dir);
            }
	    }
    }

	/**
	 * @param $assetTypes
	 */
	public static function createCacheFoldersFiles($assetTypes)
	{
	    foreach ($assetTypes as $assetType) {
	        if ($assetType === 'css') {
		        $cacheDir = WP_CONTENT_DIR . OptimiseAssets\OptimizeCss::getRelPathCssCacheDir();
	        } elseif ($assetType === 'js') {
	            $cacheDir = WP_CONTENT_DIR . OptimiseAssets\OptimizeJs::getRelPathJsCacheDir();
            } else {
	            return;
            }

		    $emptyPhpFileContents = <<<TEXT
<?php
// Silence is golden.
TEXT;

		    $htAccessContents = <<<HTACCESS
<IfModule mod_autoindex.c>
Options -Indexes
</IfModule>
HTACCESS;

		    if ( ! is_dir( $cacheDir ) ) {
			    @mkdir( $cacheDir, 0755, true );
		    }

		    if ( ! is_file( $cacheDir . 'index.php' ) ) {
			    // /wp-content/cache/asset-cleanup/cache/(css|js)/index.php
			    FileSystem::file_put_contents( $cacheDir . 'index.php', $emptyPhpFileContents );
		    }

			if ( ! is_dir( $cacheDir . OptimizeCommon::$optimizedSingleFilesDir ) ) {
				// /wp-content/cache/asset-cleanup/cache/(css|js)/item/
				@mkdir( $cacheDir . OptimizeCommon::$optimizedSingleFilesDir, 0755 );
			}

			// For large inline STYLE & SCRIPT tags
			if ( ! is_dir( $cacheDir . OptimizeCommon::$optimizedSingleFilesDir.'/inline' ) ) {
				// /wp-content/cache/asset-cleanup/cache/(css|js)/item/inline/
			    @mkdir( $cacheDir . OptimizeCommon::$optimizedSingleFilesDir.'/inline', 0755 );
		    }

		    if ( ! is_file( $cacheDir . OptimizeCommon::$optimizedSingleFilesDir.'/inline/index.php' ) ) {
			    // /wp-content/cache/asset-cleanup/cache/(css|js)/item/inline/index.php
			    FileSystem::file_put_contents( $cacheDir . OptimizeCommon::$optimizedSingleFilesDir.'/inline/index.php', $emptyPhpFileContents );
		    }

		    $htAccessFilePath = dirname( $cacheDir ) . '/.htaccess';

		    if ( ! is_file( $htAccessFilePath ) ) {
			    // /wp-content/cache/asset-cleanup/.htaccess
			    FileSystem::file_put_contents( $htAccessFilePath, $htAccessContents );
		    }

		    if ( ! is_file( dirname( $cacheDir ) . '/index.php' ) ) {
			    // /wp-content/cache/asset-cleanup/index.php
			    FileSystem::file_put_contents( dirname( $cacheDir ) . '/index.php', $emptyPhpFileContents );
		    }
	    }

		$storageDir = WP_CONTENT_DIR . OptimiseAssets\OptimizeCommon::getRelPathPluginCacheDir() . '_storage/';

		if ( ! is_dir($storageDir . OptimizeCommon::$optimizedSingleFilesDir) ) {
			@mkdir( $storageDir . OptimizeCommon::$optimizedSingleFilesDir, 0755, true );
		}

		$siteStorageCache = $storageDir.'/'.str_replace(array('https://', 'http://', '//'), '', site_url());

		if ( ! is_dir($storageDir) ) {
			@mkdir( $siteStorageCache, 0755, true );
		}
	}

	/**
	 *
	 */
	public function adminInit()
	{
		if (strpos($_SERVER['REQUEST_URI'], '/plugins.php') !== false && get_transient(WPACU_PLUGIN_ID . '_redirect_after_activation')) {
			// Remove it as only one redirect is needed (first time the plugin is activated)
			delete_transient(WPACU_PLUGIN_ID . '_redirect_after_activation');

			// Do the 'first activation time' redirection
			wp_redirect(admin_url('admin.php?page=' . WPACU_ADMIN_PAGE_ID_START));
			exit();
		}

        $triggerFirstUsage = (strpos($_SERVER['REQUEST_URI'], '/plugins.php') !== false ||
                              strpos($_SERVER['REQUEST_URI'], '/plugin-install.php') !== false ||
                              strpos($_SERVER['REQUEST_URI'], '/options-general.php') !== false ||
                              strpos($_SERVER['REQUEST_URI'], '/update-core.php') !== false);

		// No first usage timestamp set, yet? Set it now!
		if ($triggerFirstUsage) {
			self::triggerFirstUsage();
		}
	}

	/**
	 * @param $links
	 *
	 * @return mixed
	 */
	public function actionLinks($links)
	{
		$links['getting_started'] = '<a href="admin.php?page=' . WPACU_PLUGIN_ID . '_getting_started">' . __('Getting Started', 'wp-asset-clean-up') . '</a>';
		$links['settings']        = '<a href="admin.php?page=' . WPACU_PLUGIN_ID . '_settings">'        . __('Settings', 'wp-asset-clean-up') . '</a>';

		// [wpacu_lite]
		$allPlugins = get_plugins();

		// If the Pro version is not installed (active or not), show the upgrade link
		if (! array_key_exists('wp-asset-clean-up-pro/wpacu.php', $allPlugins)) {
			$links['go_pro'] = '<a target="_blank" style="font-weight: bold;" href="'.WPACU_PLUGIN_GO_PRO_URL.'">'.__('Go Pro', 'wp-asset-clean-up').'</a>';
		}
		// [/wpacu_lite]

		return $links;
	}

	/**
	 * Make a record when Asset CleanUp (Pro) is used for the first time (if it's not there already)
	 */
	public static function triggerFirstUsage()
	{
		// No first usage timestamp set, yet? Set it now!
		if (! get_option(WPACU_PLUGIN_ID.'_first_usage')) {
			Misc::addUpdateOption(WPACU_PLUGIN_ID . '_first_usage', time());
		}
	}

	/**
     * This works like /?wpacu_no_load with a fundamental difference:
     * It needs to be triggered through a very early 'init' action hook after all plugins are loaded, thus it can't be used in /early-triggers.php
     * e.g. in situations when the page is an AMP one, prevent any changes to the HTML source by Asset CleanUp (Pro)
     *
	 * @return bool
	 */
	public static function preventAnyChanges()
    {
        // Only relevant if all the plugins are already loaded
	    // and in the front-end view
        if (! defined('WPACU_ALL_ACTIVE_PLUGINS_LOADED') || is_admin()) {
            return false;
        }

        // Perhaps the editor from "Pro" (theme.co) is on
	    if (apply_filters('wpacu_prevent_any_changes', false)) {
		    define('WPACU_PREVENT_ANY_CHANGES', true);
		    return true;
	    }

        if (defined('WPACU_PREVENT_ANY_CHANGES')) {
	        return WPACU_PREVENT_ANY_CHANGES;
        }

	    // e.g. /amp/ - /amp? - /amp/? - /?amp or ending in /amp
	    $isAmpInRequestUri = ((isset($_SERVER['REQUEST_URI']) && (preg_match('/(\/amp$|\/amp\?)|(\/amp\/|\/amp\/\?)/', $_SERVER['REQUEST_URI']))) || (array_key_exists('amp', $_GET)));

	    // Is it an AMP endpoint?
	    if ( ($isAmpInRequestUri && Misc::isPluginActive('accelerated-mobile-pages/accelerated-mobile-pages.php')) // "AMP for WP – Accelerated Mobile Pages"
	         || ($isAmpInRequestUri && Misc::isPluginActive('amp/amp.php')) // "AMP – WordPress plugin"
	         || (function_exists('is_wp_amp') && Misc::isPluginActive('wp-amp/wp-amp.php') && is_wp_amp()) // "WP AMP — Accelerated Mobile Pages for WordPress and WooCommerce" (Premium plugin)
	    ) {
		    define('WPACU_PREVENT_ANY_CHANGES', true);
		    return true; // do not print anything on an AMP page
	    }

	    if (array_key_exists('wpacu_clean_load', $_GET)) {
		    define('WPACU_PREVENT_ANY_CHANGES', true);
	        return true;
        }

	    define('WPACU_PREVENT_ANY_CHANGES', false);
	    return false;
    }
}
