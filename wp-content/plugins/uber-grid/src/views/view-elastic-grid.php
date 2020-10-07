<?php
/**
 * @var $portfolioID int
 * @var $view_slug string
 * @var $portfolioShowLoading
 * @var $pfhub_portfolio_get_options
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
<section id="pfhub_portfolio_container_<?php echo esc_attr($portfolioID); ?>" class="pfhub_portfolio_elastic_grid_content" data-object-name="<?php echo "images_obj_".esc_attr($portfolioID); ?>">
	<div id="pfhub_portfolio-container-loading-overlay_<?php echo esc_attr($portfolioID); ?>"></div>
	<div id="pfhub_portfolio_content_<?php echo esc_attr($portfolioID); ?>"
			 class="portfolio-gallery-content elastic_grid view-<?php echo esc_attr($view_slug) ?>"
		 	 data-show-loading="<?php echo esc_attr($portfolioShowLoading); ?>"
			 data-image-behaviour="<?php echo esc_attr($pfhub_portfolio_get_options['pfhub_portfolio_view7_image_behaviour']); ?>">

	</div>
</section>
