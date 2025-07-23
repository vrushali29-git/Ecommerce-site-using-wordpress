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

$endpoint = 'notifications';
$paged    = esc_html( get_query_var( $endpoint ) );
$paged    = $paged ? $paged : 1;
$args     = array(
	'paged' => $paged,
);

$_notifications = Notifications::get_notifications( $args );
$notifications  = $_notifications['notifications'] ?? array();
$page_count     = $_notifications['page_count'] ?? 1;

?>
<div class="sp-notifications-wrap">
	<h3><?php esc_html_e( 'Notifications', 'shop-press' ); ?></h3>
	<div class="sp-notifications-items">
		<?php
		if ( ! empty( $notifications ) ) {

			foreach ( $notifications as $notification_id => $notification ) {

				switch ( $notification['type'] ) {

					// case 'order_comment':
					// 	include __DIR__ . '/notification-comment-item.php';
					// 	break;
					default:
						include __DIR__ . '/notification-item.php';
				}
			}
		} else {

			echo '<div class="shoppress-not-found">' . esc_html__( 'Not found', 'shop-press' ) . '</div>';
		}
		?>
	</div>
	<?php
	if ( $page_count > 1 ) {

		echo '<div class="sp-notifications-page-nav">';
		if ( 1 < $paged ) {
			$prev = $paged - 1;
			$url  = wc_get_endpoint_url( $endpoint, $prev );
			echo '<a class="button" href="' . esc_url( $url ) . '">' . __( 'Prev', 'shop-press' ) . '</a>';
		}

			$page_url = wc_get_endpoint_url( $endpoint, 'page_number' );
			echo '<input type="number" name="" class="" placeholder="' . esc_attr__( 'Page Number', 'shop-press' ) . '" value="' . esc_attr( $paged ) . '" data-page-url="' . $page_url . '" max="' . $page_count . '" onchange="window.location = jQuery(this).data(\'page-url\').replace(\'page_number\', jQuery(this).val())"/>';

		if ( $page_count > $paged ) {
			$next = $paged + 1;
			$url  = wc_get_endpoint_url( $endpoint, $next );
			echo '<a class="button" href="' . esc_url( $url ) . '">' . __( 'Next', 'shop-press' ) . '</a>';
		}
			echo '</div>';
	}
	?>
</div>
