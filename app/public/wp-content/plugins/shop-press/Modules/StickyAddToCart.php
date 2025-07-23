<?php
/**
 * Sticky Add To Cart.
 *
 * @package ShopPress
 */

namespace ShopPress\Modules;

defined( 'ABSPATH' ) || exit;

use ShopPress\Modules\CatalogMode;

class StickyAddToCart {
	/**
	 * Init.
	 *
	 * @since 1.2.0
	 */
	public static function init() {

		if ( CatalogMode::is_catalog_mode() ) {
			return;
		}

		add_action( 'wp_footer', array( __CLASS__, 'sticky_add_to_cart_template' ), 99 );
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'enqueue' ) );
		add_action( 'body_class', array( __CLASS__, 'body_class' ) );
	}

	/**
	 * Check if sticky add to cart is enabled.
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	public static function is_sticky_add_to_cart() {

		if ( ! empty( sp_get_module_settings( 'sticky_add_to_cart', 'status' ) ) ) {
			return true;
		}

		return false;
	}

	/**
	 * enqueue style.
	 *
	 * @since 1.0.0
	 */
	public static function enqueue() {

		if ( is_product() && self::is_sticky_add_to_cart() ) {
			wp_enqueue_style( 'sp-sticky-add-to-cart' );

			if ( is_rtl() ) {
				wp_enqueue_style( 'sp-sticky-add-to-cart-rtl' );
			}
		}
	}

	/**
	 * Sticky add to cart Template.
	 *
	 * @since 1.0.0
	 */
	public static function sticky_add_to_cart_template() {

		if ( ! sp_get_module_settings( 'sticky_add_to_cart', 'status' ) || ! is_product() ) {
			return;
		}

		$hide_on_mobile = sp_get_module_settings( 'sticky_add_to_cart', 'hide_on_mobile' );
		global $shoppress_sticky_add_to_cart;
		$shoppress_sticky_add_to_cart = true;
		global $product;

		?>
		<div class="sp-sticky-add-to-cart <?php echo $hide_on_mobile ? 'sp-hide-on-mobile' : ''; ?>">
			<div class="sp-left-content-wrapper">
				<div class="sticky-add-to-cart-image">
					<?php echo woocommerce_get_product_thumbnail(); ?>
				</div>
				<div class="sp-title-price-wrapper">
					<div class="sticky-add-to-cart-title">
						<?php echo esc_html( $product->get_title() ); ?>
					</div>
					<div class="sticky-add-to-cart-price">
						<?php echo wp_kses_post( $product->get_price_html() ); ?>
					</div>
				</div>
			</div>
			<div class="sticky-add-to-cart-btn product">
				<?php
				if ( $product->is_type( 'simple' ) ) {
					woocommerce_simple_add_to_cart();
				} else {
					echo '<script>
							jQuery(document).ready(function($){

								$(".sp-sticky-scroll-add-to-cart").on("click",function(e){
									e.preventDefault();
									jQuery([document.documentElement, document.body]).animate({
										scrollTop: $(".single_add_to_cart_button").closest("form").offset().top
									}, 1000);
									return false;
								 });
							});
						 </script>';
					echo '<a href="' . esc_url( $product->add_to_cart_url() ) . '" class="single_add_to_cart_button sp-sticky-scroll-add-to-cart button alt">' . ( true == $product->is_type( 'variable' ) || true == $product->is_type( 'grouped' ) ? esc_html__( 'Select Options', 'shop-press' ) : $product->single_add_to_cart_text() ) . '</a>';
				}
				?>
			</div>
		</div>
		<?php

		$shoppress_sticky_add_to_cart = false;
	}

	/**
	 * Sticky add to cart Body class.
	 *
	 * @since 1.0.0
	 */
	public static function body_class( $classes ) {

		$hide_on_mobile = sp_get_module_settings( 'sticky_add_to_cart', 'hide_on_mobile' );

		if ( is_product() && $hide_on_mobile ) {
			return array_merge( $classes, array( 'sp-active-sticky-add-to-cart', 'sp-hide-sticky-add-to-cart' ) );

		} elseif ( is_product() && ! $hide_on_mobile ) {
			return array_merge( $classes, array( 'sp-active-sticky-add-to-cart' ) );
		}

		return $classes;
	}
}
