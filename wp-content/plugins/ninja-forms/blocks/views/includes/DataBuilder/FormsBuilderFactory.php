<?php

namespace NinjaForms\Blocks\DataBuilder;

class FormsBuilderFactory {

    public function make() {
        $forms = array_map( function($form) {
            return array_merge([ 'id' => $form->get_id(), ], $form->get_settings() );
        }, Ninja_Forms()->form()->get_forms() );
        return new FormsBuilder( $forms );
    }
}
