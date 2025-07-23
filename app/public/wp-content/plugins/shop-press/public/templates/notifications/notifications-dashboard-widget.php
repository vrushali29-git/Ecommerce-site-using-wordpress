<?php
/**
 * Notifications
 *
 * @package ShopPress\Templates
 *
 * @version 1.1.3
 */

use ShopPress\Modules\Notifications;

defined( 'ABSPATH' ) || exit;

$args           = array(
	'posts_per_page' => 3,
);
$_notifications = Notifications::get_notifications( $args );
$notifications  = $_notifications['notifications'] ?? array();
$page_count     = $_notifications['page_count'] ?? 1;

?>
<div class="sp-notifications-wrap sp-notifications-dashboard-widget">
	<h3><?php esc_html_e( 'Notifications', 'shop-press' ); ?></h3>
	<div class="sp-notifications-items">
		<?php
		if ( ! empty( $notifications ) ) {

			foreach ( $notifications as $notification_id => $notification ) {

				switch ( $notification['type'] ) {

					case 'order_comment':
						include __DIR__ . '/notification-comment-item.php';
						break;
					default:
						include __DIR__ . '/notification-item.php';
				}
			}
		} else {

			echo '<div class="shoppress-not-found">' . esc_html__( 'Not found', 'shop-press' ) . '</div>';
		}
		?>
	</div>
</div>
