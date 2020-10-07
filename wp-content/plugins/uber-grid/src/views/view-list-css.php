<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
<style>
.portelement_<?php echo $portfolioID; ?> .play-icon.youtube-icon  {
    background: url(<?php echo  PFHUB_PORTFOLIO_IMAGES_URL.'/admin/play.youtube.png';?>) center center no-repeat;
    background-size: 30% 30%;
}
.portelement_<?php echo $portfolioID; ?> .play-icon.vimeo-icon  {
    background: url(<?php echo  PFHUB_PORTFOLIO_IMAGES_URL.'/admin/play.vimeo.png';?>) center center no-repeat;
    background-size: 30% 30%;
}
.portelement_<?php echo $portfolioID; ?> .play-icon {
    position: absolute;
    top: 0px;
    left: 0px;
    width: 100%;
    height: 100%;
}
.add-H-relative {
    position: relative;
}
.add-H-block {
    display: block;
    border: none !important;
    box-shadow: none;

}
/***</add>***/
.portelement_<?php echo $portfolioID; ?> {
    position: relative;
    width: calc(96% - <?php echo 2*$pfhub_portfolio_get_options['pfhub_portfolio_view3_element_border_width']; ?>px);
    margin:5px 0px 5px 0px;
    padding:2%;
    clear:both;
    overflow: hidden;
    border:<?php echo $pfhub_portfolio_get_options['pfhub_portfolio_view3_element_border_width']; ?>px solid #<?php echo $pfhub_portfolio_get_options['pfhub_portfolio_view3_element_border_color']; ?>;
    background:#<?php echo $pfhub_portfolio_get_options['pfhub_portfolio_view3_element_background_color']; ?>;
}

.portelement_<?php echo $portfolioID; ?> div.left-block_<?php echo $portfolioID; ?> {
    padding-right: 10px;
    display: inline-block;
    float: left;
}

.portelement_<?php echo $portfolioID; ?> div.left-block_<?php echo $portfolioID; ?> .main-image-block_<?php echo $portfolioID; ?> {
    clear:both;
    width:<?php echo $pfhub_portfolio_get_options['pfhub_portfolio_view3_mainimage_width']; ?>px;
}

.portelement_<?php echo $portfolioID; ?> div.left-block_<?php echo $portfolioID; ?> .main-image-block_<?php echo $portfolioID; ?> img {
    margin:0px !important;
    padding:0px !important;
    width:<?php echo $pfhub_portfolio_get_options['pfhub_portfolio_view3_mainimage_width']; ?>px !important;
    height:auto;
}

.portelement_<?php echo $portfolioID; ?> div.left-block_<?php echo $portfolioID; ?> .thumbs-block {
    position:relative;
    margin-top:10px;
    display: inline-block;
}

.portelement_<?php echo $portfolioID; ?> div.left-block_<?php echo $portfolioID; ?> .thumbs-block .thumbs-list_<?php echo $portfolioID; ?>{
    padding: 0 !important;
}

.portelement_<?php echo $portfolioID; ?> div.left-block_<?php echo $portfolioID; ?> .thumbs-block ul {
    width:<?php echo $pfhub_portfolio_get_options['pfhub_portfolio_view3_mainimage_width']; ?>px;
    height:auto;
    display:table;
    margin:0px;
    padding:0px;
    list-style:none;
}

.portelement_<?php echo $portfolioID; ?> div.left-block_<?php echo $portfolioID; ?> .thumbs-block ul li {
    margin:2px 3px 0px 2px;
    padding:0px;
    width:<?php echo $pfhub_portfolio_get_options['pfhub_portfolio_view3_thumbs_width']; ?>px;
    height:<?php echo $pfhub_portfolio_get_options['pfhub_portfolio_view3_thumbs_height']; ?>px;
    float:left;
}

.portelement_<?php echo $portfolioID; ?> div.left-block_<?php echo $portfolioID; ?> .thumbs-block ul li a {
    display:block;
    width:<?php echo $pfhub_portfolio_get_options['pfhub_portfolio_view3_thumbs_width']; ?>px;
    height:<?php echo $pfhub_portfolio_get_options['pfhub_portfolio_view3_thumbs_height']; ?>px;
    border: none;
    box-shadow: none;
}

