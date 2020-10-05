<?php

namespace NinjaForms\Blocks\DataBuilder;

class SubmissionsBuilder {

    protected $submissions;

    public static function make( $formID ) {
        $submissions = array_map( function( $submission ) {
            return $submission->get_field_values();
        }, Ninja_Forms()->form( $formID )->get_subs() );
        return new self( $submissions );
    }

    public function __construct( $submissions ) {
        $this->submissions = $submissions;
    }

    public function get() {
        return array_map( [ $this, 'toArray' ], $this->submissions );
    }

    protected function toArray( $values ) {
        $values = array_map([$this, 'formatValue'], $values );
        $values = array_filter( $values, function( $value, $key ) {
            return 0 === strpos( $key, '_field_' );
        }, ARRAY_FILTER_USE_BOTH );
        return $this->normalizeArrayKeys( $values );
    }

    protected function formatValue( $value ) {

        /**
         * Basic File Uploads support.
         * 
         * Auto-detect a file uploads value, by format, as a serialized array.
         * @note using a preliminary `is_serialized()` check to determine
         *       if the value is from File Uploads, since we do not have
         *       access to the field information in this context.
         */
        if(is_serialized($value)) {
            $unserialized = unserialize($value);
            if(is_array($unserialized)) {
                return implode(', ', array_values($unserialized));
            }
        }

        return $value;
    }

    protected function normalizeArrayKeys( $values ) {
        $keys = array_map( function( $key ) {
            return str_replace( '_field_', '', $key );
        }, array_flip( $values ) );
        return array_flip( $keys );
    }
}