/**
 * Editor Scripts.
 *
 * @package	Shop Press
 */
'use strict';

var ShopPressElementorEditor = ( function ( $ ) {
	return {
		/**
		 * Init.
		 *
		 * @since 1.0.0
		 */
		init: function () {
			this.UpdateProductView();
			this.UpdateProductViewAfterChangeLoadMoreOption();
			this.singleReviewTabInit();
		},

		/**
		 * Init review tab widget.
		 *
		 * @since 1.0.0
		 */
		singleReviewTabInit: function () {
			jQuery( '.wc-tabs-wrapper, .woocommerce-tabs, #rating' ).trigger(
				'init'
			);
		},

		/**
		 * Update Preview.
		 *
		 * @since 1.0.0
		 */
		UpdateProductView: function () {
			window.addEventListener( 'load', ( event ) => {
				elementor.reloadPreview();

				if ( typeof sp_slider_init === 'function' ) {
					sp_slider_init();
				}

				if ( typeof sp_product_slider_init === 'function' ) {
					sp_product_slider_init();
				}
			} );
		},

		/**
		 * Update Preview.
		 *
		 * @since 1.0.0
		 */
		UpdateProductViewAfterChangeLoadMoreOption: function () {
			elementor.hooks.addAction(
				'panel/open_editor/widget/sp-products',
				function ( panel, model, view ) {
					let $element = panel.$el.find(
						'[data-setting="load_more_button"]'
					);
					$element.off( 'change' ).on( 'change', function ( e ) {
						setTimeout( function () {
							view.renderOnChange();
						}, 1000 );
					} );
				}
			);
		},
	};
} )( jQuery );

// Frontend
jQuery( document ).ready( function () {
	ShopPressElementorEditor.init();
} );
