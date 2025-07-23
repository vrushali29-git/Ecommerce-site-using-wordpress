<?php
/**
 * Product Navigation.
 *
 * @package ShopPress
 */

defined( 'ABSPATH' ) || exit;

$next_product = get_adjacent_post( false, '', false, 'product_cat' );
$prev_product = get_adjacent_post( false, '', true, 'product_cat' );
?>

<div class="sp-navigation">
	<?php if ( $prev_product && $prev_product->ID ) : ?>
		<div class="sp-prev-navigation sp-navigation-wrap">
			<a class="sp-navigation-link" href="<?php echo esc_url( get_permalink( $prev_product ) ); ?>">
			<svg xmlns="http://www.w3.org/2000/svg" width="13" height="10" viewBox="0 0 13 10">
				<path id="Path_4554" data-name="Path 4554" d="M88.968,76.719l.657.675-3.832,3.857H97v.937H85.794l3.832,3.857-.657.675-4.967-5Z" transform="translate(-84.001 -76.719)"/>
			</svg>
			</a>
			<?php echo wp_kses_post( sp_navigation_render_html( $prev_product->ID, $args['hover_details'] ?? '' ) ); ?>
		</div>
	<?php endif; ?>

	<?php if ( $next_product && $next_product->ID ) : ?>
		<div class="sp-next-navigation sp-navigation-wrap">
			<a class="sp-navigation-link" href="<?php echo esc_url( get_permalink( $next_product ) ); ?>">
			<svg xmlns="http://www.w3.org/2000/svg" width="13" height="10" viewBox="0 0 13 10">
				<path id="Path_4554" data-name="Path 4554" d="M92.034,76.719l-.657.675,3.832,3.857H84v.937H95.208l-3.832,3.857.657.675,4.967-5Z" transform="translate(-84.001 -76.719)"/>
			</svg>
			</a>
			<?php echo wp_kses_post( sp_navigation_render_html( $next_product->ID, $args['hover_details'] ?? '' ) ); ?>
		</div>
	<?php endif; ?>
</div>
