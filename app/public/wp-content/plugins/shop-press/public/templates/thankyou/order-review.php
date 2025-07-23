<?php
/**
 * Order Review.
 *
 * @package ShopPress
 */

defined( 'ABSPATH' ) || exit;

use ShopPress\Templates\Thankyou;

$order_key = Thankyou::get_order_key();
$order_id  = wc_get_order_id_by_order_key( $order_key );
$order     = wc_get_order( $order_id ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited

if ( ! $order ) {
	return;
}

?>
<div class="sp-thankyou-order-review">
	<?php if ( $order->has_status( 'failed' ) ) : ?>

		<p class="woocommerce-notice woocommerce-notice--error woocommerce-thankyou-order-failed"><?php esc_html_e( 'Unfortunately your order cannot be processed as the originating bank/merchant has declined your transaction. Please attempt your purchase again.', 'shop-press' ); ?></p>

		<p class="woocommerce-notice woocommerce-notice--error woocommerce-thankyou-order-failed-actions">
			<a href="<?php echo esc_url( $order->get_checkout_payment_url() ); ?>" class="button pay"><?php esc_html_e( 'Pay', 'shop-press' ); ?></a>
			<?php if ( is_user_logged_in() ) : ?>
				<a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>" class="button pay"><?php esc_html_e( 'My account', 'shop-press' ); ?></a>
			<?php endif; ?>
		</p>

	<?php else : ?>

		<?php wc_get_template( 'checkout/order-received.php', array( 'order' => $order ) ); ?>

	<ul class="woocommerce-order-overview woocommerce-thankyou-order-details order_details">

		<li class="woocommerce-order-overview__order order">
			<?php esc_html_e( 'Order number:', 'shop-press' ); ?>
			<strong><?php echo $order->get_order_number(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></strong>
		</li>

		<li class="woocommerce-order-overview__date date">
			<?php esc_html_e( 'Date:', 'shop-press' ); ?>
			<strong><?php echo wc_format_datetime( $order->get_date_created() ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></strong>
		</li>

		<?php if ( is_user_logged_in() && $order->get_user_id() === get_current_user_id() && $order->get_billing_email() ) : ?>
			<li class="woocommerce-order-overview__email email">
				<?php esc_html_e( 'Email:', 'shop-press' ); ?>
				<strong><?php echo $order->get_billing_email(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></strong>
			</li>
		<?php endif; ?>

		<li class="woocommerce-order-overview__total total">
			<?php esc_html_e( 'Total:', 'shop-press' ); ?>
			<strong><?php echo $order->get_formatted_order_total(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></strong>
		</li>

		<?php if ( $order->get_payment_method_title() ) : ?>
			<li class="woocommerce-order-overview__payment-method method">
				<?php esc_html_e( 'Payment method:', 'shop-press' ); ?>
				<strong><?php echo wp_kses_post( $order->get_payment_method_title() ); ?></strong>
			</li>
		<?php endif; ?>

	</ul>

	<?php endif; ?>
</div>
