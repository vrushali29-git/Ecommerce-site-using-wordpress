(function ($) {
	/**
	 * @param $scope The Widget wrapper element as a jQuery element
	 * @param $ The jQuery alias
	 */
	var WidgetLanguageSwitcherHandler = function ($scope, $) {
		// niceselect
		var dropdown = $('.kata-language-switcher').find('select');
		dropdown.niceSelect();

		$(dropdown).on('change', function (event) {
			location.href = event.currentTarget.value;
		});
	};

	// Make sure you run this code under Elementor.
	$(window).on('elementor/frontend/init', function () {
		elementorFrontend.hooks.addAction(
			'frontend/element_ready/kata-plus-language-switcher.default',
			WidgetLanguageSwitcherHandler
		);
	});
})(jQuery);
