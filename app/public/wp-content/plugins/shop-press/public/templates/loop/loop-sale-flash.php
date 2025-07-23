<?php
/**
 * Loop Sale Flash.
 *
 * @package ShopPress
 */

defined( 'ABSPATH' ) || exit;

global $post, $product;

$icon = sp_render_icon( $args['cart_icon'] ?? '', array( 'aria-hidden' => 'true' ) );

if ( isset( $args['label'] ) ) {
	$label = $args['label'];
} else {
	$label = esc_html__( 'Sale!', 'shop-press' );
}

if ( isset( $args['cart_icon_pos'], $icon ) && 'before' == $args['cart_icon_pos'] ) {
	$before_icon = $icon;
} else {
	$before_icon = '';
}

if ( isset( $args['cart_icon_pos'], $icon ) && 'after' == $args['cart_icon_pos'] ) {
	$after_icon = $icon;
} else {
	$after_icon = '';
}

?>

<div class="sp-onsale">
	<?php if ( $product->is_on_sale() ) : ?>
		<?php
			echo apply_filters(
				'woocommerce_sale_flash',
				'<span class="onsale">' . wp_kses_post( $before_icon . $label . $after_icon ) . '</span>',
				$post,
				$product
			);
		?>
	<?php endif; ?>
</div>
