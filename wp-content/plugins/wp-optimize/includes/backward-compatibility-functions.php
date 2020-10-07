<?php
/**
 * Normalize a filesystem path.
 */
if (!function_exists('wp_normalize_path')) {
	/**
	 * WordPress function to normalize a filesystem path; was added to WP core in WP 3.9
	 *
	 * @see wp_normalize_path() https://developer.wordpress.org/reference/functions/wp_normalize_path/#source for the original source code
	 *
	 * @param string $path Path to normalize.
	 * @return string Normalized path.
	 */
	function wp_normalize_path($path) {
		$wrapper = '';
		if (wp_is_stream($path)) {
			list($wrapper, $path) = explode('://', $path, 2);
			$wrapper .= '://';
		}
		// Standardise all paths to use /
		$path = str_replace('\\', '/', $path);
		// Replace multiple slashes down to a singular, allowing for network shares having two slashes.
		$path = preg_replace('|(?<=.)/+|', '/', $path);
		// Windows paths should uppercase the drive letter
		if (':' === substr($path, 1, 1)) {
			$path = ucfirst($path);
		}
		return $wrapper.$path;
	}
}
