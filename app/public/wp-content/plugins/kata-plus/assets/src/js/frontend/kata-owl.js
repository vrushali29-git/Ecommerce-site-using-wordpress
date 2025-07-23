(function ($) {
	/**
	 * @param $scope The Widget wrapper element as a jQuery element
	 * @param $ The jQuery alias
	 */
	var WidgetTestimonialsdler = function ($scope, $) {
		$('.kata-owl').each(function (index, element) {
			var $this = $(this);
			var data = $this.data();

			if (data['inc_owl_rtl'] == ' false') {
				data['inc_owl_rtl'] = false;
			}
			data['inc_thumbs'] = data['inc_thumbs']
				? data['inc_thumbs']
				: false;
			data['inc_thumbimage'] = data['inc_thumbimage']
				? data['inc_thumbimage']
				: false;

			$this.owlCarousel({
				smartSpeed: data['inc_owl_smspd'],
				dots: data['inc_owl_pag'],
				loop: data['inc_owl_loop'],
				rtl: data['inc_owl_rtl'],
				autoplay: data['inc_owl_autoplay'],
				center: data['inc_owl_center'],
				animateOut: data['animateout'],
				animateIn: data['animatein'],
				autoplayTimeout: data['inc_owl_spd'],
				autoplayHoverPause: data['inc_owl_autoplayhoverpause'],
				nav: data['inc_owl_arrow'],
				navText: [
					window.atob(data['inc_owl_prev']),
					window.atob(data['inc_owl_nxt']),
				],
				autoplayHoverPause: true,
				thumbs: data['inc_thumbs'],
				thumbImage: data['inc_thumbimage'],
				thumbContainerClass: data['inc_thumbContainerClass'],
				thumbItemClass: data['inc_thumbItemClass'],
				responsive: {
					200: {
						items: data['inc_owl_items'].inc_owl_item_mobile,
						stagePadding: data['inc_owl_stgpadmob'],
						margin: data['inc_owl_marginmob'],
					},
					481: {
						items: data['inc_owl_items'].inc_owl_item_mobile_extra,
						stagePadding: data['inc_owl_stgpadtab'],
						margin: data['inc_owl_margintab'],
					},
					769: {
						items: data['inc_owl_items'].inc_owl_item_tablet,
						stagePadding: data['inc_owl_stgpadtab'],
						margin: data['inc_owl_margintab'],
					},
					994: {
						items: data['inc_owl_items'].inc_owl_item_tablet_extra,
						stagePadding: data['inc_owl_stgpadtab'],
						margin: data['inc_owl_margintab'],
					},
					1025: {
						items: data['inc_owl_items'].inc_owl_item_laptop,
						stagePadding: 0,
						margin: data['inc_owl_margintab'],
					},
					1367: {
						items: data['inc_owl_items'].inc_owl_item,
						stagePadding: data['inc_owl_stgpad'],
						margin: data['inc_owl_margin'],
					},
				},
			});

			// Dots odd and even
			var $dots = $('.owl-dots').find('button');
			for (var i = 1; i <= $dots.length; i++) {
				if (i % 2 == 0) {
					$('.owl-dots')
						.find('button:nth-child(' + i + ')')
						.addClass('edd');
				} else {
					$('.owl-dots')
						.find('button:nth-child(' + i + ')')
						.addClass('even');
				}
			}

			// Progress bar
			if ($scope.find(this).hasClass('dots-and-num')) {
				var $progress = $('.dots-and-num').find(
						'.kata-owl-progress-bar'
					),
					items = $scope
						.find(this)
						.find('.owl-item')
						.not('.cloned').length,
					percentage = 100 / items;
				$('<span class="maxitems">' + items + '</span>').appendTo(
					$scope.find(this).next('.kata-owl-progress-bar')
				);
				$('<span class="minitems">1</span>').prependTo(
					$scope.find(this).next('.kata-owl-progress-bar')
				);
				$('.kata-progress-bar-inner').css('width', percentage);
				$scope
					.find(this)
					.on('translate.owl.carousel', function (event) {
						var $this = $scope.find(this),
							$items = $this.find('.owl-item.active'),
							progresswidth = $this
								.next('.kata-owl-progress-bar')
								.children('.kata-progress-bar-inner')
								.css('width'),
							progresswidth = parseInt(
								progresswidth.replace('px', '')
							),
							max = progresswidth + percentage;
						if (max >= 102) {
							$this
								.next('.kata-owl-progress-bar')
								.children('.kata-progress-bar-inner')
								.css('width', Math.round(percentage));
						} else {
							$this
								.next('.kata-owl-progress-bar')
								.children('.kata-progress-bar-inner')
								.css('width', Math.round(max));
						}
					});
			}

			// stairs carousel
			if ($('.kata-stairs-carousel').length) {
				$('.kata-stairs-carousel')
					.find('.owl-item.active.center')
					.prev()
					.addClass('prev')
					.siblings()
					.removeClass('prev');
				$('.kata-stairs-carousel')
					.find('.owl-item.active.center')
					.next()
					.addClass('next')
					.siblings()
					.removeClass('next');
				$scope
					.find(this)
					.on('translate.owl.carousel', function (event) {
						var $this = $(this);
						setTimeout(function () {
							$this
								.find('.owl-item.active.center')
								.prev()
								.addClass('prev')
								.siblings()
								.removeClass('prev');
							$this
								.find('.owl-item.active.center')
								.next()
								.addClass('next')
								.siblings()
								.removeClass('next');
						}, 1);
					});
			}

			/**
			 * Active Item
			 */
			if (
				$scope.find('.kata-owl').data('active-item') &&
				$scope.find('.kata-owl').data('active-item') !== 'none'
			) {
				checkClasses();
				$scope
					.find('.kata-owl')
					.on('translated.owl.carousel', function (event) {
						checkClasses();
					});
			}

			function checkClasses() {
				var total = $scope
						.find('.kata-owl')
						.find('.owl-stage .owl-item.active').length,
					active_item = $scope.find('.kata-owl').data('active-item');
				$scope
					.find('.kata-owl')
					.find('.owl-stage .owl-item')
					.removeClass('kata-owl-active-item');
				$scope
					.find('.kata-owl')
					.find('.owl-stage .owl-item.active')
					.each(function (index) {
						if (index === 0 && active_item == 'left') {
							$(this).addClass('kata-owl-active-item');
						}
						if (
							index === total - 1 &&
							total > 1 &&
							active_item == 'right'
						) {
							$(this).addClass('kata-owl-active-item');
						}
					});
			}
			if ($scope.find(this).hasClass('dots-counter')) {
				var $dots = $scope.find('.owl-dots'),
					$items_size = $scope.find('.owl-item:not(.cloned)').length,
					$star_num = 1;
				$dots.after(
					'<div class="dots-counter"><span class="counter">' +
						$star_num +
						'</span> - <span class="sum">' +
						$items_size +
						'</span> </div>'
				);
				$dots.hide();
				$scope
					.find('.kata-owl')
					.on('translate.owl.carousel', function (event) {
						$scope
							.find('.dots-counter')
							.find('.counter')
							.text(++event.page.index);
					});
			}

			// thumbnail size
			if ($this.length) {
				if ($this.attr('data-inc_thumbs') == 'true') {
					var items = $this.find('.owl-item').not('.cloned'),
						thumbs = $this.find('.owl-thumb-item');
					for (var i = 0; i <= items.length; i++) {
						$this
							.find(thumbs[i])
							.find('img')
							.attr(
								'src',
								$this
									.find(items[i])
									.find('img')
									.attr('data-thumb-size')
							);
					}
				}
			}
		});
	};

	// Make sure you run this code under Elementor.
	$(window).on('elementor/frontend/init', function () {
		// elementorFrontend.elementsHandler.attachHandler( 'kata-plus-testimonials', WidgetTestimonialsdler );
		elementorFrontend.hooks.addAction(
			'frontend/element_ready/kata-plus-testimonials.default',
			WidgetTestimonialsdler
		);
		elementorFrontend.hooks.addAction(
			'frontend/element_ready/kata-plus-content-slider.default',
			WidgetTestimonialsdler
		);
		elementorFrontend.hooks.addAction(
			'frontend/element_ready/kata-plus-brands.default',
			WidgetTestimonialsdler
		);
		elementorFrontend.hooks.addAction(
			'frontend/element_ready/kata-plus-instagram.default',
			WidgetTestimonialsdler
		);
		elementorFrontend.hooks.addAction(
			'frontend/element_ready/kata-plus-blog-posts.default',
			WidgetTestimonialsdler
		);
		elementorFrontend.hooks.addAction(
			'frontend/element_ready/kata-plus-related-posts.default',
			WidgetTestimonialsdler
		);
		elementorFrontend.hooks.addAction(
			'frontend/element_ready/kata-plus-image-carousel.default',
			WidgetTestimonialsdler
		);
		elementorFrontend.hooks.addAction(
			'frontend/element_ready/kata-plus-recipes.default',
			WidgetTestimonialsdler
		);
		elementorFrontend.hooks.addAction(
			'frontend/element_ready/kata-plus-task-process.default',
			WidgetTestimonialsdler
		);
		elementorFrontend.hooks.addAction(
			'frontend/element_ready/kata-plus-testimonials-vertical.default',
			WidgetTestimonialsdler
		);
		elementorFrontend.hooks.addAction(
			'frontend/element_ready/kata-plus-course-wrap.default',
			WidgetTestimonialsdler
		);
		elementorFrontend.hooks.addAction(
			'frontend/element_ready/kata-plus-blog-posts-new.default',
			WidgetTestimonialsdler
		);
		elementorFrontend.hooks.addAction(
			'frontend/element_ready/kata-plus-archive-posts.default',
			WidgetTestimonialsdler
		);
		elementorFrontend.hooks.addAction(
			'frontend/element_ready/kata-plus-search-page.default',
			WidgetTestimonialsdler
		);
		elementorFrontend.hooks.addAction(
			'frontend/element_ready/kata-plus-author-page.default',
			WidgetTestimonialsdler
		);
		elementorFrontend.hooks.addAction(
			'frontend/element_ready/kata-plus-video-carousel.default',
			WidgetTestimonialsdler
		);
	});
})(jQuery);
