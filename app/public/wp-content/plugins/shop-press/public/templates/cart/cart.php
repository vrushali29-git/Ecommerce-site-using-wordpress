<?php
/**
 * Cart Page
 *
 * @package ShopPress\Templates
 *
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_cart' );

do_action( 'shoppress_cart' );

do_action( 'woocommerce_before_cart_collaterals' );

do_action( 'woocommerce_after_cart' );
