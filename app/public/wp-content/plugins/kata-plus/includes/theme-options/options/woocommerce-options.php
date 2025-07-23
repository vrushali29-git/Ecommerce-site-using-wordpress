<?php
/**
 * Layout Options.
 *
 * @author  ClimaxThemes
 * @package Kata Plus
 * @since   1.0.0
 */

// Don't load directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


if ( ! class_exists( 'Kata_Plus_Theme_Options_WooCommerce' ) ) {
	class Kata_Plus_Theme_Options_WooCommerce extends Kata_Plus_Theme_Options {
		/**
		 * Set Options.
		 *
		 * @since   1.0.0
		 */
		public static function set_options() {

			if ( ! function_exists( 'is_plugin_active' ) ) {
				include_once ABSPATH . 'wp-admin/includes/plugin.php';
			}

			if ( ! is_plugin_active( 'shop-press/shop-press.php' ) ) {

				new \Kirki\Field\Number(
					array(
						'section'     => 'woocommerce_product_images',
						'settings'    => 'kata_thumbnail_image_width',
						'label'       => esc_html__( 'Thumbnail Image Width', 'kata-plus' ),
						'description' => esc_html__( 'Please enter numbers, Example:400,500,600', 'kata-plus' ),
						'default'     => 400,
						'choices'     => array(
							'min'  => 0,
							'max'  => 1920,
							'step' => 1,
						),
					)
				);
				new \Kirki\Field\Number(
					array(
						'section'     => 'woocommerce_product_images',
						'settings'    => 'kata_single_image_width',
						'label'       => esc_html__( 'single Image Width', 'kata-plus' ),
						'description' => esc_html__( 'Please enter numbers, Example:400,500,600', 'kata-plus' ),
						'default'     => 800,
						'choices'     => array(
							'min'  => 0,
							'max'  => 1920,
							'step' => 1,
						),
					)
				);
			}
		}
	} // class

	Kata_Plus_Theme_Options_WooCommerce::set_options();
}
