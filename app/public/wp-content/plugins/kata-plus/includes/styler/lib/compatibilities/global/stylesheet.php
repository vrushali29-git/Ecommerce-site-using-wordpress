<?php
namespace Styler\Compatibilities\GlobalStyler;

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
		add_action( 'wp_ajax_save-global-styler', array( $this, 'save' ), -1 );
		add_action( 'init', array( $this, 'register_taxonomy' ), -1 );
	}

	/**
	 * Register styler-data Taxonomy
	 *
	 * @since     1.0.0
	 */
	public function register_taxonomy() {

		\register_taxonomy(
			'styler-data',
			'styler',
			array(
				'hierarchical'      => true,
				'show_ui'           => false,
				'query_var'         => true,
				'rewrite'           => false,
				'show_in_nav_menus' => false,
			)
		);
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

		if ( ! isset( $_REQUEST['field_id'] ) ) {
			return false;
		}

		$term = get_term_by( 'slug', 'global', 'styler-data' );

		if ( $term === null && $term === false ) {
			wp_insert_term( 'global', 'styler-data' );
			$term = get_term_by( 'slug', 'global', 'styler-data' );
		}

		$field_id = esc_attr( $_REQUEST['field_id'] );
		$cid      = esc_attr( $_REQUEST['cid'] );
		$data     = $_REQUEST['data'];

		update_term_meta(
			$term->term_id,
			$field_id,
			array(
				'cid'    => $cid,
				'stdata' => $data,
			)
		);

		$this->styles_data = get_term_meta( $term->term_id );

		$this->parse_style();

		wp_send_json_success();
	}

	/**
	 * Enqueue Generated Styles
	 *
	 * @since     1.0.0
	 */
	public function enqueue_style() {
		if ( realpath( implode( DIRECTORY_SEPARATOR, array( get_styler_upload_path(), 'global', 'css', 'global-styler.css' ) ) ) ) {
			wp_enqueue_style( 'styler-global-frontend', implode( '/', array( get_styler_upload_url(), 'global', 'css', 'global-styler.css' ) ) );
		}
	}

	/**
	 * Enqueue Generated Styles
	 *
	 * @since     1.0.0
	 */
	public function parse_style() {
		$this->__toString();
	}

	/**
	 * __toString
	 *
	 * @since     1.0.0
	 */
	public function __toString() {

		foreach ( $this->styles_data as $groupID => $value ) {

			$value = \unserialize( $value[0] );
			if ( ! $value['stdata'] ) {
				continue;
			}

			StyleSheetManager::get_instance()->prepare( $value['stdata'] );
		}

		StyleSheetManager::get_instance()->parse_content( 'styler', 'global', 'global' );
		StyleSheetManager::get_instance()->styles = array();

		return '';
	}
} // Class

StyleSheet::get_instance();
