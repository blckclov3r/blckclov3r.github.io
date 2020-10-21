<?php
/*
 * No direct access to this file
 */
if (! isset($data)) {
	exit;
}

use WpAssetCleanUp\Misc;

$wpacuTopAreaLinks = array(
	'admin.php?page=wpassetcleanup_settings' => array(
		'icon' => '<span class="dashicons dashicons-admin-generic"></span>',
		'title' => __('Settings', 'wp-asset-clean-up'),
		'page' => 'wpassetcleanup_settings'
	),

	'admin.php?page=wpassetcleanup_assets_manager' => array(
		'icon' => '<span class="dashicons dashicons-media-code"></span>',
		'title' => __('CSS &amp; JS Manager', 'wp-asset-clean-up'),
		'page'  => 'wpassetcleanup_assets_manager',
	),

	'admin.php?page=wpassetcleanup_plugins_manager' => array(
		'icon' => '<span class="dashicons dashicons-admin-plugins"></span>',
		'title' => __('Plugins Manager', 'wp-asset-clean-up'),
		'page'  => 'wpassetcleanup_plugins_manager',
	),

	'admin.php?page=wpassetcleanup_bulk_unloads' => array(
		'icon' => '<span class="dashicons dashicons-networking"></span>',
		'title' => __('Bulk Changes', 'wp-asset-clean-up'),
		'page'  => 'wpassetcleanup_bulk_unloads'
	),

	'admin.php?page=wpassetcleanup_overview' => array(
		'icon' => '<span class="dashicons dashicons-media-text"></span>',
		'title' => __('Overview', 'wp-asset-clean-up'),
		'page'  => 'wpassetcleanup_overview'
	),

	'admin.php?page=wpassetcleanup_tools' => array(
		'icon' => '<span class="dashicons dashicons-admin-tools"></span>',
		'title' => __('Tools', 'wp-asset-clean-up'),
		'page' => 'wpassetcleanup_tools'
	),
	'admin.php?page=wpassetcleanup_license' => array(
		'icon' => '<span class="dashicons dashicons-awards"></span>',
		'title' => __('License', 'wp-asset-clean-up'),
		'page' => 'wpassetcleanup_license'
	),
	'admin.php?page=wpassetcleanup_get_help' => array(
		'icon' => '<span class="dashicons dashicons-sos"></span>',
		'title' => __('Help', 'wp-asset-clean-up'),
		'page' => 'wpassetcleanup_get_help'
	),
    // [wpacu_lite]
    'admin.php?page=wpassetcleanup_go_pro' => array(
	    'icon' => '<span class="dashicons dashicons-star-filled" style="color: inherit;"></span>',
	    'title' => __('Go Pro', 'wp-asset-clean-up'),
	    'page' => 'wpassetcleanup_go_pro',
        'target' => '_blank'
    )
	// [/wpacu_lite]
);

global $current_screen;

$wpacuCurrentPage = isset($data['page']) ? $data['page'] : false;

if (! $wpacuCurrentPage) {
	$wpacuCurrentPage = str_replace(
		array(str_replace(' ', '-', strtolower(WPACU_PLUGIN_TITLE)) . '_page_', 'toplevel_page_'),
		'',
		$current_screen->base
	);
}

$wpacuDefaultPageUrl = admin_url(Misc::arrayKeyFirst($wpacuTopAreaLinks));

$goBackToCurrentUrl = '&_wp_http_referer=' . urlencode( wp_unslash( $_SERVER['REQUEST_URI'] ) );
?>
<div id="wpacu-top-area">
    <div id="wpacu-logo-wrap">
        <a href="<?php echo $wpacuDefaultPageUrl; ?>">
            <img alt="" src="<?php echo WPACU_PLUGIN_URL; ?>/assets/images/asset-cleanup-logo.png" />
            <div class="wpacu-pro-sign wpacu-lite">LITE</div>
        </a>
    </div>

    <div id="wpacu-quick-actions">
        <span class="wpacu-actions-title"><?php _e('QUICK ACTIONS', 'wp-asset-clean-up'); ?>:</span>
        <a href="<?php echo wp_nonce_url(admin_url('admin-post.php?action=assetcleanup_clear_assets_cache' . $goBackToCurrentUrl),
		    'assetcleanup_clear_assets_cache'); ?>">
            <span class="dashicons dashicons-update"></span> <?php _e('Clear CSS/JS Files Cache', 'wp-asset-clean-up'); ?>
        </a>
    </div>

    <div class="wpacu-clearfix"></div>
</div>

<div class="wpacu-tabs wpacu-tabs-style-topline">
    <nav>
        <ul>
			<?php
            foreach ($wpacuTopAreaLinks as $wpacuLink => $wpacuInfo) {
                $wpacuIsCurrentPage            = ($wpacuCurrentPage  === $wpacuInfo['page']);
	            $wpacuIsAssetsManagerPageLink  = ($wpacuInfo['page'] === 'wpassetcleanup_assets_manager');
	            $wpacuIsPluginsManagerPageLink = ($wpacuInfo['page'] === 'wpassetcleanup_plugins_manager');
	            $wpacuIsBulkUnloadsPageLink    = ($wpacuInfo['page'] === 'wpassetcleanup_bulk_unloads');
                $wpacuIsLicensePageLink        = ($wpacuInfo['page'] === 'wpassetcleanup_license');
                ?>
                <li class="<?php if ($wpacuIsCurrentPage) { echo 'wpacu-tab-current'; } ?>">
                    <?php
                    if ($wpacuIsAssetsManagerPageLink) {
                        $totalUnloadedAssets = Misc::getTotalUnloadedAssets('per_page');

                        if ($totalUnloadedAssets === 0) {
	                        ?>
                            <span class="extra-info assets-unloaded-false"><span class="dashicons dashicons-warning"></span> No unloads per page</span>
	                        <?php
                        } elseif ($totalUnloadedAssets > 0) {
                            ?>
                            <span class="extra-info assets-unloaded-true"><strong><?php echo $totalUnloadedAssets; ?></strong> page unloads</span>
                            <?php
                        }
                    }

                    // [wpacu_lite]
                    if ($wpacuIsPluginsManagerPageLink) {
		                    ?>
                            <span class="extra-info assets-unloaded-false"><span class="dashicons dashicons-lock"></span> Premium Feature</span>
		                    <?php
                    }
                    // [/wpacu_lite]

                    if ($wpacuIsBulkUnloadsPageLink) {
                        $totalBulkUnloadRules = Misc::getTotalBulkUnloadsFor('all');

                        if ($totalBulkUnloadRules === 0) {
                            ?>
                            <span class="extra-info no-bulk-unloads assets-unloaded-false"><span class="dashicons dashicons-warning"></span> No bulk unloads</span>
	                        <?php
                        } elseif ($totalBulkUnloadRules > 0) {
                            ?>
                            <span class="extra-info has-bulk-unloads assets-unloaded-true"><strong><?php echo $totalBulkUnloadRules; ?></strong> bulk unloads</span>
	                        <?php
                        }
                    }
                    ?>
                    <a <?php if (isset($wpacuInfo['target']) && $wpacuInfo['target'] === '_blank') { ?> target="_blank" <?php } ?>
                            href="<?php echo admin_url($wpacuLink); ?>">
                        <?php echo $wpacuInfo['icon']; ?> <span><?php echo $wpacuInfo['title']; ?></span>
                    </a>
                </li>
			<?php } ?>
        </ul>
    </nav>
</div><!-- /tabs -->