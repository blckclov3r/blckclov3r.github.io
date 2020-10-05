<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<div class="wpsm_row">  
<style>

#wpsm_testi_b_row_<?php echo $post_id; ?> .wpsm_testimonial .wpsm_testimonial-title{
	font-size:<?php echo $test_mb_name_font_size; ?>px !important;
	color:<?php echo $test_mb_name_clr; ?> !important;
	font-family:'<?php  echo $test_font_family; ?>';
}
#wpsm_testi_b_row_<?php echo $post_id; ?> .wpsm_testimonial > .wpsm_testimonial-review span{
	font-size:<?php echo $test_mb_deg_font_size; ?>px !important;
	color:<?php echo $test_mb_deg_clr; ?> !important;
	font-family:'<?php  echo $test_font_family; ?>';
}
#wpsm_testi_b_row_<?php echo $post_id; ?> .wpsm_testimonial .wpsm_testi_links{
	font-size:<?php echo $test_mb_web_link_font_size; ?>px !important;
	color:<?php echo $test_mb_web_link_clr; ?> !important;
	font-family:'<?php  echo $test_font_family; ?>';
}
#wpsm_testi_b_row_<?php echo $post_id; ?> .wpsm_testimonial .wpsm_testi_description{
	font-size:<?php echo $test_mb_content_font_size; ?>px !important;
	color:<?php echo $test_mb_content_clr; ?> !important;
	font-family:'<?php  echo $test_font_family; ?>';
	padding: 10px !important;
    margin: 0px !important;
	font-weight: 400;
}
#wpsm_testi_b_row_<?php echo $post_id; ?> .wpsm_testimonial .wpsm_testi_content{
	background:<?php echo $bgclr_style2; ?> !important;
}
#wpsm_testi_b_row_<?php echo $post_id; ?> .wpsm_testimonial .wpsm_testi_content:after{
	  border-top: 10px solid <?php echo $bgclr_style2; ?> !important;
}
#wpsm_testi_b_row_<?php echo $post_id; ?> .wpsm_testimonial-pic > img{
	<?php  if($test_image_layout=="2") { ?>
		border-radius: 50% !important;
	<?php } else { ?>
		border-radius: 0% !important;
	<?php } ?>
}
<?php echo $custom_css; ?>
</style>
<?php

 $i=1;
switch($test_layout){
	case(12):
		$row=1;
	break;
	case(6):
		$row=2;
	break;
	case(4):
		$row=3;
	break;
}

foreach($test_data as $single_data)
{
	$mb_photo = $single_data['mb_photo'];
	$mb_name = $single_data['mb_name'];
	$mb_deg = $single_data['mb_deg'];
	$mb_website = $single_data['mb_website'];
	$mb_desc = $single_data['mb_desc'];
	$mb_id = $single_data['mb_id'];
	$crop_size = wp_get_attachment_image_src($mb_id,'wpsm_testi_small');

	if($mb_id==0){
		$img_url = $mb_photo;
	}
	else{
		$img_url = $crop_size[0];
	}
?>
	<div class="wpsm_testimonial wpsm_col-md-<?php echo $test_layout; ?>">
		<?php if($mb_desc!=""){ ?>
		<div class="wpsm_testi_content">
			<p class="wpsm_testi_description">
				<?php echo $mb_desc; ?>
			 </p>
		</div>
		<?php } ?>
		<div class="wpsm_testimonial-pic">
			<img src="<?php echo $img_url; ?>" alt="<?php echo $mb_name; ?>">
		</div>
		<div class="wpsm_testimonial-review">
			<?php if($mb_name!=""){ ?>
				<h3 class="wpsm_testimonial-title"><?php echo $mb_name; ?></h3>
			<?php } ?>
			<?php if($mb_deg!=""){ ?>
				<span><?php echo $mb_deg; ?></span>
			<?php } ?>
			<?php if($mb_website!=""){ ?>
				<a class="wpsm_testi_links" href="<?php echo $mb_website; ?>" target="_blank"><?php echo $test_mb_web_link_label; ?></a>
			<?php } ?>
		</div>
	</div>
	<?php
	if($i%$row==0){
		?>
		</div>
		<div class="wpsm_row">
		<?php 
	}	
	 
	 $i++;
	
 } ?>
 
 </div>
            