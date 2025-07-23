<?php
/**
 * Backorder.
 *
 * @package ShopPress
 */

namespace ShopPress\Modules;

defined( 'ABSPATH' ) || exit;

class Backorder {
	/**
	 * Init.
	 *
	 * @since 1.2.0
	 */
	public static function init() {

		if ( ! sp_get_module_settings( 'backorder', 'status' ) ) {
			return;
		}

		add_action( 'woocommerce_add_to_cart_validation', array( __CLASS__, 'set_order_limit_single' ), 10, 3 );
		add_action( 'woocommerce_update_cart_validation', array( __CLASS__, 'set_order_limit_cart' ), 10, 4 );
		add_filter( 'woocommerce_get_availability_text', array( __CLASS__, 'availability_message' ), 10, 2 );
		add_action( 'woocommerce_product_options_stock_status', array( __CLASS__, 'add_fields' ) );
		add_action( 'woocommerce_process_product_meta', array( __CLASS__, 'save_meta_box' ), 10, 1 );
		add_filter( 'sp_backorder_limit', array( __CLASS__, 'get_product_backorder_limit' ), 10, 3 );
		add_filter( 'sp_backorder_date', array( __CLASS__, 'get_product_backorder_date' ), 10, 2 );
		add_action( 'admin_enqueue_scripts', array( __CLASS__, 'enqueue_scripts' ) );
	}

	/**
	 * enqueue scripts.
	 *
	 * @since 1.1.0
	 */
	public static function enqueue_scripts() {
		wp_enqueue_script( 'sp-backorder-admin' );
		wp_enqueue_style( 'sp-backorder-admin' );
	}

	/**
	 * Set add to cart limit.
	 *
	 * @since 1.1.0
	 */
	public static function set_order_limit_single( $passed, $product_id, $quantity ) {

		if ( ! self::is_onbackorder( $product_id ) ) {
			return $passed;
		}

		$cart_items_count = ! empty( WC()->cart ) ? WC()->cart->get_cart_contents_count() : 0;
		$total_count      = $cart_items_count + $quantity;
		$limit            = apply_filters( 'sp_backorder_limit', sp_get_module_settings( 'backorder', 'backorder_limit' ), $product_id, false );

		if ( $cart_items_count >= $limit || $total_count > $limit ) {

			$passed = false;

			wc_add_notice( __( "The maximum backorder limit has reached. ( $limit available )", 'shop-press' ), 'error' );
		}

		return $passed;
	}

	/**
	 * Set update cart limit.
	 *
	 * @since 1.1.0
	 */
	public static function set_order_limit_cart( $passed, $cart_item_key, $values, $quantity ) {

		if ( ! self::is_onbackorder( $values['product_id'] ) ) {
			return $passed;
		}

		$cart_items_count  = ! empty( WC()->cart ) ? WC()->cart->get_cart_contents_count() : 0;
		$original_quantity = $values['quantity'];
		$total_count       = $cart_items_count - $original_quantity + $quantity;
		$limit             = apply_filters( 'sp_backorder_limit', sp_get_module_settings( 'backorder', 'backorder_limit' ), $values['product_id'], $cart_item_key );

		if ( $cart_items_count > $limit || $total_count > $limit ) {

			$passed = false;

			wc_add_notice( __( "The maximum backorder limit has reached. ( $limit available )", 'shop-press' ), 'error' );

		}

		return $passed;
	}

	/**
	 * Check if the product is on backorder.
	 *
	 * @since 1.1.0
	 *
	 * @param int $product_id
	 *
	 * @return bool
	 */
	public static function is_onbackorder( $product_id ) {
		$product = wc_get_product( $product_id );

		if ( method_exists( $product, 'get_stock_status' ) && $product->get_stock_status() === 'onbackorder' ) {
			return true;
		}

		return false;
	}

