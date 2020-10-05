<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<div style=" overflow: hidden;padding: 10px;">
	<h3><?php _e('Add Testimonials Here',wpshopmart_test_b_text_domain); ?></h3>
	<input type="hidden" name="test_b_save_data_action" value="test_b_save_data_action" />
	<ul class="clearfix" id="wpsm_test_panel">
		<?php
		$i=1;
		$All_data = unserialize(get_post_meta( $post->ID, 'wpsm_test_b_data', true));
		$TotalCount =  get_post_meta( $post->ID, 'wpsm_test_b_count', true );
		if($TotalCount) 
		{
			if($TotalCount!=-1)
			{
				foreach($All_data as $single_data)
				{
					$mb_photo = $single_data['mb_photo'];
					$mb_name = $single_data['mb_name'];
					$mb_deg = $single_data['mb_deg'];
					$mb_website = $single_data['mb_website'];
					$mb_desc = $single_data['mb_desc'];
					$mb_id = $single_data['mb_id'];
										
					?>
	
					<li class="wpsm_ac-panel single_color_box" >
						<div class="col-md-12">
							<div class="col-md-2">
								<img style="margin-bottom:15px" class="test-img-responsive" src="<?php echo $mb_photo; ?>" />
								<input style="margin-bottom:15px" type="button" id="upload-background" name="upload-background" value="Upload Image" class="button-primary rcsp_media_upload btn-block"  onclick="wpsm_media_upload(this)"/>
								<input style="display:block;width:100%" type="hidden"  name="mb_photo[]" class="wpsm_ac_label_text"  value="<?php echo $mb_photo; ?>"  readonly="readonly" placeholder="No Media Selected" />
								<input style="display:block;width:100%" type="hidden"  name="mb_id[]" class="wpsm_ac_label_text"  value="<?php echo $mb_id; ?>"  readonly="readonly" placeholder="No Media Selected" />
							</div>
							<div class="col-md-10">
								<span class="ac_label"><?php _e('Testimonial User Name',wpshopmart_test_b_text_domain); ?></span>
								<input type="text"  name="mb_name[]" value="<?php echo $mb_name; ?>" placeholder="Enter Testimonial Name Here" class="wpsm_ac_label_text">
								
								<span class="ac_label"><?php _e('Testimonial Designation',wpshopmart_test_b_text_domain); ?></span>
								<input type="text"  name="mb_deg[]" value="<?php echo $mb_deg; ?>" placeholder="Enter Testimonial Designation Here" class="wpsm_ac_label_text">
								
								<span class="ac_label"><?php _e('Website URL',wpshopmart_test_b_text_domain); ?></span>
								<input type="text"  name="mb_website[]" value="<?php echo $mb_website; ?>" placeholder="Enter web site url with http:// ex : http://wpshopmart.com/" class="wpsm_ac_label_text">
								
								<span class="ac_label"><?php _e('Testimonial Content',wpshopmart_test_b_text_domain); ?></span>
								<textarea  name="mb_desc[]"  placeholder="Enter Testimonial Content Here" class="wpsm_ac_label_text"><?php echo esc_html($mb_desc); ?></textarea>
							<a class="remove_button" href="#delete" id="remove_bt" ><i class="fa fa-trash-o"></i></a>
							
							</div>
						</div>
						
					</li>
					<?php 
					$i++;
				} // end of foreach
			}
			else{
				echo "<h2>No Tabs Found</h2>";
			}
		}
		else{
	
			for($i=1; $i<=2; $i++)
			{
				  ?>
				<li class="wpsm_ac-panel single_color_box" >
					<div class="col-md-12">
						<div class="col-md-2">
							<img style="margin-bottom:15px" class="test-img-responsive" src="<?php echo wpshopmart_test_b_directory_url.'assets/images/test.png'; ?>" />
							<input style="margin-bottom:15px" type="button" id="upload-background" name="upload-background" value="Upload Image" class="button-primary rcsp_media_upload btn-block"  onclick="wpsm_media_upload(this)"/>
							<input style="display:block;width:100%" type="hidden"  name="mb_photo[]" class="wpsm_ac_label_text"  value="<?php echo wpshopmart_test_b_directory_url.'assets/images/test.png'; ?>"  readonly="readonly" placeholder="No Media Selected" />
							<input style="display:block;width:100%" type="hidden"  name="mb_id[]" class="wpsm_ac_label_text"  value="0"  readonly="readonly" placeholder="No Media Selected" />
						
						</div>
						<div class="col-md-10">
							<span class="ac_label"><?php _e('Testimonial User Name',wpshopmart_test_b_text_domain); ?></span>
							<input type="text"  name="mb_name[]" value="Testimonial Name" placeholder="Enter Testimonial Name Here" class="wpsm_ac_label_text">
							
							<span class="ac_label"><?php _e('Testimonial Designation',wpshopmart_test_b_text_domain); ?></span>
							<input type="text"  name="mb_deg[]" value="Designation" placeholder="Enter Testimonial Designation Here" class="wpsm_ac_label_text">

							<span class="ac_label"><?php _e('Website URL',wpshopmart_test_b_text_domain); ?></span>
							<input type="text"  name="mb_website[]" value="http://wpshopmart.com/" placeholder="Enter web site url with http:// ex : http://wpshopmart.com/" class="wpsm_ac_label_text">
							
							<span class="ac_label"><?php _e('Testimonial Content',wpshopmart_test_b_text_domain); ?></span>
							<textarea  name="mb_desc[]"  placeholder="Enter Testimonial Content Here" class="wpsm_ac_label_text">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut vel fermentum dui. Pellentesque vitae porttitor ex, euismod sodales magna. Nunc sed felis sed dui pellentesque sodales porta a magna. Donec dui augue</textarea>
							
							<a class="remove_button" href="#delete" id="remove_bt" ><i class="fa fa-trash-o"></i></a>	
							
						</div>
					</div>
			
				</li>
				<?php
			}
		}
		?>
	</ul>
	<div style="display:block;margin-top:20px;overflow:hidden;width: 100%;float:left;">
		<div class="col-md-10">
			<a class="btn " class="wpsm_ac-panel add_wpsm_ac_new" id="add_new_ac" onclick="add_new_content()"  style="overflow:hidden;font-size: 52px;font-weight: 600; padding:10px 30px 10px 30px; background:#1e73be;width:100%;color:#fff;text-align:center"  >
				<?php _e('Add New Testimonial', wpshopmart_test_b_text_domain); ?>
			</a>
		</div>
		<div class="col-md-2">
		<a  style="float: left;width:100%;padding:10px !important;background:#31a3dd;" class=" add_wpsm_ac_new delete_all_acc" id="delete_all_colorbox"    >
			<i style="font-size:57px;"class="fa fa-trash-o"></i>
			<span style="display:block"><?php _e('Delete All',wpshopmart_test_b_text_domain); ?></span>
		</a>
		</div>
	</div>
	
</div>
<?php require('add-test-js-footer.php'); ?>