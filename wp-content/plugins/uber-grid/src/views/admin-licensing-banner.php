<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
?>
<div class="free_version_banner" <?php if (isset($_COOKIE['portfolioGalleryBannerShow']) && $_COOKIE['portfolioGalleryBannerShow'] == "no") echo 'style="display:none"'; ?> >


    <div class="hub_image_block">
        <a href="https;//portfoliohub.io/"><img src="https://ps.w.org/uber-grid/assets/icon-128x128.png?rev=2210988" alt="PortfolioHub"></a>
    </div>
    <div class="hub_content_block">
        <div class="description_text"><p>You are using the free version of Portfolio Gallery. To upgrade the plugin with more functions and advanced features click on “Enable Pro Version” button.</p></div>
        <div class="pfhub_portfolio_view_plugins_block">
            <a class="get_full_version" href="https://portfoliohub.io" target="_blank">Enable Pro Version</a>
        </div>
    </div>

    <ul class="company_links">
        <li><a target="_blank" href="https://wordpress.org/support/plugin/uber-grid/reviews/">Rate Us</a></li>
        <li><a target="_blank" href="https://portfoliohub.io/demo2/">See Demo</a></li>
        <li><a target="_blank" href="https://wordpress.org/support/plugin/uber-grid">Contact Us</a></li>
    </ul>
    <a class="close_free_banner">+</a>

    <div style="clear: both;"></div>
</div>
