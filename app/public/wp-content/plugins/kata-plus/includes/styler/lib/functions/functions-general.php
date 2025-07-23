<?php
/**
 * Styler functions.
 *
 * @package Styler
 */

defined( 'ABSPATH' ) || exit;

/**
 * File loader.
 *
 * @since 3.0
 *
 * @param string $base_dir
 * @param string $name
 * @param string $prefix
 * @param string $suffix
 *
 * @return void
 */
function styler_loader( $base_dir, $name, $prefix = '', $suffix = '' ) {
	Styler\Utils\Loader::load( $base_dir, $name, $prefix, $suffix );
}

/**
 * Returns styler upload dir url.
 *
 * @since 3.0
 *
 * @return string
 */
function get_styler_upload_url() {
	$wp_upload_dir = wp_upload_dir();

	return rtrim(trailingslashit( $wp_upload_dir['baseurl'] . '/styler' ), "/");
}

/**
 * Returns styler upload dir path.
 *
 * @since 3.0
 *
 * @return string
 */
function get_styler_upload_path() {
	$wp_upload_dir = wp_upload_dir();

	return trailingslashit( $wp_upload_dir['basedir'] . DIRECTORY_SEPARATOR . 'styler' );
}

/**
 * Localize Styler data.
 *
 * @since 3.0
 *
 * @return void
 */
function localize_styler_data() {
	Styler\LocalizeData::localize();
}
