<?php
/**
 * Mini Cart.
 *
 * @package ShopPress
 */

defined( 'ABSPATH' ) || exit;

use ShopPress\Helpers\ThumbnailGenerator;

$cart_items_count = ! empty( WC()->cart ) ? WC()->cart->get_cart_contents_count() : 0;
?>

<div class="sp-header-toggle <?php esc_attr_e( $args['open_in'] ); ?> sp-open-<?php esc_attr_e( $args['open_in'] ); ?>">
	<div class="sp-header-toggle-click">
		<?php
		if ( isset( $args['placeholder'] ) ) :
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
		<span class="sp-cart-items-count"><?php echo esc_html( $cart_items_count ); ?></span>
	</div>

	<div class="sp-header-toggle-content-wrap" style="display: none;">
		<div class="sp-header-toggle-close">
			<?php echo wp_kses( sp_get_svg_icon( 'close' ), sp_allowd_svg_tags() ); ?>
		</div>

		<?php require sp_get_template_path( 'mini-cart/style-1' ); ?>
	</div>
</div>
