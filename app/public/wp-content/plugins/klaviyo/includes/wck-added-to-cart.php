<?php
/**
 * WooCommerceKlaviyo Webhook Service
 *
 * Handles outgoing requests to Klaviyo's webhook endpoint.
 *
 * @package     WooCommerceKlaviyo/Webhook
 * @since       2.4.2
 */

// The last woocommerce_add_to_cart hook has priority of 20 so we need to make sure we
// we fire after it. The higher the number, the later the function executes.
add_action( 'woocommerce_add_to_cart', 'kl_added_to_cart_event', 25, 3 );

/**
 * If the param is an instance of a WP_Error, returns
 * an empty array. If the param is not a WP_Error then
 * runs strip_tags and explode to return an array of strings.
 *
 * @param string $list String of product terms.
 * @return array
 */
function kl_strip_explode( $list ) {
	if ( $list instanceof WP_Error ) {
		return array();
	}
	return explode( ', ', strip_tags( $list ) );
}

/**
 * Creates a Request argument that can be used within
 * wp_remote_post method for added to cart event
 *
 * @param array $payload of the added to cart event.
 * @return array
 */

function kl_added_to_cart_options( $payload ) {
	return array(
		'blocking' => false,
		'headers'  => array(
			'Content-Type' => 'application/json',
			'X-Klaviyo-User-Agent' => kl_get_plugin_usage_meta_data(),
			'revision' => kl_get_klaviyo_api_revision(),
		),
		'body'     => wp_json_encode( $payload ),
	);
}

/**
 * Set wck_cart data then build the Added Item and return the array
 * of the full cart data.
 *
 * @param object $added_product Added product data.
 * @param int    $quantity Quantity of item added to cart.
 * @param object $cart Cart data.
 * @return array
 */
function kl_build_add_to_cart_data( $added_product, $quantity, $cart ) {
	$wck_cart         = wck_build_cart_data( $cart );
	$added_product_id = $added_product->get_id();

	$added_to_cart = array(
		'value'               => (float) $cart->total,
		'AddedItemCategories'  => (array) kl_strip_explode( wc_get_product_category_list( $added_product_id ) ),
		'AddedItemImageURL'    => (string) wp_get_attachment_url( get_post_thumbnail_id( $added_product_id ) ),
		'AddedItemPrice'       => (float) $added_product->get_price(),
		'AddedItemQuantity'    => (int) $quantity,
		'AddedItemProductID'   => (int) $added_product_id,
		'AddedItemProductName' => (string) $added_product->get_name(),
		'AddedItemSKU'         => (string) $added_product->get_sku(),
		'AddedItemTags'        => (array) kl_strip_explode( wc_get_product_tag_list( $added_product_id ) ),
		'AddedItemURL'         => (string) $added_product->get_permalink(),
		'ItemNames'            => (array) $wck_cart['ItemNames'],
		'Categories'           => isset( $wck_cart['Categories'] ) ? (array) $wck_cart['Categories'] : array(),
		'ItemCount'            => (int) $wck_cart['Quantity'],
		'Tags'                 => isset( $wck_cart['Tags'] ) ? (array) $wck_cart['Tags'] : array(),
		'extra'               => $wck_cart['$extra'],
	);

	/**
	 * Allow developers to customise the payload before it is sent to Klaviyo.
	 *
	 * The `kl_added_to_cart` filter allows you to add additional properties to the [Added to Cart]
	 * (https://help.klaviyo.com/hc/en-us/articles/360030732832#added-to-cart2) event.
	 *
	 * This example below shows you how to add custom fields to the event
	 *
	 * add_filter('kl_added_to_cart','kl_modify_added_to_cart', 1, 4);
	 *
	 * function kl_modify_added_to_cart($added_to_cart, $added_product, $quantity, $wck_cart) {
	 *        $product_lead_time =  get_field('leadtime',$added_product->get_id());
	 *        $product_designer = get_field('designer', $added_product->get_id());
	 *        $added_to_cart['LeadTime'] = $product_lead_time;
	 *        $added_to_cart['Designer'] = $product_designer;
	 *        return $added_to_cart;
	 * }
	 *
	 * @since 3.0.12
	 *
	 * @param array      $added_to_cart The Klaviyo added to cart payload
	 * @param WC_Product $added_product The product being added to the cart
	 * @param integer    $quantity The quantity of the item being added
	 * @param array      $wck_cart The entire Klaviyo cart object.
	 */
	$added_to_cart = apply_filters( 'kl_added_to_cart', $added_to_cart, $added_product, $quantity, $wck_cart );
	return $added_to_cart;
}

/**
 * Check that the Public API token is set, build Added to Cart event payload,
 * create an options request array using kl_added_to_cart_options function and
 * send the request.
 *
 * @param array $customer_identify Identifies the customer based on email or exchange_id.
 * @param array $data Cart and AddedItem data.
 * @returns null
 */
function kl_track_request( $customer_identify, $data ) {
	$public_api_key = WCK()->options->get_klaviyo_option( 'klaviyo_public_api_key' );
	if ( ! $public_api_key ) {
		return;
	}

	$metric_data = array(
		'data' => array(
			'type' => 'metric',
			'attributes' => array(
				'name' => 'Added to Cart',
			),
		),
	);

	$profile_data = array(
		'data' => array(
			'type' => 'profile',
			'attributes' => $customer_identify,
		),
	);

	// 'value' should be a key under 'attributes'
	$value = $data['value'];
	unset($data['value']);
	$attributes = array(
		'properties' => $data,
		'metric' => $metric_data,
		'profile' => $profile_data,
		'value' => $value,
	);

	$atc_data = array(
		'data' => array(
			'type' => 'event',
			'attributes' => $attributes,
		),
	);

	$url = 'https://a.klaviyo.com/client/events/?company_id=' . $public_api_key;

	$options = kl_added_to_cart_options( $atc_data );

	wp_remote_post( $url, $options );
}

/**
 * Set customer identity, call kl_build_add_to_cart_data and then call kl_track_request
 * to trigger the event.
 *
 * @param string $cart_item_key Unique key for item in cart.
 * @param int    $product_id ID of item added to cart.
 * @param int    $quantity Quantity of item added to cart.
 * @returns null
 */

function kl_added_to_cart_event( $cart_item_key, $product_id, $quantity ) {
	if ( ! isset( $_COOKIE['__kla_id'] ) ) {
		return;
	}
	$kl_cookie         = sanitize_text_field( wp_unslash( $_COOKIE['__kla_id'] ) );
	$kl_decoded_cookie = json_decode( base64_decode( $kl_cookie ), true );
	$has_exchange_id   = isset( $kl_decoded_cookie['$exchange_id'] );
	$has_email         = isset( $kl_decoded_cookie['email'] );
	$customer_identify = array();

	if ( $has_exchange_id ) {
		$customer_identify['_kx'] = $kl_decoded_cookie['$exchange_id'];
	}

	if ( $has_email ) {
		$customer_identify['email'] = $kl_decoded_cookie['email'];
	}

	if (!$has_exchange_id && !$has_email) {
		return;
	}

	$added_product = wc_get_product( $product_id );
	if ( ! $added_product instanceof WC_Product ) {
		return;
	}

	kl_track_request( $customer_identify, kl_build_add_to_cart_data( $added_product, $quantity, WC()->cart ) );
}
