<?php
/**
 * Product SKU.
 *
 * @package ShopPress
 */

defined( 'ABSPATH' ) || exit;

global $product;

if ( ! $product->get_sku() ) {
	return;
}

?>
<div class="sp-sku-wrapper">
	<span class="sp-sku-label"><?php echo wp_kses_post( $args['sku_label'] ?? '' ); ?></span>
	<span class="sp-sku sku">
		<?php echo wp_kses_post( $product->get_sku() ); ?>
	</span>
</div>
