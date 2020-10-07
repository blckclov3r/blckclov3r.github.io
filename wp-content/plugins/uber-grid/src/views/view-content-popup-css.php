<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
<style>
section#pfhub_portfolio_content_<?php echo $portfolioID; ?> {
    position:relative;
    display:block;
}

.portelement_<?php echo $portfolioID; ?> .play-icon.youtube-icon,
.play-icon.youtube-icon {
    background: url(<?php echo  PFHUB_PORTFOLIO_IMAGES_URL.'/admin/play.youtube.png';?>) center center no-repeat;
    background-size: 30% 30%;
}
.portelement_<?php echo $portfolioID; ?> .play-icon.vimeo-icon,
.play-icon.vimeo-icon {
    background: url(<?php echo  PFHUB_PORTFOLIO_IMAGES_URL.'/admin/play.vimeo.png';?>) center center no-repeat;
    background-size: 30% 30%;
}

.portelement_<?php echo $portfolioID; ?> .play-icon,.play-icon {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
}
.portelement_<?php echo $portfolioID; ?> {
    max-width:<?php echo $pfhub_portfolio_get_options['pfhub_portfolio_view2_element_width']+2*$pfhub_portfolio_get_options['pfhub_portfolio_view2_element_border_width']; ?>px !important;
    width:100%;
    margin:0 0 10px 0;
    background:#<?php echo $pfhub_portfolio_get_options['pfhub_portfolio_view2_element_background_color']; ?>;
    border:<?php echo $pfhub_portfolio_get_options['pfhub_portfolio_view2_element_border_width']; ?>px solid #<?php echo $pfhub_portfolio_get_options['pfhub_portfolio_view2_element_border_color']; ?>;
    outline:none;
    box-sizing: border-box;
}

.portelement_<?php echo $portfolioID; ?> .image-block_<?php echo $portfolioID; ?> {
<?php if($pfhub_portfolio_get_options['pfhub_portfolio_port_natural_size_contentpopup']=='resize'){?>
    position:relative;
    width:100%;
<?php }elseif($pfhub_portfolio_get_options['pfhub_portfolio_port_natural_size_contentpopup']=='natural'){?>
    position:relative;
    width:100%;
    overflow: hidden;
<?php }?>
    height:<?php echo $pfhub_portfolio_get_options['pfhub_portfolio_view2_element_height']; ?>px !important;
}

.portelement_<?php echo $portfolioID; ?> .image-block_<?php echo $portfolioID; ?> img {
<?php if($pfhub_portfolio_get_options['pfhub_portfolio_port_natural_size_contentpopup']=='resize'){?>
    margin:0 !important;
    padding:0 !important;
    width:100% !important;
    height:<?php echo $pfhub_portfolio_get_options['pfhub_portfolio_view2_element_height']; ?>px !important;
    display:block;
    border-radius: 0 !important;
    box-shadow: 0 0 0 rgba(0, 0, 0, 0) !important;
<?php }elseif($pfhub_portfolio_get_options['pfhub_portfolio_port_natural_size_contentpopup']=='natural'){?>
    display:block;
    max-width: none !important;
    border-radius: 0 !important;
    box-shadow: 0 0 0 rgba(0, 0, 0, 0) !important;
<?php }?>
}

.portelement_<?php echo $portfolioID; ?> .image-block_<?php echo $portfolioID; ?> .image-overlay {
    position:absolute;
    top:0;
    left:0;
    width:100%;
    height:100%;
    background: <?php
			list($r,$g,$b) = array_map('hexdec',str_split($pfhub_portfolio_get_options['pfhub_portfolio_view2_element_overlay_color'],2));
				$titleopacity=$pfhub_portfolio_get_options["pfhub_portfolio_view2_element_overlay_transparency"]/100;
				echo 'rgba('.$r.','.$g.','.$b.','.$titleopacity.')  !important';
	?>;
    display:none;
}

.portelement_<?php echo $portfolioID; ?>:hover .image-block_<?php echo $portfolioID; ?>  .image-overlay {
    display:block;
}

