<?php
/**
 * Product Description.
 *
 * @package ShopPress
 */

defined( 'ABSPATH' ) || exit;

global $product;

?>
<div class="sp-product-content-wrapper">
	<p class="sp-product-content">
		<?php echo wp_kses_post( wpautop( $product->get_description() ) ); ?>
	</p>
</div>
