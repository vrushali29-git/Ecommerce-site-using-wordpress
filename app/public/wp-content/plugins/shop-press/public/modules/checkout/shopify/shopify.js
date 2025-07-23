jQuery(window).ready(function ($) {
	'use strict';

	$('.list-sp-nav-button li .button-next').on('click', function () {
		// $( 'body' ).trigger( 'update_checkout', { update_shipping_method: true } );
		let tab = $(this).data('tab');

		var $currentStepItem = $('.sp-step-item.active');
		var check_status = true;
		if ($currentStepItem.hasClass('sp-step-information')) {
			if (
				!$('#ship-to-different-address-checkbox', $currentStepItem).is(
					':checked'
				)
			) {
				$currentStepItem = $currentStepItem.find(
					'.woocommerce-billing-fields'
				);
			}
		}
		if (check_status) {
			$(
				' .validate-required input, .validate-required select, .validate-required textarea',
				$currentStepItem
			).each(function (i, input) {
				$(input).trigger('change');
			});

			if (
				$('.validate-required.woocommerce-invalid', $currentStepItem)
					.length
			) {
				jQuery([document.documentElement, document.body]).animate(
					{
						scrollTop: $('.validate-required.woocommerce-invalid')
							.first()
							.offset().top,
					},
					1000
				);

				return;
			}
		}

		if (tab != 4) {
			$('.sp-shopify-tabs-list li').removeClass('current');
			$('.list-sp-nav-button li').removeClass('active');
			$('.sp-shopify-content .sp-step-item').removeClass('active');
		}

		switch (tab) {
			case 2:
				$('.sp-shopify-tabs-list li.sp-shopify-shipping').addClass(
					'current'
				);
				$('.list-sp-nav-button li:nth-child(2)').addClass('active');
				$('.sp-shopify-content .sp-step-shipping').addClass('active');
				break;
			case 3:
				$('.sp-shopify-tabs-list li.sp-shopify-payment').addClass(
					'current'
				);
				$('.list-sp-nav-button li:nth-child(3)').addClass('active');
				$('.sp-shopify-content .sp-step-payment').addClass('active');
				break;
			case 4:
				$('#place_order').click();
				break;
		}
		var diff =
			$('.sp-shopify-tabs-wrapper').offset().top - $(window).scrollTop();
		var scroll_offset = 70;

		if (diff < -40) {
			$('html, body').animate(
				{
					scrollTop:
						$('.sp-shopify-tabs-wrapper').offset().top -
						scroll_offset,
				},
				800
			);
		}
	});

	$('.list-sp-nav-button li .button-return').on('click', function () {
		// $( 'body' ).trigger( 'update_checkout', { update_shipping_method: true } );
		let tab = $(this).data('tab');
		if (tab != 0) {
			$('.sp-shopify-tabs-list li').removeClass('current');
			$('.list-sp-nav-button li').removeClass('active');
			$('.sp-shopify-content .sp-step-item').removeClass('active');
		}
		switch (tab) {
			case 1:
				$('.sp-shopify-tabs-list li.sp-shopify-information').addClass(
					'current'
				);
				$('.list-sp-nav-button li:nth-child(1)').addClass('active');
				$('.sp-shopify-content .sp-step-information').addClass(
					'active'
				);
				break;
			case 2:
				$('.sp-shopify-tabs-list li.sp-shopify-shipping').addClass(
					'current'
				);
				$('.list-sp-nav-button li:nth-child(2)').addClass('active');
				$('.sp-shopify-content .sp-step-shipping').addClass('active');
				break;
			case 3:
				$('.sp-shopify-tabs-list li.sp-shopify-payment').addClass(
					'current'
				);
				$('.list-sp-nav-button li:nth-child(3)').addClass('active');
				$('.sp-shopify-content .sp-step-payment').addClass('active');
				break;
			case 0:
				window.location.replace(carUrl);
				break;
		}
		var diff =
			$('.sp-shopify-tabs-wrapper').offset().top - $(window).scrollTop();
		var scroll_offset = 70;

		if (diff < -40) {
			$('html, body').animate(
				{
					scrollTop:
						$('.sp-shopify-tabs-wrapper').offset().top -
						scroll_offset,
				},
				800
			);
		}
	});

	$('form.checkout').on(
		'input validate change',
		'.input-text, .sp-input-text, select, input:checkbox',
		function (e) {
			var $this = $(this),
				$parent = $this.closest('.sp-form-row'),
				validated = true,
				validate_required = $parent.is('.validate-required'),
				validate_email = $parent.is('.validate-email'),
				validate_phone = $parent.is('.validate-phone'),
				pattern = '',
				event_type = e.type;

			if ('input' === event_type) {
				$parent.removeClass(
					'woocommerce-invalid woocommerce-invalid-required-field woocommerce-invalid-email woocommerce-invalid-phone woocommerce-validated'
				); // eslint-disable-line max-len
			}

			if ('validate' === event_type || 'change' === event_type) {
				if (validate_required) {
					if (
						'checkbox' === $this.attr('type') &&
						!$this.is(':checked')
					) {
						$parent
							.removeClass('woocommerce-validated')
							.addClass(
								'woocommerce-invalid woocommerce-invalid-required-field'
							);
						validated = false;
					} else if ($this.val() === '') {
						$parent
							.removeClass('woocommerce-validated')
							.addClass(
								'woocommerce-invalid woocommerce-invalid-required-field'
							);
						validated = false;
					}
				}

				if (validate_email) {
					if ($this.val()) {
						pattern = new RegExp(
							/^([a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+(\.[a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+)*|"((([ \t]*\r\n)?[ \t]+)?([\x01-\x08\x0b\x0c\x0e-\x1f\x7f\x21\x23-\x5b\x5d-\x7e\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|\\[\x01-\x09\x0b\x0c\x0d-\x7f\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))*(([ \t]*\r\n)?[ \t]+)?")@(([a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.)+([a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[0-9a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.?$/i
						); // eslint-disable-line max-len

						if (!pattern.test($this.val())) {
							$parent
								.removeClass('woocommerce-validated')
								.addClass(
									'woocommerce-invalid woocommerce-invalid-email woocommerce-invalid-phone'
								); // eslint-disable-line max-len
							validated = false;
						}
					}
				}

				if (validate_phone) {
					pattern = new RegExp(/[\s\#0-9_\-\+\/\(\)\.]/g);

					if (0 < $this.val().replace(pattern, '').length) {
						$parent
							.removeClass('woocommerce-validated')
							.addClass(
								'woocommerce-invalid woocommerce-invalid-phone'
							);
						validated = false;
					}
				}

				if (validated) {
					$parent
						.removeClass(
							'woocommerce-invalid woocommerce-invalid-required-field woocommerce-invalid-email woocommerce-invalid-phone'
						)
						.addClass('woocommerce-validated'); // eslint-disable-line max-len
				}
			}
		}
	);

	$('input, textarea').on('input change', function (e) {
		var value = $(this).val();
		var placeholder =
			'undefined' !== typeof $(this).attr('placeholder')
				? $(this).attr('placeholder')
				: '';
		var id =
			'undefined' !== typeof $(this).attr('id') ? $(this).attr('id') : '';
		if (0 == id.length || placeholder.length) {
			return true;
		}

		var $p = $('#' + id + '_field');
		if (value.length > 0) {
			$p.removeClass('sp-label-placeholder');
		} else {
			$p.addClass('sp-label-placeholder');
		}
	});

	// const rowWarp = $( '.sp-input-text' );
	// rowWarp.on( 'click', function ( e ) {
	// 	const item = $( this );
	// 	const parent = item.closest( '.sp-form-row' );
	// 	const label = parent.find( 'label' );
	// 	label.addClass( 'sp-mv-label' );
	// } );
});
