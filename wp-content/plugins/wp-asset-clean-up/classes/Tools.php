<?php
namespace WpAssetCleanUp;

use WpAssetCleanUp\OptimiseAssets\OptimizeCommon;
use WpAssetCleanUp\OptimiseAssets\OptimizeCss;
use WpAssetCleanUp\OptimiseAssets\OptimizeJs;
use WpAssetCleanUp\ThirdParty\Browser;

/**
 * Class Tools
 * @package WpAssetCleanUp
 */
class Tools
{
	/**
	 * @var string
	 */
	public $wpacuFor = 'reset';

	/**
	 * @var array
	 */
	public $errorLogsData = array();

	/**
	 * @var
	 */
	public $resetChoice;

	/**
	 * @var bool
	 */
	public $licenseDataRemoved = false;

	/**
	 * @var bool
	 */
	public $cachedAssetsRemoved = false;

	/**
	 * @var array
	 */
	public $data = array();

	/**
	 * Tools constructor.
	 */
	public function __construct()
	{
		$this->wpacuFor = Misc::getVar('request', 'wpacu_for', $this->wpacuFor);

		if ($this->wpacuFor === 'debug') {
			$isLogPHPErrors       = @ini_get( 'log_errors' );
			$logPHPErrorsLocation = @ini_get( 'error_log' ) ?: 'none set';

			$this->errorLogsData['log_status'] = $isLogPHPErrors;
			$this->errorLogsData['log_file']   = $logPHPErrorsLocation;
		}
	}

	/**
	 *
	 */
	public function init()
    {
	    add_action('admin_init', array($this, 'onAdminInit'), 1);
    }

	/**
	 *
	 */
	public function onAdminInit()
	{
		if (Misc::getVar('post', 'wpacu-tools-reset')) {
			$this->doReset();
		}

		if (Misc::getVar('post', 'wpacu-get-system-info')) {
			$this->downloadSystemInfo();
		}

		if (Misc::getVar('post', 'wpacu-get-error-log') && is_file($this->errorLogsData['log_file'])) {
		    self::downloadFile($this->errorLogsData['log_file']);
        }

		if (! empty($_POST) && $this->wpacuFor === 'import_export') {
			$wpacuImportExport = new ImportExport();

			// Any import/export action taken? It will reload the page if action is successful
			$wpacuImportExport->doImport();

			// This will download the JSON through the right headers (the user will stay on the same page)
			$wpacuImportExport->doExport();
		}

		if (isset($_GET['page']) && $_GET['page'] === WPACU_PLUGIN_ID. '_tools') {
			// "Import" Completed
			if ($importDoneInfo = get_transient('wpacu_import_done')) {
				$resetDoneListArray = @json_decode($importDoneInfo, ARRAY_A);

				if (! is_array($resetDoneListArray)) {
					return;
				}

				$this->data['import_done_list'] = $resetDoneListArray;

				delete_transient('wpacu_import_done');

				// Show the confirmation that the import was completed
				add_action('wpacu_admin_notices', array($this, 'importDone'));
			}

			// "Reset" Completed
			if ($resetDoneInfo = get_transient('wpacu_reset_done')) {
				$resetDoneInfoArray = @json_decode($resetDoneInfo, ARRAY_A);

				if (! is_array($resetDoneInfoArray)) {
					return;
				}

				$this->resetChoice         = isset($resetDoneInfoArray['reset_choice']) ? $resetDoneInfoArray['reset_choice'] : '';
				$this->licenseDataRemoved  = isset($resetDoneInfoArray['license_data_removed']) ? $resetDoneInfoArray['license_data_removed'] : '';
				$this->cachedAssetsRemoved = isset($resetDoneInfoArray['cached_assets_removed']) ? $resetDoneInfoArray['cached_assets_removed'] : '';

				delete_transient('wpacu_reset_done');

				// Show the confirmation that the reset was completed
				add_action('wpacu_admin_notices', array($this, 'resetDone'));
			}
		}
	}

	/**
	 *
	 */
	public function toolsPage()
	{
		$this->data['for'] = $this->wpacuFor;

		if ($this->data['for'] === 'system_info') {
		    $this->data['system_info'] = $this->getSystemInfo();
        }

		if ($this->data['for'] === 'debug') {
			$this->data['error_log'] = $this->errorLogsData;
		}

		Main::instance()->parseTemplate('admin-page-tools', $this->data, true);
	}

