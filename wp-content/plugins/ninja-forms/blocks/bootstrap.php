<?php

/**
 * Register blocks and there scripts
 */
add_action('init', function () {
    /**
     * Form Block
     */
    // automatically load dependencies and version
    $block_asset_file = include dirname(__DIR__) . '/build/form-block.asset.php';
    $block = (array)json_decode(file_get_contents(__DIR__ . '/form/block.json'), true);

    wp_register_script(
        'ninja-forms/form',
        plugins_url('../build/form-block.js', __FILE__),
        $block_asset_file['dependencies'],
        $block_asset_file['version']
    );

    register_block_type('ninja-forms/form', array_merge($block, [
        'title' => esc_attr__('Ninja Form', 'ninja-forms'),
        'render_callback' => function ($atts) {
            $formID = isset($atts['formID']) ? $atts['formID'] : 1;
            ob_start();
            Ninja_Forms()->display( absint($formID), true );
            return ob_get_clean();
        },
        'editor_script' => 'ninja-forms/form'
    ]));


    /**
     * Views Block
     */
    $token = NinjaForms\Blocks\Authentication\TokenFactory::make();
    $publicKey = NinjaForms\Blocks\Authentication\KeyFactory::make();

    // automatically load dependencies and version
    $block_asset_file = include dirname(__DIR__) . '/build/sub-table-block.asset.php';
    wp_register_script(
        'ninja-forms/submissions-table/block',
        plugins_url('../build/sub-table-block.js', __FILE__),
        $block_asset_file['dependencies'],
        $block_asset_file['version']
    );

    wp_localize_script('ninja-forms/submissions-table/block', 'ninjaFormsViews', [
        'token' => $token->create($publicKey),
    ]);

    $render_asset_file = include dirname(__DIR__) . '/build/sub-table-render.asset.php';
    wp_register_script(
        'ninja-forms/submissions-table/render',
        plugins_url('../build/sub-table-render.js', __FILE__),
        $render_asset_file['dependencies'],
        $render_asset_file['version']
    );

    wp_localize_script('ninja-forms/submissions-table/render', 'ninjaFormsViews', [
        'token' => $token->create($publicKey),
    ]);

    register_block_type('ninja-forms/submissions-table', array(
        'editor_script' => 'ninja-forms/submissions-table/block',
        'render_callback' => function ($attributes, $content) {
            if (isset($attributes['formID']) && $attributes['formID']) {
                wp_enqueue_script('ninja-forms/submissions-table/render');
                $className = 'ninja-forms-views-submissions-table';
                if (isset($attributes['alignment'])) {
                    $className .= ' align' . $attributes['alignment'];
                }
                return sprintf("<div class='%s' data-attributes='%s'></div>", esc_attr($className),
                    esc_attr(wp_json_encode($attributes)));
            }
        }
    ));

    /**
     * Have Translations set in scripts via i18n package
     * https://developer.wordpress.org/block-editor/packages/packages-i18n/
     * https://developer.wordpress.org/reference/functions/wp_set_script_translations/
     * https://developer.wordpress.org/block-editor/developers/internationalization/
     */
    wp_set_script_translations( "ninja-forms/form", "ninja-forms", plugin_dir_path( __FILE__ ) . 'lang' );
    wp_set_script_translations( "ninja-forms/submissions-table/block", "ninja-forms", plugin_dir_path( __FILE__ ) . 'lang' );
    wp_set_script_translations( "ninja-forms/submissions-table/render", "ninja-forms", plugin_dir_path( __FILE__ ) . 'lang' );

});

/**
 * Localize data for blocks
 */
add_action('admin_enqueue_scripts', function () {
    //Get all forms, to base form selector on.
    $formsBuilder = (new NinjaForms\Blocks\DataBuilder\FormsBuilderFactory)->make();
    $forms = $formsBuilder->get();
    if (!empty($forms)) {
        //Escape for use in JavaScript
        foreach ($forms as $key => $form) {
            $forms[$key] = [
                'formID' => absint($form['formID']),
                'formTitle' => esc_textarea($form['formTitle'])
            ];
        }
    }
    wp_localize_script('ninja-forms/form', 'nfFormsBlock', [
        'forms' => $forms,//array keys escaped above
        'siteUrl' => esc_url_raw(site_url()),
        'previewToken' => wp_create_nonce('nf_iframe' )
    ]);
});

/**
 * Register REST API routes related to blocks
 */
