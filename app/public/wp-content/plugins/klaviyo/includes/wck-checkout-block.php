<?php
/**
 * Add hooks for Checkout Block integration.
 *
 * @package WooCommerceKlaviyo/Blocks
 * @version 3.2.0
 */

/**
 * Registers the block using the metadata loaded from the `block.json` file.
 * Behind the scenes, it registers also all assets so they can be enqueued
 * through the block editor in the corresponding context.
 *
 * @see https://developer.wordpress.org/reference/functions/register_block_type/
 */

require __DIR__ . '/blocks/StoreApi.php';

new WCK\Blocks\StoreApi();

add_action(
	'init',
	function () {
		register_block_type( __DIR__ . '/blocks/build' );
	}
);

add_action(
	'woocommerce_blocks_checkout_block_registration',
	function ( $integration_registry ) {
		require_once __DIR__ . '/blocks/CheckoutIntegration.php';
		$integration_registry->register(
			new WCK\Blocks\CheckoutIntegration()
		);
	},
	10,
	1
);
