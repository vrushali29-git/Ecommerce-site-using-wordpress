(function ($) {
	/**
	 * @param $scope The Widget wrapper element as a jQuery element
	 * @param $ The jQuery alias
	 */
	var WidgetPricingTableHandler = function ($scope, $) {
		$scope.find('.mode-switcher').on('click', function () {
			var $this = $(this),
				$wrap = $this.closest('.kata-plus-pricing-table'),
				$pric = $wrap.find('.pricing-table-price');

			$this.toggleClass('active');
			$pric.toggle();
		});
	};

	// Make sure you run this code under Elementor.
	$(window).on('elementor/frontend/init', function () {
		elementorFrontend.hooks.addAction(
			'frontend/element_ready/kata-plus-pricing-table.default',
			WidgetPricingTableHandler
		);
	});
})(jQuery);
