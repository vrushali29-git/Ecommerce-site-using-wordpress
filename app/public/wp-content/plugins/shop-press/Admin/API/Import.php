<?php
/**
 * Import services.
 *
 * @package ShopPress
 */

namespace ShopPress\Admin\API;

defined( 'ABSPATH' ) || exit;

class Import {
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
	 * Import.
	 *
	 * @since 1.0.0
	 *
	 * @param object $request
	 */
	public function import( $request ) {
		$action = $request->get_param( 'action' );

		switch ( $action ) {
			case 'importOptions': {
				$content = $request->get_param( 'fileContent' );

				$options = json_decode( $content, true );

				update_option( 'sp_admin', $options );

				break;
			}

			default: {

				break;
			}
		}
	}
}
