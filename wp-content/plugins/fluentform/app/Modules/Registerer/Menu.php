<?php

namespace FluentForm\App\Modules\Registerer;

use FluentForm\App\Helpers\Helper;
use FluentForm\App\Modules\Activator;
use FluentForm\App\Modules\AddOnModule;
use FluentForm\App\Modules\DocumentationModule;
use FluentForm\Framework\Foundation\Application;
use FluentForm\View;

class Menu
{
    /**
     * @var \FluentForm\Framework\Foundation\Application
     */
    protected $app;

    /**
     * Menu constructor.
     *
     * @param \FluentForm\Framework\Foundation\Application $application
     */
    public function __construct(Application $application)
    {
        $this->app = $application;

        $this->app->addFilter('fluentform_form_settings_menu', array(
            $this, 'filterFormSettingsMenu'
        ), 10, 2);
    }

    public function reisterScripts()
    {
        if (!$this->isFluentPages()) {
            return;
        }

        $app = $this->app;

        wp_register_style(
            'fluentform_settings_global',
            $app->publicUrl("css/settings_global.css"),
            array(),
            FLUENTFORM_VERSION,
            'all'
        );

        wp_register_script(
            'clipboard',
            $app->publicUrl('libs/clipboard.min.js'),
            array(),
            false,
            true
        );

        wp_register_script(
            'copier',
            $app->publicUrl('js/copier.js'),
            array(),
            false,
            true
        );

        wp_register_script(
            'fluentform_form_settings',
            $app->publicUrl("js/form_settings_app.js"),
            array('jquery'),
            FLUENTFORM_VERSION,
            true
        );

        wp_register_script(
            'fluent_all_forms',
            $app->publicUrl("js/fluent-all-forms-admin.js"),
            array('jquery'),
            FLUENTFORM_VERSION,
            true
        );

        wp_register_style(
            'fluent_all_forms',
            $app->publicUrl("css/fluent-all-forms.css"),
            array(),
            FLUENTFORM_VERSION,
            'all'
        );

        wp_register_script(
            'fluentform_editor_script',
            $app->publicUrl("js/fluent-forms-editor.js"),
            array('jquery'),
            FLUENTFORM_VERSION,
            true
        );

        wp_register_style(
            'fluentform_editor_style',
            $app->publicUrl("css/fluent-forms-admin-sass.css"),
            [],
            FLUENTFORM_VERSION,
            'all'
        );

        wp_register_style(
            'fluentform_editor_sass',
            $app->publicUrl("css/fluent-forms-admin.css"),
            [],
            FLUENTFORM_VERSION,
            'all'
        );

        wp_register_script(
            'fluentform-transfer-js',
            $app->publicUrl("js/fluentform-transfer.js"),
            ['jquery'],
            FLUENTFORM_VERSION,
            true
        );

        wp_register_script(
            'fluentform-global-settings-js',
            $app->publicUrl("js/fluentform-global-settings.js"),
            ['jquery'],
            FLUENTFORM_VERSION,
            true
        );

        wp_register_script(
            'fluentform-modules',
            $app->publicUrl('js/modules.js'),
            ['jquery'],
            FLUENTFORM_VERSION,
            true
        );

        wp_register_script(
            'fluentform_form_entries',
            $app->publicUrl('js/form_entries.js'),
            array('jquery'),
            FLUENTFORM_VERSION,
            true
        );

        wp_register_script(
            'fluentform_all_entries',
            $app->publicUrl('js/all_entries.js'),
            array('jquery'),
            FLUENTFORM_VERSION,
            true
        );

        wp_register_style(
            'fluentform-add-ons',
            $app->publicUrl('css/add-ons.css'),
            [],
            FLUENTFORM_VERSION,
            'all'
        );

        wp_register_style(
            'fluentform_doc_style',
            $app->publicUrl('css/admin_docs.css'),
            [],
            FLUENTFORM_VERSION,
            'all'
        );

    }

