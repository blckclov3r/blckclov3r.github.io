<?php
namespace FluentForm\App\Modules;

use FluentForm\App\Databases\Migrations\FormLogs;
use FluentForm\App\Databases\Migrations\FormSubmissionDetails;
use FluentForm\App\Modules\Acl\Acl;
use FluentForm\App\Databases\DatabaseMigrator;

class Activator
{
	/**
	 * This method will be called on plugin activation
	 * @return void
	 */
	public function handleActivation($network_wide)
	{
        global $wpdb;
        if ($network_wide) {
            // Retrieve all site IDs from this network (WordPress >= 4.6 provides easy to use functions for that).
            if (function_exists('get_sites') && function_exists('get_current_network_id')) {
                $site_ids = get_sites(array('fields' => 'ids', 'network_id' => get_current_network_id()));
            } else {
                $site_ids = $wpdb->get_col("SELECT blog_id FROM $wpdb->blogs WHERE site_id = $wpdb->siteid;");
            }
            // Install the plugin for all these sites.
            foreach ($site_ids as $site_id) {
                switch_to_blog($site_id);
                $this->migrate();
                restore_current_blog();
            }
        } else {
            $this->migrate();
        }

        self::setCronSchedule();
	}

	public function migrate()
    {
        // We are going to migrate all the database tables here.
        DatabaseMigrator::run();
        // Assign fluentform permission set.
        Acl::setPermissions();
        $this->setDefaultGlobalSettings();
        $this->setCurrentVersion();
        $this->maybeMigrateDB();
    }

    public function maybeMigrateDB()
    {
        // First we have to check if global modules is set or not
        $this->migrateGlobalAddOns();

        if(!get_option('fluentform_entry_details_migrated')) {
            FormSubmissionDetails::migrate();
        }

        if(!get_option('fluentform_db_fluentform_logs_added')) {
            FormLogs::migrate();
        }
    }

    public function migrateGlobalAddOns()
    {
        $globalModules = get_option('fluentform_global_modules_status');
        // Modules already set
        if(is_array($globalModules)) {
            return;
        }

        $possibleGloablModules = [
            'mailchimp' => '_fluentform_mailchimp_details',
            'activecampaign' => '_fluentform_activecampaign_settings',
            'campaign_monitor' => '_fluentform_campaignmonitor_settings',
            'constatantcontact' => '_fluentform_constantcontact_settings',
            'getresponse' => '_fluentform_getresponse_settings',
            'icontact' => '_fluentform_icontact_settings'
        ];

        $possibleMetaModules = [
            'webhook' => 'fluentform_webhook_feed',
            'zapier' => 'fluentform_zapier_feed',
            'slack' => 'slack'
        ];

        $moduleStatuses = [];
        foreach ($possibleGloablModules as $moduleName => $settingsKey)
        {
            if(get_option($settingsKey)) {
                $moduleStatuses[$moduleName] = 'yes';
            } else {
                $moduleStatuses[$moduleName] = 'no';
            }
        }

        global $wpdb;

        foreach ($possibleMetaModules as $moduleName => $metaName) {
            $row = $wpdb->get_row("SELECT * FROM `{$wpdb->prefix}fluentform_form_meta` WHERE `meta_key` = '{$metaName}' LIMIT 1");

            if ($row) {
                $moduleStatuses[$moduleName] = 'yes';
            } else {
                $moduleStatuses[$moduleName] = 'no';
            }
        }

        update_option('fluentform_global_modules_status', $moduleStatuses, 'no');
    }

	private function setDefaultGlobalSettings()
	{
		if (!get_option('_fluentform_global_form_settings')) {
			update_option('__fluentform_global_form_settings', array(
				'layout' => array(
					'labelPlacement' => 'top',
					'asteriskPlacement' => 'asterisk-right',
					'helpMessagePlacement' => 'with_label',
					'errorMessagePlacement' => 'inline',
					'cssClassName' => ''
				)
			), 'no');
		}
	}

	private function setCurrentVersion()
	{
		update_option('_fluentform_installed_version', FLUENTFORM_VERSION, 'no');
	}

	public static function setCronSchedule()
    {

        add_filter('cron_schedules', function ($schedules) {
            $schedules['ff_every_five_minutes'] = array(
                'interval' => 300,
                'display'  => esc_html__('Every 5 Minutes (FluentForm)', 'fluentform'),
            );
            return $schedules;
        }, 10, 1);

        $hookName = 'fluentform_do_scheduled_tasks';
        if (!wp_next_scheduled($hookName)) {
            wp_schedule_event(time(), 'ff_every_five_minutes', $hookName);
        }

        $emailReportHookName = 'fluentform_do_email_report_scheduled_tasks';
        if (!wp_next_scheduled($emailReportHookName)) {
            wp_schedule_event(time(), 'daily', $emailReportHookName);
        }

    }
}
