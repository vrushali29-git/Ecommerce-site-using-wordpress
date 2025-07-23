<?php
/**
 * Product Attributes.
 *
 * @package ShopPress
 */

defined( 'ABSPATH' ) || exit;

global $product;
?>

<div class="sp-attributes-wrapper">
	<?php wc_display_product_attributes( $product ); ?>
</div>
