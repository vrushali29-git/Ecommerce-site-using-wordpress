<?php
/**
 * Coupon Form.
 *
 * @package ShopPress
 */

defined( 'ABSPATH' ) || exit;

if ( ! wc_coupons_enabled() ) { // @codingStandardsIgnoreLine.
	return;
}

?>
<div class="sp-checkout-form-coupon-toggle">
	<div class="woocommerce-form-coupon-toggle">
		<?php wc_print_notice( apply_filters( 'woocommerce_checkout_coupon_message', esc_html__( 'Have a coupon?', 'shop-press' ) . ' <a href="#" class="sp-show-coupon">' . esc_html__( 'Click here to enter your code', 'shop-press' ) . '</a>' ), 'notice' ); ?>
	</div>
</div>

<div class="sp-checkout-form-coupon" style="display:none">
	<form class="checkout_coupon woocommerce-form-coupon" method="post" style="display:none">
		<p><?php esc_html_e( 'If you have a coupon code, please apply it below.', 'shop-press' ); ?></p>
			<input type="text" name="coupon_code" class="input-text" placeholder="<?php esc_attr_e( 'Coupon code', 'shop-press' ); ?>" id="coupon_code" value="" />
			<button type="submit" class="button apply-coupon" name="apply_coupon" value="<?php esc_attr_e( 'Apply', 'shop-press' ); ?>"><?php esc_html_e( 'Apply', 'shop-press' ); ?></button>
		<div class="clear"></div>
	</form>
</div>
