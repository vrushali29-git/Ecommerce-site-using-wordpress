<?php
namespace Styler\Compatibilities\Gutenberg;

/**
 * Styler Gutenberg StyleSheet Class.
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
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_style' ), 999 );
		// add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_inline_styles' ] );
		// add_action( 'wp_ajax_save-gutenberg-styler', [ $this, 'save' ], -1 );
		add_action( 'edit_post', array( $this, 'save' ), -1, 2 );
		add_action( 'styler-gutenberg-custom-save', array( $this, 'customSave' ), -1, 2 );
	}


	/**
	 * Enqueue Generated Styles
	 *
	 * @since     1.0.0
	 */
	public function enqueue_inline_styles() {
		$id = get_the_ID();
		if ( is_preview() || Plugin::$instance->preview->is_preview_mode() || $force ) {
			$styler_object = get_post_meta( $id, 'styler_object', true );
			$styler_object = is_array( $styler_object ) ? $styler_object : array();

			foreach ( $styler_object as $key => $value ) {
				$css = '';
				if ( is_array( $value ) ) {
					foreach ( $value as $style ) {
						$css .= $style;
					}
				} else {
					$css .= $value;
				}

				printf( '<style id="%1$s">%2$s</style>', 'styler-' . $key, $css );
			}
		}
	}

	/**
	 * Parse Gutenberg Blocks
	 *
	 * @since     1.0.0
	 */
	public function parseContent( $content, $blocksStylers = array() ) {
		foreach ( $content as $block ) {
			if ( isset( $block['attrs'] ) ) {
				if ( isset( $block['attrs']['styler'] ) ) {
					$blocksStylers[] = $block['attrs']['styler'];
				}
			}

			if ( $block['innerBlocks'] ) {
				$blocksStylers = $this->parseContent( $block['innerBlocks'], $blocksStylers );
			}
		}

		return $blocksStylers;
	}

	/**
	 * Widget Save Handler
	 *
	 * @since     1.0.0
	 */
	public function save( $post_ID, $post ) {
		$content       = parse_blocks( $post->post_content );
		$blocksStylers = $this->parseContent( $content );
		$stylerData    = array();

		foreach ( $blocksStylers as $blockStylers ) {
			$blockStylers = json_decode( $blockStylers, true );

			if ( ! is_array( $blockStylers ) ) {
				continue;
			}

			foreach ( $blockStylers as $key => $data ) {
				if ( is_array( $data['data'] ) || is_object( $data['data'] ) ) {
					$stylerData[ $data['cid'] ] = $data['data'];
				} else {
					$stylerData[ $data['cid'] ] = json_decode( $data['data'], true );
				}
			}
		}

		update_post_meta( $post_ID, 'gutenberg_styler_data', $stylerData );

		$this->parse_style( $post_ID );
	}

	/**
	 * Widget Save Handler
	 *
	 * @since     1.0.0
	 */
	public function customSave( $post_ID, $post_content ) {

		$content       = parse_blocks( $post_content );
		$blocksStylers = $this->parseContent( $content );
		$stylerData    = array();

		foreach ( $blocksStylers as $blockStylers ) {
			$blockStylers = json_decode( $blockStylers, true );

			if ( ! is_array( $blockStylers ) ) {
				continue;
			}

			foreach ( $blockStylers as $key => $data ) {
				$stylerData[ $data['cid'] ] = json_decode( $data['data'], true );
			}
		}

		update_post_meta( $post_ID, 'gutenberg_styler_data', $stylerData );

		$this->parse_style( $post_ID );
	}

	/**
	 * Enqueue Generated Styles
	 *
	 * @since     1.0.0
	 */
	public function enqueue_style() {
		$id = apply_filters( 'styler/block_editor/post_id', get_the_ID() );

		if ( realpath( implode( DIRECTORY_SEPARATOR, array( get_styler_upload_path(), 'gutenberg', 'css', get_post_type( $id ) . "-{$id}.css" ) ) ) ) {
			wp_enqueue_style( 'styler-gutenberg-' . get_post_type( $id ) . '-' . $id, implode( '/', array( get_styler_upload_url(), 'gutenberg', 'css', get_post_type( $id ) . "-{$id}.css" ) ) );
		}
	}

	/**
	 * Enqueue Generated Style
	 *
	 * @since     1.0.0
	 */
	public function parse_style( $id ) {

		$this->styles_data = get_post_meta( $id, 'gutenberg_styler_data', true );
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

		if ( ! is_array( $this->styles_data ) ) {
			return false;
		}

		foreach ( \array_reverse( $this->styles_data, true ) as $cid => $style ) {

			if ( ! $style ) {
				continue;
			}

			$parsed_style = StyleSheetManager::get_instance()->prepare( $style );

			$styler_object[ $cid ] = $parsed_style;
		}

		if ( $postID ) {
			update_post_meta( $postID, 'gutenberg_styler_object', $styler_object );
		}

		StyleSheetManager::get_instance()->parse_content( $postID, get_post_type( $postID ), 'gutenberg' );
		StyleSheetManager::get_instance()->styles = array();

		return '';
	}
} // Class

StyleSheet::get_instance();
