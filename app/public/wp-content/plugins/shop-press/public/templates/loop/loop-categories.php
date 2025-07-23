<?php
/**
 * Loop Category.
 *
 * @package ShopPress
 */

defined( 'ABSPATH' ) || exit;

global $product;

$separator = '';

if ( isset( $args['separator'] ) ) {
	$separator = $args['separator'];
} else {
	$separator = ', ';
}

if ( ! empty( $separator ) ) {
	$separator = '<span class="separator">' . $separator . '</span>';
}

echo get_the_term_list( $product->get_id(), 'product_cat', '<div class="sp-product-cats">', $separator, '</div>' );
