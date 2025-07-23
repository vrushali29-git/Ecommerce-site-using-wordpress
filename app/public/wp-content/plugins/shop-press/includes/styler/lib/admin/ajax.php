<?php
namespace Styler\Admin;

/**
 * Styler Admin Ajax Class.
 *
 * @author  ClimaxThemes
 * @package Styler
 * @since   1.0.0
 */

// Don't load directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Ajax {

	/**
	 * Instance of this class.
	 *
	 * @since   1.0.0
	 * @access  public
	 * @var     Styler
	 */
	public static $instance;

	/**
	 * Provides access to a single instance of a module using the singleton pattern.
	 *
	 * @since   1.0.0
	 * @return  object
	 */
	public static function get_instance() {
		if ( self::$instance === null ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Ajax constructor.
	 *
	 * Load the dependencies.
	 *
	 * @since     1.0.0
	 */
	function __construct() {
		$this->actions();
	}

	/**
	 * Setup Dependencies.
	 *
	 * @since 1.0.0
	 */
	protected function actions() {
		add_action( 'wp_ajax_styler-add-swatch', array( $this, 'add_swatch' ) );
	}

	/**
	 * Add Color Pickr Swatches
	 *
	 * @since     1.0.0
	 */
	public function add_swatch() {
		$color    = esc_attr( $_REQUEST['color'] );
		$swatches = get_option( 'styler-pickr-swatches', array() );
		$added    = 0;
		if ( array_search( $color, $swatches ) === false ) {
			$swatches[] = $color;
			update_option( 'styler-pickr-swatches', $swatches );
			$added = 1;
		}
		wp_send_json_success(
			array(
				'added' => $added,
			)
		);
	}
} // Class

Ajax::get_instance();
