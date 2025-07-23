<?php
/**
 * Product Rating.
 *
 * @package ShopPress
 */

defined( 'ABSPATH' ) || exit;

if ( ! wc_review_ratings_enabled() ) {
	return;
}

global $product;
$rating_count = $product->get_rating_count();
$review_count = $product->get_review_count();
$average      = number_format( $product->get_average_rating(), 1 );
$permalink    = $product->get_permalink();
$link         = is_sp_quick_view_ajax() ? $permalink . '#reviews' : '#reviews';

if ( $rating_count > 0 ) : ?>
	<?php if ( 'modern' === $args['rating_type'] ) : ?>
		<div class="woocommerce-product-rating sp-single-rating sp-modern-rating">
			<?php echo '<div class="sp-rating"><span class="sp-rating-star">S</span>' . $average . '</div>'; ?>
			<?php if ( comments_open() ) : ?>
				<?php if ( 'yes' === $args['show_review_counter'] ) { ?>
					<a href="<?php echo esc_url( $link ); ?>" class="woocommerce-review-link" rel="nofollow"><?php printf( _n( '%s review', '%s reviews', $review_count, 'shop-press' ), '<span class="count">' . esc_html( $review_count ) . '</span>' ); ?></a>
				<?php } ?>
			<?php endif ?>
		</div>
	<?php elseif ( 'classic' === $args['rating_type'] ) : ?>
		<div class="woocommerce-product-rating sp-single-rating sp-classic-rating">
		<?php echo wc_get_rating_html( $average, $rating_count ); // WPCS: XSS ok. ?>
			<?php if ( comments_open() ) : ?>
				<?php if ( 'yes' === $args['show_review_counter'] ) { ?>
					<a href="<?php echo esc_url( $link ); ?>" class="woocommerce-review-link" rel="nofollow"><?php printf( _n( '%s review', '%s reviews', $review_count, 'shop-press' ), '<span class="count">' . esc_html( $review_count ) . '</span>' ); ?></a>
				<?php } ?>
			<?php endif ?>
		</div>
	<?php endif; ?>
<?php endif; ?>
