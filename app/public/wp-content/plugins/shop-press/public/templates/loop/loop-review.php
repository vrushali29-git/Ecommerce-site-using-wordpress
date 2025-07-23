<?php
/**
 * Loop Review.
 *
 * @package ShopPress
 */

defined( 'ABSPATH' ) || exit;

global $product;

if ( ! wc_review_ratings_enabled() ) {
	return;
}

$icon = sp_render_icon( $args['icon'] ?? '', array( 'aria-hidden' => 'true' ) );

?>
<div class="sp-product-review">
	<?php echo wp_kses( $icon, sp_allowd_svg_tags() ); ?>
	<span class="review-count">
		<?php
			echo esc_html( $product->get_review_count() );
		?>
	</span>
</div>
