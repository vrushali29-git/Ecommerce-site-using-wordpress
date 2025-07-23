<?php
/**
 * Localize data.
 *
 * @package Styler
 */

namespace Styler;

defined( 'ABSPATH' ) || exit;

class LocalizeData {
	/**
	 * Styler data.
	 *
	 * @var object
	 */
	public static $data;

	/**
	 * Localize Styler data.
	 *
	 * @return array
	 */
	public static function localize() {

		if ( ! self::$data ) {

			$swatches = apply_filters( 'styler-pickr-swatches', get_option( 'styler-pickr-swatches', array( '#00000000' , '#000000', '#FFFFFF', '#F44336', '#E91E63', '#9C27B0', '#673AB7' ) ) );

			$gradient_swatches = get_option( 'styler-gradient-swatches', [
				'linear-gradient(135deg,#12c2e9 0%,#c471ed 50%,#f64f59 100%)',
				'linear-gradient(135deg,#0F2027 0%, #203A43 0%, #2c5364 100%)',
				'linear-gradient(135deg,#1E9600 0%, #FFF200 0%, #FF0000 100%)',
			] );


			$isPro = apply_filters( 'styler-is-pro', false );

			self::$data = array(
				'nonce'           => wp_create_nonce( 'styler' ),
				'ajaxUrl'         => admin_url( 'admin-ajax.php' ),
				'commands'        => '',
				'currentPageID'   => apply_filters( 'styler-current-pageID', false ),
				'doDestroy'       => true,
				'pickers'         => (object) array(),
				'assetsURL'       => STYLER_ASSETS_URL,
				'storage'         => '',
				'config'          => array(
					'swatches'  => $swatches,
					'gradients' => $gradient_swatches,
				),
				'GeneratedStyles' => array(),
				'isPro' => $isPro,
			);
		}

		self::$data = apply_filters( 'styler-localize-data', self::$data );

		if ( current_action() === 'customize_controls_enqueue_scripts' ) {
			add_action( 'customize_controls_print_scripts', array( __CLASS__, 'enqueue_localize_data' ) );
		} else if ( current_action() === 'wp_enqueue_scripts' ) {
			self::enqueue_localize_data();
		} elseif ( ! did_action( 'wp_print_scripts' ) ) {
			add_action( 'wp_print_scripts', array( __CLASS__, 'enqueue_localize_data' ) );
		} else {
			add_action( 'admin_footer', array( __CLASS__, 'enqueue_localize_data' ) );
		}
	}

	public static function enqueue_localize_data() {
		if ( self::$data ) {
			remove_action( 'wp_print_scripts', array( __CLASS__, 'enqueue_localize_data' ) );
			echo '<script>var styler=' . json_encode( self::$data, true ) . '</script>';
		}
	}
}
