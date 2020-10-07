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
        top : 0;
        left : 0;
        width: 100%;
        height: 100%;
    }
    .portelement_<?php echo $portfolioID; ?> .add-H-relative {
        position: relative;
    }
    .portelement_<?php echo $portfolioID; ?> {
        width: <?php echo $pfhub_portfolio_get_options['pfhub_portfolio_view1_block_width']+2*$pfhub_portfolio_get_options['pfhub_portfolio_view1_element_border_width']; ?>px !important;
        max-width: calc(100% - 10px);
        height:auto;
        margin: 5px 0 5px 0;
        float: left;
        overflow: hidden;
        position: relative;
        outline:none;
        background:#<?php echo $pfhub_portfolio_get_options['pfhub_portfolio_view1_element_background_color']?>;
        border:<?php echo $pfhub_portfolio_get_options['pfhub_portfolio_view1_element_border_width']; ?>px solid #<?php echo $pfhub_portfolio_get_options['pfhub_portfolio_view1_element_border_color']; ?>;
        box-sizing: border-box;
    }

    .default-block_<?php echo $portfolioID; ?> {
        position:relative;;
        width:100%;
    }

    .default-block_<?php echo $portfolioID; ?> .image-block_<?php echo $portfolioID; ?> {
        margin :0;
        padding :0;
        line-height :0;
        border-bottom:<?php echo $pfhub_portfolio_get_options['pfhub_portfolio_view1_element_border_width']; ?>px solid #<?php echo $pfhub_portfolio_get_options['pfhub_portfolio_view1_element_border_color']; ?>;
    }

    .default-block_<?php echo $portfolioID; ?> img {
        margin :0 !important;
        padding :0 !important;
        width: 100%;
        border-radius :0;
        display: inline-block;
    }

    .default-block_<?php echo $portfolioID; ?> .title-block_<?php echo $portfolioID; ?> {
        display:block;
        height:auto;
        padding:10px 0 10px 0;
        width:100%;
        text-overflow: ellipsis;
    }

    .default-block_<?php echo $portfolioID; ?> .title-block_<?php echo $portfolioID; ?> h3 {
        position:relative;
        margin :0 !important;
        padding :0 5px 0 5px !important;
        max-width:<?php echo $pfhub_portfolio_get_options['pfhub_portfolio_view1_block_width']; ?>px !important;
        width: 100%;
        text-overflow: ellipsis;
        overflow: hidden;
        /*white-space:nowrap;*/
        font-weight:normal;
        color:#<?php echo $pfhub_portfolio_get_options['pfhub_portfolio_view1_title_font_color']; ?>;
        font-size:<?php echo $pfhub_portfolio_get_options['pfhub_portfolio_view1_title_font_size']; ?>px !important;
        line-height:<?php echo $pfhub_portfolio_get_options['pfhub_portfolio_view1_title_font_size']+4; ?>px !important;
    }


    .wd-portfolio-panel_<?php echo $portfolioID; ?> {
        position: relative;
        display:block;
        width: calc(100% - 10px);
        margin:0 5px 0 5px;
        padding :0;
        text-align:left;
    }

    .wd-portfolio-panel_<?php echo $portfolioID; ?> .description-block_<?php echo $portfolioID; ?> p,.wd-portfolio-panel_<?php echo $portfolioID; ?> .description-block_<?php echo $portfolioID; ?> * {
        text-align:justify;
        font-size:<?php echo $pfhub_portfolio_get_options['pfhub_portfolio_view1_description_font_size']; ?>px !important;
        color:#<?php echo $pfhub_portfolio_get_options['pfhub_portfolio_view1_description_color']; ?>;
        margin :0 !important;
        padding :0 !important;
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
        margin :0 !important;
    }

    .wd-portfolio-panel_<?php echo $portfolioID; ?> .description-block_<?php echo $portfolioID; ?> ul,
    .wd-portfolio-panel_<?php echo $portfolioID; ?> .description-block_<?php echo $portfolioID; ?> li {
        padding:2px 0 2px 5px;
        margin :0 0 0 8px;
    }


    .wd-portfolio-panel_<?php echo $portfolioID; ?> .thumbs-list_<?php echo $portfolioID; ?> {
        list-style:none;
        clear:both;
        display:table;
        width:100%;
        padding :0;
        margin:3px 0 0 0;
        text-align:center;
    }

    .wd-portfolio-panel_<?php echo $portfolioID; ?> .thumbs-list_<?php echo $portfolioID; ?> li {
        display:inline-block;
        margin :0 3px 0 2px;
    }

    .wd-portfolio-panel_<?php echo $portfolioID; ?> .thumbs-list_<?php echo $portfolioID; ?> li a {
        display:block;
        width:<?php echo $pfhub_portfolio_get_options['pfhub_portfolio_view1_thumbs_width']; ?>px;
        height:<?php echo $pfhub_portfolio_get_options['pfhub_portfolio_view1_thumbs_width']; ?>px;
        opacity:0.7;
        display:table;
        border: none;
        box-shadow: none;

    }

    .wd-portfolio-panel_<?php echo $portfolioID; ?> .thumbs-list_<?php echo $portfolioID; ?> li a:hover {
        opacity:1;
    }

    .wd-portfolio-panel_<?php echo $portfolioID; ?> img {
        margin :0 !important;
        padding :0 !important;
        display:table-cell;
        vertical-align:middle;
        width:<?php echo $pfhub_portfolio_get_options['pfhub_portfolio_view1_thumbs_width']; ?>px !important;
        max-height:<?php echo $pfhub_portfolio_get_options['pfhub_portfolio_view1_thumbs_width']; ?>px !important;
        width:100%;
        height:100%;
    }

    .wd-portfolio-panel_<?php echo $portfolioID; ?> > div {
        padding-top:10px;
        margin-bottom:10px;
    <?php if($pfhub_portfolio_get_options['pfhub_portfolio_view1_show_separator_lines']=="on") {?>
        background:url('<?php echo  PFHUB_PORTFOLIO_IMAGES_URL.'/admin/divider.line.png'; ?>') center top repeat-x;
    <?php } ?>
    }

    .wd-portfolio-panel_<?php echo $portfolioID; ?> .button-block {
        padding-top:10px;
        margin-bottom:10px;

    }

    .wd-portfolio-panel_<?php echo $portfolioID; ?> .button-block a, .wd-portfolio-panel_<?php echo $portfolioID; ?> .button-block a:link, .wd-portfolio-panel_<?php echo $portfolioID; ?> .button-block a:visited {
        padding:10px;
        display:inline-block;
        font-size:<?php echo $pfhub_portfolio_get_options['pfhub_portfolio_view1_linkbutton_font_size']; ?>px;
        background:#<?php echo $pfhub_portfolio_get_options['pfhub_portfolio_view1_linkbutton_background_color']; ?>;
        color:#<?php echo $pfhub_portfolio_get_options['pfhub_portfolio_view1_linkbutton_color']; ?>;
        padding:6px 12px;
        text-decoration:none;
    }

    .wd-portfolio-panel_<?php echo $portfolioID; ?> .button-block a:hover, .wd-portfolio-panel_<?php echo $portfolioID; ?> .button-block a:focus, .wd-portfolio-panel_<?php echo $portfolioID; ?> .button-block a:active {
        background:#<?php echo $pfhub_portfolio_get_options['pfhub_portfolio_view1_linkbutton_background_hover_color']; ?>;
        color:#<?php echo $pfhub_portfolio_get_options['pfhub_portfolio_view1_linkbutton_font_hover_color']; ?>;
        text-decoration:none;
    }

    #pfhub_portfolio_content_<?php echo $portfolioID; ?> #pfhub_portfolio_options_<?php echo $portfolioID; ?> {
        position: relative;
        overflow: hidden;
    <?php   if($sortingFloatFullHeight != 'top'){
                echo 'float:'.$sortingFloatFullHeight.';margin-top:5px;';
                echo  "max-width:180px;width:20%;display:inline-block;";
                if($filteringFloatFullHeight == 'top') echo 'margin-top:45px;';
                if($sortingFloatFullHeight == 'left') echo 'margin-right: 1%;';
                else echo 'margin-left:1%;';
            }
            else {
                if($portfolioposition == 'on' && ($filteringFloatFullHeight == 'top' || $filteringFloatFullHeight == '')) echo 'left:50%; transform:translateX(-50%);';
                if($filteringFloatFullHeight == 'left') echo 'margin-left:calc( 185px + 1%);';else echo 'margin-left:5px;';
                echo 'width: auto; margin-bottom: 5px;display:table;';
            }
            if(($sortingFloatFullHeight == 'left' && $filteringFloatFullHeight == 'left') || ($sortingFloatFullHeight == 'right' && $filteringFloatFullHeight == 'right')){
                echo 'width: 100%;';
            }
    ?>

    <?php
        if($portfolioShowLoading == 'on') echo 'opacity: 0;';
    ?>
        margin-bottom: 10px;
    }

    #pfhub_portfolio_content_<?php echo $portfolioID; ?> #pfhub_portfolio_options_<?php echo $portfolioID; ?> ul {
        margin : 0 !important;
        padding : 0 !important;
        list-style: none;
    <?php if($sortingFloatFullHeight == 'top') {
          echo "float:left;margin-left:1%;";
          } ?>
    }

    #pfhub_portfolio_content_<?php echo $portfolioID; ?> #pfhub_portfolio_filters_<?php echo $portfolioID; ?> ul {
        margin : 0 !important;
        padding : 0 !important;
        overflow: hidden;
    <?php if($filteringFloatFullHeight == 'top') {
        echo "float:left;margin-left:1%;";
        } ?>
        width: 100%;
    }

    <?php if($sortingFloatFullHeight == '') { ?>
    #pfhub_portfolio_content_<?php echo $portfolioID; ?> #pfhub_portfolio_options_<?php echo $portfolioID; ?> ul {
        float: left;
    }
    <?php } ?>


    #pfhub_portfolio_content_<?php echo $portfolioID; ?> #pfhub_portfolio_options_<?php echo $portfolioID; ?> ul li {
        border-radius: <?php echo $pfhub_portfolio_get_options["pfhub_portfolio_view1_sortbutton_border_radius"];?>px;
        list-style-type: none;
        margin : 0 !important;
        padding: 0;
    <?php
        if($sortingFloatFullHeight == "top")
        { echo "float:left !important;margin : 0 8px 4px 0 !important;"; }
        if($sortingFloatFullHeight == "left" || $sortingFloatFullHeight == "right")
        { echo 'border-bottom: 1px solid #ccc;'; }
        else
        { echo 'border: 1px solid #ccc;'; }
    ?>
    }

    #pfhub_portfolio_content_<?php echo $portfolioID; ?> #pfhub_portfolio_options_<?php echo $portfolioID; ?> ul li a {
        background-color: #<?php echo $pfhub_portfolio_get_options["pfhub_portfolio_view1_sortbutton_background_color"];?> !important;
        border-radius: <?php echo $pfhub_portfolio_get_options["pfhub_portfolio_view1_sortbutton_border_radius"];?>px;
        font-size:<?php echo $pfhub_portfolio_get_options["pfhub_portfolio_view1_sortbutton_font_size"];?>px !important;
        color:#<?php echo $pfhub_portfolio_get_options["pfhub_portfolio_view1_sortbutton_font_color"];?> !important;
        text-decoration: none;
        cursor: pointer;
        margin : 0 !important;
        display: block;
        padding:<?php echo $pfhub_portfolio_get_options["pfhub_portfolio_view1_sortbutton_border_padding"];?>px;
    }

    #pfhub_portfolio_content_<?php echo $portfolioID; ?> #pfhub_portfolio_options_<?php echo $portfolioID; ?> ul li a:hover {
        background-color: #<?php echo $pfhub_portfolio_get_options["pfhub_portfolio_view1_sortbutton_hover_background_color"];?> !important;
        color:#<?php echo $pfhub_portfolio_get_options["pfhub_portfolio_view1_sortbutton_hover_font_color"];?> !important;
        cursor: pointer;
    }

    #pfhub_portfolio_content_<?php echo $portfolioID; ?> #pfhub_portfolio_filters_<?php echo $portfolioID; ?> {
        position: relative;
        overflow: hidden;
    <?php   if($filteringFloatFullHeight != 'top'){
                echo 'float:'.$filteringFloatFullHeight.';margin-top:5px;';
                echo  "max-width:180px;width:20%;display:inline-block;";
                if($filteringFloatFullHeight == 'left') echo 'margin-right: 1%;';
                else echo 'margin-left:1%;';
            }
            else {
                if($portfolioposition == 'on' && ($sortingFloatFullHeight == 'top' || $sortingFloatFullHeight == '')) echo 'left:50%; transform:translateX(-50%);';
                if($sortingFloatFullHeight == 'left') echo 'margin-left:calc( 185px + 1%);';else echo 'margin-left:5px;';
                echo 'width: auto; margin-bottom: 5px;display:table;';
            }
            if(($sortingFloatFullHeight == 'left' && $filteringFloatFullHeight == 'left') || ($sortingFloatFullHeight == 'right' && $filteringFloatFullHeight == 'right')){
                echo 'width: 100%;';
            }
    ?>

    <?php
        if($portfolioShowLoading == 'on') echo 'opacity: 0;';
    ?>
    }

    #pfhub_portfolio_content_<?php echo $portfolioID; ?> #pfhub_portfolio_filters_<?php echo $portfolioID; ?> ul li {
        list-style-type: none;
        border-radius: <?php echo $pfhub_portfolio_get_options["pfhub_portfolio_view1_filterbutton_border_radius"];?>px;
    <?php
        if($filteringFloatFullHeight == "top") { echo "float:left !important;margin : 0 8px 4px 0 !important;"; }
        if($filteringFloatFullHeight == "left" || $filteringFloatFullHeight == "right")
        { echo 'border-bottom: 1px solid #ccc;'; }
        else echo "border: 1px solid #ccc;";
    ?>
    }

    #pfhub_portfolio_content_<?php echo $portfolioID; ?> #pfhub_portfolio_filters_<?php echo $portfolioID; ?> ul li a {
        font-size:<?php echo $pfhub_portfolio_get_options["pfhub_portfolio_view1_filterbutton_font_size"];?>px !important;
        color:#<?php echo $pfhub_portfolio_get_options["pfhub_portfolio_view1_filterbutton_font_color"];?> !important;
        background-color: #<?php echo $pfhub_portfolio_get_options["pfhub_portfolio_view1_filterbutton_background_color"];?> !important;
        border-radius: <?php echo $pfhub_portfolio_get_options["pfhub_portfolio_view1_filterbutton_border_radius"];?>px;
        padding:<?php echo $pfhub_portfolio_get_options["pfhub_portfolio_view1_filterbutton_border_padding"];?>px;
        display: block;
        text-decoration: none;
    }

    #pfhub_portfolio_content_<?php echo $portfolioID; ?> #pfhub_portfolio_filters_<?php echo $portfolioID; ?>  ul li a:hover {
        color:#<?php echo $pfhub_portfolio_get_options["pfhub_portfolio_view1_filterbutton_hover_font_color"];?> !important;
        background-color: #<?php echo $pfhub_portfolio_get_options["pfhub_portfolio_view1_filterbutton_hover_background_color"];?> !important;
        cursor: pointer
    }
    #pfhub_portfolio_content_<?php echo $portfolioID; ?> #pfhub_portfolio_filters_<?php echo $portfolioID; ?> ul li.active a,
    #pfhub_portfolio_content_<?php echo $portfolioID; ?> #pfhub_portfolio_filters_<?php echo $portfolioID; ?> ul li.active a:link,
    #pfhub_portfolio_content_<?php echo $portfolioID; ?> #pfhub_portfolio_filters_<?php echo $portfolioID; ?> ul li.active a:visited,
    #pfhub_portfolio_content_<?php echo $portfolioID; ?> #pfhub_portfolio_filters_<?php echo $portfolioID; ?>  ul li.active a:hover,
    #pfhub_portfolio_content_<?php echo $portfolioID; ?> #pfhub_portfolio_filters_<?php echo $portfolioID; ?>  ul li.active a:focus,
    #pfhub_portfolio_content_<?php echo $portfolioID; ?> #pfhub_portfolio_filters_<?php echo $portfolioID; ?>  ul li.active a:active {
        color:#<?php echo $pfhub_portfolio_get_options["pfhub_portfolio_view1_filterbutton_hover_font_color"];?> !important;
        background-color: #<?php echo $pfhub_portfolio_get_options["pfhub_portfolio_view1_filterbutton_hover_background_color"];?> !important;
        cursor: pointer;
    }
    #pfhub_portfolio_content_<?php echo $portfolioID; ?> section {
        position:relative;
        display:block;
    }

    #pfhub_portfolio_content_<?php echo $portfolioID; ?> #pfhub_portfolio_container_<?php echo $portfolioID; ?> {
        width: 79%;
        max-width: 100% !important;
    <?php if(($sortingFloatFullHeight == "left" && $filteringFloatFullHeight == "right") || ($sortingFloatFullHeight == "right" && $filteringFloatFullHeight == "left"))
        {echo "margin : 0 auto;width:58%;"; }
        if(($filteringFloatFullHeight == "left" || $filteringFloatFullHeight == "right" && $sortingFloatFullHeight == "top") || ($sortingFloatFullHeight == "left" || $sortingFloatFullHeight == "right" && $filteringFloatFullHeight == "top"))
        {echo 'float:left;';}
        if(($portfolioShowSorting == 'off' && $portfolioShowFiltering == 'off') || ($sortingFloatFullHeight == 'top' && $filteringFloatFullHeight == 'top') ||
            ($sortingFloatFullHeight == 'top' && $filteringFloatFullHeight == '') || ($sortingFloatFullHeight == '' && $filteringFloatFullHeight == 'top'))
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
    @media screen and (max-width: <?php echo $pfhub_portfolio_get_options['pfhub_portfolio_view0_block_width']+2*$pfhub_portfolio_get_options['pfhub_portfolio_view1_element_border_width']+40; ?>px) {
        .portelement_<?php echo $portfolioID; ?>  {
            width: calc(98% - <?php echo 2*$pfhub_portfolio_get_options['pfhub_portfolio_view1_element_border_width']; ?>px);
            margin: 1% !important;
            float: left;
            overflow: hidden;
            outline:none;
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
        float:<?php echo $sortingFloatFullHeight; ?>;
    <?php if($sortingFloatFullHeight == 'left') echo 'margin-right: 1%;';
        else echo 'margin-left:1%;';
    ?>
    }
</style>
