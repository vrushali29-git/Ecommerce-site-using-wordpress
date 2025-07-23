<?php
/**
 * Loop Price.
 *
 * @package ShopPress
 */

defined( 'ABSPATH' ) || exit;

global $product;

$classes = apply_filters( 'woocommerce_product_price_class', 'price' ); ?>

<div class="sp-product-price">
	<p class="<?php echo esc_attr( $classes ); ?>">
		<?php echo wp_kses_post( $product->get_price_html() ); ?>
	</p>
</div>
