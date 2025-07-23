<?php
/**
 * Thank You Page.
 *
 * @package ShopPress
 */

namespace ShopPress\Templates;

defined( 'ABSPATH' ) || exit;

class Thankyou {
	/**
	 * Init.
	 *
	 * @since 1.2.0
	 */
	public static function init() {

		if ( self::is_thank_you_builder() ) {

			add_action( 'wp_enqueue_scripts', array( __CLASS__, 'enqueue' ), 99 );
			add_filter( 'wc_get_template', array( __CLASS__, 'thank_you_templates' ), 10, 2 );
			add_action( 'shoppress_account_thank_you', array( __CLASS__, 'thank_you_content' ) );
		}
	}

	/**
	 * Enqueue scripts.
	 *
	 * @since 1.4.0
	 */
	public static function enqueue() {

		if ( is_checkout() ) {

			$builder_id = sp_get_template_settings( 'thank_you', 'page_builder' );

			if ( 'block_editor' === sp_get_builder_type( $builder_id ) ) {

				add_filter(
					'styler/block_editor/post_id',
					function () {
						return sp_get_template_settings( 'thank_you', 'page_builder' );
					}
				);
			}
		}
	}

	/**
	 * Thank You templates.
	 *
	 * @since 1.1.0
	 */
	public static function thank_you_templates( $located, $template_name ) {

		if ( 'checkout/thankyou.php' === $template_name ) {
			$located = sp_get_template_path( 'thankyou/thankyou' );
		}

		return $located;
	}

	/**
	 * Returns the content of the page that has been selected as the thank you page.
	 *
	 * @since 1.2.0
	 */
	public static function thank_you_content() {

		if ( self::is_thank_you_builder() ) {

			$thank_you_page = sp_get_template_settings( 'thank_you', 'page_builder' );
			echo '<div id="shoppress-wrap" class="shoppress-wrap">';
			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo sp_get_builder_content( $thank_you_page );
			echo '</div>';
		}
	}

	/**
	 * Check if thank you builder is enabled.
	 *
	 * @since 1.2.0
	 *
	 * @return bool
	 */
	public static function is_thank_you_builder() {
		return ( sp_get_template_settings( 'thank_you', 'status' ) && sp_get_template_settings( 'thank_you', 'page_builder' ) );
	}

	/**
	 * Get order key.
	 *
	 * @since 1.2.0
	 */
	public static function get_order_key() {
		$order_key = '';

		if ( is_checkout() && isset( $_GET ) && isset( $_GET['key'] ) && ! empty( $_GET['key'] ) ) {
			$order_key = $_GET['key'];
		}

		return $order_key;
	}
}
