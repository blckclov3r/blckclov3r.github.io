<?php
/**
 * Finder tools class
 *
 * @package Happy_Addons
 */
namespace Happy_Addons\Elementor;

use Elementor\Core\Base\Document;
use Elementor\Core\Common\Modules\Finder\Base_Category as Finder_Category;
use Elementor\TemplateLibrary\Source_Local;
use Happy_Addons\Elementor\Clone_Handler as Cloner;

defined( 'ABSPATH' ) || die();

class Finder_Edit extends Finder_Category {

    const SLUG = 'edit';

    /**
     * Get title.
     *
     * @access public
     *
     * @return string
     */
    public function get_title() {
        return __( 'Edit + Happy Clone', 'happy-elementor-addons' );
    }

    /**
     * Set category as dynamic
     *
     * @return bool
     */
    public function is_dynamic() {
        return true;
    }

    /**
     * Get category items.
     *
     * @access public
     *
     * @param array $options
     *
     * @return array
     */
    public function get_category_items( array $options = [] ) {
        $post_types = get_post_types( [
            'exclude_from_search' => false,
        ] );

        $post_types[] = Source_Local::CPT;

        $document_types = ha_elementor()->documents->get_document_types( [
            'is_editable' => true,
        ] );

        $query_args = [
            'post_type' => $post_types,
            'post_status' => [ 'publish', 'draft', 'private', 'pending', 'future' ],
            'posts_per_page' => '10',
            'meta_query' => [
                [
                    'key' => '_elementor_edit_mode',
                    'value' => 'builder',
                ],
                [
                    'relation' => 'or',
                    [
                        'key' => Document::TYPE_META_KEY,
                        'compare' => 'NOT EXISTS',
                    ],
                    [
                        'key' => Document::TYPE_META_KEY,
                        'value' => array_keys( $document_types ),
                    ],
                ],
            ],
            'orderby' => 'modified',
            's' => $options['filter'],
        ];


        if ( Cloner::can_clone() ) {
            $editor_post_id = isset( $options['editor_post_id'] ) ? $options['editor_post_id'] : 0;
            $editor_document = ha_elementor()->documents->get( $editor_post_id );
            $items = [];

            $ref = 'list';

            if ( $editor_document ) {
                $description = $editor_document->get_title();
                $icon = 'document-file';
                $ref = 'editor';

                if ( $editor_document->get_post()->post_type === Source_Local::CPT ) {
                    $description = __( 'Template', 'happy-elementor-addons' ) . ' / ' . $description;
                    $icon = 'post-title';
                }

                $url = Cloner::get_url( $editor_document->get_id(), $ref );
                $items[] = [
                    'icon' => $icon,
                    'title' => __( 'Clone / Duplicate This', 'happy-elementor-addons' ),
                    'description' => $description,
                    'url' => $url,
                    'actions' => [
                        [
                            'name' => 'clone',
                            'url' => $url,
                            'icon' => 'clone',
                        ]
                    ]
                ];
            }
        }


        $posts_query = new \WP_Query( $query_args );

        foreach ( $posts_query->posts as $post ) {
            $document = ha_elementor()->documents->get( $post->ID );

            if ( ! $document ) {
                continue;
            }

            $is_template = Source_Local::CPT === $post->post_type;

            $description = $document->get_title();

            $icon = 'document-file';

            if ( $is_template ) {
                $description = __( 'Template', 'happy-elementor-addons' ) . ' / ' . $description;

                $icon = 'post-title';
            }

            $actions = [
                [
                    'name' => 'view',
                    'url' => $document->get_permalink(),
                    'icon' => 'eye',
                ],
            ];

            if ( Cloner::can_clone() ) {
                $actions[] = [
                    'name' => 'clone',
                    'url' => Cloner::get_url( $document->get_id(), $ref ),
                    'icon' => 'clone',
                ];
            }

            $items[] = [
                'icon' => $icon,
                'title' => $post->post_title,
                'description' => $description,
                'url' => $document->get_edit_url(),
                'actions' => $actions,
            ];
        }

        return $items;
    }
}
