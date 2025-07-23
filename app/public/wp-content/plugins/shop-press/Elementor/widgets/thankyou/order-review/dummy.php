<?php

defined( 'ABSPATH' ) || exit;

?>

<div class="sp-thankyou-order-review">
	<p class="woocommerce-notice woocommerce-notice--success woocommerce-thankyou-order-received">Thank you. Your order has been received.</p>

	<ul class="woocommerce-order-overview woocommerce-thankyou-order-details order_details">

		<li class="woocommerce-order-overview__order order">
			<?php esc_html_e( 'Order number:', 'shop-press' ); ?>
			<strong>562</strong>
		</li>

		<li class="woocommerce-order-overview__date date">
			<?php esc_html_e( 'Date:', 'shop-press' ); ?>
			<strong>February 26, 2024</strong>
		</li>

		<li class="woocommerce-order-overview__email email">
			<?php esc_html_e( 'Email:', 'shop-press' ); ?>
			<strong>sample@yourdomain.com</strong>
		</li>

		<li class="woocommerce-order-overview__total total">
			<?php esc_html_e( 'Total:', 'shop-press' ); ?>
			<strong>$49.50</strong>
		</li>

		<li class="woocommerce-order-overview__payment-method method">
			<?php esc_html_e( 'Payment method:', 'shop-press' ); ?>
			<strong>Direct bank transfer</strong>
		</li>
	</ul>
</div>
