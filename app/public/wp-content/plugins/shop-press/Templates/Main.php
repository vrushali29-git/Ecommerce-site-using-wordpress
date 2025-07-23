<?php
/**
 * Templates.
 *
 * @package ShopPress
 */

namespace ShopPress\Templates;

defined( 'ABSPATH' ) || exit;

use ShopPress\Settings;
use ShopPress\Modules\Wishlist;
use ShopPress\Modules\Compare;

class Main {
	/**
	 * Init.
	 *
	 * @since 1.0.0
	 */
	public static function init() {
		add_filter( 'template_include', array( __CLASS__, 'builder_template' ), 99 );
		add_filter( 'option_elementor_cpt_support', array( __CLASS__, 'add_support' ) );
		add_filter( 'default_option_elementor_cpt_support', array( __CLASS__, 'add_support' ) );

		// Elementor
		add_action( 'elementor/preview/init', array( __CLASS__, 'add_to_cart_preview' ) );
		add_action( 'elementor/editor/init', array( __CLASS__, 'add_to_compare_preview' ) );
		add_action( 'elementor/editor/init', array( __CLASS__, 'add_to_wishlist_preview' ) );
		add_action( 'elementor/preview/init', array( __CLASS__, 'editor_woocommerce_class' ) );

		// Block editor
		add_action( 'enqueue_block_editor_assets', array( __CLASS__, 'add_to_cart_preview' ) );
		add_action( 'enqueue_block_editor_assets', array( __CLASS__, 'add_to_compare_preview' ) );
		add_action( 'enqueue_block_editor_assets', array( __CLASS__, 'add_to_wishlist_preview' ) );

		if ( is_admin() ) {
			add_action( 'shoppress/builder/before_load_template', array( __CLASS__, 'prepare_cart_and_checkout_editor' ), 10, 2 );
		}
	}

	/**
	 * Prepare cart editor.
	 *
	 * @since 1.4.0
	 *
	 * @param string $builder
	 */
	public static function prepare_cart_and_checkout_editor( $builder ) {

		if ( $builder === 'cart' || $builder === 'checkout' ) {

			WC()->frontend_includes();
			WC()->session = new \WC_Session_Handler();
			WC()->session->init();
			WC()->customer = new \WC_Customer( get_current_user_id(), true );
			WC()->cart     = new \WC_Cart();
			WC()->cart->calculate_shipping();
			WC()->cart->calculate_totals();
		}
	}

	/**
	 * Builder template.
	 *
	 * @since  1.0.0
	 */
	public static function builder_template( $template ) {
		global $post;

		$custom_type = sp_get_template_type( $post->ID ?? null );

		if ( is_singular( 'shoppress_myaccount' ) || is_singular( 'shoppress_loop' ) || ( $custom_type !== 'single' && is_singular( 'shoppress_pages' ) ) ) {
			$template = sp_get_template_path( 'editor-single' );
		}

		if ( $custom_type === 'single' && is_singular( 'shoppress_pages' ) ) {
			$template = sp_get_template_path( 'editor-single-product' );
		}

		return $template;
	}

