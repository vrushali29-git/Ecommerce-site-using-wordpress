<?php
/**
 * Export services.
 *
 * @package ShopPress
 */

namespace ShopPress\Admin\API;

defined( 'ABSPATH' ) || exit;

class Export {
	/**
	 * Instance of this class.
	 *
	 * @since  1.0.0
	 */
	public static $instance;

	/**
	 * File content.
	 *
	 * @since  1.0.0
	 */
	private $content;

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
	 * Set content.
	 *
	 * @since 1.0.0
	 *
	 * @param $content
	 */
	private function set_file_content( $content ) {
		$this->content = $content;
	}

	/**
	 * Export.
	 *
	 * @since 1.0.0
	 *
	 * @param object $request
	 */
	public function export( $request ) {
		$action = $request->get_param( 'action' );

		switch ( $action ) {
			case 'exportOptions': {
				$options = get_option( 'sp_admin' );

				$this->set_file_content( $options );
				break;
			}

			default: {
				break;
			}
		}

		return wp_send_json( $this->content );
	}
}