    public function isFluentPages()
    {
        return Helper::isFluentAdminPage();
    }

    public function enqueuePageScripts()
    {
        if (!$this->isFluentPages()) {
            return;
        }

        $page = sanitize_text_field($_GET['page']);

        if ($page == 'fluent_forms' && isset($_GET['route']) && isset($_GET['form_id'])) {
            wp_enqueue_style('fluentform_settings_global');
            wp_enqueue_script('clipboard');
            wp_enqueue_script('copier');

            if ($_GET['route'] == 'settings') {
                if (function_exists('wp_enqueue_editor')) {
                    add_filter('user_can_richedit', function ($status) {
                        return true;
                    });

                    wp_enqueue_editor();
                    wp_enqueue_media();
                }

                wp_enqueue_script('fluentform_form_settings');

            } else if ($_GET['route'] == 'editor') {
                $this->enqueueEditorAssets();
            }
        } else if ($page == 'fluent_forms') {
            wp_enqueue_script('fluent_all_forms');
            wp_enqueue_style('fluent_all_forms');
        } else if ($page == 'fluent_forms_transfer') {
            wp_enqueue_style('fluentform_settings_global');
            wp_enqueue_script('fluentform-transfer-js');
        } else if (
            $page == 'fluent_forms_settings' ||
            $page == 'fluent_form_payment_entries' ||
            $page == 'fluent_forms_all_entries'
        ) {
            wp_enqueue_style('fluentform_settings_global');
        } else if ($page == 'fluent_form_add_ons') {
            wp_enqueue_style('fluentform-add-ons');
        } else if ($page == 'fluent_forms_docs') {
            wp_enqueue_style('fluentform_doc_style');
        }
    }

    /**
     * Register menu and sub-menus.
     */
    public function register()
    {
        $dashBoardCapability = apply_filters(
            'fluentform_dashboard_capability', 'fluentform_settings_manager'
        );

        $settingsCapability = apply_filters(
            'fluentform_settings_capability', 'fluentform_settings_manager'
        );

        if (!current_user_can($dashBoardCapability) && !current_user_can($settingsCapability)) {
            $customRoles = get_option('_fluentform_form_permission');

            if (is_string($customRoles)) {
                $customRoles = [];
            }

            if (!$customRoles) {
                return;
            }

            $hasAccess = false;
            foreach ($customRoles as $roleName) {
                if (current_user_can($roleName)) {
                    $hasAccess = true;
                    $dashBoardCapability = $roleName;
                    $settingsCapability = $roleName;
                }
            }

            if (!$hasAccess) {
                return;
            }
        }

        if (defined('FLUENTFORMPRO')) {
            $title = __('Fluent Forms Pro', 'fluentform');
        } else {
            $title = __('Fluent Forms', 'fluentform');
        }

        add_menu_page(
            $title,
            $title,
            $dashBoardCapability,
            'fluent_forms',
            array($this, 'renderFormAdminRoute'),
            $this->getMenuIcon(),
            25
        );


        add_submenu_page(
            'fluent_forms',
            __('All Forms', 'fluentform'),
            __('All Forms', 'fluentform'),
            $dashBoardCapability,
            'fluent_forms',
            array($this, 'renderFormAdminRoute')
        );

        if ($settingsCapability) {
            add_submenu_page(
                'fluent_forms',
                __('New Form', 'fluentform'),
                __('New Form', 'fluentform'),
                $settingsCapability,
                'fluent_forms#add=1',
                array($this, 'renderFormAdminRoute')
            );

            $entriesTitle =  __('Entries', 'fluentform');

            if(Helper::isFluentAdminPage()) {
                $entriesCount = wpFluent()->table('fluentform_submissions')
                    ->where('status', 'unread')
                    ->count();
                if($entriesCount) {
                    $entriesTitle .= ' <span class="ff_unread_count" style="background: #ca4a20;color: white;border-radius: 8px;padding: 1px 8px;">'.$entriesCount.'</span>';
                }
            }

            // Register entries intermediary page
            add_submenu_page(
                'fluent_forms',
                $entriesTitle,
                $entriesTitle,
                $settingsCapability,
                'fluent_forms_all_entries',
                array($this, 'renderAllEntriesAdminRoute')
            );

            if (apply_filters('fluentform_show_payment_entries', false)) {
                add_submenu_page(
                    'fluent_forms',
                    __('Payments', 'fluentform'),
                    __('Payments', 'fluentform'),
                    $settingsCapability,
                    'fluent_form_payment_entries',
                    array($this, 'renderPaymentEntries')
                );
            }

        }


        // Register Add-Ons
        add_submenu_page(
            'fluent_forms',
            __('Integration Modules', 'fluentform'),
            __('Integration Modules', 'fluentform'),
            $dashBoardCapability,
            'fluent_form_add_ons',
            array($this, 'renderAddOns')
        );

        if ($settingsCapability) {
            // Register global settings sub menu page.
            add_submenu_page(
                'fluent_forms',
                __('Global Settings', 'fluentform'),
                __('Global Settings', 'fluentform'),
                $settingsCapability,
                'fluent_forms_settings',
                array($this, 'renderGlobalSettings')
            );

            // Register import/export sub menu page.
            add_submenu_page(
                'fluent_forms',
                __('Tools', 'fluentform'),
                __('Tools', 'fluentform'),
                $settingsCapability,
                'fluent_forms_transfer',
                array($this, 'renderTransfer')
            );
        }


        // Register Documentation
        add_submenu_page(
            'fluent_forms',
            __('Get Help', 'fluentform'),
            __('Get Help', 'fluentform'),
            $dashBoardCapability,
            'fluent_forms_docs',
            array($this, 'renderDocs')
        );

        $this->commonAction();

    }

