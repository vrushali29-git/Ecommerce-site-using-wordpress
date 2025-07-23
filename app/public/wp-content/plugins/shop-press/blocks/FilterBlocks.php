<?php
/**
 * Filter block.
 *
 * @package ShopPress
 */

namespace ShopPress\BlockEditor;

defined( 'ABSPATH' ) || exit;

use WP_Block_Type_Registry;

class FilterBlocks {
	/**
	 * Init integration blocks.
	 *
	 * @since 1.4.0
	 */
	public static function init() {
		add_filter( 'allowed_block_types_all', array( __CLASS__, 'filter_blocks' ), 10, 2 );
	}

	/**
	 * Filters blocks.
	 *
	 * @since 1.4.0
	 */
	public static function filter_blocks( $allowed_block_types, $editor_context ) {
		$all_blocks  = WP_Block_Type_Registry::get_instance()->get_all_registered();
		$custom_type = get_post_meta( $editor_context->post->ID, 'custom_type', true );
		$post_type   = $editor_context->post->post_type;

		if ( ! $custom_type ) {
			return $allowed_block_types;
		}

		$core_blocks = array_filter(
			array_keys( $all_blocks ),
			function ( $block ) {
				return strpos( $block, 'shop-press/' ) !== 0;
			}
		);

		$single_blocks = array(
			'shop-press/product-title',
			'shop-press/product-price',
			'shop-press/product-add-to-cart',
			'shop-press/product-attributes',
			'shop-press/product-breadcrumb',
			// 'shop-press/product-call-for-price',
			'shop-press/product-categories',
			'shop-press/product-compare',
			'shop-press/product-description',
			'shop-press/product-content',
			'shop-press/product-discount',
			// 'shop-press/product-flash-sale-countdown',
			'shop-press/product-images',
			'shop-press/product-navigation',
			'shop-press/product-rating',
			'shop-press/product-review',
			'shop-press/product-sale-badge',
			// 'shop-press/product-sharing',
			'shop-press/product-size-chart',
			'shop-press/product-sku',
			'shop-press/product-stock',
			// 'shop-press/product-stock-progress-bar',
			// 'shop-press/product-suggest-price',
			'shop-press/product-tags',
			'shop-press/product-weight',
			'shop-press/product-wishlist',
			'shop-press/product-tabs',
		);

		$cart_blocks = array(
			'shop-press/cart-coupon',
			'shop-press/cart-table',
			'shop-press/cart-totals',
		);

		$empty_cart = array(
			'shop-press/cart-empty-message',
			'shop-press/cart-return-to-shop',
		);

		$checkout_blocks = array(
			'shop-press/checkout-additional-fields',
			'shop-press/checkout-form-billing',
			'shop-press/checkout-form-coupon',
			'shop-press/checkout-order-review',
			'shop-press/checkout-payment',
			'shop-press/checkout-login-form',
			'shop-press/checkout-shipping-form',
		);

		$thankyou_blocks = array(
			'shop-press/thankyou-order-details',
			'shop-press/thankyou-details-customer',
			'shop-press/thankyou-order-review',
		);

		$my_account_blocks = array(
			'shop-press/my-account',
		);

		$wishlist_blocks = array(
			'shop-press/my-wishlist',
		);

		$compare_blocks = array(
			'shop-press/compare',
		);

		$shop_blocks = array(
			'shop-press/shop-product',
			'shop-press/shop-filters',
		);

		$dashboard_blocks = array(
			'shop-press/my-account-dashboard',
		);

		$orders_blocks = array(
			'shop-press/my-account-orders',
		);

		$downloads_blocks = array(
			'shop-press/my-account-downloads',
		);

		$addresses_blocks = array(
			'shop-press/my-account-addresses',
		);

		$account_details_blocks = array(
			'shop-press/my-account-edit-account',
		);

		$account_login_blocks = array(
			'shop-press/my-account-login',
		);

		$my_account_blocks = array(
			'shop-press/my-account',
		);

		$my_account_wishlist_blocks = array(
			'shop-press/wishlist_blocks',
		);

		$my_account_notifications_blocks = array(
			'shop-press/my-account-notifications',
		);

		$loop_blocks = array(
			'shop-press/loop-add-to-cart',
			'shop-press/loop-categories',
			'shop-press/loop-compare',
			'shop-press/loop-description',
			'shop-press/loop-discount',
			// 'shop-press/loop-flash-sale-countdown',
			'shop-press/loop-price',
			'shop-press/loop-quick-view',
			'shop-press/loop-rating',
			'shop-press/loop-review',
			'shop-press/loop-sale-flash',
			'shop-press/loop-sku',
			'shop-press/loop-stock',
			'shop-press/loop-title',
			'shop-press/loop-tags',
			'shop-press/loop-thumbnail',
			'shop-press/loop-wishlist',
		);

		if ( $post_type === 'shoppress_pages' ) {

			if ( $custom_type === 'single' || $custom_type === 'quick_view' ) {
				$allowed_block_types = array_merge( $core_blocks, $single_blocks );
			} elseif ( $custom_type === 'cart' ) {
				$allowed_block_types = array_merge( $core_blocks, $cart_blocks );
			} elseif ( $custom_type === 'checkout' ) {
				$allowed_block_types = array_merge( $core_blocks, $checkout_blocks );
			} elseif ( $custom_type === 'shop' || $custom_type === 'archive' ) {
				$allowed_block_types = array_merge( $core_blocks, $shop_blocks );
			} elseif ( $custom_type === 'empty_cart' ) {
				$allowed_block_types = array_merge( $core_blocks, $empty_cart );
			} elseif ( $custom_type === 'wishlist' ) {
				$allowed_block_types = array_merge( $core_blocks, $wishlist_blocks );
			} elseif ( $custom_type === 'compare' ) {
				$allowed_block_types = array_merge( $core_blocks, $compare_blocks );
			} elseif ( $custom_type === 'thank_you' ) {
				$allowed_block_types = array_merge( $core_blocks, $thankyou_blocks );
			}
		}

		if ( $post_type === 'shoppress_myaccount' ) {

			if ( $custom_type === 'dashboard' ) {
				$allowed_block_types = array_merge( $core_blocks, $dashboard_blocks );
			} elseif ( $custom_type === 'orders' ) {
				$allowed_block_types = array_merge( $core_blocks, $orders_blocks );
			} elseif ( $custom_type === 'downloads' ) {
				$allowed_block_types = array_merge( $core_blocks, $downloads_blocks );
			} elseif ( $custom_type === 'addresses' ) {
				$allowed_block_types = array_merge( $core_blocks, $addresses_blocks );
			} elseif ( $custom_type === 'account_details' ) {
				$allowed_block_types = array_merge( $core_blocks, $account_details_blocks );
			} elseif ( $custom_type === 'my_account' ) {
				$allowed_block_types = array_merge( $core_blocks, $my_account_blocks );
			} elseif ( $custom_type === 'my_account_wishlist' ) {
				$allowed_block_types = array_merge( $core_blocks, $my_account_wishlist_blocks );
			} elseif ( $custom_type === 'my_account_notifications' ) {
				$allowed_block_types = array_merge( $core_blocks, $my_account_notifications_blocks );
			} elseif ( $custom_type === 'my_account_wishlist' ) {
				$allowed_block_types = array_merge( $core_blocks, $my_account_wishlist_blocks );
			} elseif ( $custom_type === 'login_register_form' ) {
				$allowed_block_types = array_merge( $core_blocks, $account_login_blocks );
			}
		}

		if ( $post_type === 'shoppress_loop' ) {
			$allowed_block_types = array_merge( $core_blocks, $loop_blocks );
		}

		return $allowed_block_types;
	}
}