	/**
	 * @return bool|string
	 */
	public function maybeGetHost()
    {
	    if ( defined( 'WPE_APIKEY' ) ) {
		    $host = 'WP Engine';
	    } elseif( defined( 'PAGELYBIN' ) ) {
		    $host = 'Pagely';
	    } elseif( DB_HOST === 'localhost:/tmp/mysql5.sock' ) {
		    $host = 'ICDSoft';
	    } elseif( DB_HOST === 'mysqlv5' ) {
		    $host = 'NetworkSolutions';
	    } elseif( strpos( DB_HOST, 'ipagemysql.com' ) !== false ) {
		    $host = 'iPage';
	    } elseif( strpos( DB_HOST, 'ipowermysql.com' ) !== false ) {
		    $host = 'IPower';
	    } elseif( strpos( DB_HOST, '.gridserver.com' ) !== false ) {
		    $host = 'MediaTemple Grid';
	    } elseif( strpos( DB_HOST, '.pair.com' ) !== false ) {
		    $host = 'pair Networks';
	    } elseif( strpos( DB_HOST, '.stabletransit.com' ) !== false ) {
		    $host = 'Rackspace Cloud';
	    } elseif( strpos( DB_HOST, '.sysfix.eu' ) !== false ) {
		    $host = 'SysFix.eu Power Hosting';
	    } elseif( strpos( $_SERVER['SERVER_NAME'], 'Flywheel' ) !== false ) {
		    $host = 'Flywheel';
	    } else {
		    // Fallback
		    $host = 'DBH: ' . DB_HOST . ', SRV: ' . $_SERVER['SERVER_NAME'];
	    }

	    return $host;
    }