	/**
	 * Adds WooCommerce classes to the body class filter based on various conditions.
	 *
	 * @since 1.1.0
	 *
	 * @return void
	 */
	public static function editor_woocommerce_class() {
		add_filter(
			'body_class',
			function ( $classes ) {
				$editor_pages = array(
					'single'          => array( 'woocommerce', 'woocommerce-page', 'woocommerce-js' ),
					'loop'            => array( 'woocommerce', 'woocommerce-page', 'archive', 'tax-product_cat', 'tax-product_tag', 'post-type-archive', 'post-type-archive-product' ),
					'wishlist'        => array( 'woocommerce', 'woocommerce-page', 'woocommerce-js' ),
					'compare'         => array( 'woocommerce', 'woocommerce-page', 'woocommerce-js' ),
					'shop'            => array( 'woocommerce', 'woocommerce-page', 'archive', 'tax-product_cat', 'tax-product_tag', 'post-type-archive', 'post-type-archive-product' ),
					'general'         => array( 'woocommerce', 'woocommerce-page', 'archive', 'tax-product_cat', 'tax-product_tag', 'post-type-archive', 'post-type-archive-product' ),
					'cart'            => array( 'woocommerce', 'woocommerce-cart', 'woocommerce-page', 'woocommerce-js' ),
					'empty_cart'      => array( 'woocommerce', 'woocommerce-cart', 'woocommerce-page', 'woocommerce-js' ),
					'checkout'        => array( 'woocommerce', 'woocommerce-checkout', 'woocommerce-page', 'woocommerce-js' ),
					'thank_you'       => array( 'woocommerce', 'woocommerce-checkout', 'woocommerce-page', 'woocommerce-order-received', 'woocommerce-js' ),
					'my_account'      => array( 'woocommerce-account', 'woocommerce-page', 'woocommerce-js' ),
					'dashboard'       => array( 'woocommerce-account', 'woocommerce-page', 'woocommerce-js' ),
					'orders'          => array( 'woocommerce-account', 'woocommerce-page', 'woocommerce-orders', 'woocommerce-js' ),
					'downloads'       => array( 'woocommerce-account', 'woocommerce-page', 'woocommerce-downloads', 'woocommerce-js' ),
					'addresses'       => array( 'woocommerce-account', 'woocommerce-page', 'woocommerce-edit-address', 'woocommerce-js' ),
					'account_details' => array( 'woocommerce-account', 'woocommerce-page', 'woocommerce-edit-account', 'woocommerce-js' ),
				);

				foreach ( $editor_pages as $page => $page_classes ) {

					if ( \ShopPress\Elementor\EditorCondition::is_editor_page( $page ) ) {

						foreach ( $page_classes as $class ) {

							if ( ! isset( $classes[ $class ] ) ) {
								$classes[] = $class;
							}
						}
					}
				}

				return $classes;
			}
		);
	}

	/**
	 * Add to cart.
	 *
	 * @since 1.2.0
	 */
	public static function add_to_cart_preview() {

		if ( 'shoppress_pages' == get_post_type() ) {

			$product_id = Utils::get_latest_product_id();

			if ( ! empty( WC()->cart ) && WC()->cart->get_cart_contents_count() === 0 ) {

				WC()->cart->add_to_cart( $product_id );
			}
		}
	}

	/**
	 * Add to compare.
	 *
	 * @since 1.2.0
	 */
	public static function add_to_compare_preview() {

		if ( 'shoppress_pages' == get_post_type() ) {

			$page_type = get_post_meta( get_the_ID(), 'custom_type', true );

			if ( 'compare' === $page_type ) {

				$product_id = Utils::get_latest_product_id();

				if ( ! in_array( $product_id, Compare::get_compare_product_ids() ) ) {
					$action = Compare::add_to_compare( $product_id );
				}
			}
		}
	}

	/**
	 * Add to wishlist.
	 *
	 * @since 1.2.0
	 */
	public static function add_to_wishlist_preview() {

		if ( 'shoppress_pages' == get_post_type() ) {

			$page_type = get_post_meta( get_the_ID(), 'custom_type', true );

			if ( 'wishlist' === $page_type ) {

				$product_id = Utils::get_latest_product_id();
				$Wishlist   = Wishlist\Main::get_wishlist_products();

				if ( ! isset( $Wishlist[ $product_id ] ) ) {
					$action = Wishlist\Main::add_to_wishlist( $product_id );
				}
			}
		}
	}

	/**
	 * Create WooCommerce pages.
	 *
	 * @since 1.0.0
	 */
	public static function create_pages() {
		$pages = array(
			'quick_view' => 'Quick View',
			'compare'    => 'Compare',
			'thank_you'  => 'Thank You',
			'wishlist'   => 'Wishlist',
			'empty_cart' => 'Empty Cart',
			'cart'       => 'Cart',
			'checkout'   => 'Checkout',
			'archive'    => 'Archive',
			'shop'       => 'Shop',
			'single'     => 'Single',
		);

		foreach ( $pages as $key => $page ) {

			$post_id = self::add_post( 'shoppress_pages', $page );

			if ( $post_id ) {

				update_post_meta( $post_id, 'custom_type', $key );
				update_post_meta( $post_id, 'sp_builder_type', Utils::get_builder_template_type() );
			}
		}
	}

