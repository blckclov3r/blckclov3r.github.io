<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
<style>
/***For Lifgtbox Gallery view***/
.play-icon.youtube-icon  {
    background: url(<?php echo  PFHUB_PORTFOLIO_IMAGES_URL.'/admin/play.youtube.png';?>) center center no-repeat;
    background-size: 30% 30%;
}
.play-icon.vimeo-icon  {
    background: url(<?php echo  PFHUB_PORTFOLIO_IMAGES_URL.'/admin/play.vimeo.png';?>) center center no-repeat;
    background-size: 30% 30%;
}
.play-icon {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
}
/***</add>***/
.portelement_<?php echo $portfolioID; ?> {
    max-width:<?php echo $pfhub_portfolio_get_options['pfhub_portfolio_view6_width']+2*$pfhub_portfolio_get_options['pfhub_portfolio_view6_border_width']; ?>px;
    width: 100%;
    margin:0 0 10px 0;
    border:<?php echo $pfhub_portfolio_get_options['pfhub_portfolio_view6_border_width']; ?>px solid #<?php echo $pfhub_portfolio_get_options['pfhub_portfolio_view6_border_color']; ?>;
    border-radius:<?php echo $pfhub_portfolio_get_options['pfhub_portfolio_view6_border_radius']; ?>px;
    outline:none;
    overflow:hidden;
}

.portelement_<?php echo $portfolioID; ?> .image-block_<?php echo $portfolioID; ?> {
    position:relative;
    max-width:<?php echo $pfhub_portfolio_get_options['pfhub_portfolio_view6_width']; ?>px;
    width:100%;
}
.portelement_<?php echo $portfolioID; ?> .image-block_<?php echo $portfolioID; ?> a {
    border:none;
    display: block;
    cursor: -webkit-zoom-in; cursor: -moz-zoom-in;
}
.portelement_<?php echo $portfolioID; ?> .image-block_<?php echo $portfolioID; ?> img {
    margin:0 !important;
    padding:0 !important;
    max-width:<?php echo $pfhub_portfolio_get_options['pfhub_portfolio_view6_width']; ?>px !important;
    width: 100%;
    height:auto;
    display:block;
    border-radius: 0 !important;
    box-shadow: 0 0 0 rgba(0, 0, 0, 0) !important;
}

.portelement_<?php echo $portfolioID; ?> .image-block_<?php echo $portfolioID; ?> img:hover {
    cursor: -webkit-zoom-in; cursor: -moz-zoom-in;
}

.portelement_<?php echo $portfolioID; ?> .title-block_<?php echo $portfolioID; ?> {
    position:absolute;
    text-overflow: ellipsis;
    overflow: hidden;
    left:0;
    width:100%;
    height: <?php echo $pfhub_portfolio_get_options["pfhub_portfolio_view6_title_font_size"] + 14; ?>px;
    bottom:-<?php echo $pfhub_portfolio_get_options["pfhub_portfolio_view6_title_font_size"] + 15; ?>px;
    background: <?php
			list($r,$g,$b) = array_map('hexdec',str_split($pfhub_portfolio_get_options['pfhub_portfolio_view6_title_background_color'],2));
				$titleopacity=$pfhub_portfolio_get_options["pfhub_portfolio_view6_title_background_transparency"]/100;
				echo 'rgba('.$r.','.$g.','.$b.','.$titleopacity.')  !important';
	?>;
    -webkit-transition: bottom 0.3s ease-out 0.1s;
    -moz-transition: bottom 0.3s ease-out 0.1s;
    -o-transition: bottom 0.3s ease-out 0.1s;
    transition: bottom 0.3s ease-out 0.1s;
}

.portelement_<?php echo $portfolioID; ?>:hover .title-block_<?php echo $portfolioID; ?> {bottom:0;}

.portelement_<?php echo $portfolioID; ?> .title-block_<?php echo $portfolioID; ?> a, .portelement_<?php echo $portfolioID; ?> .title-block_<?php echo $portfolioID; ?> a:link, .portelement_<?php echo $portfolioID; ?> .title-block_<?php echo $portfolioID; ?> a:visited, 
.portelement_<?php echo $portfolioID; ?> .title-block_<?php echo $portfolioID; ?> {
    margin:0;
    padding:0 1% 0 2%;
    text-decoration:none;
    text-overflow: ellipsis;
    overflow: hidden;
    white-space:nowrap;
    z-index:20;
    font-size: <?php echo $pfhub_portfolio_get_options["pfhub_portfolio_view6_title_font_size"];?>px;
    color:#<?php echo $pfhub_portfolio_get_options["pfhub_portfolio_view6_title_font_color"];?>;
    font-weight:normal;
    border: none;
    box-shadow: none;
}