.portelement_<?php echo $portfolioID; ?> div.left-block_<?php echo $portfolioID; ?> .thumbs-block ul li a img {
    margin:0px !important;
    padding:0px !important;
    width:<?php echo $pfhub_portfolio_get_options['pfhub_portfolio_view3_thumbs_width']; ?>px;
    height:<?php echo $pfhub_portfolio_get_options['pfhub_portfolio_view3_thumbs_height']; ?>px;
}

.portelement_<?php echo $portfolioID; ?> div.right-block {
    vertical-align:top;
    float: right;
    display: inline-block;
    width: calc(96% - <?php echo $pfhub_portfolio_get_options['pfhub_portfolio_view3_mainimage_width']; ?>px);
}

.portelement_<?php echo $portfolioID; ?> div.right-block > div {
    width:100%;
    padding-bottom:10px;
    margin-top:10px;
<?php if($pfhub_portfolio_get_options['pfhub_portfolio_view3_show_separator_lines']=="on") {?>
    background:url('<?php echo PFHUB_PORTFOLIO_IMAGES_URL.'/admin/divider.line.png'; ?>') center bottom repeat-x;
<?php } ?>
}

.portelement_<?php echo $portfolioID; ?> div.right-block > div:last-child {
    background:none;
}

.portelement_<?php echo $portfolioID; ?> div.right-block .title-block_<?php echo $portfolioID; ?>  {
    margin-top:3px;
}

.portelement_<?php echo $portfolioID; ?> div.right-block .title-block_<?php echo $portfolioID; ?> h3 {
    margin:0px;
    padding:0px;
    font-weight:normal;
    font-size:<?php echo $pfhub_portfolio_get_options['pfhub_portfolio_view3_title_font_size']; ?>px !important;
    line-height:<?php echo $pfhub_portfolio_get_options['pfhub_portfolio_view3_title_font_size']+4; ?>px !important;
    color:#<?php echo $pfhub_portfolio_get_options['pfhub_portfolio_view3_title_font_color']; ?>;
}

.portelement_<?php echo $portfolioID; ?> div.right-block .description-block_<?php echo $portfolioID; ?> p,.portelement_<?php echo $portfolioID; ?> div.right-block .description-block_<?php echo $portfolioID; ?>  {
    margin:0px;
    padding:0px;
    font-size:<?php echo $pfhub_portfolio_get_options['pfhub_portfolio_view3_description_font_size']; ?>px;
    color:#<?php echo $pfhub_portfolio_get_options['pfhub_portfolio_view3_description_color']; ?>;
    text-align: justify;
}


.portelement_<?php echo $portfolioID; ?> div.right-block .description-block_<?php echo $portfolioID; ?> h1,
.portelement_<?php echo $portfolioID; ?> div.right-block .description-block_<?php echo $portfolioID; ?> h2,
.portelement_<?php echo $portfolioID; ?> div.right-block .description-block_<?php echo $portfolioID; ?> h3,
.portelement_<?php echo $portfolioID; ?> div.right-block .description-block_<?php echo $portfolioID; ?> h4,
.portelement_<?php echo $portfolioID; ?> div.right-block .description-block_<?php echo $portfolioID; ?> h5,
.portelement_<?php echo $portfolioID; ?> div.right-block .description-block_<?php echo $portfolioID; ?> h6,
.portelement_<?php echo $portfolioID; ?> div.right-block .description-block_<?php echo $portfolioID; ?> p,
.portelement_<?php echo $portfolioID; ?> div.right-block .description-block_<?php echo $portfolioID; ?> strong,
.portelement_<?php echo $portfolioID; ?> div.right-block .description-block_<?php echo $portfolioID; ?> span {
    padding:2px !important;
    margin:0px !important;
}

.portelement_<?php echo $portfolioID; ?> div.right-block .description-block_<?php echo $portfolioID; ?> ul,
.portelement_<?php echo $portfolioID; ?> div.right-block .description-block_<?php echo $portfolioID; ?> li {
    padding:2px 0px 2px 5px;
    margin:0px 0px 0px 8px;
}

.portelement_<?php echo $portfolioID; ?> .button-block {
    position:relative;
}

