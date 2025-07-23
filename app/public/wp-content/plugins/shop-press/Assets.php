<?php
/**
 * Plugin Assets.
 *
 * @package ShopPress
 */

namespace ShopPress;

defined( 'ABSPATH' ) || exit;

class Assets {
	/**
	 * Init.
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
		add_action( 'shoppress_quick_view_before_content', array( __CLASS__, 'register_scripts' ), 0 );
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'register_scripts' ), 0 );
		add_action( 'elementor/editor/before_enqueue_scripts', array( __CLASS__, 'register_scripts' ), 0 );
		add_action( 'admin_enqueue_scripts', array( __CLASS__, 'admin_register_scripts' ), 0 );
	}

	/**
	 * Returns an array of scripts with their URLs.
	 *
	 * @since 1.2.0
	 *
	 * @return array
	 */
	public static function get_scripts_list() {
		$sp_deps = array( 'jquery', 'sp-frontend' );

		$scripts = array(
			'sp-frontend'                 => array(
				'src'  => SHOPPRESS_URL . 'public/dist/js/frontend.min.js',
				'deps' => array( 'jquery' ),
			),
			'sp-cart-totals'              => SHOPPRESS_URL . 'public/widgets/cart-totals/cart-totals.js',
			'sp-wishlist'                 => SHOPPRESS_URL . 'public/modules/wishlist/wishlist.js',
			'sp-products-loop'            => SHOPPRESS_URL . 'public/widgets/products/products.js',
			'sp-recent-products'          => SHOPPRESS_URL . 'public/widgets/recent-products/recent-products.js',
			'sp-nicescroll-script'        => SHOPPRESS_URL . 'public/lib/nicescroll/jquery.nicescroll.js',
			'sp-ajax-search'              => SHOPPRESS_URL . 'public/widgets/ajax-search/ajax-search.js',
			'sp-size-chart'               => SHOPPRESS_URL . 'public/widgets/size-chart/size-chart.js',
			'sp-suggest-price'            => SHOPPRESS_URL . 'public/widgets/suggest-price/suggest-price.js',
			'sp-multi-step-checkout'      => SHOPPRESS_URL . 'public/modules/checkout/multistep/multistep.js',
			'sp-shopify-checkout'         => SHOPPRESS_URL . 'public/modules/checkout/shopify/shopify.js',
			'sp-flash-sale-countdown'     => SHOPPRESS_URL . 'public/modules/flash-sale-countdown/flash-sale-countdown.js',
			'sp-variation-swatches'       => array(
				'src'  => SHOPPRESS_URL . 'public/modules/variation-swatches/front/js/sp-variation-swatches.js',
				'deps' => array( 'jquery', 'wc-add-to-cart-variation' ),
			),
			'sp-menu-cart'                => array(
				'src'  => SHOPPRESS_URL . 'public/modules/menu-cart/menu-cart.js',
				'deps' => $sp_deps,
			),
			'sp-quickview'                => array(
				'src'  => SHOPPRESS_URL . 'public/modules/quick-view/static-quick-view.js',
				'deps' => $sp_deps,
			),
			'sp-compare'                  => array(
				'src'  => SHOPPRESS_URL . 'public/modules/compare/compare.js',
				'deps' => $sp_deps,
			),
			'sp-my-account-notifications' => array(
				'src'  => SHOPPRESS_URL . 'public/modules/notifications/notifications.js',
				'deps' => $sp_deps,
			),
			'sp-product-filters'          => array(
				'src'  => SHOPPRESS_URL . 'public/widgets/product-filters/product-filters.js',
				'deps' => $sp_deps,
			),
			'sp-single-ajax-add-to-cart'  => array(
				'src'  => SHOPPRESS_URL . 'public/modules/single-ajax-add-to-cart/single-ajax-add-to-cart.js',
				'deps' => $sp_deps,
			),
			'sp-header-toggle'            => SHOPPRESS_URL . 'public/widgets/header-toggle/header-toggle.js',
			'slick'                       => array(
				'src'  => SHOPPRESS_URL . 'public/lib/slick/slick.min.js',
				'deps' => $sp_deps,
			),
		);

		return apply_filters( 'shoppress/register_scripts', $scripts );
	}

