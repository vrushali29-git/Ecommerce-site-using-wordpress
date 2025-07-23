<?php
/**
 * Admin page.
 *
 * @package ShopPress
 */

namespace ShopPress\Admin;

defined( 'ABSPATH' ) || exit;

class Page {
	/**
	 * Hooks.
	 *
	 * @since 1.2.0
	 */
	public static function init() {
		add_action( 'admin_menu', array( __CLASS__, 'add_admin_menu' ) );
		add_action( 'admin_notices', array( __CLASS__, 'remove_header_actions' ), 1 );
	}

	/**
	 * Remove actions from the header.
	 *
	 * @since 1.1.1
	 */
	public static function remove_header_actions() {

		if ( isset( $_GET['page'] ) && $_GET['page'] === 'shoppress' ) {
			remove_all_actions( 'admin_notices' );
		}
	}

	/**
	 * Menu.
	 *
	 * @since  1.0.0
	 */
	public static function add_admin_menu() {
		add_menu_page(
			__( 'ShopPress', 'shop-press' ),
			__( 'ShopPress', 'shop-press' ),
			'manage_options',
			'shoppress',
			array( __CLASS__, 'admin_view' ),
			SHOPPRESS_URL . 'public/images/logo/logo.svg',
			2
		);
	}

	/**
	 * React div.
	 *
	 * @since  1.0.0
	 */
	public static function admin_view() {
		do_action( 'shoppress/admin/before_dashboard' );

		?>
		<div id="shoppress"></div>
		<?php

		do_action( 'shoppress/admin/after_dashboard' );
	}
}
