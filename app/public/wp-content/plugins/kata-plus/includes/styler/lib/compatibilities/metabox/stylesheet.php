<?php
namespace Styler\Compatibilities\MetaBox;

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
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_style' ) );
	}

	/**
	 * Enqueue Generated Styles
	 *
	 * @since     1.0.0
	 */
	public function enqueue_style() {

		$id = \get_the_ID();

		if ( realpath( implode( DIRECTORY_SEPARATOR, array( get_styler_upload_path(), 'rwmb', 'css', get_post_type( $id ) . "-{$id}.css" ) ) ) ) {
			wp_enqueue_style( 'styler-rwmb-' . get_post_type( $id ) . '-' . $id, implode( '/', array( get_styler_upload_url(), 'rwmb', 'css', get_post_type( $id ) . "-{$id}.css" ) ) );
		}
	}

	/**
	 * Enqueue Generated Styles
	 *
	 * @since     1.0.0
	 */
	public function parse_style( $id ) {

		$this->styles_data = get_post_meta( $id, 'rwmb_styler_data', true );
		$this->__toString( $id );
	}

	/**
	 * __toString
	 *
	 * @since     1.0.0
	 */
	public function __toString() {

		if ( func_num_args() > 0 ) {
			$postID = func_get_arg( 0 );
		} else {
			$postID = false;
		}

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

		if ( $postID ) {
			update_post_meta( $postID, 'rwmb_styler_object', $styler_object );
		}

		StyleSheetManager::get_instance()->parse_content( $postID, get_post_type( $postID ), 'rwmb' );
		StyleSheetManager::get_instance()->styles = array();

		return '';
	}
} // Class

StyleSheet::get_instance();
