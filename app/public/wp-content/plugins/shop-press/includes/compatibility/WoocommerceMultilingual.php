<?php
/**
 * Compatibility with WoocommerceMultilingual.
 *
 * @package ShopPress
 */

namespace ShopPress\Compatibility;

defined( 'ABSPATH' ) || exit;

class WoocommerceMultilingual {
	/**
	 * Init.
	 *
	 * @since 1.4.10
	 */
	public static function init() {
		/**
		 * Detect plugin. For frontend only.
		 */
		include_once ABSPATH . 'wp-admin/includes/plugin.php';

		// check for plugin using plugin name
		if ( ! is_plugin_active( 'woocommerce-multilingual/wpml-woocommerce.php' ) ) {
			return;
		}

		add_filter( 'shoppress/elementor/widgets', array( __CLASS__, 'add_widget' ) );
	}

	/**
	 * Add WPML currency switcher widget.
	 *
	 * @since 1.4.10
	 */
	public static function add_widget( $widgets ) {
		$widgets['currency-switcher'] = array(
			'editor_type' => 'general',
			'class_name'  => 'CurrencySwitcher',
			'is_pro'      => false,
			'path_key'    => 'general/currency-switcher',
		);

		return $widgets;
	}
}
