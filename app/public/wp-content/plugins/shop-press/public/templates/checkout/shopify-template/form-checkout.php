<?php
/**
 * Shopify checkout form
 *
 * @package ShopPress\Templates
 *
 * @version 1.0.0
 */

use ShopPress\Settings;

defined( 'ABSPATH' ) || exit;


$logo                  = sp_get_module_settings( 'shopify_checkout', 'logo', '' );
$is_bottom_menu        = sp_get_module_settings( 'shopify_checkout', 'bottom_menu' );
$checkout_bottom_menu  = sp_get_module_settings( 'shopify_checkout', 'bottom_menu' );
$is_cart_navigation    = sp_get_module_settings( 'shopify_checkout', 'cart_navigation' );
$is_shipping_step      = sp_get_module_settings( 'shopify_checkout', 'shipping_step', true );
$is_phone_number_field = sp_get_module_settings( 'shopify_checkout', 'phone_number_field' );
$is_company_name_field = sp_get_module_settings( 'shopify_checkout', 'company_name_field' );

$arrow_right  = '<svg xmlns="http://www.w3.org/2000/svg" width="6" height="11" viewBox="0 0 6 11">
<path id="right-icon" data-name="right-icon" d="M5.786,6.09,1.261,10.776a.721.721,0,0,1-1.044,0,.784.784,0,0,1,0-1.081l4.05-4.2L.216,1.305a.784.784,0,0,1,0-1.081.721.721,0,0,1,1.044,0L5.786,4.91A.765.765,0,0,1,6,5.476.87.87,0,0,1,5.786,6.09Z" fill="#b7bec9"/>
</svg>';
$checkout_url = wc_get_checkout_url();
echo '<script>let carUrl="' . esc_url( wc_get_cart_url() ) . '";</script>'
?>

