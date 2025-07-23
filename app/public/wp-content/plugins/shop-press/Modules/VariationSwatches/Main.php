<?php
/**
 * The file that defines the core plugin class.
 *
 * @package ShopPress
 */

namespace ShopPress\Modules\VariationSwatches;

defined( 'ABSPATH' ) || exit;

class Main {

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public static function init() {
		new Admin();
		new Frontend();
		add_filter( 'shoppress/elementor/widgets', array( __CLASS__, 'add_elementor_widget' ), 9 );
	}

	/**
	 * Add Add elementor widgets.
	 *
	 * @since 1.2.0
	 *
	 * @return array
	 */
	public static function add_elementor_widget( $widgets ) {
		$widgets['loop-attributes']         = array(
			'editor_type' => 'loop',
			'class_name'  => 'LoopBuilder\Attributes',
			'is_pro'      => false,
			'path_key'    => 'loop/attributes',
		);
		$widgets['brands-attribute-list']   = array(
			'editor_type' => 'general',
			'class_name'  => 'BrandsAttribute',
			'is_pro'      => false,
			'path_key'    => 'general/brands',
		);
		$widgets['single-brands-attribute'] = array(
			'editor_type' => 'single',
			'class_name'  => 'BrandAttribute',
			'is_pro'      => false,
			'path_key'    => 'single-product/brands',
		);

		return $widgets;
	}
}
