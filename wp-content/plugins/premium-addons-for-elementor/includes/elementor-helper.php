<?php

namespace PremiumAddons\Includes;

use Elementor\Frontend;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
* premium_Templat_Tags class defines all the query of options of select box
* 
* Setting up the helper assets of the premium widgets
*
* @since 1.0
*/

class premium_Template_Tags {

	/**
	* Instance of this class 
	* @since 1.0
	*/

	protected static $instance;
	

	/**
	* $options is option field of select
	*
	* @access protected 
	*
	* @since 1.0
	*/
	protected $options;

	/**
	* set instance of this class
	*/

	public static function getInstance() {
		if( !static::$instance ) {
			static::$instance = new self;
		}
		return static::$instance;
	}


	public function get_all_post() {

		$all_posts = get_posts( array(
                'posts_per_page'    => -1,
				'post_type'         => array ( 'page', 'post' ),
			)
		);
		if( !empty( $all_posts ) && !is_wp_error( $all_posts ) ) {
			foreach ( $all_posts as $post ) {
				$this->options[ $post->ID ] = strlen( $post->post_title ) > 20 ? substr( $post->post_title, 0, 20 ).'...' : $post->post_title;
			}
		}
		return $this->options;
	}
    
    /*
     * Get Elementor Template ID by title
     * 
     * @since 3.6.0
     * @access public
     * 
     */
    public function get_id_by_title( $handle ) {
        
        $template = get_page_by_title( $handle, OBJECT, 'elementor_library' );
        
        $template_id = isset ( $template->ID ) ? $template->ID : $handle;
        
        return $template_id;
    }


	public function get_elementor_page_list() {
        
		$pagelist = get_posts( array(
			'post_type' => 'elementor_library',
			'showposts' => 999,
		));
        
		if ( ! empty( $pagelist ) && ! is_wp_error( $pagelist ) ) {
            
			foreach ( $pagelist as $post ) {
				$options[ $post->post_title ] = $post->post_title;
			}
            
        update_option( 'temp_count', $options );
        
        return $options;
		}
	}
    
    /*
     * Get Elementor Template HTML Content
     * 
     * @since 3.6.0
     * @access public
     * 
     */
    public function get_template_content( $title ) {
        
        $frontend = new Frontend;
        
		$id = $this->get_id_by_title( $title );
		
		$id = apply_filters( 'wpml_object_id', $id, 'elementor_library', TRUE );
		
        $template_content = $frontend->get_builder_content( $id, true );
        
        return $template_content;
        
    }
    
}
