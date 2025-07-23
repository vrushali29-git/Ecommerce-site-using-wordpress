<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Styler\StyleSheet as StyleSheetManager;

/**
 *
 * Styler Control for Gutenberg Use
 */
class StylerGutenbergField {

	/**
	 * Instance of this class.
	 *
	 * @since   1.0.0
	 * @access  public
	 * @var     Styler
	 */
	public static $instance;
	private $registered = array();

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
		global $pagenow;

		if ( $pagenow === 'post.php' || $pagenow === 'post-new.php' ) {
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );
		}
	}

	/**
	 * Check if the Gutenberg editor is being used
	 *
	 * @since   3.0.1
	 */
	private static function is_gutenberg_editor() {
		if( function_exists( 'is_gutenberg_page' ) && is_gutenberg_page() ) {
			// Return true if Gutenberg is being used
			return true;
		}
		// Get the current screen
		$current_screen = get_current_screen();
		// Check if the current screen has a method to check if it is the block editor
		if ( method_exists( $current_screen, 'is_block_editor' ) && $current_screen->is_block_editor() ) {
			// Return true if the block editor is being used
			return true;
		}
		// Return false if neither Gutenberg nor the block editor is being used
		return false;
	}

	/**
	 *
	 * Save Handler
	 *
	 * @return void
	 */
	public static function save( $new, $old, $post_id, $field ) {
		if ( empty( $field['id'] ) || ! $field['save_field'] && isset( $_REQUEST[ $field['id'] ] ) ) {
			return;
		}

		$new    = $_REQUEST[ $field['id'] ];
		$stdata = get_post_meta( $post_id, 'gutenberg_styler_data', true );
		if ( $stdata && is_array( $stdata ) ) {
			$stdata[ 'gutenberg' . $post_id ]                = is_array( $stdata[ 'gutenberg' . $post_id ] ) ? $stdata[ 'gutenberg' . $post_id ] : array();
			$stdata[ 'gutenberg' . $post_id ][ $new['cid'] ] = $new['stdata'];
		} else {
			$stdata = array(
				'gutenberg' . $post_id => array(
					$new['cid'] => $new['stdata'],
				),
			);
		}

		update_post_meta( $post_id, 'gutenberg_styler_data', $stdata );

		\Styler\Compatibilities\MetaBox\StyleSheet::get_instance()->parse_style( $post_id );

		$name    = $field['id'];
		$storage = $field['storage'];

		$storage->delete( $post_id, $name );
		$storage->update( $post_id, $name, $new );
	}

	/**
	 *
	 * Get Control Uid
	 *
	 * @return string
	 */
	private function check( $field_id ) {
		if ( in_array( $field_id, $this->registered ) ) {
			/* Translators: %s replaced with $field_id */
			throw new \Exception( \wp_sprintf( __( 'Fatal error: Cannot redeclare <%s>' ), $field_id ), 1 );
		}
		$this->registered[] = $field_id;
	}

	/**
	 * Enqueue Admin Scripts
	 *
	 * @since     1.0.0
	 */
	public function enqueue_admin_scripts() {

		if ( is_customize_preview() || ! static::is_gutenberg_editor() ) {
			return;
		}

		$asset_file = include( STYLER_PATH . 'build/styler-chunk.asset.php');

		wp_enqueue_script( 'styler-gutenberg-chunk', STYLER_URL . 'build/styler-chunk.js', $asset_file['dependencies'], $asset_file['version'], true );

		$asset_file = include STYLER_PATH . '/build/styler-gutenberg.asset.php';

		wp_enqueue_script( 'styler-gutenberg', STYLER_URL . 'build/styler-gutenberg.js', array_merge( array( 'lodash', 'wp-element', 'wp-i18n', 'wp-util' ), $asset_file['dependencies'] ), $asset_file['version'], true );

		add_filter( 'styler-localize-data', array( $this, 'get_localize_data' ), 10, 1 );

		localize_styler_data();

		wp_enqueue_style(  'styler-style-dark', STYLER_URL . 'build/styler-dark.css', [],  $asset_file['version'], '(prefers-color-scheme: dark)');

		wp_enqueue_style(  'styler-style-chunk', STYLER_URL . 'build/styler-chunk.css' );

		wp_enqueue_style(  'styler-gutenberg', STYLER_URL . 'build/styler-gutenberg.css', array( ), $asset_file['version'], false );

		wp_enqueue_media();

		$this->print_inline_styles();
	}

	/**
	 * Print Admin Inline Styles
	 *
	 * @since     1.0.0
	 */
	private function print_inline_styles() {

		$id            = get_the_ID();
		$styler_object = get_post_meta( get_the_ID(), 'gutenberg_styler_object', true );
		$styler_data   = get_post_meta( get_the_ID(), 'gutenberg_styler_data', true );
		$styler_object = is_array( $styler_object ) ? $styler_object : array();

		if ( $styler_data && ! $styler_object ) {
			foreach ( $styler_data as $cid => $style ) {

				if ( ! $style ) {
					continue;
				}

				$parsed_style = StyleSheetManager::get_instance()->prepare( $style );

				$styler_object[ $cid ] = $parsed_style;
			}
		}

		$css = '';
		foreach ( $styler_object as $key => $value ) {
			if ( is_array( $value ) ) {
				foreach ( $value as $style ) {
					$css .= $style;
				}
			} else {
				$css .= $value;
			}
		}

		printf( '<style id="%1$s">%2$s</style>', 'styler-gutenberg', $css );
	}

	/**
	 * Get Styler localize data
	 *
	 * @return array
	 */
	public static function get_localize_data( $data ) {
		$stylerData = get_post_meta( get_the_ID(), 'gutenberg_styler_data', true );

		if ( ! is_array( $stylerData ) ) {
			$stylerData = array();
		}

		$data['GeneratedStyles'] += array(
			'gutenberg' => $stylerData,
		);

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
}

StylerGutenbergField::get_instance();
