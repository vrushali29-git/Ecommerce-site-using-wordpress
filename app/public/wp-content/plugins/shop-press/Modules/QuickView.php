<?php
/**
 * Quick View.
 *
 * @package ShopPress
 */

namespace ShopPress\Modules;

defined( 'ABSPATH' ) || exit;

use Elementor\Plugin;
use ShopPress\Modules\VariationSwatches;

class QuickView {
	/**
	 * Init hooks.
	 *
	 * @since 1.2.0
	 */
	public static function init() {
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'enqueue' ) );
		add_action( 'wp_ajax_quick_view_ajax', array( __CLASS__, 'quick_view_ajax' ), 99 );
		add_action( 'wp_ajax_nopriv_quick_view_ajax', array( __CLASS__, 'quick_view_ajax' ), 99 );
		add_action( 'wp_footer', array( __CLASS__, 'footer_content' ) );
		add_action( 'shoppress_quick_view_before_content', array( __CLASS__, 'essensial_scripts' ) );
		add_action( 'shoppress_quick_view_before_content', array( __CLASS__, 'variation_swatches_template' ) );
		add_filter( 'shoppress/elementor/widgets', array( __CLASS__, 'add_quick_view_widgets' ), 9 );

		if ( self::is_quick_view_builder() ) {

			add_action( 'shoppress_quick_view', array( __CLASS__, 'quick_view_content' ) );
		} elseif ( self::is_static_quick_view() ) {

			add_action( 'shoppress_quick_view_content', array( __CLASS__, 'title' ) );
			add_action( 'shoppress_quick_view_content', array( __CLASS__, 'rating' ) );
			add_action( 'shoppress_quick_view_content', array( __CLASS__, 'price' ) );
			add_action( 'shoppress_quick_view_content', array( __CLASS__, 'add_to_cart' ) );
			add_action( 'shoppress_quick_view_images', array( __CLASS__, 'images' ) );
			// add_action( 'shoppress_quick_view_before_content', array( __CLASS__, 'variation_swatches_template' ) );
			add_action( 'shoppress_quick_view_content', array( __CLASS__, 'social_share' ) );
		}

		$quick_view_location_archive = sp_get_module_settings( 'quick_view', 'location_products_loop' )['value'] ?? '';
		if ( ! empty( $quick_view_location_archive ) ) {
			add_action( $quick_view_location_archive, array( __CLASS__, 'static_quick_view_output' ) );
		}
	}

	/**
	 * Adds the quick view widgets to the existing list of widgets.
	 *
	 * @param array $widgets The array of widgets.
	 *
	 * @since  1.2.0
	 *
	 * @return array The updated array of widgets.
	 */
	public static function add_quick_view_widgets( $widgets ) {
		$widgets['loop-quick-view'] = array(
			'editor_type' => 'loop',
			'class_name'  => 'LoopBuilder\QuickView',
			'is_pro'      => false,
			'path_key'    => 'loop/quick-view',
		);

		return $widgets;
	}

	/**
	 * Check if quick view builder is enabled.
	 *
	 * @since 1.1.0
	 *
	 * @return bool
	 */
	public static function is_quick_view_builder() {

		if ( sp_get_template_settings( 'quick_view', 'status' ) && sp_get_template_settings( 'quick_view', 'page_builder' ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Check static compare.
	 *
	 * @since 1.7.
	 *
	 * @return bool
	 */
	public static function is_static_quick_view() {

		if ( sp_get_module_settings( 'quick_view', 'status' ) && ! self::is_quick_view_builder() ) {
			return true;
		}

		return false;
	}

	/**
	 * Enqueue Scripts.
	 *
	 * @since 1.0.0
	 */
	public static function enqueue() {

		if ( ( is_page() || is_woocommerce() || is_tax( 'shoppress_brand' ) ) && ! is_checkout() ) {

			wp_enqueue_script( 'sp-quickview' );
			wp_enqueue_style( 'sp-pr-general' );

			if ( is_rtl() ) {
				wp_enqueue_style( 'sp-pr-general-rtl' );
			}

			wp_enqueue_style( 'sp-quickview' );

			if ( is_rtl() ) {
				wp_enqueue_style( 'sp-quickview-rtl' );
			}

			wp_enqueue_script( 'flexslider' );

			if ( current_theme_supports( 'wc-product-gallery-zoom' ) ) {
				wp_enqueue_script( 'zoom' );
			}

			if ( current_theme_supports( 'wc-product-gallery-lightbox' ) ) {
				wp_enqueue_script( 'photoswipe-ui-default' );
				wp_enqueue_style( 'photoswipe-default-skin' );

				if ( has_action( 'wp_footer', 'woocommerce_photoswipe' ) === false ) {
					add_action( 'wp_footer', 'woocommerce_photoswipe', 15 );
				}
			}

			wp_enqueue_script( 'wc-single-product' );

			if ( self::is_quick_view_builder() ) {
				wp_enqueue_style( 'elementor-frontend' );
			}
		}
	}

	/**
	 * Setup quick view data.
	 *
	 * @since 1.3.7
	 *
	 * @return \WC_Product
	 */
	public static function setup_quick_view_data() {

		global $shoppress_product_variation;
		$shoppress_product_variation = false;

		$product_id = intval( sanitize_text_field( $_POST['product_id'] ) );
		$_product   = wc_get_product( $product_id );
		$parent_id  = $_product->get_parent_id();
		if ( $parent_id ) {
			$shoppress_product_variation = $_product;
			$_product                    = wc_get_product( $parent_id );
		}

		global $post, $product;

		$post    = get_post( $_product->get_id() );
		$product = $_product;

		setup_postdata( $post );

		return $product;
	}

	/**
	 * Returns the content of the page that has been selected as the quick view.
	 *
	 * @since 1.1.0
	 */
	public static function quick_view_content() {

		if ( self::is_quick_view_builder() ) {

			$product = self::setup_quick_view_data();

			do_action( 'shoppress_quick_view_before_content', $product );
			$quick_view_page = sp_get_template_settings( 'quick_view', 'page_builder' );
			?>
			<div id="product-<?php the_ID(); ?>" <?php wc_product_class( 'sp-qv-wrap', $product ); ?>>
				<?php
					// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					echo sp_get_builder_content( $quick_view_page );
				?>
			</div>
			<?php
			do_action( 'shoppress_quick_view_after_content', $product );
		}
	}

	/**
	 * Footer content.
	 *
	 * @since 1.0.0
	 */
	public static function footer_content() {

		if ( is_woocommerce() || is_page() ) {
			?>
				<div id="sp-quick-view-overlay" style="display: none;">
					<div id="sp-quick-view-content" class="woocommerce">
						<div id="sp-close-quick-view"> <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 10 10" > <path d="M9.79.21a.717.717,0,0,1,0,1.014L5.956,5.057,9.674,8.776A.717.717,0,0,1,8.66,9.79L4.942,6.072l-3.6,3.6A.717.717,0,0,1,.326,8.66l3.6-3.6L.21,1.34A.717.717,0,0,1,1.224.326L4.942,4.043,8.776.21A.717.717,0,0,1,9.79.21Z" fill="#7f8da0" /> </svg> </div>
						<div id="sp-quick-view-html"></div>
					</div>
				</div>
			<?php
		}
	}

	/**
	 * Quick View Ajax.
	 *
	 * @since 1.1.0
	 */
	public static function quick_view_ajax() {
		check_ajax_referer( 'shoppress_nonce', 'nonce' );

		$product = self::setup_quick_view_data();

		if ( ! is_a( $product, '\WC_Product' ) ) {
			wp_die();
		}

		$content = '';
		if ( self::is_quick_view_builder() ) {

			ob_start();
				do_action( 'shoppress_quick_view' );
			$content = ob_get_clean();
		} elseif ( self::is_static_quick_view() ) {

			$content = self::static_quick_view_content_output();
		}

		wp_send_json(
			array(
				'content' => $content,
			)
		);
	}

	/**
	 * Load essential scripts.
	 *
	 * @since 1.2.0
	 */
	public static function essensial_scripts() {
		echo '<script type="text/javascript" src="' . esc_url( plugins_url( 'assets/js/frontend/add-to-cart-variation.js', WC_PLUGIN_FILE ) ) . '"></script>';
		echo '<script type="text/javascript" src="' . esc_url( plugins_url( 'assets/js/frontend/single-product.js', WC_PLUGIN_FILE ) ) . '"></script>';
		echo '<script type="text/javascript" src="' . esc_url( SHOPPRESS_URL . 'public/modules/variation-swatches/front/js/sp-variation-swatches.js' ) . '"></script>';

		\WC_Frontend_Scripts::load_scripts();

		wp_print_styles( 'woocommerce-general' );
		wp_print_styles( 'sp-variation-swatches' );

		if ( is_rtl() ) {
			wp_print_styles( 'sp-variation-swatches-rtl' );
		}

		wp_print_styles( 'sp-single' );

		if ( is_rtl() ) {
			wp_print_styles( 'sp-single-rtl' );
		}
	}

	/**
	 * Product title.
	 *
	 * @since 1.0.0
	 */
	public static function title() {
		?>
			<h3 class="sp-qv-title"><?php esc_html_e( get_the_title() ); ?></h3>
		<?php
	}

	/**
	 * Product price.
	 *
	 * @since 1.0.0
	 */
	public static function price() {
		global $product;
		?>
		<div class="sp-price-wrapper">
			<p class="<?php echo esc_attr( apply_filters( 'woocommerce_product_price_class', 'price' ) ); ?>">
				<?php echo wp_kses_post( $product->get_price_html() ); ?>
			</p>
		</div>
		<?php
	}

	/**
	 * Product rating.
	 *
	 * @since 1.0.0
	 */
	public static function rating() {
		$args = array(
			'show_review_counter' => 'yes',
		);

		sp_load_builder_template( 'single-product/product-rating', $args );
	}

	/**
	 * Checks if the current request is an AJAX request for the quick view feature.
	 *
	 * @since 1.2.0
	 *
	 * @return bool
	 */
	public static function is_quick_view_ajax() {
		return is_ajax() && isset( $_POST['action'] ) && 'quick_view_ajax' === $_POST['action'];
	}

	/**
	 * Product add to cart.
	 *
	 * @since 1.0.0
	 */
	public static function add_to_cart() {
		global $product;

		?>
			<div class="sp-add-to-cart-wrapper product-<?php echo esc_attr( $product->get_type() ); ?>">
				<?php
				switch ( $product->get_type() ) {
					case 'simple':
						woocommerce_simple_add_to_cart();
						break;

					case 'grouped':
						woocommerce_grouped_add_to_cart();
						break;

					case 'variable':
						woocommerce_variable_add_to_cart();
						break;

					case 'external':
						woocommerce_external_add_to_cart();
						break;
				}
				?>
			</div>
		<?php
	}

	/**
	 * Product images.
	 *
	 * @since 1.0.0
	 */
	public static function images() {

		if ( ! function_exists( 'wc_get_gallery_image_html' ) ) {
			return;
		}

		global $post, $product;

		$columns = 4;

		$post_thumbnail_id = $product->get_image_id();

		$wrapper_classes = apply_filters(
			'woocommerce_single_product_image_gallery_classes',
			array(
				'woocommerce-product-gallery',
				'woocommerce-product-gallery--' . ( $post_thumbnail_id ? 'with-images' : 'without-images' ),
				'woocommerce-product-gallery--columns-' . absint( $columns ),
				'images',
			)
		);
		?>
		<div class="sp-images">
			<div class="<?php echo esc_attr( implode( ' ', array_map( 'sanitize_html_class', $wrapper_classes ) ) ); ?>" data-columns="<?php echo esc_attr( $columns ); ?>">
				<figure class="woocommerce-product-gallery__wrapper">
					<?php
					if ( $post_thumbnail_id ) {
						$html = wc_get_gallery_image_html( $post_thumbnail_id, true );
					} else {
						$html  = '<div class="woocommerce-product-gallery__image--placeholder">';
						$html .= sprintf( '<img src="%s" alt="%s" class="wp-post-image" />', esc_url( wc_placeholder_img_src( 'woocommerce_single' ) ), esc_html__( 'Awaiting product image', 'shop-press' ) );
						$html .= '</div>';
					}

					echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', $html, $post_thumbnail_id ); // phpcs:disable WordPress.XSS.EscapeOutput.OutputNotEscaped

					do_action( 'woocommerce_product_thumbnails' );

					?>
				</figure>
			</div>
		</div>
		<?php
	}

	/**
	 * Product thumbnails.
	 *
	 * @since 1.2.0
	 */
	public static function thumbnails() {
		// echo wp_kses_post( woocommerce_get_product_thumbnail( 'woocommerce_full_size' ) );
	}

	/**
	 * Icon.
	 *
	 * @since 1.0.0
	 */
	public static function get_icon() {
		return '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="14.25" viewBox="0 0 18 14.25"> <defs> <clipPath id="sp-quick-view-icon"> <rect width="18" height="14.25" transform="translate(615.623 4145)" fill="#959ca7"/> </clipPath> </defs> <g transform="translate(-615.623 -4145)" clip-path="url(#sp-quick-view-icon)"> <path d="M9.095,3a9,9,0,0,1,8.716,6.75,9,9,0,0,1-17.433,0A9,9,0,0,1,9.095,3Zm0,12A7.5,7.5,0,0,1,1.937,9.75a7.5,7.5,0,0,1,14.314,0A7.5,7.5,0,0,1,9.095,15Z" transform="translate(615.528 4142.75)" fill="#959ca7" fill-rule="evenodd"/> <path d="M14,11a3,3,0,1,1-3-3A3,3,0,0,1,14,11Zm-1.5,0A1.5,1.5,0,1,1,11,9.5,1.5,1.5,0,0,1,12.5,11Z" transform="translate(613.622 4141.5)" fill="#959ca7" fill-rule="evenodd"/> </g> </svg> ';
	}

	/**
	 * Quick view output.
	 *
	 * @since 1.0.0
	 */
	public static function static_quick_view_output() {
		global $shoppress_cross_sell_popup;
		// Do not display the variations in cross-sell popup
		if ( $shoppress_cross_sell_popup ) {
			return;
		}

		$product_id = get_the_ID();

		?>
		<div class="sp-quick-view" data-product_id="<?php echo esc_attr( $product_id ); ?>">
			<i class="sp-quick-view-icon">
				<?php echo wp_kses( self::get_icon(), sp_allowd_svg_tags() ); ?>
			</i>
			<span class="sp-quick-view-label"><?php esc_html_e( 'Quick View', 'shop-press' ); ?></span>
		</div>
		<?php
	}

	/**
	 * Variation Swatches template.
	 *
	 * @since 1.2.0
	 */
	public static function variation_swatches_template() {

		// If variation is not activated return do not run the function anymore
		if ( ! sp_is_module_active( 'variation_swatches' ) ) {
			return false;
		}

		// Create instance of VariationSwatches\Frontend
		$VariationSwatches = new VariationSwatches\Frontend();

		// Run necessary hooks for filter the WC variation swatches
		$VariationSwatches->public_hook_definition();
	}

	/**
	 * Display social share links.
	 *
	 * @since 1.3.7
	 *
	 * @return void
	 */
	public static function social_share() {
		$is_active_share = sp_get_module_settings( 'quick_view', 'social_share', false );
		if ( ! $is_active_share ) {
			return;
		}

		$_links = sp_get_module_settings( 'quick_view', 'social_media' );
		$_links = array_column( $_links, 'value' );

		$args = array(
			'type'  => 'icon-label',
			'label' => __( 'Share', 'shop-press' ),
			'links' => $_links,
		);

		include sp_get_template_path( 'general/product-sharing' );
	}

	/**
	 * Quick view content output.
	 *
	 * @since 1.0.0
	 */
	public static function static_quick_view_content_output() {
		ob_start();

		load_template( sp_get_template_path( 'quick-view/quick-view-content' ) );

		$content = ob_get_contents();

		ob_end_clean();

		return $content;
	}
}
