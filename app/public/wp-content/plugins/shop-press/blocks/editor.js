const builderType = sp_blocks.custom_type;
const postType = sp_blocks.post_type;

wp.domReady( function () {
	const generalBlocks = [ 'shop-press/product-collection' ];

	wp.blocks.getBlockTypes().map( function ( blockType ) {
		if (
			builderType.length === 0 &&
			blockType.name.includes( 'shop-press' ) &&
			! blockType.name.includes( generalBlocks )
		) {
			wp.blocks.unregisterBlockType( blockType.name );
		}
	} );

	function addWooClass( selector, classes ) {
		jQuery( document ).find( selector ).addClass( classes );
	}

	setTimeout( () => {
		const interfaceBody = '.interface-interface-skeleton__body';
		const spPages = '.post-type-shoppress_pages';
		const spLoop = '.post-type-shoppress_loop';
		const spMyAccount = '.post-type-shoppress_myaccount';
		const blockPostContent = '.wp-block-post-content';
		const defaultClasses = 'woocommerce woocommerce-page woocommerce-js';
		const accountClassess =
			'woocommerce-account woocommerce-page woocommerce-js woocommerce-edit-account woocommerce-orders woocommerce-downloads woocommerce-edit-address woocommerce-edit-account';

		if ( 'shoppress_pages' === postType ) {
			addWooClass( `${ spPages } ${ interfaceBody }`, defaultClasses );

			if ( 'single' === builderType || 'quick_view' === builderType ) {
				addWooClass( `${ spPages } ${ blockPostContent }`, 'product' );
			}

			if ( 'shop' === builderType || 'archive' === builderType ) {
				addWooClass(
					`${ spPages } ${ blockPostContent }`,
					'archive tax-product_cat tax-product_tag post-type-archive post-type-archive-product'
				);
			}

			if ( 'checkout' === builderType || 'thank_you' === builderType ) {
				addWooClass(
					`${ spPages } ${ blockPostContent }`,
					'woocommerce-checkout woocommerce-order-received'
				);
			}

			if ( 'cart' === builderType || 'empty_cart' === builderType ) {
				addWooClass(
					`${ spPages } ${ blockPostContent }`,
					'woocommerce-cart'
				);
			}
		}

		if ( 'shoppress_loop' === postType ) {
			addWooClass( `${ spLoop } ${ interfaceBody }`, defaultClasses );

			if ( 'products_loop' === builderType ) {
				addWooClass(
					`${ spLoop } ${ blockPostContent }`,
					'archive tax-product_cat tax-product_tag post-type-archive post-type-archive-product'
				);
			}
		}

		if ( 'shoppress_myaccount' === postType ) {
			addWooClass(
				`${ spMyAccount } ${ interfaceBody }`,
				accountClassess
			);
		}
	}, 500 );
} );
