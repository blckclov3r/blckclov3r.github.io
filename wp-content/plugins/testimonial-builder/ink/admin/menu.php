<?php
if ( ! defined( 'ABSPATH' ) ) exit;
class wpsm_test_b {
	private static $instance;
    public static function forge() {
        if (!isset(self::$instance)) {
            $className = __CLASS__;
            self::$instance = new $className;
        }
        return self::$instance;
    }
	
	private function __construct() {
		add_action('admin_enqueue_scripts', array(&$this, 'wpsm_test_b_admin_scripts'));
        if (is_admin()) {
			add_action('init', array(&$this, 'test_b_register_cpt'), 1);
			add_action('add_meta_boxes', array(&$this, 'wpsm_test_b_meta_boxes_group'));
			add_action('admin_init', array(&$this, 'wpsm_test_b_meta_boxes_group'), 1);
			add_action('save_post', array(&$this, 'add_test_b_save_meta_box_save'), 9, 1);
			add_action('save_post', array(&$this, 'test_b_settings_meta_box_save'), 9, 1);
		}
    }
	// admin scripts
	public function wpsm_test_b_admin_scripts(){
		if(get_post_type()=="test_builder"){
			
			wp_enqueue_script('theme-preview');
			wp_enqueue_media();
			wp_enqueue_script('jquery-ui-datepicker');
			//color-picker css n js
			wp_enqueue_style( 'wp-color-picker' );
			wp_enqueue_style('thickbox');
			wp_enqueue_script( 'wpsm_test_b-color-pic', wpshopmart_test_b_directory_url.'assets/js/color-picker.js', array( 'wp-color-picker' ), false, true );
			wp_enqueue_style('wpsm_test_b-panel-style', wpshopmart_test_b_directory_url.'assets/css/panel-style.css');
			 wp_enqueue_script('wpsm_test_b-media-uploads',wpshopmart_test_b_directory_url.'assets/js/media-upload-script.js',array('media-upload','thickbox','jquery')); 
			//font awesome css
			wp_enqueue_style('wpsm_test_b-font-awesome', wpshopmart_test_b_directory_url.'assets/css/font-awesome/css/font-awesome.min.css');
			wp_enqueue_style('wpsm_test_b_bootstrap', wpshopmart_test_b_directory_url.'assets/css/bootstrap.css');
			wp_enqueue_style('wpsm_test_b_jquery-css', wpshopmart_test_b_directory_url .'assets/css/ac_jquery-ui.css');
			
			//css line editor
			wp_enqueue_style('wpsm_test_b_line-edtor', wpshopmart_test_b_directory_url.'assets/css/jquery-linedtextarea.css');
			wp_enqueue_script( 'wpsm_test_b-line-edit-js', wpshopmart_test_b_directory_url.'assets/js/jquery-linedtextarea.js');
			
			wp_enqueue_script( 'wpsm_tabs_bootstrap-js', wpshopmart_test_b_directory_url.'assets/js/bootstrap.js');
			
			//tooltip
			wp_enqueue_style('wpsm_test_b_tooltip', wpshopmart_test_b_directory_url.'assets/tooltip/darktooltip.css');
			wp_enqueue_script( 'wpsm_test_b-tooltip-js', wpshopmart_test_b_directory_url.'assets/tooltip/jquery.darktooltip.js');
			
			// tab settings
			wp_enqueue_style('wpsm_test_b_settings-css', wpshopmart_test_b_directory_url.'assets/css/settings.css');
			
			
		}
	}
	public function test_b_register_cpt(){
		require_once('cpt-reg.php');
		add_filter( 'manage_edit-test_builder_columns', array(&$this, 'test_builder_columns' )) ;
		add_action( 'manage_test_builder_posts_custom_column', array(&$this, 'test_builder_manage_columns' ), 10, 2 );
	}
	function test_builder_columns( $columns ){
        $columns = array(
            'cb' => '<input type="checkbox" />',
            'title' => __( 'testimonial' ),
            'count' => __( 'testimonial Count' ),
            'shortcode' => __( 'testimonial Shortcode' ),
            'date' => __( 'Date' )
        );
        return $columns;
    }

