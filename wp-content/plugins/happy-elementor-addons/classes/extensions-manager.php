<?php
namespace Happy_Addons\Elementor;

use Elementor\Core\Common\Modules\Finder\Categories_Manager;

defined( 'ABSPATH' ) || die();

class Extensions_Manager {

    /**
     * Initialize
     */
    public static function init() {
		include_once HAPPY_ADDONS_DIR_PATH . 'extensions/column-extended.php';
		include_once HAPPY_ADDONS_DIR_PATH . 'extensions/widgets-extended.php';

		if ( ha_is_background_overlay_enabled() ) {
			include_once HAPPY_ADDONS_DIR_PATH . 'extensions/background-overlay.php';
		}

		if ( ha_is_grid_layer_enabled() ) {
			include_once HAPPY_ADDONS_DIR_PATH . 'extensions/happy-grid.php';
		}

		if ( ha_is_wrapper_link_enabled() ) {
			include_once HAPPY_ADDONS_DIR_PATH . 'extensions/wrapper-link.php';
		}

		if ( ha_is_floating_effects_enabled() || ha_is_css_transform_enabled() ) {
			include_once HAPPY_ADDONS_DIR_PATH . 'extensions/happy-effects.php';
		}

		if ( is_user_logged_in() && ha_is_adminbar_menu_enabled() ) {
			include_once HAPPY_ADDONS_DIR_PATH . 'classes/admin-bar.php';
		}

		if ( is_user_logged_in() && ha_is_happy_clone_enabled() ) {
			add_action( 'elementor/finder/categories/init', [ __CLASS__, 'register_finder' ] );

			include_once HAPPY_ADDONS_DIR_PATH . 'classes/clone-handler.php';
		}
	}

	/**
     * Register finder category and category items
     *
     * @param $categories_manager
     */
    public static function register_finder( Categories_Manager $categories_manager ) {
        include_once HAPPY_ADDONS_DIR_PATH . 'classes/finder-edit.php';
        $categories_manager->add_category( Finder_Edit::SLUG, new Finder_Edit() );
	}
}

Extensions_Manager::init();
