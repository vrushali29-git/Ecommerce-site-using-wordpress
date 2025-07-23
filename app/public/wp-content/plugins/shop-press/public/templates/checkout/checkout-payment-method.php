<?php
/**
 * Payment Method.
 *
 * @package ShopPress
 */

defined( 'ABSPATH' ) || exit;

$hide_title = $args['hide_title'] ?? false;

if ( empty( $_POST ) || sp_is_elementor_editor() ) {

	$policy_desc = isset( $args['policy_desc'] ) ? $args['policy_desc'] : '';
	$method_desc = isset( $args['method_desc'] ) ? $args['method_desc'] : '';

	update_option( 'display_woocommerce_policy_desc_in_checkout_page', $policy_desc );
	update_option( 'display_woocommerce_method_desc_in_checkout_page', $method_desc );

} else {

	$policy_desc = get_option( 'display_woocommerce_policy_desc_in_checkout_page' );
	$method_desc = get_option( 'display_woocommerce_method_desc_in_checkout_page' );

}

if ( ! $policy_desc ) {
	?>
		<style>
			.woocommerce-terms-and-conditions-wrapper { display: none; }
		</style>
	<?php
}

if ( ! $method_desc ) {
	?>
		<style>
			.payment_box { display: none !important; }
		</style>
	<?php
}

if ( ! wp_doing_ajax() || sp_is_elementor_editor() ) {
	do_action( 'woocommerce_review_order_before_payment' );
}

?>
<style>
	.woocommerce #payment .place-order {
	display: block!important;
}
</style>
<div class="sp-checkout-payment-method">

	<?php if ( ! $hide_title ) : ?>
		<h2 class="checkout-step-title"><?php esc_html_e( 'Payment Method', 'shop-press' ); ?></h2>
	<?php endif; ?>

	<?php woocommerce_checkout_payment(); ?>

</div>
<?php

if ( ! wp_doing_ajax() || sp_is_elementor_editor() ) {
	do_action( 'woocommerce_review_order_after_payment' );
}
