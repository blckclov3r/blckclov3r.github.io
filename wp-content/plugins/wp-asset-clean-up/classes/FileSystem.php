<?php
namespace WpAssetCleanUp;

/**
 * Class FileSystem
 * @package WpAssetCleanUp
 */
class FileSystem
{
	/**
	 * @return bool|\WP_Filesystem_Direct
	 */
	public static function init()
	{
		// Set the permission constants if not already set.
		if ( ! defined('FS_CHMOD_DIR') ) {
			define('FS_CHMOD_DIR', fileperms(ABSPATH) & 0777 | 0755);
		}

		if ( ! defined('FS_CHMOD_FILE') ) {
			define('FS_CHMOD_FILE', fileperms(ABSPATH . 'index.php') & 0777 | 0644);
		}

		if (! defined('WPACU_FS_USED') && ! class_exists('\WP_Filesystem_Base') && ! class_exists('\WP_Filesystem_Direct')) {
			$wpFileSystemBase   = ABSPATH . 'wp-admin/includes/class-wp-filesystem-base.php';
			$wpFileSystemDirect = ABSPATH . 'wp-admin/includes/class-wp-filesystem-direct.php';

			if (is_file($wpFileSystemBase) && is_file($wpFileSystemDirect)) {
				// Make sure to use the 'direct' method as it's the most effective in this scenario
				require_once ABSPATH . 'wp-admin/includes/class-wp-filesystem-base.php';
				require_once ABSPATH . 'wp-admin/includes/class-wp-filesystem-direct.php';
				define('WPACU_FS_USED', true);
			} else {
				// Do not use WordPress FileSystem Direct (fallback to default PHP functions)
				define('WPACU_FS_USED', false);
			}
		}

		if (defined('WPACU_FS_USED') && WPACU_FS_USED === true) {
			return new \WP_Filesystem_Direct( new \StdClass() );
		}

		return false;
	}

	/**
	 * @param $localPathToFile
	 * @param string $alter
	 *
	 * @return false|string
	 */
	public static function file_get_contents($localPathToFile, $alter = '')
	{
		// ONLY relevant for CSS files
		if ($alter === 'combine_css_imports') {
			// This custom class does not minify as it's custom made for combining @import
			$optimizer = new \WpAssetCleanUp\OptimiseAssets\CombineCssImports($localPathToFile);
			return $optimizer->minify();
		}

		// Fallback
		if (! self::init()) {
			return @file_get_contents($localPathToFile);
		}

		return self::init()->get_contents($localPathToFile);
	}

	/**
	 * @param $localPathToFile
	 * @param $contents
	 *
	 * @return bool|int|void
	 */
	public static function file_put_contents($localPathToFile, $contents)
	{
		// Fallback
		if (! self::init()) {
			$return = @file_put_contents($localPathToFile, $contents);
		} else {
			$return = self::init()->put_contents( $localPathToFile, $contents, FS_CHMOD_FILE );
		}

		if (! $return) {
			error_log('Asset CleanUp: Could not write to '.$localPathToFile);
		}

		return $return;
	}
}
