<?php
/**
 * Rename label.
 *
 * @package ShopPress
 */

namespace ShopPress\Modules;

defined( 'ABSPATH' ) || exit;

class RenameLabel {
	/**
	 * Init.
	 *
	 * @since 1.2.0
	 */
	public static function init() {

		if ( ! sp_get_module_settings( 'rename_label', 'status' ) ) {
			return;
		}

		add_filter( 'woocommerce_product_add_to_cart_text', array( __CLASS__, 'loop_add_to_cart' ), 99, 1 );
		add_filter( 'woocommerce_product_single_add_to_cart_text', array( __CLASS__, 'single_add_to_cart' ), 99, 1 );
		add_filter( 'woocommerce_product_description_tab_title', array( __CLASS__, 'description_tab' ), 99, 1 );
		add_filter( 'woocommerce_product_description_heading', array( __CLASS__, 'description_tab' ), 99, 1 );
		add_filter( 'woocommerce_product_additional_information_tab_title', array( __CLASS__, 'information_tab' ), 99, 1 );
		add_filter( 'woocommerce_product_additional_information_heading', array( __CLASS__, 'information_tab' ), 99, 1 );
		add_filter( 'woocommerce_product_reviews_tab_title', array( __CLASS__, 'reviews_tab' ), 99, 1 );
		add_filter( 'woocommerce_order_button_text', array( __CLASS__, 'order_button' ), 99, 1 );
	}

	/**
	 * Rename loop add to cart.
	 *
	 * @since 1.1.0
	 */
	public static function loop_add_to_cart( $label ) {
		$custom_label = sp_get_module_settings( 'rename_label', 'shop_cart_text' );

		if ( $custom_label ) {
			return __( $custom_label, 'shop-press' );
		}

		return $label;
	}

	/**
	 * Rename single add to cart.
	 *
	 * @since 1.1.0
	 */
	public static function single_add_to_cart( $label ) {
		$custom_label = sp_get_module_settings( 'rename_label', 'single_cart_text' );

		if ( $custom_label ) {
			return __( $custom_label, 'shop-press' );
		}

		return $label;
	}

	/**
	 * Rename description tab.
	 *
	 * @since 1.1.0
	 */
	public static function description_tab( $label ) {
		$custom_label = sp_get_module_settings( 'rename_label', 'single_description_tab_text' );

		if ( $custom_label ) {
			return __( $custom_label, 'shop-press' );
		}

		return $label;
	}

	/**
	 * Rename information tab.
	 *
	 * @since 1.1.0
	 */
	public static function information_tab( $label ) {
		$custom_label = sp_get_module_settings( 'rename_label', 'single_information_tab_text' );

		if ( $custom_label ) {
			return __( $custom_label, 'shop-press' );
		}

		return $label;
	}

	/**
	 * Rename reviews tab.
	 *
	 * @since 1.1.0
	 */
	public static function reviews_tab( $label ) {
		$custom_label = sp_get_module_settings( 'rename_label', 'single_reviews_tab_text' );

		if ( $custom_label ) {
			return __( $custom_label, 'shop-press' );
		}

		return $label;
	}

	/**
	 * Rename order button.
	 *
	 * @since 1.1.0
	 */
	public static function order_button( $label ) {
		$custom_label = sp_get_module_settings( 'rename_label', 'checkout_order_button_text' );

		if ( $custom_label ) {
			return __( $custom_label, 'shop-press' );
		}

		return $label;
	}
}
