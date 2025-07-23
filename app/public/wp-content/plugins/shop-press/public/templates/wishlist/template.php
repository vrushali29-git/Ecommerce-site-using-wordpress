<?php
/**
 * Wishlist
 *
 * @version 1.0.0
 */

use ShopPress\Modules\Wishlist\Main;
use ShopPressPro\Modules\Wishlist as WishlistPro;

defined( 'ABSPATH' ) || exit;

$wishlist_key = get_query_var( 'view' );

$enable_show_social_share = sp_get_module_settings( 'wishlist_general_settings', 'enable_show_social_share' );

$limit          = 10;
$paged          = get_query_var( 'paged' );
$product_ids    = array();
$can_view       = Main::user_can_view_wishlist( $wishlist_key );
$wishlist_key   = $can_view ? $wishlist_key : null;
$product_ids    = Main::get_wishlist_products( $limit, $wishlist_key, $paged ? $paged : 1 );
$total_products = Main::get_total_wishlist_products( -1, $wishlist_key );
$wishlist       = $wishlist_key ? Main::get_wishlist( $wishlist_key ) : Main::get_current_user_default_wishlist( false );
$wishlist_id    = $wishlist['wishlist_id'] ?? 0;
$date_format    = get_option( 'date_format' );
$wishlist_title = isset( $wishlist['type'] ) && 'custom' === $wishlist['type'] && isset( $wishlist['title'] ) ? $wishlist['title'] : __( 'My wishlist', 'shop-press' );

?>
<div class="sp-my-wishlist">
	<?php
	if ( ! empty( $product_ids ) ) {
		?>
		<div class="sp-my-wishlist-header">
			<h2 class="my-wishlist-title">
				<svg xmlns="http://www.w3.org/2000/svg" width="17.7" height="16.551" viewBox="0 0 17.7 16.551">
					<g transform="translate(0.85 -4.149)">
						<path d="M13.5,20.85,10.16,17.421,6.845,13.991a4.915,4.915,0,0,1,0-6.757,4.388,4.388,0,0,1,6.413.355l.243.239.241-.249a4.388,4.388,0,0,1,6.413-.355,4.915,4.915,0,0,1,0,6.757L16.84,17.41Z" transform="translate(-5.5 -1)" fill="none" stroke="#474849" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" fill-rule="evenodd"/>
					</g>
				</svg>
				<?php echo esc_html( $wishlist_title ); ?>
			</h2>
			<div class="sp-wishlist-info">
				<div class="sp-wishlist-share-status <?php echo esc_attr( $wishlist['share_status'] ?? 'public' ); ?>"><?php echo esc_html( $wishlist['share_status_label'] ?? __( 'Public', 'shop-press' ) ); ?></div>
				<div class="sp-wishlist-created-date"><?php echo __( 'Created:', 'shop-press' ) . ' ' . date_i18n( $date_format, $wishlist['created_date'] ?? '' ); ?></div>
			</div>
			<div class="sp-wishlist-author">
				<div class="sp-wishlist-author-avatar"><?php echo get_avatar( $wishlist['author_email'] ?? '' ); ?></div>
				<div class="sp-wishlist-author-name"><?php echo $wishlist['author_name'] ?? ''; ?></div>
			</div>
			<?php if ( isset( $wishlist['share_status'] ) && 'public' === $wishlist['share_status'] && class_exists( WishlistPro::class ) && $enable_show_social_share ) : ?>
				<span class="sp-wishlist-share" data-wishlist_link="<?php echo esc_url( Main::get_wishlist_link( $wishlist_id, $wishlist_key ) ); ?>" data-wishlist_title="<?php esc_attr__( $wishlist['title'] ?? '-', 'shop-press' ); ?>"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="18" height="18" viewBox="0 0 18 18"><g><path d="M195.6,100.436a2.588,2.588,0,0,0-2.074,1.06l-4.359-2.8a2.674,2.674,0,0,0,.094-1.956l4.406-2.828a2.531,2.531,0,0,0,1.932.872,2.592,2.592,0,1,0-2.592-2.592,2.866,2.866,0,0,0,.165.9l-4.383,2.828a2.591,2.591,0,1,0-1.956,4.289,2.527,2.527,0,0,0,1.791-.73l4.477,2.875a2.966,2.966,0,0,0-.094.66,2.592,2.592,0,1,0,2.592-2.569Zm0-9.873a1.649,1.649,0,1,1-1.649,1.649A1.654,1.654,0,0,1,195.6,90.563Zm-8.766,8.695a1.649,1.649,0,1,1,1.649-1.649A1.654,1.654,0,0,1,186.833,99.258Zm8.766,5.4A1.649,1.649,0,1,1,197.248,103,1.654,1.654,0,0,1,195.6,104.654Z" transform="translate(-182.241 -88.597)" fill="#bcc4ce"/></g></svg></span>
			<?php endif; ?>
		</div>
		<?php
			$items_style    = sp_get_module_settings( 'wishlist_table_settings', 'my_wishlist_view_type' )['value'] ?? 'table';
			$items_filename = sp_get_template_path( 'wishlist/my-wishlist-' . $items_style );
			$items_filename = file_exists( $items_filename ) ? $items_filename : sp_get_template_path( 'wishlist/my-wishlist-table' );
			include $items_filename;
		?>
		<div class="sp-my-wishlist-footer">
			<nav class="sp-pagination">
				<?php
				echo paginate_links(
					apply_filters(
						'shoppress_pagination_args',
						array( // WPCS: XSS ok.
							'base'      => esc_url_raw( str_replace( 999999999, '%#%', Main::get_wishlist_link( $wishlist['wishlist_id'], $wishlist['key'], 999999999 ) ) ),
							'add_args'  => false,
							'current'   => max( 1, $paged ),
							'total'     => $total_products / $limit,
							'prev_text' => is_rtl() ? '&rarr;' : '&larr;',
							'next_text' => is_rtl() ? '&larr;' : '&rarr;',
							'type'      => 'list',
							'end_size'  => 3,
							'mid_size'  => 3,
						)
					)
				);
				?>
			</nav>
		</div>
		<?php
	} else {
		$empty_wishlist_title       = sp_get_module_settings( 'wishlist_table_settings', 'empty_wishlist_title' );
		$empty_wishlist_button_text = sp_get_module_settings( 'wishlist_table_settings', 'empty_wishlist_button_text' );
		?>
		<div class="sp-my-wishlist-empty">
			<i class="ti-heart"></i>
			<h2><?php echo $empty_wishlist_title ? $empty_wishlist_title : __( 'Your wishlist is currently empty', 'shop-press' ); ?></h2>
			<a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="button return-to-shop"><?php echo $empty_wishlist_button_text ? $empty_wishlist_button_text : __( 'Shop Now', 'shop-press' ); ?></a>
		</div>
		<?php
	}

	require sp_get_template_path( 'wishlist/share-popup' );
	?>
</div>
