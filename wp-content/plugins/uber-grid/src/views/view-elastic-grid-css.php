<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
<style>
#pfhub_portfolio_container_<?php echo $portfolioID; ?> ul#portfolio-filter {
<?php if( $portfolioposition == 'on' ): ?> text-align: center;
<?php endif; ?>
}

#pfhub_portfolio_container_<?php echo $portfolioID; ?> ul#portfolio-filter li {
<?php if( $portfolioposition == 'on' ): ?> float: none;
<?php endif; ?>
}

#pfhub_portfolio_container_<?php echo $portfolioID; ?> ul#portfolio-filter a {
    display: inline-block;
    color: #<?php echo esc_html($pfhub_portfolio_get_options['pfhub_portfolio_view7_filter_button_font_color']); ?>;
    background-color: #<?php echo $pfhub_portfolio_get_options['pfhub_portfolio_view7_filter_button_background_color']; ?>;
    padding: <?php echo $pfhub_portfolio_get_options['pfhub_portfolio_view7_filter_button_padding']; ?>px;
    border-radius: <?php echo $pfhub_portfolio_get_options['pfhub_portfolio_view7_filter_button_radius']; ?>px;
    -moz-border-radius: <?php echo $pfhub_portfolio_get_options['pfhub_portfolio_view7_filter_button_radius']; ?>px;
    -webkit-border-radius: <?php echo $pfhub_portfolio_get_options['pfhub_portfolio_view7_filter_button_radius']; ?>px;
}

#pfhub_portfolio_container_<?php echo $portfolioID; ?> ul#portfolio-filter li.current a {
    color: #<?php echo $pfhub_portfolio_get_options['pfhub_portfolio_view7_filter_button_font_active_color']; ?> !important;
    background-color: #<?php echo $pfhub_portfolio_get_options['pfhub_portfolio_view7_filter_button_bg_color_active']; ?> !important;
}

#pfhub_portfolio_container_<?php echo $portfolioID; ?> ul#portfolio-filter li.current a:hover {
    color: #<?php echo $pfhub_portfolio_get_options['pfhub_portfolio_view7_filter_button_font_active_color']; ?> !important;
    background-color: #<?php echo $pfhub_portfolio_get_options['pfhub_portfolio_view7_filter_button_bg_color_active']; ?> !important;
}

#pfhub_portfolio_container_<?php echo $portfolioID; ?> ul#portfolio-filter a:hover {
    color: #<?php echo $pfhub_portfolio_get_options['pfhub_portfolio_view7_filter_button_font_hover_color']; ?>;
    background-color: #<?php echo $pfhub_portfolio_get_options['pfhub_portfolio_view7_filter_button_background_hover_color']; ?>;
}

#pfhub_portfolio_container_<?php echo $portfolioID; ?> #pfhub_portfolio-container-loading-overlay_<?php echo $portfolioID; ?> {
    width: 100%;
    height: 100%;
    position: absolute;
    z-index: 1;
    background: url(<?php echo PFHUB_PORTFOLIO_IMAGES_URL.'/loading/loading-'.$portfolioLoadingIconype.'.svg'; ?>) center top;
    background-repeat: no-repeat;
    background-size: 60px auto;
<?php if($portfolioShowLoading != 'on') echo 'display:none'; ?>
}

#pfhub_portfolio_container_<?php echo $portfolioID; ?> #pfhub_portfolio_content_<?php echo $portfolioID; ?> {
<?php if($portfolioShowLoading == 'on'): ?> opacity: 0;
<?php endif; ?>
}

#pfhub_portfolio_container_<?php echo $portfolioID; ?> {
    position: relative;
    display: inline-block;
    width: 100%;
}

#pfhub_portfolio_content_<?php echo $portfolioID; ?> .wagwep-container {
<?php if ( $portfolioShowFiltering != 'on' ): ?> display: none;
<?php endif; ?>
}

#pfhub_portfolio_content_<?php echo $portfolioID; ?> ul.og-grid {
    padding: 0;
<?php if( $portfolioposition == 'on' ): ?> text-align: center;
<?php endif; ?>
}

#pfhub_portfolio_content_<?php echo $portfolioID; ?> .og-grid > li {
    width: <?php echo $pfhub_portfolio_get_options['pfhub_portfolio_view7_element_width'] + 2*$pfhub_portfolio_get_options['pfhub_portfolio_view7_element_border_width']; ?>px;
    height: <?php echo $pfhub_portfolio_get_options['pfhub_portfolio_view7_element_height'] + 2*$pfhub_portfolio_get_options['pfhub_portfolio_view7_element_border_width']; ?>px;
