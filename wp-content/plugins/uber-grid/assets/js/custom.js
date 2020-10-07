"use strict";
function pfhubPortfolioIsotope(elem,option){
    if(typeof elem.isotope == 'function'){
        elem.isotope(option);
    } else {
        elem.pfhubPortfolioNeon(option);
    }
}
jQuery(document).ready(function () {
    if (portfolioGalleryDisableRightClick == 'on') {
        jQuery('.portfolio-gallery-content img, ul[id^="pfhub_portfolio_popup_list_"] img').each(function () {
            jQuery(this).bind('contextmenu', function () {
                return false;
            });
        });
        jQuery('#pcolorbox').bind('contextmenu', '#pcboxLoadedContent img', function () {
            return false;
        });
    }
    jQuery('.portfolio-gallery-content').each(function(){
        var portfolioId = jQuery(this).attr('data-portfolio-id');
        if(portfolio_lightbox_type == 'classic') {

            jQuery("#pfhub_portfolio_content_" + portfolioId + " a[href$='.jpg'], #pfhub_portfolio_content_" + portfolioId + " a[href$='.png'], #pfhub_portfolio_content_" + portfolioId + " a[href$='.gif']").addClass('group1');
            var group_count = 0;
            jQuery(".portelement_" + portfolioId).each(function () {
                group_count++;
            });
            for (var i = 1; i <= group_count; i++) {
                jQuery(".portfolio-group" + i + "-" + portfolioId).removeClass('cboxElement').pcolorbox({rel: 'portfolio-group' + i + "-" + portfolioId});
            }
            jQuery(".portfolio-lightbox-group" + portfolioId).removeClass('cboxElement').pcolorbox({rel: "portfolio-lightbox-group" + portfolioId});
            jQuery(".portfolio-lightbox a[href$='.png'],.portfolio-lightbox a[href$='.jpg'],.portfolio-lightbox a[href$='.gif'],.portfolio-lightbox a[href$='.jpeg']").addClass("portfolio-lightbox-group");
            var group_count_slider = 0;
            jQuery(".slider-content").each(function () {
                group_count_slider++;
            });
            jQuery(".portfolio-group-slider" + i).removeClass('cboxElement').pcolorbox({rel: 'portfolio-group-slider' + i});
            for (var i = 1; i <= group_count_slider; i++) {
                jQuery(".portfolio-group-slider_" + portfolioId + "_" + i).removeClass('cboxElement').pcolorbox({rel: 'portfolio-group-slider_' + portfolioId + "_" + i});
                jQuery("#p-main-slider_" + portfolioId + " .clone  a").removeClass();
            }
            jQuery(".pyoutube").removeClass('cboxElement').pcolorbox({iframe: true, innerWidth: 640, innerHeight: 390});
            jQuery(".pvimeo").removeClass('cboxElement').pcolorbox({iframe: true, innerWidth: 640, innerHeight: 390});
            jQuery(".callbacks").removeClass('cboxElement').pcolorbox({
                onOpen: function () {
                    alert('onOpen: pcolorbox is about to open');
                },
                onLoad: function () {
                    alert('onLoad: pcolorbox has started to load the targeted content');
                },
                onComplete: function () {
                    alert('onComplete: pcolorbox has displayed the loaded content');
                },
                onCleanup: function () {
                    alert('onCleanup: pcolorbox has begun the close process');
                },
                onClosed: function () {
                    alert('onClosed: pcolorbox has completely closed');
                }
            });

            jQuery('.non-retina').removeClass('cboxElement').pcolorbox({rel: 'group5', transition: 'none'})
            jQuery('.retina').removeClass('cboxElement').pcolorbox({
                rel: 'group5',
                transition: 'none',
                retinaImage: true,
                retinaUrl: true
            });

            jQuery("#pfhub_portfolio_content_" + portfolioId + " #pfhub_portfolio_filters_" + portfolioId + " ul li a").click(function () {
                jQuery("#pfhub_portfolio_content_" + portfolioId + " #pfhub_portfolio_filters_portfolioId ul li").removeClass("active");
                jQuery(this).parent().addClass("active");

            });
        }
        else if(portfolio_lightbox_type == 'modern'){
            var watermark_class='',imgsrc;
            if(is_watermark){
                watermark_class='watermark';
            }
            var group_count = 0;
            jQuery(".portelement_" + portfolioId).each(function () {
                group_count++;
            });
            for (var i = 1; i <= group_count; i++) {
                jQuery(".portfolio-group" + i + "-" + portfolioId + " > img").addClass(watermark_class).attr('data-src', '');
                imgsrc = jQuery(".portfolio-group" + i + "-" + portfolioId + " > img").parent().attr('href');
                jQuery(".portfolio-group" + i + "-" + portfolioId + " > img").attr('data-imgsrc', imgsrc);
                jQuery(".portfolio-group" + i + "-" + portfolioId).addClass('portfolio_responsive_lightbox');
                if(!jQuery('.pfhub_portfolio_container').hasClass('view-store') && (!jQuery('.pfhub_portfolio_container').hasClass('view-grid') ||
                    !jQuery('.pfhub_portfolio_container').hasClass('view-masonry') ||
                    !jQuery('.pfhub_portfolio_container').hasClass('view-list') ||
                    !jQuery('.portfolio-gallery-content').hasClass('view-content-slider'))) {
                    jQuery(".portfolio-group" + i + "-" + portfolioId).lightboxPortfolio();
                }
            }
            jQuery(".portfolio-lightbox-group" + portfolioId + " > img").addClass(watermark_class).attr('data-src', '');
            jQuery(".portfolio-lightbox-group" + portfolioId + " > img").each(function(){
                imgsrc = jQuery(this).parent().attr('href');
                jQuery(this).attr('data-imgsrc', imgsrc);
            });
            jQuery(".portfolio-lightbox-group" + portfolioId).addClass('portfolio_responsive_lightbox');
            if(!jQuery('.pfhub_portfolio_container').hasClass('view-store'))
            {
                jQuery(".portfolio-lightbox-group" + portfolioId).removeClass('cboxElement').lightboxPortfolio();

            }
            var group_count_slider = 0;
            jQuery(".slider-content").each(function () {
                group_count_slider++;
            });
            if(!jQuery('.pfhub_portfolio_container').hasClass('view-store'))
            {
                jQuery(".portfolio-group-slider" + i).removeClass('cboxElement').addClass('portfolio_responsive_lightbox').lightboxPortfolio();

            }
            for (var i = 1; i <= group_count_slider; i++) {
                jQuery(".portfolio-group-slider_" + portfolioId + "_" + i ).each(function () {
                    imgsrc = jQuery(this).attr('href');
                    jQuery(this).find('img:first').attr('data-imgsrc', imgsrc);
                });
                jQuery(".portfolio-group-slider_" + portfolioId + "_" + i + " > img").addClass(watermark_class).attr('data-src', '');
                jQuery(".portfolio-group-slider_" + portfolioId + "_" + i).removeClass('cboxElement').addClass('portfolio_responsive_lightbox').lightboxPortfolio();
                jQuery("#p-main-slider_" + portfolioId + " .clone  a").removeClass();
            }
        }
    });

});
