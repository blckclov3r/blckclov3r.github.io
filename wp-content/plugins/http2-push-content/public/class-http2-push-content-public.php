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
class Http2_Push_Content_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	public $push_list;

	public $push_general_list = false;

	public $push_all_style = false;

	public $push_all_script = false;

	public $css_async = array();

	public $apply_obj; 
	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->apply_obj = new Http2_Push_Content_Apply_To();

		$this->plugin_name = $plugin_name;
		$this->version = $version;

		
		
		$this->push_all_style = get_option('push_all_style',true);		
		$this->push_all_script = get_option('push_all_script',true);
		$this->push_general_list = get_option('http2_push_general_list');
		$this->css_async_list = get_option('http2_async_css_list',true);	
		$this->js_async_list = get_option('http2_async_js_list',true);

		$this->general_list();

		if(!is_admin()) {

			

			
			
			add_action( 'wp_head', array($this,'http2_resource_in_document'), 99, 1);
			
			if($this->push_all_script != false):
				add_filter('script_loader_src', array($this,'http2_link_script_to_header'), 99, 1);
			endif;

			if($this->push_all_style != false):
				add_filter('style_loader_src', array($this, 'http2_link_style_to_header'), 99, 1);
			endif;

			add_action('init',  array($this, 'http2_ob_start'));
			
			add_filter( 'style_loader_tag', array($this, 'async_style'),99,4 );
			add_filter( 'script_loader_tag', array($this, 'async_script'),99,3 );

			add_action('wp_footer', array($this,'adding_polyfill'));
		}

	}

	function async_style($html, $handle, $href, $media){
		$to_do = $this->check_async_style_list($href);
		if($to_do[0]){
			if($to_do[1] == 'remove'){
				$final_html = "";
			}else{
				$final_html = '<link rel="preload" id="'.$handle.'" href="'.$href.'"  media="'.$media.'" as="style" onload="this.onload=null;this.rel=\'stylesheet\'" />'.'<noscript>'.$html.'</noscript>';
			}
			return $final_html;
		}else{
			return $html;
		}
	}

	function async_script($html, $handle, $src){
		$to_do = $this->check_async_js_list($src);
		
		if($to_do[0]){
			
			if($to_do[1] == 'remove'){
				$final_html = "";
			}elseif($to_do[1] == 'defer'){
				$final_html = '<script defer src="'.$src.'"></script>';
			}elseif($to_do[1] == 'async'){
				$final_html = '<script async src="'.$src.'"></script>';
			}

			return $final_html;
		}else{
			return $html;
		}
	}

	function check_async_js_list($src){
		$make_async = array(false, false);
		if(is_array($this->js_async_list)):
			foreach($this->js_async_list as $js){
				if(isset($js['js']) && strpos($src, $js['js'])){
					if($this->apply_obj->check($js['apply_to'], $js)):
						$make_async = array(true, isset($js['to']) ? $js['to'] : 'async');
					endif;
				}
			}
		endif;

		return $make_async;
	}

	function check_async_style_list($href){
		$make_async = array(false, false);
		if(is_array($this->css_async_list)):
			foreach($this->css_async_list as $css){
				if(isset($css['css']) && strpos($href, $css['css'])){
					if($this->apply_obj->check($css['apply_to'], $css)):
						$make_async = array(true, isset($css['to']) ? $css['to'] : 'async');
					endif;
				}
			}
		endif;

		return $make_async;
	}
	/**
	 * Adding list from general option to the push list
	 */
	function general_list(){
		$links = $this->push_general_list;
		if(isset($links) && $links != false && is_array($links)):
			foreach($links as $link){
				$this->push_list[] = array(
					'url'=>$link['url'],
					'as'=> $link['as'],
					 'to'=>(($link['to'] == "") ? 'push-preload': $link['to']),
					 'apply_to'=> (isset($link['apply_to']) ? $link['apply_to'] : 'all' ),
					 'id' => (isset($link['id']) ? $link['id'] : '' )
					 ) ;
			}
		endif;
	}

	/**
	 * This is neaded so we can call header() without error
	 */
	function http2_ob_start() {
		ob_start();
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Http2_Push_Content_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Http2_Push_Content_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		//wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/http2-push-content-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Http2_Push_Content_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Http2_Push_Content_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		//wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/http2-push-content-public.js', array( 'jquery' ), $this->version, false );

	}

	

	function http2_link_style_to_header($src){
		$this->push_list[] = array('url'=>$src,'as'=> 'style', 'to'=>$this->push_all_style, 'apply_to'=> 'all');
		return $src;
	} 

	function http2_link_script_to_header($src){
		$this->push_list[] = array('url'=>$src,'as'=> 'script', 'to'=>$this->push_all_script,  'apply_to'=> 'all');
		return $src;
	} 

	function http2_link_to_header($src, $as) {

		global $http2_header_size_accumulator;
	
		if (strpos($src, site_url()) !== false) {
	
	
			if (!empty($src) && !empty($as)) {
				
				if($as == 'font'){
					$header = sprintf(
						'Link: <%s>; rel=preload; as=%s; crossorigin',
						esc_url( $this->http2_link_to_relative_path( $src ) ), 
						$as 
					);
				}else{
					$header = sprintf(
						'Link: <%s>; rel=preload; as=%s',
						esc_url( $this->http2_link_to_relative_path( $src ) ), 
						$as 
					);
				}
	
				// Make sure we haven't hit the header limit
				if(($http2_header_size_accumulator + strlen($header)) < PI_HTTP2_MAX_HEADER_SIZE) {
					$http2_header_size_accumulator += strlen($header);
					header( $header, false );

					$GLOBALS['pi_resources']['http2_' . $as . '_srcs'][] = array('url'=> $src, 'as'=> $as );
				}
							
			}
	
		}
	
		return $src;
	}

	function http2_resource_in_document(){
		//$resources = $GLOBALS['pi_resources'];
		$resources = $this->push_list;
		if(is_array($resources)) {
		foreach($resources as $link){
			
			if($this->apply_obj->check($link['apply_to'], $link)):

				if(isset($link) && $link != false):
					if($link['to'] == 'preload' || $link['to'] == 'push-preload'){
						$this->http2_resource_hints($link['url'], $link['as']);
					}

					if($link['to'] == 'push' || $link['to'] == 'push-preload'){
						$this->http2_link_to_header($link['url'], $link['as']);
					}
				endif;
				
			endif;
		}
		}
	}

	/**
	 * Adding resource preload in document as well
	 */
	function http2_resource_hints($src, $as) {
		if($as == 'font'){
			printf( '<link rel="preload" href="%s" as="%s" crossorigin>', esc_url($src), $as );
		}else{
			printf( '<link rel="preload" href="%s" as="%s">', esc_url($src), $as );
		}
		
	}

	/**
	 * Convert an URL to a relative path
	 *
	 */
	function http2_link_to_relative_path($src) {
		return '//' === substr($src, 0, 2) ? preg_replace('/^\/\/([^\/]*)\//', '/', $src) : preg_replace('/^http(s)?:\/\/[^\/]*/', '', $src);
	}

	/**
	 * polifyll for async css
	 */
	function adding_polyfill(){
		?>
		<script>
		(function( w ){
	"use strict";
	// rel=preload support test
	if( !w.loadCSS ){
		w.loadCSS = function(){};
	}
	// define on the loadCSS obj
	var rp = loadCSS.relpreload = {};
	// rel=preload feature support test
	// runs once and returns a function for compat purposes
	rp.support = (function(){
		var ret;
		try {
			ret = w.document.createElement( "link" ).relList.supports( "preload" );
		} catch (e) {
			ret = false;
		}
		return function(){
			return ret;
		};
	})();

	// if preload isn't supported, get an asynchronous load by using a non-matching media attribute
	// then change that media back to its intended value on load
	rp.bindMediaToggle = function( link ){
		// remember existing media attr for ultimate state, or default to 'all'
		var finalMedia = link.media || "all";

		function enableStylesheet(){
			// unbind listeners
			if( link.addEventListener ){
				link.removeEventListener( "load", enableStylesheet );
			} else if( link.attachEvent ){
				link.detachEvent( "onload", enableStylesheet );
			}
			link.setAttribute( "onload", null ); 
			link.media = finalMedia;
		}

		// bind load handlers to enable media
		if( link.addEventListener ){
			link.addEventListener( "load", enableStylesheet );
		} else if( link.attachEvent ){
			link.attachEvent( "onload", enableStylesheet );
		}

		// Set rel and non-applicable media type to start an async request
		// note: timeout allows this to happen async to let rendering continue in IE
		setTimeout(function(){
			link.rel = "stylesheet";
			link.media = "only x";
		});
		// also enable media after 3 seconds,
		// which will catch very old browsers (android 2.x, old firefox) that don't support onload on link
		setTimeout( enableStylesheet, 3000 );
	};

	// loop through link elements in DOM
	rp.poly = function(){
		// double check this to prevent external calls from running
		if( rp.support() ){
			return;
		}
		var links = w.document.getElementsByTagName( "link" );
		for( var i = 0; i < links.length; i++ ){
			var link = links[ i ];
			// qualify links to those with rel=preload and as=style attrs
			if( link.rel === "preload" && link.getAttribute( "as" ) === "style" && !link.getAttribute( "data-loadcss" ) ){
				// prevent rerunning on link
				link.setAttribute( "data-loadcss", true );
				// bind listeners to toggle media back
				rp.bindMediaToggle( link );
			}
		}
	};

	// if unsupported, run the polyfill
	if( !rp.support() ){
		// run once at least
		rp.poly();

		// rerun poly on an interval until onload
		var run = w.setInterval( rp.poly, 500 );
		if( w.addEventListener ){
			w.addEventListener( "load", function(){
				rp.poly();
				w.clearInterval( run );
			} );
		} else if( w.attachEvent ){
			w.attachEvent( "onload", function(){
				rp.poly();
				w.clearInterval( run );
			} );
		}
	}


	// commonjs
	if( typeof exports !== "undefined" ){
		exports.loadCSS = loadCSS;
	}
	else {
		w.loadCSS = loadCSS;
	}
}( typeof global !== "undefined" ? global : this ) );
		</script>
		<?php
	}
}
