<?php
/**
 * Register Blocks.
 *
 * @package ShopPress
 */

namespace ShopPress\BlockEditor;

defined( 'ABSPATH' ) || exit;

use ShopPress\Templates;

class RegisterBlocks {
	/**
	 * Init Register Blocks.
	 *
	 * @since 1.4.0
	 */
	public static function init() {
		add_action( 'init', array( __CLASS__, 'register_blocks' ) );
	}

	/**
	 * Retrieves the list of blocks.
	 *
	 * @since 1.4.0
	 *
	 * @return array
	 */
	public static function get_blocks_list() {
		$blocks = array(
			'single-title'                     => array(
				'is_pro' => false,
				'path'   => 'single-product/title',
			),
			'single-price'                     => array(
				'is_pro' => false,
				'path'   => 'single-product/price',
			),
			'single-add-to-cart'               => array(
				'is_pro' => false,
				'path'   => 'single-product/add-to-cart',
			),
			'single-attributes'                => array(
				'is_pro' => false,
				'path'   => 'single-product/attributes',
			),
			'single-tabs'                      => array(
				'is_pro' => false,
				'path'   => 'single-product/tabs',
			),
			'single-breadcrumb'                => array(
				'is_pro' => false,
				'path'   => 'single-product/breadcrumb',
			),
			// 'single-call-for-price'            => array(
			// 'is_pro' => false,
			// 'path'   => 'single-product/call-for-price',
			// ),
			'single-categories'                => array(
				'is_pro' => false,
				'path'   => 'single-product/categories',
			),
			'single-compare'                   => array(
				'is_pro' => false,
				'path'   => 'single-product/compare',
			),
			'single-description'               => array(
				'is_pro' => false,
				'path'   => 'single-product/description',
			),
			'single-content'                   => array(
				'is_pro' => false,
				'path'   => 'single-product/content',
			),
			'single-discount'                  => array(
				'is_pro' => false,
				'path'   => 'single-product/discount',
			),
			// 'single-flash-sale-countdown' => array(
			// 'is_pro' => false,
			// 'path'   => 'single-product/flash-sale-countdown',
			// ),
			'single-images'                    => array(
				'is_pro' => false,
				'path'   => 'single-product/image',
			),
			'single-navigation'                => array(
				'is_pro' => false,
				'path'   => 'single-product/navigation',
			),
			'single-rating'                    => array(
				'is_pro' => false,
				'path'   => 'single-product/rating',
			),
			'single-review'                    => array(
				'is_pro' => false,
				'path'   => 'single-product/review',
			),
			'single-sale-badge'                => array(
				'is_pro' => false,
				'path'   => 'single-product/sale-badge',
			),
			// 'single-sharing' => array(
			// 'is_pro' => false,
			// 'path'   => 'single-product/sharing',
			// ),
			'single-size-chart'                => array(
				'is_pro' => false,
				'path'   => 'single-product/size-chart',
			),
			'single-sku'                       => array(
				'is_pro' => false,
				'path'   => 'single-product/sku',
			),
			'single-stock'                     => array(
				'is_pro' => false,
				'path'   => 'single-product/stock',
			),
			// 'single-stock-progress-bar'        => array(
			// 'is_pro' => false,
			// 'path'   => 'single-product/stock-progress-bar',
			// ),
			// 'single-suggest-price'             => array(
			// 'is_pro' => false,
			// 'path'   => 'single-product/suggest-price',
			// ),
			'single-tags'                      => array(
				'is_pro' => false,
				'path'   => 'single-product/tags',
			),
			'single-weight'                    => array(
				'is_pro' => false,
				'path'   => 'single-product/weight',
			),
			'single-wishlist'                  => array(
				'is_pro' => false,
				'path'   => 'single-product/wishlist',
			),
			'cart-coupon'                      => array(
				'is_pro' => false,
				'path'   => 'cart/cart-coupon',
			),
			'cart-table'                       => array(
				'is_pro' => false,
				'path'   => 'cart/cart-table',
			),
			'cart-totals'                      => array(
				'is_pro' => false,
				'path'   => 'cart/cart-totals',
			),
			'checkout-additional-fields'       => array(
				'is_pro' => false,
				'path'   => 'checkout/additional-fields',
			),
			'checkout-form-billing'            => array(
				'is_pro' => false,
				'path'   => 'checkout/form-billing',
			),
			'checkout-form-coupon'             => array(
				'is_pro' => false,
				'path'   => 'checkout/form-coupon',
			),
			'checkout-order-review'            => array(
				'is_pro' => false,
				'path'   => 'checkout/order-review',
			),
			'checkout-payment-method'          => array(
				'is_pro' => false,
				'path'   => 'checkout/payment-method',
			),
			'checkout-shipping-form'           => array(
				'is_pro' => false,
				'path'   => 'checkout/shipping-form',
			),
			'thankyou-order-details'           => array(
				'is_pro' => false,
				'path'   => 'thankyou/order-details',
			),
			'thankyou-order-details-customers' => array(
				'is_pro' => false,
				'path'   => 'thankyou/order-details-customer',
			),
			'thankyou-order-review'            => array(
				'is_pro' => false,
				'path'   => 'thankyou/order-review',
			),
			'my-account'                       => array(
				'is_pro' => false,
				'path'   => 'my-account/my-account',
			),
			'my-account-addresses'             => array(
				'is_pro' => false,
				'path'   => 'my-account/addresses',
			),
			'my-account-dashboard'             => array(
				'is_pro' => false,
				'path'   => 'my-account/dashboard',
			),
			'my-account-downloads'             => array(
				'is_pro' => false,
				'path'   => 'my-account/downloads',
			),
			'my-account-edit-account'          => array(
				'is_pro' => false,
				'path'   => 'my-account/edit-account',
			),
			'my-account-login'                 => array(
				'is_pro' => false,
				'path'   => 'my-account/login',
			),
			'my-account-notifications'         => array(
				'is_pro' => false,
				'path'   => 'my-account/notifications',
			),
			'my-account-orders'                => array(
				'is_pro' => false,
				'path'   => 'my-account/orders',
			),
			'my-wishlist'                      => array(
				'is_pro' => false,
				'path'   => 'my-wishlist',
			),
			'cart-empty-message'               => array(
				'is_pro' => false,
				'path'   => 'empty-cart/cart-empty-message',
			),
			'cart-return-to-shop'              => array(
				'is_pro' => false,
				'path'   => 'empty-cart/return-to-shop',
			),
			'compare'                          => array(
				'is_pro' => false,
				'path'   => 'compare',
			),
			'shop-product'                     => array(
				'is_pro' => false,
				'path'   => 'product/shop-product',
			),
			'product-collection'               => array(
				'is_pro' => false,
				'path'   => 'product/product-collection',
			),
			'related-product'                  => array(
				'is_pro' => false,
				'path'   => 'product/related',
			),
			'upsell-product'                   => array(
				'is_pro' => false,
				'path'   => 'product/upsell',
			),
			'cross-sell-product'               => array(
				'is_pro' => false,
				'path'   => 'product/cross-sell',
			),
			'loop-add-to-cart'                 => array(
				'is_pro' => false,
				'path'   => 'loop/add-to-cart',
			),
			'loop-categories'                  => array(
				'is_pro' => false,
				'path'   => 'loop/categories',
			),
			'loop-tags'                        => array(
				'is_pro' => false,
				'path'   => 'loop/tags',
			),
			'loop-compare'                     => array(
				'is_pro' => false,
				'path'   => 'loop/compare',
			),
			'loop-description'                 => array(
				'is_pro' => false,
				'path'   => 'loop/description',
			),
			'loop-discount'                    => array(
				'is_pro' => false,
				'path'   => 'loop/discount',
			),
			'loop-price'                       => array(
				'is_pro' => false,
				'path'   => 'loop/price',
			),
			'loop-quick-view'                  => array(
				'is_pro' => false,
				'path'   => 'loop/quick-view',
			),
			'loop-rating'                      => array(
				'is_pro' => false,
				'path'   => 'loop/rating',
			),
			'loop-review'                      => array(
				'is_pro' => false,
				'path'   => 'loop/review',
			),
			'loop-sale-flash'                  => array(
				'is_pro' => false,
				'path'   => 'loop/sale-flash',
			),
			'loop-stock'                       => array(
				'is_pro' => false,
				'path'   => 'loop/stock',
			),
			'loop-sku'                         => array(
				'is_pro' => false,
				'path'   => 'loop/sku',
			),
			'loop-thumbnail'                   => array(
				'is_pro' => false,
				'path'   => 'loop/thumbnail',
			),
			'loop-title'                       => array(
				'is_pro' => false,
				'path'   => 'loop/title',
			),
			'loop-wishlist'                    => array(
				'is_pro' => false,
				'path'   => 'loop/wishlist',
			),
			'shop-filters'                     => array(
				'is_pro' => false,
				'path'   => 'shop/filters',
			),
		);

		return apply_filters( 'shoppress/block_editor/blocks', $blocks );
	}

	/**
	 * Register the builder blocks for WordPress editor.
	 *
	 * @since 1.4.0
	 */
	public static function register_blocks() {
		$blocks = self::get_blocks_list();

		foreach ( $blocks as $key => $block ) {

			register_block_type( SHOPPRESS_PATH . "blocks/build/{$block['path']}" );
		}
	}
}
