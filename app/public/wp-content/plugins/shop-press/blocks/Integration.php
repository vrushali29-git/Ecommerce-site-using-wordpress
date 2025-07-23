<?php
/**
 * Block editor integration.
 *
 * @package ShopPress
 */

namespace ShopPress\BlockEditor;

defined( 'ABSPATH' ) || exit;

use ShopPress\Templates;
use ShopPress\Assets;

class Integration {
	/**
	 * Init integration blocks.
	 *
	 * @since 1.4.0
	 */
	public static function init() {
		add_filter( 'block_categories_all', array( __CLASS__, 'add_custom_categories' ), 10, 2 );
		add_action( 'enqueue_block_editor_assets', array( __CLASS__, 'editor_assets' ) );
		add_action( 'shoppress/builder/before_load_template', array( __CLASS__, 'before_styler_wrapper' ), 10, 3 );
		add_action( 'shoppress/builder/after_load_template', array( __CLASS__, 'after_styler_wrapper' ), 10, 3 );

		BlockAPI::instance();
		RegisterBlocks::init();
		FilterBlocks::init();
	}

	/**
	 * Add styler wrapper to the blocks.
	 *
	 * @since 1.4.0
	 */
	public static function before_styler_wrapper( $builder, $template_file, $args ) {

		if ( ! isset( $args['wrapperID'] ) ) {
			return;
		}

		echo '<div class="' . esc_attr( $args['wrapperID'] ) . '">';
	}

	/**
	 * Close the styler wrapper.
	 *
	 * @since 1.4.0
	 */
	public static function after_styler_wrapper( $builder, $template_file, $args ) {

		if ( ! isset( $args['wrapperID'] ) ) {
			return;
		}

		echo '</div>';
	}

	/**
	 * Editor assets.
	 *
	 * @since 1.4.0
	 */
	public static function editor_assets() {
		$post_id     = $_GET['post'] ?? 0;
		$custom_type = get_post_meta( $post_id, 'custom_type', true );
		$styles      = Assets::get_styles_list();

		wp_enqueue_style( 'sp-blocks', SHOPPRESS_URL . 'blocks/editor.css', array(), SHOPPRESS_VERSION );
		wp_enqueue_script( 'sp-blocks', SHOPPRESS_URL . 'blocks/editor.js', array( 'wp-blocks' ), SHOPPRESS_VERSION, true );
		wp_localize_script(
			'sp-blocks',
			'sp_blocks',
			array(
				'custom_type' => $custom_type,
				'post_type'   => get_post_type(),
			)
		);

		if ( ! $custom_type ) {
			return;
		}

		if ( $styles ) {
			foreach ( $styles as $handle => $style ) {
				$src  = $style['src'] ?? $style;
				$deps = $style['deps'] ?? array();

				wp_enqueue_style( $handle, $src, $deps, SHOPPRESS_VERSION );
			}
		}

		if ( 'single' === $custom_type ) {

			wp_enqueue_script( 'zoom' );
			wp_enqueue_script( 'flexslider' );
			wp_enqueue_script( 'photoswipe-ui-default' );
			wp_enqueue_style( 'photoswipe-default-skin' );
			wp_enqueue_script( 'wc-single-product' );
		}
	}

	/**
	 * Register custom categories.
	 *
	 * @since 1.4.0
	 *
	 * @param array                          $block_categories
	 * @param WP_Block_Editor_Context|string $block_editor_context
	 */
	public static function add_custom_categories( $block_categories, $block_editor_context ) {
		array_unshift(
			$block_categories,
			array(
				'slug'  => 'sp-product-single',
				'title' => 'Product Single',
			),
			array(
				'slug'  => 'sp_woo_shop',
				'title' => 'Shop',
			),
			array(
				'slug'  => 'sp_woo_dashboard',
				'title' => 'My Account',
			),
			array(
				'slug'  => 'sp_woo_cart',
				'title' => 'Cart',
			),
			array(
				'slug'  => 'sp_general',
				'title' => 'ShopPress General',
			),
			array(
				'slug'  => 'sp_woo_checkout',
				'title' => 'Checkout',
			),
			array(
				'slug'  => 'sp_woo_loop',
				'title' => 'Product Loop',
			),
			array(
				'slug'  => 'sp_wishlist',
				'title' => 'Wishlist',
			),
			array(
				'slug'  => 'sp_compare',
				'title' => 'Compare',
			),
		);

		return $block_categories;
	}
}
