<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       piwebsolution.com
 * @since      1.0.0
 *
 * @package    Http2_Push_Content
 * @subpackage Http2_Push_Content/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Http2_Push_Content
 * @subpackage Http2_Push_Content/public
 * @author     piwebsolution <rajeshsingh520@gmail.com>
 */

if(!class_exists("Http2_Push_Content_Apply_To")){
class Http2_Push_Content_Apply_To {

	

	public $apply_to;
	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( ) {
				
	}

	function apply_to_options(){
		?>
			<option disabled><?php _e('Select', 'http2-push-content'); ?></option>
			<option value="all" {{if value.apply_to == 'all'}}selected="selected"{{/if}}>All Pages</option>
			<option value="mobile" {{if value.apply_to == 'mobile'}}selected="selected"{{/if}}>Mobile or tablet Device</option>
			<option value="not_mobile" {{if value.apply_to == 'not_mobile'}}selected="selected"{{/if}}>Desktop</option>
			<option value="front_page" {{if value.apply_to == 'front_page'}}selected="selected"{{/if}}>On Front Page</option>
			<option value="page" {{if value.apply_to == 'page'}}selected="selected"{{/if}}>On Page</option> 
			<option value="specific_pages" {{if value.apply_to == 'specific_pages'}}selected="selected"{{/if}}>On Specific Pages (by page ID)</option> 
			<option value="not_specific_pages" {{if value.apply_to == 'not_specific_pages'}}selected="selected"{{/if}}>Not On Specific Pages (by page ID)</option> 
			<option value="page_exclude_front_page" {{if value.apply_to == 'page_exclude_front_page'}}selected="selected"{{/if}}>On Page exclude front page</option>                       
			<option value="single" {{if value.apply_to == 'single'}}selected="selected"{{/if}}>On Single Post</option>
			<option value="specific_posts" {{if value.apply_to == 'specific_posts'}}selected="selected"{{/if}}>On Specific Posts (by post ID)</option>  
			<option value="not_specific_posts" {{if value.apply_to == 'not_specific_posts'}}selected="selected"{{/if}}>Not On Specific posts (by page ID)</option>                        
			<option value="home" {{if value.apply_to == 'home'}}selected="selected"{{/if}}>On Blog Home</option>                        
			<option value="category" {{if value.apply_to == 'category'}}selected="selected"{{/if}}>On Category</option>                        
			<option value="tag" {{if value.apply_to == 'tag'}}selected="selected"{{/if}}>On Tag</option>                        
			<option value="search" {{if value.apply_to == 'search'}}selected="selected"{{/if}}>On Search Page</option>                        
			<option value="rtl" {{if value.apply_to == 'rtl'}}selected="selected"{{/if}}>On RTL Page</option>
			
			<option value="woocommerce_category" {{if value.apply_to == 'woocommerce_category'}}selected="selected"{{/if}}>On WooCommerce category page</option>
			<option value="woocommerce_shop" {{if value.apply_to == 'woocommerce_shop'}}selected="selected"{{/if}}>On WooCommerce shop page</option>
			<option value="woocommerce_single_product" {{if value.apply_to == 'woocommerce_single_product'}}selected="selected"{{/if}}>On WooCommerce Single product page</option>

			<option value="woocommerce_cart" {{if value.apply_to == 'woocommerce_cart'}}selected="selected"{{/if}}>On WooCommerce cart page</option>
			<option value="woocommerce_checkout" {{if value.apply_to == 'woocommerce_checkout'}}selected="selected"{{/if}}>On WooCommerce checkout page</option>
			
			<option value="page_exclude_woo_cart_and_checkout" {{if value.apply_to == 'page_exclude_woo_cart_and_checkout'}}selected="selected"{{/if}}>All Pages exclude WooCommerce Cart & Checkout Page</option>                     
		<?php
	}

	function getArray($ids){
		if(empty($ids)) return array();

		$array = explode(',',$ids);
		$array = array_map('trim', $array);
		return $array;
	}

	/**
	 * It check the asset type and calls appropreat check function 
	 * and if asset apply to matches present page type then it return true else it return false
	 * if any mismach hapen it will return true and asset will be applied on global scale
	 */
	function check($apply_to, $rule = ""){
		if(isset($apply_to)){
			if(method_exists($this, 'is_'.$apply_to)){
				return $this->{'is_'.$apply_to}($rule);
			}else{
				return true;
			}
		}else{
			return true;
		}
	}

	function is_specific_pages($rule = ""){
		$ids = isset($rule['id']) ? $this->getArray($rule['id']) : array();

		if(empty($ids))  return false;

		if(function_exists('is_page')) return is_page($ids);

		return false;
	}

	function is_not_specific_pages($rule = ""){
		$ids = isset($rule['id']) ? $this->getArray($rule['id']) : array();

		if(empty($ids))  return false;

		if(function_exists('is_page')) return !is_page($ids);

		return false;
	}

	function is_specific_posts($rule = ""){
		$ids = isset($rule['id']) ? $this->getArray($rule['id']) : array();

		if(empty($ids))  return false;

		if(function_exists('is_single')) return is_single($ids);

		return false;
	}

	function is_not_specific_posts($rule = ""){
		$ids = isset($rule['id']) ? $this->getArray($rule['id']) : array();

		if(empty($ids))  return false;

		if(function_exists('is_single')) return !is_single($ids);

		return false;
	}

	function is_all($rule = ""){
		return true;
	}

	function is_front_page($rule = ""){
		if(function_exists('is_front_page')){
			return is_front_page();
		}
		return false;
	}

	function is_page($rule = ""){
		if(function_exists('is_page')){
			return is_page();
		}
		return false;
	}

	function is_page_exclude_front_page($rule = ""){
		if(function_exists('is_page') && function_exists('is_front_page')){
			if(is_page() && !is_front_page()){
				return true;
			}
		}
		return false;
	}

	function is_page_exclude_woo_cart_and_checkout($rule = ""){
		if(function_exists('is_cart') && function_exists('is_checkout')){
			if(!is_cart() && !is_checkout()){
				return true;
			}else{
				return false;
			}
		}
		return false;
	}

	function is_woocommerce_category($rule = ""){
		if(function_exists('is_product_category')){
			if(is_product_category()){
				return true;
			}else{
				return false;
			}
		}
		return false;
	}

	function is_woocommerce_cart($rule = ""){
		if(function_exists('is_cart')){
			if(is_cart()){
				return true;
			}else{
				return false;
			}
		}
		return false;
	}

	function is_woocommerce_checkout($rule = ""){
		if(function_exists('is_checkout')){
			if(is_checkout()){
				return true;
			}else{
				return false;
			}
		}
		return false;
	}

	function is_woocommerce_shop($rule = ""){
		if(function_exists('is_shop')){
			if(is_shop()){
				return true;
			}else{
				return false;
			}
		}
		return false;
	}

	function is_woocommerce_single_product($rule = ""){
		if(function_exists('is_product')){
			if(is_product()){
				return true;
			}else{
				return false;
			}
		}
		return false;
	}

	function is_single($rule = ""){
		if(!function_exists('is_single'))  return false;

		return is_single();
	}

	function is_home($rule = ""){
		if(!function_exists('is_home'))  return false;

		return is_home();
	}

	function is_category($rule = ""){
		if(!function_exists('is_category'))  return false;

		return is_category();
	} 

	function is_tag($rule = ""){
		if(!function_exists('is_tag'))  return false;

		return is_tag();
	} 

	function is_search($rule = ""){
		if(!function_exists('is_search'))  return false;

		return is_search();
	} 

	function is_rtl($rule = ""){
		if(!function_exists('is_rtl'))  return false;

		return is_rtl();
	} 
	
	function is_mobile($rule = ""){
		if(!function_exists('wp_is_mobile'))  return false;

		return wp_is_mobile();
	}

	function is_not_mobile($rule = ""){
		if(!function_exists('wp_is_mobile'))  return false;
		
		return !wp_is_mobile();
	}
}

}