.portelement_<?php echo $portfolioID; ?> .image-block_<?php echo $portfolioID; ?> .image-overlay a {
    border:none;
    position:absolute;
    top:0;
    left:0;
    display:block;
    width:100%;
    height:100%;
    background:url('<?php echo  PFHUB_PORTFOLIO_IMAGES_URL.'/admin/zoom.'.$pfhub_portfolio_get_options["pfhub_portfolio_view2_zoombutton_style"].'.png'; ?>') center center no-repeat;
}

.portelement_<?php echo $portfolioID; ?> .title-block_<?php echo $portfolioID; ?> {
    position:relative;
    height: <?php echo 24+$pfhub_portfolio_get_options['pfhub_portfolio_view2_element_title_font_size']; ?>px;
    margin: 0;
    border-top:<?php echo $pfhub_portfolio_get_options['pfhub_portfolio_view2_element_border_width']; ?>px solid #<?php echo $pfhub_portfolio_get_options['pfhub_portfolio_view2_element_border_color']; ?>;
    box-sizing: content-box;
}

.portelement_<?php echo $portfolioID; ?> .title-block_<?php echo $portfolioID; ?> h3 {
    position:relative;
    margin:0 !important;
    padding:0 1% 0 1% !important;
    width:90%;
    text-overflow: ellipsis;
    overflow: hidden;
    white-space:nowrap;
    font-weight:normal;
    top: 50%;
    transform: translateY(-50%);
    font-size: <?php echo $pfhub_portfolio_get_options["pfhub_portfolio_view2_element_title_font_size"];?>px !important;
    line-height: <?php echo $pfhub_portfolio_get_options["pfhub_portfolio_view2_element_title_font_size"]+4;?>px !important;
    color:#<?php echo $pfhub_portfolio_get_options["pfhub_portfolio_view2_element_title_font_color"];?>;
}

.portelement_<?php echo $portfolioID; ?> .title-block_<?php echo $portfolioID; ?> .button-block {
    position:absolute;
    right:0;
    top:0;
    display:none;
    vertical-align:middle;
    height:30px;
    padding:10px 10px 4px 10px;
}
.portelement_<?php echo $portfolioID; ?>:hover .title-block_<?php echo $portfolioID; ?> .button-block {display:block;}

.portelement_<?php echo $portfolioID; ?> .title-block_<?php echo $portfolioID; ?> a,.portelement_<?php echo $portfolioID; ?> .title-block_<?php echo $portfolioID; ?> a:link,.portelement_<?php echo $portfolioID; ?> .title-block_<?php echo $portfolioID; ?> a:visited,
.portelement_<?php echo $portfolioID; ?> .title-block_<?php echo $portfolioID; ?> a:hover,.portelement_<?php echo $portfolioID; ?> .title-block_<?php echo $portfolioID; ?> a:focus,.portelement_<?php echo $portfolioID; ?> .title-block_<?php echo $portfolioID; ?> a:active {
    position:relative;
    display:block;
    vertical-align:middle;
    padding: 3px 10px 3px 10px;
    border-radius:3px;
    font-size:<?php echo $pfhub_portfolio_get_options["pfhub_portfolio_view2_element_linkbutton_font_size"];?>px;
    background:#<?php echo $pfhub_portfolio_get_options["pfhub_portfolio_view2_element_linkbutton_background_color"];?>;
    color:#<?php echo $pfhub_portfolio_get_options["pfhub_portfolio_view2_element_linkbutton_color"];?>;
    text-decoration:none;
    border: none;
    box-shadow: none;
}

/*#####POPUP#####*/

#pfhub_portfolio_popup_list_<?php echo $portfolioID; ?> {
    position:fixed;
    display:table;
    width:80%;
    top:7%;
    left:7%;
    margin:0 !important;
    list-style:none;
    z-index:999999;
    display:none;
    height:90%;
}

#pfhub_portfolio_popup_list_<?php echo $portfolioID; ?>.active {display:table;}

#pfhub_portfolio_popup_list_<?php echo $portfolioID; ?> li.pupup-element {
    position:relative;
    display:none;
    width:100%;
    padding:40px 0 20px 0;
    min-height:100%;
    position:relative;
    background:#<?php echo $pfhub_portfolio_get_options["pfhub_portfolio_view2_popup_background_color"];?>;
}

#pfhub_portfolio_popup_list_<?php echo $portfolioID; ?> li.pupup-element.active {
    display:block;
}

