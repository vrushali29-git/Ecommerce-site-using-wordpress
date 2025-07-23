<?php
/**
 * Product Cross-sell.
 *
 * @package ShopPress
 */

defined( 'ABSPATH' ) || exit;

// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
echo sp_render_product_collection( 'sp-cross-sell-products', $attributes );
