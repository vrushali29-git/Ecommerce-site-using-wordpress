/**
 * Admin JS
 *
 * @author  ClimaxThemes
 * @package	Kata Plus
 * @since	1.0.0
 */
'use strict';

var Kata_Plus_Admin = (function ($) {
	/**
	 * Global variables.
	 *
	 * @since	1.0.0
	 */
	var loaded = false;
	var _this = false;

	return {
		/**
		 * Init.
		 *
		 * @since	1.0.0
		 */
		init: function () {
			if (loaded === false) {
				_this = this;
				this.load();
				loaded = true;
			}
		},
		load: function () {
			if (!loaded) {
				// Licence Form in Theme Activation Page
				if (jQuery('#kata-license').length) {
					_this.handle_licence_form();
				}
			}
		},
		alert: function (config) {
			Swal.fire(config);
		},
		handle_licence_form: function () {
			jQuery('#kata-license').on('submit', function () {
				var form = jQuery(this);
				var ack = jQuery(this).find('.kata-form-control').val();
				if (!ack) {
					jQuery('.ti-close').show();
					_this.alert({
						icon: 'warning',
						text: kata_plus_admin_localize.translate.activation
							.empty_input,
						confirmButtonText:
							kata_plus_admin_localize.translate.activation.back,
					});
					return false;
				}
				form.find('input[type="submit"]').addClass('loading');
				jQuery.ajax({
					type: 'post',
					url: kata_plus_admin_localize.ajax.url,
					data: {
						action: 'kata_plus_activation',
						code: ack,
					},
					dataType: 'json',
					success: function (response) {
						console.log(response);
						jQuery('.ti-close').attr('class', 'ti-check');
						form.find('input[type="submit"]').removeClass(
							'loading'
						);
						_this.alert({
							icon: 'success',
							text: response.message,
							confirmButtonText:
								kata_plus_admin_localize.translate.activation
									.well,
						});

						setTimeout(() => {
							location.reload();
						}, 2000);
					},
					error: function (response) {
						jQuery('.ti-check').attr('class', 'ti-close');
						jQuery('.ti-close').show();
						jQuery('#activation-successfully').css(
							'display',
							'none'
						);
						form.find('input[type="submit"]').removeClass(
							'loading'
						);
						_this.alert({
							icon: 'error',
							text: response.responseJSON.message,
							confirmButtonText:
								kata_plus_admin_localize.translate.activation
									.well,
							footer: response.responseJSON.footer,
						});
					},
				});
				return false;
			});
		},
	};
})(jQuery);

jQuery(document).on('ready', function () {
	Kata_Plus_Admin.init();
});
