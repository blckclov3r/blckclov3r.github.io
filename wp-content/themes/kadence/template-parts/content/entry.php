<?php
/**
 * Template part for displaying a post
 *
 * @package kadence
 */

namespace Kadence;

?>

<article <?php post_class( 'entry content-bg loop-entry' ); ?>>
	<?php
		get_template_part( 'template-parts/content/entry_loop_thumbnail', get_post_type() );
	?>
	<div class="entry-content-wrap">
		<?php
		get_template_part( 'template-parts/content/entry_loop_header', get_post_type() );

		get_template_part( 'template-parts/content/entry_summary', get_post_type() );

		get_template_part( 'template-parts/content/entry_loop_footer', get_post_type() );
		?>
	</div>
</article>