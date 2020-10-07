<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
?>
<style>
    /*** For Toggle-Up Down ***/

    .portelement_<?php echo $portfolioID; ?> .play-icon.youtube-icon  {
        background: url(<?php echo  PFHUB_PORTFOLIO_IMAGES_URL.'/admin/play.youtube.png' ;?>) center center no-repeat;
        background-size: 30% 30%;
    }
    .portelement_<?php echo $portfolioID; ?> .play-icon.vimeo-icon  {
        background: url(<?php echo  PFHUB_PORTFOLIO_IMAGES_URL.'/admin/play.vimeo.png' ;?>) center center no-repeat;
        background-size: 30% 30%;
    }
    .portelement_<?php echo $portfolioID; ?> .play-icon {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
    }
    .portelement_<?php echo $portfolioID; ?> .dropdownable .play-icon {
        display: none;
    }
    .portelement_<?php echo $portfolioID; ?>  .add-H-relative {
        position: relative;
    }
    .portelement_<?php echo $portfolioID; ?> {
        background:#<?php echo $pfhub_portfolio_get_options['pfhub_portfolio_view0_element_background_color']?>;
        width:<?php echo $pfhub_portfolio_get_options['pfhub_portfolio_view0_block_width']+2*$pfhub_portfolio_get_options['pfhub_portfolio_view0_element_border_width']; ?>px !important;
        margin: 5px;
        max-width: calc(100% - 10px) !important;
        float: left;
        overflow: hidden;
        outline:none;
        border:<?php echo $pfhub_portfolio_get_options['pfhub_portfolio_view0_element_border_width']; ?>px solid #<?php echo $pfhub_portfolio_get_options['pfhub_portfolio_view0_element_border_color']; ?>;
        box-sizing: border-box;
    }

    .portelement_<?php echo $portfolioID; ?>.large,
    .variable-sizes .portelement_<?php echo $portfolioID; ?>.large,
    .variable-sizes .portelement_<?php echo $portfolioID; ?>.large.width2.height2 {
        max-width: <?php echo $pfhub_portfolio_get_options['pfhub_portfolio_view0_block_width']; ?>px;
        width: 100%;
        z-index: 10;
    }

    .default-block_<?php echo $portfolioID; ?> {
        position:relative;
        max-width:<?php echo $pfhub_portfolio_get_options['pfhub_portfolio_view0_block_width'];?>px !important;
        width: 100%;
        height:<?php echo $pfhub_portfolio_get_options['pfhub_portfolio_view0_block_height']+20+$pfhub_portfolio_get_options['pfhub_portfolio_view0_title_font_size']?>px !important;
    }

    .default-block_<?php echo $portfolioID; ?> .image-block_<?php echo $portfolioID; ?> {
        margin:0;
        padding: 0;
        line-height: 0;
        border-bottom:<?php echo $pfhub_portfolio_get_options['pfhub_portfolio_view0_element_border_width']; ?>px solid #<?php echo $pfhub_portfolio_get_options['pfhub_portfolio_view0_element_border_color']; ?>;
        height:<?php echo $pfhub_portfolio_get_options['pfhub_portfolio_view0_block_height']; ?>px !important;
    }

    .default-block_<?php echo $portfolioID; ?> .image-block_<?php echo $portfolioID; ?> a {
        display:block;
        height:<?php echo $pfhub_portfolio_get_options['pfhub_portfolio_view0_block_height']; ?>px !important;
        overflow: hidden;
        position:relative;
        border: none;
        box-shadow: none;
    }

    .portelement_<?php echo $portfolioID; ?> .default-block_<?php echo $portfolioID; ?> img {
        margin: 0 !important;
        padding: 0 !important;
        max-width:<?php echo $pfhub_portfolio_get_options['pfhub_portfolio_view0_block_width'];?>px ;
        max-width:none !important;
        border-radius: 0;
    <?php  if($pfhub_portfolio_get_options['pfhub_portfolio_port_natural_size_toggle'] == 'resize'){ ?>
        height: 100%;
        width: 100%;
    <?php }?>
    }

    .default-block_<?php echo $portfolioID; ?> .title-block_<?php echo $portfolioID; ?> {
        position:relative;
        display:block;
        height: <?php echo 20+$pfhub_portfolio_get_options['pfhub_portfolio_view0_title_font_size']-$pfhub_portfolio_get_options['pfhub_portfolio_view0_element_border_width']; ?>px;
        padding: 0 0 0 0;
        max-width:<?php echo $pfhub_portfolio_get_options['pfhub_portfolio_view0_block_width']; ?>px !important;
        width: 100%;
    }

    .default-block_<?php echo $portfolioID; ?> .title-block_<?php echo $portfolioID; ?> h3 {
        position:relative;
        margin: 0 !important;
        padding: 0 0 0 5px !important;
        max-width:<?php echo $pfhub_portfolio_get_options['pfhub_portfolio_view0_block_width']-30; ?>px !important;
        width: 80%;
        text-overflow: ellipsis;
        overflow: hidden;
        white-space:nowrap;
        font-weight:normal;
        color:#<?php echo $pfhub_portfolio_get_options['pfhub_portfolio_view0_title_font_color']; ?>;
        font-size:<?php echo $pfhub_portfolio_get_options['pfhub_portfolio_view0_title_font_size']; ?>px !important;
        line-height: normal !important;
        top: 50%;
        transform: translateY(-50%);
    }

    .portelement_<?php echo $portfolioID; ?> .title-block_<?php echo $portfolioID; ?> .open-close-button {
        width:20px;
        height:20px;
        display:block;
        position:absolute;
        top:50%;
        right:2%;
        background:url('<?php echo  PFHUB_PORTFOLIO_IMAGES_URL.'/admin/open-close.'.$pfhub_portfolio_get_options['pfhub_portfolio_view0_togglebutton_style'].'.png' ; ?>') left top no-repeat;
        z-index:5;
        cursor:pointer;
        opacity:0.33;
        transform: translateY( -50%);
    }

    .portelement_<?php echo $portfolioID; ?>:hover .title-block_<?php echo $portfolioID; ?> .open-close-button {opacity:1;}

    .portelement_<?php echo $portfolioID; ?>.large .open-close-button {
        background:url('<?php echo  PFHUB_PORTFOLIO_IMAGES_URL.'/admin/open-close.'.$pfhub_portfolio_get_options['pfhub_portfolio_view0_togglebutton_style'].'.png' ; ?>') left bottom no-repeat;
    }

    .wd-portfolio-panel_<?php echo $portfolioID; ?> {
        position: absolute;
        display:block;
        width:<?php echo $pfhub_portfolio_get_options['pfhub_portfolio_view0_block_width']-10; ?>px !important;
        margin: 0 5px 0 5px;
        padding: 0;
        text-align:left;
        top:<?php echo $pfhub_portfolio_get_options['pfhub_portfolio_view0_block_height']+20+$pfhub_portfolio_get_options['pfhub_portfolio_view0_title_font_size']; ?>px;
        z-index:6;
        height:200px;
    }


    .wd-portfolio-panel_<?php echo $portfolioID; ?> .description-block_<?php echo $portfolioID; ?>, .portelement_<?php echo $portfolioID; ?> div.right-block .description-block_<?php echo $portfolioID; ?> * {
        position:relative;
        clear:both;
    }

    .wd-portfolio-panel_<?php echo $portfolioID; ?> .description-block_<?php echo $portfolioID; ?> p,.wd-portfolio-panel_<?php echo $portfolioID; ?> .description-block_<?php echo $portfolioID; ?> * {
        text-align:justify;
        /*font-weight:normal;*/
        font-size:<?php echo $pfhub_portfolio_get_options['pfhub_portfolio_view0_description_font_size']; ?>px;
        color:#<?php echo $pfhub_portfolio_get_options['pfhub_portfolio_view0_description_color']; ?>;
        margin: 0;
        padding: 0;
    }



    .wd-portfolio-panel_<?php echo $portfolioID; ?> .description-block_<?php echo $portfolioID; ?> h1,
    .wd-portfolio-panel_<?php echo $portfolioID; ?> .description-block_<?php echo $portfolioID; ?> h2,
    .wd-portfolio-panel_<?php echo $portfolioID; ?> .description-block_<?php echo $portfolioID; ?> h3,
    .wd-portfolio-panel_<?php echo $portfolioID; ?> .description-block_<?php echo $portfolioID; ?> h4,
    .wd-portfolio-panel_<?php echo $portfolioID; ?> .description-block_<?php echo $portfolioID; ?> h5,
    .wd-portfolio-panel_<?php echo $portfolioID; ?> .description-block_<?php echo $portfolioID; ?> h6,
    .wd-portfolio-panel_<?php echo $portfolioID; ?> .description-block_<?php echo $portfolioID; ?> p,
    .wd-portfolio-panel_<?php echo $portfolioID; ?> .description-block_<?php echo $portfolioID; ?> strong,
    .wd-portfolio-panel_<?php echo $portfolioID; ?> .description-block_<?php echo $portfolioID; ?> span {
        padding:2px !important;
        margin: 0 !important;
    }

    .wd-portfolio-panel_<?php echo $portfolioID; ?> .description-block_<?php echo $portfolioID; ?> ul,
    .wd-portfolio-panel_<?php echo $portfolioID; ?> .description-block_<?php echo $portfolioID; ?> li {
        padding:2px 0 2px 5px;
        margin: 0 0 0 8px;
    }


    .wd-portfolio-panel_<?php echo $portfolioID; ?> .thumbs-list_<?php echo $portfolioID; ?> {
        position:relative;
        clear:both;
        list-style:none;
        display:table;
        width:100%;
        padding: 0;
        margin:3px 0 0 0;
        text-align:center;
    }

    .wd-portfolio-panel_<?php echo $portfolioID; ?> .thumbs-list_<?php echo $portfolioID; ?> li {
        display:inline-block;
        margin: 0 3px 0 2px;
    }

    .wd-portfolio-panel_<?php echo $portfolioID; ?> .thumbs-list_<?php echo $portfolioID; ?> li a {
        display:block;
        width:<?php echo $pfhub_portfolio_get_options['pfhub_portfolio_view0_thumbs_width']; ?>px;
        height:<?php echo $pfhub_portfolio_get_options['pfhub_portfolio_view0_thumbs_width']; ?>px;
        opacity:0.7;
        display:table;
        border: none;
        box-shadow: none;
    }

    .wd-portfolio-panel_<?php echo $portfolioID; ?> .thumbs-list_<?php echo $portfolioID; ?> li a:hover {
        opacity:1;
    }

    .wd-portfolio-panel_<?php echo $portfolioID; ?> img {
        margin: 0 !important;
        padding: 0 !important;
        display:table-cell;
        vertical-align:middle;
        width:<?php echo $pfhub_portfolio_get_options['pfhub_portfolio_view0_thumbs_width']; ?>px !important;
        max-height:<?php echo $pfhub_portfolio_get_options['pfhub_portfolio_view0_thumbs_width']; ?>px !important;
        width:100%;
        height:100%;
    }

    .wd-portfolio-panel_<?php echo $portfolioID; ?> > div {
        position:relative;
        clear:both;
        padding-top:10px;
        margin-bottom:10px;
    <?php if($pfhub_portfolio_get_options['pfhub_portfolio_view0_show_separator_lines']=="on") {?>
        background:url('<?php echo  PFHUB_PORTFOLIO_IMAGES_URL.'/admin/divider.line.png' ; ?>') center top repeat-x;
    <?php } ?>
    }

    .wd-portfolio-panel_<?php echo $portfolioID; ?> .button-block {
        padding-top:10px;
        margin-bottom:10px;

    }

    .wd-portfolio-panel_<?php echo $portfolioID; ?> .button-block a, .wd-portfolio-panel_<?php echo $portfolioID; ?> .button-block a:link, .wd-portfolio-panel_<?php echo $portfolioID; ?> .button-block a:visited {
        padding:6px 12px;
        text-decoration:none;
        display:inline-block;
        font-size:<?php echo $pfhub_portfolio_get_options['pfhub_portfolio_view0_linkbutton_font_size']; ?>px;
        background:#<?php echo $pfhub_portfolio_get_options['pfhub_portfolio_view0_linkbutton_background_color']; ?>;
        color:#<?php echo $pfhub_portfolio_get_options['pfhub_portfolio_view0_linkbutton_color']; ?>;
    }

    .wd-portfolio-panel_<?php echo $portfolioID; ?> .button-block a:hover, .wd-portfolio-panel_<?php echo $portfolioID; ?> .button-block a:focus, .wd-portfolio-panel_<?php echo $portfolioID; ?> .button-block a:active {
        background:#<?php echo $pfhub_portfolio_get_options['pfhub_portfolio_view0_linkbutton_background_hover_color']; ?>;
        color:#<?php echo $pfhub_portfolio_get_options['pfhub_portfolio_view0_linkbutton_font_hover_color']; ?>;
        text-decoration:none;
    }

    #pfhub_portfolio_content_<?php echo $portfolioID; ?> #pfhub_portfolio_options_<?php echo $portfolioID; ?> {
        position: relative;
        overflow: hidden;
    <?php   if($sortingFloatToggle != 'top'){
                echo 'float:'.$sortingFloatToggle.';margin-top:5px;';
                echo  "max-width:180px;width:20%;display:inline-block;";
                if($filteringFloatToggle == 'top') echo 'margin-top:45px;';
                if($sortingFloatToggle == 'left') echo 'margin-right: 1%;';
                else echo 'margin-left:1%;';
            }
            else {
                if($portfolioposition == 'on' && ($filteringFloatToggle == 'top' || $filteringFloatToggle == '')) echo 'left:50%; transform:translateX(-50%);';
                if($filteringFloatToggle == 'left') echo 'margin-left:calc( 185px + 1%);';else echo 'margin-left:5px;';
                echo 'width: auto; margin-bottom: 5px;display:table;';
            }
            if(($sortingFloatToggle == 'left' && $filteringFloatToggle == 'left') || ($sortingFloatToggle == 'right' && $filteringFloatToggle == 'right')){
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
    <?php if($sortingFloatToggle == 'top') {
          echo "float:left;margin-left:1%;";
          } ?>
    }

    #pfhub_portfolio_content_<?php echo $portfolioID; ?> #pfhub_portfolio_filters_<?php echo $portfolioID; ?> ul {
        margin: 0 !important;
        padding: 0 !important;
        overflow: hidden;
    <?php if($filteringFloatToggle == 'top') {
        echo "float:left;margin-left:1%;";
        } ?>
        width: 100%;
    }

    <?php if($pfhub_portfolio_get_options["pfhub_portfolio_view0_sorting_float"] == 'none') { ?>
    #pfhub_portfolio_content_<?php echo $portfolioID; ?> #pfhub_portfolio_options_<?php echo $portfolioID; ?> ul {
        float: left;
    }
    <?php } ?>

    #pfhub_portfolio_content_<?php echo $portfolioID; ?> #pfhub_portfolio_options_<?php echo $portfolioID; ?> ul li {
        border-radius: <?php echo $pfhub_portfolio_get_options["pfhub_portfolio_view0_sortbutton_border_radius"];?>px;
        list-style-type: none;
        margin: 0 !important;
        padding: 0;
    <?php
        if($sortingFloatToggle == "top")
        { echo "float:left !important;margin: 0 8px 4px 0 !important;"; }
        if($sortingFloatToggle == "left" || $sortingFloatToggle == "right")
        { echo 'border-bottom: 1px solid #ccc;'; }
        else
        { echo 'border: 1px solid #ccc;'; }
    ?>
    }

    #pfhub_portfolio_content_<?php echo $portfolioID; ?> #pfhub_portfolio_options_<?php echo $portfolioID; ?> ul li a {
        background-color: #<?php echo $pfhub_portfolio_get_options["pfhub_portfolio_view0_sortbutton_background_color"];?> !important;
        font-size:<?php echo $pfhub_portfolio_get_options["pfhub_portfolio_view0_sortbutton_font_size"];?>px !important;
        color:#<?php echo $pfhub_portfolio_get_options["pfhub_portfolio_view0_sortbutton_font_color"];?> !important;
        border-radius: <?php echo $pfhub_portfolio_get_options["pfhub_portfolio_view0_sortbutton_border_radius"];?>px;
        text-decoration: none;
        cursor: pointer;
        margin: 0 !important;
        display: block;
        padding:<?php echo $pfhub_portfolio_get_options["pfhub_portfolio_view0_sortbutton_border_padding"];?>px;
    }

    /*#pfhub_portfolio_content_<?php echo $portfolioID; ?> #pfhub_portfolio_options_<?php echo $portfolioID; ?> ul li:hover {

}*/

    #pfhub_portfolio_content_<?php echo $portfolioID; ?> #pfhub_portfolio_options_<?php echo $portfolioID; ?> ul li a:hover {
        background-color: #<?php echo $pfhub_portfolio_get_options["pfhub_portfolio_view0_sortbutton_hover_background_color"];?> !important;
        color:#<?php echo $pfhub_portfolio_get_options["pfhub_portfolio_view0_sortbutton_hover_font_color"];?> !important;
        cursor: pointer;
    }

    #pfhub_portfolio_content_<?php echo $portfolioID; ?> #pfhub_portfolio_filters_<?php echo $portfolioID; ?> {
        position: relative;
        overflow: hidden;
    <?php   if($filteringFloatToggle != 'top'){
                echo 'float:'.$filteringFloatToggle.';margin-top:5px;';
                echo  "max-width:180px;width:20%;display:inline-block;";
                if($filteringFloatToggle == 'left') echo 'margin-right: 1%;';
                else echo 'margin-left:1%;';
            }
            else {
                if($portfolioposition == 'on' && ($sortingFloatToggle == 'top' || $sortingFloatToggle == '')) echo 'left:50%; transform:translateX(-50%);';
                if($sortingFloatToggle == 'left') echo 'margin-left:calc( 185px + 1%);';else echo 'margin-left:5px;';
                echo 'width: auto; margin-bottom: 5px;display:table;';
            }
            if(($sortingFloatToggle == 'left' && $filteringFloatToggle == 'left') || ($sortingFloatToggle == 'right' && $filteringFloatToggle == 'right')){
                echo 'width: 100%;';
            }
    ?>

    <?php
        if($portfolioShowLoading == 'on') echo 'opacity: 0;';
    ?>
    }

    #pfhub_portfolio_content_<?php echo $portfolioID; ?> #pfhub_portfolio_filters_<?php echo $portfolioID; ?> ul li {
        list-style-type: none;
        border-radius: <?php echo $pfhub_portfolio_get_options["pfhub_portfolio_view0_filterbutton_border_radius"];?>px;
    <?php
        if($filteringFloatToggle == "top") { echo "float:left !important;margin: 0 8px 4px 0 !important;"; }
        if($filteringFloatToggle == "left" || $filteringFloatToggle == "right")
        { echo 'border-bottom: 1px solid #ccc;'; }
        else echo "border: 1px solid #ccc;";
    ?>
    }

    #pfhub_portfolio_content_<?php echo $portfolioID; ?> #pfhub_portfolio_filters_<?php echo $portfolioID; ?> ul li a,
    #pfhub_portfolio_content_<?php echo $portfolioID; ?> #pfhub_portfolio_filters_<?php echo $portfolioID; ?> ul li a:link,
    #pfhub_portfolio_content_<?php echo $portfolioID; ?> #pfhub_portfolio_filters_<?php echo $portfolioID; ?> ul li a:visited {
        font-size:<?php echo $pfhub_portfolio_get_options["pfhub_portfolio_view0_filterbutton_font_size"];?>px !important;
        color:#<?php echo $pfhub_portfolio_get_options["pfhub_portfolio_view0_filterbutton_font_color"];?> !important;
        background-color: #<?php echo $pfhub_portfolio_get_options["pfhub_portfolio_view0_filterbutton_background_color"];?> !important;
        border-radius: <?php echo $pfhub_portfolio_get_options["pfhub_portfolio_view0_filterbutton_border_radius"];?>px;
        padding: <?php echo $pfhub_portfolio_get_options["pfhub_portfolio_view0_filterbutton_border_padding"];?>px;
        display: block;
        text-decoration: none;
    }

    #pfhub_portfolio_content_<?php echo $portfolioID; ?> #pfhub_portfolio_filters_<?php echo $portfolioID; ?>  ul li a:hover,
    #pfhub_portfolio_content_<?php echo $portfolioID; ?> #pfhub_portfolio_filters_<?php echo $portfolioID; ?>  ul li a:focus,
    #pfhub_portfolio_content_<?php echo $portfolioID; ?> #pfhub_portfolio_filters_<?php echo $portfolioID; ?>  ul li a:active {
        color:#<?php echo $pfhub_portfolio_get_options["pfhub_portfolio_view0_filterbutton_hover_font_color"];?> !important;
        background-color: #<?php echo $pfhub_portfolio_get_options["pfhub_portfolio_view0_filterbutton_hover_background_color"];?> !important;
        cursor: pointer;
    }

    #pfhub_portfolio_content_<?php echo $portfolioID; ?> #pfhub_portfolio_filters_<?php echo $portfolioID; ?> ul li.active a,
    #pfhub_portfolio_content_<?php echo $portfolioID; ?> #pfhub_portfolio_filters_<?php echo $portfolioID; ?> ul li.active a:link,
    #pfhub_portfolio_content_<?php echo $portfolioID; ?> #pfhub_portfolio_filters_<?php echo $portfolioID; ?> ul li.active a:visited,
    #pfhub_portfolio_content_<?php echo $portfolioID; ?> #pfhub_portfolio_filters_<?php echo $portfolioID; ?>  ul li.active a:hover,
    #pfhub_portfolio_content_<?php echo $portfolioID; ?> #pfhub_portfolio_filters_<?php echo $portfolioID; ?>  ul li.active a:focus,
    #pfhub_portfolio_content_<?php echo $portfolioID; ?> #pfhub_portfolio_filters_<?php echo $portfolioID; ?>  ul li.active a:active {
        color:#<?php echo $pfhub_portfolio_get_options["pfhub_portfolio_view0_filterbutton_hover_font_color"];?> !important;
        background-color: #<?php echo $pfhub_portfolio_get_options["pfhub_portfolio_view0_filterbutton_hover_background_color"];?> !important;
        cursor: pointer;
    }

    section#pfhub_portfolio_content_<?php echo $portfolioID; ?> {
        position:relative;
        display:block;
    }

    #pfhub_portfolio_content_<?php echo $portfolioID; ?> #pfhub_portfolio_container_<?php echo $portfolioID; ?> {
        width: 79%;
        max-width: 100%;
    <?php if(($sortingFloatToggle == "left" && $filteringFloatToggle == "right") ||
         ($sortingFloatToggle == "right" && $filteringFloatToggle == "left"))
       { echo "margin: 0 auto;width:58%;"; }
       if(($filteringFloatToggle == "left" || $filteringFloatToggle == "right" && $sortingFloatToggle == "top") || ($sortingFloatToggle == "left" || $sortingFloatToggle == "right" && $filteringFloatToggle == "top"))
       {echo 'float:left;';}
       if(($portfolioShowSorting == 'off' && $portfolioShowFiltering == 'off') || ($sortingFloatToggle == 'top' && $filteringFloatToggle == 'top') ||
            ($sortingFloatToggle == 'top' && $filteringFloatToggle == '') || ($sortingFloatToggle == '' && $filteringFloatToggle == 'top'))
       {echo 'width:100%;';}
       if($portfolioShowLoading == 'on') echo 'opacity: 0;';
    ?>
    }
    #pfhub_portfolio_content_<?php echo $portfolioID; ?> #pfhub_portfolio_options_<?php echo $portfolioID; ?> #port-sort-direction{
        position: static;
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
        #pfhub_portfolio_container_<?php echo $portfolioID; ?>{
            width:100%;
        }
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
    @media screen and (max-width: <?php echo $pfhub_portfolio_get_options['pfhub_portfolio_view0_block_width']+2*$pfhub_portfolio_get_options['pfhub_portfolio_view0_element_border_width']+40; ?>px) {
        .portelement_<?php echo $portfolioID; ?>  {
            width:98%;
            margin: 1% !important;
            float: left;
            overflow: hidden;
            outline:none;
            border:<?php echo $pfhub_portfolio_get_options['pfhub_portfolio_view0_element_border_width']; ?>px solid #<?php echo $pfhub_portfolio_get_options['pfhub_portfolio_view0_element_border_color']; ?>;
        }
        .wd-portfolio-panel_<?php echo $portfolioID; ?> {
            width: calc(100% - 10px) !important;
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
        float:<?php echo $sortingFloatToggle; ?>;
    <?php if($sortingFloatToggle == 'left') echo 'margin-right: 1%;';
        else echo 'margin-left:1%;';
    ?>
        margin-top: 5px;
    }
</style>
