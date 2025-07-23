<?php
/**
 * Product Tags.
 *
 * @package ShopPress
 */

defined( 'ABSPATH' ) || exit;

global $product;

if ( ! get_the_term_list( $product->get_id(), 'product_tag' ) ) {
	return;
}

$separator = '';

if ( isset( $args['tag_separator'] ) ) {
	$separator = $args['tag_separator'];
} else {
	$separator = ', ';
}

if ( ! empty( $separator ) ) {
	$separator = '<span class="sp-tags-separator">' . $separator . '</span>';
}
?>
<div class="sp-tags-wrapper">
	<span class="sp-tag-label"><?php echo wp_kses_post( $args['tag_label'] ); ?></span>
	<?php
		echo wp_kses_post( get_the_term_list( $product->get_id(), 'product_tag', '<span class="sp-product-tags">', $separator, '</span>' ) );
	?>
</div>