	/**
	 * @return string
	 */
	public function getSystemInfo()
    {
	    global $wpdb;

	    $return = '### Begin System Info ###' . "\n";

	    $return .= "\n" . '# Site Info' . "\n";
	    $return .= 'Site URL:                  ' . site_url() . "\n";
	    $return .= 'Home URL:                  ' . home_url() . "\n";
	    $return .= 'Multisite:                 ' . ( is_multisite() ? 'Yes' : 'No' ) . "\n";

	    $host = $this->maybeGetHost();
	    $browser = new Browser();

	    if ($host) {
		    $return .= "\n" . '# Hosting Provider' . "\n";
		    $return .= 'Host: ' . $host . "\n";
	    }

	    if ($browser) {
		    $return .= "\n" . '# User Browser' . "\n";
		    $return .= strip_tags($browser)."\n";
        }

	    // WordPress configuration.
	    // Get theme info.
	    $theme_data = wp_get_theme();
	    $theme      = $theme_data->Name . ' ' . $theme_data->Version;

	    $return .= "\n" . '# WordPress Configuration' . "\n";
	    $return .= 'Version:                   ' . get_bloginfo( 'version' ) . "\n";
	    $return .= 'Language:                  ' . ( defined( 'WPLANG' ) && WPLANG ? WPLANG : 'en_US' ) . "\n";
	    $return .= 'Permalink Structure:       ' . ( get_option( 'permalink_structure' ) ? get_option( 'permalink_structure' ) : 'Default' ) . "\n";
	    $return .= 'Active Theme:              ' . $theme . "\n";
	    $return .= 'Show On Front:             ' . get_option( 'show_on_front' ) . "\n";

	    // Only show page specs if front page is set to 'page'.
	    if ( get_option( 'show_on_front' ) === 'page' ) {
		    $front_page_id = get_option( 'page_on_front' );
		    $blog_page_id  = get_option( 'page_for_posts' );

		    $return .= 'Page On Front:             ' . ( 0 != $front_page_id ? get_the_title( $front_page_id ) . ' (ID: ' . $front_page_id . ')' : 'Unset' ) . "\n";
		    $return .= 'Page For Posts:            ' . ( 0 != $blog_page_id ? get_the_title( $blog_page_id ) . ' (ID: ' . $blog_page_id . ')' : 'Unset' ) . "\n";
	    }

	    $return .= 'ABSPATH:                   ' . ABSPATH . "\n";
	    $return .= 'WP_DEBUG:                  ' . ( defined( 'WP_DEBUG' ) ? (WP_DEBUG ? 'Enabled' : 'Disabled') : 'Not set' ) . "\n";
	    $return .= 'Memory Limit:              ' . WP_MEMORY_LIMIT . "\n";

	    $return .= "\n" . '# WordPress Uploads/Constants' . "\n";
	    $return .= 'WP_CONTENT_DIR:            ' . ( defined( 'WP_CONTENT_DIR' ) ? (WP_CONTENT_DIR ? WP_CONTENT_DIR : 'Disabled') : 'Not set' ) . "\n";
	    $return .= 'WP_CONTENT_URL:            ' . ( defined( 'WP_CONTENT_URL' ) ? (WP_CONTENT_URL ? WP_CONTENT_URL : 'Disabled') : 'Not set' ) . "\n";
	    $return .= 'UPLOADS:                   ' . ( defined( 'UPLOADS' ) ? (UPLOADS ? UPLOADS : 'Disabled') : 'Not set' ) . "\n";

	    $uploads_dir = wp_upload_dir();

	    $return .= 'wp_uploads_dir() path:     ' . $uploads_dir['path'] . "\n";
	    $return .= 'wp_uploads_dir() url:      ' . $uploads_dir['url'] . "\n";
	    $return .= 'wp_uploads_dir() basedir:  ' . $uploads_dir['basedir'] . "\n";
	    $return .= 'wp_uploads_dir() baseurl:  ' . $uploads_dir['baseurl'] . "\n";

	    // Get plugins that have an update.
	    $updates = get_plugin_updates();

	    // Must-use plugins.
	    // NOTE: MU plugins can't show updates!
	    $muplugins = get_mu_plugins();
	    if ( ! empty( $muplugins ) && count( $muplugins ) > 0 ) {
		    $return .= "\n" . '# Must-Use Plugins ("mu-plugins" directory)' . "\n";

		    foreach ( $muplugins as $plugin => $plugin_data ) {
			    $return .= $plugin_data['Name'] . ': ' . $plugin_data['Version'] . "\n";
		    }
	    }

	    // WordPress active plugins.
	    $return .= "\n" . '# Active Plugins ("plugins" directory)' . "\n";

	    $plugins        = get_plugins();
	    $active_plugins = get_option( 'active_plugins', array() );

	    foreach ( $plugins as $plugin_path => $plugin ) {
		    if ( ! in_array( $plugin_path, $active_plugins, true ) ) {
			    continue;
		    }
		    $update  = array_key_exists($plugin_path, $updates) ? ' (new version available - ' . $updates[ $plugin_path ]->update->new_version . ')' : '';
		    $return .= $plugin['Name'] . ': ' . $plugin['Version'] . $update . "\n";
	    }

	    // WordPress inactive plugins.
	    $return .= "\n" . '# Inactive Plugins ("plugins" directory)' . "\n";

	    foreach ( $plugins as $plugin_path => $plugin ) {
		    if ( in_array( $plugin_path, $active_plugins, true ) ) {
			    continue;
		    }
		    $update  = array_key_exists($plugin_path, $updates) ? ' (new version available - ' . $updates[ $plugin_path ]->update->new_version . ')' : '';
		    $return .= $plugin['Name'] . ': ' . $plugin['Version'] . $update . "\n";
	    }

	    if ( is_multisite() ) {
		    // WordPress Multisite active plugins.
		    $return .= "\n" . '# Network Active Plugins' . "\n";

		    $plugins        = wp_get_active_network_plugins();
		    $active_plugins = get_site_option( 'active_sitewide_plugins', array() );

		    foreach ( $plugins as $plugin_path ) {
			    $plugin_base = plugin_basename( $plugin_path );
			    if ( ! array_key_exists( $plugin_base, $active_plugins ) ) {
				    continue;
			    }
			    $update  = array_key_exists($plugin_path, $updates) ? ' (new version available - ' . $updates[ $plugin_path ]->update->new_version . ')' : '';
			    $plugin  = get_plugin_data( $plugin_path );
			    $return .= $plugin['Name'] . ': ' . $plugin['Version'] . $update . "\n";
		    }
	    }

	    // Server configuration (really just versions).
	    $return .= "\n" . '# Webserver Configuration' . "\n";
	    $return .= 'PHP Version:              ' . PHP_VERSION . "\n";
	    $return .= 'MySQL Version:            ' . $wpdb->db_version() . "\n";
	    $return .= 'Webserver Info:           ' . $_SERVER['SERVER_SOFTWARE'] . "\n";

	    // PHP important configuration taken from php.ini
	    $return .= "\n" . '# PHP Configuration' . "\n";
	    $return .= 'Memory Limit:             ' . ini_get( 'memory_limit' ) . "\n";
	    $return .= 'Upload Max Size:          ' . ini_get( 'upload_max_filesize' ) . "\n";
	    $return .= 'Post Max Size:            ' . ini_get( 'post_max_size' ) . "\n";
	    $return .= 'Upload Max Filesize:      ' . ini_get( 'upload_max_filesize' ) . "\n";
	    $return .= 'Time Limit:               ' . ini_get( 'max_execution_time' ) . "\n";
	    $return .= 'Max Input Vars:           ' . ini_get( 'max_input_vars' ) . "\n";
	    $return .= 'Display Errors:           ' . ( ini_get( 'display_errors' ) ? 'On (php.ini value: ' . ini_get( 'display_errors' ) . ')' : 'N/A' ) . "\n";

	    // PHP extensions and such.
	    $return .= "\n" . '# PHP Extensions' . "\n";
	    $return .= 'cURL:                     ' . ( function_exists( 'curl_init' ) ? 'Supported' : 'Not Supported' ) . "\n";
	    $return .= 'fsockopen:                ' . ( function_exists( 'fsockopen' ) ? 'Supported' : 'Not Supported' ) . "\n";
	    $return .= 'SOAP Client:              ' . ( class_exists( 'SoapClient' ) ? 'Installed' : 'Not Installed' ) . "\n";
	    $return .= 'Suhosin:                  ' . ( extension_loaded( 'suhosin' ) ? 'Installed' : 'Not Installed' ) . "\n";

	    // Session stuff.
	    $return .= "\n" . '# Session Configuration' . "\n";
	    $return .= 'Session:                  ' . ( isset( $_SESSION ) ? 'Enabled' : 'Disabled' ) . "\n";

	    // The rest of this is only relevant if session is enabled.
	    if ( isset( $_SESSION ) ) {
		    $return .= 'Session Name:             ' . esc_html( ini_get( 'session.name' ) ) . "\n";
		    $return .= 'Cookie Path:              ' . esc_html( ini_get( 'session.cookie_path' ) ) . "\n";
		    $return .= 'Save Path:                ' . esc_html( ini_get( 'session.save_path' ) ) . "\n";
		    $return .= 'Use Cookies:              ' . ( ini_get( 'session.use_cookies' ) ? 'On' : 'Off' ) . "\n";
		    $return .= 'Use Only Cookies:         ' . ( ini_get( 'session.use_only_cookies' ) ? 'On' : 'Off' ) . "\n";
	    }

	    $return .= "\n" . '# '.WPACU_PLUGIN_TITLE.' Configuration '. "\n";

	    $settingsClass = new Settings();
	    $settings = $settingsClass->getAll();

	    $globalUnloadList = Main::instance()->getGlobalUnload();

	    if (in_array('wp-block-library', $globalUnloadList['styles'])) {
		    $settings['disable_wp_block_library'] = 1;
	    }

	    if (in_array('jquery-migrate', $globalUnloadList['scripts'])) {
		    $settings['disable_jquery_migrate'] = 1;
	    }

	    if (in_array('comment-reply', $globalUnloadList['scripts'])) {
		    $settings['disable_comment_reply'] = 1;
	    }

	    $return .= 'Has read "Stripping the fat" text:   '. (($settings['wiki_read'] == 1) ? 'Yes' : 'No') . "\n\n";

	    $return .= 'Manage in the Dashboard:             '. (($settings['dashboard_show'] == 1) ? 'Yes ('.$settings['dom_get_type'].')' : 'No');

	    if ($settings['hide_assets_meta_box']) {
		    $return .= ' - Assets Meta Box is Hidden';
	    }

	    if ($settings['hide_options_meta_box']) {
		    $return .= ' - Side Options Meta Box is Hidden';
	    }

	    $return .= "\n";

	    $return .= 'Manage in the Front-end:             '. (($settings['frontend_show'] == 1) ? 'Yes' : 'No') . "\n";

	    if ($settings['frontend_show'] == 1 && $settings['frontend_show_exceptions']) {
		    $return .= 'Do not show front-end assets when the URI contains (textarea value):' . "\n" . $settings['frontend_show_exceptions'] . "\n\n";
	    }

	    $return .= 'Input Fields Style:                  '. ucfirst($settings['input_style'])."\n";
	    $return .= 'Hide WP Files (from managing):       '. (($settings['hide_core_files'] == 1) ? 'Yes' : 'No') . "\n";
	    $return .= 'Enable "Test Mode"?                  '. (($settings['test_mode'] == 1) ? 'Yes' : 'No') . "\n\n";

	    $return .= 'Minify loaded CSS?                   '. (($settings['minify_loaded_css'] == 1) ? 'Yes' : 'No') . "\n";
	    $return .= 'Minify loaded JS?                    '. (($settings['minify_loaded_js'] == 1) ? 'Yes' : 'No') . "\n";

	    $return .= 'Combine loaded CSS?                  '. (($settings['combine_loaded_css'] == 1) ? 'Yes' : 'No') . "\n";
	    $return .= 'Combine loaded JS?                   '. (($settings['combine_loaded_js'] == 1) ? 'Yes' : 'No') . "\n";

	    $storageCssJsDir = WP_CONTENT_DIR . OptimizeCommon::getRelPathPluginCacheDir();
	    $return .= 'CSS/JS Storage Directory:            '. $storageCssJsDir . ' ('.(is_writable($storageCssJsDir) ? 'writable' : 'NON WRITABLE').')' ."\n\n";

	    $return .= 'Disable Emojis (site-wide)?                       '. (($settings['disable_emojis'] == 1) ? 'Yes' : 'No') . "\n";
        $return .= 'Disable oEmbed (Embeds) (site-wide)?              '. (($settings['disable_oembed'] == 1) ? 'Yes' : 'No') . "\n";
	    $return .= 'Disable Dashicons if Toolbar (top admin bar) is not showing (site-wide)?         '. (($settings['disable_dashicons_for_guests'] == 1) ? 'Yes' : 'No') . "\n";
	    $return .= 'Disable Gutenberg CSS Block Editor (site-wide)?   '. (($settings['disable_wp_block_library'] == 1) ? 'Yes' : 'No') . "\n";
	    $return .= 'Disable jQuery Migrate (site-wide)?               '. (($settings['disable_jquery_migrate'] == 1) ? 'Yes' : 'No') . "\n";
	    $return .= 'Disable Comment Reply (site-wide)?                '. (($settings['disable_comment_reply'] == 1) ? 'Yes' : 'No') . "\n\n";

	    $return .= 'Remove "Really Simple Discovery (RSD)" link tag?  '. (($settings['remove_rsd_link'] == 1) ? 'Yes' : 'No') . "\n";
	    $return .= 'Remove "Windows Live Writer" link tag?            '. (($settings['remove_wlw_link'] == 1) ? 'Yes' : 'No') . "\n";
	    $return .= 'Remove "REST API" link tag?                       '. (($settings['remove_rest_api_link'] == 1) ? 'Yes' : 'No') . "\n";
	    $return .= 'Remove Pages/Posts "Shortlink" tag?               '. (($settings['remove_shortlink'] == 1) ? 'Yes' : 'No') . "\n";
	    $return .= 'Remove "Post\'s Relational Links" tag?             '. (($settings['remove_posts_rel_links'] == 1) ? 'Yes' : 'No') . "\n";
	    $return .= 'Remove "WordPress version" meta tag?              '. (($settings['remove_wp_version'] == 1) ? 'Yes' : 'No') . "\n";
	    $return .= 'Remove All "generator" meta tags?                 '. (($settings['remove_generator_tag'] == 1) ? 'Yes' : 'No') . "\n";
	    $return .= 'Remove Main RSS Feed Link?                        '. (($settings['remove_main_feed_link'] == 1) ? 'Yes' : 'No') . "\n";
	    $return .= 'Remove Comment RSS Feed Link?                     '. (($settings['remove_comment_feed_link'] == 1) ? 'Yes' : 'No') . "\n";

	    $xmlProtocolStatus = 'Enabled (default)';

	    if ($settings['disable_xmlrpc'] === 'disable_pingback') {
		    $xmlProtocolStatus = 'Disable XML-RPC Pingback Only';
	    } elseif ($settings['disable_xmlrpc'] === 'disable_all') {
		    $xmlProtocolStatus = 'Disable XML-RPC Completely';
	    }

	    $return .= "\n" . 'XML-RPC protocol: '. $xmlProtocolStatus . "\n";

	    $return .= "\n" . '# '.WPACU_PLUGIN_TITLE.': CSS/JS Caching Storage'. "\n";

	    $storageStats = OptimizeCommon::getStorageStats();

	    if (isset($storageStats['total_size'], $storageStats['total_files'])) {
		    $return .= 'Total cached files: '.$storageStats['total_files'].' ('.$storageStats['total_size'].') of which '.$storageStats['total_files_assets'].' are CSS/JS assets ('.$storageStats['total_size_assets'].')';
	    } else {
		    $return .= 'Not used';
        }

	    $return .= "\n\n" . '# '.WPACU_PLUGIN_TITLE.': Database Storage';

	    $wpacuPluginId = WPACU_PLUGIN_ID;

	    $wpacuOptionNamesExceptions = array(
		    "'".$wpacuPluginId.'_pro_license_key'."'",
	    );

	    $wpacuSqlPartOptionExceptions = implode(',', $wpacuOptionNamesExceptions);

	    $sqlQueryGetOptions = <<<SQL
SELECT option_name, option_value FROM `{$wpdb->prefix}options`
WHERE option_name LIKE '{$wpacuPluginId}_%' AND option_name NOT IN ({$wpacuSqlPartOptionExceptions})
SQL;
	    $wpacuOptions = $wpdb->get_results($sqlQueryGetOptions, ARRAY_A);

	    $return .= "\n" . 'Table: options'."\n";

	    if (! empty($wpacuOptions)) {
		    foreach ($wpacuOptions as $wpacuOption) {
			    $return .= '-- Option Name: ' . $wpacuOption['option_name'] . ' / Option Value: ' . self::stripKeysWithNoValues($wpacuOption['option_value']) . "\n";
		    }
        } else {
		    $return .= 'No records'."\n";
        }

	    // `usermeta` and `termmeta` might have traces from the Pro version (if ever used)
	    foreach (array('postmeta', 'usermeta', 'termmeta') as $tableBaseName) {
		    // Get all Asset CleanUp (Pro) meta keys from all WordPress meta tables where it can be possibly used
		    $wpacuGetMetaKeysQuery = <<<SQL
SELECT * FROM `{$wpdb->prefix}{$tableBaseName}` WHERE meta_key LIKE '_{$wpacuPluginId}_%'
SQL;
		    $wpacuMetaResults = $wpdb->get_results($wpacuGetMetaKeysQuery, ARRAY_A);

		    $return .= "\n" . 'Table: '.$tableBaseName."\n";

		    if (! empty($wpacuMetaResults)) {
			    foreach ($wpacuMetaResults as $metaResult) {
				    $rowIdVal = '';

			        if (isset($metaResult['post_id'])) {
				        $rowIdVal = 'Post ID: '.$metaResult['post_id'];
                    } elseif (isset($metaResult['user_id'])) {
				        $rowIdVal = 'User ID: '.$metaResult['user_id'];
			        } elseif (isset($metaResult['term_id'])) {
			            $term = get_term($metaResult['term_id']);
				        $rowIdVal = 'Taxonomy Name: '.$term->taxonomy.'; Taxonomy ID: '.$metaResult['term_id'];
			        }

			        $metaValue = $metaResult['meta_value'];

			        if (trim($metaValue) === '[]') { // empty, not relevant
			            continue;
                    }

				    $return .= '-- ' . $rowIdVal . ' / Meta Key: ' . $metaResult['meta_key'] . ' / Meta Value: ' . $metaValue . "\n";
			    }
		    } else {
			    $return .= 'No records'."\n";
            }
	    }

	    $return .= "\n" . '### End System Info ###';

	    return $return;
    }