#pfhub_portfolio_popup_list_<?php echo $portfolioID; ?> .heading-navigation_<?php echo $portfolioID; ?> {
    position:absolute;
    width:100%;
    height:40px;
    top:0;
    left:0;
    z-index:2001;
    background:url('<?php echo  PFHUB_PORTFOLIO_IMAGES_URL.'/admin/divider.line.png'; ?>') center bottom repeat-x;
}

#pfhub_portfolio_popup_list_<?php echo $portfolioID; ?> .heading-navigation_<?php echo $portfolioID; ?> .close,#pfhub_portfolio_popup_list_<?php echo $portfolioID; ?> .heading-navigation_<?php echo $portfolioID; ?> .close:link, #pfhub_portfolio_popup_list_<?php echo $portfolioID; ?> .heading-navigation_<?php echo $portfolioID; ?> .close:visited {
    position:relative;
    float:right;
    width:40px;
    height:40px;
    display:block;
    background:url('<?php echo  PFHUB_PORTFOLIO_IMAGES_URL.'/admin/close.popup.'.$pfhub_portfolio_get_options["pfhub_portfolio_view2_popup_closebutton_style"].'.png'; ?>') center center no-repeat;
    border-left:1px solid #ccc;
    opacity:.65;
}

#pfhub_portfolio_popup_list_<?php echo $portfolioID; ?> .heading-navigation_<?php echo $portfolioID; ?> .close:hover, #pfhub_portfolio_popup_list_<?php echo $portfolioID; ?> .heading-navigation_<?php echo $portfolioID; ?> .close:focus, #pfhub_portfolio_popup_list_<?php echo $portfolioID; ?> .heading-navigation_<?php echo $portfolioID; ?> .close:active {opacity:1;}


#pfhub_portfolio_popup_list_<?php echo $portfolioID; ?> li.pupup-element .pfhub-portfolio-popup-wrapper_<?php echo $portfolioID; ?> {
    position:relative;
    width:98%;
    height:98%;
    padding:2% 0% 0% 2%;
}

#pfhub_portfolio_popup_list_<?php echo $portfolioID; ?> .pfhub-portfolio-popup-wrapper_<?php echo $portfolioID; ?> .image-block_<?php echo $portfolioID; ?> {
    width:55%;
<?php if($pfhub_portfolio_get_options['pfhub_portfolio_view2_popup_full_width'] == 'off') { echo "height:100%;"; } else {echo "height:100%;";} ?>
    position:relative;
    float:left;
    margin-right:2%;
    border-right:1px solid #ccc;
    min-width:200px;
    min-height:100%;
}

#pfhub_portfolio_popup_list_<?php echo $portfolioID; ?> .pfhub-portfolio-popup-wrapper_<?php echo $portfolioID; ?> .image-block_<?php echo $portfolioID; ?> img {
<?php
    if($pfhub_portfolio_get_options['pfhub_portfolio_view2_popup_full_width'] == 'off') { echo "max-width:100% !important; max-height:100% !important; margin: 0 auto !important; position:relative;"; }
    else { echo "width:100% !important;"; }
?>
    display:block;
    padding:0 !important;
}

#pfhub_portfolio_popup_list_<?php echo $portfolioID; ?> .pfhub-portfolio-popup-wrapper_<?php echo $portfolioID; ?> .right-block {
    width:42.8%;
    height: 100%;
    position:relative;
    float:left;
}

#pfhub_portfolio_popup_list_<?php echo $portfolioID; ?> li.pupup-element .pfhub-portfolio-popup-wrapper_<?php echo $portfolioID; ?> .right-block > div {
    padding-top:10px;
    padding-right: 4%;
    margin-bottom:10px;
<?php if($pfhub_portfolio_get_options['pfhub_portfolio_view2_show_separator_lines']=="on") {?>
    background:url('<?php echo  PFHUB_PORTFOLIO_IMAGES_URL.'/admin/divider.line.png'; ?>') center top repeat-x;
<?php } ?>
}
#pfhub_portfolio_popup_list_<?php echo $portfolioID; ?> li.pupup-element .pfhub-portfolio-popup-wrapper_<?php echo $portfolioID; ?> .right-block > div:last-child {background:none;}


