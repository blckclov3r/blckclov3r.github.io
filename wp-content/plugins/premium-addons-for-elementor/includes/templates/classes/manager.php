<?php

namespace PremiumAddons\Includes\Templates\Classes;

use PremiumAddons\Includes\Templates;

if( ! defined( 'ABSPATH') ) exit; 

if ( ! class_exists( 'Premium_Templates_Manager' ) ) {

	/**
     * Premium Templates Manager.
     *
     * Templates manager class handles all templates library insertion
     *
     * @since 3.6.0
     * 
     */
	class Premium_Templates_Manager {

		private static $instance = null;

		private $sources = array();

        /**
         * Premium_Templates_Manager constructor.
         *
         * initialize required hooks for templates.
         * 
         * @since 3.6.0
         * @access public
         * 
         */
		public function __construct() {
            
            //Register AJAX hooks
			add_action( 'wp_ajax_premium_get_templates', array( $this, 'get_templates' ) );
            add_action( 'wp_ajax_premium_inner_template', array( $this, 'insert_inner_template' ) );
            
            
            if ( defined( 'ELEMENTOR_VERSION' ) && version_compare( ELEMENTOR_VERSION, '2.2.8', '>' ) ) {
				add_action( 'elementor/ajax/register_actions', array( $this, 'register_ajax_actions' ), 20 );
			} else {
				add_action( 'wp_ajax_elementor_get_template_data', array( $this, 'get_template_data' ), -1 );
			}
            
            $this->register_sources();

			add_filter( 'premium-templates-core/assets/editor/localize', array( $this, 'localize_tabs' ) );

		}

		/**
         * Localize tabs
         * 
		 * Add tabs data to localize object
		 *
         * @since 3.6.0
         * @access public
         * 
		 * @return [type] [description]
		 */
		public function localize_tabs( $data ) {
            
			$tabs    = $this->get_template_tabs();
			$ids     = array_keys( $tabs );
			$default = $ids[0];

			$data['tabs']       = $this->get_template_tabs();
			$data['defaultTab'] = $default;

			return $data;

		}

		/**
         * Register sources
         * 
		 * Register templates sources.
		 *
         * @since 3.6.0
         * @access public
         * 
		 * @return void
		 */
		public function register_sources() {

			require PREMIUM_ADDONS_PATH . 'includes/templates/sources/base.php';

            $namespace = str_replace( 'Classes', 'Sources' , __NAMESPACE__ );
            
			$sources = array(
				'premium-api'   =>  $namespace . '\Premium_Templates_Source_Api',
			);

			foreach ( $sources as $key => $class ) {
                
				require PREMIUM_ADDONS_PATH . 'includes/templates/sources/' . $key . '.php';
                
				$this->add_source( $key, $class );
			}

		}

		/**
         * Get template tabs
         * 
		 * Get tabs for the library.
		 *
         * @since 3.6.0
         * @access public
         * 
		 */
		public function get_template_tabs() {

			$tabs = Templates\premium_templates()->types->get_types_for_popup();
            
			return $tabs;

		}

		/**
         * Get template tabs
         * 
		 * Get tabs for the library.
		 *
         * @since 3.6.0
         * @access public
         * 
         * @param $key source key 
         * @param $class source class
         * 
		 */
		public function add_source( $key, $class ) {
			$this->sources[ $key ] = new $class();
		}

		/**
		 * Returns needed source instance
		 *
		 * @return object
		 */
		public function get_source( $slug = null ) {
			return isset( $this->sources[ $slug ] ) ? $this->sources[ $slug ] : false;
		}

		
		/**
         * Get template
         * 
		 * Get templates grid data.
		 *
         * @since 3.6.0
         * @access public
         * 
		 */
		public function get_templates() {

			if ( ! current_user_can( 'edit_posts' ) ) {
				wp_send_json_error();
			}

			$tab     = $_GET['tab'];
			$tabs    = $this->get_template_tabs();
			$sources = $tabs[ $tab ]['sources'];

			$result = array(
				'templates'  => array(),
				'categories' => array(),
				'keywords'   => array(),
			);

            foreach ( $sources as $source_slug ) {

				$source = isset( $this->sources[ $source_slug ] ) ? $this->sources[ $source_slug ] : false;

				if ( $source ) {
					$result['templates']  = array_merge( $result['templates'], $source->get_items( $tab ) );
					$result['categories'] = array_merge( $result['categories'], $source->get_categories( $tab ) );
                    $result['keywords']   = array_merge( $result['keywords'], $source->get_keywords( $tab ) );
				}

			}

			
			$all_cats = array(
				array(
					'slug' => '',
					'title' => __( 'All', 'premium-addons-for-elementor' ),
				)
			);

			if ( ! empty( $result['categories'] ) ) {
				$result['categories'] = array_merge( $all_cats, $result['categories'] );
			}

			wp_send_json_success( $result );

		}
        
        /**
         * Insert inner template
         * 
		 * Insert an inner template before insert the parent one.
		 *
         * @since 3.6.0
         * @access public
         * 
		 */
		public function insert_inner_template() {

			if ( ! current_user_can( 'edit_posts' ) ) {
				wp_send_json_error();
			}

			$template = isset( $_REQUEST['template'] ) ? $_REQUEST['template'] : false;

			if ( ! $template ) {
				wp_send_json_error();
			}

			$template_id = isset( $template['template_id'] ) ? esc_attr( $template['template_id'] ) : false;
			$source_name = isset( $template['source'] ) ? esc_attr( $template['source'] ) : false;
			$source      = isset( $this->sources[ $source_name ] ) ? $this->sources[ $source_name ] : false;

			if ( ! $source || ! $template_id ) {
				wp_send_json_error();
			}

			$template_data = $source->get_item( $template_id );

			if ( ! empty( $template_data['content'] ) ) {
				wp_insert_post( array(
					'post_type'   => 'elementor_library',
					'post_title'  => $template['title'],
					'post_status' => 'publish',
					'meta_input'  => array(
						'_elementor_data'          => $template_data['content'],
						'_elementor_edit_mode'     => 'builder',
						'_elementor_template_type' => 'section',
					),
				) );
			}

			wp_send_json_success();

		}
        
        /**
         * Register AJAX actions
         * 
		 * Add new actions to handle data after an AJAX requests returned.
		 *
         * @since 3.6.0
         * @access public
         * 
		 */
		public function register_ajax_actions( $ajax_manager ) {

			if ( ! isset( $_POST['actions'] ) ) {
				return;
			}

			$actions     = json_decode( stripslashes( $_REQUEST['actions'] ), true );
			$data        = false;

			foreach ( $actions as $id => $action_data ) {
				if ( ! isset( $action_data['get_template_data'] ) ) {
					$data = $action_data;
				}
			}

			if ( ! $data ) {
				return;
			}

			if ( ! isset( $data['data'] ) ) {
				return;
			}

			if ( ! isset( $data['data']['source'] ) ) {
				return;
			}

			$source = $data['data']['source'];

			if ( ! isset( $this->sources[ $source ] ) ) {
				return;
			}

			$ajax_manager->register_ajax_action( 'get_template_data', function( $data ) {
				return $this->get_template_data_array( $data );
			} );

		}
        
        /**
         * Get template data array
         * 
		 * triggered to get an array for a single template data
		 *
         * @since 3.6.0
         * @access public
         * 
		 */
		public function get_template_data_array( $data ) {

			if ( ! current_user_can( 'edit_posts' ) ) {
				return false;
			}

			if ( empty( $data['template_id'] ) ) {
				return false;
			}

			$source_name = isset( $data['source'] ) ? esc_attr( $data['source'] ) : '';

			if ( ! $source_name ) {
				return false;
			}

			$source = isset( $this->sources[ $source_name ] ) ? $this->sources[ $source_name ] : false;

			if ( ! $source ) {
				return false;
			}

			if ( empty( $data['tab'] ) ) {
				return false;
			}
            
			$template = $source->get_item( $data['template_id'], $data['tab'] );
            
			return $template;
		}

		/**
         * Premium get template data
         * 
		 * trigger `get_template_data_array` after template insert
		 *
         * @since 3.6.0
         * @access public
         * 
		 */
		public function premium_get_template_data() {

			$template = $this->get_template_data_array( $_REQUEST );

			if ( ! $template ) {
				wp_send_json_error();
			}
            
			wp_send_json_success( $template );

		}

		/**
		 * Returns the instance.
		 *
		 * @since  3.6.0
		 * @return object
		 */
		public static function get_instance() {

			// If the single instance hasn't been set, set it now.
			if ( null == self::$instance ) {
				self::$instance = new self;
			}
			return self::$instance;
		}
	}

}
