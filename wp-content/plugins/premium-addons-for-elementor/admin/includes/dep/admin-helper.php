<?php

namespace PremiumAddons\Admin\Includes;

use PremiumAddons\Admin\Settings\Modules_Settings;
use PremiumAddons\Helper_Functions;

if ( ! defined( 'ABSPATH' ) ) exit;

class Admin_Helper {
    
    const DUPLICATE_ACTION = 'pa_duplicator';
    
    protected $page_slug = 'premium-addons';
    
	private static $instance = null;
    
    public static $current_screen = null;
    
    /**
    * Constructor for the class
    */
    public function __construct() {
        
        add_action( 'current_screen', array( $this, 'get_current_screen' ) );
        
        add_filter( 'plugin_action_links_' . PREMIUM_ADDONS_BASENAME, array( $this, 'insert_action_links' ) );
        
        add_filter( 'plugin_row_meta', array( $this, 'plugin_row_meta' ), 10, 2 );
        
        if( ! Modules_Settings::check_premium_duplicator() )
            return;
        
        add_action( 'admin_action_' . self::DUPLICATE_ACTION, array( $this, 'duplicate_post' ) );
        add_filter( 'post_row_actions', array( $this, 'add_duplicator_actions' ), 10, 2 );
        add_filter( 'page_row_actions', array( $this, 'add_duplicator_actions' ), 10, 2 );
        
    }
    
    /**
	 * Insert action links.
	 *
	 * Adds action links to the plugin list table
	 *
	 * Fired by `plugin_action_links` filter.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 */
   public function insert_action_links( $links ) {

       $papro_path = 'premium-addons-pro/premium-addons-pro-for-elementor.php';
       
       $is_papro_installed = Helper_Functions::is_plugin_installed( $papro_path );
       
       $settings_link = sprintf( '<a href="%1$s">%2$s</a>', admin_url( 'admin.php?page=' . $this->page_slug ), __( 'Settings', 'premium-addons-for-elementor' ) );
       
       $rollback_link = sprintf( '<a href="%1$s">%2$s</a>', wp_nonce_url( admin_url( 'admin-post.php?action=premium_addons_rollback' ), 'premium_addons_rollback' ), __( 'Rollback to Version ' . PREMIUM_ADDONS_STABLE_VERSION, 'premium-addons-for-elementor' ) );
       
       $new_links = array( $settings_link, $rollback_link );
       
       if( ! $is_papro_installed ) {
           
           $theme = Helper_Functions::get_installed_theme();
                    
           $link = sprintf( 'https://premiumaddons.com/pro/?utm_source=plugins-page&utm_medium=wp-dash&utm_campaign=get-pro&utm_term=%s', $theme );
           
           $pro_link = sprintf( '<a href="%s" target="_blank" style="color: #39b54a; font-weight: bold;">%s</a>', $link, __( 'Go Pro', 'premium-addons-for-elementor' ) );
           array_push ( $new_links, $pro_link );
       }
       
       $new_links = array_merge( $links, $new_links );

       return $new_links;
   }
   
   /**
	 * Plugin row meta.
	 *
	 * Extends plugin row meta links
	 *
	 * Fired by `plugin_row_meta` filter.
	 *
	 * @since 3.8.4
	 * @access public
	 *
     *  @return array An array of plugin row meta links.
	 */
    public function plugin_row_meta( $meta, $file ) {
        
        if( Helper_Functions::is_hide_row_meta() )
            return $meta;
        
        if ( PREMIUM_ADDONS_BASENAME == $file ) {
            
            $theme = Helper_Functions::get_installed_theme();
                    
            $link = sprintf( 'https://premiumaddons.com/support/?utm_source=plugins-page&utm_medium=wp-dash&utm_campaign=get-support&utm_term=%s', $theme );
            
            $row_meta = [
				'docs' => '<a href="' . esc_attr( $link ) . '" aria-label="' . esc_attr( __( 'View Premium Addons for Elementor Documentation', 'premium-addons-for-elementor' ) ) . '" target="_blank">' . __( 'Docs & FAQs', 'premium-addons-for-elementor' ) . '</a>',
				'videos' => '<a href="https://www.youtube.com/watch?v=D3INxWw_jKI&list=PLLpZVOYpMtTArB4hrlpSnDJB36D2sdoTv" aria-label="' . esc_attr( __( 'View Premium Addons Video Tutorials', 'premium-addons-for-elementor' ) ) . '" target="_blank">' . __( 'Video Tutorials', 'premium-addons-for-elementor' ) . '</a>',
			];

			$meta = array_merge( $meta, $row_meta );
        }

        return $meta;
       
    }
    
    /**
     * Add Duplicator Actions
     * 
     * Add duplicator action links to posts/pages
     *
     * @access public
     * @since 3.9.7
     * 
     * @param array $actions
     * @param \WP_Post $post
     * @return array
     */
    public function add_duplicator_actions( $actions, $post ) {
        
        if ( current_user_can( 'edit_posts' ) && post_type_supports( $post->post_type, 'elementor' ) ) {
            
            $actions[ self::DUPLICATE_ACTION ] = sprintf(
                '<a href="%1$s" title="%2$s"><span class="screen-reader-text">%2$s</span>%3$s</a>',
                esc_url( self::get_duplicate_url( $post->ID ) ),
                sprintf( esc_attr__( 'Duplicate - %s', 'premium-addons-for-elementor' ), esc_attr( $post->post_title ) ),
                __( 'PA Duplicate', 'premium-addons-for-elementor' )
            );
            
        }

        return $actions;
    }
    
