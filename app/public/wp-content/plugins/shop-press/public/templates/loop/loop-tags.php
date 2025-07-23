<?php
/**
 * Loop Tags.
 *
 * @package ShopPress
 */

defined( 'ABSPATH' ) || exit;

global $product;

if ( isset( $args['separator'] ) ) {
	$separator = '<span class="separator">' . $args['separator'] . '</span>';
} else {
	$separator = '<span class="separator">|</span>';
}

echo get_the_term_list( $product->get_id(), 'product_tag', '<div class="sp-product-tags">', $separator, '</div>' );