    private function getMenuIcon()
    {
        return 'data:image/svg+xml;base64,' . base64_encode('<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><defs><style>.cls-1{fill:#fff;}</style></defs><title>dashboard_icon</title><g id="Layer_2" data-name="Layer 2"><g id="Layer_1-2" data-name="Layer 1"><path class="cls-1" d="M15.57,0H4.43A4.43,4.43,0,0,0,0,4.43V15.57A4.43,4.43,0,0,0,4.43,20H15.57A4.43,4.43,0,0,0,20,15.57V4.43A4.43,4.43,0,0,0,15.57,0ZM12.82,14a2.36,2.36,0,0,1-1.66.68H6.5A2.31,2.31,0,0,1,7.18,13a2.36,2.36,0,0,1,1.66-.68l4.66,0A2.34,2.34,0,0,1,12.82,14Zm3.3-3.46a2.36,2.36,0,0,1-1.66.68H3.21a2.25,2.25,0,0,1,.68-1.64,2.36,2.36,0,0,1,1.66-.68H16.79A2.25,2.25,0,0,1,16.12,10.53Zm0-3.73a2.36,2.36,0,0,1-1.66.68H3.21a2.25,2.25,0,0,1,.68-1.64,2.36,2.36,0,0,1,1.66-.68H16.79A2.25,2.25,0,0,1,16.12,6.81Z"/></g></g></svg>');
    }

    public function renderFormAdminRoute()
    {
        if (isset($_GET['route']) && isset($_GET['form_id'])) {
            return $this->renderFormInnerPages();
        }

        $this->renderForms();
    }

    public function renderAllEntriesAdminRoute()
    {
        wp_enqueue_script('fluentform_all_entries');
        View::render('admin.all_entries', array());
    }

