<?php
/**
 * Register Widgets.
 *
 * @package ShopPress
 */

namespace ShopPress\Elementor;

defined( 'ABSPATH' ) || exit;

class RegisterWidgets {
	/**
	 * Init Register Widgets.
	 *
	 * @since 1.2.0
	 */
	public static function init() {
		self::hooks();
	}

	/**
	 * Init hooks.
	 *
	 * @since 1.2.0
	 */
	private static function hooks() {
		add_action( 'elementor/widgets/register', array( __CLASS__, 'register_widgets' ) );
	}

	/**
	 * Retrieves the list of widgets.
	 *
	 * @since 1.2.0
	 *
	 * @return array
	 */
	public static function get_widgets_list() {
		$widgets = array(
			'product-collection'        => array(
				'editor_type' => 'general',
				'class_name'  => 'ProductsLoop',
				'is_pro'      => false,
				'path_key'    => 'products/products-loop',
			),
			'cart-table'                => array(
				'editor_type' => 'cart',
				'class_name'  => 'CartTable',
				'is_pro'      => false,
				'path_key'    => 'cart/cart-table',
			),
			'cart-totals'               => array(
				'editor_type' => 'cart',
				'class_name'  => 'CartTotals',
				'is_pro'      => false,
				'path_key'    => 'cart/cart-totals',
			),
			'cart-coupon'               => array(
				'editor_type' => 'cart',
				'class_name'  => 'CartCoupon',
				'is_pro'      => false,
				'path_key'    => 'cart/cart-coupon',
			),
			'cross-sell-products'       => array(
				'editor_type' => 'cart',
				'class_name'  => 'ProductsLoop\CrossSellProducts',
				'is_pro'      => false,
				'path_key'    => 'products/cross-sell-products',
			),
			'cart-empty-message'        => array(
				'editor_type' => 'empty_cart',
				'class_name'  => 'CartEmptyMessage',
				'is_pro'      => false,
				'path_key'    => 'empty-cart/cart-empty-message',
			),
			'return-to-shop'            => array(
				'editor_type' => 'empty_cart',
				'class_name'  => 'ReturnToShop',
				'is_pro'      => false,
				'type'        => 'empty-cart',
				'path_key'    => 'empty-cart/return-to-shop',
			),
			'additional-fields'         => array(
				'editor_type' => 'checkout',
				'class_name'  => 'AdditionalFields',
				'is_pro'      => false,
				'path_key'    => 'checkout/additional-fields',
			),
			'form-billing'              => array(
				'editor_type' => 'checkout',
				'class_name'  => 'FormBilling',
				'is_pro'      => false,
				'path_key'    => 'checkout/form-billing',
			),
			'form-coupon'               => array(
				'editor_type' => 'checkout',
				'class_name'  => 'FormCoupon',
				'is_pro'      => false,
				'path_key'    => 'checkout/form-coupon',
			),
			'login-form'                => array(
				'editor_type' => 'checkout',
				'class_name'  => 'LoginForm',
				'is_pro'      => false,
				'path_key'    => 'checkout/login-form',
			),
			'order-review'              => array(
				'editor_type' => 'checkout',
				'class_name'  => 'OrderReview',
				'is_pro'      => false,
				'path_key'    => 'checkout/order-review',
			),
			'payment-method'            => array(
				'editor_type' => 'checkout',
				'class_name'  => 'PaymentMethod',
				'is_pro'      => false,
				'path_key'    => 'checkout/payment-method',
			),
			'shipping-form'             => array(
				'editor_type' => 'checkout',
				'class_name'  => 'FormShipping',
				'is_pro'      => false,
				'path_key'    => 'checkout/shipping-form',
			),
			'my-account'                => array(
				'editor_type' => 'my_account',
				'class_name'  => 'MyAccount',
				'is_pro'      => false,
				'path_key'    => 'my-account/my-account',
			),
			'dashboard'                 => array(
				'editor_type' => 'dashboard',
				'class_name'  => 'Dashboard',
				'is_pro'      => false,
				'path_key'    => 'my-account/dashboard',
			),
			'addresses'                 => array(
				'editor_type' => 'addresses',
				'class_name'  => 'Addresses',
				'is_pro'      => false,
				'path_key'    => 'my-account/addresses',
			),
			'downloads'                 => array(
				'editor_type' => 'downloads',
				'class_name'  => 'Downloads',
				'is_pro'      => false,
				'path_key'    => 'my-account/downloads',
			),
			'edit-account'              => array(
				'editor_type' => 'account_details',
				'class_name'  => 'EditAccount',
				'is_pro'      => false,
				'path_key'    => 'my-account/edit-account',
			),
			'orders'                    => array(
				'editor_type' => 'orders',
				'class_name'  => 'Orders',
				'is_pro'      => false,
				'path_key'    => 'my-account/orders',
			),
			'login-form'                => array(
				'editor_type' => 'login_register_form',
				'class_name'  => 'MyAccountLogin',
				'is_pro'      => false,
				'path_key'    => 'my-account/login',
			),
			'user-notifications'        => array(
				'editor_type' => 'notifications',
				'class_name'  => 'UserNotifications',
				'is_pro'      => false,
				'path_key'    => 'my-account/notifications',
			),
			'categories-grid'           => array(
				'editor_type' => 'general',
				'class_name'  => 'CategoriesGrid',
				'is_pro'      => false,
				'path_key'    => 'general/categories-grid',
			),
			'orders-tracking'           => array(
				'editor_type' => 'general',
				'class_name'  => 'OrdersTracking',
				'is_pro'      => false,
				'path_key'    => 'general/orders-tracking',
			),
			'shop-products'             => array(
				'editor_type' => 'shop',
				'class_name'  => 'ShopProducts',
				'is_pro'      => false,
				'path_key'    => 'products/shop-products',
			),
			'filters'                   => array(
				'editor_type' => 'shop',
				'class_name'  => 'Filters',
				'is_pro'      => false,
				'path_key'    => 'shop/filters',
			),
			'result-count'              => array(
				'editor_type' => 'shop',
				'class_name'  => 'ResultCount',
				'is_pro'      => false,
				'path_key'    => 'shop/result-count',
			),
			'catalog-ordering'          => array(
				'editor_type' => 'shop',
				'class_name'  => 'CatalogOrdering',
				'is_pro'      => false,
				'path_key'    => 'shop/catalog-ordering',
			),
			'archive-title'             => array(
				'editor_type' => 'archive',
				'class_name'  => 'ArchiveTitle',
				'is_pro'      => false,
				'path_key'    => 'general/archive-title',
			),
			'ajax-search'               => array(
				'editor_type' => 'general',
				'class_name'  => 'AjaxSearch',
				'is_pro'      => false,
				'path_key'    => 'general/ajax-search',
			),
			'single-title'              => array(
				'editor_type' => 'single',
				'class_name'  => 'Title',
				'is_pro'      => false,
				'path_key'    => 'single-product/title',
			),
			'single-price'              => array(
				'editor_type' => 'single',
				'class_name'  => 'Price',
				'is_pro'      => false,
				'path_key'    => 'single-product/price',
			),
			'single-image'              => array(
				'editor_type' => 'single',
				'class_name'  => 'Image',
				'is_pro'      => false,
				'path_key'    => 'single-product/image',
			),
			'single-add-to-cart'        => array(
				'editor_type' => 'single',
				'class_name'  => 'AddToCart',
				'is_pro'      => false,
				'path_key'    => 'single-product/add-to-cart',
			),
			'single-description'        => array(
				'editor_type' => 'single',
				'class_name'  => 'Description',
				'is_pro'      => false,
				'path_key'    => 'single-product/description',
			),
			'single-content'            => array(
				'editor_type' => 'single',
				'class_name'  => 'Content',
				'is_pro'      => false,
				'path_key'    => 'single-product/content',
			),
			'single-rating'             => array(
				'editor_type' => 'single',
				'class_name'  => 'Rating',
				'is_pro'      => false,
				'path_key'    => 'single-product/rating',
			),
			'single-review'             => array(
				'editor_type' => 'single',
				'class_name'  => 'Review',
				'is_pro'      => false,
				'path_key'    => 'single-product/review',
			),
			'single-tags'               => array(
				'editor_type' => 'single',
				'class_name'  => 'Tags',
				'is_pro'      => false,
				'path_key'    => 'single-product/tags',
			),
			'single-categories'         => array(
				'editor_type' => 'single',
				'class_name'  => 'Categories',
				'is_pro'      => false,
				'path_key'    => 'single-product/categories',
			),
			'single-sku'                => array(
				'editor_type' => 'single',
				'class_name'  => 'SKU',
				'is_pro'      => false,
				'path_key'    => 'single-product/sku',
			),
			'single-stock'              => array(
				'editor_type' => 'single',
				'class_name'  => 'Stock',
				'is_pro'      => false,
				'path_key'    => 'single-product/stock',
			),
			'single-stock-progress-bar' => array(
				'editor_type' => 'single',
				'class_name'  => 'StockProgressBar',
				'is_pro'      => false,
				'path_key'    => 'single-product/stock-progress-bar',
			),
			'single-breadcrumb'         => array(
				'editor_type' => 'single',
				'class_name'  => 'Breadcrumb',
				'is_pro'      => false,
				'path_key'    => 'single-product/breadcrumb',
			),
			'single-navigation'         => array(
				'editor_type' => 'single',
				'class_name'  => 'Navigation',
				'is_pro'      => false,
				'path_key'    => 'single-product/navigation',
			),
			'single-attributes'         => array(
				'editor_type' => 'single',
				'class_name'  => 'Attributes',
				'is_pro'      => false,
				'path_key'    => 'single-product/attributes',
			),
			'single-tabs'               => array(
				'editor_type' => 'single',
				'class_name'  => 'Tabs',
				'is_pro'      => false,
				'path_key'    => 'single-product/tabs',
			),
			'single-weight'             => array(
				'editor_type' => 'single',
				'class_name'  => 'Weight',
				'is_pro'      => false,
				'path_key'    => 'single-product/weight',
			),
			'single-call-for-price'     => array(
				'editor_type' => 'single',
				'class_name'  => 'CallForPrice',
				'is_pro'      => false,
				'path_key'    => 'single-product/call-for-price',
			),
			'single-suggest-price'      => array(
				'editor_type' => 'single',
				'class_name'  => 'SuggestPrice',
				'is_pro'      => false,
				'path_key'    => 'single-product/suggest-price',
			),
			'single-sale-badge'         => array(
				'editor_type' => 'single',
				'class_name'  => 'OnSale',
				'is_pro'      => false,
				'path_key'    => 'single-product/sale-badge',
			),
			'single-sharing'            => array(
				'editor_type' => 'single',
				'class_name'  => 'Sharing',
				'is_pro'      => false,
				'path_key'    => 'single-product/sharing',
			),
			'single-related-products'   => array(
				'editor_type' => 'single',
				'class_name'  => 'RelatedProducts',
				'is_pro'      => false,
				'path_key'    => 'products/related-products',
			),
			'up-sell-products'          => array(
				'editor_type' => 'single',
				'class_name'  => 'ProductsLoop\UpSellProducts',
				'is_pro'      => false,
				'path_key'    => 'products/up-sell-products',
			),
			'single-discount'           => array(
				'editor_type' => 'single',
				'class_name'  => 'Discount',
				'is_pro'      => false,
				'path_key'    => 'single-product/discount',
			),
			'single-meta'           => array(
				'editor_type' => 'single',
				'class_name'  => 'Meta',
				'is_pro'      => false,
				'path_key'    => 'single-product/meta',
			),
			'loop-title'                => array(
				'editor_type' => 'loop',
				'class_name'  => 'LoopBuilder\Title',
				'is_pro'      => false,
				'path_key'    => 'loop/title',
			),
			'loop-price'                => array(
				'editor_type' => 'loop',
				'class_name'  => 'LoopBuilder\Price',
				'is_pro'      => false,
				'path_key'    => 'loop/price',
			),
			'loop-thumbnail'            => array(
				'editor_type' => 'loop',
				'class_name'  => 'LoopBuilder\Thumbnail',
				'is_pro'      => false,
				'path_key'    => 'loop/thumbnail',
			),
			'loop-add-to-cart'          => array(
				'editor_type' => 'loop',
				'class_name'  => 'LoopBuilder\AddToCart',
				'is_pro'      => false,
				'path_key'    => 'loop/add-to-cart',
			),
			'loop-description'          => array(
				'editor_type' => 'loop',
				'class_name'  => 'LoopBuilder\Description',
				'is_pro'      => false,
				'path_key'    => 'loop/description',
			),
			'loop-rating'               => array(
				'editor_type' => 'loop',
				'class_name'  => 'LoopBuilder\Rating',
				'is_pro'      => false,
				'path_key'    => 'loop/rating',
			),
			'loop-review'               => array(
				'editor_type' => 'loop',
				'class_name'  => 'LoopBuilder\Review',
				'is_pro'      => false,
				'path_key'    => 'loop/review',
			),
			'loop-tags'                 => array(
				'editor_type' => 'loop',
				'class_name'  => 'LoopBuilder\Tags',
				'is_pro'      => false,
				'path_key'    => 'loop/tags',
			),
			'loop-categories'           => array(
				'editor_type' => 'loop',
				'class_name'  => 'LoopBuilder\Categories',
				'is_pro'      => false,
				'path_key'    => 'loop/categories',
			),
			'loop-sku'                  => array(
				'editor_type' => 'loop',
				'class_name'  => 'LoopBuilder\SKU',
				'is_pro'      => false,
				'path_key'    => 'loop/sku',
			),
			'loop-stock'                => array(
				'editor_type' => 'loop',
				'class_name'  => 'LoopBuilder\Stock',
				'is_pro'      => false,
				'path_key'    => 'loop/stock',
			),
			'loop-sale-flash'           => array(
				'editor_type' => 'loop',
				'class_name'  => 'LoopBuilder\OnSale',
				'is_pro'      => false,
				'path_key'    => 'loop/sale-flash',
			),
			'loop-discount'             => array(
				'editor_type' => 'loop',
				'class_name'  => 'LoopBuilder\Discount',
				'is_pro'      => false,
				'path_key'    => 'loop/discount',
			),
			'recently-viewed-products'  => array(
				'editor_type' => 'general',
				'class_name'  => 'RecentlyViewedProducts',
				'is_pro'      => false,
				'path_key'    => 'general/recently-viewed-products',
			),
			'order-details'             => array(
				'editor_type' => 'thank_you',
				'class_name'  => 'ThankyouOrderDetails',
				'is_pro'      => false,
				'path_key'    => 'thankyou/order-details',
			),
			'order-details-customer'    => array(
				'editor_type' => 'thank_you',
				'class_name'  => 'ThankyouOrderCustomerDetails',
				'is_pro'      => false,
				'path_key'    => 'thankyou/order-details-customer',
			),
			'thankyou-order-review'     => array(
				'editor_type' => 'thank_you',
				'class_name'  => 'ThankyouOrderReview',
				'is_pro'      => false,
				'path_key'    => 'thankyou/order-review',
			),
			'header-toggle'             => array(
				'editor_type' => 'general',
				'class_name'  => 'HeaderToggle',
				'is_pro'      => false,
				'path_key'    => 'general/header-toggle',
			),
			'mini-cart'                 => array(
				'editor_type' => 'general',
				'class_name'  => 'MiniCart',
				'is_pro'      => false,
				'path_key'    => 'general/mini-cart',
			),
			'single-qr'                 => array(
				'editor_type' => 'single',
				'class_name'  => 'QR',
				'is_pro'      => false,
				'path_key'    => 'single-product/qr',
			),
		);

		return apply_filters( 'shoppress/elementor/widgets', $widgets );
	}

	/**
	 * Register Widgets
	 *
	 * @since 1.2.0
	 *
	 * @param Widgets_Manager $widgets_manager Elementor widgets manager.
	 */
	public static function register_widgets( $widgets_manager ) {
		$widgets_list = self::get_widgets_list();

		foreach ( $widgets_list as $key => $widget ) {

			if ( EditorCondition::is_editor_page( $widget['editor_type'] ) ) {

				$widget_path = ( true === $widget['is_pro'] && defined( 'SHOPPRESS_PRO_PATH' ) ) ? SHOPPRESS_PRO_PATH : SHOPPRESS_PATH;
				$custom_path = $widget['custom_path'] ?? false;
				$widget_file = $custom_path ? $custom_path : $widget_path . 'Elementor/widgets/' . $widget['path_key'] . '/config.php';
				if ( file_exists( $widget_file ) ) {
					require_once $widget_file;
				}

				$namespace  = $widget['is_pro'] === true ? 'ShopPressPro' : 'ShopPress';
				$class_name = $namespace . '\\Elementor\\Widgets\\' . $widget['class_name'];
				if ( class_exists( $class_name ) ) {
					$widgets_manager->register( new $class_name() );
				}
			}
		}
	}
}
