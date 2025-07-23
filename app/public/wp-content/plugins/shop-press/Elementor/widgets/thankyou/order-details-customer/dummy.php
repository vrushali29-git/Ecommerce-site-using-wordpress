<?php

defined( 'ABSPATH' ) || exit;

?>
<section class="woocommerce-customer-details">
	<div class="sp-thankyou-order-details-customer">

	<section class="woocommerce-columns woocommerce-columns--2 woocommerce-columns--addresses col2-set addresses">
		<div class="woocommerce-column woocommerce-column--1 woocommerce-column--billing-address col-1">

			<h2 class="woocommerce-column__title"><?php esc_html_e( 'Billing address', 'shop-press' ); ?></h2>

			<address>
				<?php esc_html_e( 'United States (US) Minor Outlying Islands', 'shop-press' ); ?>

				<p class="woocommerce-customer-details--phone"><?php echo esc_html( '999-99-99' ); ?></p>

				<p class="woocommerce-customer-details--email"><?php echo esc_html( 'sample@yourdomain.com' ); ?></p>
			</address>
		</div><!-- /.col-1 -->

		<div class="woocommerce-column woocommerce-column--2 woocommerce-column--shipping-address col-2">
			<h2 class="woocommerce-column__title"><?php esc_html_e( 'Shipping address', 'shop-press' ); ?></h2>
			<address>
				<?php esc_html_e( 'United States (US) Minor Outlying Islands', 'shop-press' ); ?>
				<p class="woocommerce-customer-details--phone"><?php echo esc_html( '999-99-99' ); ?></p>
			</address>
		</div><!-- /.col-2 -->

	</section><!-- /.col2-set -->
	</div>
</section>
