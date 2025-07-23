<?php
/**
 * WooCommerceKlaviyo Order Functions
 *
 * Functions for order specific things.
 *
 * @package   WooCommerceKlaviyo/Functions
 * @version   2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Builds composite products for cart.
 *
 * @param array $composite_products Composite products to add to cart.
 * @return void
 */
function add_composite_products_cart( $composite_products ) {
	foreach ( $composite_products as $product ) {
		$container = array();
		foreach ( $product as $i => $v ) {
			$item         = $v['item'];
			$container_id = $item['container_id'];
			if ( isset( $item['attributes'] ) ) {
				$container[ $container_id ] = array(
					'product_id'   => $item['product_id'],
					'quantity'     => $item['quantity'],
					'variation_id' => $item['variation_id'],
					'attributes'   => $item['attributes'],
				);
			} else {
				$container[ $container_id ] = array(
					'product_id' => $item['product_id'],
					'quantity'   => $item['quantity'],
				);
			}
		}
		$added = WC_CP()->cart->add_composite_to_cart( $v['composite_id'], $v['composite_quantity'], $container );
	}
}

/**
 * Gets email of current user or commenter.
 *
 * @param WP_User $current_user The current WordPress user.
 * @return mixed|string
 */
function get_email( $current_user ) {
	$email = '';
	if ( $current_user->user_email ) {
		$email = $current_user->user_email;
	} else {
		// See if current user is a commenter.
		$commenter = wp_get_current_commenter();
		if ( $commenter['comment_author_email'] ) {
			$email = $commenter['comment_author_email'];
		}
	}
	return $email;
}

/**
 * Rebuild cart from Abandoned Checkout query param.
 *
 * @return void
 */
function wck_rebuild_cart() {
	// Exit if in back-end.
	if ( is_admin() ) {
		return;
	}
	global $woocommerce;

	// Exit if not on cart page or no wck_rebuild_cart parameter.
	$current_url          = build_current_url();
	$utm_wck_rebuild_cart = isset( $_GET['wck_rebuild_cart'] ) ? sanitize_text_field( wp_unslash( $_GET['wck_rebuild_cart'] ) ) : '';
	if ( wc_get_cart_url() !== $current_url[0] || '' === $utm_wck_rebuild_cart ) {
		return;
	}

	// Rebuild cart.
	$woocommerce->cart->empty_cart( true );
	$woocommerce->cart->get_cart();

	$kl_cart = json_decode( base64_decode( $utm_wck_rebuild_cart ), true );

	/**
	* Allow developers to customise the payload before the cart is rebuilt.
	*
	* @since 3.0.12
	*
	* @param mixed $kl_cart The Klaviyo added to cart payload
	*/
	$kl_cart = apply_filters( 'kl_cart_rebuild', $kl_cart );

	$composite_products = $kl_cart['composite'];
	$normal_products    = $kl_cart['normal_products'];

	foreach ( $normal_products as $product ) {
		$cart_key = $woocommerce->cart->add_to_cart( $product['product_id'], $product['quantity'], $product['variation_id'], $product['variation'] );
	}

	if ( class_exists( 'WC_Composite_Products' ) ) {
		add_composite_products_cart( $composite_products );
	}

	/**
	* Allow developers to call a callback on cart rebuild.
	*
	* The `kl_cart_rebuild_complete` allows you to perform an action after Klaviyo has recovered and rebuilt the customers cart.
	*
	* @since 3.0.12
	*
	* @param mixed $kl_cart The Klaviyo added to cart payload
	*/
	do_action( 'kl_cart_rebuild_complete', $kl_cart );

	$carturl = wc_get_cart_url();
	if ( wc_get_cart_url() === $current_url[0] ) {
		header( 'Refresh:0; url=' . $carturl );
	}
}

/**
 * Construct current url.
 *
 * @return false|string[]
 */
