<?php
/**
 * Templates Fields.
 *
 * @package ShopPress
 */

namespace ShopPress\Admin\PagesFields;

defined( 'ABSPATH' ) || exit;

class Templates {
	/**
	 * Get templates fields.
	 *
	 * @since 1.2.0
	 *
	 * @return array $fields
	 */
	public static function fields() {
		$fields = array(
			array(
				'title'   => __( 'Product Single', 'shop-press' ),
				'name'    => 'single',
				'link'    => 'template=single',
				'icon'    => 'single-component',
				'tooltip' => array(
					'content' => __( 'This component allows you to modify the Single Product page to suit your specific requirements.', 'shop-press' ),
					'link'    => 'https://climaxthemes.com/shoppress/helpdesk/woocommerce-single-product-page/',
				),
			),
			array(
				'title'   => __( 'Shop', 'shop-press' ),
				'name'    => 'shop',
				'link'    => 'template=shop',
				'icon'    => 'shop-component',
				'tooltip' => array(
					'content' => __( 'To modify the display of the Shop page, you can utilize this component.', 'shop-press' ),
					'link'    => 'https://climaxthemes.com/shoppress/helpdesk/woocommerce-shop-page/',
				),
			),
			array(
				'title'   => __( 'Wishlist', 'shop-press' ),
				'name'    => 'wishlist',
				'link'    => 'template=wishlist',
				'icon'    => 'wishlist-component',
				'tooltip' => array(
					'content' => __( 'To lunch your Wishlist page, utilize this component along with its numerous options.', 'shop-press' ),
					'link'    => 'https://climaxthemes.com/shoppress/helpdesk/woocommerce-wishlist/',
				),
			),
			array(
				'title'   => __( 'Checkout', 'shop-press' ),
				'name'    => 'checkout',
				'link'    => 'template=checkout',
				'icon'    => 'checkout-component',
				'tooltip' => array(
					'content' => __( 'You can customize the WooCommerce Checkout page by using this component to suit your preferences and requirements.', 'shop-press' ),
					'link'    => 'https://climaxthemes.com/shoppress/helpdesk/woocommerce-checkout-manager/',
				),
			),
			array(
				'title'   => __( 'Archive', 'shop-press' ),
				'name'    => 'archive',
				'link'    => 'template=archive',
				'icon'    => 'archive-component',
				'tooltip' => array(
					'content' => __( 'Activate this component to edit your product Archive page using Elementor.', 'shop-press' ),
					'link'    => 'https://climaxthemes.com/shoppress/helpdesk/woocommerce-archive-page/',
				),
			),
			array(
				'title'   => __( 'Products Loop', 'shop-press' ),
				'name'    => 'products_loop',
				'link'    => 'template=products_loop',
				'icon'    => 'loop-component',
				'is_pro'  => false,
				'tooltip' => array(
					'content' => __( 'You can use Elementor and special widgets to design how your products are displayed on Loop pages such as Shop, Archive, Related Products and etc.', 'shop-press' ),
					'link'    => 'https://climaxthemes.com/shoppress/helpdesk/woocommerce-products-loop-page/',
				),
			),
			array(
				'title'   => __( 'My Account', 'shop-press' ),
				'name'    => 'my_account',
				'link'    => 'template=my_account_page',
				'icon'    => 'my-account-component',
				'tooltip' => array(
					'content' => __( 'The appearance of the My Account page and each tab of it can be customized in this component.', 'shop-press' ),
					'link'    => 'https://climaxthemes.com/shoppress/helpdesk/woocommerce-my-account-page/',
				),
			),
			array(
				'title'   => __( 'Cart', 'shop-press' ),
				'name'    => 'cart',
				'link'    => 'template=cart',
				'icon'    => 'cart-component',
				'tooltip' => array(
					'content' => __( 'You can customize the WooCommerce Cart page by using this component to suit your preferences and requirements.', 'shop-press' ),
					'link'    => 'https://climaxthemes.com/shoppress/helpdesk/woocommerce-cart-page/',
				),
			),
			array(
				'title'   => __( 'Empty Cart', 'shop-press' ),
				'name'    => 'empty_cart',
				'link'    => 'template=empty_cart',
				'icon'    => 'empty-cart-component',
				'tooltip' => array(
					'content' => __( 'If you want to modify the way the Empty Cart page looks, you can do so using this component.', 'shop-press' ),
					'link'    => 'https://climaxthemes.com/shoppress/helpdesk/woocommerce-empty-cart-page/',
				),
			),
			array(
				'title'   => __( 'Quick View', 'shop-press' ),
				'name'    => 'quick_view',
				'link'    => 'template=quick_view',
				'icon'    => 'quick-view-component',
				'tooltip' => array(
					'content' => __( 'Activate this component to edit the way Quick View modal looks using Elementor.', 'shop-press' ),
					'link'    => 'https://climaxthemes.com/shoppress/helpdesk/woocommerce-product-quick-view/',
				),
			),
			array(
				'title'   => __( 'Compare', 'shop-press' ),
				'name'    => 'compare',
				'link'    => 'template=compare',
				'icon'    => 'compare-component',
				'tooltip' => array(
					'content' => __( 'To modify the display of the Compare page, you can utilize this component.', 'shop-press' ),
					'link'    => 'https://climaxthemes.com/shoppress/helpdesk/woocommerce-product-compare-page/',
				),
			),
			array(
				'title'   => __( 'Thank You', 'shop-press' ),
				'name'    => 'thank_you',
				'link'    => 'template=thank_you',
				'icon'    => 'thank-you-component',
				'tooltip' => array(
					'content' => __( 'After successful payment, a page will be displayed that you can customize according to your needs using this component.', 'shop-press' ),
					'link'    => 'https://climaxthemes.com/shoppress/helpdesk/woocommerce-thank-you-page/',
				),
			),
		);

		$fields = apply_filters( 'shoppress/pages_fields/templates', $fields );

		return $fields;
	}
}
