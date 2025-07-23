<?php
/**
 * Product Compare.
 *
 * @package ShopPress
 */

defined( 'ABSPATH' ) || exit;

use ShopPress\Modules\Compare;

$product_id  = get_the_ID();
$Comparelist = Compare::get_compare_product_ids();
$Comparelist = is_array( $Comparelist ) && in_array( $product_id, $Comparelist ) ? 'yes' : 'no';
if ( 'yes' === $Comparelist ) {
	$label = sp_get_module_settings( 'compare', 'remove_label' );
} else {
	$label = sp_get_module_settings( 'compare', 'add_label' );
}

$custom_icon = sp_render_icon(
	$args['icon'] ?? '',
	array()
);
?>

<div class="sp-single-compare sp-compare-button-wrapper" data-product_id="<?php echo esc_attr( $product_id ); ?>" data-status="<?php echo esc_attr( $Comparelist ); ?>">
	<div class="sp-compare-button">
		<?php if ( 'label' === $args['type'] ) : ?>
			<?php if ( ! empty( $label ) ) { ?>
				<span class="sp-compare-label"><?php echo wp_kses( $label, wp_kses_allowed_html( 'post' ) ); ?></span>
			<?php } ?>
		<?php endif; ?>

		<?php if ( 'icon' === $args['type'] ) : ?>
			<span class="sp-compare-icon">
				<?php echo ! empty( $args['icon']['value'] ) ? wp_kses( $custom_icon, sp_allowd_svg_tags() ) : '<i class="sp-icon">' . wp_kses( sp_get_svg_icon( 'compare' ), sp_allowd_svg_tags() ) . '</i>'; ?>
			</span>
		<?php endif; ?>

		<?php if ( 'icon-label' === $args['type'] ) : ?>
			<span class="sp-compare-icon">
				<?php echo ! empty( $args['icon']['value'] ) ? wp_kses( $custom_icon, sp_allowd_svg_tags() ) : '<i class="sp-icon">' . wp_kses( sp_get_svg_icon( 'compare' ), sp_allowd_svg_tags() ) . '</i>'; ?>
			</span>
			<?php if ( ! empty( $label ) ) { ?>
				<span class="sp-compare-label"><?php echo wp_kses( $label, wp_kses_allowed_html( 'post' ) ); ?></span>
			<?php } ?>
		<?php endif; ?>
	</div>
</div>
