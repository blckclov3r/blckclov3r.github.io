<?php 
if ( ! defined( 'ABSPATH' ) ) exit;
add_action('plugins_loaded', 'wpsm_test_tr');
function wpsm_test_tr() {
	load_plugin_textdomain( wpshopmart_test_b_text_domain, FALSE, dirname( plugin_basename(__FILE__)).'/languages/' );
}

function wpsm_test_b_front_script() {
	wp_enqueue_style('wpsm_test_b_bootstrap-front', wpshopmart_test_b_directory_url.'assets/css/bootstrap-front.css');
	wp_enqueue_style('wpsm_test_b_style-1', wpshopmart_test_b_directory_url.'assets/css/style-1.css');
	wp_enqueue_style('wpsm_test_b_style-2', wpshopmart_test_b_directory_url.'assets/css/style-2.css');
}

add_action( 'wp_enqueue_scripts', 'wpsm_test_b_front_script' );
add_filter( 'widget_text', 'do_shortcode');

add_action( 'admin_notices', 'wpsm_testi_b_review' );
function wpsm_testi_b_review() {

	// Verify that we can do a check for reviews.
	$review = get_option( 'wpsm_testi_b_review' );
	$time	= time();
	$load	= false;
	if ( ! $review ) {
		$review = array(
			'time' 		=> $time,
			'dismissed' => false
		);
		add_option('wpsm_testi_b_review', $review);
		//$load = true;
	} else {
		// Check if it has been dismissed or not.
		if ( (isset( $review['dismissed'] ) && ! $review['dismissed']) && (isset( $review['time'] ) && (($review['time'] + (DAY_IN_SECONDS * 2)) <= $time)) ) {
			$load = true;
		}
	}
	// If we cannot load, return early.
	if ( ! $load ) {
		return;
	}

	// We have a candidate! Output a review message.
	?>
	<div class="notice notice-info is-dismissible wpsm-testi-b-review-notice">
		<div style="float:left;margin-right:10px;margin-bottom:5px;">
			<img style="width:100%;width: 150px;height: auto;" src="<?php echo wpshopmart_test_b_directory_url.'assets/images/icon-show.png'; ?>" />
		</div>
		<p style="font-size:17px;">'Hi! We saw you have been using <strong>Testimonial Builder plugin</strong> for a few days and wanted to ask for your help to <strong>make the plugin better</strong>.We just need a minute of your time to rate the plugin. Thank you!</p>
		<p style="font-size:17px;"><strong><?php _e( '~ wpshopmart', '' ); ?></strong></p>
		<p style="font-size:18px;"> 
			<a style="color: #fff;background: #ef4238;padding: 3px 11px 7px 11px;border-radius: 4px;text-decoration:none" href="https://wordpress.org/support/plugin/testimonial-builder/reviews/?filter=5#new-post" class="wpsm-testi-b-dismiss-review-notice wpsm-testi-b-review-out" target="_blank" rel="noopener">Rate the plugin</a>&nbsp; &nbsp;
			<a style="color: #fff;background: #27d63c;padding: 3px 11px 7px 11px;border-radius: 4px;text-decoration:none" href="#"  class="wpsm-testi-b-dismiss-review-notice wpsm-rate-later" target="_self" rel="noopener"><?php _e( 'Nope, maybe later', '' ); ?></a>&nbsp; &nbsp;
			<a style="color: #fff;background: #31a3dd;padding: 3px 11px 7px 11px;border-radius: 4px;text-decoration:none" href="#" class="wpsm-testi-b-dismiss-review-notice wpsm-rated" target="_self" rel="noopener"><?php _e( 'I already did', '' ); ?></a>
			
		</p>
	</div>
	<script type="text/javascript">
		jQuery(document).ready( function($) {
			$(document).on('click', '.wpsm-testi-b-dismiss-review-notice, .wpsm-testi-b-dismiss-notice .notice-dismiss', function( event ) {
				if ( $(this).hasClass('wpsm-testi-b-review-out') ) {
					var wpsm_rate_data_val = "1";
				}
				if ( $(this).hasClass('wpsm-rate-later') ) {
					var wpsm_rate_data_val =  "2";
					event.preventDefault();
				}
				if ( $(this).hasClass('wpsm-rated') ) {
					var wpsm_rate_data_val =  "3";
					event.preventDefault();
				}

				$.post( ajaxurl, {
					action: 'wpsm_testi_b_dismiss_review',
					wpsm_rate_data_testi_b : wpsm_rate_data_val
				});
				
				$('.wpsm-testi-b-review-notice').hide();
				//location.reload();
			});
		});
	</script>
	<?php
}

add_action( 'wp_ajax_wpsm_testi_b_dismiss_review', 'wpsm_testi_b_dismiss_review' );
function wpsm_testi_b_dismiss_review() {
	if ( ! $review ) {
		$review = array();
	}
	
	if($_POST['wpsm_rate_data_testi_b']=="1"){
		$review['time'] 	 = time();
		$review['dismissed'] = false;
		
	}
	if($_POST['wpsm_rate_data_testi_b']=="2"){
		$review['time'] 	 = time();
		$review['dismissed'] = false;
		
	}
	if($_POST['wpsm_rate_data_testi_b']=="3"){
		$review['time'] 	 = time();
		$review['dismissed'] = true;
		
	}
	update_option( 'wpsm_testi_b_review', $review );
	die;
}