<?php $margin = $pfhub_portfolio_get_options['pfhub_portfolio_view7_element_margin']; ?> margin: <?php echo $margin; ?>px <?php echo $margin/2; ?>px 0 <?php echo $margin/2; ?>px;
}

#pfhub_portfolio_content_<?php echo $portfolioID; ?> .og-grid > li > a {
    width: 100%;
    height: 100%;
    overflow: hidden;
    box-shadow: none;
    max-height: <?php echo $pfhub_portfolio_get_options['pfhub_portfolio_view7_element_height'] + 2*$pfhub_portfolio_get_options['pfhub_portfolio_view7_element_border_width']; ?>px;
    border: solid #<?php echo $pfhub_portfolio_get_options['pfhub_portfolio_view7_element_border_color']; ?> <?php echo $pfhub_portfolio_get_options['pfhub_portfolio_view7_element_border_width']; ?>px;
}

#pfhub_portfolio_content_<?php echo $portfolioID; ?> .og-grid li > a > img {
<?php if( $pfhub_portfolio_get_options['pfhub_portfolio_view7_image_behaviour'] == 'resize'): ?> width: 100%;
    height: 100%;
<?php endif;?> max-width: none;
    max-height: none;
}

#pfhub_portfolio_content_<?php echo $portfolioID; ?> .og-grid > li > a > figure > span {
    padding: <?php echo $pfhub_portfolio_get_options['pfhub_portfolio_view7_element_title_padding_top_bottom']; ?>px 0;
    margin: <?php echo $pfhub_portfolio_get_options['pfhub_portfolio_view7_element_title_margin_top']; ?>px 20px 20px 20px;
    color: #<?php echo $pfhub_portfolio_get_options['pfhub_portfolio_view7_element_title_font_color']; ?>;
    border-bottom: solid #<?php echo $pfhub_portfolio_get_options['pfhub_portfolio_view7_element_title_border_color']; ?> <?php echo $pfhub_portfolio_get_options['pfhub_portfolio_view7_element_title_border_width']; ?>px;
    font-size: <?php echo $pfhub_portfolio_get_options['pfhub_portfolio_view7_element_title_font_size']; ?>px;
    text-align: <?php echo $pfhub_portfolio_get_options['pfhub_portfolio_view7_element_title_align']; ?>;
}

#pfhub_portfolio_content_<?php echo $portfolioID; ?> .og-grid > li .og-pointer {
    border-bottom-color: #<?php echo $pfhub_portfolio_get_options['pfhub_portfolio_view7_expand_block_background_color']; ?>;
}

#pfhub_portfolio_content_<?php echo $portfolioID; ?> .og-grid > li > a > figure {
    background: <?php
			list($r,$g,$b) = array_map('hexdec',str_split($pfhub_portfolio_get_options['pfhub_portfolio_view7_element_overlay_background_color_'],2));
				$overlay_opacity=$pfhub_portfolio_get_options["pfhub_portfolio_view7_element_overlay_opacity"]/100;
				echo 'rgba('.$r.','.$g.','.$b.','.$overlay_opacity.') ';
	?>;
    margin: 0;
}

#pfhub_portfolio_content_<?php echo $portfolioID; ?> .og-expander {
    width: <?php echo $pfhub_portfolio_get_options[ 'pfhub_portfolio_view7_expand_width' ]; ?>%;
    background: <?php
			list($r,$g,$b) = array_map('hexdec',str_split($pfhub_portfolio_get_options['pfhub_portfolio_view7_expand_block_background_color'],2));
				$expand_opacity=$pfhub_portfolio_get_options["pfhub_portfolio_view7_expand_block_opacity"]/100;
				echo 'rgba('.$r.','.$g.','.$b.','.$expand_opacity.') ';
	?>;
<?php if( $portfolioposition == 'on' ): ?> left: 50%;
    transform: translateX(-50%);
<?php else: ?> left: 0;
<?php endif; ?>
}

#pfhub_portfolio_content_<?php echo $portfolioID; ?> .og-expander .elastislide-wrapper {
    background-color: #<?php echo $pfhub_portfolio_get_options['pfhub_portfolio_view7_thumbnail_bg_color']; ?>;
<?php if( $pfhub_portfolio_get_options['pfhub_portfolio_view7_thumbnail_block_box_shadow'] == 'on'): ?> box-shadow: inset 0 0 10px #000;
    -moz-box-shadow: inset 0 0 10px #000;
    -webkit-box-shadow: inset 0 0 10px #000;
