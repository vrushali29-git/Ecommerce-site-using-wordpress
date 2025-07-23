( function ( $ ) {
	/**
	 * @param $scope The Widget wrapper element as a jQuery element
	 * @param $ The jQuery alias
	 */
	var WidgetContentToggleHandler = function ( $scope, $ ) {
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
					$wrap
						.find( '.kata-plus-content-toggle-click' )
						.addClass( 'active' );
				} else {
					$content_wrap.fadeOut();
					$wrap
						.find( '.kata-plus-content-toggle-click' )
						.removeClass( 'active' );
				}
			} );

		$( '.kata-plus-content-toggle-close' ).on( 'click', function ( e ) {
			$( this )
				.closest( '.kata-plus-content-toggle' )
				.find( '.kata-plus-content-toggle-click' )
				.removeClass( 'active' )
				.siblings( '.kata-plus-content-toggle-content-wrap' )
				.fadeOut();
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
			'frontend/element_ready/kata-plus-content-toggle.default',
			WidgetContentToggleHandler
		);
	} );
} )( jQuery );
