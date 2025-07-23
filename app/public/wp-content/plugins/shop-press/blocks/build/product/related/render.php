<?php
/**
 * Product Related.
 *
 * @package ShopPress
 */

defined( 'ABSPATH' ) || exit;

// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
echo sp_render_product_collection( 'sp-related-products', $attributes );
