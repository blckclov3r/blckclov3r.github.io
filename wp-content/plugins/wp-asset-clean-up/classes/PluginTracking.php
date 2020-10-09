<?php
namespace WpAssetCleanUp;

/**
 * Class PluginTracking
 * @package WpAssetCleanUp
 */
class PluginTracking
{
	/**
	 * The data to send to the Asset CleanUp site
	 *
	 * @access private
	 */
	public $data;

	/**
	 * Initiate Settings Class
	 *
	 * @var Settings
	 */
	public $settings;

	/**
	 * @var bool
	 */
	public $showTrackingNotice = false;

	/**
	 * PluginTracking constructor.
	 */
	public function __construct()
	{
		$this->settings = new Settings();
	}

	/**
	 *
	 */
	public function init()
	{
		// Schedule
		add_action('wp',   array($this, 'schedule_events'));
		add_action('init', array($this, 'schedule_send'));

		// Triggers when Buttons from the Top Notice are clicked and page is reloaded (non-AJAX call)
        // This is a fallback in case there are JS errors and the AJAX call is not triggering
        if (isset($_GET['wpacu_is_page_reload']) && $_GET['wpacu_is_page_reload']) {
	        add_action('admin_init', array($this, 'opt_in_out'));
        }

		// Before "Settings" are saved in the database, right after form submit
        // Check "Allow Usage Tracking" value and take action if it's enabled
		add_action('wpacu_before_save_settings', array($this, 'check_for_settings_optin'));

        // Notice on the top screen within the Dashboard to get permission from the user to allow tracking
        add_action('admin_notices', array($this, 'admin_notice'));

        add_action('admin_head',   array($this, 'notice_styles'));
        add_action('admin_footer', array($this, 'notice_scripts'));

		// Close the notice when action is taken by AJAX call
		add_action('wp_ajax_' . WPACU_PLUGIN_ID . '_close_tracking_notice', array($this, 'ajaxCloseTrackingNoticeCallback'));
	}

	/**
     * @param bool $isAjaxCall
	 * @return bool|string|void
	 */
	public function opt_in_out($isAjaxCall = false)
	{
	    if (! array_key_exists('wpacu_action', $_REQUEST)) {
	        return false;
        }

		$response = '';
		$redirect = true;

	    if ($isAjaxCall) {
	        $redirect = false;
        }

	    $wpacuAction = isset($_REQUEST['wpacu_action']) ? $_REQUEST['wpacu_action'] : '';

	    if ($wpacuAction === 'wpacu_opt_into_tracking') {
		    $response = $this->check_for_optin();
	    }

	    if ($wpacuAction === 'wpacu_opt_out_of_tracking') {
            $response = $this->check_for_optout();

            if ($redirect) {
	            // Reload the same page without the Asset CleanUp query action
	            wp_redirect(remove_query_arg(array('wpacu_action', 'wpacu_is_page_reload')));
	            exit();
            }
        }

        return $response;
	}

	/**
	 * Trigger scheduling
	 */
	public function schedule_events()
	{
		$this->weekly_events();
	}

	/**
	 * Schedule weekly events
	 *
	 * @access private
	 * @since 1.6
	 * @return void
	 */
	private function weekly_events()
	{
		if (! wp_next_scheduled('wpacu_weekly_scheduled_events')) {
			wp_schedule_event(current_time('timestamp', true), 'weekly', 'wpacu_weekly_scheduled_events');
		}
	}

	/**
	 * Check if the user has opted into tracking
	 *
	 * @access private
	 * @return bool
	 */
	private function tracking_allowed()
	{
		$allowUsageTracking = $this->settings->getOption('allow_usage_tracking');
		return (bool) $allowUsageTracking;
	}
	/**
	 * Setup the data that is going to be tracked
	 *
	 * @access private
	 * @return void
	 */
	public function setup_data()
	{
		$data = array();

		// Retrieve current theme info
		$theme_data = wp_get_theme();
		$theme      = $theme_data->Name . ' ' . $theme_data->Version;

		$settingsClass = new Settings();

		$data['php_version']       = phpversion();
		$data['wpacu_version']     = WPACU_PLUGIN_VERSION;
		$data['wpacu_settings']    = $settingsClass->getAll();
		$data['wpacu_first_usage'] = get_option(WPACU_PLUGIN_ID.'_first_usage');
		$data['wpacu_review_info'] = get_option(WPACU_PLUGIN_ID.'_review_notice_status');
		$data['wp_version']        = get_bloginfo('version');
		$data['server']            = isset( $_SERVER['SERVER_SOFTWARE'] ) ? $_SERVER['SERVER_SOFTWARE'] : '';
		$data['multisite']         = is_multisite() ? 'Yes' : 'No';
		$data['theme']             = $theme;

		// Retrieve current plugin information
		$adminPluginFile = ABSPATH . '/wp-admin/includes/plugin.php';
		if (! function_exists( 'get_plugins') && is_file($adminPluginFile)) {
			include $adminPluginFile;
		}

		$plugins        = array_keys(get_plugins());
		$active_plugins = get_option('active_plugins', array());

		foreach ($plugins as $key => $plugin) {
			if (in_array($plugin, $active_plugins)) {
				// Remove active plugins from list so we can show active and inactive separately
				unset($plugins[$key]);
			}
		}

		$data['active_plugins']   = $active_plugins;
		$data['inactive_plugins'] = $plugins;
		$data['locale']           = get_locale();

		$this->data = $data;
	}

