<?php
namespace Styler\Compatibilities\Widgets;

/**
 * Styler StyleSheet Class.
 *
 * @author  ClimaxThemes
 * @package Styler
 * @since   1.0.0
 */

// Don't load directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Styler\StyleSheet as StyleSheetManager;

class StyleSheet {

	/**
	 * Instance of this class.
	 *
	 * @since   1.0.0
	 * @access  public
	 * @var     Styler
	 */
	public static $instance;

	private $styles_data = array();


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
	 * Define the functionality .
	 *
	 * Load the dependencies.
	 *
	 * @since     1.0.0
	 */
	function __construct() {
		$this->actions();
	}

	/**
	 * Add WP Hooks
	 *
	 * @since     1.0.0
	 */
	public function actions() {
		add_action( 'admin_print_scripts-widgets.php', array( $this, 'enqueue_admin_scripts' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_style' ) );
		add_action( 'wp_ajax_save-widget', array( $this, 'save' ), -1 );
		add_action( 'wp_ajax_update-widget', array( $this, 'save' ), -1 );
	}

	/**
	 * Enqueue Admin Scripts
	 *
	 * @since     1.0.0
	 */
	public function enqueue_admin_scripts() {
		wp_enqueue_script( 'styler-wp-widgets', STYLER_ASSETS_URL . 'dist/styler-wp-widgets.js', array( 'lodash', 'wp-element', 'wp-i18n', 'wp-util' ), '', false );

		add_filter( 'styler-localize-data', array( __CLASS__, 'get_localize_data' ) );

		localize_styler_data();

		wp_enqueue_style( 'styler-wp-widgets', STYLER_ASSETS_URL . 'dist/styler-wp-widgets.css' );
	}

	/**
	 * Get Styler localize data
	 *
	 * @return array
	 */
	public static function get_localize_data( $data ) {
		$stylerData = get_option( 'styler-widget-object', array() );

		if ( $stylerData ) {
			$stylerData = static::json_decode( $stylerData );
		}

		if ( ! is_array( $stylerData ) ) {
			$stylerData = array();
		}

		$data['GeneratedStyles'] += $stylerData;

		return $data;
	}

	/**
	 * Json Decode
	 *
	 * @since     1.0.0
	 */
	private static function json_decode( $data ) {

		if ( is_array( $data ) ) {
			foreach ( $data as $key => $value ) {
				if ( is_array( $value ) ) {
					$data[ $key ] = static::json_decode( $value );
				} else {
					$data[ $key ] = json_decode( $value, true );
				}
			}
		}

		return $data;
	}

	/**
	 * Widget Save Handler
	 *
	 * @since     1.0.0
	 */
	public function save() {

		if ( isset( $_REQUEST['styler-field-name'] ) && ! isset( $_REQUEST['delete_widget'] ) ) {

			$request = current( $_REQUEST[ 'widget-' . $_REQUEST['id_base'] ] );
			$data    = get_option( 'styler-widget-object', array() );
			$data[ $_REQUEST['widget-id'] ][ $request['cid'] ] = \stripslashes( $request['stdata'] );

			update_option( 'styler-widget-object', $data );

			$this->styles_data = $data;
			$this->parse_style();

			wp_send_json_success();

		} elseif ( isset( $_REQUEST['styler-field-name'] ) && isset( $_REQUEST['delete_widget'] ) ) {

			$request = current( $_REQUEST[ 'widget-' . $_REQUEST['id_base'] ] );
			$data    = get_option( 'styler-widget-object', array() );

			unset( $data[ $_REQUEST['widget-id'] ] );
			update_option( 'styler-widget-object', $data );

			$this->styles_data = $data;
			$this->parse_style();

			wp_send_json_success();

		}
	}

	/**
	 * Enqueue Generated Styles
	 *
	 * @since     1.0.0
	 */
	public function enqueue_style() {
		if ( realpath( implode( DIRECTORY_SEPARATOR, array( get_styler_upload_path(), 'widgets', 'css', 'global-widget.css' ) ) ) ) {
			wp_enqueue_style( 'styler-widgets-global', implode( '/', array( get_styler_upload_url(), 'widgets', 'css', 'global-widget.css' ) ) );
		}
	}

	/**
	 * Enqueue Generated Styles
	 *
	 * @since     1.0.0
	 */
	public function parse_style() {

		$this->styles_data = get_option( 'styler-widget-object', array() );
		$this->__toString();
	}

	/**
	 * __toString
	 *
	 * @since     1.0.0
	 */
	public function __toString() {

		$styler_object = array();

		foreach ( $this->styles_data as $groupID => $value ) {

			foreach ( \array_reverse( $value, true ) as $cid => $style ) {

				if ( ! $style ) {
					continue;
				}

				$parsed_style = StyleSheetManager::get_instance()->prepare( $style );

				if ( is_array( $value ) ) {
					$styler_object[ $groupID ] [ $cid ] = $parsed_style;
				} else {
					$styler_object[] = $parsed_style;
				}
			}
		}

		update_option( 'styler-widget-style-object', $styler_object );

		StyleSheetManager::get_instance()->parse_content( 'widget', 'global', 'widgets' );
		StyleSheetManager::get_instance()->styles = array();

		return '';
	}
} // Class

StyleSheet::get_instance();
