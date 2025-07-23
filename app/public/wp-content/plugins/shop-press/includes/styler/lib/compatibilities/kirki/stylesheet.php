<?php
namespace Styler\Compatibilities\KirkiStyler;

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
		// add_action('admin_enqueue_scripts', [$this, 'enqueue_admin_scripts']);
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_style' ), 999 );
		add_action( 'wp_footer', array( $this, 'print_inline_style' ), 999 );
		add_action( 'wp_ajax_save-kirki-styler', array( $this, 'save' ), -1 );
		add_action( 'wp_ajax_save-kirki-styler-customizer', array( $this, 'save_customizer' ), -1 );
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
	 * Customizer Save Handler
	 *
	 * @since     1.0.0
	 */
	public function save_customizer() {

		if ( ! isset( $_REQUEST['data'] ) ) {
			return false;
		}

		$term = get_term_by( 'slug', 'kirki', 'styler-data' );

		if ( $term === null && $term === false ) {
			wp_insert_term( 'kirki', 'styler-data' );
			$term = get_term_by( 'slug', 'kirki', 'styler-data' );
		}

		$data = json_decode( stripslashes( $_REQUEST['data'] ), true );
		foreach ( $data as $cid => $value ) {

			delete_term_meta( $term->term_id, $cid );
			$cid = str_replace( 'styler_', '', $cid );
			$this->styles_data[ $cid ] = array(
				'cid'    => $cid,
				'stdata' => $value,
			);
			update_term_meta( $term->term_id, $cid, $this->styles_data[ $cid ] );
		}

		$this->parse_style();
		wp_send_json_success();
	}

	/**
	 * Customizer Save Handler
	 *
	 * @since     1.0.0
	 */
	public function import_customizer( $data ) {

		$term = get_term_by( 'slug', 'kirki', 'styler-data' );

		if ( $term === null || $term === false ) {
			wp_insert_term( 'kirki', 'styler-data' );
			$term = get_term_by( 'slug', 'kirki', 'styler-data' );
		}

		if( ! $term ) {
			return false;
		}

		if( ! is_object( $data ) && ! is_array( $data ) ) {
			$data = json_decode( $data );
		}

		foreach ( $data as $key => $value ) {
			if ( is_string( $value[0] ?? array() ) ) {
				$value = unserialize( current( $value ) );
			}

			$cid = $value['cid'];

			delete_term_meta( $term->term_id, $cid );

			$cid = str_replace( 'styler_', '', $cid );

			$this->styles_data[ $cid ] = $value;

			update_term_meta( $term->term_id, $cid, $this->styles_data[ $cid ] );
		}

		$this->parse_style();
		return true;
	}

	/**
	 * Save Handler
	 *
	 * @since     1.0.0
	 */
	public function save() {

		if ( ! isset( $_REQUEST['field_id'] ) ) {
			return false;
		}

		$term = get_term_by( 'slug', 'kirki', 'styler-data' );

		if ( $term === null && $term === false ) {
			wp_insert_term( 'kirki', 'styler-data' );
			$term = get_term_by( 'slug', 'kirki', 'styler-data' );
		}

		$field_id = esc_attr( $_REQUEST['field_id'] );
		$cid      = esc_attr( $_REQUEST['cid'] );
		$data     = $_REQUEST['data'];

		$field_id = str_replace( 'styler_', '', $field_id );

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
		if( is_customize_preview() ) {
			// $path = realpath( implode( DIRECTORY_SEPARATOR, array( get_styler_upload_path(), 'kirki', 'css', 'kirki-styler.css' ) ) );
			// if ( $path ){
			// 	$content = file_get_contents( $path );
			// 	echo "<style id=\"styler-kirki\">{$content}</style>";
			// }
		} else if ( realpath( implode( DIRECTORY_SEPARATOR, array( get_styler_upload_path(), 'kirki', 'css', 'kirki-styler.css' ) ) ) ) {
			$deps = apply_filters( 'styler-kirki-frontend', array() );
			wp_enqueue_style( 'styler-kirki-frontend', implode( '/', array( get_styler_upload_url(), 'kirki', 'css', 'kirki-styler.css' ) ), $deps,  STYLER_VERSION );
		}
	}
	/**
	 * Enqueue Generated Styles
	 *
	 * @since     1.0.0
	 */
	public function print_inline_style() {
		if( is_customize_preview() ) {
			$path = realpath( implode( DIRECTORY_SEPARATOR, array( get_styler_upload_path(), 'kirki', 'css', 'kirki-styler.css' ) ) );
			if ( $path ){
				$content = file_get_contents( $path );
				echo "<style id=\"styler-kirki\">{$content}</style>";
			}
		}
	}

	/**
	 * Enqueue Generated Style
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

			$value = \maybe_unserialize( $value );

			if ( ! $value['stdata'] ) {
				continue;
			}

			$value['stdata'] = json_encode( $value['stdata'] );

			if( preg_match('/"font-family":{"value":"(.*?)"/', $value['stdata'], $matches ) ) {
				$value['stdata'] = preg_replace('/"font-family":{"value":"(.*?)"/', '"font-family":{"value":"' . str_replace( '"', "'", $matches[1] ) . '"', $value['stdata'] );
			}

			$value['stdata'] = json_decode( $value['stdata'], true );

			StyleSheetManager::get_instance()->prepare( $value['stdata'] );
		}

		StyleSheetManager::get_instance()->parse_content( 'styler', 'kirki', 'kirki' );
		StyleSheetManager::get_instance()->styles = array();

		return '';
	}
} // Class

StyleSheet::get_instance();