	/**
	 * Add filter to backorder limit for a specific product.
	 *
	 * @since 1.1.0
	 */
	public static function get_product_backorder_limit( $limit, $product_id, $cart_item_key ) {

		if ( $product_id ) {

			$meta  = get_post_meta( $product_id, 'sp_backorder_limit', true );
			$limit = ( isset( $meta ) && ! empty( $meta ) ) ? $meta : $limit;
		}

		if ( $cart_item_key ) {

			$cart_item = WC()->cart->get_cart_item( $cart_item_key );
			$meta      = get_post_meta( $cart_item['product_id'], 'sp_backorder_limit', true );
			$limit     = ( isset( $meta ) && ! empty( $meta ) ) ? $meta : $limit;
		}

		return $limit;
	}

	/**
	 * Add filter to backorder date for a specific product.
	 *
	 * @since 1.1.0
	 */
	public static function get_product_backorder_date( $date, $product_id ) {
		$meta = get_post_meta( $product_id, 'sp_backorder_date', true );

		if ( isset( $meta ) && ! empty( $meta ) ) {
			return $meta;
		}

		return $date;
	}

	/**
	 * Display availability message.
	 *
	 * @since 1.1.0
	 */
	public static function availability_message( $availability, $product ) {
		$date = apply_filters( 'sp_backorder_date', sp_get_module_settings( 'backorder', 'backorder_date' ), $product->get_id() );
		$date = date( 'F j Y', strtotime( $date ) );

		$message = str_replace( '{date}', $date, sp_get_module_settings( 'backorder', 'backorder_msg' ) );

		if ( $product->is_on_backorder() || $product->backorders_allowed() ) {
			return $message;
		}

		return $availability;
	}

	/**
	 * Add custom fields to product inventory.
	 *
	 * @since 1.1.0
	 */
	public static function add_fields() {
		$product_id      = get_the_id();
		$product         = wc_get_product( $product_id );
		$backorder_limit = get_post_meta( $product_id, 'sp_backorder_limit', true );
		$backorder_date  = get_post_meta( $product_id, 'sp_backorder_date', true );
		$manage_stock    = get_post_meta( $product_id, '_manage_stock', true );
		$stock_status    = get_post_meta( $product_id, '_stock_status', true );
		$limit           = apply_filters( 'sp_backorder_limit', sp_get_module_settings( 'backorder', 'backorder_limit' ), $product_id, false );

		$display_backorder = ( self::is_onbackorder( $product_id ) && $product->is_type( 'simple' ) ) || $manage_stock === 'yes';

		?>
		<div class="sp-backorder-fields <?php ( $display_backorder && 'onbackorder' === $stock_status ) ? esc_attr_e( 'sp-backorder-fields-show' ) : ''; ?>">
		<?php

		$field = array(
			'id'          => 'sp_backorder_limit',
			'value'       => $backorder_limit,
			'placeholder' => __( "Default Limit ( $limit )", 'shop-press' ),
			'label'       => __( 'Backorder Limit', 'shop-press' ),
		);

		woocommerce_wp_text_input( $field );

		?>
			<p class="form-field sp_backorder_date_field ">
				<label for="sp_backorder_date"><?php esc_html_e( 'Backorder Date', 'shop-press' ); ?></label>
				<input type="date" name="sp_backorder_date" id="sp_backorder_date" value="<?php esc_attr_e( $backorder_date ); ?>">
			</p>
		</div>
		<?php
	}

	/**
	 * Save custom fields.
	 *
	 * @since 1.1.0
	 */
	public static function save_meta_box( $post_id ) {

		if ( isset( $_REQUEST['sp_backorder_limit'] ) ) {
			update_post_meta( $post_id, 'sp_backorder_limit', sanitize_text_field( $_REQUEST['sp_backorder_limit'] ) );
		}

		if ( isset( $_REQUEST['sp_backorder_date'] ) ) {
			update_post_meta( $post_id, 'sp_backorder_date', sanitize_text_field( $_REQUEST['sp_backorder_date'] ) );
		}
	}
}
