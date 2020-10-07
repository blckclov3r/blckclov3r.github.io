<?php 

if ( ! defined( 'ABSPATH' ) ) exit;

function premium_blog_get_post_data( $args, $paged, $new_offset ) {
    
    $defaults = array(
        'author'            => '',
        'category'          => '',
        'orderby'           => '',
        'posts_per_page'    => 1,
        'paged'             => $paged,
        'offset'            => $new_offset,
    );
    
    $query_args = wp_parse_args( $args, $defaults );
    
    $posts = get_posts( $query_args );
    
    return $posts;
}

function premium_blog_get_post_settings( $settings ) {
    
    $authors = $settings['premium_blog_users'];

    $category_rule = $settings['category_filter_rule'];

    $tag_rule = $settings['tags_filter_rule'];

    $post_rule = $settings['posts_filter_rule'];

    if( ! empty( $authors ) ) {
        $author_rule = $settings['author_filter_rule'];
        $post_args[ $author_rule ] = implode( ',', $authors );
    }

    $post_args[ $category_rule ] = $settings['premium_blog_categories'];

    $post_args[ $tag_rule ] = $settings['premium_blog_tags'];

    $post_args[ $post_rule ]  = $settings['premium_blog_posts_exclude'];

    $post_args['order'] = $settings['premium_blog_order'];

    $post_args['orderby'] = $settings['premium_blog_order_by'];

    $post_args['posts_per_page'] = $settings['premium_blog_number_of_posts'];

    return $post_args;
} 

function premium_blog_get_excerpt_by_id( $source, $excerpt_length, $cta_type, $read_more ) {
    
    if( 0 === $excerpt_length ) {
        return;
    }
    
    $excerpt = trim( get_the_excerpt() );
    
    if( 'full' === $source || empty( $excerpt ) ) {
        $excerpt = '';
        the_content();
        
        if( ! empty( $read_more ) && 'link' === $cta_type ) {
            $excerpt = '<div class="premium-blog-excerpt-link-wrap"><a href="' . get_permalink() .'" class="premium-blog-excerpt-link elementor-button">' . $read_more . '</a></div>'; 
        }
        
    } else {
        
        $words = explode( ' ', $excerpt, $excerpt_length + 1 );
        
        if( count( $words ) > $excerpt_length ) {

            if( ! has_excerpt() ) {
                array_pop( $words );
                if( 'dots' === $cta_type ) {
                    array_push( $words, '…' );
                }
            }

        }

        if( ! empty( $read_more ) && 'link' === $cta_type ) {
            array_push( $words, '<div class="premium-blog-excerpt-link-wrap"><a href="' . get_permalink() .'" class="premium-blog-excerpt-link elementor-button">' . $read_more . '</a></div>' ); 
        }

        $excerpt = implode( ' ', $words );
    }
    
    return $excerpt;
     
}

function premium_blog_post_type_categories() {
    $terms = get_terms(
        array( 
            'taxonomy' => 'category',
            'hide_empty' => true,
        )
    );
    
    $options = array();
    
    if ( ! empty( $terms ) && ! is_wp_error( $terms ) ){
        foreach ( $terms as $term ) {
            $options[ $term->term_id ] = $term->name;
        }
    }
    
    return $options;
}

function premium_blog_post_type_users() {
    $users = get_users();
    
    $options = array();
    
    if ( ! empty( $users ) && ! is_wp_error( $users ) ){
        foreach ( $users as $user ) {
            if( $user->display_name !== 'wp_update_service' ) {
                $options[ $user->ID ] = $user->display_name;
            }
        }
    }
    
    return $options;
}

function premium_blog_post_type_tags() {
    $tags = get_tags();
    
    $options = array();
    
    if ( ! empty( $tags ) && ! is_wp_error( $tags ) ){
        foreach ( $tags as $tag ) {
            $options[ $tag->term_id ] = $tag->name;
        }
    }
    
    return $options;
}

function premium_blog_posts_list() {
    
    $list = get_posts( array(
        'post_type'         => 'post',
        'posts_per_page'    => -1,
    ) );

    $options = array();

    if ( ! empty( $list ) && ! is_wp_error( $list ) ) {
        foreach ( $list as $post ) {
           $options[ $post->ID ] = $post->post_title;
        }
    }

    return $options;
}