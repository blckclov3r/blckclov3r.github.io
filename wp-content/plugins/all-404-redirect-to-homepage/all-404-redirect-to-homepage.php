<?php
/*
Plugin Name: All 404 Redirect  to Homepage
Plugin URI: http://www.clogica.com
Description: a plugin to redirect 404 pages to home page or any custom page
Author: Fakhri Alsadi
Version: 1.20
Author URI: http://www.clogica.com
*/

if ( ! defined( 'ABSPATH' ) ) exit;

define( 'OPTIONS404', 'options-404-redirect-group' );
require_once ('functions.php');
add_action('admin_menu', 'p404_admin_menu');
add_action('admin_head', 'p404_header_code');
add_action('wp', 'p404_redirect');
add_action( 'admin_enqueue_scripts', 'p404_enqueue_styles_scripts' );
add_action('wp_ajax_P404REDIRECT_HideMsg', 'P404REDIRECT_HideMsg'); 


register_activation_hook( __FILE__ , 'p404_install' );
register_deactivation_hook( __FILE__ , 'p404_uninstall' );


function p404_redirect()
{
	if(is_404()) 
	{
	 	
            $options= P404REDIRECT_get_my_options();
	    $link=P404REDIRECT_get_current_URL();
	    if($link == $options['p404_redirect_to'])
	    {
	        echo "<b>All 404 Redirect to Homepage</b> has detected that the target URL is invalid, this will cause an infinite loop redirection, please go to the plugin settings and correct the traget link! ";
	        exit(); 
	    }
	    
	 	if($options['p404_status']=='1' & $options['p404_redirect_to']!=''){
                        $links = P404REDIRECT_read_option_value('links',0);
                        P404REDIRECT_save_option_value('links', $links + 1);
                        P404REDIRECT_add_redirected_link(P404REDIRECT_get_current_URL());
		 	header ('HTTP/1.1 301 Moved Permanently');
			header ("Location: " . $options['p404_redirect_to']);
			exit(); 
		}
	}
}

//---------------------------------------------------------------

function p404_get_site_404_page_path()
{
   $url= str_ireplace("://", "", site_url());
   $site_404_page = substr($url, stripos($url, "/"));  
    if (stripos($url, "/")=== FALSE || $site_404_page == "/")
       $site_404_page = "/index.php?error=404";
    else
       $site_404_page = $site_404_page . "/index.php?error=404";   
   return  $site_404_page;
}
//---------------------------------------------------------------

function p404_check_default_permalink() 
 {           
    $file= get_home_path() . "/.htaccess";

    $content="ErrorDocument 404 " . p404_get_site_404_page_path();

    $marker_name="FRedirect_ErrorDocument";
    $filestr ="";

    if(file_exists($file)){            
        $f = @fopen( $file, 'r+' );
        $filestr = @fread($f , filesize($file));            
        if (strpos($filestr , $marker_name) === false)
         {
             insert_with_markers( $file,  $marker_name,  $content ); 
         }
    }else
    {
       insert_with_markers( $file,  $marker_name,  $content ); 
    }

 }
 

//---------------------------------------------------------------

function p404_header_code()
{
	p404_check_default_permalink();
	      
}


function p404_enqueue_styles_scripts()
{
    if( is_admin() ) {              
        $css= plugins_url() . '/'.  basename(dirname(__FILE__)) . "/stylesheet.css";               
        wp_enqueue_style( 'main-404-css', $css );
    }
}
        

//---------------------------------------------------------------

function p404_admin_menu() {
	add_options_page('All 404 Redirect to Homepage', 'All 404 Redirect to Homepage', 'manage_options', basename(__FILE__), 'p404_options_menu'  );
}

//---------------------------------------------------------------
function p404_options_menu() {
	
	if (!current_user_can('manage_options'))  {
			wp_die( __('You do not have sufficient permissions to access this page.') );
		}
		
	include "option_page.php";
}
//---------------------------------------------------------------

function p404_install(){

}


//---------------------------------------------------------------

function p404_uninstall(){
	//delete_option(OPTIONS404);
}

//---------------------------------------------------------------
$path = plugin_basename( __FILE__ );
add_action("after_plugin_row_{$path}", 'P404REDIRECT_after_plugin_row', 10, 3);