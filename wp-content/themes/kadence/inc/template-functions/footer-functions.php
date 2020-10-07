<?php
/**
 * Calls in Templates using theme hooks.
 *
 * @package kadence
 */

namespace Kadence;

use function Kadence\kadence;
use function get_template_part;
use function add_action;

defined( 'ABSPATH' ) || exit;
/**
 * Main Call for Kadence footer
 */
function footer_markup() {
	if ( kadence()->has_footer() ) {
		get_template_part( 'template-parts/footer/base' );
	}
}

/**
 * Footer Top Row
 */
function top_footer() {
	if ( kadence()->display_footer_row( 'top' ) ) {
		kadence()->get_template( 'template-parts/footer/footer', 'row', array( 'row' => 'top' ) );
	}
}

/**
 * Footer Middle Row
 */
function middle_footer() {
	if ( kadence()->display_footer_row( 'middle' ) ) {
		kadence()->get_template( 'template-parts/footer/footer', 'row', array( 'row' => 'middle' ) );
	}
}

/**
 * Footer Bottom Row
 */
function bottom_footer() {
	if ( kadence()->display_footer_row( 'bottom' ) ) {
		kadence()->get_template( 'template-parts/footer/footer', 'row', array( 'row' => 'bottom' ) );
	}
}

/**
 * Footer Column
 *
 * @param string $row the column row.
 * @param string $column the row column.
 */
function footer_column( $row, $column ) {
	kadence()->render_footer( $row, $column );
}


/**
 * Footer HTML
 */
function footer_html() {
	$content = kadence()->option( 'footer_html_content' );
	if ( $content || is_customize_preview() ) {
		echo '<div class="footer-html">';
		kadence()->customizer_quick_link();
		echo '<div class="footer-html-inner">';
		$content = str_replace( '{copyright}', '&copy;', $content );
		$content = str_replace( '{year}', date_i18n( _x( 'Y', 'copyright date format; check date() on php.net', 'kadence' ) ), $content );
		$content = str_replace( '{site-title}', get_bloginfo( 'name' ), $content );
		// translators: %s is link to Kadence WP.
		$content = str_replace( '{theme-credit}', sprintf( __( '- WordPress Theme by %s', 'kadence' ), '<a href="https://www.kadencewp.com/" rel="nofollow noopener" target="_blank">Kadence WP</a>' ), $content );
		echo do_shortcode( wpautop( $content ) );
		echo '</div>';
		echo '</div>';
	}
}

/**
 * Desktop Navigation
 */
function footer_navigation() {
	?>
	<nav id="footer-navigation" class="footer-navigation" role="navigation" aria-label="<?php esc_attr_e( 'Footer Navigation', 'kadence' ); ?>">
		<?php kadence()->customizer_quick_link(); ?>
		<div class="footer-menu-container">
			<?php
			if ( kadence()->is_footer_nav_menu_active() ) {
				kadence()->display_footer_nav_menu( array( 'menu_id' => 'footer-menu' ) );
			} else {
				kadence()->display_fallback_menu();
			}
			?>
		</div>
	</nav><!-- #footer-navigation -->
	<?php
}

/**
 * Desktop Social
 */
function footer_social() {
	$items      = kadence()->sub_option( 'footer_social_items', 'items' );
	$title      = kadence()->option( 'footer_social_title' );
	$show_label = kadence()->option( 'footer_social_show_label' );
	$brand_colors = kadence()->option( 'footer_social_brand' );
	$brand_color_class = '';
	if ( 'onhover' === $brand_colors ) {
		$brand_color_class = ' social-show-brand-hover';
	} elseif ( 'untilhover' === $brand_colors ) {
		$brand_color_class = ' social-show-brand-until';
	} elseif ( 'always' === $brand_colors ) {
		$brand_color_class = ' social-show-brand-always';
	}
	echo '<div class="footer-social-wrap">';
	kadence()->customizer_quick_link();
	if ( ! empty( $title ) ) {
		echo '<h2 class="widget-title">' . wp_kses_post( $title ) . '</h2>';
	}
	echo '<div class="footer-social-inner-wrap social-show-label-' . ( $show_label ? 'true' : 'false' ) . ' social-style-' . esc_attr( kadence()->option( 'footer_social_style' ) ) . esc_attr( $brand_color_class ) . '">';
	if ( is_array( $items ) && ! empty( $items ) ) {
		foreach ( $items as $item ) {
			if ( $item['enabled'] ) {
				$link = kadence()->option( $item['id'] . '_link' );
				if ( 'phone' === $item['id'] ) {
					$link = 'tel:' . str_replace( 'tel:', '', $link );
				} elseif ( 'email' === $item['id'] ) {
					$link = 'mailto:' . str_replace( 'mailto:', '', $link );
				}
				echo '<a href="' . esc_attr( $link ) . '"' . esc_attr( $show_label ? '' : ' aria-label="' . $item['label'] . '"' ) . ' ' . ( 'phone' === $item['id'] || 'email' === $item['id'] ? '' : 'target="_blank" rel="noopener noreferrer"  ' ) . 'class="social-button footer-social-item social-link-' . esc_attr( $item['id'] ) . esc_attr( 'image' === $item['source'] ? ' has-custom-image' : '' ) . '">';
				if ( 'image' === $item['source'] ) {
					if ( $item['imageid'] && wp_get_attachment_image( $item['imageid'], 'full', true ) ) {
						echo wp_get_attachment_image( $item['imageid'], 'full', true, array( 'class' => 'social-icon-image', 'style' => 'max-width:' . esc_attr( $item['width'] ) . 'px' ) );
					} elseif ( ! empty( $item['url'] ) ) {
						echo '<img src="' . esc_attr( $item['url'] ) . '" alt="' . esc_attr( $item['label'] ) . '" class="social-icon-image" style="max-width:' . esc_attr( $item['width'] ) . 'px"/>';
					}
				} else {
					kadence()->print_icon( $item['icon'], '', false );
				}
				if ( $show_label ) {
					echo '<span class="social-label">' . esc_html( $item['label'] ) . '</span>';
				}
				echo '</a>';
			}
		}
	}
	echo '</div>';
	echo '</div>';
}

/**
 * Scroll To Top.
 */
function scroll_up() {
	if ( kadence()->option( 'scroll_up' ) ) {
		echo '<a id="kt-scroll-up" href="#wrapper" aria-label="' . esc_attr__( 'Scroll to top', 'kadence' ) . '" class="scroll-up-wrap scroll-ignore scroll-up-side-' . esc_attr( kadence()->option( 'scroll_up_side' ) ) . ' scroll-up-style-' . esc_attr( kadence()->option( 'scroll_up_style' ) ) . ' vs-lg-' . ( kadence()->sub_option( 'scroll_up_visiblity', 'desktop' ) ? 'true' : 'false' ) . ' vs-md-' . ( kadence()->sub_option( 'scroll_up_visiblity', 'tablet' ) ? 'true' : 'false' ) . ' vs-sm-' . ( kadence()->sub_option( 'scroll_up_visiblity', 'mobile' ) ? 'true' : 'false' ) . '">';
		kadence()->print_icon( kadence()->option( 'scroll_up_icon' ), esc_attr__( 'Scroll to top', 'kadence' ), false );
		echo '</a>';
	}
}