add_action('rest_api_init', function () {

    $tokenAuthenticationCallback = function (WP_REST_Request $request) {
        $token = NinjaForms\Blocks\Authentication\TokenFactory::make();
        return $token->validate($request->get_header('X-NinjaFormsViews-Auth'));
    };

    register_rest_route('ninja-forms-views', 'forms', array(
        'methods' => 'GET',
        'callback' => function (WP_REST_Request $request) {
            $formsBuilder = (new NinjaForms\Blocks\DataBuilder\FormsBuilderFactory)->make();
            return $formsBuilder->get();
        },
        'permission_callback' => '__return_true',
    ));

    register_rest_route('ninja-forms-views', 'forms/(?P<id>\d+)/fields', [
        'methods' => 'GET',
        'args' => [
            'id' => [
                'required' => true,
                'description' => esc_attr__('Unique identifier for the object.', 'ninja-forms'),
                'type' => 'integer',
                'validate_callback' => 'rest_validate_request_arg',
            ],
        ],
        'callback' => function (WP_REST_Request $request) {
            $fieldsBuilder = (new NinjaForms\Blocks\DataBuilder\FieldsBuilderFactory)->make(
                $request->get_param('id')
            );
            return $fieldsBuilder->get();
        },
        'permission_callback' => $tokenAuthenticationCallback,
    ]);

    register_rest_route('ninja-forms-views', 'forms/(?P<id>\d+)/submissions', [
        'methods' => 'GET',
        'args' => [
            'id' => [
                'required' => true,
                'description' => esc_attr__('Unique identifier for the object.', 'ninja-forms'),
                'type' => 'integer',
                'validate_callback' => 'rest_validate_request_arg',
            ],
            'perPage' => [
                'description' => esc_attr__('Maximum number of items to be returned in result set.', 'ninja-forms'),
                'type' => 'integer',
                'minimum' => 1,
                'maximum' => 100,
                'sanitize_callback' => 'absint',
                'validate_callback' => 'rest_validate_request_arg',
            ],
            'page' => [
                'description' => esc_attr__('Current page of the collection.', 'ninja-forms'),
                'type' => 'integer',
                'default' => 1,
                'sanitize_callback' => 'absint',
                'validate_callback' => 'rest_validate_request_arg',
                'minimum' => 1,
            ]
        ],
        'callback' => function (WP_REST_Request $request) {
            $submissionsBuilder = (new NinjaForms\Blocks\DataBuilder\SubmissionsBuilderFactory)->make(
                $request->get_param('id'),
                $request->get_param('perPage'),
                $request->get_param('page')
            );
            return $submissionsBuilder->get();
        },
        'permission_callback' => $tokenAuthenticationCallback,
    ]);

});

/**
 * Handler for form preview iFrame used in Forms block
 */
add_action( 'wp_head', function () {
    // check for preview and iframe get parameters
    if( isset( $_GET[ 'nf_preview_form' ] ) && isset( $_GET[ 'nf_iframe' ] ) ){
        if( ! wp_verify_nonce( $_GET['nf_iframe'], 'nf_iframe') ){
            wp_die( esc_html__('Preview token failed validation', 'ninja-forms'));
            exit;
        }

        //Attempt to get theme background color
        $background = '#fff';
        $supports = get_theme_support('editor-color-palette','background');
        if( is_array($supports) ){
            foreach($supports[0] as $index => $support ){
                if( 'background' === $support['slug']){
                    $background = $support['color'];
                    break;
                }
            }
        }

        $js_dir  = Ninja_Forms::$url . 'assets/js/min/';

        $form_id = absint( $_GET[ 'nf_preview_form' ] );
        // Style below: update width and height for particular form
        ?>
        <style media="screen">
            #wpadminbar {
                display: none;
            }
            #nf-form-<?php echo $form_id; ?>-cont {
                z-index: 90000001;
                position: fixed;
                top: 0; left: 0;
                width: 100vw;
                height: 100vh;
                background-color: <?php echo sanitize_hex_color($background ); ?>;
            }

            div.site-branding, header.entry-header, .site-footer, header, .footer-nav-widgets-wrapper {
                display:none !important;
            }

        </style>

        <?php

        // register our script to target the form iFrame in page builder
        wp_register_script(
            'ninja-forms-block-setup',
            $js_dir . 'blockFrameSetup.js',
            array( 'underscore', 'jquery' )
        );

        wp_localize_script( 'ninja-forms-block-setup', 'ninjaFormsBlockSetup', array(
            'form_id' => $form_id
        ) );

        wp_enqueue_script( 'ninja-forms-block-setup' );
    }

});