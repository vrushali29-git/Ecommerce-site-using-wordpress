(function ($) {
	/**
	 * @param $scope The Widget wrapper element as a jQuery element
	 * @param $ The jQuery alias
	 */

	var WidgetMenuNavigationHandler = function ($scope, $) {
		if ($.fn.superfish) {
			$('.kata-menu-navigation').superfish({
				delay: 500, // one second delay on mouseout
				animation: {
					opacity: 'show',
					height: 'show',
				}, // fade-in and slide-down animation
				speed: 'normal', // faster animation speed
				speedOut: 'normal',
				autoArrows: false, // disable generation of arrow mark-up
			});
			$('.kata-menu-navigation')
				.find('.menu-item-has-children')
				.on('mouseover', function () {
					var style_attr = $(this).find('.sub-menu').attr('style');
					if (typeof style_attr == 'undefined' || style_attr === '') {
						$(this).find('.sub-menu').css('display', 'block');
					}
				});
		}
		// mega menu
		if ($('.menu-item-object-kata_mega_menu').length > 0) {
			$('.menu-item-object-kata_mega_menu').each(function (
				index,
				element
			) {
				var $this = $(this);
				$this.closest('.kata-header-wrap').addClass('have-mega-menu');
				$this
					.closest('.kata-sticky-header-wrap')
					.addClass('have-mega-menu');
			});
		}
		// vertical menu
		if ($scope.find('.kata-menu-vertical').length > 0) {
			$scope
				.find('.kata-menu-vertical')
				.find('.menu-item-has-children')
				.find('.parent-menu-icon')
				.on('click', function (e) {
					e.preventDefault();
					var $this = $(this),
						$wrap = $this.closest('.menu-item-has-children');

					$wrap.toggleClass('kt-active-menu');
					$this.toggleClass('submenu-close-icon');

					var $submenu = $wrap.children('.sub-menu');

					if ($wrap.hasClass('kt-active-menu')) {
						$submenu.slideDown();
					} else {
						$submenu.slideUp();
					}
				});
		}
		// responsive close
		$('.kata-responsive-menu-wrap')
			.find('.cm-ham-close-icon')
			.on('click', function () {
				var $this = $(this),
					$wrap = $this.closest('.kata-responsive-menu-wrap');
				if ($wrap.css('left') == '0px') {
					$wrap.css('left', '-110%');
				}
			});
		// responsive open
		$('.kata-menu-wrap')
			.find('.cm-ham-open-icon')
			.on('click', function () {
				var $this = $(this),
					$wrap = $this.closest('.kata-menu-wrap'),
					$menu = $wrap.find('.kata-responsive-menu-wrap');
				if ($menu.css('left') < '0') {
					$menu.css('left', '0');
				}
			});
	};

	// Make sure you run this code under Elementor.
	$(window).on('elementor/frontend/init', function () {
		elementorFrontend.hooks.addAction(
			'frontend/element_ready/kata-plus-menu-navigation.default',
			WidgetMenuNavigationHandler
		);
	});
})(jQuery);
