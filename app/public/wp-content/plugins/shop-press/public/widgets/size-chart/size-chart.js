( function ( $ ) {
	/**
	 * @param $scope The Widget wrapper element as a jQuery element
	 * @param $ The jQuery alias
	 */
	var WidgetChartSizeHandler = function ( $scope, $ ) {
		/**
		 * Show Modal
		 *
		 * @Package @ChartSize
		 * @since 1.0.0
		 */
		$scope.find( '.sp-size-chart' ).on( 'click', function () {
			$scope.find( '.size-chart-overlay' ).fadeIn();
		} );

		/**
		 * Hide Modal
		 *
		 * @Package @ChartSize
		 * @since 1.0.0
		 */
		$( document ).on(
			'click',
			'.size-chart-close, .size-chart-overlay',
			function ( e ) {
				if (
					! $( e.target ).hasClass( 'size-chart-close' ) &&
					$( e.target ).closest( '.size-chart-content' ).length > 0
				) {
					return;
				}
				$scope.find( '.size-chart-overlay' ).fadeOut();
			}
		);

		/**
		 * Chart Size Template
		 *
		 * @Package @ChartSize
		 * @since 1.0.0
		 */
		$scope.on( 'click', '.sp-size-chart', function () {
			$( '.size-chart-content' ).addClass( 'loaded' );
		} );
	};

	// Make sure you run this code under Elementor.
	$( window ).on( 'elementor/frontend/init', function () {
		elementorFrontend.hooks.addAction(
			'frontend/element_ready/sp-size-chart.default',
			WidgetChartSizeHandler
		);
	} );

	WidgetChartSizeHandler( $( '.sp-size-chart' ).parent(), $ );
} )( jQuery );
