<?php

namespace FluentForm\App\Modules\ReCaptcha;

use FluentForm\Framework\Helpers\ArrayHelper;

class ReCaptcha
{
    /**
     * Verify reCaptcha response.
     *
     * @param string $token response from the user.
     * @param null $secret provided or already stored secret key.
     *
     * @return bool
     */
    public static function validate($token, $secret = null)
    {
        $verifyUrl = 'https://www.google.com/recaptcha/api/siteverify';

        $secret = $secret ?: ArrayHelper::get(get_option('_fluentform_reCaptcha_details'), 'secretKey');

        $response = wp_remote_post($verifyUrl, [
            'method' => 'POST',
            'body'   => [
                'secret'   => $secret,
                'response' => $token
            ],
        ]);

        $isValid = false;

        if (! is_wp_error($response)) {
            $result = json_decode(wp_remote_retrieve_body($response));
            $isValid = $result->success;
        }

        return $isValid;
    }
}