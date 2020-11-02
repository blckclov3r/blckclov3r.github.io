<?php
/**
 * MAZ Loader Helper
 *
 * @link       https://www.feataholic.com
 * @since      1.1.8 Free
 */
class MZLDR_WP_Helper {

	/**
	 * Retrieves the attachment ID from the file URL
	 * 
	 * @return  int
	 */
	public static function get_image_alt( $image_url ) {
		global $wpdb;
		
		if( empty( $image_url ) ) {
			return false;
		}

		$query_arr = $wpdb->get_col( $wpdb->prepare("SELECT ID FROM {$wpdb->posts} WHERE guid='%s';", strtolower( $image_url ) )) ;

		$image_id   = ( ! empty( $query_arr ) ) ? $query_arr[0] : 0;

		return get_post_meta( $image_id, '_wp_attachment_image_alt', true );
	}

}