.portelement_<?php echo $portfolioID; ?> div.right-block .button-block a,.portelement_<?php echo $portfolioID; ?> div.right-block .button-block a:link,.portelement_<?php echo $portfolioID; ?> div.right-block .button-block a:visited {
    position:relative;
    display:inline-block;
    padding:6px 12px;
    background:#<?php echo $pfhub_portfolio_get_options["pfhub_portfolio_view3_linkbutton_background_color"];?>;
    color:#<?php echo $pfhub_portfolio_get_options["pfhub_portfolio_view3_linkbutton_color"];?>;
    font-size:<?php echo $pfhub_portfolio_get_options["pfhub_portfolio_view3_linkbutton_font_size"];?>px;
    text-decoration:none;
    border:none;
}

.portelement_<?php echo $portfolioID; ?> div.right-block .button-block a:hover,.pupup-elemen.element div.right-block .button-block a:focus,.portelement_<?php echo $portfolioID; ?> div.right-block .button-block a:active {
    background:#<?php echo $pfhub_portfolio_get_options["pfhub_portfolio_view3_linkbutton_background_hover_color"];?>;
    color:#<?php echo $pfhub_portfolio_get_options["pfhub_portfolio_view3_linkbutton_font_hover_color"];?>;
    border:none;
}

#pfhub_portfolio_content_<?php echo $portfolioID; ?> #pfhub_portfolio_options_<?php echo $portfolioID; ?> {
    position: relative;
    overflow: hidden;
<?php   if($sortingFloatFullWidth != 'top'){
            echo 'float:'.$sortingFloatFullWidth.';margin-top:5px;';
            echo  "max-width:180px;width:20%;display:inline-block;";
            if($filteringFloatFullWidth == 'top') echo 'margin-top:45px;';
            if($sortingFloatFullWidth == 'left') echo 'margin-right: 1%;';
            else echo 'margin-left:1%;';
        }
        else {
            if($portfolioposition == 'on' && ($filteringFloatFullWidth == 'top' || $filteringFloatFullWidth == '')) echo 'left:50%; transform:translateX(-50%);';
            if($filteringFloatFullWidth == 'left') echo 'margin-left:calc( 185px + 1%);';
            echo 'width: auto; margin-bottom: 5px;display:table;';
        }
        if(($sortingFloatFullWidth == 'left' && $filteringFloatFullWidth == 'left') || ($sortingFloatFullWidth == 'right' && $filteringFloatFullWidth == 'right')){
            echo 'width: 100%;';
        }
?>

<?php
    if($portfolioShowLoading == 'on') echo 'opacity: 0;';
?>
    margin-bottom: 10px;
}

#pfhub_portfolio_content_<?php echo $portfolioID; ?> #pfhub_portfolio_options_<?php echo $portfolioID; ?> ul {
    margin: 0px !important;
    padding: 0px !important;
    list-style: none;
<?php if($sortingFloatFullWidth == 'top') {
        echo "float:left;margin-left:1%;";
      }
?>
}

#pfhub_portfolio_content_<?php echo $portfolioID; ?> #pfhub_portfolio_filters_<?php echo $portfolioID; ?> ul {
    margin: 0px !important;
    padding: 0px !important;
    overflow: hidden;
<?php if($filteringFloatFullWidth == 'top') {
    echo "float:left;margin-left:1%;";
    } ?>
    width: 100%;
}

<?php if($pfhub_portfolio_get_options["pfhub_portfolio_view3_sorting_float"] == 'none') { ?>
#pfhub_portfolio_content_<?php echo $portfolioID; ?> #pfhub_portfolio_options_<?php echo $portfolioID; ?> ul {
    float: left;
}
<?php } ?>

#pfhub_portfolio_content_<?php echo $portfolioID; ?> #pfhub_portfolio_options_<?php echo $portfolioID; ?> ul li {
    border-radius: <?php echo $pfhub_portfolio_get_options["pfhub_portfolio_view3_sortbutton_border_radius"];?>px;
    list-style-type: none;
    margin: 0px !important;
    padding: 0;
<?php
    if($sortingFloatFullWidth == "top")
    { echo "float:left !important;margin: 0px 8px 4px 0px !important;"; }
    if($sortingFloatFullWidth == "left" || $sortingFloatFullWidth == "right")
    { echo 'border-bottom: 1px solid #ccc;'; }
    else
    { echo 'border: 1px solid #ccc;'; }
?>
}