    function test_builder_manage_columns( $column, $post_id ){
        global $post;
		$TotalCount =  get_post_meta( $post_id, 'wpsm_test_b_count', true );
		if(!$TotalCount || $TotalCount==-1){
		$TotalCount =0;
		}
        switch( $column ) {
          case 'shortcode' :
            echo '<input style="width:225px" type="text" value="[TEST_B id='.$post_id.']" readonly="readonly" />';
            break;
			case 'count' :
            echo $TotalCount;
            break;
          default :
            break;
        }
    }
	// metaboxes
	public function wpsm_test_b_meta_boxes_group(){
		add_meta_box('test_b_design', __('Select Design', wpshopmart_test_b_text_domain), array(&$this, 'wpsm_add_test_b_design_function'), 'test_builder', 'normal', 'low' );
		add_meta_box('test_b_add', __('Add testimonial Panel', wpshopmart_test_b_text_domain), array(&$this, 'wpsm_add_test_b_meta_box_function'), 'test_builder', 'normal', 'low' );
		add_meta_box ('test_b_shortcode', __('Testimonial Shortcode', wpshopmart_test_b_text_domain), array(&$this, 'wpsm_pic_test_b_shortcode'), 'test_builder', 'normal', 'low');
		add_meta_box ('test_b_help', __('Support & Docs', wpshopmart_test_b_text_domain), array(&$this, 'wpsm_pic_test_b_help'), 'test_builder', 'normal', 'low');
		
		add_meta_box ('test_b_more_pro', __('More Pro Plugin From Wpshopmart', wpshopmart_test_b_text_domain), array(&$this, 'wpsm_pic_test_b__more_pro'), 'test_builder', 'normal', 'low');
		add_meta_box('test_b_follow', __('follow Us', wpshopmart_test_b_text_domain), array(&$this, 'wpsm_test_b_follow_meta_box_function'), 'test_builder', 'side', 'low');
		add_meta_box('test_b_setting', __('Testimonial Settings', wpshopmart_test_b_text_domain), array(&$this, 'wpsm_add_test_b_setting_function'), 'test_builder', 'side', 'low');
		add_meta_box('test_b_donate', __('Donate Us', wpshopmart_test_b_text_domain), array(&$this, 'wpsm_test_b_donate_meta_box_function'), 'test_builder', 'side', 'low');		
		add_meta_box('test_b_faetures', __('Testimonial Pro Features', wpshopmart_test_b_text_domain), array(&$this, 'wpsm_add_test_b_features_function'), 'test_builder', 'side', 'low');
	}
	
	public function wpsm_add_test_b_design_function($post){
		require_once('design.php');
	}
	
