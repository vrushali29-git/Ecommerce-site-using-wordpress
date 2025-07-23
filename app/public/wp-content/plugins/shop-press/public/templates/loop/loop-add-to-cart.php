<?php
/**
 * Loop Add To Cart.
 *
 * @package ShopPress
 */

defined( 'ABSPATH' ) || exit;

global $product;

$icon_position = '';
$product_id    = get_the_ID();

if ( isset( $args['icon_position'] ) ) {
	$icon_position = $args['icon_position'];
}

$icon = sp_render_icon(
	$args['icon'] ?? '',
	array(
		'aria-hidden' => 'true',
		'class'       => 'sp-addtocart-icon',
	)
);

$overlay = $args['overlay'];

if ( $overlay === 'yes' ) {
	?>
	<div class="sp-product-add-to-cart sp-addtocart overlay" data-product_id="<?php echo esc_attr( $product_id ); ?>">
		<?php
		if ( isset( $args['icon'] ) ) {
			echo '<i aria-hidden="true" class="sp-addtocart-icon">' . wp_kses( $icon, sp_allowd_svg_tags() ) . '</i>';
		}
		?>
	</div>
	<?php
}

?>
<div class="sp-product-add-to-cart" style="<?php echo $overlay === 'yes' ? 'display:none;' : ''; ?>">
	<?php sp_add_to_cart_link( $product, $args['icon'] ?? '', $icon_position ); ?>
</div>