#pfhub_portfolio_popup_list_<?php echo $portfolioID; ?> .pfhub-portfolio-popup-wrapper_<?php echo $portfolioID; ?> .right-block .title {
    position:relative;
    display:block;
    margin:0 0 10px 0 !important;
    font-size:<?php echo $pfhub_portfolio_get_options["pfhub_portfolio_view2_popup_title_font_size"];?>px !important;
    line-height:<?php echo $pfhub_portfolio_get_options["pfhub_portfolio_view2_popup_title_font_size"]+4;?>px !important;
    color:#<?php echo $pfhub_portfolio_get_options["pfhub_portfolio_view2_popup_title_font_color"];?>;
}

#pfhub_portfolio_popup_list_<?php echo $portfolioID; ?> .pfhub-portfolio-popup-wrapper_<?php echo $portfolioID; ?> .right-block .description {
    clear:both;
    position:relative;
    text-align:justify;
    font-size:<?php echo $pfhub_portfolio_get_options["pfhub_portfolio_view2_description_font_size"];?>px !important;
    color:#<?php echo $pfhub_portfolio_get_options["pfhub_portfolio_view2_description_color"];?>;
}

#pfhub_portfolio_popup_list_<?php echo $portfolioID; ?> .pfhub-portfolio-popup-wrapper_<?php echo $portfolioID; ?> .right-block .description h1,
#pfhub_portfolio_popup_list_<?php echo $portfolioID; ?> .pfhub-portfolio-popup-wrapper_<?php echo $portfolioID; ?> .right-block .description h2,
#pfhub_portfolio_popup_list_<?php echo $portfolioID; ?> .pfhub-portfolio-popup-wrapper_<?php echo $portfolioID; ?> .right-block .description h3,
#pfhub_portfolio_popup_list_<?php echo $portfolioID; ?> .pfhub-portfolio-popup-wrapper_<?php echo $portfolioID; ?> .right-block .description h4,
#pfhub_portfolio_popup_list_<?php echo $portfolioID; ?> .pfhub-portfolio-popup-wrapper_<?php echo $portfolioID; ?> .right-block .description h5,
#pfhub_portfolio_popup_list_<?php echo $portfolioID; ?> .pfhub-portfolio-popup-wrapper_<?php echo $portfolioID; ?> .right-block .description h6,
#pfhub_portfolio_popup_list_<?php echo $portfolioID; ?> .pfhub-portfolio-popup-wrapper_<?php echo $portfolioID; ?> .right-block .description p,
#pfhub_portfolio_popup_list_<?php echo $portfolioID; ?> .pfhub-portfolio-popup-wrapper_<?php echo $portfolioID; ?> .right-block .description strong,
#pfhub_portfolio_popup_list_<?php echo $portfolioID; ?> .pfhub-portfolio-popup-wrapper_<?php echo $portfolioID; ?> .right-block .description span {
    padding:2px !important;
    margin:0 !important;
}

#pfhub_portfolio_popup_list_<?php echo $portfolioID; ?> .pfhub-portfolio-popup-wrapper_<?php echo $portfolioID; ?> .right-block .description ul,
#pfhub_portfolio_popup_list_<?php echo $portfolioID; ?> .pfhub-portfolio-popup-wrapper_<?php echo $portfolioID; ?> .right-block .description li {
    padding:2px 0 2px 5px;
    margin:0 0 0 8px;
}

#pfhub_portfolio_popup_list_<?php echo $portfolioID; ?> .pfhub-portfolio-popup-wrapper_<?php echo $portfolioID; ?> .right-block ul.thumbs-list_<?php echo $portfolioID; ?> {
    list-style:none;
    display:table;
    position:relative;
    clear:both;
    width:100%;
    margin:0 auto;
    padding:0;
}

#pfhub_portfolio_popup_list_<?php echo $portfolioID; ?> .pfhub-portfolio-popup-wrapper_<?php echo $portfolioID; ?> .right-block ul.thumbs-list_<?php echo $portfolioID; ?> li {
    display:block;
    float:left;
    width:<?php echo $pfhub_portfolio_get_options["pfhub_portfolio_view2_thumbs_width"];?>px;
    height:<?php echo $pfhub_portfolio_get_options["pfhub_portfolio_view2_thumbs_height"];?>px;
    margin:0 2% 5px 1% !important;
    opacity:0.45;
}

