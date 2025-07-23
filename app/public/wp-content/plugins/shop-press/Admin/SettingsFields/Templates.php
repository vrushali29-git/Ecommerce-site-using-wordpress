<?php
/**
 * Templates Fields.
 *
 * @package ShopPress
 */

namespace ShopPress\Admin\SettingsFields;

defined( 'ABSPATH' ) || exit;

class Templates {
	/**
	 * Get wishlist fields.
	 *
	 * @since 1.2.0
	 *
	 * @return array $wishlist_fields
	 */
	public static function wishlist() {
		$wishlist_fields = array(
			'heading' => array(
				'title'     => __( 'Wishlist', 'shop-press' ),
				'component' => 'heading',
			),
			'builder' => array(
				'component' => 'pages',
				'type'      => 'shoppress_pages',
			),
		);

		return $wishlist_fields;
	}

	/**
	 * Get single product fields.
	 *
	 * @since 1.2.0
	 *
	 * @return array $product_single_fields
	 */
	public static function single() {
		$product_single_fields = array(
			'heading' => array(
				'title'     => __( 'Product Single', 'shop-press' ),
				'component' => 'heading',
			),
			// 'templates_by_category'  => array(
			// 'title'     => __( 'Custom Single Template Per Category', 'shop-press' ),
			// 'name'      => 'templates_by_category',
			// 'default'   => false,
			// 'tooltip'   => array(
			// 'content' => __( 'On the product category editing page, you can select a template products of that category.', 'shop-press' ),
			// 'link'    => 'https://climaxthemes.com/shoppress/helpdesk/woocommerce-single-product-page/',
			// ),
			// 'component' => 'switch',
			// 'className' => array( 'switch-right' ),
			// ),
			// 'custom_single_template' => array(
			// 'title'     => __( 'Custom Single Template Per Product', 'shop-press' ),
			// 'name'      => 'custom_single_template',
			// 'default'   => false,
			// 'tooltip'   => array(
			// 'content' => __( 'When you activate this option, you have the ability to select a specific template for each product individually.', 'shop-press' ),
			// 'link'    => 'https://climaxthemes.com/shoppress/helpdesk/woocommerce-single-product-page/',
			// ),
			// 'component' => 'switch',
			// 'className' => array( 'switch-right' ),
			// ),
			// 'product_video'  => array(
			// 'title'     => __( 'Product Video', 'shop-press' ),
			// 'name'      => 'product_video',
			// 'default'   => false,
			// 'tooltip'   => array(
			// 'content' => __( 'Adds a new option in the product edit tab in order to add a video link for the product. You can add the video widget using Elementor to the product single.', 'shop-press' ),
			// 'link'      => 'https://climaxthemes.com/shoppress/helpdesk/woocommerce-single-product-page/',
			// ),
			// 'component' => 'switch',
			// 'className' => array( 'switch-right' ),
			// ),
			'pages'   => array(
				'component' => 'pages',
				'type'      => 'shoppress_pages',
			),
		);

		return $product_single_fields;
	}

	/**
	 * Get shop fields.
	 *
	 * @since 1.2.0
	 *
	 * @return array $shop_fields
	 */
	public static function shop() {
		$shop_fields = array(
			'heading' => array(
				'title'     => __( 'Shop', 'shop-press' ),
				'component' => 'heading',
			),
			'pages'   => array(
				'component' => 'pages',
				'type'      => 'shoppress_pages',
			),
		);

		return $shop_fields;
	}

	/**
	 * Get checkout fields.
	 *
	 * @since 1.2.0
	 *
	 * @return array $checkout_fields
	 */
	public static function checkout() {
		$checkout_fields = array(
			'heading' => array(
				'title'     => __( 'Checkout', 'shop-press' ),
				'component' => 'heading',
			),
			'pages'   => array(
				'component' => 'pages',
				'type'      => 'shoppress_pages',
			),
		);

		return $checkout_fields;
	}

	/**
	 * Get archive fields.
	 *
	 * @since 1.2.0
	 *
	 * @return array $archive_fields
	 */
	public static function archive() {
		$archive_fields = array(
			'heading' => array(
				'title'     => __( 'Archive', 'shop-press' ),
				'component' => 'heading',
			),
			'pages'   => array(
				'component' => 'pages',
				'type'      => 'shoppress_pages',
			),
		);

		return $archive_fields;
	}