#pfhub_portfolio_content_<?php echo $portfolioID; ?> #pfhub_portfolio_options_<?php echo $portfolioID; ?> ul li a {
    background-color: #<?php echo $pfhub_portfolio_get_options["pfhub_portfolio_view3_sortbutton_background_color"];?> !important;
    border-radius: <?php echo $pfhub_portfolio_get_options["pfhub_portfolio_view3_sortbutton_border_radius"];?>px;
    font-size:<?php echo $pfhub_portfolio_get_options["pfhub_portfolio_view3_sortbutton_font_size"];?>px !important;
    color:#<?php echo $pfhub_portfolio_get_options["pfhub_portfolio_view3_sortbutton_font_color"];?> !important;
    text-decoration: none;
    cursor: pointer;
    margin: 0px !important;
    display: block;
    padding:<?php echo $pfhub_portfolio_get_options["pfhub_portfolio_view3_sortbutton_border_padding"];?>px;
}

/*#pfhub_portfolio_content_<?php echo $portfolioID; ?> #pfhub_portfolio_options_<?php echo $portfolioID; ?> ul li:hover {

}*/

#pfhub_portfolio_content_<?php echo $portfolioID; ?> #pfhub_portfolio_options_<?php echo $portfolioID; ?> ul li a:hover {
    background-color: #<?php echo $pfhub_portfolio_get_options["pfhub_portfolio_view3_sortbutton_hover_background_color"];?> !important;
    color:#<?php echo $pfhub_portfolio_get_options["pfhub_portfolio_view3_sortbutton_hover_font_color"];?> !important;
    cursor: pointer;
}

#pfhub_portfolio_content_<?php echo $portfolioID; ?> #pfhub_portfolio_filters_<?php echo $portfolioID; ?> {
    position: relative;
    overflow: hidden;
<?php   if($filteringFloatFullWidth != 'top'){
            echo 'float:'.$filteringFloatFullWidth.';margin-top:5px;';
            echo  "max-width:180px;width:20%;display:inline-block;";
            if($filteringFloatFullWidth == 'left') echo 'margin-right: 1%;';
            else echo 'margin-left:1%;';
        }
        else {
            if($portfolioposition == 'on' && ($sortingFloatFullWidth == 'top' || $sortingFloatFullWidth == '')) echo 'left:50%; transform:translateX(-50%);';
            if($sortingFloatFullWidth == 'left') echo 'margin-left:calc( 185px + 1%);';
            echo 'width: auto; margin-bottom: 5px;display:table;';
        }
        if(($sortingFloatFullWidth == 'left' && $filteringFloatFullWidth == 'left') || ($sortingFloatFullWidth == 'right' && $filteringFloatFullWidth == 'right')){
            echo 'width: 100%;';
        }
?>

<?php if ($pfhub_portfolio_get_options["pfhub_portfolio_view2_show_sorting"] == 'off')
    echo "display:none;";
    if($portfolioShowLoading == 'on') echo 'opacity: 0;';
?>
}

#pfhub_portfolio_content_<?php echo $portfolioID; ?> #pfhub_portfolio_filters_<?php echo $portfolioID; ?> ul li {
    border-radius: <?php echo $pfhub_portfolio_get_options["pfhub_portfolio_view3_sortbutton_border_radius"];?>px;
    list-style-type: none;
<?php
    if($filteringFloatFullWidth == "top") { echo "float:left !important;margin: 0px 8px 4px 0px !important;"; }
    if($filteringFloatFullWidth == "left" || $filteringFloatFullWidth == "right")
    { echo 'border-bottom: 1px solid #ccc;'; }
    else echo "border: 1px solid #ccc;";
?>
}

#pfhub_portfolio_content_<?php echo $portfolioID; ?> #pfhub_portfolio_filters_<?php echo $portfolioID; ?> ul li a {
    font-size:<?php echo $pfhub_portfolio_get_options["pfhub_portfolio_view3_filterbutton_font_size"];?>px !important;
    color:#<?php echo $pfhub_portfolio_get_options["pfhub_portfolio_view3_filterbutton_font_color"];?> !important;
    background-color: #<?php echo $pfhub_portfolio_get_options["pfhub_portfolio_view3_filterbutton_background_color"];?> !important;
    border-radius: <?php echo $pfhub_portfolio_get_options["pfhub_portfolio_view3_filterbutton_border_radius"];?>px;
    padding:<?php echo $pfhub_portfolio_get_options["pfhub_portfolio_view3_filterbutton_border_padding"];?>px;
    display: block;
    text-decoration: none;
}

