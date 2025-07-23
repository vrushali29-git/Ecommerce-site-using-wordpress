<?php
/**
 * Wishlist Button.
 *
 * @package ShopPress
 */

defined( 'ABSPATH' ) || exit;

use ShopPress\Modules\Wishlist\Main;

$product_id   = get_the_ID();
$Wishlist     = Main::get_wishlist_products();
$wishlist_key = $Wishlist[ $product_id ]['wishlist_key'] ?? false;
$Wishlist     = $wishlist_key ? 'yes' : 'no';
if ( 'yes' === $Wishlist ) {
	$label = sp_get_module_settings( 'wishlist_general_settings', 'remove_label' );
} else {
	$label = sp_get_module_settings( 'wishlist_general_settings', 'add_label' );
}

$custom_icon = sp_render_icon(
	$args['icon'] ?? '',
);

?>

<div class="sp-wishlist sp-wishlist-button-container" data-product_id="<?php echo esc_attr( $product_id ); ?>" data-status="<?php echo esc_attr( $Wishlist ); ?>" data-wishlist_key="<?php echo esc_attr( $wishlist_key ); ?>" data-wishlist_key="<?php echo esc_attr( $wishlist_key ); ?>">
	<div class="sp-wishlist-button">
		<?php if ( 'label' === $args['type'] ) : ?>
			<?php if ( ! empty( $label ) ) { ?>
				<span class="sp-wishlist-label"><?php echo wp_kses( $label, wp_kses_allowed_html( 'post' ) ); ?></span>
			<?php } ?>
		<?php endif; ?>

		<?php if ( 'icon' === $args['type'] ) : ?>
			<span class="sp-wishlist-icon">
				<?php echo ! empty( $args['icon']['value'] ) ? wp_kses( $custom_icon, sp_allowd_svg_tags() ) : '<i class="sp-icon">' . wp_kses( sp_get_svg_icon( 'wishlist' ), sp_allowd_svg_tags() ) . '</i>'; ?>
			</span>
		<?php endif; ?>

		<?php if ( 'icon-label' === $args['type'] ) : ?>
			<span class="sp-wishlist-icon">
				<?php echo ! empty( $args['icon']['value'] ) ? wp_kses( $custom_icon, sp_allowd_svg_tags() ) : '<i class="sp-icon">' . wp_kses( sp_get_svg_icon( 'wishlist' ), sp_allowd_svg_tags() ) . '</i>'; ?>
			</span>
			<?php if ( ! empty( $label ) ) { ?>
				<span class="sp-wishlist-label"><?php echo wp_kses( $label, wp_kses_allowed_html( 'post' ) ); ?></span>
			<?php } ?>
		<?php endif; ?>
	</div>
</div>
