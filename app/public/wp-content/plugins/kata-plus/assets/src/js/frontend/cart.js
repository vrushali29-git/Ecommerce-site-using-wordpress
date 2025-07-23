(function ($) {
	/**
	 * @param $scope The Widget wrapper element as a jQuery element
	 * @param $ The jQuery alias
	 */
	var WidgetCartHandler = function ($scope, $) {
		$scope.find('.kata-cart-icon-wrap').on('click', function () {
			var $this = $(this);
			$this.next('.kata-plus-cart').fadeToggle();
			console.log($this.next('.kata-plus-cart'));
		});

		var a = 0,
			l = $('.kata-plus-cart').find('.mini_cart_item').length;
		if (l > 0) {
			$('.kata-plus-cart')
				.find('.mini_cart_item')
				.each(function cart_count(index, element) {
					var t = $(this).find('.quantity').text(),
						j = parseInt(t.split(' ', 1)[0]);
					a = a + j;
					$(this)
						.closest('.kata-plus-cart-wrap')
						.find('span.count')
						.text(a);
				});
		} else {
			$('.kata-plus-cart-wrap').find('span.count').text('0');
		}

		$(document).ajaxComplete(function () {
			var a = 0,
				l = $('.kata-plus-cart').find('.mini_cart_item').length;
			if (l > 0) {
				$('.kata-plus-cart')
					.find('.mini_cart_item')
					.each(function cart_count(index, element) {
						var t = $(this).find('.quantity').text(),
							j = parseInt(t.split(' ', 1)[0]);
						a = a + j;
						$(this)
							.closest('.kata-plus-cart-wrap')
							.find('span.count')
							.text(a);
					});
			} else {
				$('.kata-plus-cart-wrap').find('span.count').text('0');
			}
		});

		$('body').click(function (evt) {
			if ($(evt.target).closest('.kata-plus-cart-wrap').length) {
				return false;
			}
			$(document).find('.kata-plus-cart').fadeOut();
		});
	};

	// Make sure you run this code under Elementor.
	$(window).on('elementor/frontend/init', function () {
		elementorFrontend.hooks.addAction(
			'frontend/element_ready/kata-plus-cart.default',
			WidgetCartHandler
		);
	});
})(jQuery);
