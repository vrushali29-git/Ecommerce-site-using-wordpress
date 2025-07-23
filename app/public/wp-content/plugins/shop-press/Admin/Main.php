<?php
/**
 * Admin.
 *
 * @package ShopPress
 */

namespace ShopPress\Admin;

defined( 'ABSPATH' ) || exit;

class Main {
	/**
	 * Init.
	 *
	 * @since  1.0.0
	 */
	public static function init() {
		static::init_admin();
	}

	/**
	 * Load the admin dependencies.
	 *
	 * @since 1.0.0
	 */
	private static function init_admin() {
		API\Services::instance();
		PostType::init();
		Assets::init();
		// Announcement::init();
	}
}
