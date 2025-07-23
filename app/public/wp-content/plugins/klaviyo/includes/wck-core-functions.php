<?php
/**
 * WooCommerceKlaviyo Core Functions
 *
 * WooCommerce related functions -- this file will only be included if WooCommerce is activated.
 *
 * @package   WooCommerceKlaviyo/Functions
 * @version   2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

require 'wck-cart-rebuild.php';
require 'wck-added-to-cart.php';
require 'wck-cart-functions.php';
require 'wck-viewed-product.php';
/**
 * Adds WooCommerce Checkout Block compatibility - should only be
 * included if version of WooCoomerce supports the StoreApi and
 * integration is setup e.g. we have a public API key set.
 */
$klaviyo_settings = get_option('klaviyo_settings');
if (isset($klaviyo_settings['klaviyo_public_api_key']) && class_exists('\Automattic\WooCommerce\StoreApi\Schemas\V1\CheckoutSchema')) {
	include 'wck-checkout-block.php';
}