#pfhub_portfolio_popup_list_<?php echo $portfolioID; ?> .pfhub-portfolio-popup-wrapper_<?php echo $portfolioID; ?> .right-block ul.thumbs-list_<?php echo $portfolioID; ?> li.active,#pfhub_portfolio_popup_list_<?php echo $portfolioID; ?> .pfhub-portfolio-popup-wrapper_<?php echo $portfolioID; ?> .right-block ul.thumbs-list_<?php echo $portfolioID; ?> li:hover {
    opacity:1;
}

#pfhub_portfolio_popup_list_<?php echo $portfolioID; ?> .pfhub-portfolio-popup-wrapper_<?php echo $portfolioID; ?> .right-block ul.thumbs-list_<?php echo $portfolioID; ?> li a {
    border:none;
    display:block;
}

#pfhub_portfolio_popup_list_<?php echo $portfolioID; ?> .pfhub-portfolio-popup-wrapper_<?php echo $portfolioID; ?> .right-block ul.thumbs-list_<?php echo $portfolioID; ?> li img {
    margin:0 !important;
    padding:0 !important;
    width:<?php echo $pfhub_portfolio_get_options["pfhub_portfolio_view2_thumbs_width"];?>px !important;
    height:<?php echo $pfhub_portfolio_get_options["pfhub_portfolio_view2_thumbs_height"];?>px !important;
}

#pfhub_portfolio_popup_list_<?php echo $portfolioID; ?> .pfhub-portfolio-popup-wrapper_<?php echo $portfolioID; ?> .image-block_<?php echo $portfolioID; ?> iframe  {
    width:100% !important;
    height:60% !important;
    display:block;
}

#pfhub_portfolio_popup_list_<?php echo $portfolioID; ?> .heading-navigation_<?php echo $portfolioID; ?> .left-change, #pfhub_portfolio_popup_list_<?php echo $portfolioID; ?> .heading-navigation_<?php echo $portfolioID; ?> .right-change{
    width: 40px;
    height: 39px;
    font-size: 25px;
    display: inline-block;
    text-align: center;
    border: 1px solid #eee;
    border-bottom: none;
    border-top: none;
}

#pfhub_portfolio_popup_list_<?php echo $portfolioID; ?> .heading-navigation_<?php echo $portfolioID; ?> .right-change{
    position: relative;
    margin-left: -6px;
}

#pfhub_portfolio_popup_list_<?php echo $portfolioID; ?> .heading-navigation_<?php echo $portfolioID; ?> .right-change:hover, #pfhub_portfolio_popup_list_<?php echo $portfolioID; ?> .heading-navigation_<?php echo $portfolioID; ?> .left-change:hover{
    background: #ddd;
    border-color: #ccc;
    color: #000 !important;
    cursor: pointer;
}

#pfhub_portfolio_popup_list_<?php echo $portfolioID; ?> .heading-navigation_<?php echo $portfolioID; ?> .right-change a, #pfhub_portfolio_popup_list_<?php echo $portfolioID; ?> .heading-navigation_<?php echo $portfolioID; ?> .left-change a{
    position: absolute;
    transform: translate(-50%, -50%) !important;
    color: #777;
    text-decoration: none;
    width: 12px;
    height: 100%;
    display: inline-block;
    line-height: normal;
    top: 50%;
}


/**/
.pupup-element .button-block {
    position:relative;
}

.pupup-element .button-block a,.pupup-element .button-block a:link,.pupup-element .button-block a:visited{
    position:relative;
    display:inline-block;
    padding:6px 12px;
    background:#<?php echo $pfhub_portfolio_get_options["pfhub_portfolio_view2_popup_linkbutton_background_color"];?>;
    color:#<?php echo $pfhub_portfolio_get_options["pfhub_portfolio_view2_popup_linkbutton_color"];?>;
    font-size:<?php echo $pfhub_portfolio_get_options["pfhub_portfolio_view2_popup_linkbutton_font_size"];?>px;
    text-decoration:none;
}

.pupup-element .button-block a:hover,.pupup-element .button-block a:focus,.pupup-element .button-block a:active {
    background:#<?php echo $pfhub_portfolio_get_options["pfhub_portfolio_view2_popup_linkbutton_background_hover_color"];?>;
    color:#<?php echo $pfhub_portfolio_get_options["pfhub_portfolio_view2_popup_linkbutton_font_hover_color"];?>;
}


