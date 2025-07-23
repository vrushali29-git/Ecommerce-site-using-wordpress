<?php
/**
 * Product Price.
 *
 * @package ShopPress
 */

defined( 'ABSPATH' ) || exit;

global $product;

?>
<div class="sp-price-wrapper">
	<p class="<?php echo esc_attr( apply_filters( 'woocommerce_product_price_class', 'price' ) ); ?>">
		<?php echo wp_kses_post( $product->get_price_html() ); ?>
	</p>
</div>
<?php
