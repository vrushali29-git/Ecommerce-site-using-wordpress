<?php
/**
 * Options.
 *
 * @package ShopPress
 */

namespace ShopPress\Admin\API;

defined( 'ABSPATH' ) || exit;

class Options {
	/**
	 * Instance of this class.
	 *
	 * @since  1.0.0
	 */
	public static $instance;

	/**
	 * Provides access to a single instance of a module using the singleton pattern.
	 *
	 * @since   1.0.0
	 *
	 * @return  object
	 */
	public static function instance() {
		if ( self::$instance === null ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Update options.
	 *
	 * @since 1.0.0
	 */
	private function update_options( $options ) {

		$options = apply_filters( 'shoppress_update_sp_admin_settings', $options );

		$success = update_option( 'sp_admin', json_encode( $options ) );

		update_option( 'sp_need_rewrite_rules', 'yes' );

		return $success;
	}

	/**
	 * Get.
	 *
	 * @since 1.0.0
	 */
	public function get() {
		$sp_admin = json_decode( get_option( 'sp_admin' ), true );

		$sp_admin = apply_filters( 'shoppress_get_sp_admin_settings', $sp_admin );

		return new \WP_REST_Response(
			$sp_admin,
			200
		);
	}

	/**
	 * Update.
	 *
	 * @since 1.0.0
	 */
	public function update( $request ) {
		$options = $request->get_json_params();

		if ( ! empty( $options ) || is_array( $options ) ) {

			$updated = $this->update_options( $options );

			if ( ! is_wp_error( $updated ) ) {
				return new \WP_REST_Response( true, 200 );
			}
		}

		return false;
	}
}
