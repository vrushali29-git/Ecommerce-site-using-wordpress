<?php
namespace Styler\Compatibilities\MenuStyler;

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
		// add action to wp nav menus save hook
		add_action( 'wp_update_nav_menu', array( $this, 'save_menu_styles' ), 10, 2 );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_style' ), 999 );
	}

	/**
	 * Add WP Hooks
	 *
	 * @since     1.0.0
	 */
	public function save_menu_styles() {
		$menus = wp_get_nav_menus();

		foreach ($menus as $key => $menu) {

			$menu_items = wp_get_nav_menu_items( $menu );

			foreach ( $menu_items as $menu_item ) {
				$styler_data = get_post_meta( $menu_item->ID, '_menu_item_styler', true );
				if ( isset( $styler_data['cid'] ) && json_decode( $styler_data['stdata'], true ) ) {
					$this->styles_data[ $cid ] = array(
						'cid'    => $styler_data['cid'],
						'stdata' => json_decode( $styler_data['stdata'], true ),
					);
				}
			}
			$this->parse_style( $menu->term_id );
		}
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
	 * Enqueue Generated Styles
	 *
	 * @since     1.0.0
	 */
	public function enqueue_style() {
		$menus = wp_get_nav_menus();
		foreach ($menus as $key => $menu) {
			if ( realpath( implode( DIRECTORY_SEPARATOR, array( get_styler_upload_path(), 'menu', 'css', 'menu-' . $menu->term_id .'.css' ) ) ) ) {
				wp_enqueue_style( 'styler-menu-frontend-' . $menu->term_id, implode( '/', array( get_styler_upload_url(), 'menu', 'css', 'menu-' . $menu->term_id .'.css' ) ), array(),  STYLER_VERSION );
			}
		}
	}

	/**
	 * Enqueue Generated Style
	 *
	 * @since     1.0.0
	 */
	public function parse_style( $term_id ) {
		$this->__toString( $term_id );
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

		StyleSheetManager::get_instance()->parse_content( $postID , 'menu', 'menu' );
		StyleSheetManager::get_instance()->styles = array();
		return '';
	}
} // Class

StyleSheet::get_instance();
