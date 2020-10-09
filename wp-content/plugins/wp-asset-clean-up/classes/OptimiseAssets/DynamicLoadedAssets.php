<?php
namespace WpAssetCleanUp\OptimiseAssets;

/**
 * Class DynamicLoadedAssets
 * @package WpAssetCleanUp
 */
class DynamicLoadedAssets
{
	/**
	 * @param $from
	 * @param $value
	 *
	 * @return bool|mixed|string
	 */
	public static function getAssetContentFrom($from, $value)
	{
		$assetContent = '';

		if ($from === 'simple-custom-css') {
			/*
			 * Special Case: "Simple Custom CSS" Plugin
			 *
			 * /?sccss=1
			 *
			 * As it is (no minification or optimization), it adds extra load time to the page
			 * as the CSS is read via PHP and all the WP environment is loading
			 */
			if (! $assetContent = self::getSimpleCustomCss()) {
				return false;
			}
		}

		if ($from === 'dynamic') { // /? .php? etc.
			if (! OptimizeCommon::isSourceFromSameHost($value->src)) {
				return array();
			}

			$response = wp_remote_get(
				$value->src
				);

			if (wp_remote_retrieve_response_code($response) !== 200) {
				return false;
			}

			if (! $assetContent = wp_remote_retrieve_body($response)) {
				return false;
			}
		}

		return $assetContent;
	}

	/**
	 * "Simple Custom CSS" (better retrieval, especially for localhost and password protected sites)
	 *
	 * @return mixed|string
	 */
	public static function getSimpleCustomCss()
	{
		$sccssOptions    = get_option('sccss_settings');
		$sccssRawContent = isset($sccssOptions['sccss-content']) ? $sccssOptions['sccss-content'] : '';
		$cssContent      = wp_kses($sccssRawContent, array('\'', '\"'));
		$cssContent      = str_replace('&gt;', '>', $cssContent);

		return trim($cssContent);
	}
}
