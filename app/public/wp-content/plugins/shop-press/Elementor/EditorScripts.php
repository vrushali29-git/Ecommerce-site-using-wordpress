<?php
/**
 * Editor Scripts.
 *
 * @package ShopPress
 */

namespace ShopPress\Elementor;

defined( 'ABSPATH' ) || exit;

class EditorScripts {
	/**
	 * page_id.
	 *
	 * @var string
	 */
	private static $page_id;

	/**
	 * Post type.
	 *
	 * @var string
	 */
	private static $post_type;

	/**
	 * Builder type.
	 *
	 * @var string
	 */
	private static $custom_type;

	/**
	 * SP post types.
	 *
	 * @var string
	 */
	private static $post_types = array( 'shoppress_pages', 'shoppress_myaccount', 'shoppress_loop' );

	/**
	 * Init.
	 *
	 * @since 1.2.0
	 *
	 * @return void
	 */
	public static function init() {
		self::hooks();
		self::definitions();
	}

	/**
	 * Definitions.
	 *
	 * @since 1.2.0
	 *
	 * @return void
	 */
	private static function definitions() {
		self::$page_id     = get_the_ID();
		self::$post_type   = get_post_type();
		self::$custom_type = self::$page_id ? get_post_meta( self::$page_id, 'custom_type', true ) : 'undefined';
	}

	/**
	 * Hooks.
	 *
	 * @since 1.2.0
	 *
	 * @return void
	 */
	private static function hooks() {
		add_action( 'elementor/editor/after_enqueue_scripts', array( __CLASS__, 'editor_scripts' ) );
		add_action( 'elementor/preview/enqueue_styles', array( __CLASS__, 'editor_preview_styles' ), 99 );
	}

	/**
	 * Editor scripts
	 *
	 * @since 1.2.0
	 *
	 * @return void
	 */
	public static function editor_scripts() {
		wp_enqueue_script( 'sp-elementor-editor', SHOPPRESS_URL . 'public/dist/admin/editor.js', array( 'jquery', 'elementor-editor', 'sp-frontend' ), SHOPPRESS_VERSION, true );

		if ( in_array( self::$post_type, self::$post_types ) ) {

			wp_enqueue_style( 'sp-elementor-editor-custom', SHOPPRESS_URL . 'public/admin/elementor/sp-editor.css', null, SHOPPRESS_VERSION );
		}
	}

	/**
	 * Editor styles for preview.
	 *
	 * @since 1.2.0
	 *
	 * @return void
	 */
	public static function editor_preview_styles() {

		if ( 'single' === self::$custom_type || 'quick_view' === self::$custom_type || isset( self::$custom_type[0] ) && 'single' === self::$custom_type[0] ) {

			wp_enqueue_style( 'sp-single' );

			if ( is_rtl() ) {
				wp_enqueue_style( 'sp-single-rtl' );
			}

			do_action( 'shoppress/editor/single_preview_styles' );
		}

		$is_shop = in_array( self::$custom_type, array( 'shop', 'archive' ) );
		if ( $is_shop || 'shoppress_loop' === self::$post_type || in_array( 'custom_page', (array) self::$custom_type ) ) {

			do_action( 'shoppress/editor/shop_preview_styles' );
		}

		if ( 'cart' === self::$custom_type || 'empty_cart' === self::$custom_type || ( isset( self::$custom_type[0] ) && 'cart' === self::$custom_type[0] ) ) {

			wp_enqueue_style( 'sp-cart' );

			if ( is_rtl() ) {
				wp_enqueue_style( 'sp-cart-rtl' );
			}

			do_action( 'shoppress/editor/cart_preview_styles' );
		}

		if ( 'checkout' === self::$custom_type || isset( self::$custom_type[0] ) && 'checkout' === self::$custom_type[0] ) {

			wp_enqueue_style( 'sp-checkout' );

			if ( is_rtl() ) {
				wp_enqueue_style( 'sp-checkout-rtl' );
			}

			do_action( 'shoppress/editor/checkout_preview_styles' );
		}

		if ( 'shoppress_myaccount' === self::$post_type ) {

			wp_enqueue_style( 'sp-my-account' );
			wp_enqueue_style( 'sp-my-account-notifications' );

			if ( is_rtl() ) {
				wp_enqueue_style( 'sp-my-account-notifications-rtl' );
			}

			do_action( 'shoppress/editor/my_account_preview_styles' );
		}
	}
}