function build_current_url() {
	$server_protocol = isset( $_SERVER['HTTPS'] ) ? 'https' : 'http';
	$server_host     = isset( $_SERVER['HTTP_HOST'] ) ? sanitize_text_field( wp_unslash( $_SERVER['HTTP_HOST'] ) ) : '';
	$server_uri      = isset( $_SERVER['REQUEST_URI'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '';

	return explode( '?', $server_protocol . '://' . $server_host . $server_uri );
}

/**
 * Insert tracking code for tracking started checkout.
 *
 * @return void
 */
function wck_started_checkout_tracking() {
	global $current_user;
	wp_reset_query();
	wp_get_current_user();
	$cart       = WC()->cart;
	$event_data = wck_build_cart_data( $cart );
	if ( empty( $event_data['$extra']['Items'] ) ) {
		return;
	}
	$event_data['$service'] = 'woocommerce';
	// Remove top level properties to maintain consistent Started Checkout event data in 2.5.0.
	unset( $event_data['Tags'] );
	unset( $event_data['Quantity'] );
	$email = get_email( $current_user );

	/** Adding apply_filter hook to modify the $event_data array which is passed to wck-started-checkout.js
	*
	* The `kl_started_checkout` filter allows you to add additional top level properties to the
	* [Started Checkout](https://help.klaviyo.com/hc/en-us/articles/360030732832#started-checkout1) event.
	*
	* The example below will add a "ReferralCode" property to the Started Checkout event
	*
	* add_filter('kl_started_checkout','kl_modify_started_checkout', 1, 2);
	*
	* function kl_modify_started_checkout($checkout_data, $cart){
	*        $referrer = htmlspecialchars($_COOKIE['referral_code']);
	*        if(isset($referrer)){
	*            $referrer = "Direct";
	*        }
	*        $checkout_data['ReferralCode'] = $referrer;
	*     return $checkout_data;
	* }
	*
	* @since 3.0.12
	*
	* @param array $event_data
	* @param WC_Cart $cart
	*/
	$event_data = apply_filters( 'kl_started_checkout', $event_data, $cart );

	$started_checkout_data = array(
		'email'      => $email,
		'event_data' => $event_data,
	);
	// Pass Started Checkout event data to javascript attaching to 'wck_started_checkout' handle.
	wp_localize_script( 'wck_started_checkout', 'kl_checkout', $started_checkout_data );
}

// Load javascript file for Started Checkout events.
add_action( 'wp_enqueue_scripts', 'load_started_checkout' );


/**
 *  Check if page is a checkout page, if so load the Started Checkout javascript file.
 */
function load_started_checkout() {
	require_once __DIR__ . '/class-wck-api.php';
	/**
	 * Override whether a page is a checkout page for purposes of whether to load the started checkout js.
	 *
	 * @since 3.0.8
	 */
	$should_add_started_checkout = apply_filters( 'wck_should_add_started_checkout', is_checkout() );
	if ( $should_add_started_checkout ) {
		$token = WCK()->options->get_klaviyo_option( 'klaviyo_public_api_key' );
		// Enqueue post identification sync module from klaviyo.js.
		wp_enqueue_script( 'wck_anon_backfill', '//static.klaviyo.com/onsite/js/' . $token . '/klaviyo.js?module=POST_IDENTIFICATION_SYNC', null, WCK_API::VERSION, array( 'in_footer' => true ) );
		// Load klaviyo object per https://developers.klaviyo.com/en/docs/introduction_to_the_klaviyo_object.
		wp_add_inline_script('wck_anon_backfill', '!function(){if(!window.klaviyo){window._klOnsite=window._klOnsite||[];try{window.klaviyo=new Proxy({},{get:function(n,i){return"push"===i?function(){var n;(n=window._klOnsite).push.apply(n,arguments)}:function(){for(var n=arguments.length,o=new Array(n),w=0;w<n;w++)o[w]=arguments[w];var t="function"==typeof o[o.length-1]?o.pop():void 0,e=new Promise((function(n){window._klOnsite.push([i].concat(o,[function(i){t&&t(i),n(i)}]))}));return e}}})}catch(n){window.klaviyo=window.klaviyo||[],window.klaviyo.push=function(){var n;(n=window._klOnsite).push.apply(n,arguments)}}}}();');
		// Enqueue started checkout javascript for legacy checkout page.
		wp_enqueue_script( 'wck_started_checkout', plugins_url( '/js/wck-started-checkout.js', __FILE__ ), null, WCK_API::VERSION, true );
		// Enqueue started checkout data.
		wp_localize_script( 'wck_started_checkout', 'public_key', array( 'token' => $token ) );
		wp_localize_script( 'wck_started_checkout', 'plugin_meta_data', array( 'data' => kl_get_plugin_usage_meta_data() ));
		// Build started checkout event data and add inline script to html.
		wck_started_checkout_tracking();
	}
}

add_action( 'wp_loaded', 'wck_rebuild_cart' );

/**
 * Add checkbox to subscribe profiles to email list during checkout.
 *
 * @param array[] $fields Checkout form fields.
 * @return array[] $fields
 */
function kl_checkbox_custom_checkout_field( $fields ) {
	$klaviyo_settings                            = get_option( 'klaviyo_settings' );
	$fields['billing']['kl_newsletter_checkbox'] = array(
		'type'     => 'checkbox',
		'class'    => array( 'kl_newsletter_checkbox_field' ),
		'label'    => $klaviyo_settings['klaviyo_newsletter_text'],
		'value'    => true,
		'default'  => 0,
		'required' => false,
	);

	return $fields;
}

/**
 * Add checkbox to subscribe profiles to SMS list during checkout.
 *
 * @param array[] $fields Checkout form fields.
 * @return array[] $fields
 */
function kl_sms_consent_checkout_field( $fields ) {
	$klaviyo_settings                             = get_option( 'klaviyo_settings' );
	$fields['billing']['kl_sms_consent_checkbox'] = array(
		'type'     => 'checkbox',
		'class'    => array( 'kl_sms_consent_checkbox_field' ),
		'label'    => $klaviyo_settings['klaviyo_sms_consent_text'],
		'value'    => true,
		'default'  => 0,
		'required' => false,
	);

	return $fields;
}

/**
 * Echo compliance text.
 *
 * @return void
 */
function kl_sms_compliance_text() {
	$klaviyo_settings = get_option( 'klaviyo_settings' );
	echo esc_html($klaviyo_settings['klaviyo_sms_consent_disclosure_text']);
}

/**
 * Send consent settings to Klaviyo.
 *
 * @return void
 */
function kl_add_to_list() {
	// This method is called from within WC_Checkout::process_checkout where nonce validation is done. Ignoring here.
    // phpcs:disable WordPress.Security.NonceVerification.Missing
	$klaviyo_settings = get_option( 'klaviyo_settings' );
	$email            = isset( $_POST['billing_email'] ) ? sanitize_text_field( wp_unslash( $_POST['billing_email'] ) ) : null;
	$phone            = isset( $_POST['billing_phone'] ) ? sanitize_text_field( wp_unslash( $_POST['billing_phone'] ) ) : null;
	$country          = isset( $_POST['billing_country'] ) ? sanitize_text_field( wp_unslash( $_POST['billing_country'] ) ) : null;
	$url              = 'https://a.klaviyo.com/api/webhook/integration/woocommerce?c=' . $klaviyo_settings['klaviyo_public_api_key'];
	$body             = array(
		'data' => array(),
	);

	if ( isset( $_POST['kl_sms_consent_checkbox'] ) && sanitize_text_field( wp_unslash( $_POST['kl_sms_consent_checkbox'] ) ) ) {
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
				'group_id'     => $klaviyo_settings['klaviyo_sms_list_id'],
			)
		);
	}

	if ( isset( $_POST['kl_newsletter_checkbox'] ) && sanitize_text_field( wp_unslash( $_POST['kl_newsletter_checkbox'] ) ) ) {
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
				'group_id'     => $klaviyo_settings['klaviyo_newsletter_list_id'],
			)
		);
	}
    // phpcs:enable WordPress.Security.NonceVerification.Missing

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
			'body'        => json_encode( $body ),
			'data_format' => 'body',
		)
	);
}

