<?php
/**
 * Mini Cart Style 1
 *
 * @package ShopPress\Templates
 *
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;
?>
<div class="sp-mini-cart sp-mini-cart-style-1">
	<div class="sp-title"><?php esc_html_e( 'Cart', 'shop-press' ); ?></div>
	<?php
	if ( WC()->cart && ! WC()->cart->is_empty() ) :
		?>
		<ul class="sp-cart-items">
			<?php
			do_action( 'woocommerce_before_mini_cart_contents' );

			foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
				$_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
				$product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

				if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_widget_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
					$product_name      = apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key );
					$thumbnail         = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );
					$product_price     = apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key );
					$subtotal_price    = apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key );
					$product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
					?>
					<li class="sp-cart-item-pr <?php echo esc_attr( apply_filters( 'woocommerce_mini_cart_item_class', '', $cart_item, $cart_item_key ) ); ?>" data-cart_item_key="<?php echo esc_attr( $cart_item_key ); ?>">
						<span class="sp-cart-item-img">
							<?php if ( empty( $product_permalink ) ) : ?>
								<?php echo $thumbnail; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
							<?php else : ?>
								<a href="<?php echo esc_url( $product_permalink ); ?>">
									<?php echo $thumbnail; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
								</a>
							<?php endif; ?>
						</span>
						<div class="sp-mmceta">
							<?php if ( empty( $product_permalink ) ) : ?>
								<span class="sp-cart-item-pr-title">
									<?php echo wp_kses_post( $product_name ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
								</span>
								<?php else : ?>
								<a class="sp-cart-item-pr-title-link" href="<?php echo esc_url( $product_permalink ); ?>">
									<span class="sp-cart-item-pr-title">
										<?php echo wp_kses_post( $product_name ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
									</span>
								</a>
							<?php endif; ?>
							<div class="sp-cart-item-prices">
								<?php if ( ! empty( $display_quantity_input ) && $display_quantity_input ) : ?>
									<div class="sp-cart-item-quantity">
										<?php
										if ( $_product->is_sold_individually() ) {
											$min_quantity = 1;
											$max_quantity = 1;
										} else {
											$min_quantity = 0;
											$max_quantity = $_product->get_max_purchase_quantity();
										}

										$product_quantity = woocommerce_quantity_input(
											array(
												'input_name'   => "cart[{$cart_item_key}][qty]",
												'input_value'  => $cart_item['quantity'],
												'max_value'    => $max_quantity,
												'min_value'    => $min_quantity,
												'product_name' => $product_name,
											),
											$_product,
											false
										);

										echo apply_filters( 'woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item ); // PHPCS: XSS ok.
										?>
									</div>
								<?php else : ?>
									<div class="sp-cart-item-subtotal-quantity-price">
										<?php echo apply_filters( 'woocommerce_widget_cart_item_quantity', '<span class="quantity">' . sprintf( '%s &times; %s', $product_price, $cart_item['quantity'] ) . '</span>', $cart_item, $cart_item_key ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
									</div>
								<?php endif; ?>
								<div class="sp-cart-item-subtotal-price">
									<?php echo $subtotal_price; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
									<?php if ( ! empty( $display_quantity_input ) && ! $display_quantity_input ) : ?>
										<div class="sp-cart-item-subtotal-quantity-price">
											<?php echo apply_filters( 'woocommerce_widget_cart_item_quantity', '<span class="quantity">' . sprintf( '%s &times; %s', $product_price, $cart_item['quantity'] ) . '</span>', $cart_item, $cart_item_key ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
										</div>
									<?php endif; ?>
								</div>
							</div>
						</div>
						<?php
						echo apply_filters( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
							'woocommerce_cart_item_remove_link',
							sprintf(
								'<div class="sp-cart-item-rm"><a href="%s" class="remove remove_from_cart_button" aria-label="%s" data-product_id="%s" data-cart_item_key="%s" data-product_sku="%s"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 36 36"> <g transform="translate(-830 -22)"> <circle cx="18" cy="18" r="18" transform="translate(830 22)" fill="#f1f2f3"></circle> <path d="M9.79.21a.717.717,0,0,1,0,1.014L5.956,5.057,9.674,8.776A.717.717,0,1,1,8.66,9.79L4.942,6.072l-3.6,3.6A.717.717,0,0,1,.326,8.66l3.6-3.6L.21,1.34A.717.717,0,1,1,1.224.326L4.942,4.043,8.776.21A.717.717,0,0,1,9.79.21Z" transform="translate(843 35)" fill="#959ca7"></path> </g> </svg></a></div>',
								esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
								esc_attr__( 'Remove this item', 'shop-press' ),
								esc_attr( $product_id ),
								esc_attr( $cart_item_key ),
								esc_attr( $_product->get_sku() )
							),
							$cart_item_key
						);
						?>
					</li>
					<?php
				}
			}

			do_action( 'woocommerce_mini_cart_contents' );
			?>
		</ul>

		<div class="sp-cart-details">
			<p class="woocommerce-mini-cart__total total">
				<?php
				/**
				 * Hook: woocommerce_widget_shopping_cart_total.
				 *
				 * @hooked woocommerce_widget_shopping_cart_subtotal - 10
				 */
				do_action( 'woocommerce_widget_shopping_cart_total' );
				?>
			</p>

			<?php do_action( 'shoppress/templates/mini-cart/before_buttons' ); ?>

			<?php do_action( 'woocommerce_widget_shopping_cart_before_buttons' ); ?>

			<p class="woocommerce-mini-cart__buttons buttons"><?php do_action( 'woocommerce_widget_shopping_cart_buttons' ); ?></p>

			<?php do_action( 'woocommerce_widget_shopping_cart_after_buttons' ); ?>
		</div>


	<?php else : ?>

		<div class="woocommerce-mini-cart__empty-message"><?php esc_html_e( 'No products in the cart.', 'shop-press' ); ?></div>

	<?php endif; ?>
</div>