    /**
     * Get duplicate url
     * 
     * @access public
     * @since 3.9.7
     *
     * @param $post_id
     * @return string
     */
    public static function get_duplicate_url( $post_id ) {
        return wp_nonce_url(
            add_query_arg(
                [
                    'action' => self::DUPLICATE_ACTION,
                    'post_id' => $post_id
                ],
                admin_url( 'admin.php' )
            ),
            self::DUPLICATE_ACTION
        );
    }
    
    /**
     * Duplicate required post/page
     * 
     * @access public
     * @since 3.9.7
     *
     * @return void
     */
    public function duplicate_post() {
        
        if ( ! current_user_can( 'edit_posts' ) )
            return;
        
        $nonce = isset( $_GET['_wpnonce'] ) ? $_GET['_wpnonce'] : '';
        $post_id = isset( $_GET['post_id'] ) ? absint( $_GET['post_id'] ) : 0;
        

        if ( ! wp_verify_nonce( $nonce, self::DUPLICATE_ACTION ) )
            return;
        
        if ( is_null( $post = get_post( $post_id ) ) )
            return;
        
        $post = sanitize_post( $post, 'db' );
        
        $duplicated_post_id = self::insert_post( $post );
        
        $redirect = add_query_arg( array (
            'post_type' => $post->post_type
            ),
            admin_url( 'edit.php' ) 
        );

        if ( ! is_wp_error( $duplicated_post_id ) ) {
            
            self::duplicate_post_taxonomies( $post, $duplicated_post_id );
            self::duplicate_post_meta_data( $post, $duplicated_post_id );

        }

        wp_safe_redirect( $redirect );
        die();
    }
    
    /**
     * Duplicate required post/page
     * 
     * @access public
     * @since 3.9.7
     *
     * @return void
     */
    protected static function insert_post( $post ) {
        $current_user = wp_get_current_user();
        
        $post_meta = get_post_meta( $post->ID );
        
        $duplicated_post_args = [
            'post_status'    => 'draft',
            'post_type'      => $post->post_type,
            'post_parent'    => $post->post_parent,
            'post_content'   => $post->post_content,
            'menu_order'     => $post->menu_order,
            'ping_status'    => $post->ping_status,
            'post_excerpt'   => $post->post_excerpt,
            'post_password'  => $post->post_password,
            'comment_status' => $post->comment_status,
            'to_ping'        => $post->to_ping,
            'post_author'    => $current_user->ID,
            'post_title'     => sprintf( __( 'Duplicated: %s - [#%d]', 'premium-addons-for-elementor' ), $post->post_title,
                $post->ID )
        ];
        
        if( isset( $post_meta['_elementor_edit_mode'][0] ) ) {
            
            $data = [
                'meta_input'  => array(
                    '_elementor_edit_mode'     => $post_meta['_elementor_edit_mode'][0],
                    '_elementor_template_type' => $post_meta['_elementor_template_type'][0],
                )
            ];
            
            $duplicated_post_args = array_merge( $duplicated_post_args, $data );
            
        }

        return wp_insert_post( $duplicated_post_args );
    }
    
    /**
     * Add post taxonomies to the cloned version
     *
     * @access public
     * @since 3.9.7
     * 
     * @param $post
     * @param $id
     */
    public static function duplicate_post_taxonomies( $post, $id ) {
        
        $taxonomies = get_object_taxonomies( $post->post_type );
        
        if ( ! empty( $taxonomies ) && is_array( $taxonomies ) ) {
            foreach ( $taxonomies as $taxonomy ) {
                $terms = wp_get_object_terms( $post->ID, $taxonomy, [ 'fields' => 'slugs' ] );
                wp_set_object_terms( $id, $terms, $taxonomy, false );
            }
        }
    }
    
    /**
     * Add post meta data to the cloned version
     * 
     * @access public
     * @since 3.9.7
     *
     * @param $post
     * @param $id
     */
    public static function duplicate_post_meta_data( $post, $id ) {
        
        global $wpdb;

        $meta = $wpdb->get_results(
            $wpdb->prepare( "SELECT meta_key, meta_value FROM {$wpdb->postmeta} WHERE post_id = %d", $post->ID )
        );

        if ( ! empty( $meta ) && is_array( $meta ) ) {
            
            $query = "INSERT INTO {$wpdb->postmeta} ( post_id, meta_key, meta_value ) VALUES ";
            
            $_records = [];
            
            foreach ( $meta as $meta_info ) {
                $_value = wp_slash( $meta_info->meta_value );
                $_records[] = "( $id, '{$meta_info->meta_key}', '{$_value}' )";
            }
            
            $query .= implode( ', ', $_records ) . ';';
            $wpdb->query( $query  );
        }
        
    }

    /**
     * Gets current screen slug
     * 
     * @since 3.3.8
     * @access public
     * 
     * @return string current screen slug
     */
    public static function get_current_screen() {
        
        self::$current_screen = get_current_screen()->id;
        
        return isset( self::$current_screen ) ? self::$current_screen : false;
        
    }
    
    public static function get_instance() {
        if( self::$instance == null ) {
            self::$instance = new self;
        }
        return self::$instance;
    }
       
}

if( ! function_exists('get_admin_helper_instance') ) {
    /**
	 * Returns an instance of the plugin class.
     * 
	 * @since  3.3.8
     * 
	 * @return object
	 */
    function get_admin_helper_instance() {
        return Admin_Helper::get_instance();
    }
}

get_admin_helper_instance();