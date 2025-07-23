<?php
/**
 * Archive Page.
 *
 * @package ShopPress
 */

namespace ShopPress\Templates;

defined( 'ABSPATH' ) || exit;

class Archive {
	/**
	 * Init.
	 *
	 * @since 1.2.0
	 */
	public static function init() {

		if ( ! self::is_archive_builder() ) {
			return;
		}

		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'enqueue' ), 99 );
		add_action( 'shoppress_archive', array( __CLASS__, 'archive_page_content' ) );
		add_filter( 'template_include', array( __CLASS__, 'archive_template' ), 99 );
		add_filter( 'shoppress_archive_before_content', array( __CLASS__, 'before_archive' ) );
		add_filter( 'shoppress_archive_after_content', array( __CLASS__, 'after_archive' ) );
	}

	/**
	 * Check archive builder.
	 *
	 * @since 1.1.0
	 *
	 * @return bool
	 */
	public static function is_archive_builder() {
		return sp_get_template_settings( 'archive', 'status' );
	}

	/**
	 * Enqueue scripts.
	 *
	 * @since 1.4.0
	 */
	public static function enqueue() {

		if ( ( is_product_category() || is_product_tag() ) && self::is_archive_builder() ) {

			$builder_id = sp_get_template_settings( 'archive', 'page_builder' );

			if ( 'block_editor' === sp_get_builder_type( $builder_id ) ) {

				add_filter(
					'styler/block_editor/post_id',
					function () {
						return sp_get_template_settings( 'archive', 'page_builder' );
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
	 * Return the archive page.
	 *
	 * @since 1.1.0
	 */
	public static function archive_page_content() {

		if ( self::is_archive_builder() ) {

			$archive_page = sp_get_template_settings( 'archive', 'page_builder' );

			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo sp_get_builder_content( $archive_page );
		}
	}

	/**
	 * Archive page template.
	 *
	 * @since 1.1.0
	 */
	public static function archive_template( $template ) {

		if ( is_product_category() || is_product_tag() || is_tax( 'shoppress_brand' ) ) {
			return sp_get_template_path( 'archive/archive-product' );
		}

		return $template;
	}

	/**
	 * before archive content.
	 *
	 * @since 1.3.1
	 */
	public static function before_archive() {
		echo '<div id="shoppress-wrap" class="shoppress-wrap">';
	}

	/**
	 * after archive content.
	 *
	 * @since 1.3.1
	 */
	public static function after_archive() {
		echo '</div>';
	}
}
