(function ($) {

    /****** Premium Progress Bar Handler ******/
    var PremiumProgressBarWidgetHandler = function ($scope, trigger) {

        var $progressbarElem = $scope.find(".premium-progressbar-container"),
            settings = $progressbarElem.data("settings"),
            length = settings.progress_length,
            speed = settings.speed,
            type = settings.type;


        if ("line" === type) {

            var $progressbar = $progressbarElem.find(".premium-progressbar-bar");

            if (settings.gradient)
                $progressbar.css("background", "linear-gradient(-45deg, " + settings.gradient + ")");

            $progressbar.animate({
                width: length + "%"
            }, speed);

        } else if ("circle" === type) {
            if (length > 100)
                length = 100;

            $progressbarElem.prop({
                'counter': 0
            }).animate({
                counter: length
            }, {
                duration: speed,
                easing: 'linear',
                step: function (counter) {
                    var rotate = (counter * 3.6);

                    $progressbarElem.find(".premium-progressbar-right-label span").text(Math.ceil(counter) + "%");

                    $progressbarElem.find(".premium-progressbar-circle-left").css('transform', "rotate(" + rotate + "deg)");
                    if (rotate > 180) {

                        $progressbarElem.find(".premium-progressbar-circle").css({
                            '-webkit-clip-path': 'inset(0)',
                            'clip-path': 'inset(0)',
                        });

                        $progressbarElem.find(".premium-progressbar-circle-right").css('visibility', 'visible');
                    }
                }
            });

        } else {

            var $progressbar = $progressbarElem.find(".premium-progressbar-bar-wrap"),
                width = $progressbarElem.outerWidth(),
                dotSize = settings.dot || 25,
                dotSpacing = settings.spacing || 10,
                numberOfCircles = Math.ceil(width / (dotSize + dotSpacing)),
                circlesToFill = numberOfCircles * (length / 100),
                numberOfTotalFill = Math.floor(circlesToFill),
                fillPercent = 100 * (circlesToFill - numberOfTotalFill);

            $progressbar.attr('data-circles', numberOfCircles);
            $progressbar.attr('data-total-fill', numberOfTotalFill);
            $progressbar.attr('data-partial-fill', fillPercent);

            var className = "progress-segment";
            for (var i = 0; i < numberOfCircles; i++) {
                className = "progress-segment";
                var innerHTML = '';

                if (i < numberOfTotalFill) {
                    innerHTML = "<div class='segment-inner'></div>";
                } else if (i === numberOfTotalFill) {

                    innerHTML = "<div class='segment-inner'></div>";
                }

                $progressbar.append("<div class='" + className + "'>" + innerHTML + "</div>");

            }

            if ("frontend" !== trigger) {
                PremiumProgressDotsHandler($scope);
            }

        }

    };

    var PremiumProgressDotsHandler = function ($scope) {

        var $progressbarElem = $scope.find(".premium-progressbar-container"),
            settings = $progressbarElem.data("settings"),
            $progressbar = $scope.find(".premium-progressbar-bar-wrap"),
            data = $progressbar.data(),
            speed = settings.speed,
            increment = 0;

        var numberOfTotalFill = data.totalFill,
            numberOfCircles = data.circles,
            fillPercent = data.partialFill;

        dotIncrement(increment);

        function dotIncrement(inc) {

            var $dot = $progressbar.find(".progress-segment").eq(inc),
                dotWidth = 100;

            if (inc === numberOfTotalFill)
                dotWidth = fillPercent

            $dot.find(".segment-inner").animate({
                width: dotWidth + '%'
            }, speed / numberOfCircles, function () {
                increment++;
                if (increment <= numberOfTotalFill) {
                    dotIncrement(increment);
                }

            });
        }
    };

    /****** Premium Progress Bar Scroll Handler *****/
    var PremiumProgressBarScrollWidgetHandler = function ($scope, $) {

        var $progressbarElem = $scope.find(".premium-progressbar-container"),
            settings = $progressbarElem.data("settings"),
            type = settings.type;

        if ("dots" === type) {
            PremiumProgressBarWidgetHandler($scope, "frontend");
        }

        elementorFrontend.waypoint($scope, function () {
            if ("dots" !== type) {
                PremiumProgressBarWidgetHandler($(this));
            } else {
                PremiumProgressDotsHandler($(this));
            }

        }, {
            offset: Waypoint.viewportHeight() - 150,
            triggerOnce: true
        });
    };

    /****** Premium Video Box Handler ******/
    var PremiumVideoBoxWidgetHandler = function ($scope, $) {

        var $videoBoxElement = $scope.find(".premium-video-box-container"),
            $videoContainer = $videoBoxElement.find(".premium-video-box-video-container"),
            $videoInnerContainer = $videoBoxElement.find('.premium-video-box-inner-wrap'),
            type = $videoBoxElement.data("type"),
            thumbnail = $videoBoxElement.data("thumbnail"),
            video, vidSrc;

        if ("self" === type) {

            video = $videoContainer.find("video");
            vidSrc = video.attr("src");

        } else {

            vidSrc = $videoContainer.data("src");

            if (!thumbnail || -1 !== vidSrc.indexOf("autoplay=1")) {
                playVideo();
            } else {
                vidSrc = vidSrc + "&autoplay=1";
            }

        }


        function playVideo() {

            if ($videoBoxElement.hasClass("playing")) return;

            $videoBoxElement.addClass("playing");

            if ("self" === type) {

                $(video).get(0).play();

                $videoContainer.css({
                    opacity: "1",
                    visibility: "visible"
                });

            } else {

                var $iframe = $("<iframe/>");

                checkRel = vidSrc.indexOf("rel=0");
                $iframe.attr("src", vidSrc);
                $iframe.attr("frameborder", "0");
                $iframe.attr("allowfullscreen", "1");
                $iframe.attr("allow", "autoplay;encrypted-media;");
                $videoContainer.css("background", "#000");
                $videoContainer.html($iframe);
            }

            $videoBoxElement.find(
                ".premium-video-box-image-container, .premium-video-box-play-icon-container, .premium-video-box-description-container"
            ).remove();

            if ("vimeo" === type)
                $videoBoxElement.find(".premium-video-box-vimeo-wrap").remove();
        }


        $videoBoxElement.on("click", function () {
            playVideo();
        });

    };

    /****** Premium Media Grid Handler ******/
    var PremiumGridWidgetHandler = function ($scope, $) {

        var $galleryElement = $scope.find(".premium-gallery-container"),
            settings = $galleryElement.data("settings"),
            layout = settings.img_size,
            deviceType = elementorFrontend.getCurrentDeviceMode(),
            loadMore = settings.load_more,
            columnWidth = null,
            filter = null,
            isFilterClicked = false,
            minimum = settings.minimum,
            imageToShow = settings.click_images,
            counter = minimum,
            ltrMode = settings.ltr_mode,
            shuffle = settings.shuffle;

        if (layout === "metro") {

            var gridWidth = $galleryElement.width(),
                cellSize = Math.floor(gridWidth / 12),
                suffix = null;

            setMetroLayout();

            function setMetroLayout() {

                deviceType = elementorFrontend.getCurrentDeviceMode();
                gridWidth = $galleryElement.width();
                cellSize = Math.floor(gridWidth / 12);
                suffix = "";

                if ("tablet" === deviceType) {
                    suffix = "_tablet";
                } else if ("mobile" === deviceType) {
                    suffix = "_mobile";
                }

                $galleryElement.find(".premium-gallery-item").each(function (index, item) {

                    var cells = $(item).data("metro")["cells" + suffix],
                        vCells = $(item).data("metro")["vcells" + suffix];

                    if ("" == cells || undefined == cells) {
                        cells = $(item).data("metro")["cells"];
                    }
                    if ("" == vCells || undefined == vCells) {
                        vCells = $(item).data("metro")["vcells"];
                    }

                    $(item).css({
                        width: Math.ceil(cells * cellSize),
                        height: Math.ceil(vCells * cellSize)
                    });
                });

                columnWidth = cellSize;
            }

            layout = "masonry";

            $(window).resize(function () {

                setMetroLayout();

                $galleryElement.isotope({
                    itemSelector: ".premium-gallery-item",
                    masonry: {
                        columnWidth: columnWidth
                    },
                });

            });
        }

        $galleryElement.imagesLoaded(function () { }).done(function () {
            $galleryElement.isotope({
                itemSelector: ".premium-gallery-item",
                percentPosition: true,
                animationOptions: {
                    duration: 750,
                    easing: "linear"
                },
                filter: settings.active_cat,
                layoutMode: layout,
                originLeft: ltrMode,
                masonry: {
                    columnWidth: columnWidth
                },
                sortBy: settings.sort_by
            });
        });

        if (loadMore) {

            $galleryElement.parent().find(".premium-gallery-load-more div").addClass(
                "premium-gallery-item-hidden");
            if ($galleryElement.find(".premium-gallery-item").length > minimum) {
                $galleryElement.parent().find(".premium-gallery-load-more").removeClass(
                    "premium-gallery-item-hidden");
                $galleryElement.find(".premium-gallery-item:gt(" + (minimum - 1) + ")").addClass(
                    "premium-gallery-item-hidden");

                function appendItems(imagesToShow) {

                    var instance = $galleryElement.data("isotope");
                    $galleryElement.find(".premium-gallery-item-hidden").removeClass(
                        "premium-gallery-item-hidden");
                    $galleryElement.parent().find(".premium-gallery-load-more").removeClass(
                        "premium-gallery-item-hidden");
                    var itemsToHide = instance.filteredItems.slice(imagesToShow, instance
                        .filteredItems.length).map(function (item) {
                            return item.element;
                        });
                    $(itemsToHide).addClass("premium-gallery-item-hidden");
                    $galleryElement.isotope("layout");
                    if (0 == itemsToHide) {
                        $galleryElement.parent().find(".premium-gallery-load-more").addClass(
                            "premium-gallery-item-hidden");
                    }
                }

                $galleryElement.parent().on("click", ".premium-gallery-load-more-btn", function () {
                    if (isFilterClicked) {
                        counter = minimum;
                        isFilterClicked = false;
                    } else {
                        counter = counter;
                    }
                    counter = counter + imageToShow;
                    $.ajax({
                        url: appendItems(counter),
                        beforeSend: function () {
                            $galleryElement.parent().find(
                                ".premium-gallery-load-more div").removeClass(
                                    "premium-gallery-item-hidden");
                        },
                        success: function () {
                            $galleryElement.parent().find(
                                ".premium-gallery-load-more div").addClass(
                                    "premium-gallery-item-hidden");
                        }
                    });
                });
            }
        }

        if ("yes" !== settings.light_box) {

            $galleryElement.find(".premium-gallery-video-wrap").each(function (index, item) {
                var type = $(item).data("type");
                $(item).closest(".premium-gallery-item").on("click", function () {
                    var $this = $(this);
                    $this.find(".pa-gallery-img-container").css("background", "#000");

                    $this.find("img, .pa-gallery-icons-caption-container, .pa-gallery-icons-wrapper").css("visibility", "hidden");

                    if ("style3" !== settings.skin)
                        $this.find(".premium-gallery-caption").css("visibility", "hidden");

                    if ("hosted" !== type) {
                        var $iframeWrap = $this.find(".premium-gallery-iframe-wrap"),
                            src = $iframeWrap.data("src");

                        src = src.replace("&mute", "&autoplay=1&mute");

                        var $iframe = $("<iframe/>");

                        $iframe.attr("src", src);
                        $iframe.attr("frameborder", "0");
                        $iframe.attr("allowfullscreen", "1");
                        $iframe.attr("allow", "autoplay;encrypted-media;");
                        $iframeWrap.html($iframe);
                        $iframe.css("visibility", "visible");
                    } else {
                        var $video = $(item).find("video");
                        $video.get(0).play();
                        $video.css("visibility", "visible");
                    }
                });
            });

        }

        $scope.find(".premium-gallery-cats-container li a").click(function (e) {
            e.preventDefault();

            isFilterClicked = true;

            //Showing all images of category
            $scope.find(".premium-gallery-cats-container li .active").removeClass("active");
            $(this).addClass("active");

            filter = $(this).attr("data-filter");
            $galleryElement.isotope({
                filter: filter
            });

            if (shuffle) $galleryElement.isotope("shuffle");

            if (loadMore) appendItems(minimum);

            return false;
        });

        if ("default" === settings.lightbox_type) {

            $scope.find(".premium-img-gallery a[data-rel^='prettyPhoto']").prettyPhoto({
                theme: settings.theme,
                hook: "data-rel",
                opacity: 0.7,
                show_title: false,
                deeplinking: false,
                overlay_gallery: settings.overlay,
                custom_markup: "",
                default_width: 900,
                default_height: 506,
                social_tools: ""
            });

        }
    };

    /****** Premium Counter Handler ******/
    var PremiumCounterHandler = function ($scope, $) {

        var $counterElement = $scope.find(".premium-counter");

        elementorFrontend.waypoint($counterElement, function () {

            var counterSettings = $counterElement.data(),
                incrementElement = $counterElement.find(".premium-counter-init"),
                iconElement = $counterElement.find(".icon");

            $(incrementElement).numerator(counterSettings);

            $(iconElement).addClass("animated " + iconElement.data("animation"));

        });

    };

    /****** Premium Fancy Text Handler ******/
    var PremiumFancyTextHandler = function ($scope, $) {

        var $elem = $scope.find(".premium-fancy-text-wrapper");
        var settings = $elem.data("settings");

        function escapeHtml(unsafe) {
            return unsafe.replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(
                /"/g, "&quot;").replace(/'/g, "&#039;");
        }

        if (settings.effect === "typing") {

            var fancyStrings = [];
            settings.strings.forEach(function (item) {
                fancyStrings.push(escapeHtml(item));
            });

            $elem.find(".premium-fancy-text").typed({
                strings: fancyStrings,
                typeSpeed: settings.typeSpeed,
                backSpeed: settings.backSpeed,
                startDelay: settings.startDelay,
                backDelay: settings.backDelay,
                showCursor: settings.showCursor,
                cursorChar: settings.cursorChar,
                loop: settings.loop
            });

        } else if (settings.effect === "slide") {

            $elem.find(".premium-fancy-text").vTicker({
                speed: settings.speed,
                showItems: settings.showItems,
                pause: settings.pause,
                mousePause: settings.mousePause,
                direction: "up"
            });

        } else {

            setFancyAnimation();

            function setFancyAnimation() {

                var $item = $elem.find(".premium-fancy-list-items"),
                    current = 1;

                //Get effect settings
                var delay = settings.delay || 2500,
                    loopCount = settings.count;

                //If Loop Count option is set
                if (loopCount) {
                    var currentLoop = 1,
                        fancyStringsCount = $elem.find(".premium-fancy-list-items").length;
                }

                var loopInterval = setInterval(function () {

                    var animationClass = "";

                    //Add animation class
                    if (settings.effect === "custom")
                        animationClass = "animated " + settings.animation;

                    //Show current active item
                    $item.eq(current).addClass("premium-fancy-item-visible " + animationClass).removeClass("premium-fancy-item-hidden");

                    var $inactiveItems = $item.filter(function (index) {
                        return index !== current;
                    });

                    //Hide inactive items
                    $inactiveItems.addClass("premium-fancy-item-hidden").removeClass("premium-fancy-item-visible " + animationClass);

                    current++;

                    //Restart loop
                    if ($item.length === current)
                        current = 0;

                    //Increment interval and check if loop count is reached
                    if (loopCount) {
                        currentLoop++;

                        if ((fancyStringsCount * loopCount) === currentLoop)
                            clearInterval(loopInterval);
                    }


                }, delay);
            }
        }
    };

    /****** Premium Countdown Handler ******/
    var PremiumCountDownHandler = function ($scope, $) {

        var countDownElement = $scope.find(".premium-countdown").each(function () {

            var countDownSettings = $(this).data("settings");
            var label1 = countDownSettings["label1"],
                label2 = countDownSettings["label2"],
                newLabe1 = label1.split(","),
                newLabe2 = label2.split(",");
            if (countDownSettings["event"] === "onExpiry") {
                $(this).find(".premium-countdown-init").pre_countdown({
                    labels: newLabe2,
                    labels1: newLabe1,
                    until: new Date(countDownSettings["until"]),
                    format: countDownSettings["format"],
                    padZeroes: true,
                    timeSeparator: countDownSettings["separator"],
                    onExpiry: function () {
                        $(this).html(countDownSettings["text"]);
                    },
                    serverSync: function () {
                        return new Date(countDownSettings["serverSync"]);
                    }
                });
            } else if (countDownSettings["event"] === "expiryUrl") {
                $(this).find(".premium-countdown-init").pre_countdown({
                    labels: newLabe2,
                    labels1: newLabe1,
                    until: new Date(countDownSettings["until"]),
                    format: countDownSettings["format"],
                    padZeroes: true,
                    timeSeparator: countDownSettings["separator"],
                    expiryUrl: countDownSettings["text"],
                    serverSync: function () {
                        return new Date(countDownSettings["serverSync"]);
                    }
                });
            }

            times = $(this).find(".premium-countdown-init").pre_countdown("getTimes");

            function runTimer(el) {
                return el == 0;
            }
            if (times.every(runTimer)) {
                if (countDownSettings["event"] === "onExpiry") {
                    $(this).find(".premium-countdown-init").html(countDownSettings["text"]);
                }
                if (countDownSettings["event"] === "expiryUrl") {
                    var editMode = $("body").find("#elementor").length;
                    if (editMode > 0) {
                        $(this).find(".premium-countdown-init").html(
                            "<h1>You can not redirect url from elementor Editor!!</h1>");
                    } else {
                        window.location.href = countDownSettings["text"];
                    }
                }
            }
        });
    };

    /****** Premium Carousel Handler ******/
    var PremiumCarouselHandler = function ($scope, $) {
        var $carouselElem = $scope.find(".premium-carousel-wrapper"),
            settings = $($carouselElem).data("settings"),
            isEdit = elementorFrontend.isEditMode();

        function slideToShow(slick) {

            var slidesToShow = slick.options.slidesToShow,
                windowWidth = $(window).width();
            if (windowWidth > settings.tabletBreak) {
                slidesToShow = settings.slidesDesk;
            }
            if (windowWidth <= settings.tabletBreak) {
                slidesToShow = settings.slidesTab;
            }
            if (windowWidth <= settings.mobileBreak) {
                slidesToShow = settings.slidesMob;
            }
            return slidesToShow;

        }

        //Get templates content on the editor page
        if (isEdit) {

            $carouselElem.find(".item-wrapper").each(function (index, slide) {

                var templateID = $(slide).data("template");

                if (undefined !== templateID) {
                    $.ajax({
                        type: "GET",
                        url: PremiumSettings.ajaxurl,
                        dataType: "html",
                        data: {
                            action: "get_elementor_template_content",
                            templateID: templateID
                        }
                    }).success(function (response) {

                        var data = JSON.parse(response).data;

                        if (undefined !== data.template_content) {

                            $(slide).html(data.template_content);
                            $carouselElem.find(".premium-carousel-inner").slick("refresh");

                        }
                    });
                }
            });

        }

        $carouselElem.on("init", function (event) {

            event.preventDefault();

            setTimeout(function () {
                resetAnimations("init");
            }, 500);

            $(this).find("item-wrapper.slick-active").each(function () {
                var $this = $(this);
                $this.addClass($this.data("animation"));
            });

            $(".slick-track").addClass("translate");

        });

        $carouselElem.find(".premium-carousel-inner").slick({
            vertical: settings.vertical,
            slidesToScroll: settings.slidesToScroll,
            slidesToShow: settings.slidesToShow,
            responsive: [{
                breakpoint: settings.tabletBreak,
                settings: {
                    slidesToShow: settings.slidesTab,
                    slidesToScroll: settings.slidesTab
                }
            },
            {
                breakpoint: settings.mobileBreak,
                settings: {
                    slidesToShow: settings.slidesMob,
                    slidesToScroll: settings.slidesMob
                }
            }
            ],
            useTransform: true,
            fade: settings.fade,
            infinite: settings.infinite,
            speed: settings.speed,
            autoplay: settings.autoplay,
            autoplaySpeed: settings.autoplaySpeed,
            draggable: settings.draggable,
            touchMove: settings.touchMove,
            rtl: settings.rtl,
            adaptiveHeight: settings.adaptiveHeight,
            pauseOnHover: settings.pauseOnHover,
            centerMode: settings.centerMode,
            centerPadding: settings.centerPadding,
            arrows: settings.arrows,
            nextArrow: settings.nextArrow,
            prevArrow: settings.prevArrow,
            dots: settings.dots,
            customPaging: function () {
                return ('<i class="' + settings.customPaging + '" ></i > ');
            }
        });

        function resetAnimations(event) {

            var $slides = $carouselElem.find(".premium-carousel-template");

            if ("init" === event)
                $slides = $slides.not(".slick-current");

            $slides.find(".animated").each(function (index, elem) {

                var settings = $(elem).data("settings");

                if (!settings)
                    return;

                if (!settings._animation && !settings.animation)
                    return;

                var animation = settings._animation || settings.animation;

                $(elem).removeClass("animated " + animation).addClass("elementor-invisible");
            });
        };

        function triggerAnimation() {

            $carouselElem.find(".slick-active .elementor-invisible").each(function (index, elem) {

                var settings = $(elem).data("settings");

                if (!settings)
                    return;

                if (!settings._animation && !settings.animation)
                    return;

                var delay = settings._animation_delay ? settings._animation_delay : 0,
                    animation = settings._animation || settings.animation;

                setTimeout(function () {
                    $(elem).removeClass("elementor-invisible").addClass(animation +
                        ' animated');
                }, delay);
            });
        }

        $carouselElem.on("afterChange", function (event, slick, currentSlide) {

            var slidesScrolled = slick.options.slidesToScroll,
                slidesToShow = slideToShow(slick),
                centerMode = slick.options.centerMode,
                slideToAnimate = currentSlide + slidesToShow - 1;

            //Trigger Aniamtions for the current slide
            triggerAnimation();

            if (slidesScrolled === 1) {
                if (!centerMode === true) {
                    var $inViewPort = $(this).find("[data-slick-index='" + slideToAnimate +
                        "']");
                    if ("null" != settings.animation) {
                        $inViewPort.find("p, h1, h2, h3, h4, h5, h6, span, a, img, i, button")
                            .addClass(settings.animation).removeClass(
                                "premium-carousel-content-hidden");
                    }
                }
            } else {
                for (var i = slidesScrolled + currentSlide; i >= 0; i--) {
                    $inViewPort = $(this).find("[data-slick-index='" + i + "']");
                    if ("null" != settings.animation) {
                        $inViewPort.find("p, h1, h2, h3, h4, h5, h6, span, a, img, i, button")
                            .addClass(settings.animation).removeClass(
                                "premium-carousel-content-hidden");
                    }
                }
            }
        });

        $carouselElem.on("beforeChange", function (event, slick, currentSlide) {

            //Reset Aniamtions for the other slides
            resetAnimations();

            var $inViewPort = $(this).find("[data-slick-index='" + currentSlide + "']");

            if ("null" != settings.animation) {
                $inViewPort.siblings().find(
                    "p, h1, h2, h3, h4, h5, h6, span, a, img, i, button").removeClass(
                        settings.animation).addClass(
                            "premium-carousel-content-hidden");
            }
        });

        if (settings.vertical) {

            var maxHeight = -1;

            elementorFrontend.elements.$window.on('load', function () {
                $carouselElem.find(".slick-slide").each(function () {
                    if ($(this).height() > maxHeight) {
                        maxHeight = $(this).height();
                    }
                });
                $carouselElem.find(".slick-slide").each(function () {
                    if ($(this).height() < maxHeight) {
                        $(this).css("margin", Math.ceil(
                            (maxHeight - $(this).height()) / 2) + "px 0");
                    }
                });
            });
        }
        var marginFix = {
            element: $("a.ver-carousel-arrow"),
            getWidth: function () {
                var width = this.element.outerWidth();
                return width / 2;
            },
            setWidth: function (type) {
                type = type || "vertical";
                if (type == "vertical") {
                    this.element.css("margin-left", "-" + this.getWidth() + "px");
                } else {
                    this.element.css("margin-top", "-" + this.getWidth() + "px");
                }
            }
        };
        marginFix.setWidth();
        marginFix.element = $("a.carousel-arrow");
        marginFix.setWidth("horizontal");
    };

    /****** Premium Banner Handler ******/
    var PremiumBannerHandler = function ($scope, $) {
        var $bannerElement = $scope.find(".premium-banner"),
            $bannerImg = $bannerElement.find("img");


        if ($bannerElement.data("box-tilt")) {
            var reverse = $bannerElement.data("box-tilt-reverse");
            UniversalTilt.init({
                elements: $bannerElement,
                settings: {
                    reverse: reverse
                },
                callbacks: {
                    onMouseLeave: function (el) {
                        el.style.boxShadow = "0 45px 100px rgba(255, 255, 255, 0)";
                    },
                    onDeviceMove: function (el) {
                        el.style.boxShadow = "0 45px 100px rgba(255, 255, 255, 0.3)";
                    }
                }
            });
        }


        $bannerElement.find(".premium-banner-ib").hover(function () {
            $bannerImg.addClass("active");
        }, function () {
            $bannerImg.removeClass("active");
        });
    };

    /****** Premium Modal Box Handler ******/
    var PremiumModalBoxHandler = function ($scope, $) {

        var $modalElem = $scope.find(".premium-modal-box-container"),
            settings = $modalElem.data("settings"),
            instance = null;

        if (settings.trigger === "pageload") {
            $(document).ready(function ($) {
                setTimeout(function () {
                    $modalElem.find(".premium-modal-box-modal").modal();
                }, settings.delay * 1000);
            });
        }

    };

    /****** Premium Blog Handler ******/
    var PremiumBlogHandler = function ($scope, $) {

        var $blogElement = $scope.find(".premium-blog-wrap"),
            $blogPost = $blogElement.find(".premium-blog-post-outer-container"),
            carousel = $blogElement.data("carousel"),
            grid = $blogElement.data("grid"),
            layout = $blogElement.data("layout");

        var $metaSeparators = $blogPost.first().find(".premium-blog-meta-separator");

        if (1 === $metaSeparators.length) {
            $blogPost.find(".premium-blog-meta-separator").remove();
        } else {
            if (!$blogPost.find(".fa-user").length) {
                $blogPost.each(function (index, post) {
                    $(post).find(".premium-blog-meta-separator").first().remove();
                });
            }
        }

        $scope.find(".premium-blog-cats-container li a").click(function (e) {
            e.preventDefault();
            $scope.find(".premium-blog-cats-container li .active").removeClass("active");
            $(this).addClass("active");
            var selector = $(this).attr("data-filter");
            $blogElement.isotope({
                filter: selector,
                layoutMode: "even" === layout ? "fitRows" : "masonry"
            });
            return false;
        });

        if ("masonry" === layout && !carousel) {

            $blogElement.imagesLoaded(function () {
                $blogElement.isotope({
                    itemSelector: ".premium-blog-post-outer-container",
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

            var autoPlay = $blogElement.data("play"),
                speed = $blogElement.data("speed"),
                fade = $blogElement.data("fade"),
                center = $blogElement.data("center"),
                spacing = $blogElement.data("slides-spacing"),
                arrows = $blogElement.data("arrows"),
                dots = $blogElement.data("dots"),
                cols = $blogElement.data("col"),
                colsTablet = $blogElement.data("col-tablet"),
                colsMobile = $blogElement.data("col-mobile"),
                prevArrow = null,
                nextArrow = null;

            if (arrows) {
                (prevArrow =
                    '<a type="button" data-role="none" class="carousel-arrow carousel-prev" aria-label="Next" role="button" style=""><i class="fas fa-angle-left" aria-hidden="true"></i></a>'
                ), (nextArrow =
                    '<a type="button" data-role="none" class="carousel-arrow carousel-next" aria-label="Next" role="button" style=""><i class="fas fa-angle-right" aria-hidden="true"></i></a>'
                    );
            } else {
                prevArrow = prevArrow = "";
            }

            $($blogElement).slick({
                infinite: true,
                slidesToShow: cols,
                slidesToScroll: cols,
                responsive: [{
                    breakpoint: 1025,
                    settings: {
                        slidesToShow: colsTablet,
                        slidesToScroll: 1
                    }
                },
                {
                    breakpoint: 768,
                    settings: {
                        slidesToShow: colsMobile,
                        slidesToScroll: 1
                    }
                }
                ],
                autoplay: autoPlay,
                autoplaySpeed: speed,
                nextArrow: nextArrow,
                prevArrow: prevArrow,
                fade: fade,
                centerMode: center,
                centerPadding: spacing + "px",
                draggable: true,
                dots: dots,
                customPaging: function () {
                    return '<i class="fas fa-circle"></i>';
                }
            });
        }

        //Force posts inner boxes to take the same height
        if ("even" === layout) {

            var equalHeight = $blogElement.data("equal");

            if (equalHeight) {

                var heights = new Array();

                $blogElement.find(".premium-blog-content-wrapper").each(function (index, post) {

                    var height = $(post).outerHeight();

                    heights.push(height);

                });

                var maxHeight = Math.max.apply(null, heights);

                $blogElement.find(".premium-blog-content-wrapper").css("height", maxHeight + "px");
            }

        }
    };

    /****** Premium Image Scroll Handler ******/
    var PremiumImageScrollHandler = function ($scope, $) {
        var scrollElement = $scope.find(".premium-image-scroll-container"),
            scrollOverlay = scrollElement.find(".premium-image-scroll-overlay"),
            scrollVertical = scrollElement.find(".premium-image-scroll-vertical"),
            dataElement = scrollElement.data("settings"),
            imageScroll = scrollElement.find("img"),
            direction = dataElement["direction"],
            reverse = dataElement["reverse"],
            transformOffset = null;

        function startTransform() {
            imageScroll.css("transform", (direction === "vertical" ? "translateY" : "translateX") + "( -" +
                transformOffset + "px)");
        }

        function endTransform() {
            imageScroll.css("transform", (direction === "vertical" ? "translateY" : "translateX") + "(0px)");
        }

        function setTransform() {
            if (direction === "vertical") {
                transformOffset = imageScroll.height() - scrollElement.height();
            } else {
                transformOffset = imageScroll.width() - scrollElement.width();
            }
        }
        if (dataElement["trigger"] === "scroll") {
            scrollElement.addClass("premium-container-scroll");
            if (direction === "vertical") {
                scrollVertical.addClass("premium-image-scroll-ver");
            } else {
                scrollElement.imagesLoaded(function () {
                    scrollOverlay.css({
                        width: imageScroll.width(),
                        height: imageScroll.height()
                    });
                });
            }
        } else {
            if (reverse === "yes") {
                scrollElement.imagesLoaded(function () {
                    scrollElement.addClass("premium-container-scroll-instant");
                    setTransform();
                    startTransform();
                });
            }
            if (direction === "vertical") {
                scrollVertical.removeClass("premium-image-scroll-ver");
            }
            scrollElement.mouseenter(function () {
                scrollElement.removeClass("premium-container-scroll-instant");
                setTransform();
                reverse === "yes" ? endTransform() : startTransform();
            });
            scrollElement.mouseleave(function () {
                reverse === "yes" ? startTransform() : endTransform();
            });
        }
    };


    /****** Premium Contact Form 7 Handler ******/
    var PremiumContactFormHandler = function ($scope, $) {

        var $contactForm = $scope.find(".premium-cf7-container");
        var $input = $contactForm.find(
            'input[type="text"], input[type="email"], textarea, input[type="password"], input[type="date"], input[type="number"], input[type="tel"], input[type="file"], input[type="url"]'
        );

        $input.wrap("<span class='wpcf7-span'>");

        $input.on("focus blur", function () {
            $(this).closest(".wpcf7-span").toggleClass("is-focused");
        });
    };

    /****** Premium Team Members Handler ******/
    var PremiumTeamMembersHandler = function ($scope, $) {
        var $persons = $scope.find(".multiple-persons");
        if (!$persons.length) return;
        var carousel = $persons.data("carousel");
        if (carousel) {
            var autoPlay = $persons.data("play"),
                speed = $persons.data("speed"),
                rtl = $persons.data("rtl"),
                colsNumber = $persons.data("col"),
                prevArrow =
                    '<a type="button" data-role="none" class="carousel-arrow carousel-prev" aria-label="Next" role="button" style=""><i class="fas fa-angle-left" aria-hidden="true"></i></a>',
                nextArrow =
                    '<a type="button" data-role="none" class="carousel-arrow carousel-next" aria-label="Next" role="button" style=""><i class="fas fa-angle-right" aria-hidden="true"></i></a>';
            $persons.slick({
                infinite: true,
                slidesToShow: colsNumber,
                slidesToScroll: colsNumber,
                responsive: [{
                    breakpoint: 1025,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1
                    }
                },
                {
                    breakpoint: 768,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1
                    }
                }
                ],
                autoplay: autoPlay,
                autoplaySpeed: speed,
                rtl: rtl,
                nextArrow: nextArrow,
                prevArrow: prevArrow,
                draggable: true,
                pauseOnHover: true
            });
        }
        if ($persons.hasClass("premium-person-style1")) return;

        if ("yes" !== $persons.data("persons-equal")) return;

        var heights = new Array();

        $persons.find(".premium-person-container").each(function (index, person) {
            $(person).imagesLoaded(function () { }).done(function () {
                var imageHeight = $(person).find(".premium-person-image-container")
                    .outerHeight();
                heights.push(imageHeight);
            });
        });

        $persons.imagesLoaded(function () { }).done(function () {
            var maxHeight = Math.max.apply(null, heights);
            $persons.find(".premium-person-image-wrap").css("height", maxHeight + "px");
        });
    };

    /****** Premium Title Handler ******/
    var PremiumTitleHandler = function ($scope, $) {

        var $titleContainer = $scope.find(".premium-title-container"),
            $titleElement = $titleContainer.find('.premium-title-text');

        if ($titleContainer.hasClass('style9')) {
            var $style9 = $scope.find(".premium-title-style9");

            $style9.each(function () {
                var elm = $(this);
                var holdTime = elm.attr('data-blur-delay') * 1000;
                elm.attr('data-animation-blur', 'process')
                elm.find('.premium-title-style9-letter').each(function (index, letter) {
                    index += 1;
                    var animateDelay;
                    if ($('body').hasClass('rtl')) {
                        animateDelay = 0.2 / index + 's';
                    }
                    else {
                        animateDelay = index / 20 + 's';
                    }
                    $(letter).css({ '-webkit-animation-delay': animateDelay, 'animation-delay': animateDelay });
                })
                setInterval(function () {
                    elm.attr('data-animation-blur', 'done')
                    setTimeout(function () {
                        elm.attr('data-animation-blur', 'process')
                    }, 150);
                }, holdTime);
            });
        }


        if ($titleContainer.hasClass('style8')) {

            var holdTime = $titleElement.attr('data-shiny-delay') * 1000,
                duration = $titleElement.attr('data-shiny-dur') * 1000;

            function shinyEffect() {
                $titleElement.get(0).setAttribute('data-animation', 'shiny');
                setTimeout(function () {
                    $titleElement.removeAttr('data-animation')
                }, duration);
            }

            (function repeat() {
                shinyEffect();
                setTimeout(repeat, holdTime);
            })();
        }

    };


    /****** Premium Icon List Handler ******/
    var PremiumIconListHandler = function ($scope, $) {

        var $listItems = $scope.find(".premium-icon-list-box"),
            items = $listItems.find(".premium-icon-list-content");

        items
            .each(function (index, item) {
                if ($listItems.data("list-animation") && " " != $listItems.data("list-animation")) {
                    var waypoint = new Waypoint({
                        element: item,
                        handler: function () {
                            var element = $(this.element),
                                delay = element.data('delay');
                            setTimeout(function () {
                                element.next('.premium-icon-list-divider , .premium-icon-list-divider-inline').css("opacity", "1");
                                element.next('.premium-icon-list-divider-inline , .premium-icon-list-divider').addClass("animated " + $listItems.data("list-animation"));
                                element.css("opacity", "1"),
                                    element.addClass("animated " + $listItems.data("list-animation"));
                            }, delay);
                            this.destroy();
                        },
                        offset: Waypoint.viewportHeight() - 150,
                    });
                }
            });
    };


    //Elementor JS Hooks
    $(window).on("elementor/frontend/init", function () {

        elementorFrontend.hooks.addAction("frontend/element_ready/premium-addon-video-box.default", PremiumVideoBoxWidgetHandler);
        elementorFrontend.hooks.addAction("frontend/element_ready/premium-img-gallery.default", PremiumGridWidgetHandler);
        elementorFrontend.hooks.addAction("frontend/element_ready/premium-addon-fancy-text.default", PremiumFancyTextHandler);
        elementorFrontend.hooks.addAction("frontend/element_ready/premium-counter.default", PremiumCounterHandler);
        elementorFrontend.hooks.addAction("frontend/element_ready/premium-addon-title.default", PremiumTitleHandler);
        elementorFrontend.hooks.addAction("frontend/element_ready/premium-countdown-timer.default", PremiumCountDownHandler);
        elementorFrontend.hooks.addAction("frontend/element_ready/premium-carousel-widget.default", PremiumCarouselHandler);
        elementorFrontend.hooks.addAction("frontend/element_ready/premium-addon-banner.default", PremiumBannerHandler);
        elementorFrontend.hooks.addAction("frontend/element_ready/premium-addon-modal-box.default", PremiumModalBoxHandler);
        elementorFrontend.hooks.addAction("frontend/element_ready/premium-addon-blog.default", PremiumBlogHandler);
        elementorFrontend.hooks.addAction("frontend/element_ready/premium-image-scroll.default", PremiumImageScrollHandler);
        elementorFrontend.hooks.addAction("frontend/element_ready/premium-contact-form.default", PremiumContactFormHandler);
        elementorFrontend.hooks.addAction("frontend/element_ready/premium-addon-person.default", PremiumTeamMembersHandler);
        elementorFrontend.hooks.addAction("frontend/element_ready/premium-icon-list.default", PremiumIconListHandler);


        if (elementorFrontend.isEditMode()) {
            elementorFrontend.hooks.addAction(
                "frontend/element_ready/premium-addon-progressbar.default", PremiumProgressBarWidgetHandler);
        } else {
            elementorFrontend.hooks.addAction(
                "frontend/element_ready/premium-addon-progressbar.default", PremiumProgressBarScrollWidgetHandler);
        }
    });
})(jQuery);