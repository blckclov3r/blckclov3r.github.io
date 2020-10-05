;(function($, window, undefined) {

    "use strict";

    /**
     * Get transform property from element
     * @param element
     * @return {*}
     */
    function getTransformProperty(element) {
        // Note that in some versions of IE9 it is critical that
        // msTransform appear in this list before MozTransform
        var properties = [
            "transition",
            "WebkitTransition",
            "msTransition",
            "MozTransition",
            "OTransition"
        ];
        var p;
        while (p = properties.shift()) {
            if (typeof element.style[p] !== "undefined") {
                return p;
            }
        }
        return false;
    }

    /**
     * Tooltip constructor
     * @param elm
     * @param options
     * @constructor
     */
    var Tooltip = function(elm, options) {
        this.element = $(elm);
        this.tooltip = false;
        this.tooltipBody = false;
        this.callout = false;
        this._firstShow = true;
        this._hover = false;
        this._currentPosition = false;
        this.options = $.extend({
            template: '<div class="tooltip"><div class="pp-tooltip-body"><div class="pp-tooltip-content"></div><div class="pp-tooltip-callout"></div></div></div>',
            inline: this.element.data("tooltipInline") || false,
            position: this.element.data("tooltipPosition") || "tt-top",
            hideDelay: this.element.data("tooltipHideDelay") || 50,
            showDelay: this.element.data("tooltipShowDelay") || 0,
			animDuration: 100,
			animationIn: '',
			animationOut: '',
            content: this.element.data("tooltip") || "",
            contentElement: this.element.data("tooltipContentElement") || false,
            offset: this.element.data("tooltipOffset") || 7,
            extraClass: this.element.data("tooltipClass") || false,
            toggleable: this.element.data("tooltipToggleable") || false,
            alwaysOpen: this.element.data("alwaysOpen") || false,
            width: this.element.data("tooltipWidth") || undefined,
            height: this.element.data("tooltipHeight") || undefined
		}, options);
		
        this.init();
    };

    /**
     * Create tooltip element
     */
    Tooltip.prototype.create = function() {
        var self = this, html;
        if (self.tooltip) {
            self.destroy();
        }

        if (self.options.contentElement) {
            html = self.element.find(self.options.contentElement);
        } else {
            var dataContent = self.element.data("tooltip") || false;
            var contentElement = self.element.find(".pp-tooltip-content");
            if (!dataContent && contentElement.length) {
                html = contentElement.children();
            } else if (dataContent) {
                html = dataContent;
            } else {
                html = "No tooltip content provided";
            }
        }

        self.tooltip = $(self.options.template);
        self.tooltipBody = self.tooltip.find(".pp-tooltip-content");
        self.content(html, false);

        if (self.options.inline) {
            self.tooltip.insertAfter(self.element);
        } else {
            self.tooltip.appendTo(document.body);
        }

        var transformProp = getTransformProperty(self.tooltip[0]) || "transform";
        self.tooltip.transform = {
            prop: self.tooltip.css(transformProp+"Property"),
            duration: parseFloat(self.tooltip.css(transformProp+"Duration"), 10) * 1000,
            timing: self.tooltip.css(transformProp+"TimingFunction")
        }

        if (self.options.extraClass) {
            self.tooltip.addClass(this.options.extraClass);
        }

        if (self.options.width) {
            self.tooltip.css("width", this.options.width);
        }

        if (self.options.height) {
            self.tooltip.css("height", this.options.height);
        }

        self.callout = self.tooltip.find(".pp-tooltip-callout");
    };

    /**
     * Destroy tooltip
     */
    Tooltip.prototype.destroy = function() {
        // Remove tooltip element
        if (this.tooltip) {
            this.tooltip.remove();
        }
        // Remove Tooltip instance from element
        if (this.element) {
            this.element.removeData("tooltipInstance");
        }
        // Remove instance from tooltips container
        var idx = $.tooltips.indexOf(this);
        if (idx) {
            $.tooltips.slice(idx, 1);
        }
    };

    /**
     * Initializer
     */
    Tooltip.prototype.init = function() {
        var self = this;
        
        if ( true === self.options.alwaysOpen  ) {
            self.show();
        } else {
            if (self.options.toggleable) {
                self.element.on("click", function() {
                    if (!self.tooltip) {
                        self.create();
                    }
                    if (!self._visible) {
                        self.position();
                        self.show();
                    } else {
                        self.hide();
                    }
                });
            } else {
                self.element.on({
                    "mouseenter": function(e) {
                        if (self._showTimer) {
                            return;
                        }
                        self._showTimer = setTimeout(function() {
                            self._showTimer = null;
                            self.show();
                        }, self.options.showDelay);
                    },
                    "mouseleave": function(e) {
                        if (self._showTimer) {
                            clearTimeout(self._showTimer);
                            self._showTimer = null;
                        }
                        self.hide(true);
                    }
                });
            }
        }
    };

    /**
     * Set new content
     */
    Tooltip.prototype.content = function(content, reposition) {
        reposition = reposition || true;
        if (this.tooltipBody.length) {
            this.tooltipBody.html(content);
            if (reposition) {
                this.position();
            }
        } else {
            throw new Error("Tooltip element is missing body");
        }
    };

    /**
     * Position tooltip element
     */
    Tooltip.prototype.position = function(position) {
        position = position || this.options.position;
        var top, left;
        var pos = this.options.inline ? this.element.position() : this.element.offset();

        var tooltipSize = {
            h: this.tooltip.outerHeight(),
            w: this.tooltip.outerWidth()
        };

        var targetSize = {
            h: this.element.outerHeight(),
            w: this.element.outerWidth()
        };

        var windowSize = {
            h: $(window).height(),
            w: $(window).width()
        };

        var viewport = {
            h: windowSize.h + $(window).scrollTop(),
            w: windowSize.w + $(window).scrollLeft()
        };

        // Calculate top and left according to position
        var offset = this.options.offset;
        switch (position) {
            case "tt-top":
                top = pos.top - tooltipSize.h - offset;
                left = pos.left + (targetSize.w / 2) - (tooltipSize.w / 2);
                break;

            case "tt-bottom":
                top = pos.top + targetSize.h + offset;
                left = pos.left + (targetSize.w / 2) - (tooltipSize.w / 2);
                break;

            case "tt-left":
                top = pos.top + (targetSize.h / 2) - (tooltipSize.h / 2);
                left = pos.left - tooltipSize.w - offset;
                break;

            case "tt-right":
                top = pos.top + (targetSize.h / 2) - (tooltipSize.h / 2);
                left = pos.left + targetSize.w + offset;
                break;
        }

        // Let's make sure that the tooltip is within viewport
        var spaceLeft = viewport.w - (pos.left + targetSize.w + offset) > tooltipSize.w;
        if (left < (viewport.w - windowSize.w) && spaceLeft) {
            if (position === "tt-left" && viewport.w > tooltipSize.w) {
                return this.position("tt-right");
            }
            left = (viewport.w - windowSize.w);
        }
        if ((left + tooltipSize.w) > viewport.w) {
            left = viewport.w - tooltipSize.w;
        }

        spaceLeft = viewport.h - (pos.top + targetSize.h + offset) > tooltipSize.h;
        if (top < (viewport.h - windowSize.h) && spaceLeft) {
            if (position === "tt-top" && viewport.h > (tooltipSize.h / 2)) {
                return this.position("tt-bottom");
            }
            top = (viewport.h - windowSize.h);
        }

        if ((top + tooltipSize.h) > viewport.h && viewport.h > tooltipSize.h) {
            top = viewport.h - tooltipSize.h;
        }

        this.tooltip.css({
            top: top,
            left: left
        }).removeClass("tt-top tt-bottom tt-right tt-left").addClass(position);

        this._currentPosition = position;

        this.positionCallout();

        return {
            top: top,
            left: left
        };
    };

    /**
     * Position tooltip callout
     */
    Tooltip.prototype.positionCallout = function() {
        if (this.callout.length) {
            var top, left;

            this.tooltip.show();
            var elementPos = this.options.inline ? this.element.position() : this.element.offset();
            var tooltipPos = this.options.inline ? this.tooltip.position() : this.tooltip.offset();
            if (!this._visible) {
                this.tooltip.hide();
            }

            switch (this._currentPosition) {
                case "tt-top":
                    top = this.tooltip.outerHeight();
                    //left = this.tooltip.outerWidth()/2;
                    left = elementPos.left - tooltipPos.left + this.element.outerWidth() / 2;
                    break;

                case "tt-bottom":
                    top = 0;
                    //left = this.tooltip.outerWidth()/2;
                    left = elementPos.left - tooltipPos.left + this.element.outerWidth() / 2;
                    break;

                case "tt-left":
                    //top = this.tooltip.outerHeight()/2;
                    top = elementPos.top - tooltipPos.top + this.element.outerHeight() / 2;
                    left = this.tooltip.outerWidth();
                    break;

                case "tt-right":
                    top = elementPos.top - tooltipPos.top + this.element.outerHeight() / 2;
                    left = 0;
                    break;
            }

            this.callout.css({
                top: top,
                left: left
            });
        }
    };

    /**
     * Show tooltip
     * @return {*}
     */
    Tooltip.prototype.show = function() {
        var self = this;

        // If already visible, do nothing
        if (self._visible) {
            return self;
        }

        // Create tooltip element if it doesn't already exist
        if (!self.tooltip) {
            self.create();
        }

        // Lazy loading of images on first show
        if (self._firstShow) {
            self.tooltip.find("[data-src]").each(function(){
                var $this = $(this);
                this.src = $this.data("src");
                $this.removeData("src");
            });
        } else {
            self._firstShow = false;
        }

        // Position tooltip
        self.position();

        // Hide any other tooltips
        $.each($.tooltips, function() {
            if (!this.options.toggleable) {
                this.hide();
            }
		});
		
		//self.tooltip.css("top", (parseFloat( self.tooltip.css('top') ) - 5 ) + 'px' );

		self.tooltip.show();
		
		if ( '' !== self.options.animationIn ) {
			self.tooltip
				.addClass(self.options.animationIn + ' animated')
				.one( 'webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function() {
					$(this).removeClass( self.options.animationIn + ' animated' );
				} );
		}

        setTimeout(function(){
			//self.tooltip.css("top", (parseFloat( self.tooltip.css('top') ) + 5 ) + 'px' );
            self.tooltip.css("opacity", 1);
        }, 0);
        setTimeout(function(){
            self._visible = true;
        }, self.tooltip.transform.duration);

        /*
        self.fadeIn(self.options.animDuration, function () {
            self._visible = true;
        });
        */
    };

    /**
     * Hide tooltip
     * @param timeout
     */
    Tooltip.prototype.hide = function(timeout) {
        var self = this;
        if (timeout) {
            setTimeout(function() {
                if (self._visible && !self._hover) {
                    self.hide();
                }
            }, self.options.hideDelay);
        } else {
            if (self._visible) {
                //self.tooltip.css("top", (parseFloat( self.tooltip.css('top') ) - 5 ) + 'px' );
                //self.tooltip.css("opacity", 0);
                setTimeout(function(){
					if ( '' !== self.options.animationOut ) {
						self.tooltip
							.addClass(self.options.animationOut + ' animated')
							.one( 'webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function() {
								$(this).removeClass( self.options.animationOut + ' animated' );
								$(this).css("opacity", 0);
								$(this).hide();
								self._visible = false;
							} );
					} else {
						if ( '' !== self.options.animationIn ) {
							self.tooltip.removeClass( self.options.animationIn + ' animated' );
						}
						self.tooltip.css("opacity", 0);
                    	self.tooltip.hide();
						self._visible = false;
					}
                }, self.tooltip.transform.duration);

                /*
                 self.tooltip.fadeOut(self.options.animDuration, function () {
                 self._visible = false;
                 });
                 */
            }
        }
    };

    // Tooltips container
    $.tooltips = [];

    // Throttle timer
    var throttled = false;
    // Let's reposition tooltips on resize and scroll
    $(window).on("resize", function() {
        // If throttled, do nothing
        if (throttled) {
            return;
        }
        throttled = setTimeout(function() {
            $.each($.tooltips, function() {
                if (this._visible) {
                    this.position();
                }
            });
            throttled = false;
        }, 100);
    });

    // jQuery plugin wrapper method
    $.fn._tooltip = function(options) {
        var method, args, ret = false;
        if (typeof options === "string") {
            args = [].slice.call(arguments, 0);
        }
        this.each(function() {
            var self = $(this);
            var instance = self.data("tooltipInstance");
            if (instance && options) {
                if (typeof options === "object") {
                    ret = $.extend(instance.options, options);
                } else if (options === "options") {
                    ret =  instance.options;
                } else if (typeof instance[options] === "function") {
                    ret = instance[options].apply(instance, args.slice(1));
                } else {
                    throw new Error("Tooltip has no option/method named " + method);
                }
            } else {
                instance = new Tooltip(self, options || {});
                self.data("tooltipInstance", instance);
                $.tooltips.push(instance);
            }
        });
        return ret || this;
    };

    // Init/destroy tooltips on pageshow/leave
    $(document).on("pageshow pageleave", function (e) {
        $("[data-tooltip]").tooltip(e.type === "pageleave" ? "destroy" : undefined);
    });

    // Create tooltip dynamically
    $(document).on("mouseenter", "[data-tooltip]", function() {
        var $this = $(this);
        if (!$this.data("tooltipInstance")) {
            $this.tooltip().tooltip("show");
        }
    });

}(jQuery, window));