<?php
	if ( ! defined('ABSPATH')) exit;  // if direct access 	

	function tps_super_testimonials_init() {		
		$labels = array(
			'name' 					=> _x('Testimonials', 'Post type general name', 'ktsttestimonial'),
			'singular_name' 		=> _x('Testimonials', 'Post type singular name', 'ktsttestimonial'),
			'add_new' 				=> _x('Add Testimonial', 'Testimonial Item', 'ktsttestimonial'),
			'add_new_item' 			=> __('Add New Testimonial', 'ktsttestimonial'),
			'edit_item' 			=> __('Edit testimonial', 'ktsttestimonial'),
			'new_item' 				=> __('New testimonial', 'ktsttestimonial'),
			'all_items' 			=> __('All testimonials', 'ktsttestimonial'),
			'view_item' 			=> __('View', 'ktsttestimonial'),
			'search_items' 			=> __('Search', 'ktsttestimonial'),
			'not_found' 			=>  __('No testimonials found.', 'ktsttestimonial'),
			'not_found_in_trash' 	=> __('No testimonials found.', 'ktsttestimonial'), 
			'parent_item_colon' 	=> '',
			'menu_name' 			=> _x( 'S Testimonials', 'admin menu', 'ktsttestimonial' ),
		);
		
		$args = array(
			'labels' 				=> $labels,
			'public' 				=> false,
			'publicly_queryable' 	=> false,
			'show_ui' 				=> true, 
			'show_in_menu' 			=> true, 
			'query_var' 			=> true,
			'rewrite' 				=> true,
			'capability_type' 		=> 'post',
			'has_archive' 			=> true, 
			'hierarchical' 			=> false,
			'menu_position' 		=> null,
			'supports' 				=> array('thumbnail'),
			'menu_icon' 			=> 'dashicons-format-chat',
		);		
		register_post_type('ktsprotype',$args);
		// register taxonomy
		register_taxonomy("ktspcategory", array("ktsprotype"), array("hierarchical" => true, "label" => __('Categories', 'ktsttestimonial'), "singular_label" => __('Category', 'ktsttestimonial'), "rewrite" => false, "slug" => 'ktspcategory',"show_in_nav_menus"=>false)); 
	}
	add_action('init', 'tps_super_testimonials_init');

	
	/*----------------------------------------------------------------------
		Columns Declaration Function
	----------------------------------------------------------------------*/
	function ktps_columns($ktps_columns){
		
		$order='asc';
		if( isset( $_GET['order'] ) && $_GET['order'] =='asc' ) {
			$order='desc';
		}

		$ktps_columns = array(
			"cb" 				=> "<input type=\"checkbox\" />",
			"thumbnail" 		=> __('Image', 'ktsttestimonial'),
			"title" 			=> __('Name', 'ktsttestimonial'),
			"description" 		=> __('Testimonial Description', 'ktsttestimonial'),
			"position" 			=> __('Position', 'ktsttestimonial'),
			"ktstcategories" 	=> __('Categories', 'ktsttestimonial'),
			"date" 				=> __('Date', 'ktsttestimonial'),
		);
		return $ktps_columns;
	}
	
	/*----------------------------------------------------------------------
		testimonial Value Function
	----------------------------------------------------------------------*/
	function ktps_columns_display($ktps_columns, $post_id){
		global $post;

		$width = (int) 80;
		$height = (int) 80;
		
		if ( 'thumbnail' == $ktps_columns ) {
			if ( has_post_thumbnail($post_id)) {
				$thumbnail_id = get_post_meta( $post_id, '_thumbnail_id', true );
				$thumb = wp_get_attachment_image( $thumbnail_id, array($width, $height), true );
				echo $thumb;
			}
			else {
				echo __('None');
			}
		}

		if ( 'position' == $ktps_columns ) {
			echo get_post_meta($post_id, 'position', true);
		}
		if ( 'description' == $ktps_columns ) {
			echo get_post_meta($post_id, 'testimonial_text', true);
		}
		
		if ( 'ktstcategories' == $ktps_columns ) {
			$terms = get_the_terms( $post_id , 'ktspcategory');
			$count = count($terms);
			if ( $terms ){
				$i = 0;
				foreach ( $terms as $term ) {
					echo '<a href="'.admin_url( 'edit.php?post_type=ktsprotype&ktspcategory='.$term->slug ).'">'.$term->name.'</a>';	
					
					if($i+1 != $count) {
						echo " , ";
					}
					$i++;
				}
			}
		}
	}
	
	/*----------------------------------------------------------------------
		Add manage_tmls_posts_columns Filter 
	----------------------------------------------------------------------*/
	add_filter("manage_ktsprotype_posts_columns", "ktps_columns");
	
	/*----------------------------------------------------------------------
		Add manage_tmls_posts_custom_column Action
	----------------------------------------------------------------------*/
	add_action("manage_ktsprotype_posts_custom_column",  "ktps_columns_display", 10, 2 );	
	
	/*----------------------------------------------------------------------
		Add Meta Box 
	----------------------------------------------------------------------*/
	function tps_super_testimonials_meta_box() {
		add_meta_box(
			'custom_meta_box', // $id
			'Super Testimonials Information - <a target="_blank" style="color:red;font-size:15px;font-weight:bold" href="https://themepoints.com/product/super-testimonial-pro/">Unlock All Features</a>', // $title
			'tps_super_testimonials_inner_custom_box', // $callback
			'ktsprotype', // $page
			'normal', // $context
			'high'); // $priority
	}
	add_action('add_meta_boxes', 'tps_super_testimonials_meta_box');

	/*----------------------------------------------------------------------
		Content Of Testimonials Options Meta Box 
	----------------------------------------------------------------------*/
	
	function tps_super_testimonials_inner_custom_box( $post ) {

		// Use nonce for verification
		wp_nonce_field( plugin_basename( __FILE__ ), 'tps_testimonials_inner_custom_noncename' ); ?>
		
		<!-- Name -->
		<p><label for="title"><strong><?php _e('Name:', 'ktsttestimonial');?></strong></label></p>
		
		<input type="text" name="post_title" id="title" class="regular-text code" value="<?php echo get_post_meta($post->ID, 'name', true); ?>" />
		
		<hr class="horizontalRuler"/>
		

		<!-- Position -->
							
		<p><label for="position_input"><strong><?php _e('Position:', 'ktsttestimonial');?></strong></label></p>
		
		<input type="text" name="position_input" id="position_input" class="regular-text code" value="<?php echo get_post_meta($post->ID, 'position', true); ?>" />
		
		<hr class="horizontalRuler"/>
		
		<!-- Company Name -->
							
		<p><label for="company_input"><strong><?php _e('Company Name:', 'ktsttestimonial');?></strong></label></p>
		
		<input type="text" name="company_input" id="company_input" class="regular-text code" value="<?php echo get_post_meta($post->ID, 'company', true); ?>" />
		
		<hr class="horizontalRuler"/>
		
		<!-- Company Website -->
							
		<p><label for="company_website_input"><strong><?php _e('Company URL:', 'ktsttestimonial');?></strong></label></p>
		
		<input type="text" name="company_website_input" id="company_website_input" class="regular-text code" value="<?php echo get_post_meta($post->ID, 'company_website', true); ?>" />
							
		<p><span class="description"><?php _e('Example: (www.example.com)', 'ktsttestimonial');?></span></p>
		
		<hr class="horizontalRuler"/>
		
		<!-- Company Link Target -->
		
		<p><label for="company_link_target_list"><strong><?php _e('Link Target:', 'ktsttestimonial');?></strong></label></p>
			
		<select id="company_link_target_list" name="company_link_target_list">
			<option value="_blank" <?php if(get_post_meta($post->ID, 'company_link_target', true)=='_blank') { echo 'selected'; } ?> ><?php _e('blank', 'ktsttestimonial');?></option>
			<option value="_self" <?php if(get_post_meta($post->ID, 'company_link_target', true)=='_self') { echo 'selected'; } ?> ><?php _e('self', 'ktsttestimonial');?></option>
        </select>
		
		<hr class="horizontalRuler"/>
		<!-- Rating -->
		
		<p><label for="company_rating_target_list"><strong><?php _e('Rating:', 'ktsttestimonial');?></strong></label></p>
			
		<select id="company_rating_target_list" name="company_rating_target_list">			
			<option value="5" <?php if(get_post_meta($post->ID, 'company_rating_target', true)=='5') { echo 'selected'; } ?> ><?php _e('5 Star', 'ktsttestimonial');?></option>
			<option value="4.5" <?php if(get_post_meta($post->ID, 'company_rating_target', true)=='4.5') { echo 'selected'; } ?> ><?php _e('4.5 Star', 'ktsttestimonial');?></option>
			<option value="4" <?php if(get_post_meta($post->ID, 'company_rating_target', true)=='4') { echo 'selected'; } ?> ><?php _e('4 Star', 'ktsttestimonial');?></option>
			<option value="3.5" <?php if(get_post_meta($post->ID, 'company_rating_target', true)=='3.5') { echo 'selected'; } ?> ><?php _e('3.5 Star', 'ktsttestimonial');?></option>
			<option value="3" <?php if(get_post_meta($post->ID, 'company_rating_target', true)=='3') { echo 'selected'; } ?> ><?php _e('3 Star', 'ktsttestimonial');?></option>
			<option value="2" <?php if(get_post_meta($post->ID, 'company_rating_target', true)=='2') { echo 'selected'; } ?> ><?php _e('2 Star', 'ktsttestimonial');?></option>
			<option value="1" <?php if(get_post_meta($post->ID, 'company_rating_target', true)=='1') { echo 'selected'; } ?> ><?php _e('1 Star', 'ktsttestimonial');?></option>
        </select>
		
		<hr class="horizontalRuler"/>
		
		<!-- Testimonial Text -->
							
		<p><label for="testimonial_text_input"><strong><?php _e('Testimonial Text:', 'ktsttestimonial');?></strong></label></p>
		
		<textarea type="text" name="testimonial_text_input" id="testimonial_text_input" class="regular-text code" rows="5" cols="100" ><?php echo get_post_meta($post->ID, 'testimonial_text', true); ?></textarea>
		
		<?php
	}
	
	/*===============================================
		Save testimonial Options Meta Box Function
	=================================================*/
	
	function tps_super_testimonials_save_meta_box($post_id){
		/*----------------------------------------------------------------------
			Name
		----------------------------------------------------------------------*/
		if(isset($_POST['post_title'])) {
			update_post_meta($post_id, 'name', $_POST['post_title']);
		}
	
		/*----------------------------------------------------------------------
			Position
		----------------------------------------------------------------------*/
		if(isset($_POST['position_input'])) {
			update_post_meta($post_id, 'position', $_POST['position_input']);
		}
		
		/*----------------------------------------------------------------------
			Company
		----------------------------------------------------------------------*/
		if(isset($_POST['company_input'])) {
			update_post_meta($post_id, 'company', $_POST['company_input']);
		}
		
		/*----------------------------------------------------------------------
			company website
		----------------------------------------------------------------------*/
		if(isset($_POST['company_website_input'])) {
			update_post_meta($post_id, 'company_website', $_POST['company_website_input']);
		}
		
		/*----------------------------------------------------------------------
			company link target
		----------------------------------------------------------------------*/
		if(isset($_POST['company_link_target_list'])) {
			update_post_meta($post_id, 'company_link_target', $_POST['company_link_target_list']);
		}

		/*----------------------------------------------------------------------
			Rating
		----------------------------------------------------------------------*/
		if(isset($_POST['company_rating_target_list'])) {
			update_post_meta($post_id, 'company_rating_target', $_POST['company_rating_target_list']);
		}
		
		/*----------------------------------------------------------------------
			testimonial text
		----------------------------------------------------------------------*/
		if(isset($_POST['testimonial_text_input'])) {
			update_post_meta($post_id, 'testimonial_text', $_POST['testimonial_text_input']);
		}
	}
	
	/*----------------------------------------------------------------------
		Save testimonial Options Meta Box Action
	----------------------------------------------------------------------*/
	add_action('save_post', 'tps_super_testimonials_save_meta_box');
	
	function tps_super_testimonials_updated_messages( $messages ) {
		global $post, $post_id;
		$messages['ktsprotype'] = array( 
			1 => __('Super Testimonial updated.', 'ktsttestimonial'),
			2 => $messages['post'][2], 
			3 => $messages['post'][3], 
			4 => __('Super Testimonial updated.', 'ktsttestimonial'), 
			5 => isset($_GET['revision']) ? sprintf( __('Testimonial restored to revision from %s', 'ktsttestimonial'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
			6 => __('Super Testimonial published.', 'ktsttestimonial'),
			7 => __('Super Testimonial saved.', 'ktsttestimonial'),
			8 => __('Super Testimonial submitted.', 'ktsttestimonial'),
			9 => sprintf( __('Super Testimonial scheduled for: <strong>%1$s</strong>.', 'ktsttestimonial'), date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) )),
			10 => __('Super Testimonial draft updated.', 'ktsttestimonial'),
		);
		return $messages;
	}
	add_filter( 'post_updated_messages', 'tps_super_testimonials_updated_messages' );
	
?>