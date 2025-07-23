<?php
/**
 * Loop Rating.
 *
 * @package ShopPress
 */

defined( 'ABSPATH' ) || exit;

global $product;

if ( ! wc_review_ratings_enabled() ) {
	return;
}

$rating_count = $product->get_rating_count();
$review_count = $product->get_review_count();
$average      = number_format( $product->get_average_rating(), 1 );

if ( ! $product->get_rating_count() ) {
	return;
}
?>

<?php if ( 'modern' === $args['rating_type'] ) : ?>
	<div class="woocommerce-product-rating sp-loop-rating sp-modern-rating">
		<?php echo '<div class="sp-rating"><span class="sp-rating-star">S</span>' . $average . '</div>'; ?>
	</div>
<?php elseif ( 'classic' === $args['rating_type'] ) : ?>
	<div class="woocommerce-product-rating sp-loop-rating sp-classic-rating">
		<?php echo wc_get_rating_html( $average, $rating_count ); // WPCS: XSS ok. ?>
	</div>
<?php endif; ?>
