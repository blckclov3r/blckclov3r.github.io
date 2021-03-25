<?php
/**
 * Plugin Name: Shift8 CDN 
 * Plugin URI: https://github.com/stardothosting/shift8-cdn
 * Description: Plugin that integrates a fully functional CDN service
 * Version: 1.55
 * Author: Shift8 Web 
 * Author URI: https://www.shift8web.ca
 * License: GPLv3
 */

require_once(plugin_dir_path(__FILE__).'shift8-cdn-rules.php' );
require_once(plugin_dir_path(__FILE__).'components/enqueuing.php' );
require_once(plugin_dir_path(__FILE__).'components/settings.php' );
require_once(plugin_dir_path(__FILE__).'components/functions.php' );
require_once(plugin_dir_path(__FILE__).'inc/shift8_cdn_rewrite.class.php' );
require_once(plugin_dir_path(__FILE__).'components/wp-cli.php' );

// Admin welcome page
if (!function_exists('shift8_main_page')) {
	function shift8_main_page() {
	?>
	<div class="wrap">
	<h2>Shift8 Plugins</h2>
	Shift8 is a Toronto based web development and design company. We specialize in Wordpress development and love to contribute back to the Wordpress community whenever we can! You can see more about us by visiting <a href="https://www.shift8web.ca" target="_new">our website</a>.
	</div>
	<?php
	}
}

