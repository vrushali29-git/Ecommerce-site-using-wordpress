<?php
/**
 * Shop Page.
 *
 * @package ShopPress
 */

namespace ShopPress\Templates;

defined( 'ABSPATH' ) || exit;

use Elementor\Plugin;

class Shop {
	/**
	 * Init.
	 *
	 * @since 1.2.0
	 */
	public static function init() {
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'enqueue_scripts' ) );
		add_action( 'shoppress/builder/before_shop_product_render', array( __CLASS__, 'before_render_loop' ), 1, 9 );
		add_action( 'shoppress/builder/before_product_collection_render', array( __CLASS__, 'before_render_loop' ), 1, 9 );
		add_action( 'shoppress/builder/after_product_collection_render', array( __CLASS__, 'after_render_loop' ), 1, 9 );
		add_action( 'shoppress/builder/after_shop_product_render', array( __CLASS__, 'after_render_loop' ), 1, 9 );
		add_action( 'shoppress_shop', array( __CLASS__, 'shop_page_content' ) );
		add_filter( 'template_include', array( __CLASS__, 'shop_page_template' ), 99 );
	}

	/**
	 * enqueue_scripts.
	 *
	 * @since 1.4.0
	 */
	public static function enqueue_scripts() {

		if ( is_shop() && self::is_shop_builder() ) {

			$builder_id = sp_get_template_settings( 'shop', 'page_builder' );

			if ( 'block_editor' === sp_get_builder_type( $builder_id ) ) {

				add_filter(
					'styler/block_editor/post_id',
					function () {
						return sp_get_template_settings( 'shop', 'page_builder' );
					}
				);
			}

			wp_enqueue_script( 'sp-nicescroll-script' );
			wp_enqueue_script( 'sp-products-loop' );
			wp_enqueue_style( 'sp-products-loop' );

			if ( is_rtl() ) {
				wp_enqueue_style( 'sp-products-loop-rtl' );
			}
		}
	}

	/**
	 * Before render loop.
	 *
	 * @since 1.4.6
	 *
	 * @param array $attrs
	 */
	public static function before_render_loop( $attrs ) {
		$template_id         = $attrs['template_id'] ?? 0;
		$product_loop_status = sp_is_template_active( 'products_loop' );

		if ( $product_loop_status && $template_id ) {

			global $sp_custom_loop_template_id;
			$sp_custom_loop_template_id = $template_id;

			add_filter( 'wc_get_template_part', array( 'ShopPress\Templates\ProductLoopContent', 'filter_loop_content' ), 10, 2 );
		}
	}

	/**
	 * After render loop.
	 *
	 * @since 1.4.6
	 *
	 * @param array $attrs
	 */
	public static function after_render_loop( $attrs ) {
		$template_id = $attrs['template_id'] ?? 0;

		if ( $template_id ) {
			remove_filter( 'wc_get_template_part', array( 'ShopPress\Templates\ProductLoopContent', 'filter_loop_content' ), 10, 2 );
		}
	}

	/**
	 * Check shop builder.
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	public static function is_shop_builder() {
		return sp_get_template_settings( 'shop', 'status' ) && sp_get_template_settings( 'shop', 'page_builder' ) ? true : false;
	}

	/**
	 * Returns the content of the page that has been selected as the shop page.
	 *
	 * @since 1.0.0
	 */
	public static function shop_page_content() {

		if ( self::is_shop_builder() && is_shop() && ! is_product() ) {

			$shop_page = sp_get_template_settings( 'shop', 'page_builder' );

			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo sp_get_builder_content( $shop_page );
		}
	}

	/**
	 * Shop page template.
	 *
	 * @since 1.0.0
	 */
	public static function shop_page_template( $template ) {

		if ( self::is_shop_builder() && is_shop() && ! is_product() ) {
			require_once sp_get_template_path( 'shop/shop' );
			exit;
		}

		return $template;
	}
}
