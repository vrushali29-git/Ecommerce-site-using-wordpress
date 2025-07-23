<?php
/**
 * Flash Sales Countdown.
 *
 * @package ShopPress
 */

defined( 'ABSPATH' ) || exit;

use ShopPress\Modules\FlashSalesCountdown as ModulesFlashSalesCountdown;

global $product;

ModulesFlashSalesCountdown::display_countdown( $product );
