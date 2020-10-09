<?php
/*
 * No direct access to this file
 */
if (! isset($data)) {
	exit;
}

include_once '_top-area.php';

$data['locked_for_pro'] = '<span class="dashicons dashicons-info"></span> Managing CSS/JS on the selected page requires an <a href="'.WPACU_PLUGIN_GO_PRO_URL.'?utm_source=plugin_assets_manager&utm_medium=[wpacu_chosen_page_type]">upgrade to the Pro version</a> of Asset CleanUp.</strong>';
?>
<div class="wpacu-wrap">
    <nav class="nav-tab-wrapper nav-assets-manager">
        <a href="<?php echo admin_url('admin.php?page=wpassetcleanup_assets_manager&wpacu_for=homepage'); ?>" class="nav-tab <?php if ($data['for'] === 'homepage') { ?>nav-tab-active<?php } ?>"><?php _e('Homepage', 'wp-asset-clean-up'); ?></a>
        <a href="<?php echo admin_url('admin.php?page=wpassetcleanup_assets_manager&wpacu_for=posts'); ?>" class="nav-tab <?php if ($data['for'] === 'posts') { ?>nav-tab-active<?php } ?>"><?php _e('Posts', 'wp-asset-clean-up'); ?></a>
        <a href="<?php echo admin_url('admin.php?page=wpassetcleanup_assets_manager&wpacu_for=pages'); ?>" class="nav-tab <?php if ($data['for'] === 'pages') { ?>nav-tab-active<?php } ?>"><?php _e('Pages', 'wp-asset-clean-up'); ?></a>
        <a href="<?php echo admin_url('admin.php?page=wpassetcleanup_assets_manager&wpacu_for=custom-post-types'); ?>" class="nav-tab <?php if ($data['for'] === 'custom-post-types') { ?>nav-tab-active<?php } ?>"><?php _e('Custom Post Types', 'wp-asset-clean-up'); ?></a>
        <a href="<?php echo admin_url('admin.php?page=wpassetcleanup_assets_manager&wpacu_for=media-attachment'); ?>" class="nav-tab <?php if ($data['for'] === 'media-attachment') { ?>nav-tab-active<?php } ?>"><?php _e('Media', 'wp-asset-clean-up'); ?></a>
        <a href="<?php echo admin_url('admin.php?page=wpassetcleanup_assets_manager&wpacu_for=category'); ?>" class="nav-tab for-pro <?php if ($data['for'] === 'category') { ?>nav-tab-active<?php } ?>"><img style="opacity: 0.4;" width="20" height="20" src="<?php echo WPACU_PLUGIN_URL; ?>/assets/icons/icon-lock.svg" valign="top" alt=""> <?php _e('Category', 'wp-asset-clean-up'); ?></a>
        <a href="<?php echo admin_url('admin.php?page=wpassetcleanup_assets_manager&wpacu_for=tag'); ?>" class="nav-tab for-pro <?php if ($data['for'] === 'tag') { ?>nav-tab-active<?php } ?>"><img style="opacity: 0.4;" width="20" height="20" src="<?php echo WPACU_PLUGIN_URL; ?>/assets/icons/icon-lock.svg" valign="top" alt=""> <?php _e('Tag', 'wp-asset-clean-up'); ?></a>
        <a href="<?php echo admin_url('admin.php?page=wpassetcleanup_assets_manager&wpacu_for=custom-taxonomy'); ?>" class="nav-tab for-pro <?php if ($data['for'] === 'custom-taxonomy') { ?>nav-tab-active<?php } ?>"><img style="opacity: 0.4;" width="20" height="20" src="<?php echo WPACU_PLUGIN_URL; ?>/assets/icons/icon-lock.svg" valign="top" alt=""> <?php _e('Custom Taxonomy', 'wp-asset-clean-up'); ?></a>
        <a href="<?php echo admin_url('admin.php?page=wpassetcleanup_assets_manager&wpacu_for=search'); ?>" class="nav-tab for-pro <?php if ($data['for'] === 'search') { ?>nav-tab-active<?php } ?>"><img style="opacity: 0.4;" width="20" height="20" src="<?php echo WPACU_PLUGIN_URL; ?>/assets/icons/icon-lock.svg" valign="top" alt=""> <?php _e('Search', 'wp-asset-clean-up'); ?></a>
        <a href="<?php echo admin_url('admin.php?page=wpassetcleanup_assets_manager&wpacu_for=author'); ?>" class="nav-tab for-pro <?php if ($data['for'] === 'author') { ?>nav-tab-active<?php } ?>"><img style="opacity: 0.4;" width="20" height="20" src="<?php echo WPACU_PLUGIN_URL; ?>/assets/icons/icon-lock.svg" valign="top" alt=""> <?php _e('Author', 'wp-asset-clean-up'); ?></a>
        <a href="<?php echo admin_url('admin.php?page=wpassetcleanup_assets_manager&wpacu_for=date'); ?>" class="nav-tab for-pro <?php if ($data['for'] === 'date') { ?>nav-tab-active<?php } ?>"><img style="opacity: 0.4;" width="20" height="20" src="<?php echo WPACU_PLUGIN_URL; ?>/assets/icons/icon-lock.svg" valign="top" alt=""> <?php _e('Date', 'wp-asset-clean-up'); ?></a>
        <a href="<?php echo admin_url('admin.php?page=wpassetcleanup_assets_manager&wpacu_for=404-not-found'); ?>" class="nav-tab for-pro <?php if ($data['for'] === '404-not-found') { ?>nav-tab-active<?php } ?>"><img style="opacity: 0.4;" width="20" height="20" src="<?php echo WPACU_PLUGIN_URL; ?>/assets/icons/icon-lock.svg" valign="top" alt=""> <?php _e('404 Not Found', 'wp-asset-clean-up'); ?></a>
    </nav>
    <div class="wpacu-clearfix"></div>
    <?php
    if ($data['for'] === 'homepage') {
        include_once '_admin-pages-assets-manager/_homepage.php';
    } elseif ($data['for'] === 'posts') {
	    include_once '_admin-pages-assets-manager/_posts.php';
    } elseif ($data['for'] === 'custom-post-types') {
	    include_once '_admin-pages-assets-manager/_custom-post-types.php';
    } elseif ($data['for'] === 'pages') {
	    include_once '_admin-pages-assets-manager/_pages.php';
    } elseif ($data['for'] === 'media-attachment') {
	    include_once '_admin-pages-assets-manager/_media-attachment.php';
    } elseif ($data['for'] === 'category') {
	    include_once '_admin-pages-assets-manager/_category.php';
    } elseif ($data['for'] === 'tag') {
	    include_once '_admin-pages-assets-manager/_tag.php';
    } elseif ($data['for'] === 'custom-taxonomy') {
	    include_once '_admin-pages-assets-manager/_custom-taxonomy.php';
    } elseif ($data['for'] === 'search') {
	    include_once '_admin-pages-assets-manager/_search.php';
    } elseif ($data['for'] === 'author') {
	    include_once '_admin-pages-assets-manager/_author.php';
    } elseif ($data['for'] === 'date') {
	    include_once '_admin-pages-assets-manager/_date.php';
    } elseif ($data['for'] === '404-not-found') {
	    include_once '_admin-pages-assets-manager/_404-not-found.php';
    }
    ?>
</div>