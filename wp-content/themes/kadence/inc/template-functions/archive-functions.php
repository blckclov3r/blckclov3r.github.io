<?php
/**
 * Calls in content using theme hooks.
 *
 * @package kadence
 */

namespace Kadence;

use function get_template_part;

defined( 'ABSPATH' ) || exit;

/**
 * Archive Content
 */
function archive_markup() {
	get_template_part( 'template-parts/content/archive', get_post_type() );
}

/**
 * Get Archive header classes.
 *
 * @return array $classes for the archive header.
 */
function get_archive_hero_classes() {
	$slug      = ( is_search() && ! is_post_type_archive( 'product' ) ? 'search' : get_post_type() );
	$classes   = array();
	$classes[] = 'entry-hero';
	$classes[] = $slug . '-archive-hero-section';
	$classes[] = 'entry-hero-layout-' . ( kadence()->option( $slug . '_archive_title_inner_layout' ) ? kadence()->option( $slug . '_archive_title_inner_layout' ) : 'inherit' );

	return apply_filters( 'kadence_archive_hero_classes', $classes );
}

/**
 * Get Archive header classes.
 *
 * @return array $classes for the archive header.
 */
function get_archive_title_classes() {
	$slug      = ( is_search() && ! is_post_type_archive( 'product' ) ? 'search' : get_post_type() );
	$classes   = array();
	$classes[] = 'entry-header';
	$classes[] = $slug . '-archive-title';
	$classes[] = 'title-align-' . ( kadence()->sub_option( $slug . '_archive_title_align', 'desktop' ) ? kadence()->sub_option( $slug . '_archive_title_align', 'desktop' ) : 'inherit' );
	$classes[] = 'title-tablet-align-' . ( kadence()->sub_option( $slug . '_archive_title_align', 'tablet' ) ? kadence()->sub_option( $slug . '_archive_title_align', 'tablet' ) : 'inherit' );
	$classes[] = 'title-mobile-align-' . ( kadence()->sub_option( $slug . '_archive_title_align', 'mobile' ) ? kadence()->sub_option( $slug . '_archive_title_align', 'mobile' ) : 'inherit' );
	return apply_filters( 'kadence_archive_title_classes', $classes );
}

/**
 * Get Archive container classes.
 *
 * @return array $classes for the archive container.
 */
function get_archive_container_classes() {
	$classes   = array();
	$classes[] = 'content-wrap';
	$classes[] = 'grid-cols';
	if ( is_search() ) {
		$classes[] = 'search-archive';
		if ( '1' === kadence()->option( 'search_archive_columns' ) ) {
			$placement    = kadence()->option( 'search_archive_item_image_placement' );
			$classes[] = 'grid-sm-col-1';
			$classes[] = 'grid-lg-col-1';
			$classes[] = 'item-image-style-' . $placement;
		} elseif ( '2' === kadence()->option( 'search_archive_columns' ) ) {
			$classes[] = 'grid-sm-col-2';
			$classes[] = 'grid-lg-col-2';
			$classes[] = 'item-image-style-above';
		} else {
			$classes[] = 'grid-sm-col-2';
			$classes[] = 'grid-lg-col-3';
			$classes[] = 'item-image-style-above';
		}
	} elseif ( 'post' === get_post_type() ) {
		$classes[] = 'post-archive';
		if ( '1' === kadence()->option( 'post_archive_columns' ) ) {
			$placement    = kadence()->option( 'post_archive_item_image_placement' );
			$classes[] = 'grid-sm-col-1';
			$classes[] = 'grid-lg-col-1';
			$classes[] = 'item-image-style-' . $placement;
		} elseif ( '2' === kadence()->option( 'post_archive_columns' ) ) {
			$classes[] = 'grid-sm-col-2';
			$classes[] = 'grid-lg-col-2';
			$classes[] = 'item-image-style-above';
		} else {
			$classes[] = 'grid-sm-col-2';
			$classes[] = 'grid-lg-col-3';
			$classes[] = 'item-image-style-above';
		}
	} elseif ( kadence()->option( get_post_type() . '_archive_columns' ) ) {
		$classes[] = get_post_type() . '-archive';
		if ( '1' === kadence()->option( get_post_type() . '_archive_columns' ) ) {
			$placement = kadence()->option( get_post_type() . '_archive_item_image_placement', 'above' );
			$classes[] = 'grid-sm-col-1';
			$classes[] = 'grid-lg-col-1';
			$classes[] = 'item-image-style-' . $placement;
		} elseif ( '2' === kadence()->option( get_post_type() . '_archive_columns' ) ) {
			$classes[] = 'grid-sm-col-2';
			$classes[] = 'grid-lg-col-2';
			$classes[] = 'item-image-style-above';
		} else {
			$classes[] = 'grid-sm-col-2';
			$classes[] = 'grid-lg-col-3';
			$classes[] = 'item-image-style-above';
		}
	} else {
		$classes[] = 'post-archive';
		$classes[] = 'grid-sm-col-2';
		$classes[] = 'grid-lg-col-3';
	}
	return apply_filters( 'kadence_archive_container_classes', $classes );
}

/**
 * Get Archive infinite attributes
 *
 * @return string $attributes for the archive container.
 */
function get_archive_infinite_attributes() {
	$attributes = '';
	return apply_filters( 'kadence_archive_infinite_attributes', $attributes );
}
