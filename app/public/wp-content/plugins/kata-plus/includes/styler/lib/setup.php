<?php
namespace Styler;

defined( 'ABSPATH' ) || exit;

class Setup {
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
	 * Define the functionality .
	 *
	 * Load the dependencies.
	 *
	 * @since     1.0.0
	 */
	function __construct() {
		$this->dependencies();
		$this->actions();
	}

	/**
	 * Add WP Hooks
	 *
	 * @since     1.0.0
	 */
	public function actions() {
		// Print Styler Wrapper
		add_action( 'admin_footer', array( $this, 'print_wrapper' ) );
		add_action( 'elementor/editor/footer', array( $this, 'print_wrapper' ) );
		add_action( 'customize_controls_print_footer_scripts', array( $this, 'print_wrapper' ) );
		add_action( 'wp_ajax_styler_elementor_full_site_editor_compatibility', array( $this, 'styler_elementor_full_site_editor_compatibility' ) );
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
		} else {
			return json_decode( $data );
		}

		return $data;
	}

	/**
	 * Full site editor compatibility
	 *
	 * @return JSON
	 */
	public static function styler_elementor_full_site_editor_compatibility() {
		check_ajax_referer( 'styler', 'nonce' );
		$post_id = sanitize_text_field( $_POST['id'] );
		$meta    = get_post_meta( $post_id, 'styler_data', true );
		if ( ! empty( $meta ) ) {
			$data = json_decode( $meta );
			wp_send_json_success( array( 'message' => $data ), 200 );
		} else {
			wp_send_json_error( array( 'message' => false ), 403 );
		}
		wp_die();
	}

	/**
	 * Setup Dependencies.
	 *
	 * @since 1.0.0
	 */
	protected function dependencies() {
		$currentDir = STYLER_PATH . '/lib/';

		styler_loader( STYLER_PATH . 'vendor', 'autoload' );

		styler_loader( $currentDir . 'utils', 'upload' );

		styler_loader( $currentDir . 'admin', 'ajax' );

		// BreakPoints
		styler_loader( $currentDir . 'breakpoints', 'breakpoint' );
		styler_loader( $currentDir . 'breakpoints', 'manager' );

		// StyleSheet
		styler_loader( $currentDir, 'stylesheet' );

		// Compatibilities
		if ( is_dir( $currentDir . 'compatibilities/elementor' ) ) {
			if ( did_action( 'elementor/loaded' ) ) {
				styler_loader( $currentDir . 'compatibilities/elementor', 'init' );
				styler_loader( $currentDir . 'compatibilities/elementor', 'stylesheet' );
			}
		}

		if ( is_dir( $currentDir . 'compatibilities/kirki' ) ) {
			if( class_exists( 'kirki' ) ) {
				add_action(
					'customize_register',
					function () use ( $currentDir ) {
						styler_loader( $currentDir . 'compatibilities/kirki', 'init' );
					}
				);
				styler_loader( $currentDir . 'compatibilities/kirki', 'stylesheet' );
			}
		}

		if ( is_dir( $currentDir . 'compatibilities/metabox' ) ) {
			if ( class_exists( 'RWMB_Loader' ) ) {
				styler_loader( $currentDir . 'compatibilities/metabox', 'init' );
				styler_loader( $currentDir . 'compatibilities/metabox', 'stylesheet' );
			}
		}

		if ( is_dir( $currentDir . 'compatibilities/gutenberg' ) ) {
			styler_loader( $currentDir . 'compatibilities/gutenberg', 'init' );
			styler_loader( $currentDir . 'compatibilities/gutenberg', 'stylesheet' );
		}

		if ( is_dir( $currentDir . 'compatibilities/climaxformbuilder' ) ) {
			styler_loader( $currentDir . 'compatibilities/climaxformbuilder', 'init' );
			styler_loader( $currentDir . 'compatibilities/climaxformbuilder', 'stylesheet' );
		}

		if ( is_dir( $currentDir . 'compatibilities/menu' ) ) {
			styler_loader( $currentDir . 'compatibilities/menu', 'init' );
			styler_loader( $currentDir . 'compatibilities/menu', 'stylesheet' );
		}

		if ( is_dir( $currentDir . 'compatibilities/wp-widgets' ) ) {
			// styler_loader( $currentDir . 'compatibilities/wp-widgets', 'init' );
			// styler_loader( $currentDir . 'compatibilities/wp-widgets', 'stylesheet' );
		}
	}

	/**
	 * Print Styler Wrapper.
	 *
	 * @since 1.0.0
	 */
	public function print_wrapper() {
		?>
		<div id="tmp-styler-wrap" class="tmp-styler-wrap"></div>
		<div id="tmp-styler-wrap-message" class="tmp-styler-wrap-message"></div>
		<?php
	}
} // Class

Setup::get_instance();
