<?php
/**
 * My Account.
 *
 * @package ShopPress
 */

defined( 'ABSPATH' ) || exit;

if ( ! is_user_logged_in() ) {
	return wc_get_template( 'myaccount/form-login.php' );
}

$menu_items = $args['menu_items'] ?? array();

?>

<div class="sp-my-account-wrapper woocommerce">
	<?php do_action( 'woocommerce_before_account_navigation' ); ?>
		<nav class="woocommerce-MyAccount-navigation">
			<ul>
				<?php foreach ( wc_get_account_menu_items() as $endpoint => $label ) : ?>
					<?php if ( in_array( $endpoint, (array) $menu_items ) ) : ?>
						<?php continue; ?>
					<?php endif; ?>
					<li class="<?php echo wc_get_account_menu_item_classes( $endpoint ); ?>">
						<a href="<?php echo esc_url( wc_get_account_endpoint_url( $endpoint ) ); ?>">
							<span class="myaccount-icon-wrapper">
								<?php // sp_render_icon( $args[ 'nav_icon_' . $endpoint . '' ], array( 'aria-hidden' => 'true' ) ); ?>
							</span>
							<?php esc_html_e( $label ); ?>
						</a>
					</li>
				<?php endforeach; ?>
			</ul>
		</nav>
		<?php do_action( 'woocommerce_after_account_navigation' ); ?>

	<div class="woocommerce-MyAccount-content">
	<?php
		/**
		 * My Account content.
		 *
		 * @since 2.6.0
		 */
		do_action( 'woocommerce_account_content' );
	?>
	</div>
</div>
