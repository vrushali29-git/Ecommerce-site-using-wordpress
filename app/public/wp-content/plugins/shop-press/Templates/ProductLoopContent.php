<?php
/**
 * Product Loop Content.
 *
 * @package ShopPress
 */

namespace ShopPress\Templates;

defined( 'ABSPATH' ) || exit;

class ProductLoopContent {
	/**
	 * Filter loop content.
	 *
	 * @since 1.4.0
	 */
	public static function filter_loop_content( $located, $template_name ) {
		global $sp_custom_loop_template_id;

		if ( 'content' === $template_name && $sp_custom_loop_template_id != 0 ) {
			$located = sp_get_template_path( 'products/loop-content' );
		}

		return $located;
	}
}
