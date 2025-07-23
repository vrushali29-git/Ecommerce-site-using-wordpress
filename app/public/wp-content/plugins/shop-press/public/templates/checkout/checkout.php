<?php
/**
 * Checkout
 *
 * @package ShopPress\Templates
 */

defined( 'ABSPATH' ) || exit;

// Before Content
do_action( 'shoppress_checkout_before_content' );

// Content
do_action( 'shoppress_checkout' );

// After Content
do_action( 'shoppress_checkout_after_content' );