#pfhub_portfolio-popup-overlay-portfolio {
    position:fixed;
    top:0;
    left:0;
    width:100%;
    height:100%;
    z-index:199;
    background: <?php
			list($r,$g,$b) = array_map('hexdec',str_split($pfhub_portfolio_get_options['pfhub_portfolio_view2_popup_overlay_color'],2));
				$titleopacity=$pfhub_portfolio_get_options["pfhub_portfolio_view2_popup_overlay_transparency_color"]/100;
				echo 'rgba('.$r.','.$g.','.$b.','.$titleopacity.')  !important';
	?>
}


@media only screen and (max-width: 767px) {

    #pfhub_portfolio_popup_list_<?php echo $portfolioID; ?> {
        position:absolute;
        left:0;
        top:0;
        width:100%;
        height:auto !important;
        left:0;
    }

    #pfhub_portfolio_popup_list_<?php echo $portfolioID; ?> li.pupup-element {
        margin:0;
        height:auto !important;
        position:absolute;
        left:0;
        top:0;
    }

    #pfhub_portfolio_popup_list_<?php echo $portfolioID; ?> li.pupup-element .pfhub-portfolio-popup-wrapper_<?php echo $portfolioID; ?> {
        height:auto !important;
        overflow-y:auto;
    }


    #pfhub_portfolio_popup_list_<?php echo $portfolioID; ?> .pfhub-portfolio-popup-wrapper_<?php echo $portfolioID; ?> .image-block_<?php echo $portfolioID; ?> {
        width:100%;
        float:none;
        clear:both;
        margin-right:0;
        border-right:0;
    }

    #pfhub_portfolio_popup_list_<?php echo $portfolioID; ?> .pfhub-portfolio-popup-wrapper_<?php echo $portfolioID; ?> .right-block {
        width:100%;
        float:none;
        clear:both;
        margin-right:0;
        border-right:0;
    }

    #pfhub_portfolio-popup-overlay-portfolio_<?php echo $portfolioID; ?> {
        position:fixed;
        top:0;
        left:0;
        width:100%;
        height:100%;
        z-index:199;
    }

}

#pfhub_portfolio_content_<?php echo $portfolioID; ?> #pfhub_portfolio_options_<?php echo $portfolioID; ?> {
    position: relative;
    overflow: hidden;
<?php   if($sortingFloatPopup != 'top'){
            echo 'float:'.$sortingFloatPopup.';';
            echo  "max-width:180px;width:20%;display:inline-block;";
            if($filteringFloatPopup == 'top') echo 'margin-top:40px;';
            if($sortingFloatPopup == 'left') echo 'margin-right: 1%;';
            else echo 'margin-left:1%;';
        }
        else {
            if($portfolioposition == 'on' && ($filteringFloatPopup == 'top' || $filteringFloatPopup == '')) echo 'left:50%; transform:translateX(-50%);';
            if($filteringFloatPopup == 'left') echo 'margin-left:calc( 185px + 1%);';
            echo 'width: auto; margin-bottom: 5px;display:table;';
        }
        if(($sortingFloatPopup == 'left' && $filteringFloatPopup == 'left') || ($sortingFloatPopup == 'right' && $filteringFloatPopup == 'right')){
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
<?php if($sortingFloatPopup == 'top') {
      echo "float:left;margin-left:1%;";
      } ?>
}

#pfhub_portfolio_content_<?php echo $portfolioID; ?> #pfhub_portfolio_filters_<?php echo $portfolioID; ?> ul {
    margin: 0 !important;
    padding: 0 !important;
    overflow: hidden;
<?php if($filteringFloatPopup == 'top') {
    echo "float:left;margin-left:1%;";
    } ?>
    width: 100%;
}

<?php if($sortingFloatPopup == 'top') { ?>
#pfhub_portfolio_content_<?php echo $portfolioID; ?> #pfhub_portfolio_options_<?php echo $portfolioID; ?> ul {
    float: left;
}
<?php } ?>


#pfhub_portfolio_content_<?php echo $portfolioID; ?> #pfhub_portfolio_options_<?php echo $portfolioID; ?> ul li {
    border-radius: <?php echo $pfhub_portfolio_get_options["pfhub_portfolio_view2_sortbutton_border_radius"];?>px;
    list-style-type: none;
    margin: 0 !important;
    padding: 0;
