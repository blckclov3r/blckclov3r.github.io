<?php

namespace NinjaForms\Blocks\DataBuilder;

class SubmissionsBuilderFactory {

    /**
     * @param int $formID
     * @param int $perPage
     * @param int $page
     * 
     * @return SubmissionsBuilder
     */
    public function make( $formID, $perPage = -1, $page = 0 ) {

        $args = [
            'posts_per_page' => $perPage,
            'paged' => $page,
            'post_type' => 'nf_sub',
            'meta_query' => [[
                'key' => '_form_id',
                'value' => $formID
            ]]
        ];
        
        $submissions = array_map( function( $post ) {
            return array_map( [ self::class, 'flattenPostmeta' ], get_post_meta( $post->ID ) );
        }, get_posts( $args ) );

        return new SubmissionsBuilder( $submissions );
    }

    protected static function flattenPostmeta( $postmeta ) {
        $postmeta = (array) $postmeta;
        return reset( $postmeta );
    }
}