	/**
	 * @param $maybeJsonValue
	 *
	 * @return false|mixed|string|void
	 */
	public static function stripKeysWithNoValues($maybeJsonValue)
    {
	    $arrayFromJson = @json_decode($maybeJsonValue, true);

	    if (Misc::jsonLastError() !== JSON_ERROR_NONE) {
		    return $maybeJsonValue;
	    }

	    if (is_array($arrayFromJson) && ! empty($arrayFromJson)) {
	        foreach ($arrayFromJson as $key => $value) {
	            if (! $value && empty($value)) {
	                unset($arrayFromJson[$key]);
                }
            }
        }

	    return json_encode($arrayFromJson);
    }

	/**
	 * e.g. error_log file for debugging purposes
	 *
	 * @param $localPathToFile
	 */
	public static function downloadFile($localPathToFile)
    {
	    if (! Menu::userCanManageAssets()) {
		    exit();
	    }

	    $date = date('j-M-Y');
	    $host = parse_url(site_url(), PHP_URL_HOST);

	    header('Content-type: text/plain');
	    header('Content-Disposition: attachment; filename="'.$host.'-website-errors-'.$date.'.log"');

	    echo file_get_contents($localPathToFile);
	    exit();
    }

	/**
	 *
	 */
	public function downloadSystemInfo()
    {
	    if (! Menu::userCanManageAssets()) {
		    exit();
	    }

	    if (! Misc::getVar('post', 'wpacu_get_system_info_nonce')) {
	        return;
        }

	    \check_admin_referer('wpacu_get_system_info', 'wpacu_get_system_info_nonce');

	    $date = date('j-M-Y');
	    $host = parse_url(site_url(), PHP_URL_HOST);

	    header('Content-type: text/plain');
	    header('Content-Disposition: attachment; filename="'.str_replace(' ', '-', strtolower(WPACU_PLUGIN_TITLE)).'-system-info-'.$host.'-'.$date.'.txt"');

	    echo $this->getSystemInfo();
	    exit();
    }

