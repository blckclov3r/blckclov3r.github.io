<?php

namespace PremiumAddons\Includes\Templates\Documents;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Premium_Section_Document extends Premium_Document_Base {
    
     public function get_name() {
		return 'premium_page';
	}

	public static function get_title() {
		return __( 'Section', 'premium-addons-for-elementor' );
	}

	public function has_conditions() {
		return false;
	}

}