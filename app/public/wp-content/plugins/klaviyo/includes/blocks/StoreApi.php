<?php
/**
 * WooCommerceKlaviyo Blocks StoreApi
 *
 * StoreApi used to process consent at checkout checkboxes.
 *
 * @package     WooCommerceKlaviyo/Blocks
 * @since       3.2
 */

namespace WCK\Blocks;

/**
 * StoreApi
 */
class StoreApi {

	/**
	 * Plugin settings and API keys.
	 *
	 * @var array
	 */
	protected $settings;
	/**
	 * Klaviyo production API.
	 *
	 * @var string
	 */
	protected $api_url = 'https://a.klaviyo.com/api/';

	/**
	 * Get options and hook into actions.
	 */
	public function __construct() {
		$this->settings = get_option( 'klaviyo_settings' );
		if ( ! $this->settings ) {
			return;
		}

		/**
		 * Compatibility issue separate from consent at checkout, see callback method docstring for more info. We are
		 * using this hook based on its occurring specifically from the Checkout Block and corresponding to the older
		 * woocommerce_checkout_order_processed hook and indicates and order is processed and ready for payment.
		 */
		add_action( 'woocommerce_store_api_checkout_order_processed', array( $this, 'update_order_created_at' ), 10, 1 );

		// Only run the logic if we have one of the newsletter checkboxes enabled.
		if (
			( isset($this->settings['klaviyo_sms_subscribe_checkbox']) && $this->settings['klaviyo_sms_subscribe_checkbox'] )
			|| ( isset($this->settings['klaviyo_subscribe_checkbox']) && $this->settings['klaviyo_subscribe_checkbox'] )
		) {
			// React to consent opt-in from Store API (Checkout block) and schedule an API call to Klaviyo.
			add_action( 'woocommerce_store_api_checkout_update_order_from_request', array( $this, 'optin_customer_from_store_api' ), 10, 2 );
			// Triggers an API call to Klaviyo.
			add_action( 'klaviyo_schedule_consent_event', array( $this, 'send_consent_event' ), 10, 5 );
			// Register Store API schema and validation.
			$this->register_store_api_routes();
		}
	}

	/**
	 * We need to reset the order's created_at property when the order is placed to counteract Block Checkout creating
	 * the shop_order record at the start of checkout as a draft. Legacy checkouts created a shop_order record when a
	 * customer clicks "Place Order". The earlier created_at date causes Placed Order and Ordered Product events in
	 * Klaviyo to eventually be recorded before Started Checkout events in a profile's activity feed. This can impact
	 * Abandoned Cart flows negatively.
	 *
	 * @param \WC_Order $order Order object.
	 * @return void
	 */
	public function update_order_created_at( $order ) {
		$unix_now = time();
		$order->set_date_created( $unix_now );
		// to ensure modified isn't before.
		$order->set_date_modified( $unix_now );
		$order->save();
	}

	/**
	 * Register Store API schema and validation.
	 * We're not returning anything to the Checkout request, that's why we only have schema_callback. We need to react to POST requests.
	 *
	 * @return void
	 * @since 0.1.0
	 * @see https://github.com/woocommerce/woocommerce-blocks/blob/trunk/docs/third-party-developers/extensibility/rest-api/extend-rest-api-add-data.md
	 */
	public function register_store_api_routes() {
		$args = array(
			'endpoint'        => \Automattic\WooCommerce\StoreApi\Schemas\V1\CheckoutSchema::IDENTIFIER,
			'namespace'       => 'klaviyo',
			'schema_callback' => function () {
				return array(
					'newsletter' => array(
						'description' => __( 'Subscribe to email newsletter.', 'klaviyo-checkout-block' ),
						'type'        => array( 'boolean', 'null' ),
						'context'     => array(),
						'arg_options' => $this->optional_boolean_arg_options(),
					),
					'sms'        => array(
						'description' => __( 'Subscribe to sms marketing newsletter.', 'klaviyo-checkout-block' ),
						'type'        => array( 'boolean', 'null' ),
						'context'     => array(),
						'arg_options' => $this->optional_boolean_arg_options(),
					),
				);
			},
		);

		woocommerce_store_api_register_endpoint_data( $args );
	}

