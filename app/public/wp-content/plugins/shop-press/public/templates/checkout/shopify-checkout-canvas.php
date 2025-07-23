<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$order_pay      = get_query_var( 'order-pay' );
$order_received = get_query_var( 'order-received' );
if ( $order_pay || $order_received ) {
	get_header();
} else {
	?>
	<!DOCTYPE html>
	<html <?php language_attributes(); ?>>
	<head>
		<meta charset="<?php bloginfo( 'charset' ); ?>">
		<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
		<?php if ( ! current_theme_supports( 'title-tag' ) ) : ?>
			<title><?php echo wp_get_document_title(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></title>
		<?php endif; ?>
		<?php wp_head(); ?>
	</head>
	<body <?php body_class( 'sp-shopify-checkout-body' ); ?>>
	<?php
	if ( function_exists( 'wp_body_open' ) ) {
		wp_body_open();
	}
}
echo do_shortcode( '[woocommerce_checkout]' );

if ( $order_pay || $order_received ) {
	get_footer();
} else {
	wp_footer();
	?>
	</body>
	</html>
	<?php
}
