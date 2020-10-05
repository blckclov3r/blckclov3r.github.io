<?php
/**
 * Template part for displaying a post's header
 *
 * @package kadence
 */

namespace Kadence;

?>
<header class="entry-header">

	<?php
	if ( 'post' === get_post_type() ) {
		get_template_part( 'template-parts/content/entry_loop_taxonomies', get_post_type() );
	}
	get_template_part( 'template-parts/content/entry_loop_title', get_post_type() );

	get_template_part( 'template-parts/content/entry_loop_meta', get_post_type() );
	?>
</header><!-- .entry-header -->
