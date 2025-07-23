<?php
/**
 * Compatibility with Kata theme.
 *
 * @package ShopPress
 */

namespace ShopPress\Compatibility;

defined( 'ABSPATH' ) || exit;

use Kata;
use Elementor\Plugin as EPlugin;

class KataTheme {
	/**
	 * Assets path of Kata.
	 *
	 * @var string
	 */
	public static $assets;

	/**
	 * Init.
	 *
	 * @since 1.2.0
	 */
	public static function init() {

		// Set assets directory path
		self::$assets = Kata::$assets . 'css/dist/';

		// Enqueue styles hooks
		add_action( 'shoppress/editor/single_preview_styles', array( __CLASS__, 'single_preview_styles' ) );
		add_action( 'shoppress/editor/shop_preview_styles', array( __CLASS__, 'shop_preview_styles' ) );
		add_action( 'shoppress/editor/cart_preview_styles', array( __CLASS__, 'cart_preview_styles' ) );
		add_action( 'shoppress/editor/checkout_preview_styles', array( __CLASS__, 'checkout_preview_styles' ) );
		add_action( 'shoppress/editor/my_account_preview_styles', array( __CLASS__, 'my_account_preview_styles' ) );

		// Dequeue styles hooks
		if ( class_exists( '\Kata_Plus' ) ) {
			add_action( 'wp_enqueue_scripts', array( __CLASS__, 'dequeue_scripts' ), 99 );
			add_filter( 'kata_plus/dynamic_style/dependencies', array( __CLASS__, 'enqueue_sp_frontend_scripts' ), 1, 9 );
		}

		// Fix loop builder
		add_action( 'woocommerce_before_shop_loop', array( __CLASS__, 'woocommerce_before_shop_loop' ) );
		add_action( 'woocommerce_after_shop_loop', array( __CLASS__, 'woocommerce_after_shop_loop' ) );

		// Fix container
		add_action( 'shoppress_full_page_before', array( __CLASS__, 'start_container' ), 10, 1 );
		add_action( 'shoppress_full_page_after', array( __CLASS__, 'close_container' ), 10, 1 );
	}

	/**
	 * Editor styles for single preview.
	 *
	 * @since 1.2.0
	 */
	public static function single_preview_styles() {
		wp_enqueue_style( 'kata-woo-single', self::$assets . 'single-product.css', array(), KATA_VERSION );
	}

	/**
	 * Editor styles for shop preview.
	 *
	 * @since 1.2.0
	 */
	public static function shop_preview_styles() {
		wp_enqueue_style( 'kata-woo-shop', self::$assets . 'shop.css', array(), KATA_VERSION );
	}

	/**
	 * Editor styles for cart preview.
	 *
	 * @since 1.2.0
	 */
	public static function cart_preview_styles() {
		wp_enqueue_style( 'kata-woo-cart', self::$assets . 'cart.css', array(), KATA_VERSION );
		wp_enqueue_style( 'kata-woo-shop', self::$assets . 'shop.css', array(), KATA_VERSION );
	}

	/**
	 * Editor styles for checkout preview.
	 *
	 * @since 1.2.0
	 */
	public static function checkout_preview_styles() {
		wp_enqueue_style( 'kata-woo-checkout', self::$assets . 'checkout.css', array(), KATA_VERSION );
	}

	/**
	 * Editor styles for my account preview.
	 *
	 * @since 1.2.0
	 */
	public static function my_account_preview_styles() {
		wp_enqueue_style( 'kata-woo-my-account', self::$assets . 'my-account.css', array(), KATA_VERSION );
	}

	/**
	 * Start shoppress wrap
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public static function loop_start() {

		if ( is_account_page() || is_single() || is_cart() || is_checkout() ) {
			if ( EPlugin::$instance->editor->is_edit_mode() || 'shoppress_pages' == get_post_type() ) {
				echo '<div id="woocommerce">';
			}
			if ( 'shoppress_loop' === get_post_type() ) {
				echo '<ul class="products"><li class="product">';
			}
		}
	}

	/**
	 * End shoppress wrap
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public static function loop_end() {

		if ( is_account_page() || is_single() || is_cart() || is_checkout() ) {
			if ( 'shoppress_loop' === get_post_type() ) {
				echo '</li></ul>';
			}
			if ( EPlugin::$instance->editor->is_edit_mode() || 'shoppress_pages' == get_post_type() ) {
				echo '</div>';
			}
		}
	}

	/**
	 * Start shoppress wrap
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public static function woocommerce_before_shop_loop() {

		if ( is_woocommerce() || is_cart() || is_checkout() ) {
			if ( EPlugin::$instance->editor->is_edit_mode() || 'shoppress_pages' == get_post_type() ) {
				echo '<div id="woocommerce">';
			}
		}
	}

	/**
	 * End shoppress wrap
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public static function woocommerce_after_shop_loop() {

		if ( is_woocommerce() || is_cart() || is_checkout() ) {
			if ( EPlugin::$instance->editor->is_edit_mode() || 'shoppress_pages' == get_post_type() ) {
				echo '</div>';
			}
		}
	}

	/**
	 * Dequeue Scripts
	 *
	 * @since 1.3.4
	 *
	 * @return void
	 */
	public static function dequeue_scripts() {
		wp_dequeue_style( 'sp-frontend' );

		if ( sp_is_module_active( 'shopify_checkout' ) ) {
			wp_dequeue_style( 'kata-woo-checkout' );
			wp_dequeue_style( 'kata-woo-checkout-rtl' );
		}
	}

	/**
	 * Enqueue sp-frontend Scripts
	 *
	 * @since 1.3.4
	 *
	 * @return void
	 */
	public static function enqueue_sp_frontend_scripts( $deps ) {
		$deps[] = 'sp-frontend';
		return $deps;
	}

	/**
	 * Add container
	 *
	 * @since 1.4.3
	 *
	 * @return void
	 */
	public static function start_container() {
		$cart     = is_cart() && ! sp_is_template_active( 'empty_cart' );
		$checkout = is_checkout() && ! sp_is_template_active( 'checkout' );
		$wishlist = is_sp_wishlist_page() && ! sp_is_template_active( 'wishlist' );
		$compare  = is_sp_compare_page() && ! sp_is_template_active( 'compare' );

		if ( $cart || $wishlist || $compare || $checkout ) {
			echo '<div class="container">';
		}
	}

	/**
	 * Close container
	 *
	 * @since 1.4.3
	 *
	 * @return void
	 */
	public static function close_container() {
		$cart     = is_cart() && ! sp_is_template_active( 'empty_cart' );
		$checkout = is_checkout() && ! sp_is_template_active( 'checkout' );
		$wishlist = is_sp_wishlist_page() && ! sp_is_template_active( 'wishlist' );
		$compare  = is_sp_compare_page() && ! sp_is_template_active( 'compare' );

		if ( $cart || $wishlist || $compare || $checkout ) {
			echo '</div>';
		}
	}
}
