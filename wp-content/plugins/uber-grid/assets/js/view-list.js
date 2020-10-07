"use strict";
jQuery.each(portfolio_param_obj, function (index, value) {
    if (!isNaN(value)) {
        portfolio_param_obj[index] = parseInt(value);
    }
});

function Navy_Grid_List(id) {
    var _this = this;
    _this.container = jQuery('#' + id + '.view-list');
    _this.hasLoading = _this.container.data("show-loading") == "on";
    _this.optionsBlock = _this.container.parent().find('div[id^="pfhub_portfolio_options_"]');
    _this.filtersBlock = _this.container.parent().find('div[id^="pfhub_portfolio_filters_"]');
    _this.content = _this.container.parent();
    _this.element = _this.container.find('.portelement');
    _this.defaultBlockWidth = portfolio_param_obj.pfhub_portfolio_view3_mainimage_width;
    _this.optionSets = _this.optionsBlock.find('.option-set'),
        _this.optionLinks = _this.optionSets.find('a');
    _this.sortBy = _this.optionsBlock.find('#sort-by');
    _this.filterButton = _this.filtersBlock.find('ul li');
    if (_this.container.data('show-center') == 'on' && ( ( !_this.content.hasClass('sortingActive') && !_this.content.hasClass('filteringActive') )
        || ( _this.optionsBlock.data('sorting-position') == 'top' && _this.filtersBlock.data('filtering-position') == 'top' ) ||
        ( _this.optionsBlock.data('sorting-position') == 'top' && !_this.content.hasClass('filteringActive') ) || ( !_this.content.hasClass('sortingActive') && _this.filtersBlock.data('filtering-position') == 'top' ) )) {
        _this.isCentered = _this.container.data("show-center");
    }
    _this.documentReady = function () {
        var options = {
            itemSelector: _this.element,
            masonry: {
                columnWidth: _this.defaultBlockWidth + 20 + portfolio_param_obj.pfhub_portfolio_view3_element_border_width * 2,
            },
            masonryHorizontal: {
                rowHeight: 300 + 20
            },
            cellsByRow: {
                columnWidth: 300 + 20,
                rowHeight: 240
            },
            cellsByColumn: {
                columnWidth: 300 + 20,
                rowHeight: 240
            },
            getSortData: {
                symbol: function ($elem) {
                    return $elem.attr('data-symbol');
                },
                category: function ($elem) {
                    return $elem.attr('data-category');
                },
                number: function ($elem) {
                    return parseInt($elem.find('.number').text(), 10);
                },
                weight: function ($elem) {
                    return parseFloat($elem.find('.weight').text().replace(/[\(\)]/g, ''));
                },
                id: function ($elem) {
                    return $elem.find('.id').text();
                }
            }
        };
        pfhubPortfolioIsotope(_this.container);
        pfhubPortfolioIsotope(_this.container,options);
        var loadInterval = setInterval(function(){
            pfhubPortfolioIsotope(_this.container,'layout');
        },100);
        setTimeout(function(){clearInterval(loadInterval);},5000);
    };

    _this.manageLoading = function () {
        if (_this.hasLoading) {
            _this.container.css({'opacity': 1});
            _this.optionsBlock.css({'opacity': 1});
            _this.filtersBlock.css({'opacity': 1});
            _this.content.find('div[id^="pfhub_portfolio-container-loading-overlay_"]').css('display', 'none');
        }
    };




    _this.addEventListeners = function () {
        _this.optionLinks.on('click', _this.optionsClick);
        _this.optionsBlock.find('#shuffle a').on('click',_this.randomClick);
        _this.filterButton.on('click', _this.filtersClick);
        jQuery(window).resize(_this.resizeEvent);


    };
    var $grid = _this.container.pfhubPortfolioNeon({
        getSortData: {
            name: '.name',
            load_date: '.load_date ',
            number:'.number parseInt'
        }
    });
    jQuery('.sort-by-button-group').on( 'click', 'a', function() {
        var sortByValue = jQuery(this).attr('data-option-value');
        $grid.pfhubPortfolioNeon({ sortBy: sortByValue });

    });
    jQuery('.option-set').on( 'click', 'a', function() {
        var sortByKey = jQuery(this).attr('data-option-key');
        var sortByValue = jQuery(this).attr('data-option-value');
        $grid.pfhubPortfolioNeon({ sortBy:sortByKey ,sortAscending: sortByValue === 'true' });
    });

    _this.randomClick = function () {
        pfhubPortfolioIsotope(_this.container,'shuffle');
        _this.sortBy.find('.selected').removeClass('selected');
        _this.sortBy.find('[data-option-value="random"]').addClass('selected');
        return false;
    };
    _this.filtersClick = function () {
        _this.filterButton.each(function () {
            jQuery(this).removeClass('active');
        });
        jQuery(this).addClass('active');
        // get filter value from option value
        var filterValue = jQuery(this).attr('rel');
        // use filterFn if matches value
        pfhubPortfolioIsotope(_this.container,{filter: filterValue});
    };

    _this.init = function () {
        jQuery(window).load(_this.manageLoading);
        _this.documentReady();
        _this.addEventListeners();
    };

    this.init();
}
var portfolios = [];
jQuery(document).ready(function () {
    jQuery(".pfhub_portfolio_container.view-list").each(function (i) {
        var id = jQuery(this).attr('id');
        portfolios[i] = new Navy_Grid_List(id);
    });
});
