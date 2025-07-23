<?php
/**
 * Product Add To Cart.
 *
 * @package ShopPress
 */

defined( 'ABSPATH' ) || exit;

global $product;

if ( empty( $product ) ) {
	return;
}

$product_type = $product->get_type();

if ( ! defined( 'ADD_TO_CART_ARGS' ) ) {

	define( 'ADD_TO_CART_ARGS', $args );
}
?>
<div class="sp-add-to-cart-wrapper product-<?php echo esc_attr( $product_type ); ?> <?php echo isset( $args['quantity_style'] ) ? 'sp-quantity-style-' . $args['quantity_style'] : ''; ?> ">
	<?php
	switch ( $product_type ) {
		case 'simple':
			woocommerce_simple_add_to_cart();
			break;

		case 'grouped':
			woocommerce_grouped_add_to_cart();
			break;

		case 'variable':
			woocommerce_variable_add_to_cart();
			break;

		case 'external':
			woocommerce_external_add_to_cart();
			break;
	}
	?>
</div>