$klaviyo_settings = get_option( 'klaviyo_settings' );
if (
	isset( $klaviyo_settings['klaviyo_subscribe_checkbox'] )
	&& $klaviyo_settings['klaviyo_subscribe_checkbox']
	&& ! empty( $klaviyo_settings['klaviyo_newsletter_list_id'] )
) {
	// Add the checkbox field.
	add_filter( 'woocommerce_checkout_fields', 'kl_checkbox_custom_checkout_field', 11 );

	// Post list request to Klaviyo.
	add_action( 'woocommerce_checkout_update_order_meta', 'kl_add_to_list' );
}

if (
	isset( $klaviyo_settings['klaviyo_sms_subscribe_checkbox'] )
	&& $klaviyo_settings['klaviyo_sms_subscribe_checkbox']
	&& ! empty( $klaviyo_settings['klaviyo_sms_list_id'] )
) {
	// Add the checkbox field.
	add_filter( 'woocommerce_checkout_fields', 'kl_sms_consent_checkout_field', 11 );

	// Add data compliance messaging to checkout page.
	add_filter( 'woocommerce_after_checkout_billing_form', 'kl_sms_compliance_text' );

	// Post SMS request to Klaviyo.
	add_action( 'woocommerce_checkout_update_order_meta', 'kl_add_to_list' );
}