	/**
	 * Get my account fields.
	 *
	 * @since 1.2.0
	 *
	 * @return array $my_account_fields
	 */
	public static function my_account_page() {
		$my_account_fields = array(
			array(
				'title'       => __( 'My Account', 'shop-press' ),
				'description' => __( 'Activate the builder of My Account Page elements so that the layout you have created with Elementor replaces the original layout.', 'shop-press' ),
				'component'   => 'heading',
			),
			array(
				'title'     => __( 'My Account Page', 'shop-press' ),
				'component' => 'link',
				'link'      => 'my_account',
				'icon'      => 'my-account-page-icon',
				'parent'    => 'Templates',
			),
			array(
				'title'     => __( 'Dashboard', 'shop-press' ),
				'component' => 'link',
				'link'      => 'dashboard',
				'icon'      => 'account-dashboard',
				'parent'    => 'Templates',
			),
			array(
				'title'     => __( 'Account Details', 'shop-press' ),
				'component' => 'link',
				'link'      => 'account_details',
				'icon'      => 'account-details',
				'parent'    => 'Templates',
			),
			array(
				'title'     => __( 'Orders', 'shop-press' ),
				'component' => 'link',
				'link'      => 'orders',
				'icon'      => 'account-orders',
				'parent'    => 'Templates',
			),
			array(
				'title'     => __( 'Downloads', 'shop-press' ),
				'component' => 'link',
				'link'      => 'downloads',
				'icon'      => 'account-downloads',
				'parent'    => 'Templates',
			),
			array(
				'title'     => __( 'Addresses', 'shop-press' ),
				'component' => 'link',
				'link'      => 'addresses',
				'icon'      => 'account-address',
				'parent'    => 'Templates',
			),
			array(
				'title'     => __( 'Login / Register', 'shop-press' ),
				'component' => 'link',
				'link'      => 'login_register_form',
				'icon'      => 'account-login',
				'parent'    => 'Templates',
			),
			array(
				'title'      => __( 'Wishlist', 'shop-press' ),
				'component'  => 'link',
				'link'       => 'my_account_wishlist',
				'icon'       => 'account-wishlist',
				'parent'     => 'Templates',
				'conditions' => array(
					'parent' => 'modules',
					'name'   => 'wishlist',
					'terms'  => array(
						array(
							'name'     => 'status',
							'operator' => '===',
							'value'    => true,
						),
					),
				),
			),
			array(
				'title'      => __( 'User Notifications', 'shop-press' ),
				'component'  => 'link',
				'link'       => 'my_account_notifications',
				'icon'       => 'account-notifications',
				'parent'     => 'Templates',
				'conditions' => array(
					'parent' => 'modules',
					'name'   => 'notifications',
					'terms'  => array(
						array(
							'name'     => 'status',
							'operator' => '===',
							'value'    => true,
						),
					),
				),
			),
		);

		return $my_account_fields;
	}

	/**
	 * Get my account fields.
	 *
	 * @since 1.2.0
	 *
	 * @return array
	 */
	public static function my_account() {
		return Utils::builder_fields(
			__( 'My Account', 'shop-press' ),
			'shoppress_myaccount',
		);
	}

	/**
	 * Get account details fields.
	 *
	 * @since 1.2.0
	 *
	 * @return array
	 */
	public static function account_details() {
		return Utils::builder_fields(
			__( 'Account Details', 'shop-press' ),
			'shoppress_myaccount',
		);
	}

	/**
	 * Get addresses fields.
	 *
	 * @since 1.2.0
	 *
	 * @return array
	 */
	public static function addresses() {
		return Utils::builder_fields(
			__( 'Addresses', 'shop-press' ),
			'shoppress_myaccount',
		);
	}

	/**
	 * Get login and register forms fields.
	 *
	 * @since 1.4.0
	 *
	 * @return array
	 */
	public static function login_register_form() {
		return Utils::builder_fields(
			__( 'Login / Register', 'shop-press' ),
			'shoppress_myaccount',
		);
	}

