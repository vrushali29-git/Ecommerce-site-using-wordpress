<?php
/**
 * Installation related functions and actions.
 *
 * @package   WooCommerceKlaviyo/Classes
 * @version     0.9.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'WCK_Install' ) ) :

	/**
	 * WCK_Install Class
	 */
	class WCK_Install {

		/**
		 * Hook in tabs.
		 */
		public function __construct() {
			register_activation_hook( WCK_PLUGIN_FILE, array( $this, 'install' ) );

			add_action( 'admin_init', array( $this, 'admin_init' ), 5 );
		}

		/**
		 * Check plugin version and maybe redirect to Klaviyo settings page if recently activated.
		 */
		public function admin_init() {
			$this->check_version();
		}

		/**
		 * Check version of plugin against that saved in the DB to identify update.
		 *
		 * @return void
		 */
		public function check_version() {
			if ( ! defined( 'IFRAME_REQUEST' ) && ( get_option( 'woocommerce_klaviyo_version' ) !== WooCommerceKlaviyo::get_version() ) ) {
				$this->install();
				// Send options and version number to Klaviyo.
				$this->post_update_to_klaviyo();

				/**
				 * Fires when Klaviyo plugin is updated.
				 *
				 * @since 2.0.0
				 */
				do_action( 'woocommerce_klaviyo_updated' );
			}
		}

		/**
		 * Send options and plugin version information to Klaviyo during the plugin update. Remove
		 * the site transient so we start checking for a new version of Klaviyo again.
		 */
		protected function post_update_to_klaviyo() {
			// Send options to Klaviyo.
			WCK()->webhook_service->send_options_webhook( true );

			// Remove transient so we start checking 'set_site_transient_update_plugins' again.
			delete_site_transient( 'is_klaviyo_plugin_outdated' );
		}

		/**
		 * Install WCK
		 */
		public function install() {
			// Update version.
			update_option( 'woocommerce_klaviyo_version', WooCommerceKlaviyo::get_version() );

			// Flush rules after install.
			flush_rewrite_rules();
		}

		/**
		 * Called from WCK_Api via the `disable` route. Deactivate Klaviyo plugin via builtin function so hooks fire.
		 */
		public function deactivate_klaviyo() {
			deactivate_plugins( KLAVIYO_BASENAME );
		}

		/**
		 * Handle cleanup of the plugin.
		 * Delete options and remove WooCommerce webhooks.
		 */
		public function cleanup_klaviyo() {
			// We can't remove webhooks without WooCommerce. No need to remove the integration app-side.
			if ( is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
				// Remove WooCommerce webhooks.
				self::remove_klaviyo_webhooks();
			}

			// Lastly, delete Klaviyo-related options.
			delete_option( 'klaviyo_settings' );
			delete_option( 'woocommerce_klaviyo_version' );
			delete_site_transient( 'is_klaviyo_plugin_outdated' );
		}

		/**
		 * Remove Klaviyo related webhooks. The only way to identify these are through the delivery url so check for the
		 * Woocommerce webhook path.
		 */
		private static function remove_klaviyo_webhooks() {
			$webhook_data_store = WC_Data_Store::load( 'webhook' );
			$webhooks_by_status = $webhook_data_store->get_count_webhooks_by_status();
			// $webhooks_by_status returns an associative array with a count of webhooks in each status.
			$count = array_sum( $webhooks_by_status );

			if ( 0 === $count ) {
				return;
			}

			// We can only get IDs and there's not a way to search by delivery url which is the only way to identify
			// a webhook created by Klaviyo. We'll have to iterate no matter what so might as well get them all.
			$webhook_ids = $webhook_data_store->get_webhooks_ids();

			foreach ( $webhook_ids as $webhook_id ) {
				$webhook = wc_get_webhook( $webhook_id );
				if ( ! $webhook ) {
					continue;
				}

				if ( false !== strpos( $webhook->get_delivery_url(), '/api/webhook/integration/woocommerce' ) ) {
					$webhook_data_store->delete( $webhook );
				}
			}
		}
	}

endif;
