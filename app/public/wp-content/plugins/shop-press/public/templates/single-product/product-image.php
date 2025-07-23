<?php
/**
 * Product Image.
 *
 * @package ShopPress
 */

defined( 'ABSPATH' ) || exit;

// Note: `wc_get_gallery_image_html` was added in WC 3.3.2 and did not exist prior. This check protects against theme overrides being used on older versions of WC.
if ( ! function_exists( 'wc_get_gallery_image_html' ) ) {
	return;
}

global $post, $product;

?>
<div class="sp-images">
	<?php wc_get_template( 'single-product/product-image.php' ); ?>
</div>
