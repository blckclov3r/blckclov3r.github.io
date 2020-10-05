<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<script>
var j = 1000;
	function add_new_content(){
	var output = 	'<li class="wpsm_ac-panel single_color_box" >'+
			'<div class="col-md-12">'+
				'<div class="col-md-2">'+
					'<img style="margin-bottom:15px" class="test-img-responsive" src="<?php echo wpshopmart_test_b_directory_url.'assets/images/test.png'; ?>" />'+
					'<input style="margin-bottom:15px" type="button" id="upload-background" name="upload-background" value="Upload Image" class="button-primary rcsp_media_upload btn-block" onclick="wpsm_media_upload(this)" />'+
					'<input style="display:block;width:100%" type="hidden"  name="mb_photo[]" class="wpsm_ac_label_text"  value="<?php echo wpshopmart_test_b_directory_url.'assets/images/test.png'; ?>"  readonly="readonly" placeholder="No Media Selected" />'+
					'<input style="display:block;width:100%" type="hidden"  name="mb_id[]" class="wpsm_ac_label_text"  value="0"  readonly="readonly" placeholder="No Media Selected" />'+
				'</div>'+
				'<div class="col-md-10">'+
					'<span class="ac_label"><?php _e('Testimonial User Name',wpshopmart_test_b_text_domain); ?></span>'+
					'<input type="text"  name="mb_name[]" value="" placeholder="Enter Testimonial Name Here" class="wpsm_ac_label_text">'+
					'<span class="ac_label"><?php _e('Testimonial Designation',wpshopmart_test_b_text_domain); ?></span>'+
					'<input type="text"  name="mb_deg[]" value="" placeholder="Enter Testimonial Designation Here" class="wpsm_ac_label_text">'+
					'<span class="ac_label"><?php _e('Website URL',wpshopmart_test_b_text_domain); ?></span>'+
					'<input type="text"  name="mb_website[]" value="" placeholder="Enter web site url with http:// ex : http://wpshopmart.com/" class="wpsm_ac_label_text">'+
					'<span class="ac_label"><?php _e('Testimonial Content',wpshopmart_test_b_text_domain); ?></span>'+
					'<textarea  name="mb_desc[]"  placeholder="Enter Testimonial Content Here" class="wpsm_ac_label_text"></textarea>'+
				'</div>'+
			'</div>'+
			
		'</li>';
	jQuery(output).hide().appendTo("#wpsm_test_panel").slideDown("slow");
	j++;
	
	}
	jQuery(document).ready(function(){

	  jQuery('#wpsm_test_panel').sortable({
	  
	   revert: true,
	     
	  });
	});
	
	
</script>
<script>
	jQuery(function(jQuery)
		{
			var colorbox = 
			{
				wpsm_test_panel: '',
				init: function() 
				{
					this.wpsm_test_panel = jQuery('#wpsm_test_panel');

					this.wpsm_test_panel.on('click', '.remove_button', function() {
					if (confirm('Are you sure you want to delete this?')) {
						jQuery(this).closest('li').slideUp(600, function() {
							jQuery(this).remove();
						});
					}
					return false;
					});
					 jQuery('#delete_all_colorbox').on('click', function() {
						if (confirm('Are you sure you want to delete all the Colorbox?')) {
							jQuery(".single_color_box").slideUp(600, function() {
								jQuery(".single_color_box").remove();
							});
							jQuery('html, body').animate({ scrollTop: 0 }, 'fast');
							
						}
						return false;
					});
					
			   }
			};
		colorbox.init();
	});
</script>