	/**
	 * Returns an array of styles with their URLs.
	 *
	 * @since 1.2.0
	 *
	 * @return array
	 */
	public static function get_styles_list() {
		$styles = array(
			'sp-frontend'                     => SHOPPRESS_URL . 'public/dist/css/frontend.min.css',
			'sp-frontend-rtl'                 => SHOPPRESS_URL . 'public/dist/css/frontend.min-rtl.css',
			'sp-compare'                      => SHOPPRESS_URL . 'public/dist/css/compare.css',
			'sp-compare-rtl'                  => SHOPPRESS_URL . 'public/dist/css/compare-rtl.css',
			'sp-products-loop'                => SHOPPRESS_URL . 'public/dist/css/products-loop.css',
			'sp-products-loop-rtl'            => SHOPPRESS_URL . 'public/dist/css/products-loop-rtl.css',
			'sp-ajax-search'                  => SHOPPRESS_URL . 'public/dist/css/widgets/ajax-search.css',
			'sp-ajax-search-rtl'              => SHOPPRESS_URL . 'public/dist/css/widgets/ajax-search-rtl.css',
			'sp-size-chart'                   => SHOPPRESS_URL . 'public/dist/css/size-chart.css',
			'sp-pr-general'                   => SHOPPRESS_URL . 'public/dist/css/pr-general.css',
			'sp-pr-general-rtl'               => SHOPPRESS_URL . 'public/dist/css/pr-general-rtl.css',
			'sp-my-wishlist'                  => SHOPPRESS_URL . 'public/dist/css/my-wishlist.css',
			'sp-my-wishlist-rtl'              => SHOPPRESS_URL . 'public/dist/css/my-wishlist-rtl.css',
			'sp-quickview'                    => SHOPPRESS_URL . 'public/dist/css/quick-view.css',
			'sp-quickview-rtl'                => SHOPPRESS_URL . 'public/dist/css/quick-view-rtl.css',
			'sp-shop'                         => SHOPPRESS_URL . 'public/dist/css/shop.css',
			'sp-variation-swatches'           => SHOPPRESS_URL . 'public/modules/variation-swatches/front/css/sp-variation-swatches.css',
			'sp-variation-swatches-rtl'       => SHOPPRESS_URL . 'public/modules/variation-swatches/front/css/sp-variation-swatches-rtl.css',
			'sp-cart'                         => SHOPPRESS_URL . 'public/dist/css/cart.css',
			'sp-cart-rtl'                     => SHOPPRESS_URL . 'public/dist/css/cart-rtl.css',
			'sp-mini-cart'                    => SHOPPRESS_URL . 'public/dist/css/mini-cart.css',
			'sp-mini-cart-rtl'                => SHOPPRESS_URL . 'public/dist/css/mini-cart-rtl.css',
			'sp-my-account'                   => SHOPPRESS_URL . 'public/dist/css/my-account.css',
			'sp-checkout'                     => SHOPPRESS_URL . 'public/dist/css/checkout.css',
			'sp-checkout-rtl'                 => SHOPPRESS_URL . 'public/dist/css/checkout-rtl.css',
			'sp-multi-step-checkout'          => SHOPPRESS_URL . 'public/dist/css/multi-step-checkout.css',
			'sp-multi-step-checkout-rtl'      => SHOPPRESS_URL . 'public/dist/css/multi-step-checkout-rtl.css',
			'sp-shopify-checkout'             => SHOPPRESS_URL . 'public/dist/css/shopify-checkout.css',
			'sp-shopify-checkout-rtl'         => SHOPPRESS_URL . 'public/dist/css/shopify-checkout-rtl.css',
			'sp-single'                       => SHOPPRESS_URL . 'public/dist/css/single.css',
			'sp-single-rtl'                   => SHOPPRESS_URL . 'public/dist/css/single-rtl.css',
			'sp-menu-wishlist'                => SHOPPRESS_URL . 'public/dist/css/menu-wishlist.css',
			'sp-menu-wishlist-rtl'            => SHOPPRESS_URL . 'public/dist/css/menu-wishlist-rtl.css',
			'sp-astra-checkout'               => SHOPPRESS_URL . 'public/lib/compatibility/astra/checkout.css',
			'sp-astra-cart'                   => SHOPPRESS_URL . 'public/lib/compatibility/astra/cart.css',
			'sp-astra-single'                 => SHOPPRESS_URL . 'public/lib/compatibility/astra/single.css',
			'sp-astra-my-account'             => SHOPPRESS_URL . 'public/lib/compatibility/astra/my-account.css',
			'sp-flash-sale-countdown'         => SHOPPRESS_URL . 'public/dist/css/flash-sale-countdown.css',
			'sp-flash-sale-countdown-rtl'     => SHOPPRESS_URL . 'public/dist/css/flash-sale-countdown-rtl.css',
			'sp-mobile-panel'                 => SHOPPRESS_URL . 'public/modules/mobile-panel/mobile-panel.css',
			'sp-my-account-notifications'     => SHOPPRESS_URL . 'public/dist/css/notifications.css',
			'sp-my-account-notifications-rtl' => SHOPPRESS_URL . 'public/dist/css/notifications-rtl.css',
			'sp-sticky-add-to-cart'           => SHOPPRESS_URL . 'public/dist/css/sticky-add-to-cart.css',
			'sp-sticky-add-to-cart-rtl'       => SHOPPRESS_URL . 'public/dist/css/sticky-add-to-cart-rtl.css',
			'sp-product-filters'              => SHOPPRESS_URL . 'public/dist/css/widgets/filters.css',
			'sp-product-filters-rtl'          => SHOPPRESS_URL . 'public/dist/css/widgets/filters-rtl.css',
			'sp-header-toggle'                => SHOPPRESS_URL . 'public/widgets/header-toggle/header-toggle.css',
			'sp-header-toggle-rtl'            => SHOPPRESS_URL . 'public/widgets/header-toggle/header-toggle-rtl.css',
			'sp-categories-grid'              => SHOPPRESS_URL . 'public/widgets/categories-grid/categories-grid.css',
			'sp-brands'                       => SHOPPRESS_URL . 'public/dist/css/brands.css',
			'sp-brands-rtl'                   => SHOPPRESS_URL . 'public/dist/css/brands-rtl.css',
			'slick'                           => SHOPPRESS_URL . 'public/lib/slick/slick.css',

		);

		return apply_filters( 'shoppress/register_styles', $styles );
	}

