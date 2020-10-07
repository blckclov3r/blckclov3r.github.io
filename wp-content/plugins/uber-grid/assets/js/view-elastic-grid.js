"use strict";
jQuery.each(portfolio_param_obj, function (index, value) {
    if (!isNaN(value)) {
        portfolio_param_obj[index] = parseInt(value);
    }
});

function Navy_Grid_Elastic_Grid(id) {
    var _this = this;
    _this.container = jQuery('#' + id + '.view-elastic-grid');
    _this.content = _this.container.find('#og-grid');
    _this.hasLoading = _this.container.data("show-loading") == "on";
    _this.optionsBlock = _this.container.parent().find('div[id^="pfhub_portfolio_options_"]');
    _this.filtersBlock = _this.container.parent().find('div[id^="pfhub_portfolio_filters_"]');
    _this.content = _this.container.parent();
    _this.imageBehaviour = _this.container.data('image-behaviour') == 'crop';
    _this.defaultBlockWidth = portfolio_param_obj.pfhub_portfolio_view7_element_width;
    _this.defaultBlockHeight = portfolio_param_obj.pfhub_portfolio_view7_element_height;
    _this.filterButton = _this.filtersBlock.find('ul li');
    _this.hoverEffect = portfolio_param_obj.pfhub_portfolio_view7_element_hover_effect == 'true';
    _this.hoverEffectInverse = portfolio_param_obj.pfhub_portfolio_view7_hover_effect_inverse == 'true';
    if (_this.container.data('show-center') == 'on' && ( ( !_this.content.hasClass('sortingActive') && !_this.content.hasClass('filteringActive') )
        || ( _this.optionsBlock.data('sorting-position') == 'top' && _this.filtersBlock.data('filtering-position') == 'top' ) ||
        ( _this.optionsBlock.data('sorting-position') == 'top' && !_this.content.hasClass('filteringActive') ) || ( !_this.content.hasClass('sortingActive') && _this.filtersBlock.data('filtering-position') == 'top' ) )) {
        _this.isCentered = _this.container.data("show-center");
    }
    var index = _this.content.attr('data-object-name');
    _this.documentReady = function () {
        jQuery(window).on("elastic-grid:ready",function(){
            _this.container.elastic_grid({
                'showAllText' : portfolio_param_obj.pfhub_portfolio_view7_filter_all_text,
                'filterEffect': portfolio_param_obj.pfhub_portfolio_view7_filter_effect, // moveup, scaleup, fallperspective, fly, flip, helix , popup
                'hoverDirection': _this.hoverEffect,
                'hoverDelay': portfolio_param_obj.pfhub_portfolio_view7_hover_effect_delay,
                'hoverInverse': _this.hoverEffectInverse,
                'expandingSpeed': portfolio_param_obj.pfhub_portfolio_view7_expanding_speed,
                'expandingHeight': portfolio_param_obj.pfhub_portfolio_view7_expand_block_height,
                'items' : window[index]
            });
        });
    };

    _this.manageLoading = function () {
        if (_this.hasLoading) {
            _this.container.css({'opacity': 1});
            _this.optionsBlock.css({'opacity': 1});
            _this.filtersBlock.css({'opacity': 1});
            _this.content.find('div[id^="pfhub_portfolio-container-loading-overlay_"]').css('display', 'none');
        }
    };

    _this.imageBehaiour = function(){
        _this.content.find('ul#og-grid > li > a > img').each(function(i, img) {
            var naturalRatio = jQuery(this).prop('naturalWidth')/jQuery(this).prop('naturalHeight');
            var defaultRatio = _this.defaultBlockWidth/_this.defaultBlockHeight;
            if(naturalRatio<=defaultRatio){
                jQuery(img).css({
                    position: "relative",
                    width: '100%',
                    top: '50%',
                    transform: 'translateY(-50%)',
                    height: 'auto'
                });
            }else {
                jQuery(img).css({
                    position: "relative",
                    height:'100%',
                    left: '50%',
                    transform: 'translateX(-50%)',
                    width: 'auto'
                });
            }
        });
    };

    _this.addEventListeners = function () {

    };

    _this.init = function () {
        _this.documentReady();
        _this.addEventListeners();
        jQuery(window).load(function () {
            if(_this.imageBehaviour){
                _this.imageBehaiour();
            }
            _this.manageLoading();
            _this.container.find('ul#og-grid > li > a figure > span').each(function () {
                if(!jQuery(this).text()){
                    jQuery(this).css('border','none');
                }
            });
        });
    };

    this.init();
}
var portfolios = [];
jQuery(document).ready(function () {
    jQuery(".portfolio-gallery-content.view-elastic-grid").each(function (i) {
        var id = jQuery(this).attr('id');
        portfolios[i] = new Navy_Grid_Elastic_Grid(id);
    });
});