<?php
    if($sortingFloatPopup == "top")
    { echo "float:left !important;margin: 0 8px 4px 0 !important;"; }
    if($sortingFloatPopup == "left" || $sortingFloatPopup == "right")
    { echo 'border-bottom: 1px solid #ccc;'; }
    else
    { echo 'border: 1px solid #ccc;'; }
?>
}

#pfhub_portfolio_content_<?php echo $portfolioID; ?> #pfhub_portfolio_options_<?php echo $portfolioID; ?> ul li a {
    background-color: #<?php echo $pfhub_portfolio_get_options["pfhub_portfolio_view2_sortbutton_background_color"];?> !important;
    font-size:<?php echo $pfhub_portfolio_get_options["pfhub_portfolio_view2_sortbutton_font_size"];?>px !important;
    color:#<?php echo $pfhub_portfolio_get_options["pfhub_portfolio_view2_sortbutton_font_color"];?> !important;
    text-decoration: none;
    cursor: pointer;
    margin: 0 !important;
    display: block;
    padding:<?php echo $pfhub_portfolio_get_options["pfhub_portfolio_view2_sortbutton_border_padding"];?>px;
    border-radius: <?php echo $pfhub_portfolio_get_options["pfhub_portfolio_view2_sortbutton_border_radius"];?>px;
}

/*#pfhub_portfolio_content_<?php echo $portfolioID; ?> #pfhub_portfolio_options_<?php echo $portfolioID; ?> ul li:hover {

}*/

#pfhub_portfolio_content_<?php echo $portfolioID; ?> #pfhub_portfolio_options_<?php echo $portfolioID; ?> ul li a:hover {
    background-color: #<?php echo $pfhub_portfolio_get_options["pfhub_portfolio_view2_sortbutton_hover_background_color"];?> !important;
    color:#<?php echo $pfhub_portfolio_get_options["pfhub_portfolio_view2_sortbutton_hover_font_color"];?> !important;
    cursor: pointer;
}

#pfhub_portfolio_content_<?php echo $portfolioID; ?> #pfhub_portfolio_filters_<?php echo $portfolioID; ?> {
    position: relative;
    overflow: hidden;
<?php   if($filteringFloatPopup != 'top'){
            echo 'float:'.$filteringFloatPopup.';';
            echo  "max-width:180px;width:20%;display:inline-block;";
            if($filteringFloatPopup == 'left') echo 'margin-right: 1%;';
            else echo 'margin-left:1%;';
        }
        else {
            if($portfolioposition == 'on' && ($sortingFloatPopup == 'top' || $sortingFloatPopup == '')) echo 'left:50%; transform:translateX(-50%);';
            echo 'width: auto; margin-bottom: 5px;display:table;';
        }
        if(($sortingFloatPopup == 'left' && $filteringFloatPopup == 'left') || ($sortingFloatPopup == 'right' && $filteringFloatPopup == 'right')){
            echo 'width: 100%;';
        }
?>

<?php
    if($portfolioShowLoading == 'on') echo 'opacity: 0;';
?>
}

#pfhub_portfolio_content_<?php echo $portfolioID; ?> #pfhub_portfolio_filters_<?php echo $portfolioID; ?> ul li {
    list-style-type: none;
    border-radius: <?php echo $pfhub_portfolio_get_options["pfhub_portfolio_view2_filterbutton_border_radius"];?>px;
<?php
    if($filteringFloatPopup == "top") { echo "float:left !important;margin: 0 8px 4px 0 !important;"; }
    if($filteringFloatPopup == "left" || $filteringFloatPopup == "right")
    { echo 'border-bottom: 1px solid #ccc;'; }
    else echo "border: 1px solid #ccc;";
?>
}

#pfhub_portfolio_content_<?php echo $portfolioID; ?> #pfhub_portfolio_filters_<?php echo $portfolioID; ?> ul li a {
    font-size:<?php echo $pfhub_portfolio_get_options["pfhub_portfolio_view2_filterbutton_font_size"];?>px !important;
    color:#<?php echo $pfhub_portfolio_get_options["pfhub_portfolio_view2_filterbutton_font_color"];?> !important;
    background-color: #<?php echo $pfhub_portfolio_get_options["pfhub_portfolio_view2_filterbutton_background_color"];?> !important;
    border-radius: <?php echo $pfhub_portfolio_get_options["pfhub_portfolio_view2_filterbutton_border_radius"];?>px;
    padding:<?php echo $pfhub_portfolio_get_options["pfhub_portfolio_view2_filterbutton_border_padding"];?>px;
    display: block;
    text-decoration: none;
}

