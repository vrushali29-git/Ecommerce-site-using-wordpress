<?php
/**
 * Admin assets.
 *
 * @package ShopPress
 */

namespace ShopPress\Admin;

defined( 'ABSPATH' ) || exit;

class Assets {
	/**
	 * Init.
	 *
	 * @since 1.2.0
	 */
	public static function init() {
		add_action( 'admin_enqueue_scripts', array( __CLASS__, 'enqueue_scripts' ) );
	}

	/**
	 * enqueue scripts.
	 *
	 * @since 1.0.0
	 */
	public static function enqueue_scripts() {

		if ( isset( $_GET['page'] ) && $_GET['page'] === 'shoppress' ) {

			wp_enqueue_media();

			$script_path       = SHOPPRESS_PATH . 'build/index.js';
			$script_asset_path = SHOPPRESS_PATH . 'build/index.asset.php';
			$script_asset      = file_exists( $script_asset_path )
				? require $script_asset_path
				: array(
					'dependencies' => array( 'wp-element', 'wp-i18n' ),
					'version'      => filemtime( $script_path ),
				);
			$script_url        = SHOPPRESS_URL . 'build/index.js';

			wp_enqueue_script( 'sp-admin', $script_url, $script_asset['dependencies'], $script_asset['version'], true );

			$shoppress_license = get_option( 'shoppress_license' );

			wp_localize_script(
				'sp-admin',
				'shoppressOptions',
				array(
					'nonce'        => wp_create_nonce( 'wp_rest' ),
					'url'          => esc_url_raw( rest_url() ),
					'admin_url'    => esc_url_raw( get_admin_url() ),
					'is_pro'       => is_active_shoppress_pro(),
					'is_elementor' => did_action( 'elementor/loaded' ),
					'license_key'  => isset( $shoppress_license['license']['purchase_code'] ) && ! empty( $shoppress_license['license']['purchase_code'] ) ? $shoppress_license['license']['purchase_code'] : '',
					'version'      => SHOPPRESS_VERSION,
				)
			);

			wp_set_script_translations(
				'sp-admin',
				'shop-press',
				SHOPPRESS_PATH . 'languages'
			);

			wp_enqueue_style( 'sp-admin' );

			if ( is_rtl() ) {
				wp_enqueue_style( 'sp-admin-rtl' );
			}
		}
	}
}