    private function renderFormInnerPages()
    {
        $form_id = intval($_GET['form_id']);

        $form = wpFluent()->table('fluentform_forms')->find($form_id);

        $formAdminMenus = array(
            'editor' => array(
                'slug' => 'editor',
                'title' => __('Editor', 'fluentform'),
                'url' => admin_url('admin.php?page=fluent_forms&form_id=' . $form_id . '&route=editor')
            ),
            'settings' => array(
                'slug' => 'settings',
                'hash' => 'basic_settings',
                'title' => __('Settings & Integrations', 'fluentform'),
                'sub_route' => 'form_settings',
                'url' => admin_url('admin.php?page=fluent_forms&form_id=' . $form_id . '&route=settings&sub_route=form_settings')
            ),
            'entries' => array(
                'slug' => 'entries',
                'hash' => '/',
                'title' => __('Entries', 'fluentform'),
                'url' => admin_url('admin.php?page=fluent_forms&form_id=' . $form_id . '&route=entries')
            )
        );

        $formAdminMenus = apply_filters('fluentform_form_admin_menu', $formAdminMenus, $form_id, $form);

        $form = wpFluent()->table('fluentform_forms')->find($form_id);

        if (!$form) {
            echo __("<h2>No form found</h2>", 'fluentform');
            return;
        }

        View::render('admin.form.form_wrapper', array(
            'route' => sanitize_text_field($_GET['route']),
            'form_id' => $form_id,
            'form' => $form,
            'menu_items' => $formAdminMenus
        ));
    }

    public function renderSettings($form_id)
    {
        $settingsMenus = array(
            'form_settings' => array(
                'title' => __('Form Settings', 'fluentform'),
                'slug' => 'form_settings',
                'hash' => 'basic_settings',
                'route' => '/'
            ),
            'email_notifications' => array(
                'title' => __('Email Notifications', 'fluentform'),
                'slug' => 'form_settings',
                'hash' => 'email_notifications',
                'route' => '/email-settings'
            ),
            'other_confirmations' => array(
                'title' => __('Other Confirmations', 'fluentform'),
                'slug' => 'form_settings',
                'hash' => 'other_confirmations',
                'route' => '/other-confirmations'
            ),
            'all_integrations' => array(
                'title' => __('Marketing & CRM Integrations', 'fluentform'),
                'slug' => 'form_settings',
                'route' => '/all-integrations'
            )
        );

        if (Helper::isSlackEnabled()) {
            $settingsMenus['slack'] = array(
                'title' => __('Slack', 'fluentform'),
                'slug' => 'form_settings',
                'hash' => 'slack',
                'route' => '/slack'
            );
        }

        $settingsMenus = apply_filters('fluentform_form_settings_menu', $settingsMenus, $form_id);

        $externalMenuItems = [];
        foreach ($settingsMenus as $key => $menu) {
            if (empty($menu['hash'])) {
                unset($settingsMenus[$key]);
                $externalMenuItems[$key] = $menu;
            }
        }

        $settingsMenus['custom_css_js'] = array(
            'title' => __('Custom CSS/JS', 'fluentform'),
            'slug' => 'form_settings',
            'hash' => 'custom_css_js',
            'route' => '/custom-css-js'
        );

        $settingsMenus = array_filter(array_merge($settingsMenus, $externalMenuItems));

        $currentRoute = sanitize_text_field($this->app->request->get('sub_route', ''));

        View::render('admin.form.settings_wrapper', array(
            'form_id' => $form_id,
            'settings_menus' => $settingsMenus,
            'current_sub_route' => $currentRoute
        ));
    }

    /**
     * Remove the inactive addOn menu items
     * @param string $addOn
     * @return boolean
     */
    public function filterFormSettingsMenu($settingsMenus, $form_id)
    {
        if (array_key_exists('mailchimp_integration', $settingsMenus)) {
            $option = (array)get_option('_fluentform_mailchimp_details');
            if (!isset($option['status']) || !$option['status']) {
                unset($settingsMenus['mailchimp_integration']);
            }
        }

        return $settingsMenus;
    }

    public function renderFormSettings($form_id)
    {
        wp_localize_script('fluentform_form_settings', 'FluentFormApp', array(
            'form_id' => $form_id,
            'plugin' => $this->app->getSlug(),
            'hasPro' => defined('FLUENTFORMPRO'),
            'hasPDF' => defined('FLUENTFORM_PDF_VERSION'),
            'ace_path_url' => $this->app->publicUrl('libs/ace')
        ));

        View::render('admin.form.settings', array(
            'form_id' => $form_id
        ));
    }

