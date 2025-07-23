<?php
/**
 * Notification item
 *
 * @package ShopPress\Templates
 *
 * @version 1.1.3
 */

defined( 'ABSPATH' ) || exit;

?>
<div class="sp-notification-item">
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
		<div class="sp-notification-item-content">
			<?php echo esc_html( $notification['content'] ); ?>
			<?php if ( isset( $notification['link_url'] ) ) : ?>
				<a href="<?php echo esc_html( $notification['link_url'] ); ?>"><?php echo esc_html( $notification['link_text'] ); ?></a>
			<?php endif; ?>
		</div>
	</div>
	<div class="sp-notification-item-content-show-more">
		<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
			<g fill="#fff" stroke="#e3e5e7" stroke-width="1">
				<circle cx="12" cy="12" r="12" stroke="none"/>
				<circle cx="12" cy="12" r="11.5" fill="none"/>
			</g>
			<path d="M5.786,4.91,1.261.224a.721.721,0,0,0-1.044,0,.784.784,0,0,0,0,1.081l4.05,4.2L.216,9.695a.784.784,0,0,0,0,1.081.721.721,0,0,0,1.044,0L5.786,6.09A.765.765,0,0,0,6,5.524.87.87,0,0,0,5.786,4.91Z" transform="translate(7 14) rotate(-90)" fill="#b7bec9"/>
		</svg>
	</div>
</div>
