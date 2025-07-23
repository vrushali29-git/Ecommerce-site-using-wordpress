<?php
/**
 * My Wishlist.
 *
 * @package ShopPress
 */

defined( 'ABSPATH' ) || exit;

use ShopPress\Elementor\ShopPressWidgets;
use ShopPress\Helpers\ThumbnailGenerator as ThumbGen;
use ShopPress\Modules\Wishlist\Main as WishlistMain;

$thumbnail_width  = sp_get_module_settings( 'wishlist_table_settings', 'thumbnail_size_width', 90 );
$thumbnail_width  = ! empty( $thumbnail_width ) ? $thumbnail_width : 90;
$thumbnail_height = sp_get_module_settings( 'wishlist_table_settings', 'thumbnail_size_height', 90 );
$thumbnail_height = ! empty( $thumbnail_height ) ? $thumbnail_height : 90;

$bulk_actions        = WishlistMain::get_bulk_actions();
$bulk_actions        = is_array( $bulk_actions ) ? array_column( $bulk_actions, 'label', 'value' ) : array();
$active_bulk_actions = sp_get_module_settings( 'wishlist_table_settings', 'bulk_actions' );
$active_bulk_actions = is_array( $active_bulk_actions ) ? array_column( $active_bulk_actions, 'label', 'value' ) : array();
$wishlists           = WishlistMain::get_current_user_wishlists();
unset( $wishlists[ $wishlist_key ] );
if ( 0 === count( $wishlists ) ) {
	unset( $bulk_actions['move_to_another_wishlist'] );
}

$fields_in_table = array_column(
	sp_get_module_settings( 'wishlist_table_settings', 'fields_in_table' ),
	'value'
);

foreach ( $fields_in_table as $f_key ) {

	$custom_label              = sp_get_module_settings( 'wishlist_table_settings', "custom_label_{$f_key}", '' );
	$fields_in_table[ $f_key ] = $custom_label;
}

?>
<div class="sp-wishlist-topbar">
	<div class="sp-wishlist-bulk-action-wrapper">
		<?php if ( ! empty( $bulk_actions ) && ! empty( $active_bulk_actions ) ) : ?>
			<div class="sp-wishlist-bulk-action-select-wrapper sp-wishlist-bulk-actions-wrapper">
				<select class="sp-wishlist-bulk-actions" name="sp-wishlist-bulk-actions">
					<?php
					foreach ( $bulk_actions as $ba_value => $ba_label ) :
						if ( ! isset( $active_bulk_actions[ $ba_value ] ) ) {
							continue;
						}
						?>
						<option value="<?php echo esc_attr( $ba_value ); ?>"><?php echo esc_html( $ba_label ); ?></option>
					<?php endforeach; ?>
				</select>
			</div>

			<?php if ( isset( $bulk_actions['move_to_another_wishlist'] ) && count( $wishlists ) ) : ?>
				<div class="sp-wishlist-bulk-action-select-wrapper sp-wishlist-move-to-wrapper">
					<select class="sp-wishlist-move-to" name="sp-wishlist-move-to">
						<?php foreach ( $wishlists as $wishlist_id => $wishlist ) : ?>
							<option value="<?php echo esc_attr( $wishlist['key'] ?? '' ); ?>"><?php echo esc_html( $wishlist['title'] ?? '' ); ?></option>
						<?php endforeach; ?>
					</select>
				</div>
			<?php endif; ?>

			<button class="sp-run-bulk-action"><?php esc_html_e( 'Apply', 'shop-press' ); ?></button>
		<?php endif; ?>
	</div>
