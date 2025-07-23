<?php
/**
 * Product Discount.
 *
 * @package ShopPress
 */

defined( 'ABSPATH' ) || exit;

global $product;

$label = $args['label'] ?? '';

$percentage = sp_get_product_discount( $product );
if ( $percentage ) : ?>
    <div class="sp-product-discount">
        <span class="sp-product-discount-label"><?php echo esc_html__( $label, 'shop-press' ); ?></span>
        <span class="sp-product-discount-price"><?php echo $percentage; ?></span>
    </div>
<?php endif; ?>
