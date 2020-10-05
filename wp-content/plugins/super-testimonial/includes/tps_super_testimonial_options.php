<?php 
	if ( ! defined('ABSPATH')) exit;  // if direct access 	
?>


<div class="wrap about-wrap full-width-layout">
	<h1><?php _e('Welcome to Super Testimonials 2.3', 'ktsttestimonial');?></h1>
	<p id="tp_testimonials_shortcode_para">
		<p class="about-text">Thanks for installing our plugin super testimonial.   If you have any Question or need any helps, please don't hesitate to post it on <a href="https://wordpress.org/support/plugin/super-testimonial" target="_blank">WordPress.org Support Forum</a> or <a href="https://themepoints.com/questions-answer/" target="_blank">Themepoints.com Support Forum</a>.</p>
		<div class="changelog point-releases">
			<h3>Submit a Review</h3>
			<p>We spend plenty of time to develop a plugin like this and give you freely to make your life easier. If you like this plugin, please <a style="color:red;" href="https://wordpress.org/support/plugin/super-testimonial/reviews/" target="_blank">rate it 5 stars</a>. If you have any problems with the plugin, please <a href="https://themepoints.com/questions-answer/" target="_blank">let us know</a> before leaving a review.</p>	
		</div>
	</p>
	<div class="testimonials_btn_area">
		<a target="_blank" href="https://themepoints.com/product/super-testimonial-pro" class="testimonials_btn">Upgrade Pro</a>
		<a target="_blank" href="https://themepoints.com/product/super-testimonial-pro" class="testimonials_btn">Live Preview</a>
		<a target="_blank" href="https://themepoints.com/testimonials/documentation" class="testimonials_btn">Documentation</a>
		<a target="_blank" href="https://themepoints.com/questions-answer" class="testimonials_btn">Support</a><br />
	</div>
	
	
	<div class="testimonials_btn_area">
		<h3>How to display Testimonial :</h3>
		<p class="about-text">Super testimonial is very easy to display testimonial in post, page or widget section. first need to create testimonial then copy & paste the shortcode into post, page or widgets. </p>
		<span class="shortcode">[tpsscode]</span><br/><br/>
		<p id="tp_testimonials_shortcode_para"><strong>Note: </strong> Testimonial Default Shortcode without any attributes.</p>
	</div>

	<div class="testimonials_btn_area">
		<h3>All Shortcode Attributes :</h3>
		<p class="about-text">Super Testimonial All shortcode attributes available here.</p>
		<strong style="color:red">#Testimonial Themes:</strong><br/><br/>
		<div id="">[tpsscode themes="theme1"]</div><br/>

		<p id="tp_testimonials_shortcode_para">
			<strong>Note: </strong>You can use 4 Different theme in your testimonial using shortcode parameters. testimonial shortcode parameters list: (theme1,theme2,theme3,theme4).
		</p>

		<strong style="color:red">#Testimonials Category by ID:</strong><br/><br/>
		<div id="tp_testimonials_shortcode">[tpsscode themes="theme1" category="your category id"]</div><br/>

		<p id="tp_testimonials_shortcode_para">
			<strong>Note: </strong>you can show testimonials in the website using category id. Example category id:25
		</p>	

		<strong style="color:red">#Sort by Date,Random or Custom order:</strong><br/><br/>
		<div id="tp_testimonials_shortcode">[tpsscode themes="theme1" category="25" order_by="date" order="DESC"]</div><br/>

		<p id="tp_testimonials_shortcode_para">
			<strong>Note: </strong>you can Sort your testimonials by order & order_by. Example (order="DESC/ASC", order_by="date/rand/menu_order").
		</p>

		<strong style="color:red">#Testimonials Rating Icon Color:</strong><br/><br/>
		<div id="tp_testimonials_shortcode">[tpsscode themes="theme1" stars_color="#1a1a1a"]</div><br/>

		<p id="tp_testimonials_shortcode_para">
			<strong>Note: </strong>you can change your testimonials rating icon color. Example stars_color="#1a1a1a".
		</p>

		<strong style="color:red">#Testimonials Text Color:</strong><br/><br/>
		<div id="tp_testimonials_shortcode">[tpsscode themes="theme1" text_color="#8a9aad"]</div><br/>

		<p id="tp_testimonials_shortcode_para">
			<strong>Note: </strong>you can change your testimonials text color. Example text_color="#8a9aad".
		</p>	


	</div>
	
	<div class="testimonials_btn_area">
		<h3>How to display Testimonial in widget :</h3>
		<p class="about-text">First need to add a text widget in sidebar then copy & paste the shortcode. </p>
		<span class="">[tpsscode]</span><br/><br/>
		<p id="tp_testimonials_shortcode_para"><strong>Note: </strong> Testimonial Default Shortcode without any attributes.</p>
	</div>


	<div class="testimonials_btn_area">
		<h3 style="color:red">Change Log:</h3>
		<p class="changelog-text" style="color:red"> 2.3 - 13 July 2020 </p>

		<p id="tp_testimonials_shortcode_para"><strong style="color:green">Fixed: </strong> Fix Responsive Issues.</p>
		<p id="tp_testimonials_shortcode_para"><strong style="color:blue">Update: </strong> Update Options Page.</p>
		<p id="tp_testimonials_shortcode_para"><strong style="color:red">Added: </strong> Added New Options.</p>

		<p class="changelog-text" style="color:red"> 2.2 - 04 January 2020 </p>

		<p id="tp_testimonials_shortcode_para"><strong style="color:green">Fixed: </strong> Fix CSS Issues.</p>
		<p id="tp_testimonials_shortcode_para"><strong style="color:blue">Update: </strong> Update Options.</p>

		<p class="changelog-text" style="color:red"> 2.1 - 19 June 2019 </p>

		<p id="tp_testimonials_shortcode_para"><strong style="color:green">Fixed: </strong> Testimonial Random Order issues.</p>
		<p id="tp_testimonials_shortcode_para"><strong style="color:blue">Update: </strong> Testimonial Options page.</p>
	</div>
	
</div>