<?php
/**
 * Cart Empty Message.
 *
 * @package ShopPress
 */

defined( 'ABSPATH' ) || exit;

?>

<div class="sp-cart-empty-message wc-empty-cart-message">
	<?php echo '<p class="cart-empty woocommerce-info">' . wp_kses_post( apply_filters( 'wc_empty_cart_message', $args['message'] ) ) . '</p>'; ?>
</div>
