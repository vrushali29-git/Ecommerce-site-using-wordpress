window.addEventListener( 'elementor/init', () => {
	const SPWooProductSelectControl =
		elementor.modules.controls.BaseData.extend( {
			onReady() {
				this.initializeSelect2();
				this.setupRepeaterListener();
			},

			initializeSelect2() {
				const self = this;
				const select2Options = {
					ajax: {
						url: SPWooProductSelect.restUrl,
						dataType: 'json',
						delay: 250,
						data: function ( params ) {
							return {
								search: params.term,
								per_page: 20,
								_fields: 'id,name',
								_wpnonce: SPWooProductSelect.restNonce,
							};
						},
						processResults: function ( data ) {
							return {
								results: data.map( function ( product ) {
									return {
										id: product.id,
										text: product.name,
									};
								} ),
							};
						},
						cache: true,
					},
					minimumInputLength: 2,
					multiple: false,
					placeholder: 'Search for products',
					allowClear: true,
				};

				this.$el
					.find( '.sp-woo-product-select' )
					.select2( select2Options )
					.on( 'change', () => self.saveValue() );

				this.restoreValues();
			},

			setupRepeaterListener() {
				if ( this.container.repeater ) {
					this.container.repeater.on( 'add', () => {
						setTimeout( () => this.initializeSelect2(), 10 );
					} );
				}
			},

			saveValue() {
				const selectedValues = this.$el
					.find( '.sp-woo-product-select' )
					.val();
				this.setValue( selectedValues );
			},

			restoreValues() {
				let savedValues = this.getControlValue();

				if ( ! Array.isArray( savedValues ) ) {
					if ( savedValues === null || savedValues === undefined ) {
						savedValues = [];
					} else if (
						typeof savedValues === 'string' ||
						typeof savedValues === 'number'
					) {
						savedValues = [ savedValues ];
					} else {
						return;
					}
				}

				if ( savedValues.length > 0 ) {
					const $select = this.$el.find( '.sp-woo-product-select' );

					jQuery.ajax( {
						url: SPWooProductSelect.restUrl,
						data: {
							include: savedValues.join( ',' ),
							per_page: 100,
							_fields: 'id,name',
							_wpnonce: SPWooProductSelect.restNonce,
						},
						success: function ( products ) {
							products.forEach( function ( product ) {
								const option = new Option(
									product.name,
									product.id,
									true,
									true
								);
								$select.append( option );
							} );
							$select.trigger( 'change' );
						},
					} );
				}
			},

			onBeforeDestroy() {
				this.saveValue();
				this.$el.find( '.sp-woo-product-select' ).select2( 'destroy' );
			},
		} );

	elementor.addControlView(
		'shoppress_product_select',
		SPWooProductSelectControl
	);
} );
