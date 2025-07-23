/**
 * Theme Scripts.
 *
 * @author  ClimaxThemes
 * @package	Kata Plus
 * @since	1.0.0
 */
'use strict';

var Kata_Plus_Scripts = (function ($) {
	/**
	 * Global variables.
	 *
	 * @since	1.0.0
	 */
	var $window = jQuery(window);
	var runOnceTime = false;
	var onetime = false;

	return {
		/**
		 * Init.
		 *
		 * @since	1.0.0
		 */
		init: function (elementorPreview) {
			if (onetime === false) {
				this.PostMeta();
				onetime = true;
			}
			if (runOnceTime == false) {
				runOnceTime = true;
				this.backToTop();
				this.Animations();
			}
		},

		/**
		 * Svg Fixer.
		 *
		 * @since	1.0.0
		 */
		Animations: function () {
			jQuery(
				'.elementor-widget-container *:not(.kata-onscreen-animloaded)'
			).each(function (index, element) {
				var $this = jQuery(this);
				if (
					$this.css('animation-name') !== 'none' ||
					$this.css('animation-duration') !== '0s'
				) {
					if ($this.css('--triggeranimation') == 'pageloaded') {
						if (!$this.hasClass('kata-onscreen-animloaded')) {
							jQuery(this).addClass('kata-onscreen-animloaded');
						}
					}
					if ($this.css('--triggeranimation')) {
						$this
							.closest('.elementor-widget-container')
							.addClass('kata-anim');
					}
				}
			});
			jQuery(window).on('scroll', function () {
				jQuery(
					'[class*="elementor-widget-kata"] *:not(.kata-onscreen-animloaded)'
				).each(function (index, element) {
					const $this = jQuery(this);
					if (
						$this.css('animation-name') !== 'none' ||
						$this.css('animation-duration') !== '0s'
					) {
						if ($this.css('--triggeranimation') == 'onscreen') {
							if (!$this.hasClass('kata-onscreen-animloaded')) {
								var offset = jQuery(this).offset(),
									top = Math.round(offset.top - 500);
								if (jQuery(window).scrollTop() >= top) {
									jQuery(this).addClass(
										'kata-onscreen-animloaded'
									);
								}
							}
						}
					}
				});
			});
		},

		/**
		 * postmeta fixer.
		 *
		 * @since	1.0.0
		 */
		PostMeta: function () {
			jQuery('.kata-post-metadata').each(function (index, element) {
				if (jQuery(this).html() == '') {
					jQuery(this).remove();
				}
			});
		},

		/**
		 * Get Query String Value.
		 *
		 * @since	1.0.0
		 */
		getQueryStringValue: function (key) {
			return decodeURIComponent(
				window.location.search.replace(
					new RegExp(
						'^(?:.*[&\\?]' +
							encodeURIComponent(key).replace(
								/[\.\+\*]/g,
								'\\$&'
							) +
							'(?:\\=([^&]*))?)?.*$',
						'i'
					),
					'$1'
				)
			);
		},

		/**
		 * Back to top button.
		 *
		 * @since	1.0.0
		 */
		backToTop: function () {
			jQuery(window).on('scroll', function () {
				100 < jQuery(this).scrollTop()
					? jQuery('.scrollup').fadeIn()
					: jQuery('.scrollup').fadeOut();
			});
			jQuery('.scrollup').on('click', function () {
				return (
					jQuery('html, body').animate(
						{
							scrollTop: 0,
						},
						700
					),
					!1
				);
			});
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
				Kata_Plus_Scripts.init(true);
			}
		);
	});
} else {
	// Frontend
	jQuery(document).ready(function () {
		Kata_Plus_Scripts.init(false);
	});
}