	/**
	 * React to consent opt-in from Store API (Checkout block) and schedule an API call to Klaviyo.
	 *
	 * Request payload structure:
	 * https://github.com/woocommerce/woocommerce-blocks/blob/trunk/src/StoreApi/docs/checkout.md#process-order-and-payment
	 *
	 * @param \WC_Order        $order Order object.
	 * @param \WP_REST_Request $request Request object.
	 * @return void
	 * @since 0.1.0
	 */
	public function optin_customer_from_store_api( $order, $request ) {
		$body            = $request->get_json_params();
		$billing_address = $body['billing_address'];
		$request_email   = $billing_address['email'] ?? null;
		$request_phone   = $billing_address['phone'] ?? null;
		$request_country = $billing_address['country'] ?? null;
		$email           = $order->get_billing_email() ?? $request_email;
		$phone           = $order->get_billing_phone() ?? $request_phone;
		$country         = $order->get_billing_country() ?? $request_country;

		// Structure of extension data defined in schema_callback above.
		$consent_to_sms        = $request['extensions']['klaviyo']['sms'];
		$consent_to_newsletter = $request['extensions']['klaviyo']['newsletter'];

		if ( $consent_to_sms || $consent_to_newsletter ) {
			as_enqueue_async_action( 'klaviyo_schedule_consent_event', array( $email, $phone, $country, $consent_to_sms, $consent_to_newsletter ), 'klaviyo' );
		}
	}

	/**
	 * Triggers an API call to Klaviyo.
	 *
	 * @param string  $email Customer email.
	 * @param string  $phone Customer billing phone.
	 * @param string  $country Customer billing country.
	 * @param boolean $consent_to_sms If the customer consented to sms.
	 * @param boolean $consent_to_newsletter If the customer consented to newsletter.
	 * @return void
	 * @since 0.1.0
	 */
	public function send_consent_event( $email, $phone, $country, $consent_to_sms, $consent_to_newsletter ) {
		$url  = $this->api_url . 'webhook/integration/woocommerce?c=' . $this->settings['klaviyo_public_api_key'];
		$body = array(
			'data' => array(),
		);

		if ( $consent_to_sms ) {
			array_push(
				$body['data'],
				array(
					'customer'     => array(
						'email'   => $email,
						'country' => $country,
						'phone'   => $phone,
					),
					'consent'      => true,
					'updated_at'   => gmdate( DATE_ATOM, date_timestamp_get( date_create() ) ),
					'consent_type' => 'sms',
					'group_id'     => $this->settings['klaviyo_sms_list_id'],
				)
			);
		}

		if ( $consent_to_newsletter ) {
			array_push(
				$body['data'],
				array(
					'customer'     => array(
						'email' => $email,
						'phone' => $phone,
					),
					'consent'      => true,
					'updated_at'   => gmdate( DATE_ATOM, date_timestamp_get( date_create() ) ),
					'consent_type' => 'email',
					'group_id'     => $this->settings['klaviyo_newsletter_list_id'],
				)
			);
		}

		wp_remote_post(
			$url,
			array(
				'method'      => 'POST',
				'httpversion' => '1.0',
				'blocking'    => false,
				'headers'     => array(
					'X-WC-Webhook-Topic' => 'custom/consent',
					'Content-Type'       => 'application/json',
				),
				'body'        => wp_json_encode( $body ),
				'data_format' => 'body',
			)
		);
	}

	/**
	 * Validate if the value is a boolean or null. Make sure we accept null as false.
	 *
	 * @return array Array of options for the argument. See https://developer.wordpress.org/reference/functions/register_rest_route/#arguments
	 * @since 0.1.0
	 */
	protected function optional_boolean_arg_options() {
		return array(
			'validate_callback' => function ( $value ) {
				if ( ! is_null( $value ) && ! is_bool( $value ) ) {
					return new \WP_Error( 'api-error', 'value of type ' . gettype( $value ) . ' was posted to the klaviyo opt-in callback' );
				}
				return true;
			},
			'sanitize_callback' => function ( $value ) {
				if ( is_bool( $value ) ) {
					return $value;
				}
				return false;
			},
		);
	}
}
