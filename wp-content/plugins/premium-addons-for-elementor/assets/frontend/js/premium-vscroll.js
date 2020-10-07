(function ($) {
    /****** Premium Vertical Scroll Handler ******/
    var PremiumVerticalScrollHandler = function ($scope, $) {

        var deviceType = elementorFrontend.getCurrentDeviceMode();

        var hiddenClass = "elementor-hidden-" + deviceType;

        if ("mobile" === deviceType)
            hiddenClass = "elementor-hidden-phone";

        if ($scope.closest("section.elementor-element").hasClass(hiddenClass)) {
            return
        }

        var $vScrollElem = $scope.find(".premium-vscroll-wrap"),
            instance = null,
            vScrollSettings = $vScrollElem.data("settings");

        vScrollSettings.deviceType = deviceType;

        instance = new premiumVerticalScroll($vScrollElem, vScrollSettings);
        instance.init();

    };

    window.premiumVerticalScroll = function ($selector, settings) {
        var self = this,
            $window = $(window),
            isTouch = false,
            $instance = $selector,
            checkTemps = $selector.find(".premium-vscroll-sections-wrap")
                .length,
            $htmlBody = $("html, body"),
            $itemsList = $(".premium-vscroll-dot-item", $instance),
            $menuItems = $(".premium-vscroll-nav-item", $instance),
            defaultSettings = {
                speed: 700,
                offset: 0,
                fullSection: true
            },
            settings = $.extend({}, defaultSettings, settings),
            sections = {},
            currentSection = null,
            isScrolling = false,
            inScope = true;

        var touchStartY = 0,
            touchEndY = 0;

        jQuery.extend(jQuery.easing, {
            easeInOutCirc: function (x, t, b, c, d) {
                if ((t /= d / 2) < 1)
                    return (-c / 2) * (Math.sqrt(1 - t * t) - 1) + b;
                return (c / 2) * (Math.sqrt(1 - (t -= 2) * t) + 1) + b;
            }
        });

        self.init = function () {

            isTouch = self.isTouchDevice();

            if (settings.fullTouch || (!isTouch && settings.fullSection)) {
                self.setSectionsOverflow();
            }

            self.setSectionsData();

            $itemsList.on("click.premiumVerticalScroll", self.onNavDotChange);
            $menuItems.on("click.premiumVerticalScroll", self.onNavDotChange);

            $itemsList.on("mouseenter.premiumVerticalScroll", self.onNavDotEnter);

            $itemsList.on("mouseleave.premiumVerticalScroll", self.onNavDotLeave);

            if ("desktop" === settings.deviceType) {
                $window.on("scroll.premiumVerticalScroll", self.onWheel);
            }

            $window.on("resize.premiumVerticalScroll orientationchange.premiumVerticalScroll", self.debounce(50, self.onResize));

            $window.on("load", function () {

                self.setSectionsData();

                //Handle Full Section Scroll
                if (settings.fullTouch || (!isTouch && settings.fullSection))
                    self.sectionsOverflowRefresh();

                self.checkCurrentActive();

            });

            self.keyboardHandler();

            self.scrollHandler();

            if (settings.fullSection) {

                self.fullSectionHandler();
            }

            if (settings.animation) {
                $instance.find(".premium-vscroll-dots").removeClass("elementor-invisible").addClass("animated " + settings.animation + " animated-" + settings.duration);
            }


        };

        self.checkCurrentActive = function () {

            var firstSection = Object.keys(sections)[0];

            //Get first section offset
            var firstSectionOffset = sections[firstSection].offset;

            //If page scroll is lower than first section offset, then set current active to 1
            if (firstSectionOffset >= $window.scrollTop() && firstSectionOffset - $window.scrollTop() < 200) {
                currentSection = 1;
                $itemsList.removeClass("active");
                $($itemsList[0]).addClass("active");
            }

            //If current active section is defined, then show the dots
            if (currentSection)
                $(".premium-vscroll-dots").removeClass("premium-vscroll-dots-hide");

        };

        self.setSectionsOverflow = function () {

            $itemsList.each(function () {

                var $this = $(this),
                    sectionId = $this.data("menuanchor"),
                    $section = $("#" + sectionId),
                    height = $section.outerHeight();

                if (height > $window.outerHeight() && height - $window.outerHeight() >= 50) {

                    $section.find(".elementor").first().wrapInner("<div id='scroller-" + sectionId + "'></div>");

                    $("#scroller-" + sectionId).slimScroll({
                        height: $window.outerHeight(),
                        railVisible: false
                    });

                    var iScrollInstance = new IScroll("#scroller-" + sectionId, {
                        mouseWheel: true,
                        scrollbars: true,
                        hideScrollbars: true,
                        fadeScrollbars: false,
                        disableMouse: true,
                        interactiveScrollbars: false
                    });

                    $("#scroller-" + sectionId).data('iscrollInstance', iScrollInstance);

                    setTimeout(function () {
                        iScrollInstance.refresh();
                    }, 1500);


                }

            });
        };

        self.sectionsOverflowRefresh = function () {

            $itemsList.each(function () {
                var $this = $(this),
                    sectionId = $this.data("menuanchor");

                var $section = $("#scroller-" + sectionId);

                var scroller = $section.data('iscrollInstance');

                if (scroller) {
                    scroller.refresh();
                }

            });

        };

        self.setSectionsData = function () {

            $itemsList.each(function () {
                var $this = $(this),
                    sectionId = $this.data("menuanchor"),
                    $section = $("#" + sectionId),
                    height = $section.outerHeight();

                //Make sure that section exists in the DOM
                if ($section[0]) {

                    sections[sectionId] = {
                        selector: $section,
                        offset: Math.round($section.offset().top),
                        height: height
                    };
                }
            });

        };

        self.fullSectionHandler = function () {

            var vSection = document.getElementById($instance.attr("id"));

            if (!isTouch || !settings.fullTouch) {

                if (checkTemps) {

                    document.addEventListener ?
                        vSection.addEventListener("wheel", self.onWheel, {
                            passive: false
                        }) :
                        vSection.attachEvent("onmousewheel", self.onWheel);

                } else {

                    document.addEventListener ?
                        document.addEventListener("wheel", self.onWheel, {
                            passive: false
                        }) :
                        document.attachEvent("onmousewheel", self.onWheel);

                }

            } else {
                document.addEventListener("touchstart", self.onTouchStart);
                document.addEventListener("touchmove", self.onTouchMove, {
                    passive: false
                });

            }

        };

        self.scrollHandler = function () {

            var index = 0;

            for (var section in sections) {

                var $section = sections[section].selector;

                elementorFrontend.waypoint(
                    $section,
                    function () {

                        var $this = $(this),
                            sectionId = $this.attr("id");

                        if (!isScrolling) {

                            currentSection = sectionId;

                            $itemsList.removeClass("active");
                            $menuItems.removeClass("active");

                            $("[data-menuanchor=" + sectionId + "]", $instance).addClass("active");

                        }
                    }, {
                    offset: 0 !== index ? "0%" : "-1%",
                    triggerOnce: false
                }
                );
                index++;
            }

        };

        self.keyboardHandler = function () {
            $(document).keydown(function (event) {
                if (38 == event.keyCode) {
                    self.onKeyUp(event, "up");
                }

                if (40 == event.keyCode) {
                    self.onKeyUp(event, "down");
                }
            });
        };

        self.isScrolled = function (sectionID, direction) {

            var $section = $("#scroller-" + sectionID);

            var scroller = $section.data('iscrollInstance');

            if (scroller) {
                if ('down' === direction) {
                    return (0 - scroller.y) + $section.scrollTop() + 1 + $section.innerHeight() >= $section[0].scrollHeight;
                } else if ('up' === direction) {
                    return scroller.y >= 0 && !$section.scrollTop();
                }

            } else {
                return true;
            }

        };

        self.isTouchDevice = function () {

            var isTouchDevice = navigator.userAgent.match(/(iPhone|iPod|iPad|Android|playbook|silk|BlackBerry|BB10|Windows Phone|Tizen|Bada|webOS|IEMobile|Opera Mini)/),
                isTouch = (('ontouchstart' in window) || (navigator.msMaxTouchPoints > 0) || (navigator.maxTouchPoints));

            return isTouchDevice || isTouch;

        };

        self.getEventsPage = function (e) {

            var events = [];

            events.y = (typeof e.pageY !== 'undefined' && (e.pageY || e.pageX) ? e.pageY : e.touches[0].pageY);
            events.x = (typeof e.pageX !== 'undefined' && (e.pageY || e.pageX) ? e.pageX : e.touches[0].pageX);

            if (isTouch && typeof e.touches !== 'undefined') {
                events.y = e.touches[0].pageY;
                events.x = e.touches[0].pageX;
            }

            return events;

        };


        self.onTouchStart = function (e) {

            //Prevent page scroll if scrolled down below the last of our sections.
            inScope = true;

            var touchEvents = self.getEventsPage(e);
            touchStartY = touchEvents.y;

        };

        self.onTouchMove = function (e) {

            if (inScope) {
                self.preventDefault(e);
            }

            if (isScrolling) {
                self.preventDefault(e);
                return false;
            }

            var touchEvents = self.getEventsPage(e);

            touchEndY = touchEvents.y;

            var $target = $(e.target),
                sectionSelector = checkTemps ? ".premium-vscroll-temp" : ".elementor-top-section",
                $section = $target.closest(sectionSelector),
                sectionId = $section.attr("id"),
                newSectionId = false,
                prevSectionId = false,
                nextSectionId = false,
                direction = false,
                windowScrollTop = $window.scrollTop();

            $(".premium-vscroll-tooltip").hide();

            if (beforeCheck()) {

                sectionId = self.getFirstSection(sections);

            }

            if (afterCheck()) {

                sectionId = self.getLastSection(sections);

            }

            if (touchStartY > touchEndY) {

                direction = 'down';

            } else if (touchEndY > touchStartY) {

                direction = 'up';

            }

            if (sectionId && sections.hasOwnProperty(sectionId)) {

                prevSectionId = self.checkPrevSection(sections, sectionId);
                nextSectionId = self.checkNextSection(sections, sectionId);

                if ("up" === direction) {

                    if (!nextSectionId && sections[sectionId].offset < windowScrollTop) {
                        newSectionId = sectionId;
                    } else {
                        newSectionId = prevSectionId;
                    }
                }

                if ("down" === direction) {

                    if (!prevSectionId && sections[sectionId].offset > windowScrollTop + 5) {
                        newSectionId = sectionId;
                    } else {
                        newSectionId = nextSectionId;
                    }
                }

                if (newSectionId) {

                    inScope = true;

                    $(".premium-vscroll-dots, .premium-vscroll-nav-menu").removeClass("premium-vscroll-dots-hide");

                    if (!self.isScrolled(sectionId, direction)) {
                        return;
                    }
                    if (Math.abs(touchStartY - touchEndY) > (window.innerHeight / 100 * 15)) {
                        self.onAnchorChange(newSectionId);
                    }

                } else {

                    inScope = false;

                    var $lastselector = checkTemps ? $instance : $("#" + sectionId);

                    if ("down" === direction) {

                        if ($lastselector.offset().top + $lastselector.innerHeight() - $(document).scrollTop() > 600) {

                            $(".premium-vscroll-dots, .premium-vscroll-nav-menu").addClass("premium-vscroll-dots-hide");

                        }

                    } else if ("up" === direction) {

                        if ($lastselector.offset().top - $(document).scrollTop() > 200) {

                            $(".premium-vscroll-dots, .premium-vscroll-nav-menu").addClass("premium-vscroll-dots-hide");

                        }

                    }
                }

            } else {
                inScope = false;
            }

        };

        self.scrollStop = function () {
            $htmlBody.stop(true);
        };

        self.checkNextSection = function (object, key) {
            var keys = Object.keys(object),
                idIndex = keys.indexOf(key),
                nextIndex = (idIndex += 1);

            if (nextIndex >= keys.length) {
                return false;
            }

            var nextKey = keys[nextIndex];

            return nextKey;
        };

        self.checkPrevSection = function (object, key) {
            var keys = Object.keys(object),
                idIndex = keys.indexOf(key),
                prevIndex = (idIndex -= 1);

            if (0 > idIndex) {
                return false;
            }

            var prevKey = keys[prevIndex];

            return prevKey;
        };

        self.debounce = function (threshold, callback) {
            var timeout;

            return function debounced($event) {
                function delayed() {
                    callback.call(this, $event);
                    timeout = null;
                }

                if (timeout) {
                    clearTimeout(timeout);
                }

                timeout = setTimeout(delayed, threshold);
            };
        };

        self.visible = function (selector, partial, hidden) {
            var s = selector.get(0),
                vpHeight = $window.outerHeight(),
                clientSize =
                    hidden === true ? s.offsetWidth * s.offsetHeight : true;
            if (typeof s.getBoundingClientRect === "function") {
                var rec = s.getBoundingClientRect();
                var tViz = rec.top >= 0 && rec.top < vpHeight,
                    bViz = rec.bottom > 0 && rec.bottom <= vpHeight,
                    vVisible = partial ? tViz || bViz : tViz && bViz,
                    vVisible =
                        rec.top < 0 && rec.bottom > vpHeight ? true : vVisible;
                return clientSize && vVisible;
            } else {
                var viewTop = 0,
                    viewBottom = viewTop + vpHeight,
                    position = $window.position(),
                    _top = position.top,
                    _bottom = _top + $window.height(),
                    compareTop = partial === true ? _bottom : _top,
                    compareBottom = partial === true ? _top : _bottom;
                return (
                    !!clientSize &&
                    (compareBottom <= viewBottom && compareTop >= viewTop)
                );
            }
        };

        self.onNavDotEnter = function () {
            var $this = $(this),
                index = $this.data("index");

            if (settings.tooltips) {
                $(
                    '<div class="premium-vscroll-tooltip"><span>' +
                    settings.dotsText[index] +
                    "</span></div>"
                )
                    .hide()
                    .appendTo($this)
                    .fadeIn(200);
            }
        };

        self.onNavDotLeave = function () {
            $(".premium-vscroll-tooltip").fadeOut(200, function () {
                $(this).remove();
            });
        };

        self.onNavDotChange = function (event) {
            var $this = $(this),
                index = $this.index(),
                sectionId = $this.data("menuanchor"),
                offset = null;

            if (!sections.hasOwnProperty(sectionId)) {
                return false;
            }

            offset = sections[sectionId].offset - settings.offset;

            if (offset < 0)
                offset = sections[sectionId].offset;

            if (!isScrolling) {
                isScrolling = true;

                currentSection = sectionId;
                $menuItems.removeClass("active");
                $itemsList.removeClass("active");

                if ($this.hasClass("premium-vscroll-nav-item")) {
                    $($itemsList[index]).addClass("active");
                } else {
                    $($menuItems[index]).addClass("active");
                }

                $this.addClass("active");

                $htmlBody
                    .stop()
                    .clearQueue()
                    .animate({
                        scrollTop: offset
                    },
                        settings.speed,
                        "easeInOutCirc",
                        function () {
                            isScrolling = false;
                        }
                    );
            }
        };

        self.preventDefault = function (event) {

            if (event.preventDefault) {

                event.preventDefault();

            } else {

                event.returnValue = false;

            }

        };


        self.onAnchorChange = function (sectionId) {

            var $this = $("[data-menuanchor=" + sectionId + "]", $instance),
                offset = null;

            if (!sections.hasOwnProperty(sectionId)) {
                return false;
            }

            offset = sections[sectionId].offset - settings.offset;

            if (offset < 0)
                offset = sections[sectionId].offset;

            if (!isScrolling) {
                isScrolling = true;

                if (settings.addToHistory) {
                    window.history.pushState(null, null, "#" + sectionId);
                }

                currentSection = sectionId;

                $itemsList.removeClass("active");
                $menuItems.removeClass("active");

                $this.addClass("active");

                $htmlBody.animate({
                    scrollTop: offset
                },
                    settings.speed,
                    "easeInOutCirc",
                    function () {
                        isScrolling = false;
                    }
                );
            }
        };

        self.onKeyUp = function (event, direction) {

            //If keyboard is triggered before scroll
            if (currentSection === 1) {
                currentSection = $itemsList.eq(0).data("menuanchor");
            }

            var direction = direction || "up",
                nextItem = $(".premium-vscroll-dot-item[data-menuanchor=" + currentSection + "]", $instance).next(),
                prevItem = $(".premium-vscroll-dot-item[data-menuanchor=" + currentSection + "]", $instance).prev();

            event.preventDefault();

            if (isScrolling) {
                return false;
            }

            if ("up" === direction) {
                if (prevItem[0]) {
                    prevItem.trigger("click.premiumVerticalScroll");
                }
            }

            if ("down" === direction) {
                if (nextItem[0]) {
                    nextItem.trigger("click.premiumVerticalScroll");
                }
            }
        };

        self.onScroll = function (event) {
            /* On Scroll Event */
            if (isScrolling) {
                event.preventDefault();
            }
        };

        function getFirstSection(object) {
            return Object.keys(object)[0];
        }

        function getLastSection(object) {
            return Object.keys(object)[Object.keys(object).length - 1];
        }

        function getDirection(e) {
            e = window.event || e;
            var t = Math.max(
                -1,
                Math.min(1, e.wheelDelta || -e.deltaY || -e.detail)
            );
            return t;
        }

        self.onWheel = function (event) {

            if (inScope && !isTouch) {
                self.preventDefault(event);
            }

            if (isScrolling) {
                return false;
            }

            var $target = $(event.target),
                sectionSelector = checkTemps ? ".premium-vscroll-temp" : ".elementor-top-section",
                $section = $target.closest(sectionSelector),
                sectionId = $section.attr("id"),
                $vTarget = self.visible($instance, true, false),
                newSectionId = false,
                prevSectionId = false,
                nextSectionId = false,
                delta = getDirection(event),
                direction = 0 > delta ? "down" : "up",
                windowScrollTop = $window.scrollTop(),
                dotIndex = $(".premium-vscroll-dot-item.active").index();

            if (isTouch) {

                $(".premium-vscroll-tooltip").hide();

                if (dotIndex === $itemsList.length - 1 && !$vTarget) {
                    $(".premium-vscroll-dots, .premium-vscroll-nav-menu").addClass("premium-vscroll-dots-hide");
                } else if (dotIndex === 0 && !$vTarget) {
                    if (
                        $instance.offset().top - $(document).scrollTop() >
                        200
                    ) {
                        $(
                            ".premium-vscroll-dots, .premium-vscroll-nav-menu"
                        ).addClass("premium-vscroll-dots-hide");
                    }
                } else {
                    $(
                        ".premium-vscroll-dots, .premium-vscroll-nav-menu"
                    ).removeClass("premium-vscroll-dots-hide");
                }
            }

            if (beforeCheck()) {
                sectionId = getFirstSection(sections);
            }

            if (afterCheck()) {
                sectionId = getLastSection(sections);
            }

            if (sectionId && sections.hasOwnProperty(sectionId)) {

                prevSectionId = self.checkPrevSection(sections, sectionId);
                nextSectionId = self.checkNextSection(sections, sectionId);

                if ("up" === direction) {
                    if (!nextSectionId && sections[sectionId].offset < windowScrollTop) {
                        newSectionId = sectionId;
                    } else {
                        newSectionId = prevSectionId;
                    }
                }

                if ("down" === direction) {
                    if (!prevSectionId && sections[sectionId].offset > windowScrollTop + 5) {
                        newSectionId = sectionId;
                    } else {
                        newSectionId = nextSectionId;
                    }
                }


                if (newSectionId) {
                    inScope = true;
                    if (!self.isScrolled(sectionId, direction) && !isTouch) {
                        return;
                    }

                    $(".premium-vscroll-dots, .premium-vscroll-nav-menu").removeClass("premium-vscroll-dots-hide");

                    self.onAnchorChange(newSectionId);

                } else {
                    inScope = false;
                    var $lastselector = checkTemps ?
                        $instance :
                        $("#" + sectionId);
                    if ("down" === direction) {
                        if (
                            $lastselector.offset().top +
                            $lastselector.innerHeight() -
                            $(document).scrollTop() >
                            600
                        ) {
                            $(
                                ".premium-vscroll-dots, .premium-vscroll-nav-menu"
                            ).addClass("premium-vscroll-dots-hide");
                        }
                    } else if ("up" === direction) {

                        $(
                            ".premium-vscroll-dots, .premium-vscroll-nav-menu"
                        ).addClass("premium-vscroll-dots-hide");

                    }
                }
            } else {
                inScope = false;
            }
        };

        function beforeCheck() {
            var windowScrollTop = $window.scrollTop(),
                firstSectionId = getFirstSection(sections),
                offset = sections[firstSectionId].offset,
                topBorder = windowScrollTop + $window.outerHeight(),
                visible = self.visible($instance, true, false);

            if (topBorder > offset) {
                return false;
            } else if (visible) {
                return true;
            }
            return false;
        }

        function afterCheck() {
            var windowScrollTop = $window.scrollTop(),
                lastSectionId = getLastSection(sections),
                bottomBorder =
                    sections[lastSectionId].offset +
                    sections[lastSectionId].height,
                visible = self.visible($instance, true, false);

            if (windowScrollTop < bottomBorder) {
                return false;
            } else if (visible) {
                return true;
            }

            return false;
        }

        self.onResize = function () {
            self.setSectionsData();
            self.sectionsOverflowRefresh();
        };

    };

    $(window).on("elementor/frontend/init", function () {
        elementorFrontend.hooks.addAction(
            "frontend/element_ready/premium-vscroll.default",
            PremiumVerticalScrollHandler
        );
    });
})(jQuery);