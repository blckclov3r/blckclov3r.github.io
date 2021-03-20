<?php
/**
 * Template part for displaying a post's comment and edit links
 *
 * @package kadence
 */

namespace Kadence;

$slug             = ( is_search() ? 'search' : get_post_type() );
$readmore_element = kadence()->option( $slug . '_archive_element_readmore', array(
	'enabled' => true,
) );
if ( isset( $readmore_element ) && is_array( $readmore_element ) && true === $readmore_element['enabled'] ) {
	?>
	<div class="entry-actions">
		<p class="more-link-wrap">
			<a href="<?php the_permalink(); ?>" class="post-more-link">
				<?php
					echo esc_html__( 'Read More', 'kadence' );
					kadence()->print_icon( 'arrow-right-alt' );
				?>
			</a>
		</p>
	</div><!-- .entry-actions -->
	<?php
}
