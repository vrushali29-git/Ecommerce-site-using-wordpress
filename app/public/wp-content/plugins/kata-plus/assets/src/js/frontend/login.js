( function ( $ ) {
	/**
	 * @param $scope The Widget wrapper element as a jQuery element
	 * @param $ The jQuery alias
	 */
	var WidgetLoginHandler = function ( $scope, $ ) {
		// Perform AJAX login on form submit

		$( '.kata-plus-live-login' ).on( 'submit', function ( e ) {
			var $status = $( '.kata-plus-live-login .status' );

			$status.removeClass( 'wrong' );
			$status.fadeIn( 'fast' );
			$status.addClass( 'loading' ).text( 'Please wait...' );

			if (
				$( this ).find( '.gglcptch' ).find( 'input[type="hidden"]' )
					.length
			) {
				var recaptcha_val = $( this )
					.find( '.gglcptch' )
					.find( 'input[type="hidden"]' )
					.val();
			} else {
				var recaptcha_val = false;
			}

			$.ajax( {
				url: kata_plus_localize.ajax.url,
				type: 'POST',
				dataType: 'json',
				data: {
					nonce: kata_plus_localize.ajax.nonce,
					action: 'live_login', //calls wp_ajax_nopriv_live_login
					username: $( this )
						.find( '.wrap-input.username [type="text"]' )
						.val(),
					password: $( this )
						.find( '.wrap-input.password [type="password"]' )
						.val(),
					'g-recaptcha-response': recaptcha_val,
				},
				success: function ( data ) {
					$status.removeClass( 'loading' ).empty();
					if ( data.status == true ) {
						$status
							.addClass( 'success' )
							.text(
								kata_plus_localize.translation.login.success
							);
						setTimeout(
							window.location.replace(
								$( '.kata-plus-live-login' ).attr( 'action' )
							),
							1000
						);
					} else if ( data.status == false ) {
						$status
							.addClass( 'wrong' )
							.text( kata_plus_localize.translation.login.fail );
					}
				},
			} );
			e.preventDefault();
		} );

		// Toggle
		$scope.find( '.kt-toggle-wrapper' ).on( 'click', function ( e ) {
			var viewportWidth = $( window ).width();

			if ( viewportWidth < 768 ) {
				return;
			}

			e.preventDefault();

			var $this = $( this ),
				$wrap = $this.closest( '.kt-login-open-as' ),
				$input = $wrap.find( '#user_login' ),
				$element_1 = $wrap.find( '.kata-login-wrap' ),
				$element_2 = $wrap.find( '.kt-login-overlay' ),
				$element_3 = $wrap.find( '.kt-close-overlay' ),
				$close = $wrap.find( '.kt-close-overlay' );

			if ( $wrap.hasClass( 'active' ) ) {
				$wrap.removeClass( 'active' );
				$element_1.fadeOut();
				$element_2.fadeOut();
				$element_3.fadeOut();
			} else {
				$wrap.addClass( 'active' );
				$element_1.fadeIn();
				$element_2.fadeIn();
				$element_3.fadeIn();
			}
			$input.focus();
		} );

		$( document ).keydown( function ( e ) {
			var code = e.keyCode || e.which;
			if ( code == 27 ) {
				$( '.kata-plus-content-toggle-content-wrap' ).each(
					function ( index, element ) {
						if ( $( this ).css( 'display' ) == 'block' ) {
							$( this ).fadeOut();
						}
					}
				);
			}
		} );
		$scope
			.find( '.kata-plus-content-toggle-click' )
			.on( 'click', function ( e ) {
				var viewportWidth = $( window ).width();

				if ( viewportWidth < 768 ) {
					return;
				}

				e.preventDefault();
				var $this = $( this ),
					$wrap = $this.closest( '.kata-plus-content-toggle' ),
					$content_wrap = $wrap.find(
						'.kata-plus-content-toggle-content-wrap'
					);
				$( '.kata-plus-content-toggle-content-wrap' ).each(
					function ( index, element ) {
						if ( $( this ).css( 'display' ) == 'block' ) {
							$( this ).fadeOut();
						}
					}
				);
				if ( $content_wrap.css( 'display' ) == 'none' ) {
					$content_wrap.fadeIn();
				} else {
					$content_wrap.fadeOut();
				}
			} );

		// Modal
		$( document ).on(
			'click',
			'.kt-login-open-as .kt-close-login-modal',
			function ( e ) {
				var viewportWidth = $( window ).width();

				if ( viewportWidth < 768 ) {
					return;
				}
				e.preventDefault();
				var $this = $( this ),
					$wrap = $this.closest( '.kt-login-open-as' ),
					$element_1 = $wrap.find( '.kata-login-wrap' ),
					$element_2 = $wrap.find( '.kt-login-overlay' ),
					$element_3 = $wrap.find( '.kt-close-overlay' );
				if ( $wrap.hasClass( 'active' ) ) {
					$wrap.removeClass( 'active' );
					$element_1.fadeOut();
					$element_2.fadeOut();
					$element_3.fadeOut();
				}
			}
		);
		$( document ).keydown( function ( e ) {
			var code = e.keyCode || e.which;
			if ( code == 27 ) $( '.kt-close-login-modal' ).trigger( 'click' );
		} );
		$( window ).on( 'click', function ( e ) {
			if (
				'kt-login-wrapper' == e.target.className ||
				'kt-login-overlay' == e.target.className
			) {
				if ( $( '.kt-login-open-as.modal' ).hasClass( 'active' ) ) {
					$( '.kt-close-login-modal' ).trigger( 'click' );
				}
			}
		} );

		$( document ).click( function ( evt ) {
			if (
				$( evt.target ).closest( '.kata-plus-content-toggle' )
					.length === 0
			) {
				$( document )
					.find( '.kata-plus-content-toggle-content-wrap' )
					.fadeOut();
			}
		} );
	};
	// Make sure you run this code under Elementor.
	$( window ).on( 'elementor/frontend/init', function () {
		elementorFrontend.hooks.addAction(
			'frontend/element_ready/kata-plus-login.default',
			WidgetLoginHandler
		);
	} );
} )( jQuery );
