<?php
/**
 * Notification item
 *
 * @package ShopPress\Templates
 *
 * @version 1.1.3
 */

use ShopPress\Modules\Notifications;

defined( 'ABSPATH' ) || exit;

$order_id = $notification['order_id'];

$order = wc_get_order( $order_id );
if ( ! is_a( $order, '\WC_Order' ) ) {
	return;
}
$order_items = $order->get_items();

$is_reviews_enabled       = Notifications::is_reviews_enabled();
$is_review_rating_enabled = Notifications::is_review_rating_enabled();

?>

<div class="sp-notification-item sp-notification-comment-item">
	<div class="sp-notification-item-date-wrap">
		<div class="sp-notification-item-date">
			<?php echo esc_html( $notification['date'] ); ?>
		</div>
	</div>
	<div class="sp-notification-item-sidebar-wrap">
		<div class="sp-notification-item-icon">
			<svg xmlns="http://www.w3.org/2000/svg" width="22.667" height="24" viewBox="0 0 22.667 24">
				<path id="sp-notification-icon" d="M11.333,24a2.669,2.669,0,0,1-2.666-2.666,2.726,2.726,0,0,1,.084-.667H1a1,1,0,0,1,0-2H2V11.333a9.336,9.336,0,0,1,8-9.239V1.333a1.334,1.334,0,0,1,2.668,0V2.1a9.335,9.335,0,0,1,8,9.238v7.334h1a1,1,0,0,1,0,2H13.916a2.729,2.729,0,0,1,.084.667A2.67,2.67,0,0,1,11.333,24Zm0-20a7.31,7.31,0,0,0-7.327,7.039L4,11.333v7.334H18.667V11.333A7.342,7.342,0,0,0,11.333,4Z"/>
			</svg>
		</div>
	</div>
	<div class="sp-notification-item-content-wrap">
		<div class="sp-notification-item-title"><?php echo esc_html( $notification['title'] ); ?></div>
		<div class="sp-notification-item-content"><?php echo esc_html( $notification['content'] ); ?></div>
	</div>
	<div class="sp-notification-item-content-show-more">
		<?php if ( isset( $notification['link_url'] ) ) : ?>
			<a class="sp-notification-view-add-review-open-popup" href="<?php echo esc_html( $notification['link_url'] ); ?>"><?php echo esc_html( $notification['link_text'] ); ?></a>
			<div class="sp-notification-view-add-review-popup" id="<?php echo esc_attr( str_replace( '#', '', $notification['link_url'] ) ); ?>">
				<div class="sp-notification-view-add-review-popup-wrap">
					<div class="sp-notification-view-add-review-close-popup">&#x2715</div>
					<div class="sp-notification-order-items">
						<div class="sp-notification-order-items-header">
							<div><?php esc_html_e( 'Item', 'shop-press' ); ?></div>
							<?php if ( $is_review_rating_enabled ) : ?>
								<div><?php esc_html_e( 'Current rate', 'shop-press' ); ?></div>
							<?php endif; ?>
						</div>
							<?php
							foreach ( $order_items as $item ) {

								$product_id     = $item->get_product_id();
								$product        = wc_get_product( $product_id );
								$product_title  = $product->get_formatted_name();
								$featured_image = $product->get_image( array( 50, 50, false ) );
								$review_link    = get_permalink( $product_id ) . '#reviews';

								$tooltip = '<span class="tooltiptext">' . __( 'Click to submit your rate.', 'shop-press' ) . '</span>';
								echo '<div class="sp-notification-order-item">'
										. $featured_image
										. '<a href="' . esc_html( $review_link ) . '" class="tooltip">' . $product_title . $tooltip . '</a>'
										. ( $is_review_rating_enabled ? wc_get_rating_html( $product->get_average_rating() ) : '' )
									. '</div>';
							}
							?>
						</table>
					</div>
				</div>
			</div>
		<?php endif; ?>

		<svg xmlns="http://www.w3.org/2000/svg" width="11" height="6" viewBox="0 0 11 6">
			<path id="sp-toggle-icon" d="M5.786,6.09,1.261,10.776a.721.721,0,0,1-1.044,0,.784.784,0,0,1,0-1.081l4.05-4.2L.216,1.305a.784.784,0,0,1,0-1.081.721.721,0,0,1,1.044,0L5.786,4.91A.765.765,0,0,1,6,5.476.87.87,0,0,1,5.786,6.09Z" transform="translate(11) rotate(90)" />
		</svg>
	</div>
</div>
