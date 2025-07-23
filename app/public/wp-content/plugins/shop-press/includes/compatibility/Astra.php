<?php
/**
 * Compatibility with astra theme.
 *
 * @package ShopPress
 */

namespace ShopPress\Compatibility;

defined( 'ABSPATH' ) || exit;

class Astra {
	/**
	 * Init.
	 *
	 * @since 1.1.0
	 */
	public static function init() {
		remove_action( 'wp', array( \Astra_Woocommerce::get_instance(), 'woocommerce_checkout' ) );
		remove_filter( 'woocommerce_sale_flash', array( \Astra_Woocommerce::get_instance(), 'sale_flash' ) );
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'enqueue' ) );
		add_action( 'elementor/preview/enqueue_styles', array( __CLASS__, 'astra_styles' ) );
	}

	/**
	 * Enqueue style.
	 *
	 * @since 1.1.0
	 */
	public static function enqueue() {

		if ( is_checkout() ) {
			wp_enqueue_style( 'sp-astra-checkout' );
		}

		if ( is_cart() ) {
			wp_enqueue_style( 'sp-astra-cart' );
		}

		if ( is_singular( 'product' ) ) {
			wp_enqueue_style( 'sp-astra-single' );
		}
	}

	/**
	 * Astra styles.
	 *
	 * @since 1.2.5
	 */
	public static function astra_styles() {
		wp_enqueue_style( 'sp-astra-my-account' );
	}
}