#pfhub_portfolio_content_<?php echo $portfolioID; ?> #pfhub_portfolio_filters_<?php echo $portfolioID; ?>  ul li a:hover {
    color:#<?php echo $pfhub_portfolio_get_options["pfhub_portfolio_view2_filterbutton_hover_font_color"];?> !important;
    background-color: #<?php echo $pfhub_portfolio_get_options["pfhub_portfolio_view2_filterbutton_hover_background_color"];?> !important;
    cursor: pointer
}
#pfhub_portfolio_content_<?php echo $portfolioID; ?> #pfhub_portfolio_filters_<?php echo $portfolioID; ?> ul li.active a,
#pfhub_portfolio_content_<?php echo $portfolioID; ?> #pfhub_portfolio_filters_<?php echo $portfolioID; ?> ul li.active a:link,
#pfhub_portfolio_content_<?php echo $portfolioID; ?> #pfhub_portfolio_filters_<?php echo $portfolioID; ?> ul li.active a:visited,
#pfhub_portfolio_content_<?php echo $portfolioID; ?> #pfhub_portfolio_filters_<?php echo $portfolioID; ?>  ul li.active a:hover,
#pfhub_portfolio_content_<?php echo $portfolioID; ?> #pfhub_portfolio_filters_<?php echo $portfolioID; ?>  ul li.active a:focus,
#pfhub_portfolio_content_<?php echo $portfolioID; ?> #pfhub_portfolio_filters_<?php echo $portfolioID; ?>  ul li.active a:active {
    color:#<?php echo $pfhub_portfolio_get_options["pfhub_portfolio_view2_filterbutton_hover_font_color"];?> !important;
    background-color: #<?php echo $pfhub_portfolio_get_options["pfhub_portfolio_view2_filterbutton_hover_background_color"];?> !important;
    cursor: pointer;
}
#pfhub_portfolio_content_<?php echo $portfolioID; ?> #pfhub_portfolio_container_<?php echo $portfolioID; ?> {
    width: 79%;
    max-width: 100% !important;
<?php if(($sortingFloatPopup == "left" && $filteringFloatPopup == "right") || ($sortingFloatPopup == "right" && $filteringFloatPopup == "left"))
    {echo "margin: 0 auto;width:58%;"; }
    if(($filteringFloatPopup == "left" || $filteringFloatPopup == "right" && $sortingFloatPopup == "top") || ($sortingFloatPopup == "left" || $sortingFloatPopup == "right" && $filteringFloatPopup == "top"))
    {echo 'float:left;';}
    if(($portfolioShowSorting == 'off' && $portfolioShowFiltering == 'off') || ($sortingFloatPopup == 'top' && $filteringFloatPopup == 'top') ||
        ($sortingFloatPopup == 'top' && $filteringFloatPopup == '') || ($sortingFloatPopup == '' && $filteringFloatPopup == 'top'))
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
@media screen and (max-width: <?php echo $pfhub_portfolio_get_options['pfhub_portfolio_view0_block_width']+2*$pfhub_portfolio_get_options['pfhub_portfolio_view2_element_border_width']+40; ?>px) {
    .portelement_<?php echo $portfolioID; ?>  {
        width:98%;
        margin: 1% !important;
        float: left;
        overflow: hidden;
        outline:none;
        border:<?php echo $pfhub_portfolio_get_options['pfhub_portfolio_view2_element_border_width']; ?>px solid #<?php echo $pfhub_portfolio_get_options['pfhub_portfolio_view0_element_border_color']; ?>;
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
#pfhub_portfolio_options_and_filters_<?php echo $portfolioID; ?>{
    position: relative;
    float: left;
    width: 20%;
    max-width: 180px;
    float:<?php echo $sortingFloatPopup; ?>;
<?php if($sortingFloatPopup == 'left') echo 'margin-right: 1%;';
    else echo 'margin-left:1%;';
?>
}
</style>
