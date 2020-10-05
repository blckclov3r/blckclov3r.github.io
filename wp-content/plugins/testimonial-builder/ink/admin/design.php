<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
  <div class="test_b_admin_wrapper">
	<h3><?php _e('Select Your Testimonial Design Here',wpshopmart_test_b_text_domain); ?></h3>
	<style>
	.checked_temp {
		position: absolute;
		background: #FF2300;
		color: #fff;
		top: -14px;
		right: 5px;
		border-radius: 50%;
		width: 35px;
		height: 35px;
		text-align: center;
		line-height: 29px;
		z-index: 999;
		font-size: 18px;
		border: 3px solid #fff;
	}
	.wpsm_home_portfolio_showcase{
		position:relative;
	}
	.wpsm_img_responsive{
		width:100%;
		height:auto;
	}
	.img-demo-span{
		position: absolute;
    bottom: 0px;
    width: 30%;
    overflow: hidden;
    text-align: center;
    background: rgba(0,0,0,0.8);
    padding-top: 5px;
    padding-bottom: 5px;
    color: #fff;
    border-top-left-radius: 15px;
    font-size: 13px;
    right: 0;
	}
	
	#test_b_design .hndle  , #test_b_design .handlediv {
	display:none;
	
}
#test_b_design{
	
	background:transparent;
	padding:0px;
	box-shadow:none;
	border:0px;
	border-bottom: 2px dashed rgba(0,0,0,0.2);
	margin-bottom:30px;
}

	</style>
	<?php
		$PostId = get_the_ID();
		$De_Settings = unserialize(get_option('test_B_default_Settings'));
		$Test_Settings = unserialize(get_post_meta( $PostId, 'Test_Settings', true));
		if($Test_Settings['test_mb_name_clr']  )   {  
		$templates     	= $Test_Settings['templates'];
		}
		else{
		$templates=$De_Settings['templates'];
		}			
	?>
	<?php for($i=1;$i<=2;$i++){ ?>
		<div class="col-md-4">
			<div class="demoftr">	
				<span class="checked_temp" id="checked_temp_<?php echo $i; ?>" <?php if($templates!=$i) { ?>  style="display:none" <?php } ?> ><i class="fa fa-check"></i></span>
				<div class="">
					<div class="wpsm_home_portfolio_showcase">
						<img class="wpsm_img_responsive ftr_img" src="<?php echo wpshopmart_test_b_directory_url.'assets/images/design/test-'.$i.'.png'?>">
						<span class="img-demo-span"><a style="color:#fff;text-decoration:none" target="_new" href="http://demo.wpshopmart.com/testimonial-builder/demo-<?php echo $i; ?>/">Demo</a></span>
					</div>
				</div>
				<div style="padding:13px;overflow:hidden; background: #EFEFEF; border-top: 1px dashed #ccc;">
					<h3 class="text-center pull-left" style="margin-top: 10px;margin-bottom: 10px;font-weight:900">Design <?php echo $i; ?></h3>
					<button type="button" <?php if($templates==$i) { ?> disabled="disabled" style="background:#F50000;border-color:#F50000;" <?php } ?> class="pull-right btn btn-primary design_btn" id="templates_btn<?php echo $i; ?>" onclick="select_template('<?php echo $i; ?>')"><?php if($templates==$i){  echo "Selected"; } else { echo "Select"; } ?></button>
					<input type="radio" name="templates" id="design<?php echo $i; ?>" value="<?php echo $i; ?>" <?php if($templates==$i){  echo "checked"; } ?> style="display:none">
				</div>		
			</div>	
		</div>
	<?php } ?>
		<div class="col-md-4">
		
			<div class="demoftr">	
				
				<div class="">
					<div class="wpsm_home_portfolio_showcase">
						<a   href="http://demo.wpshopmart.com/testimonial-pro-demo" target="_blank" >
						<img class="wpsm_img_responsive ftr_img" src="<?php echo wpshopmart_test_b_directory_url.'assets/images/design/test-3.png'?>"></a>
						<span class="img-demo-span"><a style="color:#fff;text-decoration:none" target="_new" href="http://demo.wpshopmart.com/testimonial-pro-demo">Demo</a></span>
					</div>
				</div>
				<div style="padding:13px;overflow:hidden; background: #EFEFEF; border-top: 1px dashed #ccc;">
					<h3 class="text-center pull-left" style="margin-top: 10px;margin-bottom: 10px;font-weight:900">Pro version</h3>
					<a  class="pull-right btn btn-primary design_btn" href="https://wpshopmart.com/plugins/testimonial-pro/" target="_blank" style="    background-color: #e0bf1b;    border-color: #e0bf1b;">Upgarde To pro Now</a>
				</div>		
			</div>
			
		</div>
</div>

<script>

function select_template(id)
{
	
	jQuery(".design_btn").attr('style','');
	jQuery(".design_btn").prop("disabled", false);
	jQuery(".design_btn").text("Select");
	
	jQuery(".checked_temp").hide();
	jQuery("#checked_temp_"+id).show();
	if(id==8){
		jQuery(".top_border_color_group").show();
		
	}
	else{
		jQuery(".top_border_color_group").hide();
		
	}
	
	
	jQuery("#templates_btn"+id).attr('disabled','disabled');
	jQuery("#templates_btn"+id).attr('style','background:#F50000;border-color:#F50000;');
	jQuery("#templates_btn"+id).text("Selected");
	 jQuery("#design"+id).prop( "checked", true );
	
}

</script>