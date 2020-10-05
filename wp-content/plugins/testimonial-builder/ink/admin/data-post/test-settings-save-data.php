<?php
if ( ! defined( 'ABSPATH' ) ) exit;
if(isset($PostID) && isset($_POST['test_b_setting_save_action'])) {
			$test_mb_name_clr            = sanitize_text_field($_POST['test_mb_name_clr']);
			$test_mb_deg_clr            = sanitize_text_field($_POST['test_mb_deg_clr']);
			$test_mb_web_link_clr            = sanitize_text_field($_POST['test_mb_web_link_clr']);
			$test_mb_content_clr            = sanitize_text_field($_POST['test_mb_content_clr']);
			$bgclr_style2            = sanitize_text_field($_POST['bgclr_style2']);
			
			$test_mb_name_font_size            = sanitize_text_field($_POST['test_mb_name_font_size']);
			$test_mb_deg_font_size            = sanitize_text_field($_POST['test_mb_deg_font_size']);
			$test_mb_web_link_font_size            = sanitize_text_field($_POST['test_mb_web_link_font_size']);
			$test_mb_content_font_size            = sanitize_text_field($_POST['test_mb_content_font_size']);
			$test_font_family            = sanitize_text_field($_POST['test_font_family']);
			$test_layout            = sanitize_text_field($_POST['test_layout']);
			$test_image_layout            = sanitize_text_field($_POST['test_image_layout']);
			$test_mb_web_link_label            = sanitize_text_field($_POST['test_mb_web_link_label']);
			$custom_css            = stripslashes($_POST['custom_css']);
			$templates         = sanitize_option('templates', $_POST['templates']);
			
			$Settings_Array = serialize( array(
				"test_mb_name_clr" 	 => $test_mb_name_clr,
				"test_mb_deg_clr" => $test_mb_deg_clr,
				"test_mb_web_link_clr" => $test_mb_web_link_clr,
				"test_mb_content_clr"   => $test_mb_content_clr,
				"test_mb_name_font_size"   => $test_mb_name_font_size,
				"test_mb_deg_font_size"   => $test_mb_deg_font_size,
				"test_mb_web_link_font_size"   => $test_mb_web_link_font_size,
				"test_mb_content_font_size"   => $test_mb_content_font_size,
				"test_font_family"   => $test_font_family,
				"test_layout"   => $test_layout,
				"test_image_layout"   => $test_image_layout,
				"test_mb_web_link_label"   => $test_mb_web_link_label,
				"custom_css"   => $custom_css,
				"templates"   => $templates,
				"bgclr_style2" => $bgclr_style2,
				) );

			update_post_meta($PostID, 'Test_Settings', $Settings_Array);
		}
?>
