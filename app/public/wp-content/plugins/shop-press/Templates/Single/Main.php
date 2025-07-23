<?php
/**
 * Single Product Page.
 *
 * @package ShopPress
 */

namespace ShopPress\Templates\Single;

defined( 'ABSPATH' ) || exit;

class Main {
	/**
	 * Product page.
	 *
	 * @var mixed
	 */
	protected static $product_page;

	/**
	 * Init.
	 *
	 * @since 1.0.0
	 */
	public static function init() {
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'enqueue_scripts' ), 99 );
		add_action( 'shoppress_single', array( __CLASS__, 'product_page_content' ) );
		add_filter( 'wc_get_template', array( __CLASS__, 'single_templates' ), 10, 2 );
		add_filter( 'template_include', array( __CLASS__, 'product_page_template' ), 99 );
		add_filter( 'woocommerce_before_single_product', array( __CLASS__, 'before_single' ) );
		add_filter( 'woocommerce_after_single_product', array( __CLASS__, 'after_single' ) );

		if ( is_admin() ) {
			Admin::init();
		}
	}

	/**
	 * Check single builder.
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	public static function is_single_builder() {
		return sp_get_template_settings( 'single', 'status' ) && static::get_custom_template_id();
	}

	/**
	 * Retrieves the custom template ID for a given product ID.
	 *
	 * @param int  $product_id The ID of the product. Default is 0.
	 * @param bool $return_cache Whether to return the cached value. Default is true.
	 *
	 * @since 1.2.0
	 *
	 * @return int|false The custom template ID if found, false otherwise.
	 */
	public static function get_custom_template_id( $product_id = 0, $return_cache = true ) {

		if ( true === $return_cache && isset( static::$product_page ) ) {
			return static::$product_page;
		}

		if ( ! $product_id ) {
			$product_id = get_the_ID();
		}

		$single_template = get_post_meta( $product_id, 'sp_woo_single_template', true );
		$single_template = $single_template['id'] ?? false;

		$custom_single_template = sp_get_template_settings( 'single', 'custom_single_template' );

		if ( $custom_single_template && $single_template ) {
			return static::$product_page = $single_template;
		}

		$templates_by_category = sp_get_template_settings( 'single', 'templates_by_category' );
		$product_cat           = $templates_by_category ? get_the_terms( $product_id, 'product_cat' ) : array();

		if ( $templates_by_category && isset( $product_cat[0] ) ) {

			// only returns the first selected category of the product
			$category_template = get_term_meta( $product_cat[0]->term_id, 'single_product_template', true );

			if ( $category_template ) {

				return static::$product_page = $category_template;
			}
		}

		return static::$product_page = sp_get_template_settings( 'single', 'page_builder' );
	}

	/**
	 * Enqueue scripts.
	 *
	 * @since 1.0.0
	 */
	public static function enqueue_scripts() {

		if ( is_product() && self::is_single_builder() ) {

			if ( 'block_editor' === sp_get_builder_type( static::get_custom_template_id() ) ) {

				add_filter(
					'styler/block_editor/post_id',
					function () {
						return static::get_custom_template_id();
					}
				);
			}

			wp_enqueue_style( 'sp-single' );

			if ( is_rtl() ) {
				wp_enqueue_style( 'sp-single-rtl' );
			}
		}
	}

	/**
	 * Return the single page.
	 *
	 * @since 1.0.0
	 */
	public static function product_page_content() {
		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo sp_get_builder_content( self::get_custom_template_id( get_the_ID() ) );
	}

	/**
	 * Single templates.
	 *
	 * @since 1.0.0
	 */
	public static function single_templates( $located, $template_name ) {
		$is_quick_view = sp_is_module_active( 'quick_view' );

		if (
			( is_product() && self::is_single_builder() )
			|| ( ! is_product() && $is_quick_view ) || is_sp_quick_view_ajax()
		) {

			if ( 'single-product/add-to-cart/variation-add-to-cart-button.php' === $template_name ) {
				$located = sp_get_template_path( 'woocommerce/single-product/add-to-cart/variation-add-to-cart-button' );
			}

			if ( 'single-product/add-to-cart/simple.php' === $template_name ) {
				$located = sp_get_template_path( 'woocommerce/single-product/add-to-cart/simple' );
			}

			if ( 'single-product/add-to-cart/grouped.php' === $template_name ) {
				$located = sp_get_template_path( 'woocommerce/single-product/add-to-cart/grouped' );
			}

			if ( 'single-product/add-to-cart/external.php' === $template_name ) {
				$located = sp_get_template_path( 'woocommerce/single-product/add-to-cart/external' );
			}
		}

		return $located;
	}

	/**
	 * Product page template.
	 *
	 * @since 1.0.0
	 */
	public static function product_page_template( $template ) {

		if ( is_product() && self::is_single_builder() ) {

			$template = sp_get_template_path( 'single-product/single-product-page' );
		}

		return $template;
	}

	/**
	 * Get Single Templates
	 *
	 * @since 1.2.0
	 *
	 * @return bool|array
	 */
	public static function get_single_templates() {
		$args = array(
			'posts_per_page' => 99,
			'post_type'      => 'shoppress_pages',
			'meta_query'     => array(
				array(
					'key'     => 'custom_type',
					'value'   => 'single',
					'compare' => 'LIKE',
				),
			),
		);

		$singles = get_posts( $args );

		if ( $singles ) {
			$single_list = array();
			foreach ( $singles as $post ) {
				$single_list[ $post->ID ] = $post->post_title;
			}

			if ( $single_list ) {
				return $single_list;
			}
		}

		return false;
	}

	/**
	 * before single content.
	 *
	 * @since 1.3.1
	 */
	public static function before_single() {
		echo '<div id="shoppress-wrap" class="shoppress-wrap">';
	}

	/**
	 * after single content.
	 *
	 * @since 1.3.1
	 */
	public static function after_single() {
		echo '</div>';
	}
}
