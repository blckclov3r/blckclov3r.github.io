<?php

/**
 * Fields Helper
 *
 * @link       https://www.feataholic.com
 * @since      1.1.8 Free
 *
 * @package    MZLDR
 * @subpackage MZLDR/includes/helpers
 */

/**
 * Fields Helper
 *
 * @since      1.1.8 Free
 * @package    MZLDR
 * @subpackage MZLDR/includes/helpers
 * @author     Your Name <email@example.com>
 */
class MZLDR_Fields_Helper {

	/**
	 * Camelizes input
	 * 
	 * @param   string  $input
	 * @param   string  $separator
	 * 
	 * @return  string
	 */
	public static function camelize($input, $separator = '_') {
		if ($input == 'custom_html') {
			return 'Custom HTML';
		}
		
		
		return str_replace($separator, ' ', ucwords($input, $separator));
	}

	/**
	 * Returns part of the generate() function name of the field
	 * 
	 * @param   string  $input
	 * 
	 * @return  string
	 */
	public static function getFieldGenerateFunctionName($input) {
		$input = ucfirst( $input );
		return str_replace('_', '', ucwords($input, '_'));
	}

}
