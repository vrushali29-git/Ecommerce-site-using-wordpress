<?php
/**
 * Checkout Page.
 *
 * @package ShopPress
 */

namespace ShopPress\Templates\Checkout;

defined( 'ABSPATH' ) || exit;

class Main {
	/**
	 * Init.
	 *
	 * @since 1.2.0
	 */
	public static function init() {
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'enqueue' ), 99 );

		if ( self::is_checkout_builder() ) {

			add_filter( 'template_include', array( __CLASS__, 'full_template' ) );
			add_filter( 'wc_get_template', array( __CLASS__, 'checkout_templates' ), 10, 2 );
			add_action( 'shoppress_checkout_before_content', array( __CLASS__, 'checkout_before_content' ) );
			add_action( 'shoppress_checkout', array( __CLASS__, 'checkout_content' ) );
			add_action( 'shoppress_checkout_after_content', array( __CLASS__, 'checkout_after_content' ) );
			add_action( 'elementor/preview/init', array( __CLASS__, 'fix_form_tag' ) );
		}
	}

	/**
	 * Check checkout builder.
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	public static function is_checkout_builder() {
		return ( sp_get_template_settings( 'checkout', 'status' ) && sp_get_template_settings( 'checkout', 'page_builder' ) );
	}

	/**
	 * Enqueue scripts.
	 *
	 * @since 1.1.0
	 */
	public static function enqueue() {

		if ( is_checkout() && self::is_checkout_builder() ) {

			$builder_id = sp_get_template_settings( 'checkout', 'page_builder' );

			if ( 'block_editor' === sp_get_builder_type( $builder_id ) ) {

				add_filter(
					'styler/block_editor/post_id',
					function () {
						return sp_get_template_settings( 'checkout', 'page_builder' );
					}
				);
			}

			wp_enqueue_style( 'sp-checkout' );

			if ( is_rtl() ) {
				wp_enqueue_style( 'sp-checkout-rtl' );
			}
		}
	}

	/**
	 * Get custom type.
	 *
	 * @since 1.1.0
	 */
	public static function get_custom_type_from_page_id() {
		$page_id     = get_the_ID();
		$post_type   = get_post_type();
		$custom_type = $page_id ? get_post_meta( $page_id, 'custom_type', true ) : 'undefined';

		return $custom_type;
	}

	/**
	 * Fix form style.
	 *
	 * @since 1.1.0
	 */
	public static function fix_form_tag() {

		if ( 'checkout' === self::get_custom_type_from_page_id() ) {
			?>
				<form name="checkout" method="post" class="checkout woocommerce-checkout sp-checkout" action="<?php echo esc_url( wc_get_checkout_url() ); ?>" enctype="multipart/form-data">
			<?php
		}
	}

	/**
	 * Returns the content of the page that has been selected as the checkout page.
	 *
	 * @since 1.1.0
	 */
	public static function checkout_content() {

		if ( self::is_checkout_builder() ) {

			$checkout_page = sp_get_template_settings( 'checkout', 'page_builder' );

			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo sp_get_builder_content( $checkout_page );
		}
	}

	/**
	 * After checkout.
	 *
	 * @since 1.1.0
	 */
	public static function checkout_after_content() {
		echo '</form>';
		echo '</div>';
	}

	/**
	 * Before checkout.
	 *
	 * @since 1.1.0
	 */
	public static function checkout_before_content() {
		echo '<div id="shoppress-wrap" class="shoppress-wrap">';
		echo '
		<form class="checkout_coupon woocommerce-form-coupon" method="post" style="display: none">
			<p>If you have a coupon code, please apply it below.</p>
			<p class="form-row form-row-first">
				<input type="text" name="coupon_code" class="input-text" placeholder="Coupon code" id="coupon_code" value="">
			</p>
			<p class="form-row form-row-last">
				<button type="submit" class="button" name="apply_coupon" value="Apply coupon">Apply coupon</button>
			</p>
			<div class="clear"></div>
		</form>
		<script>
			jQuery(document).ready(function () {
				jQuery( ".sp-checkout-form-coupon button" ).click(function(e) {
					e.preventDefault();
					var value = jQuery(".sp-checkout-form-coupon #coupon_code").val();
					jQuery(".checkout_coupon #coupon_code").val(value)
					jQuery( ".checkout_coupon button" ).trigger( "click" );
				});
				jQuery( ".sp-checkout-form-coupon-toggle a.sp-show-coupon" ).click(function(e) {
					e.preventDefault();
					show_coupon_code();
				});
				function show_coupon_code(){
					jQuery( ".sp-checkout-form-coupon" ).slideToggle( 400, function() {
						jQuery( ".sp-checkout-form-coupon" ).find( ":input:eq(0)" ).trigger( "focus" );
					});
					return false
				}
			});
		</script>
		';
		?>
			<form name="checkout" method="post" class="checkout woocommerce-checkout sp-checkout" action="<?php echo esc_url( wc_get_checkout_url() ); ?>" enctype="multipart/form-data">
		<?php
	}

	/**
	 * Get full template.
	 *
	 * @since 1.2.0
	 */
	public static function full_template( $template ) {

		if ( is_checkout() ) {
			$template = sp_get_template_path( 'full-template' );
		}

		return $template;
	}

	/**
	 * Checkout templates.
	 *
	 * @since 1.1.0
	 */
	public static function checkout_templates( $located, $template_name ) {

		if ( 'checkout/form-checkout.php' === $template_name ) {
			$located = sp_get_template_path( 'checkout/checkout' );
		}

		return $located;
	}
}
