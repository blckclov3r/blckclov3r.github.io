<?php

namespace NinjaForms\Blocks\DataBuilder;

class FieldsBuilder {

    protected $fields;

    public function __construct( $fields ) {
        $this->fields = $fields;
    }

    public function get() {
        $fields = array_filter( $this->fields, function( $field ) {
            return ! in_array( $field[ 'type' ], [ 'submit', 'html', 'hr' ] );
        });
        return array_map( [ $this, 'toArray' ], $fields );
    }

    protected function toArray( $field ) {
        extract( $field );
        return [
            'id' => $id,
            'label' => $label
        ];
    }
}