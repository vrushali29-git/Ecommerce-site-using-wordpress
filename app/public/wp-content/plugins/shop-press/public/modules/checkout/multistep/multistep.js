jQuery( window ).ready( function ( $ ) {
	'use strict';

	var shoppress_checkout = {
		$tabs: $( '.sp-tab-item' ),
		$sections: $( '.sp-step-item' ),
		$buttons: $( '.sp-nav-button' ),
		$checkout_form: $( 'form.woocommerce-checkout' ),
		$coupon_form: $( '#checkout_coupon' ),
		$before_form: $( '#woocommerce_before_checkout_form' ),
		current_step: $( 'ul.sp-tabs-list' ).data( 'current-title' ),

		init: function () {
			var self = this;

			$( '.woocommerce-checkout' ).on(
				'shoppress_switch_tab',
				function ( event, index ) {
					self.switch_tab( index );
				}
			);

			$( '.sp-step-item:first' ).addClass( 'current' );

			$( '#sp-next' ).on( 'click', function () {
				var $currentStepItem = $( '.sp-step-item.current' );
				var check_status = true;
				if (
					$currentStepItem.hasClass( 'sp-step-shipping' ) ||
					$currentStepItem.hasClass( 'sp-step-billing-and-shipping' )
				) {
					if (
						! $(
							'#ship-to-different-address-checkbox',
							$currentStepItem
						).is( ':checked' )
					) {
						$currentStepItem = $currentStepItem.find(
							'.woocommerce-billing-fields'
						);
					}
				}

				if ( check_status ) {
					$(
						' .validate-required input, .validate-required select, .validate-required textarea',
						$currentStepItem
					).each( function ( i, input ) {
						$( input ).trigger( 'change' );
					} );

					if (
						$(
							'.validate-required.woocommerce-invalid',
							$currentStepItem
						).length
					) {
						jQuery( [
							document.documentElement,
							document.body,
						] ).animate(
							{
								scrollTop: $(
									'.validate-required.woocommerce-invalid'
								)
									.first()
									.offset().top,
							},
							1000
						);

						return;
					}
				}

				self.switch_tab( self.current_index() + 1 );
			} );

			$( '#sp-prev' ).on( 'click', function () {
				self.switch_tab( self.current_index() - 1 );
			} );

			$( document ).on( 'checkout_error', function () {
				if ( ! $( '#createaccount' ).is( ':checked' ) ) {
					$(
						'#account_password_field, #account_username_field'
					).removeClass( 'woocommerce-invalid-required-field' );
				}

				if (
					! $( '#ship-to-different-address-checkbox' ).is(
						':checked'
					)
				) {
					$(
						'.woocommerce-shipping-fields__field-wrapper p'
					).removeClass( 'woocommerce-invalid-required-field' );
				}

				var section_class = $( '.woocommerce-invalid-required-field' )
					.closest( '.sp-step-item' )
					.attr( 'class' );

				$( '.sp-step-item' ).each( function ( i ) {
					if ( $( this ).attr( 'class' ) === section_class ) {
						self.switch_tab( i );
					}
				} );
			} );

			$( '.woocommerce-checkout' ).on( 'keydown', function ( e ) {
				if ( e.which === 13 ) {
					e.preventDefault();
					return false;
				}
			} );

			if (
				typeof window.location.hash != 'undefined' &&
				window.location.hash
			) {
				changeTabOnHash( window.location.hash );
			}

			$( window ).on( 'hashchange', function () {
				changeTabOnHash( window.location.hash );
			} );

			function changeTabOnHash( hash ) {
				if ( /step-[0-9]/.test( hash ) ) {
					var step = hash.match( /step-([0-9])/ )[ 1 ];
					self.switch_tab( step );
				}
			}
		},
		current_index: function () {
			return this.$sections.index( this.$sections.filter( '.current' ) );
		},
		scroll_top: function () {
			if ( $( '.sp-tabs-wrapper' ).length === 0 ) {
				return;
			}

			var diff =
				$( '.sp-tabs-wrapper' ).offset().top - $( window ).scrollTop();
			var scroll_offset = 70;

			if ( diff < -40 ) {
				$( 'html, body' ).animate(
					{
						scrollTop:
							$( '.sp-tabs-wrapper' ).offset().top -
							scroll_offset,
					},
					800
				);
			}
		},
		switch_tab: function ( index ) {
			var self = this;

			if ( index < 0 || index > this.$sections.length - 1 ) {
				return false;
			}

			this.scroll_top();

			$( 'html, body' )
				.promise()
				.done( function () {
					self.$tabs
						.removeClass( 'previous' )
						.filter( '.current' )
						.addClass( 'previous' );
					self.$sections
						.removeClass( 'previous' )
						.filter( '.current' )
						.addClass( 'previous' );
					$(
						'.woocommerce-NoticeGroup-checkout:not(sp-error)'
					).show();

					self.$tabs.removeClass( 'current' );
					self.$tabs.eq( index ).addClass( 'current' );
					self.current_step = self.$tabs
						.eq( index )
						.data( 'step-title' );
					$( '.sp-tabs-list' ).data(
						'current-title',
						self.current_step
					);

					self.$sections.removeClass( 'current' );
					self.$sections.eq( index ).addClass( 'current' );

					self.$buttons.removeClass( 'current' );
					self.$coupon_form.hide();
					self.$before_form.hide();

					if ( index < self.$sections.length - 1 ) {
						$( '#sp-next' ).addClass( 'current' );
					}

					if (
						typeof $( '.woocommerce-NoticeGroup-checkout' ).data(
							'for-step'
						) !== 'undefined' &&
						$( '.woocommerce-NoticeGroup-checkout' ).data(
							'for-step'
						) !== self.current_step
					) {
						$( '.woocommerce-NoticeGroup-checkout' ).remove();
					}

					if ( index === 0 && $( '.sp-step-login' ).length > 0 ) {
						$( '#sp-next' ).removeClass( 'current' );
						$(
							'.woocommerce-NoticeGroup-checkout:not(sp-error)'
						).hide();
					}

					if ( index === self.$sections.length - 1 ) {
						$( '#sp-prev' ).addClass( 'current' );
						$( '#sp-submit' ).addClass( 'current' );
						self.$checkout_form
							.removeClass( 'processing' )
							.unblock();
					}

					if ( index != 0 ) {
						$( '#sp-prev' ).addClass( 'current' );
					} else {
						$( '#sp-return-to-shop' ).addClass( 'current' );
					}

					if ( $( '.sp-step-review.current' ).length > 0 ) {
						self.$coupon_form.show();
					}

					if (
						$(
							'.sp-' +
								self.$before_form.data( 'step' ) +
								'.current'
						).length > 0
					) {
						self.$before_form.show();
					}
				} );
		},
	};
	shoppress_checkout.init();
} );
