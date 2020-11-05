;(function ($, w) {
	'use strict';

	var $window = $(w);

	$.fn.getHappySettings = function() {
		return this.data('happy-settings');
	};

	function debounce(func, wait, immediate) {
		var timeout;
		return function() {
			var context = this, args = arguments;
			var later = function() {
				timeout = null;
				if (!immediate) func.apply(context, args);
			};
			var callNow = immediate && !timeout;
			clearTimeout(timeout);
			timeout = setTimeout(later, wait);
			if (callNow) func.apply(context, args);
		};
	}

	function initFilterable($scope, filterFn) {
		var $filterable = $scope.find('.hajs-gallery-filter');
		if ($filterable.length) {
			$filterable.on('click', 'button', function(event) {
				event.stopPropagation();

				var $current = $(this);
				$current
					.parent()
					.addClass('ha-filter-active')
					.siblings()
					.removeClass('ha-filter-active');
				filterFn($current.data('filter'));
			});
		}
	}

	function initPopupGallery($scope, selector, hasPopup, key) {
		if ( ! $.fn.magnificPopup ) {
			return;
		}

		if ( ! hasPopup ) {
			$.magnificPopup.close();
			return;
		}

		$scope.on('click', selector, function(event) {
			event.stopPropagation();
		});

		$scope.find(selector).magnificPopup({
			key: key,
			type: 'image',
			image: {
				titleSrc: function(item) {
					return item.el.attr('title') ? item.el.attr('title') : item.el.find('img').attr('alt');
				}
			},
			gallery: {
				enabled: true,
				preload: [1,2]
			},
			zoom: {
				enabled: true,
				duration: 300,
				easing: 'ease-in-out',
				opener: function(openerElement) {
					return openerElement.is('img') ? openerElement : openerElement.find('img');
				}
			}
		});
	}

	var HandleImageCompare = function($scope) {
		var $item = $scope.find('.hajs-image-comparison'),
			settings = $item.getHappySettings(),
			fieldMap = {
				on_hover: 'move_slider_on_hover',
				on_swipe: 'move_with_handle_only',
				on_click: 'click_to_move'
			};

		settings[fieldMap[settings.move_handle || 'on_swipe']] = true;
		delete settings.move_handle;

		$item.imagesLoaded().done(function() {
			$item.twentytwenty(settings);

			var t = setTimeout(function() {
				$window.trigger('resize.twentytwenty');
				clearTimeout(t);
			}, 400);
		});
	};

	var HandleJustifiedGallery = function($scope) {
		var $item = $scope.find('.hajs-justified-gallery'),
			settings = $item.getHappySettings(),
			hasPopup = settings.enable_popup;

		$item.justifiedGallery($.extend({}, {
			rowHeight: 150,
			lastRow: 'justify',
			margins: 10,
		}, settings));

		initPopupGallery($scope, '.ha-js-popup', hasPopup, 'justifiedgallery');

		initFilterable($scope, function(filter) {
			$item.justifiedGallery({
				lastRow: (filter === '*' ? settings.lastRow : 'nojustify'),
				filter: filter
			});
			var selector = filter !== '*' ? filter : '.ha-js-popup';
			initPopupGallery($scope, selector, hasPopup, 'justifiedgallery');
		});
	};

	$window.on('elementor/frontend/init', function() {
		var EF = elementorFrontend,
			EM = elementorModules;

		var ExtensionHandler = EM.frontend.handlers.Base.extend({
			onInit: function() {
				EM.frontend.handlers.Base.prototype.onInit.apply(this, arguments);
				this.widgetContainer = this.$element.find('.elementor-widget-container')[0];

				this.initFloatingEffects();

				this.initBackgroundOverlay();
			},

			initBackgroundOverlay: function() {
				if (this.isEdit) {
					this.$element.addClass('ha-has-background-overlay')
				}
			},

			getDefaultSettings: function() {
				return {
					targets: this.widgetContainer,
					loop: true,
					direction: 'alternate',
					easing: 'easeInOutSine',
				};
			},

			onElementChange: function(changedProp) {
				if (changedProp.indexOf('ha_floating') !== -1) {
					this.runOnElementChange();
				}
			},

			runOnElementChange: debounce(function() {
				this.animation && this.animation.restart();
				this.initFloatingEffects();
			}, 200),

			getConfig: function(key) {
				return this.getElementSettings('ha_floating_fx_' + key);
			},

			initFloatingEffects: function() {
				var config = this.getDefaultSettings();

				if (this.getConfig('translate_toggle')) {
					if (this.getConfig('translate_x.size') || this.getConfig('translate_x.sizes.to')) {
						config.translateX = {
							value: [this.getConfig('translate_x.sizes.from') || 0, this.getConfig('translate_x.size') || this.getConfig('translate_x.sizes.to')],
							duration: this.getConfig('translate_duration.size'),
							delay: this.getConfig('translate_delay.size') || 0
						}
					}
					if (this.getConfig('translate_y.size') || this.getConfig('translate_y.sizes.to')) {
						config.translateY = {
							value: [this.getConfig('translate_y.sizes.from') || 0, this.getConfig('translate_y.size') || this.getConfig('translate_y.sizes.to')],
							duration: this.getConfig('translate_duration.size'),
							delay: this.getConfig('translate_delay.size') || 0
						}
					}
				}

				if (this.getConfig('rotate_toggle')) {
					if (this.getConfig('rotate_x.size') || this.getConfig('rotate_x.sizes.to')) {
						config.rotateX = {
							value: [this.getConfig('rotate_x.sizes.from') || 0, this.getConfig('rotate_x.size') || this.getConfig('rotate_x.sizes.to')],
							duration: this.getConfig('rotate_duration.size'),
							delay: this.getConfig('rotate_delay.size') || 0
						}
					}
					if (this.getConfig('rotate_y.size') || this.getConfig('rotate_y.sizes.to')) {
						config.rotateY = {
							value: [this.getConfig('rotate_y.sizes.from') || 0, this.getConfig('rotate_y.size') || this.getConfig('rotate_y.sizes.to')],
							duration: this.getConfig('rotate_duration.size'),
							delay: this.getConfig('rotate_delay.size') || 0
						}
					}
					if (this.getConfig('rotate_z.size') || this.getConfig('rotate_z.sizes.to')) {
						config.rotateZ = {
							value: [this.getConfig('rotate_z.sizes.from') || 0, this.getConfig('rotate_z.size') || this.getConfig('rotate_z.sizes.to')],
							duration: this.getConfig('rotate_duration.size'),
							delay: this.getConfig('rotate_delay.size') || 0
						}
					}
				}

				if (this.getConfig('scale_toggle')) {
					if (this.getConfig('scale_x.size') || this.getConfig('scale_x.sizes.to')) {
						config.scaleX = {
							value: [this.getConfig('scale_x.sizes.from') || 0, this.getConfig('scale_x.size') || this.getConfig('scale_x.sizes.to')],
							duration: this.getConfig('scale_duration.size'),
							delay: this.getConfig('scale_delay.size') || 0
						}
					}
					if (this.getConfig('scale_y.size') || this.getConfig('scale_y.sizes.to')) {
						config.scaleY = {
							value: [this.getConfig('scale_y.sizes.from') || 0, this.getConfig('scale_y.size') || this.getConfig('scale_y.sizes.to')],
							duration: this.getConfig('scale_duration.size'),
							delay: this.getConfig('scale_delay.size') || 0
						}
					}
				}

				if (this.getConfig('translate_toggle') || this.getConfig('rotate_toggle') || this.getConfig('scale_toggle')) {
					this.widgetContainer.style.setProperty('will-change', 'transform');
					this.animation = anime(config);
				}
			}
		});

		var Slick = EM.frontend.handlers.Base.extend({
			onInit: function () {
				EM.frontend.handlers.Base.prototype.onInit.apply(this, arguments);
				this.$container = this.$element.find('.hajs-slick');
				this.run();
			},

			isCarousel: function() {
				return this.$element.hasClass('ha-carousel');
			},

			getDefaultSettings: function() {
				return {
					arrows: false,
					dots: false,
					checkVisible: false,
					infinite: true,
					slidesToShow: this.isCarousel() ? 3 : 1,
					rows: 0,
					prevArrow: '<button type="button" class="slick-prev"><i class="fa fa-chevron-left"></i></button>',
					nextArrow: '<button type="button" class="slick-next"><i class="fa fa-chevron-right"></i></button>',
				}
			},

			onElementChange: function() {
				this.$container.slick('unslick');
				this.run();
			},

			getReadySettings: function() {
				var settings = {
					infinite: !! this.getElementSettings('loop'),
					autoplay: !! this.getElementSettings('autoplay'),
					autoplaySpeed: this.getElementSettings('autoplay_speed'),
					speed: this.getElementSettings('animation_speed'),
					centerMode: !! this.getElementSettings('center'),
					vertical: !! this.getElementSettings('vertical'),
					slidesToScroll: 1,
				};

				switch (this.getElementSettings('navigation')) {
					case 'arrow':
						settings.arrows = true;
						break;
					case 'dots':
						settings.dots = true;
						break;
					case 'both':
						settings.arrows = true;
						settings.dots = true;
						break;
				}

				if (this.isCarousel()) {
					settings.slidesToShow = this.getElementSettings('slides_to_show') || 3;
					settings.responsive = [
						{
							breakpoint: EF.config.breakpoints.lg,
							settings: {
								slidesToShow: (this.getElementSettings('slides_to_show_tablet') || settings.slidesToShow),
							}
						},
						{
							breakpoint: EF.config.breakpoints.md,
							settings: {
								slidesToShow: (this.getElementSettings('slides_to_show_mobile') || this.getElementSettings('slides_to_show_tablet')) || settings.slidesToShow,
							}
						}
					];
				}

				return $.extend({}, this.getDefaultSettings(), settings);
			},

			run: function() {
				this.$container.slick(this.getReadySettings());
			}
		});

		var NumberHandler = function($scope) {
			EF.waypoint($scope, function () {
				var $number = $scope.find('.ha-number-text');
				$number.numerator($number.data('animation'));
			});
		};

		var SkillHandler = function($scope) {
			EF.waypoint($scope, function () {
				$scope.find('.ha-skill-level').each(function() {
					var $current = $(this),
						$lt = $current.find('.ha-skill-level-text'),
						lv = $current.data('level');

					$current.animate({
						width: lv+'%'
					}, 500);
					$lt.numerator({
						toValue: lv + '%',
						duration: 1300,
						onStep: function() {
							$lt.append('%');
						}
					});
				});
			});
		};

		var Isotope = EM.frontend.handlers.Base.extend({
			onInit: function () {
				EM.frontend.handlers.Base.prototype.onInit.apply(this, arguments);
				this.$container = this.$element.find('.hajs-isotope');
				this.run();
				this.runFilter();
			},

			getLayoutMode: function() {
				var layout = this.getElementSettings('layout');
				return ( layout === 'even' ? 'masonry' : layout );
			},

			getDefaultSettings: function() {
				return {
					itemSelector: '.ha-image-grid-item',
					percentPosition: true,
					layoutMode: this.getLayoutMode()
				};
			},

			runFilter: function() {
				var self = this;
				initFilterable(this.$element, function(filter) {
					self.$container.isotope({
						filter: filter
					});

					var selector = filter !== '*' ? filter : '.ha-js-popup';
					initPopupGallery(self.$element, selector, self.getElementSettings('enable_popup'), 'imagegrid');
				});
			},

			onElementChange: function(changedProp) {
				if (['layout', 'image_height', 'columns', 'image_margin', 'enable_popup'].indexOf(changedProp) !== -1) {
					this.run();
				}
			},

			run: function() {
				var self = this;

				this.$container.isotope(self.getDefaultSettings());
				this.$container.imagesLoaded().progress(function() {
					self.$container.isotope('layout');
				});

				initPopupGallery(this.$element, '.ha-js-popup', this.getElementSettings('enable_popup'), 'imagegrid');
			}
		});

		//NewsTicker
		var NewsTicker = EM.frontend.handlers.Base.extend({

			onInit: function () {
				EM.frontend.handlers.Base.prototype.onInit.apply(this, arguments);
				this.wrapper = this.$element.find('.ha-news-ticker-wrapper');
				this.run();
			},
			onElementChange: function (changed_prop) {
				if( changed_prop === 'item_space' || changed_prop === 'title_typography_font_size' ){
					this.run();
				}
			},
			run: function () {
				var wrapper_height = this.wrapper.innerHeight(),
					wrapper_width = this.wrapper.innerWidth(),
					container = this.wrapper.find('.ha-news-ticker-container'),
					single_item = container.find('.ha-news-ticker-item'),
					scroll_direction = this.wrapper.data('scroll-direction'),
					scroll = 'scroll'+scroll_direction+wrapper_height+wrapper_width,
					duration = this.wrapper.data('duration'),
					direction = 'normal',
					all_title_width = 10;

				var start = {'transform': 'translateX(0'+wrapper_width+'px)'},
					end = {'transform': 'translateX(-101%)'};
				if('right' === scroll_direction){
					direction = 'reverse';
				}
				single_item.each(function(){
					all_title_width += $(this).outerWidth(true);
				});
				container.css({'width':all_title_width,'display':'flex'});
				$.keyframe.define([{
					name: scroll,
					'0%': start,
					'100%':end,
				}]);
				container.playKeyframe({
					name: scroll,
					duration: duration+'ms',
					timingFunction: 'linear',
					delay: '0s',
					iterationCount: 'infinite',
					direction: direction,
					fillMode: 'none',
					complete: function(){
					}
				});
			}
		});

		// fun-factor
		var FunFactor = function ($scope) {
			EF.waypoint($scope, function () {
				var $fun_factor = $scope.find('.ha-fun-factor__content-number');
				$fun_factor.numerator($fun_factor.data('animation'));
			});
		};

		var BarChart = function( $scope ) {
			EF.waypoint($scope, function () {
				var $chart = $(this),
					$container = $chart.find( '.ha-bar-chart-container' ),
					$chart_canvas = $chart.find( '#ha-bar-chart' ),
					settings      = $container.data( 'settings' );

				if ( $container.length ) {
					new Chart( $chart_canvas, settings );
				}
			} );
		};

		//twitter Feed
		var TwitterFeed = function($scope) {
			var button = $scope.find('.ha-twitter-load-more');
			var twitter_wrap = $scope.find('.ha-tweet-items');
			button.on("click", function(e) {
				e.preventDefault();
				var $self = $(this),
					query_settings = $self.data("settings"),
					total = $self.data("total"),
					items = $scope.find('.ha-tweet-item').length;
				$.ajax({
					url: HappyLocalize.ajax_url,
					type: 'POST',
					data: {
						action: "ha_twitter_feed_action",
						security: HappyLocalize.nonce,
						query_settings: query_settings,
						loaded_item: items,
					},
					success: function(response) {
						if(total > items){
							$(response).appendTo(twitter_wrap);
						}else{
							$self.text('All Loaded').addClass('loaded');
							setTimeout( function(){
								$self.css({"display": "none"});
							},800);
						}
					},
					error: function(error) {}
				});
			});
		};

		//PostTab
		var PostTab = EM.frontend.handlers.Base.extend({

			onInit: function () {
				EM.frontend.handlers.Base.prototype.onInit.apply(this, arguments);
				this.wrapper = this.$element.find('.ha-post-tab');
				this.run();
			},
			run: function () {
				var filter_wrap = this.wrapper.find('.ha-post-tab-filter'),
					filter = filter_wrap.find('li'),
					event = this.wrapper.data('event'),
					args = this.wrapper.data('query-args');

				filter.on(event, debounce(function (e) {
					e.preventDefault();

					var $self = $(this),
						term_id = $self.data("term"),
						$wrapper = $self.closest(".ha-post-tab"),
						content = $wrapper.find('.ha-post-tab-content'),
						loading = content.find('.ha-post-tab-loading'),
						tab_item = content.find('.ha-post-tab-item-wrapper'),
						$content_exist = false;

					if (0 === loading.length) {
						filter.removeClass('active');
						tab_item.removeClass('active');
						$self.addClass('active');

						tab_item.each(function () {
							var $self = $(this),
								$content_id = $self.data("term");
							if (term_id === $content_id) {
								$self.addClass('active');
								$content_exist = true;
							}
						});

						if (false === $content_exist) {
							$.ajax({
								url: HappyLocalize.ajax_url,
								type: 'POST',
								data: {
									action: "ha_post_tab_action",
									security: HappyLocalize.nonce,
									post_tab_query: args,
									term_id: term_id,
								},
								beforeSend: function () {
									content.append('<span class="ha-post-tab-loading"><i class="eicon-spinner eicon-animation-spin"></i></span>');
								},
								success: function (response) {
									content.find('.ha-post-tab-loading').remove();
									content.append(response);
								},
								error: function (error) {
								}
							});

						}
					}

				}, 200));
			}
		});

		var DataTable = function($scope) {
			var columnTH = $scope.find('.ha-table__head-column-cell');
			var rowTR = $scope.find('.ha-table__body-row');

			rowTR.each( function( i, tr) {
				var th = $(tr).find('.ha-table__body-row-cell');
				th.each( function( index, th ) {
					$(th).prepend( '<div class="ha-table__head-column-cell">' + columnTH.eq(index).html() + '</div>' );
				} );
			} );
		};

		//Threesixty Rotation
		var Threesixty_Rotation = function($scope) {
			var ha_circlr = $scope.find('.ha-threesixty-rotation-inner');
			var cls = ha_circlr.data('selector');
			var autoplay = ha_circlr.data('autoplay');
			var glass_on = $scope.find('.ha-threesixty-rotation-magnify');
			var t360 = $scope.find('.ha-threesixty-rotation-360img');
			var zoom = glass_on.data('zoom');
			//console.log(autoplay);
			var playb = $scope.find('.ha-threesixty-rotation-play');

			var crl = circlr(cls, {
				play : true,
				// vertical : true,
				// scroll : true,
				//interval : 340,
			});
			//console.log(crl);
			if( 'on' ===autoplay ){
				var autoplay_btn = $scope.find('.ha-threesixty-rotation-autoplay');
				autoplay_btn.on('click', function(el) {
					el.preventDefault();
					crl.play();
					t360.remove();
				});
				setTimeout(function(){
					autoplay_btn.trigger('click');
					autoplay_btn.remove();
				},1000);
			}else {
				playb.on('click', function(el) {
					el.preventDefault();
					var $self = $(this);
					var $i = $self.find('i');
					if($i.hasClass('hm-play-button')){
						$i.removeClass('hm-play-button');
						$i.addClass('hm-stop');
						crl.play();
					}else{
						$i.removeClass('hm-stop');
						$i.addClass('hm-play-button');
						crl.stop();
					}
					t360.remove();
				});
			}

			glass_on.on('click', function(el) {
				var img_block = $scope.find('img');
				img_block.each(function(){
					var style = $(this).attr('style');
					if( -1 !== style.indexOf("block") ){
						HappySimplaMagnify($(this)[0],zoom);
						glass_on.css('display','none');
						t360.remove();
					}
				});
			});

			$(document).on('click', function (e) {
				var t = $(e.target);
				var magnifier = $scope.find('.ha-img-magnifier-glass');
				var i = glass_on.find('i');
				if( magnifier.length && t[0] !== i[0] ){
					magnifier.remove();
					glass_on.removeAttr('style');
				}
				if( t[0] === ha_circlr[0] ){
					t360.remove();
				}
			});

			ha_circlr.on('mouseup mousedown', function (e) {
				t360.remove();
			});

		};

		$('[data-ha-element-link]').each(function() {
			var link = $(this).data('ha-element-link');
			$(this).on('click.haElementOnClick', function() {
				if (link.is_external) {
					window.open(link.url);
				} else {
					location.href = link.url;
				}
			})
		});

		var handlersFnMap = {
			'ha-image-compare.default': HandleImageCompare,
			'ha-justified-gallery.default': HandleJustifiedGallery,
			'ha-number.default': NumberHandler,
			'ha-skills.default': SkillHandler,
			'ha-fun-factor.default': FunFactor,
			'ha-bar-chart.default': BarChart,
			'ha-twitter-feed.default': TwitterFeed,
			'ha-threesixty-rotation.default': Threesixty_Rotation,
			'ha-data-table.default': DataTable
		};

		$.each( handlersFnMap, function( widgetName, handlerFn ) {
			EF.hooks.addAction( 'frontend/element_ready/' + widgetName, handlerFn );
		});

		var handlersClassMap = {
			'ha-slider.default': Slick,
			'ha-carousel.default': Slick,
			'ha-image-grid.default': Isotope,
			'ha-news-ticker.default': NewsTicker,
			'ha-post-tab.default': PostTab,
			'widget': ExtensionHandler
		};

		$.each( handlersClassMap, function( widgetName, handlerClass ) {
			EF.hooks.addAction( 'frontend/element_ready/' + widgetName, function( $scope ) {
				EF.elementsHandler.addHandler( handlerClass, { $element: $scope });
			});
		});
	});
} (jQuery, window));
