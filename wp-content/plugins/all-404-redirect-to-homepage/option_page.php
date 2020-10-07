<?php

include_once "cf_dropdown.php";

global $wpdb,$table_prefix;



$redirect_to = (isset($_POST['redirect_to'])) ? sanitize_text_field($_POST['redirect_to']) : '';
$nonce = isset($_REQUEST['_wpnonce']) ? sanitize_text_field($_REQUEST['_wpnonce']) : '';

if($redirect_to !=='')
{
if(wp_verify_nonce( $nonce, 'p404home_nounce' ))
	{
              
                P404REDIRECT_save_option_value('p404_redirect_to',$redirect_to);
                P404REDIRECT_save_option_value('p404_status',sanitize_text_field($_POST['p404_status']));
                
		P404REDIRECT_option_msg('Options Saved!');
		
	}else {
                P404REDIRECT_failure_option_msg('Unable to save data!');
        }
}
$options= P404REDIRECT_get_my_options();
?>

<?php
if(P404REDIRECT_there_is_cache()!='') 
P404REDIRECT_info_option_msg("You have a cache plugin installed <b>'" . P404REDIRECT_there_is_cache() . "'</b>, you have to clear cache after any changes to get the changes reflected immediately! ");
?>
<style>
.nav-tab-active, .nav-tab-active:focus, .nav-tab-active:focus:active, .nav-tab-active:hover {
    border-bottom: 1px solid #fff;
    background:#fff;
    color:#000;
}
#tabs_content {
  padding: 20px;
  background-color: #fff;
  border: 1px solid #ccc;
  border-top: 0;
}
</style>
<div class="wrap">
<div ><div class='inner'>
<h2>All 404 Redirect to Homepage</h2>
	
<?php
$tab = "options";
if(array_key_exists('tab',$_GET))
{
    $tab = filter_var($_GET['tab'], FILTER_SANITIZE_URL);
}
?>

<nav class="nav-tab-wrapper wp-clearfix" aria-label="Secondary menu">
<a href="<?php echo P404REDIRECT_get_current_parameters("tab") . '&tab=options';?>" class="nav-tab <?php if($tab=='options' ) echo 'nav-tab-active';?>">Options</a>
<a href="<?php echo P404REDIRECT_get_current_parameters("tab") . '&tab=404urls';?>" class="nav-tab <?php if($tab=='404urls' ) echo 'nav-tab-active';?>">404 URLs</a>
</nav>
<div id="tabs_content">
<?php
// ----   Options Tab ----------------
if($tab == "options"){
?>
<form method="POST">
	404 Redirection Status: 
	<?php
		$drop = new p404redirect_dropdown('p404_status');
		$drop->add('Enabled','1');	
		$drop->add('Disabled','2');
		$drop->dropdown_print();
		$drop->select($options['p404_status']);
	?>
	
	<br/><br/>
	
	Redirect all 404 pages to: 
	<input type="text" name="redirect_to" id="redirect_to" size="30" value="<?php echo $options['p404_redirect_to']?>">		
	
	<br/>
<input type="hidden" id="_wpnonce" name="_wpnonce" value="<?php echo $nonce = wp_create_nonce('p404home_nounce'); ?>" />
<br />
<input  class="button-primary" type="submit" value="  Update Options  " name="Save_Options"></form>  
<?php
}else if($tab == "404urls"){
// ----   404 URLs Tab ----------------
?>
<div>
    
<hr/>
   <b style="color:red"><?php echo P404REDIRECT_read_option_value('links',0);?></b> URLs redirected since the plugin install in <?php echo P404REDIRECT_read_option_value('install_date',date("Y-m-d h:i a"));?>
<hr/>
<b>Latest 20 URLs Redirected: </b><br/><br/>


    <table class="wp-list-table widefat striped">
    <thead>
    <tr>
        <th width="30">#</th>
        <th width="150">Date</th>
        <th>URL</th>
    </tr>
    </thead>
    <tbody>

        
        <?php
$links = P404REDIRECT_read_option_value('redirected_links',array());
if(count($links)==0){
?>
<tr><td colspan="3">No 404 links redirected yet.</td></tr>
<?php
    } else{ 
    for($i=0; $i<count($links); $i++){
?>
    <tr>
    <td><?php echo $i+1; ?></td>
    <td><?php echo $links[$i]['date']?></td>
    <td><a target="_blank" href="<?php echo $links[$i]['link']?>"><?php echo $links[$i]['link']?></a></td>
    </tr>
<?php }} ?>
    

    </tbody>
    </table>
    <br/></div>

<?php
}
// ----   End of Tabs ----------------
?>
    
    <br/>
    <b style="color:red">Have many broken links?</b> keep track of 404 errors using our powerfull <a target="_blank" href="http://www.clogica.com/product/seo-redirection-premium-wordpress-plugin#404-options-page">SEO Redirection Plugin</a> with an advanced <b>404 Manager</b> and set many rules to handle 404 links

</div></div></div>




				 