<div class="sp-shopify-checkout-form">
	<div class="sp-shopify-container">
		<div class="sp-shopify-content">
			<div class="sp-shopify-form">
				<div class="sp-shopify-tabs-wrapper">
					<?php if ( $logo != '' ) { ?>
						<img class="logo-shopify-checkout" src="<?php echo $logo['url']; ?>"/>
					<?php } else { ?>
						<h1 class="sp-shopify-title-page"><?php bloginfo( 'name' ); ?></h1>
					<?php } ?>

					<?php if ( $is_cart_navigation ) : ?>
						<ul class="sp-shopify-tabs-list">

							<li class="sp-shopify-tab-item sp-shopify-cart" data-step-title="cart'">
								<a href="<?php echo esc_url( wc_get_cart_url() ); ?>"><span
										class="sp-tab-text"><?php esc_html_e( 'Cart', 'shop-press' ); ?></span></a>
								<?php echo $arrow_right; ?>
							</li>

							<li class="sp-shopify-tab-item sp-shopify-information <?php is_user_logged_in() ? esc_html_e( 'current' ) : ''; ?>"
								data-step-title="information">
								<span class="sp-tab-text"><?php esc_html_e( 'Information', 'shop-press' ); ?></span>
								<?php echo $arrow_right; ?>
							</li>

							<?php if ( $is_shipping_step ) : ?>
								<li class="sp-shopify-tab-item sp-shopify-shipping" data-step-title="shipping">
									<span class="sp-tab-text"><?php esc_html_e( 'Shipping', 'shop-press' ); ?></span>
									<?php echo $arrow_right; ?>
								</li>
							<?php endif; ?>

							<li class="sp-shopify-tab-item sp-shopify-payment" data-step-title="payment">
								<span class="sp-tab-text"><?php esc_html_e( 'Payment', 'shop-press' ); ?></span>
							</li>
						</ul>
					<?php endif; ?>
				</div>
				<form name="checkout" method="post"
						action="<?php echo esc_url( $checkout_url ); ?>"
						class="checkout woocommerce-checkout" enctype="multipart/form-data">

					<div class="sp-step-item sp-step-information active">
						<?php do_action( 'woocommerce_checkout_before_customer_details' ); ?>
						<?php $checkout->checkout_form_billing(); ?>

						<?php
						if ( true === WC()->cart->needs_shipping_address() ) {
							$checkout->checkout_form_shipping();
						}
						?>
						<?php wp_nonce_field( 'woocommerce-process_checkout', 'woocommerce-process-checkout-nonce' ); ?>

						<?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>
					</div>

					<div class="sp-step-item sp-step-shipping">
						<h2><?php echo esc_html__( 'Shipping Method', 'shop-press' ); ?></h2>
						<?php
						if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() ) {
							do_action( 'woocommerce_review_order_before_shipping' );
							wc_cart_totals_shipping_html();
							do_action( 'woocommerce_review_order_after_shipping' );
						}
						?>
					</div>

					<div class="sp-step-item sp-step-payment">
						<h2><?php echo esc_html__( 'Choose a Payment Gateway', 'shop-press' ); ?></h2>
						<?php woocommerce_checkout_payment(); ?>
					</div>

				</form>
				<div class="sp-shopify-nav">
					<ul class="list-sp-nav-button">
						<li class="active">
							<?php if ( ! $is_shipping_step ) : ?>
								<button class="sp-nav-button button-next" data-tab="3">
									<?php esc_html_e( 'Continue to payment', 'shop-press' ); ?>
								</button>
							<?php else : ?>
							<button class="sp-nav-button button-next" data-tab="2">
								<?php esc_html_e( 'Continue to shipping', 'shop-press' ); ?>
							</button>
							<?php endif; ?>
							<button class="sp-nav-button button-return" data-tab="0">
								<?php esc_html_e( 'Return to cart', 'shop-press' ); ?>
							</button>
						</li>
						<li>
							<button class="sp-nav-button button-next" data-tab="3">
								<?php esc_html_e( 'Continue to payment', 'shop-press' ); ?>
							</button>
							<button class="sp-nav-button button-return" data-tab="1">
								<?php esc_html_e( 'Return to information', 'shop-press' ); ?>
							</button>
						</li>
						<li>
							<button class="sp-nav-button button-next" data-tab="4">
								<?php esc_html_e( 'Place order', 'shop-press' ); ?>
							</button>

							<?php if ( ! $is_shipping_step ) : ?>
								<button class="sp-nav-button button-return" data-tab="1">
									<?php esc_html_e( 'Return to information', 'shop-press' ); ?>
								</button>
							<?php else : ?>
								<button class="sp-nav-button button-return" data-tab="2">
									<?php esc_html_e( 'Return to shipping', 'shop-press' ); ?>
								</button>
							<?php endif; ?>
						</li>
					</ul>
				</div>
				<?php
				if ( $is_bottom_menu ) {
					?>
					<div class="sp-shopify-menu-cart">
						<?php
						echo wp_nav_menu(
							array(
								'menu_id' => $checkout_bottom_menu,
							)
						);
						?>
					</div>
				<?php } ?>
			</div>
			<div class="sp-shopify-review">
				<div class="sp-shopify-product-list">
					<?php
					foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
						$_product = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );

						if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_checkout_cart_item_visible', true, $cart_item, $cart_item_key ) ) {

							$thumbnail = $_product->get_image();
							$quantity  = apply_filters( 'woocommerce_checkout_cart_item_quantity', $cart_item['quantity'], $cart_item, $cart_item_key );

							$image = '<div class="product-image" style="width: 52px; height: 45px; display: inline-block; padding-right: 7px; vertical-align: middle;">'
								. $thumbnail
								. '<span>' . $quantity . '</span>' .
								'</div>';

							$name_item = '<div class="product-name" style="">'
								. $_product->get_name() .
								'</div>';

							$subtotal = '<div class="product-subtotal" style="">'
								. apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key ) .
								'</div>';

							$html = '<div class="product-row" style="">'
								. $image .
								$name_item .
								$subtotal .
								'</div>';

							?>
							<div class="sp-product">
								<?php echo $html; ?>
							</div>
							<?php
						}
					}
					?>
				</div>

				<?php woocommerce_order_review(); ?>
			</div>
		</div>

	</div>
</div>
