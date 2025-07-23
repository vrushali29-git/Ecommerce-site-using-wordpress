<?php
/**
 * External Product Add To Cart
 *
 * @package ShopPress\Templates
 *
 * @version 7.0.1
 */

defined( 'ABSPATH' ) || exit;

use ShopPress\Modules;

global $product;

if ( empty( $product ) ) {
	return;
}

$settings      = defined( 'ADD_TO_CART_ARGS' ) ? ADD_TO_CART_ARGS : array();
$icon          = isset( $settings['cart_icon'] ) ? $settings['cart_icon'] : '';
$icon_position = isset( $settings['cart_icon_pos'] ) ? $settings['cart_icon_pos'] : '';

do_action( 'woocommerce_before_add_to_cart_form' ); ?>

<form class="cart" action="<?php echo esc_url( $product_url ); ?>" method="get">
	<?php do_action( 'woocommerce_before_add_to_cart_button' ); ?>

	<?php sp_add_to_cart_button( $product, $icon, $icon_position ); ?>

	<?php wc_query_string_form_fields( $product_url ); ?>

	<?php do_action( 'woocommerce_after_add_to_cart_button' ); ?>
</form>

<?php do_action( 'woocommerce_after_add_to_cart_form' ); ?>
