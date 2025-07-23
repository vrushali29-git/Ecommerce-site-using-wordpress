<?php
/**
 * Loop Title.
 *
 * @package ShopPress
 */

defined( 'ABSPATH' ) || exit;

global $product;

$link_to_product = $args['link_to_product'] ?? '';

if ( 'yes' === $link_to_product ) :?>

	<a href="<?php echo get_permalink(); ?>" target="<?php echo esc_attr( $args['link_target'] ?? '' ); ?>">

		<<?php echo sp_whitelist_html_tags( $args['tag'], 'h4' );// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> class="product_title entry-title sp-product-title">
			<?php echo esc_html( $product->get_title() ); ?>
		</<?php echo sp_whitelist_html_tags( $args['tag'], 'h4' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>

	</a>

<?php else : ?>

	<<?php echo sp_whitelist_html_tags( $args['tag'], 'h4' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> class="product_title entry-title sp-product-title">
		<?php echo esc_html( $product->get_title() ); ?>
	</<?php echo sp_whitelist_html_tags( $args['tag'], 'h4' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>

<?php endif; ?>
