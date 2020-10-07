<?php

/**
 * Declare common (backend|frontend) global functions here
 * but try not to use any global functions unless you need.
 */

use FluentForm\Framework\Helpers\ArrayHelper;
use FluentForm\App\Services\FormBuilder\EditorShortCode;
use FluentForm\App\Modules\Component\BaseComponent as FluentFormComponent;

if (!function_exists('wpFluentForm')) {
    function wpFluentForm($key = null) {
        return FluentForm\App::make($key);
    }
}

if (!function_exists('wpFluentFormAddComponent')) {
    function wpFluentFormAddComponent(FluentFormComponent $component) {
        return $component->_init();
    }
}

if (! function_exists('dd')) {
    function dd()
    {
        foreach (func_get_args() as $value) {
            echo "<pre>";
            print_r($value);
            echo "</pre><br>";
        }
        die;
    }
}

if (! function_exists('fluentformMix')) {
    /**
     * Get the path to a versioned Mix file.
     *
     * @param string $path
     * @param string $manifestDirectory
     *
     * @return string
     * @throws \Exception
     */
    function fluentformMix($path, $manifestDirectory = '')
    {
        $env = \FluentForm\App::make('config')->app['env'];

        if ($env != 'dev') {
            $publicUrl = \FluentForm\App::publicUrl();

            return $publicUrl.$path;
        }

        static $manifests = [];

        if (substr($path, 0, 1) !== '/') {
            $path = "/".$path;
        }

        if ($manifestDirectory && substr($manifestDirectory, 0, 1) !== '/') {
            $manifestDirectory = "/".$manifestDirectory;
        }

        $publicPath = \FluentForm\App::publicPath();
        if (file_exists($publicPath.'/hot')) {
            return (is_ssl() ? "https" : "http")."://localhost:8080".$path;
        }

        $manifestPath = $publicPath.$manifestDirectory.'mix-manifest.json';

        if (! isset($manifests[$manifestPath])) {
            if (! file_exists($manifestPath)) {
                throw new Exception('The Mix manifest does not exist.');
            }

            $manifests[$manifestPath] = json_decode(file_get_contents($manifestPath), true);
        }

        $manifest = $manifests[$manifestPath];

        if (! isset($manifest[$path])) {
            throw new Exception(
                "Unable to locate Mix file: ".$path.". Please check your ".
                'webpack.mix.js output paths and try again.'
            );
        }

        return \FluentForm\App::publicUrl($manifestDirectory.$manifest[$path]);
    }
}

if (! function_exists('fluentFormSanitizer')) {
    /**
     * Sanitize form inputs recursively.
     *
     * @param $input
     *
     * @return string $input
     */
    function fluentFormSanitizer($input, $attribute = null, $fields = [])
    {
        if (is_string($input)) {
            if (ArrayHelper::get($fields, $attribute.'.element') === 'post_content') {
                return wp_kses_post($input);
            } else if (ArrayHelper::get($fields, $attribute.'.element') === 'textarea') {
                $input = sanitize_textarea_field($input);
            } else {
                $input = sanitize_text_field($input);
            }
        } elseif (is_array($input)) {
            foreach ($input as $key => &$value) {
                $attribute = $attribute ? $attribute.'['.$key.']' : $key;

                $value = fluentFormSanitizer($value, $attribute, $fields);

                $attribute = null;
            }
        }

        return $input;
    }
}

if (! function_exists('fluentFormEditorShortCodes')) {
    function fluentFormEditorShortCodes() {
        return apply_filters('fluentform_editor_shortcodes', [
            EditorShortCode::getGeneralShortCodes()
        ]);
    }
}

if (! function_exists('fluentFormGetAllEditorShortCodes')) {
    function fluentFormGetAllEditorShortCodes($form) {
        return apply_filters(
            'fluentform_all_editor_shortcodes',
            EditorShortCode::getShortCodes($form)
        );
    }
}

if (!function_exists('fluentImplodeRecursive')) {
    /**
     * Recursively implode a multi-dimentional array
     * @param string $glue
     * @param array $array
     * @return string
     */
    function fluentImplodeRecursive($glue, array $array) {
        $fn = function($glue, array $array) use (&$fn) {
            $result = '';
            foreach ($array as $item) {
                if(is_array($item)) {
                    $result .= $fn($glue, $item);
                } else {
                    $result .= $glue . $item;
                }
            }
            return $result;
        };

        return ltrim($fn($glue, $array), $glue);
    }
}


if (!function_exists('getFluentFormCountryList')) {
    function getFluentFormCountryList() {
        static $countries = null;
        if (is_null($countries)) {
            $countries = require(
                FluentForm\App::appPath('/Services/FormBuilder/CountryNames.php')
            );
        }
        return $countries;
    }
}

if (!function_exists('fluentFormWasSubmitted')) {
    function fluentFormWasSubmitted($action = 'fluentform_submit') {
        return wpFluentForm('request')->get('action') == $action;
    }
}

if (!function_exists('isWpAsyncRequest')) {
    function isWpAsyncRequest($action) {
        return strpos(wpFluentForm('request')->get('action'), $action) !== false;
    }
}

if (!function_exists('fluentFormIsHandlingSubmission')) {
    function fluentFormIsHandlingSubmission() {
        $status = fluentFormWasSubmitted() || isWpAsyncRequest('fluentform_async_request');
        return apply_filters('fluentform_is_handling_submission', $status);
    }
}

function fluentform_mb_strpos($haystack, $needle) {
    if(function_exists('mb_strpos')) {
        return mb_strpos($haystack, $needle);
    }
    return strpos($haystack, $needle);
}

function fluentFormHandleScheduledTasks()
{
    // Let's run the feed actions
    $handler = new \FluentForm\App\Services\WPAsync\FluentFormAsyncRequest(wpFluentForm());
    $handler->processActions();
}

function fluentFormHandleScheduledEmailReport()
{
     \FluentForm\App\Services\Scheduler\Scheduler::processEmailReport();
}