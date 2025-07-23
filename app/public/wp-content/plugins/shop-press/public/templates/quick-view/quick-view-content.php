<?php
/**
 * Quick View
 *
 * @package ShopPress\Templates
 *
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

global $post, $product;

do_action( 'shoppress_quick_view_before_content', $product );
?>
<div id="product-<?php the_ID(); ?>" <?php wc_product_class( 'sp-qv-wrap', $product ); ?>>
	<div class="sp-qv-image">
		<?php do_action( 'shoppress_quick_view_images' ); ?>
	</div>
	<div id="sp-qv-content">
		<?php do_action( 'shoppress_quick_view_content' ); ?>
	</div>
</div>
<?php
do_action( 'shoppress_quick_view_after_content', $product );
