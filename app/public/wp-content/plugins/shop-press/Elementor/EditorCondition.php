<?php
/**
 * Editor Condition.
 *
 * @package ShopPress
 */

namespace ShopPress\Elementor;

defined( 'ABSPATH' ) || exit;

use Elementor\Plugin;

class EditorCondition {
	/**
	 * Post type
	 *
	 * @var string
	 */
	private static $post_type = '';

	/**
	 * Custom type meta
	 *
	 * @var mixed
	 */
	private static $custom_type_meta = '';

	/**
	 * Page ID
	 *
	 * @var string
	 */
	private static $page_id = '';

	/**
	 * Retrieves the post type of the current post.
	 *
	 * @since 1.2.0
	 *
	 * @return string The post type of the current post.
	 */
	private static function get_post_type() {

		if ( empty( self::$post_type ) ) {
			self::$post_type = get_post_type();
		}

		return self::$post_type;
	}

	/**
	 * Retrieves the custom type meta for the current page.
	 *
	 * @since 1.2.0
	 *
	 * @return string The custom type meta for the current page.
	 */
	private static function get_custom_type_meta() {

		if ( empty( self::$page_id ) ) {
			self::$page_id = get_the_ID();
		}

		if ( empty( self::$custom_type_meta ) ) {
			self::$custom_type_meta = get_post_meta( self::$page_id, 'custom_type', true );
		}

		return self::$custom_type_meta;
	}

	/**
	 * Check the custom type of the given editor.
	 *
	 * @since 1.2.0
	 *
	 * @param string $editor The editor to check.
	 *
	 * @return bool
	 */
	private static function check_custom_type( $editor ) {
		return ( in_array( $editor, (array) self::get_custom_type_meta(), true ) );
	}

	/**
	 * Check if is elementor edit mode.
	 *
	 * @since 1.2.0
	 *
	 * @return bool
	 */
	private static function is_edit_mode() {
		return Plugin::$instance->editor->is_edit_mode();
	}

	/**
	 * Determine if the current page is a products page.
	 *
	 * @since 1.2.0
	 *
	 * @return bool
	 */
	private static function is_products() {
		$type = self::get_custom_type_meta();
		return (
			is_shop()
			||
			in_array( $type, array( 'shop', 'archive', 'single' ) )
			||
			(
				in_array( self::get_post_type(), array( 'page', 'post', 'product' ) )
				&&
				! in_array( $type, array( 'cart', 'checkout' ) )
			)
		);
	}

	/**
	 * Returns true for all types of editors.
	 *
	 * @since 1.2.0
	 *
	 * @return bool
	 */
	private static function is_general() {
		return true;
	}

	/**
	 * Determine if is a page or post.
	 *
	 * @since 1.2.0
	 *
	 * @return bool
	 */
	private static function is_page_or_post() {
		return in_array( self::get_post_type(), array( 'page', 'post' ) );
	}

	/**
	 * Determine if the current page is a shop page.
	 *
	 * @since 1.2.0
	 *
	 * @return bool
	 */
	private static function is_shop() {
		return (
			in_array( self::get_custom_type_meta(), array( 'shop', 'archive' ) ) ||
			is_shop() ||
			is_product_category() ||
			is_product_tag() ||
			is_tax( 'shoppress_brand' )
		);
	}

	/**
	 * Determine if the current page is a archive page.
	 *
	 * @since 1.4.3
	 *
	 * @return bool
	 */
	private static function is_archive() {
		return (
			in_array( self::get_custom_type_meta(), array( 'archive' ) ) ||
			is_product_category() ||
			is_product_tag()
		);
	}

	/**
	 * Determine if the current page is a loop page.
	 *
	 * @since 1.2.0
	 *
	 * @return bool
	 */
	private static function is_loop() {

		if ( self::get_post_type() === 'shoppress_loop' ) {
			return true;
		}

		return (
			( false === self::is_edit_mode() || isset( $_POST['action'] ) && 'elementor_ajax' === $_POST['action'] ) &&
			( self::is_general() || self::is_single() || self::is_shop()
		) );
	}

	/**
	 * Determine if the current page is a single page.
	 *
	 * @since 1.2.0
	 *
	 * @return bool
	 */
	private static function is_wishlist() {
		return self::check_custom_type( 'wishlist' ) || self::check_custom_type( 'my_account_wishlist' ) || is_sp_wishlist_page() || is_page();
	}

