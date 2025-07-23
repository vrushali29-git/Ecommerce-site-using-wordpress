/**
 * FullPageSlider Scripts.
 *
 * @author  ClimaxThemes
 * @package	Kata Plus
 * @since	1.0.0
 */
'use strict';

var Kata_Plus_FullPageSlider = (function ($) {
	return {
		/**
		 * Init.
		 *
		 * @since	1.0.0
		 */
		init: function () {
			this.FullPageSlider();
		},

		/**
		 * Full Page Slider.
		 *
		 * @since	1.0.0
		 */
		FullPageSlider: function () {
			var $fullpageslider = jQuery('.kata-full-page-slider'),
				selector = jQuery('[data-element_type="container"]').length
					? '[data-element_type="container"]'
					: '.elementor-top-section';
			if ($fullpageslider.length > 0) {
				var data = $fullpageslider.data();
				$fullpageslider.fullpage({
					licenseKey: '87258B1C-E97F4235-86313F6C-156D94A6',
					sectionSelector: selector,
					navigation: data.navigation,
					navigationPosition: data.navigationPosition,
					scrollingSpeed: data.scrollingSpeed,
					loopBottom: data.loopBottom,
					loopTop: data.loopTop,
					fixedElements:
						'.kata-header-wrap, .kata-footer, .kata-page-title',
					css3: true,
					scrollingSpeed: 700,
					autoScrolling: true,
					fitToSection: true,
					fitToSectionDelay: 1000,
					easing: 'easeInOutCubic',
					easingcss3: 'ease',
				});
			}
		},
	};
})(jQuery);

// Check Backend or Frontend
if (Kata_Plus_Scripts.getQueryStringValue('elementor-preview')) {
	// Elementor preview
	jQuery(window).on('elementor/frontend/init', function () {
		elementorFrontend.hooks.addAction(
			'frontend/element_ready/global',
			function ($scope) {
				Kata_Plus_FullPageSlider.init(true);
			}
		);
	});
} else {
	// Frontend
	jQuery(document).ready(function () {
		Kata_Plus_FullPageSlider.init(false);
	});
}
