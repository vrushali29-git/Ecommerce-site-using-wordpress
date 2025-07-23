<?php
/**
 * Compatibility with Storefront theme.
 *
 * @package ShopPress
 */

namespace ShopPress\Compatibility;

defined( 'ABSPATH' ) || exit;

class Storefront {
	/**
	 * Init.
	 *
	 * @since 1.2.0
	 */
	public static function init() {
		add_action( 'shoppress_before_shop', array( __CLASS__, 'add_custom_content_before_shop' ) );
		add_action( 'shoppress_after_shop', array( __CLASS__, 'add_custom_content_after_shop' ) );
	}

	/**
	 * Fix before shop render.
	 *
	 * @since 1.2.0
	 */
	public static function add_custom_content_before_shop() {
		echo '<main id="main" class="site-main" role="main">';
	}

	/**
	 * Fix after shop render.
	 *
	 * @since 1.2.0
	 */
	public static function add_custom_content_after_shop() {
		echo '</main';
	}
}
