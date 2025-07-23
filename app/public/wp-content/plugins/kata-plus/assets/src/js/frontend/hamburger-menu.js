(function ($) {
	/**
	 * @param $scope The Widget wrapper element as a jQuery element
	 * @param $ The jQuery alias
	 */
	var WidgetHamburgerMenuHandler = function ($scope, $) {
		$scope.find('.kata-plus-hamburger-menu').on('mousedown', function (e) {
			var $this = $(this),
				$text = $this.find('.icon-text'),
				$open = $this.find('.open-icon'),
				$close = $this.find('.close-icon'),
				$template = $this.find('.kata-hamburger-menu-template');

			if ($(e.target).closest('.kata-hamburger-menu-template').length) {
				if ($(e.target).hasClass('close-icon-text')) {
					closeHandler($this, $open, $close, $template);
				}

				if (!$(e.target).closest('.close-icon').length) return;
			}

			if (
				$(e.target).closest('.close-icon').length ||
				($(e.target).hasClass('icon-text') &&
					$this.hasClass('activated-hamburger-menu'))
			) {
				closeHandler($this, $open, $close, $template);
			} else {
				openHandler($this, $open, $close, $template);
			}

			function openHandler($this, $open, $close, $template) {
				$this.addClass('activated-hamburger-menu');
				$open.fadeOut();
				$close.fadeIn();
				$template.addClass('open-hamburger');
				if ($template.hasClass('kata-hamburger-full')) {
					$template.fadeIn();
				}
			}

			function closeHandler($this, $open, $close, $template) {
				$this.removeClass('activated-hamburger-menu');
				$open.fadeIn();
				$close.fadeOut();
				$template.removeClass('open-hamburger');
				if ($template.hasClass('kata-hamburger-full')) {
					$template.fadeOut();
				}
			}
		});
	};

	$(window).on('elementor/frontend/init', function () {
		elementorFrontend.hooks.addAction(
			'frontend/element_ready/kata-plus-hamburger-menu.default',
			WidgetHamburgerMenuHandler
		);
	});
})(jQuery);