	/**
	 * Determine if the current page is a compare page.
	 *
	 * @since 1.2.0
	 *
	 * @return bool
	 */
	private static function is_compare() {
		$action = $_REQUEST['action'] ?? false;
		return self::check_custom_type( 'compare' ) || is_sp_compare_page() || in_array( $action, array( 'AddCompare', 'RemoveCompare' ) );
	}

	/**
	 * Determine if the current page is a cart page.
	 *
	 * @since 1.2.0
	 *
	 * @return bool
	 */
	private static function is_cart() {
		return self::check_custom_type( 'cart' ) || is_cart();
	}

	/**
	 * Determine if the current page is a empty cart page.
	 *
	 * @since 1.2.0
	 *
	 * @return bool
	 */
	private static function is_empty_cart() {
		return self::check_custom_type( 'empty_cart' ) || is_cart();
	}

	/**
	 * Determine if the current page is a single page.
	 *
	 * @since 1.2.0
	 *
	 * @return bool
	 */
	private static function is_checkout() {
		return self::check_custom_type( 'checkout' ) || is_checkout();
	}

	/**
	 * Determine if the current page is a thank you page.
	 *
	 * @since 1.2.0
	 *
	 * @return bool
	 */
	private static function is_thank_you() {
		return self::check_custom_type( 'thank_you' ) || is_checkout();
	}

	/**
	 * Determine if the current page is a single page.
	 *
	 * @since 1.2.0
	 *
	 * @return bool
	 */
	private static function is_my_account() {
		return self::check_custom_type( 'my_account' ) || is_account_page();
	}

	/**
	 * Determine if the current page is a dashboard page.
	 *
	 * @since 1.2.0
	 *
	 * @return bool
	 */
	private static function is_dashboard() {
		return self::check_custom_type( 'dashboard' ) || is_account_page();
	}

	/**
	 * Determine if the current page is a account details page.
	 *
	 * @since 1.2.0
	 *
	 * @return bool
	 */
	private static function is_account_details() {
		return self::check_custom_type( 'account_details' ) || is_account_page();
	}

	/**
	 * Determine if the current page is a orders page.
	 *
	 * @since 1.2.0
	 *
	 * @return bool
	 */
	private static function is_orders() {
		return self::check_custom_type( 'orders' ) || is_account_page();
	}

	/**
	 * Determine if the current page is a login page.
	 *
	 * @since 1.4.0
	 *
	 * @return bool
	 */
	private static function is_login_register_form() {
		return self::check_custom_type( 'login_register_form' ) || is_account_page();
	}

	/**
	 * Determine if the current page is a downloads page.
	 *
	 * @since 1.2.0
	 *
	 * @return bool
	 */
	private static function is_downloads() {
		return self::check_custom_type( 'downloads' ) || is_account_page();
	}

	/**
	 * Determine if the current page is a addresses page.
	 *
	 * @since 1.2.0
	 *
	 * @return bool
	 */
	private static function is_addresses() {
		return self::check_custom_type( 'addresses' ) || is_account_page();
	}

	/**
	 * Determine if the current page is a user notifications page.
	 *
	 * @since 1.2.0
	 *
	 * @return bool
	 */
	private static function is_notifications() {
		return self::check_custom_type( 'my_account_notifications' ) || is_account_page();
	}

	/**
	 * Determine if the current page is a single or quick view page.
	 *
	 * @since 1.2.0
	 *
	 * @return bool
	 */
	private static function is_single() {
		return (
			( self::check_custom_type( 'single' ) || is_product() ) ||
			( self::check_custom_type( 'quick_view' ) || is_sp_quick_view_ajax() )
		);
	}

	/**
	 * Checks the current editor page.
	 *
	 * @since 1.2.0
	 *
	 * @param string $editor The editor page to check.
	 *
	 * @return bool
	 */
	public static function is_editor_page( $editor ) {

		if ( self::get_post_type() === 'elementor_library' ) {
			return true;
		}

		$method = "is_{$editor}";

		if ( method_exists( __CLASS__, $method ) ) {
			return self::$method();
		}

		return false;
	}
}