	/**
	 * Send the data to the Asset CleanUp server
	 *
	 * @access private
	 *
	 * @param  bool $override If we should override the tracking setting.
	 * @param  bool $ignore_last_checkin If we should ignore when the last check in was.
	 *
	 * @return bool
	 */
	public function send_checkin($override = false, $ignore_last_checkin = false)
	{
		// Allows us to stop the plugin's own site from checking in, and a filter for any related sites
		if (apply_filters('wpacu_disable_tracking_checkin', false)) {
			return false;
		}

		if (! $override && ! $this->tracking_allowed()) {
			return false;
		}

		// Send a maximum of once per week
		$last_send = $this->get_last_send();

		if (! $ignore_last_checkin && is_numeric($last_send) && $last_send > strtotime('-1 week')) {
			return 'Not Sent: Only Weekly';
		}

		$this->setup_data();

		$response = wp_remote_post('https://www.gabelivan.com/tracking/?wpacu_action=checkin', array(
			'method'      => 'POST',
			'timeout'     => 8,
			'redirection' => 5,
			'httpversion' => '1.1',
			'blocking'    => false,
			'body'        => $this->data,
			'user-agent'  => 'WPACU/' . WPACU_PLUGIN_VERSION . '; ' . get_bloginfo('url')
		));

		Misc::addUpdateOption(WPACU_PLUGIN_ID.'_tracking_last_send', time());

		return wp_remote_retrieve_body($response);
	}

	/**
	 * @param $savedSettings
	 *
	 * @return array
	 */
	public function check_for_settings_optin($savedSettings)
	{
		// Send an initial check in when "Settings" are saved
		if (isset($savedSettings['allow_usage_tracking']) && $savedSettings['allow_usage_tracking'] == 1) {
			$this->send_checkin( true );
		}

		return $savedSettings;
	}

	/**
     * Check for a new opt-in via the admin notice or after "Settings" is saved
     *
	 * @return bool|void
	 */
	public function check_for_optin()
	{
		if (! Menu::userCanManageAssets()) {
			return;
		}

		// Update the value in the "Settings" area
		$this->settings->updateOption('allow_usage_tracking', 1);

		// Send the tracking data
		$response = $this->send_checkin(true);

		// Mark the notice to be hidden
		Misc::addUpdateOption(WPACU_PLUGIN_ID . '_hide_tracking_notice', 1);

		return $response;
	}

	/**
	 * @return string
	 */
	public function check_for_optout()
	{
		if (! Menu::userCanManageAssets()) {
			return 'Unauthorized';
		}

		// Disable tracking option from "Settings" and mark the notice as hidden (to not show again)
		$this->settings->deleteOption('allow_usage_tracking');
		Misc::addUpdateOption(WPACU_PLUGIN_ID . '_hide_tracking_notice', 1);

		return 'success';
	}

	/**
	 * Get the last time a checkin was sent
	 *
	 * @access private
	 * @return false|string
	 */
	private function get_last_send()
	{
		return get_option(WPACU_PLUGIN_ID . '_tracking_last_send');
	}

	/**
	 * Schedule a weekly checkin
	 *
	 * We send once a week (while tracking is allowed) to check in, which can be
	 * used to determine active sites.
	 *
	 * @return void
	 */
	public function schedule_send()
	{
		if (Misc::doingCron()) {
			add_action('wpacu_weekly_scheduled_events', array($this, 'send_checkin'));
		}
	}

