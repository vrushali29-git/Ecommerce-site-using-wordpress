<?php
/**
 * Shop Product.
 *
 * @package ShopPress
 */

defined( 'ABSPATH' ) || exit;

// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
echo sp_render_shop_products( $attributes );
