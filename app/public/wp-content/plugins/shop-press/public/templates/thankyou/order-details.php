<?php
/**
 * Order Details.
 *
 * @package ShopPress
 */

defined( 'ABSPATH' ) || exit;

use ShopPress\Templates\Thankyou;

$order_key = Thankyou::get_order_key();
$order_id  = wc_get_order_id_by_order_key( $order_key );
$order     = wc_get_order( $order_id ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited

// if ( $this->is_editor() ) {
// $args = array(
// 'limit' => 1,
// 'paged' => 1,
// );

// $latest_order = wc_get_orders( $args );
// $order_id     = isset( $latest_order[0] ) ? $latest_order[0]->get_id() : '';
// $order        = wc_get_order( $order_id ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
// }

if ( ! $order ) {
	return;
}

?>
<div class="sp-thankyou-order-details">
	<?php
	$order_items           = $order->get_items( apply_filters( 'woocommerce_purchase_order_item_types', 'line_item' ) );
	$show_purchase_note    = $order->has_status( apply_filters( 'woocommerce_purchase_note_order_statuses', array( 'completed', 'processing' ) ) );
	$show_customer_details = is_user_logged_in() && $order->get_user_id() === get_current_user_id();
	$downloads             = $order->get_downloadable_items();
	$show_downloads        = $order->has_downloadable_item() && $order->is_download_permitted();

	if ( $show_downloads ) {
		wc_get_template(
			'order/order-downloads.php',
			array(
				'downloads'  => $downloads,
				'show_title' => true,
			)
		);
	}
	?>
	<section class="woocommerce-order-details">
		<?php do_action( 'woocommerce_order_details_before_order_table', $order ); ?>

		<h2 class="woocommerce-order-details__title"><?php esc_html_e( 'Order details', 'shop-press' ); ?></h2>

		<table class="woocommerce-table woocommerce-table--order-details shop_table order_details">

			<thead>
				<tr>
					<th class="woocommerce-table__product-name product-name"><?php esc_html_e( 'Product', 'shop-press' ); ?></th>
					<th class="woocommerce-table__product-table product-total"><?php esc_html_e( 'Total', 'shop-press' ); ?></th>
				</tr>
			</thead>

			<tbody>
				<?php
				do_action( 'woocommerce_order_details_before_order_table_items', $order );

				foreach ( $order_items as $item_id => $item ) {
					$product = $item->get_product();

					wc_get_template(
						'order/order-details-item.php',
						array(
							'order'              => $order,
							'item_id'            => $item_id,
							'item'               => $item,
							'show_purchase_note' => $show_purchase_note,
							'purchase_note'      => $product ? $product->get_purchase_note() : '',
							'product'            => $product,
						)
					);
				}

				do_action( 'woocommerce_order_details_after_order_table_items', $order );
				?>
			</tbody>

			<tfoot>
				<?php
				foreach ( $order->get_order_item_totals() as $key => $total ) {
					?>
						<tr>
							<th scope="row"><?php echo esc_html( $total['label'] ); ?></th>
							<td><?php echo ( 'payment_method' === $key ) ? esc_html( $total['value'] ) : wp_kses_post( $total['value'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></td>
						</tr>
						<?php
				}
				?>
				<?php if ( $order->get_customer_note() ) : ?>
					<tr>
						<th><?php esc_html_e( 'Note:', 'shop-press' ); ?></th>
						<td><?php echo wp_kses_post( nl2br( wptexturize( $order->get_customer_note() ) ) ); ?></td>
					</tr>
				<?php endif; ?>
			</tfoot>
		</table>

		<?php do_action( 'woocommerce_order_details_after_order_table', $order ); ?>
	</section>

	<?php do_action( 'woocommerce_after_order_details', $order ); ?>
</div>
