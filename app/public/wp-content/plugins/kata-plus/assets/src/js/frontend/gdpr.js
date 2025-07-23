/**
 * GDPR Scripts.
 *
 * @author  ClimaxThemes
 * @package	Kata Plus
 * @since	1.0.0
 */
'use strict';

var Kata_Plus_GDPR = (function ($) {
	return {
		/**
		 * Init.
		 *
		 * @since	1.0.0
		 */
		init: function () {
			this.Gdpr();
		},

		/**
		 * Gdpr.
		 *
		 * @since	1.0.0
		 */
		Gdpr: function () {
			jQuery('.kata-gdpr-box')
				.find('.gdpr-button-agree')
				.find('button')
				.on('click', function (e) {
					jQuery('.kata-gdpr-box').addClass('hide');
					e.preventDefault();
					$.ajax({
						url: kata_plus_localize.ajax.url,
						type: 'POST',
						data: {
							nonce: kata_plus_localize.ajax.nonce,
							action: 'set_cookie',
							gdprcookie: 'true',
						},
					});
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
				Kata_Plus_GDPR.init(true);
			}
		);
	});
} else {
	// Frontend
	jQuery(document).ready(function () {
		Kata_Plus_GDPR.init(false);
	});
}
