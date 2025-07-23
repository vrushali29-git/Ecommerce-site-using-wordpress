<?php
/**
 * WPS Menu Exporter.
 *
 * @version 1.3.6
 * @package WPS Menu Exporter
 * @author https://wpserveur.net
 */

// don't load directly
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

// Plugin constants
define( 'WPS_MENU_EXPORTER_VERSION', '1.3.6' );
define( 'WPS_MENU_EXPORTER_FOLDER', 'wps-menu-exporter' );
define( 'WPS_MENU_EXPORTER_BASENAME', plugin_basename( __FILE__ ) );

define( 'WPS_MENU_EXPORTER_URL', plugin_dir_url( __FILE__ ) );
define( 'WPS_MENU_EXPORTER_DIR', plugin_dir_path( __FILE__ ) );

require_once WPS_MENU_EXPORTER_DIR . 'autoload.php';

if ( ! function_exists( 'plugins_loaded_wps_menu_exporter_plugin' ) ) {
	add_action( 'init', 'plugins_loaded_wps_menu_exporter_plugin' );
	function plugins_loaded_wps_menu_exporter_plugin() {
		\WPS\WPS_Menu_Exporter\Plugin::get_instance();

		load_plugin_textdomain( 'wps-menu-exporter', false, basename( rtrim( dirname( __FILE__ ), '/' ) ) . '/languages' );
	}
}