<?php
namespace WpAssetCleanUp\OptimiseAssets;

use WpAssetCleanUp\Main;
use WpAssetCleanUp\Plugin;

/**
 * Class LocalFonts
 * @package WpAssetCleanUp\OptimiseAssets
 */
class FontsLocal
{
	/**
	 *
	 */
	public function init()
	{
		add_action('wp_head', array($this, 'preloadFontFiles'), 1);
	}

	/**
	 *
	 */
	public function preloadFontFiles()
	{
		// AMP page or Test Mode? Do not print anything
		if (Plugin::preventAnyChanges() || Main::isTestModeActive()) {
			return;
		}

		if (! $preloadFontFiles = trim(Main::instance()->settings['local_fonts_preload_files'])) {
			return;
		}

		$preloadFontFilesArray = array();

		if (strpos($preloadFontFiles, "\n") !== false) {
			foreach (explode("\n", $preloadFontFiles) as $preloadFontFile) {
				$preloadFontFile = trim($preloadFontFile);

				if (! $preloadFontFile) {
					continue;
				}

				$preloadFontFilesArray[] = $preloadFontFile;
			}
		} else {
			$preloadFontFilesArray[] = $preloadFontFiles;
		}

		$preloadFontFilesArray = array_unique($preloadFontFilesArray);

		$preloadFontFilesOutput = '';

		// Finally, go through the list
		foreach ($preloadFontFilesArray as $preloadFontFile) {
			$preloadFontFilesOutput .= '<link rel="preload" as="font" href="'.esc_attr($preloadFontFile).'" data-wpacu-preload-font="1" crossorigin>'."\n";
		}

		echo apply_filters('wpacu_preload_local_font_files_output', $preloadFontFilesOutput);
	}
}