.portelement_<?php echo $portfolioID; ?> .title-block_<?php echo $portfolioID; ?> a:hover, .portelement_<?php echo $portfolioID; ?> .title-block_<?php echo $portfolioID; ?> a:focus, .portelement_<?php echo $portfolioID; ?> .title-block_<?php echo $portfolioID; ?> a:active, 
.portelement_<?php echo $portfolioID; ?> .title-block_<?php echo $portfolioID; ?>:hover {
    color:#<?php echo $pfhub_portfolio_get_options["pfhub_portfolio_view6_title_font_hover_color"];?>;
    text-decoration:none;
}

#pfhub_portfolio_content_<?php echo $portfolioID; ?> #pfhub_portfolio_options_<?php echo $portfolioID; ?> {
    position: relative;
    overflow: hidden;
<?php   if($sortingFloatLgal != 'top'){
           echo 'float:'.$sortingFloatLgal.';';
           echo  "max-width:180px;width:20%;display:inline-block;";
           if($filteringFloatLgal == 'top') echo 'margin-top:40px;';
           if($sortingFloatLgal == 'left') echo 'margin-right: 1%;';
           else echo 'margin-left:1%;';
       }
       else {
           if($portfolioposition == 'on' && ($filteringFloatLgal == 'top' || $filteringFloatLgal == '')) echo 'left:50%; transform:translateX(-50%);';
           if($filteringFloatLgal == 'left') echo 'margin-left:calc( 185px + 1%);';
           echo 'width: auto; margin-bottom: 5px;display:table;';
       }
       if(($sortingFloatLgal == 'left' && $filteringFloatLgal == 'left') || ($sortingFloatLgal == 'right' && $filteringFloatLgal == 'right')){
           echo 'width: 100%;';
       }
?>

<?php
    if($portfolioShowLoading == 'on') echo 'opacity: 0;';
?>
    margin-bottom: 10px;
}

#pfhub_portfolio_content_<?php echo $portfolioID; ?> #pfhub_portfolio_options_<?php echo $portfolioID; ?> ul {
    margin: 0 !important;
    padding: 0 !important;
    list-style: none;
<?php if($sortingFloatLgal == 'top') {
    echo "float:left;margin-left:1%;";
} ?>
}

#pfhub_portfolio_content_<?php echo $portfolioID; ?> #pfhub_portfolio_filters_<?php echo $portfolioID; ?> ul {
    margin: 0 !important;
    padding: 0 !important;
    overflow: hidden;
<?php if($filteringFloatLgal == 'top') {
  echo "float:left;margin-left:1%;";
} ?>
    width: 100%;
}

#pfhub_portfolio_content_<?php echo $portfolioID; ?> #pfhub_portfolio_options_<?php echo $portfolioID; ?> ul li {
    border-radius: <?php echo $pfhub_portfolio_get_options["pfhub_portfolio_view6_sortbutton_border_radius"];?>px;
    list-style-type: none;
    margin: 0 !important;
    padding: 0;
<?php
    if($sortingFloatLgal == "top")
    { echo "float:left !important;margin: 0 8px 4px 0 !important;"; }
    if($sortingFloatLgal == "left" || $sortingFloatLgal == "right")
    { echo 'border-bottom: 1px solid #ccc;'; }
    else
    { echo 'border: 1px solid #ccc;'; }
?>
}

#pfhub_portfolio_content_<?php echo $portfolioID; ?> #pfhub_portfolio_options_<?php echo $portfolioID; ?> ul li a {
    background-color: #<?php echo $pfhub_portfolio_get_options["pfhub_portfolio_view6_sortbutton_background_color"];?> !important;
    font-size:<?php echo $pfhub_portfolio_get_options["pfhub_portfolio_view6_sortbutton_font_size"];?>px !important;
    color:#<?php echo $pfhub_portfolio_get_options["pfhub_portfolio_view6_sortbutton_font_color"];?> !important;
    text-decoration: none;
    cursor: pointer;
    margin: 0 !important;
    display: block;
    padding:<?php echo $pfhub_portfolio_get_options["pfhub_portfolio_view6_sortbutton_border_padding"];?>px;
    border-radius: <?php echo $pfhub_portfolio_get_options["pfhub_portfolio_view6_sortbutton_border_radius"];?>px;
}


