<?php
if (!defined('ABSPATH')) die('No direct access allowed');

class WP_Optimize_Minify_Fonts {
	/**
	 * Get a list of Google fonts
	 *
	 * @return Array
	 */
	public static function get_google_fonts() {
		// https://www.googleapis.com/webfonts/v1/webfonts?sort=alpha
		$google_fonts_file = WPO_PLUGIN_MAIN_PATH.'google-fonts.json';
		if (is_file($google_fonts_file) && is_readable($google_fonts_file)) {
			return json_decode(file_get_contents($google_fonts_file), true);
		}
		return array();
	}

	/**
	 * Check if the google font exist or not
	 *
	 * @param string $font
	 * @return boolean
	 */
	public static function concatenate_google_fonts_allowed($font) {
		$gfonts_whitelist = self::get_google_fonts();

		// normalize
		$font = str_ireplace('+', ' ', strtolower($font));
		
		return in_array($font, $gfonts_whitelist);
	}

	/**
	 * Concatenate Google Fonts tags (http://fonts.googleapis.com/css?...)
	 *
	 * @param array $gfonts_array
	 * @return string|boolean
	 */
	public static function concatenate_google_fonts($gfonts_array) {
		// process names, weights, subsets
		$fonts = array();
		$subsets = array();
		// extract font families
		foreach ($gfonts_array as $font) {
			// Parse the font URL: Puts the query parameters in an array.
			parse_str(parse_url($font, PHP_URL_QUERY), $font_elements);
			// Process each font family
			foreach (explode('|', $font_elements['family']) as $font_family) {
				// Separate font and sizes
				$font_family = explode(':', $font_family);
				// if the family wasn't added yet
				if (!in_array($font_family[0], array_keys($fonts))) {
					$fonts[$font_family[0]] = isset($font_family[1]) ? explode(',', $font_family[1]) : array();
				} else {
					// if the family was already added, and this new one has weights, merge with previous
					if (isset($font_family[1])) {
						$fonts[$font_family[0]] = array_merge($fonts[$font_family[0]], explode(',', $font_family[1]));
					}
				}
			}

			// Add subsets
			if (isset($font_elements['subset'])) {
				$subsets = array_merge($subsets, explode(',', $font_elements['subset']));
			}
		}
		
		// build font names with font weights, if allowed
		$build = array();
		foreach ($fonts as $font_name => $font_weights) {
			if (self::concatenate_google_fonts_allowed($font_name)) {
				$f = $font_name;
				if (count($font_weights) > 0) {
					$f.= ':'. implode(',', array_unique($font_weights));
				}
				$build[] = $f;
			}
		}

		// merge, append subsets
		$merge = '';
		if (count($build) > 0) {
			$merge = str_replace(' ', '+', implode('|', $build));
			// Maybe add subsets
			if (count($subsets) > 0) {
				$merge.= '&subset='.implode(',', array_unique($subsets));
			}
			$config = wp_optimize_minify_config();
			/**
			 * Filters wether to add display=swap to Google fonts urls
			 *
			 * @param boolean $display - Default to true
			 */
			if (apply_filters('wpo_minify_gfont_display_swap', $config->get('enable_display_swap'))) {
				/**
				 * Filters the value of the display parameter.
				 *
				 * @param string $display_value - Default to 'swap'. https://developer.mozilla.org/en-US/docs/Web/CSS/@font-face/font-display
				 */
				$merge.= '&display='.apply_filters('wpo_minify_gfont_display_type', 'swap');
			}
		}

		if (!empty($merge)) return 'https://fonts.googleapis.com/css?family='.$merge;

		return false;
	}
}
