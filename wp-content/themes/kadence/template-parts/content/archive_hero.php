<?php
/**
 * Template part for displaying a post's Hero header
 *
 * @package kadence
 */

namespace Kadence;

$slug = ( is_search() && ! is_post_type_archive( 'product' ) ? 'search' : get_post_type() );
?>
<section class="<?php echo esc_attr( implode( ' ', get_archive_hero_classes() ) ); ?>">
	<div class="entry-hero-container-inner">
		<div class="hero-section-overlay"></div>
		<div class="hero-container site-container">
			<header class="<?php echo esc_attr( implode( ' ', get_archive_title_classes() ) ); ?>">
				<?php
				/**
				 * Kadence Entry Hero
				 *
				 * Hooked kadence_entry_archive_header 10
				 */
				do_action( 'kadence_entry_archive_hero', $slug . '_archive', 'above' );
				?>
			</header><!-- .entry-header -->
		</div>
	</div>
</section><!-- .entry-hero -->
