<?php

namespace NinjaForms\Blocks\DataBuilder;

class FieldsBuilderFactory {

    public function make( $formID ) {
        $fields = array_map( function($field) {
            return array_merge([ 'id' => $field->get_id(), ], $field->get_settings() );
        }, Ninja_Forms()->form( $formID )->get_fields() );
        return new FieldsBuilder( $fields );
    }
}