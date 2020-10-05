<?php
if ( ! defined( 'ABSPATH' ) ) exit;
$labels = array(
				'name'                => _x( 'Testimonial Builder', 'Testimonial Builder', wpshopmart_test_b_text_domain ),
				'singular_name'       => _x( 'Testimonial Builder', 'Testimonial Builder', wpshopmart_test_b_text_domain ),
				'menu_name'           => __( 'Testimonial Builder', wpshopmart_test_b_text_domain ),
				'parent_item_colon'   => __( 'Parent Item:', wpshopmart_test_b_text_domain ),
				'all_items'           => __( 'All Testimonial', wpshopmart_test_b_text_domain ),
				'view_item'           => __( 'View Testimonial', wpshopmart_test_b_text_domain ),
				'add_new_item'        => __( 'Add New Testimonial', wpshopmart_test_b_text_domain ),
				'add_new'             => __( 'Add New Testimonial', wpshopmart_test_b_text_domain ),
				'edit_item'           => __( 'Edit Testimonial', wpshopmart_test_b_text_domain ),
				'update_item'         => __( 'Update Testimonial', wpshopmart_test_b_text_domain ),
				'search_items'        => __( 'Search Testimonial', wpshopmart_test_b_text_domain ),
				'not_found'           => __( 'No Testimonial Found', wpshopmart_test_b_text_domain ),
				'not_found_in_trash'  => __( 'No Testimonial found in Trash', wpshopmart_test_b_text_domain ),
			);
			$args = array(
				'label'               => __( 'Testimonial Builder', wpshopmart_test_b_text_domain ),
				'description'         => __( 'Testimonial Builder', wpshopmart_test_b_text_domain ),
				'labels'              => $labels,
				'supports'            => array( 'title', '', '', '', '', '', '', '', '', '', '', ),
				//'taxonomies'          => array( 'category', 'post_tag' ),
				 'hierarchical'        => false,
				'public'              => false,
				'show_ui'             => true,
				'show_in_menu'        => true,
				'show_in_nav_menus'   => false,
				'show_in_admin_bar'   => false,
				'menu_position'       => 5,
				'menu_icon'           => 'dashicons-format-quote',
				'can_export'          => true,
				'has_archive'         => true,
				'exclude_from_search' => false,
				'publicly_queryable'  => false,
				'capability_type'     => 'page',
			);
			register_post_type( 'test_builder', $args );
			
 ?>