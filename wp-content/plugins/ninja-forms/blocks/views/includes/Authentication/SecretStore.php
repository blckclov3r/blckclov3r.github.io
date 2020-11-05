<?php

namespace NinjaForms\Blocks\Authentication;

/**
 * Manages a stored secret and guarentees that one is always available.
 */
class SecretStore {

    /** @var string */
    const OPTION_KEY = 'ninja-forms-views-secret';

    /**
     * Gets the SECRET or creates the SECRET if it does not exist.
     * 
     * If defined, defaults to NINJA_FORMS_VIEWS_SECRET constant.
     * If a secret does not exist, then it creates a secret and stores the value.
     * If the secret is wrongly typed, then it self-corrects by creating a new secret.
     * 
     * @return string
     */
    public static function getOrCreate() {

        // If defined, default to the NINJA_FORMS_VIEWS_SECRET constant.
        if( defined( 'NINJA_FORMS_VIEWS_SECRET' ) && self::validate( NINJA_FORMS_VIEWS_SECRET ) ) {
            $secret = NINJA_FORMS_VIEWS_SECRET;
        } else {
            $secret = get_option( self::OPTION_KEY );
        }

        // If the secret does not exist or is wrongly typed, then create a new secret and store the value.
        if( ! self::validate( $secret ) ) {
            $secret = KeyFactory::make();
            update_option( self::OPTION_KEY, $secret, $autoload = true );
        }

        return $secret;
    }

    /**
     * @param mixed $secret
     */
    public static function validate( $secret ) {
        return $secret && is_string( $secret );
    }
}