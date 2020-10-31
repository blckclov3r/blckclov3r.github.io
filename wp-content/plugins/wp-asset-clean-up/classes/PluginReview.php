<?php
namespace WpAssetCleanUp;

/**
 * Class PluginReview
 * @package WpAssetCleanUp
 */
class PluginReview
{
	/**
	 * @var bool
	 */
	public $showReviewNotice = false;

	/**
	 * PluginReview constructor.
	 */
	public function __construct()
	{
		// Notice to rate plugin on WordPress.org based on specific conditions
		add_action('admin_notices', array($this, 'ratePluginNoticeOutput'));

		// Close the notice when action is taken by AJAX call
		add_action('wp_ajax_' . WPACU_PLUGIN_ID . '_close_review_notice', array($this, 'ajaxCloseReviewNoticeCallback'));

		// Close the notice when action is taken by page reload (e.g. rate it later or never show the notice again)
		add_action('admin_post_' . WPACU_PLUGIN_ID . '_close_review_notice', array($this, 'doCloseNotice'));

        // Notice styling
        add_action('admin_head', array($this, 'noticeStyles'));

        // Code related to the AJAX calls and closing the notice
        add_action('admin_footer', array($this, 'noticeScripts'));
	}

	/**
	 *
	 */
	public function ratePluginNoticeOutput()
	{
		// Criteria for showing up the review plugin notice
		if (! $this->showReviewNotice()) {
		    return;
		}

        // Show notice and delete the current status (if any)
        delete_option(WPACU_PLUGIN_ID .'_review_notice_status');

        $goBackToCurrentUrl = '&_wp_http_referer=' . urlencode( wp_unslash( $_SERVER['REQUEST_URI'] ) );

        $closeNoticeUrl = wp_nonce_url(
            admin_url('admin-post.php?action='.WPACU_PLUGIN_ID.'_close_review_notice&wpacu_close_reason=_wpacu_close_reason_' . $goBackToCurrentUrl),
	        WPACU_PLUGIN_ID . '_close_review_notice'
        );

        $closeNoticeMaybeLaterUrl  = str_replace('_wpacu_close_reason_', 'maybe_later',   $closeNoticeUrl);
        $closeNoticeNeverShowItUrl = str_replace('_wpacu_close_reason_', 'never_show_it', $closeNoticeUrl);
        ?>
        <div class="notice wpacu-notice-info is-dismissible wpacu-review-plugin-notice">
            <p><?php echo __('Hey, you have been using Asset CleanUp for some time and already unloaded useless CSS/JS which would give your website a higher page speed score and better user experience.',
                    'wp-asset-clean-up'); ?>
                <br/> <?php echo __('Could you please do me a BIG favor and give it a 5-star rating on WordPress to help us spread the word and boost our motivation?',
                    'wp-asset-clean-up'); ?></p>
            <p><strong><em>~ Gabriel Livan, Lead Developer</em></strong></p>
            <p>
                <a href="<?php echo Plugin::RATE_URL; ?>"
                   data-wpacu-close-action="never_show_it"
                   class="wpacu-primary-action wpacu-close-review-notice button-primary"
                   target="_blank"><?php esc_html_e('Ok, you deserve it', 'wp-asset-clean-up'); ?> :)</a>&nbsp;&nbsp;&nbsp;

                <a href="<?php echo $closeNoticeMaybeLaterUrl; ?>"
                   data-wpacu-close-action="maybe_later"
                   class="wpacu-close-review-notice"
                   rel="noopener noreferrer"><?php esc_html_e('Nope, maybe later', 'wp-asset-clean-up'); ?></a>&nbsp;&nbsp;&nbsp;

                <a href="<?php echo $closeNoticeNeverShowItUrl; ?>"
                   data-wpacu-close-action="never_show_it"
                   class="wpacu-close-review-notice"
                   rel="noopener noreferrer"><?php esc_html_e('Don\'t show this again', 'wp-asset-clean-up'); ?></a>
            </p>
        </div>
        <?php
        if (! defined('WPACU_ADMIN_REVIEW_NOTICE_SHOWN')) {
	        define('WPACU_ADMIN_REVIEW_NOTICE_SHOWN', true);
        }

