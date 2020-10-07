<?php

namespace FluentForm\App\Modules\Form;

use FluentForm\App\Helpers\Helper;
use FluentForm\App\Modules\Acl\Acl;
use FluentForm\Framework\Foundation\Application;

class Form
{
    /**
     * @var \FluentForm\Framework\Request\Request $request
     */
    protected $request;

    /**
     * Set this value when we need predefined default settings.
     *
     * @var array $defaultSettings
     */
    protected $defaultSettings;


    /**
     * Set this value when we need predefined default notifications.
     *
     * @var array $defaultNotifications
     */
    protected $defaultNotifications;

    /**
     * Set this value when we need predefined form fields.
     *
     * @var array $formFields
     */
    protected $formFields;

    protected $metas = [];

    protected $formType = 'form';

    protected $hasPayment = 0;

    /**
     * Form constructor.
     *
     * @param \FluentForm\Framework\Foundation\Application $application
     *
     * @throws \Exception
     */
    public function __construct(Application $application)
    {
        $this->request = $application->request;
        $this->model = wpFluent()->table('fluentform_forms');
    }

    /**
     * Get all forms from database
     *
     * @return  void
     * @throws \Exception
     */
    public function index()
    {
        $search = $this->request->get('search');
        $status = $this->request->get('status');

        $shortColumn = $this->request->get('sort_column', 'id');
        $sortBy = $this->request->get('sort_by', 'DESC');

        $query = wpFluent()->table('fluentform_forms')
            ->orderBy($shortColumn, $sortBy);

        if ($status && $status != 'all') {
            $query->where('status', $status);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('id', 'LIKE', '%' . $search . '%');
                $q->orWhere('title', 'LIKE', '%' . $search . '%');
            });
        }

        $forms = $query->paginate();

        foreach ($forms['data'] as $form) {
            $form->preview_url = site_url('?fluentform_pages=1&preview_id=' . $form->id) . '#ff_preview';;
            $form->edit_url = $this->getAdminPermalink('editor', $form);
            $form->settings_url = $this->getSettingsUrl($form);
            $form->entries_url = $this->getAdminPermalink('entries', $form);
            $form->analytics_url = $this->getAdminPermalink('analytics', $form);
            $form->total_views = $this->getFormViewCount($form->id);
            $form->total_views = $this->getFormViewCount($form->id);
            $form->total_Submissions = $this->getSubmissionCount($form->id);
            $form->unread_count = $this->getUnreadCount($form->id);
            $form->conversion = $this->getConversionRate($form);
            unset($form->form_fields);
        }

        wp_send_json($forms, 200);
    }

    private function getFormViewCount($formId)
    {
        $hasCount = wpFluent()
            ->table('fluentform_form_meta')
            ->where('meta_key', '_total_views')
            ->where('form_id', $formId)
            ->first();

        if ($hasCount) {
            return intval($hasCount->value);
        }

        return 0;
    }

    private function getSubmissionCount($formID)
    {
        return wpFluent()
            ->table('fluentform_submissions')
            ->where('form_id', $formID)
            ->where('status', '!=', 'trashed')
            ->count();
    }

    private function getConversionRate($form)
    {
        if (!$form->total_Submissions)
            return 0;

        if (!$form->total_views)
            return 0;

        return ceil(($form->total_Submissions / $form->total_views) * 100);
    }

    /**
     * Create a form from backend/editor
     * @return void
     */
    public function store()
    {
        $type = $this->request->get('type', $this->formType);
        $title = $this->request->get('title', 'My New Form');
        $status = $this->request->get('status', 'published');
        $createdBy = get_current_user_id();

        $now = current_time('mysql');

        $insertData = [
            'title' => $title,
            'type' => $type,
            'status' => $status,
            'created_by' => $createdBy,
            'created_at' => $now,
            'updated_at' => $now
        ];

        if ($this->formFields) {
            $insertData['form_fields'] = $this->formFields;
        }

        if($this->hasPayment) {
            $insertData['has_payment'] = $this->hasPayment;
        }

        $formId = $this->model->insert($insertData);

        // Rename the form name  here
        wpFluent()->table('fluentform_forms')->where('id', $formId)->update(array(
            'title' => $title . ' (#' . $formId . ')'
        ));

        if($this->metas && is_array($this->metas)) {
            foreach ($this->metas as $meta) {
                $meta['value'] = trim(preg_replace('/\s+/', ' ', $meta['value']));

                wpFluent()->table('fluentform_form_meta')
                    ->insert(array(
                        'form_id' => $formId,
                        'meta_key' => $meta['meta_key'],
                        'value' => $meta['value']
                    ));
            }
        } else {
            // add default form settings now
            $defaultSettings = $this->defaultSettings ?: $this->getFormsDefaultSettings($formId);

            $defaultSettings = apply_filters('fluentform_create_default_settings', $defaultSettings);

            wpFluent()->table('fluentform_form_meta')
                ->insert(array(
                    'form_id' => $formId,
                    'meta_key' => 'formSettings',
                    'value' => json_encode($defaultSettings)
                ));

            if ($this->defaultNotifications) {
                wpFluent()->table('fluentform_form_meta')
                    ->insert(array(
                        'form_id' => $formId,
                        'meta_key' => 'notifications',
                        'value' => json_encode($this->defaultNotifications)
                    ));
            }
        }

        do_action('fluentform_inserted_new_form', $formId, $insertData);

        wp_send_json_success(array(
            'formId' => $formId,
            'redirect_url' => admin_url('admin.php?page=fluent_forms&form_id=' . $formId . '&route=editor'),
            'message' => __('Successfully created a form.', 'fluentform')
        ), 200);
    }

    public function getFormsDefaultSettings($formId = false)
    {
        $defaultSettings = array(
            'confirmation' => array(
                'redirectTo' => 'samePage',
                'messageToShow' => __('Thank you for your message. We will get in touch with you shortly', 'fluentform'),
                'customPage' => null,
                'samePageFormBehavior' => 'hide_form',
                'customUrl' => null
            ),
            'restrictions' => array(
                'limitNumberOfEntries' => array(
                    'enabled' => false,
                    'numberOfEntries' => null,
                    'period' => 'total',
                    'limitReachedMsg' => 'Maximum number of entries exceeded.'
                ),
                'scheduleForm' => array(
                    'enabled' => false,
                    'start' => null,
                    'end' => null,
                    'pendingMsg' => __("Form submission is not started yet.", 'fluentform'),
                    'expiredMsg' => __("Form submission is now closed.", 'fluentform')
                ),
                'requireLogin' => array(
                    'enabled' => false,
                    'requireLoginMsg' => 'You must be logged in to submit the form.',
                ),
                'denyEmptySubmission' => [
                    'enabled' => false,
                    'message' => __('Sorry, you cannot submit an empty form. Let\'s hear what you wanna say.', 'fluentform'),
                ]
            ),
            'layout' => array(
                'labelPlacement' => 'top',
                'helpMessagePlacement' => 'with_label',
                'errorMessagePlacement' => 'inline',
                'cssClassName' => '',
                'asteriskPlacement' => 'asterisk-right'
            ),
            'delete_entry_on_submission' => 'no'
        );

        if ($formId) {
            $value = $this->getMeta($formId, 'formSettings', true);
            if ($value) {
                $defaultSettings = wp_parse_args($value, $defaultSettings);
            }
        } else {
            $globalSettings = get_option('_fluentform_global_form_settings');
            if (isset($globalSettings['layout'])) {
                $defaultSettings['layout'] = $globalSettings['layout'];
            }
        }

        return $defaultSettings;
    }

    public function getAdvancedValidationSettings($formId)
    {
        $settings = [
            'status' => false,
            'type' => 'all',
            'conditions' => [
                [
                    'field' => '',
                    'operator' => '=',
                    'value' => ''
                ]
            ],
            'error_message' => '',
            'validation_type' => 'fail_on_condition_met'
        ];

        $metaSettings = $this->getMeta($formId, 'advancedValidationSettings', true);

        if($metaSettings && is_array($metaSettings)) {
            $settings = wp_parse_args($metaSettings, $settings);
        }

        return $settings;
    }

    public function getMeta($formId, $metaKey, $isJson = true)
    {
        $settingsMeta = wpFluent()->table('fluentform_form_meta')
            ->where('form_id', $formId)
            ->where('meta_key', $metaKey)
            ->first();
        if ($settingsMeta) {
            if($isJson) {
                return \json_decode($settingsMeta->value, true);
            } else {
                return $settingsMeta->value;
            }
        }
        return false;
    }

    public function updateMeta($formId, $metaKey, $metaValue)
    {
        $exist = wpFluent()->table('fluentform_form_meta')
            ->where('form_id', $formId)
            ->where('meta_key', $metaKey)
            ->first();

        if(is_array($metaValue) || is_object($metaValue)) {
            $metaValue = \json_encode($metaValue);
        }

        if($exist) {
            return wpFluent()->table('fluentform_form_meta')
                ->where('id', $exist->id)
                ->update([
                    'value' => $metaValue
                ]);
        }

        return wpFluent()->table('fluentform_form_meta')->insert([
            'form_id' => $formId,
            'meta_key' => $metaKey,
            'value' => $metaValue
        ]);
    }

    /**
     * Find/Read a from from the database
     * @return void
     */
    public function find()
    {
        $form = $this->fetchForm($this->request->get('formId'));
        wp_send_json(['form' => $form, 'metas' => []], 200);
    }

    /**
     * Fetch a from from the database
     * Note: required for ninja-tables
     * @return mixed
     */
    public function fetchForm($formId)
    {
        return $this->model->find($formId);
    }

    /**
     * Save/update a form from backend/editor
     * @return void
     * @throws \WpFluent\Exception
     */
    public function update()
    {
        $formId = $this->request->get('formId');
        $title = $this->request->get('title');
        $status = $this->request->get('status', 'published');

        $this->validate();

        $data = [
            'title' => $title,
            'status' => $status,
            'updated_at' => current_time('mysql')
        ];


        if ($formFields = $this->request->get('formFields')) {
            $formFields = apply_filters('fluentform_form_fields_update', $formFields, $formId);
            $data['form_fields'] = $formFields;
        }

        $this->model->where('id', $formId)->update($data);

        $form = $this->fetchForm($formId);

        if (FormFieldsParser::hasPaymentFields($form)) {
            $this->model->where('id', $formId)->update([
                'has_payment' => 1
            ]);
        } else if ($form->has_payment) {
            $this->model->where('id', $formId)->update([
                'has_payment' => 0
            ]);
        }

        wp_send_json([
            'message' => __('The form is successfully updated.', 'fluentform')
        ], 200);
    }

    /**
     * Delete a from from database
     * @return void
     * @throws \WpFluent\Exception
     */
    public function delete()
    {
        $formId = $this->request->get('formId');

        $this->model->where('id', $formId)->delete();


        // Now Let's delete associate items
        wpFluent()->table('fluentform_submissions')
            ->where('form_id', $formId)
            ->delete();

        wpFluent()->table('fluentform_submission_meta')
            ->where('form_id', $formId)
            ->delete();

        wpFluent()->table('fluentform_entry_details')
            ->where('form_id', $formId)
            ->delete();

        wpFluent()->table('fluentform_form_analytics')
            ->where('form_id', $formId)
            ->delete();

        wpFluent()->table('fluentform_logs')
            ->where('parent_source_id', $formId)
            ->whereIn('source_type', ['submission_item', 'form_item'])
            ->delete();

        ob_start();
        if (defined('FLUENTFORMPRO')) {
            try {
                wpFluent()->table('fluentform_order_items')
                    ->where('form_id', $formId)
                    ->delete();
                wpFluent()->table('fluentform_subscriptions')
                    ->where('form_id', $formId)
                    ->delete();
                wpFluent()->table('fluentform_transactions')
                    ->where('form_id', $formId)
                    ->delete();
            } catch (\Exception $exception) {

            }
        }
        $errors = ob_get_clean();

        wp_send_json([
            'message' => __('Successfully deleted the form.', 'fluentform'),
            'errors' => $errors
        ], 200);
    }

    /**
     * Duplicate a from
     * @return void
     * @throws \WpFluent\Exception
     */
    public function duplicate()
    {
        $formId = absint($this->request->get('formId'));
        $form = $this->model->where('id', $formId)->first();

        $data = array(
            'title' => $form->title,
            'status' => $form->status,
            'appearance_settings' => $form->appearance_settings,
            'form_fields' => $form->form_fields,
            'type' => $form->type,
            'has_payment' => $form->has_payment,
            'conditions' => $form->conditions,
            'created_by' => get_current_user_id(),
            'created_at' => current_time('mysql'),
            'updated_at' => current_time('mysql')
        );

        $newFormId = $this->model->insert($data);

        // Ranme the form name  here
        wpFluent()->table('fluentform_forms')
            ->where('id', $newFormId)
            ->update(array(
                'title' => $form->title . ' (#' . $newFormId . ')'
            ));

        $formMetas = wpFluent()->table('fluentform_form_meta')
            ->where('form_id', $formId)
            ->whereNot('meta_key', ['_total_views'])
            ->get();

        foreach ($formMetas as $meta) {
            $metaData = [
                'meta_key' => $meta->meta_key,
                'value' => $meta->value,
                'form_id' => $newFormId
            ];

            wpFluent()->table('fluentform_form_meta')->insert($metaData);
        }

        do_action('flentform_form_duplicated', $newFormId);

        wp_send_json([
            'message' => __('Form has been successfully duplicated.', 'fluentform'),
            'form_id' => $newFormId,
            'redirect' => admin_url('admin.php?page=fluent_forms&route=editor&form_id=' . $newFormId)
        ], 200);
    }

    /**
     * Validate a form only by form title
     * @return void
     */
    private function validate()
    {
        if (!$this->request->get('title')) {
            wp_send_json([
                'title' => 'The title field is required.'
            ], 422);
        }
    }

    private function getAdminPermalink($route, $form)
    {
        $baseUrl = admin_url('admin.php?page=fluent_forms');
        return $baseUrl . '&route=' . $route . '&form_id=' . $form->id;
    }

    private function getSettingsUrl($form)
    {
        $baseUrl = admin_url('admin.php?page=fluent_forms');
        return $baseUrl . '&form_id=' . $form->id . '&route=settings&sub_route=form_settings#basic_settings';
    }

    public function getAllForms()
    {
        $fields = $this->request->get('fields');

        if ($fields) {
            $forms = $this->model
                ->select($fields)
                ->orderBy('created_at', 'DESC')->get();
        } else {
            $forms = $this->model->orderBy('created_at', 'DESC')->get();
        }

        wp_send_json($forms, 200);
    }

    private function getUnreadCount($formId)
    {
        return wpFluent()->table('fluentform_submissions')
            ->where('status', 'unread')
            ->where('form_id', $formId)
            ->count();
    }
}
