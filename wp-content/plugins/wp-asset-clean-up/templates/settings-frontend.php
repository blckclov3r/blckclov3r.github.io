<?php
/*
 * No direct access to this file
 */
if (! isset($data)) {
    exit;
}
?>
<form action="#wpacu_wrap_assets" method="post">
    <div id="wpacu_wrap_assets">
        <?php
        if ($data['is_updateable']) {
	        if ($data['wpacu_page_just_updated']) {
		        $updateClass = new \WpAssetCleanUp\Update;
		        ?>
                <div class="wpacu-updated-frontend"><em>
                    <?php if (\WpAssetCleanUp\Misc::isHomePage()) {
                        echo $updateClass->updateDoneMsg['homepage'];
                    } else {
                        echo $updateClass->updateDoneMsg['page'];
                    } ?>
                    </em>
                </div>
		        <?php
	        }

            $wpacuMisc = new \WpAssetCleanUp\Misc();
            $activeCachePlugins = $wpacuMisc->getActiveCachePlugins();

            if (in_array('wp-rocket/wp-rocket.php', $activeCachePlugins)) {
                // Get WP Rocket Settings
                $wpRocketSettings = get_option('wp_rocket_settings');

                if (isset($wpRocketSettings['cache_logged_user']) && $wpRocketSettings['cache_logged_user'] == 1) {
	                ?>
                    <div class="wpacu-warning">
                        <strong><span class="dashicons dashicons-warning"></span> <?php _e('Important', 'wp-asset-clean-up'); ?>:</strong> <?php echo sprintf(__('You have enabled "%s" in WP Rocket\'s Cache area.', 'wp-asset-clean-up'), '<em>Enable caching for logged-in WordPress users</em>'); ?>
                        <?php _e('This could cause some issues with Asset CleanUp retrieving an outdated (cached) asset list below.', 'wp-asset-clean-up'); ?>
                        <?php _e('If you experience issues such as unsaved settings or viewing assets from plugins that are disabled, consider using Asset CleanUp only in the Dashboard area (option "Manage in the Dashboard" has to be enabled in plugin\'s settings).', 'wp-asset-clean-up'); ?>
                        <!--
                        -->
                    </div>
                    <div class="clearfix"></div>
	                <?php
                }
            }

            if (\WpAssetCleanUp\Misc::isPluginActive('perfmatters/perfmatters.php')) {
	            $perfmattersExtras = get_option('perfmatters_extras');

	            if (isset($perfmattersExtras['script_manager']) && (int)$perfmattersExtras['script_manager'] === 1) {
                ?>
                    <div class="wpacu-warning">
                        <span class="dashicons dashicons-warning" style="color: #cc0000;"></span> <?php _e('You\'re using the "Script Manager" option from "Extras" tab within Perfmatters plugin.', 'wp-asset-clean-up'); ?> <?php _e('You\'re already using Asset CleanUp to manage the CSS/JS.', 'wp-asset-clean-up'); ?> <strong><?php _e('Try not to use both plugins for the same feature as you could end up with broken functionality on either end.', 'wp-asset-clean-up'); ?></strong>
                    </div>
                <?php
                }
            }
        ?>
            <p><small><?php _e('This area is shown only for the admin users and if "Manage in the Front-end?" was selected in the plugin\'s settings. Handles such as \'admin-bar\' and \'wpassetcleanup-style\' are not included as they are irrelevant since they are used by the plugin for this area.', 'wp-asset-clean-up'); ?></small></p>

            <?php
            if ($data['is_woo_shop_page']) {
                ?>
                <p><strong><span style="color: #0f6cab;" class="dashicons dashicons-cart"></span> <?php _e('This a WooCommerce shop page (\'product\' type archive).', 'wp-asset-clean-up'); ?> <?php _e('Unloading CSS/JS will also take effect for the pagination/sorting pages', 'wp-asset-clean-up'); ?>(e.g. /2, /3, /?orderby=popularity etc.).</strong></p>
                <?php
            }

            if (isset($data['vars']['woo_url_not_match'])) {
                ?>
                <div class="wpacu_note wpacu_warning">
                    <p><?php _e('Although this page is detected as the home page, its URL is not the same as the one from "General Settings" &#187; "Site Address (URL)" and the WooCommerce plugin is not active anymore. This could be the "Shop" page that is no longer active.', 'wp-asset-clean-up'); ?></p>
                </div>
                <?php
            }

	        require_once 'meta-box-loaded.php';
        } else {
	        // Category, Tag, Search, 404, Author, Date pages (not supported by Lite version)
            $contentUnlockFeature = ' <p class="pro-page-unlock-notice">'.__('To unlock this feature, you can upgrade to the Pro version.', 'wp-asset-clean-up').'</p>';
	        $utm_medium = 'n_a'; // not available

            if (\WpAssetCleanUp\Main::isWpDefaultSearchPage()) {
                echo '<span class="dashicons dashicons-search"></span> '.__('This is a <strong>WordPress Search Page</strong> and managing CSS &amp; JS (unload, defer, async, etc.) for this page type can be done in Asset CleanUp Pro.', 'wp-asset-clean-up') . $contentUnlockFeature;
	            $utm_medium = 'search_page';
            } elseif (is_404()) {
                echo '<span class="dashicons dashicons-warning"></span> '.__('This is a <strong>404 (Not Found) Page</strong> and managing CSS &amp; JS (unload, defer, async, etc.) for this page type can be done in Asset CleanUp Pro.', 'wp-asset-clean-up') . $contentUnlockFeature;
	            $utm_medium = '404_not_found_page';
            } elseif (is_author()) {
	            echo '<span class="dashicons dashicons-admin-users"></span> '.__('This is an <strong>Author Page</strong> and managing CSS &amp; JS (unload, defer, async, etc.) for this page type can be done in Asset CleanUp Pro.', 'wp-asset-clean-up') . $contentUnlockFeature;
	            $utm_medium = 'author_page';
            } elseif (is_category()) {
	            echo '<span class="dashicons dashicons-category"></span> '.__('This is a <strong>Category (Taxonomy) Page</strong> and managing CSS &amp; JS (unload, defer, async, etc.) for this page type can be done in Asset CleanUp Pro.', 'wp-asset-clean-up') . $contentUnlockFeature;
	            $utm_medium = 'category_page';
            } elseif (function_exists('is_product_category') && is_product_category()) {
	            echo '<img src="'.WPACU_PLUGIN_URL . '/assets/icons/woocommerce-icon-logo.svg'.'" alt="" style="height: 40px !important; margin-top: -6px; margin-right: 5px;" align="middle" /> '.__('This is a <strong>WooCommerce Product Category (Taxonomy) Page</strong> and managing CSS &amp; JS (unload, defer, async, etc.) for this page type can be done in Asset CleanUp Pro.', 'wp-asset-clean-up') . $contentUnlockFeature;
	            $utm_medium = 'woo_product_category_page';
            } elseif (is_date()) {
	            echo '<span class="dashicons dashicons-calendar-alt"></span> '.__('This is a <strong>Date (Archive) Page</strong> and managing (unload, defer, async etc.) CSS &amp; JS for this page type can be done in Asset CleanUp Pro.', 'wp-asset-clean-up') . $contentUnlockFeature;
	            $utm_medium = 'date_page';
            } elseif (is_tag()) {
	            echo '<span class="dashicons dashicons-tag"></span> '.__('This is a <strong>Tag (Archive) Page</strong> and managing CSS &amp; JS (unload, defer, async, etc.) for this page type can be done in Asset CleanUp Pro.', 'wp-asset-clean-up') . $contentUnlockFeature;
	            $utm_medium = 'tag_page';
            } elseif (is_tax()) {
	            echo '<span class="dashicons dashicons-tag"></span> '.__('This is a <strong>Taxonomy Page</strong> and managing CSS &amp; JS (unload, defer, async, etc.) for this page type can be done in Asset CleanUp Pro.', 'wp-asset-clean-up') . $contentUnlockFeature;
	            $utm_medium = 'taxonomy_page';
            } elseif (\WpAssetCleanUp\Misc::isHomePage()) {
	            $utm_medium = 'home_page';
            }
        ?>
            <p>
                <a class="go-pro-button" target="_blank" href="<?php echo WPACU_PLUGIN_GO_PRO_URL; ?>?utm_source=front_end_manage&utm_medium=<?php echo $utm_medium; ?>">
                    <span class="dashicons dashicons-cart"></span>&nbsp; <?php _e('Upgrade to Asset CleanUp Pro', 'wp-asset-clean-up'); ?></a>
            </p>
        <?php
        }

        if ($data['is_updateable']) {
        ?>
        <div id="wpacu-update-front-settings-area">
            <button class="wpacu_update_btn"
                    type="submit"
                    name="submit"><span class="dashicons dashicons-update"></span> <?php esc_attr_e('UPDATE', 'wp-asset-clean-up'); ?></button>

            <div id="wpacu-updating-front-settings" style="display: none;">
                <img src="<?php echo admin_url(); ?>/images/spinner.gif" align="top" width="20" height="20" alt="" />
            </div>
        </div>

        <p align="right"><small><?php echo sprintf(
            __('Powered by %1$s, version %2$s', 'wp-asset-clean-up'),
            WPACU_PLUGIN_TITLE,
            WPACU_PLUGIN_VERSION);
        ?></small></p>
        <?php } ?>
    </div>

    <?php
    if ($data['is_updateable']) {
    ?>
    <?php wp_nonce_field($data['nonce_action'], $data['nonce_name']); ?>
    <input type="hidden" name="wpacu_update_asset_frontend" value="1" />
    <?php } ?>
</form>