	/**
	 * Create my account pages.
	 *
	 * @since 1.0.0
	 */
	public static function create_account() {
		$account = array(
			'dashboard'                => 'Dashboard',
			'orders'                   => 'Orders',
			'downloads'                => 'Downloads',
			'addresses'                => 'Addresses',
			'account_details'          => 'Account Details',
			'my_account'               => 'My Account',
			'login_register_form'      => 'Login',
			'my_account_wishlist'      => 'Wishlist',
			'my_account_notifications' => 'User Notifications',
		);

		foreach ( $account as $key => $page ) {

			$post_id = self::add_post( 'shoppress_myaccount', $page );

			if ( $post_id ) {

				update_post_meta( $post_id, 'custom_type', $key );
				update_post_meta( $post_id, 'sp_builder_type', Utils::get_builder_template_type() );
			}
		}
	}

	/**
	 * Create products loop.
	 *
	 * @since 1.0.0
	 */
	public static function create_loop() {
		$post_id = self::add_post( 'shoppress_loop', __( 'Products Loop', 'shop-press' ) );

		if ( $post_id ) {

			update_post_meta( $post_id, 'custom_type', 'products_loop' );
			update_post_meta( $post_id, 'sp_builder_type', Utils::get_builder_template_type() );
		}
	}

	/**
	 * Return template by title
	 *
	 * @param  string $title
	 * @param  string $post_type
	 *
	 * @since 1.0.0
	 *
	 * @return \WP_Post|false
	 */
	public static function get_template_by_title( $title, $post_type ) {
		$templates = get_posts(
			array(
				'post_type' => $post_type,
				'title'     => $title,
			)
		);

		return $templates[0] ?? false;
	}

	/**
	 * Add post.
	 *
	 * @since  1.0.0
	 */
	public static function add_post( $post_type, $title, $content = '' ) {
		$post = static::get_template_by_title( $title, $post_type );

		if ( empty( $post ) ) {
			$post_id = wp_insert_post(
				array(
					'post_title'   => $title,
					'post_status'  => 'publish',
					'post_type'    => $post_type,
					'post_author'  => 1,
					'post_content' => $content,
				)
			);

			return $post_id;
		}
	}

	/**
	 * Elementor support for custom post type.
	 *
	 * @since  1.0.0
	 */
	public static function add_support( $value ) {

		if ( empty( $value ) ) {
			$value = array();
		}

		return array_merge(
			$value,
			array(
				'shoppress_pages',
				'shoppress_myaccount',
				'shoppress_loop',
			)
		);
	}

	/**
	 * Create Shortcode pages
	 *
	 * @since 1.2.0
	 *
	 * @return void
	 */
	public static function create_shortcode_pages() {

		$settings = Settings::get_settings();

		$wishlist_text          = __( 'Wishlist', 'shop-press' );
		$saved_wishlist_page_id = $settings['modules']['wishlist_general_settings']['wishlist_page'] ?? false;
		if ( ! $saved_wishlist_page_id ) {
			$wishlist_page_id = self::add_post( 'page', $wishlist_text, '<!-- wp:shortcode -->[shoppress-wishlist-page]<!-- /wp:shortcode -->' );
			$settings['modules']['wishlist_general_settings']['wishlist_page'] = array(
				'value' => $wishlist_page_id,
				'label' => $wishlist_text,
			);
		}

		$compare_text          = __( 'Compare', 'shop-press' );
		$saved_compare_page_id = $settings['modules']['compare']['compare_page'] ?? false;
		if ( ! $saved_compare_page_id ) {
			$compare_page_id                                = self::add_post( 'page', $compare_text, '<!-- wp:shortcode -->[shoppress-compare-page]<!-- /wp:shortcode -->' );
			$settings['modules']['compare']['compare_page'] = array(
				'value' => $compare_page_id,
				'label' => $compare_text,
			);
		}

		Settings::update_settings( $settings );
	}

	/**
	 * Create templates.
	 *
	 * @since 1.0.0
	 */
	public static function create_templates() {
		self::create_pages();
		self::create_account();
		self::create_loop();
		self::create_shortcode_pages();
	}
}
