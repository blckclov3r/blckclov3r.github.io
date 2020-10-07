if ("undefined" == typeof jQuery) throw new Error("Bootstrap's JavaScript requires jQuery");
! function (t) {
    "use strict";
    var e = t.fn.jquery.split(" ")[0].split(".");
    if (e[0] < 2 && e[1] < 9 || 1 == e[0] && 9 == e[1] && e[2] < 1 || e[0] > 3) throw new Error("Bootstrap's JavaScript requires jQuery version 1.9.1 or higher, but lower than version 4")
}(jQuery),
function (t) {
    "use strict";
    var e = function (e, i) {
        this.options = i, this.$body = t(document.body), this.$html = t(document.documentElement), this.$element = t(e), this.$dialog = this.$element.find(".premium-modal-box-modal-dialog"), this.$backdrop = null, this.isShown = null, this.originalBodyPad = null, this.scrollbarWidth = 0, this.ignoreBackdropClick = !1, this.options.remote && this.$element.find(".premium-modal-box-modal-content").load(this.options.remote, t.proxy(function () {
            this.$element.trigger("loaded.bs.modal")
        }, this))
    };

    function i(i, o) {
        return this.each(function () {
            var s = t(this),
                n = s.data("bs.modal"),
                r = t.extend({}, e.DEFAULTS, s.data(), "object" == typeof i && i);
            n || s.data("bs.modal", n = new e(this, r)), "string" == typeof i ? n[i](o) : r.show && n.show(o)
        })
    }
    e.VERSION = "3.3.7", e.TRANSITION_DURATION = 300, e.BACKDROP_TRANSITION_DURATION = 150, e.DEFAULTS = {
        backdrop: !0,
        keyboard: !0,
        show: !0
    }, e.prototype.toggle = function (t) {
        return this.isShown ? this.hide() : this.show(t)
    }, e.prototype.show = function (e) {
        var i = this,
            o = t.Event("show.bs.modal", {
                relatedTarget: e
            });
        this.$element.trigger(o), this.isShown || o.isDefaultPrevented() || (this.isShown = !0, this.checkScrollbar(), this.setScrollbar(), this.$body.addClass("premium-modal-open"), this.$html.addClass("premium-modal-open"), this.escape(), this.resize(), this.$element.on("click.dismiss.bs.modal", '[data-dismiss="premium-modal"]', t.proxy(this.hide, this)), this.$dialog.on("mousedown.dismiss.bs.modal", function () {
            i.$element.one("mouseup.dismiss.bs.modal", function (e) {
                t(e.target).is(i.$element) && (i.ignoreBackdropClick = !0)
            })
        }), this.backdrop(function () {
            var o = t.support.transition && i.$element.hasClass("premium-modal-fade");
            i.$element.parent().length || i.$element.appendTo(i.$body), i.$element.show().scrollTop(0), i.adjustDialog(), o && i.$element[0].offsetWidth, i.$element.addClass("premium-in"), i.enforceFocus();
            var s = t.Event("shown.bs.modal", {
                relatedTarget: e
            });
            i.$element.trigger("focus").trigger(s)
        }))
    }, e.prototype.hide = function (e) {
        e && e.preventDefault(), e = t.Event("hide.bs.modal"), this.$element.trigger(e), this.isShown && !e.isDefaultPrevented() && (this.isShown = !1, this.escape(), this.resize(), t(document).off("focusin.bs.modal"), this.$element.removeClass("premium-in").off("click.dismiss.bs.modal").off("mouseup.dismiss.bs.modal"), this.$dialog.off("mousedown.dismiss.bs.modal"), this.hideModal())
    }, e.prototype.enforceFocus = function () {
        t(document).off("focusin.bs.modal").on("focusin.bs.modal", t.proxy(function (t) {
            document === t.target || this.$element[0] === t.target || this.$element.has(t.target).length || this.$element.trigger("focus")
        }, this))
    }, e.prototype.escape = function () {
        this.isShown && this.options.keyboard ? this.$element.on("keydown.dismiss.bs.modal", t.proxy(function (t) {
            27 == t.which && this.hide()
        }, this)) : this.isShown || this.$element.off("keydown.dismiss.bs.modal")
    }, e.prototype.resize = function () {
        this.isShown ? t(window).on("resize.bs.modal", t.proxy(this.handleUpdate, this)) : t(window).off("resize.bs.modal")
    }, e.prototype.hideModal = function () {
        var t = this;
        this.$element.hide(), this.backdrop(function () {
            t.$body.removeClass("premium-modal-open"), t.$html.removeClass("premium-modal-open"), t.resetAdjustments(), t.resetScrollbar(), t.$element.trigger("hidden.bs.modal")
        })
    }, e.prototype.removeBackdrop = function () {
        this.$backdrop && this.$backdrop.remove(), this.$backdrop = null
    }, e.prototype.backdrop = function (e) {
        var i = this,
            o = this.$element.hasClass("premium-modal-fade") ? "premium-modal-fade" : "";
        if (this.isShown && this.options.backdrop) {
            var s = t.support.transition && o;
            if (this.$backdrop = t(document.createElement("div")).addClass("premium-modal-backdrop " + o), this.$element.on("click.dismiss.bs.modal", t.proxy(function (t) {
                    this.ignoreBackdropClick ? this.ignoreBackdropClick = !1 : t.target === t.currentTarget && ("static" == this.options.backdrop ? this.$element[0].focus() : this.hide())
                }, this)), s && this.$backdrop[0].offsetWidth, this.$backdrop.addClass("premium-in"), !e) return;
            e()
        } else if (!this.isShown && this.$backdrop) {
            this.$backdrop.removeClass("premium-in");
            i.removeBackdrop(), e && e()
        } else e && e()
    }, e.prototype.handleUpdate = function () {
        this.adjustDialog()
    }, e.prototype.adjustDialog = function () {
        var t = this.$element[0].scrollHeight > document.documentElement.clientHeight;
        this.$element.css({
            paddingLeft: !this.bodyIsOverflowing && t ? this.scrollbarWidth : "",
            paddingRight: this.bodyIsOverflowing && !t ? this.scrollbarWidth : ""
        })
    }, e.prototype.resetAdjustments = function () {
        this.$element.css({
            paddingLeft: "",
            paddingRight: ""
        })
    }, e.prototype.checkScrollbar = function () {
        var t = window.innerWidth;
        if (!t) {
            var e = document.documentElement.getBoundingClientRect();
            t = e.right - Math.abs(e.left)
        }
        this.bodyIsOverflowing = document.body.clientWidth < t, this.scrollbarWidth = this.measureScrollbar()
    }, e.prototype.setScrollbar = function () {
        parseInt(this.$body.css("padding-right") || 0, 10);
        this.originalBodyPad = document.body.style.paddingRight || ""
    }, e.prototype.resetScrollbar = function () {
        this.$body.css("padding-right", this.originalBodyPad)
    }, e.prototype.measureScrollbar = function () {
        var t = document.createElement("div");
        t.className = "premium-modal-scrollbar-measure", this.$body.append(t);
        var e = t.offsetWidth - t.clientWidth;
        return this.$body[0].removeChild(t), e
    };
    var o = t.fn.modal;
    t.fn.modal = i, t.fn.modal.Constructor = e, t.fn.modal.noConflict = function () {
        return t.fn.modal = o, this
    };
    var s, n = 0;
    t(document).on("click.bs.modal.data-api", '[data-toggle="premium-modal"]', function (e) {
        var o = t(this),
            r = o.attr("href"),
            a = t(o.attr("data-target") || r && r.replace(/.*(?=#[^\s]+$)/, "")),
            d = a.data("bs.modal") ? "toggle" : t.extend({
                remote: !/#/.test(r) && r
            }, a.data(), o.data());
        o.is("a") && e.preventDefault(), a.one("show.bs.modal", function (e) {
            t(this).find("iframe").each(function (index, elem) {
                var source = t(elem).attr("src");
                t(elem).attr("data-src", source);
            });
            0 === n && e.isDefaultPrevented() || a.one("hidden.bs.modal", function () {
                var e = t(this).find(".premium-video-box-container").data("type");
                t(this).find("iframe").each(function (index, elem) {
                    var source = t(elem).attr("data-src");
                    t(elem).attr("src", source);
                });

                "self" === e && t(this).find("video").get(0).pause(), n++, o.is(":visible") && o.trigger("focus")
            })
        }), i.call(a, d, this)
    })
}(jQuery);