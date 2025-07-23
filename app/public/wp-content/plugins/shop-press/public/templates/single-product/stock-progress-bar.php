<?php
/**
 * Stock Progress Bar.
 *
 * @package ShopPress
 */

defined( 'ABSPATH' ) || exit;

global $product;

if ( ! $product->managing_stock() ) {
	return;
}

$total_sales = get_post_meta( get_the_ID(), 'total_sales', true );
$stock_qty   = $product->get_stock_quantity();
$total_stock = (int) $total_sales + (int) $stock_qty;
$width       = ( (int) $total_sales / $total_stock ) * 100;

?>
<div class="sp-stock-progress-bar">
	<div class="sp-stock-progress-bar-labels">
		<?php
		if ( $args['show_ordered'] ) :
			?>
			<div class="sp-stock-progress-bar-total-sales"><?php echo wp_sprintf( '<span class="sp-stock-progress-bar-labels-ordered">%s</span> <span class="sp-stock-progress-bar-labels-ordered-count">%s</span>', $args['ordered_label'], $total_sales ); ?></div><?php endif; ?>
		<?php
		if ( $args['show_available'] ) :
			?>
			<div class="sp-stock-progress-bar-stock-qty"><?php echo wp_sprintf( '<span class="sp-stock-progress-bar-labels-available">%s</span> <span class="sp-stock-progress-bar-labels-available-count">%s</span>', $args['available_label'], $stock_qty ); ?></div><?php endif; ?>
	</div>
	<div class="sp-stock-progress-bar-percent">
		<div class="sp-stock-progress-bar-percent-sold" style="width: <?php echo esc_attr( $width ); ?>%"></div>
	</div>
</div>