	/**
	 * Register Scripts
	 *
	 * @since 1.2.0
	 */
	public static function register_scripts() {
		$scripts = self::get_scripts_list();
		$styles  = self::get_styles_list();

		foreach ( $scripts as $handle => $script ) {
			$src  = $script['src'] ?? $script;
			$deps = $script['deps'] ?? array( 'jquery' );

			wp_register_script( $handle, $src, $deps, SHOPPRESS_VERSION, true );
		}

		foreach ( $styles as $handle => $style ) {
			$src  = $style['src'] ?? $style;
			$deps = $style['deps'] ?? array();

			wp_register_style( $handle, $src, $deps, SHOPPRESS_VERSION );
		}
	}

	/**
	 * Returns an array of admin scripts with their URLs.
	 *
	 * @since 1.2.0
	 *
	 * @return array
	 */
	private static function get_admin_scripts_list() {
		$scripts = array(
			'sp-backend'            => SHOPPRESS_URL . 'public/admin/backend.js',
			'sp-admin-announcement' => SHOPPRESS_URL . 'public/dist/admin/announcement.js',
			'sp-admin-message'      => SHOPPRESS_URL . 'public/dist/admin/message.js',
			'sp-backorder-admin'    => SHOPPRESS_URL . 'public/modules/backorder/backorder-admin.js',
			'select2'               => SHOPPRESS_URL . 'public/lib/select2/select2.min.js',
		);

		return apply_filters( 'shoppress/register_admin_scripts', $scripts );
	}

	/**
	 * Returns an array of admin styles with their URLs.
	 *
	 * @since 1.2.0
	 *
	 * @return array
	 */
	private static function get_admin_styles_list() {
		$styles = array(
			'sp-backend'            => SHOPPRESS_URL . 'public/dist/admin/backend.css',
			'sp-admin-announcement' => SHOPPRESS_URL . 'public/dist/admin/announcement.css',
			'sp-admin'              => SHOPPRESS_URL . 'build/index.css',
			'sp-admin-rtl'          => SHOPPRESS_URL . 'public/admin/admin-rtl.css',
			'sp-admin-message'      => SHOPPRESS_URL . 'public/dist/admin/message.css',
			'sp-backorder-admin'    => SHOPPRESS_URL . 'public/modules/backorder/backorder-admin.css',
			'select2'               => SHOPPRESS_URL . 'public/lib/select2/select2.min.css',
		);

		return apply_filters( 'shoppress/register_admin_styles', $styles );
	}

	/**
	 * Register Admin Scripts
	 *
	 * @since 1.2.0
	 */
	public static function admin_register_scripts() {
		$scripts = self::get_admin_scripts_list();
		$styles  = self::get_admin_styles_list();

		foreach ( $scripts as $handle => $script ) {
			$src  = $script['src'] ?? $script;
			$deps = $script['deps'] ?? array( 'jquery' );

			wp_register_script( $handle, $src, $deps, SHOPPRESS_VERSION, true );
		}

		foreach ( $styles as $handle => $style ) {
			$src  = $style['src'] ?? $style;
			$deps = $style['deps'] ?? array();

			wp_register_style( $handle, $src, $deps, SHOPPRESS_VERSION );
		}
	}
}
