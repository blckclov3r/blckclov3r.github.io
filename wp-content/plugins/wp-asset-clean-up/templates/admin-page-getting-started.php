<?php
/*
 * No direct access to this file
 */
if (! isset($data)) {
    exit;
}
?>
<div class="wpacu-wrap">
    <div class="about-wrap wpacu-about-wrap">
        <h1><?php echo sprintf(__('Welcome to %s %s', 'wp-asset-clean-up'), 'Asset CleanUp', WPACU_PLUGIN_VERSION); ?></h1>
        <p class="about-text wpacu-about-text">
            <?php _e('Thank you for installing this page speed booster plugin', 'wp-asset-clean-up'); ?>! <?php _e('Prepare to make your WordPress website faster &amp; lighter by removing the useless CSS &amp; JavaScript files from your pages.', 'wp-asset-clean-up'); ?>
            <?php echo sprintf(
                    __('For maximum performance, %s works best when used with either a %scaching plugin%s, the in-built hosting caching (e.g. via %sWPEngine%s, Kinsta, etc.) or something like Varnish.', 'wp-asset-clean-up'),
                     'Asset CleanUp',
                    '<a style="text-decoration: none; color: #555d66;" href="https://www.gabelivan.com/visit/wp-rocket">', '</a>',
                    '<a style="text-decoration: none; color: #555d66;" href="https://www.gabelivan.com/visit/wp-engine">', '</a>'
            );
            ?>
            <img src="<?php echo WPACU_PLUGIN_URL; ?>/assets/images/wpacu-logo-transparent-bg-v1.png" alt="" />
        </p>

        <h2 class="nav-tab-wrapper wp-clearfix">
            <a href="<?php echo admin_url('admin.php?page=wpassetcleanup_getting_started&wpacu_for=how-it-works'); ?>" class="nav-tab <?php if ($data['for'] === 'how-it-works') { ?>nav-tab-active<?php } ?>"><?php _e('How it works', 'wp-asset-clean-up'); ?></a>
            <a href="<?php echo admin_url('admin.php?page=wpassetcleanup_getting_started&wpacu_for=benefits-fast-pages'); ?>" class="nav-tab <?php if ($data['for'] === 'benefits-fast-pages') { ?>nav-tab-active<?php } ?>"><?php _e('Benefits of a Fast Website', 'wp-asset-clean-up'); ?></a>
            <a href="<?php echo admin_url('admin.php?page=wpassetcleanup_getting_started&wpacu_for=start-optimization'); ?>" class="nav-tab <?php if ($data['for'] === 'start-optimization') { ?>nav-tab-active<?php } ?>"><?php _e('Start Optimization', 'wp-asset-clean-up'); ?></a>
            <a href="<?php echo admin_url('admin.php?page=wpassetcleanup_getting_started&wpacu_for=video-tutorials'); ?>" class="nav-tab <?php if ($data['for'] === 'video-tutorials') { ?>nav-tab-active<?php } ?>"><span class="dashicons dashicons-video-alt3" style="color: #ff0000;"></span> <?php _e('Video Tutorials', 'wp-asset-clean-up'); ?></a>
            <a href="<?php echo admin_url('admin.php?page=wpassetcleanup_getting_started&wpacu_for=lite-vs-pro'); ?>" class="nav-tab <?php if ($data['for'] === 'lite-vs-pro') { ?>nav-tab-active<?php } ?>"><span class="dashicons dashicons-awards"></span> <?php _e('Lite vs Pro', 'wp-asset-clean-up'); ?></a>
        </h2>

        <div class="about-wrap-content">
            <?php
            if ($data['for'] === 'how-it-works') {
                include_once '_admin-page-getting-started-areas/_how-it-works.php';
            } elseif ($data['for'] === 'benefits-fast-pages') {
                include_once '_admin-page-getting-started-areas/_benefits-fast-pages.php';
            } elseif ($data['for'] === 'start-optimization') {
                include_once '_admin-page-getting-started-areas/_start-optimization.php';
            } elseif ($data['for'] === 'video-tutorials') {
	            include_once '_admin-page-getting-started-areas/_video-tutorials.php';
            } elseif ($data['for'] === 'lite-vs-pro') {
                include_once '_admin-page-getting-started-areas/_lite-vs-pro.php';
            }
            ?>
        </div>
    </div>
</div>