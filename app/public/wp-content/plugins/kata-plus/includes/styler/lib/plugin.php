<?php
/**
 * Plugin.
 *
 * @package Styler
 */

namespace Styler;

defined( 'ABSPATH' ) || exit;

final class Plugin {
	/**
	 * Init Plugin class.
	 *
	 * @since 3.0
	 */
	public static function init() {
		self::load_dependencies();
		self::hooks();
	}

	/**
	 * Init hooks.
	 *
	 * @since 3.0
	 */
	private static function hooks() {
		add_action( 'after_setup_theme', array( __CLASS__, 'load_textdomain' ) );
		add_action( 'wp_ajax_st-add-swatch', array( __CLASS__, 'add_swatch' ) );
		add_action( 'wp_ajax_st-delete-swatch', array( __CLASS__, 'delete_swatch' ) );
		add_action( 'wp_ajax_st-add-gradient-swatch', array( __CLASS__, 'add_gradient_swatch' ) );
		add_action( 'wp_ajax_st-delete-gradient-swatch', array( __CLASS__, 'delete_gradient_swatch' ) );
	}

	/**
	 * Load dependencies.
	 *
	 * @since 1.0.0
	 */
	private static function load_dependencies() {
		require_once STYLER_PATH . 'lib/utils/loader.php';
		require_once STYLER_PATH . 'lib/functions/functions-general.php';
		styler_loader( STYLER_PATH . 'lib/utils', 'data' );
		styler_loader( STYLER_PATH . 'lib', 'setup' );
		styler_loader( STYLER_PATH . 'lib', 'localize-data' );
		// styler_loader( STYLER_PATH . 'test/gutenberg', 'setup' );
	}

	/**
	* Add Color Swatches
	*
	* @since     1.0.0
	*/
	public function delete_swatch() {
		$swatch   = esc_attr( $_REQUEST['swatch'] );
		$swatches = get_option( 'styler-pickr-swatches', array( '#000000', '#FFFFFF', '#F44336', '#E91E63', '#9C27B0', '#673AB7' ) );

		if( ! is_array( $swatches ) ) {
			$swatches = json_decode( $swatches );
		}

		$_swatches  = [];
		foreach ($swatches as $value) {
			if( $value !== $swatch ) {
				$_swatches[] = $value;
			}
		}

		update_option( 'styler-pickr-swatches', $_swatches );

		wp_send_json( $_swatches );
	}

	/**
	* Add Color Swatches
	*
	* @since     1.0.0
	*/
	public function delete_gradient_swatch() {
		$swatch   = esc_attr( $_REQUEST['swatch'] );
		$gradient_swatches = get_option( 'styler-gradient-swatches', [
			'linear-gradient(135deg,#12c2e9 0%,#c471ed 50%,#f64f59 100%)',
			'linear-gradient(135deg,#0F2027 0%, #203A43 0%, #2c5364 100%)',
			'linear-gradient(135deg,#1E9600 0%, #FFF200 0%, #FF0000 100%)',
		] );

		if( ! is_array( $gradient_swatches ) ) {
			$gradient_swatches = json_decode( $gradient_swatches );
		}

		$_swatches  = [];
		foreach ($gradient_swatches as $value) {
			if( $value !== $swatch ) {
				$_swatches[] = $value;
			}
		}

		update_option( 'styler-gradient-swatches', $_swatches );

		wp_send_json( $_swatches );
	}

	/**
	* Add Color Swatches
	*
	* @since     1.0.0
	*/
	public static function add_swatch() {
		$swatch   = esc_attr( $_REQUEST['swatch'] );
		$swatches = get_option( 'styler-pickr-swatches', array( '#000000', '#FFFFFF', '#F44336', '#E91E63', '#9C27B0', '#673AB7' ) );

		if( ! is_array( $swatches ) ) {
			$swatches = json_decode( $swatches );
		}

		if( array_search( $swatch, $swatches ) === false ) {
			$swatches[] = $swatch;
			update_option( 'styler-pickr-swatches', $swatches );
		}

		wp_send_json( $swatches );
	}

	/**
	* Add Color Swatches
	*
	* @since     1.0.0
	*/
	public static function add_gradient_swatch() {
		$swatch   = esc_attr( $_REQUEST['swatch'] );
		$gradient_swatches = get_option( 'styler-gradient-swatches', [
			'linear-gradient(135deg,#12c2e9 0%,#c471ed 50%,#f64f59 100%)',
			'linear-gradient(135deg,#0F2027 0%, #203A43 0%, #2c5364 100%)',
			'linear-gradient(135deg,#1E9600 0%, #FFF200 0%, #FF0000 100%)',
		] );

		if( ! is_array( $gradient_swatches ) ) {
			$gradient_swatches = json_decode( $gradient_swatches );
		}

		if( array_search( $swatch, $gradient_swatches ) === false ) {

			$gradient_swatches[] = $swatch;

			update_option( 'styler-gradient-swatches', $gradient_swatches );
		}

		wp_send_json( $gradient_swatches );
	}

	public static function findGradientBySwatch( $gradient_swatches, $swatch ){
		foreach ($gradient_swatches as $gradient) {
			if ($gradient['gradient'] == $swatch) {
				return true;
			}
		}
		return false;
	}

	/**
	 * Initialize styler.
	 *
	 * @since 1.0.0
	 */
	public static function load_textdomain() {
		load_plugin_textdomain( 'styler', false, STYLER_PATH . 'languages/' );
	}
}