		// Only mark it as shown after it was printed
		$this->showReviewNotice = true;
	}

	/**
	 *
	 */
	public function noticeStyles()
    {
        ?>
        <style type="text/css">
            .wpacu-review-plugin-notice {
                border-left: 4px solid #008f9c;
            }
        </style>
        <?php
    }

	/**
	 *
	 */
	public function noticeScripts()
    {
        if (! $this->showReviewNotice) {
            return;
        }
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function($) {
                $(document).on('click', '.wpacu-review-plugin-notice .notice-dismiss', function(event) {
                    $('[data-wpacu-close-action="maybe_later"]').trigger('click');
                });

                $('.wpacu-close-review-notice').on('click', function(e) {
                    $('.wpacu-review-plugin-notice').fadeOut('fast');

                    // If the primary action was taken, also perform the AJAX call to close the notice
                    if (! $(this).hasClass('wpacu-primary-action')) {
                        e.preventDefault();
                    }

                    var wpacuXhr = new XMLHttpRequest(),
                        wpacuCloseAction = $(this).attr('data-wpacu-close-action');

                    wpacuXhr.open('POST', '<?php echo admin_url('admin-ajax.php'); ?>');
                    wpacuXhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                    wpacuXhr.onload = function () {
                        if (wpacuXhr.status === 200) {
                            } else if (wpacuXhr.status !== 200) {
                            }
                    };

                    wpacuXhr.send(encodeURI('action=<?php echo WPACU_PLUGIN_ID . '_close_review_notice'; ?>&wpacu_close_reason=' + wpacuCloseAction));
                });
            });
        </script>
        <?php
    }

	/**
	 *
	 */
	public function ajaxCloseReviewNoticeCallback()
    {
        $action = isset($_POST['action']) ? $_POST['action'] : false;

	    if ($action !== WPACU_PLUGIN_ID . '_close_review_notice' || ! $action) {
		    exit('Invalid Action');
	    }

        $closeReason = isset($_POST['wpacu_close_reason']) ? $_POST['wpacu_close_reason'] : false;

	    if (! $closeReason) {
		    exit('Invalid Reason');
	    }

	    $this->doCloseNotice();
    }

	/**
     * Conditions for showing the notice (all should be met):
     * 1) Used the plugin at least for a week (or a few days if at least 30 assets were unloaded)
     * 2) At least 10 assets unloaded
     * 3) The WIKI was read or one of the minify/combine options were enabled
     *
	 * @return bool
	 */
	public function showReviewNotice()
    {
        // On URL request (for debugging)
        if (array_key_exists('wpacu_show_review_notice', $_GET)) {
            return true;
        }

	    // If another Asset CleanUp notice (e.g. for plugin tracking) is already shown
	    // don't also show this one below/above it
	    if (defined('WPACU_ADMIN_TRACKING_NOTICE_SHOWN')) {
		    return false;
	    }

	    $screen = get_current_screen();

	    $doNotTriggerOnScreens = array(
		    'options-general', 'tools', 'users', 'user', 'profile', 'plugins', 'plugin-editor', 'plugin-install'
	    );

	    if (isset($screen->base) && in_array($screen->base, $doNotTriggerOnScreens)) {
		    return false;
	    }

	    $noticeStatus = get_option(WPACU_PLUGIN_ID .'_review_notice_status');

	    if (isset($noticeStatus['status']) && $noticeStatus['status'] === 'never_show_it') {
		    return false; // Never show it (user has chosen or the primary button was clicked)
	    }

	    if (isset($noticeStatus['status'], $noticeStatus['updated_at']) && $noticeStatus['status'] === 'maybe_later') {
		    // Two weeks after the review notice is closed to show up later
		    $showNoticeAfterTimestamp = ($noticeStatus['updated_at'] + (DAY_IN_SECONDS * 14));

		    // If two weeks haven't passed since the user has chosen "Nope, maybe later" then do not show the notice yet
		    if (time() < $showNoticeAfterTimestamp) {
			    return false;
		    }
	    }

	    // Make sure that at least {$daysPassedAfterFirstUsage} days has passed after the first usage of the plugin was recorded
	    $firstUsageTimestamp = get_option(WPACU_PLUGIN_ID.'_first_usage');

	    if (! $firstUsageTimestamp) {
	        return false;
        }

	    $unloadedTotalAssets = Misc::getTotalUnloadedAssets();

	    // Show the notice after one week
	    $daysPassedAfterFirstUsage = 7;

	    // Unloaded at least thirty assets? Show the notice sooner
	    if ($unloadedTotalAssets >= 30) {
		    $daysPassedAfterFirstUsage = 4;
        }

	    if ( ! ($firstUsageTimestamp && (time() - $firstUsageTimestamp) >= ($daysPassedAfterFirstUsage * DAY_IN_SECONDS)) ) {
		    return false;
	    }

	    $settings = new Settings();
	    $allSettings = $settings->getAll();

	    return ( $unloadedTotalAssets >= 10
                 && ( $allSettings['wiki_read'] == 1
	                  || $allSettings['minify_loaded_css'] == 1 || $allSettings['combine_loaded_css'] == 1
	                  || $allSettings['minify_loaded_js']  == 1 || $allSettings['combine_loaded_js']  == 1 ) );
    }

	/**
     * Either via page reload or AJAX call
	 */
	public function doCloseNotice()
    {
        $doRedirect = array_key_exists('wpacu_close_reason', $_GET) && array_key_exists('wpacu_close_reason', $_GET) && ! defined('DOING_AJAX');
        $reason = isset($_REQUEST['wpacu_close_reason']) ? $_REQUEST['wpacu_close_reason'] : false;

        if (! $reason) {
            return;
        }

	    $performUpdate = false;

        if ($reason === 'never_show_it') {
            Misc::addUpdateOption(WPACU_PLUGIN_ID . '_review_notice_status', array('status' => 'never_show_it'));
	        $performUpdate = true;
        } elseif ($reason === 'maybe_later') {
            // Set the current timestamp and show it later (e.g. in 2 weeks from now)
	        Misc::addUpdateOption(WPACU_PLUGIN_ID . '_review_notice_status', array('status' => 'maybe_later', 'updated_at' => time()));
	        $performUpdate = true;
        }

        // AJAX call return
        if ($performUpdate && $doRedirect === false) {
            echo 'success';
            exit();
        }

        // The AJAX call should be successful
        // This redirect is made as a fallback in case the page is reloaded
	    if ( $doRedirect && wp_get_referer() ) {
		    wp_safe_redirect( wp_get_referer() );
		    exit();
	    }
    }
}