<?php endif; ?>
    margin-bottom: 25px;
    height: 100px;
    box-sizing: content-box;
}

#pfhub_portfolio_content_<?php echo $portfolioID; ?> .og-expander ul.elastislide-list {
    padding: 0;
}

#pfhub_portfolio_content_<?php echo $portfolioID; ?> .og-expander ul.elastislide-list li {
    margin: 0 2% 5px 1%;
    max-width: none !important;
    max-height: none !important;
    width: <?php echo $pfhub_portfolio_get_options['pfhub_portfolio_view7_thumbnail_width']; ?>px !important;
    height: <?php echo $pfhub_portfolio_get_options['pfhub_portfolio_view7_thumbnail_height']; ?>px !important;
}

#pfhub_portfolio_content_<?php echo $portfolioID; ?> .og-expander ul.elastislide-list li > a > img {
    width: 100%;
    height: 100%;
    max-width: none;
    max-height: none;
    border: 2px solid #<?php echo $pfhub_portfolio_get_options['pfhub_portfolio_view7_thumbnail_bg_color']; ?>px;
}

#pfhub_portfolio_content_<?php echo $portfolioID; ?> .og-expander ul.elastislide-list li > a > img.selected {
    border: 2px solid #999999;
}

#pfhub_portfolio_content_<?php echo $portfolioID; ?> .og-expander ul.elastislide-list li > a {
    width: 100%;
    height: 100%;
}

#pfhub_portfolio_content_<?php echo $portfolioID; ?> .og-expander .og-details a.link-button {
    font-size: <?php echo $pfhub_portfolio_get_options['pfhub_portfolio_view7_expand_block_button_font_size']; ?>px;
    color: #<?php echo $pfhub_portfolio_get_options['pfhub_portfolio_view7_expand_block_button_text_color']; ?>;
    background-color: #<?php echo $pfhub_portfolio_get_options['pfhub_portfolio_view7_expand_block_button_background_color']; ?>;
    box-shadow: none;
    text-decoration: none;
}

#pfhub_portfolio_content_<?php echo $portfolioID; ?> .og-expander .og-details a.link-button:hover {
    color: #<?php echo $pfhub_portfolio_get_options['pfhub_portfolio_view7_expand_block_description_font_hover_color']; ?>;
    background: -webkit-gradient(
        linear, left top, left bottom,
        from(#<?php echo $pfhub_portfolio_get_options['pfhub_portfolio_view7_expand_block_button_background_hover_color']; ?>),
        to(#<?php echo \PfhubPortfolio\Helpers\GridHelper::adjustBrightness($pfhub_portfolio_get_options['pfhub_portfolio_view7_expand_block_button_background_hover_color'],-50);?>));
}

#pfhub_portfolio_content_<?php echo $portfolioID; ?> .og-expander .og-expander-inner {
    display: inline-block;
    width: 100%;
}

#pfhub_portfolio_content_<?php echo $portfolioID; ?> .og-expander .og-details p {
    font-size: <?php echo $pfhub_portfolio_get_options['pfhub_portfolio_view7_expand_block_description_font_size']; ?>px;
    color: #<?php echo $pfhub_portfolio_get_options['pfhub_portfolio_view7_expand_block_description_font_color']; ?>;
    text-align: <?php echo $pfhub_portfolio_get_options['pfhub_portfolio_view7_expand_block_description_text_align']; ?>;
    margin: 0;
}

#pfhub_portfolio_content_<?php echo $portfolioID; ?> .og-expander .og-close {
    z-index: 999;
}

#pfhub_portfolio_content_<?php echo $portfolioID; ?> .og-expander .og-details {
    float: right;
    height: auto;
}

#pfhub_portfolio_content_<?php echo $portfolioID; ?> .og-expander .og-details h3 {
    color: #<?php echo $pfhub_portfolio_get_options['pfhub_portfolio_view7_element_title_font_color']; ?>;
}

#pfhub_portfolio_content_<?php echo $portfolioID; ?> .og-fullimg {
    height: 100%;
    float: left;
}

#pfhub_portfolio_content_<?php echo $portfolioID; ?> .og-fullimg img {
    margin-bottom: 25px;
    width: 100%;
}

#pfhub_portfolio_content_<?php echo $portfolioID; ?> .og-fullimg iframe {
    width: 100%;
    border: none;
    height: 82%!important;
}

@media (max-width: 767px){
    #pfhub_portfolio_content_<?php echo $portfolioID; ?> .og-fullimg {
        height: auto;
    }
}

</style >
