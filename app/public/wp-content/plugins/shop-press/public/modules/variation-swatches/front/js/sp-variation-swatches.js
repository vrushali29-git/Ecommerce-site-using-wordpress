var sp_vs_public_base = ( function ( $, window, document ) {
	'use strict';

	function isEmpty( val ) {
		return val === undefined || val == null || val.length <= 0
			? true
			: false;
	}

	function display_char_count( elm, isCount ) {
		var fid = elm.prop( 'id' );
		var len = elm.val().length;
		var displayElm = $( '#' + fid + '-char-count' );

		if ( isCount ) {
			displayElm.text( '(' + len + ' characters)' );
		} else {
			var maxLen = elm.prop( 'maxlength' );
			var left = maxLen - len;
			displayElm.text( '(' + left + ' characters left)' );
			if ( rem < 0 ) {
				displayElm.css( 'color', 'red' );
			}
		}
	}

	function set_field_value_by_elm( elm, type, value ) {
		switch ( type ) {
			case 'radio':
				elm.val( [ value ] );
				break;
			case 'checkbox':
				if ( elm.data( 'multiple' ) == 1 ) {
					value = value ? value : [];
					elm.val( [ value ] );
				} else {
					elm.val( [ value ] );
				}
				break;
			case 'select':
				if ( elm.prop( 'multiple' ) ) {
					elm.val( value );
				} else {
					elm.val( [ value ] );
				}
				break;
			case 'country':
				elm.val( [ value ] ).change();
				break;
			case 'state':
				elm.val( [ value ] ).change();
				break;
			case 'multiselect':
				if ( elm.prop( 'multiple' ) ) {
					if ( typeof value != 'undefined' ) {
						elm.val( value.split( ',' ) ).change();
					}
				} else {
					elm.val( [ value ] );
				}
				break;
			default:
				elm.val( value );
				break;
		}
	}

	function get_field_value( type, elm, name ) {
		var value = '';
		switch ( type ) {
			case 'radio':
				value = $(
					'input[type=radio][name=' + name + ']:checked'
				).val();
				break;
			case 'checkbox':
				if ( elm.data( 'multiple' ) == 1 ) {
					var valueArr = [];
					$(
						"input[type=checkbox][name='" + name + "[]']:checked"
					).each( function () {
						valueArr.push( $( this ).val() );
					} );
					value = valueArr; // .toString();
				} else {
					value = $(
						'input[type=checkbox][name=' + name + ']:checked'
					).val();
				}
				break;
			case 'select':
				value = elm.val();
				break;
			case 'multiselect':
				value = elm.val();
				break;
			default:
				value = elm.val();
				break;
		}
		return value;
	}

	return {
		display_char_count: display_char_count,
		set_field_value_by_elm: set_field_value_by_elm,
		get_field_value: get_field_value,
	};
} )( window.jQuery, window, document );

