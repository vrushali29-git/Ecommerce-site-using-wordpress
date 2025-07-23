<?php
/**
 * Empty Cart Page.
 *
 * @package ShopPress
 */

namespace ShopPress\Templates;

defined( 'ABSPATH' ) || exit;

use Elementor\Plugin;

class EmptyCart {
	/**
	 * Init.
	 *
	 * @since 1.2.0
	 */
	public static function init() {

		if ( ! self::is_empty_cart_builder() ) {
			return;
		}

		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'enqueue' ), 99 );
		add_filter( 'wc_get_template', array( __CLASS__, 'cart_templates' ), 10, 2 );
		add_action( 'shoppress_empty_cart', array( __CLASS__, 'empty_cart_content' ) );
		add_filter( 'shoppress_before_empty_cart', array( __CLASS__, 'before_empty_cart' ) );
		add_filter( 'shoppress_after_empty_cart', array( __CLASS__, 'after_empty_cart' ) );
	}

	/**
	 * Check if empty cart builder is enabled.
	 *
	 * @since 1.2.0
	 *
	 * @return bool
	 */
	public static function is_empty_cart_builder() {
		return sp_get_template_settings( 'empty_cart', 'status' ) && sp_get_template_settings( 'empty_cart', 'page_builder' );
	}

	/**
	 * Enqueue scripts.
	 *
	 * @since 1.4.0
	 */
	public static function enqueue() {

		if ( is_cart() && self::is_empty_cart_builder() ) {

			$builder_id = sp_get_template_settings( 'empty_cart', 'page_builder' );

			if ( 'block_editor' === sp_get_builder_type( $builder_id ) ) {

				add_filter(
					'styler/block_editor/post_id',
					function () {
						return sp_get_template_settings( 'empty_cart', 'page_builder' );
					}
				);
			}
		}
	}

	/**
	 * Returns the content of the page that has been selected as the empty cart page.
	 *
	 * @since 1.2.0
	 */
	public static function empty_cart_content() {

		if ( self::is_empty_cart_builder() ) {

			$empty_cart_page = sp_get_template_settings( 'empty_cart', 'page_builder' );

			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo sp_get_builder_content( $empty_cart_page );
		}
	}

	/**
	 * Empty Cart templates.
	 *
	 * @since 1.2.0
	 */
	public static function cart_templates( $located, $template_name ) {

		if ( 'cart/cart-empty.php' === $template_name && self::is_empty_cart_builder() ) {
			$located = sp_get_template_path( 'cart/cart-empty' );
		}

		return $located;
	}

	/**
	 * Before empty cart content.
	 *
	 * @since 1.4.0
	 */
	public static function before_empty_cart() {
		echo '<div id="shoppress-wrap" class="shoppress-wrap">';
	}

	/**
	 * After empty cart content.
	 *
	 * @since 1.4.0
	 */
	public static function after_empty_cart() {
		echo '</div>';
	}
}
