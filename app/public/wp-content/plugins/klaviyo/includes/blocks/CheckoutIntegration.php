<?php
/**
 * WooCommerceKlaviyo Blocks CheckoutIntegration
 *
 * StoreApi used to process consent at checkout checkboxes.
 *
 * @package     WooCommerceKlaviyo/Blocks
 * @since       3.2
 */

namespace WCK\Blocks;

/**
 * Class for integrating with WooCommerce Blocks
 */
class CheckoutIntegration implements \Automattic\WooCommerce\Blocks\Integrations\IntegrationInterface {

	/**
	 * Plugin settings and API keys.
	 *
	 * @var array
	 */
	protected $settings;

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->settings = get_option( 'klaviyo_settings' );
	}

	/**
	 * The name of the integration.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'klaviyo_checkout_block';
	}

	/**
	 * Block scripts and settings are already loaded
	 */
	public function initialize() {
		$script_path = '/blocks/build/events.js';

		$script_url        = plugins_url( $script_path, __DIR__ );
		$script_asset_path = dirname( __DIR__ ) . '/blocks/build/events.asset.php';
		$script_asset      = file_exists( $script_asset_path )
			? require $script_asset_path
			: array(
				'dependencies' => array(),
				'version'      => $this->get_file_version( $script_path ),
			);

		wp_register_script(
			'klaviyo-checkout-block-event-script',
			$script_url,
			array_merge( $script_asset['dependencies'], array( 'wck_started_checkout' ) ),
			$script_asset['version'],
			true
		);
	}

	/**
	 * Returns an array of script handles to enqueue in the frontend context.
	 *
	 * Note: first element in array matches namespace/block-name from block
	 * name in block.json e.g. klaviyo/klaviyo-checkout-block matches to
	 * klaviyo-klaviyo-checkout-block. Mismatch gets caught and very hard to
	 * identify.
	 *
	 * @return string[]
	 */
	public function get_script_handles() {
		return array( 'klaviyo-klaviyo-checkout-block-view-script', 'klaviyo-checkout-block-event-script' );
	}

	/**
	 * Returns an array of script handles to enqueue in the editor context.
	 *
	 * Note: first element in array matches namespace/block-name from block
	 * name in block.json e.g. klaviyo/klaviyo-checkout-block matches to
	 * klaviyo-klaviyo-checkout-block. Mismatch gets caught and very hard to
	 * identify.
	 *
	 * @return string[]
	 */
	public function get_editor_script_handles() {
		return array( 'klaviyo-klaviyo-checkout-block-editor-script' );
	}

	/**
	 * An array of key, value pairs of data made available to the block on the client side.
	 *
	 * @return array
	 */
	public function get_script_data() {
		return array(
			'newsletterText'           => $this->get_newsletter_text(),
			'smsConsentText'           => $this->get_sms_consent_text(),
			'smsConsentDisclosureText' => $this->get_sms_consent_disclosure_text(),
			'smsEnabled'               => $this->get_sms_enabled(),
			'newsletterEnabled'        => $this->get_newsletter_enabled(),
		);
	}

	/**
	 * Gets newsletter consent text from settings.
	 *
	 * @return mixed
	 */
	public function get_newsletter_text() {
		return $this->settings['klaviyo_newsletter_text'] ?? __( 'Sign me up to receive email updates and news (optional)', 'klaviyo-checkout-block' );
	}

	/**
	 * Get sms consent text from settings.
	 *
	 * @return mixed
	 */
	public function get_sms_consent_text() {
		return $this->settings['klaviyo_sms_consent_text'] ?? __( 'Sign me up to receive SMS updates and news (optional)', 'klaviyo-checkout-block' );
	}

	/**
	 * Get sms consent disclosure text from settings.
	 *
	 * @return mixed
	 */
	public function get_sms_consent_disclosure_text() {
		return $this->settings['klaviyo_sms_consent_disclosure_text'] ?? __( 'By checking this box and entering your phone number above, you consent to receive marketing text messages (such as [promotion codes] and [cart reminders]) from [company name] at the number provided, including messages sent by autodialer. Consent is not a condition of any purchase. Message and data rates may apply. Message frequency varies. You can unsubscribe at any time by replying STOP or clicking the unsubscribe link (where available) in one of our messages. View our Privacy Policy [link] and Terms of Service [link]', 'klaviyo-checkout-block' );
	}

	/**
	 * Whether sms consent collection is enabled in the integration settings.
	 *
	 * @return false|mixed
	 */
	public function get_sms_enabled() {
		return $this->settings['klaviyo_sms_subscribe_checkbox'] ?? false;
	}

	/**
	 * Whether email consent collection is enabled in the integration settings.
	 *
	 * @return false|mixed
	 */
	public function get_newsletter_enabled() {
		return $this->settings['klaviyo_subscribe_checkbox'] ?? false;
	}

	/**
	 * Get the file modified time as a cache buster if we're in dev mode.
	 *
	 * @param string $file Local path to the file.
	 * @return string The cache buster value to use for the given file.
	 */
	protected function get_file_version( $file ) {
		if ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG && file_exists( $file ) ) {
			return filemtime( $file );
		}

		return \WooCommerceKlaviyo::get_version();
	}
}