	/**
	 *
	 */
	public function doReset()
	{
		// Several security checks before proceeding with the chosen action
		if (! Misc::getVar('post', 'wpacu_tools_reset_nonce')) {
			return;
		}

		\check_admin_referer('wpacu_tools_reset', 'wpacu_tools_reset_nonce');

		$wpacuResetValue = Misc::getVar('post', 'wpacu-reset', false);

		if (! $wpacuResetValue) {
			exit('Error: Field not found, the action is not valid!');
		}

		// Has to be confirmed
		$wpacuConfirmedValue = Misc::getVar('post', 'wpacu-action-confirmed', false);

		if ($wpacuConfirmedValue !== 'yes') {
			exit('Error: Action needs to be confirmed.');
		}

		if (! Menu::userCanManageAssets()) {
			exit();
		}

		global $wpdb;

		$this->resetChoice = $wpacuResetValue;

		$wpacuPluginId = WPACU_PLUGIN_ID;

		if ($wpacuResetValue === 'reset_settings') {
			delete_option($wpacuPluginId.'_settings');
		} elseif (in_array($wpacuResetValue, array('reset_everything', 'reset_everything_except_settings'))) {
			// `usermeta` and `termmeta` might have traces from the Pro version (if ever used)
			foreach (array('postmeta', 'usermeta', 'termmeta') as $tableBaseName) {
			    // Get all Asset CleanUp (Pro) meta keys from all WordPress meta tables where it can be possibly used
				$wpacuGetMetaKeysQuery = <<<SQL
SELECT meta_key FROM `{$wpdb->prefix}{$tableBaseName}` WHERE meta_key LIKE '_{$wpacuPluginId}_%'
SQL;
				$wpacuMetaKeys = $wpdb->get_col($wpacuGetMetaKeysQuery);

				if ($tableBaseName === 'postmeta') { // e.g. Posts, Pages, Custom Post Types)
				    foreach ($wpacuMetaKeys as $postMetaKey) {
					    delete_post_meta_by_key($postMetaKey);
				    }
                } elseif ($tableBaseName === 'usermeta') { // User Meta: Pro version (if used)
					foreach ($wpacuMetaKeys as $userMetaKey) {
						delete_metadata('user', 0, $userMetaKey, '', true);
					}
                } elseif ($tableBaseName === 'termmeta') { // e.g. Taxonomy: Pro version (if used)
					foreach ($wpacuMetaKeys as $termMetaKey) {
						delete_metadata('term', 0, $termMetaKey, '', true);
					}
                }
			}

			$wpacuOptionNamesExceptions = array(
				"'".$wpacuPluginId.'_pro_license_key'."'",
				"'".$wpacuPluginId.'_pro_license_status'."'"
            );

			// Add "Settings" to the NOT IN list to avoid clearing it
			if ($wpacuResetValue === 'reset_everything_except_settings') {
			    $wpacuOptionNamesExceptions[] = "'".$wpacuPluginId.'_settings'."'";
            }

			$wpacuSqlPartOptionExceptions = implode(',', $wpacuOptionNamesExceptions);

			// Fetch all Asset CleanUp (Pro) options except the license key related ones
			$sqlQueryGetOptions = <<<SQL
SELECT option_name FROM `{$wpdb->prefix}options`
WHERE option_name LIKE '{$wpacuPluginId}_%' AND option_name NOT IN ({$wpacuSqlPartOptionExceptions})
SQL;
			$wpacuOptionNames = $wpdb->get_col($sqlQueryGetOptions);

			foreach ($wpacuOptionNames as $wpacuOptionName) {
			    delete_option($wpacuOptionName);
            }

			// Remove transients
			$sqlQueryGetTransients = <<<SQL
SELECT option_name FROM `{$wpdb->prefix}options`
WHERE option_name LIKE '_transient_{$wpacuPluginId}_%' OR option_name LIKE '_transient_timeout_{$wpacuPluginId}_%'
OR option_name LIKE '_transient_wpacu_%' OR option_name LIKE '_transient_timeout_wpacu_%'
SQL;
			$wpacuTransientNames = $wpdb->get_col($sqlQueryGetTransients);

			foreach ($wpacuTransientNames as $wpacuTransientName) {
				$wpacuTransientName = str_replace(array('_transient_timeout_', '_transient_'), '', $wpacuTransientName);
				delete_transient($wpacuTransientName);
			}

			// Remove the license data?
			if (Misc::getVar('post', 'wpacu-remove-license-data') !== '') {
				delete_option($wpacuPluginId . '_pro_license_key');
				delete_option($wpacuPluginId . '_pro_license_status');
				$this->licenseDataRemoved = true;
			}

			// Remove all cached CSS/JS files?
			if (Misc::getVar('post', 'wpacu-remove-cache-assets') !== '') {
				$pathToCacheDirCss = WP_CONTENT_DIR . OptimizeCss::getRelPathCssCacheDir();
				$pathToCacheDirJs  = WP_CONTENT_DIR . OptimizeJs::getRelPathJsCacheDir();

				$allCssFiles = glob( $pathToCacheDirCss . '**/*.css' );
				$allJsFiles  = glob( $pathToCacheDirJs . '**/*.js' );
				$allCachedAssets = array_merge($allCssFiles, $allJsFiles);

				if (! empty($allCachedAssets)) {
				    foreach ($allCachedAssets as $cachedAssetFile) {
				        @unlink($cachedAssetFile);
                    }
                }

				$this->cachedAssetsRemoved = true;
            }

			// Remove Asset CleanUp (Pro)'s cache transients
            $this->clearAllCacheTransients();
		}

		// Also make 'jQuery Migrate' and 'Comment Reply' core files to load again
		// As they were enabled (not unloaded) in the default settings
        $wpacuUpdate = new Update();
        $wpacuUpdate->removeEverywhereUnloads(
            array(),
            array('jquery-migrate' => 'remove', 'comment-reply' => 'remove')
        );

        set_transient('wpacu_reset_done',
            json_encode(array(
	                'reset_choice'          => $this->resetChoice,
	                'license_data_removed'  => $this->licenseDataRemoved,
	                'cached_assets_removed' => $this->cachedAssetsRemoved
	            )
            ),
            30
        );

        wp_redirect(admin_url('admin.php?page=wpassetcleanup_tools&wpacu_time='.time()));
        exit;
	}

