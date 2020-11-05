<?php
/**
 * Kadence\BBPress\Component class
 *
 * @package kadence
 */

namespace Kadence\BBPress;

use Kadence\Component_Interface;
use function Kadence\kadence;
use function add_action;
use function add_filter;
use function add_theme_support;
use function have_posts;
use function the_post;
use function is_search;
use function get_template_part;
use function get_post_type;

/**
 * Class for adding bbpress plugin support.
 */
class Component implements Component_Interface {

	/**
	 * Gets the unique identifier for the theme component.
	 *
	 * @return string Component slug.
	 */
	public function get_slug() : string {
		return 'bbpress';
	}

	/**
	 * Adds the action and filter hooks to integrate with WordPress.
	 */
	public function initialize() {
		add_action( 'wp_enqueue_scripts', array( $this, 'bbpress_styles' ), 60 );
		//add_filter( 'kadence_post_layout', array( $this, 'bbpress_layout' ), 99 );
		add_filter( 'post_class', array( $this, 'post_class' ), 10, 3 );
		add_filter( 'bbp_get_reply_admin_links', array( $this, 'admin_reply_links' ), 10, 3 );
		add_filter( 'bbp_get_topic_admin_links', array( $this, 'admin_reply_links' ), 10, 3 );
		add_action( 'kadence_single_after_entry_header', array( $this, 'bbpress_single_meta' ) );
	}
	/**
	 * Filters admin links output.
	 *
	 * @param string $retval A string of the output.
	 * @param array  $r   An array of the output.
	 * @param array  $args The args.
	 */
	public function admin_reply_links( $retval, $r, $args ) {
		$retval = '<div class="bbpress-admin-settings-container">' . kadence()->get_icon( 'settings', __( 'Settings', 'kadence' ), false ) . $retval . '</div>';
		return $retval;
	}
	/**
	 * Filters the class for bbpress.
	 *
	 * @param array $classes An array of post class names.
	 * @param array $class   An array of additional class names added to the post.
	 * @param int    $post_id The post ID..
	 */
	public function post_class( $classes, $class, $post_ID ) {
		if ( is_admin() ) {
			return $classes;
		}
		if ( is_bbpress() && 'topic' === get_post_type() ) {
			// $post = get_post( $post_id );
			// if ( 'topic' === $post->post_type ) {
				//print_r( $classes );
			$entry = array_search( 'entry', $classes );
			if ( is_numeric( $entry ) ) {
				unset( $classes[ $entry ] );
			}
			$bg = array_search( 'content-bg', $classes );
			if ( is_numeric( $bg ) ) {
				unset( $classes[ $bg ] );
			}
			$single = array_search( 'single-entry', $classes );
			if ( is_numeric( $single ) ) {
				unset( $classes[ $single ] );
			}
			//}
		}
		return $classes;
	}
	/**
	 * Renders the layout for bbpress.
	 *
	 * @param array $layout the layout array.
	 */
	public function bbpress_layout( $layout ) {
		if ( is_bbpress() ) {
			$layout = wp_parse_args(
				array(
					'feature'          => 'hide',
					'comments'         => 'hide',
					'navigation'       => 'hide',
					'content'          => 'enable',
				),
				$layout
			);
		}

		return $layout;
	}
	/**
	 * Add some css styles for learndash
	 */
	public function bbpress_styles() {
		if ( is_bbpress() ) {
			wp_enqueue_style( 'kadence-bbpress', get_theme_file_uri( '/assets/css/bbpress.min.css' ), array(), KADENCE_VERSION );
		}
	}
	/**
	 * Add meta below title.
	 */
	public function bbpress_single_meta() {
		if ( 'topic' === get_post_type() ) {
			echo '<div class="bbpress-topic-meta entry-meta">';
			echo '<span class="bbpress-back-to-forum-wrap"><a href="' . esc_url( bbp_get_forum_permalink( bbp_get_topic_forum_id() ) ) . '" class="bbpress-back-to-forum">' . kadence()->get_icon( 'arrow-left-alt', '', true ) . ' ' . __( 'Back to:', 'kadence' ) . ' ' . bbp_get_forum_title( bbp_get_topic_forum_id() ) . '</a></span>';
			echo '<span class="bbpress-meta-replies-wrap"><span class="bbpress-meta-replies">' . bbp_get_topic_reply_count() . ' ' . esc_html__( 'Replies', 'kadence' ) . '</span></span>';
			echo '</div>';
		}
		if ( 'forum' === get_post_type() ) {
			echo '<div class="bbpress-topic-meta entry-meta">';
			bbp_breadcrumb();
			// echo '<span class="bbpress-back-to-forum-wrap"><a href="' . esc_url( bbp_get_forum_permalink( bbp_get_topic_forum_id() ) ) . '" class="bbpress-back-to-forum">' . kadence()->get_icon( 'arrow-left-alt', '', true ) . ' ' . __( 'Back to:', 'kadence' ) . ' ' . bbp_get_forum_title( bbp_get_topic_forum_id() ) . '</a></span>';
			// echo '<span class="bbpress-meta-replies-wrap"><span class="bbpress-meta-replies">' . bbp_get_topic_reply_count() . ' ' . esc_html__( 'Replies', 'kadence' ) . '</span></span>';
			echo '</div>';
		}
	}
}
