<?php
/**
 * Loop Description.
 *
 * @package ShopPress
 */

defined( 'ABSPATH' ) || exit;

global $product;

?>
<div class="sp-product-description">
	<p><?php echo $product->get_description(); ?></p>
</div>