    public function renderForms()
    {
        if (!get_option('_fluentform_installed_version')) {
            (new Activator())->migrate();
        }

        $formsCount = wpFluent()->table('fluentform_forms')->count();

        wp_localize_script('fluent_all_forms', 'FluentFormApp', apply_filters('fluent_all_forms_vars', array(
            'plugin' => $this->app->getSlug(),
            'formsCount' => $formsCount,
            'hasPro' => defined('FLUENTFORMPRO'),
            'adminUrl' => admin_url('admin.php?page=fluent_forms'),
            'isDisableAnalytics' => apply_filters('fluentform-disabled_analytics', false)
        )));

        View::render('admin.all_forms', array());
    }

    public function renderEditor($form_id)
    {
        View::render('admin.form.editor', array(
            'plugin' => $this->app->getSlug(),
            'form_id' => $form_id
        ));
    }

    public function renderDocs()
    {
        echo (new DocumentationModule())->render();
    }

    public function renderAddOns()
    {
        echo (new AddOnModule())->render();
    }

    private function enqueueEditorAssets()
    {
        $formId = intval($_GET['form_id']);

        $form = wpFluent()->table('fluentform_forms')->find($formId);

        do_action('fluentform_loading_editor_assets', $form);

        wp_enqueue_style('fluentform_editor_sass');
        wp_enqueue_style('fluentform_editor_style');

        $pluginSlug = $this->app->getSlug();

        if (function_exists('wp_enqueue_editor')) {
            add_filter('user_can_richedit', '__return_true');
            wp_enqueue_editor();
            wp_enqueue_media();
        }

        wp_enqueue_script('fluentform_editor_script');

        $jsonData = $form->form_fields;

        if (!defined('FLUENTFORM_DISABLE_EDITOR_FILED_HOOOK')) {
            $formFields = $form->form_fields;

            if ($formFields) {
                $formFields = json_decode($formFields, true);

                foreach ($formFields['fields'] as $index => $formField) {
                    $formFields['fields'][$index] = apply_filters(
                        'fluentform_editor_init_element_' . $formField['element'], $formField, $form
                    );

                    if (!$formFields['fields'][$index]) {
                        unset($formFields['fields'][$index]);
                        continue;
                    }

                    if ($formField['element'] == 'container') {
                        $columns = $formField['columns'];
                        foreach ($columns as $columnIndex => $column) {
                            foreach ($column['fields'] as $fieldIndex => $columnField) {
                                $columns[$columnIndex]['fields'][$fieldIndex] = apply_filters(
                                    'fluentform_editor_init_element_' . $columnField['element'],
                                    $columnField, $form
                                );

                                if (!$columns[$columnIndex]['fields'][$fieldIndex]) {
                                    unset($columns[$columnIndex]['fields'][$fieldIndex]);
                                }
                            }
                        }

                        $formFields['fields'][$index]['columns'] = array_values($columns);
                    }
                }

                $formFields['fields'] = array_values($formFields['fields']);
                $formFields = json_encode($formFields, true);
            }


            $form->form_fields = $formFields;
        }

        $searchTags = $this->app->load(
            $this->app->appPath('Services/FormBuilder/ElementSearchTags.php')
        );

        $searchTags = apply_filters( 'fluent_editor_element_search_tags', $searchTags, $form );

        wp_localize_script('fluentform_editor_script', 'FluentFormApp', apply_filters('fluentform_editor_vars', array(
            'plugin' => $pluginSlug,
            'form_id' => $formId,
            'plugin_public_url' => $this->app->publicUrl(),
            'preview_url' => $this->getFormPreviewUrl($formId),
            'form' => $form,
            'hasPro' => defined('FLUENTFORMPRO'),
            'countries' => $this->app->load(
                $this->app->appPath('Services/FormBuilder/CountryNames.php')
            ),
            'element_customization_settings' => $this->app->load(
                $this->app->appPath('Services/FormBuilder/ElementCustomization.php')
            ),

            'validation_rule_settings' => $this->app->load(
                $this->app->appPath('Services/FormBuilder/ValidationRuleSettings.php')
            ),

            'element_search_tags' => $searchTags,

            'element_settings_placement' => $this->app->load(
                $this->app->appPath('Services/FormBuilder/ElementSettingsPlacement.php')
            ),
            'all_forms_url' => admin_url('admin.php?page=fluent_forms'),
            'has_payment_features' => !defined('FLUENTFORMPRO')
        )));
    }

