<?php
/**
 * Plugin Name:       ShopPress
 * Plugin URI:        https://climaxthemes.com/shoppress
 * Description:       ShopPress plugin makes customizing all your store pages easier than ever with seamless WooCommerce and Elementor integration. It has a minimalist design and uses the latest Ajax technology to provide a unique user experience.
 * Version:           1.4.15
 * Requires at least: 5.2
 * Requires PHP:      5.6
 * Author:            Climax Themes
 * Author URI:        https://climaxthemes.com
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       shop-press
 * Domain Path:       /languages
 * Elementor tested up to: 3.28.4
 */

defined( 'ABSPATH' ) || exit;

define( 'SHOPPRESS_VERSION', '1.4.15' );
define( 'SHOPPRESS_PATH', plugin_dir_path( __FILE__ ) );
define( 'SHOPPRESS_URL', plugin_dir_url( __FILE__ ) );

if ( ! file_exists( SHOPPRESS_PATH . 'vendor/autoload.php' ) ) {
	return;
}

require SHOPPRESS_PATH . 'vendor/autoload.php';

/**
 * Initializes the Plugin class.
 *
 * @since 1.0.0
 */
if ( ! function_exists( 'shoppress' ) ) {
	function shoppress() {
		ShopPress\Plugin::instance();
	}
}

// Initialize the plugin.
shoppress();

// Add default option.
register_activation_hook( __FILE__, array( 'ShopPress\Plugin', 'add_sp_option' ) );

// Create woocommerce default templates.
register_activation_hook( __FILE__, array( 'ShopPress\Templates\Main', 'create_templates' ) );
