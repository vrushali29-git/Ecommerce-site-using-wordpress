<?php
/**
 * Single Ajax Add To Cart.
 *
 * @package ShopPress
 */

namespace ShopPress\Modules;

defined( 'ABSPATH' ) || exit;

class SingleAjaxAddToCart {
	/**
	 * Init.
	 *
	 * @since 1.2.0
	 */
	public static function init() {
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'enqueue_scripts' ), 99 );
		add_action( 'woocommerce_before_add_to_cart_button', array( __CLASS__, 'add_fields_to_add_to_cart_form' ) );
		add_action( 'wp_ajax_shoppress_single_add_to_cart_by_ajax', array( __CLASS__, 'add_to_cart_by_ajax' ), 99 );
		add_action( 'wp_ajax_nopriv_shoppress_single_add_to_cart_by_ajax', array( __CLASS__, 'add_to_cart_by_ajax' ), 99 );
	}

	/**
	 * Enqueue scripts.
	 *
	 * @since 1.2.0
	 */
	public static function enqueue_scripts() {

		if ( ! is_product() ) {
			return;
		}

		wp_enqueue_script( 'sp-single-ajax-add-to-cart' );
	}

	/**
	 * Add to cart by ajax
	 *
	 * @since 1.2.0
	 *
	 * @return void
	 */
	public static function add_to_cart_by_ajax() {
		$product_id   = $_POST['product_id'] ?? false;
		$variation_id = $_POST['variation_id'] ?? false;
		$p_id         = $variation_id ? $variation_id : $product_id;
		$quantity     = $_POST['quantity'] ?? 1;
		if ( is_array( $quantity ) ) {
			$products = $quantity;
		} else {
			$products[ $p_id ] = $quantity;
		}

		$passed_validation = count( $products ) ? true : false;
		foreach ( $products as $product_id => $quantity ) {

			if ( $quantity ) {

				$passed_validation = $passed_validation && apply_filters(
					'woocommerce_add_to_cart_validation',
					true,
					$product_id,
					$quantity
				);
			}
		}

		foreach ( $products as $product_id => $quantity ) {

			$r = $passed_validation ? WC()->cart->add_to_cart( $product_id, $quantity ) : false;
			if ( $r && is_string( $r ) && $quantity ) {
				// wc_add_to_cart_message( array( $product_id => $quantity ), true );
			}
		}

		$message_html = wc_print_notices( true );

		ob_start();
			woocommerce_mini_cart();
		$mini_cart = ob_get_clean();

		$data                 = array(
			'fragments' => apply_filters(
				'woocommerce_add_to_cart_fragments',
				array(
					'div.widget_shopping_cart_content' => '<div class="widget_shopping_cart_content">' . $mini_cart . '</div>',
				)
			),
			'cart_hash' => WC()->cart->get_cart_hash(),
		);
		$data['message_html'] = $message_html;

		wp_send_json( $data );
	}

	/**
	 * Add fields to add to cart form
	 *
	 * @since 1.2.0
	 *
	 * @return void
	 */
	public static function add_fields_to_add_to_cart_form() {

		echo '<input type="hidden" name="action" value="shoppress_single_add_to_cart_by_ajax">';
	}
}
