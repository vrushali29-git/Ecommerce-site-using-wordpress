<?php
/**
 * Shop Page
 *
 * @package ShopPress\Templates
 *
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

get_header( 'shop' );

do_action( 'shoppress_before_shop' );

do_action( 'shoppress_shop' );

do_action( 'shoppress_after_shop' );

get_footer( 'shop' );
