<?php
/**
 * Multi step checkout form
 *
 * @package ShopPress\Templates
 */

defined( 'ABSPATH' ) || exit;

$show_return_to_shop                         = sp_get_module_settings( 'multi_step_checkout', 'show_return_to_shop' );
$display_billing_and_shipping_steps_together = sp_get_module_settings( 'multi_step_checkout', 'display_billing_and_shipping_steps_together' );
$display_order_and_payment_steps_together    = sp_get_module_settings( 'multi_step_checkout', 'display_order_and_payment_steps_together' );
$registration_enabled                        = get_option( 'woocommerce_enable_signup_and_login_from_checkout' );
$show_login                                  = ( is_user_logged_in() || 'no' === get_option( 'woocommerce_enable_checkout_login_reminder' ) ) ? false : true;
$login                                       = ( ! $registration_enabled && $checkout->is_registration_required() && ! is_user_logged_in() ) ? true : false;
$checkout_url                                = wc_get_checkout_url();
$arrow                                       = '<svg xmlns="http://www.w3.org/2000/svg" width="6" height="11" viewBox="0 0 6 11">
<path d="M5.786,6.09,1.261,10.776a.721.721,0,0,1-1.044,0,.784.784,0,0,1,0-1.081l4.05-4.2L.216,1.305a.784.784,0,0,1,0-1.081.721.721,0,0,1,1.044,0L5.786,4.91A.765.765,0,0,1,6,5.476.87.87,0,0,1,5.786,6.09Z" fill="#d4d7dc"/>
</svg>';

if ( ! $registration_enabled && $checkout->is_registration_required() && ! is_user_logged_in() && ! $show_login ) {
	echo apply_filters( 'woocommerce_checkout_must_be_logged_in_message', __( 'You must be logged in to checkout.', 'shop-press' ) );
	return;
}

wc_print_notices();

$numbers = array(
	'1' => 1,
	'2' => 2,
	'3' => 3,
	'4' => 4,
);

if ( $show_login ) {
	foreach ( $numbers as $key => $number ) {
		$numbers[ $key ] = $number + 1;
	}
}

?>

