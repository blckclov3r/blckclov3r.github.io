<?php
namespace WpAssetCleanUp;

/**
 * Gets information pages such as "Getting Started", "Help" and "Info"
 * Retrieves specific information about a plugin or a theme
 *
 * Class Info
 * @package WpAssetCleanUp
 */
class Info
{
	/**
	 * Info constructor.
	 */
	public function __construct()
	{
		add_action('wpacu_assets_plugin_notice_table_row', array($this, 'pluginNotice'));
	}

	/**
	 *
	 */
	public function gettingStarted()
	{
		$data = array('for' => 'how-it-works');

		if (array_key_exists('wpacu_for', $_GET)) {
			$data['for'] = sanitize_text_field($_GET['wpacu_for']);
		}

		Main::instance()->parseTemplate('admin-page-getting-started', $data, true);
	}

    /**
     *
     */
    public function help()
    {
        Main::instance()->parseTemplate('admin-page-get-help', array(), true);
    }

	/**
	 *
	 */
	public function pagesInfo()
    {
	    Main::instance()->parseTemplate('admin-page-pages-info', array(), true);
    }

	/**
	 *
	 */
	public function license()
	{
		Main::instance()->parseTemplate('admin-page-license', array(), true);
	}

	/**
	 * @param $locationChild
	 * @param $allPlugins
	 * @param $allActivePluginsIcons
	 *
	 * @return string
	 */
	public static function getPluginInfo($locationChild, $allPlugins, $allActivePluginsIcons)
	{
		foreach (array_keys($allPlugins) as $pluginFile) {
			if (strpos($pluginFile, $locationChild.'/') === 0) {
				$imageIconStyle = $classIconStyle = '';

				if (isset($allActivePluginsIcons[$locationChild]) && $allActivePluginsIcons[$locationChild]) {
					$classIconStyle = 'has-icon';
					$imageIconStyle = 'style="background: transparent url(\''.$allActivePluginsIcons[$locationChild].'\') no-repeat 0 0; background-size: cover;"';
				}

				return '<div class="icon-plugin-default '.$classIconStyle.'"><div class="icon-area" '.$imageIconStyle.'></div></div> &nbsp; <span class="wpacu-child-location-name">'.$allPlugins[$pluginFile]['Name'].'</span>' . ' <span class="wpacu-child-location-version">v'.$allPlugins[$pluginFile]['Version'].'</span>';
			}
		}

		return $locationChild;
	}

	/**
	 * @param $locationChild
	 * @param $allThemes
	 *
	 * @return array
	 */
	public static function getThemeInfo($locationChild, $allThemes)
	{
		foreach (array_keys($allThemes) as $themeDir) {
			if ($locationChild === $themeDir) {
				$themeInfo = wp_get_theme($themeDir);
				$themeIconUrl = Misc::getThemeIcon($themeInfo->get('Name'));

				$themeIconHtml = '';
				$hasIcon = false;

				if ($themeIconUrl) {
					$hasIcon = true;
					$imageIconStyle = 'style="background: transparent url(\''.$themeIconUrl.'\') no-repeat 0 0; background-size: cover;"';
					$themeIconHtml  = '<div class="icon-theme has-icon"><div class="icon-area" '.$imageIconStyle.'></div></div>';
				}

				$output = $themeIconHtml . $themeInfo->get('Name') . ' <span class="wpacu-child-location-version">v'.$themeInfo->get('Version').'</span>';

				return array('has_icon' => $hasIcon, 'output' => $output);
			}
		}

		return array('has_icon' => false, 'output' => $locationChild);
	}

	/**
	 * Notices about consequences in unloading assets from specific plugins
	 *
	 * @param $plugin
	 */
	public function pluginNotice($plugin)
	{
		$output = '';

		// Elementor, Elementor Pro
		if (in_array($plugin, array('elementor', 'elementor-pro'))) {
			$wpacuPluginTitle = WPACU_PLUGIN_TITLE;

			$output = <<<HTML
<tr class="wpacu_asset_row wpacu_notice_row">
	<td valign="top">
		<div class="wpacu-warning">
			<p style="margin: 0 0 4px !important;"><small><span class="dashicons dashicons-warning"></span> Most (if not all) of this plugin's files are linked (child &amp; parent) for maximum compatibility. Unloading one Elementor CSS/JS will likely trigger the unloading of other "children" associated with it.  <strong>To avoid breaking the Elementor editor, {$wpacuPluginTitle} is deactivated in the page builder's edit &amp; preview mode. If this page is not edited via Elementor and you don't need any of the plugin's functionality (widgets, templates etc.) here, you can unload the files below making sure to test the page after you updated it.</strong></small></p>
		</div>
	</td>
</tr>
HTML;
		}

		echo $output;
	}
}
