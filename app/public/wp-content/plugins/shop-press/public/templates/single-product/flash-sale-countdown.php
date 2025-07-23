<?php
/**
 * Flash Sales Countdown.
 *
 * @package ShopPress
 */

defined( 'ABSPATH' ) || exit;

use ShopPress\Modules\FlashSalesCountdown as ModulesFlashSalesCountdown;

global $product;

$show_title = $args['show_title'] ?? false;
$atts       = array();
if ( 'yes' === $show_title ) {
	$atts['title'] = sp_get_module_settings( 'flash_sale_countdown', 'timer_title' );
}

ModulesFlashSalesCountdown::display_countdown( $product, $atts );
