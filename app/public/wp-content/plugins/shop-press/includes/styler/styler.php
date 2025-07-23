<?php
/**
 * Plugin Name:       Styler Pro Redux
 * Plugin URI:        https://climaxthemes.com
 * Description:       Styler makes styling your pages easier.
 * Version:           3.2
 * Requires at least: 5.2
 * Requires PHP:      5.6
 * Author:            Climax Themes
 * Author URI:        https://climaxthemes.com
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       styler
 * Domain Path:       /languages
 */

defined( 'ABSPATH' ) || exit;

if ( function_exists( 'styler_init' ) ) return;

if ( ! defined( 'STYLER_VERSION' ) ) define( 'STYLER_VERSION', '3.2' );
if ( ! defined( 'STYLER_PATH' ) ) define( 'STYLER_PATH', plugin_dir_path( __FILE__ ) );
if ( ! defined( 'STYLER_URL' ) ) define( 'STYLER_URL', plugin_dir_url( __FILE__ ) );
if ( ! defined( 'STYLER_ASSETS_URL' ) ) define( 'STYLER_ASSETS_URL', STYLER_URL . 'public/' );
if ( ! defined( 'STYLER_ASSETS_PATH' ) ) define( 'STYLER_ASSETS_PATH', STYLER_PATH . 'public/' );

require_once STYLER_PATH . 'lib/plugin.php';

if ( ! function_exists( 'styler_init' ) ) {
	function styler_init() {
		Styler\Plugin::init();
	}
	styler_init();
}