	public function wpsm_add_test_b_meta_box_function($post){
		require_once('add-test.php');
	}
	public function add_test_b_save_meta_box_save($PostID){
		require('data-post/test-save-data.php');
	}
	public function test_b_settings_meta_box_save($PostID){
		require('data-post/test-settings-save-data.php');
	}
	public function wpsm_pic_test_b_shortcode(){
		require('test-shortcode-css.php');
	}
	public function wpsm_pic_test_b_help(){
		require_once('help.php');
	}
	public function wpsm_test_b_follow_meta_box_function(){
		?>
		<style>
		#test_b_follow{
			background:#3338dd;
			text-align:center;
			}
			#test_b_follow .hndle , #test_b_follow .handlediv{
			display:none;
			}
			#test_b_follow h1{
			color:#fff;
			margin-bottom:10px;
			}
			 #test_b_follow h3 {
			color:#fff;
			font-size:15px;
			}
			#test_b_follow .button-hero{
			background: #efda4a;
    color: #312c2c;
    box-shadow: none;
    text-shadow: none;
    font-weight: 500;
    font-size: 22px;
    border: 1px solid #efda4a;
			}
			.wpsm-rate-us{
			text-align:center;
			}
			.wpsm-rate-us span.dashicons {
				width: 40px;
				height: 40px;
				font-size:20px;
				color : #fff !important;
			}
			.wpsm-rate-us span.dashicons-star-filled:before {
				content: "\f155";
				font-size: 40px;
			}
		</style>
		   <h1>Follow Us On</h1>
		   <h3>Youtube To Grab Free Web design Course & WordPress Help/Tips </h3>
			<a href="https://www.youtube.com/c/wpshopmart" target="_blank"><img style="width:200px;height:auto" src="<?php echo wpshopmart_test_b_directory_url.'assets/images/youtube.png'; ?>" /></a>
			<a href="https://www.youtube.com/c/wpshopmart?sub_confirmation=1" target="_blank" class="button button-primary button-hero ">Subscribe Us Now</a>
			<?php
	}
	public function wpsm_test_b_donate_meta_box_function(){
		?>
			<style>
			#test_b_donate{
			background:#dd3333;
			text-align:center
			}
			#test_b_donate .hndle , #test_b_donate .handlediv{
			display:none;
			}
			#test_b_donate h1{
			    color: #fff;
				border-bottom: 1px dashed rgba(255, 255, 255,0.9);
				padding-bottom: 10px;
			}
			 #test_b_donate h3 {
			color:#fff;
			font-size:15px;
			}
			#test_b_donate .button-hero{
			display:block;
			text-align:center;
			margin-bottom:15px;
			background:#fff !important;
			color:#000 !important;
			box-shadow:none;
			text-shadow:none;
			font-weight:600;
			font-size:18px;
			border:0px;
			}
			.wpsm-rate-us{
			text-align:center;
			}
			.wpsm-rate-us span.dashicons {
				width: 40px;
				height: 40px;
				font-size:20px;
				color:#fff !important;
			}
			.wpsm-rate-us span.dashicons-star-filled:before {
				content: "\f155";
				font-size: 40px;
			}
		</style>
		   <h1>Please Rate Us </h1>
			<h3>If you like our plugin then please give us some valuable feedback on wordpress</h3>
			<a href="https://wordpress.org/support/plugin/testimonial-builder/reviews/?filter=5" target="_blank" class="button button-primary button-hero ">RATE HERE</a>
			<a class="wpsm-rate-us" style=" text-decoration: none; height: 40px; width: 40px;" href="https://wordpress.org/support/plugin/testimonial-builder/reviews/?filter=5" target="_blank">
				<span class="dashicons dashicons-star-filled"></span>
				<span class="dashicons dashicons-star-filled"></span>
				<span class="dashicons dashicons-star-filled"></span>
				<span class="dashicons dashicons-star-filled"></span>
				<span class="dashicons dashicons-star-filled"></span>
			</a>
			<?php 
		
			
	}
	
	public function wpsm_add_test_b_setting_function($post){
		require_once('settings.php');
	}
	
	public function wpsm_pic_test_b__more_pro(){
		require_once('more-pro.php');
	}
	public function wpsm_add_test_b_features_function(){
		?><style>
		.pro-button-div .btn-danger{    font-size: 19px;
    color: #fff;
    background-color: #01c698 !important;
    border-color: #01c698 !important;
    border-radius: 1px;
    margin-right: 10px;
    margin-top: 0px;
	width:100%;
	text-decoration:none;
	margin-bottom:10px;
	
		}
		.pro-button-div .btn-success{    font-size: 19px;
    color: #fff;
    background-color: #673ab7 !important;
    border-color: #673ab7 !important;
    border-radius: 1px;
    margin-right: 10px;
    margin-top: 0px;
	width:100%;
	text-decoration:none;
	
		}
		.pro-list li i{
		margin-right:10px;	
		}
		</style>
			<ul class="pro-list">
			<li> <i class="fa fa-check"></i>100+ Slider Templates </li>
			<li> <i class="fa fa-check"></i>50+ Grid Templates</li>
			<li> <i class="fa fa-check"></i>3d Testimonial Effect</li>
			<li> <i class="fa fa-check"></i>Front End Submission</li>
			<li> <i class="fa fa-check"></i>Filter Option</li>
				<li><i class="fa fa-check"></i>10+ Column Layout</li>
			<li> <i class="fa fa-check"></i>Add Website Link</li>
			<li> <i class="fa fa-check"></i>Add Email Link </li>								
			<li> <i class="fa fa-check"></i>Add Ratings</li>								
			<li> <i class="fa fa-check"></i>Touch Slider Effect </li>	
			<li> <i class="fa fa-check"></i>1000+ FontAwesome Icons</li>
			<li> <i class="fa fa-check"></i>5+ Dot navigation Style</li>
			<li> <i class="fa fa-check"></i>5+ Button Navigation Style</li>
			<li> <i class="fa fa-check"></i>GDPR Supported </li>
			<li> <i class="fa fa-check"></i>Quotes Icon Settings </li>
			<li> <i class="fa fa-check"></i>500+ Google Fonts </li>
			<li> <i class="fa fa-check"></i>Border Color Customization </li>
			<li> <i class="fa fa-check"></i>Unlimited Color Scheme </li>
			<li> <i class="fa fa-check"></i>High Priority Support </li>
			<li> <i class="fa fa-check"></i>All Browser Compatible </li>
											
			</ul>
			<div class="pro-button-div">
				<a class="btn btn-danger btn-lg " href="https://wpshopmart.com/plugins/testimonial-pro/" target="_blank">Check Pro Version</a><a class="btn btn-success btn-lg " href="http://demo.wpshopmart.com/testimonial-pro-demo/" target="_blank">Testimonial Pro Demo</a>
			</div>				
		<?php
	}
	
}
global $wpsm_test_b;
$wpsm_test_b = wpsm_test_b::forge();
	

?>