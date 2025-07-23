<?php
/**
 * Product Sale Badge.
 *
 * @package ShopPress
 */

defined( 'ABSPATH' ) || exit;

global $product;

if ( ! $product->is_on_sale() ) {
	return;
}

$before_icon = '';
$after_icon  = '';

if ( isset( $args['label'] ) ) {
	$sale_label = $args['label'];
} else {
	$sale_label = esc_html__( 'Sale!', 'shop-press' );
}

$icon = sp_render_icon( $args['cart_icon'] ?? '', array( 'aria-hidden' => 'true' ) );

if ( isset( $args['cart_icon_pos'], $icon ) && 'before' == $args['cart_icon_pos'] ) {
	$before_icon = $icon;
}

if ( isset( $args['cart_icon_pos'], $icon ) && 'after' == $args['cart_icon_pos'] ) {
	$after_icon = $icon;
}
?>

<div class="sp-onsale">
	<?php echo wp_kses_post( '<span class="onsale">' . $before_icon . $sale_label . $after_icon . '</span>' ); ?>
</div>