	/**
	 * Returns true or false for showing the top tracking notice
	 *
	 * @return bool
	 */
	public function show_tracking_notice()
	{
	    // On URL request (for debugging)
		if (array_key_exists('wpacu_show_tracking_notice', $_GET)) {
			return true;
		}

		// If another Asset CleanUp notice (e.g. for plugin review) is already shown
        // don't also show this one below/above it
		if (defined('WPACU_ADMIN_REVIEW_NOTICE_SHOWN')) {
		    return false;
        }

		$hide_notice = get_option(WPACU_PLUGIN_ID . '_hide_tracking_notice');

		if ($hide_notice) {
			return false;
		}

		if ($this->settings->getOption('allow_usage_tracking')) {
			return false;
		}

		if (! Menu::userCanManageAssets()) {
			return false;
		}

		if (false !== stripos(network_site_url('/'), 'dev') ||
			false !== stripos(network_site_url('/'), 'localhost') ||
			false !== strpos(network_site_url('/'), ':8888') // This is common with MAMP on OS X
		) {
			update_option(WPACU_PLUGIN_ID . '_tracking_notice', '1');
			return false;
		}

		return true;
	}

	/**
	 *
	 */
	public function ajaxCloseTrackingNoticeCallback()
	{
		check_ajax_referer('wpacu_plugin_tracking_nonce', 'wpacu_security');

		$action = isset($_POST['action']) ? $_POST['action'] : false;

		if ($action !== WPACU_PLUGIN_ID . '_close_tracking_notice' || ! $action) {
			exit('Invalid Action');
		}

		$wpacuAction = isset($_POST['wpacu_action']) ? $_POST['wpacu_action'] : false;

		if (! $wpacuAction) {
			exit('Invalid Asset CleanUp Action');
		}

		// Allow to Disallow (depending on the action chosen)
		$response = $this->opt_in_out(true);
		echo $response;

		exit();
	}

	/**
	 *
	 */
	public function notice_styles()
	{
		?>
        <style type="text/css">
            .wpacu-tracking-notice {
                border-left-color: #008f9c;
            }

            .wpacu-tracking-notice .wpacu-action-links {
                margin: 0 0 8px;
            }

            .wpacu-tracking-notice .wpacu-action-links ul {
                list-style: none;
                margin: 0;
            }

            .wpacu-tracking-notice .wpacu-action-links ul li.wpacu-optin {
                float: left;
                margin-right: 10px;
            }

            .wpacu-tracking-notice .wpacu-action-links ul li.wpacu-optout {
                float: left;
                margin-right: 5px;
            }

            .wpacu-tracking-notice .wpacu-action-links ul li.wpacu-more-info {
                float: left;
                margin-top: 5px;
                margin-left: 5px;
            }

            #wpacu-tracked-data-list {
                margin: 14px 0;
            }