#pfhub_portfolio_content_<?php echo $portfolioID; ?> #pfhub_portfolio_filters_<?php echo $portfolioID; ?>  ul li a:hover {
    color:#<?php echo $pfhub_portfolio_get_options["pfhub_portfolio_view3_filterbutton_hover_font_color"];?> !important;
    background-color: #<?php echo $pfhub_portfolio_get_options["pfhub_portfolio_view3_filterbutton_hover_background_color"];?> !important;
    cursor: pointer;
}
#pfhub_portfolio_content_<?php echo $portfolioID; ?> #pfhub_portfolio_filters_<?php echo $portfolioID; ?> ul li.active a,
#pfhub_portfolio_content_<?php echo $portfolioID; ?> #pfhub_portfolio_filters_<?php echo $portfolioID; ?> ul li.active a:link,
#pfhub_portfolio_content_<?php echo $portfolioID; ?> #pfhub_portfolio_filters_<?php echo $portfolioID; ?> ul li.active a:visited,
#pfhub_portfolio_content_<?php echo $portfolioID; ?> #pfhub_portfolio_filters_<?php echo $portfolioID; ?>  ul li.active a:hover,
#pfhub_portfolio_content_<?php echo $portfolioID; ?> #pfhub_portfolio_filters_<?php echo $portfolioID; ?>  ul li.active a:focus,
#pfhub_portfolio_content_<?php echo $portfolioID; ?> #pfhub_portfolio_filters_<?php echo $portfolioID; ?>  ul li.active a:active {
    color:#<?php echo $pfhub_portfolio_get_options["pfhub_portfolio_view3_filterbutton_hover_font_color"];?> !important;
    background-color: #<?php echo $pfhub_portfolio_get_options["pfhub_portfolio_view3_filterbutton_hover_background_color"];?> !important;
    cursor: pointer;
}
#pfhub_portfolio_content_<?php echo $portfolioID; ?> section {
    position:relative;
    display:block;
}

#pfhub_portfolio_content_<?php echo $portfolioID; ?> #pfhub_portfolio_container_<?php echo $portfolioID; ?> {

    width: 79%;
<?php if(($sortingFloatFullWidth == "left" && $filteringFloatFullWidth == "right") || ($sortingFloatFullWidth == "right" && $filteringFloatFullWidth == "left"))
    {echo "margin: 0px auto;width:58%;"; }
    if(($filteringFloatFullWidth == "left" || $filteringFloatFullWidth == "right" && $sortingFloatFullWidth == "top") || ($sortingFloatFullWidth == "left" || $sortingFloatFullWidth == "right" && $filteringFloatFullWidth == "top"))
    {echo 'float:left;';}
    if(($portfolioShowSorting == 'off' && $portfolioShowFiltering == 'off') || ($sortingFloatFullWidth == 'top' && $filteringFloatFullWidth == 'top') ||
        ($sortingFloatFullWidth == 'top' && $filteringFloatFullWidth == '') || ($sortingFloatFullWidth == '' && $filteringFloatFullWidth == 'top'))
    {echo 'width:100%;';}
    if($portfolioShowLoading == 'on') echo 'opacity: 0;';
?>

}
@media screen and (max-width: 768px) {

    #pfhub_portfolio_content_<?php echo $portfolioID; ?> #pfhub_portfolio_filters_<?php echo $portfolioID; ?> ul li a,
    #pfhub_portfolio_content_<?php echo $portfolioID; ?> #pfhub_portfolio_filters_<?php echo $portfolioID; ?> ul li a:link,
    #pfhub_portfolio_content_<?php echo $portfolioID; ?> #pfhub_portfolio_filters_<?php echo $portfolioID; ?> ul li a:visited {
        font-size: 2vw !important;
    }
    #pfhub_portfolio_content_<?php echo $portfolioID; ?> #pfhub_portfolio_options_<?php echo $portfolioID; ?> ul li a {
        font-size:2vw !important;
    }

}

