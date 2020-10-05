<?php
if ( ! defined( 'ABSPATH' ) ) exit;
if(isset($PostID) && isset($_POST['test_b_save_data_action']) ) {
			$TotalCount = count($_POST['mb_name']);
			$All_data = array();
			if($TotalCount) {
				for($i=0; $i < $TotalCount; $i++) {
					$mb_photo = sanitize_text_field($_POST['mb_photo'][$i]);
					$mb_name = stripslashes(sanitize_text_field($_POST['mb_name'][$i]));
					$mb_deg = stripslashes(sanitize_text_field($_POST['mb_deg'][$i]));
					$mb_website = sanitize_text_field($_POST['mb_website'][$i]);
					$mb_desc = stripslashes($_POST['mb_desc'][$i]);
					$mb_id = sanitize_text_field($_POST['mb_id'][$i]);
					
					$All_data[] = array(
						'mb_photo' => $mb_photo,
						'mb_name' => $mb_name,
						'mb_deg' => $mb_deg,
						'mb_website' => $mb_website,
						'mb_desc' => $mb_desc,
						'mb_id' => $mb_id,
						
					);
				}
				update_post_meta($PostID, 'wpsm_test_b_data', serialize($All_data));
				update_post_meta($PostID, 'wpsm_test_b_count', $TotalCount);
			} else {
				$TotalCount = -1;
				update_post_meta($PostID, 'wpsm_test_b_count', $TotalCount);
				$All_data = array();
				update_post_meta($PostID, 'wpsm_test_b_data', serialize($All_data));
			}
		}
 ?>