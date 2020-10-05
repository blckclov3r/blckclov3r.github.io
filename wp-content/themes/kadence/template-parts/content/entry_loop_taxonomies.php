<?php
/**
 * Template part for displaying a post's category terms
 *
 * @package kadence
 */

namespace Kadence;

$slug     = ( is_search() ? 'search' : 'post' );
$elements = kadence()->option( $slug . '_archive_element_categories' );
if ( isset( $elements ) && is_array( $elements ) && true === $elements['enabled'] ) {
	$divider  = ( isset( $elements['divider'] ) && ! empty( $elements['divider'] ) ? $elements['divider'] : 'vline' );
	$style    = ( isset( $elements['style'] ) && ! empty( $elements['style'] ) ? $elements['style'] : 'normal' );
	switch ( $divider ) {
		case 'dot':
			$separator = ' &middot; ';
			break;
		case 'slash':
			/* translators: separator between taxonomy terms */
			$separator = _x( ' / ', 'list item separator', 'kadence' );
			break;
		case 'dash':
			/* translators: separator between taxonomy terms */
			$separator = _x( ' - ', 'list item separator', 'kadence' );
			break;
		default:
			/* translators: separator between taxonomy terms */
			$separator = _x( ' | ', 'list item separator', 'kadence' );
			break;
	}
	if ( 'pill' === $style ) {
		$separator = ' ';
	}
	?>
	<div class="entry-taxonomies">
		<span class="category-links term-links category-style-<?php echo esc_attr( $style ); ?>">
			<?php echo get_the_category_list( esc_html( $separator ), '', $post->ID ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		</span>
	</div><!-- .entry-taxonomies -->
	<?php
}
