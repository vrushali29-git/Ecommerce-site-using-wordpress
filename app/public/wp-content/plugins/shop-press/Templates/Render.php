<?php
/**
 * Render Templates.
 *
 * @package ShopPress
 */

namespace ShopPress\Templates;

defined( 'ABSPATH' ) || exit;

use Elementor\Plugin;
use ShopPress\Elementor;

class Render {
	/**
	 * Render builder content.
	 *
	 * @since 1.3.0
	 *
	 * @param int $builder_id
	 */
	public static function get_builder_content( $builder_id ) {
		$builder_type = sp_get_builder_type( $builder_id );

		ob_start();

		if ( 'elementor' === $builder_type && class_exists( '\Elementor\Plugin' ) ) {

			echo Plugin::instance()->frontend->get_builder_content_for_display( $builder_id, false );
		} elseif ( 'block_editor' === $builder_type ) {

			$content = get_post_field( 'post_content', $builder_id );
			echo do_blocks( $content );
		}

		return ob_get_clean();
	}

	/**
	 * Locate the themplate.
	 *
	 * @since 1.4.2
	 *
	 * @param string $template_name
	 *
	 * @return string
	 */
	public static function locate_template( $template_name ) {
		global $wp_stylesheet_path, $wp_template_path;

		if ( ! isset( $wp_stylesheet_path ) || ! isset( $wp_template_path ) ) {
			wp_set_template_globals();
		}

		$located = '';
		if ( file_exists( $wp_stylesheet_path . '/shop-press/' . $template_name ) ) {
			$located = $wp_stylesheet_path . '/shop-press/' . $template_name;
		} elseif ( is_child_theme() && file_exists( $wp_template_path . '/shop-press/' . $template_name ) ) {
			$located = $wp_template_path . '/shop-press/' . $template_name;
		}

		return $located;
	}

	/**
	 * Load the builder template.
	 *
	 * @since 1.3.0
	 *
	 * @param string $template_name
	 * @param array  $args
	 * @param bool   $is_pro
	 *
	 * @return void
	 */
	public static function load_builder_template( $template_name, $args = array(), $is_pro = false ) {
		$base          = ( $is_pro && defined( 'SHOPPRESS_PRO_PATH' ) ) ? SHOPPRESS_PRO_PATH : SHOPPRESS_PATH;
		$builder       = explode( '/', $template_name )[0] ?? '';
		$template_path = self::locate_template( basename( "{$template_name}.php" ) );
		if ( ! $template_path ) {
			$template_path = $base . "public/templates/{$template_name}.php";
		}

		/**
		 * Filters the path of the template.
		 *
		 * @since 1.3.0
		 *
		 * @param string $template_path
		 * @param string $template_name
		 * @param bool   $is_pro
		 */
		$template_path = apply_filters( 'shoppress/builder/template_path', $template_path, $template_name, $is_pro );

		/**
		 * Fires before a template file is loaded.
		 *
		 * @since 1.3.0
		 *
		 * @param string $builder
		 * @param string $template_path
		 * @param array  $args
		 * @param bool   $is_pro
		 */
		do_action( 'shoppress/builder/before_load_template', $builder, $template_name, $args, $is_pro );

		if ( file_exists( $template_path ) ) {
			require $template_path;
		}

		/**
		 * Fires after a template file is loaded.
		 *
		 * @since 1.3.0
		 *
		 * @param string $builder
		 * @param string $template_path
		 * @param array  $args
		 * @param bool   $is_pro
		 */
		do_action( 'shoppress/builder/after_load_template', $builder, $template_name, $args, $is_pro );
	}

	/**
	 * Returns the list of the builders that need to set post data.
	 *
	 * @since 1.3.0
	 *
	 * @return array
	 */
	public static function get_builders_need_set_post_data() {
		$builders = array(
			'single-product',
			'loop',
		);

		/**
		 * Filters the list of the builders that need to set post data.
		 *
		 * @since 1.3.0
		 *
		 * @param array $builders
		 */
		return apply_filters( 'shoppress/builder/items_to_set_post_data', $builders );
	}
}