var sp_vs_public = ( function ( $ ) {
	'use strict';

	function initialize_sp() {
		var swatches_form = function ( $form ) {
			var self = this;
			self.$form = $form;
			this.variationData = $form.data( 'product_variations' );
			this.$attributeFields = $form.find( '.variations select' );
			self.$singleVariation = $form.find( '.single_variation' );
			self.$singleVariationWrap = $form.find( '.single_variation_wrap' );
			self.$product = $form.closest( '.product' );

			const displayBySelectOptions = self.$product
				.find( '.variations_form' )
				.hasClass( 'sp-display-by-click-on-options-button' );

			if ( displayBySelectOptions ) {
				$( self.$product ).on(
					'click',
					'.add_to_cart_button.product_type_variable',
					function ( e ) {
						e.preventDefault();

						self.$product
							.find(
								'.sp-variation-swatches-quick-shop.sp-display-by-click-on-options-button'
							)
							.addClass( 'show' );
					}
				);
			}

			$( self.$product ).on(
				'click',
				'.sp-display-by_click_on_first_variation table.variations .sp-wrapper-item-li',
				function ( e ) {
					e.preventDefault();
					self.$product
						.find(
							'.sp-variation-swatches-quick-shop.sp-display-by_click_on_first_variation'
						)
						.addClass( 'show' );
				}
			);
			self.$is_quick_shop = $form.hasClass(
				'sp-variation-swatches-quick-shop'
			);

			$form.on( 'click.sp_vs_variation_form', function ( e ) {
				var $form = $( e.target ).closest(
					'form.sp-variation-swatches-quick-shop'
				);
				if (
					$form.length &&
					false === $form.data( 'product_variations' )
				) {
					$.ajax( {
						type: 'POST',
						url: shoppress_frontend.ajax.url,
						data: {
							action: 'sp_variation_swatches_quick_shop_get_product_variations',
							nonce: shoppress_frontend.ajax.nonce,
							product_id: $form.data( 'product_id' ),
						},
						success: function ( data ) {
							if ( data.length > 0 ) {
								$form.attr(
									'data-product_variations',
									JSON.stringify( data )
								);
								$form.data( 'product_variations', data );
								$form.trigger( 'reload_product_variations' );
								// $form.data('product_variations', data);
								// $form.attr('data-product_variations', data);
								// $form.wc_variation_form();
								// $form.sp_vs_variation_form();
							}
							$form.removeClass( 'loading' );
						},
						error: function ( data ) {
							console.log( data );
							$form.removeClass( 'loading' );
						},
					} );
				}
			} );
			$form.on(
				'click.sp_vs_variation_form',
				'.sp-checkbox',
				{ swatches_form: this },
				this.onselect
			);
			$form.on(
				'change.sp_vs_variation_form',
				'input[type="radio"].sp-rad',
				{ swatches_form: this },
				this.onselectradio
			);

			$form.on(
				'check_variations.sp_vs_variation_form',
				{ swatches_form: this },
				this.onFindVariation
			);
			$form.on(
				'click.sp_vs_variation_form',
				'.reset_variations',
				{ swatches_form: this },
				this.onReset
			);

			$form.on(
				'update_variation_values.sp_vs_variation_form',
				{ variationForm: this },
				this.onUpdateAttributes
			);

			if ( this.$is_quick_shop ) {
				$form.on( 'found_variation', function ( event, variation ) {
					var product_thumbnail_selector =
						'undefined' !==
							typeof $form.data( 'product_thumbnail_selector' ) &&
						$form.data( 'product_thumbnail_selector' ).length
							? $form.data( 'product_thumbnail_selector' )
							: '.sp-product-thumbnail img';
					var add_to_cart_link =
						'?add-to-cart=' + variation.variation_id;
					var $add_to_cart_button = self.$product.find(
						'.add_to_cart_button'
					);
					self.$product
						.find( '.price' )
						.replaceWith( variation.price_html );
					self.$product
						.find( product_thumbnail_selector )
						.attr( 'src', variation.image.thumb_src );
					self.$product
						.find( product_thumbnail_selector )
						.attr( 'srcset', variation.image.srcset );
					$add_to_cart_button.data(
						'variation_id',
						variation.variation_id
					);
					$add_to_cart_button.data( 'product_sku', variation.sku );
					$add_to_cart_button.attr( 'href', add_to_cart_link );
					$add_to_cart_button.text(
						shoppress_frontend.i18n.add_to_cart
					);
					$add_to_cart_button.removeClass( 'product_type_variable' );
				} );

				var variation_attributes_on_loop = $form.data(
					'variation_attributes_on_loop'
				);
				if ( parseInt( variation_attributes_on_loop ) ) {
					var $attributes = $form.find(
						'.variations tbody tr:nth-child(n+' +
							( variation_attributes_on_loop + 1 ) +
							')' +
							' ul.sp-wrapper-ul'
					);
					if (
						! $form.hasClass(
							'sp-display-by-click-on-options-button'
						) &&
						'undefined' !== typeof $attributes &&
						$attributes.length
					) {
						$attributes.addClass( 'sp-hidden' );
					}
				}

				$form.find( 'ul.sp-wrapper-ul' ).each( function ( i, ul ) {
					if (
						0 ===
						$( ul ).closest( '.sp-variation-swatches-quick-shop' )
							.length
					) {
						return;
					}

					var limit_swatches = $( ul )
						.closest( '.sp-variation-swatches-quick-shop' )
						.data( 'limit_swatches' );
					var $items = $( ul ).find(
						'li.sp-wrapper-item-li:nth-child(n+' +
							( limit_swatches + 1 ) +
							')'
					);

					if ( $items.length ) {
						$( ul ).addClass( 'sp-variation-swatches-has-more' );
						$items.addClass( 'sp-variation-swatch-more' );
						if (
							0 ===
							$( ul ).find( '.sp-variation-swatch-show-more' )
								.length
						) {
							$( ul ).append(
								'<i class="sp-variation-swatch-show-more eicon-angle-right"></i>'
							);
						}
					}
				} );

				$form
					.find( '.sp-variation-swatch-show-more' )
					.off( 'click' )
					.on( 'click', function ( e ) {
						$( this )
							.closest( '.sp-variation-swatches-has-more' )
							.toggleClass( 'show' );
					} );

				$form
					.find( 'ul.sp-wrapper-ul' )
					.first()
					.find( '.sp-wrapper-item-li' )
					.on( 'click', function ( e ) {
						$( this )
							.closest( '.variations' )
							.find( 'ul.sp-wrapper-ul' )
							.removeClass( 'sp-hidden' );
					} );
			} else {
				$form.on( 'found_variation', function ( event, variation ) {
					self.replaceGallery( variation );
				} );
			}
		};

		swatches_form.prototype.onReset = function ( event ) {
			var form = event.data.swatches_form;
			$( '.sp_vs_fields .sp-checkbox' ).removeClass( 'sp-selected' );
			$( '.sp_vs_fields > span' ).removeClass( 'selected' );
			$( '.sp_vs_fields .sp-checkbox' ).removeClass( 'deactive' );

			$( '.sp-rad' ).prop( 'checked', false );
			$( '.sp-rad' ).attr( 'checked', false );
			$( '.sp-rad-li > label' ).removeClass( 'sp-selected' );
			var $element = $( this );

			var $button = $element
				.parents( '.variations_form' )
				.siblings( '.sp_vs_add_to_cart_button' );
			active_and_deactive_variation( form );

			$( '.sp-selected-attribute-label', form.$form ).html( '' );
		};

		swatches_form.prototype.onselect = function ( event ) {
			var form = event.data.swatches_form;
			var $element = $( this ),
				$select = $element.closest( '.sp_vs_fields' ).find( 'select' ),
				attribute_name =
					$select.data( 'attribute_name' ) || $select.attr( 'name' ),
				value = $element.data( 'value' ),
				clicked = attribute_name;
			selected.push( attribute_name );
			var clear_by_reselect =
				$element
					.closest( '.sp-wrapper-ul' )
					.hasClass( 'sp-clear-by-reselect' ) ||
				$( form.$form ).hasClass( 'sp-clear-by-reselect' )
					? true
					: false;
			if ( 'undefined' !== typeof attribute_name ) {
				$element
					.parent()
					.parent()
					.parent()
					.parent()
					.find( '.sp-selected-attribute-label' )
					.html( value );
			}

			var opt_val = value;
			opt_val =
				typeof opt_val === 'string'
					? opt_val.replace( /'/g, "\\'" ).replace( /"/g, '\\"' )
					: opt_val;
			if ( ! $select.find( 'option[value="' + opt_val + '"]' ).length ) {
				$element
					.siblings( '.sp-checkbox' )
					.removeClass( 'sp-selected' );
				$select.val( '' ).change();
				alert( 'No combination' );
				return false;
			}

			if ( clear_by_reselect && $element.hasClass( 'sp-selected' ) ) {
				$element
					.removeClass( 'sp-selected' )
					.siblings( '.sp-selected' )
					.removeClass( 'sp-selected' );
				$select.val( '' );
			} else {
				$element
					.addClass( 'sp-selected' )
					.siblings( '.sp-selected' )
					.removeClass( 'sp-selected' );
				$select.val( value );
			}

			$select.change();
			active_and_deactive_variation( form );
		};

		swatches_form.prototype.onselectradio = function ( event ) {
			var form = event.data.swatches_form;
			var $element = $( this ),
				$select = $element.closest( '.sp_vs_fields' ).find( 'select' ),
				attribute_name =
					$select.data( 'attribute_name' ) || $select.attr( 'name' ),
				value = $element.data( 'value' );
			clicked = attribute_name;
			selected.push( attribute_name );

			// Added for making the radion fields outer class thwvs-selected for hiding the all fields other than first
			var parent_radio = $element.closest( '.th-label-radio' );
			var is_checked = $element.prop( 'checked' );

			if ( is_checked == true ) {
				parent_radio
					.addClass( 'sp-selected' )
					.siblings()
					.removeClass( 'sp-selected' );
			}

			$select.val( value );
			$select.change();

			// var form = event.data.swatches_form;
			// var $element = $( this ),
			// $select = $element.closest( '.sp_vs_fields' ).find( 'select' ),
			// attribute_name = $select.data( 'attribute_name' ) || $select.attr( 'name' ),
			// value = $element.data( 'value' );
			// clicked = attribute_name;
			// selected.push(attribute_name);

			// $select.val( value );
			// $select.change();
		};

		swatches_form.prototype.onUpdateAttributes = function ( event ) {
			var form = event.data.variationForm;

			active_and_deactive_variation( form );
		};

		function active_and_deactive_variation( form ) {
			var $attributeFields = form.$attributeFields,
				$addtocart_button = form.$form.find(
					'.woocommerce-variation-add-to-cart'
				);
			// var choosed_attr = $select.data( 'attribute_name' ) || $select.attr( 'name' );
			$attributeFields.each( function ( index, el ) {
				var current_attr_select = $( el ),
					current_attr_name =
						current_attr_select.data( 'attribute_name' ) ||
						current_attr_select.attr( 'name' );

				var $current_attr = form.$form.find( '.' + current_attr_name );

				$current_attr.addClass( 'deactive' );
				var options = current_attr_select.children( 'option' );

				options.each( function ( i, option ) {
					var opt_val = option.value;
					var disabled = $( option ).prop( 'disabled' );
					if ( opt_val != '' && ! disabled ) {
						opt_val = opt_val.replace( /[^a-z0-9_-]/gi, '' );
						var $current_opt = form.$form.find(
							'.' +
								current_attr_name +
								'[data-value="' +
								opt_val +
								'"]'
						);
						if ( $current_opt.length > 0 ) {
							$current_opt.removeClass( 'deactive' );
						} else {
							opt_val = opt_val.replace( /[^a-z0-9_-]/gi, '' );

							var $current_opt = form.$form.find(
								'.' + current_attr_name + '.' + opt_val
							);
							$current_opt.removeClass( 'deactive' );
						}
					}
				} );
			} );
		}

		$.fn.wc_set_variation_attr = function ( attr, value ) {
			if ( undefined === this.attr( 'data-o_' + attr ) ) {
				this.attr(
					'data-o_' + attr,
					! this.attr( attr ) ? '' : this.attr( attr )
				);
			}
			if ( false === value ) {
				this.removeAttr( attr );
			} else {
				this.attr( attr, value );
			}
		};

		swatches_form.prototype.onFindVariation = function ( event ) {
			var form = event.data.swatches_form;
			var $attributeFields = form.$attributeFields;

			active_and_deactive_variation( form );
		};

		swatches_form.prototype.replaceGallery = function ( variation ) {
			var variation_image_gallery_html =
				'undefined' !== typeof variation.variation_image_gallery_html
					? variation.variation_image_gallery_html
					: '';
			var $gallery = $(
				'.woocommerce-product-gallery, .sp-product-gallery',
				self.$product
			);
			if ( variation_image_gallery_html.length ) {
				$gallery.replaceWith( variation_image_gallery_html );

				if (
					$( variation_image_gallery_html ).hasClass(
						'woocommerce-product-gallery'
					)
				) {
					$gallery = $(
						'.woocommerce-product-gallery, .sp-product-gallery',
						self.$product
					);

					$gallery.trigger( 'wc-product-gallery-before-init', [
						$gallery,
						wc_single_product_params,
					] );

					$gallery.wc_product_gallery( wc_single_product_params );

					$gallery.trigger( 'wc-product-gallery-after-init', [
						$gallery,
						wc_single_product_params,
					] );
				} else {
					$( document ).trigger( 'sp_product_gallery_reinit' );
				}
			}
		};

		$.fn.sp_vs_variation_form = function () {
			new swatches_form( this );

			return this;
		};

		$( function () {
			if ( typeof wc_add_to_cart_variation_params !== 'undefined' ) {
				$( '.variations_form' ).each( function () {
					$( this ).sp_vs_variation_form();
				} );
			}
		} );

		var clicked = null,
			selected = [];
	}

	function remove_selected_attribute_item( $element ) {
		var default_label = $element
			.closest( 'tr' )
			.find( '.sp-wrapper-ul' )
			.data( 'default-label' );
		$element.closest( 'tr' ).find( 'label' ).text( default_label );
		var attrbute_uls = $element.closest( 'tr' ).siblings( 'tr' );

		attrbute_uls.each( function ( index, el ) {
			var elm = $( el ),
				default_label = elm
					.find( '.sp-wrapper-ul' )
					.data( 'default-label' );

			elm.find( 'label' ).text( default_label );
		} );
	}
	var execute = false;
	initialize_sp();

	$( document ).on( 'quick_view_loaded', initialize_sp );

	return {
		initialize_sp: initialize_sp,
	};
} )( jQuery );

function init_sp() {
	sp_vs_public.initialize_sp();
}
