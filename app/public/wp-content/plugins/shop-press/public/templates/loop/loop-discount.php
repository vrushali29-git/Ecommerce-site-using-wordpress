<?php

/**
 * ShopPress Loop Product Title view
 *
 * @version     1.0.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

$label = $args['label'] ?? '';

$percentage = sp_get_product_discount( $product );
if ( ! $percentage ) {
	return;
}

?>
<div class="sp-product-discount">
	<?php echo '<span class="sp-product-discount-label">' . esc_html__( $label, 'shop-press' ) . '</span> <span class="sp-product-discount-price">' . $percentage . '</span>'; ?>
</div>
