<?php
/**
 * Adds wishlist to WooCommerce my account page.
 *
 * @package ShopPress
 */

namespace ShopPress\Modules\Wishlist;

defined( 'ABSPATH' ) || exit;

class MyAccount {
	/**
	 * Init.
	 *
	 * @since 1.2.0
	 */
	public static function init() {
		add_action( 'init', array( __CLASS__, 'add_wishlist_endpoint' ) );
		add_filter( 'woocommerce_account_menu_items', array( __CLASS__, 'menu_items' ) );
		add_action( 'woocommerce_account_my-wishlist_endpoint', array( __CLASS__, 'wishlist_endpoint' ) );
	}

	/**
	 * Add wishlist endpoint rewrite.
	 *
	 * @since  1.2.0
	 */
	public static function add_wishlist_endpoint() {
		add_rewrite_endpoint( 'my-wishlist', EP_ROOT | EP_PAGES );
	}

	/**
	 * Wishlist endpoint content.
	 *
	 * @since  1.2.0
	 */
	public static function wishlist_endpoint() {
		echo self::get_wishlist_content();
	}

	/**
	 * Returns the wishlist content.
	 *
	 * @since  1.2.0
	 */
	private static function get_wishlist_content() {
		ob_start();

		wp_enqueue_style( 'sp-pr-general' );

		if ( is_rtl() ) {
			wp_enqueue_style( 'sp-pr-general-rtl' );
		}

		wp_enqueue_script( 'sp-wishlist' );
		wp_enqueue_style( 'sp-my-wishlist' );

		if ( is_rtl() ) {
			wp_enqueue_style( 'sp-my-wishlist-rtl' );
		}

		echo '<div id="shoppress-wrap" class="shoppress-wrap">';
		$builder_id = sp_get_template_settings( 'my_account_wishlist', 'page_builder' );

		if ( sp_is_template_active( 'my_account' ) && $builder_id ) {

			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo sp_get_builder_content( $builder_id );
		} else {
			require_once sp_get_template_path( 'wishlist/template' );
		}
		echo '</div>';

		return ob_get_clean();
	}

	/**
	 * Add wishlist link.
	 *
	 * @since  1.2.0
	 */
	public static function menu_items( $links ) {
		$wishlist_text = __( 'Wishlist', 'shop-press' );

		$wishlist = array( 'my-wishlist' => $wishlist_text );

		$links = array_slice( $links, 0, 1, true )
		+ $wishlist
		+ array_slice( $links, 1, null, true );

		return $links;
	}
}