#pfhub_portfolio_content_<?php echo $portfolioID; ?> #pfhub_portfolio_options_<?php echo $portfolioID; ?> ul li a:hover {
    background-color: #<?php echo $pfhub_portfolio_get_options["pfhub_portfolio_view6_sortbutton_hover_background_color"];?> !important;
    color:#<?php echo $pfhub_portfolio_get_options["pfhub_portfolio_view6_sortbutton_hover_font_color"];?> !important;
    cursor: pointer;
}

#pfhub_portfolio_content_<?php echo $portfolioID; ?> #pfhub_portfolio_filters_<?php echo $portfolioID; ?> {
    float: <?php echo $pfhub_portfolio_get_options["pfhub_portfolio_view6_filtering_float"]; ?>;
    position: relative;
<?php   if($filteringFloatLgal != 'top'){
            echo 'float:'.$filteringFloatLgal.';';
            echo  "max-width:180px;width:20%;display:inline-block;";
            if($filteringFloatLgal == 'left') echo 'margin-right: 1%;';
            else echo 'margin-left:1%;';
        }
        else {
            if($portfolioposition == 'on' && ($sortingFloatLgal == 'top' || $sortingFloatLgal == '')) echo 'left:50%; transform:translateX(-50%);';
            echo 'width: auto; margin-bottom: 5px;display:table;';
        }
        if(($sortingFloatLgal == 'left' && $filteringFloatLgal == 'left') || ($sortingFloatLgal == 'right' && $filteringFloatLgal == 'right')){
            echo 'width: 100%;';
        }
?>

<?php
    if($portfolioShowLoading == 'on') echo 'opacity: 0;';
?>
}

#pfhub_portfolio_content_<?php echo $portfolioID; ?> #pfhub_portfolio_filters_<?php echo $portfolioID; ?> ul li {
    list-style-type: none;
    border-radius: <?php echo $pfhub_portfolio_get_options["pfhub_portfolio_view6_sortbutton_border_radius"];?>px;
<?php
   if($filteringFloatLgal == "top") { echo "float:left !important;margin: 0 8px 4px 0 !important;"; }
   if($filteringFloatLgal == "left" || $filteringFloatLgal == "right")
   { echo 'border-bottom: 1px solid #ccc;'; }
   else echo "border: 1px solid #ccc;";
?>
}

#pfhub_portfolio_content_<?php echo $portfolioID; ?> #pfhub_portfolio_filters_<?php echo $portfolioID; ?> ul li a {
    font-size:<?php echo $pfhub_portfolio_get_options["pfhub_portfolio_view6_filterbutton_font_size"];?>px !important;
    color:#<?php echo $pfhub_portfolio_get_options["pfhub_portfolio_view6_filterbutton_font_color"];?> !important;
    background-color: #<?php echo $pfhub_portfolio_get_options["pfhub_portfolio_view6_filterbutton_background_color"];?> !important;
    border-radius: <?php echo $pfhub_portfolio_get_options["pfhub_portfolio_view6_filterbutton_border_radius"];?>px;
    padding:<?php echo $pfhub_portfolio_get_options["pfhub_portfolio_view6_sortbutton_border_padding"];?>px;
    display: block;
    text-decoration: none;
}

#pfhub_portfolio_content_<?php echo $portfolioID; ?> #pfhub_portfolio_filters_<?php echo $portfolioID; ?>  ul li a:hover {
    color:#<?php echo $pfhub_portfolio_get_options["pfhub_portfolio_view6_filterbutton_hover_font_color"];?> !important;
    background-color: #<?php echo $pfhub_portfolio_get_options["pfhub_portfolio_view6_filterbutton_hover_background_color"];?> !important;
    cursor: pointer
}
#pfhub_portfolio_content_<?php echo $portfolioID; ?> #pfhub_portfolio_filters_<?php echo $portfolioID; ?> ul li.active a,
#pfhub_portfolio_content_<?php echo $portfolioID; ?> #pfhub_portfolio_filters_<?php echo $portfolioID; ?> ul li.active a:link,
#pfhub_portfolio_content_<?php echo $portfolioID; ?> #pfhub_portfolio_filters_<?php echo $portfolioID; ?> ul li.active a:visited,
#pfhub_portfolio_content_<?php echo $portfolioID; ?> #pfhub_portfolio_filters_<?php echo $portfolioID; ?>  ul li.active a:hover,
#pfhub_portfolio_content_<?php echo $portfolioID; ?> #pfhub_portfolio_filters_<?php echo $portfolioID; ?>  ul li.active a:focus,
#pfhub_portfolio_content_<?php echo $portfolioID; ?> #pfhub_portfolio_filters_<?php echo $portfolioID; ?>  ul li.active a:active {
    color:#<?php echo $pfhub_portfolio_get_options["pfhub_portfolio_view6_filterbutton_hover_font_color"];?> !important;
    background-color: #<?php echo $pfhub_portfolio_get_options["pfhub_portfolio_view6_filterbutton_hover_background_color"];?> !important;
    cursor: pointer;
}
#pfhub_portfolio_content_<?php echo $portfolioID; ?> section {
    position:relative;
    display:block;
}

