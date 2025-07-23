<?php
/**
 * Modules Fields.
 *
 * @package ShopPress
 */

namespace ShopPress\Admin\PagesFields;

defined( 'ABSPATH' ) || exit;

class Modules {
	/**
	 * Get modules fields.
	 *
	 * @since 1.2.0
	 *
	 * @return array $fields
	 */
	public static function fields() {
		$fields = array(
			array(
				'title'   => __( 'Wishlist', 'shop-press' ),
				'name'    => 'wishlist',
				'link'    => 'module=wishlist',
				'icon'    => 'wishlist-component',
				'tooltip' => array(
					'content' => __( 'To lunch your Wishlist page, utilize this component along with its numerous options.', 'shop-press' ),
					'link'    => 'https://climaxthemes.com/shoppress/helpdesk/woocommerce-wishlist/',
				),
			),
			array(
				'title'   => __( 'Compare', 'shop-press' ),
				'name'    => 'compare',
				'link'    => 'module=compare',
				'icon'    => 'compare-component',
				'tooltip' => array(
					'content' => __( 'To modify the display of the Compare page, you can utilize this component.', 'shop-press' ),
					'link'    => 'https://climaxthemes.com/shoppress/helpdesk/woocommerce-product-compare-page/',
				),
			),
			array(
				'title'   => __( 'Quick View', 'shop-press' ),
				'name'    => 'quick_view',
				'link'    => 'module=quick_view',
				'icon'    => 'quick-view-component',
				'tooltip' => array(
					'content' => __( 'Activate this component to edit the way Quick View modal looks using Elementor.', 'shop-press' ),
					'link'    => 'https://climaxthemes.com/shoppress/helpdesk/woocommerce-product-quick-view/',
				),
			),
			array(
				'title'   => __( 'Variation Swatches', 'shop-press' ),
				'name'    => 'variation_swatches',
				'link'    => 'module=variation_swatches',
				'icon'    => 'variation-swatches-module',
				'tooltip' => array(
					'content' => __( 'Use this module to convert product variation select fields into radio buttons, images, colors, and labels.', 'shop-press' ),
					'link'    => 'https://climaxthemes.com/shoppress/helpdesk/woocommerce-variation-swatches/',
				),
			),
			array(
				'title'  => __( 'Single Product Variations', 'shop-press' ),
				'name'   => 'variation_as_product',
				'icon'   => 'variation-as-product',
				'link'   => 'module=variation_as_product',
				'is_pro' => true,
				// 'tooltip' => array(
				// 'content' => __( '', 'shop-press' ),
				// 'link'    => '#',
				// ),
			),
			array(
				'title'   => __( 'Sales Notification', 'shop-press' ),
				'name'    => 'sales_notification',
				'link'    => 'module=sales_notification',
				'icon'    => 'sale-notification-module',
				'tooltip' => array(
					'content' => __( 'Display live sales notifications of products on your online store with this module to attract customers.', 'shop-press' ),
					'link'    => 'https://climaxthemes.com/shoppress/helpdesk/woocommerce-sales-notification/',
				),
				'is_pro'  => true,
			),
			array(
				'title'  => __( 'Linked Variations', 'shop-press' ),
				'name'   => 'linked_variations',
				'link'   => 'module=linked_variations',
				'icon'   => 'sale-linked-variations',
				// 'tooltip' => array(
				// 'content' => __( 'Display live sales notifications of products on your online store with this module to attract customers.', 'shop-press' ),
				// 'link'    => 'https://climaxthemes.com/shoppress/helpdesk/woocommerce-sales-notification/',
				// ),
				'is_pro' => true,
			),
			array(
				'title'   => __( 'Size Chart', 'shop-press' ),
				'name'    => 'size_chart',
				'link'    => 'module=size_chart',
				'icon'    => 'size-chart-module',
				'tooltip' => array(
					'content' => __( "Enhance your customer's shopping experience by providing a Size Chart using this module.", 'shop-press' ),
					'link'    => 'https://climaxthemes.com/shoppress/helpdesk/woocommerce-size-chart/',
				),
			),
			array(
				'title'   => __( 'Multi Step Checkout', 'shop-press' ),
				'name'    => 'multi_step_checkout',
				'link'    => 'module=multi_step_checkout',
				'icon'    => 'multi-step-checkout',
				'tooltip' => array(
					'content' => __( 'Improve user experience by dividing the checkout process into multiple steps.', 'shop-press' ),
					'link'    => 'https://climaxthemes.com/shoppress/helpdesk/woocommerce-multi-step-checkout/',
				),
			),
			array(
				'title'   => __( 'Shopify Style Checkout', 'shop-press' ),
				'name'    => 'shopify_checkout',
				'link'    => 'module=shopify_checkout',
				'icon'    => 'shopify-checkout',
				'tooltip' => array(
					'content' => __( "Shopify's checkout page is considered one of the best in the eCommerce industry. Simply enable this module to utilize it.", 'shop-press' ),
					'link'    => 'https://climaxthemes.com/shoppress/helpdesk/shopify-style-checkout-page/',
				),
			),
			array(
				'title'   => __( 'Brands', 'shop-press' ),
				'name'    => 'brands',
				'link'    => 'module=brands',
				'icon'    => 'brands-module',
				'tooltip' => array(
					'content' => __( "This module enables you to add brands to your shop's products and display them in various ways.", 'shop-press' ),
					'link'    => 'https://climaxthemes.com/shoppress/helpdesk/woocommerce-brands/',
				),
				'is_pro'  => true,
			),
			array(
				'title'   => __( 'Rename Label', 'shop-press' ),
				'name'    => 'rename_label',
				'link'    => 'module=rename_label',
				'icon'    => 'rename-label-module',
				'tooltip' => array(
					'content' => __( 'This module enables you to customize the translation strings of your online store to suit your preferences.', 'shop-press' ),
					'link'    => 'https://climaxthemes.com/shoppress/helpdesk/rename-label/',
				),
			),
			array(
				'title'   => __( 'Backorder', 'shop-press' ),
				'name'    => 'backorder',
				'link'    => 'module=backorder',
				'icon'    => 'backorder-module',
				'tooltip' => array(
					'content' => __( 'Using this module you can let the customers to order products that are currently out of stock but will become available soon.', 'shop-press' ),
					'link'    => 'https://climaxthemes.com/shoppress/helpdesk/woocommerce-backorder/',
				),
			),
			array(
				'title'   => __( 'Flash Sale Countdown', 'shop-press' ),
				'name'    => 'flash_sale_countdown',
				'link'    => 'module=flash_sale_countdown',
				'icon'    => 'flash-countdown-module',
				'tooltip' => array(
					'content' => __( 'This tool creates a sense of urgency by displaying a countdown on product pages, motivating customers to make a purchase.', 'shop-press' ),
					'link'    => 'https://climaxthemes.com/shoppress/helpdesk/woocommerce-flash-sale-countdown/',
				),
			),
			array(
				'title'   => __( 'Pre-Order', 'shop-press' ),
				'name'    => 'pre_order',
				'link'    => 'module=pre_order',
				'icon'    => 'pre-order',
				'tooltip' => array(
					'content' => __( 'Using this module you can give customers the option of ordering products ahead of time, even before they are officially available.', 'shop-press' ),
					'link'    => 'https://climaxthemes.com/shoppress/helpdesk/woocommerce-pre-orders/',
				),
				'is_pro'  => true,
			),
			array(
				'title'   => __( 'Partial Payment', 'shop-press' ),
				'name'    => 'partial_payment',
				'link'    => 'module=partial_payment',
				'icon'    => 'partial-payment-module',
				'tooltip' => array(
					'content' => __( 'This module adds a a payment method that enables you to receive part of the total price in an initial payment and then decide how to get the rest of it.', 'shop-press' ),
					'link'    => 'https://climaxthemes.com/shoppress/helpdesk/woocommerce-partial-payment/',
				),
				'is_pro'  => true,
			),
			array(
				'title'   => __( 'Cross Sell Popup', 'shop-press' ),
				'name'    => 'cross_sell',
				'icon'    => 'cross-sell-module',
				'link'    => 'module=cross_sell',
				'tooltip' => array(
					'content' => __( 'As a retail strategy, the Cross-sell module involves offering complementary items for sale alongside the primary purchase.	', 'shop-press' ),
					'link'    => 'https://climaxthemes.com/shoppress/helpdesk/woocommerce-cross-sell-products/',
				),
				'is_pro'  => true,
			),
			array(
				'title'   => __( 'Order Bump', 'shop-press' ),
				'name'    => 'order_bump',
				'icon'    => 'order-bump',
				'link'    => 'module=order_bump',
				'tooltip' => array(
					'content' => __( "This module helps increase your site's Average Order Value by presenting last-minute offers to customers.", 'shop-press' ),
					'link'    => 'https://climaxthemes.com/shoppress/helpdesk/woocommerce-order-bump/',
				),
				'is_pro'  => true,
			),
			array(
				'title'   => __( 'Menu Cart', 'shop-press' ),
				'name'    => 'menu_cart',
				'icon'    => 'menu-cart',
				'link'    => 'module=menu_cart',
				'tooltip' => array(
					'content' => __( "Activate this module to show the card button in your website's menu.", 'shop-press' ),
					'link'    => 'https://climaxthemes.com/shoppress/helpdesk/woocommerce-menu-cart/',
				),
			),
			array(
				'title'   => __( 'Mini Cart Drawer', 'shop-press' ),
				'name'    => 'mini_cart_drawer',
				'icon'    => 'mini-cart-drawer',
				'link'    => 'module=mini_cart_drawer',
				'is_pro'  => true,
				'tooltip' => array(
					'content' => __( 'Activate this module to show the card drawer button in your website.', 'shop-press' ),
					'link'    => 'https://climaxthemes.com/shoppress/helpdesk/woocommerce-mini-cart-drawer/',
				),
			),
			array(
				'title'   => __( 'Sticky Add to Cart', 'shop-press' ),
				'name'    => 'sticky_add_to_cart',
				'icon'    => 'sticky-cart-module',
				'link'    => 'module=sticky_add_to_cart',
				'tooltip' => array(
					'content' => __( 'With this module, you can make the Add to Cart button sticky on the single product page, improving the user experience for your customers.', 'shop-press' ),
					'link'    => 'https://climaxthemes.com/shoppress/helpdesk/woocommerce-sticky-add-to-cart/',
				),
			),
			array(
				'title'   => __( 'Product Gallery', 'shop-press' ),
				'name'    => 'product_gallery',
				'icon'    => 'product-gallery',
				'link'    => 'module=product_gallery',
				'is_pro'  => true,
				'tooltip' => array(
					'content' => __( 'You can use this module to have better control over the product image gallery and use new layouts.', 'shop-press' ),
					'link'    => 'https://climaxthemes.com/shoppress/helpdesk/woocommerce-product-gallery/',
				),
			),
			array(
				'title'   => __( 'Product Badges', 'shop-press' ),
				'name'    => 'product_badges',
				'icon'    => 'product-badges',
				'link'    => 'module=product_badges',
				'is_pro'  => true,
				'tooltip' => array(
					'content' => __( 'With this module, you can easily create custom badges to display on single product and product loops pages.', 'shop-press' ),
					'link'    => 'https://climaxthemes.com/shoppress/helpdesk/woocommerce-product-badges/',
				),
			),
			array(
				'title'   => __( 'Quick Checkout', 'shop-press' ),
				'name'    => 'quick_checkout',
				'icon'    => 'quick-checkout',
				'tooltip' => array(
					'content' => __( 'Optimize the checkout process and improve user experience with the Quick Checkout module to boost conversion rates.', 'shop-press' ),
					'link'    => 'https://climaxthemes.com/shoppress/helpdesk/woocommerce-quick-checkout/',
				),
				'link'    => 'module=quick_checkout',
				'is_pro'  => true,
			),
			array(
				'title'   => __( 'Product Tabs Manager', 'shop-press' ),
				'name'    => 'product_tabs_manager',
				'icon'    => 'product-tabs-manager',
				'tooltip' => array(
					'content' => __( 'Using this module, you can create custom tabs for all products or a specific product. You can also sort default WooCommerce and custom product tabs as per your preference.', 'shop-press' ),
					'link'    => 'https://climaxthemes.com/shoppress/helpdesk/woocommerce-product-tabs-manager/',
				),
				'link'    => 'module=product_tabs_manager',
				'is_pro'  => true,
			),
			array(
				'title'   => __( 'Mobile Panel', 'shop-press' ),
				'name'    => 'mobile_panel',
				'icon'    => 'mobile-panel-module',
				'link'    => 'module=mobile_panel',
				'tooltip' => array(
					'content' => __( 'Enabling this module showcases a visually appealing and easily accessible bar at the bottom of web pages on any device with a screen size of 768px or less.', 'shop-press' ),
					'link'    => 'https://climaxthemes.com/shoppress/helpdesk/woocommerce-mobile-panel/',
				),
			),
			array(
				'title'   => __( 'Frequently Bought Together', 'shop-press' ),
				'name'    => 'frequently_bt',
				'icon'    => 'frequently-bought-together',
				'tooltip' => array(
					'content' => __( 'Frequently Bought Together', 'shop-press' ),
					'link'    => '',
				),
				'link'    => 'module=frequently_bt',
				'is_pro'  => true,
			),
			array(
				'title'   => __( 'Catalog Mode', 'shop-press' ),
				'name'    => 'catalog_mode',
				'icon'    => 'catalog-mode-module',
				'tooltip' => array(
					'content' => __( 'For users who want to display their products on WooCommerce without selling online, we offer the WooCommerce Catalog Mode. ', 'shop-press' ),
					'link'    => 'https://climaxthemes.com/shoppress/helpdesk/woocommerce-catalog-mode/',
				),
				'link'    => 'module=catalog_mode',
			),
			array(
				'title'   => __( 'Single Ajax Add to Cart', 'shop-press' ),
				'name'    => 'single_ajax_add_to_cart',
				'icon'    => 'single-ajax-add-to-cart',
				'tooltip' => array(
					'content' => __( 'Activate this option to enable Ajax for the Add to Cart button on the Single Product page.', 'shop-press' ),
					'link'    => 'https://climaxthemes.com/shoppress/helpdesk/single-ajax-add-to-cart/',
				),
			),
			array(
				'title'   => __( 'User Notifications', 'shop-press' ),
				'name'    => 'notifications',
				'icon'    => 'user-notification-module',
				'tooltip' => array(
					'content' => __( 'With this module, you can show order details on the My Account page and enable customers to keep track of their orders every step of the way.', 'shop-press' ),
					'link'    => 'https://climaxthemes.com/shoppress/helpdesk/woocommerce-user-notification/',
				),
			),
		);

		$fields = apply_filters( 'shoppress/pages_fields/modules', $fields );

		return $fields;
	}
}
