function shoppress_header_toggle_init( $ ) {
	$( document ).keydown( function ( e ) {
		var code = e.keyCode || e.which;
		if ( code == 27 ) {
			$( '.sp-header-toggle-content-wrap' ).each(
				function ( index, element ) {
					if ( $( element ).css( 'display' ) == 'block' ) {
						$( element ).fadeOut();
					}
				}
			);
		}
	} );

	function sp_close_all_header_toggles() {
		$( '.sp-header-toggle-content-wrap' ).each(
			function ( index, element ) {
				if ( $( element ).css( 'display' ) == 'block' ) {
					$( element ).fadeOut();
					$( element )
						.closest( '.sp-header-toggle' )
						.removeClass( 'active' );
				}
			}
		);
	}

	$( document ).on( 'click', '.sp-header-toggle-click', function ( e ) {
		e.preventDefault();
		var $this = $( this ),
			$wrap = $this.closest( '.sp-header-toggle' ),
			$content_wrap = $wrap.find( '.sp-header-toggle-content-wrap' );

		sp_close_all_header_toggles();

		if ( $content_wrap.css( 'display' ) == 'none' ) {
			$content_wrap.fadeIn();
			$wrap.find( '.sp-header-toggle-click' ).addClass( 'active' );
		} else {
			$content_wrap.fadeOut();
			$wrap.find( '.sp-header-toggle-click' ).removeClass( 'active' );
		}
	} );

	$( document ).on( 'click', '.sp-header-toggle-close', function ( e ) {
		$( this )
			.closest( '.sp-header-toggle' )
			.find( '.sp-header-toggle-click' )
			.removeClass( 'active' )
			.siblings( '.sp-header-toggle-content-wrap' )
			.fadeOut();
	} );

	$( document ).on( 'click', function ( e ) {
		var is_header_toggle =
			$( e.target ).hasClass( 'sp-header-toggle-click' ) ||
			$( e.target ).closest( '.sp-header-toggle' ).length
				? true
				: false;
		if (
			! is_header_toggle &&
			0 ==
				$( e.target )
					.closest( '.sp-header-toggle' )
					.find( '.sp-header-toggle-click.active' ).length
		) {
			sp_close_all_header_toggles();
		}
	} );
}
( function ( $ ) {
	shoppress_header_toggle_init( $ );
} )( jQuery );