	/**
	 * Remove Asset CleanUp (Pro)'s Cache Transients
	 */
	public function clearAllCacheTransients()
    {
        global $wpdb;

	    // Remove Asset CleanUp (Pro)'s cache transients
	    $transientLikes = array(
		    '_transient_wpacu_css_',
		    '_transient_wpacu_js_'
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
	 *
	 */
	public function resetDone()
	{
		$msg = '';

		if ($this->resetChoice === 'reset_settings') {
			$msg = __('All the settings were reset to their default values.', 'wp-asset-clean-up');
		} elseif ($this->resetChoice === 'reset_everything_except_settings') {
			$msg = __('Everything except the "Settings" was reset (including page &amp; bulk unloads, load exceptions).', 'wp-asset-clean-up');
        } elseif ($this->resetChoice === 'reset_everything') {
			$msg = __('Everything was reset (including settings, individual &amp; bulk unloads, load exceptions) to the same point it was when you first activated the plugin.', 'wp-asset-clean-up');

			if ($this->licenseDataRemoved) {
				$msg .= ' <span id="wpacu-license-data-removed-msg">'.__('The license information was also removed.', 'wp-asset-clean-up').'</span>';
			}

			if ($this->cachedAssetsRemoved) {
				$msg .= ' <span id="wpacu-cached-assets-removed-msg">'.__('The cached CSS/JS files were also removed.', 'wp-asset-clean-up').'</span>';
			}
		}
		?>
		<div class="updated notice wpacu-notice wpacu-reset-notice is-dismissible">
			<p><span class="dashicons dashicons-yes"></span> <?php echo $msg; ?></p>
		</div>
		<?php
	}

	/**
	 *
	 */
	public function importDone()
    {
        if (empty($this->data['import_done_list'])) {
            return;
        }

	    $importedMessage = __('The following were imported:', 'wp-asset-clean-up');

	    $importedMessage .= '<ul style="list-style: disc; padding-left: 30px; margin-bottom: 0;">';

	    foreach ($this->data['import_done_list'] as $importedKey) {
            if ($importedKey === 'settings') {
	            $importedMessage .= '<li>"'.__('Settings', 'wp-asset-clean-up').'"</li>';
            } elseif ($importedKey === 'homepage_unloads') {
	            $importedMessage .= '<li>'.__('Homepage Unload Rules', 'wp-asset-clean-up').'</li>';
            } elseif ($importedKey === 'homepage_exceptions') {
	            $importedMessage .= '<li>'.__('Homepage Load Exceptions (for site-wide and bulk unloads)', 'wp-asset-clean-up').'</li>';
            } elseif ($importedKey === 'sitewide_unloads') {
	            $importedMessage .= '<li>'.__('Site-wide unloads', 'wp-asset-clean-up').'</li>';
            } elseif ($importedKey === 'bulk_unloads') {
	            $importedMessage .= '<li>'.__('Bulk Unloads (e.g. for all pages of `post` post type)', 'wp-asset-clean-up').'</li>';
            } elseif ($importedKey === 'posts_metas') {
	            $importedMessage .= '<li>'.__('Posts, Pages &amp; Custom Post Types: Rules &amp; Page Options (Side Meta Box)', 'wp-asset-clean-up').'</li>';
            }
        }

	    $importedMessage .= '</ul>';
        ?>
        <div class="clearfix"></div>
        <div class="updated notice wpacu-notice wpacu-imported-notice is-dismissible">
            <p><span class="dashicons dashicons-yes"></span> <?php echo $importedMessage; ?></p>
            <p>If you're using a caching plugin (e.g. WP Rocket, WP Fastest Cache, W3 Total Cache etc.) it's recommended to clear its cache if the website is working as you expect after this import, so the changes will take effect for every visitor.</p>
        </div>
        <?php
	    $this->data['import_done_list'] = array(); // reset it to avoid showing it twice
    }
}
