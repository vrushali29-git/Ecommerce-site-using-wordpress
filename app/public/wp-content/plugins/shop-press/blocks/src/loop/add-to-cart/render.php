<?php
/**
 * Loop add to cart.
 *
 * @package ShopPress
 */

defined( 'ABSPATH' ) || exit;

use ShopPress\Modules;

if ( ! Modules\CatalogMode::is_catalog_mode() ) {

	sp_load_builder_template( 'loop/loop-add-to-cart', $attributes );
}
