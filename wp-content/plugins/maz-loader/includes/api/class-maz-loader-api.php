<?php

/**
 * MAZ Loader API
 *
 * @link       https://www.feataholic.com
 * @since      1.1.8 Free
 *
 * @package    MZLDR
 * @subpackage MZLDR/includes/forms
 */

/**
 * MAZ Loader API.
 *
 * Provides helper methods to run all form fields.
 *
 * @since      1.1.8 Free
 * @package    MZLDR
 * @subpackage MZLDR/includes/forms
 * @author     Your Name <email@example.com>
 */
class MZLDR_API {

	private $namespace;

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		$this->namespace = 'maz-loader/loader';
	}

	/**
	 * Run all of the plugin functions.
	 *
	 * @since 1.0.0
	 */
	public function run() {
		add_action( 'rest_api_init', array( $this, 'loaders' ) );
	}

	/**
	 * Register REST API
	 */
	public function loaders() {
		// Council
		register_rest_route(
			$this->namespace,
			'loaders',
			array(
				'methods'             => 'GET',
				'callback'            => array( $this, 'get_loaders' ),
				'permission_callback' => function () {
					return current_user_can( 'edit_posts' );
				},
			)
		);
	}

	/**
	 * Get the user roles
	 *
	 * @return $roles JSON feed of returned objects
	 */
	public function get_loaders() {
		
		$loader_model = new MZLDR_Loader_Model();

		$args = [
			'where' => 'WHERE l.published = 1'
		];
		$loaders = $loader_model->getLoaders($args);

		$data = [];

		foreach ($loaders as $loader) {
			$data[] = [
				'id' => $loader->id,
				'title' => $loader->name
			];
		}

		return $data;
	}

}