<div class="sp-multistep-checkout">
	<div class="sp-tabs-wrapper">
		<ul class="sp-tabs-list">
		<?php if ( $show_login ) : ?>
			<li class="sp-tab-item current sp-login" data-step-title="login">
				<span class="sp-tab-number">1</span>
				<span class="sp-tab-text"><?php esc_html_e( 'Login', 'shop-press' ); ?></span>
			</li>
		<?php endif; ?>

		<?php if ( $display_billing_and_shipping_steps_together ) : ?>
			<li class="sp-tab-item sp-billing-and-shipping <?php is_user_logged_in() ? esc_html_e( 'current' ) : ''; ?>" data-step-title="billing-shipping">
				<span class="sp-tab-number"><?php esc_html_e( current( $numbers ) ); ?></span>
				<span class="sp-tab-text"><?php esc_html_e( 'Billing & Shipping', 'shop-press' ); ?></span>
			</li>
		<?php else : ?>
			<li class="sp-tab-item sp-billing <?php is_user_logged_in() ? esc_html_e( 'current' ) : ''; ?>" data-step-title="billing">
				<span class="sp-tab-number"><?php esc_html_e( current( $numbers ) ); ?></span>
				<span class="sp-tab-text"><?php esc_html_e( 'Billing', 'shop-press' ); ?></span>
			</li>

			<li class="sp-tab-item sp-shipping" data-step-title="shipping">
				<span class="sp-tab-number"><?php esc_html_e( next( $numbers ) ); ?></span>
				<span class="sp-tab-text"><?php esc_html_e( 'Shipping', 'shop-press' ); ?></span>
			</li>
		<?php endif; ?>

		<?php if ( $display_order_and_payment_steps_together ) : ?>

			<li class="sp-tab-item sp-order-and-payment" data-step-title="order-payment">
				<span class="sp-tab-number"><?php esc_html_e( next( $numbers ) ); ?></span>
				<span class="sp-tab-text"><?php esc_html_e( 'Order & Payment', 'shop-press' ); ?></span>
			</li>
		<?php else : ?>
			<li class="sp-tab-item sp-order" data-step-title="order">
				<span class="sp-tab-number"><?php esc_html_e( next( $numbers ) ); ?></span>
				<span class="sp-tab-text"><?php esc_html_e( 'Order', 'shop-press' ); ?></span>
			</li>

			<li class="sp-tab-item sp-payment" data-step-title="payment">
				<span class="sp-tab-number"><?php esc_html_e( next( $numbers ) ); ?></span>
				<span class="sp-tab-text"><?php esc_html_e( 'Payment', 'shop-press' ); ?></span>
			</li>
		<?php endif; ?>
		</ul>
	</div>

	<div class="sp-steps-wrapper">
	<div id="woocommerce_before_checkout_form" class="woocommerce_before_checkout_form" data-step="<?php echo apply_filters( 'woocommerce_before_checkout_form_step', 'step-review' ); ?>" style="display: none;">
		<?php do_action( 'woocommerce_before_checkout_form', $checkout ); ?>
	</div>

	<?php if ( $show_login ) : ?>
		<div class="sp-step-item sp-step-login">
			<div id="checkout_login" class="woocommerce_checkout_login">
				<?php
				woocommerce_login_form(
					array(
						'message'  => apply_filters( 'woocommerce_checkout_logged_in_message', __( 'If you have shopped with us before, please enter your details in the boxes below. If you are a new customer, please proceed to the Billing &amp; Shipping section.', 'shop-press' ) ),
						'redirect' => wc_get_page_permalink( 'checkout' ),
						'hidden'   => false,
					)
				);
				?>
			</div>
				<?php
				if ( $login ) {
					echo apply_filters( 'woocommerce_checkout_must_be_logged_in_message', __( 'You must be logged in to checkout.', 'shop-press' ) );
					echo '</div>';
				}
				?>
		</div>
	<?php endif; ?>

	<form name="checkout" method="post" class="checkout woocommerce-checkout" action="<?php echo esc_url( $checkout_url ); ?>" enctype="multipart/form-data">

		<div class="sp-step-item <?php echo $display_billing_and_shipping_steps_together ? 'sp-step-billing-and-shipping' : 'sp-step-billing'; ?>">
			<?php
				do_action( 'woocommerce_checkout_before_customer_details' );
				do_action( 'woocommerce_checkout_billing' );
			?>

			<?php if ( ! $display_billing_and_shipping_steps_together ) : ?>
				</div>

				<div class="sp-step-item sp-step-shipping">
			<?php endif; ?>

			<?php
				do_action( 'woocommerce_checkout_shipping' );
			?>
		</div>

		<div class="sp-step-item <?php echo $display_order_and_payment_steps_together ? 'sp-step-order-and-payment' : 'sp-step-review'; ?>">
			<?php
				do_action( 'woocommerce_checkout_before_order_review' );
				echo '<h3 id="order_review_heading">' . esc_html__( 'Your order', 'shop-press' ) . '</h3>';
				echo '<div id="order_review">';
				do_action( 'sp-woocommerce_order_review' );
				echo '</div>';
			?>

			<?php if ( ! $display_order_and_payment_steps_together ) : ?>
				</div>

				<div class="sp-step-item sp-step-payment">
			<?php endif; ?>

			<?php
				echo '<h3 id="payment_heading">' . esc_html__( 'Payment', 'shop-press' ) . '</h3>';
				$order_button_text = __( 'Place order', 'shop-press' );
			if ( ! wp_doing_ajax() ) {
				do_action( 'woocommerce_review_order_before_payment' );
			}
			?>
				<div id="payment" class="woocommerce-checkout-payment">
					<?php if ( WC()->cart->needs_payment() ) : ?>
						<ul class="wc_payment_methods payment_methods methods">
							<?php
							if ( ! empty( $available_gateways ) ) {
								foreach ( $available_gateways as $gateway ) {
									wc_get_template( 'checkout/payment-method.php', array( 'gateway' => $gateway ) );
								}
							} else {
								echo '<li class="woocommerce-notice woocommerce-notice--info woocommerce-info">' . apply_filters( 'woocommerce_no_available_payment_methods_message', WC()->customer->get_billing_country() ? esc_html__( 'Sorry, it seems that there are no available payment methods for your state. Please contact us if you require assistance or wish to make alternate arrangements.', 'shop-press' ) : esc_html__( 'Please fill in your details above to see available payment methods.', 'shop-press' ) ) . '</li>'; // @codingStandardsIgnoreLine
							}
							?>
						</ul>
					<?php endif; ?>
					<div class="form-row place-order">
						<noscript>
							<?php
							/* translators: $1 and $2 opening and closing emphasis tags respectively */
							printf( esc_html__( 'Since your browser does not support JavaScript, or it is disabled, please ensure you click the %1$sUpdate Totals%2$s button before placing your order. You may be charged more than the amount stated above if you fail to do so.', 'shop-press' ), '<em>', '</em>' );
							?>
							<br/><button type="submit" class="button alt" name="woocommerce_checkout_update_totals" value="<?php esc_attr_e( 'Update totals', 'shop-press' ); ?>"><?php esc_html_e( 'Update totals', 'shop-press' ); ?></button>
						</noscript>

						<?php wc_get_template( 'checkout/terms.php' ); ?>

						<?php do_action( 'woocommerce_review_order_before_submit' ); ?>

						<?php echo apply_filters( 'woocommerce_order_button_html', '<button type="submit" class="button alt" name="woocommerce_checkout_place_order" id="place_order" value="' . esc_attr( $order_button_text ) . '" data-value="' . esc_attr( $order_button_text ) . '">' . esc_html( $order_button_text ) . '</button>' ); // @codingStandardsIgnoreLine ?>

						<?php do_action( 'woocommerce_review_order_after_submit' ); ?>

						<?php wp_nonce_field( 'woocommerce-process_checkout', 'woocommerce-process-checkout-nonce' ); ?>
					</div>
				</div>
				<?php
				if ( ! wp_doing_ajax() ) {
					do_action( 'woocommerce_review_order_after_payment' );
				}
				do_action( 'woocommerce_checkout_after_order_review' );
				?>
		</div>
	</form>
	<?php do_action( 'woocommerce_after_checkout_form', $checkout ); ?>
	</div>

	<div class="sp-nav-wrapper">
		<?php if ( wc_get_page_id( 'shop' ) > 0 && $show_return_to_shop ) : ?>
			<a id="sp-return-to-shop" class="current sp-nav-button" href="<?php echo esc_url( apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) ); ?>">
				<span class="woo-checkout-btn-icon left"><?php echo $arrow; ?></span>
				<?php esc_html_e( 'Return To Shop', 'shop-press' ); ?>
			</a>
		<?php endif; ?>
		<button id="sp-prev" class="sp-nav-button">
			<span class="woo-checkout-btn-icon left"><?php echo $arrow; ?></span>
			<?php esc_html_e( 'Prev', 'shop-press' ); ?>
		</button>
		<?php if ( $show_login ) : ?>
			<button id="sp-next" class="sp-nav-button">
				<?php esc_html_e( 'Next', 'shop-press' ); ?>
				<span class="woo-checkout-btn-icon right"><?php echo $arrow; ?></span>
			</button>
		<?php else : ?>
			<button id="sp-next" class="current sp-nav-button">
				<?php esc_html_e( 'Next', 'shop-press' ); ?>
				<span class="woo-checkout-btn-icon right"><?php echo $arrow; ?></span>
			</button>
		<?php endif; ?>
	</div>
</div>
