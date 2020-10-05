<?php 
if ( ! defined( 'ABSPATH' ) ) exit;
	$post_type = "test_builder";	
    $AllTest = array(  'p' => $WPSM_TEST_ID, 'post_type' => $post_type, 'orderby' => 'ASC');
    $loop = new WP_Query( $AllTest );
	
	while ( $loop->have_posts()) : $loop->the_post();
		//get the post id
		$post_id = get_the_ID();
		$Test_Settings = unserialize(get_post_meta( $post_id, 'Test_Settings', true));
		if(count($Test_Settings)) 
		{
			$option_names = array(
				"test_mb_name_clr" 	 => "#000000",
				"test_mb_deg_clr" => "#000000",
				"test_mb_web_link_clr" => "#000000",
				"test_mb_content_clr" => "#000000",
				"test_mb_name_font_size"   => 15,
				"test_mb_deg_font_size"   => 15,
				"test_mb_web_link_font_size"   => 13,
				"test_mb_content_font_size"   => 14,
				"test_font_family"   => "Open Sans",
				"test_layout"   => 3,
				"custom_css"   => "",
				"templates"   => 1,
				"bgclr_style2" => "#E37BA7",
				"test_image_layout" => "1",
				"test_mb_web_link_label" => "website",
				);
			
			foreach($option_names as $option_name => $default_value) {
				if(isset($Test_Settings[$option_name])) 
					${"" . $option_name}  = $Test_Settings[$option_name];
				else
					${"" . $option_name}  = $default_value;
			}
		}				
		
		$test_data = unserialize(get_post_meta( $post_id, 'wpsm_test_b_data', true));
		$TotalCount =  get_post_meta( $post_id, 'wpsm_test_b_count', true );		
	
		?>
		<div class="wpsm_testi_b_row" id="wpsm_testi_b_row_<?php echo $post_id; ?>">
			<?php 
			if($TotalCount>0) 
			{
				
				require('design/index'.$templates.'.php');
				
			}
			else{
				echo "<h3> No Testimonial Content Found </h3>";
			}
			
			?>
		</div>
		<?php 
	endwhile; 
?>