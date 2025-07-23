<?php
/**
 * Mini Wishlist.
 *
 * @package ShopPress
 */

defined( 'ABSPATH' ) || exit;

use ShopPress\Modules\Wishlist\MenuWishlist;
use ShopPress\Helpers\ThumbnailGenerator;
use ShopPress\Modules\Wishlist\Main;

$wishlist = Main::get_wishlist_products();
$count    = count( $wishlist );
?>

<div class="sp-header-toggle sp-header-wishlist <?php echo esc_attr_e( $args['open_in'] ); ?> sp-open-<?php echo esc_attr_e( $args['open_in'] ); ?>">
	<div class="sp-header-toggle-click">
		<?php
		if ( $args['placeholder'] ) :
			switch ( $args['placeholder'] ) {
				case 'image':
					if ( isset( $args['image']['id'] ) ) {
						ThumbnailGenerator::instance()->image_resize_output(
							$args['image']['id'],
							( isset( $args['image_custom_dimension'] ) && ! empty( $args['image_custom_dimension'] ) ? $args['image_custom_dimension'] : $args['image_size'] )
						);
					}
					break;

				case 'text':
					echo '<p class="header-toggle-text">' . wp_kses( $args['text'], wp_kses_allowed_html( 'post' ) ) . '</p>';
					break;

				case 'icon':
				default:
					$icon = sp_render_icon( $args['icon'] ?? '' );
					echo wp_kses( $icon, sp_allowd_svg_tags() );
					echo '<p class="header-toggle-text">' . wp_kses( $args['text'], wp_kses_allowed_html( 'post' ) ) . '</p>';
					break;
			}
		endif;
		?>
		<span class="sp-wishlist-items-count"><?php echo esc_html( $count ); ?></span>
	</div>

	<div class="sp-header-toggle-content-wrap" style="display: none;">
		<div class="sp-header-toggle-close">
			<?php echo wp_kses( sp_get_svg_icon( 'close' ), sp_allowd_svg_tags() ); ?>
		</div>

		<div class="sp-mini-wishlist">
			<div class="sp-wishlist-title"><?php esc_html_e( 'Wishlist', 'shop-press' ); ?></div>
			<div class="sp-wishlist-items">
				<?php echo MenuWishlist::wishlist_content( $wishlist ); ?>
			</div>
		</div>
	</div>
</div>
