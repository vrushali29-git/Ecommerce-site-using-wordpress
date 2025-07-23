<?php
/**
 * My Wishlist Grid.
 *
 * @package ShopPress
 */

defined( 'ABSPATH' ) || exit;

?>

<div class="sp-my-wishlist-grid sp-my-wishlist-items">
	<?php
	foreach ( $product_ids as $product_id => $product_data ) :
		$wishlist_key = $product_data['wishlist_key'] ?? '';
		$product_id   = $product_data['product_id'] ?? '';
		$product      = wc_get_product( $product_id );
		$rating       = $product->get_rating_counts() ? (int) $product->get_average_rating() : '';
		if ( ! is_a( $product, '\WC_Product' ) ) {
			continue;
		}

		$stock_status_text = '';
		if ( 'outofstock' == $product->get_stock_status() ) {
			$stock_status_text = __( 'out of stock', 'shop-press' );
		} elseif ( 'instock' == $product->get_stock_status() ) {
			$stock_status_text = __( 'in stock', 'shop-press' );
		}
		?>
		<div class="sp-my-wishlist-item sp-wishlist sp-product" data-product_id="<?php echo esc_attr( $product_id ); ?>" data-wishlist_key="<?php echo esc_attr( $wishlist_key ); ?>">
			<div class="sp-my-wishlist-item-thumbnail">
				<a href="<?php echo esc_url( $product->get_permalink() ); ?>"><?php echo $product->get_image( $product_id ); ?></a>
			</div>
			<h4 class="sp-my-wishlist-item-title">
				<a href="<?php echo esc_url( $product->get_permalink() ); ?>"><?php echo wp_kses_post( $product->get_title() ); ?></a>
			</h4>

			<div class="sp-flex-row">
				<div class="sp-my-wishlist-item-price"><?php echo wp_kses_post( $product->get_price_html() ); ?></div>
				<?php if ( ! empty( $rating ) ) : ?>
					<div class="sp-my-wishlist-item-rating">
						<?php echo sp_kses_post( sp_get_svg_icon( 'star' ) ); ?>
						<?php echo sp_kses_post( $rating ); ?>
					</div>
				<?php endif; ?>
			</div>
			<div class="sp-my-wishlist-item-date-added">
				<?php echo sp_kses_post( sp_get_svg_icon( 'date' ) ); ?>
				<?php echo sp_kses_post( sprintf( __( 'Added: %s', 'shop-press' ), wp_kses_post( isset( $product_data['date_added'] ) ? date_i18n( $date_format, $product_data['date_added'] ) : '' ) ) ); ?>
			</div>
			<div class="sp-my-wishlist-item-stock <?php echo esc_attr( $product->get_stock_status() ); ?>"><?php echo esc_html( $stock_status_text ); ?></div>

			<div class="sp-my-wishlist-item-remove-item">
				<i class="sp-rmf-wishlist">
					<?php echo sp_kses_post( sp_get_svg_icon( 'close' ) ); ?>
				</i>
			</div>
		</div>
	<?php endforeach; ?>
</div>

<?php