            #wpacu-tracked-data-list .table-striped {
                border: none;
                border-spacing: 0;
            }

            #wpacu-tracked-data-list .wpacu_table_wrap .table.table-striped th,
            #wpacu-tracked-data-list .wpacu_table_wrap .table.table-striped td {
                padding: 0.62rem;
                vertical-align: top;
                border-top: 1px solid #eceeef;
            }

            #wpacu-tracked-data-list .table-striped tbody tr:nth-of-type(even) {
                background-color: rgba(0, 143, 156, 0.05);
            }

            #wpacu-tracked-data-list .table-striped tbody tr td:first-child {
                font-weight: bold;
            }
        </style>
		<?php
	}

	/**
	 * Display the admin notice to users that have not opted-in or out
	 *
	 * @return void
	 */
	public function admin_notice()
	{
	    if (! $this->show_tracking_notice()) {
		    return;
        }

		$this->setup_data();

		$optin_url  = add_query_arg(array('wpacu_action' => 'wpacu_opt_into_tracking', 'wpacu_is_page_reload' => true));
		$optout_url = add_query_arg(array('wpacu_action' => 'wpacu_opt_out_of_tracking', 'wpacu_is_page_reload' => true));

		?>
		<div class="wpacu-tracking-notice notice is-dismissible">
			<p><?php echo __('Allow Asset CleanUp to anonymously track plugin usage in order to help us make the plugin better? No sensitive or personal data is collected.', 'wp-asset-clean-up'); ?></p>
			<div class="wpacu-action-links">
				<ul>
					<li class="wpacu-optin">
                        <a href="<?php echo esc_url($optin_url); ?>"
                           data-wpacu-close-action="wpacu_opt_into_tracking"
                           class="wpacu-close-tracking-notice button-primary"><?php echo __('Allow, I\'m happy to help', 'easy-digital-downloads'); ?></a>
                    </li>
					<li class="wpacu-optout">
                        <a href="<?php echo esc_url($optout_url); ?>"
                           data-wpacu-close-action="wpacu_opt_out_of_tracking"
                           class="wpacu-close-tracking-notice button-secondary"><?php echo __('No, do not allow', 'easy-digital-downloads'); ?></a></li>
			        <li class="wpacu-more-info"><span style="color: #004567;" class="dashicons dashicons-info"></span> <a id="wpacu-show-tracked-data" href="#">What kind of data will be sent for the tracking?</a></li>
				</ul>
				<div style="clear: both;"></div>
			</div>

			<div style="display: none;" id="wpacu-tracked-data-list">
				<?php self::showSentInfoDataTable($this->data); ?>
			</div>
		</div>
		<?php
        if (! defined('WPACU_ADMIN_TRACKING_NOTICE_SHOWN')) {
	        define('WPACU_ADMIN_TRACKING_NOTICE_SHOWN', true);
        }

        // Only mark it as shown after it was printed
		$this->showTrackingNotice = true;
	}

	/**
	 *
	 */
	public function notice_scripts()
	{
	    if (! $this->showTrackingNotice) {
	        return;
        }
		?>
		<script type="text/javascript">
			jQuery(document).ready(function($) {
			    var $wpacuTrackedDataList = $('#wpacu-tracked-data-list');

			    // Tracking Info Link Clicked
			    $('#wpacu-show-tracked-data').on('click', function() {
					if ($wpacuTrackedDataList.is(':hidden')) {
                        $wpacuTrackedDataList.slideDown('fast');
					} else {
                        $wpacuTrackedDataList.slideUp('fast');
					}
			    });

			    // 'x' click from the top right of the notice
                $(document).on('click', '.wpacu-tracking-notice .notice-dismiss', function(event) {
                    $('[data-wpacu-close-action="wpacu_opt_out_of_tracking"]').trigger('click');
                });

                // button click
                $('.wpacu-close-tracking-notice').on('click', function(e) {
                    e.preventDefault();

                    $('.wpacu-tracking-notice').fadeOut('fast');

                    var wpacuXhr = new XMLHttpRequest(),
                        wpacuCloseAction = $(this).attr('data-wpacu-close-action'),
                        wpacuSecurityNonce = '<?php echo wp_create_nonce('wpacu_plugin_tracking_nonce'); ?>';

                    wpacuXhr.open('POST', '<?php echo admin_url('admin-ajax.php'); ?>');
                    wpacuXhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                    wpacuXhr.onload = function () {
                        if (wpacuXhr.status === 200) {
                            } else if (wpacuXhr.status !== 200) {
                            }
                    };

                    wpacuXhr.send(encodeURI('action=<?php echo WPACU_PLUGIN_ID . '_close_tracking_notice'; ?>&wpacu_action=' + wpacuCloseAction + '&wpacu_security='+ wpacuSecurityNonce));
                });
			});
		</script>
		<?php
	}

	/**
	 * @param $data
	 */
	public static function showSentInfoDataTable($data)
    {
        ?>
        <div class="wpacu_table_wrap">
            <table class="table table-striped">
                <tr>
                    <td style="width: 182px;">PHP Version:</td>
                    <td><?php echo $data['php_version']; ?></td>
                </tr>
                <tr>
                    <td>Asset CleanUp Info:</td>
                    <td>Version: <?php echo $data['wpacu_version']; ?>, Settings &amp; Usage Information</td>
                </tr>
                <tr>
                    <td>WordPress Version:</td>
                    <td><?php echo $data['wp_version']; ?></td>
                </tr>
                <tr>
                    <td>Server:</td>
                    <td><?php echo $data['server']; ?></td>
                </tr>
                <tr>
                    <td>Multisite:</td>
                    <td><?php echo $data['multisite']; ?></td>
                </tr>
                <tr>
                    <td>Theme:</td>
                    <td><?php echo $data['theme']; ?></td>
                </tr>
                <tr>
                    <td>Locale:</td>
                    <td><?php echo $data['locale']; ?></td>
                </tr>
                <tr>
                    <td>Plugins:</td>
                    <td>The list of active &amp; inactive plugins</td>
                </tr>
            </table>
        </div>
        <?php
    }
}
