<?php
/**
 * Loop Compare.
 *
 * @package ShopPress
 */

defined( 'ABSPATH' ) || exit;

use ShopPress\Modules\Compare;

$product_id   = get_the_ID();
$Comparelist  = Compare::get_compare_product_ids();
$Comparelist  = is_array( $Comparelist ) && in_array( $product_id, $Comparelist ) ? 'yes' : 'no';
$show_label   = $args['show_label'] ?? '';
$label_remove = sp_get_module_settings( 'compare', 'remove_label' );
$label        = sp_get_module_settings( 'compare', 'add_label' );
$icon         = sp_render_icon(
	$args['icon'] ?? '',
	array(
		'aria-hidden' => 'true',
	)
);

$overlay = $args['overlay'] ?? '';

?>
<div class="sp-compare-button sp-product-compare <?php echo $overlay === 'yes' ? 'overlay' : ''; ?>" data-product_id="<?php echo esc_attr( $product_id ); ?>" data-status="<?php echo esc_attr( $Comparelist ); ?>">
	<div class="sp-compare-button-loop">
		<?php if ( 'left' === $args['icon_pos'] ) { ?>
			<span class="sp-compare-icon">
				<?php echo ! empty( $args['icon']['value'] ) ? wp_kses( $icon, sp_allowd_svg_tags() ) : '<i class="sp-icon">' . wp_kses( sp_get_svg_icon( 'compare' ), sp_allowd_svg_tags() ) . '</i>'; ?>
			</span>
		<?php } ?>
		<?php if ( $show_label ) { ?>
			<?php if ( $Comparelist == 'yes' ) { ?>
				<span class="sp-compare-label"><?php echo wp_kses( $label_remove, wp_kses_allowed_html( 'post' ) ); ?></span>
				<?php } else { ?>
					<span class="sp-compare-label"><?php echo wp_kses( $label, wp_kses_allowed_html( 'post' ) ); ?></span>
			<?php } ?>
		<?php } ?>
		<?php if ( 'right' === $args['icon_pos'] ) { ?>
			<span class="sp-compare-icon">
				<?php echo ! empty( $args['icon']['value'] ) ? wp_kses( $icon, sp_allowd_svg_tags() ) : '<i class="sp-icon">' . wp_kses( sp_get_svg_icon( 'compare' ), sp_allowd_svg_tags() ) . '</i>'; ?>
			</span>
		<?php } ?>
	</div>
</div>
