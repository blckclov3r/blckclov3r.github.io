<?php
/**
 * Template part for displaying comments list.
 *
 * @package kadence
 */

namespace Kadence;

?>
<?php
if ( have_comments() ) {
	do_action( 'kadence_before_comments_list' );
	?>
	<h2 class="comments-title">
		<?php
		$comment_count = (int) get_comments_number();
		if ( 1 === $comment_count ) {
			echo esc_html__( 'One Comment', 'kadence' );
		} else {
			printf(
				/* translators: 1: comment count number */
				esc_html( _nx( '%1$s Comment', '%1$s Comments', $comment_count, 'comments title', 'kadence' ) ),
				// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				number_format_i18n( $comment_count )
			);
		}
		?>
	</h2><!-- .comments-title -->

	<?php the_comments_navigation(); ?>

	<?php kadence()->the_comments(); ?>

	<?php
	if ( ! comments_open() ) {
		?>
		<p class="no-comments"><?php esc_html_e( 'Comments are closed.', 'kadence' ); ?></p>
		<?php
	}
	do_action( 'kadence_after_comments_list' );
}
