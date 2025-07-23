<?php
/**
 * Login Form.
 *
 * @package ShopPress
 */

defined( 'ABSPATH' ) || exit;

if ( is_user_logged_in() || 'no' === get_option( 'woocommerce_enable_checkout_login_reminder' ) ) {
	return;
}

?>
<div class="sp-checkout-login-form">
	<div class="woocommerce-form-login-toggle">
		<?php wc_print_notice( apply_filters( 'woocommerce_checkout_login_message', esc_html__( 'Returning customer?', 'shop-press' ) ) . ' <a href="#" class="showlogin">' . esc_html__( 'Click here to login', 'shop-press' ) . '</a>', 'notice' ); ?>
	</div>
	<?php
	woocommerce_login_form(
		array(
			'message'  => esc_html__( 'If you have shopped with us before, please enter your details below. If you are a new customer, please proceed to the Billing section.', 'shop-press' ),
			'redirect' => wc_get_checkout_url(),
			'hidden'   => true,
		)
	);
	?>
</div>
