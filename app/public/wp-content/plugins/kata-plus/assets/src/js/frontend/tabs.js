(function ($) {
	/**
	 * @param $scope The Widget wrapper element as a jQuery element
	 * @param $ The jQuery alias
	 */
	var WidgetTabsHandler = function ($scope, $) {
		// Bind the click event handler
		$(document).on('click', '.kata-plus-tab-item', function (e) {
			// Make the old tab inactive.
			var $this = $(this),
				$wrap = $this.closest('.kata-plus-tabs'),
				hash = $this.attr('data-id');

			// Tabs and contents
			$this.addClass('active').siblings().removeClass('active');
			$wrap
				.find('.kata-plus-tabs-content[id="' + hash + '"]')
				.show()
				.siblings()
				.hide();
			$wrap
				.find('.kata-plus-tabs-content[id="' + hash + '"]')
				.addClass('active')
				.siblings()
				.removeClass('active');

			// Prevent the anchor's default click action
			e.preventDefault();
		});
	};

	// Make sure you run this code under Elementor.
	$(window).on('elementor/frontend/init', function () {
		elementorFrontend.hooks.addAction(
			'frontend/element_ready/kata-plus-tabs.default',
			WidgetTabsHandler
		);
	});
})(jQuery);
