<?php
/**
 * Suggest Price.
 *
 * @package ShopPress
 */

defined( 'ABSPATH' ) || exit;

global $product;

if ( isset( $_REQUEST[ 'sp_suggest_price_' . $args['id'] ] ) ) {

	$name    = sanitize_text_field( $_POST['sp_price_name'] );
	$email   = sanitize_text_field( $_POST['sp_price_email'] );
	$price   = sanitize_text_field( $_POST['sp_price'] );
	$message = sanitize_text_field( $_POST['sp_price_message'] );

	$to      = esc_attr( $args['email_address'] );
	$subject = 'Suggest price for ' . esc_attr( $product->get_title() ) . ' ' . __( 'Price:', 'shop-press' ) . esc_attr( $price );
	$body    = esc_attr( $message );
	$headers = array( 'Content-Type: text/html; charset=UTF-8', 'From: ' . esc_attr( $name ) . ' <' . esc_attr( $email ) . '>' );

	wp_mail( $to, $subject, $body, $headers );
}

?>

<div class="sp-suggest-price">
	<a href="#" class="button"><?php esc_html_e( $args['btn_text'] ); ?></a>
</div>

<div class="sp-suggest-price-form" data-post_id="<?php echo esc_attr( $args['id'] ); ?>" data-el_id="<?php echo esc_attr( $args['id'] ); ?>">
	<form action="<?php echo esc_url( $_SERVER['REQUEST_URI'] ); ?>" method="post">
		<a href="#" class="sp-suggest-close"></a>
		<input type="text" name="sp_price_name" placeholder="<?php esc_attr_e( $args['name_placeholder'] ); ?>">
		<input type="text" name="sp_price_email" placeholder="<?php esc_attr_e( $args['email_placeholder'] ); ?>">
		<input type="text" name="sp_price" placeholder="<?php esc_attr_e( $args['price_placeholder'] ); ?>">
		<textarea name="sp_price_message" cols="30" rows="5" placeholder="<?php esc_attr_e( $args['message_placeholder'] ); ?>"></textarea>
		<input type="submit" name="sp_suggest_price_<?php esc_attr_e( $args['id'] ); ?>" value="<?php esc_html_e( $args['submit_btn_text'] ); ?>">
	</form>
</div>
