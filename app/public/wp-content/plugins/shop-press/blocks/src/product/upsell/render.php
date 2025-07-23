<?php
/**
 * Product Upsell.
 *
 * @package ShopPress
 */

defined( 'ABSPATH' ) || exit;

// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
echo sp_render_product_collection( 'sp-up-sell-products', $attributes );
