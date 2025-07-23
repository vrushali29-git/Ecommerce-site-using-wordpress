<?php
/**
 * Variation Product Add To Cart
 *
 * @package ShopPress\Templates
 *
 * @version 7.0.1
 */

defined( 'ABSPATH' ) || exit;

use ShopPress\Modules;

global $product;

$settings      = defined( 'ADD_TO_CART_ARGS' ) ? ADD_TO_CART_ARGS : array();
$icon          = isset( $settings['cart_icon'] ) ? $settings['cart_icon'] : '';
$icon_position = isset( $settings['cart_icon_pos'] ) ? $settings['cart_icon_pos'] : '';
?>
<div class="woocommerce-variation-add-to-cart variations_button">
	<?php do_action( 'woocommerce_before_add_to_cart_button' ); ?>

	<?php
	do_action( 'woocommerce_before_add_to_cart_quantity' );

	woocommerce_quantity_input(
		array(
			'min_value'   => apply_filters( 'woocommerce_quantity_input_min', $product->get_min_purchase_quantity(), $product ),
			'max_value'   => apply_filters( 'woocommerce_quantity_input_max', $product->get_max_purchase_quantity(), $product ),
			'input_value' => isset( $_POST['quantity'] ) ? wc_stock_amount( wp_unslash( $_POST['quantity'] ) ) : $product->get_min_purchase_quantity(), // WPCS: CSRF ok, input var ok.
		)
	);

	do_action( 'woocommerce_after_add_to_cart_quantity' );
	?>

	<?php sp_add_to_cart_button( $product, $icon, $icon_position ); ?>

	<?php do_action( 'woocommerce_after_add_to_cart_button' ); ?>

	<input type="hidden" name="add-to-cart" value="<?php echo absint( $product->get_id() ); ?>" />
	<input type="hidden" name="product_id" value="<?php echo absint( $product->get_id() ); ?>" />
	<input type="hidden" name="variation_id" class="variation_id" value="0" />
</div>