// Admin settings page
function shift8_cdn_settings_page() {
?>
<div class="wrap">
<h2>Shift8 CDN Settings</h2>
<?php if (is_admin()) { 
$active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'core_settings';
$plugin_data = get_plugin_data( __FILE__ );
$plugin_name = $plugin_data['TextDomain'];
    ?>
<h2 class="nav-tab-wrapper">
    <a href="?page=<?php echo $plugin_name; ?>%2Fcomponents%2Fsettings.php%2Fcustom&tab=core_settings" class="nav-tab <?php echo $active_tab == 'core_settings' ? 'nav-tab-active' : ''; ?>">Core Settings</a>
    <a href="?page=<?php echo $plugin_name; ?>%2Fcomponents%2Fsettings.php%2Fcustom&tab=cdn_purge" class="nav-tab <?php echo $active_tab == 'cdn_purge' ? 'nav-tab-active' : ''; ?>">Purge Cache</a>
    <a href="?page=<?php echo $plugin_name; ?>%2Fcomponents%2Fsettings.php%2Fcustom&tab=cdn_options" class="nav-tab <?php echo $active_tab == 'cdn_options' ? 'nav-tab-active' : ''; ?>">CDN Settings</a>
    <a href="?page=<?php echo $plugin_name; ?>%2Fcomponents%2Fsettings.php%2Fcustom&tab=support_options" class="nav-tab <?php echo $active_tab == 'support_options' ? 'nav-tab-active' : ''; ?>">Support</a>
</h2>

<form method="post" action="options.php">
    <?php settings_fields( 'shift8-cdn-settings-group' ); ?>
    <?php do_settings_sections( 'shift8-cdn-settings-group' ); ?>
    <?php
	$locations = get_theme_mod( 'nav_menu_locations' );
	if (!empty($locations)) {
		foreach ($locations as $locationId => $menuValue) {
			if (has_nav_menu($locationId)) {
				$shift8_cdn_menu = $locationId;
			}
		}
	}
	?>
    <table class="form-table shift8-cdn-table">
    <tbody class="<?php echo $active_tab == 'core_settings' ? 'shift8-cdn-admin-tab-active' : 'shift8-cdn-admin-tab-inactive'; ?>">
	<tr valign="top">
    <th scope="row">Core Settings</th>
    <td><span id="shift8-cdn-notice">
    <?php 
    settings_errors('shift8_cdn_url');
    settings_errors('shift8_cdn_api');
    settings_errors('shift8_cdn_prefix');
    settings_errors('shift8_cdn_css');
    settings_errors('shift8_cdn_js');
    settings_errors('shift8_cdn_media');
    ?>
    </span></td>
	</tr>
    <tr valign="top">
    <th scope="row">Enable Shift8 CDN : </th>
    <td>
    <?php
    if (esc_attr( get_option('shift8_cdn_enabled') ) == 'on') {
        $enabled_checked = "checked";
    } else {
        $enabled_checked = "";
    }
    ?>
    <label class="switch">
    <input type="checkbox" name="shift8_cdn_enabled" <?php echo $enabled_checked; ?>>
    <div class="slider round"></div>
    </label>
    </td>
    </tr>
    <tr valign="top">
    <th scope="row">Shift8 CDN Account Status : </th>
    <td>
    <?php
    if (shift8_cdn_check_paid_transient() === S8CDN_SUFFIX_PAID) {
        $account_status = "Paid Plan";
    } else {
        $account_status = "Free Plan";
    }
    ?>
    <strong><?php echo $account_status; ?></strong>
    <div class="shift8-cdn-tooltip"><span class="dashicons dashicons-editor-help"></span>
        <span class="shift8-cdn-tooltiptext">Note : If you have upgraded your account and dont see this status change, click the "Check" button to manually synchronize.</span>
    </div>
    </td>
</tr>
	<tr valign="top">
    <th scope="row">Site URL : </th>
    <td><input type="text" name="shift8_cdn_url" size="34" value="<?php echo (empty(esc_attr(get_option('shift8_cdn_url'))) ? get_site_url() : esc_attr(get_option('shift8_cdn_url'))); ?>">
    <div class="shift8-cdn-tooltip"><span class="dashicons dashicons-editor-help"></span>
        <span class="shift8-cdn-tooltiptext">Note : Only enter site, no URI : https://www.domain.com . Also make sure the site url matches whats in our dashboard exactly.</span>
    </div>
    </td>
	</tr>
	<tr valign="top">
    <th scope="row">Shift8 CDN API Key : </th>
    <td><input type="text" id="shift8_cdn_api_field" name="shift8_cdn_api" size="34" value="<?php echo (empty(esc_attr(get_option('shift8_cdn_api'))) ? '' : esc_attr(get_option('shift8_cdn_api'))); ?>">
    <div class="shift8-cdn-tooltip"><span class="dashicons dashicons-editor-help"></span>
        <span class="shift8-cdn-tooltiptext">Keep this safe!</span>
    </div>
    </td>
	</tr>
	<tr valign="top">
    <th scope="row">Shift8 CDN Prefix : </th>
    <td><input type="text" id="shift8_cdn_prefix_field" name="shift8_cdn_prefix" size="34" value="<?php echo (empty(esc_attr(get_option('shift8_cdn_prefix'))) ? '' : esc_attr(get_option('shift8_cdn_prefix'))); ?>"></td>
	</tr>
    <?php if (!empty(esc_attr(get_option('shift8_cdn_prefix')))) { ?>
    <tr valign="top">
    <th scope="row">Test URL before enabling : </th>
    <?php
        if (!empty(esc_attr(get_option('shift8_cdn_prefix'))) && !empty(esc_attr(get_option('shift8_cdn_url')))) {
            if (shift8_cdn_check_paid_transient() === S8CDN_SUFFIX_PAID) {
                $shift8_test_url = 'https://' . esc_attr(get_option('shift8_cdn_prefix')) . S8CDN_SUFFIX_PAID . 
                    rtrim(parse_url(esc_attr(get_option('shift8_cdn_url'), PHP_URL_PATH))['path'], '/') . 
                    '/wp-content/plugins/shift8-cdn/test/test.png';
            } else if (shift8_cdn_check_paid_transient() === S8CDN_SUFFIX) {
                $shift8_test_url = 'https://' . esc_attr(get_option('shift8_cdn_prefix')) . S8CDN_SUFFIX . 
                    rtrim(parse_url(esc_attr(get_option('shift8_cdn_url'), PHP_URL_PATH))['path'], '/') . 
                    '/wp-content/plugins/shift8-cdn/test/test.png';
            } else {
                $shift8_test_url = 'https://' . esc_attr(get_option('shift8_cdn_prefix')) . S8CDN_SUFFIX_SECOND . 
                    rtrim(parse_url(esc_attr(get_option('shift8_cdn_url'), PHP_URL_PATH))['path'], '/') . 
                    '/wp-content/plugins/shift8-cdn/test/test.png';
            }
        } else { 
            $shift8_test_url = null;
        }
    ?>
    <td><a href="<?php echo $shift8_test_url; ?>" target="_new" >Click to open test URL in new tab</a>
    <div class="shift8-cdn-tooltip"><span class="dashicons dashicons-editor-help"></span>
        <span class="shift8-cdn-tooltiptext">Note : this will load a test image from the CDN. If it loads correctly then it should be working!</span>
    </div>
    </td>
    </tr>
    <?php } ?>
    <tr valign="top">
    <td width="226px"><div class="shift8-cdn-spinner"></div></td>
    <td>
    <?php if (empty(esc_attr(get_option('shift8_cdn_api')))) { ?>
    <div class="shift8-cdn-prereg-note">Note : You need to register in our dashboard first. Click "Register" below and add your site via our dashboard. Then copy the information back here and hit save.</div>
    <?php } ?>
    <ul class="shift8-cdn-controls">
    <li>
    <div class="shift8-cdn-button-container">
    <button onclick="window.open('<?php echo S8CDN_API . "/register"; ?>','_blank')" class="shift8-cdn-button shift8-cdn-button-register">Register</button>
    </div>
    </li>
    <?php if (!empty(esc_attr(get_option('shift8_cdn_api')))) { ?>
    <li>
    <div class="shift8-cdn-button-container">
    <a id="shift8-cdn-check" href="<?php echo wp_nonce_url( admin_url('admin-ajax.php?action=shift8_cdn_push'), 'process'); ?>"><button class="shift8-cdn-button shift8-cdn-button-check">Check</button></a>
    </div>
    </li>
    <?php } ?>
    </ul>
    <div class="shift8-cdn-response">
    </div>
    </td>
    </tr>
    </tbody>
    <!-- CDN PURGE TAB -->
    <tbody class="<?php echo $active_tab == 'cdn_purge' ? 'shift8-cdn-admin-tab-active' : 'shift8-cdn-admin-tab-inactive'; ?>">
    <tr valign="top">
    <th scope="row">Purge Cache</th>
    </tr>
    <tr valign="top">
    <td>This will submit a purge request across the entire network of endpoints. This can only be done once every few minutes. If you are troubleshooting, it is better to switch the CDN off in the Core Settings tab.</td>
    </tr>
    <tr valign="top">
    <td>
        <div class="shift8-cdn-button-container">
            <a id="shift8-cdn-purge" href="<?php echo wp_nonce_url( admin_url('admin-ajax.php?action=shift8_cdn_push'), 'process'); ?>"><button class="shift8-cdn-button shift8-cdn-button-register">Purge</button></a>
        </div>
    </td>
    </tr>
    <tr valign="top">
    <td width="226px"><div class="shift8-cdn-purge-spinner"></div></td>
    </tr>
    <tr>
    <td>
        <div class="shift8-cdn-purge-response"></div>
    </td>
    </tr>
    </tbody>
    <!-- CDN SETTINGS TAB -->
    <tbody class="<?php echo $active_tab == 'cdn_options' ? 'shift8-cdn-admin-tab-active' : 'shift8-cdn-admin-tab-inactive'; ?>">
    <tr valign="top">
    <th scope="row">CDN Settings</th>
    </tr>
    <tr valign="top">
    <th scope="row">Enable CDN for CSS files : </th>
    <td><input type="checkbox" name="shift8_cdn_css" size="34" <?php echo (empty(esc_attr(get_option('shift8_cdn_css'))) ? '' : 'checked'); ?>></td>
    </tr>
    <tr valign="top">
    <th scope="row">Enable CDN for JS files : </th>
    <td><input type="checkbox" name="shift8_cdn_js" size="34" <?php echo (empty(esc_attr(get_option('shift8_cdn_js'))) ? '' : 'checked'); ?>></td>
    </tr>
    <tr valign="top">
    <th scope="row">Enable CDN for Media files : </th>
    <td><input type="checkbox" name="shift8_cdn_media" size="34" <?php echo (empty(esc_attr(get_option('shift8_cdn_media'))) ? '' : 'checked'); ?>></td>
    </tr>
    <tr valign="top">
    <th scope="row">Exclude files from CDN</th>
    </tr>
    <tr valign="top">
    <th scope="row">Specify URL(s) of files that should not get served via CDN (one per line) : </th>
    </tr>
    <td>
    <textarea id="shift8-cdn-reject-files" rows="10" cols="100" name="shift8_cdn_reject_files" placeholder="wp-content/uploads/file.jpg\nsome/path/file(.*)"><?php echo esc_textarea(get_option('shift8_cdn_reject_files')); ?></textarea>
    </td>
    </tbody>
    <!-- SUPPORT TAB -->
    <tbody class="<?php echo $active_tab == 'support_options' ? 'shift8-cdn-admin-tab-active' : 'shift8-cdn-admin-tab-inactive'; ?>">
    <tr valign="top">
    <th scope="row">Support</th>
    </tr>
    <tr valign="top">
    <td style="width:500px;">If you are experiencing difficulties, you can receive support if you Visit the <a href="https://wordpress.org/support/plugin/shift8-cdn/" target="_new">Shift8 CDN Wordpress support page</a> and post your question there.<Br /><Br />
    <strong>Debug Info</strong><br /><br />
    Providing the debug information below to the Shift8 CDN support team may be helpful in them assisting in diagnosing any issues you may be having. <br /><br />
    <div class="shift8-cdn-button-container">
    </div><button class="shift8-cdn-button shift8-cdn-button-copyclipboard" id="button1" onclick="Shift8CDNCopyToClipboard('shift8cdn-debug')">Copy info below to clipboard</button>
    <br /><br />
    <script type="text/javascript">
        function showDetails(id) {
            document.getElementById(id).style.display = 'block';
        }
        function hideDetails(id) {
            document.getElementById(id).style.display = 'none';
        }
    </script>
    <div class="wrap">
        <div class="postbox" id="shift8cdn-debug">
            <h2><?php _e('Shift8 CDN Debug Info'); ?></h2>
            <p><?php echo shift8_cdn_debug_version_check(); ?></p>
        </div>
        <!--<div class="postbox" id="shift8cdn-debugphp">
            <h2><?php _e('Shift8 CDN PHP Debug Info'); ?></h2>
            <p><?php _e('For more detailed PHP server related information, click the Show Details link below.'); ?></p>
            <a href="#" onclick="showDetails('details'); return false;"><?php _e('Show Details'); ?></a>
            <a href="#" onclick="hideDetails('details'); return false;"><?php _e('Hide Details'); ?></a>
            <span id="details" style="display: none;"><?php echo shift8_cdn_debug_get_php_info(); ?></span>
        </div>-->
    </div>
    </td>
    </tr>
    </tbody>
    </table>
    <?php 
    if ($active_tab !== 'support_options' && $active_tab !== 'cdn_purge') {
        submit_button(); 
    }
    ?>
    </form>
</div>
<?php 
	} // is_admin
}