@media screen and (max-width: 600px) {
    .portelement_<?php echo $portfolioID; ?> div.left-block_<?php echo $portfolioID; ?> {
        width: 100%;
    }
    .portelement_<?php echo $portfolioID; ?> div.left-block_<?php echo $portfolioID; ?> .main-image-block_<?php echo $portfolioID; ?>{
        float: left;
    }
    .portelement_<?php echo $portfolioID; ?> div.left-block_<?php echo $portfolioID; ?> .thumbs-block{
        width: calc(100% - <?php echo $pfhub_portfolio_get_options['pfhub_portfolio_view3_mainimage_width']+10; ?>px);
        margin-left: 10px;
    }
    .portelement_<?php echo $portfolioID; ?> div.left-block_<?php echo $portfolioID; ?> .thumbs-block ul{
        width: auto;
    }
    .portelement_<?php echo $portfolioID; ?> div.right-block {
        width: 100%;
    }
    .portelement_<?php echo $portfolioID; ?> div.right-block .title-block_<?php echo $portfolioID; ?> h3 {
        text-align: center;
    }
}

@media screen and (max-width: 480px) {

    #pfhub_portfolio_content_<?php echo $portfolioID; ?> #pfhub_portfolio_filters_<?php echo $portfolioID; ?> ul li a,
    #pfhub_portfolio_content_<?php echo $portfolioID; ?> #pfhub_portfolio_filters_<?php echo $portfolioID; ?> ul li a:link,
    #pfhub_portfolio_content_<?php echo $portfolioID; ?> #pfhub_portfolio_filters_<?php echo $portfolioID; ?> ul li a:visited {
        font-size: 3vw !important;
    }
    #pfhub_portfolio_content_<?php echo $portfolioID; ?> #pfhub_portfolio_options_<?php echo $portfolioID; ?> ul li a {
        line-height: 3vw;
        font-size:3vw !important;
    }
    .portelement_<?php echo $portfolioID; ?> div.left-block_<?php echo $portfolioID; ?> .thumbs-block{
        width: auto;
    }
    .portelement_<?php echo $portfolioID; ?> div.left-block_<?php echo $portfolioID; ?> .main-image-block_<?php echo $portfolioID; ?>  {
        left: 50%;
        transform: translateX(-50%);
        position: relative;
    }
    .portelement_<?php echo $portfolioID; ?> div.left-block_<?php echo $portfolioID; ?> .thumbs-block ul{
        left: 50%;
        transform: translateX(-50%);
        position: relative;
    }
    .portelement_<?php echo $portfolioID; ?> div.left-block_<?php echo $portfolioID; ?> .thumbs-block{
        width: 100%;
        margin-left: 0;
    }
}
@media screen and (max-width: 420px) {

    #pfhub_portfolio_content_<?php echo $portfolioID; ?> #pfhub_portfolio_filters_<?php echo $portfolioID; ?> ul li a,
    #pfhub_portfolio_content_<?php echo $portfolioID; ?> #pfhub_portfolio_filters_<?php echo $portfolioID; ?> ul li a:link,
    #pfhub_portfolio_content_<?php echo $portfolioID; ?> #pfhub_portfolio_filters_<?php echo $portfolioID; ?> ul li a:visited {
        font-size: 4vw !important;
    }
    #pfhub_portfolio_content_<?php echo $portfolioID; ?> #pfhub_portfolio_options_<?php echo $portfolioID; ?> ul li a {
        font-size:4vw !important;
    }
}

@media screen and (max-width: 765px) {
    #pfhub_portfolio_content_<?php echo $portfolioID; ?> .right-block {
        float: left !important;
        width: 100% !important;

    }
}
#pfhub_portfolio-container-loading-overlay_<?php echo $portfolioID; ?> {
    width: 100%;
    height: 100%;
    position: absolute;
    z-index: 1;
    background:  url(<?php echo PFHUB_PORTFOLIO_IMAGES_URL.'/loading/loading-'.$portfolioLoadingIconype.'.svg'; ?>) center top ;
    background-repeat: no-repeat;
    background-size: 60px auto;
<?php if($portfolioShowLoading != 'on') echo 'display:none'; ?>
}
#pfhub_portfolio_options_and_filters_<?php echo $portfolioID; ?>{
    position: relative;
    float: left;
    width: 20%;
    max-width: 180px;
    float:<?php echo $sortingFloatFullWidth; ?>;
<?php if($sortingFloatFullWidth == 'left') echo 'margin-right: 1%;';
    else echo 'margin-left:1%;';
?>
}
</style>
