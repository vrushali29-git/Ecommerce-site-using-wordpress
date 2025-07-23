<?php
/**
 * Archive Product
 *
 * @package ShopPress\Templates
 *
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

get_header();

do_action( 'shoppress_archive_before_content' );

do_action( 'shoppress_archive' );

do_action( 'shoppress_archive_after_content' );

get_footer();
