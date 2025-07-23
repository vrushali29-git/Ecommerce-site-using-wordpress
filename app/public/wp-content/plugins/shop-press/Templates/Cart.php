<?php
/**
 * Cart Page.
 *
 * @package ShopPress
 */

namespace ShopPress\Templates;

defined( 'ABSPATH' ) || exit;

use Elementor\Plugin;

class Cart {
	/**
	 * Init.
	 *
	 * @since 1.2.0
	 */
	public static function init() {

		if ( ! self::is_cart_builder() ) {
			return;
		}

		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'enqueue' ), 99 );
		add_filter( 'template_include', array( __CLASS__, 'full_template' ) );
		add_filter( 'wc_get_template', array( __CLASS__, 'cart_templates' ), 10, 2 );
		add_action( 'shoppress_cart', array( __CLASS__, 'cart_content' ) );
		add_filter( 'woocommerce_add_to_cart_fragments', array( __CLASS__, 'change_header_woo_cart_number' ), 99 );
		add_filter( 'woocommerce_before_cart', array( __CLASS__, 'before_cart' ) );
		add_filter( 'woocommerce_after_cart', array( __CLASS__, 'after_cart' ) );
	}

	/**
	 * Check cart builder.
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	public static function is_cart_builder() {
		return sp_get_template_settings( 'cart', 'status' ) && sp_get_template_settings( 'cart', 'page_builder' );
	}

	/**
	 * Enqueue scripts.
	 *
	 * @since 1.1.0
	 */
	public static function enqueue() {

		if ( is_cart() && self::is_cart_builder() ) {

			$builder_id = sp_get_template_settings( 'cart', 'page_builder' );

			if ( 'block_editor' === sp_get_builder_type( $builder_id ) ) {

				add_filter(
					'styler/block_editor/post_id',
					function () {
						return sp_get_template_settings( 'cart', 'page_builder' );
					}
				);
			}

			wp_enqueue_style( 'sp-cart' );

			if ( is_rtl() ) {
				wp_enqueue_style( 'sp-cart-rtl' );
			}
		}
	}

	/**
	 * Change woo cart number in header.
	 *
	 * @since 1.1.0
	 */
	public static function change_header_woo_cart_number( $fragments ) {
		global $woocommerce;
		ob_start();
		?>
		<span id="header-cart-count-icon" class="header-cart-count-icon " data-cart_count ="<?php echo esc_attr( $woocommerce->cart->cart_contents_count ); ?>" ><?php echo esc_html( $woocommerce->cart->cart_contents_count ); ?></span>
		<?php
		$fragments['span.header-cart-count-icon'] = ob_get_clean();
		return $fragments;
	}

	/**
	 * Get full template.
	 *
	 * @since 1.2.0
	 */
	public static function full_template( $template ) {

		if ( is_cart() ) {
			$template = sp_get_template_path( 'full-template' );
		}

		return $template;
	}

	/**
	 * Returns the content of the page that has been selected as the cart page.
	 *
	 * @since 1.1.0
	 */
	public static function cart_content() {

		if ( self::is_cart_builder() ) {

			/** Remove cross sell from cart */
			remove_action( 'woocommerce_cart_collaterals', 'woocommerce_cross_sell_display' );
			remove_action( 'woocommerce_after_cart', 'woocommerce_cross_sell_display' );

			$cart_page = sp_get_template_settings( 'cart', 'page_builder' );

			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo sp_get_builder_content( $cart_page );
		}
	}

	/**
	 * Cart templates.
	 *
	 * @since 1.1.0
	 */
	public static function cart_templates( $located, $template_name ) {
		$cart_items_count = ! empty( WC()->cart ) ? WC()->cart->get_cart_contents_count() : 0;
		if ( 'cart/cart.php' === $template_name && is_cart() && self::is_cart_builder() && $cart_items_count ) {
			$located = sp_get_template_path( 'cart/cart' );
		}

		return $located;
	}

	/**
	 * before cart content.
	 *
	 * @since 1.3.1
	 */
	public static function before_cart() {
		echo '<div id="shoppress-wrap" class="shoppress-wrap">';
	}

	/**
	 * after cart content.
	 *
	 * @since 1.3.1
	 */
	public static function after_cart() {
		echo '</div>';
	}
}
