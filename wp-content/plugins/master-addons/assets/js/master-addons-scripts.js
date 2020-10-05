/*
* Frontend Script for Elementor
*/
; (function ($) {
    "use strict";

    var editMode = false;
    var isRellax = false;
    var currentDevice = '';


    var animatedProgressbar = function (id, type, value, strokeColor, trailColor, strokeWidth, strokeTrailWidth) {
        var triggerClass = '.ma-el-progress-bar-' + id;
        if ("line" == type) {
            new ldBar(triggerClass, {
                "type": 'stroke',
                "path": 'M0 10L100 10',
                "aspect-ratio": 'none',
                "stroke": strokeColor,
                "stroke-trail": trailColor,
                "stroke-width": strokeWidth,
                "stroke-trail-width": strokeTrailWidth
            }).set(value);
        }
        if ("line-bubble" == type) {
            new ldBar(triggerClass, {
                "type": 'stroke',
                "path": 'M0 10L100 10',
                "aspect-ratio": 'none',
                "stroke": strokeColor,
                "stroke-trail": trailColor,
                "stroke-width": strokeWidth,
                "stroke-trail-width": strokeTrailWidth
            }).set(value);
            $($('.ma-el-progress-bar-' + id).find('.ldBar-label')).animate({
                left: value + '%'
            }, 1000, 'swing');
        }
        if ("circle" == type) {
            new ldBar(triggerClass, {
                "type": 'stroke',
                "path": 'M50 10A40 40 0 0 1 50 90A40 40 0 0 1 50 10',
                "stroke-dir": 'normal',
                "stroke": strokeColor,
                "stroke-trail": trailColor,
                "stroke-width": strokeWidth,
                "stroke-trail-width": strokeTrailWidth,
            }).set(value);
        }
        if ("fan" == type) {
            new ldBar(triggerClass, {
                "type": 'stroke',
                "path": 'M10 90A40 40 0 0 1 90 90',
                "stroke": strokeColor,
                "stroke-trail": trailColor,
                "stroke-width": strokeWidth,
                "stroke-trail-width": strokeTrailWidth,
            }).set(value);
        }
    };


    var getElementSettings = function ($element, setting) {

        var elementSettings = {},
            modelCID = $element.data('model-cid');

        if (elementorFrontend.isEditMode() && modelCID) {
            var settings = elementorFrontend.config.elements.data[modelCID],
                type = settings.attributes.widgetType || settings.attributes.elType,
                settingsKeys = elementorFrontend.config.elements.keys[type];

            if (!settingsKeys) {
                settingsKeys = elementorFrontend.config.elements.keys[type] = [];

                jQuery.each(settings.controls, function (name, control) {
                    if (control.frontend_available) {
                        settingsKeys.push(name);
                    }
                });
            }

            jQuery.each(settings.getActiveControls(), function (controlKey) {
                if (-1 !== settingsKeys.indexOf(controlKey)) {
                    elementSettings[controlKey] = settings.attributes[controlKey];
                }
            });
        } else {
            elementSettings = $element.data('settings') || {};
        }

        return getItems(elementSettings, setting);

    };

    var getItems = function (items, itemKey) {
        if (itemKey) {
            var keyStack = itemKey.split('.'),
                currentKey = keyStack.splice(0, 1);

            if (!keyStack.length) {
                return items[currentKey];
            }

            if (!items[currentKey]) {
                return;
            }

            return this.getItems(items[currentKey], keyStack.join('.'));
        }

        return items;
    };



    var Master_Addons = {

        //
        //         try {
        //         (function($) {
        //
        //         })(jQuery);
        // } catch(e) {
        //         //We can also throw from try block and catch it here
        //         // No Error Show
        //     }
        //
        // Master Addons: Headlines

        MA_Animated_Headlines: function ($scope, $) {
            try {
                (function ($) {

                    Master_Addons.MA_Animated_Headlines.elementSettings    = getElementSettings( $scope );


                    /*----------- Animated Headlines --------------*/
                    //set animation timing
                    var $animatedHeaderContainer     = $scope.find('.ma-el-animated-headline').eq(0),

                        animationDelay = 2500,

                        //loading bar effect
                        barAnimationDelay = 3800,
                        barWaiting = barAnimationDelay - 3000, //3000 is the duration of the transition on the loading bar - set in the scss/css file
                        
                        //letters effect
                        lettersDelay = 50,

                        //type effect
                        typeLettersDelay = 150,
                        selectionDuration = 500,
                        typeAnimationDelay = selectionDuration + 800,
                        
                        //clip effect
                        revealDuration = 600,
                        revealAnimationDelay = 1500;

                    Master_Addons.MA_Animated_Headlines.singleLetters = function($words) {
                        $words.each(function(){
                            var word = $(this),
                                letters = word.text().trim().split(''),
                                selected = word.hasClass('is-visible');

                            for( var i=0; i<letters.length; i++){
                                if(word.parents('.rotate-2').length > 0){ letters[i] = '<em>' + letters[i] + '</em>'; }
                                letters[i] = (selected) ? '<i class="in">' + letters[i] + '</i>': '<i>' + letters[i] + '</i>';
                            }
                            var newLetters = letters.join('');
                            word.html(newLetters).css('opacity', 1);
                        });
                    }

                    // function animateHeadline($headlines) {
                    Master_Addons.MA_Animated_Headlines.animateHeadline = function($headlines) {
                        
                        var duration = animationDelay;

                        $headlines.each(function(){
                            var headline = $(this);

                            if(headline.hasClass('loading-bar')) {
                                duration = barAnimationDelay;
                                setTimeout(function(){ headline.find('.ma-el-words-wrapper').addClass('is-loading') }, barWaiting);
                            } else if (headline.hasClass('clip')){
                                var spanWrapper = headline.find('.ma-el-words-wrapper'),
                                    newWidth = spanWrapper.width() + 10
                                spanWrapper.css('width', newWidth);
                            } else if (!headline.hasClass('type') ) {
                                //assign to .ma-el-words-wrapper the width of its longest word
                                var words = headline.find('.ma-el-words-wrapper b'),
                                    width = 0;

                                words.each(function(){
                                    var wordWidth = $(this).width();
                                    if (wordWidth > width) width = wordWidth;
                                });
                                headline.find('.ma-el-words-wrapper').css('width', width);
                            };

                            //trigger animation
                            setTimeout(function(){ Master_Addons.MA_Animated_Headlines.hideWord( headline.find('.is-visible').eq(0) ) }, duration);
                        });
                    }


                    Master_Addons.MA_Animated_Headlines.hideWord = function($word) {

                        var nextWord = Master_Addons.MA_Animated_Headlines.takeNext($word);

                        if($word.parents('.ma-el-animated-headline').hasClass('type')) {
                            var parentSpan = $word.parent('.ma-el-words-wrapper');
                            parentSpan.addClass('selected').removeClass('waiting');
                            setTimeout(function(){
                                parentSpan.removeClass('selected');
                                $word.removeClass('is-visible').addClass('is-hidden').children('i').removeClass('in').addClass('out');
                            }, selectionDuration);
                            setTimeout(function(){ Master_Addons.MA_Animated_Headlines.showWord(nextWord, typeLettersDelay) }, typeAnimationDelay);

                        } else if($word.parents('.ma-el-animated-headline').hasClass('letters')) {
                            var bool = ($word.children('i').length >= nextWord.children('i').length) ? true : false;
                            Master_Addons.MA_Animated_Headlines.hideLetter($word.find('i').eq(0), $word, bool, lettersDelay);
                            Master_Addons.MA_Animated_Headlines.showLetter(nextWord.find('i').eq(0), nextWord, bool, lettersDelay);

                        }  else if($word.parents('.ma-el-animated-headline').hasClass('clip')) {
                            $word.parents('.ma-el-words-wrapper').animate({ width : '2px' }, revealDuration, function(){
                                Master_Addons.MA_Animated_Headlines.switchWord($word, nextWord);
                                Master_Addons.MA_Animated_Headlines.showWord(nextWord);
                            });

                        } else if ($word.parents('.ma-el-animated-headline').hasClass('loading-bar')){
                            $word.parents('.ma-el-words-wrapper').removeClass('is-loading');
                            Master_Addons.MA_Animated_Headlines.switchWord($word, nextWord);
                            setTimeout(function(){ Master_Addons.MA_Animated_Headlines.hideWord(nextWord) }, barAnimationDelay);
                            setTimeout(function(){ $word.parents('.ma-el-words-wrapper').addClass('is-loading') }, barWaiting);

                        } else {
                            Master_Addons.MA_Animated_Headlines.switchWord($word, nextWord);
                            setTimeout(function(){ Master_Addons.MA_Animated_Headlines.hideWord(nextWord) }, animationDelay);
                        }
                    }

                    Master_Addons.MA_Animated_Headlines.showWord = function($word, $duration) {
                        if($word.parents('.ma-el-animated-headline').hasClass('type')) {
                            Master_Addons.MA_Animated_Headlines.showLetter($word.find('i').eq(0), $word, false, $duration);
                            $word.addClass('is-visible').removeClass('is-hidden');

                        }  else if($word.parents('.ma-el-animated-headline').hasClass('clip')) {
                            $word.parents('.ma-el-words-wrapper').animate({ 'width' : $word.width() + 10 }, revealDuration, function(){
                                setTimeout(function(){ Master_Addons.MA_Animated_Headlines.hideWord($word) }, revealAnimationDelay);
                            });
                        }
                    }

                    Master_Addons.MA_Animated_Headlines.hideLetter = function($letter, $word, $bool, $duration) {
                        $letter.removeClass('in').addClass('out');

                        if(!$letter.is(':last-child')) {
                            setTimeout(function(){ Master_Addons.MA_Animated_Headlines.hideLetter($letter.next(), $word, $bool, $duration); }, $duration);
                        } else if($bool) {
                            setTimeout(function(){ Master_Addons.MA_Animated_Headlines.hideWord(Master_Addons.MA_Animated_Headlines.takeNext($word)) }, animationDelay);
                        }

                        if($letter.is(':last-child') && $('html').hasClass('no-csstransitions')) {
                            var nextWord = Master_Addons.MA_Animated_Headlines.takeNext($word);
                            Master_Addons.MA_Animated_Headlines.switchWord($word, nextWord);
                        }
                    }

                    Master_Addons.MA_Animated_Headlines.showLetter = function($letter, $word, $bool, $duration) {
                        $letter.addClass('in').removeClass('out');

                        if(!$letter.is(':last-child')) {
                            setTimeout(function(){ Master_Addons.MA_Animated_Headlines.showLetter($letter.next(), $word, $bool, $duration); }, $duration);
                        } else {
                            if($word.parents('.ma-el-animated-headline').hasClass('type')) { setTimeout(function(){ $word.parents('.ma-el-words-wrapper').addClass('waiting'); }, 200);}
                            if(!$bool) { setTimeout(function(){ Master_Addons.MA_Animated_Headlines.hideWord($word) }, animationDelay) }
                        }
                    }

                    Master_Addons.MA_Animated_Headlines.takeNext = function($word) {
                        return (!$word.is(':last-child')) ? $word.next() : $word.parent().children().eq(0);
                    }

                    Master_Addons.MA_Animated_Headlines.takePrev = function($word) {
                        return (!$word.is(':first-child')) ? $word.prev() : $word.parent().children().last();
                    }

                    Master_Addons.MA_Animated_Headlines.switchWord = function($oldWord, $newWord) {
                        $oldWord.removeClass('is-visible').addClass('is-hidden');
                        $newWord.removeClass('is-hidden').addClass('is-visible');
                    }

                    Master_Addons.MA_Animated_Headlines.initHeadline = function() {
                        //insert <i> element for each letter of a changing word
                        Master_Addons.MA_Animated_Headlines.singleLetters($('.ma-el-animated-headline.letters').find('b'));
                        //initialise headline animation
                        Master_Addons.MA_Animated_Headlines.animateHeadline($('.ma-el-animated-headline'));
                    }

                    Master_Addons.MA_Animated_Headlines.initHeadline();

                })(jQuery);
            } catch (e) {
                //We can also throw from try block and catch it here
                // No Error Show
            }

        },

        // Master Addons: Accordion
        MA_Accordion: function ($scope, $) {

            var $advanceAccordion = $scope.find(".ma-advanced-accordion"),
                $accordionHeader = $scope.find(".ma-advanced-accordion-header"),
                $accordionType = $advanceAccordion.data("accordion-type"),
                $accordionSpeed = $advanceAccordion.data("toogle-speed");

            // Open default actived tab
            $accordionHeader.each(function () {
                if ($(this).hasClass("active-default")) {
                    $(this).addClass("show active");
                    $(this)
                        .next()
                        .slideDown($accordionSpeed);
                }
            });

            // Remove multiple click event for nested accordion
            $accordionHeader.unbind("click");

            $accordionHeader.click(function (e) {
                e.preventDefault();

                var $this = $(this);

                if ($accordionType === "accordion") {
                    if ($this.hasClass("show")) {
                        $this.removeClass("show active");
                        $this.next().slideUp($accordionSpeed);
                    } else {
                        $this
                            .parent()
                            .parent()
                            .find(".ma-advanced-accordion-header")
                            .removeClass("show active");
                        $this
                            .parent()
                            .parent()
                            .find(".ma-accordion-tab-content")
                            .slideUp($accordionSpeed);
                        $this.toggleClass("show active");
                        $this.next().slideDown($accordionSpeed);
                    }
                } else {

                    // For acccordion type 'toggle'
                    if ($this.hasClass("show")) {
                        $this.removeClass("show active");
                        $this.next().slideUp($accordionSpeed);
                    } else {
                        $this.addClass("show active");
                        $this.next().slideDown($accordionSpeed);
                    }
                }
            });

        },



        // Master Addons: Tabs

        MA_Tabs: function ($scope, $) {

            try {
                (function ($) {

                    var $tabsWrapper    = $scope.find('[data-tabs]'),
                        $tabEffect      = $tabsWrapper.data('tab-effect');


                    $tabsWrapper.each( function() {
                        var tab = $(this);
                        var isTabActive = false;
                        var isContentActive = false;

                        tab.find('[data-tab]').each(function () {
                            if ($(this).hasClass('active')) {
                                isTabActive = true;
                            }
                        });
                        tab.find('.ma-el-advance-tab-content').each(function () {
                            if ($(this).hasClass('active')) {
                                isContentActive = true;
                            }
                        });
                        if (!isContentActive) {
                            tab.find('.ma-el-advance-tab-content').eq(0).addClass('active');
                        }
                        if (!isTabActive) {
                            tab.find('[data-tab]').eq(0).addClass('active');
                        }

                        if( $tabEffect == "hover"){
                            tab.find('[data-tab]').hover(function() {
                                var $data_tab_id = $(this).data('tab-id');
                                $(this).siblings().removeClass('active');
                                $(this).addClass('active');
                                $(this).closest('[data-tabs]').find('.ma-el-advance-tab-content').removeClass('active');
                                $('#' + $data_tab_id).addClass('active');
                            });
                        } else{
                            tab.find('[data-tab]').click(function() {
                                var $data_tab_id = $(this).data('tab-id');
                                $(this).siblings().removeClass('active');
                                $(this).addClass('active');
                                $(this).closest('[data-tabs]').find('.ma-el-advance-tab-content').removeClass('active');
                                $('#' + $data_tab_id).addClass('active');
                            });
                        }


                    });

                })(jQuery);
            } catch (e) {
                //We can also throw from try block and catch it here
                // No Error Show
            }


        },


        //Master Addons: Progressbar
        MA_ProgressBar: function ($scope, $) {

            try {
                (function ($) {

                    const $progressBarWrapper = $scope.find('[data-progress-bar]').eq(0);
                    $progressBarWrapper.waypoint(function () {
                        var element = $(this.element);
                        var id = element.data('id');
                        var type = element.data('type');
                        var value = element.data('progress-bar-value');
                        var strokeWidth = element.data('progress-bar-stroke-width');
                        var strokeTrailWidth = element.data('progress-bar-stroke-trail-width');
                        var color = element.data('stroke-color');
                        var trailColor = element.data('stroke-trail-color');
                        animatedProgressbar(id, type, value, color, trailColor, strokeWidth, strokeTrailWidth);
                        this.destroy();
                    }, {
                        offset: 'bottom-in-view'
                    });

                })(jQuery);
            } catch (e) {
                //We can also throw from try block and catch it here
                // No Error Show
            }


        },

        //Master Addons: Image Filter Gallery
        MA_Image_Filter_Gallery: function ($scope, $) {

            // var $carousel           = $scope.find('.jltma-gallery-slider__carousel').eq(0),
            //     $preview            = $scope.find('.jltma-gallery-slider__preview');

            var $ma_el_image_filter_gallery_container   = $scope.find('.ma-el-image-filter-gallery').eq(0),
                $ma_el_image_filter_gallery_nav         = $scope.find('.ma-el-image-filter-nav');


            if ($.isFunction($.fn.imagesLoaded)) {
                $ma_el_image_filter_gallery_container.imagesLoaded(function () {
                    if ($.isFunction($.fn.isotope)) {
                        $ma_el_image_filter_gallery_container.isotope({
                            filter: '*',
                            itemSelector: '.ma-el-image-filter-item',
                        });
                    }
                });
            }

            $ma_el_image_filter_gallery_nav.on('click', 'li', function () {
                $ma_el_image_filter_gallery_nav.find('.active').removeClass('active');
                $(this).addClass('active');

                if ($.isFunction($.fn.isotope)) {
                    var selector = $(this).attr('data-filter');
                    $ma_el_image_filter_gallery_container.isotope({
                        filter: selector,
                    });
                    return false;
                }
            });




            if ($.isFunction($.fn.fancybox)) {
                $('.ma-el-fancybox').fancybox({
                    openEffect	: 'none',
                    closeEffect	: 'none',
                    afterLoad : function(instance, current) {
                        var pixelRatio = window.devicePixelRatio || 1;

                        if ( pixelRatio > 1.5 ) {
                        current.width  = current.width  / pixelRatio;
                        current.height = current.height / pixelRatio;
                        }
                    }
                });
            }
        },



        // Gallery Slider
        MA_Gallery_Slider: function($scope, $){

            Master_Addons.MA_Gallery_Slider.elementSettings    = getElementSettings( $scope );

            var $carousel           = $scope.find('.jltma-gallery-slider__carousel').eq(0),
                $preview            = $scope.find('.jltma-gallery-slider__preview'),
                $thumbs             = $scope.find('.jltma-gallery .jltma-gallery__item'),
                $thumbnailsSlider   = $scope.find(".jltma-gallery-slider__gallery .jltma-gallery"),
                $thumbtype          = Master_Addons.MA_Gallery_Slider.elementSettings.jltma_gallery_slider_thumb_type,
                $thumbposition      = Master_Addons.MA_Gallery_Slider.elementSettings.jltma_gallery_slider_preview_position,
                $thumbVertical      = ( $thumbposition == "top" || $thumbposition == "bottom" ) ? false : true,

                start               = elementorFrontend.config.is_rtl ? 'right' : 'left',
                end                 = elementorFrontend.config.is_rtl ? 'left' : 'right',
            
                slickArgs       = {
                    slidesToShow    : 1,
                    slidesToScroll  : 1,
                    cssEase         : "linear",
                    draggable       : true,
                    asNavFor        : ($thumbtype == "slide") ? ".jltma-gallery-slider__gallery .jltma-gallery" : "",
                    adaptiveHeight  : 'yes' === Master_Addons.MA_Gallery_Slider.elementSettings.jltma_gallery_slider_adaptive_height,
                    autoplay        : 'yes' === Master_Addons.MA_Gallery_Slider.elementSettings.jltma_gallery_slider_autoplay,
                    autoplaySpeed   : Master_Addons.MA_Gallery_Slider.elementSettings.jltma_gallery_slider_autoplay_speed,
                    infinite        : 'yes' === Master_Addons.MA_Gallery_Slider.elementSettings.jltma_gallery_slider_infinite,
                    pauseOnHover    : 'yes' === Master_Addons.MA_Gallery_Slider.elementSettings.jltma_gallery_slider_pause_on_hover,
                    speed           : Master_Addons.MA_Gallery_Slider.elementSettings.jltma_gallery_slider_speed,
                    arrows          : 'yes' === Master_Addons.MA_Gallery_Slider.elementSettings.jltma_gallery_slider_show_arrows,
                    prevArrow       : '<div class="jltma-carousel__arrow jltma-arrow jltma-arrow--prev"><i class="eicon-chevron-' + start + '"></i></div>',
                    nextArrow       : '<div class="jltma-carousel__arrow jltma-arrow jltma-arrow--next"><i class="eicon-chevron-' + end + '"></i></div>',
                    dots            : false,
                    rtl             : 'rtl' === Master_Addons.MA_Gallery_Slider.elementSettings.jltma_gallery_slider_direction,
                    fade            : 'fade' === Master_Addons.MA_Gallery_Slider.elementSettings.jltma_gallery_slider_effect,
                }, 

                thumbsArgs       = {
                    speed             : Master_Addons.MA_Gallery_Slider.elementSettings.jltma_gallery_slider_thumb_speed,
                    slidesToShow      : Master_Addons.MA_Gallery_Slider.elementSettings.jltma_gallery_slider_thumb_items,
                    slidesToScroll    : 1,
                    cssEase           : "linear",
                    centerMode        : true,
                    draggable         : false,
                    vertical          : $thumbVertical,
                    verticalSwiping   : $thumbVertical,
                    focusOnSelect     : true,
                    arrows            : 'yes' === Master_Addons.MA_Gallery_Slider.elementSettings.jltma_gallery_slider_thumb_show_arrows,
                    autoplay          : 'yes' === Master_Addons.MA_Gallery_Slider.elementSettings.jltma_gallery_slider_thumb_autoplay,
                    autoplaySpeed     : Master_Addons.MA_Gallery_Slider.elementSettings.jltma_gallery_slider_thumb_autoplay_speed,
                    pauseOnHover      : 'yes' === Master_Addons.MA_Gallery_Slider.elementSettings.jltma_gallery_slider_thumb_pause_on_hover,
                    infinite          : 'yes' === Master_Addons.MA_Gallery_Slider.elementSettings.jltma_gallery_slider_thumb_infinite,
                    asNavFor          : ".jltma-gallery-slider__carousel",
                    dots              : false,
                    prevArrow         : '<div class="jltma-carousel__arrow jltma-arrow jltma-arrow--prev"><i class="eicon-chevron-' + start + '"></i></div>',
                    nextArrow         : '<div class="jltma-carousel__arrow jltma-arrow jltma-arrow--next"><i class="eicon-chevron-' + end + '"></i></div>',
                    fade              : 'fade' === Master_Addons.MA_Gallery_Slider.elementSettings.jltma_gallery_slider_thumb_effect,
                    rtl               : 'rtl' === Master_Addons.MA_Gallery_Slider.elementSettings.jltma_gallery_slider_thumb_direction,
                        responsive        : [
                            {
                                breakpoint: 720,
                                settings: {
                                    slidesToShow: 4,
                                    slidesToScroll: 4
                                }
                            },
                            {
                                breakpoint: 576,
                                settings: {
                                    slidesToShow: 3,
                                    slidesToScroll: 3
                                }
                            },
                            {
                                breakpoint: 350,
                                settings: {
                                    slidesToShow: 2,
                                    slidesToScroll: 2
                            }
                    }
                ]
            };

            Master_Addons.MA_Gallery_Slider.events = function() {
                $carousel.on( 'beforeChange', function ( event, slick, currentSlide, nextSlide ) {
                    var currentSlide = nextSlide;
                    $thumbs.removeClass('is--active');
                    $thumbs.eq( currentSlide ).addClass('is--active');
                });

                $thumbs.each( function( currentSlide ) {
                    $(this).on( 'click', function ( e ) {
                        e.preventDefault();
                        $carousel.slick( 'slickGoTo', currentSlide );
                    });
                });
            };

            Master_Addons.MA_Gallery_Slider.init = function() {

                $carousel.slick( slickArgs );

                $thumbs.removeClass('is--active');
                $thumbs.eq( 0 ).addClass('is--active');

                $carousel.slick( 'setPosition' );

                Master_Addons.MA_Gallery_Slider.events();

                if($thumbtype == "slide"){
                    // Thumbnails Slider options
                    $thumbnailsSlider.slick( thumbsArgs );
                }

            };

            Master_Addons.MA_Gallery_Slider.init();
        },



        //Master Addons: Timeline
        MA_Timeline: function ($scope, $) {

            try {
                (function ($) {

                    var horzTimeline = $scope.find(".ma-el-timeline-slider-inner");

                    $(horzTimeline).slick({
                        slidesToShow: 3,
                        slidesToScroll: 1,
                        autoplay: false,
                        autoplaySpeed: 2000,
                        pauseOnHover: false,
                        adaptiveHeight: true,
                        // prevArrow: $('.ma-el-blog-timeline-slider-prev'),
                        // nextArrow: $('.ma-el-blog-timeline-slider-next'),
                        responsive: [
                            {
                                breakpoint: 768,
                                settings: {
                                    arrows: false,
                                    slidesToShow: 2
                                }
                            },
                            {
                                breakpoint: 480,
                                settings: {
                                    arrows: false,
                                    slidesToShow: 1
                                }
                            }
                        ]
                    });


                })(jQuery);
            } catch (e) {
                //We can also throw from try block and catch it here
                // No Error Show
            }


        },

        // Master Addons: Table of Contents
        MA_Table_Of_Contents: function ($scope, $) {

            var tableOfContent = $scope.find(".ma-el-table-of-content"),
                tableDesign = tableOfContent.data('design'),
                tableDropdownMode = tableOfContent.data('dropdown-mode');

            // var content = $scope.find(".ma-el-table-of-content"),
            //     headings = content.querySelectorAll('h1, h2, h3, h4, h5, h6, h7'),
            //     headingMap = {}

            // Array.prototype.forEach.call(headings, function (heading) {
            //     var id = heading.id ? heading.id : heading.textContent.trim().toLowerCase()
            //     .split(' ').join('-').replace(/[!@#$%^&*():]/ig, '')
            //     headingMap[id] = !isNaN(headingMap[id]) ? ++headingMap[id] : 0
            //     if (headingMap[id]) {
            //     heading.id = id + '-' + headingMap[id]
            //     } else {
            //     heading.id = id
            //     }
            // });

            tocbot.init(tableOfContent.data('settings'));


            // Offcanvas TOC
            if (tableDesign == "offcanvas") {
                $('.ma-el-toggle-button').on('click', function () {
                    $('body').addClass('show-sidemenu');
                });

                $('.ma-el-offcanvas-close').on('click', function () {
                    $('body').removeClass('show-sidemenu');
                });
            }

            if (tableDesign == "dropdown") {
                // Dropdown TOC
                $('.ma-el-toggle-button').on('click', function () {
                    $('body').addClass('show-sidemenu');
                });
            }



            // Dopdown on click
            if (tableDropdownMode === "click") {
                var ma_el_drop = $('.ma-el-drop').data('ma-el-drop');

                $('.ma-el-drop').removeClass('animated');
                $('.ma-el-drop').removeClass(ma_el_drop);

                $('.table-of-content-layout-dropdown .ma-el-toggle-button').on('click', function () {
                    $('body').removeClass('show-sidemenu');
                    $('.ma-el-drop').toggleClass('animated');
                    $('.table-of-content-layout-dropdown').toggleClass('show-sidemenu');
                    $('.ma-el-drop').toggleClass(ma_el_drop);
                });
            }

            if (tableDropdownMode === "hover") {
                $('.ma-el-toggle-button').on('hover', function () {
                    $('body').addClass('show-sidemenu');
                });
            }


        },




        //Master Addons: News Ticker
        MA_NewsTicker: function ($scope, $) {

            try {
                (function ($) {
                    $(window).load(function (e) {

                        var newsTickerWrapper = $scope.find(".ma-el-news-ticker"),
                            tickerType = newsTickerWrapper.data('tickertype'),
                            tickerid = newsTickerWrapper.data('tickerid'),
                            feedUrl = newsTickerWrapper.data('feedurl'),
                            feedAnimation = newsTickerWrapper.data('feedanimation'),
                            limitPosts = newsTickerWrapper.data('limitposts'),
                            tickerStyleEffect = newsTickerWrapper.data('scroll'),
                            autoplay = newsTickerWrapper.data('autoplay'),
                            timer = newsTickerWrapper.data('timer');

                        if (tickerType === "content") {

                            $("#" + tickerid + "").breakingNews({
                                effect: "" + tickerStyleEffect + "",
                                autoplay: autoplay,
                                timer: timer,
                                border: false,
                                feed: false,
                                feedlabels: false
                            });
                        }

                        if (tickerType === "feed") {

                            jQuery(function ($) {

                                var feed_container = $("#" + tickerid + ' .ma-el-ticker-content-inner');

                                $(feed_container).rss(feedUrl,
                                    {
                                        // how many entries do you want?
                                        // default: 4
                                        // valid values: any integer
                                        limit: limitPosts,

                                        // want to offset results being displayed?
                                        // default: false
                                        // valid values: any integer
                                        offsetStart: false, // offset start point
                                        offsetEnd: false, // offset end point

                                        // will request the API via https
                                        // default: false
                                        // valid values: false, true
                                        ssl: true,


                                        // which server should be requested for feed parsing
                                        // the server implementation is here: https://github.com/sdepold/feedr
                                        // default: feedrapp.info
                                        // valid values: any string
                                        // host: 'my-own-feedr-instance.com',


                                        // option to seldomly render ads
                                        // ads help covering the costs for the feedrapp server hosting and future improvements
                                        // default: true
                                        // valid values: false, true
                                        support: false,


                                        // formats the date with moment.js (optional)
                                        // default: 'dddd MMM Do'
                                        // valid values: see http://momentjs.com/docs/#/displaying/
                                        dateFormat: 'MMMM Do, YYYY',


                                        // localizes the date with moment.js (optional)
                                        // default: 'en'
                                        dateLocale: 'de',


                                        // outer template for the html transformation
                                        // default: "<ul>{entries}</ul>"
                                        // valid values: any string


                                        layoutTemplate: '<ul class="ma-el-ticker-content-items">{entries}</ul>',

                                        // inner template for each entry
                                        // default: '<li><a href="{url}">[{author}@{date}] {title}</a><br/>{shortBodyPlain}</li>'
                                        // valid values: any string
                                        // entryTemplate: '<p>{title}</p>',
                                        // entryTemplate: '<li><a href="{url}">[{author}@{date}] {title}</a>{teaserImage}{shortBodyPlain}</li>'
                                        entryTemplate: '<li> {teaserImage} <a href="{url}"> {title}</a></li>',

                                        // the effect, which is used to let the entries appear
                                        // default: 'show'
                                        // valid values: 'show', 'slide', 'slideFast', 'slideSynced', 'slideFastSynced'
                                        effect: feedAnimation,

                                    }, function () {

                                        $("#" + tickerid + "").breakingNews({
                                            effect: "" + tickerStyleEffect + "",
                                            autoplay: autoplay,
                                            timer: timer
                                        });
                                    })
                            });

                        }

                    }); // End of Window load

                })(jQuery);
            } catch (e) {
                //We can also throw from try block and catch it here
                // No Error Show
            }


        },


        /*
         * Master Addons: MA Blog Posts
         */

        MA_Blog: function ($scope, $) {
            var blogElement = $scope.find(".ma-el-blog-wrapper"),
                colsNumber = blogElement.data("col"),
                carousel = blogElement.data("carousel"),
                grid = blogElement.data("grid");

            $scope.find(".ma-el-blog-cats-container li a").click(function (e) {
                e.preventDefault();

                $scope
                    .find(".ma-el-blog-cats-container li .active")
                    .removeClass("active");

                $(this).addClass("active");

                var selector = $(this).attr("data-filter");

                blogElement.isotope({ filter: selector });

                return false;
            });

            var masonryBlog = blogElement.hasClass("ma-el-blog-masonry");

            if (masonryBlog && !carousel) {
                blogElement.imagesLoaded(function () {
                    blogElement.isotope({
                        itemSelector: ".ma-el-post-outer-container",
                        percentPosition: true,
                        animationOptions: {
                            duration: 750,
                            easing: "linear",
                            queue: false
                        }
                    });
                });
            }

            if (carousel && grid) {

                var autoPlay = blogElement.data("play"),
                    speed = blogElement.data("speed"),
                    fade = blogElement.data("fade"),
                    arrows = blogElement.data("arrows"),
                    dots = blogElement.data("dots"),
                    prevArrow = null,
                    nextArrow = null;

                if (arrows) {
                    prevArrow = "<div class='ma-el-team-carousel-prev'><i class='fa fa-angle-left'></i></div>",
                        nextArrow = "<div class='ma-el-team-carousel-next'><i class='fa fa-angle-right'></i></div>"
                    // prevArrow   = '<a type="button" data-role="none" class="carousel-arrow carousel-prev" aria-label="Next" role="button" style=""><i class="fas fa-angle-left" aria-hidden="true"></i></a>',
                    //     nextArrow   = '<a type="button" data-role="none" class="carousel-arrow carousel-next" aria-label="Next" role="button" style=""><i class="fas fa-angle-right" aria-hidden="true"></i></a>';
                } else {
                    prevArrow = prevArrow = '';
                }

                $(blogElement).slick({
                    infinite: true,
                    slidesToShow: colsNumber,
                    slidesToScroll: colsNumber,
                    responsive: [
                        {
                            breakpoint: 769,
                            settings: {
                                slidesToShow: 1,
                                slidesToScroll: 1
                            }
                        },
                        {
                            breakpoint: 481,
                            settings: {
                                slidesToShow: 1,
                                slidesToScroll: 1
                            }
                        }
                    ],
                    autoplay: autoPlay,
                    autoplaySpeed: speed,
                    nextArrow: nextArrow,
                    prevArrow: prevArrow,
                    fade: fade,
                    draggable: true,
                    dots: dots,
                    customPaging: function () {
                        return (
                            '<i class="fas fa-circle"></i>'
                        );
                    }
                });
            }
        },


        /**** MA Image Carousel ****/
        MA_Image_Carousel: function ($scope, $) {

            try {
                (function ($) {

                    var $imageCarouselWrapper = $scope.find('.ma-el-image-carousel').eq(0),
                        $carousel_nav = $imageCarouselWrapper.data("carousel-nav"),
                        $loop = ($imageCarouselWrapper.data("loop") !== undefined) ? $imageCarouselWrapper.data("loop") : false,
                        $slidesToShow = $imageCarouselWrapper.data("slidestoshow"),
                        $slidesToScroll = $imageCarouselWrapper.data("slidestoscroll"),
                        $autoPlay = ($imageCarouselWrapper.data("autoplay") !== undefined) ? $imageCarouselWrapper.data("autoplay") : false,
                        $autoplaySpeed = ($imageCarouselWrapper.data("autoplayspeed") !== undefined) ? $imageCarouselWrapper.data("autoplayspeed") : false,
                        $transitionSpeed = $imageCarouselWrapper.data("speed"),
                        $pauseOnHover = ($imageCarouselWrapper.data("pauseonhover") !== undefined) ? $imageCarouselWrapper.data("pauseonhover") : false


                    // Team Carousel
                    if ($carousel_nav == "arrows") {
                        var arrows = true;
                        var dots = false;
                    } else {
                        var arrows = false;
                        var dots = true;
                    }

                    $imageCarouselWrapper.slick({
                        infinite: $loop,
                        slidesToShow: $slidesToShow,
                        slidesToScroll: $slidesToScroll,
                        autoplay: $autoPlay,
                        autoplaySpeed: $autoplaySpeed,
                        speed: $transitionSpeed,
                        pauseOnHover: $pauseOnHover,
                        dots: dots,
                        arrows: arrows,
                        prevArrow: "<div class='ma-el-team-carousel-prev'><i class='fa fa-angle-left'></i></div>",
                        nextArrow: "<div class='ma-el-team-carousel-next'><i class='fa fa-angle-right'></i></div>",
                        rows: 0,
                        responsive: [
                            {
                                breakpoint: 1024,
                                settings: {
                                    slidesToShow: 3,
                                }
                            },
                            {
                                breakpoint: 576,
                                settings: {
                                    slidesToShow: 2,
                                }
                            }
                        ],
                    });

                })(jQuery);
            } catch (e) {
                //We can also throw from try block and catch it here
                // No Error Show
            }
        },


        /**** MA Team Slider ****/

        MA_TeamSlider: function ($scope, $) {
            
            Master_Addons.MA_TeamSlider.elementSettings    = getElementSettings( $scope );

            var $teamCarouselWrapper = $scope.find('.ma-el-team-members-slider-section').eq(0),
                $team_preset = $teamCarouselWrapper.data("team-preset"),
                $ma_el_team_circle_image_animation = $teamCarouselWrapper.data("ma_el_team_circle_image_animation");

            if ($team_preset == "-content-drawer") {

                try {
                    (function ($) {

                        $('.gridder').gridderExpander({
                            scroll: false,
                            scrollOffset: 0,
                            scrollTo: "panel",                  // panel or listitem
                            animationSpeed: 400,
                            animationEasing: "easeInOutExpo",
                            showNav: true, // Show Navigation
                            nextText: "<span></span>", // Next button text
                            prevText: "<span></span>", // Previous button text
                            closeText: "", // Close button text
                            onStart: function () {
                                //Gridder Inititialized
                            },
                            onContent: function () {
                                //Gridder Content Loaded
                            },
                            onClosed: function () {
                                //Gridder Closed
                            }
                        });

                    })(jQuery);
                } catch (e) {
                    //We can also throw from try block and catch it here
                    // No Error Show
                }


            } else {

                try {
                    (function ($) {

                        var $teamCarouselWrapper = $scope.find('.ma-el-team-members-slider-section').eq(0),
                            $carousel_nav = $teamCarouselWrapper.data("carousel-nav"),
                            $loop = ($teamCarouselWrapper.data("loop") !== undefined) ? $teamCarouselWrapper.data("loop") : false,
                            $slidesToShow = $teamCarouselWrapper.data("slidestoshow"),
                            $slidesToScroll = $teamCarouselWrapper.data("slidestoscroll"),
                            $autoPlay = ($teamCarouselWrapper.data("autoplay") !== undefined) ? $teamCarouselWrapper.data("autoplay") : false,
                            $autoplaySpeed = ($teamCarouselWrapper.data("autoplayspeed") !== undefined) ? $teamCarouselWrapper.data("autoplayspeed") : false,
                            $transitionSpeed = $teamCarouselWrapper.data("speed"),
                            $pauseOnHover = ($teamCarouselWrapper.data("pauseonhover") !== undefined) ? $teamCarouselWrapper.data("pauseonhover") : false


                        // Team Carousel
                        if ($carousel_nav == "arrows") {
                            var arrows = true;
                            var dots = false;
                        } else {
                            var arrows = false;
                            var dots = true;
                        }

                        $teamCarouselWrapper.slick({
                            infinite: $loop,
                            slidesToShow: $slidesToShow,
                            slidesToScroll: $slidesToScroll,
                            autoplay: $autoPlay,
                            autoplaySpeed: $autoplaySpeed,
                            speed: $transitionSpeed,
                            mobileFirst:true,
                            pauseOnHover: $pauseOnHover,
                            dots: dots,
                            arrows: arrows,
                            prevArrow: "<div class='ma-el-team-carousel-prev'><i class='fa fa-angle-left'></i></div>",
                            nextArrow: "<div class='ma-el-team-carousel-next'><i class='fa fa-angle-right'></i></div>",
                            rows: 0,
                            responsive: [

                                {
                                    breakpoint: 350,
                                    settings: {
                                        dots: dots,
                                        arrow: arrows,
                                        rows:1,
                                        slidesToShow: Master_Addons.MA_TeamSlider.elementSettings.ma_el_team_per_view_mobile,
                                        slidesToScroll: $slidesToScroll
                                    }
                                },
                                {
                                    breakpoint: 576,
                                    settings: {
                                        dots: dots,
                                        arrow: arrows,
                                        slidesToShow: Master_Addons.MA_TeamSlider.elementSettings.ma_el_team_per_view_tablet,
                                        slidesToScroll: $slidesToScroll
                                    }
                                },
                                {
                                    breakpoint: 720,
                                    settings: {
                                        slidesToShow: $slidesToShow,
                                        slidesToScroll: $slidesToScroll,
                                        infinite: true,
                                        centerMode: false,
                                        dots: dots,
                                        arrow: arrows
                                    }
                                },

                            ],
                        });

                    })(jQuery);
                } catch (e) {
                    //We can also throw from try block and catch it here
                    // No Error Show
                }

            }

            // else if ($team_preset == "-circle-animation"){
            //     if($ma_el_team_circle_image_animation == "animation_svg_04"){
            //
            //     }
            // }

        },


        /**** MA Logo Slider ****/

        MA_Logo_Slider: function ($scope, $) {
            
            Master_Addons.MA_Logo_Slider.elementSettings    = getElementSettings( $scope );

            var $logSliderWrapper   = $scope.find('.jltma-logo-slider').eq(0),
                $logo_slider_nav    = Master_Addons.MA_Logo_Slider.elementSettings.jltma_logo_slider_nav,
                // $loop = ($logSliderWrapper.data("loop") !== undefined) ? $logSliderWrapper.data("loop") : false,
                $slidesToShow       = Master_Addons.MA_Logo_Slider.elementSettings.jltma_logo_slider_slides_to_show,
                start               = elementorFrontend.config.is_rtl ? 'right' : 'left',
                end                 = elementorFrontend.config.is_rtl ? 'left' : 'right',                
                $slidesToScroll     = Master_Addons.MA_Logo_Slider.elementSettings.jltma_logo_slider_slides_to_scroll;


                // Logo Slider Navigation
                if ($logo_slider_nav == "arrows") {
                    var arrows = true;
                    var dots = false;
                } else if($logo_slider_nav == "both") {
                    var arrows = true;
                    var dots = true;                    
                } else if($logo_slider_nav == "dots") {
                    var arrows = false;
                    var dots = true;
                }
                

                var logoSlickArgs       = {

                    slidesToShow    : $slidesToShow,
                    slidesToScroll  : $slidesToScroll,
                    cssEase         : "linear",
                    draggable       : false,
                    // autoplayspeed
                    autoplay        : 'yes' === Master_Addons.MA_Logo_Slider.elementSettings.jltma_logo_slider_autoplay,
                    autoplaySpeed   : Master_Addons.MA_Logo_Slider.elementSettings.jltma_logo_slider_autoplay_speed,
                    infinite        : 'yes' === Master_Addons.MA_Logo_Slider.elementSettings.jltma_logo_slider_loop,
                    pauseOnHover    : 'yes' === Master_Addons.MA_Logo_Slider.elementSettings.jltma_logo_slider_pause,
                    speed           : Master_Addons.MA_Logo_Slider.elementSettings.jltma_logo_slider_animation_speed,
                    arrows          : arrows,
                    centerMode      : Master_Addons.MA_Logo_Slider.elementSettings.jltma_logo_slider_center_mode,
                    // centerPadding: '60px',
                    prevArrow       : $('.jltma-logo-slider .jltma-slide-prev'),
                    nextArrow       : $('.jltma-logo-slider .jltma-slide-next'),
                    dots            : true,
                    dotsClass       : $('.jltma-logo-slider .jltma-slide-dotnav'),
                    appendDots      : $('.jltma-logo-slider .jltma-slide-dotnav'),
                    // rtl             : 'rtl' === Master_Addons.MA_Logo_Slider.elementSettings.jltMA_Logo_Slider_direction,
                    // fade            : 'fade' === Master_Addons.MA_Logo_Slider.elementSettings.jltma_gallery_slider_effect,
                    responsive: [

                        {
                            breakpoint: 350,
                            settings: {
                                dots: dots,
                                arrow: arrows,
                                rows:1,
                                slidesToShow: Master_Addons.MA_Logo_Slider.elementSettings.jltma_logo_slider_slides_to_show_mobile,
                                slidesToScroll: $slidesToScroll
                            }
                        },
                        {
                            breakpoint: 576,
                            settings: {
                                dots: dots,
                                arrow: arrows,
                                slidesToShow: Master_Addons.MA_Logo_Slider.elementSettings.jltma_logo_slider_slides_to_show_tablet,
                                slidesToScroll: $slidesToScroll
                            }
                        },
                        {
                            breakpoint: 720,
                            settings: {
                                slidesToShow: $slidesToShow,
                                slidesToScroll: $slidesToScroll,
                                infinite: true,                                    
                                dots: dots,
                                arrow: arrows
                            }
                        },

                    ],                        
            };


            $logSliderWrapper.slick( logoSlickArgs );


            // $('.jltma-slide-dotnav li a').removeClass('active');
            $(".jltma-logo-slider .jltma-slide-dotnav li a").click(function(e){
                e.preventDefault();

                var slideIndex = $(this).parent().index();
                $('.jltma-logo-slider .jltma-slide-dotnav li a.active').removeClass('active');
                // $(this).addClass('active');
                $logSliderWrapper.slick('slickGoTo', parseInt(slideIndex));
            });

        },


        /**** MA Twitter Slider ****/

        MA_Twitter_Slider: function ($scope, $) {
            
            Master_Addons.MA_Twitter_Slider.elementSettings    = getElementSettings( $scope );

            var $TwitterSliderWrapper   = $scope.find('.jltma-twitter-slider .swiper-wrapper').eq(0),
                $twitter_slider_nav    = Master_Addons.MA_Twitter_Slider.elementSettings.jltma_ts_navigation,
                // $loop = ($TwitterSliderWrapper.data("loop") !== undefined) ? $TwitterSliderWrapper.data("loop") : false,
                $slidesToShow       = Master_Addons.MA_Twitter_Slider.elementSettings.jltma_ts_columns,
                start               = elementorFrontend.config.is_rtl ? 'right' : 'left',
                end                 = elementorFrontend.config.is_rtl ? 'left' : 'right',                
                $slidesToScroll     = Master_Addons.MA_Twitter_Slider.elementSettings.jltma_ts_tweet_num;


                // Logo Slider Navigation
                if ($twitter_slider_nav == "arrows") {
                    var arrows = true;
                    var dots = false;
                } else if($twitter_slider_nav == "both") {
                    var arrows = true;
                    var dots = true;                    
                } else if($twitter_slider_nav == "dots") {
                    var arrows = false;
                    var dots = true;
                }
                

                var twitterSlickArgs       = {

                    slidesToShow    : $slidesToShow,
                    slidesToScroll  : $slidesToScroll,
                    cssEase         : "linear",
                    draggable       : false,
                    // autoplayspeed
                    autoplay        : 'yes' === Master_Addons.MA_Twitter_Slider.elementSettings.jltma_ts_autoplay,
                    autoplaySpeed   : Master_Addons.MA_Twitter_Slider.elementSettings.jltma_ts_autoplay_speed,
                    infinite        : 'yes' === Master_Addons.MA_Twitter_Slider.elementSettings.jltma_ts_loop,
                    pauseOnHover    : 'yes' === Master_Addons.MA_Twitter_Slider.elementSettings.jltma_ts_pauseonhover,
                    speed           : Master_Addons.MA_Twitter_Slider.elementSettings.jltma_ts_speed.size,
                    arrows          : true,
                    // centerMode      : Master_Addons.MA_Twitter_Slider.elementSettings.jltma_logo_slider_center_mode,
                    // centerPadding: '60px',
                    prevArrow       : $('.jltma-twitter-slider .jltma-slide-prev'),
                    nextArrow       : $('.jltma-twitter-slider .jltma-slide-next'),
                    dots            : true,
                    dotsClass       : $('.jltma-twitter-slider .jltma-slide-dotnav'),
                    appendDots      : $('.jltma-twitter-slider .jltma-slide-dotnav'),
                    // rtl             : 'rtl' === Master_Addons.MA_Twitter_Slider.elementSettings.jltMA_Logo_Slider_direction,
                    // fade            : 'fade' === Master_Addons.MA_Twitter_Slider.elementSettings.jltma_gallery_slider_effect,
                    responsive: [

                        {
                            breakpoint: 350,
                            settings: {
                                dots: dots,
                                arrow: arrows,
                                rows:1,
                                slidesToShow: Master_Addons.MA_Twitter_Slider.elementSettings.jltma_ts_columns_mobile,
                                slidesToScroll: $slidesToScroll
                            }
                        },
                        {
                            breakpoint: 576,
                            settings: {
                                dots: dots,
                                arrow: arrows,
                                slidesToShow: Master_Addons.MA_Twitter_Slider.elementSettings.jltma_ts_columns_tablet,
                                slidesToScroll: $slidesToScroll
                            }
                        },
                        {
                            breakpoint: 720,
                            settings: {
                                slidesToShow: $slidesToShow,
                                slidesToScroll: $slidesToScroll,
                                infinite: true,                                    
                                dots: dots,
                                arrow: arrows
                            }
                        },

                    ],                        
            };


            $TwitterSliderWrapper.slick( twitterSlickArgs );

            $('.jltma-twitter-slider .jltma-slide-dotnav li a').click(function(e){
                e.preventDefault();

                var slideIndex = $(this).parent().index();
                $('.jltma-twitter-slider .jltma-slide-dotnav li a.active').removeClass('active');
                $TwitterSliderWrapper.slick('slickGoTo', parseInt(slideIndex));
            });

        },


        MA_ParticlesBG: function ($scope, $) {

            // try {
            //     (function($scope, $) {

            if ($scope.hasClass('ma-el-particle-yes')) {
                let id = $scope.data('id');
                let element_type = $scope.data('element_type');
                let pdata = $scope.data('ma-el-particle');
                let pdata_wrapper = $scope.find('.ma-el-particle-wrapper').data('ma-el-pdata');

                if (typeof pdata != 'undefined' && pdata != '') {
                    if ($scope.find('.ma-el-section-bs').length > 0) {
                        $scope.find('.ma-el-section-bs').after('<div class="ma-el-particle-wrapper"' +
                            ' id="ma-el-particle-' + id + '"></div>');
                        particlesJS('ma-el-particle-' + id, pdata);
                    } else {

                        if (element_type == 'column') {

                            $scope.find('.elementor-column-wrap').prepend('<div class="ma-el-particle-wrapper"' +
                                ' id="ma-el-particle-' + id + '"></div>');
                        } else {
                            $scope.prepend('<div class="ma-el-particle-wrapper" id="ma-el-particle-' + id + '"></div>');
                        }

                        particlesJS('ma-el-particle-' + id, pdata);
                    }


                } else if (typeof pdata_wrapper != 'undefined' && pdata_wrapper != '') {

                    // $scope.prepend('<div class="ma-el-particle-wrapper" id="ma-el-particle-'+ id +'"></div>');

                    if (element_type == 'column') {
                        $scope.find('.elementor-column-wrap').prepend('<div class="ma-el-particle-wrapper"' +
                            ' id="ma-el-particle-' + id + '"></div>');
                    }
                    else {
                        $scope.prepend('<div class="ma-el-particle-wrapper" id="ma-el-particle-' + id + '"></div>');
                    }

                    particlesJS('ma-el-particle-' + id, JSON.parse(pdata_wrapper));
                }

            }
            //
            //     })(jQuery);
            // } catch(e) {
            //     //We can also throw from try block and catch it here
            //     // No Error Show
            // }

        },

        MA_BgSlider: function ($scope, $) {
            var ma_el_slides = [];
            var ma_el_slides_json = [];
            var ma_el_transition;
            var ma_el_animation;
            var ma_el_custom_overlay;
            var ma_el_overlay;
            var ma_el_cover;
            var ma_el_delay;
            var ma_el_timer;
            var slider_wrapper = $scope.children('.ma-el-section-bs').children('.ma-el-section-bs-inner');

            if (slider_wrapper && slider_wrapper.data('ma-el-bg-slider')) {

                var slider_images = slider_wrapper.data('ma-el-bg-slider');
                ma_el_transition = slider_wrapper.data('ma-el-bg-slider-transition');
                ma_el_animation = slider_wrapper.data('ma-el-bg-slider-animation');
                ma_el_custom_overlay = slider_wrapper.data('ma-el-bg-custom-overlay');
                if (ma_el_custom_overlay == 'yes') {
                    ma_el_overlay = jltma_scripts.plugin_url + 'assets/lib/vegas/overlays/' + slider_wrapper.data('ma-el-bg-slider-overlay');
                } else {
                    if (slider_wrapper.data('ma-el-bg-slider-overlay')) {
                        ma_el_overlay = jltma_scripts.plugin_url + 'assets/lib/vegas/overlays/' + slider_wrapper.data('ma-el-bg-slider-overlay');
                    } else {
                        ma_el_overlay = jltma_scripts.plugin_url + 'assets/lib/vegas/overlays/' + slider_wrapper.data('ma-el-bg-slider-overlay');
                    }
                }

                ma_el_cover = slider_wrapper.data('ma-el-bg-slider-cover');
                ma_el_delay = slider_wrapper.data('ma-el-bs-slider-delay');
                ma_el_timer = slider_wrapper.data('ma-el-bs-slider-timer');

                if (typeof slider_images != 'undefined') {
                    ma_el_slides = slider_images.split(",");

                    jQuery.each(ma_el_slides, function (key, value) {
                        var slide = [];
                        slide.src = value;
                        ma_el_slides_json.push(slide);
                    });

                    slider_wrapper.vegas({
                        slides: ma_el_slides_json,
                        transition: ma_el_transition,
                        animation: ma_el_animation,
                        overlay: ma_el_overlay,
                        cover: ma_el_cover,
                        delay: ma_el_delay,
                        timer: ma_el_timer,
                        init: function () {
                            if (ma_el_custom_overlay == 'yes') {
                                var ob_vegas_overlay = slider_wrapper.children('.vegas-overlay');
                                ob_vegas_overlay.css('background-image', '');
                            }
                        }
                    });

                }
            }
        },

        MA_AnimatedGradient: function ($scope, $) {

            if ($scope.hasClass('ma-el-animated-gradient-yes')) {
                let id = $scope.data('id');
                let color = $scope.data('color');
                let angle = $scope.data('angle');
                let gradient_color = 'linear-gradient(' + angle + ',' + color + ')';
                let heading = $scope.find('.elementor-heading-title');
                $scope.css('background-image', gradient_color);

                if ($scope.hasClass('elementor-element-edit-mode')) {
                    color = $scope.find('.animated-gradient').data('color');
                    angle = $scope.find('.animated-gradient').data('angle');
                    let gradient_color_editor = 'linear-gradient(' + angle + ',' + color + ')';
                    $scope.prepend('<div class="animated-gradient" style="background-image : ' + gradient_color_editor + ' "></div>');
                    //$scope.find('.animated-gradient').css('background-image', gradient_color_editor);
                    //$scope.find('.animated-gradient').css('background-color', 'red');
                }
                //$scope.css('position', 'relative');
                //$scope.css('background-color', 'black');

            }

        },


        MA_Image_Comparison: function ($scope, $) {
            var $jltma_image_comp_wrap       = $scope.find('.jltma-image-comparison').eq(0),
                $jltma_image_data            = $jltma_image_comp_wrap.data('image-comparison-settings');

                $jltma_image_comp_wrap.twentytwenty({
                    default_offset_pct          : $jltma_image_data.visible_ratio,
                    orientation                 : $jltma_image_data.orientation,
                    before_label                : $jltma_image_data.before_label,
                    after_label                 : $jltma_image_data.after_label,
                    move_slider_on_hover        : $jltma_image_data.slider_on_hover,
                    move_with_handle_only       : $jltma_image_data.slider_with_handle,
                    click_to_move               : $jltma_image_data.slider_with_click,
                    no_overlay                  : $jltma_image_data.no_overlay
                });
        },

        MA_PiechartsHandlerOnScroll: function ($scope, $) {

            $scope.waypoint(function (direction) {

                Master_Addons.MA_PiechartsHandler($(this.element), $);

            }, {
                offset: (window.innerHeight || document.documentElement.clientHeight) - 100,
                triggerOnce: true
            });
        },

        MA_PiechartsHandler: function ($scope, $) {

            $scope.find('.ma-el-piechart .ma-el-percentage').each(function () {

                var track_color = $(this).data('track-color');
                var bar_color = $(this).data('bar-color');

                $(this).easyPieChart({
                    animate: 2000,
                    lineWidth: 10,
                    barColor: bar_color,
                    trackColor: track_color,
                    scaleColor: false,
                    lineCap: 'square',
                    size: 220

                });

            });

        },

        StatsBarHandler: function ($scope, $) {

            $scope.find('.ma-el-stats-bar-content').each(function () {

                var dataperc = $(this).data('perc');

                $(this).animate({ "width": dataperc + "%" }, dataperc * 20);

            });

        },

        StatsBarHandlerOnScroll: function ($scope, $) {

            $scope.waypoint(function (direction) {

                Master_Addons.StatsBarHandler($(this.element), $);

            }, {
                offset: (window.innerHeight || document.documentElement.clientHeight) - 150,
                triggerOnce: true
            });

        },

        // Instagram Feed
        MA_Instagram_Feed: function ($scope, $) {

            var $instagramWrapper = $scope.find(".jltma-instagram-feed"),
                $insta_data = $instagramWrapper.data("settings"),
                $insta_carousel_data = $instagramWrapper.data("slider-settings"),
                $insta_lightbox_data = $instagramWrapper.data("lightbox-settings"),
                $layout = $insta_data.layout,
                $gallery = $(this),
                $scope = $(".elementor-element-"+ $insta_data.container_id +"");

                // Carousel Layout
                if($layout == 'carousel'){

                    var $carousel_nav       = $insta_carousel_data.carousel_nav,
                        $loop               = ($insta_carousel_data.loop !== undefined) ? $insta_carousel_data.loop : false,
                        $slidesToShow       = $insta_carousel_data.slidestoshow,
                        $slidesToScroll     = $insta_carousel_data.slidestoscroll,
                        $autoPlay           = ( $insta_carousel_data.autoplay !== undefined) ? $insta_carousel_data.autoplay : false,
                        $autoplaySpeed      = ($insta_carousel_data.autoplayspeed) ? $insta_carousel_data.autoplayspeed : '2400',
                        $transitionSpeed    = $insta_carousel_data.speed,
                        $pauseOnHover       = ($insta_carousel_data.pauseonHover !== undefined) ? $insta_carousel_data.pauseonHover : false,
                        $direction          = $insta_carousel_data.direction;
                        // $adaptiveHeight     = $insta_carousel_data.autoHeight;


                    // Instagram Slider Carousel
                    if ($carousel_nav == "arrows") {
                        var arrows = true;
                        var dots = false;
                    } else {
                        var arrows = false;
                        var dots = true;
                    }

                    $("#jltma-instagram-" + $insta_data.container_id).slick({
                        infinite: $loop,
                        rtl: $direction,
                        slidesToShow: $slidesToShow,
                        slidesToScroll: $slidesToScroll,
                        autoplay: $autoPlay,
                        autoplaySpeed: $autoplaySpeed,
                        speed: $transitionSpeed,
                        mobileFirst:true,
                        pauseOnHover: $pauseOnHover,
                        // adaptiveHeight: $adaptiveHeight,
                        dots: dots,
                        arrows: arrows,
                        prevArrow: "<div class='ma-el-team-carousel-prev'><i class='fa fa-angle-left'></i></div>",
                        nextArrow: "<div class='ma-el-team-carousel-next'><i class='fa fa-angle-right'></i></div>",
                        rows: 0,
                        lazyLoad: 'ondemand',
                        touchMove: true,
                        responsive: [
                            {
                                breakpoint: 480,
                                settings: {
                                    dots: dots,
                                    arrow: arrows,
                                    slidesToShow: 1,
                                    rows:1,
                                    slidesPerRow:1,
                                    slidesToScroll: 1
                                }
                            },
                            {
                                breakpoint: 600,
                                settings: {
                                    dots: dots,
                                    arrow: arrows,
                                    slidesToShow: 2,
                                    slidesToScroll: 2
                                }
                            },
                            {
                                breakpoint: 1024,
                                settings: {
                                    slidesToShow: 3,
                                    slidesToScroll: 3,
                                    infinite: true,
                                    centerMode: false,
                                    dots: dots,
                                    arrow: arrows
                                }
                            },
                        ],
                    });


                }


                // Masonry Layout
                if($layout == 'masonry'){
                    var $settings = {
                        itemSelector: ".jltma-instafeed-item",
                        percentPosition: true,
                        masonry: {
                            columnWidth: ".jltma-instafeed-item",
                        }
                    },
                    $instagram_gallery = $(".jltma-instafeed-masonry", $scope).isotope($settings);

                    // layout gallery, while images are loading
                    $instagram_gallery.imagesLoaded().progress(function() {
                        $instagram_gallery.isotope("layout");
                    });

                    $(".jltma-instafeed-item", $gallery).resize(function() {
                        $instagram_gallery.isotope("layout");
                    });
                }


                // Lightbox Settings
                if( $insta_data.lightbox  == "enabled"){
                    if ($.isFunction($.fn.fancybox)) {
                        $( "#jltma-instagram-" + $insta_data.container_id + ' .jltma-instafeed-item' ).fancybox({
                            protect: $insta_lightbox_data.protect,
                            animationDuration: 366,
                            transitionDuration: 366,
                            transitionEffect: $insta_lightbox_data.transitionEffect, // Transition effect between slides
                            animationEffect: $insta_lightbox_data.animationEffect,
                            preventCaptionOverlap : $insta_lightbox_data.preventCaptionOverlap,
                            // loop: false,
                            infobar: false,
                            buttons: $insta_lightbox_data.buttons,

                            afterLoad : function(instance, current) {
                                var pixelRatio = window.devicePixelRatio || 1;

                                if ( pixelRatio > 1.5 ) {
                                    current.width  = current.width  / pixelRatio;
                                    current.height = current.height / pixelRatio;
                                }
                            }
                        });
                    }
                }

        },

        // Toggle Content
        MA_Toggle_Content: function( $scope, $ ) {
            Master_Addons.getElementSettings    = getElementSettings($scope);
			var $wrapper 			= $scope.find( '.jltma-toggle-content' ),
				toggleElementArgs 	= {
					active : Master_Addons.getElementSettings.jltma_toggle_content_active_index,
                };

            if ( '' !== Master_Addons.getElementSettings.jltma_toggle_content_indicator_color ) {
                toggleElementArgs.indicatorColor = Master_Addons.getElementSettings.jltma_toggle_content_indicator_color;
            }

            if ( Master_Addons.getElementSettings.jltma_toggle_content_indicator_speed.size ) {
                toggleElementArgs.speed = Master_Addons.getElementSettings.jltma_toggle_content_indicator_speed.size;
            }

            if ( elementorFrontend.isEditMode() ) {
                toggleElementArgs.watchControls = true;
            }

            $wrapper.MA_ToggleElement( toggleElementArgs );
        },

        
        // Comment Form reCaptcha
        MA_Comment_Form_reCaptcha: function( $scope, $ ) {
            Master_Addons.getElementSettings    = getElementSettings($scope);
            var $commentsWrapper = $scope.find(".jltma-comments-wrap"),
                $comments_recaptcha_data = $commentsWrapper.data("recaptcha"),
                $recaptcha_theme = $comments_recaptcha_data.theme,
                $recaptcha_sitekey = $comments_recaptcha_data.sitekey,
                jltma_comment_form;

            var onloadCallback = function() {
                jltma_comment_form = grecaptcha.render("jltma_comment_form", {
                    "sitekey" : $recaptcha_sitekey,
                    "theme" : "light"
                });
                grecaptcha.reset(jltma_comment_form);
            };
        },


        // Master Addons: Counter Up
        MA_Counter_Up: function( $scope, $ ) {
            var $counterup = $scope.find(".jltma-counter-up-number");

            if ( $.isFunction($.fn.counterUp) ) {
                $counterup.counterUp({
                    delay: 15,
                    time: 2000
                });
            }
        },


        // Master Addons: Countdown Timer
        MA_CountdownTimer: function ($scope, $) {

            var $countdownWidget = $scope.find(".ma-el-widget-countdown");
            $.fn.MasterCountDownTimer = function () {
                var $wrapper = $(this).find(".ma-el-countdown-wrapper "),
                    data = {
                        year: $wrapper.data("countdown-year"),
                        month: $wrapper.data("countdown-month"),
                        day: $wrapper.data("countdown-day"),
                        hour: $wrapper.data("countdown-hour"),
                        min: $wrapper.data("countdown-min"),
                        sec: $wrapper.data("countdown-sec")
                    },

                    targetDate = new Date(data.year, data.month, data.day, data.hour, data.min, data.sec);
                var $year = $wrapper.find('.ma-el-countdown-year'),
                    $month = $wrapper.find('.ma-el-countdown-month'),
                    $day = $wrapper.find('.ma-el-countdown-day'),
                    $hour = $wrapper.find('.ma-el-countdown-hour'),
                    $min = $wrapper.find('.ma-el-countdown-min'),
                    $sec = $wrapper.find('.ma-el-countdown-sec');

                setInterval(function () {
                    var diffTime = (Date.parse(targetDate) - Date.parse(new Date())) / 1000;

                    if (diffTime < 0) return;

                    $year.text(Math.floor(diffTime / (31536000))); // 1 year = 3153600 second
                    $month.text(Math.floor((diffTime / 2592000) % 12)); // 1 month = 2592000 second
                    $day.text(Math.floor((diffTime / 86400) % 365)); // 1 day = 86400 second
                    $hour.text(Math.floor((diffTime / 3600) % 24)); // 1 hour = 3600 second
                    $min.text(Math.floor((diffTime / 60) % 60)); // 1 min  = 60 second
                    $sec.text(Math.floor((diffTime) % 60));
                }, 1e3)
            }, $countdownWidget.each(function () {
                $(this).MasterCountDownTimer()
            })


        },

        /**
         * Fancybox popup
         */
        MA_Fancybox_Popup: function ($scope, $) {

            try {
                (function ($) {
                    if ($.isFunction($.fn.fancybox)) {
                        $("[data-fancybox]").fancybox({});
                    }
                })(jQuery);
            } catch (e) {
                //We can also throw from try block and catch it here
                // No Error Show
            }

        },

        /*
        * REVEAL
        */
        MA_Reveal: function ($scope, $) {
            var isReveal = false;

            var elementSettings = getElementSettings($scope);

            var rev1;
            var revealAction = function () {
                rev1 = new RevealFx(revealistance, {
                    revealSettings: {
                        bgcolor: elementSettings.reveal_bgcolor,
                        direction: elementSettings.reveal_direction,
                        duration: Number(elementSettings.reveal_speed.size) * 100,
                        delay: Number(elementSettings.reveal_delay.size) * 100,
                        onCover: function (contentEl, revealerEl) {
                            contentEl.style.opacity = 1;
                        }
                    }
                });
            }

            var runReveal = function () {
                rev1.reveal();
            }

            if (elementSettings.enabled_reveal) {

                var revealId = '#reveal-' + $scope.data('id');
                var revealistance = document.querySelector(revealId);

                revealAction();

                var waypointOptions = {
                    offset: '100%',
                    triggerOnce: true
                };
                elementorFrontend.waypoint($(revealistance), runReveal, waypointOptions);
            }

        },

        /*
        * MA Rellax
        */
        MA_Rellax: function ($scope, $) {

            var elementSettings = getElementSettings($scope);
            var rellax = null;

            $(window).on('resize', function () {

                if (rellax) {
                    rellax.destroy();
                    if (rellax)
                        initRellax();
                }
            });

            var initRellax = function () {
                if (elementSettings.enabled_rellax) {

                    currentDevice = elementorFrontend.getCurrentDeviceMode();

                    var setting_speed = 'speed_rellax';
                    var value_speed = 0;

                    if (currentDevice != 'desktop') {
                        setting_speed = 'speed_rellax_' + currentDevice;
                    }

                    if (eval('elementSettings.' + setting_speed + '.size'))
                        value_speed = eval('elementSettings.' + setting_speed + '.size');


                    var rellaxId = '#rellax-' + $scope.data('id');

                    if ($(rellaxId).length)
                        rellax = new Rellax(rellaxId, {
                            speed: value_speed
                        }
                        );
                    isRellax = true;
                };
            };

            initRellax();

        },

        MA_Rellax_Final: function (panel, model, view) {
            Master_Addons.getElementSettings = getElementSettings($scope);
            var $scope = view.$el;
            var scene = $scope.find('#scene');
        },


        // Entrance Animations
        MA_Entrance_Animation: function ($scope, $) {

            $scope = $scope || $(this);

            var $target      = $scope.hasClass('jltma-appear-watch-animation') ? $scope: $scope.find('.jltma-appear-watch-animation'),
                hasAnimation = $('body').hasClass('jltma-page-animation');

            if( ! $target.length ){
                return;
            }   

            if ( hasAnimation ) {
                document.body.addEventListener( 'JltmaPageAnimationDone', function(event) { 
                    $target.appearl({
                        offset: '200px',
                        insetOffset:'0px'
                    }).one( 'appear', function(event, data) {
                        this.classList.add('jltma-animated');
                        this.classList.add('jltma-animated-once');
                    });
                });
            } else {
                $target.appearl({
                    offset: '200px',
                    insetOffset:'0px'
                }).one( 'appear', function(event, data) {
                    this.classList.add('jltma-animated');
                    this.classList.add('jltma-animated-once');
                });
            }
        
        },

        /**
         * Restrict Content
         */
        MA_Restrict_Content_Ajax: function ($scope, $) {

            Master_Addons.getElementSettings = getElementSettings($scope);

            var $restrictwrapper    = $scope.find('.ma-el-restrict-content-wrap').eq(0),
                $scopeId            = $scope.data('id'),
                $restrict_layout    = $restrictwrapper.data('restrict-layout-type'),
                $restrict_type      = $restrictwrapper.data('restrict-type'),
                $error_message      = $restrictwrapper.data('error-message'),
                $rc_ajaxify         = $restrictwrapper.data('rc-ajaxify'),

                $storageID          = 'ma_el_rc_' + $scopeId,
                $formID             = $scope.find('.ma-el-restrict-form').eq(0).data('form-id'),

                // Content
                $content_div         = '#restrict-content-' + $scopeId,

                // Popup Settings
                $popup              = $scope.find( '.ma-el-restrict-content-popup-content' ),
                $content_pass       = $restrictwrapper.data('content-pass') ? $restrictwrapper.data('content-pass') : '',
                $popup_type         = $popup.data('popup-type') ? $popup.data('popup-type') : '',

                // Restrict Age
                $age_wrapper        = $scope.find('.ma-el-restrict-age-wrapper').eq(0),

                $restrict_age       = {
                    min_age             : $age_wrapper.data('min-age'),
                    age_type            : $age_wrapper.data('age-type'),
                    age_title           : $age_wrapper.data('age-title'),
                    age_content         : $age_wrapper.data('age-content'),
                    age_submit          : $( '#' + $formID ).find('button[name="submit"]').val(),
                    checkbox_msg        : $age_wrapper.data('checkbox-msg') ? $age_wrapper.data('checkbox-msg') : "",
                    empty_bday          : $age_wrapper.data('empty-bday') ? $age_wrapper.data('empty-bday') : "",
                    non_exist_bday      : $age_wrapper.data('non-exist-bday') ? $age_wrapper.data('non-exist-bday') : ""
                };


                //Check it the user has been accpeted the agreement
                if (localStorage.getItem($storageID)) {

                    $( '.ma-el-rc-button' ).addClass('d-none');
                    $('#' + $formID).addClass('d-none');
                    $('#ma-el-restrict-age-' + $scopeId).removeClass('card');
                    $('#ma-el-restrict-age-' + $scopeId).removeClass('text-center');
                    $('#restrict-content-' + $scopeId).addClass('d-block');

                } else{

                    // Dom Selector for Onpage/Popup
                    if ($restrict_layout == "popup") {
                        var dom_selector = '#ma-el-rc-modal-'  + $scopeId;
                    } else {
                        var dom_selector = '#ma-el-restrict-content-' + $scopeId;
                    }

                    $( dom_selector ).on( 'click', '.ma_el_ra_select', function() {
                        var wrap = $( this ).closest( '.ma_el_ra_select_wrap' );
                        if( !wrap.find( '.ma_el_ra_options' ).hasClass( 'ma_el_ra_active' ) ) {
                            $( '.ma_el_ra_options' ).removeClass( 'ma_el_ra_active' );
                            wrap.find( '.ma_el_ra_options' ).addClass( 'ma_el_ra_active' );
                            wrap.find( '.ma_el_ra_options' ).find( 'li:contains("' + wrap.find( '.ma_el_ra_select_val' ).html() + '")' ).addClass( 'ma_el_ra_active' );
                        }
                        else {
                            wrap.find( '.ma_el_ra_options' ).removeClass( 'ma_el_ra_active' );
                        }
                    });

                    $( dom_selector ).on( 'click', '.ma_el_ra_options ul li', function() {
                        var wrap = $( this ).closest( '.ma_el_ra_select_wrap' );
                        wrap.find( '.ma_el_ra_select_val' ).html( $( this ).html() );
                        wrap.find( 'select' ).val( $( this ).attr( 'data-val' ) );
                        wrap.find( '.ma_el_ra_options' ).removeClass( 'ma_el_ra_active' );
                    });

                    $( dom_selector ).on( 'mouseover', '.ma_el_ra_options ul li', function() {
                        if ( $( '.ma_el_ra_options ul li' ).hasClass( 'ma_el_ra_active' ) ) {
                            $( '.ma_el_ra_options ul li' ).removeClass( 'ma_el_ra_active' );
                        }
                    });

                    $( document ).click( function(e) {
                        if( $( e.target ).attr( 'class' ) != 'ma_el_ra_select' && !$( '.ma_el_ra_select' ).find( $( e.target ) ).length ) {
                            if( $( '.ma_el_ra_options.ma_el_ra_active' ).length ) {
                                $( '.ma_el_ra_options' ).removeClass( 'ma_el_ra_active' );
                            }
                        }
                    });


                    //Onload Fancybox
                    if( $popup_type == "windowload" || $popup_type=="windowloadfullscreen"){
                           $( "#ma-el-rc-modal-hidden" ).fancybox().trigger('click');
                    }else{
                        $("[data-fancybox]").fancybox({});
                    }

                    $( dom_selector ).on( 'submit', '#' + $formID , function(event) {
                        event.preventDefault();

                        var form = $( this );
                        form.find( '.ma_el_rc_result' ).remove();

                        $.ajax({
                            type: "POST",
                            url: jltma_scripts.ajaxurl,
                            data: {
                                action: 'ma_el_restrict_content',
                                fields: form.serialize(),
                                restrict_type: $restrict_type,
                                error_message: $error_message,
                                content_pass: $content_pass,
                                restrict_age: $restrict_age
                            },
                            cache: false,
                            success: function (result) {

                                try {
                                    result = jQuery.parseJSON( result );

                                    if ( result['result'] == 'success' ) {

                                        $('#restrict-content-'+ $scopeId).removeClass('d-none').addClass('d-block');

                                        //Custom Classes add/remove
                                        $('#' + $formID).addClass('d-none');
                                        $('#ma-el-restrict-age-' + $scopeId).removeClass('card');
                                        $('#ma-el-restrict-age-' + $scopeId).removeClass('text-center');


                                        //Set a cookie to remember the state
                                        localStorage.setItem($storageID, true);
                                        $.fancybox.close();

                                        $('.ma-el-rc-button').addClass('d-none');

                                    } else if ( result['result'] == 'validate' ) {
                                        $( '#' + $formID + ' ' + '.ma_el_rc_submit' ).after( '<div class="ma_el_rc_result"><span class="eicon-info-circle-o"></span> ' + result['output'] + '</div>' );
                                    } else {
                                        throw 0;
                                    }
                                }
                                catch(err) {
                                    $(  '#' + $formID + ' ' + '.ma_el_rc_submit' ).after( '<div class="ma_el_rc_result"><span class="eicon-loading"></span> Failed, please try again.</div>' );
                                }

                            }
                        }); // ajax part end

                    }); // End of Submit Event


                } // localstorage


        },

        MA_Restrict_Content: function ($scope, $) {

            try {
                (function ($) {
                    Master_Addons.getElementSettings = getElementSettings($scope);

                    var $restrictwrapper = $scope.find('.ma-el-restrict-content-wrap').eq(0),
                        $scopeId = $scope.data('id'),
                        $restrict_layout = $restrictwrapper.data('restrict-layout-type'),
                        $restrict_type = $restrictwrapper.data('restrict-type'),

                        $storageID = 'ma_el_rc',

                        // Popup Settings
                        $popup = $scope.find('.ma-el-restrict-content-popup-content'),
                        $content_pass = $restrictwrapper.data('content-pass'),

                        // Restrict Age
                        $age_wrapper = $scope.find('.ma-el-restrict-age-wrapper').eq(0),
                        $min_age = $age_wrapper.data('min-age'),
                        $age_type = $age_wrapper.data('age-type'),
                        $age_title = $age_wrapper.data('age-title'),
                        $age_content = $age_wrapper.data('age-content'),
                        $checkbox_msg = $age_wrapper.data('checkbox-msg');

                    Master_Addons.MA_Restrict_Content_Ajax($scope, $);

                })(jQuery);
            } catch (e) {
                //We can also throw from try block and catch it here
                // No Error Show
            }
        },

        MA_Nav_Menu: function ($scope, $) {
            Master_Addons.getElementSettings = getElementSettings($scope);

            var $menuContainer      = $scope.find(".jltma-nav-menu-element"),
                $menuID             = $menuContainer.data("menu-id"),
                $menu_type          = $menuContainer.data("menu-layout"),
                $menu_trigger       = $menuContainer.data("menu-trigger"),
                $menu_offcanvas     = $menuContainer.data("menu-offcanvas"),
                $menu_toggletype    = $menuContainer.data("menu-toggletype"),
                $submenu_animation  = $menuContainer.data("menu-animation"),
                $menu_container_id  = $menuContainer.data("menu-container-id"),
                $sticky_type = $menuContainer.data("sticky-type"),
                navbar_height = $('#' + $menu_container_id).outerHeight(),
                menu_container_selector = $('#' + $menu_container_id);

            // refresh window on resize
            // $(window).on('resize',function(){location.reload();});


            /* One Page Menu */
            if ($menu_type == "onepage") {

                $(document).on('click', '.jltma-navbar-nav li a', function (e) {
                    if ($(this).attr('href')) {
                        var self = $(this),
                            el = self.get(0),
                            href = el.href,
                            hasHash = href.indexOf('#'),
                            enable = self.parents('.jltma-navbar-nav-default').hasClass('jltma-one-page-enabled');

                        if (hasHash !== -1 && (href.length > 1) && enable && (el.pathname == window.location.pathname)) {
                            e.preventDefault();
                            self.parents('.jltma-menu-container').find('.jltma-close').trigger('click');
                        }
                    }
                });

                // Mobile Menu close outside clicking
                $(document).on('click', function (e) {
                    var click = $(e.target),
                        opened = $(".navbar-collapse").hasClass("show");
                     if(opened === true){
                        $(".jltma-one-page-enabled").removeClass('show');
                     }
                });

            } else {


                // Submenu Hover Animation Effect
                var submenu_animate_class = 'animated ' + $submenu_animation,
                    submenu_selector = $('.jltma-dropdown.jltma-sub-menu');
                $("#" + $menuID + " .jltma-menu-has-children").hover( function () {
                    if (submenu_selector.hasClass('fade-up')) {
                        submenu_selector.removeClass('fade-up');
                    }
                    if (submenu_selector.hasClass('fade-down')) {
                        submenu_selector.removeClass('fade-down');
                    }
                    $('.jltma-dropdown.jltma-sub-menu').addClass( $submenu_animation );
                });



                /* On Scroll Fixed Navbar */
                ///////////////// fixed menu on scroll for Desktop
                if ($sticky_type == "fixed-onscroll") {
                    if ($(window).width() > 768 ) {
                        $(function() {
                            $(window).scroll(function() {
                                var scroll = $(window).scrollTop();
                                if (scroll >= 10) {
                                    menu_container_selector.removeClass(''+$menu_container_id +'').addClass("jltma-on-scroll-fixed");
                                } else {
                                    menu_container_selector.removeClass("jltma-on-scroll-fixed").addClass(''+$menu_container_id +'');
                                }
                            });
                        });
                    }
                }


                if ($sticky_type == "sticky-top") {
                    if ($(window).width() > 768 ) {
                        $(function() {
                            $(window).scroll(function() {
                                var scroll = $(window).scrollTop();
                                if (scroll >= 10) {
                                    menu_container_selector.removeClass(''+$menu_container_id +'').addClass("sticky-top");
                                } else {
                                    menu_container_selector.removeClass("sticky-top").addClass(''+$menu_container_id +'');
                                }
                            });
                        });
                    }
                }


                if ($sticky_type == "smart-scroll") {

                    // add padding top to show content behind navbar
                    $('body').css('padding-top', navbar_height + 'px');
                        menu_container_selector.addClass('jltma-smart-scroll');

                    //////////////////////// detect scroll top or down
                    if ($('.jltma-smart-scroll').length > 0) { // check if element exists
                        var last_scroll_top = 0;

                        $(window).on('scroll', function() {
                            var scroll_top = $(this).scrollTop();
                            if(scroll_top < last_scroll_top) {
                                $('.jltma-smart-scroll').removeClass('scrolled-down').addClass('scrolled-up');
                            }
                            else {
                                $('.jltma-smart-scroll').removeClass('scrolled-up').addClass('scrolled-down');
                            }
                            last_scroll_top = scroll_top;
                        });
                    }
                }


                if ($sticky_type == "nav-fixed-top") {
                    if ($(window).width() > 768 ) {
                        $(function() {
                            // add padding top to show content behind navbar
                            // $('body').css('padding-top', $('#' + $menu_container_id ).outerHeight() + 'px');
                            $('body').css('padding-top', navbar_height + 'px');
                            menu_container_selector.addClass('jltma-fixed-top');

                        });
                    }
                }



                // Menu Settings Megamenu Trigger Effect
                if ($('.jltma-has-megamenu').hasClass('jltma-megamenu-click')) {
                    $('li.jltma-megamenu-click').on('click', function (e) {
                        e.preventDefault;
                        e.stopPropagation();
                        $(this).toggleClass("show");
                        $('.dropdown-menu.jltma-megamenu').toggleClass("show");
                    });
                }
                // else {
                //     $('.jltma-has-megamenu').on('hover', function (e) {
                //         e.preventDefault;
                //         e.stopPropagation();
                //         $(this).toggleClass("show");
                //         $('.dropdown-menu.jltma-megamenu').toggleClass("show");
                //     });
                // }


                if ($menu_toggletype == "toggle") {

                    // Menu Toggle
                    $("#" + $menuID + " .navbar-nav.toggle .jltma-menu-dropdown-toggle").click(function (e) {
                        $(this).parents(".dropdown").toggleClass("open");
                        e.stopPropagation();
                    });
                }


                if ($menu_offcanvas == "toggle-bar") {
                    $(".jltma-nav-panel .navbar-toggler").on("click", function (e) {
                        $('.jltma-burger').toggleClass("jltma-close");
                    });
                }

                // Off Canvas Menu
                if ($menu_offcanvas == "offcanvas" || $menu_offcanvas == "overlay") {

                    // /// offcanvas onmobile
                    $(".jltma-nav-panel .navbar-toggler").on("click", function (e) {
                        e.preventDefault();
                        e.stopPropagation();
                        var offcanvas_id = $(this).attr('data-trigger');
                        $(offcanvas_id).toggleClass("show");
                        $('body').toggleClass("offcanvas-active");
                        $(".jltma-nav-panel ").toggleClass("offcanvas-nav");
                        if ($menu_offcanvas == "overlay") {
                            $(".jltma-nav-panel ").toggleClass("offcanvas-overlay");
                        }
                    });

                    /// Close menu when pressing ESC
                    $(document).on('keydown', function (event) {
                        if (event.keyCode === 27) {
                            $(".mobile-offcanvas").removeClass("show");

                            $(".desktop-offcanvas").removeClass("show");

                            $("body").removeClass("overlay-active");
                        }
                    });

                    $(".btn-close, .jltma-nav-panel .offcanvas-nav, .jltma-nav-panel.desktop .jltma-close, .jltma-close").click(function (e) {
                        $(".jltma-nav-panel ").removeClass("offcanvas-nav");
                        $(".mobile-offcanvas").removeClass("show");

                        $(".desktop-offcanvas").removeClass("show");

                        $("body").removeClass("offcanvas-active");
                        if ($menu_offcanvas == "overlay") {
                            $(".jltma-nav-panel ").removeClass("offcanvas-overlay");
                        }
                    });


                }



            }






        },


        initEvents: function ($scope, $) {

            var mainSearchWrapper = $scope.find('.ma-el-search-wrapper').eq(0),
                $search_type      = mainSearchWrapper.data('search-type'),
                mainContainer = $scope.find('.main-wrap'),
                openCtrl = document.getElementById('btn-search'),
                closeCtrl = document.getElementById('btn-search-close'),
                searchContainer = $scope.find('.jltma-search'),
                inputSearch = searchContainer.find('.search__input');

            $( openCtrl ).on('click', function(){
                mainContainer.addClass('main-wrap--move');
                searchContainer.addClass('search--open');
                setTimeout(function () {
                    inputSearch.focus();
                }, 600);
            });

            $( closeCtrl ).on('click', function(){
                mainContainer.removeClass('main-wrap--move');
                searchContainer.removeClass('search--open');
                inputSearch.blur();
                inputSearch.value = '';
            });

            document.addEventListener('keyup', function (ev) {
                if (ev.keyCode == 27) {
                    Master_Addons.closeSearch();
                }
            });
        },


        MA_Header_Search: function ($scope, $) {
            $('body').addClass('js');
            Master_Addons.initEvents($scope, $);
        }


    };




    $(window).on('elementor/frontend/init', function () {
        if (elementorFrontend.isEditMode()) {
            editMode = true;
        }

        //Global Scripts
        elementorFrontend.hooks.addAction('frontend/element_ready/global', Master_Addons.MA_AnimatedGradient);
        elementorFrontend.hooks.addAction('frontend/element_ready/global', Master_Addons.MA_BgSlider);
        elementorFrontend.hooks.addAction('frontend/element_ready/global', Master_Addons.MA_ParticlesBG);
        elementorFrontend.hooks.addAction('frontend/element_ready/global', Master_Addons.MA_Reveal);
        elementorFrontend.hooks.addAction('frontend/element_ready/global', Master_Addons.MA_Rellax);
        elementorFrontend.hooks.addAction('frontend/element_ready/global', Master_Addons.MA_Entrance_Animation);



        //Element Scripts
        elementorFrontend.hooks.addAction('frontend/element_ready/ma-headlines.default', Master_Addons.MA_Animated_Headlines);
        elementorFrontend.hooks.addAction('frontend/element_ready/ma-advanced-accordion.default', Master_Addons.MA_Accordion);
        elementorFrontend.hooks.addAction('frontend/element_ready/ma-tabs.default', Master_Addons.MA_Tabs);
        elementorFrontend.hooks.addAction('frontend/element_ready/ma-progressbar.default', Master_Addons.MA_ProgressBar);
        elementorFrontend.hooks.addAction('frontend/element_ready/ma-team-members-slider.default', Master_Addons.MA_TeamSlider);
        elementorFrontend.hooks.addAction('frontend/element_ready/ma-image-carousel.default', Master_Addons.MA_Image_Carousel);
        elementorFrontend.hooks.addAction('frontend/element_ready/ma-blog-post.default', Master_Addons.MA_Blog);
        elementorFrontend.hooks.addAction('frontend/element_ready/ma-news-ticker.default', Master_Addons.MA_NewsTicker);
        elementorFrontend.hooks.addAction('frontend/element_ready/ma-table-of-contents.default', Master_Addons.MA_Table_Of_Contents);
        elementorFrontend.hooks.addAction('frontend/element_ready/ma-el-countdown-timer.default', Master_Addons.MA_CountdownTimer);
        elementorFrontend.hooks.addAction('frontend/element_ready/jltma-counter-up.default', Master_Addons.MA_Counter_Up);
        elementorFrontend.hooks.addAction('frontend/element_ready/ma-piecharts.default', Master_Addons.MA_PiechartsHandler);
        elementorFrontend.hooks.addAction('frontend/element_ready/ma-timeline.default', Master_Addons.MA_Timeline);
        elementorFrontend.hooks.addAction('frontend/element_ready/ma-image-filter-gallery.default', Master_Addons.MA_Image_Filter_Gallery);
        elementorFrontend.hooks.addAction('frontend/element_ready/jltma-gallery-slider.default', Master_Addons.MA_Gallery_Slider);

        elementorFrontend.hooks.addAction('frontend/element_ready/ma-el-image-comparison.default', Master_Addons.MA_Image_Comparison);
        elementorFrontend.hooks.addAction('frontend/element_ready/ma-el-restrict-content.default', Master_Addons.MA_Restrict_Content);
        elementorFrontend.hooks.addAction('frontend/element_ready/ma-navmenu.default', Master_Addons.MA_Nav_Menu);
        elementorFrontend.hooks.addAction('frontend/element_ready/ma-search.default', Master_Addons.MA_Header_Search);
        elementorFrontend.hooks.addAction('frontend/element_ready/ma-piecharts.default', Master_Addons.MA_PiechartsHandlerOnScroll);
        elementorFrontend.hooks.addAction('frontend/element_ready/ma-progressbars.default', Master_Addons.StatsBarHandlerOnScroll);
        elementorFrontend.hooks.addAction('frontend/element_ready/jltma-instagram-feed.default', Master_Addons.MA_Instagram_Feed);
        elementorFrontend.hooks.addAction('frontend/element_ready/jltma-toggle-content.default', Master_Addons.MA_Toggle_Content);
        elementorFrontend.hooks.addAction('frontend/element_ready/jltma-comments.default', Master_Addons.MA_Comment_Form_reCaptcha);
        elementorFrontend.hooks.addAction('frontend/element_ready/jltma-logo-slider.default', Master_Addons.MA_Logo_Slider);
        elementorFrontend.hooks.addAction('frontend/element_ready/jltma-twitter-slider.default', Master_Addons.MA_Twitter_Slider);


        if (elementorFrontend.isEditMode()) {
            elementorFrontend.hooks.addAction('frontend/element_ready/ma-headlines.default', Master_Addons.MA_Animated_Headlines);
            elementorFrontend.hooks.addAction('frontend/element_ready/ma-piecharts.default', Master_Addons.MA_PiechartsHandler);
            elementorFrontend.hooks.addAction('frontend/element_ready/ma-progressbars.default', Master_Addons.StatsBarHandler);
            elementorFrontend.hooks.addAction('frontend/element_ready/ma-news-ticker.default', Master_Addons.MA_NewsTicker);
            // elementorFrontend.hooks.addAction('frontend/element_ready/ma-image-filter-gallery.default', Master_Addons.MA_Image_Filter_Gallery);
            elementorFrontend.hooks.addAction('frontend/element_ready/jltma-gallery-slider.default', Master_Addons.MA_Gallery_Slider);
        }




    });

})(jQuery);