function wpsm_testi_b_header_info() {
 	if(get_post_type()=="test_builder") {
		?>
		<style>
		@media screen and (max-width: 760px){
			.wpsm_ac_h_i{
				display:none;
				
			}
		}
		.wpsm_ac_h_i{
			    background-color: #4916d7;
    background: -webkit-linear-gradient(60deg, #4916d7, #be94f8);
    background: linear-gradient(60deg, #4916d7, #be94f8);
			-webkit-box-shadow: 0px 13px 21px -10px rgba(128,128,128,1);
			-moz-box-shadow: 0px 13px 21px -10px rgba(128,128,128,1);
			box-shadow: 0px 13px 21px -10px rgba(128,128,128,1);			
			margin-left: -20px;
			padding-top:20px;
			    overflow: HIDDEN;
			text-align: center;
		}
		.wpsm_ac_h_i .wpsm_ac_h_b{
			color: white;
			font-size: 30px;
			font-weight: bolder;
			padding: 0 0 0px 0;
		}
		.wpsm_ac_h_i .wpsm_ac_h_b .dashicons{
			font-size: 40px;
			position: absolute;
			margin-left: -45px;
			margin-top: -10px;
		}
		 .wpsm_ac_h_small{
			font-weight: bolder;
			color: white;
			font-size: 18px;
			padding: 0 0 15px 15px;
		}
		.wpsm_ac_h_i a{
			text-decoration: none;
		}
		@media screen and ( max-width: 600px ) {
			.wpsm_ac_h_i{ padding-top: 60px; margin-bottom: -50px; }
			.wpsm_ac_h_i .WlTSmall { display: none; }
		}
		.texture-layer {
			background: rgba(0,0,0,0);
			padding-top: 0px;
			padding: 0px 0 23px 0;
		}
		.wpsm_ac_h_i  ul{
			padding:0px 0px 0px 0px;
		}
		.wpsm_ac_h_i  li {
			text-align:left;
			color:#fff;
			font-size: 16px;
			line-height: 26px;
			font-weight: 600;
			
		}
		.wpsm_ac_h_i  li i{
			margin-right:6px ;
			margin-bottom:10px;	
			font-size: 12px;			
		}
		 
		.wpsm_ac_h_i .btn-danger{
			font-size: 29px;
			background-color: #000000;
			border-radius:1px;
			margin-right:10px;
			margin-top: 0px;
			border-color:#000;
			 
		}
		.wpsm_ac_h_i .btn-success{
			font-size: 28px;
			border-radius:1px;
			background-color: #ffffff;
			border-color: #ffffff;
			color:#000;
		}
		.btn-danger {
			color: #fff;
			background-color: #01c698 !important;
    border-color: #01c698 !important;
		}
		.pad-o{
			padding:0px;
			
		}

		</style>
			<div class="wpsm_ac_h_i ">
				<div class="texture-layer">
					<div class="col-md-3">
						<img src="<?php echo wpshopmart_test_b_directory_url.'assets/images/testimonial-intro.png'; ?>"  class="img-responsive"/>
					
					</div>
				
					
					
						<div class="col-md-9">
							<div class="wpsm_ac_h_b col-md-6" style="text-align:left">
								<a class="btn btn-danger btn-lg " href="https://wpshopmart.com/plugins/testimonial-pro/" target="_blank">Buy Pro Version</a><a class="btn btn-success btn-lg " href="http://demo.wpshopmart.com/testimonial-pro-demo/" target="_blank">View Demo</a>
							</div>								
							<div class="col-md-6" style="text-align:left">							
								<h1 style="color:#fff;font-size:34px;font-weight:800">Testimonial Pro Features</h1>							
							</div>					
						
							<div class="col-md-12" style="padding-bottom:20px;">
								<a href="https://wpshopmart.com/plugins/testimonial-pro/" target="_blank">
									<div class="col-md-3 pad-o">
										<ul>
											<li> <i class="fa fa-check"></i>100+ Slider Templates </li>
											<li> <i class="fa fa-check"></i>50+ Grid Templates</li>
											<li> <i class="fa fa-check"></i>4+ Video Testimonial Templates</li>
											<li> <i class="fa fa-check"></i>3d Testimonial Effect</li>
											<li> <i class="fa fa-check"></i>Front End Submission</li>
											<li> <i class="fa fa-check"></i>Filter Option</li>
											
										</ul>
									</div>
									<div class="col-md-3 pad-o">
										<ul>
											<li><i class="fa fa-check"></i>2+ Table Layouts</li>
											<li><i class="fa fa-check"></i>10+ Column Layout</li>
											<li> <i class="fa fa-check"></i>Add Website Link</li>
											<li> <i class="fa fa-check"></i>Add Email Link </li>								
											<li> <i class="fa fa-check"></i>Add Ratings</li>								
											<li> <i class="fa fa-check"></i>Touch Slider Effect </li>								
										</ul>
									</div>
									<div class="col-md-3 pad-o">
										<ul>
											<li> <i class="fa fa-check"></i>1000+ FontAwesome Icons</li>
											<li> <i class="fa fa-check"></i>5+ Dot navigation Style</li>
											<li> <i class="fa fa-check"></i>5+ Button Navigation Style</li>
											<li> <i class="fa fa-check"></i>GDPR Supported </li>
											<li> <i class="fa fa-check"></i>Quotes Icon Settings </li>
											
										</ul>
									</div>
									<div class="col-md-3 pad-o">
										<ul>
											<li> <i class="fa fa-check"></i>500+ Google Fonts </li>
											<li> <i class="fa fa-check"></i>Border Color Customization </li>
											<li> <i class="fa fa-check"></i>Advanced Settings Panel </li>
											<li> <i class="fa fa-check"></i>High Priority Support </li>
											<li> <i class="fa fa-check"></i>All Browser Compatible </li>
										</ul>
									</div>
								</a>
							</div>				
						</div>
				</div>
			
			</div>
		<?php  
	}
}
add_action('in_admin_header','wpsm_testi_b_header_info');
?>