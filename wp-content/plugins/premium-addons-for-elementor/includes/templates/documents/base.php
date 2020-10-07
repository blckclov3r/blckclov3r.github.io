<?php

namespace PremiumAddons\Includes\Templates\Documents;

use Elementor\Core\Base\Document as Document;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Premium_Document_Base extends Document {
    
	public function get_name() {
		return '';
	}

	public static function get_title() {
		return '';
	}

	public function has_conditions() {
		return true;
	}

	public function get_preview_as_query_args() {
		return array();
	}

}