	/**
	 * Get downloads fields.
	 *
	 * @since 1.2.0
	 *
	 * @return array
	 */
	public static function downloads() {
		return Utils::builder_fields(
			__( 'Downloads', 'shop-press' ),
			'shoppress_myaccount',
		);
	}

	/**
	 * Get orders fields.
	 *
	 * @since 1.2.0
	 *
	 * @return array
	 */
	public static function orders() {
		return Utils::builder_fields(
			__( 'Orders', 'shop-press' ),
			'shoppress_myaccount',
		);
	}

	/**
	 * Get dashboard fields.
	 *
	 * @since 1.2.0
	 *
	 * @return array
	 */
	public static function dashboard() {
		return Utils::builder_fields(
			__( 'Dashboard', 'shop-press' ),
			'shoppress_myaccount',
		);
	}

	/**
	 * Get wishlist fields.
	 *
	 * @since 1.2.0
	 *
	 * @return array
	 */
	public static function my_account_wishlist() {
		return Utils::builder_fields(
			__( 'Wishlist', 'shop-press' ),
			'shoppress_myaccount',
		);
	}

	/**
	 * Get user notifications fields.
	 *
	 * @since 1.2.0
	 *
	 * @return array
	 */
	public static function my_account_notifications() {
		return Utils::builder_fields(
			__( 'User Notifications', 'shop-press' ),
			'shoppress_myaccount',
		);
	}

	/**
	 * Get cart fields.
	 *
	 * @since 1.2.0
	 *
	 * @return array $cart_fields
	 */
	public static function cart() {
		$cart_fields = array(
			'heading' => array(
				'title'     => __( 'Cart', 'shop-press' ),
				'component' => 'heading',
			),
			'pages'   => array(
				'component' => 'pages',
				'type'      => 'shoppress_pages',
			),
		);

		return $cart_fields;
	}

	/**
	 * Get quick view fields.
	 *
	 * @since 1.2.0
	 *
	 * @return array $quick_view_fields
	 */
	public static function quick_view() {
		$quick_view_fields = array(
			'heading' => array(
				'title'     => __( 'Quick View', 'shop-press' ),
				'component' => 'heading',
			),
			'builder' => array(
				'component' => 'pages',
				'type'      => 'shoppress_pages',
			),

		);

		return $quick_view_fields;
	}

	/**
	 * Get compare fields.
	 *
	 * @since 1.2.0
	 *
	 * @return array $compare_fields
	 */
	public static function compare() {
		$compare_fields = array(
			'heading' => array(
				'title'     => __( 'Compare', 'shop-press' ),
				'component' => 'heading',
			),
			'builder' => array(
				'component' => 'pages',
				'type'      => 'shoppress_pages',
			),
		);

		return $compare_fields;
	}

	/**
	 * Get thank you fields.
	 *
	 * @since 1.2.0
	 *
	 * @return array $thank_you_fields
	 */
	public static function thank_you() {
		$thank_you_fields = array(
			'heading' => array(
				'title'     => __( 'Thank You', 'shop-press' ),
				'component' => 'heading',
			),
			'pages'   => array(
				'component' => 'pages',
				'type'      => 'shoppress_pages',
			),
		);

		return $thank_you_fields;
	}

	/**
	 * Get empty cart fields.
	 *
	 * @since 1.2.0
	 *
	 * @return array $empty_cart_fields
	 */
	public static function empty_cart() {
		$empty_cart_fields = array(
			'heading' => array(
				'title'     => __( 'Empty Cart', 'shop-press' ),
				'component' => 'heading',
			),
			'pages'   => array(
				'component' => 'pages',
				'type'      => 'shoppress_pages',
			),
		);

		return $empty_cart_fields;
	}

	/**
	 * Returns products loop fields.
	 *
	 * @since 1.2.0
	 */
	public static function products_loop() {
		$fields = array(
			array(
				'title'       => __( 'Products Loop', 'shop-press' ),
				'description' => __( 'Redesign WooCommerce loops as you wish using Elementor to display products the way you want.', 'shop-press' ),
				'component'   => 'heading',
			),
			array(
				'type'      => 'shoppress_loop',
				'component' => 'pages',
				'option'    => false,
			),
		);

		return $fields;
	}
}