</div>
<table class="my-wishlist-table" data-wishlist_key="<?php echo esc_attr( $wishlist_key ); ?>">
	<thead>
		<tr>
			<?php if ( isset( $fields_in_table['thumbnail'] ) || isset( $fields_in_table['title'] ) ) { ?>
				<?php $custom_label_item = sp_get_module_settings( 'wishlist_table_settings', 'custom_label_item', '' ); ?>
				<th class="sp-h-product" colspan="3"><?php echo $custom_label_item ? $custom_label_item : __( 'Item', 'shop-press' ); ?></th>
			<?php } ?>
			<?php if ( isset( $fields_in_table['price'] ) ) { ?>
				<th class="sp-h-price"><?php echo $fields_in_table['price'] ? $fields_in_table['price'] : __( 'price', 'shop-press' ); ?></th>
			<?php } ?>
			<?php if ( isset( $fields_in_table['date_added'] ) ) { ?>
				<th class="sp-h-date-added"><?php echo $fields_in_table['date_added'] ? $fields_in_table['date_added'] : __( 'Date Added', 'shop-press' ); ?></th>
			<?php } ?>
			<?php if ( isset( $fields_in_table['stock'] ) ) { ?>
				<th class="sp-h-stock"><?php echo $fields_in_table['stock'] ? $fields_in_table['stock'] : __( 'stock', 'shop-press' ); ?></th>
			<?php } ?>
			<?php if ( isset( $fields_in_table['add_to_cart'] ) ) { ?>
				<th class="sp-h-add-to-cart"><?php echo $fields_in_table['add_to_cart'] ? $fields_in_table['add_to_cart'] : ''; ?></th>
			<?php } ?>
			<?php if ( isset( $fields_in_table['remove'] ) ) { ?>
				<th class="sp-h-actions"><?php echo $fields_in_table['remove'] ? $fields_in_table['remove'] : ''; ?></th>
			<?php } ?>
		</tr>
	</thead>
	<tbody>
		<?php
		foreach ( $product_ids as $product_id => $product_data ) {

			$wishlist_key = $product_data['wishlist_key'] ?? '';
			$product_id   = $product_data['product_id'] ?? '';
			$product      = wc_get_product( $product_id );
			if ( ! is_a( $product, '\WC_Product' ) ) {
				continue;
			}
			?>

			<tr class="sp-wishlist-button-container sp-wishlist-list sp-wishlist-<?php echo esc_attr( $product_id ); ?>" data-status="yes" data-product_id="<?php echo esc_attr( $product_id ); ?>" data-wishlist_key="<?php echo esc_attr( $wishlist_key ); ?>">
				<?php if ( isset( $fields_in_table['thumbnail'] ) || isset( $fields_in_table['title'] ) ) : ?>
					<td class="sp-product" colspan="3">
						<div class="sp-product-row">
							<?php if ( ! empty( $bulk_actions ) && ! empty( $active_bulk_actions ) ) : ?>
								<input type="checkbox" name="sp-wishlist-products[]" value="<?php echo esc_attr( $product_id ); ?>" />
							<?php endif; ?>
							<?php if ( isset( $fields_in_table['thumbnail'] ) ) : ?>
								<div class="sp-product-col">
									<div class="sp-thumb">
										<?php
										echo wp_kses_post(
											ThumbGen::instance()->image_resize_output(
												get_post_thumbnail_id( $product_id ),
												array(
													$thumbnail_width,
													$thumbnail_height,
												)
											) ?? ''
										);
										?>
									</div>
								</div>
							<?php endif; ?>
							<?php if ( isset( $fields_in_table['title'] ) ) : ?>
								<div class="sp-product-col">
									<div class="sp-content">
										<h4 class="sp-prod-title">
											<a href="<?php echo esc_url( $product->get_permalink() ); ?>"><?php echo wp_kses_post( $product->get_title() ); ?></a>
										</h4>
									</div>
								</div>
							<?php endif; ?>

						</div>
					</td>
				<?php endif; ?>
				<?php if ( isset( $fields_in_table['price'] ) ) : ?>
					<td class="sp-product-price"><?php echo wp_kses_post( $product->get_price_html() ); ?></td>
				<?php endif; ?>
				<?php if ( isset( $fields_in_table['date_added'] ) ) : ?>
					<td class="sp-product-date-added"><?php echo wp_kses_post( isset( $product_data['date_added'] ) ? date_i18n( $date_format, $product_data['date_added'] ) : '' ); ?></td>
				<?php endif; ?>
				<?php if ( isset( $fields_in_table['stock'] ) ) : ?>
					<td class="sp-product-stock-wrapper">
						<p class="sp-product-stock <?php echo esc_attr( $product->get_stock_status() ); ?>">
							<?php
							if ( 'outofstock' == $product->get_stock_status() ) {
								echo __( 'out of stock', 'shop-press' );
							} elseif ( 'instock' == $product->get_stock_status() ) {
								echo __( 'in stock', 'shop-press' );
							}
							?>
						</p>
					</td>
				<?php endif; ?>
				<?php if ( isset( $fields_in_table['add_to_cart'] ) ) : ?>
					<td class="sp-product-addtocart">
						<?php
							sp_add_to_cart_link( $product );
						?>
					</td>
				<?php endif; ?>
				<?php if ( isset( $fields_in_table['remove'] ) ) : ?>
					<td class="sp-product-remove-item">
						<div class="sp-product-col">
							<i class="sp-rmf-wishlist">
								<svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 10 10">
									<path d="M9.79.21a.717.717,0,0,1,0,1.014L5.956,5.057,9.674,8.776A.717.717,0,0,1,8.66,9.79L4.942,6.072l-3.6,3.6A.717.717,0,0,1,.326,8.66l3.6-3.6L.21,1.34A.717.717,0,0,1,1.224.326L4.942,4.043,8.776.21A.717.717,0,0,1,9.79.21Z" fill="#959ca7"/>
								</svg>
							</i>
						</div>
					</td>
				<?php endif; ?>
			</tr>
			<?php
		}
		?>
	</tbody>
</table>

<?php
