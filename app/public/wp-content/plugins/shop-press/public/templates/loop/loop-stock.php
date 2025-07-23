<?php
/**
 * Loop Stock.
 *
 * @package ShopPress
 */

defined( 'ABSPATH' ) || exit;

global $product;

$availability       = $product->get_availability();
$stock_status       = $product->get_stock_status();
$stock_status_label = '';

if ( $stock_status === 'instock' ) {
	$stock_status_label = 'In Stock';
} elseif ( $stock_status === 'outofstock' ) {
	$stock_status_label = 'Out Of Stock';
}

?>

<p class="sp-product-stock">
	<span class="sp-stock-label"><?php echo esc_html( $args['label'] ); ?></span>
	<?php if ( $availability['availability'] ) : ?>
		<span class="sp-stock-availability"><?php echo wp_kses_post( $availability['availability'] ); ?></span>
	<?php else : ?>
		<span class="sp-stock-availability"><?php echo wp_kses_post( $stock_status_label ); ?></span>
	<?php endif; ?>
</p>
