<?php

/**
 * Homepage Publishing Rule
 *
 * @link       https://www.feataholic.com
 * @since      1.1.8 Free
 *
 * @package    MZLDR
 * @subpackage MZLDR/includes/helpers
 */

/**
 * Homepage Publishing Rule
 *
 * @since      1.1.8 Free
 * @package    MZLDR
 * @subpackage MZLDR/includes/helpers
 * @author     Your Name <email@example.com>
 */
class MZLDR_Homepage_Rule extends MZLDR_Publishing_Rules {

	private $rule;
	
	function __construct($rule) {
		$this->rule = $rule;
	}

	public function pass() {
		$pass = false;

		if ( is_front_page() && is_home() ) {
			$pass = true;
		} elseif ( is_front_page()){
			$pass = true;
		}

		if ($pass) {
			if ($this->rule->type == 'exclude') {
				$pass = false;
			} else {
				$pass = true;
			}
		}
		
		return $pass;
	}

	
	public static function freeCanPass($loader) {
		if (empty($loader)) {
			return false;
		}

		$loader_model = new MZLDR_Loader_Model();

		$parsed_data = $loader_model->parseLoaderData($loader[0]);

		
		$pass = false;
		
		$show_on_homepage = isset($parsed_data->settings->show_on_homepage) ? $parsed_data->settings->show_on_homepage : 'off';
		
		if ($show_on_homepage == 'on') {
			if ( is_front_page() && is_home() ) {
				$pass = true;
			} elseif ( is_front_page()){
				$pass = true;
			}
		} else {
			$pass = true;
		}

		return $pass;
	}
	

}