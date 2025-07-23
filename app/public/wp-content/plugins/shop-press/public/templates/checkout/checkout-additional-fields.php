<?php
/**
 * Additional fields.
 *
 * @package ShopPress
 */

defined( 'ABSPATH' ) || exit;

$checkout   = WC()->checkout();
$hide_title = $args['hide_title'] ?? false;

?>
<div class="sp-checkout-additional-fields">
	<div class="woocommerce-additional-fields">
		<?php do_action( 'woocommerce_before_order_notes', $checkout ); ?>

		<?php if ( apply_filters( 'woocommerce_enable_order_notes_field', 'yes' === get_option( 'woocommerce_enable_order_comments', 'yes' ) ) ) : ?>

			<?php if ( ! $hide_title ) : ?>
				<h2 class="checkout-step-title"><?php esc_html_e( 'Additional information', 'shop-press' ); ?></h2>
			<?php endif; ?>

			<div class="woocommerce-additional-fields__field-wrapper">
				<?php foreach ( $checkout->get_checkout_fields( 'order' ) as $key => $field ) : ?>
					<?php woocommerce_form_field( $key, $field, $checkout->get_value( $key ) ); ?>
				<?php endforeach; ?>
			</div>

		<?php endif; ?>

		<?php do_action( 'woocommerce_after_order_notes', $checkout ); ?>
	</div>
</div>