    /**
     * Render global settings page.
     *
     * @throws \Exception
     */
    public function renderGlobalSettings()
    {
        // Fire an event letting others know the current component
        // that fluentform is rendering for the global settings
        // page. So that they can hook and load their custom
        // components on this page dynamically & easily.
        // N.B. native 'components' will always use
        // 'settings' as their current component.
        $currentComponent = apply_filters('fluentform_global_settings_current_component',
            $this->app->request->get('component', 'settings')
        );

        $components = apply_filters('fluentform_global_settings_components', []);

        $components['reCAPTCHA'] = [
            'hash' => 're_captcha',
            'title' => 'reCAPTCHA',
        ];

        View::render('admin.settings.index', [
            'components' => $components,
            'currentComponent' => $currentComponent
        ]);
    }

    public function renderTransfer()
    {
        $forms = wpFluent()->table('fluentform_forms')
            ->orderBy('id', 'desc')
            ->select(['id', 'title'])
            ->get();

        wp_localize_script('fluentform-transfer-js', 'FluentFormApp', [
            'plugin' => $this->app->getSlug(),
            'forms' => $forms,
            'hasPro' => defined('FLUENTFORMPRO'),
        ]);

        View::render('admin.transfer.index');
    }


    private function getFormPreviewUrl($form_id)
    {
        return site_url('?fluentform_pages=1&design_mode=1&preview_id=' . $form_id) . '#ff_preview';
    }

    public function addPreviewButton($formId)
    {
        echo '<a target="_blank" class="el-button el-button--small" href="' . $this->getFormPreviewUrl($formId) . '">Preview & Design</a>';
    }

    public function addCopyShortcodeButton($formId)
    {
        echo '<button style="background:#dedede;color:#545454;padding:5px;" title="Click to Copy" class="btn copy" data-clipboard-text=\'[fluentform id="' . $formId . '"]\'><i class="dashicons dashicons-admin-page" style="color:#eee;text-shadow:#000 -1px 1px 1px;"></i> [fluentform id="' . $formId . '"]</button>';
        return;
    }

    public function commonAction()
    {
        $fluentFormPages = array(
            'fluent_forms',
            'fluent_forms_transfer',
            'fluent_forms_settings',
            'fluent_form_add_ons',
            'fluent_forms_docs'
        );

        if (isset($_GET['page']) && in_array($_GET['page'], $fluentFormPages)) {
            // Let's deregister existing vuejs by other devs
            // Other devs should not regis
            add_action('admin_print_scripts', function () {
                wp_dequeue_script('vuejs');
                wp_dequeue_script('vue');
                wp_deregister_script('elementor-admin-app');
            });
        }
    }

    public function renderGlobalMenu()
    {
        $showPayment = false;
        if(defined('FLUENTFORMPRO')) {
            $showPayment = !get_option('__fluentform_payment_module_settings');
            if($showPayment) {
                $formCount = wpFluent()->table('fluentform_forms')
                                ->count();
                $showPayment = $formCount > 2;
            }
        }
        View::render('admin.global_menu', array(
            'show_payment' => $showPayment,
            'show_payment_entries' => apply_filters('fluentform_show_payment_entries', false)
        ));
    }

    public function renderPaymentEntries()
    {
        do_action('flunetform_render_payment_entries');
    }
}
