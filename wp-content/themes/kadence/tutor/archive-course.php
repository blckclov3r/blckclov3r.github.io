<?php
/**
 * NOTE: This template is from the TutorLMS plugin is is overridden in Kadence Theme for better theme support of TutorLMS.
 * Template for displaying courses
 *
 * Edited by Kadence Theme, Original Author:
 * @author Themeum
 * @url https://themeum.com
 *
 * @package TutorLMS/Templates
 * @version 1.5.8
 */

get_header(); ?>
	<?php do_action('tutor_course/archive/before/wrap'); ?>
	<div class="<?php tutor_container_classes() ?>">
		<?php
		do_action('tutor_course/archive/before_loop');

		if ( have_posts() ) :
			/* Start the Loop */

			tutor_course_loop_start();

			while ( have_posts() ) : the_post();
				/**
				 * @hook tutor_course/archive/before_loop_course
				 * @type action
				 * Usage Idea, you may keep a loop within a wrap, such as bootstrap col
				 */
				do_action('tutor_course/archive/before_loop_course');

				tutor_load_template('loop.course');

				/**
				 * @hook tutor_course/archive/after_loop_course
				 * @type action
				 * Usage Idea, If you start any div before course loop, you can end it here, such as </div>
				 */
				do_action('tutor_course/archive/after_loop_course');
			endwhile;

			tutor_course_loop_end();

		else :

			/**
			 * No course found
			 */
			tutor_load_template('course-none');

		endif;

		tutor_course_archive_pagination();

		do_action('tutor_course/archive/after_loop');
		?>
	</div><!-- .wrap -->

	<?php do_action('tutor_course/archive/after/wrap'); ?>

<?php get_footer();