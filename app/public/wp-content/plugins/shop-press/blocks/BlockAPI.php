<?php
/**
 * Block API.
 *
 * @package ShopPress
 */

namespace ShopPress\BlockEditor;

defined( 'ABSPATH' ) || exit;

use ShopPress\Templates;

class BlockAPI {
	/**
	 * Instance of this class.
	 *
	 * @since  1.4.0
	 */
	public static $instance;

	/**
	 * Provides access to a single instance of a module using the singleton pattern.
	 *
	 * @since  1.4.0
	 *
	 * @return object
	 */
	public static function instance() {
		if ( self::$instance === null ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Constructor.
	 *
	 * @since 1.4.0
	 */
	public function __construct() {
		add_action( 'rest_api_init', array( $this, 'register_routes' ) );
	}

	/**
	 * Register routes.
	 *
	 * @since 1.4.0
	 */
	public function register_routes() {
		register_rest_route(
			'sp-block',
			'/loop-templates',
			array(
				array(
					'methods'             => \WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_loop_templates' ),
					'permission_callback' => '__return_true',
				),
			)
		);
	}

	/**
	 * Get loop templates.
	 *
	 * @since 1.4.0
	 *
	 * @param object $request
	 */
	public function get_loop_templates( $request ) {
		$nonce = $request->get_header( 'x_wp_nonce' );

		if ( ! $nonce || ! wp_verify_nonce( $nonce, 'wp_rest' ) ) {
			return;
		}

		$templates = Templates\Utils::get_loop_builder_templates();

		return $templates;
	}
}
