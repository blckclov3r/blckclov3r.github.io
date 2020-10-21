<?php
namespace WpAssetCleanUp;

/**
 * Class PluginsManager
 * @package WpAssetCleanUp
 */
class PluginsManager
{
    /**
     * @var array
     */
    public $data = array();

	/**
	 *
	 */
	public function page()
    {
    	// Get active plugins and their basic information
	    $this->data['active_plugins'] = self::getActivePlugins();
	    $this->data['plugins_icons']  = Misc::getAllActivePluginsIcons();

	   // echo '<pre>'; print_r($this->data['plugins_icons']);
	    Main::instance()->parseTemplate('admin-page-plugins-manager', $this->data, true);
    }

	/**
	 * @return array
	 */
	public static function getActivePlugins()
    {
	    $activePluginsFinal = array();

	    // Get active plugins and their basic information
	    $activePlugins = array_unique(get_option('active_plugins', array()));

	    foreach ($activePlugins as $plugin) {
		    // Skip Asset CleanUp as it's obviously needed for the functionality
		    if (strpos($plugin, 'wp-asset-clean-up') !== false) {
			    continue;
		    }

		    $pluginData = get_plugin_data(WP_CONTENT_DIR . '/plugins/'.$plugin);
		    $activePluginsFinal[] = array('title' => $pluginData['Name'], 'path' => $plugin);
	    }

	    usort($activePluginsFinal, static function($a, $b)
	    {
		    return strcmp($a['title'], $b['title']);
	    });

	    return $activePluginsFinal;
	}
}
