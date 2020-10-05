<?php

if ( apply_filters( 'ninja_forms_disable_marketing', false ) ) return array();

return apply_filters( 'ninja_forms_available_actions', array(

    'mailchimp'             => array(
        'group'             => 'marketing',
        'name'              => 'mailchimp',
        'nicename'          => 'MailChimp',
        'link'              => 'https://ninjaforms.com/extensions/mail-chimp/?utm_source=Ninja+Forms+Plugin&utm_medium=Emails+and+Actions&utm_campaign=Builder+Actions+Drawer&utm_content=MailChimp',
        'plugin_path'       => 'ninja-forms-mail-chimp/ninja-forms-mail-chimp.php',
        'modal_content'     => '<div class="available-action-modal">
                                    <img src="' . Ninja_Forms::$url . 'assets/img/add-ons/mail-chimp.png"/>
                                    <p>In order to use this action, you need MailChimp for Ninja Forms.</p>
                                    <p>Bring new life to your lists with upgraded Mailchimp signup forms for WordPress! Easy to build and customize with no code required.</p>
                                    <div class="actions">
                                        <a target="_blank" href="https://ninjaforms.com/extensions/mail-chimp/?utm_source=Ninja+Forms+Plugin&utm_medium=Emails+and+Actions&utm_campaign=Builder+Actions+Drawer&utm_content=MailChimp" title="MailChimp" class="primary nf-button">Learn More</a>
                                    </div>
                                </div>',
    ),

    'zapier'                => array(
        'group'             => 'misc',
        'name'              => 'zapier',
        'nicename'          => 'Zapier',
        'link'              => 'https://ninjaforms.com/extensions/zapier/?utm_source=Ninja+Forms+Plugin&utm_medium=Emails+and+Actions&utm_campaign=Builder+Actions+Drawer&utm_content=Zapier',
        'plugin_path'       => 'ninja-forms-zapier/ninja-forms-zapier.php',
        'modal_content'     => '<div class="available-action-modal">
                                <img src="' . Ninja_Forms::$url . 'assets/img/add-ons/zapier.png"/>
                                <p>In order to use this action, you need Zapier for Ninja Forms.</p>
                                <p>Don\'t see an add-on integration for a service you love? Don\'t worry! Connect WordPress to more than 1,500 different services through Zapier, no code required!</p>
                                <div class="actions">
                                    <a target="_blank" href="https://ninjaforms.com/extensions/zapier/?utm_source=Ninja+Forms+Plugin&utm_medium=Emails+and+Actions&utm_campaign=Builder+Actions+Drawer&utm_content=Zapier" title="Zapier" class="primary nf-button">Learn More</a>
                                </div>
                            </div>',
    ),

    'file_uploads'          => array(
        'group'             => 'popular',
        'name'              => 'file_uploads',
        'nicename'          => 'File Uploads',
        'link'              => 'https://ninjaforms.com/extensions/file-uploads/?utm_source=Ninja+Forms+Plugin&utm_medium=Emails+and+Actions&utm_campaign=Builder+Actions+Drawer&utm_content=File+Uploads',
        'plugin_path'       => 'ninja-forms-uploads/file-uploads.php',
        'modal_content'     => '<div class="available-action-modal">
                                <img src="' . Ninja_Forms::$url . 'assets/img/add-ons/file-uploads.png"/>
                                <p>In order to use this action, you need File Uploads for Ninja Forms.</p>
                                <p>Add file upload fields to save files to your server or send them to <strong>Dropbox</strong> or <strong>Amazon S3</strong> securely. The ability to collect data from your visitors is an important tool for any site owner. Sometimes the information you need comes in the form of images, videos, or documents like PDFs, Word or Excel files, etc.</p>
                                <div class="actions">
                                    <a target="_blank" href="https://ninjaforms.com/extensions/file-uploads/?utm_source=Ninja+Forms+Plugin&utm_medium=Emails+and+Actions&utm_campaign=Builder+Actions+Drawer&utm_content=File+Uploads" title="File Uploads" class="primary nf-button">Learn More</a>
                                </div>
                            </div>',
    ),

    'createposts'           => array(
        'group'             => 'management',
        'name'              => 'createposts',
        'nicename'         => 'Create Post',
        'link'              => 'https://ninjaforms.com/extensions/front-end-posting/?utm_source=Ninja+Forms+Plugin&utm_medium=Emails+and+Actions&utm_campaign=Builder+Actions+Drawer&utm_content=Front-End+Posting',
        'plugin_path'       => 'ninja-forms-post-creation/ninja-forms-post-creation.php',
        'modal_content'     => '<div class="available-action-modal">
                                <img src="' . Ninja_Forms::$url . 'assets/img/add-ons/front-end-posting.png"/>
                                <p>In order to use this action, you need Front-End Posting for Ninja Forms.</p>
                                <p>Front-End Posting gives you the power of the WordPress post editor on any publicly viewable page you choose. You can allow users the ability to create content and have it assigned to any publicly available built-in or custom post type, taxonomy, and custom meta field.</p>
                                <div class="actions">
                                    <a target="_blank" href="https://ninjaforms.com/extensions/front-end-posting/?utm_source=Ninja+Forms+Plugin&utm_medium=Emails+and+Actions&utm_campaign=Builder+Actions+Drawer&utm_content=Front-End+Posting" title="Front-End Posting" class="primary nf-button">Learn More</a>
                                </div>
                            </div>',
    ),

    'trello'                => array(
        'group'             => 'workflow',
        'name'              => 'trello',
        'nicename'          => 'Trello',
        'link'              => 'https://ninjaforms.com/extensions/trello/?utm_source=Ninja+Forms+Plugin&utm_medium=Emails+and+Actions&utm_campaign=Builder+Actions+Drawer&utm_content=Trello',
        'plugin_path'       => 'ninja-forms-trello/ninja-forms-trello.php',
        'modal_content'     => '<div class="available-action-modal">
                                <img src="' . Ninja_Forms::$url . 'assets/img/add-ons/trello.png"/>
                                <p>In order to use this action, you need Trello for Ninja Forms.</p>
                                <p>Create a new Trello card with data from any WordPress form submission. Map fields to card details, assign members and labels, upload images, embed links.</p>
                                <div class="actions">
                                    <a target="_blank" href="https://ninjaforms.com/extensions/trello/?utm_source=Ninja+Forms+Plugin&utm_medium=Emails+and+Actions&utm_campaign=Builder+Actions+Drawer&utm_content=Trello" title="Trello" class="primary nf-button">Learn More</a>
                                </div>
                            </div>',
    ),

    'slack'                 => array(
        'group'             => 'notifications',
        'name'              => 'slack',
        'nicename'          => 'Slack',
        'link'              => 'https://ninjaforms.com/extensions/slack/?utm_source=Ninja+Forms+Plugin&utm_medium=Emails+and+Actions&utm_campaign=Builder+Actions+Drawer&utm_content=Slack',
        'plugin_path'       => 'ninja-forms-slack/ninja-forms-slack.php',
        'modal_content'     => '<div class="available-action-modal">
                                    <img src="' . Ninja_Forms::$url . 'assets/img/add-ons/slack.png"/>
                                    <p>In order to use this action, you need Slack for Ninja Forms.</p>
                                    <p>Get realtime Slack notifications in the workspace and channel of your choice with any new WordPress form submission. @Mention any team member!</p>
                                    <div class="actions">
                                        <a target="_blank" href="https://ninjaforms.com/extensions/slack/?utm_source=Ninja+Forms+Plugin&utm_medium=Emails+and+Actions&utm_campaign=Builder+Actions+Drawer&utm_content=Slack" title="Slack" class="primary nf-button">Learn More</a>
                                    </div>
                                </div>',
    ),

    'webhooks'              => array(
        'group'             => 'misc',
        'name'              => 'webhooks',
        'nicename'          => 'WebHooks',
        'link'              => 'https://ninjaforms.com/extensions/webhooks/?utm_source=Ninja+Forms+Plugin&utm_medium=Emails+and+Actions&utm_campaign=Builder+Actions+Drawer&utm_content=WebHooks',
        'plugin_path'       => 'ninja-forms-webhooks/ninja-forms-webhooks.php',
        'modal_content'     => '<div class="available-action-modal">
                                <img src="' . Ninja_Forms::$url . 'assets/img/add-ons/webhooks.png"/>
                                <p>In order to use this action, you need WebHooks for Ninja Forms.</p>
                                <p>Can\'t find a WordPress integration for the service you love? Send WordPress forms data to any external URL using a simple GET or POST request!</p>
                                <div class="actions">
                                    <a target="_blank" href="https://ninjaforms.com/extensions/webhooks/?utm_source=Ninja+Forms+Plugin&utm_medium=Emails+and+Actions&utm_campaign=Builder+Actions+Drawer&utm_content=WebHooks" title="WebHooks" class="primary nf-button">Learn More</a>
                                </div>
                            </div>',
    ),

    'campaignmonitor'       => array(
        'group'             => 'marketing',
        'name'              => 'campaignmonitor',
        'nicename'          => 'Campaign Monitor',
        'link'              => 'https://ninjaforms.com/extensions/campaign-monitor/?utm_source=Ninja+Forms+Plugin&utm_medium=Emails+and+Actions&utm_campaign=Builder+Actions+Drawer&utm_content=Campaign+Monitor',
        'plugin_path'       => 'ninja-forms-campaign-monitor/ninja-forms-campaign-monitor.php',
        'modal_content'     => '<div class="available-action-modal">
                                    <img src="' . Ninja_Forms::$url . 'assets/img/add-ons/campaign-monitor.png"/>
                                    <p>In order to use this action, you need Campaign Monitor for Ninja Forms.</p>
                                    <p>The Campaign Monitor extension allows you to quickly create newsletter signup forms for your Campaign Monitor account. Create an unlimited number of subscribe forms and begin growing your mailing lists.</p>
                                    <div class="actions">
                                        <a target="_blank" href="https://ninjaforms.com/extensions/campaign-monitor/?utm_source=Ninja+Forms+Plugin&utm_medium=Emails+and+Actions&utm_campaign=Builder+Actions+Drawer&utm_content=Campaign+Monitor" title="Campaign Monitor" class="primary nf-button">Learn More</a>
                                    </div>
                                </div>',
    ),

    'constantcontact'       => array(
        'group'             => 'marketing',
        'name'              => 'constantcontact',
        'nicename'          => 'Constant Contact',
        'link'              => 'https://ninjaforms.com/extensions/constant-contact/?utm_source=Ninja+Forms+Plugin&utm_medium=Emails+and+Actions&utm_campaign=Builder+Actions+Drawer&utm_content=Constant+Contact',
        'plugin_path'       => 'ninja-forms-constant-contact/ninja-forms-constant-contact.php',
        'modal_content'     => '<div class="available-action-modal">
                                    <img src="' . Ninja_Forms::$url . 'assets/img/add-ons/constant-contact.png"/>
                                    <p>In order to use this action, you need Constant Contact for Ninja Forms.</p>
                                    <p>The Constant Contact extension allows you to quickly create newsletter signup forms for your Constant Contact account. Create an unlimited number of subscribe forms and grow your mailing lists.</p>
                                    <div class="actions">
                                        <a target="_blank" href="https://ninjaforms.com/extensions/constant-contact/?utm_source=Ninja+Forms+Plugin&utm_medium=Emails+and+Actions&utm_campaign=Builder+Actions+Drawer&utm_content=Constant+Contact" title="Constant Contact" class="primary nf-button">Learn More</a>
                                    </div>
                                </div>',
    ),

    'aweber'                => array(
        'group'             => 'marketing',
        'name'              => 'aweber',
        'nicename'          => 'AWeber',
        'link'              => 'https://ninjaforms.com/extensions/aweber/?utm_source=Ninja+Forms+Plugin&utm_medium=Emails+and+Actions&utm_campaign=Builder+Actions+Drawer&utm_content=AWeber',
        'plugin_path'       => 'ninja-forms-aweber/ninja-forms-aweber.php',
        'modal_content'     => '<div class="available-action-modal">
                                    <img src="' . Ninja_Forms::$url . 'assets/img/add-ons/aweber.png"/>
                                    <p>In order to use this action, you need AWeber for Ninja Forms.</p>
                                    <p>The AWeber extension allows you to quickly create newsletter signup forms for your AWeber account. Create an unlimited number of subscribe forms and grow your mailing lists.</p>
                                    <div class="actions">
                                        <a target="_blank" href="https://ninjaforms.com/extensions/aweber/?utm_source=Ninja+Forms+Plugin&utm_medium=Emails+and+Actions&utm_campaign=Builder+Actions+Drawer&utm_content=AWeber" title="AWeber" class="primary nf-button">Learn More</a>
                                    </div>
                                </div>',
    ),

    'emma'                  => array(
        'group'             => 'marketing',
        'name'              => 'emma',
        'nicename'         => 'Emma',
        'link'              => 'https://ninjaforms.com/extensions/emma/?utm_source=Ninja+Forms+Plugin&utm_medium=Emails+and+Actions&utm_campaign=Builder+Actions+Drawer&utm_content=Emma',
        'plugin_path'       => '',
        'modal_content'     => '<div class="available-action-modal">
                                <img src="' . Ninja_Forms::$url . 'assets/img/add-ons/emma.png"/>
                                <p>In order to use this action, you need Emma for Ninja Forms.</p>
                                <p>The Emma extension allows you to quickly create newsletter signup forms for your Emma account. Create an unlimited number of subscribe forms and grow your mailing lists.</p>
                                <div class="actions">
                                    <a target="_blank" href="https://ninjaforms.com/extensions/emma/?utm_source=Ninja+Forms+Plugin&utm_medium=Emails+and+Actions&utm_campaign=Builder+Actions+Drawer&utm_content=Emma" title="Emma" class="primary nf-button">Learn More</a>
                                </div>
                            </div>',
    ),

    'webmerge'              => array(
        'group'             => 'workflow',
        'name'              => 'webmerge',
        'nicename'          => 'WebMerge',
        'link'              => 'https://ninjaforms.com/extensions/webmerge/?utm_source=Ninja+Forms+Plugin&utm_medium=Emails+and+Actions&utm_campaign=Builder+Actions+Drawer&utm_content=WebMerge',
        'plugin_path'       => 'ninja-forms-webmerge/ninja-forms-webmerge.php',
        'modal_content'     => '<div class="available-action-modal">
                                <img src="' . Ninja_Forms::$url . 'assets/img/add-ons/webmerge.png"/>
                                <p>In order to use this action, you need WebMerge for Ninja Forms.</p>
                                <p>With the WebMerge extension for Ninja Forms, you can send form data directly to the awesome <a href="https://webmerge.me" target="_blank">webmerge.me</a> service. This lets you easily populate PDFs, Excel spreadsheets, Word docs, or PowerPoint presentations.</p>
                                <div class="actions">
                                    <a target="_blank" href="https://ninjaforms.com/extensions/webmerge/?utm_source=Ninja+Forms+Plugin&utm_medium=Emails+and+Actions&utm_campaign=Builder+Actions+Drawer&utm_content=WebMerge" title="WebMerge" class="primary nf-button">Learn More</a>
                                </div>
                            </div>',
    ),

    'twilio_sms'            => array(
        'group'             => 'notifications',
        'name'              => 'twilio_sms',
        'nicename'          => 'Twilio SMS',
        'link'              => 'https://ninjaforms.com/extensions/twilio-sms/?utm_source=Ninja+Forms+Plugin&utm_medium=Emails+and+Actions&utm_campaign=Builder+Actions+Drawer&utm_content=Twilio+SMS',
        'plugin_path'       => 'ninja-forms-twilio/ninja-forms-twilio.php',
        'modal_content'     => '<div class="available-action-modal">
                                <img src="' . Ninja_Forms::$url . 'assets/img/add-ons/twilio-sms.png"/>
                                <p>In order to use this action, you need Twilio SMS for Ninja Forms.</p>
                                <p>Get instant SMS notifications with every new WordPress form submission. Respond to leads faster and make more personal connections!</p>
                                <div class="actions">
                                    <a target="_blank" href="https://ninjaforms.com/extensions/twilio-sms/?utm_source=Ninja+Forms+Plugin&utm_medium=Emails+and+Actions&utm_campaign=Builder+Actions+Drawer&utm_content=Twilio+SMS" title="Twilio SMS" class="primary nf-button">Learn More</a>
                                </div>
                            </div>',
    ),

    'clicksend_sms'            => array(
        'group'             => 'notifications',
        'name'              => 'clicksend_sms',
        'nicename'          => 'ClickSend SMS',
        'link'              => 'https://ninjaforms.com/extensions/clicksend-sms/?utm_source=Ninja+Forms+Plugin&utm_medium=Emails+and+Actions&utm_campaign=Builder+Actions+Drawer&utm_content=ClickSend+SMS',
        'plugin_path'       => 'ninja-forms-clicksend/ninja-forms-clicksend.php',
        'modal_content'     => '<div class="available-action-modal">
                                <img src="' . Ninja_Forms::$url . 'assets/img/add-ons/clicksend-sms.png"/>
                                <p>In order to use this action, you need ClickSend SMS for Ninja Forms.</p>
                                <p>Get instant SMS notifications with every new WordPress form submission. Respond to leads faster and make more personal connections!</p>
                                <div class="actions">
                                    <a target="_blank" href="https://ninjaforms.com/extensions/clicksend-sms/?utm_source=Ninja+Forms+Plugin&utm_medium=Emails+and+Actions&utm_campaign=Builder+Actions+Drawer&utm_content=ClickSend+SMS" title="ClickSend SMS" class="primary nf-button">Learn More</a>
                                </div>
                            </div>',
    ),

    'email_octopus'         => array(
        'group'             => 'marketing',
        'name'              => 'email_octopus',
        'nicename'          => 'EmailOctopus',
        'link'              => 'https://ninjaforms.com/extensions/emailoctopus/?utm_source=Ninja+Forms+Plugin&utm_medium=Emails+and+Actions&utm_campaign=Builder+Actions+Drawer&utm_content=EmailOctopus',
        'plugin_path'       => 'ninja-forms-emailoctopus/ninja-forms-emailoctopus.php',
        'modal_content'     => '<div class="available-action-modal">
                                <img src="' . Ninja_Forms::$url . 'assets/img/add-ons/emailoctopus.png"/>
                                <p>In order to use this action, you need EmailOctopus for Ninja Forms.</p>
                                <p>Automation, integration, analytics… EmailOctopus is the email management solution that fills every need, and it’s now available for WordPress! More than a simple email marketing tool, discover a new way to manage every aspect of your email strategy from marketing campaigns to automated employee onboarding. <strong>Save time, save money, be an email rockstar!</strong></p>
                                <div class="actions">
                                    <a target="_blank" href="https://ninjaforms.com/extensions/emailoctopus/?utm_source=Ninja+Forms+Plugin&utm_medium=Emails+and+Actions&utm_campaign=Builder+Actions+Drawer&utm_content=EmailOctopus" title="EmailOctopus" class="primary nf-button">Learn More</a>
                                </div>
                            </div>',
    ),

    'stripe'                => array(
        'group'             => 'payments',
        'name'              => 'stripe',
        'nicename'          => 'Stripe',
        'link'              => 'https://ninjaforms.com/extensions/stripe/?utm_source=Ninja+Forms+Plugin&utm_medium=Emails+and+Actions&utm_campaign=Builder+Actions+Drawer&utm_content=Stripe',
        'plugin_path'       => 'ninja-forms-stripe/ninja-forms-stripe.php',
        'modal_content'     => '<div class="available-action-modal">
                                <img src="' . Ninja_Forms::$url . 'assets/img/add-ons/stripe.png"/>
                                <p>In order to use this action, you need Stripe for Ninja Forms.</p>
                                <p>Did you know you can accept credit card payments or donations from any form? Single payments, subscriptions, and more!</p>
                                <div class="actions">
                                    <a target="_blank" href="https://ninjaforms.com/extensions/stripe/?utm_source=Ninja+Forms+Plugin&utm_medium=Emails+and+Actions&utm_campaign=Builder+Actions+Drawer&utm_content=Stripe" title="Stripe" class="primary nf-button">Learn More</a>
                                </div>
                            </div>',
    ),

    'paypal'                => array(
        'group'             => 'payments',
        'name'              => 'paypal',
        'nicename'          => 'PayPal Express',
        'link'              => 'https://ninjaforms.com/extensions/paypal-express/?utm_source=Ninja+Forms+Plugin&utm_medium=Emails+and+Actions&utm_campaign=Builder+Actions+Drawer&utm_content=PayPal+Express',
        'plugin_path'       => 'ninja-forms-paypal-express/ninja-forms-paypal-express.php',
        'modal_content'     => '<div class="available-action-modal">
                                <img src="' . Ninja_Forms::$url . 'assets/img/add-ons/paypal-express.png"/>
                                <p>In order to use this action, you need PayPal Express for Ninja Forms.</p>
                                <p>Did you know you can accept PayPal payments or donations from any form? Connect any form completely and securely to your PayPal Express account!</p>
                                <div class="actions">
                                    <a target="_blank" href="https://ninjaforms.com/extensions/paypal-express/?utm_source=Ninja+Forms+Plugin&utm_medium=Emails+and+Actions&utm_campaign=Builder+Actions+Drawer&utm_content=PayPal+Express" title="PayPal Express" class="primary nf-button">Learn More</a>
                                </div>
                            </div>',
    ),

    'elavon'                => array(
        'group'             => 'payments',
        'name'              => 'elavon',
        'nicename'          => 'Elavon',
        'link'              => 'https://ninjaforms.com/extensions/elavon/?utm_source=Ninja+Forms+Plugin&utm_medium=Emails+and+Actions&utm_campaign=Builder+Actions+Drawer&utm_content=Elavon',
        'plugin_path'       => 'ninja-forms-elavon-payment-gateway/ninja-forms-elavon-payment-gateway.php',
        'modal_content'     => '<div class="available-action-modal">
                                <img src="' . Ninja_Forms::$url . 'assets/img/add-ons/elavon.png"/>
                                <p>In order to use this action, you need Elavon for Ninja Forms.</p>
                                <p>Did you know you can accept credit card payments or donations from any form? Connect any form completely and securely to your Elavon account!</p>
                                <div class="actions">
                                    <a target="_blank" href="https://ninjaforms.com/extensions/elavon/?utm_source=Ninja+Forms+Plugin&utm_medium=Emails+and+Actions&utm_campaign=Builder+Actions+Drawer&utm_content=Elavon" title="Elavon" class="primary nf-button">Learn More</a>
                                </div>
                            </div>',
    ),

    'pipelinedeals-crm'      => array(
        'group'             => 'marketing',
        'name'              => 'pipelinedeals-crm',
        'nicename'          => 'PipelineDeals CRM',
        'link'              => 'https://ninjaforms.com/extensions/pipelinedeals-crm/?utm_source=Ninja+Forms+Plugin&utm_medium=Emails+and+Actions&utm_campaign=Builder+Actions+Drawer&utm_content=PipelineDeals+CRM',
        'plugin_path'       => 'ninja-forms-pipeline-deals-crm/ninja-forms-pipeline-crm.php',
        'modal_content'     => '<div class="available-action-modal">
                                <img src="' . Ninja_Forms::$url . 'assets/img/add-ons/pipelinedeals-crm.png"/>
                                <p>In order to use this action, you need PipelineDeals CRM for Ninja Forms.</p>
                                <p>Sick of transferring customer data manually between your website and PipelineDeals? Tired of maintaining an unstable custom integration? You can now connect your website directly to PipelineDeals through Ninja Forms with this fully automated solution!</p>
                                <div class="actions">
                                    <a target="_blank" href="https://ninjaforms.com/extensions/pipelinedeals-crm/?utm_source=Ninja+Forms+Plugin&utm_medium=Emails+and+Actions&utm_campaign=Builder+Actions+Drawer&utm_content=PipelineDeals+CRM" title="PipelineDeals CRM" class="primary nf-button">Learn More</a>
                                </div>
                            </div>',
    ),

    'active-campaign'       => array(
        'group'             => 'marketing',
        'name'              => 'active-campaign',
        'nicename'          => 'Active Campaign',
        'link'              => 'https://ninjaforms.com/extensions/active-campaign/?utm_source=Ninja+Forms+Plugin&utm_medium=Emails+and+Actions&utm_campaign=Builder+Actions+Drawer&utm_content=Active+Campaign',
        'plugin_path'       => 'ninja-forms-active-campaign/ninja-forms-active-campaign.php',
        'modal_content'     => '<div class="available-action-modal">
                                <img src="' . Ninja_Forms::$url . 'assets/img/add-ons/active-campaign.png"/>
                                <p>In order to use this action, you need Active Campaign for Ninja Forms.</p>
                                <p>Active Campaign shines for sales teams that require insightful, intelligent customer relationship management. There’s no reason your integration should deliver any less. Integrate today and combine effortless, intelligent marketing automation with your WordPress website!</p>
                                <div class="actions">
                                    <a target="_blank" href="https://ninjaforms.com/extensions/active-campaign/?utm_source=Ninja+Forms+Plugin&utm_medium=Emails+and+Actions&utm_campaign=Builder+Actions+Drawer&utm_content=Active+Campaign" title="Active Campaign" class="primary nf-button">Learn More</a>
                                </div>
                            </div>',
    ),

    'insightly-crm'         => array(
        'group'             => 'marketing',
        'name'              => 'insightly-crm',
        'nicename'          => 'Insightly CRM',
        'link'              => 'https://ninjaforms.com/extensions/insightly-crm/?utm_source=Ninja+Forms+Plugin&utm_medium=Emails+and+Actions&utm_campaign=Builder+Actions+Drawer&utm_content=Insightly+CRM',
        'plugin_path'       => 'ninja-forms-insightly-crm/ninja-forms-insightly-crm.php',
        'modal_content'     => '<div class="available-action-modal">
                                <img src="' . Ninja_Forms::$url . 'assets/img/add-ons/insightly-crm.png"/>
                                <p>In order to use this action, you need Insightly CRM for Ninja Forms.</p>
                                <p>The Insightly CRM extension for Ninja Forms enables you to send your form submission data directly into your Insightly CRM account, managing your sales leads effectively.</p>
                                <div class="actions">
                                    <a target="_blank" href="https://ninjaforms.com/extensions/insightly-crm/?utm_source=Ninja+Forms+Plugin&utm_medium=Emails+and+Actions&utm_campaign=Builder+Actions+Drawer&utm_content=Insightly+CRM" title="Insightly CRM" class="primary nf-button">Learn More</a>
                                </div>
                            </div>',
    ),

    'register-user'       => array(
        'group'             => 'management',
        'name'              => 'register-user',
        'nicename'          => 'Register User',
        'link'              => 'https://ninjaforms.com/extensions/user-management/?utm_source=Ninja+Forms+Plugin&utm_medium=Emails+and+Actions&utm_campaign=Builder+Actions+Drawer&utm_content=User+Management',
        'plugin_path'       => 'ninja-forms-user-management/ninja-forms-user-management.php',
        'modal_content'     => '<div class="available-action-modal">
                                <img src="' . Ninja_Forms::$url . 'assets/img/add-ons/user-management.png"/>
                                <p>In order to use this action, you need User Management for Ninja Forms.</p>
                                <p>With User Management for Ninja Forms, you can:<ul><li>Register new users</li><li>Login registered users</li><li>Allow users to update their existing profiles</li></p>
                                <div class="actions">
                                    <a target="_blank" href="https://ninjaforms.com/extensions/user-management/?utm_source=Ninja+Forms+Plugin&utm_medium=Emails+and+Actions&utm_campaign=Builder+Actions+Drawer&utm_content=User+Management" title="User Management" class="primary nf-button">Learn More</a>
                                </div>
                            </div>',
    ),

    'login-user'       => array(
        'group'             => 'management',
        'name'              => 'login-user',
        'nicename'          => 'Login User',
        'link'              => 'https://ninjaforms.com/extensions/user-management/?utm_source=Ninja+Forms+Plugin&utm_medium=Emails+and+Actions&utm_campaign=Builder+Actions+Drawer&utm_content=User+Management',
        'plugin_path'       => 'ninja-forms-user-management/ninja-forms-user-management.php',
        'modal_content'     => '<div class="available-action-modal">
                                <img src="' . Ninja_Forms::$url . 'assets/img/add-ons/user-management.png"/>
                                <p>In order to use this action, you need User Management for Ninja Forms.</p>
                                <p>With User Management for Ninja Forms, you can:<ul><li>Register new users</li><li>Login registered users</li><li>Allow users to update their existing profiles</li></p>
                                <div class="actions">
                                    <a target="_blank" href="https://ninjaforms.com/extensions/user-management/?utm_source=Ninja+Forms+Plugin&utm_medium=Emails+and+Actions&utm_campaign=Builder+Actions+Drawer&utm_content=User+Management" title="User Management" class="primary nf-button">Learn More</a>
                                </div>
                            </div>',
    ),

    'update-profile'       => array(
        'group'             => 'management',
        'name'              => 'update-profile',
        'nicename'          => 'Update Profile',
        'link'              => 'https://ninjaforms.com/extensions/user-management/?utm_source=Ninja+Forms+Plugin&utm_medium=Emails+and+Actions&utm_campaign=Builder+Actions+Drawer&utm_content=User+Management',
        'plugin_path'       => 'ninja-forms-user-management/ninja-forms-user-management.php',
        'modal_content'     => '<div class="available-action-modal">
                                <img src="' . Ninja_Forms::$url . 'assets/img/add-ons/user-management.png"/>
                                <p>In order to use this action, you need User Management for Ninja Forms.</p>
                                <p>With User Management for Ninja Forms, you can:<ul><li>Register new users</li><li>Login registered users</li><li>Allow users to update their existing profiles</li></p>
                                <div class="actions">
                                    <a target="_blank" href="https://ninjaforms.com/extensions/user-management/?utm_source=Ninja+Forms+Plugin&utm_medium=Emails+and+Actions&utm_campaign=Builder+Actions+Drawer&utm_content=User+Management" title="User Management" class="primary nf-button">Learn More</a>
                                </div>
                            </div>',
    ),

    'helpscout'       => array(
        'group'             => 'management',
        'name'              => 'helpscout',
        'nicename'          => 'Help Scout',
        'link'              => 'https://ninjaforms.com/extensions/help-scout/?utm_source=Ninja+Forms+Plugin&utm_medium=Emails+and+Actions&utm_campaign=Builder+Actions+Drawer&utm_content=Help+Scout',
        'plugin_path'       => 'ninja-forms-helpscout/ninja-forms-helpscout.php',
        'modal_content'     => '<div class="available-action-modal">
                                <img src="' . Ninja_Forms::$url . 'assets/img/add-ons/help-scout.png"/>
                                <p>In order to use this action, you need Help Scout for Ninja Forms.</p>
                                <p>Build the perfect support form for your users that funnels them directly into your Help Scout account. A great support experience begins with your support form! </p>
                                <div class="actions">
                                    <a target="_blank" href="https://ninjaforms.com/extensions/help-scout/?utm_source=Ninja+Forms+Plugin&utm_medium=Emails+and+Actions&utm_campaign=Builder+Actions+Drawer&utm_content=Help+Scout" title="Help Scout" class="primary nf-button">Learn More</a>
                                </div>
                            </div>',
    ),

    'salesforce-crm'        => array(
        'group'             => 'marketing',
        'name'              => 'salesforce-crm',
        'nicename'          => 'Salesforce CRM',
        'link'              => 'https://ninjaforms.com/extensions/salesforce-crm/?utm_source=Ninja+Forms+Plugin&utm_medium=Emails+and+Actions&utm_campaign=Builder+Actions+Drawer&utm_content=Salesforce+CRM',
        'plugin_path'       => 'ninja-forms-salesforce-crm/ninja-forms-salesforce-crm.php',
        'modal_content'     => '<div class="available-action-modal">
                                <img src="' . Ninja_Forms::$url . 'assets/img/add-ons/salesforce-crm.png"/>
                                <p>In order to use this action, you need Salesforce CRM for Ninja Forms.</p>
                                <p>When the world’s most used CMS and the industry leading CRM come together, great things are bound to happen for your organization. WordPress and Salesforce is an integration that you need working for you!</p>
                                <div class="actions">
                                    <a target="_blank" href="https://ninjaforms.com/extensions/salesforce-crm/?utm_source=Ninja+Forms+Plugin&utm_medium=Emails+and+Actions&utm_campaign=Builder+Actions+Drawer&utm_content=Salesforce+CRM" title="Salesforce CRM" class="primary nf-button">Learn More</a>
                                </div>
                            </div>',
    ),

    'capsule-crm'           => array(
        'group'             => 'marketing',
        'name'              => 'capsule-crm',
        'nicename'          => 'Capsule CRM',
        'link'              => 'https://ninjaforms.com/extensions/capsule-crm/?utm_source=Ninja+Forms+Plugin&utm_medium=Emails+and+Actions&utm_campaign=Builder+Actions+Drawer&utm_content=Capsule+CRM',
        'plugin_path'       => 'ninja-forms-capsule-crm/ninja-forms-capsule-crm.php',
        'modal_content'     => '<div class="available-action-modal">
                                <img src="' . Ninja_Forms::$url . 'assets/img/add-ons/capsule-crm.png"/>
                                <p>In order to use this action, you need Capsule CRM for Ninja Forms.</p>
                                <p>Connecting your WordPress website to your CRM account shouldn’t be a time sink for your team, but it too often can be. Take that pain away with effortless integration between WordPress and your CRM with Ninja Forms’ official Capsule CRM addon!</p>
                                <div class="actions">
                                    <a target="_blank" href="https://ninjaforms.com/extensions/capsule-crm/?utm_source=Ninja+Forms+Plugin&utm_medium=Emails+and+Actions&utm_campaign=Builder+Actions+Drawer&utm_content=Capsule+CRM" title="Capsule CRM" class="primary nf-button">Learn More</a>
                                </div>
                            </div>',
    ),

    'recurly'               => array(
        'group'             => 'payments',
        'name'              => 'recurly',
        'nicename'          => 'Recurly',
        'link'              => 'https://ninjaforms.com/extensions/recurly/?utm_source=Ninja+Forms+Plugin&utm_medium=Emails+and+Actions&utm_campaign=Builder+Actions+Drawer&utm_content=Recurly',
        'plugin_path'       => 'ninja-forms-recurly/ninja-forms-recurly.php',
        'modal_content'     => '<div class="available-action-modal">
                                <img src="' . Ninja_Forms::$url . 'assets/img/add-ons/recurly.png"/>
                                <p>In order to use this action, you need Recurly for Ninja Forms.</p>
                                <p>You can use any form to sign up new members to a recurring subscription plan. Connect any form directly and securely to your Recurly account. </p>
                                <div class="actions">
                                    <a target="_blank" href="https://ninjaforms.com/extensions/recurly/?utm_source=Ninja+Forms+Plugin&utm_medium=Emails+and+Actions&utm_campaign=Builder+Actions+Drawer&utm_content=Recurly" title="Recurly" class="primary nf-button">Learn More</a>
                                </div>
                            </div>',
    ),

    'highrise-crm'          => array(
        'group'             => 'marketing',
        'name'              => 'highrise-crm',
        'nicename'          => 'Highrise CRM',
        'link'              => 'https://ninjaforms.com/extensions/highrise-crm/?utm_source=Ninja+Forms+Plugin&utm_medium=Emails+and+Actions&utm_campaign=Builder+Actions+Drawer&utm_content=Highrise+CRM',
        'plugin_path'       => 'ninja-forms-highrise-crm/ninja-forms-highrise-crm.php',
        'modal_content'     => '<div class="available-action-modal">
                                <img src="' . Ninja_Forms::$url . 'assets/img/add-ons/highrise-crm.png"/>
                                <p>In order to use this action, you need Highrise CRM for Ninja Forms.</p>
                                <p></p>
                                <div class="actions">
                                    <a target="_blank" href="https://ninjaforms.com/extensions/highrise-crm/?utm_source=Ninja+Forms+Plugin&utm_medium=Emails+and+Actions&utm_campaign=Builder+Actions+Drawer&utm_content=Highrise+CRM" title="Highrise CRM" class="primary nf-button">Learn More</a>
                                </div>
                            </div>',
    ),

    'onepage-crm'           => array(
        'group'             => 'marketing',
        'name'              => 'onepage-crm',
        'nicename'          => 'OnePage CRM',
        'link'              => 'https://ninjaforms.com/extensions/onepage-crm/?utm_source=Ninja+Forms+Plugin&utm_medium=Emails+and+Actions&utm_campaign=Builder+Actions+Drawer&utm_content=OnePage+CRM',
        'plugin_path'       => 'ninja-forms-onepagecrm/ninja-forms-onepage-crm.php',
        'modal_content'     => '<div class="available-action-modal">
                                <img src="' . Ninja_Forms::$url . 'assets/img/add-ons/onepage-crm.png"/>
                                <p>In order to use this action, you need OnePage CRM for Ninja Forms.</p>
                                <p>OnePage CRM is designed to keep your sales team focused on sales instead of navigating complex software. Ninja Forms’ official integration has been built with that ideal in mind and delivers in kind!</p>
                                <div class="actions">
                                    <a target="_blank" href="https://ninjaforms.com/extensions/onepage-crm/?utm_source=Ninja+Forms+Plugin&utm_medium=Emails+and+Actions&utm_campaign=Builder+Actions+Drawer&utm_content=OnePage+CRM" title="OnePage CRM" class="primary nf-button">Learn More</a>
                                </div>
                            </div>',
    ),
) );
