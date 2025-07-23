<?php
/**
 * Multi step checkout.
 *
 * @package ShopPress
 */

namespace ShopPress\Modules;

defined( 'ABSPATH' ) || exit;

class MultiStep {
	/**
	 * Init.
	 *
	 * @since  1.2.0
	 */
	public static function init() {

		if ( ! sp_get_module_settings( 'multi_step_checkout', 'status' ) ) {
			return;
		}
		remove_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_login_form', 10 );
		remove_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form', 10 );
		remove_action( 'woocommerce_checkout_order_review', 'woocommerce_order_review', 10 );
		remove_action( 'woocommerce_checkout_order_review', 'woocommerce_checkout_payment', 20 );
		remove_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_login_form', 10 );
		add_action( 'sp-woocommerce_order_review', 'woocommerce_order_review', 20 );
		add_action( 'sp-woocommerce_checkout_payment', 'woocommerce_checkout_payment', 10 );
		add_action( 'sp-woocommerce_checkout_login_form', 'woocommerce_checkout_login_form', 10 );
		add_action( 'sp-woocommerce_checkout_coupon_form', 'woocommerce_checkout_coupon_form', 10 );
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'enqueue_scripts' ), 99 );
		add_filter( 'wc_get_template', array( __CLASS__, 'wc_custom_templates' ), 30, 3 );
		add_filter( 'woocommerce_checkout_fields', array( __CLASS__, 'filter_checkout_fields' ), 9999 );
	}

	/**
	 * Load WC custom templates.
	 *
	 * @since  1.0.0
	 */
	public static function wc_custom_templates( $located, $template_name ) {

		if ( 'checkout/form-checkout.php' === $template_name ) {
			$located = sp_get_template_path( 'checkout/multi-step-checkout-form' );
		}

		return $located;
	}

	/**
	 * enqueue scripts.
	 *
	 * @since  1.0.0
	 */
	public static function enqueue_scripts() {

		if ( is_checkout() ) {

			wp_enqueue_script( 'sp-multi-step-checkout' );
			wp_enqueue_style( 'sp-multi-step-checkout' );

			if ( is_rtl() ) {
				wp_enqueue_style( 'sp-multi-step-checkout-rtl' );
			}

			wp_enqueue_style( 'sp-checkout' );

			if ( is_rtl() ) {
				wp_enqueue_style( 'sp-checkout-rtl' );
			}
		}
	}

	/**
	 * Filter checkout fields.
	 *
	 * @param array $fields
	 *
	 * @since 1.3.7
	 */
	public static function filter_checkout_fields( $fields ) {

		$remove_billing_address_2  = sp_get_module_settings( 'multi_step_checkout', 'remove_billing_address_2' );
		$remove_shipping_address_2 = sp_get_module_settings( 'multi_step_checkout', 'remove_shipping_address_2' );

		if ( $remove_billing_address_2 ) {

			unset( $fields['billing']['billing_address_2'] );
		}

		if ( $remove_shipping_address_2 ) {

			unset( $fields['shipping']['shipping_address_2'] );
		}

		return $fields;
	}
}
