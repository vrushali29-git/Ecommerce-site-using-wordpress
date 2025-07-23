<?php
/**
 * Order Tracking.
 *
 * @package ShopPress
 */

defined( 'ABSPATH' ) || exit;

global $post;

$description       = $args['description'] ? $args['description'] : '';
$orderid_title     = $args['orderid_title'] ? $args['orderid_title'] : '';
$order_placeholder = $args['order_placeholder'] ? $args['order_placeholder'] : '';
$email_title       = $args['email_title'] ? $args['email_title'] : '';
$email_placeholder = $args['email_placeholder'] ? $args['email_placeholder'] : '';
$button_text       = $args['button_text'] ? $args['button_text'] : '';

?>
<div class="sp-orders-tracking">
	<form action="<?php echo esc_url( get_permalink( $post->ID ) ); ?>" method="post" class="woocommerce-form woocommerce-form-track-order track_order">
		<?php if ( $description ) : ?>
			<p class="order-tracking-description"><?php esc_html_e( $description, 'shop-press' ); ?></p>
		<?php endif; ?>

		<p class="form-row form-row-first">
			<label for="orderid"><?php esc_html_e( $orderid_title, 'shop-press' ); ?></label>
			<input class="input-text" type="text" name="orderid" id="orderid" value="<?php echo isset( $_REQUEST['orderid'] ) ? esc_attr( wp_unslash( $_REQUEST['orderid'] ) ) : ''; ?>" placeholder="<?php esc_attr_e( $order_placeholder, 'shop-press' ); ?>" />
        </p><?php // @codingStandardsIgnoreLine ?>

		<p class="form-row form-row-last">
			<label for="order_email"><?php esc_html_e( $email_title, 'shop-press' ); ?></label>
			<input class="input-text" type="text" name="order_email" id="order_email" value="<?php echo isset( $_REQUEST['order_email'] ) ? esc_attr( wp_unslash( $_REQUEST['order_email'] ) ) : ''; ?>" placeholder="<?php esc_attr_e( $email_placeholder, 'shop-press' ); ?>" />
        </p><?php // @codingStandardsIgnoreLine ?>
		<div class="clear"></div>

		<p class="form-row">
			<button type="submit" class="track-button button" name="track" value="<?php esc_attr_e( $button_text, 'shop-press' ); ?>"><?php esc_html_e( $button_text, 'shop-press' ); ?></button>
		</p>
		<?php wp_nonce_field( 'woocommerce-order_tracking', 'woocommerce-order-tracking-nonce' ); ?>
	</form>
</div>
