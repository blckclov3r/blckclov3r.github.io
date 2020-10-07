"use strict";
jQuery.each(portfolio_param_obj, function (index, value) {
    if (!isNaN(value)) {
        portfolio_param_obj[index] = parseInt(value);
    }
});

function Navy_Grid_Content_Slider(id) {
    var _this = this;
    _this.container = jQuery('#' + id + '.p-main-slider.liquid-slider');
    _this.pauseHover = _this.container.data("pause-hover") == "on";
    _this.autoSlide = _this.container.data("autoslide") == "on";
    _this.slideDuration = +_this.container.data("slide-duration");
    _this.slideInterval = +_this.container.data("slide-interval");
    _this.hasLoading = _this.container.data("show-loading") == "on";
    _this.slideEffect = this.container.data("slide-effect").split('_');
    _this.timeArrowsClick;
    _this.sliderOptons = {
        autoSlide: _this.autoSlide,
        pauseOnHover: _this.pauseHover,
        slideEaseDuration: _this.slideDuration,
        autoSlideInterval: _this.slideInterval,
        animateOut: _this.slideEffect[0],
        animateIn: _this.slideEffect[1]
    };
    _this.documentReady = function () {
        _this.container.liquidSlider(_this.sliderOptons);
    };
    _this.addEventListeners = function () {
        if(_this.autoSlide) {
            jQuery('body').on('click', '.ls-nav-left-arrow,.ls-nav-right-arrow', _this.autoslide);
        }
    };
    _this.autoslide = function(){
        clearTimeout(_this.timeArrowsClick);
        var api = jQuery.data( document.querySelector('#' + id + '.p-main-slider.liquid-slider'), 'liquidSlider');
        _this.timeArrowsClick = setTimeout(function(){
            api.startAutoSlide();
        },_this.slideInterval);
    };
    _this.init = function () {
        _this.documentReady();
        _this.addEventListeners();
    };
    this.init();
}
var portfolios = [];
jQuery(document).ready(function () {
    jQuery(".p-main-slider.view-content-slider").each(function (i) {
        var id = jQuery(this).attr('id');
        portfolios[i] = new Navy_Grid_Content_Slider(id);
    });
});
