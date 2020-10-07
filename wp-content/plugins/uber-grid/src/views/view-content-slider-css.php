
<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?><style>
    /***For Content Slider view***/
#p-main-slider_<?php echo $portfolioID; ?> .play-icon.youtube-icon  {
    background: url('<?php echo  PFHUB_PORTFOLIO_IMAGES_URL.'/admin/play.youtube.png';?>') center center no-repeat;
    background-size: 30% 30%;
}
#p-main-slider_<?php echo $portfolioID; ?> .play-icon.vimeo-icon  {
    background: url('<?php echo  PFHUB_PORTFOLIO_IMAGES_URL.'/admin/play.vimeo.png';?>') center center no-repeat;
    background-size: 30% 30%;
}
#p-main-slider_<?php echo $portfolioID; ?> .play-icon {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
}
#p-main-slider_<?php echo $portfolioID; ?>  .add-H-relative {
    position: relative;
}
#p-main-slider_<?php echo $portfolioID; ?>  .add-H-block {
    display: block;
}
/***</add>***/
#p-main-slider_<?php echo $portfolioID; ?>-wrapper .ls-nav { display: none; }
#p-main-slider_<?php echo $portfolioID; ?> {background:#<?php echo $pfhub_portfolio_get_options["pfhub_portfolio_view5_slider_background_color"];?>;}

#p-main-slider_<?php echo $portfolioID; ?> div.slider-content {
    position:relative;
    width:100%;
    padding:0 0 0 0;
    position:relative;
    background:#<?php echo $pfhub_portfolio_get_options["pfhub_portfolio_view5_slider_background_color"];?>;
}



[class$="-arrow"] {
    background-image:url('<?php echo PFHUB_PORTFOLIO_IMAGES_URL.'/admin/arrow.'.$pfhub_portfolio_get_options["pfhub_portfolio_view5_icons_style"].'.png';?>') !important;
}

.ls-select-box {
    background:url('<?php echo PFHUB_PORTFOLIO_IMAGES_URL.'/admin/menu.'.$pfhub_portfolio_get_options["pfhub_portfolio_view5_icons_style"].'.png';?>') right center no-repeat #<?php echo $pfhub_portfolio_get_options["pfhub_portfolio_view5_slider_background_color"];?> !important;
}

#p-main-slider_<?php echo $portfolioID; ?>-nav-select {
    color:#<?php echo $pfhub_portfolio_get_options["pfhub_portfolio_view5_title_font_color"];?>;
}

#p-main-slider_<?php echo $portfolioID; ?> div.slider-content .slider-content-wrapper {
    position:relative;
    width:100%;
    padding:0;
    display:block;
}

#p-main-slider_<?php echo $portfolioID; ?> .slider-content-wrapper .image-block_<?php echo $portfolioID; ?> {
    width:<?php echo $pfhub_portfolio_get_options["pfhub_portfolio_view5_main_image_width"];?>px;
    display:inline-block;
    padding:0 10px 0 0;
    float:left;
}

#p-main-slider_<?php echo $portfolioID; ?> .slider-content-wrapper .image-block_<?php echo $portfolioID; ?> a{
    display: inline-block;
    width: 100%;
    height: 100%;
}

#p-main-slider_<?php echo $portfolioID; ?> .slider-content-wrapper .image-block_<?php echo $portfolioID; ?> img.main-image {
    position:relative;
    width:100%;
    height:auto;
    display:block;
}

#p-main-slider_<?php echo $portfolioID; ?> .slider-content-wrapper .image-block_<?php echo $portfolioID; ?> ul.thumbs-list_<?php echo $portfolioID; ?> {
    list-style:none;
    display:table;
    position:relative;
    clear:both;
    width:100%;
    margin:10px 0 0 0;
    padding:0;
    clear:both;
}

#p-main-slider_<?php echo $portfolioID; ?> .slider-content-wrapper .image-block_<?php echo $portfolioID; ?> ul.thumbs-list_<?php echo $portfolioID; ?> li {
    display:block;
    float:left;
    width:<?php echo $pfhub_portfolio_get_options["pfhub_portfolio_view5_thumbs_width"];?>px;
    height:<?php echo $pfhub_portfolio_get_options["pfhub_portfolio_view5_thumbs_height"];?>px;
    margin:0 2% 5px 1%;
    opacity:0.45;
}

#p-main-slider_<?php echo $portfolioID; ?> .slider-content-wrapper .image-block_<?php echo $portfolioID; ?> ul.thumbs-list_<?php echo $portfolioID; ?> li.active,#p-main-slider_<?php echo $portfolioID; ?> .slider-content-wrapper .image-block_<?php echo $portfolioID; ?> ul.thumbs-list_<?php echo $portfolioID; ?> li:hover {
    opacity:1;
}

#p-main-slider_<?php echo $portfolioID; ?> .slider-content-wrapper .image-block_<?php echo $portfolioID; ?> ul.thumbs-list_<?php echo $portfolioID; ?> li a {
    border: none;
    display:block;
}

#p-main-slider_<?php echo $portfolioID; ?> .slider-content-wrapper .image-block_<?php echo $portfolioID; ?> ul.thumbs-list_<?php echo $portfolioID; ?> li img {
    margin:0 !important;
    padding:0 !important;
    width:<?php echo $pfhub_portfolio_get_options["pfhub_portfolio_view5_thumbs_width"];?>px !important;
    height:<?php echo $pfhub_portfolio_get_options["pfhub_portfolio_view5_thumbs_height"];?>px !important;
}

