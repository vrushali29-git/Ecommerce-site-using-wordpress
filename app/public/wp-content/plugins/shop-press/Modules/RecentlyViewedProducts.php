<?php
/**
 * Recently viewed products.
 *
 * @package ShopPress
 */

namespace ShopPress\Modules;

defined( 'ABSPATH' ) || exit;

class RecentlyViewedProducts {
	/**
	 * Init.
	 *
	 * @since 1.2.0
	 */
	public static function init() {
		add_action( 'template_redirect', array( __CLASS__, 'track_product_view' ), 20 );
	}

	/**
	 * Track product views.
	 *
	 * @since 1.1.0
	 */
	public static function track_product_view() {

		if ( ! is_singular( 'product' ) ) {
			return;
		}

		global $post;

        if ( empty( $_COOKIE['woocommerce_recently_viewed'] ) ) { // @codingStandardsIgnoreLine.
			$viewed_products = array();
		} else {
            $viewed_products = wp_parse_id_list( (array) explode( '|', wp_unslash( $_COOKIE['woocommerce_recently_viewed'] ) ) ); // @codingStandardsIgnoreLine.
		}

		// Unset if already in viewed products list.
		$keys = array_flip( $viewed_products );

		if ( isset( $keys[ $post->ID ] ) ) {
			unset( $viewed_products[ $keys[ $post->ID ] ] );
		}

		$viewed_products[] = $post->ID;

		if ( count( $viewed_products ) > 15 ) {
			array_shift( $viewed_products );
		}

		// Store for session only.
		wc_setcookie( 'woocommerce_recently_viewed', implode( '|', $viewed_products ) );
	}
}
