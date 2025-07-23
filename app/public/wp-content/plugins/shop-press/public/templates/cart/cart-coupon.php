<?php
/**
 * Cart Coupon.
 *
 * @package ShopPress
 */

defined( 'ABSPATH' ) || exit;

if ( ! wc_coupons_enabled() ) { // @codingStandardsIgnoreLine.
	return;
}

$button_style = isset( $args['button_style'] ) ? $args['button_style'] : 'icon';
$btn_icon     = sp_render_icon( $args['btn_icon'] ?? '' );
$btn_text     = isset( $args['button_text'] ) ? $args['button_text'] : esc_attr__( 'Apply', 'shop-press' );

switch ( $button_style ) {
	case 'text':
		$btn_html = $btn_text;
		break;
	case 'icon_button':
		$btn_html = $btn_icon . $btn_text;
		break;
	case 'button_icon':
		$btn_html = $btn_text . $btn_icon;
		break;
	case 'icon':
	default:
		$btn_html = $btn_icon;
		break;
}

?>

<div class="sp-cart-coupon coupon">
	<form class="cart_coupon woocommerce-form-coupon" method="post">
		<input type="text" name="coupon_code" class="input-text" id="coupon_code" value="" placeholder="<?php esc_attr_e( 'Coupon code', 'shop-press' ); ?>" />
		<button type="submit" class="button apply-coupon" name="apply_coupon" value="<?php esc_attr_e( 'Apply coupon', 'shop-press' ); ?>"><?php echo wp_kses_post( $btn_html ); ?></button>
		<?php do_action( 'woocommerce_cart_coupon' ); ?>
	</form>
</div>
