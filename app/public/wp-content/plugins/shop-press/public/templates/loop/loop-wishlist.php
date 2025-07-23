<?php
/**
 * Loop Wishlist Button.
 *
 * @package ShopPress
 */

defined( 'ABSPATH' ) || exit;


use ShopPress\Modules\Wishlist\Main;

$product_id   = get_the_ID();
$Wishlist     = Main::get_wishlist_products();
$wishlist_key = $Wishlist[ $product_id ]['wishlist_key'] ?? false;
$Wishlist     = $wishlist_key ? 'yes' : 'no';
$show_label   = $args['show_label'] ?? '';
$label_remove = sp_get_module_settings( 'wishlist_general_settings', 'remove_label' );
$label        = sp_get_module_settings( 'wishlist_general_settings', 'add_label' );
$icon         = sp_render_icon(
	$args['icon'] ?? '',
	array(
		'aria-hidden' => 'true',
	)
);

$overlay = $args['overlay'] ?? '';
?>
<div class="sp-wishlist-loop sp-wishlist-button-container <?php echo $overlay === 'yes' ? 'overlay' : ''; ?>" data-product_id="<?php echo esc_attr( $product_id ); ?>" data-status="<?php echo esc_attr( $Wishlist ); ?>" data-wishlist_key="<?php echo esc_attr( $wishlist_key ); ?>">
	<div class="sp-wishlist-button">
		<?php if ( 'left' === $args['icon_pos'] ) { ?>
			<span class="sp-wishlist-icon">
				<?php echo ! empty( $args['icon']['value'] ) ? wp_kses( $icon, sp_allowd_svg_tags() ) : '<i class="sp-icon">' . wp_kses( sp_get_svg_icon( 'wishlist' ), sp_allowd_svg_tags() ) . '</i>'; ?>
			</span>
		<?php } ?>
		<?php if ( $show_label ) { ?>
			<?php if ( $Wishlist == 'yes' ) { ?>
				<span class="sp-wishlist-label"><?php echo wp_kses( $label_remove, wp_kses_allowed_html( 'post' ) ); ?></span>
				<?php } else { ?>
					<span class="sp-wishlist-label"><?php echo wp_kses( $label, wp_kses_allowed_html( 'post' ) ); ?></span>
			<?php } ?>
		<?php } ?>
		<?php if ( 'right' === $args['icon_pos'] ) { ?>
			<span class="sp-wishlist-icon">
				<?php echo ! empty( $args['icon']['value'] ) ? wp_kses( $icon, sp_allowd_svg_tags() ) : '<i class="sp-icon">' . wp_kses( sp_get_svg_icon( 'wishlist' ), sp_allowd_svg_tags() ) . '</i>'; ?>
			</span>
		<?php } ?>
	</div>
</div>
