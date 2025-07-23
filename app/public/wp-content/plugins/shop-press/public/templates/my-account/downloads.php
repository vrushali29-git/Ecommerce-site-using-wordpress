<?php
/**
 * My Account Downloads.
 *
 * @package ShopPress
 */

defined( 'ABSPATH' ) || exit;

$downloads     = isset( WC()->customer ) && '' !== WC()->customer ? WC()->customer->get_downloadable_products() : array();
$has_downloads = (bool) $downloads;

do_action( 'woocommerce_before_account_downloads', $has_downloads ); ?>

<?php if ( $has_downloads ) : ?>
	<div class="sp-downloads">
		<?php do_action( 'woocommerce_before_available_downloads' ); ?>

		<?php do_action( 'woocommerce_available_downloads', $downloads ); ?>

		<?php do_action( 'woocommerce_after_available_downloads' ); ?>
	</div>
<?php else : ?>
	<div class="sp-downloads">
		<div class="woocommerce-Message woocommerce-Message--info woocommerce-info">
			<a class="woocommerce-Button button" href="<?php echo esc_url( apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) ); ?>">
				<?php esc_html_e( 'Browse products', 'shop-press' ); ?>
			</a>
			<?php esc_html_e( 'No downloads available yet.', 'shop-press' ); ?>
		</div>
	</div>
<?php endif; ?>

<?php do_action( 'woocommerce_after_account_downloads', $has_downloads ); ?>
