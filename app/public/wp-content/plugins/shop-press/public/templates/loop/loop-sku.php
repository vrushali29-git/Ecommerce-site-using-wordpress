<?php
/**
 * Loop SKU.
 *
 * @package ShopPress
 */

defined( 'ABSPATH' ) || exit;

global $product;

?>
<div class="sp-loop-product-sku">
	<?php echo esc_html( $product->get_sku() ); ?>
</div>
