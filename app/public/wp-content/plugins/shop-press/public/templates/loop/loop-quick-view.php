<?php
/**
 * Loop Quick View.
 *
 * @package ShopPress
 */

defined( 'ABSPATH' ) || exit;

$product_id = get_the_ID();
$icon       = sp_render_icon(
	$args['icon'] ?? '',
	array(
		'aria-hidden' => 'true',
	)
);

$overlay = $args['overlay'] ?? '';

?>

<div class="sp-quick-view <?php echo $overlay === 'yes' ? 'overlay' : ''; ?>" data-product_id="<?php echo esc_attr( $product_id ); ?>">
	<?php if ( 'left' == $args['icon_pos'] ) { ?>
		<span class="sp-quick-view-icon">
			<?php echo ! empty( $args['icon']['value'] ) ? wp_kses( $icon, sp_allowd_svg_tags() ) : '<i class="sp-icon">' . wp_kses( sp_get_svg_icon( 'quick-view' ), sp_allowd_svg_tags() ) . '</i>'; ?>
		</span>
	<?php } ?>
	<?php if ( ! empty( $args['label'] ) ) { ?>
		<span class="sp-quickview-label sp-quick-view-label"><?php echo wp_kses( $args['label'], wp_kses_allowed_html( 'post' ) ); ?></span>
	<?php } ?>
	<?php if ( 'right' == $args['icon_pos'] ) { ?>
		<span class="sp-quick-view-icon">
			<?php echo ! empty( $args['icon']['value'] ) ? wp_kses( $icon, sp_allowd_svg_tags() ) : '<i class="sp-icon">' . wp_kses( sp_get_svg_icon( 'quick-view' ), sp_allowd_svg_tags() ) . '</i>'; ?>
		</span>
	<?php } ?>
</div>
