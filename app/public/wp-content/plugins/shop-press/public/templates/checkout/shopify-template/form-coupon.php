<?php
/**
 * Checkout coupon form
 *
 * @package ShopPress
 */

defined( 'ABSPATH' ) || exit;

if (!wc_coupons_enabled()) { // @codingStandardsIgnoreLine.
	return;
}

?>
<form class="checkout_coupon woocommerce-form-coupon" method="post">
	<div class="box-input">
		<input type="text" name="coupon_code" class="sp-input-text"
				placeholder="<?php esc_attr_e( 'Coupon code', 'shop-press' ); ?>" id="coupon_code" value=""/>
	</div>
	<div class="box-button">
		<button type="submit" class="button" name="apply_coupon"
				value="<?php esc_attr_e( 'Apply', 'shop-press' ); ?>"><?php esc_html_e( 'Apply', 'shop-press' ); ?></button>
	</div>
</form>
