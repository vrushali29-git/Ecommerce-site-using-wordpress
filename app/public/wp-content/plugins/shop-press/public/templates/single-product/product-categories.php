<?php
/**
 * Product Category.
 *
 * @package ShopPress
 */

defined( 'ABSPATH' ) || exit;

global $product;

$separator = '';

if ( isset( $args['category_separator'] ) ) {
	$separator = $args['category_separator'];
} else {
	$separator = ', ';
}

if ( ! empty( $separator ) ) {
	$separator = '<span class="sp-cat-separator">' . $separator . '</span>';
}

?>
<div class="sp-categories-wrapper">
	<span class="sp-category-label"><?php echo wp_kses_post( $args['category_label'] ); ?></span>
	<?php
		echo wp_kses_post( get_the_term_list( $product->get_id(), 'product_cat', '<span class="sp-product-categories">', $separator, '</span>' ) );
	?>
</div>
