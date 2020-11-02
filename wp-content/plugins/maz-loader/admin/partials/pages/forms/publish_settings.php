<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

if (!MZLDR_Helper::isPro())
{
?>
<a href="#" class="mzldr-button upgrade mzldr-pro-upgrade-button" data-title="<?php _e('Publish Settings', 'maz-loader'); ?>"><?php _e('Upgrade to Pro', 'maz-loader'); ?></a>
<p>
	<?php _e( 'Set conditions that determine where your Loader will appear across your site. To display the Loader site-wide you can choose the <em>Entire Site</em> option.', 'maz-loader' ); ?>
</p>
<?php
return;
}