#p-main-slider_<?php echo $portfolioID; ?> .slider-content-wrapper .right-block {
    display:inline-block;
    width: calc(100% - <?php echo $pfhub_portfolio_get_options["pfhub_portfolio_view5_main_image_width"] + 10;?>px);
}

#p-main-slider_<?php echo $portfolioID; ?> .slider-content-wrapper .right-block > div {
    padding-bottom:10px;
    margin-top:10px;
<?php if($pfhub_portfolio_get_options['pfhub_portfolio_view5_show_separator_lines']=="on") {?>
    background:url('<?php echo PFHUB_PORTFOLIO_IMAGES_URL.'/admin/divider.line.png'; ?>') center bottom repeat-x;
<?php } ?>
}
#p-main-slider_<?php echo $portfolioID; ?> .slider-content-wrapper .right-block > div:last-child {background:none;}


#p-main-slider_<?php echo $portfolioID; ?> .slider-content-wrapper .right-block .title {
    position:relative;
    display:block;
    margin:-10px 0 0 0;
    font-size:<?php echo $pfhub_portfolio_get_options["pfhub_portfolio_view5_title_font_size"];?>px !important;
    line-height:<?php echo $pfhub_portfolio_get_options["pfhub_portfolio_view5_title_font_size"]+4;?>px !important;
    color:#<?php echo $pfhub_portfolio_get_options["pfhub_portfolio_view5_title_font_color"];?>;
}

#p-main-slider_<?php echo $portfolioID; ?> .slider-content-wrapper .right-block .description {
    clear:both;
    position:relative;
    text-align:justify;
    font-size:<?php echo $pfhub_portfolio_get_options["pfhub_portfolio_view5_description_font_size"];?>px !important;
    line-height:<?php echo $pfhub_portfolio_get_options["pfhub_portfolio_view5_description_font_size"]+4;?>px !important;
    color:#<?php echo $pfhub_portfolio_get_options["pfhub_portfolio_view5_description_color"];?>;
}

#p-main-slider_<?php echo $portfolioID; ?> .slider-content-wrapper .right-block .description h1,
#p-main-slider_<?php echo $portfolioID; ?> .slider-content-wrapper .right-block .description h2,
#p-main-slider_<?php echo $portfolioID; ?> .slider-content-wrapper .right-block .description h3,
#p-main-slider_<?php echo $portfolioID; ?> .slider-content-wrapper .right-block .description h4,
#p-main-slider_<?php echo $portfolioID; ?> .slider-content-wrapper .right-block .description h5,
#p-main-slider_<?php echo $portfolioID; ?> .slider-content-wrapper .right-block .description h6,
#p-main-slider_<?php echo $portfolioID; ?> .slider-content-wrapper .right-block .description p,
#p-main-slider_<?php echo $portfolioID; ?> .slider-content-wrapper .right-block .description strong,
#p-main-slider_<?php echo $portfolioID; ?> .slider-content-wrapper .right-block .description span {
    padding:2px !important;
    margin:0 !important;
}

#p-main-slider_<?php echo $portfolioID; ?> .slider-content-wrapper .right-block .description ul,
#p-main-slider_<?php echo $portfolioID; ?> .slider-content-wrapper .right-block .description li {
    padding:2px 0 2px 5px;
    margin:0 0 0 8px;
}



#p-main-slider_<?php echo $portfolioID; ?> .slider-content-wrapper .button-block {
    position:relative;
}

#p-main-slider_<?php echo $portfolioID; ?> .slider-content-wrapper .button-block a,#p-main-slider_<?php echo $portfolioID; ?> .slider-content-wrapper .button-block a:link,#p-main-slider_<?php echo $portfolioID; ?> .slider-content-wrapper .button-block a:visited{
    position:relative;
    display:inline-block;
    padding:6px 12px;
    background:#<?php echo $pfhub_portfolio_get_options["pfhub_portfolio_view5_linkbutton_background_color"];?>;
    color:#<?php echo $pfhub_portfolio_get_options["pfhub_portfolio_view5_linkbutton_color"];?>;
    font-size:<?php echo $pfhub_portfolio_get_options["pfhub_portfolio_view5_linkbutton_font_size"];?>px;
    text-decoration:none;
    border:none;
}

#p-main-slider_<?php echo $portfolioID; ?> .slider-content-wrapper .button-block a:hover,#p-main-slider_<?php echo $portfolioID; ?> .slider-content-wrapper .button-block a:focus,#p-main-slider_<?php echo $portfolioID; ?> .slider-content-wrapper .button-block a:active {
    background:#<?php echo $pfhub_portfolio_get_options["pfhub_portfolio_view5_linkbutton_background_hover_color"];?>;
    color:#<?php echo $pfhub_portfolio_get_options["pfhub_portfolio_view5_linkbutton_font_hover_color"];?>;
}

@media only screen and (min-width:500px) {
    #main-slider-nav-ul {
        visibility:hidden !important;
        height:1px;
    }
}

@media only screen and (max-width:500px) {
    #p-main-slider_<?php echo $portfolioID; ?> .slider-content-wrapper .image-block_<?php echo $portfolioID; ?>,#p-main-slider_<?php echo $portfolioID; ?> .slider-content-wrapper .right-block {
        width:100%;
        display:block;
        float:none;
        clear:both;
    }
}
@media only screen and (max-width: 2000px) and (min-width: 500px) {
    #p-main-slider_<?php echo $portfolioID; ?> .slider-content-wrapper .image-block_<?php echo $portfolioID; ?>,#p-main-slider_<?php echo $portfolioID; ?> .slider-content-wrapper .right-block {
        width:100%;
        display:block;
        float:none;
        clear:both;
    }
</style>
