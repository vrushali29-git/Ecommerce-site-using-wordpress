<?php
/**
 * My Account
 *
 * @package ShopPress\Templates
 *
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

get_header();

global $wp;

do_action( 'shoppress/templates/my_account/before_content' );

if ( ! is_user_logged_in() || isset( $wp->query_vars['lost-password'] ) ) {
	$message = apply_filters( 'woocommerce_my_account_message', '' );

	if ( ! empty( $message ) ) {
		wc_add_notice( $message );
	}

	// After password reset, add confirmation message.
	if ( ! empty( $_GET['password-reset'] ) ) { // WPCS: input var ok, CSRF ok.
		wc_add_notice( __( 'Your password has been reset successfully.', 'shop-press' ) );
	}

	echo '<div class="woocommerce container">';
	if ( isset( $wp->query_vars['lost-password'] ) ) {
		WC_Shortcode_My_Account::lost_password();
	} else {
		wc_get_template( 'myaccount/form-login.php' );
	}
	echo '</div>';
} else {
	do_action( 'shoppress_my_account' );
}

do_action( 'shoppress/templates/my_account/after_content' );

get_footer();