#pfhub_portfolio_content_<?php echo $portfolioID; ?> #pfhub_portfolio_container_<?php echo $portfolioID; ?> {
    width: 79%;
    max-width: 100%;
<?php if(($sortingFloatLgal == "left" && $filteringFloatLgal == "right") || ($sortingFloatLgal == "right" && $filteringFloatLgal == "left"))
    {echo "margin: 0 auto;width:58%;"; }
    if(($filteringFloatLgal == "left" || $filteringFloatLgal == "right" && $sortingFloatLgal == "top") || ($sortingFloatLgal == "left" || $sortingFloatLgal == "right" && $filteringFloatLgal == "top"))
    {echo 'float:left;';}
    if(($portfolioShowSorting == 'off' && $portfolioShowFiltering == 'off') || ($sortingFloatLgal == 'top' && $filteringFloatLgal == 'top') ||
        ($sortingFloatLgal == 'top' && $filteringFloatLgal == '') || ($sortingFloatLgal == '' && $filteringFloatLgal == 'top'))
    {echo 'width:100%;';}
    if($portfolioShowLoading == 'on') echo 'opacity: 0;';
?>
}

#port-sort-direction {
<?php if($pfhub_portfolio_get_options["pfhub_portfolio_view6_sorting_float"] == "top")
   { echo "float: left !important;"; }
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
@media screen and (max-width: 480px) {
    #pfhub_portfolio_content_<?php echo $portfolioID; ?> #pfhub_portfolio_options_<?php echo $portfolioID; ?> {
        float: left;
    }
    #pfhub_portfolio_content_<?php echo $portfolioID; ?> #pfhub_portfolio_options_<?php echo $portfolioID; ?> #sort-by{
        float: left;
        width: 100% !important;
    }
    #pfhub_portfolio_content_<?php echo $portfolioID; ?> #pfhub_portfolio_options_<?php echo $portfolioID; ?> #port-sort-direction{
        float: left;
        width: 100% !important;
        position: relative;
        padding-left: 31% !important;
        right: 31%;
    }
    #pfhub_portfolio_content_<?php echo $portfolioID; ?> #pfhub_portfolio_filters_<?php echo $portfolioID; ?> ul li a,
    #pfhub_portfolio_content_<?php echo $portfolioID; ?> #pfhub_portfolio_filters_<?php echo $portfolioID; ?> ul li a:link,
    #pfhub_portfolio_content_<?php echo $portfolioID; ?> #pfhub_portfolio_filters_<?php echo $portfolioID; ?> ul li a:visited {
        font-size: 3vw !important;
    }
    #pfhub_portfolio_content_<?php echo $portfolioID; ?> #pfhub_portfolio_options_<?php echo $portfolioID; ?> ul li a {
        line-height: 3vw;
        font-size:3vw !important;
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
@media screen and (max-width: <?php echo $pfhub_portfolio_get_options['pfhub_portfolio_view0_block_width']+2*$pfhub_portfolio_get_options['pfhub_portfolio_view6_border_width']+40; ?>px) {
    .portelement_<?php echo $portfolioID; ?>  {
        width:98%;
        margin: 1% !important;
        float: left;
        overflow: hidden;
        outline:none;
        border:<?php echo $pfhub_portfolio_get_options['pfhub_portfolio_view6_border_color']; ?>px solid #<?php echo $pfhub_portfolio_get_options['pfhub_portfolio_view0_element_border_color']; ?>;
    }
    .wd-portfolio-panel_<?php echo $portfolioID; ?> {
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
#pfhub_portfolio_options_and_filters_<?php echo $portfolioID; ?> {
    position: relative;
    float: left;
    width: 20%;
    max-width: 180px;
    float:<?php echo $sortingFloatLgal; ?>;
<?php if($sortingFloatLgal == 'left') echo 'margin-right: 1%;';
    else echo 'margin-left:1%;';
?>
}
</style>
